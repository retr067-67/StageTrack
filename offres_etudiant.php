<?php
require_once __DIR__ . '/config.php';
if (!isLoggedIn()) redirect('index.php');
if (($_SESSION['user']['role_name'] ?? '') !== 'etudiant') redirect('dashboard_responsable.php');
define('APP_INIT', true);
$pageTitle = 'Offres de stage';
require_once __DIR__ . '/includes/header.php';
$user = $_SESSION['user'];
$etudiantId = $user['id_etudiant'];
$offres = $pdo->query('SELECT o.*,e.nom AS entreprise FROM Offre_stage o JOIN Entreprise e ON o.id_entreprise=e.id_entreprise ORDER BY o.date_debut DESC')->fetchAll();
$recherches = $pdo->prepare('SELECT * FROM Recherche_stage WHERE id_etudiant=? ORDER BY date_contact DESC');
$recherches->execute([$etudiantId]);
$recherches = $recherches->fetchAll();
?>

<div class="hero-banner">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-4">
    <div>
      <span class="eyebrow">Offres de stage</span>
      <h1 class="page-title">Explore les opportunités.</h1>
      <p class="sub">Consulte les offres disponibles, repère celles qui t'intéressent et partage-les avec ton encadrant.</p>
    </div>
    <a href="dashboard_etudiant.php" class="btn btn-outline-primary flex-shrink-0">Mon tableau de bord</a>
  </div>
</div>

<div class="stats-row mb-4">
  <div class="stat-pill"><span class="stat-pill-num"><?= count($offres) ?></span> offres disponibles</div>
</div>

<?php if (empty($offres)): ?>
  <div class="content-card">
    <div class="empty-state">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M8 14h8M8 18h5"/></svg>
      <p>Aucune offre disponible pour le moment.</p>
    </div>
  </div>
<?php else: ?>
  <div class="row g-3 mb-4">
    <?php foreach ($offres as $o): ?>
      <div class="col-md-6 col-lg-4">
        <div class="offer-card">
          <div class="d-flex justify-content-between align-items-start gap-2">
            <div>
              <h4><?= htmlspecialchars($o['titre']) ?></h4>
              <p class="co"><?= htmlspecialchars($o['entreprise']) ?></p>
            </div>
            <?php $s=strtolower($o['status']??''); $cls=$s==='disponible'?'green':($s==='pourvue'?'blue':''); ?>
            <span class="tag <?= $cls ?>" style="flex-shrink:0"><?= htmlspecialchars($o['status']??'N/A') ?></span>
          </div>
          <p class="desc"><?= htmlspecialchars(mb_strimwidth($o['description']?:'Description non fournie.',0,100,'…')) ?></p>
          <div class="meta">
            <span><?= htmlspecialchars($o['lieu']?:'Lieu non précisé') ?></span>
            <?php if ($o['date_debut']): ?>
              <span><?= htmlspecialchars($o['date_debut'].' → '.$o['date_fin']) ?></span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<div class="content-card">
  <div class="card-header-bar">
    <div>
      <span class="eyebrow" style="display:block;margin-bottom:2px">Suivi de recherche</span>
      <h3>Historique des démarches</h3>
    </div>
    <a href="suivi_recherche.php" class="btn btn-sm btn-primary">Ajouter</a>
  </div>
  <?php if (empty($recherches)): ?>
    <div class="empty-state">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
      <p>Aucun suivi de recherche pour le moment.</p>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="data-table">
        <thead><tr><th>Date</th><th>Entreprise</th><th>Offre</th><th>Statut</th><th>Commentaire</th></tr></thead>
        <tbody>
          <?php foreach ($recherches as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['date_contact']) ?></td>
              <td class="fw-600"><?= htmlspecialchars($r['entreprise_contactee']) ?></td>
              <td><?= htmlspecialchars($r['offre_consultee']) ?></td>
              <td><span class="tag"><?= htmlspecialchars($r['statut']) ?></span></td>
              <td class="text-muted"><?= htmlspecialchars($r['commentaire']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
