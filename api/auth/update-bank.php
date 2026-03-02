<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->mobile)) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "UPDATE users SET bank_name = :bank_name, bank_account = :bank_account, ifsc = :ifsc WHERE mobile = :mobile";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':bank_name', $data->bank_name);
        $stmt->bindParam(':bank_account', $data->bank_account);
        $stmt->bindParam(':ifsc', $data->ifsc);
        $stmt->bindParam(':mobile', $data->mobile);
        
        if($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array("success" => true, "message" => "Bank information updated successfully"));
        } else {
            http_response_code(500);
            echo json_encode(array("error" => "Failed to update bank information"));
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("error" => "Failed to update bank information: " . $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array("error" => "Mobile number required"));
}
?>
