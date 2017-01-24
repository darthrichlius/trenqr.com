/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function SRHBOX () {
    /*
     * 
     * FONCTIONNALITES
     *  Version : b.1505.1.1 (Mai 2015 VBeta1) [DATE UPDATE : 03-05-15]
     *  
     *  EVOLUTIONS ATTENDUES
     *      -> Rechercher des Tendances par titre
     *      -> Rechercher des Profils par Pseudo, Nom complet
     *      -> Gérer le cas où on a pas de résultats
     *      -> Suivre le lien du résultat
     *      -> Effacer le résultat via la croix et remettre tout à zéro
     *      -> Switcher entre une Recherche pour Comptes et Tendances
     *      -> Gérer les cas d'injection au niveau des tire de Tendance
     *      
     *  EVOLUTIONS POSSIBLES
     *      -> Aficher "C'est moi" quand j'effectue une recherche sur mon propre Compte
     *      -> Un tout nouveau fichier pourrait être necessaire pour la future version Page
     */
    
    
    
    /*
     * gt = GlobalThis
     * Necessaire pour que les closures protégés puissent accéder au méthodes publiques du module.
     */
    var gt = this;
    
    var _xhr_srh;
    
    /*************************************************************************************************************************************/
    /*********************************************************** PROCESS SCOPE ***********************************************************/
    /*************************************************************************************************************************************/
    var _f_NoOne = function (scp) {
        if ( KgbLib_CheckNullity(scp) ) {
            return;
        }
        
        var b = ( scp === "min" ) ? ".jb-asd-sr-bdy" : ".jb-srh-hy-rslt-mx";
        var cn = $(b).find(".jb-srh-rslt-mdl").length;
        if ( cn ) {
            $(b).find(".jb-srh-no1e").addClass("this_hide");
        } else {
            $(b).find(".jb-srh-no1e").removeClass("this_hide");
        }
    };
    
    //GetDefaultValues
    var _f_Gdf = function () {
//    this.Gdf = function () {
        //DefaultParameters
        var dp = {
            //ActionTable
            "at": ['fil_menu','fil_datas','smr'],
            /*
             * pf : Profile
             * tr: Trend
             */
            "fil_mn": ['fil_mn_pfl','fil_mn_trd'],
            /*
             * at: AuTomatique
             * kw: KnoWn
             * pop: POPulaire
             * wn: Why Not (UnKnow)
             */
            "fil_cat": ['at','kw','pop','wn'],
            "qsp": ['min','hvy'],
            //WTT (WaitTypingTime) Lancer la recherche après cet interval. Cela rend plus fiable le processus
            "wtt" : 370
        };
        
        return dp;
    };
    
    //ChecKReQuestBASics
    var _f_CkRqBas = function (x) {
        var a = $(x).data("action"), sc = $(x).data("qsp");
        
        if ( KgbLib_CheckNullity(a) | KgbLib_CheckNullity(sc) ) 
            return;
        
//        Kxlib_DebugVars([_f_Gdf().at,a,$.inArray(a,_f_Gdf().at),sc,$.inArray(sc,_f_Gdf().qsp)],true);
        
        //Check de l'action
        if ( $.inArray(a,_f_Gdf().at) === -1 ) {
            return;
        }
            
        //Check du scope
        if ( $.inArray(sc,_f_Gdf().qsp) === -1 ){
            return;
        }
        
        return {"a":a,"s":sc};
    };
    
    //SwitchMenu
    var _f_SwMn = function (x,icm) {
        //icm : IsChangeModule
        if ( KgbLib_CheckNullity(x) )
            return;
        
        var t = $(x).data("target"), sc = $(x).data("qsp");
        var b = ( sc === "min" ) ? ".jb-srh-asd-b": ".jb-srh-hvy-b";
        
        //On change la cible de "See More"
        _f_SwMr(sc);
        
        if ( sc === "min" ) {
            //On change le marqueur de type pour la liste
            //ldt : ListDataType
            var ldt = $(b).find(".jb-srh-rslt-list").data("dt");
            if (! ldt ) 
                return;
            
            var nldt = ( ldt === "pf" ) ? "tr" : "pf";
            $(b).find(".jb-srh-rslt-list").data("dt",nldt);
            
            //On efface le contenu déjà affiché (Sauf AWS)
            $(".jb-asd-sr-bdy-rlist").children().not(".jb-asd-sr-bdy-nvd").remove();
            
            /*
             * RAPPEL : On utilise find pour ne pas se tromper car certains sélecteurs sont aussi dans HEAVY
             */
            
            //On change l'ambiance du header
            $("#asd-srch-max").find(".jb-asd-sr-hdr").toggleClass("tr",250);
            $("#asd-srch-max").find(".jb-asd-sr-hdr").toggleClass("pfl",250);
            
            //On change le logo
            $("#asd-srch-max").find(".jb-srh-swh-mn[data-target='fil_mn_pfl']").toggleClass("this_hide");
            $("#asd-srch-max").find(".jb-srh-swh-mn[data-target='fil_mn_trd']").toggleClass("this_hide");
            
            //On change le placeholder
            Kxlib_getDolphinsValue("SRH_IPT_PLH_TRD");
            var tx = ( t === "fil_mn_pfl" ) ? Kxlib_getDolphinsValue("SRH_IPT_PLH_PFL") : Kxlib_getDolphinsValue("SRH_IPT_PLH_TRD");
            $("#asd-srch-max").find(".jb-srch-ipt").attr("placeholder", tx);
            
            //On change la cible de l'input
            //InpuTarGet
            var itg = ( $("#asd-srch-max").find(".jb-srch-ipt").data("target") === "fil_mn_pfl" ) ? "fil_mn_trd" : "fil_mn_pfl";
            $("#asd-srch-max").find(".jb-srch-ipt").data("target", itg);
            
            //[@BOR 27-05-15] On change la cible du bouton "JUMP" 
            var t__ = ( $(".jb-asd-sr-jump-tgr").data("action") === "jump_account" ) ? "jump_trend" : "jump_account";
            $(".jb-asd-sr-jump-tgr").data("action",t__);
            
        } else {
            //S'il s'agit du même menu on passe
            if ( $(x).is(".jb-srh-swh-mn.focus") && !icm ) {
//                alert("meme menu");
                return;
            }
            
            //On change la barre latérale
            /*
             * [NOTE 17-10-14] @author L.C.
             * On le fait en premier pour des raisons esthétiques
             */
            
            var fff = ( $(x).data("target") === "fil_mn_pfl" ) ? "pf" : "tr";
            var bbb = ( $(".jb-srh-hy-ctr-r-mx").hasClass("pfl") ) ? "pf" : "tr";
            if ( fff !== bbb ) {
                $(b).find(".jb-srh-hy-ctr-r-mx").toggleClass("tr",250);
                $(b).find(".jb-srh-hy-ctr-r-mx").toggleClass("pfl",250);
            }
            
            //On change les objets au niveau des filtres
            if ( $(".jb-srh-hy-fil").length ) {
                if ( !$(x).is(".jb-srh-swh-mn.focus") ) {
                    var tr = ( $(".jb-srh-hy-fil").first().data("target") === "fil_mn_pfl" ) ? "fil_mn_trd" : "fil_mn_pfl";
                    $(".jb-srh-hy-fil").data("target",tr);
                }
            } else {
                return;
            }
            
            //On change le marqueur de type pour la liste
            //ldt : ListDataType
            var ldt = $(b).find(".jb-srh-rslt-list").data("dt");
            if (! ldt ) {
                return;
            }
            
            var nldt = ( ldt === "pf" ) ? "tr" : "pf";
            $(b).find(".jb-srh-rslt-list").data("dt",nldt);
            
            //On efface le contenu déjà affiché
            $(b).find(".jb-srh-rslt-list").children().remove();
            
            //RAPPEL : On utilise find pour ne pas se tromper car certains sélecteurs sont aussi dans HEAVY
            
            //On change les menus
            if ( ( t !== $(b).find(".jb-srh-swh-mn.focus").data("target") ) ) {
                $(b).find(".jb-srh-swh-mn").toggleClass("focus");
//                $(b).find(".jb-srh-swh-mn").toggleClass("focus");
            }
            
            //On change le placeholder
            Kxlib_getDolphinsValue("SRH_IPT_PLH_TRD");
            var tx = ( t === "fil_mn_pfl" ) ? Kxlib_getDolphinsValue("SRH_IPT_PLH_PFL") : Kxlib_getDolphinsValue("SRH_IPT_PLH_TRD");
            $(b).find(".jb-srch-ipt").attr("placeholder", tx);
            
            //On change la cible de l'input
            //InpuTarGet
            var itg = ( $(b).find(".jb-srch-ipt").data("target") === "fil_mn_pfl" ) ? "fil_mn_trd" : "fil_mn_pfl";
            $(b).find(".jb-srch-ipt").data("target", itg);
        }
        
        //FINALLY
        $(b).find(".jb-srh-rslt-list").data("rng",1);
        
        //Opérations spéciales dues au fait qu'on est dans un changement de module
        if ( icm ) {
            /*
            * Si on est dans le cas d'un changement de module, on récupère le texte contenu dans l'input de l'autre module.
            * Le texte servira pour la recherche qui sera lancée.
            */
            //tb : TempBloc
            var tb = ( sc === "min" ) ? ".jb-srh-hvy-b": ".jb-srh-asd-b";
            var tqt = $(tb).find(".jb-srch-ipt").val();
            $(b).find(".jb-srch-ipt").val(tqt);
            
            //La cible de INPUT a sans doute été changée, il faut l'ajustée
            if ( $(b).find(".jb-srch-ipt").data("target") !== $(x).data("target") ) {
                var foo = ( $(b).find(".jb-srch-ipt").data("target") === "fil_mn_pfl" ) ? "fil_mn_trd" : "fil_mn_pfl";
                $(b).find(".jb-srch-ipt").data("target", foo);
            }
//            Kxlib_DebugVars([$(b).find(".jb-srh-rslt-list").data("dt")],true);
            
            //Changement du type pour la liste (en gros le type de données acceptées dans la liste)
            //TempListType
            var tlt = ( $(x).data("target") === "fil_mn_pfl" ) ? "pf" : "tr";
            if ( $(b).find(".jb-srh-rslt-list").data("dt") !== tlt ) {
                var bar = ( $(b).find(".jb-srh-rslt-list").data("dt") === "pf" ) ? "tr" : "pf";
                $(b).find(".jb-srh-rslt-list").data("dt",bar);
//                alert("Changement mode cause ICM => De "+$(b).find(".jb-srh-rslt-list").data("dt")+"; A => "+bar);
            }
            
        }
            
        
        //S'il y a du texte dans la zone de texte on lance une recherche
        var ipt = $(b).find(".jb-srch-ipt");
        var qt = $(ipt).val();
//        alert(qt.length);
        if ( qt.length ) {
            var o = {
                "qsp": $(ipt).data("qsp"),
                "flm": ( $(ipt).data("target") === "fil_mn_pfl" ) ? "pf" : "tr",
                "flc": ( $(ipt).data("qsp") === "min" ) ? "at" : $(".jb-srh-hy-fil.focus").data("obj")
            };
            
            //On crée le marqueur RNG (RaNGe)
            o.rng = _f_GetRng(o.qsp,o.flc);
            
            oqt = "";
            
//            Kxlib_DebugVars([183,JSON.stringify(o),$(ipt).data("target")],true);
//            return;
            _f_SrhTrgr(qt,o);
        }
    };
    
    //TimeOut
    var to;
    //OldQueryText
    var oqt;
    var _f_CatchQry = function (x) {
//    this.CatchQry = function (x) {
        //isp : IsuPKey
        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
        
        var wi = _f_Gdf().wtt;
        if ( to ) {
            clearTimeout(to);
        }
        //QueryText
        var qt = $(x).val();
        if ( qt.length ) {
            to = setTimeout(function() {
                var o = {
                    "qsp": $(x).data("qsp"),
                    "flm": ( $(x).data("target") === "fil_mn_pfl" ) ? "pf" : "tr",
                    "flc": ( $(x).data("qsp") === "min" ) ? "at" : $(".jb-srh-hy-fil.focus").data("obj")
                };
        
                //On crée le marqueur RNG (RaNGe)
                o.rng = _f_GetRng(o.qsp,o.flc);
                
//                Kxlib_DebugVars([JSON.stringify(o)],true);
//                return;
                _f_SrhTrgr(qt,o);
            },wi);
        } else {
            //On reset OLD
            oqt = "";
            
            var qsp = $(x).data("qsp");
            
            //Masquer "More"
            _f_HidMr(qsp);
            
            //On retire le loader
            _f_HidLdr(qsp);
            
            //On enlève les résultats de l'ancienne requête
            _f_WpSrhRes(qsp);
            
            //On fait analyser par NoOne
            _f_NoOne(qsp);
            
        }
        
    };
    
    var _f_SrhTrgr = function (qt,o,imr) {
        //imr : IsMoreResult (Heavy)
        try {
            if ( KgbLib_CheckNullity(qt) | KgbLib_CheckNullity(o) ) {
                return;
            }

            if ( qt === oqt ) {
                return false;
            }

            var s = $("<span/>");

            oqt = qt;

            //Masque AVOID WHITE STYLE
            _f_HidAWS();

            //Masquer "More"
            _f_HidMr(o.qsp);

            //On masque NoOne
            _f_HidNoOne(o.qsp);

            //On contacte le serveur
            _f_Srv_SrhTrgr(qt,o.qsp,o.flm,o.flc,o.rng,s);

            //On enlève les résultats de la recherche précédente
            if (! imr ) {
                 _f_WpSrhRes(o.qsp);
            }

            //On affiche le loader (l'endroit dépend du cas 'imr')
            _f_ShwLdr(o.qsp,imr);

            $(s).on("datasready", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }

                /*
                 * [DEPUIS 25-06-16]
                 * ETAPE :
                 *      On vérifie que le pointeur est toujours actif.
                 *      Dans le cas contraire cela voudrait dire que l'opération a été annulée
                 */
                if ( KgbLib_CheckNullity(_xhr_srh) ) {
                    /*
                     * ETAPE :
                     *      On efface le contenu de la barre de recherche
                     */
                    $(".jb-srch-ipt").val("");

                   /*
                    * ETAPE :
                    *      On efface la recherche précédente stockée pour permettre de refaire la même recherche.  
                    */
                   oqt = "";

                   var scp = "min";
                    /*
                     * ETAPE :
                     *      Afficher AVOID WHITE STYLE
                     */
                    _f_ShwAWS();

                    /*
                     * ETAPE :
                     *      Masquer "More"
                     */
                    _f_HidMr(scp);

                    /*
                     * ETAPE :
                     *      On hide le loader
                     */
                    _f_HidLdr(scp);

                    /*
                     * ETAPE :
                     *      On hide le NoOne
                     */
                    _f_HidNoOne(scp);

                    /*
                     * ETAPE :
                     *      On efface les résultats
                     */
                    _f_WpSrhRes(scp);
                    
                    return;
                }

                //Affichage des résultats
                _f_ShwRslt(o.flm,d,o.qsp,imr);

                //Afficher "More"
                _f_ShwMr(o.qsp);

            });

            $(s).on("operended", function(e) {
                //Masquer "More"
                _f_HidMr(o.qsp);

                //On masque le loader
                _f_HidLdr(o.qsp); 

                //On supprime le résultat de la précédente recherche
                _f_WpSrhRes(o.qsp);

                //On fait analyser la zone par NoOne
                _f_NoOne(o.qsp);
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_CkResExst = function (s,i) {
      //s : Scope, i: Identifiant
      /*
       * Vérifie si un élément existe déjà dans la liste.
       * Pour ce faire, on récupère les données suivantes : 
       *    (1) Le SCoPe qui va nous permettre de déterminer : 
       *        (10) Le bloc
       *    (2) L'identifiant qui nous servira former notre selecteur
       */  
      
       if ( KgbLib_CheckNullity(s) | KgbLib_CheckNullity(i) ) {
            return;
        }
        
        var b = ( s === "min" ) ? ".jb-asd-sr-bdy-rlist" : ".jb-srh-hy-rslt-mx";
//        var b = $(".jb-srh-rslt-list");
        
        var cn = $(b).find(".jb-srh-rslt-mdl[data-item="+i+"]").length;
        
        if ( cn ) {
            return true;
        } else {
            return false;
        }
      
    };
    
    var _f_OpenHvy = function (x) {
         if ( KgbLib_CheckNullity(x) ) {
            return;
         }
         
        var o = {
            "qsp": $(x).data("qsp"),
            "flm": $(x).data("target"),
            "flc": ( $(x).data("qsp") === "min" ) ? "at" : $(".jb-srh-hy-fil.focus").data("obj")
        }; 
        

        //On sélectionne le bloc. On prend le bloc contraire du scope actuel
        var b = ( $(x).data("qsp") === "min" ) ? ".jb-srh-hvy-b" : ".jb-srh-asd-b";
        
//        Kxlib_DebugVars([JSON.stringify(o),".jb-srh-swh-mn[data-target='"+o.flm+"']",$(b).find(".jb-srh-swh-mn[data-target='"+o.flm+"']").length],true);
//        return;
        
        //On switch les menus
        //mtg : Menu TrigGer
//        var t = ( o.flm === "fil_mn_pfl" ) ? "fil_mn_trd" : "fil_mn_pfl";
        var mtg = $(b).find(".jb-srh-swh-mn[data-target='"+o.flm+"']");
        if (! $(mtg).length ) 
            return;
        
        //On déclare à 1 le range
        $(b).find(".jb-srh-rslt-list").data("rng",1);
        
        _f_SwMn($(mtg),true);
        
        //On affiche Heavy
        $(".jb-srh-hvy-sprt").removeClass("this_hide");
        
    };
    
    var _f_ClzHvy = function () {
//    this.CloseHvy = function () {
        /*
         * Ferme proprement Heavy de telle sorte qu'à la prochaine ouverture la fenêtre soit reset.
         * Cela permet de garantir au mieux la fiabilité du code
         */
        try {
            
            var b = $(".jb-srh-hvy-b");
            
            //On masque Heavy
            $(".jb-srh-hvy-sprt").addClass("this_hide");
            
            //On vide l'input
            $(b).find(".jb-hvy-srch-ipt").val("");
            
            //On réinitialise les filtres
            if ($(".jb-srh-hy-fil.focus").data("obj") !== "kw") {
                $(".jb-srh-hy-fil.focus").removeClass("focus");
                $(".jb-srh-hy-fil[data-obj='kw']").addClass("focus");
            }
            
            //On déclare à 1 le range
            $(b).find(".jb-srh-rslt-list").data("rng", 1);
            
            //On fait que le menu de base soit "Compte"
            var mtg = $(b).find(".jb-srh-swh-mn[data-target='fil_mn_pfl']");
            if (!$(mtg).length) 
                return;
            _f_SwMn($(mtg));
            
            //On reinit oqt pour permettre de nouvelles recherches "de suite"
            oqt = "";
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    //FeTCHDeePeR
    var _f_FtchDpr = function (x) {
        try {
            
            //On récupère le texte s'il existe
            var qt = "";
            if (!$(".jb-hvy-srch-ipt").length || !$(".jb-hvy-srch-ipt").val().length) {
                return;
            }
            qt = $(".jb-hvy-srch-ipt").val();
            
            //Récupère les données paramètres
            var s = "hvy";
            var c = $(".jb-srh-hy-fil.focus").data("obj");
            
            //On incrémente le range
            var r = $(".jb-srh-hvy-b").find(".jb-srh-rslt-list").data("rng");
            r += 1;
            $(".jb-srh-hvy-b").find(".jb-srh-rslt-list").data("rng", r);
            
            var o = {
                "qsp": s,
                "flm": ($(".jb-srh-swh-mn.focus").data("target") === "fil_mn_pfl") ? "pf" : "tr",
                "flc": c
            };
            
            o.rng = _f_GetRng(s, c);
//        Kxlib_DebugVars([JSON.stringify(o),qt,r],true);
//        return;
            
            oqt = "";
            
            //On lance la recherche
            _f_SrhTrgr(qt, o, true);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_GoFilt = function (x) {
        /*
         * Fecth données en fonction du filtre lié au déclencheur
         */
        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
        try {
            
            //On change visuellement le menu s'il est différent
            if (!$(".jb-srh-hy-fil.focus").length || $(".jb-srh-hy-fil.focus").length > 1) {
                return;
            } else if (!$(x).is(".jb-srh-hy-fil.focus")) {
                //of= OldFocus
                $(".jb-srh-hy-fil.focus").toggleClass("focus");
                $(x).toggleClass("focus");
            }
            
            //On réinitialise le range
            $(".jb-srh-hvy-b").find(".jb-srh-rslt-list").data("rng", 1);
            
            var o = {
                "qsp": $(x).data("qsp"),
                "flm": ($(x).data("target") === "fil_mn_pfl") ? "pf" : "tr",
                "flc": $(x).data("obj")
            }; 
            
            //Récupérer Range
            o.rng = _f_GetRng(o.qsp, o.flc);
            
            //On récupère le texte
            var qt = $(".jb-hvy-srch-ipt").val();
            
//        Kxlib_DebugVars([JSON.stringify(o),qt],true);
//        return;
            
            //On lance la recherche
            if (qt.length) {
                oqt = "";
                _f_SrhTrgr(qt, o);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_GetRng = function (s,c) {
        //s: Scope; c: Category
        if ( KgbLib_CheckNullity(s) | KgbLib_CheckNullity(c) )
            return;
        
        //r: Range
        var r;
        if ( c === "at" ) {
            r = c+"_rng_1";
        } else {
            var b = ( s === "min" ) ? ".jb-srh-asd-b" : ".jb-srh-hvy-b";
            //tr : TempRange
            var tr = $(b).find(".jb-srh-rslt-list").data("rng");
            var rn = ( tr ) ? tr : 1;
            r = c+"_rng_"+rn;
        }
        
        return r;
    };
    
    //CheckGeneralOperation
    var _f_CkGnOp = function (x) {
//    this.CkGnOp = function (x) {
        if ( KgbLib_CheckNullity(x) ){
            return;
        }
        try {
            
//         Kxlib_DebugVars([$(x).data("action"),$(x).data("qsp")],true);
            
            var p = _f_CkRqBas(x);
            if (!p) {
                return;
            }
            
//       Kxlib_DebugVars([p.a],true);
            
            switch (p.a.toString().toLowerCase()) {
                case "fil_menu" :
                        _f_SwMn(x);
                    break;
                case "smr" :
                    if ($(x).data("qsp") === "min") {
                        _f_OpenHvy(x);
                    } else {
                        _f_FtchDpr(x);
                    }
                    break;
                case "fil_datas" :
                        _f_GoFilt(x);
                    break;
                case "clz":
                        _f_ClzHvy();
                    break;
                default :
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Shbx_Jump = function(x) {
        if ( KgbLib_CheckNullity(x) | !$(x).length | KgbLib_CheckNullity($(x).data("action")) ) {
            return;
        }
        
        try {
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            
            //On affiche le spinner
            _f_JmpSpnr(x,true);
            
            //TODO : On bloque le système de navigation en module
             
            //TODO : On bloque le système de sélection de scope 
            
            var a = $(x).data("action"), o__;
            switch (a) {
                case "jump_account" :
                        o__ = {
                            object: "account",
                            url: Document.URL
                        };
                    break;
                case "jump_trend" :
                        o__ = {
                            object: "trend",
                            url: Document.URL
                        };
                    break;
                default:
                    return;
            }
            
            var s = $("<span/>");
            
            _f_Srv_SrhJmp(obj,url,s);
            
            $(s).on("datasready",function(e,d){
                
            });
            
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*************************************************************************************************************************************************/
    /****************************************************************** SERVER SCOPE *****************************************************************/
    /*************************************************************************************************************************************************/
    
    var _Ax_SrhTrgr = Kxlib_GetAjaxRules("TQR_SEARCH");
    var _f_Srv_SrhTrgr = function(qt,qsp,flm,flc,rng,s) {
        if ( KgbLib_CheckNullity(qt) | KgbLib_CheckNullity(qsp) | KgbLib_CheckNullity(flm) | KgbLib_CheckNullity(rng) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else return;
                
                if ( !KgbLib_CheckNullity(d.err)) {
                    
                    if (Kxlib_AjaxIsErrVolatile(d.err)) {
                        switch (d.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_OWNER_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                                    Kxlib_HandleCurrUserGone();
                                break;
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
                                    return;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                return;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                        }
                    } 
                    return;
                } else if ( d.hasOwnProperty("return") )  {
                    /*
                     * Données attendues : 
                     *  (1) Données sur le résultats de la recherche
                     */
                     if (! KgbLib_CheckNullity(d.return)  )  {
                         rds = [d.return];
                         $(s).trigger("datasready",rds);
                     } else {
                         $(s).trigger("operended");
                     }
                } else return;
                
            } catch (e) {
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
            
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        
        var toSend = {
            "urqid": _Ax_SrhTrgr.urqid,
            "datas": {
                "qt"    :qt,
                "qsp"   :qsp,
                "flm"   :flm,
                "flc"   :flc,
                "rng"   : rng
            }
        };

        _xhr_srh = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_SrhTrgr.url, wcrdtl : _Ax_SrhTrgr.wcrdtl });
    };
    
    
    var _Ax_SrhJmp = Kxlib_GetAjaxRules("TQR_SRH_JMP");
    var _f_Srv_SrhJmp = function(qt,qsp,flm,flc,rng,s) {
        if ( KgbLib_CheckNullity(qt) | KgbLib_CheckNullity(qsp) | KgbLib_CheckNullity(flm) | KgbLib_CheckNullity(rng) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else return;
                
                if ( !KgbLib_CheckNullity(d.err)) {
                    
                    if (Kxlib_AjaxIsErrVolatile(d.err)) {
                        switch (d.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_OWNER_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                                    Kxlib_HandleCurrUserGone();
                                break;
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
                                    return;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
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
                } else if ( d.hasOwnProperty("return") )  {
                    /*
                     * Données attendues : 
                     *  (1) Données sur le résultats de la recherche
                     */
                     if (! KgbLib_CheckNullity(d.return)  )  {
                         rds = [d.return];
                         $(s).trigger("datasready",rds);
                     } else {
                         $(s).trigger("operended");
                     }
                } else return;
                
            } catch (e) {
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
            
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        
        var toSend = {
            "urqid": _Ax_SrhTrgr.urqid,
            "datas": {
                "qt":qt,
                "qsp":qsp,
                "flm":flm,
                "flc":flc,
                "rng": rng
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_SrhTrgr.url, wcrdtl : _Ax_SrhTrgr.wcrdtl });
    };
    
    /*******************************************************************************************************************************************************/
    /********************************************************************** VIEW SCOPE *********************************************************************/
    /*******************************************************************************************************************************************************/
    
    var _f_ShwRslt = function (rt,rd,scp,imr) {
        //imr : IsMoreResult (Heavy)
        //rt: ResultType, rd; ResultDatas
        
        if ( KgbLib_CheckNullity(rt) | KgbLib_CheckNullity(rd) | KgbLib_CheckNullity(scp) ) {
            return;
        }
//        Kxlib_DebugVars([523,rt,scp],true);
        
        //On masque le loader
        _f_HidLdr(scp);
        
        //On retire le marqueur NoOne
        _f_HidNoOne(scp);
        
        if (! imr ) {
            //On enlève les résultats de la recherche précédente
            _f_WpSrhRes(scp);
        }
        
        //On sélectionne le block
        var b = ( scp === "min" ) ? $(".jb-srh-asd-b").find(".jb-srh-rslt-list") : $(".jb-srh-hvy-b").find(".jb-srh-rslt-list");
//        var b = ".jb-srh-rslt-list";
        
        var e;
        $.each(rd,function(x,v){
            //On prépare le modèle
            if ( rt === "pf" ) {
                //On vérifie pour des raisons de fiabilité que l'élément n'est pas déjà présent
                if ( _f_CkResExst(scp,v.upsd) ) {
//                    alert("Exists");
                    return;
                }
                
                /*
                 * On vérifie que les données vont bien dans la bonne liste.
                 * Ce controle est necessaire dans le cas où on change de menu en cours de route.
                 */
                var md = $(b).data("dt");
                if ( rt !== md ) {
//                    Kxlib_DebugVars([552,"Mode issue",rt,md],true);
                    return;
                }
                        
                e = _f_PprPflMdl(v,scp);
                
                $(e).hide().appendTo(b).fadeIn();
//                Kxlib_DebugVars([Ajouté avec => Mode : "+md+"; DataType :"+rt]);
            } else {
                //On vérifie pour des raisons de fiabilité que l'élément n'est pas déjà présent
                if ( _f_CkResExst(scp,v.i) ) {
//                    alert("exists");
                    return;
                }
                
                 /*
                 * On vérifie que les données vont bien dans la bonne liste.
                 * Ce controle est necessaire dans le cas où on change de menu en cours de route.
                 */
                var md = $(b).data("dt");
                if ( rt !== md ) {
//                    alert("Mode issue");
                    return;
                }
                        
                e = _f_PprTrdMdl(v,scp);
                
                $(e).hide().appendTo(b).fadeIn();
//                Kxlib_DebugVars([Ajouté avec => Mode : "+md+"; DataType :"+rt]);
            }
        });
        
        
        return true;
    };
    
    this._PrepareFullMdl = function () {
        
    };
    
    //Wp :Wipe
    var _f_WpSrhRes = function(scp) {
         if ( KgbLib_CheckNullity(scp) ) 
            return;
        
        var b = ( scp === "min" ) ? ".jb-asd-sr-bdy": ".jb-srh-hy-rslt-mx";
        $(b).find(".jb-srh-rslt-list").find(".jb-srh-rslt-mdl").remove();
        
    };
    
    var _f_WipeSrhBar = function(x) {
//    this._WipeSrhBar = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            /*
             * [DEPUIS 25-06-16]
             * ETAPE :
             *      On annule l'opération en cours si elle existe
             */
            if (! KgbLib_CheckNullity(_xhr_srh) ) {
                _xhr_srh.abort();
                _xhr_srh = null;
            }

            /*
             * On efface le contenu de la barre de recherche
             */
            $(x).val("");

            /*
             * ETAPE :
             *      On efface la recherche précédente stockée pour permettre de refaire la même recherche.  
             */
            oqt = "";

            var scp = $(x).data("qsp");
    //        var b = ( scp === "min" ) ? ".jb-asd-sr-bdy" : ".jb-srh-hy-rslt-mx";       

    //        $(b).find(".jb-srh-rslt-mdl").remove();

            /*
             * ETAPE :
             *      Afficher AVOID WHITE STYLE
             */
            _f_ShwAWS();

            /*
             * ETAPE :
             *      Masquer "More"
             */
            _f_HidMr(scp);

            /*
             * ETAPE :
             *      On hide le loader
             */
            _f_HidLdr(scp);

            /*
             * ETAPE :
             *      On hide le NoOne
             */
            _f_HidNoOne(scp);

            /*
             * ETAPE :
             *      On efface les résultats
             */
            _f_WpSrhRes(scp);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ShwLdr = function(scp,imr) {
        if ( KgbLib_CheckNullity(scp) ) 
            return;
        
        var b = ( scp === "min" ) ? ".jb-asd-sr-bdy": ".jb-srh-hy-rslt-mx";
        
        var ldg = $(b).find(".jb-srh-ldg");
        var lt = $(b).find(".jb-srh-rslt-list");
        if ( imr ) {
            //S'il s'agit du cas où on veut plus de résultat, on affiche ldg à la fin
            $(ldg).parent().insertAfter(lt);
            $(ldg).parent().addClass("bt");
        } else {
            //On le remet au debut
            $(ldg).parent().insertBefore(lt);
            $(ldg).parent().removeClass("bt");
        }
        
        $(ldg).removeClass("this_hide");    
    };
    
    var _f_HidLdr = function(scp) {
        if ( KgbLib_CheckNullity(scp) ) 
            return;
        
        var b = ( scp === "min" ) ? ".jb-asd-sr-bdy": ".jb-srh-hy-rslt-mx";
        $(b).find(".jb-srh-ldg").addClass("this_hide");    
    };
    
    var _f_SwMr = function(scp) {
        /*
         * Permet de changer les données au niveau de l'élément.
         */
        if ( KgbLib_CheckNullity(scp) ) 
            return;
        
        var b = ( scp === "min" ) ? ".jb-srh-asd-b" : ".jb-srh-hvy-b";
            
        var x = $(b).find(".jb-srh-rslt-mr");
        var t = $(x).data("target");
//        Kxlib_DebugVars([x,t],true);
//        return;
        if ( t ) {
            var nt = ( t === "fil_mn_pfl" ) ? "fil_mn_trd" : "fil_mn_pfl";
            $(x).data("target",nt);
        }
        
    };
    
    
    var _f_ShwMr = function(scp) {
        if ( KgbLib_CheckNullity(scp) ) 
            return;
        
        var b = ( scp === "min" ) ? ".jb-srh-asd-b" : ".jb-srh-hvy-b";
        $(b).find(".jb-srh-rslt-mr").removeClass("this_hide");
    };
    
    var _f_HidMr = function(scp) {
        if ( KgbLib_CheckNullity(scp) ) 
            return;
        
        var b = ( scp === "min" ) ? ".jb-srh-asd-b" : ".jb-srh-hvy-b";
        $(b).find(".jb-srh-rslt-mr").addClass("this_hide");
    };
    
    var _f_HidNoOne = function (scp) {
        
        if ( KgbLib_CheckNullity(scp) ) 
            return;
        
        var b = ( scp === "min" ) ? ".jb-asd-sr-bdy" : ".jb-srh-hy-rslt-mx";
        $(b).find(".jb-srh-no1e").addClass("this_hide");
    };
    
    ///AWS = AvoidWhiteStyle
    var _f_ShwAWS = function () {
        $(".jb-asd-sr-bdy-nvd").removeClass("this_hide");
    };
    
    var _f_HidAWS = function () {
        $(".jb-asd-sr-bdy-nvd").addClass("this_hide");
    };
    
    /************ MODEL BUILDING **********/
    var _f_PprPflMdl = function(d,s) {
        if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        /*
         * REL CODE :
         * 'nn' = (NoNe) Aucune relation,
         * 'fl' = (FoLlow) Abonnement,
         * 'df' = (DoubleFoLlow) Abonnement,
         * 'fr' = (Friend) Amis
         */
        
        var fn = Kxlib_Decode_After_Encode(d.ufn);
        var psd = Kxlib_Decode_After_Encode(d.upsd);
        
        //Détermination du texte pour la relation
        var ur = "";
        if ( d.hasOwnProperty("urel") && !KgbLib_CheckNullity(d.urel) ) {

            switch (d.urel) { 
                case "nn" :
                        ur = "";
                    break;
                case "fl" :
                        ur = Kxlib_getDolphinsValue("REL_FL");
                    break;
                case "df" :
                        ur = Kxlib_getDolphinsValue("REL_DFL1");
                    break;
                case "fr" :
                        ur = Kxlib_getDolphinsValue("REL_FR");
                    break;
                default:
                    break;
            }
        }
        
        /*
         * [DEPUIS 07-09-15] @author BOR
         */
        var hrf = "/"+d.upsd;
        var cl__ = "";
        if ( d.hasOwnProperty("acctdl") && !KgbLib_CheckNullity(d.acctdl) ) {
            if ( parseInt(d.acctdl) === 1 ) {
                hrf = "javascript:;";
                cl__ = "gone";
            }
        }
            
        if ( s === "min" ) {
            /*
             * [NOTE 25-06-15] @BOR
             *  -> On insère pas '@' dans href car cette manière de faire se révèl plus lente lorsqu'il s'agit d'atteindre la page du Compte concerné. 
             *  -> On passe à un format arrondi avec une bordure inferieure
             *  -> On ajoute '@' pour le pseudo pour des raisons esthétiques
             */
            var e = "<div class=\"asd-sr-min-pfl-mdl jb-srh-rslt-mdl\" data-item=\""+d.upsd+"\">";
            e += "<div class=\"asd-sr-min-ugrp\">";
            e += "<a class=\"asd-sr-min-ugp-mn "+cl__+"\" href=\""+hrf+"\" >";
            e += "<span class=\"asd-sr-min-ugp-i-fade\"></span>";
            e += "<img class=\"asd-sr-min-ugp-img\" src=\""+d.uppic+"\" width=\"60\" />";
            e += "<span class=\"asd-sr-min-ugp-psd\">@"+psd+"</span>";
            e += "</a>";
            e += "<div class=\"asd-sr-min-ug-fn\">";
            e += "<span class=\"asd-sr-min-ug-fn\">"+fn+"</span>";
            e += "</div>";
            e += "</div>";
            e += "<div class=\"asd-sr-min-urel\">";
            e += "<span class=\"\">"+ur+"</span>";
            e += "</div>";
            e += "</div>";
            
            e = $.parseHTML(e);
            
        } 
        /*
        else {
            
            var e = "<div class=\"srh-hy-pfl-mdl-mx jb-srh-rslt-mdl\" data-item=\""+d.upsd+"\">";
            e += "<div class=\"srh-hy-pfl-mdl-top\">";
            e += "<div class=\"srh-hy-pfl-mdl-ugrp-mn clearfix2\">";
            e += "<div class=\"srh-hy-pfl-mdl-ugrp-1\">";
            e += "<a class=\"srh-hy-pfl-mdl-ugrp-trg\" href=\"/@"+d.upsd+"\">";
            e += "<img class=\"srh-hy-pfl-mdl-ugrp-ppc\" src=\""+d.uppic+"\" width=\"70\" height=\"70\"/>";
            e += "<span class=\"srh-hy-pfl-mdl-ugrp-psd\">"+psd+"</span>";
            e += "</a>";
            e += "</div>";
            e += "<div class=\"srh-hy-pfl-mdl-ugrp-fn\">"+fn+"</div>";
            e += "</div>";
            e += "<div class=\"srh-hy-pfl-mdl-ugrp-xtra\">";
            e += "</div>";
            e += "</div>";
            e += "<div class=\"srh-hy-pfl-mdl-bot\">";
            e += "<span class=\"srh-hy-pfl-mdl-cap-mx\">";
            e += "<span class=\"srh-hy-pfl-mdl-cap-nb\">"+d.ucap+"</span>";
            e += "<span class=\"srh-hy-pfl-mdl-cap-lib\">Coo!</span>";
            e += "</span>";
//            e += "<span class=\"srh-hy-pfl-mdl-folg-mx\">";
//            e += "<span class=\"srh-hy-pfl-mdl-folg-nb\">1</span>";
//            e += "<span class=\"srh-hy-pfl-mdl-folg-lib\">Abonnements</span>";
//            e += "</span>";
            e += "<span class=\"srh-hy-pfl-mdl-folw-mx\">";
            e += "<span class=\"srh-hy-pfl-mdl-folw-nb\">"+d.ufols+"</span>";
            e += "<span class=\"srh-hy-pfl-mdl-folw-lib\">Abonnés</span>";
            e += "</span>";
            if (! KgbLib_CheckNullity(ur) )
                e += "<span class=\"srh-hy-pfl-mdl-rel\">"+ur+"</span>";
            e += "</div>";
            e += "</div>";
            
            e = $.parseHTML(e);
            
        }
        //*/
        return e;
        
    };
    
    var _f_PprTrdMdl = function(d,s) {
        if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        /*
         * [DEPUIS 07-09-15] @author BOR
         */
        var hrf = d.hrf;
        var cl__ = "";
        if ( d.hasOwnProperty("acctdl") && !KgbLib_CheckNullity(d.acctdl) ) {
            if ( parseInt(d.acctdl) === 1 ) {
                hrf = "javascript:;";
                cl__ = "gone";
            }
        }
        if ( s === "min" ) {
            
            var tle = Kxlib_Decode_After_Encode(d.tle);
            
            var e = "<div class=\"asd-sr-min-trd-mdl jb-srh-rslt-mdl\" data-item=\""+d.i+"\">";
            e += "<div class=\"asd-sr-m-t-mdl-top clearfix2\">";
//            e += "<div class=\"asd-sr-m-t-mdl-t-lft\">";
//            e += "<a class=\"asd-sr-m-t-mdl-tr-lg-mx\" href=\"javascript:;\">";
//            e += "<span class=\"asd-sr-m-t-mdl-tr-lg\">T</span>";
//            e += "</a>";
//            e += "</div>";
            e += "<div class=\"asd-sr-m-t-mdl-t-rgt\">";
            e += "<a class=\"asd-sr-m-t-mdl-tr-tle "+cl__+"\" href=\""+hrf+"\"></a>";
            e += "</div>";
            e += "<div class=\"asd-sr-m-t-mdl-tr-dsc\">"+d.dsc+"</div>";
            e += "</div>";
            e += "<div class=\"asd-sr-m-t-mdl-bot clearfix2\">";
            e += "<span class=\"asd-sr-m-t-mdl-b-fol\">";
            e += "<span class=\"asd-sr-m-t-mdl-b-fol-nb\">"+d.fnb+"</span>";
            e += "<span class=\"asd-sr-m-t-mdl-b-fol-lib\">Abo.</span>";
            e += "</span>";
            e += "<span class=\"asd-sr-m-t-mdl-b-pst\">";
            e += "<span class=\"asd-sr-m-t-mdl-b-pst-nb\">"+d.pnb+"</span>";
            e += "<span class=\"asd-sr-m-t-mdl-b-pst-lib\">Posts</span>";
            e += "</span>";
            e += "</div>";
            e += "</div>";
            
            e = $.parseHTML(e);
            
            /*
             * [NOTE 03-05-15] @BOR
             * Cette méthode tente de se prémunir au maximum des injections XSS
             */
            $(e).find(".asd-sr-m-t-mdl-tr-tle").text(tle);
            
        } else {
            
            var tle = Kxlib_Decode_After_Encode(d.tle);
            var dsc = Kxlib_Decode_After_Encode(d.dsc);
            
            var e = "<div class=\"srh-hy-tr-mdl-mx jb-srh-rslt-mdl\" data-item=\""+d.i+"\">";
            e += "<div class=\"srh-hy-tr-mdl-top clearfix2\">";
            e += "<div class=\"srh-hy-tr-lg\">";
            e += "<img src=\"\" />";
            e += "</div>";
            e += "<div class=\"srh-hy-tr-spcs-mx\">";
            e += "<div class=\"srh-hy-tr-mdl-spcs-mn-l\">";
            e += "<div class=\"srh-hy-tr-mdl-s-mn-tle-bx\">";
            e += "<a class=\"srh-hy-tr-mdl-s-mn-tle\" href=\""+d.hrf+"\"></a>";
            e += "</div>";
            e += "<div class=\"clearfix2\">";
            
            if ( d.rl === "me" ) {
                e += "<div class=\"srh-hy-tr-mdl-spcs-mn-r\">";
                e += "<span class=\"srh-hy-tr-mdl-spcs-cnx\">";
                e += "<img width=\"22\" height=\"22\" src=\""+Kxlib_GetExtFileURL("sys_url_img", "r/tr_cntd.png")+" />";
//                e += "<img width=\"22\" height=\"22\" src=\"\" />";
                e += "<span>Connecté</span>";
                e += "</span>";
                e += "</div>";
            }
            
            e += "<p class=\"srh-hy-tr-mdl-s-mn-desc\">"+dsc+"</p>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e += "<div class=\"srh-hy-tr-mdl-bot\">";
            e += "<a class=\"srh-hy-tr-mdl-s-x-psd\" href=\"/@"+d.op+"\" title=\""+d.of+"\">@"+d.op+"</a>";
            e += "<span class=\"srh-hy-tr-mdl-s-x-pst-grp\">";
            e += "<span class=\"srh-hy-tr-mdl-s-x-pp-nb\">"+d.pnb+"</span>";
            e += "<span class=\"srh-hy-tr-mdl-s-x-pp-lib\">Posts</span>";
            e += "</span>";
            e += "<span class=\"srh-hy-tr-mdl-s-x-fol-grp\">";
            e += "<span class=\"srh-hy-tr-mdl-s-x-fp-nb\">"+d.fnb+"</span>";
            e += "<span class=\"srh-hy-tr-mdl-s-x-fp-lib\">Abonnés</span>";
            e += "</span>";
//            e += "<span class='kxlib_tgspy srh-hy-tr-mdl-tm' data-tgs-crd='1413382230000' data-tgs-dd-atn='' data-tgs-dd-uut=''>";
//            e += "<span class='tgs-frm'>Il y a </span>";
//            e += "<span class='tgs-val'>3 </span>";
//            e += "<span class='tgs-uni'>jours</span>";
//            e += "</span>"; 
            e += "</div>";
            e += "</div>";
            
            e = $.parseHTML(e);
             
            /*
             * [NOTE 03-05-15] @BOR
             * Cette méthode tente de se prémunir au maximum des injections XSS.
             * RAPPEL : Heavy a été désactivé mais on effectue tout de même la mise à jour pour la prochaine fois
             */
            $(e).find(".srh-hy-tr-mdl-s-mn-tle").text(tle);
        }
        
        return e;
        
    };
    
    var _f_JmpSpnr = function(x,shw){
        if ( KgbLib_CheckNullity(x) | !$(x).find(".jb-asd-sr-j-txt")| KgbLib_CheckNullity($(x).find(".jb-asd-sr-j-txt").length) ) {
            return;
        }
        
        if (shw) {
            $(x).find(".jb-asd-sr-j-txt").addClass("this_hide");
            $(x).addClass("loading");
            $(x).find(".jb-asd-sr-j-ldg").removeClass("this_hide");
        } else {
            $(x).find(".jb-asd-sr-j-ldg").removeClass("this_hide");
            $(x).find(".jb-asd-sr-j-txt").removeClass("this_hide");
            $(x).removeClass("loading");
        }
        
    };
    
    /*******************************************************************************************************************************************************/
    /******************************************************************* LISTENERS SCOPE *******************************************************************/
    /*******************************************************************************************************************************************************/
    
    /***************** PROCESS SCOPE *********************/
    $(".jb-asd-sr-ipt-rst").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_WipeSrhBar($(".jb-asd-srch-ipt"));
    });
    
    /*
     * [NOTE 15-04-15] @BOR
     * Je retire la fonctionnalité de Heavy car elle n'est pas optimale.
     * Etant donné, la durée de vie de la version beta1, cela ne posera pas de problème si on a pas tous les résultats.
     * Il suffira à l'utilisateur d'être précis dans sa recherche
     */
    /*
    $(".jb-srh-rslt-mr").click(function(e) {
        Kxlib_PreventDefault(e);
        
        _f_CkGnOp(this);
    });
    //*/
    $(".jb-srh-hy-clz").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_ClzHvy();
    });
    
    $(".jb-srh-swh-mn").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_CkGnOp(this);
    });
    
    $(".jb-srh-hy-fil").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_CkGnOp(this);
    });
    
    //ACQUISITION DU TEXTE
    $(".jb-srch-ipt").keyup(function(){
        _f_CatchQry(this);
    });
    
    /**************** VIEW ANIMATION *********************/
    
    $(".jb-asd-srch-ipt").hover(function(){
        $(".asd-sr-hdr-ipt-wrap").addClass("hover");
        $(".jb-asd-sr-ipt-rst").addClass("hover");
    }, function(){
        if (! $(this).is(":focus") ) {
            $(".asd-sr-hdr-ipt-wrap").removeClass("focus");
            $(".jb-asd-sr-ipt-rst").removeClass("focus");
        }
        $(".asd-sr-hdr-ipt-wrap").removeClass("hover");
        $(".jb-asd-sr-ipt-rst").removeClass("hover");
    });
    
    $(".jb-asd-srch-ipt").focus(function() {
        $(".asd-sr-hdr-ipt-wrap").addClass("focus");
        $(".jb-asd-sr-ipt-rst").addClass("focus");
    });
    
    $(".jb-asd-srch-ipt").blur(function(){
        $(".asd-sr-hdr-ipt-wrap").removeClass("focus");
        $(".jb-asd-sr-ipt-rst").removeClass("focus");
        
        if (! $(this).is(":hover") ) {
            $(".asd-sr-hdr-ipt-wrap").removeClass("hover");
            $(".jb-asd-sr-ipt-rst").removeClass("hover");
        }
    });
    
    $(".jb-asd-sr-jump-tgr").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Shbx_Jump(this);
    });
}

new SRHBOX();