<?php 

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:http://localhost/smartpark/backend/admin.php");
    exit;
}
include("includes/config.php");

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: http://localhost/smartpark/frontend/index.php");
    exit;
}


?>

<html>
 <head>
    <title>Dashboard</title>
    <link rel="icon" type="images/LOGOSP.png" href="images/LOGOSP.png">
    <link rel="stylesheet" type="text/css" th:href="@{/css/table.css}" />
    <link rel="stylesheet" type="text/css" th:href="@{/css/home.css}" />
    <link rel="stylesheet" type="text/css" th:href="@{/css/form.css}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
 </head>

 <body>

     <!-- NAVBAR BOOTSTRAP -->
  <nav class="navbar py-0 gx-0 navbar-expand-lg sticky-top navbar-light bg-white" style="box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 0.2), 0 2px 10px 0 rgba(0, 0, 0, 0.19);">
    <div class="container-fluid">

      <a class="navbar-brand" href="#" style="font-size: 30px; font-weight: bold; padding-left: 15px;">
        <img src="images/LOGOSP3.png" href="index.html" alt="Logo" width="70px" height="auto">
      </a>

  <!-- NAV_ITEM -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto d-flex justify-content-end" style="background-color: #141313;" >
          <li class="nav-item">
          <form method="POST" class="d-flex" style="background-color: #141313;">
            <button type="submit" name="logout" style="color: #eed771;" class="btn pt-3">Kembali ke beranda</button>
          </form>
        </li>
      </div>    
  </nav>
    

  <!-- CARD UNTUK PILIHAN DASHBOARD --> 

  <div class="container-xxl mt-5">
    <div class="container justify-content-center d-flex">
      <div class="row d-flex justify-content-center" style="margin-bottom: 50px;">
        
        <div class="card text-center p-2 m-2 shadow p-3 mb-5 bg-body rounded" style="width: 15rem;">
          <img src="images/P.png" class="card-img-top" alt="img">
          <div class="card-body">
            <h5 class="card-title">PARKING</h5>
            <p class="card-text">Manage Parking Space</p>
            <a href="parking.php" style="background-color: #141313; color: #eed771;" class="btn">Go</a>
          </div>
        </div>

        <div class="card text-center p-2 m-2 shadow p-3 mb-5 bg-body rounded" style="width: 15rem;">
          <img src="images/L.png" class="card-img-top" alt="img">
          <div class="card-body">
            <h5 class="card-title">LOTS</h5>
            <p class="card-text">Manage Parking Slot</p>
            <a href="parkingslot.php" style="background-color: #141313; color: #eed771;" class="btn">Go</a>
          </div>
        </div>

        <div class="card text-center p-2 m-2 shadow p-3 mb-5 bg-body rounded" style="width: 15rem;">
          <img src="images/U.png" class="card-img-top" alt="img">
          <div class="card-body">
            <h5 class="card-title">USERS</h5>
            <p class="card-text">Manage Parking Users</p>
            <a href="user.php" style="background-color: #141313; color: #eed771;" class="btn">Go</a>
          </div>
        </div>

        <div class="card text-center p-2 m-2 shadow p-3 mb-5 bg-body rounded" style="width: 15rem;">
          <img src="images/B.png" class="card-img-top" alt="img">
          <div class="card-body">
            <h5 class="card-title">BOOKING</h5>
            <p class="card-text">Manage Parking Books</p>
            <a href="booked.php" style="background-color: #141313; color: #eed771;" class="btn">Go</a>
          </div>
        </div>

      </div>
    </div>
  </div>
  
 </body>
</html>
