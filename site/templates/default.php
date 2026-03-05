<<?php snippet('header') ?>

<section class="datenschutz" style="padding-top: 120px;">
    <div class="container">
        <div class="datenschutz-content">
            
            <h1><?= $page->title() ?></h1>
            
            <div class="post-content">
                <?= $page->text()->kt() ?>
            </div>

            <div style="margin-top: 3rem; text-align: center; border-top: 1px solid var(--border-color); padding-top: 2rem;">
                <a href="<?= $site->url() ?>" class="btn btn-secondary">Zurück zur Startseite</a>
            </div>

        </div>
    </div>
</section>

<?php snippet('footer') ?>h1><?= $page->title() ?></h1>
