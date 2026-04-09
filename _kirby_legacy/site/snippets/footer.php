<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Ingenieurbüro Gavrilov</h3>
                <p>Planung für Verkehrsanlagen im Land Berlin</p>
            </div>
            <div class="footer-section">
                <h4>Kontakt</h4>
                <p>+49 1577 3489528</p>
                <p>n.gavrilov@ibgavrilov.de</p>
            </div>
            <div class="footer-section">
                <h4>Adresse</h4>
                <p>Lincolnstraße 31A<br>10315 Berlin</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Ingenieurbüro Gavrilov. Alle Rechte vorbehalten.</p>
            <div class="footer-links">
                <a href="#impressum" class="footer-link">Impressum</a>
                
                <?php if($p = page('datenschutz')): ?>
                <a href="<?= $p->url() ?>" class="footer-link">Datenschutz</a>
                <?php endif ?>
            </div>
        </div>
    </div>
</footer>

<div id="impressum" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Impressum</h2>
        <h3>Angaben gemäß § 5 DDG</h3>
        <p><strong>Nikolaj Gavrilov</strong><br>
        Lincolnstr. 31A<br>
        10315 Berlin<br>
        Steuernummer: 32/301/02403</p>
        
        <h4>Vertreten durch:</h4>
        <p>Nikolaj Gavrilov</p>
        
        <h4>Kontakt:</h4>
        <p>Telefon: +49 1577 3489528<br>
        E-Mail: n.gavrilov@ibgavrilov.de</p>
    </div>
</div>

<?= js('assets/js/script.js') ?>
</body>
</html>
