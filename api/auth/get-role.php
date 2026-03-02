<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->mobile)) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT role, permissions FROM users WHERE mobile = :mobile LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':mobile', $data->mobile);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($user) {
            $permissions = null;
            if (isset($user['permissions']) && !empty($user['permissions'])) {
                if (is_string($user['permissions'])) {
                    $permissions = json_decode($user['permissions'], true);
                } else {
                    $permissions = $user['permissions'];
                }
            }
            
            http_response_code(200);
            echo json_encode(array(
                "success" => true, 
                "role" => $user['role'],
                "permissions" => $permissions
            ));
        } else {
            http_response_code(404);
            echo json_encode(array("error" => "User not found"));
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("error" => "Failed to fetch role"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Mobile number required"));
}
?>
