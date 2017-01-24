 
function POSTMAN (ko) {
    //ko => KeepOpen
    
    /*
     * le Module POSTMAN gère tout ce qui est relatif aux Notifications au niveau de FE.
     * 
     * ---*** FONCTIONNALITES ***---
     * Version B0315.1.1 (BetaVersion1.1 de Mars 2015)
     *  -> Notifications traitées :
     *      -> Notifications pour Usertag de type : Artcicle, Commentaire
     *      -> Notifications pour EVAL : tous les types
     *  -> Faire apparaitre les Notifications à l'ouverture du module
     *  -> Consulter les Notifications les plus anciennes
     *  -> Faire disparaitre le bouton quand il n'y a plus de NOtifications disponibles
     *  -> Veille automatique de nouvelles Notifications quand le module est FERME
     *  -> S'il n'y a plus de Notification à "Non Roger", on fait disparaitre le nombre.
     *  -> Signalement des nouvelles Notifications en exposant au niveau du Header
     *  -> Signaler au Serveur la date de traitement/signalement auprès de l'utilisateur
     *  -> Déclarer toutes les Notifications accessibles (affichées dans le module) comme lues à l'ouverture. 
     *     Cela permettra de corriger un problème qui ferait que de trop vieilles Notifications non signalées continueront à l'être.
     *     L'uitilisateur devont chercher en profondeur pour les retrouver.
     *  -> Toutes les Notifications qui sont insérées dans le Module sont désignées comme "Pulled"
     *  -> Veille automatique de nouvelles Notifications quand le module est OUVERT
     *  -> Afficher un message éphémère dans le coin guche qui indique à l'utilisateur qu'il a une ou plusieurs nouvelles Notifications
     *     
     *  ---*** EVOLUTION ***---
     *  -> Lorsque l'utilisateur clique sur lien qui mène vers l'objet, la Notification est marqué comme "visited"
     *  -> Signalier visuellement qu'une Notification a été : Lue, Visitée
     *  -> Filtrer les Notifications afin de ne faire apparaitre que celles "Non lues", "Des amis", etc ...
     *  -> Intégrer des fonctionnalités liées au module de Notification au "Module flottant" (Module d'Accès Rapide disponible en bas à gauche) 
     *  -> Mettre en place des filtres pour visualiser les Notifications dans le module
     *  -> L'utilisateur décide de ne voir que les Notifications provenant de son 1er cercle ET/OU de comptes confirmés
     */
//    var _xhr_plrps;
    var _xhr_plrps_top;
    var _xhr_plrps_btm;
    //Une donnée pour signaler qu'un processus de soumission est en cours.
    var _sbrps;
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** PROCESS SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/   
    
    var _f_Gdf = function () {
        var ds = {
            //PullRePortsInterval
            "prpi" : 15500,
            "drl" : ["fst","top","btm"]
        };
        
        return ds;
    };
    
    var _f_AdjustHInB = function () {
//    this._AdjustHeightInBlocs = function () {
        var hnb = $(window).height();
        hnb -= 10;
        
        $(".jb-pm-sprt").css("height",Kxlib_ToPxUnit(hnb));
    };
    
    var _f_Init = function(a){
        try {
            
            /* On vérifie si le serveur qu'on ne touche pas à la configuration */
            var sp = $(".jb-pm-sprt").data("svskip");
            if (!KgbLib_CheckNullity(sp) && sp === 1) { return; }
            
            /* Place le support de telle sorte qu'il puisse descendre lors de l'ouverture */
            var hnb = $(window).height();
            
            var mrg = hnb * -1;
            mrg = mrg.toString() + "px";
            
            $(".jb-pm-sprt").css("margin-top", mrg);
            $(".jb-pm-sprt").removeClass("this_hide");
            
            $(".jb-pm-mx").removeClass("this_hide");
            $(".jb-pm-mx").fadeOut();
            
            //Ajuster la hauteur des différents blocs (Important)
            _f_AdjustHInB();        
            
            if (!KgbLib_CheckNullity(a) && a) { _f_Open(); }
            
            /* Vérifier si Menu, View sont initialisés. Corriger les problèmes d'éléments non synchronisés */
//            _f_RepareAlignment();
            
            //TODO : Lancer la Sentinel
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true); 
        }
    };
    
    /*
     * Vérifie au niveau du serveur si des données sont disponibles.
     * Dans ce dernier cas, on les affiche et on avertit le serveur le cas échéant pour lui signaler les Notifications "Roger" ou "Pull".
     * La méthode gère donc les opérations de Traitement et de Verfication/Soumission pour les Notifications.
     * La méthode peut être utilisée par une autre méthode. On citera @see _f_AutoPushRps()
     * 
     * Une Notification est dite 'RoGer' lorsqu'on considère que l'utilisateur l'a visualisée.
     *  On considère que l'utiisateur a été visualisée quand : 
     *      (1) L'utilisateur accède aux Notifications préchargées dans le module 
     *      (2) Le module est ouvert quand la Notification est affichée (pull)
     * Une Notification est dite 'Pull' lorsque cette dernière a été traitée. 
     * Cela signifie que la Notification a été insérée dans la liste des Notifications.
     * 
     * @param {Array} rgrs Liste des Notifications (identifiant) considérées comme "RoGer"
     * @param {Array} plds Liste des Notifications (identifiant) considérée comme "Pulled"
     * @returns {mixed}
     * @see _f_AutoPushRps()
     */
    var _f_Sentinel = function (a) {
//    var _f_Sentinel = function (rgrs,plds) {
        try {
            //On vérifie si la vérification est autorisée
//            if ( !KgbLib_CheckNullity(_xhr_plrps) | $(".jb-pm-mx-odr-tgr").data("lk") === 1 | _sbrps | ( !KgbLib_CheckNullity(a) && !Kxlib_ObjectChild_Count(a) ) ) {
            /*
             * [DEPUIS 07-07-15] @BOR
             */
            if ( !KgbLib_CheckNullity(a) && !Kxlib_ObjectChild_Count(a) ) {
//                Kxlib_DebugVars([PM : Lock at Sentinel => XHR : "+typeof _xhr_plrps+"; BTN : "+$(".jb-pm-mx-odr-tgr").data("lk")+"; SBRPS : "+_sbrps]);
                Kxlib_DebugVars(["PM : Lock at Sentinel =>>> PARAMS"]);
                return;
            } else if ( a.hasOwnProperty('dir') && !KgbLib_CheckNullity(a.dir) && a.dir === "btm" && $(".jb-pm-mx-odr-tgr").data("lk") === 1 && !KgbLib_CheckNullity(_xhr_plrps_btm) ) {
                Kxlib_DebugVars(["PM : Lock at Sentinel => XHR_BTM : "+typeof _xhr_plrps_btm+"; BTN : "+$(".jb-pm-mx-odr-tgr").data("lk")]);
                return;
            } else if ( a.hasOwnProperty('dir') && !KgbLib_CheckNullity(a.dir) && ( a.dir === "top" || a.dir === "fst" ) && !KgbLib_CheckNullity(_xhr_plrps_top) ) {
                Kxlib_DebugVars(["PM : Lock at Sentinel => XHR_TOP : "+typeof _xhr_plrps_top]);
                return;
            } else if ( ( !KgbLib_CheckNullity(plds) | !KgbLib_CheckNullity(rgrs) ) && _sbrps ) {
                Kxlib_DebugVars(["PM : Lock at Sentinel =>>> REPORT TO SERVER"]);
                return;
            }
            
            var dir  = ( !KgbLib_CheckNullity(a) && a.hasOwnProperty('dir') && !KgbLib_CheckNullity(a.dir) && $.inArray(a.dir,_f_Gdf().drl) !== -1 ) ? a.dir : 'top';
            var rgrs = ( !KgbLib_CheckNullity(a) && a.hasOwnProperty('rgrs') && !KgbLib_CheckNullity(a.rgrs) && $.isArray(a.rgrs) && a.rgrs.length ) ? a.rgrs :null;
            var plds = ( !KgbLib_CheckNullity(a) && a.hasOwnProperty('plds') && !KgbLib_CheckNullity(a.plds) && $.isArray(a.plds) && a.plds.length ) ? a.plds : null;
            
            var fm;
            /*
             * ETAPE :
             * On s'assure que la direction fournie est cohérente avec l'environnement.
             * En effet, si la direction indique top ou btm, il faut qu'on est au moins une Notification disponible.
             * Ce sont les données liées à cette Notification qui vont permettre d'exécuter correctement l'opération au niveau du serveur.
             */
            if ( dir.toLowerCase() === "top" ) {
//            if ( a.dir.toLowerCase() === "top" ) {
                if (! $(".jb-pm-mdl-mx").length ) {
                    dir = "fst";
                } else {
                    var rf = Kxlib_GetFirstDomSortedByTimeBy($(".jb-pm-mdl-mx[data-item]"),"data-ptm","FIRST");
                    fm = {
                        i : $(rf).data("item"),
                        t : $(rf).data("ptm")
                    };  
                    /*
                    fm = {
                        i : $(".jb-pm-mdl-mx:first").data("item"),
                        t : $(".jb-pm-mdl-mx:first").data("ptm")
                    }; 
                    //*/
                }
            } else if ( dir.toLowerCase() === "btm" ) {
//            } else if ( a.dir.toLowerCase() === "btm" ) {
                if (! $(".jb-pm-mdl-mx").length ) {
                    dir = "fst";
                } else {
                    var rf = Kxlib_GetFirstDomSortedByTimeBy($(".jb-pm-mdl-mx[data-item]"),"data-ptm","LAST");
                    fm = {
                        i : $(rf).data("item"),
                        t : $(rf).data("ptm")
                    };   
                    /*
                    fm = {
                        i : $(".jb-pm-mdl-mx:last").data("item"),
                        t : $(".jb-pm-mdl-mx:last").data("ptm")
                    };   
                    //*/
                }
            }
            
//            Kxlib_DebugVars([POSTMAN (137) : "+JSON.stringify(fm)]);
            
            /*
             * ETAPE :
             * On vérifie s'il s'agit d'un cas Vérification/Soumission.
             * Dans ce cas, on signale qu'une opération auprès du serveur est en cous.
             */
            if ( !KgbLib_CheckNullity(plds) | !KgbLib_CheckNullity(rgrs) ) {
                _sbrps = true;
            }
            
            /*
             * ETAPE :
             * On vérifie si on est dans un cas différent de celui d'un cas automatique.
             * Dans ce cas, on lock le bouton qui permet de charger des Notifications plus anciennes.
             * L'utilisateur n'ayant rien demander, il ne comprendrait pas si le bouton ne mene nulle part.
             */
            if ( !( !KgbLib_CheckNullity(a) && a.hasOwnProperty("isa") && a.isa === true ) && dir === "btm" ) {
                //On lock le bouton "Voir Plus"
                $(".jb-pm-mx-odr-tgr").data("lk",1);
            }
            
            
            /*
             * ETAPE :
             * On vérifie le cas en présence. S'agit-il d'une ouverture (nouvelle demande) ou d'une mise à jour ?
             * Cela permet de mieux gérer le cas du spinner et autres
             */
            if ( !_f_IsSprtFrmr() && !_sbrps && ( !KgbLib_CheckNullity(a) && KgbLib_CheckNullity(a.isa) ) && dir === "btm" ) {
                //On toggle le Spinner/LoadMore
                _f_TglLdMrSpnr();
                //TODO : On fait apparaitre la phrase d'attente
            }
            
            //** Lancement du processus de récupération des Notifications **//
            var s = $("<span/>");
            
            _f_Srv_SbPl_Rps(rgrs,plds,dir,fm,s);
            
            $(s).on("datasready", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                /*
                if ( a.dir === "btm" && d.hasOwnProperty("ds") && ( KgbLib_CheckNullity(d.ds) || d.ds === false ) ) {
                    Kxlib_DebugVars([JSON.stringify(d)],true);
//                    return;
                }
                //*/
                /*
                 * ETAPE :
                 *      On signale à l'utilisateur qu'il a de nouvelles Notifications dans le coin gauche.
                 * RAPPEL : 
                 *      On affiche dans TOUS les cas pour des raisons de confort.
                 */
                 _f_ShwBpr(d.ds,dir);
                
                //On merge les données pour faciliter le traitement
                var nd = d.ds;
//                var nd = _f_MrgDs(d);
//                Kxlib_DebugVars([JSON.stringify(nd)],true);
//                return;
      
                /*
                 * ETAPE :
                 *      On affiche les Notifications récupérées depuis le serveur.
                 */ 
                var tsb = _f_ShwRps(nd,dir);
                
//                Kxlib_DebugVars(["PSMN : BREAKING POINT : DEV AJOUT NOUVEAUX MODELES"]);
//                return;
                
                if ( _f_IsSprtFrmr() ) {
                    //On signale les Notifications selon le ou les types
                    _f_VwSigRpt(d.gcn);
                }
                
                /*
                 * ETAPE :
                 *      On fait un rapport au serveur en ce qui concerne la prise en compte des Notifications.
                 */
                if ( !KgbLib_CheckNullity(tsb) && ( Kxlib_ObjectChild_Count(tsb.rgrs) | Kxlib_ObjectChild_Count(tsb.plds) ) ) {
//                    Kxlib_DebugVars([JSON.stringify(tsb)],true);
//                    f_GoFree(); //TEMP
//                    return;
                    /*
                     * Ces opérations sont effectuées au préalable, car on considère qu'on a fini avec le premier processus.
                     * Ne pas libérer le bouton et le pointeur n'aurait pas permis l'envoi des données.
                     */
                   _f_GoFree(dir,true);
//                    Kxlib_DebugVars([204,JSON.stringify(tsb)],true);
                   //On signale au serveur que les données ont été publiées ou lues
                   _f_Sentinel({rgrs:tsb.rgrs,plds:tsb.plds});
                } else {
                    Kxlib_DebugVars(["SBRPS 258"]);
                    _f_GoFree(dir);
                }
                
                //On toggle le Spinner/LoadMore (Manuellement)
                _f_Spnr(false,"load_more");
                _f_TglLdMr(true);
                    
                return; 
            });
            
            $(s).on("operended", function(e,d) {
                /*
                if ( a.dir === "btm" ) {
                    alert("NONE"); //>>> ICI <<<
                }
                //*/
                /*
                 * ETAPE :
                 * S'il n'y a plus de données, on retire le bouton et on signale que tous les éléments ont été chargés
                 */
                if ( dir === "btm" ) {
                    $(".jb-pm-mx-odr-tgr").addClass("this_hide");
                    $(".jb-pm-mx-odr-tgr").data("sts","kill");
                    _f_Spnr(false,"load_more");
                } else if ( dir === "fst" ) {
                    _f_Spnr(false,"body");
                    _f_None(true);
                    _f_TglLdMr(false);
                } else {
                    //On toggle le Spinner/LoadMore (Manuellement)
                    _f_Spnr(false,"load_more");
                    _f_TglLdMr(true);
                }
                //[NOTE 07-07-15] @BOR Ici, je mets TRUE, un peu au pif
                _f_GoFree(dir,true);
                
                if ( _f_IsSprtFrmr() && !KgbLib_CheckNullity(d) && !KgbLib_CheckNullity(d.gcn) ) {
                    //On signale les Notifications selon le ou les types
                    _f_VwSigRpt(d.gcn);
                }
                
//                _f_TglLdMrSpnr();
                /*
                //On unlock le bouton
                $(".jb-pm-mx-odr-tgr").data("lk",0);
                //On libère le pointeur
                _xhr_plrps = null;
                //*/
            });
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_PlOdr = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
           
            _f_Sentinel({dir:"btm"});
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
        
    };
    
    var _f_GoFree = function (dir,isc) {
        //isc : IsSubmitCase
        try {
            /*
             * [NOTE 07-07-15] @BOR
             */
            if ( !KgbLib_CheckNullity(dir) && ( dir === "btm") ) {
                //On unlock le bouton
                $(".jb-pm-mx-odr-tgr").data("lk",0);
                //On libère le pointeur
                _xhr_plrps_btm = null;
                Kxlib_DebugVars(["PM : GOFREE BTM !"]);
            } else if ( !KgbLib_CheckNullity(dir) && ( dir === "top" || dir === "fst" ) ) {
                //On libère le pointeur
                _xhr_plrps_top = null;
                Kxlib_DebugVars(["PM : GOFREE TOP !"]);
            }
            
            if ( typeof isc !== "undefined" && isc === true ) {
                _sbrps = false;
                Kxlib_DebugVars(["PM : GOFREE SUBMIT !"]);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
        
    };
    var _f_GoVoid = function () {
        if (! _f_IsVoid() ) {
            $(".jb-pm-mx-bd-l-mx").find(".jb-pm-mdl-mx").remove(); 
        }
    };
    
    var _f_IsVoid = function () {
        return ( $(".jb-pm-mx-bd-l-mx").find(".jb-pm-mdl-mx").length ) ? false : true; 
    };
    
    var _f_IsSprtFrmr = function () {
        var akx = $(".jb-pm-sprt").data("access");
        return ( KgbLib_CheckNullity(akx) | akx === 0 ) ? true : false; 
    };
    
    var _f_MrgDs = function (ds) {
        /*
         * Permet de créer un tableau unique contenant les données de Notifications en utilisant une méthode de "merge".
         * Cette manière de faire permet un traitement en aval des données beaucoup plus souple.
         * Mais encore, la méthide qui sera chargée de traiter les données pourra compter sur le type de Notification afin d'ajuster son traitement.
         */
        if ( KgbLib_CheckNullity(ds) ) {
            return;
        }
        try {
            
            var nds = [];
            $.each(ds, function(x,gds) {
                switch (x) {
                    case "XRCT" :
                        if ("UAT_XRCT_AD_oMA" in gds) {
                            var arr = $.map(gds["UAT_XRCT_AD_oMA"], function(el) { return el; });
                            nds = nds.concat(arr);
                        }
                        break;
                    default:
                        return false;
                }
            });
            
            return nds;
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /**
     * Affiche un message qui indique que l'utilisateur a recu de nouvelles Notifications dans le coin gauche de marnière éphémère.
     * Ces messages ne peuvent pas être FERMER. De plus, pour chaque nouvelle activité, si une activité est en cours il faudra placer le nouvel élément en prepend()
     * @param {Object} d La liste des Notifications qu'il faudra signaler
     * @returns {undefined}
     */
    var _f_ShwBpr = function(d,dr) {
        try {
            if ( !$(".jb-pm-bpr-sprt") | !$(".jb-pm-bpr-sprt").length | KgbLib_CheckNullity(d) | typeof d !== "object" | !Kxlib_ObjectChild_Count(d) | KgbLib_CheckNullity(dr) | dr.toLowerCase() !== "top" ) {
                return;
            }
        
            var nb = Kxlib_ObjectChild_Count(d);
            
            /*
             * ETAPE :
             *      Dans tous les cas, on affiche la zone support.
             *      Elle est forcement vide car chaque élément
             */
            $(".jb-pm-bpr-sprt").removeClass("this_hide");
            
            /*
             * ETAPE :
             *      On passe en revue les éléments pour récupérer les données necessaires à la construction du modèle.
             */
            var nd = [];
            var us__ = [];
            $.each(d, function(x,e) {
                /* RAPPEL REGLES :
                 *  On ne doit ajouter qu'une seule fois un acteur.
                 * * */
                
                /*
                 * [DEPUIS 06-09-15] @author BOR
                 *  On vérifie que la notification n'est pas déjà présente.
                 *  L'objectif étant de traiter plusieurs bogues comme celui faisant apparaitre de manière réccurrente la zone Beeper.
                 */
                if ( $(".jb-pm-mdl-mx[data-item='"+e.id+"'").length ) {
//                    Kxlib_DebugVars(["Anti-Beeper-Fou : "+e.id+" existe !"],false);
                    return true;
                }
                
                if ( !us__.length || $.inArray(e.actid, us__) === -1 ) {
                    var u__ = {
                        "ui"    : e.actid,
                        "ufn"   : e.act_ufn,
                        "upsd"  : e.act_upsd,
                        "uppic" : e.act_uppic,
                        "uhref" : "/@" + e.act_upsd
                    };
                    nd.push(u__);
                    us__.push(e.actid);
                }
            });
            
            /*
            * ETAPE :
            *       On construit les modèles et on les ajoute au support.
            */
           if (! KgbLib_CheckNullity(nd) ) {
               _f_ShwBprMdl(nd,nb);
           }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ShwBprMdl = function (d,enb) {
        
        try {
            if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(enb) ) {
                return;
            }
            
//            Kxlib_DebugVars([LOCKED Show Notif Beeper Model !"]); // >>> ICI <<<
//            return;
            /*
             * ETAPE :
             * On consctruit l'osature du modèle.
             */
            var m = "<div class=\"pm-bpr-mdl-mx jb-pm-bpr-mdl\">";
            m += "<div class=\"pm-bpr-mdl-top\">";
            m += "<span class=\"b jb-pm-bpr-nb\">" + enb + "</span>";
            m += "<span class=\"b\">&nbsp;nouvelles</span>";
            m += "<span>&nbsp;notificatons</span>";
            m += "</div>";
            m += "<div class=\"pm-bpr-mdl-btm\">";
            m += "<ul class=\"pm-bpr-mdl-u-list-mx jb-pm-bpr-mdl-u-l-mx\">";
            m += "</ul>";
            m += "</div>";
            m += "</div>";
            m = $.parseHTML(m);
            
            /*
             * ETAPE :
             * On traite le cas des représentations des acteurs en présence.
             * RAPPEL REGLES :
             *  1- On ne doit ajouter qu'une seule fois un utilisateur
             *  2- 
             */
            $.each(d, function(x,ut) {
                var u_ = "";
                u_ = "<li class=\"pm-bpr-mdl-u-mx jb-pm-bpr-m-u-mx\">";
                /*
                 * [NOTE 08-05-15] @BOR
                 * On ne laisse pas les profils de manière que ça soit cliquable.
                 * Ce n'est pas optimal en termes d'xpérience utilisateur.
                 */
                u_ += "<a class=\"pm-bpr-mdl-u\" title=\"" + ut.ufn + " - @" + ut.upsd + "\">";
//                u_ += "<a class=\"pm-bpr-mdl-u\" href=\"" + ut.uhref + "\" title=\"" + ut.ufn + " - @" + ut.upsd + "\">";
                u_ += "<img class=\"\" height=\"40\" src=\"" + ut.uppic + "\" >";
                u_ += "</a>";
                u_ += "</li>";
                u_ = $.parseHTML(u_);
                $(m).find(".jb-pm-bpr-mdl-u-l-mx").append(u_);
            });
            
            /*
             * ETAPE :
             * Ajout dans la zone en mode prepend
             */
            $(m).hide().prependTo(".jb-pm-bpr-sprt").fadeIn();
            
            setTimeout(function() {
                //On retire l'élément après le lapse de temps défini
                $(m).fadeOut().remove();
            },10000);
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_ShwRps = function (d,dir) {
        /*
         * Permet de gérer l'affichage de nouvelles Notifications.
         * La donnée 'dir' détermine la direction à suivre pour l'ajout.
         * Elle peut être calculée ou fournie. Il n'existe pas à proprement parler de 'dir' par défaut.
         * 
         * RAPPEL :
         *  Les Notifications sont stockées dans des sous tableaux. Elles ont été au préalable "merge" (ou pas).
         *  Dans tous les cas, les Notifications arrivent sous forme de liste directement accessible.
         */ 
        if ( KgbLib_CheckNullity(d) ) {
            return;
        }
        
        try {
            
            /*
             * On détermine s'il s'agit d'un cas de nouvel ajout ou d'une mise à jour de la liste.
             * Cela déterminera le comportement des processus à venir mais aussi et surtout de la direction 
             */
            var inw = ( _f_IsVoid() ) ? true : false;
                    
            //On toggle le Spinner/LoadMore
//            _f_TglLdMrSpnr();

            /*
             * [DEPUIS 08-07-15] @BOR
             */
            _f_Spnr(false,"body");
            _f_None(false);
            
            //On masque le nombre de Notifications non lues dans le header
            _f_VwRstRpt();
            
            //On détermine la direction dans le seul cas où la donnée n'est pas fournie. 
            if ( KgbLib_CheckNullity(dir) ) {
                dir = ( _f_IsSprtFrmr() | _f_IsVoid() ) ? "fst" : "top";
            } else {
                //On vérifie que la direction est conforme
                if ( $.inArray(dir,_f_Gdf().drl) === -1 ) {
                    return;
                }
            }
            
            //Que faire des données déjà présentes ?
            if ( inw && !_f_IsVoid() ) {
                _f_GoVoid();
            }
            
            //tsb (ToSuBmit), tsg (ToSiGnal)
            var tsb = {
                rgrs: [],
                plds: []
            };
            d = ( dir !== 'top' ) ? d : $(d).get().reverse() ;
//            Kxlib_DebugVars([d.length,JSON.stringify(d)],true);
            $.each(d, function(x,rd) {
//                Kxlib_DebugVars([JSON.stringify(rd)],true);
                //On vérifie que l'élément n'est pas déjà inséré
                if ( $(".jb-pm-mdl-mx[data-item='"+rd.id+"']") && $(".jb-pm-mdl-mx[data-item='"+rd.id+"']").length ) {
                    Kxlib_DebugVars(["PM : NOTIF Exists => "+rd.id]);
                    return;
                }
                
                //On prépare l'élément
                var e = _f_VwPprRpt(rd);
                
                //On rebind l'élément à ajouter
                e = _f_RbdRpt(e);
                
                //On affiche l'élément
                if ( dir === 'top' ) {
                    $(".jb-pm-mx-bd-l-mx").prepend(e);
                } else {
                    $(".jb-pm-mx-bd-l-mx").append(e);
                }
                
                //On insère l'élément dans le tableau pour soumission
                var t__ = {
                    "i": rd.id,
                    "t": new Date().getTime()
                };
                
                /*
                if ( !_f_IsSprtFrmr() && rd.tm_rgr !== true ) {
                    tsb.rgrs.push(t__);
                } else if ( _f_IsSprtFrmr() && rd.tm_pull !== true ) {
                    tsb.plds.push(t__);
                }
                //*/
                tsb.rgrs = [];
                tsb.plds = [];
            });
            
            return tsb;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
                
    };
    
    var _f_RbdRpt = function (b) {
        try {
            if ( KgbLib_CheckNullity(b) ) {
                    return;
            }

            $(b).hover(function(e){
                //Rappel : Ne pas utiliser 'toggle' une meilleure maitrise
                if (! $(this).hasClass("visited") ) {
                    _f_VwMdVstd(this,true);
                } else {
                    _f_VwMdVstd(this);
                }
            });

            $(b).click(function(e){
                Kxlib_PreventDefault(e);
    //            Kxlib_StopPropagation(e);
    //            Kxlib_DebugVars(["Mother"],true);
            
                _f_HdlLineClick(this);
//                (new Unique()).OnOpen("psmn",this); //[DEPUIS 14-04-16]
    //            _f_GoVstd(this); //[NOTE 09-04-15] @BOR On affiche maintenant l'Article "sur place"
            });

            $(b).find("a").click(function(e){
                Kxlib_StopPropagation(e);
    //            Kxlib_DebugVars(["Children href"],true);
            });

            return b;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_AutoPushRps = function () {
        try {
            /*
             * Permet de lancer une opération de récupération des Notfications auprès du serveur.
             */
            /*
             * [DEPUIS 07-07-15] @BOR
             */
            if ( !KgbLib_CheckNullity(_xhr_plrps_top) | _sbrps ) {
                Kxlib_DebugVars(["PM : Lock at >AUTO< => XHR_TOP-FST : "+typeof _xhr_plrps_btm+"; BTN : "+$(".jb-pm-mx-odr-tgr").data("lk")+"; SBRPS : "+_sbrps]);
                return;
            } 

            /*
            //On vérifie s'il y a déjà une opération en cours dans lequel cas, on annule.
            if ( !KgbLib_CheckNullity(_xhr_plrps) | _sbrps ) {
                Kxlib_DebugVars([PM : Lock at AutoPush => XHR : "+typeof _xhr_plrps+"; SBRPS : "+_sbrps]);
                return;
            }
            //*/

            var d__ = ( $(".jb-pm-mdl-mx").length ) ? "top" : "fst";
            _f_Sentinel({
                dir :   d__,
                isa :   true
            });
         } catch(ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_GoVstd = function (x) {
        /*
         * Aller vers l'objet concerné par la Notification.
         * La plupart du temps il s'agit de l'Objet secondaire.
         */
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
        
            if ( $(x).find(".jb-pm-mdl-gvstd-hfr").length ) {
                //On lock le bouton qui permet de récupérer des Notifications plus anciennes
                $(".jb-pm-mx-odr-tgr").data("lk",1);
                //On effectue le goto    
                var h__ = $(x).find(".jb-pm-mdl-gvstd-hfr").attr("href");
//                Kxlib_DebugVars([h__],true);
                
                window.location.href = h__;
            }
        } catch(ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
        
    };
    
    var _f_HdlLineClick = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            var $lnmx = ( $(x).is(".jb-pm-mdl-mx") ) ? $(x) : $(x).closest(".jb-pm-mdl-mx");
            var thm = $lnmx.data("thm").toString().toUpperCase();
            var clicable = false, trgtyp;
            switch (thm) {
                case "XRCT" :
                case "XART_USTG" :
                case "XEVL" :
                        clicable = true;
                        trgtyp = "ART";
                    break;
                case "XTSM" :
                case "XTSR" :
                case "XTSL" :
                case "XTST_USTG" :
                        clicable = true;
                        trgtyp = "TST";
                    break;
                case "XUREL" :
                    break;
                case "XTRABO" :
                    break;
                default :
                    return;
            }
//            Kxlib_DebugVars(["PSMN : ","THEME => ",thm,"; OBJECT_TYPE => ",trgtyp,"; IS_CLICABLE => ",clicable]);
            if ( clicable && trgtyp === "ART" ) {
                (new Unique()).OnOpen("psmn",x);
            } else if ( clicable && trgtyp === "TST" ) {
                require(["r/csam/tkbvwr.csam"],function(Talkboard){
                    var _VWR = new Talkboard();
                    _VWR.open({
                        model   : "AJCA-PSMN",
                        trigger : x,
                        action  : "post-react-open"
                    });
                });
            }
            
            
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
        
    };
    
    /**********************************************************************/
    
    var _f_HndlUstg = function (bdy,us) {
        if ( KgbLib_CheckNullity(us) | !( !KgbLib_CheckNullity(bdy) && typeof bdy === "string" ) ) {
            return;
        }
        
        try {
            var t__;
            var ustgs = [];
            $.each(us, function(x,v) {
                var rw__ = [];
                $.map(v,function(e,x) {
                    rw__.push(e);
                });
                ustgs.push(rw__);
            });
            
//                            var ustgs = Kxlib_DataCacheToArray(str__)[0];
//                Kxlib_DebugVars([Kxlib_ObjectChild_Count(art.austgs),ustgs[3]],true);
            var ps = (ustgs && $.isArray(ustgs[0])) ? Kxlib_GetColumn(3, ustgs) : [ustgs[3]];
            //                var ps = ( Kxlib_ObjectChild_Count(art.austgs) > 1 ) ? Kxlib_GetColumn(3,ustgs) : [ustgs[3]];
            t__ = Kxlib_UsertagFactory(bdy, ps, "tqr-unq-user");
            
//                            $(e).find(".nwfd-b-l-mdl-ftr-desc").text(t__);
//                            t__ = $(e).find(".nwfd-b-l-mdl-ftr-desc").text();
            t__ = Kxlib_SplitByUsertags(t__);
            
            return t__;
        } catch (ex) {
//            Kxlib_DebugVars([424,ex],true);
        }

    };
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** TIMER SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    //On lance le processus qui va récupérer les Notifications auprès du serveur en passant par les procédures préliminaires
    setInterval(function(){
        _f_AutoPushRps();
    },_f_Gdf().prpi);
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** SERVER SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/  
    
    var _Ax_Sbpl_rps = Kxlib_GetAjaxRules("PM_SBPL_RPS");
    var _f_Srv_SbPl_Rps = function(rgrs,plds,dir,fm,s) {
        if ( KgbLib_CheckNullity(s) | KgbLib_CheckNullity(dir) ) {
            return;
        }
                
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    _f_GoFree(dir,true);
                    return;
                }
                
                if ( !KgbLib_CheckNullity(d.err)) {
                    /* ??
                    _xhr_plcs = null;
                    //On rend "enable" l'input de recherche
                    $(".jb-asd-c-h-t-ipt").removeProp("disabled");
                    //*/
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
//                                        Kxlib_AJAX_HandleDeny();
                                break;
                            case "__ERR_VOL_FAILED" :
//                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DATAS_MSG" :
//                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                break;
                            default:
                                    /*
                                     * [NOE 14-04-15] @BOR
                                     * Cette méthode peut être utilisée de manière automatique, on n'affiche pas les erreurs pour les cas non gérés
                                     */
//                                    Kxlib_AJAX_HandleFailed();
//                                return;
                                break;
                        }
                    } 
                    _f_GoFree(dir,true);
                    return;
                } else if ( d.hasOwnProperty("return") )  {
                    /*
                     * Données attendues : 
                     *  (1) Données sur les Notifications
                     */
                     if ( !KgbLib_CheckNullity(d.return) && d.return.hasOwnProperty("ds") && d.return.hasOwnProperty("gcn") && ( !KgbLib_CheckNullity(d.return.ds) && !KgbLib_CheckNullity(d.return.gcn) ) )  {
                         rds = [d.return];
                         $(s).trigger("datasready",rds);
                     } else if ( !KgbLib_CheckNullity(d.return) && d.return.hasOwnProperty("gcn") && !KgbLib_CheckNullity(d.return.gcn) ) {
                         rds = [d.return];
                         $(s).trigger("operended",rds);
                     } else {
                         $(s).trigger("operended");
                     }
                } else {
                    /*
                     * [NOTE 07-07-15] @BOR
                     */
                    if ( !KgbLib_CheckNullity(rgrs) | !KgbLib_CheckNullity(plds) ) {
                        _f_GoFree(dir,true);
                    } else {
                        _f_GoFree(dir);
                    }
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
               /*
                * [NOTE 07-07-15] @BOR
                */
                if ( !KgbLib_CheckNullity(rgrs) | !KgbLib_CheckNullity(plds) ) {
                    _f_GoFree(dir,true);
                } else {
                    _f_GoFree(dir);
                }
                return;
            }
        };

        var onerror = function (a,b,c) {
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            _f_GoFree(dir,true);
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_Sbpl_rps.urqid,
            "datas": {
                "rgrs": rgrs,
                "plds": plds,
                "dir" : dir,
                "fm"  : {
                    i   : ( !KgbLib_CheckNullity(fm) && fm.hasOwnProperty("i") && !KgbLib_CheckNullity(fm.i) ) ? fm.i : null,
                    t   : ( !KgbLib_CheckNullity(fm) && fm.hasOwnProperty("t") && !KgbLib_CheckNullity(fm.t) ) ? fm.t : null
                },
                "curl": u 
            }
        };
        
//        xhr__ = Kx_XHR_Send(toSend, "post", _Ax_Sbpl_rps.url, onerror, onsuccess, true);
//        Kxlib_DebugVars([_Ax_Sbpl_rps.url,_Ax_Sbpl_rps.wcrdtl],true);
        xhr__ = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_Sbpl_rps.url, wcrdtl : _Ax_Sbpl_rps.wcrdtl });
        if ( dir.toLowerCase() === "top" || dir.toLowerCase() === "fst" ) {
            _xhr_plrps_top = xhr__;
        } else {
            _xhr_plrps_btm = xhr__;
        }
        return xhr__;
    };
         
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** VIEW SCOPE ****************************************************************************/
    /*******************************************************************************************************************************************************************/  
    
    var _f_None = function (shw) {
        try {
            
            if ( !$(".jb-pm-mx-bd-list-none-mx") | !$(".jb-pm-mx-bd-list-none-mx").length ) {
                return;
            }
            
            if (shw) {
                $(".jb-pm-mx-bd-list-none-mx").removeClass("this_hide");
            } else {
                $(".jb-pm-mx-bd-list-none-mx").addClass("this_hide");
            }
            
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Spnr = function (shw,scp) {
        /*
         * Gère l'apparition/disparition du spinner.
         * Pour la gestion simultannée du spinner et du bouton @see _f_TglLdMrSpnr().
         */
        try {
            if ( KgbLib_CheckNullity(scp) ) {
                return;
            }

            /*
             * [DEPUIS 08-07-15] @BOR
             */
            var $spnr;
            scp = scp.toLowerCase();
            switch (scp) {
                case "load_more" :
                        $spnr = $(".jb-pm-mx-spnr");
                    break;
                case "body" :
                        $spnr = $(".jb-pm-mx-bd-list-spnr-mx");
                    break;
                default :
                    return;
            }
            if (! ( $spnr && $spnr.length )) {
                return;
            }

            if (shw) {
                $spnr.removeClass("this_hide");
            } else {
                $spnr.addClass("this_hide");
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_TglLdMr = function (shw) {
        /*
         * Gère l'apparition/disparition du bouton qui permet de lancer le processus de load des Notifications plus anciennes.
         * Pour la gestion simultannée du spinner et du bouton @see _f_TglLdMrSpnr().
         */
        if ( !$(".jb-pm-mx-odr-tgr") | !$(".jb-pm-mx-odr-tgr").length ) {
            return;
        }
        
        if ( shw && $(".jb-pm-mx-odr-tgr").data("sts") !== "kill" ) {
            $(".jb-pm-mx-odr-tgr").removeClass("this_hide");
        } else {
            $(".jb-pm-mx-odr-tgr").addClass("this_hide");
        }
    };
    
    var _f_TglLdMrSpnr = function () {
        /*
         * Gère l'apparition/disparition du bouton qui permet de lancer le processus de load des Notifications plus anciennes ET du spinner.
         * La gestion est dite "intelligente" du fait qu'elle se base sur les états actuels des composants cités plus haut.
         * 
         * RAPPEL : Ces éléments peuvent être controllés séparement. Voir les méthodes correspondantes.
         */
//        Kxlib_DebugVars([$(".jb-pm-mx-spnr"), $(".jb-pm-mx-spnr").length, $(".jb-pm-mx-odr-tgr"), $(".jb-pm-mx-odr-tgr").length],true);
//        return;
        if (! ( ( $(".jb-pm-mx-spnr") && $(".jb-pm-mx-spnr").length ) && ( $(".jb-pm-mx-odr-tgr") && $(".jb-pm-mx-odr-tgr").length ) ) ) {
            return;
        }
        
        if ( $(".jb-pm-mx-spnr").hasClass("this_hide") && $(".jb-pm-mx-odr-tgr").hasClass("this_hide") ) {
            return;
        } else if ( $(".jb-pm-mx-odr-tgr").data("sts") === "kill" ) {
            $(".jb-pm-mx-odr-tgr").toggleClass("this_hide");
        } else if ( $(".jb-pm-mx-spnr").hasClass("this_hide") && !$(".jb-pm-mx-odr-tgr").hasClass("this_hide") ) {
            $(".jb-pm-mx-spnr").removeClass("this_hide");
            $(".jb-pm-mx-odr-tgr").addClass("this_hide");
        } else if ( !$(".jb-pm-mx-spnr").hasClass("this_hide") && $(".jb-pm-mx-odr-tgr").hasClass("this_hide") ) {
            $(".jb-pm-mx-odr-tgr").removeClass("this_hide");
            $(".jb-pm-mx-spnr").addClass("this_hide");
        }
        
    };
    
    var _f_Open = function () {
        try {
            
            /*
             * [DEPUIS 03-05-16]
             *      On désactive le système OVERFLOW de WINDOW
             */
            Kxlib_WindOverflow(true);
            
            //On affiche les zones pour permettre les différents calculs
            $(".jb-pm-mx").removeClass("this_hide");
            
            _f_AdjustHInB(); 
                    
            $(".jb-pm-mx").addClass("this_hide");
            
            //Afficher le support
            $(".jb-pm-sprt").animate({
                "margin-top": 0
            }, 900, function() {
                //Apres que le support ait terminé l'affichage on ouvre le module à propement parlé
                $(".jb-pm-mx").hide().removeClass("this_hide").fadeIn().removeAttr("style");
                
                //On indique que le module est visible
                $(".jb-pm-sprt").data("access",1);
                
                /*
                 * ETAPE :
                 *      On masque la nombre de Notifications visibles dans le header.
                 */
                $(".jb-sig-ev-cn").addClass("this_hide");
                $(".jb-sig-ev-cn").text("");
                /*
                 * [DEPUIS 22-06-16]
                 * ETAPE : 
                 *      On change le titre de la PAGE pour interpeller USER
                 */
                Kxlib_DocTitleSwNb();
                
                
                /*
                 * ETAPE :
                 *      On vérifie s'il y a des Notifications qu'il faut signaler au niveau du serveur.
                 *      On signale toutes les Notifications présentes (accessible par l'utilisateur), qui n'ont pas de date Roger.
                 */
                var a = {
                    dir : "",
                    rgrs: []
                };
                if ( $(".jb-pm-mdl-mx").length ) {
                    a.dir = "top";
                    $.each($(".jb-pm-mdl-mx"),function(x,ne){
                        var c__ = $(ne).data("cache");
                        var rt__ = Kxlib_DataCacheToArray(c__)[0][3];
                        if ( !KgbLib_CheckNullity(rt__) && parseInt(rt__) === 0 ) {
                            var t__ = {
                                "i": $(ne).data("item"),
                                "t": new Date().getTime()
                            };
                            a.rgrs.push(t__);
                        }
                    });
                } else {
                    a.dir = "fst";
                }
//                Kxlib_DebugVars([JSON.stringify(a)],true);
//                return;
                /*
                 * ETAPE :
                 * On lance le processus de récupération des données.
                 */
                _f_Sentinel(a);
                
            });
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Close = function(){
        try {
           //Fermer le module
           var h = $(window).height();
           
           h *= -1;
           h = h.toString() + "px";

           $(".jb-pm-sprt").animate({
               "margin-top": h
           }, 750, function() {
               //On masque le module
               $(".jb-pm-mx").addClass("this_hide").removeAttr("style");

               //On indique que le module est caché
               $(".jb-pm-sprt").data("access",0);

               //Mise à jour de la configuration de NewsFeed
//               _f_UpdtComposSts();

                
                /*
                 * [DEPUIS 03-05-16]
                 *      On réactive le système OVERFLOW de WINDOW
                 */
                Kxlib_WindOverflow();
           });
       } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
       }
    };
    
    var _f_VwSigRpt = function (ds) {
        /*
         * Gère les opérations relatives à la signalisation de nouvelles Notifications.
         * Le tableau contient le nombre de Notifications par nature : "PRT_NOTFY", "PRT_INFO", "PRT_ALERT_ORG", "PRT_ALERT_RED", "PRT_NEWS"
         */
        try {
            if ( KgbLib_CheckNullity(ds) ) {
                return;
            }

            $.each(ds, function(x,r) {
                switch (r.typ) {
                    case "PRT_NOTFY" : 
                            if ( r.cn === 0 ) {
                                $(".jb-sig-ev-cn[data-scp='psmn']").addClass("this_hide");
                                $(".jb-sig-ev-cn[data-scp='psmn']").text("");
                                
                                /*
                                 * [DEPUIS 22-06-16]
                                 * ETAPE : 
                                 *      On change le titre de la PAGE pour interpeller USER
                                 */
                                Kxlib_DocTitleSwNb();
                            } else {
                                $(".jb-sig-ev-cn[data-scp='psmn']").removeClass("_pmc_2_1y _pmc_3_1y _pmc_4_1y");
                                if ( r.cn.length > 1 ) {
                                    $(".jb-sig-ev-cn[data-scp='psmn']").addClass("_pmc_"+r.cn.length+"_1y");
                                } 
                                $(".jb-sig-ev-cn[data-scp='psmn']").text(r.cn);
                                $(".jb-sig-ev-cn[data-scp='psmn']").removeClass("this_hide");
                                
                                /*
                                 * [DEPUIS 22-06-16]
                                 * ETAPE : 
                                 *      On change le titre de la PAGE pour interpeller USER
                                 */
                                Kxlib_DocTitleSwNb(r.cn);
                            }
                        break;
                    default:
                        return;
                }
            });
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
         
    };
    
    var _f_VwRstRpt = function () {
        $(".jb-sig-ev-cn[data-scp='psmn']").addClass("this_hide");
        $(".jb-sig-ev-cn[data-scp='psmn']").removeClass("_pmc_2_1y _pmc_3_1y _pmc_4_1y");
        $(".jb-sig-ev-cn[data-scp='psmn']").text("");
    };
    
    var _f_VwPprRpt = function (d) {
        /*
         * Permet de préparer (construire) un modèle de Notification.
         * 
         * [NOTE 09-03-15] @Lou
         * Il est possible que la méthode change pour permettre l'utilisateur d'autres modèles en fonction de la nature de la Notification.
         */
        /*
         * TABLE DES CLES : 
         *  id          : L'identifiant externe de la Notification
         *  mstid       : L'identiant externe de l'objet principal liée à la Notification
         *  mst_prmlk   : (Facultatif?) Le lien vers la page 'Fokus' de l'objet principal liée à la Notification. Il peut arriver qu'il n'y ait pas de lien
         *  slvid       : L'identiant externe de l'objet secondaire liée à la Notification
         *  slv_prmlk   : (Facultatif) Le lien vers la page 'Fokus' de l'objet secondaire liée à la Notification. Il peut arriver qu'il n'y ait pas de lien
         *  pmr_tp      : La nature de la Notification 
         *  tm_vstd     : La date selon laquelle l'utilisateur a visité le lien de la Notification. Cette donnée peut être vide ou remplacée par un booléen.
         *  wha         : Le type exacte de Notification
         *  tm          : La date correspondant à l'action. La plupart du temps, cette date correspond à celle de l'objet principal
         *  actid       : L'identifiant externe de l'auteur de l'action qui a amené à la création de la Notification
         *  act_ufn     : Le nom complet de l'auteur de l'action qui a amené à la création de la Notification
         *  act_upsd    : Le pseudo de l'auteur de l'action qui a amené à la création de la NOtification
         *  act_uppic   : L'image de profil de l'auteur de l'action qui a amené à la création de la NOtification
         */
         if ( KgbLib_CheckNullity(d) ) {
             return;
         }
         
//         act_ufn: "Mouna"
//act_uppic: "http://tqim.ycgkit1.com/user/12aoka10155/12aoka10155_3d313i37351h_3j683o8j.jpg"
//act_upsd: "Mouna"
//actid: "12aoka10155"
//id: "4688ao8"
//mstid: "46883o6d"
//pmr_tp: "PRT_NOTFY"
//pmrid: "4688ao8"
//slv_prmlk: "/f/7fbbjo5d"
//slvid: "7fbbjo5d"
//tm: "1427125927745"
//tm_pull: false
//tm_rgr: false
//tm_vstd: false
//wha: "UAT_XRCT_AD_oMA"
         try {
             
             /*
              * LISTE DES CODES :
              *         UAT_XRCT_AD_OMA
              *         UAT_XUSTG_ART
              *         UAT_XUSTG_RCT
              *         UAT_XEVL_GOEVL_OMA
              *         UAT_XUSTG_MEOTSM
              *         UAT_XUSTG_MEOTSR
              *         UAT_XTSTY_AD_SBOMTBD
              *         UAT_XTSTY_AD_SBOMTBD
              *         UAT_XTSTY_TSL_SBOMTSM
              *         UAT_XREL_NWAB
              *         UAT_XMTRD_NWABO
              */
            
            //On détermine le texte d'action (ActionText)
            var at = Kxlib_getDolphinsValue("PM_" + d.wha);
            var nat = "";
            
            if ( $.inArray(d.wha.toUpperCase(),["UAT_XRCT_AD_OMA","UAT_XUSTG_ART","UAT_XUSTG_RCT","UAT_XMTRD_NWABO"]) !== -1 ) {
//                Kxlib_DebugVars(["PSMN => ",d.wha.toUpperCase(),"; PERMA : ",d.slv_prmlk]);
                nat = ( d.hasOwnProperty("slv_prmlk") && !KgbLib_CheckNullity(d.slv_prmlk) ) ? Kxlib_DolphinsReplaceDmd(at, "prmlk", d.slv_prmlk) : at;
            } else if ( $.inArray(d.wha.toUpperCase(),["UAT_XEVL_GOEVL_OMA"]) !== -1 ) {
                at = Kxlib_getDolphinsValue("PM_" + d.wha + d.pvw_tab.pvwbody);
//                alert("PM_" + d.wha + d.pvw_tab.pvwbody);
                nat = ( d.hasOwnProperty("slv_prmlk") && !KgbLib_CheckNullity(d.slv_prmlk) ) ? Kxlib_DolphinsReplaceDmd(at, "prmlk", d.slv_prmlk) : at;
            } else {
                nat = at;
            }
            
            //On crée une chaine pour la date
            var dt = new KxDate(parseInt(d.tm));
            dt.SetUTC(true);
            //On insere la date
            var strtm = dt.WriteDate();
            var strtm_hr = dt.getHours()+":"+("0"+dt.getMinutes()).substr(-2);
            //TODO : Créer une chaine qui donne la date et heure exacte
                    
            var tm = ( d.tm === false ) ? 0 : d.tm;        
            var ptm =  ( d.ptm === false ) ? 0 : d.ptm;        
            var tm_p = ( d.tm_pull === false ) ? 0 : 1;        
            var tm_r = ( d.tm_rgr === false ) ? 0 : 1;        
            var tm_v = ( d.tm_vstd === false ) ? 0 : 1;        
                    
            var e = "<article class=\"pm-mdl-mx jb-pm-mdl-mx\" data-item=\"" + d.id + "\" data-time=\"" + d.tm + "\" data-ptm=\""+ d.ptm+"\" ";
            e += "data-cache=\"['"+tm+"','"+ptm+"','"+tm_p+"','"+tm_r+"','"+tm_v+"']\" ";
            e += " >";
            e += "<div class=\"pm-mdl-lt\">";
            e += "<a class=\"pm-mdl-upic-hf\" href=\"/@" + d.act_upsd + "\">";
            e += "<span class=\"pm-mdl-upic-i-fade\"></span>";
            e += "<img class=\"pm-mdl-upic-img jb-pm-mdl-upic-img\" height=\"40\" src=\"" + d.act_uppic + "\" />";
            e += "</a>";
            e += "</div>";
            e += "<div class=\"pm-mdl-rt\">";
            e += "<span class=\"pm-mdl-txt jb-pm-mdl-txt\">";
            e += "<a class=\"pm-mdl-txt-psd\" href=\"/@" + d.act_upsd + "\">@" + d.act_upsd + "</a>&nbsp" + nat + "";
            e += "</span>";
            /* // [DEPUIS 22-06-16]
            if (d.tm_vstd === true) {
                e += "<a class=\"pm-mdl-vstd-hrf jb-pm-mdl-vstd-hrf this_hide\" href=\"javascript:;\" title=\"\" alt=\"\">";
                e += "<img class=\"pm-mdl-vstd-icn jb-pm-mdl-vstd-icn\" src=\""+Kxlib_GetExtFileURL("sys_url_img","r/eye.png",["_WITH_ROOTABS_OPTION"])+"\" height=\"15\" width=\"15\" />";
                e += "</a>";
            }
            //*/
            if ( d.tm_rgr === true ) {
                e += "<span class=\"pm-mdl-vstd-hrf jb-pm-mdl-vstd-hrf\" href=\"javascript:;\" title=\"Vous avez déjà vu cette notification\" alt=\"\"></span>";
            }
            e += "</div>";
            e += "<div class=\"pm-mdl-bt\">";
            e += "<span class=\"pm-mdl-bt-time-athr\">&nbsp;à&nbsp;"+ strtm_hr +"</span>";
            e += "<span class=\"pm-mdl-bt-time\" title=\"\">" + strtm + "</span>";
            e += "</div>";
            e += "<div class=\"pm-mdl-prvw-mx jb-mdl-prvw-mx\"></div>";
            e += "<div class=\"pm-mdl-spcl-icns-mx jb-pm-mdl-spcl-icns-mx\"></div>";
            e += "</article>";
            e = $.parseHTML(e);
                    
            /*
             * ETAPE :
             * On effectue des traitements relatifs à chaque cas EN PARTICULIER pour la zone Preview.
             */
            switch (d.wha.toUpperCase()) {
                case "UAT_XRCT_AD_OMA" :
                case "UAT_XUSTG_ART" :
                case "UAT_XUSTG_RCT" :
                /*
                 * [DEPUIS 13-04-16]
                 */
                case "UAT_XUSTG_MEOTSM" :
                case "UAT_XUSTG_MEOTSR" :
                case "UAT_XTSTY_AD_SBOMTBD" :
                case "UAT_XTSTY_TSR_SBOMTSM" :
                case "UAT_XTSTY_TSL_SBOMTSM" :
                        var wha__ = d.wha.toUpperCase();
                        var obtp = "";
                        if ( $.inArray(wha__,["UAT_XRCT_AD_OMA","UAT_XUSTG_RCT"]) !== -1 ) {
                            obtp = "react";
                        } else if ( wha__ === "UAT_XUSTG_ART" ) {
                            obtp = "article";
                        } else if ( $.inArray(wha__,["UAT_XUSTG_MEOTSM","UAT_XUSTG_MEOTSR","UAT_XTSTY_AD_SBOMTBD","UAT_XTSTY_TSR_SBOMTSM","UAT_XTSTY_TSL_SBOMTSM"]) !== -1 ) {
                            obtp = "testy";
                        }

                        /*
                         * ETPAE :
                         * Insertion du texte du Commentaire. L'insertion se fait selon la présence des Usertags.
                         */
                        if ( d.hasOwnProperty("pvw_tab") && !KgbLib_CheckNullity(d.pvw_tab) && !KgbLib_CheckNullity(d.pvw_tab.pvwbody) ) {

                            var t__ = d.pvw_tab.pvwbody;
                            t__ = Kxlib_Decode_After_Encode(t__);
                            /*
                            
                            //Dans tous les cas on crée l'élement qui contiendra le texte
                            var __ = $("<div/>", {
                                class: "pm-mdl-prvw jb-mdl-prvw",
                                "data-obtype": obtp
                            });
                            
                            t__ = Kxlib_Decode_After_Encode(t__);
                            if ( d.pvw_tab.hasOwnProperty("ustgs") && !KgbLib_CheckNullity(d.pvw_tab.ustgs) && Kxlib_ObjectChild_Count(d.pvw_tab.ustgs) ) {
                                t__ = _f_HndlUstg(t__,d.pvw_tab.ustgs);
                                $(__).html(t__);
                            } else {
                                $(__).text(t__);
                            }
                            $(e).find(".jb-mdl-prvw-mx").append(__);
                            ///*/
                            /*
                             * [DEPUIS 14-04-16]
                             *      On RENDER le texte selon la nouvelle méthodologie
                             */
                            var ustgs,hashs;
                            ustgs = ( d.pvw_tab.hasOwnProperty("ustgs") && !KgbLib_CheckNullity(d.pvw_tab.ustgs) && Kxlib_ObjectChild_Count(d.pvw_tab.ustgs) )
                            ? d.pvw_tab.ustgs : null;
                            hashs = ( d.pvw_tab.hasOwnProperty("hashs") && !KgbLib_CheckNullity(d.pvw_tab.hashs) && Kxlib_ObjectChild_Count(d.pvw_tab.hashs) )
                            ? d.pvw_tab.hashs : null;
                            
                            /*
                            if ( d.wha.toUpperCase() === "UAT_XUSTG_RCT" && ( !KgbLib_CheckNullity(ustgs) | !KgbLib_CheckNullity(hashs) ) ) {
                                Kxlib_DebugVars([typeof ustgs,JSON.stringify(ustgs),typeof hashs,JSON.stringify(hashs)],true);
                            }
                            //*/
                            
                            var rtxt =  Kxlib_TextEmpow(t__,ustgs,hashs,null,{
                                emoji : {
                                    "size"          : 36,
                                    "size_css"      : 18,
                                    "position_y"    : 3
                                },
                                wrap_text : true
                            });
                            
                            /*
                            if ( d.wha.toUpperCase() === "UAT_XUSTG_RCT" && ( !KgbLib_CheckNullity(ustgs) | !KgbLib_CheckNullity(hashs) ) ) {
                                Kxlib_DebugVars([rtxt],true);
                            }
                            //*/
                            
                            $(rtxt).addClass("pm-mdl-prvw jb-mdl-prvw").data("obtype",obtp).attr("data-obtype",obtp);
                            $(e).find(".jb-mdl-prvw-mx").text("").append(rtxt);
                        }
                        
                        /*
                         * [DEPUIS 14-04-16]
                         *      On ajoute les données AJCACHE ce qui permettra de ne pas faire appel à une méthode d'acquisition ASYNC.
                         */
                        if ( obtp === "testy" ) { 
                            $(e).data("subj-ajcache",JSON.stringify(d.xdatas));
                        }
                        

                        /*
                         * ETAPE :
                         * On ajoute l'identifiant de l'Article à la zone pour permettre d'accéder à l'Article lié depuis UNIQUE. 
                         */
                        var ai__ = at__ = "";
                        switch (wha__) {
                            /* [DEPUIS 14-04-16] */
                            case "UAT_XTSTY_AD_SBOMTBD" :
                                    ai__ = d.mstid;
                                break;
                            case "UAT_XRCT_AD_OMA" :
                            case "UAT_XUSTG_ART" :
                                    ai__ = d.slvid;
                                    at__ = d.pvw_tab.time; //[DEPUIS 02-05-16]
                                break;
                            /* [DEPUIS 14-04-16] */
                            case "UAT_XUSTG_MEOTSM" :
                            case "UAT_XTSTY_TSL_SBOMTSM" :
                            case "UAT_XTSTY_TSR_SBOMTSM" :
                                    ai__ = d.slvid;
                                break;
                            case "UAT_XUSTG_RCT" :
                                    ai__ = d.slvl1id;
                                    at__ = d.pvw_tab.time; //[DEPUIS 02-05-16]
                                break;
                            /* [DEPUIS 14-04-16] */
                            case "UAT_XUSTG_MEOTSR" :
                                    ai__ = d.slvl1id;
                                break;
                            default :
                                break;
                        }
                        $(e).data("ai",ai__).attr("data-ai",ai__);
                        if ( at__ ) {
                            $(e).data("at",at__).attr("data-at",at__); 
                        }
                    break;
                case "UAT_XEVL_GOEVL_OMA" :
                        /*
                        * ETAPE :
                        * On ajoute l'identifiant de l'Article à la zone pour permettre d'accéder à l'Article lié depuis UNIQUE. 
                        */
                       $(e).data("ai",d.slvid).attr("data-ai",d.slvid);
                       $(e).data("at",d.pvw_tab.time).attr("data-at",d.pvw_tab.time);
                    break;
                case "UAT_XREL_NWAB" :
                case "UAT_XMTRD_NWABO" :
                        $(e).data("ai",null);
                    break;
                default :
                    return;
            }
            
            
            /*
             * ETAPE :
             *      On détermine l'état de la Notification pour mettre le bon indicateur visuel.
             *      La Notification peut être : Pull, Roger ou Visited.
             * ATTENTION : Tous les cas mentionnés ci-dessus ne sont pas traités.
             */
            if ( tm_v === 1 ) {
                $(e).addClass("visited");
            }
            if ( tm_r === 1 ) {
                $(e).addClass("read");
            }
            
            /*
             * [NOTE 06-07-15] @BOR
             * TODO : On vérifie si l'utilisateur a le droit d'accéder à l'Article.
             */
            if ( d.cnrd === false ) {
                var dolcd = ( obtp === "testy" ) ? "PM_XTRA_TST_DNY_AKX_LCK_TLE" : "PM_XTRA_ART_DNY_AKX_LCK_TLE"; //[DEPUIS 14-04-16]
                var ik = $("<span/>",{
                    class       : "pm-mdl-spcl-icn",
                    "data-type" : "art_deny",
                    title       : Kxlib_getDolphinsValue(dolcd)
                }).append(function(){
                    var $t__ = $("<i/>",{
                        class : "fa fa-lock"
                    });
                    return $t__;
                });
                $(e).find(".jb-pm-mdl-spcl-icns-mx").append(ik);
            }
            
            /*
             * [DEPUIS 07-07-15] @BOR
             * Permet de mettre en place le code d'identification qui servira pour l'icone
             */
            var icn;
            switch (d.wha.toUpperCase()) {
                case "UAT_XRCT_AD_OMA" :
                        icn = "XRCT";
                    break;
                case "UAT_XUSTG_ART" :
                case "UAT_XUSTG_RCT" :
                /* [DEPUIS 14-04-16] */
                        icn = "XART_USTG";
                    break;
                case "UAT_XUSTG_MEOTSM" :
                case "UAT_XUSTG_MEOTSR" :
                        icn = "XTST_USTG";
                    break;
                case "UAT_XEVL_GOEVL_OMA" :
                        icn = "XEVL";
                    break;
                /* [DEPUIS 14-04-16] */
                case "UAT_XTSTY_AD_SBOMTBD" :
                        icn = "XTSM";
                    break;
                case "UAT_XTSTY_TSR_SBOMTSM" :
                        icn = "XTSR";
                    break;
                case "UAT_XTSTY_TSL_SBOMTSM" :
                        icn = "XTSL";
                    break;
                case "UAT_XREL_NWAB" :
                        icn = "XUREL";
                    break;
                case "UAT_XMTRD_NWABO" :
                        icn = "XTRABO";
                    break;
                default :
                    break;
            }
            $(e).attr("data-thm",icn);
            
            return e;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_VwMdVstd = function (x,shw) {
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x)) | !$(x).length ) {
            return;
        }
        
        if (! KgbLib_CheckNullity(shw) ) {
            $(x).addClass("visited");
            //[DEPUIS 22-06-16]
            /*
            if ( $(x).find(".jb-pm-mdl-vstd-hrf").length ) {
                $(x).find(".jb-pm-mdl-vstd-hrf").removeClass("this_hide");
            } else {
                var $i__ = $("<img/>");   
                $i__.attr({
                    class   : "pm-mdl-vstd-icn jb-pm-mdl-vstd-icn",
                    src     : Kxlib_GetExtFileURL("sys_url_img","r/eye.png",["_WITH_ROOTABS_OPTION"]),
                    height  : 15,
                    width   : 15 
                });
                
                var $h__ = $("<a/>");        
                $h__.attr({
                    class : "pm-mdl-vstd-hrf jb-pm-mdl-vstd-hrf",
                    href  : "javascript:;",
                    title : "",
                    alt   : ""
                }).append($i__);
                
                $(x).find(".jb-pm-mdl-txt").after($h__);
            }
            //*/
            
            /*
             * ETAPE :
             * On vérifie s'il la ligne contient une zone prévu valide.
             * Dans ce dernier cas, on l'affiche.
             */
//            if ( $(x).find(".jb-mdl-prvw-mx") && $(x).find(".jb-mdl-prvw-mx").length && $(x).find(".jb-mdl-prvw-mx").children(".jb-mdl-prvw").length ) {
//                $(x).find(".jb-mdl-prvw").removeClass("this_hide");
//            }
        } else {
            $(x).removeClass("visited");
            //[DEPUIS 22-06-16]
//            $(x).find(".jb-pm-mdl-vstd-hrf").addClass("this_hide");
            
//            if ( $(x).find(".jb-mdl-prvw-mx") && $(x).find(".jb-mdl-prvw-mx").length && $(x).find(".jb-mdl-prvw-mx").children(".jb-mdl-prvw").length ) {
//                $(x).find(".jb-mdl-prvw").addClass("this_hide");
//            }
        }
    };
    
    /**********************************************************************************************************************************************************************/
    /************************************************************************** LISTERNERS SCOPE **************************************************************************/
    /**********************************************************************************************************************************************************************/                                                              
    
    _f_Init();
//    _f_Init(true);
    
    $(".jb-pm-close").click(function(e){
        Kxlib_PreventDefault(e);
        _f_Close();
    });
    
     /*
     * [NOTE 13-05-15] @BOR
     * .is(":hover") ne fonctionnant pas partout, j'utilise la voie standard.
     */
    $(".jb-global-nav-elt[data-target=pm]").hover(function(e){
        $(this).find(".menu-std").addClass("this_hide");
        $(this).find(".menu-hvr").removeClass("this_hide");
        /*
        $(this).find(".menu-std").toggleClass("this_hide");
        $(this).find(".menu-hvr").toggleClass("this_hide");
        //*/
//        $("#h-c-b-pc-menu-txt .nwfd").toggleClass("this_hide");
    }, function () {
        $(this).find(".menu-hvr").addClass("this_hide");
        $(this).find(".menu-std").removeClass("this_hide");
    });
    
    $(".jb-global-nav-elt[data-target='pm']").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Open();
    });
    
    
    $(".jb-pm-mdl-mx").hover(function(e){
        //Rappel : Ne pas utiliser 'toggle' une meilleure maitrise
        if (! $(this).hasClass("visited") ) {
            _f_VwMdVstd(this,true);
        } else {
            _f_VwMdVstd(this);
        }
    });
    
//    $(".jb-pm-mdl-mx, .jb-pm-mdl-txt-mst").click(function(e){
//        Kxlib_PreventDefault(e);
//        Kxlib_StopPropagation(e);
//        
//        _f_GoVstd(this);
//    });
    
    $(".jb-pm-mx-odr-tgr").click(function(e){
        Kxlib_PreventDefault(e);
                
        _f_PlOdr(this);
    });
};

new POSTMAN ();