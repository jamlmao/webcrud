<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("location: login.php");
    exit();
}

require("dbconnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["car_id"])) {
    $carId = $_POST["car_id"];

    $updateStatusSql = "UPDATE tblcar SET status_ = 'Available' WHERE id = :car_id";
    $stmt = $conn->prepare($updateStatusSql);
    $stmt->bindParam(":car_id", $carId);
    $stmt->execute();

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}

$rejectedCarsSql = "SELECT c.id, c.brand, c.model, c.plateNum, c.status_, u.username, u.contactinfo 
                    FROM tblcar c 
                    INNER JOIN rented_cars rc ON c.id = rc.car_id
                    INNER JOIN tbluser u ON rc.user_id = u.id
                    WHERE c.status_ = 'Rejected'";
$rejectedCarsResult = $conn->query($rejectedCarsSql);
$rejectedCars = $rejectedCarsResult->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
<head>
    <title>Rejected Cars</title>
</head>

<body>
<h1>Rejected Cars</h1>

<table>
    <tr>
        <th>Brand</th>
        <th>Model</th>
        <th>Plate Number</th>
        <th>Status</th>
        <th>User Name</th>
        <th>Contact Info</th>
        <th>Action</th>
    </tr>
    <?php foreach ($rejectedCars as $car) { ?>
        <tr>
            <td><?php echo $car["brand"]; ?></td>
            <td><?php echo $car["model"]; ?></td>
            <td><?php echo $car["plateNum"]; ?></td>
            <td><?php echo $car["status_"]; ?></td>
            <td><?php echo $car["username"]; ?></td>
            <td><?php echo $car["contactinfo"]; ?></td>
            <td>
                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <input type="hidden" name="car_id" value="<?php echo $car["id"]; ?>">
                    <input type="submit" value="Confirm">
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

<a href="MainPage.php">Back to Car List</a>

</body>
</html>