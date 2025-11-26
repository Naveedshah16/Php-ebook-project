
<?php
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
$query = "SELECT * FROM books";
$result = mysqli_query($con, $query);
$categoriesRes = mysqli_query($con, "SELECT category_id, category_name FROM categories ORDER BY category_name ASC");

?>

  <div class="container py-5">
    <h2 class="mb-4">📚 Explore Our Book Collection</h2>
    <div class="row mb-4">
      <div class="col-md-6 mb-3">
        <label class="form-label">Search by title or author</label>
        <input type="text" id="books-search" class="form-control" placeholder="Start typing to filter..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '';?>">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Filter by category</label>
        <select id="category-filter" class="form-select">
          <option value="">All categories</option>
          <?php while($cat = mysqli_fetch_assoc($categoriesRes)) { ?>
            <option value="<?php echo (int)$cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-md-2 mb-3">
        <label class="form-label">&nbsp;</label>
        <button type="button" id="clear-filters" class="btn btn-outline-secondary w-100">Clear</button>
      </div>
    </div>

    <div class="row g-4" id="books-grid">
      <?php while ($book = mysqli_fetch_assoc($result)) { ?>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4 book-item" data-title="<?php echo htmlspecialchars(strtolower($book['book_title'])); ?>" data-author="<?php echo htmlspecialchars(strtolower($book['book_author'])); ?>" data-category="<?php echo (int)$book['category_id']; ?>">
          <div class="book-card h-100 shadow-sm">
            <div class="book-image-container">
              <?php 
                $__coverRel = 'admin/images/' . $book['book_cover']; 
                $__coverAltRel = 'admin/bookCoverImages/' . $book['book_cover']; 
                $__src = file_exists($__coverRel) ? $__coverRel : $__coverAltRel; 
              ?>
              <img src="<?php echo htmlspecialchars($__src); ?>" 
                   alt="<?php echo htmlspecialchars($book['book_title']); ?>" 
                   class="book-image">
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
                  <button class="btn btn-add-to-cart dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                  </button>
                  <ul class="dropdown-menu w-100">
                    <?php
                    $options = explode(',', $book['delivery_options']);
                    foreach ($options as $opt) {
                      $opt_trim = trim($opt);
                      $price = $book['book_price'];
                      switch($opt_trim) {
                        case 'cd': $price = $book['book_price'] * 1.1; break;
                        case 'hardcopy': $price = $book['book_price'] * 1.3; break;
                        default: $price = $book['book_price'];
                      }
                      echo '<li><a class="dropdown-item" href="all_books.php?id=' . $book['book_id'] . '&option=' . urlencode($opt_trim) . '">' . ucfirst($opt_trim) . ' - $' . number_format($price, 2) . '</a></li>';
                    }
                    ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.getElementById('books-search');
      const categorySelect = document.getElementById('category-filter');
      const clearBtn = document.getElementById('clear-filters');
      const items = Array.from(document.querySelectorAll('#books-grid .book-item'));

      function applyFilters() {
          const q = (searchInput?.value || '').trim().toLowerCase();
          const cat = categorySelect?.value || '';
          items.forEach(el => {
              const title = el.getAttribute('data-title') || '';
              const author = el.getAttribute('data-author') || '';
              const itemCat = el.getAttribute('data-category') || '';
              const matchesText = !q || title.includes(q) || author.includes(q);
              const matchesCat = !cat || itemCat === cat;
              el.style.display = (matchesText && matchesCat) ? '' : 'none';
          });
      }

      searchInput?.addEventListener('input', applyFilters);
      categorySelect?.addEventListener('change', applyFilters);
      clearBtn?.addEventListener('click', () => {
          if (searchInput) searchInput.value = '';
          if (categorySelect) categorySelect.value = '';
          applyFilters();
      });
      applyFilters();
  });
  </script>

<?php 
include "footer.php";
