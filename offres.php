<?php
require_once __DIR__ . '/includes/db.php';
$pdo = getDB();

$ville = $_GET['ville'] ?? '';
$type = $_GET['type'] ?? '';
$dispo = $_GET['disponibilite'] ?? '';
$urgence = $_GET['urgence'] ?? '';
$q = $_GET['q'] ?? '';

$where = ["status='active'"];
$params = [];
if ($ville) { $where[] = "city=?"; $params[] = $ville; }
if ($type) { $where[] = "taxi_type=?"; $params[] = $type; }
if ($dispo) { $where[] = "availability=?"; $params[] = $dispo; }
if ($urgence) { $where[] = "is_urgent=1"; }
if ($q) { $where[] = "(title LIKE ? OR conditions LIKE ?)"; $params[] = "%$q%"; $params[] = "%$q%"; }

$sql = "SELECT * FROM offers WHERE " . implode(' AND ', $where) . " ORDER BY is_urgent DESC, created_at DESC";
$offers = [];
$total = 0;
if ($pdo) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $offers = $stmt->fetchAll();
        $total = count($offers);
    } catch(Exception $e){}
}

// Fallback static offers
if (empty($offers)) {
    $offers = [
        ['id'=>1,'title'=>'Chauffeur taxi – Brazzaville centre','city'=>'Brazzaville','taxi_type'=>'Berline','availability'=>'Temps plein','is_urgent'=>1,'conditions'=>'Partage 60/40, véhicule récent Toyota Corolla. Permis B exigé, 2 ans min.','required_experience'=>'2 ans min.','img_index'=>1],
        ['id'=>2,'title'=>'Co-gérant taxi – Pointe-Noire','city'=>'Pointe-Noire','taxi_type'=>'SUV','availability'=>'Temps plein','is_urgent'=>0,'conditions'=>'Contrat co-gérance, bonne connaissance de Pointe-Noire requise.','required_experience'=>'3 ans min.','img_index'=>2],
        ['id'=>3,'title'=>'Chauffeur disponible immédiatement','city'=>'Brazzaville','taxi_type'=>'Berline','availability'=>'Disponible immédiatement','is_urgent'=>1,'conditions'=>'Remplacement urgent, démarrage immédiat. Expérience taxi appréciée.','required_experience'=>'1 an min.','img_index'=>3],
        ['id'=>4,'title'=>'Taxi partagé – Dolisie','city'=>'Dolisie','taxi_type'=>'Minibus','availability'=>'Temps partiel','is_urgent'=>0,'conditions'=>'Trajet Dolisie–Brazzaville, 3 jours/semaine. Véhicule fourni.','required_experience'=>'1 an min.','img_index'=>4],
        ['id'=>5,'title'=>'Chauffeur VTC premium – Brazzaville','city'=>'Brazzaville','taxi_type'=>'SUV','availability'=>'Temps plein','is_urgent'=>0,'conditions'=>'Clientèle entreprises, tenue professionnelle exigée. Bonus mensuel.','required_experience'=>'5 ans min.','img_index'=>5],
        ['id'=>6,'title'=>'Chauffeur taxi – Nkayi','city'=>'Nkayi','taxi_type'=>'Berline','availability'=>'Temps plein','is_urgent'=>0,'conditions'=>'Secteur Nkayi, bonne connaissance locale. Partage 55/45.','required_experience'=>'2 ans min.','img_index'=>1],
    ];
    $total = count($offers);
}

// Card images rotation
function offerImg($offer) {
    $idx = ($offer['img_index'] ?? ($offer['id'] % 5 + 1));
    $idx = max(1, min(5, (int)$idx));
    foreach(['jpg','jpeg','png','webp','gif'] as $ext) {
        if(file_exists(__DIR__.'/assets/images/taxi-card-'.$idx.'.'.$ext))
            return '/assets/images/taxi-card-'.$idx.'.'.$ext;
    }
    // fallback to hero
    foreach(['jpg','jpeg','png','webp'] as $ext) {
        if(file_exists(__DIR__.'/assets/images/taxi-hero.'.$ext))
            return '/assets/images/taxi-hero.'.$ext;
    }
    return null;
}

$pageTitle = 'Offres de chauffeurs taxi';
include __DIR__ . '/includes/header.php';
?>

<style>
/* ── Offres page ──────────────────────────── */
.hw-offers-hero {
    background: #fff;
    border-bottom: 1px solid #E5E7EB;
    padding: 52px 24px 40px;
    text-align: center;
}
.hw-offers-hero h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(26px, 4vw, 40px);
    font-weight: 900;
    color: #111;
    margin-bottom: 10px;
    letter-spacing: -0.5px;
}
.hw-offers-hero p {
    font-size: 16px;
    color: #6B7280;
    margin-bottom: 28px;
}
.hw-search-bar {
    display: flex;
    gap: 10px;
    max-width: 520px;
    margin: 0 auto;
    flex-wrap: wrap;
}
.hw-search-bar input {
    flex: 1;
    min-width: 200px;
    height: 48px;
    border: 1.5px solid #E5E7EB;
    border-radius: 50px;
    padding: 0 20px;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    color: #111;
    outline: none;
    transition: border-color 0.2s;
}
.hw-search-bar input:focus { border-color: #0070FF; }
.hw-search-bar button {
    height: 48px;
    padding: 0 28px;
    background: #0070FF;
    color: #fff;
    border: none;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 700;
    font-family: 'Montserrat', sans-serif;
    cursor: pointer;
    transition: background 0.2s;
}
.hw-search-bar button:hover { background: #005FD6; }

/* Filters */
.hw-filters-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px 24px 0;
}
.hw-filters-bar {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 16px;
}
.hw-filter-select {
    height: 40px;
    padding: 0 16px;
    border: 1.5px solid #E5E7EB;
    border-radius: 50px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    color: #374151;
    background: #fff;
    cursor: pointer;
    outline: none;
    transition: border-color 0.2s;
}
.hw-filter-select:focus { border-color: #0070FF; }
.hw-filter-reset {
    font-size: 13px;
    color: #0070FF;
    text-decoration: none;
    font-weight: 600;
    white-space: nowrap;
}
.hw-filter-count {
    margin-left: auto;
    font-size: 13px;
    color: #6B7280;
    font-family: 'Inter', sans-serif;
    white-space: nowrap;
}
.hw-badge-filters {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 28px;
}
.hw-badge-pill {
    display: inline-block;
    padding: 6px 16px;
    background: #EEF2FF;
    color: #0070FF;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 700;
    font-family: 'Montserrat', sans-serif;
    text-decoration: none;
    transition: background 0.15s, color 0.15s;
}
.hw-badge-pill:hover { background: #0070FF; color: #fff; }
.hw-badge-pill.urgent { background: #FFF3F3; color: #DC2626; }
.hw-badge-pill.urgent:hover { background: #DC2626; color: #fff; }

/* Cards grid */
.hw-offers-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px 80px;
}
.hw-offers-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-bottom: 56px;
}
@media (max-width: 1023px) { .hw-offers-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px)  { .hw-offers-grid { grid-template-columns: 1fr; } }

/* Hello Work card */
.hw-ocard {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s, transform 0.2s;
    border: 1px solid #F3F4F6;
    text-decoration: none;
    color: inherit;
}
.hw-ocard:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,0.13);
    transform: translateY(-2px);
}
.hw-ocard-img-wrap {
    position: relative;
    height: 180px;
    overflow: hidden;
    background: linear-gradient(135deg, #0070FF 0%, #004DB3 100%);
    flex-shrink: 0;
}
.hw-ocard-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s;
}
.hw-ocard:hover .hw-ocard-img { transform: scale(1.04); }
.hw-ocard-img-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0.28) 100%);
}
.hw-ocard-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    background: #fff;
    border-radius: 50px;
    padding: 5px 12px;
    font-size: 11px;
    font-weight: 800;
    font-family: 'Montserrat', sans-serif;
    color: #111;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    display: flex;
    align-items: center;
    gap: 5px;
}
.hw-ocard-badge-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #0070FF;
    flex-shrink: 0;
}
.hw-ocard-urgent {
    position: absolute;
    top: 14px;
    right: 14px;
    background: #DC2626;
    color: #fff;
    border-radius: 50px;
    padding: 5px 10px;
    font-size: 10px;
    font-weight: 800;
    font-family: 'Montserrat', sans-serif;
}
.hw-ocard-body {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.hw-ocard-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 15px;
    font-weight: 800;
    color: #111;
    line-height: 1.35;
}
.hw-ocard-owner {
    font-size: 13px;
    color: #6B7280;
    font-family: 'Inter', sans-serif;
}
.hw-ocard-tags {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}
.hw-ocard-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #F3F4F6;
    border-radius: 50px;
    font-size: 11.5px;
    font-weight: 600;
    color: #374151;
    font-family: 'Inter', sans-serif;
}
.hw-ocard-conditions {
    font-size: 12.5px;
    color: #9CA3AF;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.hw-ocard-footer {
    padding: 0 20px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}
.hw-ocard-date {
    font-size: 11px;
    color: #9CA3AF;
    font-family: 'Inter', sans-serif;
}
.hw-ocard-btn {
    display: inline-block;
    padding: 9px 20px;
    border: 1.5px solid #0070FF;
    color: #0070FF;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 700;
    font-family: 'Montserrat', sans-serif;
    text-decoration: none;
    transition: background 0.15s, color 0.15s;
    white-space: nowrap;
}
.hw-ocard-btn:hover { background: #0070FF; color: #fff; }

/* Empty state */
.hw-empty {
    grid-column: 1/-1;
    text-align: center;
    padding: 80px 20px;
}
.hw-empty-icon { font-size: 52px; margin-bottom: 16px; }
.hw-empty h3 { font-size: 18px; font-weight: 800; color: #111; margin-bottom: 8px; font-family: 'Montserrat', sans-serif; }
.hw-empty p { font-size: 14px; color: #6B7280; margin-bottom: 24px; }

/* CTA banner */
.hw-offers-cta {
    background: #EEF2FF;
    border-radius: 24px;
    padding: 48px 32px;
    text-align: center;
}
.hw-offers-cta h3 {
    font-family: 'Montserrat', sans-serif;
    font-size: 22px;
    font-weight: 900;
    color: #111;
    margin-bottom: 8px;
}
.hw-offers-cta p {
    font-size: 14px;
    color: #6B7280;
    margin-bottom: 24px;
}
.hw-offers-cta a {
    display: inline-block;
    padding: 14px 36px;
    background: #0070FF;
    color: #fff;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 800;
    font-family: 'Montserrat', sans-serif;
    text-decoration: none;
    transition: background 0.2s;
}
.hw-offers-cta a:hover { background: #005FD6; }
</style>

<!-- Hero search -->
<div class="hw-offers-hero">
    <h1>Offres de recrutement</h1>
    <p>Trouvez le partenariat idéal entre propriétaire et chauffeur au Congo</p>
    <form method="GET" class="hw-search-bar">
        <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="🔍  Chercher une offre, ville…">
        <button type="submit">Rechercher</button>
    </form>
</div>

<!-- Filters -->
<div class="hw-filters-wrap">
    <div class="hw-filters-bar">
        <select class="hw-filter-select" onchange="applyFilter('ville', this.value)">
            <option value="">📍 Toutes les villes</option>
            <?php foreach(['Brazzaville','Pointe-Noire','Dolisie','Nkayi','Ouesso'] as $v): ?>
            <option value="<?=$v?>" <?=$ville==$v?'selected':''?>><?=$v?></option>
            <?php endforeach; ?>
        </select>
        <select class="hw-filter-select" onchange="applyFilter('disponibilite', this.value)">
            <option value="">⏰ Toutes disponibilités</option>
            <?php foreach(['Temps plein','Temps partiel','Disponible immédiatement'] as $d): ?>
            <option value="<?=$d?>" <?=$dispo==$d?'selected':''?>><?=$d?></option>
            <?php endforeach; ?>
        </select>
        <select class="hw-filter-select" onchange="applyFilter('urgence', this.value)">
            <option value="">Toutes les offres</option>
            <option value="1" <?=$urgence?'selected':''?>>🔥 Urgentes uniquement</option>
        </select>
        <?php if ($ville || $dispo || $urgence || $q): ?>
        <a href="/offres" class="hw-filter-reset">✕ Réinitialiser</a>
        <?php endif; ?>
        <span class="hw-filter-count"><?= $total ?> offre<?= $total > 1 ? 's' : '' ?> trouvée<?= $total > 1 ? 's' : '' ?></span>
    </div>
    <div class="hw-badge-filters">
        <a href="/offres?urgence=1" class="hw-badge-pill urgent">🔥 Urgent</a>
        <a href="/offres?disponibilite=Disponible+imm%C3%A9diatement" class="hw-badge-pill">⚡ Disponible immédiatement</a>
        <a href="/offres?disponibilite=Temps+plein" class="hw-badge-pill">🕐 Temps plein</a>
        <a href="/offres?disponibilite=Temps+partiel" class="hw-badge-pill">🕔 Temps partiel</a>
        <a href="/offres?ville=Brazzaville" class="hw-badge-pill">📍 Brazzaville</a>
        <a href="/offres?ville=Pointe-Noire" class="hw-badge-pill">📍 Pointe-Noire</a>
    </div>
</div>

<!-- Cards -->
<div class="hw-offers-section">
    <div class="hw-offers-grid">
        <?php if (empty($offers)): ?>
        <div class="hw-empty">
            <div class="hw-empty-icon">🔍</div>
            <h3>Aucune offre trouvée</h3>
            <p>Essayez d'élargir vos critères de recherche.</p>
            <a href="/offres" class="hw-ocard-btn" style="border:none;background:#0070FF;color:#fff;padding:12px 28px;">Voir toutes les offres</a>
        </div>
        <?php else: ?>
        <?php
        $imgCycle = 1;
        foreach ($offers as $offer):
            $img = offerImg($offer + ['img_index' => $imgCycle]);
            $imgCycle = ($imgCycle % 5) + 1;
        ?>
        <div class="hw-ocard">
            <div class="hw-ocard-img-wrap">
                <?php if ($img): ?>
                <img class="hw-ocard-img" src="<?= $img ?>" alt="<?= htmlspecialchars($offer['title']) ?>"
                     onerror="this.style.display='none'">
                <?php endif; ?>
                <div class="hw-ocard-img-overlay"></div>
                <div class="hw-ocard-badge">
                    <span class="hw-ocard-badge-dot"></span>
                    <?= htmlspecialchars($offer['taxi_type'] ?? 'Taxi') ?>
                </div>
                <?php if (!empty($offer['is_urgent'])): ?>
                <span class="hw-ocard-urgent">🔥 Urgent</span>
                <?php endif; ?>
            </div>
            <div class="hw-ocard-body">
                <div class="hw-ocard-title"><?= htmlspecialchars($offer['title']) ?></div>
                <div class="hw-ocard-owner">Propriétaire · FLASH</div>
                <div class="hw-ocard-tags">
                    <span class="hw-ocard-tag">📍 <?= htmlspecialchars($offer['city']) ?></span>
                    <span class="hw-ocard-tag">⏰ <?= htmlspecialchars($offer['availability']) ?></span>
                    <?php if (!empty($offer['required_experience'])): ?>
                    <span class="hw-ocard-tag">⭐ <?= htmlspecialchars($offer['required_experience']) ?></span>
                    <?php endif; ?>
                </div>
                <?php if (!empty($offer['conditions'])): ?>
                <div class="hw-ocard-conditions"><?= htmlspecialchars($offer['conditions']) ?></div>
                <?php endif; ?>
            </div>
            <div class="hw-ocard-footer">
                <span class="hw-ocard-date">Publié récemment</span>
                <a href="/chauffeurs?offre=<?= $offer['id'] ?>" class="hw-ocard-btn">Voir l'offre</a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- CTA -->
    <div class="hw-offers-cta">
        <h3>Vous êtes propriétaire de taxi ?</h3>
        <p>Publiez votre offre gratuitement et trouvez un chauffeur fiable en quelques jours.</p>
        <a href="/proprietaires">Publier mon offre gratuitement</a>
    </div>
</div>

<script>
function applyFilter(name, value) {
    var url = new URL(window.location.href);
    if (value) { url.searchParams.set(name, value); } else { url.searchParams.delete(name); }
    window.location = url.toString();
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
