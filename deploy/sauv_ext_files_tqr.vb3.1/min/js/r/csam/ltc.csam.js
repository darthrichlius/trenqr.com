define("r/csam/ltc.csam",["autobahn"],function(ab){return function LTC(cfd){var cargs=cfd;var iRdy,md_slc,md_props;var trgList=[""];var cfdKeys=["trigger","action"];var $sprt=$(".jb-tqr-ltc-sprt");var _xhr_sdms;var KEY_RETURN=13;var ab_connection;var ab_session;var ab_ssid;var ab_isOpen=false;var ab_ntvChannels={"onjoin":"ltc.session.event.onjoin","onleave":"ltc.session.event.onleave"};var mrChannels={};var _f_Gdf=function(){var dt={ab_url:"wss://trenqr.com/l/?tid=",ab_realm:"ltc.realm.wolverine",
ab_mxrt:30};return dt};var _f_Init=function(){try{}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_Action=function(x,a,e){try{if(KgbLib_CheckNullity(x)|(KgbLib_CheckNullity($(x).data("action"))&&!a))return;var _a=KgbLib_CheckNullity($(x).data("action"))?a:$(x).data("action");switch(_a){case "send-msg":_f_AddMsg(x,e);break;case "tag-user":_f_TagUser(x);break;case "ltc-hide":_f_HideApp(x);break;case "ltc-show":_f_ShowApp(x);break;case "hoit-add":_f_Hoit_Add(x);break;case "hoit-remove":_f_Hoit_Rmv(x);
break;case "hoit-open":_f_Hoit_Io(true);break;case "hoit-close":_f_Hoit_Io();break;default:return}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_AddMsg=function(x,e){try{if(KgbLib_CheckNullity(x))return;var text=$(".jb-tqr-ltc-s-f-txar").val();$(".jb-tqr-ltc-s-f-txar").val("");var s=$("<span/>"),hopi=(new Date).getTime();var prm={tri:Kxlib_GetTrendPropIfExist()?Kxlib_GetTrendPropIfExist().trid:null,wcsi:ab_ssid,tx:text,pi:"",pt:""};_f_Srv_SndMs(hopi,prm.tri,prm.wcsi,prm.tx,
prm.pi,prm.pt,x,s);$(s).on("datasready",function(e,d){if(KgbLib_CheckNullity(d))return;$(x).data("lk",0);_xhr_sdms=null})}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_ApdMsg=function(ms){try{if(KgbLib_CheckNullity(ms))return;$(".jb-tqr-ltc-scrn-wlc").stop(true,true).fadeOut().addClass("this_hide");$(".jb-tqr-ltc-list-lvms").stop(true,true).hide().removeClass("this_hide").fadeIn();if($(".jb-tqr-ltc-msg-art").filter("[data-item='".concat(ms.msg.id,"']")).length)return;var mm=
_f_PprMdl(ms);mm=_f_RbdMdl(mm);$(".jb-tqr-ltc-list-lvms").append(mm);_f_ScrollZn();$(".jb-tqr-ltc-scrn-bdy").perfectScrollbar("update")}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_TagUser=function(x){try{if(KgbLib_CheckNullity(x))return;var $mm=$(x).data("scp")==="std"?$(x).closest(".jb-tqr-ltc-msg-art"):$(x).closest(".jb-tqr-ltc-s-ap-hoit-amx");var text=$mm.find(".jb-tqr-ltc-m-ubx-p").text();$(".jb-tqr-ltc-s-f-txar").val(function(a,prev){return prev.concat(text," ")});
$(".jb-tqr-ltc-s-f-txar").focus()}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_Start=function(d){try{if(KgbLib_CheckNullity(d))return}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_LtcIsOnpn=function(){try{var akx=$(".jb-tqr-ltc-scrn-cnxnb").data("access");return akx&&akx==="1"?true:false}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_ShowApp=function(x){try{if(KgbLib_CheckNullity(x))return;var trmetas=Kxlib_GetTrendPropIfExist();
if(!trmetas&&KgbLib_CheckNullity(trmetas.trid))throw"Unable to get acces to Trend's datas. They are mandatory.";var r=parseInt($(".jb-page").css("margin-right").slice(0,-2));$(".jb-tqr-ltc-scrn").stop(true,true).animate({right:r},function(){if(!ab_isOpen){ab_connection=new ab.Connection({url:_f_Gdf().ab_url.concat(trmetas.trid),realm:_f_Gdf().ab_realm,max_retries:_f_Gdf().ab_mxrt});ab_connection.onopen=onConnect;ab_connection.onclose=onClose;ab_connection.open()}}).data("access",1)}catch(ex){Kxlib_DebugVars([ex,
ex.fileName,ex.lineNumber],true)}};var _f_HideApp=function(x){try{if(KgbLib_CheckNullity(x))return;if(_f_Hoit_Io())_f_Hoit_Io();var r=($(".jb-tqr-ltc-scrn").width()+2)*-1;$(".jb-tqr-ltc-scrn").stop(true,true).animate({right:r},function(){}).data("access",0)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_ScrollZn=function(){try{var $tar=$(".jb-tqr-ltc-scrn-bdy");if($tar.find(".jb-tqr-ltc-msg-art").length){var r__=$tar.find(".jb-tqr-ltc-msg-art"),h__=0;$.each(r__,function(i,
rc){h__+=$(rc).height()});$($tar).animate({scrollTop:h__},1500)}else $($tar).animate({scrollTop:$($tar).height()},1500)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_Hoit_IsOpen=function(){try{var akx=$(".jb-tqr-ltc-s-ap-hoit").data("access");return akx&&akx==="access"?true:false}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_Hoit_Io=function(shw){try{var pazn_w=500,ltc_w=340;if(shw){$(".jb-tqr-ltc-scrn").stop(true,true).css({overflow:"visible"},
400);$(".jb-tqr-ltc-s-ap-hoit").data("access",1)}else{$(".jb-tqr-ltc-scrn").stop(true,true).css({overflow:"hidden"},400);$(".jb-tqr-ltc-s-ap-hoit").data("access",0)}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_Hoit_Add=function(x){try{if(KgbLib_CheckNullity(x))return;var amx=$(x).closest(".jb-tqr-ltc-msg-art");if(!$(amx).length)return;var ajca=$(amx).data("ajcache");if(KgbLib_CheckNullity(ajca))return;var ajca_o=$.parseJSON(ajca);if(!(typeof ajca_o==="object"&&!KgbLib_CheckNullity(ajca_o)))return;
var ai=ajca_o.msg.id;if(!ai|$(".jb-tqr-ltc-s-ap-hoit-amx[data-item='"+ai+"']").length){if(!_f_LtcIsOnpn())_f_Hoit_Io(true);return}var m=_f_Hoit_PprA(ajca_o);m=_f_Hoit_RbdMdl(m);if(!_f_Hoit_IsOpen())_f_Hoit_Io(true);$(m).hide().prependTo(".jb-tqr-ltc-s-ap-hoit-bdy").fadeIn();var nb=$(".jb-tqr-ltc-s-ap-hoit-bdy").find(".jb-tqr-ltc-s-ap-hoit-amx").length;$(".jb-tqr-ltc-s-f-hoit ._figure").text("(".concat(nb,")"))}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_Hoit_Rmv=function(x){try{if(KgbLib_CheckNullity(x))return;
var amx=$(x).closest(".jb-tqr-ltc-s-ap-hoit-amx");if(!$(amx).length)return;$(amx).remove();if(!$(".jb-tqr-ltc-s-ap-hoit-amx").length)_f_Hoit_Io();var nb=$(".jb-tqr-ltc-s-ap-hoit-bdy").find(".jb-tqr-ltc-s-ap-hoit-amx").length;if(nb)$(".jb-tqr-ltc-s-f-hoit ._figure").text("(".concat(nb,")"));else $(".jb-tqr-ltc-s-f-hoit ._figure").text("")}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var onConnect=function(session,transport,details){try{ab_session=session;ab_ssid=session.id;var tuname=
"ltc.chat.app.mtune.";var trmetas=Kxlib_GetTrendPropIfExist();if(KgbLib_CheckNullity(trmetas)|!(trmetas.hasOwnProperty("trid")&&trmetas.trid))return;tuname+=trmetas.trid;ab_ntvChannels["mtune"]=tuname;subToNatives();ab_isOpen=true;$(".jb-tqr-trpg-ckp-akxn").data({"cntd_sts":"connected"}).attr({"data-cntd_sts":"connected"});$(".jb-tqr-ltc-scrn-pndg").addClass("this_hide");$(".jb-tqr-ltc-scrn-cnxnb").removeClass("this_hide");Kxlib_DebugVars(["LTC : Connected from JS :"+session.id])}catch(ex){Kxlib_DebugVars([ex,
ex.fileName,ex.lineNumber],true)}};var subToNatives=function(){try{$.each(ab_ntvChannels,function(cn,cl){switch(cn){case "onjoin":ab_session.subscribe(cl,onJoin);break;case "mtune":ab_session.subscribe(cl,onMessage);break;case "onleave":ab_session.subscribe(cl,onLeave);break}})}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var onJoin=function(args,kwargs){try{Kxlib_DebugVars(["LTC : onJoin",JSON.stringify(kwargs),kwargs.online]);$(".jb-tqr-ltc-scrn-cnxnb ._figure").text(kwargs.online)}catch(ex){Kxlib_DebugVars([ex,
ex.fileName,ex.lineNumber],true)}};var onMessage=function(args,kwargs){try{Kxlib_DebugVars(["LTC : onMessage",JSON.stringify(kwargs)]);$(".jb-tqr-ltc-scrn-cnxnb ._figure").text(kwargs.online);_f_ApdMsg(kwargs)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var onLeave=function(args,kwargs){try{Kxlib_DebugVars(["LTC : onLeave",JSON.stringify(kwargs),kwargs.online]);$(".jb-tqr-ltc-scrn-cnxnb ._figure").text(kwargs.online)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};
var onClose=function(reason){try{switch(reason){case "closed":Kxlib_DebugVars(["LTC : onClose",JSON.stringify(reason)]);break;case "lost":Kxlib_DebugVars(["LTC : onClose",JSON.stringify(reason)]);break;case "unreachable":Kxlib_DebugVars(["LTC : onClose",JSON.stringify(reason)]);break;case "unsupported":Kxlib_DebugVars(["LTC : onClose",JSON.stringify(reason)]);break}ab_isOpen=false;$(".jb-tqr-trpg-ckp-akxn").data({"cntd_sts":"pending"}).attr({"data-cntd_sts":"pending"});$(".jb-tqr-ltc-scrn-cnxnb").addClass("this_hide");
$(".jb-tqr-ltc-scrn-pndg").removeClass("this_hide")}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var subscribeTo=function(chan){try{}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _Ax_SndMs=Kxlib_GetAjaxRules("TQR_LTC_MSG_SEND");var _f_Srv_SndMs=function(hopi,tri,wcsi,ms,pi,pt,x,s){if(KgbLib_CheckNullity(hopi)|KgbLib_CheckNullity(tri)|KgbLib_CheckNullity(wcsi)|KgbLib_CheckNullity(ms)|KgbLib_CheckNullity(x)|KgbLib_CheckNullity(s))return;var onsuccess=function(datas){try{if(!KgbLib_CheckNullity(datas))datas=
JSON.parse(datas);else{if(!KgbLib_CheckNullity(x))$(x).data("lk",0);_xhr_sdms=null;return}if(!KgbLib_CheckNullity(datas.err)){if(!KgbLib_CheckNullity(x))$(x).data("lk",0);_xhr_sdms=null;if(Kxlib_AjaxIsErrVolatile(datas.err))switch(datas.err){case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_USER_GONE":case "__ERR_VOL_U_G":case "__ERR_VOL_CU_GONE":Kxlib_HandleCurrUserGone();break;case "__ERR_VOL_FAILED":Kxlib_AJAX_HandleFailed();break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":break;case "__ERR_VOL_DNY_AKX_AUTH":if($(".jb-tqr-btm-lock").length){$(".jb-tqr-btm-lock").removeClass("this_hide");
$(".jb-tqr-btm-lock-fd").removeClass("this_hide")}break;default:Kxlib_AJAX_HandleFailed();break}return}else if(!KgbLib_CheckNullity(datas.return)){var ds=[datas.return];$(s).trigger("datasready",ds)}else{var ds=[datas.return];$(s).trigger("operended",ds);return}}catch(ex){if(!KgbLib_CheckNullity(x))$(x).data("lk",0);_xhr_sdms=null;Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");return};
var curl=document.URL;var toSend={"urqid":_Ax_SndMs.urqid,"datas":{"hopi":hopi,"tri":tri,"wcsi":wcsi,"ms":ms,"lmpi":pi,"lmpt":pt,"cu":curl}};_xhr_sdms=Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_SndMs.url,wcrdtl:_Ax_SndMs.wcrdtl})};var _f_PprMdl=function(d){try{if(KgbLib_CheckNullity(d))return;var $ml='<article class="tqr-ltc-msg-art jb-tqr-ltc-msg-art" >';$ml+="<div>";$ml+='<a class="tqr-ltc-m-ubx jb-tqr-ltc-m-ubx" href="">';$ml+='<img class="tqr-ltc-m-ubx-i jb-tqr-ltc-m-ubx-i" height="28" width="28" alt="Nom Complet (@Pseudo)" src="http://www.placehold.it/28/28" />';
$ml+='<a class="tqr-ltc-m-ubx-p jb-tqr-ltc-m-ubx-p jb-tqr-ltc-action cursor-pointer" data-action="tag-user" data-scp="std" title="Nom Complet">@Pseudo</a>';$ml+="</a>";$ml+='<span class="tqr-ltc-m-cny jb-tqr-ltc-m-cny this_hide">[FR]</span>';$ml+='<span class="tqr-ltc-m-msg jb-tqr-ltc-m-msg"></span>';$ml+="</div>";$ml+='<div class="tqr-ltc-msg-art-ftr">';$ml+='<a class="tqr-ltc-amx-opts jb-tqr-ltc-amx-opts" data-action="hoit-add"></a>';$ml+="</div>";$ml+="</article>";$ml=$($.parseHTML($ml));$ml.attr("data-item",
d.msg.id).data({"item":d.msg.id,"time":d.msg.date,"ajcache":JSON.stringify(d)}).attr({"item":d.msg.id,"time":d.msg.date,"ajcache":JSON.stringify(d)});$ml.find(".jb-tqr-ltc-amx-opts[data-action='hoit-add']").text("Mettre de c\u00f4t\u00e9");$ml.find(".jb-tqr-ltc-m-ubx").prop("href","/".concat(d.user.ps));$ml.find(".jb-tqr-ltc-m-ubx-i").prop("src",d.user.pp);$ml.find(".jb-tqr-ltc-m-ubx-p").text("@".concat(d.user.ps));var ustgs=d.msg.ustgs?d.msg.ustgs:null;var hashs=d.msg.hashs?d.msg.hashs:null;var txt=
d.msg.ms;var rtxt=Kxlib_TextEmpow(txt,ustgs,hashs,null,{"ena_inner_link":{"all":false,"only":"fksa"},emoji:{"size":36,"size_css":20,"position_y":3}});$ml.find(".jb-tqr-ltc-m-msg").text("").append(rtxt);if(d.hasOwnProperty("xtras")&&!KgbLib_CheckNullity(d.xtras)&&!KgbLib_CheckNullity(d.xtras.is_admin)&&d.xtras.is_admin===true)$ml.addClass("admin");return $ml}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_RbdMdl=function(mb){try{if(KgbLib_CheckNullity(mb))return;$rm=$(mb);$rm.find(".jb-tqr-ltc-amx-opts, .jb-tqr-ltc-action").click(function(e){Kxlib_PreventDefault(e);
_f_Action(this,null,e)});return $rm}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_Hoit_PprA=function(d){try{if(KgbLib_CheckNullity(d))return;var $ml='<article class="tqr-ltc-msg-art hoit jb-tqr-ltc-s-ap-hoit-amx" >';$ml+="<div>";$ml+='<a class="tqr-ltc-m-ubx jb-tqr-ltc-m-ubx" href="">';$ml+='<img class="tqr-ltc-m-ubx-i jb-tqr-ltc-m-ubx-i" height="28" width="28" alt="Nom Complet (@Pseudo)" src="http://www.placehold.it/28/28" />';$ml+='<a class="tqr-ltc-m-ubx-p jb-tqr-ltc-m-ubx-p jb-tqr-ltc-action cursor-pointer" data-action="tag-user" data-scp="hoit" title="Nom Complet">@Pseudo</a>';
$ml+="</a>";$ml+='<span class="tqr-ltc-m-cny jb-tqr-ltc-m-cny this_hide">[FR]</span>';$ml+='<span class="tqr-ltc-m-msg jb-tqr-ltc-m-msg"></span>';$ml+="</div>";$ml+='<div class="tqr-ltc-s-ap-hoit-amx-ftr">';$ml+='<a class="tqr-ltc-s-ap-hoit-amx-opts jb-tqr-ltc-s-ap-hoit-amx-opts" data-css="hoit-remove" data-action="hoit-remove" title="Retirer de la liste">Retirer</a>';$ml+="</div>";$ml+="</article>";$ml=$($.parseHTML($ml));$ml.attr("data-item",d.msg.id).data({"item":d.msg.id,"time":d.msg.date,"ajcache":JSON.stringify(d)}).attr({"item":d.msg.id,
"time":d.msg.date,"ajcache":JSON.stringify(d)});$ml.find(".jb-tqr-ltc-amx-opts[data-action='hoit-add']").text("Mettre de c\u00f4t\u00e9");$ml.find(".jb-tqr-ltc-m-ubx").prop("href","/".concat(d.user.ps));$ml.find(".jb-tqr-ltc-m-ubx-i").prop("src",d.user.pp);$ml.find(".jb-tqr-ltc-m-ubx-p").text("@".concat(d.user.ps));var ustgs=d.msg.ustgs?d.msg.ustgs:null;var hashs=d.msg.hashs?d.msg.hashs:null;var txt=d.msg.ms;var rtxt=Kxlib_TextEmpow(txt,ustgs,hashs,null,{"ena_link_fk":true,emoji:{"size":36,"size_css":20,
"position_y":3}});$ml.find(".jb-tqr-ltc-m-msg").text("").append(rtxt);return $ml}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_Hoit_RbdMdl=function(mb){try{if(KgbLib_CheckNullity(mb))return;$rm=$(mb);$rm.find(".jb-tqr-ltc-s-ap-hoit-amx-opts, .jb-tqr-ltc-action").click(function(e){Kxlib_PreventDefault(e);_f_Action(this,null,e)});return $rm}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_spnr=function(shw){try{if(shw)$(".jb-tlkb-uqv-a-m-s-mx").removeClass("this_hide");
else $(".jb-tlkb-uqv-a-m-s-mx").addClass("this_hide")}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_ldmore=function(shw){try{if(shw)$(".jb-tlkb-uqv-a-m-l-a").removeClass("this_hide");else $(".jb-tlkb-uqv-a-m-l-a").addClass("this_hide")}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};$(".jb-tqr-ltc-action, .jb-tqr-ltc-s-ap-hoit-amx-opts, .jb-tqr-ltc-s-ap-hoit-fmr").off("click").click(function(e){Kxlib_PreventDefault(e);_f_Action(this,null,e)});$(".jb-tqr-ltc-s-f-txar").keypress(function(e){if(e.charCode===
13){Kxlib_PreventDefault(e);_f_Action($(".jb-tqr-ltc-action[data-action='send-msg']"),null,e)}});_f_Init()}});