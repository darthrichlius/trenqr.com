<!--
    * qsp : Query ScoPe
-->
<div id="asd-srch-max" class="jb-asdapp-modl jb-srh-asd-b this_hide" data-modl="searchbox">
    <!--<div id="asd-sr-hdr" class="tr">-->
    <div id="asd-sr-hdr" class="jb-asd-sr-hdr pfl">
        <div class="asd-sr-hdr-tle">
            <span>Chercher un utilisateur</span>
        </div>
<!--        <div id="asd-sr-hdr-catg-max" class="asd-sr-hdr-elts">
            <a class="asd-sr-h-catg-chcs cursor-pointer jb-srh-swh-mn" data-action="fil_menu" data-target="fil_mn_trd" data-qsp="min" title="Rechercher un profil" alt="Lancer une recherche sur un compte Trenqr" >
                <img src="{wos/sysdir:img_dir_uri}/r/man_w.png" width="20" height="20"/>
            </a>
            <a class="asd-sr-h-catg-chcs cursor-pointer jb-srh-swh-mn this_hide" data-action="fil_menu" data-target="fil_mn_pfl" data-qsp="min" title="Rechercher une Tendance" alt="Lancer une recherche sur une Tendance Trenqr" >T</a>
        </div>-->
        <div id="asd-sr-hdr-ipt" class="asd-sr-hdr-elts">
            <span id="asd-sr-hdr-ipt-wrapl" class="asd-sr-hdr-ipt-wrap"></span> 
            <input id="asd-sr-hdr-ipt-ipt" class="jb-asd-srch-ipt jb-srch-ipt" data-target="fil_mn_pfl" data-qsp="min" type="text" name="" placeholder="Pseudo, Nom Complet" >
            <a id="asd-sr-hdr-ipt-rst" class="jb-asd-sr-ipt-rst" href="">x</a>
            <span id="asd-sr-hdr-ipt-wrapr"  class="asd-sr-hdr-ipt-wrap"></span>
        </div>
    </div>
    <div id="asd-sr-bdy" class="jb-asd-sr-bdy">
        <div class="srh-hy-rslt-no1e pf jb-srh-no1e this_hide">
            <span id="srh-hy-rslt-no1e-tx">AUCUNE IDÉE</span>
        </div>
        <div id="srh-asd-ldg-bx">
            <img id="srh-asd-ldg" class="jb-srh-ldg this_hide" src="{wos/sysdir:img_dir_uri}/r/ld_32.gif" />
        </div>
        <div id="asd-sr-bdy-rlist" class="jb-asd-sr-bdy-rlist jb-srh-rslt-list" data-dt="pf">
            <!-- nvd : NoVoid -->
            <div id="asd-sr-bdy-nvd" class="jb-asd-sr-bdy-nvd">
<!--                <div id="asd-sr-bdy-aim-mx">
                    <div id="asd-sr-bdy-aim">Lancer une nouvelle rechercher</div>
                    <div id="asd-sr-bdy-or">- ou -</div>
                </div>
                <a class="asd-sr-jump-tgr jb-asd-sr-jump-tgr" data-action="jump_account" href="javascript:;" title="Laissez-vous guider dans l'exploration de l'univers Trenqr" role='button'>
                    <span class="jb-asd-sr-j-txt">JUMP PLAY</span>
                    <img id="asd-sr-j-ldg" class="jb-asd-sr-j-ldg this_hide" src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" />
                </a>-->
                <span id="asd-sr-bdy-nvd-tqr" class="">Trenqr</span>
            </div>
            <?php for($i=0; $i<6; $i++) : ?>
<!--            <div class="asd-sr-min-pfl-mdl jb-srh-rslt-mdl" data-item="">
                <div class="asd-sr-min-ugrp">
                    <a class="asd-sr-min-ugp-mn" href="/mouna">
                        <img class="asd-sr-min-ugp-img" src="http://www.placehold.it/45x45" width="45" height="45"/>
                        <span class="asd-sr-min-ugp-psd">@Pseudo<?php // echo $i; ?></span>
                    </a>
                    <div class="asd-sr-min-ug-fn">
                        <span class="asd-sr-min-ug-fn">Nom Complet<?php // echo $i; ?></span>
                     </div>
                </div>
                <div class="asd-sr-min-urel">
                    <span class="">Amis</span>
                </div>
            </div>-->
<!--            <div class="asd-sr-min-trd-mdl jb-srh-rslt-mdl" data-item="">
                <div class="asd-sr-m-t-mdl-top clearfix2">
                    <div class="asd-sr-m-t-mdl-t-lft">
                        <img src="http://www.placehold.it/35/58237B/ffffff" />
                    </div>
                    <div class="asd-sr-m-t-mdl-t-rgt">
                        <a class="asd-sr-m-t-mdl-tr-tle" href="">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet eros vehicula viverra fusce.
                        </a>
                    </div>
                </div>
                <div class="asd-sr-m-t-mdl-bot clearfix2">
                    <span class="asd-sr-m-t-mdl-b-fol">
                        <span class="asd-sr-m-t-mdl-b-fol-nb">23</span>
                        <span class="asd-sr-m-t-mdl-b-fol-lib">Abos</span>
                    </span>
                    <span class="asd-sr-m-t-mdl-b-pst">
                        <span class="asd-sr-m-t-mdl-b-pst-nb">100</span>
                        <span class="asd-sr-m-t-mdl-b-pst-lib">Posts</span>
                    </span>
                </div>
            </div>-->
            <?php endfor; ?>
        </div>
        <!-- [NOTE 15-04-15]RETIRE -->
        <!--<a id="asd-sr-bdy-mr" class="jb-srh-rslt-mr this_hide" data-action="smr" data-target="fil_mn_pfl" data-qsp="min" href="">Plus de résultats</a>-->
    </div>
    <div id="asd-sr-ftr" class="">
    </div>
</div>

