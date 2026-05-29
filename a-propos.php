<?php
$pageTitle = 'À propos de FLASH';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container" style="text-align:center;">
        <h1 class="page-hero-title">FLASH connecte les talents<br>du taxi au Congo</h1>
        <p class="page-hero-sub">Nous accompagnons propriétaires et chauffeurs dans la réussite du business taxi depuis Brazzaville.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div style="max-width:760px;margin:0 auto;text-align:center;margin-bottom:60px;">
            <p style="font-size:18px;line-height:1.8;color:var(--text);">FLASH connecte les <strong>propriétaires de taxis sérieux</strong> avec des <strong>chauffeurs fiables</strong>, tout en accompagnant chacun dans la réussite du business taxi. Nous croyons que le taxi au Congo mérite une plateforme moderne, simple et efficace.</p>
        </div>

        <!-- Mission / Vision -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:48px;">
            <div style="background:var(--blue);color:white;border-radius:var(--radius);padding:40px;">
                <div style="font-size:36px;margin-bottom:16px;">🎯</div>
                <h3 style="font-size:20px;font-weight:800;margin-bottom:12px;">Notre mission</h3>
                <p style="font-size:15px;opacity:0.85;line-height:1.7;">Faciliter la mise en relation entre propriétaires de taxis et chauffeurs au Congo, en offrant un service transparent, gratuit et efficace.</p>
            </div>
            <div style="background:var(--black);color:white;border-radius:var(--radius);padding:40px;">
                <div style="font-size:36px;margin-bottom:16px;">🔭</div>
                <h3 style="font-size:20px;font-weight:800;margin-bottom:12px;">Notre vision</h3>
                <p style="font-size:15px;opacity:0.85;line-height:1.7;">Devenir la référence du recrutement dans le secteur taxi au Congo, en contribuant à la professionnalisation du transport urbain.</p>
            </div>
        </div>

        <!-- Valeurs -->
        <div class="section-header">
            <p class="section-label">Ce qui nous guide</p>
            <h2 class="section-title">Nos valeurs</h2>
        </div>
        <div class="values-grid">
            <?php foreach([
                ['🤝','Confiance','Nous construisons une communauté basée sur la transparence et le respect mutuel entre propriétaires et chauffeurs.'],
                ['⚡','Rapidité','Connecter un propriétaire à un chauffeur en moins de 24h, c\'est notre engagement quotidien.'],
                ['🆓','Gratuité','FLASH est gratuit pour tous. Nous croyons en l\'accès équitable aux opportunités.'],
                ['🌍','Local','Nous connaissons le marché congo. Nos conseils et outils sont adaptés à la réalité du terrain.'],
                ['📈','Accompagnement','Au-delà du recrutement, nous aidons chacun à développer son activité taxi.'],
                ['🛡️','Qualité','Chaque profil et chaque offre sont vérifiés pour garantir la qualité des échanges.'],
            ] as $v): ?>
            <div class="value-card">
                <div class="value-icon"><?=$v[0]?></div>
                <div class="value-title"><?=$v[1]?></div>
                <div class="value-desc"><?=$v[2]?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Engagement -->
        <div style="background:var(--gray-light);border-radius:var(--radius);padding:48px;text-align:center;margin-top:48px;">
            <h3 style="font-size:24px;font-weight:800;color:var(--text);margin-bottom:16px;">Notre engagement qualité</h3>
            <p style="font-size:16px;color:var(--text-light);max-width:560px;margin:0 auto 28px;line-height:1.7;">Chaque offre publiée sur FLASH est vérifiée. Chaque chauffeur inscrit est un professionnel sérieux. Nous nous engageons à maintenir la qualité de notre plateforme pour le bénéfice de toute la communauté taxi congolaise.</p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                <a href="/contact" class="btn btn-blue btn-lg">Nous contacter</a>
                <a href="/offres" class="btn btn-outline-blue btn-lg">Voir les offres</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
