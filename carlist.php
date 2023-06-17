<?php
session_start();

if(!isset($_SESSION["userID"])){
	header("location: login.php"); 
	exit();
}

if(isset($_REQUEST["logout"])){
	session_destroy();
	header("location: login.php");
	exit();
}
	
	if(isset($_REQUEST['id'])){
		
		try {
			
			require("dbconnect.php");
		
			$sql = "UPDATE tblcar_info SET is_delete = '1' WHERE id  = :id";
			
			$values = array(":id" => $_REQUEST['id']);
			
			$result = $conn->prepare($sql);
			$result->execute($values);
			
			if($result->rowCount()>0){
				header("location:list_cars.php");
				exit();
			}
			
		} catch(PDOException $e){
			exit("Unexpected error has been occurred!" . $e);
		}
		
	}
	
?>

<html>
	
	<head>
		<title>List of Cars</title>
		
		<style>
			li {
				list-style-type: none;
				padding:5px;
				border:1px solid #ccc;
			}
		</style>
	</head>
	
<body onLoad = "loadCars()">
	
	<ul style = "display:flex;">
		<li>HOME</li>
		<li>SET UP</li>
		<li>TRANSACTIONS</li>
		<li>REPORTS</li>
		<li><a href="list_cars.php?logout=<?php echo $_SESSION["userID"]; ?>">LOG OUT</a></li>
	</ul>
	
	<a href="add_record.php">New Record</a>
	<input type = "text" id="txtSearch" onKeyUp = "search(event);">
	<select name = "year" id = "cboYear" onChange = "loadCars()">
		<option value = "">Please select year</option>
		<?php
		
			$year = date("Y");
			
			for($y=$year; $y>=2000; $y--){
				echo "<option value = '" . $y . "'>" . $y . "</option>";
			}
			
		?>
	</select>
	
	<select id = "cboBrand" onChange = "loadCars()">
		<option value = "">Please select brand</option>
		<option value = "Toyota">Toyota</option>
	</select>
	
	<div id="htmlContent"></div>
	
</body>
</html>

<script>
	function search(event){
		
		
		if(event.keyCode == 13){
		
			let search = document.getElementById("txtSearch").value;
			
			if(search.length >= 3){
				loadCars();
			} else if(search.length == 0){
				loadCars();
			}	
		} 
		
	}

	function loadCars(){
		
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("htmlContent").innerHTML = this.responseText;
			}
			
		};
		
		let search = document.getElementById("txtSearch").value;
		let year = document.getElementById("cboYear").value;
		let brand = document.getElementById("cboBrand").value;
		
		xhttp.open("GET", "cars.php?search="+search+"&year="+year+"&brand="+brand, true);
		xhttp.send();
	
	}
	
</script>