<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';
include_once '../../models/LoanDisbursement.php';

$database = new Database();
$db = $database->getConnection();
$loan = new LoanDisbursement($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->applicantName) && !empty($data->amount)) {
    $loan->applicant_name = $data->applicantName;
    $loan->mobile_number = $data->mobileNumber ?? '';
    $loan->category = $data->category;
    $loan->lender_name = $data->lenderName;
    $loan->case_type = $data->caseType;
    $loan->amount = $data->amount;
    $loan->interest_rate = $data->interestRate ?? 0;
    $loan->tenure = $data->tenure ?? 0;
    $loan->status = $data->status ?? 'pending';
    $loan->employee_name = $data->employeeName ?? '';
    $loan->manager_name = $data->managerName ?? '';
    $loan->dsa_partner = $data->dsaPartner ?? '';

    if($loan->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Loan created successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create loan."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create loan. Data is incomplete."));
}
?>
