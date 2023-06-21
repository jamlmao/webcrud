<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("location: login.php");
    exit();
}

require("dbconnect.php");

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
</head>

<body>
<h1>Car Rental History</h1>

<table>
    <tr>
        <th>Brand</th>
        <th>Model</th>
        <th>Plate Number</th>
        <th>Rental Date</th>
        <th>Username</th>
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
