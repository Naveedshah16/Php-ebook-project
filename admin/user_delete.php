<?php
include "../config.php";

$idToBeSelected = $_GET["id"];
   $data = mysqli_query($con, "SELECT * FROM users where user_id = $idToBeSelected");
    $data = mysqli_fetch_assoc($data);
    if($data["role_id"] != 1){
        $deleteQuery ="DELETE FROM `users` WHERE user_id = $idToBeSelected";
        $result = mysqli_query($con, $deleteQuery);

        if($result){
            echo "<script>alert('User Deleted Successfully');
             window.location.href = 'all_users.php';</script>";
        }
        else{
            echo "<script>alert('Somthing Went Wrong');
             window.location.href = 'all_users.php';</script>";
        }
    }
    else{
        echo "<script>alert('Admin Can Not Delete!');
        window.location.href = 'all_users.php';</script>";
    }

?>