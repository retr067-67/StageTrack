<?php
require_once __DIR__ . '/config.php';
if (isLoggedIn()) redirect('dashboard_etudiant.php');
$error=''; $success='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $nom=trim($_POST['nom']??''); $prenom=trim($_POST['prenom']??'');
    $email=trim($_POST['email']??''); $promo=trim($_POST['promotion']??'');
    $mat=trim($_POST['matricule']??''); $pwd=$_POST['password']??''; $conf=$_POST['confirm_password']??'';
    if (!$nom||!$prenom||!$email||!$pwd||!$conf) { $error='Merci de remplir tous les champs obligatoires.'; }
    elseif (!filter_var($email,FILTER_VALIDATE_EMAIL)) { $error='Adresse e-mail invalide.'; }
    elseif ($pwd!==$conf) { $error='Les mots de passe ne correspondent pas.'; }
    else {
        $st=$pdo->prepare('SELECT COUNT(*) FROM users WHERE email=?'); $st->execute([$email]);
        if ($st->fetchColumn()>0) { $error='Cette adresse e-mail est déjà utilisée.'; }
        else {
            $pdo->beginTransaction();
            try {
                $pdo->prepare('INSERT INTO Etudiant (nom,prenom,email,promotion,matricule) VALUES (?,?,?,?,?)')->execute([$nom,$prenom,$email,$promo,$mat]);
                $idEtu=$pdo->lastInsertId();
                $rs=$pdo->prepare('SELECT id_role FROM roles WHERE nom=?'); $rs->execute(['etudiant']); $rid=$rs->fetchColumn();
                $pdo->prepare('INSERT INTO users (email,password,role_id,id_etudiant,nom,prenom) VALUES (?,?,?,?,?,?)')->execute([$email,hash('sha256',$pwd),$rid,$idEtu,$nom,$prenom]);
                $pdo->commit(); $success='Votre compte a été créé. Vous pouvez maintenant vous connecter.';
            } catch(Exception $ex){ $pdo->rollBack(); $error='Erreur : '.$ex->getMessage(); }
        }
    }
}
define('APP_INIT',true); $pageTitle='Inscription — StageTrack ESUP';
require_once __DIR__ . '/includes/header.php';
?>

<div class="row align-items-center gy-5" style="min-height:70vh">
  <div class="col-lg-5">
    <span class="eyebrow">Inscription étudiant</span>
    <h1 class="hero-title-lg" style="font-size:clamp(1.8rem,3vw,2.6rem)">Rejoins StageTrack et démarre ta recherche.</h1>
    <p class="hero-sub">Crée ton espace en quelques clics pour suivre tes offres, ta convention et ton parcours de stage.</p>
    <div class="feat-card mt-4">
      <div class="feat-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
      <h3>Simple et rapide</h3>
      <p>Ton compte te donne accès à un tableau de bord clair, un suivi de stage et un espace offres.</p>
    </div>
  </div>
  <div class="col-lg-6 offset-lg-1">
    <div class="auth-card">
      <span class="eyebrow">Inscription</span>
      <h2 style="font-size:1.3rem;margin-bottom:20px">Commence dès maintenant</h2>
      <?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
      <form method="post">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nom *</label>
            <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($_POST['nom']??'') ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Prénom *</label>
            <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($_POST['prenom']??'') ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Adresse e-mail *</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($_POST['email']??'') ?>" placeholder="prenom.nom@univ.fr" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Promotion</label>
            <input type="text" class="form-control" name="promotion" value="<?= htmlspecialchars($_POST['promotion']??'') ?>" placeholder="ex : L3 Info">
          </div>
          <div class="col-md-6">
            <label class="form-label">Matricule</label>
            <input type="text" class="form-control" name="matricule" value="<?= htmlspecialchars($_POST['matricule']??'') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Mot de passe *</label>
            <input type="password" class="form-control" name="password" placeholder="••••••••" required>
          </div>
          <div class="col-12">
            <label class="form-label">Confirmer le mot de passe *</label>
            <input type="password" class="form-control" name="confirm_password" placeholder="••••••••" required>
          </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-4">Créer mon compte</button>
        <p class="text-center text-muted small mt-3 mb-0">Déjà un compte ? <a href="index.php">Se connecter</a></p>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
