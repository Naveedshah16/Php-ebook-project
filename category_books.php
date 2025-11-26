<?php
include "header.php";
if (isset($_GET['id']) && isset($_GET['option'])) {
  if (!isset($_SESSION['userid'])) {
        header("Location: login.php");
        exit();
    }
    $bookId = intval($_GET['id']);
    $deliveryOption = $_GET['option'];
    $categoryId = intval($_GET['category_id']);
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $query = "SELECT b.*, c.category_name FROM books b LEFT JOIN categories c ON b.category_id = c.category_id WHERE b.book_id = $bookId";
    $result = mysqli_query($con, $query);
    
    if ($book = mysqli_fetch_assoc($result)) {
        $cartKey = $bookId . '_' . $deliveryOption;
        $price = $book['book_price'];
        switch($deliveryOption) {
            case 'cd':
                $price = $book['book_price'] * 1.1;
                break;
            case 'hardcopy':
                $price = $book['book_price'] * 1.3;
                break;
            default:
                $price = $book['book_price'];
        }
        if (isset($_SESSION['cart'][$cartKey])) {
            $_SESSION['cart'][$cartKey]['qty'] += 1;
        } else {
            $_SESSION['cart'][$cartKey] = array(
                'book_id' => $bookId,
                'title' => $book['book_title'],
                'author' => $book['book_author'],
                'price' => $price,
                'cover' => $book['book_cover'],
                'category' => $book['category_name'] ?? 'N/A',
                'delivery_option' => $deliveryOption,
                'qty' => 1
            );
        }
    }
    header("Location: category_books.php?category_id=" . $categoryId);
    exit();
}
if (!isset($_GET['category_id'])) {
    header("Location: categories.php");
    exit();
}

$categoryId = intval($_GET['category_id']);
$categoryQuery = "SELECT * FROM categories WHERE category_id = $categoryId";
$categoryResult = mysqli_query($con, $categoryQuery);
$category = mysqli_fetch_assoc($categoryResult);

if (!$category) {
    header("Location: categories.php");
    exit();
}
$booksQuery = "SELECT b.*, c.category_name FROM books b 
               LEFT JOIN categories c ON b.category_id = c.category_id 
               WHERE b.category_id = $categoryId";
$booksResult = mysqli_query($con, $booksQuery);
$books = [];
while ($book = mysqli_fetch_assoc($booksResult)) {
    $book['delivery_options_array'] = explode(',', $book['delivery_options']);
    $books[] = $book;
}
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="fw-bold"><?php echo htmlspecialchars($category['category_name']); ?> Books</h1>
            <p class="text-muted">Browse all books in the <?php echo htmlspecialchars($category['category_name']); ?> category</p>
        </div>
    </div>

    <?php if(count($books) > 0) { ?>
        <div class="row">
            <?php foreach($books as $book) { ?>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                <div class="book-card h-100 shadow-sm">
                    <div class="book-image-container">
                        <?php if(!empty($book['book_cover'])): ?>
                            <?php $c1 = 'admin/images/' . $book['book_cover']; $c2 = 'admin/bookCoverImages/' . $book['book_cover']; ?>
                            <img src="<?php echo file_exists($c1) ? $c1 : $c2; ?>" class="book-image" alt="<?php echo $book['book_title']; ?>">
                        <?php else: ?>
                            <div class="card-img-top book-cover bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-book fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <?php if (strtotime($book['created_at']) > strtotime('-30 days')): ?>
                          <span class="book-badge new">NEW</span>
                        <?php endif; ?>
                    </div>

                    <div class="card-body d-flex flex-column">
                      <h5 class="card-title"><?php echo $book['book_title']; ?></h5>
                      
                      <p class="card-text">by <?php echo $book['book_author']; ?></p>
                      
                      <div class="book-meta">
                        <span class="book-author"><?php echo $book['book_author']; ?></span>
                        <span class="book-category"><?php echo $book['category_name'] ?? 'N/A'; ?></span>
                      </div>
                      
                      <div class="book-price">
                        <span class="current-price">$<?php echo number_format($book['book_price'], 2); ?></span>
                        <?php if ($book['book_price'] < 20): ?>
                          <span class="original-price">$<?php echo number_format($book['book_price'] * 1.2, 2); ?></span>
                        <?php endif; ?>
                      </div>
                      
                      <div class="mt-auto">
                        <div class="book-actions">
                          <a href="book_details.php?id=<?php echo $book['book_id']; ?>" class="btn btn-view-details btn-book-action">
                            <i class="fas fa-eye"></i> View Details
                          </a>
                          <button class="btn btn-outline-danger btn-sm favorite-toggle mb-1" data-book-id="<?php echo $book['book_id']; ?>" title="Toggle Favorite">
                            <i class="fas fa-heart"></i>
                          </button>
                        </div>
                        <div class="dropdown">
                          <button class="btn btn-add-to-cart dropdown-toggle w-100" type="button" id="addToCartDropdown<?php echo $book['book_id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                          </button>
                          <ul class="dropdown-menu w-100" aria-labelledby="addToCartDropdown<?php echo $book['book_id']; ?>">
                            <?php if(in_array('download', $book['delivery_options_array'])): ?>
                              <li><a class="dropdown-item" href="category_books.php?category_id=<?php echo $categoryId; ?>&id=<?php echo $book['book_id']; ?>&option=download">Digital Download - $<?php echo number_format($book['book_price'], 2); ?></a></li>
                            <?php endif; ?>
                            <?php if(in_array('cd', $book['delivery_options_array'])): ?>
                              <li><a class="dropdown-item" href="category_books.php?category_id=<?php echo $categoryId; ?>&id=<?php echo $book['book_id']; ?>&option=cd">CD Delivery - $<?php echo number_format($book['book_price'] * 1.1, 2); ?></a></li>
                            <?php endif; ?>
                            <?php if(in_array('hardcopy', $book['delivery_options_array'])): ?>
                              <li><a class="dropdown-item" href="category_books.php?category_id=<?php echo $categoryId; ?>&id=<?php echo $book['book_id']; ?>&option=hardcopy">Hard Copy - $<?php echo number_format($book['book_price'] * 1.3, 2); ?></a></li>
                            <?php endif; ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="row">
            <div class="col text-center py-5">
                <i class="fas fa-book fa-5x text-muted mb-4"></i>
                <h3 class="mb-3">No Books Found</h3>
                <p class="text-muted">There are currently no books in the <?php echo $category['category_name']; ?> category.</p>
                <a href="categories.php" class="btn btn-primary">Browse Other Categories</a>
            </div>
        </div>
    <?php } ?>
</div>

<?php include "footer.php"; ?>