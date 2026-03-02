<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../config/cors.php';

try {
    $data = json_decode(file_get_contents("php://input"));

    if(empty($data->rc_number)) {
        throw new Exception('RC number required');
    }

    $api_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc2NjM5ODg5MiwianRpIjoiMjdiNjdiNWEtZjkyZC00YTZmLTk2NmMtMDhhZjc4ZjAwNmI2IiwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2LmZpbm9uZXN0aW5kaWFAc3VyZXBhc3MuaW8iLCJuYmYiOjE3NjYzOTg4OTIsImV4cCI6MjM5NzExODg5MiwiZW1haWwiOiJmaW5vbmVzdGluZGlhQHN1cmVwYXNzLmlvIiwidGVuYW50X2lkIjoibWFpbiIsInVzZXJfY2xhaW1zIjp7InNjb3BlcyI6WyJ1c2VyIl19fQ.dl1S5S3OxNs3hwxkwtLhcTAN6CmIlYa_hg4yOl5ASlg';
    
    // Step 1: Get vehicle details from RC
    $ch = curl_init('https://kyc-api.surepass.io/api/v1/rc/rc-full');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $api_token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['id_number' => $data->rc_number]));
    
    $rc_response = curl_exec($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    if ($curl_error) {
        throw new Exception('RC API Error: ' . $curl_error);
    }
    
    $rc_data = json_decode($rc_response, true);
    
    if (!$rc_data['success']) {
        throw new Exception($rc_data['message'] ?? 'RC verification failed');
    }
    
    $chassis = $rc_data['data']['vehicle_chasi_number'];
    $engine = $rc_data['data']['vehicle_engine_number'];
    
    if (empty($chassis) || empty($engine)) {
        throw new Exception('Chassis or Engine number not found in RC data');
    }
    
    // Step 2: Get challan details
    $ch = curl_init('https://kyc-api.surepass.io/api/v1/rc/rc-related/challan-details');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $api_token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'rc_number' => $data->rc_number,
        'chassis_number' => $chassis,
        'engine_number' => $engine,
        'state_only' => false,
        'state_portal' => ['DL', 'TS', 'KA', 'GJ', 'MH', 'UP', 'RJ', 'HR', 'PB']
    ]));
    
    $response = curl_exec($ch);
    $curl_error = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($curl_error) {
        throw new Exception('Challan API Error: ' . $curl_error);
    }
    
    $api_response = json_decode($response, true);
    
    if ($http_code !== 200) {
        $error_msg = $api_response['message'] ?? 'Challan fetch failed';
        throw new Exception($error_msg);
    }
    
    http_response_code(200);
    echo json_encode($api_response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
