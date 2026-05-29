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
        'taxi_type' => trim($_POST['taxi_type'] ?? ''),
        'desired_schedule' => trim($_POST['desired_schedule'] ?? ''),
        'required_experience' => trim($_POST['required_experience'] ?? ''),
        'working_conditions' => trim($_POST['working_conditions'] ?? ''),
        'message' => trim($_POST['message'] ?? ''),
    ];
    if (!$data['full_name']) $errors[] = 'Le nom est obligatoire.';
    if (!$data['phone']) $errors[] = 'Le téléphone est obligatoire.';
    if (!$data['city']) $errors[] = 'La ville est obligatoire.';
    if (!$data['taxi_type']) $errors[] = 'Le type de taxi est obligatoire.';
    if (!$data['working_conditions']) $errors[] = 'Les conditions de travail sont obligatoires.';

    if (empty($errors) && $pdo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO owner_requests (full_name,phone,city,taxi_type,desired_schedule,required_experience,working_conditions,message) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute([$data['full_name'],$data['phone'],$data['city'],$data['taxi_type'],$data['desired_schedule'],$data['required_experience'],$data['working_conditions'],$data['message']]);
            $success = true;
        } catch(Exception $e){ $errors[] = 'Erreur lors de l\'enregistrement. Réessayez.'; }
    } elseif (empty($errors)) {
        $success = true;
    }
}

$pageTitle = 'Je suis propriétaire';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <div style="max-width:560px;margin:0 auto;text-align:center;">
            <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,196,0,0.15);border:1px solid rgba(255,196,0,0.4);color:var(--yellow);font-size:12px;font-weight:700;padding:6px 14px;border-radius:50px;margin-bottom:16px;text-transform:uppercase;">🔑 Pour les propriétaires</div>
            <h1 class="page-hero-title">Trouvez un chauffeur<br>fiable rapidement</h1>
            <p class="page-hero-sub">Publiez votre offre gratuitement et recevez les candidatures de chauffeurs vérifiés au Congo.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:48px;">
            <?php foreach([
                ['👤','Profils vérifiés','Tous les chauffeurs ont rempli leur profil complet.'],
                ['⚡','Réponse rapide','Recevez des candidatures en moins de 24h.'],
                ['🆓','100% gratuit','Publier une offre ne coûte rien.'],
                ['🛡️','En toute confiance','FLASH accompagne votre recrutement.'],
            ] as $a): ?>
            <div style="text-align:center;padding:24px;background:var(--gray-light);border-radius:var(--radius);">
                <div style="font-size:32px;margin-bottom:10px;"><?=$a[0]?></div>
                <div style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:6px;"><?=$a[1]?></div>
                <div style="font-size:13px;color:var(--text-light);line-height:1.5;"><?=$a[2]?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="content-with-sidebar">
            <div>
                <div class="form-card">
                    <div class="form-title">Publiez votre recherche de chauffeur</div>
                    <div class="form-sub">Décrivez votre besoin et les chauffeurs disponibles vous contacteront.</div>

                    <?php if ($success): ?>
                    <div class="alert alert-success">✅ Votre demande a été publiée ! Les chauffeurs vont vous contacter rapidement.</div>
                    <?php endif; ?>
                    <?php foreach ($errors as $err): ?>
                    <div class="alert alert-error">⚠️ <?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>

                    <form method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nom complet <span class="required">*</span></label>
                                <input type="text" name="full_name" required placeholder="Votre nom" value="<?=htmlspecialchars($_POST['full_name']??'')?>">
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
                                <label>Type de taxi <span class="required">*</span></label>
                                <select name="taxi_type" required>
                                    <option value="">Type de taxi</option>
                                    <option value="Taxi vert-blanc">Taxi vert-blanc</option>
                                    <option value="Taxi bleu-blanc">Taxi bleu-blanc</option>
                                    <option value="Taxi climatisé">Taxi climatisé</option>
                                    <option value="Taxi ordinaire">Taxi ordinaire</option>
                                    <option value="Autre">Autre</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Horaires souhaités</label>
                                <select name="desired_schedule">
                                    <option value="">Choisir les horaires</option>
                                    <option value="Temps plein">Temps plein</option>
                                    <option value="Temps partiel">Temps partiel</option>
                                    <option value="Journée uniquement">Journée uniquement</option>
                                    <option value="Soirée uniquement">Soirée uniquement</option>
                                    <option value="Flexible">Flexible</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Expérience demandée</label>
                                <select name="required_experience">
                                    <option value="">Niveau d'expérience</option>
                                    <option value="Débutant accepté">Débutant accepté</option>
                                    <option value="1 an minimum">1 an minimum</option>
                                    <option value="2 ans minimum">2 ans minimum</option>
                                    <option value="3 ans minimum">3 ans minimum</option>
                                    <option value="5 ans et plus">5 ans et plus</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Conditions de travail <span class="required">*</span></label>
                            <textarea name="working_conditions" required placeholder="Recette journalière : 15 000 FCFA. Carburant à la charge du chauffeur. Véhicule en bon état…"><?=htmlspecialchars($_POST['working_conditions']??'')?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Message complémentaire</label>
                            <textarea name="message" placeholder="Informations supplémentaires sur votre taxi ou vos attentes…"><?=htmlspecialchars($_POST['message']??'')?></textarea>
                        </div>
                        <button type="submit" class="btn btn-blue w-full btn-lg">Publier ma recherche →</button>
                    </form>
                </div>
            </div>

            <div>
                <div class="sidebar-card">
                    <div class="sidebar-card-title">💡 Conseils pour trouver le bon chauffeur</div>
                    <ul style="font-size:13px;color:var(--text-light);line-height:1.8;padding-left:16px;">
                        <li>Soyez précis sur la recette journalière</li>
                        <li>Mentionnez l'état réel du véhicule</li>
                        <li>Indiquez clairement les horaires</li>
                        <li>Précisez si le carburant est inclus</li>
                        <li>Répondez rapidement aux candidats</li>
                    </ul>
                </div>
                <div class="sidebar-card" style="background:var(--blue-light);border-color:var(--blue);">
                    <div style="font-size:20px;margin-bottom:8px;">📖</div>
                    <div class="sidebar-card-title">Lire nos conseils</div>
                    <p style="font-size:13px;color:var(--text-light);margin-bottom:14px;">Découvrez comment bien gérer votre taxi et choisir le bon chauffeur.</p>
                    <a href="/conseils" class="btn btn-blue btn-sm w-full">Lire les conseils</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
