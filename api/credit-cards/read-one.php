<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';
include_once '../../models/CreditCard.php';

$database = new Database();
$db = $database->getConnection();
$creditCard = new CreditCard($db);

$creditCard->id = isset($_GET['id']) ? $_GET['id'] : die();

$stmt = $creditCard->readOne();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row) {
    $card_arr = array(
        "id" => $row['id'],
        "name" => $row['name'],
        "bank" => $row['bank'],
        "type" => $row['type'],
        "annual_fee" => $row['annual_fee'],
        "joining_fee" => $row['joining_fee'],
        "dsa_commission" => $row['dsa_commission'],
        "reward_points" => $row['reward_points'],
        "status" => $row['status'],
        "created_at" => $row['created_at']
    );

    http_response_code(200);
    echo json_encode($card_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Credit card not found."));
}
?>
