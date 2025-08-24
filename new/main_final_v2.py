import os
os.environ["ULTRALYTICS_CONFIG_DIR"] = "C:/Users/Irwansyah/Favorites/Github/yolo-object-detection/new/yolo_config"

import cv2
import numpy as np
from ultralytics import settings, YOLO
import requests
from datetime import datetime, timezone
import pytz
import threading


# Untuk mengirim data ke API Laravel
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
            response = requests.post(url, json=payload, timeout=3)
            print(f"[API] Status: {response.status_code} | Response: {response.text}")
        except Exception as e:
            print(f"[API ERROR] {e}")

    # Kirim request tanpa menunggu
    threading.Thread(target=_send).start()

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

# Tracker sederhana
class EuclideanDistTracker:
    def __init__(self):
        self.center_points = {}
        self.id_count = 0

    def update(self, objects_rect):
        objects_bbs_ids = []
        for rect in objects_rect:
            x, y, w, h = rect
            cx = int((x + x + w) / 2)
            cy = int((y + y + h) / 2)

            same_object_detected = False
            for id, pt in self.center_points.items():
                dist = np.hypot(cx - pt[0], cy - pt[1])
                if dist < 40:
                    self.center_points[id] = (cx, cy)
                    objects_bbs_ids.append([x, y, w, h, id])
                    same_object_detected = True
                    break

            if not same_object_detected:
                self.center_points[self.id_count] = (cx, cy)
                objects_bbs_ids.append([x, y, w, h, self.id_count])
                self.id_count += 1

        new_center_points = {}
        for obj_bb_id in objects_bbs_ids:
            _, _, _, _, object_id = obj_bb_id
            center = self.center_points[object_id]
            new_center_points[object_id] = center

        self.center_points = new_center_points.copy()
        return objects_bbs_ids

settings.update({
    "runs_dir": "C:/Users/Irwansyah/Favorites/Github/yolo-object-detection/new/runs/detections",      # where results will be saved
})

print(settings)  # confirm changes

# Inisialisasi
model = YOLO("./my_model/best.pt")
rtsp_url = "rtsp://admin:admin@192.168.1.23:8554/Streaming/Channels/102"
# cap = cv2.VideoCapture("videoplayback.mp4")
# cap = cv2.VideoCapture("rtsp://admin:admin@192.168.1.23:8554/Streaming/Channels/102", cv2.CAP_FFMPEG)
cap = VideoStream("rtsp://admin:admin@192.168.1.23:8554/Streaming/Channels/102")

tracker = EuclideanDistTracker()
garis_x = 270
in_count = 0
out_count = 0
track_hist = {}

while True:
    ret, frame = cap.read()
    if not ret:
        break

    results = model(frame, conf=0.2, verbose=False)[0]
    det_boxes = []

    for box in results.boxes:
        x1, y1, x2, y2 = map(int, box.xyxy[0])
        det_boxes.append([x1, y1, x2 - x1, y2 - y1])
        print(f"Box: {det_boxes}")
        
    tracked_objects = tracker.update(det_boxes)

    for obj in tracked_objects:
        x, y, w, h, obj_id = obj
        cx = x + w // 2
        cy = y + h // 2

        if obj_id not in track_hist:
            track_hist[obj_id] = [cx]
        else:
            track_hist[obj_id].append(cx)
            if len(track_hist[obj_id]) >= 2:
                prev_x = track_hist[obj_id][-2]
                curr_x = track_hist[obj_id][-1]
                if prev_x < garis_x <= curr_x:
                    in_count += 1
                    print(f"IN ↑ ID {obj_id}")
                    track_hist[obj_id] = [9999, 9999]
                    
                    # Kirim ke API Laravel
                    send_to_api(camera_id=1, direction="in")
                elif prev_x > garis_x >= curr_x:
                    out_count += 1
                    print(f"OUT ↓ ID {obj_id}")
                    track_hist[obj_id] = [9999, 9999]

                    # Kirim ke API Laravel
                    send_to_api(camera_id=1, direction="out")

        cv2.rectangle(frame, (x, y), (x + w, y + h), (0, 255, 0), 2)
        cv2.circle(frame, (cx, cy), 4, (0, 0, 255), -1)
        cv2.putText(frame, f"ID #{obj_id} KARUNG", (x, y - 10), cv2.FONT_HERSHEY_SIMPLEX, 1, (255, 255, 0), 3)

    cv2.line(frame, (garis_x, 0), (garis_x, frame.shape[0]), (255, 255, 255), 2)
    cv2.putText(frame, f'in: {in_count}', (garis_x + 10, 300), cv2.FONT_HERSHEY_SIMPLEX, 1, (255, 255, 255), 2)
    cv2.putText(frame, f'out: {out_count}', (garis_x + 10, 340), cv2.FONT_HERSHEY_SIMPLEX, 1, (255, 255, 255), 2)

    cv2.imshow("CCTV Tracker", frame)
    if cv2.waitKey(1) & 0xFF == 27:
        break

cap.release()
cv2.destroyAllWindows()
