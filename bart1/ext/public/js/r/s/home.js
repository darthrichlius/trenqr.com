


function HOME_OTQR () {
    var _xhr_sugg_ga;
    /*****************************************************************************************************************************************************************/
    /************************************************************************* PROCESS SCOPE *************************************************************************/
    /*****************************************************************************************************************************************************************/
    
    var _f_Init = function () {
        try {
            
            /*
             * ETAPE : 
             *      On vérifie s'il y a une demande de d'ouverture automatique de la zone d'en bas
             */
            if ( $(".jb-pg-vw").length && $(".jb-pg-vw").data("view") === "1m30" ) {
                setTimeout(function(){
                    $("#home1_locker").click();
                },600);
                $(".jb-pg-vw").remove();
                
                /*
                 * [DEPUIS 09-11-15] @author BOR
                 *      Récupérer les données des suggestions
                 */
                _f_GtSgs();
            } else {
                /*
                 * [DEPUIS 09-11-15] @author BOR
                 *      Récupérer les données des suggestions
                 */
                setTimeout(function(){
                    $(".jb-ddle-arrw").stop(true,true).fadeOut(500).fadeIn(600).fadeOut(500).fadeIn(600);
                },1000);
                
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /*************** HOME FORM VALIDATOR *****************/
    
    var _f_HmFrmVldtr = function (e,x) {
        try {
            if ( KgbLib_CheckNullity(e) ) {
                return;
            }
            
            var final_val_prf = true;
            
            $('.preg_ins_com_check').removeClass('error_border');

            $.each($(".preg_ins_com_check"),function(x,v){
                if ( $(v).val() === "" ) {
                    $(v).addClass("error_border");
                    final_val_prf = false;
                    return;
                } 
            });
            
            if ( final_val_prf !== true ) {
                Kxlib_PreventDefault(e);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /***************** UNDER HOMES SCOPE *****************/
    
    var _f_OpenSubs = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            
            //Vérification pour le on/off
            if ( $('#home1_locker').data('st') === "undone" ){
                
               /*
                * [DEPUIS 09-11-15] @author BOR
                *      Récupérer les données des suggestions
                * [DEPUIS 24-11-2015] @author BOR
                *       On vérifie au préalable si on a pas déjà des éléments chargés.
                *       En effet, si le mode 1m30 est activé, un chargement a surement déjà été lancé
                */
                if ( !$(".jb-tqr-u-smr-articles-bmx").length && KgbLib_CheckNullity(_xhr_sugg_ga) ) {
                   _f_GtSgs();
                }

                //Changement de background du bouton et unlock de la page
                $('#home1').css('padding', '0 0 50px 0');
                $('#home1_locker').css('background', 'url("/bart1/timg/files/img/w/c_open.jpg")');
                $('#home1_scrolllock').css('overflow', 'visible');

                //Smooth scroll sur une partie de la p2
                $('html, body').stop(true).animate({
//                    scrollTop: 215
                    scrollTop: 315
                }, 1000);
                $('#home1_locker').data('st', 'done');    
                
                $("#tqr-unvrs-tqr-faq-vid-wpr").perfectScrollbar({
                    suppressScrollX : true
                });

            } else if ( $('#home1_locker').data('st') === "done" ){

                $('html, body').stop(true).animate({
                    scrollTop: 0
                }, 1000, function(){$('#home1_scrolllock').css('overflow', 'hidden'); $('#home1').css('padding', '0 0 4px 0')});
                $('#home1_locker').data('st', 'undone');  

                //Rechangement du background et relock de la page.
                $('#home1_locker').css('background', 'url("/bart1/timg/files/img/w/c_lock.jpg")');
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /***************** TQR POLITICS SCOPE *****************/
    
    var _f_PoltcsAct = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("target")) ) {
                return;
            }
            
            var tgt = $(x).data("target").toLowerCase();
            switch (tgt) {
                case "liberty-respect" :
                case "just-fun" :
                case "lmbyf" :
                case "commitment" :
                        //On continue
                    break;
                default :
                    return;
            }
            
           /*
            * [NOTE 03-07-15] @BOR
            * J'ai fait exprès de séparer les dux opérations car dans le cas contraire on aurait eu une usine à gaz difficile à maintenir.
            */
            if ( $(x).hasClass("activate") ) {
                _f_Vw_HidManftos(tgt);
            } else {
                _f_Vw_ShwManftos(tgt);
            }
             
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_GtSgs = function(){
        try {
            
            /*
             * ETAPE :
             *      On vérifie si les éléments ne sont pas déjà affichés, dans lequel cas on ne lance pas la requete
             */
            if ( $(".jb-tqr-u-smr-articles-bmx").length ) {
                return;
            }
            
            /*
             * ETAPE :
             *      On contacte le serveur
             */
            var s = $("<span/>");
            _f_Srv_PlSuggs(s);
            
            $(s).on("datasready",function(e,ds){
                if ( KgbLib_CheckNullity(ds) | KgbLib_CheckNullity(ds.trends) ) {
                    return;
                }
                
//                Kxlib_DebugVars([JSON.stringify(ds.trends)],true);
//                return;
                
                $.each(ds.trends,function(x,d){
                    if ( $(".jb-tqr-u-smr-articles-bmx[data-item='"+d.tid+"']").length ) {
                        return true;
                    }
                    
                    var m = _f_PprMdl(x,d);
                    $(m).hide().appendTo(".jb-tqr-u-sctn-t-sc-bdy[data-scp='sample']").fadeIn();
                    
                });
                
                
                _xhr_sugg_ga = null;
            });
            
            $(s).on("datasready",function(e){
                _xhr_sugg_ga = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_PprMdl = function(x,d){
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(d) ) {
                return;
            }
            
            /*
             * TABLE DES DONNÉES :
             * tid      : Identifiant externe
             * ttle     : Titre de la Tendance
             * tdsc     : Description de la Tendance
             * tlk      : Titre de la Tendance
             * tctm     : Date ajout
             * tcvpc    : {cov_rp, cov_w, cov_h, cov_t}
             * town     : {oid, ofn, opsd}
             */
            
            var m = "<article class=\"tqr-u-smr-articles-bmx jb-tqr-u-smr-articles-bmx\" data-item=\"\">";
            m += "<div class=\"tqr-u-smr-art-trd-mx\">";
            m += "<div class=\"tqr-u-smr-art-trd-cvr\">";
            m += "<a class=\"tqr-u-smr-art-trd-cvr-hrf jb-tqr-u-smr-art-trd-cvr-hrf\" href=\"\">";
            m += "<span class='tqr-u-smr-art-trd-cvr-fd'></span>";
            m += "<img class=\"tqr-u-smr-art-trd-cvr-i jb-tqr-u-smr-art-trd-cvr-i\" src=\"\" alt=\"\" width=\"280\" />";
            m += "</a>";
            m += "</div>";
            m += "<div class=\"tqr-u-smr-art-trd-hdr\">";
            m += "<header class=\"tqr-u-smr-art-trd-hdr-tle-mx\">";
            m += "<h4><a class=\"tqr-u-smr-art-trd-hdr-tle jb-tqr-u-smr-art-trd-h-tle\" href=\"\"></a></h4>";
            m += "</header>";
            m += "<div class=\"tqr-u-smr-art-trd-hdr-dsc-mx\">";
            m += "<a class=\"tqr-u-smr-art-trd-hdr-dsc jb-tqr-u-smr-art-trd-h-dsc\" href=\"\"></a>";
            m += "</div>";
            m += "<footer class=\"tqr-u-smr-art-trd-hdr-xtra-mx\">";
            m += "<a class=\"tqr-u-smr-art-trd-hdr-xt-ownr jb-tqr-u-smr-art-trd-hdr-xt-o\" href=\"\">";
            m += "<span class=\"tqr-u-smr-art-trd-hdr-xt-psd jb-tqr-u-smr-art-trd-hdr-xt-p\"></span>";
            m += "</a>";
//            m += "<span class=\"tqr-u-smr-art-trd-hdr-xt-time\">";
//            
//            m += "</span>";
            m += "</footer>";
            m += "</div>";
            m += "</div>";
            m += "</article>";
            
            m = $.parseHTML(m);
            
            /*
             * ETAPE :
             *      Insertion des données
             */
            $(m).data("item",d.tid);
            $(m).find(".jb-tqr-u-smr-art-trd-cvr-hrf").attr("href",d.tlk);
            if (! KgbLib_CheckNullity(d.tcvpc.cov_rp) ) {
                $(m).find(".jb-tqr-u-smr-art-trd-cvr-i").attr("src",d.tcvpc.cov_rp).attr("alt",d.tdsc);
                /*
                 * ETAPE :
                 *      On effectue les calculs qui permettront de retrouver la même proportion que la couverture de la Tendance sur sa page.
                 */
                var cf = 140/260;
                var t = (d.tcvpc.cov_t*cf)+5;
                $(m).find(".jb-tqr-u-smr-art-trd-cvr-i").css("top",t);
            } else {
                $(m).find(".jb-tqr-u-smr-art-trd-cvr-i").remove();
            }
            $(m).find(".jb-tqr-u-smr-art-trd-h-tle").attr("href",d.tlk).attr("title",d.tlk).text(d.ttle);
            $(m).find(".jb-tqr-u-smr-art-trd-h-dsc").attr("href",d.tlk).text(d.tdsc);
            $(m).find(".jb-tqr-u-smr-art-trd-hdr-xt-o").attr("href","/"+d.town.opsd);
            $(m).find(".jb-tqr-u-smr-art-trd-hdr-xt-p").text("@"+d.town.opsd);
            
            if ( x === 2 ) {
                 $(m).addClass("last");
            }
            
            return m;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /***************** TRENQR HOME : UNIVERS SCOPE *****************/
    
    var _f_TqrUnvrAction = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | ( !$(x).data("action") && !a ) ) {
                return;
            }
            
            var _a = ( $(x).data("action") ) ?$(x).data("action") : a;
            switch (_a) {
                case "nav-menu" :
                case "nav-prev" :
                case "nav-next" :
                       _f_TqrUnvr_NavMn(x,_a);
                    break;
                default:
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_TqrUnvr_NavMn = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            //amn = ActiveMeNu
            var amn = $(".jb-tqr-u-menu.active");
            if ( !$(amn).length || $(amn).length > 1 ) {
                return;
            }
            
            /*
             * ETAPE : 
             *      On récupère la référence du MENU cible
             */
            //tmn = TargetMeNu
            var tmn;
            switch (a) {
                case "nav-menu" :
                        tmn = $(x);
                    break;
                case "nav-prev" :
                        tmn = $(amn).closest(".jb-tqr-u-menu-mx").prev().find(".jb-tqr-u-menu");
                    break;
                case "nav-next" :
                        tmn = $(amn).closest(".jb-tqr-u-menu-mx").next().find(".jb-tqr-u-menu");
                    break;
                default:
                    return;
            }
            if (! $(tmn).length ) {
                return;
            }
            
            /*
             * ETAPE : 
             *      On switch le menu ACTIF
             */
            $(".jb-tqr-u-menu.active").removeClass("active");
            $(tmn).addClass("active");
            
            /*
             * ETAPE : 
             *      On gère l'affichage (disponibilité) des boutons de NAV latéraux et autres spécificités
             */
            var tgt = $(tmn).data("target");
            switch (tgt) {
                case "home_tqr_wha" :
                        $(".jb-tqr-unvrs-scnd-nav-btn").removeClass("this_hide");
                        $(".jb-tqr-unvrs-scnd-nav-btn[data-action='nav-prev']").addClass("this_hide");
                    break;
                case "home_tqr_mnfto" :
                        $(".jb-tqr-unvrs-scnd-nav-btn").removeClass("this_hide");
                    break;
                case "home_tqr_faq" :
                        $(".jb-tqr-unvrs-scnd-nav-btn").removeClass("this_hide");
                        $(".jb-tqr-unvrs-scnd-nav-btn[data-action='nav-next']").addClass("this_hide");
                    break;
                default:
                    return;
            }
            
//            alert($(tmn).data("target"));
            
            /*
             * ETAPE :
             *      On change de WINDOW
             */
            $(".jb-tqr-unvrs-scnd-sec-bmx")
                .stop(true,true)
                .fadeOut()
                .addClass("this_hide");
            $(".jb-tqr-unvrs-scnd-sec-bmx[data-scp='"+tgt+"']")
                .stop(true,true)
                .hide()
                .removeClass("this_hide")
                .fadeIn();
            
            if (  tgt === "home_tqr_faq") {
                setTimeout(function(){
                    $("#tqr-unvrs-tqr-faq-vid-wpr").perfectScrollbar("update");
                },1000);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /*****************************************************************************************************************************************************************/
    /************************************************************************* SERVER SCOPE **************************************************************************/
    /*****************************************************************************************************************************************************************/
    
    var _Ax_PlSuggs = Kxlib_GetAjaxRules("TQR_SUGG_GETALL");
    var _f_Srv_PlSuggs = function(s) {
        if ( KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
//            alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    _xhr_sugg_ga = null;
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    _xhr_sugg_ga = null;
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
                } else if ( !KgbLib_CheckNullity(datas.return) && ( !KgbLib_CheckNullity(datas.return.profils) | !KgbLib_CheckNullity(datas.return.trends) ) ) {
                    var ds = [datas.return];
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
            "urqid": _Ax_PlSuggs.urqid,
            "datas": {
                "ttm"   : false,
                "cu"    : curl
            }
        };
        
        _xhr_sugg_ga = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlSuggs.url, wcrdtl : _Ax_PlSuggs.wcrdtl });
    };
    
    /*****************************************************************************************************************************************************************/
    /*************************************************************************** VIEW SCOPE **************************************************************************/
    /*****************************************************************************************************************************************************************/
    
    /***************** TQR POLITICS SCOPE *****************/
    
    var _f_Vw_ShwManftos = function (tgt) {
        try {
            
            var f1 = function(){
                /*
                 * Affiche tous les éléments
                 */
                 //On active le controleur du manifesto
                 $(".jb-tqr-pltcs-chc-mx").stop(true,true).addClass("activate");
                 //On affiche la ligne
                 $(".jb-tqr-pltcs-chc-tgr").stop(true,true).addClass("activate");
                 //On affiche le bon manifesto
                 $(".jb-tqr-pltcs-manfto-mx").stop(true,true).hide().removeClass("this_hide").fadeIn(600).removeAttr("style");
            };
            var f2 = function(){
                /*
                 * Affiche l'élément
                 */
                 //On active le controleur du manifesto
                 $(".jb-tqr-pltcs-chc-mx[data-target='"+tgt+"']").stop(true,true).addClass("activate");
                 //On affiche la ligne
                 $(".jb-tqr-pltcs-chc-tgr[data-target='"+tgt+"']").stop(true,true).addClass("activate");
                 //On affiche le bon manifesto
                 $(".jb-tqr-pltcs-manfto-mx[data-target='"+tgt+"']").stop(true,true).hide().removeClass("this_hide").fadeIn(600).removeAttr("style");


                /*
                 * On scroll vers le bas de la page
                 */
                $("html, body").stop(true,true).animate({ scrollTop: $(document).height() }, 1100);
            };
            
           /*
            * [NOTE 03-07-15] @BOR
            * Cette méthode permet d'avoir un code plus lisible.
            */
            var f__ =  (! tgt ) ? f1 : f2;
            _f_Vw_HidManftos(null,f__);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Vw_HidManftos = function (tgt,cplt) {
        try {
            
            if (! tgt ) {
               /*
                * On procède à la réinitialisation de tous les éléments
                */
                if ( typeof cplt === "function" && $(".jb-tqr-pltcs-manfto-mx:not(.this_hide)").length ) {
                    
                    var h__ = $("#tqr-pltcs-sprt").height();
                    $("#tqr-pltcs-sprt").height(h__);
                    
                    //On rend invisible le manisfeste pour des raisons esthétiques
                    $(".jb-tqr-pltcs-manfto-mx:not(.this_hide)").stop(true,true).fadeTo(400,0);
                    
                    //On masque tous les manifestos
                    $(".jb-tqr-pltcs-manfto-mx:not(.this_hide)").stop(true,true).hide( "blind", { direction: "up" }, 500, function(){
                        $(this).addClass("this_hide").removeAttr("style");
                        cplt();
                        
                        /*
                         * [NOTE 03-07-15] @BOR
                         * On ajuste la hauteur pour des besoins esthétiques
                         */
                        var el = $('#tqr-pltcs-sprt'),
                            crH = el.height(),
                            atH = el.css('height', 'auto').height();
                        el.height(crH).animate({height: atH}, 800);
                    });

                    //On masque toutes les lignes
                    $(".jb-tqr-pltcs-chc-tgr").stop(true,true).removeClass("activate");
                    //On réinitialise tous les controleurs de manifestos
                    $(".jb-tqr-pltcs-chc-mx").stop(true,true).removeClass("activate");
                    
                } else if ( typeof cplt === "function" && !$(".jb-tqr-pltcs-manfto-mx:not(.this_hide)").length ) {
                    cplt(); 
                } else {
                    //On masque tous les manifestos
                    $(".jb-tqr-pltcs-manfto-mx:not(.this_hide)").stop(true,true).fadeOut().addClass("this_hide").removeAttr("style");

                    //On masque toutes les lignes
                    $(".jb-tqr-pltcs-chc-tgr").stop(true,true).removeClass("activate");
                    //On réinitialise tous les controleurs de manifestos
                    $(".jb-tqr-pltcs-chc-mx").stop(true,true).removeClass("activate");
               }
                
            } else if ( $(".jb-tqr-pltcs-manfto-mx[data-target='"+tgt+"']").length ) {

               /*
                * On procède à la réinitialisation de l'élément spécifique
                */
                if ( typeof cplt === "function" ) {
                    
                    //On masque tous les manifestos
                    $(".jb-tqr-pltcs-manfto-mx[data-target='"+tgt+"']").stop(true,true).hide( "blind", { direction: "up" }, 500, function(){
                        $(this).addClass("this_hide").removeAttr("style");
                        cplt();
                        
                        /*
                         * [NOTE 03-07-15] @BOR
                         * On ajuste la hauteur pour des besoins esthétiques
                         */
                        var el = $('#tqr-pltcs-sprt'),
                            crH = el.height(),
                            atH = el.css('height', 'auto').height();
                        el.height(crH).animate({height: atH}, 800);
                    });
 
                    //On masque toutes les lignes
                    $(".jb-tqr-pltcs-chc-tgr[data-target='"+tgt+"']").stop(true,true).removeClass("activate");
                    //On réinitialise tous les controleurs de manifestos
                    $(".jb-tqr-pltcs-chc-mx[data-target='"+tgt+"']").stop(true,true).removeClass("activate");
                } else {
                   /*
                    * [NOTE 03-07-15] @BOR
                    * On ajuste la hauteur pour des besoins esthétiques
                    */
                   $('#tqr-pltcs-sprt').css("height","auto");
                       
                    //On masque tous les manifestos
                    $(".jb-tqr-pltcs-manfto-mx[data-target='"+tgt+"']").stop(true,true).hide( "blind", { direction: "up" }, 600, function(){
                        $(this).addClass("this_hide").removeAttr("style");
                    });

                    //On masque toutes les lignes
                    $(".jb-tqr-pltcs-chc-tgr[data-target='"+tgt+"']").stop(true,true).removeClass("activate");
                    //On réinitialise tous les controleurs de manifestos
                    $(".jb-tqr-pltcs-chc-mx[data-target='"+tgt+"']").stop(true,true).removeClass("activate");
                    
               }
               /*
                //On masque le manifesto désigné
                $(".jb-tqr-pltcs-manfto-mx[data-target='"+tgt+"']").stop(true,true).hide("blind",{direction: "up"}, 500, function(){
                    $(this).addClass("this_hide").removeAttr("style");
                });
                //On masque la ligne liée
                $(".jb-tqr-pltcs-chc-tgr[data-target='"+tgt+"']").stop(true,true).removeClass("activate");
                //On réinitialise le controleurs du manifesto désigné
                $(".jb-tqr-pltcs-chc-mx[data-target='"+tgt+"']").stop(true,true).removeClass("activate");
                //*/
            } 
                
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*****************************************************************************************************************************************************************/
    /************************************************************************ LISTENERS SCOPE ************************************************************************/
    /*****************************************************************************************************************************************************************/
    
    $('#home1_locker').click(function(e){ 
        Kxlib_PreventDefault(e);
        
        _f_OpenSubs(this);
    });
    
    $('#home1_preinscription').submit(function(e){
        _f_HmFrmVldtr(e,this);
    });
    
    $(".preg_ins_com_check").blur(function(){
        if ( $(this).length === 0 || $(this).val() === "" ) {
            $(this).addClass("error_border");
        } else {
            $(this).removeClass("error_border");
        }
    });
    
    $(".jb-tqr-pltcs-chc-tgr").click(function(e){ 
        Kxlib_PreventDefault(e);
        
        _f_PoltcsAct(this);
    });
    
    $(".jb-tqr-last-news-clz, .jb-hm1-menus-ch[data-menu='news']").click(function(e){ 
        Kxlib_PreventDefault(e);
        
        if ( $(".jb-tqr-last-news-mx").hasClass("this_hide") ) {
            $(".jb-tqr-last-news-mx").removeClass("this_hide");
        } else {
            $(".jb-tqr-last-news-mx").addClass("this_hide");
        }
        
    });
    
    $(".jb-tqr-unvrs-scnd-nav-btn, .jb-tqr-u-menu").click(function(e){ 
        Kxlib_PreventDefault(e);
        
        _f_TqrUnvrAction(this);
    });

    /*****************************************************************************************************************************************************************/
    /**************************************************************************** AUTO SCOPE *************************************************************************/
    /*****************************************************************************************************************************************************************/
    _f_Init();
    
    /*
    $(window).scroll(function(){
        var win_lmt = 965, bx_lmt = 0;
        var zntop = $(".jb-tqr-unvrs-nav-btn").position().top;
        var zntop_ad = ( $(this).scrollTop() - win_lmt ) + bx_lmt;
        
        Kxlib_DebugVars(["SCROLL_TOP => ",$(this).scrollTop(),"WIN_LMT => ",win_lmt,"ZNTOP_ADD => ",zntop_ad,"BX_LMT => ",bx_lmt,"ZNTOP => ",zntop]);

        if ( $(this).scrollTop() >= win_lmt ) {
            $(".jb-tqr-unvrs-nav-btn").css({
                top : zntop_ad
            });
        } else {
            $(".jb-tqr-unvrs-nav-btn").css({
                top : bx_lmt
            });
        }
    });
    //*/
    
}

new HOME_OTQR ();