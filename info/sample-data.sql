-- ========================================
-- SAMPLE DATA FOR THE DROP OF LIFE
-- Blood Donation Management System
-- ========================================
-- 
-- This file contains sample data for testing and development
-- Run this AFTER creating the database structure with schema.sql
--
-- @author Ashan Jayawardana
-- @version 1.0
-- @date 2025-07-31
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

-- Sample Camps Data (10 records)
INSERT INTO camps (name, location, district, date, time, organizer) VALUES
('Blood Drive 2025 - Colombo', 'National Hospital Colombo', 'Colombo', '2025-08-15', '09:00:00', 'Red Cross Society'),
('Life Saver Camp - Kandy', 'Teaching Hospital Kandy', 'Kandy', '2025-08-20', '08:30:00', 'Lions Club Kandy'),
('Emergency Blood Collection', 'Base Hospital Gampaha', 'Gampaha', '2025-08-25', '10:00:00', 'Blood Bank Gampaha'),
('Community Blood Drive', 'District Hospital Kurunegala', 'Kurunegala', '2025-09-01', '09:30:00', 'Rotary Club'),
('University Blood Camp', 'University of Peradeniya', 'Kandy', '2025-09-05', '08:00:00', 'Medical Faculty'),
('Corporate Blood Drive', 'Dialog Axiata Headquarters', 'Colombo', '2025-09-10', '10:30:00', 'Dialog CSR Team'),
('School Blood Camp', 'Royal College Colombo', 'Colombo', '2025-09-15', '09:00:00', 'Old Boys Association'),
('Temple Blood Drive', 'Kelaniya Raja Maha Viharaya', 'Gampaha', '2025-09-20', '07:30:00', 'Temple Committee'),
('Coastal Blood Collection', 'Base Hospital Matara', 'Matara', '2025-09-25', '08:30:00', 'Matara Medical Association'),
('Northern Province Drive', 'Teaching Hospital Jaffna', 'Jaffna', '2025-10-01', '09:00:00', 'Jaffna Medical Society');

-- Sample Camp Registrations Data (30+ records - Many-to-Many relationship)
INSERT INTO camp_registrations (donor_id, camp_id) VALUES
-- Colombo Blood Drive registrations (Camp ID: 1)
(1, 1), (2, 1), (3, 1), (9, 1), (10, 1),
-- Kandy Life Saver Camp registrations (Camp ID: 2)
(2, 2), (5, 2), (8, 2), (11, 2),
-- Gampaha Emergency Collection registrations (Camp ID: 3)
(3, 3), (6, 3), (13, 3), (14, 3),
-- Kurunegala Community Drive registrations (Camp ID: 4)
(4, 4), (7, 4), (12, 4), (15, 4),
-- University Blood Camp registrations (Camp ID: 5)
(1, 5), (5, 5), (8, 5), (11, 5), (2, 5),
-- Corporate Blood Drive registrations (Camp ID: 6)
(9, 6), (10, 6), (13, 6), (1, 6),
-- School Blood Camp registrations (Camp ID: 7)
(3, 7), (6, 7), (14, 7),
-- Temple Blood Drive registrations (Camp ID: 8)
(12, 8), (15, 8), (4, 8), (7, 8),
-- Coastal Blood Collection registrations (Camp ID: 9)
(5, 9), (8, 9), (11, 9),
-- Northern Province Drive registrations (Camp ID: 10)
(10, 10), (2, 10), (6, 10);

-- Sample Emergency Requests Data (10 records)
INSERT INTO emergency_requests (donor_id, blood_type, location, contact, note) VALUES
(NULL, 'O-', 'National Hospital Colombo', '0112691111', 'Urgent requirement for accident victim. Universal donor needed immediately.'),
(5, 'A+', 'Teaching Hospital Kandy', '0812232337', 'Patient undergoing major surgery. Family member registered as backup donor.'),
(NULL, 'B+', 'Colombo South Teaching Hospital', '0112740700', 'Thalassemia patient requires regular transfusion. Multiple units needed.'),
(8, 'AB+', 'Lady Ridgeway Hospital', '0112693711', 'Pediatric emergency case. Rare blood type required urgently.'),
(NULL, 'O+', 'Base Hospital Gampaha', '0332223261', 'Road traffic accident. Multiple casualties need immediate blood supply.'),
(12, 'A-', 'Teaching Hospital Peradeniya', '0812388047', 'Cancer patient chemotherapy support. Regular donor available.'),
(NULL, 'B-', 'General Hospital Ratnapura', '0452222261', 'Pregnancy complications. Rare blood type needed for mother.'),
(3, 'O+', 'Base Hospital Matara', '0412222261', 'Emergency surgery case. Common blood type but large quantity needed.'),
(NULL, 'AB-', 'Teaching Hospital Jaffna', '0212222261', 'Very rare blood type emergency. Seeking any compatible donors.'),
(7, 'A+', 'District General Hospital Badulla', '0552222324', 'Dengue patient with low platelet count. Platelet-rich blood needed.');

-- ========================================
-- UPDATE TIMESTAMPS FOR REALISTIC DATA
-- ========================================

-- Update some camp registration timestamps to show variety in registration dates
UPDATE camp_registrations SET registered_at = '2025-07-25 14:30:00' WHERE registration_id IN (1, 2, 3);
UPDATE camp_registrations SET registered_at = '2025-07-26 10:15:00' WHERE registration_id IN (6, 7, 8);
UPDATE camp_registrations SET registered_at = '2025-07-27 16:45:00' WHERE registration_id IN (11, 12, 13);
UPDATE camp_registrations SET registered_at = '2025-07-28 09:20:00' WHERE registration_id IN (16, 17, 18);
UPDATE camp_registrations SET registered_at = '2025-07-29 11:45:00' WHERE registration_id IN (21, 22, 23);

-- Update some emergency request timestamps to show different urgency levels
UPDATE emergency_requests SET requested_at = '2025-07-31 08:30:00' WHERE request_id IN (1, 4, 5, 9);
UPDATE emergency_requests SET requested_at = '2025-07-30 22:15:00' WHERE request_id IN (2, 6);
UPDATE emergency_requests SET requested_at = '2025-07-29 14:20:00' WHERE request_id IN (3, 7, 8, 10);

-- ========================================
-- VERIFICATION QUERIES (Optional - for testing)
-- ========================================

-- Uncomment these queries to verify the data was inserted correctly:

/*
-- Count records in each table
SELECT 'donors' as table_name, COUNT(*) as record_count FROM donors
UNION ALL
SELECT 'camps' as table_name, COUNT(*) as record_count FROM camps
UNION ALL
SELECT 'camp_registrations' as table_name, COUNT(*) as record_count FROM camp_registrations
UNION ALL
SELECT 'emergency_requests' as table_name, COUNT(*) as record_count FROM emergency_requests;

-- Blood type distribution
SELECT blood_type, COUNT(*) as donor_count 
FROM donors 
GROUP BY blood_type 
ORDER BY donor_count DESC;

-- Most popular camps
SELECT c.name, COUNT(cr.registration_id) as registration_count
FROM camps c
LEFT JOIN camp_registrations cr ON c.camp_id = cr.camp_id
GROUP BY c.camp_id, c.name
ORDER BY registration_count DESC;

-- Emergency requests by blood type
SELECT blood_type, COUNT(*) as request_count
FROM emergency_requests
GROUP BY blood_type
ORDER BY request_count DESC;
*/

-- ========================================
-- END OF SAMPLE DATA
-- ========================================
