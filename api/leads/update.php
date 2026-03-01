<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';
include_once '../../models/Lead.php';

$database = new Database();
$db = $database->getConnection();
$lead = new Lead($db);

$headers = getallheaders();
$user_role = $headers['X-User-Role'] ?? null;

if (!in_array($user_role, ['admin', 'super_admin'])) {
    http_response_code(403);
    echo json_encode(['message' => 'Unauthorized. Admin access required.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id)) {
    http_response_code(400);
    echo json_encode(['message' => 'Lead ID is required']);
    exit;
}

$lead->id = $data->id;
$lead->applicant_name = $data->applicant_name;
$lead->applicant_phone = $data->applicant_phone;
$lead->applicant_email = $data->applicant_email;
$lead->status = $data->status;
$lead->activation_status = $data->activation_status ?? null;
$lead->card_variant = $data->card_variant ?? null;
$lead->application_no = $data->application_no ?? null;
$lead->cust_type = $data->cust_type ?? null;
$lead->vkyc_status = $data->vkyc_status ?? null;
$lead->bkyc_status = $data->bkyc_status ?? null;
$lead->card_issued_date = $data->card_issued_date ?? null;
$lead->remark = $data->remark ?? null;

if ($lead->update()) {
    http_response_code(200);
    echo json_encode(['message' => 'Lead updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to update lead']);
}
?>
