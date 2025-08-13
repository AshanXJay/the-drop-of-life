-- ========================================
-- THE DROP OF LIFE - STREAMLINED DATABASE
-- Blood Donation Management System
-- ========================================
-- 
-- This file contains only the necessary database structure
-- and sample data for the blood donation management system.
-- Unused tables have been removed for simplicity.
--
-- @author Ashan Jayawardana
-- @version 2.0
-- @date 2025-08-13
-- ========================================

-- Drop existing tables if they exist
DROP TABLE IF EXISTS emergency_requests;
DROP TABLE IF EXISTS camp_registrations;
DROP TABLE IF EXISTS camps;
DROP TABLE IF EXISTS donors;

-- ========================================
-- CORE DATABASE STRUCTURE
-- ========================================

-- Donors table - Store blood donor information
CREATE TABLE donors (
    donor_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    blood_type VARCHAR(5),
    district VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Camps table - Store blood donation camp information
CREATE TABLE camps (
    camp_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(100) NOT NULL,
    district VARCHAR(100),
    date DATE NOT NULL,
    time TIME NOT NULL,
    organizer VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ========================================
-- SAMPLE DATA FOR TESTING
-- ========================================

-- Sample Donors Data (15 records)
INSERT INTO donors (name, email, phone, blood_type, district) VALUES
('Ashan Jayawardana', 'ashan@email.com', '0771234567', 'O+', 'Colombo'),
('Nimali Perera', 'nimali.perera@gmail.com', '0772345678', 'A+', 'Kandy'),
('Kasun Silva', 'kasun.silva@yahoo.com', '0773456789', 'B+', 'Gampaha'),
('Sanduni Fernando', 'sanduni.f@outlook.com', '0774567890', 'AB+', 'Kurunegala'),
('Thilaka Rajapaksa', 'thilaka.r@email.com', '0775678901', 'O-', 'Matara'),
('Priyantha Wickramasinghe', 'priyantha.w@gmail.com', '0776789012', 'A-', 'Anuradhapura'),
('Chamari Dissanayake', 'chamari.d@email.com', '0777890123', 'B-', 'Ratnapura'),
('Ruwan Gunasekara', 'ruwan.g@gmail.com', '0778901234', 'AB-', 'Badulla'),
('Sachini Mendis', 'sachini.m@email.com', '0779012345', 'O+', 'Galle'),
('Dinesh Karunaratne', 'dinesh.k@yahoo.com', '0770123456', 'A+', 'Jaffna'),
('Malika Seneviratne', 'malika.s@gmail.com', '0781234567', 'B+', 'Polonnaruwa'),
('Chaminda Lakmal', 'chaminda.l@email.com', '0782345678', 'O+', 'Hambantota'),
('Dilani Wijesinghe', 'dilani.w@outlook.com', '0783456789', 'A-', 'Kalutara'),
('Sampath Bandara', 'sampath.b@gmail.com', '0784567890', 'AB+', 'Kegalle'),
('Nayomi Ranasinghe', 'nayomi.r@email.com', '0785678901', 'O-', 'Nuwara Eliya');

-- Sample Camps Data (12 records - mix of past, current, and future)
INSERT INTO camps (name, location, district, date, time, organizer) VALUES
-- Past camps for analytics
('Blood Drive 2024 - Colombo', 'National Hospital Colombo', 'Colombo', '2024-12-15', '09:00:00', 'Red Cross Society'),
('Life Saver Camp - Kandy', 'Teaching Hospital Kandy', 'Kandy', '2024-12-20', '08:30:00', 'Lions Club Kandy'),

-- Current/upcoming camps
('Emergency Blood Collection', 'Base Hospital Gampaha', 'Gampaha', '2025-08-15', '10:00:00', 'Blood Bank Gampaha'),
('Community Blood Drive', 'District Hospital Kurunegala', 'Kurunegala', '2025-08-20', '09:30:00', 'Rotary Club'),
('University Blood Camp', 'University of Peradeniya', 'Kandy', '2025-08-25', '08:00:00', 'Medical Faculty'),
('Corporate Blood Drive', 'Dialog Axiata Headquarters', 'Colombo', '2025-09-01', '10:30:00', 'Dialog CSR Team'),
('School Blood Camp', 'Royal College Colombo', 'Colombo', '2025-09-05', '09:00:00', 'Old Boys Association'),
('Temple Blood Drive', 'Kelaniya Raja Maha Viharaya', 'Gampaha', '2025-09-10', '07:30:00', 'Temple Committee'),
('Coastal Blood Collection', 'Base Hospital Matara', 'Matara', '2025-09-15', '08:30:00', 'Matara Medical Association'),
('Northern Province Drive', 'Teaching Hospital Jaffna', 'Jaffna', '2025-09-20', '09:00:00', 'Jaffna Medical Society'),
('Monthly Blood Drive', 'General Hospital Badulla', 'Badulla', '2025-10-01', '08:00:00', 'Badulla Medical Center'),
('Year-End Blood Campaign', 'Central Hospital Ratnapura', 'Ratnapura', '2025-12-15', '09:30:00', 'Health Ministry');

-- ========================================
-- VERIFICATION QUERIES
-- ========================================

-- Check record counts
SELECT 'donors' as table_name, COUNT(*) as record_count FROM donors
UNION ALL
SELECT 'camps' as table_name, COUNT(*) as record_count FROM camps;

-- Blood type distribution
SELECT blood_type, COUNT(*) as donor_count 
FROM donors 
GROUP BY blood_type 
ORDER BY donor_count DESC;

-- District distribution
SELECT district, COUNT(*) as donor_count 
FROM donors 
GROUP BY district 
ORDER BY donor_count DESC;

-- Upcoming camps
SELECT name, date, time, location, district, organizer
FROM camps 
WHERE date >= CURDATE() 
ORDER BY date ASC, time ASC;

-- ========================================
-- DATABASE STRUCTURE SUMMARY
-- ========================================

/*
STREAMLINED STRUCTURE:
- donors: 15 sample records
- camps: 12 sample records (mix of past/current/future)

REMOVED UNUSED TABLES:
- camp_registrations (not used in application)
- emergency_requests (not used in application)

BLOOD TYPE DISTRIBUTION:
- O+ (most common): 3 donors
- A+: 2 donors, A-: 2 donors  
- B+: 2 donors, B-: 1 donor
- AB+: 2 donors, AB-: 1 donor
- O- (universal donor): 2 donors

DISTRICT COVERAGE:
All major Sri Lankan districts represented with realistic distribution.

CAMP TIMELINE:
- Past: 2 camps (for analytics)
- Current/Future: 10 camps (for active use)
*/

-- ========================================
-- END OF DATABASE SETUP
-- ========================================
