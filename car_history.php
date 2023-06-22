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

$carHistorySql = "SELECT c.brand, c.model, c.plateNum, u.username, c.approval_date
                  FROM tblcar c
                  INNER JOIN rented_cars rc ON c.id = rc.car_id
                  INNER JOIN tbluser u ON rc.user_id = u.id
                  WHERE c.brand IS NOT NULL AND c.model IS NOT NULL AND c.plateNum IS NOT NULL
                  ORDER BY c.brand, c.model, c.plateNum";
$carHistoryResult = $conn->query($carHistorySql);
$carHistory = $carHistoryResult->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
<head>
    <title>Car Rental History</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400;700&family=Roboto:ital,wght@0,300;0,400;0,700;1,300&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #ADACB5;
            color: #2D3142;
            font-family: 'Roboto', sans-serif;
            font-size: 20px;
            height: 100vh;
            text-align: center;
        }

        

        h1 {
            text-align: center;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #D8D5DB;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #D8D5DB;
        }

        th {
            background-color: #2D3142;
            color: #D8D5DB;
            
        }

        tr:nth-child(even) {
            background-color: #D8D5DB;
        }
        tr:nth-child(odd) {
            background-color: #48639C;
        }

        form {
            display: inline;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #2D3142;
            text-decoration: none;
            font-size: larger;
            font-weight: bold;
        }

        img {
            display: block;
            margin: 0 auto;
        }

     
    </style>
</head>

<body>
<h1>Car Rental History</h1>

<h2>Approved Cars</h2>
<table>
    <tr>
        <th>Brand</th>
        <th>Model</th>
        <th>Plate Number</th>
        <th>Name</th>
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

<h2>Car Rental History</h2>
<table>
    <tr>
        <th>Brand</th>
        <th>Model</th>
        <th>Plate Number</th>
        <th>Rental Date</th>
        <th>Name</th>
    </tr>
    <?php
    $previousCar = null;
    foreach ($carHistory as $car) {
        if ($car != $previousCar) {
            $sameCarRentalsSql = "SELECT u.username, c.approval_date
                                  FROM tblcar c
                                  INNER JOIN rented_cars rc ON c.id = rc.car_id
                                  INNER JOIN tbluser u ON rc.user_id = u.id
                                  WHERE c.brand = :brand AND c.model = :model AND c.plateNum = :plateNum
                                  ORDER BY c.brand, c.model, c.plateNum";

            $sameCarRentalsStmt = $conn->prepare($sameCarRentalsSql);
            $sameCarRentalsStmt->bindParam(":brand", $car["brand"]);
            $sameCarRentalsStmt->bindParam(":model", $car["model"]);
            $sameCarRentalsStmt->bindParam(":plateNum", $car["plateNum"]);
            $sameCarRentalsStmt->execute();
            $sameCarRentals = $sameCarRentalsStmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <tr>
                <td rowspan="<?php echo count($sameCarRentals); ?>"><?php echo $car["brand"]; ?></td>
                <td rowspan="<?php echo count($sameCarRentals); ?>"><?php echo $car["model"]; ?></td>
                <td rowspan="<?php echo count($sameCarRentals); ?>"><?php echo $car["plateNum"]; ?></td>
                <td><?php echo $sameCarRentals[0]["approval_date"]; ?></td>
                <td><?php echo $sameCarRentals[0]["username"]; ?></td>
            </tr>
            <?php
            for ($i = 1; $i < count($sameCarRentals); $i++) {
                ?>
                <tr>
                    <td><?php echo $sameCarRentals[$i]["approval_date"]; ?></td>
                    <td><?php echo $sameCarRentals[$i]["username"]; ?></td>
                </tr>
                <?php
            }
            $previousCar = $car;
        }
    }
    ?>
</table>

<a href="MainPage.php">Back to Car List</a>

</body>
</html>
