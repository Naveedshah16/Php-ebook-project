<?php
session_start();

function checkLogin()
{
    if (!isset($_SESSION['userid'])) {
        header("Location: login.php");
        exit();
    }
}
?>
