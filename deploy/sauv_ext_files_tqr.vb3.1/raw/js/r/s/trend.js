/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * Cette 'Classe' représente la Tendance au niveau du FE
 * Elle a été créée après les fichiers de Gestion de HEADER, COVER, LIST, etc ...
 * Aussi, certaines fonctionnalités qui auraient dû être implémentées naturellement, ne le sont pas dans ce fichier.
 * 
 * Cependant, elle peut être appelée par une autre page que TRPG (actuellement je travaille dessus).
 * Aussi on va quand même ajouter les méthodes (certaines) de telles sortes qu'elles soient accessibles par tous. 
 * 
 * Elle regroupe les fonctionnalités générales de gestion d'une TENDANCE
 */
function Trend (isload) {
    var gt = this;
    var _datas;
//    this.datas;
    var _t;
//    this.t;
    var _d;
//    this.d;
    var _c;
//    this.c;
    var _p;
//    this.p;
    var _g;
//    this.g;
    var _ti;
//    this.ti;
    
    
     /******************************************************************************************************************************************************/
     /******************************************************************** PROCESS SCOPE *******************************************************************/
     /******************************************************************************************************************************************************/
     
    var _f_Init = function(){
        try {
            /*
             * NOTE
             *      On vérifie s'il y a des Articles. 
             *      Dans ce dernier cas, on affiche la barre de load et on masque eventuellement NoOne.
             *      RAPPEL : La barre est toujours masquée, on l'affiche via cette méthode init().
             *      On affiche si on a la présence des 6 publications et que le nombre de publications affiché est superieur à 6
             */
//            alert("TREND.JS : Stop:47");
            if ( typeof window.pageIsLoaded === "undefined" || window.pageIsLoaded === false ) {
                
                if ( $(".jb-mdl-tr-post-in-list").length && $(".jb-mdl-tr-post-in-list").length === 6 && parseInt($("#tr-h-t-d-20").data("length")) > 6 ) {
                    $(".jb-whub-mx").addClass("this_hide");
                    $(".jb-trpg-loadm-box").removeClass("this_hide");
                } else if ( $(".jb-mdl-tr-post-in-list").length && $(".jb-mdl-tr-post-in-list").length <= 6 ) {
                    $(".jb-whub-mx").addClass("this_hide");
                    $(".jb-trpg-loadm-box").addClass("this_hide");
                } else {
                    $(".jb-trpg-loadm-box").addClass("this_hide");
                    $(".jb-whub-mx").removeClass("this_hide");
                }

                /*
                 * ETAPE :
                 *      On vérifie s'il y a une image de couverture.
                 *      Dans ce dernier cas, on affiche le bouton qui permet de supprimer la Couverture
                 */
                if ( $(".jb-a-h-t-top-img").length ) {
                    $(".afl_choice.bind-deltrcov").removeClass("this_hide");
                } else {
                    $(".afl_choice.bind-deltrcov").addClass("this_hide");
                }
                
                window.pageIsLoaded = true;
                
            } 

        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
     /***************************** ARTICLE SCOPE *****************************/
         
     var _f_OnLoad = function (il) {
        try {
//            alert("TREND.JS:85");
            /*
             * ETAPE :
             *      On travaille le texte descriptif des Articles affichés
             */
            var $sl = $(".jb-mdl-tr-post-in-list");
            gt._f_FrmtDsc($sl);
            
            /*
             * ETAPE :
             *      On ajuste la taille de la zone conteneur en fonction du nombre d'Articles en présence et de leur taille respective.
             */
            _f_AdjtArtBx($sl);
            
            /*
             * ETAPE :
             *      On range l'ensemble des Articles selon leur taille et leur ordre dans la liste
             */
            if ( il !== false ) {
                _f_OrdArts($sl);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
     };
     
     //STAY PUBLIC !!
     this._f_Shuffle = function ($sl) {
        try {
            
            $sl = ( KgbLib_CheckNullity($sl) ) ? $(".jb-trpg-art-nest").find(".jb-mdl-tr-post-in-list") : $sl;
            
            /*
             * ETAPE :
             *      On range l'ensemble des Articles selon leur taille et leur ordre dans la liste
             */
            _f_OrdArts($sl);
            
            /*
             * ETAPE :
             *      On ajuste la taille de la zone conteneur en fonction du nombre d'Articles en présence et de leur taille respective.
             */
            _f_AdjtArtBx($sl);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
     };
     
     /**
      * Permet de faire glisser la colonne West vers le haut ou vers le bas pour permettre l'apparition de la zone d'ajout.
      * 
      * @param {string} dr
      * @returns {undefined}
      */
     this._f_WColSlip = function ($sl,dr) {
         try {
             if ( KgbLib_CheckNullity($sl) | !$sl.length | KgbLib_CheckNullity(dr) | $.inArray(dr,["top","btm"]) === -1 | $("#nwtrdart-box").height() === 0 ) {
//             Kxlib_DebugVars([gbLib_CheckNullity($sl), !$sl.length, KgbLib_CheckNullity(dr), $.inArray(dr,["top","btm"]) === -1, $("#nwtrdart-box").height() === 0]);
                return;
            }
         
            /*
             * ETAPE :
             * On déplace individuellement chaque Article.
             */
            $.each($sl, function(i,e) {
                if ( i%2 === 0 && $(e).height() !== 0 && !KgbLib_CheckNullity($(e).css("transform")) && typeof $(e).css("transform") === "string" ) {
//                    Kxlib_DebugVars([08,$(e).css("transform"),$(e).css("transform").split(",")]);
                    var x = parseInt($(e).css("transform").split(",")[4].substr(1));
                    var y = $(e).css("transform").split(",")[5];
                    y = parseInt(y.substr(1,y.length-1));
//                    Kxlib_DebugVars([]);
                    /*
                    if ( i === 0 ) {
//                        Kxlib_DebugVars([0 cas BEFORE => "+y]);
                        y += 40;
//                        Kxlib_DebugVars([0 cas AFTER => "+y]);
                    } else {
                        y += 40;
                    }
                    //*/
                    if ( dr === "btm" ) {
                        y += 40;
                        y += $("#nwtrdart-box").height();
                    } else if ( dr === "top" ) {
                        y -= 40;
                        y -= $("#nwtrdart-box").height();
                    }
                    
                    $(e).css({
                        transform: "translate3d(" + x + "px, " + y + "px, 0px)"
                    });
                }
            });
            
            /*
             * ETAPE :
             * On ajuste la taille de la zone
             */
            var bxh = $(".jb-trpg-art-nest").height();
            if ( dr === "btm" ) {
                $(".jb-trpg-art-nest").height(bxh+$("#nwtrdart-box").height()+40);
            } else if ( dr === "top" ) {
                $(".jb-trpg-art-nest").height(bxh-$("#nwtrdart-box").height()-40);
            }
            
        } catch (ex) {
             Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

     };
    
    this._f_FrmtDsc = function ($sl) {
//    var _f_FrmtDsc = function ($sl) {
        try {
            if ( KgbLib_CheckNullity($sl) ) {
                return;
            }
            
            if ( $sl.length ) {
                var is = [];
                var __ = 0;
                $.each($sl, function(x,am) {
                    /*
                    if ($(e) && !KgbLib_CheckNullity($(e).data("with")) && typeof $(e).data("with") === "string") {
//                        is.push($(e).data("item"));
//                        ++__;
//                        if ( $(e).data("item") === "7fbbjoe2" ) {
//                            alert($(e).find(".jb-mdl-acc-post-txt").text());
//                        }
                        
                        var w__ = $(e).data("with");
                        //On récupère les éléments
                        w__ = Kxlib_DataCacheToArray(w__)[0];
                        if (w__ && $.isArray(w__) && w__.length) {
                            
                            var ps__ = ( $.isArray(w__[0]) ) ? Kxlib_GetColumn(3, w__) : [w__[3]];
//                            Kxlib_DebugVars([181,JSON.stringify(w__),JSON.stringify(ps__)], true);
//                            Kxlib_DebugVars([182,$(e).find(".jb-mdl-acc-post-txt").text()], true);
                            var t__ = Kxlib_UsertagFactory($(e).find(".jb-mdl-acc-post-txt").text(), ps__, "tqr-unq-user");
                            t__ = $("<div/>").text(t__).text();
                            
//                            t__ = Kxlib_Decode_After_Encode(t__);
//                            Kxlib_DebugVars([186,JSON.stringify(t__)], true);
                            t__ = Kxlib_SplitByUsertags(t__);
//                            Kxlib_DebugVars([189,$(t__).html()], true);
//                            Kxlib_DebugVars([188,JSON.stringify(t__)], true);
                            
                            //Mettre en place la description
                            $(e).find(".jb-mdl-acc-post-txt").html(t__);
                            
                            /*
                             * [DEPUIS 13-06-15] @BOR
                             * Règle le problème lié au fait qu'il y ait plusieurs Usertags.
                             * Le fragmentation créait plusieurs <span> qui par la suite était mal traitée.
                             *
                            $.each($(e).find(".jb-mdl-acc-post-txt").find("span"),function(i,se){
                                var ty__ = $(se).text();
                                ty__ = ( ty__ ) ? Kxlib_Decode_After_Encode(ty__) : ty__;

                                $(se).text(ty__);
                            });         
                           /*
                            t__ = $(e).find(".jb-mdl-acc-post-txt").find("span").text();
//                            t__ = $("<div/>").text(t__).text();
                            t__ = Kxlib_Decode_After_Encode(t__); 
                            
                            $(e).find(".jb-mdl-acc-post-txt").find("span").text(t__);
                            //*
                            //On affiche le texte
                            $(e).find(".jb-mdl-acc-post-txt").hide().removeClass("this_invi").fadeIn();
                        }
                    } else {
                        //On affiche le texte
                        var t__ = $(e).find(".jb-mdl-acc-post-txt").text();
                        /*
                         * [NOTE 28-04-15] @BOR
                         * J'ai ajouté l'instruction suivante pour essayer de stabiliser le processus d'encodage.
                         * Mais je ne garantie pas le résultat pour TOUS LES CAS.
                         *
//                        t__ = $("<p/>").text(t__).text();
                        t__ = Kxlib_Decode_After_Encode(t__); 
                        $(e).find(".jb-mdl-acc-post-txt").text(t__);
                        $(e).find(".jb-mdl-acc-post-txt").hide().removeClass("this_invi").fadeIn();
                    }
                    //*/
                    var atxt = $(am).find(".jb-mdl-acc-post-txt").text();
                    atxt = $("<div/>").html(atxt).text();
//                    Kxlib_DebugVars(["'"+$(am).data("ajcache")+"'"],true);
//                    var ajca_o = JSON.parse("'"+$(am).data("ajcache")+"'");
                    var ajca_o = ( typeof $(am).data("ajcache") === "object" ) ? $(am).data("ajcache") : JSON.parse(""+$(am).data("ajcache")+"");
                    var ustgs = ajca_o.ustgs;
                    var hashs = ajca_o.hashs;

//                    Kxlib_DebugVars([atxt],true);
//                    Kxlib_DebugVars([atxt,typeof ajca_o,ajca_o.art_id,hashs,ustgs],true);
                    
                    //rtxt = RenderedText
                    var rtxt = Kxlib_TextEmpow(atxt,ustgs,hashs,null,{
                        emoji : {
                            "size"          : 36,
                            "size_css"      : 20,
                            "position_y"    : 3
                        }
                    });
//                Kxlib_DebugVars([rtxt],true);
                    //On ajoute le texte
                    $(am).find(".jb-mdl-acc-post-txt").text("").append(rtxt);
                    //On affiche la zone de texte
                    $(am).find(".jb-mdl-acc-post-txt").hide().removeClass("this_invi").fadeIn();
                    
                });
            } else {
                Kxlib_DebugVars(["Pas d'ARTICLES"],true); //DEV, TEST, DEBUG
            }
            
        } catch (ex) {
             Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_AdjtArtBx = function ($sl) {
        try {
            if ( KgbLib_CheckNullity($sl) | !$sl.length ) {
                return;
            }
            
            var h__ = 0, mrg = 20, ntab = [];
            if ( $($sl).length === 1 ) {
                /*
                 * S'il n'y a qu'un seul élément, on le traite du premier coup.
                 */
                h__ = $($sl).first().height()+mrg;
//                $($sl).get(0).height()+mrg;
            } else {
                $.each($sl, function(i,e) {
//                    Kxlib_DebugVars([153 : ELEMENT_H => "+$(e).height(),"; TYPE => "+typeof $(e).height()]);
                    /*
                     * ETAPE : 
                     *      On ne traite que si on arrive à la fin de la ligne d'un couple de 2 !.
                     *      A partir de là, on récupère la hauteur de l'élément qui a la hauteur la plus grande de la dite ligne.
                     */
                    if ( i%2 !== 0 && $(e).height() !== 0 ) {
                        var t__ = ( $(e).prev().height() >= $(e).height() ) ? $(e).prev().height() : $(e).height();
                        t__ += mrg;
                        h__ += t__;
                    }
                   /*
                    * [NOTE 22-04-15] @BOR
                    *       Certains éléments n'ont pas de hauteur et je n'arrive pas à déterminer pourquoi.
                    *       Aussi, je crée un tableau avec seulement les éléments qui ont une hauteur.
                    */
                   if ( $(e).height() !== 0 ) {
                       ntab.push(e);
                   }
                });
            }
            /*
             * ETAPE : 
             *      On vérifie le nombre total d'éléments est pair. 
             *      Dans le cas contraire, on ajoute la hauteur du dernier élément avec sa marge.
             *      En effet, étant donné qu'on ne traite les cas que par deux, le dernier élément dans une liste impair n'est jamais vraiment traité.
             * NOTE : 
             *  -> On ne traite pas si on a qu'un élément car le cas a déjà été traité.
             */
//            Kxlib_DebugVars([ntab.length],true);
            if ( ntab.length > 1 && ntab.length%2 !== 0 ) {
                h__ += $(ntab[ntab.length-1]).height()+mrg;
//                h__ += $($sl).last().height()+mrg; //[22-04-15] Toujours le meme probleme de hauteur 0
            }
            /*
             * [DEPUIS 13-06-15] @BOR
             *      Permet de régler le problème de la bordure inferieur du dernier Article qu'on ne voit pas.
             */
            h__ += 3;
            
            $(".jb-trpg-art-nest").height(h__);
//            Kxlib_DebugVars([187 : FINAL_H => "+h__]);
        } catch (ex) {
             Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_OrdArts = function ($sl) {
        try {
            if ( KgbLib_CheckNullity($sl) | !$sl.length ) {
                return;
            }
            
            /*
             * x : La coordonnée en abscisse de l'Article
             * y : La coordonnée en ordonné de l'Article
             * cx : Le rang de l'Article dans la pile (AXE X)
             * cy : Le rang de l'Article en ce qui concerne les lignes (AXE Y)
             * cn : 
             * eh : La somme des hauteurs pour la colonne de gauche
             * wh : La somme des hauteurs pour la colonne de droite
             */
            var x = 0, y = 0, cn = 0, eh = 0, wh = 0;
            $.each($sl, function(i,a) {
                
                if ( $(a).height() === 0 | $(a).hasClass("this_hide") ) {
                    return;
                }
                
                var cx, cy;
                /*
                 * ETAPE :
                 *      On calcule les coordonnées de l'Article dans l'espace
                 */
//                Kxlib_DebugVars([159, EST IMPAIR ? => ",i%2]);
                if ( i === 0 ) {
                    x = 1;
//                    x = 0;
                    y = 0;
                    cx = 0;
                    cy = 0;
                } else if ( i === 1 ) {
                    x = 373+20;
//                    x = 372+20;
                    y = 0;
                    cx = 1;
                    cy = 0;
                } else if ( i%2 === 0 ) {
                    x = 1;
//                    x = 0;
                    /*
                     * [NOTE 22-04-15] 
                     *      La coordonné y correspond à la somme des hauteurs des Articles + la somme des "margin-top".
                     */
                    y = eh + (20*(i/2));
                } else {
                    x = 373+20;
//                    x = 372+20;
                    /*
                     * [NOTE 22-04-15] 
                     *      La coordonné y correspond à la somme des hauteurs des Articles + la somme des "margin-top".
                     *      Dans ce cas, on retranche - 1 car qu'on soit à gauche ou droite, ça ne change rien.
                     *      Alors on retranche pour permettre de refaire le même calcul que pour un élément qui serait à gauche.
                     */
                    y = wh + (20*((i-1)/2));
                }
//                Kxlib_DebugVars([288 : X => ",x,";Y => ",y]);
                
                /*
                 * ETAPE : 
                 * On place géométriquement l'élément dans la zone.
                 */
                $(a).css({
                    transform: "translate3d(" + x + "px, " + y + "px, 0px)"
                });
                
                /*
                 * ETAPE : 
                 *      On insère la coordonnée qui permet de situer l'Article par segment.
                 *      Pour les cas pairs, on divise par 2 et on retire 1.
                 *      Pour les cas impairs, on constate qu'il sont sur la même ligne que ceux pairs qui se trouvent à gauche...
                 *      ... On retranche donc 1 et on effectue la même opération.
                 */
                if ( KgbLib_CheckNullity(cx) && KgbLib_CheckNullity(cy) ) {
                    cx = i;
                    cy = ( i%2 === 0 ) ? (i/2) : (i-1)/2;
                } 
                $(a).data("at","[" + cx + "," + cy + "]");
                
                /*
                 * ETAPE :
                 *      On incrémente la hauteur selon la colonne.
                 */
                if ( i%2 === 0 ) {
                    eh += $(a).height();
                } else {
                    wh += $(a).height();
                }
                /*
                 * ETPAE :
                 *      On affiche l'Article dans le DOM s'il ne l'est pas.
                 */
                if ( $(a).hasClass("this_invi") ) {
                    $(a).hide().removeClass("this_invi").fadeIn();
                } 
                
//                Kxlib_DebugVars([220 : EH => ",eh,";WH => ",wh,"; CX => ",cx,"; CY => ",cy]);
            });
            
        } catch (ex) {
             Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    
    /**
     * Vérifie si la Tendance passée en paramètre existe. C'est à dire qu'elle vie et qu'elle n'est pas supprimée
     * 
     * @param {type} t
     * @returns {r|Boolean}
     */
    var _f_ChkTrXst = function (t,s,m) {
//    this.CheckTrendExists = function (t,s,m) {
        //s = Selecteur. Il sert a déclencher un événement lorsqu'on recoit le retour d'AJAX
        
        //Vérification 
        _f_Srv_ChkTrXsts(t,s,m);
    };
    
    
    /**
     * Vérifie auprès du serveur si la Tendance "Vie".
     * En d'autres termes la méthode vérifie si la Tendance n'a pas été supprimée ET qu'elle n'a pas été bannie.
     * 
     * La méthode renvoit un message d'erreur (si on la lui spécifie) OU un booléen.
     * 
     * @param {type} t
     */
    var _f_ChkTrLvs = function (t,s,m) {
//    this.CheckTrendLives = function (t,s,m) {
        //s = Selecteur. Il sert a déclencher un événement lorsqu'on recoit le retour d'AJAX
        
        //Vérification 
        _f_Srv_ChkTrLvs(t,s,m);
        
    };
    
    /**
     * Vérifie si la Tendance est "Officielle".
     * En d'autres termes si le propriétaire de la Tendance appartient au groupe de "L'Equipe"
     * 
     * @param {type} t
     * @returns {r|Boolean}
     */
    /*
    this.CheckTrendIsOfficial = function (t) {
        r = false;
        
        return r;
    };
    //*/
    /**
     * Vérifie si l'utilisateur passé en paramètre est abonné à la Tendance elle même aussi passée en paramètre.
     * 
     * @param {type} t
     * @returns {r|Boolean}
     */
    var _f_ChkUserFolg = function (u,t) {
//    this.CheckUserFollowing = function (u,t) {
        
    };
    
    //Récupère les données de base d'une Tendance passée en paramètre
    //Title, Description (d), Catégorie (c), Participation (p), Gratification (g), Time (ti)
    var AcqrTrFeats = function (t) {
//    this.AcquireTrendFeatures = function (t) {
        
    };
    
    
    /***************************** RETATION METHODES *********************************/
    
    /* Relation Methodes sont utilisées pour avertir l'élément passé en paramètre que les données sont disponibles et les lui fournir */
     var _f_RelM_ChkTrdXsts = function (r,s,md) {
//     this.RelM_CheckTrendExists = function (r,s,md) {
         //md = mode ["message" | "bool"]
        var dl = "err_trpg_tr_donotexists_msg";
//        alert("RETURN =>"+r);
//        alert("ELEMENT RELATION => "+$(s));
        var mode = ( KgbLib_CheckNullity(md) || md.length !== 1 ) ? "b" : md[0];
        if (! r ) {
//                alert(md);
            switch (mode) {
                case "m": 
                case "messsage": 
                        $(s).attr("return",Kxlib_getDolphinsValue(dl));
                    break;
                case "boolean": 
                case "bool": 
                case "b": 
                        $(s).attr("return",false);
                    break;
                default : 
                        //Par défaut, on renvoit un bool
                        $(s).attr("return",false);
                    break;
            }
            //On avertit le Handler
            $(s).trigger("datasready");
            
        } else {
            //Dans le cas où on demande un boolean on supprose qu'on attends un retour coute que coute
            if ( mode === "b" || mode === "bool" ||  mode === "boolean" ) {
                $(s).attr("return",true);
                $(s).trigger("datasready");
            }
            //Sinon on ne fait rien
        }
     };
     
     
     var _f_RelM_ChkTrdLives = function (r,s,md) {
//     this.RelM_CheckTrendLives = function (r,s,md) {
         //md = mode ["message" | "bool"]
        var dl = "err_trpg_tr_nonliving_msg";
//        alert("RETURN =>"+r);
//        alert("ELEMENT RELATION => "+$(s));
         var mode = ( KgbLib_CheckNullity(md) || md.length !== 1 ) ? "b" : md[0];
         if (! r ) {
            
//                alert(md);
            switch (mode) {
                case "m": 
                case "messsage": 
                        $(s).attr("return",Kxlib_getDolphinsValue(dl));
                    break;
                case "bool": 
                case "b": 
                        $(s).attr("return",false);
                    break;
                default : 
                        //Par défaut, on renvoit un bool
                        $(s).attr("return",false);
                    break;
            }
            //On avertit le Handler
            $(s).trigger("datasready");
            
        } else {
            //Dans le cas où on demande un boolean on supprose qu'on attends un retour coute que coute
            if ( mode === "b" || mode === "bool" ||  mode === "boolean" ) {
                $(s).attr("return",true);
                $(s).trigger("datasready");
            }
            //Sinon on ne fait rien
            
        }
     };
 
    /******************************************************************************************************************************************************/
    /******************************************************************* LISTENERS CSOPE ******************************************************************/
    /******************************************************************************************************************************************************/
     
    //URQID => Vérifier si la Tendance existe
    this.checkTrExists_url = "http://127.0.0.1/korgb/ajax_test.php";
    this.checkTrExists_uq = "chk_tr_exists";
    
    var _Ax_ChkTrXsts = Kxlib_GetAjaxRules("CHK_TR_XST");
    var _f_Srv_ChkTrXsts = function (i,s,md) {
//    this.Ajax_CheckTrendExists = function (i,s,md) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(i) | KgbLib_CheckNullity(md) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            
            if (! KgbLib_CheckNullity(datas) ) {
                datas = JSON.parse(datas);
            }
            
            //alert(datas.return);
            var o = new ErrorBarVTop(), i = "#err_bar_in_trpg";
            
            if ( typeof datas.err !== "undefined" ) {
                var me = Kxlib_getDolphinsValue("err_com_failajax_sys");
                me = ( !KgbLib_CheckNullity(datas.err) ) ? datas.err : me;
                o.EB_DeclareErr(i,me); 
            } else if ( typeof datas.return === "undefined" ) {
                //Si on ne recoit aucune réponse
                var mr = Kxlib_getDolphinsValue("err_com_failajax_sys");
                o.EB_DeclareErr(i,mr,"m");
            } else {
                //Cette fonction étant appelée de façon récurente, il est important de masquer l'erreur dès que le problème a été réparé
                o.CloseBar(i);
                
                //La réponse est soit TRUE ou FALSE
                _f_RelM_ChkTrdXsts(datas.return,s,md);
            }
                
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            alert("AJAX ERR : "+th.checkTrExists_uq);
        };
        
        //Pour déterminer qui est le propriétaire on regardera dans la SESSION en cours
        var toSend = {
            "urqid": _Ax_ChkTrXsts.urqid,
            "datas": {
                "trid": i
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_ChkTrXsts.url, wcrdtl : _Ax_ChkTrXsts.wcrdtl });
    };
    
    //URQID => Vérifier si la Tendance 'Vie'
//    this.checkTrLives_url = "http://127.0.0.1/korgb/ajax_test.php";
//    this.checkTrLives_uq = "chk_tr_lives";
    
    var _f_Srv_ChkTrLvs = function (i,s,md) {
//    this.Ajax_CheckTrendLives = function (i,s,md) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(s) | KgbLib_CheckNullity(md) ) {
            return;
        }
        
        var th = this;
        
        var onsuccess = function (datas) {
            
//            alert("CHAINE JSON AVANT PARSE"+datas);
            
            if (! KgbLib_CheckNullity(datas) )
                datas = JSON.parse(datas);
            //alert(datas.return);
            var o = new ErrorBarVTop(), 
                        i = "#err_bar_in_trpg";
            
            if ( typeof datas.err !== "undefined" ) {
                var lm = Kxlib_getDolphinsValue("err_com_failajax_sys");
                var m = ( !KgbLib_CheckNullity(datas.err) ) ? datas.err : lm;
                o.EB_DeclareErr(i,m); 
            } else if ( typeof datas.return === "undefined" ) {
                var rm = Kxlib_getDolphinsValue("err_com_failajax_sys");
                //Si on ne recoit aucune réponse
                o.EB_DeclareErr(i,rm,"m");
            } else {
                //Cette fonction étant appelée de façon récurente, il est important de masquer l'erreur dès que le problème a été réparé
                o.CloseBar(i);
                
                //La réponse est soit TRUE ou FALSE
                _f_RelM_ChkTrdLives(datas.return,s,md);
            }
                
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            alert("AJAX ERR : "+th.checkTrLives_uq);
        };
        
        //Pour déterminer qui est le propriétaire on regardera dans la SESSION en cours
        var toSend = {
            "urqid": th.checkTrLives_uq,
            "datas": {
                "trid": i
            }
        };

        Kx_XHR_Send(toSend, "post", this.checkTrLives_url, onerror, onsuccess);
    };
     
     /******************************************************************************************************************************************************/
     /********************************************************************* AUTO SCOPE *********************************************************************/
     /******************************************************************************************************************************************************/
     
//     (function(){
//         _f_OnLoad();
//     })();
    /*
     $( document ).ready(function() {
         console.log("DOCU READY");
         _f_Init();
        _f_OnLoad();
      });
     console.log("Should have been INIT()");
     //*/
     /******************************************************************************************************************************************************/
     /******************************************************************* LISTENERS CSOPE ******************************************************************/
     /******************************************************************************************************************************************************/
     
     
    /*********************************************************************************************************************************************************/
    /********************************************************************** INIT SCOPE ***********************************************************************/
    /*********************************************************************************************************************************************************/
    
    /*
     * [DEPUIS 10-05-16]
     *      Permet de résoudre un bogue dont l'origine devait être le manque de synchronisme ou l'exécution de manière aléatoire de _f_Init().
     *      Il semblerait qu'il faille que @see _f_Init() s'exécute avant _f_OnLoad().
     *      Or $(document).ready() avait un fonctionnement aléatoire que je n'ai pas pu dompter (à temps...).
     */
    _f_Init();
    _f_OnLoad(isload);
}

new Trend();