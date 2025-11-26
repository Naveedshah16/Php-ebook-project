<?php
include "../config.php";

if(isset($_GET["id"])) {
    $idToBeSelected = $_GET["id"];
    $data = mysqli_query($con, "SELECT * FROM books WHERE book_id = $idToBeSelected");
    $book = mysqli_fetch_assoc($data);
    
    if($book) {
        if(!empty($book['book_cover'])) {
            $coverPath = "images/" . $book['book_cover'];
            if(file_exists($coverPath)) {
                unlink($coverPath);
            }
        }
        
        if(!empty($book['book_pdf'])) {
            $pdfPath = "bookPdfFiles/" . $book['book_pdf'];
            if(file_exists($pdfPath)) {
                unlink($pdfPath);
            }
        }
        
        $deleteQuery = "DELETE FROM `books` WHERE book_id = $idToBeSelected";
        $result = mysqli_query($con, $deleteQuery);

        if($result) {
            echo "<script>alert('Book Deleted Successfully');
             window.location.href = 'all_books.php';</script>";
        } else {
            echo "<script>alert('Something Went Wrong');
             window.location.href = 'all_books.php';</script>";
        }
    } else {
        echo "<script>alert('Book Not Found');
        window.location.href = 'all_books.php';</script>";
    }
} else {
    echo "<script>alert('Invalid Request');
    window.location.href = 'all_books.php';</script>";
}

?>