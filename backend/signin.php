<?php
session_start();
include("includes/config.php");

/* SESSION */
$pasRec = $_SESSION['pasRec'] ?? null;
unset($_SESSION['pasRec']);

/* QUERY INSERT DATA KE DATABASE */
if (isset($_POST['Simpan'])) {
    $userNAME   = $_POST['inputname'];
    $phoneNUM   = $_POST['inputphone'];
    $userSTATUS = $_POST['inputstatus'];
    $userPLATE  = $_POST['inputplate'];
    $userPASS   = $_POST['inputpass'];

    $insert = mysqli_query($conn,"INSERT INTO user (user_name, phone_num, user_status, user_plate, user_pass) 
        VALUES ('$userNAME', '$phoneNUM', '$userSTATUS', '$userPLATE', '$userPASS')");

    if ($insert) {
        $_SESSION['pasRec'] = [
            'user_id'   => mysqli_insert_id($conn),
            'user_name' => $userNAME
        ];
        header("Location: signin.php");
        exit;
    }
}

/* QUERY AMBIL DATA DARI DATABASE */
$query = mysqli_query($conn, "SELECT * FROM user");
?>

<!-- HTML -->
<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <link rel="icon" href="images/LOGOSP.png">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<!-- NAVBAR -->
<?php include("includes/navbar.php"); ?>

<section>
  <div class="px-4 py-3 px-md-5">
    <div class="container">
      <div class="row gx-lg-5 align-items-center">

      <!-- LEFT CONTENT -->
        <div class="col-lg-6 mb-5">
          <h1 class="display-4 fw-bold">Parkir tidak Perlu<br>
            <span style="color:#eed771;">Pusing Lagi</span>
          </h1>


          <!-- NOTIFIKASI BERHASIL -->
            <?php if ($pasRec): ?>
              <div class="alert alert-success text-center mt-4" style="background:#141313;color:#eed771;">
                Data berhasil disimpan! Catat ID dan Password Anda.
              </div>

              <table class="table table-striped text-center">
                <thead>
                  <tr>
                    <th>ID User</th>
                    <th>Nama User</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><?= $pasRec['user_id']; ?></td>
                    <td><?= $pasRec['user_name']; ?></td>
                  </tr>
                </tbody>
              </table>

              <div class="d-grid">
                <a href="login.php" class="btn" style="background:#141313;color:#eed771;">Lanjut ke Login</a>
              </div>
            <?php endif; ?>
        </div>

      <!-- FORM KANAN -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body p-5">

              <img src="images/LOGOSP3.png" width="120">
              <h3 class="mb-4">Pendaftaran</h3>

                <form method="post">
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <input type="text" class="form-control" name="inputname" placeholder="Nama" required>
                    </div>

                    <div class="col-md-6 mb-3">
                      <input type="text" class="form-control" name="inputplate" placeholder="Plat Kendaraan" required>                  
                    </div>
                  </div>                

                <!-- INPUT PACKAGE MEMBER -->
                  <label class="form-label fw-semibold">Choose a Package</label>
                    <div class="package-group">
                      <label class="package-card">
                        <input type="radio" name="inputstatus" value="Member" checked>
                          <div style="text-align:center;">
                            <strong style="color: #eed771;">Trial</strong>
                            <p>Free Try</p>
                          </div>
                      </label>

                      <label class="package-card">
                        <input type="radio" name="inputstatus" value="Monthly">
                          <div style="text-align:center;">
                            <strong style="color: #eed771;">Monthly</strong>
                            <p>Best value</p>
                          </div>
                      </label>

                      <label class="package-card">
                        <input type="radio" name="inputstatus" value="Member">
                          <div style="text-align:center;">
                            <strong style="color: #eed771;">Member</strong>
                            <p>Unlimited</p>
                          </div>
                      </label>
                    </div>

                      <input type="text" class="form-control mt-3 mb-3" name="inputphone" placeholder="Nomor Telefon" required>

                      <input type="password" class="form-control mb-3" id="userPASS" name="inputpass" placeholder="Password" required>

                      <input type="password" class="form-control mb-3" id="cpassword" placeholder="Ulang Password" onkeyup="checkPass()" required>
                      <small id="warning"></small>

                    <div class="form-check mb-3">
                      <input class="form-check-input" type="checkbox" required>
                      <label class="form-check-label">Setuju Persyaratan</label>
                    </div>

                    <button type="submit" class="btn" name="Simpan" style="background:#141313;color:#eed771;">Sign Up</button>
                </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<?php include("includes/footer.php"); ?>

<!-- LOGIC CHECK PASS DUA KALI -->
<script>
function checkPass() {
    const pass = document.getElementById("userPASS").value;
    const cpass = document.getElementById("cpassword").value;
    const warn = document.getElementById("warning");

    if (pass !== cpass) {
        warn.innerHTML = "Password tidak sama!";
        warn.style.color = "red";
    } else {
        warn.innerHTML = "Password cocok!";
        warn.style.color = "green";
    }
}
</script>

</body>
</html>

<?php mysqli_close($conn); ?>
