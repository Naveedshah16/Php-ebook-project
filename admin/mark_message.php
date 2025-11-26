<?php
include("../config.php");

if (isset($_GET["id"]) && isset($_GET["action"])) {
    $messageId = intval($_GET["id"]);
    $action = $_GET["action"]; 
    
    $isRead = ($action == "read") ? 1 : 0;
    $query = "UPDATE messages SET is_read = $isRead WHERE message_id = $messageId";
    
    if (mysqli_query($con, $query)) {
        echo "<script>
            alert('Message marked as " . ($isRead ? "read" : "unread") . " successfully!');
            window.location.href = 'messages.php';
        </script>";
    } else {
        echo "<script>
            alert('Error updating message: " . mysqli_error($con) . "');
            window.location.href = 'messages.php';
        </script>";
    }
} else {
    header("Location: messages.php");
    exit();
}
?>