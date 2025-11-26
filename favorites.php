<?php include "header.php"; ?>

<div class="container py-5">
	<h2 class="mb-4">❤️ My Favorites</h2>
    <div id="favorites-container">
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading your favorites...</p>
        </div>
    </div>
</div>
<script>
function loadFavorites() {
    const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    const container = document.getElementById('favorites-container');
    if (favorites.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="fas fa-heart-broken fa-3x mb-3"></i>
                <p>You have no favorites yet.</p>
                <a href="all_books.php" class="btn btn-primary">Browse Books</a>
            </div>
        `;
        return;
    }
    container.innerHTML = `
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> You have <strong>${favorites.length}</strong> book(s) in your favorites.
        </div>
        <div class="text-center py-3">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading books...</span>
            </div>
            <p class="mt-2">Loading book details...</p>
        </div>
    `;
    fetch('favorites_details.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({book_ids: favorites})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayFavorites(data.books);
        } else {
            container.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> You have <strong>${favorites.length}</strong> book(s) in your favorites.
                </div>
                <div class="row g-3">
                    ${favorites.map(bookId => `
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Book ID: ${bookId}</h5>
                                    <button class="btn btn-danger btn-sm" onclick="removeFavorite(${bookId})">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
                <div class="mt-3">
                    <a href="all_books.php" class="btn btn-secondary">Continue Browsing</a>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        container.innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> You have <strong>${favorites.length}</strong> book(s) in your favorites.
            </div>
            <div class="row g-3">
                ${favorites.map(bookId => `
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5>Book ID: ${bookId}</h5>
                                <button class="btn btn-danger btn-sm" onclick="removeFavorite(${bookId})">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
            <div class="mt-3">
                <a href="all_books.php" class="btn btn-secondary">Continue Browsing</a>
            </div>
        `;
    });
}
function displayFavorites(books) {
    const container = document.getElementById('favorites-container');
    
    if (books.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="fas fa-heart-broken fa-3x mb-3"></i>
                <p>You have no favorites yet.</p>
                <a href="all_books.php" class="btn btn-primary">Browse Books</a>
            </div>
        `;
        return;
    }
    
    container.innerHTML = `
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> You have <strong>${books.length}</strong> book(s) in your favorites.
        </div>
        <div class="row g-4">
            ${books.map(book => {
                const coverRel = 'admin/images/' + (book.book_cover || '');
                const coverAltRel = 'admin/bookCoverImages/' + (book.book_cover || '');
                
                return `
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="book-card h-100 shadow-sm">
                        <div class="book-image-container">
                            ${book.book_cover ? 
                                `<img src="${coverRel}" class="book-image" alt="${book.book_title || 'Book Cover'}" onerror="this.onerror=null;this.src='${coverAltRel}';">` :
                                `<div class="card-img-top book-cover bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-book fa-3x text-muted"></i>
                                </div>`
                            }
                            ${book.created_at && new Date(book.created_at) > new Date(Date.now() - 30 * 24 * 60 * 60 * 1000) ? 
                              '<span class="book-badge new">NEW</span>' : ''}
                        </div>
                        <div class="card-body d-flex flex-column">
                          <h5 class="card-title">${book.book_title || 'Unknown Title'}</h5>
                          
                          <p class="card-text">by ${book.book_author || 'Unknown Author'}</p>
                          
                          <div class="book-meta">
                            <span class="book-author">${book.book_author || 'Unknown Author'}</span>
                            <span class="book-category">${book.category_name || 'N/A'}</span>
                          </div>
                          
                          <div class="book-price">
                            <span class="current-price">$${parseFloat(book.book_price || 0).toFixed(2)}</span>
                            ${book.book_price < 20 ? 
                              `<span class="original-price">$${(book.book_price * 1.2).toFixed(2)}</span>` : ''}
                          </div>
                          
                          <div class="mt-auto">
                            <div class="book-actions">
                              <a href="book_details.php?id=${book.book_id}" class="btn btn-view-details btn-book-action">
                                <i class="fas fa-eye"></i> View Details
                              </a>
                              <button class="btn btn-outline-danger btn-sm favorite-remove mb-1" data-book-id="${book.book_id}" title="Remove from Favorites" onclick="removeFavorite(${book.book_id})">
                                <i class="fas fa-trash"></i>
                              </button>
                            </div>
                            
                            <!-- Cart Dropdown -->
                            <div class="dropdown mt-2">
                              <button class="btn btn-add-to-cart dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                              </button>
                              <ul class="dropdown-menu w-100">
                                ${(book.delivery_options || '').split(',').map(opt => {
                                  const optTrim = opt.trim();
                                  let price = parseFloat(book.book_price || 0);
                                  switch(optTrim) {
                                    case 'cd': price = price * 1.1; break;
                                    case 'hardcopy': price = price * 1.3; break;
                                    default: price = price;
                                  }
                                  return `<li><a class="dropdown-item" href="favorites.php?id=${book.book_id}&option=${encodeURIComponent(optTrim)}">${optTrim.charAt(0).toUpperCase() + optTrim.slice(1)} - $${price.toFixed(2)}</a></li>`;
                                }).join('')}
                              </ul>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                `;
            }).join('')}
        </div>
        <div class="mt-3">
            <a href="all_books.php" class="btn btn-secondary">Continue Browsing</a>
        </div>
    `;
    attachFavoriteToggleHandlers();
}
function removeFavorite(bookId) {
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    favorites = favorites.filter(id => id !== bookId);
    localStorage.setItem('favorites', JSON.stringify(favorites));
    updateFavoritesCount();
    loadFavorites();
}
document.addEventListener('DOMContentLoaded', function() {
    loadFavorites();
});
</script>

<?php include "footer.php"; ?>