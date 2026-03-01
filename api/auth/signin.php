<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->identifier) && !empty($data->mpin)) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT * FROM users WHERE mobile = :identifier OR email = :identifier LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':identifier', $data->identifier);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($user && password_verify($data->mpin, $user['mpin'])) {
            // Decode permissions from JSONB
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
                "token" => bin2hex(random_bytes(32)), 
                "user" => array(
                    "mobile" => $user['mobile'], 
                    "name" => $user['name'],
                    "channel_code" => $user['channel_code'],
                    "role" => $user['role'],
                    "permissions" => $permissions
                )
            ));
        } else {
            http_response_code(401);
            echo json_encode(array("error" => "Invalid credentials"));
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("error" => "Login failed"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Identifier and MPIN required"));
}
?>
