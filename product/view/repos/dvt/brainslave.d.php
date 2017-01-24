
<?php

    $_PFOP_BRAIN_ALWZ_OPN = ( !$prefdcs || (  $prefdcs && is_array($prefdcs) && !$prefdcs["_PFOP_BRAIN_ALWZ_OPN"] ) 
        || ( $prefdcs && is_array($prefdcs) && $prefdcs["_PFOP_BRAIN_ALWZ_OPN"] && $prefdcs["_PFOP_BRAIN_ALWZ_OPN"]["prfodtp_lib"] === "_DEC_ENA" ) 
    ) ? TRUE : FALSE;
    
?>

<div id="slave_maximus" class="jb-tmlnr-bn-slv-mxmus <?php echo ( $_PFOP_BRAIN_ALWZ_OPN ) ? "" : "this_hide"; ?>" data-isopen="<?php echo ( $_PFOP_BRAIN_ALWZ_OPN ) ? 1 : 0; ?>">
    <!------------------------------------------------------------------------ -->
    <!------------------------------- WAREHOUSE -------------------------------- -->
    <div id="brain_wrh_menu_desc" class="jb-brn-mn-dsc-txt-wpr this_hide">
        <span class="jb-brn-mn-dsc-txt" data-cd="brn-mn-dsc-gen">{wos/deco:_brain_Intro}</span>
        <span class="jb-brn-mn-dsc-txt" data-cd="brn-mn-dsc-nwml">{wos/deco:_brain_thnpost_desc}</span>
        <span class="jb-brn-mn-dsc-txt" data-cd="brn-mn-dsc-story">{wos/deco:_brain_story_desc}</span>
        <span class="jb-brn-mn-dsc-txt" data-cd="brn-mn-dsc-hosted">{wos/deco:_brain_hosted_desc}</span>
        <span class="jb-brn-mn-dsc-txt" data-cd="brn-mn-dsc-nwtr">{wos/deco:_brain_thnwtr_desc}</span>
        <span class="jb-brn-mn-dsc-txt" data-cd="brn-mn-dsc-folrs">{wos/deco:_brain_thfolws_desc}</span>
        <span class="jb-brn-mn-dsc-txt" data-cd="brn-mn-dsc-folgs">{wos/deco:_brain_thfolg_desc}</span>
<!--        <p id="brain_desc-gen">{wos/deco:_brain_Intro}</p>
        <p id="brain_desc-new_ml">{wos/deco:_brain_thnpost_desc}</p>
        <p id="brain_desc-folls">{wos/deco:_brain_thfolws_desc}</p>
        <p id="brain_desc-folgs">{wos/deco:_brain_thfolg_desc}</p>-->
    </div>

    <ul id="brain_wrh_trends_choices" class="this_hide" data-title="Que voulez-vous faire ?">
        <li><a id="brain_submenu_mytrch" class="brainM_submenu_elmnt jb-brain-menu-action cursor-pointer" data-action="add-art-itr" data-slave="brain_th-mytrch" role="button">Ajouter dans une de mes Salons</a></li>
        <li><a id="brain_submenu_follgtrch" class="brainM_submenu_elmnt jb-brain-menu-action cursor-pointer" data-action="add-art-itr" data-slave="brain_th-follgtrch" role="button">Ajouter dans une Salon que je suis</a></li>
        <li><a id="brain_submenu_newtr" class="brainM_submenu_elmnt cursor-pointer" data-slave="brain_th-newtr" data-ext="reset_tr_btn" role="button">Créer un Salon</a></li>
    </ul>
    <!-- ------------------------------- WAREHOUSE -------------------------------- -->
    <!-- -------------------------------------------------------------------------- -->

    <!-- ********************************************************************************************* -->
    <!-- **************************************** THEME START ****************************************  -->
    
    <!-- Ajouter .pod pour indiquer le cas de la Photo Du Jour -->
    <div id="brain_th-new_ml" class="brain-th-com brain_focus" data-sec="nwiml">
        <div id="new_post-ml_top">
            <!--<p id="npost_tr_top_max" class="jb-nwpst-tr-top-mx this_hide" data-trid="" data-isown=""><span id="npost_tr_sp_in">in</span>&nbsp;<span id="npost_tr_title" class="jb-nwpst-tr-tle" title=""></span></p>-->
            <div class="tqr-brn-npst-bnr jb-tqr-brn-npst-bnr" data-scp="iml">
                <div class="tqr-brn-npst-bnr-grp jb-tqr-brn-npst-bnr-grp" data-scp="iml">
                    <div class="tqr-brn-npst-bnr-tle jb-tqr-brn-npst-bnr-tle">
                        <img class="_logo" src="{wos/sysdir:img_dir_uri}/r/frd-ctr-w.png" width="34">
                        <span>Pour mes amis</span>
                    </div>
                </div>
            </div>
            <div class="tqr-brn-npst-bnr jb-tqr-brn-npst-bnr this_hide" data-scp="sod">
                <div class="tqr-brn-npst-bnr-grp jb-tqr-brn-npst-bnr-grp" data-scp="sod">
                    <div class="tqr-brn-npst-bnr-tle jb-tqr-brn-npst-bnr-tle">
                        <i class="fa fa-sun-o _logo"></i>
                        <span>Photos du jour</span>
                    </div>
                </div>
            </div>
            <div class="tqr-brn-npst-bnr jb-tqr-brn-npst-bnr jb-nwpst-tr-top-mx this_hide" data-scp="itr" data-trid="" data-isown="">
                <div class="tqr-brn-npst-bnr-grp jb-tqr-brn-npst-bnr-grp" data-scp="itr">
                    <div class="tqr-brn-npst-bnr-tle jb-tqr-brn-npst-bnr-tle" data-scp="itr">
                        <span>Tendance : </span>
                        <span class="jb-nwpst-tr-tle">C'est la fin des haricots mais aussi le début des abricots</span>
                    </div>
                    <div class="tqr-brn-npst-bnr-more jb-tqr-brn-npst-bnr-more this_hide"></div>
                </div>
            </div>
            <div class="tqr-brn-npst-bnr jb-tqr-brn-npst-bnr this_hide" data-scp="hstd">
                <div class="tqr-brn-npst-bnr-grp jb-tqr-brn-npst-bnr-grp" data-scp="hstd">
                    <div class="tqr-brn-npst-bnr-tle jb-tqr-brn-npst-bnr-tle">
                        <i class="fa fa-lock _logo" aria-hidden="true"></i>
                        <span>Photos hébergées</span>
                    </div>
                </div>
            </div>
        </div>
        <div id="new_post-ml_img" class="jb-tmlnr-nwpst-iml-i-wpr">
            <div id="nwpst-wt-pan-mx" class="jb-nwpst-wt-pan-mx this_hide">
                <span id="nwpst-wt-pan-txt">Patientez...</span>
                <progress id="nwpst-wt-pan-prgbr" class="jb-np-wt-pan-prgbr this_hide"></progress>
            </div>
            <div id="nwpst-vid-pan-mx" class="jb-np-vid-pan-mx this_hide">
                <a id="nwpst-vid-pan-abort" class="jb-np-vid-pan-abort" data-scp="vid-thumbnail" >&times;</a>
                <div id="nwpst-vid-pan-bdy" class=""><i class="fa fa-file-video-o"></i></div>
                <div id="nwpst-vid-pan-ftr">Votre vidéo est prête à être ajoutée</div>
            </div>
            <div id="newp_err_max" class="jb-nwpst-err-mx this_hide">
                <div id="newp_err_bloc">
                    <p id="newp_err_img">
                        <img height="150" weight="150" src="{wos/sysdir:img_dir_uri}/r/npberr.png" />
                    </p>
                    <p id="newp_err_msg" class="jb-nwpst-err-msg">
                        Votre texte doit comporter au moins un <span class="kgb_blue_link">#mot-clé</span>
                    </p>
                    <a id="newp_err_clo_a" class="jb-nwpst-clz" href="">{wos/deco:_Back}</a>
                    <!--<p id="newp_err_clo"><a id="newp_err_clo_a" href="#">{wos/deco:_Back}</a></p>-->
                </div>
            </div>
            <div id="newp_explain" class="in_npost_focus jb-nwpst-xpln">
                <span class="jb-tqr-skycrpr-snit" data-target='brain'></span>
                <p class="newp_explain_big newp_explain_top">
                    <span>Cliquez</span>
                </p>
                <p class="newp_explain_small">
                    <span>ou</span>
                </p>
                <p class="newp_explain_big">
                    <span>Déposez</span>
                </p>
            </div>
            <div id="newp_plus" class="jb-nwpst-pls this_hide">
                <p id="plus_omg">Déposez ici</p>
            </div>
            <div id="thumbnail" class="jb-nwpst-ab-pic-bx this_hide">
                <a id="abort_pic" class="jb-nwpst-ab-pic" data-scp="img-thumbnail" >&times;</a>
                <div id="nwpst-pic-xtra-txt" class="jb-nwpst-pic-xtra-txt this_hide" data-clcd="default" >
                    <span class="_text"></span>
                </div>
                <p id="inDncPreview"></p>
            </div>
        </div> 
        <div id="tqr-brn-nwpst-orien" class="jb-tqr-brn-nwpst-orien this_hide">
            <a class="tqr-brn-nwpst-or-ax jb-tqr-brn-nwpst-or-ax" data-action="brn-img-cstm-rot-l" data-wha="brn-img-cstm-rot-l" href="javascript:;" role="button" title="Faire pivoter l'image vers la gauche" ></a>
            <a class="tqr-brn-nwpst-or-ax jb-tqr-brn-nwpst-or-ax" data-action="brn-img-cstm-rot-r" data-wha="brn-img-cstm-rot-r" href="javascript:;" role="button" title="Faire pivoter l'image vers la droite" ></a>
            <a class="tqr-brn-nwpst-or-ax jb-tqr-brn-nwpst-or-ax" data-action="brn-img-cstm-ccl" data-wha="brn-img-cstm-ccl" href="javascript:;" role="button" title="Annuler toutes les modifications" >Annuler</a>
        </div>
        <div id="nwpst-iml-ftr-bmx">
            <div class="child-pad">
                <!-- Ajouter .pod pour indiquer le cas de la Photo Du Jour -->
                <textarea id="npost_txt" class="kb_boxeditable check_char skip_sharp jb-nwpst-txt" data-maxch="242" data-target="npost_opt_char" ></textarea>
            </div>
            <div id="store" class="this_hide" ></div>
            <div id="npost_opt" class="child-pad">
                
                <div id="np_opt_action">
                    <!-- Depuis 16-10-15 -->
                    <a id="npost_opt_ccl" class="jb-nwpst-opt-chs this_hide" data-action="reset" href="javascript:;">{wos/deco:_Reset}</a>
<!--                    <a id="npost_opt_post" class="jb-nwpst-opt-chs cursor-pointer" data-action="post" role="button" ><b>Just Trenq</b> it <span class='bold italic'>!</span></a>-->
<!--                    <a id="npost_opt_post" class="jb-nwpst-opt-chs" data-action="post" href="javascript:;" role="button" ><b>Just Trenq</b> it <span class='bold italic'>!</span></a>-->
<!--                    <span id="nwpst-socfd-mx">
                        <span id="nwpst-socfd-lbl">Vous utiliserez : </span><span id="nwpst-socfd-valzn" class="jb-nwpst-socfd-vzn">
                           <span class="jb-nwpst-socfd-vzn-value">-</span>§
                       </span>
                       <span class="jb-nwpst-socfd-ttl">&nbsp;(sur <span>{wos/datx:oucapital}</span>)</span>
                   </span>-->
                </div>
                <div id="np_opt_char">
                    <span id="npost_opt_char" class="jb-nwpst-opr-chr">242</span>
                </div>
            </div>
            <!--
            <div id="tqr-nwpst-tqs-bmx">
                <a id="tqr-nwpst-tqs-hrf-mx" class="jb-tqr-nwpst-tqs-hrf-mx" href="//studio.trenqr.com" title="Redimensionnez, personnalisez et modifiez vos photos" target="_blank">
                    <span id="tqr-nwpst-tqs-hrf-txt">Accéder au studio</span>
                </a>
            </div>
            -->
            <div id="tqr-brn-nwpst-opt-sections">
                <fieldset class="tqr-brn-nwpst-opt-sec jb-tqr-brn-nwp-o-sec this_hide" data-scp="pic-emb-text">
                    <legend align="right">Texte incoporé</legend>
                    <div class="tqr-brn-nwpst-o-s-fld" data-sec="xtra-text-ena">
                        <label>
                            <span>Ajouter du texte sur l'image</span>
                            <input id="tqr-brn-nwpst-x-t-ena" class="jb-tqr-brn-nwp-x-t-ena" data-action="xtra-text-ena" type="checkbox" >
                        </label>
                    </div>
                    <div class="tqr-brn-nwpst-o-s-fld jb-tqr-brn-nwpst-o-s-fld this_hide" data-sec="text-input">
                        <input id="tqr-brn-nwpst-x-t-ipt" class="jb-tqr-brn-nwp-x-t-ipt" data-action="xtra-txt-txt" type="text" placeholder="Rentrez votre texte ici" maxlength="50" />
                    </div>
                    <div class="tqr-brn-nwpst-o-s-fld jb-tqr-brn-nwpst-o-s-fld this_hide" data-sec="color-selector">
                        <ul>
                            <li>
                                <a class="jb-tqr-brn-nwp-x-t-clpkr _color_slc" data-action="xtra-txt-clr-pkr" data-color="" data-code="none"></a>
                            </li>
                            <li>
                                <a class="jb-tqr-brn-nwp-x-t-clpkr _color_slc" data-action="xtra-txt-clr-pkr" data-color="" data-code="default"></a>
                            </li>
                            <li>
                                <a class="jb-tqr-brn-nwp-x-t-clpkr _color_slc" data-action="xtra-txt-clr-pkr" data-color="" data-code="std_trenqr"></a>
                            </li>
                            <li>
                                <a class="jb-tqr-brn-nwp-x-t-clpkr _color_slc" data-action="xtra-txt-clr-pkr" data-color="" data-code="std_friend"></a>
                            </li>
                            <li>
                                <a class="jb-tqr-brn-nwp-x-t-clpkr _color_slc" data-action="xtra-txt-clr-pkr" data-color="" data-code="std_pod"></a>
                            </li>
                            <li>
                                <a class="jb-tqr-brn-nwp-x-t-clpkr _color_slc" data-action="xtra-txt-clr-pkr" data-color="" data-code="std_trend"></a>
                            </li>
                            <li>
                                <a class="jb-tqr-brn-nwp-x-t-clpkr _color_slc" data-action="xtra-txt-clr-pkr" data-color="" data-code="std_black"></a>
                            </li>
<!--                            <li>
                                <a class="_color_slc" data-code="custum">Personnaliser</a>
                            </li>-->
                        </ul>
                    </div>
                    <div class="tqr-brn-nwpst-o-s-fld" data-sec="">

                    </div>
                </fieldset>
            </div>
            <div id="nwpst-socfd-bmx" class="child-pad">
<!--                <span>
                     <span id="nwpst-socfd-lbl">Vous utiliserez : </span><span id="nwpst-socfd-valzn" class="jb-nwpst-socfd-vzn">
                        <span class="jb-nwpst-socfd-vzn-value">-</span>§
                    </span>
                    <span class="jb-nwpst-socfd-ttl">&nbsp;(sur <span>{wos/datx:oucapital}</span>)</span>
                </span>-->
                <a id="npost_opt_post" class="jb-nwpst-opt-chs cursor-pointer" data-action="post" role="button" >
                    <span id="nwpst-trg-spnr" class="jb-nwpst-trg-spnr this_hide"><img height="16" width="16" src="{wos/sysdir:img_dir_uri}/w/anim_loading.gif" /></span>
                    <span><b>Just Trenq</b> it <span class='bold italic'>!</span></span>
                </a>
            </div>
    <!--        
            <div id="nwpst-iml-ftr-pod-bmx" class="jb-nwpst-iml-ftr-pod-bmx child-pad">
                <label id="np-i-ftr-pod-lbl" class="jb-np-i-ftr-pod-lbl">
                    <input class="jb-np-i-ftr-pod-ipt" type="checkbox" />
                    <span>Boostez</span>
                </label>
                <span id="np-i-ftr-pod-hlp-tgr">
                    <a id="np-i-ftr-pod-hlp-tgr-h" class="jb-np-i-ftr-pod-hlp-tgr-h">
                        <i class="fa fa-question-circle"></i>
                    </a>
                    <div id="np-i-ftr-pod-hlp-msg-bmx" class="jb-np-i-ftr-pod-h-m-bmx this_hide">
                        <div id="np-i-ftr-pod-hlp-mbx">
                            La photo du jour est un concept qui vous permet de diffuser une ou plusieurs photos marquantes de vore journée, auprès de vos amis et de toutes les personnes qui vous suivent. 
                            Vous avez droit à une diffusion par jour. Au delà, vous devrez utiliser vos UCoins. 
                            Elle est visible pendant 24 heures dans Newsfeed.
                        </div>
                        <div id="np-i-ftr-pod-hlp-fndmr">
                            <a id="np-i-ftr-pod-hlp-f-hrf" href="blog.trenqr.com/lien-vers-larticle-blog">En savoir plus</a>
                        </div>
                    </div>
                </span>
            </div>-->
        </div>

    </div>

    <!-- **************************************** THEME *********************************** -->
    <?php
        if ( isset($trcct) && intval($trcct) === 0 ) :
    ?>
    <div id='brain_th-notuserontr' class='brain-th-com this_hide'>
        <span id="toptop"></span>
        <div class="brain_f_title" style="text-align: center;">{wos/deco:_brain_thaboutTr_header}</div>
        <div id="brain_trconcept" class="css-br-trconcept">
            <div class="trcct-box">
                <h4>A propos des Tendances</h4>
                <p>
                    Une <b style="color: #6B237E">Tendance</b> est un flux de publications traitant d'un thème ou d'un sujet donné. 
                    <!--Elle vous offre une vitrine publique d'expression, sur <span title="Trenqr Inside correspond à l'environnement accéssible en mode connecté." style="border-bottom:dotted 1px black; cursor:help;">Trenqr Inside</span> et au-delà.-->
                    Elle vous offre une vitrine publique d'expression sur Trenqr et au-delà.
                </p>
            </div>
            <div id="trcct-box-y" class="trcct-box">
                <h4>A quoi sert une Tendance</h4>
                <p>Touchez un public au-delà de vos amis ou vos connaissances</p>
                <p>Découvrez, partagez et débattez de manière structurée</p>
                <p>Rencontrez des personnes qui partagent vos centres d'intêret</p>
            </div>
            <div id="trcct-box-trmore" class="trcct-box this_invi"><a href="javascript:;">En savoir plus ...</a></div>
            <div id="trcct-box-sec" class="trcct-box">
                <h4>Que faire dans cette section</h4>
                <ul>
                    <li><a class="brainM_submenu_elmnt" data-or="brain_submenu_mytrch" data-slave="brain_th-newtr" href="javascript:;" role="button">Ajouter dans une de mes Tendances</a></li>
                    <li><a class="brainM_submenu_elmnt" data-or="brain_submenu_follgtrch" data-slave="brain_th-follgtrch" href="javascript:;" role="button">Ajouter dans une Tendance que je suis</a></li>
                    <li><a class="brainM_submenu_elmnt" data-or="brain_submenu_newtr" data-slave="brain_th-mytrch" href="javascript:;" role="button">Créer une Tendance</a></li>
                </ul>
            </div>
            <div id="trcct-bo-dsma" class="trcct-box">
                <label>
                    <input id="trcct-bo-skip-dsma-ib" type="checkbox" name=""/>
                    <span>Ne plus afficher</span>
                </label>
            </div>
            <div id="trcct-bo-skip" class="trcct-box">
                <a id="trcct-bo-skip-trg" class="jb-trcct-bo-skip-trg" href="">J'ai Compris</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
     <!-- **************************************** THEME *********************************** -->
     <div id='brain_th-newtr' class='this_hide'>
        <span id="toptop"></span>
        <div class="brain_f_title">
            {wos/deco:_brain_thnewtr_header}
            <a id="bn-ntr-catg-chs-clz" class="jb-ntr-catg-clz this_hide" href="javascript:;" role="button">Retour</a>
        </div>
        <div id="bn-ntr-catg-chs-sprt" class="jb-bn-ntr-catg-chs-sprt this_hide">
            <div id="bn-ntr-catg-chs-mx">
                <div id="bn-ntr-catg-chs-hdr">
                    <span>Catégories disponibles</span>
                </div>
                <div id="bn-ntr-catg-chs-bdy" class="jb-bn-ntr-catg-chs-bdy">
                    <div class="center">
                        <span id="bn-ntr-catg-chs-spnr" class="jb-bn-ntr-catg-chs-spnr this_hide">
                            <img height="32" width="32"src="{wos/sysdir:img_dir_uri}/r/ld_32.gif" />
                        </span>
                    </div>
                    <?php // for($xcn=0; $xcn<20; $xcn++) : ?>
<!--                    <div class="bn-ntr-catg-chc-grp jb-bn-ntr-catg-chc-grp">
                        <input id="" class="bn-ntr-catg-chc-ipt" name="bn-ntr-catg-chcs" value="" data-lbl="" type="radio">
                        <label class="bn-ntr-catg-chcs-lbl" for="bn-ntr-catg-chcs1">Une Catagorie<?php echo rand(0,$xcn); ?></label>
                    </div>-->
                    <?php // endfor; ?>
                </div>
                <div id="bn-ntr-catg-chs-ftr">
                    <a id="bn-ntr-catg-chs-th" class="jb-this-catg" href="javascript:;" role="button">Valider</a>
                </div>
            </div>
        </div> 
        <div id="brain_new_trend" class="jb-bn-newtr-mx">
            <a class="tqr-hlprmd-entry-tgr jb-tqr-hlprmd-entry-tgr" data-target="nwtr" href="javascript:;" role="button" title="Besoin d'aide ?">?</a>
            <form id="newtr_form" action="#" method="post">
                <div id="bn-nwtr-wrng-krm-sprt" class="jb-bn-nwtr-wrng-krm-sprt">
                    <span id="bn-nwtr-wrng-krm-txt">
                        <b>Attention :</b> Pour créer un Salon vous devez avoir au moins <b>x points Karma</b>.
                        Cliquez sur le lien ci-contre pour voir <a href="">comment obtenir le nombre de points necessaire</a>.
                    </span>
                </div>
                <div class="form_nwtrend_line">
                   <label for="newtr_title">
                        <span class="newtr_cl_label">{wos/deco:_Title}&nbsp;</span>
                        <input id="newtr_title" class="check_char jb-f_n_itext jb-ntr-itr-tle jb-ntr-ipt" data-ft="title" data-target="bn_ntr_opt_char_tle" data-maxch="100" type="text" name="newtr_title" placeholder="{wos/deco:_brain_thnewtr_title_ph}" required>
                    </label> 
                    <div class="tqr-hlprmd-popps-mx jb-tqr-hlprmd-popps-mx this_hide" data-scp="nwtr" data-target="nwtr_title">
                        <a class="tqr-hlprmd-popps-clz jb-tqr-hlprmd-popps-clz" data-target="nwtr_title" href="javascript:;" title="Fermer la vue" role="button">x</a>
                        <span class="tqr-hlprmd-popps-crnr" data-target="nwtr_title"></span>
                        <span class="tqr-hlprmd-popps-msg">
                            Le titre de votre Tendance doit avoir au moins 15 caractères alphanumériques.
                            Nous vous conseillons l'utilisation d'un titre précis, accrocheur et dynamique.
                        </span>
                    </div>
                </div>
                <div class="f_n_l_char">
                    <span id="bn_ntr_opt_char_tle" class="check_char_rcv" data-dft="100" class="">100</span>
                    <span class="bn-ntr-min-char">min. 15</span>
                </div>
                <div class="form_nwtrend_line">
                    <label for="newtr_desc">
                        <span class="newtr_cl_label">{wos/deco:_Description}&nbsp;</span>
                        <textarea id="newtr_desc" class="check_char jb-f_n_itext jb-ntr-itr-desc jb-ntr-ipt" data-ft="description" data-target="bn_ntr_opt_char_desc" data-maxch="200" name="newtr_desc" placeholder="{wos/deco:_brain_thnewtr_desc_ph}" required ></textarea>
                    </label>
                    <div class="tqr-hlprmd-popps-mx jb-tqr-hlprmd-popps-mx this_hide" data-scp="nwtr" data-target="nwtr_desc">
                        <a class="tqr-hlprmd-popps-clz jb-tqr-hlprmd-popps-clz" data-target="nwtr_desc" href="javascript:;" title="Fermer la vue" role="button">x</a>
                        <span class="tqr-hlprmd-popps-crnr" data-target="nwtr_desc"></span>
                        <span class="tqr-hlprmd-popps-msg">
                            La description de votre Tendance doit avoir au moins 20 caractères alphanumériques. 
                            Elle complète le titre en lui apportant plus de précisions dans la longueur.
                        </span>
                    </div>
                </div>
                <div class="f_n_l_char">
                    <span id="bn_ntr_opt_char_desc" class="check_char_rcv" data-dft="200" class="">200</span>
                    <span class="bn-ntr-min-char">min. 20</span> 
                </div>
                <div class="form_nwtrend_line">
                    <label>
                        <span class="newtr_cl_label">{wos/deco:_Category}</span>
                        <select id="newtr_cat" class="jb-ntr-itr-catg jb-ntr-ipt" data-ft="category" data-preview="cat_prevw" required>
                            <option value="_NTR_CATG_ANIMALS" selected>{wos/deco:_NTR_CATG_ANIMALS}</option>
                            <option value="_NTR_CATG_FASHION">{wos/deco:_NTR_CATG_FASHION}</option>
                            <option value="_NTR_CATG_HUMOR">{wos/deco:_NTR_CATG_HUMOR}</option>
                            <option value="_NTR_CATG_POLITICS">{wos/deco:_NTR_CATG_POLITICS}</option>
                            <option value="_NTR_CATG_SOCIETY">{wos/deco:_NTR_CATG_SOCIETY}</option>
                            <option value="_NTR_CATG_TECHNOLOGY">{wos/deco:_NTR_CATG_TECHNOLOGY}</option>
                            <option value="_NTR_CATG_OTHER">Plus ...</option>
                        </select>
                    </label>
                    <span id="cat_prevw" class="form_nwtrend_selected jb-cat-prw">{wos/deco:_NTR_CATG_HUMOR}</span>
                    <div class="tqr-hlprmd-popps-mx jb-tqr-hlprmd-popps-mx this_hide" data-scp="nwtr" data-target="nwtr_catg">
                        <a class="tqr-hlprmd-popps-clz jb-tqr-hlprmd-popps-clz" data-target="nwtr_catg" href="javascript:;" title="Fermer la vue" role="button">x</a>
                        <span class="tqr-hlprmd-popps-crnr" data-target="nwtr_catg"></span>
                        <span class="tqr-hlprmd-popps-msg">
                            La catégorie permet de situer votre Tendance. 
                            C'est une donnée essentielle, pour sa compréhension par les utilisateurs de Trenqr, mais aussi, pour nos algorithmes de traitement.
                        </span>
                    </div>
                </div>
                <div class="form_nwtrend_line">
                    <label>
                        <span class="newtr_cl_label">{wos/deco:_Participation}</span>
                        <select id="newtr_part" class="jb-ntr-itr-part jb-ntr-ipt" data-ft="participation" data-preview="part_prevw" required>
                            <option value="_NTR_PART_PUB" selected>{wos/deco:_Public}</option>
                            <option value="_NTR_PART_PRI">{wos/deco:_Private}</option>
                        </select>
                    </label> 
                    <span id="part_prevw" class="form_nwtrend_selected jb-part-prw">{wos/deco:_Public}</span>
                    <div class="tqr-hlprmd-popps-mx jb-tqr-hlprmd-popps-mx this_hide" data-scp="nwtr" data-target="nwtr_part">
                        <a class="tqr-hlprmd-popps-clz jb-tqr-hlprmd-popps-clz" data-target="nwtr_part" href="javascript:;" title="Fermer la vue" role="button">x</a>
                        <span class="tqr-hlprmd-popps-crnr" data-target="nwtr_part"></span>
                        <span class="tqr-hlprmd-popps-msg">
                            En privé, seul vous, pourrez ajouter des publications à votre Tendance. 
                            Dans tous les cas, tout visiteur ou utilisateur de Trenqr, aura accès en lecture, à toutes les publications contenues dans votre Tendance.
                            Les utilisateurs de Trenqr eux, pourront commenter ou donner une appréciation.
                        </span>
                    </div>
                </div>
                <div id="form_nwtrend_submit_max">
                    <a id="f_ntr_sub_rst" class="jb-f_ntr_sub_rst" data-target="newtr_form" href="">{wos/deco:_Reset}</a>
                    <span id="nwtr-trg-spnr" class="this_hide jb-nwtr-trg-spnr"><img height="16" width="16" src="{wos/sysdir:img_dir_uri}/w/anim_loading.gif" /></span>
                    <button id="create_tr_btn" class="jb-crt-trd-tgr" value="Create">{wos/deco:_brain_Create_tr}</button>
                </div>
            </form>
        </div>
     </div>
     
     <!-- **************************************** THEME *********************************** -->
     <div id="brain_th-mytrch" class="jb-com-bn-trs-bc jb-brn-lsts-bmx brain-th-com this_hide" data-scp="mytrs">
        <span id="toptop"></span>
        <!--
        <div id="slv-a-i-i-folw" class="slv-add-itrart-inst">
            <span class="slv-a-i-i-txt">Cliquez sur le titre de la Tendance pour ajouter</span>
            <a class="slv-a-i-i-close jb-kxlib-close" data-target="slv-a-i-i-folw" href="" ></a>
        </div>
        -->
        <div class="brain_f_title">
            <span>{wos/deco:_brain_thmytr_header}</span>
            <span class="counter jb-counter jb-bn-lsts-nb">0</span>
            <a class="tqr-hlprmd-entry-tgr jb-tqr-hlprmd-entry-tgr" data-target="mytrs" href="javascript:;" role="button" title="Besoin d'aide ?">?</a>
        </div>
        <div class="tqr-hlprmd-popps-mx jb-tqr-hlprmd-popps-mx this_hide" data-scp="mytrs" data-target="mytrs_additr">
            <a class="tqr-hlprmd-popps-clz jb-tqr-hlprmd-popps-clz" data-target="mytrs_additr" href="javascript:;" title="Fermer la vue" role="button">x</a>
            <span class="tqr-hlprmd-popps-crnr" data-target="mytrs_additr"></span>
            <span class="tqr-hlprmd-popps-msg">
                Retrouvez ici, la liste des Tendances dont vous êtes le propriétaire. Cliquez sur le titre de la Tendance, dans laquelle vous souhaiteriez ajouter la publication.
            </span>
        </div>
        <div id="brain_list_mytrs" class="jb-com-bn-trs-bc in_slave_list">
            <div class="brn-trs-spnr-mx jb-brn-lsts-spnr-mx" data-scp="mytrs"><i class="fa fa-refresh fa-spin"></i></div>
            <div class="brn-trs-noone-mx jb-brn-lsts-noone-mx this_hide" data-scp="mytrs">
                <span class="brn-trs-noone-txt">Aucune Tendance ... Pour l'instant !</span>
            </div>
            <div class="back_to_60s"><a href="javascript:;" class="this_hide">{wos/deco:_Bttf}</a></div>
        </div>
     </div>
     
     <!-- **************************************** THEME *********************************** -->

     <div id="brain_th-follgtrch" class="jb-brn-lsts-bmx brain-th-com this_hide" data-scp="flgtrs">
        <span id="toptop"></span>
<!--        <div id="slv-a-i-i-folg" class="slv-add-itrart-inst">
            <span class="slv-a-i-i-txt">Cliquez sur le titre de la Tendance pour ajouter</span>
            <a class="slv-a-i-i-close jb-kxlib-close" data-target="slv-a-i-i-folg" href="" ></a>
        </div>-->
        <div class="brain_f_title">
            {wos/deco:_brain_thfolgtr_header}
            <span class="counter jb-counter jb-bn-lsts-nb">0</span>
            <a class="tqr-hlprmd-entry-tgr jb-tqr-hlprmd-entry-tgr" data-target="flgtrs" href="javascript:;" role="button" title="Besoin d'aide ?">?</a>
        </div>
        <div class="tqr-hlprmd-popps-mx jb-tqr-hlprmd-popps-mx this_hide" data-scp="flgtrs" data-target="flgtrs_additr">
            <a class="tqr-hlprmd-popps-clz jb-tqr-hlprmd-popps-clz" data-target="flgtrs_additr" href="javascript:;" title="Fermer la vue" role="button">x</a>
            <span class="tqr-hlprmd-popps-crnr" data-target="flgtrs_additr"></span>
            <span class="tqr-hlprmd-popps-msg">
                Retrouvez ici, la liste des Tendances auxquelles vous êtes abonné. Cliquez sur le titre de la Tendance, dans laquelle vous souhaiteriez ajouter la publication.
            </span>
        </div>
        <div id="brain_list_follgtrs" class="in_slave_list">
            <div class="brn-trs-spnr-mx jb-brn-lsts-spnr-mx" data-scp="flgtrs"><i class="fa fa-refresh fa-spin"></i></div>
            <div class="brn-trs-noone-mx jb-brn-lsts-noone-mx this_hide" data-scp="flgtrs">
                <span class="brn-trs-noone-txt">Aucune Tendance ... Pour l'instant !</span>
            </div>
            <div class="back_to_60s"><a href="javascript:;" class="this_hide">{wos/deco:_Bttf}</a></div>
        </div>
     </div>

    <!-- **************************************** THEME *********************************** --> 

    <div id="brain_th-folls" class="jb-brn-lsts-bmx brain-th-com this_hide" data-scp="rlsflr">
        <span id="toptop"></span>
        <div class="brain_f_title">
            {wos/deco:_brain_thfolws_header}
            <span class="counter jb-counter jb-bn-lsts-nb">0</span>
        </div>
        <div id="brain_list_folls" class="in_slave_list">
            <div class="brn-trs-spnr-mx jb-brn-lsts-spnr-mx" data-scp="rlsflr"><i class="fa fa-refresh fa-spin"></i></div>
            <div class="brn-trs-noone-mx jb-brn-lsts-noone-mx this_hide" data-scp="rlsflr">
                <span class="brn-trs-noone-txt">Aucune Relation ... Pour l'instant !</span>
            </div>
            <div class="back_to_60s"><a href="javascript:;" class="this_hide">{wos/deco:_Bttf}</a></div>
        </div>
    </div>

   <!-- **************************************** THEME *********************************** -->  

    <div id="brain_th-folgs" class="jb-brn-lsts-bmx brain-th-com this_hide" data-scp="rlsflg">
        <span id="toptop"></span>
        <div class="brain_f_title">
            {wos/deco:_brain_thfolg_header}
            <span class="counter jb-counter jb-bn-lsts-nb">0</span>
        </div>
        <div id="brain_list_folgs" class="in_slave_list">
            <div class="brn-trs-spnr-mx jb-brn-lsts-spnr-mx" data-scp="rlsflg"><i class="fa fa-refresh fa-spin"></i></div>
            <div class="brn-trs-noone-mx jb-brn-lsts-noone-mx this_hide" data-scp="rlsflg">
                <span class="brn-trs-noone-txt">Aucune Relation ... Pour l'instant !</span>
            </div>
            <div class="back_to_60s"><a href="javascript:;" class="this_hide">{wos/deco:_Bttf}</a></div>
        </div>
    </div>
   
   <!-- **************************************** THEME *********************************** -->
   
     <div id="brain_th-trophies" class="jb-com-bn-trs-bc jb-brn-lsts-bmx brain-th-com this_hide" data-scp="trophies">
        <span id="toptop"></span>
        <div class="brain_f_title">
            <span>Mes trophés</span>
            <!--<a class="tqr-hlprmd-entry-tgr jb-tqr-hlprmd-entry-tgr" data-target="mytrs" href="javascript:;" role="button" title="Besoin d'aide ?">?</a>-->
        </div>
        <div class="tqr-hlprmd-popps-mx jb-tqr-hlprmd-popps-mx this_hide" data-scp="mytrs" data-target="mytrs_additr">
            <a class="tqr-hlprmd-popps-clz jb-tqr-hlprmd-popps-clz" data-target="mytrs_additr" href="javascript:;" title="Fermer la vue" role="button">x</a>
            <span class="tqr-hlprmd-popps-crnr" data-target="mytrs_additr"></span>
            <span class="tqr-hlprmd-popps-msg">
                Retrouvez ici, la liste des Tendances dont vous êtes le propriétaire. Cliquez sur le titre de la Tendance, dans laquelle vous souhaiteriez ajouter la publication.
            </span>
        </div>
        <div id="brain_list_trophz" class="jb-com-bn-trs-bc in_slave_list this_hide">
            <div>
                Chaque ligne comportera : date, titre, un logo (Publicatio, Tendance, Relation, ...) .
            </div>
            <div>Publicaton : Ma premiere publication</div>
            <div>Publicaton : Ma premiere publication en favoris</div>
            <div>Tendance : Ma première contribution</div>
            <div>Tendance : Ma première Tendance</div>
            <div>Tendance : Mon premier abonnement</div>
            <div>Relation : Mon premier abonné</div>
            <div>Relation : Mon premier abonnement</div>
            <div>Relation : Mon premier ami</div>
            <div>Commentaire : Mon premier commentaire</div>
            <div>Evaluation : Ma première appréciation</div>
            <div></div>
        </div>
     </div>
   
   <!-- **************************************** THEME *********************************** -->
   
     <div id="brain_th-settings" class="jb-com-bn-trs-bc jb-brn-lsts-bmx brain-th-com this_hide" data-scp="settings">
        <span id="toptop"></span>
        <div class="brain_f_title">
            <span>Paramètres</span>
            <!--<a class="tqr-hlprmd-entry-tgr jb-tqr-hlprmd-entry-tgr" data-target="mytrs" href="javascript:;" role="button" title="Besoin d'aide ?">?</a>-->
        </div>
        <div class="tqr-hlprmd-popps-mx jb-tqr-hlprmd-popps-mx this_hide" data-scp="mytrs" data-target="mytrs_additr">
            <a class="tqr-hlprmd-popps-clz jb-tqr-hlprmd-popps-clz" data-target="mytrs_additr" href="javascript:;" title="Fermer la vue" role="button">x</a>
            <span class="tqr-hlprmd-popps-crnr" data-target="mytrs_additr"></span>
            <span class="tqr-hlprmd-popps-msg">
                Profitez en un seul endroit d'un accès rapide à une partie des paramètres essentiels de votre compte.
            </span>
        </div>
        <div id="brain_list_stgs" class="brn-menu-body-bmx jb-com-bn-trs-bc in_slave_list" data-scp="settings">
        <?php 
            $_PFOP_BRAIN_ALWZ_OPN = ( !$prefdcs || (  $prefdcs && is_array($prefdcs) && !$prefdcs["_PFOP_BRAIN_ALWZ_OPN"] ) 
                    || ( $prefdcs && is_array($prefdcs) && $prefdcs["_PFOP_BRAIN_ALWZ_OPN"] && $prefdcs["_PFOP_BRAIN_ALWZ_OPN"]["prfodtp_lib"] === "_DEC_ENA" ) 
            ) ? TRUE : FALSE;
            /************************/
            $_PFOP_PSMN_EMLWHN_NW = ( !$prefdcs || (  $prefdcs && is_array($prefdcs) && !$prefdcs["_PFOP_PSMN_EMLWHN_NW"] ) 
                    || ( $prefdcs && is_array($prefdcs) && $prefdcs["_PFOP_PSMN_EMLWHN_NW"] && $prefdcs["_PFOP_PSMN_EMLWHN_NW"]["prfodtp_lib"] === "_DEC_ENA" ) 
            ) ? TRUE : FALSE;
            $_PFOP_PSMN_EMLFOR_WKACTY = ( !$prefdcs || (  $prefdcs && is_array($prefdcs) && !$prefdcs["_PFOP_PSMN_EMLFOR_WKACTY"] ) 
                    || ( $prefdcs && is_array($prefdcs) && $prefdcs["_PFOP_PSMN_EMLFOR_WKACTY"] && $prefdcs["_PFOP_PSMN_EMLFOR_WKACTY"]["prfodtp_lib"] === "_DEC_ENA" ) 
            ) ? TRUE : FALSE;
            /************************/
            $_PFOP_INFO_EMLWHN_NWTQRVnSECU = ( !$prefdcs || (  $prefdcs && is_array($prefdcs) && !$prefdcs["_PFOP_INFO_EMLWHN_NWTQRVnSECU"] ) 
                    || ( $prefdcs && is_array($prefdcs) && $prefdcs["_PFOP_INFO_EMLWHN_NWTQRVnSECU"] && $prefdcs["_PFOP_INFO_EMLWHN_NWTQRVnSECU"]["prfodtp_lib"] === "_DEC_ENA" ) 
            ) ? TRUE : FALSE;
            $_PFOP_INFO_EMLFOR_WKBESTPUB = ( !$prefdcs || (  $prefdcs && is_array($prefdcs) && !$prefdcs["_PFOP_INFO_EMLFOR_WKBESTPUB"] ) 
                    || ( $prefdcs && is_array($prefdcs) && $prefdcs["_PFOP_INFO_EMLFOR_WKBESTPUB"] && $prefdcs["_PFOP_INFO_EMLFOR_WKBESTPUB"]["prfodtp_lib"] === "_DEC_ENA" ) 
            ) ? TRUE : FALSE;
        ?> 
            <div id="brn-mn-bdy-wt-pnl-bmx" class="jb-brn-mn-bdy-wt-pnl-bmx this_hide">
                <div id="brn-mn-bdy-wt-pnl-mbx">
                    <span>Patientez...</span><i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
                </div>
            </div>
            <div class="brn-mn-bdy-sec-hdr">
                Profitez en un seul endroit d'un accès rapide à une partie des paramètres et préférences de votre compte.
            </div>
            <section class="brn-mn-bdy-sec-bmx" >
                <header>Vroom</header>
                <div class="brn-mn-bdy-sec-bdy">
                    <ul class="brn-mn-bdy-sec-prmy-opts">
                        <li class="brn-mn-bdy-sec-p-opt">
                            <label class="brn-mn-bdy-s-p-opt-lbl">
                                <input class="brn-mn-bdy-s-p-opt-chkbx jb-brn-mn-bdy-s-p-opt-chbx" type="checkbox" <?php echo ( $_PFOP_BRAIN_ALWZ_OPN ) ? "checked='true'" : ""; ?> data-wha="_PFOP_BRAIN_ALWZ_OPN" >
                                <span class="brn-mn-bdy-s-p-opt-txt">Le module "vroom" reste ouvert en permanence</span>
                            </label>
                            <div class="brn-mn-bdy-s-p-opt-xpln">
                                
                            </div>
                        </li>
                    </ul>
                </div>
                <footer></footer>
            </section>
            <section class="brn-mn-bdy-sec-bmx">
                <header>Notifications (Email)</header>
                <div class="brn-mn-bdy-sec-bdy">
                    <ul class="brn-mn-bdy-sec-prmy-opts">
                        <li class="brn-mn-bdy-sec-p-opt">
                            <label>
                                <input class="brn-mn-bdy-s-p-opt-chkbx jb-brn-mn-bdy-s-p-opt-chbx" type="checkbox" <?php echo ( $_PFOP_PSMN_EMLWHN_NW ) ? "checked='true'" : ""; ?> data-wha="_PFOP_PSMN_EMLWHN_NW">
                                <span class="brn-mn-bdy-s-p-opt-txt">M'avertir à chaque nouvelle notification sur mon compte</span>
                            </label>
                            <div class="brn-mn-bdy-s-p-opt-xpln">
                                Recevez un email à chaque fois qu'une nouvelle notification vous concernant est disponible.
                            </div>
                        </li>
<!--                        <li class="brn-mn-bdy-sec-p-opt">
                            <label>
                                <input class="brn-mn-bdy-s-p-opt-chkbx jb-brn-mn-bdy-s-p-opt-chbx" type="checkbox" <?php echo ( $_PFOP_PSMN_EMLFOR_WKACTY ) ? "checked='true'" : ""; ?> data-wha="_PFOP_PSMN_EMLFOR_WKACTY">
                                <span class="brn-mn-bdy-s-p-opt-txt">Recevoir un récapitulatif hebdomadaire de mon activité</span>
                            </label>
                            <div class="brn-mn-bdy-s-p-opt-xpln">
                                Recevez chaque semaine un email récapitulant votre activité sur la plateforme sur les 7 derniers jours.
                            </div>
                        </li>-->
                    </ul>
                </div>
            </section>
            <section class="brn-mn-bdy-sec-bmx">
                <header>Informations (Email)</header>
                <div class="brn-mn-bdy-sec-bdy">
                    <ul class="brn-mn-bdy-sec-prmy-opts">
                        <li class="brn-mn-bdy-sec-p-opt">
                            <label>
                                <input class="brn-mn-bdy-s-p-opt-chkbx jb-brn-mn-bdy-s-p-opt-chbx" type="checkbox" <?php echo ( $_PFOP_INFO_EMLWHN_NWTQRVnSECU ) ? "checked='true'" : ""; ?> data-wha="_PFOP_INFO_EMLWHN_NWTQRVnSECU" >
                                <span class="brn-mn-bdy-s-p-opt-txt" checked="true">M'avertir quand une nouvelle version de Trenqr est disponible</span>
                            </label>
                            <div class="brn-mn-bdy-s-p-opt-xpln">
                                Recevez un email à chaque fois qu'une nouvelle version de Trenqr est disponible ainsi que des informations sur des correctifs de sécurité.
                            </div>
                        </li>
<!--                        <li class="brn-mn-bdy-sec-p-opt">
                            <label>
                                <input class="brn-mn-bdy-s-p-opt-chkbx jb-brn-mn-bdy-s-p-opt-chbx" type="checkbox" <?php echo ( $_PFOP_INFO_EMLFOR_WKBESTPUB ) ? "checked='true'" : ""; ?> data-wha="_PFOP_INFO_EMLFOR_WKBESTPUB">
                                <span class="brn-mn-bdy-s-p-opt-txt" checked="true">Recevoir un récapitulatif hebdomadaire des meilleures publications</span>
                            </label>
                            <div class="brn-mn-bdy-s-p-opt-xpln">
                                Recevez chaque semaine un email où vous trouverez un récapitulatif des publications qui ont été le plus plébiscitées par votre entourage et sur Trenqr.
                            </div>
                        </li>-->
                    </ul>
                </div>
            </section>
        </div>
     </div>
</div>