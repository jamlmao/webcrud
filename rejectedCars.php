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
        }

    </style> 
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