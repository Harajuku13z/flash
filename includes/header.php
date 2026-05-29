<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — ' : '' ?>FLASH — Recrutement de chauffeurs taxi au Congo</title>
    <meta name="description" content="<?= isset($pageDesc) ? htmlspecialchars($pageDesc) : 'FLASH connecte les propriétaires de taxis et les chauffeurs au Congo. Déposez votre CV, trouvez un chauffeur fiable et développez votre business taxi.' ?>">
    <meta name="keywords" content="chauffeur taxi Congo, propriétaire taxi Congo, taxi Brazzaville, taxi Pointe-Noire, recrutement chauffeur taxi">
    <meta property="og:title" content="FLASH — Recrutement de chauffeurs taxi au Congo">
    <meta property="og:description" content="FLASH connecte les propriétaires de taxis et les chauffeurs au Congo.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<style>
/* ══ HEADER HELLO WORK STYLE ══════════════════════════════ */
.hw-header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: #fff;
    border-bottom: 1px solid #E5E7EB;
    box-shadow: none;
    transition: box-shadow 0.2s;
}
.hw-header.scrolled {
    box-shadow: 0 2px 20px rgba(0,0,0,0.08);
}
.hw-header-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 32px;
    height: 68px;
    display: flex;
    align-items: center;
    gap: 0;
}

/* Logo */
.hw-header-logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    flex-shrink: 0;
    margin-right: 40px;
}
.hw-header-logo img {
    height: 52px;
    width: auto;
    max-width: 160px;
    display: block;
    object-fit: contain;
}

/* Nav centrale */
.hw-header-nav {
    display: flex;
    align-items: center;
    gap: 2px;
    flex: 1;
}
.hw-nav-item {
    position: relative;
}
.hw-nav-link {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 8px 14px;
    font-family: 'Inter', sans-serif;
    font-size: 14.5px;
    font-weight: 500;
    color: #111;
    text-decoration: none;
    border-radius: 8px;
    white-space: nowrap;
    transition: background 0.15s, color 0.15s;
    cursor: pointer;
    border: none;
    background: none;
}
.hw-nav-link:hover {
    background: #F3F4F6;
    color: #111;
}
.hw-nav-link.active {
    color: #0070FF;
    font-weight: 600;
}
.hw-nav-link svg {
    opacity: 0.5;
    transition: transform 0.2s;
}
.hw-nav-item:hover .hw-nav-link svg {
    transform: rotate(180deg);
}

/* Dropdown */
.hw-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 14px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    padding: 8px;
    min-width: 220px;
    display: none;
    z-index: 100;
}
.hw-nav-item:hover .hw-dropdown {
    display: block;
}
.hw-dropdown a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    font-size: 14px;
    color: #374151;
    text-decoration: none;
    border-radius: 8px;
    transition: background 0.15s;
    font-family: 'Inter', sans-serif;
}
.hw-dropdown a:hover {
    background: #F3F4F6;
    color: #111;
}
.hw-dropdown-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

/* Bouton Se connecter (pill noir) */
.hw-header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-left: auto;
    flex-shrink: 0;
}
.hw-btn-connect {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #111;
    color: #fff;
    border-radius: 50px;
    padding: 10px 20px;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    position: relative;
    transition: background 0.2s;
    white-space: nowrap;
}
.hw-btn-connect:hover {
    background: #333;
}
.hw-btn-connect svg {
    width: 18px;
    height: 18px;
}
/* Point rouge notification */
.hw-btn-connect::after {
    content: '';
    position: absolute;
    top: 6px;
    right: 6px;
    width: 8px;
    height: 8px;
    background: #EF4444;
    border-radius: 50%;
    border: 1.5px solid #111;
}

/* Hamburger mobile */
.hw-hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    margin-left: auto;
}
.hw-hamburger span {
    display: block;
    width: 24px;
    height: 2px;
    background: #111;
    border-radius: 2px;
    transition: all 0.3s;
}

/* Menu mobile */
.hw-mobile-menu {
    display: none;
    position: fixed;
    inset: 0;
    background: #fff;
    z-index: 999;
    flex-direction: column;
    padding: 24px;
    overflow-y: auto;
}
.hw-mobile-menu.open {
    display: flex;
}
.hw-mobile-close {
    align-self: flex-end;
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    color: #111;
    margin-bottom: 24px;
    padding: 4px;
}
.hw-mobile-menu a {
    display: block;
    font-size: 18px;
    font-weight: 600;
    color: #111;
    text-decoration: none;
    padding: 14px 0;
    border-bottom: 1px solid #F3F4F6;
    font-family: 'Montserrat', sans-serif;
}
.hw-mobile-menu a:hover { color: #0070FF; }
.hw-mobile-btns {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 28px;
}
.hw-mobile-btns a {
    border: none !important;
    text-align: center;
    border-radius: 50px !important;
    padding: 14px !important;
    font-size: 15px !important;
}
.hw-mobile-btn-black {
    background: #111;
    color: #fff !important;
}
.hw-mobile-btn-blue {
    background: #0070FF;
    color: #fff !important;
}

/* Responsive */
@media (max-width: 1023px) {
    .hw-header-nav { display: none; }
    .hw-header-actions .hw-btn-connect { display: none; }
    .hw-hamburger { display: flex; }
    .hw-header-logo { margin-right: 0; }
}
@media (max-width: 480px) {
    .hw-header-inner { padding: 0 16px; }
    .hw-header-logo img { height: 44px; }
}
</style>

<header class="hw-header" id="hw-header">
    <div class="hw-header-inner">

        <!-- Logo -->
        <a href="/" class="hw-header-logo">
            <img src="/assets/images/logo-flash.png"
                 alt="FLASH Transport Urbain"
                 onerror="this.src='';this.alt='FLASH';">
        </a>

        <!-- Navigation centrale -->
        <nav class="hw-header-nav">

            <!-- Offres -->
            <div class="hw-nav-item">
                <a href="/offres" class="hw-nav-link">Trouver une offre</a>
            </div>

            <!-- Chauffeurs avec dropdown -->
            <div class="hw-nav-item">
                <span class="hw-nav-link">
                    Je suis chauffeur
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </span>
                <div class="hw-dropdown">
                    <a href="/chauffeurs">
                        <div class="hw-dropdown-icon" style="background:#EEF2FF;">📄</div>
                        Déposer mon CV
                    </a>
                    <a href="/offres">
                        <div class="hw-dropdown-icon" style="background:#F0FDF4;">🔍</div>
                        Voir les offres
                    </a>
                    <a href="/conseils">
                        <div class="hw-dropdown-icon" style="background:#FFF7ED;">💡</div>
                        Conseils chauffeurs
                    </a>
                </div>
            </div>

            <!-- Propriétaires avec dropdown -->
            <div class="hw-nav-item">
                <span class="hw-nav-link">
                    Je suis propriétaire
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </span>
                <div class="hw-dropdown">
                    <a href="/proprietaires">
                        <div class="hw-dropdown-icon" style="background:#F0FDF4;">🚖</div>
                        Trouver un chauffeur
                    </a>
                    <a href="/offres">
                        <div class="hw-dropdown-icon" style="background:#EFF6FF;">📋</div>
                        Mes offres
                    </a>
                    <a href="/conseils">
                        <div class="hw-dropdown-icon" style="background:#FDF4FF;">💼</div>
                        Conseils propriétaires
                    </a>
                </div>
            </div>

            <!-- Conseils -->
            <div class="hw-nav-item">
                <a href="/conseils" class="hw-nav-link">Conseils</a>
            </div>

            <!-- À propos -->
            <div class="hw-nav-item">
                <a href="/a-propos" class="hw-nav-link">À propos</a>
            </div>

        </nav>

        <!-- Actions droite -->
        <div class="hw-header-actions">
            <a href="/contact" class="hw-btn-connect">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Se connecter
            </a>
        </div>

        <!-- Hamburger mobile -->
        <button class="hw-hamburger" id="hw-hamburger" onclick="toggleHwMenu()" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

    </div>
</header>

<!-- Menu mobile -->
<div class="hw-mobile-menu" id="hw-mobile-menu">
    <button class="hw-mobile-close" onclick="toggleHwMenu()">✕</button>
    <a href="/offres">Trouver une offre</a>
    <a href="/chauffeurs">Déposer mon CV</a>
    <a href="/proprietaires">Trouver un chauffeur</a>
    <a href="/conseils">Conseils</a>
    <a href="/a-propos">À propos</a>
    <a href="/contact">Contact</a>
    <div class="hw-mobile-btns">
        <a href="/chauffeurs" class="hw-mobile-btn-blue">📄 Déposer mon CV</a>
        <a href="/proprietaires" class="hw-mobile-btn-black">🚖 Trouver un chauffeur</a>
    </div>
</div>

<script>
function toggleHwMenu() {
    document.getElementById('hw-mobile-menu').classList.toggle('open');
    document.body.style.overflow =
        document.getElementById('hw-mobile-menu').classList.contains('open') ? 'hidden' : '';
}
// Shadow au scroll
window.addEventListener('scroll', function() {
    document.getElementById('hw-header').classList.toggle('scrolled', window.scrollY > 10);
});
// Active link
(function() {
    var path = window.location.pathname;
    document.querySelectorAll('.hw-nav-link[href]').forEach(function(l) {
        var h = l.getAttribute('href');
        if (h && (h === path || (h !== '/' && path.startsWith(h)))) {
            l.classList.add('active');
        }
    });
})();
</script>
