<?php
require_once __DIR__ . '/session_manager.php';

if(!isset($_SESSION["username"]) || $_SESSION["userid"] == 1){
    header("Location: login.php");
    exit();
}
?>