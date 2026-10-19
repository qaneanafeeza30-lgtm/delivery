<?php

session_start();

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include __DIR__ . '/../config/databse.php';


/* ================= ADD ================= */

if(isset($_POST['add'])){

    $name = $_POST['sender_name'];
    $address = $_POST['sender_address'];
    $phone = $_POST['sender_phone'];

    mysqli_query($conn, "INSERT INTO senders
    (sender_name, sender_address, sender_phone)
    VALUES ('$name','$address','$phone')");

    header("Location: index.php");
    exit;
}


/* ================= DELETE ================= */

if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    /* hapus dulu deliveries yang pakai sender ini */

    mysqli_query($conn,

    "DELETE FROM deliveries
    WHERE sender_id='$id'"

    );

    /* baru hapus sender */

    mysqli_query($conn,

    "DELETE FROM senders
    WHERE sender_id='$id'"

    );

    header("Location: index.php");

    exit;
}
/* ================= UPDATE ================= */

if(isset($_POST['update'])){

    $id = $_POST['sender_id'];

    $name = $_POST['sender_name'];

    $address = $_POST['sender_address'];

    $phone = $_POST['sender_phone'];

    mysqli_query($conn, "UPDATE senders SET

    sender_name='$name',

    sender_address='$address',

    sender_phone='$phone'

    WHERE sender_id='$id'");

    header("Location: index.php");
    exit;
}

?>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/senders.css">

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<!DOCTYPE html>
<html>
    <head>

    <title>Senders</title>

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css"
    rel="stylesheet">

    <link rel="stylesheet"
    href="/delivery_express/css/style.css">

    <link rel="stylesheet"
    href="/delivery_express/css/senders.css">


    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<title>Senders</title>
<link rel="stylesheet" href="/delivery_express/css/style.css">
<link rel="stylesheet" href="/delivery_express/css/senders.css">
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

<a href="index.php" class="active">

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

    <a href="../packages/index.php">

        <i class="ri-box-3-line"></i>

        <span>Packages</span>

    </a>

    <a href="../deliveries/index.php">

        <i class="ri-map-pin-line"></i>

        <span>Deliveries</span>

    </a>

</div>
<div class="main">

    <!-- PAGE HEADER -->

    <div class="page-header">

        <h1 class="page-title">
            Senders
        </h1>

        <p class="page-subtitle">
            Manage sender information easily
        </p>

    </div>



    <!-- ADD CARD -->

    <div class="modern-card">

        <div class="card-header">

            <div class="card-icon">

                <i class="fa-solid fa-user-plus"></i>

            </div>


            <div>

                <h3>Add New Sender</h3>

                <p>
                    Fill in sender information
                </p>

            </div>

        </div>



        <form method="POST" class="form-grid">


            <div class="input-box">

                <i class="fa-solid fa-user"></i>

                <input type="text"
                name="sender_name"
                placeholder="Full Name"
                required>

            </div>



            <div class="input-box">

                <i class="fa-solid fa-location-dot"></i>

                <input type="text"
                name="sender_address"
                placeholder="Address"
                required>

            </div>



            <div class="input-box">

                <i class="fa-solid fa-phone"></i>

                <input type="text"
                name="sender_phone"
                placeholder="Phone Number"
                required>

            </div>



            <button type="submit"
            name="add"
            class="modern-btn">

                <i class="fa-solid fa-plus"></i>

                Add Sender

            </button>

        </form>

    </div>



    <!-- TABLE CARD -->

    <div class="modern-card">

        <div class="table-top">

            <div>

                <h3 class="table-title">
                    Sender List
                </h3>

                <p class="table-subtitle">
                    Manage all sender data
                </p>

            </div>


            <div class="search-box">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input type="text"
            placeholder="Search sender..."
            id="searchInput"
            onkeyup="filterTable()">

</div>

        </div>



        <table id="senderTable">
            <tr>

                <th>ID</th>

                <th>Name</th>

                <th>Address</th>

                <th>Phone</th>

                <th>Action</th>

            </tr>


<?php

$query = mysqli_query($conn, "SELECT * FROM senders");

while($row = mysqli_fetch_assoc($query)){

if(isset($_GET['edit']) && $_GET['edit'] == $row['sender_id']){

?>

<tr>

<form method="POST">

    <td>

        <?= $row['sender_id'] ?>

    </td>


    <td>

        <input type="text"
        name="sender_name"
        value="<?= $row['sender_name'] ?>"
        required>

    </td>


    <td>

        <input type="text"
        name="sender_address"
        value="<?= $row['sender_address'] ?>"
        required>

    </td>


    <td>

        <input type="text"
        name="sender_phone"
        value="<?= $row['sender_phone'] ?>"
        required>

    </td>


    <td class="action-buttons">

        <input type="hidden"
        name="sender_id"
        value="<?= $row['sender_id'] ?>">


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

<?php

} else {

?>

<tr>

    <td><?= $row['sender_id'] ?></td>

    <td><?= $row['sender_name'] ?></td>

    <td><?= $row['sender_address'] ?></td>

    <td><?= $row['sender_phone'] ?></td>


    <td class="action-buttons">

        <a href="index.php?edit=<?= $row['sender_id'] ?>"
        class="btn-edit">

            <i class="fa-solid fa-pen"></i>

            Edit

        </a>


        <a href="index.php?delete=<?= $row['sender_id'] ?>"
        class="btn-delete"
        onclick="return confirm('Delete this sender?')">

            <i class="fa-solid fa-trash"></i>

            Delete

        </a>

    </td>

</tr>

<?php

}

}

?>
</table>

</table>

<script>

function filterTable() {

    let input = document.getElementById("searchInput");

    let filter = input.value.toLowerCase();

    let table = document.getElementById("senderTable");

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