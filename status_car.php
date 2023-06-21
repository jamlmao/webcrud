<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("location: login.php");
    exit();
}

require("dbconnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["car_id"]) && isset($_POST["status"])) {
        $carId = $_POST["car_id"];
        $status = $_POST["status"];
        $isRejected = $status == "Rejected" ? "is_rejected + 1" : "is_rejected";

        $updateStatusSql = "UPDATE tblcar SET status_ = :status, is_rejected = $isRejected";

        if ($status == "Approved") {
            $updateStatusSql .= ", approval_date = NOW()"; // Add the current date and time
        }

        $updateStatusSql .= " WHERE id = :car_id";
        $stmt = $conn->prepare($updateStatusSql);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":car_id", $carId);
        $stmt->execute();

        if ($status == "Approved") {
            $incrementCountSql = "UPDATE tblcar SET approval_count = approval_count + 1 WHERE id = :car_id";
            $stmt = $conn->prepare($incrementCountSql);
            $stmt->bindParam(":car_id", $carId);
            $stmt->execute();
        } elseif ($status == "Rejected") {
            $updateAvailabilitySql = "UPDATE tblcar SET status_ = 'Rejected' WHERE id = :car_id";
            $stmt = $conn->prepare($updateAvailabilitySql);
            $stmt->bindParam(":car_id", $carId);
            $stmt->execute();
        }

        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    }
}

$pendingCarsSql = "SELECT c.id, c.brand, c.model, c.plateNum, c.image, u.username, c.status_, c.approval_count, c.is_rejected
            FROM tblcar c
            INNER JOIN rented_cars rc ON c.id = rc.car_id
            INNER JOIN tbluser u ON rc.user_id = u.id
            WHERE c.status_ = 'Pending'";

$pendingCarsResult = $conn->query($pendingCarsSql);
$pendingCars = $pendingCarsResult->fetchAll(PDO::FETCH_ASSOC);
?>

<html>

<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400;700&family=Roboto:ital,wght@0,300;0,400;0,700;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
        body {
            background-color: #ADACB5;
            color: #2D3142;
            font-family: 'Roboto', sans-serif;
            font-size: 20px;
        }

        

        h1 {
            text-align: center;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        select {
            padding: 5px 10px;
            font-size: 16px;
            background-color: #D8D5DB;
        }

        option[value="Approved"] {
            background-color: green;
            color: white;
        }

        option[value="Rejected"] {
            background-color: red;
            color: white;
        }
        button {
            background-color: #ADACB5;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            color: #2D3142;
            font-weight: bold;
            font-size: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;

        }
        #home {
            text-decoration: none;
            width: 10px;
            font-size: 50px;
            color: inherit;
        }
     
        .approved {
            background-color: green;
            color: white;
        }

        .rejected {
            background-color: red;
            color: white;
        }

        .update-button {
            background-color: #ADACB5;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            color: #2D3142;
            font-weight: bold;
            font-size: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .update-button:hover {
            background-color: #D8D5DB;
        }
                

    </style>



    <title>Pending Cars</title>
</head>

<body>
<a id="home" href="MainPage.php"><i class="fa fa-home"></i></a>
<h1>Pending Cars</h1>
<table>
    <tr>
        <th>Brand</th>
        <th>Model</th>
        <th>Plate Number</th>
        <th>User Name</th>
        <th>Image</th>
        <th>Action</th>
    </tr>
    <?php foreach ($pendingCars as $car) { ?>
        <tr>
            <td><?php echo $car["brand"]; ?></td>
            <td><?php echo $car["model"]; ?></td>
            <td><?php echo $car["plateNum"]; ?></td>
            <td><?php echo $car["username"]; ?></td>
            <td><img src="data:image/jpeg;base64,<?php echo base64_encode($car["image"]); ?>" width="150px" height="120px"></td>
            <td>
                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <input type="hidden" name="car_id" value="<?php echo $car["id"]; ?>">
                    <select name="status">
                        <option value="Approved" <?php if ($car["status_"] == "Approved") echo "selected"; ?>>Approved</option>
                        <option value="Rejected" <?php if ($car["status_"] == "Rejected") echo "selected"; ?>>Rejected</option>
                    </select>
                    <input type="submit" value="Confirm" class="update-button <?php echo ($car["status_"] == "Approved") ? "Approved" : "Rejected"; ?>">
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

   

</body>
</html>
