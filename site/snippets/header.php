<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page->title() ?> - Ingenieurbüro Gavrilov</title>
  <?= css('assets/css/styles.css') ?>
  <?= js('assets/js/script.js', ['defer' => true]) ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <nav class="navbar">
    <div class="nav-container">
      <div class="nav-logo">
        <a href="<?= $site->url() ?>" style="text-decoration: none;"><h2>ibgavrilov</h2></a>
      </div>
      <ul class="nav-menu">
        <li><a href="<?= $site->url() ?>/#home" class="nav-link">Home</a></li>
        <li><a href="<?= $site->url() ?>/#leistungen" class="nav-link">Leistungen</a></li>
        <li><a href="<?= $site->url() ?>/#projekte" class="nav-link">Projekte</a></li>
        <li><a href="<?= $site->url() ?>/#kontakt" class="nav-link">Kontakt</a></li>
      </ul>
      <div class="hamburger">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
      </div>
    </div>
  </nav>
