import cv2
from ultralytics import YOLO

model = YOLO("./my_model/best.pt")

video_path = 'videoplayback.mp4'
cap = cv2.VideoCapture(video_path)
if not cap.isOpened():
    print("Gagal membuka video.")
    exit()

garis_x = 230
fps = cap.get(cv2.CAP_PROP_FPS)
delay = int(1000 / fps)
in_count = 0
out_count = 0
track_memory = {}  # track_id: [prev_x, curr_x]
next_id = 0

def iou(box1, box2):
    """Hitung IOU antar 2 box"""
    xA = max(box1[0], box2[0])
    yA = max(box1[1], box2[1])
    xB = min(box1[2], box2[2])
    yB = min(box1[3], box2[3])
    interArea = max(0, xB - xA) * max(0, yB - yA)
    box1Area = (box1[2] - box1[0]) * (box1[3] - box1[1])
    box2Area = (box2[2] - box2[0]) * (box2[3] - box2[1])
    return interArea / float(box1Area + box2Area - interArea)

while True:
    ret, frame = cap.read()
    if not ret:
        print("Gagal membaca frame.")
        break

    results = model(frame, conf=0.2, verbose=False)[0]
    current_tracks = []

    for box in results.boxes:
        x1, y1, x2, y2 = map(int, box.xyxy[0])
        cx = int((x1 + x2) / 2)
        cy = int((y1 + y2) / 2)

        matched_id = None
        for track_id, pos in track_memory.items():
            prev_cx = pos[-1]
            if abs(prev_cx - cx) < 40:  # threshold
                matched_id = track_id
                break

        if matched_id is None:
            matched_id = next_id
            next_id += 1

        # Simpan posisi
        if matched_id in track_memory:
            track_memory[matched_id].append(cx)
            if len(track_memory[matched_id]) > 2:
                track_memory[matched_id] = track_memory[matched_id][-2:]
        else:
            track_memory[matched_id] = [cx]

        # Hitung crossing garis
        if len(track_memory[matched_id]) >= 2:
            prev_x, curr_x = track_memory[matched_id]
            if prev_x < garis_x and curr_x >= garis_x:
                in_count += 1
                print(f"IN ↑ ID {matched_id}")
                track_memory[matched_id] = [-9999, -9999]
            elif prev_x > garis_x and curr_x <= garis_x:
                out_count += 1
                print(f"OUT ↓ ID {matched_id}")
                track_memory[matched_id] = [-9999, -9999]

        # Gambar box dan titik tengah
        cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 255, 0), 2)
        cv2.circle(frame, (cx, cy), 4, (255, 0, 0), -1)
        cv2.putText(frame, f"ID #{matched_id} KARUNG", (x1, y1 - 5), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255,255,0), 1)

    if results.boxes:
        print("Deteksi:", len(results.boxes))

    # annotated_frame = results.plot()
    # if annotated_frame is None:
    #     print("Tidak ada anotasi yang ditemukan.")
    #     continue

    # cv2.line(annotated_frame, (garis_x, 0), (garis_x, annotated_frame.shape[0]), (255, 255, 255), 2)
    # Garis vertikal & count
    cv2.line(frame, (garis_x, 0), (garis_x, frame.shape[0]), (255, 255, 255), 2)
    cv2.putText(frame, f"in: {in_count}", (garis_x + 10, 300), cv2.FONT_HERSHEY_SIMPLEX, 1, (255,255,255), 2)
    cv2.putText(frame, f"out: {out_count}", (garis_x + 10, 340), cv2.FONT_HERSHEY_SIMPLEX, 1, (255,255,255), 2)

    cv2.imshow("CCTV View", frame)

    if cv2.waitKey(delay) == 27:
        break
    # Tekan ESC untuk keluar
    # if cv2.waitKey(1) == 27:
    #     break

cap.release()
cv2.destroyAllWindows()
