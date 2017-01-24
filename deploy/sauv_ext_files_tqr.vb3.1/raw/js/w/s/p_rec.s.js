/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function RECOVERY_MASTER () {
    var gth = this;
    
    var _f_Gdf = function () {
        var df = {
            //Email
            "rgx_eml"       : /^[\w.+-]+@(?:[\w\d-]+\.)+[a-z]{2,6}$/i,
            //Code de validation
            "rgx_code"      : /^[a-z\d]{6}$/i,
            //Mot de passe
            "rgx_pwd"       : /^(?=(.*\d))(?=.*[a-z])(?=.*[²&<>!.?+*_~µ£^¨°\(\)\[\]\-@#$%:;=''/\\¤]).{6,32}$/i,
            "rgx_pwd_ltr"   : /[a-z]+/i,
            "rgx_pwd_spch"  : /([²&<>!.?+*_~µ£^¨°\(\)\[\]\-@#$%:;=''/\\¤])/g,
//            "rgx_pwd_bspch": /[:;=''/\\¤]+/,
            "rgx_pwd_dig"   : /([0-9])/g,
            "rgx_pwd_case"  : /([A-Z])/g,
            //np => NotPerfect : Si le password a tout pour être excellent mais que les mots ci_desssous si trouve, il ne le sera pas
            "rgx_pwd_np"    : /(toto|azerty|0000|1234|qwerty|letmein|password)/ig,
            "pwd_min"       : 6,
            "pwd_max"       : 32,
            //PassWaitingTime
            "pwt"           : 200
        };

        return df;
    };
    
    /**********************************************************************************************************************************/
    /********************************************************* PROCESS SCOPE **********************************************************/
    /**********************************************************************************************************************************/
    
    this.Submit_RecL = function () {
        
        //On vérifie si le bouton est lock
        if ( $(".jb-rec-form-submit").data("lk") === 1 ) {
            return;
        }
        
        //On lock le bouton
        $(".jb-rec-form-submit").data("lk",1);
        
        //On affiche le spinner
        _f_ShwSpinner("REC_L");
        
        //On vérifie que les champs sont valides
        var ckf = _f_CheckEmlField();
        
        if (! ckf ) {
            var m;
            if ( ckf === 0 ) {
                m = Kxlib_getDolphinsValue("rec_email_void");
            } else {
                m = Kxlib_getDolphinsValue("rec_email_noeml");
            }
            //On affiche l'erreur au niveau du champ
            _f_ShwErrorField(".jb-rec-form-email");
            //On affiche la phrase d'erreur
            _f_ShwErrHdr("REC_L",m);
            
            //On masque le spinner
            _f_HidSpinner("REC_L");
            
            //On unlock le bouton
            $(".jb-rec-form-submit").data("lk",0);
            
            return;
        } else {
            //On retire les marqueures d'erreur
            _f_HidErrorField(".jb-rec-form-email");
            //On retire la phrase d'erreur
            _f_HidErrHdr("REC_L");
        }
        
        //On contacte le serveur pour lancer la procédure de réinitialisation du mot de passe
        var s = $("<span/>"), em = $(".jb-rec-form-email").val();
        
        _f_Srv_Recovery(em,s);
        
        $(s).on("operended", function(e,d) {
            if ( KgbLib_CheckNullity(d) )
                return;
            
            //On masque le spinner
            _f_HidSpinner("REC_L");
            
            var m = "", ie = false;
            switch (d) {
                case "_REC_L_WRG_DATAS":
                        m = Kxlib_getDolphinsValue("rec_email_wrg_by_srv");
                        ie = true;
                    break;
                case "_REC_L_UKNW_EML":
                        m = Kxlib_getDolphinsValue("rec_email_uknw");
                        ie = true;
                    break;
                case "_REC_L_U_GONE":
                        m = Kxlib_getDolphinsValue("rec_email_ugone");
                        ie = true;
                    break;
                case "_REC_L_ALDY_TD":
                        m = Kxlib_getDolphinsValue("rec_email_aldy_td");
                        ie = true;
                    break;
            }
            
            if ( ie ) {
                _f_ShwErrHdr("REC_L",m);
            } else {
                //On fait apparaittre la deuxième zone
                _f_ShwFinalView("REC_L",em);
            }
            
            //On unlock le bouton
            $(".jb-rec-form-submit").data("lk",0);
        });
        
        return;
    };
    
    this.Submit_RecF = function () {
        /*
         * Permet de valider définitivement le formulaire et de lancer le processus auprès du serveur.
         */
        
        //On vérifie que le bouton est disponible
        if ( $(".jb-recch-submit").data("lk") === 1 ) {
            return;
        }
        
        //On lock le bouton
        $(".jb-recch-submit").data("lk",1);
        //On fait apparaitre le spinner
        _f_ShwSpinner("REC_F"); 
            
        /*
         * On vérifie que les champs sont valides.
         * Les champs qui ne sont pas valides seront signalés à l'utilisateur
         */
        var r = _f_CheckFileds_RecF();
        if ( r && r !== -1 ) {
            //On retire le messge d'erreur s'il existe
            $(".jb-recch-err").text("");
            
            /*********** ON LANCE L'OPERATION COTE SERVEUR ************/
            
            var s = $("<span/>"), p = $(".jb-recch-pwd-1st").val(), c = $(".jb-recch-code").val();
            _f_Srv_Recovery_F(p,c,s);
            
            //On retire les marqueurs de validation en attendant la réponse du servueur
            _f_HidValidField(".jb-recch-input");
            $(s).on("operended",function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    //On unlock le bouton
                    $(".jb-recch-submit").data("lk",0);
                    //On masque le spinner
                    _f_HidSpinner("REC_F"); 
                    
                    return;
                }
                
                if ( d.toUpperCase() !== "DONE" ) {
                    var em = "";
                    //IsFaltalError
                    var ife = false;
                    switch (d.toUpperCase()) {
                        case "__ERR_VOL_UG" :
                                em = Kxlib_getDolphinsValue("recch_err_ug");
                                ife = true;
                            break;
                        case "__ERR_VOL_UKNW" :
                                em = Kxlib_getDolphinsValue("recch_err_uknw_oper");
                                ife = true;
                            break;
                        case "__ERR_VOL_EXP" :
                                em = Kxlib_getDolphinsValue("recch_err_exp_oper");
                                ife = true;
                            break;
                        case "__ERR_VOL_CCL" :
                                em = Kxlib_getDolphinsValue("recch_err_ccl_oper");
                                ife = true;
                            break;
                        case "__ERR_VOL_INVLD" :
                                em = Kxlib_getDolphinsValue("recch_err_invld");
                                ife = true;
                            break;
                        case "__ERR_VOL_OBSLT" :
                                em = Kxlib_getDolphinsValue("recch_err_obslt");
                                ife = true;
                            break;
                        case "_REC_WC" : 
                                em = Kxlib_getDolphinsValue("recch_err_badcode");
                            break;
                        case "_REC_BC" :
                                em = Kxlib_getDolphinsValue("recch_err_badcode");
                            break;
                        case "_REC_WP" :
                                em = Kxlib_getDolphinsValue("recch_err_badpwd");
                            break;
                        default:
                                Kxlib_AJAX_HandleFailed();
                                //On unlock le bouton
                                $(".jb-recch-submit").data("lk",0);
                                //On masque le spinner
                                _f_HidSpinner("REC_F"); 
                            break;
                    }
                    
                    if ( ife === true ) {
                        _f_ShwFatalErr_Recch(em);
                    } else {
                        //On retire le messge d'erreur s'il existe
                        $(".jb-recch-err").text(em);
                        //On vide le formulaire
                        Kxlib_ResetForm("recch_form");
                        //On reset l'indicateur de validité du mot de passe
                        $(".jb-pwd-strength").stop(true,true).animate({
                            "width": "0%"
                        }).removeAttr("title"); 
                    }
                    
                } else {
                    _f_ShwFinalView_Recch();
                }
                
                //On unlock le bouton
                $(".jb-recch-submit").data("lk",0);
                
                //On masque le spinner
                _f_HidSpinner("REC_F"); 
            });
            
        } else if ( r === -1 ) {
            $(".jb-recch-err").text(Kxlib_getDolphinsValue("recch_dom_hack"));
            //On masque le spinner
            _f_HidSpinner("REC_F"); 
            //On unlock le bouton
            $(".jb-recch-submit").data("lk",0);
        } else{
            $(".jb-recch-err").text(Kxlib_getDolphinsValue("recch_fe_wrg_datas"));
            //On masque le spinner
            _f_HidSpinner("REC_F"); 
            //On unlock le bouton
            $(".jb-recch-submit").data("lk",0);
        }
        
    };
    
    var _f_CheckFileds_RecF = function () {
        
        var ipt = $(".jb-recch-input");
        //On vérifie qu'on a bien 3 entrées
        if ( ipt.length !== 3 ) {
            return -1;
        }
        
        //ec = ErrorCount
        var ec = 0;
        /*
        * Permet de s'assurer que les trois éléments sont bien présents. 
        * Ce genre de vérification permet de résister à des modifications sur le DOM de la part de l'utilisateur.
        */
        var tpl = 0;
        $.each(ipt,function(x,e){
            var i = $(e).attr("id");
            var v = $(e).val();
//            Kxlib_DebugVars([]);
            switch (i) {
                case "recch_passwd" :
                        if ( KgbLib_CheckNullity(v) || !_f_Gdf().rgx_pwd.test(v) ) {
                            _f_ShwErrorField(e);
                            _f_HidValidField(e);
                            ++ec;
                        } else {
                            _f_HidErrorField(e);
                            _f_ShwValidField(e);
                        }
                        ++tpl;
                    break;
                case "recch_passwd_conf" :
                        if ( _f_CheckPwdConf() !== true ) {
                            _f_ShwErrorField(e);
                            _f_HidValidField(e);
                            ++ec;
                        } else {
                             _f_HidErrorField(e);
                             _f_ShwValidField(e);
                        }
                        ++tpl;
                    break;
                case "recch-code" :
                        if ( KgbLib_CheckNullity(v) || !_f_Gdf().rgx_code.test(v) ) {
                            _f_ShwErrorField(e);
                            _f_HidValidField(e);
                            ++ec;
                        } else {
                            _f_HidErrorField(e);
                            _f_ShwValidField(e);
                        }
                        ++tpl;
                    break;
                default :
                        //L'erreur vient de l'utilisateur
                        return -1;
                    break;
            }
        });
        
        if ( tpl !== 3 ) {
            return -1;
        } else {
            return ( !ec ) ? true : false;
        }
        
    };
    
    var _f_CheckEmlField = function () {
        /*
         * Permet de vérifier si le champ est valide.
         */
        
        var m = $(".jb-rec-form-email").val();
        if ( KgbLib_CheckNullity(m) ) {
            //On signale visuellement l'erreur
            _f_ShwErrField($(".jb-rec-form-email"));
        
            return 0;
        } else {
            //On vérifie que le format de l'email est correct
            return ( _f_Gdf().rgx_eml.test(m) ) ? true : false;
        }
        
    };
    
    var pwto;
    this.PwdCatch = function(x) {
        if ( KgbLib_CheckNullity(x) )
            return;
        
        if ( pwto ) {
            clearTimeout(pwto);
        }
        
        //QueryText
        var pwqt = $(x).val();
        var wt = _f_Gdf().pwt;
        //On reset le "SelectedElement" pour que le formulaire ne soit pas validé
//        $(".jb-ins-city-ipt").data("se","");
        //On retire le marqueur "valid"
//        _f_IsRowVald("ctysrh");
        
//        Kxlib_DebugVars([qt.length >= _f_Gdf().cysrh_min],true);
//        Kxlib_DebugVars([wi,qt,_f_Gdf().cysrh_min],true);
//        return;
        var iv = true;
        if ( pwqt.length ) {
            
            pwto = setTimeout(function() {
                
//                Kxlib_DebugVars([CAR. SPE. => "+(pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length]);
//                Kxlib_DebugVars([DIGIT. => "+(pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length]);
//                Kxlib_DebugVars([UPPER CASE. => "+(pwqt.match(_f_Gdf().rgx_pwd_case) || []).length]);
//                Kxlib_DebugVars([INTERDIT => "+(pwqt.match(_f_Gdf().rgx_pwd_np) || []).length]);
//                Kxlib_DebugVars([TOTAL => "+pwqt.length]);
//                Kxlib_DebugVars();
                if ( pwqt.length < _f_Gdf().pwd_min ) {
                    _f_InsPwdLevel(1);
                    iv = false;
                } else if ( pwqt.length > _f_Gdf().pwd_max ) {
                    _f_InsPwdLevel(6);
                    iv = false;
                } else if (! _f_Gdf().rgx_pwd.test(pwqt) ) {
                    _f_InsPwdLevel(1);
                    iv = false;
                } else if ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length === 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length === 1 && pwqt.length < 8 ) {
                    /*
                     * (Peu mieux faire) 
                     * Respecte les conditions mais il n'y a qu'un chiffre et un caractère spéciale et la longueur est < 8
                     */
                    _f_InsPwdLevel(2);
                } else if ( 
                            ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length === 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length === 1 && pwqt.length >= 8 && pwqt.length <= 16) ||
                            ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && ( ( (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length === 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1 ) || (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length === 1 ) && pwqt.length <= 10 )
                        ) {
                    /*
                     * (Encore un effort) 
                     * Respecte les conditions mais il n'y a qu'un chiffre et un caractère spéciale et la longueur est > 8
                     * Respecte les conditions avec au moins deux indicateurs sup. pour l'un OU l'autre. Exemple toto1*+ OU toto23* MAIS la longueur <= 10
                     */
                    _f_InsPwdLevel(3);
                } else if ( 
                       ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length === 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length === 1 && pwqt.length > 16 ) ||
                       ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1 && !(pwqt.match(_f_Gdf().rgx_pwd_case) || []).length  && pwqt.length >= 8 ) ||
                       ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_case) || []).length > 1 && pwqt.length >= 12 && (pwqt.match(_f_Gdf().rgx_pwd_np) || []).length ) ||
                       ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && ( ( (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length === 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1 ) || (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length === 1 ) && pwqt.length > 10 )
                )
                {
                    /*
                     * (Très bien) 
                     * Respecte les conditions avec au moins deux indicateurs sup. pour l'un OU l'autre. Exemple toto1*+ OU toto23* MAIS la longueur > 10
                     * Respecte les conditions avec au moins deux indicateurs sup. pour chaque. Exemple toto12*+ OU toto23+* ET la longueur >= 10
                     */
                    _f_InsPwdLevel(4);
                } else if ( 
                        pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1 && pwqt.length >= 12 
                        && (pwqt.match(_f_Gdf().rgx_pwd_case) || []).length > 1 && !(pwqt.match(_f_Gdf().rgx_pwd_np) || []).length
                        ) {
                    /*
                     * (Excellent) 
                     * Respecte les conditions avec au moins deux indicateurs sup. pour chaque. Exemple azerty12*+ OU azerty23+* ET la longueur > 8
                     * ET
                     * Au moins un caractère UPPERCASE [A-Z]
                     * 
                     * (Amelioration)
                     * Il faut que l'utilisateur n'utilise pas un mot provenant de son pseudo ou de son nom.
                     * L'utilisateur n'utilise pas des combinaisons connues telles : 1234, toto, azerty
                     */
                    _f_InsPwdLevel(5);
                }
                
                if ( iv ) {
                    //On retire le marqueur d'erreur visuel
                    _f_HidErrorField(x);
                    
                    var s_ = $(".jb-recch-pwd-2nd").val();
                    if ( _f_Gdf().rgx_pwd.test(s_) && s_ === pwqt ) {
                        //On ajoute le marqueur de validation visuel
                        _f_ShwValidField(".jb-recch-pwd-input");
                    }
                } else {
                    //On retire le marqueur de validation visuel
                    _f_HidValidField(".jb-recch-pwd-input");
                }
                
            },wt);
        } else {
            //On reinitialise la barre de strength
            _f_InsPwdLevel(0);
            //On retire le marqueur de validation visuel
            _f_HidValidField(x);
        }
        
    };
    
    this.PwdConfBlur = function (x) {
        
        if ( KgbLib_CheckNullity(x) )
            return;
        
         var iv = _f_CheckPwdConf();
//         Kxlib_DebugVars([v]);
         if ( iv === true ) {
             _f_HidErrorField(".jb-recch-pwd-input");
             _f_ShwValidField(".jb-recch-pwd-input");
         } else {
             //On vérifie le code d'erreur
             switch (iv) {
                 case "_VOID":
                 case "_BOTH_WRG_DATAS":
                 case "_REF_MSG":
                 case "_REF_WRG":
                        //Ne rien faire
                     break;
                 case "_WRG_DATAS":
                    //On retire le marqueur visuel "valid"
                    _f_HidValidField(".jb-recch-pwd-input");
                 case "_WRG_DATAS":
                 case "_REF_MSM":
                        //On retire le marqueur visuel "valid"
                        _f_HidValidField(".jb-recch-pwd-input");
//                        //Faux sur confirm
//                        _f_HidValidField(".jb-recch-pwd-2nd");
                     break;
             }
         }
        
        var val = $(x).val(), iv = false;
        if ( KgbLib_CheckNullity(val) && _f_Gdf().rgx_pwd.test(val) ) {
            iv = true;
        } 
        
    };
    
    var _f_CheckPwdConf = function() {
        /*
         * Vérifie si le champ est valide.
         * Le champ de confirmation est valide si son compte est de type "password" et qu'il est identique au champ de référence.
         * La méthode renvoie TRUE ou un code d'erreur qui correspond au "probleme". 
         */
        var val = $(".jb-recch-pwd-2nd").val();
        var refv = $(".jb-recch-pwd-1st").val();
        if ( KgbLib_CheckNullity(val) ) {
            return "_VOID";
        } else if ( val && KgbLib_CheckNullity(refv) ) {
            return "_REF_MSG";
        } else if ( !_f_Gdf().rgx_pwd.test(val) && !_f_Gdf().rgx_pwd.test(refv) ) {
            return "_BOTH_WRG_DATAS";
        } else if ( !_f_Gdf().rgx_pwd.test(val) && _f_Gdf().rgx_pwd.test(refv) ) {
            return "_WRG_DATAS";
        } else if ( ( val !== refv ) || ( _f_Gdf().rgx_pwd.test(val) && _f_Gdf().rgx_pwd.test(refv) && val !== refv ) ) {
            return "_REF_MSM";
        } else if ( _f_Gdf().rgx_pwd.test(val) && !_f_Gdf().rgx_pwd.test(refv) ) {
            return "_REF_WRG";
        } else {
            return true;
        } 
            
    };
    
    /**********************************************************************************************************************************/
    /********************************************************* SERVER SCOPE ***********************************************************/
    /**********************************************************************************************************************************/
    var _Ax_Recovery_L = Kxlib_GetAjaxRules("TQR_REC_L");
    var  _f_Srv_Recovery = function (em,s){
        if ( KgbLib_CheckNullity(em) | KgbLib_CheckNullity(s) ) {
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
                    _f_HidSpinner("REC_L");        
                    //On unlock le bouton
                    $(".jb-rec-form-submit").data("lk",0);
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
                        switch (d.err) {
                            case "__ERR_VOL_DENY":
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
                                return;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) )  {
                    /*
                     * Données attendues : 
                     *  DONE | (ERROR STRING)
                     */
                    rds = [d.return];
                    $(s).trigger("operended",rds);
                } else return;
                
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([e],true);
                _f_HidSpinner("REC_L");
                //On unlock le bouton
                $(".jb-rec-form-submit").data("lk",0);
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
            _f_HidSpinner("REC_L");
            //On unlock le bouton
            $(".jb-rec-form-submit").data("lk",0);
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        var curl = document.URL;
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        
        var toSend = {
            "urqid": _Ax_Recovery_L.urqid,
            "datas": {
                "curl":curl,
                "eml": em
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_Recovery_L.url, wcrdtl : _Ax_Recovery_L.wcrdtl });
    };
    
    
    var _Ax_Recovery_F = Kxlib_GetAjaxRules("TQR_REC_F");
    var  _f_Srv_Recovery_F = function (p,c,s){
        if ( KgbLib_CheckNullity(p) | KgbLib_CheckNullity(c) | KgbLib_CheckNullity(s) )
            return;
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else return;
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    _f_HidSpinner("REC_F");    
                    $(".jb-recch-submit").data("lk",0);
                    
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
                                     * La plupart du temps, il s'agit de données dites 'system'.
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
                     *  DONE | (ERROR STRING)
                     */
                    rds = [d.return];
                    $(s).trigger("operended",rds);
                } else {
                    //On unlock le bouton
                    $(".jb-recch-submit").data("lk",0);
                    //On masque le spinner
                    _f_HidSpinner("REC_F"); 
                    
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex],true);
                _f_HidSpinner("REC_F");
                //On unlock le bouton
                $(".jb-recch-submit").data("lk",0);
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
            _f_HidSpinner("REC_F");
            //On unlock le bouton
            $(".jb-recch-submit").data("lk",0);
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        var curl = document.URL;
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        
        var toSend = {
            "urqid": _Ax_Recovery_F.urqid,
            "datas": {
                "p": p,
                "c": c,
                "curl":curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_Recovery_F.url, wcrdtl : _Ax_Recovery_F.wcrdtl });
    };
    
    /**********************************************************************************************************************************/
    /*********************************************************** VIEW SCOPE ***********************************************************/
    /**********************************************************************************************************************************/
    
    var _f_ShwErrHdr = function (scp,m) {
        if ( KgbLib_CheckNullity(scp) | KgbLib_CheckNullity(m) )
            return;
        
        if ( scp === "REC_L")
            $(".jb-rec-form-err").text(m);
    };
    
    var _f_HidErrHdr = function (scp) {
        if ( KgbLib_CheckNullity(scp) )
            return;
        
        if ( scp === "REC_L")
            $(".jb-rec-form-err").text("");
    };
    
    var _f_ShwSpinner = function (scp) {
        if ( KgbLib_CheckNullity(scp) )
            return;
        
        if ( scp === "REC_L")
            $(".jb-rec-spnr").removeClass("this_hide");
        else if ( scp === "REC_F")
            $(".jb-recch-spnr").removeClass("this_hide");
    };
    
    var _f_HidSpinner = function (scp) {
        if ( KgbLib_CheckNullity(scp) )
            return;
        
        if ( scp === "REC_L")
            $(".jb-rec-spnr").addClass("this_hide");
        else if ( scp === "REC_F")
            $(".jb-recch-spnr").addClass("this_hide");
    };
    
    var _f_ShwFinalView = function(scp,em) {
        if ( KgbLib_CheckNullity(scp) | KgbLib_CheckNullity(em) )
            return;
        
        if ( scp === "REC_L" ) {
            //On masque la vue de travail
            $(".jb-rec-content").addClass("this_hide");
            
            //On affiche la vue finale
            $(".jb-rec-content-final").removeClass("this_hide");
            
            //********** On procède à l'animation **************
            var t__ = em.split("@");
            var dom = t__[1];
            
            //On insère les données
            $(".jb-rec-gomail").text(dom);
            $(".jb-rec-mailredir").attr("href","http://www."+dom);
            
            var w = $(".jb-rec-mailredir").width();
            var left = 476-w;
            
            setTimeout(function(){
                $(".jb-rec-mv").animate({
                    "padding-left": left,
                    easing: "swing"
                },1150);
            },1000);
            
        }
        
    };
    
    var _f_InsPwdLevel = function(gl) {
        //GivenLevel
        if ( KgbLib_CheckNullity(gl) )
            return;
        
        /*
         * w: Width
         * clr: Color
         * ci: Code Infos
         */
        var w, clr,ci;
        switch (gl) {
            case 0 :
                    w = 0;
                    clr = null;
                break;
            case 1 :
                    w = "20%";
                    clr = "#f00";
                    ci = "ins_infos_pwd_notvl";
                break;
            case 2 :
                    w = "40%";
                    clr = "#ff6223";
                    ci = "ins_infos_pwd_cdb";
                break;
            case 3 :
                    w = "60%";
                    clr = "#b7f121";
                    ci = "ins_infos_pwd_notbd";
                break;
            case 4 :
                    w = "80%";
                    clr = "#00b33d";
                    ci = "ins_infos_pwd_good";
                break;
            case 5 :
                    w = "100%";
                    clr = "#00b33d";
                    ci = "ins_infos_pwd_xclt";
                break;
            case 6 :
                    //Le cas où le mot de passe est trop long
                    w = "100%";
                    clr = "#f00";
                    ci = "ins_infos_pwd_notvl";
                break;
            default :
                break;
        }
        
        if ( !w | !clr ) {
            $(".jb-pwd-strength").stop(true,true).animate({
                "width": "0%"
            }); 
            
            $(".jb-pwd-strength").removeAttr("title");
        } else {
            $(".jb-pwd-strength").stop(true,true).animate({
                "width": w,
                "background-color": clr
            });
            
            var mi = Kxlib_getDolphinsValue(ci);
            $(".jb-pwd-strength").attr({
                "title": mi
            });
        }
            
    };
    
    var _f_ShwValidField = function (x) {
        if ( KgbLib_CheckNullity(x) ) 
            return;
        
        $(x).addClass("recch-error-valid",250);
    };
    
    var _f_HidValidField = function (x) {
        if ( KgbLib_CheckNullity(x) ) 
            return;
        
        $(x).removeClass("recch-error-valid",250);
    };
    
    var _f_ShwErrorField = function (x) {
        if ( KgbLib_CheckNullity(x) ) 
            return;
        
        $(x).addClass("error_field",100);
    };
    
    var _f_HidErrorField = function (x) {
        if ( KgbLib_CheckNullity(x) ) 
            return;
        
        $(x).removeClass("error_field",100);
    };
    
    var _f_ShwFatalErr_Recch = function (m) {
        if ( KgbLib_CheckNullity(m) ) 
            return;
        
        var e = $("<div/>");
        $(e).attr({
            "id": "rec-s-mj-err",
            "class": "jb-s-mj-err"
        }).text(m);

        //Cacher la zone principale
        $(".jb-recch-content").addClass("this_hide");
        //Faire apparaitre la zone d'erreur
        $(e).insertBefore(".jb-recch-content");
        
        //Pour des raisons des sécurité et de fiabiloté, on retire l'élément du DOM
        $(".jb-recch-content").remove();
    };
    
    var _f_ShwFinalView_Recch = function () {
        
        var mx = $("<div/>");
        var bd = $("<div/>");
        var pt = $("<p/>");
        var pb = $("<p/>");
        
        
        $(pb).attr({
            "id": "recch-f-b-txt-b",
            "class": "jb-recch-f-b-txt-b"
        }).html(Kxlib_getDolphinsValue("recch_final_sukx_mr"));
        
        $(pt).attr({
            "id": "recch-f-b-txt-t",
            "class": "jb-recch-f-b-txt-t"
        }).text(Kxlib_getDolphinsValue("recch_final_sukx"));
        
        $(bd).attr({
            "id": "recch-f-body",
            "class": "jb-recch-f-body"
        }).append(pt,pb);
        
        $(mx).attr({
            "id": "recch-final-mx",
            "class": "jb-recch-final-mx"
        }).append(bd);

        //Cacher la zone principale
        $(".jb-recch-content").addClass("this_hide");
        //Faire apparaitre la zone d'erreur
        $(mx).insertBefore(".jb-recch-content");
        
        //Pour des raisons des sécurité et de fiabiloté, on retire l'élément du DOM
        $(".jb-recch-content").remove();
    };
}

(function(){
    var RM = new RECOVERY_MASTER();
    
    $(".jb-rec-form").submit(function(e) {
        Kxlib_PreventDefault(e);
        
        RM.Submit_RecL();
    });
    
    $(".jb-recch-pwd-1st").keyup(function(e) {
        
        RM.PwdCatch(this);
    });
    
    $(".jb-recch-pwd-2nd").blur(function(){
        RM.PwdConfBlur(this);
    });
    
    $(".jb-recch-form").submit(function(e) {
        Kxlib_PreventDefault(e);
        
        RM.Submit_RecF();
    });
    
})();