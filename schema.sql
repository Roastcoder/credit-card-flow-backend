-- Credit Cards Table
CREATE TABLE IF NOT EXISTS credit_cards (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    bank VARCHAR(255) NOT NULL,
    type VARCHAR(100),
    annual_fee DECIMAL(10,2) DEFAULT 0,
    joining_fee DECIMAL(10,2) DEFAULT 0,
    dsa_commission DECIMAL(10,2) DEFAULT 0,
    reward_points TEXT,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Loan Disbursements Table
CREATE TABLE IF NOT EXISTS loan_disbursements (
    id SERIAL PRIMARY KEY,
    applicant_name VARCHAR(255) NOT NULL,
    mobile_number VARCHAR(15),
    category VARCHAR(100),
    lender_name VARCHAR(255),
    case_type VARCHAR(255),
    amount DECIMAL(15,2) NOT NULL,
    interest_rate DECIMAL(5,2) DEFAULT 0,
    tenure INT DEFAULT 0,
    status VARCHAR(50) DEFAULT 'pending',
    employee_name VARCHAR(255),
    manager_name VARCHAR(255),
    dsa_partner VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Leads Table
CREATE TABLE IF NOT EXISTS leads (
    id SERIAL PRIMARY KEY,
    lead_id VARCHAR(10) UNIQUE NOT NULL,
    applicant_name VARCHAR(255) NOT NULL,
    applicant_email VARCHAR(255),
    applicant_phone VARCHAR(15) NOT NULL,
    card_name VARCHAR(255),
    bank_name VARCHAR(255),
    status VARCHAR(50) DEFAULT 'Generated',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    mobile VARCHAR(15) UNIQUE NOT NULL,
    email VARCHAR(255),
    mpin VARCHAR(255) NOT NULL,
    employee_type VARCHAR(50),
    channel_code VARCHAR(50) UNIQUE,
    pan VARCHAR(10),
    dob DATE,
    aadhaar VARCHAR(12),
    aadhaar_name VARCHAR(255),
    aadhaar_address TEXT,
    aadhaar_father_name VARCHAR(255),
    bank_account VARCHAR(50),
    ifsc VARCHAR(20),
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
