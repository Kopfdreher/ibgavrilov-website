<?php snippet('header') ?>

<?php 
// Check if a background image is selected in the panel
$heroBg = $page->heroImage()->toFile();
// Fallback style if no image is uploaded yet
$bgStyle = $heroBg ? "background-image: url('" . $heroBg->url() . "');" : "";
?>

<section id="home" class="hero" style="<?= $bgStyle ?>">
    <div class="hero-overlay"></div>
    <div class="hero-container">
        <div class="hero-content">
            <h1 class="hero-title"><?= $page->heroTitle() ?></h1>
            <p class="hero-subtitle"><?= $page->heroSubtitle() ?></p>
            <div class="hero-buttons">
                <a href="#leistungen" class="btn btn-primary"><?= $page->heroBtnPrimary() ?></a>
                <a href="#kontakt" class="btn btn-secondary"><?= $page->heroBtnSecondary() ?></a>
            </div>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <h2 class="section-title"><?= $page->featuresHeadline() ?></h2>
        <div class="features-grid">
            <?php foreach ($page->featureGrid()->toStructure() as $feature): ?>
            <div class="feature-card">
                <div class="feature-icon"><i class="<?= $feature->icon() ?>"></i></div>
                <h3><?= $feature->title() ?></h3>
                <p><?= $feature->text() ?></p>
            </div>
            <?php endforeach ?>
        </div>
    </div>
</section>

<section id="leistungen" class="services">
    <div class="services-overlay"></div>
    <div class="container">
        <h2 class="section-title"><?= $page->servicesHeadline() ?></h2>
        <div class="services-grid">
            <?php foreach ($page->serviceGrid()->toStructure() as $service): ?>
            <div class="service-card">
                <div class="service-icon"><i class="<?= $service->icon() ?>"></i></div>
                <h3><?= $service->title() ?></h3>
                <p><?= $service->description() ?></p>
            </div>
            <?php endforeach ?>
        </div>
    </div>
</section>

<section id="projekte" class="projects">
    <div class="container">
        <h2 class="section-title">Projekte</h2>
        <div class="projects-grid">
            <?php 
            // This pulls from the separate Project Pages you create
            $projects = page('projects')->children()->listed(); 
            if($projects->count() > 0):
                foreach($projects as $project): ?>
                <div class="project-card">
                    <a href="<?= $project->url() ?>">
                        <?php if ($image = $project->gallery()->toFiles()->first()): ?>
                            <div class="project-image">
                                <img src="<?= $image->crop(600, 400)->url() ?>" alt="<?= $project->title() ?>">
                            </div>
                        <?php else: ?>
                            <div class="project-icon"><i class="<?= $project->icon()->or('fas fa-road') ?>"></i></div>
                        <?php endif ?>
                        <div class="project-content">
                            <h3><?= $project->title() ?></h3>
                            <p><?= $project->summary() ?></p>
                            <span class="btn btn-secondary project-btn">Mehr Infos</span>
                        </div>
                    </a>
                </div>
                <?php endforeach;
            else: ?>
                <p class="text-center">Neue Projekte werden bald geladen.</p>
            <?php endif ?>
        </div>
    </div>
</section>

<section id="kontakt" class="contact">
    <div class="contact-overlay"></div>
    <div class="container">
        <h2 class="section-title">Kontakt</h2>
        <div class="contact-content">
             <div class="contact-info">
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-phone"></i></div>
                    <div class="contact-details"><h3>Telefon</h3><p>+49 1577 3489528</p></div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div class="contact-details"><h3>E-Mail</h3><p>n.gavrilov@ibgavrilov.de</p></div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="contact-details"><h3>Adresse</h3><p>Lincolnstraße 31A<br>10315 Berlin</p></div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php snippet('footer') ?>
