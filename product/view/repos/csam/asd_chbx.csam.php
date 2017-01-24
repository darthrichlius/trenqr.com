<div id="asd-chbx-mx" class="jb-asdapp-modl this_hide" data-modl="chatbox">
    
    <?php 
            
        //var_dump(empty($prefdcs),is_array($prefdcs),count($prefdcs),key_exists("_PFOP_CHTBX_ISXPM",$prefdcs), strtoupper($prefdcs["_PFOP_CHTBX_ISXPM"]["prfodtp_lib"]), strtoupper($prefdcs["_PFOP_CHTBX_ISXPM"]["prfodtp_lib"]) === "_DEC_DSMA");
        //exit();
        if (! ( !empty($prefdcs) && is_array($prefdcs) && count($prefdcs)
                && key_exists("_PFOP_CHTBX_ISXPM",$prefdcs) && strtoupper($prefdcs["_PFOP_CHTBX_ISXPM"]["prfodtp_lib"]) === "_DEC_DSMA" )
        ) :
    ?>
    <div id="chbx-wrng-xpmdl-sprt" class="jb-chbx-wrng-xpmdl-sprt">
        <div id="chbx-wrng-xpmdl-header"></div>
        <div id="chbx-wrng-xpmdl-body">
            <div id="chbx-wrng-xpmdl-xplnd" class="jb-chbx-wrng-xpmdl-xplnd this_hide">
                <div id="chbx-wrng-xpmdl-xplnd-clz-mx">
                    <a id="chbx-wrng-xpmdl-xplnd-clz" class="jb-chbx-wrng-xpmdl-xplnd-clz" data-action="close_lrnabt_xpmd" href="javascript:;" role="button">x</a>
                </div>
                <div>
                    La Messagerie Instantannée Privée (alias PIM ou Parley) est une application en temps réel, qui vous permet de rester en contact avec vos amis. 
                    Ce module vous permet de communiquer sans limite, dans un environnement privé, pour plus d'intimité et de convivialité. 
                </div>
                <p>
                    Pour pouvoir utiliser ce module, il vous suffit de rechercher un de vos amis et de lancer une conversation en appuyant sur "Parley". C'est aussi simple que ça !
                </p>
<!--                <div>
                    Les modules expérimentaux, sont des modules accessibles sur demande, dont la qualité en termes d'expérience utilisateur, n'a pas été jugée assez acceptable, pour être distribués sans préavis.<br/>
                    Le faible niveau de qualité, peut être dû à des fonctionnalités manquantes ou à de légers problèmes de performance. Dans tous les cas, ils restent utilisables en l'état !<br/>
                    Des corrections régulières y sont apportées, afin d'atteindre le niveau de qualité voulu. Sachez que <u>les améliorations, les corrections ou toutes autres modifications, n'ont aucune incidence sur vos données</u>.<br/>
                </div>
                <p>
                    Nous les mettons à votre disposition, car nous pensons qu'ils pourraient, même dans leurs phases expérimentales, vous être utiles.
                </p>-->
            </div>
            <div id="chbx-wrng-xpmdl-board" class="jb-chbx-wrng-xpmdl-board">
                <div id="chbx-wrng-xpmdl-mdtle-mx">
                    <span id="chbx-wrng-xpmdl-mdtle">Messagerie Instantanée</span>
                </div>
                <div id="chbx-wrng-xpmdl-wrngm-mx">
                    <i id="chbx-wrng-xpmdl-wrngm-gle" class="fa fa-weixin"></i>
                    <span id="chbx-wrng-xpmdl-mdtle-msg">BLA BLA ENTRE AMIS</span>
                </div>
                <div id="chbx-wrng-xpmdl-wrngm-lrnab-mx">
                    <a id="chbx-wrng-xpmdl-wrngm-lrnab" class="jb-chbx-wrng-xpmdl-wrngm-lrnab" data-action="open_lrnabt_xpmd" href="javascript:;" role="button">En savoir plus ...</a>
                </div>
            </div>
        </div>
        <div id="chbx-wrng-xpmdl-footer">
            <div id="chbx-wrng-xpmdl-gaw-mx" class="jb-chbx-wrng-xpmdl-gaw-mx">
                <a id="chbx-wrng-xpmdl-gaw" class="jb-chbx-wrng-xpmdl-gaw" data-action="enable_xpmd" href="javascript:;" role="button">Activer le module</a>
                <label id="chbx-wrng-xpmdl-gaw-rmbr-mx">
                    <input id="chbx-wrng-xpmdl-gaw-rmbr-ipt" class="jb-chbx-wrng-xpmdl-g-r-ipt" type="checkbox" /> 
                    <span>Se souvenir de ma décision</span>
                </label>
            </div>
            <div id="chbx-wrng-xpmdl-gaw-spnr-mx" class="jb-chbx-wrng-xpmdl-g-s-mx this_hide">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div id="chbx-dlgbx-sprt" class="jb-chbx-dlgbx-sprt this_hide">
        <span id="chbx-drctmsg-m" class="jb-chbx-drctmsg-m this_hide">Patientez ...</span>
        <div id="chbx-dlgbx-mx" class="jb-chbx-dlgbx-mx this_hide">
            <p id="chbx-dlgbx-msg" class="jb-chbx-dlgbx-msg"></p>
            <div id="chbx-dlgbx-opts">
                <a id="chbx-dlgbx-opt" class="jb-chbx-action" data-action="dlgbx_valid" href="javascript:;" role="button">OK</a>
            </div>
        </div>
    </div>
    <div id="chbx-mods-convs" class="chbx-mods jb-chbx-mods active" data-wdw="conv_list">
        <div id="asd-chbx-hdr">
            <a id="asd-c-h-home" class="jb-asd-c-h-home" data-action="bkthome" href="javascript:;" title="Accueil">
                <i class="fa fa-home"></i>
            </a>
            <div id="asd-c-h-tle">Chatter avec mes amis</div>
            <div id="asd-c-h-t-ipt-mx">
                <input id="asd-c-h-t-ipt" class="jb-asd-c-h-t-ipt" type="type" placeholder="Rechercher"/>
            </div>
            <div id="asd-c-h-r-btm">
                <!--<span class="chbx-submn">Amis</span>-->
            </div>
        </div>
        <div id="asd-chbx-bdy" class="jb-chbx-list-convs">
        <!--<div id="asd-chbx-bdy" class="jb-chbx-list-convs ondelete">-->
            <div id="chbx-srh-fil-sprt" class="jb-chbx-srh-fil-sprt this_hide">
                <ul id="chbx-srh-fl-chcs">
                    <li class="chbx-srh-fl-chc-mx jb-chbx-srh-fl-chc-mx">
                        <a class="chbx-srh-fl-chc jb-chbx-srh-fl-chc jb-chbx-action" data-action="srh_fil_cnv" href="javascript:;">Conversations</a>
                    </li>
                    <li class="chbx-srh-fl-chc-mx jb-chbx-srh-fl-chc-mx" >
                        <a class="chbx-srh-fl-chc jb-chbx-srh-fl-chc jb-chbx-action" data-action="srh_fil_pfl" href="javascript:;">Liste d'amis</a>
                    </li>
                </ul>
            </div>
<!--            <div id="chbx-bdy-spnr" class="jb-chbx-bdy-spnr center">
                <img id="chbx-ldg-spnr" class="jb-chbx-ldg-spnr this_hide" src="{wos/sysdir:img_dir_uri}/r/ld_32.gif" />
            </div>-->
            <div class="jb-chbx-list-c-mx">
                <?php 
                for($i=0; $i<0; $i++) :
                ?>
                <div class="chbx-conv-mdl-mx onselection jb-chbx-conv-mdl-mx jb-chbx-action" data-action="nav_conv_theater" data-item="">
<!--                    <div>
                        <input class="" type="checkbox" />
                    </div>-->
                <!--<div class="chbx-conv-mdl-mx parley">-->
                    <div class="chbx-c-m-slct-mx jb-chbx-c-m-slct-mx">
                        <input class="chbx-c-m-slct-ipt jb-chbx-c-m-slct-ipt" type="checkbox">
                    </div>
                    <div class="chbx-c-m-left">
                        <!--<input class="chbx-mdl-slct" type="checkbox" />-->
                        <img class="chbx-mdl-psd-img" src="http://tqim.ycgkit1.com/user/12aoka10155/12aoka10155_3d313i37351h_3j683o8j.jpg?v=1418934560" height="55" width="55"/>
                        <!--<img class="chbx-mdl-psd-img" src="http://www.placehold.it/60x60"/>-->
                        <span class="chbx-usts-led"></span>
                    </div>
                    <div class="chbx-c-m-right jb-chbx-c-m-right noslct">
                        <div class="chbx-c-m-r-top">
                            <span class="chbx-mdl-psd">@Pseudo</span>
        <!--                    <span class="chbx-mdl-psd">@pseudoUnPeuLongLong1</span>-->
                            <!--<span class="chbx-mdl-nwink">15</span>-->
                            <span class="chbx-mdl-time">11:51</span>
                        </div>
                        <div class="chbx-c-m-r-btm">
                            <span class="chbx-mdl-sample">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat Nam volutpat Nam volutpat.
                            </span>
                            <a class="chbx-lets-speak this_hide" href="javascript:;" role="button" title="Engager la conversation">Parley</a>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
<!--            <div id="chbx-bdy-btm">
                <a id="chbx-ldmr-pd" class="jb-chbx-ldmr-pd" href="javascript:;" role="button">Voir Plus</a>
            </div>-->
            <div id="chbx-l-c-ldmr" class="jb-chbx-l-ldmr jb-chbx-l-c-ldmr">
                <a id="chbx-l-c-ldmr-tgr" class="this_hide jb-chbx-rslt-mr jb-chbx-action" data-action="pull_cbcnv_odr" href="javascript:;">Plus anciens</a>
                <img class="jb-chbx-ldg-spnr this_hide" src="{wos/sysdir:img_dir_uri}/r/ld_32.gif" />
            </div>
            <div class="chbx-noone-mx jb-chbx-noone-mx center">
                <div>
                    <div class="chbx-noone-txt jb-chbx-noone-txt">Rechercher un ami pour démarrer une conversation</div>
                    <div class="chbx-noone-sof-mx jb-chbx-noone-sof-mx">
                        <a class="chbx-noone-sof jb-chbx-noone-sof jb-chbx-action" data-action="nav_conv_list_og" href="javascript:;" role="button">Afficher les Conversations en cours</a>
                    </div>
                    <!--<div id="chbx-noone-sof-mx"><a id="chbx-noone-sof" class="jb-chbx-noone-sof" href="javascript:;" role="button">Afficher les amis en ligne</a></div>-->
                </div>
            </div>
        </div>
        <div id="chbx-del-cnv-mx" class="jb-chbx-del-cnv-mx this_hide">
            <p id="chbx-del-cnv-hdr">Supprimer définitivement les éléments sélectionnés ?</p>
            <div id="chbx-del-cnv-chcs">
                <a id="chbx-d-c-ch-ab" class="chbx-d-c-ch jb-chbx-action" data-action="abort_delconv" href="javascript:;" role="button">Annuler</a>
                <a id="chbx-d-c-ch-cf" class="chbx-d-c-ch jb-chbx-action" data-action="confirm_delconv" href="javascript:;" role="button">Supprimer</a>
            </div>
        </div>
        <div class="asd-chbx-ftr">
            <div class="chbx-opts-mx">
                <a class="chbx-opt-tgr jb-chbx-opt-tgr this_hide" data-wdw="conv_list" href="javascript:;" role="button"><i class="fa fa-cog"></i></a>
                <!--<a class="chbx-opt-tgr jb-chbx-opt-tgr" data-wdw="conv_list" href="javascript:;" role="button">Options</a>-->
                <ul class="chbx-opt-chcs jb-chbx-opt-chcs this_hide" data-wdw="conv_list">
                    <li class="chbx-opt-chc"><a class="chbx-opt-chc-tgr jb-chbx-opt-chc-tgr jb-chbx-action" data-action="delete_conv" data-state="active" href="javascript:;" role="button">Supprimer les conversations</a></li>
                    <!--<li class="chbx-opt-chc"><a class="checkable chbx-opt-chc-tgr jb-chbx-opt-chc-tgr jb-chbx-action" data-action="online_only" data-state="active" href="javascript:;" role="button">Seulement ceux en ligne</a></li>-->
                    <!--<li class="chbx-opt-chc"><a class="chbx-opt-chc-tgr jb-chbx-opt-chc-tgr jb-chbx-action" data-action="close_chbox" data-state="active" href="javascript:;" role="button">Fermer ChatBox</a></li>-->
                </ul>
            </div>
<!--            <div class="chbx-usts">
                <span class="chbx-you">Vous : </span>
                <a class="chbx-you-btn" href="javascript:;" role="button" title="Statut de connexion" >
                    <span class="chbx-y-b-indk"></span>
                </a>
            </div>-->
        </div>
    </div>
    <div id="chbx-mods-focusconv" class="chbx-mods jb-chbx-mods" data-wdw="conv_theater">
<!--        <div id="chbx-dlgbx-sprt" class="jb-chbx-dlgbx-sprt this_hide">
            <span id="chbx-drctmsg-m" class="jb-chbx-drctmsg-m this_hide">Patientez ...</span>
            <div id="chbx-dlgbx-mx" class="jb-chbx-dlgbx-mx this_hide">
                <p id="chbx-dlgbx-msg" class="jb-chbx-dlgbx-msg">Vous devez sélectionner au moins un message. Cliquez dans une case à cocher pour sélectionner le message à supprimer.</p>
                <div id="chbx-dlgbx-opts">
                    <a id="chbx-dlgbx-opt" class="jb-chbx-action" data-action="dlgbx_valid" href="javascript:;" role="button">OK</a>
                </div>
            </div>
        </div>-->
        <div id="chbx-fc-hdr">
            <div id="chbx-fc-hdr-left-mx">
                <div id="chbx-fc-h-bk">
                    <a id="chbx-fc-h-bk-tgr" class="jb-chbx-fc-h-bk-tgr jb-chbx-action" data-action="nav_conv_list" href="javascript:;" role="button"></a>
                    <!--<a id="chbx-fc-h-bk-tgr" class="jb-chbx-fc-h-bk-tgr jb-chbx-action" data-action="nav_conv_list" href="javascript:;" role="button"><i class="fa fa-angle-left"></i></a>-->
                    <a id="chbx-fc-h-bk-nw" class="jb-chbx-fc-h-bk-nw jb-chbx-action this_hide" data-action="nav_conv_list" href="javascript:;" role="button">2 nouveaux</a>
                </div>
            </div>
            <div id="chbx-fc-hdr-right-mx">
                <div id="chbx-fc-h-ubox-mx">
                    <a id="chbx-fc-h-ubox" class="jb-chbx-ubox-tgt-mx" href="/@pseudo">
                        <span id="chbx-fc-h-ubx-pi-fade"></span>
                        <span id="chbx-fc-h-ubox-psd" class="jb-chbx-ubox-tgt-psd">@Pseudo</span>
                        <img id="chbx-fc-h-ubox-ppic" class="jb-chbx-ubox-tgt-ppic" src="" height="50" width="50"/>
                        <!--<img id="chbx-fc-h-ubox-ppic" class="jb-chbx-ubox-tgt-ppic" src="http://tqim.ycgkit1.com/user/12aoka10155/12aoka10155_3d313i37351h_3j683o8j.jpg?v=1418934560" height="55" width="55"/>-->
                    </a>
                </div>
                <div></div>
            </div>
        </div>
        <div id="chbx-fc-body">
            <div id="chbx-list-msg" class="jb-chbx-list-msg">
                <div class="chbx-noone-mx jb-chbx-noone-mx this_hide center">
                    <div>
                        <div class="chbx-noone-txt jb-chbx-noone-txt"></div>
<!--                        <div class="chbx-noone-sof-mx jb-chbx-noone-sof-mx this_hide">
                            <a class="chbx-noone-sof jb-chbx-action" data-action="nav_conv_list_og" href="javascript:;" role="button"></a>
                        </div>-->
                        <!--<div id="chbx-noone-sof-mx"><a id="chbx-noone-sof" class="jb-chbx-noone-sof" href="javascript:;" role="button">Afficher les amis en ligne</a></div>-->
                    </div>
                </div>
                <!-- gb :GoBottom -->
                <div id="chbx-l-m-gb" class="jb-chbx-l-m-gb">
                    <div id="chbx-l-m-ldmr" class="jb-chbx-l-ldmr jb-chbx-l-m-ldmr">
                        <a id="chbx-l-m-ldmr-tgr" class="jb-chbx-rslt-mr jb-chbx-action this_hide" data-action="pull_cbmsg_odr" href="javascript:;">Plus Anciens</a>
                        <img class="jb-chbx-ldg-spnr this_hide" src="{wos/sysdir:img_dir_uri}/r/ld_16.gif" />
                    </div>
                    <?php 
                        for($i=0;$i<0;$i++) :
                    ?>
                    <div class="chbx-msgmdl-mx jb-chbx-msgmdl-mx" data-item="" data-cache="" time="" data-direction="lbdr">
                        <!--<div class="chbx-msgmdl-top">04 Dec</div>-->
                        <span class="chbx-msgmdl-bdy" data-direction="lbdr">
                            <span class="chbx-msgmdl-msg jb-chbx-msgmdl-msg">Lorem ipsum dolor sit amet, consectetur cras amet.</span>
                            <span class="chbx-msgmdl-btm jb-chbx-msgmdl-btm" data-direction="lbdr">
                                <input class="chbx-msgmdl-slct jb-chbx-msgmdl-slct this_hide" type="checkbox" data-direction="lbdr"/>
                                <span class="chbx-msgmdl-hr jb-chbx-msgmdl-hr this_hide" data-direction="lbdr">11:51</span>
                            </span>
                        </span>
                    </div>    
                    <div class="chbx-msgmdl-mx jb-chbx-msgmdl-mx" data-item="" data-cache="" data-direction="rbdr">
                        <!--<div class="chbx-msgmdl-top">04 Dec</div>-->
                        <span class="chbx-msgmdl-bdy" data-direction="rbdr">
                            <span class="chbx-msgmdl-msg jb-chbx-msgmdl-msg">Lorem ipsum dolor sit amet, consectetur cras amet.</span>
                            <span class="chbx-msgmdl-btm jb-chbx-msgmdl-btm" data-direction="rbdr">
                                <input class="chbx-msgmdl-slct jb-chbx-msgmdl-slct this_hide" type="checkbox" data-direction="rbdr"/>
                                <span class="chbx-msgmdl-hr jb-chbx-msgmdl-hr this_hide" data-direction="rbdr">11:51</span>
                            </span>
                        </span>
                    </div>    
                    <?php endfor; ?>
                </div>
            </div>
            <div id="chbx-nwmsg-ipt-mx" class="jb-chbx-nwmsg-ipt-mx">
                <textarea id="chbx-nwmsg-ipt" class="jb-chbx-nwmsg-ipt" data-action="newmessage" maxlength="1000"></textarea>
                <!--<a id="chbx-nwmsg-tgr" class="jb-chbx-action" data-action="newmessage" href="javascript:;" role="button"></a>-->
            </div>
            <div id="chbx-del-msg-mx" class="jb-chbx-del-msg-mx this_hide">
                <p id="chbx-del-msg-hdr">Supprimer définitivement les éléments sélectionnés ?</p>
                <div id="chbx-del-msg-chcs">
                    <a id="chbx-d-m-ch-ab" class="chbx-d-m-ch jb-chbx-action" data-action="abort_delmsg" href="javascript:;" role="button">Annuler</a>
                    <a id="chbx-d-m-ch-cf" class="chbx-d-m-ch jb-chbx-action" data-action="confirm_delmsg" href="javascript:;" role="button">Supprimer</a>
                </div>
            </div>
        </div>
        <div class="asd-chbx-ftr">
            <div class="chbx-opts-mx">
                <a class="chbx-opt-tgr jb-chbx-opt-tgr" data-wdw="conv_theater" href="javascript:;" role="button"><i class="fa fa-cog"></i></a>
                <!--<a class="chbx-opt-tgr jb-chbx-opt-tgr" data-wdw="conv_theater" href="javascript:;" role="button">Options</a>-->
                <ul class="chbx-opt-chcs jb-chbx-opt-chcs this_hide" data-wdw="conv_theater">
                    <!--<li class="chbx-opt-chc"><a class="checkable chbx-opt-chc-tgr jb-chbx-opt-chc-tgr jb-chbx-action" data-action="submit_w_enter" data-state="active" href="javascript:;" role="button">Activer la soumission via Entrer</a></li>-->
                    <!--<li class="chbx-opt-chc"><a class="checkable chbx-opt-chc-tgr jb-chbx-opt-chc-tgr jb-chbx-action" data-action="message_hour" data-state="inactive" href="javascript:;" role="button">Afficher l'heure des messages</a></li>-->
                    <li class="chbx-opt-chc"><a class="chbx-opt-chc-tgr jb-chbx-opt-chc-tgr jb-chbx-action" data-action="delete_messages"  href="javascript:;" role="button">Supprimer les messages</a></li>
                </ul>
            </div>
<!--            <div class="chbx-usts">
                <span class="chbx-you">Vous : </span>
                <a class="chbx-you-btn" href="javascript:;" role="button" title="Statut de connexion" href="javascript:;" role="button">
                    <span class="chbx-y-b-indk"></span>
                </a>
            </div>-->
        </div>
    </div>
</div>