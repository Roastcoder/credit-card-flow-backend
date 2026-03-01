<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->user_id) || !isset($data->permissions)) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing required fields']);
    exit;
}

$query = "UPDATE users SET permissions = :permissions WHERE id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':permissions', json_encode($data->permissions));
$stmt->bindParam(':user_id', $data->user_id);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['message' => 'Permissions updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to update permissions']);
}
?>
