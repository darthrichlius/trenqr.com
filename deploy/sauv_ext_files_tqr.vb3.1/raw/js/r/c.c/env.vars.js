/**
 * Renvoie la valeur liée à la clé passée en paramètre.
 * Le fichier rassemble l'ensemble des VARIABLES d'ENVIRONNEMENT du produit. 
 * Ces variables sont de type variés : chemin, variable de configuration, etc.
 * 
 * ! IMPORTANT !
 *  * -> CE FICHIER DOIT ETRE TENU A JOUR REGULIEREMENT. 
 *  * -> LE FICHIER DOIT ETRE MODIFIE A CHAQUE CHANGEMENT MAJEUR (domaine, chemin, etc ...) 
 * 
 * @param {string} k
 * @returns {Boolean|undefined|window.Dolphins}
 */

function ENV_VARS (k) {
    if ( KgbLib_CheckNullity(k) ) {
        return;
    }
    
   /*
    * [NOTE 02-07-16]
    *       - NE PAS mettre "www." en mode PROD car le SSL ne fonctionne que pour "http://trenqr.com". 
    *       - Il faut aujouter '.' à la fin du nom du sous-domaine
    */
    var subdom = "";
    
    var ENV = {
        /*
         * Correspond au mode de fonctionnement du produit web.
         * 
         * MODE :
         *  DEV     : Mode DEVELOPMENT
         *  TEST    : Mode TEST
         *  PROD    : Mode PRODUCTION
         *  DEBUG   : Mode DEBUG
         */
        "SYS_ENV_RUNG_MODE"         : "PROD",
        /*
         * Déterminer le comportement de la fonction Kxlib_DebugVars().
         * 
         * MODE :
         *  0 : Ne pas afficher de message;
         *  1 : N'afficher que les messages de type ALERT;
         *  2 : N'afficher que les messages de type CONSOLE.LOG;
         *  3 : Afficher les messages de type ALERT et CONSOLE.LOG;
         */
        "SYS_ENV_SHW_DBGVARS_MD"    : 0
    };
    
    var VARS = {
        /* PATH : ROOT ABSOLUS */
        /* //DEPUIS 02-07-16
        "SYS_URL_AUD_ROOTABS"       : "https://"+subdom+".trenqr.com",
        "SYS_URL_IMG_ROOTABS"       : "https://"+subdom+".trenqr.com",
        "SYS_URL_SCRIPT_ROOTABS"    : "https://"+subdom+".trenqr.com",
        "SYS_URL_STYLE_ROOTABS"     : "https://"+subdom+".trenqr.com",
        //*/
        "SYS_URL_AUD_ROOTABS"       : "https://".concat(subdom,"trenqr.com"),
        "SYS_URL_IMG_ROOTABS"       : "https://".concat(subdom,"trenqr.com"),
        "SYS_URL_SCRIPT_ROOTABS"    : "https://".concat(subdom,"trenqr.com"),
        "SYS_URL_STYLE_ROOTABS"     : "https://".concat(subdom,"trenqr.com"),
        /* PATH : CHEMIN DE FICHIERS */
        "SYS_URL_AUD_PATH"          : "/bart1/timg/files/aud/",
        "SYS_URL_IMG_PATH"          : "/bart1/timg/files/img/",
        "SYS_URL_SCRIPT_PATH"       : "/bart1/ext/public/js/",
        "SYS_URL_STYLE_PATH"        : "/bart1/ext/public/css/"
    };
    
    if ( !ENV.hasOwnProperty(k) && !VARS.hasOwnProperty(k) ) {
        return false;
    } else if ( ENV.hasOwnProperty(k) && VARS.hasOwnProperty(k) ) {
        return false;
    } else if ( ENV.hasOwnProperty(k) ) {
        return ENV[k];
    } else if ( VARS.hasOwnProperty(k) ) {
        return VARS[k];
    }
    
};

var TqCons = {
    'toto':'value1'
};

function TqOn (ev,d) {
    try {
        if ( KgbLib_CheckNullity(ev) ) {
            return;
        }

        switch (ev) {
            case "RBD_FR_FV" : 
                    if ( KgbLib_CheckNullity(d) ) {
                        return;
                    }
                    $(".jb-tqr-lstnr-onev").trigger("RBD_FR_FV",[d]);
                break;
            default :
                return;
        }

    } catch (ex) {
        Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber,console.trace()],true);
    }
};