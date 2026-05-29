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

$pageTitle = 'Offres de chauffeurs taxi';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <h1 class="page-hero-title">Offres de recrutement</h1>
        <p class="page-hero-sub">Trouvez le partenariat idéal entre propriétaire et chauffeur au Congo</p>
        <form method="GET" style="display:flex;gap:10px;max-width:520px;margin:0 auto;flex-wrap:wrap;">
            <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Rechercher…" style="flex:1;min-width:180px;">
            <button type="submit" class="btn btn-blue">Rechercher</button>
        </form>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Filters -->
        <div class="filters-bar">
            <select class="filter-select" onchange="applyFilter('ville', this.value)">
                <option value="">Toutes les villes</option>
                <?php foreach(['Brazzaville','Pointe-Noire','Dolisie','Nkayi'] as $v): ?>
                <option value="<?=$v?>" <?=$ville==$v?'selected':''?>><?=$v?></option>
                <?php endforeach; ?>
            </select>
            <select class="filter-select" onchange="applyFilter('disponibilite', this.value)">
                <option value="">Toutes disponibilités</option>
                <?php foreach(['Temps plein','Temps partiel','Disponible immédiatement'] as $d): ?>
                <option value="<?=$d?>" <?=$dispo==$d?'selected':''?>><?=$d?></option>
                <?php endforeach; ?>
            </select>
            <select class="filter-select" onchange="applyFilter('urgence', this.value)">
                <option value="">Toutes les offres</option>
                <option value="1" <?=$urgence?'selected':''?>>Urgentes uniquement</option>
            </select>
            <?php if ($ville || $dispo || $urgence || $q): ?>
            <a href="/offres" style="font-size:13px;color:var(--blue);text-decoration:none;font-weight:600;">✕ Réinitialiser</a>
            <?php endif; ?>
            <span class="filters-count"><?= $total ?> offre<?= $total > 1 ? 's' : '' ?> trouvée<?= $total > 1 ? 's' : '' ?></span>
        </div>

        <!-- Badges filters -->
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:28px;">
            <?php foreach(['Urgent','Disponible','Temps plein','Temps partiel'] as $badge): ?>
            <a href="/offres?disponibilite=<?=urlencode($badge)?>" class="tag tag-blue" style="text-decoration:none;cursor:pointer;padding:6px 14px;"><?=$badge?></a>
            <?php endforeach; ?>
        </div>

        <div class="offers-grid">
            <?php if (empty($offers)): ?>
            <div style="grid-column:1/-1;text-align:center;padding:60px 20px;">
                <div style="font-size:48px;margin-bottom:16px;">🔍</div>
                <h3 style="font-size:18px;font-weight:700;color:var(--text);margin-bottom:8px;">Aucune offre trouvée</h3>
                <p style="color:var(--text-light);">Essayez d'élargir vos critères de recherche.</p>
                <a href="/offres" class="btn btn-blue mt-4">Voir toutes les offres</a>
            </div>
            <?php else: ?>
            <?php foreach ($offers as $offer): ?>
            <div class="offer-card">
                <div class="offer-header">
                    <div class="offer-title"><?= htmlspecialchars($offer['title']) ?></div>
                    <div class="offer-badges">
                        <?php if ($offer['is_urgent']): ?>
                        <span class="tag tag-urgent">🔥 Urgent</span>
                        <?php endif; ?>
                        <span class="tag tag-green">✓ Disponible</span>
                    </div>
                </div>
                <div class="offer-meta">
                    <span class="offer-meta-item">📍 <?= htmlspecialchars($offer['city']) ?></span>
                    <span class="offer-meta-item">🚗 <?= htmlspecialchars($offer['taxi_type']) ?></span>
                    <span class="offer-meta-item">⏰ <?= htmlspecialchars($offer['availability']) ?></span>
                    <span class="offer-meta-item">⭐ <?= htmlspecialchars($offer['required_experience']) ?></span>
                </div>
                <div class="offer-conditions"><?= htmlspecialchars($offer['conditions']) ?></div>
                <div class="offer-footer">
                    <span class="offer-date">Publié récemment</span>
                    <a href="/chauffeurs?offre=<?= $offer['id'] ?>" class="btn btn-blue btn-sm">Postuler</a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- CTA -->
        <div style="text-align:center;margin-top:48px;padding:40px;background:var(--gray-light);border-radius:var(--radius);">
            <h3 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:8px;">Vous êtes propriétaire ?</h3>
            <p style="font-size:14px;color:var(--text-light);margin-bottom:20px;">Publiez votre offre gratuitement et trouvez un chauffeur fiable.</p>
            <a href="/proprietaires" class="btn btn-blue">Publier mon offre</a>
        </div>
    </div>
</section>

<script>
function applyFilter(name, value) {
    var url = new URL(window.location.href);
    if (value) { url.searchParams.set(name, value); } else { url.searchParams.delete(name); }
    window.location = url.toString();
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
