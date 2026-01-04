<?php

// SESSION & KONEKSI DATABASE
session_start();
include("includes/config.php");

// Cek login user
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit;
}

// PEROLEH USER DARI SESSION
$userID = $_SESSION['user_id'];

// QUERY JOIN BOOKING, SLOT, PARKING
$query = $conn->prepare("SELECT b.booking_id, b.slot_id, b.checkin_time, b.checkout_time, s.slot_name, s.slot_status, p.park_name
    FROM booking b
    JOIN slot s ON b.slot_id = s.slot_id
    JOIN parking p ON b.park_id = p.park_id
    WHERE b.user_id = ?
    ORDER BY b.booking_id DESC
");
$query->bind_param("i", $userID); // Bind user_id untuk keamanan SQL Injection
$query->execute();
$result = $query->get_result(); // Ambil hasil query
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Parking</title>
    <link rel="icon" href="images/LOGOSP.png">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4">My Parking Booking</h3>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>Parking</th>
                <th>Slot</th>
                <th>Status</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['park_name'] ?></td>
                <td><?= $row['slot_name'] ?></td>

                <!-- STATUS SLOT -->
                <td>
                    <span class="badge
                        <?= $row['slot_status'] === 'RESERVED' ? 'bg-warning' :
                           ($row['slot_status'] === 'OCCUPIED' ? 'bg-danger' : 'bg-success') ?>">
                        <?= $row['slot_status'] ?>
                    </span>
                </td>

                <!-- WAKTU CHECK-IN & CHECK-OUT -->
                <td><?= $row['checkin_time'] ?? '-' ?></td>
                <td><?= $row['checkout_time'] ?? '-' ?></td>

                <!-- ACTION BUTTON -->
                <td>
                <?php if ($row['slot_status'] === 'RESERVED') { ?>
                    <!-- Tombol Check In -->
                    <button class="btn btn-success btn-sm"
                            onclick="checkin(<?= $row['booking_id'] ?>)">
                        Check In
                    </button>

                <?php } elseif ($row['slot_status'] === 'OCCUPIED') { ?>
                    <!-- Tombol Check Out -->
                    <button class="btn btn-danger btn-sm"
                            onclick="checkout(<?= $row['booking_id'] ?>)">
                        Check Out
                    </button>

                <?php } else { ?>
                    -
                <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <a href="main.php" class="btn btn-secondary mt-3">← Back to Main</a>
</div>

</body>

<!--JAVASCRIPT ACTION-->
<script>
// Kirim request CHECK-IN ke backend
function checkin(bookingID) {
    if (!confirm("Check in now?")) return;

    fetch("checkin_action.php?action=checkin", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "booking_id=" + bookingID
    })
    .then(res => res.json()) // Parsing JSON response
    .then(data => {
        alert(data.message); // Tampilkan pesan dari server
        location.reload();   // Refresh halaman
    });
}

// Kirim request CHECK-OUT ke backend
function checkout(bookingID) {
    if (!confirm("Checkout now?")) return;

    fetch("checkin_action.php?action=checkout", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "booking_id=" + bookingID
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
}
</script>

</html>
