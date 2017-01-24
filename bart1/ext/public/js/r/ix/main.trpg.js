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
        /* 
         * autobahn 0.8.2 
         * Permet de prendre en compte le Protocom WAMP1 qui est la seule compatible ave Ratchet.
         * Je ne voulais pas réapprendre à travailler avec une autre Library après avoir perdu 3 jours.
         * Sinon, une solution serait : autobahnJs(0.9) + crossbar.io + Thruway
         */
//        "autobahn"      : "http://autobahn.s3.amazonaws.com/js/autobahn.min"
        "autobahn"      : "https://autobahn.s3.amazonaws.com/autobahnjs/latest/autobahn.min"
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
        },
        "r/csam/ltc.csam" : {
            deps: ["autobahn"]
        }
    }
});
        
require(["require", "jquery", "jqueryui", "masonry", "twemoji", "punycode", "autobahn", "r/c.c/underscore.min", "r/c.c/bridget", "r/c.c/env.vars", "r/c.c/ajax_rules", "r/c.c/kxlib","r/c.c/perfect-scrollbar-0.4.6.with-mousewheel.min"], 
function( require, $, jqueryui, Masonry, twemoji, punycode, autobahn, _ ) {
    try {
      
        $( document ).ready(function() {
            require(["r/s/trend"]);
        });
        
        
        /*
         * [DEPUIS 10-08-16]
         */
        require(["r/c.c/jquery.ui.touch-punch.min"]);
      
        /**********************************************************************************************************************************************************/
        /**********************************************************************************************************************************************************/
        /**********************************************************************************************************************************************************/
//        require(["r/c.c/perfect-scrollbar-0.4.6.with-mousewheel.min"]);
        // require(["r/c.c/animatescroll.noeasing"]);
//        require(["r/c.c/underscore-min"]);
        /* [DEPUIS 22-08-15] @author BOR */
//        require(["r/c.c/env.vars"]);
        require(["r/c.c/olympe"]);
//        require(["r/c.c/ajax_rules"]);
        require(["r/c.c/fr.dolphins"]);
        
        /* require(["r/d/dashboard.d"]); */
//        require(["r/c.c/kxlib"]);
        require(["r/c.c/kxdate.enty"]);
        require(["r/c.c/com"]);
        require(["r/c.c/feat_rules"]);
        
        require(["r/c.c/mnfm"]);
        
        require(["r/csam/perm.csam"]);
        require(["r/csam/errorbar_vtop.csam"]);
        require(["r/csam/whiteblackboard.csam"]);

//        require(["r/s/trend"]);

        require(["r/csam/cropper.csam"]);
        require(["r/d/main-header2.d"]);
        require(["r/csam/tooltip.csam"]);
        require(["r/d/stream.d"]);
        require(["r/d/stream-cmts.d"]);
        // require(["r/d/aside_right.d"]); 
        // require(["r/dashboard.d"]); -->
        require(["r/csam/notify.csam"]);
        require(["r/c.c/resizable"]);
        require(["r/d/tr-filter-res.d"]);
        require(["r/csam/trpg-trends.csam"]);
        require(["r/d/newtrdart.d"]);

        // require(["r/s/account.s"]);

        // <script src="../public/js/hstg.js"></script> 
        require(["r/d/tr_header.d"]);
        require(["r/c.c/keyboard"]);
        // require(["r/c.c/noone"]);
        require(["r/csam/timegod.csam"]);
        
        if ( PgEnv && PgEnv.pgvr.toUpperCase() !== "WU" ) {
            require(["r/csam/newsfeed.csam"]);
            require(["r/d/header.d"]);
            require(["r/csam/evalbox.csam"]);
            require(["r/csam/postman.csam"]);
        }
        
        // [DEPUIS 18-07-15]
        require(["r/csam/tqrel.csam"]);
        // require(["r/csam/friends.csam"]);
        require(["r/csam/asdapps.csam"]);
        require(["r/csam/chatbox.csam"]);
        require(["r/csam/search.csam"]);
        
        require(["r/csam/unique.csam"]);
        require(["r/csam/bugzy.csam"]);
        require(["r/csam/hlpmd.csam"]);
        require(["r/csam/ec.csam"]);
        
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
        
        require(["r/csam/ltc.csam"],function(ltc){
            myltc = new ltc(autobahn);
        });
        
        
        /*
         * TEMPORARY
         */
        $("#tqr-wrng-betver-mx").remove();
        
    } catch (ex) {
        console.log(ex);
        console.log("An error occurred the loading process. This is a fatal technical issue.");
    }
});

/*
<div id="js_declare" class="this_hide">
        <script>
            document.getElementsByTagName("body")[0].removeChild(document.getElementById("js_declare"));
        </script>
        <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/perfect-scrollbar-0.4.6.with-mousewheel.min.js"></script>
        <!--<script src="{wos/sysdir:script_dir_uri}/r/c.c/animatescroll.noeasing.js"></script>-->
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/underscore-min.js"></script>
        <!-- [DEPUIS 22-08-15] @author BOR -->
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/env.vars.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/olympe.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/ajax_rules.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/fr.dolphins.js?{wos/systx:now}"></script>
        <!--<script src="{wos/sysdir:script_dir_uri}/r/d/dashboard.d.js"></script>-->
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxlib.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/kxdate.enty.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/com.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/feat_rules.js?{wos/systx:now}"></script> 
        
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/mnfm.js?{wos/systx:now}"></script>
        
        <script src="{wos/sysdir:script_dir_uri}/r/csam/perm.csam.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/r/csam/errorbar_vtop.csam.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/r/csam/whiteblackboard.csam.js?{wos/systx:now}"></script>

        <script src="{wos/sysdir:script_dir_uri}/r/s/trend.js?{wos/systx:now}"></script>

        <script src="{wos/sysdir:script_dir_uri}/r/csam/cropper.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/d/main-header2.d.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/r/csam/tooltip.csam.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/r/d/stream.d.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/r/d/stream-cmts.d.js"></script> 
        <!--<script src="{wos/sysdir:script_dir_uri}/r/d/aside_right.d.js?{wos/systx:now}"></script>-->
        <!--<script src="{wos/sysdir:script_dir_uri}/r/d/r/dashboard.d.js?{wos/systx:now}"></script>-->
        <script src="{wos/sysdir:script_dir_uri}/r/csam/notify.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/resizable.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/d/tr-filter-res.d.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/trpg-trends.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/d/newtrdart.d.js?{wos/systx:now}"></script>

        <!--<script src="{wos/sysdir:script_dir_uri}/r/s/account.s.js?{wos/systx:now}"></script>-->

        <!--<script src="../public/js/hstg.js"></script>-->
        <script src="{wos/sysdir:script_dir_uri}/r/d/header.d.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/d/tr_header.d.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/c.c/keyboard.js?{wos/systx:now}"></script>
        <!--<script src="{wos/sysdir:script_dir_uri}/r/c.c/noone.js?{wos/systx:now}"></script>-->
        <script src="{wos/sysdir:script_dir_uri}/r/csam/timegod.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/newsfeed.csam.js?{wos/systx:now}" defer></script>
        <!-- [DEPUIS 18-07-15] -->
        <script src="{wos/sysdir:script_dir_uri}/r/csam/tqrel.csam.js?{wos/systx:now}" defer></script>
        <!--<script src="{wos/sysdir:script_dir_uri}/r/csam/friends.csam.js?{wos/systx:now}" defer></script>-->
        <script src="{wos/sysdir:script_dir_uri}/r/csam/asdapps.csam.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/r/csam/chatbox.csam.js?{wos/systx:now}"></script> 
        <script src="{wos/sysdir:script_dir_uri}/r/csam/search.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/postman.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/evalbox.csam.js?{wos/systx:now}" defer></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/unique.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/bugzy.csam.js?{wos/systx:now}" defer></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/hlpmd.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/ec.csam.js?{wos/systx:now}"></script>
        <script src="{wos/sysdir:script_dir_uri}/r/csam/asdrbnr.csam.js?{wos/systx:now}" defer></script>
        <!--<script src="{wos/sysdir:script_dir_uri}/ix/trpg-index.ix.js?{wos/systx:now}"></script>-->
        </div>
        //*/