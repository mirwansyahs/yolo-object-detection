import cv2
from ultralytics import YOLO

# Load model YOLOv8
model = YOLO("karung.pt")  # Ganti dengan model kamu
cap = cv2.VideoCapture("videoplayback.mp4")

garis_x = 380  # posisi awal garis vertikal
step = 5       # step pergeseran manual garis

while True:
    ret, frame = cap.read()
    if not ret:
        break

    results = model(frame)[0]

    # Deteksi dan tandai karung
    for box in results.boxes:
        cls_id = int(box.cls[0])
        label = results.names[cls_id]
        x1, y1, x2, y2 = map(int, box.xyxy[0])

        if label.lower() == "karung":  # pastikan label sesuai
            cx = int((x1 + x2) / 2)
            cy = int((y1 + y2) / 2)

            # Tandai bounding box & titik tengah
            cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 255, 0), 2)
            cv2.circle(frame, (cx, cy), 4, (255, 0, 0), -1)

            # Tandai karung yang melewati garis
            if abs(cx - garis_x) < 5:
                cv2.putText(frame, "LEWAT!", (cx + 10, cy), cv2.FONT_HERSHEY_SIMPLEX, 0.6, (0, 0, 255), 2)

    # Tampilkan garis vertikal statis
    cv2.line(frame, (garis_x, 0), (garis_x, frame.shape[0]), (255, 255, 255), 2)
    cv2.putText(frame, f"Garis X: {garis_x}", (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 0.8, (255,255,255), 2)

    cv2.imshow("Kalibrasi Garis Karung", frame)

    key = cv2.waitKey(1)
    if key == 27:  # ESC
        break
    elif key == ord('a'):  # geser kiri
        garis_x -= step
    elif key == ord('d'):  # geser kanan
        garis_x += step

cap.release()
cv2.destroyAllWindows()
