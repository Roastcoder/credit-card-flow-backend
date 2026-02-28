# Finonest Backend API

PHP backend API for Finonest loan and credit management platform.

## Requirements

- PHP 7.4 or higher
- PostgreSQL
- Apache/Nginx web server
- PHP PDO PostgreSQL extension

## Setup Instructions

### 1. Install PHP and PostgreSQL Extension

```bash
# Ubuntu/Debian
sudo apt-get install php php-pgsql

# macOS (using Homebrew)
brew install php
```

### 2. Configure Database

The database connection is configured in `config/database.php`:
- Host: 72.61.238.231
- Port: 3000
- Database: board
- User: Board
- Password: Sanam@28

### 3. Create Database Tables

Run the SQL schema to create required tables:

```bash
psql -h 72.61.238.231 -p 3000 -U Board -d board -f schema.sql
```

Or manually execute the SQL in `schema.sql`.

### 4. Start PHP Development Server

```bash
# Navigate to backend directory
cd backend

# Start PHP built-in server on port 8000
php -S localhost:8000
```

The API will be available at: `http://localhost:8000`

### 5. For Production (Apache/Nginx)

Place the backend folder in your web server's document root and configure:

**Apache (.htaccess already included)**
- Ensure mod_rewrite is enabled
- Point document root to backend folder

**Nginx**
```nginx
location /api {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## API Endpoints

### Credit Cards
- `GET /api/credit-cards/read.php` - Get all credit cards
- `POST /api/credit-cards/create.php` - Create new credit card
- `GET /api/credit-cards/read-one.php?id={id}` - Get single credit card
- `PUT /api/credit-cards/update.php` - Update credit card
- `DELETE /api/credit-cards/delete.php` - Delete credit card

### Loan Disbursements
- `GET /api/loans/read.php` - Get all loan disbursements
- `POST /api/loans/create.php` - Create new loan disbursement

### Leads
- `GET /api/leads/read.php` - Get all leads
- `POST /api/leads/create.php` - Create new lead

## CORS Configuration

CORS is configured in `config/cors.php` to allow requests from any origin. For production, update to allow only your frontend domain.

## Testing API

Test the API using curl:

```bash
# Get all credit cards
curl http://localhost:8000/api/credit-cards/read.php

# Create a lead
curl -X POST http://localhost:8000/api/leads/create.php \
  -H "Content-Type: application/json" \
  -d '{"applicant_name":"John Doe","applicant_phone":"1234567890","card_name":"Premium Card","bank_name":"HDFC Bank"}'
```

## Environment Variables

Database credentials are in `config/database.php`. For production, consider using environment variables or a separate config file.
