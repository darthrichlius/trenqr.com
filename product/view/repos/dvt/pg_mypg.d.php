<?php
    $imacn = "{wos/datx:iml_articles_count}";
    $itacn = "{wos/datx:itr_articles_count}";
    $imacn = ( $imacn && is_int(intval($imacn)) ) ? intval($imacn) : 0;
    $itacn = ( $itacn && is_int(intval($itacn)) ) ? intval($itacn) : 0;
?>
<div id="acc_feeded" class="jb-acc_feeded <?php echo ( $pgvr === "wu" && $sector === "ML" ) ? "wlc" : ""; ?>">
        <div id="brain_open">
            <?php 
                if ( $pgvr && strtolower($pgvr) === "ro" ) : 
            ?>
                <a id="brain_open_action" class="jb-brn-opn-tgr cursor-pointer this_hide" role="button">VROOM</a>
            <?php endif; ?>
        </div>
        <div id="feeded_e">
                <!-- <div> -->
            <?php 
                if ( $pgvr && strtolower($pgvr) === "ro" ) : 
            ?>
                <?php
                    $trcct = "{wos/datx:ctw_dsma}";
                    $sk__ = ( isset($trcct) && intval($trcct) === 1 ) ? 1 : 0;
                    
                    $_PFOP_BRAIN_ALWZ_OPN = ( !$prefdcs || (  $prefdcs && is_array($prefdcs) && !$prefdcs["_PFOP_BRAIN_ALWZ_OPN"] ) 
                        || ( $prefdcs && is_array($prefdcs) && $prefdcs["_PFOP_BRAIN_ALWZ_OPN"] && $prefdcs["_PFOP_BRAIN_ALWZ_OPN"]["prfodtp_lib"] === "_DEC_ENA" ) 
                    ) ? TRUE : FALSE;
                    
                ?>
                <div id="brain_maximus" class="jb-tmlnr-bn-maximus <?php echo ( $_PFOP_BRAIN_ALWZ_OPN ) ? "" : "this_hide"; ?>" data-sds="<?php echo ( $_PFOP_BRAIN_ALWZ_OPN ) ? "o" : "c"; ?>" data-isopen="<?php echo ( $_PFOP_BRAIN_ALWZ_OPN ) ? 1 : 0; ?>" data-trcct-skip="<?php echo $sk__; ?>">
                    <span id="opref" class="this_hide"></span>
                    <div id="brain_colored" class="">
                        <div id="brain_ma_modlist" class="brain_m_elmnt this_hide">
                            <p id="brain_ma_modlist_title_maxi">
                                <span id="brain_ma_modlist_title">Menu Title</span>
                                <span id="brain_ma_modlist_back">
                                    <a  class="brn-bk cursor-pointer jb-brn-bk" data-mode="brain_listMenu" role="button" title="{wos/deco:_Back}">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                </span>
                            </p>
                            <ul id="brain_ma_modlist_l">
                                <li><a href="javascript:;">Sample1</a></li>
                                <li><a href="javascript:;">Sample2</a></li>
                                <li><a href="javascript:;">Sample3</a></li>
                            </ul>
                        </div>
                        <div id="brain_listMenu" class="brain_m_elmnt">
                            <div class="brainM_menu">
                                <a id="brain_menu_new-ml" class="brainM_menu_elmnt jb-brain-menu-action cursor-pointer selected" data-action="add-art-iml" data-field="publish_for_friends" data-slave="brain_th-new_ml" data-desc="brn-mn-dsc-nwml" role="button">1. {wos/deco:_brain_New_post}&nbsp;<span class="brain_in">pour</span>&nbsp;mes amis</a>
                            </div>
                            <div class="brainM_menu">
                                <a id="brain_menu_new-sod" class="brainM_menu_elmnt jb-brain-menu-action cursor-pointer" data-action="add-art-sod" data-field="publish_in_today" data-slave="brain_th-new_ml" data-desc="brn-mn-dsc-story" role="button">2. {wos/deco:_brain_New_post}&nbsp;<span class="brain_in">en tant que</span> photo du jour</a>
                            </div>
                            <div class="brainM_menu">
                                <a id="brain_menu_new-ml-tr" class="brainM_menu_elmnt jb-brain-menu-action cursor-pointer" data-action="add-art-itr" data-field="publish_in_trend" data-master="brain_wrh_trends_choices" data-slave="brain_th-notuserontr" data-desc="brn-mn-dsc-nwtr" role="button">3. {wos/deco:_brain_New_post}&nbsp;<span class="brain_in">{wos/deco:_in}</span> un {wos/deco:_intr}</a>
                            </div>
                            <div class="brainM_menu">
                                <a id="brain_menu_new-hstd" class="brainM_menu_elmnt jb-brain-menu-action cursor-pointer" data-action="add-art-hstd" data-field="publish_as_hosted" data-slave="brain_th-new_ml" data-desc="brn-mn-dsc-hosted" role="button">4. {wos/deco:_brain_New_post}&nbsp;<span class="brain_in">en tant que</span> photo hébergée</a>
                            </div>
<!--                            <div class="brainM_menu">
                                <a id="brain_menu_folls" class="brainM_menu_elmnt cursor-pointer" data-slave="brain_th-folls" data-desc="brn-mn-dsc-folrs" role="button">{wos/deco:_brain_See_Folws}&nbsp;<span class="red"></span></a>
                            </div>
                            <div class="brainM_menu">
                                <a id="brain_menu_folgs" class="brainM_menu_elmnt cursor-pointer" data-slave="brain_th-folgs" data-desc="brn-mn-dsc-folgs" role="button">{wos/deco:_brain_See_Folg}&nbsp;<span class="red"></span></a>
                            </div>-->
<!--                            <div class="brainM_menu">
                                <a id="brain_menu_trophz" class="brainM_menu_elmnt jb-brain-menu-action cursor-pointer" data-field="trophies" data-slave="brain_th-trophies" data-desc="brn-mn-dsc-folgs" role="button">Afficher mes Succès</a>
                            </div>-->
                            <div class="brainM_menu">
                                <a id="brain_menu_stgs" class="brainM_menu_elmnt jb-brain-menu-action cursor-pointer" data-field="settings" data-slave="brain_th-settings" data-desc="brn-mn-dsc-folgs" role="button">Paramètres</a>
                            </div>
                        </div>
                        <div id="brain_snitch">
                           {wos/deco:_brain_Intro}
                        </div>
                        <div id="brain_close">
                            <a id="brain_close_link" class="jb-brn-clz-tgr cursor-pointer" >{wos/deco:_Close}</a>
                            <!--<a id="brain_close_link" class="jb-brn-clz-tgr" href="javascript:;">{wos/deco:_Close}</a>-->
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div id="feeded_e_list">
                <?php if ( isset($pgvr) && in_array(strtolower($pgvr),["ro","ru"]) ) :  ?>
                <section class="tqr-lasta-mx jb-tqr-lasta-mx" data-scp="owner">
                    <header class="tqr-lasta-hdr-mx">
                        <div class="tqr-lasta-hdr-tle">Dernières activités de @{wos/datx:oupsd}</div>
                    </header>
                    <div class="tqr-lasta-bdy-mx">
                        <div class="tqr-lasta-arts-none-mx jb-tqr-lasta-as-nn-mx this_hide">
                            <div class="tqr-lasta-arts-none">Aucune activité récente à montrer ... <br/>Pour l'instant !</div>
                        </div>
                        <div class="tqr-lasta-arts-wrap jb-tqr-lasta-arts-wrap">
                            <?php for($aa=0;$aa<0;$aa++) : ?>
                            <article class="tqr-lasta-art-mx <?php echo ( $aa === 2 || $aa === 5 || $aa === 8 ) ? "last" : ""; ?> jb-tqr-lasta-art-mx" data-item="">
                                <a class="tqr-lasta-art-a-mx jb-tqr-lasta-art-a-mx" href="">
                                    <div class="tqr-lasta-art-a-i-mx jb-tqr-lasta-art-a-i-mx">
                                        <img class="tqr-lasta-art-a-i jb-tqr-lasta-art-a-i" width="110" height="110" src="http://www.lorempixel.com/110/110/nature/<?php echo $aa; ?>" />
                                    </div>
                                    <div class="tqr-lasta-art-a-i-fd jb-tqr-lasta-art-a-i-fd" data-type="eval"></div>
                                </a>
                            </article>
                            
                            <?php if ( $aa === 2 || $aa === 5 || $aa === 8 ) : ?>
                            <div class="tqr-lasta-arts-divdr"></div>
                            <?php endif; ?>
                            
                            <?php endfor; ?>
                        </div>
                    </div>
                </section>
                <?php endif;  ?> 
                <?php if ( isset($pgvr) && in_array(strtolower($pgvr),["ro"]) ) :  ?>
                <section class="tqr-lasta-mx jb-tqr-lasta-mx" data-scp="network">
                    <header class="tqr-lasta-hdr-mx">
                        <div class="tqr-lasta-hdr-tle">Dernières activités de mon réseau</div>
                    </header>
                    <div class="tqr-lasta-bdy-mx">
                        <div class="tqr-lasta-arts-none-mx jb-tqr-lasta-as-nn-mx this_hide">
                            <div class="tqr-lasta-arts-none">Aucune activité récente à montrer ... <br/>Pour l'instant !</div>
                        </div>
                        <div class="tqr-lasta-arts-wrap jb-tqr-lasta-arts-wrap">
                            <?php for($aa=0;$aa<0;$aa++) : ?>
                            <article class="tqr-lasta-art-mx <?php echo ( $aa === 2 || $aa === 5 || $aa === 8 ) ? "last" : ""; ?> jb-tqr-lasta-art-mx" data-item="">
                                <a class="tqr-lasta-art-a-mx jb-tqr-lasta-art-a-mx" href="">
                                    <div class="tqr-lasta-art-a-i-mx jb-tqr-lasta-art-a-i-mx">
                                        <img class="tqr-lasta-art-a-i jb-tqr-lasta-art-a-i" width="110" height="110" src="http://www.lorempixel.com/110/110/nature/<?php echo $aa; ?>" />
                                    </div>
                                    <div class="tqr-lasta-art-a-i-fd jb-tqr-lasta-art-a-i-fd" data-type="eval"></div>
                                    <div class="tqr-lasta-art-a-i-ds-mx jb-tqr-lasta-art-a-i-ds-mx this_hide">
                                        <span class="cursor-pointer jb-tqr-lasta-art-a-i-ds" data-href="/@mouna">@Mouna</span>
                                    </div>
                                </a>
                            </article>
                            
                            <?php if ( $aa === 2 || $aa === 5 || $aa === 8 ) : ?>
                            <div class="tqr-lasta-arts-divdr"></div>
                            <?php endif; ?>
                            
                            <?php endfor; ?>
                        </div>
                    </div>
                </section>
                <?php endif;  ?>    
                <div id="feed_e_loadm" class="jb-fd-e-loadm this_hide" >
                    <a id="feed_e_loadm_action" class="jb-fd-e-ldmr-tgr" href="javascript:;" role="button">
                        <span id="f_e_loadm_nb" class="jb-f-e-loadm-nb"></span>
                        <span id="f_e_loadm_text" class="jb-f-e-loadm-txt"></span>
                    </a>
                </div>
                <?php if ( $imacn !== 0 && $itacn === 0 ) :  ?>
                <div class="tmlnr-noone-section-bmx jb-tmlnr-noone-sec-bmx" data-target="e-list">
                    <div class="tmlnr-noone-section-top">
                        <span class="tmlnr-noone-sec-lowbat"></span>
                    </div>
                    <div class="tmlnr-noone-section-btm">
                        <span class="tmlnr-noone-sec-txt">Ah ! Il semblerait qu'il n'y ait rien par ici...<br/>Pour l'instant !</span>
                    </div>
                </div>
                <?php endif; ?>
                <div id="feeded_e_list_list">
                    <?php // $x = unserialize(html_entity_decode($_SESSION["ud_carrier"]["itr.articles"])); foreach ($x as $k => $article) : ?>
                    <?php 
                        $x = NULL;
                        set_error_handler('exceptions_error_handler');
                        try {
                            $t = "{wos/datx:itr_articles}";
                            $x = unserialize(base64_decode($t));
//                            $x = array_reverse($x);

                            restore_error_handler();
                        } catch (Exception $exc) {
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

                            $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);

                        }

                        foreach ($x as $k => $article) : 
                    ?>
                    {wos/dvt:tmlnr_itr}
                    <?php endforeach; ?>
                </div>
            </div>
                
        </div>
        <div id="feeded_w">
            <?php 
                if ( $pgvr && strtolower($pgvr) === "ro" ) : 
            ?>
            {wos/dvt:brainslave}
            <?php endif; ?>
            <!-- 
                Ca sera notre mini Twitter Feed à nous qui permettra plus d'interactivité entre les utilisateurs quand bien même il s'agit d'une plateforme semi-fermée.
                Une API et une page permettra de sortir ces données de la page XYZ. De plus des outils seront disponibles pour ceux qui aimeraient optimiser la gestion des flux générés.
            -->
            <section id="tqr-testy-mx" class="jb-tqr-testy-mx">
                <header id="tqr-testy-hdr-mx" class="jb-tqr-testy-hdr-mx">
                    <div id="tqr-testy-hdr-erbx" class="jb-tqr-testy-hdr-erbx this_hide">
                        <a id="tqr-testy-hdr-err-clz" class="cursor-pointer jb-tqr-testy-hdr-err-clz" data-action="errbox-clz">&times;</a>
                        <div id="tqr-testy-hdr-err-tmx" class="jb-tqr-testy-hdr-err-tmx"></div>
                    </div>
                    <div id="tqr-tsty-add-bmx" class="jb-tqr-tsty-add-bmx this_hide">
                        <div id="tqr-tsty-add-tle-mx">
                            <span id="tqr-tsty-add-tle-lbl" >Quoi de neuf ?</span>
                            <select id="tqr-tsty-add-tle-prems" class="jb-tqr-tsty-add-tle-prems" data-action="tlkb-pre-message">
                                <option value="-1" disabled selected='true'>Quoi de neuf ?</option>
                                <option class="not-now" value="PREMS_BUSY_AT_WORK" >Au travail</option>
                                <option class="not-now" value="PREMS_BUSY">Occupé(e), ne pas déranger svp !</option>
                                <option class="not-now" value="PREMS_BUSY_HOLIDAYS">En vacances, repassez plus tard :)</option>
                                <option value="PREMS_TODAY_I_WANT">Aujourd'hui, j'ai envie</option>
                                <option value="PREMS_FEDUP">J'en ai marre 😩😠</option>
                                <option value="PREMS_HBD">Joyeux anniversaire 🎉</option>
                                <option value="PREMS_LOVE_YA">Je t'aime ❤</option>
                                <option value="PREMS_WHAT_IF">Et si ...</option>
                                <option value="PREMS_IMAGINE">Imaginons</option>
                                <option value="PREMS_WAIT_A_MINUTE">Pas si vite !</option>
                                <option value="PREMS_I_REALLY_NEED">J'ai envie de</option>
                                <option value="PREMS_DID_U_KNOW">Tu savais que</option>
                                <option value="PREMS_TRENQR_N_CHILL">Trenqr and Chill, ça te dirait ? 😏</option>
                            </select>
                        </div>
                        <?php if ( $pgvr && strtolower($pgvr) === "ro" ) : ?>
                        <textarea id="tqr-tsty-add-txtarea" class="jb-tqr-tsty-art-txar" placeholder="Exprimez-vous"></textarea>
                        <?php else : ?>
                        <textarea id="tqr-tsty-add-txtarea" class="jb-tqr-tsty-art-txar" placeholder="Ecrivez un témoignage à propos de @{wos/datx:oupsd} ou laissez un message"></textarea>
                        <?php endif; ?>
                        <div id="tqr-tsty-add-otps-mx">
                            <?php if ( $pgvr && strtolower($pgvr) === "ro" ) : ?>
                            <a class="tqr-tsty-add-otp config cursor-pointer jb-tqr-tsty-add-otp" data-action="configure_access" title="Modifier les paramètres"></a>
                            <?php endif; ?>
                            <button class="tqr-tsty-add-otp add cursor-pointer jb-tqr-tsty-add-otp" data-action="post-add">Ajouter</button>
                        </div>
                    </div>
                </header>
                <div id="tqr-testy-bdy-mx" class="jb-tqr-testy-bdy-mx">
                    <?php if ( isset($pgvr) && strtolower($pgvr) === "ro" ) : ?>
                    <div id="tqr-testy-config-mx" class="jb-tqr-testy-config-mx this_hide" data-inis="">
                        <div id="tqr-tsty-cfg-wt-pnl" class="jb-tqr-tsty-cfg-wt-pnl"></div>
                        <section class="tqr-tsty-cfg-sctn jb-tqr-tsty-cfg-sctn" data-scp="ini-write-auth">
                            <div class="tqr-tsty-cfg-tle">Qui peut me laisser un mot ?</div>
                            <ul class="tqr-tsty-cfg-lst">
                                <li class="tqr-tsty-cfg-ini-mx jb-tqr-tsty-cfg-ini-mx">
                                    <label class="tqr-tsty-cfg-ini-lbl">
                                        <input class="tqr-tsty-cfg-ini jb-tqr-tsty-cfg-ini" data-action="ini-wrt-for-frd-n-flwr" type="radio" name="ini-write-auth" value="ONLY_FRD_N_FLWR" />
                                        <!-- "conseillé" Permet de confirmé l'aspect OPEN_TO_STRANGERS -->
                                        <span class="tqr-tsty-cfg-ini-txt" >Mes amis et mes abonnés (conseillé)</span>
                                    </label>
                                </li>
                                <li class="tqr-tsty-cfg-ini-mx jb-tqr-tsty-cfg-ini-mx">
                                    <label class="tqr-tsty-cfg-ini-lbl">
                                        <input class="tqr-tsty-cfg-ini jb-tqr-tsty-cfg-ini" data-action="ini-wrt-for-frd" type="radio" name="ini-write-auth" value="ONLY_FRD" />
                                        <span class="tqr-tsty-cfg-ini-txt" >Mes amis</span>
                                    </label>
                                </li>
                                <!-- 
                                    HUMOUR, INCITATION
                                    L'activation de cette option, vous êtes susceptible de subit une campagne de desinformation, harcelement, diabolisation, aliénation, dénonation, démarcharge, sorcellerie et pleins d'autres choses pas jojo.
                                    Voulez vous quand meme l'activé ?
                                -->
                                <li class="tqr-tsty-cfg-ini-mx jb-tqr-tsty-cfg-ini-mx">
                                    <label class="tqr-tsty-cfg-ini-lbl">
                                        <input class="tqr-tsty-cfg-ini jb-tqr-tsty-cfg-ini" data-action="ini-wrt-for-all" type="radio" name="ini-write-auth" value="EVRBDY" />
                                        <span class="tqr-tsty-cfg-ini-txt">Tout le monde (Trenqr Inside)</span>
                                    </label>
                                </li>
                            </ul>
                        </section>
                        <section class="tqr-tsty-cfg-sctn jb-tqr-tsty-cfg-sctn" data-scp="ini-write-deny">
                            <div class="tqr-tsty-cfg-tle">Qui ne peut pas me laisser un mot ?</div>
                            <ul class="tqr-tsty-cfg-lst">
                                <li class="tqr-tsty-cfg-ini-mx jb-tqr-tsty-cfg-ini-mx">
                                    <input class="tqr-tsty-cfg-ini-ipt jb-tqr-tsty-cfg-ini-ipt" data-scp="ini-write-deny" placeholder="Rentrez le pseudonyme" type="text" />
                                    <a class="tqr-tsty-cfg-ini-ipt-add cursor-pointer jb-tqr-tsty-cfg-ini-ipt-add" data-action="ini-write-deny-add">Ajouter</a>
                                </li>
                                <li class="tqr-tsty-cfg-ini-mx jb-tqr-tsty-cfg-ini-mx" >
                                    <ul id="tqr-tsty-cfg-ini-usr-lsts" class="jb-tqr-tsty-cfg-ini-usr-lsts">
                                        <?php for( $nn=0;$nn<0;$nn++ ) : ?>
                                        <li class="tqr-tsty-cfg-ini-uz-l-mx jb-tqr-tsty-cfg-ini-uz-l-mx" data-item="">
                                            <span class="tqr-tsty-cfg-ini-uz-l">
                                                <a class="tqr-tsty-cfg-ini-uhrf jb-tqr-tsty-cfg-ini-uhrf" href="">@Pseudo<?php echo 1; ?></a>
                                                <a class="tqr-tsty-cfg-ini-rmv cursor-pointer jb-qr-tsty-cfg-ini-rmv" data-action="ini-write-deny-rmv" title="Retirer de la liste">&times;</a>
                                            </span>
                                        </li>
                                        <?php endfor; ?>
                                    </ul>
                                </li>
                            </ul>
                        </section>
                        <section class="tqr-tsty-cfg-sctn jb-tqr-tsty-cfg-sctn" data-scp="ini-read-auth">
                            <div class="tqr-tsty-cfg-tle">Qui peut voir mes mots ?</div>
                            <ul class="tqr-tsty-cfg-lst">
                                <li class="tqr-tsty-cfg-ini-mx jb-tqr-tsty-cfg-ini-mx">
                                    <label class="tqr-tsty-cfg-ini-lbl">
                                        <input class="tqr-tsty-cfg-ini jb-tqr-tsty-cfg-ini" data-action="ini-rd-for-all" type="radio" name="ini-read-auth" value="EVRBDY" />
                                        <span class="tqr-tsty-cfg-ini-txt">Tout le monde</span>
                                    </label>
                                </li>
                                <li class="tqr-tsty-cfg-ini-mx jb-tqr-tsty-cfg-ini-mx">
                                    <label class="tqr-tsty-cfg-ini-lbl">
                                        <input class="tqr-tsty-cfg-ini jb-tqr-tsty-cfg-ini" data-action="ini-rd-for-inside" type="radio" name="ini-read-auth" value="TQR_INSD" />
                                        <span class="tqr-tsty-cfg-ini-txt">Seulement ceux connectés</span>
                                    </label>
                                </li>
                            </ul>
                        </section>
                        <section class="tqr-tsty-cfg-sctn jb-tqr-tsty-cfg-sctn" data-scp="ini-read-deny">
                            <div class="tqr-tsty-cfg-tle">Qui ne peut pas voir mes mots ?</div>
                            <ul class="tqr-tsty-cfg-lst">
                                <li class="tqr-tsty-cfg-ini-mx jb-tqr-tsty-cfg-ini-mx">
                                    <input class="tqr-tsty-cfg-ini-ipt jb-tqr-tsty-cfg-ini-ipt" data-scp="ini-read-deny" placeholder="Rentrez le pseudonyme" type="text" />
                                    <a class="tqr-tsty-cfg-ini-ipt-add cursor-pointer jb-tqr-tsty-cfg-ini-ipt-add" data-action="ini-read-deny-add">Ajouter</a>
                                </li>
                                <li class="tqr-tsty-cfg-ini-mx jb-tqr-tsty-cfg-ini-mx" >
                                    <ul id="tqr-tsty-cfg-ini-usr-lsts" class="jb-tqr-tsty-cfg-ini-usr-lsts">
                                        <?php for( $nn=0;$nn<0;$nn++ ) : ?>
                                        <li class="tqr-tsty-cfg-ini-uz-l-mx jb-tqr-tsty-cfg-ini-uz-l-mx" data-item="">
                                            <span class="tqr-tsty-cfg-ini-uz-l">
                                                <a class="tqr-tsty-cfg-ini-uhrf jb-tqr-tsty-cfg-ini-uhrf" href="">@Pseudo<?php echo $nn; ?></a>
                                                <a class="tqr-tsty-cfg-ini-rmv cursor-pointer jb-qr-tsty-cfg-ini-rmv" data-action="ini-read-deny-rmv" title="Retirer de la liste">&times;</a>
                                            </span>
                                        </li>
                                        <?php endfor; ?>
                                    </ul>
                                </li>
                            </ul>
                        </section>
                        <section class="tqr-tsty-cfg-sctn jb-tqr-tsty-cfg-sctn" data-scp="ini-fnl-oper">
                            <div id="tqr-tsty-c-i-fnl-opr-mx">
                                <a class="tqr-tsty-c-i-fnl-opr cursor-pointer jb-tqr-tsty-c-i-fnl-opr" data-action="configure_reset">Annuler</a>
                                <a class="tqr-tsty-c-i-fnl-opr cursor-pointer jb-tqr-tsty-c-i-fnl-opr" data-action="configure_save">Enregistrer</a>
                            </div>
                        </section>
                    </div>
                    <?php endif; ?>
                    <div id="tqr-testy-none-mx" class="jb-tqr-testy-none-mx this_hide">
                        <div id="">Aucun mot ajouté ...<br/> Pour l'instant !</div>
                    </div>
                    <div id="tqr-tsty-art-lsts" class="jb-tqr-tsty-art-lsts">
                        <div id="tqr-tsty-art-ls-arts" class="jb-tqr-tsty-art-ls-arts">
                        <?php for ($ii=0;$ii<0;$ii++) : ?>
                            <article class="tqr-tsty-art-bmx jb-tqr-tsty-art-bmx jb-tbv-bind-art-mdl" data-item="$ii" data-user="{i:id,f:fullname,p:pseudo,}" data-time="" >
                                <div class="tqr-tsty-art-mx">
                                    <header class="tqr-tsty-art-hdr-mx">
                                        <a class="tqr-tsty-art-userbx jb-tqr-tsty-art-userbx" href="" title="Nom compte (@Pseudo)">
                                            <span class="tqr-tsty-art-ubx-i jb-tqr-tsty-art-ubx-i" style="background: url('http://www.lorempixel.com/25/25/people/<?php echo $ii; ?>') no-repeat;"></span>
                                            <span class="tqr-tsty-art-ubx-psd jb-tqr-tsty-art-ubx-psd">@Pseudo</span>
                                        </a>
                                        <span class="kxlib_tgspy tsty-tm" data-tgs-crd='' data-tgs-dd-atn='' data-tgs-dd-uut=''>
                                            <span class='tgs-frm'></span>
                                            <span class='tgs-val'></span>
                                            <span class='tgs-uni'></span>
                                        </span>
                                        <span id="tqr-tsty-art-pin" class="jb-tqr-tsty-art-pin">Épinglé</span>
                                    </header>
                                    <div class="tqr-tsty-art-bdy-mx">
                                        <div class="tqr-tsty-art-bdy-txt">
                                            <span class="tqr-tsty-art-bdy-t-txt jb-tqr-tsty-art-bdy-t-t">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam massa ipsum, auctor id ornare quis, molestie non leo. Ut quis varius diam, eu posuere.</span>
                                            <a class="tqr-tsty-art-bdy-t-mr cursor-pointer jb-tqr-tsty-art-bdy-t-mr">... Afficher plus</a>
                                        </div>
                                    </div>
                                    <div class="tqr-tsty-art-ftr-bmx">
                                        <div class="tqr-tsty-art-ftr-mx jb-tqr-tsty-art-ftr-mx" data-scp="opt-start">
                                            <div class="tqr-tsty-art-opt-l">
                                                <a class="tqr-tsty-art-opt cursor-pointer like jb-tqr-tsty-art-opt" data-state="">0</a>
                                                <a class="tqr-tsty-art-opt cursor-pointer react jb-tqr-tsty-art-opt" data-state="">0</a>
                                            </div>
                                            <div class="tqr-tsty-art-opt-r">
                                                <button class="tqr-tsty-art-opt cursor-pointer gotopt jb-tqr-tsty-art-opt" data-action="post-gotopt"></button>
                                                <a class="tqr-tsty-art-opt cursor-pointer pin jb-tqr-tsty-art-opt this_hide" data-action="post-pin-start" data-actrvs="post-unpin-start" data-txrvs="Détacher">Epingler</a>
                                                <a class="tqr-tsty-art-opt cursor-pointer delete jb-tqr-tsty-art-opt this_hide" data-action="post-del-start">Supprimer</a>
                                            </div>
                                        </div>
                                        <div class="tqr-tsty-art-ftr-mx jb-tqr-tsty-art-ftr-mx this_hide" data-scp="opt-final">
                                            <span class="tqr-tsty-a-fnl-lbl jb-tqr-tsty-a-fnl-lbl">Etes-vous sur ? <span class="purpose"></span></span>
                                            <a class="tqr-tsty-a-fnl-opt cursor-pointer _y jb-tqr-tsty-a-fnl-opt" data-action="">Oui</a>
                                            <a class="tqr-tsty-a-fnl-opt cursor-pointer _n jb-tqr-tsty-a-fnl-opt" data-action="">Non</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <?php endfor; ?>
                        </div>
                        <div id="tqr-tsty-art-l-ftr">
                            <a id="tqr-tsty-art-ldmr" class="cursor-pointer jb-tqr-tsty-art-ldmr this_hide" data-action="post-loadoldr">
                                <span>Charger en plus</span>
                            </a>
                            <span class="tqr-tsty-art-ldmr-wt this_hide"></span>
                            <span id="tqr-tsty-art-ldmr-end" class="jb-tqr-tsty-art-ldmr-end this_hide">C'est tout ce qu'il y a</span>
                        </div>
                    </div>
                </div>
                <footer id="tqr-testy-ftr-mx">
                    <div>
                        
                    </div>
                </footer>
            </section>
            <div id="feeded_w_list">
                <div id="feed_w_loadm" class="jb-fd-w-loadm this_hide" >
                    <a id="feed_w_loadm_action" class="jb-fd-w-ldmr-tgr" href="javascript:;" role="button">
                        <span id="f_w_loadm_nb" class="jb-f-w-loadm-nb"></span>
                        <span id="f_w_loadm_text" class="jb-f-w-loadm-txt"></span>
                    </a>
                </div>
                <?php if ( $imacn === 0 && $itacn !== 0 ) :  ?>
                <div class="tmlnr-noone-section-bmx jb-tmlnr-noone-sec-bmx" data-target="w-list">
                    <div class="tmlnr-noone-section-top">
                        <span class="tmlnr-noone-sec-lowbat"></span>
                    </div>
                    <div class="tmlnr-noone-section-btm">
                        <span class="tmlnr-noone-sec-txt">Ah ! Il semblerait qu'il n'y ait rien par ici...<br/>Pour l'instant !</span>
                    </div>
                </div>
                <?php endif; ?>
                <div id="feeded_w_list_list" class="tmlnr-pg-col">
                    <?php // $x = unserialize(html_entity_decode($_SESSION["ud_carrier"]["iml_articles"])); foreach ($x as $k => $article) : ?>
                    <?php 
                        $x = NULL;
                        set_error_handler('exceptions_error_handler');
                        try {
                            $t = "{wos/datx:iml_articles}";
                            $x = unserialize(base64_decode($t));
//                                            $x = array_reverse($x);

                            restore_error_handler();

                        } catch (Exception $exc) {
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');

                            $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
                        }
//                        var_dump(__LINE__,__FILE__,$urel,$pgvr);
                        foreach ($x as $k => $article) : 
//                            var_dump(__LINE__,__FILE__,$article);
                    ?>
                        
                        <?php if ( isset($pgvr) && strtolower($pgvr) === "ro" ) : ?>
                            {wos/dvt:tmlnr_iml_fa}
                        <?php // elseif ( ( isset($pgvr) && strtolower($pgvr) === "ru" ) && ( isset($urel) && in_array(strtolower($urel),["xr03","xr13","xr23"]) ) ) : ?>
                        <!-- [DEPUIS 19-04-16] -->
                        <?php // elseif ( ( isset($pgvr) && strtolower($pgvr) === "ru" ) && ( isset($urel) && in_array(strtolower($urel),["xr03","xr13","xr23","xr02","xr12","xr22"]) ) ) : ?>
                        <?php elseif ( 
                                ( ( isset($pgvr) && strtolower($pgvr) === "ru" ) && ( isset($urel) && in_array(strtolower($urel),["xr03","xr13","xr23"]) ) ) 
                                || ( $article["isod"] === TRUE ) 
                            ) : 
                        ?>
                            {wos/dvt:tmlnr_iml_ltd}
                        <?php else : ?>
                            {wos/dvt:tmlnr_iml_lck}
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    
    <div id="where_have_you_been" class="jb-whub-mx this_hide">
        <p id="whub_txt">
            {wos/deco:_art_noone}
        </p>
    </div>

</div>

