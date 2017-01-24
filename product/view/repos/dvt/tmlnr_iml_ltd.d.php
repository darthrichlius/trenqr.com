<!-- 
    [NOTE 19-04-15] @BOR
    Ce modèle est utilisé dans le cas où l'utilisateur est autorisé à accéder aux Articles IML mais qu'il n'en est pas le propriétaire.
    Aussi, certaines fonctionnalités sont bloquées. Il n'a pas accès aux fonctionnalités suivantes :
        -> Suppression de l'Article
    Certaines autres fonctionnalités ne sont activées qu'en fonction de la relation de CU et OWU. 
    Les deux seules relations qui permettent d'accéder à ce modèle sont : DFOLW et FRD.
    RAPPEL :  Les fonctionnalités sur TRENQR sont distibuées de manière graduelles. Nous restons sur cette philosophie dans ce contexte.
    Dans le cas, FRD :
        -> Accès aux trois types d'EVAL
        -> Accès aux Commentaires
    Dans le cas, DFOLW :
        -> Accès au type EVAL de type "Cool" seulement
        -> N'a pas accès aux Commentaires
    
    Cependant, quelque soit le cas des Relations, l'utilisateur CU a accès à ACTION car il faut qu'il puisse accéder à PERMALINK.
-->
<?php 
    $str__ = "[]";
    if ( isset($article) && key_exists("ustgs",$article) && !empty($article["ustgs"]) && is_array($article["ustgs"]) ) {
        $istgs__ = [];
        foreach ($article["ustgs"] as $ustg) {
            $istgs__[] = implode("','", $ustg);
        }  
        
        if ( count($istgs__) > 1 ) {
            $str__ = implode("'],['", $istgs__);
        } else {
            $str__ = $istgs__[0];
        }
        $str__ = "['$str__']";
    }
?>
<div id="post-accp-myl-id<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" 
     class="feeded_com_bloc_figs tqr-tmlnr-art-iml-mdl jb-tmlnr-mdl-std jb-tqr-fav-bind-arml" 
     data-item="<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" 
     data-time="<?php echo ( isset($article) && key_exists("time", $article ) ) ? $article["time"] : ''; ?>" 
     data-psd="<?php echo ( isset($article) && key_exists("upsd", $article ) ) ? $article["upsd"] : ''; ?>" 
     data-fn="<?php echo ( isset($article) && key_exists("ufn", $article ) ) ? $article["ufn"] : ''; ?>" 
     data-atype="iml"
     data-cache="['<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>','<?php echo ( isset($article) && key_exists("img", $article ) ) ? $article["img"] : ''; ?>','<?php echo ( isset($article) && key_exists("msg", $article ) ) ? $article["msg"] : ''; ?>','<?php echo ( isset($article) && key_exists("trd_eid", $article ) ) ? $article["trd_eid"] : ''; ?>','<?php echo ( isset($article) && key_exists("trtitle", $article ) ) ? $article["trtitle"] : ''; ?>','<?php echo ( isset($article) && key_exists("rnb", $article ) ) ? $article["rnb"] : ''; ?>','<?php echo ( isset($article) && key_exists("trhref", $article ) ) ? $article["trhref"] : ''; ?>','<?php echo ( isset($article) && key_exists("prmlk", $article ) ) ? $article["prmlk"] : ''; ?>'],['<?php echo ( isset($article) && key_exists("time", $article ) ) ? $article["time"] : ''; ?>','<?php echo ( isset($article) && key_exists("utc", $article ) ) ? $article["utc"] : ''; ?>'],['<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][0] : ''; ?>','<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][1] : ''; ?>','<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][2] : ''; ?>','<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][3] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) && key_exists(0, $article["eval_lt"]) ) ? $article["eval_lt"][0] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) && key_exists(1, $article["eval_lt"]) ) ? $article["eval_lt"][1] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) && key_exists(2, $article["eval_lt"]) ) ? $article["eval_lt"][2] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) && key_exists(3, $article["eval_lt"]) ) ? $article["eval_lt"][3] : ''; ?>'],['<?php echo ( isset($article) && key_exists("ueid", $article ) ) ? $article["ueid"] : ''; ?>','<?php echo ( isset($article) && key_exists("ufn", $article ) ) ? $article["ufn"] : ''; ?>','<?php echo ( isset($article) && key_exists("upsd", $article ) ) ? $article["upsd"] : ''; ?>','<?php echo ( isset($article) && key_exists("uppic", $article ) ) ? $article["uppic"] : ''; ?>','<?php echo ( isset($article) && key_exists("uhref", $article ) ) ? $article["uhref"] : ''; ?>'],['<?php echo ( isset($article) && key_exists("myel", $article ) ) ? $article["myel"] : ''; ?>'] "
     data-with="<?php echo $str__; ?>"
     data-lp=""
     data-pml="<?php echo ( isset($article) && key_exists("prmlk", $article ) ) ? $article["prmlk"] : ''; ?>"
     
     data-ajcache='<?php echo htmlspecialchars(json_encode($article),ENT_QUOTES,'UTF-8'); ?>'
     data-vidu="<?php echo ( isset($article) && key_exists("vidu", $article ) && $article["vidu"] ) ? $article["vidu"] : ''; ?>"
     data-hasfv="<?php echo ( isset($article) && key_exists("hasfv", $article ) && $article["hasfv"] ) ? $article["hasfv"] : ''; ?>"
     data-fvtp="<?php echo ( isset($article) && key_exists("fvtp", $article ) && $article["fvtp"] ) ? $article["fvtp"] : ''; ?>"
     data-isod="<?php echo ( isset($article) && key_exists("isod", $article ) && $article["isod"] ) ? $article["isod"] : ''?>"
     data-trq-ver='ajca-v10'
>
    <div class="arp-spnr-bmx">
        <span class="arp-spnr-mx"><i class="fa fa-refresh fa-spin"></i></span>
    </div>
    <div class="post-solo-in-acclist">
        <div class="fcb_top">
            <?php if ( isset($article) && key_exists("isod", $article ) && $article["isod"] ) : ?>
            <div class="tqr-art-isod-lg" data-atype="onpg_tmlnr">Photo du jour</div>
            <?php endif; ?>
            <!--
            <div class="fcb_intop_time">
                <?php 
                    $spytm =  ( isset($article) && key_exists("time", $article ) ) ? $article["time"] : ''; 
                ?>
                <span class="kxlib_tgspy" data-tgs-crd="<?php echo $spytm; ?>" data-tgs-dd-atn="<?php echo $spytm; ?>" data-tgs-dd-uut="<?php echo $spytm; ?>">
                    <span class='tgs-frm'></span>
                    <span class='tgs-val'></span>
                    <span class='tgs-uni'></span>
                </span>
            </div>
            <div id="tqr-art-actbar-mx" class="jb-tqr-art-abr-mx" data-scp="am-tmlnr-iml">
                <ul id="tqr-art-actbar-lst-mx">
                    <li class="tqr-art-actbar-l-elt">
                        <a class="tqr-art-actbar-tgr jb-tqr-art-abr-tgr" data-css="favorite" data-action="favorite" data-state="" title="Mettre en favoris"></a>
                        <div id="tqr-art-actbar-fav-chcs" class="jb-tqr-art-abr-fav-chs this_hide">
                            <a class="tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch" data-css="fav_public" data-action="fav_public">Privé</a>
                            <a class="tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch" data-css="fav_private" data-action="fav_private">Public</a>
                        </div>
                    </li>
                    <!-- ONLY FOR OWNER 
                    <li class="tqr-art-actbar-l-elt"><a class="tqr-art-actbar-tgr jb-tqr-art-abr-tgr" data-css="download" data-action="download" title="Télécharger la photo"></a></li>
                    <li class="tqr-art-actbar-l-elt"><a class="tqr-art-actbar-tgr jb-tqr-art-abr-tgr" data-css="report" data-action="report" title="Signaler la publication"></a></li>
                </ul>
            </div>
            <div class='fcb_intop_left'>
                <span class="fcb_intop_in">in</span>
                <span class="fcb_intop_wa">MyLIFE</span>
            </div>
            -->
        </div>
        <div class="fcb_img_maximus" data-target="post-accp-myl-id<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>">
            <div class="fcb_img">
                <?php 
                    if ( isset($article) && key_exists("img", $article ) && !empty($article["img"]) ) :
                        $aimg = $article["img"];
                ?>
                <img class="fcb_img_img" height="370" width="370" src="<?php echo $aimg; ?>" alt="<?php echo $article["msg"]; ?>"/>
                <?php else : ?>
                <span></span>
                <?php endif; ?>
            </div>
            
            <?php if ( isset($article) && key_exists("vidu", $article ) && $article["vidu"] ) : ?>
            <span class="soft_fade vidu">
            <?php else : ?>
            <span class="soft_fade">
            <?php endif; ?>
                <div class="tqr-artml-tmonhvr jb-tqr-artml-onhvr-tm this_hide" data-atype="tmlnr">
                    <?php echo ( isset($article) && key_exists("time", $article ) && isset($article["time"]) ) ? date("d.m.y",($article["time"]/1000)) : ''; ?>
                </div>
                <div class="tqr-artmdl-asdxtr-box jb-tqr-am-ax-box this_hide">
                    
                    <?php if ( isset($pgvr) && strtolower($pgvr) === "wu" ) : ?>
                    <a class="tqr-artmdl-shron-tgr cursor-pointer jb-irr" href="javascript:;" data-art-mdl="on_page" data-action="favorite" title="Mettre en favori"></a>
                    <?php elseif ( isset($article) && key_exists("hasfv", $article ) && $article["hasfv"] ) : ?>
                    <a class="tqr-artmdl-shron-tgr cursor-pointer jb-tqr-art-abr-tgr" data-art-mdl="on_page" data-action="unfavorite" data-reva="favorite" data-revt="Mettre en favori" title="Retirer des favoris"></a>
                    <?php else : ?>
                    <a class="tqr-artmdl-shron-tgr cursor-pointer jb-tqr-art-abr-tgr" data-art-mdl="on_page" data-action="favorite" data-reva="unfavorite" data-revt="Retirer des favoris" title="Mettre en favori"></a>
                    <?php endif; ?>
                    
                    <?php if ( isset($article) && key_exists("isod", $article ) && $article["isod"] ) : ?>
                    <span class="tqr-artmdl-xtras-sep"></span>
                    <a class="tqr-artmdl-shron-tgr cursor-pointer jb-tqr-artmdl-shron-tgr " data-art-mdl="on_page" data-action="amdl_sharon_fb" title="Partager sur Facebook"></a>
                    <a class="tqr-artmdl-shron-tgr cursor-pointer jb-tqr-artmdl-shron-tgr " data-art-mdl="on_page" data-action="amdl_sharon_twr" title="Partager sur Twitter"></a>
                    <?php endif; ?>
                </div>
                <div class="tqr-art-actbar-fav-bmx jb-tqr-art-abr-fav-bmx this_hide">
                    <div class="tqr-art-actbar-fav-chcs">
                        <a class="tqr-art-actbar-fav-ccl cursor-pointer jb-tqr-art-actbar-fav-ccl" data-art-mdl="on_page" data-action="fav_cancel" title="Annuler" role="button"></a>
                        <a class="tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch" data-art-mdl="on_page" data-css="fav_public" data-action="fav_public">Public</a>
                        <a class="tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch" data-art-mdl="on_page" data-css="fav_private" data-action="fav_private">Privé</a>
                    </div>
                </div>
            </span>
            
            <div class="tmlnr_bot_fade">
                <span class="b_f_com b_f_react">
                    <span class="jb_b_f_rlib b_f_rLib" style="background: url('{wos/sysdir:img_dir_uri}/r/r3.png?v={wos/systx:now}') no-repeat;"></span>
                    <span class="jb_b_f_rnb b_f_rNb"><?php echo ( isset($article) && key_exists("rnb", $article ) ) ? $article["rnb"] : ''; ?></span>
                </span>
                <span class="b_f_com b_f_eval">
                    <span class="jb_b_f_enb b_f_eNb jb-csam-eval-oput" data-cache='<?php echo ( isset($article) && key_exists("eval", $article ) ) ? '['.implode(",",$article["eval"]).']' : ''; ?>'><span><?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][3] : ''; ?></span></span>
                </span>
            </div>
        </div>
        <div class="sp_iml_bot">
            <span class="botm_a_desc this_hide" data-d="<?php echo ( isset($article) && key_exists("msg", $article ) ) ? $article["msg"] : ''; ?>">
                <?php echo ( isset($article) && key_exists("msg", $article ) ) ? $article["msg"] : ''; ?>
            </span>
            <div class="botm_listHtgs">
                <?php 
                    if ( isset($article["hashs"]) && is_array($article["hashs"]) && count($article["hashs"]) ) : 
                        $ah = $article["hashs"];
                        while ( isset($ah) && is_array($ah) && count($ah) && !empty($ah[0]) ) :
                        $hash = array_shift($ah);
                ?>
                <a class="botm_listHtg" href="/hview/q=<?php echo $hash; ?>&src=hash" ><?php echo '<i>#</i>'.$hash; ?></a>
                <?php 
                        endwhile; 
                    endif; 
                ?>
            </div>
        </div>
    </div>
    <div class="arp-solo-in-acclist jb-arp-solo-in-acclist this_hide">
        <div class="rich-unik-acclist-child rich-unik-acclist-left jb-arp-l-composed">
            <div>
                <div class="rich-post-left-header">
                    <div class="rich-post-left-header-grpuser-max">
                        <p class="rich-post-left-header-grpuser">
                            <a class="rich-post-left-header-grpuser-link" title="<?php echo ( isset($article) && key_exists("ufn", $article ) ) ? $article["ufn"] : ''; ?>" href="<?php echo ( isset($article) && key_exists("uhref", $article ) ) ? $article["uhref"] : ''; ?>">
                                <span class="arp-lft-hdr-grpu-i-fade"></span>
                                <img class="rich-post-left-header-grpuser-link-img" height="45" width="45"src="<?php echo ( isset($article) && key_exists("uppic", $article ) ) ? $article["uppic"] : ''; ?>" />
                                <span class="rich-post-left-header-grpuser-psd"><?php echo ( isset($article) && key_exists("upsd", $article ) ) ? "@".$article["upsd"] : ''; ?></span>
                            </a>
                        </p>
                    </div>
                    <!--
                    <p class="rich-post-left-header-hash">
                        <?php 
                            if ( isset($article["hashs"]) && is_array($article["hashs"]) && count($article["hashs"]) ) : 
                                $ah = $article["hashs"];
                                while ( isset($ah) && is_array($ah) && count($ah) && !empty($ah[0]) ) :
                        ?>
                        <a class="arp-hash-elt-lnk" href="javascript:;"><?php echo '<i>#</i>'.array_shift($ah); ?></a>
                        <?php 
                                endwhile; 
                            endif; 
                        ?>
                    </p>
                    -->
                    <div class="jb-csam-eval-box css-eval-box css-eval-box-tmlnr arp">
                    <!--<div class="jb-csam-eval-box css-eval-box css-eval-box-tmlnr css-eval-box-unq clearfix arp">-->
                        <?php
                            $evals = ( isset($article) && key_exists("eval", $article ) && count($article["eval"]) === 4 ) ? $article["eval"] : [0,0,0,0];
                            $myel = ( isset($article) && key_exists("myel", $article ) && $article["myel"] !== "" ) ? $article["myel"] : "";
                        ?>
                         <div class="jb-eval-dplw-bar-mx">
                            <span class="jb-csam-eval-oput css-csam-eval-oput arp" data-cache="<?php echo "['$evals[0]','$evals[1]','$evals[2]','$evals[3]','$myel']" ?>"><span><?php echo $evals[3]; ?></span>&nbsp;coo<i>!</i></span>
                            <div class="eval-dplw-bar arp">
                                <?php 
                                   /*
                                    * [NOTE 08-09-14] @author L.C. 
                                    * J'ai préféré cette méthode de traitement (3 blocs) car je la trouvait plus lisible donc plus facile à maintenir.
                                    * D'autres choix auraient pu être fait !
                                    * 
                                    * Réaffirmé le [02-12-14]
                                    * 
                                    * [NOTE 1?-04-15] @author L.C. 
                                    * J'ai changé pour une méthode de traitement par cas de façon unitaire.
                                    * 
                                    * [NOTE 15-07-15] @author L.C.
                                    * Quand on a "supacool" le logo "cool" ne doit pas s'activer.
                                    * 
                                    * [DEPUIS 07-07-16]
                                    *       -> Les 3 EVALs sont maintenant aussi disponibles pour les ARTICLES SOD + ( FRD ou DFOLW )
                                    *       -> Quand WLC on a accès aux 3 EVALs mais avec jb-irr
                                    */
                                    if ( 
                                        !empty($urel) && in_array(strtolower($urel), ["xr03","xr13","xr23"]) //CAS : AMIS
                                        || ( $article["isod"] && !empty($urel) && in_array(strtolower($urel), ["xr03","xr13","xr23","xr02","xr12","xr22"]) ) // CAS : SOD + ( AMIS + DFOLW )
                                    ) :
                                ?>
                                    <span class="css-csam-eval-chs-wrp">
                                        <span class="evlbx-ch-nb jb-evlbx-ch-nb" data-scp="scl"><?php echo $evals[0]; ?></span>
                                        <a class="jb-csam-eval-choices jb-csam-eval-spcool css-csam-eval-chs css-c-e-chs-scl <?php echo ( $myel !== "" && $myel !== 0 && $myel === "p2" ) ? "active css-c-e-chs-scl_hover" : ""; ?>" data-action="bk_spcl" data-zr="rh_spcl" data-rev="rh_spcl" data-target="post-accp-myl-id<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" data-xc="arp" title="{wos/deco:_eval_spcool}" href="javascript:;" role="button"></a>
                                    </span>
                                    <span class="css-csam-eval-chs-wrp">
                                        <span class="evlbx-ch-nb jb-evlbx-ch-nb" data-scp="cl"><?php echo $evals[1]; ?></span>
                                        <a class="jb-csam-eval-choices jb-csam-eval-cool css-csam-eval-chs css-c-e-chs-cl <?php echo ( $myel !== "" && $myel !== 0 && $myel === "p1" ) ? "active css-c-e-chs-cl_hover" : ""; ?>" data-action="rh_cool" data-zr="rh_cool" data-rev="bk_cool" data-target="post-accp-myl-id<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" data-xc="arp" title="{wos/deco:_eval_cool}" href="javascript:;" role="button"></a>
                                    </span>
                                    <span class="css-csam-eval-chs-wrp">
                                        <span class="evlbx-ch-nb jb-evlbx-ch-nb" data-scp="dlk"><?php echo $evals[2]; ?></span>
                                        <a class="jb-csam-eval-choices jb-csam-eval-dislk css-csam-eval-chs css-c-e-chs-dsp <?php echo ( $myel !== "" && $myel !== 0 && $myel === "m1" ) ? "active css-c-e-chs-cl_hover" : ""; ?>" data-action="rh_dislk" data-zr="rh_dislk" data-rev="bk_dislk" data-target="post-accp-myl-id<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" data-xc="arp" title="{wos/deco:_eval_dslk}" href="javascript:;" role="button"></a>
                                    </span>
                                <?php
                                    //[DEPUIS 07-07-16]
                                    elseif ( $article["isod"] && $article["isrtd"] ) :
                                ?>
                                    <span class="css-csam-eval-chs-wrp">
                                        <span class="evlbx-ch-nb jb-evlbx-ch-nb" data-scp="cl"><?php echo $evals[1]; ?></span>
                                        <a class="jb-csam-eval-choices jb-csam-eval-cool css-csam-eval-chs css-c-e-chs-cl <?php echo ( $myel !== "" && $myel !== 0 && $myel === "p1" ) ? "active css-c-e-chs-cl_hover" : ""; ?>" data-action="rh_cool" data-zr="rh_cool" data-rev="bk_cool" data-target="post-accp-myl-id<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" data-xc="arp" title="{wos/deco:_eval_cool}" href="javascript:;" role="button"></a>
                                    </span>
                                <?php
                                    else :
                                ?>
                                    <span class="css-csam-eval-chs-wrp">
                                        <span class="evlbx-ch-nb jb-evlbx-ch-nb" data-scp="scl"><?php echo $evals[0]; ?></span>
                                        <a class="jb-irr css-csam-eval-chs css-c-e-chs-scl <?php echo ( $myel !== "" && $myel !== 0 && $myel === "p2" ) ? "active css-c-e-chs-scl_hover" : ""; ?>" data-action="bk_spcl" data-zr="rh_spcl" data-rev="rh_spcl" data-target="post-accp-myl-id<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" data-xc="arp" title="{wos/deco:_eval_spcool}" href="javascript:;" role="button"></a>
                                    </span>
                                    <span class="css-csam-eval-chs-wrp">
                                        <span class="evlbx-ch-nb jb-evlbx-ch-nb" data-scp="cl"><?php echo $evals[1]; ?></span>
                                        <a class="jb-irr css-csam-eval-chs css-c-e-chs-cl <?php echo ( $myel !== "" && $myel !== 0 && $myel === "p1" ) ? "active css-c-e-chs-cl_hover" : ""; ?>" data-action="rh_cool" data-zr="rh_cool" data-rev="bk_cool" data-target="post-accp-myl-id<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" data-xc="arp" title="{wos/deco:_eval_cool}" href="javascript:;" role="button"></a>
                                    </span>
                                    <span class="css-csam-eval-chs-wrp">
                                        <span class="evlbx-ch-nb jb-evlbx-ch-nb" data-scp="dlk"><?php echo $evals[2]; ?></span>
                                        <a class="jb-irr css-csam-eval-chs css-c-e-chs-dsp <?php echo ( $myel !== "" && $myel !== 0 && $myel === "m1" ) ? "active css-c-e-chs-cl_hover" : ""; ?>" data-action="rh_dislk" data-zr="rh_dislk" data-rev="bk_dislk" data-target="post-accp-myl-id<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" data-xc="arp" title="{wos/deco:_eval_dslk}" href="javascript:;" role="button"></a>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="eval-wait-bar jb-eval-wait-bar this_hide">
                            <div class="eval-wt-bar-pgrs"></div>
                        </div>
                    </div>
                    <div class="action_maximus arp">
                        <a  class='action_a arp' href="javascript:;" role="button"><span class='brain_sp_k'>A</span><span class='brain_sp_action'>ction</span></a>
                        <ul class='action_foll_choices this_hide'>
                            <li><a class='afl_choice get-link-trig' href="javascript:;" role="button" data-action="rp-get-lk" data-target="post-accp-myl-id<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>">Lien permanent</a></li>
                        </ul>
                    </div>
                </div>
                <div class="rich-post-left-list-comnt-new-max">
                    <div class="arp-nw-react-ipt-mx">
                        <?php if ( ( isset($rl) && $rl !== "" && in_array(strtolower($rl), ["xr03","xr13","xr23"]) ) || ( $article["isod"] && $article["isrtd"] ) ) : ?>
                        <textarea class="arp-nw-react-ipt jb-arp-input"></textarea>
                        <a class="arp-nw-react-trg jb-arp-nw-react-trg" data-target="arp-nw-react-ipt" href="javascript:;" role="button">{wos/deco:_Do_it}</a>
                        <!-- [DEPUIS 07-07-16] -->
                        <?php // elseif ( key_exists("isrtd", $article) && $article["isrtd"] === TRUE ) : ?>
<!--                        <textarea class="arp-nw-react-ipt" disabled></textarea>
                        <a class="arp-nw-react-trg" href="javascript:;" role="button">{wos/deco:_Do_it}</a>-->
                        <?php else : ?>
                        <textarea class="arp-nw-react-ipt" disabled></textarea>
                        <a class="arp-nw-react-trg jb-irr" href="javascript:;" role="button">{wos/deco:_Do_it}</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="arp-list-rct-mx jb-arp-list-rct-max">
                    <span class="arp-lst-rct-spnr-mx jb-arp-lst-rct-spnr-mx"><i class="fa fa-refresh fa-spin"></i></span>
                    <span class="arp-lst-rct-none-mx jb-arp-lst-rct-none-mx this_hide">
                        <i class="fa fa-exclamation-circle"></i>
                        <span class="arp-lst-rct-none-txt">Aucun commentaire</span>
                    </span>
                    <div class="arp-lst-rct-pri-mx jb-arp-lst-rct-pri-mx this_hide">
                        <div class="arp-lst-rct-pri-lgo"></div>
                        <div class="arp-lst-rct-pri-txt">Privé</div>
                    </div>
                </div>
                <div>
                    <p class="arp-left-lst-rct-cn"><span class="arp_nb_reacts"><?php echo ( isset($article) && key_exists("rnb", $article ) ) ? $article["rnb"] : ''; ?></span>&nbsp;{wos/deco:_comments1}</p>
                </div>
            </div>
        </div>
        <div class="rich-unik-acclist-child rich-unik-acclist-right">
            <div class="arp-bot-img jb-arp-bot-img">
                <?php 
                    if ( isset($article) && key_exists("vidu", $article ) && $article["vidu"] ) : 
                        preg_match('/^[\s\S]+\.([\w]{3,4})\?fmat=([\d]+)x([\d]+)\&dur=([\d]{1,2})$/', $article["vidu"], $matches);
                        $type = $matches[1];
                        $width = intval($matches[2]);
                        $height = intval($matches[3]);
                        $duration = intval($matches[4]);
                        $isloop = ( $duration <= 10 ) ? TRUE : FALSE;
                        $ref = 550;
                        
                        if ( $width/$height === 1 ) {
                            $width = $ref;
                            $height = $ref;
                        } else {
                            $ratio = ( $width >= $height ) ? $width/$ref : $height/$ref ;
                            $width = $width/$ratio;
                            $height = $height/$ratio;
                        }
                        
                ?>
                <span class="arp-bot-img-fade vidu jb-arp-bot-img-fade">
                    <a class="arp-bot-img-lnch-vid jb-arp-bot-img-lnch-vid paused" data-action="vid-play"></a>
                </span>
                <div class="arp-bot-img-vid-mx jb-arp-bot-img-vid-mx">
                    <video class="arp-bot-img-vid jb-arp-bot-img-vid" height="<?php echo $height; ?>" width="<?php echo $width; ?>" src="<?php echo $article["vidu"]; ?>" preload="auto" <?php echo ( $isloop === TRUE ) ? "loop" : ""; ?> >
                        Impossible de charger la vidéo. 
                    </video>
                </div>
                <?php else : ?>
                <span class="arp-bot-img-fade jb-arp-bot-img-fade"></span>
                <img class="arp-bot-img-img" height="550" width="550" src="<?php echo ( isset($article) && key_exists("img", $article ) ) ? $article["img"] : ''; ?>"/>
                <?php endif; ?>
                
                <span class="kxlib_tgspy arp-bot-img-time jb-arp-bot-img-time" data-tgs-crd='<?php echo ( isset($article) && key_exists("time", $article ) ) ? $article["time"] : ''; ?>' data-tgs-dd-atn='' data-tgs-dd-uut=''>
                    <span class='tgs-frm'></span>
                    <span class='tgs-val'></span>
                    <span class='tgs-uni'></span>
                </span>
                <a class="arp-bot-img-fmr jb-arp-bot-img-fmr" data-target="post-accp-myl-id<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" href="javascript:;" >&times;</a>
                <a class="tmlnr-arp-art-clz-all jb-tmlnr-arp-art-clz-all" data-action='close_all' href="javascript:;">Tout Fermer</a>
                <div class="arp-bot-img-desc jb-arp-art-desc">
                    <a class="arp-bot-img-desc-move jb-arp-bot-img-desc-move" data-action="adesc_move_up" href="javascript:;"></a>
                    <a class="arp-bot-img-desc-move jb-arp-bot-img-desc-move" data-action="adesc_move_down" href="javascript:;"></a>
                    <span class="jb-arp-art-desc-txt">
                        <?php echo ( isset($article) && key_exists("msg", $article ) ) ? $article["msg"] : ''; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="arp-prmlk-sprt jb-arp-prmlk-sprt this_hide">
            <div class="rich-post-get-link-max jb-arp-prmlk-mx">
                <div class="rich-post-get-link-header">
                    <span class="rich-post-get-link-header-text">A partager sans modération avec qui vous voulez, où vous voulez !</span>
                    <a class="arp-prmlk-cloz jb-arp-prmlk-cloz" href="javascript:;" role="button">&times;</a>
                    <!--<a class="rich-post-get-link-header-close jb-arp-prmlk-cloz" href="javascript:;" role="button">X</a>-->
                </div>
                <div class="arp-get-link-ipt-mx jb-arp-pmlk-output-mx">
                    <div>
                        <textarea class="arp-pmlk-output jb-arp-pmlk-output" type="text" value="[%permalink%]" readonly></textarea>
                    </div>
                    <div id="arp-get-link-ipt-ftr">
                        <a id="arp-get-link-ipt-gt" class="jb-arp-get-link-ipt-gt" href="javascript:;" >Aller vers</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>