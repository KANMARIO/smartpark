<?php
session_start();
include("includes/config.php");
$userID = $_SESSION['user_id'];

// LOGOUT HANDLER
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// PROTEKSI LOGIN
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// AJAX HANDLER
if (isset($_GET['action']) && $_GET['action'] === 'getSlots') {
    $parkID = $_GET['park_id'];

    $stmt = $conn->prepare(" SELECT s.slot_id, s.slot_name, s.slot_status, b.booking_id, b.user_id, 
      IF(b.user_id = ?, 1, 0) AS is_mine FROM slot s LEFT JOIN booking b ON b.slot_id = s.slot_id 
      AND b.checkout_time IS NULL WHERE s.park_id = ? ORDER BY s.slot_name ");
    $stmt->bind_param("is", $userID, $parkID);
    $stmt->execute();
    $res = $stmt->get_result();

    $data = [];
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }

    header("Content-Type: application/json");
    echo json_encode($data);
    exit;
}

    // BOOK SLOT
   if (isset($_GET['action']) && $_GET['action'] === 'bookSlot') {

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "Unauthorized"]);
        exit;
    }

    $slotID = $_POST['slot_id'];
    $userID = $_SESSION['user_id'];

    mysqli_begin_transaction($conn);

    // LOCK SLOT
    $stmt = $conn->prepare("
        SELECT park_id, slot_status
        FROM slot
        WHERE slot_id = ?
        FOR UPDATE
    ");
    $stmt->bind_param("s", $slotID);
    $stmt->execute();
    $slot = $stmt->get_result()->fetch_assoc();

    if (!$slot) {
        mysqli_rollback($conn);
        echo json_encode(["success" => false, "message" => "Slot not found"]);
        exit;
    }

    if ($slot['slot_status'] !== 'AVAILABLE') {
        mysqli_rollback($conn);
        echo json_encode(["success" => false, "message" => "Slot not available"]);
        exit;
    }

    // UPDATE SLOT
    $update = $conn->prepare("
        UPDATE slot
        SET slot_status = 'RESERVED'
        WHERE slot_id = ?
    ");
    $update->bind_param("s", $slotID);
    $update->execute();

    // INSERT BOOKING
    $insert = $conn->prepare("
        INSERT INTO booking (user_id, park_id, slot_id)
        VALUES (?, ?, ?)
    ");
    $insert->bind_param("iss", $userID, $slot['park_id'], $slotID);
    $insert->execute();

    mysqli_commit($conn);

    echo json_encode([
        "success" => true,
        "message" => "Slot booked successfully"
    ]);
    exit;
}

// PARKING AVAILABILITY
$parks = mysqli_query($conn, "SELECT p.park_id, p.park_name, COUNT(s.slot_id) AS total_slot,
        SUM(s.slot_status = 'AVAILABLE') AS available_slot FROM parking p JOIN slot s ON p.park_id = s.park_id GROUP BY p.park_id");

$selectedPark = $_GET['park_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Main Page</title>
<link rel="icon" href="images/LOGOSP.png">
<link rel="stylesheet" href="css/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.slot-card {
    cursor: pointer;
    transition: 0.2s;
}
.slot-card:hover {
    transform: scale(1.03);
}
.slot-card.reserve {
    pointer-events: none;
    opacity: 0.6;
}
</style>

</head>
<body class="bg-light">
  
     <!-- NAVBAR BOOTSTRAP -->
  <nav class="navbar py-0 gx-0 navbar-expand-lg sticky-top navbar-light bg-white" style="box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 0.2), 0 2px 10px 0 rgba(0, 0, 0, 0.19);">
    <div class="container-fluid">

      <a class="navbar-brand" href="#" style="font-size: 30px; font-weight: bold; padding-left: 15px;">
        <img src="images/LOGOSP3.png" href="index.html" alt="Logo" width="100px" height="auto">
      </a>

  <!-- NAV_ITEM -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto p-lg-0 mb-lg-0 d-flex justify-content-end">
          <li class="nav-item">
          <a href="checkin.php" class="btn p-3" style="margin-right: 5px; background-color: #141313; color: #eed771">My Bookings</a>
        </li>
        <ul class="navbar-nav ms-auto p-lg-0 mb-lg-0 d-flex justify-content-end">
          <li class="nav-item">
          <a href="main.php?logout=1" class="btn p-3" style="background-color: #141313; color: #eed771">Logout</a>
        </li>
      </div>    
  </nav>
  
<div class="container mt-5">
<!-- PARK LIST -->
<h3 class="mb-4">Available Parking Lots</h3>
<div class="row">
<?php while ($p = mysqli_fetch_assoc($parks)) { ?>
  <div class="col-md-4 mb-3">
    <div class="card shadow">
      <div class="card-body">
        <h5><?= $p['park_name']; ?></h5>
        <p><?= $p['available_slot']; ?>/<?= $p['total_slot']; ?> available</p>
        <a href="main.php?park_id=<?= $p['park_id']; ?>" class="btn btn-dark">
            View Slots
        </a>
      </div>
    </div>
  </div>
<?php } ?>
</div>

<!-- SLOT VIEW -->
<?php if ($selectedPark) { ?>
<hr>
<h4 class="mb-3">Parking Slots</h4>
<div class="row" id="slotContainer"></div>
<?php } ?>
</div>

<script>
const PARK_ID = "<?= $selectedPark ?>";

function loadSlots() {
  if (!PARK_ID) return;

  fetch(`main.php?action=getSlots&park_id=${PARK_ID}`)
    .then(res => res.json())
    .then(slots => {
      const c = document.getElementById("slotContainer");
      c.innerHTML = "";

      slots.forEach(slot => {

        // TEXT STATUS
        const statusText = slot.slot_status;

        // WARNA BADGE
        const badgeClass =
          slot.slot_status === "AVAILABLE" ? "bg-success" :
          slot.slot_status === "RESERVED"  ? "bg-warning" :
          "bg-danger";

        // DISABLE CARD JIKA BUKAN AVAILABLE
        const disabledClass =
          slot.slot_status !== "AVAILABLE" ? "reserve" : "";

        // CLICK HANDLER (HANYA AVAILABLE BISA DI-BOOK)
        const onClickAction =
          slot.slot_status === "AVAILABLE"
            ? `bookSlot('${slot.slot_id}')`
            : "";

        c.innerHTML += `
          <div class="col-md-3 mb-4">
            <div class="card slot-card ${disabledClass}"
                 onclick="${onClickAction}">
              <div class="card-body text-center">
                <h5>${slot.slot_name}</h5>
                <h6 class="text-muted">${slot.slot_id}</h6>
                <span class="badge ${badgeClass}">
                  ${statusText}
                </span>
              </div>
            </div>
          </div>
        `;
      });
    })
    .catch(err => {
      console.error("Failed to load slots:", err);
    });
}

function bookSlot(slotID) {
  if (!confirm("Book this slot?")) return;

  fetch("main.php?action=bookSlot", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: "slot_id=" + encodeURIComponent(slotID)
  })
  .then(res => res.json())
  .then(data => {
    alert(data.message);
    loadSlots();
  })
  .catch(err => {
    console.error("Booking failed:", err);
  });
}

// AUTO LOAD
if (PARK_ID) {
  loadSlots();
  setInterval(loadSlots, 5000);
}
</script>

</body>
</html>
