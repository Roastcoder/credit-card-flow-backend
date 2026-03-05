<?php
// Migration script to add created_by_user_id column to loan_disbursements table
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Add created_by_user_id column if it doesn't exist
    $query = "ALTER TABLE loan_disbursements ADD COLUMN IF NOT EXISTS created_by_user_id INT NULL";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    echo json_encode([
        "success" => true,
        "message" => "Database updated successfully. created_by_user_id column added to loan_disbursements table."
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Database migration failed: " . $e->getMessage()
    ]);
}
?>
