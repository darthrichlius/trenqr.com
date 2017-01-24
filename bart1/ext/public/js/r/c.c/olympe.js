/** 
 * Premier fichier à être appelé. Cependant, il est placé après le fichier des librairies.
 * Il permet de définir le cadre de travail de la sesion.
 * Il gère les cookies 
 */
// Pour les tests
/*
EraseCookie ("fulln", "Lou Carther",1);
EraseCookie ("psd", "@IamLouCarther",1);
*/
/*
var url = "http://127.0.0.1/korgb/ajax_test.php";

var onsuccess = function (data) {
    /*
    fn = data.fulname;
    psd = data.pseudo;
    alert("FULL NAME = "+fn+"\n"+"PSEUDO = "+psd);
    
    if(! KgbLib_CheckNullity(data.err) ) 
        alert(data.err);
    
    alert(data);
};

var onerror = function(a,b,c) {
    alert("OLYMPE : Error");
};

var toSend = {
    "urqid": "add_r_in_arp",
    "datas": {
        "msg": "Un tout nouveau commentaire"
    }
};


Kx_XHR_Send(toSend, "post", url, onerror, onsuccess);
*/
window.rm = undefined;
//Le RUNNING_MODE de la Session active au niveau FrontEnd
window.rm = "DEV"; // DEV, TEST, PROD, DEBUG


function Breathing () {
    
    
    var _xhr_sts_upd;

    /*******************************************************************************************************************************************************************/
    /**************************************************************************** PROCESS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/


    var _f_Gdf = function () {
        var dt = {
            "ivl_reftm" : 4850 //PROD
//            "ivl_reftm" : 1550 //DEV, DEBUG, TEST
        }; 

        return dt;
    };


    var _f_Init = function () {
        try {
        
            if ( $.inArray(PgEnv.pgvr.toUpperCase(),["WU","WLC"]) !== -1 ) {
                return;
            }
            
            /*
             * ETAPE :
             *      On ajoute le TIMESTAMP de début
             */
            var nw = new Date().getTime();
            var blp = $("<span/>")
                .addClass("jb-bob-leponge this_hide")
                .data("start",nw)
                .attr("data-start",nw)
            $("body").prepend(blp);

            setInterval(function(){
                var nw = new Date().getTime(), sts;
                
                if (! KgbLib_CheckNullity(_xhr_sts_upd) ) {
                    Kxlib_DebugVars(["BREATHING : ",nw,window.hasfocus,"CANCELLED_POINTER"]);
                    return;
                }
                
                //start : STARTime
                var start = ( $(".jb-bob-leponge").data("start") ) ? $(".jb-bob-leponge").data("start") : 0;
                //lachk : LAstCHeck
                var lachk = ( $(".jb-bob-leponge").data("lachk") ) ? $(".jb-bob-leponge").data("lachk") : 0;
                var lachk_sts = ( $(".jb-bob-leponge").data("lachk_sts") ) ? $(".jb-bob-leponge").data("lachk_sts") : null;
                //lafk : LAstFocus
                var lafk = ( $(".jb-bob-leponge").data("lafk") ) ? $(".jb-bob-leponge").data("lafk") : 0;
                
               /*
                * LEXIQUE pour STS
                *      _CODE_2 : Présent
                *      _CODE_1 : Pas Présent mais était recemment là
                *      _CODE_0 : Pas Présent
                */
                    
                if ( window.hasfocus ) {
                    sts = "_CODE_2";
                    Kxlib_DebugVars(["BREATHING : ",nw,window.hasfocus,"_CODE_2"]);
                } else {

                    /*
                     * ETAPE :
                     *      Si cela arrive alors :
                     *          1- Dysfonctionnement
                     *          2- USER a HACK
                     */
                    if (! start ) {
                        return;
                    }

                    var start_dff = nw - start;
                    var lafk_dff = nw - lafk;

                    if ( !lachk ) {
                        sts = "_CODE_0";
                        Kxlib_DebugVars(["BREATHING : ",nw,window.hasfocus,"_CODE_0"]);
                    } else if ( lachk && !lafk ) {
                        sts = "_CODE_0";
                        Kxlib_DebugVars(["BREATHING : ",nw,window.hasfocus,"_CODE_0"]);
                    } else if ( lachk && lafk && lafk_dff > _f_Gdf().ivl_reftm ) {
                        sts = "_CODE_0";
                        Kxlib_DebugVars(["BREATHING : ",nw,window.hasfocus,"_CODE_0"]);
                    } else if ( lachk && lafk && lafk_dff <= _f_Gdf().ivl_reftm ) {
                        sts = "_CODE_1";
                        Kxlib_DebugVars(["BREATHING : ",nw,window.hasfocus,"_CODE_1"]);
                    }
                }
                
                $(".jb-bob-leponge").data("lachk",nw);
                $(".jb-bob-leponge").data("lachk_sts",sts);
                
                /*
                 * ETAPE :
                 *      On ne signale au SERVEUR que les changements de STS.
                 *      C'est la méthode idéale car elle ne sature pas le SERVEUR et tout en restant précise !
                 */
                if ( lachk_sts !== sts ) {
                    Kxlib_DebugVars(["BREATHING (REPORT) : ",nw,window.hasfocus,"OLD_CODE",lachk_sts,"NEW_CODE",sts]);
                    var s = $("<span/>");
                    _f_Srv_Report(sts,nw,s);
                }

            },_f_Gdf().ivl_reftm);

        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
        
    
    /****************************************************************************************************************************************************************************/
    /******************************************************************************* SERVER SCOPE *******************************************************************************/
    /****************************************************************************************************************************************************************************/
    
    
    var _Ax_Report = Kxlib_GetAjaxRules("TQR_EVT_PGTKFKS");
    var _f_Srv_Report = function (sts,nw,s) {
        if ( KgbLib_CheckNullity(sts) | KgbLib_CheckNullity(nw) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
               if(! KgbLib_CheckNullity(datas.err) ) {
                   
                   //On unlock le bouton
                    $(x).data("lk",0);
                    //On fait disapparaitre le spinner
                    _f_IptWdwSpnr();
                    //On masque la fenetre d'attente
                    _f_IptWdwWtPan();
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break
                            case "__ERR_VOL_DENY":
                                    Kxlib_AJAX_HandleDeny();
                                break
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    }
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
//                    var rds = [datas.return];
//                    $(s).trigger("datasready",rds);
                    _xhr_sts_upd = null;
                } else {
                    _xhr_sts_upd = null;
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            
            return;
        };
        
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_Report.urqid,
            "datas": {
                "sts"   : sts,
                "cu"    : curl
            }
        };
        
        _xhr_sts_upd = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_Report.url, wcrdtl : _Ax_Report.wcrdtl });
    };
        
    
    /*********************************************************************************************************************************************************/
    /*********************************************************************** INIT SCOPE **********************************************************************/
    /*********************************************************************************************************************************************************/
    
    _f_Init();
}

new Breathing();