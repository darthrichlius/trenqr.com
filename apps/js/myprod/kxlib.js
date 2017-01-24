/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Permet de tester si la variable en paramètre est null ou vide.
 * Dans le cas où la variable est null ou vide, la fonction retourne 1.
 * Cette variable peut être un tableau. Dans ce cas précis, si au mois une des valeurs est nulle, la fonction renvoie true.
 * 
 * @param {type} arg
 * @returns {Number}
 */
/*
var n = null;
if ( n === null) alert("OBAMA");
//*/

/*
 * PROTOTYPES
 * Modification, ajout ou suppression de méthodes.
 */

/**
 * Retourne les valeurs d'une colonne d'un object d'entrée.
 * @param {string|number} cn L'index de la colonne. Si cn est une chaine de caractère, l'index est une clé sinon il s'agit d'un index à proprement parlé. 
 * @param {type} o L'objet utilisé pour effecuter les opérations de recherche.
 * @returns {Boolean|undefined|Array|Object.prototype.getcolumn.no}
 */
Kxlib_GetColumn = function(cn,o) {
    if ( !( typeof o === "object" && Object.keys(o).length ) | !( ( typeof cn === "string" || typeof cn === "number" ) && cn ) ) {
        return;
    }
    var no = [];
    var fnd = 0;
    for(var x in o ) {
        var e = o[x];
        if ( typeof cn === "number" ) {
            var t__ = Object.keys(e);
            cn = ( t__[cn] !== undefined ) ? t__[cn] : null;
        } 
        if ( cn && e !== undefined && e.hasOwnProperty(cn) && e[cn] ) {
            no.push(e[cn]);
            ++fnd;
        }
    }
    return ( fnd ) ? no : false;
};
/*
var a = {
    0 : {
        "a" : 1,
        "b" : 2,
        "c" : 3
    },
    1 : {
        "a" : 4,
        "b" : 5,
        "c" : 6
    },
    2 : {
        "a" : 7,
        "b" : 8,
        "c" : 9
    }
};

alert(JSON.stringify(a));
var na = a.getcolumn("b",a);
alert(JSON.stringify(na));
//*/
function KgbLib_CheckNullity (arg) {
    //*
    if (! ( arg === null | typeof(arg) === "undefined" | arg === "" | arg === "''" | ( arg && arg.length === 0 )  ) && !$.isArray(arg) ) {
        return false;
    } else if (! ( arg === null | typeof(arg) === "undefined" | arg === "" | ( arg && arg.length === 0 ) ) && $.isArray(arg) ) {
        $.each(arg, function(i,v) {
            if ( KgbLib_CheckNullity(v) ) {
                return true;
            }
        });
    } else {
        return true;
    }
    //Cette section n'est evidemment accessible que dans le cas où un tableau est non vide.
    return false;
    //*/
    //Ancienne version sans prise en compte de la gestion de tableau
    //return (arg === null || typeof(arg) === "undefined" || arg === "" || arg.length === 0);
}
//Si args null => null, si sel faux => e
/**
 * Permet d'effectuer un reverse sur un element.
 * 
 * Retourne :
 * - 1 : Le switch s'est bien passé
 * - 0 : Le switch n'a pas pu se faire car l'attribut était vide
 * - string : Le selecteur est faux
 * - undefined : Au moins unb des paramètre est faux
 * @param {type} id
 * @param {type} attr_source
 * @param {type} do_it
 * @returns {undefined|e|Number}
 */
function KgbLib_ReverseText (id, attr_source, do_it) {
   if( KgbLib_CheckNullity ([id,attr_source]) ) return;
   
   do_it = ( KgbLib_CheckNullity (do_it) ) ? false : true;
    
   try {
      $o = $(id); 
      var _n = $o.data(attr_source);
   } catch(e) {
       return e;
   }
   
   //En gros si l'attribut est vide mais non null et qu'on a pas forcé le fait de quand mm faire le switch
   if ( KgbLib_CheckNullity (_n) && _n === "" && !do_it ) return 0;
   
   var _old = $o.html();
   $o.html(_n);
   
   $o.data(attr_source,_old);
   
   return 1;
}

function CreateCookie (name,value,days) {
    var expires;
    
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    } else expires = "";
    
    document.cookie = name+"="+value+expires+"; path=/";
}

function EraseCookie (name) {
    CreateCookie(name,"",-1);
}

function ReadCookie (name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)===' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function Kx_XHR_Send(data, before, oncomplete, onsuccess, onerror, options){
    // Launch AJAX request.
    //alert(data);
    try {
        before = ( typeof before !== "undefined" && typeof before === "function" ) ? before : function(){ /* Kxlib_DebugVars([AJAX -- BeforeSend()"]); */ };;
        oncomplete = ( typeof oncomplete !== "undefined" && typeof oncomplete === "function" ) ? oncomplete : function(jqXHR,err){ /* Kxlib_DebugVars(["AJAX -- complete()" ); */ /* Kxlib_DebugVars(err]); */ };;
        
        url = ( typeof options === "object" && options.hasOwnProperty("url") && typeof options.url === "string" && options.url ) ? options.url : null;
        type = ( typeof options === "object" && options.hasOwnProperty("type") && typeof options.type === "string" && options.type ) ? options.type : null;
        wcrdtl = ( typeof options === "object" && options.hasOwnProperty("wcrdtl") && typeof options.wcrdtl === "boolean" ) ? options.wcrdtl : false;
        
        var xhr = $.ajax({
            // Data to be send
            data        : data,
            // The link we are accessing.
            url         : url,
            // The type of request.
            type        : type,
            // The type of data that is getting returned.
            //dataType: "json",
            xhrFields   : {
                withCredentials: wcrdtl
            },
            contentType : "application/x-www-form-urlencoded; charset=UTF-8",
            error       : onerror,
            beforeSend  : before,
            complete    : oncomplete,
            success     : onsuccess
        });
        
        // Prevent default action
        // return( false );		
         
        return xhr; //Depuis 29-12-14
    } catch (ex) {
//        alert(JSON.stringify(options));
        Kxlib_DebugVars(["EXCEPTION : [MESSAGE] : "+ex+"; [LINE] : "+ex.lineNuumber]);
    }
}

/* //[DEPUIS 12-07-15] @BOR
function Kx_XHR_Send(data, type, requrl, onerror, onsuccess, wcrdtl){
    // Launch AJAX request.
    //alert(data);
    try {
        wcrdtl = ( typeof wcrdtl === "boolean" ) ? wcrdtl : false;
        var xhr = $.ajax({
            // Data to be send
            data: data,
            // The link we are accessing.
            url: requrl,
            // The type of request.
            type: type,
            // The type of data that is getting returned.
            //dataType: "json",
            xhrFields: {
                withCredentials: wcrdtl
            },
            error: onerror,
            beforeSend: function() {
//            Kxlib_DebugVars([AJAX -- BeforeSend()"]);
            },
            complete: function(jqXHR, err) {
                //Kxlib_DebugVars(["AJAX -- complete()" ]);
                //Kxlib_DebugVars([rr]);
            },
            success: onsuccess
        });
        
        // Prevent default action
        // return( false );		
        
        return xhr; //Depuis 29-12-14
    } catch (ex) {
//        alert("EXCEPTION : "+ex);
    }
}
//*/

function Kx_XHR_Get(type, requrl, onerror, onsuccess){
   
    // Launch AJAX request.
    $.ajax({
        
        // The link we are accessing.
        url: requrl,

        // The type of request.
        type: "get",

        // The type of data that is getting returned.
        dataType: "json",
//        dataType: "html",

        error: onerror,

        beforeSend: function(){
//            Kxlib_DebugVars([AJAX -- BeforeSend()"]);
        },

        complete: function(){
//            Kxlib_DebugVars(["AJAX -- complete()" ]);
        },

        success: onsuccess
    });

    // Prevent default action
    return( false );					
}


function Kxlib_IsSquare(h, w) {
    return ( (h/w) === 1 ) ? 1 : 0;
}

function Kxlib_ObjectChild_Count (a) {
    if ( KgbLib_CheckNullity(a) ) {
        return;
    }
    
    var s = 0, o = a;
    for (k in o){
      if (o.hasOwnProperty(k)) s++;
    }
    return s;
}

function Kxlib_encodeURL(str) {
    if ( KgbLib_CheckNullity(s) ) return;
    return str.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
}

function Kxlib_EscapeHTMLEntity (s) {
    if ( KgbLib_CheckNullity(s) ) return;
    return s.replace(/[<]+/g, '&#60;').replace(/[>]+/g, '&#62;').replace(/\"/g, '&quot;');
}

function Kxlib_EscapeComa (s) {
    if ( KgbLib_CheckNullity(s) ) return;
    return s.replace(/\,/g, '&#44;');
}

function Kxlib_EscapeForDataCache (s) {
    if ( KgbLib_CheckNullity(s) ) return;
    return s.replace(/\,/g, '\\&#44;').replace(/\[/g, '&#91;').replace(/\]/g, '&#93;').replace(/\"/g, '\\&quot;').replace(/\'/g, '\\&#39;').replace(/\\/g, '\\&#92;');
}

function Kxlib_Decode_EscapeForDataCache (s) {
    //[NOTE 17-09-14] @author L.C. J'ai ajouté le fait de prendre en compte les '' car Kgb... les considères comme null. Dans datacache ce n'est pas NULL mais VOID !
    if ( KgbLib_CheckNullity(s) && s !== "''" ) return;
    //RAPPEL : On met '<br/> en dur pour permettre son affichage en mode HTML et non <pre>
    return s.replace(/\\{2}(?:,)/g, '&#44;').replace(/\\{2}(?:"")/g, '&quot;').replace(/\\{2}(?:')/g, '&#39;').replace(/\\{2}(?!,|')/g, '&#92;').replace(/&#92;n/g, '<br/>');
}

//Permet de récuppérer les 'dauphins'
//'check_val' oblige la fonction à ne retourner un résultat que s'il non vide.
function Kxlib_getDolphinsValue (a) {
    //Dolphins = dauphin 
    //Les dauphins sont des mamifères connus pour accompagnés les bateaux. 
    //En l'occurrence, il s'agit de données accompagant la page
    
    try {
        
        //Par défaut on considère que la majorité des pages ont une partie "._Dolphins"
//        _Obj = window.Dolphins;

        var m = DOLPHINS_H(a);
        /*
        if (!_Obj.hasOwnProperty(a)) {
            return;
        } else {
            if (!KgbLib_CheckNullity(check_val) && check_val === true) {
                return ( KgbLib_CheckNullity(_Obj[a]) ) ? undefined : _Obj[a]; 
            } else 
                return _Obj[a]; 
        }
        
        //*/
        
        return  ( KgbLib_CheckNullity(m) ) ? false : m;
    } catch (ex) {
//        alert(ex);
        return;
    }
}


/**
 * Récupère un objet de type AjaxRule à partir de la clé passée en paramètre si elle existe.
 * Dans le cas où il s'agit d'une requête necessitant la présence du pseudo, CALLER peut le passer via uz
 * @param {string} uq
 * @param {string} uz
 * @returns {Boolean|undefined}
 */
function Kxlib_GetAjaxRules (uq,uz) {
    // u = urqid (string); c = "check value" (string)
    
    if ( KgbLib_CheckNullity(uq) ){ 
        return;
    }
    
    try {
//        Check Object by urqid
//        var _Obj = window.AjaxRules;

        if (! KgbLib_CheckNullity(uz) )
            o = AJAXRULES(uq,uz);
        else
            o = AJAXRULES(uq);
        
        if ( KgbLib_CheckNullity(o) ) {
            return false;
        } else {
            /*
            if ( !KgbLib_CheckNullity(cv) && cv === true ) {
                return ( KgbLib_CheckNullity(_Obj[u])) ?
                        false : _Obj[u]; 
            } else {
                return _Obj[u]; 
            }
            //*/
            return o;
        }
        
    } catch (ex) {
//        Kxlib_DebugVars([ex],true);
        return -1;
    }

}

function Kxlib_GetFeatRules (c) {
    /* Permet de récupérer les règles liées à la fonctionnalité identiafiable par son code donné en paramètre */
    if ( KgbLib_CheckNullity(c) ) {
        return;
    }
    
    var ob = window.FeatRules;
    
    if ( KgbLib_CheckNullity(ob) ) { return; }
    
    return (! ob.hasOwnProperty(c) ) ? false : ob[c]; 
    
}

function Kxlib_DebugVars (a,fa) {
    //fa = ForceAlert. Force la fonction a affiché le message via la méthode Alert(); contre console.log
    /* Permer d'afficher les variables passées en paramètres.
     * L'affichage se fait via la console par défaut. Sauf si l'utilisateur passe fa === true */
//    alert("TYPE => "+typeof window.rm+"; VALEUR => "+window.rm)
    if ( ( KgbLib_CheckNullity(ENV_VARS("SYS_ENV_RUNG_MODE")) ) || ( ENV_VARS("SYS_ENV_RUNG_MODE") !== "DEV" && ENV_VARS("SYS_ENV_RUNG_MODE") !== "TEST" && ENV_VARS("SYS_ENV_RUNG_MODE") !== "DEBUG") ) {
        return;
    }
    /*
     * [DEPUIS 24-08-15] @BOR
     */    
    if ( !KgbLib_CheckNullity(ENV_VARS("SYS_ENV_SHW_DBGVARS_MD")) && ENV_VARS("SYS_ENV_SHW_DBGVARS_MD") === 0 ) {
        return;
    }
    
    if ( KgbLib_CheckNullity(a) ) {
        var dt = new Date ();
        var dis = "FR_TIME_MODE ["+dt.getDate()+"-"+dt.getMonth()+"-"+dt.getFullYear()+" | "+dt.getHours()+":"+dt.getMinutes()+":"+dt.getSeconds()+":"+dt.getMilliseconds()+"]";
        dis += " : ";
        dis += "La variable est de type NULL";
        
        if ( fa && ENV_VARS("SYS_ENV_SHW_DBGVARS_MD") !== 2 ) {
            alert(dis);
        } else if ( ENV_VARS("SYS_ENV_SHW_DBGVARS_MD") !== 1 ) { 
//            console.log(dis);
//            Kxlib_Log(m); //[DEPUIS 15-08-15] @BOR
        }
        
        return;
    }
    
    if (! $.isArray(a) ) return;
    
    var m = "";
    
    if ( a.length === 1 ) { m = "VAL => "+a[0]; }
    else {
        var n = 0;
        $.each(a,function(x,v) {
            ++n;
            if ( m === "" ) m = "VAL"+n+" => "+v+";";
            else m += " VAL"+n+" => "+v+";";
        });
    }
    
    if ( fa && ENV_VARS("SYS_ENV_SHW_DBGVARS_MD") !== 2 ) {
        //m = message, dt = datetime
        var dt = new Date ();
        var dis = "FR_TIME_MODE ["+dt.getDate()+"-"+dt.getMonth()+"-"+dt.getFullYear()+" | "+dt.getHours()+":"+dt.getMinutes()+":"+dt.getSeconds()+":"+dt.getMilliseconds()+"]";

        dis += " : ";
        m = ( KgbLib_CheckNullity(m) ) ? "void" : m;
        dis += m;
        
        alert(dis);
    } else if ( ENV_VARS("SYS_ENV_SHW_DBGVARS_MD") !== 1 ) {  
        console.log(m);
//        Kxlib_Log(m); //[DEPUIS 15-08-15] @BOR 
    }
}

function Kxlib_ResetForm (s) {
    if ( KgbLib_CheckNullity(s) ) {
        return;
    }
    
    //Au cas où Caller insère le caractère '#'. [ NOTE 20-05-14] La fonction devient plus souple
    var ns = Kxlib_ValidIdSel(s);
        
    //On reset le form
    
    try {
        $(ns).each(function() {
            this.reset();
        });
    } catch (e) {
        return;
    }

    //Cela permet à un autre Handler de faire des opérations à la fin du Reset
    $(ns).trigger("formcleared");
}

function Kxlib_ClearForm (s) {
    /*
     * Permet de remettre à le formulaire quand ResetForm, remet aux valeurs d'origine.
     */
    if ( KgbLib_CheckNullity(s) )
        return;
    
    //Au cas où Caller insère le caractère '#'. [ NOTE 20-05-14] La fonction devient plus souple
    var ns = Kxlib_ValidIdSel(s);
        
    //On reset le form
    
    try {
        var frm_elements = document.getElementById(s).elements;
        $(frm_elements).each(function() {
            var field_type = this.type.toLowerCase();
            switch (field_type)
            {
                case "text":
                case "password":
                case "textarea":
                case "hidden":
                    this.value = "";
                    break;
                case "radio":
                case "checkbox":
                    if (this.checked)
                    {
                        this.checked = false;
                    }
                    break;
                case "select-one":
                case "select-multi":
                    if ( $(this).find("option[value='init']").length ) {
                        $(this).find("option[value='init']").attr("selected",true);
                    } else {
                        this.selectedIndex = -1;
                    }
                    break;
                default:
                    break;
            }
        });
    } catch (e) {
        return;
    }

    //Cela permet à un autre Handler de faire des opérations à la fin du Reset
    $(ns).trigger("formcleared");
}

function Kxlib_ResetFormElt (e) {
    if ( KgbLib_CheckNullity(e) )
        return; 
    
    var o = Kxlib_ValidIdSel(e);  
    $(o).wrap('<form>').closest('form').get(0).reset();
    $(o).unwrap();
}

function Kxlib_Trim (s) {
    return s.replace(/^\s+|\s+$/gm,'');
}

function Kxlib_Strip_Specials (s) {
    return s.replace(/([<>\-'"/]+)/g,function(m){
        return "\\"+m;
    });
}


function Kxlib_Extract_All_DMD (s) {
    /*
     * Permet d'exraire tous les DMD d'un texte. 
     * La fonction renvoie ensuite un tableau.
     * 
     * GLOSSAIRE :
     *      * DMD : DolphinMarkupData
     */
    
    if ( KgbLib_CheckNullity(s) ) {
        return;
    }
    
    var d;
    
    d = s.match(/%([a-zA-Z\d_-]+)%(?=[\s]{1}|)/g);
    
    if ( Array.isArray(d) && d.length ) {
        return d;
    } else {
        return false;
    }
    
}

/**
 * Permet de remplacer dans une chaine de caractères, un DMD.
 * Cette fonction est utile notamment pour les chaines de type DataCache ou les textes UserAction de Dolphins.
 * 
 *  GLOSSAIRE :
 *      * DMD : DolphinMarkupData
 *      
 *  EVOLUTION
 *      - Chercher en fonction d'un code DOLPHINS existant
 *      - Chercher dans les données relatives à la page
 *      - Chercher dans les données relatives au CU
 *      
 * @param {type} s Source, la chaine qui va faire l'objet de la transformation
 * @param {type} c Le code à rechercher
 * @param {type} r L'élément qui va servir de remplacement
 * @returns {string|Boolean}
 */
function Kxlib_DolphinsReplaceDmd(s,c,r) {
    //s : Source, la chaine qui va faire l'objet de la transformation; c : Le code à rechercher; r : L'élément qui va servir de remplacement
    if ( KgbLib_CheckNullity(c) || KgbLib_CheckNullity(r) || KgbLib_CheckNullity(s) || typeof s !== "string" ) return;
    
    //On s'assure que le code a le bon format %texte%
    c = (! c.match(/^\%.+\%$/) ) ? '%'+c+'%': c;
//    Kxlib_DebugVars([c,s.indexOf(c)],true);
    if ( s.indexOf(c) > -1 ) {
//        return s.replace(c,r);
        return s.replace(new RegExp(c, 'g'), r);
//        alert(s.replace(c,r));
    } else {
        //TODO : On vérifie si la DMD fait référence à autre chose. (Voir texte consacré aux évolutions futures).
        
        //On renvoie false. CALLER doit donc bien faire attention à ce qu'il conserve une copie du string s'il ne veut pas qu'on l'écrase.
        return false;
    }
}

function Kxlib_IsIE () {
    
    if ( $('html').is('.ie6, .ie7, .ie8, ie9') ) {
        return true;
    } else {
        /*
         * On essai avec la deuxième technique
         */
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        /*
         * Compatible avec IE11
         */
        if ( msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./) ) {    
            // If Internet Explorer, return version number
//            alert(parseInt(ua.substring(msie + 5, ua.indexOf(".", msie))));
            return true;
        } else {                
            return false;
        }
    }
}

function Kxlib_NvgtrSayWho () {
    var ua = navigator.userAgent, tem, M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || []; 
    if( /trident/i.test(M[1]) ) {
        tem = /\brv[ :]+(\d+)/g.exec(ua) || []; 
        return {name:'IE',version:(tem[1]||'')};
    }   
    if( M[1] === 'Chrome' ){
        tem = ua.match(/\bOPR\/(\d+)/);
        if( tem !== null )   {return {name:'Opera', version:tem[1]};}
    }   
    M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
    
    if ( ( tem = ua.match(/version\/(\d+)/i) ) !== null) {
        M.splice(1,1,tem[1]);
    }
    return {
      name: M[0],
      version: M[1]
    };
}


function Kxlib_NvgtrIsMobile () {
    return (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) ? 
        true : false;
}
        
function Kxlib_ValidIdSel (a) {
    return ( a.toString().indexOf("#") === -1 ) ? "#"+a : a;
}

function Kxlib_ValidClassSel (a) {
    return ( a.toString().indexOf(".") === -1 ) ? "."+a : a;
}

function Kxlib_ValidUser (a) {
    return ( a.toString().indexOf("@") === -1 ) ? "@"+a : a;
}

function Kxlib_ToPxUnit (a) {
    return ( typeof a === "string" ) ? a+"px" : a.toString()+"px";
}

//Pour éviter d'oublier comment faire
function Kxlib_Exists (s) {
    var ns = Kxlib_ValidIdSel(s);
    
    return ( $(ns).length ) ? true : false;
}

function Kxlib_ValidMyEval (a) {
    if ( KgbLib_CheckNullity(a) ) return;
    
    switch (a.toString().toLowerCase() ) {
        case "p2" :
        case "p1" :
        case "m1" :
                return a;
            break;
        default:
                return "";
            break;
    }
    
    return "";
}

//Permet de faire apparaitre les logs dans la CONSOLE de façon personnalisé
function Kxlib_Log (m) {
    return;
    /* On afficher le log qui ssi on est sure d'être dans le mode DEV, TEST ou DEBUG
     * La variable DOIT ETRE déclarée dans le fichier x-index de la page active
     * */
    if ( ( KgbLib_CheckNullity(window.rm) ) || ( window.rm !== "DEV" && window.rm !== "TEST" && window.rm !== "DEBUG") ) {
        return;
    }
    
    //m = message, dt = datetime
    var dt = new Date ();
    var dis = "FR_TIME_MODE ["+dt.getDate()+"-"+dt.getMonth()+"-"+dt.getFullYear()+" | "+dt.getHours()+":"+dt.getMinutes()+":"+dt.getSeconds()+":"+dt.getMilliseconds()+"]";
    
    dis += " => ";
    m = ( KgbLib_CheckNullity(m) ) ? "void" : m;
    dis += m;
    
    Kxlib_DebugVars([is]);
}

function Kxlib_GetPagegProperties () {
    /* Il s'agit des propriétés liées à la page actuellement visitée.
     * Cette page peut être TRPG, TMLNR. ([NOTE 01-07-14] Attention, peut changer dans le temps)
     * 
     * RAPPEL : On peut aussi retrouver le code de la page dans la balise <html>
     * * */
    
    var r = {
        "pg":$("meta[property='tq:pg']").attr("content"),
        "ver":$("meta[property='tq:ver']").attr("content")
    };

    return r;
}

function Kxlib_GetOwnerPgPropIfExist () {
    /* Il s'agit des propriétés liées au propriétaire de la page actuellement visitée.
     * Cette page peut être TRPG, TMLNR.
     * 
     * Dans le cas de TRPG, il s'agit de celui qui a créé la TREND.
     * 
     * [NOTE 09-05-15] @BOR
     * On devra faire attention à n'utiliser cette fonction qu'en connaissance de cause. En effet, l'utilisateur peut changer à sa guise ces données.
     * Il faudra donc priviligier les données récoltées depuis SESSION.
     * * */
    
    if ( $("meta[property='tq:ufn']").length ) {
        var r = {
            "ueid"  :$("meta[property='tq:ueid']").attr("content"),
            "uppic" :$("meta[property='tq:uppic']").attr("content"),
            "ufn"   :$("meta[property='tq:ufn']").attr("content"),
            "upsd"  :$("meta[property='tq:upsd']").attr("content"),
            "uhref" :$("meta[property='tq:uhref']").attr("content"),
            "udl"   :$("meta[property='tq:udl']").attr("content"),
            "ucity" :$("meta[property='tq:ucity']").attr("content"),
            "ucn"   :$("meta[property='tq:ucn']").attr("content")
        };
        
        return r;
    }
    
}
//Kxlib_DebugVars([Kxlib_GetCurUserPropIfExist().uhref],true);

function Kxlib_GetCurUserPropIfExist () {
    /* Il s'agit des propriétés liées au visiteur de la page courante.
     * Ces données ne sont accessibles que si l'utilisateur courant est connecté ou dans la zone centrale de la plateforme ...
     * ... Où seule la box est accessible.
     * 
     * * */
    
    if ( $("#user-id-card").length && !KgbLib_CheckNullity($("#user-id-card").data("cache")) ) {
        var s = $("#user-id-card").data("cache");
        
        var d = Kxlib_DataCacheToArray(s)[0];        
        
        var r = {
            "ueid":d[0],
            "ufn":d[1],
            "upsd":d[2],
            "uppic":d[3],
            "uhref":d[4],
            "ucity":d[5],
            //La relation qui lie l'utilisateur actif (CURRENT_USER) et le propriétaire de la page (OWNER)
            "rel":d[6]
        };
        
        return r;
    } 
    return;
}

function Kxlib_ChangeActorsUrel (r) {
    /* Permet de changer la relation entre CU et OW.
     * Les changements se font au niveau de l'userbox.
     * * */
    //r = uRel
    if ( KgbLib_CheckNullity(r) ) return;
            
    try {
        var c = $("#user-id-card").data("cache");
        
        if ( KgbLib_CheckNullity(c) ) return;
        
        var s = Kxlib_AlterDataCacheAt(c,r.toLowerCase(),0,6);
//        alert(s);
        
        $("#user-id-card").data("cache",s);
        
        return s;
    } catch (e) {
//        Kxlib_DebugVars([e],true);
       return -1;
    }

}

function Kxlib_GetTrendPropIfExist () {
    /* Il s'agit des propriétés liées au propriétaire de la page actuellement visitée.
     * Cette page peut être TRPG, TMLNR
     * * */

    if ( $("meta[property='tq:ufn']").length ) {
        var r = {
            "trid":$("meta[property='tq:trid']").attr("content"),
            "trttl":$("meta[property='tq:trttl']").attr("content"),
            "trdesc":$("meta[property='tq:trdesc']").attr("content"),
            "trcat":$("meta[property='tq:trcat']").attr("content"),
            "trakx":$("meta[property='tq:trakx']").attr("content"),
            "trgrat":$("meta[property='tq:trgrat']").attr("content")
        };
        
        return r;
    }
}

function Kxlib_DCGetSection(st) {
    
    if ( KgbLib_CheckNullity(st) || typeof st !== "string" ) return;
    
    st = Kxlib_TrimReplace(st,"");

    //On récupère les sections
    var l = st.match(/\'([\s\S]*?)\'(?=(?:\,|$))/g);
    if ( l === null ) { 
        
        /* [NOTE 24-07-14]
         * Il peut arriver que certaines sections ne suive pas le pattern ['a','b'] mais [a,b].
         * Dans ce cas on procède autrement.
         * Ce pattern n'est pas recommendé car si les valeurs à l'interieur comportent aussi des ',' alors tout sera faussé.
         * C'est pour cela qu'il faut privilégié le pattern 1.
         * Cependant, à la date (24-07-14) trop de DC sont sous la forme P2. Plutot que de tout modifier et craindre des erreurs 
         * ... en cascade, j'ai préféré procéder à des modifications au niveau de cette nouvelle fonction de traitement.
         */
//        Kxlib_DebugVars([typeof l, l],true);
        var s = st.replace(/\[|\]/g,"");
        return s.split(',');
    }
    
    //On poursuit selon le cas P2 (Pattern2)
    $.each(l,function(x,v){
        v = Kxlib_TrimReplace(v,"");
        //[NOTE 17-09-14] @author L.C. Modification pour prendre en compte le fait que '' n'est pas NULL mais VOID dans le cas de DC
        if ( v === "''" ) {
            l[x] = "";
        } else {
            l[x] = Kxlib_Decode_EscapeForDataCache (v);
        }
    });
//            s = s.split(',');  //ABONNDONNE : Pas assez precis, fiable et sécurisé
    
    return l;
}

function Kxlib_DataCacheToArray (s) {
    if ( KgbLib_CheckNullity(s) | typeof s !== "string" ) return;
    
    /*
     * La forme des data-cache est souvent :
     * "[list,de,donnnées]"
     * "[list,de,donnnées],[list,de,donnnées]"
     * 
     * [08-07-14] !!!! La fonction ne traite pas les cas où les tableaux sont entourés de [] !!!!
     */
//    var rg = new RegExp("(\[)","g"); //[07/07/14] Ne marche pas
//    alert(s);
    
    //var t = s.match(/\[(.*?)\]/g); //REMPLACE [NOTE 09-07-14] (.*?) Ne prenait pas en compte \n. 
    //var t = s.match(/\[([\s\S]*?)\](?=(?:\,|$))/g); //[NOTE 09-07-14] [\s\S]*? remplace (.*?) et prends en compte \n 
//    var t = s.match(/\[(?!\[)([\s\S]*?)\](?=(?:\,|$))/g); //[NOTE 20-07-14]  
    //[NOTE 05-04-15] La version précédente prennait en compte les cas ']]' à la fin.
//    var t = s.match(/\[(?!\[)([\s\S]*?)\]/g); //[NOTE 05-04-15]  
    var t = s.match(/\[(?!\[)([\s\S]*?)\](?!')/g); //[NOTE 25-04-15]  
//    var t = s.match(/\[(?!\[)([\s\S]*?)\](?!')(?=,|(?:^$))/g); //[NOTE 26-04-15]  
    
    if ( t.length === 1 ) {
        var s = t[0];
        return [Kxlib_DCGetSection(s),1];
    } else if ( t.length > 1 ) {
        try {
            var r = new Array();
            $.each(t,function(x,v) {
                r.push(Kxlib_DCGetSection(v));
//                alert("BFB => "+r)
            });
            //alert(r); //FOR DEV, TEST, DEBUG;
            return [r,t.length];
        } catch (e) {
            //alert(e); return; //FOR DEV, TEST, DEBUG
//            var m = Kxlib_getDolphinsValue("ERR_UNQ_ONPARSE_DCACHE");
//            Kxlib_DebugVars([m],true);
            return -1;
            //TODO : Déclarer une erreur technique
        }
        
    } else {
        return 0;
    }
}
/* 
var t = "['a','b','c','d'],['e','f','g','h','i'],['s','t','x','y','z']", x = 0, y = 3;
var t = "['a','b','c','d']", x = 0, y = 0;             
                
var t = "['1405899804','http://lorempixel.com/500/500/food/2','La description pour l\\'article ajoutée. L\\'article est ajoutée en mode \\'List\\'','','','2',''],['1405899804208','1405899807808'],['0','0','0','0','@SrvUser1','@SrvUser2','@SrvUser3','100'],['11','Lou Carther','@IamLouCarther','http://lorempixel.com/70/70/animals/8','/@IamLouCarther'],['p1']", x = 2, y = 0;

alert("TEST => "+Kxlib_AlterDataCacheAt(t,2,x,y));
//*/

//[NOTE 21-07-14 ] Testée et déclaré fonctionnel. J'en ai profité pour amélioré 'un peu' Kxlib_DataCacheToArray
function Kxlib_GetDataCacheAt(t,x1,x2) {
    /* Ne fonctionne (corectement) que si le data cache suit la forme : ['','',...],[],... */
    //t = Texte, x1 = IndexLayr1 [],[], l2 = IndexLayer2 a,a,a
    
    if ( KgbLib_CheckNullity(t) || KgbLib_CheckNullity(x1) ) return;
    
    //On vérifie la forme du datacache
    var x = t.match(/^\[(?!\[)([\s\S]*?)\](?=(?:\,|$))$/);
    
    if (! x ) {
        //Si la forme n'est pas bonne 
        return 0;
    }
    
    var r = Kxlib_DataCacheToArray(t);
//    alert("LN => "+r); //FOR DEV, TEST, DEBUG;
    
    if ( typeof r[0][x1] === "undefined" ) return;
    else {
        if ( KgbLib_CheckNullity(x2) ) return r[0][x1].toString();
            
        if ( typeof r[0][x1][x2] === "undefined" ) return;
        
        return r[0][x1][x2];
    }
}

//[NOTE 21-07-14 ] Testée et déclarée fonctionnelle.
function Kxlib_AlterDataCacheAt(t,n,x1,x2) {
    /* Permet de remplacer dans la chaine datacache une valeur reconnaissable par un champs identifiable par x1, x2.
     * Ne fonctionne (corectement) que si le data cache suit la forme : ['','',...],[],... 
     * * */
    
    //t = Texte; n = La valeur qui sert de remplacement; x1 = IndexLayr1 [],[]; l2 = IndexLayer2 a,a,a
    
    if ( KgbLib_CheckNullity(t) | (typeof n === "undefined" ) | KgbLib_CheckNullity(x1) | $.isArray(n) ) return;
    
    try {
        //On vérifie la forme du datacache
        var x = t.match(/^\[(?!\[)([\s\S]*?)\](?=(?:\,|$))$/);
        
        if (!x) {
            //Si la forme n'est pas bonne 
            return 0;
        }
        
        var r = Kxlib_DataCacheToArray(t), s = "";
//    Kxlib_DebugVars(["LN => "+r[0].length,typeof r[0][x1]],true); //FOR DEV, TEST, DEBUG;
        
        if (typeof r[0][x1] === "undefined") return;
        else {
            if ( KgbLib_CheckNullity(x2) ) {
                
                $.each(r[0], function(x, v) {
                    if (x1 === x)
                        s += n;
                    else
                        s += "['" + v.join("','") + "']";
                    
                    if (x < (r[0].length - 1))
                        s += ",";
                });
                
                return s;
                
            } else {
                
                if ( r[1] === 1 ) {
                    //Dans le cas où il n'y a qu'une seule dimension on ne se sert que de x2
                    if (typeof r[0][x2] === "undefined") return;
                    else {
                        r[0][x2] = n;
                        
                        //Si le tableau n'a qu'une seule valeur
                        if ( r[0].length === 1 ) return "['"+r[0][x2]+"']";
                        else {
                            s = "['" + r[0].join("','") + "']";
                            return s;
                        }
                    }
                } else if ( r[1] > 1 ) {
//                    alert(r[0][x1][x2]);
                    if (typeof r[0][x1][x2] === "undefined") return;
                    else {
                        
                        r[0][x1][x2] = n;
                        
                        $.each(r[0], function(x,v) {
                            if ( v.length === 1 ) s += "['"+v+"']";
                            else {
                                s += "['" + v.join("','") + "']";
                            }
                            
                            if ( x < (r[0].length - 1) ) s += ",";
                        });
                        return s;
                    }
                } else return s;
            } 
        }
    } catch (e) {
//        Kxlib_DebugVars([e],true);
//        return -1;
    }

}

function Kxlib_ReplaceAt(str,index,chr) {
    /* La fonction a été mise en place afin de combler les manquement des blibliothèques JS */
    if(index > str.length-1) return str;
    return str.substr(0,index) + chr + str.substr(index+1);
}

function Kxlib_TrimReplace(str,chr) {
    /* Remplace le debut et la fin de la chaine par un caractère */
    try {
        var l = str.length;
        if( l === 1 || l === 2) return str;
        return chr + str.substr(1,l-2) + chr;
    } catch (e){
        
    }
}
/*
var n;
e = [0,'a',1,n];
e = {
    a: 0,
    b:'a',
    c: 1,
    d: {
        r : 0,
        z: n
    }
};

//e = Kxlib_ReplaceIfUndefined (e);

//alert("TEST de ReplaceIfUndefined => "+e[0]);
alert("TEST de ReplaceIfUndefined => "+e.d.z);
        //*/
                
function Kxlib_ReplaceIfUndefined (e,re) {
    //e = Element à vérifier ( peut être un tableau ou un objet ), r = ReplacingElement, l'élément qui remplacera la valeur NULL 
    
    //Si l'élément de remplacement est NULL, on remplace avec une chaine vide.
    re = ( typeof re === "undefined" ) ? "" : re;
    
    //Détermination du type 
    if (  typeof e !== "undefined" && e !== null && ( $.isArray(e) || (typeof e === 'object')) ) {
        //Rechercher, Trouver, Identifier et Remplacer
        $.each(e,function(x,v) {
            if ( typeof v === "undefined" || v === null ) {
                e[x] = re;
            } else if ( $.isArray(v) || (typeof e === 'object') ) { 
                e[x] = Kxlib_ReplaceIfUndefined(v,re);
            }
        });
        return e;
    } else {
        //Existe pour tous les autres cas : boolean, string, number, ...
        if ( KgbLib_CheckNullity(e) ) return re;
    }
    
    return e;
}

function Kxlib_AjaxIsErrVolatile (s) {
    //QUESTION : Est ce que la chaine donnée en paramètre correspond à une chaine de type ERROR VOLATILE
    if ( KgbLib_CheckNullity(s) )
        return;
    
    var r = ( s.match(/^\s?__ERR_VOL_.+/) ) ? true : false;
    
    return r;
}

function Kxlib_HandleCurrUserGone () {
    //Centralise la gestion du cas où l'utilisateur CU ou OW n'existe plus
    
    //ACTION : On recharge la page de telle sorte que WOS s'en rende compte et le redirige vers une page 404.
    location.reload();
}

function Kxlib_HandleTrdMustReload (t,m,f,r,isb) {
    //isb = IsBlack
    
    if ( KgbLib_CheckNullity(m) | KgbLib_CheckNullity(r) | KgbLib_CheckNullity(isb) )
        return;
                        
//    Kxlib_DebugVars([t,m,f,r,isb],true);
//    Kxlib_DebugVars([m],true);
//    Kxlib_DebugVars([f],true);
//    Kxlib_DebugVars([r],true);
//    Kxlib_DebugVars([isb],true);
//    return;
    
    var bbd = ( isb === true ) ? new BlackBoardDialog() : new WhiteBoardDialog();
       
    var o = {
        title:t,
        message:m,
        /*
         * Quitter la page et aller ailleurs.
         * Si la valeur isd (IsDefault) est à false, la valeur link doit représenter un des cas connu dans BOARD.
         * Sinon, on doit renseigner l'url vers lequel il faut rediriger.
         * 
         * IMPORTANT : 
         *  Si on souhaite reload mais avec URL qui est différente (cas où un paramètre est différent) redir prend "reload_fly"
         *  Aussi, il faut que la valeur link soit renseigée 
         */ 
        fly: f, 
        redir: r
    };

    bbd.Dialog(o);
    
}

function Kxlib_AJAX_HandleFailed (gc,o) {
    //gc = GiverCode
    /*
     * Centralise la gestion du cas où la requete AJAX échoue.
     * CALLER peut utiliser le message par défaut ou spécifié un message.
     * Pour cela, il envoie le code dolphin correspondant.
     */
    //dc = CommonCode
    var dc = "ERR_COM_AJAX_FAIL"; 
    //Ce = CodeError
    var ce = ( KgbLib_CheckNullity(gc) ) ? dc : gc;
    
    //On affiche la notification
    var Nty = new Notifyzing();
    
    if ( KgbLib_CheckNullity(o) ) {
        Nty.FromUserAction(ce,null,true);
    } else {
        Nty.FromUserAction(ce,o,true);
    }
}

function Kxlib_AJAX_HandleDeny (gc,o) {
    //gc = GiverCode
    /*
     * Centralise la gestion du cas où la requete AJAX échoue.
     * CALLER peut utiliser le message par défaut ou spécifié un message.
     * Pour cela, il envoie le code dolphin correspondant.
     */
    //dc = CommonCode
    var dc = "ERR_COM_AJAX_DENY"; 
    //Ce = CodeError
    var ce = ( KgbLib_CheckNullity(gc) ) ? dc : gc;
    
    //On affiche la notification
    var Nty = new Notifyzing();
    
    if ( KgbLib_CheckNullity(o) ) {
        Nty.FromUserAction(ce,null,true);
    } else {
        Nty.FromUserAction(ce,o,true);
    }
    
}

function Kxlib_AjaxGblOnErr(xhr,ts) {
    //xhr : L'objet jqXHR; ts : textStatus
    /*
     * Gère les cas d'erreur AJAX arrivant dans la zone "onerror"
     * La plupart du temps, il s'agit d'erreur avec status HTTP : 301, 302, 401, 404, 500;
     * Dans certains cas, il peut s'agir d'erreur passée avec ts : "timeout", "error", "abort", and "parsererror".
     * PROCEDURE :
     *  1- On commence par vérifier le ts pour voir s'il s'agit d'un des cas prévisible
     *  2- On vérifie le status renvoyé
     */
    
    if ( KgbLib_CheckNullity(xhr) && KgbLib_CheckNullity(ts) ) {
        return;
    }
    
    switch (ts) {
        case "timeout":
            break;
        case "error":
            break;
        case "abort":
            break;
        case "timeout":
            break;
        case "parsererror":
            break;
        default:
                ts = null;
            break;
    }
    
    if ( !xhr.hasOwnProperty("status") | !xhr.status ) {
        return false;
    }
    //On vérifie le status. Normalement, si on arrive ici, c'est qu'on ne doit se concentrer que sur le cas de HTTP_STS
    switch (xhr.status) {
        case 301:
                //Moved Permanently 
            break;
        case 302:
                //Moved Temporarily
            break;
        case 400:
                //Bad Request
            break;
        case 401:
                //Unauthorized (Necessite une authentification. Souvent quand il y a une perte de SESSION)
                /*
                 * Pour éviter que l'utilisateur ait trop d'informations sur le fonctionnement des fichiers.
                 * N'est pas efficace si l'utilisateur décide de "Preserv Log"
                 */
                console.clear();
                
                var KD = new Kxlib_Dialog();
                KD.title = null;
                KD.message = "Vous n'êtes sans doutes pas autorisé à effectuer cette action. Veuillez recharger la page, pour tenter de résoudre le problème.";        
                KD.valid = {
                    text:"Recharger",
                    actionType:"reload",
                    action:null
                };
                KD.abort = null;
                KD.extralink = null;
                KD.isrestrictive = true;
                KD.background = "transparent-white";
            
//            Kxlib_DebugVars([th.title,th.message,JSON.stringify(th.valid),JSON.stringify(th.abort),JSON.stringify(th.extralink),th.isrestrictive,th.background],true);
                //On lance la procédure de test
                KD.execute();
                
            break;
        case 404:
                //Not Found
            break;
        case 500:
                //Internal Server Error
            break;
        default:
            break;
    }
}
            

function Kxlib_Dialog () {
    /*
     * [11-12-14]
     * Permet de faire apparaitre une boite de dialogue au niveau de window
     */
    
    this.title;
    this.message;
    this.valid = {};
    Object.defineProperty(this.valid,"text",{
        enumerable: true,
        configurable: true,
        writable: true,
        value: null
    });
    Object.defineProperty(this.valid,"actionType",{
        enumerable: true,
        configurable: true,
        writable: true,
        value: null
    });
    Object.defineProperty(this.valid,"action",{
        enumerable: true,
        configurable: true,
        writable: true,
        value: null
    });
    this.abort = {};
    Object.defineProperty(this.abort,"text",{
        enumerable: true,
        configurable: true,
        writable: true,
        value: null
    });
    Object.defineProperty(this.abort,"actionType",{
        enumerable: true,
        configurable: true,
        writable: true,
        value: null
    });
    Object.defineProperty(this.abort,"action",{
        enumerable: true,
        configurable: true,
        writable: true,
        value: null
    });
    this.extralink = {};
    Object.defineProperty(this.extralink,"text",{
        enumerable: true,
        configurable: true,
        writable: true,
        value: null
    });
    Object.defineProperty(this.extralink,"action",{
        enumerable: true,
        configurable: true,
        writable: true,
        value: null
    });
    this.isrestrictive;
    this.background;
    /*
     * Signale que si la boite de dialogue est déjà affichée, plus aucune autre boite ne peut être ouverte tant que celle ouverte a été traitée.
     */
    this.uniquemode = true;
     
    var th = this;
    try {
        /*********************************************************************/
        /*************************** PROCESS SCOPE ***************************/
        var _f_Gdf = function () {
            var ds = {
                actionType: /^(?:link|classbind|close|reload|home)$/i,
                isrestrictive: [false,true]
            };
            return ds;
        };

        th.execute = function () {
            
            if ( th.uniquemode === true && _f_Opened() ) {
                return;
            }
            //cr = Control Result
            var cr = _f_Controls();
            if ( cr !== true ) {
//                Kxlib_DebugVars([JSON.stringify(cr)],true);
                return cr;
            }
            _f_Prepare();

            _f_ShwDialg();
        };

        th.test = function () {
            /*
             * Permet d'effectuer un test sur le fonctionnement de la boite de Dialog
             */
            th.title = "Mon super titre";
            th.message = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla pulvinar semper luctus. Pellentesque justo felis, placerat sed lacus non, tempus maximus lacus. Morbi at libero sit amet justo cras amet.";
            th.valid = {
                text:null,
                actionType:"link",
                action:"/help"
            };
            th.abort = {
                text:null,
                actionType:"close",
                action:null
            };
//            th.extralink = null;
            //*
            th.extralink = {
                text:"Leatn more",
                action:"/help"
            };
            //*/
            th.isrestrictive = true;
            th.background = "transparent-lightwhite";
            
//            Kxlib_DebugVars([th.title,th.message,JSON.stringify(th.valid),JSON.stringify(th.abort),JSON.stringify(th.extralink),th.isrestrictive,th.background],true);
            //On lance la procédure de test
            th.execute();
            
        };
        
        var _f_Opened = function () {
            return ( $(".jb-kldlg-sprt").length && !$(".jb-kldlg-sprt").hasClass("this_hide") ) ? true : false;
        };

        var _f_Controls = function () {
            var err = [];
            
            if (! th.message ) {
                err.push("message");
            }
            //ActionTypeSkipAction
            var atsa = ["close","reload","home"];
//            Kxlib_DebugVars([th.valid, !th.valid.actionType, ( th.valid.actionType && !_f_Gdf().actionType.test(th.valid.actionType) ), ( $.inArray(th.valid.actionType,atsa) !== -1 && !th.valid.action ),,],true);
            if ( !KgbLib_CheckNullity(th.valid) && ( !th.valid.actionType | ( th.valid.actionType && !_f_Gdf().actionType.test(th.valid.actionType) ) | ( $.inArray(th.valid.actionType,atsa) === -1 && !th.valid.action ) ) ) {
                err.push("valid");
            }
            if ( !KgbLib_CheckNullity(th.abort) && ( !th.abort.actionType | ( th.abort.actionType && !_f_Gdf().actionType.test(th.abort.actionType) ) | ( $.inArray(th.valid.actionType,atsa) === -1 && !th.valid.action ) ) ) {
                err.push("abort");
            }
            if ( !KgbLib_CheckNullity(th.extralink) && !th.extralink.action ) {
                err.push("extralink");
            }

            return ( err.length ) ? err : true;
        };

        var _f_Prepare = function() {
            //Gestion des zones : header, footer
            var h;
            
            if ( !KgbLib_CheckNullity(th.title) && !( !KgbLib_CheckNullity(th.valid) | !KgbLib_CheckNullity(th.abort) | !KgbLib_CheckNullity(th.extralink) ) ) {
                $(".jb-kldlg-bdy-ftr").remove();
                h = 180;
            } else if ( KgbLib_CheckNullity(th.title) && ( !KgbLib_CheckNullity(th.valid) | !KgbLib_CheckNullity(th.abort) | !KgbLib_CheckNullity(th.extralink) ) ) {
                $(".jb-kldlg-bdy-hdr").remove();
                h = 170;
            } else if ( KgbLib_CheckNullity(th.title) && !( !KgbLib_CheckNullity(th.valid) | !KgbLib_CheckNullity(th.abort) | !KgbLib_CheckNullity(th.extralink) ) ) {
                $(".jb-kldlg-bdy-ftr").remove();
                $(".jb-kldlg-bdy-hdr").remove();
                h = 135;
            }
            if ( th.message.length < 205 ) {
                $(".jb-kldlg-bdy-bdy").height(50);
                h = ( h ) ? h-50 : $(".jb-kldlg-bdy").height()-50;
                $(".jb-kldlg-bdy").height(h);
            } else {
                $(".jb-kldlg-bdy").height(h);
            }
            /***********************************************/
            //On ajoute le message le cas échéant
            if ( th.title ) {
                $(".jb-kldlg-tle").html(th.title);
            }
            /***********************************************/
            //On ajoute le message 
            $(".jb-kldlg-bdy-m").html(th.message);
            /***********************************************/
            //On met en forme le bouton d'Action : VALID
            if ( th.valid && $(".jb-kldlg-action[data-action=valid]").length ) {
                //On change le texte du bouton le cas échéant
                if ( th.valid.text ) {
                    $(".jb-kldlg-action[data-action=valid]").text(th.valid.text);
                }
                if ( th.valid.actionType === "link" ) {
                    $(".jb-kldlg-action[data-action=valid]").attr("href",th.valid.action);
                } else if ( th.valid.actionType === "classbind" ) {
                    //[11-12-14] Non testé
                    $(".jb-kldlg-action[data-action=valid]").addClass(th.valid.action);
                } else if ( th.valid.actionType === "close" ) {
                    $(".jb-kldlg-action[data-action=valid]").click(function(e){
                        Kxlib_PreventDefault(e);
                        $(".jb-kldlg-sprt").addClass("this_hide");
                    });
                } else if ( th.valid.actionType === "reload" ) {
                    $(".jb-kldlg-action[data-action=valid]").addClass("jb-rld");
                } else if ( th.valid.actionType === "home" ) {
                    $(".jb-kldlg-action[data-action=valid]").click(function(e){
                        Kxlib_PreventDefault(e);
                        window.location.href = "/";
                    });
                }
            } else if ( th.valid && !$(".jb-kldlg-action[data-action=valid]").length ) {
                /*
                 * Anomalie.
                 * Peut être causé par l'utilisateur via selfxss, modification volontaire du DOM.
                 */
                 $(".jb-kldlg-bdy-ftr").remove();
            } else {
                $(".jb-kldlg-action[data-action=valid]").remove();
            }
            /***********************************************/
            //On met en forme le bouton d'Action : ABORT
            if ( th.abort && $(".jb-kldlg-action[data-action=abort]").length ) {
                //On change le texte du bouton le cas échéant
                if ( th.abort.text ) {
                    $(".jb-kldlg-action[data-action=abort]").text(th.abort.text);
                }
                if ( th.abort.actionType === "link" ) {
                    $(".jb-kldlg-action[data-action=abort]").attr("href",th.abort.action);
                } else if ( th.abort.actionType === "classbind" ) {
                    //[11-12-14] Non testé
                    $(".jb-kldlg-action[data-action=abort]").addClass(th.abort.action);
                } else if ( th.abort.actionType === "close" ) {
                    $(".jb-kldlg-action[data-action=abort]").click(function(e){
                        Kxlib_PreventDefault(e);
                        $(".jb-kldlg-sprt").addClass("this_hide");
                    });
                } else if ( th.abort.actionType === "reload" ) {
                    $(".jb-kldlg-action[data-action=abort]").addClass("jb-rld");
                } else if ( th.abort.actionType === "home" ) {
                    $(".jb-kldlg-action[data-action=abort]").click(function(e){
                        Kxlib_PreventDefault(e);
                        window.location.href = "/";
                    });
                }
            } else if ( th.abort && !$(".jb-kldlg-action[data-action=abort]").length ) {
                /*
                 * Anomalie.
                 * Peut être causé par l'utilisateur via selfxss, modification volontaire du DOM.
                 */
                 $(".jb-kldlg-bdy-ftr").remove();
            } else {
                $(".jb-kldlg-action[data-action=abort]").remove();
            }
            /***********************************************/
            //On met en forme LearnMore, le cas échéant
            if ( th.extralink && $(".jb-kldlg-lm").length ) {
                if ( th.extralink.text ) {
                    $(".jb-kldlg-lm").html(th.extralink.text);
                }
                $(".jb-kldlg-lm").attr("href",th.extralink.action);
            } else {
                 /*
                 * Anomalie.
                 * Peut être causé par l'utilisateur via selfxss, modification volontaire du DOM.
                 */
                 $(".jb-kldlg-lm").remove();
            }
            /***********************************************/
            //On met en forme la capacité de fermer la fenetre
            if ( th.isrestrictive ) {
                $(".jb-kldlg-fmr").remove();
            }
            /***********************************************/
            //On met en forme le background
            if ( th.background ) {
                //On vérifie s'il s'agit d'une couleur "pré-configurée"
                switch (th.background.toLowerCase()) {
                    case "transparent-lightwhite" :
                            th.background = "rgba(255,255,255,0.4)"; 
                        break;
                    case "transparent-white" :
                            th.background = "rgba(255,255,255,0.8)"; 
                        break;
                    case "transparent-lightblack" :
                            th.background = "rgba(0,0,0,0.4)"; 
                        break;
                    case "transparent-black" :
                            th.background = "rgba(0,0,0,0.8)"; 
                        break;
                    default:
                        break;
                }
                    
                $(".jb-kldlg-sprt").css({
                    "background-color": th.background
                });
            }

            return true;
        };

        /****************************************************************/
        /*************************** VIEW SCOPE *************************/
        var _f_ShwDialg = function(){
            $(".jb-kldlg-sprt").removeClass("this_hide");
        };

        var _f_HdDialg = function(){
            $(".jb-kldlg-sprt").addClass("this_hide");
        };
    } catch (ex) {
//        Kxlib_DebugVars([ex],true);
    }
}

function Kxlib_Decode_After_Encode (s) {
    /*
     * [NOTE 13-09-14] @author L.C.
     * Permet de "décoder" un texte qui contient des céractères encode par une foncton telle que htmlentities()
     * Normalement, avec un opérateur de sortie tel que echo(), les caractres sont re(encode).
     * Mais lorsqu'on ajoute un nouvel élément au DOM, il faut le faire soit même.
     * 
     * C'est une bricole mais l'important c'est que ça soit efficace.
     */
    
    if ( KgbLib_CheckNullity(s) ) { return; }
    var ns = $("<div/>").html(s).text();
    return ns;
}

function Kxlib_GetExtFileURL(c,p,opt) {
    //c : code du dossier, p : path. Il s'agit de l'adresse du fichier à partir de la racine obtenue via le code fournie.
    /*
     * Permet de récupérer l'url racine d'un dossier système.
     * Cette fonction est principalement utilisée pour l'affichage des fichiers externes. 
     * Cela permet de centraliser la gestion de le URL parent et facilite par la même occasion toute migration.
     */
    
    if ( !( !KgbLib_CheckNullity(c) &&  typeof c === "string" ) | !( !KgbLib_CheckNullity(p) && typeof p === "string" ) ) {
        return;
    }
    
    if ( p.charAt(0) === '/' ) {
        p = p.substring(1);
    }
    
    var u;
    switch (c.toLowerCase()) {
        case "sys_url_aud" :
                u = ENV_VARS("SYS_URL_AUD_PATH");
                if (! u ) {
                    return;
                }
                u = u+p;
                if ( !KgbLib_CheckNullity(opt) && $.inArray("_WITH_ROOTABS_OPTION",opt) !== -1 ) {
                    u = ENV_VARS("SYS_URL_AUD_ROOTABS")+u;
                }
            break;
        case "sys_url_img" :
                u = ENV_VARS("SYS_URL_IMG_PATH");
                if (! u ) {
                    return;
                }
                u = u+p;
                if ( !KgbLib_CheckNullity(opt) && $.inArray("_WITH_ROOTABS_OPTION",opt) !== -1 ) {
                    u = ENV_VARS("SYS_URL_IMG_ROOTABS")+u;
                }
            break;
        case "sys_url_script" :
                u = ENV_VARS("SYS_URL_SCRIPT_PATH");
                if (! u ) {
                    return;
                }
                u = u+p;
            break;
        case "sys_url_style" :
                u = ENV_VARS("SYS_URL_STYLE_PATH");
                if (! u ) {
                    return;
                }
                u = u+p;
            break;
        default :    
            return;
    }
    
    
    return u;
    
}

function Kxlib_PreventDefault(e) {
    if ( KgbLib_CheckNullity(e) ) return;
        
    try {
        if (e.preventDefault) 
            e.preventDefault();
        else
            e.returnValue = false;
    } catch (e) {
    }

}

function Kxlib_StopPropagation(e) {
    if ( KgbLib_CheckNullity(e) ) {
        return;
    }
    
//    var m = e.stopPropagation ? "W3C" : "IExplorer";
//    alert(m);
    
    try {    
        if (e.stopPropagation) { 
            e.stopPropagation();
        }
        else {
            window.event.cancelBubble = true;
            e.cancelBubble = true;
            return;
        }
    } catch (e) {
//        alert(e);
    }
}

/**
 * Permet de remplacer des usertags inertes en liens cliquables.
 * @param {string} s La chaine qui va faire l'objet d'une transformation
 * @param {array} p Le tableau contenant les pseudos qui seront utilisés pour créer les liens.
 * @param {string} c la ou les classes à appliquer aux éléments.
 * @returns {String|undefined} La chaine transformée.
 */
function Kxlib_UsertagFactory (s,p,c) {
    if ( KgbLib_CheckNullity(s) | typeof s !== "string" | KgbLib_CheckNullity(p) | !$.isArray(p) | KgbLib_CheckNullity(c) | typeof c !== "string" ) {
//        Kxlib_DebugVars([gbLib_CheckNullity(s), typeof s !== "string", KgbLib_CheckNullity(p), !$.isArray(p)]);
        return;
    }
    var ns__ = s;
    $.each(p,function(x,v){
        ns__ = ns__.replace(new RegExp("\@"+v+"","gi"),"<a class='"+c+"' href='/"+v.toLowerCase()+"' target='_blank'>@"+v+"</a>");
    });
    return ns__;
}

//var t = "Tu vois <a class='tqr-unq-user' href='/mouna'>@Mouna</a> c'est pareil. <a class='tqr-unq-user' href='/marigo'>@marigo</a> et @Märigo ce n'est pas pareil #demonstration #scientifique";
//var t = "Tu vois <a class='tqr-unq-user' href='/mouna'>@Mouna</a> c'est pareil. @marigo et @Märigo ce n'est pas pareil #demonstration #scientifique<a class='tqr-unq-user' href='/mouna'>@Mouna</a>";
//var t = "Tu vois @Mouna c'est pareil. @marigo et @Märigo ce n'est pas pareil #demonstration #scientifique";
function Kxlib_SplitByUsertags (s) {
   if ( KgbLib_CheckNullity(s) | typeof s !== "string" ) {
        return;
    }
    
    var r, match, uts = [];
    var ptrn = /(<a class='(?:tqr-unq-user|tqr-arp-user)' href='\/\@?[\w]+' target='_blank'>(\@[\w]+)<\/a>)/g;
    var uts = s.match();
    while ( match = ptrn.exec(s) ) {
        uts.push([match[1],match[2]]);
    }
//    alert(JSON.stringify(uts));
//        return;
    if ( uts ) {
        var pcs = [];
        ts__ = s; //[DEPUIS 07-08-15] @BOR
        $.each(uts,function(x,e){
            if ( typeof e === "object" && e.length === 2 && s !== undefined ) {
                var p__ = ts__.split(new RegExp(e[0].toString()));
//                var p__ = s.split(new RegExp(e[0].toString())); //[DEPUIS 07-08-15] @BOR
//                alert(JSON.stringify(p__));
//                return;
                pcs.push(p__[0]);
                pcs.push(e);
                
                /*
                 * [DEPUIS 07-08-15] @BOR 
                 *  On retire la portion déjà traitée pour un corriger le problème de multiples déclarations.
                 */
                z__ = p__[0].toString()+e[0].toString();
                ts__ = ts__.replace(z__, "");
                
                /*
                 *  Il peut arriver qu'un USTG soit répété plus d'une fois dans le texte. 
                 *  La conséquence est qu'on se retrouve avec plus de 2 pièces.
                 */
                /* //[DEPUIS 07-08-15] @BOR
                if ( p__.length > 2 ) {
                    var __ = "";
                    $.each(p__,function(x,v){
                        if ( typeof v === "string" && v.length ) {
                            var __2 = p__.length-1;
                            __ = ( x === __2 ) ? __.concat(v) : __.concat(v.toString(),e[0].toString());
                        }
                    });
                    s = __;
                } else {
                    s = p__[1];
                }
                //*/
                s = p__[1];
            }
        });
        pcs.push(s);
        r = pcs;
//        alert(JSON.stringify(r));
//        return;

        r = [];
        var $tst = $("<div/>");   
        $.each(pcs,function(x,e){
            if ( $.isArray(e) ) {
                var $a__ = $("<a/>",{
                    "class" : "tqr-unq-user",
                    "href" : "/"+e[1].toString().toLowerCase(),
                    "target" : "_blank"
                }).text(e[1].toString());
//                r.push($a__);
                $tst.append($a__);
            } else if ( typeof e === "string" && e ) {
                var sp__ = $("<span/>").text(e);
//                r.push(sp__);
                $tst.append(sp__);
            }
        });
        
        r = $tst;
    } else {
        r = s;
    }
    return $tst;
}

//var yo = Kxlib_SplitByUsertags(t);
//$("#home1_catchphrase").append(yo);



/**************************** MES CUSTOMS EVENTS ******************/
/**
 * List Of Custom Events (LOCE)
 */
var loce = new Array();

/**
 * -> noanswer : Signale que le seveur n'a renvoyé aucune donnée
 *    Cela signafie que lea variable JSON datas n'est pas définie ou est vide.  
 *    Il est possible que lors du déclenchement de l'évènement, on la lie avec getTime()
 */
loce.push(['noanswer',"Signale que le seveur n'a renvoyé aucune donnée."]);
/**
 * -> datasmissing : Signale que le serveur a répondu mais n'a renvoyé la donnée attendue.
 *    Cet évènement rentre dans la même case que 'datasready'.  
 *    Dans le cas où on le retour de données était obligatoire, CALLER peut déclencher une erreur.
 *    Les fonctions Ajax ne doivent donc plus traité les erreurs.
 */
loce.push(['datasmissing',"Signale que le serveur a répondu mais n'a renvoyé la donnée attendue."]);
/**
 * -> datasready : Signale que les données ont été recues. 
 *    Cela permet de gérer les cas des fonctions asynchrones.  
 */
loce.push(['datasready',"Signale que les données ont été recues."]);
/**
 * -> formCleared : Signale que le formulaire a été néttoyé (reset)
 */
loce.push(['formcleared',"Signale que le formulaire a été néttoyé."]);
/**
 * -> operended : Signale à l'envoyeur que l'opération en cours est terminée, il peut reprendre la main. 
 *                Ex : Faire quelque chose seulement lorsque l'animation est terminée. 
 */
loce.push(['operended',"Signale au receveur que l'opération en cours est terminée."]);
/**
 * -> knwerror : Signale à l'envoyeur que l'opération a échoué. Cependant, il s'agit d'un cas repertorié. Aussi, un code erreur est fourni.
 *                Ex : L'opération n'a pas pu aboutir car FEC_xxxx (FEC = FrontendErrorCode). 
 */
loce.push(['knwerror',"Signale au receveur que l'opération a échoué. Cependant, il s'agit d'un cas repertorié. Aussi, un code erreur est fourni."]);
/**
 * -> unknwerr : Signale à l'envoyeur que l'opération a échoué suite à une erreur inattendue. Utile pour la section 'onerror' d'AJAX.
 *                Ex : Le serveur n'a renvoyé aucune erreur. 
 */
loce.push(['unknwerr',"Signale à l'envoyeur que l'opération a échoué suite à une erreur inattendue."]);
/**
 * -> success : Signale au receveur que l'opération est un succes.
 *                
 */
loce.push(['success',"Signale au receveur que l'opération est un succes."]);

/**
 * -> timezoneready : Signale que le serveur a envoyé le fuseau-horaire.
 *    En temps normal, le fuseau-horaire correspondant à celui du compte actif à l'instant t.
 */
//ABONDONNE au profit de datasready
// loce.push(['tmzready',"Signale que le serveur a terminé l'envoi du fuseau-horaire."]); 
/**
 * -> notmzaval : Signale que le serveur n'a pas envoyé le fuseau-horaire demandé.
 *    Il s'agit d'une erreur de conception. Cependance, TIMEGOD (s'il s'agit du bon CALLER) peut se débrouiller sans (unsafe mode).
 */
//ABONDONNE au profit de datasmissing
//loce.push(['notmzaval',"Signale que le serveur n'a pas envoyé le fuseau-horaire qu'on lui a demandé. C'est une erreur de conception"]);