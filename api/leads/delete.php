<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';
include_once '../../models/Lead.php';

$database = new Database();
$db = $database->getConnection();
$lead = new Lead($db);

// Get user role from headers (handle different server configurations)
$user_role = null;
if (function_exists('getallheaders')) {
    $headers = getallheaders();
    $user_role = $headers['X-User-Role'] ?? $headers['x-user-role'] ?? null;
} else {
    $user_role = $_SERVER['HTTP_X_USER_ROLE'] ?? null;
}

if (!in_array($user_role, ['admin', 'super_admin'])) {
    http_response_code(403);
    echo json_encode(['message' => 'Unauthorized. Admin access required.', 'role' => $user_role]);
    exit;
}

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id)) {
    $lead->id = $data->id;

    if($lead->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "Lead deleted successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete lead."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to delete lead. Data is incomplete."));
}
?>
