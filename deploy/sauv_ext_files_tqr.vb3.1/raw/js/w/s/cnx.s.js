
function TQCNX () {
    
    var gt = this;
    var _xhr_cnx;
    
    /******************************************************************************************************************************************************************/
    /************************************************************************** PROCESS SCOPE *************************************************************************/
    /******************************************************************************************************************************************************************/
    
    var _f_Gdf = function() {
        
        var df = {
            "rgx_bdate" : /^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/,
            "bdate_min" : 12,
            "rgx_gdr"   : /^[m|f]{1}$/i,
            "rgx_psd"   : /^(?=.*[a-z])[\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20}$/i,
            "rgx_eml"   : /^[\w.+-]+@(?:[\w\d-]+\.)+[a-z]{2,6}$/i,
            "rgx_pwd"   : /^(?=(.*\d))(?=.*[a-z])(?=.*[²&<>!.?+*_~µ£^¨°\(\)\[\]\-@#$%:;=''/\\¤]).{6,32}$/i
        };
        
        return df;
    };
    
    var _f_TryConx = function () {
//    this.TryConx = function () {
        try {
            
            //On vérifie si le bouton de connexion est lock
            if ( $(".jb-cnx-submit").data("il") === 1 | !KgbLib_CheckNullity(_xhr_cnx) ) {
//                Kxlib_DebugVars([IsLocked : BTN : " + $(".jb-cnx-submit").data("il") + "; XHR : " + !KgbLib_CheckNullity(_xhr_cnx)]);
                return;
            }
            
            //On block le bouton pour éviter que plusieurs tentatives soient envoyées (presque) en même temps.
            $(".jb-cnx-submit").data("il",1);
            
            //On vérifie si les données login/Mdp sont non vides
            var ls = $(".jb-cnx-login");
            var lv = $(".jb-cnx-login").val();
            var ps = $(".jb-cnx-pass");
            var pv = $(".jb-cnx-pass").val();
            
            if ( KgbLib_CheckNullity(lv) && KgbLib_CheckNullity(pv) ) {
                return;
            }
            
            //Si au moins un champ est vide, on affiche une erreur sur ce champ. A ce stade au moins un des deux champs peut être vide.
            if ( KgbLib_CheckNullity(lv) ) {
                _f_ShwFldErr(ls);
                _f_ShwHdrErr(Kxlib_getDolphinsValue("cnx_err_one_is_void"));
                //[DEPUIS 08-05-15] 
                _f_UlkTrgBtn();
                return;
            } else if ( KgbLib_CheckNullity(pv) ) {
                _f_ShwFldErr(ps);
                _f_ShwHdrErr(Kxlib_getDolphinsValue("cnx_err_one_is_void"));
                //[DEPUIS 08-05-15] 
                _f_UlkTrgBtn();
                return;
            } else {
                _f_HidFldErr(ls);
                _f_HidFldErr(ps);
                _f_HidHdrErr();
            }
            
            /*
             * [DEPUIS 30-05-16]
             *      On TRIM la chaine de caractère
             */
            lv = lv.trim();
            
            //On fait apparaitre le spinner
            /*
             * On fait apparaitre le spinner car on considère que vérifier les login/password fait partie de la phase de tentative de connexion.
             * En effet, si les données rentrées ne coeincident pas, on considère qu'il s'agit d'une erreur.
             */
            _f_ShwSpinner();
            
            //On vérifie s'il s'agit d'un pseudo ou d'un email. On vérifie selon le type.
            var liv = ( _f_IsEmailLike(lv) ) ? _f_IsValidEmail(lv) : _f_IsValidPsd(lv);
            
            //On vérifie si le login est valide. Sinon, on signale une erreur
            if (! liv ) {
//            Kxlib_DebugVars([Failed on login"]);
                _f_HidSpinner();
                _f_ShwFldErr(ls);
                _f_ShwFldErr(ps);
                _f_ShwHdrErr(Kxlib_getDolphinsValue("cnx_err_failed"));
                //[DEPUIS 08-05-15] 
                _f_UlkTrgBtn();
                return;
            }
            
            //On vérifie si le password est valide. Sinon, on signale une erreur
            if (! _f_IsValidPwd(pv) ) {
//            Kxlib_DebugVars([Failed on password"]);
                _f_HidSpinner();
                _f_ShwFldErr(ls);
                _f_ShwFldErr(ps);
                _f_ShwHdrErr(Kxlib_getDolphinsValue("cnx_err_failed"));
                //[DEPUIS 08-05-15] 
                _f_UlkTrgBtn();
                return;
            }   
            
            /*
             * [DEPUIS 08-11-15] @author BOR
             *      On prend en compte le paramètre "Stay connected"
             *  [NOTE 08-11-15] @author BOR
             *      Abondonné car trop complexe et je n'ai plus assez de temps!
             */
//            var sc =  ( $(".jb-cnx-rmbr-ipt:checked").length ) ? true : false;
            
            //*** On lance la Tentative de connexion
            var s = $("<span/>");
            
            _f_Srv_TryCnx(lv, pv, s);
            
            $(s).on("datasready", function(e, d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//            alert(d.r);
//            return;
                //_AUTH_SUKX, _AUTH_SSM, _AUTH_FAILED, _AUTH_TD, _AUTH_WRG, _AUTH_U_G, _AUTH_LGTYP
                if ( d.r === "_AUTH_SUKX" || d.r === "_AUTH_TD" || d.r === "_AUTH_TD_PMLY" ) {
//                alert("CONNEXION ETABLIE !");
                    //On vérifie si le compte est en mode "todelete"
                    if ( d.r === "_AUTH_TD" ) {
//                    alert("CONNEXION ETABLIE !");
                        _f_ToDelCaution(true, d.utb.fn);
                    } else if ( d.r === "_AUTH_TD_PMLY" ) {
                        _f_ShwHdrErr( Kxlib_getDolphinsValue("cnx_err_tdl_permatly") );
                    } else {
                        /*
                         * [DEPUIS 30-05-16]
                         *      C'est le seruveur qui décide de la page vers laquelle il faut rediriger USER.
                         *      Pour rappel, dans le cas d'un _REDIR_AFTER_LGI, on ne redirige pas vers la page par défaut de l'utilisateur
                         */
                        var rdr_u = ( d.hasOwnProperty("rdr_afr") && d.rdr_afr && d.rdr_afr.case === "_REDIR_AFTER_LGI" && !KgbLib_CheckNullity(d.rdr_afr.url) ) 
                            ? d.rdr_afr.url : "/".concat(d.utb.psd);
                        
//                        Kxlib_DebugVars([rdr_u],true);
                        
                        //On redirige vers le compte
                        window.location.href = rdr_u;
                        return;
                    }
                } else {
//                alert("ECHEC DE CONNEXION !");
                    var em;
                    switch (d.r) {
                        case "_AUTH_WRG" :
                            em = Kxlib_getDolphinsValue("cnx_err_failed");
                            break;
                        case "_AUTH_U_G" :
                            em = Kxlib_getDolphinsValue("cnx_err_failed_ug");
                            break;
                        case "_AUTH_LGTYP" :
                            em = Kxlib_getDolphinsValue("cnx_err_failed_lg");
                            break;
                        case "_AUTH_FAILED" :
                            em = Kxlib_getDolphinsValue("cnx_err_failed");
                            if (d.et.xd === 1) {
                                em += "<br/><br/>" + Kxlib_getDolphinsValue("cnx_err_failed_ct1");
                            }
                            break;
                        case "_AUTH_SSM" :
                            var t = d.et.xd;
                            
                            var m = 60000;
                            var s = 1000;
                            
                            var M = Math.floor(t / m);
                            var R = Math.floor(t % m);
                            var S = Math.floor(R / s);
                            
                            //ReMaingTime
                            var rmt = (R === 0) ? "<b>" + M + " minutes</b>" : "<b>" + M + " : " + S + " secs </b>";
                            
//                            var_dump($M, $R, intval($R/$s));
                            em = Kxlib_getDolphinsValue("cnx_err_failed_n_lock");
                            em += "<br/><br/>" + Kxlib_getDolphinsValue("cnx_err_failed_n_lock_mr");
                            em = Kxlib_DolphinsReplaceDmd(em, "time", rmt);
                            break;
                        default:
                            Kxlib_AJAX_HandleFailed();
                            break;
                    }
                    
                    _f_ShwFldErr(ls);
                    _f_ShwFldErr(ps);
                    _f_ShwHdrErr(em);
                }
                
                //On masque le spinner
                _f_HidSpinner();
                //On unlock le bouton pour permettre de nouvelles tentatives
                _f_UlkTrgBtn();
                _xhr_cnx = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_UlkTrgBtn = function () {
        if (! $(".jb-cnx-submit").length ) {
            return;
        }
        $(".jb-cnx-submit").data("il",0);
    };
    
    var _f_IsEmailLike = function (m) {
        if ( KgbLib_CheckNullity(m) ) {
            return;
        }
            
        /*
         * Permet de vérifie si la chaine passée en paramètre ressemble à un email.
         * On ne vérifie pas s'il s'agit d'un email valide.
         * 
         * Dans notre contexte, la méthode est surtout utilisée pour faire la différence entre un emal et un pseudo.
         */
        
        var l = (m.match(/@/g) || []).length;
        /*
        if ( l === 1 ) {
            Kxlib_DebugVars([is_amail_likes"]);
            return true;
        } else {
            return false;
        }
        //*/
        return ( l === 1 ) ? true : false;
        
    };
    
    var _f_IsValidEmail = function (m) {
        if ( KgbLib_CheckNullity(m) )
            return;
        
        /*
         * Permet de vérifier si l'email est valide. 
         * 
         * Dans notre contexte, cela permet notamment d'économiser la charge au niveau du serveur.
         * En effet, ce n'est pas la peine de contacter le serveur quand on sait par avance que la tentative va échouer.
         */
        
        rgx = _f_Gdf().rgx_eml;
        return ( rgx.test(m) ) ? true : false;
    };
    
    var _f_IsValidPsd = function (m) {
        if ( KgbLib_CheckNullity(m) )
            return;
        
        /*
         * Permet de vérifier si le pseudo est valide. 
         * 
         * Dans notre contexte, cela permet notamment d'économiser la charge au niveau du serveur.
         * En effet, ce n'est pas la peine de contacter le serveur quand on sait par avance que la tentative va échouer.
         */
        
        rgx = _f_Gdf().rgx_psd;
        return ( rgx.test(m) ) ? true : false;
    };
    
    var _f_IsValidPwd = function (m) {
        if ( KgbLib_CheckNullity(m) )
            return;
        
        /*
         * Permet de vérifier si le mot-de-passe est valide. 
         * 
         * Dans notre contexte, cela permet notamment d'économiser la charge au niveau du serveur.
         * En effet, ce n'est pas la peine de contacter le serveur quand on sait par avance que la tentative va échouer.
         */
        
        rgx = _f_Gdf().rgx_pwd;
        return ( rgx.test(m) ) ? true : false;
    };
    
    var _f_ToDelAct = function (x) {
//    this.ToDelAction = function (x) {
        if ( KgbLib_CheckNullity(x) ) { return; }
        
        var a = $(x).data("action");
        
        switch (a) {
            case "cancel" :
                    _f_ToDel_Kpit();
                break;
            case "wlcmback" :
                    _f_ToDel_StopNCo();
                break;
            case "close" :
//                    this._f_ToDelCaution(false); //DEV, TEST, DEBUG
//                    _f_ToDelCaution(false);
                break;
        }
        
    };
    
    var _f_ToDel_StopNCo = function () {
        /*
         * Gère le cas où l'utilisateur décide d'annuler le processus de suppression de son compte (TODELETE) et de se connecter.
         */
        
        //Afficher la fenetre d'attente
        _f_ShwTdlWait();
        
        //Lancer la procédure d'annulation aurpès du serveur
        var s = $("<span/>");
        
        _f_Srv_ToDelAction("git",s);
        
        $(s).on("operended", function(e,d){
//            d = "loulou"; //DEV, DEBUG, TEST
//alert(d.r);
//return;
            if ( KgbLib_CheckNullity(d) )
                return;
            
            if ( d.r === "DFNTLY_TD") {
                //On cache la zone "WAIT"
                _f_HidTdlWait();
                //On affiche un message d'erreur
                _f_ShwHdrErr(Kxlib_getDolphinsValue("cnx_err_tdl_permatly"));
                
                return;
            } else if ( d.r === false ) {
                //On cache la zone "WAIT"
                _f_HidTdlWait();
                //On affiche une erreur de type UA
                Kxlib_AJAX_HandleFailed();
                
                return;
            }
            
            //Changer le message en "Redirection"
            $(".jb-cnx-tdl-w-m").text(Kxlib_getDolphinsValue("COMLG_Redir"));
            //*
            //après 2, secondes ...
            setTimeout(function(){
                //... On redirige
                var hr = "/@"+d.h;
                window.location.href = hr;
            },2000);
            //*/
        });
        
    };
    
    var _f_ToDel_Kpit = function () {
        /*
         * Gère le cas où l'utilisateur décide de ne pas se connecter et de laisser son compte en mode TODELETE.
         */
        //Afficher la fenetre d'attente
        _f_ShwTdlWait();
        
        //Lancer la procédure d'annulation de la connexion aurpès du serveur
        var s = $("<span/>");
        
        _f_Srv_ToDelAction("kpit",s);
        
        $(s).on("operended", function(e,d){
//            d = "loulou"; //DEV, DEBUG, TEST
            if ( KgbLib_CheckNullity(d) )
                return;
            
            //après 2, secondes... 
            setTimeout(function(){
                //... Masquer la fenetre d'attente
                _f_HidTdlWait();
                //On vide le formulaire
                Kxlib_ResetForm("cnx_form");
            },1000);
        });
        
    };
    
    /**********************************************************************************************************************************************************************/
    /**************************************************************************** SERVER SCOPE ****************************************************************************/
    /**********************************************************************************************************************************************************************/
    
    var _Ax_TryCnx = Kxlib_GetAjaxRules("CNX_TRYCNX");
    var _f_Srv_TryCnx = function (l,p,s) {
        if ( KgbLib_CheckNullity(l) | KgbLib_CheckNullity(p)| KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    return;
                }
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
                        switch (d.err) {
                            case "__ERR_VOL_CNX_XSTS":
                                    location.reload();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DNY_AKX":
                            case "__ERR_VOL_DENY_AKX":
                                    Kxlib_AJAX_HandleDeny();
                                break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DATAS_MISG" :
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
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    
                    //On masque le spinner
                    _f_HidSpinner();
                    //On unlock le bouton pour permettre de nouvelles tentatives
                    _f_UlkTrgBtn();
//                    $(".jb-cnx-submit").data("il",0);
                    _xhr_cnx = null;
                    
                    return;
                } else if (! KgbLib_CheckNullity(d.return) )  {
                    /*
                     * * Données attendues :
                     *  (1) .r (Résultat) : _AUTH_SUKX | _AUTH_TD | _AUTH_FAILED
                     *  //POSSIBLES
                     *  (2) .utb 
                     *      (21) .fn
                     *      (21) .psd
                     *  (3) .et (ErrorTable)
                     *      (31) .ec (ErrorCode) : Un code pour un message d'erreur ou autre
                     *      (32) .xd (eXtraDatas) : cela peut être
                     *          - Le nombre d'essais restant avant de passer en état "Shellmode"
                     *          - Le temps restant avant la fin du SM.
                     */
                    rds = [d.return];
                    $(s).trigger("datasready",rds);
                } else {
                    //On masque le spinner
                    _f_HidSpinner();
                    //On unlock le bouton pour permettre de nouvelles tentatives
                    _f_UlkTrgBtn();
//                    $(".jb-cnx-submit").data("il",0);
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                
                //On masque le spinner
                _f_HidSpinner();
                //On unlock le bouton pour permettre de nouvelles tentatives
                _f_UlkTrgBtn();
//                $(".jb-cnx-submit").data("il",0);
                _xhr_cnx = null;
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
            
            //On masque le spinner
            _f_HidSpinner();
            //On unlock le bouton pour permettre de nouvelles tentatives
            _f_UlkTrgBtn();
//            $(".jb-cnx-submit").data("il",0);
            _xhr_cnx = null;
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        var curl = document.URL;
        
//        Kxlib_DebugVars([l,p,curl],true);
        var toSend = {
            "urqid": _Ax_TryCnx.urqid,
            "datas": {
                "lv"    : l,
                "pv"    : p,
                "cl"    : curl
            }
        };

        _xhr_cnx = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_TryCnx.url, wcrdtl : _Ax_TryCnx.wcrdtl });
    };
    
    var _Ax_ToDelAction = Kxlib_GetAjaxRules("CNX_TDLACT");
    var _f_Srv_ToDelAction = function (scp,s) {
        if ( KgbLib_CheckNullity(scp) | KgbLib_CheckNullity(s) )
            return;
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else return;
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    //On cache la zone "WAIT"
                    _f_HidTdlWait();
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
                        switch (d.err) {
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                        return;
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                    return;
                                break;
                            case "__ERR_VOL_DATAS_MISG" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                    return;
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
                            case "__ERR_VOL_FATAL_UXPTD" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
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
                     * Données attendues : DONE. Si une erreur survient dans ce contexte, elle sera gérée par les méthodes ci-dessus.
                     */
                    rds = [d.return];
                    $(s).trigger("operended",rds);
                } else return;
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
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
            
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        var curl = document.URL;
        
//        Kxlib_DebugVars([l,p,curl],true);
        var toSend = {
            "urqid": _Ax_ToDelAction.urqid,
            "datas": {
                "scp":scp,
                "cl": curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_ToDelAction.url, wcrdtl : _Ax_ToDelAction.wcrdtl });
    };
    
    
    /**************************************************************************************************************************************/
    /************************************************************* VIEW SCOPE *************************************************************/
    /**************************************************************************************************************************************/
    var _f_ShwSpinner = function () {
        $(".jb-cnx-spnr").removeClass("this_hide");
    };
    
    var _f_HidSpinner = function () {
        $(".jb-cnx-spnr").addClass("this_hide");
    };
    
    var _f_ShwHdrErr = function (m) {
        if ( KgbLib_CheckNullity(m) ) {
            return;
        }
        
        /*
         * Faire apparaitre un message d'erreur dans le header. 
         */
        $(".jb-cn-hdr-mx").removeClass("std").addClass("onerror");
        $(".jb-cnx-h-txt").html(m);
    };
    
    var _f_HidHdrErr = function () {
        /*
         * Permet de réinitialiser le header afin de le remettre à son etat d'origine. 
         */
        var m = Kxlib_getDolphinsValue("cnx_err_hdr_default");
        $(".jb-cn-hdr-mx").removeClass("onerror").addClass("std");
        $(".jb-cnx-h-txt").html(m);
    };
    
    var _f_ShwFldErr = function (x) {
        if ( KgbLib_CheckNullity(x) | !$(x).length ) {
            return;
        }
        
        /*
         * Signaler visuellement une erreur sur un champs.
         * Le champ s'entoure de rouge
         */
        $(x).addClass("error_field");
    };
    
    var _f_HidFldErr = function (x) {
        if ( KgbLib_CheckNullity(x) | !$(x).length ) {
            return;
        }
        
        /*
         * Signaler visuellement une erreur sur un champs.
         * Le champ s'entoure de rouge.
         */
        $(x).removeClass("error_field");
    };
    
//    this._f_ToDelCaution = function (o,nc) {
    var _f_ToDelCaution = function (o,nc) {
        //nc = NomComplet
        
//        Kxlib_DebugVars([o,nc],true);
        try {
            
            if ((o | !$(".jb-cnx-tdl-bdy-mx").length) && !KgbLib_CheckNullity(nc)) {
                
                var m = Kxlib_getDolphinsValue("cnx_err_tdl_caution");
                m = Kxlib_DolphinsReplaceDmd(m, "nom_complet", nc);
                
                var e = "<div id=\"cnx-tdl-bdy-mx\" class=\"jb-cnx-tdl-bdy-mx\">";
                e += "<div>";
                e += "<div id=\"cnx-tdl-top\">";
                e += "<p id=\"cnx-tdl-msg\">";
                e += m;
                e += "</p>";
                e += "</div>";
                e += "<div id=\"cnx-tdl-btm\" class=\"clearfix2\">";
                e += "<div id=\"cnx-tdl-chcs-mx\">";
                e += "<a class=\"cnx-tdl-chcs cnx-tdl-chcs-ccl\" data-action=\"cancel\" href=\"\">Annuler</a>";
                e += "<a class=\"cnx-tdl-chcs cnx-tdl-chcs-git\" data-action=\"wlcmback\" href=\"\">Se connecter</a>";
                e += "</div>";
                e += "<div id=\"cnx-tdl-chcs-mr-mx\">";
                e += "<a id=\"cnx-tdl-chcs-mr\" href=\"\">Plus d'informations</a>";
                e += "</div>";
                e += "</div>";
                e += "</div>";
                e += "</div>";
                e = $.parseHTML(e);
                
                //On bind les éléments
                e = _f_ToDelC_Bind(e);
                
                //On insère l'élément
                $(e).appendTo(".jb-cnx-tdl-sprt");
                
                //On affiche le support
                $(".jb-cnx-tdl-sprt").removeClass("this_hide");
                
            } else {
                
                //On hide le support
                $(".jb-cnx-tdl-sprt").addClass("this_hide");
                
                //On supprime la zone
                if ($(".jb-cnx-tdl-bdy-mx").length) {
                    $(".jb-cnx-tdl-bdy-mx").remove();
                }
                
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_ToDelC_Bind = function (b) {
        
        if ( KgbLib_CheckNullity(b) ) {
            return;
        }
        
        $(b).find(".cnx-tdl-chcs").click(function(e){
            Kxlib_PreventDefault(e);
            _f_ToDelAct(this);
        });
        
        return b;
    };
    
    var _f_ShwTdlWait = function () {
        try {
            
            var e = "<div id=\"cnx-tdl-wait\" class=\"jb-cnx-tdl-wait\">";
            e += "<span class=\"jb-cnx-tdl-w-m\">"; 
            e += Kxlib_getDolphinsValue("COMLG_Wait");
            e += "</span>";
            e += "<span>...</span>";
            e += "</div>";
            
            
            //On masque la boite de dialogue
            $(".jb-cnx-tdl-bdy-mx").addClass("this_hide");
            
            //On affiche le message pour patienter
            $(e).hide().prependTo(".jb-cnx-tdl-sprt").fadeIn("300");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_HidTdlWait = function () {
        if ( $(".jb-cnx-tdl-wait").length ) {
            
            $(".jb-cnx-tdl-sprt").addClass("this_hide");
            $(".jb-cnx-tdl-wait").remove();
        }
    };
    
    /************************************************************************************************************************************************************************/
    /************************************************************************** LISTERNERS SCOPE ****************************************************************************/
    /************************************************************************************************************************************************************************/
    //    _Obj._f_ToDelCaution(true,"Dupont dupont");
    
    $(".jb-cnx-form-mx").submit(function(e){
        Kxlib_PreventDefault(e);
        
        _f_TryConx();
    });
    
//    $(".jb-cnx-submit").click(function(e){
//        Kxlib_PreventDefault(e);
//        
//        _Obj.TryConx();
//    });

    $(".jb-cnx-tdl-sprt").click(function(e){
        Kxlib_PreventDefault(e);
        
        var x = $("<span/>").data("action","close");
        _f_ToDelAct(x);
    });

    $(".jb-cnx-tdl-sprt *").click(function(e){
        Kxlib_StopPropagation(e);
    });
}
new TQCNX();    