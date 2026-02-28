<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';
include_once '../../models/Lead.php';

$database = new Database();
$db = $database->getConnection();
$lead = new Lead($db);

$stmt = $lead->read();
$leads = array();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $lead_item = array(
        "id" => $id,
        "lead_id" => $lead_id,
        "applicant_name" => $applicant_name,
        "applicant_email" => $applicant_email,
        "applicant_phone" => $applicant_phone,
        "card_name" => $card_name,
        "bank_name" => $bank_name,
        "status" => $status,
        "created_at" => $created_at
    );
    array_push($leads, $lead_item);
}

http_response_code(200);
echo json_encode($leads);
?>
