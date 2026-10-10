<?php
require_once __DIR__ . '/config.php';
if (!isLoggedIn()) redirect('index.php');
define('APP_INIT', true);
$pageTitle = 'Entreprises partenaires';
require_once __DIR__ . '/includes/header.php';

$entreprises = $pdo->query('SELECT e.*, COUNT(o.id_offre) AS nb_offres FROM Entreprise e LEFT JOIN Offre_stage o ON e.id_entreprise = o.id_entreprise GROUP BY e.id_entreprise ORDER BY e.nom')->fetchAll();
?>

<div class="page-header">
  <div class="top">
    <div>
      <span class="eyebrow">Annuaire</span>
      <h1 class="page-title">Entreprises partenaires</h1>
    </div>
  </div>
  <div class="stats-row">
    <div class="stat-pill"><span class="stat-pill-num"><?= count($entreprises) ?></span> entreprises</div>
    <div class="stat-pill blue"><span class="stat-pill-num"><?= $pdo->query('SELECT COUNT(*) FROM Offre_stage WHERE status="Disponible"')->fetchColumn() ?></span> offres actives</div>
  </div>
</div>

<!-- COMPANY GRID -->
<div class="company-grid mb-4">
  <?php
  // Couleurs de fond par marque
  $brandColors = [
    'YouTube'   => ['bg'=>'#ff0000','text'=>'#fff'],
    'Webedia'   => ['bg'=>'#1a1a2e','text'=>'#fff'],
    'Brut.'     => ['bg'=>'#000','text'=>'#fff'],
    'TF1'       => ['bg'=>'#003399','text'=>'#fff'],
    'Canal+'    => ['bg'=>'#000','text'=>'#fff'],
    'Ubisoft'   => ['bg'=>'#0f0f2d','text'=>'#fff'],
    'Sony Music'=> ['bg'=>'#111','text'=>'#fff'],
    'Twitch'    => ['bg'=>'#9146ff','text'=>'#fff'],
    'Cartoon Network'=> ['bg'=>'#000','text'=>'#fff'],
    'Alizé Tech'=> ['bg'=>'#3b44f5','text'=>'#fff'],
  ];
  foreach ($entreprises as $e):
    $c = $brandColors[$e['nom']] ?? ['bg'=>'var(--neutral)','text'=>'var(--muted)'];
  ?>
  <div class="company-tile" style="background:<?= $c['bg'] ?>;color:<?= $c['text'] ?>;border-color:transparent">
    <div style="text-align:center">
      <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:800;letter-spacing:-.02em;line-height:1.2">
        <?= htmlspecialchars($e['nom']) ?>
      </div>
      <?php if ($e['nb_offres'] > 0): ?>
        <div style="font-size:11px;margin-top:6px;opacity:.7;font-weight:500"><?= $e['nb_offres'] ?> offre<?= $e['nb_offres']>1?'s':'' ?></div>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- TABLE DÉTAIL -->
<div class="content-card">
  <div class="card-header-bar">
    <h3>Détail des entreprises</h3>
    <input type="text" class="search-input" id="searchEnt" placeholder="Rechercher..." style="max-width:260px">
  </div>
  <div class="table-responsive">
    <table class="data-table" id="tableEnt">
      <thead>
        <tr><th>Entreprise</th><th>Adresse</th><th>Contact</th><th>Téléphone</th><th>Offres</th></tr>
      </thead>
      <tbody>
        <?php foreach ($entreprises as $e): ?>
          <tr>
            <td class="fw-600"><?= htmlspecialchars($e['nom']) ?></td>
            <td class="text-muted"><?= htmlspecialchars($e['adresse'] ?: '—') ?></td>
            <td><?= htmlspecialchars($e['email'] ?: '—') ?></td>
            <td class="text-muted"><?= htmlspecialchars($e['telephone'] ?: '—') ?></td>
            <td>
              <?php if ($e['nb_offres'] > 0): ?>
                <span class="tag green"><?= $e['nb_offres'] ?> offre<?= $e['nb_offres']>1?'s':'' ?></span>
              <?php else: ?>
                <span class="tag">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
document.getElementById('searchEnt').addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('#tableEnt tbody tr').forEach(tr => {
    tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
