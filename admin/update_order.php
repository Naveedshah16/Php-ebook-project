<?php
include("../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["order_id"])) {
    $orderId = intval($_POST["order_id"]);
    $orderStatus = $_POST["order_status"];
    $paymentMethod = $_POST["payment_method"];
    $query = "UPDATE orders SET order_status = '$orderStatus', payment_method = '$paymentMethod' WHERE order_id = $orderId";
    
    if (mysqli_query($con, $query)) {
        echo "<script>
            alert('Order updated successfully!');
            window.location.href = 'orders.php';
        </script>";
    } else {
        echo "<script>
            alert('Error updating order: " . mysqli_error($con) . "');
            window.location.href = 'orders.php';
        </script>";
    }
} else {
    header("Location: orders.php");
    exit();
}
?>