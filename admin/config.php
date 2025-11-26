<?php

$con = mysqli_connect("localhost", "root", "", "ebook");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

?>