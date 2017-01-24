function TIMEGOD(){var _loopref=2E4;var _lpTmzKnfl=3E5;this.UTCsrc;this.codeErr;this.SRV_TMZ;var _frcTmz;var __DIS_TIME_MODE_DATE=0;var __DIS_TIME_MODE_INTRV=1;var _dis_days_mode=__DIS_TIME_MODE_DATE;var _f_GetNowTimeAtNullUTC=function(){return(new Date).getTime()};var _f_GetNowTimeAtLocalUTC=function(){return(new KxDate(true)).getTime()};this.InitTestDatas=function(){var d1=new Date(2013,11,31,23,59,59,998);var now=d1.getTime();var t10s=now+1E4;var t30s=now+3E4;var t45s=now+45E3;var t2m=now+2*60*
1E3;var t5m=now+5*60*1E3;var t50m=now+50*60*1E3;var t1d=now+24*36E5;$(".kxlib_tgspy").data("tgs-crd",now);$(".kxlib_tgspy").data("tgs-dd-atn",0);$(".kxlib_tgspy").data("tgs-dd-uut",0+72E5)};this.LocChanged=function(m){if(KgbLib_CheckNullity(m))return;var el=$("<span/>");this._Srv_LocChanged(el,m);return el};this.ugloc_uq="DID_USER_CH_LOC";this.ugloc_ajaxr=Kxlib_GetAjaxRules(this.ugloc_uq);this._Srv_LocChanged=function(el,m){if(KgbLib_CheckNullity(el)||KgbLib_CheckNullity(m))return;var th=this;var onsuccess=
function(datas){try{if(!KgbLib_CheckNullity(datas))datas=JSON.parse(datas);else{var t=(new Date).getTime().toString();$(el).trigger("noanswer",[t]);return}if(!KgbLib_CheckNullity(datas.err));if(!KgbLib_CheckNullity(datas.return)){var tp=[datas.return];$(el).trigger("datasready",[tp]);return}else{$(el).trigger("datasmissing");return}}catch(e){}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b)};var toSend={"urqid":th.ugloc_uq,"datas":{"m":m}};Kx_XHR_Send(toSend,"post",th.ugloc_ajaxr.url,onerror,
onsuccess)};this.GetGenLoopTimeRef=function(){return _loopref};this._GetProperLoopTimeRef=function(t){if(KgbLib_CheckNullity(t))return;var ft=parseInt(t);if(typeof ft!=="number"||ft<0)return false;if(ft>=0&&ft<6E4)return 2E4;else if(ft>=6E4&&ft<36E5)return 6E4;else if(ft>=36E5&&ft<864E5)return 36E5;else return 864E5};this.UpdSpies=function(g){if(KgbLib_CheckNullity(g))return;try{this.UTCsrc=!KgbLib_CheckNullity(g)?g:"s";var $ts=$(".kxlib_tgspy");var th=this;$.each($ts,function(i,e){if(!KgbLib_CheckNullity($(e).data("tgs-crd")))th.UpdateThisTimeSpies(e)})}catch(ex){Kxlib_DebugVars([ex,
ex.lineNumber],true)}};this.GetUnit=function(v){if(KgbLib_CheckNullity(v))return;switch(v){case 2E4:return["s",Kxlib_getDolphinsValue("tg_unit_sec")];break;case 6E4:return["m",Kxlib_getDolphinsValue("tg_unit_min")];break;case 36E5:return["h",Kxlib_getDolphinsValue("tg_unit_hour")];break;case 864E5:return["d",Kxlib_getDolphinsValue("tg_unit_day")];break}};this.AddUnitSteps=function(st,uta){if(KgbLib_CheckNullity(st)||KgbLib_CheckNullity(uta))return;var nv;switch(uta[0]){case "s":if(st<3){nv=st*2E4/
1E3;return[nv,uta[1]]}else if(st>=3&&st<180){nv=Math.floor(st*2E4/6E4);var fo=this.GetUnit(6E4);return[nv,fo[1]]}else if(st>=180&&st<4320){nv=Math.floor(st*2E4/36E5);var fo=this.GetUnit(36E5);fo[1];return[nv,fo[1]]}else if(st>=4320){nv=Math.floor(st*2E4/864E5);var fo=this.GetUnit(864E5);fo[1];return[nv,fo[1]]}break;case "m":case "h":case "d":return[st,uta[1]];break}return res};this.UpdateThisTimeSpies=function(o){if(KgbLib_CheckNullity(o))return;var r=_f_SpyGetTm(o);if(typeof r==="undefined")return;
if(!KgbLib_CheckNullity(_frcTmz)&&typeof _frcTmz==="number")tz=_frcTmz;else tz=_f_CalTmzFrmLclSpecs(r[1],r[2]);_f_TryUpdPrcs(o,r,tz)};var _f_TryUpdPrcs=function(o,r,tz){try{var ct=_f_GetNowTimeAtNullUTC();var dfcd=ct-parseInt(r[0]);if(dfcd>=0&&dfcd<2E4){$(o).data("tgs-dd-atn",0);$(o).data("tgs-dd-uut",tz);var svl=Kxlib_getDolphinsValue("TG_TIME_NOW");$(o).find(".tgs-uni").html(svl);$(o).find(".tgs-val").html(0);$(o).find(".tgs-frm").addClass("this_hide");$(o).find(".tgs-val").addClass("this_hide")}else if(dfcd>
2E4){if(dfcd>=864E5)if(_dis_days_mode===__DIS_TIME_MODE_INTRV);else{try{var scd=new KxDate(r[0]);scd.SetUTC(tz);var snw=new KxDate;snw.SetUTC(tz)}catch(e){}var trty=scd.getFullYear()!==snw.getFullYear()?true:false;var nvl,nvl_m=Kxlib_getDolphinsValue("TG_MONTH_"+scd.getMonth());if(!trty)switch(Kxlib_getDolphinsValue("TG_USER_TMLANG")){case "UNV":nvl=scd.getDate()+" "+nvl_m;break;case "ENG":nvl=nvl_m+" "+scd.getDate();break;default:nvl=scd.getDate()+" "+nvl_m;break}else nvl=scd.getDate()+" "+nvl_m+
" "+scd.getYear();var natn=_f_GetNowTimeAtNullUTC();var nuut=natn+tz;$(o).data("tgs-dd-atn",natn);$(o).data("tgs-dd-uut",nuut);nvl=KgbLib_CheckNullity(nvl)?"":nvl;$(o).find(".tgs-frm").addClass("this_hide");$(o).find(".tgs-val").html(nvl);$(o).find(".tgs-val").removeClass("this_hide");$(o).find(".tgs-uni").addClass("this_hide");return true}var rs=this._GetProperLoopTimeRef(dfcd);var dflu=ct-r[1];dflu=r[1]===0?1:dflu;var st=Math.floor(dfcd/rs);var lrp=st*rs;if(dflu>0&&dflu<lrp){var uta=this.GetUnit(rs);
var rr=this.AddUnitSteps(st,uta);var natn=_f_GetNowTimeAtNullUTC();var nuut=natn+tz;this._DisplayTime(o,natn,nuut,rr[0],rr[1])}}}catch(ex){Kxlib_DebugVars([ex,ex.lineNumber],true)}};var _f_SpyGetTm=function(o){if(KgbLib_CheckNullity(o))return;try{var tsp_crd=$(o).data("tgs-crd"),tsp_dd_atn=$(o).data("tgs-dd-atn"),tsp_dd_uut=$(o).data("tgs-dd-uut");var rt=new Array;rt[0]=!KgbLib_CheckNullity(tsp_crd)?tsp_crd:null;rt[1]=!KgbLib_CheckNullity(tsp_dd_atn)?tsp_crd:null;rt[2]=!KgbLib_CheckNullity(tsp_dd_uut)?
tsp_dd_uut:null;if(!tsp_crd)return;return rt}catch(ex){Kxlib_DebugVars([ex,ex.lineNumber],true)}};this._ConvertTsToTime=function(){};this._DisplayTime=function(o,natn,nuut,val,uni){try{$(o).data("tgs-dd-atn",natn);$(o).data("tgs-dd-uut",nuut);var f=Kxlib_getDolphinsValue("TG_FRM");$(o).find(".tgs-frm").html(f);$(o).find(".tgs-uni").html(uni);$(o).find(".tgs-val").html(val);$(o).find(".tgs-uni").removeClass("this_hide");$(o).find(".tgs-val").removeClass("this_hide");$(o).find(".tgs-frm").removeClass("this_hide")}catch(e){Kxlib_DebugVars([e],
true)}};this.DetectTimezConflict=function(){var el=$("<span/>"),th=this;this.GetTmzFromSrv(el);el.on("datasready",function(e,d){var tmz=d[0];var SRV_TMZ=th.ConvertSrvTimezone(tmz);if(KgbLib_CheckNullity(SRV_TMZ))return;var LOC_TMZ=th.GetTimezoneFromLocal();if(KgbLib_CheckNullity(LOC_TMZ))return;if(SRV_TMZ!==LOC_TMZ){var el=th.LocChanged(10),nth=th;if(KgbLib_CheckNullity(el))return;el.on("datasready",function(e,d){var rr=d[0];rr=rr==="1"||rr==="true"?true:false;if(rr){var ws=new WhiteBoardDialog,m=
Kxlib_getDolphinsValue("err_com_tmz_conflict_msg"),t=Kxlib_getDolphinsValue("err_com_tmz_conflict_title");var v={"title":t,"message":m,"fly":"","redir":"reload"};ws.Dialog(v)}else n_frcTmz=SRV_TMZ})}else if(typeof _frcTmz!=="undefined")_frcTmz=null})};this.gltz_uq="GET_LOCAL_TMZ";this.gltz_ajaxr=Kxlib_GetAjaxRules(this.gltz_uq);this.GetTmzFromSrv=function(el){if(KgbLib_CheckNullity(el))return;var th=this;var onsuccess=function(datas){try{if(!KgbLib_CheckNullity(datas))datas=JSON.parse(datas);else{var t=
(new Date).getTime().toString();$(el).trigger("noanswer",[t]);return}if(!KgbLib_CheckNullity(datas.err));if(!KgbLib_CheckNullity(datas.tmz)){var tp=[datas.tmz];$(el).trigger("datasready",[tp]);return}else{$(el).trigger("datasmissing");return}}catch(e){}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b)};var toSend={"urqid":th.gltz_uq,"datas":{}};Kx_XHR_Send(toSend,"post",th.gltz_ajaxr.url,onerror,onsuccess)};this.ConvertSrvTimezone=function(v){if(KgbLib_CheckNullity(v))return;var h=0,mi=0,ml=1;
if(v.toString().indexOf("-")!==-1){ml=-1;v=v.toString().replace("-","")}else if(v.toString().indexOf("+")!==-1)v=v.toString().replace("+","");if(v.toString().indexOf(":")!==-1){var tp=v.toString().split(":");mi=tp[1].length===1?parseInt(tp[1])*10:parseInt(tp[1]);h=parseInt(tp[0]);return(h*36E5+mi*6E4)*ml}else return parseInt(v)*36E5*ml};this.GetTimezoneFromLocal=function(){var atn=_f_GetNowTimeAtNullUTC();var nd=_f_GetNowTimeAtLocalUTC();return nd-atn};this.TmzEqual=function(){};var _f_CalTmzFrmLclSpecs=
function(t1,t2){return typeof t1!=="number"||typeof t2!=="number"?0:t2-t1};this.DecideOnProperTimezone=function(){};var _f_SigErrToSrv=function(a){switch(a){case "compliant":this._SignalNonCompliantComponents();break;case "craked":this._SignalCrackedComponents();break}};this.STUS=function(s){if(KgbLib_CheckNullity(s))return;var lct=(new Date).getTime();var Ajax_STUS=Kxlib_GetAjaxRules("STUS");var onsuccess=function(d){try{if(!KgbLib_CheckNullity(d))d=JSON.parse(d);else return;if(!KgbLib_CheckNullity(d.err)){_xhr_sbms=
null;if(Kxlib_AjaxIsErrVolatile(d.err))switch(d.err){default:Kxlib_AJAX_HandleFailed();return;break}return}else if(!KgbLib_CheckNullity(d.return)){var ltcy=(new Date).getTime()-lct;var tm=d.return-ltcy;rds=[tm];$(s).trigger("datasready",rds)}else return}catch(ex){return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var u=document.URL;var toSend={"urqid":Ajax_STUS.urqid,"datas":{"curl":u}};Kx_XHR_Send(toSend,"post",Ajax_STUS.url,onerror,onsuccess)}}
(function(){var Tg=new TIMEGOD;Tg.UpdSpies();var lt=Tg.GetGenLoopTimeRef();setInterval(function(){Tg.UpdSpies()},lt)})();