<?php
$host = '72.61.238.231';
$port = '3000';
$dbname = 'board';
$user = 'Board';
$password = 'Sanam@28';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $db->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS role VARCHAR(50) DEFAULT 'employee'");
    echo "✓ Added 'role' column\n";
    
    $db->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS permissions JSON");
    echo "✓ Added 'permissions' column\n";
    
    echo "\nDatabase updated successfully!\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
