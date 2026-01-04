<?php
session_start();
include("includes/config.php");

// CEK SESSION ADMIN
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

$error_message = null;

// LOGIN PROCESS
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_name = trim($_POST["user"]);
    $user_pass = trim($_POST["pass"]);

    $stmt = $conn->prepare("SELECT user_name, user_pass FROM user WHERE user_name = ? AND user_status = 'admin' LIMIT 1
    ");
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();

// VALIDASI PASSWORD
        if ($user_pass === $row['user_pass']) {
            $_SESSION['admin'] = $row['user_name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "Password salah!";
        }

    } else {
        $error_message = "Username admin tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <link rel="icon" href="images/LOGOSP.png">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color:#141313;">

<!-- INPUT ID AND PASSWORD ADMIN -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-xl-5">
            <div class="card" style="border-radius:1rem;">
                <div class="row g-0">
                    <div class="card-body p-5">
                        <form method="POST">
                            <img class="mx-auto d-block mb-3" src="images/LOGOSP2.png" width="200" class="mb-3">
                            <input type="text" name="user" class="form-control mb-3" placeholder="Username Admin" required>
                            <input type="password" name="pass" class="form-control mb-3" placeholder="Password" required>
                            <button type="submit" class="btn w-100" style="background:#141313;color:#eed771;">Login</button>
                            
                            <!-- NOTIFIKASI KALAU SALAH -->
                            <?php if ($error_message): ?>
                                <div class="alert alert-danger mt-3">
                                    <?= $error_message; ?>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
