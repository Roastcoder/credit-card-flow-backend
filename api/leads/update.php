<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id)) {
    http_response_code(400);
    echo json_encode(['message' => 'Lead ID is required']);
    exit;
}

$query = "UPDATE leads SET 
    applicant_name = :applicant_name,
    applicant_phone = :applicant_phone,
    applicant_email = :applicant_email,
    status = :status,
    activation_status = :activation_status,
    card_variant = :card_variant,
    application_no = :application_no,
    cust_type = :cust_type,
    vkyc_status = :vkyc_status,
    bkyc_status = :bkyc_status,
    card_issued_date = :card_issued_date,
    remark = :remark
    WHERE id = :id";

$stmt = $db->prepare($query);

$stmt->bindParam(':applicant_name', $data->applicant_name);
$stmt->bindParam(':applicant_phone', $data->applicant_phone);
$stmt->bindParam(':applicant_email', $data->applicant_email);
$stmt->bindParam(':status', $data->status);
$stmt->bindParam(':activation_status', $data->activation_status);
$stmt->bindParam(':card_variant', $data->card_variant);
$stmt->bindParam(':application_no', $data->application_no);
$stmt->bindParam(':cust_type', $data->cust_type);
$stmt->bindParam(':vkyc_status', $data->vkyc_status);
$stmt->bindParam(':bkyc_status', $data->bkyc_status);
$stmt->bindParam(':card_issued_date', $data->card_issued_date);
$stmt->bindParam(':remark', $data->remark);
$stmt->bindParam(':id', $data->id);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['message' => 'Lead updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to update lead']);
}
?>
