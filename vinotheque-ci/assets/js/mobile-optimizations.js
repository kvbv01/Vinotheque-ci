/**
 * VINOTHEQUE CI - Optimisations Mobile pour l'Afrique
 * Fonctionnalités adaptées aux contraintes des marchés africains
 */

class MobileOptimizer {
    constructor() {
        this.networkStatus = 'online';
        this.connectionSpeed = 'fast';
        this.isLowDataMode = false;
        this.init();
    }

    init() {
        this.detectConnection();
        this.setupNetworkMonitoring();
        this.optimizeImages();
        this.setupOfflineMode();
        this.initTouchOptimizations();
        this.setupProgressiveLoading();
        this.initLocationServices();
    }

    /**
     * Détection et monitoring de la connexion réseau
     */
    detectConnection() {
        if ('connection' in navigator) {
            const connection = navigator.connection;
            this.updateConnectionStatus(connection);
            
            connection.addEventListener('change', () => {
                this.updateConnectionStatus(connection);
            });
        }

        // Fallback pour les navigateurs sans API Connection
        window.addEventListener('online', () => {
            this.networkStatus = 'online';
            this.showNetworkStatus('Connexion rétablie', 'success');
        });

        window.addEventListener('offline', () => {
            this.networkStatus = 'offline';
            this.showNetworkStatus('Hors ligne', 'danger');
        });
    }

    updateConnectionStatus(connection) {
        const slowConnections = ['slow-2g', '2g', '3g'];
        const effectiveType = connection.effectiveType;
        
        this.connectionSpeed = slowConnections.includes(effectiveType) ? 'slow' : 'fast';
        
        if (this.connectionSpeed === 'slow') {
            this.enableDataSavingMode();
            this.showNetworkStatus(`Connexion lente (${effectiveType})`, 'warning');
        } else {
            this.disableDataSavingMode();
            this.hideNetworkStatus();
        }
    }

    showNetworkStatus(message, type) {
        let indicator = document.querySelector('.network-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.className = 'network-indicator';
            document.body.appendChild(indicator);
        }
        
        indicator.textContent = message;
        indicator.className = `network-indicator network-${type}`;
        indicator.style.display = 'block';
    }

    hideNetworkStatus() {
        const indicator = document.querySelector('.network-indicator');
        if (indicator) {
            indicator.style.display = 'none';
        }
    }

    /**
     * Mode économie de données
     */
    enableDataSavingMode() {
        this.isLowDataMode = true;
        document.body.classList.add('low-data-mode');
        
        // Réduire la qualité des images
        const images = document.querySelectorAll('img[data-src-low]');
        images.forEach(img => {
            if (img.dataset.srcLow) {
                img.src = img.dataset.srcLow;
            }
        });
        
        // Désactiver les animations
        document.body.classList.add('reduce-motion');
        
        console.log('Mode économie de données activé');
    }

    disableDataSavingMode() {
        this.isLowDataMode = false;
        document.body.classList.remove('low-data-mode');
        document.body.classList.remove('reduce-motion');
    }

    /**
     * Optimisation des images
     */
    optimizeImages() {
        // Lazy loading pour les images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const src = this.isLowDataMode ? img.dataset.srcLow : img.dataset.src;
                        
                        if (src) {
                            img.src = src;
                            img.classList.remove('lazy');
                            observer.unobserve(img);
                        }
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                img.classList.add('lazy');
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Mode hors ligne avec Service Worker
     */
    setupOfflineMode() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(registration => {
                    console.log('Service Worker enregistré:', registration);
                })
                .catch(error => {
                    console.log('Erreur Service Worker:', error);
                });
        }

        // Cache des données critiques
        this.cacheEssentialData();
    }

    cacheEssentialData() {
        const essentialUrls = [
            '/api/products/popular',
            '/api/user/profile',
            '/api/cart'
        ];

        essentialUrls.forEach(url => {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    localStorage.setItem(`cache_${url}`, JSON.stringify({
                        data: data,
                        timestamp: Date.now()
                    }));
                })
                .catch(error => console.log('Erreur cache:', error));
        });
    }

    /**
     * Optimisations tactiles
     */
    initTouchOptimizations() {
        // Améliorer la réactivité des boutons
        document.addEventListener('touchstart', function() {}, {passive: true});

        // Gérer les gestes de balayage
        let startX, startY, distX, distY;
        
        document.addEventListener('touchstart', e => {
            const touch = e.touches[0];
            startX = touch.clientX;
            startY = touch.clientY;
        }, {passive: true});

        document.addEventListener('touchmove', e => {
            if (!startX || !startY) return;
            
            const touch = e.touches[0];
            distX = touch.clientX - startX;
            distY = touch.clientY - startY;
        }, {passive: true});

        document.addEventListener('touchend', e => {
            if (!distX || !distY) return;
            
            // Détecter le balayage horizontal
            if (Math.abs(distX) > Math.abs(distY)) {
                if (distX > 50) {
                    this.handleSwipeRight();
                } else if (distX < -50) {
                    this.handleSwipeLeft();
                }
            }
            
            startX = startY = distX = distY = 0;
        }, {passive: true});
    }

    handleSwipeRight() {
        // Navigation vers la page précédente ou ouverture du menu
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.classList.add('show');
        }
    }

    handleSwipeLeft() {
        // Fermeture du menu ou navigation suivante
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.classList.remove('show');
        }
    }

    /**
     * Chargement progressif du contenu
     */
    setupProgressiveLoading() {
        // Charger le contenu par priorité
        this.loadCriticalContent();
        
        // Charger le contenu secondaire après le chargement initial
        window.addEventListener('load', () => {
            setTimeout(() => {
                this.loadSecondaryContent();
            }, 1000);
        });
    }

    loadCriticalContent() {
        // Charger les produits populaires en premier
        fetch('/api/products/popular')
            .then(response => response.json())
            .then(products => {
                this.renderProducts(products, 'critical');
            })
            .catch(error => {
                console.log('Erreur chargement critique:', error);
                // Utiliser le cache si disponible
                const cached = localStorage.getItem('cache_/api/products/popular');
                if (cached) {
                    const data = JSON.parse(cached);
                    this.renderProducts(data.data, 'cached');
                }
            });
    }

    loadSecondaryContent() {
        if (this.isLowDataMode) return; // Éviter le chargement en mode économie
        
        // Charger le reste du catalogue
        fetch('/api/products/all')
            .then(response => response.json())
            .then(products => {
                this.renderProducts(products, 'secondary');
            })
            .catch(error => console.log('Erreur chargement secondaire:', error));
    }

    renderProducts(products, priority) {
        const container = document.querySelector('.product-grid');
        if (!container) return;

        products.forEach(product => {
            const productCard = this.createProductCard(product, priority);
            container.appendChild(productCard);
        });
    }

    createProductCard(product, priority) {
        const card = document.createElement('div');
        card.className = 'product-card fade-in';
        card.dataset.priority = priority;
        
        const imageUrl = this.isLowDataMode ? product.image_small : product.image;
        
        card.innerHTML = `
            <img class="product-image lazy" 
                 data-src="${imageUrl}" 
                 data-src-low="${product.image_small || product.image}"
                 alt="${product.name}">
            <div class="product-info">
                <h3 class="product-title">${product.name}</h3>
                <p class="product-price">${product.price} ${product.currency}</p>
                <button class="btn btn-primary w-100" onclick="addToCart(${product.id})">
                    <i class="fas fa-cart-plus"></i> Ajouter
                </button>
            </div>
        `;
        
        return card;
    }

    /**
     * Services de géolocalisation
     */
    initLocationServices() {
        if ('geolocation' in navigator) {
            this.getCurrentLocation();
        }
    }

    getCurrentLocation() {
        const options = {
            enableHighAccuracy: false, // Économie de batterie
            timeout: 10000,
            maximumAge: 300000 // 5 minutes de cache
        };

        navigator.geolocation.getCurrentPosition(
            position => {
                const { latitude, longitude } = position.coords;
                this.updateLocationBasedContent(latitude, longitude);
            },
            error => {
                console.log('Erreur géolocalisation:', error);
                // Utiliser la localisation par défaut ou IP
                this.useDefaultLocation();
            },
            options
        );
    }

    updateLocationBasedContent(lat, lng) {
        // Mettre à jour les zones de livraison disponibles
        fetch(`/api/delivery/zones?lat=${lat}&lng=${lng}`)
            .then(response => response.json())
            .then(zones => {
                this.updateDeliveryZones(zones);
            })
            .catch(error => console.log('Erreur zones livraison:', error));
    }

    useDefaultLocation() {
        // Utiliser Abidjan comme localisation par défaut
        this.updateLocationBasedContent(5.3600, -4.0083);
    }

    updateDeliveryZones(zones) {
        const deliveryInfo = document.querySelector('.delivery-zones');
        if (deliveryInfo && zones.length > 0) {
            deliveryInfo.innerHTML = `
                <h5><i class="fas fa-truck"></i> Livraison disponible</h5>
                <p>Nous livrons dans votre zone : ${zones[0].name}</p>
                <small>Délai estimé : ${zones[0].delivery_time}</small>
            `;
            deliveryInfo.classList.add('available');
        }
    }

    /**
     * Gestion des notifications push
     */
    setupPushNotifications() {
        if ('Notification' in window && 'serviceWorker' in navigator) {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    this.subscribeToPushNotifications();
                }
            });
        }
    }

    subscribeToPushNotifications() {
        navigator.serviceWorker.ready.then(registration => {
            return registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array(VAPID_PUBLIC_KEY)
            });
        }).then(subscription => {
            // Envoyer l'abonnement au serveur
            fetch('/api/push/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(subscription)
            });
        });
    }

    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    /**
     * Utilitaires pour l'expérience utilisateur
     */
    showLoading(element) {
        element.innerHTML = '<div class="loading"></div> Chargement...';
        element.disabled = true;
    }

    hideLoading(element, originalText) {
        element.innerHTML = originalText;
        element.disabled = false;
    }

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
}

// Fonctions globales pour l'interaction avec les produits
function addToCart(productId) {
    const button = event.target;
    const optimizer = window.mobileOptimizer;
    
    optimizer.showLoading(button);
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=1`
    })
    .then(response => {
        if (response.ok) {
            optimizer.showToast('Produit ajouté au panier', 'success');
            updateCartCount();
        } else {
            optimizer.showToast('Erreur lors de l\'ajout', 'error');
        }
    })
    .catch(error => {
        optimizer.showToast('Erreur de connexion', 'error');
    })
    .finally(() => {
        optimizer.hideLoading(button, '<i class="fas fa-cart-plus"></i> Ajouter');
    });
}

function updateCartCount() {
    fetch('/api/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.count;
                cartCount.style.display = data.count > 0 ? 'flex' : 'none';
            }
        })
        .catch(error => console.log('Erreur compteur panier:', error));
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    window.mobileOptimizer = new MobileOptimizer();
    
    // Mettre à jour le compteur du panier
    updateCartCount();
    
    // Ajouter le panier flottant si pas présent
    if (!document.querySelector('.cart-floating')) {
        const cartFloat = document.createElement('a');
        cartFloat.href = '/cart';
        cartFloat.className = 'cart-floating';
        cartFloat.innerHTML = `
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count" style="display: none;">0</span>
        `;
        document.body.appendChild(cartFloat);
    }
});

// Styles CSS pour les toasts
const toastStyles = `
.toast {
    position: fixed;
    bottom: 100px;
    left: 50%;
    transform: translateX(-50%) translateY(100px);
    background: var(--dark-color);
    color: white;
    padding: 12px 24px;
    border-radius: 25px;
    z-index: 1002;
    transition: transform 0.3s ease;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.toast.show {
    transform: translateX(-50%) translateY(0);
}

.toast-success {
    background: var(--success-color);
}

.toast-error {
    background: var(--danger-color);
}

.toast-warning {
    background: var(--warning-color);
    color: var(--dark-color);
}

.low-data-mode img {
    filter: contrast(0.8) brightness(1.1);
}

.reduce-motion * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
}
`;

// Injecter les styles
const styleSheet = document.createElement('style');
styleSheet.textContent = toastStyles;
document.head.appendChild(styleSheet);