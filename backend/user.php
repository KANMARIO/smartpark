<?php 

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:http://localhost/smartpark/backend/admin.php");
    exit;
}
include("includes/config.php");

if (isset($_POST['Simpan'])) {
    $userNAME = $_POST['inputname'];
    $phoneNUM = $_POST['inputphone'];
    $userSTATUS = $_POST['inputstatus'];
    $userPLATE = $_POST["inputplate"];
    $userPASS = password_hash($_POST['inputpass'], PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO user (user_name, phone_num, user_status, user_plate, user_pass) VALUES('$userNAME', '$phoneNUM', '$userSTATUS', '$userPLATE', '$userPASS')");
}

// Query semua dokter untuk tabel bawah
$query = mysqli_query($conn, "SELECT * FROM user");
?>

<html>
 <head>
    <title>Dashboard</title>
    <link rel="icon" type="images/LOGOSP.png" href="images/LOGOSP.png">
    <link rel="stylesheet" type="text/css" th:href="@{/css/table.css}" />
    <link rel="stylesheet" type="text/css" th:href="@{/css/home.css}" />
    <link rel="stylesheet" type="text/css" th:href="@{/css/form.css}" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
 </head>

 <body>

     <!-- NAVBAR BOOTSTRAP -->
  <nav class="navbar py-0 gx-0 navbar-expand-lg sticky-top navbar-light bg-white">
    <div class="container-fluid">

      <a class="navbar-brand" href="#" style="font-size: 30px; font-weight: bold; padding-left: 15px;">
        <img src="images/LOGOSP3.png" href="index.html" alt="Logo" width="100px" height="auto">
      </a>

  <!-- TOMBOL UNTUK SIMPAN NAV-ITEM KALAU WEB NYA DI KECILIN -->
      <button class="navbar-toggler mx-4" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span></button>

  <!-- NAV_ITEM -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto p-4 p-lg-0 mb-2 mb-lg-0 d-flex justify-content-end">
          <li class="nav-item">
            <a class="nav-link active p-3" href="dashboard.php" style="background-color: #141313; color: #eed771;">Back</a>
          </li> 
        </ul>
      </div>
  </nav>
    
    <div class="mx-auto col-lg-6 mt-4 mb-lg-0">
          <div class="card">
            <div class="card-body py-5 px-md-5">
              <h3 class="mb-4">INPUT SLOT</h1>
              <form method="post" class="form-group" enctype="multipart/form-data">
                
                <!-- INPUT USERNAME -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" class="form-control" id="userNAME" name="inputname" placeholder="Name">
                </div>

                <!-- INPUT PLAT KENDARAAN -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" class="form-control" id="userPLATE" name="inputplate" placeholder="Plat Kendaraan">
                </div>

                <!-- INPUT PACKAGE -->
                <label class="form-label fw-semibold mb-2">Choose a Package</label>
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
                
                <!-- INPUT PHONE NUM -->
                <div data-mdb-input-init class="form-outline mb-4 mt-4">
                     <input type="text" class="form-control" id="phoneNUM" name="inputphone" placeholder="Nomor Telefon">
                </div>

                <!-- INPUT PASSWORD -->
                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="password" id="userPASS" class="form-control" placeholder="Password" name="inputpass" required />
                </div>

                <!-- INPUT CONFIRM PASSWORD -->
                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="password" id="cpassword" class="form-control" placeholder="Ulang Password" name="cpassword" onkeyup="checkPass()" required />
                  <small id="warning"></small>
                </div>                    

              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- OUTPUT -->

    <table class="container table table-striped table-hover mt-5">

    <tr class="info">
        <th>User Id</th>
        <th>User Name</th>
        <th>Phone Number</th>
        <th>User Status</th>
        <th>User Plate</th>
        <th>User Password</th>
        <th>Action</th>
    </tr>
    
<!-- LOOP FOR AN OUTPUT -->

<?php { ?>
<?php while($row = mysqli_fetch_array($query))
{ ?>
    <tr class="danger">
        <td><?php echo $row['user_id']; ?></td>
        <td><?php echo $row['user_name']; ?></td>
        <td><?php echo $row['phone_num']; ?></td>
        <td><?php echo $row['user_status']; ?></td>
        <td><?php echo $row['user_plate']; ?></td>
        <td><?php echo $row['user_pass']; ?></td>
        <td>

    <!-- BUTTON EDIT -->
  <a href="user-edit.php?id=<?= $row['user_id'] ?>"
     class="btn btn-warning btn-sm" title="User Slot">
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
         class="bi bi-pencil-square" viewBox="0 0 16 16">
      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293z"/>
      <path d="M13.752 3.396 4.939 12.21a.5.5 0 0 1-.196.12l-2.414.805a.25.25 0 0 1-.316-.316l.805-2.414a.5.5 0 0 1 .12-.196l8.813-8.814z"/>
      <path fill-rule="evenodd"
            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6
               a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11
               a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5
               A1.5 1.5 0 0 0 1 2.5z"/>
    </svg>
  </a>

  <!-- BUTTON HAPUS -->
  <a href="user-hapus.php?hapususer=<?= $row['user_id'] ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Yakin ingin menghapus user ini?')">
   <i class="bi bi-trash"></i>
</a>
  </td>
<?php } ?>
<?php } ?>
</table>



 </body>
</html>
