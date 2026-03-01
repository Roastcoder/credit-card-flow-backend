<?php
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

echo "Running database migrations...\n\n";

// Update leads table
echo "Updating leads table...\n";
try {
    $query = "ALTER TABLE leads 
        ADD COLUMN activation_status VARCHAR(50) DEFAULT NULL,
        ADD COLUMN card_variant VARCHAR(255) DEFAULT NULL,
        ADD COLUMN application_no VARCHAR(255) DEFAULT NULL,
        ADD COLUMN cust_type VARCHAR(50) DEFAULT NULL,
        ADD COLUMN vkyc_status VARCHAR(50) DEFAULT NULL,
        ADD COLUMN bkyc_status VARCHAR(50) DEFAULT NULL,
        ADD COLUMN card_issued_date DATE DEFAULT NULL,
        ADD COLUMN remark TEXT DEFAULT NULL";
    
    $db->exec($query);
    echo "✓ Leads table updated successfully\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "✓ Leads table columns already exist\n";
    } else {
        echo "✗ Error updating leads table: " . $e->getMessage() . "\n";
    }
}

// Update users table
echo "\nUpdating users table...\n";
try {
    $query = "ALTER TABLE users 
        ADD COLUMN permissions JSON DEFAULT NULL";
    
    $db->exec($query);
    echo "✓ Users table updated successfully\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "✓ Users table columns already exist\n";
    } else {
        echo "✗ Error updating users table: " . $e->getMessage() . "\n";
    }
}

echo "\n✓ All migrations completed!\n";
?>
