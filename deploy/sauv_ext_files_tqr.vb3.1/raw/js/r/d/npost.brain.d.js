/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 * GERE LES PROCESSUS D'AJOUT
 */


function Brain_HandlePost () {
    var gt = this;
    var _nwImg;
    var _nwVid;
//    this.newImage;
    var _nwImgNm;
    var _nwVidNm;
//    this.newImageName;
    var _file;
    /*
    //TODO : Remove this function and use KGBLIB
    this.checkNullity = function(arg) {
        if (arg === null || typeof(arg) === "undefined" || arg === "") 
            return 1;
        else 
            return 0;
    };
    */
   
   /***************************************************************************************************************************************************************************/
   /****************************************************************************** PROCESS SCOPE ******************************************************************************/
   /***************************************************************************************************************************************************************************/
   
    var _f_Gdf = function () {
//    this._GetDefaultValues = function () {
        
        var dv = {
            novoid          : /^[\s|\t|\n]+$/,
            /**
             * [NOTE 05-01-16]
             *      Ajouté du format "mp4" et passage de la limite de taille maximum à 6 Mo
             */
            "FrmtEna"       : ["png","jpg","jpeg","gif","mp4"],
            "imSzMx"        : 1048576*8,
            "vidSzMx"       : 1048576*20,
            /*
             * [NOTE]
             *      L'objectif est d'avoir des vidéos de maximum 30 secondes.
             *      Cependant, pour ne pas détériorer l'expérience utilisateur, il nous faut être légèrement souple.
             *      Aussi on accepte les vidéos de 60 secondes avec une tolérances de ~0.99 secondes.
             *          En effet, pour une vidéo de 7 secondes on peut avoir une longueur de 7.6 secondes.
             *          Sans cette tolérance, l'utilisateur qui voudra poster la dite vidéo sera bloqué alors que son odinateur lui indique 7 secondes
             */
            "vdMxLn"        : 61,
            //Preview "DnC"
            "DncMaxHeight"  :370,
            "DncMaxWidth"   :370,
            //Caractéristiques sur le texte lié à l'image
            "mxInLn"        :242,
            "errWinId"      :".jb-nwpst-err-mx",
            "npostWinId"    :".jb-nwpst-xpln",
            "npostImgId"    :"#inDncPreview",
            "focus"         :"in_npost_focus",
            "nPost_txtId"   :".jb-nwpst-txt",
            /*
            * Status :
            * err: error;
            * ...
            */
           "nPost_Status"   :"",
           /*
            * Mode =>
            * inml: inMyLIFE;
            * intr: inTREND;
            * fclb: forCELEB;
            */
           "nPost_Mode"     :"",
           "nPost_ErrMsg"   :"",
           "mustHash"       :false,
           /*
            * [DEPUIS 130815] @BOR
            * Correspond à du vieux code
            */
//           "mustDesc":true,
           "__MaxDesc"      :242
//           "__MaxDesc":242*2 //[DEPUIS 14-08-15] @BOR
        };
        
        return dv;
    };
    
    
    var _f_Action = function(x,a,e) {
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && KgbLib_CheckNullity(a) ) )  {
                    return;
                }

                var ac = ( a ) ? a : $(x).data("action");
                switch (ac) {
                    case "xtra-txt-clr-pkr" :
                            _f_XtraTxtClrPkr(x);
                        break;
                    case "xtra-txt-txt" :
                            _f_XtraTxtLiveTxt(x,e);
                        break;
                    default :
                        return;
                }
                return true; //?
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_ShwNotfIML = function(d) {
//    this.BuildNotificationsForIml = function(d) {
        try {
            if ( KgbLib_CheckNullity(d) ) {
                return;
            }
            
            var cn = Kxlib_ObjectChild_Count(d);
            //On inscrit le nombre d'article en stock
            $(".jb-f-w-loadm-nb").text(cn);
            
            /*
             * [DEPUIS 18-07-15] @BOR
             * On ajoute le texte lié en fonction du nombre de publications
             */
            var cd = (cn > 1) ? "ART_NEW_POSTS" : "ART_NEW_POST";
            var m = Kxlib_getDolphinsValue(cd);
            $(".jb-f-w-loadm-txt").text(m);
            
            //Affichage de la zone de notification
            $(".jb-fd-w-loadm").removeClass("this_hide");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    
    var _f_ShwNotfITR = function(d) {
//    this.BuildNotificationsForIntr = function(d) {
        try {
            if ( KgbLib_CheckNullity(d) ) {
                return;
            }
            
            var cn = Kxlib_ObjectChild_Count(d);

            //Modification du nombre d'articles
            $(".jb-f-e-loadm-nb").text(cn);

            /*
             * [DEPUIS 18-07-15] @BOR
             * On ajoute le texte lié en fonction du nombre de publications
             */
            var cd = ( cn > 1 ) ? "ART_NEW_POSTS" : "ART_NEW_POST";
            var m = Kxlib_getDolphinsValue(cd);
            $(".jb-f-e-loadm-txt").text(m);

            //Affichage de la zone de notification
            $(".jb-fd-e-loadm").removeClass("this_hide");
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    
    var _f_LdMrIml = function() {
//    this.LoadMorePostIml = function() {
        try {
            var cn = Kxlib_ObjectChild_Count(_nwArtIml);
            //On vérifie qu'on a bel et bien les Articles stockés
            if (! cn ) {
                return;
            }

            //On fait disparaitre le déclencheur après l'avoir remis à 0
            $(".jb-fd-w-loadm").addClass("this_hide");
            $(".jb-f-w-loadm-nb").text("(0)");

            //On affiche les nouveaux arrivés
            --cn;
            for (i=cn;i>=0;i--) { 
               _f_CrtNewStdArtImlVw(_nwArtIml[i]);
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_LdMrItr = function() {
//    this.LoadMorePostIntr = function() {
        var cn = Kxlib_ObjectChild_Count(_nwArtItr); 
        //On vérifie qu'on a bel et bien les Articles stockés
        if (! cn ) {
            return;
        }
        
        //On fait disparaitre le déclencheur après l'avoir remis à 0
        $(".jb-fd-e-loadm").addClass("this_hide");
        $(".jb-f-e-loadm-nb").text("(0)");
        
        //On affiche les nouveaux arrivés
        --cn;
        for (i = cn; i >= 0; i--) { 
           _f_CrtNewStdArtIntrVw(_nwArtItr[i]);
        }
            
    };
    
    
    var _f_HdlComOpgClzg = function (_to_o, _to_c, focus, msg) {
//    this.handleComOpeningClosing = function (_to_o, _to_c, focus, msg) {
        //Attention la methode ne teste pas la nullité des arguments
        $(_to_c).addClass("this_hide");
        $(_to_c).removeClass(focus);
        $(_to_o).addClass(focus);
        $(_to_o).removeClass("this_hide");
        
        if (msg) {
            $(".jb-nwpst-err-msg").html(msg);
        }
    };
    
    var _f_OpnErrWdw = function (msg){
//    this.openErrWindow = function (msg){
        _f_HdlComOpgClzg(_f_Gdf().errWinId,_f_Gdf().npostWinId,_f_Gdf().focus, msg);
    };
    
    var _f_ClzErrWdw = function (){
//    this.closeErrWindow = function (){
        _f_HdlComOpgClzg(_f_Gdf().npostWinId,_f_Gdf().errWinId,_f_Gdf().focus);
    };
    
    var _f_ShwSpcfdWdw = function(arg, m) {
//    this.openSpecifiedWind = function(arg, m) {
        //QUESTION ? : Est ce que la fenetre en focus n'est pas celle de la demande
        try {
            
            //focus
            var $fk = $(".in_npost_focus");
            var i = $fk.attr("id");
            
            if ( $(arg).attr("id") === i ) {
                return true;
            } else 
            {
                //(Sinon) Fermer la fenetre en focus
                $fk.addClass("this_hide");
                $fk.removeClass("in_npost_focus");
                //Mettre en focus la nouvelle fenetre
                $(arg).removeClass("this_hide");
                $(arg).addClass("in_npost_focus");
            }
            
            if ( m ) {
                $(".jb-nwpst-err-msg").html(m);
    //            $(".jb-nwpst-err-msg").text(m);
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Rst_ImgWind = function(x) {
//    this.reset_ImgWind = function(x) {
        try {
            
            //On enleve l'ancien image
            if ( x ) {
                /*
                 * On controle car on la methode peut etre utilisée dans le seul but de repartir vers l' 'ecran par defaut'
                 * 
                 * [NOTE : 28-06-14] J'ai un doute
                 */
                $(x).parent().children(_f_Gdf().npostImgId).children("img").remove();
            }
            
            //On reset le form
            Kxlib_ResetForm("wrap_inputFile");
            delete _nwImg;
    //        alert("Debug : In NPost Reset Image -> "+_nwImg);
            
            var id = _f_Gdf().npostWinId;
            _f_ShwSpcfdWdw(id);
            
            /*
             * [DEPUIS 24-06-16]
             */
            _f_XtraTxtShw();
            
            /*
             * [DEPUIS 30-04-16]
             */
            _f_XtraTxtEna();
            
            /*
             * [DEPUIS 12-06-16]
             */
            window.firstEnter = 0;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Rst_VidWind = function(x) {
//    this.reset_ImgWind = function(x) {
        try {
            
            //On reset le form
            Kxlib_ResetForm("wrap_inputFile");
            delete _nwVid;

            $(".jb-np-vid-pan-mx").addClass("this_hide");
            $(".jb-nwpst-xpln").removeClass("this_hide");
            
            /*
             * [DEPUIS 12-06-16]
             */
            window.firstEnter = 0;
            $(".in_npost_focus").removeClass(_f_Gdf().focus);
            $(".jb-nwpst-xpln").addClass(_f_Gdf().focus);

        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_RstNPprNext = function () {
//    this.ResetAndPrepareForNext = function () {
        //On reset la Zone Top. Surtout s'il s'agit d'un ajout TREND
        $(".jb-nwpst-tr-top-mx").addClass("this_hide");
        
        //On reset la zone IMG. On simule l'appui de la croix pour forcer la fonction à retirer la balise image
        _f_Rst_ImgWind($(".jb-nwpst-ab-pic"));
        
        //On reset le formulaire ayant le texte. 
        //RAPPEL : ".jb-nwpst-txt" est un <textarea>
        $(".jb-nwpst-txt").val('');
        
        //Reset du nombre de caractères affiché
        $(".jb-nwpst-opr-chr").html(_f_Gdf().mxInLn);
    };
    
     
    var _f_NwPstImlRdy = function () {
//    this.IsNPostInAddArtImldReady = function () {
        /* Permet de savoir si la fenetre d'ajout est prêt pour un nouvel ajout sous le mode InMyLIFE ou inTREND */
        var t = $(".jb-nwpst-tr-tle").text(), ti = $(".jb-nwpst-tr-top-mx").data("trid");
        return ( KgbLib_CheckNullity(t) && KgbLib_CheckNullity(ti) ) ? true : false;
    };
    
    var _f_AddInMyTrRdy = function () {
//    this.IsNPostInAddArtMyTrdReady = function () {
        /* Permet de savoir si la fenetre d'ajout est prêt pour un nouvel ajout sous le mode inTREND où Tr est dans le secteur MyTrends ou Following Trends */
        var ow = $("#start_npostTr_process").data("isown");
//        alert("DEBUG : in_CheckIfAddMyTRArt => "+_f_NwPstImlRdy());
//        alert("DEBUG : in_CheckIfAddMyTRArt => "+ow);
        return ( !_f_NwPstImlRdy() && (!KgbLib_CheckNullity(ow) && ow === "1" ) ) ? true : false;
    };
    
    var _f_CclInWin = function (x) {
//    this.HandleCancelInWin = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | !$(x).length | $(x).data("lk") === 1 ) {
                return;
            }

            if ( _f_NwPstImlRdy() ) {
                _f_RstNwPstWdw();
            } else {
                if ( _f_AddInMyTrRdy() ) {
                    $("#brain_submenu_mytrch").click();
                } else {
                    $("#brain_submenu_follgtrch").click();
                }
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
            
    };
    
    var _f_RstNwPstWdw = function () {
//    this.ResetAddWinPrepareForNext = function () {
        try {
                    
           /* Permet de réinitialiser la fenetre d'ajout selon la logique InMyLIFE.
            * 
            * RULES :
            *  (1) On reset le texte 
            *  (2) On reset l'image
            *  (3) On retire le titre et sa propriété title (quelle existe ou pas)
            *  (4) On retire la donnée permettant de connaitre l'id de la Tendance (quelle existe ou pas)
            *  (5) On retire la mention permettant de savoir si la Tendance vient de la liste des Tendances MyTrends
            *  (6) Reset du nombre de caractères affiché
            *  (7) Je retire le champ avec error
            * */
            //(1) On reset le texte 
            $(".jb-nwpst-txt").val('');

            //(2) On reset l'image
            _f_Rst_ImgWind($(".jb-nwpst-ab-pic"));

            //(3) On retire le titre et sa propriété title (quelle existe ou pas)
            $(".jb-nwpst-tr-tle").text("");
            $(".jb-nwpst-tr-tle").attr("title","");

            //(4) On retire la donnée permettant de connaitre l'id de la Tendance (quelle existe ou pas)
            $(".jb-nwpst-tr-top-mx").data("trid","");

            //(5) On retire la mention permettant de savoir si la Tendance vient de la liste des Tendances MyTrends
            $("#start_npostTr_process").data("isown","");

            //(6) Reset du nombre de caractères affichés
            $(".jb-nwpst-opr-chr").text(_f_Gdf().mxInLn);
            $(".jb-nwpst-opr-chr").removeClass("red"); //[DEPUIS 14-08-15] @BOR

            //(7) Je retire le champ avec error
            $(".jb-nwpst-txt").removeClass("error_field");
                    
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
        
    };
    
    
    var _f_ChkNPMode = function(arg, m) {
//    this.checkNPMode = function(arg, m) {
        try {
            
//            if ( KgbLib_CheckNullity(arg) | KgbLib_CheckNullity(m) ) {
            if ( KgbLib_CheckNullity(arg) ) {
                return;
            }
            
            //        alert("Mode1 : "+arg);
            _f_Gdf().nPost_Status = ( KgbLib_CheckNullity(arg) ) ? _f_Gdf().nPost_Status : arg ;
    //        alert("Mode2 : "+arg);

            switch (arg) {
    //        switch (_f_Gdf().nPost_Status) {
                case "explain": 
                        _f_ShwSpcfdWdw(".jb-nwpst-xpln", null);
                    break;
                case "wake_up": 
                        _f_ShwSpcfdWdw(".jb-nwpst-pls", null);
                    break;
    //            case "prepare": 
    //                    _id = "#newp_form";
    //                    _f_ShwSpcfdWdw(_id);
    //                break;
                case "err": 
                        _f_ShwSpcfdWdw(".jb-nwpst-err-mx", m);
                    break;
                default:
                     //skip
                    break;
            }

            /**
             * Cette instruction sert surtout au mode debug. 
             * Elle permet d'ouvrir la fenete pour afficher les erreurs.
             * Etant donné qu'il n'existe aucun message par defaut, cette instruction ne sert qu'à ouvrir la fenetre.
             * Voir la methode pour gérer les erreurs pour l'implémentation du process 'NPostErrOccur'
             */

            //_f_ClzErrWdw();
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_DropWkUp = function() {
//    this.dropWakeUp = function() {
        try {
//            Kxlib_DebugVars([drag_over"]);
            //On verifie qu'il n'y a pas deja une photo chargée
            if (! $(_f_Gdf().npostImgId).children("img").length ) {
                //On change le mode pour faire appraitre le message disant de drop l'element
                _f_ChkNPMode("wake_up", null);
            } else {
                //Permet d'annuler toute tentative d'ajout s'il y a déjà une image insérée. Evite ainsi un bug 'paralisant'
                return;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_PprDrop = function(e) {
//    this.prepareDrop = function(e) {
        try {
            //_f_ChkNPMode("prepare");
            
            if ( KgbLib_CheckNullity(e) ) {
                return;
            }
            
            /* [DEPUIS 12-06-16]
            Kxlib_StopPropagation(e);
            Kxlib_PreventDefault(e);
            //*/
            
            if ( e.dataTransfer ) {
                e.dataTransfer.dropEffect = "copy";
            } 
    //        else {
    //            alert("La fonctionnalité d'ajout par Glisser-Déposer n'est pas supportée par votre navigateur.");
    //        }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_SfetyOnImg = function(fl){
//    this.handleSafetyOnImage = function(arg){
        try {
            //Verifie : format, taille et forme
            if (! fl.type.match('image/*') ) {
                return {errType: "bad_file"};
            } else {
    //            alert(arg.name);
                var filef = fl.type.split('/').pop();
    //            alert("Format :"+filef);
                //*
                if ( $.inArray( filef, _f_Gdf().FrmtEna) === -1 ) {
                    //Est ce que le type de l'image est autorisé
                    return {errType: "bad_type"};
                } else if ( fl.size > _f_Gdf().imSzMx ) {
                    //Est ce que le type de l'image est autorisé
                    return {errType: "too_loud"};
                } else {
                    //Est ce que le type de l'image est autorisé
                    return {errType: "awaiting_for_dims"};
                }
                //*/
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_SfetyOnVid = function(fl){
        try {
            //Verifie : format, taille et forme
            if (! fl.type.match('video/*') ) {
                return {errType: "bad_file"};
            } else {
                var filef = fl.type.split('/').pop();
                var video = document.createElement('video');
                if ( $.inArray( filef, _f_Gdf().FrmtEna) === -1 ) {
                    //Est ce que le type de l'image est autorisé
                    return {errType: "err_vid_type"};
                } else if ( fl.size > _f_Gdf().vidSzMx ) {
                    //Est ce que le type de l'image est autorisé
                    return {errType: "err_vid_size"};
                } else {
                    //Est ce que le type de l'image est autorisé
                    return true;
                }
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Drop = function(e) {
//    this.handleDrop = function(e) {
        try {
            if ( KgbLib_CheckNullity(e) ) {
                return;
            }
            
            /* [DEPUIS 12-06-16]
            Kxlib_StopPropagation(e);
            Kxlib_PreventDefault(e);
            //*/
            
            //alert("IE");

            if (! e.originalEvent.dataTransfer ) {
    //            alert("La fonctionnalité d'ajout par Glisser-Déposer n'est pas supportée par votre navigateur.");
                return;
            }
            var files = e.originalEvent.dataTransfer.files; //Un objet Filelist
    //        var files = e.dataTransfer.files; //Un objet Filelist
            //alert(files);
            //alert("files");
            _f_TrtIptFls(files);
            //alert($(e.target).html());
            /*
            for (var i = 0, f; f = files[i]; i++) {
                output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
                f.size, ' bytes, last modified: ',
                f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a',
                '</li>');
            }
            alert('<ul>' + output.join('') + '</ul>');
            //*/
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_IsSqr = function (h, w) {
//    this.square = function (h, w) {
        return ( (h/w) === 1 ) ? true : false;
    };
    
    var _f_crtThbnl = function(img,name,coor){
//    this.createThumbnail = function(img,name){
       try {
           
//            Kxlib_DebugVars([img.height,img.width,(img.height/img.width),_f_IsSqr(img.height, img.width)],true);
//            Kxlib_DebugVars([JSON.stringify(coor)],true);
//            return;
            
            if (! _f_IsSqr(img.height, img.width) )  {
                /*
                 * [DEPUIS 06-12-15]
                 */
                 if (! $(".jb-tqr-skycrpr-src").length ) {
                     var poof = $("<span/>",{
                         class : "jb-tqr-skycrpr-src"
                     }).data("src",_file);
                     $(poof).insertAfter(".jb-tqr-skycrpr-sprt");
                 } else {
                     $(".jb-tqr-skycrpr-src").data("src",_file);
                 }

     //            Kxlib_DebugVars([$(".jb-tqr-skycrpr-src").length,_file],true);
                 $(".jb-tqr-skycrpr-sprt").trigger("open_with_datas");
                 /*
                  * ETAPE :
                  *      On reset le formulaire pour pouvoir relancer de manière fiable l'opération.
                  */
                 Kxlib_ResetForm("wrap_inputFile");
                 delete _nwImg;
            
                /*
                _errMsg = Kxlib_getDolphinsValue("ERR_APIC_BAD_FORMAT");
                _f_ChkNPMode("err",_errMsg); // [DEPUIS 06-12-15]
                //*/
            } else {
                //*
                 if ( ( parseInt(img.height) > _f_Gdf().DncMaxHeight ) || ( parseInt(img.width) > _f_Gdf().DncMaxWidth ) ) {
                     var oo = new Cropper();
                     img = oo.Cropper_ResizeTo(img,"371","371");
                 }
                 //*/ 
                 $tn = $(_f_Gdf().npostImgId);
                 $tn.append(img);
                 //*
                 $(".in_npost_focus").addClass("this_hide");
                 $(".in_npost_focus").removeClass(_f_Gdf().focus);
                 $tn.parent().addClass(_f_Gdf().focus);
                 $tn.parent().removeClass("this_hide");

                 //alert("url"+ img.url);
                 //*/

                 //On enregistre l'image pour pouvoir l'envoyer au niveau du serveur
     //            _nwImg = img;
                 //L'image est codée en base64, si on encode oas
     //            _nwImg = Kxlib_encodeURL(img.src); //LA fonction Kxlib_encodeURL() est obselete => encodeURIComponent
                 _nwImg = encodeURIComponent(img.src);
                 _nwImgNm = name;
                 /*
                 Kxlib_DebugVars([SOURCE RAW = "+img.src]);
                 Kxlib_DebugVars([SOURCE SECURED = "+Kxlib_encodeURL(img.src)]);
                 //*/
                
                 /*
                  * [DEPUIS 18-01-16]
                  */
                 coor = ( coor ) ? coor : {
                     t : 0,
                     l : 0
                 };
                 $("#inDncPreview img").data("coor",coor);
                 
                 /*
                  * [DEPUIS 21-04-16]
                  */
                 _f_XtraTxtShw(true);
                 
            }
        }
        catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
        
    var _f_RdFl = function(file) {
//    this.handleReadingOnFile = function(file) {
        try {
            if (! window.FileReader) {
                //TODO : "Switch sur Adobe ? Normallement, cela est resolu au niveau du server-side"
                return;
            } 

            var reader = new FileReader();
            var $t = this;

            /*
             * [DEPUIS 06-12-15]
             */
            _file = file;

            reader.onload = function() {
                var img = new Image();
                img.src = reader.result;

                img.onload = function() {
//                    Kxlib_DebugVars(["npost.brain.js",633,file.name],true);
//                    return;
    //                _f_crtThbnl(this,file.name).bind($t); //Il semble que bind n'a pas un effet probant mais je le mets kmm par secu
                    _f_crtThbnl(this,file.name);
                };
            };
            reader.onerror = function() {
                //TODO: Send to server
                //TODO : "An error occured when trying to read the image. It could be due to safety reasons or whatever !"
            };

            reader.readAsDataURL(file); 
    //        reader.readAsText(file); 
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_TrtIptFls = function(files) {
//    this.treatInputFiles = function(files) {
        try {
            if ( KgbLib_CheckNullity(files) ) { 
                return; 
            }
              
            $this = this;

            if (! files.length ) {
                _f_Rst_ImgWind();
                return;
            }
        
            $.each(files, function(k,f) {
                //alert('safety :'+v.size/1024);
                //alert( k + ": " + v.name );
                //_f_SfetyOnImg(v);
                
                var r, ftype;
                if ( f.type.match('image/*') ) {
                    r = _f_SfetyOnImg(f);
                    ftype = "image";
                } else if ( f.type.match('video/*') ) { 
                    r = _f_SfetyOnVid(f);
                    if ( r === true ) {
                        r = { errType: "err_vid_dura" };
                        window.URL = window.URL || window.webkitURL;
                        
                        var video = document.createElement('video');
                        video.preload = 'metadata';
                        video.onloadedmetadata = function() {
                            window.URL.revokeObjectURL(this.src);
                          
//                            Kxlib_DebugVars([typeof video.duration, video.duration, video.duration > 7],true);
//                            return;
                            if ( video.duration >= _f_Gdf().vdMxLn ) {
                                  _errMsg = Kxlib_getDolphinsValue("ERR_AVID_BAD_DURA");
                                  _f_ChkNPMode("err",_errMsg);
                            } else {
//                                var rdr = new FileReader(), _nwVid;
                                var rdr = new FileReader();
                                rdr.onload = function(rdrE) {
                                    /*
                                     * [NOTE 18-01-16]
                                     *      On écrit en dur l'entete car à cette date seul les fichiers *.mp4 sont acceptés
                                     */
                                    _nwVid = encodeURIComponent("data:video/mp4;base64,"+btoa(rdrE.target.result));
                                    
                                    _nwVidNm = f.name;
                                    
//                                    alert("NAME : "+f.name);
//                                    alert(_nwVid);
                                    
                                    $(".jb-nwpst-xpln").addClass("this_hide");
                                    $(".jb-np-vid-pan-mx").removeClass("this_hide");
                                    
                                    /*
                                     * [DEPUIS 24-06-16]
                                     * ETAPE :
                                     *      On s'assure que l'option permettant d'activer TEXTBAR n'apparait pas ou plus
                                     */
                                    _f_XtraTxtShw();
                                    
                                };
                                rdr.readAsBinaryString(f);
                            }
                        };
                        video.src = URL.createObjectURL(f);
                        ftype = "video";
                    } 
                    
                    /*
                     * ETAPE :
                     *      Dans tous les cas on change la fenêtre.
                     * [NOTE 12-06-16]
                     *      RAPPEL : Le bout de code ci-dessous provient (est inspiré) de la zone qui traite le cas de l'image.
                     */
                    $(".in_npost_focus").addClass("this_hide").removeClass(_f_Gdf().focus);
                    $(".jb-nwpst-pls").addClass("this_hide");
                    $(".jb-np-vid-pan-mx").addClass(_f_Gdf().focus);
                }
                
//                alert(video.src);
    //            alert(typeof(v));
    //            alert(typeof(r));
    //            alert(r);
    //            alert(v.type);
    //            Kxlib_DebugVars([typeof(r),v.type,r.errType],true);
                //*
                if ( typeof(r) === "object" ) {
                    var _err = r.errType, _errMsg = "";

                    switch(_err) {
                        case "bad_file" :
                                _errMsg = Kxlib_getDolphinsValue("ERR_APIC_BAD_FILE");
                                _f_ChkNPMode("err",_errMsg);
                            break;
                        case "bad_type" :
                                _errMsg = Kxlib_getDolphinsValue("ERR_APIC_BAD_TYPE");
                                _f_ChkNPMode("err",_errMsg);
                            break;
                        case "too_loud" :
                                _errMsg = Kxlib_getDolphinsValue("ERR_APIC_BAD_SIZE");
                                _f_ChkNPMode("err",_errMsg);
                            break;
                        case "awaiting_for_dims" :
                                 _f_RdFl(f);
                            break;
                        /*
                         * [DEPUIS 09-01-15]
                         */
                        case "err_vid_type" :
                                _errMsg = Kxlib_getDolphinsValue("ERR_AVID_BAD_TYPE");
                                _f_ChkNPMode("err",_errMsg);
                            break;
                        case "err_vid_size" :
                                _errMsg = Kxlib_getDolphinsValue("ERR_AVID_BAD_SIZE");
                                _f_ChkNPMode("err",_errMsg);
                            break;
                        default:
//                                alert("code inconnu"); //
                            break;
                    }
                } else {
                    return;
                }
                //*/
                //alert(r);
                //if (r === NULL) alert("ERR: You can only insert an image!");
                //--> Changer de view
                //--> Inserer photo dans la nouvelle view
                //--> Insérer pour annuler
            });
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_CrtNwPostAsTr = function() {
//    this.CreateNewPostAsTrend = function() {
//        alert();
    };
    
    var _f_NwPost = function(x) {
//    this.HandleNewPostProcess = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) | !$(x).length ) {
                return;
            }
           
            /*
             * On vérifie si le bouton est locked
             */
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            
            /* 
             * On commence par vérifier qu'il y bien une image de chargée
             * */
            //On vérifie qu'une image est affichée
            if ( !$("#inDncPreview img").length && $(".jb-np-vid-pan-mx").hasClass("this_hide") ) { 
                return;
            }
            
            /*
             * On vérifie qu'une image est stockée
             */
            if ( KgbLib_CheckNullity(_nwImg) && KgbLib_CheckNullity(_nwVid) ) { 
                return;
            }
            
            //var _cnt = $(_f_Gdf().nPost_txtId).text();
            var _cnt = $(_f_Gdf().nPost_txtId).val();
            
            //Check if the obligation on hashtags are maintened 
            /**
             * L'obligation vient obligatoirement du serveur.
             * Si on ne peut avoir l'obligation provenant du serveur, on prend la valeur par défaut.
             * Si la valeur par defaut ne satisfait pas celle du serveur après un 2eme essai
             * le serveur envoie un ordre d'annulation a l'application pour qu'elle le laisse decider
             */
            /*-- Demande de l'order auprès du serveur --*/
            /*-- On mime une NAOD 'no-answer-on-deman' --*/
//        var mustHash = _f_Gdf().mustHash;
        
            var at;
            if ( _f_Gdf().mustHash ) {
//        if ( true ) { //DEBUG ou TEST
                //In case, Does the content contain at leat one?
                //NOTE : Cette expre est independante de celle de detection en mode live
                var _rx = new RegExp("#([\da-zA-Z]{1}[\da-zA-Z]{2,})");
                var _ar = _cnt.match(_rx);
                
                if (!_ar) {
                    //Signaler l'erreur avec une bordure rouge
                    $(".jb-nwpst-txt").addClass("error_field");
                    
                    //Declencher erreur
                    var m = Kxlib_getDolphinsValue("TMLNR_ADDART_HTG_MDTRY");
                    alert(m);
                    
                    //On unlock le bouton
                    $(x).data("lk", 0);
                    //On fait disapparaitre le spinner
                    _f_IptWdwSpnr();
                    
                    return;
                } else { 
                    $(".jb-nwpst-txt").removeClass("error_field");
                }
            } else {
                //Si on demande au moins un hashtag cela signifie que le champ est forcement non vide si l'user n'atterit pas sur une erreur ...
                //... Sinon, on vérifie l'existence d'un texte en fonction de la configuration actuelle.
                
                var r__ = _f_NwPst_CnDsc($(_f_Gdf().nPost_txtId));
                if ( r__ !== true ) {
                    $(".jb-nwpst-txt").addClass("error_field");
                    
                    /*
                     * [DEPUIS 13-08-15] @BOR
                     *  Le message s'affiche maintenant que si le code est fourni.
                     *  De plus, j'ai préféré ne plus mettre de message car les erreurs mentionnées ci-dessus sont facilement compréhensibles.
                     */
                    if ( typeof r__ === "string" && r__ ) {
                        //Declencher erreur
                        var m = Kxlib_getDolphinsValue("TMLNR_ADDART_DESC_MDTRY");
                        alert(m);
                    }

                    //On unlock le bouton
                    $(x).data("lk", 0);
                    //On fait disapparaitre le spinner
                    _f_IptWdwSpnr();

                    //On remet le focus pour qu'il puisse retenter sa chance dès qu'il a fermé la fenetre popup
                    $(".jb-nwpst-txt").blur();
    //                $(".jb-nwpst-txt").focus();
                    
                    return;
                    
                } else {
                    $(".jb-nwpst-txt").removeClass("error_field");
                }
                
                //Dans tous les cas
                at = _cnt;
            }
            
            //On lock le bouton Trigger
            $(x).data("lk", 1);
            //On lock le bouton Reset
            $(".jb-nwpst-opt-chs[data-action='reset']").data("lk", 1);
            
            //On fait apparaitre le spinner
            _f_IptWdwSpnr(true);
            //On fait apparaitre la fenetre d'attente
            _f_IptWdwWtPan(true);
            
            //In case of content, format text {ori: 'text #text text', hash:[h1,h2,h3]}
            //Send content according to sending mode {'ajMod','relMod'}
            //In case of [ajMod], LaunchAddPostProcess()
            
            var s_iml = $("<span/>");
            var s_itr = $("<span/>");
            
            $(s_itr).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                //On vérifie s'il n'y a pas déjà des Articles en attente... 
                var cn = Kxlib_ObjectChild_Count(_nwArtItr); 
                if ( !$(".jb-fd-e-loadm").hasClass("this_hide") && cn ) {
                    //... Si oui, on commence par les afficher avant de passer à la suite
                    _f_LdMrItr();
                }

                //TODO : Reset vers la page "inMyLIFE" ou "Following TRENDS" 
                _f_RstNwPstWdw();
                $(".jb-nwpst-tr-top-mx").data('trid',"");
                /*
                 * [DEPUIS 30-04-15]
                 *      On RESET la zone VIDEO
                 */
                if ( d.vidu ) {
                    _f_Rst_VidWind();
                }

                //Masquer le 'brain_focus' actuel
                var $ob = $(".brain_focus");
                $ob.removeClass("brain_focus");
                $ob.addClass("this_hide");

                //On redirige vers Liste 
                if ( $(".jb-nwpst-tr-top-mx").data("isown") === "1" ) {

                    //Rediriger vers mes TENDANCES
                    $("#brain_th-mytrch").removeClass("this_hide");
                    $("#brain_th-mytrch").addClass("brain_focus");
                } else {
                    //Rediriger vers les TENDANCES que je suis
                    $("#brain_th-follgtrch").removeClass("this_hide");
                    $("#brain_th-follgtrch").addClass("brain_focus");
                }

                //On unlock le bouton
                $(x).data("lk",0);
                //On unlock le bouton Reset
                $(".jb-nwpst-opt-chs[data-action='reset']").data("lk",0);
                //On fait disapparaitre le spinner
                _f_IptWdwSpnr();
                //On masque la fenetre d'attente
                _f_IptWdwWtPan();
                
                /*
                 * ETAPE :
                 *      On ajoute l'Article
                 */
                _f_CrtNewStdArtIntrVw(d);
                
                
                /*
                 * [DEPUIS 24-06-]
                 * ETAPE :
                 *      On s'assure que l'option permettant d'activer TEXTBAR n'apparait pas ou plus
                 */
                _f_XtraTxtShw();  

                //On affiche la notification (Dolphins)
                var code = "ua_new_artintr"; 
                var o = {
                    "tr_title" : d.trtitle,
                    "tr_href" : d.trhref
                };

                var Nty = new Notifyzing();
                Nty.FromUserAction(code,o);

                //On met à jour le nombre d'Articles
                var nb = 0;
                nb = parseInt($(".jb-acc-spec-artnb").text());
                $(".jb-acc-spec-artnb").text(++nb);
            });
            
            $(s_iml).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                //On vérifie s'il n'y a pas déjà des Articles en attente... 
                var cn = Kxlib_ObjectChild_Count(_nwArtIml); 
//                    Kxlib_DebugVars([!$(".jb-fd-w-loadm").hasClass("this_hide"),cn],true);
                if ( !$(".jb-fd-w-loadm").hasClass("this_hide") && cn ) {
                    //... Si oui, on commence par les afficher avant de passer à la suite
                    _f_LdMrIml();
                }
                
                /*
                 * On ajoute l'Article
                 */
                _f_CrtNewStdArtImlVw(d);

                //Masquer la fenetre d'attente
                _f_IptWdwWtPan();

                //Reset pour permettre un nouvel ajout dans inMyLIFE
                _f_RstNwPstWdw();
                /*
                 * [DEPUIS 30-04-15]
                 *      On RESET la zone VIDEO
                 */
                if ( d.vidu ) {
                    _f_Rst_VidWind();
                }

                //On unlock le bouton
                $(x).data("lk",0);
                //On unlock le bouton Reset
                $(".jb-nwpst-opt-chs[data-action='reset']").data("lk",0);
                //On fait disapparaitre le spinner
                _f_IptWdwSpnr();
                
                /*
                 * [DEPUIS 24-06-]
                 * ETAPE :
                 *      On s'assure que l'option permettant d'activer TEXTBAR n'apparait pas ou plus
                 */
                _f_XtraTxtShw();           

                //On affiche la notification
                var Nty = new Notifyzing ();
                Nty.FromUserAction("ua_new_artiml");

                //On met à jour le nombre d'Articles
                var nb = 0;
                nb = parseInt($(".jb-acc-spec-artnb").text());
                $(".jb-acc-spec-artnb").text(++nb);
            });
            
//            Kxlib_DebugVars([parseInt($("#inDncPreview img").data("coor").t),parseInt($("#inDncPreview img").data("coor").l)],true);
//            return;

            /*
             * [DEPUIS 29-04-16]
             */
            var xtrabar = null;
            if ( !$(".jb-nwpst-pic-xtra-txt").hasClass("this_hide") && $(".jb-nwpst-pic-xtra-txt").text() && $(".jb-nwpst-pic-xtra-txt").data("clcd") ) {
                xtrabar = {
                    tx  : $(".jb-nwpst-pic-xtra-txt").text(),
                    cd  : $(".jb-nwpst-pic-xtra-txt").data("clcd"),
                    top : $(".jb-nwpst-pic-xtra-txt").position().top,
                };
            }
            
//            Kxlib_DebugVars([JSON.stringify(xtrabar)],true);
//            return;

            /*
             * [DEPUIS 18-01-15]
             */
            var fds = {};
            if (! $("#inDncPreview img").length ) {
                fds = {
                    "type" : "video",
                    "data" : _nwVid,
                    "opts" : {
                        istory      : ( $(".jb-brain-menu-action.selected").data("action") === "add-art-sod" ) ? true : false,
                        "xtrabar"   : xtrabar 
                    }
                };
            } else {
                fds = {
                    "type" : "image",
                    "data" : _nwImg,
                    "opts" : {
                        istory      : ( $(".jb-brain-menu-action.selected").data("action") === "add-art-sod" ) ? true : false,
                        edge        : 400,
                        top         : parseInt($("#inDncPreview img").data("coor").t),
                        left        : parseInt($("#inDncPreview img").data("coor").l),
                        "xtrabar"   : xtrabar 
                    }
                };
            }
            
//            Kxlib_DebugVars(["ADD ARTICLE *_*",JSON.stringify(fds)],true);
//            Kxlib_DebugVars(["ADD ARTICLE *_*",$(".jb-brain-menu-action.selected").data("action")],true);
//            return;
            
            //S'il s'agit de la création d'un article de type Tendance on switch
            var trid = $(".jb-nwpst-tr-top-mx").data('trid');
            if (! KgbLib_CheckNullity(trid) ) {
                _f_Srv_SaveItr(fds.type,fds.data,fds.opts,trid,at,x,s_itr);
            } else {     
                _f_Srv_SaveIml(fds.type,fds.data,fds.opts,at,x,s_iml);    
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_NwPst_CnDsc = function(o) {
        try {
            
            if ( KgbLib_CheckNullity(o) && typeof $(o).val() !== "undefined" ) {
                return;
            }
            
            /*
            * [02-09-14] @author Lou Carther
            *  Ajout de la nouvelle manière de vérifier le "texte".
            *  Cela évite de NE METTRE QUE DES CARACTERES qui n'ont aucune représentation visuelle pour les humains.
            *  Avant le code laissait passé du contenu qui ne contenait par exemple que : '\s', '\t', '\n', '\r', '\r\n'
            */
           var t = $(o).val();
           var is_validate = true;
           
           /*
            * dcn : DescriptionCouNt
            * [DEPUIS 14-08-15] @BOR
            */
            var dcn = CountChar_SkipHash2($(o));
//            Kxlib_DebugVars([ypeof dcn, dcn, typeof _f_Gdf().__MaxDesc, _f_Gdf().__MaxDesc]);
            var errm = "";

            if ( KgbLib_CheckNullity(t) ) {
                is_validate = false;
            } else if ( _f_Gdf().novoid.test(t) ) {
                is_validate = false;
            } else if ( dcn > _f_Gdf().__MaxDesc ) {
//                    } else if ( _cnt.length > _f_Gdf().__MaxDesc ) { //[DEPUIS 14-08-15] @BOR
                is_validate = false;
            } 
            
            return ( errm ) ? errm : is_validate; 
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_LdPdArt = function(x) {
//    this.LoadPdArt = function() {
        /* 
         * Permet de charger de nouveaux Articles dans la page TIMELINER. Il s'agit d'Articles anterieurs.
         * * */
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            /*
             * [NOTE 04-05-15] @BOR
             * ETAPE :
             * On vérifie que le bouton est disponible
             */
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            /*
             * [DEPUIS 30-04-15]
             * ETAPE :
             * On bloque le bouton
             */
            $(x).data("lk",1);
            
            /*
             * RULES :
             * On envoie les données au serveur. 
             *      (1) Si les deux colonnes sont vides, le serveur renvoie x des Articles les plus récents s'ils existent.
             *      (2) Une des colonnes est vide, le seveur renvoie x des Articles les plus récents pour la col vide et anterieur pour celle qui est pleine.
             *      (3) Les deux colonnes sont non vides, le serveur renvoie x des Articles anterieurs pour chaque colonne s'ils existent
             * [NOTE 25-06-14] Une évolution est possible dans le cas où les deux colonnes n'ont pas le même nombre d'Articles dans leur colonne respective 
             * * */
            var $lil = ($(".jb-tmlnr-mdl-std:not([data-isgone='1']):last").length) ? $(".jb-tmlnr-mdl-std:not([data-isgone='1']):last").data("item") : "";
            var $lit = ($(".jb-tmlnr-mdl-intr:not([data-isgone='1']):last").length) ? $(".jb-tmlnr-mdl-intr:not([data-isgone='1']):last").data("item") : "";
            
            /*
             * [DEPUIS 09-11-15] @author BOR
             *      On sélectionne HMT (HowManyTimes)
             */
            var hmt = "";
            if (! $(".jb-tqr-hmt").length ) {
                $(x).data("lk",0);
                return;
            } else if ( $(".jb-tqr-hmt").data("hmt") ) {
                hmt = $(".jb-tqr-hmt").data("hmt");
            }
                    
//            Kxlib_DebugVars([$lil,$lit,hmt],true);
//            return;

            //On affiche le spinner
            _f_PdArtWdwSpnr(true);
            
            var s = $("<span/>");
            _f_Srv_CheckGetPdArt(x,$lil, $lit, hmt, s);
            
            $(s).on("operended",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                /*
                 * [DEPUIS 04-05-15] @BOR
                 */
                _f_DisplayPdArt(d,x);
                
                /*
                 * [DEPUIS 20-12-15]
                 */
//                TqOn("RBD_FR_FV"); //[DEPUIS 27-04-16]
            });
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_SplCz = function (czc,o) {
        try {
            if ( KgbLib_CheckNullity(czc) ) {
                return;
            }
            
            switch (czc) {
                case "__ERR_VOL_REF_ART_GONE" :
                case "__ERR_VOL_ART_GONE" :
                        //adi : ArticleDomId
                        if ( !( o.hasOwnProperty("adi") && !KgbLib_CheckNullity(o.adi) ) | !( o.hasOwnProperty("atp") && !KgbLib_CheckNullity(o.atp) ) ) {
                            return;
                        }
                        
                        var $ad = ( o.atp === "intr" ) ? $(".jb-tmlnr-mdl-intr[data-item='"+o.adi+"']") : $(".jb-tmlnr-mdl-std[data-item='"+o.adi+"']");
                        if (! $($ad).length ) {
                            return;
                        }
                        
                        /*
                         *  On signale que la publication est "GONE"
                         */
                        $($ad).data("isgone",1);
                        $($ad).attr("data-isgone",1);
                        
                        /*
                         *  On récupère la direction si elle existe et on retente une demande le cas échéant.
                         */
                        if ( o.hasOwnProperty("dir") && !KgbLib_CheckNullity(o.dir) && $.inArray(o.dir,["top","btm"]) !== -1 && !KgbLib_CheckNullity(o.tryag) && o.tryag === 1 ) {
                            var arf;
                            /*
                             * On récupère la nouvelle référence si elle existe.
                             * On vérifie si l'Article existe avant de tenter de relancer une demande
                             */
                            if ( o.dir === "top" ) {
                                arf = ( o.atp === "intr" ) ? $(".jb-tmlnr-mdl-intr:not([data-isgone='1']):first") : $(".jb-tmlnr-mdl-std:not([data-isgone='1']):first");
                            } else {
                                arf = ( o.atp === "intr" ) ? $(".jb-tmlnr-mdl-intr:not([data-isgone='1']):last") : $(".jb-tmlnr-mdl-std:not([data-isgone='1']):last");
                            }
                            
                            if (! $(arf).length ) {
                                return 0;
                            }
                            
                            /*
                             * On relance une demande
                             */
                            if ( o.dir === "top" ) {
                                if ( o.atp === "intr" ) _f_Srv_ChkNwrItr();
                                else _f_Srv_ChkNwrIml();
                            } else {
                                var x = $(".jb-tmlnr-loadm-trg[data-scp='ml']");
                                //On réinitialise le marqueur pour permettre une autre tentative
                                $(x).data("lk",0);
                                _f_LdPdArt(x);
                            }
                            
                            return $(arf).length;
                            
                        }
                        
                    break;
                default :
                    return;
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Sharon = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
               return;
            }
            $(x).data("lk",1);
            
            /*
             * [DEPUIS 22-11-15] @author BOR
             *      On récupère le lien permanent de l'image
             * [DEPUIS 12-06-16]
             *      J'ai refactorisé le code pour qu'il prenne en compte tous les cas et de telle sorte qu'il soit plus fiable.
             */
            if ( $(x).data("art-mdl") !== "on_unq" ) {
                var dc__ = Kxlib_DataCacheToArray($(x).closest(".jb-tmlnr-mdl-intr").data("cache"));
                if ( !KgbLib_CheckNullity(dc) && $.isArray(dc) ) {
                    return;
                }
                var dc = dc__[0];
            } else {
                //idi = ItemDomId
                var idi = Kxlib_DataCacheToArray($(".jb-unq-art-mdl").data("item"))[0][1];
                if (! ( !KgbLib_CheckNullity(idi) && $(idi) && $(idi).length && $(idi).data("cache") )  ) {
                    return;
                }
                
                var dc__ = Kxlib_DataCacheToArray($(idi).data("cache"));
                if (! ( !KgbLib_CheckNullity(dc__) && $.isArray(dc__) ) ) {
                    return;
                }
                var dc = dc__[0];
            }
            
            var link = "http://".concat(escape(dc[0][7]));
            var psd = dc[3][2].toString().toLowerCase();
            
            if ( KgbLib_CheckNullity(link) | KgbLib_CheckNullity(psd) ) {
                return;
            }
            
//            Kxlib_DebugVars([link,psd],true);
             
            var a = $(x).data("action");
            switch(a) {
                case "amdl_sharon_fb" :
                        FB.ui({
                              method    : 'share',
                              href      : link
                        },function(r) {
                            if (r && !r.error_message) {
                                $(x).data("lk",0);
                            } else {
//                              alert("Erreur: l'opération a échoué ! Veuillez réessayer ultérieurement.");
                              $(x).data("lk",0);
                            }
                        });
                    break;
                case "amdl_sharon_twr" :
                        var text  = encodeURIComponent("Je viens de partager une publication de trenqr.me/".concat(psd).concat(", postée sur #Trenqr :"));
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
    
    var _f_SODAct = function (x,a,e) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) | KgbLib_CheckNullity(e) ) {
                return;
            }
            
            switch (a) {
                case "helpin" :
                        $(".jb-np-i-ftr-pod-h-m-bmx").removeClass("this_hide");
                    break;
                case "helpout" :
                        if ( $(x).is(".jb-np-i-ftr-pod-hlp-tgr-h") ) {
                            setTimeout(function(){
                                if (! $(".jb-np-i-ftr-pod-h-m-bmx:hover").length ) {
                                    $(".jb-np-i-ftr-pod-h-m-bmx").addClass("this_hide");
                                }
                            },600);
                        } else if ( $(x).is(".jb-np-i-ftr-pod-h-m-bmx") ) {
                            $(".jb-np-i-ftr-pod-h-m-bmx").addClass("this_hide");
                        }
                    break;
                case "change" :
                        if ( $(x).is(":checked") ) {
                            
                            $(".brain-th-com[data-sec='nwiml']").stop(true,true).effect("shake",{
                                direction: "up",
                                distance : 10,
                                times : 2
                            }).addClass("pod");
                            $(".jb-nwpst-iml-ftr-pod-bmx").stop(true,true).addClass("starting",250).switchClass("starting","activate",250);
                            $(".jb-nwpst-xpln").stop(true,true).addClass("pod",250);
                            
                            $(".jb-np-i-ftr-pod-lbl").stop(true,true).addClass("pod");
                            $(".jb-np-i-ftr-pod-hlp-tgr-h").addClass("pod");
                            
//                            $(".brain-th-com[data-sec='nwiml']").addClass("pod");
                            $(".jb-nwpst-txt").addClass("pod");
                        } else {
                            $(".brain-th-com[data-sec='nwiml']").stop(true,true).removeClass("pod",250);
                            $(".jb-nwpst-xpln").stop(true,true).removeClass("pod",250);
                            
                            $(".jb-nwpst-iml-ftr-pod-bmx").stop(true,true).removeClass("activate",250);
                            
                            $(".jb-np-i-ftr-pod-lbl").removeClass("pod");
                            $(".jb-np-i-ftr-pod-hlp-tgr-h").removeClass("pod");
                            
                            $(".jb-nwpst-txt").removeClass("pod",250);
                        }
                    break;
                default: 
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_XtraTxtShw = function(shw) {
        try {
            if ( shw ) {
                $(".jb-tqr-brn-nwp-o-sec[data-scp='pic-emb-text']").removeClass("this_hide");
            } else {
                $(".jb-tqr-brn-nwp-o-sec[data-scp='pic-emb-text']").addClass("this_hide");
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_XtraTxtEna = function(x,frc_ena) {
        try {
            
            if ( ( !KgbLib_CheckNullity(x) &&  $(x).filter(":checked").length ) | frc_ena) {
                $(".jb-nwpst-pic-xtra-txt").removeClass("this_hide");
                $(".jb-tqr-brn-nwp-o-sec[data-scp='pic-emb-text'] .jb-tqr-brn-nwpst-o-s-fld").filter("[data-sec='text-input'],[data-sec='color-selector']").removeClass("this_hide");
            } else {
                $(".jb-nwpst-pic-xtra-txt").addClass("this_hide");
                $(".jb-tqr-brn-nwp-o-sec[data-scp='pic-emb-text'] .jb-tqr-brn-nwpst-o-s-fld").filter("[data-sec='text-input'],[data-sec='color-selector']").addClass("this_hide");
                
                /*
                 * ETAPE :
                 *      On réinitialise les éméments
                 */
                _f_XtraTxtRst();
                
                /*
                 * ETAPE :
                 *      On DECHECK la case
                 */
                $(".jb-tqr-brn-nwp-x-t-ena").attr("checked",false);
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_XtraTxtClrPkr = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            var clrcd = $(x).data("code");
            switch (clrcd) {
                case "none" :
                        color = "transparent";
                    break;
                case "default" :
                        color = "rgba(206,59,59,0.7)";
                    break;
                case "std_trenqr" :
                        color = "rgba(0,69,137,0.6)";
                    break;
                case "std_friend" :
                        color = "rgba(78, 147,220,0.65)";
                    break;
                case "std_pod" :
                        color = "rgba(255,255,100,0.85)";
                    break;
                case "std_trend" :
                        color = "rgba(105,78,163,0.7)";
                    break;
                case "std_black" :
                        color = "rgba(0,0,0,0.7)";
                    break;
                default :
                    return;
            }
            
            $(".jb-nwpst-pic-xtra-txt")
                .data("clcd",clrcd)
                .attr("data-clcd",clrcd)
                .css({
                    "background-color" : color
                });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_XtraTxtLiveTxt = function(x,e) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(e) ) {
                return;
            }
            
            var txt = $(".jb-tqr-brn-nwp-x-t-ipt").val();
            if ( !txt ) {
                 $(".jb-nwpst-pic-xtra-txt ._text").text("");
                return;
            } else if ( txt.length > 50 ) {
                return;
            }
            
            $(".jb-nwpst-pic-xtra-txt ._text").text(txt);
            
            if ( $(".jb-nwpst-pic-xtra-txt ._text").width() > 354 ) {
                txt = txt.slice(0,-1);
                $(".jb-nwpst-pic-xtra-txt ._text").text(txt);
                $(".jb-tqr-brn-nwp-x-t-ipt").val(txt);
                
                return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_XtraTxtRst = function() {
        try {
            //On masque la bande
            $(".jb-nwpst-pic-xtra-txt").addClass("this_hide");
            
            //On retire le texte de la bande
            $(".jb-nwpst-pic-xtra-txt ._text").text("");
            
            //On retire le texte de l'input
            $(".jb-tqr-brn-nwp-x-t-ipt").val("");
            
            //On retire tout élément de style
            $(".jb-nwpst-pic-xtra-txt").removeAttr("style");
            
            //On reinit le code couleur
            $(".jb-nwpst-pic-xtra-txt")
                .data("clcd","default")
                .attr("data-clcd","default");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /**************************************************************************************************************************************************************************/
    /****************************************************************************** SERVER SCOPE ******************************************************************************/
    /**************************************************************************************************************************************************************************/
    
    //URQ => LOAD MODE REQUEST
    var _nwArtIml;
//    this.newArtInml;
    var _Ax_ChkNwrIml = Kxlib_GetAjaxRules("TMLNR_CHECK_GET_ART_IML");
    var _f_Srv_ChkNwrIml = function() {
//    this.Srv_CheckNewerImlPosts = function() {
        /**
         * Verifier si de nouveaux articles sont disponibles pour la catégorie inMyLIFE
         * Le retour est composé de l'objet Article:
         */
        //On récupère des informations sur le premier Article 
        var fil = ( $(".jb-tmlnr-mdl-std:not([data-isgone='1']):first").length ) ? $(".jb-tmlnr-mdl-std:not([data-isgone='1']):first").data("item") :"";
        
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
                            case "__ERR_VOL_USER_GONE" :
                            case "__ERR_VOL_ACC_GONE" :
                            case "__ERR_VOL_CU_GONE" :
                                    Kxlib_HandleCurrUserGone();
                                break
                            case "__ERR_VOL_DENY":
                                    Kxlib_AJAX_HandleDeny();
                                break
                            case "__ERR_VOL_REF_ART_GONE" :
                                /*
                                     * [NOTE 30-11-14] @author L.C.
                                     *  On ne fait rien. Sinon, on verra une erreur s'afficher alors qu'il n'y a pas vraiement lieu.
                                     *  L'Article sera supprimé tout ou tard. Soit parce que l'utilisateur va reload ou il va exécuter une action qui lui demander de reload.
                                     * [NOTE 31-03-15] @author BlackOwlRobot
                                     *  S'applique pour les autres erreurs inscrite dans ce scope. Etant donnée qu'il s'agit d'une fonctionnalité répétée, il vaut mieux ne rien montrer. 
                                     * [NOTE 16-08-15] @author BOR
                                     *  On tente une nouvelle opération en ayant fait attention de "disqualifier" la publication en mode "ART_GONE"
                                     */
                                    _f_SplCz("__ERR_VOL_ART_GONE",{"adi" : fil, "dir" : "top", "atp" : "inml", "tryag" : 1});
                                break;
                            case "__ERR_VOL_FAILED" :
                                break;
                            default:
                                    /*
                                     * [NOTE 31-03-15] @author BlackOwlRobot
                                     * Etant donnée qu'il s'agit d'une fonctionnalité répétée, il vaut mieux ne rien montrer. 
                                     */
//                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    }
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    /*
                     * [DEPUIS 18-07-15] @BOR
                     *      (1) On vérifie si l'Article n'est pas déjà affichée
                     *      (2) On vérifie si l'image est disponible. Dans ce dernier cas, on arrête tout. On attendra le prochain retour "sain"
                     */
                    var go = false, ok = 0;
                    $.each(datas.return,function(x,ads){
                        if ( KgbLib_CheckNullity(ads) ) {
//                            Kxlib_DebugVars([BTTF : ADSNULL !"]);
                            go = false;
                            return false;
                        }
                        //On vérifie si l'Article est déjà présent
                        if ( $(".jb-tmlnr-mdl-std[data-item='"+ads.id+"']").length ) {
//                            Kxlib_DebugVars([BTTF : EXISTS => ",ads.id,ads.img]);
                            return true;
                        }
                        
                        /*
                         * On vérifie si l'image est disponible.
                         * Cette version prend en compte le cas des publications ayant un mode LCM(TRUE) il n'y a pas d'images mais ce n'est pas une erreur
                         */
                        if ( KgbLib_CheckNullity(ads.img) && ads.LCM === false ) {
//                            Kxlib_DebugVars([BTTF : NO IMAGE => ",ads.id,ads.img]);
                            go = false;
                            return false;
                        }
                        ++ok;
                    });
                    go = ( ok === Kxlib_ObjectChild_Count(datas.return) ) ? true : false;
                    
//                    Kxlib_DebugVars([BTTF : GO => ",go,ok,Kxlib_ObjectChild_Count(datas.return)]);
                    if ( go ) { 
//                        Kxlib_DebugVars([BTTF : PASSED !"]);
                        _nwArtIml = datas.return;
                        _f_ShwNotfIML(datas.return);
                    }
                }
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                /*
                 * [12-12-14] @author L.C.
                 * Retirer car il s'agit principalement d'une fonctionnalité automatique.
                 */
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                return;
            }
        };

        var onerror = function (a,b,c) {
//            alert("AJAX_ERR : "+th.ckeckser_uq);
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            /*
            * [12-12-14] @author L.C.
            * Retirer car il s'agit principalement d'une fonctionnalité automatique.
            */
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            Kxlib_AjaxGblOnErr(a,b);
            return;
        };
                
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_ChkNwrIml.urqid,
            "datas": {
                "fil": fil,
                "curl": curl
            }
        };
        
        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_ChkNwrIml.url, wcrdtl : _Ax_ChkNwrIml.wcrdtl });
    };
    
    
    //URQ => LOAD MODE REQUEST for InTREND
    var _nwArtItr;
//    this.newArtIntr;
    var _Ax_ChkNwrItr = Kxlib_GetAjaxRules("TMLNR_CHECK_GET_ART_INTR");
    var _f_Srv_ChkNwrItr = function() {
//    this.Srv_CheckNewerItrPosts = function() {
        /**
         * Verifier si de nouveaux articles sont disponibles pour la catégorie inTREND
         * Le retour est composé de l'objet Article:
         */
        
        //On récupère des informations sur le dernier Article 
        var fit = ( $(".jb-tmlnr-mdl-intr:not([data-isgone='1']):first").length ) ? $(".jb-tmlnr-mdl-intr:not([data-isgone='1']):first").data("item") : "";
        
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
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break
                            case "__ERR_VOL_DENY":
                                    Kxlib_AJAX_HandleDeny();
                                break
                            case "__ERR_VOL_REF_ART_GONE" :
                                    /*
                                     * [NOTE 30-11-14] @author L.C.
                                     *  On ne fait rien. Sinon, on verra une erreur s'afficher alors qu'il n'y a pas vraiement lieu.
                                     *  L'Article sera supprimé tout ou tard. Soit parce que l'utilisateur va reload ou il va exécuter une action qui lui demander de reload.
                                     * [NOTE 31-03-15] @author BlackOwlRobot
                                     *  S'applique pour les autres erreurs inscrite dans ce scope. Etant donnée qu'il s'agit d'une fonctionnalité répétée, il vaut mieux ne rien montrer. 
                                     * [NOTE 16-08-15] @author BOR
                                     *  On tente une nouvelle opération en ayant fait attention de "disqualifier" la publication en mode "ART_GONE"
                                     */
                                    _f_SplCz("__ERR_VOL_ART_GONE",{"adi" : fit, "dir" : "top", "atp" : "intr", "tryag" : 1});
                                break
                            case "__ERR_VOL_FAILED" :
                                break;
                            default:
                                    /*
                                     * [NOTE 31-03-15] @author BlackOwlRobot
                                     * Etant donnée qu'il s'agit d'une fonctionnalité répétée, il vaut mieux ne rien montrer. 
                                     */
//                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    }
                    return;
                    
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    /*
                     * [DEPUIS 18-07-15] @BOR
                     *      (1) On vérifie si l'Article n'est pas déjà affichée
                     *      (2) On vérifie si l'image est disponible. Dans ce dernier cas, on arrête tout. On attendra le prochain retour "sain"
                     */
                    var go = false, ok = 0;
                    $.each(datas.return,function(x,ads){
                        if ( KgbLib_CheckNullity(ads) ) {
//                            Kxlib_DebugVars([BTTF : ADSNULL !"]);
                            go = false;
                            return false;
                        }
                        //On vérifie si l'Article est déjà présent
                        if ( $(".jb-tmlnr-mdl-intr[data-item='"+ads.id+"']").length ) {
//                            Kxlib_DebugVars([BTTF : EXISTS => ",ads.id,ads.img]);
                            return true;
                        }
                        
                        //On vérifie si l'image est disponible
                        if ( KgbLib_CheckNullity(ads.img) ) {
//                            Kxlib_DebugVars([BTTF : NO IMAGE => ",ads.id,ads.img]);
                            go = false;
                            return false;
                        }
                        ++ok;
                    });
                    go = ( ok === Kxlib_ObjectChild_Count(datas.return) ) ? true : false;
                    
//                    Kxlib_DebugVars([BTTF : GO => ",go,ok,Kxlib_ObjectChild_Count(datas.return)]);
                    if ( go ) { 
//                        Kxlib_DebugVars([BTTF : PASSED !"]);
                        _nwArtItr = datas.return;
                        _f_ShwNotfITR(datas.return);
                    }
                }
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                /*
                 * [12-12-14] @author L.C.
                 * Retirer car il s'agit principalement d'une fonctionnalité automatique.
                 */
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function (a,b,c) {
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            /*
             * [12-12-14] @author L.C.
             * Retirer car il s'agit principalement d'une fonctionnalité automatique.
             */
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            Kxlib_AjaxGblOnErr(a,b);
            return;
        };
                
        var curl = document.URL;        
        var toSend = {
            "urqid": _Ax_ChkNwrItr.urqid,
            "datas": {
                "fit": fit,
                "curl": curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_ChkNwrItr.url, wcrdtl : _Ax_ChkNwrItr.wcrdtl });
    };
    
    //Envoyer l'image et le texte (si existe) au niveau du serveur et récuppérer : nouveau texte, liste de hashtags, image, time, auteur
    
    var _f_Srv_SaveIml = function (ftype,fdata,fdopt,at,x,s) {
//    this.SaveToServNewStdArticleIml = function (at) {
        if ( KgbLib_CheckNullity(ftype) | KgbLib_CheckNullity(fdata) | KgbLib_CheckNullity(at) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) | !Kxlib_GetCurUserPropIfExist() ) {
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            
            //On unlock le bouton
            $(x).data("lk",0);
            //On unlock le bouton Reset
            $(".jb-nwpst-opt-chs[data-action='reset']").data("lk",0);
            //On fait disapparaitre le spinner
            _f_IptWdwSpnr();
            //On masque la fenetre d'attente
            _f_IptWdwWtPan();
            
            return;
        }
        
        //URQ => ADD NEW STD ARTICLE INMYLIFE
        /*
         * On effectue l'appel à ce niveau pour car en mode WLC, la fonction appelée ci-dessous ne sera pas définie
         */
        var _Ax_SaveIml = Kxlib_GetAjaxRules("TMLNR_ADD_IMLART", Kxlib_GetCurUserPropIfExist().upsd);
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else return;
                
               if(! KgbLib_CheckNullity(datas.err) ) {
                   
                    //On unlock le bouton
                    $(x).data("lk",0);
                    //On unlock le bouton Reset
                    $(".jb-nwpst-opt-chs[data-action='reset']").data("lk",0);
                    //On fait disapparaitre le spinner
                    _f_IptWdwSpnr();
                    //On masque la fenetre d'attente
                    _f_IptWdwWtPan();
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break
                            case "__ERR_VOL_ART_GONE":
                                break
                            case "__ERR_VOL_DENY":
                                    Kxlib_AJAX_HandleDeny();
                                break
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    }
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var rds = [datas.return];
                    $(s).trigger("datasready",rds);
                } else {
                    //On unlock le bouton
                    $(x).data("lk",0);
                    //On unlock le bouton Reset
                    $(".jb-nwpst-opt-chs[data-action='reset']").data("lk",0);
                    //On fait disapparaitre le spinner
                    _f_IptWdwSpnr();
                    //On masque la fenetre d'attente
                    _f_IptWdwWtPan();
                    
                    return;
                }
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                
                //On unlock le bouton
                $(x).data("lk",0);
                //On unlock le bouton Reset
                $(".jb-nwpst-opt-chs[data-action='reset']").data("lk",0);
                //On fait disapparaitre le spinner
                _f_IptWdwSpnr();
                //On masque la fenetre d'attente
                _f_IptWdwWtPan();
                
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Send error to SERVER
//            alert("AJAX ERR : "+th.addstdart_uq);
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            
            //On unlock le bouton
            $(x).data("lk",0);
            //On unlock le bouton Reset
            $(".jb-nwpst-opt-chs[data-action='reset']").data("lk",0);
            //On fait disapparaitre le spinner
            _f_IptWdwSpnr();
            //On masque la fenetre d'attente
            _f_IptWdwWtPan();
            
            return;
        };
        
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_SaveIml.urqid,
            "datas": {
                "ftype" : ftype,
                "fdata" : fdata,
//                "fdata" : _nwImg || _nwVid,
                "fdopt" : fdopt,
                /*
                 * [DEPUIS 07-01-16] 
                 */
//                "img"   : _nwImg,
                "fname" : _nwImgNm || _nwVidNm,
                "msg"   : at,
                "curl"  : curl
            }
        };
        
        /*
         * [DEPUIS]
         */
        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_SaveIml.url, wcrdtl : _Ax_SaveIml.wcrdtl });
    };
    
    //Envoyer l'image et le texte au niveau du serveur et récuppérer : nouveau texte, liste de hashtags, image, time, auteur
    var _f_Srv_SaveItr = function (ftype,fdata,fdopt,i,at,x,s) {
//    this.SaveToServNewStdArticleInTr = function (i,at) {
//        alert("DEBUG : In Ajax Save Image -> "+_nwImg);
        if ( KgbLib_CheckNullity(ftype) | KgbLib_CheckNullity(fdata) | KgbLib_CheckNullity(i) | KgbLib_CheckNullity(at) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) | !Kxlib_GetCurUserPropIfExist() ) {
            
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            
            //On unlock le bouton
            $(x).data("lk",0);
            //On unlock le bouton Reset
            $(".jb-nwpst-opt-chs[data-action='reset']").data("lk",0);
            //On fait disapparaitre le spinner
            _f_IptWdwSpnr();
            //On masque la fenetre d'attente
            _f_IptWdwWtPan();
            
            return;
        }
        
         /*
         * On effectue l'appel à ce niveau pour car en mode WLC, la fonction appelée ci-dessous ne sera pas définie
         */
        //URQ => ADD NEW STD ARTICLE TREND
        var _Ax_SaveItr = Kxlib_GetAjaxRules("TMLNR_ADD_TRART", Kxlib_GetCurUserPropIfExist().upsd);
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
               if(! KgbLib_CheckNullity(datas.err) ) {
                   
                   //On unlock le bouton
                    $(x).data("lk",0);
                    //On fait disapparaitre le spinner
                    _f_IptWdwSpnr();
                    //On masque la fenetre d'attente
                    _f_IptWdwWtPan();
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break
                            case "__ERR_VOL_ART_GONE":
                                break
                            case "__ERR_VOL_DENY":
                                    Kxlib_AJAX_HandleDeny();
                                break
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    }
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var rds = [datas.return];
                    $(s).trigger("datasready",rds);
                } else {
                    //On unlock le bouton
                    $(x).data("lk",0);
                    //On unlock le bouton Reset
                    $(".jb-nwpst-opt-chs[data-action='reset']").data("lk",0);
                    //On fait disapparaitre le spinner
                    _f_IptWdwSpnr();
                    //On masque la fenetre d'attente
                    _f_IptWdwWtPan();
                    
                    return;
                }
                
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                
                //On unlock le bouton
                $(x).data("lk",0);
                //On unlock le bouton Reset
                $(".jb-nwpst-opt-chs[data-action='reset']").data("lk",0);
                //On fait disapparaitre le spinner
                _f_IptWdwSpnr();
                //On masque la fenetre d'attente
                _f_IptWdwWtPan();
                
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
            
            //On unlock le bouton
            $(x).data("lk",0);
            //On unlock le bouton Reset
            $(".jb-nwpst-opt-chs[data-action='reset']").data("lk",0);
            //On fait disapparaitre le spinner
            _f_IptWdwSpnr();
            //On masque la fenetre d'attente
            _f_IptWdwWtPan();
            
            return;
        };
        
        var tle = $(".jb-nwpst-tr-tle").text();
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_SaveItr.urqid,
            "datas": {
                "ftype"     : ftype,
                /*
                 * [DEPUIS 08-01-15]
                 */
//                "img"       : _nwImg, 
                "fdata"     : fdata,
                "fdopt"     : fdopt,
                "fname"     : _nwImgNm || _nwVidNm,
                "trid"      : i,
                "trtitle"   : tle,
                "msg"       : at,
                "curl"      : curl
            }
        };
        
        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_SaveItr.url, wcrdtl : _Ax_SaveItr.wcrdtl });
    };
    
    var _Ax_CheckGetPdArt = Kxlib_GetAjaxRules("TMLNR_CHECK_GET_PDART");
    var _f_Srv_CheckGetPdArt = function (x,lil,lit,hmt,s) {
//    this._f_Srv_CheckGetPdArt = function (lil,lit) {
        /*
         * Il est autorisé d'envoyer des références NULL. Cela permet au serveur de décider l'envoi des Articles les plus récents
         */
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
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
                    /*
                     * On masque le spinner
                     * [NOTE 04-05-15] @BOR
                     * On ne masque plus le spinner pour montrer qu'il y a une erreur et pour forcer l'utilisateur à recharger la page
                     */
//                    _f_PdArtWdwSpnr();
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            /*
                             * [DEPUIS 09-11-15] @author BOR
                             */
                            case "__ERR_VOL_DNY_AKX_AUTH":
                                    if ( $(".jb-tqr-btm-lock").length ) {
                                        $(".jb-tqr-btm-lock").removeClass("this_hide");
                                        $(".jb-tqr-btm-lock-fd").removeClass("this_hide");
                                        
                                        /*
                                         * ETAPE :
                                         *      On ferme UNQ
                                         */
                                        $(".jb-unq-close-trg").click();
                                        
                                        /*
                                         * [DEPUIS 28-04-16]
                                         */
                                        $(x).data("lk",0);
                                        _f_PdArtWdwSpnr();
                                    }
                                break;
                            default:
//                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    /*
                     * [DEPUIS 17-08-15] @author BOR
                     *  L'objectif des instructions ci-dessous est de changer l'état de la publication qui sert de référence puis de réessayer avec d'autres éléments si possible.
                     */
                    if ( ( !KgbLib_CheckNullity(datas.return.iml) && datas.return.iml === "__ERR_VOL_REF_ART_GONE" ) | ( !KgbLib_CheckNullity(datas.return.itr) && datas.return.itr === "__ERR_VOL_REF_ART_GONE" ) ) {
                        if ( ( !KgbLib_CheckNullity(datas.return.iml) && datas.return.iml === "__ERR_VOL_REF_ART_GONE" ) && !( !KgbLib_CheckNullity(datas.return.itr) && datas.return.itr === "__ERR_VOL_REF_ART_GONE" ) ) {
                            var x1__ = _f_SplCz("__ERR_VOL_REF_ART_GONE",{"adi" : lil, "dir" : "btm", "atp" : "inml", "tryag" : 1});
                            /*
                             * On vérifie s'il y a bel et bien un référence disponible pour retenter l'opération
                             */
                            if ( x1__ === 0 ) {
                                /*
                                 * [TODO 17-08-15] @BOR
                                 *  Renseigner l'utilisateur qu'il y a un problème avec les publications et qu'il devrait recharger le page pour régler le problème.
                                 *  Pour l'instant on laisse le spinner tourner pour forcer l'utilisateur a recharger la page.
                                 */
                            }
                        } else if ( !( !KgbLib_CheckNullity(datas.return.iml) && datas.return.iml === "__ERR_VOL_REF_ART_GONE" ) && ( !KgbLib_CheckNullity(datas.return.itr) && datas.return.itr === "__ERR_VOL_REF_ART_GONE" ) ) {
                            var x1__ = _f_SplCz("__ERR_VOL_REF_ART_GONE",{"adi": lit, "dir": "btm", "atp": "intr", "tryag": 1});
                            /*
                             * On vérifie s'il y a bel et bien un référence disponible pour retenter l'opération
                             */
                            if ( x1__ === 0 ) {
                                /*
                                 * [TODO 17-08-15] @BOR
                                 *  Renseigner l'utilisateur qu'il y a un problème avec les publications et qu'il devrait recharger le page pour régler le problème.
                                 *  Pour l'instant on laisse le spinner tourner pour forcer l'utilisateur a recharger la page.
                                 */
                            }
                        } else {
                            _f_SplCz("__ERR_VOL_REF_ART_GONE",{"adi": lil, "dir": "btm", "atp": "inml", "tryag": 0});
                            var x1__ = _f_SplCz("__ERR_VOL_REF_ART_GONE",{
                                adi   : lit, 
                                dir   : "btm", 
                                atp   : "intr", 
                                tryag : 1
                            });
                            /*
                             * On vérifie s'il y a bel et bien un référence disponible pour retenter l'opération
                             */
                            if ( x1__ === 0 ) {
                                /*
                                 * On essaie de lancer le processus via la partie IML.
                                 */
                                x1__ = _f_SplCz("__ERR_VOL_REF_ART_GONE",{"adi": lil, "dir": "btm", "atp": "inml", "tryag": 1});
                                if ( x1__ === 0 ) {
                                   /*
                                    * [TODO 17-08-15] @BOR
                                    *  Renseigner l'utilisateur qu'il y a un problème avec les publications et qu'il devrait recharger le page pour régler le problème.
                                    *  Pour l'instant on laisse le spinner tourner pour forcer l'utilisateur a recharger la page.
                                    */
                                }
                            }
                        }
                    } else {
                        var rds = [datas.return];
                        $(s).trigger("operended",rds);
                    }
                } else {
                    //On masque le spinner
                    _f_PdArtWdwSpnr();
                    
                    //On débloque le bouton
//                    $(x).data("lk",0); //[DEPUIS 01-06-15] @BOR
                    var m__ = Kxlib_getDolphinsValue("ART_NOONE_GEN_PAGE");
                    $(".jb-tmlnr-loadm-trg").text(m__);
                    $(".jb-tmlnr-loadm-trg").addClass("EOP");
                    
                    /*
                     * [DEPUIS 10-07-15] @BOR
                     *      (1) On masque le message qui demande de patienter.
                     *      (2) On vérifie si UNQ est ouvert et qu'il s'agit du derner Article.
                     *      Dans ce cas, on masque la flèche NEXT.
                     */
                    $(".jb-unq-nav-btn[data-dir='next']").find(".jb-unq-nav-btn-wait").addClass("this_hide");
                    var sl = (! $(".jb-unq-art-mdl").data("item") && $(".jb-unq-art-mdl").data("item").replace(/\[.*,(.*)\]/g,"$1") ) ? null : $(".jb-unq-art-mdl").data("item").replace(/\[.*,(.*)\]/g,"$1");
                    if ( $(".jb-unq-art-mdl").hasClass("active") && sl && $(sl) && $(sl).length && $(".jb-unq-bind-art-mdl").last().data("item") === $(sl).data("item") ) {
                        $(".jb-unq-nav-btn[data-dir='next']").addClass("this_hide");
                    }
                    
                    return;
                }
            } catch (ex) {
//                alert("DEBUG AJAX => "+e.message);
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
  
               /*
                * On masque le spinner
                * [NOTE 04-05-15] @BOR
                * On ne masque plus le spinner pour montrer qu'il y a une erreur et pour forcer l'utilisateur à recharger la page
                */
//                _f_PdArtWdwSpnr();

//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;        
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            
            //On masque le spinner
            _f_PdArtWdwSpnr();
            
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_CheckGetPdArt.urqid,
            "datas": {
                "lil"   : lil,
                "lit"   : lit,
                "hmt"   : hmt,
                "curl"  : curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_CheckGetPdArt.url, wcrdtl : _Ax_CheckGetPdArt.wcrdtl });
    };
    
    var _f_DisplayPdArt = function(d,x) {
//    this.DisplayPdArt = function(d) {
        /* PROCESS : Permet d'afficher les PreDate Articles */
        
        try {
            if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(x) ) {
                return;
            }
            
            /*
             * [DEPUIS 10-07-15] @BOR
             */
            $(".jb-unq-nav-btn[data-dir='next']").find(".jb-unq-nav-btn-wait").addClass("this_hide");
            
            /* 
             * On parcoure les deux tableaux s'ils existent 
             * */
            var tnb = Kxlib_ObjectChild_Count(d.itr), mnb = Kxlib_ObjectChild_Count(d.iml);
            if ( KgbLib_CheckNullity(mnb) && KgbLib_CheckNullity(tnb) ) {
                //On masque le spinner
                _f_PdArtWdwSpnr();
                
                /*
                 * [DEPUIS 01-06-15] @BOR
                 */
                var m__ = Kxlib_getDolphinsValue("ART_NOONE_GEN_PAGE");
                $(".jb-tmlnr-loadm-trg").text(m__);
                $(".jb-tmlnr-loadm-trg").addClass("EOP");
                
                /*
                 * [DEPUIS 10-07-15] @BOR
                 * On vérifie si UNQ est ouvert et qu'il s'agit du derner Article.
                 * Dans ce cas, on masque la flèche NEXT.
                 */
                var sl = (! $(".jb-unq-art-mdl").data("item") && $(".jb-unq-art-mdl").data("item").replace(/\[.*,(.*)\]/g,"$1") ) ? null : $(".jb-unq-art-mdl").data("item").replace(/\[.*,(.*)\]/g,"$1");
                if ( $(".jb-unq-art-mdl").hasClass("active") && sl && $(sl) && $(sl).length && $(".jb-unq-bind-art-mdl").last().data("item") === $(sl).data("item") ) {
                    $(".jb-unq-nav-btn[data-dir='next']").addClass("this_hide");
                }
                        
                return;
            }
            
            //Si une valeur est null on la ramène à 0 pour permettre un traitement en une seule fois sans traitement supp.
            if ( KgbLib_CheckNullity(mnb) ) {
                mnb = 0;
            }
            if ( KgbLib_CheckNullity(tnb) ) {
                tnb = 0;
                /*
                 * [DEPUIS 10-07-15] @BOR
                 * On vérifie si UNQ est ouvert et qu'il s'agit du derner Article.
                 * Dans ce cas, on masque la flèche NEXT.
                 */
                var sl = (! $(".jb-unq-art-mdl").data("item") && $(".jb-unq-art-mdl").data("item").replace(/\[.*,(.*)\]/g,"$1") ) ? null : $(".jb-unq-art-mdl").data("item").replace(/\[.*,(.*)\]/g,"$1");
                if ( $(".jb-unq-art-mdl").hasClass("active") && sl && $(sl) && $(sl).length && $(".jb-unq-bind-art-mdl").last().data("item") === $(sl).data("item") ) {
                    $(".jb-unq-nav-btn[data-dir='next']").addClass("this_hide");
                }
            }
//        Kxlib_DebugVars([mnb,tnb],true);
            
            //On recupère la longueur superieur pour être sure de n'oublier aucun article
            //[NOTE (RAPPEL) 25-06-14] Si les deux sont agaux, alors on prends un des deux. Aussi, la ligne suivant est adéquate
            var max = ( mnb < tnb ) ? tnb : mnb;    
            
            //On va créer les Articles. Cette méthode permet d'éviter de traiter cas par cas
            for (var i = 0; i < max; i++) {
//                try {
                    
                    if ( mnb !== 0 && !KgbLib_CheckNullity(d.iml[i]) ) { 
                        _f_CrtNewStdArtImlVw(d.iml[i], true);
                    }
                    
                    if (tnb !== 0 && !KgbLib_CheckNullity(d.itr[i])) {
                        _f_CrtNewStdArtIntrVw(d.itr[i], true);
                    }
                  /*  
                } catch (e) {
                    //On masque le spinner
                    _f_PdArtWdwSpnr();
//                alert("DEBUG => "+e.message);
                }
            //*/
            }
            
            //On masque le spinner
            _f_PdArtWdwSpnr();
            
            /*
             [NOTE 25-06-14] J'ai abondonné la solution car on peut faire sans, sans dégrader l'expérience utilisateur
             //On descend vers le deuxième élément d'une des deux colonnes tant que des éléments ont été ajouté
             if ( mnb !== 0 )
             $(window).scrollTop($(".jb-tmlnr-mdl-std").first().next().offset().top);
             else 
             $(window).scrollTop($(".jb-tmlnr-mdl-intr").first().next().offset().top);
             //*/
            
            /*
             * [DEPUIS 09-11-15] @author BOR
             *      On ajoute l'identifiant de l'article pour HMT (HowManyTimes).
             *      L'opération consiste à concaténer l'identifiant reçu à celui existant s'il existe.
             */
            var hmx;
            hmx = ( !$(".jb-tqr-hmt").length ) ? $("<span/>",{ class : "jb-tqr-hmt" }) : $(".jb-tqr-hmt");
            var hmt = $(".jb-tqr-hmt").data("hmt");
            hmt = ( hmt ) ? hmt.concat(",",d.hmti) : d.hmti;
            $(".jb-tqr-hmt").data("hmt",hmt);
//            Kxlib_DebugVars(["NPOST_FILE",1856,hmt,$(".jb-tqr-hmt").data("hmt")],true);
            
            //On débloque le bouton
            $(x).data("lk", 0);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /************************************************************************************************************************************************************************/
    /****************************************************************************** VIEW SCOPE ******************************************************************************/
    /************************************************************************************************************************************************************************/
    
    var _f_SwhImlMode = function(decl, m) {
//    this.GoInMylifeMode = function(decl, m) {
        _f_Gdf().nPost_Mode = m;
        
        //On modifie la hauteur du bloc
//        $("#new_post-ml_bot").removeClass("new_post-ml_bot_sp_celeb");
        //On reinit la taille du top
//        $("#new_post-ml_top").removeClass("new_post-ml_top_sptr");
        //On cache le formulaire Celeb
//        $("#npost_celeb_form_max").addClass("this_hide");
        //On affiche le déclencheur pour switcher avec Celeb
//        $("#addToCelebs_Max").removeClass("this_hide");
        //On change le mode du déclencheur
//        if ( $(decl).attr("id") !== "start_npostMl_process" )
//            $(decl).data("mode","fclb");
        //On reinit top, le cachant
//        $(".jb-nwpst-tr-top-mx").addClass("this_hide");
        /* 
         
        //[NOTE : 23-06-14] Ajout de cette section. Si à la date de la lecture elle est toujours commentée, elle ne sert donc à rien.
        $("#start_npostTr_process").data("title",$(a).data("title"));
        $("#start_npostTr_process").data("trid",$(a).data("trid"));
        $("#start_npostTr_process").data("isown",$(a).data("isown"));
        //*/
        
//        alert("DEBUG => "+$("#brain_th-new_ml").html());
        
        //On affiche le textarea
        $(".jb-nwpst-txt").removeClass("this_hide");
        //On affiche le compteur
        $(".jb-nwpst-opr-chr").removeClass("this_hide");
              
        /* Permet de s'assurer que l'on va enregistrer l'image dans la section inMyLIFE */
        $("#start_npostTr_process").data("title","");
        $("#start_npostTr_process").data("trid","");
        $("#start_npostTr_process").data("isown","");

        //On retire les données dans la fenetre d'ajout dans BRAIN
        $(".jb-nwpst-tr-top-mx").addClass("this_hide");
        $(".jb-nwpst-tr-top-mx").data("trid","");
        $(".jb-nwpst-tr-top-mx").data("isown","");
                    
        
    };
    
    var _f_SwhItrMode = function(o, m) {
//    this.GoInTrendMode = function(o, m) {
        _f_Gdf().nPost_Mode = m;
        
        //On modifie la hauteur du bloc
//        $("#new_post-ml_bot").removeClass("new_post-ml_bot_sp_celeb");
        //On cache le formulaire Celeb
//        $("#npost_celeb_form_max").addClass("this_hide");
        //On affiche le textarea
        $(".jb-nwpst-txt").removeClass("this_hide");
        //On affiche le compteur
        $(".jb-nwpst-opr-chr").removeClass("this_hide");
        
        //On retire le trigger pour déclencher "Celeb"
//        $("#addToCelebs_Max").addClass("this_hide");
        //On show le top
        $(".jb-nwpst-tr-top-mx").removeClass("this_hide");
        var title = $(o).data("title");
            //On insere le titre
            $(".jb-nwpst-tr-tle").text(title);
            $(".jb-nwpst-tr-tle").attr("title",title);
        //On augmente la taille du top
        $("#new_post-ml_top").addClass("new_post-ml_top_sptr");
        //On ajoute l'id de la Tendance pour permettre au serveur de savoir si elle appartient ...
        //... ou pas à la session en cours
        $(".jb-nwpst-tr-top-mx").data('trid',$(o).data("trid"));
        
        //On ajoute 'isown' pour permetre au gestionnaire d'ajout de savoir où rediriger SLAVE après l'ajout
        var idd = $(o).data("isown");
        
        if ( !KgbLib_CheckNullity(idd) && idd === "brain_list_mytrs" )
            $(".jb-nwpst-tr-top-mx").data("isown","1");
        else 
            $(".jb-nwpst-tr-top-mx").data("isown","0");
        
        //On replace les anciens éléments pour permettre une annulation
        $("#start_npostTr_process").data("title",$(o).data("title"));
        $("#start_npostTr_process").data("trid",$(o).data("trid"));
        $("#start_npostTr_process").data("isown",$(".jb-nwpst-tr-top-mx").data("isown"));
                
    };
    /*
    this.GoForCelebMode = function(decl, m) {
        _f_Gdf().nPost_Mode = m;
        
        //On modifie la hauteur du bloc
        $("#new_post-ml_bot").addClass("new_post-ml_bot_sp_celeb");
        //On reinit la taille du top
        $("#new_post-ml_top").removeClass("new_post-ml_top_sptr");
        //On cache le formulaire Celeb
        $("#npost_celeb_form_max").removeClass("this_hide");
        //On affiche le textarea
        $(".jb-nwpst-txt").addClass("this_hide");
        //On affiche le compteur
        $(".jb-nwpst-opr-chr").addClass("this_hide");
        //On change le mode du déclencheur
        $(decl).data("mode","inml");
        //On reinit top, le cachant
        $(".jb-nwpst-tr-top-mx").addClass("this_hide");
    };
    */
   
    var _f_SwWdw = function(decl, m) {
//    this.SwitchInsertorWindow = function(decl, m) {
        switch (m) {
            case "inml": 
                    /* Si la fenetre n'était pas prete auparavent pour un ajout InTREND on laisse tomber.
                     * Cette décision n'est pas technique mais fonctionnelle. Elle est faite pour offir à l'utilisateur
                     * la meilleure expérience. 
                     * */
                    if (! _f_NwPstImlRdy() ) {
                        _f_RstNwPstWdw();
                    } 
                    _f_SwhImlMode(decl,m);
                break;
            case "intr": 
                    var O = $(decl).clone(true,true);
                    _f_RstNwPstWdw();
                    _f_SwhItrMode(O,m);
                break;
                /*
            case "fclb": 
                    this.GoForCelebMode(decl,"fclb");
                break;
                */
            default: 
                //Lancer error
                break;
        }
    };
    
    var _f_PdArtWdwSpnr = function (sw) {
        
        if ( KgbLib_CheckNullity(sw) ) {
            $(".jb-tmlnr-loadm-trg").removeClass("this_hide");
            $(".jb-nwfd-loadm-spnr").addClass("this_hide");
        } else {
            $(".jb-tmlnr-loadm-trg").addClass("this_hide");
            $(".jb-nwfd-loadm-spnr").removeClass("this_hide");
        }
    };
    
    var _f_IptWdwSpnr = function (sw) {
        
        if ( KgbLib_CheckNullity(sw) ) {
            $(".jb-nwpst-trg-spnr").addClass("this_hide");
            if ( $(".jb-nwpst-opt-chs[data-action='post']") && $(".jb-nwpst-opt-chs[data-action='post']").length ) {
                $(".jb-nwpst-opt-chs[data-action='post']").stop(true,true).animate({
                    "padding-left" : "14px"
                }, 200);
            }
        } else {
            if ( $(".jb-nwpst-opt-chs[data-action='post']") && $(".jb-nwpst-opt-chs[data-action='post']").length ) {
                $(".jb-nwpst-opt-chs[data-action='post']").stop(true,true).animate({
                    "padding-left" : "30px"
                }, 200, function() {
                    $(".jb-nwpst-trg-spnr").removeClass("this_hide");
                });
            } else {
                $(".jb-nwpst-trg-spnr").removeClass("this_hide");
            }
        }
    };
    
    var _f_IptWdwWtPan = function (sw) {
        
        if ( KgbLib_CheckNullity(sw) ) {
            $(".jb-nwpst-wt-pan-mx").addClass("this_hide");
        } else {
            $(".jb-nwpst-wt-pan-mx").removeClass("this_hide");
        }
    };
    
    var _f_PprNPMl = function(o) {
//    this.HandlePrepareNPMl = function(o) {
        _f_SwWdw(o,$(o).data("mode"));
    };
    /*
    this.HandlePrepareNPCeleb = function(o){
        //On change de mode pour afficher le formulaire
        var _m = $(o).data("mode");
        
        _f_SwWdw(o,_m);
    };
    */
    var _f_PprNPTr = function(o) {
//    this.HandlePrepareNPTr = function(o) {
        _f_SwWdw(o,$(o).data("mode"));
    };
    
    var _f_Init = function(arg) {
//    this.Init = function(arg) {
        _f_ChkNPMode(arg, null);
    };
    
    
    var _f_PprImlArt = function (d) {
//    this.PrepareImlArt = function (d) {
        /*
         * RAPPEL sur le Format de l'Objet
         *   id: Le numéro d'identification de l'élément
         *   ufn: Le nom complet de l'auteur [Texte],
         *   upsd: Le pseudo de l'auteur [Texte],
         *   time: Le temps correspondant à l'heure d'ajout au niveau du serveur [Texte],
         *   img: Le lien vers l'image [Texte],
         *   //Le message lié à la photo. Le message n'est affiché que sur le modèle ARP. Ici il sert à améliorer le SEO
         *   msg: Le message lié à l'imae (si elle existe) [Texte],
         *   eval: (0)Le nombre d'eval +2; (1)Le nombre d'eval +1; (2)Le nombre d'eval -1; (3)Le nombre total d'appréciations [Texte] 
         *   react: Le nombre total de commentaires [Texte] 
         *   hashs : [h1,h2] //Liste des hashtags à afficher [Tableau avec au moins un élément]
         */
        try {
            
            d = Kxlib_ReplaceIfUndefined(d);
            
            /*
             * [DEPUIS 24-04-15] @BOR
             * On vérifie la relation pour savoir si on est dans un cas où il ne faudrait affiché qu'un modèle sommaire
             */
            if ( d.hasOwnProperty("LCM") && KgbLib_CheckNullity(d.LCM) | d.LCM === true) {
//                console.log(JSON.stringify(d));
                var e = "<article id=\"post-accp-myl-id" + d.id + "\" class=\"feeded_com_bloc_figs jb-tmlnr-mdl-std\" data-item=\""+d.id+"\" >";
                e += "<div class=\"post-solo-in-acclist\">";
                /*
                 * [DEPUIS 04-01-16]
                 */
                /*
                e += "<div class=\"fcb_top\">";
                e += "<div class=\"fcb_intop_time\">";
                e += "</div>";
                e += "<div class='fcb_intop_left'>";
                e += "<span class=\"fcb_intop_in\">in</span>";
                e += "<span class=\"fcb_intop_wa\">MyLIFE</span>";
                e += "</div>";
                e += "</div>";
                //*/
                e += "<div class=\"fcb_img_maximus lock\" title=\"Vous devez être ami avec "+d.upsd+" (@"+d.ufn+") pour accéder à ces photos\">";
                e += "<div class=\"iml-lock-bgrd\">";
                e += "<div class=\"iml-lock-bgrd-wpr\">";
                e += "<div>";
                e += "<img class=\"iml-lock-img\" src=\""+Kxlib_GetExtFileURL("sys_url_img","r/frd-ctr-w.png")+"\" height=\"15\" />";
//                e += "<img class=\"iml-lock-img\" src=\"http://timg.ycgkit.com/files/img/r/frd-ctr-w.png\" height=\"15\"/>";
                e += "</div>";
                e += "<div>";
                e += "<span class=\"iml-lock-txt\">Privé</span>";
                e += "</div>";
                e += "</div>";
                e += "</div>";
                e += "</div>";
                e += "<div class=\"sp_iml_bot\"></div>";
                e += "</div>";
                e += "</article>";
                e = $.parseHTML(e);
                
                return e;
                
            }
            
            var me = Kxlib_ReplaceIfUndefined(d.myel);
            
            if (! KgbLib_CheckNullity(me) ) {
                me = Kxlib_ValidMyEval(me);
            }
            
//        Kxlib_DebugVars([d.id,d.upsd,d.ufn,d.trd_eid],true);
//        return;
            
            var eval_lt0 = (!KgbLib_CheckNullity(d.eval_lt)) ? Kxlib_ReplaceIfUndefined(d.eval_lt[0]) : ""; 
            var eval_lt1 = (!KgbLib_CheckNullity(d.eval_lt)) ? Kxlib_ReplaceIfUndefined(d.eval_lt[1]) : ""; 
            var eval_lt2 = (!KgbLib_CheckNullity(d.eval_lt)) ? Kxlib_ReplaceIfUndefined(d.eval_lt[2]) : ""; 
            var eval_lt3 = (!KgbLib_CheckNullity(d.eval_lt)) ? Kxlib_ReplaceIfUndefined(d.eval_lt[3]) : ""; 
            
            var str__;
            if (d.hasOwnProperty("ustgs") && d.ustgs !== undefined && typeof d.ustgs === "object") {
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
            
            var e = "<article id=\"post-accp-myl-id" + d.id + "\" class=\"feeded_com_bloc_figs jb-tmlnr-mdl-std jb-tqr-fav-bind-arml\" data-item=\"" + d.id + "\" data-psd=\"" + d.upsd + "\" data-fn=\"" + d.ufn + "\" data-atype=\"iml\" ";
            e += " data-cache=\"['" + d.id + "','" + d.img + "','" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(d.msg)) + "','','','" + d.rnb + "','','" + d.prmlk + "'],['" + d.time + "','" + Kxlib_ReplaceIfUndefined(d.utc) + "'],['" + d.eval[0] + "','" + d.eval[1] + "','" + d.eval[2] + "','" + d.eval[3] + "','" + Kxlib_ReplaceIfUndefined(eval_lt0) + "','" + Kxlib_ReplaceIfUndefined(eval_lt1) + "','" + Kxlib_ReplaceIfUndefined(eval_lt2) + "','" + Kxlib_ReplaceIfUndefined(eval_lt3) + "'],['" + d.ueid + "','" + d.ufn + "','" + d.upsd + "','" + d.uppic + "','" + d.uhref + "'],['" + me + "']\" ";
            e += " data-with=\"" + Kxlib_ReplaceIfUndefined(str__) + "\" ";
            e += " data-pml=\"" + d.prmlk + "\" ";
            e += " data-vidu=\"" + Kxlib_ReplaceIfUndefined(d.vidu) + "\" ";
            e += " >";
            e += "<div class=\"arp-spnr-bmx\">";
            e += "<span class=\"arp-spnr-mx\"><i class=\"fa fa-refresh fa-spin\"></i></span>";
            e += "</div>";
            e += "<div class=\"post-solo-in-acclist\">";
            
            /*
             * [DEPUIS 04-01-16]
             */
            e += "<div class=\"fcb_top\">";
            e += "<div class=\"tqr-art-isod-lg\" data-atype=\"onpg_tmlnr\">Photo du jour</div>";
            e += "</div>";
            
            /*
             * [DEPUIS 04-01-16]
             */
            /*
            e += "<div class=\"fcb_top\">";
            e += "<div class=\"fcb_intop_time\">";
            e += "<span class=\'kxlib_tgspy\' data-tgs-crd=\'" + d.time + "\' data-tgs-dd-atn=\'\' data-tgs-dd-uut=\'\'>";
            e += "<span class=\'tgs-frm\'></span>";
            e += "<span class=\'tgs-val\'></span>";
            e += "<span class=\'tgs-uni\'></span>";
            e += "</span>";
            e += "</div>";
            e += "<div id=\"tqr-art-actbar-mx\" class=\"jb-tqr-art-abr-mx\" data-scp=\"am-tmlnr-iml\">";
            e += "<ul id=\"tqr-art-actbar-lst-mx\">";
            e += "<li class=\"tqr-art-actbar-l-elt\">";
            e += "<a class=\"tqr-art-actbar-tgr jb-tqr-art-abr-tgr\" data-css=\"favorite\" data-action=\"favorite\" data-state=\"\" title=\"Mettre en favoris\"></a>";
            e += "<div id=\"tqr-art-actbar-fav-chcs\" class=\"jb-tqr-art-abr-fav-chs this_hide\">";
            e += "<a class=\"tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch\" data-css=\"fav_public\" data-action=\"fav_public\">Privé</a>";
            e += "<a class=\"tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch\" data-css=\"fav_private\" data-action=\"fav_private\">Public</a>";
            e += "</div>";
            e += "</li>";
            e += "<li class=\"tqr-art-actbar-l-elt\"><a class=\"tqr-art-actbar-tgr jb-tqr-art-abr-tgr\" data-css=\"download\" data-action=\"download\" title=\"Télécharger la photo\"></a></li>";
            e += "<li class=\"tqr-art-actbar-l-elt\"><a class=\"tqr-art-actbar-tgr jb-tqr-art-abr-tgr\" data-css=\"report\" data-action=\"report\" title=\"Signaler la publication\"></a></li>";
            e += "</ul>";
            e += "</div>";
            e += "<div class=\'fcb_intop_left\'>";
            e += "<span class=\"fcb_intop_in\">in</span>";
            e += "<span class=\"fcb_intop_wa\">MyLIFE</span>";
            e += "</div>";
            e += "</div>";
            //*/
            e += "<div class=\"fcb_img_maximus\" data-target=\"post-accp-myl-id" + d.id + "\">"; 
            e += "<div class=\"fcb_img\">";
//            e += "<img class=\"fcb_img_img\" height=\"370\" width=\"371\" src=\"" + d.img + "\" alt=\"" + Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(d.msg)) + "\"/>";
            e += "<img class=\"fcb_img_img\" height=\"370\" width=\"371\" src=\"\" alt=\"\"/>";
            e += "</div>";
            e += "<span class=\"soft_fade\">";
            
            e += "<div class=\"tqr-artml-tmonhvr jb-tqr-artml-onhvr-tm this_hide\" data-atype=\"tmlnr\"></div>";
            
            e += "<div class=\"tqr-artmdl-asdxtr-box jb-tqr-am-ax-box this_hide\">";
            e += "<a class=\"tqr-artmdl-shron-tgr cursor-pointer jb-tqr-art-abr-tgr\" data-art-mdl=\"on_page\" data-action=\"favorite\" data-reva=\"unfavorite\" data-revt=\"Retirer des favoris\" title=\"Mettre en favori\"></a>";
            e += "<span class=\"tqr-artmdl-xtras-sep\"></span>";
            e += "<a class=\"tqr-artmdl-shron-tgr cursor-pointer jb-tqr-artmdl-shron-tgr \" data-art-mdl=\"on_page\" data-action=\"amdl_sharon_fb\" title=\"Partager sur Facebook\"></a>";
            e += "<a class=\"tqr-artmdl-shron-tgr cursor-pointer jb-tqr-artmdl-shron-tgr \" data-art-mdl=\"on_page\" data-action=\"amdl_sharon_twr\" title=\"Partager sur Twitter\"></a>";
            e += "</div>";
            
            e += "<div class=\"tqr-art-actbar-fav-bmx jb-tqr-art-abr-fav-bmx this_hide\">";
            e += "<div class=\"tqr-art-actbar-fav-chcs\">";
            e += "<a class=\"tqr-art-actbar-fav-ccl cursor-pointer jb-tqr-art-actbar-fav-ccl\" data-art-mdl=\"on_page\" data-action=\"fav_cancel\" title=\"Annuler\" role=\"button\"></a>";
            e += "<a class=\"tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch\" data-art-mdl=\"on_page\" data-css=\"fav_public\" data-action=\"fav_public\">Public</a>";
            e += "<a class=\"tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch\" data-art-mdl=\"on_page\" data-css=\"fav_private\" data-action=\"fav_private\">Privé</a>";
            e += "</div>";
            e += "</div>";
            
            e += "</span>";
            e += "<div class=\"tmlnr_bot_fade\">";
            e += "<span class=\"b_f_com b_f_react\">";
            e += "<span class=\"jb_b_f_rlib b_f_rLib\" style=\"background: url('"+ Kxlib_GetExtFileURL("sys_url_img","r/r3.png",["_WITH_ROOTABS_OPTION"]) +"') no-repeat;\"></span>";
            e += "<span class=\"jb_b_f_rnb b_f_rNb\">" + d.rnb + "</span>";
            e += "</span>";
            e += "<span class=\"b_f_com b_f_eval\">";
            e += "<span class=\"jb_b_f_enb b_f_eNb jb-csam-eval-oput\" data-cache=\"[" + d.eval[0] + "," + d.eval[1] + "," + d.eval[2] + "," + d.eval[3] + "," + d.myel + "]\">";
            e += "<span>" + d.eval[3] + "</span>";
//            e += "<span>" + d.eval[3] + "</span>&nbsp;c<i>!</i>";
            e += "</span>";
            e += "</span>";
            e += "</div>";
            e += "</div>";
            e += "<div class=\"sp_iml_bot\">";
            e += "<div class=\'botm_listHtgs\'>";
            e += "<span class=\"botm_a_desc this_hide\" data-d=\"" + d.msg + "\">" + d.msg + "</span>";
            if (!KgbLib_CheckNullity(d.hashs)) {
                $.each(d.hashs, function(k,v) {
                    if (! KgbLib_CheckNullity(v) ) {
                        e += "<a class='botm_listHtg' href=\'/hview/q="+v+"&src=hash\'><i>#</i>" + v + "</a>";
//                        e += "<a class='botm_listHtg' href=\'/search?q="+v+"\'><i>#</i>"+v+"</a>";
                    }
                });
            }
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e += "</article>";
            e = $.parseHTML(e);
            
            /*
             * [DEPUIS 25-04-16]
             */
            var hasfv = ( d.hasfv ) ? 1 : 0;
            $(e)
                .data("time",d.time).attr("data-time",d.time)
                .data("hasfv",hasfv).attr("data-hasfv",hasfv)
                //[DEPUIS 30-04-16]
                .data("trq-ver",'ajca-v10').attr("data-trq-ver",'ajca-v10')
                .data("ajcache",JSON.stringify(d)).attr("data-ajcache",JSON.stringify(d));
            
            /*
             * [DEPUIS 27-04-16]
             *      On gère le cas où l'ARTICLE n'est pas distribué en mode RESTRICTED
             */
            if (! ( d.hasOwnProperty("isrtd") && d.isrtd ) ) {
                $(e).find(".jb-tqr-art-abr-tgr").addClass("jb-irr").removeClass("jb-tqr-art-abr-tgr");
            }
            
            /*
             * [DEPUIS 02-06-16]
             *      On ajoute une chaine représentant la DATE qui va s'afficher au niveau de l'ARTICLE
             */
            var adate = new Date(parseFloat(d.time));
            var foo = ("0"+adate.getDate().toString()).slice(-2).concat(".");
            foo += ("0"+adate.getMonth().toString()).slice(-2).concat(".");
            foo += adate.getFullYear().toString().substr(2,2);
            $(e).find(".jb-tqr-artml-onhvr-tm").text(foo);
             
            /*
             * ETAPE :
             *      On gère le cas de VIDU au niveau du filtre
             */
            if ( d.hasfv ) {
                var a = $(e).find(".jb-tqr-art-abr-tgr").data("action"), t = $(e).find(".jb-tqr-art-abr-tgr").attr("title");
                var ra = $(e).find(".jb-tqr-art-abr-tgr").data("reva"), rt = $(e).find(".jb-tqr-art-abr-tgr").data("revt");
                $(e).find(".jb-tqr-art-abr-tgr")
//                    .data("action",ra)
//                    .data("reva",a)
//                    .data("revt",t)
                    .data({
                        "action"    : ra,
                        "reva"      : a,
                        "revt"      : t
                    })
                    .attr({
                        "data-action"   : ra,
                        "data-reva"     : a,
                        "title"         : rt,
                        "data-revt"     : t
                    });
            }
            
            /*
             * [DEPUIS 05-05-16]
             *      Permet de régler un problème d'encodage qui suscitait un bogue.
             *      La nouvelle version marche mieux et permettra d'améliorer le SEO (j'espère)
             *      ATTENTION : Ce n'est pas la même chose avec le modème TMLNR_ITR
             */
            $(e).find(".fcb_img_img").attr({
                src :   d.img,
                alt : $("<div/>").html(d.msg).text()
            }); 
            
            /*
             * ETAPE :
             *      On gère le cas de VIDU au niveau du filtre
             */
            if ( d.vidu ) {
                $(e).find(".soft_fade").addClass("vidu");
            }
            
            /*
             * ETAPE :
             *      On gère le cas de SOD au niveau du filtre.
             *      IL NE FAUT PAS AFFICHER LES BOUTONS SHARE S'IL NE S'AGIT PAS D'UN IML_SOD
             */
            if (! d.isod ) {
                $(e).find(".tqr-artmdl-xtras-sep").remove();
                $(e).find(".jb-tqr-artmdl-shron-tgr").remove();
                $(e).find(".tqr-art-isod-lg").remove();
            }
            
            
            return e;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true); 
        }

    };
    
    //Créer un article InMyLIFE sans la partie ARP
    var _f_CrtNewStdArtImlVw = function (d,pd) {
//    this.CreateNewStdArticleImlView = function (d,pd) {
        try {
            
            //On s'assure que l'Article n'est pas déjà présent dans la liste
            if ( $(".jb-tmlnr-mdl-std[data-item='" + d.id + "']").length ) {
                return;
            }
            
            /*
             * [DEPUIS 19-09-15] @author BOR
             *  On retire le NoOne de la section
             */
            $(".jb-tmlnr-noone-sec-bmx[data-target='w-list']").fadeOut().addClass("this_hide");
            
            //On crée le modele
            var e = _f_PprImlArt(d);
            
            //On rebind le listener
            e = _f_NewArt_BindHdlr(e);
            
            if ( pd === true ) {
                $(e).hide().appendTo("#feeded_w_list_list");
            } else {
                $(e).hide().prependTo("#feeded_w_list_list");
            }
            
            /*
             * [DEPUIS 27-04-16] @author BOR
             *      On BIND pour FAV
             */
            TqOn("RBD_FR_FV",e);
            
            /*
             * [DEPUIS 18-07-15] @BOR
             * On masque None
             */
            $(".jb-whub-mx").addClass("this_hide");
            
            var Tg = new TIMEGOD();
            Tg.UpdSpies();
            $(e).fadeIn();
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_NewArt_BindHdlr = function (b,it) {
//    this._NewArt_BindHandler = function (b,it) {
        //it = IsTrend, permet de binder le déclencheur au bon Handler car la méthode est appelée pour TrArt et StdArt.
        try {
            
            
            if ( it ) {
                //On bind le click sur l'image vers UNQ
                /*
                 * [08-12-14] @author L.C.
                 * .off() pour solutionner le bogue du double listernes avec Unique.csam.js
                 */
                $(b).find(".fcb_img_maximus").off("click").click(function(e){
    //                Kxlib_DebugVars(["750","OnOpen Listener"],true);
                    Kxlib_StopPropagation(e);
                    Kxlib_PreventDefault(e);

                    (new Unique()).OnOpen("TMLNR",this);
                });

            } else {
                $(b).find(".fcb_img_maximus").click(function(e){
                    //alert($(this).html());
    //                Kxlib_DebugVars(["758","OnOpen Lister"],true);
                     Kxlib_PreventDefault(e);

                    var id = Kxlib_ValidIdSel($(this).data("target"));
                    var _O = new ARP_HNDLR();

                    /*
                     * Si l'article n'a pas la partie ARP c'est qu'il a été ajouté dynamiquement.
                     * Il faut donc créer la partie ARP.
                    */
        //            alert("ID => "+$(id).has(".arp-solo-in-acclist").length );
                    if (! $(id).has(".arp-solo-in-acclist").length ) { 
                        _O.HandleCreateArp($(id));
                    }

                    _O.PlaceArtDesc($(id));

                    $(id).addClass("sp_inmylide_figs");
                    $(id).find(".post-solo-in-acclist").addClass("this_hide");
                    $(id).find(".arp-solo-in-acclist").removeClass("this_hide");
                    //$(this).parent().parent().find(".post-solo-in-acclist").addClass("this_hide");
                    //$(this).parent().parent().find(".arp-solo-in-acclist").removeClass("this_hide");

                    //On write le texte dans la zone réservée dans le modèle ARP

                });
            }
            
            /*
             * [DEPUIS 27-04-16]
             */
            $(b).find(".fcb_img_maximus .jb-irr").off("click").click(function(e){
                Kxlib_StopPropagation(e);
                Kxlib_PreventDefault(e);
            });
            

            /*
            $(b).find(".fcb_img_maximus").off("hover").hover(function(e){
//                console.log("NWPOST.JS : 2908");
               /*
                * [DEPUIS 24-11-15] @author BOR
                *      Meilleur gestion du cas HOVER.
                *      Cette manière de faire prend aussi en compte le cas des navigateurs du type FIREFOX
                *
                var ihvr = false; //[DEPUIS 27-04-16]
                if ( $(this).filter(":hover").length ) {
                    ihvr = true;
                    $(this).children("span").removeClass("soft_fade").addClass("hard_fade");
                } else {
                    $(this).children("span").addClass("soft_fade").removeClass("hard_fade");
                }
                /*
                $(this).children("span").toggleClass("soft_fade");
                $(this).children("span").toggleClass("hard_fade");
                $(this).children(".bot_fade").toggleClass("bot_fade_sp");
                //*/
                
                /*
                 * [DEPUIS 22-11-15] @author BOR
                 *      On gère le cas des bouton de partage sur les réseaux sociaux.
                 *
                Kxlib_DebugVars(["NWPOST.JS : 2949",$(this).find(".jb-tqr-am-ax-box").length,$(this).find(".jb-tqr-am-ax-box").hasClass("this_hide"),ihvr]);
//                if ( $(this).has(".jb-tqr-artmdl-shron-tgr").length ) {
//                    if ( $(this).find(".jb-tqr-artmdl-shron-tgr").first().hasClass("this_hide") ) {
                if ( $(this).find(".jb-tqr-am-ax-box").length ) {
                    if ( $(this).find(".jb-tqr-am-ax-box").hasClass("this_hide") && ihvr ) {
//                        $(this).find(".jb-tqr-artmdl-fav-tgr").removeClass("this_hide");
//                        $(this).find(".jb-tqr-artmdl-shron-tgr").removeClass("this_hide");
                        
                        $(this).find(".jb-tqr-am-ax-box").removeClass("this_hide");
                    } else {
//                        $(this).find(".jb-tqr-artmdl-fav-tgr").addClass("this_hide");
//                        $(this).find(".jb-tqr-artmdl-shron-tgr").addClass("this_hide");
                        
                        $(this).find(".jb-tqr-am-ax-box").addClass("this_hide");
                    }
                }
            });
            //*/

            $(b).find(".fcb_img_maximus").off("hover").hover(function(e){
                /*
                 * [DEPUIS 24-11-15] @author BOR
                 *      Meilleur gestion du cas HOVER.
                 *      Cette manière de faire prend aussi en compte le cas des navigateurs du type FIREFOX
                 */
                $(this).children("span").removeClass("soft_fade").addClass("hard_fade");

//                Kxlib_DebugVars(["NWPOST.JS : 2977",$(this).find(".jb-tqr-am-ax-box").length,$(this).find(".jb-tqr-am-ax-box").hasClass("this_hide")]);
                /*
                 * [DEPUIS 02-06-16]
                 */
                if ( $(this).find(".jb-tqr-artml-onhvr-tm").length ) {
                    $(this).find(".jb-tqr-artml-onhvr-tm").removeClass("this_hide");
                }
                if ( $(this).find(".jb-tqr-am-ax-box").length ) {
                    $(this).find(".jb-tqr-am-ax-box").removeClass("this_hide");
                }
            },function(e){
                $(this).children("span").addClass("soft_fade").removeClass("hard_fade");

//                Kxlib_DebugVars(["NWPOST.JS : 2984",$(this).find(".jb-tqr-am-ax-box").length,$(this).find(".jb-tqr-am-ax-box").hasClass("this_hide")]);
                /*
                 * [DEPUIS 02-06-16]
                 */
                if ( $(this).find(".jb-tqr-artml-onhvr-tm").length ) {
                    $(this).find(".jb-tqr-artml-onhvr-tm").addClass("this_hide");
                }
                if ( $(this).find(".jb-tqr-am-ax-box").length ) {
                    $(this).find(".jb-tqr-am-ax-box").addClass("this_hide");
                }
            });
            
            /*
             * [DEPUIS 22-11-15] @author BOR
             */
            $(b).find(".jb-tqr-artmdl-shron-tgr").off("click").click(function(e){
                Kxlib_PreventDefault(e);
                Kxlib_StopPropagation(e);
                
                _f_Sharon(this);
            });
             
            return b;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_PprItrArt = function (d) {
//    this.PrepareInTrArt = function (d) {
        /*
         * RAPPEL sur le Format de l'Objet
            id: Le numéro d'identification de l'élement
            trd_eid: L'id de la Tendance
            trtitle: Le titre lié à la Tendance
            trhref: Le lien de la Tendance
            ueid: L'identifiant externe du propriétaire de l'Article
            ufn: Le nom complet de l'auteur [Texte],
            upsd: Le pseudo de l'auteur [Texte],
            time: Le temps correspondant à l'heure d'ajout au niveau du serveur [Texte],
            img: Le lien vers l'image [Texte],
            //Le message lié à la photo. Le message n'est affiché que sur le modèle ARP. Ici il sert à améliorer le SEO
            msg: Le message lié à l'imae (si elle existe) [Texte],
            eval: (0)Le nombre d'eval +2; (1)Le nombre d'eval +1; (2)Le nombre d'eval -1; (3)Le nombre total d'appréciations [Texte] 
            myel: L'évaluation de l'utilisateur courant sur l'Article si elle existe
            rnb: Le nombre total de commentaires [Texte] 
            hashs : [h1,h2] //Liste des hashtags à afficher [Tableau avec au moins un élément]
         */
        try {
            
            //On remplace toutes les valeurs de type null par ""
            d = Kxlib_ReplaceIfUndefined(d);
            
//        "["+d.id+","+d.img+","+d.msg+""+d.tr+","+d.trtitle+"],["+d.time+","+d.utc+"],["+d.eval[0]+","+d.eval[1]+","+d.eval[2]+","+d.eval[3]+"],["+d.ueid+","+d.ufn+","+d.upsd+","+d.uppic+"]"
            //Certaines valeurs sont transformées pour garantir la fiabilité de la séparation des champs.
            //Pour d'autres cela n'est pas necessaire car certains caractères sont interdits en entrées
//        alert(Kxlib_EscapeComa(d.eval[3]));
//        alert("AVANT => "+d.msg);
//        alert("APRES => "+Kxlib_EscapeForDataCache(d.msg));
//        alert("FINAL => "+"["+d.id+","+d.img+","+Kxlib_EscapeForDataCache(d.msg)+","+d.tr+","+Kxlib_EscapeForDataCache(d.trtitle)+"]");
            //me = Myeval : correspond à l'évaluation que j'ai attribué à l'Article. Celle doit respecter des règles strictes, d'où l'utilisation d'une fonction de la bibliothèque KXLIB
            
            var me = Kxlib_ReplaceIfUndefined(d.myel);
            
            if (!KgbLib_CheckNullity(me)) {
                me = Kxlib_ValidMyEval(me);
            }
            
//        Kxlib_DebugVars([d.id,d.upsd,d.ufn,d.trd_eid],true);
//        return;
            
            var eval_lt0 = (!KgbLib_CheckNullity(d.eval_lt)) ? Kxlib_ReplaceIfUndefined(d.eval_lt[0]) : ""; 
            var eval_lt1 = (!KgbLib_CheckNullity(d.eval_lt)) ? Kxlib_ReplaceIfUndefined(d.eval_lt[1]) : ""; 
            var eval_lt2 = (!KgbLib_CheckNullity(d.eval_lt)) ? Kxlib_ReplaceIfUndefined(d.eval_lt[2]) : ""; 
            var eval_lt3 = (!KgbLib_CheckNullity(d.eval_lt)) ? Kxlib_ReplaceIfUndefined(d.eval_lt[3]) : ""; 
            
            var str__, desc;
            if ( d.hasOwnProperty("ustgs") && d.ustgs !== undefined && typeof d.ustgs === "object" ) {
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
                
                /*
                 * On transforme le texte pour qu'il soit bien encodé
                 */
                desc = Kxlib_Decode_After_Encode(d.msg);
            } else {
                desc = Kxlib_ReplaceIfUndefined(d.msg);
            }
            
            /*
             * On utilise pas Kxlib_ReplaceIfUndefined() (pour les propriétés non définies) car la probabilité d'avoir des données entrantes NULL est ici relativement faible. 
             * * */ 
            var e = "<article id=\"post-accp-tr-id" + d.id + "\" class=\"feeded_com_bloc_figs sp_intr_figs jb-tmlnr-mdl-intr jb-unq-bind-art-mdl jb-tqr-fav-bind-arml\" data-item=\"" + d.id + "\" data-psd=\"" + d.upsd + "\" data-fn=\"" + d.ufn + "\" data-tr=\"" + d.tr + "\" data-atype=\"itr\" ";
            e += " data-cache=\"['" + d.id + "','" + d.img + "','{adesc}','" + d.tr + "','{trtle}','" + d.rnb + "','" + d.trhref + "','" + d.prmlk + "'],['" + d.time + "','" + Kxlib_ReplaceIfUndefined(d.utc) + "'],['" + d.eval[0] + "','" + d.eval[1] + "','" + d.eval[2] + "','" + d.eval[3] + "','" + Kxlib_ReplaceIfUndefined(eval_lt0) + "','" + Kxlib_ReplaceIfUndefined(eval_lt1) + "','" + Kxlib_ReplaceIfUndefined(eval_lt2) + "','" + Kxlib_ReplaceIfUndefined(eval_lt3) + "'],['" + d.ueid + "','" + d.ufn + "','" + d.upsd + "','" + d.uppic + "','" + d.uhref + "'],['" + me + "']\" ";
//        e += " data-cache=\"['"+d.id+"','"+d.img+"','"+Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(d.msg))+"','"+d.tr+"','"+d.trtitle+"','"+d.rnb+"','"+d.trhref+"','"+d.prmlk+"'],['"+d.time+"','"+Kxlib_ReplaceIfUndefined(d.utc)+"'],['"+d.eval[0]+"','"+d.eval[1]+"','"+d.eval[2]+"','"+d.eval[3]+"','"+Kxlib_ReplaceIfUndefined(eval_lt0)+"','"+Kxlib_ReplaceIfUndefined(eval_lt1)+"','"+Kxlib_ReplaceIfUndefined(eval_lt2)+"','"+Kxlib_ReplaceIfUndefined(eval_lt3)+"'],['"+d.ueid+"','"+d.ufn+"','"+d.upsd+"','"+d.uppic+"','"+d.uhref+"'],['"+me+"']\" ";
//        e += " data-cache=\"['"+d.id+"','"+d.img+"','"+Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(d.msg))+"','"+d.tr+"','"+Kxlib_EscapeForDataCache(d.trtitle)+"','"+d.rnb+"','"+d.trhref+"','"+d.prmlk+"'],['"+d.time+"','"+Kxlib_ReplaceIfUndefined(d.utc)+"'],['"+d.eval[0]+"','"+d.eval[1]+"','"+d.eval[2]+"','"+d.eval[3]+"','"+Kxlib_ReplaceIfUndefined(eval_lt0)+"','"+Kxlib_ReplaceIfUndefined(eval_lt1)+"','"+Kxlib_ReplaceIfUndefined(eval_lt2)+"','"+Kxlib_ReplaceIfUndefined(eval_lt3)+"'],['"+d.ueid+"','"+d.ufn+"','"+d.upsd+"','"+d.uppic+"','"+d.uhref+"'],['"+me+"']\" >";
            e += " data-with=\"" + Kxlib_ReplaceIfUndefined(str__) + "\" ";
            e += " data-vidu=\"" + Kxlib_ReplaceIfUndefined(d.vidu) + "\" ";
            e += " >";
            e += "<div class=\"jb-tqr-cldstrg this_hide\">";
            e += "<span class=\"jb-tqr-csg-elt\" data-item='adsc'>" + desc + "</span>";
            e += "<span class=\"jb-tqr-csg-elt\" data-item='trtle'>" + d.trtitle + "</span>";
            e += "</div>";
            /*
             * [DEPUIS 04-01-16]
             */
            /*
            e += "<div class=\"fcb_top\">";
            e += "<div class=\"fcb_intop_time\">";
            e += "<span class=\'kxlib_tgspy\' data-tgs-crd=\'" + d.time + "\' data-tgs-dd-atn=\'\' data-tgs-dd-uut=\'\'>";
            e += "<span class=\'tgs-frm\'></span>";
            e += "<span class=\'tgs-val\'></span>";
            e += "<span class=\'tgs-uni\'></span>";
            e += "</span>";
            e += "</div>";
            e += "<div id=\"tqr-art-actbar-mx\" class=\"jb-tqr-art-abr-mx\" data-scp=\"am-tmlnr-itr\">";
            e += "<ul id=\"tqr-art-actbar-lst-mx\">";
            e += "<li class=\"tqr-art-actbar-l-elt\">";
            e += "<a class=\"tqr-art-actbar-tgr jb-tqr-art-abr-tgr\" data-css=\"favorite\" data-action=\"favorite\" data-state=\"\" title=\"Mettre en favoris\"></a>";
            e += "<div id=\"tqr-art-actbar-fav-chcs\" class=\"jb-tqr-art-abr-fav-chs this_hide\">";
            e += "<a class=\"tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch\" data-css=\"fav_public\" data-action=\"fav_public\">Privé</a>";
            e += "<a class=\"tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch\" data-css=\"fav_private\" data-action=\"fav_private\">Public</a>";
            e += "</div>";
            e += "</li>";
            e += "<li class=\"tqr-art-actbar-l-elt\"><a class=\"tqr-art-actbar-tgr jb-tqr-art-abr-tgr\" data-css=\"download\" data-action=\"download\" title=\"Télécharger la photo\"></a></li>";
            e += "<li class=\"tqr-art-actbar-l-elt\"><a class=\"tqr-art-actbar-tgr jb-tqr-art-abr-tgr\" data-css=\"report\" data-action=\"report\" title=\"Signaler la publication\"></a></li>";
            e += "</ul>";
            e += "</div>";
            e += "<div class=\'fcb_intop_left\'>";
            e += "<span class=\"fcb_intop_in\">in</span>";
            e += "<span class=\"fcb_intop_wa\">TREND</span>";
            e += "</div>";
            e += "</div>";
            //*/
            e += "<div class=\"fcb_img_maximus\">";
            e += "<div class=\"fcb_img\">";
//            e += "<img class=\"fcb_img_img\" height=\"370\" width=\"371\" src=\"" + d.img + "\" alt=\""+Kxlib_Decode_After_Encode(desc)+"" + desc + "\"/>";
            e += "<img class=\"fcb_img_img\" height=\"370\" width=\"371\" src=\"\" alt=\"\"/>";
            e += "</div>";
            e += "<span class=\"soft_fade\">";
            
            e += "<div class=\"tqr-artml-tmonhvr jb-tqr-artml-onhvr-tm this_hide\" data-atype=\"tmlnr\"></div>";
           
            e += "<div class=\"tqr-artmdl-asdxtr-box jb-tqr-am-ax-box this_hide\">";
            e += "<a class=\"tqr-artmdl-shron-tgr cursor-pointer jb-tqr-art-abr-tgr\" data-art-mdl=\"on_page\" data-action=\"favorite\" data-reva=\"unfavorite\" data-revt=\"Retirer des favoris\" title=\"Mettre en favori\"></a>";
            e += "<span class=\"tqr-artmdl-xtras-sep\"></span>";
            e += "<a class=\"tqr-artmdl-shron-tgr cursor-pointer jb-tqr-artmdl-shron-tgr \" data-art-mdl=\"on_page\" data-action=\"amdl_sharon_fb\" title=\"Partager sur Facebook\"></a>";
            e += "<a class=\"tqr-artmdl-shron-tgr cursor-pointer jb-tqr-artmdl-shron-tgr \" data-art-mdl=\"on_page\" data-action=\"amdl_sharon_twr\" title=\"Partager sur Twitter\"></a>";
            e += "</div>";
            
            e += "<div class=\"tqr-art-actbar-fav-bmx jb-tqr-art-abr-fav-bmx this_hide\">";
            e += "<div class=\"tqr-art-actbar-fav-chcs\">";
            e += "<a class=\"tqr-art-actbar-fav-ccl cursor-pointer jb-tqr-art-actbar-fav-ccl\" data-art-mdl=\"on_page\" data-action=\"fav_cancel\" title=\"Annuler\" role=\"button\"></a>";
            e += "<a class=\"tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch\" data-art-mdl=\"on_page\" data-css=\"fav_public\" data-action=\"fav_public\">Public</a>";
            e += "<a class=\"tqr-art-actbar-fav-chc jb-tqr-art-abr-fav-ch\" data-art-mdl=\"on_page\" data-css=\"fav_private\" data-action=\"fav_private\">Privé</a>";
            e += "</div>";
            e += "</div>";
            
            e += "</span>";
            e += "<div class=\"tmlnr_bot_fade\">";
            e += "<span class=\"b_f_com b_f_react\">";
            e += "<span class=\"jb_b_f_rlib b_f_rLib\" style=\"background: url('"+ Kxlib_GetExtFileURL("sys_url_img","r/r3.png",["_WITH_ROOTABS_OPTION"]) +"') no-repeat;\"></span>";
            e += "<span class=\"jb_b_f_rnb b_f_rNb jb-unq-react\">" + d.rnb + "</span>";
            e += "</span>";
            e += "<span class=\"b_f_com b_f_eval\">";
            e += "<span class=\"jb_b_f_enb b_f_eNb jb-csam-eval-oput\" data-cache=\"[" + d.eval[0] + "," + d.eval[1] + "," + d.eval[2] + "," + d.eval[3] + "," + me + "]\">";
            e += "<span class=\"jb_b_f_elib b_f_eLib\">" + d.eval[3] + "</span>";
//            e += "<span class=\"jb_b_f_elib b_f_eLib\">" + d.eval[3] + "&nbsp;</span>c<i>!</i>";
            e += "</span>";
            e += "</div>";
            e += "</div>";
            e += "<div class=\"fcb_bottom\">";
            e += "<p class=\"fcb_b_title overflow_txt\" data-tr=\"" + d.tr + "\" title=\"" + d.trtitle + "\">";
            e += "<a class=\"fcb_b_ttl_lk\" href=\"" + d.trhref + "\">";
            e += d.trtitle;
            e += "</a>";
            e += "</p>";
            e += "<span class=\"botm_a_desc this_hide\" data-d=\"" + d.msg + "\">" + d.msg + "</span>";
            e += "<div class=\"botm_listHtgs\">";
            if (!KgbLib_CheckNullity(d.hashs)) {
                $.each(d.hashs, function(k, v) {
                    if (!KgbLib_CheckNullity(v)) {
                        e += "<a class='botm_listHtg' href=\'/hview/q="+v+"&src=hash\'><i>#</i>" + v + "</a>";
//                    e += "<a class='botm_listHtg' href=\'/search?q="+v+"\'><i>#</i>"+v+"</a>";
                    }
                });
            }
            e += "</div>";
            e += "</div>";
            e += "</article>";
            e = $.parseHTML(e);
            
            /*
             * [DEPUIS 25-04-16]
             */
            var hasfv = ( d.hasfv === true ) ? 1 : 0;
            $(e)
                .data("time",d.time).attr("data-time",d.time)
                .data("hasfv",hasfv).attr("data-hasfv",hasfv)
                //[DEPUIS 30-04-16]
                .data("trq-ver",'ajca-v10').attr("data-trq-ver",'ajca-v10')
                .data("ajcache",JSON.stringify(d)).attr("data-ajcache",JSON.stringify(d));
        
                
            /*
             * [DEPUIS 02-06-16]
             *      On ajoute une chaine représentant la DATE qui va s'afficher au niveau de l'ARTICLE
             */
            var adate = new Date(parseFloat(d.time));
            var foo = ("0"+adate.getDate().toString()).slice(-2).concat(".");
            foo += ("0"+adate.getMonth().toString()).slice(-2).concat(".");
            foo += adate.getFullYear().toString().substr(2,2);
            $(e).find(".jb-tqr-artml-onhvr-tm").text(foo);
            
            
            /*
             * [DEPUIS 27-04-16]
             *      On gère le cas où l'ARTICLE n'est pas distribué en mode RESTRICTED
             */
            if (! ( d.hasOwnProperty("isrtd") && d.isrtd ) ) {
                $(e).find(".jb-tqr-art-abr-tgr").addClass("jb-irr").removeClass("jb-tqr-art-abr-tgr");
            }
            
            
            /*
             * ETAPE :
             *      On gère le cas de VIDU au niveau du filtre
             */
            if ( d.hasfv ) {
                var a = $(e).find(".jb-tqr-art-abr-tgr").data("action"), t = $(e).find(".jb-tqr-art-abr-tgr").attr("title");
                var ra = $(e).find(".jb-tqr-art-abr-tgr").data("reva"), rt = $(e).find(".jb-tqr-art-abr-tgr").data("revt");
                $(e).find(".jb-tqr-art-abr-tgr")
//                    .data("action",ra)
//                    .data("reva",a)
//                    .data("reva",t)
                    .data({
                        "action"    : ra,
                        "reva"      : a,
                        "revt"      : t
                    })
                    .attr({
                        "data-action"   : ra,
                        "data-reva"     : a,
                        "title"         : rt,
                        "data-revt"     : t
                    });
            }
            
            /*
             * [DEPUIS 05-05-16]
             *      Permet de régler un problème d'encodage qui suscitait un bogue.
             *      La nouvelle version marche mieux et permettra d'améliorer le SEO (j'espère)
             */
            $(e).find(".fcb_img_img").attr({
                src :   d.img,
                alt : $("<div/>").html(Kxlib_Decode_After_Encode(d.msg)).text()
            });
            
            if ( d.vidu ) {
                $(e).find(".soft_fade").addClass("vidu");
            }
            
            return e;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };

    
    //Créer un article InTREND sans la partie ARP
   var _f_CrtNewStdArtIntrVw = function (d,pd) {
//    this.CreateNewStdArticleIntrView = function (d,pd) {
        try {
            
            //On s'assure que l'Article n'est pas déjà présent dans la liste
            if ($(".jb-tmlnr-mdl-intr[data-item='" + d.id + "']").length) {
                return;
            }
            
            /*
             * [DEPUIS 19-09-15] @author BOR
             *  On retire le NoOne de la section
             */
            $(".jb-tmlnr-noone-sec-bmx[data-target='e-list']").fadeOut().addClass("this_hide");
            
            var e = _f_PprItrArt(d);
            e = _f_NewArt_BindHdlr(e,true);
            
            if ( pd === true ) {
                $(e).hide().appendTo("#feeded_e_list_list");
            } else {
                $(e).hide().prependTo("#feeded_e_list_list");
            }
            
            /*
             * [DEPUIS 27-04-16] @author BOR
             *      On BIND pour FAV
             */
            TqOn("RBD_FR_FV",e);
            
            /*
             * [DEPUIS 18-07-15] @BOR
             * On masque None
             */
            $(".jb-whub-mx").addClass("this_hide");
            
            var Tg = new TIMEGOD();
            Tg.UpdSpies();
            
            $(e).fadeIn();
            
//        alert("MESSAGE FROM SERVER => "+ob.msg);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*******************************************************************************************************************************************************************************/
    /******************************************************************************** LISTENERS SCOPE ******************************************************************************/
    /*******************************************************************************************************************************************************************************/
    
    //Pour demarrer la fenetre d'erreur des le depart, passer "err" en paramètre
    _f_Init("skip"); //skip, err, ...
    
    $(".jb-nwpst-txt").on("focus blur",function(){
//        Kxlib_DebugVars([FOCUS+BLUR => ",$(this).val().length]);
        if ( $(this).hasClass("error_field") ) {
            $(this).addClass("no_outline");
        } else {
            $(this).removeClass("no_outline");
        }
//        _f_NwPst_CnDsc($(this));
//        _f_NwPst_CnDsc($(_f_Gdf().nPost_txtId));
    });
    
    $(".jb-nwpst-clz").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_ClzErrWdw();
    });
    
    $(".jb-nwpst-opt-chs[data-action='post']").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_NwPost(this);
    });
    
    $(".jb-nwpst-opt-chs[data-action='reset']").click(function(e){
        Kxlib_PreventDefault(e);
        
//        alert("Start Cancelling !");
        _f_CclInWin(this);
        //Obj.();
    });
    
    var cnsl_dragleave = function () {
        console.log("dragleave");
    };
    var cnsl_dragover = function () {
        console.log("dragover");
    };
    var cnsl_drop = function () {
        console.log("drop");
    };
    var cnsl_dragend = function () {
        console.log("dragend");
    };
    
    /*
     * [DEPUIS 17-10-15] 
     *  Permet de gérer de manière plus intelligente le cas de drag en travaillant sur un seul élément du DOM.
     */
    $.fn.draghover = function(options) {
        
        return this.each(function() {

            var collection = $(), self = $(this);
            
            self.on('dragenter', function(e) {
              if (collection.length === 0) {
                self.trigger('draghoverstart',[e.target]);
              }
              collection = collection.add(e.target);
            });

            self.on('dragleave drop', function(e) {
              collection = collection.not(e.target);
              if (collection.length === 0) {
                self.trigger('draghoverend',[e.target]);
              }
            });
        
        });
    };

    try {
        /*
         * [NOTE 11-06-16]
         *      Cette section a été modifiée pour corriger un BOGUE en ce qui concerne le fonctionnement de la possibilité d'ajouter en mode DROP & CLICK.
         *      Le résultat obtenu a été l'aboutissement d'un travail empirique de R&D.
         *      En cas de nouveau BOGUES, s'aider des "console.log()"
         */
        window.firstEnter = 0;
        $(window).draghover().on({
            'draghoverstart': function(e,tgt) {
                
                var navob = Kxlib_NvgtrSayWho();
                window.firstEnter = ( window.firstEnter === 0 ) ? 1 : 2;
                
//                console.log("BOOM : Je rentre !",window.firstEnter,"NODE_TYPE => ",$(tgt).get(0).firstChild.nodeType,tgt);
                if ( navob && navob.name ==="Firefox" && window.firstEnter === 1 && !$(".jb-nwpst-pls").is(tgt) ) {
//                    console.log("BOOM : DROP HERE !");
                    _f_DropWkUp();
                } 
                else if ( navob.name !== "Firefox" ) {
//                    console.log("BOOM : DROP HERE (NOT FFX) !");
                    _f_DropWkUp();
                }

            },
            'draghoverend': function(e,tgt) { 
                
    //            console.log("BOOM : Je sors !",window.firstEnter,"NODE_TYPE => ",$(tgt).get(0).firstChild.nodeType);
                var navob = Kxlib_NvgtrSayWho();
                if ( navob && typeof navob === "object" && navob.name === "Firefox" ) {
//                console.log("BOOM : CHECK CHECK !",tgt);
                    if ( $(tgt).get(0).firstChild.nodeType === 3 ) {
//                        console.log("BOOM : NE BOUGE PAS !",$(tgt).get(0).firstChild.nodeType,tgt);
                         window.firstEnter = 1;
                    } else {
//                        console.log("BOOM : Je sors !",window.firstEnter,"NODE_TYPE => ",$(tgt).get(0).firstChild.nodeType);
        //                if ( !$(".jb-tmlnr-nwpst-iml-i-wpr").has(tgt).length && !$(_f_Gdf().npostImgId).children("img").length ) {
                        if ( !$(_f_Gdf().npostImgId).children("img").length ) {
    //                        window.firstEnter = ( $(".jb-nwpst-pls").is(tgt) ) ? 3 : 0;
                            window.firstEnter = ( $("div[s-id]").has(tgt) && !$(tgt).is("html") ) ? 3 : 0;

//                            console.log("BOOM : CHECK BEFORE RESET !",$(tgt).attr("id"),$(tgt).is("html"),tgt);
                            if ( window.firstEnter === 0 ) {
//                                console.log("BOOM : GOOOOOOOOOOO RESET !",$(tgt).attr("id"),$(tgt).is("html"),tgt);
                                _f_Rst_ImgWind($(".jb-nwpst-ab-pic"));
                            }

                        } else {
//                            console.log("BOOM : PAS DE RESET !");
                        }
                    }
                }
                else {
//                    console.log("BOOM : GOOOOOOOOOOO RESET (NOT FFX) !",$(tgt).attr("id"),$(tgt).is("html"),tgt);
                    _f_Rst_ImgWind($(".jb-nwpst-ab-pic"));
                }
            },
            dragover : function (e) {
                Kxlib_StopPropagation(e);
                Kxlib_PreventDefault(e);
                
                if ( $(e.target).is(".jb-nwpst-pls, .jb-nwpst-pls > *") ) {
//                    console.log("BOOM (DRAG_OVER) : ",e.target);
                    _f_PprDrop(e);
                }
            },
            drop : function (e) {
//                console.log("BOOM (DROP) : ",e.target);
                Kxlib_StopPropagation(e);
                Kxlib_PreventDefault(e);
            
                if ( $(e.target).is(".jb-nwpst-pls, .jb-nwpst-pls > *") ) {
                    _f_Drop(e);
                }
            }
        });
    } catch (ex) {
//        Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        akert(ex);
    }
    
    
    /*
    //$(".jb-nwpst-xpln").on({
    $(window).bind({
        dragenter   : _f_DropWkUp.bind(gt),
//        dragenter: _f_DropWkUp.bind(this),
//        dragenter: _f_DropWkUp.bind(Obj),
        dragleave   : false,
        dragover    : false,
        drop        : false
    });
    //*/
    
    //*/
    
//    $("body").on("dragenter",_f_DropWkUp.bind(Obj));

    /*
     * [DEPUIS 12-06-16]
     *      Le DROP & CLICK est désormais géré par un seule et même HANDLER pour éviter les cconflits et une quantité incroyable de BOGUES
     */
    /*
    //Si on n'annule pas les events "enter" et "leave" ça ne marchera pas, notamment sur IE
    $('.jb-nwpst-pls').off("dragover, drop, dragleave").on({
        dragenter   : false,
//        dragenter   : true,
//        dragleave   : false,
        dragleave   : function(){
            console.log("BOOM : VODKA !")
        },
//        dragover: _f_PprDrop.bind(Obj),
//        dragover    : _f_PprDrop.bind(gt),
        dragover    : function(e) {
            _f_PprDrop(e);
        },
        drop        : _f_Drop.bind(gt)
//        drop: _f_Drop.bind(Obj)
    });
    //*/
    
    /*
    $('#newp_plus').on({
        dragenter: false,
        dragleave: false,
        dragover: _f_PprDrop.bind(this),
//        dragover: _f_PprDrop.bind(Obj),
        drop: _f_Drop.bind(this)
//        drop: _f_Drop.bind(Obj)
    });
    //*/
                                                                                                                                                                                                                                
    //var dropZone = document.getElementById('newp_plus');
    //Bind pour cibler les methodes et non eventListener
    //dropZone.addEventListener('dragover', _f_PprDrop.bind(Obj), false);
    //dropZone.addEventListener('drop', _f_Drop.bind(Obj), false);
    
    $("#kgb_click_inputfile").change(function(){
        var files = this.files;
        
        //[NOTE 04-08-14] On est passé de this.file à this.value
        _f_TrtIptFls(files,this.value);
    });
    
    /*
     * [DEPUIS 05-12-15]
     */
    $(".jb-tqr-skycrpr-snit[data-target='brain']").on("change",function(e,file,name,coor){
         
//        Kxlib_DebugVars([name, file.width, file.height],true);
        _f_crtThbnl(file,name,coor);
    });
    
    $(".jb-nwpst-xpln").click(function(e){
        Kxlib_PreventDefault(e);
        
       $("#kgb_click_inputfile").click();
       //alert('click');
       //Kxlib_DebugVars([Clique sur DROP or CLICK"]);
    });
    
    $(".jb-nwpst-xpln").hover(function(){
        $(this).toggleClass("newp_explain_hvr");
    });
    
    $(".jb-nwpst-ab-pic").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Rst_ImgWind(this);
    });
    
    $(".jb-np-vid-pan-abort").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Rst_VidWind(this);
    });
    /*
    $("#atc_input").change(function(e){
        Obj.HandlePrepareNPCeleb(e.target);
    });
    //*/
    $("#start_npostTr_process").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_PprNPTr(e.target);
    });
    
    $("#start_npostMl_process").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_PprNPMl(e.target);
    });
    
    $(".jb-fd-w-ldmr-tgr").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_LdMrIml();
    });
    $(".jb-fd-e-ldmr-tgr").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_LdMrItr();
    });
    
    /*
    $(".fcb_img_maximus").off("hover").hover(function(e){
        /*
         * [DEPUIS 24-11-15] @author BOR
         *      Meilleur gestion du cas HOVER.
         *      Cette manière de faire prend aussi en compte le cas des navigateurs du type FIREFOX
         *
//        console.log("NWPOST.JS : 3445");
        var ihvr = false; //[DEPUIS 27-04-16]
        if ( $(this).filter(":hover").length ) {
            ihvr = true;
            $(this).children("span").removeClass("soft_fade").addClass("hard_fade");
        } else {
            $(this).children("span").addClass("soft_fade").removeClass("hard_fade");
        }
        
        /*
        $(this).children("span").toggleClass("soft_fade");
        $(this).children("span").toggleClass("hard_fade");
        $(this).children(".bot_fade").toggleClass("bot_fade_sp"); //[DEPUIS 24-11-15] Je ne le trouve null part ...
        //*/      
        
        /*
         * [DEPUIS 22-11-15] @author BOR
         *      On gère le cas des bouton de partage sur les réseaux sociaux.
         */
        /*
        if ( $(this).has(".jb-tqr-artmdl-shron-tgr").length ) {
            if ( $(this).find(".jb-tqr-artmdl-shron-tgr").first().hasClass("this_hide") ) {
                $(this).find(".jb-tqr-artmdl-shron-tgr").removeClass("this_hide");
            } else {
                $(this).find(".jb-tqr-artmdl-shron-tgr").addClass("this_hide");
            }
        }
        //*
        
//                if ( $(this).has(".jb-tqr-artmdl-shron-tgr").length ) {
//                    if ( $(this).find(".jb-tqr-artmdl-shron-tgr").first().hasClass("this_hide") ) {
        Kxlib_DebugVars(["NWPOST.JS : 3476",$(this).find(".jb-tqr-am-ax-box").length,$(this).find(".jb-tqr-am-ax-box").hasClass("this_hide"),ihvr]);
        if ( $(this).find(".jb-tqr-am-ax-box").length ) {
            if ( $(this).find(".jb-tqr-am-ax-box").hasClass("this_hide") && ihvr ) {
//                        $(this).find(".jb-tqr-artmdl-fav-tgr").removeClass("this_hide");
//                        $(this).find(".jb-tqr-artmdl-shron-tgr").removeClass("this_hide");
                $(this).find(".jb-tqr-am-ax-box").removeClass("this_hide");
            } else {
//                        $(this).find(".jb-tqr-artmdl-fav-tgr").addClass("this_hide");
//                        $(this).find(".jb-tqr-artmdl-shron-tgr").addClass("this_hide");
                $(this).find(".jb-tqr-am-ax-box").addClass("this_hide");
            }
        }
        
    });
    //*/
            
    $(".fcb_img_maximus").off("hover").hover(function(e){
        /*
         * [DEPUIS 24-11-15] @author BOR
         *      Meilleur gestion du cas HOVER.
         *      Cette manière de faire prend aussi en compte le cas des navigateurs du type FIREFOX
         */
        $(this).children("span").removeClass("soft_fade").addClass("hard_fade");
        
//        Kxlib_DebugVars(["NWPOST.JS : 3501",$(this).find(".jb-tqr-am-ax-box").length,$(this).find(".jb-tqr-am-ax-box").hasClass("this_hide")]);
        /*
         * [DEPUIS 02-06-16]
         */
        if ( $(this).find(".jb-tqr-artml-onhvr-tm").length ) {
            $(this).find(".jb-tqr-artml-onhvr-tm").removeClass("this_hide");
        }
        if ( $(this).find(".jb-tqr-am-ax-box").length ) {
            $(this).find(".jb-tqr-am-ax-box").removeClass("this_hide");
        }
    },function(e){
        $(this).children("span").addClass("soft_fade").removeClass("hard_fade");
        
//        Kxlib_DebugVars(["NWPOST.JS : 3508",$(this).find(".jb-tqr-am-ax-box").length,$(this).find(".jb-tqr-am-ax-box").hasClass("this_hide")]);
        /*
         * [DEPUIS 02-06-16]
         */
        if ( $(this).find(".jb-tqr-artml-onhvr-tm").length ) {
            $(this).find(".jb-tqr-artml-onhvr-tm").addClass("this_hide");
        }
        if ( $(this).find(".jb-tqr-am-ax-box").length ) {
            $(this).find(".jb-tqr-am-ax-box").addClass("this_hide");
        }
    });
    
    $(".jb-tqr-artmdl-shron-tgr").off("click").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        _f_Sharon(this);
    });
            
    /********* LOAD des ARTICLES PREDATE ***********/
    
    //Load manuel
    $(".jb-tmlnr-loadm-trg[data-scp='ml']").click(function(e){
        Kxlib_PreventDefault(e);
            
        _f_LdPdArt(this);
    });
    
    //Load automatique
    $(window).scroll(function(){
        
        /*    
        //Debug
        var h = $('body').height() - $(this).height();
        var txt = "Hauteur -> "+h+"; Theory -> "+$('#p-l-c-main').offset().top+"; Scroll -> "+$(this).scrollTop();
//        Kxlib_DebugVars([xt]);
        $("#ctrl-s-datas").html(txt);
        //*/
//        console.log("toto");
        var sp = ( Kxlib_IsIE() ) ? 1 : 0;
                
        var l = $('body').height() - $(this).height() + sp;
        if ( l === $(this).scrollTop() ) { 
            _f_LdPdArt();
        }
    });
    
    //Verifier toutes x secondes si un ou plusieurs nouveaux contents sont disponibles
    //setInterval( "Obj.CheckServerForMore();", 5000 ); //Provoque des erreurs
    //* 
    setInterval( function(){
        if ( $(".jb-acc_feeded").length ) {
            _f_Srv_ChkNwrIml();
        }  
//    }, 2000 ); //DEV, TEST, DEBUG
    }, 14500 );
    setInterval( function(){
        if ( $(".jb-acc_feeded").length ) {
            _f_Srv_ChkNwrItr();
        }
//    }, 5000 ); //DEV, TEST, DEBUG
    }, 18500 );
    //*/
    //*/
    /*
    // Check for the various File API support.
    if (window.File && window.FileReader && window.FileList && window.Blob) {
      // Great success! All the File APIs are supported.
    } else {
      alert('The File APIs are not fully supported in this browser.');jb-nwpst-ab-pic-bx
    }
    */
   
           
    /********************** INCORPORATED TEX **********************/
    
    $(".jb-tqr-brn-nwp-x-t-clpkr").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
    $(".jb-tqr-brn-nwp-x-t-ena").click(function(e){
        _f_XtraTxtEna(this);
    });
    
    $(".jb-tqr-brn-nwp-x-t-ipt").keyup(function(e){
        
        _f_Action(this,null,e);
    });
    
    
    /********************** PHOTO DU JOUR **********************/
    
    $(".jb-np-i-ftr-pod-hlp-tgr-h, .jb-np-i-ftr-pod-h-m-bmx").hover(function(e){
        _f_SODAct(this,"helpin",e);
    },function(e){
        _f_SODAct(this,"helpout",e);
    });
    
    $(".jb-np-i-ftr-pod-ipt").change(function(e){
        Kxlib_PreventDefault(e);
        
        _f_SODAct(this,"change",e);
    });
    
    
    
    /********************** OPTIONS POUR LA PUBLICATION **********************/
    
    $(".jb-nwpst-pic-xtra-txt").draggable({
        axis : "y",
        containment: "parent"
    });
    
};

new Brain_HandlePost();     