<?php 
require_once __DIR__ . '/session_manager.php';
include "config.php";
if (isset($_GET['id']) && isset($_GET['option'])) {
    if (!isset($_SESSION['userid'])) {
        header("Location: login.php");
        exit();
    }
    $bookId = intval($_GET['id']);
    $deliveryOption = $_GET['option'];

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
    header("Location: cart.php");
    exit();
}
$featuredBooksQuery = "SELECT b.*, c.category_name FROM books b LEFT JOIN categories c ON b.category_id = c.category_id ORDER BY b.created_at DESC LIMIT 6";
$featuredBooksResult = mysqli_query($con, $featuredBooksQuery);
$featuredBooks = [];
while ($book = mysqli_fetch_assoc($featuredBooksResult)) {
    $book['delivery_options_array'] = explode(',', $book['delivery_options']);
    $featuredBooks[] = $book;
}
$categoryQuery = "SELECT * FROM categories";
$categories = mysqli_query($con, $categoryQuery);
$latestBooks = [];
$latestBooksQuery = "SELECT b.*, c.category_name FROM books b LEFT JOIN categories c ON b.category_id = c.category_id ORDER BY b.created_at DESC, b.book_id DESC LIMIT 8";
$latestBooksResult = mysqli_query($con, $latestBooksQuery);
if ($latestBooksResult) {
    while ($lb = mysqli_fetch_assoc($latestBooksResult)) {
        $lb['delivery_options_array'] = explode(',', $lb['delivery_options']);
        $latestBooks[] = $lb;
    }
}

$siteSettings = null;
$settingsResult = mysqli_query($con, "SELECT * FROM settings LIMIT 1");
if ($settingsResult && mysqli_num_rows($settingsResult) > 0) {
    $siteSettings = mysqli_fetch_assoc($settingsResult);
}
$bannerUrl = '';
if (!empty($siteSettings['banner_path'])) {
    $bannerUrl = 'admin/' . ltrim($siteSettings['banner_path'], '/');
}

$squareImageUrl = '';
if (!empty($siteSettings['square_image_path'])) {
    $squareImageUrl = 'admin/' . ltrim($siteSettings['square_image_path'], '/');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="Description" content="E-Book Store - Browse and download your favorite eBooks"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="style.css">
<title>Home - eBook Store</title>
</head>
<body>
    <?php include "header.php"; ?>
    <div class="hero-section" <?php if(!empty($bannerUrl)) { echo "style=\"background-image: url('" . $bannerUrl . "');\""; } ?>>
        <div class="hero-glass-overlay"></div>
        <div class="container py-5 position-relative">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="hero-title">
                            <span class="hero-title-line-1">Discover Your Next</span>
                            <span class="hero-title-line-2 text-warning">Favorite Book</span>
                        </h1>
                        <p class="hero-subtitle">Browse thousands of eBooks across all genres. Read anytime, anywhere.</p>
                        <div class="hero-buttons mt-4">
                            <a href="all_books.php" class="btn btn-light btn-lg me-3 hero-btn">
                                <i class="fas fa-book me-2"></i>Browse Books
                            </a>
                            <a href="favorites.php" class="btn btn-outline-light btn-lg hero-btn">
                                <i class="fas fa-heart me-2"></i>My Favorites
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center d-none d-lg-block">
                    <div class="hero-image-wrapper">
                        <?php if(!empty($squareImageUrl)): ?>
                            <img src="<?php echo $squareImageUrl; ?>" alt="Site Square Image" class="hero-image square-image-hover" style="width: 400px; height: 400px; object-fit: cover; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); transition: all 0.3s ease;">
                        <?php else: ?>
                            <img src="https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=1000&auto=format&fit=crop" alt="Reading Illustration" class="hero-image square-image-hover" style="width: 400px; height: 400px; object-fit: cover; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); transition: all 0.3s ease;">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .square-image-hover:hover {
            transform: scale(1.05);
            box-shadow: 0 0 25px rgba(255, 255, 0, 0.3) !important;
        }
    </style>
    <div class="container my-5">
<section class="mb-5">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fas fa-star text-warning me-2"></i>Featured Books</h2>
    <a href="all_books.php" class="btn btn-outline-primary">View All</a>
</div>
<div class="row">
    <?php foreach ($featuredBooks as $book) { ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div class="book-card h-100 shadow-sm">
            <div class="book-image-container">
            <?php if(!empty($book['book_cover'])): ?>
                    <?php $__coverRel = 'admin/images/' . $book['book_cover']; $__coverAltRel = 'admin/bookCoverImages/' . $book['book_cover']; ?>
                    <img src="<?php echo file_exists($__coverRel) ? $__coverRel : $__coverAltRel; ?>" class="book-image" alt="<?php echo $book['book_title']; ?>">
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
              <h5 class="card-title"><?php echo htmlspecialchars($book['book_title']); ?></h5>
              
              <p class="card-text">by <?php echo htmlspecialchars($book['book_author']); ?></p>
              
              <div class="book-meta">
                <span class="book-author"><?php echo htmlspecialchars($book['book_author']); ?></span>
                <span class="book-category"><?php echo htmlspecialchars($book['category_name'] ?? 'N/A'); ?></span>
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
                  <button class="btn btn-outline-danger btn-sm favorite-btn mb-1" data-book-id="<?php echo $book['book_id']; ?>" title="Toggle Favorite">
                    <i class="fas fa-heart"></i>
                  </button>
                </div>
                
                <div class="dropdown mt-2">
                  <button class="btn btn-add-to-cart dropdown-toggle w-100" type="button" id="addToCartDropdown<?php echo $book['book_id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                  </button>
                  <ul class="dropdown-menu w-100" aria-labelledby="addToCartDropdown<?php echo $book['book_id']; ?>">
                    <?php if(in_array('download', $book['delivery_options_array'])): ?>
                      <li><a class="dropdown-item" href="index.php?id=<?php echo $book['book_id']; ?>&option=download">Digital Download - $<?php echo number_format($book['book_price'], 2); ?></a></li>
                    <?php endif; ?>
                    <?php if(in_array('cd', $book['delivery_options_array'])): ?>
                      <li><a class="dropdown-item" href="index.php?id=<?php echo $book['book_id']; ?>&option=cd">CD Delivery - $<?php echo number_format($book['book_price'] * 1.1, 2); ?></a></li>
                    <?php endif; ?>
                    <?php if(in_array('hardcopy', $book['delivery_options_array'])): ?>
                      <li><a class="dropdown-item" href="index.php?id=<?php echo $book['book_id']; ?>&option=hardcopy">Hard Copy - $<?php echo number_format($book['book_price'] * 1.3, 2); ?></a></li>
                    <?php endif; ?>
                  </ul>
                </div>
              </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
</section>
<section class="mb-5">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fas fa-tags text-success me-2"></i>Browse Categories</h2>
    <a href="categories.php" class="btn btn-outline-success">View All</a>
</div>
<div class="row">
    <?php while($category = mysqli_fetch_assoc($categories)) { ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
        <a href="category_books.php?category_id=<?php echo $category['category_id']; ?>" class="text-decoration-none">
            <div class="card category-card text-center p-4 h-100">
                <div class="category-icon mb-3">
                    <i class="fas fa-book-open fa-2x text-primary"></i>
                </div>
                <h5 class="fw-bold mb-0"><?php echo $category['category_name']; ?></h5>
            </div>
        </a>
    </div>
    <?php } ?>
</div>
</section>
<section>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fas fa-clock text-info me-2"></i>Latest Books</h2>
    <a href="all_books.php" class="btn btn-outline-info">View All</a>
</div>
<div class="row">
    <?php if (!empty($latestBooks)) { foreach ($latestBooks as $book) { ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div class="book-card h-100 shadow-sm">
            <div class="book-image-container">
                <?php if(!empty($book['book_cover'])): ?>
                    <?php $__coverRel = 'admin/images/' . $book['book_cover']; $__coverAltRel = 'admin/bookCoverImages/' . $book['book_cover']; ?>
                    <img src="<?php echo file_exists($__coverRel) ? $__coverRel : $__coverAltRel; ?>" class="book-image" alt="<?php echo htmlspecialchars($book['book_title']); ?>">
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
              <h5 class="card-title"><?php echo htmlspecialchars($book['book_title']); ?></h5>
              
              <p class="card-text">by <?php echo htmlspecialchars($book['book_author']); ?></p>
              
              <div class="book-meta">
                <span class="book-author"><?php echo htmlspecialchars($book['book_author']); ?></span>
                <span class="book-category"><?php echo htmlspecialchars($book['category_name'] ?? 'N/A'); ?></span>
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
                    <i class="fas fa-eye"></i> View
                  </a>
                  <button class="btn btn-outline-danger btn-sm favorite-btn mb-1" data-book-id="<?php echo $book['book_id']; ?>" title="Toggle Favorite">
                    <i class="fas fa-heart"></i>
                  </button>
                </div>
                
                <div class="dropdown mt-2">
                  <button class="btn btn-add-to-cart dropdown-toggle w-100" type="button" id="latestAddToCart<?php echo $book['book_id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                  </button>
                  <ul class="dropdown-menu w-100" aria-labelledby="latestAddToCart<?php echo $book['book_id']; ?>">
                    <?php if(in_array('download', $book['delivery_options_array'])): ?>
                      <li><a class="dropdown-item" href="index.php?id=<?php echo $book['book_id']; ?>&option=download">Digital Download - $<?php echo number_format($book['book_price'], 2); ?></a></li>
                    <?php endif; ?>
                    <?php if(in_array('cd', $book['delivery_options_array'])): ?>
                      <li><a class="dropdown-item" href="index.php?id=<?php echo $book['book_id']; ?>&option=cd">CD Delivery - $<?php echo number_format($book['book_price'] * 1.1, 2); ?></a></li>
                    <?php endif; ?>
                    <?php if(in_array('hardcopy', $book['delivery_options_array'])): ?>
                      <li><a class="dropdown-item" href="index.php?id=<?php echo $book['book_id']; ?>&option=hardcopy">Hard Copy - $<?php echo number_format($book['book_price'] * 1.3, 2); ?></a></li>
                    <?php endif; ?>
                  </ul>
                </div>
              </div>
            </div>
        </div>
    </div>
    <?php } } else { ?>
        <div class="col-12">
            <div class="text-center text-muted py-5">No books found.</div>
        </div>
    <?php } ?>
</div>
</section>
    </div>

<?php 
include "footer.php";
?>