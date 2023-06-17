<?php
session_start();

if(!isset($_SESSION["id"])){
	header("location: login.php"); 
	exit();
}


if (isset($_POST["btnSave"])) {
    require("dbconnect.php");


    $carID = $_SESSION["id"];
    $model = $_POST["model"];
    $horsepower = $_POST["horsepower"];
    $engine = $_POST["engine_"];
    $transmission = $_POST["transmission"];
    $seatcap = $_POST["seatcap"];
    $fueltype = $_POST["fueltype"];

    if (empty($model)) {
        echo "Please input a valid model!";
    } elseif (empty($horsepower)) {
        echo "Please input a valid horsepower!";
    } elseif (empty($engine)) {
        echo "Please input a valid engine!";
    } elseif (empty($transmission)) {
        echo "Please input a valid transmission!";
    } elseif (empty($seatcap)) {
        echo "Please input a valid Seat Capacity!";
    } elseif (empty($fueltype)) {
        echo "Please input a valid Fuel Type!";
    } else {
        $sql = "UPDATE tblcar_info SET horsepower = :horsepower, engine_ = :engine, transmission = :transmission, seatcap = :seatcap, fueltype = :fueltype WHERE carID = :carID";

        $values = array(
            ":horsepower" => $horsepower,
            ":engine" => $engine,
            ":transmission" => $transmission,
            ":seatcap" => $seatcap,
            ":fueltype" => $fueltype,
            ":carID" => $carID,
        );

        $result = $conn->prepare($sql);
        $result->execute($values);

        if ($result->rowCount() > 0) {
            echo "Record has been saved!";
        } else {
            echo "No record has been saved!";
        }

        $sql = "UPDATE tblcar SET model = :model WHERE id = :id";
        $values = array(
            ":model" => $model,
            ":id" => $carID
        );

        $result = $conn->prepare($sql);
        $result->execute($values);

        if ($result->rowCount() > 0) {
            echo "Model has been updated!";
        } else {
            echo "No model has been updated!";
        }
        header("location: cars.php");
        exit();
    }
}

?>



<html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400;700&family=Roboto:ital,wght@0,300;0,400;0,700;1,300&display=swap" rel="stylesheet">
    <title>Edit Car</title>
</head>
<body>
    <h1>Edit Car</h1>
    <div id="image-container">
        <img id="car" src="img/custom.png">
        <form action="edit_car.php" method="POST" enctype="multipart/form-data">
        
            <div id="carname">
                <label>Car Name:</label>
                <input type="text" name="model"><br/>
            </div>
            <div id="horse">
                <label>Horsepower:</label>
                <input type="text" name="horsepower"><br/>
            </div>
            <div id="engine">
                <label>Engine:</label>
                <input type="text" name="engine_"><br/>
            </div>
            <div id="trans">
                <label>Transmission:</label>
                <input type="text" name="transmission"><br/>
            </div>
            <div id="seatcapacity">
                <label>Seat Capacity:</label>
                <input type="number" name="seatcap"><br/>
            </div>
            <div id="fuel">
                <label>Fuel type:</label>
                <input type="text" name="fueltype"><br/>
            </div>
        
            <button type="submit" name="btnSave">Save Changes</button>
        </form>
    </div>

    <style>
        button {
            height: 10%;
            width: 15%;
            color: black;
            font-family: 'Roboto';
            margin-left: 75%;
        }

        .input-box input {
            width: 200px;
            height: 30px;
            border: none;
            outline: none;
            font-size: 14px;
            padding: 5px;
        }

        body {
            background-color: #2D3142;
            margin: 0;
        }

        #car {
            width: 400px;
        }
    </style>
</body>
</html>
