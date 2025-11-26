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
                        <li class="mb-2"><a href="contact.php" class="text-white text-decoration-none">Contact</a></li>
                        <li class="mb-2"><a href="categories.php" class="text-white text-decoration-none">Categories</a></li>
                        <li class="mb-2"><a href="all_books.php" class="text-white text-decoration-none">All Books</a></li>
                        <li class="mb-2"><a href="about.php" class="text-white text-decoration-none">About Us</a></li>
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
                    <h5 class="mb-4">About Us</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Book Street, Library City</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +92 (333) 123-4567</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i><a href="mailto:naveed16@gmail.com">naveed16@gmail.com</a></li>
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
    <script>
    function updateFavoritesCount() {
        const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        const favCountElement = document.getElementById('favorites-count');
        if (favCountElement) {
            favCountElement.textContent = favorites.length;
        }
    }
    function attachFavoriteToggleHandlers() {
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.favorite-btn, .favorite-toggle');
            if (!button) return;
            e.preventDefault();
            
            const bookId = parseInt(button.getAttribute('data-book-id'));
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            
            const index = favorites.indexOf(bookId);
            if (index === -1) {
                favorites.push(bookId);
            } else {
                favorites.splice(index, 1);
            }
            
            localStorage.setItem('favorites', JSON.stringify(favorites));
            updateFavoritesCount();
            document.querySelectorAll(`.favorite-btn[data-book-id="${bookId}"]`).forEach(btn => {
                const icon = btn.querySelector('i');
                if (favorites.includes(bookId)) {
                    icon.classList.add('text-danger');
                    btn.classList.add('favorited');
                } else {
                    icon.classList.remove('text-danger');
                    btn.classList.remove('favorited');
                }
            });
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        updateFavoritesCount();
        attachFavoriteToggleHandlers();
    });
</script>
</body>
</html>