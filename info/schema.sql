
-- Donors table
CREATE TABLE donors (
    donor_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    blood_type VARCHAR(5),
    district VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Camps table
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

-- Camp Registrations table (Many-to-Many: donors <-> camps)
CREATE TABLE camp_registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT,
    camp_id INT,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES donors(donor_id) ON DELETE CASCADE,
    FOREIGN KEY (camp_id) REFERENCES camps(camp_id) ON DELETE CASCADE
);

-- Emergency Requests table
CREATE TABLE emergency_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT,
    blood_type VARCHAR(5) NOT NULL,
    location VARCHAR(100),
    contact VARCHAR(50),
    note TEXT,
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES donors(donor_id) ON DELETE SET NULL
);