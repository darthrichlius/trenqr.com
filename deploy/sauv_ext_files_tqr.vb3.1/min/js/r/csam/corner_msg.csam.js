function CornerMessenger(){this.defStatus="close";this.currentStatus;this.defEnbClose=true;this.ckeckser_url="http://127.0.0.1/korgb/ajax_test.php";this.ckeckser_uq="check_and_get_msg";this.BuildPlatMessage=function(argv){var datas=argv;var cn=Kxlib_ObjectChild_Count(argv);if(cn===1){Kxlib_DebugVars(["Debut construction du PlatMsg SOLO"]);var first;$.each(datas,function(k,v){first=v});Kxlib_DebugVars(["LE MESSAGE = > "+first.body]);$("#c_n_text").html(first.body);this.EnableChoices(first.choices);
var en;if(KgbLib_CheckNullity(first.ena_close)){alert("Close ? "+en);en=this.defEnbClose}else en=first.ena_close==="y"?true:false;this.EnableCloseCross(en);$("#corner_msg_maximus").removeClass("this_hide")}else if(cn>1)Kxlib_DebugVars(["Debut construction du PlatMsg MULTI"])};this.CheckServer=function(){var th=this;var onsuccess=function(datas){if(!KgbLib_CheckNullity(datas.err))alert(datas.err);datas=JSON.parse(datas);var str=JSON.stringify(datas);datas=JSON.parse(str);if(KgbLib_CheckNullity(datas.length))th.BuildPlatMessage(datas);
else Kxlib_DebugVars(["Pas de nouveaux messages !"])};var onerror=function(a,b,c){alert("AJAX ERR : "+th.ckeckser_uq)};var toSend={"urqid":th.ckeckser_uq};Kx_XHR_Send(toSend,"post",this.ckeckser_url,onerror,onsuccess)};this.HandleAnswerCase=function(btn){if(KgbLib_CheckNullity(btn))return;var val=$(btn).data("val");switch(val){case "getit":alert("getit");break;case "o":alert("Ok");break;case "dsma":alert("Don't Show Me Again");break;case "y":alert("Yes");break;case "n":alert("No");break}$("#corner_msg_maximus").addClass("this_hide")};
this.EnableCloseCross=function(argv){if(argv)$("#c_n_header_x").removeClass("this_hide");else $("#c_n_header_x").addClass("this_hide")};this.EnableChoices=function(argv){$.each(argv,function(k,v){switch(v){case "getit":$("#c_n_bot_getit").removeClass("this_hide");break;case "o":$("#c_n_bot_ok").removeClass("this_hide");break;case "dsma":$("#c_n_bot_dsma").removeClass("this_hide");break;case "y":$("#c_n_bot_ya").removeClass("this_hide");break;case "n":$("#c_n_bot_na").removeClass("this_hide");break}})};
this.disableChoices=function(argv){$.each(argv,function(k,v){})};this.OpenClose=function(isOpen){if(KgbLib_CheckNullity(isOpen))return;this.currentStatus=$("#corner_msg_maximus").hasClass("this_hide")?"close":"open";if(isOpen&&this.currentStatus==="close")$("#corner_msg_maximus").removeClass("this_hide");else if(!isOpen&&this.currentStatus==="open")$("#corner_msg_maximus").addClass("this_hide");else;};this.Init=function(isOpen){var st=this.defStatus;if(!KgbLib_CheckNullity(isOpen)){st=isOpen?"open":
"close";this.defStatus=st}switch(st){case "open":this.OpenClose(true);break;case "close":this.OpenClose(false);break;defaukt:this.OpenClose(false);break}}}var _obj=new CornerMessenger;_obj.Init();$("#c_n_header_x").click(function(e){e.preventDefault();_obj.OpenClose(false)});$(".c_n_bot_btn").click(function(e){e.preventDefault();_obj.HandleAnswerCase(e.target)});setInterval(function(){_obj.CheckServer()},1E4);