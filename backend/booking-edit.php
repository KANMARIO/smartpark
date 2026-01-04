<?php
ob_start();
session_start();

if (!isset($_SESSION['admin_USER'])) {
    header("Location: login.php");
    exit;
}

include("includes/config.php");

// AMBIL DATA BOOKING
$bookingID = $_GET['id'] ?? null;
if (!$bookingID) {
    header("Location: booking.php");
    exit;
}

$edit = mysqli_query($conn, "SELECT b.booking_id, b.user_id, b.park_id, b.slot_id, b.book_time, b.checkin_time, b.checkout_time, s.slot_status
    FROM booking b JOIN slot s ON b.slot_id = s.slot_id WHERE b.booking_id = '$bookingID'");
$row_edit = mysqli_fetch_assoc($edit);

// UPDATE DATA BOOKING
if (isset($_POST['ubah'])) {
    $bookingID = $_POST['booking_id'];
    $userID    = $_POST['user_id'];
    $parkID    = $_POST['park_id'];
    $slotID    = $_POST['slot_id'];

    mysqli_begin_transaction($conn);
    mysqli_query($conn, "UPDATE booking SET user_id = '$userID', park_id = '$parkID', slot_id = '$slotID' WHERE booking_id = '$bookingID'");
    mysqli_commit($conn);
    header("Location: booking.php");
    exit;
}

// JOIN TABLE BOOKING, USER, PARKING, SLOT
$query = mysqli_query($conn, "SELECT b.booking_id, u.user_name, p.park_name, 
    s.slot_name, s.slot_status, b.book_time, b.checkin_time, b.checkout_time
    FROM booking b
    JOIN user u ON b.user_id = u.user_id
    JOIN parking p ON b.park_id = p.park_id
    JOIN slot s ON b.slot_id = s.slot_id
    ORDER BY b.book_time DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Booking</title>
    <link rel="icon" type="images/LOGOSP.png" href="images/LOGOSP.png">
    <meta charset="UTF-8">
    <title>Edit Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

 <!-- NAVBAR BOOTSTRAP -->
  <nav class="navbar py-0 gx-0 navbar-expand-lg sticky-top navbar-light bg-white">
    <div class="container-fluid">

    <a class="navbar-brand" href="#" style="font-size: 30px; font-weight: bold; padding-left: 15px;">
        <img src="images/LOGOSP3.png" href="index.html" alt="Logo" width="100px" height="auto">
    </a>

    <!-- TOMBOL UNTUK SIMPAN NAV-ITEM KALAU WEB NYA DI KECILIN -->
    <button class="navbar-toggler mx-4" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- NAV_ITEM -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto p-4 p-lg-0 mb-2 mb-lg-0 d-flex justify-content-end">
            <li class="nav-item">
            <a class="nav-link active p-3" href="booked.php" style="background-color: #141313; color: #eed771;">Back</a>
            </li>
        </ul>
    </div>
  </nav>


<div class="container mt-4">

<h3 class="mb-3">Edit Booking</h3>

<!-- FORM EDIT BOOKING -->
<form method="POST" class="card p-4 mb-5">

    <div class="mb-3">
        <label class="form-label">Booking ID</label>
        <input type="text" class="form-control"
               name="booking_id"
               value="<?= htmlspecialchars($row_edit['booking_id']) ?>"
               readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">User ID</label>
        <input type="text" class="form-control"
               name="user_id"
               value="<?= htmlspecialchars($row_edit['user_id']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Park ID</label>
        <input type="text" class="form-control"
               name="park_id"
               value="<?= htmlspecialchars($row_edit['park_id']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Slot ID</label>
        <input type="text" class="form-control"
               name="slot_id"
               value="<?= htmlspecialchars($row_edit['slot_id']) ?>"
               readonly>
        <small class="text-muted">
            Status Slot: <b><?= $row_edit['slot_status'] ?></b>
        </small>
    </div>

    <div class="mt-4">
        <button type="submit" name="ubah" class="btn btn-success">Update</button>
        <a href="booked.php" class="btn btn-danger">Batal</a>
    </div>

</form>

<!-- =====================
     TABEL BOOKING
===================== -->
<table class="table table-striped table-hover">
    <thead style="background-color: #141313; color: #eed771;">
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Park</th>
            <th>Slot</th>
            <th>Status</th>
            <th>Book Time</th>
            <th>Check-In</th>
            <th>Check-Out</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
        <tr>
            <td><?= $row['booking_id'] ?></td>
            <td><?= $row['user_name'] ?></td>
            <td><?= $row['park_name'] ?></td>
            <td><?= $row['slot_name'] ?></td>
            <td><?= $row['slot_status'] ?></td>
            <td><?= $row['book_time'] ?></td>
            <td><?= $row['checkin_time'] ?: '-' ?></td>
            <td><?= $row['checkout_time'] ?: '-' ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

</div>

</body>
</html>

<?php
mysqli_close($conn);
ob_end_flush();
?>
