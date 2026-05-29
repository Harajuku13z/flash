<?php
require_once __DIR__ . '/includes/db.php';
$pdo = getDB();

$slug = $_GET['slug'] ?? '';
$q = $_GET['q'] ?? '';

// Single article view
if ($slug) {
    $article = null;
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM articles WHERE slug=? AND status='published'");
            $stmt->execute([$slug]);
            $article = $stmt->fetch();
        } catch(Exception $e){}
    }

    if (!$article) {
        // Fallback static articles
        $staticArticles = [
            'comment-rentabiliser-son-taxi-au-congo' => [
                'title' => 'Comment rentabiliser son taxi au Congo',
                'category' => 'Gestion',
                'reading_time' => 5,
                'excerpt' => 'Découvrez les meilleures stratégies pour maximiser vos revenus avec votre taxi au Congo.',
                'content' => '<h2>Fixez une bonne recette journalière</h2><p>La recette journalière est le fondement de votre business taxi. Elle doit couvrir l\'amortissement du véhicule, le carburant, l\'entretien et vous laisser un bénéfice.</p><h2>Choisissez le bon quartier</h2><p>Certains quartiers sont plus rentables que d\'autres. À Brazzaville, les zones comme Bacongo, Poto-Poto et le centre-ville sont très fréquentées.</p><h2>Entretenez votre véhicule régulièrement</h2><p>Un taxi en panne, c\'est une journée de travail perdue. Faites les révisions régulièrement et ne négligez pas les petites réparations.</p><h2>Gérez bien votre relation avec le chauffeur</h2><p>Un bon chauffeur qui se sent respecté prend soin du véhicule. Soyez juste et transparent sur les conditions de travail.</p>',
            ],
            'comment-choisir-un-bon-chauffeur' => [
                'title' => 'Comment choisir un bon chauffeur',
                'category' => 'Recrutement',
                'reading_time' => 4,
                'excerpt' => 'Les critères essentiels pour sélectionner un chauffeur fiable et professionnel.',
                'content' => '<h2>Vérifiez le permis de conduire</h2><p>C\'est la base. Assurez-vous que le permis est valide et correspond au type de véhicule.</p><h2>Testez ses compétences de conduite</h2><p>Faites-le conduire pendant quelques minutes avant de signer. Observez son comportement au volant.</p><h2>Renseignez-vous sur ses références</h2><p>Un bon chauffeur a généralement des références. N\'hésitez pas à contacter ses anciens employeurs.</p><h2>Évaluez son sérieux</h2><p>La ponctualité, la présentation et la communication lors de l\'entretien en disent long sur sa fiabilité.</p>',
            ],
            'comment-fixer-la-recette-journaliere' => [
                'title' => 'Comment fixer la recette journalière',
                'category' => 'Finance',
                'reading_time' => 3,
                'excerpt' => 'Guide pratique pour établir une recette juste entre propriétaire et chauffeur.',
                'content' => '<h2>Calculez vos charges</h2><p>Listez toutes vos charges : carburant, entretien, assurance, amortissement du véhicule. La recette doit couvrir ces charges.</p><h2>Tenez compte du marché local</h2><p>Renseignez-vous sur les recettes pratiquées dans votre ville. À Brazzaville, la recette moyenne tourne autour de 12 000 à 20 000 FCFA selon le type de taxi.</p><h2>Laissez une marge au chauffeur</h2><p>Si le chauffeur ne gagne pas assez, il ne sera pas motivé. Une bonne recette laisse au chauffeur 30 à 40% du chiffre d\'affaires journalier.</p>',
            ],
            'comment-entretenir-son-taxi' => [
                'title' => 'Comment entretenir son taxi',
                'category' => 'Entretien',
                'reading_time' => 4,
                'excerpt' => 'Les bonnes pratiques pour maintenir votre véhicule en parfait état.',
                'content' => '<h2>Faites les vidanges régulièrement</h2><p>Une vidange tous les 5 000 km est indispensable. L\'huile moteur est le sang de votre véhicule.</p><h2>Vérifiez les pneus</h2><p>Des pneus usés sont dangereux et illégaux. Vérifiez la pression régulièrement et remplacez-les dès qu\'ils sont lisses.</p><h2>Soignez les freins</h2><p>Les freins sont un élément de sécurité critique. Faites vérifier les plaquettes tous les 20 000 km.</p><h2>Nettoyez régulièrement</h2><p>Un taxi propre attire plus de clients et donne une meilleure image de votre service.</p>',
            ],
            'les-erreurs-a-eviter-dans-le-business-taxi' => [
                'title' => 'Les erreurs à éviter dans le business taxi',
                'category' => 'Conseils',
                'reading_time' => 6,
                'excerpt' => 'Les pièges classiques qui coûtent cher aux propriétaires et chauffeurs au Congo.',
                'content' => '<h2>Ne pas formaliser l\'accord avec le chauffeur</h2><p>L\'absence d\'accord écrit est source de conflits. Notez clairement la recette, les horaires et les responsabilités de chacun.</p><h2>Négliger l\'entretien préventif</h2><p>Attendre que ça casse coûte beaucoup plus cher que l\'entretien régulier. Planifiez vos révisions.</p><h2>Fixer une recette trop haute</h2><p>Une recette excessive démotive le chauffeur et l\'encourage à négliger le véhicule pour compenser.</p><h2>Ignorer les avis du chauffeur</h2><p>Le chauffeur connaît l\'état réel du véhicule. Écoutez ses signalements et agissez rapidement.</p>',
            ],
            'taxis-brazzaville-vs-pointe-noire' => [
                'title' => 'Taxis de Brazzaville vs Pointe-Noire',
                'category' => 'Marché',
                'reading_time' => 5,
                'excerpt' => 'Comparaison du marché du taxi dans les deux principales villes du Congo.',
                'content' => '<h2>Brazzaville : la capitale</h2><p>Brazzaville est caractérisée par ses taxis vert-blanc. Le marché est très concurrentiel avec de nombreux véhicules. Les recettes moyennes sont de 15 000 à 18 000 FCFA.</p><h2>Pointe-Noire : la ville économique</h2><p>Pointe-Noire a un marché taxi plus spécifique avec ses taxis bleu-blanc. La ville portuaire génère une clientèle plus aisée avec des recettes pouvant atteindre 20 000 FCFA.</p><h2>Dolisie et Nkayi</h2><p>Ces villes ont des marchés plus petits mais avec moins de concurrence. Les recettes sont généralement inférieures mais les charges aussi.</p>',
            ],
        ];
        if (isset($staticArticles[$slug])) {
            $article = array_merge(['slug' => $slug, 'status' => 'published'], $staticArticles[$slug]);
        }
    }

    if ($article) {
        $pageTitle = $article['title'];
        include __DIR__ . '/includes/header.php';
        ?>
        <section class="section">
            <div class="container">
                <div class="breadcrumb">
                    <a href="/">Accueil</a> <span>›</span>
                    <a href="/conseils">Conseils</a> <span>›</span>
                    <span><?= htmlspecialchars($article['title']) ?></span>
                </div>
                <div class="article-content">
                    <span class="tag tag-blue" style="margin-bottom:16px;display:inline-block;"><?= htmlspecialchars($article['category']) ?></span>
                    <h1 style="font-size:clamp(24px,3.5vw,38px);font-weight:800;color:var(--text);margin-bottom:16px;line-height:1.2;"><?= htmlspecialchars($article['title']) ?></h1>
                    <div style="display:flex;align-items:center;gap:16px;margin-bottom:32px;font-size:13px;color:var(--text-light);">
                        <span>📖 <?= $article['reading_time'] ?> min de lecture</span>
                        <span>✍️ Équipe FLASH</span>
                    </div>
                    <div style="background:var(--blue-light);border-left:4px solid var(--blue);border-radius:0 8px 8px 0;padding:16px 20px;margin-bottom:28px;font-size:15px;color:var(--text);font-style:italic;">
                        <?= htmlspecialchars($article['excerpt']) ?>
                    </div>
                    <?= $article['content'] ?>
                    <div style="margin-top:40px;padding-top:32px;border-top:1px solid var(--gray-border);">
                        <a href="/conseils" style="color:var(--blue);text-decoration:none;font-weight:600;">← Retour aux conseils</a>
                    </div>
                </div>
                <!-- CTA -->
                <div style="max-width:740px;margin:40px auto 0;">
                    <div class="cta-section" style="border-radius:16px;">
                        <h3 class="cta-title" style="font-size:22px;">Passez à l'action avec FLASH</h3>
                        <p class="cta-sub" style="font-size:14px;">Trouvez un chauffeur ou un propriétaire maintenant.</p>
                        <div class="cta-buttons">
                            <a href="/chauffeurs" class="btn btn-white">Déposer mon CV</a>
                            <a href="/proprietaires" class="btn btn-yellow">Trouver un chauffeur</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
        include __DIR__ . '/includes/footer.php';
        exit;
    }
}

// Articles list
$articles = [];
if ($pdo) {
    try {
        $articles = $pdo->query("SELECT * FROM articles WHERE status='published' ORDER BY created_at DESC")->fetchAll();
    } catch(Exception $e){}
}

$staticList = [
    ['🚗','Comment rentabiliser son taxi au Congo','comment-rentabiliser-son-taxi-au-congo','Gestion',5,'Découvrez les meilleures stratégies pour maximiser vos revenus avec votre taxi au Congo.'],
    ['👤','Comment choisir un bon chauffeur','comment-choisir-un-bon-chauffeur','Recrutement',4,'Les critères essentiels pour sélectionner un chauffeur fiable et professionnel.'],
    ['💰','Comment fixer la recette journalière','comment-fixer-la-recette-journaliere','Finance',3,'Guide pratique pour établir une recette juste entre propriétaire et chauffeur.'],
    ['🔧','Comment entretenir son taxi','comment-entretenir-son-taxi','Entretien',4,'Les bonnes pratiques pour maintenir votre véhicule en parfait état.'],
    ['⚠️','Les erreurs à éviter dans le business taxi','les-erreurs-a-eviter-dans-le-business-taxi','Conseils',6,'Les pièges classiques qui coûtent cher aux propriétaires et chauffeurs au Congo.'],
    ['🏙️','Taxis de Brazzaville vs Pointe-Noire','taxis-brazzaville-vs-pointe-noire','Marché',5,'Comparaison du marché du taxi dans les deux principales villes du Congo.'],
];

$pageTitle = 'Conseils taxi';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container" style="text-align:center;">
        <p style="font-size:12px;font-weight:700;color:var(--yellow);text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">Blog & ressources</p>
        <h1 class="page-hero-title">Tout connaître du<br>monde du taxi au Congo</h1>
        <p class="page-hero-sub">Conseils, stratégies et bonnes pratiques pour réussir dans le business taxi.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Filters -->
        <div class="filters-bar" style="margin-bottom:28px;">
            <?php foreach(['Tous','Gestion','Recrutement','Finance','Entretien','Conseils','Marché'] as $cat): ?>
            <a href="/conseils<?=$cat!='Tous'?'?cat='.urlencode($cat):''?>" class="tag tag-blue" style="text-decoration:none;padding:6px 16px;"><?=$cat?></a>
            <?php endforeach; ?>
        </div>

        <div class="articles-grid">
            <?php
            $list = !empty($articles) ? array_map(function($a) {
                return [$a['category'] === 'Gestion' ? '🚗' : ($a['category'] === 'Finance' ? '💰' : ($a['category'] === 'Entretien' ? '🔧' : ($a['category'] === 'Marché' ? '🏙️' : '💡'))),$a['title'],$a['slug'],$a['category'],$a['reading_time'],$a['excerpt']];
            }, $articles) : $staticList;
            foreach ($list as $art):
            ?>
            <a href="/conseils/<?= htmlspecialchars($art[2]) ?>" class="article-card">
                <div class="article-card-img">
                    <?= $art[0] ?>
                    <span class="article-card-cat"><?= htmlspecialchars($art[3]) ?></span>
                </div>
                <div class="article-card-body">
                    <div class="article-card-title"><?= htmlspecialchars($art[1]) ?></div>
                    <div class="article-card-excerpt"><?= htmlspecialchars(substr($art[5], 0, 100)) ?>…</div>
                    <div class="article-card-meta">
                        <span>📖 <?= $art[4] ?> min de lecture</span>
                        <span class="article-card-read">Lire →</span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
