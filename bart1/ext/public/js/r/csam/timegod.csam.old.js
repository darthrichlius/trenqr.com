/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function TIMEGOD () {
    /* TIMEGOD = Le Dieu du Temps */
    /* Il est admis que "TIME" représente en même temps le teps horloge et la date. Cela en fonction de la valeur traitée */
    /* AXR = AjaxRules*/
    
    /*********
     * RULES : 
     * -----
     * 
     * -> Les ajouts des éléments se font en UTC+0 au niveau du serveur.
     * -> A chaque ajout on pense quand même (Facultatif) à insérer la TIMEZONE liée à la zone à partir de laquelle a été insérée.
     * -> Lorsque les données arrivent au niveau du FrontEnd, il y a 3 dimensions distinctes :
     *      . Le temps en UTC+0. Cela permet de corriger des erreurs au cas où
     *      . Le temps en UTC de l'utilisateur actif (dans les attributs). 
     *          . L'UTC dépend de l'endroit d'où se connecte l'utilisateur (IMPORTANT à retenir)
     *          . On ne mentionne pas l'UTC dans les pages pour préserver la confidentialité du mode de traitement
     *      . Le temps effectif qui représente la différence entre le TIME d'ajout et le CURRENT_TIME (en dur)
     * -> A l'affichage les '.kxlib_tgspy' sont affichés à l'heure locale. Cela permet à l'utilisateur d'avoir un point de repère facilement compréhensible.
     * -> A l'affichage, les valeurs sont affichées en fonction d'une unité de temps.
     *      . (it1) 0s - 59s => toutes les 20 secondes
     *      . (it2) 0m - 59m => toutes les 1 minutes
     *      . (it3) 1h - 23h59 => toutes les heures
     *      . (it4) >24h => on affiche la date rapportée au fuseau horaire actuel de l'utilisateur actif
     *  -> L'affichage se fait en convertissant la valeur à la valeur inferieur la plus proche
     *      Exemple : Si la différence nous donne la valeur 15mins, on 'convertit' à 10 mins
     *                Si la valeur est 1h50 on 'convertit' à 1h
     *     Cela permet de respecter la 'standardisation' de certaines valeurs 20s, 1m, etc ... 
     *  
     *  IMPORTANT : Dans tous les cas on CHECK le '.kxlib_tgspy' selon la REFERENCE TEMPORELLE LA PLUS FAIBLE. 
     *              Dans notre cas, il s'agit de celle de l'intervale (it1) c'est à dire 20 secondes.
     *
     *********/
    
    
    /*************************** PROPERTIES ****************************/
    //Pour plus d'informations voir le paragraphe RULES.
    var _loopref = 20000; //5000 pour les tests, 20000 pour la prod
//    this._loopref = 20000; //5000 pour les tests, 20000 pour la prod
    var _lpTmzKnfl = 300000;
//    this.loopTmzConflict = 300000;
    /***
     *  Quel UTC prendre en compte (s = serveur; l = local; default -> serveur)
     *  Souvent le Caller (très souvent x-index) fait un mixte des deux à intervalles variés.
     *      //Exemple : Toutes les 10 secondes => local; toutes les 60 secondes => server
     */
    this.UTCsrc;
    //Les TGSPY peuvent être NOCOMPLIANT (non conforme) ou CRACKED (deffectueux). Cette propriété est utilisée pour gérer les cas de composants ayant un problème.
    this.codeErr;
    this.SRV_TMZ;
    /* 
     * Lorsque cette propriété est non NULL cela signifie qu'il faut que le processus considère ce fuseau-horaire pour les traitements. 
     * Cela arrive lorsque qu'une erreur de type TMZ_CONFLICT apparait.
     * */
    var _frcTmz;
//    _frcTmz;
    
    var __DIS_TIME_MODE_DATE = 0; 
    var __DIS_TIME_MODE_INTRV = 1;
    
    //Comment sont affichées les jours (Il y a 10 jours; 25 May)
    var _dis_days_mode = __DIS_TIME_MODE_DATE;
//    this.dis_days_mode = __DIS_TIME_MODE_DATE;
    
    
    /************************ AJAX SPECS ****************************/
    
    
    /****************************************************************/
    
    var _f_GetNowTimeAtNullUTC = function () {
//    this.GetNowTimeAtNullUTC = function () {
        //Obtention de la date actuelle rapporté au TMZ local
        return (new Date()).getTime() ;
    };
    
    var _f_GetNowTimeAtLocalUTC = function () {
//    this.GetNowTimeAtLocalUTC = function () {
        //Obtention de la date actuelle rapporté au TMZ local
        return (new KxDate(true)).getTime() ;
    };
    
    this.InitTestDatas = function () {
        /* Crée des données pour tests :
        * Date de création : Maintenant
        * Maintenant, il y a 30 secs, 45 secs; 2 mins; 5 mins; 50 mins; 1 jour, 
        * */ 
       var d1 = new Date(2013,11,31,23,59,59,998);
//            var d1 = new Date(2014,5,27,20,0,0);
//            var d1 = new Date();
//            Date.parse()
//            d1.get
//            alert(d1.getHours());
//            alert(d1.getUTCHours());
//            alert("TIME_ZONE => "+d1.getTimezoneOffset());
//            alert(d1.getTime());
//            alert(now);
       //Obtention de la date actuelle rapporté au TMZ local
//        var d1 = new Date();
        //Obtention des valeurs unitaires exactes rapportées à UTC+0
        //On a mettant l'heure rapportée à UTC
//        d1 = new Date(Date.UTC(a,m,d,h,mi,s,ms));
        
//        var now = d1.getTime()-3480000; //58mins
        var now = d1.getTime(); //10secs
        var t10s = now + 10000;
        var t30s = now + 30000;
        var t45s = now + 45000;
        var t2m = now + (2*60*1000);
        var t5m = now + (5*60*1000);
        var t50m = now + (50*60*1000);
        var t1d = now + (24*3600000);

        $(".kxlib_tgspy").data("tgs-crd",now);
        $(".kxlib_tgspy").data("tgs-dd-atn",0);//now+4000
        $(".kxlib_tgspy").data("tgs-dd-uut",0+7200000); //(now+40000)+7200000
    };
    
    this.LocChanged = function (m) {
        /* Permet de savoir si l'utilisateur a changé sa localisation dans les X (m) mins */
        //m = minutes
        
        if ( KgbLib_CheckNullity(m) )
            return;
        
        var el = $("<span/>");
        
        this._Srv_LocChanged(el,m);
        //Interrogation
        
        return el;
    };
    
    
    /***************************************************************/
    //URQID => Did User chage his Location Withn X minutes
    this.ugloc_uq = "DID_USER_CH_LOC";
    this.ugloc_ajaxr = Kxlib_GetAjaxRules(this.ugloc_uq);
    
    this._Srv_LocChanged = function (el,m) {
        if( KgbLib_CheckNullity(el) || KgbLib_CheckNullity(m) ) 
            return;
        
        var th = this;
        var onsuccess = function (datas) {
//            alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                if (! KgbLib_CheckNullity(datas) )
                    datas = JSON.parse(datas);
                else {
                    var t = (new Date()).getTime().toString();
                    $(el).trigger("noanswer",[t]);
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    //alert(datas.err); //DEBUG : Afficher l'erreur
                    //Sinon ne rien faire, la plateforme va récupérer le fuseau horaire au niveau du Client 
                }
                    
                //Si on a le tmz on déclenche l'event
                if ( !KgbLib_CheckNullity(datas.return) ) {
                    var tp = [datas.return];
                    
                    $(el).trigger("datasready",[tp]);
                    return;
                } else {
                    //Sinon signaler que l'on a pas tmz avec 'datasmissing'
                    $(el).trigger("datasmissing");
                    return;
                }
            } catch (e) {
                //TODO : ?
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
//            alert("AJAX ERR : "+th.ugloc_uq);
        };
        // L'identifiant n'est pas obligatoire. L'utilisateur actif est détec
        var toSend = {
            "urqid": th.ugloc_uq,
            "datas": {
                "m":m
            }
        };

        Kx_XHR_Send(toSend, "post", th.ugloc_ajaxr.url, onerror, onsuccess);
    };
    
    this.GetGenLoopTimeRef = function () {
        //Permet de récupérer la boucle temporelle servant de référence pour lancer les mises à jour des 'TGSPY'
        //Il s'agit de la référence la plus faible
        //Pour plus d'informations, voir le paragraphe 'RULES'
        return _loopref;
    };
    
    this._GetProperLoopTimeRef = function (t) {
        
        //t = Intervalle de temps entre la valeur de temps correspondant à la date de la dernière mise à jour et la date actuelle.
        
        /* 
         * Permet de récupérer la bonne référence en fonction de la valeur temporelle fournie en paramètre. 
         * En d'autres termes, en fonction de la valeur TIME reçu on renvoit une valeur qui permettra de décider s'il faut ...
         * pour une TGSPY mettre à jour sa valeur.
         * Exemple : (1) La valeur est 7000 (7s). La référence renvoyée sera donc de 20000 (20s).
         *               Caller sait qu'il NE FAUT PAS qu'il mette à jour ce TGSPY car 7-20 = -13 ce qui est < 0
         *           (2) La valeur est 305000 (5m5s). La référence renvoyée sera donc de 300000 (5m).
         *               Caller sait qu'il FAUT qu'il mette à jour ce TGSPY car 305000-300000 = 5000 ce qui est >= 0
         * */
        
        if ( KgbLib_CheckNullity(t) )
            return;
        
        /*
         * La valeur doit être fourni en millisecondes.
         * Le type doit etre un number
         */
        // ft = FormatTime le temps après formattage
        var ft = parseInt(t);
        
        if ( (typeof ft !== "number") || ft < 0 )
            return false;
        
        if ( ft >= 0 && ft < 60000 ) //0s - 59s...
            return 20000; //20 secs
        else if ( ft >= 60000 && ft < 3600000 ) //1m - 59m59s...
            return 60000; //1 min
        else if ( ft >= 3600000 && ft < 86400000 ) // 1h - 23h59m59s...
            return 3600000; //1 heure
        else //On considère qu'il s'agit du cas ft >= 24h
            return 86400000; //1j
    };
    
    this.UpdSpies = function (g) {
        //g = GMT, 's' On récupère TMZ auprès du serveur
        if ( KgbLib_CheckNullity(g) ) {
            return;
        }
        try {
            
            //L'application de l'UTC n'est pas obligatoire dans le sens où on a déjà le TMZ dans le bon UTC.
            //Cependant, le savoir permet de garantir une forte autonomie en cas de coup dur au niveau du FrontEnd
            this.UTCsrc = (!KgbLib_CheckNullity(g)) ? g : "s"; 
            
            //Récupère tous les spies 
            //tgspy = TimeGodSpy
            var $ts = $(".kxlib_tgspy");
            
            //Pour chaque TimeSpy
            var th = this;
            $.each($ts, function(i,e) {
//                Kxlib_DebugVars([56,$(e).data("tgs-crd")]);
                if (! KgbLib_CheckNullity($(e).data("tgs-crd")) ) {
                    th.UpdateThisTimeSpies(e); 
                }
            });
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    };
    
    this.GetUnit = function (v) {
        
        if ( KgbLib_CheckNullity(v) )
            return;
        
        //Permet de savoir quelle est l'unité de temps correspond au pas à effectuer
        //return [code,valeur_dans_la_langue_de_luser]
        switch(v) {
            case 20000: //20 secondes
                    return ['s',Kxlib_getDolphinsValue("tg_unit_sec")];
                break;
            case 60000: //1 min1
                    return ['m',Kxlib_getDolphinsValue("tg_unit_min")];
                break;
            case 3600000: //1 heure
                    return ['h',Kxlib_getDolphinsValue("tg_unit_hour")];
                break;
            case 86400000: //1 jour
                    return ['d',Kxlib_getDolphinsValue("tg_unit_day")];
                break;
            //L'évantualité d'avoir même plus qu'une journée est presque ubesque. Cependant, nous le prévoyant quand même.
        }
    };
    
    this.AddUnitSteps = function (st,uta) {
        //st = steps, uta = UnitToAdd ['code_unité_universel, deco]
        
        if ( KgbLib_CheckNullity(st) || KgbLib_CheckNullity(uta) ) {
            return;
        }
            
        //nv = NewValue; 
        var nv; 
        
        
        switch ( uta[0] ) {
            case 's' :  
                    /* Ici la valeur à ne pas dépassée est 2 car 60/20 = 3.
                     * Au delà de 3 on passe aux minutes, heures et ainsi de suite 
                     * Exemple : 4 => 1m20 car 4*20 = 80s => 1m20
                     * */
                    if ( st < 3 ){
                        nv = ((st*20000)/1000);
                        return [nv,uta[1]];
                    } else if ( st >= 3 && st < 180 ) {
                         nv = Math.floor(((st*20000)/60000));
                         
                         var fo = this.GetUnit(60000); 
                        
                         return [nv,fo[1]];
                    } else if ( st >= 180 && st < 4320 ) {
                        nv = Math.floor(((st*20000)/3600000));
                        
                        var fo = this.GetUnit(3600000); 
                         fo[1];
                         return [nv,fo[1]];
                    } else if ( st >= 4320 ) {
                        nv = Math.floor(((st*20000)/86400000));
                        
                        var fo = this.GetUnit(86400000); 
                         fo[1];
                         return [nv,fo[1]];
                    }
                    //Il déjà peu probable que la mise à jour concerne les unités de jours donc considérer allez au delà relève de la démence paranoiaque 
                       
                break;
            case 'm' :
            case 'h' :
            case 'd' :
                    return [st,uta[1]];
                break;
           /* NE SERT A RIEN (m,h et d sont par pas de 1) , MAIS ON GARDE LE CODE AU CAS OU.     
            case 'm' :
                    /* Ici la valeur à ne pas dépassée est 12 car 3600/300 = 12.
                     * Au delà de 12 on passe aux heures, jours et ainsi de suite 
                     * Exemple : 4 => 1m20 car 4*20 = 80s => 1m20
                     * 
                    if ( st < 12 ){
                        nv = ((st*300000)/60000)+""+uta[1];
                        return [nv,uta[1]];
                    } else if ( st >= 12 && st < 288 ) {
                         nv = Math.floor(((st*300000)/3600000));
                         
                         var fo = this.GetUnit(3600000); 
                         fo[1];
                         return [nv,fo[1]];
                    } else if ( st >= 288 ) {
                        nv = Math.floor(((st*300000)/86400000));
                        
                        var fo = this.GetUnit(86400000); 
                         fo[1];
                         return [nv,fo[1]];
                    }
                break;
            case 'h' :
                    /* Ici la valeur à ne pas dépassée est 12 car 86400/3600 = 24.
                     * Au delà de 24 on passe aux jours
                     * Exemple : 4 => 1m20 car 4*20 = 80s => 1m20
                     * 
                    if ( st < 24 ){
                        nv = ((st*3600000)/3600000)+""+uta[1];
                        return [nv,uta[1]];
                    } else if ( st >= 24 ) {
                         nv = Math.floor(((st*3600000)/86400000));
                         
                         var fo = this.GetUnit(86400000); 
                         fo[1];
                         return [nv,fo[1]];
                    }

                break;
            case 'd' :
                    /* Ici, il n'y a pas de valeur à ne pas dépassée. Sinon il faudrait metre à jour suivant les années c'est IMPOSSIBLE.
                     * Cela supproserait que l'utilisateur n'ait pas rechargé sa page depuis plus de 365 jours.
                     * De plus, tant mieux que l'on ne gère pas les valeurs superieures, cela nous évite de travailler sur les années (bissextiles ou pas ?)
                     * 
                    nv = ((st*86400000)/86400000)+""+uta[1];
                    return [nv,uta[1]];
                break;
                */
        }
        
        return res;
    };
    
    //STAY PUBLIC
    this.UpdateThisTimeSpies = function (o) {
        //Ce spy existe SINON return
        if ( KgbLib_CheckNullity(o) ) {
            return;
        }
        
//        Kxlib_DebugVars(["Start updating an element TIMEGOD_SPY"]);
//        alert("Controle Unique TMG_ELMNT");

        /*
         * ETAPE :
         * Récupérer le TIME
         * r = result
         * r est un tableau [tgs-crd,tgs-dd-atn,tgs-dd-uut]
         *  -> tgs-crd : Date de création de l'article en UTC+0
         *  -> tgs-dd-atn : Date affichée après la dernière mise à jour
         *  -> tgs-dd-uut : Date affichée après la dernière mise à jour en USER_TMZ
         *  
         * Les valeurs TIME est exprimées en valeur TIMESTAMP.
         *  
         * L'une des deux valeurs (tgs-dd-atn ou tgs-dd-uut) contenues dans la variale peut être NULL. 
         */
//        Kxlib_DebugVars([11,$(o).data("tgs-crd")]);
        var r = _f_SpyGetTm(o);
        
        if ( typeof r === "undefined" ) {
            //La valeur Object envoyée pour récupérer le temps était null
            return;
        } 
        /*else if ( !r ) {
            //Sinon (Si FALSE et UNDEFINED) récupérer le code erreur ET signaler au serveur suivant le code
            _f_SigErrToSrv(this.codeErr);
        } else if (! r[0] ) {
            r = r[1];
            //Pour éviter les erreurs en aval, on transforme r[1] en 0 s'il est NULL
            r[1] = ( typeof r[1] !== "undefined" ) ? r[1] : 0;
//            alert(r[1]);
//            alert(r[2]);
            _f_SigErrToSrv(this.codeErr);
        }
        //*/    
       /*
        //Si on n'a pas accès au time version DD_USER_UTC ou DD_AN, on tente de récupérer le GMT pour l'appliquer 
        if ( KgbLib_CheckNullity(r[1]) || KgbLib_CheckNullity(r[2]) ) { 
            
//            Kxlib_DebugVars(["Des données sont signalées comme manquant. Tentative de récupération du fuseau-horaire auprès du serveur"]);
            /* On récupère le fuseau horaire.
             * Le fuseau horaire est récupéré depuis le serveur sauf si le CALLER demande à récupére le fuseau horaire en locale (unsafe).
             *
             * Lorsqu'on demande à avoir le fuseau horaire depuis le serveur et qu'on est dans l'incapacité de l'avoir, on prend celui en local.  
             * *
            if ( KgbLib_CheckNullity(this.UTCsrc) || this.UTCsrc === 's' ) {
                
                //On demande le bon UTC au serveur
                //cr = Carrier. Celui sur lequel sera déclenché l'évènement
                var $cr = $("<span/>");

//                this.GetTmzFromSrv($cr); //[NOTE 09-10-2014] @author L.C. 
                var tmz,tar = o;
                
                var th = this;
                $cr.on("datasready", function(e,d) {
                    //On récupère l'UTC
                    tmz = d[0];
                    /*
                     * Le retour est forcement un string sous la forme (-/+)number
                     * Exemple: -1, +4
                     *
                    
                    th.SRV_TMZ = th.ConvertSrvTimezone(tmz);
                    
//                    alert('Apres conversion => '+th.SRV_TMZ);
                    if ( KgbLib_CheckNullity(th.SRV_TMZ) ) return;
                    
                    var tz;
                    //On vérifie si on est pas dans le cas où on est obliger d'utiliser un TIMEZONE (Voir DetectTimezConflict)
//                    alert("Check Force in 1 > "+ typeof _frcTmz === "number");
                            
                    if ( !KgbLib_CheckNullity(_frcTmz) && typeof _frcTmz === "number" ) 
                        tz = _frcTmz;
                    else 
                        tz = th.SRV_TMZ;
                    
//                    alert("TMZ in HERE 1 => "+tz);
                    
                    //Lancer la mise à jour
                    _f_TryUpdPrcs(tar,r,tz);
                    
                });

                $cr.on("noanswer, datasmissing", function(e) {
                    //On déclare l'élement comme CRACKED
                    this.codeErr = "CRACKED";
                    
                    //TODO: Contacter le serveur
                });

            } else {
                //TODO : UTC local
            }
        } else {
            var tz;
        //*/    
            /*
             * ETAPE :
             * On vérifie si on est pas dans le cas où on est obligé d'utiliser un TIMEZONE (Voir DetectTimezConflict)
             */
            //*
            if ( !KgbLib_CheckNullity(_frcTmz) && typeof _frcTmz === "number" ) {
                tz = _frcTmz;
//                alert('TIME in HERE 2 => '+tz);
            } else {
                
                //On calcule la différence (TIMEZONE) de temps à appliquer. La valeur est fournie en heure.
                tz = _f_CalTmzFrmLclSpecs(r[1],r[2]);
//                if (! $("#unique-max").hasClass("this_hide") ) Kxlib_DebugVars(["DEBUG BUG TIME MOZ 1=> "+tz],true); //Reste de debug (23-07-14)
            }
            //*/
//            alert('TIME in HERE 4 => '+typeof _frcTmz);
            _f_TryUpdPrcs(o,r,tz);
//        }
        
    };
    
    var _f_TryUpdPrcs = function (o,r,tz) {
//    this.TryUpdateProcess = function (o,r,tz) {
        
        try {
            
            /*
             * r[0] : TimeStamp (UTC+0) correspondant à la création de l'Article; 
             * r[1] : TimeStamp de la date affichée avec UTC+0; 
             * r[2] : TimeStamp de la date affichée avec USER_TIMEZONE
             */
            
            //On calcule la différence entre l'heure actuelle et la date de création disponible au niveau de TGSPY
            var ct = _f_GetNowTimeAtNullUTC();
            var dfcd = ct - parseInt(r[0]);
            
            //dfcd => DifferenceFromCreationDate
            //Si l'objet a été créé recemment pas besoin de faire une mise à jour par rapport à la dernière mise à jour
            if ( dfcd >= 0 && dfcd < 20000 ) {
//                Kxlib_DebugVars(["TG a décidé que l'élément est considéré comme ayant été créé à l'instant"]);
//                    alert("mise a jour autorisé : NOW");
                //Mise à jour des valeurs en timestamp 
                //en UTC+0
                //On signale donc que l'on a jamais mis à jour le TGPSY
                $(o).data("tgs-dd-atn", 0);
                
                //en User_UTC
                $(o).data("tgs-dd-uut", tz);
                
                //value = Now
                var svl = Kxlib_getDolphinsValue("TG_TIME_NOW");
                $(o).find(".tgs-uni").html(svl);
                $(o).find(".tgs-val").html(0);
                $(o).find(".tgs-frm").addClass("this_hide");
                $(o).find(".tgs-val").addClass("this_hide");
            } else if (dfcd > 20000) {
//            Kxlib_DebugVars(["TG debute le calcule de l'unité de temps à ajouter"]);
                
                //On vérifie si le décallage dépasse une journée
                if (dfcd >= 86400000) {
                    //Est ce que la valeur est superieure à au moins une journée
                    
                    //Check du mode d'affiche
                    if (_dis_days_mode === __DIS_TIME_MODE_INTRV) {
                        //continue
                    } else {
                        /* On ne fait pas totalement confiance aux réglage de fuseau-horaire en local */
                        //SpecialCurrentDate
                        try {
                            var scd = new KxDate(r[0]);
                            scd.SetUTC(tz);
                            //SpecialNow
                            var snw = new KxDate();
                            snw.SetUTC(tz);
                        } catch (e) {
                            //TODO : Déclencher une erreur technique ?
                        }
                        
                        
                        //Est ce qu'on est toujours dans la même année
                        //TreatYear
                        var trty = (scd.getFullYear() !== snw.getFullYear()) ? true : false;
//                    alert("Creation => "+scd.getFullYear()+"; Now => "+snw.getFullYear());
                        var nvl, nvl_m = Kxlib_getDolphinsValue("TG_MONTH_" + scd.getMonth());
                        
                        if (!trty) {
                            
                            //Selon la langue de la date
                            switch (Kxlib_getDolphinsValue("TG_USER_TMLANG")) {
                                case "UNV" :
                                    //Selon le format que nous qualifions d'universel
                                    nvl = scd.getDate() + " " + nvl_m;
                                    break;
                                case "ENG" :
                                    //Selon le format que nous qualifions d'Anglais
                                    nvl = nvl_m + " " + scd.getDate();
                                    break;
                                default:
                                    //Universelle
                                    nvl = scd.getDate() + " " + nvl_m;
                                    break;
                            }
                        } else //Tout le monde pareil
                            nvl = scd.getDate() + " " + nvl_m + " " + scd.getYear();
                        
                        
                        var natn = _f_GetNowTimeAtNullUTC();
                        var nuut = natn + (tz);
                        
                        $(o).data("tgs-dd-atn", natn);
                        
                        //en User_UTC
                        $(o).data("tgs-dd-uut", nuut);
                        
                        nvl = (KgbLib_CheckNullity(nvl)) ? "" : nvl;
                        
                        $(o).find(".tgs-frm").addClass("this_hide");
                        $(o).find(".tgs-val").html(nvl);
                        $(o).find(".tgs-val").removeClass("this_hide");
                        $(o).find(".tgs-uni").addClass("this_hide");
                        
                        return true;
                    }
                } 
                
                
                //On soumet la différence au révélateur qui va permettre de savoir dans quel intervalle de temps nous nous trouvons
                var rs = this._GetProperLoopTimeRef(dfcd);
                //Le résultat va nous permettre de nous dire de combien nous allons devoir avancer notre temps. Ou pas !
                
                //dflu => DifferenceFromLastUpdate
                //On calcule la différence entre la date de dernière mise à jour et maintenant
                var dflu = ct - r[1];
                
                /* 
                 * On vérifie que r[1] n'est pas égale à 0. Cela peut être du au fait qu'il n'a pas été créer et la plateforme l'a "réparé" 
                 * Dans ce cas, on définit dflu à 1, à cause des décallage de traitement), sinon tout l'algorithme sera inopérant.
                 * */
                dflu = (r[1] === 0) ? 1 : dflu;
                
                //*
//                
                //*/
                /* Exemple : 
                 *      La difference entre la date de creation et la date actuelle est -> 305000
                 *      L'unité de pas servant à la mise à jour est de -> 300000 (5min)
                 *      Last update est 240000
                 *  Pour savoir s'il faut mettre à jour la valeur, on fait on détermine si la différence entre la date de création et la date de la (...)
                 *  dernière mise à jour est positive ou nulle (sinon il s'agit d'un defaut de conception) ET si (...)
                 *  (...) cette différence est inferieur au dernier repère de mise à jour qui aurait du etre fait.
                 *  Exemple : Dans le cas des pas pour chaque 5 minutes, les repères sont : 5,10,15,20,25 etc ...
                 *  
                 *  Pour calculer se repère on utilise la formule
                 *  repère = quotient(dfcd/unite_de_pas)*unite_de_pas
                 *  
                 *  Puis, on s'interroge si dflu < repère  
                 *  
                 *  Exemple :
                 *  quotient(305/300)*300 = 300; 240 < 300 => Il faut mettre à jour
                 *  
                 *  
                 * * * */
                
                /* [NOTE 28-05-14] C'est la méthode qui me semble la plus optimale. Les autres étaient boiteuses. */
                var st = Math.floor(dfcd / rs);
                
                //lrp = LastRePere
                var lrp = st * rs;
                
//                alert("DFCD => "+dfcd+"; DFLU => "+dflu+"; UNIT => "+rs+"; STEPS => "+st+"; LAST_REPERE => "+lrp);
                if (dflu > 0 && dflu < lrp) {
//                    alert("mise a jour autorisé > 20 SECONDES");
//                    alert("DFCD => "+dfcd+"; Unite de pas => "+rs);
                    //La mise jour est applicable
//                Kxlib_DebugVars(["Application de la mise à jour temporelle"]);
                    
                    var uta = this.GetUnit(rs); 
                    /* Calcul du nombre d'unités à ajouter */
                    //Il est primordiale de calculer la bonne valeur car on avance pas toujours par pas de 1 (...)
                    // (...) cela dépend de l'unité
                    var rr = this.AddUnitSteps(st, uta);
                    
                    /* 
                     * L'unité de pas (step) ne sert qu'à mettre à jour la partie à afficher auprès de l'utilisateur.
                     * En ce qui concernela date effective c'est la date actuelle.
                     * Pour se rapporcher au plus près de la date on redemande la date actuelle car entre temps il s'est passé plusieurs millisecondes
                     * */
                    var natn = _f_GetNowTimeAtNullUTC();
                    //On applique le TZ
//                    var nuut = natn + (tz*3600000);
                    var nuut = natn + (tz);
//                        alert(nuut);
//                    return;
                    //Affichage des données
//                    alert("LUP_UTC => "+natn+"; LUP_UUTC => "+nuut+"; STEPS => "+st+"; VALUE => "+rr[0]+"; UNIT => "+rr[1]);
                    this._DisplayTime(o, natn, nuut, rr[0], rr[1]);
                }
            }
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }

    };
    
    
    /****************** ACQUIRE ****************/
    var _f_SpyGetTm = function (o) {
//    this._SpyGetTime = function (o) {
        /*
         * Structure de o dans le DOM
         * <span class='kxlib_tgspy' data-tgs-crd='' data-tgs-dd-atn='' data-tgs-dd-uut=''>
         *      <span class='tgs-frm'></span>
         *      <span class='tgs-val'></span>
         *      <span class='tgs-uni'></span>
         * </span>
         * * * * */
        //alert("CREATION => "+$(o).data("tgs-crd") );
//        alert(KgbLib_CheckNullity(o));
        if ( KgbLib_CheckNullity(o) ) {
            return;
        }
//        Kxlib_DebugVars([97,$(o).data("tgs-crd")]);
        try {
            
//        alert(KgbLib_CheckNullity(o));
            //Vérifier s'il y a TimeStamp
            //tsp_crd = TimeGodSpy_CreationDate (UTC0); tsp_dd_an = TimeGodSpy_TimeStamp_AtNull (UTC0); tsp_dd_uut = TimeGodSpy_TimeStamP_User-UTC;
            var tsp_crd = $(o).data("tgs-crd"),
                tsp_dd_atn = $(o).data("tgs-dd-atn"),
                tsp_dd_uut = $(o).data("tgs-dd-uut");
            
            //rt = ReturnTable : [Time_User_UTC, Tiùe_Utc0]
            var rt = new Array();
            
            rt[0] = (! KgbLib_CheckNullity(tsp_crd) ) ? tsp_crd : null;
            rt[1] = (! KgbLib_CheckNullity(tsp_dd_atn) ) ? tsp_crd : null;
            rt[2] = (! KgbLib_CheckNullity(tsp_dd_uut) ) ? tsp_dd_uut : null;
            
            /*
             if ( tsp_dd_atn ) {
             rt[1] = $(o).data("tgs-dd-atn");
             } else {
             //On insère une variable null
             var foo;
             rt[1] = foo;
             }
             
             if ( tsp_dd_uut ) {
             rt[2] = $(o).data("tgs-dd-uut");
             } else {
             //On insère une variable null
             var ran;
             rt[2] = ran;
             }
             //*/
            
            /*
             * ETAPE : 
             * On verifie la conformité
             * RAPPEL : 
             *  > tgs-val : La valeur affichée donc visible par l'utilisateur; tgs-uni : L'unité de temps a affiché (now, jours, heures, minutes, secondes)
             *  > Si la date de création  n'est pas accessible => CRACKED (On ne peut pas calculer la différence sans elle)
             */
            if (! tsp_crd ) {
                return;
            }
            /*
            if (! tsp_crd ) {
                //goto : Signaler au serveur que les Components sont deffectueux.
                this.codeErr = "cracked";
                return false;
            } else if (! (tsp_dd_atn && tsp_dd_uut) ) {
                /*
                 * NOTE :
                 * Si on a pas le couple DISPLAYED_DATE (UTC+0) et DISPLAYED_DATE (User UTC) => COMPLIANT
                 * Ce couple permet notamment de déterminer le fuseau horaire appliqué à l'utilisateur actif par le serveur en faisant la différence entre les deux
                 * On ne déclare pas le composant comme CRACKED car on peut toujours demander le TIMEZONE auprès du serveur.
                 * Ces deux valeurs ne nous permettent que de calculer le TMZ.
                 * Si le serveur ne nous permet pas d'obtenir le bon fuseau horaire on pourra déclarer l'élément comme CRACKED
                 *
                if (! KgbLib_CheckNullity($(o).find(".tgs-val").html()) ) {
                    //goto : Signaler au Serveur que les Components ne sont pas conformes
                    this.codeErr = "compliant";
                }
                
                var rt2 = [false, rt];
                
                return rt2;
            }
            //*/
            return rt;
            
        } catch (ex) {
            Kxlib_DebugVars([ex,ex.lineNumber],true);
        }
    
    };
    
    this._ConvertTsToTime = function () {
        
    };
    
    /****************** VIEW ********************/
    
    this._DisplayTime = function (o,natn,nuut,val,uni) {
//        alert("TOTO : "+$(o).data("tgs-dd-atn"));
       /* Mise à jour des valeurs en timestamp */
       
        try {
//          Kxlib_DebugVars([natn,nuut,f,uni,val],true);
            
            //Heure de mise à jour en UTC+0
            $(o).data("tgs-dd-atn", natn);
            
            //Heure de mise à jour en User_UTC
            $(o).data("tgs-dd-uut", nuut);
            
            //Valeur affichée auprès de l'utilisateur
            var f = Kxlib_getDolphinsValue("TG_FRM");
            $(o).find(".tgs-frm").html(f);
            $(o).find(".tgs-uni").html(uni);
            $(o).find(".tgs-val").html(val);
            
            $(o).find(".tgs-uni").removeClass("this_hide");
            //Il peut arriver que la case représentant les valeurs soit cachée. On s'assure qu'elle ne l'est pas.
            $(o).find(".tgs-val").removeClass("this_hide");
            $(o).find(".tgs-frm").removeClass("this_hide");
        } catch (e) {
            Kxlib_DebugVars([e],true);
        }

       
    };
    
    /****************** TRAITEMENT RELATIFS AU FUSEAU HORAIRE ******************/
    
    /**********************************************************/
    this.DetectTimezConflict = function () {
        /* Vérifie si le fuseau-horaire au niveau du serveur est toujours le même que celui au niveau local */
        
        /* Pour justifier les décisions prises dans cette méthode, un indice 29-05-14 */
        
        var el = $("<span/>"), th = this;
        
        this.GetTmzFromSrv(el);
        
        el.on("datasready", function(e,d) {
            
            //On récupère l'UTC
            var tmz = d[0];
//             alert("Check => "+tmz);
            /*
             * Le retour est forcement un string sous la forme (-/+)number
             * Exemple: -1, +4
             */

            /*****/
            var SRV_TMZ = th.ConvertSrvTimezone(tmz);
//            alert("Check => "+SRV_TMZ);
            if ( KgbLib_CheckNullity(SRV_TMZ) )
                return;
            
//            alert("Conflict Server TMZ => "+SRV_TMZ);
            /*****/
           var LOC_TMZ = th.GetTimezoneFromLocal();           
//            var LOC_TMZ = 6200000;     // For test      
//            alert("Conflict Local TMZ => "+LOC_TMZ);
            if ( KgbLib_CheckNullity(LOC_TMZ) )
                return;
            
            /*****/
            //On effectue la comparaison
            if ( SRV_TMZ !== LOC_TMZ ) {
//                alert("CONFLICT !!!!");
                //On demande au serveur si l'Utilisateur actif à procéder à des changements au niveau de sa ville de résidence dans les 10 mins
                var el = th.LocChanged(10), nth = th;
                
                if ( KgbLib_CheckNullity(el) )
                    return;
               
                
                el.on("datasready", function(e,d) {
                    var rr = d[0];
//                    alert("Ici4 => "+rr);
                    
                    rr = ( rr === "1" || rr === "true" ) ? true : false;
                    
                    if ( rr ) {
                        /* Déclencher WHITEBOARD pour signaler à l'utilisateur qu'il a effectué des changements importants au niveau de ses paramètres.
                         * Cela l'oblige à mettre à jour la SESSION actuelle. Pour cela, il doit redémarrer !
                         * */

                        //v = [title:"title",message:"message",(Quitter la page et aller ailleurs)fly:"fly", redir:"redir"]
                        var ws = new WhiteBoardDialog(), m = Kxlib_getDolphinsValue("err_com_tmz_conflict_msg"), t = Kxlib_getDolphinsValue("err_com_tmz_conflict_title");
                         
                        var v = {
                            "title":t,
                            "message":m,
                            "fly":"",
                            "redir":"reload"
                        };
                        ws.Dialog(v);
                        
                    } else {
                        //On force le changement du fuseau-horaire
                        n_frcTmz = SRV_TMZ;
//                        alert('Force_Zone => '+n_frcTmz);
//                        alert(n_frcTmz);
                    }
                });
                
            } else {
                //On remet la valeur à NULL pour dire de ne plus forcer le fuseau horaire.
                //Seulement dans le cas où la valeur était non NULL au paravant
                
                if (typeof _frcTmz !== "undefined")
                    _frcTmz = null;
            }
                
        });
        
    };
    
    
    /**********************************************************/
    //URQID => Get Local Timezone from Server
    this.gltz_uq = "GET_LOCAL_TMZ";
    this.gltz_ajaxr = Kxlib_GetAjaxRules(this.gltz_uq);
    
    this.GetTmzFromSrv = function (el) {
        if( KgbLib_CheckNullity(el) ) 
            return;
        
        var th = this;
       
        var onsuccess = function (datas) {
//            alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                if (! KgbLib_CheckNullity(datas) )
                    datas = JSON.parse(datas);
                else {
                    var t = (new Date()).getTime().toString();
                    $(el).trigger("noanswer",[t]);
                    return;
                }
                
                if(! KgbLib_CheckNullity(datas.err) ) {
                    //alert(datas.err); //DEBUG : Afficher l'erreur
                    //Sinon ne rien faire, la plateforme va récupérer le fuseau horaire au niveau du Client 
                }
                    
                //Si on a le tmz on déclenche l'event
                if ( !KgbLib_CheckNullity(datas.tmz) ) {
                    var tp = [datas.tmz];
                    $(el).trigger("datasready",[tp]);
                    return;
                } else {
                    //Sinon signaler que l'on a pas tmz avec 'datasmissing'
                    $(el).trigger("datasmissing");
                    return;
                }
            } catch (e) {
                //TODO : ?
            }
        };

        var onerror = function(a,b,c) {
            /*
             * [DEPUIS 26-08-15] @author BOR
             */
            Kxlib_AjaxGblOnErr(a,b);
            
            //TODO : ?
        };
        // L'identifiant n'est pas obligatoire. L'utilisateur actif est détec
        var toSend = {
            "urqid": th.gltz_uq,
            "datas": {
            }
        };

        Kx_XHR_Send(toSend, "post", th.gltz_ajaxr.url, onerror, onsuccess);
    };
    
    this.ConvertSrvTimezone = function (v) {
        /* 
         * Convertit le TMZ renvoyé depuis le serveur en TMZ valide au niveau du FE 
         * 
         * Le fuseau-horaire peut être sous forme 5:30. Cela signifie 5h30mins
         * Dans ce cas on récupère les minutes dans la var mi
         * */
        if ( KgbLib_CheckNullity(v) )
            return;
        
        
        //tr = ToReturn; h = hour; m = minute; ml = Multiplicateur pour avoir le bon signe (default c'est + donc 1)
        var h = 0, mi = 0, ml = 1;
        //
        if ( v.toString().indexOf("-") !== -1 ) {
            //On signale que le fuseau-horaire sera negatif
            ml = -1; 
            //Retirer le signe
            v = v.toString().replace('-',"");
        } else if ( v.toString().indexOf("+") !== -1 ) {
            //Retirer le signe
            v = v.toString().replace('+',"");
        } 
        
        if ( v.toString().indexOf(":") !== -1 ) {
            var tp = v.toString().split(':');
            //Si l'utilisateur envoit 5:2 on le convertit en 5:20
            mi = ( tp[1].length === 1 ) ? parseInt(tp[1])*10 : parseInt(tp[1]);
            
            h = parseInt(tp[0]);
            
            return ((h*3600000)+(mi*60000))*ml;
        } else 
            return ((parseInt(v)*3600000))*ml;
                
    };
    
    this.GetTimezoneFromLocal = function () {
        
      //On crée une date à UTC + 0
      var atn = _f_GetNowTimeAtNullUTC();
      //On crée une date à USER_UTC
      var nd = _f_GetNowTimeAtLocalUTC();
//      alert('atn => '+(new Date(atn)).getHours()+"; nd => "+(new Date(nd)).getHours());
      //On fait le difference et on renvoie le résultat
      
      return nd-atn;
    };
    
    this.TmzEqual = function () {
        //TODO : Permet de s'assurer que deux TMZ sont identiques. Permet de détecter si l'UTC locale est fiable
    };
    
    var _f_CalTmzFrmLclSpecs = function (t1,t2) {
//    this.CalculTmzFromLocalSpecs = function (t1,t2) {
        //TODO : Permet de déterminer l'UTC appliqué à une Session à partir des TIME1 (mode UTC0) et TIME2 (mode UserUtc)
        /*
         * [NOTE 23-07-14] On met 0 pour éviter de faire naitre des erreurs en aval.
         * Les erreurs liées à la datation ne sont pas des erreurs graves.
         * Une opération de refctorisation viendra réparer ces incohérences.
         */
        return ( typeof t1 !== "number" || typeof t2 !== "number" ) ? 0 : (t2-t1);
        
    };
    
    this.DecideOnProperTimezone = function () {
        //TODO : Décide s'il faut utiliser le TMZ obtenu depuis le poste client ou serveur. Le récupérer au niveau local réduit la charge serveur
    };
    
    /******************** AVERTIR SERVEUR SUR LES ERREURS ou AVERTISSEMENTS *****************/
    
    var _f_SigErrToSrv = function(a) {
//    this._SignalErrToServer = function(a) {
        switch (a) {
            case "compliant" :
                    this._SignalNonCompliantComponents();
                break;
            case "craked" :
                    this._SignalCrackedComponents();
                break;
        }
    };
    
    /*************************************************/
    /*
    //URQID => Signaler Erreur TIME_COMPLIANT au serveur
    this.sct_uq = "SIG_COMPLIANT_TIME";
    this.sct_ajaxr = Kxlib_GetAjaxRules(this.sct_uq);
    this._SignalNonCompliantComponents = function () {
        var th = this;
        
        var onsuccess = function (datas) {
            //alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                datas = JSON.parse(datas);
            } catch (e) {
                
            }
                        
            if (! KgbLib_CheckNullity(datas.err) ) {
                //A la beta on signale 
                var eo = new ErrorBarVTop(), m = Kxlib_getDolphinsValue("err_com_failajax_sys");
                        
                eo.EB_DeclareErr("#err_bar_in_trpg",m);
                datas.err;
            }
            
        };

        var onerror = function(a,b,c) {
            //TODO : ?
        };

        var toSend = {
            "urqid": th.sct_uq,
            "datas": {
                "err":th.sct_ajaxr.datas
            }
        };

        Kx_XHR_Send(toSend, "post", th.sct_ajaxr.url, onerror, onsuccess);
    };
    //*/
    /************************************************/
    /*
    //URQID => Signaler Erreur TIME_CRACKED au serveur
    this.scrt_uq = "SIG_CRACKED_TIME";
    this.scrt_ajaxr = Kxlib_GetAjaxRules(this.createNewTr_uq);
    
    this._SignalCrackedComponents = function () {
        //TODO : Send Element id
        
        var th = this;
        
        var onsuccess = function (datas) {
            //alert("CHAINE JSON AVANT PARSE"+datas);
            try {
                datas = JSON.parse(datas);
            } catch (e) {
                //TODO : Send error to server
            }
                        
            if(! KgbLib_CheckNullity(datas.err) ) {
                //A la beta on signale 
                var eo = new ErrorBarVTop(), m = Kxlib_getDolphinsValue("err_com_failajax_sys");
                        
                eo.EB_DeclareErr("#err_bar_in_trpg",m);
                datas.err;
            }
                
        };

        var onerror = function(a,b,c) {
            //TODO : ?
        };

        var toSend = {
            "urqid": th.scrt_uq,
            "datas": {
                "err":th.scrt_ajaxr.datas
            }
        };

        Kx_XHR_Send(toSend, "post", th.scrt_ajaxr.url, onerror, onsuccess);
    };
    //*/
    /************************************************/
    this.STUS = function(s){
        /*
         * Cette méthode permet d'obtenir l'heure du serveur de référence à un instant t donné.   
         * Cette heure est hautement fiable car elle est "lissée".
         * En effet, la méthode permet de ne pas prendre en compte le temps de latence entre la demande et la reception.
         */
        if ( KgbLib_CheckNullity(s) ) {
            return;
        }
        //LCT = LoCalTime
        var lct = (new Date()).getTime();
        var Ajax_STUS = Kxlib_GetAjaxRules("STUS");
        var onsuccess = function (d) {
        try {
                if (! KgbLib_CheckNullity(d) ) {
                    d = JSON.parse(d);
                } else {
                    return;
                }
                
                if ( !KgbLib_CheckNullity(d.err)) {
                    _xhr_sbms = null;
                    if (Kxlib_AjaxIsErrVolatile(d.err)) {
                        switch (d.err) {
                            default:
                                    Kxlib_AJAX_HandleFailed();
                                return;
                                break;
                        }
                    } 
                    return;
                } else if (! KgbLib_CheckNullity(d.return) )  {
                    var ltcy = (new Date()).getTime() - lct; 
                    var tm = d.return - ltcy; // Lissage
                    rds = [tm];
                    $(s).trigger("datasready",rds);
                } else {
                    return;
                }
                
            } catch (ex) {
                //TODO : Envoyer l'erreur et les données connexes au serveur pour traitement
//                Kxlib_DebugVars([ex],true);
//                Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");
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
        
        var u = document.URL;
        var toSend = {
            "urqid": Ajax_STUS.urqid,
            "datas": {
                "curl": u 
            }
        };
        Kx_XHR_Send(toSend, "post", Ajax_STUS.url, onerror, onsuccess);
    };
    
}

//autoexec
(function(){
    var Tg = new TIMEGOD();
    
    /* On crée les données de Test */
//    Tg.InitTestDatas();
    
    //Lancer la mise de la mise à jour dès le chargement de la page
    Tg.UpdSpies();
    
    //lt = looptime
    var lt = Tg.GetGenLoopTimeRef();
    //alert(lt);
    //Lancer le controle automatique 
    setInterval(function(){
//        alert("controle TIMEGOD");
        Tg.UpdSpies();
    },lt);
//    },5000);

    
    //Lancer le controle du fuseau-horaire
    /*
    setInterval(function(){
        Tg.DetectTimezConflict();
    },Tg.loopTmzConflict);
    //    },1000);
    //*/
    
})();
