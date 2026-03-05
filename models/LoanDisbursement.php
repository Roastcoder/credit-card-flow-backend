<?php
class LoanDisbursement {
    private $conn;
    private $table = "loan_disbursements";

    public $id;
    public $applicant_name;
    public $mobile_number;
    public $category;
    public $lender_name;
    public $case_type;
    public $amount;
    public $interest_rate;
    public $tenure;
    public $status;
    public $employee_name;
    public $manager_name;
    public $dsa_partner;
    public $created_by_user_id;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($user_id = null, $user_role = null) {
        if ($user_role === 'super_admin' || $user_role === 'admin') {
            $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
        } else {
            $query = "SELECT * FROM " . $this->table . " WHERE created_by_user_id = :user_id ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
        }
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET applicant_name=:applicant_name, mobile_number=:mobile_number, category=:category, lender_name=:lender_name, case_type=:case_type, amount=:amount, interest_rate=:interest_rate, tenure=:tenure, status=:status, employee_name=:employee_name, manager_name=:manager_name, dsa_partner=:dsa_partner, created_by_user_id=:created_by_user_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":applicant_name", $this->applicant_name);
        $stmt->bindParam(":mobile_number", $this->mobile_number);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":lender_name", $this->lender_name);
        $stmt->bindParam(":case_type", $this->case_type);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":interest_rate", $this->interest_rate);
        $stmt->bindParam(":tenure", $this->tenure);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":employee_name", $this->employee_name);
        $stmt->bindParam(":manager_name", $this->manager_name);
        $stmt->bindParam(":dsa_partner", $this->dsa_partner);
        $stmt->bindParam(":created_by_user_id", $this->created_by_user_id);

        return $stmt->execute();
    }
}
?>
