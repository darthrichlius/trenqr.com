
/*
 * MNFM = MaiNFraMe
 * Entity pour les opérations liées au produit.
 * Il peut tout aussi bien s'agir de l'expérience utilisateur.
 */
function MNFM() {
    /*
     * Gère certaines fonctionnalités du produit, modules non compris.
     * 
     * FONCTIONNALITES
     *  Version : b.1505.1.1 (Juillet 2015 VBeta1) [DATE UPDATE : 02-07-15]
     *      -> Permet d'enregistrer des décisions sur les préférences de façon générique
     *      -> Gère les vérifications de confort
     *  
     *  EVOLUTIONS ATTENDUES
     *      -> Changer le mode d'affichage. On pourra avoir un style : warinng, error ou autres.
     *      -> A chaque fois qu'un utilisteur tombe sur un problème de confort (par SESSION) en le signale auprès du serveur
     *  
     *  EVOLUTIONS POSSIBLES
     *      -> ...
     */
    
    var gt =  this;
    
    var _cmft_bddvc;
    var _cmft_bdscrn;
    var _cmft_bdbrwz;
    
    /***************************************************************************************************************************************************/
    /****************************************************************** PROCESS SCOPE ******************************************************************/
    /***************************************************************************************************************************************************/
    
    var _f_Gdf = function () {
        var dt = {
            "_CMFY_SCRN_MIN" : { h : 610, w : 1345 },
//            "_CMFY_SCRN_MIN" : { h : 2000, w : 2000 }, //TEST
            "_CMFY_NVGTR_LIST" : ["OPERA","CHROME","SAFARI","FIREFOX","MSIE"],
            /*
             * [NOTE 02-07-15] @BOR
             * Ces données pourront être transférées ailleurs le cas échéant.
             * NaM (Not an Issue => Toutes les versions. Cela peut aussi être la conséquence d'un manque de connaissances suffisant pour tirer une conclusion.
             */
            "_CMFY_NVGTR_VER_MIN" : { 
                "OPERA"     : "NaI", 
                "CHROME"    : "NaI", 
                "SAFARI"    : 6, 
                "FIREFOX"   : "NaI",
                "MSIE"      : 10
            },
            "_CMFY_NVGTR_UPGRD" : { 
                "OPERA"     : "http://www.opera.com/", 
                "CHROME"    : "http://www.google.com/chrome", 
                "SAFARI"    : "http://www.apple.com/safari/", 
                "FIREFOX"   : "http://www.firefox.com/",
                "MSIE"      : "http://windows.microsoft.com/ie"
            }
        };
        
        return dt;
    };
    
    var _f_Init = function () {
        try {
            /*
             * [NOTE 04-17-15] @BOR
             * Ne pas uncomment sauf dans les de DEV, TEST et DEBUG très précis au risque de corrompre profondement le système.
             */
            /* 
                docCookies.removeItem("TQRCMFRT_DVC");
                docCookies.removeItem("TQRCMFRT_SCN");
                docCookies.removeItem("TQRCMFRT_NGT");
            //*/
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    //STAY PUBLIC
    /**
     * Cette méthode permet de traiter les opérations relatives à la sauvegarde des préférences.
     * Cette méthode est recommandée que pour les cas les plus simples où il n'est pas besoin d'effectuer des tâches trop lourdes au retour des données du serveur.
     * 
     * @param {type} opi
     * @param {type} dci
     * @param {type} s
     * @param {type} rap
     * @param {type} x
     * @returns {Boolean|Array|undefined}
     */
    this.SetPrfrcs = function(opi,dci,s,rap,x) {
        /*
         * opi : OPerationId
         * dci : DeCisionId
         * s : ServerSnitcher (Facultatif)
         * rap : ReturnAjaxPointer. DEFAULT : FALSE
         * x : L'ément déclencheur (Facultatif)
         */
        try {
                    
            if ( KgbLib_CheckNullity(opi) | KgbLib_CheckNullity(dci) ) {
                return;
            }
            
            rap = ( KgbLib_CheckNullity(rap) ) ? false : rap;
            
            if ( KgbLib_CheckNullity(s) ) {
                s = $("<span/>");
            }
            var ap = _f_Srv_SetPrfrcs(opi,dci,s);
            
            /*
             * Si CALLER n'a pas envoyé 's' ou demande de retourner l'AjaxPointer, on les renvoie.
             */
            if ( KgbLib_CheckNullity(s) && rap === true ) {
                return [s,ap];
            } else if ( KgbLib_CheckNullity(s) && rap === false ) {
                return [s,null];
            } else if ( !KgbLib_CheckNullity(s) && rap === true ) {
                return [null,ap];
            } else {
                return true;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_SetPrfrcs_Betver = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            //On lock le bouton
            $(x).data("lk",1);
            
            /*
             * ETAPE :
             * On fait disparaire la bande quelque soit les cas.
             * 
             * [NOTE 21-06-15] 
             *  -> Si on rencontre une erreur, on laisse couler. Dans le pire des cas, l'utilisateur reverra la bande à la nouvelle connexion ou en changeant de page.
             *     Si l'erreur persiste nous espérons qu'il l'a signale.
             */
            $(".jb-tqr-wrng-betver-mx").addClass("this_hide");
            
            var s = $("<span/>");
            gt.SetPrfrcs("_PFOP_TIABT_INR","_DEC_DSMA",s);
            
            $(s).on("operended",function(e,d){
                
                //On affiche la notification de bienvenue
                var Nty = new Notifyzing ();
                Nty.FromUserAction("TQR_WLCM_BETA");
                
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*************************************************************************************************************************************************************/
    /*********************************************************************** COMFORT SCOPE ***********************************************************************/
    
    var _f_CmfrtAct = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | $(x).data("action") ) {
                return;
            }
            
            var a = $(x).data("action");
            switch (a.toLowerCase()) {
                case "close" :
                        _f_Cmfrt_Bbx(false);
                    break;
                case "close-with-caution" :
                        /*
                         * On vérifie si la case est cochée.
                         * Dans ce cas, on enregistre la réponse dans un cookie pour qu'il ne puisse plus s'afficher
                         */
                        if ( $(".jb-tqr-mnfm-btm-infbx-opt-chkbx") && $(".jb-tqr-mnfm-btm-infbx-opt-chkbx").length && $(".jb-tqr-mnfm-btm-infbx-opt-chkbx").is(":checked") ) {
                            var jct = $(".jb-tqr-mnfm-btm-infbx-bmx").data("jct");
                            if (! KgbLib_CheckNullity(jct) ) {
                                _f_CmfrtSvLclDc(jct);
                            }
                            
                        }
                        _f_Cmfrt_Bbx(false);
                    break;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_CmfrtSvLclDc = function (j) {
        try {
            if ( KgbLib_CheckNullity(j) ) {
                retrun;
            }
            
            j = j.toUpperCase();
            switch (j) {
                case "DVC":
                        jct = "TQRCMFRT_DVC";
                    break;
                case "SCN":
                        jct = "TQRCMFRT_SCN";
                    break;
                case "NGT":
                        jct = "TQRCMFRT_NGT";
                    break;
                default:
                    return;
            }
            
            /*
             * ETAPE :
             * On vérifie si un cookie n'est pas déjà installé. Dans ce cas, on n'enregistre pas la décision
             */
            if ( docCookies.hasItem(jct) ) {
                return;
            }
            
            /*
             * ETAPE :
             * On enregistre la décision 
             */
            docCookies.setItem(jct,"DSMA",Infinity);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_CmfrtDevice = function () {
        try {
            if ( Kxlib_NvgtrIsMobile() ) {
                /*
                 * On commence par indiquer le résultat
                 */
                _cmft_bddvc = true;
                
                /*
                 * [DEPUIS 04-07-15] @BOR
                 * On vérifie si un cookie n'est pas présent avec une réponse de type DSMA.
                 */
                if ( docCookies.hasItem("TQRCMFRT_DVC") && docCookies.getItem("TQRCMFRT_DVC").toUpperCase() === "DSMA" ) {
//                    alert("IGNORED TQRCMFRT_DVC by COOKIE !");
                    /*
                     * Cela permet de laisser l'opportunité aux autres processus de se faire.
                     * D'autant plus que dans ce cas, étant donné que l'utilisateur ne veut plus être prévenu, alors on ne peut pas considérer qu'il y a un problème.
                     */
                    _cmft_bddvc = false;
                    return;
                }
                
                /*
                 * On affiche le message d'erreur en mode "Alert" pour attirer tout de suite l'attention de l'utilisateur.
                 * On affiche que dans le cas d'un device mobile pour ne pas trop solliciter l'utilisateur.
                 * En effet, sur les Mobiles l'utilisateur pourrait ne pas voir le message ou y apporter trop d'attention.
                 */
                 if ( Kxlib_NvgtrIsMobile() ) {
                     var pup = Kxlib_getDolphinsValue("TQR_CMFRT_DVC_PUP");
                     /*
                      * [DEPUIS 25-11-15] @author
                      *     RETIRE
                      *         (1) Le message apparait à chaque fois. C'est très génant pour le confort d'utilisation
                      *         (2) Les pages sont maintenant plus adaptées aux écrans de Mobile. Ce n'est pas parfait mais ça fait l'affaire
                      *         (3) La zone d'en bas suffit largement. Les utilisateurs finiront par comprendre qu'utiliser leur mobile n'est pas une bonne idée.
                      */
//                     alert(pup);
                 }
                
                /*
                 * On affiche le message dans la zone en bas
                 */
                _f_Cmfrt_Bbx("TQR_CMFRT_DVC_BBX",true);
                
            } else {
                _cmft_bddvc = false;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_CmfrtScreen = function () {
        try {
//            Kxlib_DebugVars(["LINE_149",screen, screen.height < _f_Gdf()._CMFY_SCRN_MIN.h, screen.width < _f_Gdf()._CMFY_SCRN_MIN.w, typeof _cmft_bddvc, _cmft_bddvc === false],true);
            if ( screen && ( screen.height < _f_Gdf()._CMFY_SCRN_MIN.h | screen.width < _f_Gdf()._CMFY_SCRN_MIN.w  && _cmft_bddvc === false ) ) {
                /*
                 * On commence par indiquer le résultat
                 */
                _cmft_bdscrn = true;
                
                /*
                 * [DEPUIS 04-07-15] @BOR
                 * On vérifie si un cookie n'est pas présent avec une réponse de type DSMA.
                 */
                if ( docCookies.hasItem("TQRCMFRT_SCN") && docCookies.getItem("TQRCMFRT_SCN").toUpperCase() === "DSMA" ) {
//                    alert("IGNORED TQRCMFRT_SCN by COOKIE !");
                    /*
                     * Cela permet de laisser l'opportunité aux autres processus de se faire.
                     * D'autant plus que dans ce cas, étant donné que l'utilisateur ne veut plus être prévenu, alors on ne peut pas considérer qu'il y a un problème.
                     */
                    _cmft_bdscrn = false;
                    return;
                }
                
                /*
                 * On affiche le message d'erreur en mode "Alert" pour attirer tout de suite l'attention de l'utilisateur.
                 * On affiche que dans le cas d'un device mobile pour ne pas trop solliciter l'utilisateur.
                 * En effet, sur les Mobiles l'utilisateur pourrait ne pas voir le message ou y apporter trop d'attention.
                 */
                if ( Kxlib_NvgtrIsMobile() ) {
                    var pup = Kxlib_getDolphinsValue("TQR_CMFRT_SCN_PUP");
                    /*
                      * [DEPUIS 25-11-15] @author
                      *     RETIRE
                      *         (1) Le message apparait à chaque fois. C'est très génant pour le confort d'utilisation
                      *         (2) Les pages sont maintenant plus adaptées aux écrans de Mobile. Ce n'est pas parfait mais ça fait l'affaire
                      *         (3) La zone d'en bas suffit largement. Les utilisateurs finiront par comprendre qu'utiliser leur mobile n'est pas une bonne idée.
                      */
//                    alert(pup);
                }
                
                /*
                 * On affiche le message dans la zone en bas
                 */
                _f_Cmfrt_Bbx("TQR_CMFRT_SCN_BBX",true);

            } else {
                _cmft_bdscrn = false;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_CmfrtBrowzer = function () {
        try {
            var ds = Kxlib_NvgtrSayWho();
            if ( !KgbLib_CheckNullity(ds) && ( ds.hasOwnProperty("name") && !KgbLib_CheckNullity(ds.name) ) && ( ds.hasOwnProperty("version") && !KgbLib_CheckNullity(ds.version) ) 
                    && _cmft_bddvc === false && _cmft_bdscrn === false ) {
                /*
                 * On commence par indiquer le résultat
                 */
                _cmft_bdbrwz = true;
                
                /*
                 * [DEPUIS 04-07-15] @BOR
                 * On vérifie si un cookie n'est pas présent avec une réponse de type DSMA.
                 */
                if ( docCookies.hasItem("TQRCMFRT_NGT") && docCookies.getItem("TQRCMFRT_NGT").toUpperCase() === "DSMA" ) {
//                    alert("IGNORED TQRCMFRT_NGT by COOKIE !");
                    /*
                     * Cela permet de laisser l'opportunité aux autres processus de se faire.
                     * D'autant plus que dans ce cas, étant donné que l'utilisateur ne veut plus être prévenu, alors on ne peut pas considérer qu'il y a un problème.
                     */
                    _cmft_bdbrwz = false;
                    return;
                }
                
                var nm = ds.name.toUpperCase();
                var vr = parseInt(ds.version);
                
                /*
                 * On vérifie si le navigateur est connu
                 */
                if ( $.inArray(nm,_f_Gdf()._CMFY_NVGTR_LIST) === -1 ) {
                    return;
                } 
                
                /*
                 * On vérifie si la version du Navigateur est suffisante pour une navigation sans problème
                 */
                var vr_min = ( typeof _f_Gdf()._CMFY_NVGTR_VER_MIN[nm] === "number" ) ? parseInt(_f_Gdf()._CMFY_NVGTR_VER_MIN[nm]) : _f_Gdf()._CMFY_NVGTR_VER_MIN[nm];
//                Kxlib_DebugVars([vr, vr_min, vr_min !== "NaI", vr < vr_min],true);
                if ( vr_min !== "NaI" && vr < vr_min ) {
                    
                   /*
                    * On affiche le message d'erreur en mode "Alert" pour attirer tout de suite l'attention de l'utilisateur.
                    * On affiche que dans le cas d'un device mobile pour ne pas trop solliciter l'utilisateur.
                    * En effet, sur les Mobiles l'utilisateur pourrait ne pas voir le message ou y apporter trop d'attention.
                    */
                    if ( Kxlib_NvgtrIsMobile() ) {
                        var pup = Kxlib_getDolphinsValue("TQR_CMFRT_NGT_PUP");
                        /*
                      * [DEPUIS 25-11-15] @author
                      *     RETIRE
                      *         (1) Le message apparait à chaque fois. C'est très génant pour le confort d'utilisation
                      *         (2) Les pages sont maintenant plus adaptées aux écrans de Mobile. Ce n'est pas parfait mais ça fait l'affaire
                      *         (3) La zone d'en bas suffit largement. Les utilisateurs finiront par comprendre qu'utiliser leur mobile n'est pas une bonne idée.
                      */
//                        alert(pup);
                    }

                    /*
                     * On affiche le message dans la zone en bas
                     */
                    _f_Cmfrt_Bbx("TQR_CMFRT_NGT_BBX",true);

                }
                
            } else {
                _cmft_bdbrwz = false;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /***************************************************************************************************************************************************/
    /******************************************************************* CLOCK SCOPE *******************************************************************/
    /***************************************************************************************************************************************************/
    
    /***************************************************************************************************************************************************/
    /******************************************************************* AUTO SCOPE ********************************************************************/
    /***************************************************************************************************************************************************/
    
    setTimeout(function(){
        /*
         * [DEPUIS 01-07-16]
         */
//        _f_CmfrtDevice();
    },1100);
    setTimeout(function(){
        _f_CmfrtScreen();
    },2000);
    setTimeout(function(){
        _f_CmfrtBrowzer();
    },3000);
    
    /***************************************************************************************************************************************************/
    /******************************************************************** SERVER SCOPE *****************************************************************/
    /***************************************************************************************************************************************************/
    
    var _Ax_SetPrfrcs = Kxlib_GetAjaxRules("TQREX_STPRFRCS");
    var _f_Srv_SetPrfrcs = function(opi,dci,s) {
        if ( KgbLib_CheckNullity(opi) | KgbLib_CheckNullity(dci)| KgbLib_CheckNullity(s) ) {
            return;
        }
                
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    return;
                }
                
                if ( !KgbLib_CheckNullity(d.err) ) {
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
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
                                    $(s).trigger("err",["EXPTDERR",d.err]);
                                break;
                            case "__ERR_VOL_FAILED" :
                                    $(s).trigger("err",["EXPTDERR",d.err]);
                                break;
                            case "__ERR_VOL_DATAS_MSG" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                    $(s).trigger("err",["EXPTDERR",d.err]);
                                break;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                    $(s).trigger("err",["EXPTDERR",d.err]);
                                break;
                            default:
                                    /*
                                     * [NOE 14-04-15] @BOR
                                     * Cette méthode peut être utilisée de manière automatique, on n'affiche pas les erreurs pour les cas non gérés
                                     */
//                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(d.return) )  {
                    $(s).trigger("operended");
//                    rds = [d.return];
//                    $(s).trigger("operended",rds);
                } else {
                    $(s).trigger("err",["VOID"]);
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                $(s).trigger("err",["UEXPTDERR",ex]);
                return;
            }
        };

        var onerror = function (a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
//        Kxlib_DebugVars([opi,dci,u],true);
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_SetPrfrcs.urqid,
            "datas": {
                "opi"   : opi,
                "dci"   : dci,
                "u"     : u 
            }
        };

        var ap__ = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_SetPrfrcs.url, wcrdtl : _Ax_SetPrfrcs.wcrdtl });
        return ap__;
    };
    
    
    /***************************************************************************************************************************************************/
    /******************************************************************** VIEW SCOPE *******************************************************************/
    /***************************************************************************************************************************************************/
    
    var _f_Cmfrt_Bbx = function (i,sh) {
        try {
            if ( ( !KgbLib_CheckNullity(sh) && sh === true && KgbLib_CheckNullity(i) ) | !$(".jb-tqr-mnfm-btm-infbx-bmx").length ) {
                return;
            }
            
            var scnh = screen.height;
            if ( sh ) {
                
                /*
                 * [DEPUIS 04-07-15] @BOR
                 * Dans tous les cas, on indique quel est le cas en pésence pour des raisons fonctionnelles
                 */
                var jct;
                switch (i) {
                    case "TQR_CMFRT_DVC_BBX":
                            jct = "DVC";
                        break;
                    case "TQR_CMFRT_SCN_BBX":
                            jct = "SCN";
                        break;
                    case "TQR_CMFRT_NGT_BBX":
                            jct = "NGT";
                        break;
                    default:
                        return;
                }
                $(".jb-tqr-mnfm-btm-infbx-bmx").data("jct",jct);
                
                
                var bbx = Kxlib_getDolphinsValue(i);
                if (! bbx ) {
                    return;
                }
                
                /*
                 * On vérifie si on est dans le vas d'un navigateur érroné.
                 */
                if ( i === "TQR_CMFRT_NGT_BBX" ) {
                    var $t__ = $("<div/>").html(bbx);
                    var n__ = Kxlib_NvgtrSayWho().name.toUpperCase();
                    
                    /*
                     * On vérifie qu'on a bien les données pour le navigateur en cours.
                     */
                    if ( $.inArray(n__,_f_Gdf()._CMFY_NVGTR_LIST) === -1 ) {
                        $t__.find(".go-update").remove();
                    } else {
                        $t__.find(".go-update").attr("href",_f_Gdf()._CMFY_NVGTR_UPGRD[n__]);
                    }
                    
                    bbx = $t__.html();
                } 
                
                /*
                 * On vérifie s'il y a déjà un élément visible. 
                 * Si c'est le cas, on le masque avant d'afficher le nouveau message. 
                 */
                if (! $(".jb-tqr-mnfm-btm-infbx-bmx").hasClass("this_hide") ) {
                    var b__ = scnh * -1;
                    $(".jb-tqr-mnfm-btm-infbx-bmx").stop(true,true).animate({ 
                        bottom : b__+"px"
                    }, 1000, function(){
                        $(this).addClass("this_hide").removeAttr("style");
                       /*
                        * On insère le texte en mode HTML pour permettre d'insérer des éléments tels que de LIENs le cas échéant.
                        */
                        $(".jb-tqr-mnfm-btm-infbx-msg").html(bbx);

                       /*
                        * On affiche la zone 
                        */
                        $(".jb-tqr-mnfm-btm-infbx-bmx").hide().removeClass("this_hide").fadeIn();
                    });
                } else {
                   /*
                    * On insère le texte en mode HTML pour permettre d'insérer des éléments tels que de LIENs le cas échéant.
                    */
                    $(".jb-tqr-mnfm-btm-infbx-msg").html(bbx);

                   /*
                    * On affiche la zone 
                    */
                    $(".jb-tqr-mnfm-btm-infbx-bmx").hide().removeClass("this_hide").fadeIn();
                }
                
            } else {
                var b__ = scnh * -1;
                $(".jb-tqr-mnfm-btm-infbx-bmx").stop(true,true).animate({ 
                    bottom : b__+"px"
                }, 1000, function(){
                    $(this).addClass("this_hide").removeAttr("style");
                   /*
                    * On retire le texte (Just In Case)
                    */
                    $(".jb-tqr-mnfm-btm-infbx-msg").html("");
                });
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /***************************************************************************************************************************************************/
    /***************************************************************** LISTENERS SCOPE *****************************************************************/
    /***************************************************************************************************************************************************/
    
    $(".jb-tqr-wrng-betver-done").click(function(e){
         Kxlib_PreventDefault(e);
         
        _f_SetPrfrcs_Betver(this);      
    });
    
    $(".jb-tqr-mnfm-btm-infbx-clz, .jb-tqr-mnfm-btm-infbx-dec-tgr").click(function(e){
         Kxlib_PreventDefault(e);
         
        _f_CmfrtAct(this);      
    });
    
    /***************************************************************************************************************************************************/
    /******************************************************************** INIT SCOPE *****************************************************************/
    /***************************************************************************************************************************************************/
    _f_Init();
};

new MNFM();