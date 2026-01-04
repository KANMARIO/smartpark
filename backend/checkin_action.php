<?php
session_start();
include("includes/config.php");

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized"
    ]);
    exit;
}

$userID = $_SESSION['user_id'];
$action = $_GET['action'] ?? null;

if ($action === 'checkin') {

    $bookingID = $_POST['booking_id'] ?? null;
    if (!$bookingID) {
        echo json_encode(["success" => false, "message" => "Invalid request"]);
        exit;
    }

    mysqli_begin_transaction($conn);

    $stmt = $conn->prepare(" SELECT b.slot_id, s.slot_status FROM booking b JOIN slot s ON b.slot_id = s.slot_id WHERE b.booking_id = ? 
                            AND b.user_id = ? AND b.checkin_time IS NULL AND b.checkout_time IS NULL FOR UPDATE ");
    $stmt->bind_param("ii", $bookingID, $userID);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row || $row['slot_status'] !== 'RESERVED') {
        mysqli_rollback($conn);
        echo json_encode([
            "success" => false,
            "message" => "Cannot check in"
        ]);
        exit;
    }

    // SET CHECKIN TIME
    $updateBooking = $conn->prepare("UPDATE booking SET checkin_time = NOW() WHERE booking_id = ? ");
    $updateBooking->bind_param("i", $bookingID);
    $updateBooking->execute();

    // SET SLOT OCCUPIED
    $updateSlot = $conn->prepare("UPDATE slot SET slot_status = 'OCCUPIED' WHERE slot_id = ?");
    $updateSlot->bind_param("s", $row['slot_id']);
    $updateSlot->execute();

    mysqli_commit($conn);

    echo json_encode([
        "success" => true,
        "message" => "Check-in successful"
    ]);
    exit;
}

if ($action === 'checkout') {
    $bookingID = $_POST['booking_id'] ?? null;
    if (!$bookingID) {
        echo json_encode(["success" => false, "message" => "Invalid request"]);
        exit;
    }

    mysqli_begin_transaction($conn);

    // JOIN BOOKING DAN SLOT
    $stmt = $conn->prepare("SELECT b.slot_id, s.slot_status
        FROM booking b JOIN slot s ON b.slot_id = s.slot_id
        WHERE b.booking_id = ? AND b.user_id = ? AND b.checkout_time 
        IS NULL FOR UPDATE ");
    $stmt->bind_param("ii", $bookingID, $userID);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row || $row['slot_status'] !== 'OCCUPIED') {
        mysqli_rollback($conn);
        echo json_encode([
            "success" => false,
            "message" => "Cannot checkout"
        ]);
        exit;
    }

    // SET CHECKOUT TIME
    $updateBooking = $conn->prepare("UPDATE booking SET checkout_time = NOW() WHERE booking_id = ?");
    $updateBooking->bind_param("i", $bookingID);
    $updateBooking->execute();

    // SET SLOT AVAILABLE
    $updateSlot = $conn->prepare("UPDATE slot SET slot_status = 'AVAILABLE' WHERE slot_id = ?");
    $updateSlot->bind_param("s", $row['slot_id']);
    $updateSlot->execute();

    mysqli_commit($conn);

    echo json_encode([
        "success" => true,
        "message" => "Checkout successful"
    ]);
    exit;
}

// JSON digunakan agar server dan client bisa berkomunikasi secara terstruktur, rapi, dan mudah diproses.
echo json_encode([
    "success" => false,
    "message" => "Invalid action"
]);
exit;
