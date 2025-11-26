<?php
include "header.php";
if (isset($_GET['remove'])) {
    $key = $_GET['remove'];
    if (isset($_SESSION['cart'][$key])) {
        unset($_SESSION['cart'][$key]);
    }
    header("Location: cart.php");
    exit();
}

$total=0;
?>
<div class="container py-5">
  <h2 class="mb-4 text-center">🛒 Your Cart</h2>

  <?php if(empty($_SESSION['cart'])): ?>
    <div class="text-center py-5">
      <i class="fas fa-shopping-cart fa-5x text-muted mb-3"></i>
      <p class="mb-3">Your cart is empty.</p>
      <a href="all_books.php" class="btn btn-primary">Browse Books</a>
    </div>
  <?php else: ?>
    <div class="row g-3">
      <?php foreach($_SESSION['cart'] as $key=>$item):
        $cover = file_exists('admin/images/'.$item['cover'])?'admin/images/'.$item['cover']:'admin/bookCoverImages/'.$item['cover'];
        $qty = $item['qty'] ?? 1;
        $total += $item['price']*$qty;
      ?>
        <div class="col-12">
          <div class="card p-3 d-flex flex-row align-items-center shadow-sm">
            <img src="<?php echo $cover;?>" class="me-3" style="width:80px;height:110px;object-fit:cover;">
            <div class="flex-grow-1">
              <h5><?php echo $item['title'];?></h5>
              <p class="mb-1 text-muted"><?php echo $item['author'];?></p>
              <span class="badge bg-info"><?php echo ucfirst($item['delivery_option']);?></span>
              <p class="mb-0 mt-1">Qty: <?php echo $qty;?></p>
            </div>
            <div class="text-end">
              <h5 class="text-primary">$<?php echo number_format($item['price'],2);?></h5>
              <a href="cart.php?remove=<?php echo urlencode($key);?>" class="text-danger fs-5"><i class="fas fa-trash"></i></a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <div class="col-12 text-end mt-3">
        <h4>Total: $<?php echo number_format($total,2);?></h4>
        <a href="checkout.php" class="btn btn-success btn-lg mt-2">Proceed to Checkout</a>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php include "footer.php"; ?>
