/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function ASDAPPS () {
    
    /*
     * **> FONCTIONNALITES <**
     *      -> Switch entre les modules à l'aide des boutons ronds
     *      -> On peut changer de mode de switch pour qu'il soit visuellement différent (standard, slide)
     *      -> Rendre AsideApp flottant
     *      -> Permettre le switch sur le modèle flotant
     *      -> Gère Ouverture/Fermeture des réponses pour GuideBox.
     *          NOTE : On effectue les opérations ici pour éviter de devoir construire un fichier de traitement exprès.
     * 
     * **> EVOLUTIONS <**
     *      -> Le module de navigation admet des icones plutot que des ronds.
     *      -> Sur le nouveau module de navigatio, lorsqu'on passe la souris en :hover, une description apparrait. Cette fonctionnalité peut être désactivée.
     *      -> Ajout du module Suggestion
     *      -> Au bout d'un temps donné, le module passe en mode "Suggestion"
     */
    
    /*************************************************************************************************************************************************************************/
    /***************************************************************************** PROCESS SCOPE *****************************************************************************/
    /*************************************************************************************************************************************************************************/   
    var _f_Gdf = function ()  {
        var ds = {
            //AsdAppsAction
            "aaa" : ["goguidebox","gosearchbox","gochatbox","goroombox"],
//            "_15psz" : { h : 2000, w : 2000 }
            "_15psz" : { h : 770, w : 1400 }
        };
        
        return ds;
    };
    
    var _f_Init = function () {
        $(".jb-aside-mods").data("lk",1);
        _f_SwImHigh();
//        _f_SwImHigh(true); //DEV, DEBUG, TEST
    };
    
    var _f_SwApps = function (x) {
        try {
            
            if (KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) | $.inArray($(x).data("action"), _f_Gdf().aaa) === -1 | !$(".jb-asd-apps-chc.selected").length | !$(".jb-asd-apps-chc.selected").data("action")) {
//            Kxlib_DebugVars([gbLib_CheckNullity(x), KgbLib_CheckNullity($(x).data("action")), $(x).data("action"), $.inArray($(x).data("action"),_f_Gdf().aaa), JSON.stringify(_f_Gdf().aaa)]);
                return;
            }
            
            var a = $(x).data("action");
            if ($(".jb-asd-apps-chc.selected").data("action") === a) {
                return;
            }
            /*
             * [NOTE 11-03-14] @Lou
             *      Je laisse les deux zones séparées en prévision d'opérations spécifiques pour l'accès aux différents modules
             */
            switch (a) {
                case "goguidebox" :
                case "gosearchbox" :
                case "gochatbox" :
                case "goroombox" :
                        _f_VwSwApps(x);
                    break;
                default :
                    return;
            }
            
            _f_VwSlctAppsCh(x);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_SwImHigh = function (x,shw,lk) {
        /*
         * Permet de switcher entre le mode "Floating" et "Asided"
         */
        try {
//            if ( !KgbLib_CheckNullity($(".jb-asd-apps-pin-btn")) && $(".jb-asd-apps-pin-btn").data("for") === 2 ) {
//                //On fait réapparaitre le cadenas
//                $(".jb-asd-apps-pin-btn").removeClass("this_hide");
//            }

            if ( !KgbLib_CheckNullity($(".jb-aside-mods").data("lk")) && $(".jb-aside-mods").data("lk") === 1 ) {
                return;
            }
            
            /*
             * [DEPUIS 17-06-15] @BOR 
             * La solution de "zoom" n'était pas optimale pour les écrans de petite taille.
             * J'ai préféré une solution qui bloque la zone. On ne perd pas en qualité.
             * De plus, cette solutoin était necessaire pour permettre que les publicités en mode WLC soient toujours visibles.
             */
            var t__ = $(x).scrollTop() - 369 ;
//            Kxlib_DebugVars([TOPx : "+t__]);
//            Kxlib_DebugVars([hw, typeof x !== undefined, $(x).length]);
            if ( shw && typeof x !== undefined && $(x).length ) {
                $("#aside").css({ top: t__+"px" });
            } else {
                $("#aside").css({ top: "0" });
            }
            
            
            /*
            if (shw) {
                $(".jb-aside-mods").addClass("iamhigh");
                $(".jb-asd-apps-ch-mx").addClass("iamhigh");
                $(".jb-asd-apps-pin-btn").addClass("iamhigh");
            } else {
                $(".jb-aside-mods").removeClass("iamhigh");
                $(".jb-asd-apps-ch-mx").removeClass("iamhigh");
                $(".jb-asd-apps-pin-btn").removeClass("iamhigh");
            }
            //*/
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_Unpin = function (x) {
        try {
            
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
        
            var t__ = ( KgbLib_CheckNullity($(".jb-aside-mods").data("lk")) || $(".jb-aside-mods").data("lk") === 0 ) ? 1 : 0;
            $(".jb-aside-mods").data("lk",t__);
            var ilk;
            if ( t__ ) {
                ilk = false;
                $(".jb-asd-apps-pin-btn").attr("data-state","lock");
            } else {
                ilk = true;
                $(".jb-asd-apps-pin-btn").attr("data-state","ulock");
            }
            
            /*
             * [DEPUIS 17-06-15] @BOR 
             */
            if ( $(window).scrollTop() > 379 ) {
                $("#aside").css({ top: "0" });
            }
            
            /*
             * [DEPUIS 23-11-15]
             */
            $(".jb-aside").removeAttr("style");
            
            /*
             * [DEPUIS 05-06-16]
             */
            $(".jb-aside").removeClass("flying");
            
            /*
            var $b = $(".jb-aside-mods");
            var stt = ( $b.hasClass("iamhigh") ) ? 2 : 1;
            var f__ = ( stt === 2 ) ? 1 : 2;
            //On fait disparaitre le cadenas
            $(x).addClass("this_hide");
            $(x).data("for",f__);
            if ( stt === 2 ) {
                //On commence par lock la zone
                $b.data("lk", 1);
                if ($(this).scrollTop() >= 950) {
                    _f_SwImHigh();
                }
            } else {
                $b.data("lk", 0);
            }
            */
           
           /*
            * [DEPUIS 15-07-15] @BOR
            *       On vérifie s'il y a des ARP ouverts dans le cas d'un 15"
            */
           var $alst = $(".jb-arp-solo-in-acclist");
           var $aoplst = $(".jb-tmlnr-mdl-std:not(:first)").find(".jb-arp-solo-in-acclist:not(.this_hide)");
           var $aclzlst = $(".jb-arp-solo-in-acclist.this_hide");
           if ( screen && ( screen.height < _f_Gdf()._15psz.h | screen.width < _f_Gdf()._15psz.w && $alst.length  ) ) {
               if ( $aoplst.length ) {
                   if ( ilk ) {
                        $aoplst.switchClass("fit","edge",400);
                    } else {
                        $aoplst.switchClass("edge","fit",400);
                    }
               }
               if ( $aclzlst.length ) {
                   if ( ilk ) {
                        $aclzlst.switchClass("fit","edge");
                    } else {
                        $aclzlst.switchClass("edge","fit");
                    }
               }
               
           } 
           
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
        
    };
    
    /**************************************************************** GUIDE SCOPE ******************************************************************/
    
    var _f_gdbx_grpch = function (x) {
        try {
            
            if ( KgbLib_CheckNullity(x) | !$(x).length | !$(x).closest(".jb-asd-gdbx-l-bx").length | KgbLib_CheckNullity($(x).closest(".jb-asd-gdbx-l-bx").data("order")) ) {
                return;
            }
            
            var $gp = $(x).closest(".jb-asd-gdbx-l-bx"), gpo = $gp.data("order").toLowerCase();
            if (! $gp.find(".jb-asd-gdbx-l-cntt").length ) {
                return;
            }
            
            //ctt = ConTenT
            var $ctt = $gp.find(".jb-asd-gdbx-l-cntt");
            switch (gpo) {
                case "trenqr_what" :
                case "trenqr_why":
                case "trenqr_next":
                case "trenqr_sure":
                case "trenqr_when":
                    $gp.toggleClass("active");
                    $ctt.toggleClass("active");
                default :
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /*************************************************************************************************************************************************************************/
    /***************************************************************************** SERVER SCOPE ******************************************************************************/
    /*************************************************************************************************************************************************************************/   
    
    /*************************************************************************************************************************************************************************/
    /******************************************************************************* VIEW SCOPE ******************************************************************************/
    /*************************************************************************************************************************************************************************/   
    
    var _f_VwSwApps = function (x) {
        try {
            
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            //a : action, lp : LeftPosition, bnw : BlocNeW, bol : BlockOLd
            var a = $(x).data("action"), lp, $bnw, $bol;
            var $sn__, $so__; 
            switch (a) {
//                case "goguidebox" :
//                        $bnw = $(".jb-asdapp-modl[data-modl='guidebox']");
//                        $bol = $(".jb-asdapp-modl[data-modl='chatbox'], .jb-asdapp-modl[data-modl='searchbox']");
//
//                        //Les selecteurs
//                        $sn__ = $(".jb-asd-apps-chc[data-action='goguidebox']");
//                        $so__ = $(".jb-asd-apps-chc[data-action='gosearchbox'], .jb-asd-apps-chc[data-action='gochatbox']");
//                    break;
                case "gosearchbox" :
                        $bnw = $(".jb-asdapp-modl[data-modl='searchbox']");
                        $bol = $(".jb-asdapp-modl[data-modl='roombox'], .jb-asdapp-modl[data-modl='chatbox']");

                        //Les selecteurs
                        $sn__ = $(".jb-asd-apps-chc[data-action='gosearchbox']");
                        $so__ = $(".jb-asd-apps-chc[data-action='goroombox'], .jb-asd-apps-chc[data-action='gochatbox']");
                    break;
                case "gochatbox" :
                        $bnw = $(".jb-asdapp-modl[data-modl='chatbox']");
                        $bol = $(".jb-asdapp-modl[data-modl='roombox'], .jb-asdapp-modl[data-modl='searchbox']");

                        //Les selecteurs
                        $sn__ = $(".jb-asd-apps-chc[data-action='gochatbox']");
                        $so__ = $(".jb-asd-apps-chc[data-action='goroombox'], .jb-asd-apps-chc[data-action='gosearchbox']");
                    break;
                case "goroombox" :
                        $bnw = $(".jb-asdapp-modl[data-modl='roombox']");
                        $bol = $(".jb-asdapp-modl[data-modl='searchbox'], .jb-asdapp-modl[data-modl='chatbox']");

                        //Les selecteurs
                        $sn__ = $(".jb-asd-apps-chc[data-action='goroombox']");
                        $so__ = $(".jb-asd-apps-chc[data-action='gosearchbox'], .jb-asd-apps-chc[data-action='gochatbox']");
                    break;
                default :
                    return;
            }
            
            if (true) {
                $bol.addClass("this_hide");
                $bnw.removeClass("this_hide");
            }
            
            //LES SELECTEURS
            $sn__.addClass("selected");
            $so__.removeClass("selected");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_VwSlctAppsCh = function (x) {
        try {
             if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            
            //a : action, sld : selected, ico : icone
            var a = $(x).data("action"), sld, ico;
            
            sld = $(".jb-asd-apps-ch-illus.selected");
            if ( !$(sld).length || KgbLib_CheckNullity($(sld).data("scp")) || $(sld).data("scp") === $(ico).data("scp") ) {
                return;
            }
            
            switch (a) {
                case "goguidebox" :
                        ico = $(".jb-asd-apps-ch-illus[data-scp='guidebox']");
                    break;
                case "gochatbox" :
                        ico = $(".jb-asd-apps-ch-illus[data-scp='chatbox']");
                    break;
                case "gosearchbox" :
                        ico = $(".jb-asd-apps-ch-illus[data-scp='searchbox']");
                    break;
                case "goroombox" :
                        ico = $(".jb-asd-apps-ch-illus[data-scp='roombox']");
                    break;
                default :
                    return;
            }
            
            $(".jb-asd-apps-ch-illus").stop(true,true).fadeOut(500).addClass("this_hide");
            $(".jb-asd-apps-ch-illus").removeClass("selected");
            $(ico).stop(true,true).hide().removeClass("this_hide").fadeIn(500).addClass("selected");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_VwHvrAppsCh = function (x,fe_ih) {
        //ih : FromExternal_IsHover
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            
            //a : action, sld : selected, ico : icone
            var a = $(x).data("action"), sld, ico;
            var ih = ( typeof fe_ih === "boolean" ) ? fe_ih : function(){ return ( $(x).is(":hover") ) ? true : false ;} ;
//            var ih = ( $(x).is(":hover") ) ? true : false ;
            switch (a) {
                case "goguidebox" :
                        ico = $(".jb-asd-apps-ch-illus[data-scp='guidebox']");
                    break;
                case "gochatbox" :
                        ico = $(".jb-asd-apps-ch-illus[data-scp='chatbox']");
                    break;
                case "gosearchbox" :
                        ico = $(".jb-asd-apps-ch-illus[data-scp='searchbox']");
                    break;
                case "goroombox" :
                        ico = $(".jb-asd-apps-ch-illus[data-scp='roombox']");
                    break;
                default :
                    return;
            }
            sld = $(".jb-asd-apps-ch-illus.selected");
            /*
             * (1) On vérifie si on a les éléments necesaires au traitement de l'information.
             * (2) On vérifie si on est au dessus de l'élément sélectionné
             */
//            Kxlib_DebugVars([$(sld).length, KgbLib_CheckNullity($(sld).data("scp")), $(sld).data("scp") === $(ico).data("scp")]);
            Kxlib_DebugVars([!$(sld).length, KgbLib_CheckNullity($(sld).data("scp")), $(sld).data("scp") === $(ico).data("scp")],false);
            if ( !$(sld).length || KgbLib_CheckNullity($(sld).data("scp")) || $(sld).data("scp") === $(ico).data("scp") ) {
                Kxlib_DebugVars(["FAILED !"],false);
                return;
            }
            
            if ( ih ) {
                $(ico).stop(true,true).hide().removeClass("this_hide").fadeIn(500);
            } else {
                $(ico).stop(true,true).fadeOut(500).addClass("this_hide");
            }
                        
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*************************************************************************************************************************************************************************/
    /**************************************************************************** LISTENERS SCOPE ****************************************************************************/
    /*************************************************************************************************************************************************************************/
    
    _f_Init();
    
    $(".jb-asd-apps-chc").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_SwApps(this);
    });
    
    $(".jb-asd-apps-chc").hover(function(e){
        _f_VwHvrAppsCh(this,true);
    },function(){
        _f_VwHvrAppsCh(this,false);
    });
    
    /*
     * [NOTE 03-05-15] @BOR
     * Je ne comprends pas exactement la necessité de ce listeners. En effet, "jb-aside-mods" n'est pas un bouton !!!
     * De plus, il est à l'origine d'un bogue qui empeche de cliquer sur des liens de type 'href'
     */
    /*
    $(".jb-aside-mods").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_SwApps(this);
    });
    //*/
    
    $(".jb-asd-apps-pin-btn").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Unpin(this);
    });
    
    
    /*
    $(window).off("scroll").scroll(function(e){
        $(".jb-wos-csl-opt-steam").text("TOP : "+$(this).scrollTop());
//        Kxlib_DebugVars([TOP : "+$(this).scrollTop()]);
        if ( $(".jb-aside-mods") && $(".jb-aside-mods").length && $(this).scrollTop() >= 399 ) {
//        if ( $(this).scrollTop() >= 950 ) {
            _f_SwImHigh(this,true);
        } else {
            _f_SwImHigh(this);
        }
        
    });
    //*/
    $(".jb-asd-gdbx-l-tle").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_gdbx_grpch(this);
    });
    
}
 
new ASDAPPS();
