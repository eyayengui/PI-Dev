from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
import numpy as np
import pickle  # Import pickle module
from joblib import load

app = FastAPI()

# Load your trained model (Adjust the file path as needed)
model = load("random_forest_model.pkl")

class PredictionInput(BaseModel):
    features: list

@app.post("/fastapi/")
def predict(input_data: PredictionInput):
    try:
        features = np.array(input_data.features).reshape(1, -1)
        prediction = model.predict(features)
        return {"prediction": int(prediction[0])}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
