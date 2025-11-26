<?php
include("../config.php");

$idToBeSelected = $_GET["id"];
$deleteQuery = "DELETE FROM categories Where category_id = $idToBeSelected";

$response = mysqli_query($con, $deleteQuery);
if($response){
    header("location: all_categories.php");
}
else{
    echo"<script>alert('Somthing went wrong')</script>";
    header("location: all_categories.php");
}
?>