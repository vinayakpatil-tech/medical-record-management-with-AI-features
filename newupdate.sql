Add this in phpmyadmin SQL 
ALTER TABLE users
ADD (
    security_question VARCHAR(255) DEFAULT NULL,
    security_answer VARCHAR(255) DEFAULT NULL
);
CREATE TABLE doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    security_question TEXT NOT NULL,
    security_answer TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
--if needed--
UPDATE doctors SET email = TRIM(email);
ALTER TABLE doctors MODIFY email VARCHAR(255) COLLATE utf8_general_ci;
ALTER TABLE medical_records
    ADD COLUMN doctor_email VARCHAR(255),
    ADD COLUMN access_level ENUM('view', 'edit') NOT NULL;

-- Optionally, add a foreign key constraint to ensure the doctor_email references the doctors table
ALTER TABLE medical_records
    ADD CONSTRAINT fk_doctor_email FOREIGN KEY (doctor_email) REFERENCES doctors(email);
...ALTER TABLE medical_records
    ADD CONSTRAINT fk_doctor_email FOREIGN KEY (doctor_email) REFERENCES doctors(email);
-- CREATE TABLE shared_records (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     record_id INT NOT NULL,
--     doctor_id INT NOT NULL,
--     access_level ENUM('view', 'edit') NOT NULL,
--     shared_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (record_id) REFERENCES medical_records(id),
--     FOREIGN KEY (doctor_id) REFERENCES users(id)
-- );
ALTER TABLE shared_records DROP FOREIGN KEY shared_records_ibfk_2;
UPDATE shared_records
SET doctor_id = 5  -- Replace with a valid doctor ID
WHERE doctor_id = 1;
ALTER TABLE shared_records
DROP FOREIGN KEY shared_records_ibfk_1,
ADD CONSTRAINT shared_records_ibfk_1
FOREIGN KEY (record_id) REFERENCES medical_records(id) ON DELETE CASCADE;
