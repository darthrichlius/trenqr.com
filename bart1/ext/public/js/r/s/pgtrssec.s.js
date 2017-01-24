/*
 * Prend en charge les processus liés à la page /TRENDS
 * 
 * @author @BlackOwlRobot on 26-03-15 20:00
 */


(function SEC_TRS () {
    /*
     * FONCTIONNALITÉS :
     *  -> Compléter les données manquantes au niveau des Tendances affichées en ce qui concerne les Articles dits "Sample".
     *  -> Afficher la représentation textuelle de la date de création graceau timestamp disponible.
     *  -> Charger les Tendances les plus anciennes
     *  
     * EVOLUTIONS :
     *  -> Vérifier s'il y a de nouvelles Tendances
     *  -> Mettre à jour automatiquement la date de création des Tendances
     */
    
    var _xhr_fa;
    var _xhr_ptfm;
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** PROCESS SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    var _f_Gdf = function () {
        var ds = {
            //Le temps d'attente avant de lancer les procédures pour récupérer les données manquantes
            "when" : 1
//            "when" : 5000 //DEV, TEST, DEBUG
        };
        return ds;
    };
    
    setTimeout(function(){
//    setInterval(function(){
        _f_PA();
    },_f_Gdf().when);
    
    /*
     * Permet de compléter les données au niveau de la page.
     * Certains éléments affichés peuvent manqués de données pour des raisons de performance ou pour des raisons techniques.
     * Il peut par exemple s'agir d'un cas où une fonctionnalités existe déjà au niveau de la couche Metier de l'application au niveau duc client.
     * La méthode peut être appelée peut après le chargement de la page.
     */
    var _f_PA = function (ais) {
        
        try {
            
            //On vérifie qu'une opération n'est pas déjà lancée
            if (!KgbLib_CheckNullity(_xhr_fa)) {
                return;
            }
            
            /*
             * ETAPE : On récupère les données sur les Articles Sample
             */
            //On vérifie qu'il y a des Articles en attente
            if (!$(".jb-myts-m-md-spl-lst-item-mx").length) {
                return;
            } 
            
            //On récupère les identifiants pour chaque Article
            var is = [];
            if ( KgbLib_CheckNullity(ais) ) {
                $.each($(".jb-myts-m-md-spl-lst-item-mx"),function(x,e) {
                    //On vérifie si l'Article a déjà été chargé
                    if ($(e).data("cache") && $(e).find(".jb-myts-m-md-spl-lst-item-i")) {
                        return true;
                    }
                    is.push($(e).data("item"));
                });
            } else {
                is = ais;
            }
            
            var s = $("<span/>");
            
            _f_Srv_PA(is,s);
            
            /*
             * ETAPE : Les données 'Time'
             * En attendant la réponse du serveur, on lance l'opération d'affichage de la date de création de la Tendance.
             */
            _f_UpDt();
            
            $(s).on("datasready", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                Kxlib_DebugVars([JSON.stringify(d)],true);
                
                //On lance le processus d'affichage
                _f_Vw_Sh(d);
                
                _xhr_fa = null;
            });
            
            $(s).on("operended", function() {
                //On retire tous les Articles qui n'ont pas été chargés 
//            $(".jb-myts-m-md-spl-lst-item-mx").remove();
                
                _xhr_fa = null;
            });
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*
     * Met à jour les données de date pour les Tendances à partir de la donnée disponible
     */
    var _f_UpDt = function () {
        if ( !$(".jb-myts-mdl-mx").length | !$(".jb-myts-m-s-ifs-tm").length ) {
            return;
        }
        try {
            
            var l__ = $(".jb-myts-m-s-ifs-tm");
            $.each(l__, function(i,t) {
                if ( KgbLib_CheckNullity($(t).attr("time")) | !KgbLib_CheckNullity($(t).text()) ) {
//                    Kxlib_DebugVars([skip"]);
                    return true;
                }
//                Kxlib_DebugVars([WEIRD =>"+typeof $(t).text()]);
                var t__ = $(t).attr("time");
                
                //On crée une chaine pour la date
                var dt = new KxDate(parseInt(t__));
                dt.SetUTC(true);
                //On insere la date
                var nt__ = dt.WriteDate();
                //TODO : Créer une chaine qui donne la date et heure exacte
//                Kxlib_DebugVars([ypeof t__,t__,nt__]);
                $(t).text(nt__);
                
                //On retire 'void'
                $(t).removeClass("void");
            });
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

        
    };
    
    /*
     * PTFM (PullTrendFroM)
     * Permet de récupérer les Tendances selon une direction et une Tendance pivot.
     */
    var _f_PTFM = function (x,dr) {
        //dr (DiRection)
        try {
            if ( !$(".jb-mytrds-mx").length | KgbLib_CheckNullity(x) | !$(x).length | KgbLib_CheckNullity(dr) | $.inArray(dr,["b","t"]) === -1 ) {
                return;
            }
        
            //On vérifie qu'une opération n'est pas déjà lancée
            if (! KgbLib_CheckNullity(_xhr_ptfm) ) {
                return;
            }
            
            //On vérifie que le bouton est libre
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1); //[DEPUIS 13-07-15] @BOR
            
            //On vérifie qu'il y a au moins une Tendance disponible qui servira de pivot
            if (! $(".jb-myts-mdl-mx").length ) {
                return;
            } else {
                //[DEPUIS 13-07-15] @BOR
                $(".jb-nwfd-loadm-box.tmlnr").remove("this_hide");
            }
            
            //On lance le spinner
            _f_SwSpnr(true);
            
            //On récupère les données sur le dernier Article
            var mt = st = null;
            if ( !$(".jb-myts-mdl-mx:last").length ) {
                return;
            } else {
                //RAPEEL : tba (TrendBAtch)
                //*
                if ( $(".jb-myts-mdl-mx[data-tba='mtrs']:last").length ) {
                     mt = _f_GetLastByType("MTRS");
                }
                if ( $(".jb-myts-mdl-mx[data-tba='sbtrs']:last").length ) {
                    st = _f_GetLastByType("SBTRS");
                }
                //*/
                /*
                if ( $(".jb-myts-mdl-mx[data-tba='mtrs']:last").length ) {
                    mt = {
                        i : $(".jb-myts-mdl-mx[data-tba='mtrs']:last").data("item"),
                        t : $(".jb-myts-mdl-mx[data-tba='mtrs']:last").attr("time")
                    };
                } 
                if ( $(".jb-myts-mdl-mx[data-tba='sbtrs']:last").length ) {
                    st = {
                        i : $(".jb-myts-mdl-mx[data-tba='sbtrs']:last").data("item"),
                        t : $(".jb-myts-mdl-mx[data-tba='sbtrs']:last").attr("time")
                    };
                }
                //*/
            }
            
//            Kxlib_DebugVars([JSON.stringify(mt),JSON.stringify(st)],true);
            
            /*
             * ETAPE : 
             * On récupère les données sur les Tendances au niveau de la base de données.
             */
            var s = $("<span/>");
            
            //b = Bottom
            _f_Srv_PT_FM_WAIO(mt,st,4,dr,s);
                    
            $(s).on("datasready", function(e, d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                //On lance le processus d'affichage
//                Kxlib_DebugVars([JSON.stringify(d)],true); //DEV, TEST, DEBUG

                /*
                 * ETAPE : 
                 * On lance l'affichage des Tendances grace aux données obtenues et à la direction souhaitée.
                 */
                var t__ = _f_ShwTrds(d,dr);
                /*
                 * [DEPUIS 13-07-15] @BOR
                 */
                var ais = t__[1];
//                alert(JSON.stringify(ais));
       
                /*
                 * ETAPE :
                 * On lance le processus d'affichage des Articles FIRST
                 * [NOTE 13-07-15] @BOR
                 * L'une ou l'autre des deux instructions fonctionnera
                 */
                //On affiche les Articles FIRST
                _f_PA();
//                _f_PA(ais);
                
                //On unlock le bouton
                $(x).data("lk",0);
                //On libère la référence
                _xhr_ptfm = null;
                
                //On masque le spinner
                _f_SwSpnr();
            });
            
            $(s).on("operended", function() {
                //On retire tous les Articles qui n'ont pas été chargés 
//            $(".jb-myts-m-md-spl-lst-item-mx").remove();
                //On masque le spinner
                _f_SwSpnr();
                
                /*
                 * DEPUIS 13-07-15] @BOR
                 * On signale qu'on ne peut plus aller plus loin
                 */
                var m__ = Kxlib_getDolphinsValue("ART_NOONE_GEN_PAGE");
                $(".jb-tmlnr-loadm-trg").text(m__);
                $(".jb-tmlnr-loadm-trg").addClass("EOP");
                
                //On unlock le bouton
//                $(x).data("lk",0); //[DEPUIS 13-07-15] @BOR
                
                //On libère la référence
                _xhr_ptfm = null;
                
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_GetLastByType = function (scp) {
        try {
            if ( KgbLib_CheckNullity(scp) | $.inArray(scp,["MTRS","SBTRS"]) === -1 ) {
                return;
            }
            
            scp = scp.toUpperCase();
            var $trs;
            switch (scp) {
                case "MTRS" :
                        $trs = $(".jb-myts-mdl-mx[data-tba='mtrs']");
                    break;
                case "SBTRS" :
                        $trs = $(".jb-myts-mdl-mx[data-tba='sbtrs']");
                    break;
                default :
                    return;
            }
            
            if (! $($trs).length ) {
                return;
            } 
            
            var kv__ = [];
            $.each($trs,function(x,tr){
                kv__.push({
                    i : $(tr).data("item"),
                    t : $(tr).attr("time")
                });
            });
            
            /*
             * On trie par ordre décroissant
             */
            kv__.sort(function(a,b) {
                if ( parseFloat(a.t) < parseFloat(b.t) ) {
                  return 1;
                }
                if ( parseFloat(a.t) > parseFloat(b.t) ) {
                  return -1;
                }
                // a must be equal to b
                return 0;
            });
          
            /*
             * On récupère le dernier élément
             */
            return kv__.slice(-1)[0];
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** DATAS SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _Ax_PA = Kxlib_GetAjaxRules("TQR_ART_PULL");
    var _f_Srv_PA = function (is,s){
        //PA = PullArticles
        if ( KgbLib_CheckNullity(is) | KgbLib_CheckNullity(s) ) {
            _xhr_fa = null;
            return;
        }
                
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else {
                    _xhr_fa = null;
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY":
                                    Kxlib_AJAX_HandleDeny();
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    _xhr_fa = null;
                    return;
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    /*
                     * Données attendues :
                     *  (1) Les données sur les articles à proprement parlé
                     *  (2) La liste des relations liées aux Articles reçues
                     *      N.B : Il se peut que TOUS les Articles soient liées à une Tendance. 
                     *            Dans ce cas, la liste des relations ne sera pas disponible.
                     */
                    var rds = [datas.return];
                    $(s).trigger("datasready",rds);
                } else if ( KgbLib_CheckNullity(datas.return) ) {
                    var rds = [mb];
                    $(s).trigger("operended",rds);
                } else {
                    _xhr_fa = null;
                    return;
                }
                    
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars(["695",e],true);
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                _xhr_fa = null;
                return;
            }
        };

        var onerror = function (a,b,c) {
//            Kxlib_DebugVars([JSON.stringify(a),typeof a,b,c],true);
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
            Kxlib_AjaxGblOnErr(a,b);
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            _xhr_fa = null;
            return;
        };
        
        var curl = document.URL;
        var toSend = {
            "urqid": _Ax_PA.urqid,
            "datas": {
                "is"    : is,
                "cz"    : "TMLNR_TRD",
                "curl"  : curl
            }
        };

        _xhr_fa = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PA.url, wcrdtl : _Ax_PA.wcrdtl });
    };
    
    
    var _Ax_PTFM_WAIO = Kxlib_GetAjaxRules("TQR_PULL_TR_FM_WAIO");
    /*
     * Permet de récupérer une liste de Tendances depuis le serveur en prennant comme pivot les données (id,time) d'une Tendance.
     * La méthode peut aussi bien être appelée pour une opération de type "TOP" ou "BOT".
     * Tout dépend de la variable 'dir' passé en paramètre.
     * La méthode permet aussi de récupérer les identifiants des Articles FIRST dont la quantité maximale est passée.
     * Si aucune quantité est passée, on met la valeur 0 comme valeur par défaut.
     */
    var _f_Srv_PT_FM_WAIO = function (mt,st,c,dr,s) {
        //m = mtrs, s = sbtrs
        if ( KgbLib_CheckNullity(c) | KgbLib_CheckNullity(dr) | KgbLib_CheckNullity(s) ) {
            /*
             * RAPPEL : 
             * On ne retire pas le spinner car :
             *  (1) Il y a de grandes chances qu'il s'agisse d'une erreur provenant d'une modification de l'utilisateur
             *  (2) Pour forcer l'utilisateur a rechargé la page
             */
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else {
                    _xhr_ptfm = null;
                    _f_SwSpnr();
                    return;
                }
                
               if(! KgbLib_CheckNullity(datas.err) ) {
                    
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G" :
                            case "__ERR_VOL_U_GONE" :
                            case "__ERR_VOL_USER_GONE" :
                            case "__ERR_VOL_ACC_GONE" :
                            case "__ERR_VOL_CU_GONE" :
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_OWNER_GONE" :
                            case "__ERR_VOL_TRD_GONE" :
                                    location.reload();
                                break;
                            case "__ERR_VOL_DNY_AKX_AUTH":
                                    if ( $(".jb-tqr-btm-lock").length ) {
                                        $(".jb-tqr-btm-lock").removeClass("this_hide");
                                        $(".jb-tqr-btm-lock-fd").removeClass("this_hide");
                                    }
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                        }
                    } 
                    _xhr_ptfm = null;
                    //On masque le spinner
                    _f_SwSpnr();
                    return;
                    
                } else if (! KgbLib_CheckNullity(datas.return) ) {
                    var rds = [datas.return];
                    $(s).trigger("datasready",rds);
                } else if ( KgbLib_CheckNullity(datas.return) ) {
                    $(s).trigger("operended");
                } else {
                    _xhr_ptfm = null;
                    //On masque le spinner
                    _f_SwSpnr();
                    return;
                }
            } catch (ex) {
//                alert("DEBUG AJAX => "+e.message);
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                _xhr_ptfm = null;
                //On masque le spinner
                _f_SwSpnr();
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
            
            _xhr_ptfm = null;
            //On masque le spinner
            _f_SwSpnr();
            
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        var cu = document.URL;
        var toSend = {
            "urqid": _Ax_PTFM_WAIO.urqid,
            "datas": {
                "mtrs"  : mt || null,
                "sbtrs" : st || null,
                "c"     : ( typeof c === "number" && c > 0 ) ? c : 0,
                "dr"    : dr,
                "curl"  : cu
            }
        };

        _xhr_ptfm = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PTFM_WAIO.url, wcrdtl : _Ax_PTFM_WAIO.wcrdtl });
    };
    
    
    /*******************************************************************************************************************************************************************/
    /**************************************************************************** VIEW SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    /*
     * Permet d'afficher le spinner.
     * Dans le cas où CALLER veut faire afficher le spinner, cela entraine qu'on masque le texte.
     */ 
    var _f_SwSpnr = function (sw) {
        if ( KgbLib_CheckNullity(sw) ) {
            $(".jb-tmlnr-loadm-trg").removeClass("this_hide");
            $(".jb-nwfd-loadm-spnr").addClass("this_hide");
        } else {
            $(".jb-tmlnr-loadm-trg").addClass("this_hide");
            $(".jb-nwfd-loadm-spnr").removeClass("this_hide");
        }
    };
     
    var _f_Vw_Sh = function (d) {
        try {
            if ( KgbLib_CheckNullity(d) ) {
                return;
            }
            
            $.each(d, function(x,et) {
                //ET = ElementTab
                //On verifie que l'élément n'a pas déjà été chargé
//                Kxlib_DebugVars(["POUR => ",et.id,$(".jb-myts-m-md-spl-lst-item-mx[data-item="+et.id+"]").length,$(".jb-myts-m-md-spl-lst-item-mx[data-item="+ et.id+"]").find(".jb-myts-mdl-i-smpl-img").length]);
                if ( !$(".jb-myts-m-md-spl-lst-item-mx[data-item="+et.id+"]").length | $(".jb-myts-m-md-spl-lst-item-mx[data-item="+et.id+"]").find(".jb-myts-mdl-i-smpl-img").length ) {
                    return true;
                }
                
                _f_Vw_Ppr($(".jb-myts-m-md-spl-lst-item-mx[data-item="+et.id+"]"), et);
                
            });
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
        
    };
    
    var _f_Vw_Ppr = function (eb,d) {
        try {
            //eb = ElementBloc
            /*
             * Permet d'insérer les données au niveau de l'Article.
             * L'insertion consiste à mettre en place l'image ET les données dans le DataCache qui permettront au module UNIQUE de faire apparaitre l'image.
             */

            if ( KgbLib_CheckNullity(eb) | KgbLib_CheckNullity(d) ) {
                return;
            }

            var im = $("<img/>").attr({
                class : "myts-m-md-spl-lst-item-i jb-myts-mdl-i-smpl-img",
                height: 103,
                src   : d.img
            });
            /*
             * [DEPUIS 06-11-15] @author BOR
             */
            $(eb).find(".jb-myts-mdl-i-hrf").attr({
                "href" : "//"+d.prmlk
    //            "target" : "_blank"
            });

            $(eb).find(".jb-myts-mdl-i-spnr").remove();
            $(im).hide().appendTo($(eb).find(".jb-myts-mdl-i-hrf")).fadeIn();

            //TODO : Mettre en place les données pour DataCache
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ShwTrds = function (ds,dr) {
        try {
            if ( KgbLib_CheckNullity(ds) | KgbLib_CheckNullity(dr) ) {
                return;
            }
            
            var ids = [], ais = [];
            $.each(ds,function(x,d) {
                //On vérifie que l'élément n'existe pas déjà
                if ( $(".jb-myts-mdl-mx[data-item='"+d.trd_eid+"']").length) {
                    return true;
                }
                
                //On prépare le DOM
                var e = _f_PprTrd(d);
               
                //On ajoute le modèle dans le bloc
                var tba = d.tba.toLowerCase();
                var $ref;
                switch (tba){
                    case "mtrs" :
                            if ( $(".jb-myts-mdl-mx[data-tba='mtrs']:last").length ) {
                                $ref = $(".jb-myts-mdl-mx[data-tba='mtrs']:last");
                            } 
                        break;
                    case "sbtrs" :
                            if ( $(".jb-myts-mdl-mx[data-tba='sbtrs']:last").length ) {
                                $ref = $(".jb-myts-mdl-mx[data-tba='sbtrs']:last");
                            } 
                        break;
                }
                if ( dr.toLowerCase() === "b" ) {
                    if ( $ref ) {
                        $(e).hide().insertAfter($ref).fadeIn();
                    } else {
                        $(e).hide().appendTo(".jb-mytrds-body").fadeIn();
                    }
                } else {
                    if ( $ref ) {
                        $(e).hide().insertBefore($ref).fadeIn();
                    } else {
                        $(e).hide().appendTo(".jb-mytrds-body").fadeIn();
                    }
                } 
                
                ids.push(d.trd_eid);
                /*
                 * [DEPUIS 13-07-15] @BOR
                 */
//                Kxlib_DebugVars([ypeof d.trd_fartis,d.trd_fartis]);
                if (! KgbLib_CheckNullity(d.trd_fartis) ) {
                    var t__ = d.trd_fartis.split(',');
                    $.each(t__,function(x,ai) {
                        ais.push(ai);
                    });
                }
                
            });
            
            return [ids,ais];
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_PprTrd = function (d) {
        if ( KgbLib_CheckNullity(d) ) {
            return;
        }
        
        /*
         * TABLE des IDS :
         * 
         * "trd_eid"       : L'identifiant externe de l'identifiant,
         * "trd_tle"       : Titre de la Tendance,
         * "trd_desc"      : Description de la Tendance,
         * "trd_href"      : Lien menant vers la page dédiée de la Tendance,
         * "trd_posts_nb"  : Le nombre de publications de la Tendance,
         * "trd_abos_nb"   : Le nombre d'abonnement de la Tendance,
         * "trd_time"      : La date de création de la Tendance,
         * "tba"           : TrendBAtch, La tendance appartient à OWNER ou il la suit
         * //COVER DATAS 
         * "trd_cov_w"     : La largeur de la couverture,
         * "trd_cov_h"     : La heuteur de la couverture,
         * "trd_cov_t"     : La donnée top de la couverture,
         * "trd_cov_rp"    : Le chemin de l'image de couverture,
         * // OWNER DATAS 
         * "trd_oid"       : L'identifiant du propriétaire de la Tendance,
         * "trd_ofn"       : Le Nom Complet du propriétaire de la Tendance,
         * "trd_opsd"      : Le nom d'utilisateur du propriétaire de la Tendance,
         * "trd_ohref"     : Le lien vers la page du propriétaire de la Tendance,
         * "trd_oppic"     : Le liende la photo de profil du propriétaire de la Tendance,
         * "trd_octrib"    : Le nombre d'Article du propriétaire pour la Tendance,
         * //FIRST ARTICLES IDS 
         * "trd_fartis"    : Les identifiants des Articles FIRST pour la Tendance. On s'en sert pour en récupérer les images en mode asynchrone
         */
        try {
            
            //On crée une chaine pour la date
            var dt = new KxDate(parseInt(d.trd_time));
            dt.SetUTC(true);
            //On insere la date
            var strtm = dt.WriteDate();
            //TODO : Créer une chaine qui donne la date et heure exacte
            
            var e = "<div class='myts-mdl-mx jb-myts-mdl-mx' data-item='" + d.trd_eid + "' data-tba='"+d.tba+"' time='"+d.trd_time+"'>";
            e += "<div class=\"myts-mdl-purpz jb-myts-mdl-purpz\">";
            e += "<a class=\"myts-mdl-purpz-u jb-myts-mdl-purpz-u\" href=\"\"></a>";
            e += "<span class=\"myts-mdl-purpz-tx jb-myts-mdl-purpz-tx\" ></span>"
            e += "</div>";
            e += "<div class='myts-mdl-specs'>";
            e += "<div class=''>";
            e += "<div>";
            e += "<div class='myts-m-s-ubox'>";
            e += "<a class='myts-m-s-ubx-grp' href='" + d.trd_ohref + "'>";
            e += "<span class=\"myts-m-s-ubx-uppic-mx\">";
            e += "<span class=\"myts-m-s-ubx-uppic-fade\"></span>";
            e += "<img class='myts-m-s-ubx-uppic' src='" + d.trd_oppic + "' height='50' />";
            e += "</span>";
            e += "<span class='myts-m-s-ubx-upsd'>@" + d.trd_opsd + "</span>";
            e += "</a>";
            e += "</div>";
            e += "<div class='myts-m-s-ifs clearfix'>";
            e += "<span>Depuis&nbsp;</span>";
            e += "<span class='myts-m-s-ifs-tm jb-myts-m-s-ifs-tm' time='" + d.trd_time + "'>"+strtm+"</span>";
            e += "</div>";
            e += "</div>";
            e += "<div class='myts-m-s-mtrx'>";
            e += "<ul class='myts-m-s-mtrx-lst-mx'>";
            e += "<li class='myts-m-s-mtrx-lst-ln' level='1'>";
            e += "<div class='myts-m-s-m-l-ln-sbwrp'>";
            e += "<div class='myts-m-s-m-l-ln-nb' level='1'>" + d.trd_posts_nb + "</div>";
            e += "<div class='myts-m-s-m-l-ln-libwrp' level='_l1_1'>";
            e += "<span class='myts-m-s-m-l-ln-libt'>Contributions</span><br/>";
            e += "</div>";
            e += "</div>";
            e += "</li>";
            if ( d.hasOwnProperty("trd_poctrib") && !KgbLib_CheckNullity(d.trd_poctrib) ) {
                e += "<li class='myts-m-s-mtrx-lst-ln' level='2'>";
                e += "<div class='myts-m-s-m-l-ln-sbwrp'>";
                e += "<div class='myts-m-s-m-l-ln-nb' level='2'>"+d.trd_poctrib+"</div>";
                e += "<div class='myts-m-s-m-l-ln-libwrp' level='_l2_1'>";
                e += "<span class='myts-m-s-m-l-ln-lib'>De @"+d.trd_popsd+"</span>";
                e += "</div>";
                e += "</div>";
                e += "</li>";
            }
            e += "<li class='myts-m-s-mtrx-lst-ln' level='1'>";
            e += "<div class='myts-m-s-m-l-ln-sbwrp'>";
            e += "<div class='myts-m-s-m-l-ln-nb' level='1'>" + d.trd_abos_nb + "</div>";
            e += "<div class='myts-m-s-m-l-ln-libwrp' level='_l1_1'>";
            e += "<span class='myts-m-s-m-l-ln-libt'>Abonnés</span>";
            e += "</div>";
            e += "</div>";
            e += "</li>";
            e += "</ul>";
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e += "<div class='myts-mdl-media'>";
            e += "<div class='myts-m-md-cvr-mx'>";
            e += "<div class='myts-m-md-cvr-tle'>";
            e += "<a class='myts-m-md-cvr-tle-hrf jb-myts-m-md-cvr-tle-hrf' href='" + d.trd_href + "'></a>";
            e += "</div>";
            e += "<div class='myts-m-md-cvr-cvr'>";
            if ( d.hasOwnProperty("trd_cov_rp") && !KgbLib_CheckNullity(d.trd_cov_rp) ) {
                //On calucule la donnée 'top' par équivalence de taille et de largeur
                var x__ = d.trd_cov_t.length - 2;
                var t__ = parseInt(d.trd_cov_t.substr(0,x__));
//                var nt__ = parseInt((532/840)*t__)+3;
                var nt__ = parseInt((532/840)*t__)-2;
//                Kxlib_DebugVars([d.trd_cov_t,t__,nt__]);
                e += "<a class='myts-m-md-cvr-cvr-hrf' href='"+d.trd_href+"' title='" + Kxlib_Decode_After_Encode(d.trd_tle) + "'>";
                    e += "<img class='myts-m-md-cvr-cvr-img' width='532px' style='top:"+nt__+"px' src='"+d.trd_cov_rp+"'/>";
                    e += "<span class='myts-m-md-cvr-cvr-fade cover'></span>";
                e += "</a>";
            } else {
                e += "<a class='myts-m-md-cvr-cvr-hrf' href='"+d.trd_href+"'>";
                    e += "<span class='myts-m-md-cvr-cvr-noone cover'><img src='"+Kxlib_GetExtFileURL("sys_url_img","r/3pt-w.png")+"' /></span>";
//                    e += "<span class='myts-m-md-cvr-cvr-noone cover'><img src='http://timg.ycgkit.com/files/img/r/3pt-w.png' /></span>";
                e += "</a>";
            }
            e += "</div>";
            e += "<div class='myts-m-md-cvr-desc'>";
            e += "<a class='myts-m-md-cvr-desc-hrf' href='" + d.trd_href + "'></a>";
            e += "</div>";
            e += "</div>";
            e += "<div class='myts-m-md-sample-mx'>";
            if ( d.hasOwnProperty("trd_fartis") && !KgbLib_CheckNullity(d.trd_fartis)) {
                var l__ = d.trd_fartis.split(","); 
                e += "<ul class='myts-m-md-spl-lst'>";
                $.each(l__, function(i,v) {
                    e += "<li class='myts-m-md-spl-lst-item-sprt'>";
                    e += "<div class='myts-m-md-spl-lst-item-mx jb-myts-m-md-spl-lst-item-mx' data-item='" + v + "' >";
                    e += "<a class='myts-m-md-spl-lst-item-hwrap jb-myts-mdl-i-hrf'>";
                    e += "<img class='jb-myts-mdl-i-spnr' src='"+Kxlib_GetExtFileURL("sys_url_img","r/ld_16.gif")+"' height='10' width='10'/>";
//                    e += "<img class='jb-myts-mdl-i-spnr' src='http://timg.ycgkit.com/files/img/r/ld_16.gif' height='10' width='10'/>";
                    e += "<span class='myts-m-md-cvr-cvr-fade sample'></span>";
                    e += "</a>";
                    e += "</div>";
                    e += "</li>";
                });
                e += "</ul>";
            } else {
                e += "<span class='myts-m-s-m-noone'>Aucune publication<br/>pour le moment</span>";
            }
            e += "</div>";
            e += "</div>";
            e += "</div>";
            e = $.parseHTML(e);
            
            var nt = Kxlib_Decode_After_Encode(d.trd_tle);
            $(e).find(".jb-myts-m-md-cvr-tle-hrf").attr("title",nt);
            $(e).find(".jb-myts-m-md-cvr-tle-hrf").text(nt);
            
            var nd = Kxlib_Decode_After_Encode(d.trd_desc);
            $(e).find(".myts-m-md-cvr-desc-hrf").text(nd);
            
            /*
             * [DEPUIS 24-06-16]
             * ETAPE :
             *      On insert le motif de la présence de la Tendance dans la  liste.
             */
            if ( d.tba === "sbtrs" ) {
                $(e).find(".jb-myts-mdl-purpz-u").attr("href","/".concat(d.trd_popsd)).text("@".concat(d.trd_popsd));
                
                var ppz_m = "est à abonné à ce Salon";
                $(e).find(".jb-myts-mdl-purpz-tx").text(ppz_m);
            } else {
                $(e).find(".jb-myts-mdl-purpz-u").remove();
            }
            
            return e;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************* LISTENERS SCOPE *************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    //Load manuel
    $(".jb-tmlnr-loadm-trg.tmlnr[data-scp='tr']").click(function(e){
        Kxlib_PreventDefault(e);
            
        _f_PTFM(this,'b');
    });
    
})();

(function(){
    
    var _xhr_pfafm;
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************* PROCESS SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    var _f_Action = function(x,a){
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && !a ) ) {
                return;
            }
            
            var _a = ( KgbLib_CheckNullity($(x).data("action")) ) ? a : $(x).data("action"); 
            switch (_a) {
                case "wild" :
                        _f_Wild(x);
                    break;
                default: 
                    return;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_HvrCz = function(x,ih){
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            var bx = $(x).closest(".jb-tmlnr-pgfv-art-bmx");
            
            if ( ih ) {
                $(bx).find(".jb-tmlnr-pgfv-art-i-fd").addClass("hover");
                $(bx).find(".jb-tmlnr-pgfv-art-i-txt").removeClass("this_hide");
                $(bx).stop(true,true).animate({
                    top : "-5px"
                });
            } else {
                $(bx).find(".jb-tmlnr-pgfv-art-i-fd").removeClass("hover");
                $(bx).find(".jb-tmlnr-pgfv-art-i-txt").addClass("this_hide");
                $(bx).stop(true,true).animate({
                    top : "0px"
                });
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Wild = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if (! $(".jb-tmlnr-pgfv-scrn").hasClass("iwild") ) {
                
                $("html").css({
                    overflow: "hidden"
                });
                
                $(".jb-tmlnr-pgfv-art-bmx").stop(true,true).fadeOut(400).promise().done(function(){
                    $(".jb-tmlnr-pgfv-scrn").stop(true,true).addClass("wild");
                    
                    $(".jb-tmlnr-pgfv-scrn")
//                    .css({
//                        background : "#262727",
//                        position : "fixed",
//                        "z-index" : "1101"
//                    })
                    .stop(true,true)
                    .animate({
                        margin      : "0",
                        top         : "0",
                        left        : "0"
                    },function(){
                        $(".jb-tmlnr-pgfv-scrn").stop(true,true).animate({
                            width       : "100%",
                            height      : $(window).height()
                        });
                        
                        $(".jb-tmlnr-pgfv-art-bmx").fadeIn();
                        $(".jb-tmlnr-pgfv-scrn").addClass("iwild");
                        $(x).addClass("close").css({
                            position : "fixed",
                            right : "30px"
                        });
                        $(".jb-tmlnr-pgfv-scrn").stop(true,true).css({
                            "overflow-y": scroll
                        });
                    });
                });
            } else {
                $(".jb-tmlnr-pgfv-art-bmx").stop(true,true).fadeOut(400,function(){
                    $(".jb-tmlnr-pgfv-scrn").stop(true,true).removeClass("wild");
                
                    $(".jb-tmlnr-pgfv-scrn").stop(true,true).removeAttr("style");

                    $(".jb-tmlnr-pgfv-art-bmx").fadeIn();

                    $(".jb-tmlnr-pgfv-scrn").removeClass("iwild");
                    $(x).removeClass("close").removeAttr("style");
                    
                    $("html").css({
                        overflow: "auto"
                    });
                });
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_LdMr = function(x,dr) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(dr) ) {
                return;
            }
            
            //On vérifie qu'une opération n'est pas déjà lancée
            if (! KgbLib_CheckNullity(_xhr_pfafm) ) {
                return;
            }
            
            //On vérifie que le bouton est libre
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1); 
            
            //On vérifie qu'il y a au moins un ARTICLE disponible qui servira de pivot
            if (! $(".jb-tmlnr-pgfv-art-bmx").length ) {
                return;
            } else {
                //[DEPUIS 13-07-15] @BOR
                $(".jb-nwfd-loadm-box.tmlnr").remove("this_hide");
            }
                    
            //On lance le spinner
            _f_SwSpnr(true);
            
            //On récupère les données sur le dernier Article
            var pds;
            if ( !$(".jb-tmlnr-pgfv-art-bmx:last").length ) {
                return;
            } else {
                pds = {
                    "pvi" : $(".jb-tmlnr-pgfv-art-bmx:last").data("item"),
                    "pvt" : $(".jb-tmlnr-pgfv-art-bmx:last").data("fvtm")
                };
            }
            
//            Kxlib_DebugVars([JSON.stringify(pds)],true);
//            return;
            
            /*
             * ETAPE : 
             * On récupère les données sur les Tendances au niveau de la base de données.
             */
            var s = $("<span/>");
            
            _f_Srv_LdMr(pds.pvi,pds.pvt,dr,x,s);
                    
            $(s).on("datasready", function(e, d) {
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                //On lance le processus d'affichage
//                Kxlib_DebugVars([JSON.stringify(d)],true); //DEV, TEST, DEBUG
//                return; 
                
                /*
                 * ETAPE : 
                 * On lance l'affichage des Tendances grace aux données obtenues et à la direction souhaitée.
                 */
                _f_ShwArts(d.ds,dr);
                
                //On unlock le bouton
                $(x).data("lk",0);
                //On libère la référence
                _xhr_pfafm = null;
                
                //On masque le spinner
                _f_SwSpnr();
            });
            
            $(s).on("operended", function() {
                //On masque le spinner
                _f_SwSpnr();
                
                /*
                 * DEPUIS 13-07-15] @BOR
                 * On signale qu'on ne peut plus aller plus loin
                 */
                var m__ = Kxlib_getDolphinsValue("ART_NOONE_GEN_PAGE");
                $(".jb-tmlnr-loadm-trg").text(m__);
                $(".jb-tmlnr-loadm-trg").addClass("EOP");
                
                $(x).data("lk",0);
                //On libère la référence
                _xhr_pfafm = null;
            });
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_ShwArts = function (ds,dr) {
        try {
            if ( KgbLib_CheckNullity(ds) ) {
                return;
            }
            
            if (! $.inArray(dr,["FST,TOP","BTM"]) ) {
                return;
            }
            
            $.each(ds,function(x,atb){
                if ( $(".jb-tmlnr-pgfv-art-bmx[data-item='"+atb.id+"']").length ) {
                    return true;
                }
                
                var am = _f_Vw_BldArt(atb);
//                Kxlib_DebugVars([am],true);
                
                $(am).hide().appendTo(".jb-mlnr-pgfv-art-list-bmx").fadeIn();
            });
            
            
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
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** SERVER SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _Ax_LdMr = Kxlib_GetAjaxRules("TQR_ART_LDMR");
    var _f_Srv_LdMr = function (i,t,dr,x,s) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(dr) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
            /*
             * RAPPEL : 
             * On ne retire pas le spinner car :
             *  (1) Il y a de grandes chances qu'il s'agisse d'une erreur provenant d'une modification de l'utilisateur
             *  (2) Pour forcer l'utilisateur a rechargé la page
             */
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else {
                    _xhr_pfafm = null;
                    _f_SwSpnr();
                    return;
                }
                
               if(! KgbLib_CheckNullity(datas.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G" :
                            case "__ERR_VOL_U_GONE" :
                            case "__ERR_VOL_USER_GONE" :
                            case "__ERR_VOL_ACC_GONE" :
                            case "__ERR_VOL_CU_GONE" :
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_OWNER_GONE" :
                                    location.reload();
                                break;
                            case "__ERR_VOL_DNY_AKX_AUTH":
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
                                    Kxlib_AJAX_HandleFailed();
                                return;
                        }
                    } 
                    _xhr_pfafm = null;
                    //On masque le spinner
                    _f_SwSpnr();
                    $(x).data("lk",0);
                    return;
                } else if ( ( !KgbLib_CheckNullity(datas.return) && datas.return.ds ) ) {
                    var rds = [datas.return];
                    $(s).trigger("datasready",rds);
                } else if ( KgbLib_CheckNullity(datas.return) ) {
                    $(s).trigger("operended");
                } else {
                    _xhr_pfafm = null;
                    //On masque le spinner
                    _f_SwSpnr();
                    $(x).data("lk",0);
                    return;
                }
            } catch (ex) {
//                alert("DEBUG AJAX => "+e.message);
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                _xhr_pfafm = null;
                //On masque le spinner
                _f_SwSpnr();
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
            
            _xhr_pfafm = null;
            //On masque le spinner
            _f_SwSpnr();
            
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        var cu = document.URL;
        var toSend = {
            "urqid": _Ax_LdMr.urqid,
            "datas": {
                "i"   : i,
                "t"   : t,
                "dr"  : dr,
                "cu"  : cu
            }
        };

        _xhr_pfafm = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_LdMr.url, wcrdtl : _Ax_LdMr.wcrdtl });
    };
    
    /*******************************************************************************************************************************************************************/
    /**************************************************************************** VIEW SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_SwSpnr = function (sw) {
        try {
            if ( KgbLib_CheckNullity(sw) ) {
                $(".jb-tmlnr-loadm-trg").removeClass("this_hide");
                $(".jb-nwfd-loadm-spnr").addClass("this_hide");
            } else {
                $(".jb-tmlnr-loadm-trg").addClass("this_hide");
                $(".jb-nwfd-loadm-spnr").removeClass("this_hide");
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Vw_BldArt  = function (ds) {
        try {
            if ( KgbLib_CheckNullity(ds) ) {
                return;
            }
               
            var str__, fldesc, shdesc;
            if ( ds.hasOwnProperty("ustgs") && !KgbLib_CheckNullity(ds.ustgs) && typeof ds.ustgs === "object" ) {
                var istgs__ = [];
                $.each(ds.ustgs, function(x,v) {
                    var rw__ = [];
                    $.map(v, function(e,x) {
                        rw__.push(e);
                    });
                    istgs__.push(rw__.join("','"));
                });
                
                str__ = ( istgs__.length > 1 ) ? istgs__.join("'],['") : istgs__[0];
                str__ = "['" + str__ + "']";
            } 
            var m = Kxlib_Decode_After_Encode(ds.msg); 
            
            shdesc = ( m.length > 150 ) ? m.substr(0,150).concat(" ...") : m;
            shdesc = Kxlib_Decode_After_Encode(shdesc);
            
            fldesc = Kxlib_Decode_After_Encode(ds.msg);
            
            var am = "<article id=\"\" class=\"tmlnr-pgfv-art-bmx jb-tmlnr-pgfv-art-bmx jb-unq-bind-art-mdl\" data-trq-ver=\"ajca-v10\" ";
            am += "data-item=\"\" data-time=\"\" data-atype=\"fav\" data-with=\"\" data-istr=\"\" data-trds=\"\" data-fvtp=\"\" data-trq-ver='ajca-v10' ";
            am += " data-ajcache=\"\" ";
            am += ">";
            am += "<header>";
            am += "<span class=\'css-tgpsy kxlib_tgspy fav\' ";
            am += "data-tgs-crd=\'\' data-tgs-dd-atn=\'\' data-tgs-dd-uut=\'\'";
            am += "title=\"Date à laquelle la publication a été mise en favori\"";
            am += ">";
            am += "<span class=\'tgs-frm\'></span>";
            am += "<span class=\'tgs-val\'></span>";
            am += "<span class=\'tgs-uni\'></span>";
            am += "</span>";
            am += "</header>";
            am += "<div>";
            am += "<a class=\"tmlnr-pgfv-art-i-bmx jb-tmlnr-pgfv-art-i-bmx\">";
            if ( ds.fvtp === "ART_XA_FAV_PRI" ) {
                am += "<span class=\"tmlnr-pgfv-art-lck jb-tmlnr-pgfv-art-lck\"></span>";
            }
            am += "<span class=\"tmlnr-pgfv-art-i-fd jb-tmlnr-pgfv-art-i-fd\"></span>";
            am += "<img class=\"tmlnr-pgfv-art-i jb-tmlnr-pgfv-art-i\" width=\"240\" height=\"240\" src=\"\" alt=\"\" />";
            am += "<span class=\"tmlnr-pgfv-art-i-txt jb-tmlnr-pgfv-art-i-txt this_hide\">";
            am += "<span class=\"psd\"></span><br/>";
            am += "<span class=\"desc\" data-dsc=\"\"></span>";
            am += "</span>";
            am += "</a>";
            am += "</div>";
            am += "</article>";
            am = $.parseHTML(am);
            
            /*
             * ETAPE :
             *      On FILL les données
             */
            $(am)
                .attr("id","post-fv-aid-".concat(ds.id))
                .data("item",ds.id).attr("data-item",ds.id)
                .data("time",ds.time).attr("data-time",ds.time)
                .data("with",str__)
                .data("istr",ds.istrd).attr("data-istr",ds.istrd)
                .data("vidu",ds.vidu).attr("data-vidu",ds.vidu)
                .data("hasfv",ds.hasfv).attr("data-hasfv",ds.hasfv)
                .data("fvtp",ds.fvtp).attr("data-fvtp",ds.fvtp)
                .data("fvtm",ds.fvtp).attr("data-fvtm",ds.fvtm);
        
            if ( ds.hasOwnProperty("vidu") && ds.vidu ) {
                $(am).find(".jb-tmlnr-pgfv-art-i-fd").addClass("vidu");
            }
        
            if ( ds.hasOwnProperty("trds") && ds.trds ) {
                ds.trds = JSON.parse(Kxlib_Decode_After_Encode(ds.trds));
                $(am).data("trds",ds.trds).attr("data-trds",ds.trds);
            }
            
            $(am).data("ajcache",JSON.stringify(ds)).attr("data-ajcache",JSON.stringify(ds));
            
            $(am).find(".kxlib_tgspy").data("tgs-crd",ds.fvtm);
            
            $(am).find(".jb-tmlnr-pgfv-art-i").data("tgs-crd",ds.img);
            
            $(am).find(".jb-tmlnr-pgfv-art-i").attr("src",ds.img).attr("alt",Kxlib_Decode_After_Encode(fldesc));
            
            $(am).find(".jb-tmlnr-pgfv-art-i-txt .psd").text("@".concat(ds.upsd));
            
            $(am).find(".jb-tmlnr-pgfv-art-i-txt .desc").data("dsc",fldesc).attr("data-dsc",fldesc).text(shdesc);
            
            /*
             * ETAPE :
             *      On effectue un REBIND
             */
            $(am).find(".jb-tmlnr-pgfv-art-i-bmx").hover(function(){
                _f_HvrCz(this,true);
            },function(){
                _f_HvrCz(this);
            });

            $(am).find(".jb-tmlnr-pgfv-hdr-ax-tgr").click(function(e){
                Kxlib_PreventDefault(e);
                _f_Action(this);
            });
            $(am).find(".jb-tmlnr-pgfv-art-i-bmx").off("click").click(function(e){
                Kxlib_PreventDefault(e);
                (new Unique ()).OnOpen("fav",this);
            });
            
            return am;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************* LISTENERS SCOPE *************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    $(".jb-tmlnr-pgfv-art-i-bmx").hover(function(){
        _f_HvrCz(this,true);
    },function(){
        _f_HvrCz(this);
    });
    
    $(".jb-tmlnr-pgfv-hdr-ax-tgr").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
    //Load manuel
    $(".jb-tmlnr-loadm-trg.tmlnr[data-scp='fv']").click(function(e){
        Kxlib_PreventDefault(e);
            
        _f_LdMr(this,"btm");
    });
    
    /*
     * [DEPUIS 12-06-16] @author BOR
     */
    $(".jb-tqr-artmdl-shron-tgr").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        _f_Sharon(this);
    });
    
})();

(function(){
    
    var _xhr_ldds;
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************* PROCESS SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    var _f_Action = function(x,a){
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && !a ) ) {
                return;
            }
            
            var _a = ( KgbLib_CheckNullity($(x).data("action")) ) ? a : $(x).data("action"); 
            switch (_a) {
                case "sec-edit" :
                        _f_SecEdit(x);
                    break;
                case "sec-val" :
                        _f_SecVal(x);
                    break;
                case "sec-ccl" :
                        _f_SecCcl(x);
                    break;
                default: 
                    return;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_SecEdit = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("sec")) ) {
                return;
            }
            
            var sec = $(x).data("sec"), sec_dom = $(".jb-pgabme-sbsec-mx[data-sec='"+sec+"']");
            if (! $(sec_dom).length ) {
                return;
            }
            
            if ( $(sec_dom).find(".jb-pgabme-sbs-b-shw-mx").hasClass("this_hide") ) {
                $(sec_dom).find(".jb-pgabme-sbs-b-wrk-mx").addClass("this_hide");
                $(sec_dom).find(".jb-pgabme-sbs-b-shw-mx").removeClass("this_hide");
                return;
            }
            
            switch (sec) {
                case "abme-intro" :
                        var txt = $(".jb-pgabme-sbs-abme-rslt").text();
                        $(".jb-pgabme-sbs-abme-int-ipt").val(txt);
                        
                        $(sec_dom).find(".jb-pgabme-sbs-b-shw-mx").addClass("this_hide");
                        $(sec_dom).find(".jb-pgabme-sbs-b-wrk-mx").removeClass("this_hide");
                    break;
                case "love-snippets" :
                        $(sec_dom).find(".jb-pgabme-sbs-b-shw-mx").addClass("this_hide");
                        $(sec_dom).find(".jb-pgabme-sbs-b-wrk-mx").removeClass("this_hide");
                    break;
                case "why-me" :
                        $(sec_dom).find(".jb-pgabme-sbs-b-shw-mx").addClass("this_hide");
                        $(sec_dom).find(".jb-pgabme-sbs-b-wrk-mx").removeClass("this_hide");
                    break;
                case "i-master" :
                        $(sec_dom).find(".jb-pgabme-sbs-b-shw-mx").addClass("this_hide");
                        $(sec_dom).find(".jb-pgabme-sbs-b-wrk-mx").removeClass("this_hide");
                    break;
                default:
                    return;
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    }
    
    var _f_SecCcl = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            var sec = $(x).closest(".jb-pgabme-sbsec-mx").data("sec");
            if ( KgbLib_CheckNullity(sec) ) {
                return;
            }
            var $sec = $(".jb-pgabme-sbsec-mx[data-sec='"+sec+"']");
            if (! ( $sec && $sec.length ) ) {
                return;
            }
            
            switch (sec) {
                case "abme-intro" :
                        $sec.find(".jb-pgabme-sbs-b-shw-mx").removeClass("this_hide");
                        $sec.find(".jb-pgabme-sbs-b-wrk-mx").addClass("this_hide");
                    break;
                case "love-snippets" :
                        $sec.find(".jb-pgabme-sbs-b-shw-mx").removeClass("this_hide");
                        $sec.find(".jb-pgabme-sbs-b-wrk-mx").addClass("this_hide");
                    break;
                case "why-me" :
                        $sec.find(".jb-pgabme-sbs-b-shw-mx").removeClass("this_hide");
                        $sec.find(".jb-pgabme-sbs-b-wrk-mx").addClass("this_hide");
                    break;
                case "i-master" :
                        $sec.find(".jb-pgabme-sbs-b-shw-mx").removeClass("this_hide");
                        $sec.find(".jb-pgabme-sbs-b-wrk-mx").addClass("this_hide");
                    break;
                default:
                    return;
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    }
    
    var _f_SecVal = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).data("sec")) ) {
                return;
            }
            
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            var sec = $(x).closest(".jb-pgabme-sbsec-mx").data("sec");
            if ( KgbLib_CheckNullity(sec) ) {
                return;
            }
            var $sec = $(".jb-pgabme-sbsec-mx[data-sec='"+sec+"']");
            if (! ( $sec && $sec.length ) ) {
                return;
            }
            
            switch (sec) {
                case "abme-intro" :
                        $sec.find(".jb-pgabme-sbs-b-shw-mx").removeClass("this_hide");
                        $sec.find(".jb-pgabme-sbs-b-wrk-mx").addClass("this_hide");
                    break;
                case "love-snippets" :
                        $sec.find(".jb-pgabme-sbs-b-shw-mx").removeClass("this_hide");
                        $sec.find(".jb-pgabme-sbs-b-wrk-mx").addClass("this_hide");
                    break;
                case "why-me" :
                        $sec.find(".jb-pgabme-sbs-b-shw-mx").removeClass("this_hide");
                        $sec.find(".jb-pgabme-sbs-b-wrk-mx").addClass("this_hide");
                    break;
                case "i-master" :
                        $sec.find(".jb-pgabme-sbs-b-shw-mx").removeClass("this_hide");
                        $sec.find(".jb-pgabme-sbs-b-wrk-mx").addClass("this_hide");
                    break;
                default:
                    return;
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    }
    
    var _f_GtDs_Auto = function () {
        try {
            
            var s = $("<span/>");
            
            _Srv_LdDs("all",true,s);
            
            $(s).on("datasready",function(e,ds){
                if ( KgbLib_CheckNullity(ds) ) {
                    return;
                }
                
                if (! KgbLib_CheckNullity(ds.SEC_INTRO) ) {
                    _f_StDs("SEC_INTRO",ds.SEC_INTRO);
                }
                if (! KgbLib_CheckNullity(ds.SEC_IMAS) ) {
                    _f_StDs("SEC_IMAS",ds.SEC_IMAS);
                }
                if (! KgbLib_CheckNullity(ds.SEC_LVSP) ) {
                    _f_StDs("SEC_LVSP",ds.SEC_LVSP);
                }
                if (! KgbLib_CheckNullity(ds.SEC_WHYME) ) {
                    _f_StDs("SEC_WHYME",ds.SEC_WHYME);
                }
                if (! KgbLib_CheckNullity(ds.SEC_SUKS) ) {
                    _f_StDs("SEC_SUKS",ds.SEC_SUKS);
                }
                    
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    }
    
    var _f_GtDs = function (sec) {
        try {
            if ( KgbLib_CheckNullity(sec) ) {
                return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    }
    
    var _f_StDs = function (sec,ds) {
        try {
            if ( KgbLib_CheckNullity(sec) | KgbLib_CheckNullity(ds) ) {
                return;
            }
            
            var $scdom;
            switch (sec) {
                case "SEC_INTRO" :
                        var txt = ds.datas;
                        $scdom = $(".jb-pgabme-sbsec-mx[data-sec='abme-intro']");
                        
                        var ustgs = ds.ustgs;
                        var hashs = ds.hashs;

//                        Kxlib_DebugVars([JSON.stringify(hashs),JSON.stringify(ustgs)],true);
                        //rtxt = RenderedText
                        var rtxt = Kxlib_TextEmpow(txt,ustgs,hashs,null,{
                            "ena_inner_link" : {
        //                        "local" : true, //DEV, DEBUG, TEST
                                "all"   : false,
                            },
                            emoji : {
                                "size"          : 36,
                                "size_css"      : 22,
                                "position_y"    : 3
                            }
                        });
//                        Kxlib_DebugVars([rtxt],true);

                        $scdom.find(".jb-pgabme-sbs-abme-rslt").text("").append(rtxt);
                        $scdom.find(".jb-pgabme-sbs-b-wtpnl").addClass("this_hide");
                        $scdom.find(".jb-pgabme-sbs-b-shw-mx").removeClass("this_hide");
                    break;
                case "SEC_LVSP" :
                        $scdom = $(".jb-pgabme-sbsec-mx[data-sec='love-snippets']");
                        $.each(ds.datas,function(i,cd){
                            var ln = "<li class=\"pgabme-sbs-lvsp-li\">";
                            ln += "<a class=\"pgabme-sbs-lvsp-li-a jb-pgabme-sbs-lvsp-li-a\" href=\"javascript:;\"></a>";
                            ln += "</li>";
                            ln = $.parseHTML(ln);
                            
                            var txt = Kxlib_getDolphinsValue(cd);
                            $(ln).find(".jb-pgabme-sbs-lvsp-li-a").text(txt);
                            $scdom.find(".jb-pgabme-sbs-lvsp-l-mx").append(ln);
                        });
                        
                        $scdom.find(".jb-pgabme-sbs-b-wtpnl").addClass("this_hide");
                        $scdom.find(".jb-pgabme-sbs-b-shw-mx").removeClass("this_hide");
                    break;
                case "SEC_WHYME" :
                        $scdom = $(".jb-pgabme-sbsec-mx[data-sec='why-me']");
                        var rnk = 1;
                        $.each(ds.datas,function(i,txt){
                            if ( txt ) {
                                var rtxt = Kxlib_TextEmpow(txt,null,null,null,{
                                    "ena_inner_link" : {
//                                        "local" : true, //DEV, DEBUG, TEST
                                        "all"   : false,
                                    },
                                    emoji : {
                                        "size"          : 36,
                                        "size_css"      : 22,
                                        "position_y"    : 3
                                    }
                                });
//                                Kxlib_DebugVars([rtxt],true);

                                $scdom.find(".jb-pgabme-sbs-whme-li[data-rank='"+rnk+"']").text("").append(rtxt);
                            } else {
                                $scdom.find(".jb-pgabme-sbs-whme-li[data-rank='"+rnk+"']").remove();
                            }
                            rnk++;
                        });
                        
                        $scdom.find(".jb-pgabme-sbs-b-wtpnl").addClass("this_hide");
                        $scdom.find(".jb-pgabme-sbs-b-shw-mx").removeClass("this_hide");
                    break;
                case "SEC_IMAS" :
                    
                        $scdom = $(".jb-pgabme-sbsec-mx[data-sec='i-master']");
                        
                        var rnk = 1;
                        $.each(ds.datas,function(i,txt){
                            if ( txt ) {
                                var rtxt = Kxlib_TextEmpow(txt,null,null,null,{
                                    "ena_inner_link" : {
//                                        "local" : true, //DEV, DEBUG, TEST
                                        "all"   : false,
                                    },
                                    emoji : {
                                        "size"          : 36,
                                        "size_css"      : 22,
                                        "position_y"    : 3
                                    }
                                });
//                                Kxlib_DebugVars([rtxt],true);

                                $scdom.find(".jb-pgabme-sbs-imas-li[data-rank='"+rnk+"'] span").text("").append(rtxt);
                            } else {
                                $scdom.find(".jb-pgabme-sbs-imas-li[data-rank='"+rnk+"']").remove();
                            }
                            rnk++;
                        });
                        
                        $scdom.find(".jb-pgabme-sbs-b-wtpnl").addClass("this_hide");
                        $scdom.find(".jb-pgabme-sbs-b-shw-mx").removeClass("this_hide");
                    break;
                case "SEC_SUKS" :
                    break;
                default:
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    }
    
    
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
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** SERVER SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _Ax_LdDs = Kxlib_GetAjaxRules("TMLNR_ABME_GDS");
    var _Srv_LdDs = function (sec,isa,s) {
        if ( KgbLib_CheckNullity(sec) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            try {
                if (! KgbLib_CheckNullity(datas) ) {
//                    alert("CHAINE JSON AVANT PARSE"+datas);
                    datas = JSON.parse(datas);
                } else {
                    _xhr_ldds = null;
                    _f_SwSpnr();
                    return;
                }
                
                if (! KgbLib_CheckNullity(datas.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(datas.err) ) {
                        switch (datas.err) {
                            case "__ERR_VOL_U_G" :
                            case "__ERR_VOL_U_GONE" :
                            case "__ERR_VOL_USER_GONE" :
                            case "__ERR_VOL_ACC_GONE" :
                            case "__ERR_VOL_CU_GONE" :
                            case "__ERR_VOL_OWNER_GONE" :
                            case "__ERR_VOL_TGT_GONE" :
                                    location.reload();
                                break;
                            default :
                                    if (! isa ) {
                                        Kxlib_AJAX_HandleFailed();
                                    }
                                return;
                        }
                    } 
                    _xhr_ldds = null;
                    //On masque le spinner
                    _f_SwSpnr();
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) && ( 
                        datas.return.SEC_INTRO 
                        | !KgbLib_CheckNullity(datas.return.SEC_IMAS) 
                        | !KgbLib_CheckNullity(datas.return.SEC_LVSP) 
                        | !KgbLib_CheckNullity(datas.return.SEC_WHYME) 
                        | !KgbLib_CheckNullity(datas.return.SEC_SUKS) 
                ) ) {
                    var rds = [datas.return];
                    $(s).trigger("datasready",rds);
                } else if ( KgbLib_CheckNullity(datas.return) ) {
                    $(s).trigger("operended");
                } else {
                    _xhr_ldds = null;
                    //On masque le spinner
                    _f_SwSpnr();
                    return;
                }
            } catch (ex) {
//                alert("DEBUG AJAX => "+ex.message);
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                _xhr_pfafm = null;
                //On masque le spinner
                _f_SwSpnr();
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
            
            _xhr_ldds = null;
            //On masque le spinner
            _f_SwSpnr();
            
            if (! isa ) {
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            }
            return;
        };
        
        var cu = document.URL;
        var toSend = {
            "urqid": _Ax_LdDs.urqid,
            "datas": {
                "sec"   : sec,
                "cu"    : cu
            }
        };

        _xhr_ldds = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_LdDs.url, wcrdtl : _Ax_LdDs.wcrdtl });
    };
    
    
    /**************************************************************************a*****************************************************************************************/
    /**************************************************************************** VIEW SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_SwSpnr = function (sw) {
        try {
            if ( KgbLib_CheckNullity(sw) ) {
                $(".jb-tmlnr-loadm-trg").removeClass("this_hide");
                $(".jb-nwfd-loadm-spnr").addClass("this_hide");
            } else {
                $(".jb-tmlnr-loadm-trg").addClass("this_hide");
                $(".jb-nwfd-loadm-spnr").removeClass("this_hide");
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Vw_BldArt  = function (ds) {
        try {
            if ( KgbLib_CheckNullity(ds) ) {
                return;
            }
               
            var str__, fldesc, shdesc;
            if ( ds.hasOwnProperty("ustgs") && !KgbLib_CheckNullity(ds.ustgs) && typeof ds.ustgs === "object" ) {
                var istgs__ = [];
                $.each(ds.ustgs, function(x,v) {
                    var rw__ = [];
                    $.map(v, function(e,x) {
                        rw__.push(e);
                    });
                    istgs__.push(rw__.join("','"));
                });
                
                str__ = ( istgs__.length > 1 ) ? istgs__.join("'],['") : istgs__[0];
                str__ = "['" + str__ + "']";
            } 
            var m = Kxlib_Decode_After_Encode(ds.msg); 
            
            shdesc = ( m.length > 150 ) ? m.substr(0,150).concat(" ...") : m;
            shdesc = Kxlib_Decode_After_Encode(shdesc);
            
            fldesc = Kxlib_Decode_After_Encode(ds.msg);
            
            var am = "<article id=\"\" class=\"tmlnr-pgfv-art-bmx jb-tmlnr-pgfv-art-bmx jb-unq-bind-art-mdl\" data-trq-ver=\"ajca-v10\" ";
            am += "data-item=\"\" data-time=\"\" data-atype=\"fav\" data-with=\"\" data-istr=\"\" data-trds=\"\" data-fvtp=\"\" data-trq-ver='ajca-v10' ";
            am += " data-ajcache=\"\" ";
            am += ">";
            am += "<header>";
            am += "<span class=\'css-tgpsy kxlib_tgspy fav\' ";
            am += "data-tgs-crd=\'\' data-tgs-dd-atn=\'\' data-tgs-dd-uut=\'\'";
            am += "title=\"Date à laquelle la publication a été mise en favori\"";
            am += ">";
            am += "<span class=\'tgs-frm\'></span>";
            am += "<span class=\'tgs-val\'></span>";
            am += "<span class=\'tgs-uni\'></span>";
            am += "</span>";
            am += "</header>";
            am += "<div>";
            am += "<a class=\"tmlnr-pgfv-art-i-bmx jb-tmlnr-pgfv-art-i-bmx\">";
            if ( ds.fvtp === "ART_XA_FAV_PRI" ) {
                am += "<span class=\"tmlnr-pgfv-art-lck jb-tmlnr-pgfv-art-lck\"></span>";
            }
            am += "<span class=\"tmlnr-pgfv-art-i-fd jb-tmlnr-pgfv-art-i-fd\"></span>";
            am += "<img class=\"tmlnr-pgfv-art-i jb-tmlnr-pgfv-art-i\" width=\"240\" height=\"240\" src=\"\" alt=\"\" />";
            am += "<span class=\"tmlnr-pgfv-art-i-txt jb-tmlnr-pgfv-art-i-txt this_hide\">";
            am += "<span class=\"psd\"></span><br/>";
            am += "<span class=\"desc\" data-dsc=\"\"></span>";
            am += "</span>";
            am += "</a>";
            am += "</div>";
            am += "</article>";
            am = $.parseHTML(am);
            
            /*
             * ETAPE :
             *      On FILL les données
             */
            $(am)
                .attr("id","post-fv-aid-".concat(ds.id))
                .data("item",ds.id).attr("data-item",ds.id)
                .data("time",ds.time).attr("data-time",ds.time)
                .data("with",str__)
                .data("istr",ds.istrd).attr("data-istr",ds.istrd)
                .data("vidu",ds.vidu).attr("data-vidu",ds.vidu)
                .data("hasfv",ds.hasfv).attr("data-hasfv",ds.hasfv)
                .data("fvtp",ds.fvtp).attr("data-fvtp",ds.fvtp)
                .data("fvtm",ds.fvtp).attr("data-fvtm",ds.fvtm);
        
            if ( ds.hasOwnProperty("vidu") && ds.vidu ) {
                $(am).find(".jb-tmlnr-pgfv-art-i-fd").addClass("vidu");
            }
        
            if ( ds.hasOwnProperty("trds") && ds.trds ) {
                ds.trds = JSON.parse(Kxlib_Decode_After_Encode(ds.trds));
                $(am).data("trds",ds.trds).attr("data-trds",ds.trds);
            }
            
            $(am).data("ajcache",JSON.stringify(ds)).attr("data-ajcache",JSON.stringify(ds));
            
            $(am).find(".kxlib_tgspy").data("tgs-crd",ds.fvtm);
            
            $(am).find(".jb-tmlnr-pgfv-art-i").data("tgs-crd",ds.img);
            
            $(am).find(".jb-tmlnr-pgfv-art-i").attr("src",ds.img).attr("alt",Kxlib_Decode_After_Encode(fldesc));
            
            $(am).find(".jb-tmlnr-pgfv-art-i-txt .psd").text("@".concat(ds.upsd));
            
            $(am).find(".jb-tmlnr-pgfv-art-i-txt .desc").data("dsc",fldesc).attr("data-dsc",fldesc).text(shdesc);
            
            /*
             * ETAPE :
             *      On effectue un REBIND
             */
            $(am).find(".jb-tmlnr-pgfv-art-i-bmx").hover(function(){
                _f_HvrCz(this,true);
            },function(){
                _f_HvrCz(this);
            });

            $(am).find(".jb-tmlnr-pgfv-hdr-ax-tgr").click(function(e){
                Kxlib_PreventDefault(e);
                _f_Action(this);
            });
            $(am).find(".jb-tmlnr-pgfv-art-i-bmx").off("click").click(function(e){
                Kxlib_PreventDefault(e);
                (new Unique ()).OnOpen("fav",this);
            });
            
            return am;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************* LISTENERS SCOPE *************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    $(".jb-pgabme-sbs-h-altr, .jb-pgabme-sbs-chc").click(function(e){
        Kxlib_PreventDefault(e);
            
        _f_Action(this);
    });
    
    setTimeout(function(){
        _f_GtDs_Auto();
    },100);
    
    
})();