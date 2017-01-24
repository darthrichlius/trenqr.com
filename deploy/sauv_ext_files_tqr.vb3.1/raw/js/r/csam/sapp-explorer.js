
define("sapp/sapp-explorer", function () {
    return function() {
        /*******************************************************************************************************************************************************************/
        /**************************************************************************** PROCESS SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        var _xhr_plars, _xhr_tsty_ax;
        
        var _f_Gdf = function () {
            var dt = {
                "pstPvwLn"  : 200,
                "tkm_cov_w" : 380, 
                "tkm_cov_h" : 150 
            }; 

            return dt;
        };
        
        
        var _f_Init = function () {
            try {
                
                /*
                 * ETAPE :
                 *      On RENDER les Textes des titres et description des zones. 
                 */
                var tles = $(".jb-tqr-dscvr-ex-l-w-tle");
                $.each(tles,function(i,txz){
                    var txt = $(txz).text();
                    /*
                     * ETAPE :
                     *      Necessaire pour faire fonctionner l'affichage des EMOJIS spécifiques sur tous les BROWSERS
                     */
//                    if ( $(txz).data("scp") === "articles" && txt.substring(-1,1) !== "😜" ) {
                    if ( $(txz).data("scp") === "articles" && txt.slice(-1) === " " ) {
                        txt += "😜";
                    }
                    var rtxt = Kxlib_TextEmpow(txt,null,null,null,{
                        emoji : {
                            "size"          : 36,
                            "size_css"      : 22,
                            "position_y"    : 3
                        }
                    });
                    $(txz).text("").html(rtxt);
                });
                
                /*
                 * ETAPE :
                 *      On lance l'opération de chargement des ARTICLES (multimédias, status, Tendance)
                 */
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
                
                if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && !a ) ) {
                    return;
                } 
                
                var _a = ( KgbLib_CheckNullity(a) ) ? $(x).data("action") : a;
                switch (_a) {
                    case "explr-sec-focus" :
                    case "explr-bckth" :
                            _f_SwScn(x,_a);
                        break;
                    case "explr-load-more" :
                            _f_SecLdMr(x,_a);
                        break;
//                    case "scrt-del-art" :
//                    case "scrt-del-a-y" :
//                    case "scrt-del-a-n" :
//                            _f_Tlkbd_DelA(x,_a);
//                        break;
                    case "explr-post-react-open" :
                            _f_Tlkbd_OpenOnVw(x,_a);
                        break;
                    case "explr-post-like" :
                    case "explr-post-unlike" :
                            _f_Tlkbd_LikeAct(x,_a);
                        break;
                    default:
                        return;
                        
                }

            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_SwScn = function (x,a){
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                //BigScreenSelector, SCopeName, SCopeSelector, ARticleSelector
                var bss, scn = $(x).data("scp"), scs, ars;
                switch (scn) {
                    case "articles" :
                            ars = $(".jb-tqr-dscvr-ex-a-bmx");
                        break;
                    case "talkboard" :
                            ars = $(".jb-tqr-dscvr-explr-tlbm-bmx");
                        break;
                    case "trends" :
                            ars = $(".jb-tqr-dscvr-ex-trm-bmx");
                        break;
                    case "collec" :
                            ars = $(".jb-tqr-dscvr-explr-clcmdl-bmx");
                        break;
                    default:
                        return;
                }
                bss = $(".jb-tqr-dscvr-gbl-s-bdy[data-name='explorer']");
                scs = $(".jb-tqr-dscvr-explr-sbs[data-name='"+scn+"']");
                
                /*
                 * ETAPE :
                 *      On masque les ARTICLES en attendant que tous les autres anim se mettent en place
                 */
                ars.addClass("this_hide");
                
                
                /*
                 * ETAPE :
                 *      On gère les screen 
                 */
                if ( a === "explr-sec-focus" ) {
                    $(".jb-tqr-dscvr-explr-sbs:not([data-name='"+scn+"'])").addClass("this_hide");
                    $(".jb-tqr-dscvr-explr-sbs").removeClass("active");
                    scs.removeClass("this_hide").addClass("active");

                    /*
                     * ETAPE :
                     *      On retirer le FOCUS et on le remplace par LDM
                     */
                    $(x).addClass("this_hide");
                    scs.find(".jb-tqr-dscvr-explr-a-ldmr-a[data-action='explr-load-more']").removeClass("this_hide");
                    
                    /*
                     * ETAPE :
                     *      On affiche le bouton BACK
                     */
                    scs.find(".jb-tqr-dscvr-explr-sbs-h-tle").addClass("this_hide");
                    scs.find(".jb-tqr-dscvr-explr-s-tle-mx").removeClass("this_hide");
                } else if ( a === "explr-bckth" ) {
                    
                    /*
                     * ETAPE :
                     *      On retire le surplus d'Articles
                     */
                    var sec = $(".jb-tqr-dscvr-explr-sbs.active").data("name");
                    switch (sec) {
                        case "articles" :
                                if ( $(".jb-tqr-dscvr-ex-a-bmx").length > 9 ) {
                                    $(".jb-tqr-dscvr-ex-a-bmx").slice(9).remove();
                                }
                            break;
                        case "talkboard" :
                                if ( $(".jb-tqr-dscvr-ex-tkm-bmx").length > 9 ) {
                                    $(".jb-tqr-dscvr-ex-tkm-bmx").slice(9).remove();
                                }
                            break;
                        case "trends" :
                                if ( $(".jb-tqr-dscvr-ex-tkm-bmx").length > 6 ) {
                                    $(".jb-tqr-dscvr-ex-tkm-bmx").slice(6).remove();
                                }
                            break;
                        default:
                            break;
                    }
                    
                    $(".jb-tqr-dscvr-explr-sbs").removeClass("this_hide").removeClass("active");
                    
                    /*
                     * ETAPE :
                     *      On affiche le FOCUS et on retire LDM
                     */
                    scs.find(".jb-tqr-dscvr-explr-a-ldmr-a[data-action='explr-load-more']").addClass("this_hide");
                    scs.find(".jb-tqr-dscvr-explr-a-ldmr-a[data-action='explr-sec-focus']").removeClass("this_hide");
                    
                    /*
                     * ETAPE :
                     *      On masque le bouton BACK
                     */
                    scs.find(".jb-tqr-dscvr-explr-s-tle-mx").addClass("this_hide");
                    scs.find(".jb-tqr-dscvr-explr-sbs-h-tle").removeClass("this_hide");
                    
                    
                    
                    
                } else {
                    return;
                }
                
                
                /*
                 * ETAPE :
                 *      On affiche les ARTICLES
                 */
                ars.removeClass("this_hide");
                
                /*
                 * ETAPE :
                 *      On réorganise les Articles en refresh Masonry
                 */
                $(".jb-tqr-dscvr-ex-a-l").masonry();
                $(".jb-tqr-dscvr-ex-tkm-l").masonry();
                $(".jb-tqr-dscvr-ex-trm-l").masonry();
//                $(".jb-tqr-dscvr-explr-clcmdl-lst").masonry();
 
                bss.scrollTop(0);
                
                /*
                 * ETAPE :
                 *      Lancer le lancement des ARTICLES supplémentaires
                 */
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_Autoload = function () {
            try {
                /*
                 * [NOTE 16-04-16]
                 *      On s'en sert comme référence car il ne peut pas ne pas y avoir d'ARTICLES.
                 *      Ca ne sert donc à rien que de cheker pour chaque cas.
                 */
                var $arts = $(".jb-tqr-dscvr-ex-a-l").find(".jb-tqr-dscvr-ex-a-bmx");
                if (! $arts.length ) {
                    var prm = {
                        sc : "SEC_TQR",
                        dr : "FST",
                        fl : "DEFAULT",
                        pi : "",
                        pt : ""
                    };
                    
//                    Kxlib_DebugVars([JSON.stringify(prm)],true);
                    
                    _f_Explorer("autoload",prm.sc,prm.dr,prm.fl,prm.pi,prm.pt);
                    
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_GetSecCode = function () {
            try {

                var $zn = $(".jb-tqr-dscvr-explr-sbs.active"), sec = $zn.data("name");
                if (! $zn.length ) {
                    return;
                } else if (! sec ) {
                    return;
                }
                sec = sec.toUpperCase();
                switch (sec) {
                    case "ARTICLES" : 
                        return "SEC_TQR_ART";
                    case "TALKBOARD" : 
                        return "SEC_TQR_TST";
                    case "TRENDS" : 
                        return "SEC_TQR_TRD";
                    default :
                        return;
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_SecLdMr = function (x,a) {
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                $(x).data("lk",1);
                
                if (! KgbLib_CheckNullity(_xhr_plars) ) {
                    _f_Spnr("load_more",false);
                    $(x).data("lk",0);
                    return;
                }
                
                var sec = $(x).data("scp").toString().toUpperCase(), $arts;
                switch (sec){
                    case "ARTICLES" :
                            $arts = $(".jb-tqr-dscvr-ex-a-l").find(".jb-tqr-dscvr-ex-a-bmx");
                        break;
                    case "TALKBOARD" :
                            $arts = $(".jb-tqr-dscvr-ex-tkm-l").find(".jb-tqr-dscvr-ex-tkm-bmx");
                        break;
                    case "TRENDS" :
                            $arts = $(".jb-tqr-dscvr-ex-trm-l").find(".jb-tqr-dscvr-ex-trm-bmx");
                        break;
                    default :
                        return;
                }
                
                if ( !$arts.length | !_f_GetSecCode() ) {
                    _f_Spnr(sec,"load_more",false);
                    $(x).data("lk",0);
                    return;
                }
                
                _f_Spnr(sec,"load_more",true);
                
                var prm = {
                    sc : _f_GetSecCode(),
                    dr : "BTM",
                    fl : "DEFAULT",
                    pi : $arts.filter(":last").data("item"),
                    pt : $arts.filter(":last").data("time")
                };
                
                if ( KgbLib_CheckNullity(prm.pi) | KgbLib_CheckNullity(prm.pt) ) {
//                    alert("Not reference");
                    _f_Spnr(sec,"load_more",false);
                    $(x).data("lk",0);
                    return;
                }
                
//                Kxlib_DebugVars([JSON.stringify(prm)],true);
//                return;
                
                _f_Explorer("load_more",prm.sc,prm.dr,prm.fl,prm.pi,prm.pt,x);
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Explorer = function (cz,sc,dr,fl,pi,pt,x) {
            try {
                if ( KgbLib_CheckNullity(cz) | KgbLib_CheckNullity(sc) | KgbLib_CheckNullity(dr) | KgbLib_CheckNullity(fl) ) {
                    return;
                }
                
                var s = $("<span/>"), xt = (new Date()).getTime();
                _f_Srv_PlAr(sc,dr,fl,pi,pt,xt,s);
                
                $(s).on("datasready",function(e,ds){
                    if ( KgbLib_CheckNullity(ds) ) {
                        return;
                    }
                    
//                    Kxlib_DebugVars([JSON.stringify(ds.alist)],true);
//                    return;
                    
                    /*
                     * ETAPE :
                     *      On masque les LOADING.
                     *      Sert surtout dans le cas d'AUTOLOAD.
                     */
                    if ( cz === "autoload" ) {
                        $(".jb-tqr-dex-l-w-spnr-mx").addClass("this_hide");
                    }
                    
                    /*
                     * ETAPE :
                     *      On affiche les boutons LOAD_MORE
                     */
                    if ( cz === "autoload" ) {
                        $(".jb-tqr-d-exp-a-ldm-mx").removeClass("this_hide");
                    }
                    
                    /*
                     * ETAPE :
                     *      On affiche les zones d'affichage des ARTICLES
                     */
                    if ( cz === "autoload" ) {
                        $(".jb-tqr-dscvr-art-lst").removeClass("this_hide");
                    }
                    
                    /*
                     * ETAPE :
                     *      On affiche les ARTICLES
                     */
                    _f_DispAl(ds.alist,dr);
                    
                    /*
                     * ETAPE : 
                     *      Reinitialisation des éléments
                     */
                    _xhr_plars = null;
                    _f_Spnr(sc,"load_more",false);
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
//                    if ( cz === "change_menu" ) {
//                        $(".jb-tqr-d-p-m-l-spnr").data("lk",0);
//                    }
                });
                
                $(s).on("operended",function(e){
                    _xhr_plars = null;
                    _f_Spnr(sc,"load_more",false);
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    if ( cz === "change_menu" ) {
                        $(".jb-tqr-d-p-m-l-spnr").data("lk",0);
                    }
                });
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_DispAl = function (bds,dr) {
            try {
                //bds  : BigDataS
                if ( KgbLib_CheckNullity(bds) | KgbLib_CheckNullity(dr) ) {
                    return;
                }
                
                var sec;
                $.each(bds,function(i,ds){
                    sec = i;
                    $.each(ds,function(i,atb){
                        var m, $mlst;
                        switch (sec) {
                            case "XART" :
                                    $mlst = $(".jb-tqr-dscvr-ex-a-l");
                                    if ( $mlst.find(".jb-tqr-dscvr-ex-a-bmx").filter("[data-item='"+atb.id+"']").length ) {
                                        return true;
                                    }
                        
                                    m = _f_PprAr_XART(atb);
                                    m = _f_RbdAr_XART(m);
                                    $(m).hide().appendTo(".jb-tqr-dscvr-ex-a-l").fadeIn();
                                break;
                            case "XTST" :
                                    $mlst = $(".jb-tqr-dscvr-ex-tkm-l");
                                    if ( $mlst.find(".jb-tqr-dscvr-ex-tkm-bmx").filter("[data-item='"+atb.trd_eid+"']").length ) {
                                        return true;
                                    }
                        
                                    m = _f_PprAr_XTST(atb);
                                    m = _f_RbdAr_XTST(m);
                                    $(m).hide().appendTo(".jb-tqr-dscvr-ex-tkm-l").fadeIn();
                                break;
                            case "XTRD" :
                                    $mlst = $(".jb-tqr-dscvr-ex-trm-l");
                                    if ( $mlst.find(".jb-tqr-dscvr-ex-trm-bmx").filter("[data-item='"+atb.i+"']").length ) {
                                        return true;
                                    }
                        
                                    m = _f_PprAr_XTRD(atb);
//                                    m = _f_RbdAr_XTRD(m);
                                    $(m).hide().appendTo(".jb-tqr-dscvr-ex-trm-l").fadeIn();
                                break;
                            default:
                                return true;
                        }
                        
                        
                    });
                    
                });
                
                $(".jb-tqr-dscvr-ex-a-l").masonry("reloadItems");
                $(".jb-tqr-dscvr-ex-a-l").masonry("layout");
                
                $(".jb-tqr-dscvr-ex-tkm-l").masonry("reloadItems");
                $(".jb-tqr-dscvr-ex-tkm-l").masonry("layout");
                
                $(".jb-tqr-dscvr-ex-trm-l").masonry("reloadItems");
                $(".jb-tqr-dscvr-ex-trm-l").masonry("layout");
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        /*************************************************************************** SECTION : ARTICLES *************************************************************************/
        
        
        /*************************************************************************** SECTION : TALKBOARD *************************************************************************/
        
        
        var _f_Tlkbd_OpenOnVw = function (x,a) {
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }

    //            Kxlib_DebugVars([$(x).closest(".jb-tbv-bind-art-mdl").data("item")],true);
    //            return;
                if ( require.specified("r/csam/tkbvwr.csam") ) {
    //                Kxlib_DebugVars(["ASDRBNR : Déjà chargé !",_VWR]);
                    _VWR.open({
                        model   : "AJCA-TIA-EXPLR",
                        trigger : x,
                        action  : a
                    });
                } else {
                    require(["r/csam/tkbvwr.csam"],function(TbkVwr){
                        _VWR = new TbkVwr();
                        _VWR.open({
                            model   : "AJCA-TIA-EXPLR",
                            trigger : x,
                            action  : a
                        });
                    });
                }

            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Tlkbd_LikeAct = function (x,a) {
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }

                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                $(x).data("lk",1);

                /*
                 * tsa  : ToSendAction
                 * mrld : MustReLoaD 
                 */
                var bmx = $(x).closest(".jb-tqr-dscvr-ex-tkm-bmx"), tsa;
                switch (a) {
                    case "explr-post-like" :
                            tsa = "TST_XA_GOLK";
    //                        console.log("Lancer l'animation");
                        break;
                    case "explr-post-unlike" :
                            tsa = "TST_XA_GOULK";
                        break;
                    default:
                        return;
                }
    
                var i = $(bmx).data("item");
//                Kxlib_DebugVars([a,i,tsa],true);
//                return;
                if ( KgbLib_CheckNullity(i) ) {
                    return;
                }

                /*
                 * ETAPE :
                 *      On lock tous les boutons d'ACTION
                 */
                $(x).data("lk",1);

                var s = $("<span/>"), xt = (new Date()).getTime();
            
                _f_Srv_XaTsty(i,tsa,xt,x,s);
            
                $(s).on("operended",function(e,d){
                    if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(d.i) | KgbLib_CheckNullity(d.lc) ) {
                        return;
                    }

                    /*
                     * ETAPE :
                     *      Dans tous les cas on ajoute le nombre de LIKE
                     */
                    $(x).text(d.lc);

                    /*
                     * ETAPE :
                     *      After Work
                     */
                    if ( a === "explr-post-like" ) {
                        /*
                         * ETAPE : 
                         *      On change les ACTION
                         */
                        $(x).attr("data-state","me").data("state","me").data("action",'post-unlike').data("actrvs","explr-post-like");

                    } else {
                        if ( d.lc ) {
                            $(x).attr("data-state","ano1").data("state","ano1");
                        } else {
                            $(x).attr("data-state","").data("state","");
                        }

                        /*
                         * ETAPE : 
                         *      On change les ACTION
                         */
                         $(x).data("action",'post-like').data("actrvs","explr-post-unlike");

                    } 

                    $(x).data("lk",0);
                    _xhr_tsty_ax = null;
                });

            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
    
        
        
        /*************************************************************************** SECTION : TRENDS *************************************************************************/


        /*******************************************************************************************************************************************************************/
        /*************************************************************************** SERVERS SCOPE *************************************************************************/
        /*******************************************************************************************************************************************************************/

        
        var _Ax_PlAr = Kxlib_GetAjaxRules("TQR_TIA_EXPLR_PL");
        var _f_Srv_PlAr = function(sc,dr,fl,pi,pt,xt,s) {
            if ( KgbLib_CheckNullity(sc) | KgbLib_CheckNullity(dr) | KgbLib_CheckNullity(fl) | KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(s) ) {
                return;
            }
        
            var onsuccess = function (datas) {
                try {
                    if (! KgbLib_CheckNullity(datas) ) {
                        datas = JSON.parse(datas);
                    } else {
                        _xhr_plars = null;
                        return;
                    }

                    if (! KgbLib_CheckNullity(datas.err) ) {
                        _xhr_plars = null;
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
                    _xhr_plars = null;
                    
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
                "urqid": _Ax_PlAr.urqid,
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
        
            _xhr_plars = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlAr.url, wcrdtl : _Ax_PlAr.wcrdtl });
        };
        
        /*************************************************************************** SECTION : TALKBOARD *************************************************************************/
        
        
        var _Ax_XaTsty = Kxlib_GetAjaxRules("TQR_TSTY_XTAC");
        var _f_Srv_XaTsty  = function(i,a,xt,x,s) {
            if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(a) | KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
                return;
            }

            var onsuccess = function (datas) {
                //            alert("CHAINE JSON AVANT PARSE"+datas);
                try {
                    if (! KgbLib_CheckNullity(datas) ) {
                        datas = JSON.parse(datas);
                    } else {
                        _xhr_tsty_ax = null;
                        $(x).data("lk",0);
                        return;
                    }

                    if(! KgbLib_CheckNullity(datas.err) ) {
                        _xhr_tsty_ax = null;
                        $(x).data("lk",0);
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
                                case "__ERR_VOL_TST_GONE" :
                                        //TODO : Supprimer de la liste OU demander à recharger
                                    break;
                                case "__ERR_VOL_DNY_AKX" :
                                        //TODO : ... ?
                                    break;
                                case "__ERR_VOL_AMBIGUOUS" :
                                        //TODO : ... ?
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
                    //TODO : Renvoyer l'erreur au serveur
                    //                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
    //                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
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
                "urqid": _Ax_XaTsty.urqid,
                "datas": {
                    "i"     : i,
                    "a"     : a,
                    "xt"    : xt,
                    "cu"    : curl
                }
            };

            _xhr_tsty_ax = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_XaTsty.url, wcrdtl : _Ax_XaTsty.wcrdtl });
        };

        /*******************************************************************************************************************************************************************/
        /***************************************************************************** VIEW SCOPE **************************************************************************/
        /*******************************************************************************************************************************************************************/

        /*************************************************************************** SECTION : ARTICLES *************************************************************************/
        
        var _f_PprAr_XART = function (d) {
            try {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                Kxlib_DebugVars([d,JSON.stringify(d)],true);
//                alert(d.id);

                var str__;
                if ( d.hasOwnProperty("ustgs") && !KgbLib_CheckNullity(d.ustgs) && typeof d.ustgs === "object" ) {
                    var istgs__ = [];
                    $.each(d.ustgs, function(x,v) {
                        var rw__ = [];
                        $.map(v, function(e,x) {
                            rw__.push(e);
                        });
                        istgs__.push(rw__.join("','"));
                    });

                    str__ = ( istgs__.length > 1 ) ? istgs__.join("'],['") : istgs__[0];
                    str__ = "['" + str__ + "']";
                }
                
                var am = "<article class=\"tqr-dscvr-explr-art-bmx jb-tqr-dscvr-ex-a-bmx jb-unq-bind-art-mdl\" data-item=\"\" data-time=\"\" data-tr=\"\" data-atype=\"\" data-hatr=\"\" data-ajcache=\"\" data-with=\"\" data-vidu=\"\" data-trq-ver='ajca-v10'>";
                am += "<header class=\"tqr-dscvr-explr-art-hdr\">";
                am += "<div class=\"tqr-dscvr-explr-a-h _xl\">";
                am += "<a class=\"tqr-dscvr-explr-a-h-ubx jb-tqr-dscvr-ex-a-h-ubx\" href=\"/\">";
                am += "<span class=\"_box\">";
                am += "<span class=\"_ifd\"></span>";
                am += "<img class=\"_pflpic\" width=\"40\" height=\"40\" src=\"\" />";
                am += "</span>";
                am += "<span class=\"_pflpsd\" title=\"\"></span>";
                am += "</a>";
                am += "</div>";
                am += "<div class=\"tqr-dscvr-explr-a-h _xr\"></div>";
                am += "</header>";
                am += "<div class=\"tqr-dscvr-explr-art-bdy\">";
                am += "<figure>";
                am += "<figcaption class=\"tqr-dscvr-explr-art-dsc jb-tqr-dscvr-ex-a-dsc\"></figcaption>";
                am += "<a class=\"tqr-dscvr-explr-art-fks jb-tqr-dscvr-ax-a-fks\">";
                am += "<span class=\"fade\"></span>";
                am += "<img class=\"tqr-dscvr-explr-art-i jb-tqr-dscvr-ex-a-i\" height=\"250\" width=\"250\" src=\"\" />";
                am += "<div class=\"tqr-dscvr-explr-art-specs\">";
                am += "<span class=\"tqr-dscvr-explr-art-spbx jb-tqr-dscvr-ex-a-spbx\" data-scp=\"eval\">";
                am += "<span class=\"figure\">10</span>";
                am += "</span>";
                am += "<span class=\"tqr-dscvr-explr-art-spbx jb-tqr-dscvr-ex-a-spbx\" data-scp=\"react\">";
                am += "<span class=\"figure\">0</span>";
                am += "<span class=\"logo\" style=\"";
                am += "background: url('"+Kxlib_GetExtFileURL("sys_url_img","r/r3.png")+"') no-repeat;";
                am += "background-size: 100%;";
                am += "background-position: 0px 0px;";
                am += "\"></span>";
                am += "</span>";
                am += "</div>";
                am += "</a>";
                am += "</figure>";
                am += "<div></div>";
                am += "</div>";
                am += "</article>";
                am = $.parseHTML(am);
                
                /*
                 * ETAPE :
                 *      Insertion des données dans L'ENTENTE
                 */
                $(am)
                    .attr("id","post-tia-ex-aid-".concat(d.id))
                    .data("item",d.id).attr("data-item",d.id)
                    .data("time",d.time).attr("data-time",d.time)
                    .data("atype","tia-explr").attr("data-atype","tia-explr")
                    .data("istr",d.istrd).attr("data-istr",d.istrd)
                    .data("hatr",d.hatr).attr("data-hatr",d.hatr)
                    .data("hasfv",d.hasfv).attr("data-hasfv",d.hasfv)
                    .data("fvtp",d.fvtp).attr("data-fvtp",d.fvtp)
                    .data("with",str__).attr("data-with",str__)
                    .data("vidu",d.vidu).attr("data-vidu",d.vidu)
                    .data("ajcache",JSON.stringify(d)).attr("data-ajcache",JSON.stringify(d));
                
                
                /*
                 * ETAPE :
                 *      Insertions des données visible
                 */
                /* --- PROFILBOX --- */
                $(am).find(".jb-tqr-dscvr-ex-a-h-ubx").attr("href","/".concat(d.upsd));
                $(am).find(".jb-tqr-dscvr-ex-a-h-ubx ._pflpsd").text("@".concat(d.upsd));
                $(am).find(".jb-tqr-dscvr-ex-a-h-ubx ._pflpic").attr("src",d.uppic);
                /* --- ARTICLE --- */
                        
//                $(am).find(".jb-tqr-dscvr-ex-a-dsc").text(d.msg);
                
                var ustgs = ( d.ustgs ) ? d.ustgs : null;
                var hashs = ( d.hashs ) ? d.hashs : null;

                var txt = Kxlib_Decode_After_Encode(d.msg);

                //rtxt = RenderedText
                var rtxt = Kxlib_TextEmpow(txt,ustgs,hashs,null,{
                    emoji : {
                        "size"          : 36,
                        "size_css"      : 22,
                        "position_y"    : 3
                    }
                });

                $(am).find(".jb-tqr-dscvr-ex-a-dsc").text("").append(rtxt);
                
                /* ---- */

                $(am).find(".jb-tqr-dscvr-ex-a-i").attr("src",d.img);
                
                $(am).find(".jb-tqr-dscvr-ex-a-spbx[data-scp='eval'] .figure").text(d.eval[3]);
                $(am).find(".jb-tqr-dscvr-ex-a-spbx[data-scp='react'] .figure").text(d.rnb);
                 
                /*
                 * ETAPE :
                 *      
                 */
                if ( d.hasOwnProperty("vidu") && d.vidu ) {
                    $(am).find(".jb-tqr-dscvr-ax-a-fks .fade").addClass("vidu");
                }
                
                return am;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_RbdAr_XART = function (m) {
            try {
                if ( KgbLib_CheckNullity(m) ) {
                    return;
                }
                
                $(m).find(".jb-tqr-dscvr-ax-a-fks .fade").hover(function(e){
                    $(this).stop(true,true).animate({
                        "background-color" : "rgba(0,0,0,0.35)"
                    },300);
                },function(e){
                    $(this).stop(true,true).animate({
                        "background-color" : "rgba(0,0,0,0.1)"
                    },300);
                });
                
                $(m).find(".jb-tqr-dscvr-ax-a-fks").off("click").click(function(e){
                    Kxlib_PreventDefault(e);
                    (new Unique ()).OnOpen("tia-explr",this);
                });
                
                return m;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        /*************************************************************************** SECTION : TALKBOARD *************************************************************************/
        
        var _f_PprAr_XTST = function (d) {
            try {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                Kxlib_DebugVars([d,JSON.stringify(d)],true);
//                alert(d.id);
                
                var am = "<article class=\"tqr-dscvr-explr-tlbm-bmx jb-tqr-dscvr-ex-tkm-bmx\" data-item=\"\" data-time=\"\" data-ajcache=\"\" data-atype=\"tia-explr\">";
                am += "<header class=\"tqr-dcv-exp-tlbm-hdr-mx\">";
                am += "<div class=\"tqr-dcv-exp-tlbm-owbx-idnty\">";
                am += "<a class=\"tqr-dcv-exp-tlbm-owbx-mx jb-tqr-dcv-ex-t-obx-mx\" href=\"\" title=\"\">";
                am += "<span class=\"tqr-dcv-exp-tlbm-owbx-i-mx\">";
                am += "<img class=\"tqr-dcv-exp-tlbm-owbx-i jb-tqr-dcv-ex-t-obx-i\" width=\"45\" height=\"45\" src=\"\" />";
                am += "<span class=\"tqr-dcv-exp-tlbm-owbx-i-fd jb-tqr-dcv-exp-tm-ob-i-fd\"></span>";
                am += "</span>";
                am += "<span class=\"tqr-dcv-exp-tlbm-owbx-p jb-tqr-dcv-ex-t-obx-p\"></span>";
                am += "</a>";
                am += "<a class=\"tqr-dcv-exp-tlbm-tgbx jb-tqr-dcv-ex-t-tgbx\" href=\"\" title=\"\"></a>";
                am += "<span class=\"tqr-dcv-exp-tlbm-tm\">";
                am += "<span class=\"\">Il y a 2j</span>";
                am += "</span>";
                am += "</div>";
                am += "<div class=\"tqr-dcv-exp-tlbm-owbx-fn-mx\">";
                am += "<span class=\"tqr-dcv-exp-tlbm-owbx-fn jb-tqr-dcv-ex-t-obx-fn\"></span>";
                am += "</div>";
                am += "</header>";
                am += "<div class=\"tqr-dscvr-explr-tlbm-txt jb-tqr-dscvr-ex-t-txt\">";
                am += "<span class=\"tqr-dscvr-ex-t-t-txt jb-tqr-dscvr-ex-t-t-t\"></span>";
                am += "<a class=\"tqr-dscvr-ex-t-t-mr cursor-pointer jb-tqr-dscvr-ex-t-t-mr this_hide\" data-action=\"explr-post-react-open\">Afficher plus</a>";
                am += "</div>";
                am += "<footer class=\"tqr-dcv-exp-tlbm-ftr-mx\">";
                am += "<div class=\"tqr-dcv-exp-tlbm-opt-mx\">";
                am += "<div class=\"tqr-dcv-exp-tlbm-opt-reamx\">";
                am += "<a class=\"tqr-dcv-exp-tlbm-opt cursor-pointer like jb-tqr-dcv-exp-tlbm-opt\" data-action=\"explr-post-like\" data-actrvs=\"explr-post-unlike\" data-state=\"\">0</a>";
                am += "<a class=\"tqr-dcv-exp-tlbm-opt cursor-pointer react jb-tqr-dcv-exp-tlbm-opt\" data-action=\"explr-post-react-open\" data-state=\"\">0</a>";
                am += "</div>";
                am += "</div>";
                am += "</footer>";
                am += "</article>";
                
                am = $.parseHTML(am);
                
                /*
                 * ETAPE :
                 *      Insertion des données dans L'ENTENTE
                 */
                $(am)
                    .data("item",d.i).attr("data-item",d.i)
                    .data("time",d.tm).attr("data-time",d.tm);
                $(am).data("ajcache",JSON.stringify(d)).attr("data-ajcache",JSON.stringify(d));
                
                
                /*
                 * ETAPE :
                 *      Insertions des données visible
                 */
                /* --- TIME --- */
                
                /* --- OWNERBOX --- */
                $(am).find(".jb-tqr-dcv-ex-t-obx-mx").attr("href","/".concat(d.tg.opsd)).attr("title",d.tg.ofn);
                $(am).find(".jb-tqr-dcv-ex-t-obx-p").text("@".concat(d.au.opsd));
                $(am).find(".jb-tqr-dcv-ex-t-obx-fn").text(d.au.opsd);
                $(am).find(".jb-tqr-dcv-ex-t-obx-i").attr("src",d.au.oppic);
                /* --- TARGETBOX --- */
                if ( d.tg.opsd !== d.au.opsd ) {
                    $(am).find(".jb-tqr-dcv-ex-t-tgbx").attr("href","/".concat(d.tg.opsd)).attr("title",d.tg.ofn).text("@".concat(d.tg.opsd));
                } else {
                    $(am).find(".jb-tqr-dcv-ex-t-tgbx").remove();
                }
                
                /* --- ARTICLE : TEXT --- */
                $(am).data("text",d.m);
                var ftxt;
                if ( d.m.length > _f_Gdf().pstPvwLn ) {
                    var pcs = d.m.match(/^([\s\S]{1,200})(.+)$/i);
                    ftxt = Kxlib_TextEmpow(pcs[1].concat("..."),d.ustgs,d.hashs,null,{
                        "ena_inner_link" : {
//                            "local" : true, //DEV, DEBUG, TEST
                            "all"   : false,
                            "only"  : "fksa"
                        },
                        emoji : {
                            "size"      : 36,
                            "size_css"  : 22,
                            "position_y" : 3
                        }
                    });
                    $(am).find(".jb-tqr-dscvr-ex-t-t-t").text("").append(ftxt);

                    $(am).find(".jb-tqr-dscvr-ex-t-t-mr").removeClass("this_hide");
                } else {
                    ftxt = Kxlib_TextEmpow(d.m,d.ustgs,d.hashs,null,{
                        "ena_inner_link" : {
//                            "local" : true, //DEV, DEBUG, TEST
                            "all"   : false,
                            "only"  : "fksa"
                        },
                        emoji : {
                            "size"      : 36,
                            "size_css"  : 22,
                            "position_y" : 3
                        }
                    });
                    $(am).find(".jb-tqr-dscvr-ex-t-t-t").text("").append(ftxt);

                    $(am).find(".jb-tqr-dscvr-ex-t-t-mr").remove();
                }
            
                /* --- ARTICLE : OTHERS --- */
                $(am).find(".jb-tqr-dscvr-ex-a-i").attr("src",d.img);
                
                $(am).find(".jb-tqr-dcv-exp-tlbm-opt.like").text(d.cnlk);
                if ( d.hslk === true ) {
                    $(am).find(".jb-tqr-dcv-exp-tlbm-opt.like").data("action","explr-post-unlike").data("actrvs","explr-post-like").attr("data-state","me").data("state","me");
                }
                $(am).find(".jb-tqr-dcv-exp-tlbm-opt.react").text(d.rnb);
                
                return am;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_RbdAr_XTST = function (m) {
            try {
                if ( KgbLib_CheckNullity(m) ) {
                    return;
                }
                
                $(m).find(".jb-tqr-dscvr-ex-t-t-mr, .jb-tqr-dcv-exp-tlbm-opt.like, .jb-tqr-dcv-exp-tlbm-opt.react").click(function(e){
                    Kxlib_PreventDefault(e);

                    _f_Action(this);
                });
                
                return m;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        /*************************************************************************** SECTION : TRENDS *************************************************************************/
        
        var _f_PprAr_XTRD = function (d) {
            try {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                Kxlib_DebugVars([d,JSON.stringify(d)],true);
//                alert(d.id);


                var am = "<article class=\"tqr-dscvr-explr-trdmdl-bmx jb-tqr-dscvr-ex-trm-bmx\" data-item=\"\" data-time=\"\" data-tba=\"\" data-ajcache=\"\" >";
                am += "<header class=\"tqr-dscvr-explr-trdmdl-hdr-mx\">";
                am += "<div class=\"tqr-dscvr-explr-t-h-cvr-mx jb-tqr-dscvr-ex-t-h-cvr-mx\" style=\"";
                am += "background-repeat: no-repeat;";
                am += "\" >";
                am += "<div class=\"tqr-dscvr-explr-t-h-c-fd jb-tqr-dscvr-ex-t-h-c-fd\"></div>";
                am += "<a class=\"tqr-dscvr-explr-t-h-cvr jb-tqr-dscvr-explr-t-h-cvr\" href=\"\">";
                am += "<div class=\"tqr-dscvr-explr-t-h-c-inf-bk1\">";
                am += "<span class=\"tqr-dscvr-explr-t-h-c-i-bk1-ctg jb-tqr-dscvr-ex-t-h-c-i-bk1-ctg\"></span>";
                am += "</div>";
                am += "<div class=\"tqr-dscvr-explr-t-h-c-inf-bk2 jb-tqr-dscvr-ex-t-h-c-inf-bk2\"></div>";
                am += "</a>";
                am += "</div>";
                am += "<div class=\"tqr-dscvr-explr-t-h-smpl-mx\">";
//                am += "<a class=\"tqr-dscvr-explr-t-h-smpl-nb jb-tqr-dscvr-ex-t-h-smpl-nb\">+9 <i class=\"fa fa-picture-o\"></i></a>";
                am += "</div>";
                am += "</header>";
                am += "<div class=\"tqr-dscvr-explr-trdmdl-bdy-mx\">";
                am += "<div class=\"tqr-dvr-exp-trm-dsc jb-tqr-dvr-exp-trm-dsc\"></div>";
                am += "<div class=\"tqr-dvr-exp-trm-metas-mx jb-tqr-dvr-exp-trm-metas-mx\">";
                
                am += "<span class=\"tqr-dvr-exp-trm-metas jb-tqr-dvr-exp-trm-metas\" data-scp=\"posts\">";
                am += "<span><span class=\"_figure\"></span> Publications</span>";
                am += "</span>";
                am += "<span class=\"tqr-dvr-exp-trm-metas jb-tqr-dvr-exp-trm-metas\" data-scp=\"subs\">";
                am += "<span><span class=\"_figure\"></span> Abonnements</span>";
                am += "</span>";
                
                am += "</div>";
                am += "</div>";
                am += "</article>";
                
                am = $.parseHTML(am);
                
                /*
                 * ETAPE :
                 *      Insertion des données dans L'ENTENTE
                 */
                $(am).data("item",d.trd_eid).attr("data-item",d.trd_eid);
                $(am).data("time",d.trd_time).attr("data-time",d.trd_time);
                $(am).data("tba",d.tba).attr("data-tba",d.tba);
                $(am).data("ajcache",JSON.stringify(d)).attr("data-ajcache",JSON.stringify(d));
                
                /*
                 * ETAPE :
                 *      Insertions des données visibles
                 */
                $(am).find(".jb-tqr-dscvr-ex-t-h-c-inf-bk2").text(Kxlib_Decode_After_Encode(d.trd_tle));
                $(am).find(".jb-tqr-dscvr-ex-t-h-c-i-bk1-ctg").text(d.trd_catg);
                $(am).find(".jb-tqr-dvr-exp-trm-dsc").text(d.trd_desc);
                $(am).find(".jb-tqr-dvr-exp-trm-metas[data-scp='posts']").find("._figure").text(d.trd_posts_nb);
                $(am).find(".jb-tqr-dvr-exp-trm-metas[data-scp='subs']").find("._figure").text(d.trd_abos_nb);
                /* --- BACKGROUND --- */
                if (! KgbLib_CheckNullity(d.trd_cov_rp) ) {
                    /*
                     * ETAPE :
                     *      On effectue les calculs qui permettront de retrouver la même proportion que la couverture de la Tendance sur sa page.
                     */
                    var cf = _f_Gdf().tkm_cov_h/_f_Gdf().tkm_cov_w;
                    var t = (parseInt(d.trd_cov_t.toString().slice(0,-2))*cf)+5;
//                    Kxlib_DebugVars(["EXPLR : ",parseInt(d.trd_cov_w),cf,d.trd_cov_t,t]);
    
                    var cv = {
                        w : _f_Gdf().tkm_cov_w.toString().concat("px"),
                        h : "auto",
                        t : t
                    };
                    
                    $(am).find(".jb-tqr-dscvr-ex-t-h-cvr-mx").css({
                        "background-image"      : "url('"+d.trd_cov_rp+"')",
                        "background-size"       : cv.w.concat(" ",cv.h),
                        "background-position"   : "0px".concat(" ",cv.t,"px")
                    });
                } else {
                    var cv = {
                        w : _f_Gdf().tkm_cov_w,
                        h : _f_Gdf().tkm_cov_h
                    };
                    $(am).find(".jb-tqr-dscvr-ex-t-h-cvr-mx").css({
                        "background"            : "#43556D",
                        "background-size"       : cv.w.toString().concat("px"," ",cv.h,"px"),
                        "background-position"   : "0 0"
                    });
                }
                $(am).find(".jb-tqr-dscvr-explr-t-h-cvr").attr("href",d.trd_href).attr("title",d.trd_tle);
                


                return am;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_RbdAr_XTRD = function (m) {
            try {
                if ( KgbLib_CheckNullity(m) ) {
                    return;
                }
                
                $(m).find(".tqr-dscvr-picqr-mdl-fd").hover(function(e){
                    var b = $(this).closest(".jb-tqr-dscvr-picqr-mdl-mx");
                     b.find(".jb-tqr-dscvr-picqr-mdl-fd").stop(true,true).animate({
                        "background-color" : "rgba(0,0,0,0.25)"
                    },300);
                },function(e){
                    var b = $(this).closest(".jb-tqr-dscvr-picqr-mdl-mx");
                    b.find(".jb-tqr-dscvr-picqr-mdl-fd").stop(true,true).animate({
                        "background-color" : "rgba(0,0,0,0.1)"
                    },300);
                });
                
                return m;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        /***************************************************************************************************************************************************************************/
        
        var _f_Spnr = function (scp,cz,shw) {
            try {
                if ( KgbLib_CheckNullity(scp) ) {
                    return;
                }
                
                var $bmx, $spnr;
                scp = scp.toString().toUpperCase();
                switch (scp){
                    case "ARTICLES" :
                    case "SEC_TQR_ART" :
                            $bmx = $(".jb-tqr-dscvr-explr-sbs[data-name='articles']");
                        break;
                    case "TALKBOARD" :
                    case "SEC_TQR_TST" :
                            $bmx = $(".jb-tqr-dscvr-explr-sbs[data-name='talkboard']");
                        break;
                    case "TRENDS" :
                    case "SEC_TQR_TRD" :
                            $bmx = $(".jb-tqr-dscvr-explr-sbs[data-name='trends']");
                        break;
                    default :
                        return;
                }
                switch (cz) {
                    case "load_more" :
                            $spnr = $bmx.find(".jb-tqr-dscvr-explr-a-ldmr-a[data-action='explr-load-more']");
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

        /*
        $(".jb-tqr-dscvr-ax-a-fks .fade").hover(function(e){
            $(this).stop(true,true).animate({
                "background-color" : "rgba(0,0,0,0.35)"
            },300);
        },function(e){
            $(this).stop(true,true).animate({
                "background-color" : "rgba(0,0,0,0.1)"
            },300);
        });
        //*/
        
        $(".jb-tqr-dscvr-explr-a-ldmr-a, .jb-tqr-dscvr-explr-s-bckth, .jb-tqr-dscvr-scrt-r-m-x-d-btn, .jb-tqr-dscvr-scrt-r-m-x-d-c-a").click(function(e){
            Kxlib_PreventDefault(e);
            
            _f_Action(this);
        });
        
        /* SPECIFIC TO TESTIES */
        $(".jb-tqr-dscvr-ex-t-t-mr, .jb-tqr-dcv-exp-tlbm-opt.like, .jb-tqr-dcv-exp-tlbm-opt.react").click(function(e){
            Kxlib_PreventDefault(e);
            
            _f_Action(this);
        });


        /*******************************************************************************************************************************************************************/
        /**************************************************************************** INNIT SCOPE **************************************************************************/
        /*******************************************************************************************************************************************************************/
        $(".jb-tqr-dscvr-ex-a-l").masonry({
            // options
            itemSelector    : '.jb-tqr-dscvr-ex-a-bmx',
            columnWidth     : 250,
            isFitWidth      : true,
            "gutter"        : 30
        });
        $(".jb-tqr-dscvr-ex-tkm-l").masonry({
            // options
            itemSelector    : '.jb-tqr-dscvr-ex-tkm-bmx',
            columnWidth     : 300,
            isFitWidth      : true,
            "gutter"        : 10
        });
        $(".jb-tqr-dscvr-ex-trm-l").masonry({
            // options
            itemSelector    : '.jb-tqr-dscvr-ex-trm-bmx',
            columnWidth     : 380,
            isFitWidth      : true,
            "gutter"        : 40
        });
                                                                                                                                                                                 
        /*
        $(".jb-tqr-dscvr-explr-clcmdl-lst").masonry({
            // options
            itemSelector    : '.jb-tqr-dscvr-explr-clcmdl-bmx',
            columnWidth     : 460,
            isFitWidth      : true,
            "gutter"        : 40
        });
        //*/
                                                                                                    
        _f_Init();
    };
});
