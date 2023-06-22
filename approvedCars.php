<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("location: login.php");
    exit();
}

require("dbconnect.php");


$approvedCarsSql = "SELECT c.brand, c.model, c.plateNum, c.image, u.username
                    FROM tblcar c
                    INNER JOIN rented_cars rc ON c.id = rc.car_id
                    INNER JOIN tbluser u ON rc.user_id = u.id
                    WHERE c.status_ = 'Approved'";

$approvedCarsResult = $conn->query($approvedCarsSql);
$approvedCars = $approvedCarsResult->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
<head>
    <title>Approved Cars</title>
</head>

<body>

<h1>Approved Cars</h1>

<table>
    <tr>
        <th>Brand</th>
        <th>Model</th>
        <th>Plate Number</th>
        <th>User Name</th>
        <th>Image</th>
    </tr>
    <?php foreach ($approvedCars as $car) { ?>
        <tr>
            <td><?php echo $car["brand"]; ?></td>
            <td><?php echo $car["model"]; ?></td>
            <td><?php echo $car["plateNum"]; ?></td>
            <td><?php echo $car["username"]; ?></td>
            <td><img src="data:image/jpeg;base64,<?php echo base64_encode($car["image"]); ?>" width="100px" height="100px"></td>
        </tr>
    <?php } ?>
</table>

<a href="cars.php">Back to Car List</a>

</body>
</html>
