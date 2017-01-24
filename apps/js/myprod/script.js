
(function(){
    
    var _xhr_plsta;
    
    /***************************************************************************************************************************************************************************************************/
    /****************************************************************************************** PROCESS SCOPE ******************************************************************************************/
    /***************************************************************************************************************************************************************************************************/
    
    var _f_PullStats = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            /*
             * On vérifie que le bouton n'est pas bloqué
             */
            
            /*
             * On vérifie qu'une opération n'est pas en cours
             */
            
            /*
             * On initialise les variables
             */
            var frm_d = $(".jb-mypd-stats-pnl-dtpkr[data-purpose='start']").val();
            var frm_t = $(".jb-mypd-sta-pnl-dtpkr-slct[data-purpose='start'] option:selected").val();
            /*
             * [RAPPEL 01-09-15] @author BOR
             *  On n'envoie que les données sur la date en cours.
             *  Envoyer une période demandrait à mettre en place un mécanisme qui affiche des variations sur un graphique.
             *  Cette fonctionnalité n'est pas disponible pour l'instant.        
             */
            
            /*
             * Si on a pas de date FROM, on prend celle de maintenant
             */
            if ( KgbLib_CheckNullity(frm_d) ) {
                //Récup
                var t__ = (new Date()).getTime();
            }
            
//            Kxlib_DebugVars([frm_d,frm_t],true);
//            return;
            
            /*
             * On lance la procédure au niveau du serveur
             */
            var s = $("<span/>");
            if ( !KgbLib_CheckNullity(frm_d) && !KgbLib_CheckNullity(frm_d) ) {
                _f_Srv_PlStaFrom(frm_d,frm_t,x,s);
            } else {
                _f_Srv_PlStaFrom_Stpmd(t__,x,s);
            }
            
            $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                /*
                 * On affiche les données
                 */
                _f_WriteDatas(d);
                
            });
            
            $(s).on("operended",function(e,d){
                $(".jb-bgsm-datarow-mx[data-type='simple']").find(".jb-bgsm-datarow-data span").text(0)
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_WriteDatas = function(ds) {
        try {
            if ( KgbLib_CheckNullity(ds) ) {
                return;
            }
            
            $.each(ds,function(x,d){
                if (d) {
                    switch (x) {
                        /* STATS ERRORS COUNT */
                        case "stats_err_gen_bgzy_cn" :
                        case "stats_err_gen_auto_cn" :
                        /* STATS ACCOUNT COUNT */
                        case "stats_gen_acc_cn" :
                        case "stats_atv_acc_cn" :
                        case "stats_ded_acc_cn" :
                        case "stats_zmb_acc_cn" :
                        /* STATS TRENDS COUNT */    
                        case "stats_gen_trd_cn" :
                        case "stats_atv_trd_cn" :
                        case "stats_ded_trd_cn" :
                        case "stats_zmb_trd_cn" :
                        /* STATS ACTIVITIES COUNT */    
                        case "stats_acty_evl_actv_cn" :
                        case "stats_acty_gen_art_cn" :
                        case "stats_acty_gen_evl_cn" :
                        case "stats_acty_gen_mi" :
                        case "stats_acty_gen_rct_cn" :
                        case "stats_acty_gen_rel_cn" :
                        case "stats_acty_mi_miorph" :
                        case "stats_acty_rel_dfol_cn" :
                        case "stats_acty_rel_frds_cn" :
                        case "stats_acty_rel_sfol_cn" :
                        case "stats_acty_rel_void_cn" :
                        case "stats_acty_ustg_art_cn" :
                        case "stats_acty_ustg_rct_cn" :
                                $(".jb-bgsm-datarow-mx[data-target='"+x+"']").find(".jb-bgsm-datarow-data span").text(d);
                            break;
                        default :
                            break;
                    }
                }
                
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_GetFromDatas = function() {
        try {
            
            return tstamp;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /***************************************************************************************************************************************************************************************************/
    /********************************************************************************************* VIEW SCOPE ******************************************************************************************/
    /***************************************************************************************************************************************************************************************************/
    
    
    /***************************************************************************************************************************************************************************************************/
    /******************************************************************************************** SERVER SCOPE *****************************************************************************************/
    /***************************************************************************************************************************************************************************************************/
    
    var _Ax_PlStaFrom = Kxlib_GetAjaxRules("TQR_STATS_PNL_PL_STATS");
    var _f_Srv_PlStaFrom = function (frm_d,frm_t,x,s) {
        try {
            if ( KgbLib_CheckNullity(frm_d) | KgbLib_CheckNullity(frm_t) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
                return;
            }
            
            var onsuccess = function (datas) {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
                if (! KgbLib_CheckNullity(datas.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_FAILED":
                                break;
                            default:
                                break;
                        }
                    }
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var datas = [datas.return];
                    $(s).trigger("datasready",datas);
                } else {
                    $(s).trigger("operended");
                }
            };

            var onerror = function(a,b,c) {
                Kxlib_AjaxGblOnErr(a,b);
                return;
            };

            var toSend = {
                "urqid": _Ax_PlStaFrom.urqid,
                "datas": {
                    "fd" : frm_d,
                    "ft" : frm_t
                }
            };

            _xhr_plsta = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlStaFrom.url, wcrdtl : _Ax_PlStaFrom.wcrdtl });

            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
    };
    
    var _Ax_PlStaFrom_Stpmd = Kxlib_GetAjaxRules("TQR_STATS_PNL_PL_STATS_TMSTP");
    var _f_Srv_PlStaFrom_Stpmd = function (tm,x,s) {
        try {
            if ( KgbLib_CheckNullity(tm) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
                return;
            }
            
            var onsuccess = function (datas) {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
                if (! KgbLib_CheckNullity(datas.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_FAILED":
                                break;
                            default:
                                break;
                        }
                    }
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var datas = [datas.return];
                    $(s).trigger("datasready",datas);
                } else {
                    $(s).trigger("operended");
                }
            };

            var onerror = function(a,b,c) {
                Kxlib_AjaxGblOnErr(a,b);
                return;
            };

            var toSend = {
                "urqid": _Ax_PlStaFrom_Stpmd.urqid,
                "datas": {
                    "tm" : tm
                }
            };

            _xhr_plsta = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlStaFrom_Stpmd.url, wcrdtl : _Ax_PlStaFrom_Stpmd.wcrdtl });

            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
    };
    
    
    /***************************************************************************************************************************************************************************************************/
    /***************************************************************************************** LISTENERS SCOPE *****************************************************************************************/
    /***************************************************************************************************************************************************************************************************/
    
    $(".jb-mypd-stats-pnl-dtpkr").datepicker($.datepicker.regional["fr"]);
    
    $(".jb-launch").click(function(e){
        Kxlib_PreventDefault();
        
        _f_PullStats(this);
    });
})();
