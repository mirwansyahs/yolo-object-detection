const video = document.getElementById("video");
const counter = document.getElementById("counter");
const socket = new WebSocket("ws://localhost:8000/ws/detect");

navigator.mediaDevices.getUserMedia({ video: true }).then((stream) => {
  video.srcObject = stream;
  const canvas = document.createElement("canvas");
  const ctx = canvas.getContext("2d");

  setInterval(() => {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataURL = canvas.toDataURL("image/jpeg");
    socket.send(dataURL);
  }, 1000); // kirim tiap detik

  socket.onmessage = (event) => {
    counter.innerText = event.data;
  };
});
