<?php
require_once __DIR__ . '/config.php';
if (!isLoggedIn()) redirect('index.php');
if (!in_array($_SESSION['user']['role_name'] ?? '', ['responsable','admin'], true)) redirect('dashboard_etudiant.php');
define('APP_INIT', true);
$pageTitle = 'Gestion des offres';
require_once __DIR__ . '/includes/header.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre=trim($_POST['titre']??''); $desc=trim($_POST['description']??'');
    $idEnt=$_POST['id_entreprise']??null; $deb=$_POST['date_debut']??null; $fin=$_POST['date_fin']??null;
    $promo=trim($_POST['promotion']??''); $comp=trim($_POST['competences']??'');
    $lieu=trim($_POST['lieu']??''); $rem=trim($_POST['remuneration']??''); $an=trim($_POST['annee']??'');
    if ($titre===''||empty($idEnt)) { $error='Le titre et l\'entreprise sont obligatoires.'; }
    else {
        $pdo->prepare('INSERT INTO Offre_stage (titre,description,id_entreprise,status,competences,duree,lieu,date_debut,date_fin,remuneration,promotion,annee) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)')->execute([$titre,$desc,$idEnt,'Disponible',$comp,'',$lieu,$deb,$fin,$rem,$promo,$an]);
        redirect('offres.php');
    }
}
$entreprises = $pdo->query('SELECT id_entreprise,nom FROM Entreprise ORDER BY nom')->fetchAll();
$offres = $pdo->query('SELECT o.*,e.nom AS entreprise FROM Offre_stage o JOIN Entreprise e ON o.id_entreprise=e.id_entreprise ORDER BY o.id_offre DESC')->fetchAll();
$total=$count=count($offres); $dispos=count(array_filter($offres,fn($o)=>strtolower($o['status']??'')==='disponible'));
?>

<div class="page-header">
  <div class="top">
    <div>
      <span class="eyebrow">Responsable</span>
      <h1 class="page-title">Toutes les offres</h1>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalOffre">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-1"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Nouvelle offre
    </button>
  </div>
  <div class="stats-row">
    <div class="stat-pill"><span class="stat-pill-num"><?= $total ?></span> offres</div>
    <div class="stat-pill green"><span class="stat-pill-num"><?= $dispos ?></span> disponibles</div>
    <div class="stat-pill"><span class="stat-pill-num"><?= count($entreprises) ?></span> entreprises</div>
  </div>
</div>

<div class="content-card">
  <div class="card-toolbar">
    <input type="text" class="search-input" id="searchOffres" placeholder="Rechercher une offre ou entreprise...">
    <div class="toolbar-right">
      <span class="text-muted small"><?= $total ?> résultats</span>
    </div>
  </div>
  <div class="table-responsive">
    <table class="data-table" id="tableOffres">
      <thead>
        <tr><th>Titre</th><th>Entreprise</th><th>Lieu</th><th>Période</th><th>Promotion</th><th>Statut</th></tr>
      </thead>
      <tbody>
        <?php if (empty($offres)): ?>
          <tr><td colspan="6"><div class="empty-state">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            <p>Aucune offre enregistrée.</p>
          </div></td></tr>
        <?php else: foreach ($offres as $o):
          $s=strtolower($o['status']??''); $cls=$s==='disponible'?'green':($s==='pourvue'?'blue':''); ?>
          <tr>
            <td class="fw-600"><?= htmlspecialchars($o['titre']) ?></td>
            <td><?= htmlspecialchars($o['entreprise']) ?></td>
            <td><?= htmlspecialchars($o['lieu']?:'—') ?></td>
            <td class="text-muted small"><?= $o['date_debut']?htmlspecialchars($o['date_debut'].' → '.$o['date_fin']):'—' ?></td>
            <td><?= htmlspecialchars($o['promotion']?:'—') ?></td>
            <td><span class="tag <?= $cls ?>"><?= htmlspecialchars($o['status']?:'N/A') ?></span></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="modalOffre" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-head">
        <div>
          <span class="eyebrow" style="display:block;margin-bottom:4px">Responsable</span>
          <h5 class="title">Nouvelle offre de stage</h5>
        </div>
        <button class="btn-close-modal" data-bs-dismiss="modal">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div class="modal-body-inner">
        <?php if ($error): ?><div class="alert alert-danger mb-3"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
          <div class="form-grid">
            <div class="form-group full">
              <label class="form-label">Titre *</label>
              <input type="text" name="titre" class="form-control" placeholder="ex : Développeur web front-end" required>
            </div>
            <div class="form-group full">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Décrivez le poste..."></textarea>
            </div>
            <div class="form-group">
              <label class="form-label">Entreprise *</label>
              <select name="id_entreprise" class="form-select" required>
                <option value="">Choisir...</option>
                <?php foreach ($entreprises as $e): ?>
                  <option value="<?= $e['id_entreprise'] ?>"><?= htmlspecialchars($e['nom']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Lieu</label>
              <input type="text" name="lieu" class="form-control" placeholder="Paris, Lyon...">
            </div>
            <div class="form-group">
              <label class="form-label">Date début</label>
              <input type="date" name="date_debut" class="form-control">
            </div>
            <div class="form-group">
              <label class="form-label">Date fin</label>
              <input type="date" name="date_fin" class="form-control">
            </div>
            <div class="form-group">
              <label class="form-label">Compétences</label>
              <input type="text" name="competences" class="form-control" placeholder="React, PHP, SQL...">
            </div>
            <div class="form-group">
              <label class="form-label">Rémunération</label>
              <input type="text" name="remuneration" class="form-control" placeholder="ex : 600€/mois">
            </div>
            <div class="form-group">
              <label class="form-label">Promotion</label>
              <input type="text" name="promotion" class="form-control" placeholder="ex : L3 Info">
            </div>
            <div class="form-group">
              <label class="form-label">Année</label>
              <input type="text" name="annee" class="form-control" placeholder="2025-2026">
            </div>
          </div>
          <div class="modal-foot">
            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Créer l'offre</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('searchOffres').addEventListener('input',function(){
  const q=this.value.toLowerCase();
  document.querySelectorAll('#tableOffres tbody tr').forEach(tr=>tr.style.display=tr.textContent.toLowerCase().includes(q)?'':'none');
});
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
