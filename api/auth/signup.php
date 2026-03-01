<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->mobile) && !empty($data->mpin)) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        // Check if user already exists
        $checkQuery = "SELECT id FROM users WHERE mobile = :mobile OR email = :email OR pan = :pan";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindParam(':mobile', $data->mobile);
        $checkStmt->bindParam(':email', $data->email);
        $checkStmt->bindParam(':pan', $data->pan);
        $checkStmt->execute();
        
        if($checkStmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(array("error" => "User already exists with this mobile, email, or PAN"));
            exit();
        }
        
        $hashed_mpin = password_hash($data->mpin, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO users (name, mobile, email, mpin, employee_type, channel_code, pan, dob, aadhaar, aadhaar_name, aadhaar_address, aadhaar_father_name, aadhaar_photo) VALUES (:name, :mobile, :email, :mpin, :employee_type, :channel_code, :pan, :dob, :aadhaar, :aadhaar_name, :aadhaar_address, :aadhaar_father_name, :aadhaar_photo)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':mobile', $data->mobile);
        $stmt->bindParam(':email', $data->email);
        $stmt->bindParam(':mpin', $hashed_mpin);
        $stmt->bindParam(':employee_type', $data->employee_type);
        $stmt->bindParam(':channel_code', $data->channel_code);
        $stmt->bindParam(':pan', $data->pan);
        $stmt->bindParam(':dob', $data->dob);
        $stmt->bindParam(':aadhaar', $data->aadhaar);
        $stmt->bindParam(':aadhaar_name', $data->aadhaar_name);
        $stmt->bindParam(':aadhaar_address', $data->aadhaar_address);
        $stmt->bindParam(':aadhaar_father_name', $data->aadhaar_father_name);
        $stmt->bindParam(':aadhaar_photo', $data->aadhaar_photo);
        
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array("success" => true, "token" => bin2hex(random_bytes(32)), "user" => array("mobile" => $data->mobile, "name" => $data->name)));
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("error" => "Registration failed: " . $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Mobile and MPIN required"));
}
?>
