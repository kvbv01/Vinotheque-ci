// assets/js/script.js

document.addEventListener('DOMContentLoaded', function() {
    // Gestion du panier
    const cart = {
        items: JSON.parse(localStorage.getItem('cart')) || [],
        
        addItem: function(productId, quantity = 1) {
            const existingItem = this.items.find(item => item.product_id == productId);
            
            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                this.items.push({ product_id: productId, quantity });
            }
            
            this.save();
            this.updateCartCount();
        },
        
        removeItem: function(productId) {
            this.items = this.items.filter(item => item.product_id != productId);
            this.save();
            this.updateCartCount();
        },
        
        updateQuantity: function(productId, quantity) {
            const item = this.items.find(item => item.product_id == productId);
            if (item) {
                item.quantity = quantity;
                this.save();
            }
        },
        
        clear: function() {
            this.items = [];
            this.save();
            this.updateCartCount();
        },
        
        save: function() {
            localStorage.setItem('cart', JSON.stringify(this.items));
        },
        
        updateCartCount: function() {
            const count = this.items.reduce((total, item) => total + item.quantity, 0);
            document.querySelectorAll('.cart-count').forEach(el => {
                el.textContent = count;
                el.style.display = count > 0 ? 'inline-block' : 'none';
            });
        }
    };
    
    // Initialiser le compteur du panier
    cart.updateCartCount();
    
    // Gestion de l'ajout au panier
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            cart.addItem(productId);
            
            // Afficher une notification
            showToast('Produit ajouté au panier');
        });
    });
    
    // Gestion de la suppression du panier
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-from-cart')) {
            const productId = e.target.dataset.productId;
            cart.removeItem(productId);
            
            // Recharger la page si on est sur la page du panier
            if (window.location.pathname.includes('cart')) {
                window.location.reload();
            }
        }
    });
    
    // Gestion de la modification de quantité
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('cart-quantity')) {
            const productId = e.target.dataset.productId;
            const quantity = parseInt(e.target.value);
            
            if (quantity > 0) {
                cart.updateQuantity(productId, quantity);
                
                // Recalculer le total si on est sur la page du panier
                if (window.location.pathname.includes('cart')) {
                    updateCartTotals();
                }
            } else {
                cart.removeItem(productId);
                if (window.location.pathname.includes('cart')) {
                    window.location.reload();
                }
            }
        }
    });
    
    // Fonction pour afficher les toasts
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast show';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Fonction pour mettre à jour les totaux du panier
    function updateCartTotals() {
        fetch('/api/cart/totals', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ items: cart.items })
        })
        .then(response => response.json())
        .then(data => {
            document.querySelector('.cart-subtotal').textContent = data.subtotal.toFixed(2) + ' €';
            document.querySelector('.cart-total').textContent = data.total.toFixed(2) + ' €';
        })
        .catch(error => console.error('Error:', error));
    }
    
    // Gestion de la recherche
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchTerm = this.querySelector('input').value.trim();
            if (searchTerm) {
                window.location.href = '/products/search?q=' + encodeURIComponent(searchTerm);
            }
        });
    }
    
    // Gestion des favoris
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const isFavorite = this.classList.contains('active');
            
            fetch('/api/favorites', {
                method: isFavorite ? 'DELETE' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.classList.toggle('active');
                    this.querySelector('i').className = isFavorite ? 'far fa-heart' : 'fas fa-heart';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
    
    // Initialisation des tooltips
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});

// Fonction pour charger plus de produits (scroll infini)
function loadMoreProducts() {
    const loading = document.querySelector('.loading-more');
    if (loading) {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                const nextPage = parseInt(document.querySelector('.products-container').dataset.page) + 1;
                const totalPages = parseInt(document.querySelector('.products-container').dataset.totalPages);
                
                if (nextPage <= totalPages) {
                    fetch(`/api/products?page=${nextPage}`)
                        .then(response => response.json())
                        .then(data => {
                            const container = document.querySelector('.products-container');
                            container.insertAdjacentHTML('beforeend', data.html);
                            container.dataset.page = nextPage;
                            
                            if (nextPage >= totalPages) {
                                loading.style.display = 'none';
                            }
                        });
                }
            }
        });
        
        observer.observe(loading);
    }
}

// Initialiser le chargement infini
document.addEventListener('DOMContentLoaded', loadMoreProducts);