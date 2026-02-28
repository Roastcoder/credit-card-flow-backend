<?php
include_once '../../config/cors.php';
$config = include '../../config/kyc.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->session_id) && !empty($data->otp)) {
    $kyc = $config['kyc_api'];
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $kyc['base_url'] . '/core-svc/api/v2/exp/validation-service/aadhaar-kyc',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(array('sessionId' => $data->session_id, 'otp' => $data->otp)),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'client-user-id: ' . $kyc['client_user_id'],
            'secret-key: ' . $kyc['secret_key'],
            'access-key: ' . $kyc['access_key'],
            'service-id: ' . $kyc['service_ids']['aadhaar_kyc']
        ),
    ));
    
    $response = curl_exec($curl);
    
    http_response_code(200);
    echo $response;
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Session ID and OTP are required."));
}
?>
