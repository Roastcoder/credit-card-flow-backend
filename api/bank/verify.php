<?php
include_once '../../config/cors.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->account_number) && !empty($data->ifsc)) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.quickekyc.com/api/v1/bank-verification',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(array(
            'key' => '5490ba20-044a-4c1a-a704-e64f3ca6ce44',
            'id_number' => $data->account_number,
            'ifsc' => $data->ifsc
        )),
        CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
    ));
    
    $response = curl_exec($curl);
    
    http_response_code(200);
    echo $response;
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Account number and IFSC required."));
}
?>
