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
        <a href="/" class="logo-link" style="display:flex;align-items:center;text-decoration:none;">
            <img src="/assets/images/logo-flash.png"
                 alt="FLASH Transport Urbain"
                 style="height:46px;width:auto;display:block;"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
            <!-- Fallback SVG si logo pas encore uploadé -->
            <svg style="display:none;" width="130" height="38" viewBox="0 0 260 76" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 68V8H38V18H16V33H36V43H16V68H4Z" fill="#0070FF"/>
                <path d="M46 68V8H58V58H82V68H46Z" fill="#0070FF"/>
                <path d="M86 68L106 8H116L136 68H123L119 54H103L99 68H86ZM106 44H116L111 24L106 44Z" fill="#0070FF"/>
                <path d="M108 37L113 22L110 34H115L109 51L113 36H108Z" fill="#FFC400"/>
                <path d="M140 54L150 48C152 54 156 58 162 58C167 58 170 55 170 51C170 47 167 45 160 42C152 39 146 35 146 27C146 17 153 7 165 7C173 7 179 11 183 18L173 24C171 19 168 17 164 17C160 17 158 20 158 24C158 28 161 30 168 33C176 36 182 41 182 50C182 61 174 69 162 69C153 69 145 63 140 54Z" fill="#0070FF"/>
                <path d="M188 68V8H200V32H218V8H230V68H218V42H200V68H188Z" fill="#0070FF"/>
                <path d="M4 73 Q130 80 256 73" stroke="#0070FF" stroke-width="3" fill="none" stroke-linecap="round" opacity="0.4"/>
            </svg>
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
