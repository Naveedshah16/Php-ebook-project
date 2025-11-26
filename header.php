<?php 
include_once "config.php";
require_once __DIR__ . '/session_manager.php';
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
try {
    $settingsResult = executeQuery($con, "SELECT * FROM settings LIMIT 1");
    $siteSettings = mysqli_fetch_assoc($settingsResult) ?: [];
} catch (Exception $e) {
    error_log("Settings error: " . $e->getMessage());
    $siteSettings = [];
}
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="style.css">

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <?php if(!empty($siteSettings['logo_path'])): ?>
                <img src="<?= 'admin/' . ltrim(escapeHTML($siteSettings['logo_path']), '/'); ?>" 
                     alt="Logo" height="32" class="me-2">
            <?php else: ?>
                <i class="fas fa-book-reader me-2"></i>
            <?php endif; ?>

            <?= escapeHTML($siteSettings['site_name'] ?? 'eBook Store'); ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" href="index.php"><i class="fas fa-home me-1"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage == 'categories.php' ? 'active' : '' ?>" href="categories.php"><i class="fas fa-list me-1"></i>Categories</a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage == 'all_books.php' ? 'active' : '' ?>" href="all_books.php"><i class="fas fa-book me-1"></i>All Books</a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage == 'contact.php' ? 'active' : '' ?>" href="contact.php"><i class="fas fa-envelope me-1"></i>Contact</a></li>
            </ul>
            <ul class="navbar-nav me-3">
                <li class="nav-item">
                    <a class="nav-link" href="favorites.php">
                        <i class="fas fa-heart me-1"></i>Favorites 
                        <span id="favorites-count" class="badge bg-danger">0</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                        <i class="fas fa-shopping-cart me-1"></i>Cart
                        <span id="cart-count" class="badge bg-warning text-dark"><?= $cartCount ?></span>
                    </a>
                </li>
            </ul>

            <form class="d-flex me-3" action="all_books.php" method="get">
                <input class="form-control me-2" type="search" placeholder="Search books..." name="q"
                    value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i></button>
            </form>

            <ul class="navbar-nav">
                <?php if(isset($_SESSION['userid'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user me-1"></i><?= htmlspecialchars($_SESSION["username"] ?? 'My Account'); ?></a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php"><i class="fas fa-user-plus me-1"></i>Register</a></li>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</nav>
