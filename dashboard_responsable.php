<?php
require_once __DIR__ . '/config.php';
if (!isLoggedIn()) redirect('index.php');
if (!in_array($_SESSION['user']['role_name'] ?? '', ['responsable','admin'], true)) redirect('dashboard_etudiant.php');
define('APP_INIT', true);
$pageTitle = 'Tableau de bord — Responsable';
require_once __DIR__ . '/includes/header.php';
$stats = $pdo->query('SELECT SUM(CASE WHEN c.id_convention IS NOT NULL THEN 1 ELSE 0 END) AS assignes, SUM(CASE WHEN c.id_convention IS NULL THEN 1 ELSE 0 END) AS sans_stage FROM Etudiant e LEFT JOIN Convention c ON e.id_etudiant=c.id_etudiant')->fetch();
$offersCount = $pdo->query('SELECT COUNT(*) FROM Offre_stage')->fetchColumn();
$students = $pdo->query('SELECT e.nom,e.prenom,e.promotion,c.id_convention FROM Etudiant e LEFT JOIN Convention c ON e.id_etudiant=c.id_etudiant ORDER BY e.nom')->fetchAll();
?>

<div class="hero-banner">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-4">
    <div>
      <span class="eyebrow">Espace responsable</span>
      <h1 class="page-title">Tableau de bord</h1>
      <p class="sub">Gérez les offres de stage, les conventions et suivez facilement l'état des étudiants.</p>
    </div>
    <div class="d-flex flex-column flex-sm-row gap-2 flex-shrink-0">
      <a href="offres.php" class="btn btn-primary">Gérer les offres</a>
      <a href="conventions.php" class="btn btn-outline-primary">Conventions</a>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-sm-4">
    <div class="stat-card">
      <p class="label">Sans stage</p>
      <p class="big"><?= $stats['sans_stage'] ?? 0 ?></p>
      <p class="sub">Étudiants</p>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="stat-card">
      <p class="label">Stages affectés</p>
      <p class="big"><?= $stats['assignes'] ?? 0 ?></p>
      <p class="sub">Conventions actives</p>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="stat-card">
      <p class="label">Offres publiées</p>
      <p class="big"><?= $offersCount ?></p>
      <p class="sub">Au total</p>
    </div>
  </div>
</div>

<div class="content-card">
  <div class="card-header-bar">
    <h3>Liste des étudiants</h3>
    <span class="tag"><?= count($students) ?> étudiants</span>
  </div>
  <div class="card-toolbar">
    <input type="text" class="search-input" id="searchStudents" placeholder="Rechercher un étudiant...">
  </div>
  <div class="table-responsive">
    <table class="data-table" id="tableStudents">
      <thead>
        <tr><th>Nom</th><th>Prénom</th><th>Promotion</th><th>Stage</th></tr>
      </thead>
      <tbody>
        <?php foreach ($students as $s): ?>
          <tr>
            <td class="fw-600"><?= htmlspecialchars($s['nom']) ?></td>
            <td><?= htmlspecialchars($s['prenom']) ?></td>
            <td><span class="tag"><?= htmlspecialchars($s['promotion'] ?: '—') ?></span></td>
            <td><?php if ($s['id_convention']): ?>
              <span class="tag green">Affecté</span>
            <?php else: ?>
              <span class="tag yellow">Libre</span>
            <?php endif; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
document.getElementById('searchStudents').addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('#tableStudents tbody tr').forEach(tr => {
    tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
