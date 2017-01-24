/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function TMLNR_PFLHDR () {
    var gt = this;
    
    var _dftPic;
    var _newPic;
    var _newPic_fn;
    var _oldPic;
    
    /***********************************************************************************************************************************************/
    /**************************************************************** PROCESS SCOPE ****************************************************************/
    /***********************************************************************************************************************************************/
    
    var _f_ChkOpe = function(x,fl) {
//    this.CheckOperation = function(x,f) {
        try {
            
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
//            Kxlib_DebugVars([typeof fl],true);
//            return;
            
            if (! KgbLib_CheckNullity(fl) ) {
                _f_ChgPic(fl);
            }
            
            /*
             * [NOTE 25-06-15] @BOR 
             * C'est normal !
             */
            if ( KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            
            var a = $(x).data("action");
            switch (a.toString().toLowerCase()) {
                case "change":
                        /*
                         * Il est impossible d'arriver car on détecte la présence de fichiers pour déterminer ce cas.
                         * On garde cette section car il n'y a pas de raison impérieuse qui nous oblige à faire le contraire.
                         */
                    break;
                case "delete":
                        _f_ConfDfltPic(x);
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_Gdf = function () {
        /*
        //dt = DefaulT picture
        this.dt = "../public/img/default/photo_stand_m_100x100.png";
        this.__ZInMax = 90;
        this.__ZOutMax = 45;

        this.__MAXSize = 3000000;
        this.__Format = ["png","jpeg"];
        //*/
        var DT = {
            "dt"        : Kxlib_GetExtFileURL("sys_url_img","r-dp/tqr_std_ppic_m.png"),
//            "dt": "http://timg.ycgkit.com/files/img/r-dp/tqr_std_ppic_m.png",
            "__ZInMax"  : 90,
            "__ZOutMax" : 45,
            "__MAXSize" : 2621440,
            "__Format"  : ["png","jpeg"],
            "__Min_H"   : 70,
            "__Max_H"   : 1000,
            "__Min_W"   : 70,
            "__Max_W"   : 1000
        };
        
        return DT;
    };
    
    /******************/
    
    var _f_ChgPic = function (f) {
        try {
            if ( KgbLib_CheckNullity(f) || f.length > 1 ) { 
                return;
            }
            
            $.each(f, function(x, v) {
                var r = _f_VrfyComfy(v);
                
                //On vérifie si une erreur est définie
                if ( !r || typeof r === "string" ) {
//                var m = Kxlib_getDolphinsValue(r.err);
//                alert(m);
                    
                    _f_OnError(r);
                    
                    //On reset l'input
                    _f_Reset_Form();
                    
                    //Par précaution on delete "newImage"
                    delete _newPic;
                    
                    return;
                }
                _f_ReadFile(v);
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_VrfyComfy = function(i,w,h) {
        try {
            
            if ( KgbLib_CheckNullity(i) ) {
                return;
            }
            //Verifie : format, taille et dimensions
            if (!i.type.match('image/*')) {
                return "__PP_BAD_FILE";
            } 
            
            /*
             * Il peut arriver qu'on utilise cette méthode juste pour déterminer si le fichier est une image sans pour autant aller plus loin.
             * Aussi, on ne vérifiera les autres informations que si la largeur et la hauteur sont tous deux définies
             */
            if (!KgbLib_CheckNullity(w) && !KgbLib_CheckNullity(h)) {
                
                var filef = i.type.split('/').pop();
                if ($.inArray(filef, _f_Gdf().__Format) === -1) {
                    //Est ce que le type de l'image est autorisé
                    return "__PP_BAD_TYPE";
                } else if (i.size > _f_Gdf().__MAXSize) {
                    //Est ce que le type de l'image est autorisée
                    return "__PP_BAD_SIZE";
                } else if (w !== h) {
                    //Est ce que le format de l'image est autorisée. RAPPEL : Seuls le format CARRE est autotisé.
                    return "__PP_BAD_FORMAT";
                } else {
                    //QUESTION : Est ce que l'image respecte les dimensions minimales et maximales ?
                    
                    //Controle de la hauteur
                    if (h < _f_Gdf().__Min_H) {
                        return "__PP_BAD_DIMS_MIN";
                    } else if (h > _f_Gdf().__Max_H) {
                        return "__PP_BAD_DIMS_MAX";
                    } 
                    //Controle de la largeur
                    if (w < _f_Gdf().__Min_W) {
                        return "__PP_BAD_DIMS_MIN";
                    } else if (w > _f_Gdf().__Max_W) {
                        return "__PP_BAD_DIMS_MAX";
                    } 
                }
            }
            return true;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /*
    this._Security = function(a){
        //Verifie : format, taille et forme
        if (!a.type.match('image/*')) {
            return {err: "ERR_NWTRART_BADFILE"};
        } else {
            var ff = a.type.split('/').pop();
//            alert("Format :"+ff);
            
            if ( $.inArray( ff, _f_Gdf().__Format) === -1 ) {
                //Est ce que le type de l'image est autorisé
                return {err: "ERR_NWTRART_BADTYPE"};
            } else if ( a.size > _f_Gdf().__MAXSize ) {
                //Est ce que le type de l'image est autorisé
                return {err: "ERR_NWTRART_TOOLOUD"};
            } 
            
            return 1;
            
        }
        
    };
    //*/
    
    var _f_OnError = function (c) {
//    this.OnError = function (c) {
        try {
            
            //c : Code erreur
            if (KgbLib_CheckNullity(c)) { 
                return;
            }
            
            //m : Le message à afficher; t : Le temps durant lequel le message restera affiché
            var m, t = 0;
            switch (c.toUpperCase()) {
                case "__PP_BAD_FILE":
                        m = Kxlib_getDolphinsValue("ERR_PPIC_BAD_FILE");
                    break;
                case "__PP_BAD_TYPE":
                        m = Kxlib_getDolphinsValue("ERR_PPIC_BAD_TYPE");
                    break;
                case "__PP_BAD_SIZE":
                        m = Kxlib_getDolphinsValue("ERR_PPIC_BAD_SIZE");
                    break;
                case "__PP_BAD_FORMAT":
                        m = Kxlib_getDolphinsValue("ERR_PPIC_BAD_FORMAT");
                    break;
                case "__PP_BAD_DIMS_MIN":
                        m = Kxlib_getDolphinsValue("ERR_PPIC_BAD_DIMS_MIN");
                    break;
                case "__PP_BAD_DIMS_MAX":
                        m = Kxlib_getDolphinsValue("ERR_PPIC_BAD_DIMS_MAX");
                    break;
                default :
                        m = Kxlib_getDolphinsValue("ERR_GEN_FAILED");
                        t = 7000;
                    break;
            }
            
            //On vérifie le temps d'affichage. S'il a déjà été définie, on passe
            var ec;
            if (typeof t === "undefined" || t === 0) {
                t = 4000;
                
                /** --- GESTION DU NOMBRE D'ERREURS --- **/
                //ec = ErrorCount
                ec = $(".jb-p-h-b-ui-trg").data("ec");
                ec = (KgbLib_CheckNullity(ec)) ? 0 : parseInt(ec);
                ec = ++ec;
                
                $(".jb-p-h-b-ui-trg").data("ec", ec);
            }
            
            /*
             * Vérifie si on est dans le cas où il faut afficher l'aide.
             * Cette aide permet à l'utilisateur d'être guidé dans l'ajout d'une image de profil.
             */ 
            if (!KgbLib_CheckNullity(ec) && ec >= 3) {
                $(".jb-p-h-ui-hlp-pan").removeClass("this_hide");
            }
            
            //On s'assure qu'il n'y a pas déjà un message affiché
            if ($(".jb-h-ui-err-pan").hasClass("this_hide")) {
                //On ajoute le message d'erreur
                $(".jb-h-ui-err-pan").text(m);
                
                //On affiche le panel
                $(".jb-h-ui-err-pan").removeClass("this_hide");
//            Kxlib_DebugVars([$(".jb-cov-err-pan-ctr").outerHeight()],true);
                
                //Apres quelques secondes, on fat disparaitre le panel
                setTimeout(function() {
                    //On fait disparaitre le panel 
                    $(".jb-h-ui-err-pan").fadeOut(250, function() {
                        $(this).addClass("this_hide");
                        $(this).removeAttr("style");
                    });
                    
                }, t);
            }
            //Sinon on n'affiche pas l'erreur. Des améliorations pourront être effectuées pour résorber le problème.
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_Reset_Form = function () {
//    this.Reset_Form = function () {
        /*
         * Permet de vider l'input. Cela permet notamment de pouvoir réessayer avec le même fichier, qui est un comportement normal.
         */
        return ( Kxlib_ResetFormElt("p-h-b-ui-a-file") ) ? true : false;
    };
    
    var _f_ReadFile = function (fl) {
//    this.ReadFile = function (f) {
        try {
            
            if (! window.FileReader ) {
//              TODO : "Switch sur Adobe ? Normallement, cela est resolu au niveau du server-side"
                var m = Kxlib_getDolphinsValue("ERR_BZR_OBSOLETE");
                alert(m);
                //On reset l'input pour garantir un focntionnement normal
                _f_Reset_Form(true);
                
                return;
            } 
            
            if ( KgbLib_CheckNullity(fl) ) {
                return;
            }
            
            //rd = Reader
            var rd = new FileReader();
            rd.onload = function() {
                var img = new Image();
                img.src = rd.result;
                
                img.onload = function() {
                    
                    //On vérifie que l'image remplie les conditions attendues
                    var gd = _f_VrfyComfy(fl, img.width, img.height);
//                alert(gd);
                    if ( !gd || typeof gd === "string" ) {
                        _f_OnError(gd);
                        //On reset l'input pour garantir un focntionnement normal
                        _f_Reset_Form();
                        
                        return;
                    }
                    
                    /*
                     * [NOTE 30-06-14] 
                     * Cette section est rappelée dans certains cas. (Exemple : Je change src). 
                     * ... Il faut donc être sûr qu'on traite bien ce cas
                     */
                    var sp = $(this).data("is-default");
                    _newPic_fn = fl.name;
                    if (! sp ) {
                        _f_CrtThumbnail(this);
                    } else {
                        _f_CrtThumbnail(this, true);
                    }
                    
                    //On reset l'input
                    _f_Reset_Form();
                };
            };
            rd.onerror = function() {
                //TODO: Send to server
                //TODO : "An error occured when trying to read the image. It could be due to safety reasons or whatever !"
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            };
            
            rd.readAsDataURL(fl);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_IsSquare = function (h, w) {
        return ( (h/w) === 1 ) ? 1 : 0;
    };
    
    var _f_ImgIsDrgble = function (f) {
        
        //On commence par s'assurer que le parent a un overflow hidden
        $("#p-h-b-ui-trg").addClass("this_overhid");
        
        $(f).draggable({ 
            axis: "x,y",
            drag: function( e, ui ) {
                var _h = $(e.target).height(), _w = $(e.target).width();
                var _top = ui.position.top, _left = ui.position.left;
                
                var _limit_down = 90 - _h, _limit_right = 90 - _w;
                
                /*
                Kxlib_DebugVars("TOP : "+_top);
                Kxlib_DebugVars("HEIGHT : "+_height);
                //*/
                //*
                if ( _top > 0 ) {
                    ui.position.top = 0;
                } else if ( _top < _limit_down ) {
                    ui.position.top = _limit_down;
                } 
                
                if ( _left > 0 ) {
                    ui.position.left = 0;
                } else if ( _left < _limit_right ) {
                    ui.position.left = _limit_right;
                } 
                //*/
            }
        });
        
        $(f).addClass("cover_move");
        $(f).addClass("ui-widget-content");
        
        return f;
    };
    
    var _f_CrtThumbnail = function(fl,dc) {
        try {
            
            if ( KgbLib_CheckNullity(fl) ) {
                return;
            }
            
            //dc = DefaultCase, Le cas où on remets l'image par défaut
            if (! _f_IsSquare(fl.height, fl.width) ) {
                //[NOTE 30-06-14] A la version à sortir, le traitement des images non carrées a été abondoné (reporté).
                /*
                 /* On va afficher l'image et laisser l'utilisateur définir la position 
                 
                 //on met en place le mécanisme draggable
                 f = gt._ImageIsDraggable(f);
                 
                 $(".jb-p-h-b-ui-img").fadeOut().remove();
                 
                 $(f).attr("id","p-h-b-ui-img").hide().appendTo(".jb-p-h-b-ui-trg").fadeIn();
                 //*/
                
            } else {
                //Dans tous les cas on met l'image à la taille MAX pour des suocis de présentation
//            if ( ( parseInt(f.height) > gt.__ZInMax ) || ( parseInt(f.width) > gt.__ZInMax ) ) {
                var oo = new Cropper();
                fl = oo.Cropper_ResizeTo(fl, _f_Gdf().__ZInMax, _f_Gdf().__ZInMax);
//            }
        
                if (! dc ) {
//                    Kxlib_DebugVars([433,_oldPic],true);
                    //on met en mode stand-by l'ancienne image
//                    _oldPic = $(".jb-p-h-b-ui-img").clone();
//                    _oldPic = $(".jb-p-h-b-ui-img").clone(true);
//                    _oldPic = $(".jb-p-h-b-ui-img").clone(true, true);
//                    Kxlib_DebugVars([438,_oldPic],true);
                    /*
                     * [NOTE 30-06-14] @BOR
                     * Ce code cause un bug que je n'ai pas su résoudre. J'ai changé pour la ligne précédente.
                     */
//                    $(".jb-p-h-b-ui-img").remove();
//                    $(".jb-p-h-b-ui-img").fadeOut().remove();
//                    $(".jb-p-h-b-ui-img").fadeOut().off().remove();
                   /*
                    * [NOTE 25-06-15] @BOR
                    *   On change de méthode, on utilise maintenant une image tiers.
                    *   La solution est plus simple à gérer et plus stable. 
                    */
                    $(".jb-p-h-b-ui-img").off().fadeOut({
                        complete: function() {
                            /*
                             * [NOTE 19-11-15] @BOR
                             */
                            $(".jb-p-h-b-ui-img-buffer").remove();
                            /*
                             * On enregistre l'image pour pouvoir l'envoyer au niveau du serveur.
                             * L'image est codée en base64, si on encode pas
                             */
                            _newPic = fl.src;
                            var i__ = $("<img/>").attr({
                                "id"    : "p-h-b-ui-img-buffer",
                                "class" : "jb-p-h-b-ui-img-buffer",
                                "width" : "90px",
                                "height": "90px",
                                "src"   : fl.src
                            });
                            i__ = _f_Rebind(i__);
                            $(i__).appendTo(".jb-p-h-b-ui-trg");
                        }
                    });
                    
                    /*
                    fl = _f_Rebind($(fl));
                    $(fl).attr({
                        "id"    : "p-h-b-ui-img",
                        "class" : "jb-p-h-b-ui-img"
                    }).appendTo(".jb-p-h-b-ui-trg");
                    //*/
                    
                    /*
                     * [DEPUIS 28-06-15] @BOR
                     * On change le bouton d'action
                     */
                    if ( $(".jb-p-h-b-ui-f-a-ch[data-always='y']").length ) {
                        $(".jb-p-h-b-ui-f-a-ch[data-always='y']").data("action","save");
                        $(".jb-p-h-b-ui-f-a-tle").text(Kxlib_getDolphinsValue("COMLG_save"));
                    }
                    //On affiche la zone FINAL DECISION
                    _f_ShwFnlDecMns();
                    
                } else {
                    //On enleve tous les anciens bind sinon on aura un syteme de double écoute
                    $(fl).off();
                } 
                
//            $(f).attr("id","p-h-b-ui-img").hide().appendTo(".jb-p-h-b-ui-trg").fadeIn();
//            $(f).attr("id","p-h-b-ui-img").appendTo(".jb-p-h-b-ui-trg");
                
//                _f_Rebind($(fl)); //ICI
                
                /*
                 Kxlib_DebugVars("SOURCE RAW = "+img.src);
                 Kxlib_DebugVars("SOURCE SECURED = "+Kxlib_encodeURL(img.src));
                 //*/
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_Rebind = function (e) {
        try {
            
            if ( KgbLib_CheckNullity(e) ) {
                return;
            }
            
            $(e).off("click").click(function(e) {
//            $(e).off().click(function(e) {
                Kxlib_PreventDefault(e);
//            alert("Rebind Click");
                _f_HdleDashMn(this);
            });
            
            $(e).off("hover").hover(function() {
//            $(e).off().hover(function() {
//                Kxlib_DebugVars("Rebind : Hover In");
//                alert("Rebind Hover In");
                _f_ZmInPic(this);
            }, function() {
//                Kxlib_DebugVars("Rebind : Hover Out");
//                alert("Rebind Hover Out");
                _f_ZmOutPic(this);
            });
            
            return e;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
            
    var _f_CrtRbndIptFile = function () {
//    this._CreateRebindInputFile = function () {
        try {
            
            /* Cette manière de faire est RADICALE mais très efficace. */
            var O = new TMLNR_PFLHDR(), e = $("<input/>").attr({
                id: "p-h-b-ui-a-file",
                type: "file",
                autocomplete: "off"
            });
            //On supprime l'ancien input
            $(".jb-p-h-b-ui-a-file").remove();
            
            $(e).click(function(e) {
                /* Permet de ne pas remonter vers le parent qui a une fonction "preventDefault" */
                Kxlib_StopPropagation(e);
            });
            
            $(e).change(function(e) {
                var f = this.files;
//            alert(f.length);
                //C'est le seul cas où il y a un deuxième paramètre
                _f_ChkOpe(this, f);
            });
            
            $(e).appendTo(".jb-p-h-b-ui-action[data-action=change]");
            
            //Si quelqu'un on veut
            return e;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /******************/
    
    var _f_ConfDfltPic = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            /*
             * [NOTE 28-06-15] @BOR
             * On affiche la zone de décision et on change le paramètre d'action.
             */
            if ( $(".jb-p-h-b-ui-f-a-ch[data-always='y']").length ) {
                $(".jb-p-h-b-ui-f-a-ch[data-always='y']").data("action","delete");
                $(".jb-p-h-b-ui-f-a-tle").text(Kxlib_getDolphinsValue("COMLG_confirm"));
            }
            _f_ShwFnlDecMns();
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_SetDfltPic = function() {
//    this.SetDefaultPicture = function () {
        /* Définit l'image de profil en mettant celle par défaut */
        //Cette solution a été abondonné au profit de celle plus bas
//        $(".jb-p-h-b-ui-img").data("is-default",1).attr("src",this.dt);
//        $(".jb-p-h-b-ui-trg").data("is-default",1);
//        alert($(".jb-p-h-b-ui-a-file").val());
        try {
            
            /*
             * [DEPUIS 28-06-15] @BOR
             * TODO : On vérifie que la zone ne comporte pas déjà une image par défaut.
             */
            
           
            var s = $("<span/>");
            _f_CreateDfltPic(s);
            
            $(s).on("operended", function() {
                
                /* On reset l'input. (Méthode radicale) */
                _f_CrtRbndIptFile();
                
                //On récupère l'image de façon "asynchrone"
                var i = _dftPic;
                
                //On enlève l'ancienne image
                $(".jb-p-h-b-ui-img").hide().remove();
                
                //On ajoute l'id 
                $(i).attr("id","p-h-b-ui-img");
                
                //On ajoute l'image
                $(i).hide().appendTo(".jb-p-h-b-ui-trg").fadeIn();
                
                //Avertir le serveur
                var s2 = $("<span/>");
                
                _f_Srv_SetDefaultPic(s2);
                
                //On cache les menus de fin de décision
                _f_HidFnlDecMns();
                //On cache les menus Dash
                $(".jb-p-h-b-ui-act-box").addClass("this_hide", 500, function() {
                });
                
                //On masque le l'écran qui indique que le traitement est en cours
                _f_ShwWtPnl();
                
                //On retire le binding qui compte le nombre d'erreurs. Cela va le réinitialiser.
                $(".jb-p-h-b-ui-trg").removeData("ec");
                
                        
                $(s2).on("operended", function(e) {
                    
                    _f_HdWtPnl();
                    
                    /*
                     * [DEPUIS 29-06-15] @BOR
                     * On dezoom
                     */
                    _f_HidDashMn(_dftPic);
                    _f_ZmOutPic(_dftPic);
                    //Permet d'éviter que l'utilisateur effectue une autre action
                    $(".jb-p-h-b-ui-trg").data("lk",1);
                    
                   /*
                    * [DEPUIS 18-07-15] @BOR
                    * On notifie l'utilisateur que l'opération s'est déroulée avec succès
                    */
                   var code = "ua_pp_set"; 
                   var Nty = new Notifyzing();
                   Nty.FromUserAction(code);
                
                    /*
                    //Afficher la notification ? (Plutot NON que Oui)
                    var Nty = new Notifyzing();
                    Nty.FromUserAction("ua_pflbio_set"); 
                    */
                
                    /*
                     * [DEPUIS 18-07-15] @BOR
                     * On fait apparaitre le message qui indique que le rechargement est en cours
                     */
                    if ( $(".jb-pg-sts").length ) {
                        $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Loading")+"...");
                        $(".jb-pg-sts").removeClass("this_hide");
                    }
                    
                    setTimeout(function() {
                        /*
                         * On est obligé de Reload la page car il faut changer TOUTES les images de profil affichées.
                         * Le plus simple est donc de recharger le page. L'autre avantage est de mettre à jour toutes les autres données.
                         */
                        location.reload();
                    }, 2000);
                });
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_CreateDfltPic = function (s) {
//    this.CreateDefaultPic = function (s) {
        /* Permet de créer une image de type default */
        try {
            
            //On crée la balise avec src
            $("<img/>").attr({
                "src": _f_Gdf().dt
            }).height(45).width(45).load(function() {
                
                $(this).off();
                
                $(this).css({
                    height: "90px", 
                    width: "90px"
                });
                
                _f_Rebind($(this));
                
                _dftPic = $(this);
                
                $(s).trigger("operended");
                
                return;
            }).error(function() {
                //TODO : Décider d'une procédure à adopter en cas d'erreur dans le chargement de l'image
            });
            
//        return i;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /**************** FINAL DECISION ***************/
    
    var _f_Save = function () {
        try {
            
            //Envoyer les données au serveur
            var w = $(".jb-p-h-b-ui-img").css("width"), h = $(".jb-p-h-b-ui-img").css("height"), t = $(".jb-p-h-b-ui-img").css("top"), l = $(".jb-p-h-b-ui-img").css("left"), s = $("<span/>");
//        "img","in","ih","iw","it","cl"
            var ds = {
                "img"   : _newPic,
                "name"  : _newPic_fn,
                "h"     : h.replace("px", ""),
                "w"     : w.replace("px", ""),
                "top"   : (t === "auto") ? "0" : t.replace("px", ""),
                "left"  : (l === "auto") ? "0" : l.replace("px", "")
            };
            
//        Kxlib_DebugVars([d.name,d.w,d.h,d.top,d.left],true);
            
            //Envoyer les données au serveur
            _f_Srv_SetNewPic(ds, s);
            
            /*
             * On cache les différents menus de fin de décision. 
             * Cela nous évite entre autres de gérer le cas où l'utilisateur essai de changer d'image entre deux.
             */
            //On cache les menus de fin de décision
            _f_HidFnlDecMns();
            //On cache les menus Dash
            $(".jb-p-h-b-ui-act-box").addClass("this_hide", 500, function() {
            });
            
            //On montre l'écran qui indique que le traitement est en cours
            _f_ShwWtPnl();
            
            //On retire le binding qui compte le nombre d'erreur. Cela va le réinitialiser
            $(".jb-p-h-b-ui-trg").removeData("ec");
            
            $(s).on("datasready", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                //Remplacement de l'image
                /* RAPPEL : Structure
                 {   "pp_datas": {
                 "pp_rpath":""
                 },
                 "o_pbio":"0",
                 "o_cap":"0",
                 "o_pnb":0,
                 "tr_nb":0
                 }
                 //*/
                
                /*
                 //On vérifie si un autre changement n'est pas déjà en cours le temps qu'on est Aller et Revenu
                 if ( $("#cov_img_buffer").length ) {
                    return;
                 }
                 
                 if (! $("#a-h-t-top-img").length ) {
                    return;
                 }
                
                 //On crée l'image
                 var $im = $("<img/>");
                 $($im).attr({
                    "id":"a-h-t-top-img_new",
                    "src": d.cov_datas.acov_rpath,
                    "style": "top: "+d.cov_datas.acov_top+"px; left: 0; position: absolute; z-index: 3;",
                    "height": d.cov_datas.acov_height,
                    "width": d.cov_datas.acov_width
                 });
                 $("#a-h-t-top-img-max").prepend($im);
                 
                 //Supprime l'ancienne image après quelques secondes sinon il y a un effet de cassure visible. C'est la meilleur solution à l'heure d'aujourd'hui
                 setTimeout(function(){
                 $("#a-h-t-top-img").remove();
                 
                 $("#a-h-t-top-img_new").attr({
                    "id":"a-h-t-top-img",
                    "style": "top: "+d.cov_datas.acov_top+"px;",
                    "class": "a-h-t-top-img"
                 });
                 
                 //On supprime le "no-img"
                 $("#a-h-t-top-noimg").remove();
                 },3000);
                 //*/
                
                //On masque l'écran qui indique que le traitement est en cours
                _f_HdWtPnl();
                
                _f_HidDashMn();
                
                /*
                 * [DEPUIS 25-06-15] @BOR
                 * On retire l'image en buffering
                 */
                $(".jb-p-h-b-ui-img-buffer").fadeOut({
                    complete : function(){
                        var ni = $("<img/>").attr({
                            "id"        : "p-h-b-ui-img",
                            "width"     : 90,
                            "height"    : 90,
                            "src"       : d.pp_datas.pp_rpath
                        });
                        $(".jb-p-h-b-ui-img").replaceWith(ni);
                        _f_ZmOutPic(ni);
                       /*
                        * [DEPUIS 29-06-15] @BOR
                        * Permet d'éviter que l'utilisateur effectue une autre action
                        */
                       $(".jb-p-h-b-ui-trg").data("lk",1);
                    }
                });
                

                //On notifie l'utilisateur que l'opération s'est déroulée avec succès
                var code = "ua_pp_set"; 
                var Nty = new Notifyzing();
                Nty.FromUserAction(code);
                
                /*
                 * [DEPUIS 25-06-15] @BOR
                 * On fait apparaitre le message qui indique que le rechargement est en cours
                 */
                 if ( $(".jb-pg-sts").length ) {
                    $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Loading")+"...");
                    $(".jb-pg-sts").removeClass("this_hide");
                }
                
                setTimeout(function() {
                    /*
                     * On est obligé de Reload la page car il faut changer TOUTES les images de profil affichées.
                     * Le plus simple est donc de recharger le page. L'autre avantage est de mettre à jour toutes les autres données.
                     */
                    location.reload();
                }, 2000);
                
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_ChkFnlOpe = function (x) {
//    this.CheckFinalOperation = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            
//            Kxlib_DebugVars([$(x).data("action")],true);
//            return;
            
            var a = $(x).data("action");
            switch (a.toLowerCase()) {
                case "cancel" :
                        _f_Abort();
                    break;
                case "save" :
                        _f_Save();
                    break;
                case "delete" :
                        _f_SetDfltPic();
                        //Hide les choix
                        _f_HidFnlDecMns();
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /*********************************************************************************************************************************************/
    /************************************************************ AJAX - SERVER SCOPE ************************************************************/
    /*********************************************************************************************************************************************/
    
    var _Ax_SetDfltPic = Kxlib_GetAjaxRules("TMLNR_SET_DFTPIC", Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_SetDefaultPic = function (s) {
//    this._Srv_SetDefaultPic = function (s) {
        if ( KgbLib_CheckNullity(s) ) {
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
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_IMG_NOT_COMPLY":
                                    return;
                            case "__ERR_VOL_FAILED":
                                    //TODO : Retirer l'image en Buffer
                                    //Afficher le message adéquat dans la zone d'erreur
                                    return;
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    return;
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) ) {
                    $(s).trigger("operended");
                }
                
            } catch (ex) {
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
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        /*
         * L'URL est envoyée pour s'assurer une enième fois que l'utilisateur est bel et bien sur son compte.
         * Si l'utilisateur réussi a introduire des fonctionnalités de changement de photo de profil alors qu'il n'est pas sur son compte ...
         * cela ne pourra pas se faire. Il existe donc une garantie supplémentaire au fait qu'au final on modifie le compte de l'utilisateur actif.
         */
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_SetDfltPic.urqid,
            "datas": {
                "cl": curl
            }
        };
        
        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_SetDfltPic.url, wcrdtl : _Ax_SetDfltPic.wcrdtl });
    };
    
    var _Ax_NewPflPic = Kxlib_GetAjaxRules("TMLNR_SET_PFLPIC", Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_SetNewPic = function (ds,s) {
//    this._Srv_SetNewPicture = function (ds,s) {
        if ( KgbLib_CheckNullity(ds) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
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
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_IMG_NOT_COMPLY":
                                break;;
                            case "__ERR_VOL_FAILED":
                                    //TODO : Retirer l'image en Buffer
                                    //Afficher le message adéquat dans la zone d'erreur
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) ) {
                    /*
                     * Données attendues :
                     *  (1) Les données sur l'image de profil
                     *      -> L'url
                     *  (2) Des données extras :
                     *      -> profil_bio
                     *      -> capital
                     *      -> posts_nb
                     *      -> trend_nb
                     *      
                     *  Les autres données auraient concernées l'image. Or, son adresse physique est disponible au niveau du module.
                     */
                    var rds = [d.return];
                    $(s).trigger("datasready",rds);
                } else return;
                
            } catch (ex) {
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
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };

        /*
         * L'URL est envoyée pour s'assurer une enième fois que l'utilisateur est bel et bien sur son compte.
         * Si l'utilisateur réussi a introduire des fonctionnalités de changement de photo de profil alors qu'il n'est pas sur son compte ...
         * cela ne pourra pas se faire. Il existe donc une garantie supplémentaire au fait qu'au final on modifie le compte de l'utilisateur actif.
         */
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_NewPflPic.urqid,
            "datas": {
                "img": encodeURIComponent(ds.img),
                "in": ds.name,
                "iw": ds.w,
                "ih": ds.h,
                "it": ds.top,
                "cl": curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_NewPflPic.url, wcrdtl : _Ax_NewPflPic.wcrdtl });
    };
    
    
    /*****************************************************************************************************************************************/
    /************************************************************** VIEW SECTOR **************************************************************/
    /*****************************************************************************************************************************************/

    this.ShowPflipicRulesPanel = function () {
        $(".jb-p-h-ui-hlp-pan").hide().removeClass("this_hide").fadeIn();
        $(".jb-p-h-ui-hlp-pan").removeAttr("style");
    };

    var _f_HidPflipicRulesPanel = function () {
//    this.HidePflipicRulesPanel = function () {
        $(".jb-p-h-ui-hlp-pan").addClass("this_hide");
    };

    var _f_ZmInPic = function(a,force) {
//    this.ZoomInPic = function(a,force) {
        try {
            //La variable v, permet de signaler qu'il s'agit de l'opération d'ouverture des menus
            if ( KgbLib_CheckNullity(a) | $(".jb-p-h-b-ui-trg").data("lk") === 1 ) {
                return;
            }
            
            setTimeout(function() {
                if ( !$(".jb-p-h-b-ui-i-fade").is(":hover") && KgbLib_CheckNullity(force) ) {
//                if ( !$(a).is(":hover") && KgbLib_CheckNullity(force) ) { //[DEPUIS 20-09-15] @author BOR
                    return; 
                } 
                
                $(a).stop(true,true).animate({
                    height: _f_Gdf().__ZInMax,
                    width: _f_Gdf().__ZInMax
                }, function() {
//                Kxlib_DebugVars("Zoom In finished !");
                    
//                alert("ICI => "+typeof $(a).parent().data("lk"));
//                if ( !KgbLib_CheckNullity(v) && v.length ) $(v).trigger("operended");
//                if ( $(a).parent().data("lk") === 1 ) alert("Marie !!!");
                });
            }, 250);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ZmOutPic = function(v,impt) {
//    this.ZoomOutPic = function(v) {
        try {
            var a = ( KgbLib_CheckNullity(v) ) ? $(".jb-p-h-b-ui-img") : v;
            if ( $(".jb-p-h-b-ui-trg").data("lk") === 1 && !( !KgbLib_CheckNullity(impt) && impt === true ) ) {
                return;
            }
            
            $(a).stop(true,true).animate({
                height: _f_Gdf().__ZOutMax,
                width: _f_Gdf().__ZOutMax
            }, function() {
//            Kxlib_DebugVars("Zoom Out finished !");
//            $(a).fadeOut().fadeIn();
                //Pour résoudre le bug du doubleclick
//            $("#p-h-b-ui-trg").removeClass("p-h-b-ui-trg-zm",500);  
                //Il ne s'agit que d'un effet visuel. Il s'agit d'une opération dépendant de le stratégie fonctionnelle du produit.
                //[DEPUIS 26-06-15] @BOR
                /* 
                setTimeout(function() {
                    if ( $(".jb-p-h-b-ui-trg").data("lk") === 0 ) {
                        $("#p-h-b-upsd-psd").fadeOut(1000).fadeIn(1000);
                    }
                },1000);
                //*/
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_ShwDashMn = function (x) {
//    this.OpenDashMenu = function (x) {
        //[NOTE 29-06-14] Les utilisations de parent() nous permette de continuer à faire fonctionner le code même si on change
        // ... le sélecteur au niveau du parent. Le code devient alors plus "solide".
        try {
            
            /* 
             * Permet de faire apparaitre le menu qui permettra ensuite supprimer sa photo ou de la changer 
             */
            //dm-status : DashMenu-Status (0: La zone de menus n'est pas visible; 1: LA zone est visible
            $(x).parent().data("lk", 1);
//        alert("DEBUG "+typeof $(".jb-p-h-b-uimg").data("dm-status"));
            if ( $(".jb-p-h-b-uimg").data("dm-status") === 0 ) {
                
                /*
                 * ETAPE :
                 * On vérifie s'il s'agit du cas de l'image par défaut
                 * */
                if ( $(".jb-p-h-b-ui-trg").data("dft") === 1 ) {
                    $(".jb-p-h-b-ui-action[data-action='delete']").addClass("this_hide");
                } else {
                    $(".jb-p-h-b-ui-action[data-action='delete']").removeClass("this_hide");
                }
                
                /* Mise en place des menus */
                //on met du padding autour de l'image
                $(x).parent().parent().addClass("p-h-b-ui-trg-box-zm", 500);
                //On habille le grand cadre
                $(x).parent().parent().parent().addClass("p-h-b-uimg-zm", 500);
                
                //On fait apparaitre les menus
                $(".jb-p-h-b-ui-act-box").removeClass("this_hide", 500);
                
                setTimeout(function() {
                    //On fait de telle sorte que le cadre de l'image ne depasse pas MAX
                    if ( $(x).parent().data("lk") === 1 )
                        $(x).parent().addClass("p-h-b-ui-trg-zm", 500);
                }, 500);
                
                //Au cas où entre temps on a décidé de faire marche arrière
//            if (! $(x).parent().data("lk") ) this.CloseDashMenu($(x));
                
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_HidDashMn = function (x) {
//    this.CloseDashMenu = function (x) {
        //[NOTE 29-06-14] 
        try {
            
            var o = ( KgbLib_CheckNullity(x) ) ? $(".jb-p-h-b-ui-img") : x;
            if (! $(o).length ) {
                return;
            }
            
            /* Mise en place des menus */
            //On fait disapparaitre les menus
            $(".jb-p-h-b-ui-act-box").addClass("this_hide",500,function() {
            });
            //On enlève le padding autour de l'image
            $(o).parent().parent().removeClass("p-h-b-ui-trg-box-zm", 500);
            //On delock la taille de l'encart
            $(o).parent().removeClass("p-h-b-ui-trg-zm", 500);
            
            //on met en forme la zone autour de l'image
            $(".jb-p-h-b-uimg").removeClass("p-h-b-uimg-zm", 500);
            
            $(o).parent().data("lk",0);
            
            //On ferme la fenetre d'aide
            _f_HidPflipicRulesPanel();
            
            //On retire le binding qui compte le nombre d'erreur. Cela va le réinitialiser
            $(".jb-p-h-b-ui-trg").removeData("ec");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_HdleDashMn = function (x) {
//    this.HandleDashMenu = function (x) {
        if ( !$(".jb-p-h-b-ui-fnl-act").hasClass("this_hide") | !$(".jb-p-h-b-ui-wt").hasClass("this_hide") ) {
            return;
        }

        if ( $(x).parent().data("lk") === 0 ) {
            _f_ZmInPic(x,true);
            _f_ShwDashMn(x);
        } else {
            _f_HidDashMn(x);
        }
    };
    
    var _f_ShwFnlDecMns = function () {
//    this.OpenFinDecMenus = function () {
        $(".jb-p-h-b-ui-fnl-act").hide().removeClass("this_hide").fadeIn(500);
    };
    
    var _f_HidFnlDecMns = function () {
//    this.CloseFinDecMenus = function () {
        $(".jb-p-h-b-ui-fnl-act").animate({
            duration: 100,
            height: "0px"
        }, function(){
            $(this).addClass("this_hide");
            $(this).removeAttr("style");
        });
    };
    
    var _f_ShwWtPnl = function () {
        $(".jb-p-h-b-ui-wt").removeClass("this_hide");
    };
    
    var _f_HdWtPnl = function () {
        $(".jb-p-h-b-ui-wt").addClass("this_hide");
    };
    
    /********* FINAL DECISION ********/
    
    var _f_Abort = function () {
        try {
            
           /*
            * [NOTE 25-06-15] @BOR
            * On chose de méthode, on utilise maintenant une image tiers.
            * La solution est plus simple à gérer et plus stable. 
            */
            $(".jb-p-h-b-ui-img-buffer").fadeOut({
                complete : function() {
                    $(".jb-p-h-b-ui-img").fadeIn();
                    $(this).off().remove();
                }
            });
            /* 
             * Remettre l'ancienne image (IL NE FAUT SURTOUT PAS ESSAYER DE CHANGER SRC 
             * */
            
            /*
            $(".jb-p-h-b-ui-img").fadeOut().off().remove();
//            $(".jb-p-h-b-ui-img").fadeOut().remove();
            
            Kxlib_DebugVars([1180,typeof _oldPic],true);
            
            var $im = $(_oldPic);
            $im.hide().appendTo(".jb-p-h-b-ui-trg").fadeIn();
//            $im.hide().appendTo(".jb-p-h-b-ui-trg").fadeIn().off();
//            $(".jb-p-h-b-ui-img").off();
//            $im.off();
//            _f_Rebind($im); //ICI
            
            _oldPic = undefined;
            Kxlib_DebugVars([1190,typeof _oldPic],true);
            //*/
            //Hide la zone Final Decision
            $(".jb-p-h-b-ui-fnl-act").addClass("this_hide");
            var id;
            id = setTimeout(function() {
                //Fermer DashMenu
                _f_HidDashMn();
                
                //Dézoomer
                _f_ZmOutPic();
                
                //On recree l'input pour éviter les cas où on ne peut plus ajouter la même image
                _f_CrtRbndIptFile();
                
                //On retire le binding qui compte le nombre d'erreur. Cela va le réinitialiser
                $(".jb-p-h-b-ui-trg").removeData("ec");
                
            },600); //[NOTE 25-06-15] @BOR 600 est idéal pour que l'animation reste fluide.
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /**********************************************************************************************************************************************/
    /************************************************************** LISTENERS SECTOR **************************************************************/
    /**********************************************************************************************************************************************/
    
    $(".jb-p-h-b-ui-action").click(function(e){
        /* Si on est arrivé ici c'est forcement parce qu'il ne s'agit pas ...
         * ... du menu pour changer l'image de profil.
         * On passe quand même par CheckOperation pour tous les autres.
         * */
        Kxlib_PreventDefault(e);
        
        _f_ChkOpe(this);
    });
    
    /*
     * [NOTE 25-06-15] @BOR
     *  -> Ajouter des off()
     */
    
    
//    $(".jb-p-h-b-ui-img").off("click").click(function(e){ //[DEPUIS 20-09-15] @author BOR
//    $(".jb-p-h-b-ui-img").click(function(e){
//    $(".jb-p-h-b-ui-i-fade").off("click").click(function(e){
    $(".jb-p-h-b-ui-i-fade").off("dblclick").dblclick(function(e){
        Kxlib_PreventDefault(e);
       
        var _this = $(this).closest(".jb-p-h-b-ui-trg").find(".jb-p-h-b-ui-img");
        _f_HdleDashMn(_this);
//        _f_HdleDashMn(this);
    });
    
    $(".jb-p-h-b-ui-trg > *").off("hover").hover(function(e){
//    $(".jb-p-h-b-ui-img").off("hover").hover(function(e){ //[DEPUIS 20-09-15] @author BOR
//    $(".jb-p-h-b-ui-img").hover(function(e){
//        Kxlib_DebugVars("-> Bind : Hover In");
        var _this = $(this).closest(".jb-p-h-b-ui-trg").find(".jb-p-h-b-ui-img");
        _f_ZmInPic(_this);
//        _f_ZmInPic(this); 
    },function(){
//        Kxlib_DebugVars("-> Bind : Hover Out");
        var _this = $(this).closest(".jb-p-h-b-ui-trg").find(".jb-p-h-b-ui-img");
        _f_ZmOutPic(_this);
//        _f_ZmOutPic(this); 
    });
   
    $(".jb-p-h-b-ui-a-file").click(function(e){
        /* Permet de ne pas remonter vers le parent qui a une fonction "preventDefault" */
        Kxlib_StopPropagation(e);
    });
    
    $(".jb-p-h-b-ui-a-file").change(function(e){
        var fl = this.files;
        
        //C'est le seul cas où il y a un deuxième paramètre
        _f_ChkOpe(this,fl);
    });
    
    /******* FINAL DECISION **********/
    $(".jb-p-h-b-ui-f-a-ch").off("click").click(function(e){
        Kxlib_PreventDefault(e);
       
        _f_ChkFnlOpe(this);
    });
    
}

new TMLNR_PFLHDR();