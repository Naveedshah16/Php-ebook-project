<?php 
include_once "config.php";
include "header.php";
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
    try {
        $result = executeQuery($con, 
            "SELECT b.*, c.category_name 
             FROM books b 
             LEFT JOIN categories c ON b.category_id = c.category_id 
             WHERE b.book_id = ?",
            [$bookId]
        );
        
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
    } catch (PDOException $e) {
        error_log("Book details error: " . $e->getMessage());
    }
    header("Location: cart.php");
    exit();
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: all_books.php"); 
    exit;
}

$bookId = (int)$_GET['id'];
try {
    $result = executeQuery($con,
        "SELECT b.*, c.category_name 
         FROM books b 
         LEFT JOIN categories c ON b.category_id = c.category_id 
         WHERE b.book_id = ?",
        [$bookId]
    );
    
    if(mysqli_num_rows($result) === 0){
        header("Location: all_books.php"); 
        exit;
    }
    
    $book = mysqli_fetch_assoc($result);
    $isFavorited = false;
    if(isset($_SESSION['userid'])){
        try {
            $favResult = executeQuery($con,
                "SELECT id FROM favorites WHERE user_id = ? AND book_id = ?", 
                [(int)$_SESSION['userid'], $bookId]
            );
            $isFavorited = mysqli_num_rows($favResult) > 0;
        } catch (Exception $e) {
            error_log("Favorites check error: " . $e->getMessage());
            $isFavorited = false;
        }
    }
    
} catch (Exception $e) {
    error_log("Book details error: " . $e->getMessage());
    header("Location: all_books.php"); 
    exit;
}

$deliveryOptions = !empty($book['delivery_options']) ? explode(',', $book['delivery_options']) : ['download'];
if (!is_array($deliveryOptions)) {
    $deliveryOptions = ['download'];
}
$cover = file_exists('admin/images/'.$book['book_cover']) ? 'admin/images/'.$book['book_cover'] : 'admin/bookCoverImages/'.$book['book_cover'];
?>

<div class="container my-5">
  <div class="row g-4">
    <div class="col-md-5 text-center">
      <img src="<?php echo $cover;?>" class="img-fluid rounded shadow" style="max-height:400px;">
    </div>
    <div class="col-md-7">
      <h2 class="fw-bold"><?php echo escapeHTML($book['book_title']);?></h2>
      <p class="text-muted">by <?php echo escapeHTML($book['book_author']);?></p>
      <p><span class="badge bg-secondary"><?php echo escapeHTML($book['category_name'] ?? 'N/A');?></span></p>
      <h4 class="text-primary">$<?php echo number_format((float)$book['book_price'],2);?></h4>
      <p><?php echo nl2br(escapeHTML($book['book_description']));?></p>
      <div class="mb-3">
        <h5>Delivery Options:</h5>
        <?php foreach($deliveryOptions as $opt): ?>
          <span class="badge bg-info me-2 text-dark"><?php echo ucfirst($opt);?></span>
        <?php endforeach; ?>
      </div>
      <div class="mb-3">
        <?php foreach($deliveryOptions as $opt): 
            $price = $book['book_price'];
            if($opt=='cd') $price*=1.1;
            if($opt=='hardcopy') $price*=1.3;
        ?>
          <a href="book_details.php?id=<?php echo $bookId;?>&option=<?php echo urlencode($opt);?>" class="btn btn-warning me-2 mb-2">
            Add to Cart <?php echo ucfirst($opt);?> - $<?php echo number_format($price,2);?>
          </a>
        <?php endforeach; ?>
      </div>
      <?php if(isset($_SESSION['userid'])): ?>
      <button class="btn btn-outline-danger favorite-btn <?php echo $isFavorited ? 'favorited' : ''; ?>" 
              data-book-id="<?php echo $bookId; ?>" 
              title="<?php echo $isFavorited ? 'Remove from favorites' : 'Add to favorites'; ?>">
        <i class="fas fa-heart <?php echo $isFavorited ? 'text-danger' : ''; ?>"></i> 
        <?php echo $isFavorited ? 'Remove from Favorites' : 'Add to Favorites'; ?>
      </button>
      <?php else: ?>
      <a href="login.php?redirect=book_details.php?id=<?php echo $bookId; ?>" class="btn btn-outline-secondary">
        <i class="fas fa-heart"></i> Login to Favorite
      </a>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include "footer.php"; ?>
