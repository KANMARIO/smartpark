<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}

include("includes/config.php");

// AMBIL DATA SLOT
$slotID = $_GET['id'] ?? null;

if (!$slotID) {
    header("Location: parkingslot.php");
    exit;
}

$data = mysqli_query($conn, "SELECT * FROM slot WHERE slot_id = '$slotID'");

$row = mysqli_fetch_assoc($data);

if (!$row) {
    echo "<script>
        alert('Data slot tidak ditemukan');
        document.location='parkingslot.php';
    </script>";
    exit;
}

// UPDATE SLOT
if (isset($_POST['update'])) {
    $parkID     = $_POST['park_id'];
    $slotNAME   = $_POST['slot_name'];
    $slotSTATUS = $_POST['slot_status'];

    mysqli_query($conn, "UPDATE slot SET 
        park_id = '$parkID',
        slot_name = '$slotNAME',
        slot_status = '$slotSTATUS'
        WHERE slot_id = '$slotID'
    ");
    echo "<script>
        alert('Data slot berhasil diupdate');
        document.location='parkingslot.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Slot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5 col-lg-6">
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">Edit Slot</h4>

            <form method="post">
                <div class="mb-3">
                    <label>Park ID</label>
                    <input type="text" name="park_id" class="form-control"value="<?= $row['park_id'] ?>">
                </div>

                <div class="mb-3">
                    <label>Slot ID</label>
                    <input type="text" class="form-control"value="<?= $row['slot_id'] ?>" readonly>
                </div>

                <div class="mb-3">
                    <label>Slot Name</label>
                    <input type="text" name="slot_name" class="form-control"value="<?= $row['slot_name'] ?>">
                </div>

                <div class="mb-3">
                    <label>Slot Status</label>
                    <select name="slot_status" class="form-select">
                        <option value="AVAILABLE" <?= $row['slot_status']=='AVAILABLE'?'selected':'' ?>>AVAILABLE</option>
                        <option value="RESERVED" <?= $row['slot_status']=='RESERVED'?'selected':'' ?>>RESERVED</option>
                        <option value="OCCUPIED" <?= $row['slot_status']=='OCCUPIED'?'selected':'' ?>>OCCUPIED</option>
                    </select>
                </div>

                <button type="submit" name="update" class="btn btn-success">Update</button>
                <a href="parkingslot.php" class="btn btn-danger">Batal</a>
            </form>

        </div>
    </div>
</div>

</body>
</html>
