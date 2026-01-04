<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}

include("includes/config.php");

/* =====================
   DELETE SLOT
===================== */
if (!isset($_GET['hapusslot'])) {
    header("Location: parkingslot.php");
    exit;
}

$slotID = $_GET['hapusslot'];

/* CEK SLOT ADA */
$cek = mysqli_query($conn, "
    SELECT * FROM slot 
    WHERE slot_id = '$slotID'
");

if (mysqli_num_rows($cek) == 0) {
    echo "<script>
        alert('Slot tidak ditemukan');
        document.location='parkingslot.php';
    </script>";
    exit;
}

/* CEK BOOKING */
$cekBooking = mysqli_query($conn, "
    SELECT * FROM booking 
    WHERE slot_id = '$slotID'
");

if (mysqli_num_rows($cekBooking) > 0) {
    echo "<script>
        alert('Slot tidak bisa dihapus karena masih digunakan booking');
        document.location='parkingslot.php';
    </script>";
    exit;
}

/* DELETE */
mysqli_query($conn, "
    DELETE FROM slot 
    WHERE slot_id = '$slotID'
");

echo "<script>
    alert('Slot berhasil dihapus');
    document.location='parkingslot.php';
</script>";
