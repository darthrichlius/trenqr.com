function _PositionBar(s){var wl=$(s).data("wloc").split(",");var mc=new Object;$.each(wl,function(i,v){var f=false;if(v.toString().match(/do/g)){f=true;v=v.toString().replace("do","")}if(parseInt(v)||f)switch(i){case 0:mc["top"]=""+v+"px";break;case 1:mc["right"]=""+v+"px";break;case 2:mc["bottom"]=""+v+"px";break;case 3:mc["left"]=""+v+"px";break;case 4:mc["z-index"]=""+v+"";break}});$(s).css(mc)}
function StackHandler(){this.checkStk=1E4;this.l=0;this.fd;this.Start=function(){setInterval(function(){this.LaunchErr()},EB_T)};this.PushNewErr=function(o,wlo){Kxlib_Log("Push new ERR_OBJECT");wlo=typeof wlo==="boolean"&&wlo?wlo:false;if(!this.l){Kxlib_Log("New ERR_OBJECT is ALLOWED to be pushed");this.l=1;var t=(new Date).getTime().toString();window.esk.push([t.toString(),o,false]);this.l=0;Kxlib_Log("New ERR_OBJECT is NOT IN STACK");if(wlo&&window.esk.length===1){this.LaunchErr(t);Kxlib_Log("Due to wlo OPTION, Display is launch")}this.UpdateErrPendingNb();
this.Debug_ListStackElements();return t}else{Kxlib_Log("New ERR_OBJECT is NOT ALLOWED. Retry !");this.PushNewErr(o);this.Debug_ListStackElements()}};this.Debug_ListStackElements=function(){if(!window.esk.length){$("#list-id-esk div span").remove();return}$("#list-id-esk div span").remove();$.each(window.esk,function(ix,v){var $o=$("<span/>").html(ix+": "+v[0]+","+v[2]);$("#list-id-esk div").prepend($o)})};this.UpdateErrPendingNb=function(s){var cn=0;$.each(window.esk,function(ix,v){if(v[2]===false)cn++});
if(cn>1){$(".kxlib-dflt-error-bar").first().find(".e-b-v-l-e-nb").html(cn);$(".kxlib-dflt-error-bar").first().find(".e-b-vtop-list-err").removeClass("this_hide")}else $(".kxlib-dflt-error-bar").first().find(".e-b-vtop-list-err").addClass("this_hide")};this.LaunchErr=function(i){Kxlib_Log("StackHandler will now Treat an Err_Obect");if(!KgbLib_CheckNullity(i)&&!window.esk.length){Kxlib_Log("Spefific ERR_OBJECT TREATMENT started !");alert("LAUNCH just 1");var i=i.toString();var th=this;$.each(window.esk,
function(ix,v){Kxlib_DebugVars(["Index => "+ix+"; VALUE => "+v]);if(v[0]===i&&!v[2]){Kxlib_Log("Spefific ERR_OBJECT FOUND and valid!");th.fd(ix,v[1]);if(!KgbLib_CheckNullity(window.esk[ix][2].t,true)&&window.esk[ix][2].t==="a"){window.esk[ix][2]=true;this.RemoveFromStack(ix)}th.UpdateErrPendingNb()}})}else{var cn=window.esk.length;if(cn===0)return;cn=cn-1;if(!KgbLib_CheckNullity(window.esk[cn])&&!window.esk[cn][2]){var lo=window.esk[cn][1],er=window.esk[cn][0];this.fd(cn,lo);if(window.esk[cn][1].t===
"a"){window.esk[cn][2]=true;this.RemoveFromStack(ix)}return er}}};this.LaunchNextErr=function(){Kxlib_Log("StackHandler will now Treat Next ERR_OBJECT");var cn=window.esk.length;if(cn===0)return;cn=cn-1;if(!KgbLib_CheckNullity(window.esk[cn])&&!window.esk[cn][2]){var lo=window.esk[cn][1],er=window.esk[cn][0];this.fd(cn,lo);return er}};this.RemoveFromStack=function(i){var th=this;$.each(window.esk,function(ix,v){if(!KgbLib_CheckNullity(v)&&ix.toString()===i.toString()){window.esk.splice(ix,1);Kxlib_Log("L'erreur avec la r\u00e9f\u00e9rence : "+
i+", a \u00e9t\u00e9 retir\u00e9e de la pile !");th.Debug_ListStackElements()}})};this.DestroyStack=function(){window.esk=new Array}}
function ErrorBarVTop(){this.wait=5E3;this.MAX_TOLERATED=10;this._dfltBarSl=".kxlib-dflt-error-bar";this.CloseBar=function(s){var sh1=new StackHandler;if(window.esk.length===1){Kxlib_Log("Fermeture de la barre d'erreur suivant le cas : 1 seule erreur dans la pile.");$(s).addClass("this_hide");$(s).find(".e-b-vtop-msg").html("");var er=$(s).data("eref");sh1.RemoveFromStack(er);$(s).data("eref","");sh1.UpdateErrPendingNb()}else{Kxlib_Log("Fermeture de la barre d'erreur suivant le cas : plusieurs erreurs dans la pile.");
var er=$(s).data("eref");sh1.RemoveFromStack(er);sh1.LaunchNextErr();sh1.UpdateErrPendingNb()}};this.EB_DeclareErr=function(i,m,t){if(!KgbLib_CheckNullity(window.esk)&&window.esk.length===this.MAX_TOLERATED){var bs=new BlackBoardDialog,m=Kxlib_getDolphinsValue("err_com_toomuch_msg"),t=Kxlib_getDolphinsValue("err_com_toomuch_title");var v={"title":t,"message":m,"fly":"","redir":"reload"};bs.Dialog(v);return}var o={"i":i,"m":m,"t":t};StackHandler.prototype.fd=this._OpenBar;var sh=new StackHandler;sh.PushNewErr(o,
true)};this._OpenBar=function(er,o){var i=o.i,m=o.m;if(!KgbLib_CheckNullity(o.t))var t=o.t;var s=i.toString().indexOf("#")!==-1?i:"#"+i;$(s).find(".e-b-vtop-msg").html(m);$(s).data("eref",er);_PositionBar(s);$(s).removeClass("this_hide");switch(t){case "a":var th=this;setTimeout(function(){th.CloseBar(s)},this.wait);break;case "m":$(s).find(".e-b-vtop-err-max").removeClass("this_hide");break;default:var th=this;setTimeout(function(){th.CloseBar(s)},this.wait);break}};this.OpenBar_vDol=function(i,
dl){var m=Kxlib_getDolphinsValue(dl);if(typeof m==="undefined")m="[ The message following the error is unknow ]";this.OpenBar(i,m)}}(function(){var o=new ErrorBarVTop;$(".jsbind-close-trig").click(function(e){e.preventDefault();var s="#"+$(this).data("tar");o.CloseBar(s)})})();