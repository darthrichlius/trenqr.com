<?php
    $lg_ia = "{wos/datx:iauth}";
    //[NOTE 10-12-14] L'opération de "parsage" transforme (malheuresement) notre TRUE en "1".
    if ( isset($lg_ia) && $lg_ia === "1" ) :
?>
<!--<div id="design-legals">
    
</div>-->
<div id="legals" class="">
<?php else: ?>
<div id="legals" class="wlc">
<?php endif; ?>    
    <p>© 2016, <a href="/">TRENQR</a></p>
    <ul>
        <li><a href="/about" alt="A propos de Trenqr">{wos/deco:_legals_about}</a></li>
<!--        <li class="legals-sep"> - </li>
        <li><a href="/faq">{wos/deco:_legals_help}</a></li>-->
        <li class="legals-sep"> - </li>
        <li><a href="/terms" alt="Accéder aux conditions d'utilisation de Trenqr">{wos/deco:_legals_terms}</a></li>
        <li class="legals-sep"> - </li>
        <li><a href="/privacy" alt="Accéder à la politique de confidentialité et sur le traitement des données sur Trenqr">{wos/deco:_legals_privacy}</a></li>
        <li class="legals-sep"> - </li>
        <li><a href="/cookies" alt="Accéder à la politique sur les cookies sur Trenqr">{wos/deco:_legals_cookies}</a></li>
        <li class="legals-sep"> - </li>
        <li><a href="javascript:;">Signaler un problème</a></li>
        <li class="legals-sep"> - </li>
        <li><a href="/!/recommend-trenqr-image-trend-cool-community">Recommander Trenqr</a></li><!--
        <li class="legals-sep"> - </li>
        <li><a href="/media">{wos/deco:_legals_media}</a></li>-->
        <!--<li class="legals-sep"> - </li>-->
        <?php
            if ( isset($lg_ia) && $lg_ia === "1" ) :
        ?>
        <!--<li><a class="legals-first" href="/report/bugs" title="Signaler un dysfonctionnement" alt="Signaler un dysfonctionnement de Trenqr">{wos/deco:_legals_report_bug}</a></li>-->
        <?php else: ?>
        <!--<li><a class="legals-first jb-irr" href="javascript:;" title="Signaler un dysfonctionnement" alt="Signaler un dysfonctionnement de Trenqr">{wos/deco:_legals_report_bug}</a></li>-->
        <?php endif; ?>
    </ul>
</div>