
function BrainHandler () {
    var gt = this;
    /**
     * o: open; c: close; kcs: keep_current_status; ksds: keep_server_default_status;
     * kcs: étant donnée la complexité de la mise en oeuvre, la fonctionnalité ne sera disponible que pour la prochaine version
     * m: menu; l:list; r: raw;
     */
//    var _bnM_DfSts = "c";
//    this.brainM_DefStatus = "c";
    var _bnM_Sts = "";
//    this.brainM_Status = "";
    var _bnM_DfMd = "m";
//    this.brainM_DefMode = "m";
    var _bnM_Md = "";
//    this.brainM_Mode = "";
    //close sera toujours la valeur par défaut !
    var _bnS_DfSts = "c";
//    this.brainS_DefStatus = "c";
    var _bnS_DfThm = "sam";
//    this.brainS_DefTh = "sam";
    /**
     * new: new
     * not: notif
     * foll: follower
     * folg: following
     * sam: samples
     * fil: filters;
     * htu: how to use;
     * exp: explore
     */
    var _bnS_thm = "";
//    this.brainS_th = "";
//    var _dfltDscId = "brain_desc-gen";
//    this.defaultDescId = "brain_desc-gen";
    
    /************************************************************************************************************************************************************/
    /*********************************************************************** SCOPE SCOPE ************************************************************************/
    /************************************************************************************************************************************************************/
    
    var _f_Gdf = function () {
        var dt = {
            _bnM_DfSts : "c",
            _dfltDscId : "brn-mn-dsc-gen",
            _stgsOpts : ["_PFOP_BRAIN_ALWZ_OPN","_PFOP_PSMN_EMLWHN_NW","_PFOP_PSMN_EMLFOR_WKACTY","_PFOP_INFO_EMLWHN_NWTQRVnSECU","_PFOP_INFO_EMLFOR_WKBESTPUB"]
        }; 
        
        return dt;
    };
    
    var _f_ChkNoArt = function () {
//    this.CheckNoArticle = function () {
        //On affiche le message destiné à inciter l'user a ajouté un article ...
        //... ou inciter à le faires
        //S'il n'y a aucun article au niveau EAST aussi bien que WEST on affiche le bloc 'incitatif'
        var east = $("#feeded_e_list_list > .feeded_com_bloc_figs").length;
        var west = $("#feeded_w_list_list > .feeded_com_bloc_figs").length;

        if ( !east && !west && $("#where_have_you_been") && $("#brain_maximus").hasClass("this_hide") ) {
            $("#where_have_you_been").removeClass("this_hide");
        } else {
            $("#where_have_you_been").addClass("this_hide");
        }
    };
    
    var _f_OnClose = function () {
//    this.CloseBrainSys = function () {
        //Fermeture de Master
        if ( !$("#brain_maximus").hasClass("this_hide") ) {
            $("#brain_maximus").addClass("this_hide");
        }
        //Fermeture de Slave
        if ( !$("#slave_maximus").hasClass("this_hide") ) {
            $("#slave_maximus").addClass("this_hide");
        }
        
        //Apparition de "OPEN BRAIN"
        $(".jb-brn-opn-tgr").removeClass("this_hide");
        
        //On change le statut de ouvert/fermé de BRAIN
        $("#brain_maximus").data("isopen","0");
        
        //Permet de signaler au module NOTIFIZING la fermeture de BRAIN.
        $("#opref").change();
        
        //Doit on afficher le message incitatif?
        _f_ChkNoArt();
    };
    
    var _f_OnOpen = function () {
//    this.OpenBrainSys = function () {
        if ( $("#brain_maximus").hasClass("this_hide") ) {
            $("#brain_maximus").removeClass("this_hide");
        }
        
        if ( $("#slave_maximus").hasClass("this_hide") ) {
            $("#slave_maximus").removeClass("this_hide");
        }
        
        //Disparition de "OPEN BRAIN"
        $(".jb-brn-opn-tgr").addClass("this_hide");
        
        //Disparition de la bulle de notification (si elle existe pas ce n'est pas grave
        $("#opb_signal_new").addClass("this_hide");
        
        //On change le statut de ouvert/fermé de BRAIN
        $("#brain_maximus").data("isopen","1");
        
        //Doit on afficher le message incitatif?
        _f_ChkNoArt();
    };
    
    //Principalement utilisé par Init();
    var _f_ChkBMSts = function(a) {
//    this.CheckBMStatus = function(arg) {
        //On verifie la version du serveur
        //Si aucune information n'est donnée, on passe on continue le process normalement.
        try {
            
            var ser = $("#brain_maximus").data("sds");
            
            if (! KgbLib_CheckNullity(ser) ) {
                _bnM_Sts = ser;
            } else {
                //Si on a pas d'instruction de la part du serveur, ...
                // ... On sert la variable déclarée dans Init ... (Sert souvent pour les tests)
                // ... Sinon, on prend la valeur par défaut définie dans BRAINOMG !
                _bnM_Sts = ( KgbLib_CheckNullity(a) ) ? _f_Gdf()._bnM_DfSts : a;
            }
            
            if ( _bnM_Sts === "c" ) {
                _f_OnClose();
            } else if ( _bnM_Sts === "o" ) {
                _f_OnOpen();
            } else {
                //Ne rien faire pour l'instant
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true); 
        }

    };
    
    var _f_ClzAlFksBnThms = function () {
//    this.CloseAllFocusBrainThemes = function () {
        if ( $(".brain_focus").length > 1 ) {
            //alert("ERR: Only one theme should be focused !");
            return;
        }
        
        var _id = "#"+$(".brain_focus").attr('id');

        $(_id).addClass("this_hide");
        $(_id).removeClass("brain_focus");
    };
    
    var _f_ShwBnThm = function (a) {
//    this.HighlightRightBrainTheme = function (a) {
        //Pour l'instant on show juste le bon block present dans HTML
        //Par la suite, nous allons demander le bon block au serveur
        a = Kxlib_ValidIdSel(a);
        var _id = Kxlib_ValidIdSel($(a).data("slave")); 
        //alert("target: "+_id);
        $(_id).addClass("brain_focus");
        $(_id).removeClass("this_hide");
    };
    
    var _f_TglMstrMd = function (a) {
//    this.ToggleMasterMode = function (a) {
        /**
         * Change le mode de Master a la demande.
         * Ne rien spécifier renvoie vers le mode par defaut;
         */
        try {
            
            //[NOTE au 22-06-14] : Avant on avait une variable 'm' qui ne servait à rien ?
            if ( KgbLib_CheckNullity(a) ) {
                a = _bnM_DfMd;
            }
            
            $(".brain_m_elmnt").addClass("this_hide");
            
            switch (a) {
                case "r":
                    break;
                case "m":
                    $("#brain_listMenu").removeClass("this_hide");
                    break;
                case "l":
                    $("#brain_ma_modlist").removeClass("this_hide");
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_PprMstrInLst = function(a){
//    this.PrepareMasterInList = function(a){
        if ( KgbLib_CheckNullity(a) ) {
            return;
        }
        try {
            
            switch (a) {
                case "brain_wrh_trends_choices":
                case "brain_wrh_notifs_choices":
                case "brain_wrh_gbk_choices":
                        $("#brain_ma_modlist_l").html("");
                        var _h = $(Kxlib_ValidIdSel(a)).html();
                        $("#brain_ma_modlist_l").html(_h);
                        //*
                        $(".brainM_submenu_elmnt").off().click(function(e) {
                            Kxlib_PreventDefault(e);
                            gt._f_ClkOnMn(e.target);
                        });
                        //*/
                        if ( $(Kxlib_ValidIdSel(a)).attr("data-title") ) {
                            var _ti = $(Kxlib_ValidIdSel(a)).data("title");
                            $("#brain_ma_modlist_title").html(_ti);
                        }
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_HdlMstrOprs = function(a) {
//    this.HandleMasterOperations = function(a) {
        //arg= id du bloc de sous menu dans warehouse
        if ( KgbLib_CheckNullity(a) ) {
            return;
        }
        try {
            
            switch (a) {
                case "brain_wrh_trends_choices":
                case "brain_wrh_notifs_choices":
                case "brain_wrh_gbk_choices":
                    _f_TglMstrMd("l");
                    _f_PprMstrInLst(a);
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_OnClickOnBk = function(x) {
//    this.HandleClickOnBack = function(x) {
        /**
         * On peut Back vers :
         * - Un mode (raw, menu, list)
         * - Un mode precedent (cad si avant j'etais en mode list et qu'il s'agissait d'une liste pour GBk, bah j'y retourne)
         *   (cela est possible grace à la sauvegarde du mode précedent en mémoire) 
         */
        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
        try {
            
            var _m = Kxlib_ValidIdSel($(x).data("mode"));
//        var _m = "#"+$(x).data("mode");
            
            $(".brain_m_elmnt").addClass("this_hide");
            $(_m).removeClass("this_hide");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    
    var _f_CnThmChld = function(x){
//    this.CountThemeChildren = function(arg){

        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
        
        try {
            var sl = Kxlib_ValidIdSel(x);
            //alert(inrarg);
            var _c = $(sl).children(".in_slave_list").children(".brainS_UnikMdl").length;
            //alert("count="+_c);
            $(sl).children(".brain_f_title").children(".jb-counter").html(_c);
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_HdlClkOnMn_Ext = function(x, _id) {
//    this.HandleClickOnMenu_Ext = function(a, _id) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(_id) ) {
                return;
            }
            
            if ( !KgbLib_CheckNullity($(x).data("lk")) && $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            _f_ClzAlFksBnThms();
            _f_ShwBnThm(_id);
            var _th = $(x).attr('data-slave');
            
            var cb = "";
            switch (_id) {
                case "brain_submenu_mytrch":
                case "brain_submenu_follgtrch":
                        if ( _th === "brain_th-follgtrch" ) {
                            cb = "ftrs";
                            /*
                             * [DEPUIS 24-06-15] @BOR
                             */
                            //On vide les entrées
                            $("#brain_list_follgtrs").find(".brainS_UnikMdl").remove();
                            //On retire le "NoOne" s'il existe
                            _f_NoOne("FLGTRS",false);
                            //On affiche le spinner
                            _f_HidSpnr("FLGTRS",true);
                            //On remet le "counter" à 0
                            $(".jb-brn-lsts-bmx[data-scp='mytrs']").find(".jb-bn-lsts-nb").text(0);
                
                            _f_Srv_GMyFTr(_th,cb,x);
                        } else {
                            cb = "mtrs";
                            /*
                             * [DEPUIS 24-06-15] @BOR
                             */
                            $("#brain_list_mytrs").find(".brainS_UnikMdl").remove();
                            //On retire le "NoOne" s'il existe
                            _f_NoOne("MYTRS",false);
                            //On affiche le spinner
                            _f_HidSpnr("MYTRS",true);
                            //On remet le "counter" à 0
                            $(".jb-brn-lsts-bmx[data-scp='flgtrs']").find(".jb-bn-lsts-nb").text(0);
                            
                            _f_Srv_GMyTr(_th,cb,x);
                        }
                    break;
                case "brain_menu_folls":
                        cb = "mflwrs";
                        /*
                        * [DEPUIS 24-06-15] @BOR
                        */
                        //On vide les entrées
                        $("#brain_th-folls").find(".brainS_UnikMdl").remove();
                        //On retire le "NoOne" s'il existe
                        _f_NoOne("RLSFLR",false);
                        //On affiche le spinner
                        _f_HidSpnr("RLSFLR",true);
                        //On remet le "counter" à 0
                        $(".jb-brn-lsts-bmx[data-scp='rlsflr']").find(".jb-bn-lsts-nb").text(0);
                        
                        _f_Srv_GMyFlwL(_th,cb,x);
                    break;
                case "brain_menu_folgs":
                        cb = "mflgs";
                        /*
                        * [DEPUIS 24-06-15] @BOR
                        */
                        //On vide les entrées
                        $("#brain_th-folgs").find(".brainS_UnikMdl").remove();
                        //On retire le "NoOne" s'il existe
                        _f_NoOne("RLSFLG",false);
                        //On affiche le spinner
                        _f_HidSpnr("RLSFLG",true);
                        //On remet le "counter" à 0
                        $(".jb-brn-lsts-bmx[data-scp='rlsflg']").find(".jb-bn-lsts-nb").text(0);
                        
                        _f_Srv_GMyFlgL(_th,cb,x);
                    break;
                default:
                        $(x).data("lk",0);
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    //STAY PUBLIC
    this._f_ClkOnMn = function(x){
        try {
            if ( KgbLib_CheckNullity(x) ) { 
                return; 
            }
            
            var _id = $(x).attr('id');
            
            //On commence par vérifier qu'il ne s'agit pas d'une action destinée à master
            //On le detecte grace à l'abscence de l'attribut data-slave ou à la présence de data-master
            //_h_m = HasMaster; _h_s = HasSlave
            var _h_m = 0;
            var _h_s = 0;
            
            _h_m = ( $(x).attr('data-master') ) ? 1 : 0;
            _h_s = ( $(x).attr('data-slave') ) ? 1 : 0;
            
            if (_h_m) {
                _f_HdlMstrOprs($(x).data("master"));
            }
            
            if ( $(x).data("or") ) {
                /*
                 * [NOTE 10-12-14] @author L.C.
                 * Petit tour de passe-passe pour permettre à certains liens de fonctionner malgré l'extrême rigité de ce vieux code.
                 * Il faudra repenser la logique BRAIN.
                 */
                _id = $(x).data("or");
            }
            
            switch (_id) {
                //Au [05-11-14: L'emploi du switch peut paraitre saugrenu mais il anticipe les modifications/évolutions à venir ]
                case "brain_menu_new-ml":
                        _f_HdlClkOnMn_Ext(x, _id);
                        $("#npost_txt").focus();
                        //On simule un click pour déclencher le processus de preparation
                        $("#start_npostMl_process").click();
                    break;
                /*
                 * [DEPUIS 29-03-16]
                 *      
                 */
                case "brain_menu_new-sod": 
                        _f_HdlClkOnMn_Ext(x, _id);
                        $("#npost_txt").focus();
                        //On simule un click pour déclencher le processus de preparation
                        $("#start_npostMl_process").click();
                    break;
                case "brain_th-npost_tr":
                        //RAPPEL : On ne réalise pas certaines actions (Reset) car on a pas l'objet référence. Ce qui fait qu'on ne reSertera vraiment jamais.
                        
                        /*
                         * [DEPUIS 15-08-15] @BOR
                         *  Si l'élément fait référence à une Tendance PRIVATE et qu'il ne s'agit pas d'une Tendance appartenant à l'utilisateur CU, on interdit l'opération.
                         */
                        var $tr__ = $(".brain_trch_mdl[data-trid='"+$(x).data("trid")+"'");
//                        Kxlib_DebugVars([$tr__.data("noakx"),$tr__.data("isown")],true);
                        if ( ( $tr__.data("noakx") && parseInt($tr__.data("noakx")) === 1 ) && !( $tr__.data("isown") && parseInt($tr__.data("isown")) === 1 ) ) {
                            return;
                        }
                        
                        //Ainsi, on peut faire apparaitre la fenetre qui sera modifiée par la suite au niveau du module NewPost.
                        _f_HdlClkOnMn_Ext(x, "brain_menu_new-ml");
                        $("#npost_txt").focus();

                        //Permet de lancer le process au niveau du module NewPost
                        $("#start_npostTr_process").data("title", $(x).data("title"));
                        $("#start_npostTr_process").data("trid", $(x).data("trid"));
                        $("#start_npostTr_process").data("isown", $(x).data("isown"));

                        //Element qui permet à NewPost de reprendre la main
                        $("#start_npostTr_process").click();
                    break;
                case "brain_submenu_newtr":
                        _f_HdlClkOnMn_Ext(x, _id);
                        if (! KgbLib_CheckNullity($(x).data("ext")) ) {
                            var sl = Kxlib_ValidIdSel($(x).data("ext"));
                            $(sl).change();
                        }
                    break;
                    //[NOTE  22-06-14] : Ajouter cette partie pour faire correspondre aux besoins
                case "brain_menu_new-ml-tr":
                        if ($("#brain_maximus").data("trcct-skip") === 0) {
                            _f_HdlClkOnMn_Ext(x, _id);
                        } else {
                            _f_HdlClkOnMn_Ext(x, "brain_submenu_mytrch");
                        }
                    break;
                case "brain_menu_folls" :
                case "brain_menu_folgs" :
                case "brain_submenu_mytrch" :
                case "brain_submenu_follgtrch" :
                case "brain_submenu_notif_all" :
                case "brain_submenu_notif_mtrs" :
                case "brain_submenu_notif_ftrs" :
                case "brain_submenu_notif_reac" :
                case "brain_submenu_gbck_favs" :
                case "brain_menu_sam" :
                case "brain_submenu_gbck_fav" :
                case "brain_submenu_gbck_r" :
                case "brain_submenu_gbck_s" :
                case "brain_menu_trophz":
                case "brain_menu_stgs":
                        _f_HdlClkOnMn_Ext(x, _id);
                    break;
                default : 
                    return;
            }
            
            /*
             * [DEPUIS ]
             */
            if ( $(x).is(".jb-brain-menu-action") ) {
                $(".jb-brain-menu-action.selected").removeClass("selected");
                $(x).addClass("selected");
                
                _f_BrnNwPstBnr(x);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_BrnNwPstBnr  = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            var scp = $(x).data("action"), scp;
            switch (scp) {
                case "add-art-iml" :
                        scp = "[data-scp='iml']";
                    break;
                case "add-art-sod" :
                        scp = "[data-scp='sod']";
                    break;
                case "add-art-itr" :
                        scp = "[data-scp='itr']";
                    break;
                default :
                    return;
            }
                
            $(".jb-tqr-brn-npst-bnr").addClass("this_hide");
            $(".jb-tqr-brn-npst-bnr").filter(scp).removeClass("this_hide");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    //Attend target en argument
    var _f_ShwBnMnDsc = function(x) {
//    this.HandleBrainMenuDesc = function(arg) {
        /*
         * Une amélioration de la procedure permettra d'aller chercher le ou les textes au près du serveur.
         */
        try {
            
            if ( KgbLib_CheckNullity(x) | !$(x) | !$(x).length | KgbLib_CheckNullity($(x).data("desc")) ) {
                var dcd = _f_Gdf()._dfltDscId;
                var _m = $(".jb-brn-mn-dsc-txt-wpr").find(".jb-brn-mn-dsc-txt[data-cd='"+dcd+"']").text();
                $("#brain_snitch").text(_m);
//                var _m = $(".jb-brn-mn-dsc-txt-wpr").children(Kxlib_ValidIdSel(_f_Gdf()._dfltDscId)).html();
//                $("#brain_snitch").html(_m);
                return;
            }
            
            //dcd : DescriptionCoDe
            var dcd = $(x).data("desc");
            if (! $(".jb-brn-mn-dsc-txt[data-cd='"+dcd+"']").length | KgbLib_CheckNullity($(".jb-brn-mn-dsc-txt[data-cd='"+dcd+"']").text()) ) {
                return;
            }
            
            $("#brain_snitch").text($(".jb-brn-mn-dsc-txt[data-cd='"+dcd+"']").text());
            
            //[DEPUIS 23-06-15] @BOR
            /*
            var _p = $(".jb-brn-mn-dsc-txt-wpr").children(Kxlib_ValidIdSel(x));
            if ($(_p).length === 1) {
                
                var _m = $(_p).html();
                $("#brain_snitch").html(_m);
                
            } else {
                return;
            }
            //*/
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_HdlSmplUnikHvrStart = function(x) {
//    this.HandleSampleUnikHoverStart = function(x) {
        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
        
        var _f = $(x).data("fade");
        
        if ( KgbLib_CheckNullity(_f) ) {
            return;
        }
        
        var _cl = "."+_f;
        $(x).children(_cl).addClass("this_hide");
        
    };
    
    var _f_HdlSmplUnikHvrOff = function(x) {
//    this.HandleSampleUnikHoverOff = function(arg) {
        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
        
        var _f = $(x).data("fade");
        
        if (KgbLib_CheckNullity(_f)) return;
        
        var _cl = "."+_f;
        $(x).children(_cl).removeClass("this_hide");
        
    };
    
    var _f_BkInBn = function(x) {
//    this.HandleBackInBrain = function(x) {
        if ( KgbLib_CheckNullity(x) ) {
            var _m = $(".jb-brn-mn-dsc-txt-wpr").children(Kxlib_ValidIdSel(_f_Gdf()._dfltDscId)).html();
            $("#brain_snitch").html(_m);
            return;
        }
    };
    
    var _f_HdlBckInNpost = function () {
//    this.HandleBackInNpost = function () {
        
    };
    
    var _f_HdlBckInNpost = function () {
//    this.HandleBackInNpost = function () {
        
    };
    
    var _f_Init = function(a) {
//    this.Init = function(a) {
        _f_ChkBMSts(a);
    };
    
    var _f_HdlNewStgs = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk") === 1;
            
            var ick = ( $(x).is(":checked") ) ? "_DEC_ENA" :"_DEC_DISA";
            
            var sc = $(x).data("wha");
            if ( KgbLib_CheckNullity(sc) || $.inArray(sc,_f_Gdf()._stgsOpts) === -1 ) {
                $(x).data("lk",0);
                return;
            }
             
//            Kxlib_DebugVars([sc,ick],true); 
            
            $(".jb-brn-mn-bdy-wt-pnl-bmx").removeClass("this_hide");
            
            var s = $("<span/>");
                
            var T = new MNFM(); 
            T.SetPrfrcs(sc,ick,s);

            $(s).on("operended",function(e){
                
                $(".jb-brn-mn-bdy-wt-pnl-bmx").addClass("this_hide");
                
                $(x).data("lk",0);
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        };
    };
    
    /************************************************************************************************************************************************************/
    /*********************************************************************** SERVER SCOPE ***********************************************************************/
    /************************************************************************************************************************************************************/
    
    
    //URQID => On vérifie et récupère les Articles antérieurs. on envoit la date du dernier article affiché
    var _Ax_SigDsmaTrCct = Kxlib_GetAjaxRules("TMLNR_SIG_DSMATRCCT", Kxlib_GetCurUserPropIfExist().upsd);
//    this.Ajax_SigDsmaTrCct = Kxlib_GetAjaxRules("TMLNR_SIG_DSMATRCCT", Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_SigDsmaTrCcpt = function() {
//    this.Srv_SignalDsmaTrConcept = function() {
        
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
                            case "__ERR_VOL_SS_MSG":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_USER_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break
                            case "__ERR_VOL_DNY":
                            case "__ERR_VOL_DENY":
                                return;
                            default:
                                return;
                        }
                    }
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    
                    if ( ( typeof datas.return === "string" || datas.return instanceof String ) && ( datas.return.toLowerCase() === "done" ) ) {
                        //On signale qu'il ne faut plus afficher la section de renseignement
                        $("#brain_maximus").data("trcct-skip","1");

                        //Aller vers MyTRENDS
                        $("#brain_submenu_mytrch").click();
                    } else return;
                    
                } else {
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
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
        
        var toSend = {
            "urqid": _Ax_SigDsmaTrCct.urqid,
            "datas": {
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_SigDsmaTrCct.url, wcrdtl : _Ax_SigDsmaTrCct.wcrdtl });
    };
    
    var _f_SigDsmaTrCcpt = function() {
//    this.SignalDsmaTrConcept = function() {
        /*
         * Gère le cas où l'utilisateur arrive sur la fenêtre où on lui explique rapidement le concept des Tendance.
         * Il peut decider de :
         *      (1) Faire disparaitre la fenetre
         *      (2) Faire disparaitre la fenetre ET demander à ce qu'on ne la lui montre plus
         */
        
        
        //On vérifie s'il demande à ce qu'on ne lui montre plus la fenetre
        
        if ( $("#trcct-bo-skip-dsma-ib").is(':checked') ) {
            //On le signale au serveur
            _f_Srv_SigDsmaTrCcpt();
        } else {
            //On signale qu'il ne faut continuer à afficher la section à chaque nouveaux click
            $("#brain_maximus").data("trcct-skip","0");
            
            //On fait disparaitre la fenetre. Pour cela on simule un click sur le lien qui nous dirige vers MyTrends
            $("#brain_submenu_mytrch").click();
        }
            
    };
    
    
    //URQID => Récupérer les Tendances appartenant à CU pour les lister dans BRAIN
    var _Ax_GMyTr = Kxlib_GetAjaxRules("TMLNR_BRAIN_GETMYTRS", Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_GMyTr = function(a,cb,x) {
//    this.Srv_GetMyTrends = function(a,cb) {
        //a = Identifiant slace du BLOC, cb =CodeBlock : Il sert afficher les éléments dans la bonne DIV
        if ( KgbLib_CheckNullity(a) | KgbLib_CheckNullity(cb) | KgbLib_CheckNullity(x) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
                if (! KgbLib_CheckNullity(datas.err) ) {
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_SS_MSG":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_USER_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                            case "__ERR_VOL_DNY_AKX":
                                break;
                            default:
                                break;
                        }
                    }
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    _f_ShwTrsLst(datas.return,cb,x);
                    _f_CnThmChld(a);
                } else {
                     _f_ShwTrsLst(null,cb,x);
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
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            return;
        };
        
        var toSend = {
            "urqid": _Ax_GMyTr.urqid,
            "datas": {
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_GMyTr.url, wcrdtl : _Ax_GMyTr.wcrdtl });
    };
    
    
    //URQID => Récupérer les Tendances suivies par CU pour les lister dans BRAIN
    var _Ax_GMyFTr = Kxlib_GetAjaxRules("TMLNR_BRAIN_GETFOLGTRS", Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_GMyFTr = function(a,cb,x) {
//    this.GetMyFollowingTrends = function(a,cb) {
        //a = Identifiant slace du BLOC, cb =CodeBlock : Il sert afficher les éléments dans la bonne DIV
        if ( KgbLib_CheckNullity(a) | KgbLib_CheckNullity(cb) | KgbLib_CheckNullity(x) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
                if (! KgbLib_CheckNullity(datas.err) ) {
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_SS_MSG":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_USER_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                            case "__ERR_VOL_DNY_AKX":
                                break;
                            default:
                                break;
                        }
                    }
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    _f_ShwTrsLst(datas.return,cb,x);
                    _f_CnThmChld(a);
                } else {
                    _f_ShwTrsLst(null,cb,x);
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
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            return;
        };
        
        var toSend = {
            "urqid": _Ax_GMyFTr.urqid,
            "datas": {
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_GMyFTr.url, wcrdtl : _Ax_GMyFTr.wcrdtl });
    };
    
    //URQID => Récupérer les comptes qui me suivent pour les lister dans BRAIN
    var _Ax_GMyFlwL = Kxlib_GetAjaxRules("TMLNR_BRAIN_GETMYFOLW", Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_GMyFlwL = function(a,cb,x) {
//    this.Srv_GetMyFlwList = function(a,cb) {
        //a = Identifiant slace du BLOC, cb =CodeBlock : Il sert afficher les éléments dans la bonne DIV
        if ( KgbLib_CheckNullity(a) | KgbLib_CheckNullity(cb) | KgbLib_CheckNullity(x) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else return;
                
                if (! KgbLib_CheckNullity(datas.err) ) {
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_SS_MSG":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_USER_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                            case "__ERR_VOL_DNY_AKX":
                                break;
                            default:
                                break;
                        }
                    }
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    _f_ShwFlwRelLst(datas.return,cb,x);
                    _f_CnThmChld(a);
                } else {
                    _f_ShwFlwRelLst(null,cb,x);
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
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            return;
        };
        
        var toSend = {
            "urqid": _Ax_GMyFlwL.urqid,
            "datas": {
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_GMyFlwL.url, wcrdtl : _Ax_GMyFlwL.wcrdtl });
    };
    
    
    
    //URQID => Récupérer les comptes qui je suis pour les lister dans BRAIN
    var _Ax_GMyFlgL = Kxlib_GetAjaxRules("TMLNR_BRAIN_GETMYFOLG", Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_GMyFlgL = function(a,cb,x) {
//    this.Srv_GetMyFlgList = function(a,cb) {
        //a = Identifiant slace du BLOC, cb =CodeBlock : Il sert afficher les éléments dans la bonne DIV
        if ( KgbLib_CheckNullity(a) | KgbLib_CheckNullity(cb) | KgbLib_CheckNullity(x) ) {
            $(x).data("lk",0);
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    $(x).data("lk",0);
                    return;
                }
                
                if (! KgbLib_CheckNullity(datas.err) ) {
                    $(x).data("lk",0);
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_SS_MSG":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_USER_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                            case "__ERR_VOL_DNY_AKX":
                                break;
                            default:
                                break;
                        }
                    }
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    _f_ShwFlwRelLst(datas.return,cb,x);
                    _f_CnThmChld(a);
                } else {
                    _f_ShwFlwRelLst(null,cb,x);
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
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            return;
        };
        
        var toSend = {
            "urqid": _Ax_GMyFlgL.urqid,
            "datas": {
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_GMyFlgL.url, wcrdtl : _Ax_GMyFlgL.wcrdtl });
    };
    
    
    /****************************************************************************************************************************************/
    /************************************************************** VIEW SCOPE **************************************************************/
    /****************************************************************************************************************************************/
    
    var _f_HidSpnr = function (scp,shw) {
        try {
            if ( KgbLib_CheckNullity(scp) | typeof scp !== "string" ) {
                return;
            }
            
            switch (scp) {
                case "MYTRS" :
                case "FLGTRS" :
                case "RLSFLR" :
                case "RLSFLG" :
                        scp = scp.toLowerCase();
                        $s = $(".jb-brn-lsts-spnr-mx[data-scp='"+scp+"']");
                        if ( $s && $s.length && shw === true ) {
                            //On masque le trigger du "ScrollTop" 
//                            Kxlib_DebugVars([$(".jb-brn-lsts-bmx[data-scp='"+scp+"']").length, $(".jb-brn-lsts-bmx[data-scp='"+scp+"']").find(".back_to_60s > a").length],true);
                            $(".jb-brn-lsts-bmx[data-scp='"+scp+"']").find(".back_to_60s > a").addClass("this_hide");
                            //On affiche le Spnr
                            $s.removeClass("this_hide");
                        }
                        else if ( $s && $s.length ) {
                            //On masque le Spnr
                            $s.addClass("this_hide");
                        } 
                    break;
                default :
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_NoOne = function (scp,shw) {
        try {
            if ( KgbLib_CheckNullity(scp) | typeof scp !== "string" ) {
                return;
            }
            
            var $elts;
            switch (scp) {
                case "MYTRS" :
                        $elts = $(".jb-com-bn-trs-elt[data-bind_isown=1]");
                    break;
                case "FLGTRS" :
                        $elts = $(".jb-com-bn-trs-elt[data-isfolw=1]");
                    break;
                case "RLSFLR" :
                        $elts = $(".jb-com-bn-rls-elt[data-etype='flr']");
                    break;
                case "RLSFLG" :
                        $elts = $(".jb-com-bn-rls-elt[data-etype='flg']");
                    break;
                default :
                    return;
            }
            
            scp = scp.toLowerCase();
            if (! $(".jb-brn-lsts-noone-mx[data-scp='"+scp+"']").length ) {
                return;
            }
//            Kxlib_DebugVars($elts.length, KgbLib_CheckNullity(shw), shw === false );
            if ( ( $elts && $elts.length ) | ( !KgbLib_CheckNullity(shw) && shw === false ) ) {
                //On masque "NoOne"
                $(".jb-brn-lsts-noone-mx[data-scp='"+scp+"']").addClass("this_hide");
            } else {
//                Kxlib_DebugVars([$(".jb-brn-lsts-bmx[data-scp='"+scp+"']").length, $(".jb-brn-lsts-bmx[data-scp='"+scp+"']").find(".back_to_60s > a").length],true);
                //On masque le trigger du "ScrollTop"
                $(".jb-brn-lsts-bmx[data-scp='"+scp+"']").find(".back_to_60s > a").addClass("this_hide");
                //On affiche "NoOne"
                $(".jb-brn-lsts-noone-mx[data-scp='"+scp+"']").removeClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ShwTrsLst = function (d,l,x) {
//    this.DisplayTrendsList = function (d,l) {
//        Kxlib_DebugVars([KgbLib_CheckNullity(d),KgbLib_CheckNullity(loc)],true);
//        alert(KgbLib_CheckNullity(l));
        //d= datas, l = location (le code permettant de repérer le bloc dans lequel on inserera les éléments de la liste
        
        try {
            
//        Kxlib_DebugVars([KgbLib_CheckNullity(d),KgbLib_CheckNullity(l)],true);
            if ( KgbLib_CheckNullity(x) | !$(x).length ) {
                return;
            } else if ( KgbLib_CheckNullity(d) && !KgbLib_CheckNullity(l) ) {
                /*
                 * [DEPUIS 24-06-15] @BOR
                 */
                var b = ( l === "mtrs" ) ? "#brain_list_mytrs" : "#brain_list_follgtrs";
                var scp = ( l === "mtrs" ) ? "MYTRS" : "FLGTRS";

                //On vide les entrées
                $(b).find(".brainS_UnikMdl").remove();
                //On réinit le nombre d'éléments affichés
                $(".jb-brn-lsts-bmx[data-scp='"+scp+"']").find(".jb-bn-lsts-nb").text(0);
                //On retire le spinner
                _f_HidSpnr(scp);
                //On affiche NoOne
                _f_NoOne(scp,true);
                
                $(x).data("lk",0);
                
                return;
            } else if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(l)  ) {
                $(x).data("lk",0);
                
                return;
            }
            
            var b = ( l === "mtrs" ) ? "#brain_list_mytrs" : "#brain_list_follgtrs";

            //On vide les entrées
            $(b).find(".brainS_UnikMdl").remove();
//        Kxlib_DebugVars([d,l],true);

            /*
             * [DEPUIS 24-06-15] @BOR
             */
            var scp = ( l === "mtrs" ) ? "MYTRS" : "FLGTRS";
            //On retire le spinner
            _f_HidSpnr(scp);
            //On affiche NoOne
            _f_NoOne(scp,false);
            
            var bind_isown, bind_isfolg, item_id;
            $.each(d, function(x, elt) {
                if (l === "mtrs") {
                    bind_isown = 1;
                    bind_isfolg = 0;
                    item_id = "mytr_model_id" + elt.trd_eid;
                } else {
                    bind_isown = 0;
                    bind_isfolg = 1;
                    item_id = "folwtr_model_id" + elt.trd_eid;
                }
                
                /*
                 * INDENTIFIANTS :
                 *  trd_eid
                 *  trd_title
                 *  trd_desc
                 *  trd_posts_nb
                 *  trd_abos_nb
                 */
                
                var nt = Kxlib_Decode_After_Encode(elt.trd_title);
                var nd = Kxlib_Decode_After_Encode(elt.trd_desc);
//                Kxlib_DebugVars([elt.trd_title,nt],true);
                
                //On crée la vue
                var e = "<div id=\"\" class=\"jb-com-bn-trs-elt brain_trch_mdl brainS_UnikMdl\" data-trid=\"" + elt.trd_eid + "\" data-isown=\"" + bind_isown + "\" data-isfolw=\"" + bind_isfolg + "\" data-title=\"\"  data-desc=\"\" data-prevw=\"\" data-flwg=\"\" data-postnb=\"\">";
//                var e = "<div id=\"\" class=\"jb-com-bn-trs-elt brain_trch_mdl brainS_UnikMdl\" data-trid=\"" + elt.trd_eid + "\" data-isown=\"" + bind_isown + "\" data-isfolw=\"" + bind_isfolg + "\" data-title=\"\"  data-desc=\"\" data-prevw=\"\" data-flwg=\"\" data-postnb=\"\">"; //[DEPUIS 24-06-15] @BOR EUH ... ?
                e += "<div class=\"brain_trch_mdl_conf this_hide\">";
                e += "<div class=\"btmc_top_max\">";
                e += "<p class=\"btmc_top_text\">";
                e += "Humh ! Etes vous certain de vouloir supprimer définitivement cette Tendance ?";
                e += "</p>";
                e += "</div>";
                e += "<div class=\"btmc_bot_max\">";
                e += "<div class=\"btmc_bot_btn_max\">";
                e += "<a class=\"btmc_bot_btn\" data-ans=\"0\" data-target=\"\" href=\"javascript:;\">Non</a>";
                e += "<a class=\"btmc_bot_btn\" data-ans=\"1\" data-target=\"\" href=\"javascript:;\">Oui</a>";
                e += "</div>";
                e += "</div>";
                e += "</div>";
                
                if ( elt.hasOwnProperty("trd_tdl") && elt.trd_tdl === true ) {
                    e += "<div class=\"trch_btm_delwrng\">";
                    e += "<i class=\"fa fa-exclamation-triangle wrleft\"></i><span>En suppression programmée</span><i class=\"fa fa-exclamation-triangle wrright\"></i>";
                    e += "</div>";
                }
                
                e += "<div class=\"brain_trch_hdr\">";
                e += "<a class=\'brain_trch_title npost_tr_trig\' data-trid=\"" + elt.trd_eid + "\" href=\'javascript:;\' title=\"\"></a>";
                e += "</div>";
                e += "<div class=\"trch_body_top\">";
                e += "<p class=\"trch_mdl_desc\" max=\"200\"></p>";
                e += "<div class=\"trch_body_opt\">";
                e += "<span class=\"trch_bdg_cntd\"title=\"\" >";
                e += "<img class=\"trch_bdg_cntd_lg\" height=\"22\" width=\"22\" src=\"" + Kxlib_GetExtFileURL("sys_url_img", "r/tr_cntd.png") + "\" />";
                e += "<span class=\"trch_bdg_cntd_txt\">connected</span>";
                e += "</span>";
                e += "<div class=\"action_maximus action_trch\">";
                e += "<a class=\'action_a cursor-pointer\'><span class=\'brain_sp_k\'>A</span><span class=\'brain_sp_action\'>ction<span></a>";
                e += "<ul class=\'action_foll_choices this_hide\'>";
                /*
                 * [NOTE 06-12-14] @author L.C.
                 * La fonctionnalité n'est pas aboutie. Je préfère la retirer le temps de travailler dessus.
                 * A la place, j'insère le bouton "Aller vers" qui permet d'accéder directement à la Tendance. 
                 * Sans cela, il aurait fallu chercher un Article puis cliquer sur le titre pour y accéder. C'est une tâche fastidieuse et qui a coup sur en aurait énervé plus d'un.
                 */
                
                if (l === "mtrs" && elt.hasOwnProperty("trd_tdl") && elt.trd_tdl === true ) {
                    e += "<li><a href=\"" + elt.trd_href + "\" class='afl_choice bind-goto' alt=''>Aller vers</a>";
                } else if ( l === "mtrs") {
                    e += "<li><a href=\"" + elt.trd_href + "\" class='afl_choice bind-goto' alt=''>Aller vers</a>";
                    e += "<li><a href=\"\" class=\"afl_choice kgb_el_can_revs bind-delmytr\" data-tarbloc=\"brain_list_mytrs\" data-target=\"mtr-model-id-" + elt.trd_eid + "\" data-action=\"del_mytr\" alt=\"\">Supprimer</a></li>";
                } else {
                    e += "<li><a href=\"" + elt.trd_href + "\" class='afl_choice bind-goto' alt=''>Aller vers</a>";
//                e += "<li><a href=\"\" class=\'afl_choice kgb_el_can_revs bind-conxtr\' data-revs=\"Connect\" data-tarbloc=\"brain_list_follgtrs\" data-target=\"fltr-model-id-"+elt.trd_eid+"\" data-action=\"back_conxtr\" alt=\"\">Se Déconnecter</a></li>";
                }
                e += "</ul>";            
                e += "</div>";
                e += "</div>";
                e += "<div class=\"trch_body_down\">";
                e += "<p class=\"trch_b_d_post\"><span class=\"trch_b_d_nbrB\">" + elt.trd_posts_nb + "</span>&nbsp;<span class=\"trch_b_d_nbrInd trch_b_d_nbrInd_post\">Post</span></p>";
                e += "<p class=\"trch_b_d_follg\"><span class=\"trch_b_d_nbrB\">" + elt.trd_abos_nb + "</span>&nbsp;<span class=\"trch_b_d_nbrInd\">Followers</span></p>";
                e += "</div>";
                e += "</div>";
                e += "</div>";
                e = $.parseHTML(e);
                
                $(e).find(".brain_trch_title").attr("title",nt);
                var t__ = $("<span/>").addClass("bn-trch-tle-tle").text(nt);
                $(e).find(".brain_trch_title").html(t__);
                $(e).find(".trch_mdl_desc").text(nd);
                
                /*
                 * [DEPUIS 15-08-15] @BOR
                 *  On vérifie si le mode de participation de la Tendance.
                 *  S'il s'agit d'une Tendance de type PRIVATE, il faut :
                 *      (1) Le signaler visuellement
                 *      (2) L'indiquer pour que : 
                 *          (20) Ne puisse pas ajouter dans cette dernière
                 *          (21) (TODO) Afficher un message éphémère qui rappel que la Tendance est PRIVATE
                 */
                if ( elt.trd_iprv === true ) {
                    if (  parseInt(bind_isown) !== 1 ) {
                        var $l__ = $("<i/>").addClass("fa fa-lock bn-trch-tle-lck");
                        $(e).find(".brain_trch_title").prepend($l__);
                                
                        $(e).data("noakx",1);
                    }
                }
 
                //Mise en place de l'identifiant
                if ( l === "mtrs" ) {
                    $(e).attr({
                        "id": "mtr-model-id-" + elt.trd_eid
                    });
                    $(e).find(".btmc_bot_btn").data("target", "mtr-model-id-" + elt.trd_eid);
                } else {
                    $(e).attr({
                        "id": "fltr-model-id-" + elt.trd_eid
                    });
                    $(e).find(".btmc_bot_btn").data("target", "fltr-model-id-" + elt.trd_eid);
                }
                
                
                //** On rebind les éléments **//
                
                //On rebind les bouton de choix
                $(e).find(".btmc_bot_btn").click(function(e) {
                    Kxlib_PreventDefault(e);
                    (new Brain_HandleTrend()).DelMyTr(this);
                });
                
                
                //On rebind le tigger
                $(e).find(".npost_tr_trig, .npost_tr_trig > *").click(function(e) {
                    Kxlib_PreventDefault(e);
                    Kxlib_StopPropagation(e);
                    try {
                        var _this = ( $(e.target).is(".npost_tr_trig") ) ? this : $(e.target).closest(".npost_tr_trig");
                        
                        var trid = $(_this).data("trid");
//                        var trid = $(e.target).data("trid");
                        var isown = $(_this).closest(".jb-com-bn-trs-bc").attr("id");
//                        var isown = $(e.target).closest(".jb-com-bn-trs-bc").attr("id");
                        var isfolw = parseInt($(_this).closest(".jb-com-bn-trs-elt").data("isfolw"));
//                        var isfolw = parseInt($(e.target).closest(".jb-com-bn-trs-elt").data("isfolw"));

                        if ( KgbLib_CheckNullity(trid) || (isown !== "brain_list_mytrs" && !isfolw) ) {
                            return;
                        }

                        var $el = $(document.createElement('span'));
                        $el.data("slave", "brain_th-new_ml");
                        $el.attr("id", "brain_th-npost_tr");
                        $el.data("title", $(_this).attr("title"));
//                        $el.data("title", $(e.target).attr("title"));
                        $el.data("trid", trid);
                        $el.data("isown", isown);

                        gt._f_ClkOnMn($el);
                    } catch (ex) {
                        Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                    }
                });
                
                
                //On rebind le bouton action
                $(e).find(".action_a").focusout(function(e) {
                    if ( e.target === this ) {
                        e.stopPropagation();
                        $(this).parent().children(".action_foll_choices").addClass("this_hide");
                        //La ligne ci-dessous causait un bug : too much recursion a cause d'un 'bubbling tree'
                        //            $(this).blur();
                    }
                });
                
                
                $(e).find(".action_a").click(function(e) {
                    Kxlib_PreventDefault(e);
                    
                    $(this).focus();
                    
                    if (!$(this).parent().children(".action_foll_choices").hasClass("this_hide")) {
                        $(this).parent().children(".action_foll_choices").addClass("this_hide");
                        $(this).blur();
                        
                        return;
                    }
                    $(".action_foll_choices").not(this).addClass("this_hide");
                    $(this).parent().children(".action_foll_choices").toggleClass("this_hide");
                });
                
                $(".action_a").hover(function() {
                    
                }, function() {
                    
                });
                
                //On append l'élément
                $(b).find(".back_to_60s").before(e);
                
            });
            
            /*
             * [DEPUIS 24-06-15] @BOR
             * On n'affiche le "ScrollTop" seulement si on a un nombre suffisant d'éléments. Sinon, cela ne sert à rien.
             */
            if ( $(b).find(".jb-com-bn-trs-elt").length > 3 ) {
                //On fait apparaitre "back_to_60s"
                $(b).find(".back_to_60s > a").click(function(e) {
                    Kxlib_PreventDefault(e);
                    $("#toptop").animatescroll({element: '.in_slave_list', padding: 20});
                    $(".in_slave_list").scrollTop(0);
                    $(".in_slave_list").perfectScrollbar();
                    $(".in_slave_list").perfectScrollbar("update");
                });
                $(b).find(".back_to_60s > a").removeClass("this_hide");
            }
           
            $(x).data("lk",0);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_ShwFlwRelLst = function (d,l,x) {
//    this.DisplayFollowRelationList = function (d,l) {
//        Kxlib_DebugVars([KgbLib_CheckNullity(d),KgbLib_CheckNullity(loc)],true);
//        alert(KgbLib_CheckNullity(l));
        //d= datas, l = location (le code permettant de repérer le bloc dans lequel on inserera les éléments de la liste
        try {
            
            if ( KgbLib_CheckNullity(x) | !$(x).length ) {
                return;
            } else if ( KgbLib_CheckNullity(d) && !KgbLib_CheckNullity(l) ) {
                /*
                 * [DEPUIS 24-06-15] @BOR
                 */
                var b = ( l === "mflwrs" ) ? "#brain_list_folls" : "#brain_list_folgs";
                var scp = ( l === "mflwrs" ) ? "RLSFLR" : "RLSFLG";

                //On vide les entrées
                $(b).find(".brainS_UnikMdl").remove();
                //On réinit le nombre d'éléments affichés
                $(".jb-brn-lsts-bmx[data-scp='"+scp+"']").find(".jb-bn-lsts-nb").text(0);
                //On retire le spinner
                _f_HidSpnr(scp);
                //On affiche NoOne
                _f_NoOne(scp,true);
                
                $(x).data("lk",0);
                
                return;
            } else if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(l)  ) {
                $(x).data("lk",0);
                
                return;
            }
            
            var bid = "";
            if ( l === "mflwrs" ) {
                b = "brain_list_folls";
                bid = "#brain_list_folls";
            } else {
                b = "brain_list_folgs";
                bid = "#brain_list_folgs";
            }
            
            //On vide les entrées
            $(bid).find(".brainS_UnikMdl").remove();
//        Kxlib_DebugVars([d,l],true);
            
            /*
             * [DEPUIS 24-06-15] @BOR
             */
            var scp = ( l === "mflwrs" ) ? "RLSFLR" : "RLSFLG";
            //On retire le spinner
            _f_HidSpnr(scp);
            //On affiche NoOne
            _f_NoOne(scp,false);
            
            var item_id, url_join_str, dfolw, ifolw, folg, follow, unfollow, etype;
            $.each(d, function(x, elt) {
                if ( l === "mflwrs" ) {
                    url_join_str = "flr";
//                alert(typeof elt.urel);
//                alert(typeof elt.urel === "object");
//                alert(Kxlib_ObjectChild_Count(elt.urel) === 2);
                    
                    if (typeof elt.urel === "object" && Kxlib_ObjectChild_Count(elt.urel) === 2) {
                        /*
                         * On ne vérifie pas l'exactitude de la donnée renvoyée. Si le tableau contient deux données alors pour cet URQ cela signifie qu'on est dans le cas 'D_FOLW'
                         */
                        url_join_str += ",flg";
                        
//                    Kxlib_DebugVars([url_join_str],true);
                        
                        folg = Kxlib_getDolphinsValue("following");
                        follow = Kxlib_getDolphinsValue("follow");
                        unfollow = Kxlib_getDolphinsValue("unfollow");
                        
//                    Kxlib_DebugVars([folg,follow,unfollow],true);
                        
                        dfolw = true;
                    } else {
                        dfolw = false;
                    }
                    
                    item_id = "bflruid-" + elt.ueid;
                    
                    /*
                     * [DEPUIS 24-06-15] @BOR
                     * Pour permettre de faire fonctionner _f_Spnr() et _f_NoOne()
                     */
                    etype = "flr";
                } else {
                    ifolw = true;
                    
                    folg = Kxlib_getDolphinsValue("following");
                    follow = Kxlib_getDolphinsValue("follow");
                    unfollow = Kxlib_getDolphinsValue("unfollow");
                    
                    url_join_str = "flg";
                    item_id = "bflguid-" + elt.ueid;
                    
                    /*
                     * [DEPUIS 24-06-15] @BOR
                     * Pour permettre de faire fonctionner _f_Spnr() et _f_NoOne()
                     */
                    etype = "flg";
                }
                
                //On s'assure que l'élément n'existe pas déjà
                if ( $(bid).find(Kxlib_ValidIdSel(item_id)).length ) {
                    return;
                }
                
                /*
                 * INDENTIFIANTS :
                 * ueid => L'identifiant externe du compte cible
                 * ufn => Le nom complet du compte cible
                 * upsd => Le pseudo de la cible
                 * uhref => Le lien vers le compte de la cible
                 * uppic => La photo de profil
                 * 
                 * upflbio => La bio de l'utilisateur cible
                 * 
                 * urel => Tableau contenant les Relations, ce qui permet de définir la relation entre les protagonistes (flr;flw;flr,flw) 
                 */
                
                //On crée la vue
                var e = "<div id=\"" + item_id + "\" class=\"jb-com-bn-rls-elt brain_foll_mdl brainS_UnikMdl\" data-bfuid=\"" + elt.ueid + "\" data-bfurel=\"" + url_join_str + "\" data-etype=\""+etype+"\">";
                e += "<div class=\"body_top\">";
                e += "<p class=\"brain_foll_header\">";
                e += "<a class=\"group_user\" href=\"" + elt.uhref + "\">";
                e += "<img class=\"br_foll_user_pic\" height=\"45\" width=\"45\" src=\"" + elt.uppic + "\" />";
                e += "<span class=\"br_foll_user_psd\">@" + elt.upsd + "</span>";
                e += "</a>";
                e += "</p>";
                e += "<p class=\"why_you_hatin\">";
                if (!KgbLib_CheckNullity(elt.upflbio)) {
                    e += "<q>" + Kxlib_Decode_After_Encode(elt.upflbio) + "</q>";
                }
                e += "</p>";
                e += "</div>";
                e += "<div class=\"body_bot\">";
                //L'un ou l'autre ou les deux
                if ( dfolw === true | ifolw === true ) {
                    e += "<span class=\"br_foll_folg\" data-fltype=\"flw\">" + folg + "</span>";
                    e += "<div class=\"action_maximus\">";
                    e += "<a class=\'action_a cursor-pointer\'><span class=\'brain_sp_k\'>A</span><span class=\'brain_sp_action\'>ction<span></a>";
                    e += "<ul class=\'action_foll_choices this_hide\'>";
                    e += "<li><a href=\"#\" class=\'afl_choice kgb_el_can_revs bind-fluser bind-fluser-folg\' data-revs =\"" + follow + "\" data-tarbloc=\"" + b + "\" data-target=\"" + item_id + "\" data-action=\"back_flw\" alt=\"\">" + unfollow + "</a></li>";
                    e += "</ul>";            
                    e += "</div>";
                }
                e += "</div>";
                e += "</div>";
                e = $.parseHTML(e);
                
                /*****************************/
                ///** On rebind les éléments **///
                
                
                $(e).find(".action_a").focusout(function(e) {
                    if (e.target === this) {
                        e.stopPropagation();
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
                        
                        return;
                    }
                    $(".action_foll_choices").not(this).addClass("this_hide");
                    $(this).parent().children(".action_foll_choices").toggleClass("this_hide");
                });
                
                $(".action_a").hover(function(){},function(){});
                
                /* On append dans l'élément */
                $(bid).find(".back_to_60s").before(e);
                
            });
            
            
            /*
             * [DEPUIS 24-06-15] @BOR
             * On n'affiche le "ScrollTop" seulement si on a un nombre suffisant d'éléments. Sinon, cela ne sert à rien.
             */
            if ( $(b).find(".jb-com-bn-rls-elt").length > 9 ) {
                //On fait apparaitre "back_to_60s"
                $(bid).find(".back_to_60s > a").removeClass("this_hide");
            }
            
            $(x).data("lk",0);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /*********************************************************************************************************************************************/
    /************************************************************** LISTENERS SCOPE **************************************************************/
    /*********************************************************************************************************************************************/
    
    /************************ BRAIN MENUS GENERAL  ****************************/
    
    _f_Init("o");

    $(".jb-brn-clz-tgr").click(function(e){
//    $("#brain_close_link").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_OnClose();
    });

    $(".jb-brn-opn-tgr").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_OnOpen();
    });

    $(".brainM_menu_elmnt").off().click(function(e){
//    $(".brainM_menu_elmnt").click(function(e){
        Kxlib_PreventDefault(e);
        
        gt._f_ClkOnMn(this);
    });
    
    $(".brain_in").off().click(function(e){
        Kxlib_PreventDefault(e);
        
        var $sl = $(this).parent();
        gt._f_ClkOnMn($sl);
    });
    
    $(".brainM_submenu_elmnt, #brain_submenu_follgtrch, #brain_submenu_mytrch").off().click(function(e){
//    $(".brainM_submenu_elmnt, #brain_submenu_follgtrch, #brain_submenu_mytrch").click(function(e){
        Kxlib_PreventDefault(e);
        
        gt._f_ClkOnMn(this);
    });
    
    $(".brainM_menu_elmnt").hover(function(e){
        _f_ShwBnMnDsc(this);
        /*
        var _d = $(this).data("desc");
        _f_ShwBnMnDsc(_d);
        //*/
    },function(e){
        _f_ShwBnMnDsc();
    });
    
    $(".th-sams_samplUnik_a").hover(function(e){
        _f_HdlSmplUnikHvrStart(this);
    }, function(e){
        _f_HdlSmplUnikHvrOff(this);
    });
    
    $(".jb-brn-bk").click(function(e){
//    $(".brain_back").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_OnClickOnBk(this);
    });
     
    $(".npost_tr_trig, .npost_tr_trig > *").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        try {
            var _this = ( $(e.target).is(".npost_tr_trig") ) ? this : $(e.target).closest(".npost_tr_trig");
            
            var trid = $(_this).data("trid");
//            var trid = $(this).data("trid");
            var isown = $(_this).closest(".jb-com-bn-trs-bc").attr("id");
//            var isown = $(this).closest(".jb-com-bn-trs-bc").attr("id");
            //alert("CIBLE => "+$(this).parent().html());
            var iflw = parseInt($(_this).closest(".jb-com-bn-trs-elt").data("isfolw"));
//            var iflw = parseInt($(this).closest(".jb-com-bn-trs-elt").data("isfolw"));
            
            if ( KgbLib_CheckNullity(trid) | (isown !== "brain_list_mytrs" && !iflw) ) {
                return;
            }
            
            //La methode ci-dessous semble plus rapide que $("<span>") ou $("<span></span>")
            var $el = $(document.createElement('span'));
            $el.data("slave", "brain_th-new_ml");
            $el.attr("id", "brain_th-npost_tr");
            $el.data("title", $(_this).attr("title"));
//            $el.data("title", $(this).attr("title"));
            $el.data("trid", trid);
            $el.data("isown", isown);
            
            /*
             * [DEPUIS 29-03-16]
             */
            $el.data("action", "add-art-itr");
            
            gt._f_ClkOnMn($el);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    });
    
    $(".jb-trcct-bo-skip-trg").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_SigDsmaTrCcpt();
    });
    
    $(".jb-brn-mn-bdy-s-p-opt-chbx").change(function(e){
        Kxlib_PreventDefault(e);
        
        _f_HdlNewStgs(this);
    });
    
}

new BrainHandler(); 
    