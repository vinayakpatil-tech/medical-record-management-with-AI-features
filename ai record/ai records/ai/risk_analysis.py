import pickle
import os
import numpy as np
import json

# Absolute path to the model
model_path = 'C:/xampp/htdocs/ai record/ai/health_model.pkl'

# Check if the model file exists
if not os.path.exists(model_path):
    print(f"Error: Model file does not exist at {model_path}")
    exit(1)

# Load the pre-trained health risk model
try:
    with open(model_path, 'rb') as file:
        model = pickle.load(file)
except Exception as e:
    print(f"Error loading model: {e}")
    exit(1)

def detect_health_risks(data):
    """
    Analyze the health risks based on patient data.
    Args:
        data (dict): Patient's medical data.
    Returns:
        dict: Health warnings or recommendations.
    """
    try:
        input_features = np.array([
            data.get('age', 0),
            data.get('blood_sugar', 0),
            data.get('bmi', 0),
        ]).reshape(1, -1)

        risk_score = model.predict(input_features)[0]

        if risk_score > 0.7:
            return {"warning": "High risk of diabetes. Consult a specialist."}
        elif risk_score > 0.4:
            return {"warning": "Moderate risk. Monitor health regularly."}
        else:
            return {"warning": "Low risk. Maintain a healthy lifestyle."}

    except Exception as e:
        print(f"Error during risk detection: {e}")
        return {"warning": "Error analyzing data."}

# Example for local testing (replace with actual data for real requests)
if __name__ == "__main__":
    sample_data = {"age": 45, "blood_sugar": 130, "bmi": 28}
    result = detect_health_risks(sample_data)
    print(json.dumps(result, indent=2))
