<?php
class Lead {
    private $conn;
    private $table = "leads";

    public $id;
    public $lead_id;
    public $applicant_name;
    public $applicant_email;
    public $applicant_phone;
    public $card_name;
    public $bank_name;
    public $status;
    public $created_at;
    public $created_by;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT l.*, u.name as creator_name, u.email as creator_email 
                  FROM " . $this->table . " l 
                  LEFT JOIN users u ON l.created_by = u.id 
                  ORDER BY l.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $this->lead_id = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        $query = "INSERT INTO " . $this->table . " (lead_id, applicant_name, applicant_email, applicant_phone, card_name, bank_name, status, created_by) VALUES (:lead_id, :applicant_name, :applicant_email, :applicant_phone, :card_name, :bank_name, :status, :created_by)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":lead_id", $this->lead_id);
        $stmt->bindParam(":applicant_name", $this->applicant_name);
        $stmt->bindParam(":applicant_email", $this->applicant_email);
        $stmt->bindParam(":applicant_phone", $this->applicant_phone);
        $stmt->bindParam(":card_name", $this->card_name);
        $stmt->bindParam(":bank_name", $this->bank_name);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":created_by", $this->created_by);

        return $stmt->execute();
    }
}
?>
