<?php
require_once __DIR__ . '/db.php';

$pdo = getDB();
if (!$pdo) { die("Connexion DB échouée"); }

$queries = [
"CREATE TABLE IF NOT EXISTS drivers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    city VARCHAR(100) NOT NULL,
    age INT,
    experience VARCHAR(255),
    license_type VARCHAR(100),
    availability VARCHAR(100),
    years_in_taxi INT DEFAULT 0,
    cv_path VARCHAR(500),
    photo_path VARCHAR(500),
    message TEXT,
    status ENUM('pending','active','inactive') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"CREATE TABLE IF NOT EXISTS owner_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    city VARCHAR(100) NOT NULL,
    taxi_type VARCHAR(100),
    desired_schedule VARCHAR(255),
    required_experience VARCHAR(255),
    working_conditions TEXT,
    message TEXT,
    status ENUM('pending','active','closed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"CREATE TABLE IF NOT EXISTS offers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    taxi_type VARCHAR(100),
    conditions TEXT,
    availability VARCHAR(100),
    required_experience VARCHAR(255),
    status ENUM('active','closed') DEFAULT 'active',
    is_urgent TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT,
    category VARCHAR(100),
    reading_time INT DEFAULT 5,
    image VARCHAR(500),
    status ENUM('published','draft') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

"CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    email VARCHAR(255),
    profile_type ENUM('chauffeur','proprietaire','autre') NOT NULL,
    city VARCHAR(100),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
];

foreach ($queries as $sql) {
    $pdo->exec($sql);
}

// Seed offers
$count = $pdo->query("SELECT COUNT(*) FROM offers")->fetchColumn();
if ($count == 0) {
    $offers = [
        ['Chauffeur recherché pour taxi vert-blanc', 'Brazzaville', 'Taxi vert-blanc', 'Recette journalière : 15 000 FCFA. Bon état du véhicule exigé.', 'Temps plein', '2 ans minimum', 1],
        ['Chauffeur cherché pour taxi bleu-blanc', 'Pointe-Noire', 'Taxi bleu-blanc', 'Recette 12 000 FCFA/jour. Permis valide obligatoire.', 'Temps plein', '1 an minimum', 0],
        ['Offre urgente - Chauffeur taxi', 'Dolisie', 'Taxi ordinaire', 'Rémunération à négocier. Démarrage immédiat.', 'Temps partiel', 'Débutant accepté', 1],
        ['Chauffeur de taxi recherché', 'Nkayi', 'Taxi ordinaire', 'Bonne connaissance de la ville exigée. Véhicule fourni.', 'Temps plein', '1 an minimum', 0],
        ['Propriétaire cherche chauffeur sérieux', 'Brazzaville', 'Taxi climatisé', 'Véhicule récent. Recette 20 000 FCFA. Carburant inclus.', 'Temps plein', '3 ans minimum', 0],
    ];
    $stmt = $pdo->prepare("INSERT INTO offers (title, city, taxi_type, conditions, availability, required_experience, is_urgent) VALUES (?,?,?,?,?,?,?)");
    foreach ($offers as $o) { $stmt->execute($o); }
}

// Seed drivers
$count = $pdo->query("SELECT COUNT(*) FROM drivers")->fetchColumn();
if ($count == 0) {
    $drivers = [
        ['Jean-Marc Moukala', '+242 06 123 4567', 'Brazzaville', 32, '6 ans d\'expérience dans le taxi', 'Permis B', 'Disponible immédiatement', 6],
        ['Brice Ngoyi', '+242 05 987 6543', 'Pointe-Noire', 28, '3 ans d\'expérience', 'Permis B', 'Disponible immédiatement', 3],
        ['Alain Mavoungou', '+242 06 555 4321', 'Dolisie', 35, '8 ans d\'expérience', 'Permis B+C', 'Disponible en journée', 8],
        ['Patrick Nguimbi', '+242 05 111 2222', 'Nkayi', 30, '4 ans d\'expérience', 'Permis B', 'Disponible en journée', 4],
    ];
    $stmt = $pdo->prepare("INSERT INTO drivers (full_name, phone, city, age, experience, license_type, availability, years_in_taxi, status) VALUES (?,?,?,?,?,?,?,?,'active')");
    foreach ($drivers as $d) { $stmt->execute($d); }
}

// Seed articles
$count = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
if ($count == 0) {
    $articles = [
        [
            'Comment rentabiliser son taxi au Congo',
            'comment-rentabiliser-son-taxi-au-congo',
            'Découvrez les meilleures stratégies pour maximiser vos revenus avec votre taxi au Congo.',
            '<p>Le taxi est un business lucratif au Congo, à condition de bien le gérer...</p>',
            'Gestion',
            5
        ],
        [
            'Comment choisir un bon chauffeur',
            'comment-choisir-un-bon-chauffeur',
            'Les critères essentiels pour sélectionner un chauffeur fiable et professionnel.',
            '<p>Choisir le bon chauffeur est crucial pour la rentabilité de votre taxi...</p>',
            'Recrutement',
            4
        ],
        [
            'Comment fixer la recette journalière',
            'comment-fixer-la-recette-journaliere',
            'Guide pratique pour établir une recette juste entre propriétaire et chauffeur.',
            '<p>La recette journalière est au cœur de la relation propriétaire-chauffeur...</p>',
            'Finance',
            3
        ],
        [
            'Comment entretenir son taxi',
            'comment-entretenir-son-taxi',
            'Les bonnes pratiques pour maintenir votre véhicule en parfait état.',
            '<p>Un taxi bien entretenu, c\'est moins de pannes et plus de revenus...</p>',
            'Entretien',
            4
        ],
        [
            'Les erreurs à éviter dans le business taxi',
            'les-erreurs-a-eviter-dans-le-business-taxi',
            'Les pièges classiques qui coûtent cher aux propriétaires et chauffeurs au Congo.',
            '<p>Beaucoup de propriétaires font les mêmes erreurs au démarrage...</p>',
            'Conseils',
            6
        ],
        [
            'Taxis de Brazzaville vs Pointe-Noire',
            'taxis-brazzaville-vs-pointe-noire',
            'Comparaison du marché du taxi dans les deux principales villes du Congo.',
            '<p>Le marché du taxi varie considérablement entre Brazzaville et Pointe-Noire...</p>',
            'Marché',
            5
        ],
    ];
    $stmt = $pdo->prepare("INSERT INTO articles (title, slug, excerpt, content, category, reading_time) VALUES (?,?,?,?,?,?)");
    foreach ($articles as $a) { $stmt->execute($a); }
}

echo "Installation terminée avec succès !";
