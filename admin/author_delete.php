<?php
include("../config.php");
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: all_authors.php");
    exit();
}

$authorId = intval($_GET['id']);
$checkQuery = "SELECT * FROM authors WHERE author_id = $authorId";
$checkResult = mysqli_query($con, $checkQuery);

if (mysqli_num_rows($checkResult) == 0) {
    header("Location: all_authors.php");
    exit();
}

$bookCheckQuery = "SELECT COUNT(*) as bookCount FROM books WHERE book_author IN (SELECT author_name FROM authors WHERE author_id = $authorId)";
$bookCheckResult = mysqli_query($con, $bookCheckQuery);
$bookCountRow = mysqli_fetch_assoc($bookCheckResult);
$bookCount = $bookCountRow['bookCount'];

if ($bookCount > 0) {
    echo "<script>
        alert('Cannot delete this author because there are $bookCount book(s) associated with them. Please update those books first.');
        window.location.href = 'all_authors.php';
    </script>";
    exit();
}
$deleteQuery = "DELETE FROM authors WHERE author_id = $authorId";

if (mysqli_query($con, $deleteQuery)) {
    echo "<script>
        alert('Author Deleted Successfully!');
        window.location.href = 'all_authors.php';
    </script>";
} else {
    echo "<script>
        alert('Error deleting author: " . mysqli_error($con) . "');
        window.location.href = 'all_authors.php';
    </script>";
}
?>