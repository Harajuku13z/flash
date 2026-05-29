<style>
/* ── Footer Hello Work style ─────────────────────── */
.hw-footer {
    background: #111;
    color: #fff;
    padding-top: 64px;
}
.hw-footer-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 32px;
}
.hw-footer-top {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
    gap: 40px;
    padding-bottom: 56px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
.hw-footer-logo {
    font-family: 'Montserrat', sans-serif;
    font-size: 22px;
    font-weight: 900;
    color: #fff;
    letter-spacing: -0.5px;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.hw-footer-logo span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px; height: 30px;
    background: #0070FF;
    border-radius: 8px;
    font-size: 16px;
}
.hw-footer-tagline {
    font-size: 11px;
    font-weight: 700;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 16px;
    font-family: 'Montserrat', sans-serif;
}
.hw-footer-desc {
    font-size: 13px;
    color: #9CA3AF;
    line-height: 1.7;
    margin-bottom: 24px;
    max-width: 280px;
}
.hw-footer-socials {
    display: flex;
    gap: 10px;
}
.hw-footer-social {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: rgba(255,255,255,0.08);
    display: flex; align-items: center; justify-content: center;
    text-decoration: none;
    color: #9CA3AF;
    transition: background 0.2s, color 0.2s;
}
.hw-footer-social:hover {
    background: rgba(255,255,255,0.15);
    color: #fff;
}
.hw-footer-col h4 {
    font-family: 'Montserrat', sans-serif;
    font-size: 12px;
    font-weight: 800;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 18px;
}
.hw-footer-col a {
    display: block;
    font-size: 13.5px;
    color: #9CA3AF;
    text-decoration: none;
    margin-bottom: 10px;
    transition: color 0.15s;
    font-family: 'Inter', sans-serif;
}
.hw-footer-col a:hover { color: #fff; }
.hw-footer-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px 0;
    gap: 16px;
    flex-wrap: wrap;
}
.hw-footer-copy {
    font-size: 12px;
    color: #6B7280;
    font-family: 'Inter', sans-serif;
}
.hw-footer-legal {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.hw-footer-legal a {
    font-size: 12px;
    color: #6B7280;
    text-decoration: none;
    font-family: 'Inter', sans-serif;
    transition: color 0.15s;
}
.hw-footer-legal a:hover { color: #fff; }

/* WhatsApp float */
.wha-float {
    position: fixed;
    bottom: 28px; right: 28px;
    z-index: 999;
    width: 56px; height: 56px;
    border-radius: 50%;
    background: #25D366;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 20px rgba(37,211,102,0.45);
    text-decoration: none;
    transition: transform 0.2s, box-shadow 0.2s;
}
.wha-float:hover { transform: scale(1.1); box-shadow: 0 6px 28px rgba(37,211,102,0.55); }

@media (max-width: 1023px) {
    .hw-footer-top { grid-template-columns: 1fr 1fr 1fr; gap: 32px; }
    .hw-footer-brand { grid-column: 1 / -1; }
}
@media (max-width: 600px) {
    .hw-footer-inner { padding: 0 20px; }
    .hw-footer-top { grid-template-columns: 1fr 1fr; gap: 24px; }
    .hw-footer-bottom { flex-direction: column; align-items: flex-start; gap: 12px; }
}
</style>

<footer class="hw-footer">
    <div class="hw-footer-inner">
        <div class="hw-footer-top">

            <div class="hw-footer-brand">
                <div class="hw-footer-logo">
                    <span>⚡</span> FLASH
                </div>
                <div class="hw-footer-tagline">Transport Urbain · Congo</div>
                <p class="hw-footer-desc">FLASH connecte les propriétaires de taxis et les chauffeurs au Congo pour développer le business taxi.</p>
                <div class="hw-footer-socials">
                    <a href="https://wa.me/242000000000" class="hw-footer-social" target="_blank" rel="noopener" title="WhatsApp">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                    <a href="#" class="hw-footer-social" title="Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="hw-footer-social" title="Instagram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                </div>
            </div>

            <div class="hw-footer-col">
                <h4>Chauffeurs</h4>
                <a href="/chauffeurs">Déposer mon CV</a>
                <a href="/offres">Voir les offres</a>
                <a href="/conseils">Conseils chauffeurs</a>
                <a href="/contact">Nous contacter</a>
            </div>

            <div class="hw-footer-col">
                <h4>Propriétaires</h4>
                <a href="/proprietaires">Trouver un chauffeur</a>
                <a href="/offres">Publier une offre</a>
                <a href="/conseils">Conseils business</a>
                <a href="/contact">Nous contacter</a>
            </div>

            <div class="hw-footer-col">
                <h4>FLASH</h4>
                <a href="/a-propos">À propos</a>
                <a href="/contact">Contact</a>
                <a href="/offres">Toutes les offres</a>
                <a href="/conseils">Blog conseils</a>
            </div>

            <div class="hw-footer-col">
                <h4>Villes</h4>
                <a href="/offres?ville=Brazzaville">Brazzaville</a>
                <a href="/offres?ville=Pointe-Noire">Pointe-Noire</a>
                <a href="/offres?ville=Dolisie">Dolisie</a>
                <a href="/offres?ville=Nkayi">Nkayi</a>
                <a href="/offres?ville=Ouesso">Ouesso</a>
            </div>

        </div>

        <div class="hw-footer-bottom">
            <p class="hw-footer-copy">&copy; <?= date('Y') ?> FLASH Transport Urbain. Tous droits réservés.</p>
            <div class="hw-footer-legal">
                <a href="/mentions-legales">Mentions légales</a>
                <a href="/confidentialite">Confidentialité</a>
                <a href="/contact">Contact</a>
            </div>
        </div>
    </div>
</footer>

<a href="https://wa.me/242000000000?text=Bonjour%20FLASH%2C%20je%20souhaite%20des%20informations."
   class="wha-float" target="_blank" rel="noopener" title="Contactez-nous sur WhatsApp">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>

<script src="/assets/js/main.js"></script>
</body>
</html>
