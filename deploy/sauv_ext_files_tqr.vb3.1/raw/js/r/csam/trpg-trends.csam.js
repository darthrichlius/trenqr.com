/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function TrendEditHandle () {
    var gt = this;
    var _mxScrn = 1072;
//    this.maxScreen = 1072;
//    this.uaction;
    
    /*******************************************************/
    //La référence objet utilisé pour récupérer les données SETTINGS auprès du serveur
    //Elle nous servira pour les récupérer ulterieurement
    var _o_trhdr_edit;
//    this.o_trhdr_edit;
    
    //Utiliser pour savoir si l'utilisateur a confirmer le changement de Participation
    var _hasConfPart = false;
//    this.hasConfPart = false;
    
    var _f_NwEdit = function (s,v) {
//    this.HandeNewEdit = function (s,v) {
        try {
            if ( KgbLib_CheckNullity(s) | KgbLib_CheckNullity(v) ) {
                return;
            }
         
            /*
             * [DEPUIS 12-07-15] @BOR
             * On vérifie si le formulaire est ENABLED
             */
            if ( $(".jb-trpg-ed-ver-ovly-mx").data("disabled") === true ) {
//                Kxlib_DebugVars([SUBMIT : FORM is DISABLED !"]);
                return;
            }
                    
//        alert(_o_trhdr_edit.TrSrvSetgs.desc);
//        alert(_o_trhdr_edit);
            //On récupère les données provenant du serveur pour comparaison
            //Cela n'est possible parce qu'à ce stade, les fonction sont redevenues synchrone
            //var d = _o_trhdr_edit.TrSrvSetgs;
            
            //Création de la variable de données (provenant d'un formulaire EDIT)
            //layer 1 : t = title, d = desc, c = catg, p = part, g = grat
            
            var tl = $(s).find(".jb-trpg-input-tle").val();
            tl = $("<div/>").text(tl).text();
            var ds = $(s).find(".jb-trpg-input-desc").val();
            ds = $("<div/>").text(ds).text();
            
            tl = (typeof tl === "undefined") ? "" : tl; 
            ds = (typeof ds === "undefined") ? "" : ds; 
            
            var d = {
                "t": tl,
                "d": ds,
//            "c": $(s).find(".jb-trpg-input-cat").find("option:selected").val(),
                //0: la valeur (le code); 1: le texte vu par l'utilisateur
                "p": [
                    $(s).find(".jb-trpg-input-part").find("option:selected").val(),
                    $(s).find(".jb-trpg-input-part").find("option:selected").text()
                ]
//            "g": $(s).find(".jsbind-trpg_input_grat").find("option:selected").val()
            };
//        alert(d.p[1]);
//        Kxlib_DebugVars([JSON.stringify(d)],true);
//        return;
            //Vérification de sécurité sur les formulaires
            var r = _f_SecureFields(s, d);
//        alert(typeof r);
            if (!r) {
                return;
            }
            
//        _f_SecureFields(s,d);
//        alert(Kxlib_Strip_Specials(r.title));
            //var t = d.title;
//        d.title = t.replace(/[<]+/g, '&lt;').replace(/[>]+/g, '&gt;');
            //d.title = Kxlib_EscapeHTMLEntity(t);
//        if ( t.match(/(<(([^>]+>)|(\?php)))/ig,"") ) {
//            alert(d.title);
//        }
//        alert(d.title);
//        return;
            
            //On vérifie si l'utilisateur a changé de mode de Participation 
            //On récupère les données originelles provenant du serveur pour comparaison
            //Cela n'est possible parce qu'à ce stade, les fonctions sont redevenues synchrones
            //sd = server datas
            var sd = _o_trhdr_edit.TrSrvSetgs();
            if ((sd.p[0] !== d.p[0]) && !_hasConfPart) {
                //ps = Paretnt Selector
                var ps = $(s).data("par");
//            alert(ps);
                var dl = (d.p[0].toString().toUpperCase() === "_NTR_PART_PUB") ? "trpg_conf_part_pub" : "trpg_conf_part_pri";
//            alert(dl);
                _f_CnfrmPartChc([ps], dl);
                return;
            }
            //On remet à false pour garantir la fiabilité pour une utilisation ulterieure
            _hasConfPart = false;
            /*************/ 
            var pp = Kxlib_GetTrendPropIfExist();
            
            if (KgbLib_CheckNullity(pp)) {
                //TODO: Averrtir l'utilisateur qu'il y a un problème technique avec la page.
                //TODO : Prévenir le serveur. Cette erreur a une probabilité faible d'arriver. Cela peut être causé par une modificationde la page par CU
                return;
            }
            
            var i = pp.trid;
            
            
//        var i = Kxlib_getDolphinsValue("trid");
            //Envoi des données
            _f_Srv_SvNwEdit(i, d);
            
            //On ferme le bon formulaire
            switch (v) {
//            case "aside":
//                _f_ClzEditInTrpg();
//            break;
                case "ovly":
                        _f_ClzEditInTrpg_Ovly();
                    break;
                default :
                    return;
            }
            
            //On affiche la notification
            
            //On vide le formulaire
            Kxlib_ResetForm(s);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_CnfrmPartChc = function(v,dl) {
//    this.ConfirmPartChoice = function(v,dl) {
//        
        if ( !KgbLib_CheckNullity(v) && v.length === 1 && !KgbLib_CheckNullity(dl) ) {
            
            var s = Kxlib_ValidIdSel(v[0]), m = Kxlib_getDolphinsValue(dl);
//            alert(m);
            
            //Insertion du message
            $(s).find(".jb-trpg-c-p-d-m").html(m);
            //Afficher la fenetre
            $(s).find(".trpg-conf-part-max").removeClass("this_hide");
        } else {
            //v[1] contient le type d'action : c = cancel; s = save
//            alert(v[1]);
            var s = Kxlib_ValidIdSel(v[0]);
            switch (v[1]) {
                case "c":
                        $(s).find(".trpg-conf-part-max").addClass("this_hide");
                        return false;
                    break;
                case "s":
                        $(s).find(".trpg-conf-part-max").addClass("this_hide");
                        _hasConfPart = true;
                    break;
            }
        }
    };
    
    
    /********************* END SETTINGS ********************/
    
    
    /********************* START CREATE NEW TREND ********************/
    
    
    var _f_NwCrt = function (s,v) {
//    this.HandeNewCreate = function (s,v) {

//        alert(_o_trhdr_edit.TrSrvSetgs.desc);
//        alert(_o_trhdr_edit);
        //On récupère les données provenant du serveur pour comparaison
        //Cela n'est possible parce qu'à ce stade, les fonction sont redevenues synchrone
        //var d = _o_trhdr_edit.TrSrvSetgs;
        
        //Création de la variable de données (provenant d'un formulaire EDIT)
        //layer 1 : t = title, d = desc, c = catg, p = part, g = grat
        var d = {
            "t": $(s).find(".jsbind-trpg_input_title").val(),
            "d": $(s).find(".jsbind-trpg_input_desc").val(),
            "c": $(s).find(".jsbind-trpg_input_cat").find("option:selected").val(),
            //0: la valeur (le code); 1: le texte vu par l'utilisateur
            "p": [
                $(s).find(".jsbind-trpg_input_part").find("option:selected").val(),
                $(s).find(".jsbind-trpg_input_part").find("option:selected").text()
            ],
            "g": $(s).find(".jsbind-trpg_input_grat").find("option:selected").val()
        };
//        alert(d.p[1]);
        //Vérification de sécurité sur les formulaires
        var r = _f_SecureFields(s,d);
//        alert(typeof r);
        if ( !r ) { return; }
        
//        _f_SecureFields(s,d);
//        alert(Kxlib_Strip_Specials(r.title));
          //var t = d.title;
//        d.title = t.replace(/[<]+/g, '&lt;').replace(/[>]+/g, '&gt;');
        //d.title = Kxlib_EscapeHTMLEntity(t);
//        if ( t.match(/(<(([^>]+>)|(\?php)))/ig,"") ) {
//            alert(d.title);
//        }
//        alert(d.title);
//        return;
        /*************/ 
        //Envoi des données
        var i = Kxlib_getDolphinsValue("trid");
//        alert("AYEM => "+d.title);
        gt.Srv_SaveNewTrend(r);
        
        //On ferme le bon formulaire
        switch (v) {
            case "aside":
                _f_ClzCrtInTrpg();
            break;
            case "ovly":
                _f_ClzCrtInTrpg_Ovly();
            break;
        }
        
        //On vide le formulaire
        Kxlib_ResetForm(s);
        
    };
    
    /********************* END CREATE NEW TREND ********************/
    
    
    var _f_Basics = function (x) {
//    this.AcquireBasics = function (th) {
        $tarsel = $(x);
        var ua;
        if( KgbLib_CheckNullity($tarsel.data("action")) ) {
            //L'erreur devra etre encoyé au server dans la version production
//            Kxlib_DebugVars([Error : Can't get access to uaction"]);
            return;
        } else ua = $tarsel.data("action");

        return ua;
    };
    
    //STAY PUBLIC
    this.CheckOperation = function (x) {
        try {
            
            if (KgbLib_CheckNullity(x)) {
                return;
            }
            
            //On vérifie si on est en mode max_size_screen
            var sz = parseInt($("#p-l-c-main").width());
            
            var ua = _f_Basics(x);
//        Kxlib_DebugVars(["SIZE => "+sz+"; MAX_SCREEN => "+_mxScrn,"USER_ACTION => ",ua],true);
            switch (ua.toLowerCase()) {
                case "op_trpg_edit":
                    _f_ShwEditInTrpg_Ovly();
                    /*
                     if ( sz < _mxScrn ) 
                     {
                     _f_ShwEditInTrpg_Ovly();
                     }
                     else 
                     {
                     _f_ShwEditInTrpg();
                     }
                     //*/    
                    break;
                case "op_trpg_cr":
                    if (sz < _mxScrn) { 
                        _f_ShwCrtInTrpg_Ovly();
                    } else {
                        _f_ShwCrtInTrpg();
                    }
                    break;
                default :
                    //TODO : Incoherence, envoyer au serveur
                    break;
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /******************************** START CONX **********************************/
    //Debut de la construction de TRPG_CONX 31-07-14 
    
    var _f_CntdAct = function (x) {
//    this.CntdAction = function (x) {
        
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
        
            var a = $(x).data("action");
            switch (a) {
                case "co": 
                        _f_GetCo(x);
                    break;
                case "disco": 
                        _f_Disco(x);
                    break;
            }
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            //TODO : Envoyer l'erreur au serveur
            return;
        }

    };
    
    var _f_GetCo = function (x) {
//    this.GetConnected = function (x) {
        /* Permet de créer une connexion entre l'utilisaeur et la Tendance */
        
        try {
           /*
            * On s'assure que le snitcher est bien présent. 
            * Si ce n'est pas le cas, il n'existe que deux cas possibles :
            *  (1) Il s'agit d'une erreur de construction
            *  (2) L'uilisateur l'a retiré sciemment
            * La probabilité que cela arrive est faible. Aussi, on ne n'indique rien.
            */
            if ( !$(".jb-tao-sn").length ) {
                return;
            }

            //On vérifie qu'une action n'est pas déjà en cours
            //isl : IsLock
            if ( $(".jb-tao-sn").data("isl") === 1 ) {
    //            Kxlib_DebugVars([locked"]);
                return;
            }

            var th = this;
        
            //On lock les opérations de connexion tant qu'on a pas de retour
            $(".jb-tao-sn").data("isl",1);
            
            var s = $("<span/>"), i = Kxlib_GetCurUserPropIfExist().ueid, t = Kxlib_GetTrendPropIfExist().trid;
//            Kxlib_DebugVars([i,t],true);
//            return;
            //sra : StaRtAbo
            _f_Srv_TryAboOper(i,t,"sra",s);
            
            $(".jb-trpg_abo_ldg").removeClass('this_hide');
            $(s).on("operended", function(e,d) {
                //On masque le loader
                $(".jb-trpg_abo_ldg").addClass('this_hide');
                
                //On fait apparaitre le message de notification
                var Nty = new Notifyzing ();
                Nty.FromUserAction("ua_trpg_abo");
                
                //Faire apparaitre l'overlay qui informe qu'une redirection encours
                if ( $(".jb-pg-sts").length ) {
                    $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Redir")+"...");
                    $(".jb-pg-sts").removeClass("this_hide");
                }
                
                //On change le bouton d'Action
                var a = $(x).data("action");
                _f_SwCntdAct(a);
                
                //On DElock les opérations de connexion tant qu'on a pas de retour
                /*
                 * [06-12-14] @author L.C.
                 * On ne unlock plus pour éviter que l'utilisateur se déconnecter avant qu'on est lancer la redirection.
                 */
//                $(".jb-tao-sn").data("isl",0);
                setTimeout(function(){
                    window.location.reload();
                },2000);
            });
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            //TODO : Envoyer l'erreur au serveur
            return;
        }
        
    };
    /*
    this.Ajax_TryGetConnected = Kxlib_GetAjaxRules("TRPG_TRY_GET_CONNECTED");
    this.Srv_TryGetConnected = function(i,t,f) {
        
        if ( KgbLib_CheckNullity(i) || KgbLib_CheckNullity(t) || KgbLib_CheckNullity(f) ) return; 
        
        var th = this;
        var onsuccess = function (datas) {
            
            try {
                if (!KgbLib_CheckNullity(datas)) {
//                    alert("CHAINE JSON AVANT PARSE" + datas);
                    datas = JSON.parse(datas);
                    
                    if (!KgbLib_CheckNullity(datas.err)) {
                        $(f).trigger("knwerror", datas.err);
                        return; 
                    } else if (!KgbLib_CheckNullity(datas.return)) {
                        
                        $(f).trigger("operended");
                    } 
                }
                
                $(f).trigger("unknwerr");
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            }
        };

        var onerror = function(a,b,c) {
            $(f).trigger("unknwerr");
        };
        
        //Pour déterminer qui est le propriétaire on regardera dans la SESSION en cours
        var toSend = {
            "urqid": th.Ajax_TryGetConnected.urqid,
            "datas": {
                "i": i,
                "t": t
            }
        };

        Kx_XHR_Send(toSend, "post", this.Ajax_TryGetConnected.url, onerror, onsuccess);
    };
    //*/
    var _f_SwCntdAct = function (c) {
//    this.SwitchCntdAction = function (c) {
        try {
            //Si l'utilisateur souhaite changer le bouton action actuel pour un autre indiqué de manière précise.
            if (! KgbLib_CheckNullity(c) ) {
                
                c = c.toLowerCase();
                
                switch (c) {
                    case "co" :
                        SwiToActCntd();
                        break;
                    case "disco" : 
                        _f_SwToActDisCo();
                        break;
                }
            }
            
            //Sinon on prend celui afficher et on switch
            if ( $(".jb-trpg_getctd_btn").hasClass("this_hide") ) {
                $(".jb-trpg_getctd_btn").removeClass("this_hide");
                $(".jb-trpg_cntd_bdg").addClass("this_hide");
            } else {
                $(".jb-trpg_getctd_btn").addClass("this_hide");
                $(".jb-trpg_cntd_bdg").removeClass("this_hide");
            }
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            //TODO : Envoyer l'erreur au serveur
            return;
        }

    };
    
    /***************/
    
    
    var _f_Disco = function (x) {
//    this.Disconnect = function (x) {
        /* Permet de briser une connexion entre l'utilisaeur et la Tendance si elle existe */
        
        try {
           /*
            * On s'assure que le snitcher est bien présent. 
            * Si ce n'est pas le cas, il n'existe que deux cas possibles :
            *  (1) Il s'agit d'une erreur de construction
            *  (2) L'uilisateur l'a retiré sciemment
            * La probabilité que cela arrive est faible. Aussi, on ne n'indique rien.
            */
           if ( !$(".jb-tao-sn").length ) {
               return;
           }

           //On vérifie qu'une action n'est pas déjà en cours
           //isl : IsLock
           if ( $(".jb-tao-sn").data("isl") === 1 ) {
   //            Kxlib_DebugVars([locked"]);
               return;
           }
        
            //On lock les opérations de connexion tant qu'on a pas de retour
            $(".jb-tao-sn").data("isl",1);
            
            var s = $("<span/>"), i = Kxlib_GetCurUserPropIfExist().ueid, t = Kxlib_GetTrendPropIfExist().trid;
//            Kxlib_DebugVars([i,t],true);
//            return;
            //sta : STopAbo
            _f_Srv_TryAboOper(i,t,'sta',s);
            
            $(".jb-trpg_abo_ldg").removeClass('this_hide');
            $(s).on("operended", function(e,d) {
                //On masque le loader
                $(".jb-trpg_abo_ldg").addClass('this_hide');
                
                //On fait apparaitre le message de notification
                var Nty = new Notifyzing ();
                Nty.FromUserAction("ua_trpg_disabo");
                
                //Faire apparaitre l'overlay qui informe qu'une redirection encours
                if ( $(".jb-pg-sts").length ) {
                    $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Redir")+"...");
                    $(".jb-pg-sts").removeClass("this_hide");
                }
                
                //On change le bouton d'Action
                var a = $(x).data("action");
                _f_SwCntdAct(a);
                
                //On DElock les opérations de connexion tant qu'on a pas de retour
                /*
                 * [06-12-14] @author L.C.
                 * On ne unlock plus pour éviter que l'utilisateur se déconnecter avant qu'on est lancer la redirection.
                 */
//                $(".jb-tao-sn").data("isl",0);
                setTimeout(function(){
                    window.location.reload();
                },2000);
                
                /*
                //On change le bouton d'Action
                var a = $(x).data("action");
                _f_SwCntdAct(a);
                
                //On DElock les opérations de connexion tant qu'on a pas de retour
                $(".jb-tao-sn").data("isl",0);
                //*/
            });
            
        } catch (ex) {
//            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
            //TODO : Envoyer l'erreur au serveur
            return;
        }
    };
    
    
    /********************************** END CONNECTION *****************************************/
    
    /********************************************************************************************************************************************************************/
    /**************************************************************************** PROCESS SCOPE *************************************************************************/
    /********************************************************************************************************************************************************************/
    var _f_Gdf = function() {
        var df = {
            "rgx_tle": /(?:(?=.*[a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]*).(?![\s]{5,})){20,}/i,
//            "rgx_tle": /(?=.*[a-z])(?:.*[a-zA-Z\dÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]){20,}/i,
//            "rgx_tle": /^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{20,100}$/i,
            "tle_min": 20,
            "tle_max": 100,
            "rgx_desc": /(?:(?=.*[a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]*).(?![\s]{5,})){20,}/i,
//            "rgx_desc": /(?=.*[a-z])(?:.*[a-zA-Z\dÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]){20,}/i,
//            "rgx_desc": /^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{20,200}$/i,
            "desc_min": 20,
            "desc_max": 200,
            "part": ["_NTR_PART_PUB","_NTR_PART_PRI"]
        };
        
        return df;
    };
    
    var _f_Init = function() {
        try {
            /*
             * [DEPUIS 12-07-15] @BOR
             */
            $(".jb-trpg-ed-ver-ovly-mx").data("disabled",true);
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    var _f_SecureFields = function (s,d) {
        try {
            
            if (KgbLib_CheckNullity(s) | KgbLib_CheckNullity(d)) {
                return;
            }
            
            var fds = $(".jb-trpg-ovly-ipt"), ecn = 0;
            $.each(fds, function(x, el) {
                if (!$(el).data("ft")) {
                    return false;
                }
                
                if (!_f_ChecKField(s, el)) {
                    ++ecn;
                }
            });
            
            return (ecn) ? false : true;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    
    var _f_ChecKField = function (s,x) {
        try {
            
            if (KgbLib_CheckNullity(s) | KgbLib_CheckNullity(x) | !$(x).data("ft")) {
                return;
            }
            
            var fd = $(x).data("ft"), v, ie = false, $eb, em;
            switch (fd.toLowerCase()) {
                case "title":
                    v = $(x).val();
                    if (KgbLib_CheckNullity(v)) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else if (v.length < _f_Gdf().tle_min) {
//                    } else if ( !_f_Gdf().rgx_tle.test(v) && v.length < _f_Gdf().tle_min ) { //[DEPUIS 15-06-15]
                        ie = true;
                        em = Kxlib_getDolphinsValue("err_trpg_title_eln").replace("%min%", _f_Gdf().tle_min).replace("%max%", _f_Gdf().tle_max);
                        $(x).addClass("error_field");
                    } else if (v.length > _f_Gdf().tle_max) { 
//                    } else if ( !_f_Gdf().rgx_tle.test(v) && v.length > _f_Gdf().tle_max ) { //[DEPUIS 15-06-15]
                        ie = true;
                        em = Kxlib_getDolphinsValue("err_trpg_title_eln").replace("%min%", _f_Gdf().tle_min).replace("%max%", _f_Gdf().tle_max);
                        $(x).addClass("error_field");
                    } else if (!_f_Gdf().rgx_tle.test(v)) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else {
                        $(x).removeClass("error_field");
                    }
                    
                    $eb = $(s).find(".erb-trpg-input-tle");
                    if (ie && em && $eb.length) {
                        $eb.text(em).removeClass("this_hide");
                    } else {
                        $eb.text("").addClass("this_hide");
                    }
                    break;
                case "description":
                    v = $(x).val();
                    if (KgbLib_CheckNullity(v)) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else if (v.length < _f_Gdf().desc_min) {
//                    } else if ( !_f_Gdf().rgx_tle.test(v) && v.length < _f_Gdf().desc_min ) { //[DEPUIS 15-06-15]
                        ie = true;
                        em = Kxlib_getDolphinsValue("err_trpg_desc_eln").replace("%min%", _f_Gdf().desc_min).replace("%max%", _f_Gdf().desc_max);
                        $(x).addClass("error_field");
                    } else if (v.length > _f_Gdf().desc_max) {
//                    } else if ( !_f_Gdf().rgx_tle.test(v) && v.length > _f_Gdf().desc_max ) { //[DEPUIS 15-06-15]
                        ie = true;
                        em = Kxlib_getDolphinsValue("err_trpg_desc_eln").replace("%min%", _f_Gdf().desc_min).replace("%max%", _f_Gdf().desc_max);
//                        em = Kxlib_getDolphinsValue("err_trpg_title_eln").replace("%min%",_f_Gdf().desc_min).replace("%max%",_f_Gdf().desc_max); //[DEPUIS 15-06-15]
                        $(x).addClass("error_field");
                    } else if (!_f_Gdf().rgx_desc.test(v)) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else {
                        $(x).removeClass("error_field");
                    }
                    
                    $eb = $(s).find(".erb-trpg-input-desc");
                    if (ie && em && $eb.length) {
                        $eb.text(em).removeClass("this_hide");
                    } else {
                        $eb.text("").addClass("this_hide");
                    }
                    break;
                case "category":
                    //Controllée par le serveur
                    break;
                case "participation":
                    v = $(".jb-trpg-input-part option:selected").val(); 
                    if (KgbLib_CheckNullity(v)) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else if ($.inArray(v, _f_Gdf().part) === -1) {
                        ie = true;
                        $(x).addClass("error_field");
                    } else {
                        $(x).removeClass("error_field");
                    }
                    break;
                default:
                    return;
            }
            
            return (ie) ? false : true;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /* OBSELETE
    var _f_SecureFields = function (s,d) {
//    this.SecureField = function (s,d) {
//        alert("FORM => "+s);
        //Tableau de repère. On stocke pour chaque field le bon selecteur
        var ts = {
            //layer 1 : t = title, d = desc, c = catg, p = part, g = grat
            //layar 2 : t = target, e = la barre d'erreur
            "t": {
                "t" : $(s).find(".jb-trpg-input-tle"),
                "e" : $(s).find(".erb-trpg-input-tle")
            },
            "d": {
                "t" : $(s).find(".jb-trpg-input-desc"),
                "e" : $(s).find(".erb-trpg-input-desc")
            },
//            "c": {
//                "t" : $(s).find(".jb-trpg-input-cat"),
//                "e" : $(s).find(".erb-trpg-input-cat")
//            },
            "p": {
                "t" : $(s).find(".jb-trpg-input-part"),
                "e" : $(s).find(".erb-trpg-input-part")
            }
//            "g": {
//                "t" : $(s).find(".jsbind-trpg_input_grat"),
//                "e" : $(s).find(".errbar-trpg_input_grat")
//            }
        }, e = true;
       
        //*
        $.each(d, function(i,v){
            
            //On utilise une variable pour les traitements internes au each
            var ie = true;
            
//            alert("debut--> "+v.toString()+" <--fin");
            //Si c'est un tableau (cas de participation), on le traitre (ou pas) dans les cas particuliers
            if ( Object.prototype.toString.call(v) !== '[object Array]' ) {
                //[NOTE 09-10-14] @author L.C. Il faut laisser le serveur sécuriser le texte. En effet, cela risque de créer un enchevetrement de sécurisation et comprendre ce dernier.
//                var c = Kxlib_EscapeHTMLEntity(v.toString());
                
                
                if ( KgbLib_CheckNullity(v) ) {
                    var rw = ts[i]["t"];
                    $(rw).addClass("error_field");
                    e = false;
                    ie = false;
                    
                    v = "";
                    
                } else {
                    var c = Kxlib_Trim(v.toString());
                    if (! c.length ) {
//                    Kxlib_DebugVars([VIDE"]);
//                    alert("VIDE -- INDEX => "+i);
                        //rw = row
                        var rw = ts[i]["t"];
                        $(rw).addClass("error_field");
                        e = false;
                        ie = false;
                    } //BALISE
                    /*
    //            else if ( c.match(/(<([^>]+)>)/ig,"") ) {
                else if ( c.match(/(<(([^>]+>)|(\?php)))/ig,"") ) {
                    //ORIGINE (seulement balise) = /(<([^>]+)>)/ig
                    //<script>alert("toto");</script>
                    Kxlib_DebugVars([BALISE"]);
                    ts[i].addClass("error_field");
                    e = false;
                } 
                
//                else {
//                    e = true;
//                }
                }
            }
            
            //Traitement des cas spéciaux
            switch (i) {
                case "t":
                        var x1 = $("#a-h-t-top-tr-title").data("maxln");
                                
                        if ( v.length > x1 ) {
                            //Kxlib_DebugVars([in TRPG_EDIT : MAX => "+$("#a-h-t-top-tr-title").data("maxln")+"; LN => "+v.length]);
                            //Ah ! Trop long !
                            e = false;
                            ie = false;
                            
                            //Entourer en rouge
                            $(ts[i]["t"]).addClass("error_field");
                            
                            //Afficher l'erreur
                            var m1 = Kxlib_getDolphinsValue("err_trpg_title_eln");
                            m1 = m1.replace("%maxln%",x1);
//                            Kxlib_DebugVars([m1,$(ts[i]["e"]).length],true);
                            $(ts[i]["e"]).text(m1);
                            $(ts[i]["e"]).removeClass("this_hide");
                        }
//                        alert();
                        //On réaffecte la valeur
                        d[i] = c;
                    break;
                case "d":
                        var x2 = $("#a-h-t-top-tr-desc").data("maxln");
                        if ( v.length > x2 ) {
                            //Kxlib_DebugVars([in TRPG_EDIT : MAX => "+$("#a-h-t-top-tr-title").data("maxln")+"; LN => "+v.length]);
                            //Ah ! Trop long !
                            e = false;
                            ie = false;
                            
                            //Entourer en rouge
                            $(ts[i]["t"]).addClass("error_field");
                            
                            //Afficher l'erreur
                            var m2 = Kxlib_getDolphinsValue("err_trpg_desc_eln");
                            m2 = m2.replace("%maxln%",x2);
                            $(ts[i]["e"]).html(m2);
                            $(ts[i]["e"]).removeClass("this_hide");
                        }
                        
                        //On réaffecte la valeur
                        d[i] = c;
                    break;
                case "c":
                        //On réaffecte la valeur
                        d[i] = v;
                    break;
                case "p":
                        //On réaffecte la valeur
                        d[i] = v;
                    break;
//                case "g":
//                        //On réaffecte la valeur
//                        d[i] = v;
//                    break;
                default:
                        //NOTE : C'est pratiquement impossible. Il est possible que l'utilisateur ait changé des données au niveau de FE.
                        return;
                    break;
            }
            
//            alert(d["g"]);
            
            //Si aucune erreur n'est détectée. De plus corrige la bordure quand elle redevient valide
//            alert("ERR ?"+e);
//            alert("ERR ? => "+e+" INDEX => "+i);
            if ( ie ) {
//                if ( i ==="t")  {
//                    alert("in desc");
//                    alert("ERR ? => "+e+" INDEX => "+i);
                //}
                ts[i]["t"].removeClass("error_field");
                ts[i]["e"].addClass("this_hide");
                ts[i]["e"].html("");
            }
            
        });
        
//        Kxlib_DebugVars([EEEEEEEEEEEEEEEEEEEEE => "+e]);
        return ( e ) ? d : undefined;
    };
    //*/          
    
    var _f_RstEdit = function(x) {
        try {
            if ( KgbLib_CheckNullity(x) ) {
                return;
            }
            if ( $(".jb-trpg-ed-ver-ovly-mx").data("disabled") === true ) {
                return;
            }
            //L'identifiant du formulaire
            var fs = Kxlib_ValidIdSel("tr-e-v-ovly-form");
            
            /*
             * Permet de retirer les erreurs lorque l'on reset le formulaire
             */
            _f_RmvErrs(fs);
            
            /*
             * On reset le formulaire
             */
            Kxlib_ResetForm(fs);
            //Pour des raisons obscures, la fonction ne parvient pas à reset le textarea
            $(".jb-trpg-input-desc").val("");
            
            /*
             * On réinit les compteurs
             */
            $(".jb-trpg-ed-ver-ovly-mx").find(".jb-trpg-input-tle").triggerHandler("blur");
            $(".jb-trpg-ed-ver-ovly-mx").find(".jb-trpg-input-desc").triggerHandler("blur");
            
        } catch(ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }
    };
    
    /********************************************************************************************************************************************************************/
    /**************************************************************************** SERVER SCOPE **************************************************************************/
    /********************************************************************************************************************************************************************/
    
    //URQID => Sauvegarder les nouveaux settings de la Tendance
    var _Ax_SvNwEdit = Kxlib_GetAjaxRules("TRPG_SV_NW_STS");
    var _f_Srv_SvNwEdit = function(ti,nd) {
//    this.Srv_SaveNewEdit = function(ti,nd) {
                
        if ( KgbLib_CheckNullity(ti) | KgbLib_CheckNullity(nd) ) {
            return;
        }
            
        var onsuccess = function (d) {
            try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    return;
                }
                
                if (! KgbLib_CheckNullity(d.err)) {
                    
                    if (Kxlib_AjaxIsErrVolatile(d.err)) {
                        switch (d.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_OWNER_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_TRD_GONE":
                                    //TODO : Indiquer à l'utilisateur que la Tendance n'existe plus via un overlay ou juste en rechargeant
                                break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                        Kxlib_AJAX_HandleDeny();
                                    break;
                            case "__ERR_VOL_FAILED" :
                                    Kxlib_AJAX_HandleFailed();
                                break;
                            case "__ERR_VOL_TRTITLE_NOT_COMPLY" :
                            case "__ERR_VOL_TRDESC_NOT_COMPLY" :
                            case "__ERR_VOL_TRCATG_NOT_COMPLY" :
                                    var m = Kxlib_getDolphinsValue("ERR_TPRG_ST_DT_NCOMPL");
                                    Kxlib_AJAX_HandleFailed(m);
                                break;
                            case "__ERR_VOL_TRD_CATGLOCK" :
                                    //NOTE : On ne peut plus changer la catégorie d'une dès qu'un certain delai a été dépassé.
                                    var m = Kxlib_getDolphinsValue("ERR_TPRG_LOCK_ON_CATG");
                                    Kxlib_AJAX_HandleFailed(m);
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return)  )  {
                    /*
                     * Données attendues : 
                     *  (1) Données de base d'une Tendance : titre, description, participation, catégorie, gratification
                     *  (2) L'image de couverture
                     *  (3) Les données sur le propriétaire de la Tendance
                     */
                    
                    //On insère les données dans les champs
                    var o = new TrendHeader();
                    o.UpdHdrWNwStgs(d.return);
                    
                    //TODO : Mettre à jour les données sur l'image de couverture
                    
                    //TODO : Mettre à jour les données sur le propriétaire de la Tendance
                    
                    //Afficher le message de notification
                    var Nty = new Notifyzing ();
                    Nty.FromUserAction("ua_trpg_new_setgs");
                    
                    
                    //[DEPUIS 13-06-15] @BOR Faire apparaitre l'overlay qui informe qu'une redirection encours
                    if ( $(".jb-pg-sts").length ) {
                        $(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Redir")+"...");
                        $(".jb-pg-sts").removeClass("this_hide");
                    }
                    
                    //[NOTE 09-10-14] @author L.C. Il y a aussi les Articles voir d'autres données à changer dans la page. Autant reload
                    setTimeout(function(){
                        window.location.replace(d.return.thrf);
                    },2000);
                    
                } else {
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
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
            "urqid": _Ax_SvNwEdit.urqid,
            "datas": {
                "ti" : ti,
                "t": nd.t,
                "d": nd.d,
//                "c": nd.c,
                "p": nd.p[0]
            }
        };

        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_SvNwEdit.url, wcrdtl : _Ax_SvNwEdit.wcrdtl });
    };
    
    /*
    //URQID => Sauvegarder les données pour créer la Tendance
    this.saveNewTrend_url = "http://127.0.0.1/korgb/ajax_test.php";
    this.saveNewTrend_uq = "trpg_save_new_trend";
    this.Srv_SaveNewTrend = function(d) {
        var th = this;
        
        var onsuccess = function (datas) {
            
            //alert("CHAINE JSON AVANT PARSE"+datas);
            
            if (! KgbLib_CheckNullity(datas) )
                datas = JSON.parse(datas);
            
            var o = new ErrorBarVTop(), 
                    m = "L'opération a échoué. Nous vous conseillons d'attendre quelques minutes et de réesssayer",
                        i = "#err_bar_in_trpg";
            
            if ( typeof datas.err !== "undefined" ) {
                m = ( !KgbLib_CheckNullity(datas.err) ) ? datas.err : m;
                o.EB_DeclareErr(i,m); 
            } else if ( KgbLib_CheckNullity(datas.url) ) {
                //Si l'url n'est pas définie ou est vide, déclencher une erreur
                o.EB_DeclareErr(i,m,"m");
            } else {
                //On fait la redirection vers la nouvelle tendance
                window.location.href = datas.url;
            }
                
        };

        var onerror = function(a,b,c) {
            //A VOIR avec le temps
        };
        
        //Pour déterminer qui est le propriétaire on regardera dans la SESSION en cours
        var toSend = {
            "urqid": th.saveNewTrend_uq,
            "datas": {
                "datas": d
            }
        };

        Kx_XHR_Send(toSend, "post", this.saveNewTrend_url, onerror, onsuccess);
    };
    //*/
                                                                                                        
    
    var _Ax_TryDisco = Kxlib_GetAjaxRules("TRPG_TRY_ABO_OPER");
    var _f_Srv_TryAboOper = function(i,t,w,s) {
//    this.Srv_TryAboOper = function(i,t,w,s) {
        
        if ( KgbLib_CheckNullity(i) | KgbLib_CheckNullity(t) | KgbLib_CheckNullity(w) | KgbLib_CheckNullity(s) ) {
            return; 
        }
        
        var onsuccess = function (d) {
            try {
                Kxlib_DebugVars([typeof d,d],true);
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    return;
                }
            
                if (! KgbLib_CheckNullity(d.err) ) {
                    
                    if ( Kxlib_AjaxIsErrVolatile(d.err) ) {
                        //On masque le loader
                        $(".jb-trpg_abo_ldg").addClass('this_hide');
                        //On DElock les opérations de connexion tant qu'on a pas de retour
                        $(".jb-tao-sn").data("isl",0);
                        
                        switch (d.err) {
                            case "__ERR_VOL_U_G":
                            case "__ERR_VOL_U_GONE":
                            case "__ERR_VOL_ACC_GONE":
                            case "__ERR_VOL_USER_GONE":
                            case "__ERR_VOL_CU_GONE":
                            case "__ERR_VOL_OWNER_GONE":
                                    Kxlib_HandleCurrUserGone();
                                break;
                            case "__ERR_VOL_TRD_GONE":
                                //TODO : Indiquer à l'utilisateur que la Tendance n'existe plus via un overlay ou juste en rechargeant
                                break;
                            break;
                            case "__ERR_VOL_DENY":
                            case "__ERR_VOL_DENY_AKX":
                                    Kxlib_AJAX_HandleDeny();
                                break;
                            case "__ERR_VOL_IS_OWNER":
                                    //TODO : Il faut signaler le problème au près de l'équipe support
                                    var m = Kxlib_getDolphinsValue("UA_COM_DPERR_MUST_RLD");
                                    Kxlib_HandleTrdMustReload(null,m,"uhome","reload",true);
                                break;
                            case "__ERR_VOL_ABO_EXISTS" :
                            case "__ERR_VOL_NO_TRABO" :
                                    var m = Kxlib_getDolphinsValue("UA_COM_MUST_RLD");
                                    Kxlib_HandleTrdMustReload(null,m,"uhome","reload",true);
                                break;
                            case "__ERR_VOL_FAILED" :
//                                Kxlib_AJAX_HandleFailed();
                                break;
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) ) {
                    var rds = d.return;
                    $(s).trigger("operended");
                } else {
                    return;
                }
            } catch (ex) {
                Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
                return;
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//            Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
            return;
        };
        
        /*
         * RAPPEL : 
         *  -> UID 
         *  Pour déterminer qui est l'utilisateur courant, on regardera dans la SESSION en cours.
         *  On ne se fit pas à l'identifiant envoyé par FE. On ne l'inscrit pour des raisons de sécurité.
         *  En effet, il s'agit d'un leurre pour détecter si l'utilisateur a un profil dangereux.
         *  
         *  -> URL
         *  On envoie aussi l'URL pour les mêmes raisons de sécurité. Pour encore une fois mieux authentifier la requete.
         */
        var cul = document.URL;
        var toSend = {
            "urqid": _Ax_TryDisco.urqid,
            "datas": {
                "ui"    : i,
                "ti"    : t,
                "cl"    : cul,
                "pl"    : "TRPG",
                "w"     : w
            }
        };
//        Kxlib_DebugVars([document.URL,cul,encodeURIComponent(document.URL)],true);
//        return;
        Kx_XHR_Send(toSend, null, null, onsuccess, onerror, { type : "post", url : _Ax_TryDisco.url, wcrdtl : _Ax_TryDisco.wcrdtl });
    };
                                                                                                        
    /********************************************************************************************************************************************************************/
    /**************************************************************************** VIEW SCOPE ****************************************************************************/
    /********************************************************************************************************************************************************************/
    var _f_FillEditFormRows = function (s,d,isd) {
//    this.FillEditFormRows = function (s, d) {
        //s = selector du formulaire; d = datas formatées; isd : IsServerDatas
        try {
              
            if ( KgbLib_CheckNullity(s) | KgbLib_CheckNullity(d) ) {
                return;
            }
            
//            Kxlib_DebugVars([1139,typeof s,JSON.stringify(d),isd],true);
        
            //Titre
            var t = Kxlib_Decode_After_Encode(d.t);
            t = $("<div/>").text(t).text();
            $(s).find(".jb-trpg-input-tle").val(t);
            
            //On lance le handler de blur pour que le compteur se lance
            $(s).find(".jb-trpg-input-tle").text(t).triggerHandler("blur");
            
            /*
             * [NOTE 27-04-15] @BOR
             * ETAPE :
             * On ajoute la description
             */
            var ds = $("<div/>").text(d.d).text();
//        alert("1108 => "+ds);
            
            /*
             * [NOTE 27-04-15] @BOR
             * ETAPE :
             * On vérifie s'il s'agit d'un retour serveur, qui est encodé différemment.
             * On decode pour éviter des erreurs de decodage
             */
            if ( d.hasOwnProperty("trcov") ) {
                ds = Kxlib_Decode_After_Encode(ds);
            }
                    
//            alert("1223 => "+ds);   
    
            //On lance le handler de blur pour que le compteur se lance
//            $(s).find(".jb-trpg-input-desc").text(ds).triggerHandler("blur");
            $(s).find(".jb-trpg-input-desc").val(ds).triggerHandler("blur");
            
            //Catégorie
//        $(s).find(".jb-trpg-input-cat").find("option:selected").removeAttr("selected");
//        $(s).find(".jb-trpg-input-cat").find("option[value="+d.c+"]").prop('selected', true);
            
            //Participation (Pub, Pri)
            var prt = ( d.p[0] === "pri" || d.p[0] === "_NTR_PART_PRI" ) ? "_NTR_PART_PRI" : "_NTR_PART_PUB";
            $(s).find(".jb-trpg-input-part").find("option:selected").removeAttr("selected");
            $(s).find(".jb-trpg-input-part").find("option[value=" + prt + "]").prop('selected', true);
            
            //Gratification
//        $(s).find(".jb-trpg-input-grat").find("option:selected").removeAttr("selected");
//        $(s).find(".jb-trpg-input-grat").find("option[value="+d.g+"]").prop('selected', true);

            /*
             * [DEPUIS 12-07-15] @BOR
             * On rendre les champs "enabled" 
             */
            if ( isd === true ) {
                $(".jb-trpg-input-tle").attr("disabled",false);
                $(".jb-trpg-input-desc").attr("disabled",false);
                $(".jb-trpg-ovly-ipt[data-ft='participation'").attr("disabled",false);
            }
            
            /*
             * [DEPUIS 12-07-15] @BOR
             * On rend disponible le formulaire pour les autres actions
             */
            if ( isd === true ) {
                $(".jb-trpg-ed-ver-ovly-mx").data("disabled",false);
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_FillForm = function (s, a) {
//    this.HandleFillForm = function (s, a) {
        //On verifie pour chaque "Settings" si la valeur est à jour
        //Ces données sont fournies 'généreusement' par TR_HEADER
        try {
            
            var o = new TrendHeader();
            //Voir sa déclaration pour plus de compréhension
            _o_trhdr_edit = o;
//        alert("MONACO =>"+_o_trhdr_edit);
            o.GetAndDisplaySettings(_f_FillEditFormRows, s, a);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber], true);
        }

        
        //TODO : Controller que toutes les données présentes sont ...
        // ... Sinon déclencher une erreur

    };
    
     /************* OPEN - CLOSE (Edit_Aside) *************/
    var _f_ClzEditInTrpg = function () {
//    this.CloseEditInTrendPg = function () {
        $("#tr-edit-ver-aside").addClass("this_hide");
    };
    
    var _f_ShwEditInTrpg = function () {
//    this.OpenEditInTrendPg = function () {
        var s = "#tr-edit-ver-aside",
                sf = "#tr-e-v-a-form";
        
        //On s'assure de bien fermer CREATE
        _f_ClzCrtInTrpg();
        
        //Affiher l'ensemble
        $(s).removeClass("this_hide");
        
        //On récupère les données
        //_f_FillEditFormRows(sf,od);
        
        //On récupère les données
//        var i = Kxlib_getDolphinsValue("trid");
        var pp = Kxlib_GetTrendPropIfExist();
        
        if ( KgbLib_CheckNullity(pp) ) {
            //TODO: Averrtir l'utilisateur qu'il y a un problème technique avec la page.
            //TODO : Prévenir le serveur. Cette erreur a une probabilité faible d'arriver. Cela peut être causé par une modificationde la page par CU
            return;
        }
        
        var i = pp.trid;
        _f_FillForm(sf,i);
    };
    
    /************* OPEN - CLOSE (Create_Aside) *************/
    var _f_ClzCrtInTrpg = function () {
//    this.CloseCreateInTrendPg = function () {
        $("#tr-cr-ver-aside").addClass("this_hide");
    };
    
    var _f_ShwCrtInTrpg = function () {
//    this.OpenCreateInTrendPg = function () {
        //On s'assure de bien fermer EDIT
        _f_ClzEditInTrpg();
        
        $("#tr-cr-ver-aside").removeClass("this_hide");
    };
    
    /************* OPEN - CLOSE (Edit_OVerLaY) *************/
    var _f_ClzEditInTrpg_Ovly = function () {
//    this.CloseEditInTrPg_Ovly = function () {
        try {
            
            //On fait disparaitre l'Overlay
            $(".jb-trpg-ovly-mx").addClass("this_hide");
            
            //On fait disparaitre EDIT
            $("#trpg-ed-ver-ovly-max").addClass("this_hide");
            
            /*
             * [DEPUIS 12-07-15] @BOR
             * Rendre les input "disabled" pour la prochaine opération
             */
            $(".jb-trpg-input-tle").attr("disabled", true);
            $(".jb-trpg-input-desc").attr("disabled", true);
            $(".jb-trpg-ovly-ipt[data-ft='participation'").attr("disabled", true);
            
            /*
             * [DEPUIS 12-07-15] @BOR
             * On rend le formulaire impossible à toute autre action pour éviter que l'utilisateur n'est accès aux fonctionnalités RESET et SUBMIT
             */
            $(".jb-trpg-ed-ver-ovly-mx").data("disabled", true);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_ShwEditInTrpg_Ovly = function () {
//    this.OpenEditInTrPg_Ovly = function () {
        try {
            
            var s = "#trpg-ed-ver-ovly-max", sf = "#tr-e-v-ovly-form";
            
            //On s'assure de bien fermer CREATE (SEULEMENT)
            $("#trpg-cr-ver-ovly-max").addClass("this_hide");
            
            //On s'assure qu'EDIT (ver aside) est fermée
            _f_ClzEditInTrpg();
            //On s'assure que CREATE (ver aside) est fermée
            _f_ClzCrtInTrpg();
            
            //On fait apparaitre l'Overlay
            $(".jb-trpg-ovly-mx").removeClass("this_hide");
            //On affiche EDIT
            $(s).removeClass("this_hide");
            
            //On affiche les données de la Tendance en cours pour éviter qu'il y ait un vide
            var od = {
                "t": $(".jb-a-h-t-top-tr-tle").text(),
                "d": $(".jb-a-h-t-top-tr-desc").text(),
                "c": $(".jb-ttr-cache").data("c"),
                "p": $(".jb-ttr-cache").data("p").split(',')
            };
            
//            Kxlib_DebugVars([od.t,od.d,od.c,od.p[0]],true);
//            return;
            
//        alert("Where HAve You Been");
            _f_FillEditFormRows(sf, od);
            
            //On récupère les données
//        var i = Kxlib_getDolphinsValue("trid");
            var pp = Kxlib_GetTrendPropIfExist();
            
            if ( KgbLib_CheckNullity(pp) ) {
                //TODO: Averrtir l'utilisateur qu'il y a un problème technique avec la page.
                //TODO : Prévenir le serveur. Cette erreur a une probabilité faible d'arriver. Cela peut être causé par une modificationde la page par CU
                return;
            }
            
            var i = pp.trid;
            _f_FillForm(sf,i);
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    /************* OPEN - CLOSE (Create_OVerLaY) *************/
    var _f_ClzCrtInTrpg_Ovly = function () {
//    this.CloseCreateInTrPg_Ovly = function () {
        try {
            //On fait disparaitre l'Overlay
            $(".jb-trpg-ovly-mx").addClass("this_hide");
            
            //On fait disparaitre CREATE
            $("#trpg-cr-ver-ovly-max").addClass("this_hide");
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var _f_ShwCrtInTrpg_Ovly = function () {
//    this.OpenCreateInTrPg_Ovly = function () {
        //On s'assure de bien fermer EDIT (SEULEMENT)
        $("#trpg-ed-ver-ovly-max").addClass("this_hide");
        
        //On s'assure qu'EDIT (ver aside) est fermé
        _f_ClzEditInTrpg();
        //On s'assure que CREATE (ver aside) est fermé
        _f_ClzCrtInTrpg();
        
        //On fait apparaitre l'Overlay
        $(".jb-trpg-ovly-mx").removeClass("this_hide");
        //On affiche CREATE
        $("#trpg-cr-ver-ovly-max").removeClass("this_hide");
    };
    
    var _f_RmvErrs = function (s) {
//    this.RemoveErrors = function (s) {
        try {
            
            if ( KgbLib_CheckNullity(s) ) {
                return;
            }
            
            s = Kxlib_ValidIdSel(s);
            
            //On retire les bordures rouges
            $(s).find("input, textarea, select").removeClass("error_field");
            
            //On cache la barre d'erreur
            $(s).find(".tr-v-a-hder-err, .tr-v-ovly-hder-err").addClass("this_hide");
            //On vide la barre d'erreur
            $(s).find(".tr-v-a-hder-err, .tr-v-ovly-hder-err").html();
            
            /**
             * Il n'y a que deux cas possible qui permettent d'arriver ici :
             * 1- J'aunnule une creation 
             *      => Dans ce cas on laisse les données tapées par user
             *          => Lorsqu'on est en OVLY c'est primordiale car ça permet à User d'aller et revenir
             *  2- J'annule la modification des Settings
             *      => Les données sont automatiquement renouvellées à la création
             *          => Le compteur aussi grace au fait qu'à l'insertion des données, on controle le nombre de caractères
             *          
             *  !! Tout cela pousse à commenter ce qui est en-dessous !!
             */
            
            /*
             var v = $(s).find(".trpg-ed-cr-char-cn").data("init");
             
             v =( KgbLib_CheckNullity(v) ) ? "0" : v;
             //        alert(v);
             $(s).find(".trpg-ed-cr-char-cn").html(v);
             $(s).find(".jsbind-trpg_input_desc").val("");
             //*/
            //On retire le rouge en activant le compteur
            //$(s).find(".check_char").blur();
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);
        }

    };
    
    var SwiToActCntd = function () {
//    this.SwitchToActionCntd = function () {
        $(".jb-trpg_getctd_btn").removeClass("this_hide");
        $(".jb-trpg_cntd_bdg").addClass("this_hide");
    };
    
    var _f_SwToActDisCo = function () {
//    this.SwitchToActionDisCo = function () {
        $(".jb-trpg_getctd_btn").addClass("this_hide");
        $(".jb-trpg_cntd_bdg").removeClass("this_hide");
    };
    
    /********************************************************************************************************************************************************************/
    /************************************************************************** LISTENERS SCOPE *************************************************************************/
    /********************************************************************************************************************************************************************/
    $("#tr-cr-v-a-cancel").click(function(e){
        Kxlib_PreventDefault(e);
        var s = Kxlib_ValidIdSel($(this).data("f"));
        
        _f_ClzCrtInTrpg();
        _f_RmvErrs(s);
    });
    
    $(".jb-tr-v-ovly-ccl").click(function(e){
        Kxlib_PreventDefault(e);
        var s = Kxlib_ValidIdSel($(this).data("f"));
        
        _f_ClzEditInTrpg_Ovly();
//        _f_ClzCrtInTrpg_Ovly();
        _f_RmvErrs(s);
    });
    
    /*******************************************/
    
    $("#tr-ed-v-ovly-cancel").click(function(e){
        Kxlib_PreventDefault(e);
        var s = Kxlib_ValidIdSel($(this).data("f"));
        
        _f_ClzEditInTrpg_Ovly();
        _f_RmvErrs(s);
    });
    
    $("#tr-e-v-a-cancel").click(function(e){
        Kxlib_PreventDefault(e);
        var s = Kxlib_ValidIdSel($(this).data("f"));
        
        _f_ClzEditInTrpg();
        _f_RmvErrs(s);
    });
    
    /*******************************************/
    /***** OVLY *****/
    $("#tr-e-v-a-save").click(function(e){
        Kxlib_PreventDefault(e);
        var s = Kxlib_ValidIdSel($(this).data("f"));
        
        _f_NwEdit(s, "aside");
    });
    
    $(".jb-tr-e-v-ovly-save").click(function(e){
        Kxlib_PreventDefault(e);
        
        var s = Kxlib_ValidIdSel($(this).data("f"));
        _f_NwEdit(s, "ovly");
    });
    
    /*
     * [DEPUIS 12-07-15] @BOR
     */
    $(".jb-tr-e-v-ovly-rst").click(function(e){
        Kxlib_PreventDefault(e);
        
        _f_RstEdit(this);
    });
           
    /***** ASIDE *****/
    
    $("#tr-cr-v-a-save").click(function(e){
        Kxlib_PreventDefault(e);
        var s = Kxlib_ValidIdSel($(this).data("f"));
        
        _f_NwCrt(s, "ovly");
    });
    
    $("#tr-cr-v-ovly-save").click(function(e){
        Kxlib_PreventDefault(e);
        var s = Kxlib_ValidIdSel($(this).data("f"));
        
        _f_NwCrt(s, "ovly");
    });
    
    /********************* CAS DU RESET **********************/
    /*
    $(".trpg-sp-form").on('formcleared', function (e) {
//        alert('ALLO!');
        
        //Récupère l'id du bloc contenant le formulaire
        var s = $(e.target).parent().attr("id");
        
        //Permet de retirer les erreurs lorque l'on reset le formulaire
        _f_RmvErrs(s);
        
    });
    */
    /******************** CONFIRM PART CHANGE *********************/
    $(".trpg-c-p-d-ch-each").click(function(e){
        Kxlib_PreventDefault(e);
        var s = Kxlib_ValidIdSel($(this).data("par")),
            a = $(this).data("action");
        
        _f_CnfrmPartChc([s,a]);
    });
    
    
    /******************** CONNECTION *********************/
    $(".jb-trpg-action, .jb-trpg-action > *").click(function(e){
        Kxlib_PreventDefault(e);
        Kxlib_StopPropagation(e);
                
        if ( $(this).is(".jb-trpg-action") ) {
            _f_CntdAct(this);
        } else {
            var tr = $(this).closest(".jb-trpg-action");
            _f_CntdAct(tr);
        }
    });
    
    
    /********************************************************************************************************************************************************************/
    /**************************************************************************** INIT SCOPE ****************************************************************************/
    /********************************************************************************************************************************************************************/
    
    _f_Init();
}
var obj = new TrendEditHandle();
function TrendEdit_Receiver (){
    this.Routeur = function (th){
        if ( KgbLib_CheckNullity(th) ) return; 
        
        obj.CheckOperation(th);
    };
};
