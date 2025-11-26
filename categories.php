<?php 
require_once __DIR__ . '/session_manager.php';
include "config.php";
$categoryQuery = "SELECT * FROM categories";
$categories = mysqli_query($con, $categoryQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="Description" content="Browse Books by Category - eBook Store"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="style.css">
<title>Categories - eBook Store</title>
</head>
<body>
    <?php include "header.php"; ?>
    <div class="container my-5">
        <div class="row mb-4">
            <div class="col">
                <h1 class="fw-bold"><i class="fas fa-tags text-success me-3"></i>Browse Books by Category</h1>
                <p class="text-muted">Explore our collection organized by genre</p>
            </div>
        </div>
        
        <div class="row">
            <?php while($category = mysqli_fetch_assoc($categories)) { ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="category_books.php?category_id=<?php echo $category['category_id']; ?>" class="text-decoration-none">
                    <div class="card category-card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="category-icon mb-3 mx-auto">
                                <i class="fas fa-book-open fa-2x text-primary"></i>
                            </div>
                            <h3 class="card-title fw-bold"><?php echo $category['category_name']; ?></h3>
                            <p class="card-text text-muted">Explore books in this category</p>
                            <div class="mt-3">
                                <span class="badge bg-primary">View Books</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
    <footer class="bg-dark text-white pt-5 pb-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h4 class="mb-4"><i class="fas fa-book-reader me-2"></i>eBook Store</h4>
                    <p>Discover and download thousands of eBooks across all genres. Read anytime, anywhere with our easy-to-use platform.</p>
                    <div class="social-icons">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="mb-4">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-white text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="about.php" class="text-white text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="categories.php" class="text-white text-decoration-none">Categories</a></li>
                        <li class="mb-2"><a href="all_books.php" class="text-white text-decoration-none">All Books</a></li>
                        <li class="mb-2"><a href="contact.php" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-4">Categories</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Fiction</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Education</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Romance</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Mystery</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Science</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-4">Contact Us</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Book Street, Library City</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +92 (333) 123-4567</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> <a href="mailto:naveed16@gmail.com">naveed16@gmail.com</a></li>
                    </ul>
                </div>
            </div>
            <hr class="mt-0 mb-4 bg-secondary">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2025 eBook Store. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="about.php" class="text-white text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-white text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>