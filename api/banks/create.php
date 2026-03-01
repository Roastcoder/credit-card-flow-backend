<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->name)) {
    $query = "INSERT INTO banks (name) VALUES (:name)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":name", $data->name);
    
    if($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "Bank added successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to add bank."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Bank name is required."));
}
?>
