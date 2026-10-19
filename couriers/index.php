<?php

session_start();

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include __DIR__ . '/../config/database.php';


/* ================= ADD ================= */

if(isset($_POST['add'])){

    $name  = $_POST['courier_name'];
    $phone = $_POST['courier_phone'];

    mysqli_query($conn,
    "INSERT INTO couriers (courier_name, courier_phone, status)
     VALUES('$name','$phone','Active')");

    header("Location: index.php");
    exit;
}


/* ================= DELETE ================= */

if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    mysqli_query($conn,
    "DELETE FROM couriers WHERE courier_id='$id'");

    header("Location: index.php");
    exit;
}


/* ================= UPDATE ================= */

if(isset($_POST['update'])){

    $id     = $_POST['courier_id'];
    $name   = $_POST['courier_name'];
    $phone  = $_POST['courier_phone'];
    $status = $_POST['courier_status'];

    mysqli_query($conn,
    "UPDATE couriers SET
     courier_name='$name',
     courier_phone='$phone',
     status='$status'
     WHERE courier_id='$id'");

    header("Location: index.php");
    exit;
}


/* ================= SEARCH ================= */

$search = isset($_GET['search']) ? $_GET['search'] : '';

?>

<!DOCTYPE html>

<html>

<head>

    <title>Couriers</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/couriers.css">

    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css"
    rel="stylesheet">

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


        <a href="index.php" class="active">

            <i class="ri-truck-line"></i>

            <span>Couriers</span>

        </a>


        <a href="../packages/index.php">

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


        <!-- HEADER -->

        <div class="page-header">

            <h1 class="page-title">
                Courier Management
            </h1>

            <p class="page-subtitle">
                Manage all courier operations easily
            </p>

        </div>



        <!-- STATS -->

        <?php

        $totalCouriers = mysqli_num_rows(
            mysqli_query($conn, "SELECT * FROM couriers")
        );

        $totalActive = mysqli_num_rows(
            mysqli_query($conn,
            "SELECT * FROM couriers WHERE status='Active'")
        );

        $totalInactive = mysqli_num_rows(
            mysqli_query($conn,
            "SELECT * FROM couriers WHERE status='Inactive'")
        );

        ?>

        <div class="stats-box">

            <div class="stat-card">

                <h3>Total Couriers</h3>

                <p><?= $totalCouriers ?></p>

            </div>


            <div class="stat-card">

                <h3>Active</h3>

                <p style="color:#16a34a;">

                    <?= $totalActive ?>

                </p>

            </div>


            <div class="stat-card">

                <h3>Inactive</h3>

                <p style="color:#d97706;">

                    <?= $totalInactive ?>

                </p>

            </div>

        </div>



        <!-- ADD FORM -->

        <div class="modern-card">

            <div class="card-header">

                <div class="card-icon">

                    <i class="fa-solid fa-truck"></i>

                </div>


                <div>

                    <h3>Add New Courier</h3>

                    <p>Fill in courier information</p>

                </div>

            </div>



            <form method="POST" class="courier-form">

                <div class="input-box">

                    <i class="fa-solid fa-user"></i>

                    <input type="text"
                    name="courier_name"
                    placeholder="Courier Name"
                    required>

                </div>


                <div class="input-box">

                    <i class="fa-solid fa-phone"></i>

                    <input type="text"
                    name="courier_phone"
                    placeholder="Phone Number"
                    required>

                </div>


                <button type="submit"
                name="add"
                class="modern-btn">

                    <i class="fa-solid fa-plus"></i>

                    Add Courier

                </button>

            </form>

        </div>



        <!-- TABLE -->

        <div class="modern-card">

            <div class="table-top">

                <div>

                    <h3 class="table-title">
                        Courier List
                    </h3>

                    <p class="table-subtitle">
                        Manage all courier data
                    </p>

                </div>



                <!-- SEARCH -->
                 <div class="input-box"
style="margin:0;">

    <i class="fa-solid fa-magnifying-glass"></i>

    <input type="text"
    placeholder="Search courier..."
    id="searchInput"
    onkeyup="filterTable()">

</div>


            </div>



            <table id="courierTable">
                <tr>

                    <th>ID</th>

                    <th>Name</th>

                    <th>Phone</th>

                    <th>Status</th>

                    <th>Deliveries</th>

                    <th>Action</th>

                </tr>


<?php

if($search){

    $safe = mysqli_real_escape_string($conn, $search);

    $query = mysqli_query($conn,

    "SELECT * FROM couriers

    WHERE courier_name LIKE '%$safe%'

    OR courier_phone LIKE '%$safe%'");

} else {

    $query = mysqli_query($conn,
    "SELECT * FROM couriers");

}


while($row = mysqli_fetch_assoc($query)){


$totalQuery = mysqli_query($conn,

"SELECT COUNT(*) as total
FROM deliveries
WHERE courier_id = ".$row['courier_id']);

$totalData = mysqli_fetch_assoc($totalQuery);


if(isset($_GET['edit']) &&
$_GET['edit'] == $row['courier_id']){

?>

<tr>

<form method="POST">

    <td>

        <?= $row['courier_id'] ?>

    </td>


    <td>

        <input type="text"
        name="courier_name"

        value="<?= $row['courier_name'] ?>"

        required>

    </td>


    <td>

        <input type="text"
        name="courier_phone"

        value="<?= $row['courier_phone'] ?>"

        required>

    </td>


    <td>

        <select name="courier_status">

            <option value="Active"

            <?= $row['status']==='Active'
            ? 'selected' : '' ?>>

            Active

            </option>


            <option value="Inactive"

            <?= $row['status']==='Inactive'
            ? 'selected' : '' ?>>

            Inactive

            </option>

        </select>

    </td>


    <td>

        <?= $totalData['total'] ?>

    </td>


    <td class="action-buttons">

        <input type="hidden"
        name="courier_id"

        value="<?= $row['courier_id'] ?>">


        <button type="submit"
        name="update"
        class="btn-edit">

            Save

        </button>


        <a href="index.php"
        class="btn-delete">

            Cancel

        </a>

    </td>

</form>

</tr>


<?php } else { ?>


<tr>

    <td>

        <?= $row['courier_id'] ?>

    </td>


    <td>

        <?= $row['courier_name'] ?>

    </td>


    <td>

        <?= $row['courier_phone'] ?>

    </td>


    <td>

<?php if($row['status'] === 'Active'){ ?>

    <span style="background:#f0fdf4;
    color:#15803d;
    font-size:11px;
    padding:3px 10px;
    border-radius:999px;
    font-weight:500;">

        Active

    </span>

<?php } else { ?>

    <span style="background:#f3f4f6;
    color:#6b7280;
    font-size:11px;
    padding:3px 10px;
    border-radius:999px;
    font-weight:500;">

        Inactive

    </span>

<?php } ?>

    </td>


    <td>

        <?= $totalData['total'] ?>

    </td>


    <td class="action-buttons">

        <a href="index.php?edit=<?= $row['courier_id'] ?>"
        class="btn-edit">

            <i class="fa-solid fa-pen"></i>

            Edit

        </a>


        <a href="index.php?delete=<?= $row['courier_id'] ?>"

        class="btn-delete"

        onclick="return confirm('Delete this courier?')">

            <i class="fa-solid fa-trash"></i>

            Delete

        </a>

    </td>

</tr>

<?php } } ?>

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

    let table = document.getElementById("courierTable");

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