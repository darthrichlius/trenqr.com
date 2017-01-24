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
        "jquery"        : "//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min",
//        "jqueryui"      : "//ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min",
        "jqueryui"      : "https://code.jquery.com/ui/1.12.0/jquery-ui.min",
        "underscore"    : PgEnv.baseUrl+"r/c.c/underscore.min",
        "masonry"       : "//cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.min",
        "twemoji"       : "//twemoji.maxcdn.com/twemoji.min",
        "punycode"      : "//cdnjs.cloudflare.com/ajax/libs/punycode/1.4.0/punycode.min", 
        "sapp"          : PgEnv.baseUrl+"/r/csam",
    },
    urlArgs : (new Date()).getTime(),
    shim : {
        "masonry" : {
            deps: ['jquery']
        },
        "r/csam/tqrel.csam" : {
            deps: ["r/c.c/ajax_rules"]
//            deps: ["r/c.c/ajax_rules","r/c.c/jquery.datetimepicker.full"]
        },
        "underscore": {
            exports: "_"
        },
    }
});

require(["require", "jquery", "jqueryui", "masonry", "twemoji", "punycode", 
    "r/c.c/underscore.min", "r/c.c/bridget", "r/c.c/env.vars", "r/c.c/ajax_rules", "r/c.c/kxlib","r/c.c/perfect-scrollbar-0.4.6.with-mousewheel.min"], 
function( require, $, jqueryui, Masonry, twemoji, punycode, _ ) {
    try {
        /**********************************************************************************************************************************************************/
        /**********************************************************************************************************************************************************/
        /**********************************************************************************************************************************************************/
        
        
        /*
         * [DEPUIS 10-08-16]
         */
        require(["r/c.c/jquery.ui.touch-punch.min"]);
        
//        require(["r/c.c/perfect-scrollbar-0.4.6.with-mousewheel.min"]);
        require(["r/c.c/animatescroll.noeasing"]);
//        require(["r/c.c/underscore.min"]);
        require(["r/c.c/datepicker-fr"]);

//            require(["r/c.c/env.vars"]);
//            require(["r/c.c/ajax_rules"]);
        require(["r/c.c/olympe"]);
//            require(["r/c.c/kxlib"]);
        require(["r/c.c/kxdate.enty"]);
        require(["r/c.c/fr.dolphins"]);
        
        require(["r/c.c/com"]); 
        // require(["r/csam/chatbox.csam"]); 
        // require(["r/d/main-header2.d"]);
        require(["r/c.c/feat_rules"]); 

        require(["r/c.c/mnfm"]);

        require(["r/csam/perm.csam"]); 
        require(["r/d/stream.d"]);
        // require(["r/d/aside_right.d.js -->
        require(["r/d/stream-cmts.d"]);
        require(["r/d/dashboard.d"]);
        require(["r/csam/cropper.csam"]);
        require(["r/d/acc_header.d"]);
        require(["r/d/tr_header.d"]);
        
        // require(["r/d/npost.brain.d.js -->
        
        require(["r/d/acc_rich_post.d"]);
        // require(["r/csam/corner_msg.csam.js-->
        require(["r/csam/notify.csam"]);
        require(["r/c.c/resizable"]);
        
        
        require(["r/csam/unique.csam"]);

        require(["r/s/account.s"]);
        
        if ( PgEnv ) {
            if ( PgEnv.hasOwnProperty("sector") && PgEnv.sector && $.inArray(PgEnv.sector.toLowerCase(),["tr","fv","abme"]) !== -1 ) {
                require(["r/s/pgtrssec.s"]);
            } else {
                require(["r/d/npost.brain.d"]);
            }
        }
        
        if ( PgEnv && PgEnv.pgvr.toUpperCase() !== "WU" ) {
            require(["r/csam/newsfeed.csam"]);
            require(["r/d/header.d"]);
            require(["r/csam/evalbox.csam"]);
            require(["r/csam/postman.csam"]);
            require(["r/csam/chatbox.csam"]); 
            require(["r/csam/search.csam"]);
            require(["r/csam/ec.csam"]);
            require(["r/csam/bugzy.csam"]);
            require(["r/d/brainomg.d"]);
            
            // C'est extrememnt important que fph soit appeler avant com.js car com.js utilise les methodes de FPH -->
            //DEFER
            require(["r/csam/tqrel.csam"]);
//            require(["r/csam/fph.csam"]);
            require(["r/d/newtr.brain.d"]);
            /*
             * [DEPUIS 23-06-16]
             */
//            require(["r/csam/rlc.csam"]);
        }
        
        if ( PgEnv && PgEnv.pgvr.toUpperCase() === "RO" ) {
            require(["r/csam/pflbio_asdbox.csam"]);
            require(["r/d/profil-hdr.d"]);
        }

        /*
         * UnUSed
         */ 
        // require(["r/c.c/hstg"]);
        require(["r/c.c/keyboard"]);

        require(["r/csam/timegod.csam"]);
        /*
         * [DEPUIS 18-07-15]
         */
        //require(["r/csam/friends.csam"]);

        require(["r/csam/asdapps.csam"]); 
         
        require(["r/csam/hlpmd.csam"]);
        require(["r/csam/fstvst.csam"]);
        

        // make Masonry a jQuery plugin
        $.bridget( 'masonry' , Masonry , $ );
        require(["r/csam/asdrbnr.csam"]);
        require(["r/csam/tia.csam"]);

        /*
         * (SUPER)APP SCOPE
         *      On entend par "SDAPP" les super-services auxquels ont droit les utilisateurs.
         *      Ils sont accessibles depuis le bouton "super service" à coté du logo "Trenqr"
         */
        require(["sapp/sapp-main"]);
        
        require(["r/csam/cltvwr.csam"],function(cltvwr){
            myC = new cltvwr();
        });
        
    } catch (ex) {
        console.log("An error occurred the loading process. This is a fatal technical issue.");
    }
});