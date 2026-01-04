<?php
session_start();
include("includes/config.php");

if (!isset($_SESSION['user_id'], $_POST['slot_id'])) {
    echo json_encode(["success"=>false]);
    exit;
}

$userID = $_SESSION['user_id'];
$slotID = $_POST['slot_id'];

$conn->begin_transaction();

$check = $conn->prepare("SELECT slot_id, park_id, status FROM slot WHERE slot_id = ? FOR UPDATE");
$check->bind_param("i", $slotID);
$check->execute();
$slot = $check->get_result()->fetch_assoc();

if (!$slot || $slot['status'] != 0) {
    $conn->rollback();
    echo json_encode(["success"=>false,"message"=>"Slot not available"]);
    exit;
}

// UPDATE SLOT MENJADI → RESERVED
$update = $conn->prepare("UPDATE slot SET status = 1 WHERE slot_id = ?");
$update->bind_param("i", $slotID);
$update->execute();

// INSERT BOOKING
$insert = $conn->prepare("INSERT INTO booking (user_id, park_id, slot_id, status) VALUES (?, ?, ?, 'RESERVED')");
$insert->bind_param("iii", $userID, $slot['park_id'], $slotID);
$insert->execute();
$conn->commit();
echo json_encode(["success"=>true,"message"=>"Slot reserved"]);
?>
