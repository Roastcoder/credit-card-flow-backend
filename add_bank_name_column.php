<?php
/**
 * Migration: Add bank_name column to users table
 * Run: php add_bank_name_column.php
 */

include_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "Starting migration: Add bank_name column\n";
    echo "=====================================\n\n";
    
    // Check if column already exists
    $checkQuery = "SELECT COUNT(*) as count FROM information_schema.COLUMNS 
                   WHERE TABLE_SCHEMA = DATABASE() 
                   AND TABLE_NAME = 'users' 
                   AND COLUMN_NAME = 'bank_name'";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->execute();
    $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['count'] > 0) {
        echo "✓ Column 'bank_name' already exists in users table\n";
    } else {
        // Add bank_name column
        $alterQuery = "ALTER TABLE users ADD COLUMN bank_name VARCHAR(255) DEFAULT NULL AFTER ifsc";
        $db->exec($alterQuery);
        echo "✓ Added 'bank_name' column to users table\n";
    }
    
    // Update existing records with bank name based on IFSC code (optional)
    echo "\nMigration completed successfully!\n";
    echo "Note: Existing users can update their bank name in their profile.\n";
    
} catch (Exception $e) {
    echo "✗ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
?>
