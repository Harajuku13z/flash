<?php
require_once __DIR__ . '/includes/db.php';
$pdo = getDB();

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'full_name' => trim($_POST['full_name'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'age' => (int)($_POST['age'] ?? 0),
        'experience' => trim($_POST['experience'] ?? ''),
        'license_type' => trim($_POST['license_type'] ?? ''),
        'availability' => trim($_POST['availability'] ?? ''),
        'years_in_taxi' => (int)($_POST['years_in_taxi'] ?? 0),
        'message' => trim($_POST['message'] ?? ''),
    ];
    if (!$data['full_name']) $errors[] = 'Le nom est obligatoire.';
    if (!$data['phone']) $errors[] = 'Le téléphone est obligatoire.';
    if (!$data['city']) $errors[] = 'La ville est obligatoire.';
    if (!$data['license_type']) $errors[] = 'Le type de permis est obligatoire.';
    if (!$data['availability']) $errors[] = 'La disponibilité est obligatoire.';

    $cvPath = null;
    $photoPath = null;

    if (empty($errors)) {
        // Handle file uploads
        $uploadDir = __DIR__ . '/uploads/';
        if (!empty($_FILES['cv']['name'])) {
            $ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['pdf','doc','docx'])) {
                $fname = 'cv_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['cv']['tmp_name'], $uploadDir . 'cvs/' . $fname)) {
                    $cvPath = 'uploads/cvs/' . $fname;
                }
            } else { $errors[] = 'Le CV doit être en PDF, DOC ou DOCX.'; }
        }
        if (empty($errors) && !empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $fname = 'photo_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . 'photos/' . $fname)) {
                    $photoPath = 'uploads/photos/' . $fname;
                }
            } else { $errors[] = 'La photo doit être en JPG, PNG ou WEBP.'; }
        }
    }

    if (empty($errors) && $pdo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO drivers (full_name,phone,city,age,experience,license_type,availability,years_in_taxi,cv_path,photo_path,message) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$data['full_name'],$data['phone'],$data['city'],$data['age'],$data['experience'],$data['license_type'],$data['availability'],$data['years_in_taxi'],$cvPath,$photoPath,$data['message']]);
            $success = true;
        } catch(Exception $e){ $errors[] = 'Erreur lors de l\'enregistrement. Réessayez.'; }
    } elseif (empty($errors)) {
        $success = true;
    }
}

$drivers = [];
if ($pdo) {
    try { $drivers = $pdo->query("SELECT * FROM drivers WHERE status='active' LIMIT 8")->fetchAll(); } catch(Exception $e){}
}

$pageTitle = 'Je suis chauffeur';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <div style="max-width:560px;margin:0 auto;text-align:center;">
            <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,196,0,0.15);border:1px solid rgba(255,196,0,0.4);color:var(--yellow);font-size:12px;font-weight:700;padding:6px 14px;border-radius:50px;margin-bottom:16px;text-transform:uppercase;">🚗 Pour les chauffeurs</div>
            <h1 class="page-hero-title">Trouvez le propriétaire<br>qui vous correspond</h1>
            <p class="page-hero-sub">Déposez votre CV, consultez les offres et commencez à travailler rapidement.</p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                <a href="#deposer-cv" class="btn btn-yellow btn-lg">Déposer mon CV</a>
                <a href="/offres" class="btn btn-white btn-lg">Voir les offres</a>
            </div>
        </div>
    </div>
</section>

<!-- Avantages chauffeur -->
<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:48px;">
            <?php foreach([
                ['🔍','Offres vérifiées','Toutes les offres sont publiées par de vrais propriétaires.'],
                ['📞','Contact direct','Échangez directement avec le propriétaire.'],
                ['🆓','Gratuit','FLASH est entièrement gratuit pour les chauffeurs.'],
                ['⚡','Rapide','Trouvez un poste en moins de 24h.'],
            ] as $a): ?>
            <div style="text-align:center;padding:24px;background:var(--gray-light);border-radius:var(--radius);">
                <div style="font-size:32px;margin-bottom:10px;"><?=$a[0]?></div>
                <div style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:6px;"><?=$a[1]?></div>
                <div style="font-size:13px;color:var(--text-light);line-height:1.5;"><?=$a[2]?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="content-with-sidebar">
            <!-- Form -->
            <div id="deposer-cv">
                <div class="form-card">
                    <div class="form-title">Déposez votre CV chauffeur</div>
                    <div class="form-sub">Remplissez ce formulaire pour être visible des propriétaires qui recrutent.</div>

                    <?php if ($success): ?>
                    <div class="alert alert-success">✅ Votre profil a été déposé avec succès ! Les propriétaires vont vous contacter.</div>
                    <?php endif; ?>
                    <?php foreach ($errors as $err): ?>
                    <div class="alert alert-error">⚠️ <?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nom complet <span class="required">*</span></label>
                                <input type="text" name="full_name" required placeholder="Jean-Marc Moukala" value="<?=htmlspecialchars($_POST['full_name']??'')?>">
                            </div>
                            <div class="form-group">
                                <label>Téléphone <span class="required">*</span></label>
                                <input type="tel" name="phone" required placeholder="+242 06 000 0000" value="<?=htmlspecialchars($_POST['phone']??'')?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Ville <span class="required">*</span></label>
                                <select name="city" required>
                                    <option value="">Choisir une ville</option>
                                    <?php foreach(['Brazzaville','Pointe-Noire','Dolisie','Nkayi','Autre'] as $v): ?>
                                    <option value="<?=$v?>" <?=($_POST['city']??'')==$v?'selected':''?>><?=$v?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Âge</label>
                                <input type="number" name="age" min="18" max="70" placeholder="28" value="<?=htmlspecialchars($_POST['age']??'')?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Expérience <span class="required">*</span></label>
                                <select name="experience" required>
                                    <option value="">Niveau d'expérience</option>
                                    <option value="Débutant (0-1 an)">Débutant (0-1 an)</option>
                                    <option value="Intermédiaire (2-4 ans)">Intermédiaire (2-4 ans)</option>
                                    <option value="Expérimenté (5+ ans)">Expérimenté (5+ ans)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Type de permis <span class="required">*</span></label>
                                <select name="license_type" required>
                                    <option value="">Type de permis</option>
                                    <option value="Permis B">Permis B</option>
                                    <option value="Permis B+C">Permis B+C</option>
                                    <option value="Permis professionnel">Permis professionnel</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Disponibilité <span class="required">*</span></label>
                                <select name="availability" required>
                                    <option value="">Disponibilité</option>
                                    <option value="Disponible immédiatement">Disponible immédiatement</option>
                                    <option value="Disponible en journée">Disponible en journée</option>
                                    <option value="Disponible en soirée">Disponible en soirée</option>
                                    <option value="Week-end seulement">Week-end seulement</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Ancienneté dans le taxi (années)</label>
                                <input type="number" name="years_in_taxi" min="0" max="50" placeholder="3" value="<?=htmlspecialchars($_POST['years_in_taxi']??'')?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Télécharger votre CV (PDF, DOC, DOCX)</label>
                            <label class="form-file-label" for="cv">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                                Cliquer pour choisir votre CV
                                <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx">
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Votre photo (JPG, PNG, WEBP)</label>
                            <label class="form-file-label" for="photo">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                Cliquer pour choisir votre photo
                                <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.webp">
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Message complémentaire</label>
                            <textarea name="message" placeholder="Présentez-vous, parlez de votre motivation…"><?=htmlspecialchars($_POST['message']??'')?></textarea>
                        </div>
                        <button type="submit" class="btn btn-blue w-full btn-lg">Déposer mon CV →</button>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <div class="sidebar-card">
                    <div class="sidebar-card-title">💡 Conseils pour votre profil</div>
                    <ul style="font-size:13px;color:var(--text-light);line-height:1.8;padding-left:16px;">
                        <li>Ajoutez une photo professionnelle</li>
                        <li>Mentionnez votre type de taxi préféré</li>
                        <li>Précisez vos horaires de disponibilité</li>
                        <li>Parlez de vos références</li>
                        <li>Indiquez votre ville de manière précise</li>
                    </ul>
                </div>
                <div class="sidebar-card">
                    <div class="sidebar-card-title">📋 Offres récentes</div>
                    <?php foreach(array_slice($drivers, 0, 3) as $d): ?>
                    <div style="border-bottom:1px solid var(--gray-border);padding:10px 0;">
                        <div style="font-size:13px;font-weight:600;color:var(--text);"><?=htmlspecialchars($d['full_name'])?></div>
                        <div style="font-size:12px;color:var(--text-light);"><?=htmlspecialchars($d['city'])?> · <?=htmlspecialchars($d['availability'])?></div>
                    </div>
                    <?php endforeach; ?>
                    <a href="/offres" class="btn btn-outline-blue w-full mt-4 btn-sm">Voir toutes les offres</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chauffeurs inscrits -->
<?php if (!empty($drivers)): ?>
<section class="section section-gray">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Chauffeurs disponibles</h2>
        </div>
        <div class="drivers-grid">
            <?php foreach ($drivers as $d): ?>
            <div class="driver-card">
                <div class="driver-avatar">
                    <?php if ($d['photo_path']): ?>
                    <img src="/<?=htmlspecialchars($d['photo_path'])?>" alt="<?=htmlspecialchars($d['full_name'])?>">
                    <?php else: ?>
                    <?= strtoupper(substr($d['full_name'], 0, 1)) ?>
                    <?php endif; ?>
                </div>
                <div class="driver-name"><?=htmlspecialchars($d['full_name'])?></div>
                <div class="driver-city">📍 <?=htmlspecialchars($d['city'])?></div>
                <div class="driver-exp"><?=htmlspecialchars($d['experience'])?></div>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;">
                    <span class="driver-badge"><?=htmlspecialchars($d['availability'])?></span>
                    <span class="tag tag-blue"><?=htmlspecialchars($d['license_type'])?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
