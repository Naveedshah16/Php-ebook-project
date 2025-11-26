<?php 
include("header.php");

$categories = mysqli_query($con, "SELECT * from categories");
$authors = mysqli_query($con, "SELECT * from authors");

if(isset($_POST["addbook"])){

    $bookImageName = $_FILES["bookcover"]["name"];
    $imageTempName = $_FILES["bookcover"]["tmp_name"];
    $isMoved = move_uploaded_file($imageTempName, "images/".$bookImageName);
    
    if($isMoved) {

        $bookTitle = $_POST["booktitle"];
        $bookCategory = $_POST["categoryid"];
        $bookAuthor = $_POST["bookauthor"];
        $bookPrice = $_POST["bookprice"];
        $bookDesc = $_POST["bookdescription"];
        $publishYear = $_POST["publishyear"];
      
        $deliveryOptions = array();
        if(isset($_POST["delivery_options"])) {
            $deliveryOptions = $_POST["delivery_options"];
        }
        $deliveryOptionsStr = implode(",", $deliveryOptions);
        
        $bookPdfName = '';
        if(!empty($_FILES["bookpdf"]["name"])) {
            $bookPdfName = $_FILES["bookpdf"]["name"];
            $pdfTempName = $_FILES["bookpdf"]["tmp_name"];
            move_uploaded_file($pdfTempName, "bookPdfFiles/".$bookPdfName);
        }
    
        $bookInsertQuery = "INSERT INTO `books`(`book_title`, `book_author`, `category_id`, `book_cover`, `book_pdf`, `book_price`, `book_description`, `publish_year`, `delivery_options`) VALUES ('$bookTitle','$bookAuthor',$bookCategory,'$bookImageName','$bookPdfName','$bookPrice','$bookDesc','$publishYear','$deliveryOptionsStr')";
        
        $result = mysqli_query($con, $bookInsertQuery);
        
        if($result) {
            echo "<script>alert('Book Added Successfully')</script>";
            echo "<script>window.location.href = 'all_books.php'</script>";
        } else {
            echo "<script>alert('Error adding book')</script>";
            echo "<script>window.location.href = 'add_book.php'</script>";
        }
    }
}
?>

<section class="p-5">
  
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-book-medical me-3"></i>Add New Book</h1>
        <a href="all_books.php" class="btn btn-secondary"><i class="fas fa-list me-2"></i>View All Books</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus-circle me-2"></i>Add Book Details Below</h6>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="booktitle" class="form-label"><i class="fas fa-book me-2"></i>Book Title *</label>
                            <input type="text" class="form-control" id="booktitle" placeholder="Enter book title" required name="booktitle">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="bookauthor" class="form-label"><i class="fas fa-user-edit me-2"></i>Author Name *</label>
                            <select class="form-control" id="bookauthor" required name="bookauthor">
                                <option value="">-- Select Author --</option>
                                <?php foreach($authors as $author){?>
                                <option value="<?php echo $author["author_name"]?>"><?php echo $author["author_name"]?></option>
                                <?php }?>
                                <option value="other">-- Add New Author --</option>
                            </select>
                            <div id="newAuthorField" style="display: none; margin-top: 10px;">
                                <input type="text" class="form-control" id="newauthor" name="newauthor" placeholder="Enter new author name">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="categoryid" class="form-label"><i class="fas fa-tags me-2"></i>Category *</label>
                            <select class="form-control" id="categoryid" required name="categoryid">
                                <option value="">-- Select Category --</option>
                                <?php while($category = mysqli_fetch_assoc($categories)){?>
                                <option value="<?php echo $category["category_id"]?>"><?php echo $category["category_name"]?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="bookprice" class="form-label"><i class="fas fa-dollar-sign me-2"></i>Price ($) *</label>
                            <input type="number" step="0.01" class="form-control" id="bookprice" placeholder="0.00" required name="bookprice">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="publishyear" class="form-label"><i class="fas fa-calendar me-2"></i>Publish Year</label>
                            <input type="number" class="form-control" id="publishyear" placeholder="2024" name="publishyear">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="bookcover" class="form-label"><i class="fas fa-image me-2"></i>Book Cover Image *</label>
                            <input type="file" class="form-control" id="bookcover" accept="image/*" required name="bookcover">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="bookpdf" class="form-label"><i class="fas fa-file-pdf me-2"></i>Book PDF File</label>
                            <input type="file" class="form-control" id="bookpdf" accept=".pdf" name="bookpdf">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label"><i class="fas fa-truck me-2"></i>Delivery Options</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="download" id="deliveryDownload" name="delivery_options[]">
                                <label class="form-check-label" for="deliveryDownload">
                                    Digital Download
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="cd" id="deliveryCd" name="delivery_options[]">
                                <label class="form-check-label" for="deliveryCd">
                                    CD Delivery
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="hardcopy" id="deliveryHardcopy" name="delivery_options[]">
                                <label class="form-check-label" for="deliveryHardcopy">
                                    Hard Copy
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="bookdescription" class="form-label"><i class="fas fa-align-left me-2"></i>Description *</label>
                    <textarea class="form-control" id="bookdescription" rows="4" placeholder="Enter book description" required name="bookdescription"></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg" name="addbook">
                        <i class="fas fa-plus-circle me-2"></i>Add Book
                    </button>
                    <a href="all_books.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
document.getElementById('bookauthor').addEventListener('change', function() {
    var newAuthorField = document.getElementById('newAuthorField');
    if (this.value === 'other') {
        newAuthorField.style.display = 'block';
    } else {
        newAuthorField.style.display = 'none';
    }
});
</script>

<?php include("footer.php") ?>