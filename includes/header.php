<?php if (!defined('APP_INIT')) { header('Location: /index.php'); exit; } ?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'StageTrack ESUP') ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <?php
      $dashLink = (isLoggedIn() && in_array($_SESSION['user']['role_name'] ?? '', ['responsable','admin']))
        ? 'dashboard_responsable.php' : (isLoggedIn() ? 'dashboard_etudiant.php' : 'index.php');
      $cur = basename($_SERVER['PHP_SELF']);
    ?>
    <a class="navbar-brand" href="<?= $dashLink ?>">
      <span class="logo-dot"><span>S</span></span>
      StageTrack ESUP
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto align-items-center gap-1">
        <?php if (isLoggedIn()): ?>
          <?php if (in_array($_SESSION['user']['role_name'] ?? '', ['responsable','admin'])): ?>
            <li class="nav-item"><a class="nav-link <?= $cur==='dashboard_responsable.php'?'active':'' ?>" href="dashboard_responsable.php">Accueil</a></li>
            <li class="nav-item"><a class="nav-link <?= $cur==='offres.php'?'active':'' ?>" href="offres.php">Offres</a></li>
            <li class="nav-item"><a class="nav-link <?= $cur==='entreprises.php'?'active':'' ?>" href="entreprises.php">Entreprises</a></li>
            <li class="nav-item"><a class="nav-link <?= $cur==='conventions.php'?'active':'' ?>" href="conventions.php">Conventions</a></li>
            <li class="nav-item"><a class="nav-link <?= $cur==='suivi_recherche.php'?'active':'' ?>" href="suivi_recherche.php">Suivi</a></li>
            <li class="nav-item"><a class="nav-link <?= $cur==='contact.php'?'active':'' ?>" href="contact.php">Contact</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link <?= $cur==='dashboard_etudiant.php'?'active':'' ?>" href="dashboard_etudiant.php">Mon espace</a></li>
            <li class="nav-item"><a class="nav-link <?= $cur==='offres_etudiant.php'?'active':'' ?>" href="offres_etudiant.php">Offres</a></li>
            <li class="nav-item"><a class="nav-link <?= $cur==='entreprises.php'?'active':'' ?>" href="entreprises.php">Entreprises</a></li>
            <li class="nav-item"><a class="nav-link <?= $cur==='contact.php'?'active':'' ?>" href="contact.php">Contact</a></li>
          <?php endif; ?>
          <li class="nav-item ms-2"><a class="btn-conn" href="logout.php">Déconnexion</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link <?= $cur==='index.php'?'active':'' ?>" href="index.php">Connexion</a></li>
          <li class="nav-item ms-2"><a class="btn-primary-sm" href="register.php">S'inscrire</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container page-wrap">
