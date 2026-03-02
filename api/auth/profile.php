<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';


$data = json_decode(file_get_contents("php://input"));

if(!empty($data->mobile)) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT id, name, mobile, email, employee_type, channel_code, pan, dob, aadhaar, aadhaar_name, aadhaar_address, aadhaar_father_name, aadhaar_photo, bank_account, ifsc, bank_name, created_at FROM users WHERE mobile = :mobile LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':mobile', $data->mobile);
        $stmt->execute();
        
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($userData) {
            http_response_code(200);
            echo json_encode(array("success" => true, "user" => $userData));
        } else {
            http_response_code(404);
            echo json_encode(array("error" => "User not found"));
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("error" => "Failed to fetch profile"));
    }
} else {
    http_response_code(401);
    echo json_encode(array("error" => "Unauthorized"));
}
?>
