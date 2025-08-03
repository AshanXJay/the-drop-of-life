-- SQL script to create the camps table and sample data
-- Run this in phpMyAdmin or MySQL command line

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS blood_donation;
USE blood_donation;

-- Create camps table
CREATE TABLE IF NOT EXISTS camps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO camps (name, location, start_date, end_date, description) VALUES
('Community Blood Drive', 'City Hospital', '2025-08-15', '2025-08-15', 'Annual community blood drive to support local hospitals and emergency services.'),
('University Campus Drive', 'State University', '2025-08-20', '2025-08-22', 'Three-day blood donation camp at the university campus for students and faculty.'),
('Emergency Response Camp', 'Red Cross Center', '2025-08-25', '2025-08-26', 'Emergency blood collection drive following recent medical emergencies in the area.');

-- Query to view all camps
-- SELECT * FROM camps ORDER BY start_date ASC;

-- Query to drop table (for testing only)
-- DROP TABLE IF EXISTS camps;
