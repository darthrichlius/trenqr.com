function TrendHeader(){var gt=this;var _xhr_dlcvr;var _xhr_chcvr;var _FnlImg;var _TrSrvStgs;this.TrSrvSetgs=function(){return _TrSrvStgs};var _f_Gdf=function(){var ir={"ft":["png","jpg","jpeg"],"sz":1048576*2.5,"w_min":260,"w_max":5500,"h_min":260,"h_max":5500};return ir};this.UpdHdrWNwStgs=function(d){try{var t=Kxlib_Decode_After_Encode(d.t);$(".jb-a-h-t-top-tr-tle").text(t);var ds=Kxlib_Decode_After_Encode(d.d);var t__=$("<div/>").text(ds).text();$(".jb-a-h-t-top-tr-desc").text(t__);if($("#trpg_cov_part").length&&
$("#trpg_cov_part").parent().find(".jb-trpg-cov-part-lg").length===1&&d.hasOwnProperty("p")&&!KgbLib_CheckNullity(d.p[0])&&!KgbLib_CheckNullity(d.p[1])&&$.inArray(d.p[0],["pub","pri"])!==-1){switch(d.p[0].toLowerCase()){case "pub":if($(".jb-trpg-cov-part-lg.lock").length){var x__=$("<i/>",{class:"fa fa-unlock-alt jb-trpg-cov-part-lg unlock"});$(".jb-trpg-cov-part-lg.lock").replaceWith(x__)}else return;break;case "pri":if($(".jb-trpg-cov-part-lg.unlock").length){var x__=$("<i/>",{class:"fa fa-lock jb-trpg-cov-part-lg lock"});
$(".jb-trpg-cov-part-lg.unlock").replaceWith(x__)}else return;break;default:return}$("#trpg_cov_part").text(d.p[1]);$(".jb-ttr-cache").data("p",d.p.join(","))}}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_SvCvr=function(x){try{if($(".jb-chcvr-ch-tgr[data-action='confirm_save_cover']").data("lk")===1)return;if(!KgbLib_CheckNullity(_xhr_chcvr)|!KgbLib_CheckNullity(_xhr_dlcvr))return;$(".jb-chcvr-ch-tgr").data("lk",1);$(".jb-trpg-cov-dlcf-gninf").html("");$(".jb-trpg-cov-dlcf-gninf-mx").addClass("this_hide");
$(".jb-trpg-cov-dlcf-dc-mx").addClass("this_hide");$(".jb-trpg-cov-dlcf-wait-mx").removeClass("this_hide");$(".jb-trpg-cov-delconf-mx").removeClass("this_hide");$("#a-h-t-top-tr-img-img").remove();$("#cov_img_buffer").attr("id","a-h-t-top-tr-img-img");_f_TogMnChcs();$("#a-h-t-top-tr-img-img").draggable("destroy");$("#a-h-t-top-tr-img-img").removeClass("cover_move");$("#a-h-t-top-fade").hide();var final=$("#a-h-t-top-tr-img-img");var ih=parseInt($(final).css("height").replace("px",""));var iw=parseInt($(final).css("width").replace("px",
""));var it=$(final).position().top;it=it==="auto"?0:parseInt(it);var ds={img:$(final).attr("src"),in:_FnlImg.name,ih:ih,iw:iw,it:it};var pp=Kxlib_GetTrendPropIfExist();if(KgbLib_CheckNullity(pp))return;var ti=pp.trid;var s=$("<span/>");_f_Srv_SvCvr(ti,ds,s);_f_Rst_Form();$(s).on("datasready",function(e,d){if(KgbLib_CheckNullity(d))return;if($("#cov_img_buffer").length)return;if(!$("#a-h-t-top-tr-img-img").length)return;var $im=$("<img/>");$($im).attr({"id":"a-h-t-top-img_new","src":d.trcov.cov_rp,
"style":"top: "+d.trcov.cov_t+"px; left: 0; position: absolute; z-index: 3;","height":d.trcov.cov_h,"width":d.trcov.cov_w});$("#a-h-t-top-tr-img-max").prepend($im);$(".tr-header-fade").show();setTimeout(function(){$("#a-h-t-top-tr-img-img").remove();$("#a-h-t-top-img_new").attr({"id":"a-h-t-top-tr-img-img","style":"top: "+d.trcov.cov_t+"px;","class":"a-h-t-top-img jb-a-h-t-top-img"});$(".jb-trpg-cov-none").remove()},2E3);var Nty=new Notifyzing;Nty.FromUserAction("ua_acov_set");$(".jb-trpg-cov-dlcf-gninf-mx").addClass("this_hide");
$(".jb-trpg-cov-dlcf-wait-mx").addClass("this_hide");$(".jb-trpg-cov-dlcf-dc-mx").addClass("this_hide");$(".jb-trpg-cov-delconf-mx").addClass("this_hide");$(".afl_choice.bind-deltrcov").removeClass("this_hide");_xhr_chcvr=null;$(".jb-chcvr-ch-tgr").data("lk",0)});$(s).on("operended",function(){})}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_Rst_Form=function(){Kxlib_ResetFormElt("tr-chcov-trg");return true};var _f_DelCvr=function(x){try{if(KgbLib_CheckNullity(x)|!$(x).length|
KgbLib_CheckNullity($(x).data("action")))return;if(!KgbLib_CheckNullity(_xhr_dlcvr)|!KgbLib_CheckNullity(_xhr_chcvr))return;if(!$(".a-h-t-top-img").length)return;var a=$(x).data("action");switch(a){case "start_del_cover":$("#tr-h-an-hi").click();$(".jb-trpg-cov-dlcf-dc-dc-tgr").data("lk",0);$(".jb-trpg-cov-dlcf-gninf-mx").addClass("this_hide");$(".jb-trpg-cov-dlcf-wait-mx").addClass("this_hide");$(".jb-trpg-cov-dlcf-dc-mx").removeClass("this_hide");$(".jb-trpg-cov-delconf-mx").removeClass("this_hide");
return;case "abort_del_cover":$(".jb-trpg-cov-delconf-mx").addClass("this_hide");$(".jb-trpg-cov-dlcf-dc-mx").addClass("this_hide");return;case "confirm_del_cover":break;default:return}if($(".jb-trpg-cov-dlcf-dc-dc-tgr").first().data("lk")===1)return;$(".jb-trpg-cov-dlcf-dc-dc-tgr").data("lk",1);$(".jb-trpg-cov-dlcf-gninf-mx").addClass("this_hide");$(".jb-trpg-cov-dlcf-dc-mx").addClass("this_hide");$(".jb-trpg-cov-dlcf-wait-mx").removeClass("this_hide");var pp=Kxlib_GetTrendPropIfExist();if(KgbLib_CheckNullity(pp))return;
var ti=pp.trid;var s=$("<span/>");_f_Srv_DelCvr(ti,s);$(s).on("datasready",function(e,d){if(KgbLib_CheckNullity(d))return;$(".a-h-t-top-img").remove();var $img=$("<img/>",{src:Kxlib_GetExtFileURL("sys_url_img","r/3pt-w.png")});var $nne=$("<span/>",{id:"trpg-cov-none",class:"jb-trpg-cov-none"}).append($img);$($nne).insertAfter(".jb-trpg-cov-delconf-mx");$(".jb-trpg-cov-dlcf-gninf-mx").addClass("this_hide");$(".jb-trpg-cov-dlcf-wait-mx").addClass("this_hide");$(".jb-trpg-cov-dlcf-dc-mx").addClass("this_hide");
$(".jb-trpg-cov-delconf-mx").addClass("this_hide");var Nty=new Notifyzing;Nty.FromUserAction("ua_acov_set");$(".afl_choice.bind-deltrcov").addClass("this_hide");$(".jb-trpg-cov-dlcf-dc-dc-tgr").data("lk",0);_xhr_dlcvr=null;_f_Rst_Form()})}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_CrtCvr=function(img){var CR=new Cropper;var newImg=CR.Cropper_resizeWidthKeepHeightProrata(img,839);return $(newImg)};var _f_Lcl_SvFnl=function(img){var Fi={img:img,name:img.name,top:""};return Fi};
var _f_Comfy=function(i,w,h){try{if(KgbLib_CheckNullity(i))return;ir=_f_Gdf();if(!i.type.match("image/*"))return"__COV_BAD_FILE";if(!KgbLib_CheckNullity(w)&&!KgbLib_CheckNullity(h)){var filef=i.type.split("/").pop();if($.inArray(filef,ir.ft)===-1)return"__COV_BAD_TYPE";else if(i.size>ir.sz)return"__COV_BAD_SIZE";else{if(h<ir.h_min)return"__COV_BAD_DIMS_MIN";else if(h>ir.h_max)return"__COV_BAD_DIMS_MAX";if(w<ir.w_min)return"__COV_BAD_DIMS_MIN";else if(w>ir.w_max)return"__COV_BAD_DIMS_MAX"}}return true}catch(ex){Kxlib_DebugVars([ex,
ex.fileName,ex.lineNumber],true)}};var _f_OnErr=function(c){try{if(KgbLib_CheckNullity(c))return;c=c.toUpperCase();var m,t=0;switch(c){case "__COV_BAD_FILE":m=Kxlib_getDolphinsValue("ERR_COV_BAD_FILE");break;case "__COV_BAD_TYPE":m=Kxlib_getDolphinsValue("ERR_COV_BAD_TYPE");break;case "__COV_BAD_SIZE":m=Kxlib_getDolphinsValue("ERR_COV_BAD_SIZE");break;case "__COV_BAD_DIMS_MIN":m=Kxlib_getDolphinsValue("ERR_COV_BAD_DIMS_MIN");break;case "__COV_BAD_DIMS_MAX":m=Kxlib_getDolphinsValue("ERR_COV_BAD_DIMS_MAX");
break;default:m=Kxlib_getDolphinsValue("ERR_GEN_FAILED");t=7E3;break}if(typeof t==="undefined"||t===0)t=4E3;if($(".jb-cov-err-pan").hasClass("this_hide")){$(".jb-cov-err-inner").text(m);$(".jb-cov-err-pan").removeClass("this_hide");setTimeout(function(){var h=$(".jb-cov-err-pan-ctr").outerHeight();h=parseInt(h)*-1;$(".jb-cov-err-pan-ctr").animate({top:h},300,function(){$(".jb-cov-err-pan").addClass("this_hide");$(".jb-cov-err-inner").text("");$(".jb-cov-err-pan-ctr").removeAttr("style")})},t)}}catch(ex){Kxlib_DebugVars([ex,
ex.fileName,ex.lineNumber],true)}};var _f_CvrChg=function(files){try{if(!KgbLib_CheckNullity(_xhr_chcvr)|!KgbLib_CheckNullity(_xhr_dlcvr))return;if(KgbLib_CheckNullity(files)||files.lenght>1)return;$.each(files,function(k,v){if(!window.FileReader){var m=Kxlib_getDolphinsValue("ERR_BZR_OBSOLETE");alert(m);_f_Rst_Form(true);return}var gd=_f_Comfy(v);if(gd!==true){_f_OnErr(gd);_f_Rst_Form();return}var reader=new FileReader;reader.onload=function(){var img=new Image;img.src=reader.result;img.onload=function(){var gd=
_f_Comfy(v,img.width,img.height);if(gd!==true){_f_OnErr(gd);_f_Rst_Form();return}if($("#cov_img_buffer").length)$("#cov_img_buffer").remove();$("#a-h-t-top-tr-img-img").addClass("this_hide");var $img=_f_CrtCvr(this);_FnlImg=_f_Lcl_SvFnl($img);_FnlImg.name=v.name;gt.OnGoing=true;_f_TogMnChcs(true);$("#a-h-t-top-fade").hide();$img.draggable({axis:"y",drag:function(e,ui){var _height=$(e.target).height();var _lmt_down=260-_height;var _top=ui.position.top;var _tlh=0;if(_top>_tlh)ui.position.top=_tlh;else if(_top<
_lmt_down)ui.position.top=_lmt_down}});$img.addClass("cover_move");$img.addClass("ui-widget-content");$img.attr("id","cov_img_buffer");$("#a-h-t-top-tr-img-max").prepend($img)}};reader.onerror=function(){return};reader.readAsDataURL(v)})}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};this.GetAndDisplaySettings=function(f,s,a){_f_Srv_ChkStgs(f,s,a)};this.UpdatePostCount=function(a){var sn=a;var s="#tr-h-t-d-20",n=parseInt(sn),o=parseInt($(s).data("length"));if(o!==n)_f_HdrFmtCn(s,
n)};this.UpdateFolwrCount=function(a){var sn=a;var s="#tr-h-t-d-30",n=parseInt(sn),o=parseInt($(s).data("length"));if(o!==n)_f_HdrFmtCn(s,n)};this.CheckOperation=function(x){try{if(KgbLib_CheckNullity(x)|KgbLib_CheckNullity($(x).data("action")))return;var a=$(x).data("action");switch(a){case "ch_cover":_f_Vw_ChgCvr();break;case "start_del_cover":case "abort_del_cover":case "confirm_del_cover":_f_DelCvr(x);break;case "op_filters":break;case "abort":break;default:break}}catch(ex){Kxlib_DebugVars([ex,
ex.fileName,ex.lineNumber],true)}};var _Ax_SvCvr=Kxlib_GetAjaxRules("TRPG_SET_TCOV");var _f_Srv_SvCvr=function(ti,ds,s){if(KgbLib_CheckNullity(ti)|KgbLib_CheckNullity(ds)|KgbLib_CheckNullity(s))return;var onsuccess=function(d){try{if(!KgbLib_CheckNullity(d))d=JSON.parse(d);else return;if(!KgbLib_CheckNullity(d.err)){if(Kxlib_AjaxIsErrVolatile(d.err))switch(d.err){case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_USER_GONE":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_U_GONE":case "__ERR_VOL_U_G":Kxlib_HandleCurrUserGone();
break;case "__ERR_VOL_TRD_GONE":case "__ERR_VOL_TREND_GONE":return;break;case "__ERR_VOL_IMG_NOT_COMPLY":return;case "__ERR_VOL_FAILED":Kxlib_AJAX_HandleFailed();break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":Kxlib_AJAX_HandleDeny();break;default:break}return}else if(!KgbLib_CheckNullity(d.return)){var rds=[d.return];$(s).trigger("datasready",rds)}else if(KgbLib_CheckNullity(d.return))$(s).trigger("operended");else return}catch(e){return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);
return};var curl=document.URL;var toSend={"urqid":_Ax_SvCvr.urqid,"datas":{"ti":ti,"img":encodeURIComponent(ds.img),"in":ds.in,"ih":ds.ih,"iw":ds.iw,"it":ds.it,"cl":curl}};_xhr_chcvr=Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_SvCvr.url,wcrdtl:_Ax_SvCvr.wcrdtl})};var _Ax_DelCvr=Kxlib_GetAjaxRules("TRPG_DEL_TCOV");var _f_Srv_DelCvr=function(ti,s){if(KgbLib_CheckNullity(ti)|KgbLib_CheckNullity(s))return;var onsuccess=function(d){try{if(!KgbLib_CheckNullity(d))d=JSON.parse(d);
else return;if(!KgbLib_CheckNullity(d.err)){if(Kxlib_AjaxIsErrVolatile(d.err))switch(d.err){case "__ERR_VOL_U_GONE":case "__ERR_VOL_U_G":case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_USER_GONE":case "__ERR_VOL_CU_GONE":Kxlib_HandleCurrUserGone();break;case "__ERR_VOL_TRD_GONE":case "__ERR_VOL_TREND_GONE":break;case "__ERR_VOL_NOTGT":m=Kxlib_getDolphinsValue("ERR_COV_ONDEL_NOTGT");$(".jb-trpg-cov-dlcf-wait-mx").addClass("this_hide");$(".jb-trpg-cov-dlcf-dc-mx").addClass("this_hide");$(".jb-trpg-cov-dlcf-gninf").html(m);
$(".jb-trpg-cov-dlcf-gninf-mx").removeClass("this_hide");break;case "__ERR_VOL_FAILED":Kxlib_AJAX_HandleFailed();break;case "__ERR_VOL_DENY":case "__ERR_VOL_DENY_AKX":Kxlib_AJAX_HandleDeny();break;default:break}return}else if(!KgbLib_CheckNullity(d.return)){var rds=[d.return];$(s).trigger("datasready",rds)}else return}catch(ex){return}};var onerror=function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var curl=document.URL;var toSend={"urqid":_Ax_DelCvr.urqid,"datas":{"ti":ti,"cl":curl}};_xhr_dlcvr=Kx_XHR_Send(toSend,
null,null,onsuccess,onerror,{type:"post",url:_Ax_DelCvr.url,wcrdtl:_Ax_DelCvr.wcrdtl})};var _Ax_ChkStgs=Kxlib_GetAjaxRules("TRPG_GET_STGS");var _f_Srv_ChkStgs=function(f,s,ti){if(KgbLib_CheckNullity(f)|KgbLib_CheckNullity(s)|KgbLib_CheckNullity(ti))return;var onsuccess=function(d){try{if(!KgbLib_CheckNullity(d))d=JSON.parse(d);else return;if(!KgbLib_CheckNullity(d.err)){if(Kxlib_AjaxIsErrVolatile(d.err))switch(d.err){case "__ERR_VOL_ACC_GONE":case "__ERR_VOL_USER_GONE":case "__ERR_VOL_CU_GONE":case "__ERR_VOL_OWNER_GONE":Kxlib_HandleCurrUserGone();
break;case "__ERR_VOL_TRD_GONE":case "__ERR_VOL_TREND_GONE":return;case "__ERR_VOL_DENY":case "__ERR_VOL_DNY_AKX":case "__ERR_VOL_DENY_AKX":Kxlib_AJAX_HandleDeny();break;case "__ERR_VOL_FAILED":var Nty=new Notifyzing;Nty.FromUserAction("ERR_COM_AJAX_GDT_FAIL",null,true);return;default:return}return}else if(!KgbLib_CheckNullity(d.return)){f(s,d.return,true);_TrSrvStgs=d.return;gt.UpdHdrWNwStgs(d.return)}else return}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true);return}};var onerror=
function(a,b,c){Kxlib_AjaxGblOnErr(a,b);return};var toSend={"urqid":_Ax_ChkStgs.urqid,"datas":{"ti":ti}};Kx_XHR_Send(toSend,null,null,onsuccess,onerror,{type:"post",url:_Ax_ChkStgs.url,wcrdtl:_Ax_ChkStgs.wcrdtl})};var _f_HdrFmtCn=function(s,a){if(KgbLib_CheckNullity(s)|KgbLib_CheckNullity(a))return;try{var b,c,n;if(a/1E3===1||a/1E4===1||a/1E5===1||a/1E6===1||a/1E7===1||a/1E8===1||a/1E9===1)switch(a){case 1E3:case 1E4:case 1E5:c="k";n=a/1E3;break;case 1E6:case 1E7:case 1E8:c="M";n=a/1E6;break;case 1E9:c=
"G";n=1;break}else if(a>=0&&a<1E4){var f=parseFloat(a/1E3).toFixed(3).toString().replace("."," ").split(" ");if(f[0]!=="0")$(s).html(f);else $(s).html(parseInt(f[1]))}else if(a>=1E4&&a<1E6){var f=parseFloat(a/1E3).toFixed(3).toString().replace("."," ").split(" ");if(f[1]==="000")c="k";else b=" "+f[1];$(s).html(f[0])}else if(a>=1E6&&a<1E9){var f=parseFloat(a/1E6).toFixed(1).toString().replace("."," ").split(" ");c="M";if(f[1]==="0")b="";else b=","+f[1];$(s).html(f[0])}else if(a>=1E9&&a<1E12){var f=
parseFloat(a/1E9).toFixed(1).toString().replace("."," ").split(" ");c="G";if(f[1]==="0")b="";else b=","+f[1];$(s).html(f[0])}$(s).html(n);$(s).attr("title",a);$(s).data("length",a);if(typeof b!==undefined)$("<span/>",{class:"tr-h-t-d-xx1",text:b}).appendTo(s);if(typeof c!==undefined)$("<span/>",{class:"tr-h-t-d-xx2",text:c}).appendTo(s)}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_AbortCvr=function(){try{$("#cov_img_buffer").remove();$("#a-h-t-top-tr-img-img").removeClass("this_hide");
_f_TogMnChcs();$("#a-h-t-top-fade").show();_f_Rst_Form()}catch(ex){Kxlib_DebugVars([ex,ex.fileName,ex.lineNumber],true)}};var _f_TogMnChcs=function(show){if(KgbLib_CheckNullity(show)&&!show){$("#tr-h-t-d-1-ul").removeClass("this_hide");$("#chcov_choices").addClass("this_hide")}else{$("#tr-h-t-d-1-ul").addClass("this_hide");$("#chcov_choices").removeClass("this_hide")}};var _f_TogDescTleVisi=function(show){if(KgbLib_CheckNullity(show)&&!show){$(".jb-a-h-t-top-tr-tle").addClass("this_hide");$(".jb-a-h-t-top-tr-desc").addClass("this_hide")}else{$(".jb-a-h-t-top-tr-tle").removeClass("this_hide");
$(".jb-a-h-t-top-tr-desc").removeClass("this_hide")}};var _f_Vw_ChgCvr=function(){$("#tr_cover_action").click()};var st="";var sd="";$(".jb-tr-chcov-trg").hover(function(){if($(this).is(":hover"))$("#tr-chcov-trg-bis").addClass("hover");else $("#tr-chcov-trg-bis").removeClass("hover")},function(){$("#tr-chcov-trg-bis").removeClass("hover")});$(".jb-tr-chcov-trg").off("change").change(function(e){var files=this.files;_f_CvrChg(files)});$(".jb-chcvr-ch-tgr[data-action='confirm_save_cover']").click(function(e){Kxlib_PreventDefault(e);
Kxlib_StopPropagation(e);_f_SvCvr()});$(".jb-chcvr-ch-tgr[data-action='abort_save_cover']").click(function(e){Kxlib_PreventDefault(e);_f_AbortCvr()});$(".jb-trpg-cov-dlcf-dc-dc-tgr").click(function(e){Kxlib_PreventDefault(e);Kxlib_StopPropagation(e);gt.CheckOperation(this)})}var _obj=new TrendHeader;function TrHeader_Receiver(){this.Routeur=function(th){if(KgbLib_CheckNullity(th))return;_obj.CheckOperation(th)}};
