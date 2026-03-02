<?php
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Add role column
    $query = "ALTER TABLE users ADD COLUMN IF NOT EXISTS role VARCHAR(50) DEFAULT 'employee'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    echo "✓ Added 'role' column to users table\n";
    
    // Add permissions column
    $query = "ALTER TABLE users ADD COLUMN IF NOT EXISTS permissions JSON";
    $stmt = $db->prepare($query);
    $stmt->execute();
    echo "✓ Added 'permissions' column to users table\n";
    
    echo "\nMigration completed successfully!\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
?>
