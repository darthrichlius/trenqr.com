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
            $str__ = "['".implode("'],['", $istgs__)."']";
        } else {
            $str__ = "'".$istgs__[0]."'";
        }
        $str__ = "[$str__]";
        
        $adesc = ( isset($article) && key_exists("msg", $article ) ) ? html_entity_decode($article["msg"]) : '';
    } else {
        $adesc = ( isset($article) && key_exists("msg", $article ) ) ? $article["msg"] : '';
    }
    
?>
<article id="post-accp-tr-id<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" 
     class="feeded_com_bloc_figs sp_intr_figs jb-tmlnr-mdl-intr jb-unq-bind-art-mdl jb-tqr-fav-bind-arml" 
     data-item="<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" 
     data-time="<?php echo ( isset($article) && key_exists("time", $article ) ) ? $article["time"] : ''; ?>" 
     data-tr="<?php echo ( isset($article) && key_exists("trd_eid", $article ) ) ? $article["trd_eid"] : ''; ?>" 
     data-atype="itr"
     data-cache="['<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>','<?php echo ( isset($article) && key_exists("img", $article ) ) ? $article["img"] : ''; ?>','{adesc}','<?php echo ( isset($article) && key_exists("trd_eid", $article ) ) ? $article["trd_eid"] : ''; ?>','{trtle}','<?php echo ( isset($article) && key_exists("rnb", $article ) ) ? $article["rnb"] : ''; ?>','<?php echo ( isset($article) && key_exists("trhref", $article ) ) ? $article["trhref"] : ''; ?>','<?php echo ( isset($article) && key_exists("prmlk", $article ) ) ? $article["prmlk"] : ''; ?>'],['<?php echo ( isset($article) && key_exists("time", $article ) ) ? $article["time"] : ''; ?>','<?php echo ( isset($article) && key_exists("utc", $article ) ) ? $article["utc"] : ''; ?>'],['<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][0] : ''; ?>','<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][1] : ''; ?>','<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][2] : ''; ?>','<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][3] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) && key_exists(0, $article["eval_lt"]) ) ? $article["eval_lt"][0] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) && key_exists(1, $article["eval_lt"]) ) ? $article["eval_lt"][1] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) && key_exists(2, $article["eval_lt"]) ) ? $article["eval_lt"][2] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) && key_exists(3, $article["eval_lt"]) ) ? $article["eval_lt"][3] : ''; ?>'],['<?php echo ( isset($article) && key_exists("ueid", $article ) ) ? $article["ueid"] : ''; ?>','<?php echo ( isset($article) && key_exists("ufn", $article ) ) ? $article["ufn"] : ''; ?>','<?php echo ( isset($article) && key_exists("upsd", $article ) ) ? $article["upsd"] : ''; ?>','<?php echo ( isset($article) && key_exists("uppic", $article ) ) ? $article["uppic"] : ''; ?>','<?php echo ( isset($article) && key_exists("uhref", $article ) ) ? $article["uhref"] : ''; ?>'],['<?php echo ( isset($article) && key_exists("myel", $article ) ) ? $article["myel"] : ''; ?>']"
     data-with="<?php echo $str__; ?>"
     
     data-ajcache='<?php echo htmlspecialchars(json_encode($article),ENT_QUOTES,'UTF-8'); ?>'
     data-vidu="<?php echo ( isset($article) && key_exists("vidu", $article ) && $article["vidu"] ) ? $article["vidu"] : ''; ?>"
     data-hasfv="<?php echo ( isset($article) && key_exists("hasfv", $article ) && $article["hasfv"] ) ? TRUE : FALSE; ?>"
     data-fvtp="<?php echo ( isset($article) && key_exists("fvtp", $article ) && $article["fvtp"] ) ? $article["fvtp"] : ''; ?>"
     data-trq-ver='ajca-v10'
     
     >
    <div class="jb-tqr-cldstrg this_hide">
        <span class="jb-tqr-csg-elt" data-item='adsc'><?php echo $adesc; ?></span>
        <span class="jb-tqr-csg-elt" data-item='trtle'><?php echo ( isset($article) && key_exists("trtitle", $article ) ) ? $article["trtitle"] : ''; ?></span>
    </div>
    <div class="fcb_top this_hide">
        <div class="fcb_intop_time">
            <span class='kxlib_tgspy' data-tgs-crd='<?php echo ( isset($article) && key_exists("time", $article ) ) ? $article["time"] : ''; ?>' data-tgs-dd-atn='' data-tgs-dd-uut=''>
                <span class='tgs-frm'></span>
                <span class='tgs-val'></span>
                <span class='tgs-uni'></span>
            </span>
        </div>
        <div id="tqr-art-actbar-mx" class="jb-tqr-art-abr-mx" data-scp="am-tmlnr-itr">
            <ul id="tqr-art-actbar-lst-mx">
                <li class="tqr-art-actbar-l-elt">
                    <a class="tqr-art-actbar-tgr jb-tqr-art-abr-tgr" data-css="favorite" data-action="favorite" data-state="" title="Mettre en favoris"></a>
                    <div id="tqr-art-actbar-fav-chcs" class="jb-tqr-art-abr-fav-chs this_hide">
                        <a class="tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch" data-css="fav_public" data-action="fav_public">Privé</a>
                        <a class="tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch" data-css="fav_private" data-action="fav_private">Public</a>
                    </div>
                </li>
                <!-- ONLY FOR OWNER -->
                <li class="tqr-art-actbar-l-elt"><a class="tqr-art-actbar-tgr jb-tqr-art-abr-tgr" data-css="download" data-action="download" title="Télécharger la photo"></a></li>
                <li class="tqr-art-actbar-l-elt"><a class="tqr-art-actbar-tgr jb-tqr-art-abr-tgr" data-css="report" data-action="report" title="Signaler la publication"></a></li>
            </ul>
        </div>
<!--        <div class='fcb_intop_left'>
            <span class="fcb_intop_in">in</span>
            <span class="fcb_intop_wa">TREND</span>
        </div>-->
    </div>
    <div class="fcb_img_maximus">
        <div class="fcb_img">
            <?php 
                if ( isset($article) && key_exists("img", $article ) && !empty($article["img"]) ) : 
                    $aimg = $article["img"];
            ?>
                <!-- 
                    [DEPUIS 07-11-15] @author BOR
                    Les caracères étaient mal encodés
                -->
                <img class="fcb_img_img" height="370" width="370" src="<?php echo $aimg; ?>" alt="<?php echo html_entity_decode($article["msg"]); ?>"/>
            <?php else : ?>
                <span></span>
            <?php endif; ?>
        </div>
        
        <?php if ( isset($article) && key_exists("vidu", $article ) && $article["vidu"] && $article["vidu"] ) : ?>
        <span class="soft_fade vidu">
        <?php else : ?>
        <span class="soft_fade">
        <?php endif; ?>
            <div class="tqr-artml-tmonhvr jb-tqr-artml-onhvr-tm this_hide" data-atype="tmlnr"><?php echo ( isset($article) && key_exists("time", $article ) && isset($article["time"]) ) ? date("d.m.y",($article["time"]/1000)) : ''; ?></div>
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
        
        <div class="tmlnr_bot_fade">
            <span class="b_f_com b_f_react">
                <span class="jb_b_f_rlib b_f_rLib" style="background: url('{wos/sysdir:img_dir_uri}/r/r3.png?v={wos/systx:now}') no-repeat;"></span>
                <span class="jb_b_f_rnb b_f_rNb jb-unq-react"><?php echo ( isset($article) && key_exists("rnb", $article ) ) ? $article["rnb"] : ''; ?></span>
            </span>
            <span class="b_f_com b_f_eval">
                <span class="jb_b_f_enb b_f_eNb jb-csam-eval-oput" data-cache='<?php echo ( isset($article) && key_exists("id", $article ) ) ? '['.$article["id"].']' : '[,,,]'; ?>'>
                    <span class="jb_b_f_elib b_f_eLib"><?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][3] : ''; ?></span>
                </span>
            </span>
        </div>
    </div>
    <div class="fcb_bottom">
        <p class="fcb_b_title overflow_txt" data-tr="<?php echo ( isset($article) && key_exists("trd_eid", $article ) ) ? $article["trd_eid"] : ''; ?>" title="<?php echo ( isset($article) && key_exists("trtitle", $article ) ) ? $article["trtitle"] : ''; ?>">
            <a class="fcb_b_ttl_lk" href="<?php echo ( isset($article) && key_exists("trhref", $article ) ) ? $article["trhref"] : ''; ?>"><?php echo ( isset($article) && key_exists("trtitle", $article ) ) ? $article["trtitle"] : ''; ?></a>
        </p>
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
</article>