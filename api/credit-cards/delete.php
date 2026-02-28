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

    if($creditCard->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "Credit card deleted successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete credit card."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to delete credit card. Data is incomplete."));
}
?>
