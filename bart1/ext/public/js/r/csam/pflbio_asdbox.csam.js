/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function ProfilBio () {
    var gt = this;
    var _MAX_CHAR = 140;
//    this.__MAX_CHAR = 140;
    
    /*********************************************************************************************************************************/
    /********************************************************* PROCESS SCOPE *********************************************************/
    /*********************************************************************************************************************************/
    
    var _f_Keys = function (e) {
//    this.ControlKeys = function (e) {
        
        if ( e.which === 27 ){
            _f_ClzAkx(null,e);
        } else if ( e.which === 13 ) {
            _f_ClzAkx(e.type,e);
        }
        
        if ( e.which !== 8 && e.which !== 46 && !e.ctrlKey && $(e.target).html().length > _MAX_CHAR ) {
            Kxlib_PreventDefault(e);
        }
    };
    
    /**********************************************************************************************************************************/
    /********************************************************** SERVER SCOPE **********************************************************/
    /**********************************************************************************************************************************/
    
    var _Ax_SaveBio = Kxlib_GetAjaxRules("SAVE_USER_BIO", Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_SaveBio = function(b,w,s) {
//    this._Srv_SaveBio = function(d) {
        
        //RAPPEL : On ne vérifie pas que le texte est plein. En effet, l'utilisateur peut vouloir ne rien avoir dans sa bio
        if ( typeof b === "undefined" | typeof b !== "string" | typeof w === "undefined" | typeof w !== "string" | KgbLib_CheckNullity(s) ) {
            $(".jb-pfl-bio-own-a-chcs").data("lk",0);
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
                    $(".jb-pfl-bio-own-a-chcs").data("lk",0);
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_IMG_NOT_COMPLY":
                                break;
                            case "__ERR_VOL_BIO_MSM_RULES" : //[DEPUIS 20-06-15] @BOR
                                    Kxlib_AJAX_HandleFailed("ERR_VOL_BIO_MSM_RULES");
                                break;
                            case "__ERR_VOL_UWSBT_MSM_RULES" : //[DEPUIS 20-06-15] @BOR
                                    Kxlib_AJAX_HandleFailed("ERR_VOL_UWSBT_MSM_RULES");
                                break;
                            case "__ERR_VOL_UWSBT_MSM_LEN" : //[DEPUIS 20-06-15] @BOR
                                    Kxlib_AJAX_HandleFailed("ERR_VOL_UWSBT_MSM_LEN");
                                break;
                            case "__ERR_VOL_UWSBT_MSM_FRMT" : //[DEPUIS 20-06-15] @BOR
                                    Kxlib_AJAX_HandleFailed("ERR_VOL_UWSBT_MSM_FRMT");
                                break;
                            case "__ERR_VOL_FAILED":
                                    //TODO : Retirer l'image en Buffer
                                    //Afficher le message adéquat dans la zone d'erreur
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) && datas.return.hasOwnProperty("bio") && datas.return.hasOwnProperty("wbst")
                    && ( typeof datas.return.bio !== "undefined" | ( typeof datas.return.bio === "string" && datas.return.bio.length <= _MAX_CHAR ) )
                    && ( typeof datas.return.wbst !== "undefined" | ( typeof datas.return.wbst === "string" ) ) 
                ) {
                    /*
                     * La vérification au niveau de la taille nous permet entre autres de nous prémunir d'un retour d'erreur qu'on ne voudrait pas afficher.
                     * De plus, cela garantie encore plus la sécurité et la fiabilité du produit pour l'utilisateur (d'une manière ou d'une autre)
                     */
                    
                    /*
                     * [NOTE] 
                     *  -> Le serveur peut nous renvoyer une chaine vide si l'utilisateur a envoyé une chaine vide
                     */
                    $(s).trigger("datasready",datas.return);
                    
                } else {
                    /*
                     * [DEPUIS 19-06-15] @BOR
                     */
                    Kxlib_AJAX_HandleFailed();
                    return;
                }
                
            } catch (ex) {
                //TODO : Renvoyer l'erreur au serveur
                $(".jb-pfl-bio-own-a-chcs").data("lk",0);
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
            $(".jb-pfl-bio-own-a-chcs").data("lk",0);
//            Kxlib_DebugVars(["AJAX ERR : "+nwtrdart_uq],true);
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_SaveBio.urqid,
            "datas": {
                "b": b,
                "w": w,
                "u": curl
            }
        };
        
        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_SaveBio.url, wcrdtl : _Ax_SaveBio.wcrdtl });
    };
    
    /**********************************************************************************************************************************/
    /*********************************************************** VIEW SCOPE ***********************************************************/
    /**********************************************************************************************************************************/
    
    var _f_ChkBioSts = function () {
//    this.ChkBioSts = function () {
        if ( $(".jb-pfl-bio-bio").html() === "" ) {
            $(".jb-pflbio-own-a-trg").show();
        } else {
            $(".jb-pflbio-own-a-trg").hide();
        }
    };
    
    var _f_HdleFnlDec = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            
            var a = $(x).data("action");
            switch (a) {
                case "abort" : 
                        _f_ClzAkx(null,null,true);
                    break;
                case "save" : 
                        _f_ClzAkx(null,null);
                    break;
                default : 
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_OpAkx = function () {
//    this.OpenAccess = function () {
        try {
            
            var bio = $(".jb-pfl-bio-bio").text();
            $(".jb-pflbio-bio-txta").val(bio);
            
            /*
             * [DEPUIS 19-06-15] @BOR
             */
            var ws = $(".jb-pfl-bio-ownr-wbst-hrf").data("website");
            $(".jb-pfl-bio-ownr-wbst-ipt").val(ws);
            
            /*
             * [DEPUIS 22-06-15] @BOR
             * On vérifie si on est dans le cas de "silent man"
             */
//            Kxlib_DebugVars([$(".jb-big-pfl-asd-box").data("silent-stt"), typeof $(".jb-big-pfl-asd-box").data("silent-stt")],true);
//            if ( $(".jb-big-pfl-asd-box").hasClass("silent") && $(".jb-big-pfl-asd-box").data("silent-stt") === 1 ) {
            if ( true) {
                $(".jb-pflbio-silentman-mx").addClass("this_hide");
                $(".jb-pflbio").removeClass("this_hide");
                $(".jb-pfl-bio-o-w-bmx").removeClass("this_hide");
                
                $(".jb-big-pfl-asd-box").removeClass("sa sb sw");
            }
            
            $(".jb-pfl-bio-bio").addClass("this_hide");
            $(".jb-pfl-bio-sep").addClass("this_hide");
            $(".jb-pfl-bio-author").addClass("this_hide");
            $(".jb-pflbio-bio-txta").removeClass("this_hide");
            
            /*
             * [DEPUIS 19-06-15] @BOR
             */
            $(".jb-pfl-bio-ownr-wbst-mx").addClass("this_hide");
            $(".jb-pfl-bio-ownr-wbst-ipt-mx").removeClass("this_hide");
            
            /*
             * [DEPUIS 19-06-15] @BOR
             */
            $(".jb-pflbio").data("state","open");
            $(".jb-pflbio-own-a-trg").addClass("this_hide");
            $(".jb-pfl-bio-own-a-chcs-mx").removeClass("this_hide");
            
            
            $(".jb-pflbio-bio-txta").focus();
            
            var old = $(".jb-pfl-bio-bio").html();
            $(".jb-pfl-bio-bio").data("old", old);
            
            var old = $(".jb-pfl-bio-bio").text();
            $(".jb-pfl-bio-bio").data("old", old);
            
            $(".jb-pflbio").data("gs", '1');
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /**
     * 
     * @param {type} et
     * @param {type} e
     * @param {type} isab IsAbort
     * @returns {undefined}
     */
    var _f_ClzAkx = function (et,e,isab) {
//    this.CloseAccess = function (et,e) {
        try {
            
            /*
             * On prend n'importe lequel des deux boutons car si l'un est lock, l'autre l'est tout autant
             */
            var $b__ = $(".jb-pfl-bio-own-a-chcs[data-action='save']");
            if ( !isab && !KgbLib_CheckNullity($b__) && $b__.length && $b__.data("lk") === 1 ) {
//                Kxlib_DebugVars([Is locked Pflbio Final Buttons !"]);
                return;
            }
            
            //cd = code
            var cd;
            if ( !KgbLib_CheckNullity(e) ) {
                cd = ( e.keyCode ) ? e.keyCode : e.which;
            }
//            var cd = ( e.keyCode ) ? e.keyCode : e.which;
            
            if ( !KgbLib_CheckNullity(et) && et !== "blur" ) {
                $(".jb-pflbio-bio-txta").blur();
            }
            
            var bio, wbst; 
            if ( ( !KgbLib_CheckNullity(e) && !KgbLib_CheckNullity(cd) && cd === 27 ) | isab === true ) {
                var old = $(".jb-pfl-bio-bio").data("old");
                $(".jb-pfl-bio-bio").text(old);
                
                $(".jb-pflbio").data("gs", '0');
            } else {
                bio = $(".jb-pflbio-bio-txta").val();
                wbst = $(".jb-pfl-bio-ownr-wbst-ipt").val();
                
//                $(".jb-pfl-bio-bio").text(bio); // [DEPUIS 19-06-15]
            }
            
            /*
             * [DEPUIS 19-06-15] @BOR
             * Devenu obselète !
             */
//        if ( $(".jb-pfl-bio-bio").html() === "" ) {
//            _f_ChkBioSts();
//        }
            
            var gs = $(".jb-pflbio").data("gs");
            if ( KgbLib_CheckNullity(gs) | gs !== '1' ) {
                
                $(".jb-pflbio-bio-txta").addClass("this_hide");
                $(".jb-pfl-bio-bio").removeClass("this_hide");
                $(".jb-pfl-bio-sep").removeClass("this_hide");
                $(".jb-pfl-bio-author").removeClass("this_hide");

                /*
                 * [DEPUIS 19-06-15] @BOR
                 */
                $(".jb-pfl-bio-ownr-wbst-ipt-mx").addClass("this_hide");
                $(".jb-pfl-bio-ownr-wbst-mx").removeClass("this_hide");

                /*
                 * [DEPUIS 19-06-15] @BOR
                 */
                $(".jb-pflbio").data("state","close");
                $(".jb-pfl-bio-own-a-chcs-mx").addClass("this_hide");
                $(".jb-pflbio-own-a-trg").removeClass("this_hide");
                
                /*
                 * [DEPUIS 23-06-15] @BOR
                 * On vérifie si on est dans le cas de "silent man"
                 */
                if ( !KgbLib_CheckNullity($(".jb-big-pfl-asd-box").data("silent-stt")) && typeof $(".jb-big-pfl-asd-box").data("silent-stt") === "string" ) {
                    var ss = $(".jb-big-pfl-asd-box").data("silent-stt").toLowerCase();
                    switch (ss) {
                        case "sa" :
                                $(".jb-pfl-bio-o-w-bmx").addClass("this_hide");
                                $(".jb-pflbio").addClass("this_hide");
                                $(".jb-pflbio-silentman-mx").removeClass("this_hide");
                                $(".jb-big-pfl-asd-box").addClass("sa");
                            break;
                        case "sb" :
                                $(".jb-pfl-bio-o-w-bmx").removeClass("this_hide");
                                $(".jb-pflbio").addClass("this_hide");
                                $(".jb-pflbio-silentman-mx").addClass("this_hide");
                                $(".jb-big-pfl-asd-box").addClass("sb");
                            break;
                        case "sw" :
                                $(".jb-pfl-bio-o-w-bmx").addClass("this_hide");
                                $(".jb-pflbio").removeClass("this_hide");
                                $(".jb-pflbio-silentman-mx").addClass("this_hide");
                                $(".jb-big-pfl-asd-box").addClass("sw");
                            break;
                        default :
                                
                            break;
                    }
                    
                }
                
                return;
            } 
            
            /*
             * [DEPUIS 20-06-15] @BOR
             * On lock les boutons en attendant la réponse du serveur
             */
            $(".jb-pfl-bio-own-a-chcs").data("lk",1);
            
//            Kxlib_DebugVars([bio,wbst],true);
//            return;
            /*
             * On transmet au serveur.
             */
            var s = $("<span/>");
            _f_Srv_SaveBio(bio,wbst,s);
            
            $(s).on("datasready",function(e,d){
                
                /*
                 * On insère le texte bio tel qu'il a été enregistré par le serveur
                 */
                $(".jb-pfl-bio-bio").html(d.bio);
                        
                /*
                 * On insère les données relatifs au site web de l'utilisateur.
                 */
                
                //ETAPE : Insertion de la données visible
                var w__ = d.wbst.replace(/^https?:\/\/(?:www\.)?/i, "");
                w__ = w__.replace(/^www\./i, "");
                $(".jb-pfl-bio-ownr-wbst-hrf").text(w__);
                
                //ETAPE : Insertion les données meta
                var mw__ = ( !KgbLib_CheckNullity(d.wbst) && !d.wbst.match(/^https?:\/\//i) ) ? "http://"+d.wbst : d.wbst;
                $(".jb-pfl-bio-ownr-wbst-hrf").prop({
                    "href" : mw__,
                    "title" : mw__
                }).data("website",mw__); 
                
                /*
                 * On affiche la notification
                 */
                var Nty = new Notifyzing ();
                Nty.FromUserAction("ua_pflbio_set");
                
                if ( !KgbLib_CheckNullity(d.bio) && !KgbLib_CheckNullity(d.wbst) ) {
                    /*
                     * On fait revenir les champs à l'état "readonly".
                     */
                    $(".jb-pflbio-bio-txta").addClass("this_hide");
                    $(".jb-pfl-bio-bio").removeClass("this_hide");
                    $(".jb-pfl-bio-sep").removeClass("this_hide");
                    $(".jb-pfl-bio-author").removeClass("this_hide");

                    /*
                     * [DEPUIS 19-06-15] @BOR
                     */
                    $(".jb-pfl-bio-ownr-wbst-ipt-mx").addClass("this_hide");
                    $(".jb-pfl-bio-ownr-wbst-mx").removeClass("this_hide");

                    /*
                     * [DEPUIS 19-06-15] @BOR
                     */
                    $(".jb-pflbio").data("state","close");
                    $(".jb-pfl-bio-own-a-chcs-mx").addClass("this_hide");
                    $(".jb-pflbio-own-a-trg").removeClass("this_hide");

                    //On unlock les boutons
                    $(".jb-pfl-bio-own-a-chcs").data("lk",0);
    //                Kxlib_DebugVars([Unlocked Pflbio Final Buttons !"]);
                    
                    //On retirer les classes de type "silent"
                    $(".jb-big-pfl-asd-box").removeClass("sw sb sa");
                    $(".jb-big-pfl-asd-box").data("silent-stt","");
                } else {
                    /*
                     * [DEPUIS 23-06-15] @BOR
                     * On n'applique le bon mode "silent".
                     */
                    if ( KgbLib_CheckNullity(d.bio) && KgbLib_CheckNullity(d.wbst)  ) {
                        $(".jb-pfl-bio-o-w-bmx").addClass("this_hide");
                        $(".jb-pflbio").addClass("this_hide");
                        $(".jb-pfl-bio-sep").removeClass("this_hide");
                        $(".jb-pfl-bio-author").removeClass("this_hide");
                        
                        $(".jb-pflbio-silentman-mx").removeClass("this_hide");

                        $(".jb-big-pfl-asd-box").removeClass("sa sb sw");
                        $(".jb-big-pfl-asd-box").addClass("sa");
                        $(".jb-big-pfl-asd-box").data("silent-stt","sa");
                    } else if ( !KgbLib_CheckNullity(d.bio) && KgbLib_CheckNullity(d.wbst) ) {
                       /*
                        * On fait revenir les champs à l'état "readonly".
                        */
                        $(".jb-pflbio-bio-txta").addClass("this_hide");
                        $(".jb-pfl-bio-bio").removeClass("this_hide");
                        $(".jb-pfl-bio-sep").removeClass("this_hide");
                        $(".jb-pfl-bio-author").removeClass("this_hide");
                        
                        $(".jb-pfl-bio-ownr-wbst-ipt-mx").addClass("this_hide");
                        $(".jb-pfl-bio-ownr-wbst-mx").removeClass("this_hide");

                    
                        $(".jb-pfl-bio-o-w-bmx").addClass("this_hide");
                        $(".jb-pflbio").removeClass("this_hide");
                        $(".jb-pflbio-silentman-mx").addClass("this_hide");
                        
                        $(".jb-big-pfl-asd-box").removeClass("sa sb sw");
                        $(".jb-big-pfl-asd-box").addClass("sw");
                        $(".jb-big-pfl-asd-box").data("silent-stt","sw");
                    } else {
                        /*
                        * On fait revenir les champs à l'état "readonly".
                        */
                        $(".jb-pflbio-bio-txta").addClass("this_hide");
                        $(".jb-pfl-bio-bio").removeClass("this_hide");
                        $(".jb-pfl-bio-sep").removeClass("this_hide");
                        $(".jb-pfl-bio-author").removeClass("this_hide");   
                        
                        $(".jb-pfl-bio-ownr-wbst-ipt-mx").addClass("this_hide");
                        $(".jb-pfl-bio-ownr-wbst-mx").removeClass("this_hide");
                        
                        $(".jb-pfl-bio-o-w-bmx").removeClass("this_hide");
                        $(".jb-pflbio").addClass("this_hide");
                        $(".jb-pflbio-silentman-mx").addClass("this_hide");
                        
                        $(".jb-big-pfl-asd-box").removeClass("sa sb sw");
                        $(".jb-big-pfl-asd-box").addClass("sb");
                        $(".jb-big-pfl-asd-box").data("silent-stt","sb");
                    }
                        
                    /*
                     * [DEPUIS 22-06-15] @BOR
                     */
                    $(".jb-pflbio").data("state","close");
                    $(".jb-pfl-bio-own-a-chcs-mx").addClass("this_hide");
                    $(".jb-pflbio-own-a-trg").removeClass("this_hide");

                    //On unlock les boutons
                    $(".jb-pfl-bio-own-a-chcs").data("lk",0);
                }
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /***************************************************************************************************************************************/
    /*********************************************************** LISTENERS SCOPE ***********************************************************/
    /***************************************************************************************************************************************/
    
    /*
     * [DEPUIS 19-06-15] @BOR
     * Devenu obselète !
     */
//    _f_ChkBioSts(); 
    
    /*
    $(".jb-pfl-bio-bio").hover(function(e) {
        Kxlib_StopPropagation(e);
        
        if ( $(".jb-pfl-bio-bio").html() === "") { return; }
        
        setTimeout(function(){
            if ( $(".jb-pfl-bio-bio").is(":hover") )
                $(".jb-pflbio-own-a-trg").stop(true,true).fadeIn();
        },100);
    }, function(e){
        
        if ( $(".jb-pfl-bio-bio").html() === "") { return; }
        
        setTimeout(function(){
            if (! $(".jb-pflbio-own-a-trg").is(":hover") )
                $(".jb-pflbio-own-a-trg").stop(true,true).fadeOut();
        },500);
    });
    
    
    $(".jb-pflbio-own-a-trg").on("mouseleave", function() {
        if ( $(".jb-pfl-bio-bio").html() === "") { return; }
        
        $(".jb-pflbio-own-a-trg").stop(true,true).fadeOut();
    });
    //*/
    
    /************ OUVRIR L'ACCES ************/
    
    $(".jb-pflbio-own-a-trg").click(function(e) {
        Kxlib_PreventDefault(e);
        
        _f_OpAkx();
    });
    
    $(".jb-pfl-bio-own-a-chcs").click(function(e) {
        Kxlib_PreventDefault(e);
        
        _f_HdleFnlDec(this);
    });
    
    $(".jb-pfl-bio-bio").dblclick(function(e) {
        _f_OpAkx();
    });
    
    /************ FERMER L'ACCES ************/
    /*
     * [DEPUIS 19-06-15] @BOR
     * Retirer car l'expérience n'était pas optimale
     */
    /*
    $(".jb-pflbio-bio-txta").blur(function(e) {
        _f_ClzAkx(e.type,e);
    });
    //*/
    $(".jb-pflbio-bio-txta, .jb-pfl-bio-ownr-wbst-ipt").on("keydown keyup keypress",function(e) {
        _f_Keys(e);
    });
}

new ProfilBio();     