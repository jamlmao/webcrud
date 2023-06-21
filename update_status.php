<?php
// update_status.php

session_start();
if (!isset($_SESSION["id"])) {
    header("location: login.php");
    exit();
}

require("dbconnect.php");

if (isset($_POST["update_status"]) && isset($_POST["car_id"])) {
    $carId = $_POST["car_id"];
    
    try {
      
        $sql = "UPDATE tblcar SET status_ = 'Available' WHERE id = :carId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":carId", $carId);
        $stmt->execute();
     
        header("location: cars.php");
        exit();
    } catch (PDOException $e) {
        die("Unexpected error has occurred: " . $e->getMessage());
    }
} else {
   
    header("location: cars.php");
    exit();
}
?>
