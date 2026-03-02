<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT id, name, mobile, email, role, employee_type, channel_code, permissions, created_at FROM users ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $users = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $permissions = null;
        if (isset($row['permissions']) && !empty($row['permissions'])) {
            if (is_string($row['permissions'])) {
                $permissions = json_decode($row['permissions'], true);
            } else {
                $permissions = $row['permissions'];
            }
        }
        $row['permissions'] = $permissions;
        $users[] = $row;
    }
    
    http_response_code(200);
    echo json_encode(array("success" => true, "users" => $users));
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("error" => "Failed to fetch users: " . $e->getMessage()));
}
?>
