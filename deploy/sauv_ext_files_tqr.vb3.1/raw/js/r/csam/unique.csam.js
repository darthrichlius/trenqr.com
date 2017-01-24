/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function Unique () {
    
    /*
     * 
     * FONCTIONNALITES
     *  Version : b.1505.1.1 (Mai 2015 VBeta1) [DATE UPDATE : 17-03-15]
     *      [ARTICLE]
     *      -> Afficher sous format UNIQUE, un Article quelque soit sa nature (IML, ITR)
     *      -> Supprimer un Article quelque soit la page de référence
     *      -> Vérifer que l'utilisateur a le droit d'accéder au bouton de suppression. La donnée est récupérée depuis SERVER.
     *      -> Afficher un lien permanent pour l'Article
     *      -> Passer d'un Article à un autre à l'aide de la souris
     *      -> Controller que les Commentaires ne sont ajoutés que dans le cas où on affiche le bon Article
     *      -> Lorsque l'Article de référence est de type [NWFD,PSMN], les flèches directionnelles disparaissent (T@BlackOwlRobot : 18-03-15)
     *      -> Afficher un Article en se basant un identifiant
     *      -> Afficher un Article référencé dans le module de NOTIFICATION
     *      [REACTIONS]
     *      -> Afficher les Commentaires pour un Article
     *      -> Gérer le cas où il n'y a aucun Commentaire
     *      -> Supprimer un Commentaire
     *      -> Demander une confirmation avant de supprimer un Commentaire
     *      -> Vérifier les droits accéder au bouton de suppression du Commentire. La donnée est récupérée depuis SERVER.
     *      -> Indiquer que les Commentaire sont innaccessibles
     *      [EVAL]
     *      -> Donner une Evaluation à un Article
     *      -> Afficher une barre de chargement quand on procède à une évaluation
     *      -> Afficher le nombre d'Eval par type
     *  
     *  EVOLUTIONS ATTENDUES
     *      -> ...
     *  
     *  EVOLUTIONS POSSIBLES
     *      -> ...
     */
    
    var gt = this;
    //rdl = ReactDatasList
    var _rdl;
//    this.rdl;
    //CallerObjectconteXte : L'objet cible de l'évènement.
    var _cox;
//    this.cox;
    
    var _COUNTBOXH = 36;
//    this.__COUNTBOXH = 35;

    var _cdel;
    //pvad : PullVolatileArticleDatas
    var _xhr_pvad;
    //PulLReactionS
    var _xhr_plrs;
    //PulLEvaluationS
    var _xhr_ples;
    
    /*************************************************************************************************a*******************************************************/
    /******************************************************************** PROCESS SCOPE *********************************************************************/
    /********************************************************************************************************************************************************/
    
    var _f_Gdf = function () {
        var ds = {
            //Choix possibles : "_TR_IML","_TR_ITR","_TG_ITR","_ND_IML","_ND_ITR"
            "nav_art_allwd"  : ["_TR_ITR","_TG_ITR","_FV_IML","_FV_ITR"],
            "nav_art_slctr": {
                "_TR_ITR"   : ".jb-tmlnr-mdl-intr",
                "_TG_ITR"   : ".jb-trpg-art-nest .jb-mdl-tr-post-in-list",
                "_FV_IML"   : ".jb-unq-bind-art-mdl",
                "_FV_ITR"   : ".jb-unq-bind-art-mdl",
                "_TIA_EX_IML"   : ".jb-unq-bind-art-mdl",
                "_TIA_EX_ITR"   : ".jb-unq-bind-art-mdl",
                "_TIA_PHTK_IML" : ".jb-unq-bind-art-mdl",
                "_TIA_PHTK_ITR" : ".jb-unq-bind-art-mdl",
            },
            /*
             * puLlMIN_ : Le nombre qu'il faut d'Article minimum pour lancer Loader.
             * La différence avec la valeur suivante est que celle-ci, n'est utilisée exclusivement que dans le cas où on veut savoir si au  regard du nombre affiché, il est judicieux de lancer Loarder.
             */
            "plmin_" : 3,
            /*
             * puLlMIN : On "Pull" de nouveaux Articles quand le nombre d'Articles affichés est égal à x ou moins dans la liste.
             * Aussi, si l'Article fait partie des x derniers Articles de la liste.
             */
            "plmin" : 5
        };
        return ds;
    };
    
    var _f_SetReactNb = function (a) {
//    this._SetReactNb = function (a) {
        var n = ( KgbLib_CheckNullity(a) ) ? $(".unq-react-mdl").length : a;
        $(".jb-unq-c-b-nb").text(n);
    };
    
    var _f_GetReactNb = function () {
//    this._GetReactNb = function () {
        return $(".unq-react-mdl").length;
    };
    
    var f_WipeOldReacts = function () {
//    this._WipeOldReacts = function () {
        /* Permet de retirer les anciens commentaires avant d'afficher les nouveaux */
        
        //On retire tous les commentaires présent
        $(".jb-unq-c-a-r-bx .jb-unq-react-mdl").remove();
        
    };
    
    var _f_OnLoad = function (p,t,s) {
//    this.OnLoad = function (p,t) {
        /* Permet de réaliser certaines opérations lorsque l'Article est chargée en mode Unique. Ces opérations ont lieu avant ...
         * l'apparition de l'Article Unique  
         * */
        if ( KgbLib_CheckNullity(p) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(s) ) {
            return;
        }
        //...
        
        //... Autres opérations préliminaires ...
        
        //On vérifie si l'image 
        
        //On prépare (on met en forme son visuel) le modèle à afficher 
        return _f_PprUnqMdl(p,t,s);
       
    };
    
    //STAY PUBLIC
    this.OnOpen = function(p,t) {
//    var _f_OnOpen = function(p,t) {
//    this.OnOpen = function(p,t) {
        //p = Code la page. Cela permet de savoir quel model chargé; 
        //t = Un point d'entré au modèle dont on veut que l'image s'affiche dans UNQ. généralement il s'agit de '.fcb_img_maximus'
//        Kxlib_DebugVars(["68","OnOpen"],true);
        try {
            /*
             * [NOTE 14-04-15] @BOR
             * ETAPE :
             *      On vérifie si on est dans le cas selon lequel on charge les Articles les plus anciens pour le confort d'utilisation
             */
             _f_DigPF(p);
            
            /*
             * [NOTE 01-09-14] @BOR
             *      Le fait que le serveur mette du temps avant de renvoyer les commentaires nous poussent à reinit la vue avant toute chose.
             *      On retire les anciens éléments pour éviter de les voir à l'ouverture. 
             *      Cela implique aussi de gérer la taille du bloc contenant les commentaires.
             */
            _f_RstUnqVw();
            
            //On montre la fenetre support
            $("#unique-max").removeClass("this_hide");
            
            //On ajoute la classe .active
            $(".jb-unq-art-mdl").addClass("active");
//            alert("wait");

            /*
             * [DEPUIS 30-04-16]
             *      On désactive le système OVERFLOW de WINDOW
             */
            Kxlib_WindOverflow(true);
            
            //On sauvegarde l'Objet Article appelant
            var r = _f_SvCllrCtxt(p,t);
            
            if ( KgbLib_CheckNullity(r) ) {
                //On a besoin de la référence de l'objet appelant. Sinon on ne pourra pas mettre à jour les données au niveau de la version en col.
                //Cependant, on préfera l'utilisateur accéder à la version UNQ car cela ne l'empechera pas d'utiliser normalement UNQ
                
                //TODO: Prévenir le serveur
            }
            
            /*
             * [NOTE 09-04-15] @BOR
             * On peut se retrouver dans le cas où les données ne sont accessibles qu'en différé.
             * Aussi, on envoit DANS TOUS LES CAS le listeners. Si on est dans ce cas, on continue les opérations utérieurement.
             */
            var s = $("<span/>");
            $(s).on("datasready",function(e,o,d){
                /*
                * [NOTE 16-09-14] @author L.C.
                * Juste après avoir affiché les commentaires ont lance les requetes pour afficher les autres données : 
                *  (1) Les VIP (S'ils n'existent pas)
                *  (2) Les EVAL si le tableau des EVAL est de [0,0,0,0] 
                *  
                * Le fait que l'affichage des commentaires attire visuellement l'attention cela nous permet de faire des modifications en évitant au maximum que l'utilisateur s'en apercoive.
                */
               
//                alert(JSON.stringify(d));
                _f_PprArtUnqMdl_Ffil(d,p);
                
               _f_PullVips_Plus(o,d);

               //On récupère tous les commentaires et on les affiche
               var i = d.itemid;
               _f_GtAlRctsLmt(i);
               
            });
            
            //On charge la vue
            //ai = ArticlesInfos
            var ai = _f_OnLoad(p,t,s);
            if ( KgbLib_CheckNullity(ai) | !( typeof ai === "object" && ai.hasOwnProperty("itemid") ) ) {
                return;
            } else {
                /*
                * [NOTE 16-09-14] @author L.C.
                * Juste après avoir affiché les commentaires ont lance les requetes pour afficher les autres données : 
                *  (1) Les VIP (S'ils n'existent pas)
                *  (2) Les EVAL si le tableau des EVAL est de [0,0,0,0] 
                *  
                * Le fait que l'affichage des commentaires attire visuellement l'attention cela nous permet de faire des modifications en évitant au maximum que l'utilisateur s'en apercoive.
                */
               _f_PullVips_Plus(r,ai);

               //On récupère tous les commentaires et on les affiche
               var i = ai.itemid;
               _f_GtAlRctsLmt(i);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_OnClose = function(EC) {
//    this.OnClose = function() {
        //ec = ErrorCode
        try {
            
            /*
             * [DEPUIS 03-05-16]
             *      On arrete la vidéo lecas échéant.
             */
            if ( _f_GetMediaMode() === "video" ) {
                _f_VidPause($(".jb-unq-tv-lnch-vid[data-scp='standard']"));
            }
            
            if (! KgbLib_CheckNullity(EC) ) {
                switch (EC) {
                    case "__ERR_VOL_DNY_AKX" :
                        //On masque les boutons
                        $(".jb-unq-nav-btn").addClass("this_hide");
                        
                        //On masque la zone présentant l'auteur de l'Article
                        $(".jb-unq-u-bx-owr").addClass("this_hide");
                        
                        //On récupère le message lié
                        var m_ = Kxlib_getDolphinsValue("ERR_UNQ_BPR_DNYAKX_REL");
                        if (!KgbLib_CheckNullity(m_)) {
                            //On ajoute le message
                            $(".jb-unq-err-bpr-msg").text(m_); 
                            
                            /*
                             * [DEPUIS 11-07-15] @BOR
                             * On remet le spiner central
                             */
                            $(".jb-unq-mdl-spnr-mx").addClass("this_hide");
                            
                            //On affiche la boite liée
                            $(".jb-unq-err-beeper-mx").removeClass("this_hide");
                            
                            //On affiche la zone centre
                            $(".jb-unq-c-r").addClass("this_hide");
                        }
                        break;
                    default :
                        break;
                }
            } else {
                _f_ClzUnq();
                
                //On retire la classe .active
                $(".jb-unq-art-mdl").removeClass("active");
                
                //On ferme la zone permalink
                _f_PrmLk_OnClz();
                
                //On retire la zone de confirmation de suppression de l'Article
                $(".jb-unq-cfrm-bx-mx").addClass("this_hide");
                
                //On retire les messages 
                $(".jb-unq-err-bpr-msg").text("");
                
                //On cache la boite de dialogue qui affiche les messages d'erreur "beeper" (court)
                $(".jb-unq-err-beeper-mx").addClass("this_hide");
                
                //On masque la zone présentant l'auteur de l'Article
                $(".jb-unq-u-bx-owr").addClass("this_hide");
                
                //On masque la zone centre
                $(".jb-unq-c-r").addClass("this_hide");
                
                /*
                 * [DEPUIS 10-07-15] @BOR
                 * On retire l'ancienne image pour éviter qu'elle apparaisse à la prochaine opération en cas de lag.
                 */
                $(".unq-tv jb-unq-tv").removeAttr("src");
                
                /*
                 * [DEPUIS 04-07-15] @BOR
                 * On affiche toujours "None". C'est aux autres modules de le faire disparaitre.
                 */
                _f_RctHdlNone(true);
                
                /*
                 * [DEPUIS 10-07-15] @BOR
                 * On affiche toujours le spinner. C'est aux autres modules de le faire disparaitre.
                 */
                _f_RctHdlSpnr(true);
                
                /*
                 * [DEPUIS 10-07-15] @BOR
                 * On masque toujours le logo FRD-PRIVE.
                 */
                _f_RctHdlDnyAkx(false);
                
                /*
                 * [DEPUIS 11-07-15] @BOR
                 * On réinitialise les EVALs.
                 */
                $(".jb-css-csam-eval-chs-wrp").removeClass("this_hide");
                
                /*
                 * [DEPUIS 11-07-15] @BOR
                 * On remet le spiner central
                 */
                $(".jb-unq-mdl-spnr-mx").removeClass("this_hide");
            }
            
            /*
             * [DEPUIS 30-04-16]
             *      On réactive le système OVERFLOW de WINDOW, le cas échéant.
             */
            var atype = $(".jb-unq-art-mdl").data("atype");
            if ( atype && $.inArray(atype,["psmn","nwfd"]) === -1 ) {
                Kxlib_WindOverflow();
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*
     * [NOTE 02-03-15] @Loukz
     * Selon le cas, on décomente l'une des deux lignes ci-dessous.
     */
    var _f_Init = function (a) {
//    this._f_Init = function (a) {
        // ... Autres opérations préliminaires ... 
        $(".jb-unq-tv").prop("src","http://www.lorempixel.com/620/620").removeClass("this_hide");
        $(".jb-unq-c-a-tt-lk").prop("title","").find(".jb-unq-c-a-tt-lk-txt").text("Trenqr is the best website in the world, the cool place.");
        $(".jb-unq-u-b-owr-img-box").prop("src","http://www.lorempixel.com/70/70");
        $(".jb-unq-u-b-owr-psd").text("@Username");
        
        if ( a === true ) {
            $(".jb-unq-mx").removeClass("this_hide");
            $(".jb-unq-c-r").removeClass("this_hide");
            $(".jb-unq-rct-mx").removeClass("this_hide");
            $(".jb-unq-c-a-rct-bx-none").addClass("this_hide");
            $(".jb-unq-c-c-bot").removeClass("this_hide");
        }
    };
    
    /**
     * Décide s'il faut charger de nouveaux Articles.
     * Cette décision dépendant essentiellement de la page sur laquelle on se trouve ET de la chaine d'Articles que l'on parcourt.
     * 
     * @returns {undefined}
     */
    var _f_DigPF = function (p) {
        //DigPF = DigPoorFool
        try {
            if ( KgbLib_CheckNullity(p) | typeof p !== "string" ) {
                return;
            }
            
            /*
             * ETAPE : 
             * Déterminer si on est dans la situation adéquate.
             * On ne chargera d'Article que si 
             */
            if ( $.inArray(p, ["nwfd", "psmn", "fav", "tia-explr", "tia-phtotk"]) !== -1 ) {
                return false;
            } 
            
            //es = ElementS
            var $es;    
            /*
             * ETAPE :
             *      Vérifier si on respecte au moins une des conditions.
             *          On profite pour récupérer la liste des éléments
             */
            //tc : ToClick
            var $tc;
            switch (p.toUpperCase()) {
                case "TMLNR" :
                        $es = $(".jb-tmlnr-mdl-intr");
                        $tc = $(".jb-tmlnr-loadm-trg");
                    break;
                case "TRPG" :
                        $es = $(".jb-trpg-art-nest").find(".jb-mdl-tr-post-in-list");
                        $tc = $(".jb-trpg-loadm-trg");
                    break;
                default :
                    return;
            }
            
            /*
             * [DEPUIS 10-07-15] @BOR
             *  (1) On vérifie s'il y a plus de 3 Articles. 
             *      S'il y a moins de 3 Articles, il n'est pas necessaire de lancer l'opération.
             *      En effet, il est pratiquement impossible qu'il existe des Articles anterieurs.
             *  (2) <TMLNR> Dans ce cas, s'il y a plus de 3 Articles, on vérifie que le nombre d'Articles IML ne montre pas un signe, tel qu'il n'est nul besoin que de creuser plus profond.
             *      Si le nombre d'Articles ITR est inferieur à celui IML, cela veut dire qu'il n'y en a plus. Sinon, il y aurait toujours le même nombre d'Articles dans les deux colonnes.
             */
//            Kxlib_DebugVars([es.length < _f_Gdf().plmin_, p.toUpperCase() === "TMLNR", $(".jb-tmlnr-mdl-std").length > _f_Gdf().plmin_,_f_Gdf().plmin_]);
            if ( $es.length < _f_Gdf().plmin_ || ( p.toUpperCase() === "TMLNR" && $es.length < $(".jb-tmlnr-mdl-std").length ) ) {
                return;
            } else if ( $es.length <= _f_Gdf().plmin ) {
                $tc.click();
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /********** ARTICLE ************/
    var _f_OnDelArt = function (x) {
//    this.OnDeleteArticle = function () {
        try {
            
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            
            var a = $(x).data("action");
            switch (a) {
                case "del_start" :
                        $(".jb-unq-cfrm-bx-mx").removeClass("this_hide");
                        return;
                    break;
                case "del_abort" :
                        $(".jb-unq-cfrm-bx-mx").addClass("this_hide");
                        return;
                    break;
                case "del_confirm" :
                        //Con contnue
                    break;
                default :
                        return;
                    break;
            }
            
            /* 
             * Permet de supprimer l'Article dans la page.
             * A ce stade, on a forcement déjà enregistré l'Article cible dans 'item'.
             * * */
            var it = $(".jb-unq-art-mdl").data("item");
            
            //i = L'identifiant dans le DOM; a = L'idientifiant de l'item auprès du serveur; p = Le code de la page
            var t__ = Kxlib_DataCacheToArray(it);
            var i, a, p;
            if ( KgbLib_CheckNullity(t__) | !$.isArray(t__) ) {
                return;
            } else {
                a = t__[0][0], p = Kxlib_GetPagegProperties().pg;
                i = ( t__[0][1] && $(t__[0][1]) && $(t__[0][1]).length ) ? $(t__[0][1]) : null;
//            i = () ? Kxlib_ValidIdSel(t__[0][1]),
            }
            
//        Kxlib_DebugVars([a,p,i],true);
            
            /* On previent le serveur qu'il faut supprimer l'Article */
            var s = $("<span/>");
            _f_Srv_DelArt(p,a,s);
            
            //On ferme la fenetre du modele UNQ
            _f_OnClose();
            
            /*
             * ETAPE :
             * On masque l'Article dans le DOM.
             */
            if ( i ) {
                $(i).addClass("this_hide");
            }
            
            $(s).on("operended", function(e, d) {
                
                if (! KgbLib_CheckNullity(d) ) {
                    //** On vérfifie dans quel cas nous sommes en fonction des clés renvoyées par le serveur **//
                    if (d.hasOwnProperty("o_cap") && d.hasOwnProperty("o_pnb")) {
                        //CAS : On est sur TMLNR
                        
                        if (d.hasOwnProperty("o_cap") && !KgbLib_CheckNullity(d.o_cap)) {
                            $(".jb-u-sp-cap-nb").text(d.o_cap);
                        }
                        
                        //... et le nombre d'articles
                        if (d.hasOwnProperty("o_pnb") && !KgbLib_CheckNullity(d.o_pnb)) {
                            $(".jb-acc-spec-artnb").text(d.o_pnb);
                        }
                        
                    } else if (d.hasOwnProperty("t_snb") && d.hasOwnProperty("t_pnb")) {
//                    } else if (d.hasOwnProperty("o_ctrb") && d.hasOwnProperty("t_pnb")) {
                        //CAS : On est sur une page TRPG
                        
                        var o = new TrendHeader();
                        
                        /*
                         * On met à jour le nombre d'Abonnements
                         */
                        if (d.hasOwnProperty("t_snb") && !KgbLib_CheckNullity(d.t_snb)) {
                            o.UpdateFolwrCount(d.t_snb);
                        }
                        
                        /*
                         * ... et le nombre d'articles
                         */
                        if (d.hasOwnProperty("t_pnb") && !KgbLib_CheckNullity(d.t_pnb)) {
                            o.UpdatePostCount(d.t_pnb);
                        }
                    }
                    
                }
                
                //FINALLY
                /*
                 * ETAPE :
                 * On supprime l'élément du DOM
                 */
                var cli = $(i).clone();
                if ( i ) {
                    $(i).remove();
                }
                
                /*
                 * [NOTE 23-04-15] @BOR
                 * ETAPE :
                 * Dans le cas où on est sur une page TRPG, on replace visuellement les éléments.
                 * On envoie la sélection sinon les données risquent d'être faussés. 
                 * En effet, les éléments par défauts prennent aussi les Articles qui sont "hide" ou "invi".
                 * 
                 * Pour savoir si on est sur une page TRPG, on vérifie si le sélecteur lié à l'Article est reservé à TRPG.
                 */
//                Kxlib_DebugVars([$(cli).hasClass("jb-mdl-tr-post-in-list"), $(".jb-mdl-tr-post-in-list").filter(":visible").length],true);
                if ( $(cli).hasClass("jb-mdl-tr-post-in-list") && $(".jb-mdl-tr-post-in-list").filter(":visible").length ) {
                    /*
                     * [NOTE 29-04-15]
                     * ETAPE :
                     * On vérifie si le module d'ajout au niveau de la page TRPG, est ouvert.
                     * Dans ce cas, on le ferme.
                     */
                    if (! $("#nwtrdart-box").hasClass("this_hide") ) {
                        $(".jb-acc-nwtrart").click();
                    }
                    var $sl = $(".jb-trpg-art-nest").find(".jb-mdl-tr-post-in-list").filter(":visible");
                    var TR = new Trend();
                    TR._f_Shuffle($sl);
                }
            
                /*
                 * ETAPE :
                 * On notifie que l'Article a été supprimée avec succès
                 */
                var Nty = new Notifyzing();
                Nty.FromUserAction("ua_del_art");
                
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_SvCllrCtxt = function (p,t){
//    this._SaveCallerContext = function (p,t){
         if ( KgbLib_CheckNullity(p) | KgbLib_CheckNullity(t) ) { 
             return; 
         }
         
         var o, i;
         p = p.toLowerCase();
         switch (p) {
             case "tmlnr" :
             case "trpg" :
             case "nwfd" :
             case "fav" :
             case "tia-explr" :
             case "tia-phtotk" :
                    o = $(t).closest(".jb-unq-bind-art-mdl");
                    i = $(o).attr("id");
                    i = Kxlib_ValidIdSel($(o).attr("id"));
                 break;
             case "psmn" :
                    o = t;
                    i = "tq:skip_psmn"; //[09-04-15] @BOR Permet d'indiquer qu'il n'y a pas de référence en page
                break;
             default :
                break;
         }
         
//         if (! o.length ) {
//             return;
//         }
         
         //Cette sauvegarde n'est utile que pour les cas où la référence de l'Objet Unique est la meme ... 
         //... Dans d'autres situations, il faut récupérer dans la donnée dans data-item du 'header' UNQ
         _cox = [p,o];
         
         /* Sauvegarder dans "l'entete" unique */
         var s = $(".jb-unq-art-mdl").data("item");
         s = s.replace(/\[(.*),.*\]/g,"[$1,"+i+"]");
//         alert("SHAKIRA => "+$(".jb-unq-art-mdl").data("item"));
         $(".jb-unq-art-mdl").data("item",s);
//         alert("SHAKIRA => "+$(".jb-unq-art-mdl").data("item"));
        return o;
    };
     
    var _f_PprUnqMdl = function(p,t,s) {
//    this.PrepareUnqModel = function(p,t) {
        if ( KgbLib_CheckNullity(p) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(s) ) {
            return;
        }
        /*
         * [NOTE 08-07-14] 
         * A cette date, sur la page TMLNR, seuls les Articles de type Tendance sont afficheables en mode UNQ.
         * Cette remarque est pertinente dans le sens qu'il y a deux thèmes différents sur la dite page.
         * * */
        p = p.toLowerCase();
        var d,e;
                
        switch (p) {
            case "tmlnr" :
            case "trpg" :
            case "nwfd" :
            case "fav" :
            case "tia-explr" :
            case "tia-phtotk" :
                    d = _f_GDatasTrArtMdl(t);
                break;
            case "psmn" :
                    d = _f_PullVolArtDatas(t,s);
                break;
            default : 
                return;
            
        };
        
        _f_PprArtUnqMdl_Ffil(d,p);
        
        return d;
    };  
    
    this.RebindUNqMdl = function(e) {
//        var th = this;
        /**** UNQ SEE MORE *****/
        $(e).find(".jb-unq-show-addrct-trg, .jb-unq-show-addrct-trg > *").click(function (e) {
            Kxlib_PreventDefault(e);

            _f_ShwAddRBox();
        });

        $(e).find(".jb-unq-rst-add-rct").click(function (e) {
            Kxlib_PreventDefault(e);

            _f_RstNwRForm();
        });
    
        /**** REACTIONS ASIDE ****/

        $(e).find(".jb-unq-add-rct-ipt").off().keypress(function(e){
            if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) { 
                Kxlib_PreventDefault(e); //[DEPUIS 11-07-15] @BOR
                
                $(".jb-unq-add-rct-trg").click();
//                _f_AddRct(); //[DEPUIS 11-07-15] @BOR
            }
        });

        $(e).find(".jb-unq-add-rct-trg").off().click(function(e){
            Kxlib_PreventDefault(e);

            _f_AddRct();
        });

        $(e).find(".jb-unq-react-del").click(function(e){
            Kxlib_PreventDefault(e);

            _f_DelRct(this);
        });
        
        $(e).find(".jb-unq-react-answ").click(function(e){
            Kxlib_PreventDefault(e);

            _f_AnswRct(this);
        });
    
        return e;
    };
    
    var _f_GDatasTrArtMdl = function(t) {
//    this.GetDatasTrArtMdl = function(t) {
        /* Permet de récupérer les données dont on a besoin pour créer un UNQ-MDL
         * Les données sont récupérées à partir d'un Article de type TMLNR-TR.
         * 
         * Les données nécessaires :
         * Article : itemid, image principale, le texte de présentation accompagnant l'image, les données relatives à la date de création, les données d'EVAL  
         * TREND : trid, le titre de la Tendance
         * Owner : ueid, FullName, Pseudo, photo de profil
         * * */
        
        try {
            if ( KgbLib_CheckNullity(t) ) {
                return;
            }

            /* On récupère les données du data-cache pour créer un objet facilement utilisable par le Caller */
            //On récupère le data-cache du model
            var b = $(t).closest(".jb-unq-bind-art-mdl");
            
            var r, $a = $(t).closest(".jb-unq-bind-art-mdl"), isajca = false;
//            if ( KgbLib_CheckNullity($(b).data("cache")) && !KgbLib_CheckNullity($(b).data("ajcache")) && !KgbLib_CheckNullity($(b).data("trq-ver")) ) {
    
            if ( !KgbLib_CheckNullity($(b).data("ajcache")) && !KgbLib_CheckNullity($(b).data("trq-ver")) && $.inArray($(b).data('atype'),["itr","tia-explr","tia-phtotk","inml","intr","fav"]) !== -1 ) {
                r = _f_ExAJCDatas(b);
                isajca = true;
                
//                Kxlib_DebugVars([$(b).data('trds').trtle],true);
//                Kxlib_DebugVars([$(b).data('trds'),Kxlib_Decode_After_Encode($(b).data('trds'))],true);
//                Kxlib_DebugVars([JSON.stringify(r)],true);
//                return;
            } else {
                
                var chd = $(b).data("cache");

                var at = ( $(b).find(".kxlib_tgspy").length ) ? $(b).find(".kxlib_tgspy").html() : "";
                d = Kxlib_DataCacheToArray(chd);

                /*
                 * RAPPEL : ['%itemid%','%artpic%','%artdesc%','%trid%','%trtitle%','%rnb%','%trhref%'],['%time%','%utc%'],['%eval_p2%','%eval_p1%','%eval_m1%','%eval_tot%','%eval_list_u1%','%eval_list_u2%','%eval_list_u3%','%eval_list_total%'],['%ueid%','%ufn%','%upsd%','%uppic%','%uhref%'],['%my_eval%']
                 */

                r = {
                    "itemid"    : d[0][0][0],
                    "artpic"    : d[0][0][1],
                    "vidu"      : $(b).data("vidu"),
    //                "artdesc"   : d[0][0][2], //[DEPUIS 27-04-15] @BOR
                    "trid"      : d[0][0][3],
    //                "trtitle"   : d[0][0][4], //[DEPUIS 27-04-15] @BOR
                    //d[0][0][5] correspond à rnb. Dans UNQ on en a pas besoin car on refait une demande auprès du serveur
                    "trhref"    : d[0][0][6],
                    "prmlk"     : d[0][0][7],
                    "time"      : d[0][1][0],
                    "utc"       : d[0][1][1],
                    "alltime"   : at,
                    "ep2"       : d[0][2][0],
                    "ep1"       : d[0][2][1],
                    "em1"       : d[0][2][2],
                    "etl"       : d[0][2][3],
                    "evlt_u1"   : d[0][2][4],
                    "evlt_u2"   : d[0][2][5],
                    "evlt_u3"   : d[0][2][6],
                    "evlt_tl"   : d[0][2][7],
                    "ueid"      : d[0][3][0],
                    "ufn"       : d[0][3][1],
                    "upsd"      : d[0][3][2],
                    "uppic"     : d[0][3][3],
                    "uhref"     : d[0][3][4],
                    "myel"      : d[0][4][0]
                };
                
            }
            
            
            /*
             * [DEPUIS 27-04-15] @BOR
             * L'insertion des données provenant de l'utilisateur peuvent contenir des caractères qui pourrait rendre difficile leur traitement.
             * En effet, certains caractères comme : ' " [ ou  ] rendent le traitement plus difficile. Plusieurs solutions ont été abordées.
             * La plus simple, efficace et la plus sure est de les insérer dans une balise HTML non visible. Cela nous affranchies de toutes opérations d'encodage/decodage.
             */
            if ( isajca && $.inArray($(b).data('atype'),["itr","fav"]) !== -1 ) {
                Kxlib_DebugVars(["UNQ : NOUVEAU AJCA CASE"]);
                r["artdesc"] = $("<div/>").html(r.orim).text();
                r["trtitle"] = ( r.trtitle ) ? $("<div/>").html(r.trtitle).text() : null;
            } else if ( isajca && $.inArray($(b).data('atype'),["tia-explr","tia-phtotk","inml","intr"]) !== -1 ) {
                Kxlib_DebugVars(["UNQ : NOUVEAU AJCA CASE"]);
                r["artdesc"] = r.orim;
                r["trtitle"] = ( r.trtitle ) ? $("<div/>").html(r.trtitle).text() : null;
            } else if ( $a.find(".jb-tqr-cldstrg") && $a.find(".jb-tqr-cldstrg").length ) {
                r["artdesc"] = ( $a.find(".jb-tqr-cldstrg").find(".jb-tqr-csg-elt[data-item='adsc']").length ) ? $a.find(".jb-tqr-cldstrg").find(".jb-tqr-csg-elt[data-item='adsc']").text() : "";
                r["trtitle"] = ( $a.find(".jb-tqr-cldstrg").find(".jb-tqr-csg-elt[data-item='trtle']").length ) ? $a.find(".jb-tqr-cldstrg").find(".jb-tqr-csg-elt[data-item='trtle']").text() : "";
            } else if ( $(b).data('atype') && $(b).data('atype') === "fav" && !r.istrd ) {
                r["artdesc"] = ( $a.find(".jb-tmlnr-pgfv-art-i-txt").length ) ? $a.find(".jb-tmlnr-pgfv-art-i-txt .desc").data("dsc") : "";
                r["trtitle"] = null;
            } else if ( $(b).data('atype') && $(b).data('atype') === "fav" && $(b).data('trds') ) {
                var __ds = $(b).data('trds'); 
                r["artdesc"] = ( $a.find(".jb-tmlnr-pgfv-art-i-txt").length ) ? $a.find(".jb-tmlnr-pgfv-art-i-txt .desc").data("dsc") : "";
                r["trtitle"] = ( $(b).data('trds') && __ds && __ds.trtle ) ? __ds.trtle : "";
            } else if ( r && $(b).data('atype') && $.inArray($(b).data('atype'),["tia-explr","tia-phtotk"]) !== -1 ) {
                r["artdesc"] = r.orim;
                r["trtitle"] = r.trtitle;
            }
            
//                    ...
            //*
//            Kxlib_DebugVars([JSON.stringify(r)],true);
//            Kxlib_DebugVars([r.time,r.alltime],true);
//            Kxlib_DebugVars([r.artdesc,r.trtitle],true);
//            return;
            //*/
            /*
            if ( r.itemid === "7fbbjof4" ) {
                Kxlib_DebugVars([r.trtitle],true);
            }
            //return;
            //*/
            /*
            var w__ = $(t).closest(".jb-unq-bind-art-mdl").data("with");
            if ( w__ && w__.length ) {
                var ustgs = $(t).closest(".jb-unq-bind-art-mdl").data("with");
                ustgs = Kxlib_DataCacheToArray(ustgs)[0];
                r.ustgs = ( $.isArray(ustgs[0]) ) ? Kxlib_GetColumn(3,ustgs) : [ustgs[3]];
            }
            //*/
            
            /*
             * [DEPUIS 30-04-16]
             */
            if ( isajca && $(b).data("ajcache") ) {
//                var ajca_o = ( typeof $(b).data("ajcache") === "object" ) ? $(b).data("ajcache") : JSON.parse("'"+$(b).data("ajcache")+"'");
                var ajca_o = ( typeof $(b).data("ajcache") === "object" ) ? $(b).data("ajcache") : JSON.parse($(b).data("ajcache"));
                r["hashs"] = ( ajca_o.hashs ) ? ajca_o.hashs : null;
                r["ustgs"] = ( ajca_o.ustgs ) ? ajca_o.ustgs : null;
            } else {
                r["hashs"] = null;
                r["ustgs"] = null;
            }
            
           
//        Kxlib_DebugVars([JSON.stringify(r)],true);
//        Kxlib_DebugVars([r.ustgs,r.hashs],true);
//        Kxlib_DebugVars([JSON.stringify(r.ustgs[0]),Kxlib_GetColumn(3,r.ustgs[0])],true);
//        Kxlib_DebugVars([typeof r.ustgs,JSON.stringify(r.ustgs)],true);
//        Kxlib_DebugVars([typeof r.ustgs,JSON.stringify(r.ustgs),Kxlib_GetColumn(2,r.ustgs)],true);
//            return;
            return r;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    var _f_PullVolArtDatas = function(x,s) {
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        try {
            
            var r;
            if ( $(x) && $(x).length && !KgbLib_CheckNullity($(x).data("acache")) ) {
                /*
                 * Il se peut que l'élément ait déjà son "acache". En effet, on ne récupère les données qu'une seule fois puis on les sauvegarde dans l'entete de l'élément.
                 * Tant que l'élément cible garde une présenc dans le DOM, on aura encore et toujours ces données.
                 */
                
                /* On récupère les données du data-cache pour créer un objet facilement utilisable par le Caller */
                var ac = $(x).data("acache");
                
        //        Kxlib_DebugVars([typeof f,f],true);
                d = Kxlib_DataCacheToArray(ac);
                r = {
                    "itemid"    : d[0][0][0],
                    "artpic"    : d[0][0][1],
                    "artdesc"   : d[0][0][2],
                    "trid"      : d[0][0][3],
                    "trtitle"   : d[0][0][4],
                    //d[0][0][5] correspond à rnb. Dans UNQ on en a pas besoin car on refait une demande auprès du serveur
                    "trhref"    : d[0][0][6],
                    "prmlk"     : d[0][0][7],
                    "time"      : d[0][1][0],
                    "utc"       : d[0][1][1],
                    "alltime"   : null, //[NOTE 11-07-15] @BOR Pas finalisé
                    "ep2"       : d[0][2][0],
                    "ep1"       : d[0][2][1],
                    "em1"       : d[0][2][2],
                    "etl"       : d[0][2][3],
                    "evlt_u1"   : d[0][2][4],
                    "evlt_u2"   : d[0][2][5],
                    "evlt_u3"   : d[0][2][6],
                    "evlt_tl"   : d[0][2][7],
                    "ueid"      : d[0][3][0],
                    "ufn"       : d[0][3][1],
                    "upsd"      : d[0][3][2],
                    "uppic"     : d[0][3][3],
                    "uhref"     : d[0][3][4],
                    "myel"      : d[0][4][0]
                };
                
                var w__ = $(x).data("awith");
                if ( w__ && w__.length ) {
                    var ustgs = $(x).data("awith");
                    ustgs = Kxlib_DataCacheToArray(ustgs)[0];
                    r.ustgs = ( $.isArray(ustgs[0]) ) ? Kxlib_GetColumn(3,ustgs) : [ustgs[3]];
                }
                
                return r;
            } else if (! KgbLib_CheckNullity($(x).data("ai")) ) {
                var ai__ = $(x).data("ai");
                /*
                 * On lance l'opération de récupération des données auprès du serveur.
                 */
                var s2 = $("<span/>");
                _f_Srv_PA([ai__],s2);
            
                $(s2).on("datasready",function(e,o,d){
                    if ( KgbLib_CheckNullity(d) ) {
                        return;
                    }
                    
                    /*
                     * [DEPUIS 11-07-15] @BOR
                     */
                    var s1__ = "<span class='tgs-frm'></span>";
                    var s2__ = "<span class='tgs-val'></span>";
                    var s3__ = "<span class='tgs-uni'></span>";
                    var at = s1__.toString()+s2__.toString()+s3__.toString();
                    
                    d = $(d).get(0);
                    r = {
                        "itemid"    : d.id,
                        "artpic"    : d.img,
                        "artdesc"   : d.msg,
                        "trid"      : ( d.hasOwnProperty("trd_eid") && d.trd_eid ) ? d.trd_eid : null,
                        "trtitle"   : ( d.hasOwnProperty("trtitle") && d.trtitle ) ? d.trtitle : null,
                        //d[0][0][5] correspond à rnb. Dans UNQ on en a pas besoin car on refait une demande auprès du serveur
                        "trhref"    : ( d.hasOwnProperty("trhref") && d.trhref ) ? d.trhref : null,
                        "prmlk"     : d.prmlk,
                        "time"      : d.time,
                        "utc"       : null,
                        "alltime"   : at,
                        "ep2"       : ( d.hasOwnProperty("eval") && d.eval && $.isArray(d.eval) && d.eval.length === 4 ) ? d.eval[0] : null,
                        "ep1"       : ( d.hasOwnProperty("eval") && d.eval && $.isArray(d.eval) && d.eval.length === 4 ) ? d.eval[1] : null,
                        "em1"       : ( d.hasOwnProperty("eval") && d.eval && $.isArray(d.eval) && d.eval.length === 4 ) ? d.eval[2] : null,
                        "etl"       : ( d.hasOwnProperty("eval") && d.eval && $.isArray(d.eval) && d.eval.length === 4 ) ? d.eval[3] : null,
                        "evlt_u1"   : null,
                        "evlt_u2"   : null,
                        "evlt_u3"   : null,
                        "evlt_tl"   : null,
                        "ueid"      : d.uid,
                        "ufn"       : d.ufn,
                        "upsd"      : d.upsd,
                        "uppic"     : d.uppic,
                        "uhref"     : d.uhref,
                        "myel"      : ( d.hasOwnProperty("me") && d.me ) ? d.me : null,
                        "hatr"      : d.hatr,
                        "hasfv"     : d.hasfv,
                        "fvtp"      : d.fvtp
                    };

//                    Kxlib_DebugVars([JSON.stringify(r)],true);
//                    Kxlib_DebugVars([JSON.stringify(d.ustgs),JSON.stringify(d.hashs)],true);
                    /*
                    if ( d.hasOwnProperty("ustgs") && d.ustgs && $.isArray(d.ustgs) && d.ustgs.length ) {
                        var ustgs = d.ustgs;
                        r.ustgs = ( $.isArray(ustgs[0]) | ( typeof ustgs[0] === "object" && Kxlib_ObjectChild_Count(ustgs[0]) ) ) ? Kxlib_GetColumn(3,ustgs) : [ustgs[3]];
                        //[DEPUIS 30-04-15] @BOR
                        r.artdesc = $("<div/>").html(d.adesc).text();
                    }
                    //*/
                    /*
                     * [DEPUIS 02-05-16]
                     */
                    r.ustgs = d.ustgs;
                    r.hashs = d.hashs;
//                    Kxlib_DebugVars([JSON.stringify(r.hashs),JSON.stringify(r.ustgs)],true);

                    var rds = [o,r];
                    $(s).trigger("datasready",rds);
                });
                
                $(s2).on("operended",function(e){
                    //L'utilisateur va finir par comprendre qu'une erreur s'est déclenchée
                    _xhr_pvad = null;
                    return;
                });
            } else {
                return;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
    
    var _f_ExAJCDatas = function (b) {
        try {
            if ( KgbLib_CheckNullity(b) | KgbLib_CheckNullity($(b).data("ajcache")) | KgbLib_CheckNullity($(b).data("trq-ver")) ) {
                return;
            }
            
            var dc = $(b).data("ajcache"), vr = $(b).data("trq-ver"), r;
            var jds = ( typeof $(b).data("ajcache") === "object" ) ? $(b).data("ajcache") : JSON.parse(dc);
            if ( vr === "ajca-v10" && jds ) {
                var at  = ( $(b).find(".kxlib_tgspy").length ) ? $(b).find(".kxlib_tgspy").html() : "";
                r = {
                    "itemid"    : jds.id,
                    "artpic"    : jds.img,
                    "vidu"      : $(b).data("vidu"), //[DEPUIS 14-04-16] @BOR
    //                "artdesc"   : d[0][0][2], //[DEPUIS 27-04-15] @BOR
                    "orim"      : jds.msg, //[DEPUIS 14-04-16] @BOR
                    "istrd"     : jds.istrd,
                    "trid"      : ( jds.istrd && jds.trd_eid ) ? jds.trd_eid : null,
                    "trtitle"   : ( jds.istrd && jds.trtitle ) ? jds.trtitle : null,
                    "trhref"    : ( jds.istrd && jds.trhref ) ? jds.trhref : null,
                    "prmlk"     : jds.prmlk,
                    "time"      : jds.time,
                    "utc"       : null,
                    "alltime"   : at,
                    "ep2"       : jds.eval[0],
                    "ep1"       : jds.eval[1],
                    "em1"       : jds.eval[2],
                    "etl"       : jds.eval[3],
                    "evlt_u1"   : null,
                    "evlt_u2"   : null,
                    "evlt_u3"   : null,
                    "evlt_tl"   : null,
                    "ueid"      : jds.ueid,
                    "ufn"       : jds.ufn,
                    "upsd"      : jds.upsd,
                    "uppic"     : jds.uppic,
                    "uhref"     : jds.uhref,
                    "myel"      : jds.myel,
                    //[DEPUIS 24-12-15]
                    "fvtm"      : jds.fvtm,
                    "fvtp"      : jds.fvtp,
                    "hasfv"     : jds.hasfv,
                    "isod"      : ( jds.isod ) ? true : false,
                    //[DEPUIS 24-12-15]
                    "ustgs"     : jds.ustgs,
                    "hashs"     : jds.hashs,
                };

            }
            
            return r;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
        }
    };
            
    /***** ASIDE REACT ****/
    var _f_AddRct = function (x) {
//    this.AddReact = function () {
        try {
            /*
             * [DEPUIS 01-06-15] @BOR
             */
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).hasClass("disabled") | ( $(".jb-unq-add-rct-ipt").attr("disabled") === true || $(".jb-unq-add-rct-ipt").attr("disabled") === "disabled" ) ) {
                return;
            }
            
//            Kxlib_DebugVars([UNIQUE -> 698"]);
            var s = $("<span/>");
            var i = $(".jb-unq-art-mdl").data("item");
            i = i.replace(/\[(.*),.*\]/g, "$1");
            
            //On vérifie qu'il y a du texte 
            var t = $(".jb-unq-add-rct-ipt").val();
            if ( KgbLib_CheckNullity(t) ) {
                $(".jb-unq-add-rct-ipt").addClass("error_field");
                return;
            } else if ( !KgbLib_CheckNullity(t) && (t.match(/\s/g) || t.match(/\t/g) || t.match(/\n/g) || t.match(/\r/g) || t.match(/\r\n/g)) ) {
                //On vérifie s'il n'y a que des \n ou autres représentations de "retour chariot" dans le texte
                var c1 = (t.match(/\n/g)) ? t.match(/\n/g).length : 0;
                var c2 = (t.match(/\r/g)) ? t.match(/\r/g).length : 0;
                var c3 = (t.match(/\r\n/g)) ? t.match(/\r\n/g).length : 0;
                var c4 = (t.match(/\t/g)) ? t.match(/\t/g).length : 0;
                var c5 = (t.match(/\s/g)) ? t.match(/\s/g).length : 0;
                
                if ( c1 === t.length || c2 === t.length || c3 === t.length || c4 === t.length || c5 === t.length ) {
                    $(".jb-unq-add-rct-ipt").addClass("error_field");
                    return;
                } else {
                    $(".jb-unq-add-rct-ipt").removeClass("error_field");
                    
                    //On enlève le focus
//                    $(".jb-unq-add-rct-ipt").blur(); //[DEPUIS 11-07-15] @BOR
                }
                
            } else { 
                $(".jb-unq-add-rct-ipt").removeClass("error_field");
                
                //On enlève le focus
//                $(".jb-unq-add-rct-ipt").blur(); //[DEPUIS 11-07-15] @BOR
            }
            
            
            /*
             * [07-04-15] @BOR
             * ETAPE : 
             * On récupère le dernier commentaire pour permettre de récupérer de potentiels commentaires ajoutés avant celui ci mais qui ne sont pas affichés
             */
            var lri, lrt;
            var $rmax = $(".jb-unq-rct-mx");
            /*
             * [NOTE 07-04-15] @BOR
             * Ne considérer que les éléments visibles améliore la stabilité du processus.
             * Un Commentaire peut être invisible car il est en attente de suppression ou pour une toute autre raison.
             * Si l'élément est invisible alors il n'est pas disponible pour traitement.
             */
            
            if ($rmax.find(".jb-unq-react-mdl") && $rmax.find(".jb-unq-react-mdl").length && $rmax.find(".jb-unq-react-mdl").filter(":visible").last().length && !KgbLib_CheckNullity($rmax.find(".jb-unq-react-mdl").filter(":visible").last().data("item"))) {
                var $to__ = $rmax.find(".jb-unq-react-mdl").filter(":visible").last();
                lri = $to__.data("item");
                lrt = $to__.data("time");
            }
            
            //On envoie les coordonnées au niveau du serveur
            _f_Srv_AddRct(i, t, lri, lrt, s);
            
            /*
             * [NOTE 02-09-14] @author L.C.
             * On vide l'input pour éviter que l'utilisateur ajoute plusieurs fois son commentaire car l'aller-retour vers le serveur est trop long.
             * Si on a un problème, le message ne s'affichera pas tout court. 
             * Dans une évolution, on fera apparaittre un message d'erreur dans le bloc des commentaires.
             * [00:42] 
             * Il semble que cela venait de la fonction qui fait la liaison avec le serveur.
             * Quand on passe les données via Trigger(), il faut les passer via un tableau
             */
            _f_RstNwRForm();
            
            $(s).on("datasready", function(e,d) {
                if ( KgbLib_CheckNullity(d.rs) | KgbLib_CheckNullity(d.arn) ) {
                    return;
                }
                
                //On reset le formulaire
//                _f_RstNwRForm(); //[DEPUIS 11-07-15] @BOR
                
               /*
                * [DEPUIS 04-07-15] @BOR
                * On retire NoOne
                */
               _f_RctHdlNone(false);
                
                /*
                 * [NOTE 07-04-15] @BOR
                 * La ligne ci dessous est necessaire pour pouvoir ajouter le ou les Commentaires dans le bon ordre, mais pas que.
                 * Elle permet de créer un "vrai" objet, pourquoi ? Je n'ai pas l'explication.
                 * Dans le cas contraire, s'il n'y a qu'un élément, each() va itérer les elements de la première ligne comme s'il y avait plusieurs lignes.
                 * Il ne faudra modifier l'instruction qu'en connaissance de cause.
                 */
                d.rs = $(d.rs).get().reverse();
                var add = [];
                $.each(d.rs,function(x,rd) {
                    if ( $(".jb-unq-react-mdl[data-item='"+rd.itemid+"']").length ) {
                        return;
                    }
                    
                   /*
                    * [DEPUIS 30-04-15] @BOR
                    * ETAPE :
                    * On s'assure que le Commentaire sera chargé pour le bon Article.
                    */
                    var sl = $(".jb-unq-art-mdl").data("item");
                    var ai__ = sl.replace(/\[(.*),.*\]/g, "$1");
                    if ( rd.raid.toLowerCase() !== ai__.toLowerCase() ) {
                        return true;
                    }
                
                    //on crée le modèle de commentaire
                    var e = _f_PprUnqRct(rd);

                    //On rebind les événements
                    e = _f_RebindNwRct(e);

                    //On ajoute le commentaire
                    $(e).appendTo(".jb-unq-c-a-r-bx");
//                    $(e).hide().appendTo(".jb-unq-c-a-r-bx").fadeIn();
    //            $(e).hide().prependTo(".jb-unq-c-a-r-bx").fadeIn();
                
                    /* 
                     * [NOTE 30-04-15] @BOR
                     * Permet d'afficher un nombre de commentaires cohérent depuis qu'on insère que les Commentaires qui sont ajoutés dans le bon module UNQ.
                     */
                    add.push($(e));
                });
                
                var $tar = $(".jb-unq-c-a-r-bx");
                /*
                 * [06-04-15] @BOR
                 * ETAPE :
                 * On scroll jusqu'à la fin de la zone de texte.
                 * [08-05-15] @BOR
                 * On utilise la technique qui permet de faire la somme des tailles des Commentaires.
                 * Elle est plus adéquate et fiable.
                 */
//                $($tar).animate({scrollTop: $($tar).height()}, 1000);
                _f_ScrollZn();
                        
                /*
                 * ETAPE :
                 * On met à jour le nombre de Commentaires au niveau du footer 
                 */
                if ( add.length ) {
                    _f_SetReactNb(d.arn);
                }
                
                //On met à jour les données au niveau du modele en colonne
                _f_UpdArtInPg(d.arn);
//                _f_UpdArtInPg(_f_GetReactNb()); //[DEPUIS 17-0415]
                
                /*
                 * ETAPE :
                 * On fait apparaitre un message de notification
                 * RAPPEL : Pour la partie UNQ on n'affiche que les notifications standard.
                 */
                var Nty = new Notifyzing();
                Nty.SignalForNewReaction(1);
            });
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_UpdArtInPg = function (n) {
//    this._UpdateArtInPage = function (n) {
        
        if ( KgbLib_CheckNullity(n) ) { return; }
        
        try {
            var sl = $(".jb-unq-art-mdl").data("item");
            sl = sl.replace(/\[.*,(.*)\]/g,"$1");
//            sl = Kxlib_ValidIdSel(sl.replace(/\[.*,(.*)\]/g,"$1"));
//            Kxlib_DebugVars([729,sl,sl !== ":0_1"],true);
            /*
             * [NOTE 09-14-15] @BOR
             * Avec la possibilité d'accéder à des Articles qui ne sont pas dans une page, on est obliger de tester si l'Article existe.
             * En effet, UNIQUE peut servir à afficher des Articles à partir d'un simple identifiant. Il récupère les données par ses propres moyens.
             * Aussi, on ne met à jour que les données au niveau de UNQ. Dans le cas où on a assez de chance pour que cet Article se trouve dans la page support, on procède à la mise à jour.
             */
            if (! ( sl !== ":0_1" && sl !== "tq:skip_psmn" && $(sl) && $(sl).length ) ) {
                return;
            }
            
//            Kxlib_DebugVars(["HTML -> "+$(sl).attr("id"),$(sl).html()],true);
            $(sl).find(".jb-unq-react").text(n);
//            Kxlib_DebugVars(["Sure it is -> "+$(sl).find(".jb-unq-react").text(),n],true);

//            Kxlib_DebugVars(["HTML -> "+$(sl).data("cache")],true);
            /* On met à jour l'entete de l'Article en page */
            var j = $(sl).data("cache");
            j = Kxlib_AlterDataCacheAt(j,n,0,5);
            $(sl).data("cache",j);
//            Kxlib_DebugVars(["Sure it is -> "+$(sl).data("cache")],true);

            /* Dans le cas d'un modèle MOZ de NWFD, on met à jour le data cache de la zone permettant l'apparition de la barre d'infos */
            if ( $(sl).find(".nwfd-b-m-mdl-trig").length ) {
                 var h = $(sl).find(".nwfd-b-m-mdl-trig").data("cache");
                h = Kxlib_AlterDataCacheAt(h,n,4,0);
                $(sl).find(".nwfd-b-m-mdl-trig").data("cache",h);
            }
                    
                    
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    var _f_RebindNwRct  = function (e) {
//    this._RebindNewReact  = function (e) {
        $(e).find(".jb-unq-react-del, .jb-unq-rct-fnl-dc").click(function(e){
            Kxlib_PreventDefault(e);
            
            _f_DelRct(this);
        });
        
        $(e).find(".jb-unq-react-answ").click(function(e){
            Kxlib_PreventDefault(e);
            
            _f_AnswRct(this);
        });
        
        return e;
    };
    
    var _f_DelRct = function (x) {
//    this.DelReact = function (a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) { 
                return; 
            }
            
            var a = $(x).data("action");
            var $mdl = $(x).closest(".jb-unq-react-mdl");
            switch (a.toString()) {
                case "start_delete" : 
                        $mdl.find(".jb-unq-react-del").addClass("this_hide");
                        $mdl.find(".jb-unq-react-answ").addClass("this_hide");
                        
                        $mdl.find(".jb-unq-rct-fnl-dcs-mx").removeClass("this_hide");
                    return;
                case "abort_delete" :
                        $mdl.find(".jb-unq-rct-fnl-dcs-mx").addClass("this_hide");
                        
                        $mdl.find(".jb-unq-react-del").removeClass("this_hide");
                        $mdl.find(".jb-unq-react-answ").removeClass("this_hide");
                    return;
                case "confirm_delete" : 
                    break;
                default:
                    return;
            }
            
            if ( $(x).data("lk") === 1 ) {
//                Kxlib_DebugVars([DEL_RCT => Lock"]);
                return;
            }
            
            var s = $("<span/>");
            var tpi = Kxlib_ValidIdSel($(x).data("target"));
            var i = $(tpi).data("item");
            var ai = $(".jb-unq-art-mdl").data("item");
            ai = ai.replace(/\[(.*),.*\]/g, "$1");
            
            //On envoie les données au niveau du serveur
            /*
             * [NOTE 02-09-14] @BOR "On envoie aussi le bloc au cas où il y aurait une erreur et qu'il faudrait le faire disparaitre"
             */
            _f_Srv_DelRct(ai,i,$(tpi),s);
            
           /*
            * [DEPUIS 30-04-15] @BOR
            * On masque le commentaire pour empecher l'utilisateur de cliquer une deuxieme fois sur le bouton.
            * De plus, on bloque aussi le bouton.
            */
            $(x).closest(".jb-unq-react-mdl").addClass("this_invi"); 
            $(x).data("lk",1);
            
            $(s).on("operended", function(e,d) {
                /*
                 * On retire défintivement le commentaire
                 */
                $(x).closest(".jb-unq-react-mdl").hide().remove();
                
                //On notifie que l'article a été supprimée avec succès
                var Nty = new Notifyzing ();
                Nty.FromUserAction("ua_del_react");
                
                //On met à jour le nombre de commentaires
                _f_SetReactNb(d.arn);
                
                //On met à jour le nombre de commentaires coté page
                _f_UpdArtInPg(d.arn);
//            _f_UpdArtInPg(_f_GetReactNb()); //[DEPUIS 17-04-15] @BOR

               /*
                * [DEPUIS 04-07-15] @BOR
                * On retire vérifie avec NoOne
                */
               _f_RctHdlNone();
               
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_AnswRct = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) { 
                return; 
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            var $ps = $(x).closest(".jb-unq-react-mdl").find(".jb-unq-react-auth");
            if ( !$ps.length | KgbLib_CheckNullity($ps.text()) ) {
                return;
            }
            
            var ps = $ps.text();
            $(".jb-unq-add-rct-ipt").val(function(a,prev){
                return prev.concat(ps," ");
            });
            _f_ShwAddRBox(true);
            
            $(x).data("lk",0);
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_OnVztrAkxDelArt = function (i) {
//    this.DecideOn_VisitorAkxDelArt = function (i) {
        //i : Représente 'ueid' lié à l'article. Il s'agit donc du propriétaire de l'article.
        /* Permet de savoir si l'utilisateur actif a le droit d'accéder au lien qui permet de supprimer
         * ... l'article désigné par son id.
         * Pour cela, on verifie si l'artcle appartient à l'utilisateur actif.
         * * */
        
        if ( KgbLib_CheckNullity(i) ) { return; }
            
        try {
            //owi = OWnerId
            owi = i;
            
            /* Permet de savoir si l'utilisateur a le droit d'avoir accès à la possibilité de sup n'importe quel commentaire */
            PM = new PERM();
            
            var o = {
                "rco": owi
            };
            
            var r = PM.PermForFeatures("UNQ_AKX_DEL_ART",o);
            return ( !r ) ? null : r ;
                    
        } catch(e) {
            return;
        }
    };
    
    var _f_OnVztrAkxDelRct = function (i) {
//    this.DecideOn_VisitorAkxDelReact = function (i) {
        //i : Représente 'ueid' lié au commentaire. Il s'agit donc du propriétaire du commentaire.
        /* Permet de savoir si l'utilisateur actif a le droit d'accéder au lien qui permet de supprimer 
         * ... le commentaire désigné par son id.
         * Pour cela, on verifie si le commentaire appartient à l'utilisateur actif.
         * * */
        
        if ( KgbLib_CheckNullity(i) ) return;
            
        try {
            //owi = OWnerId
            owi = i;
            
            /* Permet de savoir si l'utilisateur a le droit d'avoir accès à la possibilité de sup n'importe quel commentaire */
            PM = new PERM();
            
            var o = {
                "rco": owi
            };
            
            var r = PM.PermForFeatures("UNQ_AKX_DEL_REACT",o);
            return ( !r ) ? null : r ;
                    
        } catch(e) {
            return;
        }
    };
    
    var _f_OnVztrAkxDelAnyRct = function () {
//    this.DecideOn_VisitorAkxDelAnyReact = function () {
        /* Permet de savoir si l'utilisateur actif a théoriquement le droit d'avoir accès à la fonctionnalité de suppression de tous les commentaires */
        var owi, PM;
        try {
            //owi = OWnerId
            var cache = $(".jb-unq-art-mdl.active").data("cache");
            owi = Kxlib_DataCacheToArray(cache)[0][1][0];
            
            /* Permet de savoir si l'utilisateur a le droit d'avoir accès à la possibilité de sup n'importe quel commentaire */
            PM = new PERM();
            var o = {
                "rco": owi
            };

            var r = PM.PermForFeatures("UNQ_AKX_DEL_ANYREACT",o);
            return ( !r ) ? null : r ;
        
        } catch (e) {
//            Kxlib_DebugVars([e],true);
            return;
        }
    };
    
    var _f_GtAlRctsLmt = function (i) {
//    this._GetAllReactsLimit = function (i) {
        /* Récupère les données des commentaires de l'Article dans la limite de x (définie par le serveur) 
         * Les données sont ensuite affichées.
         * */
        try {
            if ( KgbLib_CheckNullity(i) ) { 
                return; 
            }
            
            /*
             * [DEPUIS 04-05-15] @BOR
             * ETAPE : 
             * On vérifie si une requete est en cours.
             * Dans ce cas, on l'annule pour nous assurer qu'on n'affichera que la requete la plus récente.
             */
            if (! KgbLib_CheckNullity(_xhr_plrs) ) {
                _xhr_plrs.abort();
            }
            
            //xt : eXecutionTime
            var s = $("<span/>"), xt = (new Date()).getTime(); 
            $(".jb-unq-c-a-r-bx").data("plrt",xt);
            _f_Srv_GAllReactLmt(i,xt,s);
                    
            $(s).on("datasready", function(e,d) {
                if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(d.rtab) | KgbLib_CheckNullity(d.extm) | KgbLib_CheckNullity(d.arn) ) {
                    return;
                }
                
               /*
                * [DEPUIS 11-07-15] @BOR
                *   Si on est arrivé ici, cela signifie que l'utilisateur a le droit de voir les commentaires. 
                *   Par la même occasion, il a le droit d'accéder aux EVALs
                */
                $(".jb-css-csam-eval-chs-wrp").removeClass("this_hide");
                
                /*
                 * ETAPE :
                 *  On s'assure que les Commentaires sont ajoutés suivant la dernière référence inscrite.
                 */
                if ( KgbLib_CheckNullity($(".jb-unq-c-a-r-bx").data("plrt")) | $(".jb-unq-c-a-r-bx").data("plrt").toString() !== d.extm.toString() ) {
//                    Kxlib_DebugVars([UNQ : Bad lot !"]);
                    return;
                }
                
                //ddr = DeniedDelReacts, Tableau contenant les références (Objet) des Commentaires dont l'accès au lien de supression est proscrit.
                var ddr = new Array();
                
                //On supprime les anciens commentaires. Que l'on recoive les données ou non
                f_WipeOldReacts();
                
                if (! KgbLib_CheckNullity(d) ) { //[NOTE 17-04-15] ?? d a déjà vérifié NON !
                    //Liste des commentaires dans le DOM
                    var f = _f_ShwAllRcts(d.rtab,d.extm);
                    
                    //Afficher le nombre de commentaires
                    _f_SetReactNb(d.arn);
                    
                    //Décider si on doit afficher la disponibilité de la supression des commentaires
                            
//                Kxlib_DebugVars([typeof r, r.length,r],true);
                    /*
                     * [12-12-14] @author L.C.
                     * La décision sur le droit de supprimer les commentaires est maintenant prise par le serveur pour plus de sécurité mais aussi parce que c'est plus simple comme ça.
                     * D'ailleurs toutes les autres fonctionnalités devront au fur et à mesure un système d'autorisation donnée par le serveur.
                     */
                    //var r = false; //DEV, TEST, DEBUG
//                    var r = _f_OnVztrAkxDelAnyRct(d);
/*
                    if ( typeof r === "undefined" || r === null ) {
                        //Cela signifie que PERM a rencontré une erreur. Dans ce cas, la prévention est la meilleure arme.
                        
                        $(".jb-unq-react-del").addClass("this_hide");
                        
                        //TODO : Avertir le serveur
                        
                    } else if (r) {
                        //L'utilisateur actif a l'autorisation d'accéder aux liens de supression
                        $(".jb-unq-react-del").removeClass("this_hide");
                    } else if ( r === false ) {
                        //RAPPEL : Le retour peut etre egale à "undefined"
                        /* On vérifie pour chaque commentaire, si la personne a le droit d'avoir accès à la suppression.
                         * Pour chaque commentaire qui n'aura pas l'autorisation, on sauvegarde son id afin de cacher le lien après son insertion dans UNQ 
                         * *
                        
                        $.each(f, function(x,v) {
                            var ii = Kxlib_DataCacheToArray($(v).data("cache"))[0][0];
                                    
                            /* On commence par s'assurer que le commentaire a un 'ueid'. Sinon, on n'autorise pas l'accès au lien de supression *
                            if ( KgbLib_CheckNullity(ii) ) { ddr.push(v); }
                            else {
                                /* Sinon, on vérifie si l'utilisateur a le droit d'accéder au lien de supression via le module PERM. *
                                var rr = _f_OnVztrAkxDelRct(ii);
//                                alert(KgbLib_CheckNullity(rr));
                                if ( KgbLib_CheckNullity(rr) || !rr ) { ddr.push(v); }
                            }
                        });
//                        Kxlib_DebugVars([typeof ddr,ddr.length,ddr],true);
                    }
                    //*/
                    $.each(d.rtab, function(x,re) {
                        if (! ( re.hasOwnProperty("cdel") && re.cdel === 1 ) ) {
                            ddr.push($(".jb-unq-react-mdl[data-item='"+re.itemid+"']"));
                        }
                    });
//                    Kxlib_DebugVars([typeof ddr,ddr.length,JSON.stringify(ddr)],true);
                }
                
                //On masque (supprime) le lien de suppression pour chaque commentaire qui n'a pas eu d'autorisation.
                //[NOTE 25-07-14] : Si on a commis une erreur, ce n'est pas grave car les commentaires sont envoyés par bdd à chaque fois que l'on fait appel à UNQ
                $.each(ddr, function(x,v) {
                    $(v).find(".jb-unq-react-del").remove();
                });
                
                //On affiche les zones liées aux commentaires
                $(".jb-unq-rct-mx").hide().removeClass("this_hide").fadeIn(100);
                /*
                 * [DEPUIS 01-06-15] @BOR
                 */
                $(".jb-unq-add-rct-trg").removeClass("disabled");
                $(".jb-unq-add-rct-ipt").removeAttr("disabled");
                
                $(".jb-unq-count-box").removeClass("this_hide");
                $(".jb-unq-c-c-bot").removeClass("onlythat");
                
                $(".jb-unq-c-c-bot").hide().removeClass("this_hide").fadeIn();
                
                /*
                 * [DEPUIS 08-05-15] @BOR
                 * On fait scroller la zone plus proprement.
                 */
                _f_ScrollZn();
                
                //Spécifier la taille de la zone de commentaire
                _f_SetRctBxH ();
                
                //On met à jour le nombre de commentaires coté page
                _f_UpdArtInPg(d.arn);
//                _f_UpdArtInPg(_f_GetReactNb()); [DEPUIS 17-04-15] @BOR
                
                /*
                 * [NOTE 04-05-15] @BOR
                 * On libère le pointeur pour permettre que le processus ne soit pas corrompu.
                 */
                _xhr_plrs = null;
            });
            
            $(s).on("operended", function(){
                
               /*
                * [DEPUIS 11-07-15] @BOR
                *   Si on est arrivé ici, cela signifie que l'utilisateur a le droit de voir les commentaires. 
                *   Par la même occasion, il a le droit d'accéder aux EVALs
                */
                $(".jb-css-csam-eval-chs-wrp").removeClass("this_hide");
                
                //On affiche les zones liées aux commentaires
                $(".jb-unq-rct-mx").hide().removeClass("this_hide").fadeIn(100);
                /*
                 * [DEPUIS 01-06-15] @BOR
                 */
                $(".jb-unq-add-rct-trg").removeClass("disabled");
                $(".jb-unq-add-rct-ipt").removeAttr("disabled");
                
                $(".jb-unq-count-box").removeClass("this_hide");
                $(".jb-unq-c-c-bot").removeClass("onlythat");
                
                $(".jb-unq-c-c-bot").hide().removeClass("this_hide").fadeIn();
                
                //Spécifier la taille de la zone de commentaire
                _f_SetRctBxH();
                
                //On met à jour le nombre de commentaires coté page
                _f_UpdArtInPg(_f_GetReactNb());
                
                /*
                 * [NOTE 04-05-15] @BOR
                 * On libère le pointeur pour permettre que le processus ne soit pas corrompu.
                 */
                _xhr_plrs = null;
                
            });
             
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    var _f_GetTTR = function () {
//    this._GetTTR = function () {
        /*
         * [NOTE 17-09-14] @author L.C. 
         * Permet de récupérer le temps avant de relancer un processus de mise à jour.
         * Cette méthode existe pour la rendre en lecture seul.
         */
        
        //TimeToRenew (ou TimeToReload)
        var _TTR = 60000;
//        var _TTR = 10000; //DEV, TEST, DEBUG
        
        return _TTR;
    };
    
        
    var _f_PullVips_Plus = function (t,ai) {
//    this._Process_PullVips_Plus = function (t,ai) {
        
        //t = Target; ai = ArticleInfos
        /*
         * Permet de mettre à jour les données sur les VIPs (_PLUS) Evals.
         * A la version vb1, on ne met à jour les VIPs que s'ils n'existent pas déjà au moment de l'ouverture de UNQ.
         * En effet, à cette version, les VIPs ne sont pas des données qui sont susceptibles de changer tout le temps. 
         * Elles ne sont pas encore assez sophistiquées pour l'être.
         */
        try {
            
            if ( KgbLib_CheckNullity(t) | KgbLib_CheckNullity(ai) ) { 
                return;
            }
            
            /*
             * RAPPEL : 
             * L'objet suit la nomenclature : 
             *    "itemid": ok
             *    "artpic": ok
             *    "artdesc": ok
             *    (todo) "rlist":
             *    "trid": (inutile)
             *    "trtitle": ok
             *    "trhref": ok
             *    "trhref" : ok
             *    "time": ok
             *    "utc": ok
             *    "ep2": ok
             *    "ep1": ok
             *    "em1": ok
             *    "etl": ok
             *    "evlt": ok (Liste des vip et le nombre de personnes total)
             *    "evlt_u1": ok,
             *    "evlt_u2": ok,
             *    "evlt_u3": ok,
             *    "evlt_tl": ok,
             *    "muel" : ok
             *    "ueid": ok
             *    "ufn": ok
             *    "upsd": ok
             *    "uppic": ok
             *    "uhref": ok
             *    "myel" : ok
             * * */
            
            //On vérifie si l'Article a déja des VIPS
            if (!(KgbLib_CheckNullity(ai.evlt_u1) && KgbLib_CheckNullity(ai.evlt_u2) && KgbLib_CheckNullity(ai.evlt_u3) && KgbLib_CheckNullity(ai.evlt_tl))) {
                //Les VIPs ont déjà été mis à jour ou existaient déjà à la création de la page. On va vérifier depuis combien de temps et on verra s'il faut relancer ...
                
                /*
                 * On vérifie si on peut lancer la procédure selon le LastPulled.
                 * On vérifie au niveau de l'Article dans la page le paramètre 'lp' (LastPulled)
                 */
                var lp = $(t).data("lp"), _TTR = _f_GetTTR(), n = (new Date()).getTime();
                
                lp = (KgbLib_CheckNullity(lp)) ? 0 : parseInt(lp);
                
                //eld  = elapsed (passé)
                var eld = n - lp;
                
                //RAPPEL : lp ne peut pas être < 0. Pour cela, il y aurait fallu qu'on soit dans une année inferieure à 1970
                if ( lp > 0 && eld < _TTR ) {
                    //Try later ...
                    return false;
                } 
                
                //Sinon on continue quand même vers la mise à jour
                
            } 
            
            /*
             * [DEPUIS 04-05-15] @BOR
             * ETAPE : 
             * On vérifie si une requete est en cours.
             * Dans ce cas, on l'annule pour nous assurer qu'on n'affichera que la requete la plus récente.
             */
            if (! KgbLib_CheckNullity(_xhr_ples) ) {
                _xhr_ples.abort();
            }
            
            var i = $(t).data("item"), s = $("<span/>"), $t = $(t);
            _f_Srv_PullVips_Plus($t, i, s);
            
            /*
             * [NOTE 07-12-14] @author L.C.
             * On retire les VIPs résiduels affichés dans UNQ pour éviter que la liste s'affiche avant le retour du serveur
             */
            $("#unq-eval-box > *").addClass("this_hide");
            
            $(s).on("datasready", function(e,d) {
                /*
                 * Données attendues : 
                 *  -> vips.tab = (array) [u1,u2, u3, ut]
                 *  -> vips.nb = (int)
                 *  -> evals.tab = (array) [+2,+1, -1, tot]
                 *  -> evals.me = (array) [+2,+1, -1, tot]
                 *  -> o_cap (int)
                 *  -> cdel (true|false)
                 *  
                 */
                
                /*
                 * NOTE TECHNIQUE : Si un des Child de 'd' est NULL KgbLib_CheckNullity(d) renverra 0. Or on ne veut pas controller tous les Child en faisant KgbLib_CheckNullity(d).
                 * On ne veut que savoir si d est NULL.
                 */
                
                //On inscrit la donnée permettant de définir si on a le droit de supprimer un Article
                if (typeof d !== "undefined" && d.hasOwnProperty("cdel") && !KgbLib_CheckNullity(d.cdel)) 
                {
                    _cdel = d.cdel;
                }
                
//            Kxlib_DebugVars([ypeof d,KgbLib_CheckNullity(d.vips),KgbLib_CheckNullity(d.vips.tab),KgbLib_CheckNullity(d.vips.nb)]);
                //On affiche VIPs 
                if (
                        typeof d !== "undefined" && !KgbLib_CheckNullity(d.vips) 
                        && !KgbLib_CheckNullity(d.vips.tab) && !KgbLib_CheckNullity(d.vips.nb) 
                        ) 
                {
                    
                    // *************> On affiche les VIPs
                    var ul = d.vips.tab;
                    $.each($(".jb-unq-vip-eval-users"), function(x, v) {
                        $(v).find(".jb-unq-vip-vip").text("@" + ul[x]["upsd"]);
                        $(v).attr({
                            "href"  : "/@" + ul[x]["upsd"],
                            "title" : "" + ul[x]["ufn"] + " est sur Trenqr",
                            "alt"   : "Aller vers le compte de " + ul[x]["ufn"] + " (@" + ul[x]["upsd"] + ") - sur Trenqr"
                        });
                    });
                    //GlobalUserList
                    $("#jb-eval-gul").text(d.vips.nb);
                    
                    //On affiche de nouveau les éléments cachés au cas ils auraient été cachés.
                    $("#unq-eval-box > *").removeClass("this_hide");
                    
                    //On insère les VIPs dans l'entete de l'Article au niveau de la page pour éviter de refaire appel à la base de données
                    var ch = $(t).data("cache"), nch = "";
                    
                    nch = Kxlib_AlterDataCacheAt(ch, ul[0]["upsd"], 2, 4);
                    nch = Kxlib_AlterDataCacheAt(nch, ul[1]["upsd"], 2, 5);
                    nch = Kxlib_AlterDataCacheAt(nch, ul[2]["upsd"], 2, 6);
                    nch = Kxlib_AlterDataCacheAt(nch, d.vips.nb, 2, 7);
                    
                    $(t).data("cache", nch);
                    
                } else {
                    //On retire les VIPs résiduels affichés dans UNQ
                    $("#unq-eval-box > *").addClass("this_hide");
                    
                    //On retire les données dans l'Article (modèle page)
                    var ch = $(t).data("cache"), nch = "";
                    
                    nch = Kxlib_AlterDataCacheAt(ch, "", 2, 4);
                    nch = Kxlib_AlterDataCacheAt(nch, "", 2, 5);
                    nch = Kxlib_AlterDataCacheAt(nch, "", 2, 6);
                    nch = Kxlib_AlterDataCacheAt(nch, "", 2, 7);
                    
                    $(t).data("cache", nch);
                    
                    //On vide les données résiduelles 
                    $(".jb-unq-vip-eval-users").attr({
                        "href"  : "",
                        "title" : "",
                        "alt"   : ""
                    });
                    $(".jb-unq-vip-eval-users").find(".jb-unq-vip-vip").text("");
                    
                }
                
                // *************> On affiche les EVALs
//                Kxlib_DebugVars([typeof d, KgbLib_CheckNullity(d.evals), KgbLib_CheckNullity(d.evals.tab), KgbLib_CheckNullity(d.evals.me),typeof EVALBOX],true);
//                Kxlib_DebugVars([ypeof d, KgbLib_CheckNullity(d.evals), KgbLib_CheckNullity(d.evals.tab), KgbLib_CheckNullity(d.evals.me)]);

                /*
                 * [NOTE 30-04-15] @BOR
                 * Permet de s'assurer qu'on rempli quand meme dans la condition même si 'me' est NULL.
                 */
                
                d.evals.me = ( KgbLib_CheckNullity(d.evals.me) ) ? "" : d.evals.me;
//                Kxlib_DebugVars([ypeof d !== "undefined" && !KgbLib_CheckNullity(d.evals), !KgbLib_CheckNullity(d.evals.tab), ( !KgbLib_CheckNullity(d.evals.me) | d.evals.me === "" ),typeof(EVALBOX),EVALBOX instanceof Function,typeof(Function), typeof EVALBOX === "function"]);
                /*
                 * [NOTE 30-04-15] @BOR
                 * Dans le cas de WLC, EVALBOX est "undefined" aussi, les données ne sont pas remis à jour dans cette section.
                 * Ce n'est pas dramatique dans la mesure où l'utilisateur aura les données enregistrées dans le header de l'Article à sa création.
                 * De plus, cela est une bonne raison pour faire remarque la qualité de service lorsque l'utilisateur est connecté.
                 */
                if (
                        typeof d !== "undefined" && !KgbLib_CheckNullity(d.evals)
                        && !KgbLib_CheckNullity(d.evals.tab) 
                        && ( !KgbLib_CheckNullity(d.evals.me) | d.evals.me === "" )
                        && typeof EVALBOX === "function"
                    )
                {
                    
                    var EB = new EVALBOX(), o = $("#unq-center");
                    
                    args = {
                        "b"     : t,
                        "p"     : "unq",
                        "d"     : {"eval": d.evals.tab},
                        "me"    : d.evals.me,
                        "a"     : undefined //Sans inmportance dans ce cas
                    };
                    
//                var x = $(".jb-unq-art-mdl.active").find(".jb-csam-eval-choices[data-zr=rh_cool]");
//                EB.DisplayEval(args.b,args.p,args.d,args.me,args.a);
                    EB.UpdateModelWithEval(args.b, args.p, args.d, args.me, args.a);
                    
                    
//                //Mettre en forme EVAL
//                $(o).find(".jb-csam-eval-oput").data("cache", "[" + d.ep2 + "," + d.ep1 + "," + d.em1 + "," + d.etl + "," + d.myel + "]").find("span").html(d.etl);
                    
                    /* Bind les boutons d'EVAL */
                    //Récupération de l'identifiant
                    var i = $(t).attr("id");
                    $(o).find(".jb-csam-eval-choices").data("target", i);
                    
                    //On fait appel à EVALBOX pour afficher l'évaluation de l'utilisateur actif sur l'Article actif
                    EB.DplCUzrEvl($(o), args.me);
                    //*/
                    
                }
                
                //On met à jour le Capital de l'utilisateur
                if ( typeof d !== "undefined" && !KgbLib_CheckNullity(d.o_cap) )
                {
                    $(".jb-u-sp-cap-nb").text(d.o_cap);
                }
                
                //On réinitialise le marqueur pour une futur utilisation
                $(t).data("lp", (new Date()).getTime());
                
                /*
                 * [NOTE 04-05-15] @BOR
                 * On libère le pointeur pour permettre que le processus ne soit pas corrompu.
                 */
                _xhr_ples = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /************************************************/
    /******************* PERMALINK ******************/
     
    var _f_PrmlkAct = function (x) {
//    this.PermlkAction = function (x) {
        if ( KgbLib_CheckNullity(x) ) { return; }
        
        var a = $(x).data("action");
        a = a.toLowerCase();
        switch(a) {
            case "open" :
            case "perma" :
                    _f_PrmLk_OnOpen();
                break;
            case "skip" :
            case "close" :
                    _f_PrmLk_OnClz();
                break;
            case "copy" :
                break;
            default:
                break;
        }
    };
    
    var _f_PrmLk_OnOpen = function() {
//    this.PrmLk_OnOpen = function() {
        try {
            /*
             * On retire l'ancien texte 
             * [NOTE 18-06-05] @BOR
             * Pourquoi ne pas juste changer le texte sans devoir le retirer ... ?
             */
            $(".jb-unq-pmlk-output").val("");
            
            /* 
             * On met le nouveau texte après l'avoir récupérer 
             * */
            //On récupère le texte
            var hf = Kxlib_DataCacheToArray($(".jb-unq-c-a-pmlk-max").data("cache"))[0][0];
            //On insère le lien
            $(".jb-unq-pmlk-output").val(hf);
            
            /*
             * [DEPUIS 18-06-15] @BOR
             *      On insère le lien au niveau du lien "Goto"
             *  [DEPUIS 22-11-15]
             *      Refactorisation
             */
//            var hf__ = hf.split("trenqr.com")[1];
            var hf__ = hf.split(hf.split("/")[0])[1];
            $(".jb-unq-pmlk-choices[data-action='goto']").prop({
                "href" : hf__,
//                "target" : "_blank" //[DEPUIS 22-11-15] Laisser le choix à l'utilsateur
            });
            
            //On affiche l'overlay
            $(".jb-unq-prmlk-sprt").removeClass("this_hide");
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    var _f_PrmLk_OnClz = function() {
//    this.PrmLk_OnClose = function() {
        $(".jb-unq-prmlk-sprt").addClass("this_hide");
    };
    
   var _f_PrmLk_CopyToClipboard = function() {
//   this.PrmLk_CopyToClipboard = function() {
        
    };
    
    
    /************************************************/
    /****************** NAVIGATION ******************/
     
    var _f_NavAction = function(x) {
        /*
         * Gère les cas de navigation entre les Articles.
         * Cette méthode dépend aussi et surtout de la page sur laquelle l'utilisateur se trouve.
         * Le processus ne marche que dans le cas des pages TMLNR et TRPG.
         * De plus, le mécanisme ne fonctionne pas pour les modumes NWFD, PSMN.
         */
        try {
            if ( KgbLib_CheckNullity(x) | !$(x).length | !$(x).data("dir") ) { 
                return; 
            }
        
            //ii = ItemId
            var ii = Kxlib_DataCacheToArray($(".jb-unq-art-mdl").data("item"))[0][0];
            //idi = ItemDomId
            var idi = Kxlib_DataCacheToArray($(".jb-unq-art-mdl").data("item"))[0][1];
            
            if ( KgbLib_CheckNullity(ii) | !( !KgbLib_CheckNullity(idi) && $(idi) && $(idi).length )  ) {
                return;
            }
            
            //On vérifie qu'on est dans le cas d'une situation autorisée
            var at = _f_NavAcqArtType(idi);
            if ( KgbLib_CheckNullity(at) | at === false | $.inArray(at,_f_Gdf().nav_art_allwd) === -1 ) {
                return;
            }
            
            var dr = $(x).data("dir");
            switch (dr) {
                 case "prev" :
                        _f_NavPrev(x,ii,idi,at);
                     break;
                 case "next" :
                        _f_NavNext(x,ii,idi,at);
                     break;
                 default :
                     return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
//            return;
        }
    };
    
    var _f_NavAcqArtType = function (i) {
        /*
         * Permet de déterminer le type de l'Article de référence dans son contexte.
         * En d'autres termes qu'elle est le module ou la page qui distribue l'Article.
         * Vbeta1 : La détection du type se fait à partir de l'identifiant de l'Article de référence
         * 
         * On distingue les types suivants : 
         *  -> _TR_IML : Un Article de type *IML* affiché au niveau d'une page -TMLNR-
         *  -> _TR_ITR : Un Article de type *ITR* affiché au niveau d'une page -TMMNR-
         *  -> _TG_ITR : Un Article de type *ITR* affiché au niveau d'une page -TRPG-
         *  -> _ND_IML : Un Article de type *IML* affiché au niveau du module -NEWSFEED- 
         *  -> _ND_ITR : Un Article de type *ITR* affiché au niveau du module -NEWSFEED-
         *  -> _FV_IML : Un Article de type *IML* affiché au niveau du module -FAVORITE- 
         *  -> _FV_ITR : Un Article de type *ITR* affiché au niveau du module -FAVORITE-
         */
        if ( KgbLib_CheckNullity(i) | typeof i !== "string" ) { 
            return; 
        }
        
        try {
            if ( i.search("post-accp-tr") !== -1 ) {
                return "_TR_ITR";
            } else if ( i.search("trpg-art") !== -1 ) {
                return "_TG_ITR";
            } else if ( i.search("post-fv-aid-") !== -1 ) { //[DEPUIS 19-12-15]
                return ( $(Kxlib_ValidIdSel(i)).data("istr") === true ) ? "_FV_ITR" : "_FV_IML";
            } else if ( i.search("post-tia-ex-aid-") !== -1 ) { //[DEPUIS 18-04-15]
                return ( $(Kxlib_ValidIdSel(i)).data("istr") === true ) ? "_TIA_EX_ITR" : "_TIA_EX_IML";
            } else if ( i.search("post-tia-phtk-aid-") !== -1 ) { //[DEPUIS 18-04-15]
                return ( $(Kxlib_ValidIdSel(i)).data("istr") === true ) ? "_TIA_PHTK_ITR" : "_TIA_PHTK_IML";
            } else {
                return false;
            }
        } catch(ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }
    };
    
    var _f_NavPrev = function (x,ii,idi,at) {
        /*
         * Permet de naviguer vers l'Article précédent dans la pile d'Articles.
         */
        try {
            
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(ii) | KgbLib_CheckNullity(idi) | KgbLib_CheckNullity(at) | $.inArray(at,Object.keys(_f_Gdf().nav_art_slctr)) === -1 ) { 
                return; 
            }
            
            //rao = ReferenceArticleObject
            var $rao = $(Kxlib_ValidIdSel(idi));
            //On récupère le sélecteur en fonction du type d'Article
            var sl = _f_Gdf().nav_art_slctr[at];
            //stk = stack
            var $stk = $(sl);
            if (! $stk.length ) {
                return;
            }
            
            //On s'assure qu'il ne s'agit pas du premier Article dans la pile
            if ( $stk.first().data("item") === $rao.data("item") ) {
                /*
                 * [DEPUIS 18-06-15] @BOR
                 * On retire la flèche
                 */
                $(x).addClass("this_hide");
                return;
            }
            
            //rais = ReferenceArticleInStack
            var $rais = $stk.filter("[data-item='"+ii+"']");
//            Kxlib_DebugVars([$rais,typeof $rais,$rais.length,$rais.data("item")],true);
//            return; 
            if ( KgbLib_CheckNullity($rais) | !$rais.length ) {
                return;
            }
            //pais = PreviousArticleInStack
            var $pais = $rais.prev(); 
            
            /*
             * Permet de clore correctement la précedante session.
             * [NOTE 02-03-15] @Lou
             * Au départ j'avais pensé que cela reglerait le problème des commentaires que neni.
             * Il faut (a fallu) mettre en place un système qui règle le problème des commentaires non liés à l'Article.
             */
            _f_OnClose();
//            Kxlib_DebugVars([nais.data("item")]);
            if ( at === "_TR_ITR" ) {
                gt.OnOpen("tmlnr",$pais.find(".fcb_img_maximus"));
            } else if ( at === "_TG_ITR" ) {
                gt.OnOpen("trpg",$pais.find(".fcb_img_link"));
            } else if ( at === "_FV_IML" || at === "_FV_ITR" ) {
                gt.OnOpen("fav",$pais.find(".jb-tmlnr-pgfv-art-i-bmx"));
            } else {
                return;
            }
            
//            gt.OnOpen("tmlnr",$pais.find(".fcb_img_maximus"));
//            $pais.find(".fcb_img_maximus").trigger("click"); 
//            $pais.find(".fcb_img_maximus").click(); 
            return;
//            alert($stk.find("[data-item=]"));
        } catch(ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }
    };
    
    var _f_NavNext = function (x,ii,idi,at) {
        /*
         * Permet de naviguer vers l'Article suivant dans la pile d'Articles.
         * Cette méthode est plus sophistiquée que la méthode antagoniste @see _f_NavPrev().
         * En effet, nous devons gérer certains cas :
         *  -> Le fait qu'on l'Article suivant fait partie des x derniers Articles dans la liste
         *  -> Le fait qu'on l'Article suivant soit le dernier Article de la liste.
         */
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(at) | $.inArray(at,Object.keys(_f_Gdf().nav_art_slctr)) === -1 ) { 
                return; 
            }
        
        
            //rao = ReferenceArticleObject
            var $rao = $(Kxlib_ValidIdSel(idi));
            //On récupère le sélecteur en fonction du type d'Article
            var sl = _f_Gdf().nav_art_slctr[at];
            //stk = stack
            var $stk = $(sl);
            if (! $stk.length ) {
                return;
            }
            
            /*
             * ETAPE :
             * ON gère le cas suivant lequel l'Article en présence est le dernier dans la liste.
             */
//            if ( $stk.last().data("item") === $rao.data("item") ) {
//                return;
//            }
            
            //rais = ReferenceArticleInStack
            var $rais = $stk.filter("[data-item='"+ii+"']");
//            Kxlib_DebugVars([$rais,typeof $rais,$rais.length,$rais.data("item")],true);
//            return; 
            if ( KgbLib_CheckNullity($rais) | !$rais.length ) {
                return;
            }
            //nais = NextArticleInStack
            var $nais = $rais.next(); 
            
            /*
             * ETAPE :
             * On récupère le selector qui permet de trigger l'opération de récupération des Articles plus anciens
             */
            var $x__; 
            switch (at) {
                case "_TR_ITR" :
                        $x__ = $(".jb-tmlnr-loadm-trg");
                        //TODO : On lock toutes les fonctionnalités au niveau de NWFD 
                        //TODO : On fait apparaitre la frame indiquant de "Patienter"
                    break;
                case "_TG_ITR" :
                        $x__ = $(".jb-trpg-loadm-trg");
                        //TODO : On lock toutes les fonctionnalités au niveau de NWFD 
                        //TODO : On fait apparaitre la frame indiquant de "Patienter"
                    break;
                case "_FV_IML" :
                case "_FV_ITR" :
                        $x__ = $(".jb-trpg-loadm-trg");
                    break;
                default: 
                    return;
            }
            
            
            /*
             * [DEPUIS 18-06-15] @BOR
             * On vérifie si on est dans le cas où il vaudrait mieux faire disparaitre la flèche Next.
             */
//                Kxlib_DebugVars([asl.last().data("item") !== d.itemid, $ldmr.length, $ldmr.hasClass("EOP")]);
            if ( $stk.last().data("item") === $rao.data("item") && $x__.length && $x__.hasClass("EOP") ) {
                $(x).addClass("this_hide");
                return; //[DEPUIS 10-07-15] @BOR
            }
            
            /*
             * [DEPUIS 10-07-15] @BOR
             *  (1) On vérifie s'il y a plus de 3 Articles. 
             *      S'il y a moins de 3 Articles, il n'est pas necessaire de lancer l'opération.
             *      En effet, il est pratiquement impossible qu'il existe des Articles anterieurs.
             *  (2) <TMLNR> Dans ce cas, s'il y a plus de 3 Articles, on vérifie que le nombre d'Articles IML ne montre pas un signe, tel qu'il n'est nul besoin que de creuser plus profond.
             *      Si le nombre d'Articles ITR est inferieur à celui IML, cela veut dire qu'il n'y en a plus. Sinon, il y aurait toujours le même nombre d'Articles dans les deux colonnes.
             */
//            Kxlib_DebugVars([stk.length < _f_Gdf().plmin_, at === "TMLNR", $stk.length < $(".jb-tmlnr-mdl-std").length,_f_Gdf().plmin_]);
            //gck = GoClicK
            var gck = ( $stk.length < _f_Gdf().plmin_ || ( at === "_TR_ITR" && $stk.length < $(".jb-tmlnr-mdl-std").length ) ) ? false : true;
            if ( !gck && $stk.last().data("item") === $rao.data("item") ) {
                $(x).addClass("this_hide");
                return;
            }
//            Kxlib_DebugVars([KgbLib_CheckNullity($nais),],true);
                        
            /*
             * ETAPE :
             * On gère le cas de l'Article suivant.
             *  -> Le cas où l'Article suivant fait partie des x derniers Articles de la liste.
             *  -> Le cas où l'Article suivant n'existe pas
             */
            //CAS : Si l'Article suivant n'existe pas, on clique pour les plus anciens et on stoppe tout.
//            Kxlib_DebugVars([672,"UNIQUE",typeof $nais]);
            if ( KgbLib_CheckNullity($nais) ) {
                //TODO : Signaler visuellement qu'un chargement est en cours

                /*
                 * ETAPE :
                 * On vérifie si une action de loading n'est pas déjà en cours.
                 * Sinon, il ne faut pas lancer le processus, au risque de bouriner la machine
                 */
                if ( $x__.data("lk") !== 1 && gck ) {
                    $x__.click();
                } else if ( $x__.data("lk") === 1 && gck ) {
                    Kxlib_DebugVars(["Show Await !"]);
                    $(".jb-unq-nav-btn[data-dir='next']").find(".jb-unq-nav-btn-wait").removeClass("this_hide");
                }
                
                return;
            }

            if ( gck ) {
                //CAS : On vérifie si l'élémént NEXT fait partie des x derniers éléments
                var t__ = -1*parseInt(_f_Gdf().plmin);
//                Kxlib_DebugVars([680,"UNIQUE",t__]);
                $.each($stk.slice(t__),function(i,e){
                    if ( !KgbLib_CheckNullity($(e).data("item")) && $(e).data("item") === $nais.data("item") ) {
                        $x__.click();
                        return false;
                    }
                });
            }
            
            
            /*
             * Permet de clore correctement la précedante session.
             * [NOTE 02-03-15] @Lou
             * Au départ j'avais pensé que cela reglerait le problème des commentaires que neni.
             * Il faut (a fallu) mettre en place un système qui règle le problème des commentaires non liés à l'Article.
             */
            _f_OnClose();
//            Kxlib_DebugVars([nais.data("item")]);

            if ( at === "_TR_ITR" ) {
                gt.OnOpen("tmlnr",$nais.find(".fcb_img_maximus"));
            } else if ( at === "_TG_ITR" ) {
                gt.OnOpen("trpg",$nais.find(".fcb_img_link"));
            } else if ( at === "_FV_IML" || at === "_FV_ITR" ) {
                gt.OnOpen("fav",$nais.find(".jb-tmlnr-pgfv-art-i-bmx"));
            } else {
                return;
            }
            
//            $nais.find(".fcb_img_maximus").trigger("click"); 
//            $nais.find(".fcb_img_maximus").click(); 
            return;
//            alert($stk.find("[data-item=]"));
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
            return;
        }
    };
    
    var _f_ScrollZn = function () {
        try {
            
            var $tar = $(".jb-unq-c-a-r-bx");
            if ( $tar.find(".jb-unq-react-mdl").length ) {
                var r__ = $tar.find(".jb-unq-react-mdl"), h__ = 0;
                $.each(r__, function(i, rc) {
                    h__ += $(rc).height();
                });
                
                $($tar).animate({scrollTop: h__}, 1500);
            } else {
                $($tar).animate({scrollTop: $($tar).height()}, 1500);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }
    };
    
    var _f_VidAction = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            var a = $(x).data("action");
            
            if ( KgbLib_CheckNullity($(x).data("scp")) ) {
                return;
            }
            
            var vid = ( $(x).data("scp") === "theater" ) ? $(".jb-unq-tv-fscrn-v").get(0) : $(".jb-unq-tv-vid").get(0);
            if ( KgbLib_CheckNullity(vid) ) {
                return;
            }
                    
            switch (a) {
                case "vid-play" :
                        /*
                        vid.play();
                        $(x).data("action","vid-pause");
                        $(".jb-unq-tv-lnch-vid").removeClass("paused");
                        //*/
                        _f_VidPlay(x,vid);
//                        _f_EnaEffi($abx,true);
                    break;
                case "vid-pause" :
                        /*
                        vid.pause();
                        $(x).data("action","vid-play");
                        $(".jb-unq-tv-lnch-vid").addClass("paused");
                        //*/
                        _f_VidPause(x,vid);
//                        _f_EnaEffi($abx,false);
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_VidPlay = function(x,vid) {
        try {
            if ( KgbLib_CheckNullity(x) | !$(x).length ) {
                return;
            }
            
            if ( KgbLib_CheckNullity(vid) ) {
                var vid = ( $(x).data("scp") === "theater" ) ? $(".jb-unq-tv-fscrn-v").get(0) : $(".jb-unq-tv-vid").get(0);
                if ( KgbLib_CheckNullity(vid) ) {
                    return;
                }
            }
            
            vid.play();
            $(x).data("action","vid-pause");
            $(x).removeClass("paused");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_VidPause = function(x,vid) {
        try {
            if ( KgbLib_CheckNullity(x) | !$(x).length ) {
                return;
            }
            
            if ( KgbLib_CheckNullity(vid) ) {
                var vid = ( $(x).data("scp") === "theater" ) ? $(".jb-unq-tv-fscrn-v").get(0) : $(".jb-unq-tv-vid").get(0);
                if ( KgbLib_CheckNullity(vid) ) {
                    return;
                }
            }
            
            vid.pause();
            $(x).data("action","vid-play");
            $(x).addClass("paused");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    

    /********************************************************************************************************************************************************/
    /********************************************************************* SERVER SCOPE *********************************************************************/
    /********************************************************************************************************************************************************/
    
    var _Ax_DelRct = Kxlib_GetAjaxRules("UNQ_DEL_REACT");
    var _f_Srv_DelRct = function (ai,i,b,s) {
//    this._Srv_DelReact = function (s,i,b) {
        
        if ( KgbLib_CheckNullity(ai) | KgbLib_CheckNullity(i) | KgbLib_CheckNullity(b) | KgbLib_CheckNullity(s) ) { 
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
                            case "__ERR_VOL_U_G" :
                            case "__ERR_VOL_ACC_GONE" :
                            case "__ERR_VOL_CU_GONE" :
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE" :
                                break;
                            case "__ERR_VOL_DENY" :
                            case "__ERR_VOL_DENY_AKX" :
                            case "__ERR_VOL_DNY_AKX" :
                                break;
                            case "__ERR_VOL_REACT_GONE" :
                                    //On fait disparaitre le commentaire. Ainsi, tout reste ransparent et cohérent pour l'uilisateur
                                    $(b).remove();
                                    //On met à jour le compteur de commentaires.
//                                    _f_SetReactNb(); //[NOTE 17-04-15] @BOR Autant ne rien faire
                                break;
                            default:
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) && datas.return.hasOwnProperty("arn") && !KgbLib_CheckNullity(datas.return.arn) ) {
                    $(s).trigger("operended",[datas.return]);
                }
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
             */
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            return;
        };
        
        var toSend = {
            "urqid": _Ax_DelRct.urqid,
            "datas": {
                "ai" : ai,
                "i"  : i
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_DelRct.url, wcrdtl : _Ax_DelRct.wcrdtl });
    };
            
    var _Ax_AddRct = Kxlib_GetAjaxRules("UNQ_ADD_REACT");
    var _f_Srv_AddRct = function (i,t,lri,lrt,s) {
//    var _f_Srv_AddRct = function (s,i,t) {
//    this._Srv_AddReact = function (s,i,t) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(s) ) return;
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else{
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE":
                                break;
                            case "__ERR_VOL_WRG_DATAS":
                            case "__ERR_VOL_MAX_TEXT":
                            case "__ERR_VOL_DENY":
                                break;
                            default:
                                break;
                        }
                    }
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) && datas.return.hasOwnProperty("rs") && datas.return.hasOwnProperty("arn") ) {
                    $(s).trigger("datasready",[datas.return]);
                }
            } catch (ex) {
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
//            alert("AJAX ERR : "+th.Ajax_AddReact.urqid);
        };
        
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_AddRct.urqid,
            "datas": {
                "ai": i,
                "rm":t,
                "lri": ( !KgbLib_CheckNullity(lri) ) ? lri : null,
                "lrt": ( !KgbLib_CheckNullity(lrt) ) ? lrt : null,
                "curl":u
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_AddRct.url, wcrdtl : _Ax_AddRct.wcrdtl });
    };
    
    var _Ax_GAllReactLmt = Kxlib_GetAjaxRules("UNQ_GET_ALL_REACT_LIMIT");
    var _f_Srv_GAllReactLmt = function (i,t,s) {
//    this._Srv_GetAllReactLimit = function (i,s) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(s) ) {
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
                
                if (! KgbLib_CheckNullity(datas.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE":
                                break;
                            case "__ERR_VOL_DENY":
                                   /*
                                    * [DEPUIS 01-06-15] @BOR
                                    */
                                    $(".jb-unq-add-rct-trg").addClass("disabled");
                                    $(".jb-unq-add-rct-ipt").attr("disabled","true");
                                    
                                    $(".jb-unq-count-box").addClass("this_hide");
                                    $(".jb-unq-c-c-bot").addClass("onlythat");
                                    
                                    $(".jb-unq-c-c-bot").hide().removeClass("this_hide").fadeIn();
                                    
                                    /*
                                     * [DEPUIS 10-07-15] @BOR
                                     */
                                    _f_RctHdlSpnr(false);
                                    _f_RctHdlDnyAkx(true);
                                    
                                    /*
                                     * [DEPUIS 11-07-15] @BOR
                                     */
                                    $(".jb-css-csam-eval-chs-wrp[data-scp='spcl']").addClass("this_hide");
                                    $(".jb-css-csam-eval-chs-wrp[data-scp='dslk']").addClass("this_hide");
                                    
                                break;
                            default:
                                break;
                        }
                    }
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                /*
                 * [NOTE 09-07-14] On le fait même si on n'a pas de données. C'est au CALLER de décider 
                 * [NOTE 14-04-15] On utilise "operended" pour les cas où on aurait pas de donner en retour
                 */
                    var datas = [datas.return];
                    $(s).trigger("datasready", datas);
                } else {
                    $(s).trigger("operended", datas);
                }
            } catch (ex) {
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
//            alert("AJAX ERR : "+th.GetAllReactLimit.urqid);
            return;
        };
        
        var toSend = {
            "urqid": _Ax_GAllReactLmt.urqid,
            "datas": {
                "i" : i,
                "et": t
            }
        };

        //[DEPUIS 04-05-15] @BOR
        _xhr_plrs = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_GAllReactLmt.url, wcrdtl : _Ax_GAllReactLmt.wcrdtl });
    };
    
    var _Ax_DelArt = Kxlib_GetAjaxRules("UNQ_DEL_TMLNR_ART");
    var _f_Srv_DelArt = function (p,a,s) {
//    this._Srv_DelArticle = function (p,a,s) {
        
        if ( KgbLib_CheckNullity(p) | KgbLib_CheckNullity(a) | KgbLib_CheckNullity(s) ) {
            return;
        }
            
        //OBSELETE : Dans tous les cas on envoie au même URQ. En fonction des clés associées aux données retournées, le module s'adaptera.
        /*
        p = p.toLowerCase();
        switch (p) {
            case "tmlnr" :
                    this.Ajax_DelArticle = Kxlib_GetAjaxRules("UNQ_DEL_TMLNR_ART");
                break;
            default :
                    return;
                break;
                
        }
        //*/
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    return;
                }
                
                if(! KgbLib_CheckNullity(d.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
                        switch (d.err) {
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE":
                                    return;
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                            case "__ERR_VOL_FAILED" :
                                    return;
                                break;
                            default:
                                    return;
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) ) {
                    var rds = [d.return];
                    $(s).trigger("operended",rds);
                }
                
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                return;
            }
        };

        var onerror = function (a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            /*
             * TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
             */
            return;
        };
        /*
         * On envoie l'URL au serveur de telle sorte qu'il puisse détecter si l'utilisateur est sur sa page.
         * Cela lui permettra par la suite de décider d'effectuer des opérations supplémentaires.
         * Dans ce cas, étant donné que la méthode gère plusieurs cas, les données reçues dépendent de la page de référnece sur laquelle nous nous trouvons.
         */
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_DelArt.urqid,
            "datas": {
                "i": a,
                "cl": curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_DelArt.url, wcrdtl : _Ax_DelArt.wcrdtl });
    };
            
    var _Ax_PullVips_Plus = Kxlib_GetAjaxRules("UNQ_PULL_VIPs_PLUS");
    var _f_Srv_PullVips_Plus = function (t,i,s) {
//    this._Srv_PullVips_Plus = function (t,i,s) {
                
        if ( KgbLib_CheckNullity(t) | KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) ){ 
            return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else return;
                
                if(! KgbLib_CheckNullity(d.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
                        switch (d.err) {
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE":
                                    //On supprime l'image dans la page 
                                    $(t).remove();
                                    //On ferme brutallement (sans préavis) UNQ
                                    _f_OnClose();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                            case "__ERR_VOL_FAILED" :
                                return;
                            default:
                                return;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) ) {
                    /*
                     * Données attendues :
                     *  (1) Liste des VIPs 
                     *  (2) Données sur les Evaluations (tab,me, nombre_evels_total)
                     */
                    var rds = [d.return];
                    $(s).trigger("datasready",rds);
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                return;
            }
        };

        var onerror = function (a,b,c) {
            /*
             * TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
             */
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            return;
        };

        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_PullVips_Plus.urqid,
            "datas": {
                "i"     : i,
                "cu"    : curl
            }
        };

        //[NOTE 04-05-15] @BOR
        _xhr_ples = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PullVips_Plus.url, wcrdtl : _Ax_PullVips_Plus.wcrdtl });
    };
    
    
    var _Ax_PA = Kxlib_GetAjaxRules("TQR_ART_PULL");
    var _f_Srv_PA = function (is,s){
        //PA = PullArticles
        if ( KgbLib_CheckNullity(is) | KgbLib_CheckNullity(s) ) {
//            d = null;
            return;
        }
                
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else {
                    _xhr_pvad = null;
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DNY_AKX":
//                                    Kxlib_AJAX_HandleDeny("ERR_PLART_DNYAKX_REL"); //[DEPUIS 11-07-15] @BOR
                                    _f_OnClose("__ERR_VOL_DNY_AKX");
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    _xhr_pvad = null;
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    /*
                     * Données attendues :
                     *  (1) Les données sur les articles à proprement parlé
                     *  (2) La liste des relations liées aux Articles reçues
                     *      N.B : Il se peut que TOUS les Articles soient liées à une Tendance. 
                     *            Dans ce cas, la liste des relations ne sera pas disponible.
                     */
                    var rds = [_cox,datas.return];
                    $(s).trigger("datasready",rds);
                } else if ( KgbLib_CheckNullity(datas.return) ) {
                    $(s).trigger("operended");
                } else {
                    _xhr_pvad = null;
                    return;
                }
                    
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_DebugVars(["1882",ex],true);
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                _xhr_pvad = null;
                return;
            }
        };

        var onerror = function (a,b,c) {
//            Kxlib_DebugVars([JSON.stringify(a),typeof a,b,c],true);
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            Kxlib_AjaxGblOnErr(a,b);
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            _xhr_pvad = null;
            return;
        };
        
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_PA.urqid,
            "datas": {
                "is"    : is,
                "cz"    : null,
                "curl"  : curl
            }
        };

        _xhr_pvad = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PA.url, wcrdtl : _Ax_PA.wcrdtl });
    };
    
    
            
    /****************************************************************************************************************************************************************************/
    /******************************************************************************** VIEW SCOPE ********************************************************************************/
    /****************************************************************************************************************************************************************************/
    
    var _f_RstUnqVw = function() {
//    this.ResetUniqueView = function() {
//        Kxlib_DebugVars(["1286","Reset Unique"],true);
        try {
            
            /*
             * [NOTE 01-09-14] 
             * Le fait que le serveur mette du temps avant de renvoyer les commentaires nous poussent à reinit la vur avant toute chose.
             * On retire les anciens éléments pour éviter de les voir à l'ouverture. 
             * Cela implique aussi de gérer la taille du bloc contenant les commentaires.
             */
            
            //On masque la zone de droite
            $(".jb-unq-c-a-top").addClass("this_invi");
            
            // On retire le texte descriptif
            $(".jb-unq-artdesc-box span").text("");
            //On retire le marqueur d'erreur
            $(".jb-unq-add-rct-ipt").removeClass("error_field");
            
            //On retire les VIP
            //[NOTE 16-09-14] On emprofite pour mettre un message caché qui n'est pas pour déplaire au SEO
            $(".jb-unq-eval-box").addClass("this_invi");
            $(".jb-unq-vip-eval-users").attr({
                "href": "",
                "title": "Trenqr is fun and social",
                "alt": "Trenqr is fun and social"
            });
            $(".jb-unq-vip-vip").text("");
            
            //On retire les commentaires
            $(".jb-unq-react-mdl").remove();
            
            //On remet à 0 le compteur de commentaires
            $(".jb-unq-c-b-nb").text('0');
            
            //On masque la fenetre des commentaires
            $(".jb-unq-rct-mx").addClass("this_hide");
            //On masque le bloc contenant le compteur de commentaires
            $(".jb-unq-c-c-bot").addClass("this_hide");
            
//        _f_SetRctBxH ();
            //On masque les boutons de navigation
            $(".jb-unq-nav-btn").addClass("this_hide");
            
            //Je retire le pseudo du propriétaire de l'Article
            $(".jb-unq-u-b-owr-psd").text("");
            //Je "retire" l'image de profil du pseudo du propriétaire de l'Article
            $(".jb-unq-u-b-owr-img-box").attr("src", "/");
            
            //On "retire" l'image principale
            $(".jb-unq-tv").attr("src", "/");
            //[DEPUIS 01-04-16]
            _f_SwVwMode("img",true);
            $(".jb-unq-tv-vid").attr({
                src     : "/",
                width   : "",
                height  : ""
            });
            //On retire les données sur la Tendance ainsi que la barre
            $(".jb-unq-c-a-tt-lk").attr({
                href: "javascript:;",
                alt: "",
                title: ""
            }).find(".jb-unq-c-a-tt-lk-txt").text("");
            /* [DEPUIS 04-07-15] @BOR
             $(".jb-unq-c-a-tt-lk").attr({
             href    : "javascript:;",
             alt     : "",
             title   : ""
             }).text("");
             //*/
            $("#unq-c-a-trttl").attr("title", "").addClass("this_invi");
            
            
            //On reset le loader
            $(".jb-unq-art-mdl").find(".jb-eval-wait-bar").addClass("this_hide"); 
            $(".jb-unq-art-mdl").find(".jb-eval-dplw-bar-mx").removeClass("this_hide"); 
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_ClzUnq = function () {
//    this.CloseUnique = function () {
        $(".jb-unq-add-rct-ipt").blur();
        $(".jb-unq-c-i-bot").switchClass("addrct-box-appear","addrct-box-noappear");
        
        $("#unique-max").addClass("this_hide");
    };
    
    var _f_SetRctBxH = function () {
//    this.SetReactBoxHeight = function () {
        /* Permet de spécifier la taille de la zone de commentaire en fonction du bloc contenant le texte de description */

        //On calcule la taille à soustraire
        var tph = _COUNTBOXH + $("#unq-c-a-top").height();
//        alert(parseInt($(".jb-unq-c-a-r-bx").css("padding-bottom").replace("px","")));
        //On calcule la taille à déclarer
        var h = $("#unq-c-aside-max").height() - tph - parseInt($(".jb-unq-c-a-r-bx").css("padding-top").replace("px","")) - parseInt($(".jb-unq-c-a-r-bx").css("padding-bottom").replace("px","")) - 2; /* 2px a cause des 2 bordures*/
//        alert(h);
        //On déclare la taille
        $(".jb-unq-c-a-r-bx").height(h);
    };
    
    var _f_ShwAddRBox = function (shw) {
//    this.ShowAddReactBox = function () {
        
        try {
            /* Vérifier si l'utilisateur a droit d'accéder au lien de suppression */
            var PM = new PERM(), i = Kxlib_DataCacheToArray($(".jb-unq-art-mdl.active").data("cache"))[0][1][0];
            
//            var o = {"rco": i}, r;
//            r = PM.PermForFeatures("UNQ_AKX_DEL_ART", o);
//            Kxlib_DebugVars([_cdel,typeof _cdel,$(".jb-u-a-a-choices[data-action=delete]").length],true);
            if ( !_cdel && $(".jb-u-a-a-choices[data-action=del_start]").length ) { 
//            if ( !_cdel && $(".jb-u-a-a-choices[data-action=delete]").length ) { //[DEPUIS 07-08-15] @BOR
//            if ( !r && $(".jb-u-a-a-choices[data-action=delete]").length ) { 
                $(".jb-u-a-a-choices[data-action=del_start]").remove(); 
//                $(".jb-u-a-a-choices[data-action=delete]").remove(); //[DEPUIS 15-08-15] @BOR
            } else if ( _cdel && !$(".jb-u-a-a-choices[data-action=del_start]").length ) {
//            } else if ( _cdel && !$(".jb-u-a-a-choices[data-action=delete]").length ) { //[DEPUIS 07-08-15] @BOR
                _f_RcrtDelArt();
            }
            
            if ( $(".jb-unq-c-i-bot").hasClass("addrct-box-appear") ) {
                $(".jb-unq-add-rct-ipt").blur();
            } else {
                $(".jb-unq-add-rct-ipt").focus();
            }
            
            /*
             * [DEPUIS 16-05-16]
             *      Refactorisation pour moderniser la manière dont la zone apparait/disparait
             */
            /*
            if ( $(".jb-unq-c-i-bot").hasClass("addrct-box-appear")) {
                $(".jb-unq-c-i-bot").removeClass("addrct-box-appear");
            } else {
                $(".jb-unq-c-i-bot").addClass("addrct-box-appear");
            } 
            //*/
            if ( shw | !$(".jb-unq-c-i-bot").hasClass("addrct-box-appear")) {
                $(".jb-unq-c-i-bot").switchClass("addrct-box-noappear","addrct-box-appear",300);
            } else {
                $(".jb-unq-c-i-bot").switchClass("addrct-box-appear","addrct-box-noappear",300);
            } 
            
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_RcrtDelArt = function () {
        /*
         * Permet de recréer correctement dans le modele, le bouton "Supprimer" qui a été retirer.
         */
        
        //On crée le bouton
        var rbtn = $("<a/>").attr({
            class           : "u-a-a-choices jb-u-a-a-choices",
            "data-action"   : "del_start",
//            "data-action": "delete",
            href            : "javascript:;",
            role            : "button",
            title           : "Supprimer la publication"
        }).text();
//        }).text("Supprimer");
        
        //On Rebind
        
        $(rbtn).off().click(function(e){
            Kxlib_PreventDefault(e);
            
            _f_OnDelArt(this);
//            _f_OnDelArt(); //[DEPUIS 07-08-15] @BOR
        });
        /*
        $(rbtn).on("click",function(e){
            Kxlib_PreventDefault(e);
            _f_OnDelArt();
        });
        //*/
        //On ajoute le bouton
        if ( $(".jb-unq-a-act").length ) {
            $(".jb-unq-a-act").append(rbtn);
            return true;
        } else {
            return false;
        }
    };
    
    var _f_RstNwRForm = function () {
//    this.ResetNewReactForm = function () {
        $(".jb-unq-add-rct-ipt").val("");
        /*
         * [DEPUIS 11-07-15] @BOR
         */
        $(".jb-unq-add-rct-ipt").blur();
        $(".jb-unq-add-rct-ipt").focus();
        
        //On retire la bordure rouge
        $(".jb-unq-add-rct-ipt").removeClass("error_field");
    };
    
    var _f_PprUnqRct = function(d) {
//    this._View_PrepareUnqReact = function(d) {
        /* On crée le model du commentaire puis on le renvoie */
        try {
            if ( KgbLib_CheckNullity(d) ) { 
                return; 
            }
            
            var str__;
            if ( !KgbLib_CheckNullity(d.ustgs) && d.hasOwnProperty("ustgs") && d.ustgs !== undefined && typeof d.ustgs === "object" ) {
                var istgs__ = [];
                $.each(d.ustgs, function(x, v) {
                    var rw__ = [];
                    $.map(v, function(e, x) {
                        rw__.push(e);
                    });
                    istgs__.push(rw__.join("','"));
                });
//            Kxlib_DebugVars([JSON.stringify(istgs__)],true);
                if (istgs__.length > 1) {
                    str__ = istgs__.join("'],['");
                } else {
                    str__ = istgs__[0];
                }
                str__ = "['" + str__ + "']";
            }
            
            var alt = Kxlib_getDolphinsValue("USER_PFLPIC_ALT");
            //On préfère mettre le ofn. Raisons : Lorsqu'on cherche quelqu'un sur internet, on le cherche via son FullName et non son pseudo que l'on ne connait pas vraiment.
            alt = alt.replace("%opsd%", d.ofn);
            var e = "<div id=\"unq-react-" + d.itemid + "\" class=\"unq-react-mdl jb-unq-react-mdl\" data-item=\"" + d.itemid + "\" data-time=\"" + d.time + "\" data-cache=\"[" + d.oeid + "," + d.ofn + "," + d.opsd + "," + d.time + "]\" ";
            e += " data-with=\"" + Kxlib_ReplaceIfUndefined(str__) + "\" ";
            e += " >";
            e += "<div class=\"unq-react-left\">";
            e += "<div>";
            e += "<a class=\"unq-react-upic-box\" href=\"" + d.ohref + "\" alt=\"" + alt + "\" \">";
            e += "<span class=\"unq-react-upic-fade\"></span>";
            e += "<img class='unq-react-upic' width='45' height='45' src=\"" + d.oppic + "\" />";
            e += "</a>";
            e += "</div>";
            e += "</div>";
            e += "<div class=\"unq-react-txt\">";
            e += "<a class=\"unq-react-auth jb-unq-react-auth\" href=\"" + d.ohref + "\" alt=\"" + d.ofn + " - " + d.opsd + " on Trenqr\">@" + d.opsd + "</a>";
            e += "<span class=\"unq-react-txt-bb jb-unq-ract-txt-bb\">" + d.body + "</span>";
            if ( d.hasOwnProperty("cdel") && d.cdel === 1 ) {
                e += "<a class=\"unq-react-del jb-unq-react-del\" data-action=\"start_delete\" data-target=\"unq-react-" + d.itemid + "\" href=\"javascript:;\">Supprimer</a>";
                e += "<a class=\"unq-react-answ jb-unq-react-answ\" data-action=\"user-tag\" data-target=\"unq-react-" + d.itemid + "\" href=\"javascript:;\">Répondre</a>";
                e += "<div class=\"unq-rct-fnl-dcs-mx jb-unq-rct-fnl-dcs-mx this_hide\">";
                e += "<span class=\"unq-rct-fnl-dc-lbl\">Êtes-vous sûr ?</span>";
                e += "<span class=\"unq-rct-fnl-dc-tgr-mx\">";
                e += "<a class=\"unq-rct-fnl-dc-tgr jb-unq-rct-fnl-dc\" data-action=\"confirm_delete\" data-target=\"unq-react-" + d.itemid + "\" href=\"javascript:;\">Oui</a>";
                e += "<a class=\"unq-rct-fnl-dc-tgr jb-unq-rct-fnl-dc\" data-action=\"abort_delete\" data-target=\"unq-react-" + d.itemid + "\" href=\"javascript:;\">Non</a>";
                e += "</span>";
                e += "</div>";
            }
            e += "</div>";
            e += "</div>";
            e = $.parseHTML(e);
            
            /*
             * ETPAE :
             * Traitement du texte de description pour qu'il prenne en compte les Usertags.
             */
            /*
            var t__ = d.body;
            t__ = $("<div/>").text(t__).text();
//            var t__ = Kxlib_Decode_After_Encode(v.adesc);
            if ( str__ && str__.length ) {
                
                var ustgs = Kxlib_DataCacheToArray(str__)[0];
                //                Kxlib_DebugVars([Kxlib_ObjectChild_Count(v.ustgs),ustgs[3]],true);
                var ps = (ustgs && $.isArray(ustgs[0])) ? Kxlib_GetColumn(3, ustgs) : [ustgs[3]];
                t__ = Kxlib_UsertagFactory(t__, ps, "tqr-unq-user");
                
                $(e).find(".jb-unq-ract-txt-bb").text(t__);
                t__ = $(e).find(".jb-unq-ract-txt-bb").text();
                t__ = Kxlib_SplitByUsertags(t__);
                
                $(e).find(".jb-unq-ract-txt-bb").html(t__);
            } else {
                $(e).find(".jb-unq-ract-txt-bb").text(t__);
            }
            //*/
            
            var ustgs = ( d.ustgs ) ? d.ustgs : null;
            var hashs = ( d.hashs ) ? d.hashs : null;
            
//            var txt = Kxlib_Decode_After_Encode(d.body);
            var txt = d.body;
            
//            Kxlib_DebugVars([JSON.stringify(d),hashs,ustgs],true);
//            Kxlib_DebugVars([d.artdesc,txt],true);
            //rtxt = RenderedText
            var rtxt = Kxlib_TextEmpow(txt,ustgs,hashs,null,{
                emoji : {
                    "size"          : 36,
                    "size_css"      : 20,
                    "position_y"    : 3
                }
            });
            $(e).find(".jb-unq-ract-txt-bb").text("").append(rtxt);
            
            return e;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
            
    var _f_PprArtUnqMdl_Ffil = function (d,s) {
//    this.PrepareArtUnqMdl_Fullfil = function (d) {
        //s : scope
        
        try {
            if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(s) | $.inArray(s,["tmlnr","trpg","nwfd","psmn","fav","tia-explr","tia-phtotk"]) === -1 ) { 
                return; 
            }

            /*
             * RAPPEL : 
             * L'objet suit la nomenclature : 
             *    "itemid": ok
             *    "artpic": ok
             *    "artdesc": ok
             *    (todo) "rlist":
             *    "trid": (inutile)
             *    "trtitle": ok
             *    "trhref": ok
             *    "trhref" : ok
             *    "time": ok
             *    "utc": ok
             *    "ep2": ok
             *    "ep1": ok
             *    "em1": ok
             *    "etl": ok
             *    "evlt": ok (Liste des vip et le nombre de personnes total)
             *    "evlt_u1": ok,
             *    "evlt_u2": ok,
             *    "evlt_u3": ok,
             *    "evlt_tl": ok,
             *    "muel" : ok
             *    "ueid": ok
             *    "ufn": ok
             *    "upsd": ok
             *    "uppic": ok
             *    "uhref": ok
             *    myel : ok
             * * */

            /* On crée le modèle */
    //        
    //        "+d.+"
    //        "+d.time+"
            
            //On récupère la phrase en local dans la langue de navigation.
            //On fait confiance car si la langue a changer à la moindre requete on va reload la page
            var alt = Kxlib_getDolphinsValue("USER_PFLPIC_ALT"), o = $("#unq-center");
            //On préfère mettre le ufn. Raisons : Lorsqu'on cherche quelqu'un sur internet, on le cherche via son FullName et non son pseudo que l'on ne connait pas vraiment.
            alt = alt.replace("%upsd%",d.ufn);
            
            //* Mettre en forme le "header" *//
            //RAPPEL : data-item stocke aussi la réf de l'objet d'origine. Il ne faut pas écraser cette valeur
            var t = $(o).find(".jb-unq-art-mdl").data("item");
            t = t.replace(/\[.*,(.*)\]/g,"["+d.itemid+",$1]"); //[19-07-14] : Pourquoi ne pas avoir effectué cette opération lors de l'opération précédente de sauvegarde ?
            $(o).find(".jb-unq-art-mdl").data("cache", "[" + d.itemid + "," + d.time + "," + d.utc + "],[" + d.ueid + "," + d.ufn + "," + d.upsd + "," + d.uppic + "]").data("item",t);
            
            /*
             * [DEPUIS 11-07-15] @BOR
             */
            var z__ = t.replace(/\[.*,(.*)\]/g,"$1");
            var $ra = $(z__);
            
            var idca = Kxlib_DataCacheToArray(t);
            var idi = idca[0][1];
//            Kxlib_DebugVars([t,idi],true);
//            return;

            /*
             * [DEPUIS 25-04-16] @BOR
             */ 
            var aitem, atime, afvtp, ahasfv;
            switch(s) {
                case "tmlnr" :
                case "trpg" :
                case "nwfd" :
                case "fav" :
                case "tia-explr" :
                case "tia-phtotk" :
                        aitem = ( $(idi).data("item") ) ? $(idi).data("item") : null; 
                        atime = ( $(idi).data("time") ) ? $(idi).data("time") : null;
                        afvtp = ( $(idi).data("fvtp") ) ? $(idi).data("fvtp") : null;
                        ahasfv = ( $(idi).data("hasfv") ) ? $(idi).data("hasfv") : null;
                    break;
                case "psmn" :
                        if ( idca[0][0] && $(".jb-pm-mdl-mx[data-ai='".concat(idca[0][0],"']")).length ) {
                            var $sldm = $(".jb-pm-mdl-mx[data-ai=".concat(idca[0][0],"]"));

                            aitem = ( $sldm.data("ai") ) ? $sldm.data("ai") : null; 
                            atime = ( $sldm.data("at") ) ? $sldm.data("at") : null;
                            afvtp = ( d.fvtp ) ? d.fvtp : null;
                            ahasfv = ( d.hasfv ) ? d.hasfv : null;
                        }
                    break;
                default :
                    return;
            }
//            Kxlib_DebugVars([aitem,atime,ahasfv,afvtp],true);
//            Kxlib_DebugVars([$(o).find(".jb-unq-art-mdl").data("item"),JSON.stringify($(o).find(".jb-unq-art-mdl").data("item"))],true);
//            Kxlib_DebugVars([$(idi).data("item"),$(idi).data("time"),$(idi).data("hasfv"),$(idi).data("fvtp")],true);
            if ( KgbLib_CheckNullity(aitem) | KgbLib_CheckNullity(atime) ){
                return;
            } else {
                $(o).find(".jb-unq-art-mdl")
                    .data("aitem",aitem).attr("data-aitem",aitem)
                    .data("atime",atime).attr("data-atime",atime)
                    .data("ahasfv",ahasfv).attr("data-hasfv",ahasfv)
                    .data("afvtp",afvtp).attr("data-afvtp",afvtp);
            }
            
            /*
             * [DEPUIS 02-05-16]
             *      Permet à des MODULES externes comme celui qui gère FAV de savoir le cas dans lequel nous nous trouvons.
             */
            $(".jb-unq-art-mdl").data("atype",s).attr("data-atype",s);
            
        
            if ( $.inArray(s,["nwfd","psmn","tia-explr","tia-phtotk"]) !== -1 ) {
                $(".jb-unq-nav-btn").addClass("this_hide");
            } else {
                
                /*
                 * [DEPUIS 18-06-2015] @BOR
                 * Gestion des boutons de navigation individuellement
                 */
                var at = _f_NavAcqArtType(idi);
//                Kxlib_DebugVars([at,$.inArray(at,Object.keys(_f_Gdf().nav_art_slctr))],true);
                if ( $.inArray(at,Object.keys(_f_Gdf().nav_art_slctr)) === -1 ) {
                    return;
                }
                var $asl = $(_f_Gdf().nav_art_slctr[at]);
                        
                
                /*
                 * [DEPUIS 10-07-15] @BOR
                 *  (1) On vérifie s'il n'y a qu'un seul Article.
                 *      Dans ce cas, il n'est pas necessaire de lancer l'opération.
                 *      En effet, il est pratiquement impossible qu'il existe des Articles anterieurs.
                 *  (2) On vérifie s'il y a plus de 3 Articles. 
                 *      S'il y a moins de 3 Articles et qu'on est sur le dernier de la liste, il n'est pas necessaire de lancer l'opération.
                 *      En effet, il est pratiquement impossible qu'il existe des Articles anterieurs.
                 *  (3) <TMLNR> Dans ce cas et s'il y a plus de 3 Articles et qu'on est au niveau du dernier article, on vérifie que le nombre d'Articles IML ne montre pas un signe, tel qu'il n'est nul besoin que de creuser plus profond.
                 *      Si le nombre d'Articles ITR est inferieur à celui IML, cela veut dire qu'il n'y en a plus. Sinon, il y aurait toujours le même nombre d'Articles dans les deux colonnes.
                 */
//                Kxlib_DebugVars([asl.length >= _f_Gdf().plmin_, at === "_TR_ITR", $asl.length < $(".jb-tmlnr-mdl-std").length, $asl.last().data("item") === d.itemid]);
                        
                if ( $asl.length === 1 ) {
                    $(".jb-unq-nav-btn").addClass("this_hide");
                } else if ( $asl.length < _f_Gdf().plmin_ && $asl.last().data("item") === d.itemid ) {
                    $(".jb-unq-nav-btn[data-dir='prev']").removeClass("this_hide");
                    $(".jb-unq-nav-btn[data-dir='next']").addClass("this_hide");
                } else if ( $asl.length >= _f_Gdf().plmin_ && at === "_TR_ITR" && $asl.length < $(".jb-tmlnr-mdl-std").length && $asl.last().data("item") === d.itemid ) {
                    $(".jb-unq-nav-btn[data-dir='prev']").removeClass("this_hide");
                    $(".jb-unq-nav-btn[data-dir='next']").addClass("this_hide");
                } else {
                    if ( $asl.first().data("item") !== d.itemid ) {
                        $(".jb-unq-nav-btn[data-dir='prev']").removeClass("this_hide");
                    } 

                    var $ldmr = ( s === "tmlnr" ) ? $(".jb-tmlnr-loadm-trg") : $(".jb-trpg-loadm-trg") ;
    //                Kxlib_DebugVars([asl.last().data("item") !== d.itemid, $ldmr.length, $ldmr.hasClass("EOP")]);
                    if ( $asl.last().data("item") !== d.itemid || ( $asl.last().data("item") === d.itemid && $ldmr.length && !$ldmr.hasClass("EOP") ) ) {
                        $(".jb-unq-nav-btn[data-dir='next']").removeClass("this_hide");
                    } 

    //                $(".jb-unq-nav-btn").removeClass("this_hide");
                }
            }
            
            /*
             * ETAPE :
             *      On vérifie si on est dans le cas d'un élément POSTMAN.
             */
            var t__ = t.replace(/\[.*,(.*)\]/g,"$1");
            if ( t__ === "tq:skip_psmn" ) {
                var at__ = ( d.hasOwnProperty("trid") && !KgbLib_CheckNullity(d.trid) && typeof d.trid === "string" ) ? "itr" : "iml"; 
                $(o).find(".jb-unq-art-mdl").data("psmn", "[" + d.itemid + "," + at__ + "]");
            }
            
            /*
             * ETAPE : 
             * On transforme les Usertags contenus dans le texte de description.
             */ 
            /*
            if ( d.hasOwnProperty("ustgs") && d.ustgs !== undefined && Kxlib_ObjectChild_Count(d.ustgs) ) {
                
//                Kxlib_DebugVars([JSON.stringify(d),JSON.stringify(d.ustgs)],true);
                 var t__ = Kxlib_UsertagFactory(d.artdesc,d.ustgs,"tqr-unq-user");
                 t__= $("<div/>").text(t__).text();
//                 Kxlib_DebugVars([t__],true);
//                 t__ = Kxlib_Decode_After_Encode(t__);

                 t__ = Kxlib_SplitByUsertags(t__);
                 
                 //Mettre en place la description
                 $(o).find(".jb-unq-artdesc-box span").html(t__);
            } else {
                t__ = d.artdesc;
//                t__ = Kxlib_EscapeForDataCache(t__);
//                t__ = $("<div/>").text(t__).text();
                t__ = $("<div/>").html(t__).text();
                
                //Mettre en place la description
                $(o).find(".jb-unq-artdesc-box span").text(t__);
            }
            //*/
            var ustgs = ( d.ustgs ) ? d.ustgs : null;
            var hashs = ( d.hashs ) ? d.hashs : null;
            if ( !( ustgs && hashs ) && $(idi).data("ajcache") ) {
//                var ajca_o = ( typeof $(idi).data("ajcache") === "object" ) ? $(idi).data("ajcache") : JSON.parse("'"+$(idi).data("ajcache")+"'");
                var ajca_o = ( typeof $(idi).data("ajcache") === "object" ) ? $(idi).data("ajcache") : JSON.parse($(idi).data("ajcache"));
                hashs = ( ajca_o.hashs ) ? ajca_o.hashs : null;
                ustgs = ( ajca_o.ustgs ) ? ajca_o.ustgs : null;
            }
            
            var txt = Kxlib_Decode_After_Encode(d.artdesc);
//            var txt = d.artdesc;
            
//            Kxlib_DebugVars([JSON.stringify(d),hashs,ustgs],true);
//            Kxlib_DebugVars([d.artdesc,txt],true);
            //rtxt = RenderedText
            var rtxt = Kxlib_TextEmpow(txt,ustgs,hashs,null,{
                emoji : {
                    "size"          : 36,
                    "size_css"      : 20,
                    "position_y"    : 3
                }
            });

//            Kxlib_DebugVars([rtxt],true);

//            $(o).find(".jb-unq-artdesc-box span").text("").append($("<div/>").html(d.artdesc).text());
            $(o).find(".jb-unq-artdesc-box span").text("").append(rtxt);
            
                    
            /*
             * ETAPE :
             * Mettre en forme les données d'EVAL
             */
            
            //[DEPUIS 11-07-15] @BOR
            if ( ( s === "nwfd" && $ra.data("hatr") === false ) || ( s === "psmn" && d.hatr === false )  ) {
                $(o).find(".jb-css-csam-eval-chs-wrp[data-scp='spcl']").addClass("this_hide");
                $(o).find(".jb-css-csam-eval-chs-wrp[data-scp='dslk']").addClass("this_hide");
            }
            
            $(o).find(".jb-csam-eval-oput").data("cache", "[" + d.ep2 + "," + d.ep1 + "," + d.em1 + "," + d.etl + "," + d.myel + "]").find("span").html(d.etl);
            $(o).find(".jb-evlbx-ch-nb[data-scp='scl']").text(d.ep2);
            $(o).find(".jb-evlbx-ch-nb[data-scp='cl']").text(d.ep1);
            $(o).find(".jb-evlbx-ch-nb[data-scp='dlk']").text(d.em1);
             
            /* Bind les boutons d'EVAL */
            //Récupération de l'identifiant
            var i = Kxlib_ValidIdSel(Kxlib_DataCacheToArray(t)[0][1]);
            $(o).find(".jb-csam-eval-choices").data("target",i);
            
            //On fait appel à EVALBOX pour afficher l'évaluation de l'utilisateur actif sur l'Article actif
            
            if ( typeof EVALBOX === "function" ) {
                (new EVALBOX()).DplCUzrEvl($(o),d.myel);
            }
            //On réaffiche la zone d'information
            $(".jb-unq-c-a-top").removeClass("this_invi");
            
//            Kxlib_DebugVars([d.prmlk,$(".jb-unq-c-a-pmlk-max").length],true);
            /* Mettre en forme le lien pour la version PermaLink de l'image */
            //On ajoute le lien permanent de l'image.
            if ( $(".jb-unq-c-a-pmlk-max") && $(".jb-unq-c-a-pmlk-max").length ) {
                var pl = "["+d.prmlk+"]";
                $(".jb-unq-c-a-pmlk-max").data("cache",pl);
//                $(".jb-unq-c-a-pmlk-max").data("cache","{A remplacer par le lien permanent}");
            }
            
            //* Mettre en forme l'image *//
            //Preléminaire : On masque le bloc texte si ce n'est pas déjà fait
            $("#unq-tv-nl-deco").addClass("this_hide");
            
            if (! d.vidu ) {
            
                $(o).find(".jb-unq-tv").load(function(){
                    $(".jb-unq-tv").removeClass("this_hide");
                    $("#unq-tv-noload").addClass("this_hide");
                }).error( function(){
                    if ( _f_GetMediaMode() === "image" ) { //[DEPUIS 02-05-16]
                        $(".jb-unq-tv").addClass("this_hide");
                        //Au cas où l'image de substitution n'est elle aussi pas disponible
                        $("#unq-tv-noload").removeClass("this_hide");
                        //On affiche le bloc texte
                        $("#unq-tv-nl-deco").removeClass("this_hide");

                        //On remplace l'image
                        this.src = Kxlib_GetExtFileURL("sys_url_img", "r/npberr.png");
    //                this.src = "http://timg.ycgkit.com/files/img/r/npberr.png"; //[NOTE 24-08-15] @author BOR
                    }
                }).attr("src", d.artpic);
                
                _f_SwVwMode("img",true);
            
                /*
                 * [DEPUIS 02-11-15] @BOR
                 *      On affiche l'image au format Fullscreen
                 */
                var fi = new Image();
                fi.src = d.artpic;
                fi.onload = function(){
                    /*
                     * ETAPE :
                     *      On vérifie que l'image n'est pas trop grande.
                     *      Si c'est le cas, on la redimensionne.
                     */
                    var wsz = {
                        "h" : $(window).height(),
                        "w" : $(window).width()
                    };

                    var fh, fw;
                    if ( ( fi.height <= (wsz.h-50) ) && ( fi.width <= (wsz.w-50) ) ) {
                        fh = fi.height, fw = fi.width;
                    } else {
                        var r__ = ( fi.height >=  fi.width ) ? fi.height : fi.width;
                        var c__ = ( fi.height >=  fi.width ) ? ((wsz.h-50)/r__) : ((wsz.w-50)/r__);
    //                    Kxlib_DebugVars([r__, c__],true);
                        fh = fi.height*c__;
                        fw = fi.width*c__;
                    }
    //                Kxlib_DebugVars([fh, fw],true);
                    $(o).find(".jb-tqr-tv-fscrn-i-mx").height(fh).width(fw);
                    $(o).find(".jb-unq-tv-fscrn-i").height(fh).width(fw).attr("src", d.artpic);

                    /*
                    $(o).find(".jb-unq-tv-fscrn-clz").off("click").on("click",function(e){
                        $(o).find(".jb-unq-tv-fscrn-bmx").addClass("this_hide");
                    });
                    $(o).find(".jb-unq-art-pic-wide").off("click").on("click",function(e){
                        $(o).find(".jb-unq-tv-fscrn-bmx").removeClass("this_hide");
                    });
                    //*/
                    
                    $(o).find(".jb-unq-tv-fscrn-clz").off("click").on("click",function(e){
                        _f_SwWideImgView(o);
                    });
                    $(o).find(".jb-unq-art-pic-wide").off("click").on("click",function(e){
                        _f_SwWideImgView(o,true); 
                    });
                };
                
                
            } else {
                _f_SwVwMode("vid",true);
                
                var vid_metas = _f_MagicVid(d.vidu);
//                Kxlib_DebugVars([JSON.stringify(vid_metas)],true);
                
                if (! vid_metas.loop ) {
                    $(".jb-unq-tv-vid").removeProp("loop");
                } else {
                    $(".jb-unq-tv-vid").prop("loop");
                }
                
                /*
                 * ETAPE :
                 *      On ajoute les données sur la vidéo
                 */
                $(".jb-unq-tv-vid").prop("width",vid_metas.width).prop("height",vid_metas.height).prop("src",d.vidu);
                
                /*
                 * ETAPE :
                 *      Traitement des données pour le mode "plein écran"
                 */
                $(o).find(".jb-tqr-tv-fscrn-v-mx").height(vid_metas.height).width(vid_metas.width);
                $(o).find(".jb-unq-tv-fscrn-v").height(vid_metas.height).width(vid_metas.width).attr("src",d.vidu);
                $(o).find(".tqr-tv-fscrn-v-fd").css({
                    "line-height" : vid_metas.height.toString().concat("px")
                });
                if (! vid_metas.loop ) {
                    $(".jb-unq-tv-fscrn-v").removeProp("loop");
                } else {
                    $(".jb-unq-tv-fscrn-v").prop("loop");
                }
                
                /*
                 * ETAPE :
                 *      Gestion des boutons pour le mode "plein écran"
                 */
                $(o).find(".jb-unq-tv-fscrn-clz").off("click").on("click",function(e){
                    _f_SwWideVidView(o);
                });
                $(o).find(".jb-unq-art-pic-wide").off("click").on("click",function(e){
                    _f_SwWideVidView(o,true);
                });
                
            }
            
            /*
             * [DEPUIS 25-04-16]
             */
//            Kxlib_DataCacheToArray($(".jb-unq-art-mdl").data("item"))[0][1];     
//            Kxlib_DebugVars([typeof $(o).find(".jb-unq-art-mdl").data("ahasfv"), $(o).find(".jb-unq-art-mdl").data("ahasfv")],true);
        
            if ( $(o).find(".jb-unq-art-mdl").data("ahasfv") ) {
                $(o).find(".jb-tqr-art-abr-tgr")
                    .data("action","unfavorite").attr("data-action","unfavorite")
                    .data("reva","favorite").attr("data-reva","favorite")
                    .data("revt","Mettre en favori").attr("data-revt","Mettre en favori")
                    .attr("title","Retirer des favoris");
            } else {
                $(o).find(".jb-tqr-art-abr-tgr")
                    .data("action","favorite").attr("data-action","favorite")
                    .data("reva","unfavorite").attr("data-reva","unfavorite")
                    .data("revt","Retirer des favoris").attr("data-revt","Retirer des favoris")
                    .attr("title","Mettre en favori");
            }
            
            /* 
             * ETAPE : 
             * Mettre en forme le temps 
             */
//            Kxlib_DebugVars([d.time,d.utc,d.alltime],true);
//            alert($(o).find("#unq-art-time").length);
            $(o).find("#unq-art-time").data("tgs-crd", d.time);
            $(o).find("#unq-art-time").data("tgs-dd-atn", d.utc);
            //On retire les anciennes valeurs pour éviter toute confusion
//             alert($(o).find("#unq-art-time").html());       
            //[NOTE 23-07-14] Je dis bien html() et non text()!!!
                    
            $(o).find("#unq-art-time").html(d.alltime);
            
            //On appelle TG pour la mise à jour
            if ( typeof TIMEGOD === 'function' ) {
                var TG = new TIMEGOD();
                TG.UpdSpies($(o).find("#unq-art-time"));
            }
                    
            //Mettre en forme les données de la Tendance (Si le modèle cible est un Article de Tendance.)
//            Kxlib_DebugVars([d.trtitle,!KgbLib_CheckNullity(d.trtitle), d.trtitle !== "''"],true);
            if ( !KgbLib_CheckNullity(d.trtitle) && d.trtitle !== "''" ) {
                $(o).find("#unq-c-a-trttl").attr("title", d.trtitle);
                $(o).find(".jb-unq-c-a-tt-lk").attr("href", d.trhref);
                $(o).find(".jb-unq-c-a-tt-lk").attr("title", d.trtitle);
                $(o).find(".jb-unq-c-a-tt-lk-txt").text(d.trtitle);
//                $(o).find(".jb-unq-c-a-tt-lk").attr("title", d.trtitle).text(d.trtitle);
                
                //[NOTE au 20-07-14] Le rendre invisble permet de ne pas faire de grosses modifications sur le système tel qu'il est à l'heure actuel
                $(o).find("#unq-c-a-trttl").removeClass("this_invi");
            } else {
                $(o).find("#unq-c-a-trttl").attr("title","");
                $(o).find(".jb-unq-c-a-tt-lk").attr("href","");
                $(o).find(".jb-unq-c-a-tt-lk").attr("title","");
                $(o).find(".jb-unq-c-a-tt-lk-txt").text("");
//                $(o).find(".jb-unq-c-a-tt-lk").attr("title","").text("");
                
                $(o).find("#unq-c-a-trttl").addClass("this_invi");
            }
            
            
            /* Mettre en forme les données relatives aux personnes qui ont apprécié l'Article */
            //ul = UserList; gul = GlobalUserList contient le nombre de personnes qui ont évaluées l'Article
            
            if ( !KgbLib_CheckNullity(d.evlt_u1) && !KgbLib_CheckNullity(d.evlt_u2) && !KgbLib_CheckNullity(d.evlt_u3) && !KgbLib_CheckNullity(d.evlt_tl) ) {
                var ul = [d.evlt_u1,d.evlt_u2,d.evlt_u3];
                $.each($(".jb-unq-vip-eval-users"),function(x,v){
                    $(v).find(".jb-unq-vip-vip").text("@"+ul[x]);
                    $(v).attr({
                        "href":"/@"+ul[x],
                        "title":"@"+ul[x]+"est sur Trenqr",
                        "alt":"Aller vers le compte de "+"@"+ul[x]+" sur Trenqr"
                    });
                });
                //GlobalUserList
                $("#jb-eval-gul").text(d.evlt_tl);
                
                //On affiche de nouveau les éléments cachés au cas ils auraient été cachés.
                $("#unq-eval-box > *").removeClass("this_hide");
                
            } else {
                $("#unq-eval-box > *").addClass("this_hide");
            }
            
            /*
             * [NOTE 09-10-14] Je ne comprends pas ce que ce code fait là, mais bon !
             */
//            $(o).find("#unq-c-a-trttl").attr("title", d.trtitle);
//            $(o).find(".jb-unq-c-a-tt-lk").attr("href", d.trhref);
//            $(o).find(".jb-unq-c-a-tt-lk").attr("title", d.trtitle).html(d.trtitle);
//            $(o).find(".jb-unq-c-a-tt-lk").attr("title", d.trtitle).html(d.trtitle);
            
            
            /* Mettre en forme les données concernant le propriétaire de l'Article */
//            $(o).find(".jb-unq-u-b-owr-img-box").removeAttr("style"); //RETIRE 22-09-14
            $(o).find(".jb-unq-u-b-owr-img-box").attr({
                'src': d.uppic
            });
//            Kxlib_DebugVars([d.upsd,d.uhref],true);
        
//            $(o).find("#unq-u-b-owr-grp").append(pp);
//            $(o).find("#unq-u-b-owr-grp").attr("href", d.uhref); //[DEPUIS 02-07-16]
            $(o).find("#unq-u-b-owr-grp").attr("href", "/".concat(d.upsd));
            
            /* RETIRE 22-09-14
            //Cette methode permet de tester si l'image existe avant de la chargée en background
            $('<img/>').load(function() {
                $(this).remove(); // prevent memory leaks
                $(o).find(".jb-unq-u-b-owr-img-box").attr("style", "background: url(" + d.uppic + ") no-repeat");
             }).error(function(){
                $(o).find(".jb-unq-u-b-owr-img-box").removeAttr("style");
                alert( "BAD REQUEST : Error 400 URL PIC" );
            }).attr('src', d.uppic);
            //*/
            $(o).find(".jb-unq-u-b-owr-psd").text(Kxlib_ValidUser(d.upsd));
            
            //En fonvtion de la taille du pseudo en adapte le visuel
            //21 : Y compris '@'
            if ( d.upsd.length === 21 ) {
                $(o).find(".jb-unq-u-b-owr-psd").addClass("unq-u-b-owr-psd-max");
            } else {
                $(o).find(".jb-unq-u-b-owr-psd").removeClass("unq-u-b-owr-psd-max");
            }
            
            //RAPPEL : On ne travaille pas ici sur les commentaires, ça sera fait une fois que le modèle correctement sera affichée
            
            /*
             * ETAPE : 
             * On affiche les modules qui sont masqués
             */
            $(".jb-unq-c-r").removeClass("this_hide");
            $(".jb-unq-u-bx-owr").removeClass("this_hide");
        } catch (ex) {
//            var m = Kxlib_getDolphinsValue("ERR_UNQ_ONPREPARE");
//            Kxlib_DebugVars([m],true);
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }
    };
    
    var _f_GetMediaMode = function () {
        try {
            var type = $(".jb-unq-tv-med-scn").filter(":visible").data("media-type");
            if (! ( $(".jb-unq-tv-med-scn").filter(":visible").length === 1 && type && $.inArray(type,["image","video"]) !== -1 ) ) {
                return;
            }
            return type;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_SwWideImgView = function (o,sh) {
        try {
            if ( KgbLib_CheckNullity(o) ) {
                return;
            }
            
            if ( sh ) {
                $(".jb-tqr-tv-fscrn-v-mx").addClass("this_hide");
                $(".jb-tqr-tv-fscrn-i-mx").removeClass("this_hide"); 
                
                $(o).find(".jb-unq-tv-fscrn-bmx").removeClass("this_hide");
            } else {
                $(".jb-tqr-tv-fscrn-i-mx").addClass("this_hide");
                $(".jb-tqr-tv-fscrn-v-mx").addClass("this_hide");

                $(o).find(".jb-unq-tv-fscrn-bmx").addClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_SwWideVidView = function (o,sh) {
        try {
            if ( KgbLib_CheckNullity(o) ) {
                return;
            }
            
            if ( sh ) {
                $(".jb-tqr-tv-fscrn-i-mx").addClass("this_hide");
                $(".jb-tqr-tv-fscrn-v-mx").removeClass("this_hide"); 
                
                var lh = $(".jb-unq-tv-fscrn-v").height();
                $(".jb-unq-tv-lnch-vid[data-scp='theater']").css({
                    "line-height": lh+"px"
                });

                $(o).find(".jb-unq-tv-fscrn-bmx").removeClass("this_hide");
                
                /*
                 * ETAPE :
                 *      Dans tous les cas, on arrete la video au niveau de STD_SCREEN
                 */
                var vid = $(".jb-unq-tv-vid").get(0);
                var x = $(".jb-unq-tv-lnch-vid[data-action='vid-play'][data-scp='standard']");
                _f_VidPause(x,vid);
                
            } else {
                $(".jb-tqr-tv-fscrn-i-mx").addClass("this_hide");
                $(".jb-tqr-tv-fscrn-v-mx").addClass("this_hide");

                $(o).find(".jb-unq-tv-fscrn-bmx").addClass("this_hide");
                
                /*
                 * ETAPE :
                 *      Dans tous les cas, on arrete la video au niveau de FULLSCREEN
                 */
                var vid = $(".jb-unq-tv-fscrn-v").get(0);
                var x = $(".jb-unq-tv-lnch-vid[data-action='vid-play'][data-scp='theater']");
                _f_VidPause(x,vid);
                
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_SwVwMode = function (scp,shw) {
        try {
            if ( KgbLib_CheckNullity(scp) ) {
                return;
            }
            
            switch (scp) {
                case "vid" :
                        $widw = $(".jb-unq-tv-media-vid-mx");
                    break;
                case "img" :
                        $widw = $(".jb-unq-tv-media-img-mx");
                    break;
                default :
                    return;
            }
            
            if (! $widw.length ) {
                return;
            }
            
            if ( shw ) {
                $(".jb-unq-tv-media-scp").addClass("this_hide");
                $widw.removeClass("this_hide");
            } else {
                $(".jb-unq-tv-media-scp").addClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_MagicVid = function (vidu) {
        try {
            if ( KgbLib_CheckNullity(vidu) ) {
                return;
            }
            
            var t__ = /[\s\S]+\.([\w]{3,4})\?fmat=([\d]+)x([\d]+)\&dur=([\d]{1,2})/g.exec(vidu), metas;
            
            if ( Array.isArray(t__) && t__.length ) {
                var w =  parseInt(t__[2]), h =  parseInt(t__[3]), ref = $(".jb-unq-c-img-t-store").width();
                if ( ( w / h ) === 1 ) {
                    metas = {
                        "type"      : t__[1],
                        "width"     : ref,
                        "height"    : ref,
                        "duration"  : t__[4],
                        "loop"      : ( parseInt(t__[4]) <= 10 ) ? true : false 
                    };
                } else {
                    var ratio = ( w >= h ) ? w/ref : h/ref;
                    metas = {
                        "type"      : t__[1],
                        "width"     : w/ratio,
                        "height"    : h/ratio,
                        "duration"  : t__[4],
                        "loop"      : ( parseInt(t__[4]) <= 10 ) ? true : false 
                    };
                }
            } else {
                metas = null;
            }
            
//            Kxlib_DebugVars([typeof t__,t__,JSON.stringify(t__),JSON.stringify(metas)],true);
            return metas;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_EnaEffi = function (arp,yon,cuztm) {
        try {
            if (! $(arp).length ) {
                return;
            }
            
            if (! cuztm ) {
                if ( yon ) {
                    $(arp).find(".jb-arp-bot-img-time, .jb-tmlnr-arp-art-clz-all, .jb-arp-bot-img-fmr").addClass("effi");
                    _f_MvArtDesc($(arp).find(".jb-arp-bot-img-desc-move[data-action='adesc_move_down']"));
                } else {
                    $(arp).find(".jb-arp-bot-img-time, .jb-tmlnr-arp-art-clz-all, .jb-arp-bot-img-fmr").removeClass("effi");
                    _f_MvArtDesc($(arp).find(".jb-arp-bot-img-desc-move[data-action='adesc_move_up']"));
                }
            }
//            {
//                time    : false,
//                close_all : false,
//                close   : false,
//                desc    : false
//            }
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    // [08-07-14] Desuet. Remplacé par PrepareArtUnqMdl_Fullfil **/
    var _f_PprArtUnqMdl = function (d) {
//    this.PrepareArtUnqMdl = function (d) {
        if ( KgbLib_CheckNullity(d) ) { 
            return; 
        }
        
        try {
            /*
             * RAPPEL : 
             * L'objet suit la nomenclature : 
             *    "itemid": ok
             *    "artpic": ok
             *    "artdesc": ok
             *    (todo) "rlist":
             *    (todo) "evaluser_list"
             *    "trid": (inutile)
             *    "trtitle": ok
             *    "trhref": ok
             *    "time": ok
             *    "utc": ok
             *    "ep2": ok
             *    "ep1": ok
             *    "em1": ok
             *    "etl": ok
             *    "ueid": ok
             *    "ufn": ok
             *    "upsd": ok
             *    "uppic": ok
             *    "uhref": ok
             * * */

            /* On crée le modèle */
    //        
    //        "+d.+"
    //        "+d.time+"

            //On récupère la phrase en local dans la langue de navigation.
            //On fait confiance car si la langue a changer à la moindre requete on va reload la page
            var alt = Kxlib_getDolphinsValue("USER_PFLPIC_ALT");
            //On préfère mettre le ufn. Raisons : Lorsqu'on cherche quelqu'un sur internet, on le cherche via son FullName et non son pseudo que l'on ne connait pas vraiment.
            alt = alt.replace("%upsd%",d.ufn);

            /* Récupération de la liste des commentaires */
            //rdl = ReactionDatasList; Les données sont stockées dans la variable globale _rdl;
    //        _f_GtAlRctsLmt(d.itemid);

            /* TODO : Récupération de la liste sur les personnes qui ont apprécié l'Article (3 VIP et le nombre total */

            var e = "<div id=\"unq-center\" class=\"\">";
            e += "<div id=\"unq-c-right\">";
            e += "<div id=\"\" class=\"unq-art-mdl jb-unq-art-mdl active\" data-item=\""+d.itemid+"\" data-cache=\"["+d.itemid+","+d.time+","+d.utc+"],["+d.ueid+","+d.ufn+","+d.upsd+","+d.uppic+"]\">";
            e += "<div id=\"unq-c-aside-max\" class=\"clearfix\">";
            e += "<div id=\"unq-c-a-top\">";
            e += "<div id=\"unq-artdesc-box\" class=\"unq-c-a-r-box jb-unq-artdesc-box clearfix\">";
            e += "<span>"+d.artdesc+"</span>";
            e += "</div>";
            e += "<div id=\"unq-user-box\" class=\"unq-c-a-r-box\">";
            e += "<div class=\"jb-csam-eval-box css-eval-box css-eval-box-tmlnr css-eval-box-unq clearfix\">";
            e += "<span class=\"jb-csam-eval-oput css-csam-eval-oput\" data-cache=\"["+d.ep2+","+d.ep1+","+d.em1+","+d.etl+"]\"><span>"+d.etl+"</span> coo<i>!</i></span>";
            e += "<div>";
            e += "<a id=\"\" class=\"jb-csam-eval-choices css-csam-eval-chs css-c-e-chs-scl\" data-action=\"rh_spcl\" data-zr=\"rh_spcl\" data-rev=\"bk_spcl\" data-target=\"\" title=\"SupaCool\" href=\"\"></a>";
            e += "<a id=\"\" class=\"jb-csam-eval-choices css-csam-eval-chs css-c-e-chs-cl\" data-action=\"rh_cool\" data-zr=\"rh_cool\" data-rev=\"bk_cool\" data-target=\"\" title=\"J'adhère\" href=\"\"></a>";
            e += "<a id=\"\" class=\"jb-csam-eval-choices css-csam-eval-chs css-c-e-chs-dsp\" data-action=\"rh_dislk\" data-zr=\"rh_dislk\" data-rev=\"bk_dislk\" data-target=\"\" title=\"J'adhère pas\" href=\"\"></a>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e += "<div id=\"unq-eval-box\">";
            e += "<a id=\"\" class=\"unq-vip-eval-users\" href=\"\" alt=\"\"></a>, <a id=\"\" class=\"unq-vip-eval-users\" href=\"\" alt=\"\"></a>, <a id=\"\" class=\"unq-vip-eval-users\" href=\"\" alt=\"\"></a>  <span>and</span> <a id=\"\" class=\"unq-vip-eval-users\" href=\"\"> others</a> <span id=\"\">ont trouvé ça plus ou moins coo!.</span>";
            e += "</div>";
            e += "</div>";
            e += "<div id=\"unq-c-a-react-max\" class=\"this_hide\">";
            e += "<div id=\"unq-c-a-react-box\" class=\"\">";
            /*
             * MODELE :
             *   "itemid":
             *   "ueid":
             *   "ufn":
             *   "upsd":
             *   "uppic":
             *   "uhref":
             *   "msg":
             *   "time": //Inutile pour [08-07-14]
             *   "utc": //Inutile pour [08-07-14]
             * * */
    //        alert("JohnDoe => "+e);
            $.each(_rdl,function(x,v){
                var alt = Kxlib_getDolphinsValue("USER_PFLPIC_ALT");
                //On préfère mettre le ufn. Raisons : Lorsqu'on cherche quelqu'un sur internet, on le cherche via son FullName et non son pseudo que l'on ne connait pas vraiment.
                alt = alt.replace("%upsd%",v.ufn);

                e += "<div id=\"unq-react-"+v.itemid+"\" class=\"unq-react-mdl jb-unq-react-mdl\" data-item=\""+v.itemid+"\" data-cache=\"["+v.ueid+","+v.ufn+","+v.upsd+"]\">";
                e += "<div class=\"unq-react-left\">";
                e += "<div>";
                e += "<a class=\"unq-react-upic-box\" href=\""+v.uhref+"\" alt=\""+alt+"\" style=\"background-image: url("+v.uppic+"\"></a>";
                e += "</div>";
                e += "</div>";
                e += "<div class=\"unq-react-txt\">";
                e += "<a class=\"unq-react-auth jb-unq-react-auth\" href=\"\">@"+v.upsd+"</a>";
                e += "<span class=\"unq-react-txt-bb\">"+v.msg+"</span>";
                e += "<a class=\"unq-react-del jb-unq-react-del this_hide\" data-target=\"unq-react-"+v.itemid+"\" href=\"\">Supprimer</a>";
                e += "</div>";
                e += "</div>";
            });

            e += "</div>";
            e += "</div>";
            e += "<div id=\"unq-c-a-bot\" class=\"this_hide\">";
            e += "<a id=\"unq-show-addrct-trg\" class=\"jb-unq-show-addrct-trg\" href=\"\" style=\"background-image: url(http://lorempixel.com/45/45/people/1);\">";
            e += "</a>";
            e += "<span id=\"unq-count-box\">";
            e += "<span id=\"jb-unq-c-b-nb\">x</span>";
            e += "<span>Comments</span>";
            e += "</span>";

            e += "</div>";
            e += "</div>";
            e += "<div id=\"unq-c-img-max\">";
            e += "<div id=\"unq-c-img-top\">";
            e += "<div id=\"unq-c-img-t-store\">";
            e += "<!-- On store les images -->";
            e += "<span id=\"\" class=\"unq-tv\" style=\"background-image: url("+d.artpic+");\"></span>";
            e += "<span id=\"unq-tv-fade\"></span>";
            e += "<span id=\"unq-art-time\" class='kxlib_tgspy' data-tgs-crd=\""+d.time+"\" data-tgs-dd-atn=\""+d.utc+"\" data-tgs-dd-uut=''>";
            e += "<span class='tgs-frm'></span>";
            e += "<span class='tgs-val'></span>";
            e += "<span class='tgs-uni'></span>";
            e += "</span>";
            e += "</div>";
            e += "</div>";
            e += "<div id=\"unq-c-img-bot\" class=\"\">";
            e += "<div id=\"unq-c-a-trttl\" title=\""+d.trtitle+"\">";
            e += "<a id=\"unq-c-a-tt-lk\" href=\""+d.trhref+"\" alt=\"Trend's title\" title=\""+d.trtitle+"\">"+d.trtitle+"</a>";
            e += "</div>";
            e += "<div id=\"unq-c-a-addr\" class=\"\">";
            e += "<div id=\"unq-c-a-addr-grp\">";
            e += "<a id=\"unq-rst-add-rct\" class=\"jb-unq-rst-add-rct\" href=\"\" title=\"Reset the form\" alt=\"Reset add new reaction form on trenqr\">Reset</a>";
            e += "<textarea id=\"unq-add-rct-input\"></textarea>";
            e += "<a id=\"unq-add-rct-trg\" class=\"jb-unq-add-rct-trg\" href=\"\" title=\"Add new reaction\" alt=\"Add new reaction on trenqr\">Do it</a>";
            e += "</div>";
            e += "<div id=\"unq-a-action\">";
            e += "<a class=\"u-a-a-choices\" data-action=\"perma\" href=\"\" title=\"Show Permalink\" alt=\"Afficher le lien permanent du post sur trenqr\">Permalink</a>";
            e += "<a class=\"u-a-a-choices\" data-action=\"del_start\" href=\"\" title=\"Delete Post\" alt=\"Supprimer le post sur trenqr\">Supprimer</a>";
//            e += "<a class=\"u-a-a-choices\" data-action=\"delete\" href=\"\" title=\"Delete Post\" alt=\"Supprimer le post sur trenqr\">Supprimer</a>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e += "<div id=\"unq-c-left\">";
            e += "<div id=\"unq-user-box-owr\">";
//            e += "<a id=\"unq-u-b-owr-grp\" href=\""+d.uhref+"\">"; //[DEPUIS 02-07-16]
            e += "<a id=\"unq-u-b-owr-grp\" href=\"/"+d.upsd+"\">";
            e += "<span id=\"unq-u-b-owr-img-box\" style=\"background-image: url("+d.uppic+")\"></span>";
            e += "<span id=\"unq-u-b-owr-psd\">"+d.upsd+"</span>";
            e += "</a>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
        
        
            return $.parseHTML(e);
        } catch (ex) {
//            var m = "Les liens vers href sont surement erronés ! ";
//            Kxlib_DebugVars([m],true);
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }
    };
    
    var _f_ShwAllRcts = function (ld) {
//    this._View_DisplayAllReacts = function (ld) {
        /* ld = List(ofDatas) Liste des données dont chaque élément représente un Commentaire
         * */
        try {
             if ( KgbLib_CheckNullity(ld) ) { 
                return; 
            }
        
            /*
             * [DEPUIS 04-07-15] @BOR
             * On retire NoOne
             */
            _f_RctHdlNone(false);
            
            //rr = ReactRef (Jquery Object)
            var rr = new Array();
            ld = ld.reverse();
            $.each(ld, function(x,v) {
                /*
                 * ETAPE :
                 * On vérifie que le commentaire n'existe pas déjà dans la zone.
                 */
                if ($(".jb-unq-c-a-r-bx").find(".jb-unq-react-mdl[data-item='" + v.itemid + "']").length) {
//                    Kxlib_DebugVars([UNQ : Reaction exists !"]);
                    return true;
                }
                
                /*
                 * ETAPE :
                 * On s'assure que le Commentaire sera chargé pour le bon Article.
                 */
                var sl = $(".jb-unq-art-mdl").data("item");
                var ai__ = sl.replace(/\[(.*),.*\]/g, "$1");
                if ( v.raid.toLowerCase() !== ai__.toLowerCase() ) {
//                    Kxlib_DebugVars([UNQ : Bad Article reference for Reaction !"]);
                    return true;
                }
                
                var e = _f_PprUnqRct(v);
                
                //On rebind les événements
                e = _f_RebindNwRct(e);
                
                //On ajoute dans la liste des elements
                rr.push($(e));
                
                //On ajoute le Commentaire à la liste
                /*
                 * [NOTE 07-12-14] @author L.C.
                 * Je ne me souviens plus de la finalité du débat sur l'ordre d'affichage des commentaires alors je tente un affichage DESC.
                 * C'est exactement la même chose que pour l'Ajout des commentaires.
                 */
                $(e).appendTo(".jb-unq-c-a-r-bx");
//            $(e).hide().appendTo(".jb-unq-c-a-r-bx").fadeIn();
//            $(e).hide().prependTo(".jb-unq-c-a-r-bx").fadeIn();
            });
            
            var $tar = $(".jb-unq-c-a-r-bx");
            /*
             * [06-04-15] @BOR
             * ETAPE :
             * On scroll jusqu'à la fin de la zone de texte.
             * [08-05-15] @BOR
             * On effectue maintenant la somme des Commentaires. Cela permet de régler le bogue sur la taille de la zone.
             * Le processus a été déplacé vers une methode dédiée pour s'assurer d'obtenir la taille réelle des éléments.
             * A ce stade, les éléments n'étant pas visibles, ils n'ont pas de taille.
             */
//            $($tar).animate({scrollTop: $($tar).height()}, 1000);
            
            return rr;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }

    };
    
    var _f_RctHdlNone = function(sh){
        try {
            if ( sh === true ) {
                $(".jb-unq-c-a-rct-bx-none").removeClass("this_hide");
            } else if ( sh === false ) {
                $(".jb-unq-c-a-rct-bx-none").addClass("this_hide");
            } else {
                if ( $(".jb-unq-react-mdl").length ) {
                    $(".jb-unq-c-a-rct-bx-none").addClass("this_hide");
                } else {
                    $(".jb-unq-c-a-rct-bx-none").removeClass("this_hide");
                }
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }
    };
    
    var _f_RctHdlSpnr = function(sh){
        try {
            if ( sh === true ) {
                $(".jb-unq-c-a-rct-spnr-mx").removeClass("this_hide");
            } else {
                $(".jb-unq-c-a-rct-spnr-mx").addClass("this_hide");
            } 
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }
    };
    
    var _f_RctHdlDnyAkx = function(sh){
        try {
            if ( sh === true ) {
                $(".jb-unq-c-a-rct-dnyakx-bmx").removeClass("this_hide");
            } else {
                $(".jb-unq-c-a-rct-dnyakx-bmx").addClass("this_hide");
            } 
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }
    };
    
    
    /*********************************************************************************************************************************************************/
    /******************************************************************** LISTENERS SCOPE ********************************************************************/
    /*********************************************************************************************************************************************************/
    
    /**** UNIQUE MAX  ****/
    $("#unique-max, .jb-unq-close-trg").click(function (e) {
        Kxlib_PreventDefault(e);
        
        _f_OnClose();
    });
    
    $("#unique-max *").click(function (e) {
        Kxlib_StopPropagation(e);
    });
    
    
    /**** UNQ SEE MORE *****/
    $(".jb-unq-show-addrct-trg").off().click(function (e) {
        Kxlib_PreventDefault(e);
        
        _f_ShwAddRBox();
    });
    
    $(".jb-unq-show-addrct-trg > *").off().click(function (e) {
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        _f_ShwAddRBox();
    });
    
    $(".jb-unq-rst-add-rct").click(function (e) {
        Kxlib_PreventDefault(e);
        
        _f_RstNwRForm();
    });
    
    
    /**** REACTIONS ASIDE ****/
    
//    $(".jb-unq-add-rct-ipt").keypress(function(e){
    $(".jb-unq-add-rct-ipt").off().keypress(function(e){
        if ( (e.which && e.which === 13) || (e.keyCode && e.keyCode === 13) ) {
            Kxlib_PreventDefault(e); //[DEPUIS 11-07-15] @BOR
//            Kxlib_DebugVars([UNIQUE -> 2846"]);

            $(".jb-unq-add-rct-trg").click();
//            _f_AddRct(); //[DEPUIS 11-07-15] @BOR
        }
    });
    
    $(".jb-unq-add-rct-trg").off().click(function(e){
//    $(".jb-unq-add-rct-trg").click(function(e){
        Kxlib_PreventDefault(e);
//        Kxlib_DebugVars([UNIQUE -> 2852"]);
        _f_AddRct(this);
    });
    
    $(".jb-unq-react-del, .jb-unq-rct-fnl-dc").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_DelRct(this);
    });
    
    $(".jb-unq-react-answ").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_AnswRct(this);
    });
    
    
    /******* ARTICLE **********/
   /*
    * [08-12-14] @author L.C.
    * .off() pour solutionner le bogue du double listerne avec npost.brain.d.js
    */
    $(".jb-tmlnr-mdl-intr .fcb_img_maximus").off("click").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
//        Kxlib_DebugVars(["2093","OnOpen Listener"],true);
        
        gt.OnOpen("tmlnr",this);
    });
    
    $(".mdl-tr-post-in-list .fcb_img_link").off().click(function(e){
        Kxlib_PreventDefault(e);
//        Kxlib_DebugVars(["1857","OnOpen Listers"],true);
        gt.OnOpen("trpg",this);
    });
    
    /*
     * [DEPUIS 27-04-16]
     */
    $(".jb-tmlnr-mdl-intr .fcb_img_maximus .jb-irr, .mdl-tr-post-in-list .fcb_img_link .jb-irr").off("click").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
    });
            
    $(".jb-nwfd-b-l-mdl-b-box .jb-nwfd-b-l-mdl-b-box-box").off("click").click(function(e){
//    $(".nwfd-b-l-mdl-b-box .nwfd-b-l-mdl-b-box-box").off().click(function(e){
        Kxlib_PreventDefault(e);
//        Kxlib_DebugVars(["1863","OnOpen Listers"],true);
        gt.OnOpen("nwfd",this);
    });
             
    $(".nwfd-b-moz-mdl-max .nwfd-b-m-mdl-trig").off("click").click(function(e){
//    $(".nwfd-b-moz-mdl-max .nwfd-b-m-mdl-trig").off().click(function(e){
        Kxlib_PreventDefault(e);
//        Kxlib_DebugVars(["1869","OnOpen Listers"],true);
        gt.OnOpen("nwfd",this);
    });
    
    /*  //[DEPUIS 14-04-16] Le cas est géré en interne par POSTMAN. Même si je pense que ces lignes étaient utiles pour le cas DEBUG
    $(".jb-pm-mdl-mx").off("click").click(function(e){
//    $(".nwfd-b-moz-mdl-max .nwfd-b-m-mdl-trig").off().click(function(e){
        Kxlib_PreventDefault(e);
//        Kxlib_DebugVars(["1869","OnOpen Listers"],true);
        gt.OnOpen("psmn",this);
    });
    */
   
    /*
     * [DEPUIS 19-12-15]
     */
     $(".jb-tmlnr-pgfv-art-i-bmx").off("click").click(function(e){
//    $(".nwfd-b-moz-mdl-max .nwfd-b-m-mdl-trig").off().click(function(e){
        Kxlib_PreventDefault(e);
//        Kxlib_DebugVars(["1869","OnOpen Listers"],true);
        gt.OnOpen("fav",this);
    });
    
    $(".jb-u-a-a-choices[data-action=del_start]").click(function(e){
//    $(".jb-u-a-a-choices[data-action=delete]").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_OnDelArt(this); //[DEPUIS 07-08-15] @BOR
//        $(".jb-unq-cfrm-bx-mx").removeClass("this_hide"); //[DEPUIS 07-08-15] @BOR
//        _f_OnDelArt();
    });
    
    $(".jb-unq-del-sbchcs[data-action=del_abort]").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_OnDelArt(this);
//        $(".jb-unq-cfrm-bx-mx").addClass("this_hide"); //[DEPUIS 07-08-15] @BOR
//        _f_OnDelArt();
    });
    
    $(".jb-unq-del-sbchcs[data-action=del_confirm]").off().click(function(e){
        Kxlib_PreventDefault(e);

        _f_OnDelArt(this);
//        _f_OnDelArt();
    });
    
    $(".jb-unq-pmlk-choices:not([data-action=goto]), .jb-u-a-a-choices[data-action=perma], .jb-unq-prmlk-sprt").click(function(e){
        Kxlib_PreventDefault(e);
        
        var x = this;
        if ( $(this).is(".jb-unq-prmlk-sprt") ) {
            x = $("<a/>").data("action","close");
        }
        _f_PrmlkAct(x);
    });
    
    $(".jb-unq-nav-btn").off().click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        _f_NavAction(this);
    });
    
    $(".jb-unq-ctr").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        _f_OnClose();
    });
    
    $(".jb-unq-tv-lnch-vid").off("click").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        _f_VidAction(this);
    });
    
    
    /*********************************************************************************************************************************************************/
    /*********************************************************************** INIT SCOPE **********************************************************************/
    /*********************************************************************************************************************************************************/
    
//    _f_Init(true); //DEV, TEST, DEBUG 
}

new Unique();
