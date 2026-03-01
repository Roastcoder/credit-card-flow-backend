<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';
include_once '../../models/Lead.php';

$database = new Database();
$db = $database->getConnection();
$lead = new Lead($db);

$data = json_decode(file_get_contents("php://input"));

// Get user ID from Authorization header
$headers = getallheaders();
$user_id = null;
if (isset($headers['Authorization'])) {
    $token = str_replace('Bearer ', '', $headers['Authorization']);
    // Decode JWT to get user_id (simplified - you should use proper JWT library)
    $token_parts = explode('.', $token);
    if (count($token_parts) === 3) {
        $payload = json_decode(base64_decode($token_parts[1]));
        $user_id = $payload->user_id ?? null;
    }
}

if(!empty($data->applicant_name) && !empty($data->applicant_phone)) {
    $lead->applicant_name = $data->applicant_name;
    $lead->applicant_email = $data->applicant_email ?? '';
    $lead->applicant_phone = $data->applicant_phone;
    $lead->card_name = $data->card_name;
    $lead->bank_name = $data->bank_name;
    $lead->status = $data->status ?? 'Generated';
    $lead->created_by = $user_id;

    if($lead->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Lead created successfully.", "lead_id" => $lead->lead_id));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create lead."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create lead. Data is incomplete."));
}
?>
