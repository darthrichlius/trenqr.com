define("sapp/sapp-favlinks",function(){return function FAVLINKS(){var _xhr_fvlk_delfv;var _xhr_fvlk_addfv;var _xhr_fvlk_gtfst_fv;var _xhr_fvlk_gtfrm_fv;this.Start=function(){try{_f_Section("_SEC_XTRBAR_MX",false);_f_Section("_SEC_FIL_MX",false);_f_Section("_SEC_ADFRM_MX",false);_f_Section("_SEC_LST_MX",false);_f_Section("_SEC_LDMR_MX",false);_f_RstLdr();_f_Spnr("_FAV_CTR",true);_f_GetFirst()}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};this.End=function(){try{_f_Close();_f_SwWaitPnl(true)}catch(ex){Kxlib_DebugVars([ex,
ex.fileName,ex.lineNumber],true)}};var _f_Gdf=function(){var dt={"minlks":8,"rgx_favtle":/^(?=.*[a-z])[\s\S\u00c0\u00c1\u00c2\u00c3\u00c4\u00c5\u00c6\u00e0\u00e1\u00e2\u00e3\u00e4\u00e5\u00e6\u1eaf\u1ea1\u1eb6\u1eb7\u1eb0\u1eb1\u1ea2\u1ea3\u00de\u00df\u00fe\u00c7\u00e7\u0110\u0111\u0189\u0256\u00d0\u00f0\u00c8\u00c9\u00ca\u00cb\u00e8\u00e9\u00ea\u00eb\u1ec1\u1ec7\u0118\u0119\u00cc\u00cd\u00ce\u00cf\u00ec\u00ed\u00ee\u00ef\u0142\u00d2\u00d3\u00d4\u00d5\u00d6\u00d8\u0398\u00f2\u00f3\u00f4\u00f5\u00f6\u01a1\u1edf\u00f8\u00d1\u00f1\u2c63\u1d7d\u00d9\u00da\u00db\u00dc\u00f9\u00fa\u00fb\u00fc\u1ef1\u00ff\u00dd\u00fd\u0160\u017d\u017e\u017c]{10,100}$/i,
"rgx_favurl":/^.{5,255}$/i,"rgx_favdsc":/^(?=.*[a-z])[\s\S\u00c0\u00c1\u00c2\u00c3\u00c4\u00c5\u00c6\u00e0\u00e1\u00e2\u00e3\u00e4\u00e5\u00e6\u1eaf\u1ea1\u1eb6\u1eb7\u1eb0\u1eb1\u1ea2\u1ea3\u00de\u00df\u00fe\u00c7\u00e7\u0110\u0111\u0189\u0256\u00d0\u00f0\u00c8\u00c9\u00ca\u00cb\u00e8\u00e9\u00ea\u00eb\u1ec1\u1ec7\u0118\u0119\u00cc\u00cd\u00ce\u00cf\u00ec\u00ed\u00ee\u00ef\u0142\u00d2\u00d3\u00d4\u00d5\u00d6\u00d8\u0398\u00f2\u00f3\u00f4\u00f5\u00f6\u01a1\u1edf\u00f8\u00d1\u00f1\u2c63\u1d7d\u00d9\u00da\u00db\u00dc\u00f9\u00fa\u00fb\u00fc\u1ef1\u00ff\u00dd\u00fd\u0160\u017d\u017e\u017c]{1,200}$/i,
"favcatg_opt":["catg_news","catg_mmedia","catg_pro","catg_games","catg_shop","catg_socnet","catg_others"]};return dt};var _f_init=function(op){try{}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_Action=function(x,a){try{if(KgbLib_CheckNullity(x)|(KgbLib_CheckNullity($(x).data("action"))&&KgbLib_CheckNullity(a)))return;var ac=a?a:$(x).data("action");switch(ac){case "delconf_start":case "delconf_abort":case "delconf_del":_f_DelArt(x);break;case "add_fav":_f_AddFav(x);break;
case "load_more":_f_LoadMr(x);break;case "chng_fil":_f_ChgFil(x);break;case "visit":_f_Visit(x);break;default:return}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_Open=function(x){try{_f_SwWaitPnl(false);$(".jb-tiap-scrn-b-bdy").removeClass("this_hide");$(".jb-tiap-app-sctn-hdr[data-appname='favlink']").removeClass("this_hide");$(".jb-tiap-app-sctn[data-appname='favlink']").removeClass("this_hide")}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_Close=
function(x){try{if(KgbLib_CheckNullity(x))return}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_AppDom=function(){try{var a_={appname:"favlink",access:1,options:{}};return a_}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_AddFav=function(x){try{if(KgbLib_CheckNullity(x))return;if($(x).data("lk")===1)return;$(x).data("lk",1);_f_Spnr("_ADD_FAV",true);$(".jb-tqr-d-lifav-af-fld").prop("disabled",true);var errs=_f_AdFv_ChkFld();if(errs){$(".jb-tqr-d-lifav-af-fld").prop("disabled",
false);_f_Spnr("_ADD_FAV",false);$(x).data("lk",0);return}var ds={"title":$(".jb-tqr-d-lifav-af-fld[data-field='title']").val(),"url":$(".jb-tqr-d-lifav-af-fld[data-field='url']").val(),"descp":$(".jb-tqr-d-lifav-af-fld[data-field='description']").val(),"catg":$(".jb-tqr-d-lifav-af-fld[data-field='category'] option:selected").val()};var s=$("<span/>");_f_Srv_AddFav(ds.title,ds.url,ds.descp,ds.catg,s);$(s).on("datasready",function(e,d){if(KgbLib_CheckNullity(d))return;_f_None(false);_f_Section("_SEC_LST_MX",
true);_f_AddFavMl(d.fvdm);_f_AdFv_RstFrm(true);_f_UpdTotLksNb(d.lksnb);_xhr_fvlk_addfv=null})}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_AdFv_Nw=function(){try{return false}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_AdFv_RstFrm=function(w_ds){try{$(".jb-tqr-d-lifav-af-fld").prop("disabled",false);_f_Spnr("_ADD_FAV",false);$(".jb-tqr-d-lifav-af-sbmt").data("lk",0);if(w_ds)Kxlib_ResetForm("tqr-d-lifav-adfrm")}catch(ex){Kxlib_DebugVars([ex,ex.fileName,
ex.lineNumber],true)}};var _f_AdFv_ChkFld=function(){try{var flds=$(".jb-tqr-d-lifav-af-fld"),err=0,rgx;$.each(flds,function(x,e){var fnm=$(e).data("field");if(!fnm)return false;switch(fnm){case "title":v=$(e).val();var erp=$(e).parent().find(".jb-tqr-d-lifav-af-err");rgx=_f_Gdf().rgx_favtle;if(!_f_Gdf().rgx_favtle.test(v)){var erm="Erreur";$(erp).text(erm);$(erp).removeClass("this_hide");err++}else $(erp).addClass("this_hide");break;case "url":v=$(e).val();var erp=$(e).parent().find(".jb-tqr-d-lifav-af-err");
rgx=_f_Gdf().rgx_favurl;if(!rgx.test(v)){var erm="Erreur";$(erp).text(erm);$(erp).removeClass("this_hide");err++}else $(erp).addClass("this_hide");break;case "description":v=$(e).val();var erp=$(e).parent().find(".jb-tqr-d-lifav-af-err");rgx=_f_Gdf().rgx_favdsc;Kxlib_DebugVars([v.length,!rgx.test(v)]);if(v.length&&!rgx.test(v)){var erm="Erreur";$(erp).text(erm);$(erp).removeClass("this_hide");err++}else $(erp).addClass("this_hide");break;case "category":v=$(e).val();if($.inArray(v,_f_Gdf().favcatg_opt)===
-1)err++;break;default:return false}});return err}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_DelArt=function(x){try{if(KgbLib_CheckNullity(x))return;var a=$(x).data("action"),amx;switch(a){case "delconf_start":amx=$(x).closest(".jb-tqr-d-lifav-lk-mx");$(amx).find(".jb-tqr-d-lifav-lk-cntt-mx").addClass("this_hide");$(amx).find(".jb-tqr-d-lifav-lk-cnfdel-mx").removeClass("this_hide");return;case "delconf_abort":amx=$(x).closest(".jb-tqr-d-lifav-lk-mx");$(amx).find(".jb-tqr-d-lifav-lk-cnfdel-mx").addClass("this_hide");
$(amx).find(".jb-tqr-d-lifav-lk-cntt-mx").removeClass("this_hide");return;case "delconf_del":amx=$(x).closest(".jb-tqr-d-lifav-lk-mx");break;default:return}var i=$(amx).data("item");var s=$("<span/>");_f_Srv_Delfav(i,s);$(amx).addClass("this_hide");$(s).on("datasready",function(e,d){if(KgbLib_CheckNullity(d))return;$(amx).remove();_f_None();_f_UpdTotLksNb(d.lksnb);_xhr_fvlk_delfv=null})}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_GetFirst=function(){try{var fl=$(".jb-tqr-d-lf-flt.active").data("fil");
var s=$("<span/>");_f_Srv_GetFst_Fav(fl,s);$(s).on("datasready",function(e,d){if(!(!KgbLib_CheckNullity(d)&&d.hasOwnProperty("lksdom")&&!KgbLib_CheckNullity(d.lksdom)))return;_f_Spnr("_FAV_CTR",false);_f_None(false);$(".jb-tqr-d-lifav-lk-mx").remove();var x__=_f_DsplyDs(d.lksdom,"fst");_f_Section("_SEC_XTRBAR_MX",true);_f_Section("_SEC_ADFRM_MX",true);if(!x__)_f_None(true);else{_f_Section("_SEC_FIL_MX",true);_f_Section("_SEC_LST_MX",true);if(parseInt(d.lksnb)>_f_Gdf().minlks&&$(".jb-tqr-d-lifav-lk-mx").length===
_f_Gdf().minlks)_f_Section("_SEC_LDMR_MX",true)}_f_UpdTotLksNb(d.lksnb);_xhr_fvlk_gtfst_fv=null});$(s).on("operended",function(e,d){_f_Spnr("_FAV_CTR",false);_f_None(true);_f_Section("_SEC_FIL_MX",true);_f_Section("_SEC_XTRBAR_MX",true);_f_Section("_SEC_ADFRM_MX",true);_xhr_fvlk_gtfst_fv=null})}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_UpdFavMlDs=function(mb,ds){try{if(KgbLib_CheckNullity(mb)|KgbLib_CheckNullity(ds)|!ds.hasOwnProperty("item")|KgbLib_CheckNullity(ds.item))return;
if(!($(mb).length&&$(mb).is(".jb-tqr-d-lifav-lk-mx")&&!KgbLib_CheckNullity($(mb).data("item"))&&$(mb).data("item")===ds.item))return-1;if(KgbLib_CheckNullity(ds.last)){var m_="Jamais";$(mb).find(".jb-tqr-d-lifav-lk-last .main").text(m_)}else{$(mb).find(".kxlib_tgspy").data("tgs-crd",ds.last);$(mb).find(".kxlib_tgspy").data("tgs-dd-atn",ds.last);$(mb).find(".kxlib_tgspy").data("tgs-dd-uut",ds.last)}var visits=KgbLib_CheckNullity(ds.visits)?0:ds.visits;$(mb).find(".jb-tqr-d-lifav-lk-cn .main").text(visits);
var TG=new TIMEGOD;TG.UpdSpies()}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_UpdTotLksNb=function(n){try{if(KgbLib_CheckNullity(n))return;$(".jb-tqr-d-lifav-cn").text(n)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_LoadMr=function(x){try{if(KgbLib_CheckNullity(x))return;if($(x).data("lk")===1)return;$(x).data("lk",1);_f_Spnr("_LD_MR",true);var tos={"fil":$(".jb-tqr-d-lf-flt.active").data("fil"),"lfi":$(".jb-tqr-d-lifav-lk-mx:last").data("item"),
"lft":$(".jb-tqr-d-lifav-lk-mx:last").data("time")};var s=$("<span/>");_f_Srv_GetFrm_Fav(tos.fil,tos.lfi,tos.lft,x,s);$(s).on("datasready",function(e,d){if(!(!KgbLib_CheckNullity(d)&&d.hasOwnProperty("lksdom")&&!KgbLib_CheckNullity(d.lksdom)))return;var x__=_f_DsplyDs(d.lksdom,"btm");_f_Section("_SEC_XTRBAR_MX",true);_f_Section("_SEC_ADFRM_MX",true);_f_Section("_SEC_FIL_MX",true);_f_Section("_SEC_LST_MX",true);_f_Spnr("_LD_MR",false);_f_UpdTotLksNb(d.lksnb);$(x).data("lk",0);_xhr_fvlk_gtfrm_fv=null});
$(s).on("operended",function(e,d){_f_Spnr("_LD_MR",false);_f_NoMr(true);$(x).data("lk",0);_xhr_fvlk_gtfrm_fv=null})}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_ChgFil=function(x){try{if(KgbLib_CheckNullity(x))return;if(_f_AdFv_Nw())alert("Demander une confirmation");var fil=$(x).data("fil").toString().toLowerCase();if(fil!=="catg_all"&&$.inArray(fil,_f_Gdf().favcatg_opt)===-1)return;$(".jb-tqr-d-lf-flt.active").removeClass("active");$(x).addClass("active");_f_Section("_SEC_XTRBAR_MX",
false);_f_Section("_SEC_ADFRM_MX",false);_f_Section("_SEC_LST_MX",false);_f_Section("_SEC_LDMR_MX",false);_f_RstLdr();_f_None(false);$(".jb-tqr-d-lifav-lk-mx").remove();_f_Spnr("_FAV_CTR",true);_f_GetFirst()}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_Visit=function(x){try{if(KgbLib_CheckNullity(x))return;var md=$(x).closest(".jb-tqr-d-lifav-lk-mx"),u=$(x).data("href"),fi=$(md).data("item");if(KgbLib_CheckNullity(fi)|KgbLib_CheckNullity(u))return;var s=$("<span/>");_f_Srv_GVst_Fav(fi,
u,s);document.getElementById($(md).find(".jb-tqr-d-lifav-lk-hrf").prop("id")).click();$(s).on("datasready",function(e,d){if(KgbLib_CheckNullity(d))return;_f_UpdFavMlDs($(md),d)})}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_DsplyDs=function(ds,dir){try{if(KgbLib_CheckNullity(ds)|KgbLib_CheckNullity(dir))return;var nds=KgbLib_CheckNullity(dir)||dir==="top"?ds.reverse():ds,ads=0;$.each(nds,function(x,d){if($(".jb-tqr-d-lifav-lk-mx[data-item='"+d.item+"']").length)return true;
var fv=_f_PprFavMl(d);fv=_f_RbdFavMl(fv);if(KgbLib_CheckNullity(dir)||dir==="top"){$(".jb-tqr-d-lifav-lk-mx.first").removeClass("first");$(fv).hide().prependTo(".jb-tqr-d-lifav-lks-lsts").fadeIn()}else $(fv).hide().appendTo(".jb-tqr-d-lifav-lks-lsts").fadeIn();++ads});var TG=new TIMEGOD;TG.UpdSpies();return ads}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_None=function(frc){try{var _f_op=function(){$(".jb-tqr-d-lifav-lks-lsts").addClass("this_hide");$(".jb-tqr-d-lifav-lks-btm").addClass("this_hide");
$(".jb-tqr-d-lifav-lsts-none-mx").removeClass("this_hide")};var _f_cl=function(){$(".jb-tqr-d-lifav-lsts-none-mx").addClass("this_hide")};if(frc===true)_f_op();else if(frc===false)_f_cl();else if($(".jb-tqr-d-lifav-lk-mx").length)_f_cl();else _f_op()}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_RstLdr=function(){try{$(".jb-tqr-d-lifav-ldmr-spr").addClass("this_hide");$(".jb-tqr-d-lifav-ldmr-none").addClass("this_hide");$(".jb-tqr-d-lifav-ldmr").removeClass("this_hide");
$(".jb-tqr-d-lifav-ldmr").data("lk",0)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_NoMr=function(shw){try{if(shw){$(".jb-tqr-d-lifav-ldmr").addClass("this_hide");$(".jb-tqr-d-lifav-ldmr-spr").addClass("this_hide");$(".jb-tqr-d-lifav-ldmr-none").removeClass("this_hide")}else{$(".jb-tqr-d-lifav-ldmr-spr").addClass("this_hide");$(".jb-tqr-d-lifav-ldmr-none").addClass("this_hide");$(".jb-tqr-d-lifav-ldmr").removeClass("this_hide")}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,
ex.lineNumber],true)}};var _f_Section=function(scp,shw){try{if(KgbLib_CheckNullity(scp))return;var mb;switch(scp){case "_SEC_HEAD_MX":mb=$(".jb-tiap-app-sctn-hdr[data-appname='favlink']");break;case "_SEC_FIL_MX":mb=$(".jb-tqr-d-lifav-nav");break;case "_SEC_XTRBAR_MX":mb=$(".jb-tqr-d-lifav-xtrbr");break;case "_SEC_ADFRM_MX":mb=$(".jb-tqr-d-lifav-adfrm-mx");break;case "_SEC_LST_MX":mb=$(".jb-tqr-d-lifav-lks-lsts");break;case "_SEC_LDMR_MX":mb=$(".jb-tqr-d-lifav-lks-btm");break;default:return}if(!$(mb).length)return;
else if(shw===true)$(mb).removeClass("this_hide");else $(mb).addClass("this_hide")}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_Spnr=function(scp,shw){try{if(KgbLib_CheckNullity(scp))return;var spnr,spcfk;switch(scp){case "_ADD_FAV":spnr=$(".jb-tqr-d-lifav-af-spnr");spcfk=false;break;case "_FAV_CTR":spnr=$(".jb-tqr-d-sctn-bdy-sprbar");spcfk=false;break;case "_LD_MR":spnr=$(".jb-tqr-d-lifav-ldmr-spr");spcfk=true;if(shw===true){$(".jb-tqr-d-lifav-ldmr-none").addClass("this_hide");
$(".jb-tqr-d-lifav-ldmr").addClass("this_hide");$(spnr).removeClass("this_hide")}else{$(spnr).addClass("this_hide");$(".jb-tqr-d-lifav-ldmr-none").addClass("this_hide");$(".jb-tqr-d-lifav-ldmr").removeClass("this_hide")}break;default:return}if(!spcfk)if(shw===true)$(spnr).removeClass("this_hide");else $(spnr).addClass("this_hide")}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_AddFavMl=function(d,dir){try{if(KgbLib_CheckNullity(d))return;var fv=_f_PprFavMl(d);fv=_f_RbdFavMl(fv);
if(KgbLib_CheckNullity(dir)||dir==="top"){$(".jb-tqr-d-lifav-lk-mx.first").removeClass("first");$(fv).hide().prependTo(".jb-tqr-d-lifav-lks-lsts").fadeIn()}else $(fv).hide().appendTo(".jb-tqr-d-lifav-lks-lsts").fadeIn();var TG=new TIMEGOD;TG.UpdSpies()}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_PprFavMl=function(d){try{if(KgbLib_CheckNullity(d))return;var e='<li class="tqr-d-lifav-lk-mx jb-tqr-d-lifav-lk-mx" data-item="" data-time="">';e+='<div class="tqr-d-lifav-lk-cntt-mx jb-tqr-d-lifav-lk-cntt-mx">';
e+='<div class="tqr-d-lifav-lk-cntt jb-tqr-d-lifav-lk-cntt">';e+="<a id='tqr-d-lifav-lk-hrf' class='jb-tqr-d-lifav-lk-hrf this_hide' target='_blank' href=''></a>";e+='<div class="tqr-d-lifav-lk-wpr" data-field="title">';e+='<a class="tqr-d-lifav-lk-tle jb-tqr-d-lifav-lk-tle" href="javascript:;"></a>';e+="</div>";e+='<div class="tqr-d-lifav-lk-wpr" data-field="url">';e+='<a class="tqr-d-lifav-lk-url jb-tqr-d-lifav-lk-url" href="javascript:;"></a>';e+="</div>";e+='<div class="tqr-d-lifav-lk-wpr" data-field="desc">';
e+='<span class="tqr-d-lifav-lk-dsc jb-tqr-d-lifav-lk-dsc"></span>';e+="</div>";e+='<div class="tqr-d-lifav-lk-xtrabar">';e+='<span class="tqr-d-lifav-lk-last jb-tqr-d-lifav-lk-last">Derni\u00e8re visite : ';e+='<span class="main">';e+='<span class="kxlib_tgspy tqr-d-lifav-tgspy" data-tgs-crd="" data-tgs-dd-atn="" data-tgs-dd-uut="">';e+="<span class='tgs-frm'></span>";e+="<span class='tgs-val'></span>";e+="<span class='tgs-uni'></span>";e+="</span>";e+="</span>";e+="</span>";e+='<span class="tqr-d-lifav-lk-cn jb-tqr-d-lifav-lk-cn">Nombre de visites : <span class="main"></span></span>';
e+="</div>";e+="</div>";e+='<div class="tqr-d-lifav-lk-optbar jb-tqr-d-lifav-lk-obr">';e+='<ul class="tqr-d-lifav-lk-opts jb-tqr-d-lifav-lk-opts this_hide">';e+='<li class="tqr-d-lifav-lk-opt-mx"><a class="tqr-d-lifav-lk-opt jb-tqr-d-lifav-lk-opt" data-action="delconf_start" href="javascript:;" title="Supprimer le lien favori">&times</a></li>';e+="</ul>";e+="</div>";e+="</div>";e+='<div class="tqr-d-lifav-lk-cnfdel-mx jb-tqr-d-lifav-lk-cnfdel-mx this_hide">';e+='<div class="tqr-d-lifav-lk-cfdl-hdr">';
e+="<span>Etes-vous s\u00fbr ?</span>";e+="</div>";e+='<div class="tqr-d-lifav-lk-cfdl-bdy">';e+='<a class="tqr-d-lifav-lk-cfdl jb-tqr-d-lifav-lk-cfdl" data-action="delconf_del" href="javascript:;">Oui</a>';e+='<a class="tqr-d-lifav-lk-cfdl jb-tqr-d-lifav-lk-cfdl" data-action="delconf_abort" href="javascript:;">Non</a>';e+="</div>";e+="</div>";e+="</li>";e=$.parseHTML(e);$(e).data("item",d.item);$(e).data("time",d.since);$(e).find(".jb-tqr-d-lifav-lk-hrf").prop("href",d.url).text("lien");$(e).find(".jb-tqr-d-lifav-lk-hrf").prop("id",
"tqr-d-lifav-lk-hrf-"+d.item).text("lien");$(e).find(".jb-tqr-d-lifav-lk-tle").prop("title",d.title).data("href",d.url).text(d.title);$(e).find(".jb-tqr-d-lifav-lk-url").prop("title",d.url).data("href",d.url).text(d.url);$(e).find(".jb-tqr-d-lifav-lk-dsc").text(d.desc);if(KgbLib_CheckNullity(d.last)){var m_="Jamais";$(e).find(".jb-tqr-d-lifav-lk-last .main").text(m_)}else{$(e).find(".kxlib_tgspy").data("tgs-crd",d.last);$(e).find(".kxlib_tgspy").data("tgs-dd-atn",d.last);$(e).find(".kxlib_tgspy").data("tgs-dd-uut",
d.last)}var visits=KgbLib_CheckNullity(d.visits)?0:d.visits;var x1__="Nombre total de visites depuis la cr\u00e9ation du lien favori";$(e).find(".jb-tqr-d-lifav-lk-cn .main").prop("title",x1__).text(visits);return e}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_RbdFavMl=function(e){try{if(KgbLib_CheckNullity(e))return;$(e).find(".jb-tqr-d-lifav-lk-tle, .jb-tqr-d-lifav-lk-url").click(function(e){Kxlib_PreventDefault(e);Kxlib_StopPropagation(e);_f_Action(this,"visit")});$(e).hover(function(){$(this).addClass("hover");
$(this).find(".jb-tqr-d-lifav-lk-opts").removeClass("this_hide")},function(){$(this).removeClass("hover");$(this).find(".jb-tqr-d-lifav-lk-opts").addClass("this_hide")});$(e).find(".jb-tqr-d-lifav-lk-opt").click(function(e){Kxlib_PreventDefault(e);Kxlib_StopPropagation(e);_f_Action(this)});$(e).find(".jb-tqr-d-lifav-lk-cfdl").click(function(e){Kxlib_PreventDefault(e);Kxlib_StopPropagation(e);_f_Action(this)});return e}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _Ax_AddFav=
Kxlib_GetAjaxRules("FVLK_ADD_FAV");var _f_Srv_AddFav=function(t,u,d,c,s){if(KgbLib_CheckNullity(t)|KgbLib_CheckNullity(u)|KgbLib_CheckNullity(c)|KgbLib_CheckNullity(s))return;var onsuccess=function(d){try{if(!KgbLib_CheckNullity(d))d=JSON.parse(d);else{_xhr_fvlk_addfv=null;return}if(!KgbLib_CheckNullity(d.err)){_xhr_fvlk_addfv=null;if(Kxlib_AjaxIsErrVolatile(d.err))switch(d.err){case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_USER_GONE":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_OWNER_GONE":case "__ERR_VOL_U_GONE":case "__ERR_VOL_U_G":Kxlib_HandleCurrUserGone();
break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":Kxlib_AJAX_HandleDeny();break;case "__ERR_VOL_FAILED":Kxlib_AJAX_HandleFailed();break;case "__ERR_VOL_DATAS_MSG":Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");break;case "__ERR_VOL_WRG_DATAS":return;case "__ERR_VOL_WRG_HACK":return;break;default:Kxlib_AJAX_HandleFailed();return}return}else if(d.hasOwnProperty("return"))if(!KgbLib_CheckNullity(d.return)){rds=[d.return];$(s).trigger("datasready",rds)}else $(s).trigger("operended");else{_xhr_fvlk_addfv=
null;return}}catch(e){_xhr_fvlk_addfv=null;return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var cu=document.URL;var toSend={"urqid":_Ax_AddFav.urqid,"datas":{"t":t,"u":u,"d":d,"c":c,"cu":cu}};_xhr_fvlk_addfv=Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_AddFav.url,wcrdtl:_Ax_AddFav.wcrdtl});return _xhr_fvlk_addfv};var _Ax_Delfav=Kxlib_GetAjaxRules("FVLK_DEL_FAV");var _f_Srv_Delfav=function(i,s){if(KgbLib_CheckNullity(i)|KgbLib_CheckNullity(s))return;var onsuccess=
function(d){try{if(!KgbLib_CheckNullity(d))d=JSON.parse(d);else{_xhr_fvlk_delfv=null;return}if(!KgbLib_CheckNullity(d.err)){_xhr_fvlk_delfv=null;if(Kxlib_AjaxIsErrVolatile(d.err))switch(d.err){case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_USER_GONE":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_OWNER_GONE":case "__ERR_VOL_U_GONE":case "__ERR_VOL_U_G":Kxlib_HandleCurrUserGone();break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":Kxlib_AJAX_HandleDeny();break;case "__ERR_VOL_FAILED":Kxlib_AJAX_HandleFailed();
break;case "__ERR_VOL_DATAS_MSG":Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");break;case "__ERR_VOL_WRG_DATAS":return;case "__ERR_VOL_WRG_HACK":return;break;default:Kxlib_AJAX_HandleFailed();return}return}else if(d.hasOwnProperty("return"))if(!KgbLib_CheckNullity(d.return)){rds=[d.return];$(s).trigger("datasready",rds)}else $(s).trigger("operended");else{_xhr_fvlk_delfv=null;return}}catch(e){_xhr_fvlk_delfv=null;return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b)};var u=document.URL;
var toSend={"urqid":_Ax_Delfav.urqid,"datas":{"i":i,"cu":u}};_xhr_fvlk_delfv=Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_Delfav.url,wcrdtl:_Ax_Delfav.wcrdtl});return _xhr_fvlk_delfv};var _Ax_GetFirst_Fav=Kxlib_GetAjaxRules("FVLK_GET_FST_FAV");var _f_Srv_GetFst_Fav=function(f,s){if(KgbLib_CheckNullity(f)|KgbLib_CheckNullity(s))return;if(!KgbLib_CheckNullity(_xhr_fvlk_gtfst_fv))_xhr_fvlk_gtfst_fv.abort();if(!KgbLib_CheckNullity(_xhr_fvlk_gtfrm_fv))_xhr_fvlk_gtfrm_fv.abort();
var onsuccess=function(d){try{if(!KgbLib_CheckNullity(d))d=JSON.parse(d);else{_xhr_fvlk_gtfst_fv=null;return}if(!KgbLib_CheckNullity(d.err)){_xhr_fvlk_gtfst_fv=null;if(Kxlib_AjaxIsErrVolatile(d.err))switch(d.err){case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_USER_GONE":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_OWNER_GONE":case "__ERR_VOL_U_GONE":case "__ERR_VOL_U_G":Kxlib_HandleCurrUserGone();break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":Kxlib_AJAX_HandleDeny();break;case "__ERR_VOL_FAILED":Kxlib_AJAX_HandleFailed();
break;case "__ERR_VOL_DATAS_MSG":Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");break;case "__ERR_VOL_WRG_DATAS":return;case "__ERR_VOL_WRG_HACK":return;default:Kxlib_AJAX_HandleFailed();return}return}else if(d.hasOwnProperty("return"))if(!KgbLib_CheckNullity(d.return)&&d.return.hasOwnProperty("lksdom")&&!KgbLib_CheckNullity(d.return.lksdom)){rds=[d.return];$(s).trigger("datasready",rds)}else $(s).trigger("operended");else{_xhr_fvlk_gtfst_fv=null;return}}catch(e){_xhr_fvlk_gtfst_fv=null;return}};
var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var cu=document.URL;var toSend={"urqid":_Ax_GetFirst_Fav.urqid,"datas":{"f":f,"cu":cu}};_xhr_fvlk_gtfst_fv=Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_GetFirst_Fav.url,wcrdtl:_Ax_GetFirst_Fav.wcrdtl});return _xhr_fvlk_gtfst_fv};var _Ax_GetFrom_Fav=Kxlib_GetAjaxRules("FVLK_GET_FRM_FAV");var _f_Srv_GetFrm_Fav=function(fil,lfi,lft,x,s){if(KgbLib_CheckNullity(fil)|KgbLib_CheckNullity(lfi)|KgbLib_CheckNullity(lft)|KgbLib_CheckNullity(x)|
KgbLib_CheckNullity(s))return;var onsuccess=function(d){try{if(!KgbLib_CheckNullity(d))d=JSON.parse(d);else{$(x).data("lk",0);_xhr_fvlk_gtfrm_fv=null;return}if(!KgbLib_CheckNullity(d.err)){$(x).data("lk",0);_xhr_fvlk_gtfrm_fv=null;if(Kxlib_AjaxIsErrVolatile(d.err))switch(d.err){case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_USER_GONE":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_OWNER_GONE":case "__ERR_VOL_U_GONE":case "__ERR_VOL_U_G":Kxlib_HandleCurrUserGone();break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":Kxlib_AJAX_HandleDeny();
break;case "__ERR_VOL_FAILED":Kxlib_AJAX_HandleFailed();break;case "__ERR_VOL_DATAS_MSG":Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");break;case "__ERR_VOL_WRG_DATAS":return;case "__ERR_VOL_WRG_HACK":return;default:Kxlib_AJAX_HandleFailed();return}return}else if(d.hasOwnProperty("return"))if(!KgbLib_CheckNullity(d.return)&&d.return.hasOwnProperty("lksdom")&&!KgbLib_CheckNullity(d.return.lksdom)){rds=[d.return];$(s).trigger("datasready",rds)}else $(s).trigger("operended");else{$(x).data("lk",
0);_xhr_fvlk_gtfrm_fv=null;return}}catch(e){$(x).data("lk",0);_xhr_fvlk_gtfrm_fv=null;return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var cu=document.URL;var toSend={"urqid":_Ax_GetFrom_Fav.urqid,"datas":{"fil":fil,"lfi":lfi,"lft":lft,"cu":cu}};_xhr_fvlk_gtfrm_fv=Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_GetFrom_Fav.url,wcrdtl:_Ax_GetFrom_Fav.wcrdtl});return _xhr_fvlk_gtfrm_fv};var _Ax_GVst_Fav=Kxlib_GetAjaxRules("FVLK_VST_FAV");var _f_Srv_GVst_Fav=function(fi,
u,s){if(KgbLib_CheckNullity(fi)|KgbLib_CheckNullity(u)|KgbLib_CheckNullity(s))return;var onsuccess=function(d){try{if(!KgbLib_CheckNullity(d))d=JSON.parse(d);else{_xhr_gvst_fv=null;return}if(!KgbLib_CheckNullity(d.err)){_xhr_gvst_fv=null;if(Kxlib_AjaxIsErrVolatile(d.err))switch(d.err){case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_USER_GONE":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_OWNER_GONE":case "__ERR_VOL_U_GONE":case "__ERR_VOL_U_G":Kxlib_HandleCurrUserGone();break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":Kxlib_AJAX_HandleDeny();
break;case "__ERR_VOL_FAILED":Kxlib_AJAX_HandleFailed();break;case "__ERR_VOL_DATAS_MSG":Kxlib_AJAX_HandleFailed("ERR_COM_AJAX_FAIL_TECH");break;case "__ERR_VOL_WRG_DATAS":return;case "__ERR_VOL_WRG_HACK":return;default:Kxlib_AJAX_HandleFailed();return}return}else if(d.hasOwnProperty("return")&&!KgbLib_CheckNullity(d.return)){var rds=[d.return];$(s).trigger("datasready",rds)}else{_xhr_gvst_fv=null;return}}catch(e){_xhr_gvst_fv=null;return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};
var cu=document.URL;var toSend={"urqid":_Ax_GVst_Fav.urqid,"datas":{"fi":fi,"u":u,"cu":cu}};_xhr_gvst_fv=Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_GVst_Fav.url,wcrdtl:_Ax_GVst_Fav.wcrdtl});return _xhr_gvst_fv};$(".jb-tqr-d-lf-flt").click(function(e){Kxlib_PreventDefault(e);_f_Action(this,"chng_fil")});$(".jb-tqr-d-lifav-af-catch").click(function(e){Kxlib_PreventDefault(e);$(this).addClass("this_hide");$(".jb-tqr-d-lifav-adfrm").removeClass("this_hide");$(".jb-tqr-d-lifav-af-fld[data-field='title']").focus()});
$(".jb-tqr-d-lifav-adfrm-clz").click(function(e){Kxlib_PreventDefault(e);$(".jb-tqr-d-lifav-adfrm").addClass("this_hide");$(".jb-tqr-d-lifav-af-catch").removeClass("this_hide")});$(".jb-tqr-d-lifav-af-sbmt").click(function(e){Kxlib_PreventDefault(e);_f_Action(this)});$(".jb-tqr-d-lifav-lk-mx").hover(function(){$(this).addClass("hover");$(this).find(".jb-tqr-d-lifav-lk-opts").removeClass("this_hide")},function(){$(this).removeClass("hover");$(this).find(".jb-tqr-d-lifav-lk-opts").addClass("this_hide")});
$(".jb-tqr-d-lifav-lk-opt").click(function(e){Kxlib_PreventDefault(e);Kxlib_StopPropagation(e);_f_Action(this)});$(".jb-tqr-d-lifav-lk-cfdl").click(function(e){Kxlib_PreventDefault(e);Kxlib_StopPropagation(e);_f_Action(this)});$(".jb-tqr-d-lifav-lk-tle, .jb-tqr-d-lifav-lk-url").click(function(e){Kxlib_PreventDefault(e);Kxlib_StopPropagation(e);_f_Action(this,"visit")});$(".jb-tqr-d-lifav-ldmr").click(function(e){Kxlib_PreventDefault(e);_f_Action(this)})}});