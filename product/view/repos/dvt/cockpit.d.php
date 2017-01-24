<div id="asR_trd_cockpit_box" class="jb-asr-trd-ckpt-bx">
    <div id="trpg_toolbox">
        <img width="80" src="{wos/sysdir:img_dir_uri}/r/ckpt.png" alt="Cockpit" />
    </div>
    <div id="asR_trd_ckp_list">
        <?php 
            $iown = "{wos/datx:isown}";
            $iabo = "{wos/datx:cuisabo}";
            $tpart = "{wos/datx:trpart}";
            
//            var_dump($iown,$iabo,$tpart);
//            exit();
            
            if ( $iown | ( $iabo && isset($tpart) && strtolower($tpart) === "{wos/deco:_part_pub_code}" ) ) :
        ?>
        <div class="">
            <a class="asR_trd_ckp_choice jb-acc-nwtrart" data-action="newintrpg" href="javascript:;">
                <img height="50" width="50" src="{wos/sysdir:img_dir_uri}/r/na_lg.png" title="Ajouter une contribution" alt="Ajouter une publication" />
            </a>
            <a class="asR_trd_ckp_choice jb-tqr-trpg-ckp-akxn jb-tqr-ltc-action" data-action="ltc-show" href="javascript:;"></a>
            <!--<a class="asR_trd_ckp_choice jb-tqr-ltc-action" data-action="trd-event" href="javascript:;"></a>-->
        </div>
        <?php elseif ( $iabo ) : ?>
        <a class="asR_trd_ckp_choice jb-tqr-trpg-ckp-akxn jb-tqr-ltc-action" data-action="ltc-show" href="javascript:;"></a>
        <?php else : ?>
        <div id="ckP-gn-alt-mx" class="">
            <?php 
            if ( $iabo ) :
            ?>
            <span class="ckP-gn-alt tqr">TrEnQr !</span> 
            <?php elseif ( isset($tpart) && strtolower($tpart) !== "{wos/deco:_part_pub_code}" ) : ?>
            <span class="ckP-gn-alt pri">Abonnez-vous à cette Tendance pour n'en rien rater !</span> 
            <?php else: ?>
            <span class="ckP-gn-alt pub">Abonnez-vous à cette Tendance et devenez un de ses contributeurs</span> 
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>