/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function INS () {
    var gt = this;
    var pgEnv;
    
    /*************************************************************************************************************************************************************************/
    /***************************************************************************** PROCESS SCOPE *****************************************************************************/
    /*************************************************************************************************************************************************************************/
    var _f_Gdf = function() {
        var df = {
            //Le temps d'attente après chaque fin de frappe
            "wtt"           : 200,
            //Le nombre de caractères minimum pour lancer la recherche
            "cysrh_min"     : 3,
            "cty_srh_scp"   : ["smpl","cstm"],
            "rgx_fn"        : /^(?=.*[a-z])[a-z-\+\. ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,25}$/i,
            "rgx_bdate"     : /^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/,
            "bdate_min"     : 12,
            "rgx_gdr"       : /^[m|f]{1}$/i,
            "rgx_psd"       : /^(?=.*[a-z])[\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20}$/i,
            "rgx_eml"       : /^[\w.+-]+@(?:[\w\d-]+\.)+[a-z]{2,6}$/i,
//            "rgx_pwd": /^(?=(.*\d))(?=.*[A-Z])(?=.*[²&<>!.?+*_~µ£^¨°\(\)\[\]\-@#$%¤])[^:;=''/\\]{6,32}$/,
            "rgx_pwd"       : /^(?=(.*\d))(?=.*[a-z])(?=.*[²&<>!\.\?+\*_~µ£^¨°\(\)\[\]\-@#$%:;=''/\\¤]).{6,32}$/i,
            "rgx_pwd_ltr"   : /[a-z]+/i,
            "rgx_pwd_spch"  : /([²&<>!\.\?+\*_~µ£^¨°\(\)\[\]\-@#$%:;=''/\\¤])/g,
//            "rgx_pwd_bspch": /[:;=''/\\¤]+/,
            "rgx_pwd_dig"   : /([0-9])/g,
            "rgx_pwd_case"  : /([A-Z])/g,
            //np => NotPerfect : Si le password a tout pour être excellent mais que les mots ci_desssous s'y trouvent, il ne le sera pas
            "rgx_pwd_np"    : /(toto|azerty|0000|1234|qwerty|letmein|password)/ig,
            "pwd_min"       : 6,
            "pwd_max"       : 32,
            //PassWaitingTime
            "pwt"           : 200
        };
        
        return df;
    };
    
    var _f_Init = function () {
        try {
            
            pgEnv = $.parseJSON($(".jb-tq-pg-env").text());
            if ( KgbLib_CheckNullity(pgEnv) ) {
                return;
            }
            
            if (! ( pgEnv.hasOwnProperty("sector") && !KgbLib_CheckNullity(pgEnv.sector) ) ) {
                return;
            }
            
//            Kxlib_DebugVars([JSON.stringify(pgEnv),pgEnv.sector],true);
//            return;
            
            switch (pgEnv.sector) {
                case "ENTERCZ_DIRECT" :
                case "ENTERCZ_ACTIVE_FB_SSN" :
                        $(".jb-ins-form-prmy-sec[data-scp='waiting-room']").addClass("this_hide");
                        $(".jb-ins-form-prmy-sec[data-scp='form']").removeClass("this_hide");
                        
                    break;
                case "ENTERCZ_PREFORM" :
                case "ENTERCZ_INSAPI_FB" :
//                case "ENTERCZ_ACTIVE_FB_SSN" : //DEV, TEST, DEBUG
                    
                        $(".jb-ins-with-api-bmx ").addClass("this_hdie");
                    
                        var chk = [];
                        //USER_NAME : Le Nom Complet de l'Utilisateur
                        if ( $(".jb-ins-com-elt[data-target='fullname']").val() ) { chk.push($(".jb-ins-com-elt[data-target='fullname']")); }
                        //USER_GENDER : Le Sexe de l'Utilisateur
                        if ( $(".jb-ins-gdr-ipt:checked").length ) { chk.push( $(".jb-ins-gdr-ipt") ); }
                        //USER_BORNDATE
                        var hasbrndate = false;
                        if ( $(".jb-ins-brnd-cache") && $(".jb-ins-brnd-cache").length && $(".jb-ins-brnd-cache").text() && !KgbLib_CheckNullity($.parseJSON($(".jb-ins-brnd-cache").text())) ) {
                            var bd_o = $.parseJSON($(".jb-ins-brnd-cache").text());
                            $(".jb-ins-bd-day").val(bd_o.bd_day);
                            $(".jb-ins-bd-month").val(bd_o.bd_month);
                            $(".jb-ins-bd-year").val(bd_o.bd_year);
                            
                            hasbrndate = true;
                        }
                        
//                        Kxlib_DebugVars([hasbrndate,bd_o.bd_day,bd_o.bd_month,bd_o.bd_year],true);
                        
                        //USER_PSEUDO : Le Pseudo de l'Utilisateur
                        if ( $(".jb-ins-com-elt[data-target='pseudo']").val() ) { chk.push($(".jb-ins-com-elt[data-target='pseudo']")); }
                        //USER_EMAIL : L'Email de l'Utilisateur
                        if ( $(".jb-ins-com-elt[data-target='email']").val() ) { chk.push($(".jb-ins-com-elt[data-target='email']")); }
                        //USER_PASS : Le Mot De Passe de l'Utilisateur
                        if ( $(".jb-ins-com-elt[data-target='pwd']").val() ) { chk.push($(".jb-ins-com-elt[data-target='pwd']")); }

                        if ( chk.length ) {
                            $.each(chk,function(x,e) {
                                if ( $(e).val() ) {
                                    //Permet de lancer la vérification des champs
                                    $(e).blur();
                                    
                                    var fld = $(e).data("target");
                                    switch (fld) {
                                        case "email" :
                                                var refval = $(".jb-ins-com-elt[data-target='email']").val();
                                                $(".jb-ins-com-elt[data-target='email_conf']").val(refval);
                                                setTimeout(function(){
                                                    $(".jb-ins-com-elt[data-target='email_conf']").blur();
                                                },1000);
                                            break;
                                        case "pwd" :
                                                var refval = $(".jb-ins-com-elt[data-target='pwd']").val();
                                                $(".jb-ins-com-elt[data-target='pwd_conf']").val(refval);
                                                setTimeout(function(){
                                                    $(".jb-ins-com-elt[data-target='pwd_conf']").blur();
                                                },1000);
                                            break;
                                    }
                                }
                            });
                        }
                        
                        setTimeout(function(){
                            
                            if ( hasbrndate ) {
                                $(".jb-ins-bd-elt").blur();
                            } 
                            
                            if ( $(".jb-ins-gdr-ipt:checked").length ) {
                                $(".jb-ins-gdr-ipt").blur();
                            }
                            
                            var validated = $(".jb-ins-com-elt:not(.legals).validated");
                            $.each(validated,function(i,e){
                                $(e).closest(".jb-ins-group").addClass("this_hide");
                            });
                            
                            $(".jb-ins-tle").addClass("this_hide");

                            $(".jb-ins-with-api-bmx").addClass("this_hide");
                            $(".jb-ins-semi-form-hdr").removeClass("this_hide");

                            $(".jb-ins-form-prmy-sec[data-scp='waiting-room']").addClass("this_hide");
                            $(".jb-ins-form-prmy-sec[data-scp='form']").removeClass("this_hide");
                            
                        },2500);
                        
                    break;
                default: 
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_GenFocus = function (x) {
//    this.GenFocus = function (x) {
        if ( KgbLib_CheckNullity(x) ){
            return;
        }
        
        var t = $(x).data("target");
        
        if ( KgbLib_CheckNullity(t) ){
            return;
        }
        
        /*
         * vc : (Visible Code) Le code pour le message visible de suite
         * hc : (Hidden Code) Le code pour le message visible après appuis sur "Voir Plus"
         */
        var vc, hc; 
        switch (t) {
            case "fullname": 
                    vc = "ins_infos_fullname";
                    hc = "ins_infos_fullname_more";
                break;
            case "borndate": 
                    vc = "ins_infos_borndate";
                    hc = "ins_infos_borndate_more";
                break;
            case "gender": 
                    vc = "ins_infos_gender";
                    hc = "ins_infos_gender_more";
                break;
            case "pseudo": 
                    vc = "ins_infos_pseudo";
                    hc = "ins_infos_pseudo_more";
                break;
            case "citysrh": 
                    vc = "ins_infos_city";
                    hc = "ins_infos_city_more";
                break;
            case "email": 
                    vc = "ins_infos_mail";
                    hc = "ins_infos_mail_more";
                break;
            case "email_conf": 
                    vc = "ins_infos_mail_conf";
                    hc = "ins_infos_mail_conf_more";
                break;
            case "pwd": 
                    vc = "ins_infos_pwd";
                    hc = "ins_infos_pwd_more";
                break;
            case "pwd_conf": 
                    vc = "ins_infos_pwd_conf";
                    hc = "ins_infos_pwd_conf_more";
                break;
            default:
                    return;
                break;
        }
//        Kxlib_DebugVars([,vc,hc]);
//        Kxlib_DebugVars([xlib_getDolphinsValue(vc),Kxlib_getDolphinsValue(hc)]);
        //On ajoute les messages
        $(".jb-ins-infos-msg").html(Kxlib_getDolphinsValue(vc));
        $(".jb-ins-infos-msg-mr").html(Kxlib_getDolphinsValue(hc));
        
        //On insère la cible sur la zone
        $(".jb-ins-infos-mx").data("target",t);
        
        //On vérifie si le champ admet une erreur
        if ( $(x).data("target") !== "borndate" && ( $(x).data("iv") === 0 ) || $(".jb-ins-bd-mx").data("iv") === 0 ) {
            //On lance le controlleur d'erreur pour faire apparaitre l'erreur 
            var o = {
                iff: true
            };
            _f_ErrOnRow(x,o);
        } 
        
    };
    
    /*
     * ofn : OldFn
     * opsd : OldPsd
     * oeml : OldEmail
     */
    var ofn, opsd, oeml;
    var _f_GenBlur = function (x) {
//    this.GenBlur = function (x) {
//        Kxlib_DebugVars([Blur de l'élément"]);
        try {

            if ( KgbLib_CheckNullity(x) ) {
                return;
            }

            /*
    //        Kxlib_DebugVars([t,$(".jb-ins-infos-mx").data("target")],true);
            //On vérifie si la fenêtre des infos est ouverte ... 
            if ( $(".jb-ins-infos-mx").hasClass("active") ) {
                //... Dans ce cas on vérifie si la zone est activée pour l'élément actuellement en Blur
                if ( t !== $(".jb-ins-infos-mx").data("target") ) {
                    //On réinitialise le message dans la fenetre d'information
                    $(".jb-ins-infos-msg").html(Kxlib_getDolphinsValue("ins_infos_welcome"));
                    $(".jb-ins-infos-msg-mr").html(Kxlib_getDolphinsValue("ins_infos_welcome_more"));
                }
            } else {
                //On réinitialise le message dans la fenetre d'information
                $(".jb-ins-infos-msg").html(Kxlib_getDolphinsValue("ins_infos_welcome"));
                $(".jb-ins-infos-msg-mr").html(Kxlib_getDolphinsValue("ins_infos_welcome_more"));
            }
            //*/

            //On vérifie s'il y a une erreur pour le champ en paramètre
            var r = _f_ErrOnRow(x);
            //Vérifie auprès du serveur si la donnée est valide
            /*
             * Certains champs ont besoin d'être vérifié par le serveur.
             * En effet, certains champs bénéficie d'un caractère d'exclusivité. Cela veut dire qu'il faut que la désignation soit libre.
             * Pour savoir s'il faut envoyer la donnée auprès du server, on recupère le code message envoyée par la fonction de gestion d'erreur.
             * 
             * Dans certains cas comme celui du pseudo, le serveur peut faire une proposition à l'utilisateur.
             * Par exemple, si le pseudo "Dupont" est déjà pris, on peut lui proposer "Dupont91,Dupont_91,DupontFr,Dupont_Fr,Duppont69,Duppont_69".
             * Ces choix découle d'une combinaison du pseudo donné et d'autres éléments comme la date de naissance, le code region; ou le code du pays.
             * Le serveur a préalablement vérifier si ces pseudos étaient disponibles.
             */

            if ( r === 2 ) {
                var scp = $(x).data("target");
                if ( scp === "fullname" ) {
                    _f_ChkAvlbFullname(x);
                } else if ( scp === "pseudo" ) {
                    _f_ChkAvlbPsd(x);
                } else if ( scp === "email" ) {
                    _f_ChkAvlbEmail(x);
                } 
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ErrOnRow = function(x,o) {
        try {
            
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            /*
             * igv : Is Global Validation
             * S'agit-il du cas où on valide d'un coup tous les champs ?
             */
            var igv = (!KgbLib_CheckNullity(o) && !KgbLib_CheckNullity(o.igv)) ? o.igv : null;
            /*
             * iff : Is From Focus
             * S'agit-il du cas où on clique sur un champs qui a une erreur ?
             */
            var iff = (!KgbLib_CheckNullity(o) && !KgbLib_CheckNullity(o.iff)) ? o.iff : null;
            
            var t = $(x).data("target");
            if ( KgbLib_CheckNullity(t) ) {
                return;
            }
            
            var v = $(x).val();
            
            /*
             * rgx => La regex utilisée pour valider le champ
             * iv => IsValid
             * emc => ErrorMessageCode
             * nsv => NeedServerValidation
             * sem => ServerErrorMessage (Le serveur a déjà défini un message d'erreur. On l'utilise donc plutot qu'un code erreur)
             */
            var rgx, iv = true, emc = "", nsv = false, sem = "";
            switch (t) {
                case "fullname": 
                    if (iff && $(x).data("sve") && $(x).data("sve") !== "") {
                        /*
                         * Il s'agit d'un cas où l'erreur a été détectée par le serveur.
                         * On passe et on laisse l'utilisateur revalider avec le dit serveur
                         */
                        //On signale qu'il y a une erreur
                        iv = false;
                        //On signale qu'il existe un sem
                        sem = $(x).data("sve");
                        
                        nsv = true;
                    } else if (!iff && $(x).data("sve") && $(x).data("sve") !== "" && ofn === v) {
                        //On signale qu'il y a une erreur
                        iv = false;
                        //On signale qu'il existe un sem
                        sem = $(x).data("sve");
                        
                        nsv = true;
                    }
                    
                    if (!v.length) {
                        if (igv) {
                            //On lock le champ
                            $(x).data("iv", 0);
                            //On signale qu'il y a une erreur
                            iv = false;
                            //On signale visuellement qu'il y a une erreur
                            $(x).addClass("error_field");
                            //On retire le marqueur de validation visuel
                            _f_HidValidMark(x);
                            //ErrorCode
                            emc = "ins_err_void";
                        } else {
                            //On unlock le champ
                            $(x).data("iv", "");
                            //On signale visuellement qu'il y a une erreur
                            $(x).removeClass("error_field");
                            _f_RstAsdErr();
                        }
                        //On retire le marqueur de validation visuel
                        _f_HidValidMark(x);
                    } else {
                        rgx = _f_Gdf().rgx_fn;
                        if (!rgx.test(v)) {
                            //On lock le champ
                            $(x).data("iv", 0);
                            //On signale qu'il y a une erreur
                            iv = false;
                            //On signale visuellement qu'il y a une erreur
                            $(x).addClass("error_field");
                            //On retire le marqueur de validation visuel
                            _f_HidValidMark(x);
                            
                            //On sélectionne le bon code error pour le champ
                            if (v.length < 2)
                                emc = "ins_err_fn_thin";
                            else if (v.length > 25)
                                emc = "ins_err_fn_ln";
                            else
                                emc = "ins_err_fn_chars";
                        } else {
                            
                            /*
                             //On valide le champ
                             $(x).data("iv",1);
                             //On retire le marqueur d'erreur visuel
                             $(x).removeClass("error_field");
                             //On affiche le marqueur de validation visuel
                             _f_ShwValidMark(x);
                             //*/
                            
                            nsv = true;
                        }
                    }
                    break;
                case "borndate": 
                        var r = _f_ChkBirth();
    //                    Kxlib_DebugVars([,igv]);
                        if (typeof r === "undefined" && !igv) {
                            //On masque le marqueur de validation visuel
                            $(".jb-ins-vald-mark-bd").addClass("this_hide");
                            //On retire le marqueur d'erreur visuel
                            $(".jb-ins-bd-elt").removeClass("error_field");
                            /*
                             * [DEPUIS 03-12-15]
                             *      On change la bordure du champ INPUT
                             */
                            $(".jb-ins-bd-elt").removeClass("validated");
                            //On reinitialise le champ
                            $(".jb-ins-bd-mx").data("iv", "");

                        } else if (typeof r === "undefined" && igv) {
                            //On masque le marqueur de validation visuel
                            $(".jb-ins-vald-mark-bd").addClass("this_hide");
                            //On retire le marqueur d'erreur visuel
                            $(".jb-ins-bd-elt").addClass("error_field");
                            /*
                             * [DEPUIS 03-12-15]
                             *      On change la bordure du champ INPUT
                             */
                            $(".jb-ins-bd-elt").removeClass("validated");
                            //On lock le champ
                            $(".jb-ins-bd-mx").data("iv", 0);

                            //Le code du message d'erreur
                            emc = "ins_err_void";
                            //On signale qu'il y a une erreur
                            iv = false;

                        } else if (r === -1) {
                            //On masque le marqueur de validation visuel
                            $(".jb-ins-vald-mark-bd").addClass("this_hide");
                            /*
                             * [DEPUIS 03-12-15]
                             *      On change la bordure du champ INPUT
                             */
                            $(".jb-ins-bd-elt").removeClass("validated");
                            //On retire le marqueur d'erreur visuel
                            $(".jb-ins-bd-elt").addClass("error_field");
                            //On lock le champ
                            $(".jb-ins-bd-mx").data("iv", 0);

                            //Le code du message d'erreur
                            emc = "ins_err_bd";
                            //On signale qu'il y a une erreur
                            iv = false;

                        } else if (r === -2) {
                            //On masque le marqueur de validation visuel
                            $(".jb-ins-vald-mark-bd").addClass("this_hide");
                            /*
                             * [DEPUIS 03-12-15]
                             *      On change la bordure du champ INPUT
                             */
                            $(".jb-ins-bd-elt").removeClass("validated");
                            //On retire le marqueur d'erreur visuel
                            $(".jb-ins-bd-elt").addClass("error_field");
                            //On lock le champ
                            $(".jb-ins-bd-mx").data("iv", 0);

                            //Le code du message d'erreur
                            emc = "ins_err_bd_tyng";
                            //On signale qu'il y a une erreur
                            iv = false;

                        } else {
                            //On affiche le marqueur de validation visuel
                            $(".jb-ins-vald-mark-bd").removeClass("this_hide");
                            //On retire le marqueur d'erreur visuel
                            $(".jb-ins-bd-elt").removeClass("error_field");
                            /*
                             * [DEPUIS 03-12-15]
                             *      On change la bordure du champ INPUT
                             */
                            $(".jb-ins-bd-elt").addClass("validated");
                            //On valide le champ
                            $(".jb-ins-bd-mx").data("iv", 1);
                        }
                    
                    break;
                case "gender": 
//                    var p = $(".jb-ins-gdr-ipt[name=gender]:checked").val(); //[DEPUIS 28-05-16]
                    var p = $(".jb-ins-gdr-ipt:checked").val();
                    var f_ = ['m', 'f'];
                    if ( $.inArray(p, f_) !== -1) {
                        //On retire visuellement le signalement de l'erreur 
                        $(".jb-ins-gdr-lab").removeClass("red"); 
                        //On affiche le marqueur de validation visuel
                        _f_ShwValidMark(x);
                        //On signale que le champ est valide
                        $(".jb-ins-gdr-mx").data("iv", 1);
                    } else if ((!iff || igv) && $.inArray(p, f_) === -1) {
                        //On signale l'erreur visuellement
                        $(".jb-ins-gdr-lab").addClass("red"); 
                        //On retire le marqueur de validation visuel
                        _f_HidValidMark(x);
                        //On signale que le champ est valide
//                        $(".jb-ins-gdr-mx").data("iv", 1);
                        /*
                         * [DEPUIS 28-05-16]
                         *      On LOCK la zone
                         */
                        $(".jb-ins-gdr-mx").data("iv",0);
                        //Le code du message d'erreur
                        emc = "ins_err_gdr_void";
                        //On signale qu'il y a une erreur
                        iv = false;
                    }
                    break;
                case "pseudo": 
//                Kxlib_DebugVars([(x).data("sve")]);
                    if (iff && $(x).data("sve") && $(x).data("sve") !== "") {
                        /*
                         * Il s'agit d'un cas où l'erreur a été détectée par le serveur.
                         * On passe et on laisse l'utilisateur revalider avec le dit serveur
                         */
                        //On signale qu'il y a une erreur
                        iv = false;
                        //On signale qu'il existe un sem
                        sem = $(x).data("sve");
                        
                        nsv = true;
                    } else if (!iff && $(x).data("sve") && $(x).data("sve") !== "" && opsd === v) {
                        //On signale qu'il y a une erreur
                        iv = false;
                        //On signale qu'il existe un sem
                        sem = $(x).data("sve");
                        
                        nsv = true;
                    }
                    
                    if (!v.length) {
                        if (igv) {
                            //On lock le champ
                            $(x).data("iv", 0);
                            //On signale qu'il y a une erreur
                            iv = false;
                            //On signale visuellement qu'il y a une erreur
                            $(x).addClass("error_field");
                            //On retire le marqueur de validation visuel
                            _f_HidValidMark(x);
                            //ErrorCode
                            emc = "ins_err_void";
                        } else {
                            //On réinit opsd. Cela permet notamment d'effectuer l'action : Pseudo -> Chercher -> Efface -> Pseudo (meme) -> Chercher
                            opsd = "";
                            //On unlock le champ
                            $(x).data("iv", "");
                            //On retire le marqueur d'erreur signalée par le serveur
                            $(x).data("sve", "");
                            //On signale visuellement qu'il y a une erreur
                            $(x).removeClass("error_field");
                            _f_RstAsdErr();
                        }
                        //On retire le marqueur de validation visuel
                        _f_HidValidMark(x);
                    } else {
                        rgx = _f_Gdf().rgx_psd;
                        if (!rgx.test(v)) {
                            //On lock le champ
                            $(x).data("iv", 0);
                            //On signale qu'il y a une erreur
                            iv = false;
                            //On signale visuellement qu'il y a une erreur
                            $(x).addClass("error_field");
                            //On retire le marqueur de validation visuel
                            _f_HidValidMark(x);
                            
                            //On sélectionne le bon code error pour le champ
                            if (v.length < 2)
                                emc = "ins_err_psd_thin";
                            else if (v.length > 20)
                                emc = "ins_err_psd_ln";
                            else
                                emc = "ins_err_psd_chars";
                        } else {
                            /*
                             //On valide le champ
                             $(x).data("iv",1);
                             //On retire le marqueur d'erreur visuel
                             $(x).removeClass("error_field");
                             //On ajoute le marqueur de validation visuel
                             _f_ShwValidMark(x);
                             //*/
                            nsv = true;
                        }
                    }
                    break;
                case "citysrh":
                    //On masque les résultats
//                    _f_RstCitySrhList("smpl");
//                    _f_RstCitySrhList("cstm");

                        /*
                         * [DEPUIS 28-05-17]
                         */
                        if ( KgbLib_CheckNullity($(".jb-ins-city-ipt").data("se")) && igv ) {
                            //On signale qu'il y a une erreur
                            iv = false;
                            //On signale visuellement qu'il y a une erreur
                            $(x).addClass("error_field");
                            //On retire le marqueur de validation visuel
                            _f_HidValidMark(x);
                            emc = "ins_err_void";
                        }
                    break;
                case "email": 
                    rgx = _f_Gdf().rgx_eml;
//                    Kxlib_DebugVars([(x).data("sve")]);
//                    Kxlib_DebugVars([eml,v,sem,oeml === v,rgx.test(v)]);
                    if (iff && $(x).data("sve") && $(x).data("sve") !== "" && oeml === v) {
                        /*
                         * Il s'agit d'un cas où l'erreur a été détectée par le serveur.
                         * On passe et on laisse l'utilisateur revalider avec le dit serveur
                         */
                        //On signale qu'il y a une erreur
                        iv = false;
                        //On signale qu'il existe un sem
                        sem = $(x).data("sve");
                        
                        nsv = true;
                    } else if (!iff && $(x).data("sve") && $(x).data("sve") !== "" && oeml === v) {
                        //On signale qu'il y a une erreur
                        iv = false;
                        //On signale qu'il existe un sem
                        sem = $(x).data("sve");
                        
                        nsv = true;
                    }
                    
                    if (!v.length) {
                        if (igv) {
                            //On lock le champ
                            $(x).data("iv", 0);
                            //On signale qu'il y a une erreur
                            iv = false;
                            //On signale visuellement qu'il y a une erreur
                            $(x).addClass("error_field");
                            //On retire le marqueur de validation visuel
                            _f_HidValidMark(x);
                            //ErrorCode
                            emc = "ins_err_void";
                        } else {
                            //On réinit oeml. Cela permet notamment d'effectuer l'action : Email -> Chercher -> Efface -> Email (meme) -> Chercher
                            oeml = "";
                            //On unlock le champ
                            $(x).data("iv", "");
                            //On retire le marqueur d'erreur signalée par le serveur
                            $(x).data("sve", "");
                            //On signale visuellement qu'il y a une erreur
                            $(x).removeClass("error_field");
                            _f_RstAsdErr();
                        }
                        //On retire le marqueur de validation visuel
                        _f_HidValidMark(x);
                    } else {
                        
                        if (!rgx.test(v)) {
                            //On lock le champ
                            $(x).data("iv", 0);
                            //On signale qu'il y a une erreur
                            iv = false;
                            //On signale visuellement qu'il y a une erreur
                            $(x).addClass("error_field");
                            //On retire le marqueur de validation visuel
                            _f_HidValidMark(x);
                            
                            //On sélectionne le bon code error pour le champ
                            if (v.length > 256)
                                emc = "ins_err_eml_ln";
                            else
                                emc = "ins_err_eml";
                            
                        } else {
//                            //On unlock le champ
//                            $(x).data("iv",1);
//                            //On retire le marqueur d'erreur signalée par le serveur
//                            $(x).data("sve","");
//                            //On retire le marqueur d'erreur visuel
//                            $(x).removeClass("error_field");
                            /*
                             //On vérifie si le champ de confirmation est en mode error
                             var ecf = $(".jb-ins-emlcf-ipt");
                             var ecf_v = $(ecf).val();
                             if ( $(ecf).data("iv") === 0 && ecf_v === v ) {
                             //On valide le champ
                             $(ecf).data("iv",1);
                             //On retire le marqueur d'erreur visuel
                             $(ecf).removeClass("error_field");
                             //On affiche le marqueur de validation visuel
                             _f_ShwValidMark(x);
                             } 
                             //*/
//                            else if ( $(ecf).data("iv") === 0 && ecf_v.length && ecf_v !== v ) {
//                                
//                            }
                            nsv = true;
                            
                        }
                    }
                    break;
                case "email_conf":
                    
                    //On vérifie que l'élément principale existe
                    var mn = $(".jb-ins-eml-ipt");
                    if (! $(mn).length ) {
                        return;
                    }
                    
                    var mn_v = $(mn).val();
                    
                    /*
                     * [DEPUIS 28-05-16]
                     *      Permet la synchronisation des champs depuis qu'on a masque EMAIL_CONF et qu'on a créé un HACK dessus
                     */
                    var emlcf = ( mn_v ) ? mn_v : "";
                    $(".jb-ins-grp-confeml").val(emlcf);
                    v = emlcf;
                    
                    rgx = _f_Gdf().rgx_eml;
                    if (!v.length) {
                        if (igv) {
                            //On lock le champ
                            $(x).data("iv", 0);
                            //On signale qu'il y a une erreur
                            iv = false;
                            //On signale visuellement qu'il y a une erreur
                            $(x).addClass("error_field");
                            //On retire le marqueur de validation visuel
                            _f_HidValidMark(x);
                            //ErrorCode
                            emc = "ins_err_void";
                        } else {
                            //On unlock le champ
                            $(x).data("iv", "");
                            //On signale visuellement qu'il y a une erreur
                            $(x).removeClass("error_field");
                            
                            if (!mn_v.length) {
                                //On unlock le champ
                                $(mn).data("iv", "");
                                //On signale visuellement qu'il y a une erreur
                                $(mn).removeClass("error_field");
                            }
                            
                            _f_RstAsdErr();
                        }
                        //On retire le marqueur de validation visuel
                        _f_HidValidMark(mn);
                    } else {
                        //MasterIsError (too) 
                        var mie = false;
                        if (!mn_v.length) {
                            //On sélectionne le bon code error pour le champ
                            emc = "ins_err_emlcf_mnvoid";
                            //On signale qu'il y a une erreur
                            iv = false; 
                            //On signale l'élément maitre est aussi affecté
                            mie = true;
                        } else if (mn_v !== v) {
                            //On sélectionne le bon code error pour le champ
                            emc = "ins_err_emlcf";
                            //On signale qu'il y a une erreur
                            iv = false; 
                        } else if (mn_v === v && !rgx.test(v) && !rgx.test(mn_v)) {
                            //On sélectionne le bon code error pour le champ
                            emc = "ins_err_eml+cf";
                            //On signale qu'il y a une erreur
                            iv = false;
                            //On signale l'élément maitre est aussi affecté
                            mie = true;
                        } else if (mn_v === v && rgx.test(v) && $(mn).data("sve") && $(mn).data("sve") !== "") {
                            //On sélectionne le bon code error pour le champ
                            emc = "ins_err_eml+cf";
                            //On signale qu'il y a une erreur
                            iv = false;
                            //On signale l'élément maitre est aussi affecté
                            mie = true;
                        } else if (mn_v === v && !rgx.test(v) && rgx.test(mn_v)) {
                            /*
                             * la probabilité que cette erreur apparaisse est presque nulle.
                             * Cependant, je ne veux rien au hasard. 
                             */
                            //On sélectionne le bon code error pour le champ
                            emc = "ins_err_emlcf_uxptd";
                            //On signale qu'il y a une erreur
                            iv = false;
                        } 
                        
                        if (!iv) {
                            //On lock le champ
                            $(x).data("iv", 0);
                            //On signale visuellement qu'il y a une erreur
                            $(x).addClass("error_field");
                            //On retire le marqueur de validation visuel
                            _f_HidValidMark(mn);
                            
                            if (mie) {
                                //On lock le champ
                                $(mn).data("iv", 0);
                                //On signale visuellement qu'il y a une erreur
                                $(mn).addClass("error_field");
                            }
                            
                        } else {
                            //On unlock le champ
                            $(x).data("iv", 1);
                            //On retire le marqueur d'erreur visuel
                            $(x).removeClass("error_field");
                            //On affiche le marqueur de validation visuel
                            _f_ShwValidMark(mn);
                        }
                    }
                    break;
                case "pwd": 
                    if (!v.length) {
                        if (igv) {
                            //On lock le champ
                            $(x).data("iv", 0);
                            //On signale qu'il y a une erreur
                            iv = false;
                            //On signale visuellement qu'il y a une erreur
                            $(x).addClass("error_field");
                            //On retire le marqueur de validation visuel
                            _f_HidValidMark(x);
                            //ErrorCode
                            emc = "ins_err_void";
                        } else {
                            //On unlock le champ
                            $(x).data("iv", "");
                            //On signale visuellement qu'il y a une erreur
                            $(x).removeClass("error_field");
                            _f_RstAsdErr();
                        }
                        //On retire le marqueur de validation visuel
                        _f_HidValidMark(x);
                    } else {
                        _f_PWdLevel(v);
                        rgx = _f_Gdf().rgx_pwd;
                        if (!rgx.test(v)) {
                            //On lock le champ
                            $(x).data("iv", 0);
                            //On signale qu'il y a une erreur
                            iv = false;
                            //On signale visuellement qu'il y a une erreur
                            $(x).addClass("error_field");
                            //On retire le marqueur de validation visuel
                            _f_HidValidMark(x);
                            
                            //On sélectionne le bon code error pour le champ
                            if ( v.length < 6 ) {
                                emc = "ins_err_pwd_thin";
                            } else if (v.length > 32) {
                                emc = "ins_err_pwd_ln";
                            } else if (!_f_Gdf().rgx_pwd_ltr.test(v)) {
//                                emc = "ins_err_pwd_xtra_ltr";
                                
                                var mnm = Kxlib_getDolphinsValue("ins_err_pwd_xtra_ltr");
                                var scdm = Kxlib_getDolphinsValue("ins_err_recall");
                                sem = mnm.concat("<br/><br/>",scdm);
                            } else if (!_f_Gdf().rgx_pwd_dig.test(v)) {
//                                emc = "ins_err_pwd_xtra_dig";
                                
                                var mnm = Kxlib_getDolphinsValue("ins_err_pwd_xtra_dig");
                                var scdm = Kxlib_getDolphinsValue("ins_err_recall");
                                sem = mnm.concat("<br/><br/>",scdm);
                            } else if (!_f_Gdf().rgx_pwd_spch.test(v)){
//                                emc = "ins_err_pwd_xtra_spe";
                                
                                var mnm = Kxlib_getDolphinsValue("ins_err_pwd_xtra_spe");
                                var scdm = Kxlib_getDolphinsValue("ins_err_recall");
                                sem = mnm.concat("<br/><br/>",scdm);
                            } else {
                                emc = "ins_err_pwd";
                            }
                            
                        } else {
                            //On unlock le champ
                            $(x).data("iv", 1);
                            //On retire le marqueur d'erreur visuel
                            $(x).removeClass("error_field");
                            
                            //On vérifie si le champ de confirmation est en mode error
                            var ecf = $(".jb-ins-pwdcf-ipt");
                            var ecf_v = $(ecf).val();
                            if ($(ecf).data("iv") === 0 && ecf_v === v) {
                                //On valide le champ
                                $(ecf).data("iv", 1);
                                //On retire le marqueur d'erreur visuel
                                $(ecf).removeClass("error_field");
                                //On affiche le marqueur de validation visuel
                                _f_ShwValidMark(x);
                            } else if (ecf_v.length && ecf_v !== v) {
                                //On lock le champ
                                $(ecf).data("iv", 0);
                                //On affiche le marqueur d'erreur visuel
                                $(ecf).addClass("error_field");
                                //On affiche le marqueur de validation visuel
                                _f_HidValidMark(x);
                            }
                            
                        }
                    }
                    break;
                case "pwd_conf": 
                    //On vérifie que l'élément principale existe
                    var mn = $(".jb-ins-pwd-ipt");
                    if (!$(mn).length)
                        return;
                    var mn_v = $(mn).val();
                    
                    rgx = _f_Gdf().rgx_pwd;
                    if (!v.length) {
                        if (igv) {
                            //On lock le champ
                            $(x).data("iv", 0);
                            //On signale qu'il y a une erreur
                            iv = false;
                            //On signale visuellement qu'il y a une erreur
                            $(x).addClass("error_field");
                            //On retire le marqueur de validation visuel
                            _f_HidValidMark(x);
                            //ErrorCode
                            emc = "ins_err_void";
                        } else {
                            //On unlock le champ
                            $(x).data("iv", "");
                            //On signale visuellement qu'il y a une erreur
                            $(x).removeClass("error_field");
                            
                            if (!mn_v.length) {
                                //On unlock le champ
                                $(mn).data("iv", "");
                                //On signale visuellement qu'il y a une erreur
                                $(mn).removeClass("error_field");
                            }
                            
                            _f_RstAsdErr();
                        }
                        
                        //On retire le marqueur de validation visuel
                        _f_HidValidMark(mn);
                    } else {
                        //MasterIsError (too) 
                        var mie = false;
                        if (!mn_v.length) {
                            //On sélectionne le bon code error pour le champ
                            emc = "ins_err_pwdcf_mnvoid";
                            //On signale qu'il y a une erreur
                            iv = false; 
                            //On signale l'élément maitre est aussi affecté
                            mie = true;
                        } else if (mn_v !== v) {
                            //On sélectionne le bon code error pour le champ
                            emc = "ins_err_pwdcf";
                            //On signale qu'il y a une erreur
                            iv = false; 
                        } else if (mn_v === v && !rgx.test(v) && !rgx.test(mn_v)) {
                            //On sélectionne le bon code error pour le champ
                            emc = "ins_err_pwd+cf";
                            //On signale qu'il y a une erreur
                            iv = false;
                            //On signale l'élément maitre est aussi affecté
                            mie = true;
                        } else if (mn_v === v && !rgx.test(v) && rgx.test(mn_v)) {
                            /*
                             * la probabilité que cette erreur apparaisse est presque nulle.
                             * Cependant, je ne veux rien au hasard. 
                             */
                            //On sélectionne le bon code error pour le champ
                            emc = "ins_err_pwdcf_uxptd";
                            //On signale qu'il y a une erreur
                            iv = false;
                        } 
                        
                        if (!iv) {
                            //On lock le champ
                            $(x).data("iv", 0);
                            //On signale visuellement qu'il y a une erreur
                            $(x).addClass("error_field");
                            //On retire le marqueur de validation visuel
                            _f_HidValidMark(mn);
                            
                            if (mie) {
                                //On lock le champ
                                $(mn).data("iv", 0);
                                //On signale visuellement qu'il y a une erreur
                                $(mn).addClass("error_field");
                            }
                        } else {
                            //On unlock le champ
                            $(x).data("iv", 1);
                            //On retire le marqueur d'erreur visuel
                            $(x).removeClass("error_field");
                            //On affichee le marqueur de validation visuel
                            _f_ShwValidMark(mn);
                        }
                    }
                    break;
                default:
                    return;
            }
            
//        Kxlib_DebugVars([Nombre d'erreurs total => "+_f_MainLckChk()]);
            
            //On vérifie s'il s'agit d'un cas d'erreur. 
            if (!iv) {
                var em;
                //On calcule le nombre d'erreurs 
                if (_f_MainLckChk() > 1 && !iff) {
//                Kxlib_DebugVars([f_MainLckChk()]);
                    //On affiche le message d'erreur général
                    emc = "ins_err_multifield";
                    em = Kxlib_getDolphinsValue(emc);
                } else if (sem) {
                    em = sem;
                } else {
                    em = Kxlib_getDolphinsValue(emc);
                }
                
//            Kxlib_DebugVars([em],true);
//            Kxlib_DebugVars([mc,sem,Kxlib_getDolphinsValue(emc),em]);

                /*
                 * [DEPUIS 27-05-16]
                 *      On n'affiche le zone d'erreur que si nous ne sommes pas en mode WAINTING_ROOM, autrement dit que le formulaire est visible.
                 */
                if ( $(".jb-ins-form-prmy-sec[data-scp='waiting-room']").hasClass("this_hide") ) {
                    _f_ShwAsdErr(em);
                }
                
                return false;
                
            } else {
//            Kxlib_DebugVars([WHAAATTTT !!! ?"]);
                _f_RstAsdErr();
                
                return ( nsv ) ? 2 : 1;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_ChkAvlbFullname = function (x) {
        try {
            
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
//            Kxlib_DebugVars(["Need Server Validation"],true);
//            return;
            
            var v = $(x).val(), s = $("<span/>"), t = (new Date()).getTime(), scp = $(x).data("target");
            
//            Kxlib_DebugVars([scp,v,opsd],true);
//            return;
            
            //On vérifie s'il y a du changement
            if ( scp === "fullname" && ofn === v ) {
                return;
            } else {
                ofn = v;
            }
            
            //On interroge le serveur
            _f_Srv_InsPullDatas(v, scp, t, s);
            
            //Afficher le spinner
            _f_Spinner(scp, true);
            $(s).on("datasready", function(e, d) {
                if (KgbLib_CheckNullity(d)) {
                    return;
                }
                
//                Kxlib_DebugVars([d,typeof d,d === false],true);
                
                if (d) {
                    //Masquer le spinner
                    _f_Spinner(scp);
                    
                    //On lock le champ
                    $(x).data("iv", 0);
                    //On signale visuellement qu'il y a une erreur
                    $(x).addClass("error_field");
                    //On retire le marqueur de validation visuel
                    _f_HidValidMark(x);
                    
                    var em = Kxlib_getDolphinsValue("ins_err_fn_uvbl");
                    //On signale qu'il s'agit d'une erreur signalée par le serveur
                    //sve : SerVer Error
                    $(x).data("sve", em);
                    
//                    Kxlib_DebugVars([,em],true);
//                    Kxlib_DebugVars([d,em],true);
                    /*
                     * [DEPUIS 27-05-16]
                     *      On n'affiche le zone d'erreur que si nous ne sommes pas en mode WAINTING_ROOM, autrement dit que le formulaire est visible.
                     */
                    if ( $(".jb-ins-form-prmy-sec[data-scp='waiting-room']").hasClass("this_hide") ) {
                        _f_ShwAsdErr(em);
                    }
                    
                } else {
                    //Masquer le spinner
                    _f_Spinner(scp);
                    
                    //On valide le champ
                    $(x).data("iv", 1);
                    //On retire le marqueur d'erreur signalée par le serveur
                    $(x).data("sve", "");
                    //On retire le marqueur d'erreur visuel
                    $(x).removeClass("error_field");
                    //On ajoute le marqueur de validation visuel
                    _f_ShwValidMark(x);
                }
                
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_ChkAvlbPsd = function (x) {
        try {
            
            if (KgbLib_CheckNullity(x)) {
                return;
            }
            
//            Kxlib_DebugVars(["Need Server Validation"],true);
//            return;
            
            var v = $(x).val(), s = $("<span/>"), t = (new Date()).getTime(), scp = $(x).data("target");
            var yr4 = $(".jb-ins-bd-year").val();
            
//            Kxlib_DebugVars([scp,v,opsd],true);
//            return;
            
            //*
            //On vérifie s'il y a du changement
            if (opsd === v) {
                return;
            } else {
                opsd = v;
            }
            //*/
            
            
            if (yr4 && yr4 !== "init") {
                //On récupère certaines données qui serviront pour suggérer un pseudo s'il n'est pas disponible
                var op = {};
                var yr2 = yr4.toString().substring(2, 4);
                var yr3 = yr4.toString().substring(1, 4);
                
                //On récupère l'année de naissance si elle existe
                op = [yr2, yr3, yr4];
                
                _f_Srv_InsPullDatas(v, scp, t, s, op);
            } else {
                _f_Srv_InsPullDatas(v, scp, t, s);
            }
            
            //Afficher le spinner
            _f_Spinner(scp, true);
            $(s).on("datasready", function(e, d) {
                /*
                 * [DEPUIS 28-05-16 16:50]
                 *      Il est possible que SERVER renvoie FALSE losque le pseudo qui est jugé FAUX contient déjà 20 caractères.
                 *      Aussi, il n'arrive pas à proposer de PSEUDO donc il renvoie FALSE.
                 *      A cette date, je n'ai pas détecté de BOGUE de regression suite à cette modification.
                 */
//                if ( KgbLib_CheckNullity(d) || d === false ) { 
                if ( KgbLib_CheckNullity(d) ) {
                    $(s).trigger("operended");
                    return;
                }
                
//                Kxlib_DebugVars([d,typeof d,d === false],true);
                
                //Masquer le spinner
                _f_Spinner(scp);
                
                //On lock le champ
                $(x).data("iv", 0);
                //On signale visuellement qu'il y a une erreur
                $(x).addClass("error_field");
                //On retire le marqueur de validation visuel
                _f_HidValidMark(x);
                
                var em;
                if ( !KgbLib_CheckNullity(d) && Kxlib_ObjectChild_Count(d) ) {
                    //** On constuit le message d'erreur
                    
                    //(1) On récupère la première partie du message
                    em = Kxlib_getDolphinsValue("ins_err_psd_avlb");
                    em = Kxlib_DolphinsReplaceDmd(em, "pseudo", v);
                    em += "<br/><br/>";
                    em += Kxlib_getDolphinsValue("ins_err_psd_avlb_sug");
                    em += "<br/>";
                    
                    //(2) On construit une chaine contenant les propositions
                    $.each(d, function(x, v) {
                        var f__ = "\"<b>" + v + "</b>\"";
                        em += f__ + "<br/>";
                    });
                    
                } else {
                    //(1) On récupère la première partie du message
                    if ( d === -1 ) {
                        em = Kxlib_getDolphinsValue("ins_err_psd_uvbl");
                    } else {
                        em = Kxlib_getDolphinsValue("ins_err_psd_avlb");
                        em = Kxlib_DolphinsReplaceDmd(em, "pseudo", v);
                    }
                    em += "<br/><br/>";
                    em += Kxlib_getDolphinsValue("ins_err_psd_avlb_nosug");
                }
                
                //On signale qu'il s'agit d'une erreur signalée par le serveur
                //sve : SerVer Error
                $(x).data("sve", em);
                
//            Kxlib_DebugVars([,em],true);
//            Kxlib_DebugVars([d,em],true);

                /*
                 * [DEPUIS 27-05-16]
                 *      On n'affiche le zone d'erreur que si nous ne sommes pas en mode WAINTING_ROOM, autrement dit que le formulaire est visible.
                 */
                if ( $(".jb-ins-form-prmy-sec[data-scp='waiting-room']").hasClass("this_hide") ) {
                    _f_ShwAsdErr(em);
                }
                
            });
            
            $(s).on("operended", function(e) {
                //Masquer le spinner
                _f_Spinner(scp);
                
                //On valide le champ
                $(x).data("iv", 1);
                //On retire le marqueur d'erreur signalée par le serveur
                $(x).data("sve", "");
                //On retire le marqueur d'erreur visuel
                $(x).removeClass("error_field");
                //On ajoute le marqueur de validation visuel
                _f_ShwValidMark(x);
                
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_ChkAvlbEmail = function (x) {
        try {
            
            if (KgbLib_CheckNullity(x)) {
                return;
            }
//            Kxlib_DebugVars(["Need Server Validation"],true);
//            return;
            
            var v = $(x).val(), s = $("<span/>"), t = (new Date()).getTime(), scp = $(x).data("target");
            
//            Kxlib_DebugVars([scp,v,opsd],true);
//            return;
            
            //On vérifie s'il y a du changement
            if ( scp === "email" && oeml === v ) {
                return;
            } else {
                oeml = v;
            }
            
            _f_Srv_InsPullDatas(v, scp, t, s);
            
            //Afficher le spinner
            _f_Spinner(scp, true);
            $(s).on("datasready", function(e, d) {
                if ( KgbLib_CheckNullity(d) || d === false ) {
                    $(s).trigger("operended");
                    return;
                }
                
//                Kxlib_DebugVars([d,typeof d,d === false],true);
                
                //Masquer le spinner
                _f_Spinner(scp);
                
                //On lock le champ
                $(x).data("iv", 0);
                //On signale visuellement qu'il y a une erreur
                $(x).addClass("error_field");
                //On retire le marqueur de validation visuel
                _f_HidValidMark(x);
                
                var em;
                if ( d === "__ERR_VOL_DOM" ) {
                    em = Kxlib_getDolphinsValue("ins_err_eml_ban");
                } else if ( d === "__ERR_VOL_DNS" ) {
                    em = Kxlib_getDolphinsValue("ins_err_eml_dns");
                } else {
                    em = Kxlib_getDolphinsValue("ins_err_eml_avlb");
                }
                
                //On signale qu'il s'agit d'une erreur signalée par le serveur
                //sve : SerVer Error
                $(x).data("sve", em);
                
//            Kxlib_DebugVars([,em],true);
//            Kxlib_DebugVars([d,em],true);

                /*
                 * [DEPUIS 27-05-16]
                 *      On n'affiche le zone d'erreur que si nous ne sommes pas en mode WAINTING_ROOM, autrement dit que le formulaire est visible.
                 */
                if ( $(".jb-ins-form-prmy-sec[data-scp='waiting-room']").hasClass("this_hide") ) {
                    _f_ShwAsdErr(em);
                }
                
            });
            
            $(s).on("operended", function(e) {
                
                //Masquer le spinner
                _f_Spinner(scp);
                
                //On unlock le champ
                $(x).data("iv", 1);
                //On retire le marqueur d'erreur signalée par le serveur
                $(x).data("sve", "");
                //On retire le marqueur d'erreur visuel
                $(x).removeClass("error_field");
                
                //On vérifie si le champ de confirmation est en mode error
                var ecf = $(".jb-ins-emlcf-ipt");
                var ecf_v = $(ecf).val();
                if ($(ecf).data("iv") === 0 && ecf_v === v) {
                    //On valide le champ
                    $(ecf).data("iv", 1);
                    //On retire le marqueur d'erreur visuel
                    $(ecf).removeClass("error_field");
                    //On affiche le marqueur de validation visuel
                    _f_ShwValidMark(x);
                } 
                /*
                 //On vérifie si le champ de confirmation est en mode error
                 var ecf = $(".jb-ins-emlcf-ipt");
                 var ecf_v = $(ecf).val();
                 if ( $(ecf).data("iv") === 0 && ecf_v === v ) {
                 //On valide le champ
                 $(ecf).data("iv",1);
                 //On retire le marqueur d'erreur signalée par le serveur
                 $(x).data("sve","");
                 //On retire le marqueur d'erreur visuel
                 $(ecf).removeClass("error_field");
                 //On affiche le marqueur de validation visuel
                 _f_ShwValidMark(x);
                 } 
                 //*/
                
                
                /*
                 * [DEPUIS 27-05-16]
                 *      On n'affiche lance le Blur sur la zone cachée EMAIL_CONF.
                 */
                $(".jb-ins-emlcf-ipt").blur();
                
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /* Validation de la date */
    var _f_ChkBirth = function () {
        var dob_d = $(".jb-ins-bd-day").val();
        var dob_m = $(".jb-ins-bd-month").val();
        var dob_y = $(".jb-ins-bd-year").val();

        if ( KgbLib_CheckNullity(dob_d) | KgbLib_CheckNullity(dob_m) | KgbLib_CheckNullity(dob_y) )
            return;
        if ( dob_d === "init" | dob_m === "init" | dob_y === "init" ) {
            return;
        }

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
        try {
            
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
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

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
        $(".jb-ins-bd-day option").attr('disabled', false); 
//        alert($(".jb-ins-bd-month").val());
//        return;
        switch($(".jb-ins-bd-month").val()){
            case "02":
                var ins_leapYear = new Date($(".jb-ins-bd-year").val(),2,0).getDate();
                if(ins_leapYear === 28){
                    if($(".jb-ins-bd-day").val() === "31" | $(".jb-ins-bd-day").val() === "30" | $(".jb-ins-bd-day").val() === "29"){
                        $(".jb-ins-bd-day").val('28');
                    }
                    $(".jb-ins-bd-day option[value='29']").attr('disabled', true);
                    $(".jb-ins-bd-day option[value='30']").attr('disabled', true);
                    $(".jb-ins-bd-day option[value='31']").attr('disabled', true);
                } else if(ins_leapYear === 29){
                    if($(".jb-ins-bd-day").val() === "31" | $(".jb-ins-bd-day").val() === "30"){
                        $(".jb-ins-bd-day").val('29');
                    }
                    $(".jb-ins-bd-day option[value='30']").attr('disabled', true);
                    $(".jb-ins-bd-day option[value='31']").attr('disabled', true);
                }
                break;
            case "04":
                if($(".jb-ins-bd-day").val() === "31"){
                    $(".jb-ins-bd-day").val('30');
                }
                $(".jb-ins-bd-day option[value='31']").attr('disabled', true);
                break;
            case "06":
                if($(".jb-ins-bd-day").val() === "31"){
                    $(".jb-ins-bd-day").val('30');
                }
                $(".jb-ins-bd-day option[value='31']").attr('disabled', true);
                break;
            case "09":
                if($(".jb-ins-bd-day").val() === "31"){
                    $(".jb-ins-bd-day").val('30');
                }
                $(".jb-ins-bd-day option[value='31']").attr('disabled', true);
                break;
            case "11":
                if($(".jb-ins-bd-day").val() === "31"){
                    $(".jb-ins-bd-day").val('30');
                }
                $(".jb-ins-bd-day option[value='31']").attr('disabled', true);
                break;
        }
    };
    
    var _f_MainLckChk = function () {
        /*
         * Compte le nombdre de champs ayant un lock d'erreur
         */
        var els = $(".jb-ins-com-elt"), cn = 0;
        $.each(els, function(x,e) { 
//            alert($(e).data("iv"));
            if ( $(e).data("iv") === 0 ) {
                ++cn;                                 
            }
        });
        
        
        return cn;
    };
    
    var pwto;
    var _f_PwdCatch = function(x) {
//    this.PwdCatch = function(x) {
        try {
            
            if ( KgbLib_CheckNullity(x) ) {
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
                    iv = _f_PWdLevel(pwqt);
                    
                    if (iv) {
                        //On valide le champ
                        $(x).data("iv", 1);
                        //On retire le marqueur d'erreur visuel
                        $(x).removeClass("error_field");
                        
                        var s_ = $(".jb-ins-pwdcf-ipt").val();
                        if (_f_Gdf().rgx_pwd.test(s_)) {
                            //On ajoute le marqueur de validation visuel
                            _f_ShwValidMark(x);
                        }
                    }
                    
                }, wt);
            } else {
                //On reinitialise la barre de strength
                _f_InsPwdLevel(0);
                //On retire le marqueur de validation visuel
                _f_HidValidMark(x);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_PWdLevel = function (pwqt) {
        try {
            if ( KgbLib_CheckNullity(pwqt) ) {
                return;
            }
            
            var iv = true;
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
                    ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length === 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length === 1 && pwqt.length >= 8 && pwqt.length <= 16 ) ||
                    ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (((pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length === 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1) || (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length === 1) && pwqt.length <= 10 )
            ) {
                /*
                 * (Encore un effort) 
                 * Respecte les conditions mais il n'y a qu'un chiffre et un caractère spéciale et la longueur est > 8
                 * Respecte les conditions avec au moins deux indicateurs sup. pour l'un OU l'autre. Exemple toto1*+ OU toto23* MAIS la longueur <= 10
                 */
                _f_InsPwdLevel(3);
            } else if (
                ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length === 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length === 1 && pwqt.length > 16 ) ||
                ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1 && !(pwqt.match(_f_Gdf().rgx_pwd_case) || []).length && pwqt.length >= 8 ) ||
                ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_case) || []).length > 1 && pwqt.length >= 10 && (pwqt.match(_f_Gdf().rgx_pwd_np) || []).length ) ||
                ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && ( ( (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length === 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1 ) || (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length === 1 ) && pwqt.length > 10 )
            ) {
                /*
                 * (Très bien) 
                 * Respecte les conditions avec au moins deux indicateurs sup. pour l'un OU l'autre. Exemple toto1*+ OU toto23* MAIS la longueur > 10
                 * Respecte les conditions avec au moins deux indicateurs sup. pour chaque. Exemple toto12*+ OU toto23+* ET la longueur >= 10
                 */
                _f_InsPwdLevel(4);
            } else if (
                !(pwqt.match(_f_Gdf().rgx_pwd_np) || []).length &&    
                ( pwqt.length && _f_Gdf().rgx_pwd.test(pwqt) && (pwqt.match(_f_Gdf().rgx_pwd_dig) || []).length > 1 && (pwqt.match(_f_Gdf().rgx_pwd_spch) || []).length > 1 && pwqt.length >= 10 && (pwqt.match(_f_Gdf().rgx_pwd_case) || []).length >= 1 ) //[DEPUIS 23-09-15] @author BOR
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
            } else {
                /*
                 * [DEPUIS 23-09-15] @author BOR
                 *  J'ai ajouté une section ELSE.
                 */
                iv = false;
                //On reinitialise la barre de strength
                _f_InsPwdLevel(0);
            }
            
            return iv;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /****************************************************************************************************************/
    /********************************************* CITY SERACH SCOPE ************************************************/
    
    this.ViewAsideInfos = function () {
        
//        if ( $(".jb-ins-mr-trg").data("used") )
        
//        Kxlib_DebugVars([Click on more"]);
        
        if ( $(".jb-ins-infos-msg-mr").is(":visible") ) {
            //On signalle que la zone n'est plus activée car "more" va être réduit
            $(".jb-ins-infos-mx").removeClass("active");
        } else {
            //On signalle que la zone est activée
            $(".jb-ins-infos-mx").addClass("active");
        }
        
//        var cn = (! cn ) ? 1 : ++cn;
//        $(".jb-ins-mr-trg").data("used",cn);
        
        _f_ViewAsideInfos();
    };
    
    /****************************************************************************************************************/
    /********************************************* CITY SERACH SCOPE ************************************************/
    
    var to;
    var _f_CatchQry = function (x) {
//    this.CatchQry = function (x) {
        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
    
        var wi = _f_Gdf().wtt;
        if ( to ) {
            clearTimeout(to);
        }
        
        //QueryText
        var qt = $(x).val();
        
        //On reset le "SelectedElement" pour que le formulaire ne soit pas validé
        $(".jb-ins-city-ipt").data("se","");
        //On retire le marqueur "valid"
        _f_IsRowVald("ctysrh");
        
//        Kxlib_DebugVars([qt.length >= _f_Gdf().cysrh_min],true);
//        Kxlib_DebugVars([wi,qt,_f_Gdf().cysrh_min],true);
//        return;
        if ( qt.length >= _f_Gdf().cysrh_min ) {
         
            to = setTimeout(function() {
                //On vérifie une seconde fois
                if ( $(x).val().length  < _f_Gdf().cysrh_min )
                    return;
                
                //On affiche le spinner
                var sp = "ctysrh_smpl";
                _f_Spinner(sp,true);
                
                //-- On efface la bonne liste
                //On sélectionne le bon scope
                
//                var list_scp = ( $(".jb-cty-list-mx.this_hide").length > 1 || $(".jb-cty-list-mx.this_hide").data("obj") === "cstm" ) ? "smpl" : "cstm";
                
//                Kxlib_DebugVars([to,$(".jb-cty-list-mx.this_hide").length,$(".jb-cty-list-mx.this_hide").data("obj"),list_scp],true);
//                return;
                
                _f_CtySrhTrgr(qt);
            },wi);
        } else {
            //On retire le spinner
            _f_Spinner("ctysrh",false);
            
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
        if ( KgbLib_CheckNullity(qt) )
            return;
        
        var s = $("<span/>"), t = (new Date()).getTime();
        
//        Kxlib_DebugVars([qt,sp],true);
//        return;
        //On contacte le serveur
        _f_Srv_InsPullDatas(qt,"cty_srh",t,s);
        
        $(s).on("datasready",function(e,d) {
            if ( KgbLib_CheckNullity(d) )
                return;
            
            //On affihe la liste
            _f_ShwCtySrhReslt(qt,d,"smpl");
            
        });
        
        $(s).on("operended",function(e) {
            /*
             * Gère la cas où aucun résultat nous revient
             */
            
            //On masque le spinner
            _f_Spinner("ctysrh_smpl");
       
            //On efface les anciens résultats
            _f_RstCitySrhList("smpl");
            _f_RstCitySrhList("cstm");
        });
        
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
            "cycn" : cycn
        };
        //On contacte le serveur
        _f_Srv_InsPullDatas(qt,"cty_srh_this",t,s,xd);
        
        $(s).on("datasready",function(e,d) {
            if ( KgbLib_CheckNullity(d) )
                return;
            
            //On affiche la liste
            _f_ShwCtySrhReslt(qt,d,"cstm");
            
        });
        
        $(s).on("operended",function(e) {
            /*
             * Gère la cas où aucun résultat nous revient
             */
            
            //On masque le spinner
            _f_Spinner("ctysrh_smpl");
       
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
        var bl = $(b).find(".jb-ins-city-list");
        var r;
        
        if ( $(bl).length ) {
            var e = $(bl).find(".jb-cty-list-elt[data-ci='"+i+"']");
            r = ( $(e).length ) ? true : false;
        } else 
            return;
        
        return r;
        
    };
    
    var _f_CySrhSlct = function (x) {
//    this.CySrhSelect = function (x) {
        /*
         * Gère tous les cas de la sélection de ville.
         */
        if ( KgbLib_CheckNullity(x) ){
            return;
        }
        
        var t = $(x).data("obj");
//        Kxlib_DebugVars([t, _f_Gdf().cty_srh_scp, Array.isArray(_f_Gdf().cty_srh_scp), $.inArray(t,_f_Gdf().cty_srh_scp)],true);
        if ( KgbLib_CheckNullity(t) || $.inArray(t,_f_Gdf().cty_srh_scp) === -1 ){
            return;
        }
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
            _f_Spinner("ctysrh_cstm",true);
            
            //On lance la rcherche
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
        $(".jb-ins-city-ipt").val(se);
        //se : SelectedElement (Permet notamment de valider le champ)
        $(".jb-ins-city-ipt").data("se",$(x).data("ii"));
        
        //On retire visuellement le marqueur d'erreur
        $(".jb-ins-city-ipt").removeClass("error_field");
        
        //On ajoute le marqueur "valid"
        _f_IsRowVald("ctysrh",true);
        
//        alert($(x).data("ii"));
//        alert($(".jb-ins-city-ipt").data("se"));
//        Kxlib_DebugVars([(".jb-ins-city-ipt").data("se")]);
    };
    
    var _f_Submit = function (x) {
//    this.Submit = function () {
        /*
         * Réalise toutes les opérations consécutives à la validation générale du formulaire.
         * La méthode réalise trois principales tâches :
         *  (1) Vérifier que tous les champs sont validées
         *  (2) Switcher vers le module d'attente
         *  (3) Procéder à l'inscription + connexion
         *  (AMELIORATION) Vérifier certaines fonctionnalités et concordance de données au niveau de la base de données. Sinon envoyer vers la maintenance.
         *  (4) Aller vers la page du compte nouvellement créé
         */
        //*********** Vérifier la validité des champs ***************//
        /*
         * La vérifications se fait de la manière suivante :
         *      - On vérifie si les champs ont des données
         *      - On vérifie que les règles pour chaque champ sont respectées
         */
        try {
            
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            /*
             * [DEPUIS 15-09-15] @author BOR
             *      On vérifie si le bouton est bloqué
             */
            if ( !KgbLib_CheckNullity(x) && $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            /*
             * [DEPUIS 27-05-16]
             */
            var a = $(x).data("action");
            if ( KgbLib_CheckNullity(a) ) {
                return;
            }
                
            /*
             * [DEPUIS 27-05-16]
             */
            if ( $.inArray(a,["submit_start","submit_back","submit_finally"]) !== -1 && a !== "submit_back" ) {
                var cne = _f_FnlCheck();        
            
                /*
                 * [DEPUIS 15-09-15] @author BOR
                 *      On vérifie que les champs sont valides
                 * [DEPUIS 19-10-15] @author BOR
                 *      On vérifie que le captcha est valide
                 */
                if ( cne !== 0 | !$(".jb-ins-cgu-ipt").is(":checked") | KgbLib_CheckNullity(grecaptcha.getResponse()) ) {
                    $(x).data("lk",0);
                    return;
                }
            }
            
            
            /*
             * [DEPUIS 27-05-16]
             */
            var a = $(x).data("action");
            switch (a) {
                case "submit_start" :
                        /*
                         * ETAPE :
                         *      On rentre les données dans la zone de confirmation
                         */
                        _f_SubPprConfBx();
                        
                        /*
                         * ETAPE :
                         *      On affiche la zone de confirmation
                         */
                        $(".jb-tqr-ins-last-chk-sprt").removeClass("this_hide");
                        
                        /*
                         * ETAPE :
                         *      On débloque les boutons
                         */
                        $(x).data("lk",0);
                        $(".jb-ins-submit").data("lk",0);
                    return;
                case "submit_back" :
                        /*
                         * ETAPE :
                         *      On masque la zone de confirmation
                         */
                        $(".jb-tqr-ins-last-chk-sprt").addClass("this_hide");
                        
                        /*
                         * ETAPE :
                         *      On affiche TOUS les champs sauf ceux de confirmation
                         */
                        $(".jb-ins-grp-fn, .jb-ins-grp-bdt, .jb-ins-grp-gdr, .jb-ins-grp-cty, .jb-ins-grp-psd, .jb-ins-grp-eml, .jb-ins-grp-pwd").removeClass("this_hide");
                        
                        /*
                         * ETAPE :
                         *      On débloque les boutons
                         */
                        $(x).data("lk",0);
                        $(".jb-ins-submit").data("lk",0);
                    return;
                case "submit_finally" :
                        /*
                         * ETAPE :
                         *      On masque la zone de confirmation
                         */
                        $(".jb-tqr-ins-last-chk-sprt").addClass("this_hide");
                        
                        /*
                         * ETAPE :
                         *      On débloque les boutons
                         */
                        $(".jb-tqr-ins-last-chk-fnl-dc").data("lk",0);
                    break;
                default :
                        $(x).data("lk",0);
                        $(".jb-ins-submit").data("lk",0);
                    return;
            }
            
           /*
            * On affiche le panneau d'attente
            */
            $(".jb-ins-form-wt-pnl").removeClass("this_hide");
            
            /*
             * On fait blur tous les champs
             */
            if ( $(".jb-ins-com-elt:focus").length ) {
                 $.each($(".jb-ins-com-elt"),function(x,e){
                    _f_GenBlur(e);
                });  
            }

            /*
             * On patiente 2 secondes le temps que tous les évents se soient cloturés afin d'être sur que le formulaire est bien validé.
             */
            setTimeout(function(){
                if ( _f_FnlCheck() ) {
                    $(x).data("lk",0);
                    $(".jb-ins-form-wt-pnl").addClass("this_hide");
                    return;
                } else {
                    if ( cne === 0 && $(".jb-ins-cgu-ipt").is(":checked") && !KgbLib_CheckNullity(grecaptcha.getResponse()) ) {
                        
                        //On change de module
                        $(".jb-ins-final-mx").removeClass("this_hide");
                        $(".jb-ins-form-mx").addClass("this_hide");

                        //************* On lance les opérations d'inscription **************
                        var etp_chk = $("<span/>"), etp_ins = $("<span/>");
                        var ufn = $(".jb-ins-com-elt[data-target='fullname']").val();
                        /*
                         * [DEPUIS 29-05-16]
                         */
                        ufn = ufn.trim();
                        //----------
                        var dob_d = $(".jb-ins-bd-day").val();
                        var dob_m = $(".jb-ins-bd-month").val();
                        var dob_y = $(".jb-ins-bd-year").val();
                        var fd = dob_m + "-" + dob_d + "-" + dob_y;
                        var ubd = fd;
//                        var ugdr = $(".jb-ins-gdr-ipt[name=gender]:checked").val();  //[DEPUIS 28-05-16]
                        var ugdr = $(".jb-ins-gdr-ipt:checked").val();
                        var ucy = $(".jb-ins-com-elt[data-target='citysrh']").data("se");
                        var upsd = $(".jb-ins-com-elt[data-target='pseudo']").val();
                        /*
                         * [DEPUIS 29-05-16]
                         */
                        upsd = upsd.trim();
                        var ueml = $(".jb-ins-com-elt[data-target='email']").val();
                        var upwd = $(".jb-ins-com-elt[data-target='pwd']").val();
                        var grr = grecaptcha.getResponse();
                        var entercz = ( pgEnv && pgEnv.sector ) ? pgEnv.sector : ""; 
                        
                        
                        /*
                         * [DEPUIS 28-06-16]
                         */
                        var uage = _f_GetAge(dob_y + "-" + dob_m + "-" + dob_d);
                        var ucy_nm = $(".jb-tqr-ins-last-chk-ipt[data-fld='citysrh']").val();
                        ucy_nm = ucy_nm.split(",")[0];
                        
                        var intro_datas = {
                            "fnm" : ufn,
                            "psd" : upsd,
                            "gdr" : ugdr,
                            "age" : parseInt(uage),
                            "ucy" : ucy_nm
                        };
                        
//                        Kxlib_DebugVars([JSON.stringify(intro_datas)],true);
//                        return;
                        
                        if ( 
                            entercz && $.inArray(entercz,["ENTERCZ_ACTIVE_FB_SSN","ENTERCZ_INSAPI_FB"]) !== -1 
                            && $(".jb-ins-with-api-datas[data-api='fb']").length 
                            && $.parseJSON($(".jb-ins-with-api-datas[data-api='fb']").text())  
                        ) {
                            var ins_wapi_fb = $.parseJSON($(".jb-ins-with-api-datas[data-api='fb']").text());
                            var xtras = {
                                "ins_wapi_fb" : ins_wapi_fb
                            };
                        }

//                        Kxlib_DebugVars([ufn,ubd,ugdr,ucy,upsd,ueml,upwd,grr,entercz,JSON.stringify(xtras)],true);
//                        return;
                        
                        //On lance la verification des données par le serveur
                        _f_Srv_InsFinalOper(ufn, ubd, ugdr, ucy, upsd, ueml, upwd, grr, "INS_CHKFORM", entercz, xtras, etp_chk);

                        $(etp_chk).on("operended", function(e,d) {
                            
                            /*
                             * Gère la réponse du serveur sur le cas de validation des données par le serveur.
                             */
                            if ( KgbLib_CheckNullity(d) ) {
                                Kxlib_AJAX_HandleFailed();
                            }

                            //On masque le spinner
                            $(".jb-ins-fnl-tks-this[data-target='form']").find(".jb-ins-fnl-tks-spnr").addClass("this_hide");

                            //On signale que l'étape a abouti à un résultat
                            $(".jb-ins-fnl-tks-this[data-target='form']").addClass("done");

                            var vmark = $(".jb-ins-fnl-tks-this[data-target='form']").find(".jb-ins-fnl-tks-decs");
                            if ( d === "DONE" ) {
                                
//                                Kxlib_DebugVars(["STAOP : Passer en mode DEBUG"],true);
                                
                                //On signale visuellement que la validation s'est bien déroulée
                                $(vmark).text("OK").removeClass("this_hide");

                                //On affiche les "..." pour l'étape 2
                                $(".jb-ins-fnl-tks-this[data-target='ins_process']").find(".ins-fnl-tks-pndg").removeClass("this_hide");

                                //On lance la phase d'inscription
                                _f_Srv_InsFinalOper(ufn, ubd, ugdr, ucy, upsd, ueml, upwd, grr, "INS_GOINS", entercz, xtras, etp_ins);

                            } else {
                                //**** On signale visuellement que la validation ne s'est bien pas déroulée ****

                                //On construit le bloc qui affiche le message d'erreur
                                var em_f = Kxlib_getDolphinsValue("ins_err_final_badform");
                                var a = $("<li/>").attr({
                                    "id"    : "ins-fail-form",
                                    "class" : "ins-fail-box jb-ins-fail-form"
                                }).html(em_f);
                                $(a).insertAfter(".jb-ins-fnl-tks-this[data-target='form']");

                                $(vmark).text("ECHEC").addClass("fail").removeClass("this_hide");

                                //On change le header
                                _f_FinalChngHdr(false);
                            }
                        });

                        //Retour serveur pour la phase de création effective du compte
                        $(etp_ins).on("operended", function(e, d) {
                            /*
                             * Gère la réponse du serveur sur le cas de la création du compte.
                             */
                            if ( KgbLib_CheckNullity(d) ) {
                                Kxlib_AJAX_HandleFailed();
                            }

                            //On masque le spinner
                            $(".jb-ins-fnl-tks-this[data-target='ins_process']").find(".jb-ins-fnl-tks-spnr").addClass("this_hide");

                            //On signale que l'étape a abouti à un résultat
                            $(".jb-ins-fnl-tks-this[data-target='ins_process']").addClass("done");

                            var vmark = $(".jb-ins-fnl-tks-this[data-target='ins_process']").find(".jb-ins-fnl-tks-decs");
                            if ( d === "DONE" ) {
                                //On signale visuellement que la validation s'est bien déroulée
                                $(vmark).text("OK").removeClass("this_hide");

                                //On affiche le nouveau Header
                                $(".jb-ins-final-ourah").removeClass("this_hide");
                                
                                /*
                                 * [DEPUIS 19-10-15] @author BOR
                                 */
                                $(".jb-ins-fnl-nxt-trg").prop("href","/@"+upsd.toLowerCase());
                                
                                /*
                                //On ajoute le lien dans le bottom
                                var b_ = $("<a/>").attr({
                                    "id"    : "ins-final-next",
                                    "class" : "jb-ins-fnl-nxt-trg",
                                    //Exceptionnellement, on ajoute '@' juste pour indiquer à l'utilisateur qu'il peut tout aussi bien utiliser '@' devant son pseudo dans l'URL
                                    "href"  : "/@" + upsd.toLowerCase()
                                });

                                var b__ = Kxlib_getDolphinsValue("ins_err_final_goown");
                                $(b_).html(b__).appendTo(".jb-ins-fnl-next-mx");
                                //*/
                                
                                //On change le header
                                _f_FinalChngHdr(true);
                                
                                
                               /*
                                * [DEPUIS 28-05-16]
                                * ETAPE :
                                *       On patience de continuer pour la suite.
                                *       Nous prennons ces dispositions pour des raisons esthétiques.
                                */
                                setTimeout(function(){
                                    
                                    /*
                                    * [DEPUIS 28-06-16]
                                    * ETAPE :
                                    *      On affiche la page d'introduction
                                    */
                                   _f_InsIntro_Hdl(null,"intro-good-player",intro_datas);
                                   
                                   
                                   /*
                                    * [DEPUIS 28-05-16]
                                    * ETAPE :
                                    *      On patiente pour laisser le temps à l'INTRO de se lancer avant de continuer
                                    */
                                    setTimeout(function(){
                                        /*
                                         * ETAPE :
                                         *      On affiche la zone qui demande à l'utilisateur ce qu'il souhaite faire à présent
                                         */
                                        $(".jb-ins-fnl-next-mx").removeClass("this_hide");
                                    },1000);
                                },2000);

                            } else {
                                //**** On signale visuellement que la validation ne s'est bien pas déroulée ****
                                
                                //On construit le bloc qui affiche le message d'erreur
                                /*
                                 * [DEPUIS 20-10-15] @author BOR
                                 */
                                var em_cd = ( $.isArray(d) && $.inArray("recaptcha",d) !== -1 ) ? "ins_err_final_seccaptch" : "ins_err_final_badoper";
                                var em_f = Kxlib_getDolphinsValue(em_cd);
                                
                                var a = $("<li/>").attr({
                                    "id"    : "ins-fail-final",
                                    "class" : "ins-fail-box jb-ins-fail-final"
                                }).html(em_f);
                                $(a).insertAfter(".jb-ins-fnl-tks-this[data-target='ins_process']");
                                

                                $(vmark).text("ECHEC").addClass("fail").removeClass("this_hide");

                                //On change le header
                                _f_FinalChngHdr(false);
                            }
                        });
                    }
                }
            },2000);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
     var _f_SubPprConfBx = function () {
        try {
            $.each($(".jb-ins-com-elt"), function(i, e) {
                
                var v = $(e).val(), t = $(e).data("target");
                switch (t) {
                    case "borndate_master" :
                            var brnd = $(".jb-ins-bd-day").find(":selected").text();
                            var brnm = $(".jb-ins-bd-month").find(":selected").text();
                            var brny = $(".jb-ins-bd-year").find(":selected").text();
                            
                            v = brnd.concat(" ",brnm," ",brny);
                            
                            $(".jb-tqr-ins-last-chk-ipt[data-fld='borndate']").val(v);
                        break;
                    case "gender" :
                            var g = $(".jb-ins-gdr-ipt:checked").val();
                            if (! ( g && $.inArray(g,["m","f"]) !== -1 ) ) {
                               return;
                            }
                            v = g;
                            v = ( v === "m" ) ?  "male" : "female";
                            $(".jb-tqr-ins-last-chk-ipt[data-fld='"+t+"']").val(v); 
                        break;
                    case "fullname" :
                    case "pseudo" :
                    case "email" :
                    case "pwd" :
                    case "citysrh" :
                            $(".jb-tqr-ins-last-chk-ipt[data-fld='"+t+"']").val(v);
                        break;
                    default:
                        break;
                }
            });
            
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_FnlCheck = function () {
        //[DEPUIS 15-09-15] @author BOR
        try {
            var cne = 0;
            $.each($(".jb-ins-com-elt"), function(i, e) {
                
                var v = $(e).val(), t = $(e).data("target");
                
                var iv = true;
                switch (t) {
                    case "fullname" :
                    case "pseudo" :
                    case "email" :
                    case "email_conf" :
                    case "pwd" :
                    case "pwd_conf" :
//                        _f_ErrOnRow(x);
                            if ($(e).data("iv") !== 1) {
                                iv = false;
                                ++cne;
    //                            Kxlib_DebugVars([]);
                            }
                        break;
                    case "gender" :
                            /*
                             * [DEPUIS 28-05-16]
                             */
                            var p = $(".jb-ins-gdr-ipt:checked").val();
                            if (! ( p && $.inArray(p,["m","f"]) !== -1 && $(e).data("iv") !== 1 && $(".jb-ins-gdr-mx").data("iv") === 1 ) ) {
                                e = $(".jb-ins-gdr-ipt");
                                iv = false;
                                ++cne;
                            }
                        break;
                    case "borndate" :
                    case "borndate_master" :
                            if ( $(".jb-ins-bd-mx").data("iv") !== 1 ) {
                                iv = false;
                                ++cne;
                                //On prend n'importe lequel des champs. Je prefère prendre l'année
                                e = $(".jb-ins-bd-year");
    //                            Kxlib_DebugVars([]);
                            }
                        break;
                    case "citysrh" :
                            if (!$(".jb-ins-city-ipt").data("se") || $(".jb-ins-city-ipt").data("se") === "") {
                                $(".jb-ins-city-ipt").addClass("error_field");
                                iv = false;
                                ++cne;
    //                            Kxlib_DebugVars([]);
                            }
                        break;
                    case "cgu" :
                            if (cne === 0 && !$(".jb-ins-cgu-ipt").is(":checked")) {
                                var m = Kxlib_getDolphinsValue("ins_err_cgu");
                                alert(m);
                            }
                        break;
                    case "recaptcha" :
                            if ( cne === 0 && KgbLib_CheckNullity(grecaptcha.getResponse()) ) {
                                var m = Kxlib_getDolphinsValue("ins_err_notrbot");
                                alert(m);
                            }
                        break;
                    default:
                            /*
                             * [DEPUIS 30-08-15] @author BOR
                             */
                            var Nty = new Notifyzing();
                            Nty.FromUserAction("ins_err_fnl_dzn_corrupt_fields",null,true);
                            ++cne;
                            /*
                             * TODO : Informer le serveur de l'erreur
                             */
                        break;
                }
//           Kxlib_DebugVars([ne]);
                
                /*
                 * [DEPUIS 28-05-16]
                 *      Ajout de la vérification de la variable iv
                 */
                if (! iv ) {
                    var o = {
                        igv: true
                    };
                    _f_ErrOnRow(e,o);
                }
                
            });
            
            return cne;
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        } 
    };
    
    var _f_SyncConf = function (x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            
            var tgt_n = $(x).data("target"), tgt_f, val;
            switch (tgt_n) {
                case "email" : 
                        tgt_f = $(".jb-ins-emlcf-ipt");
                    break;
                case "pwd" : 
                        tgt_f = $(".jb-ins-pwdcf-ipt");
                    break;
                default :
                    return;
            }
            
            val = ( $(x).val() ) ? $(x).val() : "";
            
            if (! $(tgt_f).length ) {
                return;
            }
            
            $(tgt_f).val(val);
                    
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        } 
    };
    
    
    
    /********************************************************************************************************************/
    /*************************************************** SIGNUP INTRO ***************************************************/
    
    var _f_InsIntro_Hdl = function(x,cz,ds) {
        try {
            if ( !KgbLib_CheckNullity(x) && $(x).data("lk") === 1 ) {
                return;
            }
            
            if ( KgbLib_CheckNullity(cz) ) {
                return;
            }
            
            /*
             * ETAPE :
             *      Dans le cas de "intro-good-player"
             */
            if ( cz === "intro-good-player" && !KgbLib_CheckNullity(ds) && ( !KgbLib_CheckNullity(x) && !$(x).is(".jb-ins-intro-dlgbx-chc[data-sec='intro-good-player']") ) ) {
                return;
            } else {
                $(".jb-ins-intro-sprt").data("uds-stor",JSON.stringify(ds));
            }
            
            switch (cz) {
                case "intro-good-player" :
                        if (! KgbLib_CheckNullity(x) ) {
                            if ( $(x).data("val") === "y" ) {
                                /*
                                 * ETAPE :
                                 *      On bloque le ou les bouton(s).
                                 */
                                $(x).data("lk",1);
                                $(".jb-ins-intro-skip-btn").data("lk",1);
                                
                                var s = $("<span/>");
                                _f_InsIntro_SvAsw("_PFOP_PG_INS_INTRO_GDPLYR","_DEC_YES",s);
                                
                                $(s).on("operended",function(e){
                                    /*
                                     * ETAPE : 
                                     *      On affiche la prochaine fenêtre
                                     */
                                    _f_InsIntro_Io(null,true,"intro-choose-fside");
                                    
                                    /*
                                     * ETAPE :
                                     *      On débloque le ou les bouton(s)
                                     */
                                    $(".jb-ins-intro-skip-btn").data("lk",0);
                                });
                                
//                                $(s).trigger("operended"); // DEV, TEST, DEBUG
                                
                            } else {
                                _f_InsIntro_Io(null);
                            }
                        } else {
                            _f_InsIntro_Io(null,true,cz);
                        }
                    break;
                case "intro-choose-fside" :
                        if ( !KgbLib_CheckNullity(x) ) {
                            
                            /*
                             * ETAPE :
                             *      On bloque le ou les bouton(s)
                             */
                            $(x).data("lk",1);
                            $(".jb-ins-intro-skip-btn").data("lk",1);

                            var dci = ( $(x).data("val") === "dark-side" ) ? "_DEC_CSTM_PG_INS_FSIDE_DARK" : "_DEC_CSTM_PG_INS_FSIDE_LIGHT";

                            var s = $("<span/>");
                            _f_InsIntro_SvAsw("_PFOP_PG_INS_INTRO_FSIDE",dci,s);

                            $(s).on("operended",function(e){
                                /*
                                 * ETAPE :
                                 *      On récupère les données mis en sommeil
                                 */
                                var uds = $(".jb-ins-intro-sprt").data("uds-stor");
                                uds_o = JSON.parse(uds);
                                if ( KgbLib_CheckNullity(uds_o) ) {
                                    return;
                                }
                                uds_o["fside"] = $(x).data("val");
                                
                                /*
                                 * ETAPE : 
                                 *      On affiche la prochaine fenêtre
                                 */
                                _f_InsIntro_Io(null,true,"intro-launch-cinematic",uds_o);

                                /*
                                 * ETAPE :
                                 *      On débloque le ou les bouton(s)
                                 */
                                $(".jb-ins-intro-skip-btn").data("lk",0);
                            });
                            
//                            $(s).trigger("operended"); // DEV, TEST, DEBUG
                                
                        } else {
                            _f_InsIntro_Io(null,true,cz);
                        }
                    break;
                case "intro-launch-cinematic" :
                        _f_InsIntro_Io(null,true,cz,ds);
                    break;
                default :
                    return;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        } 
    };
    
    
    var _f_InsIntro_SvAsw = function(sc,dci,clr_s) {
        try {
            if ( KgbLib_CheckNullity(sc) | KgbLib_CheckNullity(dci)  | KgbLib_CheckNullity(clr_s) ) {
                return;
            }
            
//            Kxlib_DebugVars([sc,dci],true); 
            
            var s = $("<span/>");
                
            var T = new MNFM(); 
            T.SetPrfrcs(sc,dci,s);

            $(s).on("operended",function(e){
                $(clr_s).trigger("operended");
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        } 
    };
    
    var _f_InsIntro_Io = function(x,shw,cz,ds) {
        try {
            
            if ( shw && KgbLib_CheckNullity(cz) ) {
                return;
            }
            
            if ( shw && cz === "launch-cinematic" && KgbLib_CheckNullity(ds) ) {
                return;
            }
            
            if ( !KgbLib_CheckNullity(x) && $(x).data("lk") === 1 ) {
                return;
            }
            $(x).data("lk",1);
            
            if ( shw ) {
                
                /*
                 * ETAPE : 
                 *      La fenetre n'a plus d'OVERFLOW
                 */
                _f_WnOvrl(true);
                
                /*
                 * ETAPE : 
                 *      On affiche le SCREEN BLACK
                 */
                if ( $(".jb-ins-intro-sprt").hasClass("this_hide") ) {
                    $(".jb-ins-intro-sprt").stop(true,true).hide().removeClass("this_hide").fadeIn();
                }
                
                switch (cz) {
                    case "intro-good-player" :
                            $(".jb-ins-intro-sub-zn").fadeOut().addClass("this_hide");
                            
                            $(".jb-ins-intro-sub-zn[data-sec='intro-good-player']").hide().removeClass("this_hide").fadeIn();
                        break;
                    case "intro-choose-fside" :
                            $(".jb-ins-intro-sub-zn").fadeOut().addClass("this_hide");
                            
                            $(".jb-ins-intro-sub-zn[data-sec='intro-choose-fside']").hide().removeClass("this_hide").fadeIn();
                        break;
                    case "intro-launch-cinematic" :
                            $(".jb-ins-intro-sub-zn").fadeOut().addClass("this_hide");
                        
                           /*
                            * ETAPE :
                            *      On renseigne les données CUSTUM
                            */
                            //FULLNAME
                            $(".jb-ins-intro-cstm-datas[data-name='fn']").text(ds.fnm);
                            //PSEUDO
                            $(".jb-ins-intro-cstm-datas[data-name='ps']").text(ds.psd);
                            //AGE
                            $(".jb-ins-intro-cstm-datas[data-name='age']").text(ds.age);
                            //CITY
                            $(".jb-ins-intro-cstm-datas[data-name='ucy']").text(ds.ucy);
                            //GENDER-TRICK
                            if ( ds.gdr === "m" ) {
                                $.each($(".jb-ins-intro-cstm-datas[data-name='gdr-chc-trick']"),function(i,el){
                                    var tx = $(el).data("male");

                                    $(el).text(tx);

                                    $(el).removeAttr("data-fem");
                                });
                            } else {
                                $.each($(".jb-ins-intro-cstm-datas[data-name='gdr-chc-trick']"),function(i,el){
                                    var tx = $(el).data("fem");

                                    $(el).text(tx);

                                    $(el).removeAttr("data-male");
                                });
                            }
                            //LA FOCRCE TRICK
                            if ( ds.fside === "dark-side" ) {
                                var tx = $(".jb-ins-intro-cstm-datas[data-name='la-force-trick']").data("dark");
                                $(".jb-ins-intro-cstm-datas[data-name='la-force-trick']").removeAttr("data-light");
                                $(".jb-ins-intro-cstm-datas[data-name='la-force-trick']").text(tx);

                                $(".jb-ins-intro-cstm-datas[data-name='la-force-trick-story'][data-scp='light-side']").remove();
                            } else {
                               var tx = $(".jb-ins-intro-cstm-datas[data-name='la-force-trick']").data("light");
                                $(".jb-ins-intro-cstm-datas[data-name='la-force-trick']").removeAttr("data-dark");
                                $(".jb-ins-intro-cstm-datas[data-name='la-force-trick']").text(tx);

                                $(".jb-ins-intro-cstm-datas[data-name='la-force-trick-story'][data-scp='dark-side']").remove();
                            }
                            
                            /*
                             * ETAPE :
                             *      On affiche la zone de l'animation
                             */
                            $(".jb-ins-intro-sub-zn[data-sec='intro-launch-cinematic']").hide().removeClass("this_hide").fadeIn();
                            
                            /*
                             * ETAPE : 
                             *      On lance l'animation
                             */
                            $(".jb-ins-intro-par-wpr").addClass("play");
                            
                        break;
                    default :
                        return;
                }
                
                /*
                 * ETAPE : 
                 *      On débloque le bouton SKIP
                 */
                $(".jb-ins-intro-skip-btn").data("lk",0);
                
            } else {
                $(".jb-ins-intro-sprt").stop(true,true).fadeOut().addClass("this_hide");
                
                _f_WnOvrl();
                
                $(".jb-ins-intro-par-wpr").removeClass("play");
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        } 
    };
    
    
    var _f_WnOvrl = function(hd) {
        try {
            if ( hd ) {
                $("html").stop(true,true).css({
                    overflow: "hidden"
                });
            } else {
                $("html").stop(true,true).css({
                    overflow: "auto"
                });
            }
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        } 
    };
    
    
    /*************************************************************************************************************************************************************************/
    /****************************************************************************** SERVER SCOPE *****************************************************************************/
    /*************************************************************************************************************************************************************************/
    
    var _Ax_InsPullDatas = Kxlib_GetAjaxRules("INS_PULLDATAS");
    var _f_Srv_InsPullDatas = function (qt,iscp,t,s,x) {
        if ( KgbLib_CheckNullity(qt) | KgbLib_CheckNullity(iscp) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(s) ) {
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
                    
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
                        switch (d.err) {
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DATAS_MISG" :
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
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
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
            
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
//        Kxlib_DebugVars([nd.t,nd.d,nd.c,nd.p[0]],true);
        
        var toSend = {
            "urqid": _Ax_InsPullDatas.urqid,
            "datas": {
                //La chaine recherchée
                "qt"    : qt,
                //InscriptionSCoPe
                "iqsp"  : iscp,
                "t"     : t,
                "x"     : (! KgbLib_CheckNullity(x) ) ? x : "" 
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_InsPullDatas.url, wcrdtl : _Ax_InsPullDatas.wcrdtl });
        
    };
    
//    var _Ax_InsFnlChkForm = Kxlib_GetAjaxRules("INS_CHKFORM");
//    var _Ax_InsFnlGo = Kxlib_GetAjaxRules("INS_GOINS");
    var _f_Srv_InsFinalOper = function (ufn,ubd,ugdr,ucy,upsd,ueml,upwd,grr,urq,entercz,xtras,s) {
        /*
         * Gère les opérations finales pourle module d'inscription :
         *  (1) Cas de la validation du formulaire. (Sert surtout pour densifier l'opération)
         *  (2) Cas de la création définitive du compte.
         */
        if ( KgbLib_CheckNullity(ufn) | KgbLib_CheckNullity(ubd) 
                | KgbLib_CheckNullity(ugdr) | KgbLib_CheckNullity(ucy) 
                | KgbLib_CheckNullity(upsd) | KgbLib_CheckNullity(ueml) 
                | KgbLib_CheckNullity(upwd) | KgbLib_CheckNullity(grr) 
                | KgbLib_CheckNullity(urq) 
                | KgbLib_CheckNullity(entercz)
                | KgbLib_CheckNullity(s) 
                
        ) {
            return;
        }
    
        var f_ = ["INS_CHKFORM","INS_GOINS"];
        if ( $.inArray(urq,f_) === -1 ) {
            grecaptcha.reset();
            return -1;
        }
        
        var _Ax = Kxlib_GetAjaxRules("INS_FNL");
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    grecaptcha.reset();
                    return;
                }
                
                if (! KgbLib_CheckNullity(d.err) ) {
                    grecaptcha.reset();
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
                        switch (d.err) {
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                            case "__ERR_VOL_DNY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_DATAS_MISG" :
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
                } else if (! KgbLib_CheckNullity(d.return) )  {
                    /*
                     * Données attendues : 
                     *  (1) Données sur le résultats de la recherche
                     *      Il s'agit des données sur les villes composées elles mêmes de la manière suivante :
                     *          - Nom Ville
                     *          - Pays
                     *          - Population
                     *          - Nombre de villes similaire
                     */
                    var rds = [d.return];
                    $(s).trigger("operended",rds);
                    return;
                } else {
                        Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                    return;
                };
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                grecaptcha.reset();
                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            /*
             * [DEPUIS 19-10-15] @author BOR
             */
            grecaptcha.reset();
            //TODO : Renvoyer une erreur au serveur ? Cela pourrait être inutile car si l'erreur est survenue coté serveur, l'équipe technique est déjà peut-être au travail.
            //RAPPEL : Ne pas utiliser cette mainière de faire sur les requetes automatiques. 
            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
//        Kxlib_DebugVars([ufn,ugdr,ucy,upsd,ueml,upwd);
        
        var toSend = {
            "urqid": _Ax.urqid,
            "datas": {
                "urq"       : urq,
                "ubd"       : ubd,
                "ufn"       : ufn,
                "ugdr"      : ugdr,
                "ucy"       : ucy,
                "upsd"      : upsd,
                "ueml"      : ueml,
                "upwd"      : upwd,
                "g-stkey"   : $(".g-recaptcha").data("sitekey"),
                //g-r-r : Google-Recaptcha-Response
                "g-r-r"     : grr,
                /*
                 * [DEPUIS 28-05-16]
                 */
                "entercz"   : entercz,
                "xtras"     : ( xtras ) ? xtras : ""
            }
        };
        
//        Kx_XHR_Send(toSend, "post", _Ax.url, onerror, onsuccess); // [DEPUIS 29-08-15] @BOR
        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax.url, wcrdtl : _Ax.wcrdtl });
        
    };
    
    
    /**************************************************************************************************************************************************************************************************/
    /******************************************************************************************* VIEW SCOPE *******************************************************************************************/
    /**************************************************************************************************************************************************************************************************/
    var _f_Spinner = function (t,shw) {
        /*
         * Permet d'afficher le spinner associer à un élément.
         * CALLER doit envoyer la clible pour identifier le bon spinner.
         */
        if ( KgbLib_CheckNullity(t) ) {
            return;
        }
        
        var b;
        switch (t) {
            case "ctysrh_smpl":
                    if ( shw ) {
                        $(".jb-cty-ipt-spr").show(); 
                    } else {
                        $(".jb-cty-ipt-spr").hide();
                    }
                    return;
                break;
            case "ctysrh_cstm":
                    b = ".jb-cysrh-cstm-spnr-mx"; 
                    var w = $(".jb-cty-cstm-list-hdr").width();
                    $(".jb-cysrh-cstm-spnr-mx").width(w);
                    
                    if ( shw ) {
                        $(".jb-cysrh-cstm-spnr-mx").removeClass("this_hide");
                    } else {
                        $(".jb-cysrh-cstm-spnr-mx").addClass("this_hide");
                    }
                    
                    return;
                break;
            case "fullname":
                    b = ".jb-ins-grp-fn"; 
                break;
            case "pseudo":
                    b = ".jb-ins-grp-psd"; 
                break;
            case "email":
                    b = ".jb-ins-grp-eml"; 
                break;
            default:
                    //Hack
                    return;
                break;
        }
//         Kxlib_DebugVars([t,b,shw],true);
        if ( $(b).find(".spinner").length ) {
            if ( shw )
                $(b).find(".spinner").show();
            else
                $(b).find(".spinner").hide();
        }
        
    };
    
    var _f_IsRowVald = function (scp,isv) {
        if ( KgbLib_CheckNullity(scp) ) {
            return; 
        }
        
        var b;
        switch (scp) {
            case "ctysrh":
                    b = ".jb-ins-grp-cty";
                break;
            default :
                return;
        }
        
        var el = $(b).find(".jb-ins-vald-mark");
        if ( $(el).length ) {
            if ( isv ) {
                $(el).removeClass("this_hide");
                /*
                 * [DEPUIS 03-12-15] 
                 *      On change la couleur de l'INPUT
                 */
                $(b).find(".jb-ins-com-elt").addClass("validated");
            } else {
                $(el).addClass("this_hide");
                /*
                 * [DEPUIS 03-12-15] 
                 *      On change la couleur de l'INPUT
                 */
                $(b).find(".jb-ins-com-elt").removeClass("validated");
            }
        } else {
            return;
        }
        
    };
    
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
        
        bl = $(b).find(".jb-ins-city-list");
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
        var sp = "ctysrh_"+scp;
       _f_Spinner(sp);
        
       //On affiche le bloc
       $(b).removeClass("this_hide");
       
    };
    
    var _f_PprCtySrhReslt = function (qt,d,scp) {
        
        if ( KgbLib_CheckNullity(qt) |  KgbLib_CheckNullity(d) | KgbLib_CheckNullity(scp) ) {
            return; 
        }
        
        var e;
        if ( scp === "smpl" ) {
            /*
             * ci: CheckId
             * ii: ItemId
             */
            var e1 = $("<li>").attr({
                "class": "ins-city-list-row jb-cty-list-elt"
            }).data("ci",d.cynm).data("ii",d.cyid);
            var tle = d.cypop+" "+Kxlib_getDolphinsValue("__COM_LANG_RESID");
            var e2 = $("<a/>").attr({
                "class": "ins-city-list-trg jb-city-list-trg clearfix2",
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
        if ( KgbLib_CheckNullity(e) | KgbLib_CheckNullity(scp) ) {
            return; 
        }
        
        var x = ( scp === "smpl" ) ? $(e).find(".jb-city-list-trg") : $(e);
        
        $(x).click(function(e) {
            Kxlib_PreventDefault(e);
            
            _f_CySrhSlct(this);
        });
//        e = $(e).find().children().on("click",_f_CySrhSlct);
//        alert($(e).find(".jb-city-list-trg").length);
        
        return e;
    };
    
    
    /********************* STD SEARCH LIST **********************/
    var _f_HidCitySrhList = function(scp) {
        
        if ( KgbLib_CheckNullity(scp) || !$.inArray(scp,_f_Gdf().scp) ) {
            return; 
        }
        
        var b = ".jb-cty-list-mx[data-obj='"+scp+"']";
        
        $(b).addClass("this_hide");
    };
    
    var _f_RstCitySrhList = function(scp) {
        /*
         * Masque la liste et la vider.
         */
        if ( KgbLib_CheckNullity(scp) || !$.inArray(scp,_f_Gdf().scp) ) {
            return; 
        }
        
        var b = ".jb-cty-list-mx[data-obj='"+scp+"']";
        
        $(b).addClass("this_hide");
        $(b).find(".jb-cty-list-elt").remove();
    };
    
    /********************* CUSTOM SEARCH LIST **********************/
    
    var _f_ShwCstmCitySrhList = function() {
        $(".jb-cty-cstm-list-mx").removeClass("this_hide");
    };
    
    /********************* ASIDE INFOS WINDOWS **********************/
    var _f_ViewAsideInfos = function (o) {
        
        if ( $(".jb-ins-infos-msg-mr").is(":visible") ) {
            $(".jb-ins-infos-msg-mr").hide({
                "effect": "blind",
                "easing": "swing",
                "direction": "up"
            },1000);
        } else { 
            $(".jb-ins-infos-msg-mr").show({
                "effect": "blind",
                "easing": "swing",
                "direction": "up"
            },1000);
            
            $(".jb-ins-infos-msg-mr").css("display","inline-block");
        }
    };
    
    var _f_RstInfos = function () {
//    this.RstInfos = function () {
        //On réinitialise le message dans la fenetre d'information
        $(".jb-ins-infos-msg").html(Kxlib_getDolphinsValue("ins_infos_welcome"));
        $(".jb-ins-infos-msg-mr").html(Kxlib_getDolphinsValue("ins_infos_welcome_more"));
    };
    
    /********************* ASIDE ERROR WINDOWS **********************/
    var _f_ShwAsdErr = function (m) {
        if ( KgbLib_CheckNullity(m) ) {
           return;
        }
        
        $(".jb-ins-asd-err-msg").html(m);
        $(".jb-ins-asd-err-mx").removeClass("this_hide");
    };
    
    var _f_RstAsdErr = function () {
        $(".jb-ins-asd-err-msg").html("");
        $(".jb-ins-asd-err-mx").addClass("this_hide");
    };
    
    /********************* PASSWORD STRENGTH **********************/
    
    var _f_InsPwdLevel = function(gl) {
        //GivenLevel
        if ( KgbLib_CheckNullity(gl) ) {
            return;
        }
        
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
    
    /********************* OTHERS **********************/
    
    var _f_ShwValidMark = function (x) {
        if ( KgbLib_CheckNullity(x) ) {
            return; 
        }
        
        var par = $(x).closest(".jb-ins-group");
        if ( KgbLib_CheckNullity(par) ) {
            return;
        }        
        
        $(par).find(".jb-ins-vald-mark").removeClass("this_hide");
        
        /*
         * [DEPUIS 03-12-15]
         *      On ajoute une bordure verte pour que ça soit plus visible
         */
        var fld = $(par).data("field");
        switch (fld) {
            case "password" :
                    $("#ins-fld-pwd-cnf").addClass("validated");
                break;
            default :
                break;
        }
        $(par).find(".jb-ins-com-elt").addClass("validated");
        
    };
    
    var _f_HidValidMark = function (x) {
        if ( KgbLib_CheckNullity(x) ) {
            return;
        }
        
        var par = $(x).closest(".jb-ins-group");
        if ( KgbLib_CheckNullity(par) ) {
            return;
        }
        
        $(par).find(".jb-ins-vald-mark").addClass("this_hide");
        
        /*
         * [DEPUIS 03-12-15]
         *      On retire la bordure verte pour que ça soit plus visible
         */
        var fld = $(par).data("field");
        switch (fld) {
            case "password" :
                    $("#ins-fld-pwd-cnf").removeClass("validated");
                break;
            default :
                break;
        }
        $(par).find(".jb-ins-com-elt").removeClass("validated");
        
    };
    
    var _f_FinalChngHdr = function (a) {
        if ( KgbLib_CheckNullity(a) ) {
            return;
        }
        
        if ( a === true ) {
            $(".jb-ins-fnl-spnr").addClass("this_hide");
            $(".jb-ins-final-wait").addClass("this_hide");
            
            var vlm = Kxlib_getDolphinsValue("ins_err_final_goodhdr").toUpperCase();
            $(".jb-ins-final-ourah").text(vlm).removeClass("this_hide");
        } else {
            $(".jb-ins-fnl-spnr").addClass("this_hide");
            $(".jb-ins-final-wait").addClass("this_hide");
            
            var erm = Kxlib_getDolphinsValue("failure").toUpperCase();
            $(".jb-ins-final-ourah").text(erm).addClass("fail").removeClass("this_hide");
        }
    };
    
    /******************************************************************************************************************************************************************************************************************/
    /*************************************************************************************************** INIT SCOPE ***************************************************************************************************/
    /******************************************************************************************************************************************************************************************************************/
    
    _f_Init();
    
    /******************************************************************************************************************************************************************************************************************/
    /************************************************************************************************ LISTENERRS SCOPE ************************************************************************************************/
    /******************************************************************************************************************************************************************************************************************/
    /*
    $(".jb-ins-famous-sprt").click(function(e){
        Kxlib_PreventDefault(e);
        
        $(".jb-ins-famous-sprt").addClass("this_hide");
    });
    
    $(".jb-ins-famous-sprt *").click(function(e){
        Kxlib_StopPropagation(e);
    });
    //*/
    
    $(".jb-ins-no-api-rich-clz, .jb-ins-no-api-intro-txt").off("click").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
        
        if ( $(".jb-ins-no-api-rich-mx").is(":visible") ) {
            $(".jb-ins-no-api-rich-mx").hide({
                "effect"    : "blind",
                "easing"    : "swing",
                "direction" : "up"
            },1000);
//            console.log("Fermer : ",e.target.id);
        } else { 
            $(".jb-ins-no-api-rich-mx").show({
                "effect"    : "blind",
                "easing"    : "swing",
                "direction" : "up"
            },1000);
//            console.log("Ouvrir : ",e.target.id);
            $(".jb-ins-no-api-rich-mx").css("display","inline-block");
        }
    });
                                                                                                                                                                                                                                                
    $(".jb-ins-com-elt").focus(function(){
        _f_GenFocus(this);
    });
    
    $(".jb-ins-com-elt").blur(function(){
        _f_GenBlur(this);
    });
    
    $(".jb-ins-mr-trg").click(function(e){
        Kxlib_PreventDefault(e);
        
//        Kxlib_DebugVars([Traitor"]);
        
//        _Obj.ViewAsideInfos();
    });
    
//    $(".jb-ins-mr-trg").focus(function(){
//        Kxlib_DebugVars([Focus"]);
//    });
    
    $(".jb-ins-fams-trg").click(function(e){
        Kxlib_PreventDefault(e);
        
        $(".jb-ins-famous-sprt").removeClass("this_hide");
    });
    
    $(".jb-ins-mr-rst-trg").click(function(e) {
        Kxlib_PreventDefault(e);
        
        _f_RstInfos();
    });
    
    $(".jb-ins-bd-month").change(function(){
        _f_CrctDate();
    });

    $(".jb-ins-bd-year").change(function(){
        _f_CrctDate();
    });
    
    $(".jb-ins-submit, .jb-tqr-ins-last-chk-fnl-dc").click(function(e) {
        Kxlib_PreventDefault(e);
        
        _f_Submit(this);
    });
    
    /***************** PWD CHECK SCOPE *******************/
    
    $(".jb-ins-pwd-ipt").keyup(function(e){
        _f_PwdCatch(this);
    });
    
    /***************** CITY SEARCH SCOPE *******************/
    
    $(".jb-ins-city-ipt").keyup(function(e){
        _f_CatchQry(this);
    });
    
    $(".jb-city-list-trg").click(function(e) {
        Kxlib_PreventDefault(e);
//        alert("catch");
        _f_CySrhSlct(this);
    });
    
    $(".jb-city-list-trg > *").click(function(e) {
        Kxlib_PreventDefault(e);
        
        _f_CySrhSlct($(this).parent());
    });
    
    /************ NEWSLETTER ****************/
    $(".jb-ins-fams-nwsltr-go").click(function(e){
        Kxlib_PreventDefault(e);
    });
    
    
    $(window).scroll(function(){
        var win_lmt = 340, bx_lmt = 0;
        var zntop = $(".jb-ins-right-box").position().top;
        var zntop_ad = ( $(this).scrollTop() - win_lmt ) + bx_lmt;
        
//        Kxlib_DebugVars(["SCROLL_TOP => ",$(this).scrollTop(),"WIN_LMT => ",win_lmt,"ZNTOP_ADD => ",zntop_ad,"BX_LMT => ",bx_lmt,"ZNTOP => ",zntop]);

        if ( $(this).scrollTop() >= win_lmt ) {
            $(".jb-ins-right-box").css({
                top : zntop_ad
            });
        } else {
            $(".jb-ins-right-box").css({
                top : bx_lmt
            });
        }
        
    });
    
    /*************************************/
    
    $(".jb-ins-eml-ipt, .jb-ins-pwd-ipt").keyup(function(){
        _f_SyncConf(this);
    });
    
    $(".jb-ins-intro-skip-btn").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_InsIntro_Io(this);
    });
    
    $(".jb-ins-intro-dlgbx-chc[data-sec='intro-good-player']").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_InsIntro_Hdl(this,"intro-good-player");
    });
    $(".jb-ins-intro-dlgbx-chc[data-sec='intro-choose-fside']").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_InsIntro_Hdl(this,"intro-choose-fside");
    });
    
}

function Ins_Receiver() {
    
    var _Obj = new INS();
    
    this.Routeur = function (x) {
        if ( KgbLib_CheckNullity(x) ) { return; }
        
//        Kxlib_DebugVars([Routeur"]);
        _Obj.ViewAsideInfos();
    };
}

new INS();

/*
 * [NOTE 13-12-14] @author
 * Permet de régler le "bogue" qui fait que la grande majorité des gens n'arrivait pas à créer un compte car les champs pré-remplis n'étaient pas validés.
 * Plutot que modifier le système de valdation, je préfère lancer une opération de blur() qui devrait avoir le même résultat sans changer une ligne de code et ainsi réduire la probabilité de bogue.
 */
$(document).ready(function(){
    var chk = [];
    if ( $(".jb-ins-com-elt[data-target='fullname']").val() ) { chk.push($(".jb-ins-com-elt[data-target='fullname']")); }
    if ( $(".jb-ins-com-elt[data-target='pseudo']").val() ) { chk.push($(".jb-ins-com-elt[data-target='pseudo']")); }
    if ( $(".jb-ins-com-elt[data-target='email']").val() ) { chk.push($(".jb-ins-com-elt[data-target='email']")); }
    if ( $(".jb-ins-com-elt[data-target='pwd']").val() ) { chk.push($(".jb-ins-com-elt[data-target='pwd']")); }
    
    if ( chk.length ) {
        $.each(chk,function(x,e) {
            if ( $(e).val() ) {
                //Permet de lancer la vérification des champs
                $(e).blur();
            }
        });
    }
}); 