document.getElementById('health-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const age = parseInt(document.getElementById('age').value);
    const bloodSugar = parseInt(document.getElementById('bloodSugar').value);
    const bmi = parseFloat(document.getElementById('bmi').value);
    const bloodPressure = document.getElementById('bloodPressure').value.split('/');

    let systolic = parseInt(bloodPressure[0]);
    let diastolic = parseInt(bloodPressure[1]);

    let resultText = "";
    let resultClass = "";

    // Initialize flags for normal health status
    let allNormal = true;

    // Age analysis
    if (age < 18) {
        resultText += "You are under 18. Please consult a doctor for guidance on your health.\n";
        resultClass = 'error';
        allNormal = false;
    } else {
        resultText += "Age: " + age + " (Good)\n";
    }

    // Blood Sugar Level analysis
    if (bloodSugar < 100) {
        resultText += "Blood Sugar Level: " + bloodSugar + " mg/dL (Normal)\n";
    } else if (bloodSugar >= 100 && bloodSugar <= 125) {
        resultText += "Blood Sugar Level: " + bloodSugar + " mg/dL (Pre-diabetes)\n";
        resultClass = 'warning';
        allNormal = false;
    } else {
        resultText += "Blood Sugar Level: " + bloodSugar + " mg/dL (Diabetes)\n";
        resultClass = 'error';
        allNormal = false;
    }

    // BMI analysis
    if (bmi < 18.5) {
        resultText += "BMI: " + bmi + " (Underweight)\n";
        resultClass = 'warning';
        allNormal = false;
    } else if (bmi >= 18.5 && bmi < 24.9) {
        resultText += "BMI: " + bmi + " (Normal weight)\n";
    } else if (bmi >= 25 && bmi < 29.9) {
        resultText += "BMI: " + bmi + " (Overweight)\n";
        resultClass = 'warning';
        allNormal = false;
    } else {
        resultText += "BMI: " + bmi + " (Obesity)\n";
        resultClass = 'error';
        allNormal = false;
    }

    // Blood Pressure analysis
    if (systolic < 120 && diastolic < 80) {
        resultText += "Blood Pressure: " + systolic + "/" + diastolic + " mmHg (Normal)\n";
    } else if ((systolic >= 120 && systolic < 130) && diastolic < 80) {
        resultText += "Blood Pressure: " + systolic + "/" + diastolic + " mmHg (Elevated)\n";
        resultClass = 'warning';
        allNormal = false;
    } else if ((systolic >= 130 && systolic <= 139) || (diastolic >= 80 && diastolic <= 89)) {
        resultText += "Blood Pressure: " + systolic + "/" + diastolic + " mmHg (Hypertension Stage 1)\n";
        resultClass = 'warning';
        allNormal = false;
    } else {
        // Don't add this message if all inputs are normal
        if (!allNormal) {
            resultText += "Blood Pressure: " + systolic + "/" + diastolic + " mmHg (Hypertension Stage 2 - Consult a doctor)\n";
            resultClass = 'error';
        }
    }

    // Suggest doctor visit if any parameter is abnormal
    if (allNormal) {
        resultText += "\nAll your health metrics are in the normal range. No need to meet a doctor unless you have other concerns!";
        resultClass = 'success';
    }

    // Display results with animation
    const resultDiv = document.getElementById('result');
    resultDiv.textContent = resultText;
    resultDiv.classList.add(resultClass);
    resultDiv.style.display = "block";
});
