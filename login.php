<?php
session_start();

if (isset($_POST["btnLogin"])) {
    require("dbconnect.php");
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM tbluser WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":username", $username);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
       
        if (password_verify($password, $user['passcode'])) {
           
            if ($user['accounttype'] == 'Admin') {
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $username;
                header("Location:MainPage.php");
                exit();
            } 
        } else {
            $errorMsg = "Invalid password!";
        }
    } else {
        $errorMsg = "Invalid username!";
    }
}

function validatePassword($password){
	

	$valid = TRUE;
	
	if(!preg_match("/[a-z]/", $password)){
		$valid = FALSE;
	}  else if(!preg_match("/[0-9]/", $password)){
		$valid = FALSE;
	}
	
	return $valid;
}







?>

<html>
<head>
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400;700&family=Roboto:ital,wght@0,300;0,400;0,700;1,300&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #2D3142;
            font-family:'Roboto' , sans-serif;
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
            background-color: #EAE8FF;
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

        #register-btn {
            background-color: #D8D5DB;
            border: none;
            border-radius: 3px;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            padding: 10px 20px;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        #register-btn:hover {
            background-color: #EAE8FF;
        }
    </style>
</head>
<body>
    <img id="logo" src="img/logo2.png">

    <form action="login.php" method="POST">
        <h2>Login</h2>
        <?php if (!empty($errorMsg)) { ?>
            <p class="error-message"><?php echo $errorMsg; ?></p>
        <?php } ?>

        <label>Username:</label>
        <input type="text" name="username"><br>

        <label>Password:</label>
        <input type="text" name="password"><br>

        <button type="submit" name="btnLogin">Login</button>
        <button id="register-btn" onclick="location.href='register.php'" type="button">Register</button>
    </form>
</body>
</html>