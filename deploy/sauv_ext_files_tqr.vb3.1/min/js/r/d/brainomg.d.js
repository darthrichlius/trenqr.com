function BrainHandler(){var gt=this;var _bnM_Sts="";var _bnM_DfMd="m";var _bnM_Md="";var _bnS_DfSts="c";var _bnS_DfThm="sam";var _bnS_thm="";var _f_Gdf=function(){var dt={_bnM_DfSts:"c",_dfltDscId:"brn-mn-dsc-gen",_stgsOpts:["_PFOP_BRAIN_ALWZ_OPN","_PFOP_PSMN_EMLWHN_NW","_PFOP_PSMN_EMLFOR_WKACTY","_PFOP_INFO_EMLWHN_NWTQRVnSECU","_PFOP_INFO_EMLFOR_WKBESTPUB"]};return dt};var _f_ChkNoArt=function(){var east=$("#feeded_e_list_list > .feeded_com_bloc_figs").length;var west=$("#feeded_w_list_list > .feeded_com_bloc_figs").length;
if(!east&&!west&&$("#where_have_you_been")&&$("#brain_maximus").hasClass("this_hide"))$("#where_have_you_been").removeClass("this_hide");else $("#where_have_you_been").addClass("this_hide")};var _f_OnClose=function(){if(!$("#brain_maximus").hasClass("this_hide"))$("#brain_maximus").addClass("this_hide");if(!$("#slave_maximus").hasClass("this_hide"))$("#slave_maximus").addClass("this_hide");$(".jb-brn-opn-tgr").removeClass("this_hide");$("#brain_maximus").data("isopen","0");$("#opref").change();_f_ChkNoArt()};
var _f_OnOpen=function(){if($("#brain_maximus").hasClass("this_hide"))$("#brain_maximus").removeClass("this_hide");if($("#slave_maximus").hasClass("this_hide"))$("#slave_maximus").removeClass("this_hide");$(".jb-brn-opn-tgr").addClass("this_hide");$("#opb_signal_new").addClass("this_hide");$("#brain_maximus").data("isopen","1");_f_ChkNoArt()};var _f_ChkBMSts=function(a){try{var ser=$("#brain_maximus").data("sds");if(!KgbLib_CheckNullity(ser))_bnM_Sts=ser;else _bnM_Sts=KgbLib_CheckNullity(a)?_f_Gdf()._bnM_DfSts:
a;if(_bnM_Sts==="c")_f_OnClose();else if(_bnM_Sts==="o")_f_OnOpen();else;}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_ClzAlFksBnThms=function(){if($(".brain_focus").length>1)return;var _id="#"+$(".brain_focus").attr("id");$(_id).addClass("this_hide");$(_id).removeClass("brain_focus")};var _f_ShwBnThm=function(a){a=Kxlib_ValidIdSel(a);var _id=Kxlib_ValidIdSel($(a).data("slave"));$(_id).addClass("brain_focus");$(_id).removeClass("this_hide")};var _f_TglMstrMd=function(a){try{if(KgbLib_CheckNullity(a))a=
_bnM_DfMd;$(".brain_m_elmnt").addClass("this_hide");switch(a){case "r":break;case "m":$("#brain_listMenu").removeClass("this_hide");break;case "l":$("#brain_ma_modlist").removeClass("this_hide");break}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_PprMstrInLst=function(a){if(KgbLib_CheckNullity(a))return;try{switch(a){case "brain_wrh_trends_choices":case "brain_wrh_notifs_choices":case "brain_wrh_gbk_choices":$("#brain_ma_modlist_l").html("");var _h=$(Kxlib_ValidIdSel(a)).html();
$("#brain_ma_modlist_l").html(_h);$(".brainM_submenu_elmnt").off().click(function(e){Kxlib_PreventDefault(e);gt._f_ClkOnMn(e.target)});if($(Kxlib_ValidIdSel(a)).attr("data-title")){var _ti=$(Kxlib_ValidIdSel(a)).data("title");$("#brain_ma_modlist_title").html(_ti)}break}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_HdlMstrOprs=function(a){if(KgbLib_CheckNullity(a))return;try{switch(a){case "brain_wrh_trends_choices":case "brain_wrh_notifs_choices":case "brain_wrh_gbk_choices":_f_TglMstrMd("l");
_f_PprMstrInLst(a);break}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_OnClickOnBk=function(x){if(KgbLib_CheckNullity(x))return;try{var _m=Kxlib_ValidIdSel($(x).data("mode"));$(".brain_m_elmnt").addClass("this_hide");$(_m).removeClass("this_hide")}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_CnThmChld=function(x){if(KgbLib_CheckNullity(x))return;try{var sl=Kxlib_ValidIdSel(x);var _c=$(sl).children(".in_slave_list").children(".brainS_UnikMdl").length;
$(sl).children(".brain_f_title").children(".jb-counter").html(_c)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_HdlClkOnMn_Ext=function(x,_id){try{if(KgbLib_CheckNullity(x)|KgbLib_CheckNullity(_id))return;if(!KgbLib_CheckNullity($(x).data("lk"))&&$(x).data("lk")===1)return;$(x).data("lk",1);_f_ClzAlFksBnThms();_f_ShwBnThm(_id);var _th=$(x).attr("data-slave");var cb="";switch(_id){case "brain_submenu_mytrch":case "brain_submenu_follgtrch":if(_th==="brain_th-follgtrch"){cb=
"ftrs";$("#brain_list_follgtrs").find(".brainS_UnikMdl").remove();_f_NoOne("FLGTRS",false);_f_HidSpnr("FLGTRS",true);$(".jb-brn-lsts-bmx[data-scp='mytrs']").find(".jb-bn-lsts-nb").text(0);_f_Srv_GMyFTr(_th,cb,x)}else{cb="mtrs";$("#brain_list_mytrs").find(".brainS_UnikMdl").remove();_f_NoOne("MYTRS",false);_f_HidSpnr("MYTRS",true);$(".jb-brn-lsts-bmx[data-scp='flgtrs']").find(".jb-bn-lsts-nb").text(0);_f_Srv_GMyTr(_th,cb,x)}break;case "brain_menu_folls":cb="mflwrs";$("#brain_th-folls").find(".brainS_UnikMdl").remove();
_f_NoOne("RLSFLR",false);_f_HidSpnr("RLSFLR",true);$(".jb-brn-lsts-bmx[data-scp='rlsflr']").find(".jb-bn-lsts-nb").text(0);_f_Srv_GMyFlwL(_th,cb,x);break;case "brain_menu_folgs":cb="mflgs";$("#brain_th-folgs").find(".brainS_UnikMdl").remove();_f_NoOne("RLSFLG",false);_f_HidSpnr("RLSFLG",true);$(".jb-brn-lsts-bmx[data-scp='rlsflg']").find(".jb-bn-lsts-nb").text(0);_f_Srv_GMyFlgL(_th,cb,x);break;default:$(x).data("lk",0);break}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};this._f_ClkOnMn=
function(x){try{if(KgbLib_CheckNullity(x))return;var _id=$(x).attr("id");var _h_m=0;var _h_s=0;_h_m=$(x).attr("data-master")?1:0;_h_s=$(x).attr("data-slave")?1:0;if(_h_m)_f_HdlMstrOprs($(x).data("master"));if($(x).data("or"))_id=$(x).data("or");switch(_id){case "brain_menu_new-ml":_f_HdlClkOnMn_Ext(x,_id);$("#npost_txt").focus();$("#start_npostMl_process").click();break;case "brain_menu_new-sod":_f_HdlClkOnMn_Ext(x,_id);$("#npost_txt").focus();$("#start_npostMl_process").click();break;case "brain_th-npost_tr":var $tr__=
$(".brain_trch_mdl[data-trid='"+$(x).data("trid")+"'");if($tr__.data("noakx")&&parseInt($tr__.data("noakx"))===1&&!($tr__.data("isown")&&parseInt($tr__.data("isown"))===1))return;_f_HdlClkOnMn_Ext(x,"brain_menu_new-ml");$("#npost_txt").focus();$("#start_npostTr_process").data("title",$(x).data("title"));$("#start_npostTr_process").data("trid",$(x).data("trid"));$("#start_npostTr_process").data("isown",$(x).data("isown"));$("#start_npostTr_process").click();break;case "brain_submenu_newtr":_f_HdlClkOnMn_Ext(x,
_id);if(!KgbLib_CheckNullity($(x).data("ext"))){var sl=Kxlib_ValidIdSel($(x).data("ext"));$(sl).change()}break;case "brain_menu_new-ml-tr":if($("#brain_maximus").data("trcct-skip")===0)_f_HdlClkOnMn_Ext(x,_id);else _f_HdlClkOnMn_Ext(x,"brain_submenu_mytrch");break;case "brain_menu_folls":case "brain_menu_folgs":case "brain_submenu_mytrch":case "brain_submenu_follgtrch":case "brain_submenu_notif_all":case "brain_submenu_notif_mtrs":case "brain_submenu_notif_ftrs":case "brain_submenu_notif_reac":case "brain_submenu_gbck_favs":case "brain_menu_sam":case "brain_submenu_gbck_fav":case "brain_submenu_gbck_r":case "brain_submenu_gbck_s":case "brain_menu_trophz":case "brain_menu_stgs":_f_HdlClkOnMn_Ext(x,
_id);break;default:return}if($(x).is(".jb-brain-menu-action")){$(".jb-brain-menu-action.selected").removeClass("selected");$(x).addClass("selected");_f_BrnNwPstBnr(x)}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_BrnNwPstBnr=function(x){try{if(KgbLib_CheckNullity(x))return;var scp=$(x).data("action"),scp;switch(scp){case "add-art-iml":scp="[data-scp='iml']";break;case "add-art-sod":scp="[data-scp='sod']";break;case "add-art-itr":scp="[data-scp='itr']";break;default:return}$(".jb-tqr-brn-npst-bnr").addClass("this_hide");
$(".jb-tqr-brn-npst-bnr").filter(scp).removeClass("this_hide")}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_ShwBnMnDsc=function(x){try{if(KgbLib_CheckNullity(x)|!$(x)|!$(x).length|KgbLib_CheckNullity($(x).data("desc"))){var dcd=_f_Gdf()._dfltDscId;var _m=$(".jb-brn-mn-dsc-txt-wpr").find(".jb-brn-mn-dsc-txt[data-cd='"+dcd+"']").text();$("#brain_snitch").text(_m);return}var dcd=$(x).data("desc");if(!$(".jb-brn-mn-dsc-txt[data-cd='"+dcd+"']").length|KgbLib_CheckNullity($(".jb-brn-mn-dsc-txt[data-cd='"+
dcd+"']").text()))return;$("#brain_snitch").text($(".jb-brn-mn-dsc-txt[data-cd='"+dcd+"']").text())}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_HdlSmplUnikHvrStart=function(x){if(KgbLib_CheckNullity(x))return;var _f=$(x).data("fade");if(KgbLib_CheckNullity(_f))return;var _cl="."+_f;$(x).children(_cl).addClass("this_hide")};var _f_HdlSmplUnikHvrOff=function(x){if(KgbLib_CheckNullity(x))return;var _f=$(x).data("fade");if(KgbLib_CheckNullity(_f))return;var _cl="."+_f;$(x).children(_cl).removeClass("this_hide")};
var _f_BkInBn=function(x){if(KgbLib_CheckNullity(x)){var _m=$(".jb-brn-mn-dsc-txt-wpr").children(Kxlib_ValidIdSel(_f_Gdf()._dfltDscId)).html();$("#brain_snitch").html(_m);return}};var _f_HdlBckInNpost=function(){};var _f_HdlBckInNpost=function(){};var _f_Init=function(a){_f_ChkBMSts(a)};var _f_HdlNewStgs=function(x){try{if(KgbLib_CheckNullity(x))return;if($(x).data("lk")===1)return;$(x).data("lk")===1;var ick=$(x).is(":checked")?"_DEC_ENA":"_DEC_DISA";var sc=$(x).data("wha");if(KgbLib_CheckNullity(sc)||
$.inArray(sc,_f_Gdf()._stgsOpts)===-1){$(x).data("lk",0);return}$(".jb-brn-mn-bdy-wt-pnl-bmx").removeClass("this_hide");var s=$("<span/>");var T=new MNFM;T.SetPrfrcs(sc,ick,s);$(s).on("operended",function(e){$(".jb-brn-mn-bdy-wt-pnl-bmx").addClass("this_hide");$(x).data("lk",0)})}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _Ax_SigDsmaTrCct=Kxlib_GetAjaxRules("TMLNR_SIG_DSMATRCCT",Kxlib_GetCurUserPropIfExist().upsd);var _f_Srv_SigDsmaTrCcpt=function(){var onsuccess=function(datas){try{if(!KgbLib_CheckNullity(datas))datas=
JSON.parse(datas);else return;if(!KgbLib_CheckNullity(datas.err)){if(Kxlib_AjaxIsErrVolatile(datas.err))switch(datas.err){case "__ERR_VOL_SS_MSG":case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_U_GONE":case "__ERR_VOL_U_G":case "__ERR_VOL_USER_GONE":Kxlib_HandleCurrUserGone();break;case "__ERR_VOL_DNY":case "__ERR_VOL_DENY":return;default:return}return}else if(!KgbLib_CheckNullity(datas.return))if((typeof datas.return==="string"||datas.return instanceof String)&&datas.return.toLowerCase()===
"done"){$("#brain_maximus").data("trcct-skip","1");$("#brain_submenu_mytrch").click()}else return;else return}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var toSend={"urqid":_Ax_SigDsmaTrCct.urqid,"datas":{}};Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_SigDsmaTrCct.url,wcrdtl:_Ax_SigDsmaTrCct.wcrdtl})};var _f_SigDsmaTrCcpt=function(){if($("#trcct-bo-skip-dsma-ib").is(":checked"))_f_Srv_SigDsmaTrCcpt();
else{$("#brain_maximus").data("trcct-skip","0");$("#brain_submenu_mytrch").click()}};var _Ax_GMyTr=Kxlib_GetAjaxRules("TMLNR_BRAIN_GETMYTRS",Kxlib_GetCurUserPropIfExist().upsd);var _f_Srv_GMyTr=function(a,cb,x){if(KgbLib_CheckNullity(a)|KgbLib_CheckNullity(cb)|KgbLib_CheckNullity(x))return;var onsuccess=function(datas){try{if(!KgbLib_CheckNullity(datas))datas=JSON.parse(datas);else return;if(!KgbLib_CheckNullity(datas.err)){if(Kxlib_AjaxIsErrVolatile(datas.err))switch(datas.err){case "__ERR_VOL_SS_MSG":case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_U_GONE":case "__ERR_VOL_U_G":case "__ERR_VOL_USER_GONE":Kxlib_HandleCurrUserGone();
break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":case "__ERR_VOL_DNY_AKX":break;default:break}return}else if(!KgbLib_CheckNullity(datas.return)){_f_ShwTrsLst(datas.return,cb,x);_f_CnThmChld(a)}else{_f_ShwTrsLst(null,cb,x);return}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var toSend={"urqid":_Ax_GMyTr.urqid,"datas":{}};Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_GMyTr.url,wcrdtl:_Ax_GMyTr.wcrdtl})};
var _Ax_GMyFTr=Kxlib_GetAjaxRules("TMLNR_BRAIN_GETFOLGTRS",Kxlib_GetCurUserPropIfExist().upsd);var _f_Srv_GMyFTr=function(a,cb,x){if(KgbLib_CheckNullity(a)|KgbLib_CheckNullity(cb)|KgbLib_CheckNullity(x))return;var onsuccess=function(datas){try{if(!KgbLib_CheckNullity(datas))datas=JSON.parse(datas);else return;if(!KgbLib_CheckNullity(datas.err)){if(Kxlib_AjaxIsErrVolatile(datas.err))switch(datas.err){case "__ERR_VOL_SS_MSG":case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_U_GONE":case "__ERR_VOL_U_G":case "__ERR_VOL_USER_GONE":Kxlib_HandleCurrUserGone();
break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":case "__ERR_VOL_DNY_AKX":break;default:break}return}else if(!KgbLib_CheckNullity(datas.return)){_f_ShwTrsLst(datas.return,cb,x);_f_CnThmChld(a)}else{_f_ShwTrsLst(null,cb,x);return}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var toSend={"urqid":_Ax_GMyFTr.urqid,"datas":{}};Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_GMyFTr.url,wcrdtl:_Ax_GMyFTr.wcrdtl})};
var _Ax_GMyFlwL=Kxlib_GetAjaxRules("TMLNR_BRAIN_GETMYFOLW",Kxlib_GetCurUserPropIfExist().upsd);var _f_Srv_GMyFlwL=function(a,cb,x){if(KgbLib_CheckNullity(a)|KgbLib_CheckNullity(cb)|KgbLib_CheckNullity(x))return;var onsuccess=function(datas){try{if(!KgbLib_CheckNullity(datas))datas=JSON.parse(datas);else return;if(!KgbLib_CheckNullity(datas.err)){if(Kxlib_AjaxIsErrVolatile(datas.err))switch(datas.err){case "__ERR_VOL_SS_MSG":case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_U_GONE":case "__ERR_VOL_U_G":case "__ERR_VOL_USER_GONE":Kxlib_HandleCurrUserGone();
break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":case "__ERR_VOL_DNY_AKX":break;default:break}return}else if(!KgbLib_CheckNullity(datas.return)){_f_ShwFlwRelLst(datas.return,cb,x);_f_CnThmChld(a)}else{_f_ShwFlwRelLst(null,cb,x);return}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var toSend={"urqid":_Ax_GMyFlwL.urqid,"datas":{}};Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_GMyFlwL.url,
wcrdtl:_Ax_GMyFlwL.wcrdtl})};var _Ax_GMyFlgL=Kxlib_GetAjaxRules("TMLNR_BRAIN_GETMYFOLG",Kxlib_GetCurUserPropIfExist().upsd);var _f_Srv_GMyFlgL=function(a,cb,x){if(KgbLib_CheckNullity(a)|KgbLib_CheckNullity(cb)|KgbLib_CheckNullity(x)){$(x).data("lk",0);return}var onsuccess=function(datas){try{if(!KgbLib_CheckNullity(datas))datas=JSON.parse(datas);else{$(x).data("lk",0);return}if(!KgbLib_CheckNullity(datas.err)){$(x).data("lk",0);if(Kxlib_AjaxIsErrVolatile(datas.err))switch(datas.err){case "__ERR_VOL_SS_MSG":case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_U_GONE":case "__ERR_VOL_U_G":case "__ERR_VOL_USER_GONE":Kxlib_HandleCurrUserGone();
break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":case "__ERR_VOL_DNY_AKX":break;default:break}return}else if(!KgbLib_CheckNullity(datas.return)){_f_ShwFlwRelLst(datas.return,cb,x);_f_CnThmChld(a)}else{_f_ShwFlwRelLst(null,cb,x);return}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var toSend={"urqid":_Ax_GMyFlgL.urqid,"datas":{}};Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_GMyFlgL.url,
wcrdtl:_Ax_GMyFlgL.wcrdtl})};var _f_HidSpnr=function(scp,shw){try{if(KgbLib_CheckNullity(scp)|typeof scp!=="string")return;switch(scp){case "MYTRS":case "FLGTRS":case "RLSFLR":case "RLSFLG":scp=scp.toLowerCase();$s=$(".jb-brn-lsts-spnr-mx[data-scp='"+scp+"']");if($s&&$s.length&&shw===true){$(".jb-brn-lsts-bmx[data-scp='"+scp+"']").find(".back_to_60s > a").addClass("this_hide");$s.removeClass("this_hide")}else if($s&&$s.length)$s.addClass("this_hide");break;default:break}}catch(ex){Kxlib_DebugVars([ex,
ex.fileName,ex.lineNumber],true)}};var _f_NoOne=function(scp,shw){try{if(KgbLib_CheckNullity(scp)|typeof scp!=="string")return;var $elts;switch(scp){case "MYTRS":$elts=$(".jb-com-bn-trs-elt[data-bind_isown=1]");break;case "FLGTRS":$elts=$(".jb-com-bn-trs-elt[data-isfolw=1]");break;case "RLSFLR":$elts=$(".jb-com-bn-rls-elt[data-etype='flr']");break;case "RLSFLG":$elts=$(".jb-com-bn-rls-elt[data-etype='flg']");break;default:return}scp=scp.toLowerCase();if(!$(".jb-brn-lsts-noone-mx[data-scp='"+scp+"']").length)return;
if(($elts&&$elts.length)|(!KgbLib_CheckNullity(shw)&&shw===false))$(".jb-brn-lsts-noone-mx[data-scp='"+scp+"']").addClass("this_hide");else{$(".jb-brn-lsts-bmx[data-scp='"+scp+"']").find(".back_to_60s > a").addClass("this_hide");$(".jb-brn-lsts-noone-mx[data-scp='"+scp+"']").removeClass("this_hide")}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_ShwTrsLst=function(d,l,x){try{if(KgbLib_CheckNullity(x)|!$(x).length)return;else if(KgbLib_CheckNullity(d)&&!KgbLib_CheckNullity(l)){var b=
l==="mtrs"?"#brain_list_mytrs":"#brain_list_follgtrs";var scp=l==="mtrs"?"MYTRS":"FLGTRS";$(b).find(".brainS_UnikMdl").remove();$(".jb-brn-lsts-bmx[data-scp='"+scp+"']").find(".jb-bn-lsts-nb").text(0);_f_HidSpnr(scp);_f_NoOne(scp,true);$(x).data("lk",0);return}else if(KgbLib_CheckNullity(d)|KgbLib_CheckNullity(l)){$(x).data("lk",0);return}var b=l==="mtrs"?"#brain_list_mytrs":"#brain_list_follgtrs";$(b).find(".brainS_UnikMdl").remove();var scp=l==="mtrs"?"MYTRS":"FLGTRS";_f_HidSpnr(scp);_f_NoOne(scp,
false);var bind_isown,bind_isfolg,item_id;$.each(d,function(x,elt){if(l==="mtrs"){bind_isown=1;bind_isfolg=0;item_id="mytr_model_id"+elt.trd_eid}else{bind_isown=0;bind_isfolg=1;item_id="folwtr_model_id"+elt.trd_eid}var nt=Kxlib_Decode_After_Encode(elt.trd_title);var nd=Kxlib_Decode_After_Encode(elt.trd_desc);var e='<div id="" class="jb-com-bn-trs-elt brain_trch_mdl brainS_UnikMdl" data-trid="'+elt.trd_eid+'" data-isown="'+bind_isown+'" data-isfolw="'+bind_isfolg+'" data-title=""  data-desc="" data-prevw="" data-flwg="" data-postnb="">';
e+='<div class="brain_trch_mdl_conf this_hide">';e+='<div class="btmc_top_max">';e+='<p class="btmc_top_text">';e+="Humh ! Etes vous certain de vouloir supprimer d\u00e9finitivement cette Tendance ?";e+="</p>";e+="</div>";e+='<div class="btmc_bot_max">';e+='<div class="btmc_bot_btn_max">';e+='<a class="btmc_bot_btn" data-ans="0" data-target="" href="javascript:;">Non</a>';e+='<a class="btmc_bot_btn" data-ans="1" data-target="" href="javascript:;">Oui</a>';e+="</div>";e+="</div>";e+="</div>";if(elt.hasOwnProperty("trd_tdl")&&
elt.trd_tdl===true){e+='<div class="trch_btm_delwrng">';e+='<i class="fa fa-exclamation-triangle wrleft"></i><span>En suppression programm\u00e9e</span><i class="fa fa-exclamation-triangle wrright"></i>';e+="</div>"}e+='<div class="brain_trch_hdr">';e+="<a class='brain_trch_title npost_tr_trig' data-trid=\""+elt.trd_eid+'" href=\'javascript:;\' title=""></a>';e+="</div>";e+='<div class="trch_body_top">';e+='<p class="trch_mdl_desc" max="200"></p>';e+='<div class="trch_body_opt">';e+='<span class="trch_bdg_cntd"title="" >';
e+='<img class="trch_bdg_cntd_lg" height="22" width="22" src="'+Kxlib_GetExtFileURL("sys_url_img","r/tr_cntd.png")+'" />';e+='<span class="trch_bdg_cntd_txt">connected</span>';e+="</span>";e+='<div class="action_maximus action_trch">';e+="<a class='action_a cursor-pointer'><span class='brain_sp_k'>A</span><span class='brain_sp_action'>ction<span></a>";e+="<ul class='action_foll_choices this_hide'>";if(l==="mtrs"&&elt.hasOwnProperty("trd_tdl")&&elt.trd_tdl===true)e+='<li><a href="'+elt.trd_href+"\" class='afl_choice bind-goto' alt=''>Aller vers</a>";
else if(l==="mtrs"){e+='<li><a href="'+elt.trd_href+"\" class='afl_choice bind-goto' alt=''>Aller vers</a>";e+='<li><a href="" class="afl_choice kgb_el_can_revs bind-delmytr" data-tarbloc="brain_list_mytrs" data-target="mtr-model-id-'+elt.trd_eid+'" data-action="del_mytr" alt="">Supprimer</a></li>'}else e+='<li><a href="'+elt.trd_href+"\" class='afl_choice bind-goto' alt=''>Aller vers</a>";e+="</ul>";e+="</div>";e+="</div>";e+='<div class="trch_body_down">';e+='<p class="trch_b_d_post"><span class="trch_b_d_nbrB">'+
elt.trd_posts_nb+'</span>&nbsp;<span class="trch_b_d_nbrInd trch_b_d_nbrInd_post">Post</span></p>';e+='<p class="trch_b_d_follg"><span class="trch_b_d_nbrB">'+elt.trd_abos_nb+'</span>&nbsp;<span class="trch_b_d_nbrInd">Followers</span></p>';e+="</div>";e+="</div>";e+="</div>";e=$.parseHTML(e);$(e).find(".brain_trch_title").attr("title",nt);var t__=$("<span/>").addClass("bn-trch-tle-tle").text(nt);$(e).find(".brain_trch_title").html(t__);$(e).find(".trch_mdl_desc").text(nd);if(elt.trd_iprv===true)if(parseInt(bind_isown)!==
1){var $l__=$("<i/>").addClass("fa fa-lock bn-trch-tle-lck");$(e).find(".brain_trch_title").prepend($l__);$(e).data("noakx",1)}if(l==="mtrs"){$(e).attr({"id":"mtr-model-id-"+elt.trd_eid});$(e).find(".btmc_bot_btn").data("target","mtr-model-id-"+elt.trd_eid)}else{$(e).attr({"id":"fltr-model-id-"+elt.trd_eid});$(e).find(".btmc_bot_btn").data("target","fltr-model-id-"+elt.trd_eid)}$(e).find(".btmc_bot_btn").click(function(e){Kxlib_PreventDefault(e);(new Brain_HandleTrend).DelMyTr(this)});$(e).find(".npost_tr_trig, .npost_tr_trig > *").click(function(e){Kxlib_PreventDefault(e);
Kxlib_StopPropagation(e);try{var _this=$(e.target).is(".npost_tr_trig")?this:$(e.target).closest(".npost_tr_trig");var trid=$(_this).data("trid");var isown=$(_this).closest(".jb-com-bn-trs-bc").attr("id");var isfolw=parseInt($(_this).closest(".jb-com-bn-trs-elt").data("isfolw"));if(KgbLib_CheckNullity(trid)||isown!=="brain_list_mytrs"&&!isfolw)return;var $el=$(document.createElement("span"));$el.data("slave","brain_th-new_ml");$el.attr("id","brain_th-npost_tr");$el.data("title",$(_this).attr("title"));
$el.data("trid",trid);$el.data("isown",isown);gt._f_ClkOnMn($el)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}});$(e).find(".action_a").focusout(function(e){if(e.target===this){e.stopPropagation();$(this).parent().children(".action_foll_choices").addClass("this_hide")}});$(e).find(".action_a").click(function(e){Kxlib_PreventDefault(e);$(this).focus();if(!$(this).parent().children(".action_foll_choices").hasClass("this_hide")){$(this).parent().children(".action_foll_choices").addClass("this_hide");
$(this).blur();return}$(".action_foll_choices").not(this).addClass("this_hide");$(this).parent().children(".action_foll_choices").toggleClass("this_hide")});$(".action_a").hover(function(){},function(){});$(b).find(".back_to_60s").before(e)});if($(b).find(".jb-com-bn-trs-elt").length>3){$(b).find(".back_to_60s > a").click(function(e){Kxlib_PreventDefault(e);$("#toptop").animatescroll({element:".in_slave_list",padding:20});$(".in_slave_list").scrollTop(0);$(".in_slave_list").perfectScrollbar();$(".in_slave_list").perfectScrollbar("update")});
$(b).find(".back_to_60s > a").removeClass("this_hide")}$(x).data("lk",0)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_ShwFlwRelLst=function(d,l,x){try{if(KgbLib_CheckNullity(x)|!$(x).length)return;else if(KgbLib_CheckNullity(d)&&!KgbLib_CheckNullity(l)){var b=l==="mflwrs"?"#brain_list_folls":"#brain_list_folgs";var scp=l==="mflwrs"?"RLSFLR":"RLSFLG";$(b).find(".brainS_UnikMdl").remove();$(".jb-brn-lsts-bmx[data-scp='"+scp+"']").find(".jb-bn-lsts-nb").text(0);_f_HidSpnr(scp);
_f_NoOne(scp,true);$(x).data("lk",0);return}else if(KgbLib_CheckNullity(d)|KgbLib_CheckNullity(l)){$(x).data("lk",0);return}var bid="";if(l==="mflwrs"){b="brain_list_folls";bid="#brain_list_folls"}else{b="brain_list_folgs";bid="#brain_list_folgs"}$(bid).find(".brainS_UnikMdl").remove();var scp=l==="mflwrs"?"RLSFLR":"RLSFLG";_f_HidSpnr(scp);_f_NoOne(scp,false);var item_id,url_join_str,dfolw,ifolw,folg,follow,unfollow,etype;$.each(d,function(x,elt){if(l==="mflwrs"){url_join_str="flr";if(typeof elt.urel===
"object"&&Kxlib_ObjectChild_Count(elt.urel)===2){url_join_str+=",flg";folg=Kxlib_getDolphinsValue("following");follow=Kxlib_getDolphinsValue("follow");unfollow=Kxlib_getDolphinsValue("unfollow");dfolw=true}else dfolw=false;item_id="bflruid-"+elt.ueid;etype="flr"}else{ifolw=true;folg=Kxlib_getDolphinsValue("following");follow=Kxlib_getDolphinsValue("follow");unfollow=Kxlib_getDolphinsValue("unfollow");url_join_str="flg";item_id="bflguid-"+elt.ueid;etype="flg"}if($(bid).find(Kxlib_ValidIdSel(item_id)).length)return;
var e='<div id="'+item_id+'" class="jb-com-bn-rls-elt brain_foll_mdl brainS_UnikMdl" data-bfuid="'+elt.ueid+'" data-bfurel="'+url_join_str+'" data-etype="'+etype+'">';e+='<div class="body_top">';e+='<p class="brain_foll_header">';e+='<a class="group_user" href="'+elt.uhref+'">';e+='<img class="br_foll_user_pic" height="45" width="45" src="'+elt.uppic+'" />';e+='<span class="br_foll_user_psd">@'+elt.upsd+"</span>";e+="</a>";e+="</p>";e+='<p class="why_you_hatin">';if(!KgbLib_CheckNullity(elt.upflbio))e+=
"<q>"+Kxlib_Decode_After_Encode(elt.upflbio)+"</q>";e+="</p>";e+="</div>";e+='<div class="body_bot">';if(dfolw===true|ifolw===true){e+='<span class="br_foll_folg" data-fltype="flw">'+folg+"</span>";e+='<div class="action_maximus">';e+="<a class='action_a cursor-pointer'><span class='brain_sp_k'>A</span><span class='brain_sp_action'>ction<span></a>";e+="<ul class='action_foll_choices this_hide'>";e+='<li><a href="#" class=\'afl_choice kgb_el_can_revs bind-fluser bind-fluser-folg\' data-revs ="'+follow+
'" data-tarbloc="'+b+'" data-target="'+item_id+'" data-action="back_flw" alt="">'+unfollow+"</a></li>";e+="</ul>";e+="</div>"}e+="</div>";e+="</div>";e=$.parseHTML(e);$(e).find(".action_a").focusout(function(e){if(e.target===this){e.stopPropagation();$(this).parent().children(".action_foll_choices").addClass("this_hide")}});$(e).find(".action_a").click(function(e){Kxlib_PreventDefault(e);$(this).focus();if(!$(this).parent().children(".action_foll_choices").hasClass("this_hide")){$(this).parent().children(".action_foll_choices").addClass("this_hide");
$(this).blur();return}$(".action_foll_choices").not(this).addClass("this_hide");$(this).parent().children(".action_foll_choices").toggleClass("this_hide")});$(".action_a").hover(function(){},function(){});$(bid).find(".back_to_60s").before(e)});if($(b).find(".jb-com-bn-rls-elt").length>9)$(bid).find(".back_to_60s > a").removeClass("this_hide");$(x).data("lk",0)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};_f_Init("o");$(".jb-brn-clz-tgr").click(function(e){Kxlib_PreventDefault(e);
_f_OnClose()});$(".jb-brn-opn-tgr").click(function(e){Kxlib_PreventDefault(e);_f_OnOpen()});$(".brainM_menu_elmnt").off().click(function(e){Kxlib_PreventDefault(e);gt._f_ClkOnMn(this)});$(".brain_in").off().click(function(e){Kxlib_PreventDefault(e);var $sl=$(this).parent();gt._f_ClkOnMn($sl)});$(".brainM_submenu_elmnt, #brain_submenu_follgtrch, #brain_submenu_mytrch").off().click(function(e){Kxlib_PreventDefault(e);gt._f_ClkOnMn(this)});$(".brainM_menu_elmnt").hover(function(e){_f_ShwBnMnDsc(this)},
function(e){_f_ShwBnMnDsc()});$(".th-sams_samplUnik_a").hover(function(e){_f_HdlSmplUnikHvrStart(this)},function(e){_f_HdlSmplUnikHvrOff(this)});$(".jb-brn-bk").click(function(e){Kxlib_PreventDefault(e);_f_OnClickOnBk(this)});$(".npost_tr_trig, .npost_tr_trig > *").click(function(e){Kxlib_PreventDefault(e);Kxlib_StopPropagation(e);try{var _this=$(e.target).is(".npost_tr_trig")?this:$(e.target).closest(".npost_tr_trig");var trid=$(_this).data("trid");var isown=$(_this).closest(".jb-com-bn-trs-bc").attr("id");
var iflw=parseInt($(_this).closest(".jb-com-bn-trs-elt").data("isfolw"));if(KgbLib_CheckNullity(trid)|(isown!=="brain_list_mytrs"&&!iflw))return;var $el=$(document.createElement("span"));$el.data("slave","brain_th-new_ml");$el.attr("id","brain_th-npost_tr");$el.data("title",$(_this).attr("title"));$el.data("trid",trid);$el.data("isown",isown);$el.data("action","add-art-itr");gt._f_ClkOnMn($el)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}});$(".jb-trcct-bo-skip-trg").click(function(e){Kxlib_PreventDefault(e);
_f_SigDsmaTrCcpt()});$(".jb-brn-mn-bdy-s-p-opt-chbx").change(function(e){Kxlib_PreventDefault(e);_f_HdlNewStgs(this)})}new BrainHandler;