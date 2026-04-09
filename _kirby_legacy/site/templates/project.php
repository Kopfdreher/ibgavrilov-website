<?php snippet('header') ?>

<section class="datenschutz" style="padding-top: 120px;">
  <div class="container">
    <div class="datenschutz-content">
      <p style="color: var(--primary-color); text-align: center;">
         <?= $page->category() ?> | <?= $page->date()->toDate('d.m.Y') ?>
      </p>
      <h1><?= $page->title() ?></h1>
      
      <div class="post-content">
        <?= $page->text()->kt() ?>
      </div>

      <?php if($page->gallery()->isNotEmpty()): ?>
      <div class="project-gallery" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 2rem;">
        <?php foreach($page->gallery()->toFiles() as $image): ?>
          <a href="<?= $image->url() ?>" target="_blank">
            <img src="<?= $image->resize(400)->url() ?>" alt="<?= $image->alt() ?>" style="width: 100%; border-radius: 8px;">
          </a>
        <?php endforeach ?>
      </div>
      <?php endif ?>
      
      <div style="margin-top: 3rem; text-align: center;">
        <a href="<?= $site->url() ?>/#projekte" class="btn btn-secondary">← Zurück zur Übersicht</a>
      </div>

    </div>
  </div>
</section>

<?php snippet('footer') ?>
