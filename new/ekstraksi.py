import cv2
import os

# Path video input
video_path = "videoplayback.mp4"

# Folder output
output_dir = "frames"
os.makedirs(output_dir, exist_ok=True)

# Buka video
cap = cv2.VideoCapture(video_path)

# Ekstrak setiap N frame
save_every_n_frame = 10  # contoh: setiap 10 frame (bisa diubah)

frame_num = 0
saved_count = 1

while True:
    ret, frame = cap.read()
    if not ret:
        break

    if frame_num % save_every_n_frame == 0:
        filename = os.path.join(output_dir, f"frame_{saved_count:04}.jpg")
        cv2.imwrite(filename, frame)
        print(f"✅ Saved: {filename}")
        saved_count += 1

    frame_num += 1

cap.release()
print("✅ Ekstraksi selesai.")
