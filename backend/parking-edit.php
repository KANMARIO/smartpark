<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}

include "includes/config.php";

// AMBIL DATA PARKING
if (!isset($_GET['id'])) {
    header("Location: parking.php");
    exit;
}

$parkID = $_GET['id'];
$get = mysqli_query($conn, "SELECT * FROM parking WHERE park_id = '$parkID'");

$data = mysqli_fetch_assoc($get);

if (!$data) {
    echo "<script>alert('Data parking tidak ditemukan');document.location='parking.php'</script>";
    exit;
}

// UPDATE DATA
if (isset($_POST['Update'])) {
    $parkNAME    = $_POST['inputparkname'];
    $parkSLOT    = $_POST['inputparkslot'];
    $parkADDRESS = $_POST['inputparkaddress'];

    mysqli_query($conn, "UPDATE parking SET
        park_name    = '$parkNAME',
        park_slot    = '$parkSLOT',
        park_address = '$parkADDRESS'
        WHERE park_id = '$parkID'
    ");

    echo "<script>
        alert('Data parking berhasil diupdate');
        document.location = 'parking.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Parking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5 col-lg-6">
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">EDIT PARKING</h4>
            
            <form method="post">
                <!-- PARK ID (READONLY) -->
                <div class="mb-3">
                    <label>Park ID</label>
                    <input type="text" class="form-control"
                           value="<?= $data['park_id']; ?>" readonly>
                </div>

                <!-- PARK NAME -->
                <div class="mb-3">
                    <label>Park Name</label>
                    <input type="text" class="form-control"
                           name="inputparkname"
                           value="<?= $data['park_name']; ?>" required>
                </div>

                <!-- PARK SLOT -->
                <div class="mb-3">
                    <label>Park Slot</label>
                    <input type="number" class="form-control"
                           name="inputparkslot"
                           value="<?= $data['park_slot']; ?>" required>
                </div>

                <!-- PARK ADDRESS -->
                <div class="mb-3">
                    <label>Park Address</label>
                    <input type="text" class="form-control"
                           name="inputparkaddress"
                           value="<?= $data['park_address']; ?>" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="parking.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" name="Update" class="btn btn-warning">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>
