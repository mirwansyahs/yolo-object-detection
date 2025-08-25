import cv2

# RTSP URL Kamera
# rtsp_url = "rtsp://admin:admin@192.168.1.23:8554/Streaming/Channels/102"
# rtsp_url = "rtsp://admin:C0b@dulu@10.54.8.112:554/cam/realmonitor?channel=2&subtype=1&unicast=true&proto=Onvif"

# rtsp_url = "rtsp://admin:C0b@dulu@10.54.8.112:554/cam/realmonitor?channel=2&subtype=1&unicast=true&proto=Onvif"
rtsp_url = "rtsp://admin:C0b@dulu@10.54.8.208:554/cam/realmonitor?channel=1&subtype=1&unicast=true&proto=Onvif"
# rtsp_url = "rtsp://admin:C0b@dulu@10.54.8.112:7001/cam/realmonitor?channel=1&subtype=1&unicast=true&proto=Onvif"
# Load Haar Cascade untuk deteksi wajah
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + "haarcascade_frontalface_default.xml")

# Buka stream dari RTSP
cap = cv2.VideoCapture(rtsp_url)

if not cap.isOpened():
    print("Tidak dapat membuka RTSP stream")
    exit()

while True:
    ret, frame = cap.read()
    if not ret:
        print("Gagal membaca frame dari RTSP")
        break

    # Konversi ke grayscale
    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)

    # Deteksi wajah
    faces = face_cascade.detectMultiScale(gray, scaleFactor=1.1, minNeighbors=5, minSize=(30, 30))

    # Gambar kotak di sekitar wajah
    for (x, y, w, h) in faces:
        cv2.rectangle(frame, (x, y), (x+w, y+h), (0, 255, 0), 2)

    mirrored = cv2.flip(frame, 1)
    # Tampilkan hasil
    cv2.imshow("Face Detection RTSP", mirrored)

    # Tekan 'q' untuk keluar
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# Lepaskan resource
cap.release()
cv2.destroyAllWindows()
