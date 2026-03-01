-- Banks Table
CREATE TABLE IF NOT EXISTS banks (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default banks
INSERT INTO banks (name) VALUES 
('HDFC Bank'),
('ICICI Bank'),
('SBI'),
('Axis Bank'),
('Kotak Mahindra Bank')
ON CONFLICT (name) DO NOTHING;
