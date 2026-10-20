<?php

include __DIR__ . '/../config/database.php';


/* ADD */

if(isset($_POST['add'])){

    $name = $_POST['receiver_name'];
    $address = $_POST['receiver_address'];
    $phone = $_POST['receiver_phone'];

    mysqli_query($conn,"INSERT INTO receivers
    (receiver_name, receiver_address, receiver_phone)
    VALUES
    ('$name','$address','$phone')");

    header("Location: index.php");
    exit;
}


if(isset($_GET['delete'])){

    $id = intval($_GET['delete']);

    // hapus data yang terkait dulu
    mysqli_query($conn, "DELETE FROM deliveries WHERE receiver_id='$id'");

    // baru hapus receiver
    mysqli_query($conn, "DELETE FROM receivers WHERE receiver_id='$id'");

    header("Location: index.php");
    exit;
}

/* UPDATE */

if(isset($_POST['update'])){

    $id = $_POST['receiver_id'];

    $name = $_POST['receiver_name'];

    $address = $_POST['receiver_address'];

    $phone = $_POST['receiver_phone'];

    mysqli_query($conn,"
    UPDATE receivers SET

    receiver_name='$name',

    receiver_address='$address',

    receiver_phone='$phone'

    WHERE receiver_id='$id'
    ");

    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>

<html>

<head>

<link rel="stylesheet" href="../css/style.css">

<link rel="stylesheet" href="../css/receivers.css">

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<title>Receivers</title>


<link rel="stylesheet"
href="/delivery_express/css/style.css">

<link rel="stylesheet"
href="/delivery_express/css/receivers.css">

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css"
rel="stylesheet">

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

        <a href="index.php" class="active">
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


    <!-- MAIN CONTENT -->
    <div class="main">

        <h1 class="title">Receivers</h1>


        <!-- ADD RECEIVER -->
        <div class="receiver-box">

            <h2>+ Add Receiver</h2>

            <form method="POST" class="receiver-form">

                <input type="text"
                name="receiver_name"
                placeholder="Name"
                required>

                <input type="text"
                name="receiver_address"
                placeholder="Address"
                required>

                <input type="text"
                name="receiver_phone"
                placeholder="Phone"
                required>
            <button type="submit"
name="add"
class="modern-btn">

    <i class="fa-solid fa-plus"></i>

    Add Receiver

</button>

            </form>

        </div>


        <!-- RECEIVER LIST -->
        <div class="receiver-box">

            <h2>Receiver List</h2>

            <div class="search-bar">
                <input type="text"
placeholder="Search receiver..."
id="searchInput"
onkeyup="filterTable()">
            </div>

            <table id="receiverTable">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>

                <?php

$query = mysqli_query($conn, "SELECT * FROM receivers");

while($row = mysqli_fetch_assoc($query)){

if(isset($_GET['edit']) && $_GET['edit'] == $row['receiver_id']){

?>

<tr>

<form method="POST">

    <td>
        <?= $row['receiver_id'] ?>
    </td>

    <td>

        <input type="text"
        name="receiver_name"
        value="<?= $row['receiver_name'] ?>">

    </td>

    <td>

        <input type="text"
        name="receiver_address"
        value="<?= $row['receiver_address'] ?>">

    </td>

    <td>

        <input type="text"
        name="receiver_phone"
        value="<?= $row['receiver_phone'] ?>">

    </td>

    <td class="action-buttons">

        <input type="hidden"
        name="receiver_id"
        value="<?= $row['receiver_id'] ?>">

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

    <td><?= $row['receiver_id'] ?></td>

    <td><?= $row['receiver_name'] ?></td>

    <td><?= $row['receiver_address'] ?></td>

    <td><?= $row['receiver_phone'] ?></td>

    <td class="action-buttons">

       <a href="?edit=<?= $row['receiver_id'] ?>"
        class="btn-edit">

    <i class="ri-pencil-fill"></i>

        Edit

</a>


        <a href="?delete=<?= $row['receiver_id'] ?>"
        class="btn-delete"
        onclick="return confirm('Delete this receiver?')">

    <i class="ri-delete-bin-fill"></i>

    Delete

</a>

    </td>

</tr>

<?php

}

}

?>

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

    let table = document.getElementById("receiverTable");

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