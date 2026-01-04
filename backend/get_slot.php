<?php
include("includes/config.php");

if (!isset($_GET['park_id'])) {
    echo json_encode([]);
    exit;
}

$parkID = $_GET['park_id'];

$query = "SELECT slot_id, slot_name, slot_status
    FROM slot WHERE park_id = ? ORDER BY slot_name ";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $parkID);
$stmt->execute();
$result = $stmt->get_result();

$slots = [];
while ($row = $result->fetch_assoc()) {
    $slots[] = $row;
}

header("Content-Type: application/json");
echo json_encode($slots);
