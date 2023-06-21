<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400;700&family=Roboto:ital,wght@0,300;0,400;0,700;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
    <title>Car List</title>
    <style>
       

        td {
            padding: 5px;
            text-align: center;
        }

        body {
            font-weight: bold;
            background-color: #2D3142;
            font-family: 'Roboto', sans-serif;
        }

        .header {
            display: flex;
            justify-content: space-around;
            align-items: center;
            font-weight: bold;
        }

        .container {
            background-color: #ADACB5;
            padding: 10px 30px;
            border-radius: 10px;
            margin: 20px;
            width: 90%;
            box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
        }

        button {
            border-radius: 10px;
            height: 40px;
            width: 150px;
        }

        .update-button {
            margin-left: 1%;
            width: 150px;
            background-color: #4CAF50;
            color: white;
        }

        .unavailable-button {
            margin-left: 1%;
            width: 150px;
            background-color: #FF0000;
            color: white;
            cursor: not-allowed;
        }


        #home {
            text-decoration: none;
            width: 10px;
            font-size: 50px;
            color: inherit;
        }

        
    </style>
</head>
<body>
    <div class="header">
        <img src="img/logo2.png">
    </div>
    
    <div class="container">
        <a id="home" href="MainPage.php"><i class="fa fa-home"></i></a>
        <table border="0" width="100%">
            <caption><h1>Car List</h1></caption>
            <thead>
                <th></th>
                <th>#</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Status</th>
            </thead>
            <tbody>
                <?php
                session_start();
                try {
                    if (!isset($_SESSION["id"])) {
                        header("location: login.php");
                        exit();
                    }
    
                    require("dbconnect.php");
    
                    $sql = "SELECT id, brand, model,status_, image FROM tblcar";
                    $result = $conn->prepare($sql);
                    $result->execute();
    
                    if ($result->rowCount() > 0) {
                        $i = 1;
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr style='border:none; background-color: #ADACB5;'>
                                <td><img src='data:image/jpeg;base64," . base64_encode($row["image"]) . "' width='250px' height='120px'></td>
                                <td>" . $row["id"] . "</td>
                                <td style='text-align:center;'>" . $row["brand"] . "</td>
                                <td>" . $row["model"] . "</td>
                                <td>" . $row["status_"] . "</td>
                                <td>";
                             echo   "<a href='edit_car.php?id=" . $row["id"] . "'><button style='margin-left:1%; width:150px;'>Edit</button></a> |
                                <a href='view_car.php?id=" . $row["id"] . "'><button style='margin-left:1%; width:150px;'>View</button></a> |";
                          
                          
                                if ($row["status_"] === "Available") {
                                echo "<button class='unavailable-button' disabled>Car is Available</button>";
                            } else {
                                echo "
                                    <form method='POST' action='update_status.php' style='display: inline;'>
                                        <input type='hidden' name='car_id' value='" . $row["id"] . "'>
                                        <button class='update-button' type='submit' name='update_status'>Car has been returned</button>
                                    </form>";
                            }
                            
                            echo "</td></tr>";
                            $i++;
                        }
                        echo "<tr>
                            <td colspan='10'><i>Nothing Follows</i><hr></td>
                        </tr>";
                    } else {
                        echo "<tr><td colspan='10'><i>No records found!</i></td></tr>";
                    }
                } catch (PDOException $e) {
                    die("Unexpected error has occurred!" . $e);
                }
                ?>
            </tbody>
        </table>
        
    </div>
</body>
</html>
