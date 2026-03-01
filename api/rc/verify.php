<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->rc_number)) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        // Get user ID from token
        $user_id = null;
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        } else {
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
        }
        
        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
            $token_parts = explode('.', $token);
            if (count($token_parts) === 3) {
                $payload = json_decode(base64_decode($token_parts[1]));
                $user_id = $payload->user_id ?? null;
            }
        }
        
        $force_refresh = $data->force_refresh ?? false;
        
        // Check if RC exists in database (only if not force refresh)
        if (!$force_refresh) {
            $query = "SELECT response_data FROM rc_verifications WHERE rc_number = :rc_number";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':rc_number', $data->rc_number);
            $stmt->execute();
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing) {
                // Return from database
                $cached_data = json_decode($existing['response_data'], true);
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'data' => $cached_data,
                    'challan_details' => $cached_data['challan_details'] ?? null,
                    'from_cache' => true
                ]);
                exit;
            }
        }
        
        // Call Surepass API
        $api_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc2NjM5ODg5MiwianRpIjoiMjdiNjdiNWEtZjkyZC00YTZmLTk2NmMtMDhhZjc4ZjAwNmI2IiwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2LmZpbm9uZXN0aW5kaWFAc3VyZXBhc3MuaW8iLCJuYmYiOjE3NjYzOTg4OTIsImV4cCI6MjM5NzExODg5MiwiZW1haWwiOiJmaW5vbmVzdGluZGlhQHN1cmVwYXNzLmlvIiwidGVuYW50X2lkIjoibWFpbiIsInVzZXJfY2xhaW1zIjp7InNjb3BlcyI6WyJ1c2VyIl19fQ.dl1S5S3OxNs3hwxkwtLhcTAN6CmIlYa_hg4yOl5ASlg';
        
        $ch = curl_init('https://kyc-api.surepass.io/api/v1/rc/rc-full');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $api_token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['id_number' => $data->rc_number]));
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code !== 200) {
            throw new Exception('RC verification failed');
        }
        
        $api_response = json_decode($response, true);
        
        if (!$api_response['success']) {
            throw new Exception($api_response['message'] ?? 'RC verification failed');
        }
        
        // Save to database
        $query = "INSERT INTO rc_verifications (rc_number, response_data, verified_by) 
                  VALUES (:rc_number, :response_data, :verified_by)
                  ON CONFLICT (rc_number) 
                  DO UPDATE SET response_data = :response_data, verified_at = CURRENT_TIMESTAMP, verified_by = :verified_by";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':rc_number', $data->rc_number);
        $response_json = json_encode($api_response['data']);
        $stmt->bindParam(':response_data', $response_json);
        $stmt->bindParam(':verified_by', $user_id);
        $stmt->execute();
        
        // Return formatted data with challan info
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $api_response['data'],
            'challan_details' => $api_response['data']['challan_details'] ?? null,
            'from_cache' => false
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'RC number required']);
}
?>
