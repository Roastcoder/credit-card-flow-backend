import psycopg2
from urllib.parse import unquote

# Database connection
conn = psycopg2.connect(
    host='72.61.238.231',
    port=3000,
    user='Board',
    password=unquote('Sanam%4028'),
    database='board'
)

cursor = conn.cursor()

# Update leads table
print("Updating leads table...")
cursor.execute("""
ALTER TABLE leads 
ADD COLUMN IF NOT EXISTS activation_status VARCHAR(50) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS card_variant VARCHAR(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS application_no VARCHAR(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS cust_type VARCHAR(50) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS vkyc_status VARCHAR(50) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS bkyc_status VARCHAR(50) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS card_issued_date DATE DEFAULT NULL,
ADD COLUMN IF NOT EXISTS remark TEXT DEFAULT NULL
""")
conn.commit()
print("✓ Leads table updated successfully")

# Update users table
print("Updating users table...")
cursor.execute("""
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS permissions JSONB DEFAULT NULL
""")
conn.commit()
print("✓ Users table updated successfully")

cursor.close()
conn.close()
print("\n✓ All migrations completed successfully!")
