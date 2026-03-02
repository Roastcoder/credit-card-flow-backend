-- Add role and permissions columns to users table
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS role VARCHAR(50) DEFAULT 'employee',
ADD COLUMN IF NOT EXISTS permissions JSON;
