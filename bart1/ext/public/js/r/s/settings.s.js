/* 
    Created on : 18 nov. 2014, 22:25:25
*/

function SETTINGS () {
    var gt = this;
    
    /********************************************************************************************************************************************************/
    /****************************************************************** PROCESS SCOPE ***********************************************************************/
    /********************************************************************************************************************************************************/
    
    var _f_Gdf = function() {
        var df = {
            //Le temps d'attente après chaque fin de frappe
            "wtt": 200,
            //Le nombre de caractères minimum pour lancer la recherche
            "cysrh_min": 3,
            "rgx_cty_frbd": /[!\*²&<>!?\+*~µ£\^¨°\(\)\[\]@#$%:;=/\\¤\|\{\}§]/i,
            "rgx_cty": /^((?![!\*²&<>!?\+*~µ£\^¨°\(\)\[\]@#$%:;=/\\¤\|\{\}§]).){3,}$/i,
            "cty_srh_scp": ["smpl","cstm"],
            "rgx_fn": /^[a-z-\. ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,25}$/i,
            "fn_min": 2,
            "fn_max": 25,
            "rgx_bdate": /^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/,
            "bdate_min": 12,
            "rgx_gdr": /^[m|f]{1}$/i,
            "rgx_psd": /^[\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20}$/i,
            "psd_min": 2,
            "psd_max": 20,
            "rgx_eml": /^[\w.+-]+@(?:[\w\d-]+\.)+[a-z]{2,6}$/i,
            "rgx_lng": /^[a-z]{2}$/i,
            "rgx_lng_av": /^(?:fr|en)$/i,
//            "rgx_pwd": /^(?=(.*\d))(?=.*[A-Z])(?=.*[²&<>!.?+*_~µ£^¨°\(\)\[\]\-@#$%¤])[^:;=''/\\]{6,32}$/,
            "rgx_pwd": /^(?=(.*\d))(?=.*[a-z])(?=.*[²&<>!.?+*_~µ£^¨°\(\)\[\]\-@#$%:;=''/\\¤]).{6,32}$/i,
            "rgx_pwd_ltr": /[a-z]+/i,
            "rgx_pwd_spch": /([²&<>!.?+*_~µ£^¨°\(\)\[\]\-@#$%:;=''/\\¤])/g,
//            "rgx_pwd_bspch": /[:;=''/\\¤]+/,
            "rgx_pwd_dig": /([0-9])/g,
            "rgx_pwd_case": /([A-Z])/g,
            //np => NotPerfect : Si le password a tout pour être excellent mais que les mots ci_desssous si trouve, il ne le sera pas
            "rgx_pwd_np": /(toto|azerty|0000|1234|qwerty|letmein|password)/ig,
            "pwd_min": 6,
            "pwd_max": 32,
            //PassWaitingTime
            "pwt": 200,
            "hikw" : ["SCHOOL","WORKPL","RELATIVE","SOCNET","WEBSIT","MEDIA"],
            "yilv" : ["MSFUNC","MSPHONE","MSENTOURAGE","ERRNBG","MSFAV","DESIGN","CONCEPT","HTRUN","OTHER"],
            "yilv_ot" : /^(?=.*[a-z]).{4,242}$/i,
            "ilbbif" : /^(?=.*[a-z]).{4,242}$/i
        };
        
        return df;
    };
    
    var _f_LMnAct = function (x) {
//    this.LMenuAction = function (x) {
        if ( KgbLib_CheckNullity(x) | !$(x).data("mn") ){
            return;
        }
        
        var mn = $(x).data("mn"), bs;
        switch (mn) {
            case "profile":
            case "account":
            case "security":
            case "delete":   
            case "about":  
                bs = ".jb-stgs-wdw[data-wdw='"+mn+"']";
                break;
            default:    
                break;
        }
        
        var om = $(".jb-stgs-wdw.active").data("wdw");
        
        //Changement du menu actif
        _f_MnSwitch(x);
        
        //Changement de la fenetre active
         _f_WdwSwitch(x);
        
        //On set les valeurs depuis le BUFFER (??)
        
        //On reset le menu précédent
        _f_RstWdw(om);
        
        //On masque ERRBOX
        _f_HidErrBx();
    };
    
    var _f_RstWdw = function (scp) {
        /*
         * Permet de réinitialiser la fenetre correspond au scope passé en paramètre.
         * Cela permet de réinitialiser les erreurs et les valeurs des champs. 
         */
        if ( KgbLib_CheckNullity(scp) ) {
            return;
        }
        
        switch (scp) {
            case "profile" :
                    $(".jb-stgs-back-orign[data-target='jb-stgs-form-pfl']").click();
                    $(".jb-stgs-form-pfl .jb-stgs-err-sbh").text(""); 
                    $(".jb-stgs-pfl-fld, .jb-stgs-bdy").removeClass("error_field");
                    $(".jb-stgs-form-pfl .jb-stgs-pwd-spnr").addClass("this_hide"); 
                    $(".jb-stgs-submit[data-target='profile']").data("lk",0);
                break;
            case "account" :
                    //SECTION : ACCOUNT
                    $(".jb-stgs-back-orign[data-target='jb-stgs-form-acc']").click();
                    $(".jb-stgs-form-acc .jb-stgs-err-sbh").text(""); 
                    $(".jb-stgs-acc-fld").removeClass("error_field");
                    $(".jb-stgs-form-acc .jb-stgs-pwd-spnr").addClass("this_hide"); 
                    $(".jb-stgs-submit[data-target='account']").data("lk",0);
                
                    //SECTION : PASSWORD
                    $(".jb-stgs-pwd-fld").val("");
                    $(".jb-pwd-strength").width(0);
                    $(".jb-stgs-form-pwd .jb-stgs-err-sbh").text(""); 
                    $(".jb-stgs-pwd-fld").removeClass("error_field");
                    $(".jb-stgs-form-pwd .jb-stgs-pwd-spnr").addClass("this_hide"); 
                    $(".jb-stgs-submit[data-target='password']").data("lk",0);
                break;
            case "security" :
                    Kxlib_ResetForm("pfl_form_security");
                break;
            case "delete" :
                    $("input[name=sgtgs-del-hikw]").removeProp("checked");
                    $("input[name=sgtgs-del-yilv]").removeProp("checked");
                    $(".jb-stgs-del-y-other-xpln").assClass("this_hide");
                    $(".jb-stgs-del-y-other-xpln").val("");
                    $(".jb-stgs-del-y-other-xpln").removeClass("error_field");
                    $(".jb-stgs-del-free-xpln").val("");
                    $(".jb-stgs-del-free-xpln").removeClass("error_field");
                    $(".jb-stgs-del-fld[data-ft='delcf']").removeProp("checked");
                    $(".jb-stgs-del-lgls-ipt").removeClass("red");
                    
                    $(".jb-stgs-del-f-mx").addClass("this_hide");
                    $(".jb-stgs-del-f-emsg").val("");
                    
                    $(".jb-stgs-del-form .jb-stgs-del-spnr").addClass("this_hide"); 
                    $(".jb-stgs-submit[data-target='delete']").data("lk",0);
                break;
            default :
                    return;
        }
        
        return true;
    };
    
    var _f_OnBlurAct = function (x) {
//    this.OnBlurAction = function (x) {
        /*
         * Gère les cas de Blur() sur certains champs.
         */
        if ( KgbLib_CheckNullity(x) | !$(x).length | !$(x).data("ft") )
            return;
        
        var ft = $(x).data("ft"), spr, qt;
        switch (ft) {
            case "pseudo" :
                    qt = $(x).val();
                break;
            case "email" :
                    qt = $(x).val();
                break;
            default :
                    return;
                break;
        }
        //On vérifie que le champ n'est pas en mode lk
        if ( $(x).data("lk") === 1 ) {
            return;
        }
        
        //On fait apparraitre le spinner
        _f_TogFormSpnr(ft,true);
        
        //On vérifie que le champ est correctement rempli
        if ( !_f_CheckField(x) ) {
            
            //On fait disparaitre le spinner
            _f_TogFormSpnr(ft);
            
            return;
        }
        
        //On lance la requete au niveau du serveur
        var s = $("<span/>"), t = (new Date()).getTime();
        _f_Srv_StgsPullDatas(qt,ft,t,s);
        
        //On bloque le champ
        $(x).data("lk",1);
        
        /*
         * [NOTE 22-11-14] @author L.C.
         * J'ai omis de manière intentionnelle de retirer le spinner ou de débloquer la zone ...
         * ... pour obliger psychologiquement (lui mettre une pression psychologique) l'utilisateur à recharger la page.
         * De cette manière on réduit le risque d'erreur sur les prochaines opérations.
         * De plus, ça nous fait moins travaillé car de toutes les façons, s'il tente de sauvegarder le formulaire cela sera refusé.
         * A la validationn, il lui sera demandé de recharger la page.
         * "Cette page contient des erreurs. Veuillez recharger la page pour les corriger"
         */
        $(s).on("datasready", function(e,d){
            if ( KgbLib_CheckNullity(d) ) {
                //On unlock le champ
                $(x).data("lk",0);
                //On masque le spinner
                _f_TogFormSpnr(ft);
            
                return;
            }
            
            //On affiche l'erreur selon les cas 
            switch (ft) {
                case "pseudo" :
                        var em;
//                        Kxlib_DebugVars([qt,_f_GetBufferDatas().account.pseudo.toString().toLowerCase()],true);
                        if ( qt.toString().toLowerCase() === _f_GetBufferDatas().account.pseudo.toString().toLowerCase() ) {
                            em = Kxlib_getDolphinsValue("stgs_acc_acc_psd_me");
                        } else {
                            em = Kxlib_getDolphinsValue("stgs_acc_acc_psd_tkn");
                        }
                            
                        if ( em ) {
                            $(".jb-stgs-err-sbh[data-target='"+ft+"']").text(em);
                        }
                    break;
                case "email" :
                        var em;
                        if ( typeof d === "string" && d.toString().toUpperCase() === "__ERR_VOL_DOM" ) {
                            em = Kxlib_getDolphinsValue("stgs_acc_acc_eml_dom");
                        } else if ( typeof d === "string" && d.toString().toUpperCase() === "__ERR_VOL_DNS" ) {
                            em = Kxlib_getDolphinsValue("stgs_acc_acc_eml_dns");
                        } else if ( typeof d === "object" && d.hasOwnProperty("email") && d.email.toString().toLowerCase() !== _f_GetBufferDatas().account.email.toString().toLowerCase() ) {
                            em = Kxlib_getDolphinsValue("stgs_acc_acc_eml_tkn");
                        } else if ( typeof d === "object" && d.hasOwnProperty("email") && d.email.toString().toLowerCase() === _f_GetBufferDatas().account.email.toString().toLowerCase() ) {
                            em = Kxlib_getDolphinsValue("stgs_acc_acc_eml_me");
                        } else if ( typeof d === "boolean" && !d )  {
                            em = Kxlib_getDolphinsValue("stgs_acc_acc_eml_avlb");
                        }
                                
//                        } else {
//                            //On unlock le champ
//                            $(x).data("lk",0);
//                            //On masque le spinner
//                            _f_TogFormSpnr(ft);
//                            return;
//                        }
                        
                        if ( em ) {
                            $(".jb-stgs-err-sbh[data-target='"+ft+"']").text(em);
                        }
                    break;
                default :
                    break;
            }
            
            //On unlock le champ
            $(x).data("lk",0);
            //On masque le spinner
            _f_TogFormSpnr(ft);
            
        });
           
        $(s).on("operended",function(e) {
            /*
             * Gère la cas où aucun résultat nous revient
             */
            
            //Si la valeur envoyée et celle actuelle ne correspondent pas, on relance un blur
            if ( qt !==  $(x).val() ) {
                $(x).trigger("blur");
                return;
            }
            
            var em;
            switch (ft) {
                case "pseudo" :
//                        Kxlib_DebugVars([qt,_f_GetBufferDatas().account.pseudo.toString().toLowerCase()],true);
                       em = Kxlib_getDolphinsValue("stgs_is_avlb");
                    break;
                case "email" :
                        em = Kxlib_getDolphinsValue("stgs_acc_acc_eml_avlb");
                    break;
                default :
                    break;
            }
            
            if ( em ) {
                $(".jb-stgs-err-sbh[data-target='"+ft+"']").text(em);
            }
            
            //On unlock le champ
            $(x).data("lk",0);
            //On masque le spinner
            _f_TogFormSpnr(ft);
            
        });
    };
    
    var _f_RadioAct = function (x) {
//    this.RadioAction = function (x) {
        if ( KgbLib_CheckNullity(x) | KgbLib_CheckNullity($(x).val()) | KgbLib_CheckNullity($(x).prop("name")) ) {
            return;
        }
        
        var v = $(x).val(), n = $(x).prop("name");
        switch (n) {
            case "sgtgs-del-hikw" :
                    
                break;
            case "sgtgs-del-yilv" :
                    if ( v.toString().toUpperCase() === "OTHER" && $(".jb-sgtgs-del-yilv-ot-mx").length && $(".jb-stgs-del-y-other-xpln").length ) {
//                        $(".jb-stgs-del-y-other-xpln").val("");
                        $(".jb-sgtgs-del-yilv-ot-mx").removeClass("this_hide");
                        $(".jb-stgs-del-y-other-xpln").focus();
                    } else if ( v.toString().toUpperCase() === "OTHER" && ( !$(".jb-sgtgs-del-yilv-ot-mx").length | !$(".jb-stgs-del-y-other-xpln").length ) ) {
                        $(".jbsgtgs-del-yilv-ot").addClass("error_field");
                        return;
                    } else {
                        $(".jb-stgs-del-y-other-xpln").blur();
                        $(".jb-sgtgs-del-yilv-ot-mx").addClass("this_hide");
                    }
                break;
            default :
                    return;
                break;
        }
        
        return true;
        
    };
    
    var _f_GetBufferDatas = function () {
        /*
         * Permet de récupérer toutes les données présentes dans la zone BUFFER.
         * Les données récupérées sont Parser en un objet JSON
         */
        var bdb = $(".jb-nwfd-buffer");
        if ( !$(bdb).length | KgbLib_CheckNullity($(bdb).text()) ) {
            return;
        }
        
        var dt = $(bdb).text();
        try {
//            Kxlib_DebugVars([$(bdb).length,$(bdb).text()],true);
            dt = JSON.parse(dt);
            return dt;
        } catch (e) {
            return;
        }
        
    };
    
    var _f_SetBufferDatas = function () {
        
    };
    
    
    var _f_RstForm = function(x) {
//    this.ResetForm = function(x) {
        if ( KgbLib_CheckNullity(x) | !$(x).length | !$(x).data("target") ) {
            return;
        }
        
        var trg = $(x).data("target"), f;
        switch (trg) {
            case "jb-stgs-form-pfl" :
            case "jb-stgs-form-acc" :
            case "jb-stgs-form-pwd" :
                    f = $("."+trg);
                break;
            default:
                    return;
                break;
        }
        
        if ( !$(f).length | KgbLib_CheckNullity($(f).attr("id")) ) {
            return;
        } else {
            var i = $(f).attr("id");
//            Kxlib_DebugVars([$(Kxlib_ValidIdSel($(f).attr("id"))).find("input").length],true);
            Kxlib_ResetForm(i);
        }
        
    };
    
    var to;
    var _f_CatchQry = function (e,x) {
//    this.CatchQry = function (e,x) {
        if ( KgbLib_CheckNullity(e) | KgbLib_CheckNullity(x) ) {
            return;
        }
        
        var wi = _f_Gdf().wtt;
        if ( to ) {
            clearTimeout(to);
        }
        
        //QueryText
        var qt = $(x).val();
        
        //On vérifie si le texte contient des caractères non autorisés
        var ll = qt[--qt.length];
        if ( _f_Gdf().rgx_cty_frbd.test(ll) ) {
            $(x).val(qt.substring(0,--qt.length));
        }
        
        //On vérifie que le texte après l'action est DIFFERENT du texte 
        
//        Kxlib_DebugVars([qt.length >= _f_Gdf().cysrh_min],true);
//        Kxlib_DebugVars([wi,qt,_f_Gdf().cysrh_min],true);
//        return;
        if ( ( qt.length >= _f_Gdf().cysrh_min ) && _f_Gdf().rgx_cty.test(qt) ) {
         
            to = setTimeout(function() {
                
                //On vérifie une seconde fois
                if ( ( $(x).val().length  < _f_Gdf().cysrh_min ) | !_f_Gdf().rgx_cty.test(qt) ){
                    return;
                }
                
                //On affiche le spinner
                _f_TogFormSpnr("profile_city_smpl",true);
                
                //-- On efface la bonne liste
                //On sélectionne le bon scope
                
//                var list_scp = ( $(".jb-cty-list-mx.this_hide").length > 1 || $(".jb-cty-list-mx.this_hide").data("obj") === "cstm" ) ? "smpl" : "cstm";
                
//                Kxlib_DebugVars([to,$(".jb-cty-list-mx.this_hide").length,$(".jb-cty-list-mx.this_hide").data("obj"),list_scp],true);
//                return;
                //On retire le code de la ville précédente ce qui a pour effet de "unvalid" le champ
                $(".jb-stgs-cty-ipt").data("ci","");
                
                _f_CtySrhTrgr(qt);
            },wi);
        } else {
            //On retire le code de la ville précédente ce qui a pour effet de "unvalid" le champ
            $(".jb-stgs-cty-ipt").data("ci","");
            
            //On retire le spinner
             _f_TogFormSpnr("profile_city_smpl");
            
            //On enlève les résultats de l'ancienne requête
            _f_RstCitySrhList("smpl");
            _f_RstCitySrhList("cstm");
            /*
            //On fait analyser par NoOne
            _f_NoOne(qsp);
            //*/
        }
        
    };
    
    
    var _f_CtySrhTrgr = function (qt) {
        /*
         * Permet de lancer puis de gérer une recherche de ville pour l'inscription
         */
        if ( KgbLib_CheckNullity(qt) ){
            return;
        }
        
        var s = $("<span/>"), t = (new Date()).getTime();
        
//        Kxlib_DebugVars([qt,sp],true);
//        return;
        //On contacte le serveur
        _f_Srv_StgsPullDatas(qt,"cty_srh",t,s);
        
        $(s).on("datasready",function(e,d) {
            if ( KgbLib_CheckNullity(d) ){
                return;
            }
            
            //On affiche la liste
            _f_ShwCtySrhReslt(qt,d,"smpl");
            
        });
        
        $(s).on("operended",function(e) {
            /*
             * Gère la cas où aucun résultat nous revient
             */
            
            //On masque le spinner
            _f_TogFormSpnr("profile_city_smpl");
       
            //On efface les anciens résultats
//            _f_RstCitySrhList("smpl");
//            _f_RstCitySrhList("cstm");
        });
        
    };
    
    var _f_CySrhSlct = function (x) {
//    this.CySrhSelect = function (x) {
        /*
         * Gère tous les cas de la sélection de ville.
         */
        if ( KgbLib_CheckNullity(x) )
            return;
        
        var t = $(x).data("obj");
//        Kxlib_DebugVars([t, _f_Gdf().cty_srh_scp, Array.isArray(_f_Gdf().cty_srh_scp), $.inArray(t,_f_Gdf().cty_srh_scp)],true);
        if ( KgbLib_CheckNullity(t) || $.inArray(t,_f_Gdf().cty_srh_scp) === -1 )
            return;
        /*
        switch (t) {
            case "smpl":
                break;
            case "cstm":
                break;
            default :
                break;
        }
        //*/
        
        //On masque et vide toutes les listes
        _f_RstCitySrhList("smpl");
        _f_RstCitySrhList("cstm");
        
        //On vérifie s'il s'agit du cas suivant lequel, il y a plusieurs villes avec la même dénomination
        var cycop = $(x).data("cycop");
        if ( parseInt(cycop) > 1 ) {
            //On fait apparaitre la zone "Custom"
            _f_ShwCstmCitySrhList();
            
            //On affiche le 'spinner'
            _f_TogFormSpnr("profile_city_cstm",true);
            
            //On lance la recherche
            var qt = $(x).find(".jb-cy-list-cynm").text();
            var qt_cn = $(x).find(".jb-cy-list-cycn").text();
            _f_CtySrhTrgr_This(qt,qt_cn);
            
            return;
        }
        
        //Acquisition des données sur la ville
        var cnm = $(x).find(".jb-cy-list-cynm").text();
        var ccn = $(x).find(".jb-cy-list-cycn").text();
        var se = cnm+", "+ccn;
        
        //Affichage des données
        $(".jb-stgs-cty-ipt").val(se);
        //se : SelectedElement (Permet notamment de valider le champ)
        $(".jb-stgs-cty-ipt").data("ci",$(x).data("ii"));
        
        //On retire visuellement le marqueur d'erreur
        $(".jb-stgs-cty-ipt").removeClass("error_field");
        //... ET la phrase d'erreur eventuelle
        $(".jb-stgs-err-sbh[data-target='city']").text("");
        
        //On ajoute le marqueur "valid"
//        _f_IsRowVald("ctysrh",true);
        
//        alert($(x).data("ii"));
//        alert($(".jb-stgs-cty-ipt").data("ci"));
//        Kxlib_DebugVars([(".jb-stgs-cty-ipt").data("se")]);
    };
    
    var _f_CtySrhTrgr_This = function (qt,cycn) {
        /*
         * Permet de lancer puis de gérer une recherche de ville SPECIFIQUE pour l'inscription
         */
        if ( KgbLib_CheckNullity(qt) | KgbLib_CheckNullity(cycn) )
            return;
        
        var s = $("<span/>"), t = (new Date()).getTime();
        
//        Kxlib_DebugVars([qt,cycn],true);
//        return;
        var xd = {
            "cycn": cycn
        };
        
        //On contacte le serveur
        _f_Srv_StgsPullDatas(qt,"cty_srh_this",t,s,xd);
        
        $(s).on("datasready",function(e,d) {
            if ( KgbLib_CheckNullity(d) )
                return;
            
            //On affihe la liste
            _f_ShwCtySrhReslt(qt,d,"cstm");
            
        });
        
        $(s).on("operended",function(e) {
            /*
             * Gère la cas où aucun résultat nous revient
             */
            
            //On masque le spinner
            _f_TogFormSpnr("ctysrh_smpl");
       
            //On efface les anciens résultats
            _f_RstCitySrhList("smpl");
            _f_RstCitySrhList("cstm");
            
        });
        
    };
    
    var _f_CtySrhChkExists = function(i,scp) {
        /*
         * Pour une recherche de ville, vérifie si l'élément existe déjà dans la liste.
         */
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(scp) )
            return;
        
        var b = ( scp === "smpl" ) ? ".jb-cty-smpl-list-mx" : ".jb-cty-cstm-list-mx";
        var bl = $(b).find(".jb-stgs-city-list");
        var r;
        
        if ( $(bl).length ) {
            var e = $(bl).find(".jb-cty-list-elt[data-ci='"+i+"']");
            r = ( $(e).length ) ? true : false;
        } else 
            return;
        
        return r;
        
    };
    
    var s_;
    var _f_Submit = function (x) {
//    this.Submit = function (x) {
        if ( KgbLib_CheckNullity(x) | !$(x).length | KgbLib_CheckNullity($(x).data("target")) ) {
            return;
        }
        
        //On vérifie que le bouton n'est pas bloqué
        if ( $(x).data("lk") === 1 ) {
            alert("locked");
            
            return;
        }
        
        var trg = $(x).data("target");
       
        //On lock le bouton
        $(x).data("lk",1);
        
        //On fait apparaittre le spinner
        _f_TogFormSpnr(trg,true);
        
        //On s'assure que les données du formulaire cible sont valides
        if (! _f_CheckForm(x) ) {
            //On unlock le bouton
            $(x).data("lk",0);
            //On fait disparaittre le spinner
            _f_TogFormSpnr(trg);
            
            if ( trg === "delete" ) {
                $(".jb-stgs-del-f-mx").removeClass("this_hide");
                var t_em = Kxlib_getDolphinsValue("stgs_del_form");
                $(".jb-stgs-del-f-emsg").text(t_em);
            } 
        
            return;
        } else {
            if ( trg === "delete" ) {
                $(".jb-stgs-del-f-mx").addClass("this_hide");
                $(".jb-stgs-del-f-emsg").val("");
            } 
        }
        
        //On regroupe les données dans un tableau (Gathereddatas) où les index coeincident avec ceux du BUFFER
        var gd = _f_GatherFD(trg);
//        Kxlib_DebugVars([JSON.stringify(gd)], true);
//        return;
        //On vérifie qu'au moins une des données est différente de celle BUFFER sinon ça ne vaut pas la peine d'aller vers le serveur
        var id = _f_SvFormWorthIt(trg,gd);
//        Kxlib_DebugVars([id], true);
//        return;
        
        //On envoie les données au niveau du serveur pour enregistrement si des différences existent
        if ( id ) {
            var s = $("<span/>");
            s_ = $("<span/>");
            
            //Préparer les _Ax_Rules
            _Ax_Rules = _f_AxGetRules(trg,Kxlib_GetCurUserPropIfExist().upsd);
            
            if ( KgbLib_CheckNullity(_Ax_Rules) || _Ax_Rules === -1 ) {
                if ( _Ax_Rules === false || _Ax_Rules === -1 ) {
                    //Il ne s'agit très certainement pas d'une erreur due à une manipulation de la part de l'utilisateur
                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                }
                
                //On unlock le bouton
                $(x).data("lk",0);
                //On fait disparaittre le spinner
                _f_TogFormSpnr(trg);
                return;
            } 
            
            //Préparer les données 
            _Ax_Datas = gd;
            
            //Demander la confirmation du changement de mot de passe (Si pas Password)
            if ( trg !== "password" ) {
                _f_ShwConfimBP(trg);
            } else if ( trg === "password" ) {
                //Envoyer la requete pour traitement au serveur
                _f_Srv_StgsSubmit(_Ax_Rules,_Ax_Datas,trg,x,s);
            }
            
            $(s).on("operended", function(e,d) {
                if ( KgbLib_CheckNullity(d) ) {
                    //On unlock le bouton
                    $(x).data("lk",0);
                    //On fait disparaittre le spinner
                    _f_TogFormSpnr(trg);
                    return;
                }

                //Gestion du retour serveur
//                alert("Gérons tout ca : erreur ? DONE (Buffer,user card, head, RELOAD ?) ? Grosse erreur laterale ?  ");
                _f_SubmitRslt(trg,d);
            });
            
            $(s_).on("operended", function() {
                /*
                 * Si on est ici c'est parce que :
                 *  -> On est sur d'avoir la valeur
                 *  -> Que le message "Patientez..." est apparu
                 *  -> Que le bouton de validation a été bloqué
                 *  -> Que le bouton d'Annulatoion a été bloqué
                 *  
                 *  RAPPEL : Après la réponse du serveur il faudra débloquer les boutons, le cas échéant
                 */
                
                _Ax_Datas.cfpwd = $(".jb-stgs-vbp-ipt").val();
                
                //Envoyer la requete pour traitement au serveur
                _f_Srv_StgsSubmit(_Ax_Rules,_Ax_Datas,trg,x,s);
                
                //Mode standby
                _f_ConfimBPdg();
            });
            
        } else {
            //On unlock le bouton
            $(x).data("lk",0);
            //On fait disparaittre le spinner
            _f_TogFormSpnr(trg);
            return;
        }
        
    };
    
    
    var _f_SubmitRslt = function (scp,d) {
        if ( KgbLib_CheckNullity(scp) | KgbLib_CheckNullity(d) ) {
            return;
        }
        
//        Kxlib_DebugVars([d.hasOwnProperty("r"), d.r.toString().toUpperCase()],true);
        
        if ( d.hasOwnProperty("r") && d.r.toString().toUpperCase() === "DONE") {
            /*
             * Si on est ici c'est qu'on a la mise à jour c'est effectuée sans demande de mo
             */
             switch (scp)    {
                 case "profile" : 
                 case "secu_login" : 
                 case "delete" : 
                        location.reload();
                     break;
                 case "account" : 
                        if ( d.hasOwnProperty("anx") && !KgbLib_CheckNullity(d.anx) ) {
                            window.location.replace(d.anx);
                        } else {
                            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                        }
                     break;
                 case "password" :
                        //On affiche le message
                        var Nty = new Notifyzing ();
                        Nty.FromUserAction("stgs_acc_done");
                        
                        //On fait réinitialiser le formulaire en simulant un click
                        $(".jb-stgs-clear-form[data-target='jb-stgs-form-pwd']").click();
                        
                        //On unlock le bouton correspondant au scope
                        $(".jb-stgs-submit[data-target='"+scp+"']").data("lk",0);
                        //On fait disparaittre le spinner
                        _f_TogFormSpnr(scp);

                        //On fait disparaitre la box d'erreur
                        _f_HidErrBx();
                        
                    break;
                 default:
                     break;
             }
        } else if ( d.hasOwnProperty("r") && d.r.toString().toUpperCase() === "_FALD_CFP" ) {
           
            //On unlock le bouton correspondant au scope
            $(".jb-stgs-submit[data-target='"+scp+"']").data("lk",0);
            //On fait disparaittre le spinner
            _f_TogFormSpnr(scp);

            if ( scp !== "password" ) {
                //On masque la fenetre de Conf
                _f_HidConfimBP();
                //On reset la fenetre de Conf
                _f_RstConfimBP();
            }

            //On fait apparaitre la box d'erreur
            var em = ( scp === "password" ) ? Kxlib_getDolphinsValue("stgs_errbx_curr_pwcnf") : Kxlib_getDolphinsValue("stgs_errbx_pwcnf");
            _f_ShwErrBox(em);
                
        } else if ( d.hasOwnProperty("r") && d.r.toString().toUpperCase() === "FAILED" 
            && d.hasOwnProperty("anx") && !KgbLib_CheckNullity(d.anx) && $.isArray(d.anx) ) 
        {
            $.each(d.anx,function(x,v){
                switch (v.toString().toLowerCase()) {
                    case "ins_fn" :
                            $(".jb-stgs-pfl-fld[data-ft='fullname']").addClass("error_field");
                        break;
                    case "ins_nais_tstamp" : 
                            $(".jb-stgs-bdy").addClass("error_field");
                        break;
                    case  "ins_gdr" : 
                            $(".jb-stgs-pfl-fld[data-ft='gender']").addClass("error_field");
                        break;
                    case  "ins_cty" :
                            $(".jb-stgs-pfl-fld[data-ft='city']").addClass("error_field");
                        break;
                    case  "ins_psd" :
                            $(".jb-stgs-acc-fld[data-ft='pseudo']").addClass("error_field");
                        break;
                    case  "ins_eml" :
                            $(".jb-stgs-acc-fld[data-ft='email']").addClass("error_field");
                        break;
                    case  "ins_lng" :
                            $(".jb-stgs-acc-fld[data-ft='lang']").addClass("error_field");
                        break;
                    case  "ins_pwd" :
                            $(".jb-stgs-pwd-fld[data-ft='npassword']").addClass("error_field");
                        break;
                    default: 
                        break;
                }
            });
            
            //On unlock le bouton correspondant au scope
            $(".jb-stgs-submit[data-target='"+scp+"']").data("lk",0);
            //On fait disparaittre le spinner
            _f_TogFormSpnr(scp);
            
            //On masque la fenetre de Conf
            _f_HidConfimBP();
            //On reset la fenetre de Conf
            _f_RstConfimBP();
            
            //On fait apparaitre la box d'erreur
            var em = Kxlib_getDolphinsValue("stgs_errbx_err_flds");
            _f_ShwErrBox(em);
            
        } else {
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
        }
    };
    
    var _f_CheckForm = function (x) {
        if ( KgbLib_CheckNullity(x) | !$(x).length ) {
            return;
        }
        
        //fs = FormFieldSelector
        var trg = $(x).data("target"), ffs;
        //Avant de bloquer le bouton, on vérifie que la cible est connu
        switch (trg) {
            case "profile" :
                    ffs = ".jb-stgs-pfl-fld";
                break;
            case "account" :
                    ffs = ".jb-stgs-acc-fld";
                break;
            case "password" :
                    ffs = ".jb-stgs-pwd-fld";
                break;
            case "secu_login" : 
                    ffs = ".jb-stgs-seclog-fld";
                break;
            case "delete" :
                    ffs = ".jb-stgs-del-fld";
                break;
            default :
                break;
        }
        
        //efc = ErrorFieldCount
        var efc = 0;
        $.each($(ffs),function(i,e) {
            //ft = FieldTarget;
            //Il y a un problème au niveau du DOM
            if ( !$(e).data("ft") ) {
                return;
            }
            
            if (! _f_CheckField(e) ) {
                ++efc;
            }
        });
        
        return ( efc ) ? false : true;
        
    };
    
    var _f_CheckField = function (x) {
        /*
         * Vérifie la validité du champ et renvoie FALSE en cas de non conformité, TRUE si le champ est conforme.
         */
        if ( KgbLib_CheckNullity(x) | !$(x).length | !$(x).data("ft") ) {
            return;
        }
        
        //FieldTarget
        var ft = $(x).data("ft").toString().toLowerCase(), em, ie = false, emb, v;
//        Kxlib_DebugVars([ft]);
        switch (ft) {
            case "fullname" :
                    v = $(x).val();
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_fn.test(v) && v.length > _f_Gdf().fn_max ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_field_max");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_fn.test(v) && v.length < _f_Gdf().fn_min ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_field_min");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_fn.test(v) ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_pfl_pfl_fn");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else {
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).removeClass("error_field");
                    }
                break;
            case "birthday" :
                    var r = _f_ChkBirth();
//                    alert("BD => "+r);
                    if ( typeof r === "undefined" ) {
                        ie = true;
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(".jb-stgs-bdy").addClass("error_field");
                    } else if ( r === -1 ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_pfl_pfl_bd");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(".jb-stgs-bdy").addClass("error_field");
                    } else if ( r === -2 ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_pfl_pfl_bd_min");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(".jb-stgs-bdy").addClass("error_field");
                    } else {
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(".jb-stgs-bdy").removeClass("error_field");
                    }
                break;
            case "gender" :
                    if (! $(".jb-pfl-gdr-chs.active").length ) {
                        ie = true;
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if (! _f_Gdf().rgx_gdr.test($(".jb-pfl-gdr-chs.active").data("target")) ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_pfl_pfl_gdr");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else {
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).removeClass("error_field");
                    }
                break;
            case "city" :
                    v = $(x).val();
//                    Kxlib_DebugVars([v,_f_Gdf().rgx_cty.test(v)],true);
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if (! _f_Gdf().rgx_cty.test(v) ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_pfl_pfl_cty");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( v.length < _f_Gdf().cysrh_min ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_pfl_pfl_cty");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !$(x).data("ci") ) { //On vérifie qu'on a bien l'identifiant de la ville
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_pfl_pfl_cty");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else {
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).removeClass("error_field");
                    }
                break;
            case "pseudo" :
                    v = $(x).val();
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_psd.test(v) && v.length > _f_Gdf().psd_max ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_field_max");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_psd.test(v) && v.length < _f_Gdf().psd_min ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_field_min");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_psd.test(v) ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_acc_psd");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else {
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).removeClass("error_field");
                    }
                break;
            case "email" :
                    v = $(x).val();
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_eml.test(v) ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_acc_eml");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else {
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).removeClass("error_field");
                    }
                break;
            case "lang" :
                    v = $(".jb-stgs-acc-fld[data-ft='lang'] option:selected").val(); 
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                    } else if ( !_f_Gdf().rgx_lng_av.test(v) ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_acc_lng_avb");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_lng.test(v) ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_acc_lng");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else {
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).removeClass("error_field");
                    }
                break;
            case "opassword" :
                    v = $(x).val();
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                    } else if ( !_f_Gdf().rgx_pwd.test(v) ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_pwd_pwd");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else {
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).removeClass("error_field");
                    }
                break;
            case "npassword" :
                    v = $(x).val();
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                    } else if ( v.length < _f_Gdf().pwd_min  ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_pwd_pwd_min");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( v.length > _f_Gdf().pwd_max ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_pwd_pwd_max");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_pwd.test(v) && !_f_Gdf().rgx_pwd_dig ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_pwd_pwd_dig");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_pwd.test(v) && !_f_Gdf().rgx_pwd_spch ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_pwd_pwd_spch");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_pwd.test(v) ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_pwd_pwd");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else {
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).removeClass("error_field");
                    }
                break;
            case "npassword_c" :
                    v = $(x).val();
                    var rv = $(".jb-stgs-npwd-ipt").val();
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                        $(x).addClass("error_field");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                    } else if ( v.length < _f_Gdf().pwd_min  ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_pwd_pwd_min");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( v.length > _f_Gdf().pwd_max ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_pwd_pwd_max");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_pwd.test(v) && !_f_Gdf().rgx_pwd_dig ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_pwd_pwd_dig");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_pwd.test(v) && !_f_Gdf().rgx_pwd_spch ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_pwd_pwd_spch");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( _f_Gdf().rgx_pwd.test(v) && _f_Gdf().rgx_pwd.test(rv) && v !== rv ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_pwd_cfpwd");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else if ( !_f_Gdf().rgx_pwd.test(v) ) {
                        ie = true;
                        em = Kxlib_getDolphinsValue("stgs_acc_pwd_pwd");
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).addClass("error_field");
                    } else {
                        emb = ".jb-stgs-err-sbh[data-target='"+ft+"']";
                        $(x).removeClass("error_field");
                    }
                break;
            case "secu_seclog_ecwpsd" :
                    //EUH ... Ca sera toujours valide
                break;
            case "hikw" :
                    v = $("input[name=sgtgs-del-hikw]:checked",".jb-stgs-del-form").val();
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                    } else if ( !KgbLib_CheckNullity(v) && $.inArray(v,_f_Gdf().hikw) === -1 ) {
                        ie = true;
                    } 
                break;
            case "yilv" :
                    v = $("input[name=sgtgs-del-yilv]:checked",".jb-stgs-del-form").val();
                    if ( KgbLib_CheckNullity(v) ) {
                        ie = true;
                    } else if ( !KgbLib_CheckNullity(v) && $.inArray(v.toString().toUpperCase(),_f_Gdf().yilv) === -1 ) {
                        ie = true;
                    } else if ( v.toString().toUpperCase() === "OTHER" && !_f_Gdf().yilv_ot.test($(".jb-stgs-del-y-other-xpln").val()) ) {
                        ie = true;
                        $(".jb-stgs-del-y-other-xpln").addClass("error_field");
                    } else {
                        $(".jb-stgs-del-y-other-xpln").removeClass("error_field");
                    }
                break;
            case "ilbbif" :
                    v = $(".jb-stgs-del-free-xpln").val();
                    if ( !KgbLib_CheckNullity(v) && !_f_Gdf().ilbbif.test(v) ) {
                        ie = true;
                        $(".jb-stgs-del-free-xpln").addClass("error_field");
                    } else {
                        $(".jb-stgs-del-free-xpln").removeClass("error_field");
                    }
                break;
            case "delcf" : 
                    if (! $(x).is(":checked") ) {
                        ie = true;
                        $(".jb-stgs-del-lgls-ipt").addClass("red");
                    } else {
                        $(".jb-stgs-del-lgls-ipt").removeClass("red");
                    }
                break;
            default :
                    return;
                break;
        }
        
//        Kxlib_DebugVars(["IS ERROR => ",ie,"MESSAGE => ",em],true);
        
        if ( ie ) {
            if ( !KgbLib_CheckNullity(em) && ( !KgbLib_CheckNullity(emb) && $(emb).length ) ) {
                $(emb).text(em);
            } else if ( KgbLib_CheckNullity(em) && ( !KgbLib_CheckNullity(emb) && $(emb).length ) ) {
                $(emb).text("");
            }
            return false;
        } else {
            if ( !KgbLib_CheckNullity(emb) && $(emb).length ) {
                $(emb).text("");
            }
            return true;
        }
        
    };
    
    var _f_ChkBirth = function () {
        var dob_d = $(".jb-stgs-bdy-d").val();
        var dob_m = $(".jb-stgs-bdy-m").val();
        var dob_y = $(".jb-stgs-bdy-y").val();

        if ( KgbLib_CheckNullity(dob_d) | KgbLib_CheckNullity(dob_m) | KgbLib_CheckNullity(dob_y) )
            return;
        if ( dob_d === "init" | dob_m === "init" | dob_y === "init" )
            return;

        //On regarde si la date entrée est suffisemment ancienne (Utilisateur de 12 ans minimum)(format YYYY-MM-DD)
        var udob = _f_GetAge(dob_y + "-" + dob_m + "-" + dob_d);
//        Kxlib_DebugVars([dob_d,dob_m,dob_y,udob],true);
//        return;
        //On récupère la date au format américain MM-DD-YYYY avant de l'envoyer à la regex
        //fd : formatedDate
        var fd = dob_m + "-" + dob_d + "-" + dob_y;

        if ( _f_Gdf().rgx_bdate.test(fd) ) {
            if( parseInt(udob) < _f_Gdf().bdate_min ){
                return -2;  
            } else {
                return true;
            }
        }  else {
            return -1;  
        }
    };
    
    /* Fonction qui permet de retourner l'âge à partir de la date entrée */
    var _f_GetAge = function(ymd) {
        //td : ToDay
        var td = new Date();
        //bd : BirthDay
        var bd = new Date(ymd);
        var age = td.getFullYear() - bd.getFullYear();
        var m = td.getMonth() - bd.getMonth();
        if (m < 0 || (m === 0 && td.getDate() < bd.getDate())) {
            age--;
        }
        return age;
    };
    
    /* 
     * Gestion des dates impossibles.
     * La méthodes permet de corriger automatiquement une date en fonction du mois et/ou de l'année
     * Elle permet aussi de retirer les éléments qui ne sont pas possibles pour un mois. 
     * Par exemple 31 pour le mois de février.
     *  
     * [NOTE 25-10-14] @author L.C.
     * Il s'agit d'un code récupérer de la version "prototype".
     * J'ai ajouté la documentation.
     * */
    var _f_CrctDate = function() {
//    this.CorrectDate = function() {
        //Reset des dates 'fausses'
        $(".jb-stgs-bdy-d option").attr('disabled', false); 
//        alert($(".jb-stgs-bdy-m").val());
//        return;
        switch($(".jb-stgs-bdy-m").val()){
            case "02":
                var lpyr = new Date($(".jb-stgs-bdy-y").val(),2,0).getDate();
                if(lpyr === 28){
                    if($(".jb-stgs-bdy-d").val() === "31" | $(".jb-stgs-bdy-d").val() === "30" | $(".jb-stgs-bdy-d").val() === "29"){
                        $(".jb-stgs-bdy-d").val('28');
                    }
                    $(".jb-stgs-bdy-d option[value='29']").attr('disabled', true);
                    $(".jb-stgs-bdy-d option[value='30']").attr('disabled', true);
                    $(".jb-stgs-bdy-d option[value='31']").attr('disabled', true);
                } else if(lpyr === 29){
                    if($(".jb-stgs-bdy-d").val() === "31" | $(".jb-stgs-bdy-d").val() === "30"){
                        $(".jb-stgs-bdy-d").val('29');
                    }
                    $(".jb-stgs-bdy-d option[value='30']").attr('disabled', true);
                    $(".jb-stgs-bdy-d option[value='31']").attr('disabled', true);
                }
                break;
            case "04":
                if($(".jb-stgs-bdy-d").val() === "31"){
                    $(".jb-stgs-bdy-d").val('30');
                }
                $(".jb-stgs-bdy-d option[value='31']").attr('disabled', true);
                break;
            case "06":
                if($(".jb-stgs-bdy-d").val() === "31"){
                    $(".jb-stgs-bdy-d").val('30');
                }
                $(".jb-stgs-bdy-d option[value='31']").attr('disabled', true);
                break;
            case "09":
                if($(".jb-stgs-bdy-d").val() === "31"){
                    $(".jb-stgs-bdy-d").val('30');
                }
                $(".jb-stgs-bdy-d option[value='31']").attr('disabled', true);
                break;
            case "11":
                if($(".jb-stgs-bdy-d").val() === "31"){
                    $(".jb-stgs-bdy-d").val('30');
                }
                $(".jb-stgs-bdy-d option[value='31']").attr('disabled', true);
                break;
        }
    };
    
    /**************************************************** PASSWORD SCOPE **************************************************/
    
    var pwto;
    var _f_PwdCatch = function(e,x) {
//    this.PwdCatch = function(e,x) {
        if ( KgbLib_CheckNullity(e) | KgbLib_CheckNullity(x) ) {
            return;
        }
        
        if ( pwto ) {
            clearTimeout(pwto);
        }
        
        //QueryText
        var pwqt = $(x).val();
        var wt = _f_Gdf().pwt;
        //On reset le "SelectedElement" pour que le formulaire ne soit pas validé
//        $(".jb-ins-city-ipt").data("se","");
        //On retire le marqueur "valid"
//        _f_IsRowVald("ctysrh");
        
//        Kxlib_DebugVars([qt.length >= _f_Gdf().cysrh_min],true);
//        Kxlib_DebugVars([wi,qt,_f_Gdf().cysrh_min],true);
//        return;
        var iv = true;
        if ( pwqt.length ) {
            
            pwto = setTimeout(function() {
                
//                Kxlib_DebugVars([CAR. SPE. => "+(pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length]);
//                Kxlib_DebugVars([DIGIT. => "+(pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length]);
//                Kxlib_DebugVars([UPPER CASE. => "+(pwqt.match(_f_Gdf().rgx_pwd_case) || []).length]);
//                Kxlib_DebugVars([INTERDIT => "+(pwqt.match(_f_Gdf().rgx_pwd_np) || []).length]);
//                Kxlib_DebugVars([TOTAL => "+pwqt.length]);
//                Kxlib_DebugVars();
                if ( pwqt.length < _f_Gdf().pwd_min ) {
                    _f_InsPwdLevel(1);
                    iv = false;
                } else if ( pwqt.length > _f_Gdf().pwd_max ) {
                    _f_InsPwdLevel(6);
                    iv = false;
                } else if (! _f_Gdf().rgx_pwd.test(pwqt) ) {
                    _f_InsPwdLevel(1);
                    iv = false;
                } else if ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length === 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length === 1 && pwqt.length < 8 ) {
                    /*
                     * (Peu mieux faire) 
                     * Respecte les conditions mais il n'y a qu'un chiffre et un caractère spéciale et la longueur est < 8
                     */
                    _f_InsPwdLevel(2);
                } else if ( 
                            ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length === 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length === 1 && pwqt.length >= 8 && pwqt.length <= 16) ||
                            ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && ( ( (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length === 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1 ) || (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length === 1 ) && pwqt.length <= 10 )
                        ) {
                    /*
                     * (Encore un effort) 
                     * Respecte les conditions mais il n'y a qu'un chiffre et un caractère spéciale et la longueur est > 8
                     * Respecte les conditions avec au moins deux indicateurs sup. pour l'un OU l'autre. Exemple toto1*+ OU toto23* MAIS la longueur <= 10
                     */
                    _f_InsPwdLevel(3);
                } else if ( 
                       ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length === 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length === 1 && pwqt.length > 16 ) ||
                       ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1 && !(pwqt.match(_f_Gdf().rgx_pwd_case) || []).length  && pwqt.length >= 8 ) ||
                       ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_case) || []).length > 1 && pwqt.length >= 12 && (pwqt.match(_f_Gdf().rgx_pwd_np) || []).length ) ||
                       ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && ( ( (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length === 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1 ) || (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length === 1 ) && pwqt.length > 10 )
                )
                {
                    /*
                     * (Très bien) 
                     * Respecte les conditions avec au moins deux indicateurs sup. pour l'un OU l'autre. Exemple toto1*+ OU toto23* MAIS la longueur > 10
                     * Respecte les conditions avec au moins deux indicateurs sup. pour chaque. Exemple toto12*+ OU toto23+* ET la longueur >= 10
                     */
                    _f_InsPwdLevel(4);
                } else if ( 
                        pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1 && pwqt.length >= 12 
                        && (pwqt.match(_f_Gdf().rgx_pwd_case) || []).length > 1 && !(pwqt.match(_f_Gdf().rgx_pwd_np) || []).length
                        ) {
                    /*
                     * (Excellent) 
                     * Respecte les conditions avec au moins deux indicateurs sup. pour chaque. Exemple azerty12*+ OU azerty23+* ET la longueur > 8
                     * ET
                     * Au moins un caractère UPPERCASE [A-Z]
                     * 
                     * (Amelioration)
                     * Il faut que l'utilisateur n'utilise pas un mot provenant de son pseudo ou de son nom.
                     * L'utilisateur n'utilise pas des combinaisons connues telles : 1234, toto, azerty
                     */
                    _f_InsPwdLevel(5);
                }
                
                if ( iv ) {
                    //On valide le champ
                    $(x).data("iv",1);
                    //On retire le marqueur d'erreur visuel
                    $(x).removeClass("error_field");
                    
                    var s_ = $(".jb-ins-pwdcf-ipt").val();
                    if ( _f_Gdf().rgx_pwd.test(s_) ){
                        //On ajoute le marqueur de validation visuel
//                        _f_ShwValidMark(x);
                    }
                }
                
            },wt);
        } else {
            //On reinitialise la barre de strength
            _f_InsPwdLevel(0);
            //On retire le marqueur de validation visuel
//            _f_HidValidMark(x);
        }
        
    };
    
    /***********************************************************************************************************************/
    
    var _f_GatherFD = function(fi) {
        /*
         * Permet de regrouper dans un tableau les données d'un formulaire.
         * Les index coeincident avec ceux de la zone BUFFER.
         * 
         * Cette standardisation est utile pour :
         *  - la compréhension des données
         *  - les traitements à effectuer au niveau de la zone BUFFER 
         *  - le transfert et le traitement des données coté serveur
         *  - communication entre le serveur et FE
         *  
         *  La méthode ne controle pas la validité des données
         */
        if ( KgbLib_CheckNullity(fi) )
            return;
        
        var dt;
        switch (fi) {
            case "profile" :
                    var dob_d = $(".jb-stgs-bdy-d").val();
                    var dob_m = $(".jb-stgs-bdy-m").val();
                    var dob_y = $(".jb-stgs-bdy-y").val();
                    var ymd = dob_y + "-" + dob_m + "-" + dob_d;
                    var bd_t = ((new Date(ymd)).getTime()/1000);
                    
//                    Kxlib_DebugVars([bd_t,(new KxDate(bd_t)).getTime()],true);
                    
                    dt = {
                        "fullname" : $(".jb-stgs-pfl-fld[data-ft='fullname']").val(),
                        "birthdate_tsp" : (new KxDate(bd_t)).getTime(),
                        "gender" : $(".jb-pfl-gdr-chs.active").data("target"),
                        "city": $(".jb-stgs-pfl-fld[data-ft='city']").data("ci")
                    };
                break;
            case "account" :
                    dt = {
                        "email" : $(".jb-stgs-acc-fld[data-ft='email']").val(),
                        "pseudo" : $(".jb-stgs-acc-fld[data-ft='pseudo']").val(),
                        "lang" : $(".jb-stgs-acc-fld[data-ft='lang'] option:selected").val()
                    };
                break;
            case "password" :
                    dt = {
                        "opwd" : $(".jb-stgs-pwd-fld[data-ft='opassword']").val(),
                        "npwd" : $(".jb-stgs-pwd-fld[data-ft='npassword']").val(),
                        "npwd_c" : $(".jb-stgs-pwd-fld[data-ft='npassword_c']").val()
                    };
                break;
            case "secu_login" :
                    dt = {
                        "ecwpsd" : ( $(".jb-stgs-seclog-fld[data-ft='secu_seclog_ecwpsd']").is(":checked") ) ? "chk" : "uchk"
                    };
                break;
            case "delete" :
                    var yilv = $("input[name=sgtgs-del-yilv]:checked",".jb-stgs-del-form").val().toUpperCase();
                    dt = {
                        "hikw" : $("input[name=sgtgs-del-hikw]:checked",".jb-stgs-del-form").val(),
                        "yilv" : yilv,
                        "yilv_ot" : ( $(".jb-stgs-del-y-other-xpln").val() ) ? $(".jb-stgs-del-y-other-xpln").val() : "",
                        "ilbbif" : ( $(".jb-stgs-del-free-xpln").val() ) ? $(".jb-stgs-del-free-xpln").val() : ""
                    };
                break;
            default :
                break;
        }
        
        return dt;
        
    };
    
    var _f_SvFormWorthIt = function(fi,fd) {
        /*
         * Permet de comparer les données présents dans le formulaire à ceux dans le BUFFER afin et détermine si ça vaut la peine de contacter le serveur.
         * 
         * On contacte le serveur à la condition qu'au moins une des données est différente.
         */
        if ( KgbLib_CheckNullity(fi) | KgbLib_CheckNullity(fd) )
            return;
        
        //sbfd : StoredBuFferDatas
        var sbfd = _f_GetBufferDatas();;
        switch (fi) {
            case "profile" :
                    bfd = {
                        "fullname" : sbfd.profile.fullname,
                        "birthdate_tsp" : (sbfd.profile.birthdate_tsp),
                        "gender" : sbfd.profile.gender,
                        "city": sbfd.profile.city.i
                    };
                break;
            case "account" :
                    bfd = {
                        "email" : sbfd.account.email,
                        "pseudo" : sbfd.account.pseudo,
                        "lang" : sbfd.account.lang
                    };
                break;
            case "password" :
                    return true;
                break;
            case "secu_login" :
                    bfd = {
                        "ecwpsd" : ( parseInt(sbfd.security.login.enable_lg_psd) === 1 ) ? "chk" : "uchk"
                    };
                break;
            case "delete" :
                    //Il n'y a rien à comparer dans le cas de la suppresion de compte
                    return true;
                break;
            default :
                break;
        }
        
        //IsDifference
        var id = false;
        $.each(fd,function(x,v){
//            Kxlib_DebugVars([bfd[x].toString(),v.toString()],true);
            if ( bfd[x].toString() !== v.toString() ) {
//                Kxlib_DebugVars([bfd[x],v],true);
                id = true;
                return false;
            } 
        });
        
        return id;
        
    };
    
    var _f_ClzConfBP = function() {
//    this.CloseConfBP = function() {
        /*
         * Permet de fermer la fenetre de confirmation après un click si cela est possible.
         */
        
        if ( $(".jb-stgs-vbp-ccl").data("lk") === 1 ) {
            return;
        }
        
        //On ferme la fenetre de confirmation
        _f_HidConfimBP();
        
        var scp = $(".jb-stgs-vbp-mx").data("scp");
//        Kxlib_DebugVars([scp,$(".jb-stgs-submit[data-target='"+scp+"']").data("lk")],true); 
        
        //On unlock le bouton correspondant au scope
        $(".jb-stgs-submit[data-target='"+scp+"']").data("lk",0);
        //On fait disparaittre le spinner
        _f_TogFormSpnr(scp);
        
    };
    
    var _f_CnfrmBP = function () {
//    this.ConfimBP = function () {
        var v = $(".jb-stgs-vbp-ipt").val();
        
        if ( KgbLib_CheckNullity(v) ) {
            //TODO : Faire bizzer la zone comme pour un téléphone pour dire que la zone NE PEUT ETRE VIDE
            Kxlib_DebugVars(["vide"]);
            return;
        } else if (! _f_Gdf().rgx_pwd.test(v) ) {
            alert("Ce n'est pas un mot de passe");
            return;
        }
        
        //Faire passer en mode Pending
        _f_ConfimBPdg();
        
        //Envoyer le signal
        $(s_).trigger("operended");
        
    };
    
    /********************************************************************************************************************************************************/
    /****************************************************************** SERVER SCOPE ************************************************************************/
    /********************************************************************************************************************************************************/
    
    var _Ax_StgsPullDatas = Kxlib_GetAjaxRules("INS_PULLDATAS");
    var _f_Srv_StgsPullDatas = function (qt,scp,t,s,x) {
        if ( KgbLib_CheckNullity(qt) | KgbLib_CheckNullity(scp) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(s) )
            return;
        
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    return;
                }
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
                        switch (d.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                                        Kxlib_HandleCurrUserGone();
                                    break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                        return;
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                    return;
                                break;
                            case "__ERR_VOL_DATAS_MISG" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                    return;
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                    return;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                    return;
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                                break;
                        }
                    } 
                    return;
                } else if ( d.hasOwnProperty("return") )  {
                    /*
                     * Données attendues : 
                     *  (1) Données sur le résultats de la recherche
                     *      Il s'agit des données sur les villes composées elles mêmes de la manière suivante :
                     *          - Nom Ville
                     *          - Pays
                     *          - Population
                     *          - Nombre de villes similaire
                     */
                     if (! KgbLib_CheckNullity(d.return)  )  {
                         rds = [d.return];
                         $(s).trigger("datasready",rds);
                     } else {
                         $(s).trigger("operended");
                     }
                } else return;
                
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                Kxlib_DebugVars([e],true);
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        
        var toSend = {
            "urqid": _Ax_StgsPullDatas.urqid,
            "datas": {
                //La chaine recherchée
                "qt":qt,
                //InscriptionSCoPe
                "iqsp": scp,
                "t": t,
                "x": (! KgbLib_CheckNullity(x) ) ? x : "" 
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_StgsPullDatas.url, wcrdtl : _Ax_StgsPullDatas.wcrdtl });
        
    };
    
    var _f_AxGetRules = function (fi,u) {
        if ( KgbLib_CheckNullity(fi) ) {
            return;
        }
        
        var axi = "";
        switch (fi) {
            case "profile" :
                    axi = "STGS_SUBT_PFL";
                break;
            case "account" :
                    axi = "STGS_SUBT_ACC";
                break;
            case "password" :
                    axi = "STGS_SUBT_PWD";
                break;
            case "secu_login" :
                    axi = "STGS_SUBT_SEC_CO";
                break;
            case "delete" :
                    axi = "STGS_SUBT_DELACC";
                break;
            default :
                break;
        }
        
        return Kxlib_GetAjaxRules(axi,u);
    };
    
    
    
    var _f_Srv_StgsSubmit = function (_Ax_Rules,_Ax_Datas,scp,x,s) {
        if ( KgbLib_CheckNullity(_Ax_Rules) | KgbLib_CheckNullity(_Ax_Datas) | KgbLib_CheckNullity(scp) | KgbLib_CheckNullity(x) | KgbLib_CheckNullity(s) ) {
            return;
        }
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else return;
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    //On unlock le bouton
                    $(x).data("lk",0);
                    //On fait disparaittre le spinner
                    _f_TogFormSpnr(scp);
                    //On fait disparaitre la zone de confirmation
                    _f_HidConfimBP();
                    
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
                                        return;
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                    return;
                                break;
                            case "__ERR_VOL_DATAS_MSG" :
                                    Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                                    return;
                                break;
                            case "__ERR_VOL_WRG_DATAS" :
                                    return;
                            case "__ERR_VOL_WRG_HACK" :
                                    /*
                                     * Les données qui sont arrivées au niveau du serveur ont été jugées corrompues.
                                     * Ce cas n'arrive que si FE envoie des données non reconnues ou innatendues.
                                     * La plupart du temps, il s'agit de données dites 'système'.
                                     * Ces données sont fournies par FE et non par USER. Aussi, elles suivent une règle précise.
                                     * Si la ou les règles ne sont pas respectées, on déclare la requête comme une tentative de HACK.
                                     */
                                    return;
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                                break;
                        }
                    } 
                    return;
                } else if ( ! KgbLib_CheckNullity(d.return) )  {
                    /*
                     * Données attendues : 
                     *  r :
                     *      "DONE" : En cas de succes
                     *      "_FLD_X, _FLD_Y, ..." : En cas d'echec
                     *  anx (facultatif) : (Données annexes)
                     *      -> Données validées qui pourront mettre à jour le BUFFER 
                     */
                     rds = [d.return];
                     $(s).trigger("operended",rds);
                } else return;
                
            } catch (e) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([e],true);
  
                //On unlock le bouton
                $(x).data("lk",0);
                //On fait disparaittre le spinner
                _f_TogFormSpnr(scp);
                //On fait disparaitre la zone de confirmation
                _f_HidConfimBP();
                
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            
            //On unlock le bouton
            $(x).data("lk",0);
            //On fait disparaittre le spinner
            _f_TogFormSpnr(scp);
            //On fait disparaitre la zone de confirmation
            _f_HidConfimBP();
            
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            
            return;
        };
        
//        Kxlib_DebugVars([JSON.stringify(_Ax_Rules),JSON.stringify(_Ax_Datas)],true);
        
        var toSend = {
            "urqid": _Ax_Rules.urqid,
            "datas": _Ax_Datas
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_Rules.url, wcrdtl : _Ax_Rules.wcrdtl });
        
    };
    
    
    /**********************************************************************************************************************************************************/
    /*********************************************************************** VIEW SCOPE ***********************************************************************/
    /**********************************************************************************************************************************************************/
    
    var _f_LMnHvr = function (x) {
//    this.LMenuHover = function (x) {
        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
        
        setTimeout(function(){
            _f_ShwLMenuHint(x);
        }, 50);
    };
    
    var _f_ShwLMenuHint = function(x) {
        if ( KgbLib_CheckNullity(x) | !$(x).data("mn") ){
            return;
        }
        
        var mn = $(x).data("mn"), hm;
        switch (mn) {
            case "profile":
                    hm = Kxlib_getDolphinsValue("p_pfl_hint_profile");
                break;
            case "account":
                    hm = Kxlib_getDolphinsValue("p_pfl_hint_account");
                break;
            case "security":
                    hm = Kxlib_getDolphinsValue("p_pfl_hint_security");
                break;
            case "about":
                    hm = Kxlib_getDolphinsValue("p_pfl_hint_about");
                break;
            default:
                    return;
                break;
        }
        
        if (! KgbLib_CheckNullity(hm) ) {
            $(".jb-pfl-lm-hint").stop(true);
            $(".jb-pfl-lm-hint").html(hm);
            $(".jb-pfl-lm-hint").fadeToggle(250);
        }
        
    };
    
    var _f_ShwSideInfo = function (info){
        
        if ( lastInfoDisplayed !== info ) {
            $('#pfl_infomsg').fadeOut(500, function(){
                $('#pfl_infomsg').html(info);
                $('#pfl_infomsg').fadeIn(500);
            });
            lastInfoDisplayed = info;
        } else {
            stop();
        }
    };
    
    var _f_MnSwitch = function (x) {
        if ( KgbLib_CheckNullity(x) | !$(x).length | !$(x).data("mn") ) {
            return;
        }
        
        //no = NewOne; oo = OldOne
        var no, oo, mn = $(x).data("mn");
        switch (mn) {
            case "profile":
            case "account":
            case "security":
            case "delete":   
            case "about":   
                no = $(".jb-stgs-lmenu[data-mn='"+mn+"']");
                oo = $(".jb-stgs-lmenu.active");
                break;
            default:
                    return;
                break;
        }
        
        if ( KgbLib_CheckNullity(no) | KgbLib_CheckNullity(oo) ) {
            return;
        } else {
            $(oo).removeClass("active");
            $(no).addClass("active");
        }
        
    };
    
    var _f_WdwSwitch = function(x) {
        /*
         * Permet de changer visuellement la fenetre active.
         * Pour cela, la méthode recoit le trigger de type menu
         */
        if ( KgbLib_CheckNullity(x) | !$(x).length | !$(x).data("mn") ) {
            return;
        }
        
        //no = NewOne; oo = OldOne, bc = Border-Color
        var no, oo, mn = $(x).data("mn"), bc;
        switch (mn) {
            case "profile":
                    no = $(".jb-stgs-wdw[data-wdw='"+mn+"']");
                    oo = $(".jb-stgs-wdw.active");
                    bc = "#FFA500";
                break;
            case "account":
                    no = $(".jb-stgs-wdw[data-wdw='"+mn+"']");
                    oo = $(".jb-stgs-wdw.active");
                    bc = "#0BEE2F";
                break;
            case "security":
            case "delete": 
                    no = $(".jb-stgs-wdw[data-wdw='"+mn+"']");
                    oo = $(".jb-stgs-wdw.active");
                    bc = "#9188FF";
                break;
            case "about":   
                    no = $(".jb-stgs-wdw[data-wdw='"+mn+"']");
                    oo = $(".jb-stgs-wdw.active");
                    bc = "#B3B3B3";
                break;
            default:
                    return;
                break;
        }
        
//        Kxlib_DebugVars([KgbLib_CheckNullity(no), !$(no).length,  KgbLib_CheckNullity(oo), !$(oo).length, $(oo).data("wdw") === $(no).data("wdw")],true);
        
        if ( ( KgbLib_CheckNullity(no) | !$(no).length ) | ( KgbLib_CheckNullity(oo) | !$(oo).length ) | $(oo).data("wdw") === $(no).data("wdw") ) {
            return;
        } else {
            $(oo).removeClass("active");
            $(oo).addClass("this_hide");
            $(no).removeClass("this_hide");
            $(no).addClass("active");
            
            /* A preserver pour la sortie, pour garder un peu d'emotion
            $(no).stop(true,true).animate({
                "border-color": bc,
                easing: "swing"
            },400);
            $(oo).removeAttr("style");
            //*/
        }
    };
    
    /********************************************* FORMULAIRE ***********************************************/
    
    var _f_TogFormSpnr = function (trg,show) {
        if ( KgbLib_CheckNullity(trg) ) {
            return;
        }
        
        show = ( show === true) ? true : false;
        
        switch (trg) {
            case "profile" :
                    ss = ".jb-stgs-pfl-spnr";
                break;
            case "profile_city_smpl" :
                    ss = ".jb-stgs-cty-ipt-spnr";
                break;
            case "profile_city_cstm" :
                    ss = ".jb-stgs-cty-cstm-spnr";
                break;
            case "account" :
                    ss = ".jb-stgs-acc-spnr";
                break;
            case "pseudo" :
                    ss = ".jb-stgs-psd-ipt-spnr";
                break;
            case "email" :
                    ss = ".jb-stgs-eml-ipt-spnr";
                break;
            case "password" :
                    ss = ".jb-stgs-pwd-spnr";
                break;
            case "secu_login" :
                    ss = ".jb-stgs-seclog-spnr";
                break;
            case "delete" :
                    ss = ".jb-stgs-del-spnr";
                break;
            default :
                    return;
                break;
        }
        
        if ( $(ss).length && $(ss).hasClass("this_hide") && show ) {
            $(ss).removeClass("this_hide");
        } else if ( $(ss).length && !$(ss).hasClass("this_hide") && !show ) {
            $(ss).addClass("this_hide");
        }
    };
    
    
    var _f_Pfl_InitGdr = function () {
//    this.Pfl_InitGdr = function () {
        var x = $(".jb-stgs-pfl-gdr");
        if ( !$(x).length | $(x).data("g") ) {
            return;
        } else {
            _f_Vw_Pfl_ChgGdr(x);
        }
    };
    
    var _f_Pfl_InitBdy = function () {
//    this.Pfl_InitBdy = function () {
        /*
         * Permet d'initialiser le champ date de naissance à sa valeur d'origine.
         */
        //On récupère les données dans le BUFFER
        var bfd = _f_GetBufferDatas();
        
        if ( KgbLib_CheckNullity(bfd) )
            return;
        
        var d = bfd.profile.bdy_d;
        var m = bfd.profile.bdy_m;
        var y = bfd.profile.bdy_y;
//        Kxlib_DebugVars([d,m,y],true);
        
        //Traitement de jour
        $(".jb-stgs-bdy-d option:selected").removeAttr("selected");
        $(".jb-stgs-bdy-d option[value='"+d+"']").attr("selected",true);
        //Traitement du mois
        $(".jb-stgs-bdy-m option:selected").removeAttr("selected");
        $(".jb-stgs-bdy-m option[value='"+m+"']").attr("selected",true);
        //Traitement du mois
        $(".jb-stgs-bdy-y option:selected").removeAttr("selected");
        $(".jb-stgs-bdy-y option[value='"+y+"']").attr("selected",true);
        
    };
    
    var _f_Vw_Pfl_ChgGdr = function(x) {
//    this.View_Pfl_ChgGdr = function(x) {
        
        if ( KgbLib_CheckNullity(x) | !$(x).length | !$(x).data("target") ) {
            return;
        }
        
        var gv = $(x).data("target").toLowerCase();
        switch(gv) {
            case "m":
                    $(".jb-stgs-pfl-gdr").stop(true,true).toggleClass("female",false,400,"easeOutSine");
                    $(".jb-pfl-gdr-f").removeClass("active");
                    $(".jb-pfl-gdr-m").addClass("active");
//                    $(x).data("target","f");
                    $(".jb-stgs-pfl-gdr").data("target","f");
                    $(".jb-pfl-gdr-sldr-ch").data("target","f");
                break;
            case "f":
                    $(".jb-stgs-pfl-gdr").stop(true,true).toggleClass("female",true,400,"easeOutSine");
                    $(".jb-pfl-gdr-m").removeClass("active");
                    $(".jb-pfl-gdr-f").addClass("active");
//                    $(x).data("target","m");
                    $(".jb-stgs-pfl-gdr").data("target","m");
                    $(".jb-pfl-gdr-sldr-ch").data("target","m");
                break;
            default :
                    return;
                break;
        }
        
//        Kxlib_DebugVars(["HAS FEMALE => ",$(".jb-stgs-pfl-gdr").hasClass("female"),"WHO HAS ACTIVE => ",$(".jb-pfl-gdr-chs.active").attr(id),$(".jb-stgs-pfl-gdr").data("target")],true);
        
    };
    
    var _f_ClrForm = function(x) {
//    this.ClearForm = function(x) {
        
        if ( KgbLib_CheckNullity(x) | !$(x).length | !$(x).data("target") ) {
            return;
        }
        try {
            
            var trg = $(x).data("target"), f;
            switch (trg) {
                case "jb-stgs-form-pfl" :
                    f = $("." + trg);
                    _f_TogFormSpnr("profile");
                    break
                case "jb-stgs-form-acc" :
                    f = $("." + trg);
                    _f_TogFormSpnr("account");
                    break;
                case "jb-stgs-form-pwd" :
                    f = $("." + trg);
                    $(".jb-pwd-strength").width(0);
                    _f_TogFormSpnr("password");
                    break;
                default:
                    return;
                    break;
            }
            
            if (!$(f).length | KgbLib_CheckNullity($(f).attr("id"))) {
                return;
            } else {
                var i = $(f).attr("id");
//            Kxlib_DebugVars([$(Kxlib_ValidIdSel($(f).attr("id"))).find("input").length],true);
                Kxlib_ClearForm(i);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }

    };
    
    
    /******************************************** CITYSEARCH ******************************************/
    var ltoq = 0;
    var _f_ShwCtySrhReslt = function (qt,d,scp) {
        if ( KgbLib_CheckNullity(qt) | KgbLib_CheckNullity(d) | KgbLib_CheckNullity(scp) )
            return; 
        
        //On vérifie qu'il s'agit de la version la plus récente
        if ( d.toq < ltoq ) 
            return;
        
//        Kxlib_DebugVars([toq,d.toq]);
        //On inscrit le nouveau temps
        ltoq = d.toq;
        
       _f_RstCitySrhList("smpl");
       _f_RstCitySrhList("cstm");
       
//       Kxlib_DebugVars([JSON.stringify(d)],true);
//       return;
        var b, bl, i;
        b = ( scp === "smpl" ) ? ".jb-cty-smpl-list-mx" : ".jb-cty-cstm-list-mx";
        
        bl = $(b).find(".jb-stgs-city-list");
        $.each(d.cities,function(x,v){
             //On vérifie si l'élément n'existe pas déjà
             i = ( scp === "smpl" ) ? v.cynm : v.cyid;
             var foo = _f_CtySrhChkExists(i,scp);
             if ( typeof foo === "undefined" ) {
                 return false;
             } else if ( foo === true ) {
                 return;
             }

             //On prépare l'élément
             var e = _f_PprCtySrhReslt(qt,v,scp);

             //On rebind
             e = _f_RebindCtySrhReslt(e,scp);

             //On ajoute l'élement
    //                $(e).hide().appendTo(bl).fadeIn(250);
             $(e).appendTo(bl);
         });
       
        //On masque le spinner
        var sp = "profile_city_"+scp;
        _f_TogFormSpnr(sp);
        
       //On affiche le bloc
       $(b).removeClass("this_hide");
       
    };
    
    var _f_PprCtySrhReslt = function (qt,d,scp) {
        
        if ( KgbLib_CheckNullity(qt) |  KgbLib_CheckNullity(d) | KgbLib_CheckNullity(scp) )
            return; 
        
        var e;
        if ( scp === "smpl" ) {
            /*
             * ci: CheckId
             * ii: ItemId
             */
            var e1 = $("<li>").attr({
                "class": "stgs-city-list-row jb-cty-list-elt"
            }).data("ci",d.cynm).data("ii",d.cyid);
            var tle = d.cypop+" "+Kxlib_getDolphinsValue("__COM_LANG_RESID");
            var e2 = $("<a/>").attr({
                "class": "stgs-city-list-trg jb-city-list-trg clearfix2",
                "title": tle,
                "href": "",
                "data-ii": d.cyid,
                "data-cycop": d.copies
            }).data("obj",scp);
            
            var rgx = new RegExp("("+qt+")", "ig");
            var cnm = d.cynm.replace(rgx, "<b>$1</b>");
            var e3 = $("<span/>").attr({
                "class": "jb-cy-list-cynm"
            }).html(cnm);
            var e4 = $("<span/>").text(", ");
            var e5 = $("<span/>").attr({
                "class": "jb-cy-list-cycn"
            }).text(d.cycn);
            
            if ( parseInt(d.copies) > 1 ) {
                var e6 = $("<span/>").attr({
                    "class": "cy-list-cycop-mx jb-cy-list-cycop-mx"
                });
                var e61 = $("<span/>").attr({
                    "class": "jb-cy-list-cycop-nb"
                }).text(d.copies);
                var cop_lib = " "+Kxlib_getDolphinsValue("p_cities");
                var e62 = $("<span/>").attr({
                    "class": "jb-cy-list-cycop-lib"
                }).text(cop_lib);
                
                $(e6).append(e61,e62);
                
                $(e2).append(e3,e4,e5,e6);
            } else {
                $(e2).append(e3,e4,e5);
            }
            
            $(e1).append(e2);
            e = e1;
        } else {
//            <tr class='jb-cty-list-elt jb-city-list-trg" data-obj='cstm" data-ci='cyid" data-ii='cyid" data-cycop=1 >
//                <td class='jb-cy-list-cynm">Ville this_hide</td>
//                <td class='jb-cy-list-cycn">FR</td>
//                <td class='jb-cy-list-cypop">10<?php echo $i; ?></td>
//            </tr>
            var e1 = $("<tr/>").attr({
                "class": "jb-cty-list-elt jb-city-list-trg",
                "href": "",
                "data-ci": d.cyid,
                "data-ii": d.cyid,
                "data-cycop": 1
            }).data("obj",scp);
            
            var e11 = $("<td/>").attr({
                "class": "jb-cy-list-cynm"
            }).text(d.cynm);
            
            var e12 = $("<td/>").attr({
                "class": "jb-cy-list-cycn"
            }).text(d.cycn);
            
            var e13 = $("<td/>").attr({
                "class": "jb-cy-list-cypop"
            }).text(d.cypop);
            
            $(e1).append(e11,e12,e13);
            
             e = e1;
        }
        
//        e = $.parseHTML(e);
        
        return e;
    };
    
    var _f_RebindCtySrhReslt = function (e,scp) {
        if ( KgbLib_CheckNullity(e) | KgbLib_CheckNullity(scp) )
            return; 
        
        var x = ( scp === "smpl" ) ? $(e).find(".jb-city-list-trg") : $(e);
        
        $(x).click(function(e) {
            Kxlib_PreventDefault(e);
            
            _f_CySrhSlct(this);
        });
//        e = $(e).find().children().on("click",gt.CySrhSelect);
//        alert($(e).find(".jb-city-list-trg").length);
        
        return e;
    };
    
    var _f_RstCitySrhList = function(scp) {
        /*
         * Masque la liste et la vider.
         */
        if ( KgbLib_CheckNullity(scp) || !$.inArray(scp,_f_Gdf().scp) )
            return; 
        
        var b = ".jb-cty-list-mx[data-obj='"+scp+"']";
        
        $(b).addClass("this_hide");
        $(b).find(".jb-cty-list-elt").remove();
    };
    
    var _f_ShwCstmCitySrhList = function() {
        $(".jb-cty-cstm-list-mx").removeClass("this_hide");
    };
    
    /************************************************* PASSWORD SCOPE **************************************************/
    
    
    var _f_InsPwdLevel = function(gl) {
        //GivenLevel
        if ( KgbLib_CheckNullity(gl) )
            return;
        
        /*
         * w: Width
         * clr: Color
         * ci: Code Infos
         */
        var w, clr,ci;
        switch (gl) {
            case 0 :
                    w = 0;
                    clr = null;
                break;
            case 1 :
                    w = "20%";
                    clr = "#f00";
                    ci = "ins_infos_pwd_notvl";
                break;
            case 2 :
                    w = "40%";
                    clr = "#ff6223";
                    ci = "ins_infos_pwd_cdb";
                break;
            case 3 :
                    w = "60%";
                    clr = "#b7f121";
                    ci = "ins_infos_pwd_notbd";
                break;
            case 4 :
                    w = "80%";
                    clr = "#00b33d";
                    ci = "ins_infos_pwd_good";
                break;
            case 5 :
                    w = "100%";
                    clr = "#00b33d";
                    ci = "ins_infos_pwd_xclt";
                break;
            case 6 :
                    //Le cas où le mot de passe est trop long
                    w = "100%";
                    clr = "#f00";
                    ci = "ins_infos_pwd_notvl";
                break;
            default :
                break;
        }
        
        if ( !w | !clr ) {
            $(".jb-pwd-strength").stop(true,true).animate({
                "width": "0%"
            }); 
            
            $(".jb-pwd-strength").removeAttr("title");
        } else {
            $(".jb-pwd-strength").stop(true,true).animate({
                "width": w,
                "background-color": clr
            });
            
            var mi = Kxlib_getDolphinsValue(ci);
            $(".jb-pwd-strength").attr({
                "title": mi
            });
        }
            
    };
    
    /************************************ CONFIRM BYPASS ************************************/
    
    var _f_ShwConfimBP = function (scp) {
        /*
         * Permet de faire apparaitre la fenetre qui demande de confirmer la validation du formulaire par mot de passe.
         */
        if ( KgbLib_CheckNullity(scp) ) {
            return;
        }
        
        //On reset la fenetre de confirmation
        _f_RstConfimBP();
        
        //On fait apparaitre la fenetre
        $(".jb-stgs-vbp-mx").removeClass("this_hide");
        
        //On place le focus
        $(".jb-stgs-vbp-ipt").focus();
        
        //On assigne le scope
        $(".jb-stgs-vbp-mx").data("scp",scp);
        
    };
    
    var _f_HidConfimBP = function () {
        /*
         * Permet de faire apparaitre la fenetre qui demande de confirmer la validation du formulaire par mot de passe.
         */
        //On masque la fenetre
        $(".jb-stgs-vbp-mx").addClass("this_hide");
        //On vide le formulaire
        $(".jb-stgs-vbp-ipt").val("");
    };
    
    var _f_RstConfimBP = function () {
        /*
         * Permet de réinitialiser la fentetre
         */
        
        //On vide le formulaire (Précaution)
        $(".jb-stgs-vbp-ipt").val("");
        
        //On remet le texte d'origine
        $(".jb-stgs-vbp-hdr").text("CONFIRMATION");
        
        //Fait apparaitre les zones masquées
        $(".jb-stgs-vbp-ipt-mx, .jb-stgs-vbp-sub-mx, .jb-stgs-vbp-xpln").removeClass("this_hide");
        
        //Débloque les boutons
        $(".jb-stgs-vbp-ccl, .jb-stgs-vbp-sub").data("lk",0);
    };
    
    
    var _f_ConfimBPdg = function () {
        /*
         * Permet de mettre la fentetre en standby
         */
        //Texte d'attente
        $(".jb-stgs-vbp-hdr").text("PATIENTEZ...");
        
        //Fait dispparaitre les zones inutiles
        $(".jb-stgs-vbp-ipt-mx, .jb-stgs-vbp-sub-mx, .jb-stgs-vbp-xpln").addClass("this_hide");
        
        //Bloque les boutons
        $(".jb-stgs-vbp-ccl, .jb-stgs-vbp-sub").data("lk",1);
    };
    
    
    /*************************** ERRORBOX ****************************/
    
    var _f_HidErrBx = function () {
//    this.HidErrBox = function () {
        //On fait disparaitre la box
        $(".jb-stgs-errbx-mx").addClass("this_hide");
        
        //On vide le texte
        $(".jb-stgs-errbx-txt").text("");
    };
    
    var _f_ShwErrBox = function (m) {
        if ( KgbLib_CheckNullity(m) )
            return;
        
        //On ajoute le texte
        $(".jb-stgs-errbx-txt").html(m);
        
        //On fait apparaitre la box
        $(".jb-stgs-errbx-mx").removeClass("this_hide");
        
    };
    
    
    /*************************************************************************************************************************************************************************/
    /******************************************************************************* LISTENERS SCOPE *************************************************************************/
    /*************************************************************************************************************************************************************************/
    
//    var _Obj = new SETTINGS();
    
    //Initialisation du slider 'gender' selon la valeur d'origine
//    _f_Pfl_InitGdr();
    //Initialisation de la date de naissance
    _f_Pfl_InitBdy();
    
    $(".jb-stgs-lmenu").hover(function(e) {
         _f_LMnHvr(this);
    });
    
    $(".jb-stgs-lmenu").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_LMnAct(this);
    });
    
    $(".jb-stgs-clear-form").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_ClrForm(this);
    });
    
    $(".jb-stgs-back-orign").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_RstForm(this);
    });
    
    $(".jb-pfl-gdr-chs, .jb-pfl-gdr-sldr-ch").click(function(e) {
        Kxlib_StopPropagation(e);
        
        _f_Vw_Pfl_ChgGdr(this);
    });
    
    $(".jb-stgs-submit").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_Submit(this);
    });

    $(".jb-stgs-cty-ipt").keyup(function(e){
        _f_CatchQry(e,this);
    });

    $(".jb-stgs-npwd-ipt").keyup(function(e){
        _f_PwdCatch(e,this);
    });
    
    $(".jb-stgs-vbp-ccl").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_ClzConfBP();
    });
    
    $(".jb-stgs-vbp-ipt").keyup(function(e){
        if ( e.keyCode === 13 ) {
            $(".jb-stgs-vbp-sub").click();
            return false;
        }
    });
    
    $(".jb-stgs-vbp-sub").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_CnfrmBP();
    });
    
    $(".jb-stgs-bdy-m, .jb-stgs-bdy-y").change(function(e){
        _f_CrctDate();
    });
    
    $(".jb-stgs-acc-fld-psd, .jb-stgs-acc-fld-eml").blur(function(){
        _f_OnBlurAct(this);
    });
    
    $(".jb-stgs-errbx-clz").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_HidErrBx();
    });
    
//    $("input[name=sgtgs-del-hikw]:checked",".jb-stgs-del-form").val();
    $("input[name=sgtgs-del-yilv]").change(function(){
        _f_RadioAct(this);
    }); 
    
    $(".jb-stgs-del-fld[data-ft='delcf']").change(function(){
        if ( $(this).is(":checked") ) {
            $(".jb-stgs-del-lgls-ipt").removeClass("red");
        } 
    });
    
}

new SETTINGS();