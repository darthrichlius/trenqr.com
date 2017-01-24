/* 
 * 
 */

var PgEnv;
try {
PgEnv = ( document.getElementById("tq-pg-env") ) ? JSON.parse(document.getElementById("tq-pg-env").innerHTML) : null;
    if (! PgEnv ) {
        throw "A fatal error occurred. The environment cannot be loaded properly.";
    }
} catch (ex) {
    console.log(ex);
}  
require.config({
    "baseUrl" : PgEnv.baseUrl,
    "paths" : {
        /*
        "es6"           : "/dev.trenqr.com/node_modules/requirejs-babel/es6",
        "babel"         : "/dev.trenqr.com/node_modules/requirejs-babel/babel-5.8.22.min",
    //*/
        "jquery"            : "//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min",
//        "jqueryui"      : "//ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min",
        "jqueryui"          : "https://code.jquery.com/ui/1.12.0/jquery-ui.min",
        "underscore"        : PgEnv.baseUrl+"r/c.c/underscore.min",
        "masonry"           : "//cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.min",
        "twemoji"           : "//twemoji.maxcdn.com/twemoji.min",
        "punycode"          : "//cdnjs.cloudflare.com/ajax/libs/punycode/1.4.0/punycode.min", 
        "sapp"              : PgEnv.baseUrl+"/r/csam"
    },
    urlArgs : (new Date()).getTime(),
    shim : {
        "masonry" : {
            deps: ['jquery']
        },
        "r/csam/tqrel.csam" : {
            deps: ["r/c.c/ajax_rules"]
        },
        "underscore": {
            exports: "_"
        }
    }
});
        
require(["require", "jquery", "jqueryui", "masonry", "twemoji", "punycode", "r/c.c/underscore.min", "r/c.c/bridget", "r/c.c/env.vars", "r/c.c/ajax_rules", "r/c.c/kxlib"], 
function( require, $, jqueryui, Masonry, twemoji, punycode, _ ) {
    try {
        /**********************************************************************************************************************************************************/
        /**********************************************************************************************************************************************************/
        /**********************************************************************************************************************************************************/
        
        /*
         * [DEPUIS 10-08-16]
         */
        require(["r/c.c/jquery.ui.touch-punch.min"]);
        
//        require(["r/c.c/jquery.ui.datepicker-fr"]);
        require(["r/c.c/env.vars"]);
        require(["r/c.c/olympe"]);
        require(["r/c.c/kxdate.enty"]);
        require(["r/csam/tqrel.csam"]);
        require(["r/c.c/com"]);
        require(["r/c.c/fr.dolphins"]);
        require(["r/csam/notify.csam"]);
        
        require(["r/d/header.d"]);
        require(["r/c.c/keyboard"]);
        require(["r/csam/timegod.csam"]);
        require(["r/csam/newsfeed.csam"]);
        require(["r/csam/postman.csam"]);
        require(["r/csam/unique.csam"]);
        require(["r/csam/evalbox.csam"]);
        require(["r/csam/bugzy.csam"]);
        
        require(["r/csam/ec.csam"]);
        require(["r/csam/asdrbnr.csam"]);
        
        require(["sapp/sapp-main"]);
        
        require(["r/s/hview.m"]);
        
    } catch (ex) {
        console.log("An error occurred the loading process. This is a fatal technical issue.");
    }
});