/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function TrendHeader () {
    var gt = this;
    
    var _xhr_dlcvr;
    var _xhr_chcvr;
    
   /**
    *  NOTE DEV [13-05-14] :
        Cette classe a été créé après les fonctions de gestions se trouvant à la fin de ce fichier.
        Par conséquent, seules les fonctionnalités les plus récentes sont traitées ici.
        Il s'agit notamment de la gestion du changement de Cover
   */
//    this.selElt;
//    this.uaction;
    
//    this.allowedFormat = ["png","jpeg","gif"];
//    this.allowedImgSize = 3000000;
    
    //11-10-14
    var _FnlImg;
//    this.FinalImg;
    
    //Stocke les données dites 'settings' de la Tendance passée en paramètre
    //STAY PUBLIC;
    var _TrSrvStgs;
    this.TrSrvSetgs = function () {
        return _TrSrvStgs;
    };
    
    /********************** AJAX **********************/
    
    /*********************************************************************************************************************************************************/
    /********************************************************************* PROCESS SCOPE *********************************************************************/
    /*********************************************************************************************************************************************************/
    
    var _f_Gdf = function () {
//    this.GetImageRules = function () {
        /*
         * [NOTE 18-09-14] @author L.C.
         * Les dimensions max et min ont été choisies arbritairement.
         * Pour les dimensions mins je considère que c'est une limite raisonnable.
         * Pour la limite max, je me suis basée sur des images lourdes HD que j'avais sur la main.
         * Les plus grandes avaient un peu plus de 5000px.
         */
        var ir = {
            "ft"        : ["png","jpg","jpeg"],
            "sz"        : 1048576 * 2.5,
            /*
             * [NOTE]
             *      [NOTE 25-06-16]
             *          -> Passage de 250 > 260
             */
            "w_min"     : 260,
            "w_max"     : 5500,
            /*
             * [NOTE]
             *      [NOTE 25-06-16]
             *          -> Passage de 250 > 260
             */
            "h_min"     : 260,
            "h_max"     : 5500
        };
        
        return ir;
    };
    
/*
    //Récupère les données de bases necessaires au traitement des processus.
    var _f_Basics = function (x) {
//    this.AcquireBasics = function (x) {
        if ( KgbLib_CheckNullity(x) ) { return; }
        
        var a = $(x).data("action");
        return ( KgbLib_CheckNullity(a) ) ? null : a;
    };
    //*/
    /*********************************************************************************/
    //Permet de changer les données dites "Settings" de la Tendance
    //STAY PUBLIC
    this.UpdHdrWNwStgs = function (d) {
        try {
            
//        Kxlib_DebugVars([JSON.stringify(d)],true);
            
            //Titre
            var t = Kxlib_Decode_After_Encode(d.t);
            $(".jb-a-h-t-top-tr-tle").text(t);
            
            /*
             * [NOTE 27-04-15] @BOR
             * ETAPE :
             * On ajoute la description
             */
            var ds = Kxlib_Decode_After_Encode(d.d);
            var t__ = $("<div/>").text(ds).text();
//            var t__ = $("<div/>").html(ds).text(); //[DEPUIS 13-07-15] @BOR
//        alert("From server xxx");
            $(".jb-a-h-t-top-tr-desc").text(t__);
            
            //Catégorie
//        var c = Kxlib_Decode_After_Encode(d.c);
//        $(".jb-ttr-cache").data("c",c);
            
            //Participation
            if ($("#trpg_cov_part").length && $("#trpg_cov_part").parent().find(".jb-trpg-cov-part-lg").length === 1 && d.hasOwnProperty("p") && !KgbLib_CheckNullity(d.p[0]) && !KgbLib_CheckNullity(d.p[1]) && $.inArray(d.p[0], ["pub", "pri"]) !== -1) {
                switch (d.p[0].toLowerCase()) {
                    case "pub" :
                        if ($(".jb-trpg-cov-part-lg.lock").length) {
                            var x__ = $("<i/>", {
                                class: "fa fa-unlock-alt jb-trpg-cov-part-lg unlock"
                            });
                            $(".jb-trpg-cov-part-lg.lock").replaceWith(x__);
                        } else {
                            return;
                        }
                        break;
                    case "pri" :
                        if ($(".jb-trpg-cov-part-lg.unlock").length) {
                            var x__ = $("<i/>", {
                                class: "fa fa-lock jb-trpg-cov-part-lg lock"
                            });
                            $(".jb-trpg-cov-part-lg.unlock").replaceWith(x__);
                        } else {
                            return;
                        }
                        break;
                    default :
                        return;
                }
                $("#trpg_cov_part").text(d.p[1]);
                $(".jb-ttr-cache").data("p", d.p.join(','));
            }
            
            
            //TODO : Gratification
            /*
             if (! parseInt(d.g) )
             $("#tr_home_gratif").addClass("this_invi");
             else {
             $("#p-i--t-h-nb").html(d.g);
             $("#tr_home_gratif").removeClass("this_invi");
             }
             //*/
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
            
    };
    /********************************************************************************/
    
    var _f_SvCvr = function (x) {
//    this.SaveCover = function () {
        
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
        try {
            /*
             * [DEPUIS 17-07-15] @BOR
             */
            if ( $(".jb-chcvr-ch-tgr[data-action='confirm_save_cover']").data("lk") === 1 ) {
                return;
            }
            if ( !KgbLib_CheckNullity(_xhr_chcvr) | !KgbLib_CheckNullity(_xhr_dlcvr) ) {
                return;
            }
            /*
             * [DEPUIS 17-07-15] @BOR
             */
            $(".jb-chcvr-ch-tgr").data("lk",1);
            
            /*
             * [DEPUIS 16-07-15] @BOR
             * On affiche le panneau d'attente
             */
            $(".jb-trpg-cov-dlcf-gninf").html("");
            $(".jb-trpg-cov-dlcf-gninf-mx").addClass("this_hide");
            $(".jb-trpg-cov-dlcf-dc-mx").addClass("this_hide");
            $(".jb-trpg-cov-dlcf-wait-mx").removeClass("this_hide");
            $(".jb-trpg-cov-delconf-mx").removeClass("this_hide");
            
            //On commence par installer la nouvelle image en retirant l'ancienne
            $("#a-h-t-top-tr-img-img").remove();
            $("#cov_img_buffer").attr("id", "a-h-t-top-tr-img-img");
            
            //On lui donne le bon top
            
            //Cache le menu choices
            _f_TogMnChcs();
            
            //Afficher les textes sur le Cover
//        _f_TogDescTleVisi(true);
            
            //On hide le bouton
//            _f_TogMnChcs(); //?
            
            //On retire le dragable
            $("#a-h-t-top-tr-img-img").draggable("destroy");
            $("#a-h-t-top-tr-img-img").removeClass("cover_move");
//        $("#a-h-t-top-tr-img-img").removeClass("ui-widget-content");
            
            //On masque le fade
            $("#a-h-t-top-fade").hide();
            
            //On récuppère les deux images
//        var ori = OriginalImg;
            var final = $("#a-h-t-top-tr-img-img");
            /*
             * [DEPUIS 17-07-15] @BOR
             * Règle un bogue qui faisait qu'après une suppression je n'arrivais plus à accéder aux données de dimentsions de l'image.
             * La solution ci-dessus permet de permettre une succession de changement dans tous les cas.
             */
//            var final = _FnlImg.img;
            //On récuppère les données specifiques (h,w,top,type) 
            //NOTE : Le type permet de voir la concordance entre le travail au niveau du client et celui au niveau du server
            //Kxlib_DebugVars([Données sur l'original => Height "+$(ori).css("height")]);
//        Kxlib_DebugVars([Données sur le final => Height "+$(final).css("height")]);
            
            //    alert($(final).attr("src"));
            //*
            //On enregistre l'image au niveau du serveur
            var ih = parseInt($(final).css("height").replace("px",""));
            var iw = parseInt($(final).css("width").replace("px",""));
            var it = $(final).position().top;
//            var it = $(final).css("top").replace("px", "");
            it = (it === "auto") ? 0 : parseInt(it);
            
            var ds = {
                img: $(final).attr("src"),
                in: _FnlImg.name,
                ih: ih,
                iw: iw,
                it: it 
            };
            
//        Kxlib_DebugVars(["233",$(final).attr("id"),ds.in,ds.ih,ds.iw,ds.it],true);
//        return;
            /*
             var datas = {
             img: $(final).attr("src"),
             img_cov_h:  $(final).css("height"),
             img_cov_w:  $(final).css("width"),
             //Top permet de resituer l'image dans le cadre au cas où
             img_cov_top: $(final).css("top")
             };
             //*/
            var pp = Kxlib_GetTrendPropIfExist();
            
            if ( KgbLib_CheckNullity(pp) ) {
                //TODO: Averrtir l'utilisateur qu'il y a un problème technique avec la page.
                //TODO : Prévenir le serveur. Cette erreur a une probabilité faible d'arriver. Cela peut être causé par une modificationde la page par CU
                return;
            }
            
            var ti = pp.trid;
//        Kxlib_DebugVars(["182",ti,ds.in,ds.ih,ds.iw,ds.it],true);
//        return;
            var s = $("<span/>");
            _f_Srv_SvCvr(ti,ds,s);
            
            //On reset l'input pour garantir un fonctionnement normal
            _f_Rst_Form();
            $(s).on("datasready", function(e,d) {
                /*
                 * [NOTE 12-10-14] @author L.C.
                 * Ce code provient d'un retour d'expérience sur le changement de l'image de couverture au niveau de TMLNR.
                 * Le fichier référence est donc : "acc_header.d.js".
                 * Cependant, des modifications ont peut être été effectuées pour des riasons fonctionnelles ou du refactoring.
                 */
                
                if (KgbLib_CheckNullity(d)) {
                    return;
                }
                
                /* RAPPEL : Structure
                 {   "trcov": {
                 "cov_w":"",
                 "cov_h":"",
                 "cov_t":"",
                 "cov_rp":""
                 },
                 }
                 //*/
                
                //On vérifie si un autre changement n'est pas déjà en cours le temps qu'on est Aller et Revenu
                if ( $("#cov_img_buffer").length ) {
                    return;
                }
                if (! $("#a-h-t-top-tr-img-img").length ) {
                    return;
                }
                
                //On crée l'image
                var $im = $("<img/>");
                $($im).attr({
                    "id": "a-h-t-top-img_new",
                    "src": d.trcov.cov_rp,
                    "style": "top: " + d.trcov.cov_t + "px; left: 0; position: absolute; z-index: 3;",
                    "height": d.trcov.cov_h,
                    "width": d.trcov.cov_w
                            
                });
                
                $("#a-h-t-top-tr-img-max").prepend($im);
                
                /*
                 * [DEPUIS 17-07-17] @BOR
                 * On affiche de nouveau fade
                 */
                $(".tr-header-fade").show();
                
                //Supprime l'ancienne image après quelques secondes sinon il y a un effet de cassure visible. C'est la meilleur solution à l'heure d'aujourd'hui
                setTimeout(function() {
                    $("#a-h-t-top-tr-img-img").remove();
                    
                    $("#a-h-t-top-img_new").attr({
                        "id": "a-h-t-top-tr-img-img",
                        "style": "top: " + d.trcov.cov_t + "px;",
                        "class": "a-h-t-top-img jb-a-h-t-top-img"
                    });
                    
                    //On supprime le "no-img"
                    $(".jb-trpg-cov-none").remove();
//                    $("#trcov-noimg").remove();
                    
                }, 2000);
                
                //TODO : On met à jour les autres donnnées
                
                //On notifie l'utilisateur que l'opération s'est déroulée avec succès
                var Nty = new Notifyzing();
                Nty.FromUserAction("ua_acov_set");
                
                /*
                 * [DEPUIS 16-07-15] @BOR
                 * On retire le panneau d'attente
                 */
                $(".jb-trpg-cov-dlcf-gninf-mx").addClass("this_hide");
                $(".jb-trpg-cov-dlcf-wait-mx").addClass("this_hide");
                $(".jb-trpg-cov-dlcf-dc-mx").addClass("this_hide");
                $(".jb-trpg-cov-delconf-mx").addClass("this_hide");
                
                /*
                 * [DEPUIS 17-07-15] @BOR
                 * On affiche le bouton de suppression
                 */
                $(".afl_choice.bind-deltrcov").removeClass("this_hide");
                
                /*
                 * [DEPUIS 16-07-15] @BOR
                 */
                _xhr_chcvr = null;
                /*
                 * [DEPUIS 17-07-15] @BOR
                 */
                $(".jb-chcvr-ch-tgr").data("lk",0);
            });
            
            $(s).on("operended", function() {
                //Si au aucune donnée ni erreur n'est renvoyée
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
            
    var _f_Rst_Form = function () {
//    this.Reset_Form = function () {
        /*
         * Permet de vider l'input. Cela permet notamment de pouvoir réessayer avec le même fichier, qui est un comportement normal.
         * Renvoie FALSE s'il ne peut pas vider l'input car il est en cours d'utilisation.
         */
        
        //On vérifie si une image n'est pas déjà en buffering. On le fait pour ne pas "casser" la procédure en cours.
//        if ( !$("#cov_img_buffer").length | force ) {
            Kxlib_ResetFormElt("tr-chcov-trg");
            return true;
//        }
        
//        return false;
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
//            Kxlib_DebugVars([99, !KgbLib_CheckNullity(_xhr_dlcvr), !KgbLib_CheckNullity(_xhr_chcvr), _xhr_dlcvr === null, typeof _xhr_dlcvr]);
            if ( !KgbLib_CheckNullity(_xhr_dlcvr) | !KgbLib_CheckNullity(_xhr_chcvr) ) {
                return;
            }
            
            /*
             * On vérifie s'il y a bien une image en couverture
             */
            if (! $(".a-h-t-top-img").length ) {
                return;
            }
            
            var a = $(x).data("action");
            switch (a) {
                case "start_del_cover" :
                        /*
                         * [DEPUIS 12-09-15] @author BOR
                         *  Dans tous les cas, on developpe le header.
                         */
                        $("#tr-h-an-hi").click();
                        
                        $(".jb-trpg-cov-dlcf-dc-dc-tgr").data("lk",0);
                        
                        $(".jb-trpg-cov-dlcf-gninf-mx").addClass("this_hide");
                        $(".jb-trpg-cov-dlcf-wait-mx").addClass("this_hide");
                        $(".jb-trpg-cov-dlcf-dc-mx").removeClass("this_hide");
                        $(".jb-trpg-cov-delconf-mx").removeClass("this_hide");
                    return;
                case "abort_del_cover" :
                        $(".jb-trpg-cov-delconf-mx").addClass("this_hide");
                        $(".jb-trpg-cov-dlcf-dc-mx").addClass("this_hide");
                    return;
                case "confirm_del_cover" :
                    break;
                default :
                    return;
            }
            
            /*
             * On débloque les boutons le cas échéant
             */
            if ( $(".jb-trpg-cov-dlcf-dc-dc-tgr").first().data("lk") === 1 ) {
                return;
            }
            $(".jb-trpg-cov-dlcf-dc-dc-tgr").data("lk",1);
            
            /*
             * On affiche le panneau d'attente
             */
            $(".jb-trpg-cov-dlcf-gninf-mx").addClass("this_hide");
            $(".jb-trpg-cov-dlcf-dc-mx").addClass("this_hide");
            $(".jb-trpg-cov-dlcf-wait-mx").removeClass("this_hide");
            
            /*
             * On récupère l'identifiant de la Tendance
             */
            var pp = Kxlib_GetTrendPropIfExist();
            if ( KgbLib_CheckNullity(pp) ) {
                return;
            }
            var ti = pp.trid;
            
            var s = $("<span/>");
            _f_Srv_DelCvr(ti,s);
            
            $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                /*
                 * TODO : Mettre à jour les données de la Tendance
                 */
//                Kxlib_DebugVars([JSON.stringify(d)],true);

                /*
                 * On retire l'image actuellement affichée
                 */
                $(".a-h-t-top-img").remove();
                
                /*
                 * On affiche l'image par défaut.
                 */
                var $img = $("<img/>",{
                    src     : Kxlib_GetExtFileURL("sys_url_img","r/3pt-w.png")
//                    src : "http://timg.ycgkit.com/files/img/r/3pt-w.png"
                });
                var $nne = $("<span/>",{
                    id      : "trpg-cov-none",
                    class   : "jb-trpg-cov-none"
                }).append($img);
                
                $($nne).insertAfter(".jb-trpg-cov-delconf-mx");  
                
                /*
                 * On retire le panneau d'attente
                 */
                $(".jb-trpg-cov-dlcf-gninf-mx").addClass("this_hide");
                $(".jb-trpg-cov-dlcf-wait-mx").addClass("this_hide");
                $(".jb-trpg-cov-dlcf-dc-mx").addClass("this_hide");
                $(".jb-trpg-cov-delconf-mx").addClass("this_hide");
                
                //On notifie l'utilisateur que l'opération s'est déroulée avec succès
                var Nty = new Notifyzing();
                Nty.FromUserAction("ua_acov_set");
                
                /*
                 * [DEPUIS 17-07-15] @BOR
                 * On masque le bouton de suppression
                 */
                $(".afl_choice.bind-deltrcov").addClass("this_hide");
                
                $(".jb-trpg-cov-dlcf-dc-dc-tgr").data("lk",0);
                _xhr_dlcvr = null;
                
                //On reset l'input pour garantir un fonctionnement normal
                _f_Rst_Form();
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    //*
    
    /***************** DEBUT TRAITEMENT SUR L'IMAGE DU COVER ****************/
    /*
    //NOTE au [13/05/14] : Récupéré de NewPost...js
    this.handleSafetyOnImage = function(arg){
        //Verifie : format, taille et forme
        if (!arg.type.match('image/*')) {
            return {errType: "bad_file"};
        } else {
            var filef = arg.type.split('/').pop();
//            alert("Format :"+filef);
            //*
            if ( $.inArray( filef, this.allowedFormat) === -1 ) {
                //Est ce que le type de l'image est autorisé
                return {errType: "bad_type"};
            } else if ( arg.size > this.allowedImgSize ) {
                //Est ce que le type de l'image est autorisé
                return {errType: "too_loud"};
            } else {
                //Est ce que le type de l'image est autorisé
                return {errType: "awaiting_for_dims"};
            }
            //
        }
        
    };
    //*/
    var _f_CrtCvr = function (img) {
//    this.CreateCover = function (img) {
        var CR = new Cropper();
        var newImg = CR.Cropper_resizeWidthKeepHeightProrata(img,839);

        //Creation de l'element l'image
        return $(newImg);
    };
    
    var _f_Lcl_SvFnl = function (img) {
//    this.Locale_SaveFinal = function (img) {
        //FinalImage
        var Fi = {
            img : img,
            name : img.name,
            top : "" //Plus tard
        };
        
        return Fi;
    };
    
    var _f_Comfy = function(i,w,h) {
//    this.VerifyComfy = function(i,w,h) {
        try {
        
            if ( KgbLib_CheckNullity(i) ) { 
                return; 
            }

            //ImageRules
            ir = _f_Gdf();

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

                if ( $.inArray( filef, ir.ft ) === -1 ) {
                    //Est ce que le type de l'image est autorisé
                    return "__COV_BAD_TYPE";
                } else if ( i.size > ir.sz ) {
                    //Est ce que le type de l'image est autorisée
                    return "__COV_BAD_SIZE";
                } else {
                    //QUESTION : Est ce que l'image respecte les dimensions minimales et maximales ?

                    //Controle de la hauteur
                    if ( h < ir.h_min ) {
                        return "__COV_BAD_DIMS_MIN";
                    } else if ( h > ir.h_max ) {
                        return "__COV_BAD_DIMS_MAX";
                    } 
                    //Controle de la largeur
                    if ( w < ir.w_min ) {
                        return "__COV_BAD_DIMS_MIN";
                    } else if ( w > ir.w_max ) {
                        return "__COV_BAD_DIMS_MAX";
                    } 

                }

            }

            return true;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_OnErr = function (c) {
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
        
    var _f_CvrChg = function (files) {
//    this.Cover_HandleChange = function (files) {
        try {
            
            /*
             * [DEPUIS 16-07-15] @BOR
             */
            if ( !KgbLib_CheckNullity(_xhr_chcvr) | !KgbLib_CheckNullity(_xhr_dlcvr) ) {
                return;
            }
            
            if ( KgbLib_CheckNullity(files) || files.lenght > 1 ) { 
                return;
            }
            
//        if ( $("#a-h-t-top-tr-img-max").children().length > 1 ) 
//            $("#cov_img_buffer").remove();
            
            //Hide l'ancienne image
//        $('#a-h-t-top-tr-img-max img').hide();
            
            //Acquisition des images
            $.each(files, function(k,v) {
                //TODO : verifier extensiion
                
                if (! window.FileReader ) {
//            alert("Switch sur Adobe ? Normallement, cela est resolu au niveau du server-side");
                    var m = Kxlib_getDolphinsValue("ERR_BZR_OBSOLETE");
                    alert(m);
                    //On reset l'input pour garantir un focntionnement normal
                    _f_Rst_Form(true);
                    return;
                } 
                
                //On vérifie que le fichier est une image
                var gd = _f_Comfy(v);
//            Kxlib_DebugVars([414,gd],true);
//            return;
                if ( gd !== true ) {
                    _f_OnErr(gd);
                    
                    //On reset l'input pour garantir un focntionnement normal
                    _f_Rst_Form();
                    
                    return;
                }
                
                var reader = new FileReader();
                reader.onload = function() {
                    var img = new Image();
                    img.src = reader.result;
                    
                    img.onload = function() {
                        //On vérifie que l'image remplie les conditions attendues
                        var gd = _f_Comfy(v, img.width, img.height);
                        if ( gd !== true ) {
                            _f_OnErr(gd);
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
                        $("#a-h-t-top-tr-img-img").addClass("this_hide");
                        
                        //On resize l'image en gardant le prorata
                        var $img = _f_CrtCvr(this); 
//                    _f_Lcl_SvFnl($img); //OLD
//                    Kxlib_DebugVars([$img.width,v.name],true);
//                    return;
                        _FnlImg = _f_Lcl_SvFnl($img);
//                        Kxlib_DebugVars([759,$img.name,v.name],true); 
//                        Kxlib_DebugVars([759,_FnlImg.name,$(_FnlImg.img).attr("id"),$(_FnlImg.img).css("width"),$(_FnlImg.img).css("height")],true); 
//                    return;
                        _FnlImg.name = v.name;
                        
                        //On bloque le declencheur
                        gt.OnGoing = true; //...
                        
                        //2:) Retirer les textes du Cover
//                    _f_TogDescTleVisi();
                        //3:) Retirer la partie gauche du bottom
                        _f_TogMnChcs(true);
                        
                        //Retire le fade
                        $("#a-h-t-top-fade").hide();
                        
                        //On prepare le Draggable
                        $img.draggable({
                            axis: "y",
                            drag: function(e, ui) {
                                var _height = $(e.target).height();
                                var _lmt_down = 260 - _height;
                                var _top = ui.position.top;
                                //tlh : TitLe Height
//                                var _tlh = $(".jb-a-h-t-top-tr-tle").outerHeight();
                                var _tlh = 0;
                                /*
                                 Kxlib_DebugVars([TOP : "+_top]);
                                 Kxlib_DebugVars([HEIGHT : "+_height]);
                                 Kxlib_DebugVars([TLE HEIGHT : "+tle]);
                                 //*/
                                
                                //*
                                
                                if ( _top > _tlh ) {
                                    ui.position.top = _tlh;
                                } else if ( _top < _lmt_down ) {
                                    ui.position.top = _lmt_down;
                                } 
                                //*/
                            }
                        });
                        $img.addClass("cover_move");
                        $img.addClass("ui-widget-content");
                        
                        //On identifie l'image
                        $img.attr("id", "cov_img_buffer");
//                    alert("preprend");
                        
                        //Ajoute l'image au Header
                        $("#a-h-t-top-tr-img-max").prepend($img);
                        
//                        Kxlib_DebugVars([809,_FnlImg.name,$(_FnlImg.img).attr("id"),$(_FnlImg.img).css("width"),$(_FnlImg.img).css("height")],true); 
                    };
                };
                
                reader.onerror = function() {
                    //TODO: Send to server
//                Kxlib_DebugVars([An error occured when trying to read the image. It could be due to safety reasons or whatever !"]);
                    return;
                };
                
                reader.readAsDataURL(v); 
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    }; 
    
    
    /******************** FIN TRAITEMENT SUR L'IMAGE DU COVER ********************/
    /*****************************************************************************/
    
    
    /*****************************************************************************/
    /***************** DEBUT MISE A JOUR DES INFORMATIONS DU HEADER **************/
    
    this.GetAndDisplaySettings = function (f,s,a) {
        _f_Srv_ChkStgs(f,s,a);
    };
    
    
    //Met à jour le nombre de Posts
    //STAY PUBLIC
    this.UpdatePostCount = function (a) {
        /*
            On récupère le nombre de Posts auprès du serveur
            On évite au maximum de solliciter le serveur aussi toutes les autres opérations se feront en local
            
            s = selector
            n = new number
            o = current number in page
        */
        //Cas à tester: 0, 10, 100, 1000, 1250, 10000, 12000, 15001, 100000, 296000, 312500, 1000000, 2850000, 100m
        var sn = a;
//        Kxlib_DebugVars([For Post =>"+sn]);
        var s = "#tr-h-t-d-20", 
            n = parseInt(sn), 
                o = parseInt($(s).data("length"));
        
//        Kxlib_DebugVars([n,o],true);
//        Kxlib_DebugVars([SERVER => "+newCount+"; CLIENT => "+curCount]);
        //        alert("For Post =>"+sn);
//        n = 3245100; //DEBUG, DEV, TEST
        if ( o !== n ) { 
            _f_HdrFmtCn(s, n);
        }
            
    };
    
    
    //Met à jour le nombre de Followers
    //STAY PUBLIC
    this.UpdateFolwrCount = function (a) {
        /*
            On récupère le nombre de Followers auprès du serveur
            On évite au maximum de solliciter le serveur aussi toutes les autres opérations se feront en local
            
            s = selector
            n = new number
            o = current number in page
        */
        //Cas à tester: 0, 10, 100, 1000, 1250, 10000, 12000, 15001, 100000, 296000, 312500, 1000000, 2850000, 100m
        var sn = a;
//        Kxlib_DebugVars([For Follower =>"+sn]);
        var s = "#tr-h-t-d-30", 
            n = parseInt(sn), 
                o = parseInt($(s).data("length"));
        
//        Kxlib_DebugVars([SERVER => "+newCount+"; CLIENT => "+curCount]);

        if ( o !== n ){ 
            _f_HdrFmtCn(s, n);
        }
        
            
    };
    /****************** FIN MISE A JOUR DES INFORMATIONS DU HEADER ***************/
    /*****************************************************************************/
  
    //STAY PUBLIC
    this.CheckOperation = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            
            var a = $(x).data("action");
            switch (a) {
                case "ch_cover":
                        /*
                         * [NOTE 16-07-15] @BOR
                         * Je pense que ce code ne sert à rien. En effet, en cliquant sur le bouton dans le menu, on clique sur un formulaire.
                         */
                        _f_Vw_ChgCvr();
                    break;
               /*
                * [DEPUIS 16-07-15] @BOR
                */
                case "start_del_cover":
                case "abort_del_cover":
                case "confirm_del_cover":
                        _f_DelCvr(x);
                    break;
                case "op_filters":
                    //this.Process_DelMyTr();
                    break;
                case "abort":
                    //this.Process_DelMyTr();
                    break;
                default :
                    //TODO : Incoherence
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /******************************************************************************************************************************************/
    /************************************************************ SERVER SCOPE ****************************************************************/
    /******************************************************************************************************************************************/
    /*
    //URQID => Vérifier le nombre de Followers de la Tendance
    this.checkFolwNb_url = "http://127.0.0.1/korgb/ajax_test.php";
    this.checkFolwNb_uq = "trpg_check_folw";
    
    this.Srv_CheckFolwNb = function(){
        var th = this;
        
        var onsuccess = function (datas) {
            if(! KgbLib_CheckNullity(datas.err) ) 
                alert(datas.err);
            
//            alert("CHAINE JSON AVANT PARSE"+datas);
            datas = JSON.parse(datas);
            
            //Trater le nombre
            th.UpdateFolwrCount(datas.folw);
        };

        var onerror = function(a,b,c) {
            alert("AJAX ERR : "+th.checkFolwNb_uq);
        };

        var toSend = {
            "urqid": th.checkFolwNb_uq,
            "datas": {
            }
        };

        Kx_XHR_Send(toSend, "post", this.checkFolwNb_url, onerror, onsuccess);
    };
    */
    
    var _Ax_SvCvr = Kxlib_GetAjaxRules("TRPG_SET_TCOV");
    var _f_Srv_SvCvr = function (ti,ds,s) {
//    this._Srv_SaveCover = function (ti,ds,s) {
        if ( KgbLib_CheckNullity(ti) | KgbLib_CheckNullity(ds) | KgbLib_CheckNullity(s) ) {
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
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_TRD_GONE":
                            case "__ERR_VOL_TREND_GONE":
                                    return;
                                break;
                            case "__ERR_VOL_IMG_NOT_COMPLY":
                                    return;
                            case "__ERR_VOL_FAILED":
                                    Kxlib_AJAX_HandleFailed();
                                    
                                    /*
                                     * [DEPUIS 16-07-15] @BOR
                                     */
                                    /*
                                    //TODO : Retirer l'image en Buffer
                                    //Afficher le message adéquat dans la zone d'erreur
                                    var code = "ERR_COM_AJAX_GDT_FAIL"; 
                                    var Nty = new Notifyzing ();
                                    Nty.FromUserAction(code,null,true);
                                    //*/
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    Kxlib_AJAX_HandleDeny();
                                break;
                            default:
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) ) {
                    /*
                     * Données attendues : 
                     *  (1) Données de base d'une Tendance : titre, description, participation, catégorie, gratification
                     *  (2) L'image de couverture
                     *  (3) Les données sur le propriétaire de la Tendance
                     */
                    var rds = [d.return];
                    $(s).trigger("datasready",rds);
                } else if ( KgbLib_CheckNullity(d.return) ) {
                    $(s).trigger("operended");
                } else {
                    //PARANO
                    return;
                }
                
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                return;
            }
            
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            alert("Error from onError function");
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
    //*
            
        //CURL : Necessaire pour le traitement à v1+.
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_SvCvr.urqid,
            "datas": {
                /*
                "img": encodeURIComponent(datas.img), 
                "img_cov_h": datas.img_cov_h, 
                "img_cov_w": datas.img_cov_w, 
                "img_cov_top": datas.img_cov_top 
                "img": encodeURIComponent(ds.img), 
                //*/
                "ti": ti,
                "img": encodeURIComponent(ds.img), 
                "in": ds.in,
                "ih": ds.ih, 
                "iw": ds.iw, 
                "it": ds.it,
                "cl": curl
            }
        };
    //*/
        _xhr_chcvr = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_SvCvr.url, wcrdtl : _Ax_SvCvr.wcrdtl });
    };
    
    
    var _Ax_DelCvr = Kxlib_GetAjaxRules("TRPG_DEL_TCOV");
    var _f_Srv_DelCvr = function (ti,s) {
        if ( KgbLib_CheckNullity(ti) | KgbLib_CheckNullity(s) ) {
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
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_TRD_GONE":
                            case "__ERR_VOL_TREND_GONE":
                                break;
                            case "__ERR_VOL_NOTGT":
                                    /*
                                     * [DEPUIS 17-07-15] @BOR
                                     * On retire la zone d'attente et on affiche le message
                                     */
                                    m = Kxlib_getDolphinsValue("ERR_COV_ONDEL_NOTGT");
                                   /*
                                    * On masque les zones dans le HEADER
                                    */
                                    $(".jb-trpg-cov-dlcf-wait-mx").addClass("this_hide");
                                    $(".jb-trpg-cov-dlcf-dc-mx").addClass("this_hide");
                                    
                                    /*
                                     * On affiche le message
                                     */
                                    $(".jb-trpg-cov-dlcf-gninf").html(m);
                                    $(".jb-trpg-cov-dlcf-gninf-mx").removeClass("this_hide");
                                    
//                                    Kxlib_DebugVars([KgbLib_CheckNullity(_xhr_dlcvr),_xhr_dlcvr === null,typeof _xhr_dlcvr],true);
                                break;
                            case "__ERR_VOL_FAILED":
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    Kxlib_AJAX_HandleDeny();
                                break;
                            default:
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) ) {
                    /*
                     * Données attendues : 
                     *  (1) Données de base d'une Tendance : titre, description, participation, catégorie, gratification
                     *  (2) Les données sur les statitistiques (Nombre d'abonnés, Nombre de publications)
                     *  (3) Les données sur le propriétaire de la Tendance
                     */
                    var rds = [d.return];
                    $(s).trigger("datasready",rds);
                } else {
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                return;
            }
            
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            alert("Error from onError function");
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
            
        //CURL : Necessaire pour le traitement à v1+.
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_DelCvr.urqid,
            "datas": {
                "ti": ti,
                "cl": curl
            }
        };
    
        _xhr_dlcvr = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_DelCvr.url, wcrdtl : _Ax_DelCvr.wcrdtl });
    };
    
    //URQID => Récupérer les settings de la Tendance
    var _Ax_ChkStgs = Kxlib_GetAjaxRules("TRPG_GET_STGS");
    var _f_Srv_ChkStgs = function (f,s,ti) {
//    this.Srv_CheckSettings = function (f,s,ti) {
        
        if ( KgbLib_CheckNullity(f) | KgbLib_CheckNullity(s) | KgbLib_CheckNullity(ti) ) {
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
                                Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_TRD_GONE":
                            case "__ERR_VOL_TREND_GONE":
                                //TODO : Indiquer à l'utilisateur que la Tendance n'existe plus via un overlay ou juste en rechargeant
                                return;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DNY_AKX":
                            case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    var Nty = new Notifyzing ();
                                    Nty.FromUserAction("ERR_COM_AJAX_GDT_FAIL",null,true);
                                return;
                            default:
                                return;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return)  )  {
                    /*
                     * Données attendues : 
                     *  (1) Données de base d'une Tendance : titre, description, participation, catégorie, gratification
                     *  (2) L'image de couverture
                     *  (3) Les données sur le propriétaire de la Tendance
                     */
                    //Traiter le nombre
//                    alert("TR_HDR => 857");
                    f(s,d.return,true);
                    //Sauvegarder les données
                    _TrSrvStgs = d.return;
//                    alert(datas.p[0]);
//                    alert(_TrSrvStgs.title);
                
                    //Mettre à jour les données au niveau du Cover
                    gt.UpdHdrWNwStgs(d.return);
                    
                } else {
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                return;
            }

        };

        var onerror = function (a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            alert("AJAX ERR : "+th.checkSettings_uq);
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };

        var toSend = {
            "urqid": _Ax_ChkStgs.urqid,
            "datas": {
                "ti" : ti
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_ChkStgs.url, wcrdtl : _Ax_ChkStgs.wcrdtl });
    };
    
    /*
    //URQID => Vérifier le nombre de Posts de la Tendance
    this.checkPostNb_url = "http://127.0.0.1/korgb/ajax_test.php";
    this.checkPostNb_uq = "trpg_check_post";
    this.Srv_CheckPostNb = function(){
        var th = this;
        
        var onsuccess = function (datas) {
            if(! KgbLib_CheckNullity(datas.err) ) 
                alert(datas.err);
            
//            alert("CHAINE JSON AVANT PARSE"+datas);
            datas = JSON.parse(datas);
            
            //Traiter le nombre
            th.UpdatePostCount(datas.post);
        };

        var onerror = function(a,b,c) {
            alert("AJAX ERR : "+th.checkPostNb_u);
        };

        var toSend = {
            "urqid": th.checkPostNb_uq,
            "datas": {
            }
        };

        Kx_XHR_Send(toSend, "post", this.checkPostNb_url, onerror, onsuccess);
    };
    //*/
    
    /*****************************************************************************************************************************************/
    /************************************************************* VIEW SCOPE ****************************************************************/
    /*****************************************************************************************************************************************/
    
    var _f_HdrFmtCn = function (s, a) {
//    this.HeaderFormatCount = function (s, a) {
        
        if ( KgbLib_CheckNullity(s) | KgbLib_CheckNullity(a) ) {
            return;
        }
        try {
            
//        Kxlib_DebugVars([TRPG - Folw Count to format =>"+a]);
            var b, c, n;
            if (a / 1000 === 1 || a / 10000 === 1 || a / 100000 === 1 || a / 1000000 === 1 || a / 10000000 === 1 || a / 100000000 === 1 || a / 1000000000 === 1) {
                switch (a) {
                    case 1000 :
                    case 10000 :
                    case 100000 :
                        c = 'k'; 
                        n = a / 1000;
                        break;
                    case 1000000 :
                    case 10000000 :
                    case 100000000 :
                        c = 'M';
                        n = a / 1000000;
                        break;
                    case 1000000000 :
                        c = 'G';
                        n = 1;
                        break;    
                }
                
            } else if (a >= 0 && a < 10000) {
                var f = parseFloat(a / 1000).toFixed(3).toString().replace(".", " ").split(" ");
//            Kxlib_DebugVars([]);
                
                if (f[0] !== "0") {
                    $(s).html(f);
                } else {
                    $(s).html(parseInt(f[1]));
                }
            } else if (a >= 10000 && a < 1000000) {
                var f = parseFloat(a / 1000).toFixed(3).toString().replace(".", " ").split(" ");
//            Kxlib_DebugVars([[0]]);
//            Kxlib_DebugVars([[1]]);
                
                if (f[1] === "000") {
                    c = 'k';
                } else {
                    b = " " + f[1];
                }
                
                $(s).html(f[0]);
            } else if (a >= 1000000 && a < 1000000000) {
                var f = parseFloat(a / 1000000).toFixed(1).toString().replace(".", " ").split(" ");
//            Kxlib_DebugVars([[0]]);
//            Kxlib_DebugVars([[1]]);
                
                c = 'M';
                if (f[1] === "0") {
                    b = "";
                } else {
                    b = "," + f[1];
                }
                
                $(s).html(f[0]);
                /*
                 alert(parseFloat(clbNb/1000));
                 var str = parseFloat(a/1000).toString().replace("."," ");
                 Kxlib_DebugVars([tr.length]);
                 if ( str.length === 1 ) {
                 str += " 000";
                 clbNbTx = str;
                 } else if ( str.length === 3 ) {
                 str += "00";
                 clbNbTx = str;
                 } else if ( str.length === 4 ) {
                 str += "0";
                 clbNbTx = str;
                 } else {
                 clbNbTx = str;
                 }
                 
                 ///*/
            } else if (a >= 1000000000 && a < 1000000000000) {
                var f = parseFloat(a / 1000000000).toFixed(1).toString().replace(".", " ").split(" ");
//            Kxlib_DebugVars([[0]]);
//            Kxlib_DebugVars([[1]]);
                
                c = 'G';
                if (f[1] === "0") {
                    b = "";
                } else {
                    b = "," + f[1];
                }
                
                $(s).html(f[0]);
            }
            
            /*
             * [NOTE 29-04-15] @BOR
             * Je suis assez perdu avec le code ci-dessous. Je ne comprends pas pourquoi "length" prend n plutot que "a".
             * je change les valeurs je verrai bien le résultat au fil de l'utilisation.
             * De plus, HTML() prend n quand il n'est pas toujours défini.
             */
            $(s).html(n);
            $(s).attr("title", a);
//            $(s).data("length", n);
            $(s).data("length", a);
//        Kxlib_DebugVars([B=>"+b]);
            if (typeof b !== undefined) {
                $('<span/>', {
                    class: 'tr-h-t-d-xx1',
                    text: b
                }).appendTo(s);
            }
//        Kxlib_DebugVars([C=>"+c]);
            if (typeof c !== undefined) {
                $('<span/>', {
                    class: 'tr-h-t-d-xx2',
                    text: c
                }).appendTo(s);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_AbortCvr = function () {
//    this.AbortCover = function () {

        try {
            //(1) Retirer l'image en bufering
            $("#cov_img_buffer").remove();
            
            //(2) On remet la précédente image
            $("#a-h-t-top-tr-img-img").removeClass("this_hide");
            
            //(3) Masquer la zone de choix
            _f_TogMnChcs();
            
            //(4) On remet le fade. Cela permet notamment de ne plus pouvoir mouvoir l'image en dessous. (La question c'est pk ne pas juste la rendre non draggable :o ? => Ce n'est pas dérangeant ! )
            $("#a-h-t-top-fade").show();
            
            //(5) On reset l'input pour garantir un fonctionnement normal
            _f_Rst_Form();
            
            //Afficher les textes sur le Cover
//       _f_TogDescTleVisi(true);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

   };
    //*/
    
    var _f_TogMnChcs = function(show){
//    this.ToggleMenusChoices = function(show){
        //La fonction est aussi utilisée pour faire apparaitre la zone.
//        alert("halo_abort");
        if ( KgbLib_CheckNullity(show) && !show ) {
            $("#tr-h-t-d-1-ul").removeClass("this_hide");
            $("#chcov_choices").addClass("this_hide");
        } else {
            //On affiche le menu avec les boutons "Choices"
            $("#tr-h-t-d-1-ul").addClass("this_hide");
            $("#chcov_choices").removeClass("this_hide");
        }
        
    };
    
    var _f_TogDescTleVisi = function (show) {
//    this.ToggleDescTitleVisi = function (show) {
//        alert("halo_title");
        if ( KgbLib_CheckNullity(show) && !show ) {
            $(".jb-a-h-t-top-tr-tle").addClass("this_hide");
            $(".jb-a-h-t-top-tr-desc").addClass("this_hide");
        } else {
            $(".jb-a-h-t-top-tr-tle").removeClass("this_hide");
            $(".jb-a-h-t-top-tr-desc").removeClass("this_hide");
        }
    };
  
    var _f_Vw_ChgCvr = function () {
//    this.StartHandleChangeCover = function () {
        //Délcencher le formulaire
        $("#tr_cover_action").click();
        //On ne fait rien d'autre sans que l'utilisateur n'aura pas sélectionné d'image
    };
    
    /*********************************************************************************************************************************************************/
    /********************************************************************** LISTERNERS  **********************************************************************/
    /*********************************************************************************************************************************************************/
    
    //Cette fonction permet d'effectuer des opérations simples sur le Header de Trend
    //NOTE : On utilise .html() au lieu de .text() car le texte peut contenir des hashtags. 
    //[Suite] Ce sont d'ailleurs les seules balises à être acceptées.
    
    //SaveTitle
    var st = "";
    //SaveDesc
    var sd = "";
    /***************** DEBUT EDIT HEADER TREND ******************/
    /*
    $(".jb-a-h-t-top-tr-tle").dblclick(function(){
//       alert("start here");
        $(this).attr("contenteditable",'true'); 
        $(this).focus(); 
        $(this).toggleClass("a-h-t-top-tr-edit"); 
        st = $(this).html();
    });
    
    $(".jb-a-h-t-top-tr-tle").keyup(function(e){
        if (e.which === 27){
            $(this).html(st);
            $(this).blur();
        } 
    });
    
    $(".jb-a-h-t-top-tr-tle").focus(function(){
//        Kxlib_DebugVars([Focus"]);
    });
    
    $(".jb-a-h-t-top-tr-tle").blur(function(){
//        Kxlib_DebugVars([Blur"]);
        $(this).attr("contenteditable",'false'); 
        $(this).toggleClass("a-h-t-top-tr-edit");
        
        if (! $(this).text().length ) {
             $(this).text(st);
        } 
        
        //TODO : Send new modification to server
    });
    //*/
    /**** DESCRIPTION ***/
    /*
    $(".jb-a-h-t-top-tr-desc").dblclick(function(){
//       alert("start here");
        $(this).attr("contenteditable",'true'); 
        $(this).focus(); 
        $(this).toggleClass("a-h-t-top-tr-edit"); 
        sd = $(this).html();
    });
    
    $(".jb-a-h-t-top-tr-desc").keydown(function(){
        if ( $(this).html().length ) {
            $(this).find("span").remove();
        }
    });
    
    $(".jb-a-h-t-top-tr-desc").keyup(function(e){
        if (! $(this).html().length ) {
            var ph = $(".a-h-t-top-tr-desc-alt").clone();
            $(ph).toggleClass("this_hide");
            //alert($(ph).html());
            $(this).empty();
            $(this).append(ph);
        }  
        
        if (e.which === 27) {
            $(this).html(sd);
            $(this).blur();
        } 
    });
    
    $(".jb-a-h-t-top-tr-desc").blur(function(){
//       alert("start here");
        $(this).attr("contenteditable",'false'); 
        $(this).toggleClass("a-h-t-top-tr-edit");
        
        //Si on perd le focus et qu'il n'y a que le span a l'interieur
        if ( $(this).find("span").length ) {
            //On laisse le span ... et on hide la zone.
            //L'utilisateur pourra toujours l'ouvrir à l'aide du bouton Edit
            $(this).find("span").remove();
        }
        
        //TODO : Send new modification to server
    });
    //*/                                                                                                
    /***************** FIN EDIT HEADER TREND ******************/
    
    $(".jb-tr-chcov-trg").hover(function() {
        if ( $(this).is(":hover") ) {
            $("#tr-chcov-trg-bis").addClass("hover");
        } else {
            $("#tr-chcov-trg-bis").removeClass("hover");
        }
    }, function() {
        //[NOTE 11-10-14] IMPORTANT !!
        $("#tr-chcov-trg-bis").removeClass("hover");
    });
    
//    $(".jb-tr-chcov-trg").change(function(e) {
    $(".jb-tr-chcov-trg").off("change").change(function(e) {
//        Kxlib_StopPropagation(e);
//        $(e.target).closest(".action_foll_choices").addClass("this_hide");
//        $(e.target).closest(".action_a").blur();
        
        var files = this.files;
        _f_CvrChg(files);

//        var _obj = new TrendHeader();
//        _obj.CheckOperation(this);
    });
    
//    $("#c_c_sauv").click(function(e){
    $(".jb-chcvr-ch-tgr[data-action='confirm_save_cover']").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        _f_SvCvr();
    });
    
//    $("#c_c_canc").click(function(e){
    $(".jb-chcvr-ch-tgr[data-action='abort_save_cover']").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_AbortCvr();
    });
    
    $(".jb-trpg-cov-dlcf-dc-dc-tgr").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        gt.CheckOperation(this);
    });
    
    
    /*********************************************************************************************************************************************************/
    /********************************************************************** INIT SCOPE ***********************************************************************/
    /*********************************************************************************************************************************************************/
    
}

var _obj = new TrendHeader();
function TrHeader_Receiver (){
    this.Routeur = function (th) {
        
        if ( KgbLib_CheckNullity(th) ) {
            return; 
        }
        
        _obj.CheckOperation(th);
    };
};