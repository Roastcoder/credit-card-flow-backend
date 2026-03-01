<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';
include_once '../../models/CreditCard.php';

$database = new Database();
$db = $database->getConnection();
$creditCard = new CreditCard($db);

$stmt = $creditCard->read();
$num = $stmt->rowCount();

if($num > 0) {
    $cards_arr = array();
    $cards_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $card_item = array(
            "id" => $id,
            "name" => $name,
            "bank" => $bank,
            "type" => $type,
            "annual_fee" => $annual_fee,
            "joining_fee" => $joining_fee,
            "dsa_commission" => $dsa_commission,
            "reward_points" => $reward_points,
            "redirect_url" => $redirect_url ?? '',
            "payout_source" => $payout_source ?? '',
            "pincodes" => $pincodes ?? '',
            "terms" => $terms ?? '',
            "card_image" => $card_image ?? '',
            "variant_image" => $variant_image ?? '',
            "status" => $status,
            "created_at" => $created_at
        );
        array_push($cards_arr["records"], $card_item);
    }

    http_response_code(200);
    echo json_encode($cards_arr);
} else {
    http_response_code(200);
    echo json_encode(array("records" => array()));
}
?>
