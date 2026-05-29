<?php
require_once __DIR__ . '/includes/db.php';
$pdo = getDB();

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'profile_type' => trim($_POST['profile_type'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'message' => trim($_POST['message'] ?? ''),
    ];
    if (!$data['name']) $errors[] = 'Le nom est obligatoire.';
    if (!$data['phone']) $errors[] = 'Le téléphone est obligatoire.';
    if (!$data['profile_type']) $errors[] = 'Veuillez indiquer votre profil.';
    if (!$data['city']) $errors[] = 'La ville est obligatoire.';
    if (!$data['message']) $errors[] = 'Le message est obligatoire.';

    if (empty($errors) && $pdo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contacts (name,phone,email,profile_type,city,message) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$data['name'],$data['phone'],$data['email'],$data['profile_type'],$data['city'],$data['message']]);
            $success = true;
        } catch(Exception $e){ $errors[] = 'Erreur d\'envoi. Réessayez ou contactez-nous sur WhatsApp.'; }
    } elseif (empty($errors)) {
        $success = true;
    }
}

$pageTitle = 'Contact';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container" style="text-align:center;">
        <h1 class="page-hero-title">Contactez FLASH</h1>
        <p class="page-hero-sub">Une question ? Un problème ? Notre équipe vous répond rapidement.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact-grid">
            <!-- Info -->
            <div class="contact-info">
                <h3>Parlons de votre projet</h3>
                <p>Que vous soyez propriétaire ou chauffeur, nous sommes là pour vous aider à réussir dans le business taxi au Congo.</p>

                <div class="contact-item">
                    <div class="contact-item-icon">📱</div>
                    <div class="contact-item-text">
                        <strong>WhatsApp</strong>
                        <span>+242 00 000 0000</span>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon">📧</div>
                    <div class="contact-item-text">
                        <strong>Email</strong>
                        <span>contact@flash-taxi.cg</span>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon">📍</div>
                    <div class="contact-item-text">
                        <strong>Présence</strong>
                        <span>Brazzaville · Pointe-Noire · Dolisie · Nkayi</span>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon">⏰</div>
                    <div class="contact-item-text">
                        <strong>Disponibilité</strong>
                        <span>Lundi – Samedi, 8h – 18h</span>
                    </div>
                </div>

                <a href="https://wa.me/242000000000?text=Bonjour%20FLASH" target="_blank" class="btn btn-blue btn-lg" style="margin-top:24px;background:#25D366;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="white" style="margin-right:4px;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Nous écrire sur WhatsApp
                </a>
            </div>

            <!-- Form -->
            <div class="form-card">
                <div class="form-title">Envoyer un message</div>

                <?php if ($success): ?>
                <div class="alert alert-success">✅ Votre message a été envoyé ! Nous vous répondrons rapidement.</div>
                <?php endif; ?>
                <?php foreach ($errors as $err): ?>
                <div class="alert alert-error">⚠️ <?= htmlspecialchars($err) ?></div>
                <?php endforeach; ?>

                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nom <span class="required">*</span></label>
                            <input type="text" name="name" required placeholder="Votre nom" value="<?=htmlspecialchars($_POST['name']??'')?>">
                        </div>
                        <div class="form-group">
                            <label>Téléphone <span class="required">*</span></label>
                            <input type="tel" name="phone" required placeholder="+242 06 000 0000" value="<?=htmlspecialchars($_POST['phone']??'')?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="votre@email.com" value="<?=htmlspecialchars($_POST['email']??'')?>">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Vous êtes <span class="required">*</span></label>
                            <select name="profile_type" required>
                                <option value="">Votre profil</option>
                                <option value="chauffeur" <?=($_POST['profile_type']??'')=='chauffeur'?'selected':''?>>Chauffeur</option>
                                <option value="proprietaire" <?=($_POST['profile_type']??'')=='proprietaire'?'selected':''?>>Propriétaire de taxi</option>
                                <option value="autre" <?=($_POST['profile_type']??'')=='autre'?'selected':''?>>Autre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Ville <span class="required">*</span></label>
                            <select name="city" required>
                                <option value="">Choisir</option>
                                <?php foreach(['Brazzaville','Pointe-Noire','Dolisie','Nkayi','Autre'] as $v): ?>
                                <option value="<?=$v?>" <?=($_POST['city']??'')==$v?'selected':''?>><?=$v?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Message <span class="required">*</span></label>
                        <textarea name="message" required placeholder="Décrivez votre demande…" style="min-height:120px;"><?=htmlspecialchars($_POST['message']??'')?></textarea>
                    </div>
                    <button type="submit" class="btn btn-blue w-full btn-lg">Envoyer le message →</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
