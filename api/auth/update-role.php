<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->mobile) && !empty($data->role)) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        // Validate role
        $valid_roles = ['super_admin', 'admin', 'manager', 'team_leader', 'employee', 'dsa_partner'];
        if (!in_array($data->role, $valid_roles)) {
            http_response_code(400);
            echo json_encode(array("error" => "Invalid role"));
            exit();
        }
        
        $query = "UPDATE users SET role = :role WHERE mobile = :mobile";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':role', $data->role);
        $stmt->bindParam(':mobile', $data->mobile);
        
        if($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array("success" => true, "role" => $data->role));
        } else {
            http_response_code(500);
            echo json_encode(array("error" => "Failed to update role"));
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("error" => "Failed to update role: " . $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Mobile and role required"));
}
?>
