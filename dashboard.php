<?php

session_start();

if(!isset($_SESSION['login'])){

    header("Location: ../login.php");

    exit;
}

include __DIR__ . '/config/database.php';

$couriers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM couriers"));
$packages = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM packages"));
$deliveries = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM deliveries"));
?>

<!DOCTYPE html>
<html>

<head>

    <title>Dashboard</title>

    <link rel="stylesheet" href="css/style.css?v=10">

    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

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


    <a href="dashboard.php" class="active">

    <i class="ri-dashboard-line"></i>

    Dashboard

</a>


    <a href="senders/index.php">

        <i class="ri-user-line"></i>

        Senders

    </a>


    <a href="receivers/index.php">

        <i class="ri-group-line"></i>

        Receivers

    </a>


    <a href="couriers/index.php">

        <i class="ri-truck-line"></i>

        Couriers

    </a>


    <a href="packages/index.php">

        <i class="ri-box-3-line"></i>

        Packages

    </a>


    <a href="deliveries/index.php">

        <i class="ri-map-pin-line"></i>

        Deliveries

    </a>

<a href="/delivery_express/delivery_express/logout.php"
   class="logout"
   onclick="return confirm('Are you sure you want to logout?')">

    <span>Logout</span>

</a>
</div>


    <!-- MAIN -->

    <div class="main">

        <!-- HERO -->

        <div class="hero">

            <div class="hero-left">

                <h1>Delivery Express</h1>

                <p>
                    Manage your delivery system with ease
                </p>

            </div>


            <div class="welcome-box">

                <i class="ri-user-smile-line"></i>

                <span>
                    Welcome back,
                    <b>Admin!</b>
                </span>

            </div>

        </div>


        <!-- CARDS -->

        <div class="cards">

            <div class="card">

                <div class="card-icon blue">

                    <i class="ri-truck-line"></i>

                </div>

                <h3>Couriers</h3>

                <p><?= $couriers ?></p>

                <span>Total Couriers</span>

            </div>


            <div class="card">

                <div class="card-icon green">

                    <i class="ri-box-3-line"></i>

                </div>

                <h3>Packages</h3>

                <p><?= $packages ?></p>

                <span>Total Packages</span>

            </div>


            <div class="card">

                <div class="card-icon purple">

                    <i class="ri-map-pin-line"></i>

                </div>

                <h3>Deliveries</h3>

                <p><?= $deliveries ?></p>

                <span>Total Deliveries</span>

            </div>

        </div>

<!-- TABLE -->

<div class="table-box">

    <h2>Recent Deliveries</h2>

<?php

$search = "";

if(isset($_GET['search'])){

    $search = $_GET['search'];

}

$query = mysqli_query($conn,

"SELECT deliveries.delivery_id,
        deliveries.status,

        senders.sender_name,

        receivers.receiver_name,

        couriers.courier_name

FROM deliveries

JOIN senders
ON deliveries.sender_id = senders.sender_id

JOIN receivers
ON deliveries.receiver_id = receivers.receiver_id

JOIN couriers
ON deliveries.courier_id = couriers.courier_id

WHERE deliveries.delivery_id LIKE '%$search%'

ORDER BY deliveries.delivery_id DESC");

?>


    <form method="GET" class="table-header">

        <input type="text"
        name="search"

        placeholder="Search tracking number..."

        class="search"

        value="<?= $search ?>">

        <button type="submit"
        class="search-btn">

            Search

        </button>

    </form>



    <div class="delivery-table">

        <div class="delivery-head">

            <span>TRACKING ID</span>

            <span>SENDER</span>

            <span>RECEIVER</span>

            <span>COURIER</span>

            <span>STATUS</span>

        </div>


<?php

if(mysqli_num_rows($query) > 0){

    while($row = mysqli_fetch_assoc($query)){

?>

        <div class="delivery-row">

            <span>
                <?= $row['delivery_id'] ?>
            </span>

            <span>
                <?= $row['sender_name'] ?>
            </span>

            <span>
                <?= $row['receiver_name'] ?>
            </span>

            <span>
                <?= $row['courier_name'] ?>
            </span>

            <span>
                <?= $row['status'] ?>
            </span>

        </div>

<?php

    }

} else {

?>

        <div class="delivery-empty">

            No deliveries found

        </div>

<?php } ?>

    </div>

</div>