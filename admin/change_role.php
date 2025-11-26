<?php
include '../config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $role_id = $_POST['role'];

    $sql = "UPDATE users SET role_id = '$role_id' WHERE user_id = '$user_id'";
    if (mysqli_query($con, $sql)) {
        echo "<script>window.location.href = 'all_users.php';
        alert('Role Updated Successfully')
        </script>";
        exit;
    } 
    else {
        echo "Error updating role: " . mysqli_error($conn);
    }
}
?>
