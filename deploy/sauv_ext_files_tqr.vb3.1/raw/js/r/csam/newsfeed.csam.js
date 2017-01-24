/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function NewsFeed () {
    /*
     * 
     * FONCTIONNALITES
     *  Version : b.1503.1.1 (Mars 2015 VBeta1) [DATE UPDATE : 17-03-15]
     *      -> Ajustement de la taille des blocs automatiquement en fonction de la taille de l'écran et du mode d'affichage 
     *      -> Recevoir l'Activité de "Mon Réseau" :
     *          -> Je recois les Articles de mes Amis : IML, ITR
     *          -> Je recois les Articles de mes abonnements S_FOLW & D_FOLW : ITR
     *          NOTE : Je ne recois pas les Articles IML pour la Relation D_FOLW car cela peut vite devenir restrictif.
     *                 Exemple : Je viens d'être suivi par un utilisateur. J'aimerais aussi avoir accès à son Activité publique donc il faudrait aussi que je m'abonne.
     *                           Cependant, cela ferait passer notre Relation de S_FOLW à D_FOLW. Si D_FOLW donnait accès aux Articles IML, je ne voudrais sans doute pas qu'il ait accès à mes Articles IML.
     *                           Aussi, je ne le suis pas. Hors cela nuit à l'esprit d'ouverture de Trenqr. De plus, cela peut créer des frictions dans le sens où l'autre utilisateur pourrait se sentir lésé.
     *                           Il va donc aussi mettre fin à notre Relation. Pire encore, si je suis une Personnalité, je lui donnerai accès à des données que je ne veux pas voir tomber en de mauvaises mains.
     *          -> Je recois les Articles des Tendances auxquelles je suis abonnées (sauf les miens) : ITR
     *          -> Je recois les Articles publiés dans mes Tendances (sauf les miens) : ITR
     *      -> Visualiser un Article en mode UNIQUE
     *      -> Vérifier si de nouveaux Articles sont disponibles pour le modèle MOZ et les stocker en attendant qu'ils soient affichés
     *      -> Vérifier si de nouveaux Articles sont disponibles pour le modèle LIST et les stocker en attendant qu'ils soient affichés
     *      -> Afficher une "Notification" qui signale que de nouveaux Articles sont disponibles pour affichage
     *      -> Permettre à l'utilisateur d'avoir accès à un bouton qui permet d'ouvrir la zone (NewsfeedPeekZone) qui indique les Utilisateurs liés aux Articles en attente d'ajout
     *      -> Ouvrir/Fermer l'accès à la zone (NewsfeedPeekZone) 
     *      -> Afficher les nouveaux Articles stockées pour le modèle MOZ depuis le bouton dédié
     *      -> Afficher les nouveaux Articles stockées pour le modèle MOZ depuis le bouton "Tout charger"
     *      -> Afficher les nouveaux Articles stockées pour le modèle LIST depuis le bouton dédié
     *      -> Afficher les nouveaux Articles stockées pour le modèle LIST depuis le bouton "Tout charger"
     *      -> Charger les Articles les plus anciens  pour le modèle LIST
     *      -> Charger les Articles les plus anciens  pour le modèle MOZ
     *      -> Le lancement de l'opération de chargement des Articles plus anciens ne se fait que si la condition temporelle est réunie 
     *      -> Le modèle de l'Article permet une lecture selon deux vues (1) Réduite (2) Originale. L'utilisateur développe la zone en la survolant. 
     *         Cette contrainte permet d'avoir accès visuellement à deux Articles sur un écran supérieur à 17" mais aussi et surtout à Article complet sur un modèle 15"
     *  
     *  EVOLUTIONS ATTENDUES
     *      -> L'utilisateur peut ne charger qu'une partie des données grace à l'interface NewsfeedPeekZone
     *      -> Mise à disposition d'un filtrer pour ne sélectionner que les données qui nous intéresse ou de masquer certaines autres données
     *      -> Mettre en place une date au dessus de l'Article le plus récent publié au cours d'une journée donnée
     *  
     *  EVOLUTIONS POSSIBLES
     *      -> Afficher des informations sur les Articles et mon réseau au niveau des zones latérales
     */
    
    var gt = this;
    var _xhr_ldfst;
    var _xhr_ldfrm_top;
    var _xhr_ldfrm_btm;
    
    /*
     * Le temps d'attente avant de charger de nouveaux éléments lorsqu'on change de MENU/VIEW 
     * * */
    /*
    this.__NWFD_BF_LOAD = 0;
    
    this.__NWFD_LIST_MAXNB = 20;
    this.__NWFD_MOZ_MAXNB = 30;
    
    this.__NWFD_LIST_VIEW = "nwfd-b-list";
    this.__NWFD_MOZ_VIEW = "nwfd-b-moz-max";
    var gth = this;
    //*/
    
    /************************ COMMON ***********************/
    
    //Test de la zone buffer
//    Obj._GetBufferDatas();
    
    var _f_Gdf = function () {
//    this._Gdf = function () {
        var df = {
            "__NWFD_HDR_H"      : 82,
            "__NWFD_BF_LOAD"    : 0,
            //CNA : Check New Articles, Le Temps d'attente avant de pouvoir aller vérifier si de nouveaux Articles existent.
            "__NWFD_CNA_ROT"    : 12905,
            "__NWFD_LIST_MAXNB" : 200, /* Données arbritraires à la version vb1.10.14.x */
            "__NWFD_MOZ_MAXNB"  : 300, /* Données arbritraires à la version vb1.10.14.x */
            "__NWFD_LIST_VIEW"  : "nwfd-b-list",
            "__NWFD_MOZ_VIEW"   : "nwfd-b-moz-max",
            //ABA = ArticleBAtch, Chaque Article NWFD appartient à un groupe. Cette différenciation permet un traitement plus "vrai" des données.
            "__NWFD_ABA_ALW"    : ["_xl_12it","_xl_2im","_xl_3it","_xl_3im","_xl_3im_pod","_xl_mt","_xl_st"],
            "__NWFD_VW_MD"      : ['moz','list']
        };
        
        return df;
    };
    
    var _f_ChkNewLdgAlwd = function (mb) {
//    this._CheckNewLoadingAllowed = function (mb) {
        /*
         * Plusieurs règles pourront déterminer si le téléchargement de nouveaux Articles est autorisé
         * RULES : 
         *  - Le paramétrage côté serveur ne l'autorise pas
         *  - Le nombre d'Articles déjà affichés a atteint son MAX ?
        /* */
        
        //Suivant le nombre MAX
        if (! KgbLib_CheckNullity(mb) ) {
            
            if ( $(mb).data("v") === 'l' ) {
//                Kxlib_DebugVars([isl => "+$(mb).find(".jb-nwfd-art-mdl").length]);
                return ( $(mb).find(".jb-nwfd-art-mdl").length >= _f_Gdf().__NWFD_LIST_MAXNB ) ? false : true;
            } else {
                return ( $(mb).find(".nwfd-b-moz-mdl-max").length >= _f_Gdf().__NWFD_MOZ_MAXNB ) ? false : true;
            }
        }
    };
    
    var _f_GetComposSts = function () {
//    this._GetComposStatus = function () {
        try {
            var rm = $(".jb-nwfd-sprt").data("rm"), 
                access = $(".jb-nwfd-sprt").data("access"), 
                    menu = $(".nwfd-menu-active").data("target"), 
                        view = $(".jb-nwfd-view-active").data("vwcode");

            var d = {
                /*
                 * TimeToCheck_OldArticles : 30 secondes car la probabilité que si on ne trouve rien au premier essai, on trouve quelque chose au deuxième est presque nulle.
                 * Aussi, mettre une grande valeur n'est pas un décision erronée. 
                 * De toutes les façons, il s'agit d'une donnée arbritraire selon que l'expérience utilisateur est bonne ou pas.
                 * 
                 * RAPPEL : Cette valeur est utilisée par les méthodes Sentinel. Pour mieux la comprendre, il serait judicieux de lire ces méthodes.
                 */
                "ttc_oa"    : 30000,
                "rm"        : rm, //1,0
                "access"    : access, //1, 0
                "menu"      : menu, //team, boss, comy, bzfeed
                "view"      : view //moz, list
            };

            return d;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    var _f_GetActvMnB = function () {
//    this._GetActiveMenuBloc = function () {
        /* Permet d'obtenir un Objet représentant le menuBloc actif quelque soit la vue */
        var r = _f_GetComposSts();
        var sl = "#nwfd-"+r.view+"-"+r.menu;
        
        return ( $(sl).length ) ? $(sl) : null;
    };
    
    var _f_GetActvMnB2 = function () {
//    this.GetActiveMenuBloc2 = function () {
        /* Permet d'obtenir un Objet représentant le menuBloc actif quelque soit la vue
         * Cette méthode récupère le bloc actuellement affichée.
         * Je l'ai crée car elle me semblait plus fiable dans le contexte du developpement
         *  */
//        var $o = $(".jb-nwfd-view-bloc").find(":not(.this_hide)");
        var $o = $(".jb-nwfd-b-list:not(.this_hide)"); //[DEPUIS 08-04-16]
        return ( $o.length === 1 ) ? $o : null;
    };
    
    var _f_UpdtComposSts = function () {
//    this.UpdateComposStatus = function () {
        var d = _f_GetComposSts();
            
//        alert("RUNMODE => "+rm+"; ACCESS => "+access+"; MENU => "+menu+"; VIEW => "+view);
        
        //Envoyer les paramètres au niveau du serveur
        _f_Srv_UpdtComposSts(d);
                
    };
    
    var _f_AdjustHInB = function () {
//    this._AdjustHeightInBlocs = function () {
        //$(window).height() - 20 - $(".jb-nwfd-hdr").height() - $(".jb-nwfd-footer").height() //USE FOR : DEV, TEST, DEBUG
        var hnb = $(window).height();
        
//         Kxlib_DebugVars([WHY ? => "+$(".jb-nwfd-hdr").height()]);    
         
        //*** Donne la bonne taille aux blocs en fonction de la taille de l'écran ***\
        //Hauteur du support
        var h_spt = hnb - 20; //20px correspond à l'espace que prennent les bordures
//        alert("Window_Height => "+hnb+"; Height => "+h);
//        return;
        $(".jb-nwfd-sprt").css("height",Kxlib_ToPxUnit(h_spt));
       
        //*** Hauteur du body ***\\
//        var hdr = parseInt($(".jb-nwfd-hdr").height().replace("px",''));
        var hdr = parseInt($(".jb-nwfd-hdr").height());
        
        var h_bd = h_spt - hdr;
//        h_bd -= parseInt($(".jb-nwfd-footer").css("height").replace("px",''));
//        Kxlib_DebugVars([WINDOW => "+hnb+"; SUPPORT => "+h_spt+"; HEADER => "+hdr+"; BODY => "+h_bd+"; FOOTER => "+$(".jb-nwfd-footer").height()]);
//        alert("Window_Height => "+hnb+"; Header => "+hdr+"; Height => "+Kxlib_ToPxUnit(h_bd));
//        return;
        
        $(".jb-nwfd-body").css("height",Kxlib_ToPxUnit(h_bd));
    };
    
    var _f_Init = function (a) {
//    this.Init = function (a) {
        try {
            
            /* On vérifie si le serveur signale qu'on ne touche pas à la configuration */
            var sp = $(".jb-nwfd-sprt").data("svskip");
            if (! KgbLib_CheckNullity(sp) && sp === 1 ) { 
                return; 
            }
            
            /* Place le support de telle sorte qu'il puisse descendre lors de l'ouverture */
            var hnb = $(window).height();
            
            var mrg = hnb * -1;
            mrg = mrg.toString() + "px";
            
            $(".jb-nwfd-sprt").css("margin-top", mrg);
            $(".jb-nwfd-sprt").removeClass("this_hide");
            
            $(".jb-nwfd-mx").removeClass("this_hide");
            $(".jb-nwfd-mx").fadeOut();
            
            //Ajuster la hauteur des différents blocs (Important)
            _f_AdjustHInB();        
            
            if ( !KgbLib_CheckNullity(a) && a ) { _f_Open(); }
            
            /* Vérifier si Menu, View sont initialisés. Corriger les problèmes d'éléments non synchronisés */
            _f_RepareAlignment();
            
            /*
             * [DEPUIS 15-07-15] @BOR
             */
            $(".jb-nwfd-sprt").data("is_init",true);
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }

    };
            
    var _f_RepareAlignment = function () {
//    this._RepareAlignment = function () {
        /*
         * RULES :
         * NewsFeed doit être cohérent. En effet, il n'y a qu'une div pour afficher tous les menus ...
         * ... selon une VIEW donnée.
         * Il faut donc que lorsque la VIEW sélectionnée est 'List' que le bloc affichant les données ..
         * ... sous format de liste ait le focus.
         *  
         **/
        var r = _f_GetComposSts();
        
        if ( r.view === "list" ) {
            if ( $("#nwfd-b-list" ).hasClass("this_hide") ) {
                $("#nwfd-b-list" ).removeClass("this_hide");
                $("#nwfd-b-moz-max" ).addClass("this_hide");
            }
        } else {
            if (! $("#nwfd-b-moz-max" ).hasClass("this_hide") ) {
                $("#nwfd-b-moz-max" ).removeClass("this_hide");
                $("#nwfd-b-list" ).addClass("this_hide");
            }
        }
    };
    
    /****************** DATAS *****************/
    
    var _f_FirstArt = function ($amx) { //amx : ArgumentMX
//    this.Datas_FirstArticles = function () {
        try {
            
            /*
             * Permet de récupérer et de traiter les données liées à la "communauté" de l'utilisateur actif.
             * Cette méthode est appelée à la première ouverture de NWFD par l'utilisateur depuis sa connexion.
             * Aussi, on suppose que les composantes sont : 
             *  mode : list
             *  menu : community
             * 
             * Cependant, pour des raisons de qualité, on va s'en assurer en prennant en se fiant exactement aux paramètres.
             */
            var pv = _f_GetComposSts(), s = $("<span/>"), isl = false, mb;
            
            switch (pv.menu) {
                /*
                case "team" :
                        if (pv.view === "list") {
    //                        p = "NWFD_GET_LAST_TEAM_LIST";
                            isl = true;
                            mb = "#nwfd-list-team";
                        }
                        else {
    //                        p = "NWFD_GET_LAST_TEAM_MOZ";
                            mb = "#nwfd-moz-team";
                        }
                    break;
                //*/
                case "comy" :
                        if ( KgbLib_CheckNullity($amx) ) { //[DEPUIS 07-04-16]

                            if ( pv.view === "list" ) {
        //                        p = "NWFD_GET_LAST_COMY_LIST";
                                isl = true;
//                                mb = "#nwfd-list-comy";//[DEPUIS 07-04-16]
                                mb = $(".jb-nwfd-b-list[data-scp='comy']");
                            }
                            else {
        //                        p = "NWFD_GET_LAST_COMY_MOZ";
//                                mb = "#nwfd-moz-comy"; //[DEPUIS 07-04-16]
                            }
                        } else {
                            mb = $amx;
                        }
                    break;
                /*
                case "bzfeed" :
                        if (pv.view === "list") {
    //                        p = "NWFD_GET_LAST_BZFD_LIST";
                            isl = true;
                            mb = "#nwfd-list-bzfeed";
                        }
                        else {
    //                        p = "NWFD_GET_LAST_BZFD_MOZ";
                            mb = "#nwfd-moz-bzfeed";
                        }
                    break;
                //*/
                case "iml_pod" :
                        isl = true;
                        mb = ( KgbLib_CheckNullity($amx) ) ? $(".jb-nwfd-b-list[data-scp='iml_pod']") : $amx;
                    break;
                case "itr" :
                        isl = true;
                        mb = ( KgbLib_CheckNullity($amx) ) ? $(".jb-nwfd-b-list[data-scp='itr']") : $amx;
                    break;
                case "tlkb" :
                        isl = true;
                        mb = ( KgbLib_CheckNullity($amx) ) ? $(".jb-nwfd-b-list[data-scp='tlkb']") : $amx;
                    break;
                default :
                    return;
            }       
            
//            Kxlib_DebugVars(["FIRST_ARTICLES",JSON.stringify(pv),mb.length],true);
//            return;
            
            p = Kxlib_GetAjaxRules("NWFD_GET_ARTS");
            
            //w = Which
            w = "std"; //RAPPEL : std, new, old
            _f_LoadDatas(p, pv.menu, pv.view, w, mb, s);
            
            $(s).on("datasready", function(e, d, b) {
                if (KgbLib_CheckNullity(d) | KgbLib_CheckNullity(b) ) { return; }
                
//                Kxlib_DebugVars([JSON.stringify(d)],true);

                /*
                 * [DEPUIS 05-05-16]
                 *      On affiche les données LASTA
                 */
                if ( d.las ) {
//                    Kxlib_DebugVars([JSON.stringify(d.las)],true);
                    _f_Lasta_None();
                    _f_Lasta_Display(d.las,"_SC_NTWK","FST");
                } else {
                    _f_Lasta_None(true);
                }
                
                //On ajoute les données à SHED (Hangar != Buffer Zone)
                var sd = JSON.stringify(d.as);
                $(".jb-nwfd-shed").text(sd);
                
                /*
                 * [DEPUIS 05-07-15] @BOR
                 *      On retire le trigger NEWER
                 */
                _f_KlNwPostTrgPan(b);
                
                //On affiche les données selon le mode de vue
                if (isl) {
                    _f_DisplayListMode(pv.menu,d.as, b);
                } 
                /*
                else {
                    _f_DisplayMozMode(d.as, b);
                }
                */
                //Update de NOoNE
                _f_Noone();
                
                /*
                 * [DEPUIS 05-07-15] @BOR
                 */
                _xhr_ldfst = null;
                
//            Kxlib_DebugVars([th.ShedCount()],true);
                
            });
            /*
             * [DEPUIS 05-07-15] @BOR
             */
            $(s).on("operended",function(e,d){
                _f_HdlImBusy(pv.menu,false);
                _f_HdlSpnr(pv.menu,false,"list");
                _f_HdlNone(pv.menu,true,"list");
                
                /*
                 * [DEPUIS 05-07-15] @BOR
                 */
                _xhr_ldfst = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    /****************** HANDLERS *********************/
       
    var _f_HvrListElt = function(){
//    this.Handler_HoverListElt = function(){
        $(this).find(".jb-nwfd-b-m-mdl-p-fd").toggleClass("hover");
        
        //vmd : ViewMoDe
        if ( KgbLib_CheckNullity($(this).closest(".jb-nwfd-art-mdl").data("vmd")) || $(this).closest(".jb-nwfd-art-mdl").data("vmd") === "shy" ) {
            var x = this;
            setTimeout(function(){
//                alert($(x).html());
                if ( $(x).is(":hover") ) {
                    $(x).closest(".jb-nwfd-b-l-mdl-body").stop(true).animate({
                        height: 500
                    },600);
                    $(x).stop(true).animate({
                        top: 0
                    },600);
                    $(x).closest(".jb-nwfd-art-mdl").data("vmd","wild");
                } 
            },350);
        } else {
            $(this).stop(true).animate({
                top: -100
            },600);
            $(this).closest(".jb-nwfd-b-l-mdl-body").stop(true).animate({
                height: 300
            },600);
            $(this).closest(".jb-nwfd-art-mdl").data("vmd","shy");
        }
        
        
        /*
        $(this).closest(".jb-nwfd-b-l-mdl-body").stop(true,true).toggleClass("ori",1000);
        $(this).stop(true,true).toggleClass("ori",1000);
        //*/
//        $(this)(".jb-nwfd-b-l-mdl-b-box-box").toggleClass("ori",1000);
    };
    
    var _f_ShwMozBar = function(){
//    this.Handler_ShowMozBar = function(){
        _f_ShwArtBar($(this));
    };
    
    var _f_HoldMozBar = function(){
//    this.Handler_HoldMozBar = function(){
        var th = this;
        setTimeout(function(){
            if ( $(th).closest(".nwfd-b-moz-l-list").find(".nwfd-b-m-mdl-trig:hover").length === 0 ) {
                $(th).stop(true,true).fadeOut();
            }
        },100);
    };
    
    /****************** RUNNING MANAGEMENT ******************/
    var _f_Run = function () {
//    this.Run = function () {
        //TODO : Permet de préparer le NewsFeed à la premçière utilisation
            //Récupérer les status des composants
            //Appliquer les statuts des composants
            
            
        //On déclare le module running (1)
        $(".jb-nwfd-sprt").data("rm",1);
        
        //Lancer l'affichage
        _f_Open();
    };
    
    this.Shutdown = function () {
        //TODO : Permets de travailler sur certains process avant et/ou après la cloture
        //(Est utilisé surtout lors de la déconexion)
        
        //TODO : Fermeture du module si ce n'est pas déjà le cas
        
        //On déclare le module running (0)
        $(".jb-nwfd-sprt").data("rm",0);
    };
    
    /****************** ACCESS MANAGEMENT ******************/
    
    var _f_Open = function () {
//    this.Open = function () {
        try {
            
            /*
             * [DEPUIS 06-05-16]
             */
            Kxlib_WindOverflow(true);
            
            /*
             * ETAPE :
             *      On reinitialise les données au niveau de SNITCHER pour la section LASTA
             */
            _f_Snitcher_SetNotif("lasta",0);
            
            /* 
             * Mise à jour de la taille de l'écran.
             * 
             * Cela permet de redimensionner l'écran au cas où par exemple la fenetre d'inspection associée 
             * au navigateur a "déformé" la fenêtre.
             * */
            //On affiche les zones pour permettre les différents calculs
            $(".jb-nwfd-mx").removeClass("this_hide").show();
//            Kxlib_DebugVars([(".jb-nwfd-hdr").length,$(".jb-nwfd-hdr").height(),$(".jb-nwfd-hdr").css("display")]);  
            _f_AdjustHInB(); 
            
            $(".jb-nwfd-mx").addClass("this_hide");
            
            //Afficher le support
            $(".jb-nwfd-sprt").animate({
                "margin-top": 0
            }, 900, function() {
                //Apres que le support ait terminé l'affichage on ouvre le module à propement parlé
                $(".jb-nwfd-mx").hide().removeClass("this_hide").fadeIn().removeAttr("style");
                
                //On indique que le module est visible
                $(".jb-nwfd-sprt").data("access", 1);
                
                //Mise à jour de la configuration de NewsFeed
//            _f_UpdtComposSts();
                
                //On vérifie s'il s'agit de la première visite 
                /*
                 * Pour ce faire, on vérifie si la zone dite "hangar" est vide.
                 * Cette zone stocke les Articles arrivés sous le "mandat" FirstArticles.
                 * 
                 * RAPPEL : Une autre zone se nomme "BufferZone".
                 * Cette zone contient les Articles en attente d'affichage.
                 */
                if (! _f_ShedCn() ) {
                    //On récupère les données
                    _f_FirstArt();
                } else {
                    //On vérifie s'il y a des Articles ulterieurs
                    _f_ChkNwrArt();
                }
                
            });
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }

    };
    
    var _f_Close = function () {
//    this.Close = function () {
        
        try {
            //Fermer le module
            var h = $(window).height();
            
            h *= -1;
            h = h.toString() + "px";
            
            $(".jb-nwfd-sprt").animate({
                "margin-top": h
            }, 750, function() {
                //On masque le module
                $(".jb-nwfd-mx").addClass("this_hide").removeAttr("style");
                
                //On indique que le module est caché
                $(".jb-nwfd-sprt").data("access", 0);
                
                //Mise à jour de la configuration de NewsFeed
                _f_UpdtComposSts();
                
                /*
                 * [DEPUIS 06-05-16]
                 */
                Kxlib_WindOverflow();
                
            });
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }

        
    };
    
    /****************** SHED ZONES SCOPE (START) ******************/
    
    var _f_ShedCn = function () {
//    this.ShedCount = function () {
        /*
         * Permet de connaitre le nombre d'Article dans la ZONE HANGAR. S'il n'y a aucun Article, la méthode renvoie 0.
         * Si la zone n'existe pas, elle sera créée. Cela n'est possible qu'à la première ouverture de NEWSFEED.
         * Silent, man !
         */ 
        if (! $(".jb-nwfd-shed").length ) {
            //On crée la zone
            var shed = $("<div/>").attr({
                "id": "nwfd-shed",
                "class": "jb-nwfd-shed this_hide",
                //lp = LastPull (Dernière fois que l'on a procédé à la mise à jour des données de la zone
                "data-lp": ""
            });
            
            //On ajouter dans le DOM
            if (! $(".jb-nwfd-buffer").length ) {
                $(shed).insertAfter(".jb-nwfd-footer"); 
            } else {
                $(shed).insertAfter(".jb-nwfd-buffer");
            }
            
//            Kxlib_DebugVars([$(".jb-nwfd-footer").length, $(".jb-nwfd-shed").length], true);
            
            return 0;
        } else {
            var d =  _f_GetShedDatas(), cn = Kxlib_ObjectChild_Count(d);
            
            cn = ( typeof cn === "undefined" ) ? 0 : cn;
            
            return cn;
        }
        
    };
    
    var _f_GetShedDatas = function () {
//    this._GetShedDatas = function () {
                
        //sd = ShedDatas
        var sd = $(".jb-nwfd-shed").text();
        
        if (! KgbLib_CheckNullity(sd) ) {
                   
            try {
                var d = JSON.parse(sd);
//                Kxlib_DebugVars([sd, typeof d, typeof d ],true);
                return d;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
                //Si la chaine de caractères ne correspond pas à une frme JSON valide
                return false;
            }

        }
        
    };
    
    /****************** SHED ZONES SCOPE (END) ******************/
    
    /****************** BUFFER ZONES SCOPE (START) ******************/
    
    var _f_BufferInit = function () {
        try {
            if (! $(".jb-nwfd-buffer").length ) {
                //On crée la zone
                var buf = $("<div/>").attr({
                    "id"    : "nwfd-buffer",
                    "class" : "jb-nwfd-buffer this_hide",
                });
                
                /*
                 * [DEPUIS 0-05-16]
                 */
                var $comy = $("<div/>",{
                    "class" : "jb-nwfd-bufr-sub",
                    "data-mn" : "comy",
                    //lp = LastPull (Dernière fois que l'on a procédé à la mise à jour des données de la zone
                    "data-lp": ""
                }).data("mn","comy");
                var $iml_pod = $("<div/>",{
                    "class" : "jb-nwfd-bufr-sub",
                    "data-mn" : "iml_pod",
                    //lp = LastPull (Dernière fois que l'on a procédé à la mise à jour des données de la zone
                    "data-lp": ""
                }).data("mn","iml_pod");
                var $itr = $("<div/>",{
                    "class" : "jb-nwfd-bufr-sub",
                    "data-mn" : "itr",
                    //lp = LastPull (Dernière fois que l'on a procédé à la mise à jour des données de la zone
                    "data-lp": ""
                }).data("mn","itr");
                //On ajoute à la zone
                $(buf).append($comy,$iml_pod,$itr);
                
                //On ajoute dans le DOM
                if (! $(".jb-nwfd-shed").length ){
                    $(buf).insertAfter(".jb-nwfd-footer");
                } else {
                    $(buf).insertBefore(".jb-nwfd-shed");
                }
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    this._BufferCount = function (mn) {
        /*
         * [NOTE]
         *      Permet de connaitre le nombre d'Article dans la ZONE BUFFER. S'il n'y a aucun Article, la méthode renvoie 0.
         *      Si la zone n'existe pas, elle sera créée. Cela n'est possible qu'à la première ouverture de NEWSFEED.
         *      Silent, man !
         *  [NOTE 09-06-16]
         *      Refactorisation de la fonction. Cependant, je ne suis pas sur qu'elle est toujours utilisée.
         */ 
        if (! $(".jb-nwfd-buffer").length ) {
            _f_BufferInit();
            
//            Kxlib_DebugVars([$(".jb-nwfd-footer").length, $(".jb-nwfd-buffer").length], true);
            return 0;
        } else {
            var d = _f_GetBfrDs(mn), cn = Kxlib_ObjectChild_Count(d);
            
            cn = ( typeof cn === "undefined" ) ? 0 : cn;
            
            return cn;
        }
    };
    
    
    
    var _f_GetBfrDs = function (mn) {
//    this._GetBufferDatas = function () {
        try {
            if ( KgbLib_CheckNullity(mn) ) {
                return;
            }
            
            //bd = BufferDatas
            var bd;
            switch (mn) {
                case "comy" :
                        bd = $(".jb-nwfd-buffer").find(".jb-nwfd-bufr-sub[data-mn='comy']").text();
                    break;
                case "iml_pod" :
                        bd = $(".jb-nwfd-buffer").find(".jb-nwfd-bufr-sub[data-mn='iml_pod']").text();
                    break;
                case "itr" :
                        bd = $(".jb-nwfd-buffer").find(".jb-nwfd-bufr-sub[data-mn='itr']").text();
                    break;
                default:
                    return;
            }
            
            if ( KgbLib_CheckNullity(bd) ) {
                return;
            }

            var d = JSON.parse(bd);
//            Kxlib_DebugVars([bd, typeof d, typeof d ],true);
            
            return d;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    var _f_SetBfrDs = function (jo,mn) {
//    this._SetBufferDatas = function (jo) {
        try {
            if ( KgbLib_CheckNullity(jo) || typeof jo !== "object" || KgbLib_CheckNullity(mn) ) {
                return;
            }
//            Kxlib_DebugVars([Kxlib_ObjectChild_Count(jo)],true);

            if (! $(".jb-nwfd-buffer").length ) {
                _f_BufferInit();
            }
            
            var st = JSON.stringify(jo);
//            Kxlib_DebugVars([st],true);
            switch (mn) {
                case "comy" :
                        $(".jb-nwfd-buffer").find(".jb-nwfd-bufr-sub[data-mn='comy']").text(st);
                    break;
                case "iml_pod" :
                        $(".jb-nwfd-buffer").find(".jb-nwfd-bufr-sub[data-mn='iml_pod']").text(st);
                    break;
                case "itr" :
                        $(".jb-nwfd-buffer").find(".jb-nwfd-bufr-sub[data-mn='itr']").text(st);
                    break;
                default:
                    return;
            }
            
            return st;
        } catch (ex) {
//          alert(e);
            /*
             * [NOTE 27-09-14] @author L.C.
             * Je ne pense pas que stringify puisse déclencher une excepton
             */
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
            return false;
        }

    };
    
    var _f_NwfdSlidedAct = function (x) {
//    this.NwfdSlidedAction = function (x) {
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) { 
            return; 
        }
        try {
            
            var ac = $(x).data("action");
            switch (ac) {
                case "develop" : 
                        _f_ShwNwfdSidePan(x);    
                    break;
                case "simple" : 
                        _f_HdNwfdSidePan(x);    
                    break;
                case "nav-up" : 
                        _f_NwfdSlidedNavUp();
                    break;
                case "nav-down" : 
                    _f_NwfdSlidedNavDown();
                        break;
                case "nav-first" : 
                        _f_NwfdSlidedNav1st();
                    break;
                case "reveal" : 
                    if ( $(".jb-nwfd-nptp-m").hasClass("zmout") ) {
//                        alert("Reaveal Articles On First Option");
                        _f_DspNwr(x);
                    } else {
//                        alert("Reaveal Articles On Semi-lop Option");
                        //On met en place un lock qui permettra d'annuler les animations car la fenetre de droite sera ouverte
                        _f_DspNwr(x);
                    }
                    //If la partie de gauche est ouverte => Devlop Option
                    break;
                case "reveal-od" : 
//                    alert("Reveal On Develop right sector");
                        _f_DspNwr(x);
                    break;
                    
                default:
                    return;
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }

    };
    
    var _f_HdlSpnr = function (mn,sh,scp) {
        try {
            if ( KgbLib_CheckNullity(mn) | KgbLib_CheckNullity(scp) ) {
                return;
            }
            
            var $spnr;
            /*
            switch (scp) {
                case "list" :
                        $spnr = $(".jb-nwfd-b-list").find(".jb-nwfd-spnr-mx");
                    break;
                default:
                    return;
            }
            //*/
            switch (mn) {
                case "comy" :
                        $spnr = $(".jb-nwfd-b-list[data-scp='comy']").find(".jb-nwfd-spnr-mx");
                    break;
                case "iml_pod" :
                        $spnr = $(".jb-nwfd-b-list[data-scp='iml_pod']").find(".jb-nwfd-spnr-mx");
                    break;
                case "itr" :
                        $spnr = $(".jb-nwfd-b-list[data-scp='itr']").find(".jb-nwfd-spnr-mx");
                    break;
                case "tlkb" :
                        $spnr = $(".jb-nwfd-b-list[data-scp='tlkb']").find(".jb-nwfd-spnr-mx");
                    break;
                default:
                    return;
            }
            
            if (! $spnr.length ) {
                return;
            }
            
            if ( sh === true ) {
                $(".jb-nwfd-b-list").find(".jb-nwfd-spnr-mx").addClass("this_hide");
                $spnr.removeClass("this_hide");
            } else {
//                $spnr.addClass("this_hide");
                $(".jb-nwfd-b-list").find(".jb-nwfd-spnr-mx").addClass("this_hide");
            } 
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    var _f_HdlNone = function (mn,sh,scp) {
        try {
            if ( KgbLib_CheckNullity(mn) | KgbLib_CheckNullity(scp) ) {
                return;
            }
            
            var $mdl, $nne;
            /*
            switch (scp) {
                case "list" :
                        $nne = $(".jb-nwfd-b-list").find(".jb-nwfd-none-mx");
                        $mdl = $(".jb-nwfd-b-list").find(".jb-nwfd-none-mx");
                    break;
                default:
                    return;
            }
            //*/
            switch (mn) {
                case "comy" :
                        $mdl = $(".jb-nwfd-b-list[data-scp='comy']");
                    break;
                case "iml_pod" :
                        $mdl = $(".jb-nwfd-b-list[data-scp='iml_pod']");
                    break;
                case "itr" :
                        $mdl = $(".jb-nwfd-b-list[data-scp='itr']");
                    break;
                case "tlkb" :
                        $mdl = $(".jb-nwfd-b-list[data-scp='tlkb']");
                    break;
                default:
                    return;
            }
            $nne = $mdl.find(".jb-nwfd-none-mx");
            
//            if (! $nne.length ) {
            if ( !$nne.length | !$mdl.length ) {
                return;
            }
            
            if ( sh === true ) {
//                $(".jb-nwfd-b-list").find(".jb-nwfd-none-mx").addClass("this_hide");
                $nne.removeClass("this_hide");
            } else if ( sh === false ) {
                $nne.addClass("this_hide");
//                $(".jb-nwfd-b-list").find(".jb-nwfd-none-mx").addClass("this_hide");
            } 
            /*
            else {
                if ($mdl.length) {
//                    $nne.addClass("this_hide");
                    $(".jb-nwfd-b-list").find(".jb-nwfd-none-mx").addClass("this_hide");
                } else {
                    $(".jb-nwfd-b-list").find(".jb-nwfd-none-mx").addClass("this_hide");
                    $nne.removeClass("this_hide");
                }
            }
            //*/
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    var _f_HdlLdr = function (mn,sh,scp) {
        try {
            if ( KgbLib_CheckNullity(mn) | KgbLib_CheckNullity(scp) ) {
                return;
            }
            
            var $mdl, $ldr;
            /*
            switch (scp) {
                case "list" :
                        $ldr = $(".jb-nwfd-b-list").find(".jb-nwfd-loadm-box");
                    break;
                default:
                    return;
            }
            //*/
            switch (mn) {
                case "comy" :
                        $mdl = $(".jb-nwfd-b-list[data-scp='comy']");
                    break;
                case "iml_pod" :
                        $mdl = $(".jb-nwfd-b-list[data-scp='iml_pod']");
                    break;
                case "itr" :
                        $mdl = $(".jb-nwfd-b-list[data-scp='itr']");
                    break;
                case "tlkb" :
                        $mdl = $(".jb-nwfd-b-list[data-scp='tlkb']");
                    break;
                default:
                    return;
            }
            $ldr = $mdl.find(".jb-nwfd-loadm-box");
            if (! $ldr.length ) {
                return;
            }
            
            if ( sh === true ) {
                $(".jb-nwfd-b-list").find(".jb-nwfd-loadm-box").addClass("this_hide");
                $ldr.removeClass("this_hide");
            } else {
//                $ldr.addClass("this_hide");
                $(".jb-nwfd-b-list").find(".jb-nwfd-loadm-box").addClass("this_hide");
            } 
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    var _f_HdlImBusy = function (mn,sh) {
        try {
            if ( KgbLib_CheckNullity(mn) ) {
                return;
            }
            
            var $imbusy;
            switch (mn) {
                case "comy" :
                        $imbusy = $(".jb-nwfd-b-list[data-scp='comy']").find(".jb-nwfd-imbusy-mx");
                    break;
                case "iml_pod" :
                        $imbusy = $(".jb-nwfd-b-list[data-scp='iml_pod']").find(".jb-nwfd-imbusy-mx");
                    break;
                case "itr" :
                        $imbusy = $(".jb-nwfd-b-list[data-scp='itr']").find(".jb-nwfd-imbusy-mx");
                    break;
                case "tlkb" :
                        $imbusy = $(".jb-nwfd-b-list[data-scp='tlkb']").find(".jb-nwfd-imbusy-mx");
                    break;
                default:
                    return;
            }
            
            if (! $imbusy.length ) {
                return;
            }
            
            if ( sh === true ) {
                $imbusy.removeClass("this_hide");
            } else {
                $imbusy.addClass("this_hide");
            } 
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    /******************* BUFFER ZONE SCOPE (END) *******************/
       
    
    /****************************************************************************************************************************************************/
    /******************************************************************** AJAX SCOPE ********************************************************************/
    /****************************************************************************************************************************************************/
    
    
    /******************* DATAS MANAGEMENT ********************/
    
    var _f_LoadDatas = function (p,mn,vw,w,mb,s) {
//    this.LoadDatas = function (p,mn,vw,w,mb,s) {
        //p = Données sur AJAX, mn = Menu; vw = View; mb = MenuBloc; s = Snitcher; ai = ArticleId; at = ArticleTime
        if ( KgbLib_CheckNullity(p) | KgbLib_CheckNullity(mn) | KgbLib_CheckNullity(vw) | KgbLib_CheckNullity(w) | KgbLib_CheckNullity(mb) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        /*
         * [DEPUIS 05-07-15] @BOR
         */
        if (! KgbLib_CheckNullity(_xhr_ldfst) ) {
//            Kxlib_DebugVars([Locked at _f_LoadDatas()"]);
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
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                            case "__ERR_VOL_DNY_AKX":
                                    Kxlib_AJAX_HandleDeny();
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
//                } else if (! KgbLib_CheckNullity(datas.return) ) {
//                    if ( pd )
//                        _f_DsplPrdtDsListMd(datas.return,mb);
//                    else
//                        _f_DisplayListMode(datas.return,mb);
//                }
                } else if ( 
                    !KgbLib_CheckNullity(datas.return) 
                    && datas.return.hasOwnProperty("as") 
                    && !KgbLib_CheckNullity(datas.return.as) 
                ) {
                    /*
                     * Données attendues :
                     *  (1) Les données sur les articles à proprement parlé
                     *  (2) Les données sur LASTA_NETWORK.
                     *  (3) Autres ?
                     */
                    var rds = [datas.return,mb];
                    $(s).trigger("datasready",rds);
                } else if ( 
                    !KgbLib_CheckNullity(datas.return) 
                    && datas.return.hasOwnProperty("las") 
                    && !KgbLib_CheckNullity(datas.return.las) 
                ) {
                    /*
                     * Données attendues :
                     *  (1) Les données sur LASTA_NETWORK.
                     */
                    var rds = [datas.return,mb];
                    $(s).trigger("operended",rds);
                } else {
                    var rds = [null,mb];
                    $(s).trigger("operended",rds);
                    return;
                }
                    
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function (a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            Kxlib_DebugVars([JSON.stringify(a),typeof a,b,c],true);
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            Kxlib_AjaxGblOnErr(a,b);
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        var curl = document.URL;
        var toSend = {
            "urqid": p.urqid,
            "datas": {
                "mn"    : mn, //comy, team, bzfeed
                "vw"    : vw, //list, moz
                "w"     : w, //std, new, old
                "curl"  : curl
            }
        };
        
        _xhr_ldfst = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : p.url, wcrdtl : p.wcrdtl });;
//        _xhr_ldfst = Kx_XHR_Send(toSend, "post", p.url, onerror, onsuccess);
    };
    
    //Permet de récupérer les données en se basant sur un article pivot fourni en paramètre. On passe un objet avec id, time
    var _f_LoadDatas_From = function (p,mn,vw,w,mb,s,ads,lads) {
//    this.LoadDatas_From = function (p,mn,vw,w,mb,s,a) {
        //p = Données sur AJAX, mn = Menu; vw = View; mb = MenuBloc; s = Snitcher; ads = { aba : :  ai = ArticleId; at = ArticleTime, ... }
        if ( KgbLib_CheckNullity(p) | KgbLib_CheckNullity(mn) | KgbLib_CheckNullity(vw) | KgbLib_CheckNullity(w) | KgbLib_CheckNullity(mb) | KgbLib_CheckNullity(s) | !( !KgbLib_CheckNullity(ads) && Kxlib_ObjectChild_Count(ads) ) ) {
            return;
        }
        
        /*
         * [DEPUIS 05-07-15] @BOR
         */
        if ( w === "new" && !KgbLib_CheckNullity(_xhr_ldfrm_top) ) {
//            Kxlib_DebugVars([Locked at _f_LoadDatas_From() -> TOP"]);
            return;
        }
        else if ( w === "old" && !KgbLib_CheckNullity(_xhr_ldfrm_btm) ) {
//            Kxlib_DebugVars([Locked at _f_LoadDatas_From() -> BOTTOM "]);
            //On unlock le bouton
            $(mb).data("ulk",0);
            // On change l'état du bouton
            _f_VwLdrStt("_STATE_SLPG",vw);
            return;
        }
                
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else{
                    //On libère la zone
                    $(mb).data("ulk",0);
                    return;
                }
                
//                Kxlib_DebugVars([!KgbLib_CheckNullity(datas.return),( datas.return.hasOwnProperty("as") && datas.return.as.length ),( datas.return.hasOwnProperty("las") && datas.return.las.length ),( datas.return.hasOwnProperty("leeches") && datas.return.leeches.length ),datas.return.hasOwnProperty("leeches"), Kxlib_ObjectChild_Count(datas.return.leeches)],true);
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    
                    //On libère la zone
                    $(mb).data("ulk",0); 
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                                    Kxlib_AJAX_HandleDeny();
                                break;
                            default:
                                   /*
                                    * (03-12-14)
                                    * Retirer car la méthode est appelée automatiquement. Ca dégrade l'expérience utilisateur
                                    */
//                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
//                } else if (! KgbLib_CheckNullity(datas.return) ) {
//                    if ( pd )
//                        _f_DsplPrdtDsListMd(datas.return,mb);
//                    else
//                        th.DisplayDatasListMode(datas.return,mb);
//                }
                } else if ( !KgbLib_CheckNullity(datas.return) && ( 
                    ( datas.return.hasOwnProperty("as") && datas.return.as.length ) 
                    | ( datas.return.hasOwnProperty("las") && datas.return.las.length )  
                    | ( datas.return.hasOwnProperty("leeches") && datas.return.leeches && Kxlib_ObjectChild_Count(datas.return.leeches) ) 
                ) ) {
                    /*
                     * Données attendues :
                     *  (1) Les données sur les articles à proprement parlé
                     *  (2) La liste des relations liées aux Articles reçues
                     *      N.B : Il se peut que TOUS les Articles soient liées à une Tendance. 
                     *            Dans ce cas, la liste des relations ne sera pas disponible.
                     */
                    var rds = [datas.return,mb];
                    $(s).trigger("datasready",rds);
                } else if ( KgbLib_CheckNullity(datas.return) || !KgbLib_CheckNullity(datas.return) && ( 
                    !( datas.return.hasOwnProperty("as") && datas.return.as.length ) 
                    && !( datas.return.hasOwnProperty("las") && datas.return.las.length) 
                    && !( datas.return.hasOwnProperty("leeches") && datas.return.leeches.length) ) 
                ) {
                    var rds = [null,mb];
                    $(s).trigger("operended",rds);
                } else {
                    //On libère la zone
                    $(mb).data("ulk",0);
                    return;
                }
                    
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                /*
                 * (03-12-14)
                * Retirer car la méthode est appelée automatiquement. Ca dégrade l'expérience utilisateur
                */
               Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                //On libère la zone
                $(mb).data("ulk",0);
                return;
            }
        };

        var onerror = function (a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
           /*
            * (03-12-14)
            * Retirer car la méthode est appelée automatiquement. Ca dégrade l'expérience utilisateur
            */
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            //On libère la zone
            $(mb).data("ulk",0);
            return;
        };
        
        var curl = document.URL;
        var toSend = {
            "urqid": p.urqid,
            "datas": {
                "mn"    : mn, //comy, team, bzfeed
                "vw"    : vw, //list, moz
                "w"     : w, //std, new, old
                "ads"   : ads,
//                "ai": a.i,
//                "at": a.t,,
                "lads"  : lads,
                "curl"  : curl
            }
        };
        
        /*
         * [DEPUIS 05-07-15] @BOR
         */
        if ( w === "new" ) {
            _xhr_ldfrm_top = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : p.url, wcrdtl : p.wcrdtl });
//            _xhr_ldfrm_top = Kx_XHR_Send(toSend, "post", p.url, onerror, onsuccess);
        } else if ( w === "old" ) {
            _xhr_ldfrm_btm = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : p.url, wcrdtl : p.wcrdtl });
//            _xhr_ldfrm_btm = Kx_XHR_Send(toSend, "post", p.url, onerror, onsuccess);
        }
    };
    
    //OBSELETE
    var _f_LdDsListMd = function (p,mb,pd,s) {
//    this.LoadDatasListMode = function (p,mb,pd,s) {
        //p = paramtères (rm, access, menu, view); isl = IsList; pd = Predate => Est ce qu'on veut afficher les anciens résultats
        if ( KgbLib_CheckNullity(p) | KgbLib_CheckNullity(mb) | KgbLib_CheckNullity(pd) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var th = this;
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else return;
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE":
                                    return;
                                break;
                            case "__ERR_VOL_DENY":
                                    return;
                                break;
                            default:
                                    return;
                                break;
                        }
                    } 
                    return;
//                } else if (! KgbLib_CheckNullity(datas.return) ) {
//                    if ( pd )
//                        _f_DsplPrdtDsListMd(datas.return,mb);
//                    else
//                        th.DisplayDatasListMode(datas.return,mb);
//                }
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    /*
                     * Données attendues :
                     *  (1) Les données sur les articles à proprement parlé
                     *  (2) La liste des relations liées aux Articles reçues
                     *      N.B : Il se peut que TOUS les Articles soient liées à une Tendance. 
                     *            Dans ce cas, la liste des relations ne sera pas disponible.
                     */
                    var rds = [d.return,mb];
                    $(s).trigger("datasready",rds);
                } else {
                    return;
                }
                    
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            return;
        };
        
        var toSend = {
            "urqid": p.urqid,
            "datas": {
                
            }
        };

        Kx_XHR_Send(toSend, "post", p.url, onerror, onsuccess);
    };
    
    var  _f_ChkEltNoExtsInList = function (b,d) {
//    this._CheckEltNoExsitsInList = function (b,d) {
        //d=Data les données de l'élément qui sera ajouté; b = Le bloc dans lequel il sera ajouté
        
        if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(b) ) { 
            return; 
        }
        
        //it = item; Correspond à l'id de l'article
        var it = d.id;
        var $e = $(b).find(".jb-nwfd-art-mdl[data-item="+it+"]");
        
//        alert("Bloc => "+$(b).attr("id")+"; Item => "+it+"; Exists => "+$e.length); //DEBUG
        var r = ($e.length) ? true : false;
        return r;
    };
    
    //OBSELTE ?
    var _f_LdDsMozMd = function (p,mb,pd) {
//    this.LoadDatasMozMode = function (p,mb,pd) {
        //p = paramtères (rm, access, menu, view); mb = MenuBloc ; pd = Predate => Est ce qu'on veut afficher les anciens résultats
        if ( KgbLib_CheckNullity(p) ) {
            return;
        }
        
        var th = this;
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                }
                    
                
                if (! KgbLib_CheckNullity(datas.err) ) {
                    //NOTHING
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    
//                    alert(Kxlib_ObjectChild_Count(datas.return));
                    _f_DisplayMozMode(datas.return,mb,null,pd);
                }
                    
            } catch (e) {
                //TODO : ?
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //NOTHING
        };
        
        var toSend = {
            "urqid": p.urqid,
            "datas": {
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : p.url, wcrdtl : p.wcrdtl });
//        Kx_XHR_Send(toSend, "post", p.url, onerror, onsuccess);
    };
    
    var _f_ChkEltNoExsitsInMoz = function (b,a) {
//    this._CheckEltNoExsitsInMoz = function (b,d) {
        //d=Data les données de l'élément qui sera ajouté; b = Le bloc dans lequel il sera ajouté
        
        if ( KgbLib_CheckNullity(a) || KgbLib_CheckNullity(b) ) { return; }
        
        //it = item; Correspond à l'id de l'article
        var it = a.id;
        var $e = $(b).find(".nwfd-b-moz-mdl-max[data-item="+it+"]");
        
//        alert("Bloc => "+$(b).attr("id")+"; Item => "+it+"; Exists => "+$e.length); //DEBUG
        var r = ($e.length) ? true : false;
        
        return r;
    };
    
    
    
    
    /************************* DATA SYNCHORNIZATION ***********************/
    
    var _f_IsZoneTimeRdy = function (mb,k,rt) {
//    this._IsZoneTimeReady = function (mb,k,rt) {
        //k : La dénomination de la clé qui sera utilisée pour la variable 'temps', rt : Temps de référence
        if ( KgbLib_CheckNullity(mb) | KgbLib_CheckNullity(k) | KgbLib_CheckNullity(rt) ) {
//            Kxlib_DebugVars([KgbLib_CheckNullity(mb),KgbLib_CheckNullity(k),KgbLib_CheckNullity(rt)],true);
            return;
        }
        
        var tm = $(mb).data(k.toString());
        tm = ( KgbLib_CheckNullity(tm) ) ? 0 : parseInt(tm);
        var n = (new Date()).getTime();

        //eld  = elapsed (passé)
        var eld = n - tm;
//        alert(eld);
        return ( tm === 0 || eld >= rt ) ? true : false;
    };
    
    var _f_ChkNwrArt = function () {
//    this.HandleNewerArticles = function () {
        /*
         * [NOTE 05-10-14] @author L.C.
         * Permet de vérifier si des Articles ont été nouvellement ajoutés.
         * Pour cela, on utilise la Sentinel a qui ont transmet le bon bloc en question
         */
        try {
            
            /*
             * [DEPUIS 15-07-15] @BOR
             * On ne lance pas la procédure si NWFD n'est pas au préalable ouvert
             */
            /*
            if ( KgbLib_CheckNullity($(".jb-nwfd-sprt").data("access")) | $(".jb-nwfd-sprt").data("access") === 0 ) {
                return;
            }
            //*/
                    
            var prm = _f_GetComposSts(), $mb;
            var isl = ( prm.view === "list" ) ? true : false;
            switch ( prm.menu ) {
                case "comy" :
//                        mb = ( isl ) ? "#nwfd-list-comy" : "#nwfd-moz-comy";
                        $mb = $(".jb-nwfd-b-list[data-scp='comy']");
                    break;
                case "iml_pod" :
                        $mb = $(".jb-nwfd-b-list[data-scp='iml_pod']");
                    break;
                case "itr" :
                        $mb = $(".jb-nwfd-b-list[data-scp='itr']");
                    break;
//                case "tlkb" :
//                        $mb = $(".jb-nwfd-b-list[data-scp='tlkb']");
//                    break;
                default:
                    return;
            }
            
//            Kxlib_DebugVars(["NWFD_CHECK_NEW_ART",JSON.stringify(prm),$mb.length],true);
//            return;
                    
            _f_Sentinel($mb);
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    var _f_DspNwr = function (x) {
//    this.HandleDisplayNewer = function () {
            //On ferme la zone si elle ouverte
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("scp")) ) {
                return;
            }
            
            /* //[DEPUIS 09-05-16]
            if ( $(".jb-nwfd-slided-max").data("sts") === 1 ) {
                $(".jb-nwfd-nptp-dvlp-trg").click();
            }

            //On fait disparaitre le trigger
            _f_NewPostTrigPanZmOut();
//        $(".jb-nwfd-nptp-m").fadeOut(500);
            //*/
            
            /*
             * [DEPUIS 09-05-16]
             */
            var mn = $(x).data("scp");
            if ( $.inArray(mn,["comy","iml_pod","itr"]) === -1 ) {
                return;
            }
            
            /*
             * [DEPUIS 09-05-16]
             */
            var amn = _f_GetActvMnB2();
            if ( !amn && $(amn).data("scp") !== mn ) {
                $(x).addClass("this_hide");
                return;
            }
            
            //On récupère les données dans la zone Buffer
            var d = _f_GetBfrDs(mn);
            if ( !KgbLib_CheckNullity(d) && Kxlib_ObjectChild_Count(d) ) {

                var isl = ( _f_GetComposSts().view === "list" ) ? true : false;
                var b = _f_GetActvMnB2();

                //On affiche les données
                if ( isl ) {
                    _f_DisplayListMode(mn,d,b,true);
                } 
                /*
                else {
                    _f_DisplayMozMode(d, b);
                }
                //*/
                
                //Update de NOoNE
                _f_Noone();

                _f_KlNwPostTrgPan(b);
                
                
                /*
                 * [ETAPE 10-05-16]
                 *      On affiche l'information au niveau de SNITCHER_NEWFEED.
                 *      Dans ce cas, on réinitilise la valeur.
                 */
                _f_Snitcher_SetNotif(mn);
                
            }

        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };


    var _f_Sentinel = function (mb) {
    //    this.Sentinel = function (mb) {
        try {
            //Rappel : mb => MenuBox
            /* *
             * La méthode est appelée. Aussi, si on veut mettre en place un script qui permet de vérifier ...
             * ... toutes les x secondes, il faut le faire depuis l'exterieur. Il faut donc utiliser un CALLER.
             * 
             * La Sentinel se base sur les données actives en cours. Il n'est point besoin que de lui envoyer ...
             * ... la configuration. Cela est un avantage car 'elle' peut être utilisé avec le mmoins de contrainte possible.
             * Seule le statut de navigation en cours compte.
             * * * * * */

            if ( KgbLib_CheckNullity(mb) || !$(mb).length ) { 
                return; 
            }

            /* On vérifie si le chargement de nouveaux Articles est autorisé */
            var r = _f_ChkNewLdgAlwd(mb);
    //        alert(r); //DEBUG
            if ( KgbLib_CheckNullity(r) | !r ) { return; }

            //isl = isList
            var d = _f_GetComposSts(), p, isl = false, s = $("<span/>"), ad;
            switch (d.menu) {
                case "comy" :
                        if ( d.view === "list") {
    //                        p = "NWFD_GET_LAST_COMY_LIST";
                            isl = true;
                        } else {
    //                        p = "NWFD_GET_LAST_COMY_MOZ";
                        }
                    break;
                    /*
                case "team" :
                        if ( d.view === "list") {
    //                        p = "NWFD_GET_LAST_TEAM_LIST";
                            isl = true;
                        } else {
    //                        p = "NWFD_GET_LAST_TEAM_MOZ";
                        }
                    break;
                case "bzfeed" :
                        if ( d.view === "list") {
    //                        p = "NWFD_GET_LAST_BZFD_LIST";
                            isl = true;
                        } else {
    //                        p = "NWFD_GET_LAST_BZFD_MOZ";
                        }
                    break;
                    //*/
                case "iml_pod" :
                        
                    break;
                case "itr" :
                        
                    break;
                case "tlkb" :
                        
                    break;
                default: 
                    return;
            }        

            var ads = {}, mb__;
            //On récupère des données au sur l'Article s'il existe
            if ( 
                $(".jb-nwfd-b-list[data-scp='comy']").find(".jb-nwfd-art-mdl").length 
                | $(".jb-nwfd-b-list[data-scp='iml_pod']").find(".jb-nwfd-art-mdl").length 
                | $(".jb-nwfd-b-list[data-scp='itr']").find(".jb-nwfd-art-mdl").length 
            ) {
                //RAPPEL : "_xl_12it","_xl_2im","_xl_3it","_xl_3im","_xl_mt","_xl_st"
                if ( $(".jb-nwfd-b-list[data-scp='itr']").find(".jb-nwfd-art-mdl[data-aba='_xl_12it']").length ) {
                    mb__ = $(".jb-nwfd-b-list[data-scp='itr']");
                    ads["_xl_12it"] = {
                        i : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_12it']:first").data("item"),
                        t : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_12it']:first").find(".kxlib_tgspy").data("tgs-crd")
                    };
                }
                /*
                if ( $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_2im']").length ) {
                    ads["_xl_2im"] = {
                        i : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_2im']:first").data("item"),
                        t : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_2im']:first").find(".kxlib_tgspy").data("tgs-crd")
                    };
                }
                //*/
                if ( $(".jb-nwfd-b-list[data-scp='itr']").find(".jb-nwfd-art-mdl[data-aba='_xl_3it']").length ) {
                    mb__ = $(".jb-nwfd-b-list[data-scp='itr']");
                    ads["_xl_3it"] = {
                        i : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_3it']:first").data("item"),
                        t : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_3it']:first").find(".kxlib_tgspy").data("tgs-crd")
                    };
                }
                if ( $(".jb-nwfd-b-list[data-scp='comy']").find(".jb-nwfd-art-mdl[data-aba='_xl_3im']").length ) {
                    mb__ = $(".jb-nwfd-b-list[data-scp='comy']");
                    ads["_xl_3im"] = {
    //                    i : 1235, //TEST, DEBUG
                        i : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_3im']:first").data("item"),
    //                    t : 1420080400000 //TEST, DEBUG
                        t : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_3im']:first").find(".kxlib_tgspy").data("tgs-crd")
                    };
                }
                if ( $(".jb-nwfd-b-list[data-scp='iml_pod']").find(".jb-nwfd-art-mdl[data-aba='_xl_3im_pod']").length ) {
                    mb__ = $(".jb-nwfd-b-list[data-scp='iml_pod']");
                    ads["_xl_3im_pod"] = {
    //                    i : 1235, //TEST, DEBUG
                        i : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_3im_pod']:first").data("item"),
    //                    t : 1420080400000 //TEST, DEBUG
                        t : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_3im_pod']:first").find(".kxlib_tgspy").data("tgs-crd")
                    };
                }
                if ( $(".jb-nwfd-b-list[data-scp='itr']").find(".jb-nwfd-art-mdl[data-aba='_xl_mt']").length ) {
                    mb__ = $(".jb-nwfd-b-list[data-scp='itr']");
                    ads["_xl_mt"] = {
                        i : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_mt']:first").data("item"),
                        t : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_mt']:first").find(".kxlib_tgspy").data("tgs-crd")
                    };
                }
                if ( $(".jb-nwfd-b-list[data-scp='itr']").find(".jb-nwfd-art-mdl[data-aba='_xl_st']").length ) {
                    /*
                     * [DEPUIS 06-05-15] @BOR
                     * Je ne comprends pas pourquoi j'ai ":last"
                     */
                    mb__ = $(".jb-nwfd-b-list[data-scp='itr']");
                    ads["_xl_st"] = {
                        i : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_st']:first").data("item"),
                        t : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_st']:first").find(".kxlib_tgspy").data("tgs-crd")
                    };
                    /*
                    ads["_xl_st"] = {
                        i : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_st']:last").data("item"),
                        t : $(mb__).find(".jb-nwfd-art-mdl[data-aba='_xl_st']:last").find(".kxlib_tgspy").data("tgs-crd")
                    };
                    //*/
                }
                
    //            Kxlib_DebugVars([JSON.stringify(ads)],true);
    //            return;
            } 
            /*
            if ( $(mb).find(".jb-nwfd-art-mdl").length ) {
                var i = $(mb).find(".jb-nwfd-art-mdl:first").data("item");
                var t = $(mb).find(".jb-nwfd-art-mdl:first").find(".kxlib_tgspy").data("tgs-crd");
    //            Kxlib_DebugVars([i,t],true);
    //            return;
                ad = {
    //                "i" : "7fbbjo1f",
                    "i" : i,
    //                "t" : "1412501174721"
                    "t" : t
                };
            } 
            //*/
//            Kxlib_DebugVars([" >> NWFD_SENTINEL << ",p,d.menu,d.view,"new",mb,s,ad],true);
//            return;

            var lads = {};
            if ( $(".jb-nwfd-la-mdl-bmx").length ) {
                if ( $(".jb-nwfd-la-mdl-bmx[data-cz-type='react']").length ) {
                    lads["are"] = {
                        i : $(".jb-nwfd-la-mdl-bmx[data-cz-type='react']:first").data("cz-item"),
                        t : $(".jb-nwfd-la-mdl-bmx[data-cz-type='react']:first").data("cz-time")
                    };
                }
                
                if ( $(".jb-nwfd-la-mdl-bmx[data-cz-type='eval']").length ) {
                    lads["ali"] = {
                        i : $(".jb-nwfd-la-mdl-bmx[data-cz-type='eval']:first").data("cz-item"),
                        t : $(".jb-nwfd-la-mdl-bmx[data-cz-type='eval']:first").data("cz-time")
                    };
                }
                
                if ( $(".jb-nwfd-la-mdl-bmx[data-cz-type='fav']").length ) {
                    lads["afv"] = {
                        i : $(".jb-nwfd-la-mdl-bmx[data-cz-type='fav']:first").data("cz-item"),
                        t : $(".jb-nwfd-la-mdl-bmx[data-cz-type='fav']:first").data("cz-time")
                    };
                }
                
                /************************************ TESTY SCOPE ************************************/
                
                if ( $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsm']").length ) {
                    lads["tsm"] = {
                        i : $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsm']:first").data("cz-item"),
                        t : $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsm']:first").data("cz-time")
                    };
                }
                
                if ( $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsr']").length ) {
                    lads["tsr"] = {
                        i : $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsr']:first").data("cz-item"),
                        t : $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsr']:first").data("cz-time")
                    };
                }
                
                if ( $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsl']").length ) {
                    lads["tsl"] = {
                        i : $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsl']:first").data("cz-item"),
                        t : $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsl']:first").data("cz-time")
                    };
                }
            }
            
//            Kxlib_DebugVars([" >> NWFD_SENTINEL << ",JSON.stringify(lads)],true);
//            return;
            
            
            var xtp;
            if ( KgbLib_CheckNullity(ads) | !Kxlib_ObjectChild_Count(ads) ) {
                //Cela signifie qu'il n'y a pas déjà d'Articles présent dans la zone. Aussi, on lance une procédure normale
                p = Kxlib_GetAjaxRules("NWFD_GET_ARTS");
                _f_LoadDatas(p,d.menu,d.view,"std",mb,s);
                xtp = "fst";
            } else {
                p = Kxlib_GetAjaxRules("NWFD_GET_ARTS_FROM");
                _f_LoadDatas_From(p,d.menu,d.view,"new",mb,s,ads,lads);
                xtp = "frm";
            }

    //        var th = this;
            $(s).on("datasready", function(e,gds,b) {
                if ( KgbLib_CheckNullity(gds) | KgbLib_CheckNullity(b) ) { 
                    return; 
                }
                
//                Kxlib_DebugVars([JSON.stringify(gds)],true);

               /*
                * [DEPUIS 06-07-15] @BOR
                *       Permet de résoudre un bogue qui faisait que le trigger se déclencher à cause d'un temps de réponse du serveur trop lent
                */
                $.each(gds.as,function(x,el){
                    if ( $(mb).find(".jb-nwfd-art-mdl[data-item='"+el.id+"']").length ) {
    //                   Kxlib_DebugVars([REMOVED : "+el.id]);
                        delete gds.as[x];
                    }
                });
               
                /*
                 * [DEPUIS 05-05-16]
                 *      On ajoute les données relatives à LASTA
                 */
                if ( !KgbLib_CheckNullity(gds.las) ) {
//                    Kxlib_DebugVars([JSON.stringify(gds.las)],true);
                    _f_Lasta_None();
                    var ldir = ( xtp === "frm" ) ? "TOP" : "FST";
                    _f_Lasta_Display(gds.las,"_SC_NTWK",ldir);
                } else if (! $(".jb-nwfd-la-mdl-bmx").length ) {
                    _f_Lasta_None(true);
                }

               /*
                * [NOTE]
                *       On ne récupère que les données des Articles. Le reste ne sert à rien à la version vb1.10.14.
                *       ad : ArticleDatas, les Articles
                */
                var ad = gds.as;


               //dl = DatasList; al = AuthorList
               var dl = [], al = {};

                /*
                 * ETAPE :
                 *      On ajoute les Articles reçus dans la zone
                 * [DEPUIS 09-05-16]
                 *      On ajoute aussi les données de LEECHES
                 */
                _f_SetBfrDs(ad,d.menu);
                if ( gds.leeches ) {
                    $.each(gds.leeches,function(mn,lds){
                        var bb = $(".jb-nwfd-b-list[data-scp='"+mn+"']");
                        if ( lds.dir === "TOP" ) {
                            _f_SetBfrDs(lds.datas,mn);
                            _f_ShNwPostTrgPan(bb,Kxlib_ObjectChild_Count(lds.datas));
                        } else {
                            _f_HdlSpnr(mn,false);
                            _f_HdlNone(mn,false);
                            _f_DisplayListMode(mn,lds.datas,bb);
                        }
//                        Kxlib_DebugVars([Kxlib_ObjectChild_Count(_f_GetBfrDs(i))],true);
                    });
                }
//                Kxlib_DebugVars([th._BufferCount(d.menu)],true);
        

                /*
                 * ETAPE :
                 *      On crée la liste des EXTRAITS PSEUDO
                 */
                //Pour chaque élément reçu 
                /*
                $.each(ad, function(i,x) {
                    
                    /*
                     * [DEPUIS 07-05-16]
                     *      
                     *
                    if ( $.inArray(x.aba,["_xl_12it","_xl_3it","_xl_3im","_xl_mt","_xl_st"]) === -1 ) {
                        return true;
                    } 
                    
                    var aba;
                    switch (x.aba) {
                        case "_xl_3im" :
                                aba = ( x.isod === false ) ? "iml_frd" : "iml_pod";
                            break;
                        case "_xl_12it" :
                        case "_xl_3it" :
                        case "_xl_mt" :
                        case "_xl_st" :
                                aba = "itr";
                            break;
                        default:
                            return true;
                    }  
                    
                    if ( !al.hasOwnProperty(aba) || ( al.aba && !Kxlib_ObjectChild_Count(al.aba) ) ) {
                        al[aba] = {};
                    }
                    
                     //On récupère l'auteur et ... 
                     var ow = x.uid;
                             
//                    Kxlib_DebugVars([al,KgbLib_CheckNullity(al), typeof ow],true); 
//                    return;

                     //... on l'ajoute à la liste s'il n'y est pas déjà
//                     if ( !Kxlib_ObjectChild_Count(al) || ( al && !al.hasOwnProperty(ow) ) ) {
                     if ( !Kxlib_ObjectChild_Count(al.aba) || ( al.aba && !al.aba.hasOwnProperty(ow) ) ) {
                         al[aba][ow] = {
                             "oeid" : x.uid,
                             "opsd" : "@"+x.upsd,
                             "ofn"  : x.ufn,
                             "oppic": x.uppic,
                             "ohref": "/"+x.upsd,
                             "pnb"  : 1
                         };

                     } else {
                         //Pour chaque auteur on incrémente
                         al[aba][ow].pnb = parseInt(al.aba[ow].pnb) + 1;
                     }

//                    Kxlib_DebugVars([al[ow].n],true); 
                });
                
        //                Kxlib_DebugVars([Kxlib_ObjectChild_Count(al)],true);     

                
                /*
                 * [NOTE]
                 *      On transforme l'objet en Array (Il était en objet car plus maniable pour les opérations précédentes, en Array car plus maniable pour les suivantes)
                 *      'alt' est la version tableau 't' de al
                 *  [DEPUIS 07-05-16]
                 *      Prise en compte du mode multi-canal
                 *
                var alt = [];
                $.each(al,function(i,tab){
                    var a__ = $.map(tab,function(v,ix) {
                        return [v];
                    });
                    alt[i] = a__;
                });
                
//                Kxlib_DebugVars([alt.length],true);

                //On crée la liste et on ajoute cette dernière dans la zone (après avoir vider l'ancienne liste)
        //               Kxlib_DebugVars([typeof alt, $.isArray(al), $.isArray(alt), alt.toString()],true);
                 _f_NwfdSld_CrtList(alt);
                //*/
                
                /*
                 * ETAPE :
                 *      On affiche le trigger selon le type d'ARTICLE
                 */
                /*
                $.each(ad, function(i,x) {
                    if ( $.inArray(x.aba,["_xl_12it","_xl_3it","_xl_3im","_xl_3im_pod","_xl_mt","_xl_st"]) === -1 ) {
                        return true;
                    } 
                    
                    var aba;
                    switch (x.aba) {
                        case "_xl_3im" :
                                aba = "comy";
                            break;
                        case "_xl_3im_pod" :
                                aba = "iml_pod";
                            break;
                        case "_xl_12it" :
                        case "_xl_3it" :
                        case "_xl_mt" :
                        case "_xl_st" :
                                aba = "itr";
                            break;
                        default:
                            return true;
                    }  
                    
                    if ( !al.hasOwnProperty(aba) || ( al.aba && !Kxlib_ObjectChild_Count(al.aba) ) ) {
                        al[aba] = {};
                    }
                    
                     //On récupère l'identifiant
                     var ai = x.id;
                     if ( !Kxlib_ObjectChild_Count(al.aba) || ( al.aba && !al.aba.hasOwnProperty(al) ) ) {
                         al[aba][ai] = {
                             "aid" : ai,
                         };

                     } 

                        Kxlib_DebugVars([JSON.stringify(al)],true); 
                });
                //*/
                
                /*
                 * ETAPE :
                 *      On affiche le TRIGGER_NEWER pour cette section
                 */
                if ( xtp === "fst" ) {
                    _f_DisplayListMode(d.menu,ad,b);
                } else {
                    _f_ShNwPostTrgPan(b,Kxlib_ObjectChild_Count(ad));
                }
                
                /*
                 * [DEPUIS 05-07-15] @BOR
                 */
                if ( xtp === "fst" ) {
                    _xhr_ldfst = null;
                } else {
                    _xhr_ldfrm_top = null; 
                }

            });

            $(s).on("operended", function(e,gds,b) {
                
                /*
                 * ETAPE :
                 *      On agit sur :
                 *          -> SPINNER
                 *          -> LOADMORE
                 *          -> NoONE
                 */
                if ( xtp === "fst" ) {
                    _f_HdlSpnr(d.menu,false,"list");
                    _f_HdlLdr(d.menu,false,"list");
                    _f_HdlImBusy(d.menu,false);
                    _f_HdlNone(d.menu,true,"list");
                }
                
                
                /*
                 * [ETAPE 10-05-16]
                 *      On affiche l'information au niveau de SNITCHER_NEWFEED.
                 *      Dans ce cas, on réinitilise la valeur.
                 */
                _f_Snitcher_SetNotif(d.menu);
                
                /*
                 * [DEPUIS 04-06-16]
                 *      On ajoute les données relatives à LASTA si elles existes
                 */
                if ( !KgbLib_CheckNullity(gds) && !KgbLib_CheckNullity(gds.las) ) {
                    _f_Lasta_None();
                    var ldir = ( $(".jb-nwfd-la-mdl-bmx").length ) ? "TOP" : "FST";
                    _f_Lasta_Display(gds.las,"_SC_NTWK",ldir);
                } else if (! $(".jb-nwfd-la-mdl-bmx").length ) {
                    _f_Lasta_None(true);
                }
                
                
                /*
                 * [DEPUIS 05-07-15] @BOR
                 */
                if ( xtp === "fst" ) {
                    _xhr_ldfst = null;
                } else {
                    _xhr_ldfrm_top = null; 
                }
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    var _f_PdSentinel = function (mb) {
//    this.PredateSentinel = function (mb) {
        //Rappel : mb => MenuBox; isa = IsAutomatic (S'agit-il du cas où l'utilisateur a utilisé le scroll pour déclencher la demande de mise à jour)
        try {
            
            if ( KgbLib_CheckNullity(mb) ) {
                return;
            }
                
            /*
             * Cette m'éthode étant relative plus récente que sa méthode soeur "Sentinel" elles ont des similitudes.
             * Cependant, elle profite de l'expérience acquise au niveau de la méthode soeur.
             * * */

            //* On vérifie si le chargement de nouveaux Articles est autorisé *//
            /*
             * On vérifie si la mise à jour à par le bas est encours. Dans quel cas, il faut bloquer en attendant la fin de la mise à jour actuelle.
             * Cela permet de réduire et de limiter au maximum les erreurs possibles.
             */
            //ulk : UpdateLocK
    //        Kxlib_DebugVars([MoyenAge => "+$(mb).data("ulk")]);
            if ( $(mb).data("ulk") === 1 ) {
//                Kxlib_DebugVars([(mb).data("ulk")]);
                Kxlib_DebugVars(["NWFD","STOPPED => ",$(mb).data("ulk")]);
                
                return;
            } 
            //On lock la zone. La zone sera delock par la méthode d'affichage à cause du timer. 
            $(mb).data("ulk",1);
        
            var r = _f_ChkNewLdgAlwd(mb);
    //        alert(r); //DEBUG
    //        Kxlib_DebugVars([LIMIT => "+r]); //DEBUG
    //        return;

            if ( KgbLib_CheckNullity(r) || !r ) {
                return;
            }

            //isl = isList
            var d = {
                "view"  :   $(mb).find(".jb-nwfd-view-bloc").data("v"),
                "menu"  :   $(mb).find(".jb-nwfd-view-bloc").data("m")
            }, mn, vw, p, isl = false, s = $("<span/>");
            /* //[DEPUIS 08-04-16]
            var d = {
                "view":$(mb).data("v"),
                "menu":$(mb).data("m")
            }, mn, vw, p, isl = false, s = $("<span/>");
            //*/
        
            switch (d.menu) {
//                case "c" :
                case "comy" :
                        if ( d.view === "l") {
    //                        p = "NWFD_GET_PREDATE_COMY_LIST";
                            isl = true;
                            vw = "list";
                        } 
                        /*
                        else {
    //                        p = "NWFD_GET_PREDATE_COMY_MOZ";
                            vw = "moz";
                        }
                        //*/    
                        mn = "comy";
                    break;
                /*
                case "t" :
                        if ( d.view === "l") {
    //                        p = "NWFD_GET_PREDATE_TEAM_LIST";
                            isl = true;
                            vw = "list";
                        } else {
    //                        p = "NWFD_GET_PREDATE_TEAM_MOZ";
                            vw = "moz";
                        }
                        mn = "team";
                    break;
                case "b" :
                        if ( d.view === "l") {
    //                        p = "NWFD_GET_PREDATE_BZFD_LIST";
                            isl = true;
                            vw = "list";
                        } else {
    //                        p = "NWFD_GET_PREDATE_BZFD_MOZ";
                            vw = "moz";
                        }
                        mn = "bzfeed";
                    break;
                    //*/
                case "iml_pod" :
                        isl = true;
                        vw = "list";
                        mn = "iml_pod";
                    break;
                case "itr" :
                        isl = true;
                        vw = "list";
                        mn = "itr";
                    break;
                case "tlkb" :
                        isl = true;
                        vw = "list";
                        mn = "tlkb";
                    break;
                default :
                        //TODO : Prévenir le serveur
                    return;
            }     
                
            /*
             * [DEPUIS 05-07-15] @BOR
             *      Changer l'état visuel du bouton
             */
            _f_VwLdrStt("_STATE_LDG",vw);

    //        Kxlib_DebugVars([vw,mn,$(mb).find(".jb-nwfd-art-mdl").length,$(mb).find(".jb-nwfd-art-mdl:last").find(".kxlib_tgspy").data("tgs-crd")],true);
    //        return;

            //ads : ArticlesDataS, les données sur les derniers Articles pour chaque catégorie d'aba (ArticleBAtch)
            var ads = {};
            //On récupère des données au sur l'Article s'il existe
            if ( $(mb).find(".jb-nwfd-art-mdl").length ) {
                //RAPPEL : "_xl_12it","_xl_3it","_xl_3im","_xl_mt","_xl_st"
                if ( $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_12it']").length ) {
                    ads["_xl_12it"] = {
                        i : $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_12it']:last").data("item"),
                        t : $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_12it']:last").find(".kxlib_tgspy").data("tgs-crd")
                    };
                }
                if ( $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_2im']").length ) {
                    ads["_xl_2im"] = {
                        i : $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_2im']:last").data("item"),
                        t : $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_2im']:last").find(".kxlib_tgspy").data("tgs-crd")
                    };
                }
                if ( $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_3it']").length ) {
                    ads["_xl_3it"] = {
                        i : $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_3it']:last").data("item"),
                        t : $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_3it']:last").find(".kxlib_tgspy").data("tgs-crd")
                    };
                }
                if ( $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_3im']").length ) {
                    ads["_xl_3im"] = {
    //                    i : 1235, //TEST, DEBUG
                        i : $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_3im']:last").data("item"),
    //                    t : 1420080400000 //TEST, DEBUG
                        t : $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_3im']:last").find(".kxlib_tgspy").data("tgs-crd")
                    };
                }
                if ( $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_3im_pod']").length ) {
                    ads["_xl_3im_pod"] = {
    //                    i : 1235, //TEST, DEBUG
                        i : $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_3im_pod']:last").data("item"),
    //                    t : 1420080400000 //TEST, DEBUG
                        t : $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_3im_pod']:last").find(".kxlib_tgspy").data("tgs-crd")
                    };
                }
                if ( $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_mt']").length ) {
                    ads["_xl_mt"] = {
                        i : $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_mt']:last").data("item"),
                        t : $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_mt']:last").find(".kxlib_tgspy").data("tgs-crd")
                    };
                }
                if ( $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_st']").length ) {
                    ads["_xl_st"] = {
                        i : $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_st']:last").data("item"),
                        t : $(mb).find(".jb-nwfd-art-mdl[data-aba='_xl_st']:last").find(".kxlib_tgspy").data("tgs-crd")
                    };
                }

    //            Kxlib_DebugVars([JSON.stringify(ads)],true);
    //            return;
            } 
               
            /*
            var ad;
            //On récupère des données au sur l'Article s'il existe
            if ( $(mb).find(".jb-nwfd-art-mdl").length ) {
                var i = $(mb).find(".jb-nwfd-art-mdl:last").data("item");
                var t = $(mb).find(".jb-nwfd-art-mdl:last").find(".kxlib_tgspy").data("tgs-crd");
    //            Kxlib_DebugVars([i,t],true);
                ad = {
                    "i" : i,
                    "t" : t
                };
            } 
            //*/
    //        return;
    
            var lads = {};
            if ( $(".jb-nwfd-la-mdl-bmx").length ) {
                if ( $(".jb-nwfd-la-mdl-bmx[data-cz-type='react']").length ) {
                    lads["are"] = {
                        i : $(".jb-nwfd-la-mdl-bmx[data-cz-type='react']:first").data("cz-item"),
                        t : $(".jb-nwfd-la-mdl-bmx[data-cz-type='react']:first").data("cz-time")
                    };
                }
                
                if ( $(".jb-nwfd-la-mdl-bmx[data-cz-type='eval']").length ) {
                    lads["ali"] = {
                        i : $(".jb-nwfd-la-mdl-bmx[data-cz-type='eval']:first").data("cz-item"),
                        t : $(".jb-nwfd-la-mdl-bmx[data-cz-type='eval']:first").data("cz-time")
                    };
                }
                
                if ( $(".jb-nwfd-la-mdl-bmx[data-cz-type='fav']").length ) {
                    lads["afv"] = {
                        i : $(".jb-nwfd-la-mdl-bmx[data-cz-type='fav']:first").data("cz-item"),
                        t : $(".jb-nwfd-la-mdl-bmx[data-cz-type='fav']:first").data("cz-time")
                    };
                }
                
                /************************************ TESTY SCOPE ************************************/
                
                if ( $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsm']").length ) {
                    lads["tsm"] = {
                        i : $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsm']:first").data("cz-item"),
                        t : $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsm']:first").data("cz-time")
                    };
                }
                
                if ( $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsr']").length ) {
                    lads["tsr"] = {
                        i : $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsr']:first").data("cz-item"),
                        t : $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsr']:first").data("cz-time")
                    };
                }
                
                if ( $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsl']").length ) {
                    lads["tsl"] = {
                        i : $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsl']:first").data("cz-item"),
                        t : $(".jb-nwfd-la-mdl-bmx[data-cz-type='tsl']:first").data("cz-time")
                    };
                }
            }
            
            Kxlib_DebugVars([" >> NWFD_PDSENTINEL << ",JSON.stringify(lads)]);

            var xtp;
            if ( KgbLib_CheckNullity(ads) ) {
                //Cela signifie qu'il n'y a pas déjà d'Articles présent dans la zone. Aussi, on lance une procédure normale
                _f_LoadDatas(p,mn,vw,"std",mb,s);
                xtp = "fst";
            } else {
                p = "NWFD_GET_ARTS_FROM";
                p = Kxlib_GetAjaxRules(p);
                //pd (3 parametre) = Predate => Est ce qu'on veut afficher les anciens résultats 
    //            if ( isl ) {
                    _f_LoadDatas_From(p,mn,vw,"old",mb,s,ads,lads);
        //            _f_LdDsListMd(p,mb,true);
    //            } else {
    //                this.LoadDatas_From(p,mn,vw,"old",mb,s,ad);
        //            _f_LdDsMozMd (p,mb,true);
    //            }
                xtp = "frm";
            }
        
//        var th = this;
        $(s).on("datasready", function(e,d,b){
            if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(b) ) {
                return;
            }

//                Kxlib_DebugVars([isl,d.as,b],true);

            /*
             * [DEPUIS 05-05-16]
             */
            if ( d.las ) {
                _f_Lasta_None();
                _f_Lasta_Display(d.las,"_SC_NTWK","TOP");
            } else if (! $(".jb-nwfd-la-mdl-bmx").length ) {
                _f_Lasta_None(true);
            }

            //On ajoute les données à SHED (Hangar != Buffer Zone)
//            var sd = JSON.stringify(d.as);
//            $(".jb-nwfd-shed").text(sd);

            //On affiche les données selon le mode de vue
            if ( isl ) {
                _f_DisplayListMode(mn,d.as,b);
            } 
            /*
            else {
                _f_DisplayMozMode(d.as,b,null,true);
            }
            //*/

            //Update de NOoNE
            _f_Noone();
            
            //On libère la zone
            $(b).data("ulk",0);
            
            /*
             * [DEPUIS 05-07-15] @BOR
             */
            if ( xtp === "fst" ) {
                _xhr_ldfst = null;
            } else {
                _xhr_ldfrm_btm = null; 
            }
                    
            /*
             * [DEPUIS 05-07-15] @BOR
             * Changer l'état visuel du bouton
             */
            _f_VwLdrStt("_STATE_SLPG",vw);
            
//            Kxlib_DebugVars([th.ShedCount()],true);
        });
        
        $(s).on("operended", function(e,b) {
            
            /*
             * On arrive ici si on est dans la zone de mise à jour mais qu'aucun Article n'est disponible. 
             * Dans ce cas, on libère la zone.
             * 
             * Cependant, on inscrit la dernière fois que l'on a récupéré les données au niveau du serveur.
             * Cela permet de ne pas lancer des requetes trop souvent car la zone ne changeant pas de taille, les requetes continuent à être lancées.
             * (Sauf si on sort à nouveau de la zone)
             * Pour éviter ce comportement, on met  un timer qui vérifiera si on a attendu assez de longtemps par rapport à la demande précédente.
             */
//            alert(th._IsZoneTimeReady(b,"loc",parseInt(_f_GetComposSts()).ttc_oa));
//            alert(parseInt(_f_GetComposSts().ttc_oa));
            //loc : Last Older Check, la dernière fois que l'on est aller vérifier les données dites "antérieures"
//            parseInt(_f_GetComposSts()).ttc_oa);
            if ( _f_IsZoneTimeRdy(b,"loc",parseInt(_f_GetComposSts().ttc_oa)) ) {
                //On libère la zone
                $(b).data("ulk",0);
                //On inscrit le temps
                var n = (new Date()).getTime();
                $(b).data("loc",n);
            } else {
//                Kxlib_DebugVars([Lock Because time not reached"]);
                /*
                 * On maintient le blocage. Ce blocage est spécial car il autorise à rentrer dans la fonction à condition que la condition de temps soit remplie.
                 * Cela permet de contourner le fait que la méthode va bloquer toutes les procédures si on est en mode 1.
                 * Le mode 2 veut dire qu'on a déjà procédé à des mises à jour mais qu'on est tombé sur le cas où le serveur nous a indiqué qu'il n'y avait aucun Article "plus bas".
                 * Cependant, étant donné que l'on est dans une application hautement dynamique et qu'elle est construite ne mode SPA (Single Page Application) les choses sont imprévisibles.
                 * Aussi, pour ne pas dégrader l'expérience utilisateur, on choisit de continuer à intérroger le serveur mais seulement après un laps de temps donné.
                 * 
                 * Enfin, ce code est automatiquement changé en 1 à l'entrée de la méthode puis on 0 si la mise à jour se fait et se termine normalement OU
                 * si on est de nouveau autorisé car on a atteint ou dépassé le temps d'attente.
                 * Il repasse en 2 si aucune donnée n'est revenu du sereur (encore une fois) et que le temps d'attente n'est pas dépassé.
                 * Normalement, cela est impossible dans le cas on change le code à 0 dans le cas ci-dessus. Tout dépend donc de la logique du développeur.
                 * On pourrait passé le cas ci-dessus à 2. Cela semblerait logique, mais j'attends de voir les conséquences du code 0. Voir s'il résout quand meme le problème.
                 */
                $(b).data("ulk",2);
            }
            
            /*
             * [DEPUIS 05-07-15] @BOR
             */
            if ( xtp === "fst" ) {
                _xhr_ldfst = null;
            } else {
                _xhr_ldfrm_btm = null; 
            }
            
            /*
             * [DEPUIS 05-07-15] @BOR
             * Changer l'état visuel du bouton
             */
            _f_VwLdrStt("_STATE_SLPG",vw);
        });
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    
    var _f_TreatNewArt = function () {
//    this.TreatNewArt = function () {
        //TODO : Losrque de nouveaux Articles sont disponibles, on les affiche selon le mode en cours
    };
    
    /*
     * Permet de remettre dans l'odre les Articles qui peuvent apparaittre dans un ordre erroné.
     * Cette méthode permet donc de corriger un eventuel bogue d'affichage.
     * Ce bogue est dû au fait que les données sont chargées selon un "système de lot" et que la récupération des Articles n'est pas assez profonde. 
     * Aussi, les données sont chargées avec une limite mais ne reflète pas la réalité d'un point de vue chronomogique.
     * @param {Array} a 
     *      Le tableau contenant la clé de donnée ainsi que le bloc qui contient la liste des Articles.
     *      L'indice la première case contient la clé de donnée.
     * @returns {mixed} 
     *      false     : Si aucun Article n'est disponible dans le bloc
     *      true      : Si l'opération s'est bien déroulée
     *      undefined : Si l'opération a rencontré un problème
     */
    var _f_SortDisor = function (a) {
//        Kxlib_DebugVars([gbLib_CheckNullity(a), !$.isArray(a), a.length !== 2, $.inArray(a[0],_f_Gdf().__NWFD_VW_MD) !== -1, $(a[1]).length]);
        if ( ( KgbLib_CheckNullity(a) | !$.isArray(a) | a.length !== 2 ) || !( $.inArray(a[0],_f_Gdf().__NWFD_VW_MD) !== -1 && $(a[1]).length) ) {
            return;
        }
        
        try {
            //On détermine le bon sélecteur. 
            var $sl = $(a[1]).find(".jb-nwfd-art-mdl");
            //On récupère la liste des Articles. Si aucun Article n'existe, 
            if (! $sl.length ) {
                return false;
            }
            
            var mb = $(a[1]);
            
            //lst : Liste des données d'Article; srd : SoRteD, liste des données triées
            var lst = srd = [];
            /*
             * ListByindeX : Permet d'avoir le même tableau que LST mais avec comme clé des index incrémentés
             * SortedByIndeX : Permet d'avoir le même tableau que SRD mais avec comme clé des index incrémentés
             */
            var lbx = sbx = [];
            $.each($sl,function(x,e){
                if (! $(e).length ) {
                    return true;
                }
                
                //NOTE : a : Index, b : ItemId, c : Timestamp. Je tente l'utilisation de nommage 'incrémenté' pour des raisons de ssécurité (rétro-ingénierie)
                lst.push({
                    "a" : $(e).data("item"),
                    "b" : Kxlib_DataCacheToArray($(e).data("cache"))[0][1][0]
                });
            });
//            alert(lst[5].a);
            srd = lst.slice(0);
            //On trie en se basant sur le Timestamp par ordre DESC
            srd.sort(function(a,b) {
                return b["b"] - a["b"];
            });
//            alert(lst[5].a);
//            Kxlib_DebugVars([JSON.stringify(srd),"-- SEPARATOR --",JSON.stringify(srd)],true);
            //On parcours le tableau pour détecter les anomalies
            var ano = 0;
            var sk = [];
            var str = {};
//            return;
            $.each($sl,function(x,e){
                var a__ = srd[x].a, b__ = $(e).data("item");
                if ( b__ !== a__ ) {
                    var $b = $(e);
//                    var $b = $(mb).find(".jb-nwfd-art-mdl[data-item='"+b__+"']");
                    var acl;
                    if( $(mb).find(".jb-nwfd-art-mdl[data-item='"+a__+"']").length ) {
                        acl = $(mb).find(".jb-nwfd-art-mdl[data-item='"+a__+"']").clone(true,true);
                        
                        var $b__ = $(mb).find(".jb-nwfd-art-mdl[data-item='"+b__+"']").clone(true,true);
                        
//                        var agp = acl.find(".nwfd-b-m-mdl-trig").data("grp"), agd = acl.find(".nwfd-b-m-mdl-trig").data("grid");
//                        $b__.find(".nwfd-b-m-mdl-trig").data("grp",agp);
//                        $b__.find(".nwfd-b-m-mdl-trig").data("grid",agd);
                                
                        str[b__] = $b__;
                        
//                        Kxlib_DebugVars([From DOM"]);
                    } else {
//                        Kxlib_DebugVars([From ARRAY"]);
                        acl = str[a__];
                        if (! str.hasOwnProperty(b__) ) {
                            str[b__] = $(mb).find(".jb-nwfd-art-mdl[data-item='"+b__+"']").clone(true,true);
                        }
                    }
                    
                    var bgp = $b.find(".nwfd-b-m-mdl-trig").data("grp"), bgd = $b.find(".nwfd-b-m-mdl-trig").data("grid");
                    
//                    Kxlib_DebugVars([a__,JSON.stringify(Object.getOwnPropertyNames(str)),$(mb).find(".jb-nwfd-art-mdl[data-item='"+a__+"']").length],true);
                    
                    
                    acl.find(".nwfd-b-m-mdl-trig").data("grp",bgp);
                    acl.find(".nwfd-b-m-mdl-trig").data("grid",bgd);
                    $b.replaceWith(acl);
//                    alert("A => "+acl.data("item")+" B => "+$b.data("item")+" XST => "+$(mb).find(".jb-nwfd-art-mdl[data-item='"+a__+"']").length+" XST_CL => "+$acl.length);
                    $(acl).removeAttr("style");
//                    Kxlib_DebugVars([xlib_ObjectChild_Count(str)]);
                }
                /*
                return true;
                
                if ( sk.length && $.inArray($(e).data("item"),sk) !== -1 ) {
                    Kxlib_DebugVars([TREATED",$(e).data("item")]);
                    return true;
                }
                //On vérifie que pour l'index x, l'Article est bien placé par rapport à la liste triée
                var a__ = srd[x].a, b__ = $(e).data("item");
                
                if ( b__ !== a__) {
                    ++ano;
                    /*
                     * ETAPE : 
                     * A ce stade, L'élément 'e' est à la place que doit légitimement occuper 'srd[x]'.
                     * Il ne reste plus qu'à vérifier qu'elle doit être le rang exacte de 'e' et qui se trouve à sa place dans la liste originelle.
                     *
                    //On recherche l'index légitime de 'e' dans la liste triée (ex)
                    var ex;
                    $.each(srd,function(x1,v){
                        if ( v.a === b__ ) {
                            ex = x1;
                            return false;
                        }
                    });
                    //On récupère l'identifiant l'objet qui si trouve à l'indice 'ex', "xo"
                    xo = lst[ex,ex.lineNumber].a;
//                    Kxlib_DebugVars(["A => "+a__,"B => "+b__,"C => "+xo,ex,lst[x].a],true);
//                    return;
                    /*
                     * ETAPE :
                     * On procède à l'opération "ChaiseMusical".
                     * L'oération consite à :
                     *  (1) Mettre 'srd[x]' à la place de 'e'
                     *  (2) Mettre e à la place de 'xo'
                     *  (3) Mettre xo à la place de 'srd[x]'
                     *  Ces changement dépendent aussi des objets mentionnées. Pour plus d'informations, se reporter à la fonction dédiée. 
                     *
                    if ( a__ !== xo ) {
                        alert("VA DORMIR");
                    }
                    
                    var a1 = $(mb).find(".jb-nwfd-art-mdl[data-item='"+a__+"']");
                    var a2 = $(mb).find(".jb-nwfd-art-mdl[data-item='"+b__+"']");
                    var a3 = $(mb).find(".jb-nwfd-art-mdl[data-item='"+xo+"']");
                    _f_ChMuz(a1,a2,a3);
                    
                    sk.push(xo);
                    $sl[ex,ex.lineNumber] = $(e);
                }
                    //*/    
            });
            
//            Kxlib_DebugVars([ANOMALIES = > ",ano]);
            
            return KgbLib_CheckNullity(lst);
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    /*
     * Permet d'effectuer une opération de transposition entre les 3 éléments passés en paramètre.
     * L'ordre suivant lequel est passé les Objets est essentiel car cela déterminera le résultat de l'opération.
     * Les objets changent de place pour permettre de corriger des bogues en ce qui concerne leur positionnement dans le DOM.
     * L'opération se déroule de la manière suivante :
     *  (1) a prend la place de b
     *  (2) b prend la place de c
     *  (3) c prend la place de a
     * 
     * @param {Object} a 
     * @param {Object} b
     * @param {Object} c
     * @returns {mixed}
     *  true      : En cas de succès
     *  false     : L'pération n'a pas pu se réaliser
     *  undefined : En cas d'erreur
     */
    var _f_ChMuz = function (a,b,c) {
        if ( KgbLib_CheckNullity(a) | KgbLib_CheckNullity(b) | KgbLib_CheckNullity(c) ) {
            return;
        }
        
        try {
            //On vérifie si les trois éléments sont identiques
            if ( a === b && b === c ) {
                return false;
            }
            //On vérifie si au moins un des Objets n'est pas présent dans le DOM
            if ( !$(a).length | !$(b).length | !$(c).length ) {
                return false;
            }
            /*
             * ETAPE :
             * On procède au remplacement les éléments a, b et c.
             * Comme pour tous les autres rempslacement, on utilisera un système de remplacement via des clones pour éviter la perte définitive des données dans le DOM.
             */
            //On clone les éléments
            var acl = $(a).clone(true,true), bcl = $(b).clone(true,true), ccl = $(c).clone(true,true);
//            Kxlib_DebugVars(["A => "+$(a).data("item"),"B => "+$(b).data("item"),"C => "+$(c).data("item")],true);
//            Kxlib_DebugVars(["ACL => "+$(acl).data("item"),"BCL => "+$(bcl).data("item"),"CCL => "+$(ccl).data("item")],true);
            if ( $(c).data("item") !== $(bcl).data("item") ) {
                //On remplace c par le clone de b
                $(c).replaceWith(bcl);
                //On retire la balise "style" qui ne nous permet pas de visualiser les nouveaux objets.
                $(bcl).removeAttr("style");
                Kxlib_DebugVars(["C("+$(c).data("item")+") has been replaceed by B("+$(b).data("item")+")"]);
            }
            if ( $(b).data("item") !== $(ccl).data("item") ) {
                //On remplace b par le clone de c
                $(b).replaceWith(ccl);
                //On retire la balise "style" qui ne nous permet pas de visualiser les nouveaux objets.
                $(ccl).removeAttr("style");
                Kxlib_DebugVars(["B("+$(b).data("item")+") has been replaceed by C("+$(c).data("item")+")"]);
            }
/*
            if ( $(b).data("item") !== $(acl).data("item") ) {
                //On remplace b par le clone de a
                $(b).replaceWith(acl);
                //On retire la balise "style" qui ne nous permet pas de visualiser les nouveaux objets.
                $(acl).removeAttr("style");
                Kxlib_DebugVars([B("+$(b).data("item")+") has been replaceed by A("+$(a).data("item")+")"]);
            }
            if ( $(c).data("item") !== $(bcl).data("item") ) {
                //On remplace c par le clone de b
                $(c).replaceWith(bcl);
                //On retire la balise "style" qui ne nous permet pas de visualiser les nouveaux objets.
                $(bcl).removeAttr("style");
                Kxlib_DebugVars([C("+$(c).data("item")+") has been replaceed by B("+$(b).data("item")+")"]);
            }
            if ( $(a).data("item") !== $(ccl).data("item") ) {
                //On remplace a par le clone de c
                $(a).replaceWith(ccl);
                //On retire la balise "style" qui ne nous permet pas de visualiser les nouveaux objets.
                $(ccl).removeAttr("style");
                Kxlib_DebugVars([A("+$(a).data("item")+") has been replaceed by C("+$(c).data("item")+")"]);
            }
            */
            return true;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    /********************** DISPLAYING SPECIAL TREATMENTS ***********************/
    
    var _f_RdcHdr = function () {
        try {
            
//    this.ReduceHeader = function () {
            if ( $(".jb-nwfd-hdr-txt-grp-mx").hasClass("wild") ) {
                //BUT : Réduire la taille du Header + augmenter la taille du body

                //On réduit les parties "inutiles" du Header
                $(".jb-nwfd-hdr-txt-grp-mx").stop(true,true).animate({
                    height: 0
                },500,function(){
                    /*
                    $("#nwfd-h-title-max").toggleClass("this_hide",500);
                    $("#nwfd-h-desc-max").toggleClass("this_hide",500);
                    //*/


                    /* On rétablit la taille du body */
    //                $(".jb-nwfd-body").removeAttr("style");

                    /* On ajuste la taille de la zone Slided */
                    _f_UpdNwfdSidePan();

                    //On signale la zone comme ouverte
                    $(".jb-nwfd-hdr-txt-grp-mx").switchClass("wild","shy");
                });
                /*
                 * [NOTE 19-03-15] @Lou
                 * On ne peut pas déterminer la hauteur quand celle si est modifiée par un autre processus.
                 * La hauteur est connue et fixe, on peut donc utiliser la valeur directement dans notre calcul.
                 */
                var h = $(".jb-nwfd-sprt").height() - 65 - $(".jb-nwfd-footer").height();
                $(".jb-nwfd-body").height(h);
            } else {
                //BUT : On Augmente la taille du Header et Réduit la taille du body

                //On rétablit les parties "inutiles" du Header
                $(".jb-nwfd-hdr-txt-grp-mx").stop(true,true).animate({
                    height: _f_Gdf().__NWFD_HDR_H
                },500,function(){
                    /*
                    $("#nwfd-h-title-max").toggleClass("this_hide");
                    $("#nwfd-h-desc-max").toggleClass("this_hide");
                    //*/

                    /* On agrandit la zone de la liste en fonction de l'espace restant */
                    var h = $(".jb-nwfd-sprt").height() - $(".jb-nwfd-hdr").height() - $(".jb-nwfd-footer").height();
                    $(".jb-nwfd-body").height(h);

                    /* On ajuste la taille de la zone Slided */
                    _f_UpdNwfdSidePan();

                    //On signale la zone comme fermée
                    $(".jb-nwfd-hdr-txt-grp-mx").switchClass("shy","wild");
                });
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    /********************** DATAS SPECIAL TREATMENTS ***********************/
    var _f_PprMozBarDs = function (o) {
//    this._PrepareMozBarDatas = function (o) {
        /**
         * Premier bloc : accid, pseudo, actif est abonné à acc
         * Deuxieme bloc : resultat evaluation, nombre de commentaires
         * Troisième bloc : trid, titre de la Tendance
         */

        
        /* Transformation en tableau */
        //[NOTE 21-07-14 ] Rendu obselete par la nouvelle méthode 'DataCacheToArray'. On était obliger de l'utiliser pour faire fonctionner UNQ
        /*
        var tb = $(o).data("cache").match(/(\[.[^\[]*\])/g);
        
        $.each(tb,function(i,v){
            v = v.replace(/[\[]|[\]]/g,"");
            tb[i] = v.split(',');
        });
        //*/
        try {
            var tb = Kxlib_DataCacheToArray($(o).data("cache"));
            tb = tb[0];
//        var s = "Trouve => "+tb.length;
//        Kxlib_DebugVars([tb],true);
            
            /* On cible la bonne barre */
            // lirk = LIneRank; cela permet de cibler la bonne barre à afficher
//        var lirk = $(o).data("grid").toString().match(/\[.*,(.*)\]/g);
            var lirk = $(o).data("grid").replace(/[\[]|[\]]/g, "").split(',');
            var sl = "lirk-" + lirk[0];
//            Kxlib_DebugVars([sl],true);
            sl = Kxlib_ValidIdSel(sl);
            
            /* On met en forme les données */
            //TODO
//        alert("KJC is :"+tb[1][1]);
            /* On insère les données */
            /* Au sujet de User */
            $(sl).find(".nwfd-moz-user-psd").text(Kxlib_ValidUser(tb[0][2]));
            $(sl).find(".nwfd-b-mz-l-b-s-u-u").attr("href", tb[0][4]);
            $(sl).find(".nwfd-moz-user-img").attr("src", tb[0][3]);
            
            //Au sujet d'EVAL
//            Kxlib_DebugVars([tb[3][4]]);
            //[NOTE 21-07-14, COMMENTAIRE DESUET] ]NOTE : Normalement on a [[eval],react]. Mais le code n'a pas pris en compte ce cas. Aussi, si on souhaite atteindre Eval, on y accède grace à [3][3]
            $(sl).find(".nwfd-moz-eval-val").text(tb[3][3]);
            
            //Au sujet de reaction
//        alert("Reaction is :"+tb[3][1]);
            //NOTE : Normalement on a [[eval],react]. Mais le code n'a pas pris en compte ce cas. Aussi, si on souhaite atteindre React, on y accède grace à [3][4]
            $(sl).find(".nwfd-moz-react-val").text(tb[4][0]);
            
            //On verifie s'il s'agit d'un article de type TREND
            if (!KgbLib_CheckNullity(tb[2])) {
                //TODO : On ajoute le badge TREND
                
                //TODO: On bind le badge avec la fonction de traitement
            }
            
            return $(sl);
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }

    };
    
    
    
    var _f_FocusOnMozBar = function (b) {
//    this.FocusOnMozBar = function (b) {
        if ( KgbLib_CheckNullity(b)) { return; }
        $(b).toggleClass("this_hide");
    };
    
    var _f_Noone = function () {
//    this.Noone = function () {
        /* Affiche un message lorsqu'il n'y a aucun contenu disponible */
        var r = _f_GetComposSts();
        var v = r.view, sl;
        
//        sl = ( v === "list" ) ? "#nwfd-b-list" : "#nwfd-b-moz"; //old
        sl = ( v === "list" ) ? "#nwfd-b-list" : "#nwfd-b-moz-max";
       
        if (! $(sl).children().not(".jb-nwfd-none-mx, .nwfd-b-list-bttf").length ) {
            //Retirer BTTF
            $(sl).find(".nwfd-b-list-bttf").addClass("this_hide");
            
            //Afficher le message disant qu'il n'y a aucun Article
            $(sl).find(".jb-nwfd-none-mx").removeClass("this_hide");
        } else {
            
            //Retablir BTTF
            $(sl).find(".nwfd-b-list-bttf").removeClass("this_hide");
            
            //Retirer le message disant qu'il n'y a aucun Article
            $(sl).find(".jb-nwfd-none-mx").addClass("this_hide");
        }
        
    };
    
    
    /******************** SERVER EXCHANGES *******************/
    
    /**************************************************************/
    //URQID => Update les paramètres de NewsFeed
    var _Ax_UpdtComposSts = Kxlib_GetAjaxRules("UPD_NWFD_PARAMS",Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_UpdtComposSts = function (d) {
//    this._Srv_UpdateComposStatus = function (d) {
        //TODO : Met à jour le statut des composants pour améliorer l'expérience utilisateur
        //RunningMode (Ouvert/Fermer); Section (Team, Comy, BuzzFeed); View (List, Moz)

        if ( KgbLib_CheckNullity(d) || Kxlib_ObjectChild_Count(d) !== 4 )
            return;
        
        var th = this;
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) 
                    datas = JSON.parse(datas);
                
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    //NOTHING
                    
                }
                    
            } catch (e) {
                //TODO : ?
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //NOTHING
        };
        
        var toSend = {
            "urqid": _Ax_UpdtComposSts.urqid,
            "datas": {
                "conf": d
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_UpdtComposSts.url, wcrdtl : _Ax_UpdtComposSts.wcrdtl });
    };
    
    
    /************************************************************** NEWSFEED LASTA SCOPE **************************************************************/
    
    var _f_Lasta_Action = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity(a) && KgbLib_CheckNullity($(x).data("action")) ) ) {
                return;
            }
            
            var _a = ( KgbLib_CheckNullity(a) ) ? $(x).data("action") : a;
            switch (_a) {
                case "reveal" :
                        _f_Lasta_Goto(x);
                    break;
                default:
                    return;
            }
            

        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Lasta_Goto = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            var am = ( $(x).is(".jb-nwfd-la-mdl-bmx") ) ? x : $(x).closest(".jb-nwfd-la-mdl-bmx");
            var u = $(am).data("aprmlk");
            
//            window.location.href = u;
            window.open(u,'_blank');
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Lasta_Display = function (ds,sec,dir) {
        try {
            if ( KgbLib_CheckNullity(ds) | KgbLib_CheckNullity(sec) ) {
                return;
            }
            
//            Kxlib_DebugVars([JSON.stringify(ds),sec,dir],true);
            
            sec = sec.toString().toUpperCase();
            if ( $.inArray(sec,["_SC_MINE","_SC_NTWK"]) === -1 ) {
                return;
            }
            
            dir = dir.toString().toUpperCase();
            if ( $.inArray(dir,["FST","TOP"]) === -1 ) {
                return;
            }
            
            /*
             * ETAPE :
             *      S'il s'agit de TOP on reverse
             */
            if ( dir === "TOP" ) {
                ds = ds.reverse();
            }
            
            /*
             * ETAPE :
             *      On affiche les données en prenant de soin de "retenir" les "nouvelles" activités et de ne pas afficher celles déjà présentes.
             */
            var cz, santa = [];
            $.each(ds,function(i,at){
                cz = at.acz_tp;
                switch (cz) {
                    case "react" :
                    case "eval" :
                    case "fav" :
                    case "tsm" :
                    case "tsr" :
                    case "tsl" :
                            if ( $(".jb-nwfd-la-mdl-bmx[data-cz-type='"+cz+"']").filter("[data-cz-item='"+at.acz_id+"']").length ) {
                                return true;
                            } else {
                                santa.push(at);
                            }
                        break;
                    default : 
                        return true;
                }
                
                // ETAPE : On crée le MODEL
                var em = _f_lasta_PprMdl(at);
                
                // ETAPE : On rebind le MODEL
                em = _f_lasta_RbdMdl(em);
                
                // ETAPE : Si on change de SECTION on retire les anciens éléments
                var cursec = $(".jb-nwfd-la-scrn-h-fil.active").data("target");
                if ( cursec && cursec.toUpperCase() !== sec ) {
                    $(".jb-nwfd-la-mdl-bmx").remove();
                }
                
                if ( dir === "FST" ) {
                    $(em).hide().appendTo(".jb-nwfd-la-list-bdy-lst-mx").fadeIn();
                } else if ( dir === "TOP" ) {
                    $(em).hide().prependTo(".jb-nwfd-la-list-bdy-lst-mx").fadeIn();
                }
                
                setTimeout(function(){
                    if ( $(".jb-nwfd-la-mdl-bmx").length() === 1 ) {
                        $(".jb-nwfd-la-list-bdy-lst-mx").perfectScrollbar({
                            suppressScrollX : true
                        });
                    } else if ( $(".jb-nwfd-la-mdl-bmx").length() > 1 ) {
                        $(".jb-nwfd-la-list-bdy-lst-mx").perfectScrollbar("update");
                    }
                },200);
            
            });
            
            /*
             * ETAPE :
             *      On gère le cas des NOTIFICATIONS le cas échéant.
             *  [NOTE 10-05-16] 
             *      On effectue un cumul avec la valeur déjà présente car :
             *          1. Le nombre va se reinitiliaser sans fin et correspondra au dernier SET chargés ... 
             *          ... plutôt que sur toutes les activités non lues
             *          2. Même dans le cas où la valeur présente au niveau de SNITCHER est 0, cela fait toujours l'affaire
             *          3. La valeur va se réinitialiser à l'ouverture du module, il y a donc théoriquement peu de probabilité d'erreur
             *      Si on est focus sur NEWSFEED, il ne faut pas effectuer de mise à jour. Cependant, il est plus prudent de REINIT la valeur.
             */
            var lascn = ( ds && Kxlib_ObjectChild_Count(ds) > 0 ) ? 
                Kxlib_ObjectChild_Count(ds) + parseInt($(".jb-tqr-hl-n-n-tgr[data-scp='nwfd-lasta']").find(".figure").text())   
                : 0;
//            Kxlib_DebugVars([santa.length,( KgbLib_CheckNullity($(".jb-nwfd-sprt").data("access")) | $(".jb-nwfd-sprt").data("access") === 0 ),dir,dir === "TOP"],true);
            if ( santa.length 
                && ( KgbLib_CheckNullity($(".jb-nwfd-sprt").data("access")) | $(".jb-nwfd-sprt").data("access") === 0 )
                && dir && dir === "TOP"
            ) {
                _f_Snitcher_SetNotif("lasta",lascn);
            } else {
                _f_Snitcher_SetNotif("lasta",0);
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Lasta_None = function (shw) {
        try {
            
            if ( shw ) {
                $(".jb-nwfd-la-scrn-none").removeClass("this_hide");
            } else {
                $(".jb-nwfd-la-scrn-none").addClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /**********************************************************************************************************************************************/
    /************************************************************** VIEW SCOPE ********************************************************************/
    /**********************************************************************************************************************************************/
    
    /****************** VIEW MODE MANAGEMENT ******************/
    
    var _f_SwToMoz = function () {
//    this.SwitchToMoz = function () {
//        alert("Reached Moz");
        var m = Kxlib_ValidIdSel(_f_Gdf().__NWFD_MOZ_VIEW);
        var l = Kxlib_ValidIdSel(_f_Gdf().__NWFD_LIST_VIEW);
        $(l).addClass("this_hide");
        $(m).removeClass("this_hide");
        
        /* Afficher le MenuBlock correspondant */
        //mtg = MenuTarGet 
        var mtg = $(".jb-nwfd-h-mn-elt.nwfd-menu-active").data("target");
        var csl = {
            "team": { "mh": "nwfd-h-menu-team-hover", "mb":"nwfd-moz-team" },
            "comy": { "mh": "nwfd-h-menu-comy-hover", "mb":"nwfd-moz-comy" },
            "bzfeed": { "mh": "nwfd-h-menu-bzfeed-hover", "mb":"nwfd-moz-bzfeed" }
        };
        
        $(".jb-nwfd-view-bloc").addClass("this_hide");
        
//        alert(Kxlib_ValidIdSel(csl[mtg].mb));
//        return;
        var $o = $(Kxlib_ValidIdSel(csl[mtg].mb));
        $o.removeClass("this_hide");
    };
    
    var _f_SwToList = function () {
//    this.SwitchToList = function () {
        var m = Kxlib_ValidIdSel(_f_Gdf().__NWFD_MOZ_VIEW);
        var l = Kxlib_ValidIdSel(_f_Gdf().__NWFD_LIST_VIEW);
        $(m).addClass("this_hide");
        $(l).removeClass("this_hide");
        
        /* Afficher le MenuBlock correspondant */
        //mtg = MenuTarGet 
        var mtg = $(".jb-nwfd-h-mn-elt.nwfd-menu-active").data("target");
        var csl = {
            "team": { "mh": "nwfd-h-menu-team-hover", "mb":"nwfd-list-team" },
            "comy": { "mh": "nwfd-h-menu-comy-hover", "mb":"nwfd-list-comy" },
            "bzfeed": { "mh": "nwfd-h-menu-bzfeed-hover", "mb":"nwfd-list-bzfeed" }
        };
        
        $(".jb-nwfd-view-bloc").addClass("this_hide");
        
        var $o = $(Kxlib_ValidIdSel(csl[mtg].mb));
//        alert($o.hasClass("this_hide"));
        $o.removeClass("this_hide");
//        alert($o.hasClass("this_hide"));
//        return;
    };
    
    var _f_SwVw = function(o,a) {
//    this.SwitchView = function(o,a) {
        try {
            
            if ( KgbLib_CheckNullity(a) ) {
                return; //Todo : Déclencher une erreur
            }
            
            switch (a) {
                case _f_Gdf().__NWFD_LIST_VIEW :
                        _f_SwToList();
                    break;
                case _f_Gdf().__NWFD_MOZ_VIEW :
                        _f_SwToMoz();
                    break;
                default:
                    return;
            }
            
            var b1 = _f_GetActvMnB2();
            
            /* On retire la classe active à old et on la mets sur new */
            //Retirer sur old
            $(".jb-nwfd-view-active").removeClass("jb-nwfd-view-active enabled");
            //Mettre sur new
            $(o).addClass("jb-nwfd-view-active enabled");
            
            /* Vérifier, récupérer et afficher les données */
            var id;
            clearTimeout(id);
            id = setTimeout(function() {
                //Lancement de la vérification de nouveaux Articles
                _f_Sentinel(b1);
                
                // Module NOoNE
                _f_Noone();
                
                //Mise à jour de la configuration de NewsFeed
                _f_UpdtComposSts();
            }, _f_Gdf().__NWFD_BF_LOAD);
            
        } catch (ex) {
//            Kxlib_DebugVars(["1885",ex],true);
        }

    };
    
    /******************* MENUS MANAGEMENT ****************/
    
    var _f_MnHvr = function (o) {
//    this.MenuHover = function (o) {
        //o = objet actif
        if ( KgbLib_CheckNullity(o) | KgbLib_CheckNullity($(o).data("target")) ) { return; }
        
        //csl = class_selector
        var csl = ["nwfd-h-menu-team-hover","nwfd-h-menu-comy-hover","nwfd-h-menu-bzfeed-hover"],
                t = $(o).data("target");
        
        switch (t) {
            case "team" :
                    if ( !$(o).hasClass("nwfd-menu-active") )
                        $(o).toggleClass(csl[0]);
                    
                    $(".jb-nwfd-h-mn-elt[data-target='comy']:not(.nwfd-menu-active)").removeClass(csl[1]);
                    $(".jb-nwfd-h-mn-elt[data-target='bzfeed']:not(.nwfd-menu-active)").removeClass(csl[2]);
                break;
            case "comy" :
                    if ( !$(o).hasClass("nwfd-menu-active") )
                        $(o).toggleClass(csl[1]);
                    
                    $(".jb-nwfd-h-mn-elt[data-target='team']:not(.nwfd-menu-active)").removeClass(csl[0]);
                    $(".jb-nwfd-h-mn-elt[data-target='bzfeed']:not(.nwfd-menu-active)").removeClass(csl[2]);
                break;
            case "bzfeed" :
                    if ( !$(o).hasClass("nwfd-menu-active") )
                        $(o).toggleClass(csl[2]);
                    
                    $(".jb-nwfd-h-mn-elt[data-target='team']:not(.nwfd-menu-active)").removeClass(csl[0]);
                    $(".jb-nwfd-h-mn-elt[data-target='comy']:not(.nwfd-menu-active)").removeClass(csl[1]);
                break;
        }
    };
    
    
    var _f_SwMn = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("target")) ) {
                return;
            }
            
            var tgt = $(x).data("target"), $amx, $mnsl;
            
            switch (tgt) {
                case "comy" :
                        $amx = $(".jb-nwfd-b-list[data-scp='comy']"); //J'aurais voulu changer vers iml_frd mais j'ai eu peur des répercussions
                        $mnsl = $(".jb-nwfd-h-mn-elt[data-target='comy']");
                        moveto = 0;
                        clr = "";
                    break;
                case "iml_pod" :
                        $amx = $(".jb-nwfd-b-list[data-scp='iml_pod']");
                        $mnsl = $(".jb-nwfd-h-mn-elt[data-target='iml_pod']");
                        moveto = 1;
                    break;
                case "itr" :
                        $amx = $(".jb-nwfd-b-list[data-scp='itr']");
                        $mnsl = $(".jb-nwfd-h-mn-elt[data-target='itr']");
                        moveto = 2;
                    break;
                case "tlkb" :
                        $amx = $(".jb-nwfd-b-list[data-scp='tlkb']");
                        $mnsl = $(".jb-nwfd-h-mn-elt[data-target='tlkb']");
                        moveto = 3;
                    break;
                default : 
                    return;
            }
            
            if (! $amx.length ) {
                return;
            } 
            
            $(".jb-nwfd-h-menu-sldr").stop(true,true).animate({
                left : 120*moveto
            });
            
            $(".jb-nwfd-b-list").addClass("this_hide");
            $amx.removeClass("this_hide");
            
            $(".jb-nwfd-h-mn-elt").removeClass("nwfd-menu-active");
            $(x).addClass("nwfd-menu-active");
            
            
            if ( !$amx.find(".jb-nwfd-bind-com-art").length ) {
                _f_HdlSpnr(tgt,true,"list");
                _f_HdlNone(tgt,false,"list");
            }
            
            /*
             * [DEPUIS 04-06-16]
             *      La spécificité 'IMBUSY" permet de ne pas laisser l'utilisateur dans une situation où il ne comprends pas pourquoi la zone est vide !
             */
            if ( !KgbLib_CheckNullity(_xhr_ldfst) 
                && ( KgbLib_CheckNullity(_xhr_ldfrm_top) | KgbLib_CheckNullity(_xhr_ldfrm_btm) ) 
                && !$amx.find(".jb-nwfd-art-mdl").length
            ) {
                _f_HdlSpnr(tgt,false,"list");
                _f_HdlImBusy(tgt,true);
                _f_Sentinel($amx);
            } else if ( !$amx.find(".jb-nwfd-bind-com-art").length && KgbLib_CheckNullity(_xhr_ldfst) ) {
                _f_HdlImBusy(tgt,false);
                _f_FirstArt($amx);
            } else if ( KgbLib_CheckNullity(_xhr_ldfrm_top) | KgbLib_CheckNullity(_xhr_ldfrm_btm) ) {
                _f_HdlImBusy(tgt,false);
                _f_Sentinel($amx);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    
    /*
    var _f_SwMn = function (o) {
//    this.SwitchMenu = function (o) {
        
        if ( KgbLib_CheckNullity(o) | KgbLib_CheckNullity($(o).data("target")) ) {
            return; //Afficher ET/OU envoyer au serveur une erreur
        }
    
        //t = target
        var nt = $(o).data("target"), ot = $(".jb-nwfd-h-mn-elt.nwfd-menu-active").data("target");
//        alert("NewTarget => "+nt+"OldTarget => "+ot);
        
        //On prépare les données en fonction de la configuration actuelle de NewsFeed
        //csl = ClassSeLector; mh = MenuHover; mb = MenuBloc
        var csl;
        var d = _f_GetComposSts();
        //cm ContraryMode, Le mode contraire à celui actuellement activé. Permet de synchoniser les deux modes de Vue
        var cv;
        if ( d.view === "list" ) {
            var csl = {
                "team": { "mh": "nwfd-h-menu-team-hover", "mb":"nwfd-list-team" },
                "comy": { "mh": "nwfd-h-menu-comy-hover", "mb":"nwfd-list-comy" },
                "bzfeed": { "mh": "nwfd-h-menu-bzfeed-hover", "mb":"nwfd-list-bzfeed" }
            };
            cv = "nwfd-b-moz-max";
        } else {
            var csl = {
                "team": { "mh": "nwfd-h-menu-team-hover", "mb":"nwfd-moz-team" },
                "comy": { "mh": "nwfd-h-menu-comy-hover", "mb":"nwfd-moz-comy" },
                "bzfeed": { "mh": "nwfd-h-menu-bzfeed-hover", "mb":"nwfd-moz-bzfeed" }
            };
            cv = "nwfd-b-list";
        }
        
        switch (nt) {
//            case "team" :
            case "comy" :
//            case "bzfeed" :
                    _f_PrfmSwToMn(o,nt,csl,ot,cv);
                break;
        }
        
        //On sauvegarde le nouveau bloc pour ne pas se tromper
        var b1 = _f_GetActvMnB2();
//        return;
        
        /* Vérifier, récupérer et afficher les données */
        //On attends x secondes pour lancer la procédure. Pour éviter de lancer la procédure alors que l'user ...
        // ... a changé de Menu avant même de commencer
        /*
         * [NOTE 05-07-15] @BOR
         * Le code ci-dessous est bizarre ...
        var id;
        clearTimeout(id);
        id = setTimeout(function(){
                _f_Sentinel(b1);

                /* Module NOoNE */
//                _f_Noone();
                
                /* Mettre à jour les parmètres 
                _f_UpdtComposSts();
        },_f_Gdf().__NWFD_BF_LOAD);
        
    };
    
    var _f_PrfmSwToMn = function (o,nt,csl,ot,cv) {
//    this._PerformSwitchToMenu = function (o,nt,csl,ot,cv) {
        //o = object; nt = NewTarget, csl = class, ot = OldTarget, cv = ContraryView
//        alert(c);
        try {
            
//        if ( $(".jb-nwfd-h-mn-elt.nwfd-menu-active").length ) 
            if (!KgbLib_CheckNullity(ot)) {
                //On désactive l'ancien Menu
                var $tp = $(".jb-nwfd-h-mn-elt.nwfd-menu-active");
                $tp.removeClass("nwfd-menu-active");
                //On lui enlève la signature bottom
                $tp.removeClass(csl[ot].mh);
            }
            
            /* On active le menu 
            //On active 
            $(o).addClass("nwfd-menu-active");
            //On ajoute la signature bottom
            $(o).addClass(csl[nt].mh);
            
            /* 
             * On affiche le bloc correspondant au menu 
             *  
            //omb = OldMenuBlock; nmb = NewMenuBlock
            var $omb = $(Kxlib_ValidIdSel(csl[ot].mb)), $nmb = $(Kxlib_ValidIdSel(csl[nt].mb));
            //On cache l'ancien bloc
            $omb.addClass("this_hide");
            //On affiche le nouveau bloc
            $nmb.removeClass("this_hide");
            
            /* 
             * On s'assure que les bloc de la Vue contraire soit fermée. Le bon menu sera sélectionné à l'ouveerture 
             *  
            $(Kxlib_ValidIdSel(cv)).find(".jb-nwfd-view-bloc").addClass("this_hide");
            
            //On lance la vérification pour voir s'il y a de nouveaux Articles
            
            //On enlève l'annotation (x nouveaux éléménts si elle existe
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }

    };
    //*/
                                                                                                                                                        
    var _f_VwLdrStt = function(stt,scp) {
        try {
            if ( KgbLib_CheckNullity(scp) ) {
                return;
            }
            
            stt = ( KgbLib_CheckNullity(stt) ) ? "_STATE_SLPG" : stt.toUpperCase(); 
            scp = scp.toUpperCase();
            
            if ( $.inArray(stt,["_STATE_SLPG","_STATE_LDG"]) === -1 ) {
                return;
            }
            
            var $bm, $nw, $ol;
            switch (scp) {
                case "LIST" :
                        $bm = $(".jb-nwfd-b-list");
                    break;
                default:
                    return;
            }
            
            if ( stt === "_STATE_SLPG" ) {
                $nw = $bm.find(".jb-nwfd-loadm-trg-stt[data-state='_STATE_SLPG']"); 
                $ol = $bm.find(".jb-nwfd-loadm-trg-stt[data-state='_STATE_LDG']"); 
            } else {
                $nw = $bm.find(".jb-nwfd-loadm-trg-stt[data-state='_STATE_LDG']"); 
                $ol = $bm.find(".jb-nwfd-loadm-trg-stt[data-state='_STATE_SLPG']"); 
            }
            
            $ol.addClass("this_hide");
            $nw.removeClass("this_hide");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };                                                                                                                                                    
                                                                                                                                                        
    /****************************************************************************************************************************************************************************/
    /******************************************************************************** VIEW SCOPE ********************************************************************************/
    /****************************************************************************************************************************************************************************/
    
    /******************** LAST ACTIVITIES SCOPE ********************/
    
    var _f_lasta_PprMdl = function (at){
        try {
            if ( KgbLib_CheckNullity(at) ) { 
                return; 
            }
            
            var m = "<article class=\"nwfd-la-article jb-nwfd-la-mdl-bmx\" data-cz-item=\"\" data-cz-time=\"\" data-cz-type=\"\" data-ajcache=\"\">";
            m += "<div class=\"nwfd-la-art-hdr\">";
            m += "<a class=\"nwfd-la-usrbx-href jb-nwfd-la-ubx-href\" href=\"\" title=\"\">";
            m += "<span class=\"nwfd-la-usrbx-psd jb-nwfd-la-ubx-pp-psd\"></span>";
            m += "</a>";
            m += "<span class=\"nwfd-la-usrbx-ppic jb-nwfd-la-usrbx-pp\">";
            m += "<span class=\"nwfd-la-usrbx-pp-fd jb-nwfd-la-usrbx-pp-fd\"></span>";
            m += "<img class=\"nwfd-la-usrbx-pp-i jb-nwfd-la-ubx-pp-i\" width=\"45\" height=\"45\" src=\"\"/>";
            m += "</span>";
            m += "</div>";
            m += "<div class=\"nwfd-la-art-bdy jb-nwfd-la-art-bdy\"></div>";
            m += "</article>";
            m = $.parseHTML(m);
            
            /*
            "aid"           => $atb['art_eid'],
                        "aim"           => $atb['art_pdpic_realpath'],
                        "adsc"          => html_entity_decode($atb['art_desc']),
                        "aplk"          => "/f/".$atb['art_prmlk'],
                        "adt"           => $atb['art_cdate_tstamp'],
                        "acz_tp"        => $atb['case_type'],
                        "acz_id"        => $atb['case_eid'],
                        "acz_dt"        => $atb['case_date'],
                        // ACTOR SCOPE
                        "acz_aeid"      => $atb['case_aeid'],
                        "acz_afn"       => $atb['case_afn'],
                        "acz_apsd"      => $atb['case_apsd']
                        //*/
            
            /*
             * ETAPE :
             *      Insertion des données d'ENTETE
             */
            $(m)
                .data({
                    "cz-item"   : at.acz_id,
                    "cz-time"   : at.acz_dt,
                    "cz-type"   : at.acz_tp,
                    "aprmlk"    : at.aplk,
                    "ajcache"   : JSON.stringify(at),
                })
                .attr({
                    "data-cz-item"  : at.acz_id,
                    "data-cz-time"  : at.acz_dt,
                    "data-cz-type"  : at.acz_tp,
                    "data-aprmlk"   : at.aplk,
                    "data-ajcache"  : JSON.stringify(at),
                });
                
            
            /*
             * ETAPE :
             *      Ajout des données de base
             */
            
            //DATAS : Le LIEN vers le profil de l'ACTOR
            $(m).find(".jb-nwfd-la-ubx-href").attr({
                "href" : "/".concat(at.acz_apsd),
                "title" : "".concat(at.acz_afn)
            });
            
            if ( at.aim ) {
                //DATAS : L'IMAGE de l'ACTICLE
                $(m).find(".jb-nwfd-la-ubx-pp-i").attr({
    //                "src" : at.acz_appi,
                    "src" : at.aim,
                });
                
                if ( at.avid ) {
                    $(m).find(".jb-nwfd-la-usrbx-pp-fd").addClass("vidu");
                }
            } else {
                $(m).find(".jb-nwfd-la-usrbx-pp").remove();
            }
            
            
            //DATAS : Le PSEUDO de l'ACTOR
            $(m).find(".jb-nwfd-la-ubx-pp-psd").text("@".concat(at.acz_apsd));
            
//            Kxlib_DebugVars(["NWFD TYPE : ",at.acz_tp]);
            //DATAS : Le MESSAGE de description de l'ACTION
            var txt;
            switch (at.acz_tp) {
                case "react" :
                        txt = "a ajouté un commentaire";
                    break;
                case "eval" :
                        txt = "a ajouté une appréciation";
                    break;
                case "fav" :
                        txt = "a mis en favori une publication";
                    break;
                case "tsm" :
                        txt = "a ajouté un statut";
                    break;
                case "tsr" :
                        txt = "a commenté un statut";
                    break;
                case "tsl" :
                        txt = "a aimé un statut";
                    break;
                default:
                    return;
            }
            $(m).find(".jb-nwfd-la-art-bdy").text(txt);
            
            return m;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
                
    var _f_lasta_RbdMdl = function (m){
        try {
            if ( KgbLib_CheckNullity(m) ) { 
                return; 
            }
            
            $(m).hover(function(e){
                $(this).addClass("hover");
            },function(e){
                $(this).removeClass("hover");
            });
            
            $(m).click(function(e){
                Kxlib_PreventDefault(e);
                Kxlib_StopPropagation(e);

                _f_Lasta_Action(this,"reveal");
            });
            
            $(m).find("a").click(function(e){
                Kxlib_StopPropagation(e);
            });
            
            return m;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /******************** DATAS DISPLAYING MANAGEMENT ********************/
    var _f_ShwArtBar = function (o) {
//    this.ShowArtBar = function (o) {
        if ( KgbLib_CheckNullity(o) ) { return; }
        
        //TODO : Vérifier si l'objet est conforme
        
        //Prepare les données au niveau de la barre
        var $ob = _f_PprMozBarDs(o);
//        Kxlib_DebugVars([typeof $ob,$($ob).length],true);
        var $old_ob;
        //On retire l'élément barre specs
        //$(o).closest(".nwfd-b-moz-l-list").find(".nwfd-b-moz-art-specs").toggleClass("this_hide");
              
        
        //Affiche la barre des infos lorsqu'on est dans le mode MOZ
//        if ( parseInt($ob.css('opacity')) === 0 ) {
        if ( $($ob).css('display') === 'none' ) {
            
//            setTimeout(function(){
//                Kxlib_DebugVars([ob.is(":hover")]);
                //*
                try {
                    if ($(o).is(":hover")) {
                        // $ob.removeClass("this_hide");
    //                    $ob.stop(true,true).removeClass("this_invi",1000);
                        //On masque l'élément specs d'en bas
                        $(o).closest(".group_of_4").find(".nwfd-b-moz-art-specs").hide("slide", {direction: "down"}, 300);
                        $($ob).stop(true, true).fadeIn(300);
                    }
                } catch (ex) {
                    Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
                }

                //*/
//            },200);
            
        } else {
           setTimeout(function(){
//                Kxlib_DebugVars([ob.is(":hover")]);
//                alert(typeof $ob.data("iso"));
                //*
                
                try {
                    if ( !$($ob).is(":hover") && $(o).closest(".nwfd-b-moz-l-list").find(".nwfd-b-m-mdl-trig:hover").length === 0 ) {
//                if ( !$ob.is(":hover") && parseInt($ob.data("iso")) === 1 ) {
                        $($ob).stop(true, true).fadeOut(200);
                        //On réaffiche les specs de down
                        $(o).closest(".group_of_4").find(".nwfd-b-moz-art-specs").fadeIn();    
                    }
                } catch (ex) {
                    Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
                }

                //*/
            },200);
        }
    };
    
    var _f_PprArtListMode = function (v) {
//    this._PrepareArtListMode = function (v) {
        
        try {
            /*
             * ARCHI :
             *      //OLD
             *      "id" => L'id de l'article
             *      "theme" => Dans quel catégirie a été placé l'article : inml, intr
             *      "time" => La date de création au format TimeStamp GMT
             *      "pic" => La photo liée à l'article
             *      "eval" => Un tableau contenant les données d'évaluations de l'article [-1, cool, cool+, tot]
             *      "react" => Le nombre de commentaires,
             *      "desc" => La 'description' liée à l'article ajoutée.
             *      "author" => Les données liées à l'auteur de l'article : exid, fullname, psd, pic
             *      
             *      [Depuis 03-10-14] @author L.C.
             *      -- ARTICLE DATAS SCOPE --
             *      art_eid: L'identifiant externe de l'Article
             *      art_desc: Le texte accompagnant l'Article (s'il existe)
             *      art_crea_tstamp: La date de création
             *      art_evals: La liste des Evaluations de l'Article [+2,+1,-1,tot]
             *      art_tot: La valeur de l'Article
             *      art_me: L'évaluation de l'utilisateur actif sur l'Article
             *      art_hashs: Liste des hashtags
             *      art_pic_rpath: L'adresse physique de l'image liée à l'Article
             *      art_rnb: Le nombre de commentaires de l'Article
             *      -- AUTHOR DATAS SCOPE --
             *      art_oeid: L'identifiant de l'auteur de l'Article
             *      art_ofn: Le nom complet de l'auteur de l'Article
             *      art_oppic_rpath: Le chemin vers l'image de profil de l'auteur de l'Article
             *      art_opsd: Le pseudo de l'auteur de l'Article
             *      //[AJOUT 20-03-15] @BOR
             *      aba : L'identifiant du groupe (Bactch) auquel appartient l'Article
             * * * */
            
            if ( KgbLib_CheckNullity(v) ) { 
                return; 
            }
            
            //On remplace toutes les valeurs de type null par "" (void)
            v = Kxlib_ReplaceIfUndefined (v);
            var art = v;
            /*
             * Ici on crée aussi bien des modeles pour ITR que IML. Aussi, il faut être précautionneux sur les propriétés faisant référence à TR.
             * Aussi, pour les valeurs litigieuses ont s'assurent qu'elles existent. Sinon on les remplacent. Sinon l'utilisateur verait apparaitre 'undefined' quelque part. Ca fait bu d'amateur.
             */
            //thm = theme
            var thm;
            if (! KgbLib_CheckNullity(art.trd_eid) ) {
                thm = {
                    "k" : "intr",
                    "v" : "TREND"
                };
            } else {
                thm = {
                    "k" : "inml",
                    "v" : "MyLIFE"
                };
            }
            
            var str__;
            if ( art.hasOwnProperty("ustgs") && art.ustgs !== undefined && typeof art.ustgs === "object" ) {
                var istgs__ = [];
                $.each(art.ustgs,function(x,v){
                    var rw__ = [];
                    $.map(v,function(e,x){
                        rw__.push(e);
                    });
                    istgs__.push(rw__.join("','"));
                });
    //            Kxlib_DebugVars([JSON.stringify(istgs__)],true);
                str__ = ( istgs__.length > 1 )? istgs__.join("'],['") : istgs__[0];
                str__ = "['"+str__+"']";
            }
        
            art.eval = art.eval.toString().split(",");
                    
            var t = "<article id=\"nf-el-lt-"+art.id+"\" class=\"nwfd-b-l-mdl-max jb-nwfd-art-mdl jb-nwfd-bind-com-art jb-unq-bind-art-mdl\" data-item=\""+art.id+"\" data-time=\""+art.time+"\" data-atype=\""+thm.k+"\" data-aba =\""+art.aba+"\" ";
//            t += "data-cache=\"['" + art_eid + "','" + art.img + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(art.msg)) + "','" + Kxlib_ReplaceIfUndefined(art.trd_eid) + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(art.trtitle)) + "','" + art.rnb + "','" + Kxlib_ReplaceIfUndefined(art.trhrf) + "','"+art.prmlk+"'],['" + art.time + "','" + "" + "'],['" + art.eval[0] + "','" + art.eval[1] + "','" + art.eval[2] + "','" + art.eval[3] + "','" + art_eval_lt[0] + "','" + art.eval_lt[1] + "','" + art_eval_lt[2] + "','" + art_eval_lt[3] + "'],['" + art.uid + "','" + art.ufn + "','" + Kxlib_ValidUser(art.upsd) + "','" + art.uppic + "','" + art.uhref + "'],['" + Kxlib_ReplaceIfUndefined(art.me) + "']\" ";
//            t += "data-cache=\"['" + art.id + "','" + art.img + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(art.msg)) + "','" + Kxlib_ReplaceIfUndefined(art.trd_eid) + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(art.trtitle)) + "','" + art.rnb + "','" + Kxlib_ReplaceIfUndefined(art.trdhrf) + "','"+art.prmlk+"'],['" + art.time + "','" + "" + "'],['" + art.eval[0] + "','" + art.eval[1] + "','" + art.eval[2] + "','" + art.eval[3] + "','" + "" + "','" + "" + "','" + "" + "','" + "" + "'],['" + art.uid + "','" + art.ufn + "','" + Kxlib_ValidUser(art.upsd) + "','" + art.uppic + "','" + art.uhref + "'],['" + Kxlib_ReplaceIfUndefined(art.me) + "']\" ";
//            t += "data-cache=\"['" + art.id + "','" + art.img + "','" + Kxlib_ReplaceIfUndefined(art.msg) + "','" + Kxlib_ReplaceIfUndefined(art.trd_eid) + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(art.trtitle)) + "','" + art.rnb + "','" + Kxlib_ReplaceIfUndefined(art.trdhrf) + "','"+art.prmlk+"'],['" + art.time + "','" + "" + "'],['" + art.eval[0] + "','" + art.eval[1] + "','" + art.eval[2] + "','" + art.eval[3] + "','" + "" + "','" + "" + "','" + "" + "','" + "" + "'],['" + art.uid + "','" + art.ufn + "','" + Kxlib_ValidUser(art.upsd) + "','" + art.uppic + "','" + art.uhref + "'],['" + Kxlib_ReplaceIfUndefined(art.me) + "']\" ";
            t += "data-cache=\"['" + art.id + "','" + art.img + "','{adesc}','" + Kxlib_ReplaceIfUndefined(art.trd_eid) + "','{trtle}','" + art.rnb + "','" + Kxlib_ReplaceIfUndefined(art.trhref) + "','"+art.prmlk+"'],['" + art.time + "','" + "" + "'],['" + art.eval[0] + "','" + art.eval[1] + "','" + art.eval[2] + "','" + art.eval[3] + "','" + "" + "','" + "" + "','" + "" + "','" + "" + "'],['" + art.uid + "','" + art.ufn + "','" + Kxlib_ValidUser(art.upsd) + "','" + art.uppic + "','" + art.uhref + "'],['" + Kxlib_ReplaceIfUndefined(art.me) + "']\" ";
            t += "data-with=\""+Kxlib_ReplaceIfUndefined(str__)+"\" ";
            t += "data-hatr=\""+art.hatr+"\" ";
            t += "data-vidu=\""+art.vidu+"\" ";
            t += ">";
            t += "<div class=\"jb-tqr-cldstrg this_hide\">";
            t += "<span class=\"jb-tqr-csg-elt\" data-item='adsc'></span>";
            t += "<span class=\"jb-tqr-csg-elt\" data-item='trtle'></span>";
            t += "</div>";
            t += "<div class=\"nwfd-b-l-mdl-hdr\">";
            t += "<div class=\"nwfd-b-l-mdl-h-time\">";
            t += "<span class='kxlib_tgspy nwfd-tgspy-css' data-tgs-crd=\"" + art.time + "\" data-tgs-dd-atn=\"" + "" + "\" data-tgs-dd-uut=\'\'>";
            t += "<span class='tgs-frm'></span>";
            t += "<span class='tgs-val'></span>";
            t += "<span class='tgs-uni'></span>";
            t += "</span>";
            t += "</div>";
            t += "<div class=\"nwfd-b-l-mdl-h-bdg\" data-thcode=\"" + thm.k + "\">";
//            t += "<span class=\"nwfd-art-l-bdg-in\">in</span>";
//            t += "<span class=\"\">" + thm.v + "</span>";
            if ( art.isod ) {
                t += "<span class=\"nwfd-art-h-xtrainf-mx jb-nwfd-art-h-xtif-mx pod\" >";
                t += "<span class=\"_narative\" ></span>";
                t += "<span class=\"_remaining\" ></span>";
                t += "</span>";
            }
            t += "</div>";
            t += "</div>";
            t += "<div class=\"nwfd-b-l-mdl-body jb-nwfd-b-l-mdl-body\">";
            t += "<div class=\"nwfd-b-l-mdl-b-box jb-nwfd-b-l-mdl-b-box\">";
            t += "<a class=\"nwfd-b-l-mdl-b-box-box jb-nwfd-b-l-mdl-b-box-box\" href=\"\">";
            t += "<img class=\"nwfd-b-l-mdl-b-box-img\" width=\"500\" height=\"500\" src=\"" + art.img + "\" />";
            t += "<span class=\"nwfd-b-l-mdl-b-box-fade jb-nwfd-b-m-mdl-p-fd\"></span>";
            t += "<span class=\"nwfd-b-l-mdl-b-box-specs\">";
            t += "<span class=\"nwfd-b-l-mdl-react\" data-cache=\"" + art.rnb + "\">";
            t += "<span class=\"jb-unq-react\">" + art.rnb + "</span>";
            t += "<span class=\"nwfd-lst-r-lg\"></span>";
            t += "</span>";
            t += "<span class=\"nwfd-b-l-mdl-eval jb-csam-eval-oput\" data-cache=\"[" + art.eval[0] + "," + art.eval[1] + "," + art.eval[2] + "," + art.eval[3] + "]\">";
            t += "<span>" + art.eval[3] + "</span>";
//            t += "<span class=\"nwfd-lst-evl-lg\">c<i>!</i></span>";
            t += "</span>";
            t += "</span>";
            t += "</a>";
            t += "</div>";
            t += "</div>";
            t += "<div class=\"nwfd-b-l-mdl-ftr\">";
            t += "<div class=\"nwfd-b-l-mdl-ftr-stdby\">";
            t += "<p class=\"nwfd-b-l-mdl-ftr-trtitle this_hide\"><a class=\"nwfd-b-l-mdl-ftr-tr-go\" data-cache=\"[" + art.trd_eid + ",{atrtle}]\" href=\"" + art.trhref + "\"></a></p>";
//            t += "<p class=\"nwfd-b-l-mdl-ftr-trtitle this_hide\"><a class=\"nwfd-b-l-mdl-ftr-tr-go\" data-cache=\"[" + art.trd_eid + "," + art.trtitle + "]\" href=\"" + art.trhref + "\">" + art.trtitle + "</a></p>";
            t += "</div>";
            t += "<p class=\"nwfd-b-l-mdl-ftr-desc jb-nwfd-b-l-mdl-ftr-dsc\">";
//            t += "" + Kxlib_Decode_After_Encode(art.msg) + "";
            t += "</p>";
            t += "<div class=\"nwfd-b-l-mdl-ftr-ftr\">";
            t += "<div class=\"nwfd-b-l-mdl-ftr-user\">";
            t += "<a class=\"nwfd-b-l-mdl-u-box\" data-cache=\"[" + art.uid + "," + art.ufn + "," + art.upsd + "," + art.uppic + "]\" href=\"" + art.uhref + "\">";
            t += "<span class=\"nwfd-b-l-mdl-u-i-fade\"></span>";
            t += "<img class=\"nwfd-b-l-mdl-u-img\" width=\"65\" height=\"65\" src=\"" + art.uppic + "\" />";
            t += "<span class=\"nwfd-b-l-mdl-u-psd\">@" + art.upsd + "</span>";
            t += "</a>";
            t += "</div>";
            /*
            t += "<div class=\"nwfd-b-l-mdl-ftr-specs\">";
            t += "<div class=\"jb-csam-eval-box css-eval-box css-eval-box-tmlnr css-eval-box-nwfd clearfix\">";
            t += "<span class=\"jb-csam-eval-oput css-csam-eval-oput\" data-cache=\"[" + art.eval[0] + "," + art.eval[1] + "," + art.eval[2] + "," + art.eval[3] + "," + art.me + "]\"><span>" + art.eval[3] + "</span> coo<i>!</i></span>";
            t += "<div>";
            t += "<a id=\"\" class=\"jb-csam-eval-choices jb-csam-eval-spcool css-csam-eval-chs css-c-e-chs-scl\" data-action=\"rh_spcl\" data-zr=\"rh_spcl\" data-rev=\"bk_spcl\" data-target=\"\" data-xc=\"unq\" title=\"SupaCool\" href=\"\"></a>";
            t += "<a id=\"\" class=\"jb-csam-eval-choices jb-csam-eval-cool css-csam-eval-chs css-c-e-chs-cl\" data-action=\"rh_cool\" data-zr=\"rh_cool\" data-rev=\"bk_cool\" data-target=\"\" data-xc=\"unq\" title=\"J'adhère\" href=\"\"></a>";
            t += "<a id=\"\" class=\"jb-csam-eval-choices jb-csam-eval-dislk css-csam-eval-chs css-c-e-chs-dsp\" data-action=\"rh_dislk\" data-zr=\"rh_dislk\" data-rev=\"bk_dislk\" data-target=\"\" data-xc=\"unq\" title=\"J'adhère pas\" href=\"\"></a>";
            t += "</div>";
            t += "</div>";
            t += "</div>";
            //*/
            t += "</div>";
            t += "</div>";
            t += "</article>";
            var e = $.parseHTML(t);
            
            /*
             * [DEPUIS 25.-04-16]
             */
            var hasfv = ( art.hasfv ) ? 1 : 0;
            $(e)
                //[DEPUIS 30-04-16]
                .data("trq-ver",'ajca-v10').attr("data-trq-ver",'ajca-v10')
                .data("ajcache",JSON.stringify(art)).attr("data-ajcache",JSON.stringify(art))
                .data("hasfv",hasfv).attr("data-hasfv",hasfv);
        
            /*
             * ETAPE :
             *      Inscription des données en ce qui concerne le temps restant pour PHOTOS DU JOUR
             */
            if ( $(e).find(".jb-nwfd-art-h-xtif-mx").length ) {
//                var rtm = Kxlib_RemTime(nw,parseInt(art.time));
                if ( KgbLib_CheckNullity(art.pod_rtm) ) {
                    $(e).find(".jb-nwfd-art-h-xtif-mx").remove();
                    Kxlib_DebugVars(["NWFD : A dépassé la date OU problème de DATE #1"]);
//                    return;
                } else {
                    var tm__;
                    if ( art.pod_rtm.h !== 0 ) {
                        tm__ = "".concat(art.pod_rtm.h," heures");
                    } else if ( art.pod_rtm.m !== 0 ) {
                        tm__ = "".concat(art.pod_rtm.m," minutes");
                    } else if ( art.pod_rtm.s !== 0 ) {
                        tm__ = "".concat(art.pod_rtm.s," secondes");
                    } else  {
                        $(e).find(".jb-nwfd-art-h-xtif-mx").remove();
                        Kxlib_DebugVars(["NWFD : A dépassé la date OU problème de DATE #2"]);
//                    return;
                    }
                        
                    var txt = "Ne sera plus visible d'ici ";
                    $(e).find(".jb-nwfd-art-h-xtif-mx").find("._narative").text(txt);
                    $(e).find(".jb-nwfd-art-h-xtif-mx").find("._remaining").text(tm__);
                }
            }
            
                    
            /*
             * ETPAE :
             * Insertion du texte de description.
             */
            /*
            var t__ = art.msg;
//            var t__ = Kxlib_Decode_After_Encode(art.msg);
            if ( str__ && str__.length ) {
                t__ = Kxlib_Decode_After_Encode(t__);
                
                var ustgs = Kxlib_DataCacheToArray(str__)[0];
//                Kxlib_DebugVars([Kxlib_ObjectChild_Count(art.ustgs),ustgs[3]],true);
                var ps = ( ustgs && $.isArray(ustgs[0]) ) ? Kxlib_GetColumn(3,ustgs) : [ustgs[3]];
//                var ps = ( Kxlib_ObjectChild_Count(art.ustgs) > 1 ) ? Kxlib_GetColumn(3,ustgs) : [ustgs[3]];
                t__ = Kxlib_UsertagFactory(t__,ps,"tqr-unq-user");
                
                $(e).find(".nwfd-b-l-mdl-ftr-desc").text(t__);
                t__ = $(e).find(".nwfd-b-l-mdl-ftr-desc").text();
                t__ = Kxlib_SplitByUsertags(t__);
                
                $(e).find(".nwfd-b-l-mdl-ftr-desc").html(t__);
                
                /* 
                 * [DEPUIS 05-05-15] @BOR
                 * Gestion de la zone de cache dans le DOM. 
                 * Chaque cas est différent.
                 * *
                var m = $("<div/>").html(art.msg).text();
                var t = $("<div/>").html(art.trtitle).text();
                $(e).find(".jb-tqr-csg-elt[data-item='adsc']").text(m);
                $(e).find(".jb-tqr-csg-elt[data-item='trtle']").text(t);
            } else {
                t__ = $("<div/>").html(t__).text();
//                if ( art.id === "7fbbjodm" ) {
//                    alert(t__);
//                }
                $(e).find(".nwfd-b-l-mdl-ftr-desc").text(t__);
                
                /* 
                 * [DEPUIS 05-05-15] @BOR
                 * Gestion de la zone de cache dans le DOM. 
                 * Chaque cas est différent.
                 * *
                var m = art.msg;
//                var m = $("<div/>").html(art.msg).text();
                var t = $("<div/>").html(art.trtitle).text();
                $(e).find(".jb-tqr-csg-elt[data-item='adsc']").text(m);
                $(e).find(".jb-tqr-csg-elt[data-item='trtle']").text(t);
            }
            //*/
            
            /*
             * [DEPUIS 09-04-16]
             */
//            Kxlib_DebugVars([art.msg,art.ustgs,typeof art.ahashs,art.ahashs],true);
            //rtxt = RenderedText
            var brut = $("<div/>").html(art.msg).text();
            var rtxt = Kxlib_TextEmpow(brut,art.ustgs,art.hashs,null,{
                emoji : {
                    "size"          : 36,
                    "size_css"      : 22,
                    "position_y"    : 3
                }
            });
//                Kxlib_DebugVars([rtxt],true);
            $(e).find(".jb-nwfd-b-l-mdl-ftr-dsc").text("").append(rtxt);
            
            //TODO : Selon le type (itr; iml) on change l'entete visible + On change le data-atype + titre TR
            //t += "<p class=\"nwfd-b-l-mdl-ftr-trtitle this_hide\"><a class=\"nwfd-b-l-mdl-ftr-tr-go\" data-cache=\"["+v.trd_eid+","+v.trtitle+"]\" href=\""+v.trhref+"\">"+v.trtitle+"</a> </p>";
            if (! KgbLib_CheckNullity(art.trd_eid) ) {
                
                var c = "[" + art.trd_eid + ",{atrtle}]";
//                var c = "[" + art.trd_eid + "," + art.trtitle + "]";
                $(e).find(".nwfd-b-l-mdl-ftr-tr-go").data("cache", c);
                $(e).find(".nwfd-b-l-mdl-ftr-tr-go").prop("href", art.trhref);
                $(e).find(".nwfd-b-l-mdl-ftr-tr-go").text(Kxlib_Decode_After_Encode(art.trtitle));
                
                $(e).find(".nwfd-b-l-mdl-ftr-trtitle").removeClass("this_hide");
                
            } else {
                $(e).find(".nwfd-b-l-mdl-ftr-tr-go").data("cache", "");
                $(e).find(".nwfd-b-l-mdl-ftr-tr-go").prop("href", "");
                $(e).find(".nwfd-b-l-mdl-ftr-tr-go").text("");
                
                $(e).find(".nwfd-b-l-mdl-ftr-trtitle").addClass("this_hide");
                
            }
            
            /*
             * [DEPUIS 27-04-15] @BOR
             * On insère les données sur la desription et le titre de la Tendance
             */
//            var desc = Kxlib_Decode_After_Encode(art.msg);
//            var trtle = (! KgbLib_CheckNullity(art.trtitle) ) ? Kxlib_Decode_After_Encode(art.trtitle) : "";
            
//            var m = art.msg;
//            var m = $("<div/>").text(desc).text();
//            var t = art.trtitle;
//            var t = $("<div/>").text(trtle).text();
//            $(e).find(".jb-tqr-csg-elt[data-item='adsc']").text(m);
//            $(e).find(".jb-tqr-csg-elt[data-item='trtle']").text(t);

            /*
             * [DEPUIS 09-04-16]
             */
            if ( art.vidu ) {
                $(e).find(".jb-nwfd-b-m-mdl-p-fd").addClass("vidu");
            }
            
            //TODO : retourner le bloc
            return e;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
            return;
        }

    };
    
    var _f_PptArtListMode_ReBind = function (a) {
//    this._PrepareArtListMode_BindHandler = function (a) {
        if ( KgbLib_CheckNullity(a) ) {
            return; 
        }
        
        //Bind pour faire marcher le hover
        $(a).find(".jb-nwfd-b-l-mdl-b-box-box").hover(_f_HvrListElt);
                
        //Bind Unique 
        $(a).find(".jb-nwfd-b-l-mdl-b-box-box").click(function(e){
            Kxlib_PreventDefault(e);
            (new Unique()).OnOpen("nwfd",this);
        });
                 
        return a;
    };
    
    
//    this.DisplayDatasListMode = function (d,mb,isn) {
//    var _f_DisplayListMode = function (d,mb,isn) {
    var _f_DisplayListMode = function (mn,d,mb,isn) { //[DEPUIS 08-04-16]
        //d = tableau d'objets de datas, isn = IsNewarticles , permet de changer la façon dont les images seront ajoutées dans le bloc.
        try {
            
            if ( KgbLib_CheckNullity(mn) | KgbLib_CheckNullity(d) | KgbLib_CheckNullity(mb) ) {
                return;
            }
            
            /*
             * [DEPUIS 05-07-15] @BOR
             */
            _f_HdlSpnr(mn,false,"list");
            _f_HdlNone(mn,false,"list");
            /*
             * [DEPUIS 04-06-16]
             */
            _f_HdlImBusy(mn,false);
            
            $(".jb-nwfd-view-bloc").removeClass("this_hide");
            
            //*
//           alert("COBRA ! "); 
//           alert("COBRA ! "+$(mb).attr("id")); 
            var cn = 0, ft = $(mb).find(".jb-nwfd-art-mdl:first");
            $.each(d,function(i,v) {
//            setTimeout(function(){
                
                //On s'assure que l'élément n'est pas déjà présent dans la zone. Sinon on annule son ajout
                if ( _f_ChkEltNoExtsInList(mb,v) ) { 
//                        Kxlib_DebugVars([v.id+" EXISTS WHEN .LENGTH =>"+$(".jb-nwfd-art-mdl[data-item="+v.id+"]").length],true);
//                    Kxlib_DebugVars([Exists ! "+v.id]);
                    return; 
                }
                
                //On s'assure que la limite des Articles n'a pas été atteinte
                var tl = ++$(mb).find(".jb-nwfd-art-mdl").length;
//                var tl = $(mb).find(".jb-nwfd-art-mdl").length + 1;
                if ( tl > _f_Gdf().__NWFD_LIST_MAXNB ) {
                    return;
                }
                
                //On construit le bloc
                var b = _f_PprArtListMode(v);
                
                //On lie les éléments clés
                b = _f_PptArtListMode_ReBind(b);
//                alert(b);
//                return;
                /* On ajoute les blocs dans le body */
                var $o = $(mb);
                
//                $(b).hide().insertAfter($o.find(".jb-nwfd-menu-rcl")).slideDown(1000);
                
                /*
                 * [DEPUIS 06-05-15] @BOR
                 * Règle le bogue qui fait que 
                 */
                if (! $($o).find(".jb-nwfd-art-mdl").length ) {
                    $(b).hide().prependTo($o.find(".jb-nwfd-arts-lst-mx")).slideDown(1000);
//                    $(b).hide().insertAfter($o.find(".jb-nwfd-menu-rcl")).slideDown(1000);
                    
                    ft = $(mb).find(".jb-nwfd-art-mdl:first");
                } else if (! isn ) {
                    var c = $($o).find(".jb-nwfd-art-mdl").length, $l = $($o).find(".jb-nwfd-art-mdl:last");
                    
//                Kxlib_DebugVars([c],true);
//                Kxlib_DebugVars([$l],true);
//                Kxlib_DebugVars([$o.attr("id")],true);
                    
                    if (c) {
                        $(b).hide().insertAfter($l).slideDown(1000);
                    } else {
                        $(b).hide().prependTo($o.find(".jb-nwfd-arts-lst-mx")).slideDown(1000);
//                        $(b).hide().insertAfter($o.find(".jb-nwfd-menu-rcl")).slideDown(1000);
                    }
                } else {
                    
                    if ( !$(ft).length ) { return false; }
                    /*
                     * Cette manière d'insérer les éléments dépend de l'ordre selon lequel les données ont été triées au niveau du serveur.
                     * C'est bien pour cela qu'un changement au niveau des serveurs DOIT entrainer un chagement du mode d'insertion à ce niveau.
                     */
                    $(b).hide().insertBefore(ft).slideDown(1000);
                }
                
                
                /* Mettre à jour le module NOoNE */
                _f_Noone();    
//            },1000);
                
//                if ( $($o).find(".jb-nwfd-art-mdl").length === Kxlib_ObjectChild_Count(d) ) {
//                    $($o).data("ulk",0);
//                }
                
//                Kxlib_DebugVars([Dans la zone : "+$($o).find(".jb-nwfd-art-mdl").length]);
//                Kxlib_DebugVars([Articles : "+Kxlib_ObjectChild_Count(d)]);
            });
            
            _f_SortDisor(['list',$(mb)]);
            
            //On libère la zone
            $(mb).data("ulk", 0);
            
            /*
             * [DEPUIS 05-07-15] @BOR
             */
            _f_HdlLdr(mn,true,"list");
            
            //*/
//        Kxlib_DebugVars(["NWFD >>> A la fin => "+$(mb).data("ulk")]);
//        Kxlib_DebugVars(["NWFD >>> A la fin 1 => "+$(mb).is(".jb-nwfd-b-list")]);
//        Kxlib_DebugVars(["NWFD >>> A la fin 2 => "+$(mb).is(".jb-nwfd-view-bloc")]);
//        Kxlib_DebugVars([$($o).find(".jb-nwfd-art-mdl").length],true);
//        Kxlib_DebugVars([$(b).data("ulk")],true);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }

    };
    
    var _f_DsplPrdtDsListMd = function (d,mb) {
//    this.DisplayPredateDatasListMode = function (d,mb) {
        //d = tableau d'objets de datas
        if ( KgbLib_CheckNullity(d) || KgbLib_CheckNullity(mb) ) 
            return;
        
        //*
//           alert("COBRA ! "+mb.attr("id")); 
        var th = this;
        $.each(d,function(i,v){
            setTimeout(function(){
                //On s'assure que l'élément n'est pas déjà présent dans la zone. Sinon on annule son ajout
                if ( _f_ChkEltNoExtsInList(mb,v) ) { return; }
                
                //On construit le bloc
                var b = _f_PprArtListMode(v);
                        
                //On lie les éléments clés
                b = _f_PptArtListMode_ReBind(b);
    //            alert(b);
    //            return;
                
                /* On ajoute les blocs dans le body */
                //l = last
                var $o = mb;
                var c = $($o).find(".nwfd-b-l-mdl-max").length, $l = $($o).find(".nwfd-b-l-mdl-max:last");
//                alert("OWN => "+$l.length);
                if ( c ) {
                    $(b).hide().insertAfter($l).slideDown(1000);
                } else {
                    $(b).hide().insertAfter($o.find(".jb-nwfd-menu-rcl")).slideDown(1000);
                }

                /* Mettre à jour le module NOoNE */
                _f_Noone();    
            },1000);
        });
        //*/
        
    };
    
    
    /******************* MOZ *******************/
    
    var _f_PprArtMozMode = function (d,l) {
//    this._PrepareArtMozMode = function (d,l) {
        //d = Datas; l = lirk -> L'identifiant de la ligne
        /* *
         * ARCHI :
         *      "id" => L'id de l'article
         *      "theme" => Dans quel catégirie a été placé l'article : inml, intr
         *      "time" => La date de création au format TimeStamp GMT
         *      "pic" => La photo liée à l'article
         *      "eval" => Un tableau contenant les données d'évaluations de l'article [-1, cool, cool+, tot]
         *      "react" => Le nombre de commentaires,
         *      "desc" => La 'description' liée à l'article ajoutée.
         *      "author" => Les données liées à l'auteur de l'article : exid, fullname, psd, pic
         *      "aba" : L'identifiant du groupe (Bactch) auquel appartient l'Article
         * * */
        
        try {
            if ( KgbLib_CheckNullity(d) ) { 
                return;
            }
            
            d = Kxlib_ReplaceIfUndefined(d);
            var art = d;
//        alert("YO1 => "+d.art.msg);
//        alert("YO2 => "+Kxlib_EscapeForDataCache(d.art.msg));
//            Kxlib_DebugVars([JSON.stringify(art)],true);
            var theme;
            if (! KgbLib_CheckNullity(art.trd_eid) ) {
                theme = {
                    "k": "intr",
                    "v": "TREND"
                };
            } else {
                theme = {
                    "k": "inml",
                    "v": "MyLIFE"
                };
            }
                    
            art.eval = art.eval.toString().split(",");
            var r = "";
            r += "<div id=\"nf-el-mz-" + art.id + "\" class=\"nwfd-b-moz-mdl-max jb-nwfd-art-mdl jb-unq-bind-art-mdl jb-eval-bind-moz\" data-item=\"" + art.id + "\" data-atype=\""+theme.k+"\" data-aba='"+art.aba+"'";
            r += "data-cache=\"['" + art.id + "','" + art.img + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(art.msg)) + "','" + Kxlib_ReplaceIfUndefined(art.trd_eid) + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(art.trtitle)) + "','" + art.rnb + "','" + art.trhref + "','"+art.prmlk+"'],['" + art.time + "','" + art.utc + "'],['" + art.eval[0] + "','" + art.eval[1] + "','" + art.eval[2] + "','" + art.eval[3] + "','" + "" + "','" + "" + "','" + "" + "','" + "" + "'],['" + art.uid + "','" + art.ufn + "','" + Kxlib_ValidUser(art.upsd) + "','" + art.uppic + "','" + art.uhref + "'],['" + Kxlib_ReplaceIfUndefined(art.me) + "']\" ";
//            r += "data-cache=\"['" + art.id + "','" + art.img + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(art.msg)) + "','" + Kxlib_ReplaceIfUndefined(art.trd_eid) + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(art.trtitle)) + "','" + art.rnb + "','" + art.trhref + "','"+art.prmlk+"'],['" + art.time + "','" + art.utc + "'],['" + art.eval[0] + "','" + art.eval[1] + "','" + art.eval[2] + "','" + art.eval[3] + "','" + art.eval_lt[0] + "','" + art.eval_lt[1] + "','" + art.eval_lt[2] + "','" + art.eval_lt[3] + "'],['" + art.uid + "','" + art.ufn + "','" + Kxlib_ValidUser(art.upsd) + "','" + art.uppic + "','" + art.uhref + "'],['" + Kxlib_ReplaceIfUndefined(art.me) + "']\" ";
            r += ">";
            r += "<span class=\"nwfd-b-moz-mdl-grpdate this_hide\" data-time=\"" + art.time + "\">[Date]</span>";
            r += "<div class=\"nwfd-b-moz-mdl-dome\">";
            r += "<div>";
            r += "<a class=\"nwfd-b-m-mdl-trig\" data-grp=\"" + art.grp + "\" data-grid=\"[" + l + "," + 0 + "]\" data-time=\"" + art.time + "\" data-cache=\"['" + art.uid + "','" + art.ufn + "','" + art.upsd + "','" + art.uppic + "','" + art.uhref + "'],['" + theme.k + "','" + theme.v + "'],['" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(art.msg)) + "'],['" + art.eval[0] + "','" + art.eval[1] + "','" + art.eval[2] + "','" + art.eval[3] + "'],['" + art.rnb + "'],['" + Kxlib_ReplaceIfUndefined(art.trd_eid) + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(art.trtitle)) + "']\" href=\"\">";
            r += "<img class=\"nwfd-b-m-mdl-pic\" width=\"150\" height=\"150\" src=\"" + art.img + "\"/>";
            r += "<span class=\"nwfd-b-m-mdl-pic-fade jb-nwfd-b-m-mdl-p-fd\">";
//            r += "<span class=\"nwfd-b-m-mdl-pic-tr\">Trend</span>";
            r += "</span>";
            r += "</a>";
            r += "</div>";
            r += "<div class=\"nwfd-b-moz-art-specs \">";
            
            r += "<span class=\"nwfd-b-moz-art-ss-react\">";
            r += "<span class=\"jb-unq-react\">" + art.rnb + "</span>";
            r += "<span><i>co</i></span>";
            r += "</span>";
            r += "<span class=\"nwfd-b-moz-art-ss-eval\">";
            r += "<span>" + art.eval[3] + "</span>";
            r += "<span>c<i>!</i></span>";
            r += "</span>";
            r += "</div>";
            r += "<div class=\"this_hide\">";
            r += "<span class='kxlib_tgspy' data-tgs-crd='"+art.time+"' data-tgs-dd-atn='' data-tgs-dd-uut=''>";
            r += "<span class='tgs-frm'></span>";
            r += "<span class='tgs-val'></span>";
            r += "<span class='tgs-uni'></span>";
            r += "</span>";
            r += "</div>";
            r += "</div>";
            r += "</div>";
            r = $.parseHTML(r);
            
            return r;
        } catch (ex) {
//            Kxlib_DebugVars(["2211",ex],true);
        }
    };
    
    var _f_GetMozLnId = function ($fln,g,$mb,pd) {
//    this._GetMozLineId = function ($fln,g,$mb,pd) {
        //fln = La ligne selectionnable par ".nwfd-b-moz-line"; g = Group lié aux éléments à ajouter; mb = MenuBloc, le bloc où tout se passe
        /* Permet pour chaque ajout d'éléments de générer le bon identifiant de la ligne */
        
        /*
         * RULES : 2 principaux cas sont possibles
         * -> Cas 1 : La ligne est neuve (Il n'y a aucun élément)
         *      -> Cas 10 : Il n'y a pas de ligne précédente
         *      -> Cas 11 : Il y a une ligne précédente
         *          -> 110 : La ligne précédente fait partie du même groupe que la ligne "active"
         *          -> 111 : La ligne précédente ne fait pas partie du même groupe que la ligne "active"
         * -> Cas 2 : La ligne n'est pas neuve
         *      -> 20 : La ligne a un identifiant lirk
         *      -> 21 : Cas Critique : La ligne n'a pas d'identifiant lirk 
         *          -> Cas 210 : Il n'y a pas de ligne précédente
         *          -> Cas 211 : Il y a une ligne précédente
         *              -> 2110 : La ligne précédente fait partie du même groupe que la ligne "active"
         *              -> 2111 : La ligne précédente ne fait pas partie du même groupe que la ligne "active"
         * * */
        try {
            var n = $fln.find(".nwfd-b-moz-mdl-max").length;
//        alert("Louche "+n);
            //[NOTE - 11.06.14] Maux de tête en perspective
            if (n) {
//                alert("Cas 2");
//            alert("Louche "+$fln.data("lirk"));
                //CAS 2
                if (!KgbLib_CheckNullity($fln.data("lirk"))) {
//                    alert("Surveille 20");
                    //CAS 20
                    return $fln.data("lirk");
                } else {
                    //CAS 21
                    //Ce cas fait certainement référence à une ligne dans un nouveau processus d'ajout
//                alert("Surveille 21");
                    /* pcd = PreCeDente; il s'agit de la ligne précédent la ligne courante.
                     * Sa compréhension dépend du sens d'ajout ANTERIEUR ou ULTERIEUR
                     */
//                alert("Good start -> "+pd);
                    //*
                    var $pcd;
                    if (pd){
                        $pcd = $($mb).find(".nwfd-b-moz-line:last");}
                    else{
                        $pcd = $($mb).find(".nwfd-b-moz-line:eq(0)");}
                    //*/
//                $pcd = $($mb).find(".nwfd-b-moz-line:eq(0)");
                    //Cas exceptionnelle, la ligne précédente c'est :eq(0) car la ligne actulle n'est pas encore ajoutée
                    if ($pcd.length) {
//                    alert("Surveille 211");
                        
                        //CAS 211
                        //Extraction du groupe de la ligne précedente
                        var foo = $pcd.data("lirk").toString();
                        var bar = foo.indexOf("_");
                        var r = foo.substr(0, bar);
                        //Extraction du rank
                        bar = foo.substr(bar + 1, (foo.length - r.length));
//                    alert("G => "+g+"; r => "+r);
                        if (g === r) {
//                        alert("Surveille 2110");
                            //CAS 2110 : On augmente de 1 la partie après '_'
                            var rk = parseInt(bar) + 1;
                            return g.toString() + '_' + rk;
                        } else {
//                        alert("Surveille 2111");
                            //CAS 2111 : On considère cela comme 0
                            return g.toString() + '_0';
                        }
                    } else {
//                    alert("Surveille 210");
                        //CAS 210: On considère cela comme 0
                        return g.toString() + '_0';
                    }
                }
            } else {
//                alert("Cas 1");
                //Dans le contexte actuel le cas est presque impossible donc, il n'a pas été testé [09-06-14]
                
                //CAS 1 : La ligne est neuve (Il n'y a aucun élément)
//                Kxlib_DebugVars([$mb.find(".nwfd-b-moz-line").length,$mb.find(".nwfd-b-moz-line:eq(0)").data("lirk")], true);
//                        ...
                //En supposant qu'il s'agit d'un ajout, la ligne en cours est eq(0). LA ligne précédente est donc eq(1);
//            if ( $fln.closest("#nwfd-b-moz").find(".nwfd-b-moz-line:eq(1)").length ) { //OLD
                //if ($fln.closest("#nwfd-b-moz-max").find(".nwfd-b-moz-line:eq(1)").length) { //[NOTE 22-07-14 ] A la version actuelle, cela est BIZARRE et FAUX !!!
                /*
                 * En effet l'ancienne méthode testait la ligne précédente. Le problème est que $fln à ce stade ne faisait pas partie du DOM.
                 * Une erreur se produisait donc toujours.
                 */
                //Si au moins une ligne existe on rentre. S'il n'y a qu'une seule ligne, la ligne :first est la ligne précédente
                if ( $mb.find(".nwfd-b-moz-line").length ) {
                    //CAS 11
//                    alert("Cas 11");
                    //pcd = PreCeDente; il s'agit de la ligne précédent la ligne courante
//                var $pcd = $fln.closest("#nwfd-b-moz").find(".nwfd-b-moz-line:eq(1)"); //OLD
//                    var $pcd = $fln.closest("#nwfd-b-moz-max").find(".nwfd-b-moz-line:eq(1)"); //[NOTE 22-07-14 ] OLD 
                    //La ligne précédente est toujours :eq(0). En effet, ici la ligne à ajouter n'existe pas encore dans le DOM. Aussi, elle n'est pas traitée.
                    var $pcd = $mb.find(".nwfd-b-moz-line:eq(0)");
                    //Extraction du groupe de la ligne précedente
                    var foo = $pcd.data("lirk").toString();
                    var bar = foo.indexOf("_");
                    var r = foo.substr(0, bar);
                    //Extraction du rank
                    bar = foo.substr(bar + 1, (foo.length - r.length));
//                    Kxlib_DebugVars([g,r], true);
                    if (g === r) {
//                        Kxlib_DebugVars(["Cas 110"], true);
                        //CAS 110 : On augmente de 1 la partie après '_'
                        var rk = parseInt(bar) + 1;
                        return g.toString() + '_' + rk;
                    } else {
//                        Kxlib_DebugVars(["Cas 111"], true);
                        //CAS 111 : On considère cela comme 0
                        return g.toString() + '_0';
                    }
                } else {
//                    Kxlib_DebugVars(["Cas 10"], true);
                    //CAS 10: On considère cela comme 0
                    return g.toString() + '_0';
                }
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    var _f_FulfilMozLn = function ($fln, flnb, d, mb, pc, pd) {
                
//    this._FulfilMozLine = function ($fln, flnb, d, mb, pc, pd) {
        //fln = FirstLine, d = tableau de données, mb = MenuBloc => Utiliser pour s'assurer que les éléments ne sont pas ajoutés en doublon
        //pc = PerfectCase => Cas où tous les éléments à ajouter suffisent dans une seule ligne. Entraine de devoir afficher la date sur le premier élément
        try {
            var $fln = $fln;
            /*
             * RULES : 
             * - Cette méthode est accessible si et seulement si la ligne n'est pas pleine ou si elle est vide.
             * - Cette dernière se présente lorsque la méthode est appelée par la méthode qui permet de créer des lignes dans NWFD.
             * - Elle ne récupère dans le tableau d'Articles que les Articles qui manquent pour avoir les 4 éléments
             * - Elle revoie un tableau de données amputé des éléments qu'il a utilisés .
             * 
             * RETURN :
             * -> Le tableau de données évoqué plus haut
             * * */
            
            //On check si on a au moins les données. La première ligne on peut facilement la retrouver
            if ( KgbLib_CheckNullity(d) ) { return; }
            
            //Si on a pas accès à la première ligne on la crée. Cela pour éviter des erreurs stupides
            if (KgbLib_CheckNullity($fln)) { $fln = $(".nwfd-b-moz-line:first").find(".group_of_4"); }
            
            /* On ne récupère que le nombre d'Articles qui manque pour 'remplir' la ligne */
            //Calcule du nombre d'éléments à insérer
            var nb = 4 - flnb;
            nb *= -1;
//        alert(nb);
//        return;
            //Copie des éléménts à insérer suivant le nombre précisé ci-dessus
            var tptb = new Array();
            tptb = d.slice(nb).reverse();
            
//            $.grep(d, function(el, ix) {
//                Kxlib_DebugVars([TANT PIS => "+ix]);
//                if (ix < nb) tptb.push(el);
//            });
//        alert(tptb.length);
        
//            Kxlib_DebugVars(["2401",JSON.stringify(tptb)],true);
//            return;
            
            //On récupère l'identifiant de la ligne. Pour le groupe on prend nimporte lequel des éléments et on prend son groupe
            var fooo = _f_GetComposSts();
            fooo = fooo.menu;
            fooo += tptb[0].grp;
//        Kxlib_DebugVars(["OURS => "+$(_f_GetActvMnB()).html()],true);
            var lnid = _f_GetMozLnId($fln.closest(".nwfd-b-moz-line"), fooo, _f_GetActvMnB(), pd);
//         Kxlib_DebugVars([lnid],true);       
            //Dans tous les cas, on inscrit le code LIRK. 
            $fln.closest(".nwfd-b-moz-line").data("lirk", lnid);
            
            /* Dans tous les cas, On met à jour le code LIRK sur la barre */
            var i = "lirk-" + lnid;
            $fln.closest(".nwfd-b-moz-line").find(".nwfd-b-moz-l-bar").attr("id", i);
            
//        alert(lnid);
            
            /* Insertion des éléments */
            $.each(tptb, function(i, v) {
                /* On construit le modèle */
                var b = _f_PprArtMozMode(v, lnid);
                
                //Recréer les bind() JavaScript
                b = _f_PprArtMozMode_Rebind($(b), $fln.closest(".nwfd-b-moz-line"));
//                alert(b);
//                return;
                //On ajoute les blocs dans le body
//                $(b).hide().appendTo($fln).fadeIn(100);
                $(b).hide().prependTo($fln).fadeIn(100);
                
                /* Mettre à jour le module NOoNE */
                _f_Noone();    
                
            });
            
            //[18-16-14] On vérifie si on est dans le cas du perfectCase
            if (pc) {
                _WrtDateOnFirstInMoz($fln);
            }
            
            /* 
             * On revoie le tableau "amputé" des éléments ajoutées à la ligne.
             * Cela permet de passer par un autre mode de traitement de données.
             * Normalement, les données seront utilisées pour créer des lignes 
             */
            //On enlève les éléments
            var trnb = tptb.length * -1;
            d.splice(trnb);
//            d.splice(0, tptb.length);
            
            return [d, $fln];
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }

    };
    
    var _WrtDateOnFirstInMoz = function ($ln,pd) {
//    this._WriteDateOnFirstInMoz = function ($ln,pd) {
        /*
         * Modifie la vue pour permettre l'affichage de la date au niveau de la première ligne représentant une date
         */
        
        //On change les caractéristiques de marge de la ligne
        $($ln).addClass("withdate");
        
        /* On affiche la date au niveau du premier élément */
        var $el = $($ln).find(".nwfd-b-moz-mdl-max:first").find(".nwfd-b-moz-mdl-grpdate");
        //On récupère TIME
        var t = $el.data("time");
        //On init la date
        var dt = new KxDate(t);
        dt.SetUTC(true);
        //On insere la date
        $el.html(dt.WriteDate());
        //On montre l'élément
        $el.removeClass("this_hide");
        
        return $el;
    };
    
    var _f_CrtMozLn = function () {
//    this._CreateMozLine = function () {
        
        var e = "";
        e += "<div class=\"nwfd-b-moz-line\" data-lirk=\"\">";
        e += "<div class=\"nwfd-b-moz-l-list\">";
        e += "<div class=\"group_of_4\">";
        e += "</div>";
        e += "<div id=\"\" class=\"nwfd-b-moz-l-bar jsbind-b-moz-l-bar\">";
        e += "<div class=\"nwfd-b-mz-l-b-specs\">";
        e += "<div class=\"nwfd-b-mz-l-b-s-specs\">";
        e += "<span class=\"nwfd-b-mz-l-b-s-s-eval\">";
        e += "<span class=\"nwfd-moz-eval-val\">0</span>";
        e += "<spanclass=\"nwfd-moz-eval-lg\">c!</span>";
        e += "</span>";
        e += "<span class=\"nwfd-b-mz-l-b-s-s-react\">";
        e += "<span class=\"nwfd-moz-react-val\">0</span>";
        e += "<span class=\"nwfd-moz-r-lg\"></span>";
        e += "</span>";
        e += "</div>";
        e += "<div class=\"nwfd-b-mz-l-b-s-user\">";
        e += "<a class=\"nwfd-b-mz-l-b-s-u-u\" href=\"[UHREF]\">";
        e += "<img class=\"nwfd-moz-user-img\" height=\"35\" width=\"35\" src=\"[UPPIC]\"/>";
        e += "<span class=\"nwfd-moz-user-psd\">[UPSD]</span>";
        e += "</a>";
        e += "<a class=\"this_hide\" datat-tr=\"Lorem ipsum dolor sit amet, consectetur adipiscing volutpat.\" href=\"\">Trend</a>";
        e += "<span class=\'css-tgpsy-nwfd kxlib_tgspy\' data-tgs-crd=\'\' data-tgs-dd-atn=\'\' data-tgs-dd-uut=\'\'>";
        e += "<span class=\'tgs-frm\'></span>";
        e += "<span class=\'tgs-val\'></span>";
        e += "<span class=\'tgs-uni\'></span>";
        e += "</span>";
        e += "</div>";
        e += "</div>";
        e += "</div>";
        e += "</div>";
        e += "</div>";
        
        try {
            e = $.parseHTML(e);
            
            return $(e);
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    var _f_PprArtMozMode_Rebind = function (a,l) {
//    this._PrepareArtMozMode_BindHandler = function (a,l) {
        //a = L'élément individuel dans la grille MOZ; l = la liste pour rechercher la barre
        if ( KgbLib_CheckNullity(a) ) { return; }
       
        //Bind pour affihcer la MozBar
        $(a).find(".nwfd-b-m-mdl-trig").hover(_f_ShwMozBar);
        //Bind pour lock la barre lorsque la souris est au-dessus
        $(l).find(".jsbind-b-moz-l-bar").on('mouseleave',_f_HoldMozBar);
        //Bind pour UNQ
        $(a).find(".nwfd-b-m-mdl-trig").click(function(e){
            Kxlib_PreventDefault(e);
            
           (new Unique()).OnOpen("nwfd",this);
        });
             
        return a;
    };
    
    this._PrepareMozBarFromAnim = function () {
        // Permet de donner à la barre d'etre cibler pour faire marcher l'animation
    };
    
    var _f_CrtNdFulfilMozLn = function ($fln, flnb, d, mb, pd, rcs) {
//    this._CreateNdFulfilMozLine = function ($fln, flnb, d, mb, pd, rcs) {
        //pd = PreDate; rcs = Recusive => Sert surtout pour le chargement des éléments antérieurs
        if ( KgbLib_CheckNullity(d) ) { return; }
                
        try {
            //nd = NewDatas  
            var nd = d;  
            
            //Si la dernière ligne n'est pas Full on la Full
            /* 
             * RAPPEL : 
             * On arrive dans cette méthode si : 
             * (1) La dernière ligne est full
             * (2) La dernière ligne n'est pas Full MAIS que l'ajout des nouveaux éléments induit la création de nouvelles lignes.
             * (3) Il n'y a aucun élément de disponible (flnb === 0). 
             * */
            if (flnb > 0 && flnb < 4) {
//            alert("Check !");
                //On vérifie si on nous a envoyé la dernière ligne. Sinon, on sort
                if (! KgbLib_CheckNullity($fln) ) {
                    var r0 = _f_FulfilMozLn($fln, flnb, d, mb, pd);
//                    alert(2546);
                    nd = r0[0];
                }
                else { 
                    //TODO : Récupérer le dernière ligne et continuer
                    return;
                }
            }
            
            /* Création de la nouvelle ligne */
            var $ln;
            try {
                $ln = $(_f_CrtMozLn());
            } catch (ex) {
                //TODO: Send error to Server
//            Kxlib_DebugVars(["In newsfeed_handler:1216 ",ex],true);
                return;
            }
            
            /* Insertion des données dans la nouvelle ligne. On récupère le reste des éléments à charger */
            var r1 = _f_FulfilMozLn($ln.find(".group_of_4"), 0, nd, mb, null, pd);
            nd = r1[0];
            
            /* **Insertion de la nouvelle ligne dans NewsFeed**  */
            //Ajout de la ligne parmis les autres
            var bar = mb;
            /*
             alert(bar.html());
             return;
             //*/
            var $fst = $(bar).find(".nwfd-b-moz-line:first");
            var $lst = $(bar).find(".nwfd-b-moz-line:last");
            if ($fst.length) {
                if (!pd) {
                    $($ln).hide().insertBefore($fst).fadeIn(1); 
                } else { 
                    $($ln).hide().insertAfter($lst).fadeIn(1);
                }
            } else {
//                alert($(bar).html());
                $($ln).hide().insertBefore(".jb-nwfd-b-list-bttf[data-scp=moz]").fadeIn(1);
//                $($ln).hide().appendTo(bar).fadeIn(1);
            } 
            
            //Dans le cas d'un ajout en mode PreDate, On fait apparaitre la date sur le premier élément ajouté du groupe
            if ( pd && (rcs === false || typeof rcs === "undefined") ) {
                _WrtDateOnFirstInMoz($ln); 
            }
            
            /* Mettre à jour le module NOoNE au cas où on avait aucun article */
            _f_Noone();   
//        return;
            //On vérifie s'il reste encore des éléments succeptibles d'être ajoutés
            if (nd.length > 0) {
                _f_CrtNdFulfilMozLn(null, 0, nd, mb, pd, true);
            } else {
                /* On affiche la date au niveau du premier élément si nous ne sommes pas dans le cas d'un PreDate */
                if (!pd) {
                    _WrtDateOnFirstInMoz($ln);
                }
                
                /* On fait de telle sorte que la ou les barres soit accessibles sans quoi l'animation ne marcherait pas */
                //this._PrepareMozBarFromAnim($el.data("time"));
                
                //return;
                //Fin de la recusivité. Toda pasais bien (semblant d'espagnol)
                return 1;
            }
        } catch (ex) {
//            Kxlib_DebugVars(["2780",ex],true);
        }

    };
    
    var _f_SortOneDayAGrp = function (mb,d,pd) {
//        Kxlib_DebugVars(["DATAS => ",JSON.stringify(d)],true);
//                return;
//    this._SortOneDayAGroup = function (mb,d) {
        try {
            
            /* 
             * RAPPEL : Les données arrivent sous forme d'une liste d'objets. Il ne sont pas rangés dans des sous-tableaux.
             * 
             * RULES :
             * - La méthode revoie un tableau contenant des tableaux dont chacun représente une date (jour)
             * - Les tableaux sont triés de telle sorte que la date la moins récente soit ait l'index le plus faible
             * - Si tous les éléments ne correspondent qu'à une seule journée, la méthode ne renverra qu'un tableau
             * - Chaque tableau a un id de 'type' date. Exemple, les éléments pour la journée de 5 Juin 14 aura l'id (050614)
             * 
             * PROTOCOLE avec SERVER : 
             * - Les éléménts sont rangés et groupés par date. Des plus récents au plus anciens.
             * - Ce qui fait que "normalement", ils le resteront au niveau de l'objet renvoyé. ( A tester, 09-06-14) 
             * */
            if ( KgbLib_CheckNullity(d) ) { 
                return; 
            }
            
//            Kxlib_DebugVars(["DATAS => ",JSON.Stringify(d)],true);
//                return;
            
            var r = new Object();
//         alert(Kxlib_ObjectChild_Count(d));
//         alert(typeof d);
//            return;
            $.each(d, function(x,v) {
                var art = v;
//                alert(v.art.time);
//                return;
                //On s'assure que l'élément n'existe pas déjà. Sinon on ne l'ajout pas 
//             alert(v.time);
//             return;
                if ( _f_ChkEltNoExsitsInMoz(mb,art) ) { return; }
                
                /* Création de l'id à partir de la date de création fournie avec l'élément */
                
                /*
                 * [NOTE au  09-06-14] @Lou
                 * Utliser la date comme id unique, est une solution erronée car le trie engendrera des problèmes.
                 * Il vaut mieux utiliser le timestamp comme pivot.
                 */
                
                /*
                 var t = parseInt(v.time);
                 var dt = new KxDate(t);
                 
                 var id = dt.getDate().toString();
                 id += dt.getMonth().toString();
                 id += dt.getYear().toString();
                 //*/
                
                //var id = v.time; //Non car la partie Time fait que pour la même journée, on a deux blocs s'ils ont des TIME (hours, mins ou secs) différents
                var tpd = new KxDate(parseInt(art.time));
                tpd.SetUTC(true);
                var id = tpd.RemoveTimeFromTSTP2();
                        
//                Kxlib_DebugVars(["Check ID => ",id],true);
                
                //On ajoute l'élément grp qui permettra de les identifier lors de leur insertion
                art.grp = id;
                
                //Si un tableau du jour correspondant à celle de l'élément existe, on ajoute à ce tableau ...
                if (r.hasOwnProperty(id)) {
                    r[id].push(art);
//                 alert("Pour "+ix+" avec "+id+" => Push");
                } else {
                    //... Sinon on crée un nouveau tableau et on l'insère à l'objet
                    var nt = new Array();
                    nt.push(art);
                    r[id] = nt;
//                 alert("Pour "+ix+" avec "+id+" => New");
                } 
            });
            
            /*
             * On va placer les éléments de telle sorte qu'ils soient triés par ordre décroissant.
             * 
             * [NOTE 22-03-15] @BlackOwlRobot
             * Le trie doit dépendre aussi de la nature de la requete. Si la requête est de type PD, on trie par ordre décroissant.
             */
            var nms = new Array();
            if ( KgbLib_CheckNullity(pd) ) {
                nms = Object.getOwnPropertyNames(r).sort(function(a,b) {
                    return a - b;
                });
            } else {
                nms = Object.getOwnPropertyNames(r).sort(function(a,b) {
                    return b - a;
                });
            }
            
            
            /*
             //DEBUG : On verifie qu'on a bien un tableau trié
             $.each(nms, function(i,v){
                alert(v); 
             });
             return;
             //*/
            
//        alert(Kxlib_ObjectChild_Count(r));
            
            //On renvoie un tableau contenant la liste triée des des valeurs (index : 0) ...
            // ... Ainsi que le tableau contenant les données
            var rt = [nms, r];
            
            return rt;
            
        } catch (ex) {
//            Kxlib_DebugVars(["2887",ex],true);
        }

    };
    
    /*
    var _f_DisplayMozMode = function (d,mb,rcs,pd) {
//    this.DisplayDatasMozMode = function (d,mb,rcs,pd) {
        try {
            
            // Il faut s'accrocher, maux de têtes assurés
                                                                                                                                    
            //rcs  = Recursive. Cette méthode peut être appelée de façon recursive        
            //d = tableau d'objets de datas; rcs = recursive, permet à la méthode de comprendre que le Caller c'est lui même
            // pd = Predate => Est ce qu'on veut afficher les anciens résultats
            if ( KgbLib_CheckNullity(d) ) { 
                /*
                 * [AJOUTE 23-03-15] @BlackOwlRobot
                 * Permet de trier les éléments dans la grille pour régler le problème du bogue du à un nombre limit trop petit.
                 * Ce bogue cause d'autres bogues qui ne permettent pas le fonctionnement normale des mécanismes de mise à jour.
                 
                if ( KgbLib_CheckNullity(rcs) ) {
                    _f_SortDisor(['moz',mb]);
                } else {
                    return; 
                }
            }
            
            //y = Correspond au jour lié aux éléments. C'est aussi le numéro de ligne avant changement
            //gr = GlobalReturn = Utiliser pour envoyer les données à la prochaine méthode
            var y, gr;
            if ( KgbLib_CheckNullity(rcs) ) {
                
                /* On va trier le tableau de données pour permettre qu'à chaque ligne corresponde un jour 
                gr = _f_SortOneDayAGrp(mb,d,pd);
                    
                //On récupère les deux tableaux (1) les indices triés (2) les données des Articles
                var nms = gr[0], datas = gr[1]; 
                
                //On sélectionne le premier élément pour lancer la procédure
                var y = nms[0];
                d = datas[y];
                
            } else {
                
                /* On supprime l'élément déjà traité de telle sorte que le prochain process traite un nouvel élément 
                var n2 = d[0];
                //Rappel d2 un objet et non un tableau
                var d2 = d[1];
                var y = n2[0];
                
                //On supprime l'élément déjà traité ET son indice
                delete d2[y];
                n2.splice(0, 1);
                
//            alert("Tab. val. => "+Kxlib_ObjectChild_Count(d2)+"; Tab ind. => "+n2.length);
//            alert("Tab. val. => "+Kxlib_ObjectChild_Count(d2)+"; Tab ind. => "+n2[0]);
//            return;
                // ... S'il n'y a plus de tableau on stop le process de recursivité
                if ( Kxlib_ObjectChild_Count(d2) > 0 && n2.length > 0 ) {
                    //On recupère le nouvel élément à traiter
                    y = n2[0];
                    
                    //On reconstruit la variable 'gr' sinon on aura un BUG pour l'après 2ème passage
                    gr = d;
                    
                    //On récupère la donnée à traiter
                    d = d2[y];
                    
                } else {
                    _f_SortDisor(['moz',mb]);
                    return;
                }
            }
            
            /* Selon le cas : La dernière ligne n'est pas pleine OU l'est 
            var fo = mb;
            var $fln;
            if ( KgbLib_CheckNullity(fo) ){
                return;
            } else {
                $fln = ( KgbLib_CheckNullity(pd) ) ? $(fo).find(".nwfd-b-moz-line:first").find(".group_of_4") : $(fo).find(".nwfd-b-moz-line:last").find(".group_of_4");
//                $fln = $(fo).find(".nwfd-b-moz-line:first").find(".group_of_4");
            }
            
            //isf = IsFull, (false) ce qui signifie que la dernière ligne n'a pas les 4 éléments pour être pleine
            var isf = false, flnb = $fln.find(".nwfd-b-moz-mdl-max").length;
            
            //DEBUG
            /*
             alert($fln.html());
             alert($(".nwfd-b-moz-line:eq(0)").find(".nwfd-b-m-mdl-trig").length);
             return;
             //
            
            //Vérification si la dernière est pleine
            isf = ( flnb === 4 || flnb === 0 ) ? true : false;
            /* Lorsque flnb === 0 cela signifie qu'il n'y a aucun élément disponible 
//        alert("Et ça continue => "+isf);
            
            //On verifie si les éléments de la dernière ligne ont la même date que ceux à ajouter
            //frnbd = FoRceNewByDate; Signifie qu'il faut obliger à la création d'une nouvelle ligne à cause de la différence de date
//        var frnbd = true;
            if (! KgbLib_CheckNullity($fln) ) {
                
                var frnbd; 
                if ( KgbLib_CheckNullity(pd) ) {
                    frnbd = ($fln.find(".nwfd-b-m-mdl-trig:first").data("grp").toString() === y) ? false : true;
                } else {
                    frnbd = ($fln.find(".nwfd-b-m-mdl-trig:last").data("grp").toString() === y) ? false : true;
                }
                
//                var frnbd = ($fln.find(".nwfd-b-m-mdl-trig:first").data("grp").toString() === y) ? false : true; 
            }
            
//        alert("Derniere ligne => "+$fln.find(".nwfd-b-m-mdl-trig:first").data("grp")+"; Ligne à ajouter => "+y);
//        alert("Freely => "+frnbd);
//        Kxlib_DebugVars([isf,frnbd],true);
            if ( !isf && !frnbd ) {
//            alert(isf);
                //Vérification si on est dans le cas parfait où on veut ajouter des éléments sans devoir créer une nouvelle ligne
                if ( ( Kxlib_ObjectChild_Count(d) + flnb ) <= 4 ) {
//                alert("Check In -> "+d.length);
                    /* On ajoute les éléments à la ligne 
                    
                    /* Si on est ici c'est parce qu'on a fill une ligne dans un cas parfait.
                     * Cependant, il ne faut pas oublier d'afficher la date sur le premier élément de cette ligne.
                     * Voilà pourquoi on signale à la méthode que nous sommes dans ce cas 5eme arg (true)
                     /* * 
                    var fo = _f_FulfilMozLn($fln, flnb, d, mb, true);
//                alert("Check Out -> "+ak[0].length);
                } else {
                    //On demande la création recursive des lignes tant qu'on a des éléments à ajouter  
                    _f_CrtNdFulfilMozLn($fln, flnb, d, mb, pd);
                }
            } else {
//                alert("Cas de la Nouvelle Ligne");
//               return;
                if ( frnbd | KgbLib_CheckNullity($fln) ) {
//                alert("Test");
                    //On demande la création recursive des lignes tant qu'on a des éléments à ajouter.
                    //Ici on force l'apparition d'une nouvelle ligne
                    _f_CrtNdFulfilMozLn(null, 0, d, mb, pd);
                } else {
                    //On demande la création recursive des lignes tant qu'on a des éléments à ajouter  
                    _f_CrtNdFulfilMozLn($fln, flnb, d, mb, pd);
                }
            }
            
            //On relance le processus
            _f_DisplayMozMode(gr, mb, true, pd);
        } catch (ex) {
//            Kxlib_DebugVars(["3018",ex],true);
        }

    };
    //*/
    
    /************************ NWFD SLIDER *************************/
    
    var _f_ShNwPostTrgPan = function (b,n) {
//    this.NewPostTrigPan_Show = function (n) {
        try {
            if ( KgbLib_CheckNullity(b) | KgbLib_CheckNullity(n) ) {
                return;
            }
            
                
            /*
             * Permet d'afficher le Trigger.
             * La méthode recoit un nombre représentant le nombre d'Articles en "attente".
             * 
             * Si le trigger est déjà affiché, on update son nombre.
             */
    //        alert("2728 => "+$(".jb-nwfd-nptp-m").hasClass("this_hide"));
            //On vérifie se le trigger est affiché
            var mn = $(b).data("scp"), aba;
            /*
            switch (mn) {
                case "comy" :
                        aba = "iml_frd";
                    break;
                case "iml_pod" :
                        aba = "iml_pod";
                    break;
                case "itr" :
                        aba = "itr";
                    break;
                default :
                    return;
            }
            //*/
            if ( $.inArray(mn,["comy","iml_pod","itr"]) === -1 ) {
                return;
            }
            
            /*
             * [DEPUIS 09-05-16]
             *      Dans le cas où le nombre est 0
             */
            if (! n ) {
                $(".jb-nwfd-nptp-m[data-scp='"+aba+"']").addClass("this_hide");
                $(".jb-nwfd-nptp-m[data-scp='"+aba+"']").find(".jb-n-n-c-nb > span").text("");
                return true;
            }
            
            /*
             * ETAPE :
             *      On fait apparaitre le TRIGGER avec le bon chiffre
             */
            if ( $(".jb-nwfd-nptp-m[data-scp='"+mn+"']").hasClass("this_hide") ) {
                $(".jb-nwfd-nptp-m[data-scp='"+mn+"']").find(".jb-n-n-c-nb > span").text(n);
                $(".jb-nwfd-nptp-m[data-scp='"+mn+"']").removeClass("this_hide");
            } else {
                $(".jb-nwfd-nptp-m[data-scp='"+mn+"']").find(".jb-n-n-c-nb > span").text(n);
            }
            
            /*
             * [ETAPE 10-05-16]
             *      On affiche l'information au niveau de SNITCHER_NEWFEED
             */
            _f_Snitcher_SetNotif(mn,n);
            
            return true;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    var _f_KlNwPostTrgPan = function (b) {
//    this.NewPostTrigPan_Kill = function () {
        try {
            if ( KgbLib_CheckNullity(b) ) {
                return;
            }
            
            var mn = $(b).data("scp"), aba;
            /*
            switch (mn) {
                case "comy" :
                        aba = "iml_frd";
                    break;
                case "iml_pod" :
                        aba = "iml_pod";
                    break;
                case "itr" :
                        aba = "itr";
                    break;
                default :
                    return;
            }
            //*/
            
            if ( $.inArray(mn,["comy","iml_pod","itr"]) === -1 ) {
                return;
            }
            
           /*
            * Permet de masquer le trigger et de le reset.
            * Cela signifie qu'il est remis à son état d'origine afin d'être disponible pour de futures actions.
            */
           $(".jb-nwfd-nptp-m[data-scp='"+mn+"']").addClass("this_hide");
   //        alert($(".jb-nwfd-nptp-m").attr("style"));
           $(".jb-nwfd-nptp-m[data-scp='"+mn+"']").removeAttr("style");
   //        alert($(".jb-nwfd-nptp-m").attr("style"));
           $(".jb-nwfd-nptp-m[data-scp='"+mn+"']").addClass("zmout");

           $(".jb-nwfd-nptp-m[data-scp='"+mn+"']").find(".jb-n-n-c-nb > span").text(0);

           $(".jb-nwfd-nptp-m[data-scp='"+mn+"']").find(".bfmille").addClass("this_hide");
           $(".jb-nwfd-nptp-m[data-scp='"+mn+"']").find(".jb-n-n-c-lib").addClass("this_hide");
           
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    
    var _f_Snitcher_SetNotif = function (sc,fig) {
        try {
            if ( KgbLib_CheckNullity(sc) ) {
                return;
            }
            
            var atg;
            switch (sc) {
                case "lasta" :
                        atg = $(".jb-tqr-hl-n-n-tgr[data-scp='nwfd-lasta']");
                    break;
                case "comy" :
                        atg = $(".jb-tqr-hl-n-n-tgr[data-scp='nwfd-iml-fa']");
                    break;
                case "iml_pod" :
                        atg = $(".jb-tqr-hl-n-n-tgr[data-scp='nwfd-pod']");
                    break;
                case "itr" :
                        atg = $(".jb-tqr-hl-n-n-tgr[data-scp='nwfd-itr']");
                    break
                default :
                    return;
            }
            
            if (! $(atg).length ) {
                return;
            }
            
            if ( !fig || fig < 0 ) {
                $(atg).find(".figure").text(0);
                $(atg).find(".figure").removeClass("pending");
            } else {
                $(atg).find(".figure").text(fig);
                $(atg).find(".figure").addClass("pending");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    
            
    var _f_NewPostTrigPanZmIn = function (scp) {
//    this.NewPostTrigPanZmIn = function () {
        
        if ( KgbLib_CheckNullity(scp) ) {
            return;
        }
        
        var aba;
        switch (scp) {
            case "iml_frd" :
            case "iml_pod" :
            case "itr" :
                    aba = scp;
                break;
            default :
                return;
        }
        
        if ( $(".jb-nwfd-nptp-m[data-scp='"+aba+"']").data("lk") === 1 ) {
            return;
        }
        
        //On annule les animations
        $(".jb-nwfd-nptp-m[data-scp='"+aba+"']").stop(true,true);
        
        //On fait "disparaitre" le ou les composants interieurs
        $(".jb-n-n-c-nb").addClass("this_hide");
        
        //Faire passer l'ensemble à ZmIn
        $(".jb-nwfd-nptp-m[data-scp='"+aba+"']").removeClass("zmout",100);
        
        //*** On change les éléments de ZO à ZI ***//
        
        //On masque <sup>
        $(".jb-n-n-c-nb").find("sup").addClass("this_hide");
        //On change la taille de la police
        $(".jb-n-n-c-nb").removeClass("zmout");
        
        //*** On affiche les éléments en FadeIn ***//
        
        //On affiche le nombre de publications
        $(".jb-n-n-c-nb").hide().removeClass("this_hide").fadeIn();
        //On affiche le libellé 
        $(".jb-n-n-c-lib").hide().removeClass("this_hide").fadeIn();
        //On affiche l'image des "..."
        $(".jb-nwfd-nptp-dvlp").hide().removeClass("this_hide").fadeIn();
        
    };
            
    var _f_NewPostTrigPanZmOut = function (scp) {
//    this.NewPostTrigPanZmOut = function () {
        
        if ( KgbLib_CheckNullity(scp) ) {
            return;
        }
        
        var aba;
        switch (scp) {
            case "iml_frd" :
            case "iml_pod" :
            case "itr" :
                    aba = scp;
                break;
            default :
                return;
        }
        
        if ( $(".jb-nwfd-nptp-m[data-scp='"+aba+"']").data("lk") === 1 ) {
            return;
        }
        
        //On annule les animations
        $(".jb-nwfd-nptp-m[data-scp='"+aba+"']").stop(true,true);
        
        //On fait "disparaitre" le ou les composants interieurs (le temps de l'animation)
        $(".jb-n-n-c-nb").addClass("this_hide");
        $(".jb-n-n-c-lib").addClass("this_hide");
        
        //Faire passer l'ensemble à ZmOut
        $(".jb-nwfd-nptp-m[data-scp='"+aba+"']").addClass("zmout",100);
        
        //*** On change les éléments de ZI à ZO ***//
        
        //On masque <sup>
        $(".jb-n-n-c-nb").find("sup").removeClass("this_hide");
        //On change la taille de la police
        $(".jb-n-n-c-nb").addClass("zmout");
        
        //*** On affiche les éléments en FadeIn ***//
        
        //On affiche le nombre de publications
        $(".jb-n-n-c-nb").hide().removeClass("this_hide").fadeIn();
        //On affiche le libellé 
//        $(".jb-n-n-c-lib").hide().removeClass("this_hide").fadeIn();
        //On affiche l'image des "..."
//        $(".jb-nwfd-nptp-dvlp").hide().removeClass("this_hide").fadeIn();
    };
    
    var _f_UpdNwfdSidePan = function () {
//    this._UpdateNwfdSidePanel = function () {
        var h = $(".jb-nwfd-body").height() - $(".jb-nwfd-sld-hdr").height() - $(".jb-nwfd-sld-ftr").height() - 4;
        
        //On affecte la taille au body
        $(".jb-nwfd-sld-body").height(h);
    };
    
    var _f_ShwNwfdSidePan = function (x) {
//    this.ShowNwfdSidePanel = function (x) {
        try {
            
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            //Cela permet de stopper l'animation en cours
            $(".jb-nwfd-body").stop(true, true);
            
            /*
             * Permet de faire apparaitre la zone de droite.
             */
            //On fige le déclencheur sur le statut actuel. Si tout se déroule normalement, il s'agit du cas où le déclencheur est développé
            $(".jb-nwfd-nptp-m").data("lk", 1);
            
            //On calcule la taille de la zone principale
            _f_UpdNwfdSidePan();
            
            /*
             * On masque certaines zones interieures de la zone pour les faire apparaitre en fadeIn par la suite.
             * Cela permet aussi de procéder au calcul de la hauteur de la zone principale en fonction des écrans.
             *
             //On masque la zone les éléments de la zone principale
             $(".jb-nwfd-sld-ctr").addClass("this_hide");
             //*/
            //On fait slider la zone cnetrale pour faire apparaitre zone d'infos
            $(".jb-nwfd-body").toggleClass("slided", 500).promise().done(function() {
                //On signale la zone commme ouverte
                $(".jb-nwfd-slided-max").data("sts", 1);
            });
            
            //*** On change le data-action ***//
            var rv = $(x).data("rev"), nw = $(x).data("action");
            
            $(x).data("action", rv);
            $(x).data("rev", nw);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }

    };
    
    var _f_HdNwfdSidePan = function (x) {
//    this.HideNwfdSidePanel = function (x) {
        if ( KgbLib_CheckNullity(x) ) { return; }
        
        //Cela permet de stopper l'animation en cours
        $(".jb-nwfd-body").stop(true,true);
        
        //On fait slider la zone cnetrale pour faire apparaitre zone d'infos
        $(".jb-nwfd-body").toggleClass("slided",500).promise().done(function() {
            //On dézoom le Trigger
            _f_NewPostTrigPanZmOut();
            //On signale la zone commme close
            $(".jb-nwfd-slided-max").data("sts",0);
        });
        
        //*** On change le data-action ***//
        var rv = $(x).data("rev"), nw = $(x).data("action");
        
        $(x).data("action",rv);
        $(x).data("rev", nw);
        
        //On "defige" le déclencheur sur le statut actuel. Si tout se déroule normalement, il s'agit du cas où le déclencheur n'est pas développé
        $(".jb-nwfd-nptp-m").data("lk",0);
    };
    
    var _f_NwfdSlidedNavUp = function () {
//    this.NwfdSlidedNavUp = function () {
        try {
            
            /*
             * On simule la sensation de passer de pages en pages, on change le top avec une valeur égale à la hauteur actuelle de la zone.
             * Dans ce cas on monte
             */
            //On annule toutes les autres animations
            $(".jb-nwfd-sld-bd-list").stop(true, true);
            
            var $w = $(".jb-nwfd-sld-bd-list");
            
            //On récupère la taille de la zone principale
            var h = $(".jb-nwfd-sld-body").outerHeight();
            
            var dn;
            
            if ($w.position().top === 0 || $w.position().top > 0) {
                dn = 0;
            } else {
                dn = $w.position().top + h;
                
                //On va éviter que la fenetre ne monte trop loin
                if (dn > 0) {
                    dn = 0;
                }
            }
            
            //On slide pour déscendre
            $(".jb-nwfd-sld-bd-list").animate({
                "top": dn
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }

    };
    
    var _f_NwfdSlidedNavDown = function () {
//    this.NwfdSlidedNavDown = function () {
        try {
           /*
            * On simule la sensation de passer de pages en pages, on change le top avec une valeur égale à la hauteur actuelle de la zone.
            * Dans ce cas on descend
            */
           /*
           //On annule toutes les autres animations
           $(".jb-nwfd-sld-bd-list").stop(true,true);
            */
           
           var $w = $(".jb-nwfd-sld-bd-list");

           //On récupère la taille de la zone principale
           var h = $(".jb-nwfd-sld-body").outerHeight();
           
           /*
            * [DEPUIS 05-07-15] @BOR
            * L'ancien code n'était pas optimal
            */
           var  ps = $w.position().top*(-1);
           if (! $w.outerHeight() ) {
               return;
           } else if ( $w.outerHeight() <= h ) {
               return;
           } else if ( ps === 0 && $w.outerHeight() <= h ) {
               dn = h;
               Kxlib_DebugVars([4075,dn]);
           } else {
               var r__ = $w.outerHeight() - ps;
               if ( r__ <= h ) {
                   return;
               } else if ( r__ > (h+60) ) {
                   /*
                    * [NOTE 05-07-15] @BOR
                    * 60px correspond à la OutterHeight approximative d'un modèle.
                    */
                   dn = ps + h;
                   Kxlib_DebugVars([4086,dn]);
               } else {
                   /*
                    * Ce cas correspond à celui où on ne descend pas totalement
                    */
                   dn = ( $w.outerHeight() - (ps + h) ) + ps;
                   Kxlib_DebugVars([44092,dn]);
               }
           }
           dn *= -1;
           
           /*
           var dn;
           if ( $w.position().top === 0 || $w.position().top > 0 ) {
               dn = h * (-1);
           } else {
               dn = $w.position().top - h;

               //On va éviter que la fenetre descende trop loin
               var lh = $(".jb-nwfd-sld-bd-list").outerHeight() ;

               var v = lh - h;
               v *= -1;

               if ( dn <= v ) {
                   dn = lh - h;
                   dn *= -1;
               }
           }
            //*/

           //On slide pour déscendre
           $(".jb-nwfd-sld-bd-list").stop(true,true).animate({
               "top": dn
           },800);
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    var _f_NwfdSlidedNav1st = function () {
//    this.NwfdSlidedNavFirst = function () {
        $(".jb-nwfd-sld-bd-list").animate({
            "top": 0
        });
    };
    
    var _f_NwfdSld_CrtList = function (d) {
//    this.NwfdSlide_CreateList = function (d) {
        /*
         * Permet de créer une liste comprennat les utilisateurs liés aux Articles à afficher.
         * La liste est faite à partir des données reçues (d).
         */
        if ( KgbLib_CheckNullity(d) ) {
            return;
        }
        
        //On vide la liste
        $(".jb-nwfd-sld-bd-list").html("");
            
        /*
         * [DEPUIS 07-05-16]
         *      Prise en compte du mode "Multi-Canal"
         */
        $.each(d,function (aba,ds) {
            $.each(ds,function (i,v) {
                //On crée la vue de l'élément
                var e = _f__NwfdSlide_CL_PprSngl(v);
    //            Kxlib_DebugVars([e],true);

                //On ajoute l'élément. RAPPEL : C'est à CALLER de mettre en place un tri, si tri il  y a.
//                $(".jb-nwfd-sld-bd-list").append(e);
                $(".jb-nwfd-sld-bd-l-bysc[data-scp='"+aba+"'").append(e);
            });
        });
        
        
//        Kxlib_DebugVars([$(".jb-nwfd-sld-bd-list").html()],true);
        
        return true;
    };
    
    var _f__NwfdSlide_CL_PprSngl = function (d) {
//    this._NwfdSlide_CL_PrepareSingle = function (d) {
        //ap = ArticelPivot; n : le nombre de publications liées
        
        if ( KgbLib_CheckNullity(d) ) {
            return;
        }
        
        var e = "<div class=\"nwfd-sld-mdl-max jb-nwfd-sld-mdl\" data-item=\"\">";
        e += "<div class=\"nwfd-sld-mdl-lz\">";
        e += "<a class=\"nwfd-sld-mdl-lz-ugrp\" >";
//        e += "<a class=\"nwfd-sld-mdl-lz-ugrp\" href=\"/%uhref%/\">";
        e += "<img class=\"nwfd-sld-mdl-lz-ug-img\" width=\"45\" height=\"45\" src=\"/%uppic%/\" />";
        e += "<span class=\"nwfd-sld-mdl-lz-ug-psd\">/%upsd%/</span>";
        e += "</a>";
        e += "</div>";
        e += "<div class=\"nwfd-sld-mdl-rz\">";
        e += "<span class=\"nwfd-sld-mdl-rz-pnb\">/%pnb%/</span>";
        e += "</div>";
        e += "</div>";
        
        e = $.parseHTML(e);
        
        $(e).data("item",d.oeid);
        /*
         * [DEPUIS 05-05-15] @BOR
         */
//        $(e).find(".nwfd-sld-mdl-lz-ugrp").attr("href",d.uhref);
        $(e).find(".nwfd-sld-mdl-lz-ug-img").attr("src",d.uppic);
        $(e).find(".nwfd-sld-mdl-lz-ug-psd").text(d.upsd);
        $(e).find(".nwfd-sld-mdl-rz-pnb").text(d.pnb);
        
        return e;
        
    };
    
    /******************************************************************************************************************************************/
    /*************************************************************** AUTO SCOPE ***************************************************************/
    /******************************************************************************************************************************************/
    setInterval(function(){
        _f_ChkNwrArt();
    },_f_Gdf().__NWFD_CNA_ROT);
    
    /*******************************************************************************************************************************************/
    /************************************************************* LISTENERS SCOPE *************************************************************/
    /*******************************************************************************************************************************************/
    
    //Obligatoire
    _f_Init();
//    _f_Init(true);
    
    /***************** HEADER ***************/
    $(".jb-nwfd-hdr-lnmr-trgr").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_RdcHdr();
    });
    
    /***************** VIEW *****************/
    
    $(".jb-nwfd-view-choice").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_SwVw($(this),$(this).data("target"));
    });
    
    /*************** MENU *****************/
    
    $(".jb-nwfd-h-mn-elt").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_SwMn($(this));
    });
    
    /*
    $(".jb-nwfd-h-mn-elt").hover(function(){
//        var $t = $(e.target);
        _f_MnHvr($(this));
    });
    //*/                                                                                                                                                                                                                                          
    
    /**************** NWFD inGLOBALNAV ****************/
    
    $(".jb-nwfd-b-l-mdl-b-box-box").hover(_f_HvrListElt);
    
    $(".jb-global-nav-elt.global-nav-nwfd").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Run();
    });
    
    $(".jb-global-nav-elt.global-nav-close").click(function(e){
        Kxlib_PreventDefault(e);
        
         _f_Close();
    });
    
    /*
     * [NOTE 13-05-15] @BOR
     * .is(":hover") ne fonctionnant pas partout, j'utilise la voix standard.
     */
    $(".jb-global-nav-elt[data-target='nwfd']").hover(function(e){
        
//        $("#jb-global-nav-nwfd").toggleClass("this_hide");
//        $("#jb-global-nav-nwfd_h").toggleClass("this_hide");

//        Kxlib_DebugVars([ ==> "+$(".jb-global-nav-elt[data-target='nwfd']").is(":hover")]);
//        Kxlib_DebugVars([ ==> "+$(this).is(":hover")]);
        $("#jb-global-nav-nwfd").addClass("this_hide");
        $("#jb-global-nav-nwfd_h").removeClass("this_hide");
        /*
        if ( $(this).filter(':hover').length ) {
//        if ( $(this).is(":hover") ) {
            $("#jb-global-nav-nwfd").addClass("this_hide");
            $("#jb-global-nav-nwfd_h").removeClass("this_hide");
        } else {
            $("#jb-global-nav-nwfd_h").addClass("this_hide");
            $("#jb-global-nav-nwfd").removeClass("this_hide");
        }
        //*/
        $("#h-c-b-pc-menu-txt .nwfd").toggleClass("this_hide");
    },function(){
        $("#jb-global-nav-nwfd_h").addClass("this_hide");
        $("#jb-global-nav-nwfd").removeClass("this_hide");
        
        $("#h-c-b-pc-menu-txt .nwfd").toggleClass("this_hide");
    });
    
    
    $(".jb-bttf-nwfd").click(function(e){
        Kxlib_PreventDefault(e);
        
        //Version animée
        /*
        $(".jb-nwfd-body").animate({
            scrollTop :0
        },1000);
        //*/
        //Version simple 
        $(".jb-nwfd-body").scrollTop(0);
    });
    
    /***************** MOZ DATAS *******************/
    $(".nwfd-b-m-mdl-trig").hover(_f_ShwMozBar);
    
    //[Note - 03-06-14] Pas mouseout à cause des child, googleit !
    $(".jsbind-b-moz-l-bar").on('mouseleave',_f_HoldMozBar);
    
    $(".jb-nwfd-body").scroll(function(){
//        alert($(this).children().css("height"));
//        Kxlib_DebugVars([(this).scrollTop()]);
    });
    
    
    /******** CHARGEMENT DES ELEMENTS ANTERIEURS ********/
    
    //Chargement par demande 
    $(".jb-nwfd-loadm-trg").click(function(e){
        Kxlib_PreventDefault(e);
        
        //On sauvegarde le bloc où seront insérés les nouveaux éléments
        var b = _f_GetActvMnB2();
//        alert($(b).data("m"));
        _f_PdSentinel(b);
    });
    
    //Chargement automatique 
    $(".jb-nwfd-body").scroll(function(e){
//        Kxlib_DebugVars([Hauteur -> "+$("#nwfd-b-list").height()+"Scroll -> "+$(this).scrollTop()]);
        //l = limit
//        return; 
        try {
            
            var $ab = _f_GetActvMnB2(), l;

            l =  ( $($ab).find(".jb-nwfd-view-bloc").data("v") === 'l' ) ?
//                $($ab).parent().height() - $(".jb-nwfd-body").height() + 20
                $($ab).height() - $(".jb-nwfd-body").height() + 20
//                : $($ab).parent().height() - $(".jb-nwfd-body").height() + 20 + 5 ;
                : $($ab).height() - $(".jb-nwfd-body").height() + 20 + 5 ;

            var y = Math.round(l/10);
            y *= 8;
            
//            Kxlib_DebugVars(["NWFD_SCROLL >>> "," AB_HEIGHT => "+$($ab).height()+"; BODY_HEIGHT => "+$(".jb-nwfd-body").height(),"; COOR_Y => ",y,"; SCROLL => ",$(this).scrollTop()]);
        
            /*    
            //Debug
            var y = Math.round(l/10);
            y *= 8;
            var txt = "Hauteur -> "+$($ab).parent().height()+"; Theory -> "+l+"; Scroll -> "+$(this).scrollTop()+"; Au dixieme -> "+y;
            $("#nwfd-h-title").html(txt);

    //        Kxlib_DebugVars([TYPE STD => "+typeof $(this).scrollTop()+"; TYPE NB => "+typeof y]);

            //*/
        
//        Kxlib_DebugVars([($ab).data("ulk")]);
//        Kxlib_DebugVars([NWFD >>>"NWFD_SCROLL : ",";COOR_Y => ",y,";ULK_CODE => ",$($ab).data("ulk"),";TIMEZONE ? => ",_f_IsZoneTimeRdy($ab,"loc",_f_GetComposSts().ttc_oa)]); 
//        Kxlib_DebugVars(["NWFD >>> SCROLL FUNCTION => "+$($ab).is(".jb-nwfd-b-list")]);
            if ( $($ab).data("ulk") === 1 ) {
                return;
            } else if ( $(this).scrollTop() >= y && $($ab).data("ulk") === 2 && _f_IsZoneTimeRdy($ab,"loc",_f_GetComposSts().ttc_oa) ) {
//                Kxlib_DebugVars(["NWFD_SCROLL","Ca passe :) en 2 "]);
                _f_PdSentinel($ab);
    //                Kxlib_DebugVars([chargés"]);
            } else if ( $(this).scrollTop() >= y && $($ab).data("ulk") === 0 ) {
//                Kxlib_DebugVars(["NWFD_SCROLL","Ca passe :) en 0 "]);
                _f_PdSentinel($ab);
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()]);
        }
    });
    
    /******** GESTION NOUVEAUX ARRIVANTS ********/
    
    /*
    //[DEPUIS 08-05-16] Retirer car trop compliqué à mettre à niveau pour tqr.vb30 et pas necessaire
    $(".jb-nwfd-n-c-rvl-trg").hover(function(e) {
        var th = this, mx = $(this).closest(".jb-nwfd-nptp-m");
        setTimeout(function(){
            if ( $(th).is(":hover") && $(mx).hasClass("zmout") ) {
//                Kxlib_DebugVars([NPTP starts ZoomIn"]);
                //ZoomIn
                _f_NewPostTrigPanZmIn($(mx).data("scp"));
            } else if ( $(th).is(":hover") && !$(mx).hasClass("zmout") ) {
//                Kxlib_DebugVars([NPTP starts ZoomOut"]);
                //ZoomOut
                _f_NewPostTrigPanZmOut($(mx).data("scp"));
            }
        },600);
    }, function(e) {
//        Kxlib_DebugVars([NPTP starts ZoomOut"]);
    });
    //*/
    
    $(".jb-nwfd-sld-choices").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_NwfdSlidedAct(this);        
    });
    
    /******** GESTION NOUVEAUX ARRIVANTS ********/
    
    $(".jb-nwfd-la-scrn-h-fil").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Lasta_Action(this);        
    });
    
    /******** LAST ACTIVITIES ********/
    
    try {
        if ( $(".jb-nwfd-la-list-bdy-lst-mx").length && $(".jb-nwfd-la-mdl-bmx").length ) {
            $(".jb-nwfd-la-list-bdy-lst-mx").perfectScrollbar({
                suppressScrollX : true
            });
        }
    } catch (ex) {
        Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
    }
    
}

new NewsFeed();