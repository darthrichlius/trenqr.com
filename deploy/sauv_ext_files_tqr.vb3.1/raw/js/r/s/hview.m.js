

(function(){
    var _xhr_hview_srh, _xhr_hview_gtmr;
    
    /*************************************************************************************************************************************************************************************************/
    /***************************************************************************************** PROCESS SCOPE *****************************************************************************************/
    /*************************************************************************************************************************************************************************************************/
    
    var _f_Gdf = function () {
        var ds = {
            "hsh_max_ln"  : 50,
            /*
             * cnbp (CoNtentByPull)
             *      Le nombre de contenu récupérer par opération PULL.
             */
            "cnbp"  : 10
        };
        return ds;
    };
    
    
    var _f_Init = function () {
        try {
            /*
             * ETAPE :
             *      On donne une taille minimale à la zone
             */
            $(".jb-tqr-hview-screen").css({
                "min-height" : $(window).height()+"px"
            });
            
            
            /*
             * ETAPE :
             *      Dans le cas où il existe une chaine dans INPUT, on lance une opération
             */
            if (! KgbLib_CheckNullity($(".jb-tqr-hview-c-h-h-ipt").data("input")) ) {
                _f_LckIpt(true);
                
            }
            _f_HvSrh("init");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Action = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity(a) && KgbLib_CheckNullity($(x).data("action")) ) ) {
                return;
            }
            
            var _a = (! KgbLib_CheckNullity(a) ) ? a : $(x).data("action");
            switch (_a) {
                case "search" :
                case "get_more" :
                        _f_HvSrh(_a);
                    break;
                case "open-in-vwr" :
                        _f_OpenVwr(x,a);
                    break;
                default :
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_HvSrh = function (a) {
        try {
            if ( KgbLib_CheckNullity(a) ) {
                return;
            }
            
            /*
             * ETAPE :
             *      On vérifie qu'il n'y a pas déjà une action en cours en fonction du type d'action en cours.
             */
            var hs, dr, pvi, pvt;
            switch (a) {
                case "init" :
                case "search" :
                        /*
                         * ETAPE :
                         *      On vérifie la validité du texte.
                         */
                        var t__ = $(".jb-tqr-hview-c-h-h-ipt").val();
                        t__ = _f_SrhStrValid(t__);
                        if (! t__ ) {
                            return;
                        }
                        hs = t__;
                        
                        /*
                         * EXPLICATION :
                         *      Si on lance une opération de recherche, on annule toutes les opérations en cours.
                         */
                        if (! KgbLib_CheckNullity(_xhr_hview_srh) ) {
                            _xhr_hview_srh.abort();
                        }
                        if (! KgbLib_CheckNullity(_xhr_hview_gtmr) ) {
                            _xhr_hview_gtmr.abort();
                        }
                        
                        /*
                         * ETAPE :
                         *      On efface tous les anciens résultats
                         */
                        $(".jb-tqr-hview-art-bmx").remove();
                        
                        /*
                         * ETAPE :
                         *      On lock le champ le temps de l'opération
                         */
                        _f_LckIpt(true);
                        
                        /*
                         * ETAPE :
                         *      On efface le texte descriptif lié au HASHTAG
                         */
                        $(".jb-tqr-hview-c-h-h-x-mx").text("");
                        
                    break;
                case "get_more" :
                        /*
                         * ETAPE :
                         *      On vérifie que l'on a pas un EOF. 
                         *      Cela signifierait qu'il n'y a plus de données plus bas et que ça ne sert à rien d'essayer de lancer une requete.
                         */
                        if ( $(".jb-tqr-hview-ctr-l-l-EOF-none").data("EOF") === 1 ) {
//                            Kxlib_DebugVars(["GETMORE >>> EOF !!!"]);
                            return;
                        }
                        
                        
                        /*
                         * ETAPE :
                         *      On vérifie qu'il y a au moins le nombre minimal prévu par PULL.
                         *      Dans le cas contraire, cela signiferait qu'il n'y a très certainement rien plus bas.
                         */
                        if ( $(".jb-tqr-hview-art-bmx").length < _f_Gdf().cnbp ) {
//                            Kxlib_DebugVars(["GETMORE >>> SKIP !!!"]);
                            return;
                        }
//                        Kxlib_DebugVars(["GETMORE >>> GO !!!"]);
//                        return;
                        
                        var t__ = $(".jb-tqr-hview-c-h-h-ipt").data("input");
                        t__ = _f_SrhStrValid(t__);
                        if (! t__ ) {
                            return;
                        }
                        hs = t__;
                        
                        /*
                         * EXPLICATION :
                         *      Si on lance une opération de GET_MORE.
                         *      On s'assure qu'il n'y a pas une opération SEARCH (Prioritaire) qui a été lancée. 
                         *      Dans ce cas, on annule l'opération GET_MORE au niveau d'AJAX et bloque l'opération GET_MORE.
                         */
                        if (! KgbLib_CheckNullity(_xhr_hview_srh) ) {
                            if (! KgbLib_CheckNullity(_xhr_hview_gtmr) ) {
                                _xhr_hview_gtmr.abort();
                            }
                            return;
                        } else if (! KgbLib_CheckNullity(_xhr_hview_gtmr) ) {
                            return;
                        }
                        
                        /*
                         * ETAPE :
                         *      On récupère les données pivot. Elles existent forcement car on vérifie en amont qu'on a un nombre suffisant d'Articles faisant penser qu'il y a d'autres plus bas.
                         */
                        dr = "BTM";
                         pvi = $(".jb-tqr-hview-art-bmx:last").data("hid");
                         pvt = $(".jb-tqr-hview-art-bmx:last").data("htm");
                    break;
                case "auto" :
                    break;
                default :
                    return;
            }
            
            /*
             * ETAPE :
             *      On masque NOONE.
             */
            _f_None();
            
            /*
             * ETAPE :
             *      On lance l'affiche du spinner en fonction de l'action.
             */
            _f_Spnr(true,a);
            
            
            /*
             * ETAPE :
             *      On regroupe les données avant l'envoie au niveau du serveur.
             */
            var tos = {
                "hs"    : hs,
                "dr"    : ( dr ) ? dr : "FST",
                "pvi"   : ( pvi ) ? pvi : null,
                "pvt"   : ( pvt ) ? pvt : null
            };
            
//            Kxlib_DebugVars([JSON.stringify(tos)],true);
//            return;
            
            var s = $("<span/>");
            _f_Srv_HvSrh(tos.hs,tos.dr,tos.pvi,tos.pvt,a,s);
            
            $(s).on("datasready",function(e,ds){
                if ( KgbLib_CheckNullity(ds) ) {
                    return ds;
                }
                
//                Kxlib_DebugVars([JSON.stringify(ds)],true);
//                return;
                
                if (! KgbLib_CheckNullity(ds.hd) ) {
                    _f_HDesc(ds.hd);
                } else {
                    _f_HDesc();
                }
                
                /*
                 * ETAPE :
                 *      On change le resultat dans l'entete
                 */
                $(".jb-tqr-hview-c-h-h-hlib-mx").attr("title",hs).text("#"+hs);
                $(".jb-tqr-hview-c-h-h-ipt").data("input",hs);
                
                if (! KgbLib_CheckNullity(ds.ds) ) {
                    _f_AddGblDs(ds.ds,a,tos.dr);
                } else {
                    _f_None(true);
                }
                
                /*
                 * [DEPUIS 03-07-16]
                 */
                var stateObj = {}; 
                var prmlk_path = "/hview/q=".concat(tos.hs,"&src=hash");
                window.history.replaceState(stateObj, "", prmlk_path);
                
                /*
                 * On unlock INPUT
                 */
                _f_LckIpt();
                
                /*
                 * On UNLOCK les pointeurs
                 */
                if ( $.inArray(a,["search","init"]) !== -1 ) {
                    _xhr_hview_srh = null;
                } else if ( a === "get_more" ) {
                    _xhr_hview_gtmr = null;
                }
            });
            
            $(s).on("operended",function(e,ds){
                if ( ds && ds.hd ) {
                   /*
                    * ETAPE :
                    *      On efface le texte descriptif lié au HASHTAG
                    */
                    _f_HDesc(ds.hd);
                } else {
                    _f_HDesc();
                }
                
                /*
                 * ETAPE :
                 *      On change le resultat dans l'entete
                 */
                $(".jb-tqr-hview-c-h-h-hlib-mx").attr("title",hs).text("#"+hs);
                  
                if ( $.inArray(a,["search","init"]) !== -1 ) {
                   /*
                    * ETAPE :
                    *      On affiche NONE et masque le SPINER
                    */
                   _f_Spnr(null,"search");
                   _f_None(true);
                } else if ( a === "get_more" ) {
                    $(".jb-tqr-hview-ctr-l-l-EOF-none").data("EOF",1);
                }
                
                /*
                 * [DEPUIS 03-07-16]
                 */
                var stateObj = {}; 
                var prmlk_path = "/hview/q=".concat(tos.hs,"&src=hash");
                window.history.replaceState(stateObj, "", prmlk_path);
                
                /*
                 * On unlock INPUT
                 */
                _f_LckIpt();
                
                /*
                 * On UNLOCK les pointeurs
                 */
                if ( $.inArray(a,["search","init"]) !== -1 ) {
                    _xhr_hview_srh = null;
                } else if ( a === "get_more" ) {
                    _xhr_hview_gtmr = null;
                }
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_SrhStrValid = function (hs) {
        try {
            if ( KgbLib_CheckNullity(hs) ) {
                return;
            }
            var ok = true;
            
           /*
            * ETAPE :
            *      (1) On s'assure de son existence.
            *      (2) On trim
            *      (3) On retire le '#' s'il existe
            *      (4) On vérifie qu'il s'agit d'un mot
            *      (5) On vérifie la taille
            */
            // (1) On s'assure de son existence.
            if (! ( typeof hs === "string" && hs.length ) ) {
                return;
            }
            //(2) On trim
            hs = hs.trim();
            //(3) On retire le '#' s'il existe
            hs = ( hs.charAt(0) === "#" ) ? hs.slice(1) : hs;
            //(4) On vérifie qu'il s'agit d'un mot
            var rgx = /[^a-z\d_ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]/i;
            if ( rgx.test(hs) ) {
                /*
                 * TODO :
                 *      Afficher un message d'erreur de manière textuel. 
                 *      C'est moins aggressif et plus agréable.
                 */
                alert("Seulement des Lettres, Chiffres, et '_' ");
                ok = false;
            }
            //(5) On vérifie la taille
            if ( hs.length > _f_Gdf().hsh_max_ln ) {
                /*
                 * TODO :
                 *      Afficher un message d'erreur de manière textuel. 
                 *      C'est moins aggressif et plus agréable.
                 */
                alert("Pas plus de "+_f_Gdf().hsh_max_ln+" caracètres");
                ok = false;
            }
            
            return ( ok ) ? hs : false;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_AddGblDs = function (ds,a,dr) {
        try {
            if ( KgbLib_CheckNullity(ds) | KgbLib_CheckNullity(a) | KgbLib_CheckNullity(dr) ) {
                return;
            }
            
            if ( $.inArray(dr,["FST","TOP","BTM"]) === -1 ) {
                return;
            }
            
            _f_None();
            _f_Spnr(null,a);
            
            /*
             * g : (Guide) On s'en sert pour l'affichage des données dans le bon ordre
             *      'hid'   : L'identifiant associé au HASHTAG
             *      'cnid'  : L'identifiant associa au CONTENU du HASHTAG
             *      'time'  : Le TIMESTAMP du HASHGTAG
             *      'type'  : Le type de CONTENU (AIML, AITR, TST)
             * c : (CONTENTS) Tableau de données
             */
            
            $.each(ds.g,function(x,st){
                if ( $(".jb-tqr-hview-art-bmx[data-cnid='"+st.cnid+"'][data-ctp='"+st.type+"']").length ) {
                    Kxlib_DebugVars(["Exists > (TYPE) : "+st.type+"; (ITEM) : "+st.cnid]);
                    return true;
                }
                
                var cm;
                if ( $.inArray(st.type,["AIML","AITR"]) !== -1 ) {
                    cm = _f_PprMdlPho(st,ds.c[st.type][st.cnid]);
                }
                else if ( $.inArray(st.type,["TST"]) !== -1 ) {
                    cm = _f_PprMdlTst(st,ds.c[st.type][st.cnid]);
                    cm = _f_RbdMdlTst(cm);
                }
                
                /*
                 * ETAPE :
                 *      On ajoute dans la file le contenu
                 */
                if ( dr === "TOP" ) {
                    $(cm).hide().prependTo(".jb-tqr-hview-ctr-l-list-mx").fadeIn();
                } else {
                    $(cm).hide().appendTo(".jb-tqr-hview-ctr-l-list-mx").fadeIn();
                }
                
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    
    /*************************************************************************************************************************************************************************************************/
    
    var _f_OpenVwr = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            if ( require.specified("r/csam/tkbvwr.csam") ) {
//                Kxlib_DebugVars(["ASDRBNR : Déjà chargé !",_VWR]);
                _VWR.open({
                    model   : "AJCA-HVIEW",
                    trigger : x,
                    action  : a
                });
            } else {
                require(["r/csam/tkbvwr.csam"],function(TbkVwr){
                    _VWR = new TbkVwr();
                    _VWR.open({
                        model   : "AJCA-HVIEW",
                        trigger : x,
                        action  : a
                    });
                });
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /*************************************************************************************************************************************************************************************************/
    /***************************************************************************************** SERVER SCOPE *****************************************************************************************/
    /*************************************************************************************************************************************************************************************************/
    
    var _Ax_HvSrh = Kxlib_GetAjaxRules("TQR_HVIEW_GDS");
    var _f_Srv_HvSrh  = function(hs,dr,pvi,pvt,a,s) {
        if ( KgbLib_CheckNullity(hs) | KgbLib_CheckNullity(a) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
//            alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    if ( $.inArray(a,["search","init"]) !== -1 ) {
                        _xhr_hview_srh = null;
                    } else if ( a === "get_more" ) {
                        _xhr_hview_gtmr = null;
                    }
                    _f_LckIpt();
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    if ( $.inArray(a,["search","init"]) !== -1 ) {
                        _xhr_hview_srh = null;
                    } else if ( a === "get_more" ) {
                        _xhr_hview_gtmr = null;
                    }
                    _f_LckIpt();
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
                            default :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) && ( !KgbLib_CheckNullity(datas.return.ds) && !KgbLib_CheckNullity(datas.return.ds.g) && !KgbLib_CheckNullity(datas.return.ds.c) ) ) {
                    var ds = [datas.return];
                    $(s).trigger("datasready",ds);
                } else if ( !KgbLib_CheckNullity(datas.return.hd) && datas.return.hd ) {
                    var ds = [datas.return];
                    $(s).trigger("operended",ds);
                    return;
                } else {
                    $(s).trigger("operended");
                    return;
                } 
                
            } catch (ex) {
                if ( $.inArray(a,["search","init"]) !== -1 ) {
                    _xhr_hview_srh = null;
                } else if ( a === "get_more" ) {
                    _xhr_hview_gtmr = null;
                }
               _f_LckIpt();     
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
            "urqid": _Ax_HvSrh.urqid,
            "datas" : {
                "hs"    : hs,
                "dr"    : dr,
                "pvi"   : pvi,
                "pvt"   : pvt,
                "cu"    : curl
            }
        };
        
        var _xhr = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_HvSrh.url, wcrdtl : _Ax_HvSrh.wcrdtl });
        if ( $.inArray(a,["search","init"]) !== -1 ) {
            _xhr_hview_srh = _xhr;
        } else if ( a === "get_more" ) {
            _xhr_hview_gtmr = _xhr;
        }
    };
    
    /*************************************************************************************************************************************************************************************************/
    /***************************************************************************************** VIEW SCOPE ********************************************************************************************/
    /*************************************************************************************************************************************************************************************************/
    
    var _f_LckIpt = function (y) {
        try {
            
            if ( y ) {
                $(".jb-tqr-hview-c-h-h-ipt").attr("disabled","disabled");
            } else {
                $(".jb-tqr-hview-c-h-h-ipt").removeAttr("disabled");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_None = function (sh) {
        try {
            
            if ( sh ) {
                $(".jb-tqr-hview-ctr-l-l-none").removeClass("this_hide");
            } else {
                $(".jb-tqr-hview-ctr-l-l-none").addClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_HDesc = function (d) {
        try {
            
            if ( d ) {
                $(".jb-tqr-hview-c-h-h-x-mx").html(d);
                $(".jb-tqr-hview-c-h-h-x-bmx").removeClass("this_hide");
            } else {
                $(".jb-tqr-hview-c-h-h-x-bmx").addClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Spnr = function (sh,scp) {
        try {
            if ( KgbLib_CheckNullity(scp) ) {
                return;
            }
            
            var mx;
            switch (scp) {
                case "init" :
                case "search" :
                        mx = $(".jb-tqr-hview-ctr-l-l-loadr");
                    break;
                case "get_more" :
                        mx = null;
                    break;
                default:
                    return;
            }
            if (! $(mx).length ) {
                return;
            } else if ( sh ) {
                $(mx).removeClass("this_hide");
            } else {
                $(mx).addClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_PprMdlPho = function (hs,ds) {
        try {
            if ( KgbLib_CheckNullity(hs) | KgbLib_CheckNullity(ds) ) {
                return;
            }
          
           /*
            * TABLE DES DONNÉES :
            *   "aid"       : 
            *   "apic"      : 
            *   "adesc"     : 
            *   "atime"     : 
            *   "aprmlk"    : 
            * >>> DONNEES SUR LE PROPRIETAIRE 
            *   "aoid"      : 
            *   "aofn"      : 
            *   "aopsd"     : 
            *   "aohref"    : 
            *   "aoppic"    :
            *   "ustgs"    : 
            *   "hashs"    : 
            *   "areacts"   : 
            *   "aevals"    : 
            *   "arnb"      : 
            * >>> DONNEES SUR LA TENDANCE 
            *   "teid"      : 
            *   "ttle"      : 
            *   "ttle_href" : 
            *   "thref"     : 
            */
            
            var cm = "<article class=\"tqr-hview-art-bmx jb-tqr-hview-art-bmx\" data-type=\"photo\" data-hid=\"\" data-cnid=\"\" data-htm=\"\" data-ctp=\"\" data-ajcache=\"\">";
            cm += "<div class=\"tqr-hview-art-pho-top\">";
            cm += "<a class=\"tqr-hview-a-pho-a jb-tqr-hview-a-pho-a\" href=\"\">";
            cm += "<img class=\"tqr-hview-a-pho-i jb-tqr-hview-a-pho-i\" width=\"600px\" height=\"600px\" src=\"\" alt=\"\" />";
            cm += "<span class=\"tqr-hview-a-pho-fd jb-tqr-hview-a-pho-fd\"></span>";
            cm += "<span class=\'css-tgpsy kxlib_tgspy tqr-hview\' data-type=\"photo\" data-tgs-crd=\'"+ds.atime+"\' data-tgs-dd-atn=\'\' data-tgs-dd-uut=\'\'>";
            cm += "<span class=\'tgs-frm\'></span>";
            cm += "<span class=\'tgs-val\'></span>";
            cm += "<span class=\'tgs-uni\'></span>";
            cm += "</span>";
            cm += "<span class=\"tqr-hview-a-pho-stats\">";
            cm += "<span class=\"tqr-hview-a-pho-s-mx jb-tqr-hview-a-pho-s-mx\" data-type=\"eval\">";
            cm += "<span class=\"tqr-hview-a-pho-s-nb jb-tqr-hview-a-pho-s-nb\" data-type=\"eval\"></span>";
            cm += "</span>";
            cm += "<span class=\"tqr-hview-a-pho-s-mx jb-tqr-hview-a-pho-s-mx\" data-type=\"react\">";
            cm += "<span class=\"tqr-hview-a-pho-s-nb jb-tqr-hview-a-pho-s-nb\" data-type=\"react\"></span>";
            cm += "<span class=\"tqr-hview-a-pho-s-lg\" data-type=\"react\" style=\" background: url(\'"+Kxlib_GetExtFileURL("sys_url_img","r/r3.png")+"\') no-repeat; background-size: 100%;\"></span>";
            cm += "</span>";
            cm += "</span>";
            cm += "</a>";
            cm += "</div>";
            cm += "<div class=\"tqr-hview-art-pho-btm\">";
            cm += "<div class=\"tqr-hview-a-pho-b-trtle-mx jb-tqr-hview-a-pho-b-trtle-mx\">";
            cm += "<a class=\"tqr-hview-a-pho-b-trtle jb-tqr-hview-a-pho-b-trtle\" title=\"\"</a>";
            cm += "</div>";
            cm += "<div class=\"tqr-hview-a-pho-b-dsc-mx\">";
            cm += "<a class=\"tqr-hview-a-pho-b-d-o jb-tqr-hview-a-pho-b-d-o\" href=\"\"></a>";
            cm += "<span class=\"tqr-hview-a-pho-b-d-dsc jb-tqr-hview-a-pho-b-d-dsc\"></span>";
            cm += "</div>";
            cm += "</div>";
            cm += "</article>";
            cm = $.parseHTML(cm);
            
            
            /*
             * ETAPE :
             *      Données de l'entete
             */
            $(cm).data("ajcache",JSON.stringify(ds)).attr("data-ajcache",JSON.stringify(ds));
            $(cm).data("hid",hs.hid).data("cnid",hs.cnid).data("htm",hs.time).data("ctp",hs.type);
            $(cm).attr({
                "data-hid"  : hs.hid,
                "data-cnid" : hs.cnid, 
                "data-htm"  : hs.time, 
                "data-ctp"  : hs.type
            });
            
            /*
             * Données sur l'IMAGE liée à l'ARTICLE
             */
            $(cm).find(".jb-tqr-hview-a-pho-a").attr({
                href : ds.aprmlk
            });
            $(cm).find(".jb-tqr-hview-a-pho-i").attr({
                "src" : ds.apic,
                "alt" : Kxlib_Decode_After_Encode(ds.adesc) 
            });
            
            /*
             * Données statistiques
             */
            var evals = ( ds.hasOwnProperty("aevals") && ds.aevals && $.isArray(ds.aevals) && ds.aevals.length === 4 ) ? ds.aevals[3] : 0;
            $(cm).find(".jb-tqr-hview-a-pho-s-nb[data-type='eval']").text(evals);
            $(cm).find(".jb-tqr-hview-a-pho-s-nb[data-type='react']").text(ds.arnb);
                
            /*
             * Le titre de la TENDANCE s'il s'agit du cas TR
             */
            if ( ds.hasOwnProperty("teid") && ds.teid ) {
                $(cm).find(".jb-tqr-hview-a-pho-b-trtle").attr({
                    href    : ds.thref,
                    title   : Kxlib_Decode_After_Encode(ds.ttle) 
                }).text(Kxlib_Decode_After_Encode(ds.ttle));
            } else {
                $(cm).find(".jb-tqr-hview-a-pho-b-trtle-mx").remove();
            }
            
            /*
             * Le propriétaire de l'Article
             */
            $(cm).find(".jb-tqr-hview-a-pho-b-d-o").attr({
                href : "/".concat(ds.aopsd),
                title : ds.aofn.concat(" (@").concat(ds.aopsd).concat(")")
            }).text("@".concat(ds.aopsd));
            
            /*
             * ETAPE : 
             * On transforme les Usertags contenus dans le texte de description.
             */ 
            /*
            if ( ds.hasOwnProperty("ustgs") && ds.ustgs !== undefined && Kxlib_ObjectChild_Count(ds.ustgs) ) {
                var txt = Kxlib_Decode_After_Encode(ds.adesc);
                var ustgs = ( $.isArray(ds.ustgs) ) ? Kxlib_GetColumn(3,ds.ustgs) : [ds.ustgs[3]];
                
//                Kxlib_DebugVars([JSON.stringify(ustgs)],true);
//                Kxlib_DebugVars([JSON.stringify(ds),JSON.stringify(ds.ustgs)],true);

                var t__ = Kxlib_UsertagFactory(txt,ustgs,"tqr-unq-user");
                t__= $("<div/>").text(t__).text();
//                 Kxlib_DebugVars([t__],true);
//                 t__ = Kxlib_Decode_After_Encode(t__);

                t__ = Kxlib_SplitByUsertags(t__);

                //Mettre en place la description
                $(cm).find(".jb-tqr-hview-a-pho-b-d-dsc").html(t__);
            } else {
                t__ = ds.adesc;
                t__ = $("<div/>").html(t__).text();
                
                //Mettre en place la description
                $(cm).find(".jb-tqr-hview-a-pho-b-d-dsc").text(t__);
            }
            //*/
            
            var atxt = ds.adesc;
            atxt = $("<div/>").html(atxt).text();

            /*
             * [NOTE 22-04-16]
             *      Le code ci-dessous n'est pas sur à 100%
             */
            
            var ajca_o, ajca = ""+$(cm).data("ajcache")+"";
            ajca_o = ( typeof $(cm).data("ajcache") === "object" ) ? $(cm).data("ajcache") : JSON.parse(ajca);  
                
            var ustgs = ajca_o.ustgs;
            var hashs = ajca_o.hashs;

//            Kxlib_DebugVars([ajca_o.cmnid,"; HASH =>",hashs,"; USTGS =>",ustgs],true);

            //rtxt = RenderedText
            var rtxt = Kxlib_TextEmpow(atxt,ustgs,hashs,null,{
                emoji : {
                    "size"          : 36,
                    "size_css"      : 18,
                    "position_y"    : 3
                }
            });
            
            //On ajoute le texte
            $(cm).find(".jb-tqr-hview-a-pho-b-d-dsc").text("").append(rtxt);
            
            /*
             * La description de l'Article
             */
//            $(cm).find(".jb-tqr-hview-a-pho-b-d-dsc").text(Kxlib_Decode_After_Encode(ds.adesc));
            
            return cm;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileNcme,ex.lineNumber],true);
        }
    };
    
    
    var _f_RbdMdlTst = function (am) {
        try {
            if ( KgbLib_CheckNullity(am) ) {
                return;
            }
            
            $(am).click(function(e){
                Kxlib_PreventDefault(e);
                
                _f_Action(this,"open-in-vwr");
            });
            
//            $(am).find("a").click(function(e){
            $(am).children().click(function(e){
                if ( $(e.target).is("a") | $(e.target).parent().is("a") ) {
                    Kxlib_StopPropagation(e);
                }
            });

            $(am).hover(function(e){
                var bmx = ( $(this).is(".jb-tqr-hview-art-bmx") ) ? $(this) : $(x).closest(".jb-tqr-hview-art-bmx");

                $(bmx).addClass("cstm-hvr");
            },function(){
                var bmx = ( $(this).is(".jb-tqr-hview-art-bmx") ) ? $(this) : $(x).closest(".jb-tqr-hview-art-bmx");

                $(bmx).removeClass("cstm-hvr");
            });
            
            return am;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileNcme,ex.lineNumber],true);
        }
    };
    
    
    var _f_PprMdlTst = function (hs,ds) {
        try {
            if ( KgbLib_CheckNullity(hs) | KgbLib_CheckNullity(ds) ) {
                return;
            }
          
           /*
            * TABLE DES DONNÉES :
            *   "tsid"          : 
            *   "tsm"           : 
            *   "tstime"        : 
            *   "tstustgs"      :
            *   >>> Données sur l'OWNER
            *   "tsouid"        : 
            *   "tsoufn"        : 
            *   "tsoupsd"       : 
            *   "tsouppic"      : 
            *   "tsouhref"      : 
            *   >>> Données sur la TARGET
            *   "tsguid"       : 
            *   "tsgufn"        : 
            *   "tsgupsd"       : 
            *   "tsguppic"      : 
            *   "tsguhref"      : 
            */
           
            
            var cm = "<article class=\"tqr-hview-art-bmx jb-tqr-hview-art-bmx\" data-type=\"testy\" data-hid=\"\" data-cnid=\"\" data-htm=\"\" data-ctp=\"\" data-ajcache=\"\" data-atype=\"hview\" >";
            cm += "<div class=\"tqr-hview-art-tst-left\">";
            cm += "<div class=\"tqr-hview-a-tst-o-top\">";
            cm += "<a class=\"tqr-hview-a-tst-o-a jb-tqr-hview-a-tst-o-a\" href=\"\">";
            cm += "<img class=\"tqr-hview-a-tst-o-i jb-tqr-hview-a-tst-o-i\" src=\"\" height=\"65\" alt=\"65\" alt=\"\"/>";
            cm += "<span class=\"tqr-hview-a-tst-o-fd\"></span>";
            cm += "</a>";
            cm += "</div>";
            cm += "<div class=\"tqr-hview-a-tst-o-btm\">";
            cm += "<a class=\"tqr-hview-a-tst-o-psd jb-tqr-hview-a-tst-o-psd\" href=\"\"></a>";
            cm += "<span class=\"tqr-hview-a-tst-o-fn jb-tqr-hview-a-tst-o-fn\"></span>";
            cm += "</div>";
            cm += "</div>";
            cm += "<div class=\"tqr-hview-art-tst-right\">";
            cm += "<div class=\"tqr-hview-a-tst-r-top\">";
            cm += "<a class=\"tqr-hview-a-tst-tgt-a jb-tqr-hview-a-tst-tgt-a\" href=\"\"></a>";
            cm += "<span class=\'css-tgpsy kxlib_tgspy tqr-hview\' data-type=\"testy\" data-tgs-crd=\'"+ds.tm+"\' data-tgs-dd-atn=\'\' data-tgs-dd-uut=\'\'>";
            cm += "<span class=\'tgs-frm\'></span>";
            cm += "<span class=\'tgs-val\'></span>";
            cm += "<span class=\'tgs-uni\'></span>";
            cm += "</span>";
            cm += "</div>";
            cm += "<div class=\"tqr-hview-a-tst-r-btm\">";
            cm += "<div class=\"tqr-hview-a-tst-m jb-tqr-hview-a-tst-m\"></div>";
            cm += "</div>";
            cm += "</div>";
            cm += "</article>";
            cm = $.parseHTML(cm);
            
            /*
             * ETAPE :
             *      Données de l'entete
             */
            $(cm).data("ajcache",JSON.stringify(ds));
            $(cm).data("hid",hs.hid).data("cnid",hs.cnid).data("htm",hs.time).data("ctp",hs.type);
            $(cm).attr({
                "data-hid"  : hs.hid,
                "data-cnid" : hs.cnid, 
                "data-htm"  : hs.time, 
                "data-ctp"  : hs.type
            });
            
            /*
             * Données sur l'image de profil de OWNER
             */
            $(cm).find(".jb-tqr-hview-a-tst-o-a").attr({
                href : "/"+ds.au.opsd
            });
            $(cm).find(".jb-tqr-hview-a-tst-o-i").attr({
                "src" : ds.au.oppic,
                "alt" : ds.au.ofn.concat(" (@").concat(ds.au.opsd).concat(")")
            });
                
            /*
             * Données sur OWNER
             */
            $(cm).find(".jb-tqr-hview-a-tst-o-psd").attr({
                href : "/"+ds.au.opsd
            }).text("@".concat(ds.au.opsd));
            $(cm).find(".jb-tqr-hview-a-tst-o-fn").text(ds.au.ofn);
                
            /*
             * Données sur TARGET s'il existe et est différent de OWNER
             */
            if ( ds.au.oid !== ds.tg.oid ) {
                 $(cm).find(".jb-tqr-hview-a-tst-tgt-a").attr({
                    href : "/"+ds.tg.opsd
                }).text("@".concat(ds.tg.opsd));
            } else {
                $(cm).find(".jb-tqr-hview-a-tst-tgt-a").remove();
            }
            
            
            /*
             * ETAPE : 
             * On transforme les Usertags contenus dans le texte de description.
             */ 
            /*
            if ( ds.hasOwnProperty("tstustgs") && ds.tstustgs !== undefined && Kxlib_ObjectChild_Count(ds.tstustgs) ) {
                var txt = Kxlib_Decode_After_Encode(ds.tsm);
                var ustgs = ( $.isArray(ds.tstustgs) ) ? Kxlib_GetColumn(3,ds.tstustgs) : [ds.tstustgs[3]];
                
//                Kxlib_DebugVars([JSON.stringify(ustgs)],true);
//                Kxlib_DebugVars([JSON.stringify(ds),JSON.stringify(ds.tstustgs)],true);

                var t__ = Kxlib_UsertagFactory(txt,ustgs,"tqr-unq-user");
                t__= $("<div/>").text(t__).text();
//                 Kxlib_DebugVars([t__],true);
//                 t__ = Kxlib_Decode_After_Encode(t__);

                t__ = Kxlib_SplitByUsertags(t__);

                //Mettre en place la description
                $(cm).find(".jb-tqr-hview-a-tst-m").html(t__);
            } else {
                t__ = ds.tsm;
                t__ = $("<div/>").html(t__).text();
                
                //Mettre en place la description
                $(cm).find(".jb-tqr-hview-a-tst-m").text(t__);
            }
            //*/
            
            var atxt = ds.m;
            atxt = $("<div/>").html(atxt).text();

            /*
             * [NOTE 22-04-16]
             *      Le code ci-dessous n'est pas sur à 100%
             */
            
            var ajca_o, ajca = ""+$(cm).data("ajcache")+"";
            ajca_o = ( typeof $(cm).data("ajcache") === "object" ) ? $(cm).data("ajcache") : JSON.parse(ajca);  
                
            var ustgs = ajca_o.ustgs;
            var hashs = ajca_o.hashs;

//            Kxlib_DebugVars([ajca_o.cmnid,"; HASH =>",hashs,"; USTGS =>",ustgs],true);

            //rtxt = RenderedText
            var rtxt = Kxlib_TextEmpow(atxt,ustgs,hashs,null,{
                "ena_inner_link" : {
//                    "local" : true, //DEV, DEBUG, TEST
                    "all"   : false,
                    "only"  : "fksa"
                },
                emoji : {
                    "size"          : 36,
                    "size_css"      : 20,
                    "position_y"    : 3
                }
            });
            
            //On ajoute le texte
            $(cm).find(".jb-tqr-hview-a-tst-m").text("").append(rtxt);
            
            /*
             * La description de CONTENT
             */
//            var tsm = $("<div/>").text(ds.tsm).text();
//            $(cm).find(".jb-tqr-hview-a-tst-m").text(tsm);
            
            return cm;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /*************************************************************************************************************************************************************************************************/
    /**************************************************************************************** LISTENERSS SCOPE ***************************************************************************************/
    /*************************************************************************************************************************************************************************************************/
    
    $(".jb-tqr-hview-c-h-h-ipt").keypress(function(e){
        if ( (e.which && e.which === 13) || (e.keyCode && e.keyCode === 13) ) { 
            Kxlib_PreventDefault(e);
            
            _f_Action(this,"search");
        }
    });
    
    $(".jb-tqr-snifr-wdw-scltp").on("my-scroll",function(e,std){
        //std : ScrollTopData
//        Kxlib_DebugVars(["CHECK >>> TOP (MySCROLL) : "+std]);
//        Kxlib_DebugVars(["CHECK >>> NEWTOP (MySCROLL) : "+(std+$(window).height())]);
//        Kxlib_DebugVars(["CHECK >>> HEIGHT : "+$(document).height()]);
        
        var nwstd = std+$(window).height();
        
        if ( $(".jb-tqr-hview-art-bmx").length && nwstd >= $(document).height()*0.8 ) {
//            Kxlib_DebugVars(["ACTION CHECK >>> GET_MORE !!!"]);
            _f_Action(this,"get_more");
        }
    });
    
    
    /*************************************************************************************************************************************************************************************************/
    /******************************************************************************************** INNIT SCOPE ****************************************************************************************/
    /*************************************************************************************************************************************************************************************************/
    
    _f_Init();
    
})();
