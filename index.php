<?php
require_once __DIR__ . '/config.php';
if (isLoggedIn()) {
    $role = $_SESSION['user']['role_name'] ?? 'etudiant';
    redirect(in_array($role,['responsable','admin']) ? 'dashboard_responsable.php' : 'dashboard_etudiant.php');
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email === '' || $password === '') {
        $error = 'Merci de renseigner votre email et votre mot de passe.';
    } else {
        $stmt = $pdo->prepare('SELECT u.*, r.nom AS role_name FROM users u JOIN roles r ON u.role_id = r.id_role WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && hash('sha256', $password) === $user['password']) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user'] = ['id'=>$user['id_user'],'email'=>$user['email'],'role_name'=>$user['role_name'],'id_etudiant'=>$user['id_etudiant'],'nom'=>$user['nom'],'prenom'=>$user['prenom']];
            redirect(in_array($user['role_name'],['responsable','admin']) ? 'dashboard_responsable.php' : 'dashboard_etudiant.php');
        }
        $error = 'Email ou mot de passe incorrect.';
    }
}
define('APP_INIT', true);
$pageTitle = 'Accueil — StageTrack ESUP';
require_once __DIR__ . '/includes/header.php';
?>

<!-- HERO -->
<div class="hero-section">
  <div class="row align-items-center gy-5">
    <div class="col-lg-6">
      <span class="eyebrow">Bienvenue sur StageTrack</span>
      <h1 class="hero-title-lg">Trouve ton stage.<br>Simplement.</h1>
      <p class="hero-sub">Un espace clair pour gérer offres, conventions et suivi de recherche pour les étudiants et responsables.</p>
      <div class="hero-actions">
        <a href="#login" class="btn btn-primary btn-lg">Se connecter</a>
        <a href="register.php" class="btn btn-outline-primary btn-lg">Créer un compte</a>
      </div>
      <div class="row g-3">
        <div class="col-4">
          <div class="stat-card">
            <p class="label">Offres</p>
            <p class="big">84</p>
          </div>
        </div>
        <div class="col-4">
          <div class="stat-card">
            <p class="label">Étudiants</p>
            <p class="big">120</p>
          </div>
        </div>
        <div class="col-4">
          <div class="stat-card">
            <p class="label">Stages</p>
            <p class="big">47</p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-5 offset-lg-1">
      <div class="auth-card" id="login">
        <span class="eyebrow">Connexion</span>
        <h2 style="font-size:1.4rem;margin-bottom:20px">Accède à ton espace</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
          <div class="mb-3">
            <label class="form-label">Adresse e-mail</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="prenom.nom@univ.fr" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" class="form-control" name="password" placeholder="••••••••" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
        <hr style="border-color:var(--border);margin:20px 0">
        <p class="text-muted small text-center mb-2">Pas encore de compte ?</p>
        <a href="register.php" class="btn btn-outline-primary w-100">Créer un compte étudiant</a>
      </div>
    </div>
  </div>
</div>

<!-- FEATURES -->
<div class="row g-3" style="margin-top:40px">
  <div class="col-md-4">
    <div class="feat-card">
      <div class="feat-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 12h8M8 8h8M8 16h5"/></svg>
      </div>
      <h3>Offres récentes</h3>
      <p>Consulte les dernières propositions déposées par les entreprises partenaires.</p>
    </div>
  </div>
  <div class="col-md-4">
    <div class="feat-card">
      <div class="feat-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </div>
      <h3>Simple d'utilisation</h3>
      <p>Une interface claire pour les étudiants, responsables et administrateurs.</p>
    </div>
  </div>
  <div class="col-md-4">
    <div class="feat-card">
      <div class="feat-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      </div>
      <h3>Suivi complet</h3>
      <p>Gère les conventions de stage et le suivi des candidatures en un seul endroit.</p>
    </div>
  </div>
</div>

<!-- RECENT OFFERS + CONTACT -->
<div class="row g-3 mt-2">
  <div class="col-lg-8">
    <div class="content-card">
      <div class="card-header-bar">
        <h3>Offres récentes</h3>
        <a href="register.php" class="btn btn-sm btn-outline-primary">Voir tout</a>
      </div>
      <table class="data-table w-100">
        <thead><tr><th>Titre</th><th>Entreprise</th><th>Lieu</th><th>Statut</th></tr></thead>
        <tbody>
          <tr><td class="fw-600">Développeur web front-end</td><td>Brut.</td><td>Paris</td><td><span class="tag green">Disponible</span></td></tr>
          <tr><td class="fw-600">Stage marketing digital</td><td>TF1</td><td>Boulogne</td><td><span class="tag green">Disponible</span></td></tr>
          <tr><td class="fw-600">Data analyst junior</td><td>Canal+</td><td>Paris</td><td><span class="tag yellow">Bientôt</span></td></tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="contact-card" style="display:flex;flex-direction:column;gap:12px">
      <span class="eyebrow">Contact</span>
      <h3 style="font-size:1.15rem;margin:0">Une question ? On est là.</h3>
      <p class="text-muted" style="font-size:13.5px;margin:0">Ton responsable peut répondre à toutes tes questions sur les offres et la procédure de stage.</p>
      <div class="mt-auto">
        <input type="text" class="form-control mb-2" placeholder="Ton message...">
        <button class="btn btn-primary w-100">Envoyer un message</button>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
