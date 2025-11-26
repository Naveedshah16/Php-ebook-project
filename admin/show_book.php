<?php
include("header.php");
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: all_books.php");
    exit();
}

$bookId = intval($_GET['id']);
$query = "SELECT b.*, c.category_name FROM books b LEFT JOIN categories c ON b.category_id = c.category_id WHERE b.book_id = $bookId";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: all_books.php");
    exit();
}

$book = mysqli_fetch_assoc($result);
$deliveryOptions = explode(',', $book['delivery_options']);
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-book me-2"></i>Book Details</h1>
        <div>
            <a href="all_books.php" class="btn btn-secondary"><i class="fas fa-list me-2"></i>View All Books</a>
            <a href="edit_book.php?id=<?php echo $book['book_id']; ?>" class="btn btn-warning"><i class="fas fa-edit me-2"></i>Edit Book</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-image me-2"></i>Book Cover</h6>
                </div>
                <div class="card-body text-center">
                    <?php if(!empty($book['book_cover'])): ?>
                        <img src="images/<?php echo $book['book_cover']; ?>" alt="<?php echo $book['book_title']; ?>" class="img-fluid rounded">
                    <?php else: ?>
                        <i class="fas fa-book fa-5x text-gray-300"></i>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-truck me-2"></i>Delivery Options</h6>
                </div>
                <div class="card-body">
                    <?php if(in_array('download', $deliveryOptions) && !empty($book['book_pdf'])): ?>
                        <a href="bookPdfFiles/<?php echo $book['book_pdf']; ?>" class="btn btn-success btn-block mb-2" download>
                            <i class="fas fa-download me-2"></i>Download PDF
                        </a>
                    <?php endif; ?>
                    
                    <?php if(in_array('cd', $deliveryOptions)): ?>
                        <button class="btn btn-info btn-block mb-2" onclick="alert('CD order functionality would be implemented here.')">
                            <i class="fas fa-compact-disc me-2"></i>Order CD
                        </button>
                    <?php endif; ?>
                    
                    <?php if(in_array('hardcopy', $deliveryOptions)): ?>
                        <button class="btn btn-warning btn-block" onclick="alert('Hard copy order functionality would be implemented here.')">
                            <i class="fas fa-book me-2"></i>Order Hard Copy
                        </button>
                    <?php endif; ?>
                    
                    <?php if(empty($deliveryOptions[0])): ?>
                        <p class="text-muted">No delivery options available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle me-2"></i>Book Information</h6>
                </div>
                <div class="card-body">
                    <h3 class="font-weight-bold text-primary"><?php echo $book['book_title']; ?></h3>
                    <p class="text-muted h5">by <?php echo $book['book_author']; ?></p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td><?php echo $book['category_name'] ?? 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Price:</strong></td>
                                    <td>$<?php echo number_format($book['book_price'], 2); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Publish Year:</strong></td>
                                    <td><?php echo $book['publish_year'] ?? 'N/A'; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Delivery Options:</h6>
                            <?php if(in_array('download', $deliveryOptions)): ?>
                                <span class="badge badge-success">Digital Download</span>
                            <?php endif; ?>
                            <?php if(in_array('cd', $deliveryOptions)): ?>
                                <span class="badge badge-info">CD Delivery</span>
                            <?php endif; ?>
                            <?php if(in_array('hardcopy', $deliveryOptions)): ?>
                                <span class="badge badge-warning">Hard Copy</span>
                            <?php endif; ?>
                            <?php if(empty($deliveryOptions[0])): ?>
                                <p class="text-muted">No delivery options available</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="font-weight-bold"><i class="fas fa-align-left me-2"></i>Description:</h6>
                        <p><?php echo $book['book_description']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php include("footer.php"); ?>