function EVALBOX(){var gt=this;this.Action=function(x){try{if(KgbLib_CheckNullity(x))return;var a=$(x).data("action"),p=$(x).data("xc"),b=_f_GetArtBox($(x),p),t,i;if(b==="tq:skip_psmn"){if(KgbLib_CheckNullity($(".jb-unq-art-mdl.active").data("psmn"))|typeof $(".jb-unq-art-mdl.active").data("psmn")!=="string")return;var t__=$(".jb-unq-art-mdl.active").data("psmn");t__=Kxlib_DataCacheToArray(t__);i=t__[0][0];t=t__[0][1];b=$(".jb-unq-art-mdl.active");p="psmn"}else{t=$(b).data("atype");i=$(b).data("item")}if(KgbLib_CheckNullity(a)|
KgbLib_CheckNullity(b)|KgbLib_CheckNullity(t))return;var s=$("<span/>");switch(a){case "rh_spcl":_f_Srv_RhnSpcl(x,t,b,p,i,s);break;case "rh_cool":_f_Srv_RhnCl(x,t,b,p,i,s);break;case "rh_dislk":_f_Srv_RhnDslk(x,t,b,p,i,s);break;case "bk_spcl":_f_Srv_BkSpcl(x,t,b,p,i,s);break;case "bk_cool":_f_Srv_BkCl(x,t,b,p,i,s);break;case "bk_dislk":_f_Srv_BkDslk(x,t,b,p,i,s);break;default:return}var b__;if($.inArray(p,["unq","nwfd"])!==-1)b__=$(".jb-unq-art-mdl.active");else if($.inArray(p,["arp"])!==-1)b__=b;
else b__=b;_f_ShPgrsBr(b__,true);$(s).on("datasready",function(e,x,t,b,i,d){if(KgbLib_CheckNullity(d)|!d.hasOwnProperty("me"))return;gt.DplCUzrEvl(b__,d.me);gt.UpdateModelWithEval(b,p,d,d.me,a);if(d.hasOwnProperty("ocap"))$(".jb-u-sp-cap-nb").text(d.ocap);if($.inArray(p,["unq","nwfd"])!==-1)b=$(".jb-unq-art-mdl.active");_f_ShPgrsBr(b)})}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_GetArtBox=function(x,a){if(KgbLib_CheckNullity(a))return;try{var b;a=a.toLowerCase();switch(a){case "arp":b=
$(x).closest(".jb-tmlnr-mdl-std");break;case "trpg":break;case "nwfd":b=$(x).closest(".jb-unq-bind-art-mdl");break;case "unq":var t=$(".jb-unq-art-mdl.active").data("item");i=Kxlib_DataCacheToArray(t)[0][1];if(i==="tq:skip_psmn")return i;b=Kxlib_ValidIdSel(i);break;default:return}return b}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};this.DplCUzrEvl=function(b,me){try{if(KgbLib_CheckNullity(b))return;this.RmvAlEvl(b);if(!KgbLib_CheckNullity(me));var x;switch(me){case "p2":x=$(b).find(".jb-csam-eval-spcool");
break;case "p1":x=$(b).find(".jb-csam-eval-cool");break;case "m1":x=$(b).find(".jb-csam-eval-dislk");break;default:this.RmvAlEvl(b);return}this.DisplayEval(x,b);return b}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};this.RhanaSpcl=function(){};this.RhanaCool=function(x,b,t,p){};this.RhanaDislk=function(){};this.BackSpcl=function(){};this.BackCool=function(){};this.BackDislk=function(){};var _Ax_RhnCl=Kxlib_GetAjaxRules("EVAL_RHCOOL");var _f_Srv_RhnCl=function(x,t,b,p,i,s){if(KgbLib_CheckNullity(x)|
KgbLib_CheckNullity(t)|KgbLib_CheckNullity(b)|KgbLib_CheckNullity(p)|KgbLib_CheckNullity(i)|KgbLib_CheckNullity(s))return;var onsuccess=function(datas){try{if(!KgbLib_CheckNullity(datas))datas=JSON.parse(datas);else return;if(!KgbLib_CheckNullity(datas.err)){if(Kxlib_AjaxIsErrVolatile(datas.err))switch(datas.err){case "__ERR_VOL_U_G":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_TARGET_GONE":Kxlib_HandleCurrUserGone();break;case "__ERR_VOL_ART_GONE":return;break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":return;
break;default:return;break}return}else if(!KgbLib_CheckNullity(datas.return)){var ds=[x,t,b,i,datas.return];$(s).trigger("datasready",ds)}else return}catch(ex){return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var pg=Kxlib_GetPagegProperties();var cu=document.URL;var toSend={"urqid":_Ax_RhnCl.urqid,"datas":{"ec":"_EVAL_CL","t":t,"i":i,"mdl":p,"pg_prop":{"pg":pg["pg"],"ver":pg["ver"]},curl:cu}};Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_RhnCl.url,wcrdtl:_Ax_RhnCl.wcrdtl})};
var _Ax_RhnDslk=Kxlib_GetAjaxRules("EVAL_RHDSLK");var _f_Srv_RhnDslk=function(x,t,b,p,i,s){if(KgbLib_CheckNullity(x)|KgbLib_CheckNullity(t)|KgbLib_CheckNullity(b)|KgbLib_CheckNullity(p)|KgbLib_CheckNullity(i)|KgbLib_CheckNullity(s))return;var onsuccess=function(datas){try{if(!KgbLib_CheckNullity(datas))datas=JSON.parse(datas);else return;if(!KgbLib_CheckNullity(datas.err)){if(Kxlib_AjaxIsErrVolatile(datas.err))switch(datas.err){case "__ERR_VOL_CU_GONE":case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_TARGET_GONE":Kxlib_HandleCurrUserGone();
break;case "__ERR_VOL_ART_GONE":return;break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":return;break;default:return;break}return}else if(!KgbLib_CheckNullity(datas.return)){var ds=[x,t,b,i,datas.return];$(s).trigger("datasready",ds)}else return}catch(ex){return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var pg=Kxlib_GetPagegProperties();var cu=document.URL;var toSend={"urqid":_Ax_RhnDslk.urqid,"datas":{"ec":"_EVAL_DLK","t":t,"i":i,"mdl":p,"pg_prop":{"pg":pg["pg"],"ver":pg["ver"]},
curl:cu}};Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_RhnDslk.url,wcrdtl:_Ax_RhnDslk.wcrdtl})};var _Ax_RhnSpcl=Kxlib_GetAjaxRules("EVAL_RHSPCL");var _f_Srv_RhnSpcl=function(x,t,b,p,i,s){if(KgbLib_CheckNullity(x)|KgbLib_CheckNullity(t)|KgbLib_CheckNullity(b)|KgbLib_CheckNullity(p)|KgbLib_CheckNullity(i)|KgbLib_CheckNullity(s))return;var onsuccess=function(datas){try{if(!KgbLib_CheckNullity(datas))datas=JSON.parse(datas);else return;if(!KgbLib_CheckNullity(datas.err)){if(Kxlib_AjaxIsErrVolatile(datas.err))switch(datas.err){case "__ERR_VOL_U_G":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_TARGET_GONE":Kxlib_HandleCurrUserGone();
break;case "__ERR_VOL_ART_GONE":return;break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":return;break;default:return;break}return}else if(!KgbLib_CheckNullity(datas.return)){var ds=[x,t,b,i,datas.return];$(s).trigger("datasready",ds)}else return}catch(ex){return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var pg=Kxlib_GetPagegProperties();var cu=document.URL;var toSend={"urqid":_Ax_RhnSpcl.urqid,"datas":{"ec":"_EVAL_SPCL","t":t,"i":i,"mdl":p,"pg_prop":{"pg":pg["pg"],"ver":pg["ver"]},
curl:cu}};Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_RhnSpcl.url,wcrdtl:_Ax_RhnSpcl.wcrdtl})};var _Ax_BkCl=Kxlib_GetAjaxRules("EVAL_BKCOOL");var _f_Srv_BkCl=function(x,t,b,p,i,s){if(KgbLib_CheckNullity(x)|KgbLib_CheckNullity(t)|KgbLib_CheckNullity(b)|KgbLib_CheckNullity(p)|KgbLib_CheckNullity(i)|KgbLib_CheckNullity(s))return;var onsuccess=function(datas){try{if(!KgbLib_CheckNullity(datas))datas=JSON.parse(datas);else return;if(!KgbLib_CheckNullity(datas.err)){if(Kxlib_AjaxIsErrVolatile(datas.err))switch(datas.err){case "__ERR_VOL_U_G":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_TARGET_GONE":Kxlib_HandleCurrUserGone();
break;case "__ERR_VOL_ART_GONE":return;break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":return;break;default:return;break}return}else if(!KgbLib_CheckNullity(datas.return)){var ds=[x,t,b,i,datas.return];$(s).trigger("datasready",ds)}else return}catch(ex){return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var pg=Kxlib_GetPagegProperties();var cu=document.URL;var toSend={"urqid":_Ax_BkCl.urqid,"datas":{"ec":"_EVAL_CL","t":t,"i":i,"mdl":p,"pg_prop":{"pg":pg["pg"],"ver":pg["ver"]},
curl:cu}};Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_BkCl.url,wcrdtl:_Ax_BkCl.wcrdtl})};var _Ax_BkDslk=Kxlib_GetAjaxRules("EVAL_BKDSLK");var _f_Srv_BkDslk=function(x,t,b,p,i,s){if(KgbLib_CheckNullity(x)|KgbLib_CheckNullity(t)|KgbLib_CheckNullity(b)|KgbLib_CheckNullity(p)|KgbLib_CheckNullity(i)|KgbLib_CheckNullity(s))return;var onsuccess=function(datas){try{if(!KgbLib_CheckNullity(datas))datas=JSON.parse(datas);else return;if(!KgbLib_CheckNullity(datas.err)){if(Kxlib_AjaxIsErrVolatile(datas.err))switch(datas.err){case "__ERR_VOL_CU_GONE":case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_TARGET_GONE":Kxlib_HandleCurrUserGone();
break;case "__ERR_VOL_ART_GONE":return;break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":return;break;default:return;break}return}else if(!KgbLib_CheckNullity(datas.return)){var ds=[x,t,b,i,datas.return];$(s).trigger("datasready",ds)}else return}catch(ex){return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var pg=Kxlib_GetPagegProperties();var cu=document.URL;var toSend={"urqid":_Ax_BkDslk.urqid,"datas":{"ec":"_EVAL_DLK","t":t,"i":i,"mdl":p,"pg_prop":{"pg":pg["pg"],"ver":pg["ver"]},
curl:cu}};Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_BkDslk.url,wcrdtl:_Ax_BkDslk.wcrdtl})};var _Ax_BkSpcl=Kxlib_GetAjaxRules("EVAL_BKSPCL");var _f_Srv_BkSpcl=function(x,t,b,p,i,s){if(KgbLib_CheckNullity(x)|KgbLib_CheckNullity(t)|KgbLib_CheckNullity(b)|KgbLib_CheckNullity(p)|KgbLib_CheckNullity(i)|KgbLib_CheckNullity(s))return;var onsuccess=function(datas){try{if(!KgbLib_CheckNullity(datas))datas=JSON.parse(datas);else return;if(!KgbLib_CheckNullity(datas.err)){if(Kxlib_AjaxIsErrVolatile(datas.err))switch(datas.err){case "__ERR_VOL_U_G":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_TARGET_GONE":Kxlib_HandleCurrUserGone();
break;case "__ERR_VOL_ART_GONE":return;break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":return;break;default:return;break}return}else if(!KgbLib_CheckNullity(datas.return)){var ds=[x,t,b,i,datas.return];$(s).trigger("datasready",ds)}else return}catch(ex){return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var pg=Kxlib_GetPagegProperties();var curl=document.URL;var toSend={"urqid":_Ax_BkSpcl.urqid,"datas":{"ec":"_EVAL_SPCL","t":t,"i":i,"mdl":p,"pg_prop":{"pg":pg["pg"],"ver":pg["ver"]},
curl:curl}};Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_BkSpcl.url,wcrdtl:_Ax_BkSpcl.wcrdtl})};this.RmvAlEvl=function(b){if(KgbLib_CheckNullity(b))return;try{$(b).find(".jb-csam-eval-choices").removeClass("css-c-e-chs-scl_hover css-c-e-chs-cl_hover css-c-e-chs-dsp_hover active");var l=$(b).find(".jb-csam-eval-choices");$.each(l,function(x,v){var zr=$(v).data("zr");$(v).data("action",zr)})}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};this.DisplayEval=function(x){try{if(KgbLib_CheckNullity(x)|
KgbLib_CheckNullity($(x).data("action")))return;var s="";var me="",a=$(x).data("action").toLowerCase();switch(a){case "rh_spcl":s="css-c-e-chs-scl_hover";$(x).addClass(s);$(x).addClass("active");$(x).parent().find(".css-c-e-chs-cl").addClass("css-c-e-chs-cl_hover");$(x).parent().find(".css-c-e-chs-cl").addClass("active");var rv=$(x).data("rev");$(x).data("action",rv);var at=$(x).parent().find(".jb-csam-eval-choices").not($(x));$.each(at,function(x,v){var d=$(v).data("zr");$(v).data("action",d)});
$(x).parent().find(".css-c-e-chs-dsp").removeClass("css-c-e-chs-dsp_hover");me="p2";break;case "rh_cool":s="css-c-e-chs-cl_hover";$(x).addClass(s);$(x).addClass("active");var rv=$(x).data("rev");$(x).data("action",rv);var at=$(x).parent().find(".jb-csam-eval-choices").not($(x));$.each(at,function(x,v){var d=$(v).data("zr");$(v).data("action",d)});$(x).parent().find(".css-c-e-chs-scl").removeClass("css-c-e-chs-scl_hover");$(x).parent().find(".css-c-e-chs-scl").removeClass("active");$(x).parent().find(".css-c-e-chs-dsp").removeClass("css-c-e-chs-dsp_hover");
$(x).parent().find(".css-c-e-chs-dsp").removeClass("active");me="p1";break;case "rh_dislk":s="css-c-e-chs-dsp_hover";$(x).addClass(s);$(x).addClass("active");var rv=$(x).data("rev");$(x).data("action",rv);var at=$(x).parent().find(".jb-csam-eval-choices").not($(x));$.each(at,function(x,v){var d=$(v).data("zr");$(v).data("action",d)});$(x).parent().find(".css-c-e-chs-scl").removeClass("css-c-e-chs-scl_hover");$(x).parent().find(".css-c-e-chs-scl").removeClass("active");$(x).parent().find(".css-c-e-chs-cl").removeClass("css-c-e-chs-cl_hover");
$(x).parent().find(".css-c-e-chs-cl").removeClass("active");me="m1";break;case "bk_spcl":case "bk_cool":case "bk_dislk":$(x).parent().find(".css-c-e-chs-scl").removeClass("css-c-e-chs-scl_hover");$(x).parent().find(".css-c-e-chs-scl").removeClass("active");$(x).parent().find(".css-c-e-chs-cl").removeClass("css-c-e-chs-cl_hover");$(x).parent().find(".css-c-e-chs-cl").removeClass("active");$(x).parent().find(".css-c-e-chs-dsp").removeClass("css-c-e-chs-dsp_hover");$(x).parent().find(".css-c-e-chs-dsp").removeClass("active");
var at=$(x).parent().find(".jb-csam-eval-choices");$.each(at,function(x,v){var d=$(v).data("zr");$(v).data("action",d)});break;default:return}return me}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};this.UpdateModelWithEval=function(b,p,d,me,a){try{if(KgbLib_CheckNullity(b)&&KgbLib_CheckNullity(p)&&KgbLib_CheckNullity(d))return;p=p.toLowerCase();switch(p){case "arp":case "psmn":var c="["+d.eval.join(",")+","+me+"]";$(b).find(".jb-csam-eval-oput").data("cache",c);$(b).find(".jb-evlbx-ch-nb[data-scp='scl']").text(d.eval[0]);
$(b).find(".jb-evlbx-ch-nb[data-scp='cl']").text(d.eval[1]);$(b).find(".jb-evlbx-ch-nb[data-scp='dlk']").text(d.eval[2]);$(b).find(".jb-csam-eval-oput").find("span:first").text(d.eval[3]);break;case "trpg":break;case "nwfd":case "unq":var s=$(b).data("cache");s=Kxlib_AlterDataCacheAt(s,d.eval[0],2,0);s=Kxlib_AlterDataCacheAt(s,d.eval[1],2,1);s=Kxlib_AlterDataCacheAt(s,d.eval[2],2,2);s=Kxlib_AlterDataCacheAt(s,d.eval[3],2,3);s=Kxlib_AlterDataCacheAt(s,me,4,0);$(b).data("cache",s);var c="["+d.eval.join(",")+
","+me+"]";$(b).find(".jb-csam-eval-oput").data("cache",c);$(b).find(".jb-evlbx-ch-nb[data-scp='scl']").text(d.eval[0]);$(b).find(".jb-evlbx-ch-nb[data-scp='cl']").text(d.eval[1]);$(b).find(".jb-evlbx-ch-nb[data-scp='dlk']").text(d.eval[2]);$(b).find(".jb-csam-eval-oput span:first").text(d.eval[3]);if($(b).find(".jb-csam-eval-box").length){var eb=$(b).find(".jb-csam-eval-box");$(eb).find(".jb-csam-eval-oput span:first").text(d.eval[3]);var f="["+d.eval.join(",")+","+me+"]";$(eb).find(".jb-csam-eval-oput").data("cache",
f);if(p==="unq"){var u=$(b).find(".jb-csam-eval-box > div:first").find("a[data-zr='"+a+"']");if($(u).length)this.DisplayEval(u,b)}}if($(b).is(".jb-eval-bind-moz")){var j=$(b).find(".nwfd-b-m-mdl-trig").data("cache");j=Kxlib_AlterDataCacheAt(j,d.eval[0],3,0);j=Kxlib_AlterDataCacheAt(j,d.eval[1],3,1);j=Kxlib_AlterDataCacheAt(j,d.eval[2],3,2);j=Kxlib_AlterDataCacheAt(j,d.eval[3],3,3);$(b).find(".nwfd-b-m-mdl-trig").data("cache",j);$(b).find(".nwfd-b-moz-art-ss-eval span:first").text(d.eval[3])}if($(".jb-unq-art-mdl.active").length){b=
$(".jb-unq-art-mdl.active");c="["+d.eval.join(",")+","+me+"]";$(b).find(".jb-csam-eval-oput").data("cache",c);$(b).find(".jb-evlbx-ch-nb[data-scp='scl']").text(d.eval[0]);$(b).find(".jb-evlbx-ch-nb[data-scp='cl']").text(d.eval[1]);$(b).find(".jb-evlbx-ch-nb[data-scp='dlk']").text(d.eval[2]);$(b).find(".jb-csam-eval-oput span:first").text(d.eval[3])}break;default:return}return b}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_ShPgrsBr=function(b,shw){if(KgbLib_CheckNullity(b)|
!$(b).length|!$(b).find(".jb-eval-dplw-bar-mx").length|!$(b).find(".jb-eval-wait-bar").length)return;if(shw){$(b).find(".jb-eval-dplw-bar-mx").addClass("this_hide");$(b).find(".jb-eval-wait-bar").removeClass("this_hide")}else{$(b).find(".jb-eval-wait-bar").addClass("this_hide");$(b).find(".jb-eval-dplw-bar-mx").removeClass("this_hide")}};$(".css-c-e-chs-scl").hover(function(){if(!$(this).parent().find(".css-c-e-chs-cl").hasClass("active"))$(this).parent().find(".css-c-e-chs-cl").addClass("css-c-e-chs-cl_hover")},
function(){if(!$(this).parent().find(".css-c-e-chs-cl").hasClass("active"))$(this).parent().find(".css-c-e-chs-cl").removeClass("css-c-e-chs-cl_hover")})}(function(){$O=new EVALBOX;$(".jb-csam-eval-choices").click(function(e){Kxlib_PreventDefault(e);$O.Action(this)})})();
