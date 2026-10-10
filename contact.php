<?php
require_once __DIR__ . '/config.php';
define('APP_INIT', true);
$pageTitle = 'Contact';
$sent = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom   = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $sujet = trim($_POST['sujet'] ?? '');
    $msg   = trim($_POST['message'] ?? '');
    if (!$nom || !$email || !$msg) {
        $error = 'Merci de remplir tous les champs obligatoires.';
    } else {
        // Ici vous pourriez envoyer un email avec mail()
        $sent = true;
    }
}
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="top">
    <div>
      <span class="eyebrow">Aide & Support</span>
      <h1 class="page-title">Une question ? On est là.</h1>
    </div>
  </div>
</div>

<div class="row g-4">

  <!-- FORM -->
  <div class="col-lg-7">
    <div class="content-card">
      <div class="card-header-bar">
        <h3>Envoyer un message</h3>
      </div>
      <div class="card-body-p">
        <?php if ($sent): ?>
          <div class="alert alert-success">
            <strong>Message envoyé !</strong> Votre responsable vous répondra dans les plus brefs délais.
          </div>
        <?php endif; ?>
        <?php if ($error): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nom *</label>
              <input type="text" name="nom" class="form-control" placeholder="Votre nom" value="<?= htmlspecialchars($_POST['nom'] ?? (isLoggedIn() ? ($_SESSION['user']['nom'].' '.$_SESSION['user']['prenom']) : '')) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Adresse e-mail *</label>
              <input type="email" name="email" class="form-control" placeholder="vous@exemple.fr" value="<?= htmlspecialchars($_POST['email'] ?? (isLoggedIn() ? $_SESSION['user']['email'] : '')) ?>" required>
            </div>
            <div class="col-12">
              <label class="form-label">Sujet</label>
              <select name="sujet" class="form-select">
                <option value="">Choisir un sujet...</option>
                <option value="offre">Question sur une offre</option>
                <option value="convention">Convention de stage</option>
                <option value="compte">Problème de connexion / compte</option>
                <option value="autre">Autre</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Message *</label>
              <textarea name="message" class="form-control" rows="5" placeholder="Décrivez votre question ou problème..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
            </div>
          </div>
          <div class="mt-4">
            <button type="submit" class="btn btn-primary">Envoyer le message</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- INFOS SIDEBAR -->
  <div class="col-lg-5 d-flex flex-column gap-3">

    <!-- Responsable card -->
    <div class="content-card">
      <div class="card-body-p">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div style="width:44px;height:44px;border-radius:12px;background:var(--primary-s);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </div>
          <div>
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--muted-2);margin:0">Responsable stages</p>
            <p style="font-weight:700;margin:2px 0 0;font-size:15px">Stage Responsable</p>
          </div>
        </div>
        <div class="d-flex flex-column gap-2">
          <div class="d-flex align-items-center gap-2" style="font-size:13.5px;color:var(--muted)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            responsable@example.com
          </div>
          <div class="d-flex align-items-center gap-2" style="font-size:13.5px;color:var(--muted)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.19 3.4 2 2 0 0 1 3.16 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16z"/></svg>
            01 23 45 67 89
          </div>
        </div>
      </div>
    </div>

    <!-- Contacts rapides -->
    <div class="content-card">
      <div class="card-header-bar"><h3>Contacts rapides</h3></div>
      <div>
        <?php
        $contacts = [
          ['nom'=>'Abdou Diallo',  'role'=>'Coordinateur MMI', 'email'=>'a.diallo@univ.fr'],
          ['nom'=>'Laure Martin',  'role'=>'Secrétariat',      'email'=>'secretariat@univ.fr'],
          ['nom'=>'Paul Nguyen',   'role'=>'Référent numérique','email'=>'p.nguyen@univ.fr'],
        ];
        foreach ($contacts as $c): ?>
          <div class="d-flex align-items-center justify-content-between gap-3 px-3 py-3" style="border-bottom:1px solid var(--border-s)">
            <div class="d-flex align-items-center gap-3">
              <div style="width:34px;height:34px;border-radius:99px;background:var(--neutral);display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--muted);flex-shrink:0">
                <?= strtoupper(substr($c['nom'],0,1)) ?>
              </div>
              <div>
                <p style="font-weight:600;font-size:13.5px;margin:0"><?= htmlspecialchars($c['nom']) ?></p>
                <p style="font-size:12px;color:var(--muted-2);margin:0"><?= htmlspecialchars($c['role']) ?></p>
              </div>
            </div>
            <a href="mailto:<?= htmlspecialchars($c['email']) ?>" class="btn btn-sm btn-outline-primary">Écrire</a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Horaires -->
    <div class="content-card">
      <div class="card-body-p">
        <span class="eyebrow" style="display:block;margin-bottom:8px">Disponibilités</span>
        <div class="d-flex flex-column gap-2">
          <div class="d-flex justify-content-between" style="font-size:13.5px">
            <span class="text-muted">Lundi – Vendredi</span><span class="fw-600">9h – 17h</span>
          </div>
          <div class="d-flex justify-content-between" style="font-size:13.5px">
            <span class="text-muted">Samedi – Dimanche</span><span class="text-muted">Fermé</span>
          </div>
        </div>
        <div style="margin-top:14px;padding:10px 14px;background:var(--success-s);border-radius:var(--r);font-size:13px;color:var(--success);font-weight:500">
          <span style="display:inline-block;width:7px;height:7px;border-radius:99px;background:var(--success);margin-right:7px;vertical-align:middle"></span>
          Disponible aujourd'hui
        </div>
      </div>
    </div>

  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
