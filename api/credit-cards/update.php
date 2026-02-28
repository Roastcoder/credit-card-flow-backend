<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';
include_once '../../models/CreditCard.php';

$database = new Database();
$db = $database->getConnection();
$creditCard = new CreditCard($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id)) {
    $creditCard->id = $data->id;
    $creditCard->name = $data->name;
    $creditCard->bank = $data->bank;
    $creditCard->type = $data->type;
    $creditCard->annual_fee = $data->annual_fee;
    $creditCard->joining_fee = $data->joining_fee;
    $creditCard->dsa_commission = $data->dsa_commission;
    $creditCard->reward_points = $data->reward_points;
    $creditCard->status = $data->status;

    if($creditCard->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Credit card updated successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update credit card."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update credit card. Data is incomplete."));
}
?>
