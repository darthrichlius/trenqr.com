
<?php
    if ( $ec_is_ecofirm && $ec_state === "_EC_STT_WELCOME" && $ec_scope ) :
?>

<div id="tqr-ecnfrm-fnl-bmx" data-scope=<?php echo $ec_scope; ?>>
<!--<div id="tqr-ecnfrm-fnl-bmx" data-scope="TQR_INS">-->
    <a id="tqr-ecnfrm-fnl-bx-fmr" class="cursor-pointer jb-kxlib-close" data-target="tqr-ecnfrm-fnl-bmx"></a>
    <div id="tqr-ecnfrm-fnl-bx-body">
        <div id="tqr-ecnfrm-fnl-li1">Votre adresse email a bien été validée</div>
        <div id="tqr-ecnfrm-fnl-li2">Bienvenue sur Trenqr, bienvenue chez vous.</div>
        <div id="tqr-ecnfrm-fnl-li3">
            <a id="tqr-ecnfrm-fnl-invite" class="cursor-pointer" href="/!/recommend-trenqr-image-trend-cool-community">Inviter des amis</a>
        </div>
    </div>
</div>

<?php endif; ?>