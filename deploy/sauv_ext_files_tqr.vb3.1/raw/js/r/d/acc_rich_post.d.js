//Represente un objet commentaire

function ARP_HNDLR() {
    var gt = this;
       
    //URQID => Get Infos for ARP from server
    this.MaxForArp;
    
    /*************************************************************************************************************************************************/
    /***************************************************************** PROCESS SCOPE *****************************************************************/
    /*************************************************************************************************************************************************/
    
    var _f_Gdf = function () {
        var dt = {
            /*
             * TTR = TimeToResfresh : Correspond au temps minimum necessaire avant que l'on puisse rafraichir les commentaires.
             * La valeur est exprimée en MILLISECONDES.
             * On ne rafraichit pas à chaque ouverture d'ARP pour économiser le SERVER et pour une meilleure expérience utilisateur.
             * Les commentaires ne doivent pas buoger dans les tous sens.
             * MAis encore, on ne s'attends pas à ce qu'il y ait des commentaires toutes les 1 secondes.
             */
            TTR: 15000,
            /*
             * OPSNCLMT : OPenSiNCe
             * Le temps qu'il faudra pour faire disparaitre les éléments de la zone ARP
             */
            OPSNCLMT : 8000,
            "_15psz" : { h : 770, w : 1400 }
        }; 
        
        return dt;
    };
    
    //MUST STAY PUBLIC !!!
    this.CheckOperation = function(x) {
        if ( KgbLib_CheckNullity(x) | !$(x) | KgbLib_CheckNullity($(x).data("action")) ) {
            return;
        }
        try {
            
            var ua = $(x).data("action").toLowerCase();
            switch (ua) {
                case "rp-get-lk":
                        _f_Get_Link(x);
                    break;
                case "del_start":
                case "del_confirm":
                case "del_abort":
                        _f_HdlDelPost(x);
                    break;
                case "close_all":
                        _f_ClzAllSets(x);
                    break;
                case "adesc_move_up" :
                case "adesc_move_down" :
                        _f_MvArtDesc(x);
                    break;
                default :
                    //TODO : Incoherence
                    break;
            }
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    
    //Le but de cette fonction est de créer la version ARP de Post
    //Cette fonction peut servir dans de multiples cas
    //STAY PUBLIC
    this.HandleCreateArp = function(a) {
        //$argv correspond à MAX, celui qui contruit Post et ARP.
        if ( KgbLib_CheckNullity(a) ) {
            return;
        }
        
        var i = $(a).data("item");
        
        /*
         * [NOTE 21-04-15] @BOR
         * Cette méthode de gestion n'est pas fiable car la variable peut être modifiée ulterieurement car elle est globale et non réservée.
         */
//        gt.MaxForArp = $(a);
        var b = $(a), s = $("<span/>");
        
        //On demande au serveur de nous renvoyer le ..?  
        _Srv_GetARPVerFromSrv(i,b,s);
        
        $(s).on("datasready",function(e,d,b) {
            if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(b) ) {
                return;
            }
             _f_CreateARP(d,b);
        });
    };
    
    var _f_OnOpen = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) { 
                return; 
            }
            
            var id = Kxlib_ValidIdSel($(x).data("target"));
           /*
            * Si l'article n'a pas la partie ARP c'est qu'il a été ajouté dynamiquement.
            * Il faut donc créer la partie ARP.
            */
            if (! $(id).has(".jb-arp-solo-in-acclist").length ) {
    //            gt.HandleCreateArp($(x));
                gt.HandleCreateArp($(id));
            } else {
                //[NOTE 03-09-14] On lance la procédure pour récupérer les commentaires de l'Article
                _f_ARP_Pull_Rcts($(id));
            }

            //On write le texte dans la zone réservée dans le modèle ARP
            gt.PlaceArtDesc($(id));

            var $arp = $(id).find(".jb-arp-solo-in-acclist");

            $(id).addClass("sp_inmylide_figs");
            $(id).find(".post-solo-in-acclist").addClass("this_hide");
            $arp.removeClass("this_hide");

            /*
             * [DEPUIS 13-07-15] @BOR
             * On indique l'heure à laquelle ARP a été ouverte pour les modules interessés
             */
            if ( $(id).has(".jb-arp-solo-in-acclist").length ) {
                var tm__ = parseFloat((new Date()).getTime());
                $arp.data("opsnc",tm__);
            }

            /*
             * [DEPUIS 15-07-15] @BOR
             */
            _f_Placement($arp,$(id));

            //$(this).parent().parent().find(".post-solo-in-acclist").addClass("this_hide");
            //$(this).parent().parent().find(".jb-arp-solo-in-acclist").removeClass("this_hide");
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_OnClose = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) { 
                return; 
            }
            
            var $arp = $(x).closest(".jb-arp-solo-in-acclist");
            
//            _f_VidAction($arp.find(".jb-arp-bot-img-lnch-vid"));
            _f_VidPause($arp.find(".jb-arp-bot-img-lnch-vid"));
        
            var id = Kxlib_ValidIdSel($(x).data("target"));
            $(id).removeClass("sp_inmylide_figs");
            $(id).find(".post-solo-in-acclist").removeClass("this_hide");
            $(id).find(".jb-arp-solo-in-acclist").addClass("this_hide");
            
            //On reset la zone d'ajout du Commentaire
            $(id).find(".jb-arp-input").removeClass("error_field");
            
            /*
             * [DEPUIS 13-07-15] @BOR
             *  On execute la fonction de gestion de DESC.
             *  On effectue l'opération au préalable pour éviter de ne plus avoir accès à la taille une fois 
             */
//            _f_MvArtDesc($arp.find(".jb-arp-bot-img-desc-move[data-action='adesc_move_up']"),true); //[DEPUIS 31-03-16]
//            _f_MvArtDesc(null,true); //DEV, TEST, DEBUG
//            _f_MvArtDesc(null,false); //DEV, TEST, DEBUG
//            _f_MvArtDesc(null); //DEV, TEST, DEBUG

            /*
             * [DEPUIS 13-07-15] @BOR
             */
            //On reset les eléments qui ont pu prendre ".effi"
//            $arp.find(".jb-arp-bot-img-time").removeClass("effi");
//            $arp.find(".jb-tmlnr-arp-art-clz-all").removeClass("effi");
            /*
             * [NOTE 13-07-15] @BOR
             *  NON car :
             *   (1) Esthétiquement, ça ferait trop vide
             *   (2) Psychologiquement, ça pourrait faire peur à l'utilisateur de ne pas voir le bouton "Fermer"
             */
//            $arp.find(".jb-arp-bot-img-fmr").removeClass("effi"); //[DEPUIS 31-03-16]
            _f_EnaEffi($arp,false);
            
            /*
             * [DEPUIS 13-07-15] @BOR
             *      On retire l'indicateur "opsnc" pour remettre le compteur à 0.
             *      Il sera de nouveau créer s'il est ouvert.
             */
            $arp.data("opsnc","").removeAttr("data-opsnc");
            $arp.data("opsnc_autonmr","").removeAttr("opsnc_autonmr");
            
            /*
             * [DEPUIS 15-07-15] @BOR
             */
            $arp.removeClass("edge fit");
                    
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_AddRct = function(x) {
//    this.AddReaction = function(th) {

        if ( KgbLib_CheckNullity(x) ) { 
            return; 
        }
        
        var o = $(x).closest(".jb-tmlnr-mdl-std");
//        var input = $(th).parent();
//        alert(Kxlib_ValidClassSel($(th).data("target")));
        
        _f_Rct_Crt(o);
        
    };
    
    /*
    //STAY PUBLIC
    this.DelReaction = function(x) {
//    this.DelReaction = function(x) {
        if ( KgbLib_CheckNullity(x) ) { 
            return; 
        }
        _f_Rct_Del(x);
    };
    //*/
    var _f_Get_Link = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) { 
                return; 
            }
        
            var i = Kxlib_ValidIdSel($(x).data("target"));
            if ( KgbLib_CheckNullity(i) | !$(i).length ) {
                return;
            }

            //On récupère le lien
            var hf = $(x).closest(".jb-tmlnr-mdl-std").data("pml");
    //        var l = $(".arp-bot-img-img").attr("src");
            if ( KgbLib_CheckNullity(hf) ) {
                return;
            }
            //On écrit le lien
//            $(".jb-arp-pmlk-output").val(hf);
            $(i).find(".jb-arp-pmlk-output").val(hf); //[DEPUIS 28-04-16]
            
            /*
             * [DEPUIS 18-06-15] @BOR
             * On insère l'adresse au niveau du lien "Go to"
             *  [DEPUIS 22-11-15]
             *      Refactorisation
             */
//            var hf__ = l.split("trenqr.com")[1];
            var hf__ = hf.split(hf.split("/")[0])[1];
            
//            $(".jb-arp-get-link-ipt-gt").prop({
            $(i).find(".jb-arp-get-link-ipt-gt").prop({ //[DEPUIS 28-04-16]
                "href" : hf__,
//                "target" : "_blank" //[DEPUIS 22-11-15] Laisser le choix à l'utilsateur
            });

            $(i).find(".jb-arp-prmlk-sprt").removeClass("this_hide");
    //        $(i).find(".jb-arp-bot-img-fade").toggleClass("arp-bot-img-fade-gl");
        
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
                
    var _f_HdlDelPost = function (x) {
//    this.HandleDelPost = function (x) {
        if ( KgbLib_CheckNullity(x) | !$(x) | !$(x).length | KgbLib_CheckNullity($(x).data("action")) ) {
            return;
        }
        try {
             var t = Kxlib_ValidIdSel($(x).data("target")), i = $(t).data("item");
            /*
             * ETAPE :
             * On vérifie qu'elle est la nature précise du bouton afin de déciser de l'opération à suivre.
             */
            var ac = $(x).data("action").toLowerCase();
            switch (ac) {
                case "del_start" :
                        /*
                         * ETAPE :
                         * On affiche la zone de confirmation
                         */
                        $(t).find(".jb-arp-cfrm-bx-mx").removeClass("this_hide");
                        return;
                case "del_abort" :
                        /*
                         * ETAPE :
                         * On annule l'opération de suppression de l'Article
                         */
                        $(t).find(".jb-arp-cfrm-bx-mx").addClass("this_hide");
                        return;
                    break;
                case "del_confirm" :
                        /*
                         * ETAPE :
                         * On confirme la suppression de l'Article.
                         * Cela signifie qu'on continue vers la solution de suppression originelle.
                         */
                        break;
                default : 
                    return;
            }
            
            var s = $("<span/>");
            
            _Srv_RmvImlArt(i, s);
            
            $(t).addClass("this_hide");
            
            $(s).on("operended", function(e, d) {
                if (KgbLib_CheckNullity(d))
                    return;
                
                //On vérifie s'il y a des données sur le capital ...
                if (d.hasOwnProperty("o_cap") && !KgbLib_CheckNullity(d.o_cap)) {
                    $(".jb-acc-spec-artnb").text(d.o_pnb);
                }
                
                //... et le nombre d'articles
                if (d.hasOwnProperty("o_pnb") && !KgbLib_CheckNullity(d.o_pnb)) {
                    $(".jb-u-sp-cap-nb").text(d.o_cap);
                }
                
                //FINALLY
                //On supprime l'élément du DOM
                $(t).remove();
                
                //On notifie que l'article a été supprimée avec succès
                var Nty = new Notifyzing();
                Nty.FromUserAction("ua_del_art");
                
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_ClzAllSets = function() {
        try {
            $(".jb-arp-bot-img-fmr").click();
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_CreateARP = function(d,b) {
//    this.CreateARP = function(d) {
        if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(b) ) {
            return d;
        }
        
        try {
            /*
             * ETAPE :
             * On prépare le modèle ARP.
             */
            var a = _f_PprNewArp(d,b);
            
            /*
             * ETAPE :
             * On rebind les events grace à la fonction de REBIND
             */
            var $e = _f_RbdArpLstnrs(a);
            
            //On ajoute à MAX
            //alert(gt.MaxForArp);
            $($e).hide().appendTo(b);
            
            /*
             * [DEPUIS 15-07-15] @BOR
             * On vérifie le cas de l'écran pour améliorer l'expérience utilisateur
             */
            _f_Placement($e,b);
            
            /*
             * [DEPUIS 13-07-15] @BOR
             * On indique l'heure à laquelle ARP a été ouverte pour les modules interessés
             */
            var tm__ = parseFloat((new Date()).getTime());
            $($e).data("opsnc",tm__);
            
            /*
             * ETAPE :
             * On affiche l'EVAL actif
             */
//        Kxlib_DebugVars([typeof EVALBOX === "function", d.hasOwnProperty("art_myel"), !KgbLib_CheckNullity(d.art_myel)],true);
            if ( typeof EVALBOX === "function" && d.hasOwnProperty("art_myel") && !KgbLib_CheckNullity(d.art_myel) ) {
                //On utilise la fonciton qui permet de mettre à jour les EVALs 
                _f_UpdEvals(b,d.art_evals,d.art_myel);
            }
            
//        var Tg = new TIMEGOD();
//        Tg.UpdSpies();
            
            $($e).fadeIn(100);
            
            $(b).addClass("sp_inmylide_figs");
            $(b).find(".post-solo-in-acclist").addClass("this_hide");
            $(b).find(".jb-arp-solo-in-acclist").removeClass("this_hide");
            
            /* [DEPUIS 21-04-15]
            $(gt.MaxForArp).addClass("sp_inmylide_figs");
            $(gt.MaxForArp).find(".post-solo-in-acclist").addClass("this_hide");
            $(gt.MaxForArp).find(".jb-arp-solo-in-acclist").removeClass("this_hide");
            //*/
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_UpdEvals = function (b,ev,me) {
        /*
         * Permet de mettre à jour les données d'EVAL pour ARP.
         * La méthode est utilisée lors de la création d'ARP, lors de la mise à jour des données à l'ouverture d'ARP lorsque cela est permis.
         */
        //ev = EVals
        try {
            if ( KgbLib_CheckNullity(b) | KgbLib_CheckNullity(ev) | typeof EVALBOX !== "function" ) {
                return;
            }
            
            var EB = new EVALBOX(), eval_args;
            eval_args = {
                "b": b,
                "p": "arp",
                "d": {
                    "eval": ev
                },
                "me": me,
                "a": null //Sans inmportance dans ce cas
            };
//            Kxlib_DebugVars([typeof eval_args],true);
//            Kxlib_DebugVars([eval_args.b, eval_args.p, eval_args.d, eval_args.me, eval_args.a],true);
//            Kxlib_DebugVars([JSON.stringify(eval_args)],true);

//                var x = $(".jb-unq-art-mdl.active").find(".jb-csam-eval-choices[data-zr=rh_cool]");
//                EB.DisplayEval(args.b,args.p,args.d,args.me,args.a);
            b = EB.UpdateModelWithEval(eval_args.b, eval_args.p, eval_args.d, eval_args.me, eval_args.a);
//            Kxlib_DebugVars([$(b).html()],true);
//            Kxlib_DebugVars([me],true);
            if (! b ) {
                return;
            }

            /*
             * MISE A JOUR DE MYEL 
             * [30-04-15 15:45] @BOR
             * La valeur 'me' peut être nulle. Elle doit quand même être traitée
             */
            if ( true ) {
//            if (! KgbLib_CheckNullity(me) ) {
                EB.RmvAlEvl(b);
                b = EB.DplCUzrEvl(b, me);
            }

            return b;
        
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    //STAY PUBLIC !!
    this.PlaceArtDesc = function (o) {
        if ( KgbLib_CheckNullity(o) ) { 
            return; 
        }
        try {
            
            var m = $(o).find(".botm_a_desc").text();
//            Kxlib_DebugVars([m],true);
//            alert($(o).html());
//            return;
            
            /*
             * [DEPUIS 29-04-16]
             */
            /*
            var wh__ = $(o).data("with");
           
            if (!KgbLib_CheckNullity(wh__) && typeof wh__ === "string" && wh__.length) {
                var ustgs = Kxlib_DataCacheToArray(wh__)[0];
//                Kxlib_DebugVars([Kxlib_ObjectChild_Count(art.austgs),ustgs[3]],true);
                var ps = (ustgs && $.isArray(ustgs[0])) ? Kxlib_GetColumn(3, ustgs) : [ustgs[3]];
                var t__ = Kxlib_UsertagFactory(m, ps, "tqr-arp-user");
                
                t__ = Kxlib_SplitByUsertags(t__);
                $(o).find(".jb-arp-art-desc-txt").html(t__);
//            var $tp__ = $("<div/>").text(t__);
//            t__ = $tp__.text();
            } else {
                $(o).find(".jb-arp-art-desc-txt").text(m);
            }
            //*/
            if ( $(o).data("ajcache") ) {
                var atxt = m;
//                atxt = $("<div/>").html(atxt).text();
//                Kxlib_DebugVars(["'"+$(am).data("ajcache")+"'"],true);

                var ajca_o = ( typeof $(o).data("ajcache") === "object" ) ? 
                    $(o).data("ajcache") 
//                    : JSON.parse("'"+$(o).data("ajcache")+"'");
                    : JSON.parse($(o).data("ajcache"));
                    
                var ustgs = ajca_o.ustgs;
                var hashs = ajca_o.hashs;

//                Kxlib_DebugVars([ajca_o.hashs,ustgs],true);
                //rtxt = RenderedText
                var rtxt = Kxlib_TextEmpow(atxt,ustgs,hashs,null,{
                    emoji : {
                        "size"          : 36,
                        "size_css"      : 20,
                        "position_y"    : 3
                    }
                });
                $(o).find(".jb-arp-art-desc-txt").text("").append(rtxt);
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    
    var _f_ARP_Pull_Rcts = function(b) {
//    this.Handle_ARP_Pull_Reacts = function(b) {
        //i : L'identifiant externe de l'Article dont il faut récupérer les commentaires
        /*
         * Permet de récupérer les commentaires d'un Article auprès de la base de données afin de les afficher
         */
        try {
//            Kxlib_DebugVars([KgbLib_CheckNullity(b),KgbLib_CheckNullity($(b).data("item"))],true);
            if ( KgbLib_CheckNullity(b) | KgbLib_CheckNullity($(b).data("item")) ) {
                return;
            }
            
            var i = $(b).data("item");
            
            //On vérifie s'il y a lieu d'aller récupérer les commentaires auprès de la base de données
            //lp = LastPull : La dernier fois qu'on a fait une vérification des commentaires au niveau du serveur
            
            var lp = $(b).data("lp");
            lp = (KgbLib_CheckNullity(lp)) ? 0 : parseInt(lp);
//        Kxlib_DebugVars([b,$(b).data("item"),lp],true);
            var n = (new Date()).getTime();
            
            //eld  = elapsed (passé)
            var eld = n - lp;
//        Kxlib_DebugVars([lp,eld],true);
            if ( lp === 0 || eld >= _f_Gdf().TTR ) {
                //** On lance la procédure de récupération des commentaires **//
                var s = $("<span/>");
                
                //On va récupérer les données et on attend la réponse
                _Srv_PlRcts(i,b,s);
                
                $(s).on("datasready", function(e,b,d) {
                    if ( KgbLib_CheckNullity(b) | KgbLib_CheckNullity(d) ) {
                        return;
                    }
                    
                    //On récupère les données et on les traite pour les aficher dans le bloc des commentaires
//                    if (KgbLib_CheckNullity(d)) {
//                        //On retourne le nombre de commentaires supposé
//                        return 0;
//                    } else {
                    /*
                     * [16-12-14] @author
                     * Dans ce cas très précis, je suis obligé de reverse les données pour suivre la politique de lecture des commentaires
                     */
                    if ( d.hasOwnProperty("rs") && !KgbLib_CheckNullity(d.rs) && Kxlib_ObjectChild_Count(d.rs) ) {
                        d.rs = $(d.rs).get().reverse();
                        $.each(d.rs, function(x,rd) {
    //                        Kxlib_DebugVars([typeof rd,typeof _f_PushRct,rd.opsd],true);

                            //On vérifie s'il s'agit bien d'un nouveau commentaire
    //                        var ri = Kxlib_ValidIdSel("arp-react-"+rd.itemid);
                            if (! $(b).find(".jb-arp-react-mdl[data-item='" + rd.itemid + "']").length ) {
    //                        if (! $(ri).length  ) {
                                //Afficher les Commentaires
                                _f_PushRct(b,rd);
                            }
                        });
                    } else {
                        _f_RctPriv(b,false);
                        _f_Spnr(b,"arp_rct",false);
                        _f_None(b,"arp_rct",true);
                    }

                    var $tar = $(b).find(".jb-arp-list-rct-max");
                    /*
                     * [06-04-15] @BOR
                     * ETAPE :
                     * On scroll jusqu'à la fin de la zone de texte.
                     * [08-05-15] @BOR
                     * On utilise une méthode qui est plus fiable et adaptée.
                     */
//                    $($tar).animate({scrollTop: $($tar).height()}, 1000);
                    _f_ScrollZn();

                    /*
                     * ETAPE :
                     * On met à jour le nombre de commentaires 
                     */ 
//                        var $tar = $(b).find(".jb-arp-list-rct-max");
//                        var cn = $($tar).find(".jb-arp-react-mdl").length;
//                        $(b).find(".arp_nb_reacts").html(cn);
                    _f_UpdReactNb(b,d.arn);
                    
                    /*
                     * [DEPUIS 30-04-15] @BOR
                     * On met à jour les données d'EVAL
                     */
                     if ( typeof EVALBOX === "function" 
                             && d.hasOwnProperty("es") && !KgbLib_CheckNullity(d.es) 
                                && d.es.hasOwnProperty("tab") && !KgbLib_CheckNullity(d.es.tab) 
                                    && d.es.hasOwnProperty("me")
                    ) {
                        //On utilise la fonction qui permet de mettre à jour les EVALs 
                        _f_UpdEvals(b,d.es.tab,d.es.me);
                    }
                     
                     
                    //On met à jour LP
                    $(b).data("lp", (new Date()).getTime());
//                    }
                });
                
            } else {
                return false;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    
    /****************************************************************** ARP_REACTION ************************************************************/
    
    var _f_ChkCntt = function(a) {
    //    this.CheckContent = function(a) {
        if ( KgbLib_CheckNullity(a) ) {
            return;
        }
            
        var t = $(a).val();
        if ( KgbLib_CheckNullity(t) ) {
            $(a).addClass("error_field");
            return false;
        } else if ( !KgbLib_CheckNullity(t) && ( t.match(/\s/g) ||t.match(/\t/g) ||t.match(/\n/g) || t.match(/\r/g) || t.match(/\r\n/g) ) ) {
                //On vérifie s'il n'y a que des \n ou autres représentations de "retour chariot" dans le texte.
                //PARANO : je sais que j'aurais pu ne mettre '\s'
                 var c1 = ( t.match(/\n/g) ) ? t.match(/\n/g).length : 0;
                 var c2 = ( t.match(/\r/g) ) ? t.match(/\r/g).length : 0;
                 var c3 = ( t.match(/\r\n/g) ) ? t.match(/\r\n/g).length : 0;
                 var c4 = ( t.match(/\t/g) ) ? t.match(/\t/g).length : 0;
                 var c5 = ( t.match(/\s/g) ) ? t.match(/\s/g).length : 0;

                if ( c1 === t.length | c2 === t.length | c3 === t.length | c4 === t.length | c5 === t.length ) {
                    $(a).addClass("error_field");
                    return false;
                } else {
                    $(a).removeClass("error_field");
                    return true;
                }

        } else {
            $(a).removeClass("error_field");
            return true;
        }

        //parano
        return;
    };
    
    var _f_Rct_Crt = function(x) {
//    this.Create = function(x) {
        
        try {
            
            if ( KgbLib_CheckNullity(x) ) { return; }
            
            var o = $(x).find(".arp-nw-react-ipt");
            var t = $(o).val();
            
            //On s'assure qu'il y a bien du contenu
            if (! _f_ChkCntt(o) ) { 
                return;
            } else {
                //On vide avant de blur
                $(o).val("");
//                $(o).blur();
                
                /*
                 * [07-04-15] @BOR
                 * ETAPE : 
                 * On récupère le dernier commentaire pour permettre de récupérer de potentiels commentaires ajoutés avant celui ci mais qui ne sont pas affichés
                 */
                var lri, lrt;
                var $rmax = $(x).find(".jb-arp-list-rct-max");
                /*
                 * [NOTE 07-04-15] @BOR
                 * Ne considérer que les éléments visibles améliore la stabilité du processus.
                 * Un Commentaire peut être invisible car il est en attente de suppression ou pour une toute autre raison.
                 * Si l'élément est invisible alors il n'est pas disponible pour traitement.
                 */
                if ( $rmax.find(".jb-arp-react-mdl") && $rmax.find(".jb-arp-react-mdl").length && $rmax.find(".jb-arp-react-mdl:visible").last().length && !KgbLib_CheckNullity($rmax.find(".jb-arp-react-mdl:visible").last().data("item")) ) {
                    lri = $rmax.find(".jb-arp-react-mdl:visible").last().data("item");
                    lrt = $rmax.find(".jb-arp-react-mdl:visible").last().data("time");
                }
                
                //On prépare la sentinelle
                var s = $("<span/>");
                
                _f_Save(t,lri,lrt,x,s);
//                _f_Save(x,s,t);
        
                $(s).on("datasready", function(e,b,d) {
                    if ( KgbLib_CheckNullity(b) | KgbLib_CheckNullity(d) ) {
                        return;
                    }
                            
                    //On fait apparaitre le ou les commentaires
//                    _f_PushRct(b,d);
                    
                    /*
                     * [NOTE 07-04-15] @BOR
                     * La ligne ci dessous est necessaire pour pouvoir ajouter le ou les Commentaires dans le bon ordre, mais pas que.
                     * Elle permet de créer un "vrai" objet, pourquoi ? Je n'ai pas l'explication.
                     * Dans le cas contraire, s'il n'y a qu'un élément, each() va itérer les elements de la première ligne comme s'il y avait plusieurs lignes.
                     * Il ne faudra modifier l'instruction qu'en connaissance de cause.
                     */
                    d.rs = $(d.rs).get().reverse();
                    $.each(d.rs,function(x,rd) {
//                        Kxlib_DebugVars([typeof rd,typeof _f_PushRct,rd.opsd],true);
                        //On vérifie que le commentaire n'est pas déjà affiché
                        if (! $(b).find(".jb-arp-react-mdl[data-item='"+rd.itemid+"']").length  ) {
                            //Afficher les commentaires
                            _f_PushRct(b,rd);
                        }
                    });
                    
                    /*
                     * [06-04-15] @BOR
                     * ETAPE :
                     * On scroll jusqu'à la fin de la zone de texte.
                     * [08-05-15] @BOR
                     * On utilise une méthode qui est plus fiable et adaptée.
                     */
//                    $($rmax).animate({scrollTop: $($rmax).height()}, 1000);
                    _f_ScrollZn();
                    
                    //On met à jour le nombre de commentaires
                    _f_UpdReactNb(b,d.arn);
                    
                    //On fait apparaitre un message de notification
                    var Nty = new Notifyzing();
                    Nty.SignalForNewReaction(d.ua_m);
                    
                });
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };

    var _f_Rct_Del = function(x) {
//    this.Delete = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action"))) { 
                return; 
            }
            
            /*
             * [DEPUIS 13-07-15] @BOR
             */
            var $mdl = $(x).closest(".jb-arp-react-mdl");
            var a = $(x).data("action");
            switch (a) {
                case "start_delete" :
                        $mdl.find(".jb-arp-rct-opt-del-mx").addClass("this_hide");
                        $mdl.find(".jb-arp-rct-fnl-dcs-mx").removeClass("this_hide");
                    return;
                case "abort_delete" :
                        $mdl.find(".jb-arp-rct-fnl-dcs-mx").addClass("this_hide");
                        $mdl.find(".jb-arp-rct-opt-del-mx").removeClass("this_hide");
                    return;
                case "confirm_delete" :
                    break;
                default:
                    return;
            }
            
            var t = Kxlib_ValidIdSel($(x).data("target")), i = $(t).data("item"), lz = $(t).closest(".jb-tmlnr-mdl-std"), ai = $(t).closest(".jb-tmlnr-mdl-std").data("item"), s = $("<span/>");
            
            //Envoyer la reference au serveur pour suppression
            _f_Srv_DlRct(i, ai, $(t), lz, s);
            
            //On hide avant de le remove le commentaire. Ceci pour le réafficher au cas où. Mais aussi pour des raisons de confort !
            /*
             * [NOTE 07-04-15] @BOR
             * L'avantage de système est qu'il permet de supprimer visuellement un Commentaire quand ce dernier n'existe pas au niveau de la base de données.
             * Si on tente de supprimer un Commentaire qui n'existe pas, le serveur renvera une erreur mais pour l'utilisateur, cela ne change rien. 
             * De cette manière, on s'assure d'avoir un système stable du point de vue de l'utilisateur.
             */
            $(t).addClass("this_hide");
            
            $(s).on("operended", function(e,d,b) {
                if ( KgbLib_CheckNullity(b) | KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                //Retire définitivement le commentaire
                $(t).remove();
                
                //On met à jour le nombre de commentaires
                _f_UpdReactNb(b,d.arn);
                
                //On notifie la suppression
                var Nty = new Notifyzing();
                Nty.FromUserAction("ua_del_react");
                
                /*
                 * [DEPUIS 13-07-15] @BOR
                 */
                _f_None(lz,"arp_rct");
                
            });
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };

    var _f_UpdReactNb = function (b,rn) {
//    this.UpdateReactNb = function (b) {
        if ( KgbLib_CheckNullity(b) ) {
            return;
        }
        try {
            
            var nb = (KgbLib_CheckNullity(nb)) ? rn : $(b).find(".jb-arp-react-mdl").length;
            $(b).find(".arp_nb_reacts").text(nb);
//        Kxlib_DebugVars([$(b).find(".arp_nb_reacts").length, $(b).find(".jb_b_f_rnb").length,nb],true);
            $(b).find(".jb_b_f_rnb").text(nb);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_ScrollZn = function () {
        try {
            
            var $tar = $(".jb-arp-list-rct-max");
            if ( $tar.find(".jb-arp-react-mdl").length ) {
                var r__ = $tar.find(".jb-arp-react-mdl"), h__ = 0;
                $.each(r__, function(i, rc) {
                    h__ += $(rc).height();
                });
                
                $($tar).animate({scrollTop: h__}, 1500);
            } else {
                $($tar).animate({scrollTop: $($tar).height()}, 1500);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_HdlHvrOpSnc = function (x,ihv){
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(ihv) ) {
                return;
            }
            
            var $el = $(x).closest(".jb-arp-solo-in-acclist");
            if ( ihv ) {
                $(x).addClass("hover");
                
                var nw = parseFloat((new Date()).getTime()), os = parseFloat($el.data("opsnc"));
                var gap = nw - os;
//                Kxlib_DebugVars([ARP - OPSNC (HOVEROUT) => ",nw,os,gap]);
                if ( !KgbLib_CheckNullity(gap) && gap > _f_Gdf().OPSNCLMT ) {
                    /*
                    $el.find(".jb-arp-bot-img-time").removeClass("effi");
                    $el.find(".jb-tmlnr-arp-art-clz-all").removeClass("effi");
                    _f_MvArtDesc($el.find(".jb-arp-bot-img-desc-move[data-action='adesc_move_up']"));
                    //*/
    //                $el.find(".jb-arp-art-desc").removeClass("effi");//NON
    
                     _f_EnaEffi($el,false);
                }
            } else {
                $(x).removeClass("hover");
                
                var nw = parseFloat((new Date()).getTime()), os = parseFloat($el.data("opsnc"));
                var gap = nw - os;
//                Kxlib_DebugVars([ARP - OPSNC (HOVEROUT) => ",nw,os,gap]);
                if ( !KgbLib_CheckNullity(gap) && gap > _f_Gdf().OPSNCLMT ) {
                    /*
                    $el.find(".jb-arp-bot-img-time").addClass("effi");
                    $el.find(".jb-tmlnr-arp-art-clz-all").addClass("effi");
                    _f_MvArtDesc($el.find(".jb-arp-bot-img-desc-move[data-action='adesc_move_down']"));
//                    $el.find(".jb-arp-art-desc").addClass("effi"); //NON
                    //*/
                    _f_EnaEffi($el,true);
                }
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_HdlAutoOpSnc = function (){
        try {
            
            /*
             * On récupère les définition de tous les ARP ouverts
             */
            var sls = $(".jb-arp-solo-in-acclist:not(.this_hide)");
            
            /*
             * S'il existe des définitions on vérifie depuis de temps le modèle est ouvert et décide.
             */
            if ( sls.length ) {
                $.each(sls,function(x,el){
                    if ( !KgbLib_CheckNullity($(el).data("opsnc")) && ( KgbLib_CheckNullity($(el).data("opsnc_autonmr")) || $(el).data("opsnc_autonmr") === false ) ) {
                        var nw = parseFloat((new Date()).getTime()), os = parseFloat($(el).data("opsnc"));
                        var gap = nw - os;
//                        Kxlib_DebugVars([ARP - OPSNC (AUTO) => ",$(el).closest(".jb-tmlnr-mdl-std").attr("id"),nw,os,gap]);
//                        Kxlib_DebugVars([ARP - OPSNC (AUTO) => ",nw,os,gap,gap > _f_Gdf().OPSNCLMT,$(el).find(".jb-arp-bot-img").hasClass("hover"),$(el).data("opsnc_autonmr")]);
                        if ( gap > _f_Gdf().OPSNCLMT && !$(el).find(".jb-arp-bot-img").hasClass("hover") ) {
//                            Kxlib_DebugVars([ARP - OPSNC (AUTO) => ",$(el).closest(".jb-tmlnr-mdl-std").attr("id")]);
                            /*
                            $(el).find(".jb-arp-bot-img-time").addClass("effi");
                            $(el).find(".jb-tmlnr-arp-art-clz-all").addClass("effi");
                            _f_MvArtDesc($(el).find(".jb-arp-bot-img-desc-move[data-action='adesc_move_down']"));
//                            $(el).find(".jb-arp-art-desc").addClass("effi"); //NON
                            //*/
                            _f_EnaEffi(el,true);
                            
                            /*
                             * Permet de ne plus lancer le processus automatiquement.
                             * L'interet étant de ne pas faire des interférences avec le code qui gère le processus HOVER
                             */
                            $(el).data("opsnc_autonmr",true);
                        }
                    }
                });
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Placement = function(arp,am) {
        //arp : ARP; am : ArticleModel
        try {
            if ( ( KgbLib_CheckNullity(am) || !$(am).length ) | KgbLib_CheckNullity($(am).data("item")) ) {
                return;
            }
            
            var $arp = ( !KgbLib_CheckNullity(arp) ) ? arp : $(am).find(".jb-arp-solo-in-acclist");
            if (! $arp.length ) {
                return;
            }
            
            var ai = $(am).data("item");
            //On vérifie s'il s'agit d'un écran 15"
            if ( screen && ( screen.height < _f_Gdf()._15psz.h | screen.width < _f_Gdf()._15psz.w ) ) {
                //On vérifie si l'élément à charger
                if ( $(".jb-tmlnr-mdl-std").first().length && $(".jb-tmlnr-mdl-std").first().data("item") === ai ) {
                    $arp.addClass("edge");
                } else {
                    //On vérifie si la zone Aside est lock
                    var ilk = ( $(".jb-asd-apps-pin-btn").attr("data-state") === "ulock" ) ? true : false;
                    if ( ilk ) {
                        $arp.addClass("edge");
                    }
                }
            } 
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
            
    var _f_OnError = function (ercd,ops) {
        //ercd : ERrorCoDe; ops : OPtionS
        try {
            if ( KgbLib_CheckNullity(ercd) ) {
                return;
            }
            
            switch (ercd) {
                case "_ART_GONE" :
                        /*
                         * [NOTE 15-07-15] @BOR
                         *  J'ai décidé de supprimer l'Article comme pour UNIQUE pour des raisons de facilité.
                         *  Les prochaines versions permettront de mettre en place un moyen plus "doux"
                         */
                        if (! ( ops.hasOwnProperty("aii") && !KgbLib_CheckNullity(ops.aii) ) ) {
                            return;
                        }
                        var aii = ops.aii;
                        $(".jb-tmlnr-mdl-std[data-item='"+aii+"']").remove();
                    break;
                case "_ART_DNY_AKX":
                        /*
                         * [NOTE 15-07-15] @BOR
                         *  J'ai décidé de supprimer l'Article pour des raisons de facilité.
                         *  Les prochaines versions permettront de mettre en place un moyen plus "doux"
                         */
                        if (! ( ops.hasOwnProperty("aii") && !KgbLib_CheckNullity(ops.aii) ) ) {
                            return;
                        }
                        var aii = ops.aii;
                        $(".jb-tmlnr-mdl-std[data-item='"+aii+"']").remove();
                    break;
                default:
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_VidAction = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            var a = $(x).data("action");
            
            var $abx = $(x).closest(".jb-tmlnr-mdl-std");
            if ( KgbLib_CheckNullity($abx) || !$abx.length ) {
                return;
            }
            
            var vid = $abx.find(".jb-arp-bot-img-vid").get(0);
            if ( KgbLib_CheckNullity(vid) ) {
                return;
            }
                    
            switch (a) {
                case "vid-play" :
                        /*
                        vid.play();
                        $(x).data("action","vid-pause");
                        $abx.find(".jb-arp-bot-img-lnch-vid").removeClass("paused");
                                
                        _f_EnaEffi($abx,true);
                        //*/
                        _f_VidPlay(x,vid);
                        
                        _f_EnaEffi($abx,true);
                    break;
                case "vid-pause" :
                        /*
                        vid.pause();
                        $(x).data("action","vid-play");
                        $abx.find(".jb-arp-bot-img-lnch-vid").addClass("paused");
                        
                        _f_EnaEffi($abx,false);
                        //*/
                        _f_VidPause(x,vid);
                        
                        _f_EnaEffi($abx,false);
                    break;
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
            
            var t__ = /[\s\S]+\.([\w]{3,4})\?fmat=([\d]+)x([\d]+)(?:\&|(?:\&amp;))dur=([\d]{1,2})/g.exec(vidu), metas;
            
            if ( Array.isArray(t__) && t__.length ) {
                var w =  parseInt(t__[2]), h =  parseInt(t__[3]), ref = $(".jb-arp-bot-img").width();
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
    
    
    var _f_VidPlay = function(x,vid) {
        try {
            if ( KgbLib_CheckNullity(x) | !$(x).length  ) {
                return;
            }
            
            var $abx = $(x).closest(".jb-tmlnr-mdl-std");
            if ( KgbLib_CheckNullity($abx) || !$abx.length ) {
                return;
            }
            
            if ( KgbLib_CheckNullity(vid) ) {
                vid = $abx.find(".jb-arp-bot-img-vid").get(0);
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
            
            var $abx = $(x).closest(".jb-tmlnr-mdl-std");
            if ( KgbLib_CheckNullity($abx) || !$abx.length ) {
                return;
            }
            
            if ( KgbLib_CheckNullity(vid) ) {
                vid = $abx.find(".jb-arp-bot-img-vid").get(0);
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
    
    /************************************************************************************************************************************************/
    /***************************************************************** AUTO SCOPE *******************************************************************/
    /************************************************************************************************************************************************/
    
    setInterval(function(){
        _f_HdlAutoOpSnc();
    },2000);
    
    /************************************************************************************************************************************************/
    /***************************************************************** SERVER SCOPE *****************************************************************/
    /************************************************************************************************************************************************/
    
    //URQID => Get Infos for ARP from server
    var _Ax_GetARPVerFromSrv = Kxlib_GetAjaxRules("GET_ARP_VIA_POSTID");
    var _Srv_GetARPVerFromSrv = function(i,b,s) {
//    this.Srv_GetARPVerFromSrv = function(i) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(b) | KgbLib_CheckNullity(s) ) {
            return;
        }

        var onsuccess = function(datas) {
            try {
                if (!KgbLib_CheckNullity(datas)) {
                    datas = JSON.parse(datas);
                } else { return; }

                if (! KgbLib_CheckNullity(datas.err) ) {
                     
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE":
                                    _f_OnError("_ART_GONE",{"aii":i});
                                return;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                            case "__ERR_VOL_DNY_AKX":
//                                    Kxlib_AJAX_HandleDeny(); //[20-04-15]
                                    _f_OnError("_ART_DNY_AKX",{"aii":i});
                                break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                return;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                        }
                    } 
                    return;
                    
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var rds = [datas.return,b];
                    $(s).trigger("datasready",rds);
                } else { 
                    return; 
                }

            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
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
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };

        var toSend = {
            "urqid": _Ax_GetARPVerFromSrv.urqid,
            "datas": {
                "i": i
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_GetARPVerFromSrv.url, wcrdtl : _Ax_GetARPVerFromSrv.wcrdtl });
    };
    
    
    //URQID => Get Infos for ARP from server
    var _Ax_PlRcts = Kxlib_GetAjaxRules("ARP_PULL_REACTS");
    var _Srv_PlRcts = function(i,b,s) {
//    this.Srv_ArpPullReacts = function(i,b,s) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(b) | KgbLib_CheckNullity(s) ) {
            return;
        }

        var onsuccess = function(datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    return;
                }

                if (! KgbLib_CheckNullity(datas.err) ) {
                     
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                           case "__ERR_VOL_U_G":
                           case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE":
                                    _f_OnError("_ART_GONE",{"aii":i});
                                return;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DNY_AKX":
                            case "__ERR_VOL_DENY_AKX":
                                    /*
                                     * [NOTE 30-11-14] @author L.C.
                                     * Le refus est surement du au fait que l'utilsateur actif n'a pas le bon type de relation avec le propriétaire de l'Article.
                                     * Pour rappel, seuls les amis de l'utilisateur peuvent voir les commentaires.
                                     * 
                                     * TODO : Faire apparaitre un cadenas
                                     */
//                                    Kxlib_AJAX_HandleDeny();
                                    /*
                                     * [DEPUIS 16-07-15] @BOR
                                     * On retire les commentaires déjà présent
                                     */
                                    $(b).find(".jb-arp-react-mdl").remove();
                                    
                                    /*
                                     * [DEPUIS 13-07-15] @BOR
                                     */
                                    _f_Spnr(b,"arp_rct",false);
                                    _f_None(b,"arp_rct",false);
                                    _f_RctPriv(b,true);
                                break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                return;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                        }
                    } 
                    return;
                    
                } else if ( datas.hasOwnProperty("return") ) {
                    //RAPPEL : Le serveur peut et a le droit de renvoyer NULL. Dans tous les cas, on doit renvoyer le retour au CALLER.
                    var rds = [b,datas.return];
                    $(s).trigger("datasready", rds);
                } else {
                    return;
                }

            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
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
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };

        var toSend = {
            "urqid": _Ax_PlRcts.urqid,
            "datas": {
                "i": i
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlRcts.url, wcrdtl : _Ax_PlRcts.wcrdtl });
    };
    
    //URQID => Supprimer un Article IML depuis ARP
    var _Ax_RmvImlArt = Kxlib_GetAjaxRules("ARP_DEL_ART");
    var _Srv_RmvImlArt = function(i,s) {
//    this.Srv_RemoveImlArt = function(i,s) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) )
            return;
                
        var onsuccess = function(datas) {
            try {
                if (!KgbLib_CheckNullity(datas)) {
                    datas = JSON.parse(datas);
                } else {
                    return;
                }

                if (!KgbLib_CheckNullity(datas.err)) {
                     
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE":
                                    /*
                                     * [30-11-14] 
                                     * On notifie (QUAND MEME) que l'article a été supprimée avec succès
                                     * On ne décrémente pas le nombre d'Articles. Il ne s'agit pas d'une donnée capitale. Quand l'utilisateur va recharger le page, le nombre sera corrigé
                                     */
                                    var Nty = new Notifyzing ();
                                    Nty.FromUserAction("ua_del_art");
                                    _f_OnError("_ART_GONE",{"aii":i});
                                return;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    Kxlib_AJAX_HandleDeny();
                                break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                return;
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                    return;
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    /*
                     * Dans ce cas, le serveur renvoie :
                     *  (1) Le nombre d'Articles du propriétaire de l'Article
                     *  (2) Le capital mis à jour de l'utilisateur
                     */
                    //rds = ReturnDataS
                    var rds = [datas.return];
                    $(s).trigger("operended", rds);
                } else return;

            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function(a,b,c) {
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        /*
         * On envoie l'URL au serveur de telle sorte qu'il puisse détecter si l'utilisateur est sur sa page.
         * Cela lui permettra par la suite de décider d'effectuer des opérations supplémentaires.
         * Dans ce cas très précis, cela lui permettra de renvoyer le capital de l'utilisateur actif.
         */
        var curl = document.URL;
        
        var toSend = {
            "urqid": _Ax_RmvImlArt.urqid,
            "datas": {
                "i": i,
                "cl": curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_RmvImlArt.url, wcrdtl : _Ax_RmvImlArt.wcrdtl });
    };
    
    /************************************************************ ARP_REACTION *********************************************************/
    
    /** Permet de créer le message coté serveur ...
     * ... et de récupérer 
     * Texte : Affichage sécurisé
     * Time : Affichage
     * Nom Complet : Reft + Overlay (amelioration)
     * Pseudo :  Pour l'affichage + Reft
    */
    var _Ax_Save = Kxlib_GetAjaxRules("ADD_R_IN_ARP");
    var _f_Save =  function (m,lri,lrt,b,s) {
//    this.Save =  function (b,s,m) {
        if ( KgbLib_CheckNullity(m) | KgbLib_CheckNullity(b) | KgbLib_CheckNullity($(b).data("item")) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var ai = $(b).data("item");
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
                            case "__ERR_VOL_U_GE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE":
                                    Kxlib_AJAX_HandleFailed("ERR_AX_ART_GONE");
                                    _f_OnError("_ART_GONE",{"aii":ai});
                                return;
                            case "__ERR_VOL_DATAS_MSG":
                            case "__ERR_VOL_SS_MSG":
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                break;
                            case "__ERR_VOL_DENY":
                                    /*
                                     * [NOTE 30-11-14] @author L.C.
                                     * Le refus est surement du au fait que l'utilsateur actif n'a pas le bon type de relation avec le propriétaire de l'Article.
                                     * Pour rappel, seuls les amis de l'utilisateur peuvent voir/ajouter des commentaires.
                                     * 
                                     * TODO : Faire apparaitre un cadenas
                                     */
 //                                    Kxlib_AJAX_HandleDeny();
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;

                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var ds = [b,datas.return];
                    $(s).trigger("datasready",ds);
                } else {
                    return;
                }
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                 Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }

        };

        var onerror = function(a,b,c) {
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };

        var u = document.URL;
        //On récupère l'id de l'article. Dans tous les cas il s'agit d'un Article IML. Nul besoin de repreciser au serveur, il se basera sur urqid.
        
        //i=item; m= message
        var toSend = {
            "urqid": _Ax_Save.urqid,
            "datas": {
                "ai": ai,
                "rm": m,
                "lri": ( !KgbLib_CheckNullity(lri) ) ? lri : null,
                "lrt": ( !KgbLib_CheckNullity(lrt) ) ? lrt : null,
                "curl": u
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_Save.url, wcrdtl : _Ax_Save.wcrdtl });
    };
     
    var _Ax_DlRct = Kxlib_GetAjaxRules("DEL_R_IN_ARP");
    var _f_Srv_DlRct =  function (i,ai,e,b,s) {
//    this.Srv_Delete =  function (b,e,s,i) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(ai) | KgbLib_CheckNullity(e) | KgbLib_CheckNullity(b) |  KgbLib_CheckNullity(s) ) {
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
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE" :
                                    _f_OnError("_ART_GONE",{"aii":ai});
                                return;
                            case "__ERR_VOL_REACT_GONE":
                                    //On fait disparaitre le commentaire. Ainsi, tout reste ransparent et cohérent pour l'uilisateur
                                    $(e).remove();
                                    //On met à jour le compteur de commentaires.
//                                    _f_UpdReactNb(b);
                                return;
                            case "__ERR_VOL_DATAS_MSG":
                            case "__ERR_VOL_SS_MSG":
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                break;
                            case "__ERR_VOL_DENY":
                                    Kxlib_AJAX_HandleDeny();
                                    break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                        }
                    } 
                    return;

                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var ds = [datas.return,b];
                    $(s).trigger("operended",ds);
                } else {
                    return;
                }
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }

        };

        var onerror = function(a,b,c) {
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };

        var toSend = {
            "urqid": _Ax_DlRct.urqid,
            "datas": {
                "ai":ai,
                "i":i
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_DlRct.url, wcrdtl : _Ax_DlRct.wcrdtl });
    };
    
    /****************************************************************************************************************************************************/
    /******************************************************************** VIEW SCOPE ********************************************************************/
    /****************************************************************************************************************************************************/
    
    var _f_Spnr = function(b,scp,sh){
        try {
            if ( KgbLib_CheckNullity(b) | !$(b).length | KgbLib_CheckNullity(scp) ) {
                return;
            }
            
            var $spnr;
            switch (scp) {
                case "arp_rct" :
                        $spnr = $(b).find(".jb-arp-lst-rct-spnr-mx");
                    break;
                default :
                    return;
            }
            
            if (! $spnr.length ) {
                return;
            }
            
            if ( sh === true ) {
                $spnr.removeClass("this_hide");
            } else {
                $spnr.addClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_None = function(b,scp,sh){
        try {
            if ( KgbLib_CheckNullity(b) | !$(b).length | KgbLib_CheckNullity(scp) ) {
                return;
            }
            
            var $nne, $elt;
            switch (scp) {
                case "arp_rct" :
                        $nne = $(b).find(".jb-arp-lst-rct-none-mx");
                        $elt = $(b).find(".jb-arp-react-mdl");
                    break;
                default :
                    return;
            }
            
            if (! $nne.length ) {
                return;
            }
            
            if ( sh === true ) {
                $nne.removeClass("this_hide");
            } else if ( sh === false ) {
                $nne.addClass("this_hide");
            } else {
                if ( $elt.length ) {
                    $nne.addClass("this_hide");
                } else {
                    $nne.removeClass("this_hide");
                }
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_RctPriv = function(b,sh){
        try {
            if ( KgbLib_CheckNullity(b) ) {
                return;
            }
            
            var $prx = $(b).find(".jb-arp-lst-rct-pri-mx");
            if (! $prx.length ) {
                return;
            }
            
            if ( sh === true ) {
                $prx.removeClass("this_hide");
            } else {
                $prx.addClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_RbdArpLstnrs = function (e) {
//    this.RebindArpListeners = function (e) {
        /* 
         * On rebind les listeners 
         * */
        try {
            
            if (KgbLib_CheckNullity(e)) {
                return;
            }
            
            $(e).find(".jb-arp-bot-img-fmr").click(function(e) {
                Kxlib_PreventDefault(e);
                
                _f_OnClose(this);
            });
            
            /* [DEPUIS 13-07-15] @BOR
             $(e).find(".jb-arp-bot-img-fmr").hover(function(){
             //$(this).removeClass("arp-bot-img-fmr").addClass("arp-bot-img-fmr_hover");
             $(this).addClass("arp-bot-img-fmr_hover");
             },function(){
             //$(this).removeClass("arp-bot-img-fmr_hover").addClass("arp-bot-img-fmr");
             $(this).removeClass("arp-bot-img-fmr_hover");
             });
             */
            /*
             * [DEPUIS 19-06-15] @BOR
             */
            $(e).find(".jb-tmlnr-arp-art-clz-all").click(function(e) {
                Kxlib_PreventDefault(e);
                gt.CheckOperation(this);
            });
            
            $(e).find(".jb-arp-prmlk-cloz").click(function(e) {
                Kxlib_PreventDefault(e);
                Kxlib_StopPropagation(e);
                
                $(this).closest(".jb-arp-prmlk-sprt").addClass("this_hide");
//            $(this).parent().parent().parent().find(".jb-arp-bot-img-fade").toggleClass("arp-bot-img-fade-gl");
            });
            
            //Trigger de l'ajout du commentaire
            $(e).find(".jb-arp-nw-react-trg").off().click(function(e) {
//        $(e).find(".jb-arp-nw-react-trg").click(function(e){
                Kxlib_PreventDefault(e);
                _f_AddRct(this);
            });
            
            //Controle sur le focus
            $(e).find(".jb-arp-input").focus(function() {
                $(this).removeClass("error_field");
            });
            
            //Supprimer un commentaire
            $(e).find(".jb-arp-rct-del, .jb-arp-rct-fnl-dc").click(function(e) {
                Kxlib_PreventDefault(e);
                //        alert($(e.target).data("target"));
                _f_Rct_Del(e.target);
            });
            
            /****** REBIND BOUTON ACTION ******/
            
            $(e).find(".action_a").focusout(function(e) {
                //        return;
                if (e.target === this) {
                    e.stopPropagation();
//                Kxlib_DebugVars([Lost Focus!"]);
//                Kxlib_DebugVars([Event Was : "+e.type]);
                    $(this).parent().children(".action_foll_choices").addClass("this_hide");
                    //La ligne ci-dessous causait un bug : too much recursion a cause d'un 'bubbling tree'
                    //            $(this).blur();
                }
            });
            
            
            $(e).find(".action_a").click(function(e) {
                Kxlib_PreventDefault(e);
                //        e.stopPropagation();
                
                $(this).focus();
                
                if (!$(this).parent().children(".action_foll_choices").hasClass("this_hide")) {
                    $(this).parent().children(".action_foll_choices").addClass("this_hide");
                    $(this).blur();
//                Kxlib_DebugVars([Ready to retrun !"]);
                    return;
                }
                $(".action_foll_choices").not(this).addClass("this_hide");
                $(this).parent().children(".action_foll_choices").toggleClass("this_hide");
            });
            
            $(e).find(".action_a").hover(function() {
//           Kxlib_DebugVars([Hover Action_a !"]);     
            }, function() {
                
            });
            
            /****** REBIND CONFIRM ACTION ******/
            
            
            $(e).find(".jb-arp-del-sbchcs").off().click(function(e) {
                Kxlib_PreventDefault(e);
                
                gt.CheckOperation(this);
            });
            
            /******************* EVAL *********************/
            
//        alert($(e).find(".jb-csam-eval-choices").length);
            $(e).find(".jb-csam-eval-choices").click(function(e) {
                Kxlib_PreventDefault(e);
                
                if ( typeof EVALBOX === 'function' ) {
                    (new EVALBOX()).Action(e.target);
                }
            });
            
            $(e).find(".css-c-e-chs-scl").hover(function() {
//            Kxlib_DebugVars([Leave Heart! => "+$(this).parent().find(".css-c-e-chs-cl").hasClass("active")]);
                if (!$(this).parent().find(".css-c-e-chs-cl").hasClass("active")) {
                    $(this).parent().find(".css-c-e-chs-cl").addClass("css-c-e-chs-cl_hover");
                }
            }, function() {
//            Kxlib_DebugVars([Leave Heart! => "+$(this).parent().find(".css-c-e-chs-cl").hasClass("active")]);
                if (!$(this).parent().find(".css-c-e-chs-cl").hasClass("active")) {
                    $(this).parent().find(".css-c-e-chs-cl").removeClass("css-c-e-chs-cl_hover");
                }
            });
            
            /*
             * [DEPUIS 13-07-15] @BOR
             */
            $(e).find(".jb-arp-bot-img-desc-move").off().click(function(e) {
                Kxlib_PreventDefault(e);

                gt.CheckOperation(this);
            });
            
            /*
             * [DEPUIS 13-07-15] @BOR
             */
            $(e).find(".jb-arp-bot-img").off().hover(function(){
//                Kxlib_DebugVars([ADESC HOVERIN REBIND"]);
                _f_HdlHvrOpSnc(this,true);
            },function(){
//                Kxlib_DebugVars([ADESC HOVEROUT REBIND"]);
                _f_HdlHvrOpSnc(this,false);
            });
            
           /*
            * [DEPUIS 13-07-15] @BOR
            */
            $(e).find(".jb-arp-input").off().keypress(function(e){
                if ( (e.which && e.which === 13) || (e.keyCode && e.keyCode === 13) ) {
                    Kxlib_PreventDefault(e);
                    
                    var btn = $(this).closest(".arp-nw-react-ipt-mx").find(".jb-arp-nw-react-trg");
                    $(btn).click();
                }
            });
            
            /*
             * [DEPUIS 31-03-16]
             */
            $(e).find(".jb-arp-bot-img-lnch-vid").off().click(function(e){
                Kxlib_PreventDefault(e);
                    
                _f_VidAction(this);
            });
            
            return $(e);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_PprNewArp = function (d,b) {
//    this.PrepareNewArp = function (d) {
        try {
            if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(b) ) {
                return;
            }
        
            var evals_join = (!KgbLib_CheckNullity(d.art_evals)) ? d.art_evals.join() : "[0,0,0,0]";
//        Kxlib_DebugVars([41]);
            var evals_tot = (!KgbLib_CheckNullity(d.art_evals)) ? d.art_evals[3] : "0";
//        Kxlib_DebugVars([43]);
            var art_nb = (!KgbLib_CheckNullity(d.art_reacts)) ? Kxlib_ObjectChild_Count(d.art_reacts) : 0;
//        Kxlib_DebugVars([45]);
            var dsc = Kxlib_Decode_After_Encode(d.art_desc);
//        Kxlib_DebugVars([47]);

            /*
             * [DEPUIS 31-03-16]
             */
            var vid_metas;
            if (! KgbLib_CheckNullity(d.vidu) ) {
                vid_metas = _f_MagicVid(d.vidu);
//                Kxlib_DebugVars([JSON.stringify(vid_metas),vid_metas.loop],true);
            }
            
            //Construction de la vue
            var e = "<div class=\"arp-solo-in-acclist jb-arp-solo-in-acclist this_hide\">";
            e += "<div class=\"rich-unik-acclist-child rich-unik-acclist-left\">";
            e += "<div>";
            e += "<div class=\"rich-post-left-header\">";
            e += "<div class=\"rich-post-left-header-grpuser-max\">";
            e += "<p class=\"rich-post-left-header-grpuser\">";
            e += "<a class=\"rich-post-left-header-grpuser-link\" title=\"" + d.aofn + "\" href=\"" + d.aohref + "\">";
            e += "<span class=\"arp-lft-hdr-grpu-i-fade\"></span>";
            e += "<img class=\"rich-post-left-header-grpuser-link-img\" width=\"45\" height=\"45\" src=\"" + d.aoppic + "\" />";
            e += "<span class=\"rich-post-left-header-grpuser-psd\">@" + d.aopsd + "</span>";
            e += "</a>";
            e += "</p>";
            e += "</div>";
            /*
            e += "<p class=\"rich-post-left-header-hash\">";
            if (!KgbLib_CheckNullity(d.art_hashs)) {
                $.each(d.art_hashs, function(k, v) {
                    e += "<a class=\"arp-hash-elt-lnk\" href=\"javascript:;\">#" + v + "</a>";
                });
            }
            e += "</p>";
            //*/
            e += "<div class=\"jb-csam-eval-box css-eval-box css-eval-box-tmlnr arp\">";
            e += "<div class=\"jb-eval-dplw-bar-mx\">";
            e += "<span class=\"jb-csam-eval-oput css-csam-eval-oput arp\" data-cache=\"" + evals_join + "\"><span>" + evals_tot + "</span>&nbsp;coo<i>!</i></span>";
            e += "<div class=\"eval-dplw-bar arp\">";
            if ( d.te_ena ) {
                e += "<span class=\"css-csam-eval-chs-wrp\">";
                e += "<span class=\"evlbx-ch-nb jb-evlbx-ch-nb\" data-scp=\"scl\">"+d.art_evals[0]+"</span>";
                e += "<a id=\"\" class=\"jb-csam-eval-choices jb-csam-eval-spcool css-csam-eval-chs css-c-e-chs-scl\" data-action=\"rh_spcl\" data-zr=\"rh_spcl\" data-rev=\"bk_spcl\" data-target=\"post-accp-myl-id" + d.art_eid + "\" data-xc=\"arp\" title=\"SupaCool\" href=\"javascript:;\" role=\"button\"></a>";
                e += "</span>";
                e += "<span class=\"css-csam-eval-chs-wrp\">";
                e += "<span class=\"evlbx-ch-nb jb-evlbx-ch-nb\" data-scp=\"cl\">"+d.art_evals[1]+"</span>";
                e += "<a id=\"\" class=\"jb-csam-eval-choices jb-csam-eval-cool css-csam-eval-chs css-c-e-chs-cl\" data-action=\"rh_cool\" data-zr=\"rh_cool\" data-rev=\"bk_cool\" data-target=\"post-accp-myl-id" + d.art_eid + "\" data-xc=\"arp\" title=\"J'adhère\" href=\"javascript:;\" role=\"button\"></a>";
                e += "</span>";
                e += "<span class=\"css-csam-eval-chs-wrp\">";
                e += "<span class=\"evlbx-ch-nb jb-evlbx-ch-nb\" data-scp=\"dlk\">"+d.art_evals[2]+"</span>";
                e += "<a id=\"\" class=\"jb-csam-eval-choices jb-csam-eval-dislk css-csam-eval-chs css-c-e-chs-dsp\" data-action=\"rh_dislk\" data-zr=\"rh_dislk\" data-rev=\"bk_dislk\" data-target=\"post-accp-myl-id" + d.art_eid + "\" data-xc=\"arp\" title=\"J'adhère pas\" href=\"javascript:;\" role=\"button\"></a>";
                e += "</span>";
            } else if ( d.isrtd ) {
                e += "<span class=\"css-csam-eval-chs-wrp\">";
                e += "<span class=\"evlbx-ch-nb jb-evlbx-ch-nb\" data-scp=\"cl\">"+d.art_evals[1]+"</span>";
                e += "<a id=\"\" class=\"jb-csam-eval-choices jb-csam-eval-cool css-csam-eval-chs css-c-e-chs-cl\" data-action=\"rh_cool\" data-zr=\"rh_cool\" data-rev=\"bk_cool\" data-target=\"post-accp-myl-id" + d.art_eid + "\" data-xc=\"arp\" title=\"J'adhère\" href=\"javascript:;\" role=\"button\"></a>";
                e += "</span>";
            } else {
                e += "<span class=\"css-csam-eval-chs-wrp\">";
                e += "<span class=\"evlbx-ch-nb jb-evlbx-ch-nb\" data-scp=\"cl\">"+d.art_evals[1]+"</span>";
                e += "<a id=\"\" class=\"jb-irr css-csam-eval-chs css-c-e-chs-cl\" title=\"J'adhère\" href=\"javascript:;\" role=\"button\"></a>";
                e += "</span>";
            }
            e += "</div>";
            e += "</div>";
            e += "<div class=\"eval-wait-bar jb-eval-wait-bar this_hide\">";
            e += "<div class=\"eval-wt-bar-pgrs\"></div>";
            e += "</div>";
            e += "</div>";
            if ( d.af_ena !== false ) {
                e += "<div class=\"action_maximus arp\">";
                e += "<a href=\"#\" class=\'action_a  arp\'><span class=\'brain_sp_k\'>A</span><span class=\'brain_sp_action\'>ction<span></a>";
                e += "<ul class=\'action_foll_choices this_hide\'>";
                if ( $.isArray(d.af_ena) && $.inArray("del",d.af_ena) !== -1 ) {
                    e += "<li><a href=\"javascript:;\" class=\'afl_choice del-the-post\' data-action=\"del_start\" data-target=\"post-accp-myl-id" + d.art_eid + "\" role='button' \">Supprimer</a></li>";
                }
                if ( $.isArray(d.af_ena) && $.inArray("pml",d.af_ena) !== -1 ) {
                    e += "<li><a href=\"javascript:;\" class=\'afl_choice get-link-trig\' data-action=\"rp-get-lk\" data-target=\"post-accp-myl-id" + d.art_eid + "\" role='button' \">Lien permanent</a></li>";
                }
                e += "</ul>";            
                e += "</div>";
            }
            e += "</div>";
            e += "<div class=\"rich-post-left-list-comnt-new-max\">";
            e += "<div class=\"arp-nw-react-ipt-mx\">";
            if ( d.te_ena ) {
                e += "<textarea class=\"arp-nw-react-ipt jb-arp-input\"/></textarea>";
                e += "<a class=\"arp-nw-react-trg jb-arp-nw-react-trg\" href=\"javascript:;\" role=\"button\" data-target=\"arp-nw-react-ipt\">Réagir</a>";
            } else if ( d.isrtd ) {
                e += "<textarea class=\"arp-nw-react-ipt\" disabled/></textarea>";
                e += "<a class=\"arp-nw-react-trg\" href=\"javascript:;\" role=\"button\">Réagir</a>";
            } else {
                e += "<textarea class=\"arp-nw-react-ipt\" disabled/></textarea>";
                e += "<a class=\"jb-irr arp-nw-react-trg\" href=\"javascript:;\" role=\"button\">Réagir</a>";
            }
            e += "</div>";
            e += "</div>";
            e += "<div class=\"arp-list-rct-mx jb-arp-list-rct-max\">";
//        Kxlib_DebugVars([021]);
            e += "<span class=\"arp-lst-rct-spnr-mx jb-arp-lst-rct-spnr-mx\"><i class=\"fa fa-refresh fa-spin\"></i></span>";
            e += "<span class=\"arp-lst-rct-none-mx jb-arp-lst-rct-none-mx this_hide\"><i class=\"fa fa-exclamation-circle\"></i></span>";
            e += "<div class=\"arp-lst-rct-pri-mx jb-arp-lst-rct-pri-mx this_hide\">";
            e += "<div class=\"arp-lst-rct-pri-lgo\"></div>";
            e += "<div class=\"arp-lst-rct-pri-txt\">Privé</div>";
            e += "</div>";
            e += "</div>";
            e += "<div>";
            e += "<p class=\"arp-left-lst-rct-cn\"><span class=\"arp_nb_reacts\">" + art_nb + "</span>&nbsp;commentaires</p>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e += "<div class=\"rich-unik-acclist-child rich-unik-acclist-right\">";
            e += "<div class='arp-cfrm-bx-mx jb-arp-cfrm-bx-mx this_hide'>";
            e += "<div class='arp-cfrm-bx-top'>";
            e += "<span class='arp-cfrm-bx-tle'>La publication sera supprimée, définitivement !</span>";
            e += "</div>";
            e += "<div class='arp-cfrm-bx-btm'>";
            e += "<a class='arp-del-sbchcs jb-arp-del-sbchcs' data-action='del_confirm' data-target=\"post-accp-myl-id" + d.art_eid + "\" href='javascript:;'>OK</a>";
            e += "<a class='arp-del-sbchcs jb-arp-del-sbchcs' data-action='del_abort' data-target=\"post-accp-myl-id" + d.art_eid + "\" href='javascript:;'>Nooon<i>!</i></a>";
            e += "</div>";
            e += "</div>";
            e += "<div class=\"arp-bot-img jb-arp-bot-img\">";
            
            if ( d.vidu ) {
                e += "<span class=\"arp-bot-img-fade vidu jb-arp-bot-img-fade\">";
                e += "<a class=\"arp-bot-img-lnch-vid jb-arp-bot-img-lnch-vid paused\" data-action=\"vid-play\"></a>";
                e += "</span>";
                e += "<div class=\"arp-bot-img-vid-mx jb-arp-bot-img-vid-mx\">";
                e += "<video class=\"arp-bot-img-vid jb-arp-bot-img-vid\" height=\""+vid_metas.height+"\" width=\""+vid_metas.width+"\" src=\""+d.vidu+"\" preload=\"auto\" loop >";
                e += "</video>";
                e += "</div>";
            } else {
                e += "<span class=\"arp-bot-img-fade jb-arp-bot-img-fade\"></span>";
                e += "<img class=\"arp-bot-img-img\" height=\"550\" width=\"550\" src=\"" + $(b).find(".fcb_img_img").attr("src") + "\"/>";
            }
            
            e += "<span class=\"arp-bot-img-time jb-arp-bot-img-time\">";
            e += "<span class = 'kxlib_tgspy' data-tgs-crd=\"" + d.art_time + "\" data-tgs-dd-atn='' data-tgs-dd-uut='' >";
            e += "<span class='tgs-frm'></span>";
            e += "<span class='tgs-val'></span>";
            e += "<span class='tgs-uni'></span>";
            e += "</span>";
            e += "</span>";
            e += "<a class=\"arp-bot-img-fmr jb-arp-bot-img-fmr\" data-target=\"post-accp-myl-id" + d.art_eid + "\" href=\"javascript:;\">&times;</a>";
            e += "<a class=\"tmlnr-arp-art-clz-all jb-tmlnr-arp-art-clz-all\" data-action='close_all' href=\"javascript:;\">Tout Fermer</a>";
            e += "<div class=\"arp-bot-img-desc jb-arp-art-desc\">";
            e += "<a class=\"arp-bot-img-desc-move jb-arp-bot-img-desc-move\" data-action=\"adesc_move_up\" href=\"javascript:;\"></a>";
            e += "<a class=\"arp-bot-img-desc-move jb-arp-bot-img-desc-move\" data-action=\"adesc_move_down\" href=\"javascript:;\"></a>";
//            e += "<span class=\"jb-arp-art-desc-txt\">"+dsc+"</span>";
            e += "<span class=\"jb-arp-art-desc-txt\"></span>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e += "<div class=\"arp-prmlk-sprt jb-arp-prmlk-sprt this_hide\">";
            e += "<div class=\"rich-post-get-link-max jb-arp-prmlk-mx\">";
            e += "<div class=\"rich-post-get-link-header\">";
            e += "<span class=\"rich-post-get-link-header-text\">A partager sans modération avec qui vous voulez, où vous voulez !</span>";
            e += "<a class=\"arp-prmlk-cloz jb-arp-prmlk-cloz\" href=\"javascript:;\" role=\"button\">&times;</a>";
            e += "</div>";
            
            e += "<div class=\"arp-get-link-ipt-mx jb-arp-pmlk-output-mx\">";
            e += "<div>";
            e += "<textarea class=\"arp-pmlk-output jb-arp-pmlk-output\" type=\"text\" value=\"[%permalink%] readonly\"></textarea>";
            e += "</div>";
            e += "<div id=\"arp-get-link-ipt-ftr\">";
            e += "<a id=\"arp-get-link-ipt-gt\" class=\"jb-arp-get-link-ipt-gt\" href=\"javascript:;\">Aller vers</a>";
            e += "</div>";
            e += "</div>";
            /*
            e += "<div class=\"rich-post-get-link-input jb-arp-pmlk-output-mx\">";
            e += "<textarea class=\"arp-pmlk-output jb-arp-pmlk-output\" type=\"text\" value=\"[%permalink%]\" readonly='true'></textarea>";
            e += "</div>";
            //*/
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            
            e = $.parseHTML(e);
            
            /*
             * [DEPUIS 29-03-16]
             */
            //On récupère le modèle de Page
            var b_ = $(b).closest(".jb-tmlnr-mdl-std");
            if ( d.vidu ) {
                $(b_).data("vidu",d.vidu).attr("data-vidu",d.vidu);
//                $(b_).find(".jb-arp-bot-img-vid").prop("src",d.vidu);
                if (! vid_metas.loop ) {
                    $(e).find(".jb-arp-bot-img-vid").removeProp("loop");
                }
            }
            
            /*
             * [NOTE 07-04-15] @BOR
             * La fonctionnalité a été déplacé et refactorisée ici pour pouvoir faire fonctionner le mecanisme de reconnaissance des Usertags.
             * En effet, cette solution se révèle plus souple pour le developpeur.
             * ETAPE : 
             * On traire le cas des Commentaires s'ils existent.
             */
            if (! KgbLib_CheckNullity(d.art_reacts) && Kxlib_ObjectChild_Count(d.art_reacts) ) {
//            Kxlib_DebugVars([93]);
                _f_RctPriv(e,false);
                _f_None(e,"arp_rct",false);
                _f_Spnr(e,"arp_rct",false);

                var rs = $(d.art_reacts).get().reverse();
                $.each(rs, function(k,v) {
                    
                    var str__;
                    if ( !KgbLib_CheckNullity(v.ustgs) && v.hasOwnProperty("ustgs") && v.ustgs !== undefined && typeof v.ustgs === "object" ) {
                        var istgs__ = [];
                        $.each(v.ustgs, function(x,v) {
                            var rw__ = [];
                            $.map(v, function(e,x) {
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
                            
//                Kxlib_DebugVars([035]);
                    var re = "<div id=\"arp-react-" + v.itemid + "\" class=\"arp-left-lst-rct-mdl jb-arp-react-mdl\" data-item=\""+v.itemid+"\" data-aid=\"" + v.oeid + "\" data-au-fn=\"" + v.ofn + "\" data-au-psd=\"" + v.opsd + "\" data-time=\"" + v.time + "\" ";
                    re += " data-with=\"" + Kxlib_ReplaceIfUndefined(str__) + "\" ";
                    re += " >";
                    re += "<div class=\"arp-rct-hdr\">";
                    re += "<a class=\"arp-rct-hdr-link\" href=\"/"+v.opsd+"\">";
                    re += "<span class=\"arp-rct-hdr-i-fade\"></span>";
                    re += "<img class=\"arp-rct-hdr-img\" width='35' height='35' src=\"" + v.oppic + "\"/>";
                    re += "<span>@" + v.opsd + "</span>";
                    re += "</a>";
                    re += "<span class=\"arp-rct-hdr-time\">";
                    re += "<span class=\"kxlib_tgspy arp-react-tqspy\" data-tgs-crd=\"" + v.time + "\" data-tgs-dd-atn='' data-tgs-dd-uut='' >";
                    re += "<span class='tgs-frm'></span>";
                    re += "<span class='tgs-val'></span>";
                    re += "<span class='tgs-uni'></span>";
                    re += "</span>";
                    re += "</span>";
                    re += "</div>";
                    re += "<div class=\"arp-rct-txt jb-arp-rct-txt\"></div>"; 
                    re += "<ul class=\"arp-rct-opts-mx\">";
                    if ( v.cdel ) {
                        re += "<li class=\"arp-rct-opt-mx\" >";
                        re += "<div class=\"arp-rct-opt-del-mx jb-arp-rct-opt-del-mx\">";
                        re += "<a class=\"arp-rct-del cursor-pointer jb-arp-rct-del\" data-action=\"start_delete\" data-target=\"arp-react-" + v.itemid + "\" role=\"button\" title=\"Supprimer le commentaire\" ></a>";
                        re += "</div>";
                        re += "<div class=\"arp-rct-fnl-dcs-mx jb-arp-rct-fnl-dcs-mx this_hide\">";
                        re += "<span class=\"unq-rct-fnl-dc-lbl\">Confirmer ?</span>";
                        re += "<span class=\"unq-rct-fnl-dc-tgr-mx\">";
                        re += "<a class=\"unq-rct-fnl-dc-tgr cursor-pointer jb-arp-rct-fnl-dc\" data-action=\"confirm_delete\" data-target=\"arp-react-" + d.itemid + "\" role=\"button\">Oui</a>";
                        re += "<a class=\"unq-rct-fnl-dc-tgr cursor-pointer jb-arp-rct-fnl-dc\" data-action=\"abort_delete\" data-target=\"arp-react-" + d.itemid + "\" role=\"button\">Non</a>";
                        re += "</span>";
                        re += "</div>";
                        re += "</li>";
                    }
                    re += "</ul>";
                    re += "</div>";
//                    jb-arp-list-rct-max
                    re = $.parseHTML(re);
                    
                    /*
                     * ETPAE :
                     * Traitement du texte de description pour qu'il puisse en compte les Usertags.
                     */
                    
                    /*
                     var t__ = v.body;
        //            var t__ = Kxlib_Decode_After_Encode(v.adesc);
                    if ( str__ && str__.length ) {
                        t__ = $("<div/>").html(t__).text();
                        
                        var ustgs = Kxlib_DataCacheToArray(str__)[0];
        //                Kxlib_DebugVars([Kxlib_ObjectChild_Count(v.austgs),ustgs[3]],true);
                        var ps = ( ustgs && $.isArray(ustgs[0]) ) ? Kxlib_GetColumn(3,ustgs) : [ustgs[3]];
                        t__ = Kxlib_UsertagFactory(t__,ps,"tqr-unq-user");
                        
//                        $(re).find(".jb-arp-rct-txt").text(t__);
//                        t__ = $(re).find(".jb-arp-rct-txt").text();
                        t__ = Kxlib_SplitByUsertags(t__);
                            
                        $(re).find(".jb-arp-rct-txt").html(t__);
                    } else {
                        t__ = $("<div/>").html(t__).text();
                        $(re).find(".jb-arp-rct-txt").text(t__);
                    }
                    //*/
                    
                    /*
                     * [DEPUIS 29-04-16]
                     */
                    var r_otxt = Kxlib_Decode_After_Encode(v.body);
                    atxt = $("<div/>").html(r_otxt).text();

                    var r_ustgs = v.ustgs;
                    var r_hashs = v.hashs;

    //                Kxlib_DebugVars([ajca_o.hashs,ustgs],true);
                    //rtxt = RenderedText
                    var r_rtxt = Kxlib_TextEmpow(r_otxt,r_ustgs,r_hashs,null,{
                        emoji : {
                            "size"          : 36,
                            "size_css"      : 16,
                            "position_y"    : 2
                        }
                    });
                    $(re).find(".jb-arp-rct-txt").text("").append(r_rtxt);
                    
                    $(e).find(".jb-arp-list-rct-max").append(re);
                });
            } else if ( d.hasOwnProperty("rakx_ena") && d.rakx_ena === false ) {
                _f_Spnr(e,"arp_rct",false);
                _f_None(e,"arp_rct",false);
                _f_RctPriv(e,true);
            } else {
                _f_RctPriv(e,false);
                _f_Spnr(e,"arp_rct",false);
                _f_None(e,"arp_rct",true);
            }
            
            /*
             * [DEPUIS 29-04-16]
             */
            var atxt = dsc;
            atxt = $("<div/>").html(atxt).text();
//                Kxlib_DebugVars(["'"+$(am).data("ajcache")+"'"],true);

            var ustgs = d.art_ustgs;
            var hashs = d.art_hashs;

//                Kxlib_DebugVars([ajca_o.hashs,ustgs],true);
            //rtxt = RenderedText
            var rtxt = Kxlib_TextEmpow(atxt,ustgs,hashs,null,{
                emoji : {
                    "size"          : 36,
                    "size_css"      : 20,
                    "position_y"    : 3
                }
            });
            $(e).find(".jb-arp-art-desc-txt").text("").append(rtxt);
            
            
            //On met à jour le nombre de commentaires au niveau du modèle en page
//        Kxlib_DebugVars([$(b_).length, d.art_reacts.length],true);
            $(b_).find(".jb_b_f_rnb").text(art_nb);
            
            return e;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    
    var _f_PushRct = function(b,d) {
//    this._f_PushRct = function(b,d) {
//    this.DisplaySingleReaction = function(b,d) {
        //b= Objet Article, d = datas, les données
        try {
            
            /*
             * [DEPUIS 13-07-15] @BOR
             */
            _f_RctPriv(b,false);
            _f_None(b,"arp_rct",false);
            _f_Spnr(b,"arp_rct",false);
            
            var $tar = $(b).find(".jb-arp-list-rct-max");
            
            //On créé l'élément
            var r = _f_Rct_Ppr(d);
            
            //On rebind l'élément
            r = _f_Rct_Rbd(r);
            
            //Ajout dans la liste
            $(r).hide().appendTo($tar); //Depuis 06-04-15
//        $(r).hide().prependTo($tar); //Depuis 16-12-14
//        $(r).hide().appendTo($tar);
//        $(r).hide().prependTo($tar);
            
            var Tg = new TIMEGOD();
            Tg.UpdSpies();
            
            $(r).fadeIn();
            
            /* NON C'est au CALLER de le faire
             //Mise à jour du nombre de commentaires
             var cn = $tar.find(".arp-left-lst-rct-mdl").length;
             $(b).find(".arp_nb_reacts").html(cn);
             //*/
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_Rct_Ppr = function(d) {
        if ( KgbLib_CheckNullity(d) ) {
            return;
        }
        try {
            
            /*
             * RAPPEL sur les clés pour chaque Commentaire retourné
             * "itemid",
             "oeid",
             "ofn" 
             "opsd" 
             "oppic" 
             "ohref" 
             "body" 
             "time" 
             "utc" //Obselete à vb1
             */
            
//        Kxlib_DebugVars([d.itemid,d.oeid,d.ofn,d.opsd,d.oppic,d.ohref,d.body,d.time,d.utc],true);
            
            var str__;
            if ( !KgbLib_CheckNullity(d.ustgs) && d.hasOwnProperty("ustgs") && d.ustgs !== undefined && typeof d.ustgs === "object" ) {
                var istgs__ = [];
                $.each(d.ustgs, function(x,v) {
                    var rw__ = [];
                    $.map(v, function(e,x) {
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
            
            //Construction du modèle du formulaire
            var r = "<div id=\"arp-react-" + d.itemid + "\" class=\"arp-left-lst-rct-mdl jb-arp-react-mdl\" data-item=\"" + d.itemid + "\" data-aid=\"" + d.oeid + "\" data-au-fn=\"" + d.ofn + "\" data-au-psd=\"" + d.opsd + "\" data-time=\"" + d.time + "\" ";
            r += " data-with=\"" + Kxlib_ReplaceIfUndefined(str__) + "\"";
            r += " >";
            r += "<div class=\"arp-rct-hdr\">";
            r += "<a class=\"arp-rct-hdr-link\" href=\"/"+d.opsd+"\">";
            r += "<span class=\"arp-rct-hdr-i-fade\"></span>";
            r += "<img class=\"arp-rct-hdr-img\" width=\"35\" height=\"35\" src=\"" + d.oppic + "\"/>";
            r += "<span>@" + d.opsd + "</span>";                
            r += "</a>";                    
            r += "<span class=\"arp-rct-hdr-time\">";
            r += "<span class=\"kxlib_tgspy arp-react-tqspy\" data-tgs-crd=\"" + d.time + "\" data-tgs-dd-atn='' data-tgs-dd-uut='' >";
            r += "<span class='tgs-frm'></span>";
            r += "<span class='tgs-val'></span>";
            r += "<span class='tgs-uni'></span>";
            r += "</span>";
            r += "</span>";                        
            r += "</div>";                        
            r += "<div class=\"arp-rct-txt jb-arp-rct-txt\"></div>";  
            r += "<ul class=\"arp-rct-opts-mx \">";
            if (d.cdel) {
                r += "<li class=\"arp-rct-opt-mx\" >";
                r += "<div class=\"arp-rct-opt-del-mx jb-arp-rct-opt-del-mx\">";
                r += "<a class=\"arp-rct-del cursor-pointer jb-arp-rct-del\" data-action=\"start_delete\" data-target=\"arp-react-" + d.itemid + "\" title=\"Supprimer le commentaire\" ></a>";
                r += "</div>";
                r += "<div class=\"arp-rct-fnl-dcs-mx jb-arp-rct-fnl-dcs-mx this_hide\">";
                r += "<span class=\"unq-rct-fnl-dc-lbl\">Confirmer ?</span>";
                r += "<span class=\"unq-rct-fnl-dc-tgr-mx\">";
                r += "<a class=\"unq-rct-fnl-dc-tgr cursor-pointer jb-arp-rct-fnl-dc\" data-action=\"confirm_delete\" data-target=\"arp-react-" + d.itemid + "\" role=\"button\">Oui</a>";
                r += "<a class=\"unq-rct-fnl-dc-tgr cursor-pointer jb-arp-rct-fnl-dc\" data-action=\"abort_delete\" data-target=\"arp-react-" + d.itemid + "\" role=\"button\">Non</a>";
                r += "</span>";
                r += "</div>";
                r += "</li>";
            }
            r += "</ul>";
            r += "</div>";      
            
            r = $.parseHTML(r);
            
            /*
             * ETPAE :
             * Insertion du texte de description.
             */
            /*
            var t__ = d.body;
//            var t__ = Kxlib_Decode_After_Encode(d.adesc);
            if ( str__ && str__.length ) {
                
                var ustgs = Kxlib_DataCacheToArray(str__)[0];
//                Kxlib_DebugVars([Kxlib_ObjectChild_Count(d.austgs),ustgs[3]],true);
                var ps = ( ustgs && $.isArray(ustgs[0]) ) ? Kxlib_GetColumn(3,ustgs) : [ustgs[3]];
                t__ = Kxlib_UsertagFactory(t__,ps,"tqr-unq-user");
                
                $(r).find(".jb-arp-rct-txt").text(t__);
                t__ = $(r).find(".jb-arp-rct-txt").text();
                t__ = Kxlib_SplitByUsertags(t__);
                
                $(r).find(".jb-arp-rct-txt").html(t__);
            } else {
                t__ = $("<div/>").html(t__).text();
                $(r).find(".jb-arp-rct-txt").text(t__);
            }
            //*/
           /*
            * [DEPUIS 29-04-16]
            */
            var r_otxt = Kxlib_Decode_After_Encode(d.body);
//            atxt = $("<div/>").html(r_otxt).text();

            var r_ustgs = d.ustgs;
            var r_hashs = d.hashs;

//                Kxlib_DebugVars([ajca_o.hashs,ustgs],true);
           //rtxt = RenderedText
            var r_rtxt = Kxlib_TextEmpow(r_otxt,r_ustgs,r_hashs,null,{
                emoji : {
                    "size"          : 36,
                    "size_css"      : 16,
                    "position_y"    : 2
                }
            });
            $(r).find(".jb-arp-rct-txt").text("").append(r_rtxt);
            
            return r;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Rct_Rbd = function (e) {
        try {
            
            if ( KgbLib_CheckNullity(e) ) {
                return;
            }
            
            if (! $(e).find(".jb-arp-rct-del").length ) {
                return e;
            }
            
            //On rebind le listener
            $(e).find(".jb-arp-rct-del, .jb-arp-rct-fnl-dc").click(function(e) {
                Kxlib_PreventDefault(e);
                _f_Rct_Del(this);
            });
            
            return e;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /**
     * Permet de résuire/developper la zone DESC de ARP.
     * Dans le cas où aucune référence n'est envoyée, on traite tous les cas.
     * 
     * @param {type} x Le bouton MOVE
     * @param {type} sh indiquer s'il faut afficher ou pas.
     * @returns {undefined}
     */
    var _f_MvArtDesc = function (x,sh) {
        try {
            
            var sls = [], ac;
            if (! KgbLib_CheckNullity(x) ) {
                sls.push($(x).closest(".jb-arp-bot-img"));
                ac = $(x).data("action").toLowerCase(); 
            } else {
                sls = $(".jb-arp-bot-img");
                if ( !KgbLib_CheckNullity(sh) && typeof sh === "boolean" && ( sh === true ||  sh === false ) ) {
                    ac = ( sh === true ) ? "adesc_move_up" : "adesc_move_down";
                } else {
                    sh = false;
                    ac = "adesc_move_down";
                }
            }
            
            $.each(sls,function(x,el){
                var adh = $(el).find(".jb-arp-art-desc").outerHeight();
                var posb, iup;
                switch (ac) {
                    case "adesc_move_up" :
                            posb = '25px';
                            iup = true;
                        break;
                    case "adesc_move_down" :
                            posb = (adh-5)*(-1);
                            posb +='px';
                            iup = false;
                        break;
                    default :
                        return;
                }
            
                if ( iup || sh ) {
                    $(el).find(".jb-arp-bot-img-desc-move[data-action='adesc_move_up']").stop(true,true).fadeOut(100);
                    $(el).find(".jb-arp-art-desc").stop(true,true).animate({
                        bottom: posb
                    },400,function(){
                        if ( iup ) {
                            $(el).find(".jb-arp-bot-img-desc-move[data-action='adesc_move_up']").stop(true,true).fadeOut(150);
                            $(el).find(".jb-arp-bot-img-desc-move[data-action='adesc_move_down']").stop(true,true).fadeIn(150);
                        } else {
                            $(el).find(".jb-arp-bot-img-desc-move[data-action='adesc_move_down']").stop(true,true).fadeOut(150);
                            $(el).find(".jb-arp-bot-img-desc-move[data-action='adesc_move_up']").stop(true,true).fadeIn(150);
                        }
                    });
                } else {
                    $(el).find(".jb-arp-bot-img-desc-move[data-action='adesc_move_down']").stop(true,true).fadeOut(100);
                    $(el).find(".jb-arp-art-desc").stop(true,true).animate({
                        bottom: posb
                    },400,function(){
                        if ( iup ) {
                            $(el).find(".jb-arp-bot-img-desc-move[data-action='adesc_move_up']").stop(true,true).fadeOut(150);
                            $(el).find(".jb-arp-bot-img-desc-move[data-action='adesc_move_down']").stop(true,true).fadeIn(150);
                        } else {
                            $(el).find(".jb-arp-bot-img-desc-move[data-action='adesc_move_down']").stop(true,true).fadeOut(150);
                            $(el).find(".jb-arp-bot-img-desc-move[data-action='adesc_move_up']").stop(true,true).fadeIn(150);
                        }
                    });
                }
            }); 
            
        } catch(ex) {
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
    
    
    /**************************************************************************************************************************************************/
    /**************************************************************** LISTERNERS SCOPE ****************************************************************/
    /**************************************************************************************************************************************************/
     
    $(".jb-tmlnr-mdl-std .fcb_img_maximus").off("click").click(function(e){
         Kxlib_PreventDefault(e);
         Kxlib_StopPropagation(e);
         
         /*
          * [DEPUIS 15-07-15] @BOR
          */
         _f_OnOpen(this);
    });
     
    $(".jb-tmlnr-mdl-std .fcb_img_maximus .jb-irr").off("click").click(function(e){
         Kxlib_PreventDefault(e);
         Kxlib_StopPropagation(e);
    });
    
    $(".jb-arp-bot-img-fmr").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_OnClose(this);
    });
        
    /* [DEPUIS 13-07-15] @BOR
    $(e).find(".jb-arp-bot-img-fmr").hover(function(){
        //$(this).removeClass("arp-bot-img-fmr").addClass("arp-bot-img-fmr_hover");
        $(this).addClass("arp-bot-img-fmr_hover");
    },function(){
        //$(this).removeClass("arp-bot-img-fmr_hover").addClass("arp-bot-img-fmr");
        $(this).removeClass("arp-bot-img-fmr_hover");
    });
    */
       
    $(".jb-arp-prmlk-cloz").click(function(){
        $(this).closest(".jb-arp-prmlk-sprt").addClass("this_hide");
//        $(this).parent().parent().parent().find(".jb-arp-bot-img-fade").toggleClass("arp-bot-img-fade-gl");
    });
    
    //Trigger de l'ajout du commentaire
    $(".jb-arp-nw-react-trg").off().click(function(e){
        Kxlib_PreventDefault(e);
        _f_AddRct(this);
    });
    
    //Supprimer un commentaire
    $(".jb-arp-rct-del, .jb-arp-rct-fnl-dc").click(function(e){
        Kxlib_PreventDefault(e);
//        alert($(e.target).data("target"));
        _f_Rct_Del(e.target);
    });
    
    //Controle sur le focus
    $(".jb-arp-input").focus(function(){
        $(this).removeClass("error_field");
    });
    
    //Controle sur le blur
    $(".jb-arp-input").blur(function(e) {
        if ( $(this).val() === "" ) {
            $(this).removeClass("error_field");
        }
    });
    
    $(".jb-arp-del-sbchcs").off().click(function(e){
         Kxlib_PreventDefault(e);
         
         gt.CheckOperation(this);
    });
    
    $(".jb-tmlnr-arp-art-clz-all").click(function(e){
         Kxlib_PreventDefault(e);
         
         gt.CheckOperation(this);
    });
    
    /*
     * [DEPUIS 13-07-15] @BOR
     */
    $(".jb-arp-bot-img").off().hover(function(){
//        Kxlib_DebugVars([ADESC HOVERIN --"]);
        _f_HdlHvrOpSnc(this,true);
    },function(){
//        Kxlib_DebugVars([ADESC HOVEROUT --"]);
        _f_HdlHvrOpSnc(this,false);
    });
    
    $(".jb-arp-bot-img-desc-move").off().click(function(e){
        Kxlib_PreventDefault(e);
        
        gt.CheckOperation(this);
    });
    
    /*
     * [DEPUIS 13-07-15] @BOR
     */
    $(".jb-arp-input").off().keypress(function(e){
        if ( (e.which && e.which === 13) || (e.keyCode && e.keyCode === 13) ) {
            Kxlib_PreventDefault(e);
            
            var btn = $(this).closest(".arp-nw-react-ipt-mx").find(".jb-arp-nw-react-trg");
            $(btn).click();
        }
    });
    
    $(".jb-arp-bot-img-lnch-vid").off().click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_VidAction(this);
    });
    
}

new ARP_HNDLR();

function RichPost_Receiver () {
    
    var o = new ARP_HNDLR();
    this.Routeur = function (th){
        if ( KgbLib_CheckNullity(th) ) { return; }
        o.CheckOperation(th);
    };
    
//    $(".afl_choice").click(function(){
//        alert("to");
//        var id = $(this).data("target");
//        
//       $(id).find(".rich-post-get-link-max").removeClass("this_hide");
//    });
}