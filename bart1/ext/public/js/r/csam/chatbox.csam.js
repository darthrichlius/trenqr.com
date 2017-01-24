
function ChatBox () {
    /*
     * 
     * FONCTIONNALITES
     *  Version : b.1505.1.1 (Mai 2015 VBeta1) [DATE UPDATE : 17-03-15]
     *      -> Aller à la page d'Accueil
     *      [CONVERSATIONS]
     *      -> Créer une conversation en envoyant un premier message à un Amis
     *      -> Lister les Conversations d'un User dans la limite
     *      -> Charger plus de Conversations 
     *      -> Supprimer une Conversation
     *      [MESSAGES]
     *      -> ...
     *      -> Supprimer un Message
     *      -> Signaler que l'on veut utiliser le module en mode experimental
     *  
     *  EVOLUTIONS ATTENDUES
     *      NOTE : Avec les prouesses faibles du module MI en mode beta, beaucoup d'utlisateurs seront deçus. 
     *      Il faudra donc mettre le paquet sur ce module qui est important quand on parle des RS.
     *      
     *      -> Gérer la lecture individuelle des Messages
     *      -> Vérifier pour toutes les Conversations, s'il y a des Messages non lues et les signaler
     *      -> Signaler dans le header les nouveaux Messages. On pourra s'aider de "document.hidden" pour vérifier si on est sur la page
     *      -> Signaler de façon permanent les Messages dans un coin de la page 
     *      -> Pouvoir détacher le module de la partie "AsideApps" en en faisant un module amovible et riche.
     *         L'utilisateur a l'impression d'être sur un site dédié à la Messagerie instantannée comme Skype. 
     *      -> Les liens sont cliclables
     *      -> Les Usertags sont cliclables
     *      -> On peut accéder à la donnée "time" pour les Messages
     *      -> Lorsqu'on change de page, le module garde toutes ses caractéristiques pour ne pas dégrader l'expérience utilisateur
     *          
     *  EVOLUTIONS POSSIBLES
     *      -> L'utilisateur peut mettre en favoris un Message de manière individuelle via une action de glisser-déposer
     *      -> L'utilisateur peut activer l'option sonore pour chaque nouveau message.
     *      -> L'utilisateur peut personnaliser le module MI avec un thème
     *      -> L'utilisateur peu assigner un nom et une photo pour chaque contact à l'image d'un smartphone
     */
    
    var gt = this;
    /*
     * Cet objet contient les paramètres de lock pour la majeure partie des activités de Chat.
     * Quand une des valeurs est à "true", cela signifie que toute activité liée doit être bloquée.
     * Attention, cela ne concerne que les activités lancées après que le paramètre ait été changé.
     */
    var _act_lkr = {
        "shrcs" : false,
        "plcs"  : false,
        "dlcs"  : false,
        "subms" : false,
        "plms"  : false,
        "dlms"  : false
    };
    var _asdApps;
    /*
     * XHR SCOPE
     */
    var _xhr_plms;
    var _xhr_sbms;
    var _xhr_dlms;
    var _xhr_srh;
    var _xhr_plcs;
    var _xhr_dlcs;
    var _xhr_plums;
    var _xhr_sbums;
    /*
     * XHR ABORT SCOPE
     */
    var _xhr_abort_all;
    var _xhr_abort_plcs;
    var _xhr_abort_srh;
    var _xhr_abort_plms;
    
    /***********************************************************************************************************************************************************/
    /******************************************************************** DATAS WAREHOUSE **********************************************************************/
    /***********************************************************************************************************************************************************/
    
    /*
     * NOTES :
     *      xtras : Permet d'insérer d'autres propriétés à la volée
     */
    /*
    var asdapps = {
        lupd: 0,
        searchbox : {
            isactive: true,
            name: "srhbx",
            lib: "SearchBox",
            lupd: 0,
            xtras : {},
            mods: {
                srhbox : { 
                    isactive: true,
                    name: "srhbx",
                    lib: "SearchBox",
                    lupd: 0,
                    xtras : {},
                    contents: []
                }
            }
        },
        chatbox : {
            isactive: false,
            name: "chbx",
            lib: "ChatBox",
            lupd: null,
            //Permet d'insérer d'autres propriétés à la volée
            xtras : {},
            mods: {
                convlist : { 
                    isactive: false,
                    name: "convlist",
                    lib: null,
                    lupd: null,
                    xtras : {
                        maininput: null
                    },
                    contents: [
                        //Le développeur est libre d'ajouter les éléments qu'il veut dans le tableau
                        {
                            id: "",
                            lupd: "",
                            xtras: {
                                cvtype: "", //(conv,parley),
                                uid: "",
                                upsd: "",
                                ufn: "",
                                uppic: "",
                                sample: "" //Peut être null dans le cas de "Parley"
                            }
                        }
                    ]
                }
            },
            convtheater : { 
                    isactive: false,
                    name: "convtheater",
                    lib: null,
                    lupd: null,
                    xtras : {
                        maininput: null,
                        uid: "",
                        upsd: "",
                        ufn: "",
                        uppic: "",
                        //TOF : TimeOfFirst, la conversation existe depuis ...
                        tof: ""
                    },
                    contents: [
                        {
                            id: "",
                            lupd: "",
                            xtras: {
                                message: "",
                                time: ""
                            }
                        }
                    ]
            }
        }
    };
    //*/
    /***********************************************************************************************************************************************************/
    /******************************************************************** PROCESS SCOPE ************************************************************************/
    /***********************************************************************************************************************************************************/
    
    /********************************************************************************************************************/
    /*************************************************** GENERAL SCOPE **************************************************/
    
    var _f_Gdf = function () {
        var ds = {
            "cnv_max_smpl"  : 80,
            "novoid"        : /^[\s|\t|\n]+$/,
            "rgx_msg"       : /^[\s\S]{1,1000}$/i,
            //chkbl_stt: Checkable_State
            "chkbl_stt"     : /^active|inactive$/i,
            //WTT (WaitTypingTime) Lancer la recherche après cet interval. Cela rend plus fiable le processus
            "wtt"           : 370,
            //PullMessageInterval
            "pmi"           : 5000,
//            "pmi"           : 60000, //Dans le cas de WithStayOption
            //PullConversationInterval
            "pci"           : 10500, //500 Pour tenter de réduire le risque de collision entre requetes
//            "pci"           : 60000, //Dans le cas de WithStayOption
           "pni"            : 17300,
           /*
            * [DEPUIS 10-11-15] @author BOR
            *       Interval necessaire pour autoriser l'affichage de la zone après que l'utilisateur ait cliqué.
            */
           "cled"           : 10000,
            //IsTempId : Regex qui permet de vérifier s'il s'agit d'un identifiant temporaire
            "rgx_iti"       : /^[\d]+$/,
            "drt_dfvl"      : "bot",
            "srh_fl"        : ["srh_fil_pfl","srh_fil_cnv"],
            //dfnm : DefaultFiltreNaMe
            "srh_dfnm"      : "conversation"
        };
        
        return ds;
    };
    
    var _f_InitConf = function () {
        try {
            if ( KgbLib_CheckNullity(_asdApps) ) {
                return;
            }
            
//            Kxlib_DebugVars([typeof _asdApps.chatbox.xtras.shr !== "undefined", $(".jb-chbx-action[data-action='message_hour']").length],true);
            if ( typeof _asdApps.chatbox.xtras.shr !== "undefined" && $(".jb-chbx-action[data-action='message_hour']").length ) {
                var x = $(".jb-chbx-action[data-action='message_hour']");
                var st = $(x).data("state");
                var active = (st === "active") ? true : false;
//                Kxlib_DebugVars([st,_asdApps.chatbox.xtras.shr,active],true);
                if ( _asdApps.chatbox.xtras.shr !== active ) {
                    $(x).click();
                }
            }
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_AcWdw = function(gbx) {
        try {
            var bx;
            if ( gbx ) {
                if ( $(".jb-chbx-mods.active").data("wdw") === "conv_list" && !$(".jb-chbx-noone-mx").hasClass("this_hide") ) {
                    bx = $(".jb-chbx-noone-mx");
                } else {
                    bx =  $(".jb-chbx-mods.active");
                }
            } else {
                if ( $(".jb-chbx-mods.active").data("wdw") === "conv_list" && !$(".jb-chbx-noone-mx").hasClass("this_hide") ) {
                    bx = "NONE";
                } else if ( $(".jb-chbx-mods.active").data("wdw") === "conv_list" && $(".jb-chbx-noone-mx").hasClass("this_hide") ) {
                    bx = "CONV_LIST";
                } else {
                    bx =  "CONV_THEATER";
                }
            }
            
            return bx;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_PerformAction = function (x) {
        try {
            
            if (KgbLib_CheckNullity(x) | !$(x).data("action") | !_asdApps) {
                return;
            }
            
            var a = $(x).data("action");
//            Kxlib_DebugVars([]);
            switch (a) {
                case "srh_fil_pfl" :
                        _f_SwSrhFil(x);
                    break;
                case "srh_fil_cnv" :
                        _f_SwSrhFil(x);
                    break;
                case "nav_conv_list" :
                        _f_NavConvList();
                    break;
                case "nav_conv_list_og" :
                        //og : OnGoing
                        _f_NavConvList("FIRST");
                    break;
                case "nav_conv_theater" :
                        _f_NavConvTheater(x);
                    break;
                case "pull_cbcnv_odr" :
                        _f_PullCsOdr(x);
                    break;
                case "delete_conv" :
                        _f_InitDelCnvs(x);
                    break;
                case "abort_delconv" :
                        _f_AbDelConvs(x);
                    break;
                case "confirm_delconv" :
                        _f_CfDelConvs(x);
                    break;
                case "pull_cbmsg_odr" :
                        _f_PullMsOdr(x);
                    break;
                case "newmessage" :
                        _f_SubMsg(x);
                    break;
                case "message_hour" :
                        _f_MsgHours(x);
                    break;
                case "delete_messages" :
                        _f_InitDelMsgs(x);
                    break;
                case "abort_delmsg" :
                        _f_AbDelMsgs(x);
                    break;
                case "confirm_delmsg" :
                        _f_CfDelMsgs(x);
                    break;
                case "dlgbx_valid" :
                        _f_ClzDlgBx(x);
                    break;
                case "parley" :
                        _f_NavConvTheater(x);
                    break;
                case "bkthome" :
                        _f_BTHome(x);
                    break;
                default:
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /***************************************************************************************************************/
    /************************************************ CHATBOX SCOPE ************************************************/
    
    var _f_BTHome = function (x) {
        try {
            /*
             * [DEPSUIS 22-06-15]
             * J'avoue que je n'avais pas beaucoup d'inspiration pour savoir exactement que faire de ce bouton donc j'ai opté pour NoOne
             */
            
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            /*
             * [DEPUIS 13-08-15] @BOR
             */
            var at__ = (new Date()).getTime();
            _xhr_abort_srh = at__;
            _xhr_abort_plcs = at__;
            _xhr_abort_plms = at__;
//            Kxlib_DebugVars([TMCHECK ! _ABORT_TIME => "+at__]);
           
            /*
             * [DEPUIS 01-08-15] @BOR
             * On annule certaines opérations XHR en cours 
             */
            if (! KgbLib_CheckNullity(_xhr_srh) ) {
                _xhr_srh.abort();
            }
            if (! KgbLib_CheckNullity(_xhr_plcs) ) {
                _xhr_plcs.abort();
            }
            if (! KgbLib_CheckNullity(_xhr_plms) ) {
                _xhr_plms.abort();
            }
//            _xhr_srh _xhr_plcs _xhr_dlcs _xhr_sbms _xhr_plms _xhr_dlms
            
            //On retire les filtres
            _f_ShwSrhFil();
            
            //Masquer "More"
            _f_ShwMr("conv_list");
            
            //On retire le loader
            _f_ShwLdr("conv_list",false);
            
            //On enlève les résultats de l'ancienne requête
            _f_WpSrhRes();
            
            oqt = "";
            $(".jb-asd-c-h-t-ipt").val("");
            
            _f_NoOne("_GO_INIT,_GO_START_BTN");
                    
           /*
            * [DEPUIS 10-08-15] @BOR
            *   On masque le bouton qui permet d'accéder aux options.
            *   Il est caché car pour l'heure il ne donne accès qu'à une seule option "Del_Conv".
            */
           $(".jb-chbx-opt-tgr[data-wdw='conv_list']").addClass("this_hide");
           
            
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Options = function (x,fc) {
        //fc = ForceClose
        /*
         * Permet d'atteindre la liste des options.
         * La liste dépend de la fenêtre liée au bouton Option.
         */
        try {
            
            if (KgbLib_CheckNullity(x) | !$(x).data("wdw")) {
                return;
            }
            
            var wdw = $(x).data("wdw"), $tgt;
            switch (wdw) {
                case "conv_list" :
                        $tgt = $(".jb-chbx-opt-chcs[data-wdw='conv_list']");
                    break;
                case "conv_theater" :
                        $tgt = $(".jb-chbx-opt-chcs[data-wdw='conv_theater']");
                    break;
                default:
                    return;
                    break;
            }
            
            if (!$tgt.length) {
                return;
            } else if ($tgt.hasClass("this_hide") && !fc) {
                _f_ToglOpt(wdw, true);
                $tgt.focus();
            } else {
                _f_ToglOpt(wdw, false);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /********************************************************************************************************************/
    /************************************************ CONVERSATION SCOPE ************************************************/
    var _f_NoOne = function(opt){
        //opt : OPTion
        /*
         * Gère de manière autonome le cas de NoOne.
         * La méthode vérifie si le bloc contient au moins un de ses "Objets" types.
         * Sinon, il affiche la fenetre NoOne selon le cas de la fenetre active ET/OU des filtres le cas échéant.
         * 
         * Les cas sont :
         *  -> BKHOME
         *  -> CONV_LIST 
         *  -> CONV_LIST + FIL_CONV
         *  -> CONV_LIST + FIL_PFL
         *  -> CONV_THEA
         */
//        if ( KgbLib_CheckNullity(bn) | !$(".jb-chbx-noone-txt").length ) {
//            return;
//        }
        
        try {
            var bn = ( $(".jb-chbx-mods.active").data("wdw") === "conv_list" ) ? "CONV_LIST" : "CONV_THEATER";
            bn = bn.toUpperCase();
            //ElementSeLector, NooneMessageCode
            var esl, nmc, b;
            switch (bn) {
                case "CONV_LIST" :
                        if ($(".jb-chbx-mods.active").data("wdw") !== "conv_list") {
                            //On vérifie que c'est la bonne fenetre qui est active
                            return;
                        }
                        var $fp__ = $(".jb-chbx-srh-fl-chc[data-state='active']");
                        if ($fp__.length === 1 && $fp__.data("action") && $.inArray($fp__.data("action"), _f_Gdf().srh_fl) !== -1) {
                            //On vérifie si on est dans le cas de FIL
                            if ($fp__.data("action") === "srh_fil_cnv") {
                                esl = ".jb-chbx-conv-mdl-mx:not(.parley):not(.jb-skip)";
                                nmc = "CHBX_NOE_FIL_CNV";
                            } else {
                                esl = ".jb-chbx-conv-mdl-mx.parley";
                                nmc = "CHBX_NOE_FIL_PFL";
                            }
                        } else {
                            esl = ".jb-chbx-conv-mdl-mx:not(.parley):not(.jb-skip)";
                            nmc = "CHBX_NOE_INIT";
                        }
                        b = $(".jb-chbx-list-convs");
                    break;
                case "CONV_THEATER" :
                        if ($(".jb-chbx-mods.active").data("wdw") !== "conv_theater") {
                            return;
                        }
                        esl = ".jb-chbx-msgmdl-mx";
                        nmc = "CHBX_NOE_MSG";
                        b = $(".jb-chbx-list-msg");
                    break;
                default :
                    return;
            }
            
            var opt_ar = ( opt && typeof opt === "string" && $.isArray(opt.split(",")) ) ? opt.split(",") : null;
            
            if (! $(esl).length ) {
                /*
                 * Récupération du code correspondant au message à afficher en fonction des options passées en paramètres
                 */
                if ( opt_ar && $.inArray("_GO_INIT",opt_ar) !== -1 && bn === "CONV_LIST" ) {
                    nmc = "CHBX_NOE_INIT";
                } else if ( $.inArray("_GO_NOE",opt_ar) !== -1 ) {
                    nmc = "CHBX_NOE_NOE";
                }
                if ( opt_ar && $.inArray("_GO_START_BTN",opt_ar) !== -1 && bn === "CONV_LIST" ){
//                    $(b).find(".jb-chbx-noone").find(".jb-chbx-noone-sof");
                    $(b).find(".jb-chbx-noone-sof-mx").removeClass("this_hide");
                } else {
                    $(".jb-chbx-noone-sof-mx").addClass("this_hide");
                }
                /*
                 * Afficher le message dans la zone
                 */
                var ms = Kxlib_getDolphinsValue(nmc);
                if ( KgbLib_CheckNullity(ms) ) {
                    return;
                }
                $(b).find(".jb-chbx-noone-txt").text(ms);
                $(b).find(".jb-chbx-noone-mx").removeClass("this_hide");
                
            } else {
                _f_HidNoOne(b);
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_NavConvList = function (opt,xtr) {
        /*
         * Permet d'atteindre la fenêtre qui liste les conversations.
         * CALLER peut envoyé un filtre qui permettra de préciser qu'elles Conversations sont recherchés.
         * Les paramètres par défaut sont :
         *      FIRST 
         *      W_OOG_OPT : (With_OnlyOnGoing_OPTion) Récupérer les premières Conversations
         */
        
        try {
            
            opt = opt || "FIRST";
            
            /*
             * drt : DiRecTion => top, first, bottom
             */
            var pvt, rng, drt, imr = false;
            switch (opt) {
                case "FIRST" :
                    /*
                     * With_OnlyOnGoing_OPTion
                     * Récupérer les premières Conversations 
                     */
                    pvt = null;
//                rng = "RNG_0";
                    drt = "fst";
                    
                    //On masque les résultats
                    _f_WpSrhRes();
                    break;
                    /* [25-01] A utiliser dans le futur pour filtrer les FIRST. Exemple : Les enligne, seulement les femmes, etc ...
                     case "ODR" :
                     /*
                     * OlDeR
                     if ( !$(".jb-chbx-conv-mdl-mx:not(.parley)").length | !$(".jb-chbx-conv-mdl-mx:not(.parley):last").length ) {
                     return;
                     }
                     pvt = $(".jb-chbx-conv-mdl-mx:not(.parley):last");
                     rng = "RNG_0"; //[23-01-15] A cette date, cela ne sert pas vraiment à grand chose quand on prend en compte le mode de foncitonnement
                     drt = "bot";
                     break;
                     case "W_OOG_OPT_NWR" :
                     //With_OnlyOnGoing_OPTion_NeWeR
                     rng;
                     break;
                     case "W_OOG_OPT_LDM" :
                     //With_OnlyOnGoing_OPTion_LoaDMore
                     rng;
                     break;
                     //*/
                default:
                    return;
            }
            
            //On switch vers la fenetre listant les Convrsations
            _f_Vw_NavConvList();
            
            /*
             * [DEPUIS 10-11-15] @author
             *      On masque le Trigger Notif
             */
            _f_NotUpd();
            
            /*
             * [DEPUIS 11-11-15] @author
             *      On retire la donnée "cpi"
             */
            $(".jb-chbx-mods[data-wdw='conv_theater']").data("cvid","");
            
            //On vide l'input
            $(".jb-asd-c-h-t-ipt").val("");
            //On rend "disable" l'input de recherche
            $(".jb-asd-c-h-t-ipt").prop("disabled", true);
            
            //Masquer "More"
            _f_ShwMr("conv_list");
            
            //On masque NoOne
            _f_HidNoOne($(".jb-chbx-list-convs"));
            
            //On masque les filtres
            _f_ShwSrhFil();
            
            //On fait apparaitre le loader
            _f_ShwLdr("conv_list", true);
            
            //On lance la demande coté server
            var s = $("<span/>");
            
            var o = {
                "qsp": "asd",
                "flm": (true) ? "pf" : "tr"
            };
            
            var r__ = _f_Srv_PlCnv(pvt, drt, o.qsp, o.flm, false, s);
            if (!r__) {
                //On rend "enable" l'input de recherche
                $(".jb-asd-c-h-t-ipt").removeProp("disabled");
            }
            
            $(s).on("datasready", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    //On rend "enable" l'input de recherche
                    $(".jb-asd-c-h-t-ipt").removeProp("disabled");
                    return;
                }
                
//            alert(JSON.stringify(d));
                var fil = "conversation";
                //On affiche les résultats
                _f_ShwCnvRslt(d, pvt, o.flm, fil, drt);
                
                //Afficher le Trigger
                _f_ShwMr("conv_list", true);
                
                //On rend "enable" l'input de recherche
                $(".jb-asd-c-h-t-ipt").removeProp("disabled");
                
                //On vide oqt pour permettre une nouvelle recherche avec le même caractère
                oqt = "";
                
                /*
                 * [DEPUIS 10-08-15] @BOR
                 * On affiche le bouton qui permet d'accéder aux options.
                 * Il est caché car pour l'heure il ne donne accès qu'à une seule option "Del_Conv".
                 */
                $(".jb-chbx-opt-tgr[data-wdw='conv_list']").removeClass("this_hide");
                
                /*
                 * [DEPUIS 130815] @BOR
                 *  On libère le pointeur XHR.
                 */
                _xhr_plcs = null;
                
            });
            
            $(s).on("operended", function() {
                //On masque le loader
                _f_ShwLdr("conv_list");
                
                //On met un Message qui indique qu'il n'y a aucune Conversation disponible
                _f_NoOne("_GO_INIT");
                
                //On masque LoadMore
                
                //On rend "enable" l'input de recherche
                $(".jb-asd-c-h-t-ipt").removeProp("disabled");
                
                //On vide oqt pour permettre une nouvelle recherche avec le même caractère
                oqt = "";
                
                /*
                 * [DEPUIS 10-08-15] @BOR
                 * On affiche le bouton qui permet d'accéder aux options.
                 * Il est caché car pour l'heure il ne donne accès qu'à une seule option "Del_Conv".
                 */
                $(".jb-chbx-opt-tgr[data-wdw='conv_list']").addClass("this_hide");
                
                /*
                 * [DEPUIS 130815] @BOR
                 *  On libère le pointeur XHR.
                 */
                _xhr_plcs = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_PullCsOdr = function (x) {
        /*
         * Permet de récupérer les Conversations les plus anciennes en prennant en compte un ou non un pivot.
         */
        try {
            
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            //On lock pour être sur que personne n'y touchera
            $(x).data("lk", 1);
            
            //On fait disparaitre le trigger
            $(".jb-chbx-l-c-ldmr").find(".jb-chbx-rslt-mr").addClass("this_hide");
            
            //On fait apparaitre le spnr
            $(".jb-chbx-l-c-ldmr").removeClass("this_hide").find(".jb-chbx-ldg-spnr").removeClass("this_hide");
            
            /************** RECUPERATION DES DONNÉES ***********/
            var $pvt = $(".jb-chbx-list-convs").find(".jb-chbx-conv-mdl-mx:not(.parley):not(.jb-skip):last");
            if (!$pvt || !$pvt.data("item")) {
                _f_SpRstLdMrTgr("conv_list");
                return;
            }  
            
            var ds = {
                "qsp": "asd",
                "flm": "pf",
//                "rng": "RNG_0", //[23-01-15] A cette date, cela ne sert pas vraiment à grand chose quand on prend en compte le mode de foncitonnement
                //LastMessageId
                "lmi": $pvt.data("item"),
                //DiRecTion
                "drt": "bot",
                //PullConF
                "pcf": false
            };
//            Kxlib_DebugVars([JSON.stringify(ds)],true);
//            return;
            
            var s = $("<span/>");
            
            var r__ = _f_Srv_PlCnv(ds.lmi,ds.drt,ds.qsp,ds.flm,ds.pcf,s);
            if (! r__ ) {
                _f_SpRstLdMrTgr("conv_list");
            }
            
            $(s).on("datasready", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    _f_SpRstLdMrTgr("conv_list");
                    return;
                }
                
                //TODO : Mettre à jour la liste des Conversations affichées au niveau de LDW
                
                //Afficher les Conversations
                _f_ShwCnvRslt(d,$pvt,ds.flm,"conversation","bot");
                
                //Mise à defaut des paramètres
                _f_SpRstLdMrTgr("conv_list");
                
                /*
                 * [DEPUIS 10-08-15] @BOR
                 * On affiche le bouton 
                 */
                _f_ShwLdr("conv_list",true);
                        
                _xhr_plcs = null;
//                Kxlib_DebugVars([ULOCK _xhr_plms ended by datas treated"]);
            });
            
            $(s).on("operended", function(e,d) {
                _f_SpRstLdMrTgr("conv_list");
                
                /*
                 * [DEPUIS 10-08-15] @BOR
                 * On masque le bouton 
                 */
                _f_ShwLdr("conv_list",false);
                
                _xhr_plcs = null;
//                Kxlib_DebugVars([ULOCK _xhr_plms ended by datas treated"]);
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_NavConvTheater = function (x,ip) {
        /*
         * Permet d'atteindre la fenêtre qui liste les messages pour une conversation.
         * On accède à l'interface soit pour "lire" une Conversation soit pour en démarrer une.
         */
        
        try {
            
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
        
//            Kxlib_DebugVars([$(tb).length, $(tb).data("item"), !$(tb).hasClass("parley")],true);
            //tb = TargetBloc, ods = OriginDataS
            var tb = $(x).closest(".jb-chbx-conv-mdl-mx"), d, ods;
            if ( $(tb).length && $(tb).data("item") && $(tb).hasClass("parley") ) {
                //CAS : Aucune conversation existe entre les deux protagonistes
                
//                ods = Kxlib_DataCacheToArray($(x).closest(".jb-chbx-conv-mdl-mx").data("cache"));
                ods = _f_GetCnvDC($(x).closest(".jb-chbx-conv-mdl-mx"));
                if (! ods ) {
                    return;
                }
                
//                Kxlib_DebugVars([JSON.stringify(ods)], true);
//                return;
                d = {
                    uid     : ods.tgtid,
                    upsd    : ods.tgtpsd,
                    ufn     : ods.tgtfn,
                    uppic   : ods.tgtppic,
                    //TOF : TimeOfFirst, la conversation existe depuis ...
                    tof     : null
                };
                
                /*
                d = {
                    uid: ods[0][0],
                    upsd: ods[0][1],
                    ufn: ods[0][2],
                    uppic: ods[0][3],
                    //TOF : TimeOfFirst, la conversation existe depuis ...
                    tof: null
                };
                //*/
                //On affiche la fenetre de la Conversation
                _f_Vw_NavConvTheater(d,true);
                
                /*
                 * On ajoute les données dans la zone BUFFER pour _f_NavConvTheater()
                 */
                _f_NwCnvTheaInLDW(ods);
                
            } else if ( $(tb).length && $(tb).data("item") && !$(tb).hasClass("parley") ) {
                //CAS : La conversation est listée, et on souhaite y accéder
                ods = _f_GetCnvDC($(x).closest(".jb-chbx-conv-mdl-mx"));
                if (! ods ) {
                    return;
                }
//                Kxlib_DebugVars([JSON.stringify(ods)], true);
//                return;
                var $pvt;
                /*
                var $pvt = $(".jb-chbx-l-m-gb").find(".jb-chbx-msgmdl-mx:last");
                if (! KgbLib_CheckNullity($pvt) ) {
                    //On vérifie si on a le bon pivot
                    $pvt =_f_SecPvt($pvt);
                    if (! KgbLib_CheckNullity($pvt) ) {
                        return;
                    }
                }
                */
                
                var ds = {
                    "flm" : "pf",
                    "tgt" : ods.tgtid,
                    //ConversationID
                    "cid" : ods.cvid,
                    //LastMessageId
                    "lmi" : ( $pvt && $pvt.length && $pvt.data("item") ) ? $pvt.data("item") : null,
                    //PullConF
                    "pcf" : false,
                    "wso" : false
                };
                
                if ( KgbLib_CheckNullity(ds.tgt) ) {
                    //HACK
                    return;
                }
                /*
                 * RAPPEL : 
                 * On vide les anciens Messages.
                 * On le fait car _f_PullMs() étant une méthode générique, il ne peut pas décider de Wipe les Messages de lui même.
                 * Si on ne le fait pas, on verra les anciens Messages d'une autre conversation potentielle
                 */
                _f_WpMsRes();
                
                /*
                 * [DEPUIS 10-08-15] @BOR
                 * On réinitialise le marqueur 'EOL'
                 */
                $(".jb-chbx-rslt-mr[data-action='pull_cbmsg_odr']").removeData("EOL");
                
                /*
                 * [DEPUIS 10-08-15] @BOR
                 *      On masque la Pills
                 */
                _f_NotUpd();
                
                //Récupération des Messages
                _f_PullMs(ods,ds);
                
            } else {
                return;
            }
            
            //On insère les données dans la zone BUFFER
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_GetCnvDC = function (el) {
        /*
         * Permet de récupérer les données situées dans le DataCache d'une Entité Conversation.
         * Il peut s'agire d'une Conversation de type "Parley"(non existante) ou une Conversation standard (existante).
         * Cette méthode sert aussi et avant tout à standardiser et améliorer la gestion des données au niveau des DataCache.
         * Toute modification des clés des DC devra donc être répercutée au niveau du schéma des clés.
         * 
         * Selon le type de Conversation, le modèle de clé de données est différent
         * 
         * PARLEY : tgtid,tgtpsd,tgtfn,tgtppic,tgtfols,tgtcap,tgtcbsts
         * CONVERSATION : cvid,cmsgid,cmsgm,cmsgcd,cmsgrd,tgtid,tgtpsd,tgtfn,tgtppic,tgtfols,tgtcap,tgtcbsts
         * 
         * L'autre utilité de cette méthode est de pouvoir fournir un objet contenant un ensemble de paires clé-valeur, ce qui facilite le traitement.
         * 
         * NOTA : Por déterminer le type de DC, on pourra vérifier l'existence d'une propriété exclusive à un modèle. Exemple : cvid
         */
        if ( KgbLib_CheckNullity(el) | !$(el).is(".jb-chbx-conv-mdl-mx") | KgbLib_CheckNullity($(el).data("cache")) ) {
            return;
        }
        
        var dc = Kxlib_DataCacheToArray($(el).data("cache")), r;
        if ( $(el).is(".parley") ) {
            r = {
                "tgtid"     : dc[0][0],
                "tgtpsd"    : dc[0][1],
                "tgtfn"     : dc[0][2],
                "tgtppic"   : dc[0][3],
                "tgtfols"   : dc[0][4],
                "tgtcap"    : dc[0][5],
                "tgtcbsts"  : dc[0][6]
            };
        } else {
            r = {
                "cvid"      : dc[0][0],
                "cmsgid"    : dc[0][1],
                "cmsgm"     : dc[0][2],
                "cmsgcd"    : dc[0][3],
                "cmsgrd"    : dc[0][4],
                "tgtid"     : dc[0][5],
                "tgtpsd"    : dc[0][6],
                "tgtfn"     : dc[0][7],
                "tgtppic"   : dc[0][8],
                "tgtfols"   : dc[0][9],
                "tgtcap"    : dc[0][10]
//                "tgtcbsts"  : dc[0][11] //[07-01-15] Retiré; sens ambigu
            };
        }
        
        return r;
    };
    
    var _f_SpRstLdMrTgr = function (wdw) {
        
        if ( KgbLib_CheckNullity(wdw) ) {
            return;
        }
        
        switch ( wdw ) {
            case "conv_theater" :
                    //On unlock
                    $(".jb-chbx-rslt-mr[data-action='pull_cbmsg_odr']").data("lk",0);

                    //On fait apparaitre le trigger
                    _f_ShwMr("conv_theater",true);        

                    //On fait disparaitre le spnr
                    _f_ShwLdr("conv_theater",false);
                break;
            case "conv_list" :
                    //On unlock
                    $(".jb-chbx-rslt-mr[data-action='pull_cbcnv_odr']").data("lk",0);

                    //On fait apparaitre le trigger
                     $(".jb-chbx-l-c-ldmr").find(".jb-chbx-rslt-mr").removeClass("this_hide");      

                    //On fait disparaitre le spnr
                    $(".jb-chbx-l-c-ldmr").find(".jb-chbx-ldg-spnr").addClass("this_hide");
                break;
            default :
                return;
        }
        
    };
    
    //TimeOut
    var to;
    //OldQueryText
    var oqt;
    var _f_CatchQry = function (x) {
        //isp : IsuPKey
        if ( KgbLib_CheckNullity(x) | !$(".jb-chbx-srh-fl-chc").length | $(".jb-chbx-srh-fl-chc[data-state='active']").length > 1 ) {
            return;
        }
                
        var wi = _f_Gdf().wtt;
        if ( to ) {
            clearTimeout(to);
        }
        //QueryText
        var qt = $(x).val();
        if ( qt.length ) {
            //OperationTimeReFerence
            var otrf = (new Date()).getTime();
//            Kxlib_DebugVars([TMCHECK ! _OTRF_TIME (SRH) => "+(new Date()).getTime()]);
            var f = function() {
//                Kxlib_DebugVars([SRH_OTRF (Just Check) => "+otrf]);
//                return;
//                Kxlib_DebugVars([CHBX : (is ENABLE =>) "+_f_Abort_ChkAuth("_SRH_CONV",otrf),"; (TYPE =>) "+typeof otrf]);
                if (! _f_Abort_ChkAuth("_SRH_CONV",otrf) ) {
                    return;
                }
                
                var o = {
                    "fil": ( $(".jb-chbx-srh-fl-chc[data-state='active']").data("action") === "srh_fil_pfl" ) ? "profil" : "conversation",
                    "qsp": "asd",
                    "flm": ( true ) ? "pf" : "tr",
                    "otrf" : otrf,
                    "drt": "fst"
                };
        
                //On crée le marqueur RNG (RaNGe)
                o.rng = "RNG_1";
//                o.rng = _f_GetRng(o.flm,o.flc);
                
//                Kxlib_DebugVars([JSON.stringify(o)],true);
//                return;
                _f_SrhTrgr(qt,o);
            };
            to = setTimeout(f,wi);
        } else {
            //On reset OLD
            oqt = "";
            
            var qsp = $(x).data("qsp");
            
            //On retire les filtres
            _f_ShwSrhFil();
            
            //Masquer "More"
            _f_ShwMr("conv_list");
            
            //On retire le loader
            _f_ShwLdr("conv_list",false);
            
            //On enlève les résultats de l'ancienne requête
            _f_WpSrhRes();
            
            //On fait analyser par NoOne
            _f_NoOne("_GO_INIT,_GO_START_BTN");
            
        }
        
    };
    
    var _f_SwSrhFil = function(x){
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action") | !( $(".jb-asd-c-h-t-ipt").length && $(".jb-asd-c-h-t-ipt").val() ) ) ) {
            return;
        }
        
        var a = $(x).data("action"), o, v = $(".jb-asd-c-h-t-ipt").val(), fil;
        switch (a) {
            case "srh_fil_pfl" :
                    fil = "profil";
                break;
            case "srh_fil_cnv" :
                    fil = "conversation";
                break;
            default:
                return;
        }
        
       /*
        * [DEPUIS 10-08-15] @BOR
        * On masque le bouton qui permet d'accéder aux options.
        */
        $(".jb-chbx-opt-tgr[data-wdw='conv_list']").addClass("this_hide");
        
        //On switch visuellement les "menus"
        _f_Vw_SwSrhFil(fil);
        
        if (! v.length ) {
            return;
        }
        
        //On fait efface "oqt" sinon la requete ne pourra pas fonctionner
        oqt = "";
        
        var o = {
            "fil"   : fil,
            "qsp"   : "asd",
            "flm"   : ( true ) ? "pf" : "tr",
            "otrf"  : (new Date).getTime(),
            "drt"   : "fst"
        };
        
        _f_SrhTrgr(v,o);
        
    };
    
    var _f_GetRng = function (s,c) {
        //s: Scope; c: Category
        if ( KgbLib_CheckNullity(s) | KgbLib_CheckNullity(c) )
            return;
        
        //r: Range
        var r;
        if ( c === "at" ) {
            r = c+"_rng_1";
        } else {
            var b = ( s === "min" ) ? ".jb-srh-asd-b" : ".jb-srh-hvy-b";
            //tr : TempRange
            var tr = $(b).find(".jb-srh-rslt-list").data("rng");
            var rn = ( tr ) ? tr : 1;
            r = c+"_rng_"+rn;
        }
        
        return r;
    };
    
    var _f_SrhTrgr = function (qt,o) {
        
        try {
            //imr : IsMoreResult 
            if ( KgbLib_CheckNullity(qt) | KgbLib_CheckNullity(o) | !o.hasOwnProperty("drt") ) {
                return;
            }

            if ( qt === oqt ) {
                return false;
            }
            
            /*
             * [DEPUIS 10-08-15] @BOR
             * On masque le bouton qui permet d'accéder aux options.
             */
            $(".jb-chbx-opt-tgr[data-wdw='conv_list']").addClass("this_hide");
            
            var s = $("<span/>");
            
            oqt = qt;
            
            //Masquer "More"
            _f_ShwMr("conv_list");
            
            //On masque NoOne
            _f_HidNoOne($(".jb-chbx-list-convs"));
            
            var pvt = ( o.hasOwnProperty("pvt") && !KgbLib_CheckNullity(o.pvt) ) ? o.pvt : null;
            var drt = ( o.hasOwnProperty("drt") && !KgbLib_CheckNullity(o.drt) ) ? o.drt : "fst";
            var fil = ( o.hasOwnProperty("fil") && !KgbLib_CheckNullity(o.fil) ) ? o.fil : _f_Gdf().srh_dfnm;
            var otrf;
            if ( o.hasOwnProperty("otrf") && !KgbLib_CheckNullity(o.otrf) ) {
                otrf = o.otrf;
            } else {
                return;
            }
            
            //On contacte le serveur
            _f_Srv_SrhTrgr(qt,pvt,fil,drt,o.qsp,o.flm,o.otrf,s);
            
            //On enlève les résultats de la recherche précédente
            if ( !o.hasOwnProperty("drt") | ( o.hasOwnProperty("drt") && o.drt === "fst" ) ) {
                _f_WpSrhRes();
            }
            
            //On affiche le loader
            _f_ShwLdr("conv_list",true);
            
            $(s).on("datasready", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
               /*
                * [DEPUIS 11-08-15] @BOR
                * On vérifie que la fenêtre destinatrice est bien visible et disponible.
                * Cela permet de résoudre le bogue lié au bouton "BkHome" qui ne met pas fin correctement aux processus déjà lancés.
                */
//                Kxlib_DebugVars([CHBX : (is ENABLE =>) "+_f_Abort_ChkAuth("_SRH_CONV",d.otrf),"; (TYPE =>) "+typeof d.otrf]);
                if (! _f_Abort_ChkAuth("_SRH_CONV",d.otrf) ) {
                    //On masque le loader
                    _f_ShwLdr("conv_list", false);
                    //...
                    _f_NoOne("_GO_INIT,_GO_START_BTN");
                    
                    return;
                } 
            
//                Kxlib_DebugVars([1051,JSON.stringify(d)],true);

                //TODO : On insère les données dans la base locale ?
                
                //Affichage des résultats
                if ( o.hasOwnProperty("pvt") && KgbLib_CheckNullity(o.pvt) ) {
                    _f_ShwCnvRslt(d.rds,o.pvt,o.flm,fil,o.drt);
                } else {
                    _f_ShwCnvRslt(d.rds,null,o.flm,fil,o.drt);
                }
                
                //On affiche les filtres
                if ( o.hasOwnProperty("fil") && !KgbLib_CheckNullity(o.fil) ) {
                    _f_ShwSrhFil(true,o.fil);
                }
                
                //On fait traiter par NoOne
                _f_NoOne();
                
                //Afficher "More"
//                _f_ShwMr(o.qsp);
                _xhr_srh = null;
                
               /*
                * [DEPUIS 10-08-15] @BOR
                * On affiche le bouton qui permet d'accéder aux options.
                */
                if ( ( o.hasOwnProperty("fil") && !KgbLib_CheckNullity(o.fil) && o.fil === "conversation" ) && ( d.hasOwnProperty("convrs") && d.convrs ) ) {
                    $(".jb-chbx-opt-tgr[data-wdw='conv_list']").removeClass("this_hide");
                }
                
            });
            
            $(s).on("operended", function(e,otrf) {
                //Masquer "More"
//                _f_HidMr(o.qsp);

                /*
                 * [DEPUIS 11-08-15] @BOR
                 * On vérifie que la fenêtre destinatrice est bien visible et disponible.
                 * Cela permet de résoudre le bogue lié au bouton "BkHome" qui ne met pas fin correctement aux processus déjà lancés.
                 */
//                Kxlib_DebugVars([CHBX : (is ENABLE =>) "+_f_Abort_ChkAuth("_SRH_CONV",otrf),"; (TYPE =>) "+typeof otrf]);
                if (! _f_Abort_ChkAuth("_SRH_CONV",otrf) ) {
                    //On masque le loader
                    _f_ShwLdr("conv_list", false);
                    //...
                    _f_NoOne("_GO_INIT,_GO_START_BTN");
                    
                    return;
                }
                
                //On masque le loader
                _f_ShwLdr("conv_list",false);
                
                //On supprime le résultat de la précédente recherche
                _f_WpSrhRes();
                
                //On fait traiter par NoOne
                _f_NoOne("_GO_NOE");
                
                //On fait analyser la zone par NoOne
                _xhr_srh = null;
                
            });
            
        } catch (ex) {
            _xhr_srh = null;
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_AutoPullCs = function() {
        
        /*
         * Gère les opérations relatives à l'automatisation de la mise à jour des Conversations.
         * 
         * La méthode est lancée depuis un CALLER par intervalle.
         * La méthode suit le cheminement suivant
         *  1- Sommes nous dans un mode "ConvList" ou "ConvTheater"?
         *  2- Il y a t-il une opération "AutoPull" déjà lancée ou autre opération prioritaires ?
         *  3- Il y t-il une opération "SubMs" en cours ?
         *  4- Lancer l'opération de "PushCs" 
         */
        
        /*
         * ETAPE :
         * On vérifie si on est dans le mode "ConvTheater" sans quoi on ne peut pas déclencher le processus de mise à jour.
         * De plus, ce n'est que dans ce mode que l'on peut récupérer les informations qui permettront de récupérer les données auprès du serveur.
         */
        try {
            
            if (! ( ( $(".jb-chbx-mods[data-wdw='conv_theater']").is(".active") || $(".jb-chbx-mods[data-wdw='conv_list']").is(".active") ) 
                && ( $(".jb-chbx-mods[data-wdw='conv_theater']").is(":visible") || $(".jb-chbx-mods[data-wdw='conv_list']").is(":visible") ) 
                && !$(".jb-chbx-srh-fl-chc[data-state='active']").length 
                && $(".jb-chbx-del-cnv-mx").is(".this_hide") ) ) 
            {
                return;
            }
                    
            /*
             * ETAPE :
             * On vérifie s'il y a un processus "AutoPull" (assimilé à un simple pull) qui est déjà lancé. 
             * Dans ce dernier cas, on annule l'opération en le signalant par un code auprès de CALLER
             */
            if (! KgbLib_CheckNullity(_xhr_plcs) ) {
                Kxlib_DebugVars(["Abort bof PLCS"]);
                return;
            }
            
            /*
             * ETAPE :
             * On vérifie s'il y a un processus "AutoPullMs" déjà lancé. 
             * Dans ce dernier cas, on annule l'opération en le signalant par un code auprès de CALLER.
             * EXPLIQUATIONS :
             *      (1) On le fait pour éviter que les requetes se lancent en même temps ce qui a tendance à augmenter le temps des latence pour les autres requetes
             */
            if (! KgbLib_CheckNullity(_xhr_plms) ) {
                Kxlib_DebugVars(["Abort bof PLMS"]);
                return;
            }
            
            /*
             * ETAPE :
             * On vérifie s'il y a un processus "DelCs" qui est déjà lancé. 
             * Dans ce dernier cas, on annule l'opération en le signalant par un code auprès de CALLER
             */
            if (! KgbLib_CheckNullity(_xhr_dlcs) ) {
                Kxlib_DebugVars(["Abort bof DELCS"]);
                return;
            }
            
            /*
             * ETAPE :
             * On lance l'opération de "PullCs" 
             */
            //On récupère les données necessaires au déclenchement de l'opération
            
            var $pvt = $(".jb-chbx-l-m-gb").find(".jb-chbx-msgmdl-mx:last");
            
            /*
             * ETAPE :
             * On vérifie si on a le bon pivot.
             * Si le pivot que l'on a dans l'immediat n'est pas le pivot idéal...
             * ... la méthode nous renverra NULL ce qui obligera le serveur a utilisé la méthode "FIRST".
             * Il ne nous restera qu'à compter sur l'ajout qui procède par élimination.
             */
            if ( !KgbLib_CheckNullity($pvt) && $($pvt).length ) {
                $pvt =_f_SecPvt($pvt);
            }
                    
            var t__ = _asdApps.chatbox.mods.convtheater.xtras;
            var ds = {
                "flm": "pf",
                "tgt": t__.uid,
                //ConversationID
                "cid": t__.cvid,
                //LastMessageId
                "lmi": ( $pvt && $pvt.length && $pvt.data("item") ) ? $pvt.data("item") : null,
                //PullConF
                "pcf": false,
                "wso": false //NOTE : Pour les tests, on peut éviter le mode WSO. Il faudra à ce moment là réduire le temps d'intervalle
            };
//            Kxlib_DebugVars([JSON.stringify(ds)],true);
//            return;
            if ( KgbLib_CheckNullity(ds.tgt) | KgbLib_CheckNullity(ds.cid) ) {
                //HACK
                return;
            }
            
//            Kxlib_DebugVars([JSON.stringify(ds)],true);
//            return;
//            
            //On va chercher les messages sur la Conversation
            var s = $("<span/>");
            
            _f_Srv_CnvPullFMsgs(ds.tgt, ds.flm, ds.cid, ds.lmi, ds.pcf, ds.wso, "bot", s, null);
            
            $(s).on("operended", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                    Kxlib_DebugVars([JSON.stringify(d)],true);
//                    return;
//                    Kxlib_DebugVars([d.cmlist.flist.length,d.cmlist.plist.length],true);
//                    return;
//                if (d.cmlist.flist)
//                    Kxlib_DebugVars([typeof d.cmlist.flist !== "undefined", d.cmlist.flist.length],true);
//                if ( d.cmlist.plist)
//                    Kxlib_DebugVars([typeof d.cmlist.plist !== "undefined", d.cmlist.plist.length],true);
                
                if (!KgbLib_CheckNullity(d.cmlist.flist) && d.cmlist.flist.length) {
                    _f_PushModelMsgList(d.cmlist.flist, d.cutab.oid, d.cvtab.tgtid);
                } else if (!KgbLib_CheckNullity(d.cmlist.plist) && d.cmlist.plist.length) {
                    _f_PushMdlMsgListFrom(d.cmlist.plist, ds.lmi, d.cutab.oid, d.tgttab);
//                    _f_PushMdlMsgListFrom(d.cmlist.plist, ds.lmi, d.cutab.oid, d.cvtab.tgtid); //[DEPUIS 10-08-15] @BOR
                }
                
                //On ajoute les données dans la zone BUFFER
                var d__ = {
                    cvid    : d.cvtab.cid,
                    tgtid   : d.tgttab.tgtid,
                    tgtpsd  : d.tgttab.tgtpsd,
                    tgtfn   : d.tgttab.tgtfn,
                    tgtppic : d.tgttab.tgtppic
                };
//            Kxlib_DebugVars([JSON.stringify(d)],true);
//            return;
                /*
                 * On procède à la mise à jour pour "_f_AutoPullCs()".
                 */
                _f_NwCnvTheaInLDW(d__);
                
                //TODO : On met à jour les données sur la cible
                
                //On affiche "Load More"
                /*
                 * [DEPUIS 10-08-15] @BOR
                 * iEOL : isEndOfList
                 */
                var iEOL = $(".jb-chbx-rslt-mr[data-action='pull_cbmsg_odr']").data("EOL");
                if ( $(".jb-chbx-rslt-mr[data-action='pull_cbmsg_odr']").data("lk") !== 1 && KgbLib_CheckNullity(iEOL) && iEOL !== 1 ) {
                    _f_ShwMr("conv_theater",true);
                }
                
                _xhr_plms = null;
                Kxlib_DebugVars(["ULOCK _xhr_plms ended by datas treated"]);
            });     
        } catch (ex) {
            _xhr_plms = null;
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
  
    };
    
    var _f_ShwCnvRslt = function (rd,pvt,rt,fil,drt) {
//    var _f_ShwCnvRslt = function (rt,rd,scp,imr) {
        //imr : IsMoreResult
        //rt: ResultType, rd; ResultDatas
        try {
            
            if ( KgbLib_CheckNullity(rt) | KgbLib_CheckNullity(rd) | KgbLib_CheckNullity(fil) ) {
                return;
            }
            
//        Kxlib_DebugVars([523,rt,scp],true);
            
            //On masque le loader
            _f_ShwLdr("conv_list", false);
            
            //On retire le marqueur NoOne
            _f_HidNoOne($(".jb-chbx-list-convs"));
            
            //On enlève les résultats de la recherche précédente
            if ( !drt | drt === "fst" ) {
                _f_WpSrhRes();
            }
            
            $(".jb-chbx-conv-mdl-mx.parley").remove(); //?
            //On sélectionne le block
            var b = ".jb-chbx-list-c-mx";
            
            var e;
            
            //On prépare le modèle
            if ( rt === "pf" ) {
                /*
                 var datas;
                 if ( (rd.hasOwnProperty("parleys") && rd.parleys && Array.isArray(rd.parleys)) && (rd.hasOwnProperty("convrs") && rd.convers && Array.isArray(rd.convers)) ) {
                 datas = rd.parleys.concat(rd.convers);
                 } else if ( (rd.hasOwnProperty("parleys") && rd.parleys && Array.isArray(rd.parleys)) && !(rd.hasOwnProperty("convrs") && rd.convers && Array.isArray(rd.convers)) ) {
                 datas = rd.parleys;
                 } else if ( !(rd.hasOwnProperty("parleys") && rd.parleys && Array.isArray(rd.parleys)) && (rd.hasOwnProperty("convrs") && rd.convers && Array.isArray(rd.convers)) ) {
                 datas = rd.convers;
                 } 
                 //*/
                if ( rd.hasOwnProperty("plcnv") && rd.plcnv && Array.isArray(rd.plcnv) && rd.plcnv.length ) {
                    $.each(rd.plcnv, function(x, v) {
                        //On vérifie pour des raisons de fiabilité que l'élément n'est pas déjà présent
                        if (_f_CkResExst(v.uid)) {
                            //                    alert("Exists");
                            return;
                        }
                        
                        e = _f_PprConvMdl(v);
                        //On ReBind le modèle
                        e = _f_RbdCnvMdl(e); 
                        
                        $(e).hide().appendTo(b).fadeIn();
                        //                Kxlib_DebugVars([Ajouté avec => Mode : "+md+"; DataType :"+rt]);
                    });
                }
                if ( fil === "profil" ) {
                    if (rd.hasOwnProperty("parleys") && rd.parleys && Array.isArray(rd.parleys) && rd.parleys.length) {
                        $.each(rd.parleys, function(x, v) {
                            //On vérifie pour des raisons de fiabilité que l'élément n'est pas déjà présent
                            if (_f_CkResExst(v.uid)) {
                                //                    alert("Exists");
                                return;
                            }
                            //On crée le modèle
                            e = _f_PprPflMdl(v);
                            //On ReBind le modèle
                            e = _f_RebdPflMdl(e);
                            
                            $(e).hide().appendTo(b).fadeIn();
                            //                Kxlib_DebugVars([Ajouté avec => Mode : "+md+"; DataType :"+rt]);
                        });
                    }
                } else if (fil === "conversation") {
                    if ( rd.hasOwnProperty("convrs") && rd.convrs && Array.isArray(rd.convrs) && rd.convrs.length ) {
                        $.each(rd.convrs, function(x, v) {
                            //On vérifie pour des raisons de fiabilité que l'élément n'est pas déjà présent
                            if (_f_CkResExst(v.uid)) {
                                //                    alert("Exists");
                                return;
                            }
                            
                            e = _f_PprConvMdl(v);
                            //On ReBind le modèle
                            e = _f_RbdCnvMdl(e); 
                            
                            $(e).hide().appendTo(b).fadeIn();
                            //                Kxlib_DebugVars([Ajouté avec => Mode : "+md+"; DataType :"+rt]);
                        });
                    }
                }
                /*
                 if ( rd.hasOwnProperty("convoid") && rd.convoid && Array.isArray(rd.convoid) && rd.convoid.length ) {
                 $.each(rd.convoid,function(x,v){
                 //On vérifie pour des raisons de fiabilité que l'élément n'est pas déjà présent
                 if ( _f_CkResExst(v.uid) ) {
                 //                    alert("Exists");
                 return;
                 }
                 
                 e = _f_PprConvMdl(v);
                 //On ReBind le modèle
                 e = _f_RbdCnvMdl(e); 
                 
                 $(e).hide().appendTo(b).fadeIn();
                 //                Kxlib_DebugVars([Ajouté avec => Mode : "+md+"; DataType :"+rt]);
                 });
                 }
                 if ( rd.hasOwnProperty("convrs") && rd.convrs && Array.isArray(rd.convrs) && rd.convrs.length  ) {
                 $.each(rd.convrs,function(x,v){
                 //On vérifie pour des raisons de fiabilité que l'élément n'est pas déjà présent
                 if ( _f_CkResExst(v.uid) ) {
                 //                    alert("Exists");
                 return;
                 }
                 
                 e = _f_PprConvMdl(v);
                 //On ReBind le modèle
                 e = _f_RbdCnvMdl(e); 
                 
                 $(e).hide().appendTo(b).fadeIn();
                 //                Kxlib_DebugVars([Ajouté avec => Mode : "+md+"; DataType :"+rt]);
                 });
                 }
                 if ( rd.hasOwnProperty("parleys") && rd.parleys && Array.isArray(rd.parleys) && rd.parleys.length ) {
                 $.each(rd.parleys,function(x,v){
                 //On vérifie pour des raisons de fiabilité que l'élément n'est pas déjà présent
                 if ( _f_CkResExst(v.uid) ) {
                 //                    alert("Exists");
                 return;
                 }
                 //On crée le modèle
                 e = _f_PprPflMdl(v);
                 //On ReBind le modèle
                 e = _f_RebdPflMdl(e);
                 
                 $(e).hide().appendTo(b).fadeIn();
                 //                Kxlib_DebugVars([Ajouté avec => Mode : "+md+"; DataType :"+rt]);
                 });
                 }
                 */
            } else {
                /*
                 //On vérifie pour des raisons de fiabilité que l'élément n'est pas déjà présent
                 if ( _f_CkResExst(scp,v.i) ) {
                 //                    alert("exists");
                 return;
                 }
                 
                 /*
                 * On vérifie que les données vont bien dans la bonne liste.
                 * Ce controle est necessaire dans le cas où on change de menu en cours de route.
                 *
                 var md = $(b).data("dt");
                 if ( rt !== md ) {
                 //                    alert("Mode issue");
                 return;
                 }
                 
                 e = _f_PprTrdMdl(v,scp);
                 
                 $(e).hide().appendTo(b).fadeIn();
                 //                Kxlib_DebugVars([Ajouté avec => Mode : "+md+"; DataType :"+rt]);
                 //*/
            }
            
           /*
            * [DEPUIS 10-08-15] @BOR
            * On masque le bouton qui permet d'accéder aux options.
            */
            $(".jb-chbx-opt-tgr[data-wdw='conv_list']").addClass("this_hide");
            
           /*
            * [DEPUIS 10-08-15] @BOR
            */ 
            if ( ( rd.hasOwnProperty("plcnv") && rd.plcnv && Array.isArray(rd.plcnv) && rd.plcnv.length )
                && ( rd.hasOwnProperty("convrs") && rd.convrs && Array.isArray(rd.convrs) && rd.convrs.length ) )
            {
               /*
                * [DEPUIS 10-08-15] @BOR
                * On affiche le bouton qui permet d'accéder aux options.
                * Il est caché car pour l'heure il ne donne accès qu'à une seule option "Del_Conv".
                */
               $(".jb-chbx-opt-tgr[data-wdw='conv_list']").removeClass("this_hide");
            }
            
            
            return true;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_CkResExst = function (i) {
      //i: Identifiant
      /*
       * Vérifie si une Conversation existe déjà dans la liste.
       */  
       if ( KgbLib_CheckNullity(i) ) {
            return;
        }
        
        var b = $(".jb-chbx-list-c-mx");
//        var b = $(".jb-srh-rslt-list");
        var cn = $(b).find(".jb-chbx-conv-mdl-mx[data-item="+i+"]").length;
        
        return  ( cn ) ? true : false;
    };
    
    var _f_RebdPflMdl = function (el) {
        if ( KgbLib_CheckNullity(el) ) {
            return;
        }
        
        $(el).find(".jb-chbx-lets-spk").click(function(e){
            Kxlib_PreventDefault(e);
            _f_PerformAction(this);
        });
        
        /*
        $(".jb-chbx-action:not(.jb-skip)").click(function(e){
            Kxlib_PreventDefault(e);
            _f_PerformAction(this);
        });
        */
        return el;
    };
    
    var _f_RbdCnvMdl = function (el) {
        if ( KgbLib_CheckNullity(el) ) {
            return;
        }
        
        $(el).click(function(e){
            if ( $(this).is(".jb-skip") ) return;
            
            Kxlib_PreventDefault(e);
            _f_PerformAction(this);
        });
        
        return el;
    };
    
    
    var _f_InitDelCnvs = function (x) {
        /*
         * Gère le cas d'une demande de suppression d'une Conversation.
         * La méthode affiche pour toutes les Conversations affichées, la case à cocher permettant à l'utilisateur de sélectionner le ou les Conversations à supprimer.
         */
        try {
            
            if ( !$(".jb-chbx-mods[data-wdw='conv_list']").is(".active") || !$(".jb-chbx-conv-mdl-mx").length ) {
                return;
            }
            
            var b = $(".jb-chbx-mods[data-wdw='conv_list']");
            var show = ( $(b).find(".jb-chbx-c-m-slct-mx").hasClass("this_hide") ) ? true : false;
            
            //On affiche/masque les cases à cocher au niveau des messages
            _f_TogCsDelChkbx(show);
            
            //On rend "disable" l'input de recherche
            $(".jb-asd-c-h-t-ipt").prop("disabled",true);
            
            //On masque la fenetre des Options
            _f_ToglOpt("conv_list",false);
            
            //On fait apparaitre la barre de décision
//            $(".jb-chbx-nwmsg-ipt-mx").addClass("this_hide");
//            $(".jb-chbx-del-msg-mx").removeClass("this_hide");
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_AbDelConvs = function (x) {
        
        if ( $(".jb-chbx-action[data-action='confirm_delconv']").data("lk") === 1 ) {
            return;
        }
        
        //On masque les cases à cocher
        _f_TogCsDelChkbx(false);
        
        //On rend "disable" l'input de recherche
        $(".jb-asd-c-h-t-ipt").removeProp("disabled");
    };
    
    var _f_CfDelConvs = function (x) {
        /*
         * Gère la confirmation des Conversations.
         */
        try {
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            
            //On vérifie que toutes les conditions necessaires au click du bouton sont réunies
//        Kxlib_DebugVars([$(".jb-chbx-mods[data-wdw='conv_theater']").is(".active"), $(".jb-chbx-del-msg-mx").length, $(".jb-chbx-c-m-slct-ipt").filter(":checked").length],true);
            if (!(
                    $(".jb-chbx-mods[data-wdw='conv_list']").is(".active")
                    && $(".jb-chbx-conv-mdl-mx:not(.parley)").length
                    )) 
            {
                return;
            }
            
            if (! $(".jb-chbx-c-m-slct-ipt").filter(":checked").length ) {
                _f_Dialog("dialog","CHBX_DLGBX_CS_NOSLCT");
                return;
            }
            
            //On récupère les modèles sélectionnés
            var ctab = []; 
            var cl = [];
            $.each($(".jb-chbx-c-m-slct-ipt").filter(":checked"), function(x,el) {
                if ( KgbLib_CheckNullity($(el).closest(".jb-chbx-conv-mdl-mx:not(.parley)").data("item")) || $(el).closest(".jb-chbx-conv-mdl-mx:not(.parley)").attr("dsb") === 1 ) {
                    return true;
                }
                //dsb : DelStandBy : Permet de ne pas être comptabiliser si l'utilisateur tente de lancer un processus de suppression un peu trop tot
                $(el).closest(".jb-chbx-conv-mdl-mx:not(.parley)").attr("dsb",1);
                ctab.push($(el).closest(".jb-chbx-conv-mdl-mx:not(.parley)"));
                cl.push($(el).closest(".jb-chbx-conv-mdl-mx:not(.parley)").data("item").toString());
            });
            if (! ctab.length ) {
                _f_Dialog("dialog","CHBX_DLGBX_CS_NOSLCT");
//                $(".jb-chbx-dlgbx-sprt").removeClass("this_hide");//?
                return;
            }
            
            //TODO : On vérifie dans la pile des opéraition serveur, s'il n'y a pas déjà une opération de suppression en cours 
            
            //TODO : On affiche le message qui demande de patienter ?
    
            var ds = {
                "flm": "pf",
                //ConversationList
                "cl": cl,
                //PullConF
                "pcf": false,
                "wso": false //NOTE : Pour les tests, on peut éviter le mode WSO. Il faudra à ce moment là réduire le temps d'intervalle
            };
//            Kxlib_DebugVars([JSON.stringify(ds)],true);
//            return;
            
            //On contacte le serveur
            var s = $("<span/>");
            _f_Srv_DlCs(ds.cl,ds.flm,ds.pcf,ds.wso,s);
            
            //On retire les messages de la liste
            _f_HidCnv(ctab);
            
            //On masque les case à cocher
            _f_TogCsDelChkbx(false);
            
            $(s).on("operended",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                        
//                alert(JSON.stringify(d));
//                return;
                //On supprime les Conversations qui sont masquées
                $.each(ctab,function(x,cm){
                    $(cm).remove();
                });
                
                //TODO : On retire les Conversations de la mémoire locale
                
                //On fait apparaitre une notification confirmant la suppression
                var N = new Notifyzing();
                var ua = ( cl.length > 1 ) ? "CHBX_CNV_DELs" : "CHBX_CNV_DEL";
                N.FromUserAction(ua);
        
                //On libère la référence
                _xhr_dlcs = null;
                Kxlib_DebugVars(["ULOCK _xhr_plms ended by datas treated"]);
                
                //On rend "enable" l'input de recherche
                $(".jb-asd-c-h-t-ipt").removeProp("disabled");
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_SetIsLkHmStt = function (stt) {
       /*
        * [DEPUIS 11-08-15] @BOR
        *  Permet d'indiquer si on est ou non au niveau de la page HOME.
        *  La page HOME n'existant pas physiquement, il s'agit d'une simulation.
        *  
        * [NOTE 110815] @BOR
        *  A l'origine, ce code source a été faite dans le but de réparer un bogue. Le code s'est révelé inutile car j'ai choisi une autre solution.
        *  Cependant, j'ai quand meme conservé le code source au cas où.
        */
        try {
            if ( KgbLib_CheckNullity(stt) | ( !KgbLib_CheckNullity(stt) && $.inArray(stt,[1,0]) === -1 ) ) {
                return;
            }
            
            if ( KgbLib_CheckNullity(stt) ) {
                $(".jb-chbx-mods[data-wdw='conv_list']").removeData("islhm");
            } else {
                $(".jb-chbx-mods[data-wdw='conv_list']").data("islhm",stt);
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_GetIsLkHmStt = function () {
        try {
            var r__ = $(".jb-chbx-mods[data-wdw='conv_list']").data("islhm");
            return ( KgbLib_CheckNullity(r__) || r__ === 0 ) ? 0 : 1;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /**
     * Permet de vérifier si une opération est autorisée à être mené à termes.
     * Dans ce cas, on vérifie si une demande d'annulation n'est pas encours de traitement.
     * 
     * Les demandes d'annulation ont pour effet de rendre les opérations passées avant la date correspondant à l'annulation caduques.
     * 
     * @param {integer} i
     * @returns {undefined|false|true}
     */
    var _f_Abort_ChkAuth = function (ot,i) {
        /*
         * Indexes :
         *  ot  : OperationType => Le type de l'opération (voir liste des choix)
         *  i   : Identifiant   => L'identifiant de l'opération. Il s'agit souvent d'une valeur facilement incrémentable comme TIMESTAMP
         */
        try {
            if ( KgbLib_CheckNullity(ot) | ( ot && typeof ot !== "string" ) | KgbLib_CheckNullity(i) | ( i && typeof i !== "number" )  ) {
                Kxlib_DebugVars(["CHBX => "+KgbLib_CheckNullity(ot), ( ot && typeof ot !== "string" ), KgbLib_CheckNullity(i), ( i && typeof i !== "number" ), typeof i ]);
                return -1;
            }
            var r = false, ref;
            
            ot = ot.toUpperCase();
            switch(ot) {
                case "_ANY" :
                        ref = _xhr_abort_all;
                    break;
                case "_PL_CONV" :
                        ref = _xhr_abort_plcs;
                    break;
                case "_SRH_CONV" :
                        ref = _xhr_abort_srh;
                    break;
                case "_PL_MSG" :
                        ref = _xhr_abort_plms;
                    break;
                default: 
                    return -2;
            }
            
            /*
             * On vérifie si le pointeur contient une référence de temps.
             */
            if ( KgbLib_CheckNullity(ref) ) {
                r = true;
            } else if ( ref && typeof ref !== "number" ) {
                return -3;
            } else if ( i <= ref ) {
                r = false;
            } else if ( i > ref ) {
                r = true;
            }
            
            /*
             * [NOTE 110815] @BOR
             * Il n'est pas necessaire de réinitialiser la référence.
             * En effet, si on l'a réutilise, la valeur sera réinitialisée, ce qui ne faussera en aucun cas les opérations.
             */
            
            return r;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /**************************************************************************************************************************************************************/
    /*********************************************************************** MESSAGE SCOPE ************************************************************************/
    
    var _f_PullMsOdr = function (x) {
        /*
         * Permet de récupérer les Messages plus anciens d'une Conversation en cours.
         * L'opération se fait à partir d'un pivot.
         */
        try {
            
            if (KgbLib_CheckNullity(x)) {
                return;
            }
            
            if ($(x).data("lk") === 1) {
                return;
            }
            //On lock pour être sur que personne n'y touchera
            $(x).data("lk", 1);
            
            //On fait disparaitre le trigger
            _f_ShwMr("conv_theater", false);        
            
            //On fait apparaitre le spnr
            _f_ShwLdr("conv_theater", true);
            
            /************** RECUPERATION DES DONNÉES ***********/
            var $pvt = $(".jb-chbx-l-m-gb").find(".jb-chbx-msgmdl-mx:first");
            if (!$pvt || !$pvt.data("item")) {
                _f_SpRstLdMrTgr("conv_theater");
                return;
            }  
            
            var t__ = _asdApps.chatbox.mods.convtheater.xtras;
            var ds = {
                "flm": "pf",
                "tgt": t__.uid,
                //ConversationID
                "cid": t__.cvid,
                //LastMessageId
                "fmi": $pvt.data("item"),
                //DiRecTion
                "drt": "top",
                //PullConF
                "pcf": false,
                "wso": false //NOTE : Pour les tests, on peut éviter le mode WSO. Il faudra à ce moment là réduire le temps d'intervalle
            };
//            Kxlib_DebugVars([JSON.stringify(ds)],true);
//            return;
            if ( KgbLib_CheckNullity(ds.tgt) | KgbLib_CheckNullity(ds.cid) ) {
                //HACK
                _f_SpRstLdMrTgr("conv_theater");
                return;
            }
            
            var s = $("<span/>");
            
            var r__ = _f_Srv_CnvPullFMsgs(ds.tgt,ds.flm,ds.cid,ds.fmi,ds.pcf,ds.wso,ds.drt,s,null);
            if (! r__ ) {
                _f_SpRstLdMrTgr("conv_theater");
            }
            
            $(s).on("operended", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    _f_SpRstLdMrTgr("conv_theater");
                    return;
                }
                
//                Kxlib_DebugVars([1735,JSON.stringify(d)],true);
                
                if ( KgbLib_CheckNullity(d.cmlist.plist) || !d.cmlist.plist.length ) {
                    _f_SpRstLdMrTgr("conv_theater");
                    
                    /*
                     * [DEPUIS 10-08-15] @BOR
                     * On retire le bouton "load more"
                     * 
                     * iEOL : isEndOfList
                     */
                    _f_ShwMr("conv_theater",false);
                    _xhr_plms = null;
                    $(".jb-chbx-rslt-mr[data-action='pull_cbmsg_odr']").data("EOL",1);
                    
                    return;
                } 
                
                //On affiche les données
                _f_PushMdlMsgListFrom(d.cmlist.plist,ds.fmi,d.cutab.oid,d.tgttab,ds.drt);
//                _f_PushMdlMsgListFrom(d.cmlist.plist,ds.fmi,d.cutab.oid,d.cvtab.tgtid,ds.drt); //[DEPUIS 10-08-15] @BOR
                
                //Mise à defaut des paramètres
                _f_SpRstLdMrTgr("conv_theater");
                
               /*
                * [DEPUIS 10-08-15] @BOR
                * On affiche le bouton "load more"
                * 
                * [NOTE 10-08-15] @BOR
                * iEOL : isEndOfList
                */
                var iEOL = $(".jb-chbx-rslt-mr[data-action='pull_cbmsg_odr']").data("EOL");
                if ( $(".jb-chbx-rslt-mr[data-action='pull_cbmsg_odr']").data("lk") !== 1 && KgbLib_CheckNullity(iEOL) && iEOL !== 1 ) {
                    _f_ShwMr("conv_theater",true);
                }
               
                _xhr_plms = null;
//                Kxlib_DebugVars([ULOCK _xhr_plms ended by datas treated"]);
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    //RPlc = RightPlace
    var _f_RPlc = function(mm) {
        /*
         * Vérifie si le Message est bien situé.
         * Pour se faire, on vérifie si tous les éléments suivants ont un temps de référence supérieur à celui passé en paramètre.
         * Il faut bien comprendre que la méthode agit sur un élément qui est DEJA affiché dans la liste.
         * REGLES :
         *  La méthode renvoie :
         *      true    : Si le Messagde de référence est bien placé
         *      object  : L'élément qui suit l'élément pivot mais qui a un temps inferieur
         *      2       : Si le Messagde de référence a plusieurs éléments qui le suivent qui ont un temps supérieur
         *  
         *  RAPPEL : Dans le cas 2 (return), il faudrait faire appel à un "reparateur" qui remettrait tous les élements dans l'ordre.
         *  
         *  [19-01-15] @Lou
         *  Ce code ne fonctionne pas comme il a été concu.
         *  Mais dans ce "malheur" nous trouvons quand meme notre bonheur car le code marche avec une grade fiabilité bien qu'elle n'est toujours pas à 100%
         *  
         */
        if ( KgbLib_CheckNullity(mm) | KgbLib_CheckNullity($(mm).data("item")) | KgbLib_CheckNullity($(mm).attr("time")) ) {
            return;
        }
        if ( KgbLib_CheckNullity($(".jb-chbx-msgmdl-mx")) | !$(".jb-chbx-msgmdl-mx").length ) {
            //L'élément sera le seul à être ajouté
            return true;
        }
        
        //rtm : RefTiMe; ri : RefId
        var rtm = $(mm).attr("time"), ri = $(mm).data("item");
        //gn : GlobalNumber; lp : Loop
        var gn = 0, lp = 0;
        var t__ = $(".jb-chbx-msgmdl-mx").toArray().reverse();
        var ps = [];
        $.each(t__,function(x,M){
            /*
             * RAPPEL : 
             * Si la valeur "time" du Message est nulle on ne prend pas ce Message car il n'est pas fiable.
             * Il s'agit certainement d'un Message qui n'a pas encore reçu ses données définitives.
             * Ce dit Message se replacera dans le bon ordre automatiquement, une fois qu'il recevra ses données définitives.
             */
            if ( KgbLib_CheckNullity($(M).data("item")) ) {
                $(M).remove();
                return true;
            }
            if  ( $(M).data("item").toString() === ri.toString() ) {
                Kxlib_DebugVars(["Same item case"]);
//                return false;
            }
//            Kxlib_DebugVars([Previous CASE : "+$(mm).next()+", "+$(mm).prev()+", "+$(mm).prev().attr("time")+", "+parseInt(rtm.toString())+", "+parseInt($(mm).prev().attr("time"))+""]);
            if  ( !$(mm).next() && ( $(mm).prev() && $(mm).prev().attr("time") && parseInt(rtm.toString()) > parseInt($(mm).prev().attr("time")) ) ) {
//                Kxlib_DebugVars([Previous is good case"]);
                return false;
            }
            if  ( KgbLib_CheckNullity($(M).attr("time")) ) {
//                Kxlib_DebugVars([No time case"]);
                return true;
            }
            if ( lp > 15 ) {
                //Pour éviter que ça ne dure trop longtemps OU aboutisse en boucle infinie
//                Kxlib_DebugVars([Non infinite case"]);
                return false;
            }
            
            var Mtm = parseInt($(M).attr("time").toString());
            var Rtm = parseInt(rtm.toString());
            if ( Mtm < Rtm ) {
//            if ( parseInt($(M).attr("time").toString()) > parseInt(rtm.toString()) ) {
                /*
                 * RAPPEL :
                 * Les éléments sont traités en ordre inversé. 
                 * Il est donc logique que ces éléments aient un temps superieur à celui de référence.
                 * Dans le cas contraire, on considère que l'élément de référence est mal placé.
                 */
//                Kxlib_DebugVars([Pushed case : Mtm => "+Mtm+"; rtm => "+Rtm]);
                ps.push($(M));
            }
            ++lp;
            ++gn;
        });
        
        if (! ps.length ) {
            Kxlib_DebugVars(["732 : Well Ranked !"]);
            return true;
        } else if ( ps.length === 1 ) {
            Kxlib_DebugVars(["735 : Bad Ranked 1 !"]);
            return ps[0];
        } else {
            Kxlib_DebugVars(["738 : Bad Ranked MORE ! NB : "+ps.length]);
            return 2;
        }
        
    };       
            
    var _f_SecPvt = function ($p) {
        //p : Pivot
        /*
         * Permet de "sécuriser" le pivot pour éviter les erreurs de type ""BAD_REF".
         * En effet, il n efaut pas que le pivot correspond à un Message avec un identifiant temporaire.
         * Il faut choisir un pivot le plus éloigné possible du premier Message temporaire.
         * Cette solution fiabilise encore plus le processus de mise à jour des Messages.
         * De plus, le fait que l'on puisse n'ajouter que les Messages qui ne sont pas déjà présents, fini de fiabiliser ...
         * ... définitivement les choses.
         */
        try {
            
            if (KgbLib_CheckNullity($p) | !$($p).length | KgbLib_CheckNullity($($p).data("item"))) {
                return;
            }
            //On récupère l'identifiant
            var i = $($p).data("item");
            
            //On vérifie s'il s'agit d'un identifiant temporaire
            if (_f_Gdf().rgx_iti.test(i)) {
                //CAS : On doit chercher un meilleur candidat comme pivot
                
                /*
                 * ETAPE :
                 * On commence par revérifier si le dernier est toujours en "temporaire". 
                 * Il peut arriver qu'entre temps un autre processus a changé les choses.
                 */
                if ( KgbLib_CheckNullity($(".jb-chbx-msgmdl-mx:last")) && $(".jb-chbx-msgmdl-mx:last").length && !_f_Gdf().rgx_iti.test($(".jb-chbx-msgmdl-mx:last").data("item")) ) {
                    return $(".jb-chbx-msgmdl-mx:last");
                }
                
                /*
                 * ETAPE :
                 * On récupère tous les Messages et on sélectionne le premier Message qui a un identifiant définitif.
                 */
                //pvs ! PiVotS
                var pvs__ = $(".jb-chbx-msgmdl-mx").toArray();
//                Kxlib_DebugVars([typeof pvs__,JSON.stringify(pvs__)],true);
                if ( KgbLib_CheckNullity(pvs__) ) {
                    return;
                }
                //On reverse car il nous faut commencer par le Message le plus récent dans la liste
                pvs__.reverse();
                var npvt;
                $.each(pvs__,function(x,M){
                    var i__ = $(M).data("item");
                    //On s'assure que l'entité a bien d'identifiant. Sinon on le skip
                    if ( KgbLib_CheckNullity(i__) ) {
                        return true;
                    }
                    //On s'assure qu'il ne s'agit pas du même élément que celui que CALLER nous a envoyé
                    if ( KgbLib_CheckNullity(i__.toString().toLowerCase() === i.toString().toLowerCase() ) ) {
                        return true;
                    }
                    //On vérifie s'il s'agit du Pivot idéal
                    if (! _f_Gdf().rgx_iti.test(i.toString()) ) {
                        npvt = M;
                        return false;
                    }
                });
                return ( KgbLib_CheckNullity(npvt) ) ? null : npvt;
                
            } else {
                //CAS : Il s'agit du bon candidat
                return $p;
            }
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_SecPvtGet = function (tm) {
        /*
         * Permet de sélectionner le bon pivot en ce qui concerne l'ajout des Messages au niveau de la liste.
         * Les messages sont ajoutés en mode SET ou GET. Le mode SET correspond à celui où l'utilisateur a ajouté un Message.
         */
        try {
            
            if ( KgbLib_CheckNullity(tm) | KgbLib_CheckNullity($(".jb-chbx-msgmdl-mx")) | !$(".jb-chbx-msgmdl-mx").length ) {
                return;
            }
            var $pvt, lp = 0;
            var t__ = $(".jb-chbx-msgmdl-mx").toArray().reverse();
            //Le premier qui correspond c'est le bon
            $.each(t__, function(x,M){
                ++lp;
                /*
                 * RAPPEL : 
                 * Si la valeur "time" du Message est nulle on ne prend pas ce Message car il n'est pas fiable.
                 * Il s'agit certainement d'un Message qui n'a pas encore reçu ses données définitives.
                 * Ce dit Message se replacera dans le bon ordre automatiquement, une fois qu'il recevra ses données définitives.
                 */
                if (!KgbLib_CheckNullity($(M).attr("time").toString()) && parseInt(tm.toString()) > parseInt($(M).attr("time").toString())) {
                    $pvt = $(M);
                    Kxlib_DebugVars(["839 : Nouveau Pivot Get ID => " + $(M).data("item") + "; TIME = " + $(M).attr("time").toString()]);
                    return false;
                }
                /*
                 * [18-01-15] @Lou
                 * Si on arrive pas à trouver de pivot et qu'on arrive au dernier élément, alors ce dernier est le pivot.
                 * Ce cas peut se présenter dans le cas où on vient d'ajouter les Messages FIRST d'une Conversation nouvellement chargé.
                 */
                if ( lp === t__.length ) {
                    $pvt = $(M);
                    Kxlib_DebugVars(["849 : Nouveau (By Default) Pivot Get ID => " + $(M).data("item") + "; TIME = " + $(M).attr("time").toString()]);
                }
            });
                    
            return $pvt;
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_RplMm = function (mm,pvt) {
        /*
         * Déplace un Message pour le replacer à un endroit précis.
         * Le déplacement ce fait via deux actions : "supprimer", replacer.
         * La suppression implique un clonage.
         * Le replacement fait par rapport à un pivot.
         */
        if ( !( !KgbLib_CheckNullity(mm) && $(mm).length && !KgbLib_CheckNullity($(mm).attr("time")) ) && !(KgbLib_CheckNullity(pvt) && $(pvt).length && !KgbLib_CheckNullity($(pvt).attr("time")) ) ) {
            return;
        }
        
        var $nmm = $(mm).clone(true);
        $(mm).remove();
        
        var ibf = ( parseInt($nmm.attr("time").toString()) > parseInt($(pvt).attr("time")) ) ? false : true;
        _f_InsMsgVwInQueueFrom($nmm,pvt,ibf);
    };
    
    var _f_SubMsg = function (x) {
        /*
         * Gère les opérations liées à l'ajout d'un message par l'utilisateur courant.
         */
        try {
            //On vérifie que la fenetre active est bien celle d'une conversation
            if ( $(".jb-chbx-mods.active").data("wdw") !== "conv_theater" ) {
                return;
            }

            //cm = ChatMessage
            var cm = $(".jb-chbx-nwmsg-ipt").val(), $pvt = $(".jb-chbx-msgmdl-mx:last");
            //On vide l'input
            $(".jb-chbx-nwmsg-ipt").val("");
            /*
             * ETAPE :
             * On vérifie si on a le bon pivot.
             * Si le pivot que l'on a dans l'immediat n'est pas le pivot idéal...
             * ... la méthode nous renverra NULL ce qui obligera le serveur a utilisé la méthode "FIRST".
             * Il ne nous restera qu'à compter sur l'ajout qui procède par élimination.
             */
            if (! KgbLib_CheckNullity($pvt) ) {
                $pvt = _f_SecPvt($pvt);
            }
            
            //On vérifie que le message respecte les conditions 
            if ( !_f_Gdf().rgx_msg.test(cm) | _f_Gdf().novoid.test(cm) ) {
                return; //Les conditions etant souples, le temps restreint, on refuse seulement l'envoi sans plus
            }
            
            //[18-01-15] @Lou => Est plus proche de la réalité
            var s__ = $("<span/>"), fet;
            (new TIMEGOD().STUS(s__));
            
            $(s__).on("datasready",function(e,d){
                fet = d;
                Kxlib_DebugVars(["TIME : "+fet+"; MSG : "+cm]);
                //On crée la représentation temporaire du message
                var mm = _f_CrtTmpCurrMsgVw(cm,null,true);

                //On ajoute la vue au niveau de la liste
                _f_InsMsgVwInQueue(mm);
    //            alert(typeof $(".jb-chbx-msgmdl-mx").first().data("item"));
    //            Kxlib_DebugVars([$(mm).data("item"),$(".jb-chbx-msgmdl-mx").filter("[data-item="+$(mm).data("item")+"]").length,$(".jb-chbx-msgmdl-mx").filter("[data-item='"+$(mm).data("item")+"']").length],true);

                //On fait appel au serveur pour authentifier enregistrer le message
                var s = $("<span/>");
    //            Kxlib_DebugVars([asdApps.chatbox.mods.convtheater.xtras.cvid]);
                var ds = {
                    //Message
                    "m"     : cm,
                    "tgt"   : ( typeof _asdApps.chatbox.mods.convtheater.xtras.uid !== "undefined" ) ? _asdApps.chatbox.mods.convtheater.xtras.uid : null,
                    //ConversationID
                    "cid"   : ( typeof _asdApps.chatbox.mods.convtheater.xtras.cvid !== "undefined" ) ? _asdApps.chatbox.mods.convtheater.xtras.cvid : null,
                    //LastMessageId
                    "lmi"   : ( $pvt && $pvt.length && $pvt.data("item") ) ? $pvt.data("item") : null,
                    //PullConF
                    "pcf"   : false
                };
    //            Kxlib_DebugVars([JSON.stringify(ds)],true);
    //            return;
                if ( KgbLib_CheckNullity(ds.tgt) ) {
                    //HACK
                    return;
                }

                //FiLterMenu
                var flm = "pf";
                _f_Srv_ChbxSubM(ds.m,ds.tgt,flm,ds.cid,ds.lmi,fet,ds.pcf,s);

                $(s).on("onerror",function(e){
                   /*
                    * Afficher le message d'erreur.
                    * [TODO]
                    *   
                    */
                    alert("Impossible d'envoyer le message. Vous devez être ami avec le destinataire pour envoyer des messages privés.");
                   /*
                    * Retirer le message de la liste
                    */
                   $(mm).remove();
                });
                
                $(s).on("datasready",function(e,d){
                    if ( KgbLib_CheckNullity(d) ) {
                        //TODO : Mettre le message posté en echec 
                        return;
                    }
                    /*
                     * Données attendues : 
                     *  -> cid      : L'identifiant de la conversation cvid (Sert surtout pour le dernier message créé)
                     *  -> tgttab   : Données sur la cible du message (de la conversation)
                     *  -> cmlist :
                     *      -> cmone  : Données convernant le Message qui vient d'être ajouté
                     *      -> flist  : Données concernant les messages dits "First". Ce tableau est NON NULL quand il n'y a pas de pivot
                     *      -> plist  : (P = previous) Liste les messages anterieurs au messae ajouté mais ajouté après le message pivot. 
                     *      -> ulist  : (U = Ulterior) Liste des messages ulterieur au message créé
                     *  -> cvtab    : Données de base sur la conversation
                     *  -> chbxcnf  : Données sur la configuration de ChatBox pour l'Utilisateur actif
                     */

                    /*
                     * TOUJOURS
                     * (1) Ajouter les informations sur le message (identifiant,time,actid,tgtid)
                     * (2) Ajouter les autres messages récupérer avec le message fraichement ajouté
                     * (3) Mettre à jour les données au niveau de LDW
                     * (4) Mettre à jour les données de Target au niveau de ConvTheater s'il est toujours actif
                     * 
                     * SI CONF EST DISPONIBLE
                     * (51) Mettre à jour la configuration
                     * 
                     * NOUVELLE CONVERSATION
                     * (61) Transformer dans la Liste des résultats de recherche l'objet référence en conversation (En Back?)
                     */
    //                Kxlib_DebugVars([JSON.stringify(d)],true);

                    //On ajoute les données au MessageModel
                    _f_MM_BindDatas($(mm).data("item"),d.cmlist.cmone);

                    if ( d.cmlist.plist && d.cmlist.plist.length > 1 ) {
                        //RAPPEL : On exécute à partir de 2 éléments car s'il n'y a qu'un seul élément il s'agit forcement du message ajouté
                        //on ajoute les messages à la liste des messages en fonction de leur "time"
                        _f_PushMdlMsgListFrom(d.cmlist.plist,ds.lmi,d.cutab.oid,d.tgttab);
//                        _f_PushMdlMsgListFrom(d.cmlist.plist,ds.lmi,d.cutab.oid,d.cvtab.tgtid); //[DEPUIS 10-08-15] @BOR
                    } 

                    //***** Mettre à jour les données au niveau LDW faute de quoi on aurait des opéraitons faussées

                    //Mettre à jour les données de la Conversation (interessant pour la valeur cid dans le cas où elle serait nulle)
                    _asdApps.chatbox.mods.convtheater.xtras = {
                        maininput   : "",
                        cvid        : d.cvtab.cid,
                        uid         : d.tgttab.tgtid,
                        ufn         : d.tgttab.tgtfn,
                        upsd        : d.tgttab.tgtpsd,
                        uppic       : d.tgttab.tgtppic,
                        //TOF : TimeOfFirst, la conversation existe depuis ...
                        tof         : (new Date()).getTime()
                    };
    //                alert(JSON.stringify(_asdApps.chatbox.mods.convtheater.xtras));
    //                Kxlib_DebugVars([JSON.stringify(_asdApps.chatbox.mods.convtheater.xtras),d.cvtab.cid],true);
                    
                    _f_SetAsdApps(_asdApps);

                    //TODO : Ajouter les données de Message au niveau de LDW pour permettre leur persistance

                    _xhr_sbms = null;
                    Kxlib_DebugVars(["_xhr_sbms ended by datas treated"]);
                });
             });

            
        } catch(ex) {
            _xhr_sbms = null;
            Kxlib_DebugVars(["_xhr_sbms ended by CATCH ERR"]);
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_PullMs = function (ods,ds) {
        try {
            
            /*
             * Gère et centralise les opérations relatives au recueil des Messages depuis le server.
             */
            if ( KgbLib_CheckNullity(ods) | KgbLib_CheckNullity(ds) ) {
                return;
            }
            
            var d = {
                uid     : ods.tgtid,
                upsd    : ods.tgtpsd,
                ufn     : ods.tgtfn,
                uppic   : ods.tgtppic,
                //TOF : TimeOfFirst, la conversation existe depuis ...
                tof     : null
            };
            
            //On va chercher les messages sur la Conversation
            var s = $("<span/>");
            
//            Kxlib_DebugVars([JSON.stringify(ds)],true);
//            return;
            if ( KgbLib_CheckNullity(ds.tgt) | KgbLib_CheckNullity(ds.cid) ) {
                //HACK
                return;
            }
            
            _f_Srv_CnvPullFMsgs(ds.tgt, ds.flm, ds.cid, ds.lmi, ds.pcf, ds.wso, "bot", s, null);
            
            //On affiche la fenetre de la Conversation
            _f_Vw_NavConvTheater(d);
            
            //TODO : Afficher un mecanisme de chargement ?
            $(s).on("operended", function(e, d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                    Kxlib_DebugVars([JSON.stringify(d)],true);
//                    return;
//                    Kxlib_DebugVars([d.cmlist.flist.length,d.cmlist.plist.length],true);
//                    return;
                if ( typeof d.cmlist.flist !== "undefined" && d.cmlist.flist.length ) {
                    Kxlib_DebugVars(["1035 : Enter start for FIRST"]);
                    _f_WpMsRes();
                    _f_PushModelMsgList(d.cmlist.flist, d.cutab.oid, d.cvtab.tgtid);
                } else if ( typeof d.cmlist.plist !== "undefined" && d.cmlist.plist.length ) {
                    Kxlib_DebugVars(["1038 : Enter start for PREVIOUS"]);
                    _f_PushMdlMsgListFrom(d.cmlist.plist, ds.lmi, d.cutab.oid, d.tgttab);
//                _f_PushMdlMsgListFrom(d.cmlist.plist,ds.lmi,d.cutab.oid,d.cvtab.tgtid); //[DEPUIS 10-08-15] @BOR
                }
                
                /*
                 * [DEPUIS 10-11-15] @author BOR
                 *      Pour faciliter l'accès à la donnée cvid je le rend accessible depuis le header.
                 */
                var ci__ = ( ods.hasOwnProperty("cvid") && ods.cvid ) ? ods.cvid : null;
                $(".jb-chbx-mods[data-wdw='conv_theater']").data("cvid",ci__);
                
                /*
                 * On procède à la mise à jour des données dans la zone BUFFER pour "_f_PullMs()".
                 */
                _f_NwCnvTheaInLDW(ods);
                
                //TODO : On met à jour les données sur la cible
                
                _xhr_plms = null;
                Kxlib_DebugVars(["ULOCK _xhr_plms ended by datas treated"]);
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    
    var _f_PushModelMsgList = function (d,arf,tgt) {
        //arf : ActorReFerence
        /*
         * Permet d'ajouter des messages à la Conversation sans avoir de modele pivot.
         * On ajoute donc les modèles en mode "append" dans le bloc.
         * 
         * NOTA : Ce processus pourra être amélioré en fonction des résultats obtenus en CRU (Conditions Réelles d'Utilisation).
         */
        try {
            /*
             * TABLE DES CLES DE DONNEES :
             *  cmsgid      : L'identifiant du message
             *  cmsgm       : Le message texte
             *  cmsgcd      : Date de creation du message coté serveur
             *  cmsg_fecd   : Date de creation du message coté FE 
             *  cmsgrd      : Indique si le message par le destinataire
             *  actid       : Identifiant de l'auteur du message
             *  tgtid       : Identifiant du destinataire du message
             */
             if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(arf) | KgbLib_CheckNullity(tgt) ) {
                 return;
             }

             //ibf : IsBeFore; il : IsLeft (le message est placé à Gauche ?)
             var il;
             //On reverse les données du tableau
             d.reverse();
             $.each(d,function(x,el){
                //On vérifie si le Message existe déjà
                if ( _f_MM_Exists($(".jb-chbx-l-m-gb"),el.cmsgid) ) {
                    return true;
                }
    //            Kxlib_DebugVars([act.toString(),el.actid.toString()]);
                //Où place t-on le futur Message ? (Positionnement horizontale)
                il = ( arf.toString() === el.actid.toString() ) ? false : true;

                //On crée la représentation temporaire du message
                var mm = _f_CrtTmpCurrMsgVw(el.cmsgm,il);

                //*** On ajoute la vue au niveau de la liste ***
                /*
                 * ETAPE :
                 * On vérifie s'il existe quand même des Messages. En effet, on peut arriver ici si le pivot a été mis à null car son identifiant était temporaire.
                 * Dans ce cas, on ajoute selon la méthode "FROM". Dans le cas contraire, le Message ira se loger en fin de fil ce qui ne correspond pas forcement à la réalité.
                 * Cette correction est interessante dans le cas où deux personnes s'envoient en quasi-simultanné deux messages.
                 */
                 if ( !KgbLib_CheckNullity($(".jb-chbx-msgmdl-mx")) && $(".jb-chbx-msgmdl-mx").length ) {
                     var pvt = _f_SecPvtGet(el.cmsg_fecd);
                     _f_InsMsgVwInQueueFrom(mm,pvt,false);
                 } else {
                     _f_InsMsgVwInQueue(mm);
                 }

                //On lie les données
                _f_MM_BindDatas($(mm).data("item"),el);

                //temp
                _f_ToBottom($(".jb-chbx-list-msg"));
             });

            /*
             * [DEPUIS 11-11-15] @author BOR
             *      On vérifie si le message n'a pas de date "read" par CU. 
             *      On ajoute la date de lecture dans le cas où CU est la cible du message. 
             */
            _f_NotSetRdMsg();
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_PushMdlMsgListFrom = function (d,mmi,arf,tgt,drt) {
        //arf = ActorReFerence
        /*
         * Permet d'ajouter des messages à la Conversation en prenant un message déjà présent dans la liste comme pivot.
         * De plus, on se réfère à la donnée "time" qui permettra d'ajuster l'odre d'ajout (avant ou après).
         * A savoir qu'en "fonctionnement normal", il est rare qu'un des messages à ajouter soit anterieur au message pivot.
         * Cependant, on pourra tolérer qu'il en ait au moins un. Le but étant de réduire le risque de "rater" un message.
         * Dans ce dernier cas, il est fort probable que ces messages correspondent à des messages appartenant au destinataire.
         * 
         * NOTA : Ce processus pourra être amélioré en fonction des résultats obtenus en CRU (Conditions Réelles d'Utilisation).
         */
        /*
         * TABLE DES CLES DE DONNEES :
         *  cmsgid      : L'identifiant du message
         *  cmsgm       : Le message texte
         *  cmsgcd      : Date de creation du message coté serveur
         *  cmsg_fecd   : Date de creation du message coté FE  
         *  cmsgrd      : Indique si le message par le destinataire
         *  actid       : Identifiant de l'auteur du message
         *  tgtid       : Identifiant du destinataire du message
         *  tgtid       : Identifiant du destinataire du message
         */
        try {
            
            if ( KgbLib_CheckNullity(mmi) && KgbLib_CheckNullity(d) ) {
                return;
            }
            
            var $pvt = $(".jb-chbx-msgmdl-mx[data-item='" + mmi + "']");
            if ( !$pvt.length | !$pvt.attr("time") ) {
                return -1;
            }
            
            //ibf : IsBeFore; il : IsLeft (le message est placé à Gauche ?)
            var ibf = false, il, tm;
            
            drt = ( KgbLib_CheckNullity(drt) ) ? _f_Gdf().drt_dfvl : drt;
            if ( drt !== "top"  ) {
                //On reverse les données du tableau
                d.reverse();
            }
            var hsnw = 0;
            $.each(d, function(x,el) {
                //On vérifie si le Message existe déjà
                if (_f_MM_Exists($(".jb-chbx-l-m-gb"), el.cmsgid)) {
//                Kxlib_DebugVars([l.cmsgid]);
                    return true;
                } 
                
                hsnw += 1;
                
                //Où place t-on le futur Message ? (Positionnement horizontale)
                il = (arf.toString() === el.actid.toString()) ? false : true;
                
                //On crée la représentation temporaire du message
                var mm = _f_CrtTmpCurrMsgVw(el.cmsgm, il);
                
                //Pour garantir une meilleure fiabilité, on procède obligatoirement à une vérificationd de pivot. A cause des temps de latences non constant, l'emplacement peut être biaisé
                if ( !KgbLib_CheckNullity($(".jb-chbx-msgmdl-mx")) && $(".jb-chbx-msgmdl-mx").length ) {
                    $pvt = _f_SecPvtGet(el.cmsg_fecd);
                    /*
                    var t__ = $(".jb-chbx-msgmdl-mx").toArray().reverse();
                    //Le premier qui correspond c'est le bon
                    $.each(t__, function(x, M) {
                        if (parseInt(el.cmsg_fecd.toString()) > parseInt($(M).attr("time").toString())) {
//                            Kxlib_DebugVars([Nouveau trouvé : "+$(M).data("item")]);
                            $pvt = $(M);
                            return false;
//                         _f_InsMsgVwInQueueFrom(mm,$(M),false);
                        }
                    });
                    //*/
                }
                if ( drt === "top"  ) {
                    ibf = true;
                } else {
                    tm = $pvt.attr("time");
                    //Où place t-on le futur Message ? (Positionnement vertical)
                    ibf = (parseInt(el.cmsg_fecd.toString()) > parseInt(tm.toString())) ? false : true;
                }
                
                //On ajoute la vue au niveau de la liste en fonction du pivot
                _f_InsMsgVwInQueueFrom(mm,$pvt,ibf,drt);
                
                //On lie les données
                _f_MM_BindDatas($(mm).data("item"), el);
                
                //On déclare le nouveau pivot qui correspond au Message déjà ajouté
                $pvt = $(".jb-chbx-msgmdl-mx[data-item='" + el.cmsgid + "']");
            });
            
            /*
             * [DEPUIS 10-08-15] @BOR
             * On met à les données sur la cible.
             * On met à jour le pseudo et l'image de profil.
             */
            if ( !KgbLib_CheckNullity(tgt) && !KgbLib_CheckNullity(tgt.tgtpsd) && !KgbLib_CheckNullity(tgt.tgtppic) ) {
                $(".jb-chbx-ubox-tgt-psd").text(Kxlib_ValidUser(tgt.tgtpsd));
                $(".jb-chbx-ubox-tgt-ppic").attr("src",tgt.tgtppic);
                $(".jb-chbx-ubox-tgt-mx").attr("href","/"+tgt.tgtpsd);
            }
            
            /*
             * [DEPUIS 11-11-15] @author BOR
             *      On vérifie si le message n'a pas de date "read" par CU. 
             *      On ajoute la date de lecture dans le cas où CU est la cible du message. 
             */
            _f_NotSetRdMsg();
            
            /*
             * [DEPUIS 11-07-15] @BOR
             * On lance un signale sonore si l'utilisateur n'a pas de focus sur la page mais que les indices indiquent qu'il est en plein tchat.
             */
            if ( drt === "bot" && $(".jb-chbx-list-msg").is(":visible") === true && window.hasfocus === false && hsnw ) {
                var aSound = document.createElement('audio');
                aSound.setAttribute('src', Kxlib_GetExtFileURL("sys_url_aud", "tqr-mgding.wav"));
                aSound.volume = 0.5;
                aSound.play();
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_NotSetRdMsg = function () {
        try {
            if ( !$(".jb-chbx-msgmdl-mx").filter("[data-direction='lbdr']").length ) {
                return;
            }
            
            if (! KgbLib_CheckNullity(_xhr_sbums) ) {
                return;
            }
            
            var lbdr = $(".jb-chbx-msgmdl-mx").filter("[data-direction='lbdr']");
            
            /*
             * ETAPE :
             *      On vérifie qu'il existe des messages dont CU est le destinataire qui n'ont pas de date de lecture.
             */
            var a = [];
            $.each(lbdr,function(x,mm){
                if ( KgbLib_CheckNullity($(mm).data("mrd")) ) {
                    a.push({
                        "i" : $(mm).data("item")
                    });
                }
            });
            
            
            /*
             * ETAPE :
             *      On vérifie si les conditions sont réunies pour déclarer les messages comme lus.
             * REGLES :
             *      (1) La fenetre a le focus, et l'utilisateur est sur la fenetre où sont affichées les messages
             */
            if ( a && a.length && _f_AcWdw() === "CONV_THEATER" && window.hasfocus === true ) {
//                Kxlib_DebugVars(["A SNITCHER => ",JSON.stringify(a)]);
                /*
                 * ETAPE : 
                 *      On récupère le time serveur qui permettra de signaler la lecture des messages.
                 *      Dès qu'on a la réponse, on lance l'opération SET_UNREAD
                 */
                var s1 = $("<span/>");
                (new TIMEGOD().STUS(s1));
                var t1__ = (new Date()).getTime();
                $(s1).on("datasready",function(e,d){
                    var nw = d, t2__ = (new Date()).getTime();
                    Kxlib_DebugVars(["A SNITCHER 2 => ","TIME : "+nw+"; MSG_IDS : "+JSON.stringify(a),t2__-t1__]);
                    
                    var s2 = $("<span/>");
                    _f_Srv_NotSn(a,nw,s2);
                    
                    $(s2).on("datasready",function(e,ds){
                        if ( KgbLib_CheckNullity(ds) ) {
                            return;
                        }
                        
                        Kxlib_DebugVars(["A SNITCHER 3 => ",JSON.stringify(ds)]);
                        
                        /*
                         * ETAPE :  
                         *      On mets à jour les données sur les messages.
                         *      A cette date, on traite les données ici mais il est possible qu'on ait besoin de les traiter dans une fonction indépendante dans le futur, en fonction des cas.
                         */
                        if ( _f_AcWdw() === "CONV_THEATER" ) {
                            $.each(ds.jrmis,function(x,md){
                                var mb = $(".jb-chbx-msgmdl-mx[data-item='"+md.i+"']");
                                if ( $(mb).length ) {
                                    $(mb).data("mrd",md.t);
                                }
                                Kxlib_DebugVars(["A SNITCHER 4 => ",$(mb).data("item"),$(mb).data("mrd")]);
                            });
                        }
                        
                        _xhr_sbums = null;
                    });
                    
                    $(s2).on("operended",function(){
                        _xhr_sbums = null;
                    });
                });
                
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_AutoPullMs = function() {
        
        /*
         * Gère les opérations relatives à l'automatisation de la mise à jour des Messages.
         * 
         * La méthode est lancée depuis un CALLER par intervalle.
         * La méthode suit le cheminement suivant
         *  1- Sommes nous dans un mode "ConvTheater"
         *  2- Il y a t-il une opération "AutoPull" déjà lancée ?
         *  3- Il y t-il une opération "SubMs" en cours ?
         *  4- Lancer l'opération de "PushMs" en mode WITH_STAY_OPTION
         */
        
        /*
         * ETAPE :
         * On vérifie si on est dans le mode "ConvTheater" sans quoi on ne peut pas déclencher le processus de mise à jour.
         * De plus, ce n'est que dans ce mode que l'on peut récupérer les informations qui permettront de récupérer les données auprès du serveur.
         */
        try {
            /*
             * [DEPUIS 13-08-15] @BOR
             *  On vérifie qu'on se trouve sur une Conversation ouverte.
             *  Cette modification permet de régler un bogue concernant l'indisponible d'un identifiant de Conversation persistant.
             *  De plus, cela ne correspondrait pas à la version beta1 que de laisser cette vérification s'effectuer.
             *  En effet, cela ne servirait que dans le cas où on vérifie la présence de Messages non lues ou de nouvelles conversation.
             */
            if (! ( $(".jb-chbx-mods[data-wdw='conv_theater']").is(".active") 
                    && $(".jb-chbx-mods[data-wdw='conv_theater']").is(":visible") ) ) 
            {
                return;
            }
            
            /*
             * ETAPE :
             * On vérifie s'il y a un processus "AutoPull" (assimilé à un simple pull)qui est déjà lancé. 
             * Dans ce dernier cas, on annule l'opération en le signalant par un code auprès de CALLER
             */
            if (! KgbLib_CheckNullity(_xhr_plms) ) {
                Kxlib_DebugVars(["Abort bof PLMS"]);
                return;
            }
            
            /*
             * ETAPE :
             * On vérifie s'il y a un processus "SubMs" qui est déjà lancé. 
             * Dans ce dernier cas, on annule l'opération en le signalant par un code auprès de CALLER
             */
            if (! KgbLib_CheckNullity(_xhr_sbms) ) {
                Kxlib_DebugVars(["Abort bof SUBMS"]);
                return;
            }
            
            /*
             * ETAPE :
             * On vérifie s'il y a un processus "DelMs" qui est déjà lancé. 
             * Dans ce dernier cas, on annule l'opération en le signalant par un code auprès de CALLER
             */
            if (! KgbLib_CheckNullity(_xhr_dlms) ) {
                Kxlib_DebugVars(["Abort bof DELMS"]);
                return;
            }
            
            /*
             * ETAPE :
             * On lance l'opération de "PullMs" en mode WITH_STAY_OPTION 
             */
            //On récupère les données necessaires au déclenchement de l'opération
            
            var $pvt = $(".jb-chbx-l-m-gb").find(".jb-chbx-msgmdl-mx:last");
            
            /*
             * ETAPE :
             * On vérifie si on a le bon pivot.
             * Si le pivot que l'on a dans l'immediat n'est pas le pivot idéal...
             * ... la méthode nous renverra NULL ce qui obligera le serveur a utilisé la méthode "FIRST".
             * Il ne nous restera qu'à compter sur l'ajout qui procède par élimination.
             */
            if (! KgbLib_CheckNullity($pvt) ) {
                $pvt =_f_SecPvt($pvt);
            }
            
            var t__ = _asdApps.chatbox.mods.convtheater.xtras;
            var ds = {
                "flm": "pf",
                "tgt": t__.uid,
                //ConversationID
                "cid": t__.cvid,
                //LastMessageId
                "lmi": ( $pvt && $pvt.length && $pvt.data("item") ) ? $pvt.data("item") : null,
                //PullConF
                "pcf": false,
                "wso": false //NOTE : Pour les tests, on peut éviter le mode WSO. Il faudra à ce moment là réduire le temps d'intervalle
            };
//            Kxlib_DebugVars([JSON.stringify(ds)],true);
//            return;
            if ( KgbLib_CheckNullity(ds.tgt) | KgbLib_CheckNullity(ds.cid) ) {
                //HACK
                return;
            }
            
//            Kxlib_DebugVars([JSON.stringify(ds)],true);
//            return;
//            
            //On va chercher les messages sur la Conversation
            var s = $("<span/>");
            
            _f_Srv_CnvPullFMsgs(ds.tgt, ds.flm, ds.cid, ds.lmi, ds.pcf, ds.wso, "bot", s, true);
                    
            $(s).on("operended", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                    Kxlib_DebugVars([JSON.stringify(d)],true);
//                    return;
//                    Kxlib_DebugVars([d.cmlist.flist.length,d.cmlist.plist.length],true);
//                    return;
//                if (d.cmlist.flist)
//                    Kxlib_DebugVars([typeof d.cmlist.flist !== "undefined", d.cmlist.flist.length],true);
//                if ( d.cmlist.plist)
//                    Kxlib_DebugVars([typeof d.cmlist.plist !== "undefined", d.cmlist.plist.length],true);
                
                if ( !KgbLib_CheckNullity(d.cmlist.flist) && d.cmlist.flist.length ) {
                    _f_PushModelMsgList(d.cmlist.flist, d.cutab.oid, d.cvtab.tgtid);
                    d.cmlist.flist.length;
                } else if ( !KgbLib_CheckNullity(d.cmlist.plist) && d.cmlist.plist.length ) {
                    _f_PushMdlMsgListFrom(d.cmlist.plist, ds.lmi, d.cutab.oid, d.tgttab);
//                    _f_PushMdlMsgListFrom(d.cmlist.plist, ds.lmi, d.cutab.oid, d.cvtab.tgtid); //[DEPUIS 10-08-15] @BOR
                    d.cmlist.plist.length;
                }
                
                /*
                 * On ajoute les données dans la zone BUFFER
                 */
                var d__ = {
                    cvid    : d.cvtab.cid,
                    tgtid   : d.tgttab.tgtid,
                    tgtpsd  : d.tgttab.tgtpsd,
                    tgtfn   : d.tgttab.tgtfn,
                    tgtppic : d.tgttab.tgtppic
                };
//            Kxlib_DebugVars([JSON.stringify(d)],true);
//            return;

                /*
                 * On procède à la mise à jour des données dans la zone BUFFER pour "_f_AutoPullMs()".
                 */
                _f_NwCnvTheaInLDW(d__);
                
                //TODO : On met à jour les données sur la cible
                
                //On affiche "Load More"
                /*
                 * [DEPUIS 10-08-15] @BOR
                 * iEOL : isEndOfList
                 */
                var iEOL = $(".jb-chbx-rslt-mr[data-action='pull_cbmsg_odr']").data("EOL");
                if ( $(".jb-chbx-rslt-mr[data-action='pull_cbmsg_odr']").data("lk") !== 1 && KgbLib_CheckNullity(iEOL) && iEOL !== 1 ) {
                    _f_ShwMr("conv_theater",true);
                }
                
                _xhr_plms = null;
//                Kxlib_DebugVars([ULOCK _xhr_plms ended by datas treated"]);
            });     
        } catch (ex) {
            _xhr_plms = null;
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
  
    };
    
    /********************************************************************** EXTRAS MESSAGE SCOPE *****************************************************************/
    
    var _f_MM_Exists = function(b,i) {
        /*
         * Vérifie si le Message avec l'identifiant passé en paramètre existe dans la Conversation.
         * Pour des raisons de simplification, on exige de passer le bloc qui sert de contenant.
         */
        if ( KgbLib_CheckNullity(b) | !$(b).length | KgbLib_CheckNullity(i) ) {
            return;
        }
        return ( $(b).find(".jb-chbx-msgmdl-mx[data-item='"+i+"']").length ) ? true : false;
    };
    
    var _f_MM_BindDatas = function (mmi,d) {
        //mmmi = MessageModelId, d = Datas
        /**
         * Permet de lier les données concernant un message à un modèle de message.
         * Cette insertion est necessaire car les messages ajoutés ne sont pas authentiques.
         * Sans ses données, ils ne pourront pas servir comme message pivot.
         */
        /*
         * TABLE DES CLES DE DONNEES :
         *  cmsgid      : L'identifiant du message
         *  cmsgm       : Le message texte
         *  cmsguic     : Liste des URLS_IN_CONTENT
         *  cmsgcd      : Date de creation du message coté serveur
         *  cmsg_fecd   : Date de creation du message coté FE
         *  cmsgrd      : Indique si le message par le destinataire
         *  actid       : Identifiant de l'auteur du message
         *  tgtid       : Identifiant du destinataire du message
         */
//        Kxlib_DebugVars([mmi,JSON.stringify(d),$(".jb-chbx-msgmdl-mx[data-item='"+mmi+"']").length],true);
        try {
            if ( KgbLib_CheckNullity(mmi) | KgbLib_CheckNullity(d) | !$(".jb-chbx-msgmdl-mx[data-item=" + mmi + "]").length ) {
                return;
            }
            
            var mm = $(".jb-chbx-msgmdl-mx[data-item=" + mmi + "]");
            
            //On vérifie si le Message a déjà été ajouté par en mode PULL
            if (_f_MM_Exists($(".jb-chbx-l-m-gb"),d.cmsgid)){
                $(mm).remove();
                //Avant de repartir on vérifie que l'élément déjà présent est bien placé
                var $nmm = $(b).find(".jb-chbx-msgmdl-mx[data-item='"+d.cmsgid+"']");
                var y__ = _f_RPlc($nmm);
                if ( !KgbLib_CheckNullity(y__) && typeof y__ === "object" ) {
                    _f_RplMm($nmm,y__);
                    Kxlib_DebugVars(["1368 : Replaced after Binding -1"]);
                }
                return -1;
//                return -1;
            } 
            
            /*
             * On ajoute l'identifiant
             */
            $(mm).data("item",d.cmsgid.toString());
            $(mm).attr("data-item",d.cmsgid.toString()); //Pour pouvoir utiliser le selecteur "[data-item]"
            
            /*
             * [DEPUIS 11-11-15] @author BOR
             *      On ajoute la données sur la date de lecture. Cela permettra par la suite d'effectuer une mettre à jour des données au niveau de la BDD
             */
            if (! KgbLib_CheckNullity(d.cmsgrd) ) {
                $(mm).data("mrd",d.cmsgrd);
                $(mm).attr("data-mrd",d.cmsgrd);
            }
            
            
            /*
             * [DEPUIS 15-11-15] @author BOR
             *      
             */
            /*
            if ( !KgbLib_CheckNullity(d.cmsguic) && $.isArray(d.cmsguic) && d.cmsguic.length ) {
//                console.log("RENTRE > ",d.cmsgid);
                var nm = Kxlib_ActivateURLIC(d.cmsgm,d.cmsguic);
                $(mm).find(".jb-chbx-msgmdl-msg").html(nm);
                
                $(mm).find(".jb-chbx-msgmdl-msg").find(".jb-tqr-uic-a").addClass("mi-case");
            } else {
                var m_ = Kxlib_Decode_After_Encode(d.cmsgm);
                $(mm).find(".jb-chbx-msgmdl-msg").html(m_);
            }
            //*/
            
            var ustgs = d.cmsgm_ustgs;
            var hashs = d.cmsgm_hashs;
            
            var m_ = Kxlib_Decode_After_Encode(d.cmsgm);
            m_ = $("<div/>").html(m_).text();
            var rtxt = Kxlib_TextEmpow(m_,ustgs,hashs,null,{
                "ena_inner_link" : {
//                    "local" : true, //DEV, DEBUG, TEST
                    "all"   : false,
                    "only"  : "fksa"
                },
                emoji : {
                    "size"          : 36,
                    "size_css"      : 18,
                    "position_y"    : 3
                }
            });

            $(mm).find(".jb-chbx-msgmdl-msg").text("").append(rtxt);
            
//            Kxlib_DebugVars([d.cmsgid.toString(),$(".jb-chbx-msgmdl-mx[data-item=" + d.cmsgid.toString() + "]").length, $(".jb-chbx-msgmdl-mx[data-item=" + d.cmsgid.toString() + "]").data("item")], true);
            
            /*
             * On ajoute la donnée "time"
             */
            $(mm).attr("time", d.cmsg_fecd);
            
            /*
             * On ajoute les données dans DataCache
             */
            var d__ = [d.cmsgid, d.cmsgm, d.cmsg_fecd, d.cmsgrd, d.actid, d.tgtid];
            $(mm).data("cache", "[" + d__.toString() + "]");
            
            //Avant de repartir on vérifie que l'élément déjà présent est bien placé
            var y__ = _f_RPlc(mm);
            if ( !KgbLib_CheckNullity(y__) && typeof y__ === "object" ) {
                _f_RplMm(mm,y__);
                Kxlib_DebugVars(["1387 : Replaced after Binding"]);
            }
            
            return mm;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_MsgHours = function(x) {
        /*
         * Gère les opérations relatives à l'affichage de l'heure au niveau des messages.
         * La fonction permet d'afficher ou non l'heure en fonction des desirs de l'utilsateur.
         */
        try {
            
//        Kxlib_DebugVars([KgbLib_CheckNullity(x), !$(x).data("state"), !_f_Gdf().chkbl_stt.test($(x).data("state"))],true);
            if ( !$(".jb-chbx-mods[data-wdw='conv_theater']").is(".active") | KgbLib_CheckNullity(x) | !$(x).data("state") | !_f_Gdf().chkbl_stt.test($(x).data("state"))) {
                return;
            }
            
            var st = $(x).data("state"), show = (st === "active") ? false : true;
            var btm = ( show | !$(".jb-chbx-msgmdl-slct").hasClass("this_hide") ) ? true : false;
            
            //On affiche l'heure au niveau des messages
            _f_ToglHr(show, btm, btm);
            
            //On enregistre la configuration au niveau du serveur
            
            //On effectue le changement visuel au niveau des options. Cette donnée pourra être modifier par le retour serveur
            _f_Opt_SwAct(x);
            var wdw = $(x).closest(".jb-chbx-opt-chcs").data("wdw");
            _f_ToglOpt(wdw,false);
            
            //On change localement la configuration 
//                Kxlib_DebugVars([typeof _asdApps, "asdApps" in sessionStorage, "chatbox" in sessionStorage.asdApps, "xtras" in sessionStorage.asdApps.chatbox],true);
//            Kxlib_DebugVars([typeof _asdApps, "asdApps" in sessionStorage],true);
            if ( _asdApps && "chatbox" in _asdApps && "xtras" in _asdApps.chatbox ) {
                _asdApps.chatbox.xtras.shr = show;
                sessionStorage.setItem("asdApps",JSON.stringify(_asdApps));
            }
                    
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
        
    };
    
    var _f_InitDelMsgs = function (x) {
        /*
         * Gère le cas d'une demande de suppression de messages dans une conversation.
         * La méthode affiche pour tous les messages affichés la case à coche permettant à l'utilisateur de sélectionner le ou les messages à supprimer.
         */
        try {
            
            if (! $(".jb-chbx-mods[data-wdw='conv_theater']").is(".active") || !$(".jb-chbx-msgmdl-mx").length  ) {
                return;
            }
            
            //On bloque toutes les activités qui peuvent rentrer en interférence avec l'opération de suppression
            _act_lkr.shrcs = true;
            _act_lkr.plcs = true;
            _act_lkr.dlcs = true;
            _act_lkr.subms = true;
            _act_lkr.plms = true;
//            _act_lkr.dlms = true; /[DEPUIS 12-07-15] @BOR
            
            var b = $(".jb-chbx-mods[data-wdw='conv_theater']");
            var show = ( $(b).find(".jb-chbx-msgmdl-slct").hasClass("this_hide") ) ? true : false;
            var btm = ( $(".jb-chbx-opt-chc-tgr[data-action='message_hour']").data("state") === "active" | $(".jb-chbx-msgmdl-slct").hasClass("this_hide") ) ? true : false;
            
            //On affiche/masque les cases à cocher au niveau des messages
            _f_TogMsDelChkbx(show, btm);
            
            //On masque la fenetre des Options
            var wdw = $(x).closest(".jb-chbx-opt-chcs").data("wdw");
            _f_ToglOpt(wdw,false);
            
            //On fait apparaitre la barre de décision
            $(".jb-chbx-nwmsg-ipt-mx").toggleClass("this_hide");
            $(".jb-chbx-del-msg-mx").toggleClass("this_hide");
//            $(".jb-chbx-nwmsg-ipt-mx").addClass("this_hide");
//            $(".jb-chbx-del-msg-mx").removeClass("this_hide");
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_AbDelMsgs = function (x) {
        
        if ( $(".jb-chbx-action[data-action='confirm_delmsg']").data("lk") === 1 ) {
            return;
        }
        
        //On masque les cases à cocher
        var btm = ( $(".jb-chbx-opt-chc-tgr[data-action='message_hour']").data("state") === "active" ) ? true : false;
        _f_TogMsDelChkbx(false, btm, btm);
        
        //On switch les barres
        $(".jb-chbx-nwmsg-ipt-mx").removeClass("this_hide");
        $(".jb-chbx-del-msg-mx").addClass("this_hide");
        
        //On débloque les "lockers"
        _act_lkr.shrcs = false;
        _act_lkr.plcs = false;
        _act_lkr.dlcs = false;
        _act_lkr.subms = false;
        _act_lkr.plms = false;
        _act_lkr.dlms = false;
    };
    
    var _f_CfDelMsgs = function (x) {
        /*
         * Gère la confirmation des Messages. Ce qui implique la suppression effective.
         * 
         */
        try {
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            
            //On vérifie que toutes les conditions necessaires au click du bouton sont réunies
//        Kxlib_DebugVars([$(".jb-chbx-mods[data-wdw='conv_theater']").is(".active"), $(".jb-chbx-del-msg-mx").length, $(".jb-chbx-msgmdl-slct").filter(":checked").length],true);
            if (!(
                    $(".jb-chbx-mods[data-wdw='conv_theater']").is(".active")
                    && $(".jb-chbx-del-msg-mx").length
                    )) 
            {
                return;
            }
            
            if (! $(".jb-chbx-msgmdl-slct").filter(":checked").length ) {
                _f_Dialog("dialog","CHBX_DLGBX_MS_NOSLCT");
                return;
            }
            
            //On récupère les modèles sélectionnés
            var mtab = []; 
            var ml = [];
            $.each($(".jb-chbx-msgmdl-slct").filter(":checked"), function(x,el) {
                if ( KgbLib_CheckNullity($(el).closest(".jb-chbx-msgmdl-mx").data("item")) || $(el).closest(".jb-chbx-msgmdl-mx").attr("dsb") === 1 ) {
                    return true;
                }
                //dsb : DelStandBy : Permet de ne pas être comptabiliser si l'utilisateur tente de lancer un processus de suppression un peu trop tot
                $(el).closest(".jb-chbx-msgmdl-mx").attr("dsb",1);
                mtab.push($(el).closest(".jb-chbx-msgmdl-mx"));
                ml.push($(el).closest(".jb-chbx-msgmdl-mx").data("item").toString());
            });
            if (! mtab.length ) {
                _f_Dialog("dialog","CHBX_DLGBX_MS_NOSLCT");
//                $(".jb-chbx-dlgbx-sprt").removeClass("this_hide");//?
                return;
            }
            
            //TODO : On vérifie dans la pile des opéraition serveur, s'il n'y a pas déjà une opération de suppression en cours 
            
            //TODO : On affiche le message qui demande de patienter ?
    
            var t__ = _asdApps.chatbox.mods.convtheater.xtras;
            var ds = {
                "flm": "pf",
                "tgt": t__.uid,
                //ConversationID
                "cid": t__.cvid,
                //MessageList
                "ml": ml,
                //PullConF
                "pcf": false,
                "wso": false //NOTE : Pour les tests, on peut éviter le mode WSO. Il faudra à ce moment là réduire le temps d'intervalle
            };
//            Kxlib_DebugVars([JSON.stringify(ds)],true);
//            return;
            if ( KgbLib_CheckNullity(ds.tgt) | KgbLib_CheckNullity(ds.cid) ) {
                //HACK
                return;
            }
            
            //On contacte le serveur
            var s = $("<span/>");
            _f_Srv_DlMs(ds.tgt,ds.cid,ds.ml,ds.flm,ds.pcf,ds.wso,s);
            
            /*
             * [DEPUIS 12-07-15] @BOR
             * On lock (après ) que l'opération AJAX soit lancé pour empecher que des opérations similaires se fassent.
             */
            _act_lkr.dlms = true;
            
            //On retire les messages de la liste
            _f_HidMsg(mtab);
            
            //On masque les case à cocher
            var btm = ( $(".jb-chbx-opt-chc-tgr[data-action='message_hour']").data("state") === "active" ) ? true : false;
            _f_TogMsDelChkbx(false, btm);
            
            //On fait disparaitre la barre de décision
            $(".jb-chbx-nwmsg-ipt-mx").removeClass("this_hide");
            $(".jb-chbx-del-msg-mx").addClass("this_hide");
            
            $(s).on("operended",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                alert(JSON.stringify(d));
//                return;
                //On supprime les Messages qui sont masqués
                $.each(mtab,function(x,mm){
                    $(mm).remove();
                });
                
                //On ajoute les données dans la zone BUFFER
                var d__ = {
                    cvid    : d.cvtab.cid,
                    tgtid   : d.tgttab.tgtid,
                    tgtpsd  : d.tgttab.tgtpsd,
                    tgtfn   : d.tgttab.tgtfn,
                    tgtppic : d.tgttab.tgtppic
                };
//            Kxlib_DebugVars([JSON.stringify(d)],true);
//            return;

                /*
                 * On procède à la mise à jour des données dans la zone BUFFER pour "_f_CfDelMsgs()".
                 */
                _f_NwCnvTheaInLDW(d__);
                
                //TODO : On retire les messages de la mémoire locale
                
                //TODO : Mettre à jour les données sur la cible de la COnversation
//                00..
                
                //On affiche les nouveaux messages s'il existent
                if ( typeof d.cmlist.flist !== "undefined" && d.cmlist.flist.length ) {
                    _f_PushModelMsgList(d.cmlist.flist,d.cutab.oid,d.cvtab.tgtid);
                }
            
                //On fait apparaitre une notification confirmant la suppression
                var N = new Notifyzing();
                var ua = ( ml.length > 1 ) ? "CHBX_MSG_DELs" : "CHBX_MSG_DEL";
                N.FromUserAction(ua);
                
                //On libère la référence
                _xhr_dlms = null;
                Kxlib_DebugVars(["ULOCK _xhr_plms ended by datas treated"]);
                
                //On débloque les "lockers"
                _act_lkr.shrcs = false;
                _act_lkr.plcs = false;
                _act_lkr.dlcs = false;
                _act_lkr.subms = false;
                _act_lkr.plms = false;
                _act_lkr.dlms = false;
            });
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
            
    var _f_Dialog = function (cz,mc) {
        //cz = CaSe;mc = MessageCode
        /*
         * Permet d'afficher la boite de dialogue ou seulement le support pour afficher un Message.
         */
        if ( KgbLib_CheckNullity(cz) | KgbLib_CheckNullity(mc) | !$(".jb-chbx-dlgbx-sprt").length ) {
            return;
        }
                
        try {
            
            var ms = Kxlib_getDolphinsValue(mc);
            if (KgbLib_CheckNullity(ms)) {
                return;
            }
            
            switch (cz) {
                case "direct":
                    if (!$(".jb-chbx-drctmsg-m").length) {
                        return;
                    }
                    
                    $(".jb-chbx-dlgbx-mx").addClass("this_hide");
                    $(".jb-chbx-drctmsg-m").removeClass("this_hide");
                    $(".jb-chbx-drctmsg-m").text(ms);
                    $(".jb-chbx-dlgbx-sprt").removeClass("this_hide");
                    
                    break;
                case "dialog":
//                    Kxlib_DebugVars([(".jb-chbx-dlgbx-mx").length,$(".jb-chbx-dlgbx-msg").length,$(".jb-chbx-action[data-action='dlgbx_valid']").length]);
                    if ( !$(".jb-chbx-dlgbx-mx").length | !$(".jb-chbx-dlgbx-msg").length | !$(".jb-chbx-action[data-action='dlgbx_valid']").length) {
                        return;
                    } 
                    
                    $(".jb-chbx-drctmsg-m").addClass("this_hide");
                    $(".jb-chbx-dlgbx-mx").removeClass("this_hide");
                    $(".jb-chbx-dlgbx-msg").text(ms);
                    $(".jb-chbx-dlgbx-sprt").removeClass("this_hide");
                    
                    break;
                default: 
                    return;
            }
            
            
        } catch (ex) {
//            KgbLib_CheckNullity(ex);
        }
                
    };
       
    var _f_ClzDlgBx = function (x) {
        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
        
        $(".jb-chbx-dlgbx-sprt").addClass("this_hide");
        $(".jb-chbx-dlgbx-mx").addClass("this_hide");
        $(".jb-chbx-dlgbx-msg").text("");
        
    };
    
    var _f_Opt_SwAct = function (x) {
//        Kxlib_DebugVars([KgbLib_CheckNullity(x), !$(x).data("state"), !_f_Gdf().chkbl_stt.test($(x).data("state")), !$(x).closest(".jb-chbx-opt-chcs").data("wdw")],true);
        if ( KgbLib_CheckNullity(x) | !$(x).data("state") | !_f_Gdf().chkbl_stt.test($(x).data("state")) | !$(x).closest(".jb-chbx-opt-chcs").data("wdw") ) {
            return;
        }
        
        var stt = ( $(x).data("state") === "active" ) ? "inactive" : "active";
        $(x).attr("data-state",stt); //HACK : Pour css
        $(x).data("state",stt); //HACK : Pour js
    };
    
    
    /*************************************************************************************************************************************************************/
    /******************************************************************** EXPERIMENTAL MODULE ********************************************************************/
    
    var _f_XpMdAct = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            
            var a = $(x).data("action");
            switch (a) {
                case "open_lrnabt_xpmd" :
                case "close_lrnabt_xpmd" :
                        _f_ShwLrnAbtXpMd(x);
                    break;
                case "enable_xpmd" :
                        _f_EnaChatbox(x);
                    break;
                default : 
                    return;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    var _f_EnaChatbox = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            /*
             * ETAPE : 
             * On vérifie si l'utilisateur souhaite que l'on se souvienne de sa décision.
             */
            if ($(".jb-chbx-wrng-xpmdl-g-r-ipt").is(":checked")) {
                /*
                 * ETAPE :
                 * On sauvegarde la décision puis on laisse l'utilisateur accéder à l'application.
                 */
                
                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                //On lock le bouton
                $(x).data("lk",1);
                
                /*
                 * ETAPE :
                 * On lock le bouton et la checkbox pour faire apparaitre le spinner
                 */
                $(".jb-chbx-wrng-xpmdl-gaw-mx").addClass("this_hide");
                $(".jb-chbx-wrng-xpmdl-g-s-mx").removeClass("this_hide");
                
                var s = $("<span/>");
                var T = new MNFM();
                T.SetPrfrcs("_PFOP_CHTBX_ISXPM","_DEC_DSMA",s);
                
                $(s).on("operended",function(e,d){
                    
                    //On affiche la notification de bienvenue
                    var Nty = new Notifyzing ();
                    Nty.FromUserAction("UA_CHBX_ENABLE_EXPMD");
                
                    $(".jb-chbx-wrng-xpmdl-sprt").addClass("this_hide");
                });
                
            } else {
               /*
                * ETAPE : 
                * On retirer la fenêtre d'avertissement tout de suite pour permettre l'utimisation immediate du module.
                */
               $(".jb-chbx-wrng-xpmdl-sprt").addClass("this_hide");
            }
            
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    
    /*************************************************************************************************************************************************************/
    /******************************************************************** NOTIFICATION SCOPE ********************************************************************/
    
    var _f_AuNot = function () {
        try {
            
            if (! $(".jb-chbx-crnr-if-bmx").length ) {
                return;
            }
                    
            if (! KgbLib_CheckNullity(_xhr_plums) ) {
                return;
            }
                      
            /*
             * ETAPE :
             *      Si on récupère l'identifiant de la conversaion ouverte si elle existe.
             */
            var cpi = ( $(".jb-chbx-mods[data-wdw='conv_theater']").data("cvid") ) ? 
                $(".jb-chbx-mods[data-wdw='conv_theater']").data("cvid") : null;
                        
//            Kxlib_DebugVars([cpi]);
            
            /*
             * ETAPE :  
             *      On envoie les données au niveau de serveur.
             */
            var s = $("<span/>");
            _f_Srv_NotGn(cpi,s);
            
            $(s).on("datasready",function(e,ds){
                if ( KgbLib_CheckNullity(ds) ) {
                    return;
                }
//                $(".jb-chbx-crnr-if-mx").data("lexd", "");      
                Kxlib_DebugVars([JSON.stringify(ds)]);
                
                /*
                 * ETAPE : En fonction de l'activité on décide du déroulement de la suite.
                 *      CAS 1 : Une opération PULL_CS 'FST' ou 'TOP' est active > On masque car l'utilisateur aura des données à jour
                 *      CAS 2 : Si la zone est déjà affichée > On la met à jour
                 *      CAS 3 : Si la zone n'est PAS affichée > On affiche en fonction du cas ExeD
                 */
                if ( _act_lkr.plcs === true && $(".jb-chbx-ldg-spnr").hasClass("this_hide") ) {
//                    Kxlib_DebugVars(["CAS 1"]);
                    _f_NotUpd();
                } else if (! $(".jb-chbx-crnr-if-bmx").hasClass('this_hide') ) {
//                    Kxlib_DebugVars(["CAS 2"]);
                    _f_NotUpd(ds.ums,true);
                } else if ( $(".jb-chbx-crnr-if-bmx").hasClass('this_hide') ) {
//                    Kxlib_DebugVars(["CAS 3"]);
                     _f_NotUpd(ds.ums,true,true);
                }
                
                _xhr_plums = null;
            });
            
            $(s).on("operended",function(){
                _f_NotUpd();
                _xhr_plums = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_NotAct = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && KgbLib_CheckNullity(a) ) ) {
                return;
            }
            
            var _a = ( KgbLib_CheckNullity($(x).data("action")) ) ? a: $(x).data("action");
            switch (_a) {
                case "exc" :
                        _f_NotExc(x);
                    break;
                default :
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_NotExc = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            /*
             * ETAPE : 
             *      On masque le bouton et on met à jour la donnée "data-lexd"
             */
            $(".jb-chbx-crnr-if-bmx").addClass("this_hide");
            var ld = (new Date()).getTime();
            $(".jb-chbx-crnr-if-mx").data("lexd",ld);
            
            /*
             * ETAPE :
             *      On effectue la suite des opérations en fonction du cas dans lequel nous sommes
             */
            
            if (! $(".jb-asd-apps-chc.selected[data-action='gochatbox']").length ) {
               /*
                * ETAPE :
                *      On s'assure que l'utilisateur voit la zone d'ajout
                */
                if ( $(window).scrollTop() !== 370 ) {
                    $("html, body").animate({ scrollTop: "370px" });
                }
                
                /*
                 * ETAPE :
                 *      On sélectionne la zone
                 */
                $(".jb-asd-apps-chc[data-action='gochatbox']").click();
                
                /*
                 * ETAPE :
                 *      On clique pour récupérer les données mise à jour sur les conversations
                 */
                $(".jb-chbx-action[data-action='nav_conv_list_og']").click();
            } else {
                if ( _f_AcWdw() === "NONE" ) {
                    $(".jb-chbx-action[data-action='nav_conv_list_og']").click();
                } else if ( _f_AcWdw() === "CONV_THEATER" ) {
                    $(".jb-chbx-action[data-action='nav_conv_list']").click();
                } else {
                    $(".jb-chbx-action[data-action='nav_conv_list_og']").click(); //HACK
                }
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_NotUpd = function (d,sh,clec) {
        //clec : CheckLastExecutionCase
        try {
            
            var a = true, nw = (new Date()).getTime();
            if ( clec && $(".jb-chbx-crnr-if-mx").data("lexd") ) {
                var at = $(".jb-chbx-crnr-if-mx").data("lexd");
//                Kxlib_DebugVars([nw,at,nw - at, _f_Gdf().cled]);
                a = ( nw - at > _f_Gdf().cled  ) ? true : false;
            } 
            //On vérifie tout simplement la dernière fois que le module a été fermé. Cela prend en compte les cas où on masque la zone en attendant qu'une opération se fasse. 
            else if ( $(".jb-chbx-crnr-if-mx").data("lhdd") ) {
                var at = $(".jb-chbx-crnr-if-mx").data("lhdd");
//                Kxlib_DebugVars([nw,at,nw - at, _f_Gdf().cled]);
                a = ( nw - at > _f_Gdf().cled  ) ? true : false;
            }
            
            if ( a && !KgbLib_CheckNullity(d) && d !== parseInt($(".jb-chbx-crnr-if-nb").text()) ) {
                $(".jb-chbx-crnr-if-nb").text(d);
            }
            
            if ( a && sh ) {
                $(".jb-chbx-crnr-if-bmx").removeClass('this_hide');
            } else {
                $(".jb-chbx-crnr-if-bmx").addClass('this_hide');
                /*
                 * [DEPUIS 15-11-15] @autor BOR
                 */
                $(".jb-chbx-crnr-if-mx").data("lhdd",nw);
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /*************************************************************************************************************************************************************/
    /************************************************************************ TIMER SCOPE ************************************************************************/
    /*************************************************************************************************************************************************************/
    
    //On lance le processus qui va récupérer les Messages auprès du serveur
    setInterval(function(){
        _f_AutoPullMs();
    },_f_Gdf().pmi);
    setInterval(function(){
        /*
         * [DEPUIS 13-08-15] @BOR
         */
//        _f_AutoPullCs();
    },_f_Gdf().pci);
   /*
    * [DEPUIS 10-11-15] @BOR
    */
    setInterval(function(){
        _f_AuNot();
//    },5300);
    },_f_Gdf().pni);
    
    /**************************************************************************************************************************************************************/
    /********************************************************************* LOCALE DATAS SCOPE *********************************************************************/
    /**************************************************************************************************************************************************************/
    
    var _f_GetAsdApps = function () {
        /*
         * Récupère dans SESSION_STORAGE, l'objet représentant la configuration des applications du module AsdApps
         */
        try {
            if ( "asdApps" in sessionStorage ) {
                var o = JSON.parse(sessionStorage.asdApps);
                /*
                 * On va s'assurer que la "premiere couche" est correcte.
                 * Cela nous fera gagner du temps en aval pour la manipulation de l'Objet.
                 */
                
                if (! "chatbox" in o ) {
                    return;
                }
                
                var xptd = ["isactive","name","lib","lupd","xtras","mods"];
                var ras = true;
                $.each(xptd,function(x,e){
                    if (! e in o.chatbox ) {
                        ras = false;
                        return false;
                    }
                });
                var mods = 
                ( 
                    ("convlist" in o.chatbox.mods && typeof o.chatbox.mods.convlist === "object" && Kxlib_ObjectChild_Count(o.chatbox.mods.convlist)) 
                    && ("convtheater" in o.chatbox.mods && typeof o.chatbox.mods.convtheater === "object" && Kxlib_ObjectChild_Count(o.chatbox.mods.convtheater)) 
                ) ? true : false;
                return ( "chatbox" in o && ras && mods ) ? o : null;
            } else {
                return;
            }
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_SetAsdApps = function (aa) {
        //aa : AsideApps
        
        try {
            if ( KgbLib_CheckNullity(aa) ) {
                return;
            }
        
            aa = (typeof aa === "object") ? JSON.stringify(aa) : aa;
            sessionStorage.setItem("asdApps", aa);
//            Kxlib_DebugVars([typeof aa, aa, sessionStorage.asdApps],true);
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_NwCnvTheaInLDW = function (dc,tof) {
        //ip : IsParley, LDW : LocalDataWarehouse
        /*
         * Permet de mettre à jour les données sur la nouvelle Conversation active.
         * Pour cela, on utilise un DataCache normalisé selon la nature de la conversation.
         * Il peut s'agir d'une Conversation de type Parley ou Conversation déjà créée.
         */
        /*
         *   cvid       : Identifiant de la Conversation
         *   cmsgid     : Identifiant du Message
         *   cmsgm      : Texte du Message
         *   cmsgcd     : Date de creation du message coté serveur
         *   cmsg_fecd  : Date de creation du message coté FE
         *   cmsgrd     : Date de lecture du Message
         *   tgtid      : Identifiant de la cible dans la Conversation
         *   tgtpsd     : Pseudo de la cible dans la Conversation
         *   tgtfn      : Nom Complet de la cible dans la Conversation
         *   tgtppic    : Photo de Profil de la cible dans la Conversation
         *   tgtfols    : Nombre de Followers de la cible dans la Conversation
         *   tgtcap     : Capital de la cible dans la Conversation
         *   tgtcbsts   : Le statut de ChatBox (?)
        */
        if ( KgbLib_CheckNullity(dc) ) {
            return;
        }
                
        _asdApps.chatbox.mods.convtheater.xtras = {
            maininput   : "",
            cvid        : ( dc.hasOwnProperty("cvid") ) ? dc.cvid : null,
            uid         : dc.tgtid,
            upsd        : dc.tgtpsd,
            ufn         : dc.tgtfn,
            uppic       : dc.tgtppic,
            //TOF : TimeOfFirst, la conversation existe depuis ...
            tof         : tof
        };
        
        //Mise à jour de LDW
        _f_SetAsdApps(_asdApps);
        
    };
    
    
    /***********************************************************************************************************************************************************/
    /********************************************************************* SERVER SCOPE ************************************************************************/
    /***********************************************************************************************************************************************************/
    
    var _Ax_SrhTrgr = Kxlib_GetAjaxRules("CHBX_SEARCH");
    var _f_Srv_SrhTrgr = function(qt,pvt,fil,drt,qsp,flm,otrf,s) {
        if ( KgbLib_CheckNullity(qt) | KgbLib_CheckNullity(fil) | KgbLib_CheckNullity(drt) | KgbLib_CheckNullity(qsp) | KgbLib_CheckNullity(flm) | KgbLib_CheckNullity(otrf) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        if ( _act_lkr.shrcs === true ) {
           return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    _xhr_srh = null;
                    return;
                }
                
                if ( !KgbLib_CheckNullity(d.err)) {
                    _xhr_srh = null;
                    if (Kxlib_AjaxIsErrVolatile(d.err)) {
                        switch (d.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_OWNER_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DATAS_MSG" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                break;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                break;
                            default:
                                return;
                                break;
                        }
                    } 
                    return;
                } else if ( d.hasOwnProperty("return") && !KgbLib_CheckNullity(d.return) )  {
                    /*
                     * Données attendues : 
                     *  (1) Données sur le résultats de la recherche
                     */
                     if ( d.return.hasOwnProperty("rds") && !KgbLib_CheckNullity(d.return.rds) ) {
                         rds = [d.return];
//                         rds = [d.return];
                         $(s).trigger("datasready",rds);
                     } else if ( d.return.hasOwnProperty("otrf") && !KgbLib_CheckNullity(d.return.otrf) ) {
                         rds = [d.return.otrf];
                         $(s).trigger("operended",rds);
                     } else {
                         return;
                     }
                } else {
                    _xhr_srh = null;
                    return;
                }
                
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                _xhr_srh = null;
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            _xhr_srh = null;
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_SrhTrgr.urqid,
            "datas": {
                "qt"    : qt,
                "pvt"   : pvt,
                "fil"   : fil,
                "drt"   : drt,
                "qsp"   : qsp,
                "flm"   : flm,
                "otrf"  : otrf,
                "curl"  : u 
            }
        };

        _xhr_srh = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_SrhTrgr.url, wcrdtl : _Ax_SrhTrgr.wcrdtl });
        return _xhr_srh;
    };
    
    var _Ax_PlCnv = Kxlib_GetAjaxRules("CHBX_PL_CNV");
    var _f_Srv_PlCnv = function(pvt,drt,qsp,flm,pcf,s) {
        if ( KgbLib_CheckNullity(drt) | KgbLib_CheckNullity(qsp) | KgbLib_CheckNullity(flm) | KgbLib_CheckNullity(pcf) | KgbLib_CheckNullity(s) ) {
//        if (  KgbLib_CheckNullity(qsp) | KgbLib_CheckNullity(flm) | KgbLib_CheckNullity(rng) | KgbLib_CheckNullity(drt) | KgbLib_CheckNullity(pcf) | KgbLib_CheckNullity(s) ) {
            return;
        }
                
        if ( _act_lkr.plcs === true ) {
           return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    _xhr_plcs = null;
                    return;
                }
                
                if ( !KgbLib_CheckNullity(d.err)) {
                    _xhr_plcs = null;
                    //On rend "enable" l'input de recherche
                    $(".jb-asd-c-h-t-ipt").removeProp("disabled");
                    
                    if (Kxlib_AjaxIsErrVolatile(d.err)) {
                        switch (d.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_OWNER_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DATAS_MSG" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                    return;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                    return;
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                                break;
                        }
                    } 
                    return;
                } else if ( d.hasOwnProperty("return") )  {
                    /*
                     * Données attendues : 
                     *  (1) Données sur les Conversations
                     */
                     if (! KgbLib_CheckNullity(d.return)  )  {
                         rds = [d.return];
                         $(s).trigger("datasready",rds);
                     } else {
                         $(s).trigger("operended");
                     }
                } else {
                    //On rend "enable" l'input de recherche
                    $(".jb-asd-c-h-t-ipt").removeProp("disabled");
                    _xhr_plcs = null;
                    return;
                }
                
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                //On rend "enable" l'input de recherche
                $(".jb-asd-c-h-t-ipt").removeProp("disabled");
                _xhr_plcs = null;
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
          
            //On rend "enable" l'input de recherche
            $(".jb-asd-c-h-t-ipt").removeProp("disabled");
            _xhr_plcs = null;
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_PlCnv.urqid,
            "datas": {
                "pvt"   :pvt,
                "drt"   : drt,
                "qsp"   :qsp,
                "flm"   :flm,
                "pcf"   : pcf,
                "curl"  : u 
            }
        };

        _xhr_plcs = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlCnv.url, wcrdtl : _Ax_PlCnv.wcrdtl });
        return _xhr_plcs;
    };
           
    var _Ax_DlCs = Kxlib_GetAjaxRules("CHBX_DL_CS");
    var _f_Srv_DlCs = function(cl,flm,pcf,wso,s) {
        if ( KgbLib_CheckNullity(cl) | KgbLib_CheckNullity(flm) | KgbLib_CheckNullity(pcf) | KgbLib_CheckNullity(wso) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        if ( _act_lkr.dlcs === true ) {
           return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    //[21-01-15] @Lou On ne reset pas le Ldr pour insister sur le fait qu'il y a une erreur
                    _xhr_dlcs = null;
                    return;
                }
                
                if ( !KgbLib_CheckNullity(d.err) ) {
                    
                    if (Kxlib_AjaxIsErrVolatile(d.err)) {
                        switch (d.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_OWNER_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DATAS_MSG" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                    return;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                    return;
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                                break;
                        }
                    } 
                    return;
                } else if ( d.hasOwnProperty("return") )  {
                    /*
                     * Données attendues : 
                     *  (1) Les liste des Messages (Meme role que PL_CS mais en mode FIRST) => NOOOOO!!!
                     *  (2) La table de la Cible de la Conversation => NOOOOO!!!
                     *  (3) Les table de la Conversation => NOOOOO!!!
                     *  (4) La configuration de CB (Facultatif)
                     *  (5) Autres
                     */
                    rds = [d.return];
                    $(s).trigger("operended",rds);
                } else {
                    _xhr_dlcs = null;
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                _xhr_dlcs = null;
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            _xhr_dlcs = null;
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_DlCs.urqid,
            "datas": {
                "cl"    : cl.toString(),
                "flm"   : flm,
                "pcf"   : pcf,
                "wso"   : wso,
                "curl"  : u 
            }
        };
        
        _xhr_dlcs = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_DlCs.url, wcrdtl : _Ax_DlCs.wcrdtl });
        return _xhr_dlcs;
    };
    
    /***************************** MESSAGE *****************************/
    
    var _Ax_ChbxSubM = Kxlib_GetAjaxRules("CHBX_SUB_M");
    var _f_Srv_ChbxSubM = function(m,tgt,flm,cid,lmi,fet,pcf,s) {
        if ( KgbLib_CheckNullity(m) | KgbLib_CheckNullity(tgt) | KgbLib_CheckNullity(flm)  | KgbLib_CheckNullity(fet) | KgbLib_CheckNullity(pcf) | KgbLib_CheckNullity(s) ) {
//        if ( KgbLib_CheckNullity(m) | KgbLib_CheckNullity(tgt) | KgbLib_CheckNullity(flm) | KgbLib_CheckNullity(cid) | KgbLib_CheckNullity(lmi) | KgbLib_CheckNullity(pcf) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        if ( _act_lkr.subms === true ) {
            return;
        }
            
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    _xhr_sbms = null;
                    return;
                }
                
                if ( !KgbLib_CheckNullity(d.err)) {
                    _xhr_sbms = null;
                    if (Kxlib_AjaxIsErrVolatile(d.err)) {
                        switch (d.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_OWNER_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_TGT_GONE":
                                    //Supprimer les conversations de la liste
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    Kxlib_AJAX_HandleDeny();
                                break;
                            /*
                             * [DEPUIS 21-09-15] @author BOR
                             */
                            case "__ERR_VOL_FRD_GONE" :
                                    $(s).trigger("onerror");
                                break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DATAS_MSG" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                return;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                    return;
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) )  {
                    /*
                     * Données attendues : 
                     *  -> cid      : L'identifiant de la conversation cvid (Sert surtout pour le dernier message créé)
                     *  -> tgttab   : Données sur la cible du message (de la conversation)
                     *  -> cmlist :
                     *      -> cmone  : Données convernant le Message qui vient d'être ajouté
                     *      -> flist  : Données concernant les messages dits "First". Ce tableau est NON NULL quand il n'y a pas de pivot
                     *      -> plist  : (P = previous) Liste les messages anterieurs au messae ajouté mais ajouté après le message pivot. 
                     *      -> ulist  : (U = Ulterior) Liste des messages ulterieur au message créé
                     *  -> cvtab    : Données de base sur la conversation
                     *  -> chbxcnf  : Données sur la configuration de ChatBox pour l'Utilisateur actif
                     */
//                    _xhr_sbms = null; //NON : On a pas encore remplacer l'identifiant temporaire par celui définitif. Cela fausse tout !
                    rds = [d.return];
                    $(s).trigger("datasready",rds);
//                    _xhr_sbms = null;
                } else {
                    _xhr_sbms = null;
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                _xhr_sbms = null;
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            _xhr_sbms = null;
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_ChbxSubM.urqid,
            "datas": {
                "m"     : m,
                "tgt"   : tgt,
                "fet"   : fet, 
                "flm"   : flm,
                "cid"   : cid,
                "lmi"   : lmi,
                "pcf"   : pcf,
                "curl"  : u 
            }
        };
//        Kxlib_DebugVars([]);        
        _xhr_sbms = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_ChbxSubM.url, wcrdtl : _Ax_ChbxSubM.wcrdtl });
        return _xhr_sbms;
        Kxlib_DebugVars(["Lock for XHR_PLMS "+typeof _xhr_sbms]);
    };
    
    var _Ax_CnvPlMs = Kxlib_GetAjaxRules("CHBX_PL_MS");
    var _f_Srv_CnvPullFMsgs = function(tgt,flm,cid,lmi,pcf,wso,drt,s,isa) {
        //isa : IsAuto
        if ( KgbLib_CheckNullity(tgt) | KgbLib_CheckNullity(flm) | KgbLib_CheckNullity(cid) | KgbLib_CheckNullity(pcf) | KgbLib_CheckNullity(wso) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        if ( _act_lkr.plcs === true ) {
            return;
        }
            
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    //[21-01-15] @Lou On ne reset pas le Ldr pour insister sur le fait qu'il y a une erreur
                    _xhr_plms = null;
                    return;
                }
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    //[21-01-15] @Lou On ne reset pas le Ldr pour insister sur le fait qu'il y a une erreur
                    _xhr_plms = null;
                    if (Kxlib_AjaxIsErrVolatile(d.err)) {
                        switch (d.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_OWNER_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                                    if (! ( isa === true ) ) {
                                        Kxlib_HandleCurrUserGone();
                                    }
                                break;
                            case "__ERR_VOL_TGT_GONE":
                                    //Supprimer les conversations de la liste
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    if (! ( isa === true ) ) {
                                        Kxlib_AJAX_HandleDeny();
                                    }
                                break;
                            case "__ERR_VOL_FAILED" :
                                    if (! ( isa === true ) ) {
                                        Kxlib_AJAX_HandleFailed();
                                    }
                                break;
                            case "__ERR_VOL_DATAS_MSG" :
                                    if (! ( isa === true ) ) {
                                        Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                    }
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                break;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                break;
                            default:
                                    if (! ( isa === true ) ) {
                                        Kxlib_AJAX_HandleFailed();
                                    }
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) )  {
                    /*
                     * Données attendues : 
                     *  -> cid      : L'identifiant de la conversation cvid (Sert surtout pour le dernier message créé)
                     *  -> tgttab   : Données sur la cible du message (de la conversation)
                     *  -> cmlist :
                     *      -> cmone  : Données convernant le Message qui vient d'être ajouté
                     *      -> flist  : Données concernant les messages dits "First". Ce tableau est NON NULL quand il n'y a pas de pivot
                     *      -> plist  : (P = previous) Liste les messages anterieurs au messae ajouté mais ajouté après le message pivot. 
                     *      -> ulist  : (U = Ulterior) Liste des messages ulterieur au message créé
                     *  -> cvtab    : Données de base sur la conversation
                     *  -> chbxcnf  : Données sur la configuration de ChatBox pour l'Utilisateur actif
                     */
                    rds = [d.return];
                    $(s).trigger("operended",rds);
//                    _xhr_plms = null;
                } else {
                    _f_SpRstLdMrTgr("conv_theater");
                    _xhr_plms = null;
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                /*
                 * [21-01-15] @Lou On ne reset pas le Ldr pour insister sur le fait qu'il y a une erreur
                 */
                _xhr_plms = null;
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            //[21-01-15] @Lou On ne reset pas le Ldr pour insister sur le fait qu'il y a une erreur
            _xhr_plms = null;
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_CnvPlMs.urqid,
            "datas": {
                "tgt": tgt,
                "flm": flm,
                "cid": cid,
                "lmi": lmi,
                "drt": ( KgbLib_CheckNullity(drt) ) ? "top" : drt,
                "pcf": pcf,
                "wso": wso, 
                "curl": u 
            }
        };

        _xhr_plms = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_CnvPlMs.url, wcrdtl : _Ax_CnvPlMs.wcrdtl });
        Kxlib_DebugVars(["Lock for XHR_PLMS : "+typeof _xhr_plms]);
        return _xhr_plms;
    };
       
    var _Ax_DlMs = Kxlib_GetAjaxRules("CHBX_DL_MS");
    var _f_Srv_DlMs = function(tgt,cid,ml,flm,pcf,wso,s) {
        if ( KgbLib_CheckNullity(tgt) | KgbLib_CheckNullity(cid) | KgbLib_CheckNullity(ml) | KgbLib_CheckNullity(flm) | KgbLib_CheckNullity(pcf) | KgbLib_CheckNullity(wso) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        if ( _act_lkr.dlms === true ) {
           return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    //[21-01-15] @Lou On ne reset pas le Ldr pour insister sur le fait qu'il y a une erreur
                    _xhr_dlms = null;
                    return;
                }
                
                if ( !KgbLib_CheckNullity(d.err) ) {
                    
                    if (Kxlib_AjaxIsErrVolatile(d.err)) {
                        switch (d.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_OWNER_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DATAS_MSG" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                    return;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                    return;
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                                break;
                        }
                    } 
                    return;
                } else if ( d.hasOwnProperty("return") )  {
                    /*
                     * Données attendues : 
                     *  (1) Les liste des Messages (Meme role que PL_MS mais en mode FIRST)
                     *  (2) La table de la Cible de la Conversation
                     *  (3) Les table de la Conversation
                     *  (4) La configuration de CB (Facultatif)
                     *  (5) Autres
                     */
                    rds = [d.return];
                    $(s).trigger("operended",rds);
                } else {
                    _xhr_dlms = null;
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                _xhr_dlms = null;
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            _xhr_dlms = null;
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_DlMs.urqid,
            "datas": {
                "tgt"   : tgt,
                "cid"   : cid,
                "ml"    : ml.toString(),
                "flm"   : flm,
                "pcf"   : pcf,
                "wso"   : wso,
                "curl"  : u 
            }
        };
        
//        Kxlib_DebugVars([JSON.stringify(_Ax_DlMs)],true);
        _xhr_dlms = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_DlMs.url, wcrdtl : _Ax_DlMs.wcrdtl });
        return _xhr_plms;
    };
    
    /********************************** NOTIFICATION SCOPE **********************************/
    
    var _Ax_NotGn = Kxlib_GetAjaxRules("CHBX_NTY_GN");
    var _f_Srv_NotGn = function(cpi,s) {
        if ( KgbLib_CheckNullity(s) ) {
            return;
        }
        
        /*        
        if ( _act_lkr.plcs === true ) {
           return;
        }
        //*/
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    _xhr_plums = null;
                    return;
                }
                
                if ( !KgbLib_CheckNullity(d.err)) {
                    _xhr_plums = null;
                    //On rend "enable" l'input de recherche
                    $(".jb-asd-c-h-t-ipt").removeProp("disabled");
                    
                    if (Kxlib_AjaxIsErrVolatile(d.err)) {
                        switch (d.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_OWNER_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                    break;
                            case "__ERR_VOL_FAILED" :
//                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DATAS_MSG" :
//                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                    return;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                    return;
                                break;
                            default:
                                break;
                        }
                    } 
                    return;
                } else if ( d.hasOwnProperty("return") )  {
                    /*
                     * Données attendues : 
                     *  (1) Données sur les Conversations
                     */
                     if ( !KgbLib_CheckNullity(d.return.ums) && d.return.ums > 0 )  {
                         rds = [d.return];
                         $(s).trigger("datasready",rds);
                     } else {
                         $(s).trigger("operended");
                     }
                } else {
                    //On rend "enable" l'input de recherche
                    _xhr_plums = null;
                    return;
                }
                
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                //On rend "enable" l'input de recherche
                $(".jb-asd-c-h-t-ipt").removeProp("disabled");
                _xhr_plums = null;
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
          
            _xhr_plums = null;
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_NotGn.urqid,
            "datas": {
                "cpi"   : (! cpi ) ? null : cpi,
                "cu"    : u 
            }
        };

        _xhr_plums = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_NotGn.url, wcrdtl : _Ax_NotGn.wcrdtl });
        return _xhr_plums;
    };
        
    
    var _Ax_NotSn = Kxlib_GetAjaxRules("CHBX_NTY_SN");
    var _f_Srv_NotSn = function(a,n,s) {
        if ( KgbLib_CheckNullity(a) | KgbLib_CheckNullity(n) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        /*        
        if ( _act_lkr.plcs === true ) {
           return;
        }
        //*/
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    _xhr_sbums = null;
                    return;
                }
                
                if ( !KgbLib_CheckNullity(d.err)) {
                    _xhr_sbums = null;
                    //On rend "enable" l'input de recherche
                    $(".jb-asd-c-h-t-ipt").removeProp("disabled");
                    
                    if (Kxlib_AjaxIsErrVolatile(d.err)) {
                        switch (d.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_OWNER_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DATAS_MSG" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                    return;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if ( d.hasOwnProperty("return") )  {
                    /*
                     * Données attendues : 
                     *  (1) Données sur les Conversations
                     */
                     if ( !KgbLib_CheckNullity(d.return.jrmis) )  {
                         rds = [d.return];
                         $(s).trigger("datasready",rds);
                     } else {
                         $(s).trigger("operended");
                     }
                } else {
                    //On rend "enable" l'input de recherche
                    _xhr_sbums = null;
                    return;
                }
                
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                //On rend "enable" l'input de recherche
                $(".jb-asd-c-h-t-ipt").removeProp("disabled");
                _xhr_sbums = null;
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
          
            _xhr_sbums = null;
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_NotSn.urqid,
            "datas": {
                "is"    : a,
                "rd"    : n,
                "cu"    : u 
            }
        };

        _xhr_sbums = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_NotSn.url, wcrdtl : _Ax_NotSn.wcrdtl });
        return _xhr_sbums;
    };
        
    /***********************************************************************************************************************************************************/
    /*********************************************************************** VIEW SCOPE ************************************************************************/
    /***********************************************************************************************************************************************************/
    
    var _f_ShwLrnAbtXpMd = function (x) {
        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
        
        if ( $(x).is(".jb-chbx-wrng-xpmdl-wrngm-lrnab") ) {
            $(".jb-chbx-wrng-xpmdl-board").addClass("this_hide");
            $(".jb-chbx-wrng-xpmdl-gaw-mx").addClass("this_hide");
            
            $(".jb-chbx-wrng-xpmdl-xplnd").removeClass("this_hide");
        } else if ( $(x).is(".jb-chbx-wrng-xpmdl-xplnd-clz") ) {
            $(".jb-chbx-wrng-xpmdl-xplnd").addClass("this_hide");
            
            $(".jb-chbx-wrng-xpmdl-board").removeClass("this_hide");
            $(".jb-chbx-wrng-xpmdl-gaw-mx").removeClass("this_hide");
        } else {
            return;
        }
    };
    
    var _f_ToglOpt = function (wdw, a) {
        if ( KgbLib_CheckNullity(wdw) ) {
            return;
        }
        
        var $tgt;
        switch (wdw) {
            case "conv_list" :
                    $tgt = $(".jb-chbx-opt-chcs[data-wdw='conv_list']");
                break;
            case "conv_theater" :
                    $tgt = $(".jb-chbx-opt-chcs[data-wdw='conv_theater']");
                break;
            default:
                    return;
                break;
        }
        
        ( a ) 
        ? $tgt.removeClass("this_hide")
        : $tgt.addClass("this_hide");
    };
    
    var _f_ToBottom = function (b) {
        if ( KgbLib_CheckNullity(b) | !$(b).length ) {
            return;
        }
        
        var h = $(b).height();
        var sh = $(b)[0].scrollHeight-h;
        
        /*
         * [DEPUIS 22-06-15] @BOR
         */
                
//        Kxlib_DebugVars([$(".jb-chbx-l-m-ldmr").length, $(".jb-chbx-l-m-ldmr").height()],true);
         if ( $(".jb-chbx-l-m-ldmr") && $(".jb-chbx-l-m-ldmr").length && $(".jb-chbx-l-m-ldmr").height() ) {
             sh += $(".jb-chbx-l-m-ldmr").height();
         }
         
        $(b).scrollTop(sh);
    };
    
    var _f_Vw_NavConvList = function () {
        $(".jb-chbx-mods[data-wdw='conv_list']").addClass("active");
        $(".jb-chbx-mods[data-wdw='conv_theater']").removeClass("active");
        
        _f_ToBottom($(".jb-chbx-list-convs"));
    };
    
    var _f_Vw_NavConvTheater = function (d,wo) {
        //wo : WipeOld
        /*
         * Permet de mettre en forme et d'afficher l'interface listant les messages d'une Conversation.
         * 
         * L'index des données attendues est : 
         *      uid : L'identifiant de l'utilisateur qui est considéré comme TARGET
         *      upsd : Le pseudonyme
         *      ufn : Le Nom Complet
         *      uppic : L'image de profil
         *      tof : Le temps correspondant à la création de la conversation. En d'autres termes, 
         *          celui du premier message ajouté.
         *          
         *  C'est la responsabilité de CALLER de vérifier que les données sont authentiques et sécurisées.
         *  Sauf cas particuliers, on ne fait qu'ajouter les données.
         */
       
        if ( KgbLib_CheckNullity(d) ) {
            return;
        }
        
        //On vide les anciens Messages 
        if ( wo ) {
            _f_WpMsRes();
        }
        
        //*** On met en forme le Header ***/
        $(".jb-chbx-ubox-tgt-psd").text(Kxlib_ValidUser(d.upsd));
        $(".jb-chbx-ubox-tgt-ppic").attr("src",d.uppic);
        $(".jb-chbx-ubox-tgt-mx").attr("href","/"+Kxlib_ValidUser(d.upsd));
        $(".jb-chbx-ubox-tgt-mx").attr("title",d.ufn);
        
        //Affichage de la fenetre
        $(".jb-chbx-mods[data-wdw='conv_list']").removeClass("active");
        $(".jb-chbx-mods[data-wdw='conv_theater']").addClass("active");
        
        _f_ToBottom($(".jb-chbx-list-msg"));
    };
    
    
    var _f_TogCsDelChkbx = function(shw) {
        //frc = ForceWB
        /*
         * Permet de faire afficher les cases à cocher au niveau des Conversations affichées dans la liste.
         * La méthode affiche aussi de manière synchroniser et par défaut la zone de décision.
         */
        if ( KgbLib_CheckNullity(shw) | !$(".jb-chbx-mods[data-wdw='conv_list']").length ) {
            return;
        }
        var b = $(".jb-chbx-mods[data-wdw='conv_list']");
        
        //On vérifie que la zone de décision est accessible
        if (! $(b).find(".jb-chbx-del-cnv-mx").length ) {
            return;
        }
        
        if ( shw ) {
            //On décoche toutes les cases à coher
            $(".jb-chbx-c-m-slct-ipt").prop('checked',false);
            //On fait passer tous les modèles de Conversations en mode "ondelete"
            $(b).find(".jb-chbx-list-convs").addClass("ondelete");
            $(b).find(".jb-chbx-conv-mdl-mx").addClass("onselection jb-skip");
            $(b).find(".jb-chbx-c-m-right").removeClass("noslct");
            $(b).find(".jb-chbx-c-m-slct-mx").removeClass("this_hide");
            
            //On fait apparaitre la zone de décision
            $(b).find(".jb-chbx-del-cnv-mx").removeClass("this_hide");
        } else {
            //On retire de tous les modèles de Conversations le mode "ondelete"
            $(b).find(".jb-chbx-list-convs").removeClass("ondelete");
            $(b).find(".jb-chbx-conv-mdl-mx").removeClass("onselection jb-skip");
            $(b).find(".jb-chbx-c-m-right").addClass("noslct");
            $(b).find(".jb-chbx-c-m-slct-mx").addClass("this_hide");
            
            //On fait disparaitre la zone de décision
            $(b).find(".jb-chbx-del-cnv-mx").addClass("this_hide");
            
            //On décoche toutes les cases à coher
            $(".jb-chbx-c-m-slct-ipt").prop('checked',false);
        }
        
//        _f_ToBottom($(".jb-chbx-list-msg"));
    };
    
    var _f_HidCnv = function(d) {
        try {
            if ( KgbLib_CheckNullity(d) | !d.length ) {
                return;
            }
            
            $.each(d, function(i,e) {
                $(e).addClass("this_hide");
            });
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_CrtTmpCurrMsgVw = function (m,il,ita) {
        //ita : IsTempAdd
        /*
         * Permet de créer une représentation temporaire d'un message créé par l'utilisateur courant.
         * Cette dernière information est importante car elle détermine la nature visuel du message dans la liste. 
         */
        try {
            if ( KgbLib_CheckNullity(m) ) {
                return;
            }
            //hd : HorzontaleDirection, il : IsLeft (Permet de laisser comme paramètre par défaut 'rbdr'
            var hd = ( il ) ? "lbdr": "rbdr";
            //mm= MessageModel
            var mm = "<div class='chbx-msgmdl-mx jb-chbx-msgmdl-mx' data-item='' data-cache='' time='' data-direction='"+hd+"'>";
            mm += "<span class='chbx-msgmdl-bdy' data-direction='"+hd+"'>";
            mm += "<span class='chbx-msgmdl-msg jb-chbx-msgmdl-msg'></span>";
            mm += "<span class='chbx-msgmdl-btm jb-chbx-msgmdl-btm' data-direction='"+hd+"'>";
            mm += "<input class='chbx-msgmdl-slct jb-chbx-msgmdl-slct this_hide' type='checkbox' data-direction='"+hd+"'/>";
            mm += "<span class='chbx-msgmdl-hr jb-chbx-msgmdl-hr this_hide' data-direction='"+hd+"'></span>";
            mm += "</span>";
            mm += "</span>";
            mm += "</div> ";
            mm = $.parseHTML(mm);

            var ti = (new Date()).getTime();
            //Ajoute l'identifiant temporaire. L'identifiant est unique et necessaire pour faciliter le processus
    //        $(mm).data("item",ti); 
            $(mm).attr("data-item",ti); //Pour pouvoir utiliser le selecteur "[data-item]"

            //J'ajoute le message
            m = ( ita ) ? m : Kxlib_Decode_After_Encode(m);
            $(mm).find(".jb-chbx-msgmdl-msg").text(m);

            return mm;
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_InsMsgVwInQueue = function (mm) {
        /*
         * Permet d'ajouter dans la queue des messages un modele de message.
         * NOTA : 
         *  (1) Il ne s'agit pas de LWD !
         *  (2) On ne controle pas si le bon module esta activé.
         */
        
        if ( KgbLib_CheckNullity(mm) | !$(".jb-chbx-l-m-gb").length ) {
            return;
        }
        
        $(mm).hide().appendTo(".jb-chbx-l-m-gb").fadeIn(500);
//        $(mm).hide().appendTo(".jb-chbx-list-msg").fadeIn(500);
        //(TOUJOURS) On ramène le curseur en bas de page
        _f_ToBottom($(".jb-chbx-list-msg"));
            
    };
    
    var _f_InsMsgVwInQueueFrom = function (mm,pvt,ibf,drt) {
        //mm : MessageModel; pvt : PiVoT; ibf : IsBeFore
        /*
         * Permet d'ajouter dans la queue des messages un modele de message.
         * NOTA : 
         *  (1) Il ne s'agit pas de LWD !
         *  (2) On ne controle pas si le bon module esta activé.
         */
        
//        if ( !$(".jb-chbx-l-m-gb").length | !$(pvt).length ) {
        if ( !$(".jb-chbx-l-m-gb").length | !$(pvt).length | KgbLib_CheckNullity($(pvt).attr("time")) ) {
            return;
        }
        
        if (! ibf ) {
            $(mm).hide().insertAfter(pvt).fadeIn(500);
        } else {
            $(mm).hide().insertBefore(pvt).fadeIn(500);
        }
        
        if ( drt && drt !== "top" ) {
            //(TOUJOURS) On ramène le curseur en bas de page
            _f_ToBottom($(".jb-chbx-list-msg"));
        }
            
    };
    
    var _f_ToglHr = function(a,wb,fwb) {
        /*
         *  a 
         *      true : Afficher l'heure
         *      false : MAsquer l'heure
         *  wb : (WithBottom)
         *      true : On met en forme la partie du bas
         *      false : On ne fait que se charger de la vue "time". CALLER c'est peut être déjà chargé de mettre en forme bottom
         *  frc : ForceWB
         */
        
        if ( ( wb && a ) || fwb === true ) {
            $(".jb-chbx-msgmdl-mx").addClass("wbtm");
        } else {
             $(".jb-chbx-msgmdl-mx").removeClass("wbtm");
        }
        
        ( true === a ) 
        ? $(".jb-chbx-msgmdl-mx").find(".jb-chbx-msgmdl-hr").removeClass("this_hide") 
        : $(".jb-chbx-msgmdl-mx").find(".jb-chbx-msgmdl-hr").addClass("this_hide");
    };
    
    var _f_TogMsDelChkbx = function(a,wb,fwb) {
        //frc = ForceWB
        /*
         */
        
        if ( (wb && a) || fwb === true ) {
            $(".jb-chbx-msgmdl-mx").addClass("wbtm");
        } else {
             $(".jb-chbx-msgmdl-mx").removeClass("wbtm");
        }
        
        if ( true === a ) {
            //On décoche toutes les cases à coher
            $(".jb-chbx-msgmdl-slct").prop('checked',false);
            $(".jb-chbx-msgmdl-mx").find(".jb-chbx-msgmdl-slct").removeClass("this_hide");
        } else {
            $(".jb-chbx-msgmdl-mx").find(".jb-chbx-msgmdl-slct").addClass("this_hide");
            //On décoche toutes les cases à coher
            $(".jb-chbx-msgmdl-slct").prop('checked',false);
        }
        
        _f_ToBottom($(".jb-chbx-list-msg"));
    };
    
    var _f_HidMsg = function(d) {
        try {
            if ( KgbLib_CheckNullity(d) | !d.length ) {
                return;
            }
            
            $.each(d, function(i,e) {
                $(e).addClass("this_hide");
            });
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_WpMsRes = function() {
        var b = $(".jb-chbx-list-msg");
        //On retire les anciens Messages
        $(b).find(".jb-chbx-msgmdl-mx").remove();
        //On retire LoadMore parce que c'est pas logique qu'il reste
        _f_ShwMr("conv_theater");
    };
    
    /********* SEARCH SCOPE ********/
    
    var _f_ShwLdr = function(wdw,shw) {
        if ( KgbLib_CheckNullity(wdw) ) {
            return wdw;
        }
        
        var b;
        switch (wdw) {
            case "conv_list" :
                    b = $(".jb-chbx-list-convs");
                break;
            case "conv_theater" :
                    b = $(".jb-chbx-list-msg");
                break;
            default:
                return;
        }
        
        if ( shw ) {
            if (  $(b).find(".jb-chbx-l-ldmr").hasClass("this_hide") ) {
                $(b).find(".jb-chbx-l-ldmr").removeClass("this_hide");
            }
            $(b).find(".jb-chbx-ldg-spnr").removeClass("this_hide");
        } else {
//            Kxlib_DebugVars([(b).find(".jb-chbx-l-ldmr").is("this_hide")]);
//            $(b).find(".jb-chbx-l-ldmr").addClass("this_hide");
            $(b).find(".jb-chbx-ldg-spnr").addClass("this_hide");
        }
    };
    
    /* OBSELETE
    var _f_HidLdr = function() {
        var b = $(".jb-chbx-list-convs");
        $(b).find(".jb-chbx-ldg-spnr").addClass("this_hide");
    };
    //*/
    var _f_SwMr = function(scp) {
        /*
         * Permet de changer les données au niveau de l'élément.
         */
        var b = ( scp === "min" ) ? ".jb-srh-asd-b" : ".jb-srh-hvy-b";
            
        var x = $(b).find(".jb-srh-rslt-mr");
        var t = $(x).data("target");
//        Kxlib_DebugVars([x,t],true);
//        return;
        if ( t ) {
            var nt = ( t === "fil_mn_pfl" ) ? "fil_mn_trd" : "fil_mn_pfl";
            $(x).data("target",nt);
        }
        
    };
    
    
    var _f_ShwMr = function(wdw,shw) {
        if ( KgbLib_CheckNullity(wdw) ) {
            return;
        }
         try {
            
            var b;
            switch (wdw) {
                case "conv_list" :
                        b = $(".jb-chbx-list-convs");
                    break;
                case "conv_theater" :
                        b = $(".jb-chbx-list-msg");
                    break;
                default:
                    return;
            }
            
            if (shw) {
                //wc : WasClose
                var wc = ( wdw === "conv_theater" && $(b).find(".jb-chbx-l-ldmr").hasClass("this_hide") ) ? true : false;    
//                if (wdw === "conv_theater") {
                    $(b).find(".jb-chbx-l-ldmr").removeClass("this_hide");
//                } else {
//                    $(b).find("jb-chbx-l-c-ldmr").removeClass("this_hide");
//                }
                $(b).find(".jb-chbx-rslt-mr").removeClass("this_hide");
                
                if ( wdw === "conv_theater" && wc ) {
                    _f_ToBottom($(".jb-chbx-list-msg"));
                }
            } else {
                $(b).find(".jb-chbx-l-ldmr").addClass("this_hide");
                $(b).find(".jb-chbx-rslt-mr").addClass("this_hide");
            }
            
            /*
             if ( KgbLib_CheckNullity(scp) ) 
             return;
             
             var b = ( scp === "min" ) ? ".jb-srh-asd-b" : ".jb-srh-hvy-b";
             $(b).find(".jb-srh-rslt-mr").removeClass("this_hide");
             //*/
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    /*
    var _f_HidMr = function() {
        var b = $(".jb-asd-sr-bdy");
        $(b).find(".jb-srh-no1e").addClass("this_hide");
    };
    //*/
    var _f_ShwNoOne = function (bn,shw,mc) {
        var b = $(".jb-chbx-list-convs");
        $(b).find(".jb-chbx-noone-mx").removeClass("this_hide");
    };
    
    var _f_HidNoOne = function (b) {
        if (KgbLib_CheckNullity(b)){
            return;
        }
//        var b = $(".jb-chbx-list-convs");
        $(b).find(".jb-chbx-noone-mx").addClass("this_hide");
        if ( $(b).find(".jb-chbx-noone-sof-mx").length ) {
            $(b).find(".jb-chbx-noone-sof-mx").addClass("this_hide");
        }
    };
    
    ///AWS = AvoidWhiteStyle
    var _f_ShwAWS = function () {
        $(".jb-asd-sr-bdy-nvd").removeClass("this_hide");
    };
    
    var _f_WpSrhRes = function() {
        var b = $(".jb-chbx-list-c-mx");
        $(b).find(".jb-chbx-conv-mdl-mx").remove();
    };
    
    var _f_ShwSrhFil = function(shw,fil) {
        
        if (shw) {
            var nf = ( KgbLib_CheckNullity(fil) ) ? _f_Gdf().srh_dfnm : fil;
            _f_Vw_SwSrhFil(nf);
            $(".jb-chbx-srh-fil-sprt").removeClass("this_hide");
        } else {
            $(".jb-chbx-srh-fil-sprt").addClass("this_hide");
            _f_Vw_RstSrhFil();
        }
    };
    
    var _f_Vw_SwSrhFil = function(fil) {
        
        if ( KgbLib_CheckNullity(fil) ){
            return;
        }
        
        switch (fil) {
            case "profil" :
                    $(".jb-chbx-srh-fl-chc[data-action='srh_fil_cnv']").removeAttr("data-state");
                    $(".jb-chbx-srh-fl-chc[data-action='srh_fil_cnv']").parent().removeAttr("data-state");
                    
                    $(".jb-chbx-srh-fl-chc[data-action='srh_fil_pfl']").data("state","active").attr("data-state","active");
                    $(".jb-chbx-srh-fl-chc[data-action='srh_fil_pfl']").parent().data("state","active").attr("data-state","active");
                break;
            case "conversation" :
                    $(".jb-chbx-srh-fl-chc[data-action='srh_fil_pfl']").removeAttr("data-state");
                    $(".jb-chbx-srh-fl-chc[data-action='srh_fil_pfl']").parent().removeAttr("data-state");
                    
                    $(".jb-chbx-srh-fl-chc[data-action='srh_fil_cnv']").data("state","active").attr("data-state","active");
                    $(".jb-chbx-srh-fl-chc[data-action='srh_fil_cnv']").parent().data("state","active").attr("data-state","active");
                break;
            default :
                return;
        }
    };
    
    var _f_Vw_RstSrhFil = function() {
        //On reset le filtre Profil
        $(".jb-chbx-srh-fl-chc[data-action='srh_fil_pfl']").removeAttr("data-state");
        $(".jb-chbx-srh-fl-chc[data-action='srh_fil_pfl']").parent().removeAttr("data-state");

        //On reset le filtre Conversation
        $(".jb-chbx-srh-fl-chc[data-action='srh_fil_cnv']").removeAttr("data-state");
        $(".jb-chbx-srh-fl-chc[data-action='srh_fil_cnv']").parent().removeAttr("data-state");
    };
    
    var _f_PprPflMdl = function(d) {
        if ( KgbLib_CheckNullity(d) ) {
            return;
        }
        
        /*
         * uid
         * upsd
         * ufn
         * uppic
         * ufols
         * ucap
         * ucbsts
         */
        
        var fn = Kxlib_Decode_After_Encode(d.ufn);
        var psd = Kxlib_Decode_After_Encode(d.upsd);
            
        var e = "<div class=\"chbx-conv-mdl-mx jb-chbx-conv-mdl-mx parley\" data-item=\""+d.uid+"\" ";
        e += "data-cache=\"["+d.uid+","+psd+","+fn+","+d.uppic+","+d.ufols+","+d.ucap+","+d.ucbsts+"]\">";
        e += "<div class=\"chbx-c-m-left\">";
        e += "<input class=\"chbx-mdl-slct jb-chbx-mdl-slct this_hide\" type=\"checkbox\" />";
        e += "<span class=\"chbx-mdl-psd-i-fade\" />";
        e += "<img class=\"chbx-mdl-psd-img jb-chbx-mdl-psd-img\" src=\""+d.uppic+"\" height=\"55\" width=\"55\"/>";
        e += "<span class=\"chbx-usts-led jb-chbx-usts-led this_hide\"></span>";
        e += "</div>";
        e += "<div class=\"chbx-c-m-right noslct\">";
        e += "<div class=\"chbx-c-m-r-top\">";
        e += "<span class=\"chbx-mdl-psd\">@"+psd+"</span>";
        e += "</div>";
        e += "<div class=\"chbx-c-m-r-btm\">";
        e += "<a class=\"chbx-lets-speak jb-chbx-lets-spk jb-chbx-action\" data-action=\"parley\" href=\"javascript:;\" role=\"button\" title=\"Engager la conversation\">Parley</a>";
        e += "</div>";
        e += "</div>";
        e += "</div>";

        e = $.parseHTML(e);
            
        return e;
        
    };
    
    var _f_PprConvMdl = function(d) {
        if ( KgbLib_CheckNullity(d) ) {
            return;
        }
        try {
            
            /*
             * TABLE DES CLES 
             * cvid
             * [19-01-15] NOTE : Dans le cas d'une Conversation vide, on a pas accès aux données de Message
             * chbm_id
             * chbm_msg
             * chbm_cd
             * chbm_rd
             * //CIBLE
             * uid
             * upsd
             * ufn
             * uppic
             * ufols
             * ucap
             * ucbsts //[07-01-15] Retiré, sens ambigu
             */
            
            var fn = Kxlib_Decode_After_Encode(d.ufn);
            var psd = Kxlib_Decode_After_Encode(d.upsd);
            
            var msg;
            if ( d.hasOwnProperty("chbm_msg") && !KgbLib_CheckNullity(d.chbm_msg) ) {
                msg = d.chbm_msg.substr(0, _f_Gdf().cnv_max_smpl);
            }
            
            if ( d.hasOwnProperty("chbm_cd") && !KgbLib_CheckNullity(d.chbm_cd) ) {
                var dt = new KxDate(parseInt(d.chbm_cd));
                dt.SetUTC(true);
                //On insere la date
                var strtime = dt.WriteDate();
                //TODO : Mettre un title pour donner la date et l'heure exacte avec un format qui est lié à la langue de diffusion
            }
//           Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(msg))
            // onselection jb-skip 
            var e = "<div class=\"chbx-conv-mdl-mx jb-chbx-conv-mdl-mx jb-chbx-action\" data-action=\"nav_conv_theater\" data-item=\"" + d.cvid + "\" time=\"" + Kxlib_ReplaceIfUndefined(d.chbm_cd) + "\" ";
            e += "data-cache=\"[" + d.cvid + "," + Kxlib_ReplaceIfUndefined(d.chbm_id) + ",{cnvm}," + Kxlib_ReplaceIfUndefined(d.chbm_cd) + "," + Kxlib_ReplaceIfUndefined(d.chbm_rd) + "," + d.uid + "," + psd + "," + fn + "," + d.uppic + "," + d.ufols + "," + d.ucap + "]\">";
//        e += "data-cache=\"["+d.cvid+","+d.chbm_id+","+d.chbm_msg+","+d.chbm_cd+","+d.chbm_rd+","+d.uid+","+psd+","+fn+","+d.uppic+","+d.ufols+","+d.ucap+","+d.ucbsts+"]\">";
            e += "<div class=\"jb-chbx-mdl-cldstrg this_hide\">";
            e += "<span class=\"jb-chbx-mdl-csg-elt\" data-item=\"cnvm\">"+msg+"</span>";
            e += "</div>";
            e += "<div class=\"chbx-c-m-slct-mx jb-chbx-c-m-slct-mx this_hide\">";
            e += "<input class=\"chbx-c-m-slct-ipt jb-chbx-c-m-slct-ipt\" type=\"checkbox\">";
            e += "</div>";
            e += "<div class=\"chbx-c-m-left\">";
//            e += "<input class=\"chbx-mdl-slct this_hide\" type=\"checkbox\" />";
            e += "<span class=\"chbx-mdl-psd-i-fade\" />";
            e += "<img class=\"chbx-mdl-psd-img\" src=\"" + d.uppic + "\" height=\"55\" width=\"55\"/>";
            e += "<span class=\"chbx-usts-led this_hide\"></span>";
            e += "</div>";
            e += "<div class=\"chbx-c-m-right jb-chbx-c-m-right noslct\">";
//            e += "<div class=\"chbx-c-m-right noslct\">";
            e += "<div class=\"chbx-c-m-r-top\">";
            e += "<span class=\"chbx-mdl-psd jb-chbx-mdl-psd\">@" + psd + "</span>";
            e += "<span class=\"chbx-mdl-nwink jb-chbx-mdl-nwink this_hide\">15</span>";
            e += "<span class=\"chbx-mdl-time jb-chbx-mdl-time\">" + Kxlib_ReplaceIfUndefined(strtime) + "</span>";
            e += "</div>";
            e += "<div class=\"chbx-c-m-r-btm\">";
            e += "<span class=\"chbx-mdl-sample jb-chbx-mdl-spl\">" + Kxlib_ReplaceIfUndefined(msg) + "</span>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e = $.parseHTML(e);
            
            if ( d.cvird ) {
                $(e).addClass("onunread");
            }
            
            return e;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /***********************************************************************************************************************************************************/
    /******************************************************************** LISTENERS SCOPE **********************************************************************/
    /***********************************************************************************************************************************************************/
    
    $(".jb-chbx-action:not(.jb-skip)").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_PerformAction(this);
    });
    
    $(".jb-chbx-nwmsg-ipt").keypress(function(e){
        if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) { 
            Kxlib_PreventDefault(e);
            
            _f_PerformAction(this);
//            $(".jb-chbx-action[data-action='newmessage']").click(); 
        }
    });
    
    $(".jb-chbx-opt-tgr").click(function(e){
        Kxlib_PreventDefault(e);
       _f_Options(this);
    });
    
    $(".jb-chbx-opt-tgr").blur(function(e){
        Kxlib_PreventDefault(e);
       _f_Options(this,true);
    });
    
    $(".jb-chbx-list-convs").scroll(function(){
        
    });
    
    $(".jb-chbx-list-msg").scroll(function(){

    });
    
    $(".jb-asd-c-h-t-ipt").keyup(function(){
        _f_CatchQry(this);
    });
    
    $(".jb-chbx-wrng-xpmdl-wrngm-lrnab, .jb-chbx-wrng-xpmdl-xplnd-clz, .jb-chbx-wrng-xpmdl-gaw").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_XpMdAct(this);
    });
    
    $(".jb-asd-c-h-home").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_PerformAction(this);
    });
    
    $(".jb-chbx-crnr-if-mx").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_NotAct(this);
    });
    
    /***********************************************************************************************************************************************************/
    /************************************************************************ INIT SCOPE ***********************************************************************/
    /***********************************************************************************************************************************************************/
    
    (function(){
        //INIT
        try {
            var PgEnv;
            PgEnv = ( document.getElementById("tq-pg-env") ) ? JSON.parse(document.getElementById("tq-pg-env").innerHTML) : null;
            if (! PgEnv ) {
                throw "A fatal error occurred. The environment cannot be loaded properly.";
            }
            
//            localStorage.clear();
//            sessionStorage.clear();
            //*
            _asdApps = _f_GetAsdApps();
            if ( !_asdApps ) {
                /*
                 * [DEPUIS 12-06-16]
                 */
                if ( PgEnv.pgvr && PgEnv.pgvr.toUpperCase() !== "WU" ) {
                    alert("Une anomalie a été constatée. Certaines fonctionnalités de Trenqr ne pourront peut être pas vous être fournies normalement.");
                }
            } else {
                _f_InitConf();
            }
            /*
             * [DEPUIS 12-11-15]
             */
            _f_AuNot();
            //*/
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    })();
}

new ChatBox();