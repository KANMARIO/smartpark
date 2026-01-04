<?php 

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:http://localhost/smartpark/backend/admin.php");
    exit;
}
include("includes/config.php");

// INSERT BOOKING KE DATABASE
if (isset($_POST['Simpan'])) {
    $userID = $_POST['inputuserid'];
    $parkID = $_POST['inputparkid'];
    $slotID = $_POST['inputslotid'];

    mysqli_begin_transaction($conn);

    // AMBIL SLOT
    $check = mysqli_query($conn, " SELECT slot_status FROM slot WHERE slot_id = '$slotID' FOR UPDATE");
    $slot = mysqli_fetch_assoc($check);

    if ($slot['slot_status'] !== 'AVAILABLE') {
        mysqli_rollback($conn);
        die("Slot tidak tersedia");
    }

    // UPDATE SLOT
    mysqli_query($conn, "UPDATE slot SET slot_status = 'RESERVED' WHERE slot_id = '$slotID'");

    // INSERT BOOKING
    mysqli_query($conn, "INSERT INTO booking (user_id, park_id, slot_id, book_time) VALUES ('$userID', '$parkID', '$slotID', NOW())");

    mysqli_commit($conn);
    header("Location: booking.php");
    exit;
}

  // INSERT CHECKIN
if (isset($_GET['checkin'])) {
    $bookingID = $_GET['checkin'];
    $slotID = $_GET['slot'];

    mysqli_begin_transaction($conn);
    mysqli_query($conn, "UPDATE booking SET checkin_time = NOW() WHERE booking_id = '$bookingID' AND checkin_time IS NULL");

    mysqli_query($conn, "UPDATE slot SET slot_status = 'OCCUPIED' WHERE slot_id = '$slotID'");

    mysqli_commit($conn);
    header("Location: booking.php");
    exit;
}

  // INSERT CHECKOUT
if (isset($_GET['checkout'])) {

    $bookingID = $_GET['checkout'];
    $slotID = $_GET['slot'];

    mysqli_begin_transaction($conn);

    mysqli_query($conn, "UPDATE booking SET checkout_time = NOW() WHERE booking_id = '$bookingID'AND checkout_time IS NULL");

    mysqli_query($conn, "UPDATE slot SET slot_status = 'AVAILABLE'WHERE slot_id = '$slotID'");

    mysqli_commit($conn);
    header("Location: booking.php");
    exit;
}

  // QUERY PENGGABUNGAN TABLE
$query = mysqli_query($conn, "SELECT b.booking_id, b.book_time, b.checkin_time, b.checkout_time, 
  u.user_id, u.user_name, p.park_id, p.park_name, s.slot_id, s.slot_name, s.slot_status 
  FROM booking b
  JOIN user u ON b.user_id = u.user_id
  JOIN parking p ON b.park_id = p.park_id
  JOIN slot s ON b.slot_id = s.slot_id
  ORDER BY b.book_time DESC
");

?>  
<html>
 <head>
    <title>Dashboard B</title>
    <link rel="icon" href="images/LOGOSP.png">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
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
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- NAV ITEM -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto p-4 p-lg-0 mb-2 mb-lg-0 d-flex justify-content-end">
          <li class="nav-item">
          <a class="nav-link active p-3" href="dashboard.php" style="background-color: #141313; color: #eed771;">Back</a>
        </li>    
      </div>
  </nav>
    

  <!-- INPUT BOOKING -->
  <div class="mx-auto col-lg-6 mt-4 mb-lg-0">
    <div class="card">
      <div class="card-body py-5 px-md-5">
        <h3 class="mb-4">INPUT BOOKING</h1>
        <form method="post" class="form-group" enctype="multipart/form-data">
                
          <!-- INPUT USER ID -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="text" class="form-control" id="userID" name="inputuserid" placeholder="User ID">
          </div>

          <!-- INPUT PARK ID -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="text" class="form-control" id="parkID" name="inputparkid" placeholder="Park ID">
          </div>

          <!-- INPUT SLOT ID -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="text" class="form-control" id="slotID" name="inputslotid" placeholder="Slot ID">
          </div>                

          <!-- INPUT BOOK TIME -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="time" class="form-control" id="bookTIME" name="inputtime" placeholder="Checkin time">
          </div>                
                
          <!-- SUBMIT -->
          <div class="d-flex justify-content-between">
            <button type="submit" class="btn" style="background-color: #141313; color: #eed771;" name="Simpan">Input</button>
          </div>                    

        </form>
      </div>
    </div>
  </div>

  <!-- OUTPUT -->
    <table class="container table table-striped table-hover mt-5">
    <thead style="background-color: #141313; color: #eed771;">
    <tr class="info">
      <th>Booking ID</th>
      <th>User</th>
      <th>Park</th>
      <th>Slot</th>
      <th>Book Time</th>
      <th>Check-in</th>
      <th>Check-out</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
    </thead>
    
<!-- LOOP FOR AN OUTPUT -->
<?php { ?>
<?php while($row = mysqli_fetch_array($query))
  { ?>
<tr>
    <td><?= $row['booking_id'] ?></td>
    <td><?= $row['user_name'] ?></td>
    <td><?= $row['park_name'] ?></td>
    <td><?= $row['slot_id'] ?></td>
    <td><?= $row['book_time'] ?></td>
    <td><?= $row['checkin_time'] ?? '-' ?></td>
    <td><?= $row['checkout_time'] ?? '-' ?></td>
    <td><?= $row['slot_status'] ?></td>
    <td>
      <!-- BUTTON EDIT -->
    <a href="booking-edit.php?id=<?= $row['booking_id'] ?>"class="btn btn-warning btn-sm" title="Edit Booking">
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293z"/>
        <path d="M13.752 3.396 4.939 12.21a.5.5 0 0 1-.196.12l-2.414.805a.25.25 0 0 1-.316-.316l.805-2.414a.5.5 0 0 1 .12-.196l8.813-8.814z"/>
        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6 a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11 a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5 A1.5 1.5 0 0 0 1 2.5z"/>
      </svg>
    </a>

      <!-- BUTTON HAPUS -->
    <a href="booking-hapus.php?hapusbooking=<?= $row['booking_id'] ?>" class="btn btn-danger btn-sm" title="Delete Booking" onclick="return confirm('Yakin ingin menghapus booking ini?')">
      <i class="bi bi-trash"></i>
    </a>
  </td>
</tr>
<?php } ?>
<?php } ?>

  </table>
 </body>
</html>
