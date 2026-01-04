<?php 

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:http://localhost/smartpark/backend/admin.php");
    exit;
}
include("includes/config.php");

if (isset($_POST['Simpan'])) {
    $parkID = $_POST['inputparkid'];
    $slotID = $_POST['inputslotid'];
    $slotNAME = $_POST['inputslotname'];
    $slotSTATUS = $_POST['inputstatus'];

    mysqli_query($conn, "INSERT INTO slot (park_id, slot_id, slot_name, slot_status) VALUES('$parkID', 
    '$slotID', '$slotNAME', '$slotSTATUS')");
}

// Query semua dokter untuk tabel bawah
$query = mysqli_query($conn, "SELECT * FROM slot");
?>

<html>
 <head>
    <title>Dashboard S</title>
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
                
                <!-- INPUT PARK ID -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" class="form-control" id="parkID" name="inputparkid" placeholder="Park Id">
                </div>

                <!-- INPUT PARK NAME -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" class="form-control" id="slotID" name="inputslotid" placeholder="Slot Id">
                </div>
                
                <!-- INPUT PARK SLOT -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" class="form-control" id="slotNAME" name="inputslotname" placeholder="Slot Name">
                </div>

                <!-- INPUT PARK ADDRESS -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" class="form-control" id="slotSTATUS" name="inputstatus" placeholder="Slot Status">
                </div>

                <!-- Submit button -->
                 <div class="d-flex justify-content-between">
                    <button type="submit" class="btn" style="background-color: #141313; color: #eed771;" name="Simpan">Input</button>
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
    <thead style="background-color: #141313; color: #eed771;">
    <tr class="info">
        <th>Park Id</th>
        <th>Slot Id</th>
        <th>Slot Name</th>
        <th>Slot Status</th>
        <th>Action</th>
    </tr>
    </thead>
    
<!-- LOOP FOR AN OUTPUT -->

<?php { ?>
<?php while($row = mysqli_fetch_array($query))
{ ?>
    <tr class="danger">
        <td><?php echo $row['park_id']; ?></td>
        <td><?php echo $row['slot_id']; ?></td>
        <td><?php echo $row['slot_name']; ?></td>
        <td><?php echo $row['slot_status']; ?></td>
        <td>

    <!-- BUTTON EDIT -->
  <a href="slot-edit.php?id=<?= $row['slot_id'] ?>"
     class="btn btn-warning btn-sm" title="Edit Slot">
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
  <a href="slot-hapus.php?hapusslot=<?= $row['slot_id'] ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Yakin ingin menghapus slot ini?')">
   <i class="bi bi-trash"></i>
</a>
  </td>
<?php } ?>
<?php } ?>
</table>



 </body>
</html>
