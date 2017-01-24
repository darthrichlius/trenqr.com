<?php
    $imacn = "{wos/datx:iml_articles_count}";
    $itacn = "{wos/datx:itr_articles_count}";
    $imacn = ( $imacn && is_int(intval($imacn)) ) ? intval($imacn) : 0;
    $itacn = ( $itacn && is_int(intval($itacn)) ) ? intval($itacn) : 0;
?>

<section id="tmlnr-pgfv-screen" class="jb-tmlnr-pgfv-scrn clearfix <?php echo ( $pgvr === "wu" && $sector === "ML" ) ? "wlc" : ""; ?>">
    <header id="tmlnr-pgfv-scrn-hdr">
        
    </header>
    <div id="tmlnr-pgfv-scrn-bdy">
        <section class="tmlnr-pgfv-s-b-sctn" data-section="north">
            <div id="tmlnr-pgfv-s-b-sctn-hdr">
                <span id="tmlnr-pgfv-s-b-s-hdr-tle">Mes publications favorites</span>
                <!--<a class="tmlnr-pgfv-hdr-ax-tgr jb-tmlnr-pgfv-hdr-ax-tgr" data-action="wild"></a>-->
            </div>
            <div id="tmlnr-pgfv-s-b-sctn-bdy" class="jb-tmlnr-pgfv-s-b-s-b">
                <div id="tmlnr-pgfv-art-list-bmx" class="jb-mlnr-pgfv-art-list-bmx">
                    <?php for($iii=0;$iii<0;$iii++) :?>
                    <article class="tmlnr-pgfv-art-bmx jb-tmlnr-pgfv-art-bmx" data-item="" data-time="">
                        <header></header>
                        <div>
                            <a class="tmlnr-pgfv-art-i-bmx jb-tmlnr-pgfv-art-i-bmx">
                                <span class="tmlnr-pgfv-art-lck jb-tmlnr-pgfv-art-lck"></span>
                                <span class="tmlnr-pgfv-art-i-fd jb-tmlnr-pgfv-art-i-fd"></span>
                                <img class="tmlnr-pgfv-art-i" width="240" height="240" src="//lorempixel.com/240/240/people/<?php echo rand(1,10); ?>" />
                                <span class="tmlnr-pgfv-art-i-txt jb-tmlnr-pgfv-art-i-txt this_hide">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus quis tristique leo, sit amet metus.</span>
                            </a>
                        </div>
                    </article>
                    <?php endfor; ?>
                    <?php 
                        $articles = NULL;
                        set_error_handler('exceptions_error_handler');
                        try {
                            $t = "{wos/datx:pg_favs_datas}";
                            $articles = unserialize(base64_decode($t));
    //                        $articles = array_reverse($articles);

                            restore_error_handler();
                        } catch (Exception $exc) {
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

                            $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);

                        }
                        /*
                         * [DEPUIS 06-05-16]
                         */
                        $art_exist = ( count($articles) ) ? TRUE : FALSE;
                        
                        foreach ($articles as $k => $article) : 
                            $fldesc = ( isset($article) && key_exists("msg", $article ) ) ? html_entity_decode($article["msg"]) : '';
                            if ( isset($article) && key_exists("msg", $article ) ) {
                               $smldesc = ( strlen(html_entity_decode($article["msg"])) >= 150 ) ? substr(html_entity_decode($article["msg"]),0,150)." ..." : html_entity_decode($article["msg"]);
                            } else {
                                $smldesc = "";
                            }

                            $tp = [
                                "trid"  => ( isset($article) && key_exists("trd_eid", $article ) ) ? $article["trd_eid"] : '',
                                "trtle" => ( isset($article) && key_exists("trtitle", $article ) ) ? $article["trtitle"] : '',
                                "trhrf" => ( isset($article) && key_exists("trhref", $article ) ) ? $article["trhref"] : '',
                            ];
                            $trds = (string)json_encode($tp);
                            
                            $vidu = ( isset($article) && key_exists("vidu", $article ) ) ? $article["vidu"] : ''
                    ?>
                
                    <article id="post-fv-aid-<?php echo $article["id"]; ?>" class="tmlnr-pgfv-art-bmx jb-tmlnr-pgfv-art-bmx jb-unq-bind-art-mdl" 
                    data-item="<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>" 
                    data-time="<?php echo ( isset($article) && key_exists("fvtm", $article ) ) ? $article["fvtm"] : ''; ?>"
                    data-atype="fav"
                    data-cache="['<?php echo ( isset($article) && key_exists("id", $article ) ) ? $article["id"] : ''; ?>','<?php echo ( isset($article) && key_exists("img", $article ) ) ? $article["img"] : ''; ?>','{adesc}','<?php echo ( isset($article) && key_exists("trd_eid", $article ) ) ? $article["trd_eid"] : ''; ?>','{trtle}','<?php echo ( isset($article) && key_exists("rnb", $article ) ) ? $article["rnb"] : ''; ?>','<?php echo ( isset($article) && key_exists("trhref", $article ) ) ? $article["trhref"] : ''; ?>','<?php echo ( isset($article) && key_exists("prmlk", $article ) ) ? $article["prmlk"] : ''; ?>'],['<?php echo ( isset($article) && key_exists("time", $article ) ) ? $article["time"] : ''; ?>','<?php echo ( isset($article) && key_exists("utc", $article ) ) ? $article["utc"] : ''; ?>'],['<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][0] : ''; ?>','<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][1] : ''; ?>','<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][2] : ''; ?>','<?php echo ( !empty($article["eval"]) && count($article["eval"]) == 4 ) ? $article["eval"][3] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) && key_exists(0, $article["eval_lt"]) ) ? $article["eval_lt"][0] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) && key_exists(1, $article["eval_lt"]) ) ? $article["eval_lt"][1] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) && key_exists(2, $article["eval_lt"]) ) ? $article["eval_lt"][2] : ''; ?>','<?php echo ( !empty($article["eval_lt"]) && count($article["eval_lt"]) && key_exists(3, $article["eval_lt"]) ) ? $article["eval_lt"][3] : ''; ?>'],['<?php echo ( isset($article) && key_exists("ueid", $article ) ) ? $article["ueid"] : ''; ?>','<?php echo ( isset($article) && key_exists("ufn", $article ) ) ? $article["ufn"] : ''; ?>','<?php echo ( isset($article) && key_exists("upsd", $article ) ) ? $article["upsd"] : ''; ?>','<?php echo ( isset($article) && key_exists("uppic", $article ) ) ? $article["uppic"] : ''; ?>','<?php echo ( isset($article) && key_exists("uhref", $article ) ) ? $article["uhref"] : ''; ?>'],['<?php echo ( isset($article) && key_exists("myel", $article ) ) ? $article["myel"] : ''; ?>']"
                    data-with="<?php echo $str__; ?>"
                    data-istr="<?php echo ( $article["isrtd"] ) ? true : false; ?>"
                    data-trds="<?php echo htmlspecialchars($trds, ENT_QUOTES, 'UTF-8'); ?>"
                    
                    data-ajcache='<?php echo htmlspecialchars(json_encode($article),ENT_QUOTES,'UTF-8'); ?>'
                    data-hasfv="<?php echo ( isset($article) && key_exists("hasfv", $article ) && $article["hasfv"] ) ? $article["hasfv"] : ''; ?>"
                    data-fvtp="<?php echo $article["fvtp"]; ?>"
                    data-vidu="<?php echo ( isset($article) && key_exists("vidu", $article ) && $article["vidu"] ) ? $article["vidu"] : ''; ?>"
                    data-trq-ver='ajca-v10'
                    data-fvtm="<?php echo ( isset($article) && key_exists("fvtm", $article ) ) ? $article["fvtm"] : ''; ?>"
                    
                    >
                        <header>
                            <span class='css-tgpsy kxlib_tgspy fav' 
                                data-tgs-crd='<?php echo ( isset($article) && key_exists("fvtm", $article ) ) ? $article["fvtm"] : ''; ?>' data-tgs-dd-atn='' data-tgs-dd-uut=''
                                title="Date à laquelle la publication a été mise en favori"
                            >
                                <span class='tgs-frm'></span>
                                <span class='tgs-val'></span>
                                <span class='tgs-uni'></span>
                            </span>
                        </header>
                        <div>
                            <a class="tmlnr-pgfv-art-i-bmx jb-tmlnr-pgfv-art-i-bmx">
                                <?php if ( $article["fvtp"] === "ART_XA_FAV_PRI" ) : ?>
                                <span class="tmlnr-pgfv-art-lck jb-tmlnr-pgfv-art-lck"></span>
                                <?php endif; ?>
                                
                                <?php if ( $vidu ) : ?>
                                <span class="tmlnr-pgfv-art-i-fd jb-tmlnr-pgfv-art-i-fd vidu"></span>
                                <img class="tmlnr-pgfv-art-i" width="240" height="240" src="<?php echo ( isset($article) && key_exists("img", $article ) ) ? $article["img"] : ''; ?>" alt="<?php echo $fldesc; ?>" />
                                <?php else : ?>
                                <span class="tmlnr-pgfv-art-i-fd jb-tmlnr-pgfv-art-i-fd"></span>
                                <img class="tmlnr-pgfv-art-i" width="240" height="240" src="<?php echo ( isset($article) && key_exists("img", $article ) ) ? $article["img"] : ''; ?>" alt="<?php echo $fldesc; ?>" />
                                <?php endif; ?>
                                
                                <span class="tmlnr-pgfv-art-i-txt jb-tmlnr-pgfv-art-i-txt this_hide">
                                    <span class="psd">@<?php echo $article["upsd"]; ?></span><br/>
                                    <span class="desc" data-dsc="<?php echo $fldesc; ?>"><?php echo $smldesc; ?></span>
                                </span>
                            </a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <div id="tmlnr-pgfv-s-b-nne" class="jb-tmlnr-pgfv-s-b-nne <?php echo ( count($articles) ) ? "this_hide" : ""; ?>">
                    Aucune publication disponible ... <br/>pour l'instant !
                </div>
            </div>
        </section>
    </div> 
    <div id="tmlnr-pgfv-scrn-ftr">
        
    </div>
</section>

