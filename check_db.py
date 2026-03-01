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

# Check leads table columns
print("=== LEADS TABLE COLUMNS ===")
cursor.execute("""
    SELECT column_name, data_type 
    FROM information_schema.columns 
    WHERE table_name = 'leads'
    ORDER BY ordinal_position
""")
for row in cursor.fetchall():
    print(f"  {row[0]}: {row[1]}")

# Check users table columns
print("\n=== USERS TABLE COLUMNS ===")
cursor.execute("""
    SELECT column_name, data_type 
    FROM information_schema.columns 
    WHERE table_name = 'users'
    ORDER BY ordinal_position
""")
for row in cursor.fetchall():
    print(f"  {row[0]}: {row[1]}")

# Check leads count
cursor.execute("SELECT COUNT(*) FROM leads")
leads_count = cursor.fetchone()[0]
print(f"\n=== STATISTICS ===")
print(f"  Total leads: {leads_count}")

# Check users count
cursor.execute("SELECT COUNT(*) FROM users")
users_count = cursor.fetchone()[0]
print(f"  Total users: {users_count}")

cursor.close()
conn.close()
