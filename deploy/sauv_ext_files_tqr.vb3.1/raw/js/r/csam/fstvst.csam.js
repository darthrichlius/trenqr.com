

function FSTVST () {
    /*
     * FONCTIONNALITES
     *  Version : b.1505.1.1 (Juillet 2015 VBeta1) [DATE UPDATE : 30-06-15]
     *      -> Lancer le processus automatiquement au chargement de la page
     *      -> TODO : Vérifier si l'utilisateur a décidé de ne plus vouloir voir le message lui demandant de lancer le tuto
     *      -> TODO : Démarrer le processus depuis "Chez Moi"
     *      -> Passer de TIPs en TIPs
     *      -> Améliorer l'expérience utilisateur, en recentrant l'écran vers un TIP, dans certains cas
     *      -> Sécuriser les dépassement d'index
     *      -> Gérer les cas où une action doit avoir lieu avant l'affichage d'un TIP
     *  
     *  EVOLUTIONS ATTENDUES
     *      -> ...
     *  
     *  EVOLUTIONS POSSIBLES
     *      -> ...
     */
    
    var _tbix;
    
    /*********************************************************************************************************************************************/
    /*************************************************************** PROCESS SCOPE ***************************************************************/
    /*********************************************************************************************************************************************/
    
    var _f_Gdf = function () {
        var dt = {
            /*
             * i : Identifiant
             * rs : RefSelecteur
             */
            "tab_index" : [
                /*
                0   : { i : "_IX_HOME", rs : "#header-logo" },
                1   : { i : "_IX_NEWSFEED", rs : ".jb-global-nav-elt[data-target='nwfd']" },
                2   : { i : "_IX_NOTIFICATION", rs : ".jb-global-nav-elt[data-target='pm']" },
                3   : { i : "_IX_FRDCENTER", rs : ".jb-tqr-f-nav-hdr[data-target='friend']" },
                4   : { i : "_IX_USERBOX", rs : "#user-id-card" },
                5   : { i : "_IX_PFLPIC", rs : ".jb-p-h-b-ui-trg" },
                6   : { i : "_IX_ATHOME", rs : "#tmlnr-hdr-homebox" },
                7   : { i : "_IX_COVER", rs : ".jb-c-c-start" },
                8   : { i : "_IX_MENUS_MYP", rs : "#menu-li-post" },
                9   : { i : "_IX_MENUS_MYT", rs : "#menu-li-tr" },
                10  : { i : "_IX_HDRSTATS", rs : "#acc-header-down" },
                11  : { i : "_IX_BRAIN", rs : ".jb-brn-opn-tgr" },
                110 : { i : "_IX_TQRS", rs : ".jb-tqr-nwpst-tqs-hrf-mx" },
                12  : { i : "_IX_ASDBIO", rs : ".jb-pflbio-bx" }, 
                13  : { i : "_IX_ASDSTATS", rs : ".jb-usr-socprt-bx" }, 
                14  : { i : "_IX_ASDAPPS", rs : ".jb-aside-mods" }, 
                15  : { i : "_IX_THEND", rs : "" }, 
                16  : { i : "_IX_START", rs : "#tmlnr-hdr-homebox" }
                //*/
                { i : "_IX_HOME",           rs : "#header-logo" },
                { i : "_IX_NEWSFEED",       rs : ".jb-global-nav-elt[data-target='nwfd']" },
                { i : "_IX_NOTIFICATION",   rs : ".jb-global-nav-elt[data-target='pm']" },
                { i : "_IX_FRDCENTER",      rs : ".jb-tqr-f-nav-hdr[data-target='friend']" },
                { i : "_IX_USERBOX",        rs : "#user-id-card" },
                { i : "_IX_PFLPIC",         rs : ".jb-p-h-b-ui-trg" },
                { i : "_IX_ATHOME",         rs : ".jb-tmlnr-hdr-hmbx" },
                { i : "_IX_COVER",          rs : ".jb-c-c-start" },
                { i : "_IX_MENUS_MYP",      rs : "#menu-li-post" },
                { i : "_IX_MENUS_MYT",      rs : "#menu-li-tr" },
                { i : "_IX_MENUS_FAV",      rs : "#menu-li-fav" },
                { i : "_IX_HDRSTATS",       rs : "#acc-header-down" },
                { i : "_IX_BRAIN",          rs : ".jb-brn-opn-tgr" },
                { i : "_IX_ADD_IN_IML_FRD", rs : "#brain_menu_new-ml" },
                { i : "_IX_ADD_IN_IML_SOD", rs : "#brain_menu_new-sod" },
                { i : "_IX_ADD_IN_TRD",     rs : "#brain_menu_new-ml-tr" },
//                { i : "_IX_TQRS",           rs : ".jb-tqr-nwpst-tqs-hrf-mx" },
                { i : "_IX_TLKBRD",         rs : ".jb-tqr-testy-hdr-mx" },
                { i : "_IX_LASTA",          rs : ".jb-tqr-lasta-mx" },
                { i : "_IX_ASDBIO",         rs : ".jb-pflbio-bx" }, 
                { i : "_IX_ASDSTATS",       rs : ".jb-usr-socprt-bx" }, 
                { i : "_IX_ASDAPPS",        rs : ".jb-aside-mods" }, 
                { i : "_IX_TIA",            rs : "#header-logo-discover" },
                { i : "_IX_THEND",          rs : "" }, 
                { i : "_IX_START",          rs : "#tmlnr-hdr-homebox" } 
            ]
        };
        
        return dt;
    };
    
    var _f_Init = function(shpl,shat) {
        try {
            //shpl : SHowPaneL; shat : SHowAllTips
            
            /*
             * ETAPE :
             * On vérifie que l'état est toujours à "sleeping"
             */
//            Kxlib_DebugVars([gbLib_CheckNullity($(".jb-tqr-fry-hq-mx").data("state")), $(".jb-tqr-fry-hq-mx").data("state") !== "sleeping"]);
            if (! $(".jb-tqr-fry-hq-mx").hasClass("state-sleeping") ) {
                //On retire les autres clases "state-*"
                $(".jb-tqr-fry-hq-mx").removeClass("state-wakeup");
                //On met en mode "sleeping"
                $(".jb-tqr-fry-hq-mx").addClass("state-sleeping");
            }
            
            /*
             * ETAPE :
             * On affiche le message qui demande à l'utilisateur s'il veut lancer le tutoriel
             */
            if ( $(".jb-tqr-fdry-invit-bmx").length ) {
               /*
                * On place l'élément de telle sorte qu'il se place au bon endroit quelque soit l'écran
                */
               var ix__ = Kxlib_ObjectChild_Count(_f_Gdf().tab_index);
               ix__ -= 1;
                _f_AcrtLoc(ix__);
               /*
                * On s'assure que le TIP est visible
                */
                _f_CmfrtOffset("_IX_START");
                
                //On affiche le TIP
                $(".jb-tqr-fdry-invit-bmx").hide().removeClass("this_hide").fadeIn().removeClass("style");
            }
            
            /*
             * Faut-il afficher le panneau de controle ?
             */
            if ( shpl ) {
                _f_Vw_ShPnl(true);
            }
            
            /*
             * Faut-il afficher tous les tips ?
             */
            if ( shat ) {
                _f_Vw_ShTips(true);
            }
                    
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    var _f_Action = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("action")) ) {
                return;
            }
            
            var a = $(x).data("action");
            switch (a.toLowerCase()) {
                case "close" :
                        _f_Close(x);
                    break;
                case "start" :
                        _f_Start(x);
                    break;
                case "previous" :
                        _f_Prev(x);
                    break;
                case "next" : 
                        _f_Next(x);
                    break;
                case "stop" : 
                        _f_Stop(x);
                    break;
                default : 
                    break;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    var _f_Close = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            /*
             * On lock le bouton si ce n'est pas déjà fait.
             */
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            $(".jb-tqr-fdry-invit-bmx").fadeOut().addClass("this_hide");
            
            if ( $(".jb-tqr-fdry-invt-dsma-chkbx").is(":checked") ) {
                var s = $("<span/>");
                
                var T = new MNFM();
                T.SetPrfrcs("_PFOP_FSTCNX","_DEC_DSMA",s);
                
                $(s).on("operended",function(e){
                    $(".jb-tqr-fstdvry-elmts").remove();
                });
            }
            
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    var _f_Start = function (x) {
        try {
            
            if ( !KgbLib_CheckNullity(x) && $(x).data("action") === "start" ) {
                $(".jb-tqr-fdry-invit-bmx").fadeOut().addClass("this_hide");
            }
            
            /*
             * On affiche le panneau de controle
             */
            _f_Vw_ShPnl(true);
            
            /*
             * On place l'élément de tel sorte qu'il se place au bon endroit quelque soit l'écran
             */
            _f_AcrtLoc(0);
            /*
             * On s'assure que les TIPS sont visibles
             */
            _f_CmfrtOffset(_f_Gdf().tab_index[0].i);
            
            /*
             * On affiche le premier TIPs
             */
            _f_Vw_ShTips(true,_f_Gdf().tab_index[0].i);
            
            /*
             * On indique l'index courant
             */
            _tbix = 0;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    var _f_Stop = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            /*
             * On masque les TIPS
             */
            _f_Vw_ShTips();
            
            /*
             * On masque le panneau
             */
            _f_Vw_ShPnl();
            
            /*
             * On réinitialise l'index
             */
            _tbix = 0;
            
            //On unlock le bouton
            $(x).data("lk",0);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    var _f_Prev = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            /*
             * On masque les TIPS
             */
            _f_Vw_ShTips();
            
            //On récupère le numéro d'index
            _tbix = ( _tbix === 0 ) ? 0 : --_tbix;
            
            var i = _f_Gdf().tab_index[_tbix].i.toUpperCase();
            /*
             * Dans le cas particulier de "BRAIN", on ferme l'élément avant d'afficher le TIP
             */
            switch ( i ) {
                case "_IX_BRAIN" : 
                case "_IX_TLKBRD" : 
                case "_IX_LASTA" : 
                        /*
                         * NOTE :  
                         *      Il faut que le module BRAIN soit FERMER dans ces cas.
                         */
                        if (! $("#slave_maximus").hasClass("this_hide") ) {
                            $(".jb-brn-clz-tgr").click();
                        }
                    break;
                    /*
                case "_IX_TQRS" : 
                        /*
                         * NOTE :  
                         *      Il faut que le module BRAIN soit OUVERT dans ces cas.
                         *
                        if ( $("#slave_maximus").hasClass("this_hide") ) {
                            $(".jb-brn-opn-tgr").click();
                        }
                        $(".jb-brn-bk").click();
                        $("#brain_menu_new-ml").click();
                    break;
                    //*/
                case "_IX_ADD_IN_IML_FRD" : 
                case "_IX_ADD_IN_IML_SOD" : 
                case "_IX_ADD_IN_TRD" : 
                        /*
                         * NOTE :  
                         *      Il faut que le module BRAIN soit OUVERT dans ces cas.
                         */
                        if ( $("#slave_maximus").hasClass("this_hide") ) {
                            $(".jb-brn-opn-tgr").click();
                        }
                        $(".jb-brn-bk").click();
                        $("#brain_menu_new-ml").click();
                    break;
                case "_IX_START" :
                        /*
                         * On renvoie vers le dernier TIPs faisant partie de la liste des TIPs pour le tuto
                         */
                        _tbix -= 1;
                        i = _f_Gdf().tab_index[_tbix].i.toUpperCase();
                    break;
                default: 
                    break;
            }
            
            /*
             * On place l'élément de tel sorte qu'il se place au bon endroit quelque soit l'écran
             */
            _f_AcrtLoc(_tbix);
            
            /*
             * On s'assure que les TIPS sont visibles
             */
            _f_CmfrtOffset(i);
            
            //On navigue vers l'élément
            _f_Vw_ShTips(true,i);
            
            //On unlock le bouton
            $(x).data("lk",0);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    var _f_Next = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | $(x).data("lk") === 1 ) {
                return;
            }
            
            /*
             * On masque les TIPS
             */
            _f_Vw_ShTips();
            
            /*
             * On récupère le numéro d'index
             */
            _tbix = ( _tbix === ( Kxlib_ObjectChild_Count(_f_Gdf().tab_index) - 1 ) ) ? _tbix : ++_tbix;
            
            var i = _f_Gdf().tab_index[_tbix].i.toUpperCase();
            Kxlib_DebugVars(["TUTO > ",i]);
            
            /*
             * Dans le cas particulier de "BRAIN", on ferme l'élément avant d'afficher le TIP
             */
            switch (i) {
                case "_IX_BRAIN" : 
                case "_IX_TLKBRD" : 
                case "_IX_LASTA" : 
                        /*
                         * NOTE :  
                         *      Il faut que le module BRAIN soit FERMER dans ces cas.
                         */
                        if (! $("#slave_maximus").hasClass("this_hide") ) {
                            $(".jb-brn-clz-tgr").click();
                        }
                    break;
                /*
                case "_IX_TQRS" : 
                        /*
                         * NOTE :  
                         *      Il faut que le module BRAIN soit OUVERT dans ces cas.
                         *
                        if ( $("#slave_maximus").hasClass("this_hide") ) {
                            $(".jb-brn-opn-tgr").click();
                        }
                        $(".jb-brn-bk").click();
                        $("#brain_menu_new-ml").click();
                    break;
                //*/
                case "_IX_ADD_IN_IML_FRD" : 
                case "_IX_ADD_IN_IML_SOD" : 
                case "_IX_ADD_IN_TRD" : 
                        /*
                         * NOTE :  
                         *      Il faut que le module BRAIN soit OUVERT dans ces cas.
                         */
                        if ( $("#slave_maximus").hasClass("this_hide") ) {
                            $(".jb-brn-opn-tgr").click();
                        }
                        $(".jb-brn-bk").click();
                        $("#brain_menu_new-ml").click();
                    break;
                case "_IX_START" :
                        /*
                         * On renvoie vers le dernier TIPs faisant partie de la liste des TIPs pour le tuto
                         */
                        _tbix -= 1;
                        i = _f_Gdf().tab_index[_tbix].i.toUpperCase();
                    break;
                default: 
                    break;
            }
            
            /*
             * On place l'élément de tel sorte qu'il se place au bon endroit quelque soit l'écran
             */
            _f_AcrtLoc(_tbix);
            
            /*
             * On s'assure que les TIPS sont visibles
             */
            _f_CmfrtOffset(i);
            
            //On navigue vers l'élément
            _f_Vw_ShTips(true,i);
            
            //On unlock le bouton
            $(x).data("lk",0);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    var _f_AcrtLoc = function (ix) {
        try {
            if ( KgbLib_CheckNullity(ix) ) {
                return;
            }
            
            var id = _f_Gdf().tab_index[ix].i.toUpperCase();
            var $rs, t, r, b, l;
            switch (id) {
                case "_IX_HOME" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top + $rs.height() + 6 + 3; 
                        /*
                         * NOTE :
                         *      85 correspond à la distance du coin gauche au pointer
                         */
                        l = $rs.offset().left + ( $rs.width() / 2) - 85;
                        
//                        l = $(".jb-tqr-fry-bx-bmx[data-target='home']").css({
                        $(".jb-tqr-fry-bx-bmx[data-target='home']").css({
//                            top : (t+59)+"px",
//                            left : (l+13)+"px"
                            top     : t,
                            left    : l
                        }); 
                    break;
                case "_IX_NEWSFEED" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        /*
                         * NOTE :
                         *      300 correspond à la distance du coin gauche au pointer
                         *      8 à un réajustement
                         */
                        l = $rs.offset().left + ( $rs.width() / 2) - 300 - 8; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='newsfeed']").css({
//                            top : (t+38)+"px",
//                            left : (l-283)+"px"
                            top     : (t+38),
                            left    : l
                        }); 
                    break;
                case "_IX_NOTIFICATION" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
//                        l = $rs.offset().left; 
                        /*
                         * NOTE :
                         *      300 correspond à la distance du coin gauche au pointer
                         *      8 à un réajustement
                         */
                        l = $rs.offset().left + ( $rs.width() / 2) - 300 - 8; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='notification']").css({
//                            top : (t+38)+"px",
//                            left : (l-283)+"px"
                            top     : (t+38),
                            left    : l
                        }); 
                    break;
                case "_IX_FRDCENTER" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
//                        l = $rs.offset().left; 
                        /*
                         * NOTE :
                         *      300 correspond à la distance du coin gauche au pointer
                         */
                        l = $rs.offset().left + ( $rs.width() / 2) - 300; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='frdcenter']").css({
//                            top : (t+34)+"px",
//                            left : (l-274)+"px"
                            top     : (t+34),
                            left    : l
                        }); 
                    break;
                case "_IX_USERBOX" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='userbox']").css({
                            top     : (t+73)+"px",
                            left    : (l-224)+"px"
                        }); 
                    break;
                case "_IX_PFLPIC" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
//                        l = $rs.offset().left; 
                        /*
                         * NOTE :
                         *      300 correspond à la distance du coin gauche au pointer
                         *      8 à un réajustement MANUEL
                         */
                        l = $rs.offset().left + ( $rs.width() / 2) - 300 - 8; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='pflpic']").css({
//                            top     : (t+55)+"px",
//                            left    : (l-276)+"px"
                            top     : (t+55), 
                            left    : l
                        }); 
                    break;
                case "_IX_ATHOME" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='athome']").css({ 
//                            top : (t+39)+"px",
//                            left : parseInt(l-306)+"px"
//                            left : (l-283)+"px"

                            top     : (t+39),
                            left    : parseInt(l-306)
                        }); 
                    break;
                case "_IX_COVER" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='cover']").css({
//                            top : (t-190)+"px",
//                            left : (l+272)+"px"
//                            left : (l+472)+"px"

                            top     : (t-190),
                            left    : (l+272)
                        });
                    break;
                case "_IX_MENUS_MYP" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='hdrmn_myp']").css({
//                            top     : (t+54)+"px",
//                            left    : (l-9)+"px"
                            top     : (t+54),
                            left    : (l-9)
                        });
                    break;
                case "_IX_MENUS_MYT" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='hdrmn_myt']").css({
//                            top : (t+54)+"px",
//                            left : (l-9)+"px"
                            top     : (t+54),
                            left    : (l-9)
                        });
                    break;
                case "_IX_MENUS_FAV" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='hdrmn_fav']").css({
                            top : (t+54)+"px",
                            left : (l-9)+"px"
                        });
                    break;
                case "_IX_HDRSTATS" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='hdrstats']").css({
//                            top : (t+87)+"px",
//                            left : (l+182)+"px"
                            top     : (t+87),
                            left    : (l+182)
                        });
                    break;
                case "_IX_BRAIN" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='brain']").css({
//                            top : (t-3)+"px",
//                            left : (l-382)+"px"  
                            top     : (t-3),
                            left    : (l-382) 
                        });
                    break;
                case "_IX_ADD_IN_IML_FRD" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
//                        t = $rs.offset().top;
//                        l = $rs.offset().left;
                        t = $rs.offset().top; 
                        /*
                         * NOTE :
                         *      371 : correspond à la taille supposé de la box. 
                         *          On la renseigne à la main car n'étant pas visible on ne peut pas en déduire la WIDTH
                         *      8 : Est une contrainte due à je ne sais quoi ..l lol
                         *          
                         */
                        l = $rs.offset().left - 371 - 8; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='iml_addin_frd']").css({
//                            top : (t-3)+"px",
//                            left : (l-382)+"px"  
                            top     : t,
                            left    : l
                        });
                    break;
                case "_IX_ADD_IN_IML_SOD" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
//                        t = $rs.offset().top; 
//                        l = $rs.offset().left; 
                        t = $rs.offset().top; 
                        /*
                         * NOTE :
                         *      371 : correspond à la taille supposé de la box. 
                         *          On la renseigne à la main car n'étant pas visible on ne peut pas en déduire la WIDTH
                         *      8 : Est une contrainte due à je ne sais quoi ..l lol
                         *          
                         */
                        l = $rs.offset().left - 371 - 8; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='iml_addin_sod']").css({
//                            top : (t-3)+"px",
//                            left : (l-382)+"px"  
                            top     : t,
                            left    : l
                        });
                    break;
                case "_IX_ADD_IN_TRD" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
//                        t = $rs.offset().top; 
//                        l = $rs.offset().left; 
                        t = $rs.offset().top; 
                        /*
                         * NOTE :
                         *      371 : correspond à la taille supposé de la box. 
                         *          On la renseigne à la main car n'étant pas visible on ne peut pas en déduire la WIDTH
                         *      8 : Est une contrainte due à je ne sais quoi ..l lol
                         *          
                         */
                        l = $rs.offset().left - 371 - 8; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='iml_addin_trd']").css({
//                            top : (t-3)+"px",
//                            left : (l-382)+"px"  
                            
                            top     : t,
                            left    : l
                        });
                    break;
                    /*
                case "_IX_TQRS" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
//                        Kxlib_DebugVars([$rs.id,t,l],true);
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='tqrstudio']").css({
                            top : (t-7)+"px",
                            left : (l+147)+"px"  
                        });
                    break;
                    //*/
                case "_IX_TLKBRD" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
//                        Kxlib_DebugVars([$rs.id,t,l],true);
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='tlkbrd']").css({
//                            top : (t+1)+"px",
//                            left : (l+383)+"px"  
                            top     : (t+1),
                            left    : (l+383)
                        });
                    break;
                case "_IX_LASTA" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
//                        Kxlib_DebugVars([$rs.id,t,l],true);
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='lasta']").css({
//                            top : (t-216)+"px",
//                            left : (l-0)+"px"  

                            top     : (t-236),
                            left    : (l-0) 
                        });
                    break;
                case "_IX_ASDBIO" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='asdbio']").css({
//                            top     : (t+9)+"px",
//                            left    : (l-380)+"px"  
                            top     : (t+9),
                            left    : (l-380) 
                        });
                    break;
                case "_IX_ASDSTATS" :   
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='asdstats']").css({
//                            top : (t+13)+"px", 
//                            left : (l-380)+"px"  
                            top     : (t+13),
                            left    : (l-380) 
                        });
                    break;
                case "_IX_ASDAPPS" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
                        l = $(".jb-tqr-fry-bx-bmx[data-target='asdapps']").css({
//                            top : (t+18)+"px",
//                            left : (l-381)+"px"   
                            top     : (t+18),
                            left    : (l-381) 
                        });
                        
                        /* //[DEPUIS 29-06-16]
                        if ( $(".jb-asd-apps-chc.selected").data("action") !== "goguidebox" ) {
                            $(".jb-asd-apps-chc[data-action='goguidebox']").click();
                        }
                        //*/                                                                            
                    break;
                case "_IX_TIA" :   
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top + $rs.height() + 6 + 3; 
                        /*
                         * NOTE :
                         *      85 correspond à la distance du coin gauche au pointer
                         */
                        l = $rs.offset().left + ( $rs.width() / 2) - 20 + 2;
                        
                        $(".jb-tqr-fry-bx-bmx[data-target='tia']").css({
                            top     : t,
                            left    : l
                        }); 
                    break;
                case "_IX_THEND" :
                    break;
                case "_IX_START" :
                        $rs = $(_f_Gdf().tab_index[ix].rs);
                        t = $rs.offset().top; 
                        l = $rs.offset().left; 
                        
                        l = $(".jb-tqr-fdry-invit-bmx").css({
                            /*
                            top : (t+41)+"px",
                            left : (l-287)+"px"  
                            //*/
//                            top : (t+43)+"px",
//                            left : parseInt((l-333))+"px"  
                            
                            top : (t+43),
                            left : parseInt((l-333)) 
                        });
                    break;
                default: 
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
            
    var _f_CmfrtOffset = function (i) {
        try {
            if ( KgbLib_CheckNullity(i) ) {
                return;
            }
            
            //wpt :WindoPositionTop
            var wpt = $(window).scrollTop();
            switch ( i ) {
                case "_IX_HOME" :
                case "_IX_NEWSFEED" :
                case "_IX_NOTIFICATION" :
                case "_IX_FRDCENTER" :
                case "_IX_USERBOX" :
                case "_IX_USERBOX" :
                case "_IX_PFLPIC" :
                case "_IX_ATHOME" :
                case "_IX_TIA" :
                        if ( wpt >= 215 ) {
                            $("html, body").animate({ scrollTop: "0px" });
                        }
                    break;
                case "_IX_COVER" : 
                        if ( wpt >= 155 ) {
                            $("html, body").animate({ scrollTop: "80px" });
                        }
                    break;
                case "_IX_MENUS_MYP" :
                case "_IX_MENUS_MYT" :
                case "_IX_MENUS_FAV" :
                        if ( $(window).height() < 640 | ( wpt < 200 || wpt >= 685 ) ) {
                            $("html, body").animate({ scrollTop: "280px" });
                        }
                    break;
                case "_IX_HDRSTATS" :
                        if ( $(window).height() < 340 | ( ( $(window).height() < 730 && wpt > 640 ) || wpt < 200 || wpt >= 685 ) ) {
                            $("html, body").animate({ scrollTop: "280px" });
                        }
                    break;
                case "_IX_BRAIN" :
                case "_IX_ADD_IN_IML_FRD" :
                case "_IX_ADD_IN_IML_SOD" :
                case "_IX_ADD_IN_TRD" :
                        if ( $(window).height() < 640 | ( wpt < 200 ) || wpt >= 755 ) {
                            $("html, body").animate({ scrollTop: "700px" });
                        }
                    break;
                    /*
                case "_IX_TQRS" :
                        if ( $(window).height() < 640 | ( wpt < 490 ) || wpt >= 1440 ) {
                            $("html, body").animate({ scrollTop: "500px" });
                        }
                    break;
                    //*/
                /*
                 * [DEPUIS 26-06-16]
                 */
                case "_IX_TLKBRD" :
                case "_IX_LASTA" :
                        if ( $(window).height() < 640 | ( wpt < 200 || wpt >= 685 ) ) {
                            $("html, body").animate({ scrollTop: "280px" });
                        }
                    break;          
                case "_IX_ASDBIO" :
                        if ( $(window).height() < 415 | ( wpt > 160 ) ) {
//                        if ( ( $(window).height() < 830 && wpt < 200 ) || wpt >= 755 ) {
                            $("html, body").animate({ scrollTop: "0px" });
                        }
                    break;
                case "_IX_ASDSTATS" : 
                        if ( $(window).height() < 580 | ( wpt > 285 ) ) {
//                        if ( ( $(window).height() < 830 && wpt < 200 ) || wpt >= 755 ) {
                            $("html, body").animate({ scrollTop: "150px" });
                        }
                    break;
                case "_IX_ASDAPPS" :
                        if ( $(window).height() < 700 | ( wpt >= 450 ) ) {
//                        if ( ( $(window).height() < 830 && wpt < 200 ) || wpt >= 755 ) {
                            $("html, body").animate({ scrollTop: "360px" });
                        }
                    break;
                case "_IX_START" :
                        $("html, body").animate({ scrollTop: "0px" });
                    break;
                default: 
                    break;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    /*********************************************************************************************************************************************/
    /***************************************************************** VIEW SCOPE ****************************************************************/
    /*********************************************************************************************************************************************/
    var _f_Vw_ShPnl = function (sh,wto) {
        try {
            //wto : _WITH_TIPS_OPTION
            
            /*
             * On masque les tips dans le cas où CALLER le demande
             */
            if ( ( KgbLib_CheckNullity(sh) || sh === false ) && wto === true ) {
                _f_Vw_ShTips();
            }
            
            /*
             * On change l'état du panneau en fonction de la demande
             */
            if ( sh ) {
                $(".jb-tqr-fry-hq-mx").switchClass("state-sleeping","state-wakeup");
            } else {
                $(".jb-tqr-fry-hq-mx").switchClass("state-wakeup","state-sleeping");
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    var _f_Vw_ShTips = function (sh,tgt) {
        try {
            if ( sh === true ) {
                /*
                 * [DEPUIS 26-06-16]
                 */
//                $(".jb-tqr-fry-bx-bmx").stop(true,true).fadeOut().addClass("this_hide").removeProp("style");
                $(".jb-tqr-fry-bx-bmx").not("[data-index='"+tgt+"']").stop(true,true).fadeOut().addClass("this_hide").removeProp("style");
                if (! tgt ) {
                    $(".jb-tqr-fry-bx-bmx").stop(true,true).hide().removeClass("this_hide").fadeIn().removeProp("style");
                } else {
                    $(".jb-tqr-fry-bx-bmx[data-index='"+tgt+"']").stop(true,true).hide().removeClass("this_hide").fadeIn();
//                    $(".jb-tqr-fry-bx-bmx[data-index='"+tgt+"']").stop(true,true).hide().removeClass("this_hide").fadeIn().removeProp("style");
                }
            } else {
                if (! tgt ) {
                    $(".jb-tqr-fry-bx-bmx").stop(true,true).fadeOut(100).addClass("this_hide").removeProp("style");
                } 
            } 
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    /*********************************************************************************************************************************************/
    /**************************************************************** SERVER SCOPE ***************************************************************/
    /*********************************************************************************************************************************************/
    
    
    /*********************************************************************************************************************************************/
    /************************************************************** LISTENERSS SCOPE *************************************************************/
    /*********************************************************************************************************************************************/
    
    $(".jb-tqr-fdry-hq-opt-tgr, .jb-tqr-fdry-invt-start-tgr, .jb-tqr-fdry-invt-strt-alwz, .jb-tqr-fdry-invt-clz-tgr").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
    
    /*********************************************************************************************************************************************/
    /***************************************************************** INIT SCOPE ****************************************************************/
    /*********************************************************************************************************************************************/
    _f_Init();
//    _f_Init(true, true);
    /*
    _f_Init(true);
    _f_Start();
    //*/
}

new FSTVST();