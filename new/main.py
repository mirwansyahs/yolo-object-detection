import os
os.environ["ULTRALYTICS_CONFIG_DIR"] = "C:/Users/Irwansyah/Favorites/Github/yolo-object-detection/new/yolo_config"

import cv2
import numpy as np
from ultralytics import settings, YOLO
import requests
from datetime import datetime
import pytz
import threading
import time

# ----------- API worker (tetap) -----------
def send_to_api(camera_id, direction):
    def _send():
        jakarta = pytz.timezone("Asia/Jakarta")
        jakarta_time = datetime.now(jakarta)
        url = "http://localhost:8000/api/sack-movements"
        payload = {
            "camera_id": camera_id,
            "direction": direction,
            "detected_at": jakarta_time.isoformat(),
            "sack_count": 1,
            "image_path": ""
        }
        try:
            requests.post(url, json=payload, timeout=3)
        except Exception as e:
            # log ringan saja
            print(f"[API ERROR] {e}")
    threading.Thread(target=_send, daemon=True).start()

# ----------- Reader: selalu simpan frame TERBARU -----------
class VideoStream:
    def __init__(self, src):
        self.cap = cv2.VideoCapture(src, cv2.CAP_FFMPEG)
        self.cap.set(cv2.CAP_PROP_BUFFERSIZE, 1)
        self.lock = threading.Lock()
        self.frame = None
        self.ret = False
        self.running = True
        t = threading.Thread(target=self.update, daemon=True)
        t.start()

    def update(self):
        # buang frame lama, simpan terakhir
        while self.running:
            ret, f = self.cap.read()
            if ret:
                with self.lock:
                    self.ret = True
                    self.frame = f
            # opsional: purge buffer (kadang bantu)
            # while self.cap.grab():
            #     pass

    def read(self):
        with self.lock:
            return self.ret, None if self.frame is None else self.frame.copy()

    def release(self):
        self.running = False
        try:
            self.cap.release()
        except:
            pass

# ----------- Tracker sederhana (punya kamu) -----------
class EuclideanDistTracker:
    def __init__(self):
        self.center_points = {}
        self.id_count = 0

    def update(self, objects_rect):
        objects_bbs_ids = []
        for rect in objects_rect:
            x, y, w, h = rect
            cx = (x + x + w) // 2
            cy = (y + y + h) // 2
            same = False
            for id, (px, py) in list(self.center_points.items()):
                if np.hypot(cx - px, cy - py) < 40:
                    self.center_points[id] = (cx, cy)
                    objects_bbs_ids.append([x, y, w, h, id])
                    same = True
                    break
            if not same:
                self.center_points[self.id_count] = (cx, cy)
                objects_bbs_ids.append([x, y, w, h, self.id_count])
                self.id_count += 1

        new_cp = {}
        for _, _, _, _, oid in objects_bbs_ids:
            new_cp[oid] = self.center_points[oid]
        self.center_points = new_cp
        return objects_bbs_ids

# ----------- YOLO settings -----------
settings.update({
    "runs_dir": "C:/Users/Irwansyah/Favorites/Github/yolo-object-detection/new/runs/detections",
})

# Inisialisasi model (pakai GPU kalau ada)
model = YOLO("./my_model/best.pt")
try:
    model.to("cuda")
    use_cuda = True
except Exception:
    use_cuda = False

# ----------- RTSP (pakai SD) -----------
# rtsp_url = "rtsp://admin:admin@192.168.1.23:8554/Streaming/Channels/102"
rtsp_url = "rtsp://admin:C0b@dulu@10.54.8.112:554/cam/realmonitor?channel=1&subtype=1&unicast=true&proto=Onvif"
# rtsp_url = "rtsp://admin:C0b@dulu@10.54.8.208:554/cam/realmonitor?channel=1&subtype=1&unicast=true&proto=Onvif"
# rtsp_url = "rtsp://admin:C0b@dulu@10.54.8.208:7001/cam/realmonitor?channel=1&subtype=1&unicast=true&proto=Onvif"
cap = VideoStream(rtsp_url)

tracker = EuclideanDistTracker()
garis_x = 300
in_count = 0
out_count = 0
track_hist = {}

# throttle: deteksi tiap N frame (atau target fps inferensi) rtsp://10.54.8.112:554/cam/realmonitor?channel=1&subtype=0&unicast=true&proto=Onvif
SKIP_N = 2             # proses 1 dari 2 frame → kira-kira 50% beban
TARGET_INFER_FPS = 10  # alternatif: batasi infer ~10 FPS
last_infer_t = 0
frame_idx = 0

# kecilkan teks & garis biar drawing lebih ringan
FONT = cv2.FONT_HERSHEY_SIMPLEX

while True:
    ret, frame = cap.read()
    if not ret or frame is None:
        # jangan break keras; tunggu frame berikutnya
        if cv2.waitKey(1) & 0xFF == 27:
            break
        continue

    # --- Resize ke lebar 640 (pertahankan rasio) untuk percepat ---
    h, w = frame.shape[:2]
    if w > 640:
        new_w = 640
        new_h = int(h * (640 / w))
        frame_infer = cv2.resize(frame, (new_w, new_h), interpolation=cv2.INTER_LINEAR)
    else:
        frame_infer = frame

    # --- Throttle inferensi: skip frame atau target fps ---
    do_infer = False
    frame_idx += 1
    if frame_idx % SKIP_N == 0:
        do_infer = True
    # atau pakai batas fps infer
    now = time.time()
    if now - last_infer_t < 1.0 / TARGET_INFER_FPS:
        do_infer = False

    det_boxes = []
    if do_infer:
        # imgsz + half precision (kalau cuda)
        results = model.predict(
            frame_infer,
            conf=0.25,
            imgsz=640,
            device=0 if use_cuda else None,
            half=True if use_cuda else False,
            verbose=False
        )[0]
        last_infer_t = now

        # scale back boxes ke ukuran frame asli bila perlu
        sx = w / frame_infer.shape[1]
        sy = h / frame_infer.shape[0]

        for box in results.boxes:
            x1, y1, x2, y2 = box.xyxy[0].tolist()
            # kembalikan ke skala asli kalau tadi di-resize
            x1 = int(x1 * sx); y1 = int(y1 * sy); x2 = int(x2 * sx); y2 = int(y2 * sy)
            det_boxes.append([x1, y1, x2 - x1, y2 - y1])

        # NOTE: matikan print per-frame, itu bikin patah-patah
        # print(f"Box count: {len(det_boxes)}")

    # tetap update tracker walau det_boxes kosong (akan mempertahankan ID sebentar)
    tracked_objects = tracker.update(det_boxes)

    # gambar ringan
    for x, y, w0, h0, obj_id in tracked_objects:
        cx = x + w0 // 2
        cy = y + h0 // 2

        if obj_id not in track_hist:
            track_hist[obj_id] = [cx]
        else:
            track_hist[obj_id].append(cx)
            if len(track_hist[obj_id]) >= 2:
                prev_x = track_hist[obj_id][-2]
                curr_x = track_hist[obj_id][-1]
                if prev_x < garis_x <= curr_x:
                    in_count += 1
                    track_hist[obj_id] = [9999, 9999]
                    send_to_api(camera_id=1, direction="in")
                elif prev_x > garis_x >= curr_x:
                    out_count += 1
                    track_hist[obj_id] = [9999, 9999]
                    send_to_api(camera_id=1, direction="out")

        cv2.rectangle(frame, (x, y), (x + w0, y + h0), (0, 255, 0), 2)
        cv2.circle(frame, (cx, cy), 3, (0, 0, 255), -1)
        cv2.putText(frame, f"ID {obj_id}", (x, max(0, y - 6)), FONT, 0.5, (255, 255, 0), 1, cv2.LINE_AA)

    cv2.line(frame, (garis_x, 0), (garis_x, frame.shape[0]), (255, 255, 255), 1)
    cv2.putText(frame, f'in:{in_count} out:{out_count}', (garis_x + 8, 24), FONT, 0.6, (255, 255, 255), 1, cv2.LINE_AA)
    
    mirrored = cv2.flip(frame, 1)

    cv2.imshow("CCTV Tracker", mirrored)
    if cv2.waitKey(1) & 0xFF == 27:
        break

cap.release()
cv2.destroyAllWindows()
