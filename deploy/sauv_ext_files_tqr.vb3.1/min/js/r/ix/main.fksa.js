var PgEnv;try{PgEnv=document.getElementById("tq-pg-env")?JSON.parse(document.getElementById("tq-pg-env").innerHTML):null;if(!PgEnv)throw"A fatal error occurred. The environment cannot be loaded correctly.";}catch(ex){console.log(ex)}
require.config({"baseUrl":PgEnv.baseUrl,"paths":{"jquery":"//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min","jqueryui":"//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min","underscore":PgEnv.baseUrl+"r/c.c/underscore.min","twemoji":"//twemoji.maxcdn.com/twemoji.min","punycode":"//cdnjs.cloudflare.com/ajax/libs/punycode/1.4.0/punycode.min","sapp":PgEnv.baseUrl+"/r/csam"},urlArgs:(new Date).getTime(),shim:{"underscore":{exports:"_"}}});
require(["require","jquery","jqueryui","twemoji","punycode","r/c.c/underscore.min","r/c.c/env.vars","r/c.c/ajax_rules","r/c.c/kxlib"],function(require,$,jqueryui,twemoji,punycode,_){try{require(["r/c.c/jquery.ui.datepicker-fr"]);require(["r/c.c/olympe"]);require(["r/c.c/kxdate.enty"]);require(["r/c.c/com"]);require(["r/c.c/fr.dolphins"]);require(["r/csam/timegod.csam"]);require(["r/csam/notify.csam"]);require(["r/csam/ec.csam"]);require(["r/csam/asdrbnr.csam"]);require(["r/s/fksa.s"])}catch(ex){console.log("An error occurred the loading process. This is a fatal technical issue.")}});