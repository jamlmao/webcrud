<?php  session_start();

if (!isset($_SESSION["id"])) {
    header("location: login.php");
    exit();
}

require("dbconnect.php");

function updateCarStatus($carID, $newStatus, $conn) {
    $updateSql = "UPDATE tblcar SET status_ = :newStatus WHERE id = :carID";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(":newStatus", $newStatus, PDO::PARAM_STR);
    $updateStmt->bindParam(":carID", $carID, PDO::PARAM_INT);
    $updateStmt->execute();
}

if (isset($_GET["id"])) {
    $carID = $_GET["id"];

    $carSql = "SELECT c.brand, c.model, c.plateNum, c.image, u.username
                FROM tblcar c
                INNER JOIN rented_cars rc ON c.id = rc.car_id
                INNER JOIN tbluser u ON rc.car_id = u.id
                WHERE c.id = :carID";
                
    $carStmt = $conn->prepare($carSql);
    $carStmt->bindParam(":carID", $carID, PDO::PARAM_INT);
    $carStmt->execute();
    $carRow = $carStmt->fetch(PDO::FETCH_ASSOC);

    if ($carRow && $carRow["status_"] === "Pending") {
        if (isset($_POST["btnApprove"])) {
            updateCarStatus($carID, "Approved", $conn);
            header("location: cars.php");
            exit();
        }
    } else {
        header("location: cars.php");
        exit();
    }
} else {
    header("location: cars.php");
    exit();
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
    </tr>
    <?php foreach ($pendingCars as $car) { ?>
        <tr>
            <td><?php echo $car["brand"]; ?></td>
            <td><?php echo $car["model"]; ?></td>
            <td><?php echo $car["plateNum"]; ?></td>
            <td><?php echo $car["username"]; ?></td>
            <td><img src="data:image/jpeg;base64,<?php echo base64_encode($car["image"]); ?>" width="100px" height="100px"></td>
            <td>
                <form action="status_car.php?id=<?php echo $carID; ?>" method="POST">
                    <input type="hidden" name="carID" value="<?php echo $carID; ?>">
                    <button type="submit" name="btnApprove">Update Status</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

<a href="cars.php">Back to Car List</a>

</body>
</html>