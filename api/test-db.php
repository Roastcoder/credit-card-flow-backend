<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if($db) {
    try {
        $query = "SELECT COUNT(*) as count FROM users";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Database connected',
            'users_count' => $result['count']
        ]);
    } catch(PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Query failed: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed'
    ]);
}
