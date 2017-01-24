/*
var _z = function(tg) {
    var _par_id = $(tg).parent().attr("id");

    switch (_par_id) {
        case "menu-li-post":
                $(tg).toggleClass("hover-post");
            break;
        case "menu-li-tr":
                $(tg).toggleClass("hover-tr");
            break;
        case "menu-li-clb":
                $(tg).toggleClass("hover-clb");
            break;
        default:
            break;
    }
};
//*/
  
(function() {
    var _par = $(".menu-selected").parent();
    var _par_id = $(".menu-selected").parent().attr("id");
    
    switch (_par_id) {
        case "menu-li-post":
                $(_par).addClass("hover-post");
            break;
        case "menu-li-tr":
                $(_par).addClass("hover-tr");
            break;
        case "menu-li-clb":
                $(_par).addClass("hover-clb");
            break;
        default:
            break;
    }
})();



/*
 * [NOTE 17-09-14] @author L.C.
 * Permet de gérer tous les processus liés à l'image de couverture.
 * Le module a été créé pour rendre plus lisible l'ancien code et le rendre plus cohérent
 */
function ACCHDR () {
    var _FnlImg;
//    HdlOpnAtHmMns

    var _xhr_dlcvr;
    var _xhr_chcvr;
    
    /***********************************************************************************************************************************************/
    /**************************************************************** PROCESS SCOPE ****************************************************************/
    /***********************************************************************************************************************************************/
    
    var _f_GIR = function () {
//    this.GetImageRules = function () {
        /*
         * [NOTE 18-09-14] @author L.C.
         * Les dimensions max et min ont été choisies arbritairement.
         * Pour les dimensions mins je considère que c'est une limite raisonnable.
         * Pour la limite max, je me suis basée sur des images lourdes HD que j'avais sur la main.
         * Les plus grandes avaient un peu plus de 5000px.
         */
        var ir = {
            "ft": ["png","jpg","jpeg"],
            "sz": 2621440,
            "w_min": 250,
            "w_max": 5500,
            "h_min": 250,
            "h_max": 5500
        };
        
        return ir;
    };
    
    var _f_Action = function(x) {
        try {
            if (KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).parent().attr("id"))) {
                return;
            }
            
            //ParenID
            var _pid = $(x).parent().attr("id");
            switch (_pid) {
                case "menu-li-post":
                        $(x).toggleClass("hover-post");
                    break;
                case "menu-li-tr":
                        $(x).toggleClass("hover-tr");
                    break;
                    /*
                case "menu-li-clb":
                        $(x).toggleClass("hover-clb");
                    break;
                    */
                default:
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    this.CheckOperation = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
                    
            var a = $(x).data("action");
            switch (a) {
                case "firstvisit" :
                        $(".jb-tqr-fdry-invt-strt-alwz").click();
//                        $(".jb-tmlnr-hdr-hmbx").addClass("disable");
                        /*
                         * [DEPUIS 22-11-15]
                         */
                        _f_ShwAtHmMns();
                        $(".jb-tmlnr-athm-mn-bmx").data("state","");
                    break;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_AtHmMns = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
                    
            var a = $(x).data("action");
            switch (a) {
                case "open-menu" :
                        _f_HdlOpnAtHmMns();
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_HdlOpnAtHmMns = function(e,x,frc) {
        //frc : Force (TRUE|FALSE)
        try {
            
            /*
             * [NOTE 01-07-15] @BOR
             * On ne doit pas afficher le menu, si la box qui affiche l'invitation au tuto est ouverte.
             */
            if ( frc === false | $(".jb-tmlnr-athm-mn-bmx").data("state") === "activate" ) {
//                alert("FERMER");
                _f_ShwAtHmMns();
                $(".jb-tmlnr-athm-mn-bmx").data("state","");
                /*
                 * [NOTE 01-07-15] @BOR
                 *      Permet d'éviter une boucle infinie qui ferait planter lamentablement la page.
                 * [NOTE 01-07-15 02:45] @BOR
                 *      Faute de conception
                 */
                if ( KgbLib_CheckNullity(e) || ( e.type.toString().toLowerCase() !== "blur" && e.type.toString().toLowerCase() !== "focusout" ) ) {
                    $(".jb-tmlnr-hdr-hmbx").blur();
                }
            } else if ( ( frc === true | KgbLib_CheckNullity($(".jb-tmlnr-athm-mn-bmx").data("state")) | $(".jb-tmlnr-athm-mn-bmx").data("state") !== "activate" ) 
                    && ( !$(".jb-tqr-fdry-invit-bmx").length || $(".jb-tqr-fdry-invit-bmx").hasClass("this_hide") )
                    && $(".jb-tqr-fry-bx-bmx").not(".this_hide").length === 0 ) 
            {
//                alert("OUVRIR");
                _f_ShwAtHmMns(true);
                $(".jb-tmlnr-athm-mn-bmx").data("state","activate");
                $(".jb-tmlnr-hdr-hmbx").focus();
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_VrfyComfy = function(i,w,h) {
//    this.VerifyComfy = function(i,w,h) {
        try {
            
            if ( KgbLib_CheckNullity(i) ) {
                return;
            }
            
            //ImageRules
            var ir = _f_GIR();
            
            //Verifie : format, taille et dimensions
            if (! i.type.match('image/*') ) {
                return "__COV_BAD_FILE";
            } 
            
            /*
             * Il peut arriver qu'on utilise cette méthode juste pour déterminer si le fichier est une image sans pour autant aller plus loin.
             * Aussi, on ne vérifiera les autres informations que si la largeur et la hauteur sont tous deux définies
             */
            if ( !KgbLib_CheckNullity(w) && !KgbLib_CheckNullity(h) ) {
                
                var filef = i.type.split('/').pop();
                
                if ($.inArray(filef, ir.ft) === -1) {
                    //Est ce que le type de l'image est autorisé
                    return "__COV_BAD_TYPE";
                } else if (i.size > ir.sz) {
                    //Est ce que le type de l'image est autorisée
                    return "__COV_BAD_SIZE";
                } else {
                    //QUESTION : Est ce que l'image respecte les dimensions minimales et maximales ?
                    
                    //Controle de la hauteur
                    if (h < ir.h_min) {
                        return "__COV_BAD_DIMS_MIN";
                    } else if (h > ir.h_max) {
                        return "__COV_BAD_DIMS_MAX";
                    } 
                    //Controle de la largeur
                    if (w < ir.w_min) {
                        return "__COV_BAD_DIMS_MIN";
                    } else if (w > ir.w_max) {
                        return "__COV_BAD_DIMS_MAX";
                    } 
                }
            }
            
            return true;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_OnError = function (c) {
//    this.OnError = function (c) {
        //c : Code erreur
        try {
            
            if (KgbLib_CheckNullity(c)) {
                return;
            }
            
            c = c.toUpperCase();
            //m : Le message à afficher; t : Le temps durant lequel le message restera affiché
            var m, t = 0;
            switch (c) {
                case "__COV_BAD_FILE":
                    m = Kxlib_getDolphinsValue("ERR_COV_BAD_FILE");
                    break;
                case "__COV_BAD_TYPE":
                    m = Kxlib_getDolphinsValue("ERR_COV_BAD_TYPE");
                    break;
                case "__COV_BAD_SIZE":
                    m = Kxlib_getDolphinsValue("ERR_COV_BAD_SIZE");
                    break;
                case "__COV_BAD_DIMS_MIN":
                    m = Kxlib_getDolphinsValue("ERR_COV_BAD_DIMS_MIN");
                    break;
                case "__COV_BAD_DIMS_MAX":
                    m = Kxlib_getDolphinsValue("ERR_COV_BAD_DIMS_MAX");
                    break;
                default :
                    m = Kxlib_getDolphinsValue("ERR_GEN_FAILED");
                    t = 7000;
                    break;
            }
            
            //On vérifie le temps d'affichage. S'il a déjà été définie, on passe
            if (typeof t === "undefined" || t === 0) {
                t = 4000;
            }
            
            //On s'assure qu'il n'y a pas déjà un message affiché
            if ($(".jb-cov-err-pan").hasClass("this_hide")) {
                //On ajoute le message d'erreur
                $(".jb-cov-err-inner").text(m);
                
                //On affiche le panel
                $(".jb-cov-err-pan").removeClass("this_hide");
//            Kxlib_DebugVars([$(".jb-cov-err-pan-ctr").outerHeight()],true);
                
                //Apres quelques secondes, on fat disparaitre le panel
                setTimeout(function() {
                    //On fait disparaitre le panel par slide
                    var h = $(".jb-cov-err-pan-ctr").outerHeight();
                    h = parseInt(h) * -1;
                    $(".jb-cov-err-pan-ctr").animate({
                        top: h
                    }, 300, function() {
                        //On cache le panel
                        $(".jb-cov-err-pan").addClass("this_hide");
                        //On le vide du message
                        $(".jb-cov-err-inner").text("");
                        
                        //On retire les instruction "style"
                        $(".jb-cov-err-pan-ctr").removeAttr("style");
                    });
                    
                }, t);
            }
            //Sinon on affiche pas l'erreur. Des améliorations pourront être effectuées pour résorber le problème.
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_Cvr_HdleChg = function (files) {
//    this.Cover_HandleChange = function (files) {
        try {
            
            if ( KgbLib_CheckNullity(files) || files.lenght > 1 ) {
                return;
            }
            
            //Acquisition des images
            //cr = CanAbort
            var th = this, ca = false;
            $.each(files, function(k, v) {
                
                if (!window.FileReader) {
                    //            alert("Switch sur Adobe ? Normallement, cela est resolu au niveau du server-side");
                    var m = Kxlib_getDolphinsValue("ERR_BZR_OBSOLETE");
                    alert(m);
                    //On reset l'input pour garantir un focntionnement normal
                    _f_Rst_Form(true);
                    return;
                } 
                
                //On vérifie que le fichier est une image
                var gd = _f_VrfyComfy(v);
                if (gd !== true) {
                    _f_OnError(gd);
                    
                    //On reset l'input pour garantir un focntionnement normal
                    _f_Rst_Form();
                    
                    return;
                }
                
                /*
                 * [NOTE 18-09-14] @author L.C. 
                 * Cette vérification vient d'en ancien code. A la refactorisation on aurait du la retirer, mais je la laisse pour garantir le mode mode.
                 */
                if (gd) {
                    
                    var reader = new FileReader();
                    reader.onload = function() {
                        var img = new Image();
                        img.src = reader.result;
                        
                        img.onload = function() {
                            
                            //On vérifie que l'image remplie les conditions attendues
                            var gd = _f_VrfyComfy(v, img.width, img.height);
                            if ( gd !== true ) {
                                _f_OnError(gd);
                                //On reset l'input pour garantir un focntionnement normal
                                _f_Rst_Form();
                                return;
                            }
                            
                            /*
                             * Maintenant, il ne nous reste plus qu'à lancer la procédure d'affichage
                             */
                            
                            //On vérifie s'il n'y pas déjà une image présente
                            if ( $("#cov_img_buffer").length ) {
                                $("#cov_img_buffer").remove();
                            } 
                            
                            //Hide l'ancienne image
                            $(".jb-a-h-t-top-img").addClass("this_hide");
                            
                            var $img = _f_CreateCvr(this); 
                            _FnlImg = _f_Locale_SvFnl($img);
                            _FnlImg.name = v.name;
                            
                            //On bloque le declencheur
                            th.OnGoing = true;
                            
                            //On affiche les boutons de validataion
                            _f_FnlDcBtns(true, "_ALTERING");
//                            $(".jb-cvr-fnl-chs-mx").removeClass("this_hide"); //[DEPUIS 26-06-15] @BOR
                            
                            //Retire le fade
                            $(".jb-a-h-t-top-fade").hide();
                            
                            //On prepare le Draggable
                            $img.draggable({
                                axis: "y",
                                drag: function(e, ui) {
                                    var _height = $(e.target).height();
                                    var _limit_down = 260 - _height;
                                    var _top = ui.position.top;
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
                                    //*/
                                }
                            });
                            $img.addClass("cover_move");
                            $img.addClass("ui-widget-content");
                            
                            //On identifie l'image
                            $img.attr("id", "cov_img_buffer");
                            
                            //On ajoute l'image au Header
                            $(".jb-a-h-t-top-img-mx").prepend($img);
                            
                            /*
                             * [DEPUIS 28-06-15] @BOR
                             * On indique que l'action désigne l'acte de sauvegarder 
                             */
                            $(".jb-cvr-fnl-chs[data-action='save']").data("action","_NEW_CVR");
                            
                        };
                    };
                    
                    reader.onerror = function() {
                        //TODO: Send to server
                        //            Kxlib_DebugVars("An error occured when trying to read the image. It could be due to safety reasons or whatever !");
                        Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                        return;
                    };
                    
                    reader.readAsDataURL(v); 
                }
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_DelCvr = function (x) {
        try {
            
            if ( KgbLib_CheckNullity(x) | !$(x).length | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            
            /*
             * On vérifie s'il n'y a pas déjà une action en cours.
             * On vérifie si un changement de cover OU une suppression de cover est en cours.
             */
//            Kxlib_DebugVars(399, !KgbLib_CheckNullity(_xhr_dlcvr), !KgbLib_CheckNullity(_xhr_chcvr), _xhr_dlcvr === null, typeof _xhr_dlcvr);
            if ( !KgbLib_CheckNullity(_xhr_dlcvr) | !KgbLib_CheckNullity(_xhr_chcvr) ) { 
                return;
            }
            
            /*
             * On vérifie s'il y a bien une image en couverture
             */
            if (! $(".jb-a-h-t-top-img").length ) {
                return;
            }
            
            var a = $(x).data("action");
            switch (a) {
                case "start_del_cover" :
                        $(".jb-tmlnr-cov-dlcf-dc-dc-tgr").data("lk",0);
                        
                        $(".jb-tmlnr-cov-dlcf-gninf-mx").addClass("this_hide");
                        $(".jb-tmlnr-cov-dlcf-wait-mx").addClass("this_hide");
                        $(".jb-tmlnr-cov-dlcf-dc-mx").removeClass("this_hide");
                        $(".jb-tmlnr-cov-delconf-mx").removeClass("this_hide");
                    return;
                case "abort_del_cover" :
                        $(".jb-tmlnr-cov-delconf-mx").addClass("this_hide");
                        $(".jb-tmlnr-cov-dlcf-dc-mx").addClass("this_hide");
                    return;
                case "confirm_del_cover" :
                    break;
                default :
                    return;
            }
            
            _f_Delete(x);
                         
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*
    var _f_DelCvr = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | !$(".jb-chg-cvr") | !$(".jb-chg-cvr").length | $(x).data("lk") === 1 ) {
                return;
            }
            
            /*
             * On lock le bouton
             *
            $(x).data("lk",1);
            
            /*
             * ETAPE : 
             * On masque les boutons 
             *  -> Delete Cover
             *  -> Change Cover    
             *
            $(x).addClass("this_hide");
            $(".jb-chg-cvr").addClass("this_hide");
            
            /*
             * On indique que l'action désigne l'acte de retirer la couverture.
             *
            $(".jb-cvr-fnl-chs[data-action='save']").data("action","_DEL_CVR");
            
            /*
             * ETAPE : 
             * On affiche les boutons de décisions
             *
             _f_FnlDcBtns(true,"_DELETING");
                         
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    //*/
    
    var _f_CreateCvr = function (img) {
//    this.CreateCover = function (img) {
        /*
         * [NOTE 17-09-14] @author L.C.
         * Quand j'ai repris les travaux sur ce fichier, j'ai découvert cette valeur "759".
         * Je ne me souvienns pas pourquoi j'ai inscrit une valeur en dur quand je sais que la bannière peut avoir une taille différente.
         * J'ai donc décidé de passer outre en me basant sur la taille actuelle de la bannière.
         * 
         * Cette taille n'admet que deux valeurs. Soit la bannière est en mode "FULL" soit elle est en mode "THIN". Cela facilite grandement les choses.
         */
        try {
            
            var CR = new Cropper();
            var w = $(".jb-acc-hdr-top").width();
            var newImg = CR.Cropper_resizeWidthKeepHeightProrata(img, w);
            
            //Creation de l'element l'image
            return $(newImg);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_Locale_SvFnl = function (img) {
//    this.Locale_SaveFinal = function (img) {
        //FinalImage
        var Fi = {
            img : img,
            name : img.name,
            top : "" //Plus tard
        };
        
        return Fi;
    };
    
    var _f_Rst_Form = function (frc) {
//    this.Reset_Form = function (force) {
        /*
         * Permet de vider l'input. Cela permet notamment de pouvoir réessayer avec le même fichier, qui est un comportement normal.
         * Renvoie FALSE s'il ne peut pas vider l'input car il est en cours d'utilisation.
         */
        
        //On vérifie si une image n'est pas déjà en buffering. On le fait pour ne pas "casser" la procédure en cours.
        if ( !$("#cov_img_buffer").length | frc ) {
            Kxlib_ResetFormElt("cover-file-input");
            return true;
        }
        
        return false;
    };
    
    var _f_FnlConfDecAct = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            
            var a = $(x).data("action");
            switch (a.toUpperCase()) {
                case "_NEW_CVR" :
                        _f_Save(x);
                    break;
                    /*
                case "_DEL_CVR" :
                        _f_Delete(x);
                    break;
                    */
                default :
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    } ;
    
    var _f_Save = function (x) {
//    this.Save = function () {
//    this.Cover_Save = function () {
        try {
            
            if ( $(".jb-cvr-fnl-chs").first().data("lk") === 1 ) {
                return;
            }
            $(".jb-cvr-fnl-chs").data("lk",1);
            
            /*
             * On vérifie s'il n'y a pas déjà une action en cours.
             * On vérifie si un changement de cover OU une suppression de cover est en cours.
             */
//            Kxlib_DebugVars(399, !KgbLib_CheckNullity(_xhr_dlcvr), !KgbLib_CheckNullity(_xhr_chcvr), _xhr_dlcvr === null, typeof _xhr_dlcvr);
            if ( !KgbLib_CheckNullity(_xhr_dlcvr) | !KgbLib_CheckNullity(_xhr_chcvr) ) { 
                return;
            }
            
            /*
             * On fait apparaitre la zone d'attente
             * [DEPUIS 18-07-15] @BOR
             * On utilise la nouvelle fenetre
             */
            _f_CovPanel("_WAIT",true);
            /*
            var wm = Kxlib_getDolphinsValue("COMLG_Wait").toString().trim()+ "...";
            _f_ShwChgCvrWtPnl(wm);
            */
            
            /**
             * Le but est de récuppérer la photo originale ainsi que les paramètres caractéristiques à l'image.
             * Les données sont ensuite envoyées via AJAX au Serveur.
             * 
             * Les données sont : 
             *      - Les dimensions de l'image (
             *      - Le positionnement final de l'image => PosX
             *      - NOTE : Le degré de Zoom n'est pas techniquement une donnée car il ne s'agit que de la grosseur 
             *          * ... de l'image.
             */
            //On commence par installer la nouvelle image en retirant l'ancienne
            $(".jb-a-h-t-top-img").remove();
//        $("#cov_img_buffer").attr("id","a-h-t-top-img");
//        $(".jb-a-h-t-top-img").addClass("a-h-t-top-img");
            
            
            $("#cov_img_buffer").attr({
                "id": "a-h-t-top-img",
                "class": "a-h-t-top-img jb-a-h-t-top-img"
            });
            
            //On lui donne le bon top
            
            //On hide les boutons
            _f_FnlDcBtns();
//            $(".jb-cvr-fnl-chs-mx").addClass("this_hide"); //[DEPUIS 26-06-15] @BOR
            
            //On remet le fade
            $(".jb-a-h-t-top-fade").show();
            
            //On récuppère les deux images
//        var ori = OriginalImg;
            var final = _FnlImg.img;
            //On récuppère les données specifiques (h,w,top,type) 
            //NOTE : Le type permet de voir la concordance entre le travail au niveau du client et celui au niveau du server
//        Kxlib_DebugVars("Données sur l'original => Height "+$(ori).css("height"));
//        Kxlib_DebugVars("Données sur le final => Height "+$(final).css("height"));
//        Kxlib_DebugVars("Données sur le final => Top "+$(final).css("top"));
            
            //On enregistre l'image au niveau du serveur
            var ih = parseInt($(final).css("height").replace("px", ""));
            var iw = parseInt($(final).css("width").replace("px", ""));
            var it = $(final).css("top").replace("px", "");
            it = (it === "auto") ? 0 : parseInt(it);
            
            var ds = {
                img: $(final).attr("src"),
                in: _FnlImg.name,
                ih: ih,
                iw: iw,
                it: it 
            };
            
            /*
             $.each(datas, function(x,v){
             Kxlib_DebugVars([v],true);
             });
             //*/
            
            //On sauvegarde l'image de bannière auprès du serveur
            var s = $("<span/>");
            
            _f_Srv_SaveCvr(ds,s);
            
            //On reset l'input pour garantir un fonctionnement normal
            _f_Rst_Form();
            
            
            $(s).on("datasready", function(e, d) {
//            Kxlib_DebugVars([JSON.stringify(d)],true);
                if (KgbLib_CheckNullity(d)) {
                    return;
                }
                
                /* RAPPEL : Structure
                 {   "cov_datas": {
                 "acov_width":"",
                 "acov_height":"",
                 "acov_top":"",
                 "acov_rpath":""
                 },
                 "o_pbio":"0",
                 "o_cap":"0",
                 "o_pnb":0,
                 "tr_nb":0
                 }
                 //*/
//            Kxlib_DebugVars(442);
                //On vérifie si un autre changement n'est pas déjà en cours le temps qu'on est Aller et Revenu
                if ($("#cov_img_buffer").length) {
                    return;
                }
//            Kxlib_DebugVars(447);
                if (! $(".jb-a-h-t-top-img").length ) { 
                    return;
                }
//            Kxlib_DebugVars(451);
                //On crée l'image
                var $im = $("<img/>",{
                    "id": "a-h-t-top-img_new",
                    "src": d.cov_datas.acov_rpath,
                    "style": "top: " + d.cov_datas.acov_top + "px; left: 0; position: absolute; z-index: 3;",
                    "height": d.cov_datas.acov_height,
                    "width": d.cov_datas.acov_width
                            
                });
                /*
                var $im = $("<img/>");
                $($im).attr({
                    "id": "a-h-t-top-img_new",
                    "src": d.cov_datas.acov_rpath,
                    "style": "top: " + d.cov_datas.acov_top + "px; left: 0; position: absolute; z-index: 3;",
                    "height": d.cov_datas.acov_height,
                    "width": d.cov_datas.acov_width
                            
                });
                */
                $(".jb-a-h-t-top-img-mx").prepend($im);
                
                /*
                 * [DEPUIS 18-07-15] @BOR
                 */
                $(".jb-a-h-t-top-fade").removeClass("this_hide");
                
//            Kxlib_DebugVars(463);
                //Supprime l'ancienne image après quelques secondes sinon il y a un effet de cassure visible. C'est la meilleur solution à l'heure d'aujourd'hui
                setTimeout(function() {
//                    Kxlib_DebugVars([832,"Switch d'image"],true);
                    $(".jb-a-h-t-top-img").remove();
                    
                    $("#a-h-t-top-img_new").attr({
                        "id": "a-h-t-top-img",
//                        "style": "top: " + d.cov_datas.acov_top + "px;",
                        "class": "a-h-t-top-img jb-a-h-t-top-img"
                    });
                    
                    /*
                     * On supprime le "no-img"
                     * [DEPUIS 18-07-15] @BOR
                     * On masque pouu les opérations futures
                     */
                    $(".jb-a-h-t-t-noimg-i-mx").addClass("this_hide");
//                    $("#a-h-t-top-noimg").remove();
                }, 1000);
//            Kxlib_DebugVars(477);
                
                //*/
                /*
                 //On configure la nouvelle image
                 $("#a-h-t-top-img_new").attr({
                 "id":"a-h-t-top-img",
                 "style": "top: "+d.cov_datas.acov_top+"px;",
                 "class": "a-h-t-top-img"
                 });
                 //*/
                /*
                 //On retire l'ancienne image
                 $(".jb-a-h-t-top-img").fadeOut("500", function() {
                 //On ajoute la nouvelle
                 $(".jb-a-h-t-top-img-mx").addClass("this_hide").append($im).toggleClass("this_hide",250);
                 
                 $(this).remove();
                 });
                 //*/
                
                _f_HidChgCvrWtPnl();
                
                //On met à jour profilbio
                $(".jb-pfl-bio-bio").text(d.o_pbio);
//            Kxlib_DebugVars(500);
                //On update Capital
                $(".jb-u-sp-cap-nb").text(d.o_cap);
//            Kxlib_DebugVars(503);
                //On update le nombre de publications
                $(".jb-acc-spec-artnb").text(d.o_pnb);
//            Kxlib_DebugVars(506);
                //On update le nombre de Tendances
                $(".jb-acc-spec-trnb").text(d.tr_nb);
//            Kxlib_DebugVars(509);

                /*
                 * [DEPUIS 18-07-15] @BOR
                 * On retire le panneau d'attente
                 */
                _f_CovPanel();
            
                //On notifie l'utilisateur que l'opération s'est déroulée avec succès
                var Nty = new Notifyzing();
                Nty.FromUserAction("ua_acov_set");
                
                /*
                 * [DEPUIS 18-07-15] @BOR
                 */
                $(".jb-cvr-fnl-chs").data("lk",0);
                _xhr_chcvr = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_Delete = function (x) {
        try {
            /*
             * On débloque les boutons le cas échéant
             */
            if ( $(".jb-tmlnr-cov-dlcf-dc-dc-tgr").first().data("lk") === 1 | $(".jb-chg-cvr-del-bx").data("lk") === 1 ) {
                return;
            }
            $(".jb-tmlnr-cov-dlcf-dc-dc-tgr").data("lk",1);
            $(".jb-chg-cvr-del-bx").data("lk",1);
            
            /*
             * ETAPE :
             * On fait apparaitre la zone d'attente.
             */
            _f_CovPanel("_WAIT",true);
            
            /*
             * On transmet la demande au serveur
             */
            var s = $("<span/>");
                    
            _f_Srv_DelCvr(s);
            
            $(s).on("operended",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                /*
                 * ETAPE :
                 * On masque le message d'attente.
                 */
                _f_CovPanel();
                
                /*
                 * ETAPE :
                 * On retire l'image et on fait apparaitre "l'image" pour le cas de NoOne.
                 */
                $(".jb-a-h-t-top-img").remove();
                /*
                 * [DEPUIS 18-07-15] @BOR
                 * On masque plutot que supprimé pour pouvoir le réutilisé
                 */
                $(".jb-a-h-t-top-fade").addClass("this_hide");
//                $(".jb-a-h-t-top-fade").remove();
                $(".jb-a-h-t-t-noimg-i-mx").removeClass("this_hide");
                
                /*
                 * ETAPE :
                 * On notifie l'utilisateur que l'opération s'est déroulée avec succès.
                 */
                var Nty = new Notifyzing();
                Nty.FromUserAction("ua_pflbio_set");
                
                /*
                 * On retire les éléments qui indiquent l'état des 
                 */
                 $(".jb-c-c-start").removeClass("altering deleting");
                 
                 /*
                  * On unlock les boutons et le pointeur XHR
                  */
                 $(".jb-tmlnr-cov-dlcf-dc-dc-tgr").data("lk",0);
                 $(".jb-chg-cvr-del-bx").data("lk",0);
                 _xhr_dlcvr = null;
                 
                /*
                 * On met à jour les données extras
                 */
                //On met à jour profilbio
                $(".jb-pfl-bio-bio").text(d.o_pbio);
                //On update Capital
                $(".jb-u-sp-cap-nb").text(d.o_cap);
                //On update le nombre de publications
                $(".jb-acc-spec-artnb").text(d.o_pnb);
                //On update le nombre de Tendances
                $(".jb-acc-spec-trnb").text(d.tr_nb);
                //TODO : Mettre à jour le nombre de Followers
                //TODO : Mettre à jour le nombre de Following
                
                
            });
            
//            $(s).trigger("operended");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*
    var _f_Delete = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            /*
             * ETAPE :
             * On fait apparaitre la zone d'attente.
             *
            var wm = Kxlib_getDolphinsValue("COMLG_Wait").toString().trim()+ "...";
            _f_ShwChgCvrWtPnl(wm);
            
            /*
             * ETAPE :
             * On hide les boutons de décision.
             *
            _f_FnlDcBtns();
            
            /*
             * On transmet la demande au serveur
             *
            var s = $("<span/>");
                    
            _f_Srv_DelCvr(s);
            
            $(s).on("operended",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                /*
                 * ETAPE :
                 * On masque le message d'attente.
                 *
                _f_HidChgCvrWtPnl();
                
                /*
                 * ETAPE :
                 * On retire l'image et on fait apparaitre "l'image" pour le cas de NoOne.
                 *
                $(".jb-a-h-t-top-img").remove();
                $(".jb-a-h-t-top-fade").remove();
                $(".jb-a-h-t-t-noimg-i-mx").removeClass("this_hide");
                
                /*
                 * ETAPE :
                 * On notifie l'utilisateur que l'opération s'est déroulée avec succès.
                 *
                var Nty = new Notifyzing();
                Nty.FromUserAction("ua_pflbio_set");
                
                /*
                 * On retire les éléments qui indiquent l'état des 
                 *
                 $(".jb-c-c-start").removeClass("altering deleting");
                 
                /*
                 * On met à jour les données extras
                 *
                //On met à jour profilbio
                $(".jb-pfl-bio-bio").text(d.o_pbio);
                //On update Capital
                $(".jb-u-sp-cap-nb").text(d.o_cap);
                //On update le nombre de publications
                $(".jb-acc-spec-artnb").text(d.o_pnb);
                //On update le nombre de Tendances
                $(".jb-acc-spec-trnb").text(d.tr_nb);
                //TODO : Mettre à jour le nombre de Followers
                //TODO : Mettre à jour le nombre de Following
                
            });
            
//            $(s).trigger("operended");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    //*/
    
    var _f_Abort = function () {
//    this.Abort = function () {
//    this.Cover_Abort = function () {
        //Permet de repartir à l'image affichée avant le debut du processus de changement de l'image de bannière
       
       //(1) Retirer l'image en bufering
       $("#cov_img_buffer").remove();
       
       //(2) On remet la précédente image
       $(".jb-a-h-t-top-img").removeClass("this_hide");
       
       //(3) Masquer la zone de choix
        _f_FnlDcBtns();
//        $(".jb-cvr-fnl-chs-mx").addClass("this_hide"); //[DEPUIS 26-06-15] @BOR
       
       //(4) On remet le fade. Cela permet notamment de ne plus pouvoir mouvoir l'image en dessous. (La question c'est pk ne pas juste la rendre non draggable :o ? => Ce n'est pas dérangeant ! )
       $(".jb-a-h-t-top-fade").show();
       
       //(5) On reset l'input pour garantir un fonctionnement normal
       Kxlib_ResetFormElt("cover-file-input");
       
    };
    /*
    this.Cover_Wipe = function () {
        //TODO : Permet de revenir à l'état initial de la bannière. Il peut s'agir d'une simple DIV avec de la couleur de fond ou d'une image dpar défaut
    };
    //*/
    /****************************************************************************************************************************************************/
    /**************************************************************** AJAX - SERVER SCOPE ***************************************************************/
    /****************************************************************************************************************************************************/
    
    var _f_Srv_SaveCvr = function (ds,s) {
//    this._Srv_SaveCover = function (ds,s) {
        if ( KgbLib_CheckNullity(ds) | KgbLib_CheckNullity(s) | !Kxlib_GetCurUserPropIfExist() ) {
            return;
        }
        
        var _Ax_SvCvr = Kxlib_GetAjaxRules("TMLNR_SET_COVER", Kxlib_GetCurUserPropIfExist().upsd);
        
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
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_IMG_NOT_COMPLY":
                                    return;
                            case "__ERR_VOL_FAILED":
                                    //TODO : Retirer l'image en Buffer
                                    //Afficher le message adéquat dans la zone d'erreur
                                    var Nty = new Notifyzing ();
                                    Nty.FromUserAction("ERR_COM_AJAX_GDT_FAIL",null,true);
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    Kxlib_AJAX_HandleDeny();
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
                     *  (1) Les données sur les l'image de couverture
                     *  (2) Des données extras :
                     *      -> profil_bio
                     *      -> capital
                     *      -> posts_nb
                     *      -> trend_nb
                     */
//                    Kxlib_DebugVars([JSON.stringify(d.return)],true);
                    var rds = [d.return];
                    $(s).trigger("datasready",rds);
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
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
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        //CURL : Permet de finri de s'assurer que l'utilisateur est bien sur son compte.
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_SvCvr.urqid,
            "datas": {
                "img": encodeURIComponent(ds.img), 
                "in": ds.in,
                "ih": ds.ih, 
                "iw": ds.iw, 
                "it": ds.it,
                "cl": curl
            }
        };
//        Kxlib_DebugVars([toSend.datas.in,toSend.datas.ih,toSend.datas.iw,toSend.datas.it,toSend.datas.cl],true);
        
//        Kx_XHR_Send(toSend, "post", _Ax_SvCvr.url, onerror, onsuccess);
        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_SvCvr.url, wcrdtl : _Ax_SvCvr.wcrdtl });
    };
    
    var _Ax_DelCvr = Kxlib_GetAjaxRules("TMLNR_RST_COVER", Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_DelCvr = function (s) {
//    this._Srv_SaveCover = function (ds,s) {
        if ( KgbLib_CheckNullity(s) | !Kxlib_GetCurUserPropIfExist() ) {
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
                                    break;
                            case "__ERR_VOL_FAILED":
                                    //TODO : Retirer l'image en Buffer
                                    //Afficher le message adéquat dans la zone d'erreur
                                    var Nty = new Notifyzing ();
                                    Nty.FromUserAction("ERR_COM_AJAX_GDT_FAIL",null,true);
                                break;
                            case "__ERR_VOL_MSM_RULES" :
                                    Kxlib_AJAX_HandleFailed("ERR_COV_SRV_MSM_RLS");
                                break;
                            case "__ERR_VOL_NOTGT" :
                                    /*
                                     * [DEPUIS 18-07-15] @BOR
                                     * On retire la zone d'attente et on affiche le message
                                     */
                                    m = Kxlib_getDolphinsValue("ERR_COV_ONDEL_NOTGT");
                                    _f_CovPanel("_GEN_INFO",true,{info:m});
                                    
//                                    Kxlib_DebugVars([KgbLib_CheckNullity(_xhr_dlcvr),_xhr_dlcvr === null,typeof _xhr_dlcvr],true);
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    Kxlib_AJAX_HandleDeny();
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) ) {
                    /*
                     * Données attendues : [NONE]
                     * Des données extras :
                     *      -> profil_bio
                     *      -> capital
                     *      -> posts_nb
                     *      -> trend_nb
                     */
                    var rds = [d.return];
                    $(s).trigger("operended",rds);
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
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
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        //CURL : Permet de finri de s'assurer que l'utilisateur est bien sur son compte.
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_DelCvr.urqid,
            "datas": {
                "cl": curl
            }
        };
        
//        Kxlib_DebugVars([toSend.datas.in,toSend.datas.ih,toSend.datas.iw,toSend.datas.it,toSend.datas.cl],true);
        _xhr_dlcvr = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_DelCvr.url, wcrdtl : _Ax_DelCvr.wcrdtl });
    };
    
    /********************************************************************************************************************************************/
    /**************************************************************** VIEW SCOPE ****************************************************************/
    /********************************************************************************************************************************************/
    var _f_ShwChgCvrWtPnl = function (m) {
        if ( KgbLib_CheckNullity(m) ) {
            return;
        }
        
        $(".jb-a-h-t-top-wait").text(m);
        $(".jb-a-h-t-top-wait").stop(true,true).toggleClass("this_hide",300,"easeOutExpo");
        
    };
    
    var _f_HidChgCvrWtPnl = function () {
        $(".jb-a-h-t-top-wait").stop(true,true).toggleClass("this_hide",true,300,"easeOutExpo",function(){
            $(".jb-a-h-t-top-wait").text("");
        });
    };
    
    var _f_FnlDcBtns = function (shw,scp) {
        try {
            if ( typeof shw !== "undefined" && typeof scp === "undefined" ) {
                return;
            }
            
            if ( shw ) {
                var cls = ( scp === "_DELETING" ) ? "deleting" : "altering";
                
                $(".jb-c-c-start").removeClass("altering deleting");
                $(".jb-c-c-start").addClass(cls);
                $(".jb-cvr-fnl-chs-mx").removeClass("this_hide");
            } else {
                $(".jb-c-c-start").removeClass("altering deleting");
                $(".jb-chg-cvr-del-bx").data("lk", 0);
                $(".jb-cvr-fnl-chs-mx").addClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_CvrHdBx = function (shw) {
        
        if ( shw ) {
            $(".jb-chg-cvr-del-bx").removeClass("this_hide");
        } else {
            $(".jb-chg-cvr-del-bx").addClass("this_hide");
        }
    };
    
    var _f_ShwAtHmMns = function (sh) {
        try {
            if ( sh ) {
                $(".jb-tmlnr-hdr-hmbx").stop(true,true).addClass("activate",250);
                $(".jb-tmlnr-athm-mn-bmx").stop(true,true).hide().fadeIn(240).removeClass("this_hide");
            } else {
                $(".jb-tmlnr-hdr-hmbx").stop(true,true).removeClass("activate",250);
                $(".jb-tmlnr-athm-mn-bmx").stop(true,true).fadeOut(270,function(){
                    $(this).addClass("this_hide");
                });
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_CovPanel = function (pan,sh,opts) {
        try {
            
            switch ( pan ) {
                case "_GEN_INFO" :
                        if ( sh === true ) {
                            if (! ( !KgbLib_CheckNullity(opts) && opts.hasOwnProperty("info") && !KgbLib_CheckNullity(opts.info) && typeof opts.info === "string" ) ) {
                                return;
                            }

                            $(".jb-tmlnr-cov-dlcf-gninf").text(opts.info);

                            $(".jb-tmlnr-cov-dlcf-wait-mx").addClass("this_hide");
                            $(".jb-tmlnr-cov-dlcf-dc-mx").addClass("this_hide");
                            $(".jb-tmlnr-cov-dlcf-gninf-mx").removeClass("this_hide");
                        }
                    break;
                case "_WAIT" :
                        if ( sh === true ) {
                            $(".jb-tmlnr-cov-dlcf-gninf").text("");
                            $(".jb-tmlnr-cov-dlcf-dc-mx").addClass("this_hide");
                            $(".jb-tmlnr-cov-dlcf-gninf-mx").addClass("this_hide");
                            $(".jb-tmlnr-cov-dlcf-wait-mx").removeClass("this_hide");
                        }
                    break;
                case "_DIALOG" :
                        if ( sh === true ) {
                            $(".jb-tmlnr-cov-dlcf-gninf").text("");
                            $(".jb-tmlnr-cov-dlcf-wait-mx").addClass("this_hide");
                            $(".jb-tmlnr-cov-dlcf-dc-mx").addClass("this_hide");
                            $(".jb-tmlnr-cov-dlcf-gninf-mx").removeClass("this_hide");
                        }
                    break;
                default:
                        $(".jb-tmlnr-cov-delconf-mx").addClass("this_hide");
                        $(".jb-tmlnr-cov-dlcf-wait-mx").addClass("this_hide");
                        $(".jb-tmlnr-cov-dlcf-dc-mx").addClass("this_hide");
                        $(".jb-tmlnr-cov-dlcf-gninf").text("");
                        $(".jb-tmlnr-cov-dlcf-gninf-mx").addClass("this_hide");
                    return;
            }
            
            if ( sh === true ) {
                $(".jb-tmlnr-cov-delconf-mx").removeClass("this_hide");
            } else {
                $(".jb-tmlnr-cov-dlcf-gninf-mx").addClass("this_hide");
                $(".jb-tmlnr-cov-dlcf-gninf").text("");
                $(".jb-tmlnr-cov-dlcf-wait-mx").addClass("this_hide");
                $(".jb-tmlnr-cov-dlcf-dc-mx").addClass("this_hide");
                $(".jb-tmlnr-cov-delconf-mx").addClass("this_hide");
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*************************************************************************************************************************************************/
    /**************************************************************** LISTENERS SCOPE ****************************************************************/
    /*************************************************************************************************************************************************/
    
    /*
     * [NOTE 13-05-15] @BOR
     *      .is(":hover") ne fonctionnant pas partout, j'utilise la voix standard.
     * [NOTE 26-06-15] @BOR
     *      Prise en compte du logo "lock"
     */
    $(".jb-a-h-t-top").hover(function(){
        /*
         * [DEPUIS 04-07-15] @BOR
         * Ajout du setTimeout pour ne pas agacer l'utilisateur
         */
        setTimeout(function(){
            
//            Kxlib_DebugVars(( !$(".jb-a-h-t-t-noimg-i-mx").length || $(".jb-a-h-t-t-noimg-i-mx").hasClass("this_hide") ),$(".jb-c-c-start").hasClass("altering"),$(".jb-c-c-start").hasClass("deleting"),$(".jb-a-h-t-top-wait").hasClass("this_hide"),$(".jb-a-h-t-top:hover").length);
            if ( 
                ( !$(".jb-a-h-t-t-noimg-i-mx").length || $(".jb-a-h-t-t-noimg-i-mx").hasClass("this_hide") ) 
                && !$(".jb-c-c-start").hasClass("altering") && !$(".jb-c-c-start").hasClass("deleting") && $(".jb-a-h-t-top-wait").hasClass("this_hide") 
                && $(".jb-a-h-t-top:hover").length
                && ( KgbLib_CheckNullity(_xhr_chcvr) && KgbLib_CheckNullity(_xhr_dlcvr) )
            ) 
            {
                $(".jb-chg-cvr-del-bx").stop(true,true).hide().removeClass("this_hide").fadeIn();
                $(".jb-chg-cvr").stop(true,true).hide().removeClass("this_hide").fadeIn();
            } 
            else if ( 
                !( !$(".jb-a-h-t-t-noimg-i-mx").length || $(".jb-a-h-t-t-noimg-i-mx").hasClass("this_hide") )
                && !$(".jb-c-c-start").hasClass("altering") && !$(".jb-c-c-start").hasClass("deleting") && $(".jb-a-h-t-top-wait").hasClass("this_hide") 
                && $(".jb-a-h-t-top:hover").length
                && ( KgbLib_CheckNullity(_xhr_chcvr) && KgbLib_CheckNullity(_xhr_dlcvr) )
            )
            {
                $(".jb-chg-cvr").stop(true,true).hide().removeClass("this_hide").fadeIn();
            } 
            else if ( $(".jb-c-c-start").hasClass("altering") && $(".jb-a-h-t-top:hover").length ) 
            {
                $(".jb-chg-cvr").stop(true,true).hide().removeClass("this_hide").fadeIn();
            }
            else 
            {
                $(".jb-chg-cvr-del-bx").stop(true,true).fadeOut(500,function(){
                    $(this).addClass("this_hide");
                });
                $(".jb-chg-cvr").stop(true,true).fadeOut(500,function(){
                    $(this).addClass("this_hide");
                });
            }
        },350);
        
        /*
        if ( $(this).is(':hover') ) {
            $(".jb-chg-cvr").removeClass("this_hide");
        } else {
            $(".jb-chg-cvr").addClass("this_hide");
        }
        //*/
    }, function(){
        $(".jb-chg-cvr").stop(true,true).fadeOut(500,function(){
            $(this).addClass("this_hide");
        });
        
        $(".jb-chg-cvr-del-bx").stop(true,true).fadeOut(500,function(){
            $(this).addClass("this_hide");
        });
    });
    
    $(".jb-chg-cvr").click(function(e) {
//        alert("Parent");
//        Kxlib_PreventDefault(e);
//        $(".jb-cvr-file-ipt").focus();
//        $(".jb-cvr-file-ipt").click();
    });
    
    $(".jb-cvr-file-ipt").click(function(e) {
        Kxlib_StopPropagation(e);
//        Kxlib_PreventDefault(e);
        
//        alert("Child");
//        $(".jb-cvr-file-ipt").change();
    });
    
    $(".jb-cvr-file-ipt").change(function(e) {
        Kxlib_StopPropagation(e);
        var files = this.files;
        
        _f_Cvr_HdleChg(files);
    });
    
    $(".jb-chg-cvr-del-bx, .jb-tmlnr-cov-dlcf-dc-dc-tgr").click(function(e) {
        Kxlib_PreventDefault(e);
        
        _f_DelCvr(this);
    });
    
    $(".jb-cvr-fnl-chs[data-action='save']").click(function(e) {
        Kxlib_PreventDefault(e);

        _f_FnlConfDecAct(this);
//        _f_Save();
    });
 
    $(".jb-cvr-fnl-chs[data-action='cancel']").click(function(e) {
       Kxlib_PreventDefault(e);
       
       _f_Abort();
    });
    
    /****************** MENUS ****************/
    
    $(".menu-noselected").hover(function(e){
        _f_Action(this);
    }, function(e){
        _f_Action(this);
    });
    
    /****************** ATHOME ****************/
    
    $(".jb-tmlnr-hdr-hmbx, .jb-tmlnr-hdr-hmbx > *").hover(function(e) {
        if ( ( !$(".jb-tqr-fdry-invit-bmx").length || $(".jb-tqr-fdry-invit-bmx").hasClass("this_hide") ) && $(".jb-tqr-fry-bx-bmx").not(".this_hide").length === 0 ) 
        {
            $(".jb-tmlnr-hdr-hmbx").removeClass("disable");
        }
    }, function(){
    }); 
    
    $(".jb-tmlnr-hdr-hmbx").off().click(function(e) {
       Kxlib_PreventDefault(e);
       Kxlib_StopPropagation(e);
       
       _f_AtHmMns(this);
    });
    
    $(".jb-tmlnr-hdr-hmbx").focusout(function(e) {
        Kxlib_StopPropagation(e);
        
       _f_HdlOpnAtHmMns(e,null,false);
    });
    
}

new ACCHDR(); 


function ACHR_Receiver () {
    var o = new ACCHDR();
    this.Routeur = function (th){
        if ( KgbLib_CheckNullity(th) ) { return; }
        o.CheckOperation(th);
    };
    
}
