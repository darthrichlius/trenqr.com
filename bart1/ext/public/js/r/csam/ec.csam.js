
(function(){
   
    var _f_Gdf = function () {
        var dp = {
//            ""    : 
        };
        
        return dp;
    };
    
    /*******************************************************************************************************************************************************************************************/
    /************************************************************************************** PROCESS SCOPE **************************************************************************************/
    /*******************************************************************************************************************************************************************************************/
    
    var _f_Action = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            
            var a = $(x).data("action");
            switch (a) {
                case "renew-email" :
                        _f_RnwEml(x);
                    break;
                default :
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    var _f_RnwEml = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            /*
             * On vérifie que le bouton n'est pas LOCK
             */
            if ( $(x).data("lk") === 0 ) {
                return;
            }
            $(x).data("lk",1);
            
            /*
             * ETAPE :
             *      On récupère la clé de l'opération en cours
             */
            var k = $(".jb-tqr-cnfrm-eml-bmx").data("eckey");
            if (! k ) {
                return;
            }
            
            /*
             * ETAPE :
             *      On affiche l'indicateur qui demande de patienter
             */
            if ( $(".jb-pg-sts").length ) {
                $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Wait3p"));
                $(".jb-pg-sts").removeClass("this_hide");
            } 
            
            /*
             * ETAPE :
             *      Dans tous les cas, on fait tout pour décourager l'utilisateur d'effectuer d'autres actions.
             */
            $(".jb-tqr-cnfrm-fnl-dec").removeAttr("data-action").removeProp("data-action");
            $(".jb-tqr-cnfrm-fnl-dec").removeAttr("href");
            $(".jb-tqr-cnfrm-fnl-dec").data("lk",1);
                
            /*
             * ETAPE :
             *      On lance l'opération au niveau du serveur
             */
            var s = $("<span/>");
            _f_Srv_EcRedo(k,s);
            
            $(s).on("operended", function(e,d){
                $(".jb-tqr-cnfrm-eml-sctn[data-section='main']").addClass("this_hide");
                $(".jb-tqr-cnfrm-eml-sctn[data-section='sent']").removeClass("this_hide");
                
                /*
                 * ETAPE :
                 *      On recharge la page en affichant au préalable la message d'attente
                 *      
                 *  [DEPUIS 24-11-15] @author BOR
                 *      Je ne comprends pas l'intêtet car :
                 *          (1) Il n'y a aucune image de profil affichée
                 *          (2) Le comportement logique serait que l'utilisateur RELOAD la page 
                 */
                if ( $(".jb-pg-sts").length ) {
                    $(".jb-pg-sts").addClass("this_hide"); 
                    $(".jb-pg-sts-txt").text("");
                }
                /*
                if ( $(".jb-pg-sts").length ) {
                    /*
                     * Permet de le faire disparaitre un moment pour attirer l'attention de l'utilisateur.
                     * Meme si la vitesse sera telle qu'il est fort propable qu'il ne voit rien.
                     *
                    $(".jb-pg-sts").addClass("this_hide"); 
                    $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Loading")+"...");
                    $(".jb-pg-sts").removeClass("this_hide");
                }
                /*
                setTimeout(function() {
                    /*
                     * On est obligé de RELOAD la page car il faut changer TOUTES les images de profil affichées.
                     * Le plus simple est donc de recharger le page. L'autre avantage est de mettre à jour toutes les autres données.
                     *
                    location.reload();
                }, 10000);
                //*/
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    /*******************************************************************************************************************************************************************************************/
    /************************************************************************************** SERVER SCOPE ***************************************************************************************/
    /*******************************************************************************************************************************************************************************************/
    
    var _Ax_EcRedo = Kxlib_GetAjaxRules("TQR_EC_REDO");
    var _f_Srv_EcRedo = function(k,s) {
        
        if ( KgbLib_CheckNullity(k) | KgbLib_CheckNullity(s) ) {
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
                            case "__ERR_VOL_NOT_FND" :
                            case "__ERR_VOL_MATCHING" :
                            case "__ERR_VOL_NOT_XPTD" :
                                    Kxlib_AJAX_HandleFailed();
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
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    $(s).trigger("operended");
                } else {
                    Kxlib_AJAX_HandleFailed();
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
            "urqid": _Ax_EcRedo.urqid,
            "datas": {
                "k": k,
                "cu": curl
            }
        };
        
        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_EcRedo.url, wcrdtl : _Ax_EcRedo.wcrdtl });
    };
    
    /*******************************************************************************************************************************************************************************************/
    /**************************************************************************************** VIEW SCOPE  **************************************************************************************/
    /*******************************************************************************************************************************************************************************************/
    
    $(".jb-tqr-cnfrm-fnl-dec[data-action='renew-email']").off("click").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
})();

