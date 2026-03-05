<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';
include_once '../../models/LoanDisbursement.php';

$database = new Database();
$db = $database->getConnection();
$loan = new LoanDisbursement($db);

$user_id = $_GET['user_id'] ?? null;
$user_role = $_GET['user_role'] ?? null;

$stmt = $loan->read($user_id, $user_role);
$loans = array();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $loan_item = array(
        "id" => $id,
        "applicantName" => $applicant_name,
        "mobileNumber" => $mobile_number,
        "category" => $category,
        "lenderName" => $lender_name,
        "caseType" => $case_type,
        "amount" => $amount,
        "interestRate" => $interest_rate,
        "tenure" => $tenure,
        "status" => $status,
        "employeeName" => $employee_name,
        "managerName" => $manager_name,
        "dsaPartner" => $dsa_partner,
        "createdAt" => $created_at
    );
    array_push($loans, $loan_item);
}

http_response_code(200);
echo json_encode($loans);
?>
