
<?php 
    
//var_dump(empty($prefdcs),is_array($prefdcs),count($prefdcs),key_exists("_PFOP_TIABT_INR",$prefdcs), strtoupper($prefdcs["_PFOP_TIABT_INR"]["prfodtp_lib"]), strtoupper($prefdcs["_PFOP_TIABT_INR"]["prfodtp_lib"]) === "_DEC_DSMA");
//exit();
    if (! ( !empty($prefdcs) && is_array($prefdcs) && count($prefdcs)
            && key_exists("_PFOP_TIABT_INR",$prefdcs) && strtoupper($prefdcs["_PFOP_TIABT_INR"]["prfodtp_lib"]) === "_DEC_DSMA" )
    ) :
?>
<div id="tqr-wrng-betver-mx" class="jb-tqr-wrng-betver-mx">
    <a id="tqr-wrng-betver-clz" class="cursor-pointer jb-kxlib-close" data-target="tqr-wrng-betver-mx">&times;</a>
    <span id="tqr-wrng-betver-msg">
        Bienvenue sur Trenqr <span style="font-weight: bold; font-style: italic;  margin-right: 5px;">!</span> Vous utilisez actuellement la version <b>Trenqr VB3</b>.
        <a id="tqr-wrng-betver-msg-lrnbt" href="http://blog.trenqr.com/nouveautes-nouvelle-version-trenqr-vb3/" target="_blank">En savoir plus ...</a>
    </span>
    <a id="tqr-wrng-betver-done" class="cursor-pointer jb-tqr-wrng-betver-done" role="button">J'AI COMPRIS</a>
</div>
<?php endif; ?>