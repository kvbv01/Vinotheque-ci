    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h5><i class="fas fa-wine-bottle"></i> VINOTHEQUE CI</h5>
                    <p>Votre caviste de confiance en Afrique. Vins et spiritueux premium avec livraison rapide et sécurisée.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h5><i class="fas fa-info-circle"></i> Informations</h5>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="/about">À propos</a></li>
                        <li><a href="/contact">Contact</a></li>
                        <li><a href="/delivery">Livraison</a></li>
                        <li><a href="/terms">Conditions</a></li>
                        <li><a href="/privacy">Confidentialité</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h5><i class="fas fa-shield-alt"></i> Consommation Responsable</h5>
                    <p><small>L'abus d'alcool est dangereux pour la santé. À consommer avec modération.</small></p>
                    <p><small>Vente interdite aux mineurs. Respect des réglementations locales.</small></p>
                    <div class="age-verification-badge">
                        <i class="fas fa-id-card"></i> 18+
                    </div>
                </div>
            </div>
            
            <hr style="border-color: #495057; margin: 2rem 0 1rem;">
            
            <div class="footer-bottom text-center">
                <p>&copy; <?= date('Y') ?> VINOTHEQUE CI. Tous droits réservés.</p>
                <p><small>Développé avec ❤️ pour l'Afrique</small></p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="/assets/js/mobile-optimizations.js"></script>
    
    <!-- Scripts conditionnels pour améliorer les performances -->
    <script>
        // Charger jQuery seulement si nécessaire
        if (!window.jQuery) {
            const script = document.createElement('script');
            script.src = 'https://code.jquery.com/jquery-3.5.1.slim.min.js';
            script.async = true;
            document.head.appendChild(script);
        }
        
        // Charger Bootstrap JS seulement sur desktop
        if (window.innerWidth > 768) {
            const bootstrapScript = document.createElement('script');
            bootstrapScript.src = 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js';
            bootstrapScript.async = true;
            document.head.appendChild(bootstrapScript);
        }
    </script>
    
    <!-- Analytics et monitoring (à configurer selon les besoins) -->
    <script>
        // Monitoring des performances
        if ('performance' in window) {
            window.addEventListener('load', function() {
                setTimeout(function() {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    if (perfData) {
                        console.log('Temps de chargement:', perfData.loadEventEnd - perfData.fetchStart, 'ms');
                        
                        // Envoyer les métriques au serveur si nécessaire
                        if (perfData.loadEventEnd - perfData.fetchStart > 3000) {
                            console.warn('Chargement lent détecté');
                        }
                    }
                }, 1000);
            });
        }
    </script>
</body>
</html>