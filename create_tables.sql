CREATE DATABASE IF NOT EXISTS student_assessment;
USE student_assessment;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(15) NOT NULL,
    qualification VARCHAR(50) NOT NULL,
    grad_year INT NOT NULL CHECK (grad_year BETWEEN 1900 AND YEAR(CURRENT_DATE)),
    about TEXT,
    certifications TEXT,
    projects TEXT,
    skills TEXT,
    software TEXT,
    resume VARCHAR(255),
    experience INT CHECK (experience >= 0), -- Ensuring valid experience values
    soft_skills TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE quiz_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    score INT NOT NULL CHECK (score >= 0), -- Prevents negative scores
    answers JSON NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
