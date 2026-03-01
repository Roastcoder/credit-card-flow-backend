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

# Check if role column exists
cursor.execute("""
    SELECT column_name 
    FROM information_schema.columns 
    WHERE table_name = 'users' AND column_name = 'role'
""")
role_exists = cursor.fetchone()

if not role_exists:
    print("Adding 'role' column to users table...")
    cursor.execute("""
        ALTER TABLE users 
        ADD COLUMN role VARCHAR(50) DEFAULT 'employee'
    """)
    conn.commit()
    print("✓ Role column added")
else:
    print("✓ Role column already exists")

# Show all users
print("\n=== USERS IN DATABASE ===")
cursor.execute("SELECT id, name, email, role FROM users")
users = cursor.fetchall()
for user in users:
    print(f"  ID: {user[0]}, Name: {user[1]}, Email: {user[2]}, Role: {user[3]}")

print(f"\nTotal users: {len(users)}")

cursor.close()
conn.close()
