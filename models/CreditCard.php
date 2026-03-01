<?php
class CreditCard {
    private $conn;
    private $table = "credit_cards";

    public $id;
    public $name;
    public $bank;
    public $type;
    public $annual_fee;
    public $joining_fee;
    public $dsa_commission;
    public $reward_points;
    public $redirect_url;
    public $payout_source;
    public $variant_image;
    public $card_image;
    public $pincodes;
    public $terms;
    public $status;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " (name, bank, type, annual_fee, joining_fee, dsa_commission, reward_points, redirect_url, payout_source, variant_image, card_image, pincodes, terms, status) VALUES (:name, :bank, :type, :annual_fee, :joining_fee, :dsa_commission, :reward_points, :redirect_url, :payout_source, :variant_image, :card_image, :pincodes, :terms, :status)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":bank", $this->bank);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":annual_fee", $this->annual_fee);
        $stmt->bindParam(":joining_fee", $this->joining_fee);
        $stmt->bindParam(":dsa_commission", $this->dsa_commission);
        $stmt->bindParam(":reward_points", $this->reward_points);
        $stmt->bindParam(":redirect_url", $this->redirect_url);
        $stmt->bindParam(":payout_source", $this->payout_source);
        $stmt->bindParam(":variant_image", $this->variant_image);
        $stmt->bindParam(":card_image", $this->card_image);
        $stmt->bindParam(":pincodes", $this->pincodes);
        $stmt->bindParam(":terms", $this->terms);
        $stmt->bindParam(":status", $this->status);

        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET name=:name, bank=:bank, type=:type, annual_fee=:annual_fee, joining_fee=:joining_fee, dsa_commission=:dsa_commission, reward_points=:reward_points, status=:status WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":bank", $this->bank);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":annual_fee", $this->annual_fee);
        $stmt->bindParam(":joining_fee", $this->joining_fee);
        $stmt->bindParam(":dsa_commission", $this->dsa_commission);
        $stmt->bindParam(":reward_points", $this->reward_points);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
}
?>
