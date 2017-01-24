
function BUGZY () {
    var gt = this;
    
    /*******************************************************************************************************************************************************************/
    /************************************************************************** PROCESS SCOPE **************************************************************************/
    /*******************************************************************************************************************************************************************/
    
    var _f_Gdf = function () {
        var dt = {
            "type"      : ["BGTYP_CNX","BGTYP_SSN","BGTYP_VW","BGTYP_DT","BGTYP_SEC","BGTYP_PRF","BGTYP_PFL","BGTYP_NAV","BGTYP_SRH","BGTYP_ART","BGTYP_TRD","BGTYP_BGZY","BGTYP_OTHER"],
            "rgx_whr"   : /^(?=.*[a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]).{8,70}$/i,
            "rgx_whn"   : /^(?=.*[a-z\dÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]).{4,70}$/i,
            "rgx_msg"   : /^(?=.*[a-z])[\s\S]{100,1000}$/i, 
            "msg_min"   : 100,
            "msg_max"   : 1000,
            "rgx_lng"   : /^[a-z]{2}$/i,
            "rgx_lng_av": /^(?:fr|en)$/i
        };
        
        return dt;
    };
    
    var _f_HCloz = function() {
//    this.Close = function() {
        _f_Close();
        
        _f_Reset();
    };
    
    var _f_HOpen = function() {
//    this.Open = function() {
        _f_Open();
    };
    
    var _f_Submit = function(x) {
//    this.Submit = function(x) {
        if ( KgbLib_CheckNullity(x) | !$(x).length ) {
            return;
        }
        
        //On vérifie si le bouton est lock
        if ( $(x).data("lk") === 1 ) {
            return;
        }
        
        //On lock le bouton
        $(x).data("lk",1);
        
        //Afficher spinner
        _f_Spinner(true);
        
        //Vérifier les champs
        if (! _f_CheckFields() ) {
            //On unlock le bouton
            $(x).data("lk",0);
            //Masquer spinner
            _f_Spinner();
            
            return;
        }
        
//        Kxlib_DebugVars([JSON.stringify(dims)],true);
        
        
        //On regroupe les données dans un tableau (GatheredDatas) où les index coeincident avec ceux du BUFFER
        var gd = _f_GatherFD();
        
//        Kxlib_DebugVars([JSON.stringify(gd)],true);
//        return;
        
        var s = $("<span/>");
        
        _f_Srv_Submit(gd,x,s);
        
        $(s).on("datasready",function(e,d) {
            if ( KgbLib_CheckNullity(d) ) {
                //On unlock le bouton
                $(x).data("lk",0);
                //On fait disparaittre le spinner
                _f_Spinner();
            }
            
            
            if ( d.r.toString().toUpperCase() === "DONE" ) {
                _f_HCloz();
                
                var Nty = new Notifyzing ();
                Nty.FromUserAction("bgzy_msg_done");
            } else {
                _f_HCloz();
                
                Kxlib_AJAX_HandleFailed("BUGZY_AX_FAILED");
            }
            
            //On unlock le bouton
            $(x).data("lk",0);
            //Masquer spinner
            _f_Spinner();
        });
        
    };
    
    var _f_GatherFD = function () {
        
        dt = {
            "type"      : $(".jb-bgzy-fld[data-ft='type'] option:selected").val(),
            "where"     : $(".jb-bgzy-fld[data-ft='where']").val(),
            "when"      : $(".jb-bgzy-fld[data-ft='when']").val(),
            "message"   : $(".jb-bgzy-fld[data-ft='message']").val(),
            "lang"      : $(".jb-bgzy-fld[data-ft='lang'] option:selected").val(),
            "url"       : document.URL,
            "scrn_w"    : screen.width,
            "scrn_h"    : screen.height
        };
        
        return dt;
    };
    
    var _f_CheckFields = function () {
        
        var ec = 0;
        $.each($(".jb-bgzy-fld"),function(x,v) {
            if ( !$(v).length | !$(v).data("ft") ) {
                return;
            }
            
            if (! _f_CheckField(v) ) {
                ++ec;
            }
        });
        
        return ( ec ) ? false : true;
    };
    
    var _f_CheckField = function (x) {
        if ( KgbLib_CheckNullity(x) | !$(x).length | !$(x).data("ft") ) {
            return;
        }
        
        //FieldTarget
        var v, ft = $(x).data("ft").toString().toLowerCase(), em, ie = false, emb;
        Kxlib_DebugVars([$(x).data("ft")]);
        switch (ft) {
            case "type" :
                    v = $(".jb-bgzy-fld[data-ft='type'] option:selected").val(); 
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else if ( $.inArray(v,_f_Gdf().type) === -1  ) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else {
                        $(x).removeClass("error_field");
                    }
                break;
            case "where" :
                    v = $(x).val();
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_whr.test(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else {
                        $(x).removeClass("error_field");
                    }
                break;
            case "when" :
                    v = $(x).val();
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_whn.test(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else {
                        $(x).removeClass("error_field");
                    }
                break;
            case "message" :
                    v = $(x).val();
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_msg.test(v) && v.length > _f_Gdf().msg_max ) {
                        ie = true;
                        $(x).addClass("error_field");
                        
                        em = Kxlib_getDolphinsValue("bgzy_msg_max");
                        $(".jb-bgzy-ipt-err").html(em);
                    } else if ( !_f_Gdf().rgx_msg.test(v) && v.length < _f_Gdf().msg_min ) {
                        ie = true;
                        $(x).addClass("error_field");
                        
                        em = Kxlib_getDolphinsValue("bgzy_msg_min");
                        $(".jb-bgzy-ipt-err").html(em);
                    } else if ( !_f_Gdf().rgx_msg.test(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                        
                        em = Kxlib_getDolphinsValue("bgzy_msg_rgx");
                        $(".jb-bgzy-ipt-err").html(em);
                        $(x).addClass("error_field");
                    } else {
                        $(".jb-bgzy-ipt-err").html("");
                        $(x).removeClass("error_field");
                    }
                break;
            case "lang" :
                    v = $(".jb-bgzy-fld[data-ft='lang'] option:selected").val(); 
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else if (! _f_Gdf().rgx_lng.test(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else if (! _f_Gdf().rgx_lng_av.test(v)  ) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else {
                        $(x).removeClass("error_field");
                    }
                break;
        }
        
        return ( ie ) ? false : true;
    };
    
    var _f_Reset = function () {
        $(".jb-bgzy-fld").removeClass("error_field");
        $(".jb-bgzy-ipt-err").html("");
        Kxlib_ResetForm("bgzy-form");
        $(".jb-bgzy-ipt-ln").text(_f_Gdf().msg_max);
    };
    
    /*************************************************************************************************************************************************************/
    /********************************************************************* SERVER SCOPE **************************************************************************/
    /*************************************************************************************************************************************************************/
    
    var _Ax_Submit_Rules = Kxlib_GetAjaxRules("BGZY_SUB",Kxlib_GetCurUserPropIfExist().upsd);
    var _f_Srv_Submit = function (gd,x,s) {
        if ( KgbLib_CheckNullity(gd) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
            return;
        }
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    return;
                }
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    //On unlock le bouton
                    $(x).data("lk",0);
                    //On fait disparaittre le spinner
                    _f_Spinner();
                    
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
                        switch (d.err) {
                            case "__ERR_VOL_U_G" :
                            case "__ERR_VOL_ACC_GONE" :
                            case "__ERR_VOL_USER_GONE" :
                            case "__ERR_VOL_CU_GONE" :
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_DENY" :
                            case "__ERR_VOL_DENY_AKX" :
                                        Kxlib_AJAX_HandleDeny();
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DATAS_MSG" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                break;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if ( !KgbLib_CheckNullity(d.return) )  {
                    rds = [d.return];
                    $(s).trigger("datasready",rds);
                } else {
                    //On unlock le bouton
                    $(x).data("lk",0);
                    //On fait disparaittre le spinner
                    _f_Spinner();
                    
                    return;
                }
                
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([e],true);
  
                //On unlock le bouton
                $(x).data("lk",0);
                //On fait disparaittre le spinner
                _f_Spinner();
                
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                
                return;
            }
        };

        var onerror = function(a,b,c) {
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            
            //On unlock le bouton
            $(x).data("lk",0);
            //On fait disparaittre le spinner
            _f_Spinner();
            
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            return;
        };
        
//        Kxlib_DebugVars([JSON.stringify(_Ax_Rules),JSON.stringify(_Ax_Datas)],true);
        
        var toSend = {
            "urqid": _Ax_Submit_Rules.urqid,
            "datas": gd
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_Submit_Rules.url, wcrdtl : _Ax_Submit_Rules.wcrdtl });
    };
    
    /*************************************************************************************************************************************************************/
    /*********************************************************************** VIEW SCOPE **************************************************************************/
    /*************************************************************************************************************************************************************/
    
    var _f_Close = function () {
        $(".jb-bgzy-sprt").addClass("this_hide");
    };
    
    var _f_Open = function () {
        $(".jb-bgzy-sprt").removeClass("this_hide");
    };
    
    var _f_Spinner = function (sw) {
        if ( KgbLib_CheckNullity(sw) ) {
            $(".jb-bgzy-spnr").addClass("this_hide");
        } else {
            $(".jb-bgzy-spnr").removeClass("this_hide");
        }
    };
     
    /****************************************************************************************************************************************************************/
    /*********************************************************************** LISTERNERS SCOPE ***********************************************************************/
    /****************************************************************************************************************************************************************/
    
    $(".jb-bgzy-sprt").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_HCloz();
    });
    
    $(".jb-bgzy-sprt *").click(function(e) {
        Kxlib_StopPropagation(e);
    });
    
    /*
     * [DEPUIS 12-06-16]
     */
//    $(".jb-ubx-menu-choices[data-action='bugzy'], .jb-tqr-cnfrm-fnl-dec[data-action='bugzy']").click(function(e){
    $(".jb-ubx-menu-choices[data-action='bugzy'], .jb-tqr-cnfrm-fnl-dec[data-action='bugzy']").on("tqr_cuev_click",function(e){
        Kxlib_PreventDefault(e);
        
        
        /*
         * [DEPUIS 12-06-16]
         *      On masque (correctement) le menu pour des raisons esthétiques
         */
        $(".jb-hdr-btn-hdle").blur();
        
        /*
         * [DEPUIS 26-10-15] @author BOR
         */
        if ( $(this).is(".jb-tqr-cnfrm-fnl-dec") && $(".jb-pg-sts").length && !$(".jb-pg-sts").hasClass("this_hide") ) {
            return;
        }
        
        _f_HOpen();
    });
    
    $(".jb-bgzy-form").submit(function(e) {
        Kxlib_PreventDefault(e);
        
        _f_Submit(this);
    });
    
    $(".jb-bgzy-fld[data-ft='when']").datepicker($.datepicker.regional["fr"]);
    
    $(".jb-bgzy-fld[data-ft='lang']").change(function(){
        var rgc = ( $( this ).val() === "en" ) ? "" : $( this ).val();
        $(".jb-bgzy-fld[data-ft='when']").datepicker("option",$.datepicker.regional[rgc]);
    });
}


_Obj = new BUGZY(); 
