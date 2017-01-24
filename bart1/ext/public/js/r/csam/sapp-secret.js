
define("sapp/sapp-secret", function () {
    return function(){
        
        var _xhr_addmm, _xhr_delmm, _xhr_evlmm, _xhr_plmms;
        
        /*******************************************************************************************************************************************************************/
        /**************************************************************************** PROCESS SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/

        var _f_Gdf = function () {
            var dt = {
                "dftsc" : "SEC_TQR",
                "dftfl" : "BY_DATE"
            }; 

            return dt;
        };
        
        var _f_Init = function () {
            try {
                
                /*
                 * ETAPE :
                 *      On RENDER certains textes. 
                 */
                /*
                var txts = $(".jb-tqr-dscvr-scrt-a-l-nne-mx");
                $.each(txts,function(i,txz){
                    var txt = $(txz).text();
                    var rtxt = Kxlib_TextEmpow(txt,null,null,null,{
                        emoji : {
                            "size"          : 36,
                            "size_css"      : 22,
                            "position_y"    : 3
                        }
                    });
                    $(txz).text("").html(rtxt);
                });
                //*/
                
                _f_Autoload();
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };

        var _f_Action = function (x,a){
            try {
                if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity(a) && KgbLib_CheckNullity($(x).data("action")) ) ) {
                    return;
                }

                var _a = ( KgbLib_CheckNullity(a) ) ? $(x).data("action") : a;
                switch (_a) {
                    case "mysm-filtered" :
                            _f_Filtered(x,_a);
                        break;
                    case "mysm-add" :
                            _f_AddA(x,_a);
                        break;
                    case "eval-plus" :
                    case "eval-minus" :
                            _f_Rate(x,_a);
                        break;
                    case "art-del-start" :
                    case "art-del-y" :
                    case "art-del-n" :
                            _f_Dela(x,_a);
                        break;
                    case "load-more" :
                            _f_LdMr(x,_a);
                        break;
                    default:
                        return;
                }
                
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Autoload = function () {
            try {
                
                var $mms = $(".jb-tqr-dscvr-scrt-art-lsts").find(".jb-tqr-dscvr-sct-a-bmx");
                if (! $mms.length ) {
                    var prm = {
                        sc : _f_Gdf().dftsc,
                        dr : "FST",
                        fl : _f_Gdf().dftfl,
                        pi : "",
                        pt : ""
                    };
                    
//                    Kxlib_DebugVars([JSON.stringify(prm)],true);
//                    return;

                    $mms.remove();
                    
                    _f_Mystery("autoload",prm.sc,prm.dr,prm.fl,prm.pi,prm.pt);
                }
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Filtered = function (x,a) {
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                $(x).data("lk",1);
                
                /*
                 * ETAPE :
                 *      L'Opération n'est pas compatible avec les opérations ci-dessous
                 */
                if ( !KgbLib_CheckNullity(_xhr_addmm) | !KgbLib_CheckNullity(_xhr_delmm) | !KgbLib_CheckNullity(_xhr_evlmm) | !KgbLib_CheckNullity(_xhr_plmms) ) {
                    $(x).data("lk",0);
                    return;
                }
                
                var $mms = $(".jb-tqr-dscvr-scrt-art-lsts").find(".jb-tqr-dscvr-sct-a-bmx");
                
                $(".jb-tqr-dscvr-scrt-fil-mx").prop("disabled",true);
                var filprd = $(".jb-tqr-dscvr-scrt-fil-mx[data-action='mysm-fil-period'] option:selected").val();
                var filspec = $(".jb-tqr-dscvr-scrt-fil-mx[data-action='mysm-fil-spec'] option:selected").val();
                
//                Kxlib_DebugVars([filprd,filspec],true);
//                return;

                var fil = ( filprd === "DEFAULT" ) ? filspec : filprd.concat("_",filspec);
                
                var prm = {
                    sc : _f_Gdf().dftsc,
                    dr : "FST",
                    fl : fil,
                    pi : "",
                    pt : ""
                };
                    
//                    Kxlib_DebugVars([JSON.stringify(prm)],true);
//                    return;

                /*
                 * ETAPE :
                 *      On supprime tous les ARTICLES déjà présents
                 */
                $mms.remove();
                
                /*
                 * ETAPE :
                 *      Dans tous les cas, on masque NONE
                 */
                _f_None();
                
                _f_Loading(true);
                    
                _f_Mystery("filtered",prm.sc,prm.dr,prm.fl,prm.pi,prm.pt,x);
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_LdMr = function (x,a) {
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                $(x).data("lk",1);
                
                _f_Spnr("load_more",true);
                
                $(".jb-tqr-dscvr-scrt-fil-mx").prop("disabled",true);
                var $mms = $(".jb-tqr-dscvr-scrt-art-lsts").find(".jb-tqr-dscvr-sct-a-bmx");
                var filprd = $(".jb-tqr-dscvr-scrt-fil-mx[data-action='mysm-fil-period'] option:selected").val();
                var filspec = $(".jb-tqr-dscvr-scrt-fil-mx[data-action='mysm-fil-spec'] option:selected").val();
                
                var fil = ( filprd === "DEFAULT" ) ? filspec : filprd.concat("_",filspec);
                             
//                Kxlib_DebugVars([filprd,filspec],true);
//                return;         

                if ( $mms.length ) {
                    var prm = {
                        sc : _f_Gdf().dftsc,
                        dr : "BTM",
                        fl : fil,
                        pi : $mms.last().data("item"),
                        pt : $mms.last().data("time")
                    };
                } else {
                    var prm = {
                        sc : _f_Gdf().dftsc,
                        dr : "FST",
                        fl : fil,
                        pi : $mms.last().data("item"),
                        pt : $mms.last().data("time")
                    };
                }
                
//                Kxlib_DebugVars([JSON.stringify(prm)],true);
//                return; 
                
                _f_Mystery("load_more",prm.sc,prm.dr,prm.fl,prm.pi,prm.pt,x);
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Mystery = function (cz,sc,dr,fl,pi,pt,x) {
            try {
                if ( KgbLib_CheckNullity(cz) | KgbLib_CheckNullity(sc) | KgbLib_CheckNullity(dr) | KgbLib_CheckNullity(fl) ) {
                    return;
                }
                
                $(".jb-tqr-dscvr-scrt-fil-mx").prop("disabled",true); 
                
                var s = $("<span/>"), xt = (new Date()).getTime();
                _f_Srv_PlMm(sc,dr,fl,pi,pt,xt,s);
                
                $(s).on("datasready",function(e,ds){
                    if ( KgbLib_CheckNullity(ds) ) {
                        return;
                    }
                    
//                    Kxlib_DebugVars([JSON.stringify(ds.alist)],true);
//                    return;

                    /*
                     * ETAPE :
                     *      Dans tous les cas, on masque NONE
                     */
                    _f_None();

                    /*
                     * ETAPE :
                     *      Dans le cas d'AUTOLOAD
                     */
                    if ( $.inArray(cz,["autoload","filtered"]) !== -1 ) {
                        _f_Loading(false);
                    }
                    
                    _f_DispAl(ds.alist,dr);
                    
                    
                    /*
                     * ETAPE : 
                     *      Reinitialisation des éléments
                     */
                    _xhr_plmms = null;
                    _f_Spnr("load_more",false);
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    $(".jb-tqr-dscvr-scrt-fil-mx").prop("disabled",false); 
                });
                
                $(s).on("operended",function(e){
                    _f_Loading(false);
                    
                    /*
                     * ETAPE : 
                     *      On affiche NONE dans le cas où il n'y a pas d'ARTICLES présents
                     */
                    if (! $(".jb-tqr-dscvr-sct-a-bmx").length ) {
                        _f_None(true);
                    }
                    
                    _xhr_plmms = null;
                    _f_Spnr("load_more",false);
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    $(".jb-tqr-dscvr-scrt-fil-mx").prop("disabled",false); 
                });
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_AddA = function (x,a) {
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                $(x).data("lk",1);
                
                if (! KgbLib_CheckNullity(_xhr_addmm) ) {
                    $(x).data("lk",0);
                    return;
                }
                
                var $txar = $(".jb-tqr-dscvr-s-a-n-txtar");
                var m = $txar.val();
                
                if ( KgbLib_CheckNullity(m) ) {
                    $(x).data("lk",0);
                    return;
                }
                
                $(".jb-tqr-dscvr-scrt-fil-mx").prop("disabled",true);
                var $mms = $(".jb-tqr-dscvr-scrt-art-lsts").find(".jb-tqr-dscvr-sct-a-bmx");
                var filprd = $(".jb-tqr-dscvr-scrt-fil-mx[data-action='mysm-fil-period'] option:selected").val();
                var filspec = $(".jb-tqr-dscvr-scrt-fil-mx[data-action='mysm-fil-spec'] option:selected").val();
                
                var fil = ( filprd === "DEFAULT" ) ? filspec : filprd.concat("_",filspec);
                
                var prm = {
                    m   : m,
                    sc  : _f_Gdf().dftsc,
                    fl  : fil,
                    pi  : $mms.first().data("item"),
                    pt  : $mms.first().data("time")
                };
                
                /*
                 * ETAPE :
                 *      Dans tous les cas on retire NONE
                 */
                _f_None();
                
                
                /*
                 * ETAPE :
                 *      S'il n'y a pas de BlindM présents dans la zone (liste), on fait apparaitre "LOADING" pour des raisons esthétiques.
                 */
                if (! $(".jb-tqr-dscvr-sct-a-bmx").length ) {
                    _f_Loading(true);
                }
                
//                var sc = _f_Gdf().dftsc, fl = _f_Gdf().dftfl;
                
//                Kxlib_DebugVars([JSON.stringify(prm)],true);
//                return;
                
                var s = $("<span/>");
                                
                _f_Srv_AddA(prm.sc,prm.fl,prm.m,prm.pi,prm.pt,x,s);
                
                $txar.val("");
                $(s).on("datasready",function(e,ds){
                    if ( KgbLib_CheckNullity(ds) ) {
                        return;
                    }
                    
//                    Kxlib_DebugVars([JSON.stringify(d)],true);
                    /*
                     * ETAPE :
                     *      Dans tous les cas on retire LOADING
                     */
                    _f_Loading();
                    
                    _f_DispAl(ds.alist,"TOP");
                    
                    $(".jb-tqr-dscvr-scrt-fil-mx").prop("disabled",false);
                    $(x).data("lk",0);
                    _xhr_addmm = null;
                });
                
                $(s).on("operended",function(e){
                    $(".jb-tqr-dscvr-scrt-fil-mx").prop("disabled",true);
                    $(x).data("lk",0);
                    _xhr_addmm = null;
                });
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };

        var _f_Rate = function(x,a) {
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                var $mm  = $(x).closest(".jb-tqr-dscvr-sct-a-bmx");

                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                //VoteButtonS
                var $vbs = $mm.find(".jb-tqr-dscvr-scrt-a-r-act");
                $vbs.data("lk",1);
                
                if ( !KgbLib_CheckNullity(_xhr_evlmm) | !KgbLib_CheckNullity(_xhr_addmm) | !KgbLib_CheckNullity(_xhr_plmms) ) {
                    $($vbs).data("lk",0);
                    return;
                }
                
                _f_EnaVote($mm,false);
                
                //rbx : RateBoX; b : rateBox; dv : DataValue; fv : FrontValue; p : eval-plus; m : eval-minus
                var rbx = {
                    b   : $(x).closest(".jb-tqr-dscvr-scrt-a-r-a-mx"),
                    dv  : $(x).closest(".jb-tqr-dscvr-scrt-a-r-a-mx").data("fig"),
                    fv  : $(x).closest(".jb-tqr-dscvr-scrt-a-r-a-mx").find(".jb-tqr-dscvr-scrt-a-r-v"),
                    p   : $(x).closest(".jb-tqr-dscvr-scrt-a-r-a-mx").find(".jb-tqr-dscvr-scrt-a-r-act[data-action='eval-plus']"),
                    m   : $(x).closest(".jb-tqr-dscvr-scrt-a-r-a-mx").find(".jb-tqr-dscvr-scrt-a-r-act[data-action='eval-minus']")
                };
                
                var prm = {
                    mmi : $mm.data("item"),
                    //VoTeCode
                    vtc : ( a === "eval-plus" ) ? "VOTE_UP" : "VOTE_DOWN"
                };
                
//                Kxlib_DebugVars([JSON.stringify(prm)],true);
//                return;
                
                var s = $("<span/>");
                
                _f_Srv_EvlMm(prm.mmi,prm.vtc,x,s);

                $(s).on("datasready",function(e,ds){
                    if ( KgbLib_CheckNullity(ds) ) {
    //                    return;
                    }
                    
//                    Kxlib_DebugVars([JSON.stringify(ds)],true);
//                    return;

                    _f_RatePrf(rbx,ds);
                    
                    _f_EnaVote($mm,true);
                    _xhr_evlmm = null;
                    $vbs.data("lk",0);
                });

                $(s).on("datasready",function(e){
                    
                    _f_EnaVote($mm,true);
                    _xhr_evlmm = null;
                    $vbs.data("lk",0);
                });


            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };

        var _f_RatePrf = function(o,d) {
            try { 
                if ( KgbLib_CheckNullity(o) | KgbLib_CheckNullity(d) ) {
                    return;
                }

                o.b.data("fig",d.ratio);
                o.fv.text(d.ratio);

            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };

        var _f_Dela = function(x,a) {
            try { 
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                $(x).data("lk",1);

                $mx = $(x).closest(".jb-tqr-dscvr-sct-a-bmx");
                switch (a) {
                    case "art-del-start" :
                            $mx.find(".jb-tqr-dscvr-s-a-action[data-action='art-del-start']").addClass("this_hide");
                            $mx.find(".jb-tqr-dscvr-s-a-da-cfmx").removeClass("this_hide");
                            $(x).data("lk",1);
                        return;
                    case "art-del-n" :
                            $mx.find(".jb-tqr-dscvr-s-a-da-cfmx").addClass("this_hide");
                            $mx.find(".jb-tqr-dscvr-s-a-action[data-action='art-del-start']").removeClass("this_hide");
                            /*
                             * ETAPE :
                             *      On débloque les boutons d'ACTION
                             */
                            $mx.find(".jb-tqr-dscvr-s-a-action[data-action='art-del-start'], .jb-tqr-dscvr-s-a-action[data-action='art-del-y'], .jb-tqr-dscvr-s-a-action[data-action='art-del-n']").data("lk",0);
                        return;
                    case "art-del-y" :
                        break;
                    default:
                            $(x).data("lk",0);
                        return;
                }
                
                /*
                 * ETAPE :
                 *      On récupère l'identifiant du BlindM 
                 */
                var i = $mx.data("item");
                if ( KgbLib_CheckNullity(i) ) {
                    $(x).data("lk",0);
                    return;
                }
                
//                Kxlib_DebugVars([i],true);
                
                var s = $("<span/>"), xt = (new Date()).getTime();
                
                _f_Srv_DlMm(i,xt,x,s);
                
                /*
                 * ETAPE :
                 *      On masque le BlindM
                 */
                $mx.addClass("this_hide");
                
                /*
                 * ETAPE :
                 *      S'il n'y a plus de BlindM présents (excepté celui masqué) dans la zone (liste), on fait apparaitre "LOADING" pour des raisons esthétiques.
                 */
                if (! $(".jb-tqr-dscvr-sct-a-bmx:not(.this_hide)").length ) {
                    _f_None();
                    _f_Loading(true);
                }
                
                $(s).on("operended",function(e,ds){
                    if ( KgbLib_CheckNullity(ds) ) {
                        return;
                    }
                    
//                    Kxlib_DebugVars([JSON.stringify(ds)],true);
//                    return;

                    /*
                     * ETAPE :
                     *      Dans tous les cas, on retire le LOADING
                     */
//                    _f_Loading();
                    
                    /*
                     * ETAPE :
                     *      On procède à la suppression effective du MYSM dans la liste. 
                     */
                    $mx.remove();
                    
                    /*
                     * ETAPE :
                     *      S'il n'y a plus de BlindM présents (excepté celui masqué) dans la zone (liste), on fait apparaitre "LOADING" pour des raisons esthétiques.
                     * NOTE :
                     *      On patiente quelques ms puis on controle
                     */
                    setTimeout(function(){
                        if (! $(".jb-tqr-dscvr-sct-a-bmx").length ) {
                            $(".jb-tqr-dscvr-s-a-action[data-action='mysm-filtered']").click();
                        }
                    },250);
                    
                    _xhr_delmm = null;
                });
                

            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_DispAl = function (ds,dr) {
            try {
                if ( KgbLib_CheckNullity(ds) | KgbLib_CheckNullity(dr) ) {
                    return;
                }
                
                $.each(ds,function(i,atb){
                    if ( $(".jb-tqr-dscvr-scrt-art-lsts").find(".jb-tqr-dscvr-sct-a-bmx").filter("[data-item='"+atb.id+"']").length ) {
                        return true;
                    }
                    
                    var m = _f_PprAr(atb);
                    m = _f_RbdAr(m);
                     
                    if ( $.inArray(dr,["FST","BTM"]) !== -1 ) {
                        $(m).hide().appendTo(".jb-tqr-dscvr-scrt-art-lsts").fadeIn(); 
                    } else {
                        $(m).hide().prependTo(".jb-tqr-dscvr-scrt-art-lsts").fadeIn(); 
                    }
                    
                });
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        

        /*******************************************************************************************************************************************************************/
        /*************************************************************************** SERVERS SCOPE *************************************************************************/
        /*******************************************************************************************************************************************************************/

        var _Ax_Adda = Kxlib_GetAjaxRules("TQR_TIA_MYSRY_ADD");
        var _f_Srv_AddA = function(sc,fl,m,pi,pt,x,s) {
            if ( KgbLib_CheckNullity(sc) | KgbLib_CheckNullity(fl) | KgbLib_CheckNullity(m) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
                return;
            }
        
            var onsuccess = function (datas) {
                try {
                    if (! KgbLib_CheckNullity(datas) ) {
                        datas = JSON.parse(datas);
                    } else {
                        _xhr_addmm = null;
                        return;
                    }

                    if (! KgbLib_CheckNullity(datas.err) ) {
                        _xhr_addmm = null;
                        if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                            switch (datas.err) {
                                case "__ERR_VOL_ACC_GONE" :
                                case "__ERR_VOL_USER_GONE" :
                                case "__ERR_VOL_U_G" :
                                case "__ERR_VOL_CU_GONE" :
                                        Kxlib_HandleCurrUserGone();
                                    break;
                                case "__ERR_VOL_FAILED" :
                                    //TODO : Retirer l'image en Buffer
                                    //Afficher le message adéquat dans la zone d'erreur
                                        Kxlib_AJAX_HandleFailed();
                                    break;
                                case "__ERR_VOL_DENY" :
                                case "__ERR_VOL_DENY_AKX" :
                                    break;
                                case "__ERR_VOL_DNY_AKX_AUTH" :
                                    break;
                                default :
                                        Kxlib_AJAX_HandleFailed();
                                    break;
                            }
                        } 
                        return;
                    } else if ( !KgbLib_CheckNullity(datas.return) && !KgbLib_CheckNullity(datas.return.alist) ) {
                        var ds = [datas.return];
                        $(s).trigger("datasready",ds);
                    } else if ( !KgbLib_CheckNullity(datas.return) ) {
                        $(s).trigger("operended");
                        return;
                    }

                } catch (ex) {
                    _xhr_addmm = null;
                    Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                    //TODO : Renvoyer l'erreur au serveur
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

                //TODO : Send error to SERVER
                //            Kxlib_DebugVars(["AJAX ERR : "+nwtrdart_uq],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            };
        
            var curl = document.URL;
            var toSend = {
                "urqid": _Ax_Adda.urqid,
                "datas": {
                    "sc"    : sc,
                    "fl"    : fl,
                    "m"     : m,
                    "pi"    : pi,
                    "pt"    : pt,
                    "cu"    : curl 
                }
            };
            
            _xhr_addmm = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_Adda.url, wcrdtl : _Ax_Adda.wcrdtl });
        };
        
        var _Ax_PlMm = Kxlib_GetAjaxRules("TQR_TIA_MYSRY_PLMM");
        var _f_Srv_PlMm = function(sc,dr,fl,pi,pt,xt,s) {
            if ( KgbLib_CheckNullity(sc) | KgbLib_CheckNullity(dr) | KgbLib_CheckNullity(fl) | KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(s) ) {
                return;
            }
        
            var onsuccess = function (datas) {
                try {
                    if (! KgbLib_CheckNullity(datas) ) {
                        datas = JSON.parse(datas);
                    } else {
                        _xhr_plmms = null;
                        return;
                    }

                    if (! KgbLib_CheckNullity(datas.err) ) {
                        _xhr_plmms = null;
                        if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                            switch (datas.err) {
                                case "__ERR_VOL_ACC_GONE" :
                                case "__ERR_VOL_USER_GONE" :
                                case "__ERR_VOL_U_G" :
                                case "__ERR_VOL_CU_GONE" :
                                        Kxlib_HandleCurrUserGone();
                                    break;
                                case "__ERR_VOL_FAILED" :
                                    //TODO : Retirer l'image en Buffer
                                    //Afficher le message adéquat dans la zone d'erreur
                                        Kxlib_AJAX_HandleFailed();
                                    break;
                                case "__ERR_VOL_DENY" :
                                case "__ERR_VOL_DENY_AKX" :
                                    break;
                                case "__ERR_VOL_DNY_AKX_AUTH" :
                                        if ( $(".jb-tqr-btm-lock").length ) {
                                            $(".jb-tqr-btm-lock").removeClass("this_hide");
                                            $(".jb-tqr-btm-lock-fd").removeClass("this_hide");
                                        }
                                    break;
                                default :
                                        Kxlib_AJAX_HandleFailed();
                                    break;
                            }
                        } 
                        return;
                    } else if ( !KgbLib_CheckNullity(datas.return) && !KgbLib_CheckNullity(datas.return.alist) ) {
                        var ds = [datas.return];
                        $(s).trigger("datasready",ds);
                    } else if ( !KgbLib_CheckNullity(datas.return) ) {
                        $(s).trigger("operended");
                        return;
                    }

                } catch (ex) {
                    _xhr_plmms = null;
//                    Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                    //TODO : Renvoyer l'erreur au serveur
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

                //TODO : Send error to SERVER
                //            Kxlib_DebugVars(["AJAX ERR : "+nwtrdart_uq],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            };
        
            var curl = document.URL;
            var toSend = {
                "urqid": _Ax_PlMm.urqid,
                "datas": {
                    "sc"    : sc,
                    "dr"    : dr,
                    "fl"    : fl,
                    "pi"    : pi,
                    "pt"    : pt,
                    "xt"    : xt,
                    "cu"    : curl 
                }
            };
        
            _xhr_plmms = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlMm.url, wcrdtl : _Ax_PlMm.wcrdtl });
        };
        
        
        var _Ax_EvlMm = Kxlib_GetAjaxRules("TQR_TIA_MYSRY_EVLMM");
        var _f_Srv_EvlMm = function(mmi,vtc,x,s) {
            if ( KgbLib_CheckNullity(mmi) | KgbLib_CheckNullity(vtc) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
                return;
            }
        
            var onsuccess = function (datas) {
                try {
                    if (! KgbLib_CheckNullity(datas) ) {
                        datas = JSON.parse(datas);
                    } else {
                        _xhr_evlmm = null;
                        return;
                    }

                    if (! KgbLib_CheckNullity(datas.err) ) {
                        _xhr_evlmm = null;
                        if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                            switch (datas.err) {
                                case "__ERR_VOL_ACC_GONE" :
                                case "__ERR_VOL_USER_GONE" :
                                case "__ERR_VOL_U_G" :
                                case "__ERR_VOL_CU_GONE" :
                                        Kxlib_HandleCurrUserGone();
                                    break;
                                case "__ERR_VOL_FAILED" :
                                    //TODO : Retirer l'image en Buffer
                                    //Afficher le message adéquat dans la zone d'erreur
                                        Kxlib_AJAX_HandleFailed();
                                    break;
                                case "__ERR_VOL_DENY" :
                                case "__ERR_VOL_DENY_AKX" :
                                    break;
                                case "__ERR_VOL_DNY_AKX_AUTH" :
                                    break;
                                default :
                                        Kxlib_AJAX_HandleFailed();
                                    break;
                            }
                        } 
                        return;
                    } else if ( !KgbLib_CheckNullity(datas.return) && !KgbLib_CheckNullity(datas.return.currvt) && !KgbLib_CheckNullity(datas.return.ratio) ) {
                        var ds = [datas.return];
                        $(s).trigger("datasready",ds);
                    } else if ( !KgbLib_CheckNullity(datas.return) ) {
                        $(s).trigger("operended");
                        return;
                    }

                } catch (ex) {
                    _xhr_evlmm = null;

                    //TODO : Renvoyer l'erreur au serveur
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

                //TODO : Send error to SERVER
                //            Kxlib_DebugVars(["AJAX ERR : "+nwtrdart_uq],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            };
        
            var curl = document.URL;
            var toSend = {
                "urqid": _Ax_EvlMm.urqid,
                "datas": {
                    "mmi"   : mmi,
                    "vtc"   : vtc,
                    "cu"    : curl 
                }
            };
            
            _xhr_evlmm = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_EvlMm.url, wcrdtl : _Ax_EvlMm.wcrdtl });
        };
        
        
        var _Ax_DlMm = Kxlib_GetAjaxRules("TQR_TIA_MYSRY_DLMM");
        var _f_Srv_DlMm = function(i,xt,x,s) {
            if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
                return;
            }
        
            var onsuccess = function (datas) {
                try {
                    if (! KgbLib_CheckNullity(datas) ) {
                        datas = JSON.parse(datas);
                    } else {
                        _xhr_delmm = null;
                        return;
                    }

                    if (! KgbLib_CheckNullity(datas.err) ) {
                        _xhr_delmm = null;
                        if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                            switch (datas.err) {
                                case "__ERR_VOL_ACC_GONE" :
                                case "__ERR_VOL_USER_GONE" :
                                case "__ERR_VOL_U_G" :
                                case "__ERR_VOL_CU_GONE" :
                                        Kxlib_HandleCurrUserGone();
                                    break;
                                case "__ERR_VOL_FAILED" :
                                    //TODO : Retirer l'image en Buffer
                                    //Afficher le message adéquat dans la zone d'erreur
                                        Kxlib_AJAX_HandleFailed();
                                    break;
                                case "__ERR_VOL_DENY" :
                                case "__ERR_VOL_DENY_AKX" :
                                    break;
                                case "__ERR_VOL_DNY_AKX_AUTH" :
                                    break;
                                default :
                                        Kxlib_AJAX_HandleFailed();
                                    break;
                            }
                        } 
                        return;
                    } else if ( !KgbLib_CheckNullity(datas.return) ) {
                        var ds = [datas.return];
                        $(s).trigger("operended",ds);
                    } else {
                        return;
                    }

                } catch (ex) {
                    _xhr_delmm = null;

                    //TODO : Renvoyer l'erreur au serveur
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

                //TODO : Send error to SERVER
                //            Kxlib_DebugVars(["AJAX ERR : "+nwtrdart_uq],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            };
        
            var curl = document.URL;
            var toSend = {
                "urqid": _Ax_DlMm.urqid,
                "datas": {
                    "i"     : i,
                    "xt"    : xt,
                    "cu"    : curl 
                }
            };
            
//            Kxlib_DebugVars([_Ax_DlMm.url,_Ax_DlMm.wcrdtl],true);
            
            _xhr_delmm = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_DlMm.url, wcrdtl : _Ax_DlMm.wcrdtl });
        };
        
        
        /*******************************************************************************************************************************************************************/
        /***************************************************************************** VIEW SCOPE **************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        var _f_Loading = function (is) {
            try {
                if ( is ) {
                    
                    //ETAPE : On masque le spinner LOAD_MORE
                    $(".jb-tqr-dscvr-s-a-action[data-action='load-more']").addClass("this_hide");
                    
                    //ETAPE : On affiche la zone LOADING
                    $(".jb-tqr-d-scrt-mdl-l-spnr-mx").removeClass("this_hide");
                    
                } else {
                    //ETAPE : On masque la zone LOADING
                    $(".jb-tqr-d-scrt-mdl-l-spnr-mx").addClass("this_hide");
                    
                    //ETAPE : On affiche le spinner LOAD_MORE
                    $(".jb-tqr-dscvr-s-a-action[data-action='load-more']").removeClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_None = function (shw) {
            try {
                
                if ( shw ) {
                    $(".jb-tqr-dscvr-scrt-a-l-nne-mx").removeClass("this_hide");
                } else {
                    $(".jb-tqr-dscvr-scrt-a-l-nne-mx").addClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_PprAr = function (d) {
            try {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                Kxlib_DebugVars([d,JSON.stringify(d)],true);
//                alert(d.id);
                
                
                am = "<article class=\"tqr-dscvr-scrt-art-bmx jb-tqr-dscvr-sct-a-bmx\" data-item=\"\" data-time=\"\" data-ajcache=\"\">";
                am += "<div class=\"tqr-dscvr-scrt-art-mdl-bx\">";
                am += "<div class=\"tqr-dscvr-scrt-art-rate-mx jb-tqr-dscvr-scrt-a-r-a-mx\" data-item=\"\" data-fig=\"0\" data-state=\"done\">";
                am += "<div class=\"tqr-dscvr-scrt-art-rate-act-bx jb-tqr-dscvr-scrt-a-r-a-bx\" data-state=\"done\">";
                am += "<a class=\"tqr-dscvr-scrt-art-rate-act jb-tqr-dscvr-scrt-a-r-act\" data-action=\"eval-plus\" data-state=\"done\">+</a>";
                am += "</div>";
                am += "<div class=\"tqr-dscvr-scrt-art-rate-val jb-tqr-dscvr-scrt-a-r-v\" data-state=\"done\">0</div>";
                am += "<div class=\"tqr-dscvr-scrt-art-rate-act-bx jb-tqr-dscvr-scrt-a-r-a-bx\" data-state=\"done\">";
                am += "<a class=\"tqr-dscvr-scrt-art-rate-act jb-tqr-dscvr-scrt-a-r-act\" data-action=\"eval-minus\" data-state=\"done\">-</a>";
                am += "</div>";
                am += "</div>";
                am += "<div class=\"tqr-dscvr-scrt-art-upper-bmx\">";
                am += "<div class=\"tqr-dscvr-scrt-art-qmak\">";
                am += "<span class=\"tqr-dscvr-scrt-a-q-sign\"></span>";
                am += "</div>";
                am += "<div class=\"tqr-dscvr-scrt-art-bnr\">";
                am += "<a class=\"tqr-dscvr-s-a-dela jb-tqr-dscvr-s-a-action\" data-action=\"art-del-start\">Supprimer</a>";
                am += "<span class=\"tqr-dscvr-s-a-dela-cfmx jb-tqr-dscvr-s-a-da-cfmx this_hide\">";
                am += "<span class=\"tqr-dscvr-s-a-dela-cf-lbl\">Confirmer ? </span>";
                am += "<a class=\"tqr-dscvr-s-a-dela-cf-ch jb-tqr-dscvr-s-a-action\" data-action=\"art-del-y\" role=\"button\">Oui</a>";
                am += "<a class=\"tqr-dscvr-s-a-dela-cf-ch jb-tqr-dscvr-s-a-action\" data-action=\"art-del-n\" role=\"button\">Non</a>";
                am += "</span>";
                am += "<span class=\"kxlib_tgspy art-time\" data-tgs-crd=\"\" data-tgs-dd-atn=\"\" data-tgs-dd-uut=\"\">";
                am += "<span class='tgs-frm'></span>";
                am += "<span class='tgs-val'></span>";
                am += "<span class='tgs-uni'></span>";
                am += "</span>";
                am += "</div>";
                am += "<div class=\"tqr-dscvr-scrt-art-txt-mx\">";
                am += "<div class=\"tqr-dscvr-scrt-a-t jb-tqr-dscvr-scrt-a-t\"></div>";
                am += "</div>";
                am += "</article>";
                am = $.parseHTML(am);
                
                /*
                 * ETAPE :
                 *      On rend enable la zone des votes
                 */
                var $am = _f_EnaVote($(am),true);
                
                //HEADERS
                $am.data("item",d.id).attr("data-item",d.id);
                $am.data("time",d.time).attr("data-time",d.time);
                $am.data("ajcache",JSON.stringify(d));
                
                //DELETE CASE
                if ( d.candel !== true ) {
                    $am.find(".jb-tqr-dscvr-s-a-action[data-action='art-del-start'], .jb-tqr-dscvr-s-a-da-cfmx").remove();
                }
                
                //DATE
                $am.find(".art-time").data("tgs-crd",d.time);
                
                //TEXT
                var txt = Kxlib_TextEmpow(d.text,null,d.hashs,null,{
                    emoji : {
                        "size"      : 36,
                        "size_css"  : 22,
                        "position_y" : 3
                    }
                });
                $am.find(".jb-tqr-dscvr-scrt-a-t").append(txt);
                
                //VOTES
                $am.find(".jb-tqr-dscvr-scrt-a-r-v").text(d.sumvotes);
                
                return $am;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_RbdAr = function (m) {
            try {
                if ( KgbLib_CheckNullity(m) ) {
                    return;
                }
                
                $(m).find(".jb-tqr-dscvr-scrt-a-r-act, .jb-tqr-dscvr-s-a-action").click(function(e){
                    Kxlib_PreventDefault(e);

                    _f_Action(this);
                });
                
                return m;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_EnaVote = function ($mm,a) {
            try {
                if ( KgbLib_CheckNullity($mm) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                if ( a ) {
                    $mm.find(".jb-tqr-dscvr-scrt-a-r-a-mx").data("state","").attr("data-state","");
                    $mm.find(".jb-tqr-dscvr-scrt-a-r-a-bx").data("state","").attr("data-state","");
                    $mm.find(".jb-tqr-dscvr-scrt-a-r-act").data("state","").attr("data-state","");
                    $mm.find(".jb-tqr-dscvr-scrt-a-r-v").data("state","").attr("data-state","");
                } else {
                    $mm.find(".jb-tqr-dscvr-scrt-a-r-a-mx").data("state","done").attr("data-state","done");
                    $mm.find(".jb-tqr-dscvr-scrt-a-r-a-bx").data("state","done").attr("data-state","done");
                    $mm.find(".jb-tqr-dscvr-scrt-a-r-act").data("state","done").attr("data-state","done");
                    $mm.find(".jb-tqr-dscvr-scrt-a-r-v").data("state","done").attr("data-state","done");
                }
                
                return $mm;
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_Spnr = function (scp,shw) {
            try {
                if ( KgbLib_CheckNullity(scp) ) {
                    return;
                }
                
                var $spnr;
                switch (scp) {
                    case "load_more" :
                            $spnr = $(".jb-tqr-dscvr-s-a-action[data-scp='load-more']");
                        break;
                    default :
                        return;
                }
                
                if ( shw ) {
                    $spnr.find("._this_tgr").addClass("this_hide");
                    $spnr.find("._this_spnr").removeClass("this_hide");
                } else {
                    $spnr.find("._this_spnr").addClass("this_hide");
                    $spnr.find("._this_tgr").removeClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };

        /*******************************************************************************************************************************************************************/
        /************************************************************************** LISTENERS SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/


        $(".jb-tqr-dscvr-scrt-a-r-act, .jb-tqr-dscvr-s-a-action").click(function(e){
            Kxlib_PreventDefault(e);

            _f_Action(this);
        });


        /*******************************************************************************************************************************************************************/
        /**************************************************************************** INNIT SCOPE **************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        /*
         * [NOTE ]
         *      Peut être utilisé pour des raisons de DEV, TEST, DEBUG.
         *      On ne lance pas la procédure pour des raisons de performance en privilégiant EXPLORER
         */
        _f_Init(); 
    };
});
