<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("location: login.php");
    exit();
}

require("dbconnect.php");

if (isset($_GET["id"])) {
    $carID = $_GET["id"];

    $carSql = "SELECT brand, model, plateNum, image FROM tblcar WHERE id = :carID";
    $carValues = array(":carID" => $carID);

    $carResult = $conn->prepare($carSql);
    $carResult->execute($carValues);

    if ($carResult->rowCount() > 0) {
        $carRow = $carResult->fetch(PDO::FETCH_ASSOC);

        $carInfoSql = "SELECT horsepower, engine_, transmission, seatcap, fueltype FROM tblcar_info WHERE carID = :carID";
        $carInfoValues = array(":carID" => $carID);

        $carInfoResult = $conn->prepare($carInfoSql);
        $carInfoResult->execute($carInfoValues);

        if ($carInfoResult->rowCount() > 0) {
            $carInfoRow = $carInfoResult->fetch(PDO::FETCH_ASSOC);
        } else {
           
        }
    } else {
      
    }
} else {
  
    header("location: cars.php");
    exit();
}
?>

<html>
<head>
    <title>View Car</title>
</head>

<body>
<div class="image-container">
    <div class="overlay-image"></div>
</div>

<div>
    <h1><?php echo $carRow["brand"] . " " . $carRow["model"]; ?></h1>
    <h2>Plate Number: <?php echo $carRow["plateNum"]; ?></h2>
    <img src="data:image/jpeg;base64,<?php echo base64_encode($carRow["image"]); ?>" width="300px" height="300px">
    <h2>Car Information</h2>
    <p>Horsepower: <?php echo $carInfoRow["horsepower"]; ?></p>
    <p>Engine: <?php echo $carInfoRow["engine_"]; ?></p>
    <p>Transmission: <?php echo $carInfoRow["transmission"]; ?></p>
    <p>Seat Capacity: <?php echo $carInfoRow["seatcap"]; ?></p>
    <p>Fuel Type: <?php echo $carInfoRow["fueltype"]; ?></p>
</div>

<a href="cars.php">Back to Car List</a>

</body>
</html>