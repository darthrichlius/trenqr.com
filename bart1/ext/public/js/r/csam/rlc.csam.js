define("r/csam/rlc.csam",function(){
    //cfd : ConstructorFeed
    return function RLC(cfd) {
        /*
         * {
         *      trigger     : Le bouton qui est à l'origine l'évènement
         *      action      : Le libellé de l'action  
         *      autoload    : Lancer l'affichage du viewer si les données sont passées et qu'elles sont valides
         * }
         */
        var cargs = cfd;

        /*
         *  iRdy        : IsrReaDY
         */
        var iRdy;
        /*
         * 
         */
        var trgList = [".jb-tqr-tsty-art-opt[data-action='post-react-open']"];
        var cfdKeys = ["trigger","action"];
        
        var $sprt = $(".jb-tqr-rlctr-sprt");
        
        var _xhr_plrl, _xhr_rel_ax;
        
        /*******************************************************************************************************************************************************************/
        /**************************************************************************** PROCESS SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        
        var _f_Gdf = function () {
            var dt = {
            }; 

            return dt;
        };
        
        
        var _f_Init = function () {
            try {
                
               /*
                * ETAPE :
                *      On vérifie qu'on a les éléments de base 
                */
                if ( cargs && _f_Ctrl_Init(cargs) ) {
                    
                    if ( $(".jb-tqr-rlc-b-a-l-amx").length ) {
                         
                        $(".jb-tqr-rlctr-b-a-lst-mx").masonry({
                            // options
                            itemSelector    : '.jb-tqr-rlc-b-a-l-amx',
                            columnWidth     : 260,
                            isFitWidth      : true,
                            "gutter"        : 20
                        });
                        
//                        $(".jb-tqr-rlctr-bdy-mx").perfectScrollbar({
//                            suppressScrollX : true
//                        });

//                        $(".jb-tqr-rlctr-b-a-lst-mx").masonry("reloadItems");
//                        $(".jb-tqr-rlctr-b-a-lst-mx").masonry("layout");
                    }
                    
                    if ( cargs && cargs.hasOwnProperty("trigger") && $(cargs.trigger).length ) {
                        var scp = $(cargs.trigger).data("scp");
                        if (! KgbLib_CheckNullity(scp) ) {
                            switch (scp) {
                                case "follower" :
                                        $(".jb-tqr-rlctr-h-mn-l-ax").removeClass("active");
                                        $(".jb-tqr-rlctr-h-mn-l-ax[data-sec='_SEC_FOLW']").addClass("active");
                                    break;
                                case "following" :
                                        $(".jb-tqr-rlctr-h-mn-l-ax").removeClass("active");
                                        $(".jb-tqr-rlctr-h-mn-l-ax[data-sec='_SEC_FOLG']").addClass("active");
                                    break;
                                default :
                                        $(".jb-tqr-rlctr-h-mn-l-ax").removeClass("active");
                                        $(".jb-tqr-rlctr-h-mn-l-ax.default").addClass("active");
                                    return;
                            }
                        }
                    }
                     
                    iRdy = true;

                    if ( cargs.hasOwnProperty("autoload") && cargs.autoload ) {
                        open();
                    }
                    
                }
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Ctrl_Init = function (a) {
            try {
                if ( KgbLib_CheckNullity(a) ) {
                    return;
                } 
                
                var keys = _.keys(a);
//                Kxlib_DebugVars([cfd,a,keys,_.intersection(keys,cfdKeys).length],true);
                return ( keys && _.intersection(keys,cfdKeys).length >= 2 ) ? true : false; //2 correspond au nombre d'éléments obligatoires attendus
                    
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Action = function (x,a) {
            try {
                if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && !a ) ) {
                    return;
                } 
                
                var _a = ( KgbLib_CheckNullity($(x).data("action")) ) ? a : $(x).data("action"); 
                switch (_a) {
                    case "rlc-sprt-clz" :
                            _f_Io(x,_a);
                        break;
                    case "rlc-sw-menu" :
                            _f_SwMn(x,_a);
                        break;
                    default :
                        return;
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Io = function (x,a) {
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }

                /*
                 * ETAPE :
                 *      On réinitialise la zone de CONFIRMATION de SUPPRESSION pour une prochaine utilisation.
                 */
                $(".jb-tlkb-uqv-a-m-m-m-dl-cfbx").addClass("this_hide");
                $(".jb-tlkb-uqv-a-m-m-m-dl-btn").removeClass("this_hide");

                switch (a) {
                    case "rlc-sprt-clz" :
                            /*
                             * ETAPE
                             *      On active le système OVERFLOW de WINDOW
                             */
                            Kxlib_WindOverflow();
                            
                            /*
                             * ETAPE
                             *      On masque la fenetre
                             */
                            $(".jb-tqr-rlctr-sprt").addClass("this_hide");
                            
                            /*
                             * ETAPE :
                             *      On reset complètement la fenetre
                             */
                            $(".jb-tqr-rlc-b-a-l-amx").remove();
                            _f_Vw_SwMn($(".jb-tqr-rlctr-h-mn-l-ax.default"));
                            _f_WtPnl(true);
                            _f_swldmr(true);
                            if (! KgbLib_CheckNullity(_xhr_plrl) ) {
                                _xhr_plrl.abort();
                                _xhr_plrl = null;
                            }
                            $(".jb-tqr-rlctr-h-mn-l-ax, .jb-tqr-rlctr-f-a-ldmr-a").data("lk",0);
                            
                        break;
                    case "rlc-sprt-opn" :
                            
                        break;
                    default : 
                        return;
                }


            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };


        this.open = function (ags) {
            try {
                if ( !( ( iRdy ) || !KgbLib_CheckNullity(ags) ) ) {
                    return;
                }
                
                /*
                 * ETAPE
                 *      On désactive le système OVERFLOW de WINDOW
                 */
                Kxlib_WindOverflow(true);
                
                cargs = ags;
                _f_Init();
                
                /*
                 * ETAPE :
                 *      On affiche la zone
                 */
                $sprt.removeClass("this_hide");
                
                _f_Rel_LdMr();
                        
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_SwMn = function (x,a) {
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                /*
                 * ETAPE :
                 *      On vérifie que le bouton n'est pas LOCK
                 * NOTE :
                 *      -> Attention, on ne LOCK pas le bouton. Il faut laisser cette gestion à @see _f_Rel_LdMr
                 */
                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                
                /*
                 * ETAPE :
                 *      Si nous sommes déjà sur le MENU recherch, on arrete tout !
                 *      NOTE : NON ! On peut vouloir le faire pour mettre à jour les données.
                 */
                
                /*
                 * ETAPE :
                 *      On change visuellement de MENU
                 */
                _f_Vw_SwMn(x);
                
                /*
                 * ETAPE :
                 *      On lance le chargement des DATAS
                 */
                _f_Rel_LdMr(x,a);
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_SprtUpdDs = function (ds) {
            try {
                if ( KgbLib_CheckNullity(ds) ) {
                    return;
                }
                
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_PgUpdDs = function (ds) {
            try {
                if ( KgbLib_CheckNullity(ds) ) {
                    return;
                }
                
                if ( $(".jb-u-sp-flwr-nb").length && ds.flrnb ) {
                    $(".jb-u-sp-flwr-nb").text(ds.flrnb);
                }
                
                if ( $(".jb-u-sp-flg-nb").length && ds.flgnb ) {
                    $(".jb-u-sp-flg-nb").text(ds.flgnb);
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_Rel_LdMr = function (x,a) {
            try {
                
                /*
                 * ETAPE :
                 *      On effectue les vérifications de fiabilité préalables
                 */
                var dr, pi, pt;
                if ( !KgbLib_CheckNullity(x) && $(x).data("lk") === 1 ) {
                    return;
                } else if ( $(".jb-tlkb-uqv-art-m-m-m-lklst").data("lk") === 1 ) {
                    return;
                } else if ( !KgbLib_CheckNullity(x) && !KgbLib_CheckNullity(a) && a === "rlc-load-more" ) {
                    dr = "BTM";
                    
                    pi = ( $(".jb-tqr-rlc-b-a-l-amx:first").data("item") ) ? $(".jb-tqr-rlc-b-a-l-amx:first").data("item") : "";
                    pt = ( $(".jb-tqr-rlc-b-a-l-amx:first").data("time") ) ? $(".jb-tqr-rlc-b-a-l-amx:first").data("time") : "";
                    
//                    $(x).data("lk",1); //[DEPUIS 13-06-16]
                } else if ( !KgbLib_CheckNullity(x) && !KgbLib_CheckNullity(a) && a === "rlc-sw-menu" ) {
                    dr = "FST";
                    pi = "";
                    pt = "";
                } else if ( KgbLib_CheckNullity(x) ) {
                    dr = "FST";
                    pi = "";
                    pt = "";
                    var x = cargs.trigger;
                }
                
                /*
                 * ETAPE :
                 *      On bloquer les bouton de changement de MENU et de LOAD_MORE
                 */
                $(".jb-tqr-rlctr-h-mn-l-wpr, .jb-tqr-rlctr-f-a-ldmr-a").data("lk",1);
                
                
                /*
                 * ETAPE :
                 *      On véréfie su une opération SERVER est déjà en cours.
                 *      Si c'est le cas, on l'annule pour en lancer une nouvelle
                 */
                if ( !KgbLib_CheckNullity(_xhr_plrl) ) {
                    _xhr_plrl.abort();
                } 
                
                /*
                 * ETAPE :  
                 *      On vérifie qu'il y a bel et bien un MENU d'actif. 
                 *      Dans le cas contraire, on en désigne un
                 */
                var $acmn = _f_GetActMn();
                if ( KgbLib_CheckNullity($acmn) ) {
                    $(".jb-tqr-rlctr-h-mn-l-ax[data-sec='_SEC_FOLW']").addClass("active");
                    $acmn = $(".jb-tqr-rlctr-h-mn-l-ax[data-sec='_SEC_FOLW']");
                }
                
                
                if ( dr === "FST" ) {
                   /*
                    * ETAPE :
                    *       On efface les modèles déjà affichés.
                    */
                    $sprt.find(".jb-tqr-rlc-b-a-l-amx").remove();
                    
                    /*
                     * ETAPE :
                     *      On masque NONE
                     */
                    _f_none();
                    
                    /*
                     * ETAPE :
                     *      On affiche la WAIT
                     */
                    _f_WtPnl(true);
                
                    /*
                     * ETAPE :
                     *      On masque le LOAD_MORE ET on affiche le spinner
                     */
                    _f_swldmr()
                } else {
                    /*
                     * ETAPE :
                     *      On masque le LOAD_MORE ET on affiche le spinner
                     */
                    _f_swldmr()
                }
               
                /*
                 * "sc"     : La section ("_SEC_FOLW","_SEC_FOLG","_SEC_RCPFOLW")
                 * "fl"     : [FACULTATIF]Le filtre utilisé 
                 * "dr"     : La direction (FST, BTM, TOP)
                 * "pi"     : [FACULTATIF]L'ID externe de l'élement qui sert de PIVOT 
                 * "pt"     : [FACULTATIF] Le TIME de l'élement qui sert de PIVOT
                 * "xt"     : L'identifiant de l'opération qui permet de fiabiliser l'affichage des commentaires en prenant en compte le temps de réponse
                 * */
                var prm = {
                    sc : $acmn.data("sec"),
                    fl : null,
                    dr : dr,
                    pi : pi,
                    pt : pt,
                };
                
//                if ( dr === "BTM" ) {
//                    Kxlib_DebugVars([JSON.stringify(prm)],true);
//                    return;
//                }
                
                var xt = (new Date()).getTime();
                var s = $("<span/>");
                
//                Kxlib_DebugVars(["TBUV_TIME_REF : LANCÉ !"]);
                _f_Srv_PlRl(prm.sc,prm.fl,prm.dr,prm.pi,prm.pt,xt,x,s);
                
                $(s).on("datasready",function(e,d){
                    if ( KgbLib_CheckNullity(d) ) {
                        return;
                    }
                    
//                    Kxlib_DebugVars(["RLC_TIME_REF : ",parseFloat(xt),parseFloat(d.xt)]);
                    if ( parseFloat(xt) !== parseFloat(d.xt) ) {
                        $sprt.find(".jb-tqr-rlc-b-a-l-amx").remove();
                        return;
                    }
                    
//                    Kxlib_DebugVars([JSON.stringify(d)],true);
//                    return;
                    
                    /*
                     * ETAPE :
                     *      On affiche les éléments
                     */
                    _f_AddToLst(d.list);
                    
                    /*
                     * ETAPE :
                     *      On masque le spinner
                     */
                    _f_swldmr(true);
                    
                    /*
                     * ETAPE :
                     *      On affiche le LOAD_MORE en fonction du nombre d'éléments restants.
                     *      On effectue cette opération seulement dans les cas de dr > FST, BTM
                     */
                    if ( $.inArray(dr,["FST","BTM"]) !== -1  ) {
                        var acnb, rest;
                        switch (prm.sc) {
                            case "_SEC_FOLW" :
                                    acnb = d.flrnb;
                                    rest = acnb - $(".jb-tqr-rlc-b-a-l-amx").length;
                                break;
                            case "_SEC_FOLG" :
                                    acnb = d.flgnb;
                                    rest = acnb - $(".jb-tqr-rlc-b-a-l-amx").length;
                                break;
                            case "_SEC_RCPFOLW" :
                                    acnb = d.flrnb + d.flgnb;
                                    rest = acnb - $(".jb-tqr-rlc-b-a-l-amx").length;
                                break;
                        }
                        if ( rest > 0 ) {
                            _f_ldmore(true);
                        } else {
                            _f_ldmore();
                        }
                    }
                    
                    /*
                     * [DEPUIS 13-06-16]
                     *      On met à jour les données :
                     *             -> De la PAGE
                     *             -> Du module ?
                     */
                    if ( d.cuio ) {
                        _f_PgUpdDs(d);
                    }
//                    _f_SprtUpdDs(d);
                    
                    
                    _xhr_plrl = null;
//                    $(x).data("lk",0);
                    $(".jb-tqr-rlctr-h-mn-l-wpr, .jb-tqr-rlctr-f-a-ldmr-a").data("lk",0);
                });
                
                
                $(s).on("operended",function(e,d){
                    if ( KgbLib_CheckNullity(d) ) {
                        return;
                    }
                    
                    if ( $.inArray(prm.dr,["FST","BTM"]) !== -1 ) {
                        _f_WtPnl();
                    }
                    
                    /*
                     * ETAPE :
                     *      On masque le spinner
                     */
                    _f_swldmr(true);
                    _f_ldmore();
                    
                    /*
                     * ETAPE :
                     *      On affiche NONE
                     */
                    _f_none(true);
                    
                    /*
                     * [DEPUIS 13-06-16]
                     *      On met à jour les données :
                     *          -> De la PAGE
                     *          -> Du module ?
                     */
                    if ( d.cuio ) {
                        _f_PgUpdDs(d);
                    }
                    
                    _xhr_plrl = null;
                    $(".jb-tqr-rlctr-h-mn-l-wpr, .jb-tqr-rlctr-f-a-ldmr-a").data("lk",0);
                });
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_AddToLst = function (ds,dr) {
            try {
                if ( KgbLib_CheckNullity(ds) ) {
                    return;
                }
                
                _f_WtPnl();
                _f_none();
                
                $(".jb-tqr-rlc-b-a-l-amx").removeClass("this_hide");
                
                ds = ( dr && dr === "BTM" ) ? ds : ds.reverse();
                $.each(ds,function(i,d){
                    if ( $(".jb-tqr-rlctr-b-a-lst-mx").find(".jb-tqr-rlc-b-a-l-amx[data-item='"+d.i+"']").length ) {
                        return true;
                    }
                    
                    var mb = _f_PprMdl(d);
                    mb = _f_RbdMdl(mb);
                    
                    if ( dr === "BTM" ) {
                        $(mb).hide().prependTo(".jb-tqr-rlctr-b-a-lst-mx").fadeIn();
                    } else {
                        $(mb).hide().appendTo(".jb-tqr-rlctr-b-a-lst-mx").fadeIn();
                    }
                });
                
                var tmout = 200;
                var ln = Kxlib_ObjectChild_Count(ds);
                if ( ln > 5 && ln <= 20 ) {
                    tmout = 1000;
                } else if ( Kxlib_ObjectChild_Count(ds) > 20 ) {
                    tmout = 1500;
                }
                
                setTimeout(function(){
                    if ( dr !== "TOP" && $(".jb-tqr-rlc-b-a-l-amx").length ) {
                        $(".jb-tqr-rlctr-b-a-lst-mx").masonry({
                            itemSelector    : '.jb-tqr-rlc-b-a-l-amx',
                            columnWidth     : 260,
                            isFitWidth      : true,
                            "gutter"        : 20
                        });
                    } else if ( $(".jb-tqr-rlc-b-a-l-amx").length ) {
                        $(".jb-tqr-rlctr-b-a-lst-mx").masonry("reloadItems");
                        $(".jb-tqr-rlctr-b-a-lst-mx").masonry("layout");
                    }
                    
                },tmout);
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_PprMdl = function (d) {
            try {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                /*
                 * ETAPE :
                 *      On construit le modèle
                 */
                
                var m = "<article class=\'tqr-rlc-b-a-l-amx jb-tqr-rlc-b-a-l-amx \' data-item=\'\' data-time=\'\' data-rltype=\'\' >";
                m += "<div class=\'tqr-rlc-b-a-l-amx-hdr-mx\'>";
                m += "</div>";
                m += "<div class=\'tqr-rlc-b-a-l-amx-bdy-mx\'>";
                m += "<div class=\'tqr-rlc-b-a-l-amx-b-ubox\'>";
                m += "<div>";
                m += "<a class=\'tqr-rlc-b-a-l-amx-ubx-wpr1 jb-tqr-rlc-b-a-l-amx-ubx-wpr1\' href=\'\'>";
                m += "<span class=\'tqr-rlc-b-a-l-amx-u-i-mx\'>";
                m += "<span class=\'tqr-rlc-b-a-l-amx-u-i-fd\'></span>";
                m += "<img class=\'tqr-rlc-b-a-l-amx-u-i jb-tqr-rlc-b-a-l-amx-u-i\' width='50' src=\'\' />";
                m += "</span>";
                m += "<span class=\'tqr-rlc-b-a-l-amx-u-ps jb-tqr-rlc-b-a-l-amx-u-ps\'></span>";
                m += "</a>";
                m += "</div>";
                m += "<div>";
                m += "<a class=\'tqr-rlc-b-a-l-amx-u-fn jb-tqr-rlc-b-a-l-amx-u-fn\' href=\'\'></a>";
                m += "</div>";
                m += "</div>";
                m += "<div class=\'tqr-rlc-b-a-l-amx-b-bio jb-tqr-rlc-b-a-l-amx-b-bio\'></div>";
                m += "</div>";
                m += "<div class=\'tqr-rlc-b-a-l-amx-ftr-mx\'>";
                m += "</div>";
                m += "</article>";
                m = $.parseHTML(m);
                
                /*
                 * ETAPE :
                 *      Insertion des données d'entete
                 */
                $(m)
                    .data({
                        item : d.i,
                        time : d.tm,
                    })
                    .attr({
                        "data-item" : d.i,
                        "data-time" : d.tm,
                    });
                
                
                /*
                 * ETAPE :
                 *      Insertion des données de corps
                 */
                $(m).find(".jb-tqr-rlc-b-a-l-amx-ubx-wpr1").attr("href","/".concat(d.ps));
                //PP
                $(m).find(".jb-tqr-rlc-b-a-l-amx-u-i").prop("src",d.pp);
                //PS
                $(m).find(".jb-tqr-rlc-b-a-l-amx-u-ps").text("@".concat(d.ps));
                //FN
                $(m).find(".jb-tqr-rlc-b-a-l-amx-u-fn").text(d.fn).attr("href","/".concat(d.ps));
                
                //TIME
//                $(m).find(".jb-tlkb-uqv-rct-m-tm").data("tgs-crd",d.pdrtab.date).attr("data-tgs-crd",d.tm);
                
                var pb = $("<div/>").html(d.pb).text();
                
                //TEXT
                var txt = Kxlib_TextEmpow(pb,null,null,null,{
                    emoji : {
                        "size"          : 36,
                        "size_css"      : 20,
                        "position_y"    : 3
                    }
                });
                $(m).find(".jb-tqr-rlc-b-a-l-amx-b-bio").append(txt);
                
                return m;
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_RbdMdl = function (mb) {
            try {
                if ( KgbLib_CheckNullity(mb) ) {
                    return;
                }
                
//                $(mb).find("").off("click").click(function(e){ });
                
                return mb;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_ErrsHdlr = function (ec) {
            try {
                if ( KgbLib_CheckNullity(ec) ) {
                    return; 
                }
                
                switch (ec) {
                    case "__ERR_VOL_UREF_GONE" :
                            _f_ErHd_URefGn();
                        break;
                    default:
                        return;
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        }
        
        
        var _f_ErHd_URefGn = function () {
            try {
                
                /*
                 * TODO : 
                 *      Afficher un message de courtoisie
                 */
                
                /*
                 * TAOE :
                 *      On efface l'élément SOURCE
                 */
                
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        }
        
        
        
        var _f_GetActMn = function (rsc) {
            //rsc : ReturnSeCtion
            try {
                
                var acmn = $(".jb-tqr-rlctr-h-mn-l-ax.active");
                if ( acmn && $(acmn).length ) {
                    return ( rsc ) ? $(acmn).data("sec") : acmn;
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        }
        
        
        /*************************************************************************************************************************************************************************/
        /**************************************************************************** ACCESSORS SCOPE ****************************************************************************/
        /*************************************************************************************************************************************************************************/
        
        /*******************************************************************************************************************************************************************/
        /***************************************************************************** SERVER SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        var _Ax_PlRl = Kxlib_GetAjaxRules("TQR_RLC_PLAC");
        var _f_Srv_PlRl = function(sc,fl,dr,pi,pt,xt,x,s) {
            if ( KgbLib_CheckNullity(sc) | KgbLib_CheckNullity(dr) | KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
                return;
            }
        
            var onsuccess = function (datas) {
            //            alert("CHAINE JSON AVANT PARSE"+datas);
                try {
                    if (! KgbLib_CheckNullity(datas) ) {
                        datas = JSON.parse(datas);
                    } else {
                        if (! KgbLib_CheckNullity(x) ) {
                            $(x).data("lk",0);
                        }
                        _xhr_plrl = null;
                        return;
                    }
                
                if (! KgbLib_CheckNullity(datas.err) ) {
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    _xhr_plrl = null;
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
                                break;
                            case "__ERR_VOL_UREF_GONE" :
                                    _f_ErrsHdlr(datas.err);
                                break;
                            default :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) && !KgbLib_CheckNullity(datas.return.list) ) {
                    var ds = [datas.return];
                    $(s).trigger("datasready",ds);  
                } else if ( !KgbLib_CheckNullity(datas.return) && datas.return.hasOwnProperty("flrnb") && datas.return.hasOwnProperty("flgnb") ) {
                    var ds = [datas.return];
                    $(s).trigger("operended",ds);
                    return;
                } else {
                    return;
                }
                
                } catch (ex) {
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    _xhr_plrl = null;

                    //TODO : Renvoyer l'erreur au serveur
                    Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
//                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
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
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            };
        
            var curl = document.URL;
            var toSend = {
                "urqid": _Ax_PlRl.urqid,
                "datas": {
                    "sc"    : sc,
                    "fl"    : fl,
                    "dr"    : dr,
                    "pi"    : pi,
                    "pt"    : pt,
                    "xt"    : xt,
                    "cu"    : curl
                }
            };

            _xhr_plrl = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlRl.url, wcrdtl : _Ax_PlRl.wcrdtl });
        };
        
        
        
        var _Ax_XaTsty = Kxlib_GetAjaxRules("TQR_TSTY_XTAC");
        var _f_Srv_XaTsty  = function(i,a,xt,x,s) {
            if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(a) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(s) ) {
                return;
            }

            var onsuccess = function (datas) {
                //            alert("CHAINE JSON AVANT PARSE"+datas);
                try {
                    if (! KgbLib_CheckNullity(datas) ) {
                        datas = JSON.parse(datas);
                    } else {
                        _xhr_tsty_ax = null;
                        $(x).data("lk",0);
                        return;
                    }

                    if(! KgbLib_CheckNullity(datas.err) ) {
                        _xhr_tsty_ax = null;
                        $(x).data("lk",0);
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
                                case "__ERR_VOL_TST_GONE" :
                                        //TODO : Supprimer de la liste OU demander à recharger
                                    break;
                                case "__ERR_VOL_DNY_AKX" :
                                        //TODO : ... ?
                                    break;
                                case "__ERR_VOL_AMBIGUOUS" :
                                        //TODO : ... ?
                                    break;
                                case "__ERR_VOL_TSM_GONE" :
                                case "__ERR_VOL_TESTY_GONE" :
                                case "__ERR_VOL_TST_GONE" :
                                        _f_ErrsHdlr(datas.err);
                                    break;
                                default :
                                        Kxlib_AJAX_HandleFailed();
                                    break;
                            }
                        } 
                        return;
                    } else if ( !KgbLib_CheckNullity(datas.return) ) {
                        var ds = [datas.return];
                        $(s).trigger("operended",ds);
                    } else {
                        return;
                    }

                } catch (ex) {
                    //TODO : Renvoyer l'erreur au serveur
                    //                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
    //                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
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
                "urqid": _Ax_XaTsty.urqid,
                "datas": {
                    "i"     : i,
                    "a"     : a,
                    "xt"    : xt,
                    "cu"    : curl
                }
            };

            _xhr_tsty_ax = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_XaTsty.url, wcrdtl : _Ax_XaTsty.wcrdtl });
        };
        
        
        /*******************************************************************************************************************************************************************/
        /****************************************************************************** VIEW SCOPE *************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        var _f_none = function (shw) {
            try {
                
                var sc = _f_GetActMn(true), cm, nne_m;
                
                switch (sc) {
                    case "_SEC_FOLW" :
                            cm = "rlc_none_flr";
                        break
                    case "_SEC_FOLG" :
                            cm = "rlc_none_flg";
                        break
                    case "_SEC_RCPFOLW" :
                            cm = "rlc_none_dfl";
                        break
                    default :
                        return;
                }
                nne_m = Kxlib_getDolphinsValue(cm);
                if ( KgbLib_CheckNullity(nne_m) ) {
                    return;
                }
                
                  
                if (shw) {
                    //ETAPE : On masque la zone où est lister les ARTICLES
                    $(".jb-tqr-rlc-b-a-l-amx").addClass("this_hide");
                    
                    //ETAPE : On ajoute le message en fonction de la SECTION
                    $(".jb-tqr-rlctr-b-nne-mx").html(nne_m)
                    
                    //ETAPE : On affiche NONE
                    $(".jb-tqr-rlctr-b-nne-mx").removeClass("this_hide");
                } else {
                    $(".jb-tqr-rlctr-b-nne-mx").addClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_ldmore = function (shw) {
            try {
                if ( shw ) {
                    $(".jb-tqr-rlc-b-a-l-amx").addClass("this_hide");
                    $(".jb-tqr-rlctr-f-a-ldmr-a").removeClass("this_hide");
                } else {
                    $(".jb-tqr-rlctr-f-a-ldmr-a").addClass("this_hide");
                }
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_swldmr = function (shlmr) {
            try {
                
                /*
                 * ETAPE :
                 *      Dans tous les cas on affiche le BTN LOAD_MORE
                 */
                $(".jb-tqr-rlctr-f-a-ldmr-a").removeClass("this_hide");
                
                if ( shlmr ) {
                    $(".jb-tqr-rlctr-f-a-ldmr-a").find("._this_spnr").addClass("this_hide");
                    $(".jb-tqr-rlctr-f-a-ldmr-a").find("._this_tgr").removeClass("this_hide");
                } else {
                    $(".jb-tqr-rlctr-f-a-ldmr-a").find("._this_tgr").addClass("this_hide");
                    $(".jb-tqr-rlctr-f-a-ldmr-a").find("._this_spnr").removeClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_WtPnl = function (shw) {
            try {
                
                if ( shw ) {
                    $(".jb-tqr-rlc-b-a-l-amx").addClass("this_hide");
                    $(".jb-tqr-rlctr-b-wtpnl").removeClass("this_hide");
                } else {
                    $(".jb-tqr-rlctr-b-wtpnl").addClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Vw_SwMn = function (x) {
            try {
                if ( KgbLib_CheckNullity(x) ) {
                    return;
                }
                
                var $acmn = _f_GetActMn();
                if ( $acmn.length ) {
                    /*
                     * ETAPE :
                     *      On retire sur tous les MENUS (au cas où)
                     */
                    $(".jb-tqr-rlctr-h-mn-l-ax.active").removeClass("active");
                }
                
                var sc = $acmn.data("sec");
                switch (sc) {
                    case "_SEC_FOLW" :
                    case "_SEC_FOLG" :
                    case "_SEC_RCPFOLW" :
                            $(x).addClass("active");
                        break;
                    default :
                        return;
                }
                
                return true;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        
        /*******************************************************************************************************************************************************************/
        /************************************************************************** LISTENERS SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        $(".jb-tqr-rlctr-clzbx, .jb-tqr-rlctr-h-mn-l-ax").off("click").click(function(e){
            Kxlib_PreventDefault(e);
            
            _f_Action(this);
        });
        
        
        /*******************************************************************************************************************************************************************/
        /************************************************************************ CONSTRUCTOR SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        $(function() {
            _f_Init();
        });
    };
});