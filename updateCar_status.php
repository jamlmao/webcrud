<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("location: login.php");
    exit();
}

require("dbconnect.php");

$rentedCarsSql = "SELECT c.id, c.brand, c.model, c.plateNum, c.image, u.username, c.status_
            FROM tblcar c
            LEFT JOIN rented_cars rc ON c.id = rc.car_id
            LEFT JOIN tbluser u ON rc.user_id = u.id";

$rentedCarsResult = $conn->query($rentedCarsSql);
$rentedCars = $rentedCarsResult->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
<head>
    <title>Rented Cars</title>
</head>

<body>
<div class="image-container">
    <div class="overlay-image"></div>
</div>

<h1>Rented Cars</h1>

<table>
    <tr>
        <th>Brand</th>
        <th>Model</th>
        <th>Plate Number</th>
        <th>User Name</th>
        <th>Image</th>
        <th>Status</th>
    </tr>
    <?php foreach ($rentedCars as $car) { ?>
        <tr>
            <td><?php echo $car["brand"]; ?></td>
            <td><?php echo $car["model"]; ?></td>
            <td><?php echo $car["plateNum"]; ?></td>
            <td><?php echo ($car["status_"] != "Available") ? $car["username"] : ""; ?></td>
            <td><img src="data:image/jpeg;base64,<?php echo base64_encode($car["image"]); ?>" width="100px" height="100px"></td>
            <td><?php echo $car["status_"]; ?></td>
        </tr>
    <?php } ?>
</table>

<a href="cars.php">Back to Car List</a>

</body>
</html>