
function FKSA () {
    
    /*
     * 
     * FONCTIONNALITES
     *  Version : b.1507.1.1 (Juillet 2015 VBeta1) [DATE UPDATE : 31-05-15]
     *      [ARTICLE]
     *      -> Afficher l'Article (IML et ITR) ainsi que les données annexes (Nombre commentaires, nombre Eval, description, etc ...)
     *      -> Récupérer/afficher des échantillons de publications en fonction du type de l'Article affiché
     *      -> Afficher une barre pour CNX/SGN directement visible vers les échantillons lorsqu'on n'est pas connecté
     *      -> Aller vers la Tendance mère de l'Article
     *      [REACTIONS]
     *      -> Récupérer/afficher les Commentaires pour un Article en bas de page
     *      [EVAL]
     *      -> Récupérer/afficher les Evals pour l'Article en bas de page
     *  
     *  EVOLUTIONS ATTENDUES
     *      -> Ajouter un commentaire
     *      -> Les Articles affichés suivent un ordre. On apercoit les 4 articles suivants et précédents
     *  
     *  EVOLUTIONS POSSIBLES
     *      -> ...
     */
    
    var _xhr_plrs;
    var _xhr_ples;
    var _xhr_adrc;
    var _xhr_dlrc;
    var _xhr_evax;
    
    /***************************************************************************************************************************************************************/
    /************************************************************************ PROCESS SCOPE ************************************************************************/
    /***************************************************************************************************************************************************************/
    
    var _f_Gdf = function () {
        var ds = {
            "rstsprt_state"  : [0,1,2,3],
            "armx"           : 1000
        };
        return ds;
    };
    
    var _f_Action = function(x,a){
        try {
//            if ( KgbLib_CheckNullity(x) | !$(x).data("action") ) {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && !a ) ) {
                return;
            }
            
//            var a = $(x).data("action");
            var _a = ( KgbLib_CheckNullity($(x).data("action")) ) ? a : $(x).data("action"); 
        
            /*
             * On descend vers la zone si on est pas dans le cas où on ne veut que "fermer" un menu.
             * 
             * [DEPUIS 22-11-15] @author BOR
             *      On n'effectue pas cette action s'il s'agit d'une action de type SHARON
             */
            if ( $.inArray(_a,["fksa_sharon_fb","fksa_sharon_twr","fksa_add_r","fksa-del-r-start","fksa-del-r-fnl-y","fksa-del-r-fnl-n","fksa-vid-play","fksa-vid-pause","tst-open-in-vwr"]) === -1 && !$(x).hasClass("selected") ) {
                _f_GotoBtm();
            } 
            
            switch (_a) {
                case "fksa_evaluations" :
                        _f_PullEs(x);
                    break;
                case "fksa_evaluations_charts" :
                        _f_OpnEsCharts(x);
                    break;
                case "fksa_reactions" :
                        _f_PullRs(x);
                    break;
                case "fksa_add_r" :
                        _f_AddRct(x);
                    break;
                case "fksa-del-r-start" :
                case "fksa-del-r-fnl-y" :
                case "fksa-del-r-fnl-n" :
                        _f_DelRct(x,_a);
                    break;
                case "fksa_sharon_fb" :
                        _f_Sharon(x,_a);
                    break;
                case "fksa_sharon_twr" :
                        _f_Sharon(x,_a);
                    break;
                case "fksa-vid-play" :
                case "fksa-vid-pause" :
                        _f_VidAct(x,_a);
                    break;
                case "tst-open-in-vwr" :
                        _f_OpenVwr(x,_a);
                    break;
                case "eval-supacool" :
                case "eval-cool" :
                case "eval-dislike" :
                        _f_ArtEval(x,_a);
                    break;
                default :
                    return;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*************** ONLOAD *******************/
    
    var _f_OnLoad = function (sec) {
         try {
             if ( KgbLib_CheckNullity(sec) ) {
                 return;
             }
             
             switch (sec) {
                 case "_sec_testy" :
                         _f_OnLoad_Testy(sec);
                     break;
                 case "_sec_article" :
                        _f_OnLoad_Article(sec);
                     break;
                 default: 
                     return;
             }
             
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_OnLoad_Testy = function (sec) {
        try {
            
            if ( KgbLib_CheckNullity(sec) ) {
                return;
            }
            
            /*
             * ETAPE :
             *      On récupère la référence vers TESTY_DOM
             */
            var tdm = $(".jb-tqr-fksa-tst-art-bmx");
            if (! $(tdm).length ) {
                return;
            }
            
            /*
             * [NOTE 18-05-16]
             *      Le code ci-dessous n'est pas sur à 100%
             */
            var ajca_o, ajca = ""+$(tdm).data("ajcache")+"";
            ajca_o = ( typeof $(tdm).data("ajcache") === "object" ) ? $(tdm).data("ajcache") : JSON.parse(ajca);  
                
            var ustgs = ajca_o.ustgs;
            var hashs = ajca_o.hashs;

//            Kxlib_DebugVars([ajca_o.cmnid,"; HASH =>",hashs,"; USTGS =>",ustgs],true);
//            return;
            
            /*
             * ETAPE :
             *      On ajoute les données
             */
            tdm = _f_Tst_AdDatas(tdm,ajca_o,ustgs,hashs);
            
            /*
             * ETAE :
             *      On bind les éléments
             */
            tdm = _f_Tst_RbdMdl(tdm);
            
            $(tdm).stop(true,true).hide().removeClass("this_hide").fadeIn();
            
            
            /********************* RECUPERATION DES SAMPLES *********************/
            
           var s = $("<span/>");
           
           //On récupère l'identifiant
           var i = $(".jb-tqr-fksa-tst-art-bmx").data("item");
           
           _f_Srv_PlSmpl(i,sec,s);
           
           $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                Kxlib_DebugVars([JSON.stringify(d)],true);
//                return;
               
                _f_DsplASmpl_tstver(d.ds);
               
           });
   
           $(s).on("operended",function(e){
           });
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Tst_AdDatas = function (tdm,ds,ustgs,hashs) {
        try {
            
            if ( KgbLib_CheckNullity(tdm) ) {
                return;
            }
            
            $(tdm).data({
                "item" : ds.i,
                "time" : ds.tm
            });
            $(tdm).attr({
                "data-item" : ds.i,
                "data-time" : ds.tm
            });
            
            /*
             * Données sur l'image de profil de OWNER
             */
            $(tdm).find(".jb-tqr-fksa-tst-o-a").attr({
                href : "/"+ds.au.opsd
            });
            $(tdm).find(".jb-tqr-fksa-tst-o-i").attr({
                "src" : ds.au.oppic,
                "alt" : ds.au.ofn.concat(" (@").concat(ds.au.opsd).concat(")")
            });
                
            /*
             * Données sur OWNER
             */
            $(tdm).find(".jb-tqr-fksa-tst-o-psd").attr({
                href : "/"+ds.au.opsd
            }).text("@".concat(ds.au.opsd));
            $(tdm).find(".jb-tqr-fksa-tst-o-fn").text(ds.au.ofn);
                
            /*
             * Données sur TARGET s'il existe et est différent de OWNER
             */
            if ( ds.au.oid !== ds.tg.oid ) {
                 $(tdm).find(".jb-tqr-fksa-tst-tgt-a").attr({
                    href : "/"+ds.tg.opsd
                }).text("@".concat(ds.tg.opsd));
            } else {
                $(tdm).find(".jb-tqr-fksa-tst-tgt-a").remove();
            }
            
            
            var atxt = ds.m;
            atxt = $("<div/>").html(atxt).text();

            //rtxt = RenderedText
            var rtxt = Kxlib_TextEmpow(atxt,ustgs,hashs,null,{
                "ena_inner_link" : {
//                    "local" : true, //DEV, DEBUG, TEST
                    "all"   : false,
                    "only"  : "fksa"
                },
                emoji : {
                    "size"          : 36,
                    "size_css"      : 22,
                    "position_y"    : 3
                }
            });
            
            //On ajoute le texte
            $(tdm).find(".jb-tqr-fksa-tst-m").text("").append(rtxt);
            
            /*
             * Données sur les XTRAS_DATAS (rnb, lnb, ...)
             */
            $(".jb-tqr-fksa-tst-xds-tsl").text(ds.cnlk);
            $(".jb-tqr-fksa-tst-xds-tsr").text(ds.rnb);
            
            return tdm;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Tst_RbdMdl = function (am) {
         try {
             if ( KgbLib_CheckNullity(am) ) {
                return;
            }
            
            $(am).click(function(e){
                Kxlib_PreventDefault(e);
                
                _f_Action(this,"tst-open-in-vwr");
            });
            
//            $(am).find("a").click(function(e){
            $(am).children().click(function(e){
                if ( $(e.target).is("a") | $(e.target).parent().is("a") ) {
                    Kxlib_StopPropagation(e);
                }
            });

            $(am).hover(function(e){
                var bmx = ( $(this).is(".jb-tqr-fksa-tst-art-bmx") ) ? $(this) : $(x).closest(".jb-tqr-fksa-tst-art-bmx");

                $(bmx).addClass("cstm-hvr");
            },function(){
                var bmx = ( $(this).is(".jb-tqr-fksa-tst-art-bmx") ) ? $(this) : $(x).closest(".jb-tqr-fksa-tst-art-bmx");

                $(bmx).removeClass("cstm-hvr");
            });
            
            return am;
            
         } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_OpenVwr = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            if ( require.specified("r/csam/tkbvwr.csam") ) {
//                Kxlib_DebugVars(["ASDRBNR : Déjà chargé !",_VWR]);
                _VWR.open({
                    model   : "AJCA-FKSA",
                    trigger : x,
                    action  : a
                });
            } else {
                require(["r/csam/tkbvwr.csam"],function(TbkVwr){
                    _VWR = new TbkVwr();
                    _VWR.open({
                        model   : "AJCA-FKSA",
                        trigger : x,
                        action  : a
                    });
                });
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /****************************** ARTICLE SCOPE ********************************/
    
    var _f_OnLoad_Article = function (sec) {
         try {
             
            if ( KgbLib_CheckNullity(sec) ) {
                return;
            }
            
           /*
            * [DEPUIS 19-05-16]
            */
            if ( $(".jb-fksa-art-ctr-vid-mx").length ) {
                _f_Vid_Fit();
                
                $(".jb-fksa-art-ctr-vid-mx").hide().removeClass("this_invi").fadeIn();
            }
             
            if ( $(".jb-fksa-art-desc").length ) {
                var e = $(".jb-fksa-article");
                /*
                 * [DEPUIS 18-04-16]
                 */
                var ajustgs_o = $(".jb-fksa-article ").data("ajustgs"); 
                var ajhashs_o = $(".jb-fksa-article ").data("ajhashs"); 
//                Kxlib_DebugVars([JSON.stringify(ajustgs_s), typeof ajustgs_s, JSON.stringify(ajhashs_s), typeof ajhashs_s],true);
//                return;
//                t__ = Kxlib_Decode_After_Encode(t__);
                var t__ =  $(e).find(".jb-fksa-art-desc").text();
                var rtxt =  Kxlib_TextEmpow(t__,ajustgs_o.ustgs,ajhashs_o.hashs,null,{
                    emoji : {
                        "size"          : 36,
                        "size_css"      : 18,
                        "position_y"    : 3
                    },
                    wrap_text : true
                });
                $(e).find(".jb-fksa-art-desc").text("").append(rtxt);
                $(e).find(".jb-fksa-art-desc").hide().removeClass("this_invi").fadeIn();
                
                /* //[DEPUIS 18-04-16]
                if ( $(e) && !KgbLib_CheckNullity($(e).data("with")) && typeof $(e).data("with") === "string" ) {
                    var w__ = $(e).data("with");
                    //On récupère les éléments
                    w__ = Kxlib_DataCacheToArray(w__)[0];
                    if ( w__ && $.isArray(w__) && w__.length ) {

                        var ps__ = ( $.isArray(w__[0]) ) ? Kxlib_GetColumn(3,w__): [w__[3]];
//                            Kxlib_DebugVars([200,JSON.stringify(w__),JSON.stringify(ps__)], true);
                        var t__ = Kxlib_UsertagFactory($(e).find(".jb-fksa-art-desc").text(),ps__,"tqr-unq-user");
                        var $tp__ = $("<div/>").text(t__);
                        t__ = $tp__.text();

//                            t__ = Kxlib_Decode_After_Encode(t__);
//                            Kxlib_DebugVars([206,JSON.stringify(t__)], true);
                        t__ = Kxlib_SplitByUsertags(t__);

                        //Mettre en place la description
                        $(e).find(".jb-fksa-art-desc").html(t__);
                        //On affiche le texte
                        $(e).find(".jb-fksa-art-desc").hide().removeClass("this_invi").fadeIn();
                    }
                } else {
                    //On affiche le texte
                    $(e).find(".jb-fksa-art-desc").hide().removeClass("this_invi").fadeIn();
                }
                //*/
                
            } 
           
            
           /*
            * [NOTE 30-05-15] @BOR
            * On récupère les échantillons d'images 
            */
           var s = $("<span/>");
           
           //On récupère l'identifiant
           var i = $(".jb-fksa-article").data("item");
           
           _f_Srv_PlSmpl(i,sec,s);
           
           $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
               
                _f_DsplASmpl(d.ds);
               
               /*
                * [DEPUIS 15-12-15]
                *   On affiche le leurre-bouton "Voir Plus"
                */
                if ( d.cz === "wu" ) {
                    $(".jb-fksa-art-s-mr-mx").removeClass("this_hide");
                }
           });
   
           $(s).on("operended",function(e){
               //On masque la zone spinner
               $(".jb-fksa-a-s-mx-spn-mx").addClass("this_hide");
               
               //On affiche le leurre-bouton "Voir Plus"
               $(".jb-fksa-art-s-mr-mx").removeClass("this_hide");
           });
           
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber], true);
        }
     };
     
     
    var _f_Vid_Fit = function () {
        try {
            var wprw = $(".jb-fksa-art-ctr-vid-wpr").width(), wprh = $(".jb-fksa-art-ctr-vid-wpr").height();
            var vdw = $(".jb-fksa-art-ctr-vid").width(), vdh = $(".jb-fksa-art-ctr-vid").height();
            
            var ref = ( vdw >= vdh ) ? vdw : vdh;
            if ( ref === wprw ) {
                return true;
            }
            
            var ratio = ( vdw >= vdh ) ? vdw/wprw : vdh/wprw;
            
            var w = vdw/ratio;
            var h = vdh/ratio;
            
            $(".jb-fksa-art-ctr-vid").attr({
                width : w,
                height : h
            });
            
            return true;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber], true);
        }
    };
     
    /*************** REACTIONS ****************/
    
    var _f_PullRs = function(x){
        /*
         * Permet de récupérer les Commentaires au près du serveur et de les afficher.
         * La méthode ne permet d'afficher qu'un nombre limité de commentaires.
         * 
         * La méthode gère le cas du menu actif.
         */
        try {
            
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            //On vérifie qu'on a bien l'identifiant externe de l'Article
            if ( !$(".jb-fksa-article").length | !$(".jb-fksa-article").data("item") | !$(".jb-fksa-art-rct-mx").length ) {
                return;
            }
            
//            Kxlib_DebugVars([f_FtrMod_GetState("fksa_reactions")]);
            
            //On s'assure que la zone n'est pas déjà active
            if ( _f_FtrMod_GetState("fksa_reactions") === 1 ) {
                return;
            } else if ( _f_FtrMod_GetState("fksa_reactions") === 2 ) {
                _f_RstInitMode(x);
                
                /* //[DEPUIS 20-06-16]
                _f_VwShwSprt("fksa_reactions");
                _f_RstSprt("fksa_reactions");
                //On désactive le menu
                _f_VwMenuActiv(x);
                
                _f_Sprt_SetState("fksa_reactions",0);
                //*/
                
                return;
            }
            
            //On active le menu
            _f_VwMenuActiv(x);
            
            //On change l'etat de l'autre zone sinon, on ne pourra pas revenir dessus
            _f_Sprt_SetState("fksa_evaluations",0);
            //On efface les anciennes données
            _f_RstSprt("fksa_evaluations");
            
            //On affiche la zone qui va contenir les commentaires
            _f_VwShwSprt("fksa_reactions",true);
            //On affiche le spinner
            _f_VwShwSpnr("fksa_reactions",true);
            
            //On récupère l'identifiant
            var i = $(".jb-fksa-article").data("item");
            
            //On contacte le serveur
            var s = $("<span/>");
            
            _f_Srv_PlRs(i,s);
            
            //On change le statut de la zone pour signifier que la zone est en attente de commentaires
            _f_Sprt_SetState("fksa_reactions",1);
            
            //On affiche le nombre de commentaires au niveau du header
            if ( $(".jb-fksa-article").data("rnb") ) {
                $(".jb-fksa-art-rnb").text($(".jb-fksa-article").data("rnb"));
            }
            
            $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                //On retire NONE
                _f_VwOnVoid("fksa_reactions");
                
                //On liste les Commentaires
                _f_ListRs(d);
                
                //On change le statut de la zone pour signifier que la zone est chargée
                _f_Sprt_SetState("fksa_reactions",2);
                
                _xhr_plrs = null;
            });
            
            $(s).on("operended",function(e,d){
                /*
                 * Dans le cas où il n'y a pas de commentaires.
                 * Le but étant d'afficher un message indiquant qu'il n'y a pas de commentaires.
                 * 
                 * RAPPEl : 
                 *  Même si à son affichage, l'Article n'admet pas de commentaires, on a autorisé l'action pour permettre de faire une mise à jour des données.
                 */
                //On change le statut de la zone pour signifier que la zone est chargée
                _f_Sprt_SetState("fksa_reactions",2);
                
                _f_VwOnVoid("fksa_reactions",true);
                
                //On masque le spinner
                _f_VwShwSpnr("fksa_reactions");
                
                //On change la valeur du nombre de commentaires
                $(".jb-fksa-art-rnb").text(0);
                
                _xhr_plrs = null;
                
//                alert("Pas de Commentaires, n'oublies pas la page FKSA_GTPG !!!");
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_AddRct = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            if ( !KgbLib_CheckNullity(_xhr_adrc) | !KgbLib_CheckNullity(_xhr_plrs) ) {
                $(x).data("lk",0);
                return;
            }
            
            var $armx = $(".jb-fksa-article"), $txar = $(".jb-fksa-art-nw-rct-b-t-txta");
            var vl = $txar.val();
            if ( KgbLib_CheckNullity(vl) ) {
                return;
            }
            
            /*
             * ETAPE 
             *      On vérifie que le texte ne dépasse pas la limite MAX
             *      On affiche pas de message d'erreur car cela n'est possible si l'utilisateur a retiré l'attribut MAX.
             */
            if ( vl.length > vl.armx ) {
                return;
            }
            
            var prm = {
                ai : $armx.data("item"),
                at : $armx.data("time"),
                rm : vl,
                lri : $(".jb-fksa-art-r-mdl:last").length ? $(".jb-fksa-art-r-mdl:last").data("item") :"",
                lrt : $(".jb-fksa-art-r-mdl:last").length ? $(".jb-fksa-art-r-mdl:last").data("time") :"",
            };
            
//            Kxlib_DebugVars(["MESAGE => "+vl],true);
//            Kxlib_DebugVars(["PRM => "+JSON.stringify(prm)],true);
//            return;
            
            var s = $("<span/>"), xt = (new Date()).getTime();
            
            _f_Srv_AdRct(prm.ai,prm.at,prm.rm,prm.lri,prm.lrt,xt,x,s);
            
            $txar.val("");
            
            $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                Kxlib_DebugVars([JSON.stringify(d)],true);
                
                //On retire NONE
                _f_VwOnVoid("fksa_reactions");
                
                //On affiche le ou les COMMENTS
                _f_ListRs(d);
                
                _xhr_adrc = null;
                $(x).data("lk",0);
                
                //On notifie que l'article a été ajouté avec succès
                var Nty = new Notifyzing ();
                Nty.SignalForNewReaction(1);
            });
            
            $(s).on("operended",function(){
                $(x).data("lk",0);
            });

        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_DelRct  = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
          
            $rmx = $(x).closest(".jb-fksa-art-r-mdl");
            if ( !$rmx.length ) {
                return;
            }
            
            switch (a) {
                case "fksa-del-r-start" :
                        $rmx.find(".jb-fksa-a-r-m-f-dl-opt").addClass("this_hide");
                        $rmx.find(".jb-fksa-a-r-m-f-dl-o-fnl-mx").removeClass("this_hide");
                        return;
                    break;
                case "fksa-del-r-fnl-n" :
                        $rmx.find(".jb-fksa-a-r-m-f-dl-o-fnl-mx").addClass("this_hide");
                        $rmx.find(".jb-fksa-a-r-m-f-dl-opt").removeClass("this_hide");
                        return;
                    break;
                case "fksa-del-r-fnl-y" :
//                        alert("Lancer la suppression !");
                    break;
                default:
                    return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            if ( !KgbLib_CheckNullity(_xhr_dlrc) ) {
                $(x).data("lk",0);
                return;
            }
            
            
            var $armx = $(".jb-fksa-article");
            if ( !$armx.length ) {
                $(x).data("lk",0);
                return;
            }
            
            var prm = {
                ai : $armx.data("item"),
                at : $armx.data("time"),
                ri : $rmx .data("item"),
                rt : $rmx .data("time"),
            };
            
//            Kxlib_DebugVars(["PRM => "+JSON.stringify(prm)],true);
//            return;
            
            var s = $("<span/>"), xt = (new Date()).getTime();
            
            _f_Srv_DlRct(prm.ai,prm.at,prm.ri,prm.rt,xt,x,s);
            
            $rmx.addClass("this_hide");
            
            $(s).on("operended",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                Kxlib_DebugVars([JSON.stringify(d)],true);
                
                $rmx.remove();
                
               /*
                * ETAPE
                *       Mise à jour des données
                */
               //Entête
               $(".jb-fksa-article").data("rnb",d.arnb);
               //Aside
               $(".jb-fksa-a-mn-choice[data-action='fksa_reactions']").find(".jb-fksa-a-mn-ch-nb").text(d.arnb);
               //Liste
               $(".jb-fksa-art-rnb").text(d.arnb);
               
               //On ajoute NONE le cas échant
               if ( parseInt(d.arnb) === 0 ) {
                   _f_VwOnVoid("fksa_reactions",true);
               }
                
                _xhr_dlrc = null;
                $(x).data("lk",0);
                
                //On notifie que l'article a été supprimée avec succès
                var Nty = new Notifyzing ();
                Nty.FromUserAction("ua_del_react");
            });
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ListRs = function (d) {
        try {
            if ( KgbLib_CheckNullity(d) | !( d.hasOwnProperty("ards") && !KgbLib_CheckNullity(d.ards) ) | !( d.hasOwnProperty("arnb") && !KgbLib_CheckNullity(d.arnb) ) ) {
                return;
            }
            
            //On masque le spinner
            _f_VwShwSpnr("fksa_reactions");
            
            /* //[DEPUIS 29-07-15] @BOR
            //On affiche (update) le nombre de commentaires au niveau du header
            $(".jb-fksa-art-rnb").text(d.length);
            //*/
            
            //On reverse le tableau pour faire apparaitre les commetaires par ordre croissant
            var ards = d.ards.reverse();
            
            //On affiche le commentaire
            $.each(ards,function(x,r){
                //On vérifie si le Commentaire n'est pas déjà présent dans la liste
                if ( $(".jb-fksa-art-r-mdl[data-item='"+r.rid+"']").length ) {
                    return;
                }
                
                //On Prepare le modèle 
                var m = _f_PprRctMdl(r);
                
                //Rebind 
                m = _f_RbdRctMdl(m);
                
                //On ajoute dans la liste
                $(m).hide().appendTo(".jb-fksa-a-r-m-list-list").fadeIn();
            });
            
            /*
             * [29-07-15] @BOR
             * Mise à jour des données
             */
            //Entête
            $(".jb-fksa-article").data("rnb",d.arnb);
            //Aside
            $(".jb-fksa-a-mn-choice[data-action='fksa_reactions']").find(".jb-fksa-a-mn-ch-nb").text(d.arnb);
            //Liste
            $(".jb-fksa-art-rnb").text(d.arnb);
            
            return true;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*************** EVALUATIONS ****************/
    
    var _f_ArtEval = function(x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(".jb-fksa-a-ev-m-evbx-ax").data("lk",1);
            
//            var me = ( $(x).data("state") && $(x).data("state") === "me" ) ? true : false;
            
            var ec; 
            switch (a) {
                case "eval-supacool" :
                        ec = "_EVAL_SPCL";
                    break;
                case "eval-cool" :
                        ec = "_EVAL_CL";
                    break;
                case "eval-dislike" :
                        ec = "_EVAL_DLK";
                    break;
                default :
                    return;
            }
            
           /* 
            * ec => Eval_Code : le code qui sera reçu par le serveur
            * i => id de l'article qui sera identifiable grace à son type ou à la page 
            * t => Le type d'Article 'iml' ou 'itr'
            * p => Le code de la page 
            * * */
            var prms = {
                "ec"    : ec,
                "t"     : ( $(".jb-fksa-art-hdr").hasClass("no-hdr") ) ? "iml" : "itr",
                "i"     : $(".jb-fksa-article").data("item"),
                "p"     : "fksa",
            };
            
//            Kxlib_DebugVars([JSON.stringify(prms)],true);
//            return;
            
            var s = $("<span/>");
            
            _f_Srv_EvalAx(ec, prms.i, prms.t, prms.p, x, s);
            
            /*
             * ETAPE :
             *      Afficher la barre de chargement
             */ 
            $(".jb-fksa-a-ev-m-evalbx").addClass("eval-wt-bar-pgrs");
            $(".jb-fksa-a-ev-m-evbx-ax").addClass("this_hide");
    
            $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                Kxlib_DebugVars([JSON.stringify(d)],true);
//                return;
                
                /*
                 * ETAPE :
                 *      On change l'état du bouton qui a déclenché l'ACTION d'EVAL
                 */
                if ( !KgbLib_CheckNullity(d.me) ) {
                    $(".jb-fksa-a-ev-m-evbx-ax").data({
                        "state"         : "",
                        "sty-state"     : ""
                    }).attr({
                        "data-state"        : "",
                        "data-sty-state"    : ""
                    });
                    
                    $(x).data({
                        "state"         : "me",
                        "sty-state"     : "me"
                    }).attr({
                        "data-state"        : "me",
                        "data-sty-state"    : "me"
                    });
                } else {
                    $(x).data({
                        "state"         : "",
                        "sty-state"     : ""
                    }).attr({
                        "data-state"        : "",
                        "data-sty-state"    : ""
                    });
                }
                
                /*
                 * ETAPE :
                 *      On met à jour les données sur les EVAL indivivuels
                 */
                $(".jb-fksa-a-ev-m-evbx-ax[data-action='eval-supacool']").find(".jb-fksa-a-ev-m-evbx-ax-nb").text("(".concat(d.eval[0],")"));
                $(".jb-fksa-a-ev-m-evbx-ax[data-action='eval-cool']").find(".jb-fksa-a-ev-m-evbx-ax-nb").text("(".concat(d.eval[1],")"));
                $(".jb-fksa-a-ev-m-evbx-ax[data-action='eval-dislike']").find(".jb-fksa-a-ev-m-evbx-ax-nb").text("(".concat(d.eval[2],")"));
                
                /*
                 * ETAPE :
                 *      On met à jour les données sur l'EVAL total
                 */
                $(".jb-fksa-a-mn-choice[data-action='fksa_evaluations']").find(".jb-fksa-a-mn-ch-nb").text(d.eval[3]);
                var nb = d.eval[0]+d.eval[1]+d.eval[2];
                $(".jb-fksa-art-evnb").text(nb);
                
                /*
                 * ETAPE :
                 *      Masquer la barre de chargement
                 */ 
                $(".jb-fksa-a-ev-m-evalbx").removeClass("eval-wt-bar-pgrs");
                $(".jb-fksa-a-ev-m-evbx-ax").removeClass("this_hide");
                
                $(".jb-fksa-a-ev-m-evbx-ax").data("lk",0);
                _xhr_evax = null;
            });
            
            $(s).on("operended",function(){
                $(".jb-fksa-a-ev-m-evbx-ax").data("lk",0);
                _xhr_evax = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    }
    
    
    var _f_PullEs = function(x){
        /*
         * Permet de récupérer les Evaluations au près du serveur et de les afficher.
         * La méthode ne permet d'afficher qu'un nombre limité d'Evaluations.
         * 
         * La méthode gère le cas du menu actif.
         */
        try {
            
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            //On vérifie qu'on a bien l'identifiant externe de l'Article
            if ( !$(".jb-fksa-article").length | !$(".jb-fksa-article").data("item") | !$(".jb-fksa-art-evl-mx").length ) {
                return;
            }
            
//            Kxlib_DebugVars([f_FtrMod_GetState("fksa_reactions")]);
            
            //On s'assure que la zone n'est pas déjà active
            if ( _f_FtrMod_GetState("fksa_evaluations") === 1 ) {
                return;
            } else if ( _f_FtrMod_GetState("fksa_evaluations") === 2 ) {
                _f_RstInitMode(x);
                
                /* //[DEPUIS 20-06-16]
                _f_VwShwSprt("fksa_evaluations");
                _f_RstSprt("fksa_evaluations");
                //On désactive le menu
                _f_VwMenuActiv(x);
                
                _f_Sprt_SetState("fksa_evaluations",0);
                
                /*
                 * [DEPUIS 29-07-15] @BOR
                 *      On ferme la fenêtre des Evaluations détaillées
                 *
                $(".jb-fksa-a-ev-m-lst-chrts-mx").addClass("this_hide");
                //*/
                
                return;
            }
            
            //On activer/desactiver le menu
            _f_VwMenuActiv(x);
            
            //On change l'etat de l'autre zone sinon, on ne pourra pas revenir dessus
            _f_Sprt_SetState("fksa_reactions",0);
            //On efface les anciennes données
            _f_RstSprt("fksa_reactions",true);
            
            //On affiche la zone qui va contenir les Evaluations
            _f_VwShwSprt("fksa_evaluations",true);
            //On affiche le spinner
            _f_VwShwSpnr("fksa_evaluations",true);
            
            //On récupère l'identifiant
            var i = $(".jb-fksa-article").data("item");
            
            //On contacte le serveur
            var s = $("<span/>");
            
            _f_Srv_PlEs(i,s);
            
            //On change le statut de la zone pour signifier que la zone est en attente de commentaires
            _f_Sprt_SetState("fksa_evaluations",1);
            
            //On affiche le nombre de commentaires au niveau du header
            if ( $(".jb-fksa-article").data("enb") ) {
                $(".jb-fksa-art-evnb").text($(".jb-fksa-article").data("enb"));
            }
            
            $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                //On liste les Evaluations
                _f_ListEs(d);
                
                //On change le statut de la zone pour signifier que la zone est chargée
                _f_Sprt_SetState("fksa_evaluations",2);
                
                _xhr_ples = null;
            });
            
            $(s).on("operended",function(e,d){
                /*
                 * Dans le cas où il n'y a pas d'Evaluations.
                 * Le but étant d'afficher un message indiquant qu'il n'y a pas d'Evaluations.
                 * 
                 * RAPPEl : 
                 *  Même si à son affichage, l'Article n'admet pas d'Evaluations, on a autorisé l'action pour permettre de faire une mise à jour des données.
                 */
                //On change le statut de la zone pour signifier que la zone est chargée
                _f_Sprt_SetState("fksa_evaluations",2);
                
                _f_VwOnVoid("fksa_evaluations",true);
                
                //On masque le spinner
                _f_VwShwSpnr("fksa_evaluations");
                
                //On change la valeur du nombre d'Evaluations
                $(".jb-fksa-art-evnb").text(0);
                
                _xhr_ples = null;
                
//                alert("Pas d'Evaluations, n'oublies pas la page FKSA_GTPG !!!");
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    
    var _f_ListEs = function (d) {
        try {
            if ( KgbLib_CheckNullity(d) | !( d.hasOwnProperty("evdslst") && !KgbLib_CheckNullity(d.evdslst) ) | !( d.hasOwnProperty("evnb") && !KgbLib_CheckNullity(d.evnb) ) | !( d.hasOwnProperty("evvl") && !KgbLib_CheckNullity(d.evnb) ) ) {
                return;
            }
            
            /*
             * [DEPUIS 20-06-16]
             */
            _f_RstSprt("fksa_evaluations",true);
            
            //On masque le spinner
            _f_VwShwSpnr("fksa_evaluations");
            
            //On affiche (update) le nombre d'Evaluations au niveau du header
            $(".jb-fksa-art-evnb").text(d.length);
            
            //On reverse le tableau pour faire apparaitre les commetaires par ordre croissant
            var evdslst = d.evdslst.reverse();
            
            //On affiche l'Evaluation
            $.each(evdslst, function(x,ev) {
                //On vérifie si l'Evaluation n'est pas déjà présent dans la liste
                if ( $(".jb-fksa-art-eval-mdl[data-item='" + ev.evoid + "']").length ) {
                    return;
                }
                
                //On Prépare le modèle 
                var ml = _f_PprEvlMdl(ev);
                if ( KgbLib_CheckNullity(ml) ) {
                    return;
                }
                
                //TODO : Rebind 
                
                //On ajoute dans la liste
                $(".jb-fksa-a-ev-m-list-list").append(ml);
            });
            
            /*
             * [DEPUIS 28-07-15] @BOR
             * Mise à jour des données 
             */
            //Entête
            $(".jb-fksa-article").data("enb",d.evnb);
            $(".jb-fksa-article").data("evl",d.evvl);
            //Aside
            $(".jb-fksa-a-mn-choice[data-action='fksa_evaluations']").find(".jb-fksa-a-mn-ch-nb").text(d.evvl);
            //Liste
            $(".jb-fksa-art-evnb").text(d.evnb);
            //Détails
            $(".jb-fksa-a-ev-m-lst-chrts-evlmnt[data-scp='spcl']").find(".jb-fksa-a-ev-m-lst-chrts-evlmnt-nb").text(d.evds[0]);
            $(".jb-fksa-a-ev-m-lst-chrts-evlmnt[data-scp='cl']").find(".jb-fksa-a-ev-m-lst-chrts-evlmnt-nb").text(d.evds[1]);
            $(".jb-fksa-a-ev-m-lst-chrts-evlmnt[data-scp='dslk']").find(".jb-fksa-a-ev-m-lst-chrts-evlmnt-nb").text(d.evds[2]);
            
            
            return true;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_OpnEsCharts = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(".jb-fksa-a-ev-m-lst-chrts-mx").hasClass("this_hide") ) {
                $(".jb-fksa-a-ev-m-lst-chrts-mx").removeClass("this_hide");
            } else {
                $(".jb-fksa-a-ev-m-lst-chrts-mx").addClass("this_hide");
                return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*************** EXTRAS DATAS ****************/
    
    var _f_FtrMod_GetState = function(scp) {
        /*
         * Permet de récupérer l'état de la zone qui affiche les données dans le module placé dans la zone Footer.
         * 
         * STATE :
         *  - sleep(0)   : La zone est fermée
         *  - pending(1) : La zone est en attente des commentaires
         *  - loaded(2)  : La zone contient les commentaires qui ont été chargés
         *  - kill(3)    : La zone est en instance de fermeture
         *  
         *  NOTE :
         *  Cette méthode aussi et surtout à se souvenir des codes liés
         */
        try {
            if ( KgbLib_CheckNullity(scp) ) {
                return;
            }
        
            var $b;
            switch (scp) {
                case "fksa_evaluations" :
                        $b = $(".jb-fksa-art-evl-mx");
                    break;
                case "fksa_reactions" :
                        $b = $(".jb-fksa-art-rct-mx");
                    break;
                default: 
                    return;
            }
            
            if (! $b.length ) {
                return;
            }
            
            return (KgbLib_CheckNullity($b.data("state"))) ? 0 : $b.data("state");
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    
    var _f_Sprt_SetState = function(scp,s) {
        if ( KgbLib_CheckNullity(scp) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        //On s'assure que les codes correspondent
        if ( _f_Gdf().rstsprt_state.indexOf(s) === -1 ) {
            return;
        };
        
        var $b;
        switch (scp) {
            case "fksa_evaluations" :
                    $b = $(".jb-fksa-art-evl-mx");
                break;
            case "fksa_reactions" :
                    $b = $(".jb-fksa-art-rct-mx");
                break;
            default :
                return;
        }
        
        $b.data("state",s);
    };
    
    var _f_RstInitMode = function (x) {
        try {
            _f_VwShwSprt("fksa_evaluations",true,false);
            _f_RstSprt("fksa_evaluations");
            $(".jb-fksa-a-ev-m-list-mx").addClass("this_hide");
            //On désactive le menu
            _f_VwMenuActiv(x);

            _f_Sprt_SetState("fksa_evaluations",0);
            _f_Sprt_SetState("fksa_reactions",0);
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    }
    
    var _f_RstSprt = function(scp,rznb) {
        try {
            /*
             * Permet d'effectuer les opérations de reset de la fenêtre qui liste les commentaires.
             */
            if ( KgbLib_CheckNullity(scp) ) {
                return;
            }

            var $b;
            switch (scp) {
                case "fksa_evaluations" :
                        $b = $(".jb-fksa-art-evl-mx");
                    break;
                case "fksa_reactions" :
                        $b = $(".jb-fksa-art-rct-mx");
                    break;
                default: 
                    return;
            }

            if ( !$b.find(".jb-fksa-a-elt-m-list-list").length | !$b.find(".jb-fksa-elt-exdnb-mx").length ) {
                return;
            }

            //On retire la zone NoOne
            $b.find(".jb-fksa-a-elt-noone-mx").remove();
            //On efface les Commentaires
            $b.find(".jb-fksa-art-elt-mdl").remove();
            
           /*
            * [DEPUIS 20-06-16]
            *       CALLER doit spécifier s'il veut réinitialiser le nombre
            */
            if ( rznb === true ) {
                //On réinitialise le nombre de Commentaires/Evaluations
                $b.find(".jb-fksa-elt-nb").text("0");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Sharon = function (x,a){
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
               return;
            }
            $(x).data("lk",1);
            
            switch(a) {
                case "fksa_sharon_fb" :
                        FB.ui({
                              method    : 'share',
                              href      : document.URL
                        },function(r) {
                            if (r && !r.error_message) {
                                $(x).data("lk",0);
                            } else {
//                              alert("Erreur: l'opération a échoué ! Veuillez réessayer ultérieurement.");
                              $(x).data("lk",0);
                            }
                        });
                    break;
                case "fksa_sharon_twr" :
                        var psd = $("#fksa-art-ubx-upsd").text().slice(1).toLowerCase();
                        var link = document.URL;
                        var text  = encodeURIComponent("Je viens de partager une publication de trenqr.me/".concat(psd).concat(" postée sur #Trenqr :"));
                        window.open('https://twitter.com/share?url=' + link + '&text=' + text + '&', 'twitterwindow', 'height=450, width=550, top='+($(window).height()/2 - 225) +', left='+$(window).width()/2 +', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
                        
                        $(x).data("lk",0);
                    break;
                default :
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_VidAct = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            var vid = $(".jb-fksa-art-ctr-vid").get(0);
            if ( KgbLib_CheckNullity(vid) ) {
                return;
            }
                    
            switch (a) {
                case "fksa-vid-play" :
                        vid.play();
                        $(x).data("action","fksa-vid-pause");
                        $(".jb-fksa-art-ctr-lnch-vid").removeClass("paused");
                        
                    break;
                case "fksa-vid-pause" :
                        vid.pause();
                        $(x).data("action","fksa-vid-play");
                        $(".jb-fksa-art-ctr-lnch-vid").addClass("paused");
                        
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /***************************************************************************************************************************************************************/
    /************************************************************************ SERVER SCOPE *************************************************************************/
    /***************************************************************************************************************************************************************/
    
    var _Ax_PlRs = Kxlib_GetAjaxRules("FKSA_PLRS");
    var _f_Srv_PlRs = function(i,s) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        if (! KgbLib_CheckNullity(_xhr_plrs) ) {
           return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    _xhr_plrs = null;
                    return;
                }
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    _xhr_plrs = null;
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
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
                                    /*
                                     * [DEPUIS 31-05-15] @BOR
                                     */
                                    //On change le statut de la zone pour signifier que la zone est chargée
                                    _f_Sprt_SetState("fksa_reactions",2);
                                    //On affiche la zone NoOne
                                    _f_VwOnVoid("fksa_reactions",true);
                                    //On masque le spinner
                                    _f_VwShwSpnr("fksa_reactions");
                                    
//                                        Kxlib_AJAX_HandleDeny();
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                break;
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
                                    Kxlib_AJAX_HandleFailed();
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
                } else {
                    _xhr_plrs = null;
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                _xhr_plrs = null;
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
            _xhr_plrs = null;
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_PlRs.urqid,
            "datas": {
                "ai":i,
                "curl": u 
            }
        };

        _xhr_plrs = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlRs.url, wcrdtl : _Ax_PlRs.wcrdtl });
        return _xhr_plrs;
    };
    
    var _Ax_EvalAx = Kxlib_GetAjaxRules("FKSA_EVAL_AX");
    var _f_Srv_EvalAx = function (ec,i,t,p,x,s) {
        if ( KgbLib_CheckNullity(ec) | KgbLib_CheckNullity(i) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(p) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                       
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G" :
                            case "__ERR_VOL_CU_GONE" :
                            case "__ERR_VOL_ACC_GONE" :
                            case "__ERR_VOL_TARGET_GONE" :
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE" :
                                return;
                            case "__ERR_VOL_DENY" :
                            case "__ERR_VOL_DENY_AKX" :
                                return;
                            default:
                                return;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) && !KgbLib_CheckNullity(datas.return.eval) ) {
                    var ds = [datas.return];
                    $(s).trigger("datasready",ds);
                } else {
                    $(s).trigger("operended",ds);
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
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
        
        /* 
         * ec => Eval_Code : le code qui sera reçu par le serveur
         * i => id de l'article qui sera identifiable grace à son type ou à la page 
         * t => Le type d'Article 'iml' ou 'itr'
         * p => Le code de la page 
         * a => L'Action rh_cool, rh_spcl, rh_dislk, bk_cool, bk_spcl, bk_dislk ([NOTE 26-06-14] C'est inutile, on est déjà dans la fonction de traitement de l'action connue
         * * */
        //On récupère la page en cours
        var cu = document.URL;
        var toSend = {
            "urqid": _Ax_EvalAx.urqid,
            "datas": {
                "ec"    : ec,
                "t"     : t,
                "i"     : i,
                "mdl"   : p,
                /*
                 * [NOTE 20-06-16] 
                 *      Ces données ne servent à rien et relève d'une ancienne version.
                 *      Nous ne les remplissons que pour faire fonctionner le mécanisme. 
                 *      La donnée "ru" est arbitraire.
                 */
                "pg_prop": {
                    "pg"    : "fksa",
                    "ver"   : "ru" 
                },
                curl    : cu
            }
        };

        _xhr_evax = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_EvalAx.url, wcrdtl : _Ax_EvalAx.wcrdtl });
    };
    
    var _Ax_PlEs = Kxlib_GetAjaxRules("FKSA_PLES");
    var _f_Srv_PlEs = function(i,s) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        if (! KgbLib_CheckNullity(_xhr_ples) ) {
           return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    _xhr_ples = null;
                    return;
                }
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    _xhr_ples = null;
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
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
                                       /*
                                        * [DEPUIS 31-05-15] @BOR
                                        */
                                        //On change le statut de la zone pour signifier que la zone est chargée
                                        _f_Sprt_SetState("fksa_evaluations",2);
                                        //On affiche la zone NoOne
                                        _f_VwOnVoid("fksa_evaluations",true);
                                        //On masque le spinner
                                        _f_VwShwSpnr("fksa_evaluations");
                                        
//                                        Kxlib_AJAX_HandleDeny();
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                break;
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
                                    Kxlib_AJAX_HandleFailed();
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
                } else {
                    _xhr_ples = null;
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                _xhr_ples = null;
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
            _xhr_ples = null;
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_PlEs.urqid,
            "datas": {
                "ai":i,
                "curl": u 
            }
        };

        _xhr_ples = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlEs.url, wcrdtl : _Ax_PlEs.wcrdtl });
        return _xhr_ples;
    };
    
    
    var _Ax_PlSmpl = Kxlib_GetAjaxRules("FKSA_PLSMPL");
    var _f_Srv_PlSmpl = function(i,sec,s) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(sec) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } 
                
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
                                    break;
                            case "__ERR_VOL_FAILED" :
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                    break;
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
//                                    Kxlib_AJAX_HandleFailed();
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
                } else {
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
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
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_PlSmpl.urqid,
            "datas": {
                "ai"    : i,
                "sec"   : sec,
                "curl"  : u 
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlSmpl.url, wcrdtl : _Ax_PlSmpl.wcrdtl });
    };
    
    
    var _Ax_AdRct = Kxlib_GetAjaxRules("FKSA_ADD_RCT");
    var _f_Srv_AdRct = function(ai,at,rm,lri,lrt,xt,x,s) {
        if ( KgbLib_CheckNullity(ai) |  KgbLib_CheckNullity(at) |  KgbLib_CheckNullity(rm) |  KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        if (! KgbLib_CheckNullity(_xhr_adrc) ) {
            if ( x && $(x).length ){
                $(x).data("lk",0);
            };
            return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    _xhr_adrc = null;
                    if ( x && $(x).length ){
                        $(x).data("lk",0);
                    };
                    return;
                }
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    _xhr_adrc = null;
                    if ( x && $(x).length ){
                        $(x).data("lk",0);
                    };
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
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
                                    /*
                                     * [DEPUIS 31-05-15] @BOR
                                     */
                                    //On change le statut de la zone pour signifier que la zone est chargée
                                    _f_Sprt_SetState("fksa_reactions",2);
                                    //On affiche la zone NoOne
                                    _f_VwOnVoid("fksa_reactions",true);
                                    //On masque le spinner
                                    _f_VwShwSpnr("fksa_reactions");
                                    
//                                        Kxlib_AJAX_HandleDeny();
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                break;
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
                                    Kxlib_AJAX_HandleFailed();
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
                } else {
                    _xhr_adrc = null;
                    if ( x && $(x).length ){
                        $(x).data("lk",0);
                    };
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                _xhr_adrc = null;
                if ( x && $(x).length ){
                    $(x).data("lk",0);
                };
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
            _xhr_adrc = null;
            if ( x && $(x).length ){
                $(x).data("lk",0);
            };
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_AdRct.urqid,
            "datas": {
                "ai"    : ai,
                "at"    : at,
                "rm"    : rm,
                "lri"   : lri,
                "lrt"   : lrt,
                "xt"    : xt,
                "cu"    : u
            }
        };

        _xhr_adrc = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_AdRct.url, wcrdtl : _Ax_AdRct.wcrdtl });
        return _xhr_adrc;
    };
    
    
    var _Ax_DlRct = Kxlib_GetAjaxRules("FKSA_DEL_RCT");
    var _f_Srv_DlRct = function(ai,at,ri,rt,xt,x,s) {
        if ( KgbLib_CheckNullity(ai) |  KgbLib_CheckNullity(at) |  KgbLib_CheckNullity(ri) |  KgbLib_CheckNullity(rt) |  KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        if (! KgbLib_CheckNullity(_xhr_dlrc) ) {
            if ( x && $(x).length ){
                $(x).data("lk",0);
            };
            return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    _xhr_dlrc = null;
                    if ( x && $(x).length ){
                        $(x).data("lk",0);
                    };
                    return;
                }
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    _xhr_dlrc = null;
                    if ( x && $(x).length ){
                        $(x).data("lk",0);
                    };
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
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
                                    /*
                                     * [DEPUIS 31-05-15] @BOR
                                     */
                                    //On change le statut de la zone pour signifier que la zone est chargée
                                    _f_Sprt_SetState("fksa_reactions",2);
                                    //On affiche la zone NoOne
                                    _f_VwOnVoid("fksa_reactions",true);
                                    //On masque le spinner
                                    _f_VwShwSpnr("fksa_reactions");
                                    
//                                        Kxlib_AJAX_HandleDeny();
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                break;
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
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if ( d.hasOwnProperty("return") && !KgbLib_CheckNullity(d.return) )  {
                    /*
                     * Données attendues : 
                     *  (1) Données sur le nombre de COMMENTS
                     */
                    rds = [d.return];
                    $(s).trigger("operended",rds);
                } else {
                    _xhr_dlrc = null;
                    if ( x && $(x).length ){
                        $(x).data("lk",0);
                    };
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                _xhr_dlrc = null;
                if ( x && $(x).length ){
                    $(x).data("lk",0);
                };
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
            _xhr_dlrc = null;
            if ( x && $(x).length ){
                $(x).data("lk",0);
            };
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        var u = document.URL;
        var toSend = {
            "urqid": _Ax_DlRct.urqid,
            "datas": {
                "ai"    : ai,
                "at"    : at,
                "ri"    : ri,
                "rt"    : rt,
                "xt"    : xt,
                "cu"    : u
            }
        };

        _xhr_dlrc = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_DlRct.url, wcrdtl : _Ax_DlRct.wcrdtl });
        return _xhr_dlrc;
    };
    
    
    var _Ax_ActEvl = Kxlib_GetAjaxRules("FKSA_ACT_EVL");
    
    
    /***********************************************************************************************************************************************************/
    /*********************************************************************** VIEW SCOPE ************************************************************************/
    /***********************************************************************************************************************************************************/
    
    var _f_VwMenuActiv = function(x){
        /*
         * Permet d'activer vsuellement un menu en particulier.
         * Si le menu passé est déjà activé on le désactive.
         */
        try {
            if ( KgbLib_CheckNullity(x) | !$(x).data("action") ) {
                return;
            }
            //On s'assure que tout est normal au niveau du pin de signalisation
            if ( $(".fksa-a-mn-ch-selected-pin").length && $(".fksa-a-mn-ch-selected-pin").length > 1 ) {
                return;
            }
            
            //ta = TargetAction
            var ta = $(x).data("action");
            //am = ActiveMenu, ama = ActiveMenuAction
            var am, ama;
            if ( $(".jb-fksa-a-mn-ch-selected-pin").length && !($(".jb-fksa-a-mn-ch-selected-pin").closest(".jb-fksa-a-mn-choice").length && $(".jb-fksa-a-mn-ch-selected-pin").closest(".jb-fksa-a-mn-choice").data("action").length) ) {
                return;
            } else if ( $(".jb-fksa-a-mn-ch-selected-pin").length ) {
                am = $(".jb-fksa-a-mn-ch-selected-pin").closest(".jb-fksa-a-mn-choice");
                ama = $(".jb-fksa-a-mn-ch-selected-pin").closest(".jb-fksa-a-mn-choice").data("action");
            }
            
            
            if (ama && ama === ta) {
                $(x).find(".jb-fksa-a-mn-ch-selected-pin").remove();
                $(".jb-fksa-a-mn-choice").removeClass("selected");
            } else {
//        } else if (! ama ) {
                
                $(".jb-fksa-a-mn-ch-selected-pin").remove();
                $(".jb-fksa-a-mn-choice").removeClass("selected");
                
                switch (ta) {
                    case "fksa_evaluations" :
                    case "fksa_reactions" :
                        var sp = $("<span/>").attr({
                            class: "fksa-a-mn-ch-selected-pin jb-fksa-a-mn-ch-selected-pin"
                        });
                        $(".jb-fksa-a-mn-choice[data-action='" + ta + "']").prepend(sp);
                        $(".jb-fksa-a-mn-choice[data-action='" + ta + "']").addClass("selected");
                        break;
                    default: 
                        return;
                }
                
            }
            
            return true;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_VwShwSprt = function(scp,shw,rznb){
        /*
         * Permet de fermer et d'ouvrir la fenetre qui liste les commentaires au niveau de la zone Footer.
         */
        try {
            if ( KgbLib_CheckNullity(scp) ) {
                return;
            }
            
            var $b, inr_rznb = (! KgbLib_CheckNullity(rznb) ) ? rznb : true;
            switch (scp) {
                case "fksa_evaluations" :
                        $b = $(".jb-fksa-art-evl-mx");
                        $(".jb-fksa-art-rct-mx").addClass("this_hide");
                        /*
                         * [DEPUIS 20-06-16]
                         */
//                        _f_RstSprt("fksa_reactions",inr_rznb);
                        _f_RstSprt("fksa_evaluations",inr_rznb);
                        
                        $(".jb-fksa-a-ev-m-list-mx").removeClass("this_hide");
                    break;
                case "fksa_reactions" :
                        $b = $(".jb-fksa-art-rct-mx");
                        $(".jb-fksa-art-evl-mx").addClass("this_hide");
                        /*
                         * [DEPUIS 20-06-16]
                         */
//                        _f_RstSprt("fksa_evaluations",inr_rznb);
                        _f_RstSprt("fksa_reactions",inr_rznb);
                    break;
                default:
                    return;
            }
            
            //On s'assure que la zone support est présente dans le dom
            if (!$b.length) {
                return;
            }
            
            if (shw) {
                $b.removeClass("this_hide");
            } else {
                $b.addClass("this_hide");
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_VwShwSpnr = function(scp,shw) {
        /*
         * Permet d'afficher et de masquer le spinner lié à la liste les commentaires au niveau de la zone Footer.
         */
        try {
            if ( KgbLib_CheckNullity(scp) ) {
                return;
            }
            
            var $b;
            switch (scp) {
                case "fksa_evaluations" :
                        $b = $(".jb-fksa-art-evl-mx");
                    break;
                case "fksa_reactions" :
                        $b = $(".jb-fksa-art-rct-mx");
                    break;
                default:
                    return;
            }
            
            //On s'assure que la zone support est présente dans le dom
            if (! $b.length ) {
                return;
            }
            
            if (! $b.find(".jb-fksa-a-spnr").length ) {
                return;
            }
            
            if ( shw ) {
                $b.find(".jb-fksa-a-spnr").removeClass("this_hide");
            } else {
                $b.find(".jb-fksa-a-spnr").addClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /**************************** MODELS ***************************/
    
    var _f_PprRctMdl = function (d) {
        try {
            if ( KgbLib_CheckNullity(d) ) {
                return;
            }
            
            var fn = Kxlib_Decode_After_Encode(d.rofn);
            var psd = Kxlib_Decode_After_Encode(d.ropsd);
            
            //** On met en forme la date **//
            var dt = new KxDate(parseInt(d.rtm));
            dt.SetUTC(true);
            //On insere la date
            var tm = dt.WriteDate();
            var tm_hr = dt.getHours()+":"+("0"+dt.getMinutes()).substr(-2);
            //TODO : Mettre un title pour donner la date et l'heure exacte avec un format qui est lié à la langue de diffusion
            
            var str__;
            if (!KgbLib_CheckNullity(d.ustgs) && d.hasOwnProperty("ustgs") && d.ustgs !== undefined && typeof d.ustgs === "object") {
                var istgs__ = [];
                $.each(d.ustgs, function(x, v) {
                    var rw__ = [];
                    $.map(v, function(e, x) {
                        rw__.push(e);
                    });
                    istgs__.push(rw__.join("','"));
                });
//            Kxlib_DebugVars([JSON.stringify(istgs__)],true);
                if ( istgs__.length > 1 ) {
                    str__ = istgs__.join("'],['");
                } else {
                    str__ = istgs__[0];
                }
                str__ = "['" + str__ + "']";
            }
            
            
            var e = "<div class=\"fksa-art-react-mdl jb-fksa-art-elt-mdl jb-fksa-art-r-mdl\" data-item=\""+d.rid +"\" data-time=\""+d.rtm +"\" data-cache=\"["+d.rid+","+d.rm+","+d.rtm +","+d.roid+","+fn+","+psd+","+d.roppic+"]\" ";
            e += " data-with=\""+Kxlib_ReplaceIfUndefined(str__)+"\"";
            e += " >";
            e += "<div class=\"fksa-a-r-m-header\">";
            e += "<a class=\"fksa-a-r-m-user\" href=\"/@"+d.ropsd+"\" title=\""+d.rofn+"\">";
            e += "<span class=\"fksa-a-r-m-u-upic-fade\"></span>";
            e += "<img class=\"fksa-a-r-m-u-upic\" height=\"40\" width=\"40\" src=\""+d.roppic+"\" />";
            e += "<span class=\"fksa-a-r-m-u-upsd\">@"+d.ropsd+"</span>";
            e += "</a>";
            e += "<span class=\"fksa-a-r-m-time\">"+tm+"&nbsp;à&nbsp;"+tm_hr+"</span>";
            e += "</div>";
            e += "<div class=\"fksa-a-r-m-contents-mx\">";
            e += "<div class=\"fksa-a-r-m-content jb-fksa-rct-txt\"></div>";
            e += "</div>";
            e += "<div class=\"fksa-a-r-m-footer jb-fksa-a-r-m-ftr\">";
            if ( d.cdel ) {
                e += "<span class=\"fksa-a-r-m-ftr-del-mx\">";
                e += "<a class=\"fksa-a-r-m-f-dl-opt jb-fksa-a-r-m-f-dl-opt\" data-action=\"fksa-del-r-start\">Supprimer</a>";
                e += "<span class=\"fksa-a-r-m-f-dl-opt-fnl-mx jb-fksa-a-r-m-f-dl-o-fnl-mx this_hide\">";
                e += "<span class=\"fksa-a-r-m-f-dl-fnl-o-lbl\">Certain ?</span>";
                e += "<a class=\"fksa-a-r-m-f-dl-fnl-o jb-fksa-a-r-m-f-dl-fnl-o\" data-action=\"fksa-del-r-fnl-y\">Oui</a>";
                e += "<a class=\"fksa-a-r-m-f-dl-fnl-o jb-fksa-a-r-m-f-dl-fnl-o\" data-action=\"fksa-del-r-fnl-n\">Non</a>";
                e += "</span>";
                e += "</span>";
            }
            e += "</div>";
            e += "</div>";
            
            e = $.parseHTML(e);
            
            /*
             * ETPAE :
             * Traitement du texte de description pour qu'il puisse en compte les Usertags.
             */
            var t__ = d.rm;
//            var t__ = Kxlib_Decode_After_Encode(v.adesc);
            /*
            if (str__ && str__.length) {
                /*
                 * [DEPUIS 30-04-15]
                 *
                t__ = $("<div/>").html(t__).text();
                
                var ustgs = Kxlib_DataCacheToArray(str__)[0];
                //                Kxlib_DebugVars([Kxlib_ObjectChild_Count(v.austgs),ustgs[3]],true);
                var ps = ( ustgs && $.isArray(ustgs[0]) ) ? Kxlib_GetColumn(3, ustgs) : [ustgs[3]];
                t__ = Kxlib_UsertagFactory(t__, ps, "tqr-unq-user");
                
                $(e).find(".jb-fksa-rct-txt").text(t__);
                t__ = $(e).find(".jb-fksa-rct-txt").text();
                t__ = Kxlib_SplitByUsertags(t__);
                
                $(e).find(".jb-fksa-rct-txt").html(t__);
            } else {
                /*
                 * [DEPUIS 30-04-15]
                 *
                t__ = $("<div/>").html(t__).text();
                $(e).find(".jb-fksa-rct-txt").text(t__);
            }
            //*/
            var ustgs,hashs;
            ustgs = d.ustgs;
            hashs = d.hashs;
            
            /*
            if ( !KgbLib_CheckNullity(ustgs) | !KgbLib_CheckNullity(ustgs) ) {
                Kxlib_DebugVars([typeof ustgs,ustgs,typeof hashs, hashs],true);
            }
            //*/
            t__ = Kxlib_Decode_After_Encode(t__);
            var rtxt =  Kxlib_TextEmpow(t__,ustgs,hashs,null,{
                emoji : {
                    "size"          : 36,
                    "size_css"      : 18,
                    "position_y"    : 3
                },
                wrap_text : true
            });
            $(e).find(".jb-fksa-rct-txt").append(rtxt);
            
            return e;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_RbdRctMdl = function (rm) {
        try {
            if ( KgbLib_CheckNullity(rm) ) {
                return;
            }
            
            $(rm).find(".jb-fksa-a-r-m-f-dl-opt, .jb-fksa-a-r-m-f-dl-fnl-o").click(function(e){
                Kxlib_PreventDefault(e);
                
                _f_Action(this);
            });
            
            return rm;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_PprEvlMdl = function (d) {
        try {
            if ( KgbLib_CheckNullity(d) ) {
                return;
            }
        
            var fn = Kxlib_Decode_After_Encode(d.evofn);
            var psd = Kxlib_Decode_After_Encode(d.evopsd);
            
            //*** On met en forme la date ***//
            var dt = new KxDate(parseInt(d.evtime));
            dt.SetUTC(true);
            //On insere la date
            var tm = dt.WriteDate();
            var tm_hr = dt.getHours()+":"+("0"+dt.getMinutes()).substr(-2);
            //TODO : Mettre un title pour donner la date et l'heure exacte avec un format qui est lié à la langue de diffusion
            
            //*** EVAL ACTION ***//
            var evact = Kxlib_getDolphinsValue("FKSA"+d.evcode);
            if (! evact ) {
                return;
            }
            
            var e = "<div class=\"fksa-art-react-mdl jb-fksa-art-elt-mdl jb-fksa-art-eval-mdl\" data-item=\""+d.oid+"\" data-cache=["+d.evcode+","+d.evtime+","+d.evoid+","+d.evofn+","+d.evopsd+","+d.evoppic+"]>";
            e += "<div class=\"fksa-a-r-m-header\">";
            e += "<a class=\"fksa-a-r-m-user\" href=\"/@"+psd+"\" title=\""+fn+"\" >";
            e += "<span class=\"fksa-a-r-m-u-upic-fade\"></span>";
            e += "<img class=\"fksa-a-r-m-u-upic fksa-a-ev-m-u-upic\" height=\"30\" src=\""+d.evoppic+"\" />";
            e += "<span class=\"fksa-a-r-m-u-upsd fksa-a-ev-m-u-upsd\">@"+psd+"</span>";
            e += "</a>";
            e += "<span class=\"fksa-a-r-m-time fksa-a-ev-m-time\">"+tm+"&nbsp;à&nbsp;"+tm_hr+"</span>";
            e += "</div>";
            e += "<div class=\"fksa-a-e-m-contents-mx\">";
            e += "<span class=\"fksa-a-r-m-content\"><b class=\"fksa-a-r-m-ctt-action\">"+evact+"</b> cette publication.</span>";
            e += "</div>";
            e += "</div>";
            
            e = $.parseHTML(e);
            
            return e;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_VwOnVoid = function (scp,shw) {
        try {
            
           /*
            * Permet d'afficher ou de masquer la zone NoOne pour "Reaction" ou "Evaluation"
            */
            if ( KgbLib_CheckNullity(scp) ) {
                return;
            }
            if ( KgbLib_CheckNullity(shw) ) {
                $(".jb-fksa-a-elt-noone-mx").remove();
                return;
            }
            
            var e;
            switch (scp) {
                case "fksa_evaluations" :
                        e = _f_PprOnVoid(scp);
//                        Kxlib_DebugVars([e,$(".jb-fksa-a-r-spnr-mx").length],true);
                        
                        //On ajoute l'élément après la zone de spinner
                        $(e).insertAfter($(".jb-fksa-a-ev-spnr-mx"));
                    break;
                case "fksa_reactions" :
                        e = _f_PprOnVoid(scp);
//                        Kxlib_DebugVars([e,$(".jb-fksa-a-r-spnr-mx").length],true);
                        
                        //On ajoute l'élément après la zone de spinner
                        $(e).insertAfter($(".jb-fksa-a-r-spnr-mx"));
                    break;
                default: 
                    return;
            }
            
            return true;
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_PprOnVoid = function (scp) {
        /*
         * Construit un modèle pour afficher un message disons qu'il n'y a pas d'éléments à afficher.
         */
        if ( KgbLib_CheckNullity(scp) ) {
            return;
        }
        
        var e;
        switch (scp) {
            case "fksa_evaluations" :
                    e = "<div id=\"fksa-a-ev-noone-mx\" class=\"jb-fksa-a-elt-noone-mx jb-fksa-a-ev-noone-mx\">";
                    e += "<span id=\"fksa-a-ev-noone-txt\" class=\"fksa-a-e-noone-txt jb-fksa-a-ev-noone-txt\">Aucune appreciation disponible</span>";
                    e += "</div>";
                break;
            case "fksa_reactions" :
                    e = "<div id=\"fksa-a-r-noone-mx\" class=\"jb-fksa-a-elt-noone-mx jb-fksa-a-r-noone-mx\">";
                    e += "<span id=\"fksa-a-r-noone-txt\" class=\"fksa-a-r-noone-txt jb-fksa-a-r-noone-txt\">Aucun commentaire disponible</span>";
                    e += "</div>";
                break;
            default: 
                return;
        }
        
        e = $.parseHTML(e);
        
        return e;
    };
    
    var _f_GotoBtm = function () {
        $('html, body').animate({scrollTop: 700}, 1000);
    };
    
    
    var _f_DsplASmpl = function (ds) {
        try {
            if ( KgbLib_CheckNullity(ds) ) {
                return;
            }
        
            $.each(ds,function(i,at){
                //On vérifie si l'élément existe
                if ( $(".jb-fksa-art-smpl-bx[data-item='"+at.id+"']").length ) {
                    return;
                }
                
                //On prépare les données
                var e__ = _f_PprASmplMdl(at);

                //On ajoute les données
                $(".jb-fksa-art-smpl-list").append(e__);
            });

            //On masque la zone spinner
            $(".jb-fksa-a-s-mx-spn-mx").addClass("this_hide");

            //On affiche les Articles
            $(".jb-fksa-art-smpl-list").removeClass("this_hide");
            $(".jb-fksa-art-smpl-bx").hide().removeClass("this_hide").fadeIn();
        
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_PprASmplMdl = function (d) {
        try {
            if ( KgbLib_CheckNullity(d) ) {
                return;
            }
        
            var e = "<div class=\"fksa-art-smpl-bx jb-fksa-art-smpl-bx this_hide\" data-item=\""+d.id+"\">";
            e += "<a class=\"fksa-art-smpl-bx-iwpr\" href=\""+d.h+"\">";
            e += "<span class=\"fksa-art-smpl-bx-i-fade jb-fksa-a-s-bx-i-fd\"></span>";
            e += "<img class=\"fksa-art-smpl-bx-img\" src=\""+d.im+"\" height=\"75\"/>";
            e += "</a>";
            e += "</div>";
            e = $.parseHTML(e);
            
            if ( d.vidu ) {
                $(e).find(".jb-fksa-a-s-bx-i-fd").addClass("vidu");
            }
            
            return e;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_DsplASmpl_tstver = function (ds) {
        try {
            if ( KgbLib_CheckNullity(ds) ) {
                return;
            }
        
            $.each(ds,function(i,at){
                //On vérifie si l'élément existe
                if ( $(".jb-tqr-fksa-tst-go-f-b-amx[data-item='"+at.id+"']").length ) {
                    return;
                }
                
                //On prépare les données
                var e__ = _f_PprASmplMdl_tstver(at);

                //On ajoute les données
                $(".jb-tqr-fksa-tst-go-f-b-a-lst").append(e__);
            });
            
            //On ajoute les données au sujet de PSD
            var psd = $(".jb-tqr-fksa-tst-o-psd").text();
            $(".jb-tqr-fksa-tst-go-f-h-t-psd").text(psd).attr("href","/".concat(psd));

            //On affiche les Articles
            $(".jb-tqr-fksa-tst-go-fur-mx").hide().removeClass("this_hide").fadeIn();
        
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_PprASmplMdl_tstver = function (d) {
        try {
            if ( KgbLib_CheckNullity(d) ) {
                return;
            }
        
            var e = "<li class=\"tqr-fksa-tst-go-f-b-a-wpr jb-tqr-fksa-tst-go-f-b-a-wpr\">";
            e += "<article class=\"tqr-fksa-tst-go-f-b-amx jb-tqr-fksa-tst-go-f-b-amx\" data-item=\""+d.id+"\">";
            e += "<a class=\"tqr-fksa-tst-go-f-b-a-i-wpr jb-tqr-fksa-tst-go-f-b-a-i-wpr\" href=\""+d.h+"\">";
            e += "<span class=\"tqr-fksa-tst-go-f-b-a-i-fd jb-tqr-fksa-tst-go-f-b-a-i-fd\"></span>";
            e += "<img class=\"tqr-fksa-tst-go-f-b-a-i jb-tqr-fksa-tst-go-f-b-a-i\" src=\""+d.im+"\" width=\"70\" alt=\"\" title=\"\" />";
            e += "</a>";
            e += "</article>";
            e += "</li>";
                    
            e = $.parseHTML(e);
            
            if ( d.vidu ) {
                $(e).find(".jb-tqr-fksa-tst-go-f-b-a-i-fd").addClass("vidu");
            }
            
            return e;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /***********************************************************************************************************************************************************/
    /*********************************************************************** AUTOS SCOPE ***********************************************************************/
    /***********************************************************************************************************************************************************/
    
    (function(){
        if ( $(".jb-fksa-art-desc") && $(".jb-fksa-art-desc").length ) {
            _f_OnLoad("_sec_article");
        } else if ( $("html").find("div[s-id='FKSA_GTPG_TSTVER']") ) {
            _f_OnLoad("_sec_testy");
        }
    })();
    
    /***********************************************************************************************************************************************************/
    /********************************************************************* LISTENERS SCOPE *********************************************************************/
    /***********************************************************************************************************************************************************/
    $(".jb-fksa-a-mn-choice").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
    $(".jb-fksa-art-legal-hrf, .jb-fksa-art-s-mr-hf, .jb-fksa-smr-ads-rmvads").click(function(e){
        Kxlib_PreventDefault(e);
        
        $(".jb-cnxsgn-ovly-sprt").removeClass("this_hide");
    });
    
    $(".jb-fksa-a-ev-m-lst-chrts-tgr, .jb-fksa-a-ev-m-lst-chrts-clz, .jb-fksa-art-nw-rct-b-b-send, .jb-fksa-art-ctr-lnch-vid, .jb-fksa-a-ev-m-evbx-ax").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
    if ( $(window).scrollTop() < 100 ) {
        $('html, body').animate({scrollTop: 115}, 1000);
    }
    
    $(window).resize(function(){
        _f_Vid_Fit();
    });
    
}

new FKSA();