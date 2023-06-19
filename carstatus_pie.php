<?php
require("dbconnect.php");

$en_trends_arr = array();
				
	$sql = "SELECT status_, COUNT(id) AS total FROM tblcar GROUP BY status_ ORDER BY status_ DESC";
	
	try {
										
		$result = $conn->prepare($sql);
		
		$result->execute();
		
		if($result->rowCount()>0){
			
			while($row = $result->fetch(PDO::FETCH_ASSOC)){									
				$en_trends_arr[] = array("status_" => $row["status_"], "num" => $row["total"]);
			}
			
		}
		
		exit(json_encode($en_trends_arr));
		
	} catch(Exception $e){
		exit("Unexpected error has been occurred!");
	}

?>