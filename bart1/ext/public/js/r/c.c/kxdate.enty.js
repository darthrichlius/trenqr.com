/* L'Interêt de refaire un Objet Date réside dans le fait qu'utiliser celui natif ne correspond pas à nos attentes.
 * En effet, lorsque l'on souhaite obtenir la date courante en UTC+0 la tâche devient ardue.
 * 
 * Exemple : 
 *      var n = new Date(); //Convertira la date au fuseau horaire local.
 *      
 * Or que notre désire est d'avoir une date à UTC+0. 
 * Cette Classe permet d'avoir les valeurs directement à UTC+0 à partir (pour l'instant) du timestamp.
 * Mais encore, on peut lui passer un UTC et il sera capable de nous fornir l'heure que nous souhaitons à l'UTC+0 souhaité.
 * Cela même pour le TimeStamp.
 * 
 * Exemple : 
 *      var n = new KxDate(); //Crée la date au fuseau UTC+0
 *      var n = new KxDate(true); //Crée la date au fuseau local
 *      var n = new KxDate(869542200); //Crée la date à partir du Timestamp au fuseau UTC+0
 *      
 *      var n = new KxDate();
 *      n.setUTC ("3:50");
 *      Kxlib_DebugVars([.getHours]); //Renvoie l'heure à UTC+3:50
 *      n.setUTC (false); //Reinitialise l'UTC. On aurait aussi pu mettre 0;
 *      Kxlib_DebugVars([.getHours]); //Renvoie l'heure à UTC+0
 *      n.setUTC (3600000); //Renitialise UTC+0 à UTC+1
 *      n.setUTC (true); //Renitialise UTC+0 à UTC local
 *      
 *      n.setUTC(); //Remet la date à UTC+0
 *      n.getTime() //Renvoie le TimeStamp à UTC+0
 *      n.setUTC(+2);
 *      n.getTime() //Renvoie le TimeStamp à UTC+2
 *      n.setUTC(0);
 *      n.getTime() //Renvoie le TimeStamp à UTC+0
 * */

function KxDate (ml) {
    this.y;
    this.m;
    this.d;
    this.h;
    this.mi;
    this.s;
    this.ml;
    
    this.UTC = 0;
    
    //gt = given_time; On récupère la date envoyée en millisecondes
    this.gt;
    //Il s'agit du TIMESTAMP donné auquel on a appliqué l'UTC actif. C'est cette donnée qui est utilisée pour les calculs
    this.gtUTC;
//    this.gtUTC = 31539600000; //1 Jav 1971 à 1:0:0:0
//    this.gtUTC = 31535999999; //31 Dec 1970 à 23:59:59:999
//    this.gtUTC = 31535405000; //31 Dec 1970 à 23:50:00:000
//    this.gtUTC = 31536000000; //1 Jav 1971 à 0:0:0:0
    
    this.yr;
    
    this._ResetAllFields = function () {
        this.y = this.m = this.d = this.h = this.mi = this.s = this.ml = null;
    };
    
    /********************************** COMMONS **********************************/
    
    this.YearIsLeap = function (yr) {
        return ( (yr%400 === 0) || ( (yr%4 === 0) && !(yr%100 === 0) ) ) ? true : false;
    };
    
    this._GetLocalTMZ = function () {
        var foo = new Date(); //Heure locale
        var bar = new KxDate(); //Heule UTC+0

        /* On récupère le fuseau-horaire local */
        var tp1 = foo.getHours() - bar.getHours();
        tp1 *= 3600000;
        //Rappel : Il peut exister des fuseaux-horaires de type '5:30'
        var tp2 = foo.getMinutes() - bar.getMinutes();
        tp2 *= 60000;
        var tp3 = tp1+tp2;
        
        return tp3;
    };
    
    this.RemoveTimeFromTSTP = function () {
        /* Prend en paramètre un timestamp en millisecondes et renvoie un nombre ne prennant pas en compte la partie TIME.
         * 2/5/14 17:00:00:000 (1399050000000) devient 2414
         * 
         * Utile notamment dans le module NewsFeed pour grouper les éléments par date.
         * */
        
        var y = this.getYear(), m = this.getMonth(), d = this.getDate();
        return d.toString()+m.toString()+y.toString();
    };
    
    this.RemoveTimeFromTSTP2 = function () {
        /* Prend en paramètre un timestamp en millisecondes et renvoie un nombre ne prennant pas en compte la partie TIME.
         * 2/5/14 17:00:00:000 (1399050000000) devient 20140502
         * 
         * Utile notamment dans le module NewsFeed pour grouper les éléments par date.
         * 
         * [NOTE 13-03-14] @Lou
         * Cette fonction est une amélioration de RemoveTimeFromTSTP().
         * La méthode renvoyait des données impossibles à trier. En effet, la date du 18 Janv 15 était forcement inferieure à celle du 12 dec 14.
         * */
                
        var y = this.getFullYear(), m = this.getMonth(), d = this.getDate();
        m += 1;
        d = ( d < 10 ) ? '0'+d: d;
        m = ( m < 10 ) ? '0'+m: m;
        return y.toString()+m.toString()+d.toString();
    };
    
    this.WriteDate = function (u) {
        /* 
         * Permet d'obtenir une date écrite à partir d'une donnée TIMESTAMP.
         * La version actuelle de la méthode permet d'obtenir des formats de date spécifiques :
         *  *- La date d'aujourd'hui => "Aujourd'hui"
         *  *- La date d'hier => "Hier"
         *  *- Les autres dates => Sont affichées selon le format de la langue
         *  
         *  La date renvoyée fait référence au TMZ actuellement défini.
         * * */
        //sd = StringDate
        var sd = "";
        
        //Acquisition de la date d'aujourd'hui à UTC donné
        var auj = new KxDate();
        auj.SetUTC(this.UTC);
        
        //Acquisition de la date d'hier à UTC donné
        var tp = auj.getTime() - 86400000;
        
        var hier = new KxDate(tp);
        hier.SetUTC(this.UTC);
        
        
        //On vérifie s'il s'agit de date spécifique
        if ( (this.getDate().toString()+this.getMonth().toString()+this.getYear().toString()) === (auj.getDate().toString()+auj.getMonth().toString()+auj.getYear().toString()) ) {
            return Kxlib_getDolphinsValue("TIME_TODAY");
        } else if ( (this.getDate().toString()+this.getMonth().toString()+this.getYear().toString()) === (hier.getDate().toString()+hier.getMonth().toString()+hier.getYear().toString()) ) {
            return Kxlib_getDolphinsValue("TIME_YESTD");
        }
        
        if ( this.getYear() === auj.getYear() ) {
            //On regarde du coté de la définition de "TG_USER_TMLANG"
            if ( Kxlib_getDolphinsValue("TG_USER_TMLANG") === "UNV" ){
                sd = this.getDate().toString();
                sd += " ";
                sd += Kxlib_getDolphinsValue("TG_MONTH_"+this.getMonth().toString());
            
                return sd;
            } else {
                sd = Kxlib_getDolphinsValue("TG_MONTH_"+this.getMonth().toString());
                sd += " ";
                sd += this.getDate().toString();
            
                return sd;
            }
        } else {
            //On prend le format universel par défaut
            sd = this.getDate().toString();
            sd += " ";
            sd += Kxlib_getDolphinsValue("TG_MONTH_"+this.getMonth().toString());
            sd += " ";
            sd += this.getYear().toString();

            return sd;
        }
            
    };
    
    /***************************** GETTERS and PROCESS ***************************/
    
    this.getFullYear = function () {
        //yr = Year, année de départ; ytr = YearToRemove
        var yr = 1970, ytr, cry = 1970, lgt = this.gtUTC, halt = (new Date()).getFullYear();
        var anb = 31536000000;
        var ab = 31622400000; 
//        alert("RECEIVED => "+lgt);
        while (true) {
            if ( this.YearIsLeap(yr) ) {
                //Année bissextiles (366)
                ytr = ab;
            } else {
                //Année non bissextiles (365)
                ytr = anb;
            }
            
            lgt -= ytr;
            
            if ( lgt === 0 ) {
                this.y = ++yr;
//                alert("Année trouvée +1 => "+this.y);
//                alert("Reste +1 => "+lgt);
                //Il reste l'année d'après - 1 mls
//                var foo = [( this.YearIsLeap(this.y) ) ? ab-1 : anb-1,this.YearIsLeap(this.y)];
                return this.y; 
                break; //Parano
            } else if ( lgt < 0 ) {
                this.y = yr;
//                alert("Année trouvée 0 => "+this.y);
//                alert("Reste 0 => "+lgt);
//                var bar = [lgt,this.YearIsLeap(this.y)];
                return this.y; 
                break; //Parano
            }
            
            //Unité de secours
            if ( yr > halt ) {
//                alert("Sortie d'urgence => "+halt);
                break;
            }
            
            ++yr;
        }
        
        return this.y;
    };
    
    this.getYear = function () {
        var y = this.getFullYear();
        var ny = parseInt(y.toString().substr(2,2));
        return ny;
    };
    
    this._GetRestAfterYearOpe = function () {
        //yr = Year, année de départ; ytr = YearToRemove
        var yr = 1970, ytr, cry = 1970, lgt = this.gtUTC, halt = (new Date()).getFullYear();
        var anb = 31536000000;
        var ab = 31622400000; 
//        alert("RECEIVED => "+lgt);
        while (true) {
            if ( this.YearIsLeap(yr) ) {
                //Année bissextiles (366)
                ytr = ab;
            } else {
                //Année non bissextiles (365)
                ytr = anb;
            }
            var lgt_bf = lgt; 
            lgt -= ytr;
            
            if ( lgt === 0 ) {
                this.y = ++yr;
//                alert("Année trouvée +1 => "+this.y);
//                alert("Reste +1 => "+lgt);
                //On est passé à l'annnée d'après. On est à 0 millisecondes dans l'année d'après
                var foo = [0,(this.YearIsLeap(this.y))?ab:anb];
                return foo;
                break;
            } else if ( lgt < 0 ) {
                this.y = yr;
//                alert("Année trouvée 0 => "+this.y);
//                alert("Reste 0 => "+lgt_bf);
                var bar = [lgt_bf,(this.YearIsLeap(this.y))?true:false];
                return bar;
                break;
            }
            
            //Unité de secours
            if ( yr > halt ) {
//                alert("Sortie d'urgence => "+halt);
                this.y = yr;
                break;
            }
            
            ++yr;
        }
        
        return this.y;
    };
    
    this.getMonth = function () {
        var foo = this._GetRestAfterYearOpe();
        var c = 0, mtr = foo[0], isLeap = foo[1];
//        mtr = mtr;
//        alert("Reste in Month"+mtr);
        
        if (! $.isArray(foo) ) return;
        
        //Si on a pas au moins 31 jours, alors on est en janvier pas besoin de continuer
        if ( foo[0] < 2678400000 ) {
//            alert("janvier");
            return c;
        }
        
        for (c; c < 12; c++ ) {
            switch (c) {
                case 0:
                case 2:
                case 4:
                case 6:
                case 7:
                case 9:
                case 11:
                        //31 jours
                        mtr -= 31*86400000;
                    break;
                case 3:
                case 5:
                case 8:
                case 10:
                        //30 jours
                        mtr -= 30*86400000;
                    break;
                case 1:
                        //28 jours ou 29 jours
                        mtr -= ( isLeap ) ? 29*86400000 : 28*86400000;
                    break;
            }
//            Kxlib_DebugVars([tr]);
            if ( mtr === 0 ) {
                this.m = ++c;
//                alert("Rest of month +1 => "+mtr);
//                alert("Found month +1 => "+this.m);
                return this.m;
                break; //Paranoiaque
            } else if ( mtr < 0 ) {
                this.m = c;
//                alert("Rest of month 0 => "+mtr);
//                alert("Found month 0 => "+this.m);
                return this.m;
                break; //Paranoiaque
            }
        }
    };
    
    this._GetRestAfterMonthOpe = function () {
        var foo = this._GetRestAfterYearOpe();
        var c = 0, mtr = foo[0], isLeap = foo[1], mtr_bf = 0;
//        mtr = mtr;
//        alert("Reste in Month"+mtr);
        
        //Si on a pas au moins 31 jours, alors on est en janvier pas besoin de continuer
        if ( foo[0] < 2678400000 ) {
//            alert("janvier");
            return foo[0];
        }
        
        for (c; c < 12; c++ ) {
            
            mtr_bf = mtr;
            
            switch (c) {
                case 0:
                case 2:
                case 4:
                case 6:
                case 7:
                case 9:
                case 11:
                        //31 jours
                        mtr -= 31*86400000;
                    break;
                case 3:
                case 5:
                case 8:
                case 10:
                        //30 jours
                        mtr -= 30*86400000;
                    break;
                case 1:
                        //28 jours ou 29 jours
                        mtr -= ( isLeap ) ? 29*86400000 : 28*86400000;
                    break;
            }
            
//            Kxlib_DebugVars([tr]);

            if ( mtr === 0 ) {
                this.m = ++c;
//                alert("Rest of month +1 => "+mtr);
//                alert("Found month +1 => "+this.m);
                return 0;
                break; //Paranoiaque
            } else if ( mtr < 0 ) {
                this.m = c;
//                alert("Rest of month 0 => "+mtr);
//                alert("Found month 0 => "+this.m);
                return mtr_bf;
                break; //Paranoiaque
            }
        }
    };
    
    this.getDate = function () {
        var lft = this._GetRestAfterMonthOpe(), cn = 1; 
//        alert("In Date Left => "+lft);
        
        if ( lft < 86400000 ) {
            //Nous sommes le premier du mois
            return 1;
        }
        
        while (true) {
            
            lft -= 86400000;
            
            if ( lft === 0 ) {
                this.d = ++cn;
//                alert("Rest of date +1 => "+lft);
//                alert("Found Date +1 => "+this.d);
                return this.d;
                break; //Parano
            } else if ( lft < 0 ) {
                this.d = cn;
//                alert("Rest of date 0 => "+lft);
//                alert("Found Date 0 => "+this.d);
                return this.d;
                break; //Parano
            }
            
            //Securité
            if ( cn > 31 ) {
                break;
            }
            
            ++cn;
        }
    };
    
    this._GetRestAfterDateOpe = function () {
        var lft = this._GetRestAfterMonthOpe(), cn = 1, lft_bf = 0; 
//        alert("In Date Left => "+lft);
        
        if ( lft < 86400000 ) {
            //Nous sommes le premier du mois
            return lft;
        }
        
        while (true) {
            lft_bf = lft;
            
            lft -= 86400000;
            
            if ( lft === 0 ) {
                this.d = ++cn;
//                alert("Rest of date +1 => "+lft);
//                alert("Found Date +1 => "+this.d);
                return 0;
                break; //Parano
            } else if ( lft < 0 ) {
                this.d = cn;
//                alert("Rest of date 0 => "+lft);
//                alert("Found Date 0 => "+this.d);
                return lft_bf;
                break; //Parano
            }
            
            //Securité
            if ( cn > 31 )
                break;
            
            ++cn;
        }
    };
    
    this.getHours = function () {
        var lft = this._GetRestAfterDateOpe(), cn = 0, lft_bf = 0; 
//        alert("In Hours Left => "+lft);
        
        if ( lft < 3600000 ) {
            //L'heure correspond à minuit
            return 0;
        }
        
        while (true) {
            lft_bf = lft;
            
            lft -= 3600000;
            
            if ( lft === 0 ) {
                this.h = ++cn;
//                alert("Rest of Hours +1 => "+lft);
//                alert("Found Hours +1 => "+this.h);
                return this.h;
                break; //Parano
            } else if ( lft < 0 ) {
                this.h = cn;
//                alert("Rest of Hours 0 => "+lft);
//                alert("Found Hours 0 => "+this.h);
                return this.h;
                break; //Parano
            }
            
            //Securité
            if ( cn > 23 )
                break;
            
            ++cn;
        }
    };
    
    this._GetRestAfterHoursOpe = function () {
        var lft = this._GetRestAfterDateOpe(), cn = 0, lft_bf = 0; 
//        alert("In Hours Left => "+lft);
        
        if ( lft < 3600000 ) {
            //L'heure correspond à minuit
            return lft;
        }
        
        while (true) {
            lft_bf = lft;
            
            lft -= 3600000;
            
            if ( lft === 0 ) {
                this.h = ++cn;
//                alert("Rest of Hours +1 => "+lft);
//                alert("Found Hours +1 => "+this.h);
                return 0;
                break; //Parano
            } else if ( lft < 0 ) {
                this.h = cn;
//                alert("Rest of Hours 0 => "+lft);
//                alert("Found Hours 0 => "+this.h);
                return lft_bf;
                break; //Parano
            }
            
            //Securité
            if ( cn > 23 )
                break;
            
            ++cn;
        }
    };
    
    this.getMinutes = function () {
        var lft = this._GetRestAfterHoursOpe(), cn = 0, lft_bf = 0; 
//        alert("In Minutes Left => "+lft);
        
        if ( lft < 60000 ) {
            //Les minutes correspondent à 0 min
            return 0;
        }
        
        while (true) {
            lft_bf = lft;
            
            lft -= 60000;
            
            if ( lft === 0 ) {
                this.mi = ++cn;
//                alert("Rest of Minutes +1 => "+lft);
//                alert("Found Minutes +1 => "+this.mi);
                return this.mi;
                break; //Parano
            } else if ( lft < 0 ) {
                this.mi = cn;
//                alert("Rest of Minutes 0 => "+lft);
//                alert("Found Minutes 0 => "+this.im);
                return this.mi;
                break; //Parano
            }
            
            //Securité
            if ( cn > 59 )
                break;
            
            ++cn;
        }
    };
    
    this._GetRestAfterMinutesOpe = function () {
        var lft = this._GetRestAfterHoursOpe(), cn = 0, lft_bf = 0; 
//        alert("In Minutes Left => "+lft);
        
        if ( lft < 60000 ) {
            //Les minutes correspondent à 0 min
            return lft;
        }
        
        while (true) {
            lft_bf = lft;
            
            lft -= 60000;
            
            if ( lft === 0 ) {
                this.mi = ++cn;
//                alert("Rest of Minutes +1 => "+lft);
//                alert("Found Minutes +1 => "+this.mi);
                return 0;
                break; //Parano
            } else if ( lft < 0 ) {
                this.mi = cn;
//                alert("Rest of Minutes 0 => "+lft);
//                alert("Found Minutes 0 => "+this.im);
                return lft_bf;
                break; //Parano
            }
            
            //Securité
            if ( cn > 59 )
                break;
            
            ++cn;
        }
    };
    
    this.getSeconds = function () {
        var lft = this._GetRestAfterMinutesOpe(), cn = 0, lft_bf = 0; 
//        alert("In Seconds Left => "+lft);
        
        if ( lft < 1000 ) {
            //Les minutes correspondent à 0 min
            return 0;
        }
        
        while (true) {
            lft_bf = lft;
            
            lft -= 1000;
            
            if ( lft === 0 ) {
                this.s = ++cn;
//                alert("Rest of Seconds +1 => "+lft);
//                alert("Found Seconds +1 => "+this.s);
                return this.s;
                break; //Parano
            } else if ( lft < 0 ) {
                this.s = cn;
//                alert("Rest of Seconds 0 => "+lft);
//                alert("Found Seconds 0 => "+this.s);
                return this.s;
                break; //Parano
            }
            
            //Securité
            if ( cn > 59 )
                break;
            
            ++cn;
        }
    };
    
    this._GetRestAfterSecondsOpe = function () {
        var lft = this._GetRestAfterMinutesOpe(), cn = 0, lft_bf = 0; 
//        alert("In Seconds Left => "+lft);
        
        if ( lft < 1000 ) {
            //Les minutes correspondent à 0 min
            return lft;
        }
        
        while (true) {
            lft_bf = lft;
            
            lft -= 1000;
            
            if ( lft === 0 ) {
                this.s = ++cn;
//                alert("Rest of Seconds +1 => "+lft);
//                alert("Found Seconds +1 => "+this.s);
                return lft;
                break; //Parano
            } else if ( lft < 0 ) {
                this.s = cn;
//                alert("Rest of Seconds 0 => "+lft);
//                alert("Found Seconds 0 => "+this.s);
                return lft_bf;
                break; //Parano
            }
            
            //Securité
            if ( cn > 59 )
                break;
            
            ++cn;
        }
    };
    
    this.getMilliseconds = function () {
        var lft = this._GetRestAfterSecondsOpe(); 
//        alert("In Milliseconds Left => "+lft);
        
        this.ml = lft;
        return this.ml;
    };
    
    this.getTime = function () {
        return this.gt;
    };
    
    /*****************************  SETTERS ********************************/
    this.SetUTC = function (a) {
        /* UTC peut prendre trois forme 
         * (1) Un nombre à appliquer (2) Une chaine à convertir puis à appliquer (3) False ou Undefined qui permet de réinitialiser le TimeStamp à UTC+0
         * */ 
//        alert("SetUTC => "+typeof a);
        switch(typeof a) {
            case "boolean" :
            case "undefined" :
                
                    if ( KgbLib_CheckNullity(a) ) { //Reinitialise à UTC+0
                        if (! KgbLib_CheckNullity(this.UTC) ) {
                            //Si UTC n'est pas nulle (0) cela signifie qu'on est en mode UTC+x. Donc, il ya interet à lancer l'opération
                            
                            /* [Note au 09-06-14] 
                            * Les lignes ci dessous ne sont absolument pas logiques.
                            * Les instructions ci-dessous écrasent le TIMESTAMP originel.
                            * Le but est de changer le fuseau horaire et non la date.
                            * 
                            * Il faut donc obliger les méthodes de calcul à prendre en compte le fuseau-horaire et non changer la date.
                            */
                            //On réinitialise le TimeStamp (09-06-14 --> WEIRD !!!!)
                            //this.gt = (new Date()).getTime();
                            //On reset les champs. Cela n'est pas necessaire mais bon, on ne sait jamais
                            this._ResetAllFields();    
                                
                            //On met la valeur à NULL pour spécifier qu'on est à UTC+0
                            this.UTC = 0;
                            
                            //On change gtUTC. On le réinitialise !
                            this.gtUTC = this.gt;
                        }
                    } else {
                        //On set à UTC de l'heure local selon l'horloge de l'ordinateur
                        
                        this.UTC = this._GetLocalTMZ();
                        
                        //On change gtUTC. On le réinitialise !
                        this.gtUTC = this.gt + this.UTC;
                    }
                break;
            case "string" :
                    if (! KgbLib_CheckNullity(a) ) {
                        var ml = 1, v = 0;
                        
                        //On détecte le signe et retire le signe
                        if ( a.toString().indexOf('-') !== -1 ) {
                            a = a.toString().replace('-',"");
                            ml = -1;
                        } else if ( a.toString().indexOf('+') !== -1 ) {
                            a = a.toString().replace('+',"");
                        }
                        
                        //Conversion et obtention de la valeur
                        if ( a.toString().indexOf(':') !== -1 ) {
                           var b = a.toString().split(':');
                           v = parseInt(b[0])*3600000;
                           v+= parseInt(b[1])*60000;
                           v *= ml;
                           
                        } else {
                            v = parseInt(a)*3600000;
                            v *= ml;
                        }
                        
//                        alert(v);
                        this.UTC = v;
                        
                        /* [Note au 09-06-14] 
                         * Les lignes ci dessous ne sont absolument pas logiques.
                         * Les instructions ci-dessous écrase le TIMESTAMP originel.
                         * Le but est de changer le fuseau horaire et non la date.
                         * 
                         * Il faut donc obliger les méthodes de calcul à prendre en compte le fuseau-horaire et non changer la date.
                         */
                        /**
                        this.gt = (new Date()).getTime();
                        //*/
                        this.gtUTC = this.gt + this.UTC;
                        
                    }
                break;
            case "number" :
                    //On change le gtUTC
                    this.gtUTC = this.gt + a;
                    
                    //On reset les champs. Cela n'est pas necessaire mais bon, on ne sait jamais
                    this._ResetAllFields();
                    
                    //On initialise UTC
                    this.UTC = a;
                break;
        }
    };
    
    /********************************** AutoExec *********************************/
    var th = this;
    (function(){
//        Kxlib_DebugVars(["TYPE => "+typeof ml]);
        
        if (typeof ml === "boolean" && ml) {
            /* Cela veut dire qu'on veut avoir les données à l'heure locale */
            
            //On récupère le fuseau-horaire de la zone locale
            th.UTC = th._GetLocalTMZ();
            
            //On met à jour gtUTC qui sert de base pour les calculs
            th.gtUTC = ml + th.UTC;
            
            //On reset les champs qui ont pu êre modifiés
            th._ResetAllFields();
        } else if ( typeof ml === "number" ) {
//            alert("ML => "+ml);
            th.gt = ml;
            th.gtUTC = ml;
        } else if ( (typeof ml === "string") && ( (new Date(parseInt(ml))).getTime() > 0 )  ) {
            //[NOTE 23-07-14] Ajouté à la suite d'une cascade d'erreurs
            th.gt = parseInt(ml);
            th.gtUTC = parseInt(ml);
        } else if ( typeof ml === "undefined" ) {
            th.gt = (new Date()).getTime();
            th.gtUTC = (new Date()).getTime();
        } 
    })();
}