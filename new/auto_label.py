import os
from ultralytics import YOLO

model = YOLO("./my_model/best.pt")  # file model hasil training kamu
frames_dir = "frames"  # direktori berisi frame gambar
labels_dir = "frames/labels"  # direktori untuk menyimpan anotasi
os.makedirs(labels_dir, exist_ok=True)

image_files = sorted([f for f in os.listdir(frames_dir) if f.endswith(".jpg")])

for image_file in image_files:
    image_path = os.path.join(frames_dir, image_file)
    results = model(image_path, conf=0.1)[0]

    label_path = os.path.join(labels_dir, image_file.replace(".jpg", ".txt"))

    with open(label_path, "w") as f:
        for box in results.boxes:
            cls = int(box.cls[0])
            xywh = box.xywhn[0].tolist()  # normalized
            f.write(f"{cls} " + " ".join(f"{x:.6f}" for x in xywh) + "\n")

print("✅ Anotasi otomatis selesai disimpan di:", labels_dir)
