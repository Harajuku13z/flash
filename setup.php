<?php
// ============================================================
// FLASH — Script d'installation unique (accès public temporaire)
// Supprimez ce fichier après exécution !
// ============================================================

// Protection basique
$secret = $_GET['key'] ?? '';
if ($secret !== 'flash2025setup') {
    die('<h2 style="font-family:sans-serif;color:#DC2626;">❌ Accès refusé. Ajoutez ?key=flash2025setup à l\'URL.</h2>');
}

define('DB_HOST', 'localhost');
define('DB_USER', 'u686558857_flash2');
define('DB_PASS', 'Harajuku1993@');
define('DB_NAME', 'u686558857_flash2');

$log = [];
$ok  = true;

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $log[] = '✅ Connexion MySQL réussie.';
} catch (PDOException $e) {
    die('<pre style="font-family:sans-serif;color:#DC2626;">❌ Connexion échouée : ' . $e->getMessage() . '</pre>');
}

// ── Tables ───────────────────────────────────────────────────
$tables = [
"CREATE TABLE IF NOT EXISTS `drivers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `age` INT DEFAULT NULL,
  `experience` VARCHAR(255) DEFAULT NULL,
  `license_type` VARCHAR(100) DEFAULT NULL,
  `availability` VARCHAR(100) DEFAULT NULL,
  `years_in_taxi` INT DEFAULT 0,
  `cv_path` VARCHAR(500) DEFAULT NULL,
  `photo_path` VARCHAR(500) DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `status` ENUM('pending','active','inactive') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"CREATE TABLE IF NOT EXISTS `owner_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `taxi_type` VARCHAR(100) DEFAULT NULL,
  `desired_schedule` VARCHAR(255) DEFAULT NULL,
  `required_experience` VARCHAR(255) DEFAULT NULL,
  `working_conditions` TEXT DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `status` ENUM('pending','active','closed') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"CREATE TABLE IF NOT EXISTS `offers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `taxi_type` VARCHAR(100) DEFAULT NULL,
  `conditions` TEXT DEFAULT NULL,
  `availability` VARCHAR(100) DEFAULT NULL,
  `required_experience` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('active','closed') DEFAULT 'active',
  `is_urgent` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"CREATE TABLE IF NOT EXISTS `articles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `excerpt` TEXT DEFAULT NULL,
  `content` LONGTEXT DEFAULT NULL,
  `category` VARCHAR(100) DEFAULT NULL,
  `reading_time` INT DEFAULT 5,
  `image` VARCHAR(500) DEFAULT NULL,
  `status` ENUM('published','draft') DEFAULT 'published',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"CREATE TABLE IF NOT EXISTS `contacts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `profile_type` ENUM('chauffeur','proprietaire','autre') NOT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
];

foreach ($tables as $sql) {
    try {
        $pdo->exec($sql);
        preg_match('/`(\w+)`/', $sql, $m);
        $log[] = '✅ Table `' . ($m[1] ?? '?') . '` créée (ou déjà existante).';
    } catch (PDOException $e) {
        $log[] = '❌ Erreur table : ' . $e->getMessage();
        $ok = false;
    }
}

// ── Seed : Chauffeurs ────────────────────────────────────────
$cnt = $pdo->query("SELECT COUNT(*) FROM drivers")->fetchColumn();
if ($cnt == 0) {
    $drivers = [
        ['Jean-Marc Moukala', '+242 06 123 4567', 'Brazzaville', 32, '6 ans d\'expérience', 'Permis B', 'Disponible immédiatement', 6],
        ['Brice Ngoyi',       '+242 05 987 6543', 'Pointe-Noire', 28, '3 ans d\'expérience', 'Permis B', 'Disponible immédiatement', 3],
        ['Alain Mavoungou',   '+242 06 555 4321', 'Dolisie',      35, '8 ans d\'expérience', 'Permis B+C','Disponible en journée',    8],
        ['Patrick Nguimbi',   '+242 05 111 2222', 'Nkayi',        30, '4 ans d\'expérience', 'Permis B', 'Disponible en journée',    4],
    ];
    $stmt = $pdo->prepare("INSERT INTO drivers (full_name,phone,city,age,experience,license_type,availability,years_in_taxi,status) VALUES (?,?,?,?,?,?,?,?,'active')");
    foreach ($drivers as $d) { $stmt->execute($d); }
    $log[] = '✅ ' . count($drivers) . ' chauffeurs exemples insérés.';
} else {
    $log[] = 'ℹ️ Chauffeurs déjà présents (' . $cnt . ').';
}

// ── Seed : Offres ────────────────────────────────────────────
$cnt = $pdo->query("SELECT COUNT(*) FROM offers")->fetchColumn();
if ($cnt == 0) {
    $offers = [
        ['Chauffeur recherché — taxi vert-blanc Brazzaville', 'Brazzaville', 'Taxi vert-blanc',    'Recette journalière : 15 000 FCFA. Carburant à la charge du chauffeur. Véhicule en bon état.', 'Temps plein',   '2 ans minimum',       0],
        ['Propriétaire cherche chauffeur — taxi bleu-blanc',   'Pointe-Noire','Taxi bleu-blanc',    'Recette : 12 000 FCFA/jour. Permis valide obligatoire. Bonne connaissance de la ville.',        'Temps plein',   '1 an minimum',        0],
        ['URGENT — Chauffeur taxi disponible immédiatement',   'Dolisie',     'Taxi ordinaire',     'Démarrage immédiat. Rémunération attractive. Véhicule fourni en bon état.',                     'Temps partiel', 'Débutant accepté',    1],
        ['Chauffeur de taxi sérieux recherché',                'Nkayi',       'Taxi ordinaire',     'Bonne connaissance de Nkayi exigée. Recette : 8 000 FCFA. Carburant inclus.',                  'Temps plein',   '1 an minimum',        0],
        ['Propriétaire cherche chauffeur — taxi climatisé',    'Brazzaville', 'Taxi climatisé',     'Recette : 20 000 FCFA. Carburant inclus. Véhicule récent 2020.',                               'Temps plein',   '3 ans minimum',       0],
    ];
    $stmt = $pdo->prepare("INSERT INTO offers (title,city,taxi_type,conditions,availability,required_experience,is_urgent) VALUES (?,?,?,?,?,?,?)");
    foreach ($offers as $o) { $stmt->execute($o); }
    $log[] = '✅ ' . count($offers) . ' offres exemples insérées.';
} else {
    $log[] = 'ℹ️ Offres déjà présentes (' . $cnt . ').';
}

// ── Seed : Articles ──────────────────────────────────────────
$cnt = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
if ($cnt == 0) {
    $articles = [
        ['Comment rentabiliser son taxi au Congo',
         'comment-rentabiliser-son-taxi-au-congo',
         'Découvrez les meilleures stratégies pour maximiser vos revenus avec votre taxi au Congo.',
         '<h2>1. Fixez une recette juste</h2><p>La recette journalière doit couvrir vos charges et vous laisser un bénéfice. À Brazzaville, la moyenne est de 12 000 à 20 000 FCFA selon le type de taxi.</p><h2>2. Choisissez le bon chauffeur</h2><p>Un chauffeur fiable et honnête, c\'est la clé de votre rentabilité. Vérifiez le permis, les références et testez la conduite.</p><h2>3. Entretenez régulièrement</h2><p>Un taxi en panne, c\'est une journée de perdue. Planifiez les révisions tous les 5 000 km minimum.</p><h2>4. Suivez vos comptes</h2><p>Tenez un carnet de bord journalier pour suivre recettes, carburant et réparations.</p>',
         'Gestion', 5],

        ['Comment choisir un bon chauffeur',
         'comment-choisir-un-bon-chauffeur',
         'Les critères essentiels pour sélectionner un chauffeur fiable et professionnel.',
         '<h2>1. Vérifiez le permis</h2><p>Assurez-vous que le permis est valide et correspond à la catégorie du véhicule.</p><h2>2. Testez la conduite</h2><p>Faites un tour d\'essai. Observez la prudence, la politesse et la connaissance de la ville.</p><h2>3. Contrôlez les références</h2><p>Contactez les anciens employeurs. Un bon chauffeur a de bonnes relations avec ses précédents propriétaires.</p><h2>4. Établissez un accord clair</h2><p>Fixez par écrit la recette, les horaires et les responsabilités dès le départ.</p>',
         'Recrutement', 4],

        ['Comment fixer la recette journalière',
         'comment-fixer-la-recette-journaliere',
         'Guide pratique pour établir une recette juste entre propriétaire et chauffeur.',
         '<h2>Calculez vos charges fixes</h2><p>Listez : assurance, vignette, amortissement véhicule. Divisez par 26 jours de travail mensuel.</p><h2>Fourchettes par ville</h2><ul><li>Brazzaville : 12 000 – 20 000 FCFA</li><li>Pointe-Noire : 10 000 – 18 000 FCFA</li><li>Dolisie : 8 000 – 12 000 FCFA</li><li>Nkayi : 6 000 – 10 000 FCFA</li></ul><h2>Le carburant</h2><p>En général, le chauffeur paye le carburant et remet la recette nette. Certains propriétaires fournissent le carburant avec une recette plus élevée.</p>',
         'Finance', 3],

        ['Comment entretenir son taxi',
         'comment-entretenir-son-taxi',
         'Les bonnes pratiques pour maintenir votre véhicule en parfait état et éviter les pannes.',
         '<h2>Entretien régulier</h2><ul><li>Vidange huile : tous les 5 000 km</li><li>Filtres : tous les 10 000 km</li><li>Freins : vérification tous les 15 000 km</li><li>Pneus : pression hebdomadaire</li></ul><h2>Carnet d\'entretien</h2><p>Notez chaque intervention. Cela permet d\'anticiper les prochaines révisions et valorise le véhicule à la revente.</p>',
         'Entretien', 4],

        ['Les erreurs à éviter dans le business taxi',
         'les-erreurs-a-eviter-dans-le-business-taxi',
         'Les pièges classiques qui coûtent cher aux propriétaires et chauffeurs au Congo.',
         '<h2>Erreur 1 — Pas d\'accord écrit</h2><p>Sans accord clair, les conflits arrivent vite. Notez la recette, les horaires et les responsabilités.</p><h2>Erreur 2 — Recette trop haute</h2><p>Une recette excessive démotive le chauffeur et risque de l\'amener à brusquer le véhicule.</p><h2>Erreur 3 — Ignorer l\'entretien</h2><p>Reporter les révisions coûte toujours plus cher à terme.</p><h2>Erreur 4 — Pas d\'assurance</h2><p>Rouler sans assurance valide, c\'est risquer de tout perdre en cas d\'accident.</p>',
         'Conseils', 6],

        ['Taxis de Brazzaville, Pointe-Noire, Dolisie et Nkayi',
         'taxis-brazzaville-vs-pointe-noire',
         'Comparaison du marché du taxi dans les principales villes du Congo.',
         '<h2>Brazzaville</h2><p>Capitale du Congo. Taxi vert-blanc emblématique. Fort volume de trafic. Recettes : 12 000 – 20 000 FCFA/jour.</p><h2>Pointe-Noire</h2><p>2e ville, centre pétrolier. Taxi bleu-blanc. Clientèle internationale. Recettes : 10 000 – 18 000 FCFA/jour.</p><h2>Dolisie</h2><p>3e ville, carrefour commercial. Moins de concurrence. Recettes : 8 000 – 12 000 FCFA/jour.</p><h2>Nkayi</h2><p>Ville industrielle. Marché de niche. Recettes : 6 000 – 10 000 FCFA/jour.</p>',
         'Marché', 5],
    ];
    $stmt = $pdo->prepare("INSERT INTO articles (title,slug,excerpt,content,category,reading_time) VALUES (?,?,?,?,?,?)");
    foreach ($articles as $a) { $stmt->execute($a); }
    $log[] = '✅ ' . count($articles) . ' articles exemples insérés.';
} else {
    $log[] = 'ℹ️ Articles déjà présents (' . $cnt . ').';
}

// ── Vérifications ────────────────────────────────────────────
$stats = [
    'drivers'        => $pdo->query("SELECT COUNT(*) FROM drivers")->fetchColumn(),
    'offers'         => $pdo->query("SELECT COUNT(*) FROM offers")->fetchColumn(),
    'articles'       => $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn(),
    'owner_requests' => $pdo->query("SELECT COUNT(*) FROM owner_requests")->fetchColumn(),
    'contacts'       => $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn(),
];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>FLASH — Installation</title>
<style>
body{font-family:'Segoe UI',sans-serif;max-width:700px;margin:40px auto;padding:20px;background:#F5F6F8;color:#111;}
h1{color:#0070FF;font-size:28px;margin-bottom:4px;}
.sub{color:#6B7280;margin-bottom:28px;}
.log{background:#fff;border-radius:12px;padding:20px;margin-bottom:20px;border:1px solid #E5E7EB;}
.log p{margin:6px 0;font-size:15px;}
.stats{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:24px;}
.stat{background:#fff;border-radius:10px;padding:16px;text-align:center;border:1px solid #E5E7EB;}
.stat-n{font-size:28px;font-weight:800;color:#0070FF;}
.stat-l{font-size:11px;color:#6B7280;margin-top:4px;}
.links{display:flex;gap:10px;flex-wrap:wrap;}
.btn{display:inline-block;padding:10px 22px;background:#0070FF;color:#fff;text-decoration:none;border-radius:50px;font-weight:600;font-size:14px;}
.btn-y{background:#FFC400;color:#111;}
.btn-r{background:#DC2626;}
.warn{background:#FFF8DC;border:1px solid #FDE68A;border-radius:10px;padding:16px;font-size:13px;color:#92700A;margin-bottom:20px;}
</style>
</head>
<body>
<h1>⚡ FLASH — Installation</h1>
<p class="sub">Base de données initialisée avec succès.</p>

<div class="log">
<?php foreach ($log as $l): ?><p><?= $l ?></p><?php endforeach; ?>
</div>

<div class="stats">
<?php foreach ($stats as $table => $count): ?>
<div class="stat">
    <div class="stat-n"><?= $count ?></div>
    <div class="stat-l"><?= $table ?></div>
</div>
<?php endforeach; ?>
</div>

<div class="warn">⚠️ <strong>Supprimez ce fichier</strong> après utilisation ! Accédez à votre hébergeur et supprimez <code>setup.php</code> pour des raisons de sécurité.</div>

<div class="links">
    <a href="/" class="btn">🏠 Voir le site</a>
    <a href="/offres" class="btn">📋 Offres</a>
    <a href="/chauffeurs" class="btn">🚗 Chauffeurs</a>
    <a href="/conseils" class="btn">📚 Conseils</a>
    <a href="/contact" class="btn btn-y">✉️ Contact</a>
</div>
</body>
</html>
