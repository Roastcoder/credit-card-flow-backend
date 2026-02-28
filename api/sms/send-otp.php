<?php
include_once '../../config/cors.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->mobile_number)) {
    $mobile = $data->mobile_number;
    $otp = rand(100000, 999999);
    
    $token = '1507603797696c62b571b953.18331010';
    $user_id = '50962153';
    $template = "Hi! $otp is your OTP to log in to Finonest Pro. The code is valid for just 3 mins. -Team Finonest";
    
    $url = "https://m1.sarv.com/api/v2.0/sms_campaign.php?token=$token&user_id=$user_id&route=OT&template_id=16212&sender_id=FINOST&language=EN&template=" . urlencode($template) . "&contact_numbers=$mobile";
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
    ));
    
    $sms_response = curl_exec($curl);
    error_log("SMS API Response: " . $sms_response);
    
    http_response_code(200);
    echo json_encode(array("success" => true, "otp" => $otp));
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Mobile number is required."));
}
?>
