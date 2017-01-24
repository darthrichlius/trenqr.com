
define("sapp/sapp-main", function (require) {
    /*
    var secret = require("sapp/sapp-secret");
    var explorer = require("sapp/sapp-explorer");
    //*/
    
    var opnrBtn = $("#header-logo-discover");
    var clzrBtn = $("#tqr-dscvr-clz");
    var mnBtn = $(".jb-tqr-dscvr-menu-tgr");
    
    /*******************************************************************************************************************************************************************/
    /**************************************************************************** PROCESS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    var _f_Init = function () {
        try {
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
                case "open" :
                        _f_Opn(x,_a);
                    break;
                case "close" :
                        _f_Clz(x,_a);
                    break;
                case "menu" :
                        _f_Mn(x,_a);
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
            
            var mode = "_MD_DECORA";
            var s = $("<span/>");
            
            _f_Srv_PlDecora(mode,s);
            
            $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                Kxlib_DebugVars([JSON.stringify(d)],true);
                
                var fn = d.ufn;
                var ps = d.upsd;
                var hr = d.uhref;
                var im = d.img;
                var prm = d.prmlk;
                
                $(".jb-tqr-dscvr-gbl-s-hdr").css({
                    "background-image"      : "url('"+im+"')",
                    "background-position"   : "0 40%",
                    "background-size"       : "100%",
                    "background-repeat"     : "no-repeat", 
                });
                
                $(".jb-bckgrd-im-ownr-prmlk").attr({
                    href: "//".concat(prm),
                });
                
                $(".jb-bckgrd-im-ownr-hrf").attr({
                    href: hr,
                }).text("@".concat(ps));
                
            });
            
            $(s).on("operended",function(e){
                
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Opn = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            $("html").data("state",'discovery').attr("data-state",'discovery');
            $(".jb-tqr-dscvr-sprt").stop(true,true).removeClass("this_hide").animate({
                left : "0px"
            });
            
            _f_RfrshGrid();
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Clz = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            $(".jb-tqr-dscvr-sprt").stop(true,true).animate({
                left : "-250px"
            },function(){
                $(this).addClass("this_hide");
            });
            $("html").data("state",'').attr("data-state",'');
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Mn = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            var trgt = $(x).data("target");
            
            $(".jb-tqr-dscvr-gbl-sect.active").addClass("this_hide").removeClass("active");
            $(".jb-tqr-dscvr-gbl-sect[data-name='"+trgt+"']").removeClass("this_hide").addClass("active");
            
            _f_RfrshGrid(trgt);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_RfrshGrid = function (t) {
        try {
            if (! KgbLib_CheckNullity(t) ) {
            
                switch (t){
                    case "explorer" :
                            $(".jb-tqr-dscvr-ex-a-l").masonry();
                            $(".jb-tqr-dscvr-ex-tkm-l").masonry();
                            $(".jb-tqr-dscvr-ex-trm-l").masonry();
                            $(".jb-tqr-dscvr-explr-clcmdl-lst").masonry();
                        break;
                    case "mystery" :
                        break;
                    case "picqr" :
                            $(".jb-tqr-d-p-mdl-lst-mx").masonry("reloadItems");
                            $(".jb-tqr-d-p-mdl-lst-mx").masonry("layout");
                        break;
                    default:
                        return;
                }
            } else {
                /*
                 * EXPLORER 
                 */
                $(".jb-tqr-dscvr-ex-a-l").masonry();
                $(".jb-tqr-dscvr-ex-tkm-l").masonry();
                $(".jb-tqr-dscvr-ex-trm-l").masonry();
                $(".jb-tqr-dscvr-explr-clcmdl-lst").masonry();
                /*
                 * PICQR 
                 */        
                $(".jb-tqr-d-p-mdl-lst-mx").masonry("reloadItems");
                $(".jb-tqr-d-p-mdl-lst-mx").masonry("layout");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** SERVERS SCOPE *************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _Ax_PlDecora = Kxlib_GetAjaxRules("TQR_SUGG_GT_DCORA");
    var _f_Srv_PlDecora = function(mode,s) {
        if ( KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
//            alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    
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
                            default :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) && ( !KgbLib_CheckNullity(datas.return.decora) ) ) {
                    var ds = [datas.return.decora];
                    $(s).trigger("datasready",ds);
                } else {
                    $(s).trigger("operended");
                    return;
                }
            } catch (ex) {
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
            "urqid": _Ax_PlDecora.urqid,
            "datas": {
                "mode"  : ( mode ) ? mode : null,
                "cu"    : curl
            }
        };
        
        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlDecora.url, wcrdtl : _Ax_PlDecora.wcrdtl });
    };
    
    /*******************************************************************************************************************************************************************/
    /***************************************************************************** VIEW SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** LISTENERS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    opnrBtn.on({
        click : function(e){
            Kxlib_PreventDefault(e);
            
            if ( require.specified("sapp/sapp-secret") ) {
//                alert("Chargé !");
                _f_Action(this);
            } else {
//                alert("Pas chargé !");
                require(["sapp/sapp-secret","sapp/sapp-explorer","sapp/sapp-picqr","sapp/sapp-favlinks","r/c.c/cf.min","r/c.c/crpr.min"],function(secret,explorer,picqr,favlinks){
                    if (! $(".jb-tqr-dscvr-gbl-sect").not(".this_hide").length ) {
                        $(".jb-tqr-dscvr-bdy-wtmx").addClass("this_hide");
                        $(".jb-tqr-dscvr-gbl-sect[data-name='explorer']").addClass("active").removeClass("this_hide");
                        
                        secret();
                        explorer();
                        picqr();
                        var LIFA = new favlinks();
                        LIFA.Start();
                        
                        /*
                         * [DEPUIS 15-07-16]
                         *      On ajoute les fichiers liés à TQS :
                         *          - cf.min (CARMAN FULL)
                         *          - crpr.min (CROPPER)
                         *          - tqs.m 
                         */
//                        require(["r/c.c/cf.min"]);
//                        require(["r/c.c/crpr.min"]);
                        require(["r/s/tqs.m"]);

                    }
                });
                _f_Action(this);
            }
//            require(["sapp/sapp-secret"],function(){
//                _f_Action(this);
//            });
            
        }
    });
    
    clzrBtn.on({
        click : function(e){
            Kxlib_PreventDefault(e);
            
            _f_Action(this);
        }
    });
    
    mnBtn.on({
        click : function(e){
            Kxlib_PreventDefault(e);
            
            _f_Action(this);
        }
    });
    
    
    /*******************************************************************************************************************************************************************/
    /**************************************************************************** INNIT SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    _f_Init();
});
