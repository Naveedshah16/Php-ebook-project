<?php 
include("header.php");
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: all_books.php");
    exit();
}

$idToBeUpdate = intval($_GET['id']);
$bookData = mysqli_query($con, "SELECT * FROM books WHERE book_id = $idToBeUpdate");
$bookData = mysqli_fetch_assoc($bookData);
$categories = mysqli_query($con, "SELECT * FROM categories");
$authors = mysqli_query($con, "SELECT * FROM authors ORDER BY author_name");

if (isset($_POST["updatebook"])) {
    $bookTitle = $_POST["booktitle"];
    $bookAuthor = $_POST["bookauthor"];
    $bookCategory = $_POST["categoryid"];
    $bookPrice = $_POST["bookprice"];
    $bookDesc = $_POST["bookdescription"];
    $publishYear = $_POST["publishyear"];

    $deliveryOptions = array();
    if(isset($_POST["delivery_options"])) {
        $deliveryOptions = $_POST["delivery_options"];
    }
    $deliveryOptionsStr = implode(",", $deliveryOptions);
    
    $bookImageInDb = $bookData["book_cover"];
    $newImageName = $bookImageInDb; 
    if(!empty($_FILES["bookcover"]["name"])){
        $newImageName = $_FILES["bookcover"]["name"];
        $newImageTemPath = $_FILES["bookcover"]["tmp_name"];

        if(move_uploaded_file($newImageTemPath, "images/".$newImageName)) {
            if(!empty($bookImageInDb)) {
                $oldImagePath = "images/".$bookImageInDb;
                if(file_exists($oldImagePath)){
                    unlink($oldImagePath);
                }
            }
        } else {
            $newImageName = $bookImageInDb;
        }
    }

    $bookPdfInDb = $bookData["book_pdf"];
    $newPdfName = $bookPdfInDb;
    if(!empty($_FILES["bookpdf"]["name"])){
        $newPdfName = $_FILES["bookpdf"]["name"];
        $newPdfTemPath = $_FILES["bookpdf"]["tmp_name"];
        if(move_uploaded_file($newPdfTemPath, "bookPdfFiles/".$newPdfName)) {
            if(!empty($bookPdfInDb)) {
                $oldPdfPath = "bookPdfFiles/".$bookPdfInDb;
                if(file_exists($oldPdfPath)){
                    unlink($oldPdfPath);
                }
            }
        } else {
            $newPdfName = $bookPdfInDb;
        }
    }
    
    $updateBookQuery = "UPDATE `books` SET 
                      `book_title`='$bookTitle',
                      `book_author`='$bookAuthor',
                      `category_id`= $bookCategory,
                      `book_cover`='$newImageName',
                      `book_pdf`='$newPdfName',
                      `book_price`= $bookPrice,
                      `book_description`='$bookDesc',
                      `publish_year`='$publishYear',
                      `delivery_options`='$deliveryOptionsStr' 
                      WHERE book_id = $idToBeUpdate";

    $res = mysqli_query($con, $updateBookQuery);

    if($res){
        echo "<script>alert('Book Updated Successfully')</script>";
        echo "<script>window.location.href = 'show_book.php?id=$idToBeUpdate'</script>";
    }
    else{
        echo "<script>alert('Updated Error')</script>";
        echo "<script>window.location.href = 'show_book.php?id=$idToBeUpdate'</script>";
    }
}
?>

<section class="p-5">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-book-medical me-3"></i>Edit Book</h1>
        <a href="show_book.php?id=<?php echo $bookData['book_id']; ?>" class="btn btn-secondary"><i class="fas fa-eye me-2"></i>View Book</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-edit me-2"></i>Edit Book Details Below</h6>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="booktitle" class="form-label"><i class="fas fa-book me-2"></i>Book Title *</label>
                            <input type="text" class="form-control" id="booktitle" placeholder="Enter book title" required name="booktitle" value="<?php echo htmlspecialchars($bookData["book_title"]); ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="bookauthor" class="form-label"><i class="fas fa-user-edit me-2"></i>Author Name *</label>
                            <select class="form-control" id="bookauthor" required name="bookauthor">
                                <option value="">-- Select Author --</option>
                                <?php foreach ($authors as $author) { ?>
                                <option <?php if($bookData["book_author"] == $author["author_name"]) echo "selected"?> value="<?php echo htmlspecialchars($author["author_name"]); ?>"><?php echo htmlspecialchars($author["author_name"]); ?></option>
                                <?php } ?>
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
                                <?php while($category = mysqli_fetch_assoc($categories)) { ?>
                                <option <?php if($bookData["category_id"] == $category["category_id"]) echo "selected"?> value="<?php echo $category["category_id"]; ?>"><?php echo $category["category_name"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="bookprice" class="form-label"><i class="fas fa-dollar-sign me-2"></i>Price ($) *</label>
                            <input type="number" step="0.01" class="form-control" id="bookprice" placeholder="0.00" required name="bookprice" value="<?php echo $bookData["book_price"]; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="publishyear" class="form-label"><i class="fas fa-calendar me-2"></i>Publish Year</label>
                            <input type="number" class="form-control" id="publishyear" placeholder="2024" name="publishyear" value="<?php echo $bookData["publish_year"]; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="bookcover" class="form-label"><i class="fas fa-image me-2"></i>Book Cover Image</label>
                            <?php if(!empty($bookData['book_cover'])): ?>
                            <div class="mb-2">
                                <img width="100px" src="images/<?php echo $bookData['book_cover']; ?>" alt="<?php echo $bookData['book_title']; ?>">
                            </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="bookcover" accept="image/*" name="bookcover">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="bookpdf" class="form-label"><i class="fas fa-file-pdf me-2"></i>Book PDF File</label>
                            <?php if(!empty($bookData['book_pdf'])): ?>
                            <div class="mb-2">
                                <small class="form-text text-muted">Current PDF: <?php echo $bookData['book_pdf']; ?></small>
                            </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="bookpdf" accept=".pdf" name="bookpdf">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label"><i class="fas fa-truck me-2"></i>Delivery Options</label>
                            <?php 
                            $bookDeliveryOptions = explode(',', $bookData['delivery_options']);
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="download" id="deliveryDownload" name="delivery_options[]" <?php echo in_array('download', $bookDeliveryOptions) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="deliveryDownload">
                                    Digital Download
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="cd" id="deliveryCd" name="delivery_options[]" <?php echo in_array('cd', $bookDeliveryOptions) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="deliveryCd">
                                    CD Delivery
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="hardcopy" id="deliveryHardcopy" name="delivery_options[]" <?php echo in_array('hardcopy', $bookDeliveryOptions) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="deliveryHardcopy">
                                    Hard Copy
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="bookdescription" class="form-label"><i class="fas fa-align-left me-2"></i>Description *</label>
                    <textarea class="form-control" id="bookdescription" rows="4" placeholder="Enter book description" required name="bookdescription"><?php echo htmlspecialchars($bookData["book_description"]); ?></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg" name="updatebook">
                        <i class="fas fa-save me-2"></i>Update Book
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