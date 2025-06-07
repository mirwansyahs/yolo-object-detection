import cv2
import requests
from ultralytics import YOLO

URL = "http://localhost:8000/report"  # ganti IP jika kirim ke VPS
model = YOLO("yolov8n.pt")

karung_count = 0
cap = cv2.VideoCapture(1)


while True:
    ret, frame = cap.read()
    if not ret:
        break

    results = model(frame)
    # results[0].show()  # menampilkan hasil deteksi (gambar + box)
    # results[0].save(filename="deteksi.jpg")  # menyimpan hasil
    # print(results[0].names)  # daftar label yang dikenali
    # print(results[0].boxes.cls)  # class hasil deteksi

    karung = [r for r in results[0].boxes.data.tolist() if int(r[5]) == 0]  # class 0 = karung
    count = len(karung)

    if count > 0:
        karung_count += count
        print(f"Karung bertambah: {count}, Total: {karung_count}")

        # Kirim ke server
        requests.post(URL, json={"count": count, "source": "Client 1"})

    # Optional: tampilkan frame
    cv2.imshow("Deteksi Karung", frame)
    if cv2.waitKey(1) & 0xFF == ord("q"):
        break

cap.release()
cv2.destroyAllWindows()
