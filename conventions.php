<?php
require_once __DIR__ . '/config.php';
if (!isLoggedIn()) redirect('index.php');
if (!in_array($_SESSION['user']['role_name']??'',['responsable','admin'],true)) redirect('dashboard_etudiant.php');
define('APP_INIT',true); $pageTitle='Conventions';
require_once __DIR__ . '/includes/header.php';
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $idEtu=$_POST['id_etudiant']??null; $idEnt=$_POST['id_entreprise']??null;
    $idM=$_POST['id_maitre']??null; $idO=$_POST['id_offre']??null;
    $deb=$_POST['date_debut']??null; $fin=$_POST['date_fin']??null;
    $num=trim($_POST['numero_convention']??''); $st=trim($_POST['status']??'En validation');
    if (!$idEtu||!$idEnt||!$idM||!$deb||!$fin) { $error='Les champs obligatoires doivent être remplis.'; }
    else {
        $pdo->prepare('INSERT INTO Convention (date_debut,date_fin,status,id_etudiant,id_entreprise,id_maitre,id_offre,numero_convention) VALUES (?,?,?,?,?,?,?,?)')->execute([$deb,$fin,$st,$idEtu,$idEnt,$idM,$idO?:null,$num]);
        redirect('conventions.php');
    }
}
$etudiants=$pdo->query('SELECT id_etudiant,nom,prenom FROM Etudiant ORDER BY nom')->fetchAll();
$entreprises=$pdo->query('SELECT id_entreprise,nom FROM Entreprise ORDER BY nom')->fetchAll();
$maitres=$pdo->query('SELECT id_maitre,nom,prenom FROM Maitre_de_stage ORDER BY nom')->fetchAll();
$offres=$pdo->query('SELECT id_offre,titre FROM Offre_stage ORDER BY titre')->fetchAll();
$conventions=$pdo->query('SELECT c.*,e.nom AS entreprise,m.nom AS maitre_nom,m.prenom AS maitre_prenom,o.titre AS offre_titre,t.nom AS etu_nom,t.prenom AS etu_prenom FROM Convention c JOIN Etudiant t ON c.id_etudiant=t.id_etudiant JOIN Entreprise e ON c.id_entreprise=e.id_entreprise JOIN Maitre_de_stage m ON c.id_maitre=m.id_maitre LEFT JOIN Offre_stage o ON c.id_offre=o.id_offre ORDER BY c.id_convention DESC')->fetchAll();
?>

<div class="page-header">
  <div class="top">
    <div>
      <span class="eyebrow">Responsable</span>
      <h1 class="page-title">Conventions de stage</h1>
    </div>
    <div class="d-flex gap-2">
      <a href="dashboard_responsable.php" class="btn btn-outline-primary">Retour</a>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalConv">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-1"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nouvelle convention
      </button>
    </div>
  </div>
  <div class="stats-row">
    <div class="stat-pill"><span class="stat-pill-num"><?= count($conventions) ?></span> conventions</div>
  </div>
</div>

<div class="content-card">
  <div class="table-responsive">
    <table class="data-table">
      <thead><tr><th>Étudiant</th><th>Entreprise</th><th>Maître</th><th>Offre</th><th>Période</th><th>Statut</th></tr></thead>
      <tbody>
        <?php if(empty($conventions)): ?>
          <tr><td colspan="6"><div class="empty-state"><p>Aucune convention enregistrée.</p></div></td></tr>
        <?php else: foreach($conventions as $c):
          $s=strtolower($c['status']??''); $cls=str_contains($s,'valid')?'green':(str_contains($s,'refus')?'red':'blue'); ?>
          <tr>
            <td class="fw-600"><?= htmlspecialchars($c['etu_prenom'].' '.$c['etu_nom']) ?></td>
            <td><?= htmlspecialchars($c['entreprise']) ?></td>
            <td><?= htmlspecialchars($c['maitre_prenom'].' '.$c['maitre_nom']) ?></td>
            <td class="text-muted"><?= htmlspecialchars($c['offre_titre']?:'—') ?></td>
            <td class="text-muted small"><?= htmlspecialchars($c['date_debut'].' → '.$c['date_fin']) ?></td>
            <td><span class="tag <?= $cls ?>"><?= htmlspecialchars($c['status']) ?></span></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="modalConv" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-head">
        <div>
          <span class="eyebrow" style="display:block;margin-bottom:4px">Responsable</span>
          <h5 class="title">Nouvelle convention</h5>
        </div>
        <button class="btn-close-modal" data-bs-dismiss="modal">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div class="modal-body-inner">
        <?php if($error): ?><div class="alert alert-danger mb-3"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Étudiant *</label>
              <select name="id_etudiant" class="form-select" required>
                <option value="">Choisir...</option>
                <?php foreach($etudiants as $e): ?><option value="<?= $e['id_etudiant'] ?>"><?= htmlspecialchars($e['nom'].' '.$e['prenom']) ?></option><?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Entreprise *</label>
              <select name="id_entreprise" class="form-select" required>
                <option value="">Choisir...</option>
                <?php foreach($entreprises as $e): ?><option value="<?= $e['id_entreprise'] ?>"><?= htmlspecialchars($e['nom']) ?></option><?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Maître de stage *</label>
              <select name="id_maitre" class="form-select" required>
                <option value="">Choisir...</option>
                <?php foreach($maitres as $m): ?><option value="<?= $m['id_maitre'] ?>"><?= htmlspecialchars($m['prenom'].' '.$m['nom']) ?></option><?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Offre liée</label>
              <select name="id_offre" class="form-select">
                <option value="">Aucune</option>
                <?php foreach($offres as $o): ?><option value="<?= $o['id_offre'] ?>"><?= htmlspecialchars($o['titre']) ?></option><?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Date début *</label>
              <input type="date" name="date_debut" class="form-control" required>
            </div>
            <div class="form-group">
              <label class="form-label">Date fin *</label>
              <input type="date" name="date_fin" class="form-control" required>
            </div>
            <div class="form-group">
              <label class="form-label">N° convention</label>
              <input type="text" name="numero_convention" class="form-control" placeholder="ex : CONV-2026-001">
            </div>
            <div class="form-group">
              <label class="form-label">Statut</label>
              <input type="text" name="status" class="form-control" value="En validation">
            </div>
          </div>
          <div class="modal-foot">
            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Créer la convention</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
