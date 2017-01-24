/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function NewsFeed () {
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
    
    this._GetDefaultValues = function () {
        var df = {
            "__NWFD_BF_LOAD" : 0,
            //CNA : Check New Articles, Le Temps d'attente avant de pouvoir aller vérifier si de nouveaux Articles existent.
            "__NWFD_CNA_ROT" : 30000,
//            "__NWFD_CNA_ROT" : 5000, //DEBUG
            "__NWFD_LIST_MAXNB" : 200, /* Données arbritraires à la version vb1.10.14.x */
            "__NWFD_MOZ_MAXNB" : 300, /* Données arbritraires à la version vb1.10.14.x */
            "__NWFD_LIST_VIEW" : "nwfd-b-list",
            "__NWFD_MOZ_VIEW" : "nwfd-b-moz-max"
        };
        
        return df;
    };
    
    this._CheckNewLoadingAllowed = function (mb) {
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
                return ( $(mb).find(".jb-nwfd-art-mdl").length >= this._GetDefaultValues().__NWFD_LIST_MAXNB ) ? false : true;
            } else {
                return ( $(mb).find(".nwfd-b-moz-mdl-max").length >= this._GetDefaultValues().__NWFD_MOZ_MAXNB ) ? false : true;
            }
                
        }
    };
    
    this._GetComposStatus = function () {
        var rm = $("#newsfeed-sprt").data("rm"), access = $("#newsfeed-sprt").data("access"), menu = $(".nwfd-menu-active").data("target"), view = $(".jb-nwfd-view-active").data("vwcode");
        var d = {
            /*
             * TimeToCheck_OldArticles : 30 secondes car la probabilité que si on ne trouve rien au premier essai, on trouve quelque chose au deuxième est presque nulle.
             * Aussi, mettre une grande valeur n'est pas un décision erronée. 
             * De toutes les façons, il s'agit d'une donnée arbritraire selon que l'expérience utilisateur est bonne ou pas.
             * 
             * RAPPEL : Cette valeur est utilisée par les méthodes Sentinel. Pour mieux la comprendre, il serait judicieux de lire ces méthodes.
             */
            "ttc_oa": 30000,
            "rm" : rm, //1,0
            "access": access, //1, 0
            "menu" : menu, //team, boss, comy, bzfeed
            "view" : view //moz, list
        };
        
        return d;
    };
    
    this._GetActiveMenuBloc = function () {
        /* Permet d'obtenir un Objet représentant le menuBloc actif quelque soit la vue */
        var r = this._GetComposStatus();
        var sl = "#nwfd-"+r.view+"-"+r.menu;
        
        if ( $(sl).length ) return $(sl);
        else return;
    };
    
    this.GetActiveMenuBloc2 = function () {
        /* Permet d'obtenir un Objet représentant le menuBloc actif quelque soit la vue
         * Cette méthode récupère le bloc actuellement affichée.
         * Je l'ai crée car elle me semblait plus fiable dans le contexte du developpement
         *  */
//        var $o = $(".jb-nwfd-view-bloc").find(":not(.this_hide)");
        var $o = $(".jb-nwfd-view-bloc:not(.this_hide)");
        
        if ( $o.length === 1 )
            return $o;
        else 
            return;
    };
    
    this.UpdateComposStatus = function () {
        var d = this._GetComposStatus();
            
//        alert("RUNMODE => "+rm+"; ACCESS => "+access+"; MENU => "+menu+"; VIEW => "+view);
        
        //Envoyer les paramètres au niveau du serveur
        this._Srv_UpdateComposStatus(d);
    };
    
    this._AdjustHeightInBlocs = function () {
        var hnb = $(window).height();
        
        /* Donne la bonne taille aux blocs en fonction de la taille de l'écran */
        //Hauteur du support
        var h_spt = hnb - 20; //20px correspond à l'espace que prennent les bordures
//        alert("Window_Height => "+hnb+"; Height => "+h);
//        return;
        $("#newsfeed-sprt").css("height",Kxlib_ToPxUnit(h_spt));
       
        //Hauteur du body
//        var hdr = parseInt($("#nwfd-header").height().replace("px",''));
        var hdr = parseInt($("#nwfd-header").height());
        
        var h_bd = h_spt - hdr;
        h_bd -= parseInt($("#nwfd-footer").css("height").replace("px",''));
//        alert("Window_Height => "+hnb+"; Header => "+hdr+"; Height => "+Kxlib_ToPxUnit(h_bd));
//        return;
        
        $("#nwfd-body").css("height",Kxlib_ToPxUnit(h_bd));
    };
    
    this.Init = function (a) {
        
        /* On vérifie si le serveur qu'on ne touche pas à la configuration */
        var sp = $("#newsfeed-sprt").data("svskip");
        if ( !KgbLib_CheckNullity(sp) && sp === 1 ) 
            return;
        
        /* Place le support de telle sorte qu'il puisse descendre lors de l'ouverture */
        var hnb = $(window).height();

        var mrg = hnb * -1;
        mrg = mrg.toString()+"px";
        
        $("#newsfeed-sprt").css("margin-top",mrg);
        $("#newsfeed-sprt").removeClass("this_hide");
        
        $("#newsfeed-max").removeClass("this_hide");
        $("#newsfeed-max").fadeOut();
        
        //Ajuster la hauteur des différents blocs (Important)
        this._AdjustHeightInBlocs();        
        
        if ( !KgbLib_CheckNullity(a) && a ) 
            this.Open();
        
        /* Vérifier si Menu, View sont initialisés. Corriger les problèmes d'éléments non synchronisés */
        this._RepareAlignment();
        
        //TODO : Lancer la Sentinel
    };
            
    this._RepareAlignment = function () {
        /*
         * RULES :
         * NewsFeed doit être cohérent. En effet, il n'y a qu'une div pour afficher tous les menus ...
         * ... selon une VIEW donnée.
         * Il faut donc que lorsque la VIEW sélectionnée est 'List' que le bloc affichant les données ..
         * ... sous format de liste ait le focus.
         *  
         **/
        var r = this._GetComposStatus();
        
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
    
    this.Datas_FirstArticles = function () {
        
        /*
         * Permet de récupérer et de traiter les données liées à la "communauté" de l'utilisateur actif.
         * Cette méthode est appelée à la première ouverture de NWFD par l'utilisateur depuis sa connexion.
         * Aussi, on suppose que les composantes sont : 
         *  mode : list
         *  menu : community
         * 
         * Cependant, pour des raisons de qualité, on va s'en assurer en prennant en se fiant exactement aux paramètres.
         */
        var pv = this._GetComposStatus(), s = $("<span/>"), isl = false, mb;
        
        switch (pv.menu) {
            case "team" :
                    if ( pv.view === "list") {
//                        p = "NWFD_GET_LAST_TEAM_LIST";
                        isl = true;
                        mb = "#nwfd-list-team";
                    }
                    else {
//                        p = "NWFD_GET_LAST_TEAM_MOZ";
                        mb = "#nwfd-moz-team";
                    }
                break;
            case "comy" :
                    if ( pv.view === "list") {
//                        p = "NWFD_GET_LAST_COMY_LIST";
                        isl = true;
                        mb = "#nwfd-list-comy";
                    }
                    else {
//                        p = "NWFD_GET_LAST_COMY_MOZ";
                        mb = "#nwfd-moz-comy";
                    }
                break;
            case "bzfeed" :
                    if ( pv.view === "list") {
//                        p = "NWFD_GET_LAST_BZFD_LIST";
                        isl = true;
                        mb = "#nwfd-list-bzfeed";
                    }
                    else{
//                        p = "NWFD_GET_LAST_BZFD_MOZ";
                        mb = "#nwfd-moz-bzfeed";
                    }
                break;
        }        
        
        p = Kxlib_GetAjaxRules("NWFD_GET_ARTS");
        
        //w = Which
        w = "std"; //RAPPEL : std, new, old
        this.LoadDatas(p,pv.menu,pv.view,w,mb,s);
        
        var th = this;
        $(s).on("datasready", function (e,d,b) {
            if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(b) )
                return;
            
            //On ajoute les données à SHED (Hangar != Buffer Zone)
            var sd = JSON.stringify(d.as);
            $(".jb-nwfd-shed").text(sd);
            
            //On affiche les données selon le mode de vue
            if ( isl ) {
                th.DisplayDatasListMode(d.as,b);
            } else {
                th.DisplayDatasMozMode(d.as,b);
            }
            
            //Update de NOoNE
            th.Noone();
            
//            Kxlib_DebugVars([th.ShedCount()],true);
            
        });

    };
    
    /****************** HANDLERS *********************/
       
    this.Handler_HoverListElt = function(){
        $(this).find(".nwfd-b-l-mdl-b-box-fade").toggleClass("nwfd-b-l-mdl-b-box-fade-hover");
    };
    
    this.Handler_ShowMozBar = function(){
        gth.ShowArtBar($(this));
    };
    
    this.Handler_HoldMozBar = function(){
        var th = this;
        setTimeout(function(){
            if ( $(th).closest(".nwfd-b-moz-l-list").find(".nwfd-b-m-mdl-trig:hover").length === 0 ) {
                $(th).stop(true,true).fadeOut();
            }
        },100);
    };
    
    /****************** RUNNING MANAGEMENT ******************/
    this.Run = function () {
        //TODO : Permet de préparer le NewsFeed à la premçière utilisation
            //Récupérer les status des composants
            //Appliquer les statuts des composants
            
            
        //On déclare le module running (1)
        $("#newsfeed-sprt").data("rm",1);
        
        //Lancer l'affichage
        this.Open();
    };
    
    this.Shutdown = function () {
        //TODO : Permets de travailler sur certains process avant et/ou après la cloture
        //(Est utilisé surtout lors de la déconexion)
        
        //TODO : Fermeture du module si ce n'est pas déjà le cas
        
        //On déclare le module running (0)
        $("#newsfeed-sprt").data("rm",0);
    };
    
    /****************** ACCESS MANAGEMENT ******************/
    
    this.Open = function () {
        var th = this;
        /* 
         * Mise à jour de la taille de l'écran.
         * 
         * Cela permet de redimensionner l'écran au cas où par exemple la fenetre d'inspection associée 
         * au navigateur a "déformé" la fenêtre.
         * */
        //On affiche les zones pour permettre les différents calculs
        $("#newsfeed-max").removeClass("this_hide");
        
        this._AdjustHeightInBlocs(); 
        
        $("#newsfeed-max").addClass("this_hide");
        
        //Afficher le support
        $("#newsfeed-sprt").animate({
            "margin-top": 0
        }, 900, function() {
            //Apres que le support ait terminé l'affichage on ouvre le module à propement parlé
            $("#newsfeed-max").hide().removeClass("this_hide").fadeIn().removeAttr("style");
            
            //On indique que le module est visible
            $("#newsfeed-sprt").data("access",1);
            
            //Mise à jour de la configuration de NewsFeed
//            th.UpdateComposStatus();
            
            //On vérifie s'il s'agit de la première visite 
            /*
             * Pour ce faire, on vérifie si la zone dite "hangar" est vide.
             * Cette zone stocke les Articles arrivés sous le "mandat" FirstArticles.
             * 
             * RAPPEL : Une autre zone se nomme "BufferZone".
             * Cette zone contient les Articles en attente d'affichage.
             */
           
            if (! th.ShedCount() ) {
                //On récupère les données
                th.Datas_FirstArticles();
            } else {
                //On vérifie s'il y a des Articles ulterieur
                th.HandleNewerArticles();
            }
            
        });
    };
    
    this.Close = function () {
        //Fermer le module
        var h = $(window).height(), th = this;

        h *= -1;
        h = h.toString()+"px";
        
        $("#newsfeed-sprt").animate({
            "margin-top": h
        }, 750, function() {
            //On masque le module
            $("#newsfeed-max").addClass("this_hide").removeAttr("style");
            
            //On indique que le module est caché
            $("#newsfeed-sprt").data("access",0);
            
            //Mise à jour de la configuration de NewsFeed
            th.UpdateComposStatus();
        });
        
    };
    
    /****************** SHED ZONES SCOPE (START) ******************/
    
    this.ShedCount = function () {
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
            var d = this._GetShedDatas(), cn = Kxlib_ObjectChild_Count(d);
            
            cn = ( typeof cn === "undefined" ) ? 0 : cn;
            
            return cn;
        }
        
    };
    
    this._GetShedDatas = function () {
                
        //sd = ShedDatas
        var sd = $(".jb-nwfd-shed").text();
        
        if (! KgbLib_CheckNullity(sd) ) {
                   
            try {
                var d = JSON.parse(sd);
//                Kxlib_DebugVars([sd, typeof d, typeof d ],true);
                return d;
            } catch (e) {
//                alert(e);
                //Si la chaine de caractères ne correspond pas à une frme JSON valide
                return false;
            }

        }
        
    };
    
    /****************** SHED ZONES SCOPE (END) ******************/
    
    /****************** BUFFER ZONES SCOPE (START) ******************/
    
    this._BufferCount = function () {
        /*
         * Permet de connaitre le nombre d'Article dans la ZONE BUFFER. S'il n'y a aucun Article, la méthode renvoie 0.
         * Si la zone n'existe pas, elle sera créée. Cela n'est possible qu'à la première ouverture de NEWSFEED.
         * Silent, man !
         */ 
        if (! $(".jb-nwfd-buffer").length ) {
            //On crée la zone
            var buf = $("<div/>").attr({
                "id": "nwfd-buffer",
                "class": "jb-nwfd-buffer this_hide",
                //lp = LastPull (Dernière fois que l'on a procédé à la mise à jour des données de la zone
                "data-lp": ""
            });
            
            //On ajouter dans le DOM
            if (! $(".jb-nwfd-shed").length ){
                $(buf).insertAfter(".jb-nwfd-footer");
            } else {
                $(buf).insertBefore(".jb-nwfd-shed");
            }
            
//            Kxlib_DebugVars([$(".jb-nwfd-footer").length, $(".jb-nwfd-buffer").length], true);
            
            return 0;
        } else {
            var d = this._GetBufferDatas(), cn = Kxlib_ObjectChild_Count(d);
            
            cn = ( typeof cn === "undefined" ) ? 0 : cn;
            
            return cn;
        }
    };
    
    this._GetBufferDatas = function () {
        
        //bd = BufferDatas
        var bd = $(".jb-nwfd-buffer").text();
        
        if (! KgbLib_CheckNullity(bd) ) {
                   
            try {
                var d = JSON.parse(bd);
//                Kxlib_DebugVars([bd, typeof d, typeof d ],true);
                return d;
            } catch (e) {
//                alert(e);
                //Si la chaine de caractères ne correspond pas à une frme JSON valide
                return false;
            }

        }
        
    };
    
    this._SetBufferDatas = function (jo) {
        if ( KgbLib_CheckNullity(jo) || typeof jo !== "object" )
            return;
        
        try {
//            Kxlib_DebugVars([Kxlib_ObjectChild_Count(jo)],true);
            var st = JSON.stringify(jo);
//            Kxlib_DebugVars([st],true);
            if (! $(".jb-nwfd-buffer").length ) {
                //On crée la zone
                var buf = $("<div/>").attr({
                    "id": "nwfd-buffer",
                    "class": "jb-nwfd-buffer this_hide",
                    //lp = LastPull (Dernière fois que l'on a procédé à la mise à jour des données de la zone
                    "data-lp": ""
                });
                
                //On ajoute dans le DOM
                if (! $(".jb-nwfd-shed").length ){
                    $(buf).insertAfter(".jb-nwfd-footer");
                } else {
                    $(buf).insertBefore(".jb-nwfd-shed");
                }
            }
            
            $(".jb-nwfd-buffer").text(st);
            
            return st;
        } catch (e) {
//          alert(e);
            /*
             * [NOTE 27-09-14] @author L.C.
             * Je ne pense pas que stringify puisse déclencher une excepton
             */
            return false;
        }

    };
    
    this.NwfdSlidedAction = function (x) {
        if ( KgbLib_CheckNullity(x) || KgbLib_CheckNullity($(x).data("action")) )
            return;
        
        var ac = $(x).data("action");
        
        switch (ac) {
            case "develop" : 
                    this.ShowNwfdSidePanel(x);    
                break;
            case "simple" : 
                    this.HideNwfdSidePanel(x);    
                break;
            case "nav-up" : 
                    this.NwfdSlidedNavUp();
                break;
            case "nav-down" : 
                    this.NwfdSlidedNavDown();
                break;
            case "nav-first" : 
                    this.NwfdSlidedNavFirst();
                break;
            case "reveal" : 
                    if ( $(".jb-nwfd-nptp-m").hasClass("zmout") ) {
//                        alert("Reaveal Articles On First Option");
                        this.HandleDisplayNewer();
                    } else {
//                        alert("Reaveal Articles On Semi-lop Option");
                        //On mets en place un lock qui permettra d'annuler les animations car la fenetre de droite sera ouverte
                        this.HandleDisplayNewer();
                    }
                    //If la partie de gauche est ouverte => Devlop Option
                break;
            case "reveal-od" : 
//                    alert("Reveal On Develop right sector");
                    this.HandleDisplayNewer();
                break;
                
            default:
                    return;
                break;
        }
        
    };
    
    
    /******************* BUFFER ZONE SCOPE (END) *******************/
       
    
    /**********************************************************************************************************************************************/
    /************************************************************** AJAX SCOPE ********************************************************************/
    /**********************************************************************************************************************************************/
    
    
    /******************* DATAS MANAGEMENT ********************/
    
    this.LoadDatas = function (p,mn,vw,w,mb,s) {
        //p = Données sur AJAX, mn = Menu; vw = View; mb = MenuBloc; s = Snitcher; ai = ArticleId; at = ArticleTime
        if ( KgbLib_CheckNullity(p) | KgbLib_CheckNullity(mn) | KgbLib_CheckNullity(vw) | KgbLib_CheckNullity(w) | KgbLib_CheckNullity(mb) | KgbLib_CheckNullity(s) )
            return;
                
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
//                        th.DisplayPredateDatasListMode(datas.return,mb);
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
                    var rds = [datas.return,mb];
                    $(s).trigger("datasready",rds);
                } else if ( KgbLib_CheckNullity(datas.return) ) {
                    var rds = [mb];
                    $(s).trigger("operended",rds);
                } else return;
                    
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                return;
            }
        };

        var onerror = function(a,b,c) {
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            return;
        };
        
        var toSend = {
            "urqid": p.urqid,
            "datas": {
                "mn": mn, //comy, team, bzfeed
                "vw": vw, //list, moz
                "w": w //std, new, old
            }
        };

        Kx_XHR_Send(toSend, "post", p.url, onerror, onsuccess);
    };
    
    //Permet de récupérer les données en se basant sur un article pivot fourni en paramètre. On passe un objet avec id, time
    this.LoadDatas_From = function (p,mn,vw,w,mb,s,a) {
        //p = Données sur AJAX, mn = Menu; vw = View; mb = MenuBloc; s = Snitcher; a = { ai = ArticleId; at = ArticleTime }
        if ( KgbLib_CheckNullity(p) | KgbLib_CheckNullity(mn) | KgbLib_CheckNullity(vw) | KgbLib_CheckNullity(w) | KgbLib_CheckNullity(mb) | KgbLib_CheckNullity(s) | KgbLib_CheckNullity(a) )
            return;
                
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
//                        th.DisplayPredateDatasListMode(datas.return,mb);
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
                    var rds = [datas.return,mb];
                    $(s).trigger("datasready",rds);
                } else if ( KgbLib_CheckNullity(datas.return) ) {
                    var rds = [mb];
                    $(s).trigger("operended",rds);
                } else return;
                    
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                return;
            }
        };

        var onerror = function(a,b,c) {
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            return;
        };
        
        var toSend = {
            "urqid": p.urqid,
            "datas": {
                "mn": mn, //comy, team, bzfeed
                "vw": vw, //list, moz
                "w": w, //std, new, old
                "ai": a.i,
                "at": a.t
            }
        };

        Kx_XHR_Send(toSend, "post", p.url, onerror, onsuccess);
    };
    
    //OBSELETE
    this.LoadDatasListMode = function (p,mb,pd,s) {
        //p = paramtères (rm, access, menu, view); isl = IsList; pd = Predate => Est ce qu'on veut afficher les anciens résultats
        if ( KgbLib_CheckNullity(p) | KgbLib_CheckNullity(mb) | KgbLib_CheckNullity(pd) | KgbLib_CheckNullity(s) )
            return;
                
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
//                        th.DisplayPredateDatasListMode(datas.return,mb);
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
                } else return;
                    
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                return;
            }
        };

        var onerror = function(a,b,c) {
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
    
    this._CheckEltNoExsitsInList = function (b,d) {
        //d=Data les données de l'élément qui sera ajouté; b = Le bloc dans lequel il sera ajouté
        
        if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(b) )
            return;
        
        //it = item; Correspond à l'id de l'article
        var it = d.art_eid;
        
        var $e = $(b).find(".nwfd-b-l-mdl-max[data-item="+it+"]");
        
//        alert("Bloc => "+$(b).attr("id")+"; Item => "+it+"; Exists => "+$e.length); //DEBUG
        var r = ($e.length) ? true : false;
        
        return r;
    };
    
    this.LoadDatasMozMode = function (p,mb,pd) {
        //p = paramtères (rm, access, menu, view); mb = MenuBloc ; pd = Predate => Est ce qu'on veut afficher les anciens résultats
        if ( KgbLib_CheckNullity(p) ) return;
        
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
                    th.DisplayDatasMozMode(datas.return,mb,null,pd);
                }
                    
            } catch (e) {
                //TODO : ?
            }
        };

        var onerror = function(a,b,c) {
            //NOTHING
        };
        
        var toSend = {
            "urqid": p.urqid,
            "datas": {
            }
        };

        Kx_XHR_Send(toSend, "post", p.url, onerror, onsuccess);
    };
    
    this._CheckEltNoExsitsInMoz = function (b,d) {
        //d=Data les données de l'élément qui sera ajouté; b = Le bloc dans lequel il sera ajouté
        
        if ( KgbLib_CheckNullity(d) || KgbLib_CheckNullity(b) ) return;
        
        //it = item; Correspond à l'id de l'article
        var it = d.art.id;
        
        var $e = $(b).find(".nwfd-b-moz-mdl-max[data-item="+it+"]");
        
//        alert("Bloc => "+$(b).attr("id")+"; Item => "+it+"; Exists => "+$e.length); //DEBUG
        var r = ($e.length) ? true : false;
        
        return r;
    };
    
    
    
    
    /************************* DATA SYNCHORNIZATION ***********************/
    
    this._IsZoneTimeReady = function (mb,k,rt) {
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
        if ( tm === 0 || eld >= rt )  {
            return true;
        } 
        
        return false;
    };
    
    this.HandleNewerArticles = function () {
        /*
         * [NOTE 05-10-14] @author L.C.
         * Permet de vérifier si des Articles ont été nouvellement ajoutés.
         * Pour cela, on utilise la Sentinel a qui ont transmet le bon bloc en question
         */
        
        var x = this._GetComposStatus(), mb;
        
        var isl = ( x.view === "list" ) ? true : false;
        
        switch ( x.menu ) {
            case "comy" :
                    if ( isl ) {
                        mb = "#nwfd-list-comy";
                    } else {
                        mb = "#nwfd-moz-comy";
                    }
                break;
            default:
                    return;
                break;
        }
        
        this.Sentinel(mb);
        
    };
    
    this.HandleDisplayNewer = function () {
        //On ferme la zone si elle ouverte
        if ( $(".jb-nwfd-slided-max").data("sts") === 1 ) {
            $(".jb-nwfd-nptp-dvlp-trg").click();
        }
        
        //On fait disparaitre le trigger
        this.NewPostTrigPanZmOut();
//        $(".jb-nwfd-nptp-m").fadeOut(500);
        
        //On récupère les données dans la zone Buffer
        var d = this._GetBufferDatas();
        
        if ( !KgbLib_CheckNullity(d) && Kxlib_ObjectChild_Count(d) ) {
            
            var isl = ( this._GetComposStatus().view === "list" ) ? true : false;
            var b = this.GetActiveMenuBloc2();
            
            //On affiche les données
            if ( isl ) {
                this.DisplayDatasListMode(d,b,true);
            } else {
                this.DisplayDatasMozMode(d,b);
            }
            
            //Update de NOoNE
            this.Noone();
            
            this.NewPostTrigPan_Kill();
        }
        
    };
    
    this.Sentinel = function (mb) {
        
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
        var r = this._CheckNewLoadingAllowed(mb);
//        alert(r); //DEBUG
        if ( KgbLib_CheckNullity(r) || !r )
            return;
       
        //isl = isList
        var d = this._GetComposStatus(), p, isl = false, s = $("<span/>"), ad;
        
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
            default: 
                    return;
                break;
        }        
        
        p = Kxlib_GetAjaxRules("NWFD_GET_ARTS_FROM");
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
//        Kxlib_DebugVars([p,d.menu,d.view,"new",mb,s,ad],true);
        
        if ( KgbLib_CheckNullity(ad) ) {
            //Cela signifie qu'il n'y a pas déjà d'Articles présent dans la zone. Aussi, on lance une procédure normale
            this.LoadDatas(p,d.menu,d.view,"std",mb,s);
        } else {
            this.LoadDatas_From(p,d.menu,d.view,"new",mb,s,ad);
        }
        
        var th = this;
        $(s).on("datasready", function(e,d,b) {
           if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(b) ) {
               return;
           }
           
           /*
            * On ne récupère que les données des Articles. Le reste ne sert à rien à la version vb1.10.14.
            * ad : ArticleDatas, les Articles
            */
           var ad = d.as;
           
           //dl = DatasList; al = AuthorList
           var dl = [], al = {};
           
               
            //On ajoute les Articles reçus dans la zone
            th._SetBufferDatas(ad);
    //               Kxlib_DebugVars([th._BufferCount()],true);

            //Pour chaque élément reçu 
            $.each(ad, function(i,x) {
                 //On récupère l'auteur et ... 
                 var ow = x.art_oeid;

    //                    Kxlib_DebugVars([al,KgbLib_CheckNullity(al), typeof ow],true); 
    //                    return;

                 //... on l'ajoute à la liste s'il n'y est pas déjà
                 if ( !Kxlib_ObjectChild_Count(al) || ( al && !al.hasOwnProperty(ow) ) ) {

                     al[ow] = {
                         "oeid": x.art_oeid,
                         "opsd": "@"+x.art_opsd,
                         "ofn": x.art_ofn,
                         "oppic": x.art_oppic_rpath,
                         "ohref": "/@"+x.art_opsd,
                         "pnb": 1
                     };

                 } else {
                     //Pour chaque auteur on incrémente
                     al[ow].pnb = parseInt(al[ow].pnb) + 1;
                 }

    //                    Kxlib_DebugVars([al[ow].n],true); 

            });

    //                Kxlib_DebugVars([Kxlib_ObjectChild_Count(al)],true);     

            //On transforme l'objet en Array (Il était en objet car plus maniable pour les opérations précédentes, en Array car plus maniable pour les suivantes)
            //alt est la version tableau 't' de al
            var alt = $.map(al, function(v,i) {
                 return [v];
            });

    //               Kxlib_DebugVars([alt.length],true);

            //On crée la liste et on ajoute cette dernière dans la zone (avant on vide l'ancienne liste)
    //               Kxlib_DebugVars([typeof alt, $.isArray(al), $.isArray(alt), alt.toString()],true);
             th.NwfdSlide_CreateList(alt);

            //On affiche le trigger
            th.NewPostTrigPan_Show(Kxlib_ObjectChild_Count(ad));
           
        });
        
        $(s).on("operended", function(e,b) {
            
        });
        
        
    };
    
    this.PredateSentinel = function (mb) {
        //Rappel : mb => MenuBox; isa = IsAutomatic (S'agit-il du cas où l'utilisateur a utilisé le scroll pour déclencher la demande de mise à jour)
        
        if ( KgbLib_CheckNullity(mb) )
            return;
        
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
            return;
        } 
        
        //On lock la zone. La zone sera delock par la méthode d'affichag à cause du timer. 
        $(mb).data("ulk",1);
        
        var r = this._CheckNewLoadingAllowed(mb);
//        alert(r); //DEBUG
//        Kxlib_DebugVars([LIMIT => "+r]); //DEBUG
//        return;
        
        if ( KgbLib_CheckNullity(r) || !r )
            return;
        
        //isl = isList
        var d = {
            "view":$(mb).data("v"),
            "menu":$(mb).data("m")
        }, mn, vw, p, isl = false, s = $("<span/>");
        
        switch (d.menu) {
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
            case "c" :
                    if ( d.view === "l") {
//                        p = "NWFD_GET_PREDATE_COMY_LIST";
                        isl = true;
                        vw = "list";
                    } else {
//                        p = "NWFD_GET_PREDATE_COMY_MOZ";
                        vw = "moz";
                    }
                    mn = "comy";
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
            default :
                    //TODO : Prévenir le serveur
                    return;
                break;
        }     
        
//        Kxlib_DebugVars([vw,mn,$(mb).find(".jb-nwfd-art-mdl").length,$(mb).find(".jb-nwfd-art-mdl:last").find(".kxlib_tgspy").data("tgs-crd")],true);
//        return;
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
//        return;


        if ( KgbLib_CheckNullity(ad) ) {
            //Cela signifie qu'il n'y a pas déjà d'Articles présent dans la zone. Aussi, on lance une procédure normale
            this.LoadDatas(p,mn,vw,"std",mb,s);
        } else {
            p = "NWFD_GET_ARTS_FROM";
            p = Kxlib_GetAjaxRules(p);
            //pd (3 parametre) = Predate => Est ce qu'on veut afficher les anciens résultats 
//            if ( isl ) {
                this.LoadDatas_From(p,mn,vw,"old",mb,s,ad);
    //            this.LoadDatasListMode(p,mb,true);
//            } else {
//                this.LoadDatas_From(p,mn,vw,"old",mb,s,ad);
    //            this.LoadDatasMozMode(p,mb,true);
//            }
        }
        
        var th = this;
        $(s).on("datasready", function(e,d,b){
            if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(b) )
                return;
            
//            Kxlib_DebugVars([isl,d.as,b],true);
            
            //On ajoute les données à SHED (Hangar != Buffer Zone)
//            var sd = JSON.stringify(d.as);
//            $(".jb-nwfd-shed").text(sd);
            
            //On affiche les données selon le mode de vue
            if ( isl ) {
                th.DisplayDatasListMode(d.as,b);
            } else {
                th.DisplayDatasMozMode(d.as,b);
            }
            
            //Update de NOoNE
            th.Noone();
            
//            Kxlib_DebugVars([th.ShedCount()],true);
        });
        
        $(s).on("operended", function(e,b) {
            
            /*
             * On arrive ici si on est dans la zone de mise à jour mais qu'aucun Article n'est disponible. 
             * Dans ce cas, on libère la zone.
             * 
             * Cependant, on inscrit la dernière fois que l'on a récupérer les données au niveau du serveur.
             * Cela permet de ne pas lancer des requetes trop souvent car la zone ne changeant pas de taille, les requetes continuent à être lancées.
             * (Sauf si on sort à nouveau de la zone)
             * Pour éviter ce comportement, on met donc un timer qui vérifiera si on a attendu assez de temps par rapport à la demande précédente.
             */
//            alert(th._IsZoneTimeReady(b,"loc",parseInt(th._GetComposStatus()).ttc_oa));
//            alert(parseInt(th._GetComposStatus().ttc_oa));
            //loc : Last Older Check, la dernière fois que l'on est aller vérifier les données dites "antérieures"
//            parseInt(th._GetComposStatus()).ttc_oa);
            if ( th._IsZoneTimeReady(b,"loc",parseInt(th._GetComposStatus().ttc_oa)) ) {
                //On libère la zone
                $(b).data("ulk",0);
                //On inscrit le temps
                var n = (new Date()).getTime();
                $(b).data("loc",n);
            } else {
//                Kxlib_DebugVars([Lock Because time not reached"]);
                /*
                 * On maintient le blocage. Ce blocage est spécial car il autorise à rentrer dans la fonciton à condition que la condition de temps soit remplie.
                 * Cela permet de contourner le fait que la méthode va bloquer toutes les procédures si on est en mode 1.
                 * Le mode 2 veut dire qu'on a déjà procédé à des mises à jour mais qu'on est tombé sur le cas où le serveur nous a indiqué qu'il n'y avait aucun Article "plus bas".
                 * Cependant, étant donné que l'on est dans une application hautement dynamique et qu'elle est construite ne mode SPA (Single Page Application) les choses sont imprévisibles.
                 * Aussi, pour ne pas dégrader l'expérience utilisateur, on choisit de continuer à intérroger le serveur mais seulement après un lapse de temps donné.
                 * 
                 * Enfin, ce code est automatiquement changé en 1 à l'entrée de la méthode puis on 0 si la mise à jour se fait et se termine normalement OU
                 * si on est de nouveau autorisé car on a atteint ou dépassé le temps d'attente.
                 * Il repasse en 2 si aucune donnée n'est revenu du sereur (encore une fois) et que le temps d'attente n'est pas dépassé.
                 * Normalement, cela est impossible dans le cas on change le code à 0 dans le cas ci-dessus. Tout dépend donc de la logique du développeur.
                 * On pourrait passé le cas ci-dessus à 2. Cela semblerait logique, mais j'attends de voir les conséquences du code 0. Voir s'il résout quand meme le problème.
                 */
                $(b).data("ulk",2);
            }
            
        });
    };
    
    
    this.TreatNewArt = function () {
        //TODO : Losrque de nouveaux Articles sont disponibles, on les affiche selon le mode en cours
        
    };
    
    /********************** DISPLAYING SPECIAL TREATMENTS ***********************/
    
    this.ReduceHeader = function () {
        if (! $("#nwfd-h-title").hasClass("this_hide") ) {
            //On réduit les parties "inutiles" du Header
            $("#nwfd-h-title-max").toggleClass("this_hide");
            $("#nwfd-h-desc-max").toggleClass("this_hide");
            
            /* On agrandit la zone de la liste en fonction de l'espace restant */
            var h = $("#newsfeed-sprt").height() - $("#nwfd-header").height() - $("#nwfd-footer").height();
            $("#nwfd-body").height(h);
            
            /* On ajuste la taille de la zone Slided */
            this._UpdateNwfdSidePanel();
        } else {
            //On rétablit les parties "inutiles" du Header
            $("#nwfd-h-title-max").toggleClass("this_hide");
            $("#nwfd-h-desc-max").toggleClass("this_hide");
            
            /* On rétablit la taille du body */
            $("#nwfd-body").removeAttr("style");
            
            /* On ajuste la taille de la zone Slided */
            this._UpdateNwfdSidePanel();
        }
            
    };
    
    /********************** DATAS SPECIAL TREATMENTS ***********************/
    this._PrepareMozBarDatas = function (o) {
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
        } catch (e) {
            Kxlib_DebugVars([e],true);
        }

    };
    
    
    
    this.FocusOnMozBar = function (b) {
        if ( KgbLib_CheckNullity(b))
            return;
        
        var $ob = $(b);
        $ob.toggleClass("this_hide");
    };
    
    this.Noone = function () {
        /* Affiche un message lorsqu'il n'y a aucun contenu disponible */
        var r = this._GetComposStatus();
        var v = r.view, sl;
        
//        sl = ( v === "list" ) ? "#nwfd-b-list" : "#nwfd-b-moz"; //old
        sl = ( v === "list" ) ? "#nwfd-b-list" : "#nwfd-b-moz-max";
       
        if (! $(sl).children().not(".jsbind-nwfd-noone-max, .nwfd-b-list-bttf").length ) {
            //Retirer BTTF
            $(sl).find(".nwfd-b-list-bttf").addClass("this_hide");
            
            //Afficher le message disant qu'il n'y a aucun Article
            $(sl).find(".jsbind-nwfd-noone-max").removeClass("this_hide");
        } else {
            
            //Retablir BTTF
            $(sl).find(".nwfd-b-list-bttf").removeClass("this_hide");
            
            //Retirer le message disant qu'il n'y a aucun Article
            $(sl).find(".jsbind-nwfd-noone-max").addClass("this_hide");
        }
        
    };
    
    /******************** SERVER EXCHANGES *******************/
    
    /**************************************************************/
    //URQID => Update les paramètres de NewsFeed
    this.updnwfd_uq = "UPD_NWFD_PARAMS";
    this.updnwfd_ajaxr = Kxlib_GetAjaxRules(this.updnwfd_uq);
    
    this._Srv_UpdateComposStatus = function (d) {
        //TODO : Mets à jour le statut des composants pour améliorer l'expérience utilisateur
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
            //NOTHING
        };
        
        var toSend = {
            "urqid": th.updnwfd_uq,
            "datas": {
                "conf": d
            }
        };

        Kx_XHR_Send(toSend, "post", th.updnwfd_ajaxr.url, onerror, onsuccess);
    };
    
    /**********************************************************************************************************************************************/
    /************************************************************** VIEW SCOPE ********************************************************************/
    /**********************************************************************************************************************************************/
    
    /****************** VIEW MODE MANAGEMENT ******************/
    
    this.SwitchToMoz = function () {
//        alert("Reached Moz");
        var m = Kxlib_ValidIdSel(this._GetDefaultValues().__NWFD_MOZ_VIEW);
        var l = Kxlib_ValidIdSel(this._GetDefaultValues().__NWFD_LIST_VIEW);
        $(l).addClass("this_hide");
        $(m).removeClass("this_hide");
        
        /* Afficher le MenuBlock correspondant */
        //mtg = MenuTarGet 
        var mtg = $(".nwfd-h-menu-elt.nwfd-menu-active").data("target");
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
    
    this.SwitchToList = function () {
        var m = Kxlib_ValidIdSel(this._GetDefaultValues().__NWFD_MOZ_VIEW);
        var l = Kxlib_ValidIdSel(this._GetDefaultValues().__NWFD_LIST_VIEW);
        $(m).addClass("this_hide");
        $(l).removeClass("this_hide");
        
        /* Afficher le MenuBlock correspondant */
        //mtg = MenuTarGet 
        var mtg = $(".nwfd-h-menu-elt.nwfd-menu-active").data("target");
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
    
    this.SwitchView = function(o,a) {
        if ( KgbLib_CheckNullity(a) )
            return; //Todo : Déclencher une erreur
        
        switch (a) {
            case this._GetDefaultValues().__NWFD_LIST_VIEW :
                    this.SwitchToList();
                break;
            case this._GetDefaultValues().__NWFD_MOZ_VIEW :
                    this.SwitchToMoz();
                break;
        }
        
        var b1 = this.GetActiveMenuBloc2();
        
        /* On retire la classe active à old et on la mets sur new */
        //Retirer sur old
        $(".jb-nwfd-view-active").removeClass("jb-nwfd-view-active");
        //Mettre sur new
        $(o).addClass("jb-nwfd-view-active");
        
        /* Vérifier, récupérer et afficher les données */
        var th = this, id;
        clearTimeout(id);
        id = setTimeout(function(){
            //Lancement de la vérification de nouveaux Articles
            th.Sentinel(b1);

            // Module NOoNE
            th.Noone();

            //Mise à jour de la configuration de NewsFeed
            th.UpdateComposStatus();
        },this._GetDefaultValues().__NWFD_BF_LOAD);
        
    };
    
    /******************* MENUS MANAGEMENT ****************/
    
    this.MenuHover = function (o) {
        //o = objet actif
        if ( KgbLib_CheckNullity(o) || KgbLib_CheckNullity($(o).data("target")) )
            return;
        
        //csl = class_selector
        var csl = ["nwfd-h-menu-team-hover","nwfd-h-menu-comy-hover","nwfd-h-menu-bzfeed-hover"],
                t = $(o).data("target");
        
        switch (t) {
            case "team" :
                    if ( !$(o).hasClass("nwfd-menu-active") )
                        $(o).toggleClass(csl[0]);
                    
                    $(".nwfd-h-menu-elt[data-target='comy']:not(.nwfd-menu-active)").removeClass(csl[1]);
                    $(".nwfd-h-menu-elt[data-target='bzfeed']:not(.nwfd-menu-active)").removeClass(csl[2]);
                break;
            case "comy" :
                    if ( !$(o).hasClass("nwfd-menu-active") )
                        $(o).toggleClass(csl[1]);
                    
                    $(".nwfd-h-menu-elt[data-target='team']:not(.nwfd-menu-active)").removeClass(csl[0]);
                    $(".nwfd-h-menu-elt[data-target='bzfeed']:not(.nwfd-menu-active)").removeClass(csl[2]);
                break;
            case "bzfeed" :
                    if ( !$(o).hasClass("nwfd-menu-active") )
                        $(o).toggleClass(csl[2]);
                    
                    $(".nwfd-h-menu-elt[data-target='team']:not(.nwfd-menu-active)").removeClass(csl[0]);
                    $(".nwfd-h-menu-elt[data-target='comy']:not(.nwfd-menu-active)").removeClass(csl[1]);
                break;
        }
    };
    
    this._PerformSwitchToMenu = function (o,nt,csl,ot,cv) {
        //o = object; nt = NewTarget, csl = class, ot = OldTarget, cv = ContraryView
//        alert(c);
        
//        if ( $(".nwfd-h-menu-elt.nwfd-menu-active").length ) 
        if (! KgbLib_CheckNullity(ot) ) {
            //On désactive l'ancien Menu
            var $tp =$(".nwfd-h-menu-elt.nwfd-menu-active");
            $tp.removeClass("nwfd-menu-active");
            //On lui enlève la signature bottom
            $tp.removeClass(csl[ot].mh);
        }
        
        /* On active le menu */
        //On active 
        $(o).addClass("nwfd-menu-active");
        //On ajoute la signature bottom
        $(o).addClass(csl[nt].mh);
        
        /* On affiche le bloc correspondant au menu */
        //omb = OldMenuBlock; nmb = NewMenuBlock
        var $omb = $(Kxlib_ValidIdSel(csl[ot].mb)), $nmb = $(Kxlib_ValidIdSel(csl[nt].mb));
        //On cache l'ancien bloc
        $omb.addClass("this_hide");
        //On affiche le nouveau bloc
        $nmb.removeClass("this_hide");
        
        /* On s'assure que les bloc de la Vue contraire soit fermée. Le bon menu sera sélectionné à l'ouveerture */

        $(Kxlib_ValidIdSel(cv)).find(".jb-nwfd-view-bloc").addClass("this_hide");
        
        //On lance la vérification pour voir s'il y a de nouveaux Articles
        
        //On enlève l'annotation (x nouveaux éléménts si elle existe
    };
    
    this.SwitchMenu = function (o) {
        
        if ( KgbLib_CheckNullity(o) || KgbLib_CheckNullity($(o).data("target")) )
            return; //Afficher ET/OU envoyer au serveur une erreur
        
        //t = target
        var nt = $(o).data("target"), ot = $(".nwfd-h-menu-elt.nwfd-menu-active").data("target");
//        alert("NewTarget => "+nt+"OldTarget => "+ot);
        
        //On prépare les données en fonction de la configuration actuelle de NewsFeed
        //csl = ClassSeLector; mh = MenuHover; mb = MenuBloc
        var csl;
        var d = this._GetComposStatus();
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
                    this._PerformSwitchToMenu(o,nt,csl,ot,cv);
                break;
        }
        
        //On sauvegarde le nouveau bloc pour ne pas se tromper
        var b1 = this.GetActiveMenuBloc2();
//        return;
        
        /* Vérifier, récupérer et afficher les données */
        //On attends x secondes pour lancer la procédure. Pour éviter de lancer la procédure alors que l'user ...
        // ... a changé de Menu avant même de commencer
        var th = this, id;
        clearTimeout(id);
        id = setTimeout(function(){
                th.Sentinel(b1);

                /* Module NOoNE */
                th.Noone();
                
                /* Mettre à jour les parmètres */
                th.UpdateComposStatus();
        },this._GetDefaultValues().__NWFD_BF_LOAD);
        
    };
    
    /*****************************************************************************************************************************************************************/
    /********************************************************************* VIEW SCOPE ********************************************************************************/
    /*****************************************************************************************************************************************************************/
    
    /******************** DATAS DISPLAYING MANAGEMENT ********************/
    this.ShowArtBar = function (o) {
        if ( KgbLib_CheckNullity(o) ) return; 
        
        //TODO : Vérifier si l'objet est conforme
        
        //Prepare les données au niveau de la barre
        var $ob = this._PrepareMozBarDatas(o);
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
                } catch (e) {
                    Kxlib_DebugVars([e]);
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
                } catch (e) {
                    Kxlib_DebugVars([e]);
                }

                //*/
            },200);
        }
    };
    
    this._PrepareArtListMode = function (v) {
        
        if ( KgbLib_CheckNullity(v) )
            return;
        
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
         * 
         ***/
        //On remplace toutes les valeurs de type null par "" (void)
        v = Kxlib_ReplaceIfUndefined (v);
        /*
         * Ici on crée aussi bien des modeles pour ITR que IML. Aussi, il faut être précautionneux sur les propriétés faisant référence à TR.
         * Aussi, pour les valeurs litigieuses ont s'assurent qu'elles existent. Sinon on les remplacent. Sinon l'utilisateur verait apparaitre 'undefined' quelque part. Ca fait bu d'amateur.
         */
        var theme;
        if (! KgbLib_CheckNullity(v.art_trd_eid) ) {
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
        
        v.art_evals = v.art_evals.toString().split(",");
            
        try {
            var t = "<div id=\"nf-el-lt-" + v.art_eid + "\" class=\"nwfd-b-l-mdl-max jb-nwfd-art-mdl jb-unq-bind-art-mdl\" data-item=\"" + v.art_eid + "\" data-atype=\"" + theme.k + "\" ";
//            t += "data-cache=\"['" + v.art_eid + "','" + v.art_pic_rpath + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(v.art_desc)) + "','" + Kxlib_ReplaceIfUndefined(v.art_trd_eid) + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(v.art_trd_title)) + "','" + v.art_rnb + "','" + Kxlib_ReplaceIfUndefined(v.art_trd_href) + "','"+v.art.prmlk+"'],['" + v.art_crea_tstamp + "','" + "" + "'],['" + v.art_evals[0] + "','" + v.art_evals[1] + "','" + v.art_evals[2] + "','" + v.art_evals[3] + "','" + v.art_eval_lt[0] + "','" + v.art.eval_lt[1] + "','" + v.art_eval_lt[2] + "','" + v.art_eval_lt[3] + "'],['" + v.art_oeid + "','" + v.art_ofn + "','" + Kxlib_ValidUser(v.art_opsd) + "','" + v.art_oppic_rpath + "','" + v.art_ohref + "'],['" + Kxlib_ReplaceIfUndefined(v.art_me) + "']\" ";
            t += "data-cache=\"['" + v.art_eid + "','" + v.art_pic_rpath + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(v.art_desc)) + "','" + Kxlib_ReplaceIfUndefined(v.art_trd_eid) + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(v.art_trd_title)) + "','" + v.art_rnb + "','" + Kxlib_ReplaceIfUndefined(v.art_trd_href) + "','"+""+"'],['" + v.art_crea_tstamp + "','" + "" + "'],['" + v.art_evals[0] + "','" + v.art_evals[1] + "','" + v.art_evals[2] + "','" + v.art_evals[3] + "','" + "" + "','" + "" + "','" + "" + "','" + "" + "'],['" + v.art_oeid + "','" + v.art_ofn + "','" + Kxlib_ValidUser(v.art_opsd) + "','" + v.art_oppic_rpath + "','" + v.art_ohref + "'],['" + Kxlib_ReplaceIfUndefined(v.art_me) + "']\" ";
            t += ">";
            t += "<div class=\"nwfd-b-l-mdl-hdr\">";
            t += "<div class=\"nwfd-b-l-mdl-h-time\">";
            t += "<span class='kxlib_tgspy nwfd-tgspy-css' data-tgs-crd=\"" + v.art_crea_tstamp + "\" data-tgs-dd-atn=\"" + "" + "\" data-tgs-dd-uut=\'\'>";
            t += "<span class='tgs-frm'></span>";
            t += "<span class='tgs-val'></span>";
            t += "<span class='tgs-uni'></span>";
            t += "</span>";
            t += "</div>";
            t += "<div class=\"nwfd-b-l-mdl-h-bdg\" data-thcode=\"" + theme.k + "\">";
            t += "<span class=\"nwfd-art-l-bdg-in\">in</span>";
            t += "<span class=\"\">" + theme.v + "</span>";
            t += "</div>";
            t += "</div>";
            t += "<div class=\"nwfd-b-l-mdl-body\">";
            t += "<div class=\"nwfd-b-l-mdl-b-box\">";
            t += "<a class=\"nwfd-b-l-mdl-b-box-box\" href=\"\">";
            t += "<img class=\"nwfd-b-l-mdl-b-box-img\" width=\"500\" height=\"500\" src=\"" + v.art_pic_rpath + "\" />";
            t += "<span class=\"nwfd-b-l-mdl-b-box-fade\"></span>";
            t += "<span class=\"nwfd-b-l-mdl-b-box-specs\">";
            t += "<span class=\"nwfd-b-l-mdl-react\" data-cache=\"" + v.art_rnb + "\">";
            t += "<span class=\"jb-unq-react\">" + v.art_rnb + "</span>";
            t += "<span>coms</span>";
            t += "</span>";
            t += "<span class=\"nwfd-b-l-mdl-eval jb-csam-eval-oput\" data-cache=\"[" + v.art_evals[0] + "," + v.art_evals[1] + "," + v.art_evals[2] + "," + v.art_evals[3] + "]\">";
            t += "<span>" + v.art_evals[3] + "</span>";
            t += "<span>coo<i>!</i></span>";
            t += "</span>";
            t += "</span>";
            t += "</a>";
            t += "</div>";
            t += "</div>";
            t += "<div class=\"nwfd-b-l-mdl-ftr\">";
            t += "<div class=\"nwfd-b-l-mdl-ftr-stdby\">";
            t += "<p class=\"nwfd-b-l-mdl-ftr-trtitle this_hide\"><a class=\"nwfd-b-l-mdl-ftr-tr-go\" data-cache=\"[" + v.art_trd_eid + "," + v.art_trd_title + "]\" href=\"" + v.art_trd_href + "\">" + v.art_trd_title + "</a></p>";
            t += "</div>";
            t += "<p class=\"nwfd-b-l-mdl-ftr-desc\">";
//            t += "" + Kxlib_Decode_After_Encode(v.art_desc) + "";
            t += "</p>";
            t += "<div class=\"nwfd-b-l-mdl-ftr-ftr\">";
            t += "<div class=\"nwfd-b-l-mdl-ftr-user\">";
            t += "<a class=\"nwfd-b-l-mdl-u-box\" data-cache=\"[" + v.art_oeid + "," + v.art_ofn + "," + v.art_opsd + "," + v.art_oppic_rpath + "]\" href=\"" + v.art_ohref + "\">";
            t += "<img class=\"nwfd-b-l-mdl-u-img\" width=\"65\" height=\"65\" src=\"" + v.art_oppic_rpath + "\" />";
            t += "<span class=\"nwfd-b-l-mdl-u-psd\">@" + v.art_opsd + "</span>";
            t += "</a>";
            t += "</div>";
            /*
            t += "<div class=\"nwfd-b-l-mdl-ftr-specs\">";
            t += "<div class=\"jb-csam-eval-box css-eval-box css-eval-box-tmlnr css-eval-box-nwfd clearfix\">";
            t += "<span class=\"jb-csam-eval-oput css-csam-eval-oput\" data-cache=\"[" + v.art_evals[0] + "," + v.art_evals[1] + "," + v.art_evals[2] + "," + v.art_evals[3] + "," + v.art_me + "]\"><span>" + v.art_evals[3] + "</span> coo<i>!</i></span>";
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
            t += "</div>";
            
            var e = $.parseHTML(t);
            
            $(e).find(".nwfd-b-l-mdl-ftr-desc").text(Kxlib_Decode_After_Encode(v.art_desc));
            
            //TODO : Selon le type (itr; iml) on change l'entete visible + On change le data-atype + titre TR
            //t += "<p class=\"nwfd-b-l-mdl-ftr-trtitle this_hide\"><a class=\"nwfd-b-l-mdl-ftr-tr-go\" data-cache=\"["+v.trid+","+v.trtitle+"]\" href=\""+v.trhref+"\">"+v.trtitle+"</a> </p>";
            if (! KgbLib_CheckNullity(v.art_trd_eid) ) {
                
                var c = "[" + v.art_trd_eid + "," + v.art_trd_title + "]";
                $(e).find(".nwfd-b-l-mdl-ftr-tr-go").data("cache", c);
                $(e).find(".nwfd-b-l-mdl-ftr-tr-go").prop("href", v.art_trd_href);
                $(e).find(".nwfd-b-l-mdl-ftr-tr-go").text(v.art_trd_title);
                
                $(e).find(".nwfd-b-l-mdl-ftr-trtitle").removeClass("this_hide");
            } else {
                $(e).find(".nwfd-b-l-mdl-ftr-tr-go").data("cache", "");
                $(e).find(".nwfd-b-l-mdl-ftr-tr-go").prop("href", "");
                $(e).find(".nwfd-b-l-mdl-ftr-tr-go").text("");
                
                $(e).find(".nwfd-b-l-mdl-ftr-trtitle").addClass("this_hide");
            }
            
            //TODO : retourner le bloc
            return e;
        } catch (ex) {
//            alert(ex.message);
            return;
        }

    };
    
    this._PrepareArtListMode_BindHandler = function (a) {
        if ( KgbLib_CheckNullity(a) )
            return;
        
        //Bind pour faire marcher le hover
        $(a).find(".nwfd-b-l-mdl-b-box-box").hover(this.Handler_HoverListElt);
                
        //Bind Unique 
        $(a).find(".nwfd-b-l-mdl-b-box-box").click(function(e){
            Kxlib_PreventDefault(e);
        
            (new Unique()).OnOpen("nwfd",this);
        });
                 
        return a;
    };
    
    
    this.DisplayDatasListMode = function (d,mb,isn) {
        //d = tableau d'objets de datas, isn = IsNewarticles , permet de changer la façon dont les images seront ajoutées dans le bloc.
        if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(mb) ) 
            return;
        
        //*
//           alert("COBRA ! "); 
//           alert("COBRA ! "+$(mb).attr("id")); 
        var th = this, cn = 0, ft = $(mb).find(".jb-nwfd-art-mdl:first");;
        $.each(d,function(i,v) {
//            setTimeout(function(){
                     
                //On s'assure que l'élément n'est pas déjà présent dans la zone. Sinon on annule son ajout
                if ( th._CheckEltNoExsitsInList(mb,v) ){
                    return;
                }
                
                //On s'assure que la limite des Articles n'a pas été atteinte
                var tl = $(mb).find(".jb-nwfd-art-mdl").length + 1;
                if ( tl > th._GetDefaultValues().__NWFD_LIST_MAXNB ) {
                    return;
                }
                
                //On construit le bloc
                var b = th._PrepareArtListMode(v);

                //On lie les éléments clés
                b = th._PrepareArtListMode_BindHandler(b);
//                alert(b);
//                return;
                /* On ajoute les blocs dans le body */
                var $o = $(mb);
                
//                $(b).hide().insertAfter($o.find(".jb-nwfd-menu-rcl")).slideDown(1000);

                if ( !isn ) {
                    var c = $($o).find(".jb-nwfd-art-mdl").length, $l = $($o).find(".jb-nwfd-art-mdl:last");
                
//                Kxlib_DebugVars([c],true);
//                Kxlib_DebugVars([$l],true);
//                Kxlib_DebugVars([$o.attr("id")],true);
                
                    if ( c ) {
                        $(b).hide().insertAfter($l).slideDown(1000);
                    } else {
                        $(b).hide().insertAfter($o.find(".jb-nwfd-menu-rcl")).slideDown(1000);
                    }
                } else {
                    
                    if (! $(ft).length ) {
                        return false;
                    }
                    /*
                     * Cette manière d'insérer les éléments dépend de l'ordre selon lequel les données ont été triées au niveau du serveur.
                     * C'est bien pou cela qu'un changement au niveau des serveurs DOIT entrainer un chagement du mode d'insertion à ce niveau.
                     */
                    $(b).hide().insertBefore(ft).slideDown(1000);
                    
                }
                

                /* Mettre à jour le module NOoNE */
                th.Noone();    
//            },1000);
                
//                if ( $($o).find(".jb-nwfd-art-mdl").length === Kxlib_ObjectChild_Count(d) ) {
//                    $($o).data("ulk",0);
//                }
                
//                Kxlib_DebugVars([Dans la zone : "+$($o).find(".jb-nwfd-art-mdl").length]);
//                Kxlib_DebugVars([Articles : "+Kxlib_ObjectChild_Count(d)]);
        });
        //On libère la zone
        $(mb).data("ulk",0);
        
        //*/
//        Kxlib_DebugVars([A la fin => "+$(mb).data("ulk")]);
//        Kxlib_DebugVars([$($o).find(".jb-nwfd-art-mdl").length],true);
//        Kxlib_DebugVars([$(b).data("ulk")],true);
        
    };
    
    this.DisplayPredateDatasListMode = function (d,mb) {
        //d = tableau d'objets de datas
        if ( KgbLib_CheckNullity(d) || KgbLib_CheckNullity(mb) ) 
            return;
        
        //*
//           alert("COBRA ! "+mb.attr("id")); 
        var th = this;
        $.each(d,function(i,v){
            setTimeout(function(){
                //On s'assure que l'élément n'est pas déjà présent dans la zone. Sinon on annule son ajout
                if ( th._CheckEltNoExsitsInList(mb,v) )
                    return;
                
                //On construit le bloc
                var b = th._PrepareArtListMode(v);

                //On lie les éléments clés
                b = th._PrepareArtListMode_BindHandler(b);
    //            alert(b);
    //            return;
                
                /* On ajoute les blocs dans le body */
                //l = last
                var $o = mb;
                var c = $($o).find(".nwfd-b-l-mdl-max").length, $l = $($o).find(".nwfd-b-l-mdl-max:last");
//                alert("OWN => "+$l.length);
                if ( c )
                    $(b).hide().insertAfter($l).slideDown(1000);
                else
                    $(b).hide().insertAfter($o.find(".jb-nwfd-menu-rcl")).slideDown(1000);

                /* Mettre à jour le module NOoNE */
                th.Noone();    
            },1000);
        });
        //*/
        
    };
    
    
    /******************* MOZ *******************/
    
    this._PrepareArtMozMode = function (d,l) {
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
         * 
         * * */
        if ( KgbLib_CheckNullity(d) ) return;
        
        d = Kxlib_ReplaceIfUndefined(d);
//        alert("YO1 => "+d.art.msg);
//        alert("YO2 => "+Kxlib_EscapeForDataCache(d.art.msg));
        try {
            var r = "";
            r += "<div id=\"nf-el-mz-" + d.art.id + "\" class=\"nwfd-b-moz-mdl-max jb-nwfd-art-mdl jb-unq-bind-art-mdl jb-eval-bind-moz\" data-item=\"" + d.art.id + "\" data-atype=\""+d.art.theme[0]+"\" ";
            r += "data-cache=\"['" + d.art.id + "','" + d.art.img + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(d.art.msg)) + "','" + Kxlib_ReplaceIfUndefined(d.art.trid) + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(d.art.trtitle)) + "','" + d.art.rnb + "','" + d.art.trhref + "','"+d.art.prmlk+"'],['" + d.art.time + "','" + d.art.utc + "'],['" + d.art.eval[0] + "','" + d.art.eval[1] + "','" + d.art.eval[2] + "','" + d.art.eval[3] + "','" + d.art.eval_lt[0] + "','" + d.art.eval_lt[1] + "','" + d.art.eval_lt[2] + "','" + d.art.eval_lt[3] + "'],['" + d.user.ueid + "','" + d.user.ufn + "','" + Kxlib_ValidUser(d.user.upsd) + "','" + d.user.uppic + "','" + d.user.uhref + "'],['" + Kxlib_ReplaceIfUndefined(d.art.myel) + "']\" ";
            r += ">";
            r += "<span class=\"nwfd-b-moz-mdl-grpdate this_hide\" data-time=\"" + d.art.time + "\">[Date]</span>";
            r += "<div class=\"nwfd-b-moz-mdl-dome\">";
            r += "<div>";
            r += "<a class=\"nwfd-b-m-mdl-trig\" data-grp=\"" + d.grp + "\" data-grid=\"[" + l + "," + 0 + "]\" data-time=\"" + d.art.time + "\" data-cache=\"['" + d.user.ueid + "','" + d.user.ufn + "','" + d.user.upsd + "','" + d.user.uppic + "','" + d.user.uhref + "'],['" + d.art.theme[0] + "','" + d.art.theme[1] + "'],['" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(d.art.msg)) + "'],['" + d.art.eval[0] + "','" + d.art.eval[1] + "','" + d.art.eval[2] + "','" + d.art.eval[3] + "'],['" + d.art.rnb + "'],['" + Kxlib_ReplaceIfUndefined(d.art.trid) + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(d.art.trtitle)) + "']\" href=\"\">";
            r += "<img class=\"nwfd-b-m-mdl-pic\" width=\"150\" height=\"150\" src=\"" + d.art.img + "\"/>";
            r += "<!--<span class=\"nwfd-b-m-mdl-pic-fade\"></span>-->";
            r += "<span class=\"nwfd-b-m-mdl-pic-fade\">";
            r += "<!--<span class=\"nwfd-b-m-mdl-pic-tr\">Trend</span>-->";
            r += "</span>";
            r += "</a>";
            r += "</div>";
            r += "<div class=\"nwfd-b-moz-art-specs \">";
            
            r += "<span class=\"nwfd-b-moz-art-ss-react\">";
            r += "<span class=\"jb-unq-react\">" + d.art.rnb + "</span>";
            r += "<span><i>co</i></span>";
            r += "</span>";
            r += "<span class=\"nwfd-b-moz-art-ss-eval\">";
            r += "<span>" + d.art.eval[3] + "</span>";
            r += "<span>c<i>!</i></span>";
            r += "</span>";
            r += "</div>";
            r += "<div class=\"this_hide\">";
            r += "<span class='kxlib_tgspy' data-tgs-crd='"+d.art.time+"' data-tgs-dd-atn='' data-tgs-dd-uut=''>";
            r += "<span class='tgs-frm'></span>";
            r += "<span class='tgs-val'></span>";
            r += "<span class='tgs-uni'></span>";
            r += "</span>";
            r += "</div>";
            r += "</div>";
            r += "</div>";
                    
            return $.parseHTML(r);
        } catch (e) {
            Kxlib_DebugVars([e],true);
        }
    };
    
    this._GetMozLineId = function ($fln,g,$mb,pd) {
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
                    if (pd)
                        $pcd = $($mb).find(".nwfd-b-moz-line:last");
                    else
                        $pcd = $($mb).find(".nwfd-b-moz-line:eq(0)");
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
        } catch (e) {
            Kxlib_DebugVars([e], true);
        }
    };
    
    this._FulfilMozLine = function ($fln, flnb, d, mb, pc, pd) {
        //fln = FirstLine, d = tableau de données, mb = MenuBloc => Utiliser pour s'assurer que les éléments ne sont pas ajoutés en doublon
        //pc = PerfectCase => Cas où tous les éléments à ajouter suffisent dans une seule ligne. Entraine de devoir afficher la date sur le premier élément
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
        if ( KgbLib_CheckNullity(d) ) return;
        
        //Si on a pas accès à la première ligne on la crée. Cela pour éviter des erreurs stupides
        if ( KgbLib_CheckNullity($fln) )
            $fln = $(".nwfd-b-moz-line:first").find(".group_of_4"); 
        
        /* On ne récupère que le nombre d'Articles qui manque pour 'remplir' la ligne */
        //Calcule du nombre d'éléments à insérer
        var nb = 4 - flnb;
//        alert(nb);
//        return;
        //Copie des éléménts à insérer suivant le nombre précisé ci-dessus
        var tptb = new Array();
        $.grep(d, function(el,ix){
            if ( ix < nb )
                tptb.push(el);
        });
//        alert(tptb.length);
        
        //On récupère l'identifiant de la ligne. Pour le groupe on prend nimporte lequel des éléments et on prend son groupe
        var fooo = this._GetComposStatus();
        fooo = fooo.menu;
        fooo += tptb[0].grp;
//        Kxlib_DebugVars(["OURS => "+$(this._GetActiveMenuBloc()).html()],true);
        var lnid = this._GetMozLineId($fln.closest(".nwfd-b-moz-line"),fooo,this._GetActiveMenuBloc(),pd);
//         Kxlib_DebugVars([lnid],true);       
        //Dans tous les cas, on inscrit le code LIRK. 
        $fln.closest(".nwfd-b-moz-line").data("lirk",lnid);
                
        /* Dans tous les cas, On met à jour le code LIRK sur la barre */
        var i = "lirk-"+lnid;
        $fln.closest(".nwfd-b-moz-line").find(".nwfd-b-moz-l-bar").attr("id",i);
        
//        alert(lnid);
        
        /* Insertion des éléments */
        var th = this;
        $.each(tptb,function(i,v){
                /* On construit le modèle */
                var b = th._PrepareArtMozMode(v,lnid);
                
                //Recréer les bind() JavaScript
                b = th._PrepareArtMozMode_BindHandler($(b),$fln.closest(".nwfd-b-moz-line"));
//                alert(b);
//                return;
                //On ajoute les blocs dans le body
                $(b).hide().prependTo($fln).fadeIn(100);
                
                /* Mettre à jour le module NOoNE */
                th.Noone();    
                
        });
        
        //[18-16-14] On vérifie si on est dans le cas du perfectCase
        if ( pc ) this._WriteDateOnFirstInMoz($fln);
        
        /* 
         * On revoie le tableau "amputé" des éléments ajoutées à la ligne.
         * Cela permet de passer par un autre mode de traitement de données.
         * Normalement, les données seront utilisées pour créer des lignes 
         */
        //On enlève les éléments
        d.splice(0,tptb.length);
        
        return [d,$fln];
    };
    
    this._WriteDateOnFirstInMoz = function ($ln,pd) {
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
    
    this._CreateMozLine = function () {
        
        var e = "";
        e += "<div class=\"nwfd-b-moz-line\" data-lirk=\"\">";
        e += "<div class=\"nwfd-b-moz-l-list\">";
        e += "<div class=\"group_of_4\">";
        e += "</div>";
        e += "<div id=\"\" class=\"nwfd-b-moz-l-bar jsbind-b-moz-l-bar\">";
        e += "<div class=\"nwfd-b-mz-l-b-specs\">";
        e += "<div class=\"nwfd-b-mz-l-b-s-specs\">";
        e += "<span class=\"nwfd-b-mz-l-b-s-s-eval\">";
        e += "<!--<img src=\"\"/>-->";
        e += "<span class=\"nwfd-moz-eval-val\">0</span>";
        e += "<span>&nbsp;coo!</span>";
        e += "</span>";
        e += "<span class=\"nwfd-b-mz-l-b-s-s-react\">";
        e += "<!--<img src=\"\"/>-->";
        e += "<span class=\"nwfd-moz-react-val\">0</span>";
        e += "<span>&nbsp;coms</span>";
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
        } catch (e) {
            
        }
    };
    
    this._PrepareArtMozMode_BindHandler = function (a,l) {
        //a = L'élément individuel dans la grille MOZ; l = la liste pour rechercher la barre
        if ( KgbLib_CheckNullity(a) )
            return;
       
        //Bind pour affihcer la MozBar
        $(a).find(".nwfd-b-m-mdl-trig").hover(gth.Handler_ShowMozBar);
        //Bind pour lock la barre lorsque la souris est au-dessus
        $(l).find(".jsbind-b-moz-l-bar").on('mouseleave',gth.Handler_HoldMozBar);
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
    
    this._CreateNdFulfilMozLine = function ($fln, flnb, d, mb, pd, rcs) {
        //pd = PreDate; rcs = Recusive => Sert surtout pour le chargement des éléments antérieurs
        if ( KgbLib_CheckNullity(d) ) return;

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
        if ( flnb > 0 && flnb < 4 ) {
//            alert("Check !");
            //On vérifie si on nous a envoyé la dernière ligne. Sinon, on sort
            if (! KgbLib_CheckNullity($fln) ){
                var r0 = this._FulfilMozLine($fln, flnb, d,mb,pd);
                nd = r0[0];
            }
            else //TODO : Récupérer le dernière ligne et continuer
                return;
        }
            
        /* Création de la nouvelle ligne */
        var $ln;
        try {
            $ln = $(this._CreateMozLine());
        } catch (e) {
            //TODO: Send error to Server
            Kxlib_DebugVars(["In newsfeed_handler:1216 ",e],true);
            return;
        }

        /* Insertion des données dans la nouvelle ligne. On récupère le reste des éléments à charger */
        var r1 = this._FulfilMozLine($ln.find(".group_of_4"), 0, nd, mb, null, pd);
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
        if ( $fst.length ) {
            if (! pd ) 
                $($ln).hide().insertBefore($fst).fadeIn(1);
            else 
                $($ln).hide().insertAfter($lst).fadeIn(1);
        } else {
            $($ln).hide().appendTo(bar).fadeIn(1);
        } 
        
        //Dans le cas d'un ajout en mode PreDate, On fait apparaitre la date sur le premier élément ajouter du groupe
        if ( pd && (rcs === false || typeof rcs === "undefined") )
            this._WriteDateOnFirstInMoz($ln);
        
        
        /* Mettre à jour le module NOoNE au cas où on avait aucun article */
        this.Noone();   
//        return;
        //On vérifie s'il reste encore des éléments succeptibles d'être ajoutés
        if ( nd.length > 0 ) {
            this._CreateNdFulfilMozLine(null,0,nd,mb,pd,true);
        } else {
            /* On affiche la date au niveau du premier élément si nous ne sommes pas dans le cas d'un PreDate */
            if (! pd )
                this._WriteDateOnFirstInMoz($ln);
            
            /* On fait de telle sorte que la ou les barres soit accessibles sans quoi l'animation ne marcherait pas */
            //this._PrepareMozBarFromAnim($el.data("time"));
            
             //return;
            //Fin de la recusivité. Toda pasais bien (semblant d'espagnol)
            return 1;
        }
    };
    
    this._SortOneDayAGroup = function (mb,d) {
        
        /* 
         * RAPPEL : Les données arrives sous forme d'une liste d'objets. Il ne sont pas rangés dans des sous-tableaux.
         * 
         * RULES :
         * - La méthode revoie un tableau contenant des tableaux dont chacun représente un jour 
         * - Les tableaux sont triés de telle sorte que la date le mois récente soit ait l'index le plus faible
         * - Si tous les éléments ne correspondent qu'à une seule journée, la méthode ne renverra qu'un tableau
         * - Chaque tableau a un id de 'type' date. Exemple, les éléments pour la journée de 5 Juin 14 aura l'id (050614)
         * 
         * PROTOCOLE avec SERVER : 
         * - Les éléménts sont rangés et groupés par date. Des plus récents au plus anciens.
         * - Ce qui fait que "normalement", ils le resteront au niveau de l'objet renvoyé. ( A tester, 09-06-14) 
         * */
         if ( KgbLib_CheckNullity(d) ) return;
        
        var r = new Object(), th = this;
//         alert(Kxlib_ObjectChild_Count(d));
//         alert(typeof d);
         $.each(d, function(x,v) {
             //On s'assure que l'élément n'existe pas déjà. Sinon on ne l'ajout pas 
//             alert(v.time);
//             return;
            if ( th._CheckEltNoExsitsInMoz(mb,v) ) return;
            
             /* Création de l'id à partir de la date de création fournie avec l'élément */
             
             //[NOTE au  09-06-14] Utliser la date comme id unique est faux car si on veut trier on aura des problèmes
             //Vaut mieux prendre timestamp
             /*
             var t = parseInt(v.time);
             var dt = new KxDate(t);
             
             var id = dt.getDate().toString();
             id += dt.getMonth().toString();
             id += dt.getYear().toString();
             //*/
             
             //var id = v.time; //Non car la partie Time fait que pour la même journée on a deux blocs s'ils ont des TIME (hours, mins ou secs) différents
             
             var tpd = new KxDate(parseInt(v.art.time));
             tpd.SetUTC(true);
             var id = tpd.RemoveTimeFromTSTP();
//             alert(id);
             //On ajoute l'élément grp qui permettra de les identifier lors de leur insertion
             v.grp = id;
             
             //Si un tableau du jour correspondant à celle de l'élément existe, on ajoute à ce tableau ...
             if ( r.hasOwnProperty(id) ) {
                 r[id].push(v);
//                 alert("Pour "+ix+" avec "+id+" => Push");
             } else {
                 //... Sinon on crée un nouveau tableau et on l'insère à l'objet
                 var nt = new Array();
                 nt.push(v);
                 r[id] = nt;
//                 alert("Pour "+ix+" avec "+id+" => New");
             } 
         });
         
         //On va placer les éléments de telle sorte qu'ils soient triés par ordre décroissant
         var nms = new Array();
         nms = Object.getOwnPropertyNames(r).sort();
         
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
         var rt = [nms,r];
         
         return rt;
    };
    
    
    this.DisplayDatasMozMode = function (d,mb,rcs,pd) {
        
        /* Il faut s'accrocher, maux de cranes assurés */
        //rcs  = Recursive. Cette méthode peut être appelé de façon recursive        
        //d = tableau d'objets de datas; rcs = recursive, permet à la méthode de comprendre que le Caller c'est lui même
        // pd = Predate => Est ce qu'on veut afficher les anciens résultats
        if ( KgbLib_CheckNullity(d) ) return;
        
        //y = Correspond au jour lié aux éléments. C'est aussi le numéro de ligne avant changement
        //gr = GlobalReturn = Utiliser pour envoyer les données à la prochaine méthode
        var y, gr;
        if ( KgbLib_CheckNullity(rcs) ) {
            
            /* On va trier le tableau de données pour permettre qu'à chaque ligne corresponde un jour */
            gr = this._SortOneDayAGroup(mb, d);
            
            //On récupère les deux tableaux (1) les indices triés (2) les valeurs
            var nms = gr[0];
            var datas = gr[1]; 
            
            //On sélection le premier élément pour lancer la procédure
            var y = nms[0];
            d = datas[y];
            
        } else {
            
            /* On supprime l'élément déjà traité de telle sorte que le prochain process traite un nouvel élément */
            var n2 = d[0];
            //Rappel d2 un objet et non un tableau
            var d2 = d[1];
            var y = n2[0];
            
            //On supprime l'élément déjà traité ET son indice
            delete d2[y];
            n2.splice(0,1);
            
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

            } else return;
        }
        
        /* Selon le cas : La dernière ligne n'est pas pleine OU l'est */
        var fo = mb;
        var $fln;
        if ( KgbLib_CheckNullity(fo) )
            return;
        else {
            $fln = $(fo).find(".nwfd-b-moz-line:first").find(".group_of_4");
        }
        
        //isf = IsFull, (false) ce qui signifie que la dernière ligne n'a pas les 4 éléments pour être pleine
        var isf = false, flnb = $fln.find(".nwfd-b-moz-mdl-max").length;
        
        //DEBUG
        /*
        alert($fln.html());
        alert($(".nwfd-b-moz-line:eq(0)").find(".nwfd-b-m-mdl-trig").length);
        return;
        //*/
        
        //Vérification si la dernière est pleine
        isf = ( flnb === 4 || flnb === 0 ) ? true : false;
        /* Lorsque flnb === 0 cela signifie qu'il n'y a aucun élément disponible */
//        alert("Et ça continue => "+isf);
        
        //On verifie si les éléments de la dernière ligne ont la même date que ceux à ajouter
        //frnbd = FoRceNewByDate; Signifie qu'il faut obliger à la création d'une nouvelle ligne à cause de la différence de date
//        var frnbd = true;
        if (! KgbLib_CheckNullity($fln) )
            var frnbd = ( $fln.find(".nwfd-b-m-mdl-trig:first").data("grp").toString() === y ) ? false : true;
        
//        alert("Derniere ligne => "+$fln.find(".nwfd-b-m-mdl-trig:first").data("grp")+"; Ligne à ajouter => "+y);
//        alert("Freely => "+frnbd);
//        Kxlib_DebugVars([isf,frnbd],true);
        if ( !isf && !frnbd ) {
//            alert(isf);
            //Vérification si on est dans le cas parfait où on veut ajouter des éléments sans devoir créer une nouvelle ligne
            if ( ( Kxlib_ObjectChild_Count(d)+flnb ) <= 4 ) {
//                alert("Check In -> "+d.length);
                /* On ajoute les éléments à la ligne */
                
                /* Si on est ici c'est parce qu'on a fill une ligne dans un cas parfait.
                 * Cependant, il ne faut pas oublier d'afficher la date sur le premier élément de cette ligne.
                 * Voilà pourquoi on signale à la méthode que nous sommes dans ce cas 5eme arg (true)
                /* * */
                var fo = this._FulfilMozLine($fln, flnb, d, mb, true);
//                alert("Check Out -> "+ak[0].length);
            } else {
                //On demande la création recursive des lignes tant qu'on a des éléments à ajouter  
                this._CreateNdFulfilMozLine($fln, flnb, d, mb, pd);
            }
        } else {
//                alert("Cas de la Nouvelle Ligne");
//               return;
            if ( frnbd || KgbLib_CheckNullity($fln) ) {
//                alert("Test");
                //On demande la création recursive des lignes tant qu'on a des éléments à ajouter.
                //Ici on force l'apparition d'une nouvelle ligne
                this._CreateNdFulfilMozLine(null, 0, d, mb, pd);
            } else {
                //On demande la création recursive des lignes tant qu'on a des éléments à ajouter  
                this._CreateNdFulfilMozLine($fln, flnb, d, mb, pd);
            }
        }
        
        //On relance le processus
        this.DisplayDatasMozMode(gr,mb,true,pd);
    };
    
    
    /************************ NWFD SLIDER *************************/
    
    this.NewPostTrigPan_Show = function (n) {
        /*
         * Permet d'afficher le Trigger.
         * La méthode recoit un nombre représentant le nombre d'Articles en "attente".
         * 
         * Si le trigger est déjà affiché, on update son nombre.
         */
        if ( KgbLib_CheckNullity(n) )
            return;
//        alert("2728 => "+$(".jb-nwfd-nptp-m").hasClass("this_hide"));
        //On vérifie se le trigger est affiché
        if ( $(".jb-nwfd-nptp-m").hasClass("this_hide") ) {
            $(".jb-nwfd-nptp-m").find(".jb-n-n-c-nb > span").text(n);
            $(".jb-nwfd-nptp-m").removeClass("this_hide");
        } else {
            $(".jb-nwfd-nptp-m").find(".jb-n-n-c-nb").text(n);
        }
        
        return true;
    };
    
    this.NewPostTrigPan_Kill = function () {
        /*
         * Permet de masquer le trigger et de le reset.
         * Cela signifie qu'il est remis à son état d'origine afin d'être disponible pour de futures actions.
         */
        $(".jb-nwfd-nptp-m").addClass("this_hide");
//        alert($(".jb-nwfd-nptp-m").attr("style"));
        $(".jb-nwfd-nptp-m").removeAttr("style");
//        alert($(".jb-nwfd-nptp-m").attr("style"));
        $(".jb-nwfd-nptp-m").addClass("zmout");
        
        $(".jb-nwfd-nptp-m").find(".jb-n-n-c-nb > span").text(0);
        
        $(".jb-nwfd-nptp-m").find(".bfmille").addClass("this_hide");
        $(".jb-nwfd-nptp-m").find(".jb-n-n-c-lib").addClass("this_hide");
    };
            
    this.NewPostTrigPanZmIn = function () {
        
        if ( $(".jb-nwfd-nptp-m").data("lk") === 1 ) 
            return;
        
        //On annule les animations
        $(".jb-nwfd-nptp-m").stop(true,true);
        
        //On fait "disparaitre" le ou les composants interieurs
        $(".jb-n-n-c-nb").addClass("this_hide");
        
        //Faire passer l'ensemble à ZmIn
        $(".jb-nwfd-nptp-m").removeClass("zmout",100);
        
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
            
    this.NewPostTrigPanZmOut = function () {
        
        if ( $(".jb-nwfd-nptp-m").data("lk") === 1 ) 
            return;
        
        //On annule les animations
        $(".jb-nwfd-nptp-m").stop(true,true);
        
        //On fait "disparaitre" le ou les composants interieurs (le temps de l'animation)
        $(".jb-n-n-c-nb").addClass("this_hide");
        $(".jb-n-n-c-lib").addClass("this_hide");
        
        //Faire passer l'ensemble à ZmOut
        $(".jb-nwfd-nptp-m").addClass("zmout",100);
        
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
    
    this._UpdateNwfdSidePanel = function () {
        var h = $(".jb-nwfd-body").height() - $(".jb-nwfd-sld-hdr").height() - $(".jb-nwfd-sld-ftr").height();
        
        //On affecte la taille au body
        $(".jb-nwfd-sld-body").height(h);
        
    };
    
    this.ShowNwfdSidePanel = function (x) {
        
        if ( KgbLib_CheckNullity(x) )
            return;
        
        //Cela permet de stopper l'animation en cours
        $(".jb-nwfd-body").stop(true,true);
        
        /*
         * Permet de faire apparaitre la zone de droite.
         */
        //On fige le déclencheur sur le statut actuel. Si tout se déroule normalement, il s'agit du cas où le déclencheur est développé
        $(".jb-nwfd-nptp-m").data("lk",1);
        
        //On calcule la taille de la zone principale
        this._UpdateNwfdSidePanel();
        
        /*
         * On masque certaines zones interieures de la zone pour les faire apparaitre en fadeIn par la suite.
         * Cela permet aussi de procéder au calcul de la hauteur de la zone principale en fonction des écrans.
         *
        //On masque la zone les éléments de la zone principale
        $(".jb-nwfd-sld-ctr").addClass("this_hide");
        //*/
        //On fait slider la zone cnetrale pour faire apparaitre zone d'infos
        $(".jb-nwfd-body").toggleClass("slided",500).promise().done(function() {
            //On signale la zone commme ouverte
            $(".jb-nwfd-slided-max").data("sts",1);
        });
        
        //*** On change le data-action ***//
        var rv = $(x).data("rev"), nw = $(x).data("action");
        
        $(x).data("action",rv);
        $(x).data("rev", nw);
        
    };
    
    this.HideNwfdSidePanel = function (x) {
        if ( KgbLib_CheckNullity(x) )
            return;
        
        //Cela permet de stopper l'animation en cours
        $(".jb-nwfd-body").stop(true,true);
        
        //On fait slider la zone cnetrale pour faire apparaitre zone d'infos
        var th = this;
        $(".jb-nwfd-body").toggleClass("slided",500).promise().done(function() {
            //On dézoom le Trigger
            th.NewPostTrigPanZmOut();
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
    
    this.NwfdSlidedNavUp = function () {
        /*
         * On simuler la sensation de passer de pages en pages, on change le top avec une valeur égale à la hauteur actuelle de la zone.
         * Dans ce cas on monte
         */
        //On annule toutes les autres animations
        $(".jb-nwfd-sld-bd-list").stop(true,true);
        
        var $w = $(".jb-nwfd-sld-bd-list");
        
        //On récupère la taille de la zone principale
        var h = $(".jb-nwfd-sld-body").outerHeight();
        
        var dn;
        
        if ( $w.position().top === 0 || $w.position().top > 0 ) {
            dn = 0;
        } else {
            dn = $w.position().top + h;
            
            //On va éviter que la fenetre ne monte trop loin
            if ( dn > 0 ) {
                dn = 0;
            }
        }
           
        //On slide pour déscendre
        $(".jb-nwfd-sld-bd-list").animate({
            "top": dn
        });
    };
    
    this.NwfdSlidedNavDown = function () {
        /*
         * On simuler la sensation de passer de pages en pages, on change le top avec une valeur égale à la hauteur actuelle de la zone.
         * Dans ce cas on descend
         */
        //On annule toutes les autres animations
        $(".jb-nwfd-sld-bd-list").stop(true,true);
        
        var $w = $(".jb-nwfd-sld-bd-list");
        
        //On récupère la taille de la zone principale
        var h = $(".jb-nwfd-sld-body").outerHeight();
        
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
           
        //On slide pour déscendre
        $(".jb-nwfd-sld-bd-list").animate({
            "top": dn
        });
    };
    
    this.NwfdSlidedNavFirst = function () {
        $(".jb-nwfd-sld-bd-list").animate({
            "top": 0
        });
    };
    
    this.NwfdSlide_CreateList = function (d) {
        /*
         * Permet de créer une liste comprennat les utilisateurs liés aux Articles à afficher.
         * La liste est faite à partir des données reçues (d).
         * 
         */
        var th = this;
        if ( KgbLib_CheckNullity(d) ) {
            return;
        }
        
        //On vide la liste
        $(".jb-nwfd-sld-bd-list").html("");
            
        $.each(d, function (i,v) {
            //On crée la vue de l'élément
            var e = th._NwfdSlide_CL_PrepareSingle(v);
//            Kxlib_DebugVars([e],true);
            
            //On ajoute l'élément. RAPPEL : C'est à CALLER de mettre en place un tri, si tri il  y a.
            $(".jb-nwfd-sld-bd-list").append(e);
        });
        
//        Kxlib_DebugVars([$(".jb-nwfd-sld-bd-list").html()],true);
        
        return true;
    };
    
    this._NwfdSlide_CL_PrepareSingle = function (d) {
        //ap = ArticelPivot; n : le nombre de publications liées
        
        if ( KgbLib_CheckNullity(d) ) {
            return;
        }
        
        var e = "<div class=\"nwfd-sld-mdl-max jb-nwfd-sld-mdl\" data-item=\"\">";
        e += "<div class=\"nwfd-sld-mdl-lz\">";
        e += "<a class=\"nwfd-sld-mdl-lz-ugrp\" href=\"/%uhref%/\">";
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
        $(e).find(".nwfd-sld-mdl-lz-ugrp").attr("href",d.ohref);
        $(e).find(".nwfd-sld-mdl-lz-ug-img").attr("src",d.oppic);
        $(e).find(".nwfd-sld-mdl-lz-ug-psd").text(d.opsd);
        $(e).find(".nwfd-sld-mdl-rz-pnb").text(d.pnb);
        
        return e;
        
    };
 
}

(function(){
    var Obj = new NewsFeed ();
    
    //Obligatoire
    Obj.Init();
//    Obj.Init(true);
    
    //Test de la zone buffer
//    Obj._GetBufferDatas();
    
    /***************** HEADER ***************/
    $("#nwfd-hide_hdr").click(function(e){
        Kxlib_PreventDefault(e);
        
        Obj.ReduceHeader();
    });
    
    /***************** VIEW *****************/
    
    $(".jb-nwfd-view-choice").click(function(e){
        Kxlib_PreventDefault(e);
        
        Obj.SwitchView($(this),$(this).data("target"));
    });
    
    /*************** MENU *****************/
    
    $(".nwfd-h-menu-elt").click(function(e){
        Kxlib_PreventDefault(e);
        
        Obj.SwitchMenu($(this));
    });
    
    $(".nwfd-h-menu-elt").hover(function(){
//        var $t = $(e.target);
        Obj.MenuHover($(this));
    });
    
    /**************** NWFD inGLOBALNAV ****************/
    
    $(".nwfd-b-l-mdl-b-box-box").hover(Obj.Handler_HoverListElt);
    
    $(".jb-global-nav-elt.global-nav-nwfd").click(function(e){
        Kxlib_PreventDefault(e);
        
        Obj.Run();
    });
    
    $(".jb-global-nav-elt.global-nav-close").click(function(e){
        Kxlib_PreventDefault(e);
        
        Obj.Close();
    });
    
    $(".jb-global-nav-elt").hover(function(e){
        $("#jb-global-nav-nwfd").toggleClass("this_hide");
        $("#jb-global-nav-nwfd_h").toggleClass("this_hide");
        
        $("#h-c-b-pc-menu-txt .nwfd").toggleClass("this_hide");
    });
    
    
    $(".jb-bttf-nwfd").click(function(e){
        Kxlib_PreventDefault(e);
        
        //Version animée
        /*
        $("#nwfd-body").animate({
            scrollTop :0
        },1000);
        //*/
        //Version simple 
        $("#nwfd-body").scrollTop(0);
    });
    
    /***************** MOZ DATAS *******************/
    $(".nwfd-b-m-mdl-trig").hover(Obj.Handler_ShowMozBar);
    
    //[Note - 03-06-14] Pas mouseout à cause des child, googleit !
    $(".jsbind-b-moz-l-bar").on('mouseleave',Obj.Handler_HoldMozBar);
    
    $("#nwfd-body").scroll(function(){
//        alert($(this).children().css("height"));
//        Kxlib_DebugVars([(this).scrollTop()]);
    });
    
    
    /******** CHARGEMENT DES ELEMENTS ANTERIEURS ********/
    
    //Chargement par demande 
    $(".jb-nwfd-loadm-trg").click(function(e){
        Kxlib_PreventDefault(e);
        
        //On sauvegarde le bloc où seront insérées les nouveaux éléments
        var b = Obj.GetActiveMenuBloc2();
//        alert($(b).data("m"));
        Obj.PredateSentinel(b);
    });
    
    //Chargement automatique 
    $("#nwfd-body").scroll(function(e){
//        Kxlib_DebugVars([Hauteur -> "+$("#nwfd-b-list").height()+"Scroll -> "+$(this).scrollTop()]);
        //l = limit
        var $ab =Obj.GetActiveMenuBloc2(), l;
        
        if ( $($ab).data("v") === 'l' ) {
            l = $($ab).parent().height() - $("#nwfd-body").height() + 20;
        } else {
            l = $($ab).parent().height() - $("#nwfd-body").height() + 20 + 5 ;
        }
        
        var y = Math.round(l/10);
        y *= 8;
        
        /*    
        //Debug
        var y = Math.round(l/10);
        y *= 8;
        var txt = "Hauteur -> "+$($ab).parent().height()+"; Theory -> "+l+"; Scroll -> "+$(this).scrollTop()+"; Au dixieme -> "+y;
        $("#nwfd-h-title").html(txt);
        
//        Kxlib_DebugVars([TYPE STD => "+typeof $(this).scrollTop()+"; TYPE NB => "+typeof y]);
        
        //*/
        
//        Kxlib_DebugVars([($ab).data("ulk")]);
//        Kxlib_DebugVars([($ab).data("ulk") === 1]);
        if ( $($ab).data("ulk") === 1 ) {
            return;
        } else if ( $(this).scrollTop() >= y && $($ab).data("ulk") === 2 && Obj._IsZoneTimeReady($ab,"loc",Obj._GetComposStatus().ttc_oa) ) {
//            Kxlib_DebugVars([Ca passe :) en 2 "]);
            Obj.PredateSentinel($ab);
//                Kxlib_DebugVars([chargés"]);
        } else if ( $(this).scrollTop() >= y && $($ab).data("ulk") === 0 ) {
//            Kxlib_DebugVars([Ca passe :) en 0 "]);
            Obj.PredateSentinel($ab);
        }
    });
    
    /******** GESTION NOUVEAUX ARRIVANTS ********/
    
    $(".jb-nwfd-n-c-rvl-trg").hover(function() {
        var th = this;
        setTimeout(function(){
            if ( $(th).is(":hover") && $(".jb-nwfd-nptp-m").hasClass("zmout") ) {
//                Kxlib_DebugVars([NPTP starts ZoomIn"]);
                //ZoomIn
                Obj.NewPostTrigPanZmIn();
            } else if ( $(th).is(":hover") && !$(".jb-nwfd-nptp-m").hasClass("zmout") ) {
//                Kxlib_DebugVars([NPTP starts ZoomOut"]);
                //ZoomOut
                Obj.NewPostTrigPanZmOut();
            }
        },600);
    }, function() {
//        Kxlib_DebugVars([NPTP starts ZoomOut"]);
    });
    
    $(".jb-nwfd-sld-choices").click(function(e){
        Kxlib_PreventDefault(e);
        
        Obj.NwfdSlidedAction(this);        
    });
    
//    alert(Obj._GetDefaultValues().__NWFD_CNA_ROT);
    
    setInterval(function() {
        Obj.HandleNewerArticles();
    },Obj._GetDefaultValues().__NWFD_CNA_ROT);
    
})();