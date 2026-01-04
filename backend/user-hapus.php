<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}

include("includes/config.php");

/* =====================
   DELETE USER
===================== */
if (!isset($_GET['hapususer'])) {
    header("Location: user.php");
    exit;
}

$userID = $_GET['hapususer'];

/* CEK USER */
$cekUser = mysqli_query($conn, "
    SELECT * FROM user 
    WHERE user_id = '$userID'
");

if (mysqli_num_rows($cekUser) == 0) {
    echo "<script>
        alert('User tidak ditemukan');
        document.location='user.php';
    </script>";
    exit;
}

/* CEK BOOKING */
$cekBooking = mysqli_query($conn, "
    SELECT * FROM booking 
    WHERE user_id = '$userID'
");

if (mysqli_num_rows($cekBooking) > 0) {
    echo "<script>
        alert('User tidak bisa dihapus karena masih memiliki booking');
        document.location='user.php';
    </script>";
    exit;
}

/* DELETE */
mysqli_query($conn, "
    DELETE FROM user 
    WHERE user_id = '$userID'
");

echo "<script>
    alert('User berhasil dihapus');
    document.location='user.php';
</script>";
