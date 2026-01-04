<?php  
	include("includes/config.php");
	include("includes/jsscript.php");
	ob_start();
	session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: backend/main.php");
    exit;
}

if(isset($_POST["login"])){
    $username = $_POST["user"];
    $userpass = $_POST["pass"];

    $sql_login = mysqli_query($conn, "SELECT * FROM user WHERE user_id = '$username' AND user_pass = '$userpass'");

        if (mysqli_num_rows($sql_login) > 0) {
            $row_admin = mysqli_fetch_array($sql_login);
            $_SESSION['user_id'] = $row_admin['user_id'];
            header("Location:http://localhost/smartpark/backend/main.php");
            exit;
        } else {
            $error_message = "Invalid username or password.";
        }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="icon" href="images/LOGOSP.png">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #141313;">

  <!-- INCLUDE NAVBAR -->
  <?php include("includes/navbar.php"); ?>

  <!-- CARD UNTUK LOGIN -->
  <section>
    <div class="container">
      <div class="row d-flex justify-content-center mt-5">
        <div class="col col-xl-10">
          <div class="card h-100" style="border-radius: 1rem;">
            <div class="row g-0">

              <div class="col-md-6 col-lg-5 d-none d-md-block h-100">
                <img src="images/LOT2.png"
                  alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
              </div>
              
              <div class="col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">
  
                  <form method="POST">
                    <div class="mb-3">
                     <img src="images/LOGOSP3.png" width="120px">
                    </div>
	
                    <h5 class="fw-normal mb-2" style="letter-spacing: 1px;">Login into your account</h5> <br>
  
                    <div data-mdb-input-init class="form-outline mb-2">
                      <input type="user" class="form-control" id="pasien_id" name="user" placeholder="Masukkan ID Anda" required >
                    </div> <br>
  
                    <div data-mdb-input-init class="form-outline mb-2">
                      <input type="password" class="form-control" id="password" name="pass" placeholder="Masukkan Password anda" required>
                    </div> <br>

                    <div class="pt-1 mb-3 d-flex flex-row">
                      <div class="container d-flex justify-content-start" style="width: 40%;">
                        <button data-mdb-button-init data-mdb-ripple-init class="btn btn-lg btn-block" style="background-color: #141313; color: #eed771;" type="submit" name="login">Login</button>          
                      </div>

                      <div class="container d-flex justify-content-end" >
                        <p class="pb-lg-2" style="color: #393f81;">Don't have an account? <a href="http://localhost//smartpark/backend/signin.php"
                        style="color: #393f81;">Register here</a></p>
                      </div>
                    </div>
                  </form>

                </div>
              </div>  
            </div>
          
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <?php if (isset($error_message)) { ?>
      <div class="alert text-center" role="alert" style="color: #ff7070ff;">
        <?= $error_message; ?>
      </div>
  <?php } ?>

  <!-- INCLUDE FOOTER -->
  <br>
  <?php include("includes/footer.php"); ?>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
