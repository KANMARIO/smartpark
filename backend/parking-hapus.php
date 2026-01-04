<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}

include "includes/config.php";

// DELETE PARKING
if (!isset($_GET['hapusparking'])) {
    header("Location: parking.php");
    exit;
}

$parkID = $_GET['hapusparking'];

/* CEK DATA ADA ATAU TIDAK */
$cek = mysqli_query($conn, "
    SELECT * FROM parking 
    WHERE park_id = '$parkID'
");

if (mysqli_num_rows($cek) == 0) {
    echo "<script>
        alert('Data parking tidak ditemukan');
        document.location='parking.php';
    </script>";
    exit;
}

/* (OPSIONAL) CEK RELASI SLOT */
$cekSlot = mysqli_query($conn, "
    SELECT * FROM slot 
    WHERE park_id = '$parkID'
");

if (mysqli_num_rows($cekSlot) > 0) {
    echo "<script>
        alert('Parking tidak bisa dihapus karena masih memiliki slot');
        document.location='parking.php';
    </script>";
    exit;
}

/* DELETE */
mysqli_query($conn, "
    DELETE FROM parking 
    WHERE park_id = '$parkID'
");

echo "<script>
    alert('Data parking berhasil dihapus');
    document.location='parking.php';
</script>";
