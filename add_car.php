<?php
session_start();

if(!isset($_SESSION["id"])){
	header("location: login.php"); 
	exit();
}


if (isset($_POST["btnSave"])) {
    require("dbconnect.php");
    $brand = $_POST["brand"];
    $model = $_POST["model"];
    $plateNum = $_POST["plateNum"];
    $status_ = "Available";
    $horsepower = $_POST["horsepower"];
    $engine = $_POST["engine"];
    $transmission = $_POST["transmission"];
    $seatcap = $_POST["seatcap"];
    $fueltype = $_POST["fueltype"];

    if (empty($brand)) {
        echo "Please input a valid brand!";
    } else if (empty($model)) {
        echo "Please input a valid model!";
    } else if (empty($plateNum)) {
        echo "Please input a valid plate number!";
    }  else if (empty($horsepower)) {
        echo "Please input a valid horsepower!";
    } else if (empty($engine)) {
        echo "Please input a valid engine!";
    } else if (empty($transmission)) {
        echo "Please input a valid transmission!";
    } else if (empty($seatcap)) {
        echo "Please input a valid seat capacity!";
    } else if (empty($fueltype)) {
        echo "Please input a valid fuel type!";
    } else {

        $carSql = "INSERT INTO tblcar (brand, model, plateNum,status_ ) VALUES (:brand, :model, :plateNum,:status_ )";
        $carValues = array(
            ":brand" => $brand,
            ":model" => $model,
            ":plateNum" => $plateNum,
            ":status_" => $status_
           
        );

        $carResult = $conn->prepare($carSql);
        $carResult->execute($carValues);

        $carID = $conn->lastInsertId();

        if ($carResult->rowCount() > 0) {
            echo "Car record has been saved!";
        } else {
            echo "No car record has been saved!";
        }


        $carInfoSql = "INSERT INTO tblcar_info (carID, horsepower, engine_, transmission, seatcap, fueltype) VALUES (:carID, :horsepower, :engine, :transmission, :seatcap, :fueltype)";
        $carInfoValues = array(
            ":carID" => $carID,
            ":horsepower" => $horsepower,
            ":engine" => $engine,
            ":transmission" => $transmission,
            ":seatcap" => $seatcap,
            ":fueltype" => $fueltype
        );

        $carInfoResult = $conn->prepare($carInfoSql);
        $carInfoResult->execute($carInfoValues);

        if ($carInfoResult->rowCount() > 0) {
            echo "Car info record has been saved!";
        } else {
            echo "No car info record has been saved!";
        }

      
        $rentedCarsSql = "INSERT INTO rented_cars (car_id, user_id) VALUES (:carID, :userID)";
        $rentedCarsValues = array(
            ":carID" => $carID,
            ":userID" => $_SESSION["id"] 
        );

        $rentedCarsResult = $conn->prepare($rentedCarsSql);
        $rentedCarsResult->execute($rentedCarsValues);

        if ($rentedCarsResult->rowCount() > 0) {
        } else {
        }
    }
}
?>

<html>
	<head>
		<title>New Record</title>
	</head>
<body>

<div class="image-container">
  <div class="overlay-image"></div>
</div>

<form action="add_car.php" method="POST">
    <label>Brand:</label>
    <input type="text" name="brand"><br/>

    <label>Model:</label>
    <input type="text" name="model"><br/>

    <label>Plate Number:</label>
    <input type="text" name="plateNum"><br/>

    <label><h2>CAR INFORMATIONS</h2></label>
    <label>Horsepower:</label>
    <input type="text" name="horsepower"><br/>

    <label>Engine:</label>
    <input type="text" name="engine"><br/>

    <label>Transmission:</label>
    <input type="text" name="transmission"><br/>

    <label>Seat Capacity:</label>
    <input type="text" name="seatcap"><br/>

    <label>Fuel Type:</label>
    <input type="text" name="fueltype"><br/>

    <button type="submit" name="btnSave">Save Changes</button>
</form>

</body>
</html>
