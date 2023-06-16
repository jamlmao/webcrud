<?php

$errorMsg = "";

if (isset($_POST["btnSave"])) {
    require("dbconnect.php");
    $name_ = $_POST["name_"];
    $username = $_POST["username"];
    $passcode = password_hash($_POST["passcode"], PASSWORD_BCRYPT);

    if (empty($name_)) {
        $errorMsg = "Please enter a valid name!";
    } else if (empty($username)) {
        $errorMsg = "Please enter a valid username!";
    } else if (empty($passcode)) {
        $errorMsg = "Please enter a valid passcode!";
    } else {

        $sql = "INSERT INTO tbluser (name_,username,passcode) VALUES (:name_, :username, :passcode)";
        $values = array(
            ":name_" => $name_,
            ":username" => $username,
            ":passcode" => $passcode
        );

        $result = $conn->prepare($sql);
        $result->execute($values);

        if ($result->rowCount() > 0) {
            echo "Record has been saved!";
            header("Location: login.php");
            exit();
        } else {
            echo "No record has been saved!";
        }
    }
}

?>

<html>
<head>
    <title>Registration</title>
    <style>
        body {
            background-color: #2D3142;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h2 {
            color: #2D3142;
        }

        form {
            background-color: #ADACB5;
            border-radius: 5px;
            padding: 15px;
            text-align: left;
            width: 300px;
            margin-right: 100px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        input[type="text"],
        input[type="password"] {
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
            font-size: 14px;
            padding: 8px;
            width: 100%;
        }

        button[type="submit"] {
            background-color: #2D3142;
            border: none;
            border-radius: 3px;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            padding: 10px 20px;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
       
        #logo {
            width: 20%;
            height: 25%; 
            margin: 5%;
            display: block;
        }


       

    </style>
</head>
<body>




<img id="logo" src="img/logo2.png">

<form action="register.php" method="POST">
        <h2>Registration</h2>
    <?php if (!empty($errorMsg)) { ?>
        <p class="error-message"><?php echo $errorMsg; ?></p>
    <?php } ?>
    

    <label>Full Name:</label>
    <input type="text" name="name_"><br>

    <label>Username:</label>
    <input type="text" name="username"><br>

    <label>Passcode:</label>
    <input type="password" name="passcode"><br>

    <button type="submit" name="btnSave">Register</button>
</form>

</body>
</html>