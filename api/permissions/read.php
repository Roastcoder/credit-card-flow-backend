<?php
include_once '../../config/cors.php';
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, email, name, role, permissions FROM users WHERE role != 'super_admin'";
$stmt = $db->prepare($query);
$stmt->execute();

$users = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $row['permissions'] = $row['permissions'] ? json_decode($row['permissions'], true) : null;
    $users[] = $row;
}

http_response_code(200);
echo json_encode(['users' => $users]);
?>
