
define("sapp/sapp-picqr", function () {
    return function() {
        
        var _xhr_plars;
        
        /*******************************************************************************************************************************************************************/
        /**************************************************************************** PROCESS SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/

        var _f_Gdf = function () {
            var dt = {
                "" : "",
            }; 

            return dt;
        };
        
        var _f_Init = function () {
            try {
                _f_Autoload();
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };

        var _f_Action = function (x,a){
            try {
                if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity(a) && KgbLib_CheckNullity($(x).data("action")) ) ) {
                    return;
                }

                var _a = ( KgbLib_CheckNullity(a) ) ? $(x).data("action") : a;
                _a = ( _a && /^pref-sl/.test(_a) ) ? "pref-select" : _a; 
                switch (_a) {
                    case "pref-io" :
                            _f_PrefIo(x,_a);
                        break;
                    case "pref-select" :
                            _f_PrefA(x,_a);
                        break;
                    case "tree-mp-ap" :
                    case "tree-mp-iml" :
                    case "tree-mp-iml-pri" :
                    case "tree-mp-iml-pub" :
                    case "tree-mp-iml-hstd" :
                    case "tree-mp-itr" :
                    case "tree-mp-itr-mine" :
                    case "tree-mp-itr-folwd" :
                    case "tree-mp-fav" :
                    case "tree-mp-fav-pub" :
                    case "tree-mp-fav-pri" :
                            _f_TreePicA(x,_a);
                        break;
                    case "tree-album-akx" :
                            _f_TreeAlbSA(x,_a);
                        break;    
                    case "tree-collec-akx" :
                            _f_TreeClcSA(x,_a);
                        break;
                    case "tree-album-create" :
                    case "tree-collec-create" :
                    case "back-tree" :
                            _f_AdFrmIo(x,_a);
                        break;
                    case "load_more" :
                            _f_LdMr(x);
                        break;
                    default:
                        return;
                }

            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };

        var _f_PrefIo = function (x,a){
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                var b = $(".jb-tqr-dscvr-picqr-h-s-chcs"), s = $(b).data("scp"), g;
                switch (s) {
                    case "mypi" :
                            g = $(b).find(".jb-tqr-dscvr-picqr-h-s-grp[data-scp='"+s+"']");
                        break;
                    default :
                        return;
                }
                
                if ( g && g.length ) {
                    if ( g.hasClass("this_hide") ) {
                        $(g).stop(true,true).hide().removeClass("this_hide").show("blind",300);
                    } else {
                        $(g).stop(true,true).hide("blind").addClass("this_hide");
                    }
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_PrefA = function (x,a){
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                var b = $(".jb-tqr-dscvr-picqr-h-s-chcs"), a = $(x).data("action");
                alert(a);
                switch (a) {
                    case "pref-sl-nf" : //SeLect-NewerFirst
                        break;
                    case "pref-sl-mlf" : //SeLect-MostLoveFirst
                        break;
                    case "pref-sl-mrf" : //SeLect-MostReacionFirst
                        break;
                    default :
                        return;
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_TreePicA = function (x,a){
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                if (! KgbLib_CheckNullity(_xhr_plars) ) {
                    $(x).data("lk",0);
                    return;
                }
                
                var scrn = $("jb-tqr-dscvr-picqr-sub-sect"), subt;
                switch (a) {
                    case "tree-mp-ap" : //MyPic-AllPictures
                            subt = "Photos de ma vie";
                        break;
                    /****************** IN MYLIFE ******************/
                    case "tree-mp-iml" : //MyPic-InMyLife
                            subt = "Ce qui se passe dans ma vie";
                        break;
                    case "tree-mp-iml-pri" :
                            subt = "Photos & vidéos privées";
                        break;
                    case "tree-mp-iml-pub" :
                            subt = "Photos & vidéos du jour";
                        break;
                    case "tree-mp-iml-hstd" :
                            subt = "Publications hébergées (non repertoriées)";
                        break;
                    /****************** IN TREND ******************/
                    case "tree-mp-itr" : //MyPic-InTRend
                            subt = "Publications de Tendances";
                        break;
                    case "tree-mp-itr-mine" :
                            subt = "Ajoutées dans mes Tendances";
                        break;
                    case "tree-mp-itr-folwd" :
                            subt = "Ajoutées dans les Tendances que je suis";
                        break;
                    /****************** FAVORITE ******************/
                    case "tree-mp-fav" : //MyPic-FAVorite
                            subt = "Mes publications favorites";
                        break;
                    case "tree-mp-fav-pub" :
                            subt = "Mes favoris publics";
                        break;
                    case "tree-mp-fav-pri" :
                            subt = "Mes favoris privés";
                        break;
                    default :
                        return;
                }
                
                
//                Kxlib_DebugVars([a,subt],true);
                
                /*
                 * ETAPE :
                 *      On change les filtres
                 */
                $(".jb-tqr-dscvr-picqr-h-s-grp.selected").removeClass("selected");
                $(".jb-tqr-dscvr-picqr-h-s-grp[data-scp='mypi']").addClass("selected");
                
                /*
                 * ETAPE :
                 */
                $(".jb-tqr-dscvr-p-h-t-t-main").text("Mes photos");
                if ( subt ) {
                    $(".jb-tqr-dscvr-p-h-t-t-subtle").text(subt);
                    $(".jb-tqr-dscvr-picqr-h-t-tle > *").removeClass("this_hide");
                } else {
                    $(".jb-tqr-dscvr-picqr-h-t-tle").children(":not(.jb-tqr-dscvr-p-h-t-t-main)").addClass("this_hide");
                    $(".jb-tqr-dscvr-p-h-t-t-subtle").text("");
                }
                
                /*
                 * ETAPE :
                 */
                var $cur = $(".jb-tqr-dscvr-picqr-tree-a.active");
                var $tar = $(".jb-tqr-dscvr-picqr-tree-a").filter("[data-action='"+a+"']");
                if ( $tar.length === 1 ) {
                    if ( $cur.is($tar) ) {
                        return;
                    } else {
//                        alert("Lancer le chargement des nouvelles données !");
                        $cur.removeClass("active");
                        $tar.addClass("active");
                    }
                }
                
                /*
                 * ETAPE :
                 *      On réinitialise cette valeur dès le départ pour plus de cohérence
                 */
                $(".jb-tqr-dr-pr-h-t-anb").text("-");
                
                _f_ChgMn(x);
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_AdFrmIo = function (x,a){
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                var nm, iso = false;
                switch (a) {
                    case "tree-album-create" :
                            nm = "album";
                            iso = true;
                        break
                    case "tree-collec-create" :
                            nm = "collec";
                            iso = true;
                        break;
                    case "back-tree" :
                            nm = $(x).closest(".jb-tqr-dscvr-picqr-f-c").data("scp");
                        break;
                    default : 
                        return;
                }
                
                var tbx = $(".jb-tqr-dscvr-picqr-tree-mx"), fbx = $(".jb-tqr-dscvr-picqr-f-c[data-scp='"+nm+"']");
                if (! tbx.length ) {
                    return;
                }
                
                if (! iso ) {
                    tbx.hide().removeClass("this_hide");
                    fbx.stop(true,true).hide("slide",{direction:"right"},300,function(){
                        $(this).addClass("this_hide");
                    });
                    tbx.stop(true,true).show("slide",{direction:"left"},300);
                } else {
                    fbx.hide().removeClass("this_hide");
                    tbx.stop(true,true).hide("slide",{direction:"left"},300,function(){
                        $(this).addClass("this_hide");
                    });
                    fbx.stop(true,true).show("slide",{direction:"right"},300);
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_TreeAlbSA = function (x,a){
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                var subt = $(x).text();
                
                /*
                 * ETAPE :
                 *      On change les filtres
                 */
                $(".jb-tqr-dscvr-picqr-h-s-grp.selected").removeClass("selected");
                $(".jb-tqr-dscvr-picqr-h-s-grp[data-scp='mypi']").addClass("selected");
                
                /*
                 * ETAPE :
                 */
                $(".jb-tqr-dscvr-p-h-t-t-main").text("Album");
                if ( subt ) {
                    $(".jb-tqr-dscvr-p-h-t-t-subtle").text(subt);
                    $(".jb-tqr-dscvr-picqr-h-t-tle > *").removeClass("this_hide");
                } else {
                    $(".jb-tqr-dscvr-picqr-h-t-tle").children(":not(.jb-tqr-dscvr-p-h-t-t-main)").addClass("this_hide");
                    $(".jb-tqr-dscvr-p-h-t-t-subtle").text("");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_TreeClcSA = function (x,a){
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                var subt = $(x).text();
                
                /*
                 * ETAPE :
                 *      On change les filtres
                 */
                $(".jb-tqr-dscvr-picqr-h-s-grp.selected").removeClass("selected");
                $(".jb-tqr-dscvr-picqr-h-s-grp[data-scp='mypi']").addClass("selected");
                
                /*
                 * ETAPE :
                 */
                $(".jb-tqr-dscvr-p-h-t-t-main").text("Collection");
                if ( subt ) {
                    $(".jb-tqr-dscvr-p-h-t-t-subtle").text(subt);
                    $(".jb-tqr-dscvr-picqr-h-t-tle > *").removeClass("this_hide");
                } else {
                    $(".jb-tqr-dscvr-picqr-h-t-tle").children(":not(.jb-tqr-dscvr-p-h-t-t-main)").addClass("this_hide");
                    $(".jb-tqr-dscvr-p-h-t-t-subtle").text("");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_Autoload = function () {
            try {
                var $arts = $(".jb-tqr-d-p-mdl-lst-mx").find(".jb-tqr-dscvr-picqr-mdl-mx");
                if (! $arts.length ) {
                    var prm = {
                        sc : "MYLIFE_ALL",
                        dr : "FST",
                        fl : "DEFAULT",
                        pi : "",
                        pt : ""
                    };
                    
//                    Kxlib_DebugVars([JSON.stringify(prm)],true);
                    
                    _f_Phototek("autoload",prm.sc,prm.dr,prm.fl,prm.pi,prm.pt);
                    
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_ChgMn = function (x) {
            try {
                
                if ( KgbLib_CheckNullity(x) ) {
                    return;
                }
                
                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                $(x).data("lk",1);
                
                if (! KgbLib_CheckNullity(_xhr_plars) ) {
                    _f_Spnr("load_more",false);
                    $(x).data("lk",0);
                    return;
                }
                
                var $arts = $(".jb-tqr-d-p-mdl-lst-mx").find(".jb-tqr-dscvr-picqr-mdl-mx");
                
                if ( $arts.length ) {
                    $arts.remove();
                    
                    $(".jb-tqr-d-p-m-l-spnr").data("lk",1);
                }
                
                /*
                 * ETAPE :
                 *      Dans tous les cas, on masque NONE
                 */
                _f_None();
                        
//                _f_Spnr("load_more",true);
                _f_Loading(true);
                
                var prm = {
                    sc : _f_GetSectionCode(),
                    dr : "FST",
                    fl : "DEFAULT",
                    pi : "",
                    pt : ""
                };
                
//                Kxlib_DebugVars([JSON.stringify(prm)],true);
//                return;
                
                _f_Phototek("change_menu",prm.sc,prm.dr,prm.fl,prm.pi,prm.pt,x);
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_LdMr = function (x) {
            try {
                if ( KgbLib_CheckNullity(x) ) {
                    return;
                }
                
                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                $(x).data("lk",1);
                
                if (! KgbLib_CheckNullity(_xhr_plars) ) {
                    _f_Spnr("load_more",false);
                    $(x).data("lk",0);
                    return;
                }
                
                var $arts = $(".jb-tqr-d-p-mdl-lst-mx").find(".jb-tqr-dscvr-picqr-mdl-mx");
                
                if (! $arts.length ) {
                    _f_Spnr("load_more",false);
                    $(x).data("lk",0);
                    return;
                }
                
                /*
                 * ETAPE :
                 *      Dans tous les cas, on masque NONE
                 */
                _f_None();
                
                _f_Spnr("load_more",true);
                
                var prm = {
                    sc : _f_GetSectionCode(),
                    dr : "BTM",
                    fl : "DEFAULT",
                    pi : $arts.filter(":last").data("item"),
                    pt : $arts.filter(":last").data("time")
                };
                
                if ( KgbLib_CheckNullity(prm.pi) | KgbLib_CheckNullity(prm.pt) ) {
//                    alert("Not reference");
                    _f_Spnr("load_more",false);
                    $(x).data("lk",0);
                    return;
                }
                
//                Kxlib_DebugVars([JSON.stringify(prm)],true);
//                return;
                
                _f_Phototek("load_more",prm.sc,prm.dr,prm.fl,prm.pi,prm.pt,x);
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_Phototek = function (cz,sc,dr,fl,pi,pt,x) {
            try {
                if ( KgbLib_CheckNullity(cz) | KgbLib_CheckNullity(sc) | KgbLib_CheckNullity(dr) | KgbLib_CheckNullity(fl) ) {
                    return;
                }
                
                var s = $("<span/>"), xt = (new Date()).getTime();
                _f_Srv_PlAr(sc,dr,fl,pi,pt,xt,s);
                
                $(s).on("datasready",function(e,ds){
                    if ( KgbLib_CheckNullity(ds) ) {
                        return;
                    }
                    
//                    Kxlib_DebugVars([JSON.stringify(ds.alist)],true);

                    /*
                     * ETAPE :
                     *      Dans tous les cas, on masque NONE
                     */
                    _f_None();
                    
                    /*
                     * ETAPE :
                     *      Dans le cas d'AUTOLOAD
                     */
                    if ( cz === "autoload" ) {
                        $(".jb-tqr-d-pqr-mdl-l-spnr-mx").addClass("this_hide");
                        $(".jb-tqr-d-p-m-l-spnr").removeClass("this_hide");
                    } else {
                        _f_Loading(false);
                    }
                    
                    _f_DispAl(cz,ds.alist,dr);
                    
                    $(".jb-tqr-dr-pr-h-t-anb").text(ds.anb);
                    
                    /*
                     * ETAPE : 
                     *      Reinitialisation des éléments
                     */
                    _xhr_plars = null;
                    _f_Spnr("load_more",false);
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    if ( cz === "change_menu" ) {
                        $(".jb-tqr-d-p-m-l-spnr").data("lk",0);
                    }
                });
                
                $(s).on("operended",function(e){
                    
                    _f_Loading(false,false);
                    
                    /*
                     * ETAPE : 
                     *      On affiche NONE dans le cas où il n'y a pas d'ARTICLES présents
                     */
                    if (! $(".jb-tqr-dscvr-picqr-mdl-mx").length ) {
                        _f_None(true);
                    }
                    
                    
                    _xhr_plars = null;
                    _f_Spnr("load_more",false);
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    if ( cz === "change_menu" ) {
                        $(".jb-tqr-d-p-m-l-spnr").data("lk",0);
                    }
                });
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_DispAl = function (cz,ds,dr) {
            try {
                if ( KgbLib_CheckNullity(cz) | KgbLib_CheckNullity(ds) | KgbLib_CheckNullity(dr) ) {
                    return;
                }
                
//                if ( cz === "autoload" ) {
//                    $(".jb-tqr-d-p-mdl-lst-mx").masonry({
//                        // options
//                        itemSelector    : '.jb-tqr-dscvr-picqr-mdl-mx',
//                        columnWidth     : 200,
//                        isFitWidth      : true,
//                        "gutter"        : 3
//                    });
//                }
                
                $(".jb-tqr-d-p-mdl-lst-mx").removeClass("this_hide");
                
                $.each(ds,function(i,atb){
                    if ( $(".jb-tqr-d-p-mdl-lst-mx").find(".jb-tqr-dscvr-picqr-mdl-mx").filter("[data-item='"+atb.id+"']").length ) {
                        return true;
                    }
                    
                    var m = _f_PprAr(atb);
                    m = _f_RbdAr(m);
                     
                    $(m).hide().appendTo(".jb-tqr-d-p-mdl-lst-mx").fadeIn();
                });
                
                if ( cz === "autoload" ) {
//                    alert("autoload");
                    $(".jb-tqr-d-p-mdl-lst-mx").masonry({
                        // options
                        itemSelector    : '.jb-tqr-dscvr-picqr-mdl-mx',
                        columnWidth     : 200,
                        isFitWidth      : true,
                        "gutter"        : 3
                    });
                    
                    var nm = $(".jb-tqr-dscvr-gbl-sect.active").data("name");
                    if ( nm === "picqr" ) {
                        $(".jb-tqr-d-p-mdl-lst-mx").masonry("reloadItems");
                        $(".jb-tqr-d-p-mdl-lst-mx").masonry("layout");
                    }
                    
                    
                } else { 
                    $(".jb-tqr-d-p-mdl-lst-mx").masonry("reloadItems");
                    $(".jb-tqr-d-p-mdl-lst-mx").masonry("layout");
                }
                
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_GetSectionCode = function () {
            try {

                var sec = $(".jb-tqr-dscvr-picqr-tree-a.active").data("action");
                switch (sec) {
                    case "tree-mp-iml" : 
                        return "MYLIFE_ALL";
                    case "tree-mp-iml-pri" : 
                        return "MYLIFE_REST";
                    case "tree-mp-iml-pub" : 
                        return "MYLIFE_STORY";
                    case "tree-mp-iml-hstd" : 
                        return "MYLIFE_HOSTED";
                    case "tree-mp-itr" : 
                        return "TREND_ALL";
                    case "tree-mp-itr-mine" : 
                        return "TREND_MINE";
                    case "tree-mp-itr-folwd" : 
                        return "TREND_FOLLOWED";
                    case "tree-mp-fav" : 
                        return "FAV_ALL";
                    case "tree-mp-fav-pub" : 
                        return "FAV_PUBLIC";
                    case "tree-mp-fav-pri" : 
                        return "FAV_PRIVATE";
                    default :
                        return;
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_Loading = function (is,hspr) {
            //hspr : HandleSPinneR
            try {
                hspr = ( KgbLib_CheckNullity(hspr) ) ? true : hspr;
                if ( is ) {
                    //ETAPE : On masque la zone d'affichage des ARTICLES
                    $(".jb-tqr-d-p-mdl-lst-mx").addClass("this_hide");
                    
                    if ( hspr ) {
                        //ETAPE : On masque le spinner LOAD_MORE
                        $(".jb-tqr-d-p-m-l-spnr").addClass("this_hide");
                    }
                    
                    //ETAPE : On affiche la zone LOADING
                    $(".jb-tqr-d-pqr-mdl-l-spnr-mx").removeClass("this_hide");
                    
                    //ETAPE : On rend disponible la zone d'affichage des ARTICLES
                    $(".jb-tqr-d-p-mdl-lst-mx").removeClass("this_hide");
                    
                } else {
                    //ETAPE : On masque la zone LOADING
                    $(".jb-tqr-d-pqr-mdl-l-spnr-mx").addClass("this_hide");
                    
                    //ETAPE : On affiche la zone d'affichage des ARTICLES
                    $(".jb-tqr-d-p-mdl-lst-mx").removeClass("this_hide");
                    
                    if ( hspr ) {
                        //ETAPE : On affiche le spinner LOAD_MORE
                        $(".jb-tqr-d-p-m-l-spnr").removeClass("this_hide");
                    }
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        

        /*******************************************************************************************************************************************************************/
        /*************************************************************************** SERVERS SCOPE *************************************************************************/
        /*******************************************************************************************************************************************************************/

        var _Ax_PlAr = Kxlib_GetAjaxRules("TQR_TIA_PCQR_PL");
        var _f_Srv_PlAr = function(sc,dr,fl,pi,pt,xt,s) {
            if ( KgbLib_CheckNullity(sc) | KgbLib_CheckNullity(dr) | KgbLib_CheckNullity(fl) | KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(s) ) {
                return;
            }
        
            var onsuccess = function (datas) {
                try {
                    if (! KgbLib_CheckNullity(datas) ) {
                        datas = JSON.parse(datas);
                    } else {
                        _xhr_plars = null;
                        return;
                    }

                    if (! KgbLib_CheckNullity(datas.err) ) {
                        _xhr_plars = null;
                        if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                            switch (datas.err) {
                                case "__ERR_VOL_ACC_GONE" :
                                case "__ERR_VOL_USER_GONE" :
                                case "__ERR_VOL_U_G" :
                                case "__ERR_VOL_CU_GONE" :
                                        Kxlib_HandleCurrUserGone();
                                    break;
                                case "__ERR_VOL_FAILED" :
                                    //TODO : Retirer l'image en Buffer
                                    //Afficher le message adéquat dans la zone d'erreur
                                        Kxlib_AJAX_HandleFailed();
                                    break;
                                case "__ERR_VOL_DENY" :
                                case "__ERR_VOL_DENY_AKX" :
                                    break;
                                case "__ERR_VOL_DNY_AKX_AUTH" :
                                        if ( $(".jb-tqr-btm-lock").length ) {
                                            $(".jb-tqr-btm-lock").removeClass("this_hide");
                                            $(".jb-tqr-btm-lock-fd").removeClass("this_hide");
                                        }
                                    break;
                                default :
                                        Kxlib_AJAX_HandleFailed();
                                    break;
                            }
                        } 
                        return;
                    } else if ( !KgbLib_CheckNullity(datas.return) && !KgbLib_CheckNullity(datas.return.alist) ) {
                        var ds = [datas.return];
                        $(s).trigger("datasready",ds);
                    } else if ( !KgbLib_CheckNullity(datas.return) ) {
                        $(s).trigger("operended");
                        return;
                    }

                } catch (ex) {
                    _xhr_plars = null;

                    //TODO : Renvoyer l'erreur au serveur
                    //                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                    return;
                }
            };
        
            var onerror = function(a,b,c) {
                /*
                 * [DEPUIS 26-08-15] @author BOR
                 */
                Kxlib_AjaxGblOnErr(a,b);

                //TODO : Send error to SERVER
                //            Kxlib_DebugVars(["AJAX ERR : "+nwtrdart_uq],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            };
        
            var curl = document.URL;
            var toSend = {
                "urqid": _Ax_PlAr.urqid,
                "datas": {
                    "sc"    : sc,
                    "dr"    : dr,
                    "fl"    : fl,
                    "pi"    : pi,
                    "pt"    : pt,
                    "xt"    : xt,
                    "cu"    : curl 
                }
            };
        
            _xhr_plars = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlAr.url, wcrdtl : _Ax_PlAr.wcrdtl });
        };
                
        /*******************************************************************************************************************************************************************/
        /**************************************************************************** VIEW SCOPE ***************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        var _f_PprAr = function (d) {
            try {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                Kxlib_DebugVars([d,JSON.stringify(d)],true);
//                alert(d.id);
                
                var str__;
                if ( d.hasOwnProperty("ustgs") && !KgbLib_CheckNullity(d.ustgs) && typeof d.ustgs === "object" ) {
                    var istgs__ = [];
                    $.each(d.ustgs, function(x,v) {
                        var rw__ = [];
                        $.map(v, function(e,x) {
                            rw__.push(e);
                        });
                        istgs__.push(rw__.join("','"));
                    });

                    str__ = ( istgs__.length > 1 ) ? istgs__.join("'],['") : istgs__[0];
                    str__ = "['" + str__ + "']";
                }
                 
                var am = "<article class=\"tqr-dscvr-picqr-mdl-mx jb-tqr-dscvr-picqr-mdl-mx jb-unq-bind-art-mdl\" data-item=\"\" data-time=\"\" data-tr=\"\" data-atype=\"\" data-hatr=\"\" data-ajcache=\"\" data-with=\"\" data-vidu=\"\" data-trq-ver='ajca-v10'>";
                am += "<a class=\"tqr-dscvr-picqr-mdl-a jb-tqr-dscvr-picqr-mdl-a\" href=\"javascript:;\">";
                am += "<span class=\"tqr-dscvr-picqr-mdl-fd jb-tqr-dscvr-picqr-mdl-fd\"></span>";
                am += "<img class=\"tqr-dscvr-picqr-mdl-i jb-tqr-dscvr-picqr-mdl-i\" width=\"200\" height=\"200\" src=\"\" />";
                am += "</a>";
                am += "</article>";
                am = $.parseHTML(am);
                
                /*
                 * ETAPE :
                 *      Insertion des données dans L'ENTENTE
                 */
                $(am)
                    .attr("id","post-tia-ex-aid-".concat(d.id))
                    .data("item",d.id).attr("data-item",d.id)
                    .data("time",d.time).attr("data-time",d.time)
                    .data("atype","tia-phtotk").attr("data-atype","tia-phtotk")
                    .data("istr",d.istrd).attr("data-istr",d.istrd)
                    .data("hatr",d.hatr).attr("data-hatr",d.hatr)
                    .data("hasfv",d.hasfv).attr("data-hasfv",d.hasfv)
                    .data("fvtp",d.fvtp).attr("data-fvtp",d.fvtp)
                    .data("with",str__).attr("data-with",str__)
                    .data("vidu",d.vidu).attr("data-vidu",d.vidu)
                    .data("ajcache",JSON.stringify(d)).attr("data-ajcache",JSON.stringify(d));
                
                $(am).find(".jb-tqr-dscvr-picqr-mdl-i").attr("src",d.img);
                
                if ( d.hasOwnProperty("vidu") && d.vidu ) {
                    $(am).find(".jb-tqr-dscvr-picqr-mdl-fd").addClass("vidu");
                }
                
                return am;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_RbdAr = function (m) {
            try {
                if ( KgbLib_CheckNullity(m) ) {
                    return;
                }
                
                $(m).find(".tqr-dscvr-picqr-mdl-fd").hover(function(e){
                    var b = $(this).closest(".jb-tqr-dscvr-picqr-mdl-mx");
                     b.find(".jb-tqr-dscvr-picqr-mdl-fd").stop(true,true).animate({
                        "background-color" : "rgba(0,0,0,0.25)"
                    },300);
                },function(e){
                    var b = $(this).closest(".jb-tqr-dscvr-picqr-mdl-mx");
                    b.find(".jb-tqr-dscvr-picqr-mdl-fd").stop(true,true).animate({
                        "background-color" : "rgba(0,0,0,0.1)"
                    },300);
                });
                
                $(m).find(".jb-tqr-dscvr-picqr-mdl-a").off("click").click(function(e){
                    Kxlib_PreventDefault(e);
                    (new Unique ()).OnOpen("tia-phtotk",this);
                });
                
                return m;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_Spnr = function (scp,shw) {
            try {
                if ( KgbLib_CheckNullity(scp) ) {
                    return;
                }
                
                var $spnr;
                switch (scp) {
                    case "load_more" :
                            $spnr = $(".jb-tqr-d-p-m-l-spnr");
                        break;
                    default :
                        return;
                }
                
                if ( shw ) {
                    $spnr.find("._this_tgr").addClass("this_hide");
                    $spnr.find("._this_spnr").removeClass("this_hide");
                } else {
                    $spnr.find("._this_spnr").addClass("this_hide");
                    $spnr.find("._this_tgr").removeClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_None = function (shw) {
            try {
                
                if ( shw ) {
                    $(".jb-tqr-d-pqr-mdl-l-nne-mx").removeClass("this_hide");
                } else {
                    $(".jb-tqr-d-pqr-mdl-l-nne-mx").addClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        /*******************************************************************************************************************************************************************/
        /************************************************************************** LISTENERS SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/

        
        $(".tqr-dscvr-picqr-mdl-fd").hover(function(e){
            var b = $(this).closest(".jb-tqr-dscvr-picqr-mdl-mx");
             b.find(".jb-tqr-dscvr-picqr-mdl-fd").stop(true,true).animate({
                "background-color" : "rgba(0,0,0,0.25)"
            },300);
            /*
            var ps = { "t" : $(b).position().top, "l" : $(b).position().left };
            $(b).find(".jb-tqr-dscvr-picqr-mdl-i").stop(true,true).animate({
                "width" : "206",
                "height" : "206"
            },300);
            $(b).stop(true,true).animate({
                "width"     : "206",
                "height"    : "206",
                "top"       : ps.t-3,
                "left"      : ps.l-3
            },300).data("ps",ps);
            //*/
        },function(e){
            var b = $(this).closest(".jb-tqr-dscvr-picqr-mdl-mx");
            b.find(".jb-tqr-dscvr-picqr-mdl-fd").stop(true,true).animate({
                "background-color" : "rgba(0,0,0,0.1)"
            },300);
            /*
            var ps = { "t" : $(b).data("ps").t, "l" : $(b).data("ps").l };
            $(b).find(".jb-tqr-dscvr-picqr-mdl-i").stop(true,true).animate({
                "width" : "200",
                "height" : "200"
            },300);
            $(b).stop(true,true).animate({
                "width"     : "200",
                "height"    : "200",
                "top"       : ps.t,
                "left"      : ps.l
            },300).data("ps",ps);
        //*/
        });


        $(".jb-tqr-dscvr-picqr-h-s-chc, .jb-tqr-dscvr-picqr-tree-a, .jb-tqr-dscvr-picqr-f-c-c-btn, .jb-tqr-d-p-m-l-spnr").click(function(e){
            Kxlib_PreventDefault(e);
            
            _f_Action(this);
        });

        /*******************************************************************************************************************************************************************/
        /**************************************************************************** INNIT SCOPE **************************************************************************/
        /*******************************************************************************************************************************************************************/

        $(".jb-tqr-d-p-mdl-lst-mx").masonry({
            // options
            itemSelector    : '.jb-tqr-dscvr-picqr-mdl-mx',
            columnWidth     : 200,
            isFitWidth      : true,
            "gutter"        : 3
        });
        
        /*
         * [NOTE ]
         *      Peut être utilisé pour des raisons de DEV, TEST, DEBUG.
         *      On ne lance pas la procédure pour des raisons de performance en privilégiant EXPLORER
         */
        _f_Init(); 
        
    };
});
