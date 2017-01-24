/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function NewTrdArt() {
    /*
     * 
     * FONCTIONNALITES
     *  Version : b.1505.1.1 (Mai 2015 VBeta1) [DATE UPDATE : 17-03-15]
     *      -> ...
     *      -> Afficher correctement les textes pour qu'ils soient fiables, sure et scalable.
     *  
     *  EVOLUTIONS ATTENDUES
     *      -> ...
     *  
     *  EVOLUTIONS POSSIBLES
     *      -> ...
     */
    
//    var gt = this;
//    this.alwfomat = ["png","jpeg","gif"];
//    this.alwsize = 5000000;
//    this.PrvwMaxH = "71";
//    this.PrvwMaxW = "71";
//    this.descMax = 484;
    var _nwImg;
    var _nwVid;
//    this.newImage;
    var _nwImg_nm;
    var _nwVid_nm;
//    this.newImage_nm;
    
    //Les nouveaux Articles chargés 
    var _NwLoadDs;
//    this.NewLoadDatas;
    //Les anciens Articles chargés. Il s'agit des Articles anterieurs
    var _PdDatas;
//    this.PdDatas;
    
    var _file;
    
    //L'interval de temps qui est pris comme repère pour faire la boucle
//    this.__NWCHECK_LOOP = 10000;
    
//    this.__LOC_EAST = "#jssel-tr-e-list-list";
//    this.__LOC_WEST = "#jssel-tr-w-list-list";
    
    
    /***************************************************************************************************************************************************************************/
    /****************************************************************************** PROCESS SCOPE ******************************************************************************/
    /***************************************************************************************************************************************************************************/
    
    var _f_Gdf = function () {
//    _f_Gdf = function () {
        var sz = 1048576 * 2.5;
        var dps = {
            "__LOC_EAST"        : "#jssel-tr-e-list-list",
            "__LOC_WEST"        : "#jssel-tr-w-list-list",
            "__NWCHECK_LOOP"    : 15000,
            "alwfomat"          : ["png","jpeg","gif"],
            "alwsize"           : sz,
            "PrvwMaxH"          : "71",
            "PrvwMaxW"          : "71",
            "descMax"           : 242,
//            "descMax": 484
            /**
             * [NOTE 20-05-16]
             */
            "FrmtEna"       : ["mp4"],
            "vdSzMx"        : 1048576*8,
            /*
             * [NOTE]
             *      L'objectif est d'avoir des vidéos de maximum 30 secondes.
             *      Cependant, pour ne pas détériorer l'expérience utilisateur, il nous faut être légèrement souple.
             *      Aussi on accepte les vidéos de 60 secondes avec une tolérances de ~0.99 secondes.
             *          En effet, pour une vidéo de 7 secondes on peut avoir une longueur de 7.6 secondes.
             *          Sans cette tolérance, l'utilisateur qui voudra poster la dite vidéo sera bloqué alors que son odinateur lui indique 7 secondes
             */
            "vdMxLn"        : 61,
        };
        return dps;
    };
    
    var _f_NwrArtControls = function(d) {
        try {
            if ( KgbLib_CheckNullity(d) | typeof d !== "object" | !( Kxlib_ObjectChild_Count(d) && Kxlib_ObjectChild_Count(d) > 0 ) ) {
                return;
            }
            
            var nd = [];
            $.each(d,function(x,o){
//                var a = o.art;
                var a = o; //[DEPUIS 22-04-16]
                if ( $(".jb-mdl-tr-post-in-list[data-item='"+a.id+"']").length ) {
//                    Kxlib_DebugVars([NWR_AT_CTRLS => "+a.id+"(AID)"]);
                    return true;
                } else {
                    nd.push(o);
                }
            });
            
            return ( nd.length ) ? nd : null;            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ChkNwArts = function (isat) {
//    this.CheckNewArticles = function () {
        /* Permet de vérifier s'il y a de nouveaux Articles disponibles. 
         * */
        try {
            
            /* On récupère l'id de la Tendance dans les meta */
            /* OBSELETE : Je préfère récupérer la donnée au niveau de l'Article lui même
             var r = Kxlib_GetTrendPropIfExist();
             if ( KgbLib_CheckNullity(r) )
             return;
             
             var ti = r.trid;
             //*/
            
            //On récupère la data correspondante au premier Article
            var fo = _f_G1stArt();
            
            if (! KgbLib_CheckNullity(fo) ) {
                var ai = $(fo).data("item");
                var at = $(fo).find(".kxlib_tgspy").data("tgs-crd");
                var ti = $(fo).data("tr");
                
//            Kxlib_DebugVars([ai,at,ti],true);
//            return;
                /*
                 * ETAPE : 
                 * On fait appel au serveur 
                 */
                var s = $("<span/>");
                isat = ( isat === true ) ? true : false;
                _f_Srv_ChkTrArt_Frm(ai, at, ti, "new", isat, null, s);
//            this.Srv_CheckNewTrArt(ti);
                
                $(s).on("datasready", function(e,d) {
                    if ( KgbLib_CheckNullity(d) || KgbLib_CheckNullity(d.tds) ) {
                        return;
                    }
                    
                    if ( Kxlib_ObjectChild_Count(d.tds) ) {
                        /*
                         * [DEPUIS 15-08-15] @BOR
                         */
                        var d__ = _f_NwrArtControls(d.tds);
                        if ( d__ ) {
                            _NwLoadDs = d__;
                            _f_SglNwArt(d__);
                        }
                    } else {
                        _f_Rst_SglNwArt();
                    }
                });
                
            } else {
                //TODO : Si aucun Article n'est encore disponible on lance First Articles. Pour l'heure, ça sera à l'utilisateur de mettre à jour la page en la rechargeant.
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_GtArtNbByCol = function() {
//    this._GetArtNumbByCol = function() {
        var ne = $("#jssel-tr-e-list-list").find(".mdl-tr-post-in-list").length,  
                nw = $("#jssel-tr-w-list-list").find(".mdl-tr-post-in-list").length;
        
        return [nw,ne];
    };
    
    var _f_GtLastInECol = function () {
//    this._GetLastArtInECol = function () {
        return $("#jssel-tr-e-list-list").find(".mdl-tr-post-in-list:last");
    };
    
    var _f_GtLastInWCol = function () {
//    this._GetLastArtInWCol = function () {
        return $("#jssel-tr-w-list-list").find(".mdl-tr-post-in-list:last");
    };
    
    var _f_G1stArt = function() {
//    this._GetFirstArticleInPage = function() {
        
        if (! $(".jb-mdl-tr-post-in-list").length ) {
            return;
        } else {
            /*
             * [NOTE 23-04-15] @BOR
             *  Cette manière est fiable.
             */
            return $(".jb-trpg-art-nest").find(".jb-mdl-tr-post-in-list:not([data-isgone='1']):first");
//            return $(".jb-trpg-art-nest").find(".jb-mdl-tr-post-in-list:first");
        }
        /* [BOSELETE 22-04-15]
        return (! _f_GtArtNbByCol()[0] ) ? null : $("#jssel-tr-w-list-list").find(".mdl-tr-post-in-list:first");
        //*/
    };
    
    var _f_GtLast = function() {
//    this._GetLastArticleInPage = function() {
        
        if (! $(".jb-mdl-tr-post-in-list").length ) {
            return;
        } else {
//            return $(".jb-trpg-art-nest").find(".jb-mdl-tr-post-in-list:last");
            /*
             * [NOTE 23-04-15] @BOR
             *      Cette manière est fiable.
             */
            var last = $(".jb-trpg-art-nest").find(".jb-mdl-tr-post-in-list:not([data-isgone='1']):last");
            return last;
        }
        
        /* [BOSELETE 22-04-15]
        var fo = _f_GtArtNbByCol();
        
        if ( fo[0] === fo[1] ) {
            return _f_GtLastInECol();
        } else {
            return ( fo[0] < fo[1] ) ? _f_GtLastInECol() : _f_GtLastInWCol();
        } 
        //*/    
    };
    
    var _f_GtPdArts = function (x) {
//    this.GetPdArticles = function () {
        //
        try {
            
            if ( KgbLib_CheckNullity(x) | !$(x).length ) {
                return;
            }
            
            /*
             * ETAPE :
             * On vérifie que le bouton est disponible
             */
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            
            /*
             * [DEPUIS 30-04-15]
             * ETAPE :
             *      On bloque le bouton
             */
            $(x).data("lk",1);
            
            /*
             * [DEPUIS 20-05-16]
             *      On bloque tous boutons qui pourraient créer des interférences
             */
            $(".jb-acc-nwtrart").data("lk",1);
            $(".jb-na-box-nw-clz-trg").data("lk",1);
            
            /*
             * ETAPE :
             * On affiche le spinner
             */
            _f_TglSpnr(true);
            
            //On récupère l'id de la Tendance
            var r = Kxlib_GetTrendPropIfExist();
            var ti = r.trid;
            
            //On récupère la data correspondante au dernier Article
            var fo = _f_GtLast();
            if ( KgbLib_CheckNullity(fo) ) {
                _f_TglSpnr();
                $(x).data("lk",0);
                return;
            }
            
//        alert("Debug !"+$(fo).attr("id"));

            var ai = $(fo).data("item");
            var at = $(fo).find(".kxlib_tgspy").data("tgs-crd");
            
            /*
             * [DEPUIS 09-11-15] @author BOR
             *      On sélectionne HMT (HowManyTimes)
             */
            var hmt = "";
            if (! $(".jb-tqr-hmt").length ) {
                return;
            } else if ( $(".jb-tqr-hmt").data("hmt") ) {
                hmt = $(".jb-tqr-hmt").data("hmt");
            }
            
//        var dt = $(fo).find(".kxlib_tgspy").html();
//        Kxlib_DebugVars([hmt,ai,at,ti],true);
//        return;
            /*
             * ETAPE :
             * On intérroge le serveur pour récupérer les données Antérieures
             */
            var s = $("<span/>");
//            Kxlib_DebugVars([ai,at,ti],true);
            _f_Srv_ChkTrArt_Frm(ai,at,ti,"old",false,hmt,s);
            
           /*
            * [NOTE 24-04-15] @BOR
            * On utilise maintenant la méthode évenement
            */
            $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) || KgbLib_CheckNullity(d.tds) ) {
                    return;
                }
                
                /*
                 * [DEPUIS 11-07-15] @BOR
                 */
                $(".jb-unq-nav-btn[data-dir='next']").find(".jb-unq-nav-btn-wait").addClass("this_hide");
                
                //aot : AllOperationTerminated
                var aot = $("<span/>");
                
                $(aot).on("operended",function(e){
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
    //               Kxlib_DebugVars(["NWA_TRPG_FILE",307,hmt,$(".jb-tqr-hmt").data("hmt")],true);

                    /*
                     * ETAPE : 
                     *      On reset la barre en masquant le spinner et libérant le bouton
                     */
                    _f_TglSpnr();
                    $(x).data("lk",0);
                    
                    /*
                     * [DEPUIS 20-05-16]
                     *      On débloque tous boutons qui aurient pu créer des interférences
                     */
                    $(".jb-acc-nwtrart").data("lk",0);
                    $(".jb-na-box-nw-clz-trg").data("lk",0);
                });
                
                _f_ShwPdLoadArts(d.tds,aot);
                
            });
            
            $(s).on("operended",function(e){
                /*
                 * ETAPE : 
                 * On reset la barre en masquant le spinner et libérant le bouton
                 */
                _f_TglSpnr();
                
                /*
                 * [DEPUIS 01-06-15] @BOR
                 */
                var m__ = Kxlib_getDolphinsValue("ART_NOONE_GEN_PAGE");
                $(".jb-trpg-loadm-trg").text(m__);
                $(".jb-trpg-loadm-trg").addClass("EOP");
//                $(x).data("lk",0);
                
                /*
                 * [DEPUIS 20-05-16]
                 *      On débloque tous boutons qui aurient pu créer des interférences
                 */
                $(".jb-acc-nwtrart").data("lk",0);
                $(".jb-na-box-nw-clz-trg").data("lk",0);
                    
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_ChkAldyXsts = function (d) {
//    this._CheckIfAldyListed = function (d) {
        return ( $(".jb-mdl-tr-post-in-list[data-item='"+d.id+"']").length ) ? true : false;
    };
    
    
    var _f_AddNew = function(x) {
//    this.AddNewArticle = function() {
        try {
            /*
             * [DEPUIS 140815] @BOR
             */
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            //1- On vérifie qu'il y a bien un fichier chargé et que toutes les conditions sont respectées
//            if ( $("#na-box-img-explain").attr("rdy") !== "1" ) { //[DEPUIS 20-05-16]
            if ( $(".jb-na-box-img-xpln[data-scp='image']").attr("rdy") !== "1" 
                && $(".jb-na-box-img-xpln[data-scp='video']").attr("rdy") !== "1"  ) {
                $(x).data("lk",0);
                return;
            }
            
            //t = texte
            var t = $(".jb-na-box-input-txt").val();
            
            //2- On vérifie qu'il y a bien du texte dans la zone 
            //3- On envoie le texte pour traitement
            var r = _f_ChkArtTxt(t);
            if ( typeof r === "undefined" ) {
                //(Sinon) On "annonce" l'erreur en changeant la bordure de textarea
                $(".jb-na-box-input-txt").addClass("error_field");
                $(x).data("lk",0);
                return;
            } 
            
            /*
             $(".tr-h-t-d-40").html(r); //Pour tester l'efficacité de l'échappement des caractères
             return;
             //*/
//        alert("GRAZE MILLE");
            
            
            //4- On prépare les données
            var dc = Kxlib_DataCacheToArray($(".jb-trpg-trd-owr").data("cache"));
            if ( KgbLib_CheckNullity(dc) ) {
                //NOTE : Ca vient surement d'une modification du DOM de l'utilisateur => on s'en fou
                return;
            }
            
            var toid = dc[0][0];
            if ( KgbLib_CheckNullity(toid) ) {
                //NOTE : Ca vient surement d'une modification du DOM de l'utilisateur => on s'en fou
                return;
            }
            
            /* //[DEPUIS 20-05-16]
            var d = {
                "toid"  : toid,
                "img"   : _nwImg,
                "d"     : t,
                "n"     : _nwImg_nm
            };
            //*/
            
            /*
             * [DEPUIS 20-05-16]
             */
            var fds = {};
            if ( $(".jb-na-box-img-xpln[data-scp='image']").hasClass("this_hide") ) {
                fds = {
                    "type"  : "video",
                    "data"  : _nwVid,
                    "name"  : _nwVid_nm,
                    "opts"  : {
                        istory      : false,
                        "xtrabar"   : null 
                    }
                };
            } else {
                fds = {
                    "type"  : "image",
                    "data"  : _nwImg,
                    "name"  : _nwImg_nm,
                    "opts"  : {
                        istory      : false,
                        "xtrabar"   : null 
                    }
                };
            }
            
            
//            Kxlib_DebugVars([JSON.stringify(d)],true);
//            Kxlib_DebugVars([d.toid],true);
//            Kxlib_DebugVars([JSON.stringify(fds)],true);
//            Kxlib_DebugVars([toid,fds.type,fds.name,fds.opts,t],true);
//            return;
        
            //5- On envoie les données 
            var s = $("<span/>");
//            _Srv_NewArt(d,s);
            _Srv_NewArt(toid,fds.type,fds.data,fds.opts,t,s);
            
            //6- On construit et affiche le panneau d'attente
            _f_ShwWtPan();
            
            /*
             * [29-04-15] @BOR
             * ETAPE :
             *      On affiche les Articles en attente s'il en a pour augmenter les chances que les Articles soient ajoutés dans le bon sens.
             *      RAPPEL : La même opération est exécutée juste avant l'insertion de nouvel Article.
             */
            if ( !$(".jb-new-art-bar").hasClass("this_hide") && !KgbLib_CheckNullity(_NwLoadDs) ) {
                $(".jb-n-a-b-trig").click();
                _f_AkxNewDeck(null,true);
            }
            
            $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                
                //*
                //On vérifie si le titre est différent
                var tle = Kxlib_Decode_After_Encode(d.art.trtle);
    //                    Kxlib_DebugVars([tle,$(".jb-a-h-t-top-tr-tle").text()],true);
    //                    return;
                if ( tle !== $(".jb-a-h-t-top-tr-tle").text() ) {
                    //*** On fait apparaitre la black fenetre ***

                    //On vérifie qu'on a bien trtle_h
                    if ( KgbLib_CheckNullity(d.art.trhref) ) {
                        //On signale l'erreur au serveur
                        //[NOTE 10-10-14] @author L.C. ... mais pas à l'utilisteur car je ne vois pas comment lui expliquer la situation
                        return;
                    }

                    var m = Kxlib_getDolphinsValue("UA_TRPG_DEEP_ALT");
    //                        Kxlib_DebugVars([m],true);
    //                        return;

                    var bbd = new BlackBoardDialog();
                    var o = {
                        title: null,
                        message: m,
                        /*
                         * Quitter la page et aller ailleurs.
                         * Si la valeur isd (IsDefault) est à false, la valeur link doit représenter un des cas connu dans BOARD.
                         * Sinon, on doit renseigner l'url vers lequel il faut rediriger.
                         * 
                         * IMPORTANT : 
                         *  Si on souhaite reload mais avec URL qui est différente (cas où un paramètre est différent) redir prend "reload_fly"
                         *  Aussi, il faut que la valeur link soit renseigner 
                         */ 
                        fly: null,
                        redir: d.art.trhref
                    };

    //                        Kxlib_DebugVars([o.title, o.message, o.fly, o.redir],true);
    //                        return;

                    bbd.Dialog(o);

                }

                //TODO : On vérifie si l'accessibilité de la Tendance a changé

                /*
                 * ETAPE :
                 * On vérifie s'il y a des Articles en attente à afficher. Le but n'étant de limiter le risque de voir les Articles être mal agencés.
                 */
                if ( !$(".jb-new-art-bar").hasClass("this_hide") && !KgbLib_CheckNullity(_NwLoadDs) ) {
                    $(".jb-n-a-b-trig").click();
                }

                //On insère les données dans les champs
                var o = new TrendHeader();
                o.UpdHdrWNwStgs(d.tr_stsg);
                o.UpdatePostCount(d.art.trpnb);
                o.UpdateFolwrCount(d.art.trfol);

                //TODO : Mettre à jour les données sur l'image de couverture

                //TODO : Mettre à jour les données sur le propriétaire de la Tendance
                //*/

                //FINALLY
                _f_HdWtPan();

                //On masque
                if (! $("#nwtrdart-box").hasClass("this_hide") ) { //[DEPUIS 18-08-15] @author BOR
                    _f_AkxNewDeck();
                }

//                _f_ShwNwTrArt(d,true); //[DEPUIS 22-04-16]
                _f_ShwNwTrArt(d.art,true,true);

                _f_NoOnePg(true); //[DEPUIS 02-06-15] @BOR

                //Afficher le message de notification
                var c = "ua_new_artintr"; 
                var Nty = new Notifyzing();
                var o = {
                    "tr_title"  : d.art.trtitle,
                    "tr_href"   : d.art.trhref
                };
                Nty.FromUserAction(c,o);

                /*
                 * [DEPUIS 140815] @BOR
                 */
                $(x).data("lk", 0);
            
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_ChkArtTxt = function (t) {
//    this._VerifyArtText = function (t) {
        
        if ( KgbLib_CheckNullity(t) ) { 
            return; 
        }
        
       /*
        * dcn : DescriptionCouNt
        * [DEPUIS 14-08-15] @BOR
        */
        var dcn = CountChar_SkipHash2(t);
        
        //On vérifie que le texte ne dépasse pas le MAX
        if ( dcn > _f_Gdf().descMax ) { return; }
//        if ( t.length > _f_Gdf().descMax ) { return; }
        
        //Echapper le texte
        /*
         * [NOTE 10-10-14] @author L.C. 
         * On laisse le serveur se charger de la sécurisation des données. 
         * Sinon, elles risquent d'être corrompues
         */
//        t = Kxlib_EscapeHTMLEntity(t);
        
        return t;
    };
    
    
    var _f_CheckImg = function(arg){
//    this.HandleSafetyOnImage = function(arg){
        //Verifie : format, taille et forme
        if (!arg.type.match('image/*')) {
            return {errType: "badfile"};
        } else {
            var filef = arg.type.split('/').pop();
//            alert("Format :"+filef);
            //*
            if ( $.inArray( filef, _f_Gdf().alwfomat) === -1 ) {
                //Est ce que le type de l'image est autorisé
                return {errType: "badtype"};
            } else if ( arg.size > _f_Gdf().alwsize ) {
                //Est ce que le type de l'image est autorisé
                return {errType: "tooloud"};
            } else {
                //On signale que tout va bien, il ne reste plus qu'à verifier que l'image est caréé
                return {errType: "aw_dims"};
            }
            //*/
        }
    };
    
    
    var _f_ImgErrs = function (a,x,c,m) {
//    this._ImgErrors = function (c) {
        try {
            if ( KgbLib_CheckNullity(a) | ( a === "shw-err" && KgbLib_CheckNullity(c) && KgbLib_CheckNullity(m) ) ) {
                return;
            }

            /* On affiche le message d'erreur */
//            var msg = Kxlib_getDolphinsValue(c);
//            alert(msg);
            
            /*
             * [DEPUIS 20-05-16]
             *      Refactorisation pour prendre en compte l'affichage vie une fenetre personnalisée
             */
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            switch (a) {
                case "shw-err" :
                        $(".jb-na-box-nw-clz-trg, .jb-na-box-nw-rst-trg, .jb-acc-nwtrart").data("lk",1);
                        m = ( m ) ? m : Kxlib_getDolphinsValue(c);
                        $(".jb-trpg-nwabx-errm").html(m);
                        $(".jb-trpg-nwabx-bmx").removeClass("this_hide");
                    break;
                case "hid-err" :    
                        $(".jb-trpg-nwabx-bmx").addClass("this_hide");
                        $(".jb-trpg-nwabx-errm").html("");
                        $(".jb-na-box-nw-clz-trg, .jb-na-box-nw-rst-trg, .jb-acc-nwtrart").data("lk",0);
                    break;
                default :
                    return;
            }
            
            /* 
             * ETAPE :
             *      On vide l'input 
             * */
            $("#na-box-img-catcher").val("");
            
            /*
             * ETAPE :
             *      On débloque le bouton
             */
            $(x).data("lk",0);

        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_SfetyOnVid = function(fl){
        try {
            //Verifie : format, taille et forme
            if (! fl.type.match('video/*') ) {
                return { errType : "bad_file" };
            } else {
                var filef = fl.type.split('/').pop();
                var video = document.createElement('video');
                if ( $.inArray( filef, _f_Gdf().FrmtEna) === -1 ) {
                    //Est ce que le type de l'image est autorisé
                    return { errType : "err_vid_type"};
                } else if ( fl.size > _f_Gdf().vdSzMx ) {
                    //Est ce que le type de la taille de la vidéo est conforme
                    return { errType : "err_vid_size"};
                } else {
                    //Est ce que le type de l'image est autorisé
                    return true;
                }
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_OnReadFile = function(file) {
//    this._HandleReadingOnFile = function(file) {
        try {
            if(! window.FileReader) {
                //TODO : "Switch sur Adobe ? Normallement, cela est resolu au niveau du server-side"
                return;
            } 

            var reader = new FileReader();
            var $t = this;

            reader.onload = function() {
                var img = new Image();
                img.src = reader.result;

                /*
                 * [DEPUIS 06-12-15]
                 */
                _file = file;

                img.onload = function() {
                    _f_CrtThumbnail(this, file.name);
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
    
    var _f_TreatIptFiles = function (files) {
//    this.TreatinputFiles = function (f) {
        try {
            
            if ( KgbLib_CheckNullity(files) ){
                return;
            }

            $.each(files, function(ix,f) {

                if ( f.type.match('image/*') ) {

                    //1- On vérifie si le fichier est une image
                    var c = _f_CheckImg(f);
                        //(Sinon) On affihce l'erreur correspondante
                    if ( c.errType !== "aw_dims" ) {
                        c = "ERR_NWTRART_"+c.errType.toUpperCase();
                        _f_ImgErrs("shw-err",null,c,null);
                        return;
                    } else {
                        //21- On charge l'image et on vérifie que l'image est carrée.
                        //PUIS on crée le thumbnail

                        _f_OnReadFile(f);
                    }

                    //3- On affiche la phrase "Image chargée"

                    //4- On insère une donnée qui permettra de signifier que tout est en règle
                } else if ( f.type.match('video/*') ) { 
                    var r = _f_SfetyOnVid(f);
                    if ( r === true ) {
                        r = { 
                            errType : "err_vid_dura" 
                        };
                        window.URL = window.URL || window.webkitURL;
                        
                        var video = document.createElement('video');
                        video.preload = 'metadata';
                        video.onloadedmetadata = function() {
                            window.URL.revokeObjectURL(this.src);
                          
//                            Kxlib_DebugVars([typeof video.duration, video.duration, video.duration > 7],true);
//                            return;
                            
                            if ( video.duration >= _f_Gdf().vdMxLn ) {
                                  _errMsg = Kxlib_getDolphinsValue("ERR_AVID_BAD_DURA");
                                  _f_ImgErrs("shw-err",null,null,_errMsg);
                            } else {
//                                var rdr = new FileReader(), _nwVid;
                                var rdr = new FileReader();
                                rdr.onload = function(rdrE) {
                                    /*
                                     * [NOTE 18-01-16]
                                     *      On écrit en dur l'entete car à cette date seul les fichiers *.mp4 sont acceptés
                                     */
                                    _nwVid =  encodeURIComponent("data:video/mp4;base64,"+btoa(rdrE.target.result));
                                    
                                    _nwVid_nm = f.name;
                                    
                                    //On signale que l'image est chargée et conforme
                                    $(".jb-na-box-img-xpln[data-scp='video']").attr("rdy","1");
                                    
//                                    alert("NAME : "+f.name);
//                                    alert(_nwVid);
//                                    Kxlib_DebugVars([f.name,_nwVid],true);
//                                    return;
                                    
                                    $(".jb-na-box-img-xpln").addClass("this_hide");
                                    $(".jb-na-box-img-xpln[data-scp='video']").removeClass("this_hide");
                                };
                                rdr.readAsBinaryString(f);
                            }
                        };
                        video.src = URL.createObjectURL(f);
                } 
                    else if ( r.errType ) {
                        //TODO : VID_ABORT
                        var ecd = r.errType;
                        switch (ecd) {
                            case "err_vid_type" :
                                    _errMsg = Kxlib_getDolphinsValue("ERR_AVID_BAD_TYPE");
//                                    _f_ImgErrs(null,_errMsg);
                                    _f_ImgErrs("shw-err",null,null,_errMsg);
                                break;
                            case "bad_file" :
                            case "err_vid_size" :
                                    _errMsg = Kxlib_getDolphinsValue("ERR_AVID_BAD_SIZE");
//                                    _f_ImgErrs(null,_errMsg);
                                    _f_ImgErrs("shw-err",null,null,_errMsg);
                                break;
                            default:
                                return;
                        }
                    } 
                    else {
                        //TODO : VID_ABORT
                        return;
                    }
                }
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    
    
    var _f_Abort = function (x) {
//    this.AbortAddingProcess = function () {
        try {
            
            if ( KgbLib_CheckNullity(x) && $(x).data("lk") === 1 ) {
                return;
            }
            
            //On vérifie si un ajout n'est pas en cours de réalisation
            if (! $(".jb-na-box-pwt").hasClass("this_hide") ) {
                return;
            }

            _f_RstCreaForm();
            
            //On reset l'image sauvegardée pour encore mieux sécuriser la création de l'article
            _nwImg = null; 
            _nwImg_nm = null; 
            
            /*
             * [DEPUIS 21-05-16]
             *      On reset l'image sauvegardée pour encore mieux sécuriser la création de l'article
             */
            _nwVid = null; 
            _nwVid_nm = null; 
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
        
    };
    
    var _f_HandleArtDesc = function (txt) {
//    this._HandleArtDesc = function (txt) {
        //TODO : Opérations sur le texte
        
        //1- On échappe le texte
        
    };
    
    var _f_LiveHandleArtDesc = function () {
//    this._LiveHandleArtDesc = function () {
        //TODO : Gère l'insertion du texte en live
        
        /*
         * RULES : 
         * -> Si le text comporte un lien, on retire x caractères restant
         * * */
    };
    
    /******************************* DISPLAYING **********************************/
    
    
    var _f_NwTrArt_ReBind = function (am) {
//    this._NewTrArt_BindHandler = function (e) {
        try {
            if ( KgbLib_CheckNullity(am) ) {
                return;
            }
            
            //On bind Hover
            $(am).find(".fcb_img").off("hover").hover(function(){
                _f_OnHvrArt(this,true);
            }, function(){
                _f_OnHvrArt(this);
            });

            $(am).find(".fcb_img_link").click(function(e){
                Kxlib_PreventDefault(e);

                (new Unique()).OnOpen("TRPG",this);
            });
            
            /*
             * [DEPUIS 16-05-16]
             */
            $(am).find(".fcb_img .jb-irr").off("click").click(function(e){
                Kxlib_StopPropagation(e);
                Kxlib_PreventDefault(e);
            });

            /*
             * [DEPUIS 24-11-15] @author BOR
             */
            $(am).find(".jb-tqr-artmdl-shron-tgr").click(function(e){
                Kxlib_PreventDefault(e);
                Kxlib_StopPropagation(e);
                
                _f_Sharon(this);
            });

            return am;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_TidyUpArtBczOfNew = function ($o) {
//    this._TidyUpArtBczOfNew = function ($o) {
        /* Permet de "ranger" les articles après chaque nouvel ajout d'Article */
        if ( KgbLib_CheckNullity($o) ) { return; }
        
        //0: On masque les liste et on fait apparaitre le loader
//        $("#jssel-tr-w-list-list").addClass("this_hide");
//        $("#jssel-tr-e-list-list").addClass("this_hide");
//        $("#trdart_load").removeClass("this_hide");
        
        //1: on ajoute le nouvel article au debut de West
//        $($o).hide();
//        $($o).prependTo("#jssel-tr-w-list-list").fadeIn(250);
        $($o).prependTo("#jssel-tr-w-list-list");
        
        //2: on ajoute l'ancien 1st Article au debut de East
        var $old = $("#jssel-tr-w-list-list").find(".mdl-tr-post-in-list:eq(1)");
//        $old.hide().prependTo("#jssel-tr-e-list-list").fadeIn(250);
        $old.prependTo("#jssel-tr-e-list-list");
        
        //3: On switch les éléments entre les colonnes les colonnes ESAT et WEST
            //On commence par cloner les éléments à WEST sauf le premier qui représente le nouvel élément
        var wlist = $("#jssel-tr-w-list-list").find(".mdl-tr-post-in-list:gt(0)").clone(true,true);
            //On supprime tous les articles à WEST sauf le premier
        $("#jssel-tr-w-list-list").find(".mdl-tr-post-in-list:gt(0)").remove();
            //On sélectionne les éléments à déplacer dans la colonne EAST à WEST
        var elist = $("#jssel-tr-e-list-list").find(".mdl-tr-post-in-list:gt(0)");
        //$("#jssel-tr-e-list-list").find(".mdl-tr-post-in-list:gt(0)").insertAfter($($me)); //NON !!
        $.each(elist, function(i,v) {
//            $(v).hide().appendTo("#jssel-tr-w-list-list");
//            $(v).fadeIn(250);
            $(v).appendTo("#jssel-tr-w-list-list");
        });
            //On ajoute les éléments de WEST vers EAST
        $.each(wlist, function(i,v) {
//            $(v).hide().appendTo("#jssel-tr-e-list-list");
//            $(v).fadeIn(250);
            $(v).appendTo("#jssel-tr-e-list-list");
        });
        
        //4: On Hide les Articles et on les affichers en fadeIn
        //[NOTE : 14-06-14] Le processus va tellement vite que l'utilisateur n'a pas le temps de le voir se dérouler
            //Aussi, pas besoin de le masquer car il ne gène pas l'oeil et la méthode de Rangement n'est pas vraiment 
            //"révélée"
    };
    
    var _f_SglNwArt = function (a) {
//    this.SignalNewArt = function (a) {
        //Le nombre d'Articles
        var n = Kxlib_ObjectChild_Count(a);
        $(".jb-n-a-b-nb").text(n);
        
        /*
         * Récupérer le libellé dans la bonne langue et insérer
         */
        var m = Kxlib_getDolphinsValue("trpg_notif_new_art");
        $(".jb-n-a-b-lib").text(m);
        
        /*
         * Faire apparaitre le bandeau
         */
        $(".jb-new-art-bar").removeClass("this_hide");
        
        //TODO : Changer le titre de la page HTML pour attirer l'attention de l'utilisateur actif
    };
    
    var _f_Rst_SglNwArt = function () {
//    this.Reset_SignalNewArt = function () {
        
        $(".jb-n-a-b-nb").text("");
        $(".jb-n-a-b-lib").text("");
        $(".jb-new-art-bar").addClass("this_hide");
    };
    
    var _f_ShwToLdAs = function () {
//    this.DisplayNewLoadArtiles = function () {
        /* Permet d'afficher les nouveaux Articles chargés */
        if ( KgbLib_CheckNullity(_NwLoadDs) ) { 
            return; 
        }
        
       /*
        * [DEPUIS 21-05-16]
        *       On vérifie si le module d'ajout au niveau de la page TRPG, est ouvert.
        *       Dans ce cas, on le ferme.
        */
        if (! $("#nwtrdart-box").hasClass("this_hide") ) {
            _f_AkxNewDeck(null);
        }
            
        $.each(_NwLoadDs, function (i,v) {
            _f_ShwNwTrArt(v);
        });
                
        /*
         * [NOTE 22-04-15] @BOR
         * ETAPE :
         *      On reshuffle pour replacer les éléments
         *  [NOTE 20-05-16] @BOR
         *      setTimeout
         */
        setTimeout(function(){
            var TR = new Trend(false);
            TR._f_Shuffle();
        },500);
        
        //On retire le ruban qui signale qu'il y a de nouveaux Articles
        $(".jb-new-art-bar").addClass("this_hide");
    };
    
    var _f_ShwPdLoadArts = function (d,aot) {
//    this.DisplayPdLoadArtiles = function (d) {
        try {
            /* Permet d'afficher les nouveaux Articles chargés */
            if ( KgbLib_CheckNullity(d) ) { 
                return; 
            }
            
            //fe = FirstElement : necessaire pour le scrollTop
            /*
            var fst, scd, fe; 
            var fo = _f_GtArtNbByCol();
//        alert("Debug !"+" WEST => "+fo[0]+"; EAST => "+fo[1]);
            if (fo[0] === fo[1]) {
                fst = _f_Gdf().__LOC_WEST;
                scd = _f_Gdf().__LOC_EAST;
            } else {
                if (fo[0] < fo[1]) { 
                    fst = _f_Gdf().__LOC_WEST;
                    scd = _f_Gdf().__LOC_EAST;
                } else {
                    fst = _f_Gdf().__LOC_WEST;
                    scd = _f_Gdf().__LOC_EAST;
                }
            } 
//        alert("Debug !"+" FIRST => "+fst+"; SECND => "+scd);
            var cn = 0;
            $.each(d, function(ix, v) {
                if (!(cn % 2)) {
                    //Si c'est le premier de la liste on le sauvegarde pour le scrollTop
                    if (!ix) {
                        fe = _f_ShwPdTrArt(v, fst, true);
                    }
                    
                    _f_ShwPdTrArt(v, fst);
                } else {
                    _f_ShwPdTrArt(v, scd);
                }
                ++cn;
            });
//        alert($(fe).attr("id"));
            //*/
            
           /*
            * [NOTE 29-04-15]
            * ETAPE :
            *       On vérifie si le module d'ajout au niveau de la page TRPG, est ouvert.
            *       Dans ce cas, on le ferme.
            */
            if (! $("#nwtrdart-box").hasClass("this_hide") ) {
//                $(".jb-acc-nwtrart").click(); //[DEPUIS 20-05-16]
                _f_AkxNewDeck(null,"_force_inner_needed");
            }
            
            /*
             * [DEPUIS 20-05-16]
             *      L'utilisation de mecanisme d'affichage avec animation fait que certaines opérations sont exécutées presque au même moment.
             *      Cela pose plusieurs problèmes surtout au niveau de l'affichage.
             *      Mettre un TimeOut permet de laisser le temps à NEWDECK de se fermer puis de lancer les autres opérations.
             */
            setTimeout(function(){
                
                //tdc = ToDesC
                var tdc = [];
                $.each(d,function(i,v) {
                    var am = _f_ShwPdTrArt(v,$(".jb-trpg-art-nest"),true);
                    tdc.push(am);
                });

                /*
                 * ETPAE : 
                 *      On fait appel au script qui permet de trier les Articles et de les afficher
                 */
                var TR = new Trend(false);
    //            TR._f_FrmtDsc(tdc); //[DEPUIS 22-04-16]
                TR._f_Shuffle();
                
                $(aot).trigger("operended");
            
//            $(window).scrollTop($(fe).offset().top);
            },500);
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
                case "__ERR_VOL_ART_GONE" :
                        //adi : ArticleDomId
                        if (! ( o.hasOwnProperty("adi") && !KgbLib_CheckNullity(o.adi) ) ) {
                            return;
                        }
                        var $ad = $(".jb-mdl-tr-post-in-list[data-item='"+o.adi+"']");
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
                                arf = $(".jb-trpg-art-nest").find(".jb-mdl-tr-post-in-list:not([data-isgone='1']):first");
                            } else {
                                arf = $(".jb-trpg-art-nest").find(".jb-mdl-tr-post-in-list:not([data-isgone='1']):last");
                            }
                            
                            if (! $(arf).length ) {
                                return;
                            }
                            
                            /*
                             * On relance une demande
                             */
                            if ( o.dir === "top" ) {
                                _f_ChkNwArts();
                            } else {
                                var x = $(".jb-trpg-loadm-trg");
                                //On réinitialise le marqueur pour permettre une autre tentative
                                $(x).data("lk",0);
                                _f_GtPdArts(x);
                            }
                            
                        }
                        
//                        Kxlib_DebugVars(["START processing ART_GONE"],true);
                        
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
//                              href      : "htt://beta.trenqr.com/f/d6601Do3aLbQ8Q6HlCh89"
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
    
    /****************************************************************************************************************************************************************************/
    /******************************************************************************* SERVER SCOPE *******************************************************************************/
    /****************************************************************************************************************************************************************************/
    
    /**********************/
    //URQID => Update les paramètres de NewsFeed
    var _Ax_NwTrArt = Kxlib_GetAjaxRules("CR_NW_TRDART");
//    var _Srv_NewArt = function (ad,s) {
    var _Srv_NewArt = function (toid,ftype,fdata,fdopt,at,s) { 
//    this._Srv_NewTrendArt = function (ad) {
//        if ( KgbLib_CheckNullity(ad) | KgbLib_CheckNullity(s) ){ 
        if ( KgbLib_CheckNullity(toid) | KgbLib_CheckNullity(ftype) | KgbLib_CheckNullity(fdata) | KgbLib_CheckNullity(at) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var x = $(".jb-nwtrart");
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    return;
                }
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    //On masque le panneau d'attente
                    _f_HdWtPan();
                    $(x).data("lk",0);
                    if (Kxlib_AjaxIsErrVolatile(d.err)) {
                        switch (d.err) {
                            case "__ERR_VOL_U_G" :
                            case "__ERR_VOL_U_GONE" :
                            case "__ERR_VOL_ACC_GONE" :
                            case "__ERR_VOL_USER_GONE" :
                            case "__ERR_VOL_CU_GONE" :
                            case "__ERR_VOL_OWNER_GONE" :
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_TRD_GONE" :
                                    //TODO : Indiquer à l'utilisateur que la Tendance n'existe plus via un overlay ou juste en rechargeant
                                   /*
                                    * [DEPUIS 09-11-15] @author BOR
                                    *      On utilise un HACK pour faire apparaitre la zone voulue puis on reload.
                                    */
                                    Kxlib_AjaxGblOnErr({status:401},"error");
//                                    location.reload();
                                break;
                            case "__ERR_VOL_DENY" :
                            case "__ERR_VOL_DENY_AKX" :
                            case "__ERR_VOL_DNY_AKX" :
//                                    Kxlib_AJAX_HandleDeny();
                                break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            default:
                                break;
                        }
                    } 
                    
                    return;
                } else if (! KgbLib_CheckNullity(d.return)  )  {
                    /*
                     * Données attendues : 
                     *  (1) Données sur l'Article
                     *  (2) Données sur le propriétaire
                     *  (3) Données sur la Tendance
                     *      Notamment le nombre de posts et de Followers
                     *      + Titre, Description, Couverture
                     */
                    var rds = [d.return];
                    $(s).trigger("datasready",rds);
                } else {
                    return;
                }
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Send error to SERVER
//            alert("AJAX ERR : "+nwtrdart_uq);
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH_LTR");
            
            //FINALLY
             _f_HdWtPan();
            
            return;
        };
        
        //On récupère l'id de la Tendance
        var i = Kxlib_GetTrendPropIfExist();
        var curl = document.URL;
        
//        Kxlib_DebugVars([KgbLib_CheckNullity(i),i.hasOwnProperty("trid"),KgbLib_CheckNullity(i.trid),KgbLib_CheckNullity(i.trttl)],true);
//        return;
        
        if ( 
            KgbLib_CheckNullity(i) 
            || !( i.hasOwnProperty("trid") && !KgbLib_CheckNullity(i.trid) ) 
            || !( i.hasOwnProperty("trttl") && !KgbLib_CheckNullity(i.trttl) ) 
        ) { 
            return;
        } 
        
        var toSend = {
            "urqid": _Ax_NwTrArt.urqid,
            "datas": {
                /* //[DEPUIS 20-05-16]
                "ti"    : i.trid,
                "toid"  : ad.toid,
                "p"     : ad.img,
                "d"     : ad.d,
                "n"     : ad.n
                //*/
                "trid"      : i.trid,
                "trtle"     : i.trttl,
                "toid"      : toid,
                "ftype"     : ftype,
                "fdata"     : fdata,
                "fname"     : _nwImg_nm || _nwVid_nm,
                "fdopt"     : fdopt,
                "msg"       : at,
                "curl"      : curl
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_NwTrArt.url, wcrdtl : _Ax_NwTrArt.wcrdtl });
    };
    
    /*****************************/
    /* //[NOTE 08-10-14] OBSELETE 
    //URQID => On vérifie et récupère les nouveaux Articles 
    this.checkNewPost_url = "http://127.0.0.1/korgb/ajax_test.php";
    this.checkNewPost_uq = "TRPG_CHECK_NWPOSTS";
    this.Srv_CheckNewTrArt = function (ti) {
        
        if ( KgbLib_CheckNullity(ti) )
            return;
        
        var th = this;
        var onsuccess = function (datas) {
            try {
                
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else {
                    $(".jb-new-art-bar").addClass("this_hide");
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    //alert(datas.err);
                } else if ( datas.return ) {
                    Kxlib_DebugVars([New articles '+Kxlib_ObjectChild_Count(datas.return)]);
                    //*
                    if ( Kxlib_ObjectChild_Count(datas.return) ) {
                        _NwLoadDs = datas.return;
                        _f_SglNwArt(datas.return);
                    } else 
                        $(".jb-new-art-bar").addClass("this_hide");
                    
                }
            } catch (e) {
//                alert("TRPG_CHECK_NWPOSTS => "+e.message);
//                $(".jb-new-art-bar").addClass("this_hide");
            }
            
        };

        var onerror = function(a,b,c) {
            alert("AJAX ERR : "+th.checkNewPost_uq);
        };

        var toSend = {
            "urqid": th.checkNewPost_uq,
            "datas": {
                "ti": ti
            }
        };

        Kx_XHR_Send(toSend, "post", this.checkNewPost_url, onerror, onsuccess);
    };
    //*/
    
    
    /*****************************/
    //URQID => On vérifie et récupère les Articles antérieurs. on envoie la date du dernier article affiché
    var _Ax_ChckPdPsts = Kxlib_GetAjaxRules("TRPG_CHECK_PDPOSTS");
    var _f_Srv_ChkTrArt_Frm = function (ai,at,ti,w,isat,hmt,s) {
//    this._Srv_CheckTrArt_Frm = function (ai,at,ti,w,sl) {
        
        if ( KgbLib_CheckNullity(ti) | KgbLib_CheckNullity(w) | KgbLib_CheckNullity(isat) | KgbLib_CheckNullity(s) ) {
//        if ( KgbLib_CheckNullity(ai) | KgbLib_CheckNullity(at) | KgbLib_CheckNullity(ti) | KgbLib_CheckNullity(w) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else {
                    /*
                     * [DEPUIS 01-06-15] @BOR
                     */
                    _f_TglSpnr();
                    
                    var m__ = Kxlib_getDolphinsValue("ART_NOONE_GEN_PAGE");
                    $(".jb-trpg-loadm-trg").text(m__);
                    $(".jb-trpg-loadm-trg").addClass("EOP");
                    
                    return;
                }
                
                if (! KgbLib_CheckNullity(datas.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        
                        switch (datas.err) {
                            case "__ERR_VOL_U_G" :
                            case "__ERR_VOL_U_GONE" :
                            case "__ERR_VOL_ACC_GONE" :
                            case "__ERR_VOL_USER_GONE" :
                            case "__ERR_VOL_CU_GONE" :
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_ART_GONE" :
                                    var dir = ( w === "new" ) ? "top" : "btm";
                                    _f_SplCz("__ERR_VOL_ART_GONE",{"adi" : ai, "dir" : dir, "tryag" : 1});
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX" :
                            case "__ERR_VOL_DNY_AKX" :
                            case "__ERR_VOL_FAILED" :
                                break;
                            /*
                             * [DEPUIS 09-11-15] @author BOR
                             *      On utilise un HACK pour faire apparaitre la zone voulue puis on reload.
                             */
                            case "__ERR_VOL_TRD_GONE" :
                                    Kxlib_AjaxGblOnErr({status:401},"error");
//                                    location.reload();
                                break;
                            case "__ERR_VOL_DNY_AKX_AUTH" :
                                    if ( $(".jb-tqr-btm-lock").length ) {
                                        $(".jb-tqr-btm-lock").removeClass("this_hide");
                                        $(".jb-tqr-btm-lock-fd").removeClass("this_hide");
                                        
                                        /*
                                         * ETAPE :
                                         *      On ferme UNQ
                                         */
                                        $(".jb-unq-close-trg").click();
                                    }
                                break;
                            default:
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) && !KgbLib_CheckNullity(datas.return.tds) ) {
                    if ( Kxlib_ObjectChild_Count(datas.return.tds) && w === "old" ) { 
                        /*
                         * [NOTE 24-04-15] @BOR
                         * On utilise maintenant la méthode évenement
                         */
                        var rds = [datas.return];
                        $(s).trigger("datasready",rds);
                    } else if ( w === "new" ) {
                        /*
                         * [NOTE 24-04-15] @BOR
                         * On utilise maintenant la méthode évenement
                         */
                        var rds = [datas.return];
                        $(s).trigger("datasready",rds);
                    } else {
                       /*
                        * [DEPUIS 01-06-15] @BOR
                        * [DEPUIS 17-07-15] @BOR
                        *   On effectue les opérations que dans le cas de "old"
                        */
                        if ( w === "old" ) {
                            var m__ = Kxlib_getDolphinsValue("ART_NOONE_GEN_PAGE");
                             $(".jb-trpg-loadm-trg").text(m__);
                             $(".jb-trpg-loadm-trg").addClass("EOP");
                        }
                        
                        /*
                         * [DEPUIS 11-07-15] @BOR
                         *      (1) On masque le message qui demande de patienter.
                         *      (2) On vérifie si UNQ est ouvert et qu'il s'agit du derner Article.
                         *      Dans ce cas, on masque la flèche NEXT.
                         */
                        if ( w === "old" ) {
                            $(".jb-unq-nav-btn[data-dir='next']").find(".jb-unq-nav-btn-wait").addClass("this_hide");
                            var sl = (! $(".jb-unq-art-mdl").data("item") && $(".jb-unq-art-mdl").data("item").replace(/\[.*,(.*)\]/g,"$1") ) ? null : $(".jb-unq-art-mdl").data("item").replace(/\[.*,(.*)\]/g,"$1");
                            if ( $(".jb-unq-art-mdl").hasClass("active") && sl && $(sl) && $(sl).length && $(".jb-unq-bind-art-mdl").last().data("item") === $(sl).data("item") ) {
                                $(".jb-unq-nav-btn[data-dir='next']").addClass("this_hide");
                            }
                        }
                        
                        $(s).trigger("operended");
                        return;
                    }
                } else {
                    _f_Rst_SglNwArt();
                    
                    /*
                     * [DEPUIS 01-06-15] @BOR
                     * [DEPUIS 17-07-15] @BOR
                     *    On effectue les opérations que dans le cas de "old"
                     */
                    if ( w === "old" ) {
                        var m__ = Kxlib_getDolphinsValue("ART_NOONE_GEN_PAGE");
                        $(".jb-trpg-loadm-trg").text(m__);
                        $(".jb-trpg-loadm-trg").addClass("EOP");
                    }
                    $(s).trigger("operended");
                    
                    /*
                     * [DEPUIS 11-07-15] @BOR
                     *      (1) On masque le message qui demande de patienter.
                     *      (2) On vérifie si UNQ est ouvert et qu'il s'agit du derner Article.
                     *      Dans ce cas, on masque la flèche NEXT.
                     */
                    if ( w === "old" ) {
                        $(".jb-unq-nav-btn[data-dir='next']").find(".jb-unq-nav-btn-wait").addClass("this_hide");
                        var sl = (! $(".jb-unq-art-mdl").data("item") && $(".jb-unq-art-mdl").data("item").replace(/\[.*,(.*)\]/g,"$1") ) ? null : $(".jb-unq-art-mdl").data("item").replace(/\[.*,(.*)\]/g,"$1");
                        if ( $(".jb-unq-art-mdl").hasClass("active") && sl && $(sl) && $(sl).length && $(".jb-unq-bind-art-mdl").last().data("item") === $(sl).data("item") ) {
                            $(".jb-unq-nav-btn[data-dir='next']").addClass("this_hide");
                        }
                    }

                    return;
                }
            } catch (ex) {
//                alert("TRPG_CHECK_PDPOSTS => "+e.message);
//                $(".jb-new-art-bar").addClass("this_hide");
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                if ( isat !== true ) { //[DEPUIS 30-08-15]
                    Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                }
                return;
            }
            
        };

        var onerror = function (a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            alert("AJAX ERR : "+this.Ajax_CheckPdPosts.urqid);
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            Kxlib_AjaxGblOnErr(a,b);
            return;
        };
        
        /*
        var atmp_ai = "7fbbjo19m"; //DEV, TEST, DEBUG
        var atmp_at = "1439652550549"; //DEV, TEST, DEBUG
        ai = atmp_ai;
        at = atmp_at;
        //*/
        var toSend = {
            "urqid": _Ax_ChckPdPsts.urqid,
            "datas": {
                "ai"    : ai,
                "at"    : at,
                "ti"    : ti,
               /*
                * [DEPUIS 09-11-15] @author BOR
                */
                "hmt"   : hmt,
                "w"     : w
            }
        };
        
        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_ChckPdPsts.url, wcrdtl : _Ax_ChckPdPsts.wcrdtl });
    };
    
    
    /**************************************************************************************************************************************************************************/
    /******************************************************************************* VIEW SCOPE *******************************************************************************/
    /**************************************************************************************************************************************************************************/
   var _f_TglSpnr = function (shw) {
       if ( shw ) {
           $(".jb-trpg-loadm-trg").addClass("this_hide");
           $(".jb-trpg-loadm-spnr").removeClass("this_hide");
       } else {
           $(".jb-trpg-loadm-spnr").addClass("this_hide");
           $(".jb-trpg-loadm-trg").removeClass("this_hide");
       }
   };
     
    var _f_ShwWtPan = function () {
//    this._ShowWaitPan = function () {
        try {
            
            var m = $("<p/>").attr({
                "id": "na-box-nw-pwt-msg"
            });
            var ch1 = $("<span/>").attr({
                "id": "na-box-nw-pwt-m-mn"
            }).text("Patientez ");
            var ch2 = $("<span/>").attr({
                "id": "na-box-nw-pwt-m-cn"
            }).text("...");
            
            $(m).append(ch1).append(ch2);
            
            $(".jb-na-box-pwt").append(m);
            
            $(".jb-na-box-pwt").removeClass("this_hide");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_HdWtPan = function () {
//    this._HideWaitPan = function () {
        $(".jb-na-box-pwt").addClass("this_hide");
        $(".jb-na-box-pwt").children().remove();
    };
    
    var _f_OnHvrArt = function (x,ih) {
//    this.HandleHoverOnArticle = function () {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
                    
            /*
             * [DEPUIS 24-11-15] @author BOR
             *
            var am = $(x).closest(".jb-mdl-tr-post-in-list");
            if ( ih ) {
                $(am).find(".fcb_img_link").addClass("fcb_img_link_hover");
                $(am).find(".bot_fade").addClass("bot_fade_sp");
                $(am).find(".mdl-a-p-r-cat-p").addClass("mdl-a-p-r-cat-p-full");
            } else {
                $(am).find(".fcb_img_link").removeClass("fcb_img_link_hover");
                $(am).find(".bot_fade").removeClass("bot_fade_sp");
                $(am).find(".mdl-a-p-r-cat-p").removeClass("mdl-a-p-r-cat-p-full");
            }
            /*
            $(x).children(".fcb_img_link").toggleClass("fcb_img_link_hover");
            $(x).parent().children(".bot_fade").toggleClass("bot_fade_sp");
        //    $(x).parent().parent().children(".mdl-a-p-r-cat-p").toggleClass("mdl-a-p-r-cat-p-full");
            $(x).parent().parent().children().find(".mdl-a-p-r-cat-p").toggleClass("mdl-a-p-r-cat-p-full");
            //*/        
                //    $(x).children("span").toggleClass("soft_fade");
        //    $(x).children("span").toggleClass("hard_fade");
        //    $(x).children(".bot_fade").toggleClass("bot_fade_sp");
        
        
            /*
             * [DEPUIS 23-11-15] @author BOR
             *      On gère le cas des bouton de partage sur les réseaux sociaux.
             *
            if ( $(x).has(".jb-tqr-artmdl-shron-tgr").length ) {
                if ( $(x).find(".jb-tqr-artmdl-shron-tgr").first().hasClass("this_hide") ) {
                    $(x).find(".jb-tqr-artmdl-shron-tgr").removeClass("this_hide");
                } else {
                    $(x).find(".jb-tqr-artmdl-shron-tgr").addClass("this_hide");
                }
            }
            //*/
            
            var am = $(x).closest(".jb-mdl-tr-post-in-list");
            if ( ih ) {
                $(am).find(".fcb_img_link").addClass("fcb_img_link_hover");
                $(am).find(".bot_fade").addClass("bot_fade_sp");

                /*
                 * [DEPUIS 02-06-16]
                 */
                if ( $(am).find(".jb-tqr-artml-onhvr-tm").length ) {
                    $(am).find(".jb-tqr-artml-onhvr-tm").removeClass("this_hide");
                }
                if ( $(am).find(".jb-tqr-am-ax-box").length ) {
                    $(am).find(".jb-tqr-am-ax-box").removeClass("this_hide");
                }
            } else {
                $(am).find(".fcb_img_link").removeClass("fcb_img_link_hover");
                $(am).find(".bot_fade").removeClass("bot_fade_sp");
                
                /*
                 * [DEPUIS 02-06-16]
                 */
                if ( $(am).find(".jb-tqr-artml-onhvr-tm").length ) {
                    $(am).find(".jb-tqr-artml-onhvr-tm").addClass("this_hide");
                }
                if ( $(am).find(".jb-tqr-am-ax-box").length ) {
                    $(am).find(".jb-tqr-am-ax-box").addClass("this_hide");
                }
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_AkxNewDeck = function (x,op) {
//    this.AccessNewDeck = function (a) {
        try {
            
            /*
             * [DEPUIS 20-05-16]
             */
            if ( !KgbLib_CheckNullity(x) && $(x).data("lk") === 1 ) {
                return;
            }
            /*
             * [DEPUIS 20-05-16]
             */
            if ( ( $(".jb-acc-nwtrart").data("lk") === 1 | $(".jb-na-box-nw-clz-trg").data("lk") === 1 ) && ! ( op && "_force_inner_needed" ) ) {
                return;
            }
            
            if ( op === true || ( KgbLib_CheckNullity(op) && $("#nwtrdart-box").hasClass("this_hide") ) ) {
                var TR = new Trend(false);
                
                $("#trpg-art-nest").addClass("EMPTY"); //[DEPUIS 02-06-15] @BOR
                $("#nwtrdart-box").stop(true, true).hide().removeClass("this_hide").fadeIn();
                $(".jb-na-box-input-txt").focus();
                $(".jb-na-box-nw-rst").removeClass("this_hide");
                _f_NoOnePg(true);
                
                var elm = $(".jb-trpg-art-nest").find(".jb-mdl-tr-post-in-list");
                TR._f_WColSlip(elm,"btm");
            } else {
                var TR = new Trend(false);
                
                var elm = $(".jb-trpg-art-nest").find(".jb-mdl-tr-post-in-list");
                TR._f_WColSlip(elm,"top");
                
                $("#nwtrdart-box").stop(true, true).fadeOut().addClass("this_hide");
                $("#trpg-art-nest").removeClass("EMPTY"); //[DEPUIS 02-06-15@ @BOR
                $(".jb-na-box-nw-rst").addClass("this_hide");
                _f_NoOnePg();
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_NoOnePg = function (frc) {
        /*
         * Permet d'afficher en temps voulu le message indiquant qu'il n'y a pas d'Article et/ou de le retirer.
         */
        if (! $(".jb-whub-mx").length ) { 
            return; 
        }
        
        var anb = $(".jb-mdl-tr-post-in-list").length;
//        Kxlib_DebugVars([anb],true);
        if ( anb | frc ) {
             $(".jb-whub-mx").addClass("this_hide");
        } else {
             $(".jb-whub-mx").removeClass("this_hide");
        }
    };
    
    var _f_CrtThumbnail = function(img, name){
//    this._CreateThumbnail = function(img, name){
        try {
            
            if ( KgbLib_CheckNullity(img) | KgbLib_CheckNullity(name) ) {
                return;
            }
            
            if (! Kxlib_IsSquare(img.height, img.width) ) {
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
                 * [DEPUIS 21-05-16]
                 */
                var at = $(".jb-na-box-input-txt").val();
                
                /*
                 * ETAPE :
                 *      On reset le formulaire pour pouvoir relancer de manière fiable l'opération.
                 */
                _f_RstCreaForm("image");
                _nwImg = null; 
                _nwImg_nm = null;
                delete _nwImg;
                delete _nwImg_nm; 
                
                /*
                 * [DEPUIS 21-05-16]
                 */
                if (! KgbLib_CheckNullity(at) ) {
                    $(".jb-na-box-input-txt").val(at);
                }
                
                /*
                var m = Kxlib_getDolphinsValue("ERR_NWTRART_BADDIMS");
                alert(m);
                //*/
            } else {
                
                if ( ( parseInt(img.height) > _f_Gdf().PrvwMaxH ) || ( parseInt(img.width) > _f_Gdf().PrvwMaxW ) ) {
                    var oo = new Cropper();
                    img = oo.Cropper_ResizeTo(img, _f_Gdf().PrvwMaxH, _f_Gdf().PrvwMaxW);
                }
                
                //On cache l'image d'illustration
                $("#na-box-img-expl-illus").addClass("this_hide");
                
                //On attribue à l'image la classe de style
                $(img).addClass("na-box-img-expl-nwimg");
                
                /* On fait apparaitre l'image miniature */
                $("#nwtrart-sto-img").prepend(img);
                $("#nwtrart-sto-img").hide().removeClass("this_hide").fadeIn(500);
                //*
                
                /* On fait apparaitre le texte signifiant que l'image est chargée */
                //On fait disparaitre le texte initial
                $("#na-box-img-expl-txt").addClass("this_hide");
                //On fait apparaitre le texte selon lequel l'image a bel et bien été chargée
                $("#na-box-img-expl-txt-loaded").stop(true, true).hide().removeClass("this_hide").fadeIn(100);
                
                //alert("url"+ img.url);
                //*/
                
                /* On 'enregistre' l'image pour pouvoir l'envoyer au niveau du serveur */
                //L'image est codée en base64, si on encode pas on aura des erreurs
//            _nwImg = Kxlib_encodeURL(img.src); //LA fonction Kxlib_encodeURL() est obselete => encodeURIComponent
                _nwImg = encodeURIComponent(img.src);
                _nwImg_nm = name;
                /*
                 Kxlib_DebugVars([SOURCE RAW = "+img.src]);
                 Kxlib_DebugVars([SOURCE SECURED = "+Kxlib_encodeURL(img.src)]);
                 //*/
                
                //On signale que l'image est chargée et conforme
                $(".jb-na-box-img-xpln[data-scp='image']").attr("rdy","1");
                
                //On redonne le focus au textarea pour permettre à l'utilisateur de lancer l'ajout en appuyant sur 'Enter'
                $(".jb-na-box-input-txt").focus();
            } 
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_PprTrArt = function (d) {
//    this._PrepareTrendArt = function (d) {
        
         /*
         * On utilise pas Kxlib_ReplaceIfUndefined() (pour les propriétés non définies) car la probabilité d'avoir des données entrantes NULL est ici relativement faible. 
         * * */ 
        try {
            
            //On remplace toutes les valeurs de type null par ""
            d = Kxlib_ReplaceIfUndefined (d);
        
            //On échape le texte
            var desc = Kxlib_Decode_After_Encode(d.msg);
            
//            Kxlib_DebugVars([$(e).find(".mdl-acc-post-txt").length],true);
//            Kxlib_DebugVars([$(e).find(".mdl-acc-post-txt").text(),st],true);
//            return;
            
            var str__;
            if ( d.hasOwnProperty("ustgs") && d.ustgs !== undefined && typeof d.ustgs === "object" ) {
                var istgs__ = [];
                $.each(d.ustgs,function(x,v){
                    var rw__ = [];
                    $.map(v,function(e,x){
                        rw__.push(e);
                    });
                    istgs__.push(rw__.join("','"));
                });
    //            Kxlib_DebugVars([JSON.stringify(istgs__)],true);
                str__ = ( istgs__.length > 1 )? istgs__.join("'],['") : istgs__[0];
                str__ = "['"+str__+"']";
            }
            /*
            if ( d.id === "7fbbjof4" ) {
                alert(d.msg);
            }
            //*/
            var e = "<article id=\"trpg-art-"+d.id+"\" data-item=\""+d.id+"\" data-tr=\""+d.trid+"\" data-atype=\"itr\" ";
            e += " class=\"mdl-tr-post-in-list jb-mdl-tr-post-in-list jb-unq-bind-art-mdl jb-tqr-fav-bind-arml this_invi\" ";
//            e += " data-cache=\"['"+d.id+"','"+d.img+"','"+Kxlib_ReplaceIfUndefined(Kxlib_EscapeForDataCache(d.msg))+"','"+d.trid+"','"+Kxlib_EscapeForDataCache(d.trtitle)+"','"+d.rnb+"','"+d.trhref+"','"+d.prmlk+"'],['"+d.time+"','"+d.utc+"'],['"+d.eval[0]+"','"+d.eval[1]+"','"+d.eval[2]+"','"+d.eval[3]+"','"+d.eval_lt[0]+"','"+d.eval_lt[1]+"','"+d.eval_lt[2]+"','"+d.eval_lt[3]+"'],['"+d.ueid+"','"+d.ufn+"','"+Kxlib_ValidUser(d.upsd)+"','"+d.uppic+"','"+d.uhref+"'],['"+Kxlib_ReplaceIfUndefined(d.myel)+"']\" ";
//            e += " data-cache=\"['"+d.id+"','"+d.img+"','"+Kxlib_ReplaceIfUndefined(d.msg)+"','"+d.trid+"','"+d.trtitle+"','"+d.rnb+"','"+d.trhref+"','"+d.prmlk+"'],['"+d.time+"','"+d.utc+"'],['"+d.eval[0]+"','"+d.eval[1]+"','"+d.eval[2]+"','"+d.eval[3]+"','"+d.eval_lt[0]+"','"+d.eval_lt[1]+"','"+d.eval_lt[2]+"','"+d.eval_lt[3]+"'],['"+d.ueid+"','"+d.ufn+"','"+Kxlib_ValidUser(d.upsd)+"','"+d.uppic+"','"+d.uhref+"'],['"+Kxlib_ReplaceIfUndefined(d.myel)+"']\" ";
            e += " data-cache=\"['"+d.id+"','"+d.img+"','{adesc}','"+d.trid+"','{trtle}','"+d.rnb+"','"+d.trhref+"','"+d.prmlk+"'],['"+d.time+"','"+d.utc+"'],['"+d.eval[0]+"','"+d.eval[1]+"','"+d.eval[2]+"','"+d.eval[3]+"','"+d.eval_lt[0]+"','"+d.eval_lt[1]+"','"+d.eval_lt[2]+"','"+d.eval_lt[3]+"'],['"+d.ueid+"','"+d.ufn+"','"+Kxlib_ValidUser(d.upsd)+"','"+d.uppic+"','"+d.uhref+"'],['"+Kxlib_ReplaceIfUndefined(d.myel)+"']\" ";
            e += " data-with=\""+Kxlib_ReplaceIfUndefined(str__)+"\"";
            e += " >";
            e += "<div class=\"jb-tqr-cldstrg this_hide\">";
            e += "<span class=\"jb-tqr-csg-elt\" data-item='adsc'></span>";
            e += "<span class=\"jb-tqr-csg-elt\" data-item='trtle'></span>";
            e += "</div>";
            e += "<div class=\"mdl-acc-post-img\">";
            e += "<div class=\"fcb_img\">";
//            e += "<span class=\"fcb_img_link\" href=\"\"></a>";
            e += "<span class=\"fcb_img_link\" >";
//            e += "<a class=\"tqr-artmdl-shron-tgr cursor-pointer jb-tqr-artmdl-shron-tgr this_hide\" data-art-mdl=\"on_page\" data-action=\"amdl_sharon_fb\" title=\"Partager sur Facebook\"></a>";
//            e += "<a class=\"tqr-artmdl-shron-tgr cursor-pointer jb-tqr-artmdl-shron-tgr this_hide\" data-art-mdl=\"on_page\" data-action=\"amdl_sharon_twr\" title=\"Partager sur Twitter\"></a>";
            
            e += "<div class=\"tqr-artml-tmonhvr jb-tqr-artml-onhvr-tm this_hide\" data-atype=\"trpg\"></div>";
            
            e += "<div class=\"tqr-artmdl-asdxtr-box jb-tqr-am-ax-box this_hide\">";
                if (! d.isrtd ) {
                    e += "<a class=\"tqr-artmdl-shron-tgr cursor-pointer jb-irr\" href=\"javascript:;\" data-art-mdl=\"on_page\" data-action=\"favorite\" title=\"Mettre en favori\"></a>";
                } else if ( d.hasfv ) {
                    e += "<a class=\"tqr-artmdl-shron-tgr cursor-pointer jb-tqr-art-abr-tgr\" data-art-mdl=\"on_page\" data-action=\"unfavorite\" data-reva=\"favorite\" data-revt=\"Mettre en favori\" title=\"Retirer des favoris\"></a>";
                } else {
                    e += "<a class=\"tqr-artmdl-shron-tgr cursor-pointer jb-tqr-art-abr-tgr\" data-art-mdl=\"on_page\" data-action=\"favorite\" data-reva=\"unfavorite\" data-revt=\"Retirer des favoris\" title=\"Mettre en favori\"></a>";
                }
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
            e += "<img class=\"fcb_img_img\" height=\"372\" width=\"372\" src=\""+d.img+"\" alt=\""+Kxlib_Decode_After_Encode(desc)+"\" />";
            e += "</div>";
            e += "<div class=\"bot_fade\">";
            e += "<span class=\"bf_com\">";
            e += "<span class=\"bf_comNb jb-unq-react\">"+d.rnb+"</span>";
            e += "<span class=\"jb_b_f_rlib b_f_rLib\" style=\"background: url('"+ Kxlib_GetExtFileURL("sys_url_img","r/r3.png",["_WITH_ROOTABS_OPTION"]) +"') no-repeat;\"></span>";
            e += "</span>";
            e += "<span class=\"bf_cool jb-csam-eval-oput\">";
            e += "<span class=\"bf_cool_nb\">"+d.eval[3]+"</span>";
//            e += "<span class=\"bf_cool_ico\">c<i>!</i></span>";
            e += "</span>";
            /*
                e += "<span class=\"bf_cool jb-csam-eval-oput\" data-cache=\"["+d.eval[0]+","+d.eval[1]+","+d.eval[2]+","+d.eval[3]+","+d.myel+"]\">";
                e += "<span>"+d.eval[3]+"</span>";
                e += "&nbsp;c<i>!</i>";
                e += "</span>";
                e += "<span class=\"bf_comNb jb-unq-react\">"+d.rnb+"</span>";
                e += "<span class=\"bf_comLib\">reactions</span>";
            //*/
            e += "</div>";
            e += "</div>";
            e += "<p class=\"mdl-acc-post-txt jb-mdl-acc-post-txt\"></p>";
            e += "<div class=\"mdl-acc-post-bottom map-v1\">"; 
            e += "<div class=\"map-specs\">";
            /*
            e += "<div id=\"tqr-art-actbar-mx\" class=\"jb-tqr-art-abr-mx\" data-scp=\"am-trpg-itr\">";
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
            //*/
            e += "<span class=\'css-tgpsy kxlib_tgspy\' data-tgs-crd=\'"+d.time+"\' data-tgs-dd-atn=\'\' data-tgs-dd-uut=\'\'>";
            e += "<span class=\'tgs-frm\'></span>";
            e += "<span class=\'tgs-val\'></span>";
            e += "<span class=\'tgs-uni\'></span>";
            e += "</span>";
            e += "</div>";
            e += "<div class=\"map-user\">";
            e += "<p class=\"map-user-user-max\">";
            e += "<a href=\"/"+Kxlib_ValidUser(d.upsd)+"\" class=\"tr_upost_user_owner\">";
            e += "<span class=\"map-user-psd\">"+Kxlib_ValidUser(d.upsd)+"</span>";
            e += "<img class=\"map-user-img\" height=\"42\" width=\"44\" src=\""+d.uppic+"\" />";
            e += "</a>";
            if ( d.hasOwnProperty("ucontb") && d.ucontb ) {
                e += "<span class=\"map-user-contrib\"><span>"+d.ucontb+"<span> post</span>";
            }
            e += "</p>";
            e += "</div>";
            e += "</div>";
            e += "</article>";
            e = $.parseHTML(e);
            
            $(e).find(".jb-mdl-acc-post-txt").text(desc);
            
            //On traite le texte pour le rendre plus sécurisé et lui permettre de traiter les cas USERTAG
            /*
            if ( $(e) && !KgbLib_CheckNullity($(e).data("with")) && typeof $(e).data("with") === "string" ) {
//                alert($(e).find(".jb-mdl-acc-post-txt").text()); 
                var w__ = $(e).data("with");
                //On récupère les éléments
                w__ = Kxlib_DataCacheToArray(w__)[0];
                if ( w__ && $.isArray(w__) && w__.length ) {

                    var ps__ = ( $.isArray(w__[0]) ) ? Kxlib_GetColumn(3,w__): [w__[3]];
//                            Kxlib_DebugVars([200,JSON.stringify(w__),JSON.stringify(ps__)], true);
                    var t__ = Kxlib_UsertagFactory(d.msg,ps__,"tqr-unq-user");
//                    var t__ = Kxlib_UsertagFactory(desc,ps__,"tqr-unq-user");
//                    var t__ = Kxlib_UsertagFactory($(e).find(".jb-mdl-acc-post-txt").text(),ps__,"tqr-unq-user");
                    t__ = $("<div/>").html(t__).text();
//                    alert(t__);
//                            t__ = Kxlib_Decode_After_Encode(t__);
//                            Kxlib_DebugVars([206,JSON.stringify(t__)], true);
                    t__ = Kxlib_SplitByUsertags(t__);

                    /*
                     * ETAPE :
                     * Mettre en place la description pour le modèle en page
                     *
                    $(e).find(".jb-mdl-acc-post-txt").html(t__);
                    
                    /*
                     *  ETAPE :
                     * Mettre en place la description pour UNQ
                     *
                    var ad = $("<div/>").html(desc).text();
                    $(e).find(".jb-tqr-csg-elt[data-item='adsc']").text(ad);
                }
            } else {
               /*
                * [DEPUIS 27-04-15] @BOR
                * On insère les données sur la desription et le titre de la Tendance
                *
               //            var m = $("<div/>").html(desc).text();
//                var t = $("<div/>").html(d.trtitle).text();
                $(e).find(".jb-tqr-csg-elt[data-item='adsc']").text(desc);
            }
            //*/
            
            /*
             * [DEPUIS 22-04-16]
             */
//            Kxlib_DebugVars([JSON.stringify(d)],true);
            var hasfv = ( d.hasfv ) ? 1 : 0;
            $(e)
                .data("time",d.time).attr("data-time",d.time)
                .data("hasfv",hasfv).attr("data-hasfv",hasfv)
                .data("vidu",d.vidu).attr("data-vidu",d.vidu)
                .data("ajcache",JSON.stringify(d))
                .data("trq-ver",'ajca-v10').attr("data-trq-ver",'ajca-v10');
        
            /*
             * [DEPUIS 02-06-16]
             *      On ajoute une chaine représentant la DATE qui va s'afficher au niveau de l'ARTICLE
             */
            var adate = new Date(parseFloat(d.time));
            var foo = ("0"+adate.getDate().toString()).slice(-2).concat(".");
            foo += ("0"+adate.getMonth().toString()).slice(-2).concat(".");
            foo += adate.getFullYear().toString().substr(2,2);
            $(e).find(".jb-tqr-artml-onhvr-tm").text(foo);
        
        
            var ustgs = d.ustgs;
            var hashs = d.hashs;

//            Kxlib_DebugVars([desc,hashs,ustgs],true);
            //rtxt = RenderedText
            var rtxt = Kxlib_TextEmpow(desc,ustgs,hashs,null,{
                emoji : {
                    "size"          : 36,
                    "size_css"      : 20,
                    "position_y"    : 3
                }
            });
//                Kxlib_DebugVars([rtxt],true);
            $(e).find(".jb-mdl-acc-post-txt").text("").append(rtxt);
                
            var t_ = $("<div/>").html(d.trtitle).text();
            $(e).find(".jb-tqr-csg-elt[data-item='trtle']").text(t_);
            
            /*
             * [DEPUIS 21-04-16]
             */
            if ( d.vidu ) {
//                $(".fcb_img_link").data("vidu",d.vidu).attr("data-vidu",d.vidu); //[DEPUIS 16-05-16] Je ne comprends pas exactement pourquoi j'ai créé cette ligne
                $(e).find(".fcb_img_link").addClass("vidu");
            }
            
            return e;
        } catch(ex) {
            //TODO : Avertir le serveur
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            return;
        }
    };
    
    var _f_RstCreaForm = function (scp) {
//    this._ResetCreaForm = function () {
        try {
            
            if ( KgbLib_CheckNullity(scp) || scp === "image" || scp === "video" ) {
                /* On reset le formulaire et les autres champs */
                
                //On reset le formulaire
                Kxlib_ResetForm("#nwtrdart-box-form");
                
                //On reset l'input File
                $("#na-box-img-catcher").val("");
                
                //On retire le thumbnail
                $("#nwtrart-sto-img").addClass("this_hide").find(".na-box-img-expl-nwimg").remove();
                
                //On rétablit le thumbnail originel
                $("#na-box-img-expl-illus").removeClass("this_hide");
                
                //On remet le compteur à sa valeur originelle
                $("#innwtrdart-char-cn").text($("#innwtrdart-char-cn").data("init"));
                
                //On s'assure que la bordure n'est plus rouge
                $(".jb-na-box-input-txt").removeClass("error_field");
                
                //On retire le bandeau rouge du nombre de caractères
                $("#innwtrdart-char-cn").removeClass("red");
                
                /* On fait apparaitre le texte originel au niveau du catcheur */
                $("#na-box-img-expl-txt-loaded").stop(true,true).fadeOut().addClass("this_hide");
                
                //On fait apparaitre le texte initiale
                $("#na-box-img-expl-txt").removeClass("this_hide");

                //On reset l'élément qui permet d'indiquer que l'image est chargée.
                $("#na-box-img-explain").attr("rdy","");
                
                /*
                 * [DEPPUIS 21-05-16]
                 */
                $(".jb-na-box-img-xpln[data-scp='video']").addClass("this_hide");
                $(".jb-na-box-img-xpln[data-scp='image']").removeClass("this_hide");
            }
                
            if ( KgbLib_CheckNullity(scp) || scp === "video" ) {
                //On reset l'élément qui permet d'indiquer que la VIDEO est chargée.
                $(".jb-na-box-img-xpln[data-scp='video']").attr("rdy","");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ShwNwTrArt = function (d,isc,ror) {
        //ror = ReOrganize
//    this.DisplayNewTrendArticle = function (d,isc) {
        //isc = IsCreation la méthode peut être utilisée pour ajouter de nouveaux Articles hors d'un contexte de création. ...
        //... Dans ce cas, on évite de Reset le formulaire de création et de faire d'autres vérifications
        try {
            
            if ( Kxlib_getDolphinsValue(d) ) {
                return;
            }
            
            if (!isc) {
                //On vérifie si l'image n'est pas déjà présente
//            alert(_f_ChkAldyXsts(d));
                if ( _f_ChkAldyXsts(d) ) {
//                alert("inlist");
                    return;
                }
            } else {
                _f_RstCreaForm();
                //On reset l'image sauvegardée pour encore mieux sécuriser la création de l'article
                _nwImg = null; 
                /*
                 * [DEPUIS 21-05-16]
                 *      On resert la video
                 */
                _nwVid = null;
            }
            
            //On crée le modèle
            var b = _f_PprTrArt(d);
            
            //On lie les évènements clés
            b = _f_NwTrArt_ReBind(b);
            
            
            /*
             * [NOTE 22-04-15] @BOR
             * ETAPE :
             *      On ajout l'élément en tête de queue
             */
            if ($("#nwtrdart-box").length) {
                $("#nwtrdart-box").after(b);
            } else {
                $(".jb-trpg-art-nest").prepend(b);
            }
            
            /*
             * [DEPUIS 16-05-16] @author BOR
             *      On BIND pour FAV
             */
            TqOn("RBD_FR_FV",b);
            
//        $(".jb-trpg-art-nest").prepend(b);
            
//        _f_TidyUpArtBczOfNew($(b));
            
            /*
             * [NOTE 22-04-15] @BOR
             * ETAPE :
             *      On reshuffle pour replacer les éléments
             * [DEPUIS 21-05-15] @BOR
             *      Prise en compte de ROR et ajout de SetTimeout
             */
            if ( ror ) {
                setTimeout(function(){
                    var TR = new Trend(false);
//                    TR._f_FrmtDsc(b); //[DEPUIS 22-04-16]
                    TR._f_Shuffle();
                },500);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_ShwPdTrArt = function (d,l,fe) {
//    this._DisplayPdTrArt = function (d,l,fe) {
        //fe : FirstElement permet de renvoyer l'élément num1 afin de procéder au ScrollTop
        /* Ajouter un élément à la fin de la colonne East */
        try {
            
            if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(l) | !$(l).length ) { 
//            if ( Kxlib_getDolphinsValue(d) | Kxlib_getDolphinsValue(l) ) { 
                return;
            }
            
            if ( _f_ChkAldyXsts(d) ) {
                return;
            }
            
//        alert("DEBUG => "+loc);
            
            //On crée le modèle
            var b = _f_PprTrArt(d);
            
            //On lie les évènements clés
            b = _f_NwTrArt_ReBind(b);
            /*
             if ( loc === this.__LOC_EAST )
             $(b).appendTo(this.__LOC_EAST);
             else
             $(b).appendTo(this.__LOC_WEST);
             //*/
            $(b).appendTo(l);
//            $(b).hide().appendTo(l).fadeIn(500);

            /*
             * [DEPUIS 16-05-16] @author BOR
             *      On BIND pour FAV
             */
            TqOn("RBD_FR_FV",b);
            
            if (fe) {
                return $(b);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /*
    var _f_ShwPdTrArt = function (d,l,fe) {
//    this._DisplayPdTrArt = function (d,l,fe) {
        //fe : FirstElement permet de renvoyer l'élément num1 afin de procéder au ScrollTop
        /* Ajouter un élément à la fin de la colonne East 
        
        if ( Kxlib_getDolphinsValue(d) | Kxlib_getDolphinsValue(l) ) { 
            return;
        }
        
        if ( _f_ChkAldyXsts(d) ) { return; }
        
//        alert("DEBUG => "+loc);
        
        //On crée le modèle
        var b = _f_PprTrArt(d);
               
        //On lie les évènements clés
        b = _f_NwTrArt_ReBind(b);
        /*
        if ( loc === this.__LOC_EAST )
            $(b).appendTo(this.__LOC_EAST);
        else
            $(b).appendTo(this.__LOC_WEST);
        //
        $(b).hide().appendTo(l).fadeIn(500);
        
        if ( fe ) { return $(b); }
    };
    //*/
    /******************************************************************************************************************************************************************************/
    /******************************************************************************* LISTERNS SCOPE *******************************************************************************/
    /******************************************************************************************************************************************************************************/
    //Appeler cette méthode sans argument ici, est synonyme d'ouvrir la box étant donné que de base elle est fermée.
//    gt.AccessNewDeck();
    
    /************  LOAD DES ARTICLES *************/    
    //On vérifie si de nouveaux articles sont disponibles
    setInterval(function(){
        _f_ChkNwArts(true);
    },_f_Gdf().__NWCHECK_LOOP);
    
    //On déclenche manuellement l'affichage des nouveaux éléments ultérieurs
    $(".jb-n-a-b-trig").click(function(e){
        Kxlib_PreventDefault(e);
                
        _f_ShwToLdAs();
    });
    
    //On déclenche manuellement l'affichage des nouveaux éléments antérieurs
    $(".jb-trpg-loadm-trg").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_GtPdArts(this);
    });
    
    $(window).scroll(function(e){
        /*    
        //Debug
        var h = $('body').height() - $(this).height();
        var txt = "Hauteur -> "+h+"; Theory -> "+$('#p-l-c-main').offset().top+"; Scroll -> "+$(this).scrollTop();
//        Kxlib_DebugVars([xt]);
        $("#ctrl-s-datas").html(txt);
        //*/
        var sp = 0;
        if ( Kxlib_IsIE() ) { sp = 1; }
            
        var l = $('body').height() - $(this).height() + sp;
//        alert("Hauteur -> "+l+"; Scroll -> "+$(this).scrollTop());
        if ( $(this).scrollTop() === l ) { 
//            _f_GtPdArts; 
        }
    });
    
    
    /********************************************/
    
    $(".jb-nwtrart").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_AddNew(this);
    });
    
    $(".jb-na-box-input-txt").on("focus blur",function(){
//        Kxlib_DebugVars([FOCUS+BLUR => ",$(this).val().length]);
        if ( $(this).hasClass("error_field") ) {
            $(this).addClass("no_outline");
        } else {
            $(this).removeClass("no_outline");
        }
    });
    
    $("#na-box-img-catcher").change(function(){
        var f = this.files;
                
        _f_TreatIptFiles(f);
    });
    
    /*
     * 
     */
    $(".jb-tqr-skycrpr-snit[data-target='trpgnewbx']").on("change",function(e,file,name){
         
//        Kxlib_DebugVars([name, file.width, file.height],true);
        _f_CrtThumbnail(file,name);
    });
    
    $(".jb-acc-nwtrart").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_AkxNewDeck();
    });
    
    $("#whub_specs_ask_new").click(function(e){
       Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
       
       _f_AkxNewDeck();
    });
    
    $(".jb-na-box-input-txt").keypress(function(e){
        var k = e.keyCode || e.which;
        if ( k === 13 ) {
            _f_AddNew();
            $(".jb-na-box-input-txt").blur();
        }
    });
    
    $(".jb-na-box-nw-rst-trg").click(function(e){
       Kxlib_PreventDefault(e);
       
       _f_Abort(this);
    });
    
    $(".jb-na-box-nw-clz-trg").click(function(e){
       Kxlib_PreventDefault(e);
       
       _f_AkxNewDeck(this,false);
    });
    
    /*
     * [DEPUIS 24-11-15] @author BOR
     */
    $(".jb-tqr-artmdl-shron-tgr").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        _f_Sharon(this);
    });
    
    $(".jb-trpg-nwabx-git").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_ImgErrs("hid-err",this);
    });
}

new NewTrdArt();