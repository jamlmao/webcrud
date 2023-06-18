<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("location: login.php");
    exit();
}

require("dbconnect.php");

$pendingCarsSql = "SELECT c.id, c.brand, c.model, c.plateNum, c.image, u.username, c.status_
            FROM tblcar c
            INNER JOIN rented_cars rc ON c.id = rc.car_id
            INNER JOIN tbluser u ON rc.user_id = u.id
            WHERE c.status_ = 'Pending'";

$pendingCarsResult = $conn->query($pendingCarsSql);
$pendingCars = $pendingCarsResult->fetchAll(PDO::FETCH_ASSOC);

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["car_id"]) && isset($_POST["status"])) {
        $carId = $_POST["car_id"];
        $status = $_POST["status"];

       
        $updateStatusSql = "UPDATE tblcar SET status_ = :status WHERE id = :car_id";
        $stmt = $conn->prepare($updateStatusSql);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":car_id", $carId);
        $stmt->execute();

       
        if ($status == "Rejected") {
            $updateAvailabilitySql = "UPDATE tblcar SET status_ = 'Available' WHERE id = :car_id";
            $stmt = $conn->prepare($updateAvailabilitySql);
            $stmt->bindParam(":car_id", $carId);
            $stmt->execute();
        }

        


    }
}
?>

<html>
<head>
    <title>Pending Cars</title>
</head>

<body>
<div class="image-container">
    <div class="overlay-image"></div>
</div>

<h1>Pending Cars</h1>

<table>
    <tr>
        <th>Brand</th>
        <th>Model</th>
        <th>Plate Number</th>
        <th>User Name</th>
        <th>Image</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php foreach ($pendingCars as $car) { ?>
        <tr>
            <td><?php echo $car["brand"]; ?></td>
            <td><?php echo $car["model"]; ?></td>
            <td><?php echo $car["plateNum"]; ?></td>
            <td><?php echo $car["username"]; ?></td>
            <td><img src="data:image/jpeg;base64,<?php echo base64_encode($car["image"]); ?>" width="100px" height="100px"></td>
            <td><?php echo $car["status_"]; ?></td>
            <td>
                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <input type="hidden" name="car_id" value="<?php echo $car["id"]; ?>">
                    <select name="status">
                        <option value="Approved" <?php if ($car["status_"] == "Approved") echo "selected"; ?>>Approved</option>
                        <option value="Rejected" <?php if ($car["status_"] == "Rejected") echo "selected"; ?>>Rejected</option>
                    </select>
                    <input type="submit" value="Update">
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

<a href="cars.php">Back to Car List</a>

</body>
</html>