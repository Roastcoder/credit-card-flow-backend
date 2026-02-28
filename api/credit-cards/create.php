<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';
include_once '../../models/CreditCard.php';

$database = new Database();
$db = $database->getConnection();
$creditCard = new CreditCard($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->name) && !empty($data->bank)) {
    $creditCard->name = $data->name;
    $creditCard->bank = $data->bank;
    $creditCard->type = $data->type ?? 'credit_card';
    $creditCard->annual_fee = $data->annual_fee ?? 0;
    $creditCard->joining_fee = $data->joining_fee ?? 0;
    $creditCard->dsa_commission = $data->dsa_commission ?? 0;
    $creditCard->reward_points = $data->reward_points ?? '';
    $creditCard->status = $data->status ?? 'active';

    if($creditCard->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Credit card created successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create credit card."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create credit card. Data is incomplete."));
}
?>
