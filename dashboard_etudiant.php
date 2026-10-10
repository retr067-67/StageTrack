<?php
require_once __DIR__ . '/config.php';
if (!isLoggedIn()) redirect('index.php');
if (($_SESSION['user']['role_name'] ?? '') !== 'etudiant') redirect('dashboard_responsable.php');
define('APP_INIT', true);
$pageTitle = 'Tableau de bord — Étudiant';
require_once __DIR__ . '/includes/header.php';
$user = $_SESSION['user'];
$st = $pdo->prepare('SELECT * FROM Etudiant WHERE id_etudiant = ?');
$st->execute([$user['id_etudiant']]);
$etudiant = $st->fetch();
$conv = null;
if ($etudiant) {
    $q = $pdo->prepare('SELECT c.*,e.nom AS entreprise,m.nom AS maitre_nom,m.prenom AS maitre_prenom,o.titre AS offre_titre FROM Convention c JOIN Entreprise e ON c.id_entreprise=e.id_entreprise JOIN Maitre_de_stage m ON c.id_maitre=m.id_maitre LEFT JOIN Offre_stage o ON c.id_offre=o.id_offre WHERE c.id_etudiant=? ORDER BY c.id_convention DESC LIMIT 1');
    $q->execute([$etudiant['id_etudiant']]);
    $conv = $q->fetch();
}
$hasConv = !empty($conv);
$totalOffres = $pdo->query('SELECT COUNT(*) FROM Offre_stage')->fetchColumn();
?>

<!-- HERO BANNER -->
<div class="hero-banner">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-4">
    <div>
      <span class="eyebrow">Espace étudiant</span>
      <h1 class="page-title">Bonjour, <?= htmlspecialchars($etudiant['prenom'] ?? $user['prenom']) ?> 👋</h1>
      <p class="sub">Suis ta convention, consulte les offres et garde le cap sur ta recherche de stage.</p>
    </div>
    <a href="offres_etudiant.php" class="btn btn-primary btn-lg flex-shrink-0">Voir les offres</a>
  </div>
</div>

<!-- STATS -->
<div class="row g-3 mb-4">
  <div class="col-sm-4">
    <div class="stat-card">
      <p class="label">Convention</p>
      <p class="big"><?= $hasConv ? '1' : '0' ?></p>
      <p class="sub"><?= $hasConv ? 'Active' : 'Aucune' ?></p>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="stat-card">
      <p class="label">Offres disponibles</p>
      <p class="big"><?= $totalOffres ?></p>
      <p class="sub">À explorer</p>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="stat-card">
      <p class="label">Statut</p>
      <p class="big" style="font-size:1.1rem;margin-top:4px"><?= $hasConv ? htmlspecialchars($conv['status']) : 'En attente' ?></p>
      <p class="sub">Convention</p>
    </div>
  </div>
</div>

<!-- CONVENTION DETAIL -->
<div class="content-card">
  <div class="card-header-bar">
    <div>
      <span class="eyebrow" style="display:block;margin-bottom:2px">Convention de stage</span>
      <h3><?= $hasConv ? htmlspecialchars($conv['offre_titre'] ?? 'Stage personnel') : 'Aucune convention' ?></h3>
    </div>
    <?php if ($hasConv): ?>
      <span class="tag <?= strtolower($conv['status'])=='validée'||strtolower($conv['status'])=='validee'?'green':'blue' ?>"><?= htmlspecialchars($conv['status']) ?></span>
    <?php endif; ?>
  </div>
  <div class="card-body-p">
    <?php if ($hasConv): ?>
      <div class="detail-grid">
        <div class="detail-item"><p class="key">Entreprise</p><p class="val"><?= htmlspecialchars($conv['entreprise']) ?></p></div>
        <div class="detail-item"><p class="key">Maître de stage</p><p class="val"><?= htmlspecialchars($conv['maitre_prenom'].' '.$conv['maitre_nom']) ?></p></div>
        <div class="detail-item"><p class="key">Date de début</p><p class="val"><?= htmlspecialchars($conv['date_debut']) ?></p></div>
        <div class="detail-item"><p class="key">Date de fin</p><p class="val"><?= htmlspecialchars($conv['date_fin']) ?></p></div>
        <div class="detail-item"><p class="key">N° convention</p><p class="val"><?= htmlspecialchars($conv['numero_convention'] ?: '—') ?></p></div>
        <div class="detail-item"><p class="key">Promotion</p><p class="val"><?= htmlspecialchars($etudiant['promotion'] ?: '—') ?></p></div>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        <p>Aucune convention enregistrée pour le moment.<br>Contacte ton responsable pour en créer une.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
