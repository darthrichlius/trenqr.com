define("r/csam/tkbvwr.csam",function(){
    //cfd : ConstructorFeed
    return function TbkVwr(cfd) {
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
         *  md_slc      : MoDelSeLeCtion
         *  md_props    : ModelProperties
         */
        var iRdy, md_slc, md_props;
        /*
         * 
         */
        var trgList = [".jb-tqr-tsty-art-opt[data-action='post-react-open']"];
        var cfdKeys = ["trigger","action"];
        
        var $sprt = $(".jb-tlkb-unqvw-sprt-bmx");
        
        var _xhr_plrs, _xhr_plls, _xhr_adrc, _xhr_dlrc, _xhr_tsty_ax, _xhr_tsty_del;
        
        /*******************************************************************************************************************************************************************/
        /**************************************************************************** PROCESS SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        
        var _f_Gdf = function () {
            var dt = {
                "adr_maxln" : 1000
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
                    
                    /*
                     * ETAPE :
                     *      On récupère une référence de l'élément DOM représentant le modèle.
                     *      Si elle n'existe pas, on déclenche une erreur.
                     * [DEPUIS 14-04-16]
                     *      On gère le cas de MODEL multiples
                     */
                    var mdl;
                    if ( cargs.hasOwnProperty("model") && cargs.model ) {
                        switch (cargs.model) {
                            case "TLKBRD" :
                                    md_slc = $(cargs.trigger).closest(".jb-tbv-bind-art-mdl");
                                break;
                            case "AJCA-PSMN" :
                                    md_slc = ( $(cargs.trigger).is(".jb-pm-mdl-mx") ) ? 
                                    $(cargs.trigger) : $(cargs.trigger).closest(".jb-pm-mdl-mx");
                                break;
                            case "AJCA-TIA-EXPLR" :
                                    md_slc = $(cargs.trigger).closest(".jb-tqr-dscvr-ex-tkm-bmx");
                                break;
                            case "AJCA-HVIEW" :
                                    md_slc = $(cargs.trigger).closest(".jb-tqr-hview-art-bmx");
                                break;
                            case "AJCA-FKSA" :
                                    md_slc = $(cargs.trigger).closest(".jb-tqr-fksa-tst-art-bmx");
                                break;
                            default:
                                return;
                        }
                        mdl = cargs.model;
                    } else {
                        md_slc = $(cargs.trigger).closest(".jb-tbv-bind-art-mdl");
                        mdl = "TLKBRD";
                        cargs["model"] = "TLKBRD";
                    }
                    
                    if (! md_slc.length ) {
                        throw "Unable to get access to the model definition.";
                    }
                    
                    /*
                     * ETAPE :
                     *      On récupère les propriétés de base du modèle
                     * [DEPUIS 14-04-16]
                     *      Modification pour prendre en compte les cas de MULTIPLE ORIGIN
                     */
                    if ( mdl === "AJCA-PSMN" ) {
                        var ajca_s = md_slc.data("subj-ajcache"), ajca_o = JSON.parse(ajca_s);
                        md_props = {
                            i : ajca_o.i,
                            t : ajca_o.tm
                        };
                    } else if ( mdl === "AJCA-TIA-EXPLR" ) {
                        var ajca_s = md_slc.data("ajcache"), ajca_o = JSON.parse(ajca_s);
                        md_props = {
                            i : ajca_o.i,
                            t : ajca_o.tm
                        };
                    } else if ( mdl === "AJCA-HVIEW" || "AJCA-FKSA" ) {
                        var ajca_s = ""+md_slc.data("ajcache")+"";
                        ajca_o = ( typeof md_slc.data("ajcache") === "object" ) ? md_slc.data("ajcache") : JSON.parse(ajca_s); 
                        md_props = {
                            i : ajca_o.i,
                            t : ajca_o.tm
                        };
                    } else {
                        md_props = {
                            i : md_slc.data('item'),
                            t : md_slc.data('time')
                        };
                    }
                    
//                    Kxlib_DebugVars([JSON.stringify(md_props)],true);
                   
                   iRdy = true;
                   
                   if ( cargs.hasOwnProperty("autoload") && cargs.autoload ) {
                       open();
                   } else {
                       return md_props;
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
                    /*
                     * [DEPUIS 22-06-16]
                     */
                    case "tlkb-sprt-clz" :
                            _f_Uqv_Io(x,_a);
                        break;
                    case "post-like" :
                    case "post-unlike" :
                            _f_LikeAct(x,_a);
                        break;
                    case "post-del-start" :
                    case "post-del-fnl-y" :
                    case "post-del-fnl-n" :
                            _f_DelAct(x,_a);
                        break;
                    case "tbuv-react-add" :
                            _f_AddRct(x);
                        break;
                    case "tbuv-react-del" :
                    case "tbuv-react-del-y" :
                    case "tbuv-react-del-n" :
                            _f_DelRct(x,_a);
                        break;
                    case "tbuv-react-ld-mr" :
                    case "post-pl-reacts" :
                            _f_React_LdMr(x,_a);
                        break;
                    case "tbuv-like-ld-mr" :
                    case "post-pl-likes" :
                            _f_Like_LdMr(x,_a);
                        break;
                    case "tbuv-react-answ" :
                            _f_AnswRct(x);
                        break;
                    default :
                        return;
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Uqv_Io = function (x,a) {
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
                    case "tlkb-sprt-clz" :
                            $(".jb-tlkb-unqvw-sprt-bmx").addClass("this_hide");
                            $("html").removeClass("ovf-sleep");
                            
                            /*
                             * [DEPUIS 22-06-16]
                             * ETAPE :
                             *      On retire tous les éléments dans la file
                             */
                            $(".jb-tlkb-uqv-r-mdl-mx").remove();
                            
                            /*
                             * [DEPUIS 22-06-16]
                             * ETAPE :
                             *      On masque le spinner
                             */
                            _f_spnr();
                            
                            /*
                             * [DEPUIS 22-06-16]
                             * ETAPE :
                             *      Libérer et annuler le ou ls pointeurs
                             */
                            if (! KgbLib_CheckNullity(_xhr_plrs) ) {
                                _xhr_plrs.abort();
                                _xhr_plrs = null;
                            }
                            
                            /*
                             * [DEPUIS 22-06-16]
                             * ETAPE :
                             *      Libérer les boutons qui sont LOCK
                             */
                            $(".jb-tlkb-uqv-art-m-m-m-rnb, .jb-tlkb-uqv-art-m-m-m-lklst").data("lk",0);
                           

                            /*
                             * [DEPUIS 13-06-16]
                             *      On réactive le système OVERFLOW de WINDOW
                             */
                            Kxlib_WindOverflow(false);

                            /*
                             * [NOTE 24-04-16]
                             *      Dans le cas de WLC, on dénature DOM pour disable certaines fonctionnalités.
                             *      Cependant, il faut ensuite les raffraichir pour une prochaine utilisation
                             */
                            $("#tlkb-uqv-art-m-m-mbx-elv-a").addClass("jb-tlkb-uqv-art-m-m-m-e-a");

                            /*
                             * [DEPUIS 31-05-16]
                             *      On reinitialise la valeur de l'URL.
                             *      Autrement dit, nous revenons à l'URL de la page SUPPORT
                             */
                            var curl_path = $(".jb-tlkb-unqvw-sprt-bmx").data("curl");
                            var stateObj = {}; 
                            window.history.replaceState(stateObj, "", curl_path);

                        break;
                    case "tlkb-sprt-opn" :
                            $("html").addClass("ovf-sleep");
                            $(".jb-tlkb-unqvw-sprt-bmx").removeClass("this_hide");
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
                if ( !( ( iRdy && md_props ) || !KgbLib_CheckNullity(ags) ) ) {
                    return;
                }
                
//                Kxlib_DebugVars([( iRdy && md_props ), !KgbLib_CheckNullity(ags),iRdy,md_props],true);
                
                /*if ( !( iRdy && md_props ) ) { 
                    cargs = ags;
                    _f_Init();
                }
                //*/
                
                
                /*
                 * [DEPUIS 13-06-16]
                 *      On désactive le système OVERFLOW de WINDOW
                 */
                Kxlib_WindOverflow(true);
                
                cargs = ags;
                _f_Init();
                
                /*
                 * ETAPE :
                 *      On affiche la zone
                 */
                _f_AppendDatas(ags.model);
                
                /*
                 * ETAPE :
                 *      On affiche la zone
                 */
                $sprt.removeClass("this_hide");
                
                _f_React_LdMr();
                        
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_AppendDatas = function (mty) {
            try {
                if ( KgbLib_CheckNullity(mty) ) {
                    return;
                }
                
                /*
                 * [DEPUIS 14-04-16]
                 *      Le module gère les différents MODEL possibles.
                 */
                mty = mty.toString().toUpperCase();
                switch (mty) {
                    case "TLKBRD" :
                            _f_ApdDsStd();
                        break;
                    case "AJCA-PSMN" :
                            md_slc = ( $(cargs.trigger).is(".jb-pm-mdl-mx") ) ? 
                            $(cargs.trigger) : $(cargs.trigger).closest(".jb-pm-mdl-mx");
                            
                            _f_ApdDsAJCA(mty);
                        break;
                    case "AJCA-TIA-EXPLR" :
                            md_slc = $(cargs.trigger).closest(".jb-tqr-dscvr-ex-tkm-bmx");
                            
                            _f_ApdDsAJCA(mty);
                        break
                    case "AJCA-HVIEW" :
                            md_slc = $(cargs.trigger).closest(".jb-tqr-hview-art-bmx");
                            
                            _f_ApdDsAJCA(mty);
                        break
                    case "AJCA-FKSA" :
                            md_slc = $(cargs.trigger).closest(".jb-tqr-fksa-tst-art-bmx");
                            
                            _f_ApdDsAJCA(mty);
                        break;
                    default:
                        return;
                }
                
                return true;
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_ApdDsStd = function () {
            try {
                var autab = JSON.parse(md_slc.data("author"));
                var tgtab = JSON.parse(md_slc.data("target"));
                var mtxt = md_slc.data("text");
                
                /*
                 * ETAPE : 
                 *      Insertion des données
                 */
                /* --------------------- HEADERS --------------------- */
                $sprt.data("item",md_slc.data("item")).data("time",md_slc.data("time"));
                
                /* --------------------- TIME DATAS --------------------- */
                $sprt.find(".jb-tlkb-uqv-art-m-m-mbx-tm").data("tgs-crd",md_props.t);        
                
                /* --------------------- AUTHOR DATAS --------------------- */
//                Kxlib_DebugVars([typeof autab, _.keys(autab),autab.ps],true);
                $sprt.find(".jb-tlkb-uqv-art-m-m-ubx-i-a").prop("href","/".concat(autab.ps));
                $sprt.find(".jb-tlkb-uqv-art-m-m-ubx-im").prop("src",autab.pp);
                $sprt.find(".jb-tlkb-uqv-art-m-m-ubx-ps").prop("href","/".concat(autab.ps)).text("@".concat(autab.ps));
                $sprt.find(".jb-tlkb-uqv-art-m-m-ubx-nc").text(autab.fn);
                /* --------------------- TARGET DATAS --------------------- */
                $sprt.find(".jb-tlkb-uqv-a-m-m-m-tu").prop("href","/".concat(tgtab.ps)).prop("title",tgtab.fn).text("@".concat(tgtab.ps));
                
                /* --------------------- TEXT DATAS --------------------- */
                var ajca_s = md_slc.data("ajcache"), ajca_o = JSON.parse(ajca_s);
                
                /*
                 * ETAPE :
                 *      On change l'URL en affichant le PERMALINK du TSM
                 * NOTE : 
                 *      Cette opération est importante car c'est le seul moyen qui pourrait permettre à l'utilisateur de récupérer le PERMALINK.
                 *      Grace à cette donnée il peut diffuser son contenu au delà des frontières de TRENQR
                 */
                if (! KgbLib_CheckNullity(ajca_o.prmlk) ) {
                    var curl = document.URL;
                    var curl_path = Kxlib_GetAbsolutePathFromUrl(curl);
                    $(".jb-tlkb-unqvw-sprt-bmx").data("curl",curl_path);
                            
                    var stateObj = {}; 
                    var prmlk_path = Kxlib_GetAbsolutePathFromUrl(ajca_o.prmlk);
                    window.history.replaceState(stateObj, "", prmlk_path);
                }
                
                /*
                 * [DEPUIS 31-05-16]
                 *      On ajoute la donnée sur le nombre de Commentaires
                 */
                $(".jb-tlkb-uqv-art-m-m-m-rnb").text(ajca_o.rnb);
                
                /*
                 * [DEPUIS 02-04-16]
                 */
                var ustgs = ajca_o.ustgs;
                var hashs = ajca_o.hashs;
                
//                Kxlib_DebugVars([ajca_s,ajca_o.hashs,ustgs],true);
                //rtxt = RenderedText
                var rtxt = Kxlib_TextEmpow(mtxt,ustgs,hashs,null,{
                    "ena_inner_link" : {
//                        "local" : true, //DEV, DEBUG, TEST
                        "all"   : false,
                        "only"  : "fksa"
                    },
                    emoji : {
                        "size"          : 36,
                        "size_css"      : 22,
                        "position_y"    : 3
                    }
                });
                        
//                Kxlib_DebugVars([rtxt],true);

                $sprt.find(".jb-tlkb-uqv-art-m-m-mbx-txt").text("").append(rtxt);
//                $sprt.find(".jb-tlkb-uqv-art-m-m-mbx-txt").text(mtxt);
                var hslk = ( ajca_o.hslk ) ? "me" : "";
                $sprt.find(".jb-tlkb-uqv-art-m-m-m-e-a").text(ajca_o.cnlk);
                
//                Kxlib_DebugVars([hslk,ajca_o.cnlk],true);
                if ( hslk === "me" ) {
                    $sprt.find(".jb-tlkb-uqv-art-m-m-m-e-a")
//                        .addClass("like")
                            .data("state",hslk).attr("data-state",hslk)
                                .data("action","post-unlike").data("actrvs","post-like");
                } else {
                    $sprt.find(".jb-tlkb-uqv-art-m-m-m-e-a")
//                        .addClass("like")
                            .data("state","").attr("data-state",ajca_o.hslk)
                                .data("action","post-like").data("actrvs","post-unlike");
                }
                
                /*
                 * ETAPE : 
                 *      Gestion des AUTORISATIONS
                 */
                /* --------------------- SUPPRESSION ARTICLE --------------------- */
                if (! ( ajca_o.hasOwnProperty("cdl") && ajca_o.cdl ) ) {
                    $sprt.find(".jb-tlkb-uqv-a-m-m-m-dl-btn").addClass("this_hide");
                } else {
                    $sprt.find(".jb-tlkb-uqv-a-m-m-m-dl-btn").removeClass("this_hide");
                }
                /* --------------------- AUTORISATION LIKE --------------------- */
                if (! ( ajca_o.hasOwnProperty("clk") && ajca_o.clk ) ) {
                    $sprt.find("#tlkb-uqv-art-m-m-mbx-elv-a").removeClass("jb-tlkb-uqv-art-m-m-m-e-a").addClass("jb-irr");
                } else {
                    $sprt.find("#tlkb-uqv-art-m-m-mbx-elv-a").removeClass("jb-irr").addClass("jb-tlkb-uqv-art-m-m-m-e-a");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_ApdDsAJCA = function (m) {
            try {
                if ( KgbLib_CheckNullity(m) ) {
                    return;
                }
                
                /*
                 * [ETAPE]
                 *      On récupère les données en fonction du modèle en présence
                 */
                var ajca_s, ajca_o, mtxt;
                switch (m) {
                    case "AJCA-PSMN" : 
                            ajca_s = md_slc.data("subj-ajcache"), ajca_o = JSON.parse(ajca_s);
                            mtxt = ajca_o.m;
                           /*
                            * TABLE TESTY
                            *      au : {oid,ofn,opsd,oppic }
                            *      cdl : CanDel
                            *      cgap : CanGetAkxToPin
                            *      clk : CanLIKE
                            *      cnlk : Nombre de LIKE
                            *      hslk : A aimé
                            *      i : Identifiant de TESTY
                            *      isp : IsPin ?
                            *      m : Message
                            *      rnb : Nombre de commentaires
                            *      tg : {oid,ofn,opsd,oppic }
                            *      tm : Time
                            */
                        break;
                    case "AJCA-TIA-EXPLR": 
                            ajca_s = md_slc.data("ajcache"), ajca_o = JSON.parse(ajca_s);
                            mtxt = ajca_o.m;
                        break;
                    case "AJCA-HVIEW": 
                    case "AJCA-FKSA": 
                            var ajca_s = ""+md_slc.data("ajcache")+"";
                            ajca_o = ( typeof md_slc.data("ajcache") === "object" ) ? md_slc.data("ajcache") : JSON.parse(ajca_s); 
                            
                            mtxt = $("<div/>").html(ajca_o.m).text();
                        break;
                    default :
                        return;
                }
                
                var autab = ajca_o.au;
                var tgtab = ajca_o.tg;
                
                /*
                 * ETAPE :
                 *      On change l'URL en affichant le PERMALINK du TSM
                 * NOTE : 
                 *      Cette opération est importante car c'est le seul moyen qui pourrait permettre à l'utilisateur de récupérer le PERMALINK.
                 *      Grace à cette donnée il peut diffuser son contenu au delà des frontières de TRENQR
                 */
                if (! KgbLib_CheckNullity(ajca_o.prmlk) ) {
                    var curl = document.URL;
                    var curl_path = Kxlib_GetAbsolutePathFromUrl(curl);
                    $(".jb-tlkb-unqvw-sprt-bmx").data("curl",curl_path);
                            
                    var stateObj = {}; 
                    var prmlk_path = Kxlib_GetAbsolutePathFromUrl(ajca_o.prmlk);
                    window.history.replaceState(stateObj, "", prmlk_path);
                }
                
                /*
                 * [DEPUIS 31-05-16]
                 *      On ajoute la donnée sur le nombre de Commentaires
                 */
                $(".jb-tlkb-uqv-art-m-m-m-rnb").text(ajca_o.rnb);
                
                /*
                 * ETAPE : 
                 *      Insertion des données
                 */
                /* --------------------- HEADERS --------------------- */
                $sprt.data("item",md_props.i).data("time",md_props.t);
                
                /* --------------------- TIME DATAS --------------------- */
                $sprt.find(".jb-tlkb-uqv-art-m-m-mbx-tm").data("tgs-crd",md_props.t);        
                
                /* --------------------- AUTHOR DATAS --------------------- */
//                Kxlib_DebugVars([typeof autab, _.keys(autab),autab.opsd],true);
                $sprt.find(".jb-tlkb-uqv-art-m-m-ubx-i-a").prop("href","/".concat(autab.opsd));
                $sprt.find(".jb-tlkb-uqv-art-m-m-ubx-im").prop("src",autab.oppic);
                $sprt.find(".jb-tlkb-uqv-art-m-m-ubx-ps").prop("href","/".concat(autab.opsd)).text("@".concat(autab.opsd));
                $sprt.find(".jb-tlkb-uqv-art-m-m-ubx-nc").text(autab.ofn);
                /* --------------------- TARGET DATAS --------------------- */
                $sprt.find(".jb-tlkb-uqv-a-m-m-m-tu").prop("href","/".concat(tgtab.opsd)).prop("title",tgtab.ofn).text("@".concat(tgtab.opsd));
                
                /* --------------------- TEXT DATAS --------------------- */
                
                var ustgs = ajca_o.ustgs;
                var hashs = ajca_o.hashs;
                
//                Kxlib_DebugVars([ajca_s,ajca_o.hashs,ustgs],true);
                //rtxt = RenderedText
                var rtxt = Kxlib_TextEmpow(mtxt,ustgs,hashs,null,{
                    emoji : {
                        "size"          : 36,
                        "size_css"      : 22,
                        "position_y"    : 3
                    }
                });
//                Kxlib_DebugVars([rtxt],true);

                $sprt.find(".jb-tlkb-uqv-art-m-m-mbx-txt").text("").append(rtxt);
//                $sprt.find(".jb-tlkb-uqv-art-m-m-mbx-txt").text(mtxt);
                var hslk = ( ajca_o.hslk ) ? "me" : "";
                $sprt.find(".jb-tlkb-uqv-art-m-m-m-e-a").text(ajca_o.cnlk);
                
//                Kxlib_DebugVars([hslk,ajca_o.cnlk],true);
                if ( hslk === "me" ) {
                    $sprt.find(".jb-tlkb-uqv-art-m-m-m-e-a")
//                        .addClass("like")
                            .data("state",hslk).attr("data-state",hslk)
                                .data("action","post-unlike").data("actrvs","post-like");
                } else {
                    $sprt.find(".jb-tlkb-uqv-art-m-m-m-e-a")
//                        .addClass("like")
                            .data("state","").attr("data-state",ajca_o.hslk)
                                .data("action","post-like").data("actrvs","post-unlike");
                }
                
                
                /*
                 * ETAPE : 
                 *      Gestion des AUTORISATIONS
                 */
                /* --------------------- SUPPRESSION ARTICLE --------------------- */
                if (! ( ajca_o.hasOwnProperty("cdl") && ajca_o.cdl ) ) {
                    $sprt.find(".jb-tlkb-uqv-a-m-m-m-dl-btn").addClass("this_hide");
                } else {
                    $sprt.find(".jb-tlkb-uqv-a-m-m-m-dl-btn").removeClass("this_hide");
                }
                /* --------------------- AUTORISATION LIKE --------------------- */
                if (! ( ajca_o.hasOwnProperty("clk") && ajca_o.clk ) ) {
                    $sprt.find("#tlkb-uqv-art-m-m-mbx-elv-a").removeClass("jb-tlkb-uqv-art-m-m-m-e-a").addClass("jb-irr");
                } else {
                    $sprt.find("#tlkb-uqv-art-m-m-mbx-elv-a").removeClass("jb-irr").addClass("jb-tlkb-uqv-art-m-m-m-e-a");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_LikeAct = function (x,a) {
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }

                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                $(x).data("lk",1);

                /*
                 * tsa  : ToSendAction
                 * mrld : MustReLoaD 
                 */
                var tsa;
                switch (a) {
                    case "post-like" :
                            tsa = "TST_XA_GOLK";
//                            console.log("Lancer l'animation");
                        break;
                    case "post-unlike" :
                            tsa = "TST_XA_GOULK";
                        break;
                    default:
                        return;
                }

                var i = $sprt.data("item");
//                Kxlib_DebugVars([a,i,tsa],true);
//                return;
                if ( KgbLib_CheckNullity(i) ) {
                    return;
                }

                /*
                 * ETAPE :
                 *      On lock tous les boutons d'ACTION
                 */
                $(x).data("lk",1);

                var s = $("<span/>"), xt = (new Date()).getTime();

                _f_Srv_XaTsty(i,tsa,xt,x,s);
            
                $(s).on("operended",function(e,d){
                    if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(d.i) | KgbLib_CheckNullity(d.lc) ) {
                        return;
                    }

                    /*
                     * ETAPE :
                     *      Dans tous les cas on ajoute le nombre de LIKE
                     */
                    //... TKBVWR
                    $(x).text(d.lc);
                    //... MODELE EN PAGE
                    if ( $(md_slc).find(".jb-tqr-tsty-art-opt.like").length ) {
                        $(md_slc).find(".jb-tqr-tsty-art-opt.like").text(d.lc);
                    }
                    
                
                    /*
                     * ETAPE :
                     *      After Work
                     */
                    if ( a === "post-like" ) {
                        /*
                         * ETAPE : 
                         *      On change les ACTION
                         */
                        $(x).attr("data-state","me").data("state","me").data("action",'post-unlike').data("actrvs","post-like");
                        if ( $(x).is(".jb-tlkb-uqv-art-m-m-m-e-a") ) {
                            $(md_slc).find(".jb-tqr-tsty-art-opt.like").attr("data-state","me").data("state","me").data("action",'post-unlike').data("actrvs","post-like");
                        }

                    } else {
                        if ( d.lc ) {
                            $(x).attr("data-state","ano1").data("state","ano1");
                            if ( $(x).is(".jb-tlkb-uqv-art-m-m-m-e-a") ) {
                                $(md_slc).find(".jb-tqr-tsty-art-opt.like").attr("data-state","ano1").data("state","ano1");
                            } else {
                                $(".jb-tlkb-uqv-art-m-m-m-e-a").attr("data-state","ano1").data("state","ano1");
                            }
                        } else {
                            $(x).attr("data-state","").data("state","");
                            if ( $(x).is(".jb-tlkb-uqv-art-m-m-m-e-a") ) {
                                $(md_slc).find(".jb-tqr-tsty-art-opt.like").attr("data-state","").data("state","");
                            }
                        }

                        /*
                         * ETAPE : 
                         *      On change les ACTION au niveau du MODEL en PAGE et du BOUTON qui a déclenché l'ACTION
                         */
                        $(x).data("action",'post-like').data("actrvs","post-unlike");
                        if ( $(x).is(".jb-tlkb-uqv-art-m-m-m-e-a") ) {
                            $(md_slc).find(".jb-tqr-tsty-art-opt.like").data("action",'post-like').data("actrvs","post-unlike");
                        } 
                    } 
                
                    $(x).data("lk",0);
                    _xhr_tsty_ax = null;
                });

            
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_DelAct = function (x,a) {
            try {
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                if ( $(x).data("lk") === 1 ) {
                    return;
                }

                var sprt = $(".jb-tlkb-unqvw-sprt-bmx");
                switch (a) {
                    case "post-del-start" :
                            $(".jb-tlkb-uqv-a-m-m-m-dl-btn").addClass("this_hide");
                            $(".jb-tlkb-uqv-a-m-m-m-dl-cfbx").removeClass("this_hide");
                        return;
                    case "post-del-fnl-n" :
                            $(".jb-tlkb-uqv-a-m-m-m-dl-cfbx").addClass("this_hide");
                            $(".jb-tlkb-uqv-a-m-m-m-dl-btn").removeClass("this_hide");
                        return;
                    case "post-del-fnl-y" :
                            /*
                             * ETAPE :
                             *      On réinitialise la zone pour une prochaine utilisation
                             */
                            $(".jb-tlkb-uqv-a-m-m-m-dl-cfbx").addClass("this_hide");
                            $(".jb-tlkb-uqv-a-m-m-m-dl-btn").removeClass("this_hide");
                        break;
                    default:
                        return;
                }
                
                /*
                 * ETAPE :
                 *      On LOCK tous les boutons relatif à la DEL de TSM 
                 */
                $(".jb-tlkb-uqv-a-m-m-m-dl-btn, .jb-tlkb-uqv-art-m-m-m-d-c-a").data("lk",1);
                
                /*
                 * ETAPE :
                 *      On vérifie si TSM existe dans la PAGE
                 */
                if ( !$(md_slc).length ) {
                    $(".jb-tlkb-uqv-a-m-m-m-dl-btn, .jb-tlkb-uqv-art-m-m-m-d-c-a").data("lk",0);
                    return;
                }
                
//                var i = $(md_slc).data("item"), atp; //[DEPUIS 21-06-16]
                var i, atp;
                
                /*
                 * [DEPUIS 21-06-16]
                 *      Permet de sélection la bonne donnée "atp" en prennat en compte tous les cas.
                 */
                if ( KgbLib_CheckNullity($(md_slc).data("atype")) && !KgbLib_CheckNullity($(md_slc).data("thm")) && $.inArray($(md_slc).data("thm"),["XTSM","XTSR","XTSL","XTST_USTG"]) !== -1 ) {
                    atp = "psmn";
                } else {
                    atp = $(md_slc).data("atype");
                }
                
                
                /*
                 * [DEPUIS 21-06-16]
                 *      Permet une meilleure sélection de l'ID en prennat en compte tous les cas.
                 */
                if ( !KgbLib_CheckNullity($(md_slc).data("thm")) && $.inArray($(md_slc).data("thm"),["XTSM","XTSR","XTSL","XTST_USTG"]) !== -1 && !KgbLib_CheckNullity($(md_slc).data("ai")) ) {
                    //CAS : PSMN
                    i = $(md_slc).data("ai");
                } else if ( !KgbLib_CheckNullity($(md_slc).data("cnid")) ) {
                    //CAS : HVIEW
                    i = $(md_slc).data("cnid");
                } else {
                    //CAS : AUTRES (TALKBD, ...)
                    i = $(md_slc).data("item");
                }
                
//                if ( ( KgbLib_CheckNullity(i) && KgbLib_CheckNullity($(md_slc).data("cnid")) ) | KgbLib_CheckNullity(atp) ) {
                if ( KgbLib_CheckNullity(i) ) {
                    $(".jb-tlkb-uqv-a-m-m-m-dl-btn, .jb-tlkb-uqv-art-m-m-m-d-c-a").data("lk",0);
                    return;
                }
                
//                if ( KgbLib_CheckNullity(i) && !KgbLib_CheckNullity($(md_slc).data("cnid")) ) {
//                    i = $(md_slc).data("cnid");
//                }
                
//                Kxlib_DebugVars([i,atp],true);
//                return;

                /*
                 * ETAPE :
                 *      Envoyer les données au serveur
                 */
                var s = $("<span/>");

//                Kxlib_DebugVars([i],true);

                _f_Srv_DelTsty(i,atp,x,s);
                
                /*
                 * ETAPE :
                 *      On masque l'élément dans la liste
                 */
                $(md_slc).addClass("this_hide");
                
                /*
                 * ETAPE :
                 *      Fermer TLKBVIEW
                 */
                $(".jb-tlkb-uqv-close-trg").click();

                $(s).on("operended",function(e,d){
                    if ( KgbLib_CheckNullity(d) ) {
                        return;
                    }

//                    Kxlib_DebugVars([d],true);

                    /*
                     * ETAPE :
                     *      On retire l'élément
                     */
                    $(md_slc).remove();
                    
                    
                    var Nty = new Notifyzing();
                    Nty.FromUserAction("ua_del_testy");
                    
                    /*
                     * ETAPE :
                     *      On gère les cas particuliers selon la PAGE d'accueil
                     */
                    switch (atp) {
                        case "tmlnr" :
                                /*
                                 * ETAPE :
                                 *      Si nous n'avons plus d'ARTICLE TSM (en général) au niveau de la PAGE, on REDIR vbers HOME
                                 */
                                if (! $(".jb-tbv-bind-art-mdl").length ) {
                                    window.location.reload();
                                    
                                    /*
                                     * ETAPE :
                                     *      On affiche l'indicateur qui demande de patienter
                                     */
                                    if ( $(".jb-pg-sts").length && !$(".jb-tbv-bind-art-mdl").length ) {
                                        $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Wait3p"));
                                        $(".jb-pg-sts").removeClass("this_hide");
                                    } 
                                }
                            break;
                        case "hview" :
                                /*
                                 * ETAPE :
                                 *      Si nous n'avons plus d'ARTICLE (en général) au niveau de la PAGE, on REDIR vbers HOME
                                 */
                                if (! $(".jb-tqr-hview-art-bmx").length ) {
//                                    window.location.href = "/";
                                    window.location.reload();
                                }
                                
                                /*
                                 * ETAPE :
                                 *      On affiche l'indicateur qui demande de patienter
                                 */
                                if ( $(".jb-pg-sts").length && !$(".jb-tqr-hview-art-bmx").length ) {
                                    $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Wait3p"));
                                    $(".jb-pg-sts").removeClass("this_hide");
                                } 
                            break;
                        case "fksa" :
                                /*
                                 * ETAPE :
                                 *      Dans tous les cas, on REDIR vbers HOME
                                 */
                                window.location.href = "/";
                                
                                /*
                                 * ETAPE :
                                 *      On affiche l'indicateur qui demande de patienter
                                 */
                                if ( $(".jb-pg-sts").length ) {
                                    $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Wait3p"));
                                    $(".jb-pg-sts").removeClass("this_hide");
                                } 
                            break;
                        default:
                            break;
                    }

                    /*
                     * ETAPE :
                     *      On libère les BOUTONS et le POINTEUR.
                     * [NOTE 01-06-16]
                     *      Ce code source a été importé
                     */
                    $(".jb-tlkb-uqv-a-m-m-m-dl-btn, .jb-tlkb-uqv-art-m-m-m-d-c-a").data("lk",0);
                    _xhr_tsty_del = null;
                });

            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_SprtUpdDs = function (ds) {
            try {
                if ( KgbLib_CheckNullity(ds) ) {
                    return;
                }
                
                /*
                 * ETAPE :
                 *      On met à jour la donnée indiquant si l'utilisateur a LIKE ou pas.
                 */
                var hslk = ( ds.hslk ) ? "me" : ""; 
                if ( hslk === "me" ) {
                    $(".jb-tlkb-uqv-art-m-m-m-e-a")
                        .data("state",hslk).attr("data-state",hslk)
                            .data("action","post-unlike").data("actrvs","post-like");
                } else {
                    $(".jb-tlkb-uqv-art-m-m-m-e-a")
                        .data("state","").attr("data-state","")
                            .data("action","post-like").data("actrvs","post-unlike");
                }
                   
                /*
                 * ETAPE :
                 *      On met à jour les données sur le nombre de REACTS & LIKES
                 */
                $(".jb-tlkb-uqv-art-m-m-m-rnb").text(ds.rnb);
                $(".jb-tlkb-uqv-art-m-m-m-e-a").text(ds.lnb);
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
    
        /*********************************************************************************************************************************************************************/
        /**************************************************************************** REACTION SCOPE *************************************************************************/
        
        var _f_AddRct = function (x) {
            try { 
                if ( KgbLib_CheckNullity(x) ) {
                    return;
                }
                
                var $art = $(".jb-tbuv-add-react-txar");
                if (! $art.val() ) {
                    return;
                }
                var text = $art.val();
                
                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                $(x).data("lk",1);
                
                /*
                 * ETAPE :
                 *      Vider le TEXTAREA
                 */
                $art.val("");
                
                if (! _f_AddRCtrl(text) ) {
                    $(x).data("lk",0);
                    return;
                }
//                Kxlib_DebugVars([text],true);
                
                
                var s = $("<span/>"), xt = (new Date()).getTime();
                
                /*
                 * [DEPUIS 21-06-16]
                 *      Permet une meilleure sélection de l'ID en prennat en compte tous les cas.
                 */
                if ( !KgbLib_CheckNullity($(md_slc).data("thm")) && $.inArray($(md_slc).data("thm"),["XTSM","XTSR","XTSL","XTST_USTG"]) !== -1 && !KgbLib_CheckNullity($(md_slc).data("ai")) ) {
                    //CAS : PSMN
                    i = $(md_slc).data("ai");
                } else if ( !KgbLib_CheckNullity($(md_slc).data("cnid")) ) {
                    //CAS : HVIEW
                    i = $(md_slc).data("cnid");
                } else {
                    //CAS : AUTRES (TALKBD, ...)
                    i = $(md_slc).data("item");
                }
                var prm = {
                    /*
                     * [DEPUIS 21-06-16] 
                     *      Cette méthode manquait de fiabilité.
                     */
//                    ti : md_props.i, 
                    ti : i,
                    tx : text,
                    pi : "",
                    pt : ""
                };
                
//                Kxlib_DebugVars([JSON.stringify(prm)],true);
//                return;
                
                _f_Srv_AdTsr(prm.ti,prm.tx,prm.pi,prm.pt,xt,x,s);
                
                $(s).on("datasready",function(e,d){
                    if ( KgbLib_CheckNullity(d) ) {
                        return;
                    }
                    
//                    Kxlib_DebugVars([JSON.stringify(d)],true);
                    
//                    if ( xt !== parseInt(d.xt) ) {
                    if ( parseFloat(xt) !== parseFloat(d.xt) ) {
                        _xhr_adrc = null;
                        return;
                    }
                    
//                    Kxlib_DebugVars([JSON.stringify(d)],true);
                    
                    /*
                     * ETAPE :
                     *      On affiche les commentaires
                     */
                    _f_Rct_AddList(d.tsrds);
                    
                    /*
                     * ETAPE :
                     *      On affiche le LOAD_MORE en fonction du nombre de commentaires restant
                     */
//                    _f_ldmore(true);

                    /*
                     * ETAPE :
                     *      On masque le spinner
                     */
//                    _f_spnr();

                    /*
                     * ETAPE :
                     *      On mets à jour les données sur les Commentaires
                     */
                    //...TLBVR
                    $(".jb-tlkb-uqv-art-bmx").find(".jb-tlkb-uqv-art-m-m-m-rnb").text(d.rnb);
                    //...MODELE EN PAGE
                    $(md_slc).find(".jb-tqr-tsty-art-opt.react").text(d.rnb);
                    
                    $(x).data("lk",0);
                    _xhr_adrc = null;
                });
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_AddRCtrl = function(text) {
            try {
                if ( KgbLib_CheckNullity(text) ) {
                    return;
                }
                
                var rgx = /^[\s\b\t\n\r]+$/;
                return ( text.length && !rgx.test(text) && text.length <= _f_Gdf().adr_maxln );
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_DelRct = function (x,a) {
            try { 
                if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                var $rmdl = $(x).closest(".jb-tlkb-uqv-r-mdl-mx");
                if (! $rmdl.length ) {
                    alert("N'existe pas !");
                    return;
                }
                
                switch (a) {
                    case "tbuv-react-del" :
                            $(x).addClass("this_hide");
                            $rmdl.find(".jb-tlkb-uqv-r-m-x-d-cfmx").removeClass("this_hide");
                        return;
                    case "tbuv-react-del-n" :
                            $rmdl.find(".jb-tlkb-uqv-r-m-x-d-cfmx").addClass("this_hide");
                            $rmdl.find(".jb-tbuv-action[data-action='tbuv-react-del']").removeClass("this_hide");
                        return;
                    case "tbuv-react-del-y" :
//                            alert("Lancer la suppression !");
                        break;
                    default :
                        return;
                }
                
                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                $(x).data("lk",1);
                
                if (! KgbLib_CheckNullity(_xhr_dlrc) ) {
                    $(x).data("lk",0);
                    return;
                }
                
                var s = $("<span/>"), xt = (new Date()).getTime();
                
                var prm = {
                    ti : md_props.i,
                    ri : $rmdl.data("item"),
                    rt : $rmdl.data("time")
                };
                
//                Kxlib_DebugVars([JSON.stringify(prm)],true);
//                return;
                
                _f_Srv_DlTsr(prm.ti,prm.ri,prm.rt,xt,x,s);
                
                $rmdl.find(".jb-tlkb-uqv-rct-m-dl-fade").removeClass("this_hide");
                
                $(s).on("operended",function(e,d){
                    if ( KgbLib_CheckNullity(d) ) {
                        return;
                    }
                    
//                    Kxlib_DebugVars([JSON.Stringify(d)],true);
//                    return;

                    $rmdl.remove();
                    
                    if ( d.rnb === 0 ) {
                        _f_none(true);
                        _f_ldmore();
                    }
                    
                    /*
                     * ETAPE :
                     *      On mets à jour les données sur les Commentaires
                     */
                    //...TLBVR
                    $(".jb-tlkb-uqv-art-bmx").find(".jb-tlkb-uqv-art-m-m-m-rnb").text(d.rnb);
                    //...MODELE EN PAGE
                    if ( $(md_slc).find(".jb-tqr-tsty-art-opt.react").length ) {
                        $(md_slc).find(".jb-tqr-tsty-art-opt.react").text(d.rnb);
                    }
                    
                    /*
                     * ETAPE :
                     *      On libère le POINTEUR
                     */
                    _xhr_dlrc = null;
                });
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_React_LdMr = function (x,a) {
            try {
                
                /*
                 * [DEPUIS 02-04-16]
                 */
                var dr, pi, pt;
                if ( !KgbLib_CheckNullity(x) && $(x).data("lk") === 1 ) {
                    alert("TBUV_LOAD_TSR (Locked) > 1230");
                    return;
                } else if ( $(".jb-tlkb-uqv-art-m-m-m-lklst").data("lk") === 1 ) {
                    alert("TBUV_LOAD_TSR (Locked) > 1233");
                    return;
                } else if ( !KgbLib_CheckNullity(x) && !KgbLib_CheckNullity(a) && a === "tbuv-react-ld-mr" ) {
                    dr = "BTM";
                    
                    pi = ( $(".jb-tlkb-uqv-r-mdl-mx:first").data("item") ) ? $(".jb-tlkb-uqv-r-mdl-mx:first").data("item") : "";
                    pt = ( $(".jb-tlkb-uqv-r-mdl-mx:first").data("time") ) ? $(".jb-tlkb-uqv-r-mdl-mx:first").data("time") : "";
                    
//                    $(x).data("lk",1); //[DEPUIS 13-06-16]
                } else if ( !KgbLib_CheckNullity(x) && !KgbLib_CheckNullity(a) && a === "post-pl-reacts" ) {
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
                 * [DEPUIS 13-06-16]
                 *      Bloquer les bouton de PULL des éléments : REACTIONS, LIKES
                 */
                $(".jb-tlkb-uqv-art-m-m-m-rnb, .jb-tlkb-uqv-art-m-m-m-lklst").data("lk",1);
                
                
//                Kxlib_DebugVars(["TBUV_LOAD_MORE : ",_xhr_plls]);
                /*
                 * [DEPUIS 03-04-16]
                 */
                if ( !KgbLib_CheckNullity(_xhr_plrs) ) {
                    /*
                    if ( !KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    return;
                    //*/
//                    Kxlib_DebugVars(["TBUV_LOAD_MORE : OPS ABONDONNÉE"]);
                    _xhr_plrs.abort();
                } 
                
                /*
                 * [DEPUIS 13-06-16]
                 *      On annule aussi du côté de REACT car les deux processus partagent la même ZONE !
                 * [NOTE 22-06-16]
                 *      J'ai fait référence à "_xhr_plrs" quand de toute logique, il devrait s'agir de "_xhr_adrc"
                 */
                if ( !KgbLib_CheckNullity(_xhr_adrc) ) {
//                    Kxlib_DebugVars(["TBUV_LOAD_MORE_REACTS : OPS ABONDONNÉE"]);
                    _xhr_adrc.abort();
                } 
                
                
                if ( dr === "FST" ) {
                   /*
                    * ETAPE :
                    *       On efface les modèles de LIKES déjà affichés.
                    *       On efface les modèles de REACTS déjà affichés.
                    */
                    $sprt.find(".jb-tlkb-uqv-lk-mdl-mx").remove();
                    $sprt.find(".jb-tlkb-uqv-r-mdl-mx").remove();
                    
                   /*
                    * ETAPE :
                    *      On masque le LOAD_MORE
                    */
                    _f_ldmore();
                    _f_ldmore_a("tbuv-react-ld-mr");
                
                    /*
                     * ETAPE :
                     *      On masque NONE
                     */
                    _f_none();
                
                    /*
                     * ETAPE :
                     *      On affiche le spinner
                     */
                    _f_spnr(true);
                } else {
                    _f_LdMoreWait(true);
                }
                
                /*
                 * [DEPUIS 14-07-16]
                 */
                var an = cargs.model.replace(/(?:ajca-)?(.+)/gi,"$1");
                an = an.toLowerCase();
                
                var prm = {
                    ti  : md_props.i,
                    pi  : pi,
                    pt  : pt,
                    dr  : dr,
                    an  : an
                };
                /*
                if ( dr === "BTM" ) {
                    Kxlib_DebugVars([JSON.stringify(prm)],true);
                    return;
                }
                //*/
                var xt = (new Date()).getTime();
                var s = $("<span/>");
                
                
//                Kxlib_DebugVars(["TBUV_TIME_REF : LANCÉ !"]);
                _f_Srv_PlTsr(prm.ti,prm.dr,prm.an,prm.pi,prm.pt,xt,x,s);
//                alert("Demander les données sur les commentaires au serveur");
                
                $(s).on("datasready",function(e,d){
                    if ( KgbLib_CheckNullity(d) ) {
                        return;
                    }
                    
                    Kxlib_DebugVars(["TBUV_TIME_REF : ",parseFloat(xt),parseFloat(d.xt)]);
//                    if ( xt !== parseInt(d.xt) ) {
                    if ( parseFloat(xt) !== parseFloat(d.xt) ) {

//                        _xhr_plrs = null;
//                        $(x).data("lk",0);
                        $sprt.find(".jb-tlkb-uqv-lk-mdl-mx").remove();
                        $sprt.find(".jb-tlkb-uqv-r-mdl-mx").remove();
                        return;
                    }
                    
//                    Kxlib_DebugVars([JSON.stringify(d)],true);

                    
                    /*
                     * ETAPE :
                     *      On affiche les commentaires
                     */
                    _f_Rct_AddList(d.tsrds,dr);
                    
                    /*
                     * ETAPE :
                     *      On affiche le LOAD_MORE en fonction du nombre de commentaires restant
                     */
                    var rnb = d.tstmetas.cnrct, rest = rnb - $(".jb-tlkb-uqv-r-mdl-mx").length;
                    if ( rest > 0 ) {
                        _f_LdMoreWait();
                        _f_ldmore(true,rest);
                    } else {
                        _f_LdMoreWait();
                        _f_ldmore();
                    }
                    
                    /*
                     * [DEPUIS 13-06-16]
                     *      On affiche la ZONE d'ajout de REACTS car elle peut avoir été masquée
                     */
                    _f_RctZnSh(true);
                    
                    /*
                     * [DEPUIS 13-06-16]
                     *      On met à jour les données au niveau de TALKBOARD_VIEW
                     */
                    var upds = {
                        "hslk"  : d.tstmetas.hslk, 
                        "lnb"   : d.tstmetas.cnlk,
                        "rnb"   : d.tstmetas.cnrct,
                    };
                    _f_SprtUpdDs(upds);
                    
                    /*
                     * ETAPE :
                     *      On masque le spinner
                     */
                    _f_spnr();
                    _xhr_plrs = null;
//                    $(x).data("lk",0);
                    $(".jb-tlkb-uqv-art-m-m-m-rnb, .jb-tlkb-uqv-art-m-m-m-lklst").data("lk",0);
                });
                
                
                $(s).on("operended",function(e,d){
                    if ( KgbLib_CheckNullity(d) ) {
                        return;
                    }
                    
                    if ( dr === "BTM" ) {
                        _f_LdMoreWait();
                    }
                    
                    _f_ldmore(false);
                    
                    /*
                     * ETAPE :
                     *      On masque le spinner
                     */
                    _f_spnr();
                    
                    /*
                     * ETAPE :
                     *      On affiche NONE
                     */
                    _f_none(true);
                    
                    /*
                     * [DEPUIS 13-06-16]
                     *      On affiche la ZONE d'ajout de REACTS car elle peut avoir été masquée
                     */
                    _f_RctZnSh(true);
                    
                    _xhr_plrs = null;
//                    $(x).data("lk",0);
                    $(".jb-tlkb-uqv-art-m-m-m-rnb, .jb-tlkb-uqv-art-m-m-m-lklst").data("lk",0);
                });
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Rct_AddList = function (ds,dr) {
            try {
                if ( KgbLib_CheckNullity(ds) ) {
                    return;
                }
                
                _f_none();
                
                ds = ( dr && dr === "BTM" ) ? ds : ds.reverse();
                $.each(ds,function(i,d){
                    if ( $(".jb-tlkb-uqv-art-m-r-l").find(".jb-tlkb-uqv-r-mdl-mx[data-item='"+d.pdrtab.id+"']").length ) {
                        return true;
                    }
                    
                    var mb = _f_Rct_PprMdl(d);
                    mb = _f_RbdMdl(mb);
                    
                    if ( dr === "BTM" ) {
                        $(mb).hide().prependTo(".jb-tlkb-uqv-art-m-r-l").fadeIn();
                    } else {
                        $(mb).hide().appendTo(".jb-tlkb-uqv-art-m-r-l").fadeIn();
                    }
                    
                });
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Rct_PprMdl = function (d) {
            try {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                /*
                 * 
                 */
                
                var m = "<article class=\"tlkb-uqv-rct-mdl-mx jb-tlkb-uqv-r-mdl-mx\">";
                m += "<span class=\"tlkb-uqv-rct-m-dl-fade jb-tlkb-uqv-rct-m-dl-fade this_hide\"></span>";
                m += "<div class=\"tlkb-uqv-rct-m-ubx-mx\">";
                m += "<a class=\"tlkb-uqv-rct-m-ubx-a jb-tlkb-uqv-r-m-u-a\" href=\"/\">";
                m += "<img class=\"tlkb-uqv-rct-m-ubx-i jb-tlkb-uqv-r-m-u-i\" width=\"35\" height=\"35\" src=\"\">";
                m += "<span class=\"tlkb-uqv-rct-m-ubx-p jb-tlkb-uqv-r-m-u-p\"></span>";
                m += "</a>";
//                m += "<span class=\"kxlib_tgspy tsty-tm tlkb-uqv-rct-m-tm jb-tlkb-uqv-rct-m-tm\" data-tgs-crd=\"\" data-tgs-dd-atn=\"\" data-tgs-dd-uut=\"\">";
                m += "<span class=\"kxlib_tgspy tlkb-uqv-rct-m-tm jb-tlkb-uqv-rct-m-tm\" data-tgs-crd=\"\" data-tgs-dd-atn=\"\" data-tgs-dd-uut=\"\">";
                m += "<span class='tgs-frm'></span>";
                m += "<span class='tgs-val'></span>";
                m += "<span class='tgs-uni'></span>";
                m += "</span>";
                m += "</div>";
                m += "<div class=\"tlkb-uqv-rct-m-msg-mx jb-tlkb-uqv-rct-m-m-mx\"></div>";
                m += "<div class=\"tlkb-uqv-rct-m-xa jb-tlkb-uqv-rct-m-xa\">";
                m += "<div class=\"tlkb-uqv-rct-m-xa-dl-cfmx jb-tlkb-uqv-r-m-x-d-cfmx this_hide\">";
                m += "<span class=\"tlkb-uqv-rct-m-xa-dl-cf-lbl\">Êtes vous sur ?</span>";
                m += "<a class=\"tlkb-uqv-rct-m-xa-dl-cf-a jb-tbuv-action\" data-action=\"tbuv-react-del-y\">Oui</a>";
                m += "<a class=\"tlkb-uqv-rct-m-xa-dl-cf-a jb-tbuv-action\" data-action=\"tbuv-react-del-n\">Non</a>";
                m += "</div>";
                m += "<a class=\"tlkb-uqv-rct-m-xa-dl-btn jb-tbuv-action\" data-action=\"tbuv-react-del\">Supprimer</a>";
                m += "<a class=\"tlkb-uqv-rct-m-xa-answ-btn jb-tbuv-action\" data-action=\"tbuv-react-answ\">@Répondre</a>";
                m += "</div>";
                m += "</article>";
                m = $.parseHTML(m);
                
                
                var ustgs = Kxlib_SetDataWith(d.pdrtab.usertags);
                
                $(m).attr("data-item",d.pdrtab.id).data("item",d.pdrtab.id).data("with",Kxlib_ReplaceIfUndefined(ustgs)).data("time",d.pdrtab.date);
                
                $(m).find(".jb-tlkb-uqv-r-m-u-a").prop("href","/".concat(d.pdrtab.autab.ps));
                //PP
                $(m).find(".jb-tlkb-uqv-r-m-u-i").prop("src",d.pdrtab.autab.pp);
                //PSD
                $(m).find(".jb-tlkb-uqv-r-m-u-p").text("@".concat(d.pdrtab.autab.ps));
                
                //TIME
//                Kxlib_DebugVars(["TBUV_RCT_DATE : ",d.pdrtab.date]);
                $(m).find(".jb-tlkb-uqv-rct-m-tm").data("tgs-crd",d.pdrtab.date).attr("data-tgs-crd",d.pdrtab.date);
                
                //TEXT
                var txt = Kxlib_TextEmpow(d.pdrtab.text,d.pdrtab.usertags,d.pdrtab.hashtags,null,{
                    emoji : {
                        "size"          : 36,
                        "size_css"      : 22,
                        "position_y"    : 3
                    }
                });
                $(m).find(".jb-tlkb-uqv-rct-m-m-mx").append(txt);
                
                /*
                 * ETAPE :
                 *      Gestion des AUTORISATIONS
                 */
                if (! d.pdrtab.cdl ) {
                    $(m).find(".jb-tlkb-uqv-r-m-x-d-cfmx").remove();
                    $(m).find(".jb-tbuv-action[data-action='tbuv-react-del']").remove();
                }
                
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
                
                $(mb).find(".jb-tbuv-action, .jb-tlkb-uqv-a-m-l-a").off("click").click(function(e){
                    Kxlib_PreventDefault(e);

                    _f_Action(this);
                });
                
                return mb;
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        this.react_add = function () {
            try {
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        this.react_del = function () {
            try {
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        var _f_AnswRct = function (x) {
            try {
                if ( KgbLib_CheckNullity(x) ) { 
                    return; 
                }

                if ( $(x).data("lk") === 1 ) {
                    return;
                }
                $(x).data("lk",1);

                var $ps = $(x).closest(".jb-tlkb-uqv-r-mdl-mx").find(".jb-tlkb-uqv-r-m-u-p");
                if ( !$ps.length | KgbLib_CheckNullity($ps.text()) ) {
                    return;
                }

                var ps = $ps.text();
                $(".jb-tbuv-add-react-txar").val(function(a,prev){
                    return prev.concat(ps," ");
                });
                $(".jb-tbuv-add-react-txar").focus();

                $(x).data("lk",0);
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        /********************************************************************************************************************************************************************/
        /***************************************************************************** LIKES SCOPE **************************************************************************/
                
        var _f_Like_LdMr = function (x,a) {
            try {
                
                var dr, pi, pt;
                if ( !KgbLib_CheckNullity(x) && $(x).data("lk") === 1 ) {
                    return;
                } else if ( $(".jb-tlkb-uqv-art-m-m-m-rnb").data("lk") === 1 ) {
                    return;
                } else if ( !KgbLib_CheckNullity(x) && !KgbLib_CheckNullity(a) && a === "tbuv-like-ld-mr" ) {
                    dr = "BTM";
                    
                    pi = ( $(".jb-tlkb-uqv-lk-mdl-mx:first").data("item") ) ? $(".jb-tlkb-uqv-lk-mdl-mx:first").data("item") : "";
                    pt = ( $(".jb-tlkb-uqv-lk-mdl-mx:first").data("time") ) ? $(".jb-tlkb-uqv-lk-mdl-mx:first").data("time") : "";
                    
//                    $(x).data("lk",1); //[DEPUIS 13-06-16]
                } else if ( !KgbLib_CheckNullity(x) && !KgbLib_CheckNullity(a) && a === "post-pl-likes" ) {
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
                 * [DEPUIS 13-06-16]
                 *      Bloquer les bouton de PULL des éléments : REACTIONS, LIKES
                 */
                $(".jb-tlkb-uqv-art-m-m-m-rnb, .jb-tlkb-uqv-art-m-m-m-lklst").data("lk",1);
                
                
                /*
                 * [DEPUIS 13-06-16]
                 *      On masque la ZONE d'ajout de REACT
                 */
                _f_RctZnSh();
                
                
//                Kxlib_DebugVars(["TBUV_LOAD_MORE : ",_xhr_plls]);
                /*
                 * [DEPUIS 03-04-16]
                 */
                if ( !KgbLib_CheckNullity(_xhr_plls) ) {
//                    Kxlib_DebugVars(["TBUV_LOAD_MORE_LIKES : OPS ABONDONNÉE"]);
                    _xhr_plls.abort();
                } 
                
                /*
                 * [DEPUIS 13-06-16]
                 *      On annule aussi du côté de REACT car les deux processus partagent la même ZONE !
                 */
                if ( !KgbLib_CheckNullity(_xhr_plrs) ) {
//                    Kxlib_DebugVars(["TBUV_LOAD_MORE_REACTS : OPS ABONDONNÉE"]);
                    _xhr_plrs.abort();
                } 
                
                if ( dr === "FST" ) {
                   /*
                    * ETAPE :
                    *       On efface les modèles de LIKES déjà affichés.
                    *       On efface les modèles de REACTS déjà affichés.
                    */
                    $sprt.find(".jb-tlkb-uqv-lk-mdl-mx").remove();
                    $sprt.find(".jb-tlkb-uqv-r-mdl-mx").remove();
                    
                   /*
                    * ETAPE :
                    *      On masque le LOAD_MORE
                    */
                    _f_ldmore();
                    _f_ldmore_a("tbuv-like-ld-mr");
                
                    /*
                     * ETAPE :
                     *      On masque NONE
                     */
                    _f_none();
                
                    /*
                     * ETAPE :
                     *      On affiche le spinner
                     */
                    _f_spnr(true);
                } else {
                    _f_LdMoreWait(true);
                }
                
                /*
                 * [DEPUIS 14-07-16]
                 */
                var an = cargs.model.replace(/(?:ajca-)?(.+)/gi,"$1");
                an = an.toLowerCase();
                
                var prm = {
                    ti : md_props.i,
                    pi : pi,
                    pt : pt,
                    dr : dr,
                    an  : an
                };
                /*
                if ( dr === "BTM" ) {
                    Kxlib_DebugVars([JSON.stringify(prm)],true);
                    return;
                }
                //*/
                var xt = (new Date()).getTime();
                var s = $("<span/>");
                
//                Kxlib_DebugVars(["TBUV_TIME_REF : LANCÉ !"]);
                _f_Srv_PlTsl(prm.ti,prm.dr,prm.an,prm.pi,prm.pt,xt,x,s);
//                alert("Demander les données sur les commentaires au serveur");
                
                $(s).on("datasready",function(e,d){
                    if ( KgbLib_CheckNullity(d) ) {
                        return;
                    }
                    
//                    Kxlib_DebugVars(["TBUV_TIME_REF : ",xt,d.xt]);
//                    if ( xt !== parseInt(d.xt) ) {
                    if ( parseFloat(xt) !== parseFloat(d.xt) ) {

//                        _xhr_plls = null;
//                        $(x).data("lk",0);
                        $sprt.find(".jb-tlkb-uqv-lk-mdl-mx").remove();
                        $sprt.find(".jb-tlkb-uqv-r-mdl-mx").remove();
                        return;
                    }
                    
//                    Kxlib_DebugVars([JSON.stringify(d)],true);
//                    return;
                    
                    /*
                     * ETAPE :
                     *      On affiche les commentaires
                     */
                    _f_Like_AddList(d.tsrds,dr);
                    
                    /*
                     * ETAPE :
                     *      On affiche le LOAD_MORE en fonction du nombre de LIKES restant
                     */
                    var lnb = d.tstmetas.cnlk, rest = lnb - $(".jb-tlkb-uqv-lk-mdl-mx").length;
                    if ( rest > 0 ) {
                        _f_LdMoreWait();
                        _f_ldmore(true,rest);
                    } else {
                        _f_LdMoreWait();
                        _f_ldmore();
                    }
                    
                    /*
                     * [DEPUIS 13-06-16]
                     *      On met à jour les données au niveau de TALKBOARD_VIEW
                     */
                    var upds = {
                        "hslk"  : d.tstmetas.hslk, 
                        "lnb"   : d.tstmetas.cnlk,
                        "rnb"   : d.tstmetas.cnrct,
                    };
                    _f_SprtUpdDs(upds);
                    
                    /*
                     * ETAPE :
                     *      On masque le spinner
                     */
                    _f_spnr();
                    _xhr_plls = null;
//                    $(x).data("lk",0);
                    $(".jb-tlkb-uqv-art-m-m-m-rnb, .jb-tlkb-uqv-art-m-m-m-lklst").data("lk",0);
                });
                
                
                $(s).on("operended",function(e,d){
                    if ( KgbLib_CheckNullity(d) ) {
                        return;
                    }
                    
                    if ( dr === "BTM" ) {
                        _f_LdMoreWait();
                    }
                    
                    _f_ldmore(false);
                    
                    /*
                     * ETAPE :
                     *      On masque le spinner
                     */
                    _f_spnr();
                    /*
                     * ETAPE :
                     *      On affiche NONE
                     */
                    _f_none(true);
                    _xhr_plrs = null;
//                    $(x).data("lk",0);
                    $(".jb-tlkb-uqv-art-m-m-m-rnb, .jb-tlkb-uqv-art-m-m-m-lklst").data("lk",0);
                });
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Like_AddList = function (ds,dr) {
            try {
                if ( KgbLib_CheckNullity(ds) ) {
                    return;
                }
                
                _f_none();
                
                ds = ( dr && dr === "BTM" ) ? ds : ds.reverse();
                $.each(ds,function(i,d){
                    if ( $(".jb-tlkb-uqv-art-m-r-l").find(".jb-tlkb-uqv-r-mdl-mx[data-item='"+d.tsltab.id+"']").length ) {
                        return true;
                    }
                    
                    var mb = _f_Like_PprMdl(d);
                    
                    if ( dr === "BTM" ) {
                        $(mb).hide().prependTo(".jb-tlkb-uqv-art-m-r-l").fadeIn();
                    } else {
                        $(mb).hide().appendTo(".jb-tlkb-uqv-art-m-r-l").fadeIn();
                    }
                    
                });
                
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
                    case "__ERR_VOL_TSM_GONE" :
                    case "__ERR_VOL_TESTY_GONE" :
                    case "__ERR_VOL_TST_GONE" :
                            _f_ErHd_TstGn();
                        break;
                    default:
                        return;
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        }
        
        
        var _f_ErHd_TstGn = function () {
            try {
                
                /*
                 * TODO : Afficher un message de courtoisie
                 */
                
                /*
                 * ETAPE :
                 *      On efface l'élément SOURCE
                 */
                $(md_slc).remove();
                
                /*
                 * ETAPE :
                 *      On ferme le module TLKBDVW
                 */
                $(".jb-tlkb-uqv-close-trg").click();

                var atp;
                if ( KgbLib_CheckNullity($(md_slc).data("atype")) && !KgbLib_CheckNullity($(md_slc).data("thm")) && $.inArray($(md_slc).data("thm"),["XTSM","XTSR","XTSL","XTST_USTG"]) !== -1 ) {
                    atp = "psmn";
                } else {
                    atp = $(md_slc).data("atype");
                }
                
                /*
                 * ETAPE :
                 *      On gère les cas particuliers selon la PAGE d'accueil
                 */
                switch (atp) {
                        case "tmlnr" :
                                /*
                                 * ETAPE :
                                 *      Si nous n'avons plus d'ARTICLE TSM (en général) au niveau de la PAGE, on REDIR vbers HOME
                                 */
                                if (! $(".jb-tbv-bind-art-mdl").length ) {
                                    window.location.reload();
                                    
                                    /*
                                     * ETAPE :
                                     *      On affiche l'indicateur qui demande de patienter
                                     */
                                    if ( $(".jb-pg-sts").length && !$(".jb-tbv-bind-art-mdl").length ) {
                                        $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Wait3p"));
                                        $(".jb-pg-sts").removeClass("this_hide");
                                    } 
                                }
                            break;
                        case "hview" :
                                /*
                                 * ETAPE :
                                 *      Si nous n'avons plus d'ARTICLE (en général) au niveau de la PAGE, on REDIR vbers HOME
                                 */
                                if (! $(".jb-tqr-hview-art-bmx").length ) {
//                                    window.location.href = "/";
                                    window.location.reload();
                                }
                                
                                /*
                                 * ETAPE :
                                 *      On affiche l'indicateur qui demande de patienter
                                 */
                                if ( $(".jb-pg-sts").length && !$(".jb-tqr-hview-art-bmx").length ) {
                                    $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Wait3p"));
                                    $(".jb-pg-sts").removeClass("this_hide");
                                } 
                            break;
                        case "fksa" :
                                /*
                                 * ETAPE :
                                 *      Dans tous les cas, on RELOAD
                                 */
                                 window.location.reload();
                                
                                
                                /*
                                 * ETAPE :
                                 *      On affiche l'indicateur qui demande de patienter
                                 */
                                if ( $(".jb-pg-sts").length ) {
                                    $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Wait3p"));
                                    $(".jb-pg-sts").removeClass("this_hide");
                                } 
                            break;
                        default:
                            break;
                    }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        }
        
        
        /*************************************************************************************************************************************************************************/
        /**************************************************************************** ACCESSORS SCOPE ****************************************************************************/
        /*************************************************************************************************************************************************************************/
        
        this.getMdProps = function () {
            try {
//                alert(JSON.stringify(md_props));
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        /*******************************************************************************************************************************************************************/
        /***************************************************************************** SERVER SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        
        var _Ax_PlTsr = Kxlib_GetAjaxRules("TQR_TBUV_PL_TSR");
        var _f_Srv_PlTsr = function(ti,dr,an,pi,pt,xt,x,s) {
            if ( KgbLib_CheckNullity(ti) | KgbLib_CheckNullity(dr) | KgbLib_CheckNullity(an) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(s) ) {
                return;
            }
        
            var onsuccess = function (datas) {
//                alert("CHAINE JSON AVANT PARSE"+datas);
                try {
                    if (! KgbLib_CheckNullity(datas) ) {
                        datas = JSON.parse(datas);
                    } else {
                        if (! KgbLib_CheckNullity(x) ) {
                            $(x).data("lk",0);
                        }
                        _xhr_plrs = null;
                        return;
                    }
                
                if (! KgbLib_CheckNullity(datas.err) ) {
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    _xhr_plrs = null;
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
                } else if ( !KgbLib_CheckNullity(datas.return) && !KgbLib_CheckNullity(datas.return.tsrds) ) {
                    var ds = [datas.return];
                    $(s).trigger("datasready",ds);
                } else if ( !KgbLib_CheckNullity(datas.return) && datas.return.hasOwnProperty("iwd") && datas.return.hasOwnProperty("ird") ) {
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
                    _xhr_plrs = null;

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
                "urqid": _Ax_PlTsr.urqid,
                "datas": {
                    "ti"    : ti,
                    "dr"    : dr,
                    "an"    : an,
                    "pi"    : pi,
                    "pt"    : pt,
                    "xt"    : xt,
                    "cu"    : curl
                }
            };

            _xhr_plrs = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlTsr.url, wcrdtl : _Ax_PlTsr.wcrdtl });
//            Kxlib_DebugVars(["TBUV_AJAX_POINTER : ",_xhr_plrs,"; FOR XT : ",xt]);
        };
        
        
        var _Ax_AdTsr = Kxlib_GetAjaxRules("TQR_TBUV_ADD_TSR");
        var _f_Srv_AdTsr = function(ti,tx,pi,pt,xt,x,s) {
            if ( KgbLib_CheckNullity(ti) | KgbLib_CheckNullity(tx) | KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
                return;
            }
        
            var onsuccess = function (datas) {
                try {
                    if (! KgbLib_CheckNullity(datas) ) {
                        datas = JSON.parse(datas);
                    } else {
                        if (! KgbLib_CheckNullity(x) ) {
                            $(x).data("lk",0);
                        }
                        _xhr_adrs = null;
                        return;
                    }
                
                    if (! KgbLib_CheckNullity(datas.err) ) {
                        if (! KgbLib_CheckNullity(x) ) {
                            $(x).data("lk",0);
                        }
                        _xhr_adrs = null;
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
                        } else if ( !KgbLib_CheckNullity(datas.return) && !KgbLib_CheckNullity(datas.return.tsrds) ) {
                        var ds = [datas.return];
                        $(s).trigger("datasready",ds);
                    } else if ( !KgbLib_CheckNullity(datas.return) && datas.return.hasOwnProperty("iwd") && datas.return.hasOwnProperty("ird") ) {
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
                    _xhr_adrs = null;

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
                var ercd = Kxlib_AjaxGblOnErr(a,b);

                //TODO : Send error to SERVER
//                Kxlib_DebugVars(["AJAX ERR : "+nwtrdart_uq],true);
                if (! ( ercd && ercd === 401 ) ) {
                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                }
                
                return;
            };
        
            var curl = document.URL;
            var toSend = {
                "urqid": _Ax_AdTsr.urqid,
                "datas": {
                    "ti"    : ti,
                    "tx"    : tx,
                    "pi"    : pi,
                    "pt"    : pt,
                    "xt"    : xt,
                    "cu"    : curl
                }
            };
        
            _xhr_adrs = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_AdTsr.url, wcrdtl : _Ax_AdTsr.wcrdtl });
        };
        
        
        var _Ax_DlTsr = Kxlib_GetAjaxRules("TQR_TBUV_DEL_TSR");
        var _f_Srv_DlTsr = function(ti,ri,rt,xt,x,s) {
            if ( KgbLib_CheckNullity(ti) | KgbLib_CheckNullity(ri) | KgbLib_CheckNullity(rt) | KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
                return;
            }
        
            var onsuccess = function (datas) {
                try {
                    if (! KgbLib_CheckNullity(datas) ) {
                        datas = JSON.parse(datas);
                    } else {
                        if (! KgbLib_CheckNullity(x) ) {
                            $(x).data("lk",0);
                        }
                        _xhr_dlrc = null;
                        return;
                    }
                
                    if (! KgbLib_CheckNullity(datas.err) ) {
                        if (! KgbLib_CheckNullity(x) ) {
                            $(x).data("lk",0);
                        }
                        _xhr_dlrc = null;
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
                        return;
                    } else {
                        return;
                    }
                
                } catch (ex) {
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    _xhr_dlrc = null;

                    //TODO : Renvoyer l'erreur au serveur
                    //                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
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
                "urqid": _Ax_DlTsr.urqid,
                "datas": {
                    "ti"    : ti,
                    "tri"   : ri,
                    "trt"   : rt,
                    "xt"    : xt,
                    "cu"    : curl
                }
            };
        
            _xhr_dlrc = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_DlTsr.url, wcrdtl : _Ax_DlTsr.wcrdtl });
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
        
        
        var _Ax_PlTsl = Kxlib_GetAjaxRules("TQR_TBUV_PL_TSL");
        var _f_Srv_PlTsl = function(ti,dr,an,pi,pt,xt,x,s) {
            if ( KgbLib_CheckNullity(ti) | KgbLib_CheckNullity(dr) | KgbLib_CheckNullity(an) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(s) ) {
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
                        _xhr_plrs = null;
                        return;
                    }
                
                if (! KgbLib_CheckNullity(datas.err) ) {
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    _xhr_plrs = null;
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
                } else if ( !KgbLib_CheckNullity(datas.return) && !KgbLib_CheckNullity(datas.return.tsrds) ) {
                    var ds = [datas.return];
                    $(s).trigger("datasready",ds);
                } else if ( !KgbLib_CheckNullity(datas.return) && datas.return.hasOwnProperty("iwd") && datas.return.hasOwnProperty("ird") ) {
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
                    _xhr_plrs = null;

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
                "urqid": _Ax_PlTsl.urqid,
                "datas": {
                    "ti"    : ti,
                    "dr"    : dr,
                    "an"    : an,
                    "pi"    : pi,
                    "pt"    : pt,
                    "xt"    : xt,
                    "cu"    : curl
                }
            };

            _xhr_plls = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlTsl.url, wcrdtl : _Ax_PlTsl.wcrdtl });
//            Kxlib_DebugVars(["TBUV_AJAX_POINTER : ",_xhr_plrs,"; FOR XT : ",xt]);
        };
        
        
        var _Ax_DelTsty = Kxlib_GetAjaxRules("TQR_TSTY_DEL");
        var _f_Srv_DelTsty  = function(i,atp,x,s) {
            if ( KgbLib_CheckNullity(i) |  KgbLib_CheckNullity(atp) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
                return;
            }

            var onsuccess = function (datas) {
                //            alert("CHAINE JSON AVANT PARSE"+datas);
                try {
                    if (! KgbLib_CheckNullity(datas) ) {
                        datas = JSON.parse(datas);
                    } else {
                        _xhr_tsty_del = null;
                        $(x).data("lk",0);
                        return;
                    }

                    if(! KgbLib_CheckNullity(datas.err) ) {
                        _xhr_tsty_del = null;
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
                    } else if ( !KgbLib_CheckNullity(datas.return) && datas.return.hasOwnProperty("iwd") && datas.return.hasOwnProperty("ird") ) {
                        var ds = [datas.return];
                        $(s).trigger("operended",ds);
                    } else {
                        return;
                    }

                } catch (ex) {
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
                "urqid": _Ax_DelTsty.urqid,
                "datas": {
                    "i"     : i,
                    "atp"   : atp,
                    "cu"    : curl
                }
            };

            _xhr_tsty_del = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_DelTsty.url, wcrdtl : _Ax_DelTsty.wcrdtl });
        };
        
        
        /*******************************************************************************************************************************************************************/
        /****************************************************************************** VIEW SCOPE *************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        var _f_spnr = function (shw) {
            try {
                
                if (shw) {
                    $(".jb-tlkb-uqv-a-m-s-mx").removeClass("this_hide");
                } else {
                    $(".jb-tlkb-uqv-a-m-s-mx").addClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_none = function (shw) {
            try {
                
                if (shw) {
                    $(".jb-tlkb-uqv-a-m-nn-mx").removeClass("this_hide");
                } else {
                    $(".jb-tlkb-uqv-a-m-nn-mx").addClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_ldmore = function (shw,nb) {
            try {
                
                if ( shw ) {
                    if ( nb ) {
                        var t = "(".concat(nb,")");
                        $(".jb-tlkb-uqv-a-m-l-nb").text(t);
                    } else {
                        $(".jb-tlkb-uqv-a-m-l-nb").text("");
                    }
                    
                    $(".jb-tlkb-uqv-a-m-l-a").removeClass("this_hide");
                } else {
                    $(".jb-tlkb-uqv-a-m-l-a").addClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_ldmore_a = function (a) {
            try {
                if ( KgbLib_CheckNullity(a) ) {
                    return;
                }
                
                switch (a) {
                    case "tbuv-react-ld-mr" :
                    case "tbuv-like-ld-mr" :
                            $(".jb-tlkb-uqv-a-m-l-a").data("action",a).attr("data-action",a);
                        break;
                    default :
                        return;
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_LdMoreWait = function (shw) {
            try {
                
                if ( shw ) {
                    $(".jb-tlkb-uqv-a-m-l-mx").addClass("this_hide");
                    $(".jb-tlkb-uqv-a-m-l-wait").removeClass("this_hide");
                } else {
                    $(".jb-tlkb-uqv-a-m-l-wait").addClass("this_hide");
                    $(".jb-tlkb-uqv-a-m-l-mx").removeClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_RctZnSh = function (shw) {
            try {
                
                if (shw) {
                    $(".jb-tlkb-uqv-a-m-m-ftr").removeClass("this_hide");
                } else {
                    $(".jb-tlkb-uqv-a-m-m-ftr").addClass("this_hide");
                }
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        
        var _f_Like_PprMdl = function (d) {
            try {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                var m = "<article class=\"tlkb-uqv-lk-mdl-mx jb-tlkb-uqv-lk-mdl-mx\">";
                m += "<span class=\"tlkb-uqv-rct-m-dl-fade jb-tlkb-uqv-rct-m-dl-fade this_hide\"></span>";
                m += "<div class=\"tlkb-uqv-rct-m-ubx-mx\">";
                m += "<a class=\"tlkb-uqv-rct-m-ubx-a jb-tlkb-uqv-r-m-u-a\" href=\"/\">";
                m += "<img class=\"tlkb-uqv-rct-m-ubx-i jb-tlkb-uqv-r-m-u-i\" width=\"35\" height=\"35\" src=\"\">";
                m += "<span class=\"tlkb-uqv-rct-m-ubx-p jb-tlkb-uqv-r-m-u-p\"></span>";
                m += "</a>";
                m += "<span class=\"kxlib_tgspy tlkb-uqv-rct-m-tm jb-tlkb-uqv-rct-m-tm\" data-tgs-crd=\"\" data-tgs-dd-atn=\"\" data-tgs-dd-uut=\"\">";
                m += "<span class='tgs-frm'></span>";
                m += "<span class='tgs-val'></span>";
                m += "<span class='tgs-uni'></span>";
                m += "</span>";
                m += "</div>";
                m += "</article>";
                m = $.parseHTML(m);
                
                $(m).attr("data-item",d.tsltab.id).data("item",d.tsltab.id).data("time",d.tsltab.date);
                
                $(m).find(".jb-tlkb-uqv-r-m-u-a").prop("href","/".concat(d.tsltab.autab.ps));
                //PP
                $(m).find(".jb-tlkb-uqv-r-m-u-i").prop("src",d.tsltab.autab.pp);
                //PSD
                $(m).find(".jb-tlkb-uqv-r-m-u-p").text("@".concat(d.tsltab.autab.ps));
                
                //TIME
//                Kxlib_DebugVars(["TBUV_RCT_DATE : ",d.tsltab.date]);
                $(m).find(".jb-tlkb-uqv-rct-m-tm").data("tgs-crd",d.tsltab.date).attr("data-tgs-crd",d.tsltab.date);
                
                return m;
                
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };
        
        /*******************************************************************************************************************************************************************/
        /************************************************************************** LISTENERS SCOPE ************************************************************************/
        /*******************************************************************************************************************************************************************/
        
        $(".jb-tbuv-action, .jb-tlkb-uqv-a-m-l-a, .jb-tlkb-uqv-art-m-m-m-e-a, .jb-tlkb-uqv-a-m-m-m-dl-btn, .jb-tlkb-uqv-art-m-m-m-d-c-a, .jb-tlkb-uqv-art-m-m-m-lklst, .jb-tlkb-uqv-art-m-m-m-rnb").off("click").click(function(e){
            Kxlib_PreventDefault(e);
            
            _f_Action(this);
        });
        
        
        $(".jb-tlkb-uqv-close-trg").off("click").click(function(e){
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