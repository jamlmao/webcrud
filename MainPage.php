  <?php      
        
        session_start();

            if (!isset($_SESSION["id"])) {
                header("location:login.php");
                exit();
            }

            require("dbconnect.php");

            if(isset($_REQUEST["logout"])){
                session_destroy();
                header("location:login.php");
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="index.js"></script>
    <title>MainPage</title>
</head>

<body onload="loadChartData()">
    <header class="header"><h1><center>RENT N GO RENTAL SERVICES</center></h1></header>

    <section class="sidebar">
        <?php
        
            $username = $_SESSION["username"];

        ?>

        <h3>
            Hello <span class="username"><?php echo $username; ?>!</span>
        </h3>
            <div class="sidebar-btn">
                <a href='cars.php'><button>Manage Car</button></a>
                <a href='status_car.php'><button>Requested Cars</button></a>
                <a href='rejectedCars.php'><button>Rejected Request</button></a>
                <a href='car_history.php'><button>User History</button></a>
            </div>
           

        <div class="logout-container">
        <a class="logout-button" href="MainPage.php?logout=<?php echo $_SESSION["id"]; ?>">LOG OUT</a>
        </div>
    </section>




    <main class="main">
        <div class="card">
            <div class="chart-title">
                <center><h1>Cars Status</h1></center>
                <center><h1>Number of Rentals</h1></center>
            </div>
            <hr>
            <div class="chart-container">
                <canvas id="myChart"></canvas>
                <canvas id="myChart2"></canvas>
            </div>
        </div>

        <div class="card">
     
        <center><h1>Rented Cars</h1></center>
            <?php

      $rentedCarsSql = "SELECT c.brand, c.model, c.plateNum, c.image, u.username, u.contactinfo, c.status_
                FROM tblcar c
                LEFT JOIN rented_cars rc ON c.id = rc.car_id
                LEFT JOIN tbluser u ON rc.user_id = u.id
                GROUP BY c.id";

                $rentedCarsResult = $conn->query($rentedCarsSql);
                $rentedCars = $rentedCarsResult->fetchAll(PDO::FETCH_ASSOC);
            ?>

           
            <div class="table-container">

            </div>
          

            <table>
                <tr>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Plate Number</th>
                    <th>Contact Number</th>
                    <th>Renter</th>
                    <th>Image</th>
                    
                </tr>
                <hr>
                <?php foreach ($rentedCars as $car) { ?>
                    <tr>
                        <td><?php echo $car["brand"]; ?></td>
                        <td><?php echo $car["model"]; ?></td>
                        <td><?php echo $car["plateNum"]; ?></td>
                        <td><?php echo ($car["status_"] != "Available") ? $car["contactinfo"] : ""; ?></td>
                        <td><?php echo ($car["status_"] != "Available") ? $car["username"] : ""; ?></td>
                        <td><img src="data:image/jpeg;base64,<?php echo base64_encode($car["image"]); ?>" width="150px" height="100px"></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

       
    </main>



<style>
    
body {
    height: 100%;
    display: grid;
    grid-template-columns: 250px 1fr;
    grid-template-rows: 60px 1fr;
    grid-template-areas: 
        "side header"
        "side main";
    font-family: 'Roboto';
    
}


.header {
    background-color: #ADACB5;
    grid-area: header;
}




.main {
    background-color: #2D3142;
    grid-area: main;
    display: block;
    grid-template-columns: 200px 1fr 1fr ; 
    grid-template-rows:  100px 1fr 1fr;
    gap:20px;
    padding: 20px;
    height: 140vh;
}



.card  {
    background-color: #D8D5DB;
    border-radius: 1px;
    margin: 10px;
    width: 140vh;
    height: 70vh;
    overflow: hidden;
    overflow-y: scroll;
   
}

.chart-container{
    display: flex;
    justify-content: space-around;
    align-items: center;
    width: 140vh;
    height: 50vh;
    
}

.chart-title {
    display: flex;
    justify-content: space-around;
    align-items: center;
}



h3 {
    font-family: 'Roboto Condensed', sans-serif;
    font-weight: 700;
    font-size: 24px;
    color: white;
    
}

table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
    
}

th,td {
    padding: 10px;
    text-align: center;
    font-family: 'Roboto', sans-serif;
    font-weight: 400;
    font-size: 14px;
    color: #2D3142;
    border-bottom: 1px solid #D8D5DB;
}

th {
    font-weight: 700;
}

img {
    display: block;
    margin: 0 auto;
    max-width: 100%;
    height: auto;
}


.card::-webkit-scrollbar {
    width: 1px;
  }
  
  .card::-webkit-scrollbar-track {
    background: #D8D5DB;
  }
  
  .card::-webkit-scrollbar-thumb {
    background: #888;
  }
  
  .card::-webkit-scrollbar-thumb:hover {
    background: #555;
  }

  
.sidebar {
    background-color: #48639C;
    grid-area: side;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}


.sidebar-btn{
    margin-top:100px ;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.sidebar a {
    text-decoration: none;
    margin-bottom: 10px;
}


.sidebar h3 {
    text-decoration: none;
    text-align: center;
    color: #ADACB5;
}


.sidebar button {
    background-color: #ADACB5;
    border: none;
    border-radius: 4px;
    padding: 10px 20px;
    color: #2D3142;
    font-family: 'Roboto', sans-serif;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.sidebar button:hover {
    background-color: #2D3142;
    color: #FFFFFF;
}

.logout-container {
    margin-right: 40px;
    margin-top: 400px;
  }
  
  .logout-button {
    display: block;
    width: 100%;
    padding: 10px 20px;
    background-color:#6b1d1d;
    color: #fff;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 700;
    transition: background-color 0.3s ease;
  }
  
  .logout-button:hover {
    background-color: #e63946;
  }
  

</style>





</body>

</html>
