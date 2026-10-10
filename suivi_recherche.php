<?php
require_once __DIR__ . '/config.php';
if (!isLoggedIn()) redirect('index.php');
if (!in_array($_SESSION['user']['role_name']??'',['responsable','admin'],true)) redirect('dashboard_etudiant.php');
define('APP_INIT',true); $pageTitle='Suivi des recherches';
require_once __DIR__ . '/includes/header.php';
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $idEtu=$_POST['id_etudiant']??null; $ent=trim($_POST['entreprise_contactee']??'');
    $offr=trim($_POST['offre_consultee']??''); $date=$_POST['date_contact']??null;
    $stat=trim($_POST['statut']??''); $comm=trim($_POST['commentaire']??'');
    if (!$idEtu||!$date) { $error='Sélectionne un étudiant et une date de contact.'; }
    else {
        $pdo->prepare('INSERT INTO Recherche_stage (id_etudiant,entreprise_contactee,offre_consultee,date_contact,statut,commentaire) VALUES (?,?,?,?,?,?)')->execute([$idEtu,$ent,$offr,$date,$stat,$comm]);
        redirect('suivi_recherche.php');
    }
}
$etudiants=$pdo->query('SELECT id_etudiant,nom,prenom,promotion FROM Etudiant ORDER BY nom')->fetchAll();
$recherches=$pdo->query('SELECT r.*,e.nom AS etu_nom,e.prenom AS etu_prenom FROM Recherche_stage r JOIN Etudiant e ON r.id_etudiant=e.id_etudiant ORDER BY r.date_contact DESC')->fetchAll();
?>

<div class="page-header">
  <div class="top">
    <div>
      <span class="eyebrow">Responsable</span>
      <h1 class="page-title">Suivi des recherches</h1>
    </div>
    <div class="d-flex gap-2">
      <a href="dashboard_responsable.php" class="btn btn-outline-primary">Retour</a>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSuivi">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-1"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Ajouter un suivi
      </button>
    </div>
  </div>
</div>

<div class="content-card">
  <div class="table-responsive">
    <table class="data-table">
      <thead><tr><th>Date</th><th>Étudiant</th><th>Entreprise</th><th>Offre</th><th>Statut</th><th>Commentaire</th></tr></thead>
      <tbody>
        <?php if(empty($recherches)): ?>
          <tr><td colspan="6"><div class="empty-state"><p>Aucun suivi de recherche saisi.</p></div></td></tr>
        <?php else: foreach($recherches as $r): ?>
          <tr>
            <td class="text-muted small"><?= htmlspecialchars($r['date_contact']) ?></td>
            <td class="fw-600"><?= htmlspecialchars($r['etu_prenom'].' '.$r['etu_nom']) ?></td>
            <td><?= htmlspecialchars($r['entreprise_contactee']) ?></td>
            <td class="text-muted"><?= htmlspecialchars($r['offre_consultee']) ?></td>
            <td><span class="tag"><?= htmlspecialchars($r['statut']) ?></span></td>
            <td class="text-muted small"><?= htmlspecialchars($r['commentaire']) ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="modalSuivi" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-head">
        <div>
          <span class="eyebrow" style="display:block;margin-bottom:4px">Responsable</span>
          <h5 class="title">Ajouter un suivi</h5>
        </div>
        <button class="btn-close-modal" data-bs-dismiss="modal">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div class="modal-body-inner">
        <?php if($error): ?><div class="alert alert-danger mb-3"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
          <div class="form-group mb-3">
            <label class="form-label">Étudiant *</label>
            <select name="id_etudiant" class="form-select" required>
              <option value="">Choisir...</option>
              <?php foreach($etudiants as $e): ?><option value="<?= $e['id_etudiant'] ?>"><?= htmlspecialchars($e['nom'].' '.$e['prenom'].' ('.$e['promotion'].')') ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="form-group mb-3">
            <label class="form-label">Entreprise contactée</label>
            <input type="text" name="entreprise_contactee" class="form-control" placeholder="ex : Canal+">
          </div>
          <div class="form-group mb-3">
            <label class="form-label">Offre consultée</label>
            <input type="text" name="offre_consultee" class="form-control" placeholder="Titre de l'offre">
          </div>
          <div class="form-group mb-3">
            <label class="form-label">Date du contact *</label>
            <input type="date" name="date_contact" class="form-control" required>
          </div>
          <div class="form-group mb-3">
            <label class="form-label">Statut</label>
            <input type="text" name="statut" class="form-control" placeholder="En attente, Accepté, Refusé...">
          </div>
          <div class="form-group mb-3">
            <label class="form-label">Commentaire</label>
            <textarea name="commentaire" class="form-control" rows="3"></textarea>
          </div>
          <div class="modal-foot" style="padding:0;border:none;margin-top:8px">
            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
