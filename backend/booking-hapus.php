<?php
session_start();

if (!isset($_SESSION['admin_USER'])) {
    header("Location: login.php");
    exit;
}

include "includes/config.php";
if (!isset($_GET['hapusbooking'])) {
    header("Location: booking.php");
    exit;
}
$bookingID = $_GET['hapusbooking'];
mysqli_begin_transaction($conn);

// AMBIL SLOT ID
$get = mysqli_query($conn, "SELECT slot_id FROM booking WHERE booking_id = '$bookingID'");
$data = mysqli_fetch_assoc($get);

if (!$data) {
    mysqli_rollback($conn);
    echo "<script>alert('Booking tidak ditemukan');document.location='booking.php'</script>";
    exit;
}

$slotID = $data['slot_id'];

// DELETE BOOKING
mysqli_query($conn, "
    DELETE FROM booking 
    WHERE booking_id = '$bookingID'
");

// RESET SLOT STATUS
mysqli_query($conn, "
    UPDATE slot
    SET slot_status = 'AVAILABLE'
    WHERE slot_id = '$slotID'
");

mysqli_commit($conn);
echo "<script>
    alert('Booking berhasil dihapus');
    document.location = 'booked.php';
</script>";
