<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, name FROM banks WHERE status = 'active' ORDER BY name ASC";
$stmt = $db->prepare($query);
$stmt->execute();

$banks = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $banks[] = $row;
}

http_response_code(200);
echo json_encode(array("banks" => $banks));
?>
