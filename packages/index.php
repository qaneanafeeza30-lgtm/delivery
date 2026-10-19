<?php

session_start();

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include __DIR__ . '/../config/database.php';


/* ================= ADD ================= */

if(isset($_POST['add'])){

    $item_type = $_POST['item_type'];
    $item_name = $_POST['item_name'];
    $weight    = $_POST['weight'];

    mysqli_query($conn,

    "INSERT INTO packages
    (item_type, item_name, weight)

    VALUES
    ('$item_type','$item_name','$weight')");

    header("Location: index.php");
    exit;
}


/* ================= DELETE ================= */

if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    $check = mysqli_query($conn,
    "SELECT * FROM deliveries
    WHERE package_id='$id'");

    if(mysqli_num_rows($check) > 0){

        mysqli_query($conn,
        "DELETE FROM deliveries
        WHERE package_id='$id'");
    }

    mysqli_query($conn,
    "DELETE FROM packages
    WHERE package_id='$id'");

    header("Location: index.php");
    exit;
}


/* ================= UPDATE ================= */

if(isset($_POST['update'])){

    $id = $_POST['package_id'];

    $item_type = $_POST['item_type'];

    $item_name = $_POST['item_name'];

    $weight = $_POST['weight'];

    mysqli_query($conn,

    "UPDATE packages SET

    item_type='$item_type',

    item_name='$item_name',

    weight='$weight'

    WHERE package_id='$id'");

    header("Location: index.php");

    exit;
}


/* ================= SEARCH ================= */

$search = isset($_GET['search'])
? $_GET['search']
: '';

if($search){

    $safe = mysqli_real_escape_string($conn, $search);

    $query = mysqli_query($conn,

    "SELECT packages.*,

    senders.sender_name

    FROM packages

    LEFT JOIN deliveries
    ON packages.package_id = deliveries.package_id

    LEFT JOIN senders
    ON deliveries.sender_id = senders.sender_id

    WHERE

    packages.package_id LIKE '%$safe%'

    OR senders.sender_name LIKE '%$safe%'

    OR packages.item_type LIKE '%$safe%'

    OR packages.item_name LIKE '%$safe%'

    OR packages.weight LIKE '%$safe%'");

} else {

    $query = mysqli_query($conn,

    "SELECT packages.*,

    senders.sender_name

    FROM packages

    LEFT JOIN deliveries
    ON packages.package_id = deliveries.package_id

    LEFT JOIN senders
    ON deliveries.sender_id = senders.sender_id");
}


/* ================= STATS ================= */

$totalPackages = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM packages")
);


$weightQuery = mysqli_query($conn,
"SELECT SUM(weight) as total_weight
FROM packages");

$weightData = mysqli_fetch_assoc($weightQuery);

$totalWeight = $weightData['total_weight'];

?>


<!DOCTYPE html>

<html>

<head>

    <title>Packages</title>

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css">

    <link rel="stylesheet"
    href="../css/style.css">

    <link rel="stylesheet"
    href="../css/packages.css">

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

            <span>Dashboard</span>

        </a>


        <a href="../senders/index.php">

            <i class="ri-user-line"></i>

            <span>Senders</span>

        </a>


        <a href="../receivers/index.php">

            <i class="ri-group-line"></i>

            <span>Receivers</span>

        </a>


        <a href="../couriers/index.php">

            <i class="ri-truck-line"></i>

            <span>Couriers</span>

        </a>


        <a href="index.php" class="active">

            <i class="ri-box-3-line"></i>

            <span>Packages</span>

        </a>


        <a href="../deliveries/index.php">

            <i class="ri-map-pin-line"></i>

            <span>Deliveries</span>

        </a>

    </div>



    <!-- MAIN -->

    <div class="main">

        <div class="page-header">

            <h1 class="page-title">
                Package Management
            </h1>

            <p class="page-subtitle">
                Manage all package data easily
            </p>

        </div>



        <!-- STATS -->

        <div class="stats-box">

            <div class="stat-card">

                <h3>Total Packages</h3>

                <p><?= $totalPackages ?></p>

            </div>


            <div class="stat-card">

                <h3>Total Weight</h3>

                <p><?= number_format($totalWeight, 2) ?> kg</p>

            </div>

        </div>



        <!-- ADD FORM -->

        <div class="modern-card">

            <div class="card-header">

                <div class="card-icon">

                    <i class="fa-solid fa-box"></i>

                </div>


                <div>

                    <h3>Add New Package</h3>

                    <p>
                        Fill in package information
                    </p>

                </div>

            </div>



            <form method="POST"
            class="courier-form">


                <div class="input-box">

                    <i class="fa-solid fa-tag"></i>

                    <input type="text"
                    class="edit-input"

                    name="item_type"

                    placeholder="Item Type"

                    required>

                </div>



                <div class="input-box">

                    <i class="fa-solid fa-tv"></i>

                    <input type="text"

                    class="edit-input"

                    name="item_name"

                    placeholder="Item Name"

                    required>

                </div>



                <div class="input-box">

                    <i class="fa-solid fa-weight-hanging"></i>

                    <input type="number"
                    class="edit-input"

                    step="0.01"

                    name="weight"

                    placeholder="Weight (kg)"

                    required>

                </div>



                <button type="submit"

                name="add"

                class="modern-btn">

                    <i class="fa-solid fa-plus"></i>

                    Add Package

                </button>

            </form>

        </div>



        <!-- TABLE -->

        <div class="modern-card">

            <div class="table-top">

                <div>

                    <h3 class="table-title">
                        Package List
                    </h3>

                    <p class="table-subtitle">
                        Manage all package data
                    </p>

                </div>

                <div class="input-box">

    <i class="fa-solid fa-magnifying-glass"></i>

    <input type="text"
    placeholder="Search package..."
    id="searchInput"
    onkeyup="filterTable()">

</div>
                
            



            <table id="packageTable">
                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Sender</th>

                        <th>Item Type</th>

                        <th>Item Name</th>

                        <th>Weight</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                <?php if(mysqli_num_rows($query) > 0){ ?>

                <?php while($row = mysqli_fetch_assoc($query)){ ?>

                <tr>

                    <td>
                        <?= $row['package_id'] ?>
                    </td>


                    <td>
                        <?= $row['sender_name'] ?? 'No Sender' ?>
                    </td>


                    <?php if(isset($_GET['edit']) && $_GET['edit'] == $row['package_id']){ ?>

                    <form method="POST">

                    <input type="hidden"
                    name="package_id"
                    value="<?= $row['package_id'] ?>">


                    <td>

                        <input type="text"
                        class="edit-input"
                        name="item_type"
                        value="<?= htmlspecialchars($row['item_type']) ?>"
                        required>

                    </td>


                    <td>

                        <input type="text"
                        class="edit-input"
                        name="item_name"
                        value="<?= htmlspecialchars($row['item_name']) ?>"
                        required>

                    </td>


                    <td>

                        <input type="number"
                        class="edit-input"
                        step="0.01"
                        name="weight"
                        value="<?= $row['weight'] ?>"
                        required>

                    </td>


                    <td class="action-buttons">

                        <button type="submit"
                        name="update"
                        class="btn-save">

                            <i class="fa-solid fa-floppy-disk"></i>

                            Save

                        </button>


                        <a href="index.php"
                        class="btn-cancel">

                            Cancel

                        </a>

                    </td>

                    </form>

                    <?php } else { ?>


                    <td>
                        <?= htmlspecialchars($row['item_type']) ?>
                    </td>


                    <td>
                        <?= htmlspecialchars($row['item_name']) ?>
                    </td>


                    <td>
                        <?= number_format($row['weight'], 2) ?> kg
                    </td>


                    <td class="action-buttons">

                        <a href="index.php?edit=<?= $row['package_id'] ?>"
                        class="btn-edit">

                            <i class="fa-solid fa-pen"></i>

                            Edit

                        </a>


                        <a href="index.php?delete=<?= $row['package_id'] ?>"
                        class="btn-delete"
                        onclick="return confirm('Delete this package?')">

                            <i class="fa-solid fa-trash"></i>

                            Delete

                        </a>

                    </td>

                    <?php } ?>

                </tr>

                <?php } ?>

                <?php } else { ?>

                <tr>

                    <td colspan="6" class="empty-data">

                        No packages found

                    </td>

                </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>

</html>

<script>

function filterTable() {

    let input = document.getElementById("searchInput");

    let filter = input.value.toLowerCase();

    let table = document.getElementById("packageTable");

    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {

        let text = tr[i].textContent || tr[i].innerText;

        if (text.toLowerCase().indexOf(filter) > -1) {

            tr[i].style.display = "";

        } else {

            tr[i].style.display = "none";

        }
    }
}

</script>