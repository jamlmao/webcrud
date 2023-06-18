  <?php      
        
        session_start();

            if (!isset($_SESSION["id"])) {
                header("location: login.php");
                exit();
            }

            require("dbconnect.php");

            if(isset($_REQUEST["logout"])){
                session_destroy();
                header("location: login.php");
                exit();
            }

            
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400;700&family=Roboto:ital,wght@0,300;0,400;0,700;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>MainPage</title>
</head>

<body>
    <header class="header"><h1><center>RENT N GO RENTAL SERVICES</center></h1></header>
    <section class="sidebar">
        <?php
        
            $username = $_SESSION["username"];

        ?>

        <h1>
            Hello Admin <span class="username"><?php echo $username; ?>!</span>
        </h1>
    
        <a href='add_car.php'><button>Add Car</button></a>
        <a href='status_car.php'><button>Requested Cars</button></a>
        <a href='status_car.php'><button>Rejected Request</button></a>


        <div class="logout-container">
        <a class="logout-button" href=".php?logout=<?php echo $_SESSION["id"]; ?>">LOG OUT</a>
        </div>
    </section>




    <main class="main">
        <div class="card">
            <?php


                $rentedCarsSql = " SELECT c.id, c.brand, c.model, c.plateNum, c.image, u.username,u.contactinfo, c.status_ 
                FROM tblcar c LEFT JOIN tbluser u ON c.id = u.id;  ";

                $rentedCarsResult = $conn->query($rentedCarsSql);
                $rentedCars = $rentedCarsResult->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="image-container">
                <div class="overlay-image"></div>
            </div>

           <center><h1>Rented Cars</h1></center>

            <table>
                <tr>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Plate Number</th>
                    <th>Contact Number</th>
                    <th>Renter</th>
                    <th>Image</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($rentedCars as $car) { ?>
                    <tr>
                        <td><?php echo $car["brand"]; ?></td>
                        <td><?php echo $car["model"]; ?></td>
                        <td><?php echo $car["plateNum"]; ?></td>
                        <td><?php echo ($car["status_"] != "Available") ? $car["contactinfo"] : ""; ?></td>
                        <td><?php echo ($car["status_"] != "Available") ? $car["username"] : ""; ?></td>
                        <td><img src="data:image/jpeg;base64,<?php echo base64_encode($car["image"]); ?>" width="150px" height="100px"></td>
                        <td><?php echo $car["status_"]; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="card">PIE CHART HERE</div>
    </main>
</body>

</html>
