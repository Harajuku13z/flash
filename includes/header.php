<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — ' : '' ?>FLASH — Recrutement de chauffeurs taxi au Congo</title>
    <meta name="description" content="<?= isset($pageDesc) ? htmlspecialchars($pageDesc) : 'FLASH connecte les propriétaires de taxis et les chauffeurs au Congo. Déposez votre CV, trouvez un chauffeur fiable et développez votre business taxi.' ?>">
    <meta name="keywords" content="chauffeur taxi Congo, propriétaire taxi Congo, taxi Brazzaville, taxi Pointe-Noire, recrutement chauffeur taxi, business taxi Congo, déposer CV chauffeur">
    <meta property="og:title" content="FLASH — Recrutement de chauffeurs taxi au Congo">
    <meta property="og:description" content="FLASH connecte les propriétaires de taxis et les chauffeurs au Congo.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>

<header class="site-header" id="site-header">
    <div class="header-inner container">
        <a href="/" class="logo-link">
            <img src="/assets/images/logo-flash.png"
                 alt="FLASH Transport Urbain"
                 style="height:56px;width:auto;max-width:180px;display:block;object-fit:contain;">
        </a>

        <nav class="main-nav" id="main-nav">
            <a href="/offres" class="nav-link">Offres</a>
            <a href="/chauffeurs" class="nav-link">Je suis chauffeur</a>
            <a href="/proprietaires" class="nav-link">Je suis propriétaire</a>
            <a href="/conseils" class="nav-link">Conseils</a>
            <a href="/a-propos" class="nav-link">À propos</a>
        </nav>

        <div class="header-cta">
            <a href="/chauffeurs" class="btn btn-outline-blue">Déposer mon CV</a>
            <a href="/proprietaires" class="btn btn-blue">Trouver un chauffeur</a>
        </div>

        <button class="hamburger" id="hamburger" aria-label="Menu" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

<div class="mobile-menu" id="mobile-menu">
    <a href="/offres">Offres</a>
    <a href="/chauffeurs">Je suis chauffeur</a>
    <a href="/proprietaires">Je suis propriétaire</a>
    <a href="/conseils">Conseils</a>
    <a href="/a-propos">À propos</a>
    <a href="/contact">Contact</a>
    <div class="mobile-cta">
        <a href="/chauffeurs" class="btn btn-outline-blue">Déposer mon CV</a>
        <a href="/proprietaires" class="btn btn-blue">Trouver un chauffeur</a>
    </div>
</div>
