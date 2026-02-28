CREATE DATABASE IF NOT EXISTS finonest;
USE finonest;

CREATE TABLE IF NOT EXISTS credit_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    bank VARCHAR(255) NOT NULL,
    type VARCHAR(50) DEFAULT 'credit_card',
    annual_fee DECIMAL(10,2) DEFAULT 0,
    joining_fee DECIMAL(10,2) DEFAULT 0,
    dsa_commission DECIMAL(10,2) DEFAULT 0,
    reward_points TEXT,
    status VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample data
INSERT INTO credit_cards (name, bank, type, annual_fee, joining_fee, dsa_commission, reward_points, status) VALUES
('Regalia', 'HDFC Bank', 'credit_card', 2500, 2500, 3000, 'Reward points on every purchase', 'active'),
('Platinum', 'ICICI Bank', 'credit_card', 1000, 500, 2500, 'Travel benefits', 'active'),
('SimplyCLICK', 'SBI', 'credit_card', 499, 499, 2000, 'Online shopping rewards', 'active');
