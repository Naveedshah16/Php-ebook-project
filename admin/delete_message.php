<?php
include("../config.php");

if (isset($_GET["id"])) {
    $messageId = intval($_GET["id"]);
    $query = "DELETE FROM messages WHERE message_id = $messageId";
    
    if (mysqli_query($con, $query)) {
        echo "<script>
            alert('Message deleted successfully!');
            window.location.href = 'messages.php';
        </script>";
    } else {
        echo "<script>
            alert('Error deleting message: " . mysqli_error($con) . "');
            window.location.href = 'messages.php';
        </script>";
    }
} else {
    header("Location: messages.php");
    exit();
}
?>