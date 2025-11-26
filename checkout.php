<?php
include "header.php";

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

 
include "footer.php";
?>

