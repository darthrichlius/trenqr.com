function id_checkDropdown(inputLogin){$("#dd_error").html("");var emailReg=/^[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*@[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*([.][A-Za-z0-9]{2,4})+$/;var pseudoReg=/^[a-zA-Z0-9-\u00c0\u00c1\u00c2\u00c3\u00c4\u00c5\u00e0\u00e1\u00e2\u00e3\u00e4\u00e5\u00d2\u00d3\u00d4\u00d5\u00d6\u00d8\u00f2\u00f3\u00f4\u00f5\u00f6\u00f8\u00c8\u00c9\u00ca\u00cb\u00e8\u00e9\u00ea\u00eb\u00c7\u00e7\u00cc\u00cd\u00ce\u00cf\u00ec\u00ed\u00ee\u00ef\u00d9\u00da\u00db\u00dc\u00f9\u00fa\u00fb\u00fc\u00ff\u00d1\u00f1]{2,20}$/;
if(!emailReg.test(inputLogin))if(!pseudoReg.test(inputLogin)){$("#dd_error").html("Identifiant invalide");$("#dd_login").addClass("form_error_border")}}$("#dd_login").blur(function(){var inputMail=$(this).val();$("#dd_login").removeClass("form_error_border");if(inputMail!=="")id_checkDropdown(inputMail);else $("#dd_error").html("")});$("#dd_passwd").blur(function(){var inputPasswd=$(this).val();$("#dd_passwd").removeClass("form_error_border");if(inputPasswd==="")$("#dd_error").html("")});
var dd_validation_ok=true;
function dd_login_valid(inputLogin){var regEmail=/^[a-zA-Z0-9-]{1,15}([.][a-zA-Z0-9-]{1,15})?@[a-zA-Z0-9-]{1,15}[.][a-z]{2,4}([.][a-z]{2})?$/;var regNickname=/^[a-zA-Z0-9-\u00c0\u00c1\u00c2\u00c3\u00c4\u00c5\u00e0\u00e1\u00e2\u00e3\u00e4\u00e5\u00d2\u00d3\u00d4\u00d5\u00d6\u00d8\u00f2\u00f3\u00f4\u00f5\u00f6\u00f8\u00c8\u00c9\u00ca\u00cb\u00e8\u00e9\u00ea\u00eb\u00c7\u00e7\u00cc\u00cd\u00ce\u00cf\u00ec\u00ed\u00ee\u00ef\u00d9\u00da\u00db\u00dc\u00f9\u00fa\u00fb\u00fc\u00ff\u00d1\u00f1]{2,20}$/;if(!regEmail.test(inputLogin))if(!regNickname.test(inputLogin)){dd_validation_ok=
false;$("#dd_login").addClass("form_error_border")}}function dd_password_valid(inputPasswd){var reg=/^[^<=>\\;/]{4,15}$/;if(!reg.test(inputPasswd)){dd_validation_ok=false;$("#dd_passwd").addClass("form_error_border")}}$("#dd_login").blur(function(){readyForInfoCheck()});$("#dd_passwd").blur(function(){readyForInfoCheck()});
function readyForInfoCheck(){var exs=false;var cred=false;var l=$("#dd_login").val();var p=$("#dd_passwd").val();if(l!==""&&p!==""){$("#home_moreInfoRequired").remove();exs=ajaxHomepageAccountExists(l);cred=ajaxGCChecker();if(exs===true&&cred===true){var sg=ajaxSupergroupChecker();var tc=ajaxThirdCriteriaChecker();switch(sg){case 0:$("#home_sub_special_error").show();moreInfoLoginOverlay();break;case 1:$("#home_sub_birthday").show();$("#home_sub_specialgroup").show();moreInfoLoginOverlay();break;
case 2:$("#home_sub_special_error").show();moreInfoLoginOverlay();break;case 3:case false:default:if(tc===true){$("#home_sub_birthday").show();moreInfoLoginOverlay()}break}}if($("#home_sub_special_error").css("display")==="block")$("#home_overlay_ok").click(function(){window.location.reload()});else $("#home_overlay_ok").bind("click",function(e){homeOverlayButton(e)})}}$("#dropdown_login").bind("keyup keypress",function(e){var code=e.keyCode||e.which;if(code===13){e.preventDefault();return false}});
$("#dropdown_login_submit").click(function(e){var exs=false;e.preventDefault();var finalInputLogin=$("#dd_login").val();var finalInputPasswd=$("#dd_passwd").val();dd_validation_ok=true;dd_login_valid(finalInputLogin);dd_password_valid(finalInputPasswd);exs=ajaxHomepageAccountExists(finalInputLogin);var pglk=ajaxPreregLoginCheck(e);if(pglk!==true)var dd_ajax_locker=ajaxLoginChecker();if(dd_validation_ok===true&&dd_ajax_locker===true&&exs===true){$("#dd_error").html("");$("#dropdown_login").submit()}});
$(document).on("click","#delcancel_keep",function(){location.href="http://www.trenqr.com"});$(document).on("click","#delcancel_abort",function(){ajaxHomepageTodeleteCancel($("#dd_login").val())});