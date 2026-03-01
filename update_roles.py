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

# Update Super Admin role
print("Updating user roles...")
cursor.execute("""
    UPDATE users 
    SET role = 'super_admin' 
    WHERE email = 'admin@finonest.com'
""")

cursor.execute("""
    UPDATE users 
    SET role = 'employee' 
    WHERE email = 'yogendra6378@gmail.com'
""")

conn.commit()
print("✓ User roles updated")

# Show updated users
print("\n=== UPDATED USERS ===")
cursor.execute("SELECT id, name, email, role FROM users")
users = cursor.fetchall()
for user in users:
    print(f"  ID: {user[0]}, Name: {user[1]}, Email: {user[2]}, Role: {user[3]}")

cursor.close()
conn.close()
