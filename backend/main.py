from fastapi import FastAPI, Request
from pydantic import BaseModel
from fastapi.staticfiles import StaticFiles
from backend.db import DetectionLog, SessionLocal
from datetime import datetime
from fastapi import WebSocket
from sqlalchemy import func

connected_clients = []

app = FastAPI()

class ReportData(BaseModel):
    count: int
    source: str

@app.post("/report")
async def receive_report(data: ReportData):
    db = SessionLocal()
    log = DetectionLog(
        timestamp=datetime.now(),
        count=data.count,
        source=data.source,
        note="Dari sistem lokal"
    )
    db.add(log)
    db.commit()

    # Hitung total dan kirim ke semua WebSocket client
    total = db.query(func.sum(DetectionLog.count)).scalar() or 0
    db.close()

    for client in connected_clients:
        try:
            await client.send_text(str(total))
        except:
            connected_clients.remove(client)

    return {"message": "Data received"}


@app.get("/total")
def total_karung():
    db = SessionLocal()
    total = db.query(DetectionLog).with_entities(func.sum(DetectionLog.count)).scalar() or 0
    db.close()
    return {"total": total}

@app.websocket("/ws/total")
async def websocket_total(websocket: WebSocket):
    await websocket.accept()
    connected_clients.append(websocket)
    try:
        while True:
            await websocket.receive_text()  # Keep alive
    except:
        connected_clients.remove(websocket)
