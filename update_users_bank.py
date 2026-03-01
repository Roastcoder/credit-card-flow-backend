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

print("Adding bank columns to users table...")
try:
    cursor.execute("""
        ALTER TABLE users 
        ADD COLUMN IF NOT EXISTS bank_account VARCHAR(255) DEFAULT NULL,
        ADD COLUMN IF NOT EXISTS ifsc VARCHAR(50) DEFAULT NULL
    """)
    conn.commit()
    print("✓ Bank columns added successfully")
except Exception as e:
    print(f"Error: {e}")

# Show updated columns
print("\n=== USERS TABLE COLUMNS ===")
cursor.execute("""
    SELECT column_name, data_type 
    FROM information_schema.columns 
    WHERE table_name = 'users'
    ORDER BY ordinal_position
""")
for row in cursor.fetchall():
    print(f"  {row[0]}: {row[1]}")

cursor.close()
conn.close()
print("\n✓ Database updated successfully!")
