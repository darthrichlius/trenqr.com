$().ready(function(){var scrollingRightBar=$("#pfl_rightbar");var scrollingLeftBar=$("#pfl_leftbar");var limiter;var scpn=$("#page_section").data("section");if(scpn!=="")switch(scpn){case "profile":$("#pfl_leftlink_profile").trigger("click");break;case "account":$("#pfl_leftlink_account").trigger("click");break;case "security":$("#pfl_leftlink_security").trigger("click");break;case "about":$("#pfl_leftlink_about").trigger("click");break;default:$("#pfl_leftlink_profile").trigger("click");break}else $("#pfl_leftlink_profile").trigger("click");
$(window).scroll(function(){if($("#pfl_rightbar").height()<$("#pfl_leftbar").height())limiter=$("#pfl_leftbar").height();else limiter=$("#pfl_rightbar").height();var displayedPanel=displayedPanelOverseer();var scrollLimit=panelHeightOverseer(displayedPanel)-limiter+25;var effectiveScroll=$(window).scrollTop();if(scrollLimit<effectiveScroll)effectiveScroll=scrollLimit;scrollingRightBar.stop().animate({"marginTop":effectiveScroll},"slow");setTimeout(function(){scrollingLeftBar.stop().animate({"marginTop":effectiveScroll},
"slow")},100)});ajaxIsEmailConfirmed()});
function displayedPanelOverseer(){var isContentProfileDisplayed=$("#pfl_content_profile").css("display");var isContentAccountDisplayed=$("#pfl_content_account").css("display");var isContentSecurityDisplayed=$("#pfl_content_security").css("display");var isContentAppearanceDisplayed=$("#pfl_content_appearance").css("display");var isContentBlockedAccsDisplayed=$("#pfl_content_blockedaccs").css("display");var isContentAboutDisplayed=$("#pfl_content_about").css("display");var isFormProfileDisplayed=$("#pfl_form_profile_div").css("display");
var isFormAccountDisplayed=$("#pfl_form_account_div").css("display");var isFormSecurityDisplayed=$("#pfl_form_security_div").css("display");var isFormDeleteDisplayed=$("#pfl_delete_account_div").css("display");if(isContentProfileDisplayed==="block")return 1;else if(isContentAccountDisplayed==="block")return 2;else if(isContentSecurityDisplayed==="block")return 3;else if(isContentAppearanceDisplayed==="block")return 4;else if(isContentBlockedAccsDisplayed==="block")return 5;else if(isContentAboutDisplayed===
"block")return 6;else if(isFormProfileDisplayed==="block")return 7;else if(isFormAccountDisplayed==="block")return 8;else if(isFormSecurityDisplayed==="block")return 9;else if(isFormDeleteDisplayed==="block")return 10}
function panelHeightOverseer(displayedPanel){var maxHeight;switch(displayedPanel){case 1:maxHeight=$("#pfl_content_profile").height();break;case 2:maxHeight=$("#pfl_content_account").height();break;case 3:maxHeight=$("#pfl_content_security").height();break;case 4:maxHeight=$("#pfl_content_appearance").height();break;case 5:maxHeight=$("#pfl_content_blockedaccs").height();case 6:maxHeight=$("#pfl_content_about").height();break;case 7:maxHeight=$("#pfl_form_profile_div").height();break;case 8:maxHeight=
$("#pfl_form_account_div").height();break;case 9:maxHeight=$("#pfl_form_security_div").height();break;case 10:maxHeight=$("#pfl_delete_account_div").height();break}return maxHeight}var pal=0;var aal=0;var sal=0;
function clickProfile(e){$("#pfl_content_profile").css("display","none");$("#pfl_content_account").css("display","none");$("#pfl_content_security").css("display","none");$("#pfl_content_appearance").css("display","none");$("#pfl_content_blockedaccs").css("display","none");$("#pfl_form_profile_div").css("display","block");$("#pfl_form_account_div").css("display","none");$("#pfl_form_security_div").css("display","none");$("#pfl_delete_account_div").css("display","none");$("#pfl_content_about").css("display",
"none");$(".pfl_middle_content").css("border-left-color","#FFA500");$("#pfl_leftlink_profile").addClass("pfl_leftlink_current");$("#pfl_leftlink_account").removeClass("pfl_leftlink_current");$("#pfl_leftlink_security").removeClass("pfl_leftlink_current");$("#pfl_leftlink_appearance").removeClass("pfl_leftlink_current");$("#pfl_leftlink_blockedaccs").removeClass("pfl_leftlink_current");$("#pfl_leftlink_about").removeClass("pfl_leftlink_current");if(pal===0){profileLoader();pal++}displayedPanel=7;e.preventDefault()}
function clickAccount(e){$("#pfl_content_profile").css("display","none");$("#pfl_content_account").css("display","none");$("#pfl_content_security").css("display","none");$("#pfl_content_appearance").css("display","none");$("#pfl_content_blockedaccs").css("display","none");$("#pfl_form_profile_div").css("display","none");$("#pfl_form_account_div").css("display","block");$("#pfl_form_security_div").css("display","none");$("#pfl_delete_account_div").css("display","none");$("#pfl_content_about").css("display",
"none");$(".pfl_middle_content").css("border-left-color","#0BEE2F");$("#pfl_leftlink_profile").removeClass("pfl_leftlink_current");$("#pfl_leftlink_account").addClass("pfl_leftlink_current");$("#pfl_leftlink_security").removeClass("pfl_leftlink_current");$("#pfl_leftlink_appearance").removeClass("pfl_leftlink_current");$("#pfl_leftlink_blockedaccs").removeClass("pfl_leftlink_current");$("#pfl_leftlink_about").removeClass("pfl_leftlink_current");if(aal===0){accountLoader();aal++}displayedPanel=8;e.preventDefault()}
function clickSecurity(e){$("#pfl_content_profile").css("display","none");$("#pfl_content_account").css("display","none");$("#pfl_content_security").css("display","none");$("#pfl_content_appearance").css("display","none");$("#pfl_content_blockedaccs").css("display","none");$("#pfl_form_profile_div").css("display","none");$("#pfl_form_account_div").css("display","none");$("#pfl_form_security_div").css("display","block");$("#pfl_delete_account_div").css("display","none");$("#pfl_content_about").css("display",
"none");$(".pfl_middle_content").css("border-left-color","#9188FF");$("#pfl_leftlink_profile").removeClass("pfl_leftlink_current");$("#pfl_leftlink_account").removeClass("pfl_leftlink_current");$("#pfl_leftlink_security").addClass("pfl_leftlink_current");$("#pfl_leftlink_appearance").removeClass("pfl_leftlink_current");$("#pfl_leftlink_blockedaccs").removeClass("pfl_leftlink_current");$("#pfl_leftlink_about").removeClass("pfl_leftlink_current");if(sal===0){securityLoader();sal++}displayedPanel=9;
e.preventDefault()}
function clickAppearance(e){$("#pfl_content_profile").css("display","none");$("#pfl_content_account").css("display","none");$("#pfl_content_security").css("display","none");$("#pfl_content_appearance").css("display","block");$("#pfl_content_blockedaccs").css("display","none");$("#pfl_form_profile_div").css("display","none");$("#pfl_form_account_div").css("display","none");$("#pfl_form_security_div").css("display","none");$("#pfl_delete_account_div").css("display","none");$("#pfl_content_about").css("display","none");
$(".pfl_middle_content").css("border-left-color","#E2E24F");$("#pfl_leftlink_profile").removeClass("pfl_leftlink_current");$("#pfl_leftlink_account").removeClass("pfl_leftlink_current");$("#pfl_leftlink_security").removeClass("pfl_leftlink_current");$("#pfl_leftlink_appearance").addClass("pfl_leftlink_current");$("#pfl_leftlink_blockedaccs").removeClass("pfl_leftlink_current");$("#pfl_leftlink_about").removeClass("pfl_leftlink_current");displayedPanel=4;e.preventDefault()}
function clickBlockedAccs(e){$("#pfl_content_profile").css("display","none");$("#pfl_content_account").css("display","none");$("#pfl_content_security").css("display","none");$("#pfl_content_appearance").css("display","none");$("#pfl_content_blockedaccs").css("display","block");$("#pfl_form_profile_div").css("display","none");$("#pfl_form_account_div").css("display","none");$("#pfl_form_security_div").css("display","none");$("#pfl_delete_account_div").css("display","none");$("#pfl_content_about").css("display",
"none");$(".pfl_middle_content").css("border-left-color","#E44F4F");$("#pfl_leftlink_profile").removeClass("pfl_leftlink_current");$("#pfl_leftlink_account").removeClass("pfl_leftlink_current");$("#pfl_leftlink_security").removeClass("pfl_leftlink_current");$("#pfl_leftlink_appearance").removeClass("pfl_leftlink_current");$("#pfl_leftlink_blockedaccs").addClass("pfl_leftlink_current");$("#pfl_leftlink_about").removeClass("pfl_leftlink_current");displayedPanel=5;if($("#bloacc_listzone").children().length===
0)blockedAccountsFiller(0,20);e.preventDefault()}
function clickAbout(e){$("#pfl_content_profile").css("display","none");$("#pfl_content_account").css("display","none");$("#pfl_content_security").css("display","none");$("#pfl_content_appearance").css("display","none");$("#pfl_content_blockedaccs").css("display","none");$("#pfl_form_profile_div").css("display","none");$("#pfl_form_account_div").css("display","none");$("#pfl_form_security_div").css("display","none");$("#pfl_delete_account_div").css("display","none");$("#pfl_content_about").css("display",
"block");$(".pfl_middle_content").css("border-left-color","#B3B3B3");$("#pfl_leftlink_profile").removeClass("pfl_leftlink_current");$("#pfl_leftlink_account").removeClass("pfl_leftlink_current");$("#pfl_leftlink_security").removeClass("pfl_leftlink_current");$("#pfl_leftlink_appearance").removeClass("pfl_leftlink_current");$("#pfl_leftlink_blockedaccs").removeClass("pfl_leftlink_current");$("#pfl_leftlink_about").addClass("pfl_leftlink_current");displayedPanel=6;e.preventDefault()}
$("#pfl_delete_backlink a").click(function(e){clickSecurity(e);e.preventDefault()});var infoProfile=Kxlib_getDolphinsValue("p_pfl_msg_profile");var infoAccount=Kxlib_getDolphinsValue("p_pfl_msg_account");var infoSecurity=Kxlib_getDolphinsValue("p_pfl_msg_security");var infoAppearance=Kxlib_getDolphinsValue("p_pfl_msg_appearance");var infoBlockedAccs=Kxlib_getDolphinsValue("p_pfl_msg_bloacc");var infoAbout=Kxlib_getDolphinsValue("p_pfl_msg_about");var infoPflCity=Kxlib_getDolphinsValue("p_pfl_msg_pflcity");
var infoAccPseudo=Kxlib_getDolphinsValue("p_pfl_msg_accpsd");var infoAccEmail=Kxlib_getDolphinsValue("p_pfl_msg_accemail");var infoAccLang=Kxlib_getDolphinsValue("p_pfl_msg_acclang");var infoSecuPasswd=Kxlib_getDolphinsValue("p_pfl_msg_secupw");var lastInfoDisplayed=null;function pfl_infoDisplay(info){if(lastInfoDisplayed!==info){$("#pfl_infomsg").fadeOut(500,function(){$("#pfl_infomsg").html(info);$("#pfl_infomsg").fadeIn(500)});lastInfoDisplayed=info}else stop()}
function setPending(inputForm){switch(inputForm){case "profile":$("#pfl_input_fullname").data("pfl","pending");$("#pfl_birthday_date_group").data("pfl","pending");$("#pfl_input_city").data("pfl","pending");break;case "account":$("#pfl_input_nickname").data("acc","pending");$("#pfl_input_email").data("acc","pending");$("#pfl_input_socialarea").data("acc","pending");$("#pfl_input_oldpw").data("acc","pending");$("#pfl_input_newpw").data("acc","pending");$("#pfl_input_newpwconf").data("acc","pending");
break;case "security":$("#pfl_hlock_start_group").data("secu","pending");$("#pfl_hlock_end_group").data("secu","pending");$("#pfl_dlock_start_group").data("secu","pending");$("#pfl_dlock_end_group").data("secu","pending");break}}
function setActive(inputForm){switch(inputForm){case "profile":$("#pfl_input_fullname").data("pfl","ulock");$("#pfl_birthday_date_group").data("pfl","ulock");$("#pfl_input_city").data("pfl","ulock");pflProfileGeneralCheck();break;case "account":$("#pfl_input_nickname").data("acc","ulock");$("#pfl_input_email").data("acc","ulock");$("#pfl_input_socialarea").data("acc","ulock");$("#pfl_input_oldpw").data("acc","ulock");$("#pfl_input_newpw").data("acc","ulock");$("#pfl_input_newpwconf").data("acc","ulock");
pflAccountGeneralCheck();break;case "security":$("#pfl_hlock_start_group").data("secu","ulock");$("#pfl_hlock_end_group").data("secu","ulock");$("#pfl_dlock_start_group").data("secu","ulock");$("#pfl_dlock_end_group").data("secu","ulock");pflSecurityGeneralCheck();break}}$("#pfl_leftlink_profile").click(function(e){pfl_infoDisplay(infoProfile);clickProfile(e);setActive("profile");setPending("account");setPending("security")});
$("#pfl_leftlink_account").click(function(e){pfl_infoDisplay(infoAccount);clickAccount(e);setActive("account");setPending("profile");setPending("security")});$("#pfl_leftlink_security").click(function(e){pfl_infoDisplay(infoSecurity);clickSecurity(e);setActive("security");setPending("account");setPending("profile")});$("#pfl_leftlink_appearance").click(function(e){pfl_infoDisplay(infoAppearance);clickAppearance(e)});
$("#pfl_leftlink_blockedaccs").click(function(e){pfl_infoDisplay(infoBlockedAccs);clickBlockedAccs(e);setPending("account");setPending("profile");setPending("security");profileErrorChecker();AccountErrorChecker();securityErrorChecker()});$("#pfl_leftlink_about").click(function(e){pfl_infoDisplay(infoAbout);clickAbout(e)});$("#pfl_input_city").focus(function(){pfl_infoDisplay(infoPflCity)});$("#pfl_input_nickname").focus(function(){pfl_infoDisplay(infoAccPseudo)});$("#pfl_input_email").focus(function(){pfl_infoDisplay(infoAccEmail)});
$("#pfl_lang").focus(function(){pfl_infoDisplay(infoAccLang)});$("#pfl_input_oldpw, #pfl_input_newpw, #pfl_input_newpwconf").focus(function(){pfl_infoDisplay(infoSecuPasswd)});$("#pfl_form_profile_div").click(function(e){if($(e.target).prop("id")!=="pfl_input_city")pfl_infoDisplay(infoProfile)});$("#pfl_form_account_div").click(function(e){var id=$(e.target).prop("id");if(id!=="pfl_input_nickname"&&id!=="pfl_input_email"&&id!=="pfl_lang"&&id!=="pfl_input_oldpw"&&id!=="pfl_input_newpw"&&id!=="pfl_input_newpwconf")pfl_infoDisplay(infoAccount)});
var hintProfile=Kxlib_getDolphinsValue("p_pfl_hint_profile");var hintAccount=Kxlib_getDolphinsValue("p_pfl_hint_account");var hintSecurity=Kxlib_getDolphinsValue("p_pfl_hint_security");var hintAppearance=Kxlib_getDolphinsValue("p_pfl_hint_appearance");var hintBlockedAccs=Kxlib_getDolphinsValue("p_pfl_hint_bloacc");var hintAbout=Kxlib_getDolphinsValue("p_pfl_hint_about");
function pfl_hintDisplay(hint){$("#pfl_left_hint").stop(true);$("#pfl_left_hint").html(hint);$("#pfl_left_hint").fadeToggle(250)}$("#pfl_leftlink_profile").hover(function(){setTimeout(function(){pfl_hintDisplay(hintProfile)},50)});$("#pfl_leftlink_account").hover(function(){setTimeout(function(){pfl_hintDisplay(hintAccount)},50)});$("#pfl_leftlink_security").hover(function(){setTimeout(function(){pfl_hintDisplay(hintSecurity)},50)});
$("#pfl_leftlink_appearance").hover(function(){setTimeout(function(){pfl_hintDisplay(hintAppearance)},50)});$("#pfl_leftlink_blockedaccs").hover(function(){setTimeout(function(){pfl_hintDisplay(hintBlockedAccs)},50)});$("#pfl_leftlink_about").hover(function(){setTimeout(function(){pfl_hintDisplay(hintAbout)},50)});
$("#pfl_delete_account a").click(function(){$("#pfl_form_security_div").css("display","none");$("#pfl_delete_account_div").css("display","block");$("input[name=pfl_deactivation_reason]").prop("checked",false);deleteDetails();displayedPanel=10});$("#pfl_input_newpw").focusin(function(){$("#pfl_passwd_str").css("border-color","#bbb")});$("#pfl_input_newpw").focusout(function(){$("#pfl_passwd_str").css("border-color","#dfdfdf")});$("#pfl_input_newpw").keyup(function(){pflPasswdBar($("#pfl_input_newpw"))});
$("#pfl_trial_warning #pfl_trialswitch_btn").click(function(e){$(".pfl_trialswitch_form_div").slideDown();$(this).slideUp();e.preventDefault()});
$(".clear_input_wrapper .clear_input_link").click(function(e){var target=$(e.target).closest("form");target.find("input[type=text], input[type=password]").val("");target.find("select").prop("selectedIndex",0);target.find("input[type=checkbox]").prop("checked",false);switch(target.attr("id")){case "pfl_form_profile":pflProfileGeneralCheck();break;case "pfl_form_account_classic":pflNicknameCheck($("#pfl_input_nickname").val(),true);pflEmailCheck($("#pfl_input_email").val(),true);pflSocialAreaCheck($("#pfl_input_socialarea").val());
break;case "pfl_form_account_passwd":pflOldPasswdCheck($("#pfl_input_oldpw").val());pflNewPasswdCheck($("#pfl_input_newpw").val());PflNewPasswdConfCheck($("#pfl_input_newpwconf").val());break;case "pfl_form_security":break;case "pfl_form_security_locks":break}e.preventDefault()});
$(".select_deselect_wrapper .select_deselect").click(function(e){if($(e.target).prop("id")==="secu_select"){var target=$(e.target).closest("form");target.find("input[type=checkbox]").prop("checked",true)}else if($(e.target).prop("id")==="secu_deselect"){var target=$(e.target).closest("form");target.find("input[type=checkbox]").prop("checked",false)}});
