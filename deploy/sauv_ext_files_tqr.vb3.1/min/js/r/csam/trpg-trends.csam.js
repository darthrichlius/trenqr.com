function TrendEditHandle(){var gt=this;var _mxScrn=1072;var _o_trhdr_edit;var _hasConfPart=false;var _f_NwEdit=function(s,v){try{if(KgbLib_CheckNullity(s)|KgbLib_CheckNullity(v))return;if($(".jb-trpg-ed-ver-ovly-mx").data("disabled")===true)return;var tl=$(s).find(".jb-trpg-input-tle").val();tl=$("<div/>").text(tl).text();var ds=$(s).find(".jb-trpg-input-desc").val();ds=$("<div/>").text(ds).text();tl=typeof tl==="undefined"?"":tl;ds=typeof ds==="undefined"?"":ds;var d={"t":tl,"d":ds,"p":[$(s).find(".jb-trpg-input-part").find("option:selected").val(),
$(s).find(".jb-trpg-input-part").find("option:selected").text()]};var r=_f_SecureFields(s,d);if(!r)return;var sd=_o_trhdr_edit.TrSrvSetgs();if(sd.p[0]!==d.p[0]&&!_hasConfPart){var ps=$(s).data("par");var dl=d.p[0].toString().toUpperCase()==="_NTR_PART_PUB"?"trpg_conf_part_pub":"trpg_conf_part_pri";_f_CnfrmPartChc([ps],dl);return}_hasConfPart=false;var pp=Kxlib_GetTrendPropIfExist();if(KgbLib_CheckNullity(pp))return;var i=pp.trid;_f_Srv_SvNwEdit(i,d);switch(v){case "ovly":_f_ClzEditInTrpg_Ovly();break;
default:return}Kxlib_ResetForm(s)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_CnfrmPartChc=function(v,dl){if(!KgbLib_CheckNullity(v)&&v.length===1&&!KgbLib_CheckNullity(dl)){var s=Kxlib_ValidIdSel(v[0]),m=Kxlib_getDolphinsValue(dl);$(s).find(".jb-trpg-c-p-d-m").html(m);$(s).find(".trpg-conf-part-max").removeClass("this_hide")}else{var s=Kxlib_ValidIdSel(v[0]);switch(v[1]){case "c":$(s).find(".trpg-conf-part-max").addClass("this_hide");return false;break;case "s":$(s).find(".trpg-conf-part-max").addClass("this_hide");
_hasConfPart=true;break}}};var _f_NwCrt=function(s,v){var d={"t":$(s).find(".jsbind-trpg_input_title").val(),"d":$(s).find(".jsbind-trpg_input_desc").val(),"c":$(s).find(".jsbind-trpg_input_cat").find("option:selected").val(),"p":[$(s).find(".jsbind-trpg_input_part").find("option:selected").val(),$(s).find(".jsbind-trpg_input_part").find("option:selected").text()],"g":$(s).find(".jsbind-trpg_input_grat").find("option:selected").val()};var r=_f_SecureFields(s,d);if(!r)return;var i=Kxlib_getDolphinsValue("trid");
gt.Srv_SaveNewTrend(r);switch(v){case "aside":_f_ClzCrtInTrpg();break;case "ovly":_f_ClzCrtInTrpg_Ovly();break}Kxlib_ResetForm(s)};var _f_Basics=function(x){$tarsel=$(x);var ua;if(KgbLib_CheckNullity($tarsel.data("action")))return;else ua=$tarsel.data("action");return ua};this.CheckOperation=function(x){try{if(KgbLib_CheckNullity(x))return;var sz=parseInt($("#p-l-c-main").width());var ua=_f_Basics(x);switch(ua.toLowerCase()){case "op_trpg_edit":_f_ShwEditInTrpg_Ovly();break;case "op_trpg_cr":if(sz<
_mxScrn)_f_ShwCrtInTrpg_Ovly();else _f_ShwCrtInTrpg();break;default:break}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_CntdAct=function(x){try{if(KgbLib_CheckNullity(x))return;var a=$(x).data("action");switch(a){case "co":_f_GetCo(x);break;case "disco":_f_Disco(x);break}}catch(ex){return}};var _f_GetCo=function(x){try{if(!$(".jb-tao-sn").length)return;if($(".jb-tao-sn").data("isl")===1)return;var th=this;$(".jb-tao-sn").data("isl",1);var s=$("<span/>"),i=Kxlib_GetCurUserPropIfExist().ueid,
t=Kxlib_GetTrendPropIfExist().trid;_f_Srv_TryAboOper(i,t,"sra",s);$(".jb-trpg_abo_ldg").removeClass("this_hide");$(s).on("operended",function(e,d){$(".jb-trpg_abo_ldg").addClass("this_hide");var Nty=new Notifyzing;Nty.FromUserAction("ua_trpg_abo");if($(".jb-pg-sts").length){$(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Redir")+"...");$(".jb-pg-sts").removeClass("this_hide")}var a=$(x).data("action");_f_SwCntdAct(a);setTimeout(function(){window.location.reload()},2E3)})}catch(ex){return}};
var _f_SwCntdAct=function(c){try{if(!KgbLib_CheckNullity(c)){c=c.toLowerCase();switch(c){case "co":SwiToActCntd();break;case "disco":_f_SwToActDisCo();break}}if($(".jb-trpg_getctd_btn").hasClass("this_hide")){$(".jb-trpg_getctd_btn").removeClass("this_hide");$(".jb-trpg_cntd_bdg").addClass("this_hide")}else{$(".jb-trpg_getctd_btn").addClass("this_hide");$(".jb-trpg_cntd_bdg").removeClass("this_hide")}}catch(ex){return}};var _f_Disco=function(x){try{if(!$(".jb-tao-sn").length)return;if($(".jb-tao-sn").data("isl")===
1)return;$(".jb-tao-sn").data("isl",1);var s=$("<span/>"),i=Kxlib_GetCurUserPropIfExist().ueid,t=Kxlib_GetTrendPropIfExist().trid;_f_Srv_TryAboOper(i,t,"sta",s);$(".jb-trpg_abo_ldg").removeClass("this_hide");$(s).on("operended",function(e,d){$(".jb-trpg_abo_ldg").addClass("this_hide");var Nty=new Notifyzing;Nty.FromUserAction("ua_trpg_disabo");if($(".jb-pg-sts").length){$(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Redir")+"...");$(".jb-pg-sts").removeClass("this_hide")}var a=$(x).data("action");
_f_SwCntdAct(a);setTimeout(function(){window.location.reload()},2E3)})}catch(ex){return}};var _f_Gdf=function(){var df={"rgx_tle":/(?:(?=.*[a-z\u00c0\u00c1\u00c2\u00c3\u00c4\u00c5\u00c6\u00e0\u00e1\u00e2\u00e3\u00e4\u00e5\u00e6\u1eaf\u1ea1\u1eb6\u1eb7\u1eb0\u1eb1\u1ea2\u1ea3\u00de\u00df\u00fe\u00c7\u00e7\u0110\u0111\u0189\u0256\u00d0\u00f0\u00c8\u00c9\u00ca\u00cb\u00e8\u00e9\u00ea\u00eb\u1ec1\u1ec7\u0118\u0119\u00cc\u00cd\u00ce\u00cf\u00ec\u00ed\u00ee\u00ef\u0142\u00d2\u00d3\u00d4\u00d5\u00d6\u00d8\u0398\u00f2\u00f3\u00f4\u00f5\u00f6\u01a1\u1edf\u00f8\u00d1\u00f1\u2c63\u1d7d\u00d9\u00da\u00db\u00dc\u00f9\u00fa\u00fb\u00fc\u1ef1\u00ff\u00dd\u00fd\u0160\u017d\u017e\u017c]*).(?![\s]{5,})){20,}/i,
"tle_min":20,"tle_max":100,"rgx_desc":/(?:(?=.*[a-z\u00c0\u00c1\u00c2\u00c3\u00c4\u00c5\u00c6\u00e0\u00e1\u00e2\u00e3\u00e4\u00e5\u00e6\u1eaf\u1ea1\u1eb6\u1eb7\u1eb0\u1eb1\u1ea2\u1ea3\u00de\u00df\u00fe\u00c7\u00e7\u0110\u0111\u0189\u0256\u00d0\u00f0\u00c8\u00c9\u00ca\u00cb\u00e8\u00e9\u00ea\u00eb\u1ec1\u1ec7\u0118\u0119\u00cc\u00cd\u00ce\u00cf\u00ec\u00ed\u00ee\u00ef\u0142\u00d2\u00d3\u00d4\u00d5\u00d6\u00d8\u0398\u00f2\u00f3\u00f4\u00f5\u00f6\u01a1\u1edf\u00f8\u00d1\u00f1\u2c63\u1d7d\u00d9\u00da\u00db\u00dc\u00f9\u00fa\u00fb\u00fc\u1ef1\u00ff\u00dd\u00fd\u0160\u017d\u017e\u017c]*).(?![\s]{5,})){20,}/i,
"desc_min":20,"desc_max":200,"part":["_NTR_PART_PUB","_NTR_PART_PRI"]};return df};var _f_Init=function(){try{$(".jb-trpg-ed-ver-ovly-mx").data("disabled",true)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_SecureFields=function(s,d){try{if(KgbLib_CheckNullity(s)|KgbLib_CheckNullity(d))return;var fds=$(".jb-trpg-ovly-ipt"),ecn=0;$.each(fds,function(x,el){if(!$(el).data("ft"))return false;if(!_f_ChecKField(s,el))++ecn});return ecn?false:true}catch(ex){Kxlib_DebugVars([ex,ex.fileName,
ex.lineNumber],true)}};var _f_ChecKField=function(s,x){try{if(KgbLib_CheckNullity(s)|KgbLib_CheckNullity(x)|!$(x).data("ft"))return;var fd=$(x).data("ft"),v,ie=false,$eb,em;switch(fd.toLowerCase()){case "title":v=$(x).val();if(KgbLib_CheckNullity(v)){ie=true;$(x).addClass("error_field")}else if(v.length<_f_Gdf().tle_min){ie=true;em=Kxlib_getDolphinsValue("err_trpg_title_eln").replace("%min%",_f_Gdf().tle_min).replace("%max%",_f_Gdf().tle_max);$(x).addClass("error_field")}else if(v.length>_f_Gdf().tle_max){ie=
true;em=Kxlib_getDolphinsValue("err_trpg_title_eln").replace("%min%",_f_Gdf().tle_min).replace("%max%",_f_Gdf().tle_max);$(x).addClass("error_field")}else if(!_f_Gdf().rgx_tle.test(v)){ie=true;$(x).addClass("error_field")}else $(x).removeClass("error_field");$eb=$(s).find(".erb-trpg-input-tle");if(ie&&em&&$eb.length)$eb.text(em).removeClass("this_hide");else $eb.text("").addClass("this_hide");break;case "description":v=$(x).val();if(KgbLib_CheckNullity(v)){ie=true;$(x).addClass("error_field")}else if(v.length<
_f_Gdf().desc_min){ie=true;em=Kxlib_getDolphinsValue("err_trpg_desc_eln").replace("%min%",_f_Gdf().desc_min).replace("%max%",_f_Gdf().desc_max);$(x).addClass("error_field")}else if(v.length>_f_Gdf().desc_max){ie=true;em=Kxlib_getDolphinsValue("err_trpg_desc_eln").replace("%min%",_f_Gdf().desc_min).replace("%max%",_f_Gdf().desc_max);$(x).addClass("error_field")}else if(!_f_Gdf().rgx_desc.test(v)){ie=true;$(x).addClass("error_field")}else $(x).removeClass("error_field");$eb=$(s).find(".erb-trpg-input-desc");
if(ie&&em&&$eb.length)$eb.text(em).removeClass("this_hide");else $eb.text("").addClass("this_hide");break;case "category":break;case "participation":v=$(".jb-trpg-input-part option:selected").val();if(KgbLib_CheckNullity(v)){ie=true;$(x).addClass("error_field")}else if($.inArray(v,_f_Gdf().part)===-1){ie=true;$(x).addClass("error_field")}else $(x).removeClass("error_field");break;default:return}return ie?false:true}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_RstEdit=function(x){try{if(KgbLib_CheckNullity(x))return;
if($(".jb-trpg-ed-ver-ovly-mx").data("disabled")===true)return;var fs=Kxlib_ValidIdSel("tr-e-v-ovly-form");_f_RmvErrs(fs);Kxlib_ResetForm(fs);$(".jb-trpg-input-desc").val("");$(".jb-trpg-ed-ver-ovly-mx").find(".jb-trpg-input-tle").triggerHandler("blur");$(".jb-trpg-ed-ver-ovly-mx").find(".jb-trpg-input-desc").triggerHandler("blur")}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _Ax_SvNwEdit=Kxlib_GetAjaxRules("TRPG_SV_NW_STS");var _f_Srv_SvNwEdit=function(ti,nd){if(KgbLib_CheckNullity(ti)|
KgbLib_CheckNullity(nd))return;var onsuccess=function(d){try{if(!KgbLib_CheckNullity(d))d=JSON.parse(d);else return;if(!KgbLib_CheckNullity(d.err)){if(Kxlib_AjaxIsErrVolatile(d.err))switch(d.err){case "__ERR_VOL_U_G":case "__ERR_VOL_U_GONE":case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_USER_GONE":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_OWNER_GONE":Kxlib_HandleCurrUserGone();break;case "__ERR_VOL_TRD_GONE":break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":Kxlib_AJAX_HandleDeny();break;case "__ERR_VOL_FAILED":Kxlib_AJAX_HandleFailed();
break;case "__ERR_VOL_TRTITLE_NOT_COMPLY":case "__ERR_VOL_TRDESC_NOT_COMPLY":case "__ERR_VOL_TRCATG_NOT_COMPLY":var m=Kxlib_getDolphinsValue("ERR_TPRG_ST_DT_NCOMPL");Kxlib_AJAX_HandleFailed(m);break;case "__ERR_VOL_TRD_CATGLOCK":var m=Kxlib_getDolphinsValue("ERR_TPRG_LOCK_ON_CATG");Kxlib_AJAX_HandleFailed(m);break;default:Kxlib_AJAX_HandleFailed();break}return}else if(!KgbLib_CheckNullity(d.return)){var o=new TrendHeader;o.UpdHdrWNwStgs(d.return);var Nty=new Notifyzing;Nty.FromUserAction("ua_trpg_new_setgs");
if($(".jb-pg-sts").length){$(".jb-pg-sts-txt").text(Kxlib_getDolphinsValue("COMLG_Redir")+"...");$(".jb-pg-sts").removeClass("this_hide")}setTimeout(function(){window.location.replace(d.return.thrf)},2E3)}else return}catch(ex){Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var toSend={"urqid":_Ax_SvNwEdit.urqid,"datas":{"ti":ti,"t":nd.t,"d":nd.d,"p":nd.p[0]}};Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_SvNwEdit.url,
wcrdtl:_Ax_SvNwEdit.wcrdtl})};var _Ax_TryDisco=Kxlib_GetAjaxRules("TRPG_TRY_ABO_OPER");var _f_Srv_TryAboOper=function(i,t,w,s){if(KgbLib_CheckNullity(i)|KgbLib_CheckNullity(t)|KgbLib_CheckNullity(w)|KgbLib_CheckNullity(s))return;var onsuccess=function(d){try{Kxlib_DebugVars([typeof d,d],true);if(!KgbLib_CheckNullity(d))d=JSON.parse(d);else return;if(!KgbLib_CheckNullity(d.err)){if(Kxlib_AjaxIsErrVolatile(d.err)){$(".jb-trpg_abo_ldg").addClass("this_hide");$(".jb-tao-sn").data("isl",0);switch(d.err){case "__ERR_VOL_U_G":case "__ERR_VOL_U_GONE":case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_USER_GONE":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_OWNER_GONE":Kxlib_HandleCurrUserGone();
break;case "__ERR_VOL_TRD_GONE":break;break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":Kxlib_AJAX_HandleDeny();break;case "__ERR_VOL_IS_OWNER":var m=Kxlib_getDolphinsValue("UA_COM_DPERR_MUST_RLD");Kxlib_HandleTrdMustReload(null,m,"uhome","reload",true);break;case "__ERR_VOL_ABO_EXISTS":case "__ERR_VOL_NO_TRABO":var m=Kxlib_getDolphinsValue("UA_COM_MUST_RLD");Kxlib_HandleTrdMustReload(null,m,"uhome","reload",true);break;case "__ERR_VOL_FAILED":break;default:Kxlib_AJAX_HandleFailed();break}}return}else if(!KgbLib_CheckNullity(d.return)){var rds=
d.return;$(s).trigger("operended")}else return}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var cul=document.URL;var toSend={"urqid":_Ax_TryDisco.urqid,"datas":{"ui":i,"ti":t,"cl":cul,"pl":"TRPG","w":w}};Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_TryDisco.url,wcrdtl:_Ax_TryDisco.wcrdtl})};var _f_FillEditFormRows=function(s,d,isd){try{if(KgbLib_CheckNullity(s)|KgbLib_CheckNullity(d))return;
var t=Kxlib_Decode_After_Encode(d.t);t=$("<div/>").text(t).text();$(s).find(".jb-trpg-input-tle").val(t);$(s).find(".jb-trpg-input-tle").text(t).triggerHandler("blur");var ds=$("<div/>").text(d.d).text();if(d.hasOwnProperty("trcov"))ds=Kxlib_Decode_After_Encode(ds);$(s).find(".jb-trpg-input-desc").val(ds).triggerHandler("blur");var prt=d.p[0]==="pri"||d.p[0]==="_NTR_PART_PRI"?"_NTR_PART_PRI":"_NTR_PART_PUB";$(s).find(".jb-trpg-input-part").find("option:selected").removeAttr("selected");$(s).find(".jb-trpg-input-part").find("option[value="+
prt+"]").prop("selected",true);if(isd===true){$(".jb-trpg-input-tle").attr("disabled",false);$(".jb-trpg-input-desc").attr("disabled",false);$(".jb-trpg-ovly-ipt[data-ft='participation'").attr("disabled",false)}if(isd===true)$(".jb-trpg-ed-ver-ovly-mx").data("disabled",false)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_FillForm=function(s,a){try{var o=new TrendHeader;_o_trhdr_edit=o;o.GetAndDisplaySettings(_f_FillEditFormRows,s,a)}catch(ex){Kxlib_DebugVars([ex,ex.lineNumber],
true)}};var _f_ClzEditInTrpg=function(){$("#tr-edit-ver-aside").addClass("this_hide")};var _f_ShwEditInTrpg=function(){var s="#tr-edit-ver-aside",sf="#tr-e-v-a-form";_f_ClzCrtInTrpg();$(s).removeClass("this_hide");var pp=Kxlib_GetTrendPropIfExist();if(KgbLib_CheckNullity(pp))return;var i=pp.trid;_f_FillForm(sf,i)};var _f_ClzCrtInTrpg=function(){$("#tr-cr-ver-aside").addClass("this_hide")};var _f_ShwCrtInTrpg=function(){_f_ClzEditInTrpg();$("#tr-cr-ver-aside").removeClass("this_hide")};var _f_ClzEditInTrpg_Ovly=
function(){try{$(".jb-trpg-ovly-mx").addClass("this_hide");$("#trpg-ed-ver-ovly-max").addClass("this_hide");$(".jb-trpg-input-tle").attr("disabled",true);$(".jb-trpg-input-desc").attr("disabled",true);$(".jb-trpg-ovly-ipt[data-ft='participation'").attr("disabled",true);$(".jb-trpg-ed-ver-ovly-mx").data("disabled",true)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_ShwEditInTrpg_Ovly=function(){try{var s="#trpg-ed-ver-ovly-max",sf="#tr-e-v-ovly-form";$("#trpg-cr-ver-ovly-max").addClass("this_hide");
_f_ClzEditInTrpg();_f_ClzCrtInTrpg();$(".jb-trpg-ovly-mx").removeClass("this_hide");$(s).removeClass("this_hide");var od={"t":$(".jb-a-h-t-top-tr-tle").text(),"d":$(".jb-a-h-t-top-tr-desc").text(),"c":$(".jb-ttr-cache").data("c"),"p":$(".jb-ttr-cache").data("p").split(",")};_f_FillEditFormRows(sf,od);var pp=Kxlib_GetTrendPropIfExist();if(KgbLib_CheckNullity(pp))return;var i=pp.trid;_f_FillForm(sf,i)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_ClzCrtInTrpg_Ovly=function(){try{$(".jb-trpg-ovly-mx").addClass("this_hide");
$("#trpg-cr-ver-ovly-max").addClass("this_hide")}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_ShwCrtInTrpg_Ovly=function(){$("#trpg-ed-ver-ovly-max").addClass("this_hide");_f_ClzEditInTrpg();_f_ClzCrtInTrpg();$(".jb-trpg-ovly-mx").removeClass("this_hide");$("#trpg-cr-ver-ovly-max").removeClass("this_hide")};var _f_RmvErrs=function(s){try{if(KgbLib_CheckNullity(s))return;s=Kxlib_ValidIdSel(s);$(s).find("input, textarea, select").removeClass("error_field");$(s).find(".tr-v-a-hder-err, .tr-v-ovly-hder-err").addClass("this_hide");
$(s).find(".tr-v-a-hder-err, .tr-v-ovly-hder-err").html()}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var SwiToActCntd=function(){$(".jb-trpg_getctd_btn").removeClass("this_hide");$(".jb-trpg_cntd_bdg").addClass("this_hide")};var _f_SwToActDisCo=function(){$(".jb-trpg_getctd_btn").addClass("this_hide");$(".jb-trpg_cntd_bdg").removeClass("this_hide")};$("#tr-cr-v-a-cancel").click(function(e){Kxlib_PreventDefault(e);var s=Kxlib_ValidIdSel($(this).data("f"));_f_ClzCrtInTrpg();_f_RmvErrs(s)});
$(".jb-tr-v-ovly-ccl").click(function(e){Kxlib_PreventDefault(e);var s=Kxlib_ValidIdSel($(this).data("f"));_f_ClzEditInTrpg_Ovly();_f_RmvErrs(s)});$("#tr-ed-v-ovly-cancel").click(function(e){Kxlib_PreventDefault(e);var s=Kxlib_ValidIdSel($(this).data("f"));_f_ClzEditInTrpg_Ovly();_f_RmvErrs(s)});$("#tr-e-v-a-cancel").click(function(e){Kxlib_PreventDefault(e);var s=Kxlib_ValidIdSel($(this).data("f"));_f_ClzEditInTrpg();_f_RmvErrs(s)});$("#tr-e-v-a-save").click(function(e){Kxlib_PreventDefault(e);var s=
Kxlib_ValidIdSel($(this).data("f"));_f_NwEdit(s,"aside")});$(".jb-tr-e-v-ovly-save").click(function(e){Kxlib_PreventDefault(e);var s=Kxlib_ValidIdSel($(this).data("f"));_f_NwEdit(s,"ovly")});$(".jb-tr-e-v-ovly-rst").click(function(e){Kxlib_PreventDefault(e);_f_RstEdit(this)});$("#tr-cr-v-a-save").click(function(e){Kxlib_PreventDefault(e);var s=Kxlib_ValidIdSel($(this).data("f"));_f_NwCrt(s,"ovly")});$("#tr-cr-v-ovly-save").click(function(e){Kxlib_PreventDefault(e);var s=Kxlib_ValidIdSel($(this).data("f"));
_f_NwCrt(s,"ovly")});$(".trpg-c-p-d-ch-each").click(function(e){Kxlib_PreventDefault(e);var s=Kxlib_ValidIdSel($(this).data("par")),a=$(this).data("action");_f_CnfrmPartChc([s,a])});$(".jb-trpg-action, .jb-trpg-action > *").click(function(e){Kxlib_PreventDefault(e);Kxlib_StopPropagation(e);if($(this).is(".jb-trpg-action"))_f_CntdAct(this);else{var tr=$(this).closest(".jb-trpg-action");_f_CntdAct(tr)}});_f_Init()}var obj=new TrendEditHandle;
function TrendEdit_Receiver(){this.Routeur=function(th){if(KgbLib_CheckNullity(th))return;obj.CheckOperation(th)}};
