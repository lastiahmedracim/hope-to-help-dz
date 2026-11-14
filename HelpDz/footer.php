<section id="footer1">
        <div class="footer1-content">
            <div class="text">
                <ul>
                    <li><a href="<?php echo $formation; ?>" data-translate="Formation Professionnelle">Formation Professionnelle</a></li>
                    <li><a href="<?php echo $finhelp; ?>" data-translate="Aide aux projets">Aide aux projets</a></li>
                    <li><a class="change-text" href="" data-translate="Investissement">Investissement</a></li>      
                </ul>                 
            </div>
            <div class="text">
                <ul>
                    <li><a href="<?php echo $aboutus; ?>" data-translate="À propos d'Hope to Help Dz">À propos d'Hope to Help Dz</a></li>
                    <li><a href="<?php echo $contactus; ?>" data-translate="Contactez-nous">Contactez-nous</a></li>
                    <li><a href="./الشروط و الاحكام و سياسة الخصوصية .docx" data-translate="Conditions">Conditions</a></li>
                </ul>                 
            </div>
            <div class="newsletter">
                <h3 data-translate="Inscrivez-vous à notre newsletter">Inscrivez-vous à notre newsletter</h3>
                <form id="newsletterForm" action="<?php echo $newsletter; ?>" method="post">
                    <div class="newsletter-btn">
                        <input type="text" name="nw_email" id="nw_email" placeholder="Entrez votre adresse e-mail" data-translate="Entrez votre adresse e-mail">
                        <input type="submit" value="S'abonner" data-translate="S'abonner">
                    </div>
                </form>
                <div id="submit-message"></div>
            </div>
        </div>
    </section>

    <section id="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="<?php echo $logo; ?>" alt="Hope to Help Dz">
                </div>
                <div class="footer-text">
                    <p>&copy; 2023 Hope to Help Dz. <span data-translate="Tous droits réservés.">Tous droits réservés.</span></p>               
                </div>                
            </div>
        </div>
    </section>

    <script src='<?php echo $jsfile1; ?>'></script>
    <script src='<?php echo $jsfile2; ?>'></script>
    <script src='<?php echo $jsfile3; ?>'></script>
    <script src='<?php echo $rcp; ?>'></script>
    <script src='<?php echo $gun; ?>'></script>
</body>
</html>