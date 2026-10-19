<?php

session_start();

if(!isset($_SESSION['login'])){

    header("Location: ../login.php");

    exit;
}

include __DIR__ . '/../config/database.php';
/* ================= ADD ================= */

if(isset($_POST['add'])){

    $tracking = $_POST['tracking_number'];

    $sender = $_POST['sender_id'];

    $receiver = $_POST['receiver_id'];

    $courier = $_POST['courier_id'];

    $package = $_POST['package_id'];

    $date = $_POST['delivery_date'];

    $status = $_POST['status'];

    mysqli_query($conn,

    "INSERT INTO deliveries

    (tracking_number, sender_id, receiver_id, courier_id, package_id, delivery_date, status)

    VALUES

    ('$tracking','$sender','$receiver','$courier','$package','$date','$status')"

    );

    header("Location: index.php");

    exit;
}

/* ================= UPDATE STATUS ================= */

if(isset($_POST['update_status'])){

    $id = $_POST['delivery_id'];

    $status = $_POST['status'];

    mysqli_query($conn,

    "UPDATE deliveries
    SET status='$status'
    WHERE delivery_id='$id'"

    );

    header("Location: index.php");

    exit;
}

/* ================= DELETE ================= */

if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    mysqli_query($conn,

    "DELETE FROM deliveries
    WHERE delivery_id='$id'"

    );

    header("Location: index.php");

    exit;
}


/* ================= STATS ================= */

$totalDeliveries = mysqli_num_rows(
mysqli_query($conn, "SELECT * FROM deliveries")
);

$totalPending = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM deliveries WHERE status='Pending'")
);

$totalTransit = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM deliveries WHERE status='On Delivery'")
);

$totalDelivered = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM deliveries WHERE status='Delivered'")
);


/* ================= GET DATA ================= */

$query = mysqli_query($conn,

"SELECT deliveries.*,

senders.sender_name,

receivers.receiver_name,

couriers.courier_name,

packages.item_name

FROM deliveries

LEFT JOIN senders
ON deliveries.sender_id = senders.sender_id

LEFT JOIN receivers
ON deliveries.receiver_id = receivers.receiver_id

LEFT JOIN couriers
ON deliveries.courier_id = couriers.courier_id

LEFT JOIN packages
ON deliveries.package_id = packages.package_id

ORDER BY deliveries.delivery_id DESC"

);

?>

<!DOCTYPE html>

<html>

<head>

<title>Deliveries</title>

<link rel="stylesheet" href="../css/style.css">

<link rel="stylesheet" href="../css/deliveries.css">

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<div class="container">


<!-- SIDEBAR -->

<div class="sidebar">

    <div class="user">

        <div class="avatar">
            A
        </div>

        <p>Admin</p>

    </div>


    <a href="../dashboard.php">

        <i class="ri-dashboard-line"></i>

        Dashboard

    </a>


    <a href="../senders/index.php">

        <i class="ri-user-line"></i>

        Senders

    </a>


    <a href="../receivers/index.php">

        <i class="ri-group-line"></i>

        Receivers

    </a>


    <a href="../couriers/index.php">

        <i class="ri-truck-line"></i>

        Couriers

    </a>


    <a href="../packages/index.php">

        <i class="ri-box-3-line"></i>

        Packages

    </a>


    <a href="index.php" class="active">

        <i class="ri-map-pin-line"></i>

        Deliveries

    </a>

</div>



<!-- MAIN -->

<div class="main">


<h1 class="page-title">

    Delivery Management

</h1>

<p class="page-subtitle">

    Manage and track all delivery operations

</p>



<!-- STATS -->

<div class="stats-box">

    <div class="stat-card">

        <h3>Total Deliveries</h3>

        <p><?= $totalDeliveries ?></p>

    </div>


    <div class="stat-card">

        <h3>Pending</h3>

        <p><?= $totalPending ?></p>

    </div>


    <div class="stat-card">

        <h3>In Transit</h3>

        <p><?= $totalTransit ?></p>

    </div>


    <div class="stat-card">

        <h3>Delivered</h3>

        <p><?= $totalDelivered ?></p>

    </div>

</div>
<!-- ADD DELIVERY -->

<div class="modern-card">

<h2>Add Delivery</h2>

<form method="POST" class="delivery-form">

    <input type="text"
    name="tracking_number"
    placeholder="Tracking Number"
    required>

    <select name="sender_id" required>

        <option value="">Select Sender</option>

        <?php

        $senders = mysqli_query($conn, "SELECT * FROM senders");

        while($sender = mysqli_fetch_assoc($senders)) :

        ?>

        <option value="<?= $sender['sender_id'] ?>">

            <?= $sender['sender_name'] ?>

        </option>

        <?php endwhile; ?>

    </select>


    <select name="receiver_id" required>

        <option value="">Select Receiver</option>

        <?php

        $receivers = mysqli_query($conn, "SELECT * FROM receivers");

        while($receiver = mysqli_fetch_assoc($receivers)) :

        ?>

        <option value="<?= $receiver['receiver_id'] ?>">

            <?= $receiver['receiver_name'] ?>

        </option>

        <?php endwhile; ?>

    </select>


    <select name="courier_id" required>

        <option value="">Select Courier</option>

        <?php

        $couriers = mysqli_query($conn, "SELECT * FROM couriers");

        while($courier = mysqli_fetch_assoc($couriers)) :

        ?>

        <option value="<?= $courier['courier_id'] ?>">

            <?= $courier['courier_name'] ?>

        </option>

        <?php endwhile; ?>

    </select>


    <select name="package_id" required>

        <option value="">Select Package</option>

        <?php

        $packages = mysqli_query($conn, "SELECT * FROM packages");

        while($package = mysqli_fetch_assoc($packages)) :

        ?>

        <option value="<?= $package['package_id'] ?>">

            <?= $package['item_name'] ?>

        </option>

        <?php endwhile; ?>

    </select>


    <input type="date"
    name="delivery_date"
    required>


    <select name="status" required>

        <option value="Pending">Pending</option>

        <option value="On Delivery">On Delivery</option>

        <option value="Delivered">Delivered</option>

    </select>


    <button type="submit"
    name="add"
    class="modern-btn">

        Add Delivery

    </button>

</form>

</div>


<!-- TABLE -->

<div class="modern-card">


<div class="table-toolbar">

    <h2>Delivery List</h2>

    <input type="text"

    class="search-input"

    placeholder="Search anything..."

    id="searchInput"

    onkeyup="filterTable()">

</div>



<table id="deliveryTable">

<thead>

<tr>

<th>ID</th>

<th>Tracking</th>

<th>Sender</th>

<th>Receiver</th>

<th>Courier</th>

<th>Package</th>

<th>Date</th>

<th>Status</th>

<th>ACTION</th>

</tr>

</thead>


<tbody>


<?php if(mysqli_num_rows($query) > 0): ?>

<?php while($row = mysqli_fetch_assoc($query)): ?>


<tr>

<td><?= $row['delivery_id'] ?></td>

<td><?= $row['tracking_number'] ?></td>

<td><?= $row['sender_name'] ?></td>

<td><?= $row['receiver_name'] ?></td>

<td><?= $row['courier_name'] ?></td>

<td><?= $row['item_name'] ?></td>

<td><?= $row['delivery_date'] ?></td>

<td>

<?php

if($row['status'] == 'Delivered'){

    echo "<span class='status-delivered'>Delivered</span>";

}

elseif($row['status'] == 'On Delivery'){

    echo "<span class='status-transit'>On Delivery</span>";

}

else{

    echo "<span class='status-pending'>Pending</span>";

}

?>

</td>

<td>

<a href="index.php?delete=<?= $row['delivery_id'] ?>"
class="btn-delete"
onclick="return confirm('Delete this delivery?')">

    <i class="fa-solid fa-trash"></i>

    Delete

</a>

</td>









<?php endwhile; ?>


<?php else: ?>


<tr>

<td colspan="9" class="empty-state">

    No deliveries found

</td>

</tr>


<?php endif; ?>


</tbody>

</table>

</div>

</div>

</div>



<script>

function filterTable() {

    let input = document.getElementById("searchInput").value.toLowerCase();

    let rows = document.querySelectorAll("#deliveryTable tbody tr");


    rows.forEach(row => {

        row.style.display =

        row.innerText.toLowerCase().includes(input)

        ? ""

        : "none";

    });

}

</script>

</body>

</html>