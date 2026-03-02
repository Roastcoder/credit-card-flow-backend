<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id) && !empty($data->role)) {
    $database = new Database();
    $db = $database->getConnection();
    
    // Map role to employee_type
    $roleToEmployeeType = [
        'super_admin' => 'SUPER_ADMIN',
        'admin' => 'ADMIN',
        'manager' => 'MANAGER',
        'team_leader' => 'TL',
        'employee' => 'EMPLOYEE',
        'dsa_partner' => 'DSA'
    ];
    $employeeType = isset($roleToEmployeeType[$data->role]) ? $roleToEmployeeType[$data->role] : 'EMPLOYEE';
    
    $query = "UPDATE users SET role = :role, employee_type = :employee_type WHERE id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':role', $data->role);
    $stmt->bindParam(':employee_type', $employeeType);
    $stmt->bindParam(':user_id', $data->user_id);
    
    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("success" => true));
    } else {
        http_response_code(500);
        echo json_encode(array("error" => "Failed to update role"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("error" => "User ID and role required"));
}
?>
