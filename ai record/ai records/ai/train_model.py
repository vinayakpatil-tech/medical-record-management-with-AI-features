import pickle
import numpy as np
from sklearn.linear_model import LogisticRegression
from sklearn.model_selection import train_test_split

# Example dataset (age, blood sugar, BMI, target risk [0: low, 1: high])
data = np.array([
    [25, 90, 22],
    [35, 150, 28],
    [45, 180, 30],
    [60, 200, 32],
    [50, 120, 26]
])
target = np.array([0, 1, 1, 1, 0])

# Train model
X_train, X_test, y_train, y_test = train_test_split(data, target, test_size=0.2, random_state=42)
model = LogisticRegression()
model.fit(X_train, y_train)

# Save model
with open('ai/health_model.pkl', 'wb') as model_file:
    pickle.dump(model, model_file)

print("Model trained and saved as health_model.pkl")
