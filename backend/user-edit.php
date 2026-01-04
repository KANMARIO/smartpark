<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}

include("includes/config.php");

/* =====================
   AMBIL DATA USER
===================== */
$userID = $_GET['id'] ?? null;

if (!$userID) {
    header("Location: user.php");
    exit;
}

$data = mysqli_query($conn, "
    SELECT * FROM user 
    WHERE user_id = '$userID'
");

$row = mysqli_fetch_assoc($data);

if (!$row) {
    echo "<script>
        alert('User tidak ditemukan');
        document.location='user.php';
    </script>";
    exit;
}

/* =====================
   UPDATE USER
===================== */
if (isset($_POST['update'])) {

    $userNAME   = $_POST['user_name'];
    $phoneNUM   = $_POST['phone_num'];
    $userSTATUS = $_POST['user_status'];
    $userPLATE  = $_POST['user_plate'];
    $userPASS   = $_POST['user_pass'];

    if (!empty($userPASS)) {
        $hashPASS = password_hash($userPASS, PASSWORD_DEFAULT);
        $sql = "
            UPDATE user SET
                user_name   = '$userNAME',
                phone_num   = '$phoneNUM',
                user_status = '$userSTATUS',
                user_plate  = '$userPLATE',
                user_pass   = '$hashPASS'
            WHERE user_id = '$userID'
        ";
    } else {
        $sql = "
            UPDATE user SET
                user_name   = '$userNAME',
                phone_num   = '$phoneNUM',
                user_status = '$userSTATUS',
                user_plate  = '$userPLATE'
            WHERE user_id = '$userID'
        ";
    }

    mysqli_query($conn, $sql);

    echo "<script>
        alert('Data user berhasil diupdate');
        document.location='user.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container col-lg-6 mt-5">
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">Edit User</h4>

            <form method="post">

                <div class="mb-3">
                    <label>User ID</label>
                    <input type="text" class="form-control" value="<?= $row['user_id'] ?>" readonly>
                </div>

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="user_name" class="form-control"
                           value="<?= $row['user_name'] ?>" required>
                </div>

                <div class="mb-3">
                    <label>Plat Kendaraan</label>
                    <input type="text" name="user_plate" class="form-control"
                           value="<?= $row['user_plate'] ?>">
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="user_status" class="form-select">
                        <option value="Trial"   <?= $row['user_status']=='Trial'?'selected':'' ?>>Trial</option>
                        <option value="Monthly" <?= $row['user_status']=='Monthly'?'selected':'' ?>>Monthly</option>
                        <option value="Member"  <?= $row['user_status']=='Member'?'selected':'' ?>>Member</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Nomor Telepon</label>
                    <input type="text" name="phone_num" class="form-control"
                           value="<?= $row['phone_num'] ?>">
                </div>

                <div class="mb-3">
                    <label>Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="user_pass" class="form-control">
                </div>

                <button type="submit" name="update" class="btn btn-success">Update</button>
                <a href="user.php" class="btn btn-danger">Batal</a>

            </form>
        </div>
    </div>
</div>

</body>
</html>
