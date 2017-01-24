<?php 
    /*
     * [NOTE 29-04-15] @BOR
     * On travaille le texte de description pour qu'il soit correctement encodé surtout au niveau du module UNIQUE.
     */
    $str__ = "[]"; $adesc;
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
        
        $adesc = ( isset($article) && key_exists("msg", $article ) ) ? html_entity_decode($article["msg"]) : '';
    } else {
        $adesc = ( isset($article) && key_exists("msg", $article ) ) ? $article["msg"] : '';
    }
//    var_dump(json_encode($article));
//    $ajcache = htmlspecialchars(json_encode($article),ENT_QUOTES,'UTF-8');
//    var_dump(htmlspecialchars(json_encode($article),ENT_QUOTES,'UTF-8'));
//    exit();
?>
<article id="trpg-art-<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" 
     class="mdl-tr-post-in-list jb-mdl-tr-post-in-list jb-unq-bind-art-mdl this_invi jb-tqr-fav-bind-arml" 
     data-item="<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>"
     data-time="<?php echo ( isset($article) && key_exists("time", $article ) ) ? $article["time"] : ''; ?>" 
     data-tr="<?php echo ( isset($article) && key_exists("trd_eid", $article ) ) ? $article["trd_eid"] : ''; ?>" 
     data-atype="itr" 
     data-cache="['<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>','<?php echo ( isset($article) && key_exists("img", $article ) ) ? $article["img"] : ''; ?>','{adesc}','{wos/datx:trid}','{trtle}','<?php echo ( isset($article) && key_exists("rnb", $article ) ) ? $article["rnb"] : ''; ?>','{wos/datx:trhref}','<?php echo ( isset($article) && key_exists("prmlk", $article ) ) ? $article["prmlk"] : ''; ?>'],['<?php echo ( isset($article) && key_exists("time", $article ) ) ? $article["time"] : ''; ?>',''],['<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][0] : ''; ?>','<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][1] : ''; ?>','<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][2] : ''; ?>','<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][3] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) == 4 && key_exists("upsd",array_values($article["eval_lt"])[0]) ) ? $article["eval_lt"][0]["upsd"] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) == 4 && key_exists("upsd",array_values($article["eval_lt"])[1]) ) ? $article["eval_lt"][1]["upsd"] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) == 4 && key_exists("upsd",array_values($article["eval_lt"])[2]) ) ? $article["eval_lt"][2]["upsd"] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) == 4 && !empty($article["eval_lt"][3]) ) ? $article["eval_lt"][3] : ''; ?>'],['<?php echo ( isset($article) && key_exists("ueid", $article ) ) ? $article["ueid"] : ''; ?>','<?php echo ( isset($article) && key_exists("ufn", $article ) ) ? $article["ufn"] : ''; ?>','<?php echo ( isset($article) && key_exists("upsd", $article ) ) ? $article["upsd"] : ''; ?>','<?php echo ( isset($article) && key_exists("uppic", $article ) ) ? $article["uppic"] : ''; ?>','<?php echo ( isset($article) && key_exists("uhref", $article ) ) ? $article["uhref"] : ''; ?>'],['<?php echo ( isset($article) && key_exists("myel", $article ) ) ? $article["myel"] : ''; ?>']" 
     data-with="<?php echo $str__; ?>" 
     
     data-ajcache='<?php echo htmlspecialchars(json_encode($article),ENT_QUOTES,'UTF-8'); ?>'
     data-hasfv="<?php echo ( isset($article) && key_exists("hasfv", $article ) && $article["hasfv"] ) ? $article["hasfv"] : ''; ?>"
     data-fvtp="<?php echo ( isset($article) && key_exists("fvtp", $article ) && $article["fvtp"] ) ? $article["fvtp"] : ''; ?>"
     data-vidu="<?php echo ( isset($article) && key_exists("vidu", $article ) && $article["vidu"] ) ? $article["vidu"] : ''; ?>"
     data-trq-ver='ajca-v10'
>
    <div class="jb-tqr-cldstrg this_hide">
        <span class="jb-tqr-csg-elt" data-item='adsc'><?php echo $adesc; ?></span>
        <span class="jb-tqr-csg-elt" data-item='trtle'>{wos/datx:trtitle}</span>
    </div>
    <div class="mdl-acc-post-img">
        <div class="fcb_img">
            
            <?php if ( isset($article) && key_exists("vidu", $article ) && $article["vidu"] && $article["vidu"] ) : ?>
                <span href="<?php echo ( isset($article) && key_exists("uhref", $article ) ) ? $article["uhref"] : ''; ?>" class="fcb_img_link vidu">
            <?php else : ?>
                <span href="<?php echo ( isset($article) && key_exists("uhref", $article ) ) ? $article["uhref"] : ''; ?>" class="fcb_img_link">
            <?php endif; ?>
<!--                <a class="tqr-artmdl-shron-tgr cursor-pointer jb-tqr-artmdl-shron-tgr this_hide" data-art-mdl="on_page" data-action="amdl_sharon_fb" title="Partager sur Facebook"></a>
                <a class="tqr-artmdl-shron-tgr cursor-pointer jb-tqr-artmdl-shron-tgr this_hide" data-art-mdl="on_page" data-action="amdl_sharon_twr" title="Partager sur Twitter"></a>-->
                <div class="tqr-artml-tmonhvr jb-tqr-artml-onhvr-tm this_hide" data-atype="trpg">
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

                    <span class="tqr-artmdl-xtras-sep"></span>
                    <a class="tqr-artmdl-shron-tgr cursor-pointer jb-tqr-artmdl-shron-tgr " data-art-mdl="on_page" data-action="amdl_sharon_fb" title="Partager sur Facebook"></a>
                    <a class="tqr-artmdl-shron-tgr cursor-pointer jb-tqr-artmdl-shron-tgr " data-art-mdl="on_page" data-action="amdl_sharon_twr" title="Partager sur Twitter"></a>
                </div>
                <div class="tqr-art-actbar-fav-bmx jb-tqr-art-abr-fav-bmx this_hide">
                    <div class="tqr-art-actbar-fav-chcs">
                        <a class="tqr-art-actbar-fav-ccl cursor-pointer jb-tqr-art-actbar-fav-ccl" data-art-mdl="on_page" data-action="fav_cancel" title="Annuler" role="button"></a>
                        <a class="tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch" data-art-mdl="on_page" data-css="fav_public" data-action="fav_public">Public</a>
                        <a class="tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch" data-art-mdl="on_page" data-css="fav_private" data-action="fav_private">Privé</a>
                    </div>
                </div>
                
            </span>
            <!-- [DEPUIS 10-11-15] Ajout de html_entity_decode() -->
            <img class="fcb_img_img" height="372" width="372"  src="<?php echo ( isset($article) && key_exists("img", $article ) ) ? $article["img"] : ''; ?>" alt="<?php echo html_entity_decode($article["msg"]); ?>"/>
        </div>
         <!--<span class="soft_fade"></span>-->
        <div class="bot_fade">
            <span class="bf_com">
                <span class="bf_comNb jb-unq-react"><?php echo ( isset($article) && key_exists("rnb", $article ) ) ? $article["rnb"] : ''; ?></span>
                <span class="jb_b_f_rlib b_f_rLib" style="background: url('{wos/sysdir:img_dir_uri}/r/r3.png?v={wos/systx:now}') no-repeat;"></span>
            </span>
            <span class="bf_cool jb-csam-eval-oput">
                <span class="bf_cool_nb"><?php echo ( !empty($article["eval"]) && is_array($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][3] : ''; ?></span>
                <!--<span class="bf_cool_ico">c<i>!</i></span>-->
            </span>
        </div>
    </div>
    <p class="mdl-acc-post-txt jb-mdl-acc-post-txt this_invi"><?php echo ( isset($article) && key_exists("msg", $article ) ) ? $article["msg"] : ''; ?></p>
    <div class="mdl-acc-post-bottom map-v1"> 
        <div class="map-specs">
            <!-- 
            <div id="tqr-art-actbar-mx" data-scp="am-trpg-itr">
                <ul id="tqr-art-actbar-lst-mx">
                    <li class="tqr-art-actbar-l-elt">
                        <!-- data-state (ALDY) : Lorsque  
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
            -->
            <span class='css-tgpsy kxlib_tgspy' data-tgs-crd='<?php echo ( isset($article) && key_exists("time", $article ) ) ? $article["time"] : ''; ?>' data-tgs-dd-atn='' data-tgs-dd-uut=''>
                <span class='tgs-frm'></span>
                <span class='tgs-val'></span>
                <span class='tgs-uni'></span>
             </span>
        </div>
        <div class="map-user">
            <div class="map-user-user-max">
                
                <a href="<?php echo ( isset($article) && key_exists("uhref", $article ) ) ? $article["uhref"] : ''; ?>" class="tr_upost_user_owner">
                    <span class="map-user-img-fade"></span>
                    <img class="map-user-img" height="42" width="44" src="<?php echo ( isset($article) && key_exists("uppic", $article ) ) ? $article["uppic"] : ''; ?>" />
                    <span class="map-user-psd"><?php echo ( isset($article) && key_exists("upsd", $article ) ) ? "@".$article["upsd"] : ''; ?></span>
                </a>
                <?php
                 /*
                  * [06-12-14] @author L.C.
                  * J'ai l'impression qu'il y a un problème au niveau de l'insertion des données dans la vue.
                  * De toutes les façons, la fonctionnalité n'est pas fontionnelle à vb1.1412.1.0 
                  * Il faudra surement revoir lorsque cette dernière sera disponible.
                  */
                  if ( isset($article) && key_exists("ucontb", $article) && $article["ucontb"] ) :
                ?>
                <span class="map-user-contrib"><span><?php echo ( isset($article) && key_exists("ucontb", $article ) ) ? $article["ucontb"] : 0; ?><span> {wos/deco:_posts}</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</article>