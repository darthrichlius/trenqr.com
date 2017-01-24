



/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/***********************************************************************************        ASIDE-RICH-BANNER : SUGGESTIONS         ***********************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/

(function(){
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** PROCESS SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_Action = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && !a ) ) {
                return;
            }
            
            var _a = ( KgbLib_CheckNullity($(x).data("action")) ) ? a : $(x).data("action"), ds; 
            switch (_a) {
                case "go-live" :
                        if ( !KgbLib_CheckNullity($(".jb-asd-rch-s-bla-cache").data("live")) && typeof $(".jb-asd-rch-s-bla-cache").data("live") === "object" && $(".jb-asd-rch-s-bla-cache").data("live").length ) {
                            ds = $(".jb-asd-rch-s-bla-cache").data("live");
                            _f_AddSgsH(ds);
                        }
                    break;
                case "go-bsof-h" :
                        if ( !KgbLib_CheckNullity($(".jb-asd-rch-s-bla-cache").data("bsof-h")) && typeof $(".jb-asd-rch-s-bla-cache").data("bsof-h") === "object" && $(".jb-asd-rch-s-bla-cache").data("bsof-h").length ) {
                            ds = $(".jb-asd-rch-s-bla-cache").data("bsof-h");
                            _f_AddSgsH(ds);
                        }
                    break;
                case "go-bsof-p" :
                        if ( !KgbLib_CheckNullity($(".jb-asd-rch-s-bla-cache").data("bsof-p")) && typeof $(".jb-asd-rch-s-bla-cache").data("bsof-p") === "object" && $(".jb-asd-rch-s-bla-cache").data("bsof-p").length ) {
                            ds = $(".jb-asd-rch-s-bla-cache").data("bsof-p");
                            _f_AddSgsH(ds);
                        }
                    break;
                default: 
                    return;
            }
            
            /*
             * ETAPE :
             *      On switch l'état des boutons
             */
            $(".jb-asd-rch-s-bla-fil.active").removeClass("active");
            $(x).addClass("active");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_PullSuggs = function () {
        try {
            
            /*
             * RAPPEL :
             *      Ce fichier est aussi utilisé au niveau de page qui ne gère pas le module TESTY
             */
            if ( ( $("div[s-id='TQR_GTPG_STGS']").length || $("div[s-id='TQR_GTPG_HVIEW']").length ) ) {
                return;
            }
            
            var s = $("<span/>");
            _f_Srv_PlSuggs(null,s);
            
            $(s).on("datasready",function(e,ds){
                if ( KgbLib_CheckNullity(ds) ) {
                    return;
                }
                
                /*
                 * ETAPE : 
                 *      On affiche les suggestions en fonction de la page et du type de données.
                 */
                if ( ds.page !== "FKSA" ) {
                    if ( !KgbLib_CheckNullity(ds.profils) ) {
                        _f_AddSgsP(ds.profils);
                    }
                    if ( !KgbLib_CheckNullity(ds.blabla) ) {
                       /*
                        * ETAPE :
                        *      On s'assure qu'on a acces à la zone de stockage
                        */
                        if ( $(".jb-asd-rch-s-bla-cache").length ) {
                             $(".jb-asd-rch-s-bla-cache").data("live",ds.blabla.live);
                             $(".jb-asd-rch-s-bla-cache").data("bsof-h",ds.blabla.bsofh);
                             $(".jb-asd-rch-s-bla-cache").data("bsof-p",ds.blabla.bsofp);
                             _f_AddSgsH(ds.blabla.live);
                        }
                    }
                    if ( !KgbLib_CheckNullity(ds.trends) ) {
                        _f_AddSgsT(ds.trends);
                    }
                    
                    /*
                     * [DEPUIS 11-06-16]
                     *      On ajuste la taille minimale de la zone en fonction de la HEIGHT de ASIDE le cas échéant
                     */
                    //csh = CenterSreenHeight
                    var hdr_csh = $(".jb-p-l-c-main").height();
                    var hdr_asdh = $(".jb-aside").height();
                    
                    if ( hdr_asdh > hdr_csh ) {
                        var nh = ( hdr_asdh + 50 ).toString();
                        $(".jb-p-l-c-main").css({
                            "min-height" : nh.concat("px")
                        });
                    }
                    
                } else {
//                    Kxlib_DebugVars([JSON.stringify(ds.trends)],true);
                    _f_AddSgsT(ds.trends,ds.page);
                }
                
            });
            
            $(s).on("operended",function(){
//                alert("operended");
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_AddSgsP = function (ds) {
        try {
            if ( KgbLib_CheckNullity(ds) ) {
                return;
            }
            
            $.each(ds,function(x,sd){
                if ( $(".jb-asd-rch-s-psg-lst[data-item='"+sd.uid+"']").length ) {
                    return true;
                }
                
                var m = _f_Vw_BldPflMdl(sd,x);
//                $(m).hide().appendTo(".jb-asd-rch-s-psg-lsts").fadeIn();
                $(m)
                    .hide()
                    .appendTo(".jb-asd-rch-s-psg-lsts")
                    .show();
            });
                
        } catch (ex) {
            alert(ex);
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_AddSgsH = function (ds) {
        try {
            if ( KgbLib_CheckNullity(ds) ) {
                return;
            }
            
            /*
             * ETAPE :
             *      On vide les précedents résultats
             */
            $(".jb-asd-rch-s-bla-lst-mx").find(".jb-asd-rch-s-bla-kw-mx").remove();
            
            $.each(ds,function(x,sd){
                var m = _f_Vw_BldKwMdl(sd);
                
                $(m).hide().appendTo(".jb-asd-rch-s-bla-lst-mx").fadeIn();
            });
                
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_AddSgsT = function (ds,pg) {
        try {
            if ( KgbLib_CheckNullity(ds) ) {
                return;
            }
            
            if ( KgbLib_CheckNullity(pg) || pg !== "FKSA" ) {
                $.each(ds,function(x,sd){
                    if ( $(".jb-asd-rch-s-tsg-lst[data-item='"+sd.uid+"']").length ) {
                        return true;
                    }
                    //il = IsLast
                    il = ( x === (ds.length-1) ) ? true : false;
                    var m = _f_Vw_BldTrdMdl(sd,x,il);
                    m = _f_Rbd_BldTrdMdl(m);
                    $(m).hide().appendTo(".jb-asd-rch-s-tsg-lsts").fadeIn();
                });
            } else {
                $.each(ds,function(x,sd){
                    if ( $(".jb-fksa-smr-a-bmx[data-item='"+sd.uid+"']").length ) {
                        return true;
                    }
                    //il = IsLast
                    il = ( x === (ds.length-1) ) ? true : false;
                    var m = _f_Vw_BldTrdMdlFk(sd,x,il);
                    
                    $(m).hide().appendTo(".jb-fksa-smr-body").fadeIn();
                });
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Rbd_BldTrdMdl = function (m) {
        try {
            if ( KgbLib_CheckNullity(m) ) {
                return;
            }
            
            $(m).find(".jb-asd-rch-s-tsg-cov-x").off("hover").hover(function(e){
                if ( $(this).hasClass("hover") ) {
                    $(this).removeClass("hover");
                    $(this).find(".jb-subscribers-mx, .jb-publications-mx").addClass("this_hide");
                } else {
                    $(this).find(".jb-subscribers-mx, .jb-publications-mx").removeClass("this_hide");
                    $(this).addClass("hover");
                }
            });
            
            return m;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** AUTO SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    setTimeout(function(){
        _f_PullSuggs();
    },500);
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** SERVER SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _Ax_PlSuggs = Kxlib_GetAjaxRules("TQR_SUGG_GETALL");
    var _f_Srv_PlSuggs = function(mode,s) {
        if ( KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
//            alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    
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
                } else if ( !KgbLib_CheckNullity(datas.return) && ( !KgbLib_CheckNullity(datas.return.profils) | !KgbLib_CheckNullity(datas.return.blabla) | !KgbLib_CheckNullity(datas.return.trends) ) ) {
                    var ds = [datas.return];
                    $(s).trigger("datasready",ds);
                } else {
                    $(s).trigger("operended");
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
            "urqid": _Ax_PlSuggs.urqid,
            "datas": {
                "mode"  : (! KgbLib_CheckNullity(mode) ) ? mode : null,
                "cu"    : curl
            }
        };
        
        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlSuggs.url, wcrdtl : _Ax_PlSuggs.wcrdtl });
    };
    
    /*******************************************************************************************************************************************************************/
    /**************************************************************************** VIEW SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_Vw_BldPflMdl = function (d,ix) {
        try {
            if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(ix) ) {
                return;
            }
//            Kxlib_DebugVars([JSON.stringify(d)],true);
            /*
             * TABLE DE DONNÉÉS 
             * uid  :   L'identifiant externe du compte présenté
             * ufn  :   Le nom complet
             * upsd :   Le pseudo
             * uppc :   L'image de profil
             */
            
            var m = "<li class=\"asd-rch-s-psg-lst jb-asd-rch-s-psg-lst\" data-item=\"\">";
            m += "<div class=\"asd-rch-s-psg-pfl-bmx\">";
            m += "<div class=\"asd-rch-s-psg-pfl-l\">";
            m += "<a class=\"asd-rch-s-psg-pfl-hfr jb-asd-rch-s-psg-pfl-hfr\" href=\"\">";
            m += "<img class=\"asd-rch-s-psg-pfl-i jb-asd-rch-s-psg-pfl-i\" src=\"\" alt=\"\" height=\"50\" width=\"50\"/>";
            m += "<span class=\"asd-rch-s-psg-pfl-i-fd\"></span>";
            m += "</a>";
            m += "</div>";
            m += "<div class=\"asd-rch-s-psg-pfl-r\">";
            m += "<div>";
            m += "<a class=\"asd-r-s-psg-pfl-psd jb-asd-r-s-psg-pfl-psd\" href=\"\"></a>";
            m += "</div>";
            m += "<div>";
            m += "<span class=\"asd-r-s-psg-pfl-fn jb-asd-r-s-psg-pfl-fn\"></span>";
            m += "</div>";
            m += "</div>";
            m += "</div>";
            m += "</li>";
            m = $.parseHTML(m);
            
            $(m).data("item",d.uid);
            $(m).find(".jb-asd-rch-s-psg-pfl-hfr").attr("href","/"+d.upsd);
            var f = ( ix%2 === 0 ) ? "even" : "odd", alt = "";
            $(m).find(".jb-asd-rch-s-psg-pfl-i").addClass(f).attr("src",d.uppc).attr("alt",alt.concat(d.ufn," (@",d.upsd,")"));
            $(m).find(".jb-asd-r-s-psg-pfl-psd").attr("href","/"+d.upsd).text("@".concat(d.upsd));
            $(m).find(".jb-asd-r-s-psg-pfl-fn").text(d.ufn);
            
            return m;
            
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Vw_BldKwMdl = function (d) {
        try {
            if ( KgbLib_CheckNullity(d)) {
                return;
            }
//            Kxlib_DebugVars([JSON.stringify(d)],true);
            /*
             * TABLE DE DONNÉÉS 
             */
            
            /*
             * [DEPUIS 09-12-15]
             *      Suppression des caractères du type &rlm;, &lrm;
             */
            d = d.replace(/&lrm;|\u200e|&rlm;|\u200f|\u202e|\u202d|\u202c|\u202b|\u202a/gi,"");
            
            var m = "<li class=\"asd-rch-s-bla-kw-mx jb-asd-rch-s-bla-kw-mx\">";
            m += "<a class=\"asd-rch-s-bla-kw jb-asd-rch-s-bla-kw\" href=\"\"></a>";
            m += "</li>";
            m = $.parseHTML(m);
            
            var lk = "/hview/q=".concat(d).concat("&src=hash");
            var kw = "#".concat(d);
            
            $(m).data("item",d.uid);
            $(m).find(".jb-asd-rch-s-bla-kw").attr("href",lk).text(kw);
            
            return m;
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Vw_BldTrdMdl = function (d,ix,il) {
        try {
            if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(ix) ) {
                return;
            }
//            Kxlib_DebugVars([JSON.stringify(d)],true);
            /*
             * TABLE DE DONNÉÉS 
             * tid      :   L'identifiant externe de la Tendance présentée
             * cov_w    :   La largeur de l'image de couverture
             * cov_h    :   La hauteur de l'image de couverture
             * cov_t    :   La position TOP de l'image de couverture
             * cov_rp   :   L'url physique l'image de couverture
             * ttle     :   Le titre de la Tendance
             * tdsc     :   La description de la Tendance
             * tctm     :   Le timestamp de création
             * tlk      :   Le lien de la Tendance
             */
            
            var m = "<li class=\"asd-rch-s-tsg-lst jb-asd-rch-s-tsg-lst\" data-item=\"\">";
            m += "<div>";
            m += "<div class=\"asd-rch-s-tsg-hdr\">";
            m += "<a class=\"asd-rch-s-tsg-cov-mx jb-asd-rch-s-tsg-cov-mx\" href=\"\">";
            m += "<img class=\"asd-rch-s-tsg-cov-i jb-asd-rch-s-tsg-cov-i\" src=\"\" alt=\"\"width=\"255\" />";
            m += "<span class=\"asd-rch-s-tsg-cov-i-fd\"></span>";
            /*
            m += "<div class=\"asd-rch-s-tsg-cov-x jb-asd-rch-s-tsg-cov-x\">";
            m += "<div class=\"publications-mx jb-publications-mx this_hide\"><span class=\"stats\">0</span> publications</div>";
            m += "<div class=\"subscribers-mx jb-subscribers-mx this_hide\"><span class=\"stats\">0</span> abonnées</div>";
            m += "</div>";
            //*/
            m += "</a>";
            m += "</div>";
            m += "<div class=\"asd-rch-s-tsg-bdy\" >";
            m += "<div class=\"asd-rch-s-tsg-tle-mx\">";
            m += "<a class=\"asd-rch-s-tsg-tle jb-asd-rch-s-tsg-tle\" href=\"\"></a>";
            m += "</div>";
            m += "<div class=\"asd-rch-s-tsg-dsc-mx\">";
            m += "<a class=\"asd-rch-s-tsg-dsc jb-asd-rch-s-tsg-dsc\" href=\"\"></a>";
            m += "</div>";
            m += "</div>";
            m += "</div>";
            m += "</li>";
            m = $.parseHTML(m);
            
            var f = ( il ) ? "last" : "";
            $(m).addClass(f).data("item",d.tid);
            $(m).find(".jb-asd-rch-s-tsg-cov-mx").attr("href",d.tlk);
            if (! KgbLib_CheckNullity(d.tcvpc.cov_rp) ) {
                $(m).find(".jb-asd-rch-s-tsg-cov-i").attr("src",d.tcvpc.cov_rp).attr("alt",d.ttle);
                /*
                 * ETAPE :
                 *      On effectue les calculs qui permettront de retrouver la même proportion que la couverture de la Tendance sur sa page.
                 */
//                var cf = 100/260;
//                var t = (d.tcvpc.cov_t*cf)+5;
                
                var t = parseInt((255/840)*d.tcvpc.cov_t)-2
                
//                console.log(parseInt(d.tcvpc.cov_w),cf,d.tcvpc.cov_t,t);
                $(m).find(".jb-asd-rch-s-tsg-cov-i").css("top",t);
                        
            }
            $(m).find(".jb-asd-rch-s-tsg-tle").attr("title",d.ttle).attr("href",d.tlk).text(d.ttle);
            $(m).find(".jb-asd-rch-s-tsg-dsc").attr("title",d.tdsc).attr("href",d.tlk).text(d.tdsc);
            
            return m;
            
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
        
    };
    
    var _f_Vw_BldTrdMdlFk = function (d,ix) {
        try {
            if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(ix) ) {
                return;
            }
//            Kxlib_DebugVars([JSON.stringify(d)],true);
            /*
             * TABLE DE DONNÉÉS 
             * tid      :   L'identifiant externe de la Tendance présentée
             * cov_w    :   La largeur de l'image de couverture
             * cov_h    :   La hauteur de l'image de couverture
             * cov_t    :   La position TOP de l'image de couverture
             * cov_rp   :   L'url physique l'image de couverture
             * ttle     :   Le titre de la Tendance
             * tdsc     :   La description de la Tendance
             * tctm     :   Le timestamp de création
             * tlk      :   Le lien de la Tendance
             * oid      :   L'identifiant externe du propriétaire de la Tendance
             * ofn      :   Le nom complet externe du propriétaire de la Tendance
             * opsd     :   Le pseudo externe du propriétaire de la Tendance
             */
            
            var m = "<article class=\"fksa-smr-articles-bmx jb-fksa-smr-a-bmx\" data-item=\"\">";
            m += "<div class=\"fksa-smr-art-trd-mx\">";
            m += "<div class=\"fksa-smr-art-trd-cvr\">";
            m += "<a class=\"fksa-smr-art-trd-cvr-hrf jb-fksa-smr-art-trd-cvr-hrf\" href=\"\">";
            m += "<span class=\"fksa-smr-art-trd-cvr-fd\"></span>";
            m += "<img class=\"fksa-smr-art-trd-cvr-i jb-fksa-smr-art-trd-cvr-i\" src=\"\" width=\"280px\" alt=\"\" />";
            m += "</a>";
            m += "</div>";
            m += "<div class=\"fksa-smr-art-trd-hdr\">";
            m += "<header class=\"fksa-smr-art-trd-hdr-tle-mx\">";
            m += "<h4><a class=\"fksa-smr-art-trd-hdr-tle jb-fksa-smr-art-trd-hdr-tle\" href=\"\"></a></h4>";
            m += "</header>";
            m += "<div class=\"fksa-smr-art-trd-hdr-dsc-mx\">";
            m += "<a class=\"fksa-smr-art-trd-hdr-dsc jb-fksa-smr-art-trd-hdr-dsc\" href=\"\"></a>";
            m += "</div>";
            m += "<footer class=\"fksa-smr-art-trd-hdr-xtra-mx\">";
            m += "<a class=\"fksa-smr-art-trd-hdr-xt-ownr jb-fksa-smr-art-trd-hdr-xt-ownr\" href=\"\">";
            m += "<span class=\"fksa-smr-art-trd-hdr-xt-psd jb-fksa-smr-art-trd-hdr-xt-psd\" ></span>";
            m += "</a>";
            m += "<span class=\"fksa-smr-art-trd-hdr-xt-time jb-fksa-smr-art-trd-hdr-xt-tm\">";
            m += "<span class=\"kxlib_tgspy fksa-tm\" data-tgs-crd=\'\' data-tgs-dd-atn=\'\' data-tgs-dd-uut=\'\'>";
            m += "<span class=\'tgs-frm\'></span>";
            m += "<span class=\'tgs-val\'></span>";
            m += "<span class=\'tgs-uni\'></span>";
            m += "</span>";
            m += "</span>";
            m += "</footer>";
            m += "</div>";
            m += "</div>";
            m += "</article>";
    
            m = $.parseHTML(m);
            
            $(m).data("item",d.tid);
            $(m).find(".jb-fksa-smr-art-trd-cvr-hrf, .jb-fksa-smr-art-trd-hdr-tle, .jb-fksa-smr-art-trd-hdr-dsc").attr("href",d.tlk);
            if (! KgbLib_CheckNullity(d.tcvpc.cov_rp) ) {
                $(m).find(".jb-fksa-smr-art-trd-cvr-i").load(function(){
                   /*
                    * ETAPE :
                    *      On effectue les calculs qui permettront de retrouver la même proportion que la couverture de la Tendance sur sa page.
                    */
                   var cf = this.height/d.tcvpc.cov_h;
   //                var cf = 140/260;
                   var t = (d.tcvpc.cov_t*cf)+10;
   //                console.log($(m).find(".jb-fksa-smr-art-trd-cvr-i").height(),d.tcvpc.cov_h,cf,d.tcvpc.cov_t,t);
                   $(m).find(".jb-fksa-smr-art-trd-cvr-i").css("top",t);
                }).attr("src",d.tcvpc.cov_rp).attr("alt",d.ttle);
                
            }
            $(m).find(".jb-fksa-smr-art-trd-hdr-tle").attr("title",d.ttle).text(d.ttle);
            $(m).find(".jb-fksa-smr-art-trd-hdr-dsc").text(d.tdsc);
            
            $(m).find(".kxlib_tgspy").data("tgs-crd",d.tctm);
            
            /*
             * ONWER
             */
            var utle = d.town.ofn.concat(" (@",d.town.opsd,")");
            $(m).find(".jb-fksa-smr-art-trd-hdr-xt-ownr").attr("title",utle).attr("href","/".concat(d.town.opsd));
            $(m).find(".jb-fksa-smr-art-trd-hdr-xt-psd").text("@".concat(d.town.opsd));
            
            return m;
            
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
        
    };
    /*******************************************************************************************************************************************************************/
    /************************************************************************** LISTENERS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    $(".jb-asd-rch-s-tsg-cov-x").off("hover").hover(function(e){
        if ( $(this).hasClass("hover") ) {
            $(this).removeClass("hover");
            $(this).find(".jb-subscribers-mx, .jb-publications-mx").addClass("this_hide");
        } else {
            $(this).find(".jb-subscribers-mx, .jb-publications-mx").removeClass("this_hide");
            $(this).addClass("hover");
        }
    });
    
    $(".jb-asd-rch-s-bla-fil").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
})();


/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/***********************************************************************************          ASIDE-RICH-BANNER : TESTIMONY         ***********************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/

//<editor-fold defaultstate="collapsed" desc="Fermer">

(function(){
    
    var _xhr_tsty_get, _xhr_tsty_add, _xhr_tsty_del, _xhr_cnfg_set, _xhr_cnfg_get, _xhr_cnfg_ax;
    var _VWR;
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** PROCESS SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_Gdf = function () {
        var dt = {
            "addMaxLn"              : 1000,
            "ini_write_auth_vls"    : ["ONLY_FRD_N_THFRD","ONLY_FRD","EVRBDY"],
            "ini_read_auth_vls"     : ["TQR_INSD","EVRBDY"],
            "pstPvwLn"              : 200
        }; 
        
        return dt;
    };
    
    
    var _f_Action = function (x,a) {
        try {
            
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && !a ) ) {
                return;
            }
            
            var _a = ( a ) ? a : $(x).data("action");
            switch (_a) {
                case "errbox-clz" :
                        $(".jb-tqr-testy-hdr-erbx").addClass("this_hide");
                        $(".jb-tqr-testy-hdr-err-tmx").text("");
                    break;
                case "post-gotopt" :
                        _f_PostGTO(x);
                    break;
                case "post-reveal" :
                        _f_PostRvl(x);
                    break;
                case "post-add" :
                        _f_PostAdd(x);
                    break;
                case "post-del-start" :
                case "post-del-final-yes" :
                case "post-del-final-no" :
                        _f_PostDel(x,_a);
                    break;
                case "post-loadoldr" :
                        _f_PlTsts(x,_a);
                    break;
                //*
                case "post-pin-start" :
                case "post-pin-final-yes" :
                case "post-pin-final-no" :
                case "post-unpin-start" :
                case "post-unpin-final-yes" :
                case "post-unpin-final-no" :
                        _f_PostPin(x,_a);
                    break;
                //*/
                case "post-like" :
                case "post-unlike" :
                        _f_LikeAct(x,_a);
                    break;
                case "post-react-open" :
                        _f_ReactAct(x,_a);
                    break;
                case "configure_access" :
                        _f_SwCfg(x);
                    break;
                case "ini-write-deny-add" :
                case "ini-read-deny-add" :
                        _f_iniUsrAdd(x,_a);
                    break;
                case "ini-write-deny-rmv" :
                case "ini-read-deny-rmv" :
                        _f_iniUsrRmv(x,_a);
                    break;
                case "configure_reset" :
                        _f_iniRst(x);
                    break;
                case "configure_save" :
                        _f_iniSave(x);
                    break;
//                case "tlkb-sprt-clz" :
//                        _f_Uqv_Io(x,_a);
//                    break;
                case "tlkb-pre-message" :
                        _f_PreMsg(x,_a);
                    break;
                default :
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /*********************************************************************************************************************************************************************************************************/
    /****************************************************************************************** CONFIGURATION SCOPE ******************************************************************************************/
    
    var _f_SwCfg = function (x,sh) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if (! ( KgbLib_CheckNullity(_xhr_cnfg_get) && KgbLib_CheckNullity(_xhr_cnfg_set) ) ) {
                return;
            }
            
            /*
             * ETAPE :
             *      Wait panel
             */
            $(".jb-tqr-tsty-cfg-wt-pnl").removeClass("this_hide");
            
            
            if ( sh | $(".jb-tqr-testy-config-mx").hasClass("this_hide") ) {
                sh = true;
                $(".jb-tqr-testy-config-mx").removeClass("this_hide");
            } else {
                sh = false;
                $(".jb-tqr-testy-config-mx").addClass("this_hide");
            }
            
            /*
             * ETAPE :
             *      On vérifie si le formulaire a déjà des données.
             *      Dans ce cas, on les ajoute en attendant la mise à jour
             */
            if ( $(".jb-tqr-testy-config-mx").data("inis") ) {
                _f_ShwInis();
            }
            
            var s = $("<span/>");
            
            _f_Srv_CnfgGet(x,s);
            
            $(s).on("datasready",function(e,ds){
                if ( KgbLib_CheckNullity(ds) ) {
                    return;
                }
                
                //                Kxlib_DebugVars([JSON.stringify(ds[0])],true);
                
                _f_ShwInis(ds[0]);
                
                /*
                 * ETAPE :
                 *      Wait panel
                 */
                $(".jb-tqr-tsty-cfg-wt-pnl").addClass("this_hide");
                
                _xhr_cnfg_get = null;
            });
            
            $(s).on("operended",function(e,d){
                /*
                 * ETAPE :
                 *      Wait panel
                 */
                $(".jb-tqr-tsty-cfg-wt-pnl").addClass("this_hide");
                
                _xhr_cnfg_get = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ShwInis = function (ds) {
        try {
            if ( KgbLib_CheckNullity(ds) && KgbLib_CheckNullity($(".jb-tqr-testy-config-mx").data("inis")) ) {
                return;
            }
            
            var innis = ( KgbLib_CheckNullity(ds) ) ? JSON.parse($(".jb-tqr-testy-config-mx").data("inis")) : ds;
            
            $.each(innis,function(ik,iv){
                //                console.log(ik,JSON.stringify(iv));
                switch (ik) {
                    case "iwa" :   
                        $(".jb-tqr-tsty-cfg-ini[name='ini-write-auth']").removeProp('checked');
                        $(".jb-tqr-tsty-cfg-ini[name='ini-write-auth']").filter("[value='"+iv+"']").prop('checked', true);
                        break
                    case "ira" :   
                        $(".jb-tqr-tsty-cfg-ini[name='ini-read-auth']").removeProp('checked');
                        $(".jb-tqr-tsty-cfg-ini[name='ini-read-auth']").filter("[value='"+iv+"']").prop('checked', true);
                        break;
                    case "ird" : 
                    case "iwd" :   
                        _f_iniUsrAddSets(ik,iv);
                        break;
                    default :
                        return;
                }
            });
            
            /*
             * ETAPE : 
             *      Mise à jour des données
             */
            $(".jb-tqr-testy-config-mx").data("inis",JSON.stringify(innis));
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_iniSave = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            if (! ( KgbLib_CheckNullity(_xhr_cnfg_get) && KgbLib_CheckNullity(_xhr_cnfg_set) ) ) {
                return;
            }
            
            /*
             * ETAPE :
             *      Wait panel
             */
            $(".jb-tqr-tsty-cfg-wt-pnl").removeClass("this_hide");
            
            /*
             * ETAPE :  
             *      Récupérer les ini disponibles
             */
            var kv = _f_chkInis();
            if (! ( !KgbLib_CheckNullity(kv[0]) && ( kv[0].hasOwnProperty("type") && kv[0].type === "kvs" ) ) ) {
                return;
            }
            var ins = kv[0].datas;
            //            Kxlib_DebugVars([JSON.stringify(kv)],true);
            //            return;
            
            /*
             * ETAPE :
             *      On contacte le serveur
             */
            
            var s = $("<span/>");
            _f_Srv_CnfgSet(ins.ini_write_auth,ins.ini_write_deny,ins.ini_read_auth,ins.ini_read_deny,x,s);
            
            $(s).on("datasready",function(e,ds){
                if ( KgbLib_CheckNullity(ds) ) {
                    return;
                }
                
                //                Kxlib_DebugVars([JSON.stringify(ds)],true);
                
                _f_ShwInis(ds[0]);
                
                /*
                 * ETAPE :
                 *      Wait panel
                 */
                $(".jb-tqr-tsty-cfg-wt-pnl").addClass("this_hide");
                
                $(x).data("lk",0);
                _xhr_cnfg_set = null;
            });
            
            $(s).on("operended",function(e,ds){
                /*
                 * ETAPE :
                 *      Wait panel
                 */
                $(".jb-tqr-tsty-cfg-wt-pnl").addClass("this_hide");
                
                $(x).data("lk",0);
                _xhr_cnfg_set = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_chkInis = function () {
        try {
            
            var errs = 0, kv = {}, fnl = [];
            /*
             * ETAPE :
             *      On controle quel groupe peut écrire
             */
            //            console.log($(".jb-tqr-tsty-cfg-ini[name='ini-write-auth']").filter(":checked").length === 1, $(".jb-tqr-tsty-cfg-ini[name='ini-write-auth']").filter(":checked").val(), $.inArray($(".jb-tqr-tsty-cfg-ini[name='ini-write-auth']").filter(":checked").val(),_f_Gdf().ini_write_auth_vls) !== -1);
            if (! ( $(".jb-tqr-tsty-cfg-ini[name='ini-write-auth']").filter(":checked") && $(".jb-tqr-tsty-cfg-ini[name='ini-write-auth']").filter(":checked").length === 1 
                    && $(".jb-tqr-tsty-cfg-ini[name='ini-write-auth']").filter(":checked").val() && $.inArray($(".jb-tqr-tsty-cfg-ini[name='ini-write-auth']").filter(":checked").val(),_f_Gdf().ini_write_auth_vls) !== -1 ) )
            {
                errs++;
            } else {
                kv["ini_write_auth"] = $(".jb-tqr-tsty-cfg-ini[name='ini-write-auth']").filter(":checked").val();
            }
            
            /*
             * ETAPE :
             *      On controle qui ne peut pas écrire (individuellement)
             */
            if ( $(".jb-tqr-tsty-cfg-sctn[data-scp='ini-write-deny'").find(".jb-tqr-tsty-cfg-ini-uz-l-mx").length ) {
                var u__ = [];
                $.each($(".jb-tqr-tsty-cfg-sctn[data-scp='ini-write-deny'").find(".jb-tqr-tsty-cfg-ini-uz-l-mx"),function(x,ubx){
                    var vl = $(ubx).find(".jb-tqr-tsty-cfg-ini-uhrf").text();
                    var rgx = /^@?((?=.*[a-z])[\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})$/i;
                    //                    console.log('ini-write-deny',vl, rgx.test(vl), $.inArray(vl,u__) === -1);
                    
                    var pcs = vl.match(rgx);
                    if (! ( vl && pcs && $.inArray(vl,u__) === -1 ) ) {
                        //                    if (! ( vl && rgx.test(vl) && $.inArray(vl,u__) === -1 ) ) {
                        $(ubx).remove();
                        return true;
                    }
                    u__.push(pcs[1]);
                    //                    u__.push(vl);
                });
                kv["ini_write_deny"] = u__;
                //                Kxlib_DebugVars(["ini-write-deny",JSON.stringify(u__)],true);
                if (! u__.length ) {
                    errs++;
                }
            }
            
            /*
             * ETAPE :
             *      On controle quel groupe peut lire
             */
            //            console.log($(".jb-tqr-tsty-cfg-ini[name='ini-read-auth']").filter(":checked").length === 1, $(".jb-tqr-tsty-cfg-ini[name='ini-read-auth']").filter(":checked").val(), $.inArray($(".jb-tqr-tsty-cfg-ini[name='ini-read-auth']").filter(":checked").val(),_f_Gdf().ini_read_auth_vls) !== -1);
            if (! ( $(".jb-tqr-tsty-cfg-ini[name='ini-read-auth']").filter(":checked") && $(".jb-tqr-tsty-cfg-ini[name='ini-read-auth']").filter(":checked").length === 1 
                    && $(".jb-tqr-tsty-cfg-ini[name='ini-read-auth']").filter(":checked").val() && $.inArray($(".jb-tqr-tsty-cfg-ini[name='ini-read-auth']").filter(":checked").val(),_f_Gdf().ini_read_auth_vls) !== -1 ) )
            {
                errs++;
            } else {
                kv["ini_read_auth"] = $(".jb-tqr-tsty-cfg-ini[name='ini-read-auth']").filter(":checked").val();
            }
            
            /*
             * ETAPE :
             *      On controle qui ne peut pas lire (individuellement)
             */
            if ( $(".jb-tqr-tsty-cfg-sctn[data-scp='ini-read-deny'").find(".jb-tqr-tsty-cfg-ini-uz-l-mx").length ) {
                var u__ = [];
                $.each($(".jb-tqr-tsty-cfg-sctn[data-scp='ini-read-deny'").find(".jb-tqr-tsty-cfg-ini-uz-l-mx"),function(x,ubx){
                    var vl = $(ubx).find(".jb-tqr-tsty-cfg-ini-uhrf").text();
                    var rgx = /^@?((?=.*[a-z])[\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})$/i;
                    //                    console.log('ini-read-deny',vl, rgx.test(vl), $.inArray(vl,u__) === -1);
                    
                    var pcs = vl.match(rgx);
                    if (! ( vl && pcs && $.inArray(vl,u__) === -1 ) ) {
                        $(ubx).remove();
                        return true;
                    }
                    u__.push(pcs[1]);
                    //                    u__.push(vl);
                });
                kv["ini_read_deny"] = u__;
                //                Kxlib_DebugVars(["ini-read-deny",JSON.stringify(u__)],true);
                if (! u__.length ) {
                    errs++;
                }
            }
            
            /*
             * ETAPE :
             *      On vérifie s'il y a des erreurs
             */
            var fnl=[];
            if ( errs ) {
                fnl.push({
                    "type" : "err",
                    "datas" : errs
                });
            } else {
                fnl.push({
                    "type" : "kvs",
                    "datas" : kv
                });
            }
            
            return fnl;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_iniRst = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if (! ( KgbLib_CheckNullity(_xhr_cnfg_get) && KgbLib_CheckNullity(_xhr_cnfg_set) ) ) {
                return;
            }
            
            /*
             * ETAPE :  
             *      On vérifie qu'il y a des données pré-stockées
             */
            var ds = $(".jb-tqr-testy-config-mx").data("inis");
            if ( KgbLib_CheckNullity(ds) ) {
                return;
            }
            
            //            Kxlib_DebugVars([JSON.stringify(JSON.parse(ds)),""],true);
            
            /*
             * ETAPE :
             *      On lance l'opération de mise à jour  
             */
            _f_ShwInis();
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_iniUsrRmv = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            var elt = $(x).closest(".jb-tqr-tsty-cfg-ini-uz-l-mx");
            
            $(elt).remove();
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_iniUsrAddSets = function (sc,ds) {
        //sc : SCope
        try {
            if ( KgbLib_CheckNullity(sc) ) {
                return;
            }
            
            var bmx, lst;
            switch (sc) {
                case "iwd" :
                    bmx = $(".jb-tqr-tsty-cfg-sctn[data-scp='ini-write-deny']");
                    break;
                case "ird" :
                    bmx = $(".jb-tqr-tsty-cfg-sctn[data-scp='ini-read-deny']");
                    break;
                default :
                    return;
            }
            
            if (! ( $(bmx).length && $(bmx).find(".jb-tqr-tsty-cfg-ini-usr-lsts").length ) ) {
                return;
            }
            lst = $(bmx).find(".jb-tqr-tsty-cfg-ini-usr-lsts");
            
            /*
             * ETAPE :
             *      On supprime les anciennes valeurs.
             *      RAPPEL : La méthode peut être appelée dans le seul but de supprimer les valeurs.
             */
            $(lst).find(".jb-tqr-tsty-cfg-ini-uz-l-mx").remove();
            
            if ( KgbLib_CheckNullity(ds) ) {
                return;
            }
            
            $.each(ds,function(x,iv){
                if ( $(".jb-tqr-tsty-cfg-ini-uz-l-mx[data-item='"+iv.uid+"']").length ) {
                    return true;
                }
                
                /*
                 * ETAPE :
                 *      On ajoute le modèle
                 */
                var m = _f_Vw_BldIniUsrMdl(iv,null);
                $(m).hide().appendTo(lst).fadeIn();
                
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_iniUsrAdd = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            var bmx = $(x).closest(".jb-tqr-tsty-cfg-sctn"), lst = $(bmx).find(".jb-tqr-tsty-cfg-ini-usr-lsts");
            if ( $(bmx).length && $(lst).length) {
                var $ipt = $(bmx).find(".jb-tqr-tsty-cfg-ini-ipt");
                /*
                 * ETAPE :
                 *      On vérifie la validité du texte
                 */
                var rgx = /^(?=.*[a-z])[\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20}$/i;
                if ( !$ipt.val() || !rgx.test($ipt.val()) ) {
                    return;
                }
                var vl = $ipt.val();
                
                /*
                 * ETAPE :
                 *      On ajoute le modèle
                 */
                var m = _f_Vw_BldIniUsrMdl(null,vl);
                $(m).hide().appendTo(lst).fadeIn();
                
                /*
                 * ETPAE :
                 *      On réinit
                 */
                $ipt.val("");
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /*********************************************************************************************************************************************************************************************************/
    /******************************************************************************************** TESTIMONY SCOPE ********************************************************************************************/
    
    var _f_PreMsg = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if (! $(".jb-tqr-tsty-art-txar").val() ) {
                var txt = $(".jb-tqr-tsty-add-tle-prems option:selected").text();
                $(".jb-tqr-tsty-art-txar").val(txt.concat(" "));
                
                /*
                 * [DEPUIS 21-06-16]
                 *      Permet de sélectionner de nouveau un élément déjà sélectionné.
                 *      Il s'agit d'un WORKAROUND ...
                 */
                $(".jb-tqr-tsty-add-tle-prems option:selected, .jb-tqr-tsty-add-tle-prems option[selected=selected]").removeAttr("selected");
                $(".jb-tqr-tsty-add-tle-prems option[value='-1']").attr("selected","true");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_PostAdd = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            var txar = $(".jb-tqr-tsty-art-txar"), vl = $(txar).val();
            var rgx = /^[\s\b\t\n\r]+$/;
            if (! ( vl.length && !rgx.test(vl) && vl.length <= _f_Gdf().addMaxLn ) ) {
                $(x).data("lk",0);
                return;
            }
            
            var ref = _f_GetRef("F");
            if ( typeof(ref) === "undefined" ) {
                $(x).data("lk",0);
                return;
            }
            
            var s = $("<span/>");
            _f_Srv_AddTsty(vl,ref.pi,ref.pt,x,s);
            
            $(txar).val("");
            
            $(s).on("datasready",function(e,ds){
                if ( KgbLib_CheckNullity(ds) ) {
                    return;
                }
                
                if ( ds.ird ) {
                    $(".jb-tqr-tsty-add-bmx").remove();
                    $(".jb-tqr-tsty-art-lsts").remove();
                } else {
                    $(".jb-tqr-tsty-art-lsts").removeClass("this_hide");
                }
                
                if ( ds.iwd ) {
                    $(".jb-tqr-tsty-add-bmx").remove();
                } else {
                    $(".jb-tqr-tsty-add-bmx").removeClass("this_hide");
                }
                
                
                //                Kxlib_DebugVars([JSON.stringify(ds)],true);
                //                return;
                
                _f_AddTsts(ds.tds,"ADD");
                
                _f_None();
                
                if ( ds.tds.length >= 3 ) {
                    _f_LdMr(true);
                }
                
                $(x).data("lk",0);
                _xhr_tsty_add = null;
            });
            
            $(s).on("operended",function(e,ds){
                //                Kxlib_DebugVars([JSON.stringify(ds)],true);
                /*
                 * ETAPE : 
                 *      On justifie pourquoi il y a un problème
                 */
                if ( KgbLib_CheckNullity(ds.tds) && !( ds.tds > 0 ) ) {
                    $(".jb-tqr-testy-hdr-erbx").removeClass("this_hide");
                    var m_ = Kxlib_getDolphinsValue("TQR_TST_YOU_NEED_QC");
                    $(".jb-tqr-testy-hdr-err-tmx").text(m_);
                }
                
                if ( ds.ird ) {
                    $(".jb-tqr-tsty-add-bmx").remove();
                    $(".jb-tqr-tsty-art-lsts").remove();
                } 
                if ( ds.iwd ) {
                    $(".jb-tqr-tsty-add-bmx").remove();
                } 
                
                $(x).data("lk",0);
                _xhr_tsty_add = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_AddTsts = function (ds,dr) {
        try {
            if ( KgbLib_CheckNullity(ds) ) {
                return;
            }
            
            $(".jb-tqr-tsty-art-lsts").removeClass("this_hide");
            
            var dir = ( KgbLib_CheckNullity(dr) ) ? "FST" : dr;
            
            if ( $.inArray(dir,["FST","TOP"]) !== -1 ) {
                ds.reverse();
            }
            
//            Kxlib_DebugVars([JSON.stringify(ds),dir],true);
            
            $.each(ds,function(x,td){
                if ( $(".jb-tqr-tsty-art-bmx[data-item='"+td.i+"']").length ) {
                    return true;
                }
                
                var m = _f_Vw_BldTstyMdl(td,x);
                if ( $.inArray(dir,["FST","ADD","TOP"]) !== -1 ) {
                    /*
                     * [DEPUIS 13-12-15]
                     *      Permet de gérer le cas des mots EPINGLE
                     */
                    if ( dir === "ADD" && $(".jb-tqr-tsty-art-bmx[data-isp=1]:first").length ) {
                        $(m).hide().insertAfter(".jb-tqr-tsty-art-bmx[data-isp=1]:first").fadeIn();
                    } else if ( dir === "FST" && $(".jb-tqr-tsty-art-bmx[data-isp=1]:first").length ) {
                        /*
                         * NOTE :
                         *      Ce cas est possible par exempe : 
                         *          (1) Si l'utilisateur supprime les MOTs et qu'il ne garde que celui épinglé
                         */
                        $(m).hide().insertAfter(".jb-tqr-tsty-art-bmx[data-isp=1]:first").fadeIn();
                    } else {
                        $(m).hide().prependTo(".jb-tqr-tsty-art-ls-arts").fadeIn();
                    }
                } else {
                    $(m).hide().appendTo(".jb-tqr-tsty-art-ls-arts").fadeIn();
                }
            });
            
            $(".jb-tqr-tsty-art-ls-arts").perfectScrollbar("update");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_PostGTO = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            var mdl = $(x).closest(".jb-tqr-tsty-art-bmx");
            $(x).css('opacity','0');
//            $(mdl).find(".jb-tqr-tsty-art-opt").filter("[data-action='post-pin-start'],[data-action='post-del-start']").removeClass("this_hide");
            $(mdl).find(".jb-tqr-tsty-art-opt").filter("[data-action='post-pin-start'],[data-action='post-del-start']")
                .stop(true,true)
                .hide().removeClass("this_hide")
                .fadeIn();
            
//            console.log("TALKBOARD : End Operation !");
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_PostRvl = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            var bmx = $(x).closest(".jb-tqr-tsty-art-bmx");
            if (! ( $(bmx).length && $(bmx).find(".jb-tqr-tsty-art-bdy-t-t-mr").length ) ) {
                return;
            }
            var mrprt = $(bmx).find(".jb-tqr-tsty-art-bdy-t-t-mr");
            $(mrprt).toggleClass("this_hide");
            
            var to__ = $(x).text(), tn__ = $(x).data("txrvrs");
            $(x).data("txrvrs",to__).text(tn__);
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_PostPin = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            
            /*
             * tsa  : ToSendAction
             * mrld : MustReLoaD 
             */
            var bmx = $(x).closest(".jb-tqr-tsty-art-bmx"), tsa, mrld = false;;
            switch (a) {
                case "post-pin-start" :
                        $(bmx).find(".jb-tqr-tsty-a-fnl-opt._y").data("action","post-pin-final-yes") ;
                        $(bmx).find(".jb-tqr-tsty-a-fnl-opt._n").data("action","post-pin-final-no") ;
    
                        $(bmx).find(".jb-tqr-tsty-a-fnl-lbl .purpose").text("(Épingler)");
    
                        $(bmx).find(".jb-tqr-tsty-art-ftr-mx[data-scp='opt-start']").addClass("this_hide");
                        $(bmx).find(".jb-tqr-tsty-art-ftr-mx[data-scp='opt-final']").removeClass("this_hide");
                    return;
                case "post-unpin-start" :
                        $(bmx).find(".jb-tqr-tsty-a-fnl-opt._y").data("action","post-unpin-final-yes") ;
                        $(bmx).find(".jb-tqr-tsty-a-fnl-opt._n").data("action","post-unpin-final-no") ;
    
                        $(bmx).find(".jb-tqr-tsty-a-fnl-lbl .purpose").text("(Détacher)");
    
                        $(bmx).find(".jb-tqr-tsty-art-ftr-mx[data-scp='opt-start']").addClass("this_hide");
                        $(bmx).find(".jb-tqr-tsty-art-ftr-mx[data-scp='opt-final']").removeClass("this_hide");
                    return;
                case "post-pin-final-no" :
                case "post-unpin-final-no" :
                        $(bmx).find(".jb-tqr-tsty-a-fnl-opt._y").data("action","") ;
                        $(bmx).find(".jb-tqr-tsty-a-fnl-opt._n").data("action","") ;
    
                        $(bmx).find(".jb-tqr-tsty-art-ftr-mx[data-scp='opt-final']").addClass("this_hide");
                        $(bmx).find(".jb-tqr-tsty-art-ftr-mx[data-scp='opt-start']").removeClass("this_hide");
    
                        $(bmx).find(".jb-tqr-tsty-a-fnl-lbl .purpose").text("");
                    return;
                case "post-pin-final-yes" :
                case "post-unpin-final-yes" :
                        $(bmx).find(".jb-tqr-tsty-a-fnl-opt._y").data("action","") ;
                        $(bmx).find(".jb-tqr-tsty-a-fnl-opt._n").data("action","") ;
    
                        $(bmx).find(".jb-tqr-tsty-art-ftr-mx[data-scp='opt-final']").addClass("this_hide");
                        $(bmx).find(".jb-tqr-tsty-art-ftr-mx[data-scp='opt-start']").removeClass("this_hide");
    
                        $(bmx).find(".jb-tqr-tsty-a-fnl-lbl .purpose").text("");
                        
                        tsa = ( a === "post-pin-final-yes" ) ? "TST_XA_GOPN" : "TST_XA_GOUPN";
                        mrld = true;
                    break;
                default:
                    return;
            }
    
            var i = $(bmx).data("item");
//            Kxlib_DebugVars([a,i,tsa],true);
//            return;
            if ( KgbLib_CheckNullity(i) ) {
                return;
            }
            
            /*
             * ETAPE :
             *      On lock tous les boutons d'ACTION
             */
            $(".jb-tqr-tsty-art-opt").data("lk",1);
            
            var s = $("<span/>"), xt = (new Date()).getTime();
            
            _f_Srv_XaTsty(i,tsa,xt,x,s);
            
            $(s).on("operended",function(e,d){
                
                if ( mrld === true ) {
                   /*
                    * ETAPE :
                    *      On affiche le message au niveau du bottom demandant de patienter
                    */
                    m = Kxlib_getDolphinsValue("COMLG_Loading3p");
                    $(".jb-pg-sts").text(m).removeClass("this_hide");

                    /*
                     * ETAPE :
                     *      On lance la rediction
                     */
                    location.reload();
                }
            });
    
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
            var bmx = $(x).closest(".jb-tqr-tsty-art-bmx"), tsa;
            switch (a) {
                case "post-like" :
                        tsa = "TST_XA_GOLK";
//                        console.log("Lancer l'animation");
                    break;
                case "post-unlike" :
                        tsa = "TST_XA_GOULK";
                    break;
                default:
                    return;
            }
    
            var i = $(bmx).data("item");
//            Kxlib_DebugVars([a,i,tsa],true);
//            return;
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
                $(x).text(d.lc);
                
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
                    
                } else {
                    if ( d.lc ) {
                        $(x).attr("data-state","ano1").data("state","ano1");
                    } else {
                        $(x).attr("data-state","").data("state","");
                    }
                    
                    /*
                     * ETAPE : 
                     *      On change les ACTION
                     */
                     $(x).data("action",'post-like').data("actrvs","post-unlike");
                     
                } 
                
                $(x).data("lk",0);
                _xhr_tsty_ax = null;
            });

            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ReactAct = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
//            Kxlib_DebugVars([$(x).closest(".jb-tbv-bind-art-mdl").data("item")],true);
//            return;
            if ( require.specified("r/csam/tkbvwr.csam") && !KgbLib_CheckNullity(_VWR) ) {
//                Kxlib_DebugVars(["ASDRBNR : Déjà chargé !",_VWR]);
                _VWR.open({
                    model   : "TLKBRD",
                    trigger : x,
                    action  : a
                });
            } else {
                require(["r/csam/tkbvwr.csam"],function(TbkVwr){
                    _VWR = new TbkVwr();
                    _VWR.open({
                        model   : "TLKBRD",
                        trigger : x,
                        action  : a
                    });
                });
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_PostDel = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            var bmx = $(x).closest(".jb-tqr-tsty-art-bmx");
            switch (a) {
                case "post-del-start" :
                    
                    $(bmx).find(".jb-tqr-tsty-a-fnl-opt._y").data("action","post-del-final-yes") ;
                    $(bmx).find(".jb-tqr-tsty-a-fnl-opt._n").data("action","post-del-final-no") ;
                    
                    $(bmx).find(".jb-tqr-tsty-a-fnl-lbl .purpose").text("(Supprimer)");
                    
                    $(bmx).find(".jb-tqr-tsty-art-ftr-mx[data-scp='opt-start']").addClass("this_hide");
                    $(bmx).find(".jb-tqr-tsty-art-ftr-mx[data-scp='opt-final']").removeClass("this_hide");
                    return;
                case "post-del-final-no" :
                    $(bmx).find(".jb-tqr-tsty-a-fnl-opt._y").data("action","") ;
                    $(bmx).find(".jb-tqr-tsty-a-fnl-opt._n").data("action","") ;
                    
                    $(bmx).find(".jb-tqr-tsty-art-ftr-mx[data-scp='opt-final']").addClass("this_hide");
                    $(bmx).find(".jb-tqr-tsty-art-ftr-mx[data-scp='opt-start']").removeClass("this_hide");
                    
                    $(bmx).find(".jb-tqr-tsty-a-fnl-lbl .purpose").text("");
                    return;
                case "post-del-final-yes" :
                    break;
                default:
                    return;
            }
            
            var i = $(bmx).data("item"), atp = $(bmx).data("atype");
            if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(atp) ) {
                return;
            }
            
            /*
             * ETAPE :
             *      Envoyer les données au serveur
             */
            var s = $("<span/>");
            
//            Kxlib_DebugVars([i],true);
            
            _f_Srv_DelTsty(i,atp,x,s);
            
            $(bmx).addClass("this_hide");
            
            $(s).on("operended",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
//                Kxlib_DebugVars([d],true);
                
                /*
                 * ETAPE :
                 *      On retire l'élément
                 */
                $(bmx).remove();
                
                var Nty = new Notifyzing();
                Nty.FromUserAction("ua_del_testy");
                
                /*
                 * ETAPE :
                 *       On gère les cas particuliers selon la PAGE d'accueil.
                 *       Si nous n'avons plus d'ARTICLE TSM (en général) au niveau de la PAGE, on REDIR vbers HOME
                 */
                if (! $(".jb-tbv-bind-art-mdl").length ) {
                    window.location.reload();
                    
                    /*
                     * ETAPE :
                     *      On affiche l'indicateur qui demande de patienter
                     */
                    if ( $(".jb-pg-sts").length ) {
                        $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Wait3p"));
                        $(".jb-pg-sts").removeClass("this_hide");
                    } 
                }
                
                /*
                 * ETAPE :
                 *      On libère le pointeur
                 */
                _xhr_tsty_del = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_GetRef = function (a) {
        try {
            if ( KgbLib_CheckNullity(a) ) {
                return;
            }
            
            var ref;
            switch (a) {
                case "F" :
                    ref = {
                        "pi" : ( $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:first").length && $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:first").data("item") && $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:first").data("time") ) ? $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:first").data("item") : null,
                        "pt" : ( $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:first").length && $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:first").data("item") && $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:first").data("time") ) ? $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:first").data("time") : null
                    };
                    break;
                case "L" :
                    ref = {
                        "pi" : ( $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:last").length && $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:last").data("item") && $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:last").data("time") ) ? $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:last").data("item") : null,
                        "pt" : ( $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:last").length && $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:last").data("time") && $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:last").data("time") ) ? $(".jb-tqr-tsty-art-bmx").filter("[data-isp=0]:last").data("time") : null
                    };
                    break;
                default :
                    return;
            }
            
            return ref;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_AutoPlTsts = function () {
        try {
            /*
             * RAPPEL :
             *      Ce fichier est aussi utilisé au niveau de page qui ne gère pas le module TESTY
             */
            if (! ( $("div[s-id='TMLNR_GTPG_RO']").length || $("div[s-id='TMLNR_GTPG_RU']").length || $("div[s-id='TMLNR_GTPG_WLC']").length ) ) {
                return;
            }
            
            var s = $("<span/>");
            _f_Srv_PlTsty("FST",null,null,null,s);
            
            $(s).on("datasready",function(e,ds){
                if ( KgbLib_CheckNullity(ds) ) {
                    return;
                }
                
                if ( ds.iwd ) {
                    $(".jb-tqr-tsty-add-bmx").remove();
                } else {
                    $(".jb-tqr-tsty-add-bmx").removeClass("this_hide");
                }
                
                
                //                Kxlib_DebugVars([JSON.stringify(ds.tds)],true);
                //                return;
                
                _f_AddTsts(ds.tds);
                
                $(".jb-tqr-tsty-art-ldmr").removeClass("this_hide");
                
                _xhr_tsty_get = null;
                
            });
            
            $(s).on("operended",function(e,d) {
                if ( d.ird ) {
                    $(".jb-tqr-tsty-add-bmx").remove();
                    $(".jb-tqr-tsty-art-lsts").remove();
                } else {
                    $(".jb-tqr-tsty-art-lsts").removeClass("this_hide");
                }
                
                if ( d.iwd ) {
                    $(".jb-tqr-tsty-add-bmx").remove();
                } else {
                    $(".jb-tqr-tsty-add-bmx").removeClass("this_hide");
                }
                
                
                _f_None(true);
                
                $(".jb-tqr-tsty-art-lsts").addClass("this_hide");
                _xhr_tsty_get = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_PlTsts = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            var dr;
            switch (a) {
                case "post-loadoldr" :
                        var ref = _f_GetRef("L");
                        /*
                        if (! ( ref.pi && ref.pt ) ) {
                            return;
                        }
                        dr = "BTM";
                        //*/
                        /*
                         * [DEPUIS 14-12-15]
                         *      On lance une requete de type FST dans le cas où on a apas de référence
                         */
                        dr = (! ( ref.pi && ref.pt ) ) ? "FST" : "BTM";
                    break;
                default :
                    return;
            }
            
            $(x).addClass("this_hide");
            
            var s = $("<span/>");
            
            _f_Srv_PlTsty(dr,ref.pi,ref.pt,x,s);
            
            $(s).on("datasready",function(e,ds){
                if ( KgbLib_CheckNullity(ds) ) {
                    return;
                }
                
                if ( ds.iwd ) {
                    $(".jb-tqr-tsty-add-bmx").remove();
                } else {
                    $(".jb-tqr-tsty-add-bmx").removeClass("this_hide");
                }
                
                
//                Kxlib_DebugVars([JSON.stringify(ds.tds)],true);
//                return;
                
                _f_AddTsts(ds.tds,dr);
                
                if ( dr === "BTM" ) {
                    _f_LdMr(true);
                }
                
                _xhr_tsty_get = null;
                $(x).data("lk",0);
                $(x).removeClass("this_hide");
            });
            
            $(s).on("operended",function(e,d){
                if ( d.ird ) {
                    $(".jb-tqr-tsty-add-bmx").remove();
                    $(".jb-tqr-tsty-art-lsts").remove();
                } else {
                    $(".jb-tqr-tsty-art-lsts").removeClass("this_hide");
                }
                
                if ( d.iwd ) {
                    $(".jb-tqr-tsty-add-bmx").remove();
                } else {
                    $(".jb-tqr-tsty-add-bmx").removeClass("this_hide");
                }
                
                _f_NoMr(true);
                _xhr_tsty_get = null;
                $(x).data("lk",0);
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_AddSgsP = function (ds) {
        try {
            if ( KgbLib_CheckNullity(ds) ) {
                return;
            }
            
            $.each(ds,function(x,sd){
                if ( $(".jb-...[data-item='"+sd.uid+"']").length ) {
                    return true;
                }
                
                var m = _f_Vw_BldPflMdl(sd,x);
                $(m).hide().appendTo(".jb-asd-rch-s-psg-lsts").fadeIn();
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Rbd_BldTrdMdl = function (m) {
        try {
            if ( KgbLib_CheckNullity(m) ) {
                return;
            }
            
            $(m).find(".jb-asd-rch-s-tsg-cov-x").off("hover").hover(function(e){
                if ( $(this).hasClass("hover") ) {
                    $(this).removeClass("hover");
                    $(this).find(".jb-subscribers-mx, .jb-publications-mx").addClass("this_hide");
                } else {
                    $(this).find(".jb-subscribers-mx, .jb-publications-mx").removeClass("this_hide");
                    $(this).addClass("hover");
                }
            });
            
            return m;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*
     * [DEPUIS 22-06-16]
     *      A été transporté au niveau du module TALKBOARD_VIEW
     */
//    var _f_Uqv_Io = function (x,a) { };
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** SERVER SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _Ax_AddTsty = Kxlib_GetAjaxRules("TQR_TSTY_ADD");
    var _f_Srv_AddTsty  = function(tv,pi,pt,x,s) {
        if ( KgbLib_CheckNullity(tv) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            //            alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    _xhr_tsty_add = null;
                    $(x).data("lk",0);
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    _xhr_tsty_add = null;
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
                            default :
                                Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) && !KgbLib_CheckNullity(datas.return.tds) ) {
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
            "urqid": _Ax_AddTsty.urqid,
            "datas": {
                "tv"   : tv,
                "pi"   : ( pi ) ? pi : null,
                "pt"   : ( pt ) ? pt : null,
                "cu"   : curl
            }
        };
        
        _xhr_tsty_add = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_AddTsty.url, wcrdtl : _Ax_AddTsty.wcrdtl });
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
    
    var _Ax_PlTsty = Kxlib_GetAjaxRules("TQR_TSTY_GET");
    var _f_Srv_PlTsty  = function(dr,pi,pt,x,s) {
        if ( KgbLib_CheckNullity(dr) | KgbLib_CheckNullity(s) ) {
            return;
        }
        //        alert(dr);
        //        if ( dr === "BTM" ) {
        //            alert("Stop !");
        //        }
        var onsuccess = function (datas) {
            //            alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    _xhr_tsty_get = null;
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    if (! KgbLib_CheckNullity(x) ) {
                        $(x).data("lk",0);
                    }
                    _xhr_tsty_get = null;
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
                } else if ( !KgbLib_CheckNullity(datas.return) && !KgbLib_CheckNullity(datas.return.tds) ) {
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
                _xhr_tsty_get = null;
                
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
            "urqid": _Ax_PlTsty.urqid,
            "datas": {
                "dr"    : dr,
                "pi"    : pi,
                "pt"    : pt,
                "cu"    : curl
            }
        };
        
        _xhr_tsty_get = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlTsty.url, wcrdtl : _Ax_PlTsty.wcrdtl });
    };
    
    
    /*
     * [DEPUIS 24-11-15]
     *      Permet d'éviter de locker le code à cause d'un undefined sur "mds.upsd".
     *      En effet, il n' a pas de try { } catch { } sur cette portion du code.
     */
    var mds = Kxlib_GetOwnerPgPropIfExist();
    var u = ( mds && typeof mds === "object" && mds.upsd ) ? mds.upsd : null;
    var _Ax_CnfgGet = Kxlib_GetAjaxRules("TQR_TSTY_GET_CONF",u);
    var _f_Srv_CnfgGet  = function(x,s) {
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            //            alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    _xhr_cnfg_set = null;
                    $(x).data("lk",0);
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    _xhr_cnfg_set = null;
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
                            default :
                                Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) ) {
                    var ds = [datas.return];
                    $(s).trigger("datasready",ds);
                } else {
                    $(s).trigger("operended");
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
            "urqid": _Ax_CnfgGet.urqid,
            "datas": {
                "cu"    : curl
            }
        };
        
        _xhr_cnfg_get = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_CnfgGet.url, wcrdtl : _Ax_CnfgGet.wcrdtl });
    };
    
    
    /*
     * [DEPUIS 24-11-15]
     *      Permet d'éviter de locker le code à cause d'un undefined sur "mds.upsd".
     *      En effet, il n' a pas de try { } catch { } sur cette portion du code.
     */
    var mds = Kxlib_GetOwnerPgPropIfExist();
    var u = ( mds && typeof mds === "object" && mds.upsd ) ? mds.upsd : null;
    var _Ax_CnfgSet = Kxlib_GetAjaxRules("TQR_TSTY_SET_CONF",u);
    var _f_Srv_CnfgSet  = function(iwa,iwd,ira,ird,x,s) {
        if ( KgbLib_CheckNullity(iwa) | KgbLib_CheckNullity(ira) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            //            alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    _xhr_cnfg_set = null;
                    $(x).data("lk",0);
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    _xhr_cnfg_set = null;
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
                            default :
                                Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) ) {
                    var ds = [datas.return];
                    $(s).trigger("datasready",ds);
                } else {
                    $(s).trigger("operended");
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
            "urqid": _Ax_CnfgSet.urqid,
            "datas": {
                "iwa"   : iwa,
                "iwd"   : ( iwd ) ? iwd : null,
                "ira"   : ira,
                "ird"   : ( ird ) ? ird : null,
                "cu"    : curl
            }
        };
        
        _xhr_cnfg_set = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_CnfgSet.url, wcrdtl : _Ax_CnfgSet.wcrdtl });
    };
    
    
    var _Ax_XaTsty = Kxlib_GetAjaxRules("TQR_TSTY_XTAC");
    var _f_Srv_XaTsty  = function(i,a,xt,x,s) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(a) | KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
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
    /**************************************************************************** VIEW SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_None = function(sh) {
        try {
            
            if ( sh === true ) {
                $(".jb-tqr-testy-none-mx").removeClass("this_hide");
            } else {
                $(".jb-tqr-testy-none-mx").addClass("this_hide");
            }
            
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }   
    };
    
    var _f_LdMr = function(sh) {
        try {
            
            if ( sh === true ) {
                $(".jb-tqr-tsty-art-ldmr-end").addClass("this_hide");
                $(".jb-tqr-tsty-art-ldmr").removeClass("this_hide");
            } else {
                $(".jb-tqr-tsty-art-ldmr").addClass("this_hide");
            }
            
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }   
    };
    
    var _f_NoMr = function(sh) {
        try {
            
            if ( sh === true ) {
                $(".jb-tqr-tsty-art-ldmr").addClass("this_hide");
                $(".jb-tqr-tsty-art-ldmr-end").removeClass("this_hide");
            } else {
                $(".jb-tqr-tsty-art-ldmr-end").addClass("this_hide");
            }
            
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }   
    };
    
    var _f_Vw_BldIniUsrMdl = function(d,p) {
        try {
            if ( KgbLib_CheckNullity(d) && KgbLib_CheckNullity(p) ) {
                return;
            }
            
            var m = "<li class=\"tqr-tsty-cfg-ini-uz-l-mx jb-tqr-tsty-cfg-ini-uz-l-mx\" data-item=\"\">";
            m += "<span class=\"tqr-tsty-cfg-ini-uz-l\">";
            m += "<a class=\"tqr-tsty-cfg-ini-uhrf jb-tqr-tsty-cfg-ini-uhrf\"  ></a>";
            m += "<a class=\"tqr-tsty-cfg-ini-rmv cursor-pointer jb-qr-tsty-cfg-ini-rmv\" data-action=\"ini-write-deny-rmv\" title=\"Retirer de la liste\">&times;</a>";
            m += "</span>";
            m += "</li>";
            m = $.parseHTML(m);
            
            if (! KgbLib_CheckNullity(d) ) {
                $(m).data("item",d.uid);
                $(m).find(".jb-tqr-tsty-cfg-ini-uhrf").attr("href","/"+d.upsd).text("@"+d.upsd);
            } else {
                $(m).find(".jb-tqr-tsty-cfg-ini-uhrf").text("@"+p);
            }
            
            /*
             * ETAPE :
             *      On rebind
             */
            $(m).find(".jb-qr-tsty-cfg-ini-rmv").off("click").click(function(e){
                Kxlib_PreventDefault(e);
                _f_Action(this);
            });
            
            return m;
            
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Vw_BldTstyMdl = function(d,ix) {
        try {
            if ( KgbLib_CheckNullity(d) | KgbLib_CheckNullity(ix) ) {
                return;
            }
            //            Kxlib_DebugVars([JSON.stringify(d)],true);
            /*
             * TABLE DE DONNÉÉS 
             *  "i"      : L'identifiant externe du témoignage
             *  "tm"    : Le timestamp
             *  "m"     : Le message
             *  "oid"   : L'identifiant externe du propriétaire
             *  "ofn"   : Le nom complet
             *  "opsd"  : Le pseudo
             *  "oppic" : L'image de profil
             */
            //            
            var m = "<article class=\"tqr-tsty-art-bmx jb-tqr-tsty-art-bmx jb-tbv-bind-art-mdl\" data-item=\"\" data-author=\"\" data-target=\"\" data-time=\"\" data-atype=\"tmlnr\" >";
            m += "<div class=\"tqr-tsty-art-mx\">";
            m += "<header class=\"tqr-tsty-art-hdr-mx\">";
            m += "<a class=\"tqr-tsty-art-userbx jb-tqr-tsty-art-userbx\" href=\"\" title=\"\">";
            m += "<span class=\"tqr-tsty-art-ubx-i jb-tqr-tsty-art-ubx-i\" style=\"background: url(\'"+d.au.oppic+"\') no-repeat; background-size: 100%;\"></span>";
            m += "<span class=\"tqr-tsty-art-ubx-psd jb-tqr-tsty-art-ubx-psd\"></span>";
            m += "</a>";
            m += "<span class=\"kxlib_tgspy tsty-tm\" data-tgs-crd=\'"+d.tm+"\' data-tgs-dd-atn=\'\' data-tgs-dd-uut=\'\'>";
            m += "<span class=\'tgs-frm\'></span>";
            m += "<span class=\'tgs-val\'></span>";
            m += "<span class=\'tgs-uni\'></span>";
            m += "</span>";
            m += "<span id=\"tqr-tsty-art-pin\" class=\"jb-tqr-tsty-art-pin\" title=\"Mot épinglé\">Épinglé</span>";
            m += "</header>";
            m += "<div class=\"tqr-tsty-art-bdy-mx\">";
            m += "<div class=\"tqr-tsty-art-bdy-tle-mx jb-tqr-tsty-a-b-tle-mx this_hide\">";
            m += "<a class=\"tqr-tsty-a-b-tle jb-tqr-tsty-a-b-tle\"></a>";
            m += "</div>";
            m += "<div class=\"tqr-tsty-art-bdy-txt\">";
            m += "<span class=\"tqr-tsty-art-bdy-t-txt jb-tqr-tsty-art-bdy-t-t\"></span>";
            m += "<span class=\"tqr-tsty-art-bdy-t-txt jb-tqr-tsty-art-bdy-t-t-mr this_hide\"></span>";
//            m += "<a class=\"tqr-tsty-art-bdy-t-mr cursor-pointer jb-tqr-tsty-art-bdy-t-mr this_hide\" data-action=\"post-reveal\" data-txrvrs=\"Réduire\">Affficher plus</a>";
            m += "<a class=\"tqr-tsty-art-bdy-t-mr cursor-pointer jb-tqr-tsty-art-bdy-t-mr this_hide\" data-action=\"post-react-open\" data-txrvrs=\"Réduire\">Affficher plus</a>";
            m += "</div>";
            m += "</div>";
            m += "<div class=\"tqr-tsty-art-ftr-bmx jb-tqr-tsty-art-ftr-bmx\" >";
            m += "<div class=\"tqr-tsty-art-ftr-mx jb-tqr-tsty-art-ftr-mx\" data-scp=\"opt-start\">";
                m += "<div class=\"tqr-tsty-art-opt-l\">";
                    m += "<a class=\"tqr-tsty-art-opt cursor-pointer like jb-tqr-tsty-art-opt\" data-action=\"post-like\" data-actrvs=\"post-unlike\" data-state=\"\">0</a>";
                    m += "<a class=\"tqr-tsty-art-opt cursor-pointer react jb-tqr-tsty-art-opt\" data-action=\"post-react-open\" data-state=\"\">0</a>";
                m += "</div>";
                m += "<div class=\"tqr-tsty-art-opt-r\">";
//                    m += "<button class=\"tqr-tsty-art-opt cursor-pointer gotopt jb-tqr-tsty-art-opt\" data-action=\"post-gotopt\"></button>";
                    m += "<a class=\"tqr-tsty-art-opt cursor-pointer gotopt jb-tqr-tsty-art-opt\" data-action=\"post-gotopt\" href=\"javascript:;\"></a>";
                    m += "<a class=\"tqr-tsty-art-opt cursor-pointer pin jb-tqr-tsty-art-opt this_hide\" data-action=\"post-pin-start\" data-actrvs=\"post-unpin-start\" data-txrvs=\"Détacher\">Épingler</a>";
                    m += "<a class=\"tqr-tsty-art-opt cursor-pointer delete jb-tqr-tsty-art-opt this_hide\" data-action=\"post-del-start\">Supprimer</a>";
                m += "</div>";
            m += "</div>";
            m += "<div class=\"tqr-tsty-art-ftr-mx jb-tqr-tsty-art-ftr-mx this_hide\" data-scp=\"opt-final\">";
            m += "<span class=\"tqr-tsty-a-fnl-lbl jb-tqr-tsty-a-fnl-lbl\">Etes-vous sur ? <span class=\"purpose\"></span></span>";
            m += "<a class=\"tqr-tsty-a-fnl-opt cursor-pointer _y jb-tqr-tsty-a-fnl-opt\" data-action=\"\">Oui</a>";
            m += "<a class=\"tqr-tsty-a-fnl-opt cursor-pointer _n jb-tqr-tsty-a-fnl-opt\" data-action=\"\">Non</a>";
            m += "</div>";
            m += "</div>";
            m += "</div>";
            m += "</article>";
            m = $.parseHTML(m);
            
            /*
             * ETAPE :
             *      AJOUT DES DONNÉES
             */
            $(m).attr("data-item",d.i).data("time",d.tm).data("author","".concat("{\"id\":\"",d.au.oid,"\",\"fn\":\"",d.au.ofn,"\",\"ps\":\"",d.au.opsd,"\",\"pp\":\"",d.au.oppic,"\"}"));
            $(m).attr("data-item",d.i).data("time",d.tm).data("target","".concat("{\"id\":\"",d.tg.oid,"\",\"fn\":\"",d.tg.ofn,"\",\"ps\":\"",d.tg.opsd,"\",\"pp\":\"",d.tg.oppic,"\"}"));
             /*
             * [DEPUIS 02-04-16]
             */
//            $(m).attr("data-ajcache",JSON.stringify(d)).data("ajcache",JSON.stringify(d));
            $(m).data("ajcache",JSON.stringify(d));
            
            $(m).find(".jb-tqr-tsty-art-userbx").attr("href","/"+d.au.opsd).attr("title",d.au.ofn.concat(" (@",d.au.opsd,")"));
            $(m).find(".jb-tqr-tsty-art-ubx-psd").text("@".concat(d.au.opsd));
            
           
            
            //TEXTE
            $(m).data("text",d.m);
//            $(m).find(".jb-tqr-tsty-art-bdy-t-t").text(d.m);
//            Kxlib_DebugVars([d.m.length,_f_Gdf().pstPvwLn,d.m],true);
            if ( d.m.length > _f_Gdf().pstPvwLn ) {
//                var pcs = d.m.match(/^([\s\S]{1,200})(.+)$/i);
                /*
                 * [DEPUIS 19-06-16]
                 */
                var pcs = d.m.match(/^([\s\S]{1,200})([\s\S]+)$/i);
                var ftxt = Kxlib_TextEmpow(pcs[1].concat("..."),d.ustgs,d.hashs,null,{
                    "ena_inner_link" : {
//                        "local" : true, //DEV, DEBUG, TEST
                        "all"   : false,
                        "only"  : "fksa"
                    },
                    emoji : {
                        "size"          : 36,
                        "size_css"      : 20,
                        "position_y"    : 3
                    }
                });
                $(m).find(".jb-tqr-tsty-art-bdy-t-t").text("").append(ftxt);
                
                /* //[DEPUIS 02-04-16]
                $(m).find(".jb-tqr-tsty-art-bdy-t-t").text(pcs[1].concat("..."));
                $(m).find(".jb-tqr-tsty-art-bdy-t-t-mr").text(pcs[2]);
                //*/
                
                $(m).find(".jb-tqr-tsty-art-bdy-t-mr").removeClass("this_hide");
            } else {
                var ftxt = Kxlib_TextEmpow(d.m,d.ustgs,d.hashs,null,{
                    "ena_inner_link" : {
//                        "local" : true, //DEV, DEBUG, TEST
                        "all"   : false,
                        "only"  : "fksa"
                    },
                    emoji : {
                        "size"          : 36,
                        "size_css"      : 20,
                        "position_y"    : 3
                    }
                });
                $(m).find(".jb-tqr-tsty-art-bdy-t-t").text("").append(ftxt);
                
                $(m).find(".jb-tqr-tsty-art-bdy-t-t-mr").remove();;
                $(m).find(".jb-tqr-tsty-art-bdy-t-mr").remove();
            }
            
            /*
             * [DEPUIS 1-12-15] @author BOR
             *      On traite le cas du TESTIMONY épinglé
             */
            if ( d.isp === true ) {
                /*
                 * ETAPE :
                 *      On indique l'élément est épinglé afin de faciliter sa détection
                 */
                $(m).data("isp",1).attr("data-isp",1);
                
                //On ajoute le badge
//                $(m).attr("data-state","pinned").data("state","pinned");
                //On change l'ordre des ACTIONs 
                $(m).find(".jb-tqr-tsty-art-opt.pin")
                    .data("action",'post-unpin-start')
                    .data("actrvs","post-pin-start")
                    .data("txrvs","Épingler").text("Détacher");
                //On change la bordure bottom du footer pour des questions esthétiques
//                $(m).find(".jb-tqr-tsty-art-ftr-bmx").css({
//                    "border-bottom" : "1px solid transparent"
//                });
            } else {
                $(m).find(".jb-tqr-tsty-art-pin").remove();
                $(m).data("isp",0).attr("data-isp",0);
            }
            
            /*
             * [DEPUIS 1-12-15] @author BOR
             *      L'utilisateur peut-il avoir accès à la fonctionnalité de PIN ? 
             */
            if ( d.cgap === false ) {
                $(m).find(".jb-tqr-tsty-art-opt.pin").remove();
            }
            
            /*
             * [DEPUIS 02-04-16]
             *      Traitement du cas des REACTIONS
             */
             $(m).find(".jb-tqr-tsty-art-opt.react").text(d.rnb);
            
            /*
             * [DEPUIS 1-12-15] @author BOR
             *      Traitement du cas de LIKE
             */
//            Kxlib_DebugVars([d.hslk,d.cnlk],true);
                
            if ( d.cnlk ) { //QUESTION : Il y a t-il d'autres LIKE
                $(m).find(".jb-tqr-tsty-art-opt.like")
                    .attr("data-state","ano1")
                    .data("state","ano1")
                    .text(d.cnlk);
            } 
            
            if (! d.clk ) { //QUESTION : L'utilisateur peut-il accéder à la fonctionnalité LIKE pour le TESTIMONY
                $(m).find(".tqr-tsty-art-opt.like").removeClass("jb-tqr-tsty-art-opt").addClass("jb-irr");
            }
            
            if ( d.hslk === true ) { //QUESTION : L'utilisateur a t-il LIKE le TESTIMONY
//                $(m).find(".jb-tqr-tsty-art-opt.like").attr("data-state","me").data("state","me").text(d.cnlk);
                
                /*
                 * ETAPE :
                 *      On inverse les ACTION
                 */
                $(m).find(".jb-tqr-tsty-art-opt.like")
                    .data({
                        "action"    : "post-unlike",
                        "actrvs"    : "post-like",
                        "state"     : "me"
                    })
                    .attr("data-state","me")
            }
            
            
            /*
             * [DEPUIS 07-12-15]
             *      On insère les usertags le cas échant
             */
            /*
            if ( d.hasOwnProperty("ustgs") && d.ustgs !== undefined && Kxlib_ObjectChild_Count(d.ustgs) ) {
                var txt = Kxlib_Decode_After_Encode(d.m);
                var ustgs = ( $.isArray(d.ustgs) ) ? Kxlib_GetColumn(3,d.ustgs) : [d.ustgs[3]];
                
//                Kxlib_DebugVars([JSON.stringify(ustgs)],true);
//                Kxlib_DebugVars([JSON.stringify(d),JSON.stringify(d.ustgs)],true);

                var t__ = Kxlib_UsertagFactory(txt,ustgs,"tqr-unq-user");
                t__= $("<div/>").text(t__).text();
//                 Kxlib_DebugVars([t__],true);
//                 t__ = Kxlib_Decode_After_Encode(t__);

                t__ = Kxlib_SplitByUsertags(t__);
                
//                Kxlib_DebugVars([t__],true);
                
                //Mettre en place la description
                $(m).find(".jb-tqr-tsty-art-bdy-t-t").html(t__);
            } else {
                t__ = d.m;
                t__ = $("<div/>").html(t__).text();
                
                //Mettre en place la description
                $(m).find(".jb-tqr-tsty-art-bdy-t-t").text(t__);
            }
            //*/
            
            if (! d.cdl ) {
                $(m).find(".jb-tqr-tsty-art-opt[data-action='post-gotopt']").remove();
                $(m).find(".jb-tqr-tsty-art-opt[data-action='post-del-start']").remove();
                $(m).find(".jb-tqr-tsty-art-opt[data-action='post-pin-start']").remove();
            }
            
            /* --------------------------------------- REBIND SCOPE -------------------------------------- */
            
            $(m).find(".jb-tqr-tsty-art-opt, .jb-tqr-tsty-a-fnl-opt, .jb-tqr-tsty-art-bdy-t-mr").click(function(e){
                Kxlib_PreventDefault(e);
//                console.log("TALKBOARD : Click !");
                
                _f_Action(this);
            });
            
            /*
             * [DEPUIS 03-02-15]
             */
            $(m).find(".jb-tqr-tsty-art-opt").filter("[data-action='post-pin-start'],[data-action='post-del-start']").on("cusmclick",function(e){
                Kxlib_PreventDefault(e);
                
                _f_Action(this);
            });
            $(m).find(".jb-tqr-tsty-art-opt[data-action='post-gotopt']").focusout(function(e){
                Kxlib_PreventDefault(e);
                
//                console.log("TALKBOARD : BLUR !");
                
                var mdl = $(this).closest(".jb-tqr-tsty-art-bmx");
                if ( $(mdl).find(".jb-tqr-tsty-a-fnl-opt").first().hasClass("this_hide") ) {
                    e.stopPropagation();
                    $(this).focus();
//                    console.log("TALKBOARD : Stop propagation !");
                } else {
                    var mdl = $(this).closest(".jb-tqr-tsty-art-bmx");
                    $(mdl).find(".jb-tqr-tsty-art-opt").filter("[data-action='post-pin-start'],[data-action='post-del-start']").stop(true,true).fadeOut(300,function(){
                        $(mdl).find(".jb-tqr-tsty-art-opt[data-action='post-gotopt']").css('opacity','1');
                    });
                }
            });
            
            return m;
            
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /*************************************************************************************************************************************************************************/
    /**************************************************************************** LISTENERS SCOPE ****************************************************************************/
    /*************************************************************************************************************************************************************************/
    
    $(".jb-tqr-testy-hdr-err-clz, .jb-tqr-tsty-add-otp, .jb-tqr-tsty-cfg-ini-ipt-add, .jb-qr-tsty-cfg-ini-rmv, .jb-tqr-tsty-c-i-fnl-opr, .jb-tqr-tsty-art-opt, .jb-tqr-tsty-a-fnl-opt, .jb-tqr-tsty-art-bdy-t-mr, .jb-tqr-tsty-art-ldmr").off("click").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
    $(".jb-tqr-tsty-add-tle-prems").change(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
    /*
     * [DEPUIS 22-06-16]
     */
    /*
    $(".jb-tlkb-uqv-close-trg").off("click").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    //*/
    
    $(".jb-tqr-tsty-art-opt").filter("[data-action='post-pin-start'],[data-action='post-del-start']").on("cusmclick",function(e){
        Kxlib_PreventDefault(e);

        _f_Action(this);
    });
    
    $(".jb-tqr-tsty-art-opt[data-action='post-gotopt']").focusout(function(e){
        Kxlib_PreventDefault(e);

        var mdl = $(this).closest(".jb-tqr-tsty-art-bmx");
        if ( $(mdl).find(".jb-tqr-tsty-a-fnl-opt").first().hasClass("this_hide") ) {
            e.stopPropagation();
            $(this).focus();
        } else {
            var mdl = $(this).closest(".jb-tqr-tsty-art-bmx");
            $(mdl).find(".jb-tqr-tsty-art-opt").filter("[data-action='post-pin-start'],[data-action='post-del-start']").stop(true,true).fadeOut(100);
            $(mdl).find(".jb-tqr-tsty-art-opt[data-action='post-gotopt']").css('opacity','1');
        }
    });
    
    /*
     * [DEPUIS 03-12-15]
     *      
     * [NOTE 06-12-15]
     *      Permet de pallier à l'erreur qui se décleche du fait que sur certaines pages, il n'a pas accès à PERFECTSCROLLBAR
     */
    //*
    try {
        if ( $(".jb-tqr-tsty-art-ls-arts").length ) {
            $(".jb-tqr-tsty-art-ls-arts").perfectScrollbar({
                suppressScrollX : true
            });
        }
    } catch (ex) {
        Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
    }
    //*/
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** AUTO SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    setTimeout(function(){
        _f_AutoPlTsts();
    },1000);
    
})();
//</editor-fold>



/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/***********************************************************************************          ASIDE-RICH-BANNER : CAPTIVE           ***********************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/

(function(){
    
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** PROCESS SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_Action = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && !a ) ) {
                return;
            }
            
            var _a = ( a ) ? a : $(x).data("action");
            switch (_a) {
                case "close-sprt" :
                        _f_ShwSprt(false);
                    break;
                default :
                    return;
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ShwSprt = function (sh) {
        try {
            
            if ( sh ) {
                $(".jb-tqr-captv-sprt").removeClass("this_hide");
            } else {
                $(".jb-tqr-captv-sprt").addClass("this_hide");
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** SERVER SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    /*******************************************************************************************************************************************************************/
    /**************************************************************************** VIEW SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** LISTENERS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    $(".jb-tqr-captv-ftr-sltr").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
})();


/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/***********************************************************************************      ASIDE-RICH-BANNER : LASTA ACTIVITIES      ***********************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/

(function(){
    var _xhr_lasta_get;
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** PROCESS SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_Action = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity(a) && KgbLib_CheckNullity($(x).data("action")) ) ) {
                return;
            }
            
            var _a = ( KgbLib_CheckNullity(a) ) ? $(x).data("action") : a;
            switch (_a) {
                case "akx-nwfd" :
                case "akx-psmn" :
                case "akx-frdrqt" :
                case "akx-nwfd-sec" :
                        _f_Sntch_Open(x,_a);
                    break;
                default:
                    return;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_PullLasta = function () {
        try {
            
            /*
             * RAPPEL :
             *      Ce fichier est aussi utilisé au niveau de page qui ne gère pas le module TESTY
             */
            if (! ( $(".jb-tqr-lasta-mx").length && ( $("div[s-id='TMLNR_GTPG_RO']").length || $("div[s-id='TMLNR_GTPG_RU']").length ) ) ) {
                return;
            }
            
            var s = $("<span/>");
            _f_Srv_PlLasta(s);
            
            $(s).on("datasready",function(e,ds){
                if ( KgbLib_CheckNullity(ds) ) {
                    return ds;
                }
                
//                Kxlib_DebugVars([JSON.stringify(ds)],true);
//                return;
                
                if (! KgbLib_CheckNullity(ds.ads) ) {
                    _f_AddActs(ds.ads,"MINE");
                } else {
                    _f_None(true,"owner");
                }
                
                if (! KgbLib_CheckNullity(ds.ads_ntwrk) ) {
                    _f_AddActs(ds.ads_ntwrk,"NTWRK");
                } else {
                    _f_None(true,"network");
                }
                
                _xhr_lasta_get = null;
            });
            
            $(s).on("operended",function(e){
                /*
                 * ETAPE :
                 *      On affiche none
                 */
                _f_None(true,"owner");
                _f_None(true,"network");
                
                _xhr_lasta_get = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
   
   
    var _f_AddActs = function (ds,cz) {
        try {
            if ( KgbLib_CheckNullity(ds) | KgbLib_CheckNullity(cz) ) {
                return;
            }
            
            $.each(ds,function(x,a){
                if ( $(".jb-tqr-lasta-art-mx").filter("[data-item='"+a.aid+"']").length ) {
                    return true;
                }
                
                var m = _f_BuildMdl(a,x,cz);
                
                if ( cz === "MINE" ) {
                    _f_None(null,"owner");
                    $(m).hide().appendTo(".jb-tqr-lasta-mx[data-scp='owner'] .jb-tqr-lasta-arts-wrap").fadeIn(300);
                } else {
                    _f_None(null,"network");
                    $(m).hide().appendTo(".jb-tqr-lasta-mx[data-scp='network'] .jb-tqr-lasta-arts-wrap").fadeIn(300);
                }
                
                var x_ = ++x;
                if ( x_%3 === 0 ) {
                    $(m).addClass("last");
                            
                    var divdr = $("<div/>",{
                        class:"tqr-lasta-arts-divdr"
                    });
                    $(divdr).insertAfter(m);
                }
            });
            
            setTimeout(function(){
                var lata_o_nb = $(".jb-tqr-lasta-mx[data-scp='owner'] .jb-tqr-lasta-arts-wrap").find(".jb-tqr-lasta-art-mx").length;
                if ( cz === "MINE" ) {
                    if ( lata_o_nb && ( lata_o_nb % 3 ) !== 0 ) {
                        var divdr = $("<div/>",{
                            class:"tqr-lasta-arts-divdr"
                        });
                        $(divdr).insertAfter($(".jb-tqr-lasta-mx[data-scp='owner'] .jb-tqr-lasta-arts-wrap").find(".jb-tqr-lasta-art-mx:last"));
                    }
                } else {
                    var lata_n_nb = $(".jb-tqr-lasta-mx[data-scp='network'] .jb-tqr-lasta-arts-wrap").find(".jb-tqr-lasta-art-mx").length;
                    if ( lata_n_nb && ( lata_n_nb % 3 ) !== 0 ) {
                        var divdr = $("<div/>",{
                            class:"tqr-lasta-arts-divdr"
                        });
                        $(divdr).insertAfter($(".jb-tqr-lasta-mx[data-scp='network'] .jb-tqr-lasta-arts-wrap").find(".jb-tqr-lasta-art-mx:last"));
                    }
                }
            },300);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    
    var _f_Sntch_Open = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            switch (a) {
                case "akx-nwfd" :
                        $(".jb-global-nav-elt[data-target='nwfd']").click();
                    break;
                case "akx-psmn" :
                        $(".jb-global-nav-elt[data-target='pm']").click();
                    break;
                case "akx-frdrqt" :
                        $(".jb-tqr-f-nav-hdr[data-target='friend']").click();
                    break;
                case "akx-nwfd-sec" :
                        var sec = $(x).data("scp");
                        if ( $.inArray(sec,["nwfd-lasta","nwfd-iml-fa","nwfd-pod","nwfd-itr"]) === -1 ) {
                            return;
                        }
                        $(".jb-global-nav-elt[data-target='nwfd']").click();
                        switch (sec) {
                            case "nwfd-lasta" :
                                break;
                            case "nwfd-iml-fa" :
                                    $(".jb-nwfd-h-mn-elt[data-target='comy']").click();
                                break;
                            case "nwfd-pod" :
                                    $(".jb-nwfd-h-mn-elt[data-target='iml_pod']").click();
                                break;
                            case "nwfd-itr" :
                                    $(".jb-nwfd-h-mn-elt[data-target='itr']").click();
                                break;
                            default:
                                return;
                        }
                    break;
                default:
                    return;
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** AUTO SCOPE ****************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    setTimeout(function(){
        _f_PullLasta();
    },1500);
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** SERVER SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _Ax_PlLasta = Kxlib_GetAjaxRules("TQR_LASTA_GACTV");
    var _f_Srv_PlLasta  = function(s) {
        if ( KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
//            alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    _xhr_lasta_get = null;
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    _xhr_lasta_get = null;
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
                } else if ( !KgbLib_CheckNullity(datas.return) && ( !KgbLib_CheckNullity(datas.return.ads) | !KgbLib_CheckNullity(datas.return.ads_ntwrk) ) ) {
                    var ds = [datas.return];
                    $(s).trigger("datasready",ds);
                } else {/* if ( !KgbLib_CheckNullity(datas.return) && datas.return.hasOwnProperty("iwd") && datas.return.hasOwnProperty("ird") ) { */
                    $(s).trigger("operended");
                    return;
                } 
                
            } catch (ex) {
                _xhr_lasta_get = null;
                    
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
            "urqid": _Ax_PlLasta.urqid,
            "datas": {
                "cu"    : curl
            }
        };
        
        _xhr_lasta_get = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlLasta.url, wcrdtl : _Ax_PlLasta.wcrdtl });
    };
    
    /*******************************************************************************************************************************************************************/
    /**************************************************************************** VIEW SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_None = function (sh,scp) {
        try {
            if ( KgbLib_CheckNullity(scp) ) {
                return;
            }
            
            var mx;
            switch (scp) {
                case "owner" :
                case "network" :
                        mx = $(".jb-tqr-lasta-mx[data-scp='"+scp+"'] .jb-tqr-lasta-as-nn-mx");
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
    
    var _f_BuildMdl = function(d,x,cz) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(d) | KgbLib_CheckNullity(cz) ) {
                return;
            }
            
            /*
             * "aid"       => L'identifiant externe de l'Article
             * "aim"       => Le lien vers l'image associée
             * "adsc"      => Le texte de description
             * "aplk"      => Le lien vers la page PERMALIEN
             * "adt"       => La date de création de l'Article
             * "acz_tp"    => Le type d'évènement
             * "acz_id"    => L'identifiant de l'évènement (Peu être null),
             * "acz_dt"    => La date liée à l'évènement. Utilisée comme référence
             * 
             * CAS NETWORK
             * "acz_aeid"      => Identifiant externe de l'acteur
             * "acz_afn"       => Nom complet
             * "acz_apsd"      => Pseudo
             */
            
            /*
             * ETAPE :
             *      Création de la vue
             */
            var m = "<article class=\"tqr-lasta-art-mx jb-tqr-lasta-art-mx\" data-item=\"\" data-jzncah=\"\">";
            m += "<a class=\"tqr-lasta-art-a-mx jb-tqr-lasta-art-a-mx\" href=\"\">";
            m += "<div class=\"tqr-lasta-art-a-i-mx jb-tqr-lasta-art-a-i-mx\">";
            m += "<img class=\"tqr-lasta-art-a-i jb-tqr-lasta-art-a-i\" width=\"110\" height=\"110\" src=\"\" alt=\"\"/>";
            m += "</div>";
            m += "<div class=\"tqr-lasta-art-a-i-fd jb-tqr-lasta-art-a-i-fd\" data-type=\"\"></div>";
            if ( cz === "NTWRK" ) {
                m += "<div class=\"tqr-lasta-art-a-i-ds-mx jb-tqr-lasta-art-a-i-ds-mx this_hide\">";
                m += "<span class=\"cursor-pointer jb-tqr-lasta-art-a-i-ds\" ></span>";
                m += "</div>";
            }
            m += "</a>";
            m += "</article>";
            
            m = $.parseHTML(m);
    
            /*
             * ETAPE :
             *      Insertion des données
             */
            $(m).find(".jb-tqr-lasta-art-mx").data("item",d.aid).data("jzncah",JSON.stringify(d));
            $(m).find(".jb-tqr-lasta-art-a-mx").attr("href",d.aplk);
            $(m).find(".jb-tqr-lasta-art-a-i").attr("src",d.aim).attr("alt",d.adsc);
            $(m).find(".jb-tqr-lasta-art-a-i-fd").data("type",d.acz_tp).attr("data-type",d.acz_tp);
            if ( cz === "NTWRK" ) {
                $(m).find(".jb-tqr-lasta-art-a-i-ds").text("@".concat(d.acz_apsd));
            }
            
            $(m).find(".jb-tqr-lasta-art-a-mx").hover(function(e){
                Kxlib_StopPropagation(e);
                        
                if ( $(m).find(".jb-tqr-lasta-art-a-i-ds-mx").length ) {
                    $(m).find(".jb-tqr-lasta-art-a-i-ds-mx").removeClass("this_hide");
                }
            },function(e){
                Kxlib_StopPropagation(e);
                
                if ( $(m).find(".jb-tqr-lasta-art-a-i-ds-mx").length ) {
                    $(m).find(".jb-tqr-lasta-art-a-i-ds-mx").addClass("this_hide");
                }
            });
            
            return m;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** LISTENERS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/
    
//    $(".jb-tqr-hlib-sec-bmx").off("hover").hover(function(e){
//    $(".jb-tqr-hlib-sec-bmx, .jb-tqr-hlib-sec-bmx *").off("hover").hover(function(e){
    $(".jb-tqr-hlib-sec-bmx > *").off("hover").hover(function(e){
        var th = ( $(this).is(".jb-tqr-hlib-sec-bmx") ) ? this : $(this).closest(".jb-tqr-hlib-sec-bmx");
        $(th).stop(true,true).delay( 400 ).animate({
//            left : 0
            left : "-1px"
        });
    },function(e){
        var th = ( $(this).is(".jb-tqr-hlib-sec-bmx") ) ? this : $(this).closest(".jb-tqr-hlib-sec-bmx");
        $(th).stop(true,true).animate({
            left : "-77px"
        });
    });
    
    $(".jb-tqr-hlib-n-l-n-e, .jb-tqr-hl-n-n-tgr").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
})();




/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/***********************************************************************************       TRENQR SHORTCUTS SCOPE (ALIAS GPS)       ***********************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/

(function(){
    var _xhr_sugg_ga;
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** PROCESS SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_Action = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity($(x).data("action")) && !a ) ) {
                return;
            }
            
            var _a = ( a ) ? a : $(x).data("action");
            switch (_a) {
                case "open" :
                        _f_Opn(x);
                    break;
                case "close" :
                        _f_Clz(x);
                    break;
                case "add_post_xyz" :
                case "add_post_trd" :
                case "create_trend" :
                        _f_Brain(x,_a);
                    break;
                case "friend_mi" :
                case "new_search" :
                        _f_AsdApps(x,_a);
                    break;
                case "newsfeed" :
                        _f_Nwfd(x);
                    break;
                case "jump" :
                        _f_Jump(x);
                    break;
                default :
                    return;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_Opn = function (x){
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            /*
             * ETAPE :
             *      On s'assure que la zone est bien fermée.
             */
            if (! $(".jb-tqr-shcts-tpgrp").hasClass("this_hide") ) {
                $(x).data("lk",0);
                return;
            }
            
            /*
             * ETAPE :
             *      On ouvre la zone.
             */
            $(".jb-tqr-shcts-tpgrp").stop(true,true).hide().removeClass("this_hide").show("blind",function(){
                $(".jb-tqr-shcts-bmx").data("state","on");
                $(".jb-tqr-shcts-ftr-clz").removeClass("this_hide");
                $(x).data("lk",0);
            });
            
            /*
             * ETAPE : 
             *      On ajoute le texte
             */
            $(".jb-tqr-shcts-ftr-tgr").text("Raccourcis");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Clz = function (x){
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            /*
             * ETAPE :
             *      On s'assure que la zone est bien ouverte.
             */
            if ( $(".jb-tqr-shcts-tpgrp").hasClass("this_hide") ) {
                $(x).data("lk",0);
                return;
            }
            
            /*
             * ETAPE : 
             *      On retire le texte
             */
//            $(".jb-tqr-shcts-ftr-tgr").text("");
            
            /*
             * ETAPE :
             *      On ferme la zone.
             */
            $(".jb-tqr-shcts-tpgrp").stop(true,true).hide("blind",function(){
                $(".jb-tqr-shcts-tpgrp").addClass("this_hide");
                $(".jb-tqr-shcts-ftr-clz").addClass("this_hide");
                $(".jb-tqr-shcts-bmx").data("state","off");
                $(x).data("lk",0);
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Nwfd = function (x){
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            $(".jb-global-nav-elt[data-target='nwfd']").click();
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Brain = function (x,a){
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            switch (a) {
                case "add_post_xyz" :
                       /*
                        * ETAPE :
                        *      On s'assure que l'utilisateur voit la zone d'ajout
                        */
                        if ( $(window).scrollTop() !== 585 ) {
                            $("html, body").animate({ scrollTop: "585px" });
                        }
                       
                        /*
                         * ETAPE :
                         *      On appuie sur les boutons pour arriver au formulaire
                         */
                        $(".jb-brn-bk").click();
                        $("#brain_menu_new-ml").click();
                    break;
                case "create_trend" :
                       /*
                        * ETAPE :
                        *      On s'assure que l'utilisateur voit la zone d'ajout
                        */
                        if ( $(window).scrollTop() !== 585 ) {
                            $("html, body").animate({ scrollTop: "585px" });
                        }
                        
                        /*
                         * ETAPE :
                         *      On appuie sur les boutons pour arriver au formulaire
                         */
                        $("#brain_menu_new-ml-tr").click();
                        $("#brain_submenu_newtr").click();
                        $("#newtr_title").focus();
                    break;
                case "add_post_trd" :
                       /*
                        * ETAPE :
                        *      On s'assure que l'utilisateur voit la zone d'ajout
                        */
                        if ( $(window).scrollTop() > 635 ) {
                            $("html, body").animate({ scrollTop: "580px" });
                        } 
                        
                        /*
                         * ETAPE :
                         *      On appuie sur les boutons pour arriver au formulaire
                         */
                        if ( $("#nwtrdart-box").hasClass("this_hide") ) {
                            $(".jb-acc-nwtrart").click();
                        }
                        $(".jb-na-box-input-txt").focus();
                        
                    break;
                default :
                    return;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_AsdApps = function (x,a){
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            /*
             * ETAPE :
             *      On s'assure que l'utilisateur voit la zone d'ajout
             */
            if ( $(window).scrollTop() !== 370 ) {
                $("html, body").animate({ scrollTop: "370px" });
            }
            switch (a) {
                case "friend_mi" :
                        /*
                         * ETAPE :
                         *      On appuie sur les boutons pour arriver à la zone
                         */
                        $(".jb-asd-apps-chc[data-action='gochatbox']").click();
                    break;
                case "new_search" :
                        /*
                         * ETAPE :
                         *      On appuie sur les boutons pour arriver à la zone
                         */
                        $(".jb-asd-apps-chc[data-action='gosearchbox']").click();
                        $(".jb-asd-srch-ipt").focus();
                    break;
                default :
                    return;
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Jump = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            /*
             * ETAPE :
             *      On vérifie la disponibilité du bouton.
             */
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            /*
             * ETAPE :
             *      On change l'état du bouton.
             */
            $(x).data("stt","loading");
            $(x).attr("data-stt","loading");
            var m = Kxlib_getDolphinsValue("COMLG_Wait3p");
            $(x).text(m);
            
            /*
             * [DEPUIS 025-05-16]
             */
            var mode = "_MD_TAKME_TOTHMN";
            
            var s = $("<span/>");
            _f_Srv_PlSuggs(mode,s);
            
            $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    return;
                }
                
                /*
                 * ETAPE :
                 *      On affiche le message au niveau du bottom demandant de patienter
                 */
                m = Kxlib_getDolphinsValue("COMLG_Loading3p");
                $(".jb-pg-sts").text(m).removeClass("this_hide");
                
                /*
                 * ETAPE :
                 *      On lance la rediction
                 */
                window.location.href = d.tmttm;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** SERVER SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _Ax_PlSuggs = Kxlib_GetAjaxRules("TQR_SUGG_GETALL");
    var _f_Srv_PlSuggs = function(mode,s) {
        if ( KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
//            alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
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
                } else if ( !KgbLib_CheckNullity(datas.return) && ( !KgbLib_CheckNullity(datas.return.tmttm) ) ) {
                    var ds = [datas.return];
                    $(s).trigger("datasready",ds);
                } else {
                    $(s).trigger("operended");
                    return;
                }
                
            } catch (ex) {
                //TODO : Renvoyer l'erreur au serveur
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                _xhr_sugg_ga = null;
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
            "urqid": _Ax_PlSuggs.urqid,
            "datas": {
                "mode"  : (! KgbLib_CheckNullity(mode) ) ? mode : null,
                "cu"    : curl
            }
        };
        
        _xhr_sugg_ga = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_PlSuggs.url, wcrdtl : _Ax_PlSuggs.wcrdtl });
    };
    
    /*******************************************************************************************************************************************************************/
    /**************************************************************************** VIEW SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** LISTENERS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/

    $(".jb-tqr-shcts-ftr-tgr, .jb-tqr-shcts-ftr-cl, .jb-tqr-shcts-ftr-clz, .jb-tqr-shcts-ch").not(".jb-tqr-shcts-ch[data-scp='go_home'], .jb-tqr-shcts-ch[data-scp='invite_friend'], .jb-tqr-shcts-ch[data-scp='trenqr_studio']").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
            
        
    
    /*******************************************************************************************************************************************************************/
    /***************************************************************************** AUTO SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    if ( $(".jb-tqr-shcts-bmx").length ) {
        if ( $(this).scrollTop() > 0 ) {
            var h = ( $("#fksa-header").length ) ? $("#fksa-header").height()+1 : $("#header").height();
            var tp = ( h-$(this).scrollTop() < 0 ) ? 0 : h-$(this).scrollTop();
            $(".jb-tqr-shcts-bmx").css({
                top : tp
            });
        } else {
            $(".jb-tqr-shcts-bmx").removeAttr("style");
        }
    } 
})();




/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/***********************************************************************************       ASIDE-RICH-BANNER : ADS & "ASDAPP"       ***********************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/

(function(){
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** PROCESS SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_SwImHigh = function (x,shw,lk) {
        /*
         * Permet de switcher entre le mode "Floating" et "Asided"
         */
        try {
//            if ( !KgbLib_CheckNullity($(".jb-asd-apps-pin-btn")) && $(".jb-asd-apps-pin-btn").data("for") === 2 ) {
//                //On fait réapparaitre le cadenas
//                $(".jb-asd-apps-pin-btn").removeClass("this_hide");
//            }

            if ( !KgbLib_CheckNullity($(".jb-aside-mods").data("lk")) && $(".jb-aside-mods").data("lk") === 1 ) {
                return;
            }
            
            /*
             * [DEPUIS 17-06-15] @BOR 
             * La solution de "zoom" n'était pas optimale pour les écrans de petite taille.
             * J'ai préféré une solution qui bloque la zone. On ne perd pas en qualité.
             * De plus, cette solutoin était necessaire pour permettre que les publicités en mode WLC soient toujours visibles.
             */
            var t__ = $("#header").outerHeight() - 365 ;
//            var t__ = $(x).scrollTop() - 369 ;
            
//            Kxlib_DebugVars([TOPx : "+t__]);
//            Kxlib_DebugVars([hw, typeof x !== undefined, $(x).length]);
            if ( shw && typeof x !== undefined && $(x).length ) {
//                $("#aside-bmx").css({ top: t__+"px" });
                $(".jb-aside")
                    .stop(true,true)
                    .addClass("flying")
                    .css({
                        top : t__+"px",
                    });;
                if ( $(".jb-page").width() > $(window).width() ) {
                    $(".jb-aside").css({
                        right : "1px"
                    });
                }
                    
            } else {
//                $("#aside-bmx").css({ top: "0" });
                $(".jb-aside")
                    .stop(true,true)
                    .removeClass("flying")
                    .removeAttr("style");
            }
            
            
            /*
            if (shw) {
                $(".jb-aside-mods").addClass("iamhigh");
                $(".jb-asd-apps-ch-mx").addClass("iamhigh");
                $(".jb-asd-apps-pin-btn").addClass("iamhigh");
            } else {
                $(".jb-aside-mods").removeClass("iamhigh");
                $(".jb-asd-apps-ch-mx").removeClass("iamhigh");
                $(".jb-asd-apps-pin-btn").removeClass("iamhigh");
            }
            //*/
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** SERVER SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    /*******************************************************************************************************************************************************************/
    /**************************************************************************** VIEW SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** LISTENERS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    var top, frc_tp, unit;
    /*var top = $("#aside-bmx").outerHeight() + $("#header").outerHeight() - unit - $("#legals").outerHeight() - 40/*Ajustement manuel*/;
    $(window).off("scroll").scroll(function(e){
//        $(".jb-wos-csl-opt-steam").text("TOP : "+$(this).scrollTop());
//        Kxlib_DebugVars("TOP : "+$(this).scrollTop());
        
        /*
         * [DEPUIS 18-11-15] @author BOR
         *      HACK : Pour récupérer les données SCROLLTOP depuis le fichier de gestion "hview.js".
         *  [NOTE  18-11-15] @author BOR
         *      En mode : "Mais pourquoi n'y ai je pas pensé plus tôt ? ... :( :D
         */
        if ( $("div[s-id='TQR_GTPG_HVIEW']").length && $("div[s-id='TQR_GTPG_HVIEW']").find(".jb-tqr-snifr-wdw-scltp").length ) {
            $("div[s-id='TQR_GTPG_HVIEW']").find(".jb-tqr-snifr-wdw-scltp").trigger("my-scroll",[$(this).scrollTop()]);
        }
        
        /*
         * [DEPUIS 08-11-15] @author BOR
         */
        if ( $(".jb-tqr-shcts-bmx").length ) {
            
            if ( $(this).scrollTop() > 0  ) {
                var h = ( $("#fksa-header").length ) ? $("#fksa-header").height()+1 : $("#header").height();
                var tp = ( h-$(this).scrollTop() < 0 ) ? 0 : h-$(this).scrollTop();
                $(".jb-tqr-shcts-bmx").css({
                    top : tp
                });
            } else {
                $(".jb-tqr-shcts-bmx").removeAttr("style");
            }
        } 
        
        
       /*
        * [DEPUIS 04-11-15] @author
        *       J'ai transféré la gestion du cas du positionnement d'ASIDEAPP ici pour permettre aux deux "modules" de fonctionner.
        *       En effet, il semblerait qu'un seul listerner sur WINDOW.SCROLL soit autorisé.
        */
        if ( $(".jb-asd-apps-pin-btn").length && $(".jb-asd-apps-pin-btn").attr("data-state") === "ulock" ) {
            
            if ( $(".jb-aside-mods") && $(".jb-aside-mods").length && $(this).scrollTop() >= 365 ) {
                _f_SwImHigh(this,true);
            } else {
                _f_SwImHigh(this);
            }
            
        } else if ( !$(".jb-asd-apps-pin-btn").length || ( $(".jb-asd-apps-pin-btn").length && $(".jb-asd-apps-pin-btn").attr("data-state") === "lock" ) ) {
           /*
            * [NOTE]
            *   1800 = Zone de sécurité en attendant que les SUGGS apparaissent. Dans le cas contraire la hauteur est faussée
            */
            if (! ( frc_tp || ( !frc_tp && $(this).scrollTop() >= 1800 ) ) ) {
                return;
            }
            
            /*
             * [NOTE]
             *      L'utilisation de 'unit' résulte du fait qu'on doit faire marcher l'algorithme pour tous les cas de pages
             */
            unit = ( $(".jb-asd-rch-sctn[data-section='ad1']").length ) ? $(".jb-asd-rch-sctn[data-section='ad1']").outerHeight() : $(".jb-asd-rch-s-tsg-lst.last").outerHeight();
            
            top = ( frc_tp ) ? frc_tp : $("#aside-bmx").outerHeight() + $("#header").outerHeight() - unit - $("#legals").outerHeight() - 40/*Ajustement manuel*/;
//            console.log(top);
            
            if ( $(this).scrollTop() >= top ) {
                
                var x__ = ( $(".jb-asd-rch-sctn[data-section='ad1']").length ) ? unit * 2 : unit;
                var t__ = ( (-1) * ( $("#aside-rich-banner").outerHeight() + 91/*margin-top*/ ) ) + ( ( x__ ) + $("#legals").outerHeight() + 40/*Ajustement manuel*/ + 15/*Margin-top*/ );
                
                $("#aside-rich-banner").css({ 
                    position: "fixed",
                    top: t__+"px"
                });
                 
                /*
                 * [NOTE]
                 *      Ne marchera que dans le cas de WLC
                 * [DEPUIS 25-06-16]
                 */
//                $(".jb-asd-rch-sctn[data-section='ad1']").insertAfter(".jb-asd-rch-sctn[data-section='ad2']");
                
                frc_tp = $("#header").outerHeight() + $("#aside-bmx").outerHeight() + $("#aside-rich-banner").outerHeight() + 91/*margin-top*/ - ( (unit*1) + $("#legals").outerHeight() + 40/*Ajustement manuel*/ );
            } else {
                $("#aside-rich-banner").removeAttr("style");
                /*
                 * [NOTE]
                 *      Ne marchera que dans le cas de WLC
                 * [DEPUIS 25-06-16]
                 */
//                $(".jb-asd-rch-sctn[data-section='ad1']").insertBefore(".jb-asd-rch-sctn[data-section='profil-sugg']");
                
                frc_tp = $("#aside-bmx").outerHeight() + $("#header").outerHeight() - unit - $("#legals").outerHeight() - 40/*Ajustement manuel*/; 
            }
        }
        
    });
    
})();
    
/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/***********************************************************************************        TRENQR TOOLS & CO : SKY_CROPPER         ***********************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/

(function(){
    
    /*
     * CpDs : CropperDatas
     * ctx  : ConTeXt
     * cvs  : CanVaS
     */
    var _CpDs = {}, _ctx, _cvs, _img, _file;
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** PROCESS SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_Gdf = function () {
        var dt = {
            "imgRcdSz" : "600"
        }; 
        
        return dt;
    };
    
    var _f_Action = function (x,a) {
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity(a) && KgbLib_CheckNullity($(x).data("action")) ) ) {
                return;
            }
            
            var _a = ( KgbLib_CheckNullity(a) ) ? $(x).data("action") : a;
            switch (_a) {
                case "open" :
                        _f_Open();
                    break;
                case "abort" :
                        _f_Abort(x);
                    break;
                case "crop" :
                        _f_Crop(x);
                    break;
                default:
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_Open = function () {
        try {
            
            var fmw = $(".jb-tqr-skyc-b-t-i-mx").width();
            
            /*
             * ETAPE :
             *      On vide les INPUT
             */
            $(".jb-tqr-skyc-b-t-i-ipt, .jb-tqr-skyc-b-t-rnw-ipt").val("");
            
            /*
             * ETAPE : 
             *      On affiche la zone
             */
            $(".jb-tqr-skycrpr-sprt").removeClass("this_hide");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var  _f_IptChg = function (x,f) {
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(f) ) {
                return;
            }
            
            var URL = window.URL || window.webkitURL;
            if ( URL ) {
                if (! /^image\/\w+$/.test(f.type) ) {
                    alert("Ce fichier n'est pas une image !");
                }
                
               /*
                * ETAPE :
                *      On fait passer l'input à l'arrière plan pour éviter qu'il ne soit de nouveau sollicité.
                */
                _f_MnIptLyt(2);
            
                _file = f;
                _f_UpdCvs(URL.createObjectURL(f),true);
                    
                /*
                 * ETAPE :
                 *      On réinitialise l'INPUT
                 */
//                console.log($(x).attr("id"));
                $(x).val("");
                
                /*
                 * ETAPE :
                 *      On recentre le canvas
                 */
                _f_CtrCvs();
                   
                /*
                 * ETAPE :
                 *      On reset le ZOOM STICKER
                 */
                _f_RstZmSt();
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_MnIptLyt = function (lyt) {
        try {
            if ( KgbLib_CheckNullity(lyt) ) {
                return;
            }
                    
            switch (lyt) {
                case 1 :
                        $(".jb-tqr-skyc-b-t-i-ipt").css({
                            "z-index"   : 5
                        });
                        $(".jb-tqr-skyc-canvas").css({
                            "z-index"   : 4
                        });
                    break;
                case 2 :
                        $(".jb-tqr-skyc-b-t-i-ipt").css({
                            "z-index"   : 4
                        });
                        $(".jb-tqr-skyc-canvas").css({
                            "z-index"   : 5
                        });
                    break;
                default : 
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_UpdCvs = function (daul,isld) {
        //daul : DataUrL; isld : ISLoaD
        try {
            if ( KgbLib_CheckNullity(daul) ) {
                return;
            }
             
            var canvas = _cvs = $(".jb-tqr-skyc-canvas")[0];
            var ctx = _ctx = canvas.getContext('2d');
            img = new Image();
            img.onload = function() {
                $image = $(img);

                /*
                 * ETAPE :
                 *      On fait FIT l'image en fonction de sa taille et de son orientation
                 * [29-06-16]
                 *      J'ai amélioré l'algoithme pour corriger les bogues et prendre en compte les images SQUARE et d'autres problèmes.
                 */
                var dims;
                if ( ( img.width/img.height ) === 1 ) {
                    dims = {
                        width   : $(".jb-tqr-skyc-b-t-i-mx").width(),
                        height  : $(".jb-tqr-skyc-b-t-i-mx").height(),
                    };
                } else {
                    dims = _f_CvsFit(img.width,img.height);
                }
                
               /*
                * [DEPUIS 29-06-16]
                *      On réinitialise le CANVAS
                */
                $(".jb-tqr-skyc-canvas").removeAttr("width height style");
                
                /*
                 * On redimensionne le canvas en fonction de l'environnement en présence
                 */
                canvas.width = dims.width;
                canvas.height = dims.height;

                ctx.drawImage(img,0,0,dims.width,dims.height);
                _img = img;
                
                $(".jb-tqr-skyc-canvas").data("ori",{iw:dims.width,ih:dims.height});

//                EVENT.declareEvent("tqs.applyingEnd",null,{mod : mn});
                
            };
            img.src = daul;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_CtrCvs = function () {
        try {
            
            /*
             * [NOTE 04-12-15]
             *      La solution consistant à centrer à l'aide de margin:auto et top,right.. 0 ne fonctionne pas dans ce cas.
             */
            $(".jb-tqr-skyc-canvas").css({
                top     : "50%",
                left    : "50%",
                transform: "translate(-50%, -50%)"
            });
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_RstZmSt = function () {
        //RstZmSt : ReSetZooMSticker
        try {
            
            $(".jb-tqr-skyc-b-t-zm-tgr").css({
                left    : "0"
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_CvsFit =  function(w,h,wrf) {
        //wrf = WidthReFerence
        try {
            if ( KgbLib_CheckNullity(w) | KgbLib_CheckNullity(h) ) {
                return;
            }
            
            //zw : ZoneWidth
            var zw = $(".jb-tqr-skyc-b-t-i-mx").width();
            /*
             * On sélectionne la bonne valeur de référence dans le cas où elle n'a pas été fournie.
             */
            var wd = (! KgbLib_CheckNullity(wrf) ) ? wrf : zw;
            
            /*
             * ETAPE :
             *      On s'assure que la dimension de référence n'est pas trop petite.
             *      On effectue le controle au cas où la référence manuelle passée par CALLER est fausse.
             */
            wd = ( wd < zw ) ? zw : wd;
            
            /*
             * Détermination du coef.
             * On doit prendre en compte le cas de l'image rectangle ayant une hauteur plus grande que la largeur
             */
            var coef = ( h > w ) ? wd/w : wd/h;
                    
            /*
             * On renvoie les nouvelles dimensions
             */   
            var dims = {
                height  : h*coef,
                width   : w*coef
            };
            return dims;
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Abort = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(".jb-tqr-skyc-fnt-chc").data("lk",1);
            
            _f_Sprt();
            
            /*
             * ETAPE : 
             *      Retirer l'image de l'INPUT file.
             */
            $(".jb-tqr-skyc-b-t-i-ipt, .jb-tqr-skyc-b-t-rnw-ipt").val("");
            
            /*
             * ETAPE :
             *      On clear l'image au niveau du canvas
             */
            var fmw = $(".jb-tqr-skyc-b-t-i-mx").width();
            _ctx.clearRect(0,0, fmw, fmw);
            
            /*
             * ETAPE : 
             *      Reintialiser le cusrur du ZOOM.
             */
            _f_RstZmSt();
            
            /*
             * ETAPE :
             */
            $(".jb-tqr-skyc-fnt-chc").data("lk",0);
            
            /*
             * [DEPUIS 11-06-16]
             */
            $(".jb-nwpst-ab-pic").click();
            
            /*
             * [DEPUIS 29-06-16]
             *      On réinitialise le CANVAS
             */
            $(".jb-tqr-skyc-canvas").removeAttr("width height style");
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Crop = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(".jb-tqr-skyc-fnt-chc").data("lk",1);
             
            /*
             * ETAPE : 
             *      Récupération des coordonnées de l'image
             */
            var fmw = $(".jb-tqr-skyc-b-t-i-mx").width(), iw = $(".jb-tqr-skyc-canvas").width(), ih = $(".jb-tqr-skyc-canvas").height();
            var coor = {
                t   : $(".jb-tqr-skyc-canvas").position().top * (-1),
                l   : $(".jb-tqr-skyc-canvas").position().left * (-1)
            };
//            Kxlib_DebugVars([coor.t,coor.l],true);
//             return;
             
            /*
             * ETAPE : 
             *      On rogne l'image 
             */
            var cvs = $("<canvas/>",{
                id : "tqr-skyc-b-t-i-cvs",
                class : "jb-tqr-skyc-canvas"
            }).draggable({ 
                drag : function(e,ui){
                    _f_DragPic(e,ui,this);
                }
            });
            
//            Kxlib_DebugVars([_img.height,_img.width,_f_Gdf().imgRcdSz],true);
//            Kxlib_DebugVars([coor.l, coor.t,],true);
//            return;

            var canvas = cvs[0];
            canvas.setAttribute('width', 600);
            canvas.setAttribute('height', 600);
            var ctx = canvas.getContext('2d');
//            Kxlib_DebugVars([coor.t,coor.l,fmw],true);
//            ctx.drawImage(_cvs, coor.l, coor.t, fmw, fmw, 0, 0, fmw, fmw);
            ctx.drawImage(_cvs, coor.l, coor.t, fmw, fmw, 0, 0, 600, 600);
            
            $(".jb-tqr-skyc-canvas").replaceWith(canvas);
            
            var idaul = canvas.toDataURL();
            var image = new Image() || document.createElement('img');
            image.onload = function() {
                /*
                 * ETAPE :
                 *      On récupère le nom du fichier
                 */
                var t1__ = _file.name.replace(/^.*\/|\.[^.]*$/g, ''), xt = _file.type.replace(/^image\/(\w+)$/, ".$1");
                xt = ( xt === ".jpeg" ) ? ".jpg" : xt;
                var fn = t1__.concat(xt);

                /*
                 * ETAPE :
                 *      On ajoute les données sur WIDTH et HEIGHT.
                 *      On se base sur ...
                 */
//                image.width = 400;
//                image.height = 400;

//                Kxlib_DebugVars([fn,image.width,image.height],true);

                /*  
                 * ETAPE :
                 *      On masque la zone
                 */
                _f_Sprt();
                
                /*
                 * ETAPE :
                 *      On signale via un évènement qu'une image est disponible.
                 */
                if ( $(".jb-tqr-skycrpr-snit[data-target='brain']").length ) {
                    $(".jb-tqr-skycrpr-snit[data-target='brain']").trigger("change",[image,fn,coor]);
                } else if ( $(".jb-tqr-skycrpr-snit[data-target='trpgnewbx']").length ) {
                    $(".jb-tqr-skycrpr-snit[data-target='trpgnewbx']").trigger("change",[image,fn,coor]);
                }
                
                
                /*
                 * ETAPE :
                 *      On clear l'image au niveau du canvas
                 */
                _ctx.clearRect(0,0,fmw,fmw);
               
                /*
                 * ETAPE : 
                 *      Retirer l'image de l'INPUT file.
                 */
                $(".jb-tqr-skyc-b-t-i-ipt, .jb-tqr-skyc-b-t-rnw-ipt").val("");
                
                $(".jb-tqr-skyc-fnt-chc").data("lk",0);
            };
            image.src = idaul;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_DragPic = function(e,ui,x) {
        try {
            if ( KgbLib_CheckNullity(e) | KgbLib_CheckNullity(ui) | KgbLib_CheckNullity(x) ) {
                return;
            }
            
            $(".jb-tqr-skyc-canvas").css({
                top     : "",
                left    : "",
                transform : ""
            });
            
//            console.log("TOP > ",ui.position.top,"LEFT > ",ui.position.left);
            
            /*
             * ETAPE :
             *      On effectue des opération permettant que l'image ne sorte pas du cadre
             */
            
            /*
             * GLOSSAIRE :
             *      l       : Left
             *      t       : Top
             *      rfh     : ReferenceHeight
             *      rfw     : ReferenceWidth
             *      fln     : FrameworkLength
             */
            var l = parseInt(ui.position.left), t = parseInt(ui.position.top), rfh = $(x).height(), rfw = $(x).width(), fln = $(".jb-tqr-skyc-b-t-i-mx").width();
            
//            Kxlib_DebugVars(["SCROLL_ZOOM : TOP : ", t, "; LEFT : ", l,"; IMG_WIDTH : ",rfw,"; IMG_HEIGHT : ", rfh,"; CANVAS_REF : ", fln]);
            
            /*
             * ETAPE : 
             *      On s'assure que l'image ne sort pas du cadre sur l'axe HORIZONTAL.
             */
            var lmx = fln - rfw;
            if ( l >= 0 ) {
                $(x).css({left: 0});
                ui.position.left = 0;
            } else if ( l <= lmx ) {
                $(x).css({left: lmx});
                ui.position.left = lmx;
            } 
            /*
             * ETAPE : 
             *      On s'assure que l'image ne sort pas du cadre sur l'axe VERTICAL.
             */
            var tmx = fln - rfh;
            if ( t >= 0 ) {
                $(x).css({top: 0});
                ui.position.top = 0;
            } else if ( t <= tmx ) {
                $(x).css({top: tmx});
                ui.position.top = tmx;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_DragZmSt = function(e,ui,x) {
        try {
            if ( KgbLib_CheckNullity(e) | KgbLib_CheckNullity(ui) | KgbLib_CheckNullity(x) ) {
                return;
            }
            
            /*
             * ETAPE : 
             *      Vérifie qu'il y a bien une image au niveau du canvas
             */
            if ( KgbLib_CheckNullity($(".jb-tqr-skyc-canvas").data("ori")) ) {
                return;
            }
            
            /*
             * lnw  : LiNeWidth
             * r    : Right
             * l    : Left
             */
            var l = parseInt(ui.position.left), lnw = $(".jb-tqr-skyc-b-t-zm").width(), r = lnw - l - $(x).width();
//            Kxlib_DebugVars(["SCROLL_ZOOM : LEFT : ", l,"; RIGHT : ",r]);
            var lnw_ = lnw - $(x).width();
            if ( l <= 0 ) {
                $(x).css({left: 0});
                ui.position.left = 0;
            } else if ( r <= 0 ) {
                $(x).css({left: lnw_});
                ui.position.left = lnw_;
            } 
            
            $(".jb-tqr-skyc-canvas").css({
                top     : $(".jb-tqr-skyc-canvas").position().top,
                left    : $(".jb-tqr-skyc-canvas").position().left,
                transform : ""
            });
            
            /*
             * ETAPE :
             *      On effectue une suite d'opération pour Zoomer/Dezoomer
             */
            var fmw = $(".jb-tqr-skyc-b-t-i-mx").width(), iw = $(".jb-tqr-skyc-canvas").width(), ih = $(".jb-tqr-skyc-canvas").height(), zsw = lnw_;
            var oriw = Math.round($(".jb-tqr-skyc-canvas").data("ori").iw), orih = Math.round($(".jb-tqr-skyc-canvas").data("ori").ih);
            var niw = Math.round(oriw+(l*(oriw/zsw))), nih = Math.round(orih+(l*(orih/zsw)));
            
//            console.log("RGBX >>> ","LEFT : ", l,"; ORI_WIDTH : ", oriw,"; ORI_HEIGHT : ",orih, "NEW_WIDTH : ", niw,"; NEW_HEIGHT : ",nih);
//            return;
            var fnlw, fnlh;
            //On s'assure que la WIDTH ne dépasse la limite MAX
            if ( niw <= oriw ) { fnlw = oriw; } 
            else if ( niw >= oriw*2 ) { fnlw = oriw*2; }
            else { fnlw = niw; }
            //On s'assure que la HEIGHT ne dépasse la limite MAX
            if ( nih <= orih ) { fnlh = orih; } 
            else if ( nih >= orih*2 ) { fnlh = orih*2; }
            else { fnlh = nih; }
            //On s'assure que la position TOP ne dépasse les limites
            var nit = $(".jb-tqr-skyc-canvas").position().top;
            if ( nit >= 0 ) { nit = 0; } 
            else if ( nit <= (fmw-fnlh) ) { nit = (fmw-fnlh); }
            //On s'assure que la position LEFT ne dépasse les limites
            var nil = $(".jb-tqr-skyc-canvas").position().left;
            if ( nil >= 0 ) { nil = 0; } 
            else if ( nil <= (fmw-fnlw) ) { nil = (fmw-fnlw); }
            
//            console.log("TOP : ", $(".jb-tqr-skyc-canvas").position().top, "LEFT : ", $(".jb-tqr-skyc-canvas").position().left, "NEW_TOP : ", nit, "NEW_LEFT : ", nil, "; ORI_WIDTH : ", oriw,"; ORI_HEIGHT : ",orih, "NEW_WIDTH : ", niw,"; NEW_HEIGHT : ",nih);
//            return;
            var dims = {
                top     : Math.round(nit),
                left    : Math.round(nil),
                width   : Math.round(fnlw),
                height  : Math.round(fnlh)
            };
            /*
            Kxlib_DebugVars(["SCROLL_ZOOM : RGBX >>> ","TOP : ", dims.top,"; LEFT : ",dims.left]);
            Kxlib_DebugVars(["SCROLL_ZOOM : RGBX >>> ","WIDTH : ", dims.width,"; HEIGHT : ",dims.height]);
            Kxlib_DebugVars(["SCROLL_ZOOM 2 : ", _cvs]);
            //*/
//            _cvs.width = dims.width;
//            _cvs.height = dims.height;
            /*
             * [DEPUIS 29-06-16]
             *      Amélioration de l'algorithme pour corriger des BOGUES dus à l'assignation des valeurs
             */
            $(".jb-tqr-skyc-canvas")[0].width = dims.width;
            $(".jb-tqr-skyc-canvas")[0].height = dims.height;
            $(".jb-tqr-skyc-canvas").css({
                width   : "".concat(dims.width,"px"),
                height  : "".concat(dims.height,"px"),
                top     : dims.top,
                left    : dims.left
            });
            
            _ctx.drawImage(_img,0,0,dims.width,dims.height);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** SERVER SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    /*******************************************************************************************************************************************************************/
    /**************************************************************************** VIEW SCOPE ***************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_Sprt = function(sh) {
        try {
            
            if ( sh ) {
                $(".jb-tqr-skycrpr-sprt").removeClass("this_hide");
            } else {
                $(".jb-tqr-skycrpr-sprt").addClass("this_hide");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** LISTENERS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    /*
    //ABONDONNÉ
    $(".jb-tqr-skycrpr-bmx").draggable({ 
        containment: "parent",
        handle : ".jb-tqr-skycrpr-hdr-mx",
        cursor: "move",
        drag : function(e,ui){
            $(this).css({
                margin: 0
            });
        }
    });
    //*/
    $(".jb-tqr-skyc-canvas").draggable({ 
        drag : function(e,ui){
            _f_DragPic(e,ui,this);
        }
    });
    
    $(".jb-tqr-skyc-b-t-zm-tgr").draggable({ 
        axis : "x",
        drag : function(e,ui){
            _f_DragZmSt(e,ui,this);
        },
        scrollSpeed: 10
    });
    
    $(".jb-tqr-skyc-b-t-i-ipt, .jb-tqr-skyc-b-t-rnw-ipt").change(function(e){
        var file = this.files[0];
        
        _f_IptChg(this,file);
    });
    
    $(".jb-tqr-skyc-fnt-chc").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Action(this);
    });
    
    $(".jb-tqr-skycrpr-sprt").on("open",function(e){
        Kxlib_PreventDefault(e);
        
        _f_Open();
    });
    
    $(".jb-tqr-skycrpr-sprt").on("open_with_datas",function(e){
        Kxlib_PreventDefault(e);
        
        var file = $(".jb-tqr-skycrpr-src").data("src");
//        Kxlib_DebugVars([file,typeof file],true);
        _f_Open();
        var ipt = $(".jb-tqr-skyc-b-t-i-ipt");
        _f_IptChg(ipt,file);
    });
    
    $(".jb-tqr-skycrpr-sprt").on("close",function(e){
        Kxlib_PreventDefault(e);
        
        _f_Abort();
    });
})();


/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/***********************************************************************************        ASIDE-RICH-BANNER : ARTCILES COM        ***********************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/


(function(){
    var _xhr_art_fav;
    
    /*******************************************************************************************************************************************************************/
    /**************************************************************************** PROCESS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_Gdf = function () {
        var dt = {
            "" : "",
        }; 
        
        return dt;
    };
    
    var _f_Action = function (x,a){
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity(a) && KgbLib_CheckNullity($(x).data("action")) ) ) {
                return;
            }
            
            var _a = ( KgbLib_CheckNullity(a) ) ? $(x).data("action") : a;
            switch (_a) {
                case "favorite" :
                        _f_Fav(x,_a);
                    break;
                case "fav_public" :
                        _f_Fav(x,_a);
                    break;
                case "fav_private" :
                        _f_Fav(x,_a);
                    break;
                case "download" :
                        _f_Dwld(x);
                    break;
                case "report" :
                        _f_Rprt(x);
                    break;
                default:
                    return;
            }
            
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
            switch (_a) {
                case "favorite" :
                case "unfavorite" :
                case "fav_public" :
                case "fav_private" :
                case "fav_cancel" :
                        _f_Fav(x,_a);
                    break;
                case "download" :
                        _f_Dwld(x);
                    break;
                case "report" :
                        _f_Rprt(x);
                    break;
                default:
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Fav = function (x,a){
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
            /*
             * [ETAPE]
             *      Disponibilité du BOUTON
             */
            if ( $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            /*
             * [ETAPE]
             *      Vérification du POINTER AJAX
             */ 
//            if (! KgbLib_CheckNullity(_xhr_art_fav) ) {
//                $(x).data("lk",0);
//                return;
//            }
            
            var $ab, b = $(x).closest(".jb-tqr-art-abr-fav-bmx"), fac;
            $ab = $(x).closest(".jb-tqr-fav-bind-arml");
            if ( !$ab.length ) {
                $(x).data("lk",0);
                return;
            }
            
            var scp = $(x).data("art-mdl");
            if ( !scp || $.inArray(scp,["on_page","on_unq","on_arp","on_fksa"]) === -1 ) {
                $(x).data("lk",0);
                return;
            }
            
            
            switch (a) {
                case "favorite" :
                        $ab.find(".jb-tqr-am-ax-box").stop(true,true).hide().addClass("disable").addClass("this_hide").fadeIn();
                        $ab.find(".jb-tqr-art-abr-fav-bmx").stop(true,true).hide().removeClass("this_hide").fadeIn();
                        $(x).data("lk",0);
                    return;
                case "fav_public" :
                        fac = "ART_XA_FAV_PUB";
                        $ab.find(".jb-tqr-art-abr-fav-bmx").stop(true,true).hide().addClass("this_hide").fadeIn();
                        $ab.find(".jb-tqr-am-ax-box").removeClass("disable");
                    break;
                case "fav_private" :
                        fac = "ART_XA_FAV_PRI";
                        $ab.find(".jb-tqr-art-abr-fav-bmx").stop(true,true).hide().addClass("this_hide").fadeIn();
                        $ab.find(".jb-tqr-am-ax-box").removeClass("disable");
                    break;
                case "unfavorite" :
                        fac = "ART_XA_UNFAV";
                    break;
                case "fav_cancel" :
                        $ab.find(".jb-tqr-art-abr-fav-bmx").stop(true,true).hide().addClass("this_hide");
                        if ( $.inArray(scp,["on_unq"]) !== -1 ) {
                            $ab.find(".jb-tqr-am-ax-box").stop(true,true).hide().removeClass("disable").removeClass("this_hide").fadeIn();
                        }
                        $(x).data("lk",0);
                    return;
                default :
                    return;
            }
//            return;
            /*
            if ( $(x).closest(".jb-unq-bind-art-mdl").length ) {
                ab = $(x).closest(".jb-unq-bind-art-mdl");
            } else if ( $(x).closest(".jb-tmlnr-mdl-std").length ) {
                ab = $(x).closest(".jb-tmlnr-mdl-std");
            }
            //*/
            
            var i;
            switch (scp) {
                case "on_page" :
                case "on_fksa" :
                        i = $ab.data("item") ;
                    break;
                case "on_unq" :
                        i = $ab.data("aitem") ;
                    break;
                case "on_arp" :
                    break;
                default :
                        $(x).data("lk",0);
                    return;
            }
            if ( KgbLib_CheckNullity(i) ) {
                return;
            }
            
//            Kxlib_DebugVars([scp,i,fac],true);
//            return;
            
            var s = $("<span/>"), xt = (new Date()).getTime();
            
            _f_Srv_AFav(i,fac,x,xt,s);
            
            $(s).on("datasready",function(e,d){
                if ( KgbLib_CheckNullity(d) ) {
                    $(x).data("lk",0);
                    return;
                }
                
                Kxlib_DebugVars(["AFAV_RESULT => ",JSON.stringify(d)],true);
//                return;
            
                /*
                 * ETAPE :
                 *      On s'assure de ne pas modifier de données au niveau du mauvais endroit
                 */
                //UPdate MOdel
                var upmo = false, hasfv = 0;
                if ( parseInt(xt) === parseInt(d.xt) ) {
                    upmo = true;
                    switch (a) {
                        case "unfavorite" :
                                $ab.find(".jb-tqr-art-abr-tgr")
                                    .data("action","favorite")
                                    .attr({
                                        "data-action"   : "favorite",
                                        "data-reva"     : "unfavorite",
                                        "title"         : "Mettre en favori",
                                        "data-revt"     : "Retirer des favoris"
                                    });
                            break;
                        case "fav_public" :
                        case "fav_private" :
                               $ab.find(".jb-tqr-art-abr-tgr")
                                    .data("action","unfavorite")
                                    .attr({
                                        "data-action"   : "unfavorite",
                                        "data-reva"     : "favorite",
                                        "title"         : "Retirer des favoris",
                                        "data-revt"     : "Mettre en favori"
                                    });
                                hasfv = 1;
                            break;
                        default :
                            $(x).data("lk",0);
                            return;
                    }
                }
                
                
                /*
                 * ETAPE :
                 *      Mise à ajour des données d'ENTETE et INDICATEURS visuels
                 */
                var atype = $(".jb-unq-art-mdl").data("atype");
                if ( $.inArray(scp,["on_unq","on_page"]) !== -1 && atype && $.inArray(atype,["psmn"]) === -1 ) {
                    /*
                     * [NOTE 19-05-16]
                     *      Cette section correspond au cas de UNQ (Je pense :s)
                     */
                    
                    /*
                     * ETAPE :
                     *      Récupérer du selecteur
                     */
                    var idi = ( scp === "on_unq" ) ? $(Kxlib_DataCacheToArray($(".jb-unq-art-mdl").data("item"))[0][1]) : $ab;
                    if (! $(idi).length ) {
                        $(x).data("lk",0);
                        return;
                    }
                    
                    /*
                     * ETAPE :
                     *      Mise à jour des données d'ENTÊTE.
                     *      La mise à jour se fait selon les cas.
                     */
                    if ( $.inArray($(idi).data("atype"),["iml","itr","inml","intr","tia-phtotk","tia-explr"]) !== -1 ) {
                        $(idi)
                            .data("hasfv",hasfv)
                            .attr("data-hasfv",hasfv);
                    }
                    
                    /*
                     * ETAPE :
                     *      Mise à jour des INDICATEURS visuels au niveau du modèle de ARTICLE.
                     *  NOTE :
                     *      La modification est indépendante de la gestion des CONFLITS. 
                     *      La mise à jour se fait selon les cas.
                     */
                    if ( !$(idi).find(".jb-tqr-art-abr-tgr").length ) {
                        $(x).data("lk",0);
                        return;
                    } 
                    /*
                     * PAGES :
                     *      - TMLNR
                     *      - TRPG
                     * MODELS :
                     *      - TMLNR_IML
                     *      - TMLNR_ITR
                     *      - TRPG_ITR
                     */
                    else if ( $.inArray($(idi).data("atype"),["iml","itr"]) !== -1 ) {
                        /*
                         * ETAPE :
                         *      Modification des INDICATEURS visuels
                         */
                        if ( parseInt(hasfv) === 1 ) {
                            $(idi).find(".jb-tqr-art-abr-tgr")
                                .data("action","unfavorite")
                                .attr({
                                    "data-action"   : "unfavorite",
                                    "data-reva"     : "favorite",
                                    "title"         : "Retirer des favoris",
                                    "data-revt"     : "Mettre en favori"
                                });
                        } else {
                            $(idi).find(".jb-tqr-art-abr-tgr")
                                .data("action","favorite")
                                .attr({
                                    "data-action"   : "favorite",
                                    "data-reva"     : "unfavorite",
                                    "title"         : "Mettre en favori",
                                    "data-revt"     : "Retirer des favoris"
                                });
                        }
                    }
                    
                } 
               /*
                * ETAPE :
                *      Mise à jour des données d'ENTÊTE.
                *      La mise à jour se fait selon les cas.
                */
                else if ( $.inArray($ab.data("atype"),["itr","fksa"]) !== -1 ) {
                    $ab
                        .data("hasfv",hasfv)
                        .attr("data-hasfv",hasfv);
                }
                        
                
                /*
                 * ETAPE :
                 *      On affiche la zone le cas échéant
                 */
                if ( $.inArray(scp,["on_unq"]) !== -1 && $ab.find(".jb-tqr-am-ax-box").length ) {
                    $ab.find(".jb-tqr-am-ax-box").stop(true,true).hide().removeClass("disable").removeClass("this_hide").fadeIn();
                }
                
                /*
                 * ETAPE :
                 *      On signale que l'ACTION a éré exécutée avec succès
                 */
                switch (a) {
                    case "unfavorite" :
                            var Nty = new Notifyzing();
                            Nty.FromUserAction("ua_afav_unfav");
                        break;
                    case "fav_public" :
                    case "fav_private" :
                            var Nty = new Notifyzing();
                            Nty.FromUserAction("ua_afav_newfav");
                        break;
                    default :
                        return;
                }

                $(x).data("lk",0);
                _xhr_art_fav = null;
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Dwld = function (x){
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Rprt = function (x){
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_RbdAXA = function (am) {
        try {
            if ( KgbLib_CheckNullity(am) | !$(am).length ) {
                return;
            }
            
            $(am)
                .find(".jb-tqr-art-abr-tgr, .jb-tqr-art-abr-fav-ch, .jb-tqr-art-actbar-fav-ccl")
                .click(function(e){
                    Kxlib_PreventDefault(e);
                    Kxlib_StopPropagation(e);
                    Kxlib_DebugVars(["AXA (INNER) => ",$(am).find(".jb-tqr-art-abr-tgr").length,$(am).find(".jb-tqr-art-abr-fav-ch").length,$(am).find(".jb-tqr-art-actbar-fav-ccl").length]);
                    _f_Action(this);
                }
            );
    
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** SERVERS SCOPE *************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _Ax_AFav = Kxlib_GetAjaxRules("TQR_ART_FAV");
    var _f_Srv_AFav  = function(i,a,x,xt,s) {
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(a) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(xt) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (datas) {
            //            alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                if (! KgbLib_CheckNullity(datas) ) {
                    datas = JSON.parse(datas);
                } else {
                    _xhr_art_fav = null;
                    $(x).data("lk",0);
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    _xhr_art_fav = null;
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
                            case "__ERR_VOL_ART_GONE" :
                                    //TODO : Supprimer de la liste OU demander à recharger
                                break;
                            case "__ERR_VOL_DNY_AKX" :
                                    //TODO : ... ?
                                break;
                            case "__ERR_VOL_AMBIGUOUS" :
                                    //TODO : ... ?
                                break;
                            default :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(datas.return) ) {
                    var ds = [datas.return];
                    $(s).trigger("datasready",ds);
                } else {
                    $(s).trigger("operended");
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
            "urqid": _Ax_AFav.urqid,
            "datas": {
                "i"     : i,
                "a"     : a,
                "xt"    : xt,
                "cu"    : curl
            }
        };
        
        _xhr_art_fav = Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_AFav.url, wcrdtl : _Ax_AFav.wcrdtl });
    };
    
    
    /*******************************************************************************************************************************************************************/
    /***************************************************************************** VIEW SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** LISTENERS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    $(".jb-tqr-art-abr-tgr, .jb-tqr-art-abr-fav-ch, .jb-tqr-art-actbar-fav-ccl").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        _f_Action(this);
    });
    
    $(".jb-tqr-art-abr-fav-bmx, .jb-tqr-art-abr-fav-bmx > *").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
    });
    
    $(".jb-tqr-lstnr-onev").on("RBD_FR_FV",function(e,d){
        Kxlib_PreventDefault(e);
        
        _f_RbdAXA(d);
    });
    
})();


/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/***********************************************************************************        ASIDE-RICH-BANNER : ARTCILES COM        ***********************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/


(function(){
    var _xhr_art_fav;
    
    /*******************************************************************************************************************************************************************/
    /**************************************************************************** PROCESS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_Gdf = function () {
        var dt = {
            "" : "",
        }; 
        
        return dt;
    };
    
    var _f_Action = function (x,a){
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity(a) && KgbLib_CheckNullity($(x).data("action")) ) ) {
                return;
            }
            
            var _a = ( KgbLib_CheckNullity(a) ) ? $(x).data("action") : a;
            switch (_a) {
                case "favorite" :
                        _f_Fav(x,_a);
                    break;
                case "akx-nwfd" :
                case "akx-psmn" :
                case "akx-frdrqt" :
                        _f_Sntch_Open(x,_a);
                    break;
                default:
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Nwfd = function (x,a){
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_Psmn = function (x,a){
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_FrdRqst = function (x,a){
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** SERVERS SCOPE *************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    /*******************************************************************************************************************************************************************/
    /***************************************************************************** VIEW SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** LISTENERS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    $(".jb-tqr-hl-n-n-tgr").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        _f_Action(this);
    });
    
})();


/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/***********************************************************************************          ASIDE-RICH-BANNER : RLC SCOPE         ***********************************************************************************/
/***********************************************************************************                                                ***********************************************************************************/
/**********************************************************************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************************************************************/


(function(){
    
    var _RLC;
    
    /*******************************************************************************************************************************************************************/
    /**************************************************************************** PROCESS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_Gdf = function () {
        var dt = {
            "" : "",
        }; 
        
        return dt;
    };
    
    var _f_Action = function (x,a){
        try {
            if ( KgbLib_CheckNullity(x) | ( KgbLib_CheckNullity(a) && KgbLib_CheckNullity($(x).data("action")) ) ) {
                return;
            }
            
            var _a = ( KgbLib_CheckNullity(a) ) ? $(x).data("action") : a;
            switch (_a) {
                case "rlc-sprt-opn" :
                        _f_RlcIo(x,_a);
                    break;
                default:
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_RlcIo = function (x,a){
        try {
            if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity(a) ) {
                return;
            }
            
//            Kxlib_DebugVars([$(x).closest(".jb-tbv-bind-art-mdl").data("item")],true);
//            return;
            if ( require.specified("r/csam/rlc.csam") && !KgbLib_CheckNullity(_RLC) ) {
//                Kxlib_DebugVars(["ASDRBNR (RLC) : Déjà chargé !",_RLC]);
                _RLC.open({
                    trigger : x,
                    action  : a,
                });
            } else {
//                Kxlib_DebugVars(["ASDRBNR (RLC) : Nouveau Chargement !",_RLC]);
                require(["r/csam/rlc.csam"],function(RLC){
                    _RLC = new RLC();
                    _RLC.open({
                        trigger : x,
                        action  : a
                    });
                });
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    /*******************************************************************************************************************************************************************/
    /*************************************************************************** SERVERS SCOPE *************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    /*******************************************************************************************************************************************************************/
    /***************************************************************************** VIEW SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** LISTENERS SCOPE ************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    $(".jb-tqr-rlc-act.plfbio").off("click").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        _f_Action(this);
    });
    
})();