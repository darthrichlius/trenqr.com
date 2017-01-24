

(function(){
    
    var _xhr_sbmt;
    
    /**********************************************************************************************************************************************************************/
    /**************************************************************************** PROCESS SCOPE ***************************************************************************/
    /**********************************************************************************************************************************************************************/
    
    var _f_Gdf = function() {
        var df = {
            "max_emlnb"     : 10,
            "rgx_fn"        : /^(?=.*[a-z])[a-z-\+\. ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,30}$/i,
            "rgx_eml"       : /^[\w.+-]+@(?:[\w\d-]+\.)+[a-z]{2,6}$/i
        };
        
        return df;
    };

    
    var _f_Action = function(x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && KgbLib_CheckNullity(a) ) )  {
                return;
            }
            
            var ac = ( a ) ? a : $(x).data("action");
            switch (ac) {
                case "add_nw_eml" :
                        _f_AddEml(x);
                    break;
                case "del_eml" :
                        _f_DlEml(x);
                    break;
                case "reset_form" :
                        _f_RstFrm(x);
                    break;
                case "submit" :
                        _f_Sbmt(x);
                    break;
                case "back" :
                        _f_SwScrn();
                    break;
                case "send" :
                        _f_Sbmt(x);
                    break;
                default :
                    return;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_AddEml = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) )  {
                return;
            }
            
            /*
             * ETAPE :
             *  On vérifie si le nombre maximal est atteint
             */
            if ( $(".jb-tqr-rcmd-frm-lst-eml").length === _f_Gdf().max_emlnb ) {
                return;
            }
            
            var vl = $(".jb-form-lbl-ipt[data-field='rcpt_eml']").val().toLowerCase();
            if ( KgbLib_CheckNullity(vl) ) {
                return;
            }
            
            /*
             * ETAPE :
             *  On vérifie qu'il n'y a pas déjà l'email dans la liste
             */
            if ( $(".jb-tqr-rcmd-frm-lst-eml[data-email='"+vl+"']").length ) {
                return;
            }
            
            /*
             * ETAPE :
             *  On vérifie que l'email est de la forme d'un email
             */
            var erp = $(".jb-form-fld-err-mx this_hide[data-field='rcpt_eml']");
            if (! _f_Gdf().rgx_eml.test(vl) ) {
                var erm = "Erreur";
                $(erp).find(".jb-form-fld-err").text(erm);
                $(erp).removeClass("this_hide");
                
                return;
            } else {
                $(erp).addClass("this_hide");
            }
                    
            /*
             * ETAPE :
             *  On crée le modèle 
             */
            var em = "<li class=\"tqr-rcmd-frm-lst-eml jb-tqr-rcmd-frm-lst-eml\" data-email=\"\">";
            em += "<span class=\"tqr-rcmd-lst-eml-addr jb-tqr-rcmd-lst-eml-addr\" title=\"\"></span>";
            em += "<a class=\"tqr-rcmd-lst-eml-rmv jb-tqr-rcmd-lst-eml-rmv\" data-action=\"del_eml\" href=\"javascript:;\" title=\"Retirer de la liste\">×</a>";
            em += "</li>";
                  
            em = $.parseHTML(em);
            
            /*
             * ETAPE :
             *  On ajoute les données au modèle 
             */
            $(em).data("email",vl);
            $(em).attr("data-email",vl);
            $(em).find(".jb-tqr-rcmd-lst-eml-addr").prop("title",vl).text(vl);
            
            /*
             * ETAPE :
             *  On rebind le modèle 
             */
            $(em).find(".jb-tqr-rcmd-lst-eml-rmv").click(function(e){
                Kxlib_PreventDefault(e);
                _f_Action(this);
            });
            
            /*
             * ETAPE :
             *  On ajoute à la liste
             */
            $(em).hide().prependTo(".jb-tqr-rcmd-frm-lst-eml-mx").fadeIn();
            
            /*
             * ETAPE :
             *  On vérifie si on est dans le cas de l'erreur 10.
             *  Ce qui signifie qu'un message d'erreur a signalé à l'utilisateur qu'il faudrait qu'il ajoute au moins un email.
             */
            var emb = $(".jb-form-fld-err-mx[data-field='rcpt_eml']");
            if ( !$(emb).hasClass("this_hide") && $(emb).find(".jb-form-fld-err").data("errcd") === 10 ) {
                $(emb).addClass("this_hide");
            }
            
            /*
             * ETAPE :
             *  On vide le champ
             */
            $(".jb-form-lbl-ipt[data-field='rcpt_eml']").val("");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_DlEml = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) )  {
                return;
            }
            
            var bm = $(x).closest(".jb-tqr-rcmd-frm-lst-eml");
            if ( $(bm).length ) {
                $(bm).remove();
                
                /*
                 * On vérifie le cas de l'erreur sur les emaux similaires
                 */
                var t__ = [];
                $(".jb-tqr-rcmd-frm-lst-eml").filter(function(){
                    if ( $(this).data("errcd") === 3 ) {
                        t__.push(this);
                    }
                });
                var x__ = $(".jb-form-fld-err-mx[data-field='rcpt_eml']").find(".jb-form-fld-err");
//                Kxlib_DebugVars([!$(x__).hasClass("this_hide"), $(x__).data("errcd") === 30, !t__.length,JSON.stringify(t__)],true);
                if ( !$(x__).hasClass("this_hide") && $(x__).data("errcd") === 30 && !t__.length ) {
                    $(".jb-form-fld-err-mx[data-field='rcpt_eml']").addClass("this_hide");
                }
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_RstFrm = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) )  {
                return;
            }
            
            /*
             * On réinitialise les champs du formulaire
             */
            Kxlib_ResetForm("tqr-rcmd-form-mx");
            /*
             * On masque les erreurs
             */
            $(".jb-form-fld-err").addClass("this_hide");
            /*
             * On reset le Captcha
             */
            grecaptcha.reset();
            /*
             * On retire les emaux de la liste
             */
            $(".jb-tqr-rcmd-frm-lst-eml").remove();
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Sbmt = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) )  {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            /*
             * ETAPE :
             *  On vérifie que les champs obligatoires sont correctement renseignés.
             *  La vérification prend en compte les emaux 
             */
            if ( _f_ChkFlds() ) {
                _f_RstSbmtBtns();
                return;
            };
            
            /*
             * ETAPE :
             *  On vérifie que la case sur les mentions légales est cochée
             */
            if (! $(".jb-tqr-rcmd-frm-lgls-ipt").is(":checked") ) {
                alert("Vous devez accepter nos conditions de service.");
                _f_RstSbmtBtns();
                return;
            };
            
            /*
             * ETAPE :
             *  On vérifie qu'on a une réponse captcha.
             *  Sinon, on signale l'erreur. De plus, si on est sur la vue de SAMPLE, on revient sur main
             */
            if ( KgbLib_CheckNullity(grecaptcha.getResponse()) ) {
                alert("Vous devez nous confirmer que vous n'êtes pas un robot en validant le captcha.");
                _f_RstSbmtBtns();
                return;
            }
            
            /*
             * ETAPE :
             *  On vérifie que la case sur la visualisation est cochée.
             *  Dans ce cas, on envoie pas la demande. On affiche tout d'abord le sample
             */
            if ( $(".jb-tqr-rcmd-see-smpl-ipt").is(":checked") && $(x).data("action") !== "send" ) {
                var s_fn = Kxlib_FstLetterCap($(".jb-form-lbl-ipt[data-field='sndr_fn']").val());
                if ( $(".jb-tqr-rcmd-frm-lst-eml").length === 1 ) {
                    var r_fn = Kxlib_FstLetterCap($(".jb-form-lbl-ipt[data-field='rcpt_fn']").val());
                    $(".rcpt_fn").text(r_fn);
                    $(".rcpt_fn").removeClass("this_hide");
                } else {
                    $(".rcpt_fn").addClass("this_hide");
                }
                
                $(".sender_fn").text(s_fn);
                _f_SwScrn();
                _f_RstSbmtBtns();
                return;
            }
            
            /*
             * ETAPE :
             *  On prépare les données pour qu'elles 
             */
            //Récupération des urls
            var urls = [];
            $.each($(".jb-tqr-rcmd-frm-lst-eml"),function(x,emx){
                var em = $(emx).data("email");
                if ( $.inArray(em,urls) === -1 ) {
                    urls.push(em);
                }
            });
            var dts = {
                "sndr_fn"   : Kxlib_FstLetterCap($(".jb-form-lbl-ipt[data-field='sndr_fn']").val()),
                "sndr_eml"  : $(".jb-form-lbl-ipt[data-field='sndr_eml']").val(),
                "rcpt_fn"   : Kxlib_FstLetterCap($(".jb-form-lbl-ipt[data-field='rcpt_fn']").val()),
                "rcpt_eml"  : urls
            };
            
            /*
            Kxlib_DebugVars([JSON.stringify(dts)],true);
            return;
            //*/
            
            /*
             * Afficher la fenetre d'attente
             */
            _f_Section("_SCT_VIA_EML",false);
            _f_Section("_SCT_ERR_PNL",false);
            _f_Section("_SCT_WT_PNL",true);
            
            /*
             * ETAPE :
             *  On envoie les données au niveau du serveur.
             */
            var s = $("<span/>");
            _f_Srv_Smbt(dts.sndr_fn,dts.sndr_eml,dts.rcpt_fn,dts.rcpt_eml,s);
            
            $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                /*
                 * On affiche un message de confirmation
                 */
                alert("L'opération a été validé. Merci de nous faire confiance.");
                
                /*
                 * On affiche les fenetres
                 */
                _f_Section("_SCT_ERR_PNL",false);
                _f_Section("_SCT_WT_PNL",false);
                _f_Section("_SCT_VIA_EML",true);
                
                /*
                 * On libère le pointeur et les autres éléments
                 */
                _f_RstSbmtBtns();
                grecaptcha.reset();
                _xhr_sbmt = null;
            });
            
            $(s).on("operended",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
               /*
                * Afficher la zone d'erreur
                */
               _f_Section("_SCT_WT_PNL",false);
               _f_Section("_SCT_VIA_EML",true);
               _f_Section("_SCT_ERR_PNL",true);
               
                
                _f_RstSbmtBtns();
                grecaptcha.reset();
                /*
                 * On libère le pointeur
                 */
                _xhr_sbmt = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_ChkFlds = function () {
        try {
            
            var err = 0, erm, emb;
            $.each($(".jb-form-lbl-ipt"),function(x,f){
                var fdn = $(f).data("field"), fvl = $(f).val();
                if ( KgbLib_CheckNullity(fdn) ) {
                    err = -1;
                    return false;
                }
                fdn = fdn.toLowerCase();
                        
                switch (fdn) {
                    case "sndr_fn" :
                    case "rcpt_fn" :
                            emb = $(".jb-form-fld-err-mx[data-field='"+fdn+"']");
                            if ( KgbLib_CheckNullity(fvl) || ( fvl && !_f_Gdf().rgx_fn.test(fvl) ) ) {
//                                console.log(KgbLib_CheckNullity(fvl), fvl, !_f_Gdf().rgx_fn.test(fvl));
                                erm = "Champ invalide";
                                $(emb).find(".jb-form-fld-err").text(erm);
                                err++;

                                /*
                                 *  Dans le cas particulier où l'utilisateur est connecté, on récupère certaines données dans le DOM HTML.
                                 */
                                if ( true ) {
                                    $(emb).removeClass("this_hide");
                                }
                                //Dans le cas contraire, l'utilisateur a trafiqué le code. Dans ce cas, on en fait rien.
                            } else {
                                $(emb).addClass("this_hide");
                            }
                        break;
                    case "sndr_eml" :
                            emb = $(".jb-form-fld-err-mx[data-field='"+fdn+"']");
                            if ( fvl && !_f_Gdf().rgx_eml.test(fvl) ) {
                                erm = "Champ invalide";
                                $(emb).find(".jb-form-fld-err").text(erm);
                                $(emb).removeClass("this_hide");
                                err++;
                            } else {
                                $(emb).addClass("this_hide");
                            }
                        break;
                    case "rcpt_eml" :
                            emb = $(".jb-form-fld-err-mx[data-field='"+fdn+"']");
                            if (! $(".jb-tqr-rcmd-frm-lst-eml").length ) {
                                erm = "Vous devez ajouter au moins une adresse email valide";
                                $(emb).find(".jb-form-fld-err").data("errcd",10).text(erm);
                                $(emb).removeClass("this_hide");
                                err++;
                            } else {
                                $(emb).addClass("this_hide");
                                $(emb).find(".jb-form-fld-err").data("errcd","");
                                /*
                                 * On vérifie que toutes les adresses email rentrées sont valides
                                 */
                                var ers = 0, ers_cd;
                                $.each($(".jb-tqr-rcmd-frm-lst-eml"),function(x,emx){
                                    var em = $(emx).find(".jb-tqr-rcmd-lst-eml-addr").text();
                                    
                                    if ( !_f_Gdf().rgx_eml.test(em) ) {
                                        $(emx).addClass("error");
                                        ers_cd = 1;
                                        ers++;
                                    } else if ( $(".jb-tqr-rcmd-frm-lst-eml[data-email='"+em+"']").length > 1 ) {
                                        $(emx).addClass("error");
                                        ers_cd = 2;
                                        ers++;
                                    } else if ( $(".jb-form-lbl-ipt[data-field='sndr_eml']").val() && $(".jb-form-lbl-ipt[data-field='sndr_eml']").val() === em ) {
                                       /*
                                        * ETAPE :
                                        *  Verifier que sender_email et rcpt_email ne sont pas pareil
                                        */
                                        $(emx).addClass("error").data("errcd",3);
                                        ers_cd = 3;
                                        ers++;
                                    } else {
                                        $(emx).removeClass("error").data("errcd","");
                                    }
                                });
            
                                if ( ers ) {
                                    switch (ers_cd) {
                                        case 1 :
                                                erm = "Une ou plusieurs adresses email en rouge sont erronées";
                                            break;
                                        case 2 :
                                                erm = "Une ou plusieurs adresses email sont identiques";
                                            break;
                                        case 3 :
                                                $(emb).find(".jb-form-fld-err").data("errcd",30);
                                                erm = "Votre adresse email est identique à au moins une adresse email dans la liste";
                                            break;
                                    }
                                    
                                    $(emb).find(".jb-form-fld-err").text(erm);
                                    $(emb).removeClass("this_hide");
                                    err++;
                                } else {
                                    $(emb).addClass("this_hide");
                                }
                            }
                        break;
                    default :
                        return;
                }
                
            });
            
            return err;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_RstSbmtBtns = function () {
        try {
            
            $(".jb-tqr-rcmd-form-sbmt").data("lk",0);
            $(".jb-tqr-rcmd-scn-cfrm-chc[data-action='send']").data("lk",0);
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /**********************************************************************************************************************************************************************/
    /*************************************************************************** SERVER SCOPE *****************************************************************************/
    /**********************************************************************************************************************************************************************/
    
    
    var _Ax_Smbt = Kxlib_GetAjaxRules("RCMD_SUBMIT");
    var _f_Srv_Smbt = function(sfn,sml,rfn,rml,s) {
        if ( KgbLib_CheckNullity(sfn) | KgbLib_CheckNullity(rfn) | KgbLib_CheckNullity(rml)| KgbLib_CheckNullity(s) ) {
            return;
        }
                
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    _f_RstSbmtBtns();
                    grecaptcha.reset();
                    
                   /*
                    * Afficher la zone d'erreur
                    */
                    var em = "Erreur inattendue.";
                    _f_GnlErr(em);
                    _f_Section("_SCT_WT_PNL",false);
                    _f_Section("_SCT_VIA_EML",true);
                    _f_Section("_SCT_ERR_PNL",true);
                    _xhr_sbmt = null;
                    
                    return;
                }
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    _f_RstSbmtBtns();
                    grecaptcha.reset();
                    _xhr_sbmt = null;
                    
                   /*
                    * Afficher la zone d'erreur
                    */
                   _f_Section("_SCT_WT_PNL",false);
                   _f_Section("_SCT_VIA_EML",true);
                   _f_Section("_SCT_ERR_PNL",true);
                    
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
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou inattendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                return;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                        }
                    } 
                    return;
                } else if ( d.hasOwnProperty("return") )  {
                    /*
                     * Données attendues : 
                     *  (1) Données maj sur TIA
                     */
                     if (! KgbLib_CheckNullity(d.return) )  {
                         rds = [d.return];
                         $(s).trigger("datasready",rds);
                     } else {
                         $(s).trigger("operended");
                     }
                } else {
                    _f_RstSbmtBtns();
                    grecaptcha.reset();
                    _xhr_sbmt = null;
                    return;
                }
                
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                
                _f_RstSbmtBtns();
                grecaptcha.reset();
                
                /*
                 * Afficher la zone d'erreur
                 */
                 var em = "Erreur inattendue.";
                 _f_GnlErr(em);
                 _f_Section("_SCT_WT_PNL",false);
                 _f_Section("_SCT_VIA_EML",true);
                 _f_Section("_SCT_ERR_PNL",true);
                 
                 _xhr_sbmt = null;
                 
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
          
//            _xhr_sbmt = null;
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        var cu = document.URL;
        var toSend = {
            "urqid": _Ax_Smbt.urqid,
            "datas": {
                "sfn"    : sfn,
                "sml"    : sml,
                "rfn"    : rfn,
                "rml"    : rml,
                //g-r-r : Google-Recaptcha-Response
                "g-r-r"  : grecaptcha.getResponse(),
                "cu"     : cu 
            }
        };

        _xhr_sbmt = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_Smbt.url, wcrdtl : _Ax_Smbt.wcrdtl });
        return _xhr_sbmt;
    };
    
    
    
    /**********************************************************************************************************************************************************************/
    /**************************************************************************** VIEW SCOPE ******************************************************************************/
    /**********************************************************************************************************************************************************************/
    
    var _f_SwScrn = function () {
        try {
            
            var mx = $(".jb-tqr-rcmd-scn-center").not(".this_hide");
            if ( KgbLib_CheckNullity(mx) || !$(mx).length || $(mx).length > 1 || KgbLib_CheckNullity($(mx).data("section")) ) {
                return;
            }
            var sct = $(mx).data("section");
            switch (sct) {
                case "main" :
                        $(".jb-tqr-rcmd-scn-center[data-section='main']").addClass("this_hide");
                        $(".jb-tqr-rcmd-scn-center[data-section='sample']").removeClass("this_hide");
                    break;
                case "sample" :
                        $(".jb-tqr-rcmd-scn-center[data-section='sample']").addClass("this_hide");
                        $(".jb-tqr-rcmd-scn-center[data-section='main']").removeClass("this_hide");
                    break;
                default :
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Section = function (scp,shw) {
        try {
            
            if ( KgbLib_CheckNullity(scp) ) {
                return;
            }
             
            var sct;
            switch (scp) {
                case "_SCT_ERR_PNL" :   
                        sct = $(".jb-tqr-rcmd-scn-ctr-sect[data-section='err_panel']");
                    break;
                case "_SCT_VIA_EML" :
                        sct = $(".jb-tqr-rcmd-scn-ctr-sect[data-section='via-email']");
                    break;
                case "_SCT_WT_PNL" :
                        sct = $(".jb-tqr-rcmd-scn-ctr-sect[data-section='wait_panel']");
                    break;
                default :
                    return;
            }
            
            if (! $(sct).length ) {
                return;
            } else if ( shw === true ) {
                $(sct).removeClass("this_hide");
            } else {
                $(sct).addClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_GnlErr = function (m) {
        try {
            var dfem = "Outch ... Votre demande contient des erreurs, elle a été rejettée par le serveur.<br/>Assurez-vous de suivre les indications du formulaire puis réessayez."
            var em = ( m ) ? m : dfem;
            $(".jb-tqr-rcmd-errpnl").html(em);
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /**********************************************************************************************************************************************************************/
    /************************************************************************** LISTERNERS SCOPE **************************************************************************/
    /**********************************************************************************************************************************************************************/
    
    $(".jb-tqr-rcmd-lst-eml-rmv").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
    $(".jb-form-add-nw-eml").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
    $(".jb-tqr-rcmd-form-rst").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
    $("#tqr-rcmd-form-mx").submit(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action($(".jb-tqr-rcmd-form-sbmt"));
    });
    
    $(".jb-tqr-rcmd-scn-cfrm-chc").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
})();