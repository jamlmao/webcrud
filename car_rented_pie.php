<?php
require("dbconnect.php");

$en_trends_arr = array();

$sql = "SELECT model, approval_count, COUNT(id) AS total, is_rejected FROM tblcar GROUP BY model, approval_count, is_rejected ORDER BY model ASC";

try {
    $result = $conn->prepare($sql);
    $result->execute();

    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $en_trends_arr[] = array(
                "model" => $row["model"],
                "approval_count" => $row["approval_count"],
                "approval_count_label" => "Rent Count: " . $row["approval_count"],
                "num" => $row["approval_count"],
                "is_rejected" => $row["is_rejected"]
            );
        }
    }

    exit(json_encode($en_trends_arr));
} catch (Exception $e) {
    exit("Unexpected error has occurred!");
}
?>