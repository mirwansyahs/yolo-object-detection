from ultralytics import YOLO
import cv2

model = YOLO("best.pt")
img = cv2.imread("frames/frame_0058.jpg")
results = model(img, conf=0.1)[0]

if len(results.boxes) == 0:
    print("❌ Tidak ada deteksi")
else:
    annotated = results.plot()
    cv2.imshow("Test Image", annotated)
    cv2.waitKey(0)
