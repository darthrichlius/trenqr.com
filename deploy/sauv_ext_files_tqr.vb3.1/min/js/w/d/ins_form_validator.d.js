var regFullname=/^[a-zA-Z- \u00c0\u00c1\u00c2\u00c3\u00c4\u00c5\u00e0\u00e1\u00e2\u00e3\u00e4\u00e5\u00d2\u00d3\u00d4\u00d5\u00d6\u00d8\u00f2\u00f3\u00f4\u00f5\u00f6\u00f8\u00c8\u00c9\u00ca\u00cb\u00e8\u00e9\u00ea\u00eb\u00c7\u00e7\u00cc\u00cd\u00ce\u00cf\u00ec\u00ed\u00ee\u00ef\u00d9\u00da\u00db\u00dc\u00f9\u00fa\u00fb\u00fc\u00ff\u00d1\u00f1]{2,40}$/;var regNickname=/^[a-zA-Z0-9-_\u00c0\u00c1\u00c2\u00c3\u00c4\u00c5\u00e0\u00e1\u00e2\u00e3\u00e4\u00e5\u00d2\u00d3\u00d4\u00d5\u00d6\u00d8\u00f2\u00f3\u00f4\u00f5\u00f6\u00f8\u00c8\u00c9\u00ca\u00cb\u00e8\u00e9\u00ea\u00eb\u00c7\u00e7\u00cc\u00cd\u00ce\u00cf\u00ec\u00ed\u00ee\u00ef\u00d9\u00da\u00db\u00dc\u00f9\u00fa\u00fb\u00fc\u00ff\u00d1\u00f1]{2,20}$/;
var regMail=/^[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*@[a-zA-Z0-9-_]{1,15}([.][a-zA-Z0-9-_]{1,15})*([.][A-Za-z0-9]{2,4})+$/;var regCity=/^[a-zA-Z-.`, \u00c0\u00c1\u00c2\u00c3\u00c4\u00c5\u00e0\u00e1\u00e2\u00e3\u00e4\u00e5\u00d2\u00d3\u00d4\u00d5\u00d6\u00d8\u00f2\u00f3\u00f4\u00f5\u00f6\u00f8\u00c8\u00c9\u00ca\u00cb\u00e8\u00e9\u00ea\u00eb\u00c7\u00e7\u00cc\u00cd\u00ce\u00cf\u00ec\u00ed\u00ee\u00ef\u00d9\u00da\u00db\u00dc\u00f9\u00fa\u00fb\u00fc\u00ff\u00d1\u00f1]{0,50}$/;var regPasswd=/^(?=(.*\d))(?=.*[a-zA-Z])(?=.*[!.?+*_~\u00b5\u00a3^\u00a8\u00b0\(\)/\\\[\]\-@#$%])[^:;="']{6,20}$/;
var regPasswdIncorrect=/^[^"'=]{1,5}$/;var regPasswdWeak=/^(?=(.*\d))(?=.*[a-zA-Z])(?=.*[!.?+*_~\u00b5\u00a3^\u00a8\u00b0\(\)/\\\[\]\-@#$%])[^:;="']{6,7}$/;var regPasswdMed=/^(?=(.*\d))(?=.*[a-zA-Z])(?=.*[!.?+*_~\u00b5\u00a3^\u00a8\u00b0\(\)/\\\[\]\-@#$%])[^:;="']{6}(((?=(.*[a-zA-Z]))[a-zA-Z]{2})|(([^;:="'])?(?=(.*[0-9!.?+*_~\u00b5\u00a3^\u00a8\u00b0\(\)/\\\[\]\-@#$%]))[^:;="']))$/;var regPasswdStrong=/^(?=(.*\d))(?=.*[a-zA-Z])(?=.*[!.?+*_~\u00b5\u00a3^\u00a8\u00b0\(\)/\\\[\]\-@#$%])[^:;="']{6}(([^;:="'])?(?=(.*[0-9!.?+*_~\u00b5\u00a3^\u00a8\u00b0\(\)/\\\[\]\-@#$%]))[^:;="']{3,14})$/;
var regMagicDate=/^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;var ins_nickname_errtype;var ins_city_errtype;var ins_bd_errtype;var ins_nickname_suggestion=null;
function insErrorBox(){if($("#ins_error_msg").html()==="")$("#ins_right_bottom").css("display","none");else $("#ins_right_bottom").css("display","block")}function insErrorMsg(msg){$("#ins_error_msg").html(msg)}insCityErrtype=new String;
function mainLockCheck(){var lockTable=$(".ins_error_lock");var errorArray=new Array;$.each(lockTable,function(h,i){if($(i).data("su")==="lock")errorArray.push(i)});var errorCount=errorArray.length;if(errorCount>1){var msg=Kxlib_getDolphinsValue("p_ins_err_multifield");insErrorMsg(msg)}else if(errorCount===0){insErrorMsg("");insErrorBox()}else if(errorCount===1){var arg=errorArray[0];var argSelector=$(arg).attr("id");switch(argSelector){case "ins_input_fullname":var msg=Kxlib_getDolphinsValue("p_ins_err_fnbadchars");
insErrorMsg(msg);break;case "ins_input_city":switch(insCityErrtype){case "empty":var msg=Kxlib_getDolphinsValue("p_ins_err_cityempty");insErrorMsg(msg);break;case "badchars":var msg=Kxlib_getDolphinsValue("p_ins_err_citybadchars");insErrorMsg(msg);break;case "unknown":var msg=Kxlib_getDolphinsValue("p_ins_err_cityunknown");insErrorMsg(msg);break;case "select":var msg=Kxlib_getDolphinsValue("p_ins_err_cityselect");insErrorMsg(msg);break;default:var msg=Kxlib_getDolphinsValue("p_ins_err_citydefault");
insErrorMsg(msg);break}break;case "ins_input_nickname":if(ins_nickname_errtype===0){var msg=Kxlib_getDolphinsValue("p_ins_err_psdbadchars");insErrorMsg(msg)}else if(ins_nickname_errtype===1)if(ins_nickname_suggestion===null){var msg=Kxlib_getDolphinsValue("p_ins_err_psdtaken");insErrorMsg(msg)}else insErrorMsg(ins_nickname_suggestion);break;case "ins_input_mail":var msg=Kxlib_getDolphinsValue("p_ins_err_emailbadchars");insErrorMsg(msg);break;case "ins_input_passwd":var msg=Kxlib_getDolphinsValue("p_ins_err_pwbadchars");
insErrorMsg(msg);break;case "ins_input_mail_confirmation":var msg=Kxlib_getDolphinsValue("p_ins_err_emailconf");insErrorMsg(msg);break;case "ins-fld-pwd-cnf":var msg=Kxlib_getDolphinsValue("p_ins_err_pwconf");insErrorMsg(msg);break;case "ins_birthday_wrapper":switch(ins_bd_errtype){case "tooyoung":var msg=Kxlib_getDolphinsValue("p_ins_err_tooyoung");insErrorMsg(msg);break;case "invaliddate":var msg=Kxlib_getDolphinsValue("p_ins_err_bdbaddate");insErrorMsg(msg);break}break;case "ins_gender_wrapper":var msg=
Kxlib_getDolphinsValue("p_ins_err_genderselect");insErrorMsg(msg);break;case "ins_group_cgu":var msg=Kxlib_getDolphinsValue("p_ins_err_cgu");insErrorMsg(msg);break}insErrorBox()}}
function ins_fullname_check(inputFullname){$("#ins_error_msg").html("");if(!regFullname.test(inputFullname)){$("#ins_input_fullname").data("su","lock");$("#ins_input_fullname").addClass("error_border");mainLockCheck()}else{$("#ins_input_fullname").data("su","ulock");$("#ins_input_fullname").removeClass("error_border");mainLockCheck()}}
function ins_city_check(inputCity){$("#ins_input_city").removeClass("error_border");$("#ins_input_city").data("su","ulock");$("#ins_error_msg").html("");mainLockCheck();if(inputCity===""){$("#ins_input_city").data("su","lock");$("#ins_input_city").addClass("error_border");insCityErrtype="empty";ins_validation_ok=false;mainLockCheck()}else if($("#ins_input_city").data("cc")==="-1"&&$("#ins_input_city").data("temp")!=="allowed"&&$("#ins_input_city").data("check")==="true"){$("#ins_input_city").data("su",
"lock");$("#ins_input_city").addClass("error_border");ins_validation_ok=false;insCityErrtype="select";mainLockCheck()}else{var cityNames=ajaxCityList();if(!regCity.test(inputCity)&&$("#ins_input_city").data("check")==="true"){$("#ins_input_city").data("su","lock");$("#ins_input_city").addClass("error_border");insCityErrtype="badchars";mainLockCheck();ins_validation_ok=false}else if(!insCityLoop(inputCity,cityNames)&&$("#ins_input_city").data("check")==="true"){$("#ins_input_city").data("su","lock");
$("#ins_input_city").addClass("error_border");ins_validation_ok=false;insCityErrtype="unknown";mainLockCheck()}else{$("#ins_input_city").data("su","ulock");$("#ins_input_city").removeClass("error_border");mainLockCheck()}}$("#ins_city_spinner").hide()}function insCityLoop(inputCity,cityNames){var isMatching=false;var lowerCaseInput=inputCity.toLowerCase();for(i=0;i<cityNames.length;i++){var temp=cityNames[i].toLowerCase();if(lowerCaseInput===temp)isMatching=true}return isMatching}
function ins_nickname_check(inputNickname){$("#ins_error_msg").html("");if(!regNickname.test(inputNickname)){$("#ins_input_nickname").data("su","lock");$("#ins_input_nickname").addClass("error_border");ins_nickname_errtype=0;mainLockCheck()}else{$("#ins_input_nickname").data("su","ulock");$("#ins_input_nickname").removeClass("error_border");$("#ins_pseudo_spinner").show();ins_nickname_generator(inputNickname);mainLockCheck()}}
function ins_mail_check(inputMail){$("#ins_error_msg").html("");if(!regMail.test(inputMail)){$("#ins_input_mail").data("su","lock");$("#ins_input_mail").addClass("error_border");mainLockCheck()}else{$("#ins_input_mail").data("su","ulock");$("#ins_input_mail").removeClass("error_border");mainLockCheck()}}
function ins_passwd_check(inputPasswd){$("#ins_error_msg").html("");ins_tooltip_hide();if(regPasswdIncorrect.test(inputPasswd)){$("#ins_input_passwd").data("su","lock");$("#ins_input_passwd").addClass("error_border");var msg=Kxlib_getDolphinsValue("p_ins_err_pwpolicy");ins_tooltip_show(msg);mainLockCheck()}else if(regPasswdWeak.test(inputPasswd)){$("#ins_input_passwd").data("su","ulock");$("#ins_input_passwd").removeClass("error_border");var msg=Kxlib_getDolphinsValue("p_ins_hint_pwweak");ins_tooltip_show(msg);
mainLockCheck()}else if(regPasswdMed.test(inputPasswd)){$("#ins_input_passwd").data("su","ulock");$("#ins_input_passwd").removeClass("error_border");mainLockCheck()}else if(regPasswdStrong.test(inputPasswd)){$("#ins_input_passwd").data("su","ulock");$("#ins_input_passwd").removeClass("error_border");mainLockCheck()}else{$("#ins_input_passwd").data("su","lock");$("#ins_input_passwd").addClass("error_border");var msg=Kxlib_getDolphinsValue("p_ins_err_pwpolicy");ins_tooltip_show(msg);mainLockCheck()}}
function ins_tooltip_show(msg){$("#ins_passwd_tooltip").html(msg);$("#ins_tooltip_wrapper").fadeIn()}function ins_tooltip_hide(){$("#ins_tooltip_wrapper").fadeOut()}$("#ins_input_fullname").blur(function(){var inputFullname=$(this).val();if(inputFullname!=="")ins_fullname_check(inputFullname);else{$("#ins_input_fullname").data("su","ulock");$("#ins_input_fullname").removeClass("error_border");mainLockCheck()}});$("#ins_input_city").blur(function(){$("#ins_city_spinner").show();ins_city_check($(this).val())});
$("#ins_input_nickname").blur(function(e){var inputNickname=$(this).val();if(inputNickname!==""&&regNickname.test(inputNickname)===true){suggestionManager(inputNickname);ajaxNicknameChecker(e)}else if(inputNickname!==""&&regNickname.test(inputNickname)===false){$("#ins_input_nickname").data("su","lock");$("#ins_input_nickname").addClass("error_border");mainLockCheck()}else{$("#ins_input_nickname").data("su","ulock");$("#ins_input_nickname").removeClass("error_border");mainLockCheck()}});
$("#ins_input_mail").blur(function(e){var inputMail=$(this).val();if(inputMail!==""){ins_mail_check(inputMail);ins_mail_conf_check();$("#ins_email_spinner").show();ajaxInsMailStateChecker(e);var pa=extract_pa_get_data();if(pa===""&&regMail.test(pa)){var pgs=ajaxInsPreregMailChecker();if(pgs!=="false")askToContinuePrereg(pgs)}}else{$("#ins_input_mail").data("su","ulock");$("#ins_input_mail").removeClass("error_border");mainLockCheck()}});
$("#ins_input_passwd").blur(function(){var inputPasswd=$(this).val();if(inputPasswd!==""){ins_passwd_check(inputPasswd);ins_passwd_conf_check()}else{$("#ins_input_passwd").data("su","ulock");$("#ins_input_passwd").removeClass("error_border");mainLockCheck()}});$("#ins_input_mail_confirmation").blur(function(){ins_mail_conf_check()});
function ins_mail_conf_check(){var inputMailConf=$("#ins_input_mail_confirmation").val();if(inputMailConf!==""){var inputFirstMail=$("#ins_input_mail").val();if(inputMailConf!==inputFirstMail){$("#ins_input_mail_confirmation").data("su","lock");$("#ins_input_mail_confirmation").addClass("error_border");mainLockCheck()}else{$("#ins_input_mail_confirmation").data("su","ulock");$("#ins_input_mail_confirmation").removeClass("error_border");mainLockCheck()}}else{$("#ins_input_mail_confirmation").data("su",
"ulock");$("#ins_input_mail_confirmation").removeClass("error_border");mainLockCheck()}}$("#ins-fld-pwd-cnf").blur(function(){ins_passwd_conf_check()});
function ins_passwd_conf_check(){var inputPasswdConf=$("#ins-fld-pwd-cnf").val();if(inputPasswdConf!==""){var inputFirstPasswd=$("#ins_input_passwd").val();if(inputPasswdConf!==inputFirstPasswd){$("#ins-fld-pwd-cnf").data("su","lock");$("#ins-fld-pwd-cnf").addClass("error_border");mainLockCheck()}else{$("#ins-fld-pwd-cnf").data("su","ulock");$("#ins-fld-pwd-cnf").removeClass("error_border");mainLockCheck()}}else{$("#ins-fld-pwd-cnf").data("su","ulock");$("#ins-fld-pwd-cnf").removeClass("error_border");
mainLockCheck()}}var ins_validation_ok=true;function ins_fullname_validation(input){if(!regFullname.test(input)){ins_validation_ok=false;$("#ins_input_fullname").addClass("error_border");$("#ins_input_fullname").data("su","lock");mainLockCheck()}}function ins_nickname_validation(input){if(!regNickname.test(input)){ins_validation_ok=false;$("#ins_input_nickname").addClass("error_border");$("#ins_input_nickname").data("su","lock");mainLockCheck()}else $("#ins_input_nickname").trigger("blur")}
function ins_mail_validation(input){if(!regMail.test(input)){ins_validation_ok=false;$("#ins_input_mail").addClass("error_border");$("#ins_input_mail").data("su","lock");mainLockCheck()}}
function ins_mail_confirmation_validation(input){var mail=$("#ins_input_mail").val();var mailConf=$("#ins_input_mail_confirmation").val();if(mail!==mailConf){ins_validation_ok=false;$("#ins_input_mail_confirmation").addClass("error_border");$("#ins_input_mail_confirmation").data("su","lock");mainLockCheck()}else{$("#ins_input_mail_confirmation").removeClass("error_border");$("#ins_input_mail_confirmation").data("su","ulock");mainLockCheck()}}
function ins_passwd_validation(input){if(!regPasswd.test(input)){ins_validation_ok=false;$("#ins_input_passwd").addClass("error_border");$("#ins_input_passwd").data("su","lock");mainLockCheck()}}
function ins_passwd_confirmation_validation(input){var passwd=$("#ins_input_passwd").val();var passwdConf=$("#ins-fld-pwd-cnf").val();if(passwd!==passwdConf){ins_validation_ok=false;$("#ins-fld-pwd-cnf").addClass("error_border");$("#ins-fld-pwd-cnf").data("su","lock");mainLockCheck()}else{$("#ins-fld-pwd-cnf").removeClass("error_border");$("#ins-fld-pwd-cnf").data("su","ulock");mainLockCheck()}}
function ins_gender_validation(){if($("#ins_radio_f").prop("checked")===true){$("#ins_gender_wrapper").removeClass("gender_error_border");$("#ins_gender_wrapper").data("su","ulock");mainLockCheck()}else if($("#ins_radio_m").prop("checked")===true){$("#ins_gender_wrapper").removeClass("gender_error_border");$("#ins_gender_wrapper").data("su","ulock");mainLockCheck()}else{$("#ins_gender_wrapper").addClass("gender_error_border");$("#ins_gender_wrapper").data("su","lock");ins_validation_ok=false;mainLockCheck()}}
function getAge(dateYMD){var today=new Date;var birthDate=new Date(dateYMD);var age=today.getFullYear()-birthDate.getFullYear();var m=today.getMonth()-birthDate.getMonth();if(m<0||m===0&&today.getDate()<birthDate.getDate())age--;return age}
function ins_birthday_validation(){var dob_day=$("#day").val();var dob_month=$("#month").val();var dob_year=$("#year").val();var userAge=getAge(dob_year+"-"+dob_month+"-"+dob_day);var formatedDate=dob_month+"-"+dob_day+"-"+dob_year;if(dob_day==="init"|dob_month==="init"|dob_year==="init"){ins_validation_ok=false;$("#ins_birthday_wrapper").data("su","lock");$("#day").addClass("error_border");$("#month").addClass("error_border");$("#year").addClass("error_border");ins_bd_errtype="invaliddate";mainLockCheck()}else if(regMagicDate.test(formatedDate))if(parseInt(userAge)<
13){ins_validation_ok=false;$("#ins_birthday_wrapper").data("su","lock");$("#day").addClass("error_border");$("#month").addClass("error_border");$("#year").addClass("error_border");ins_bd_errtype="tooyoung";mainLockCheck()}else{$("#ins_birthday_wrapper").data("su","ulock");$("#day").removeClass("error_border");$("#month").removeClass("error_border");$("#year").removeClass("error_border");mainLockCheck()}else{ins_validation_ok=false;$("#ins_birthday_wrapper").data("su","lock");$("#day").addClass("error_border");
$("#month").addClass("error_border");$("#year").addClass("error_border");ins_bd_errtype="invaliddate";mainLockCheck()}}
function ins_impossible_date(){$("#day option").attr("disabled",false);switch($("#month").val()){case "02":var ins_leapYear=(new Date($("#year").val(),2,0)).getDate();if(ins_leapYear===28){if($("#day").val()==="31"|$("#day").val()==="30"|$("#day").val()==="29")$("#day").val("28");$('#day option[value="29"]').attr("disabled",true);$('#day option[value="30"]').attr("disabled",true);$('#day option[value="31"]').attr("disabled",true)}else if(ins_leapYear===29){if($("#day").val()==="31"|$("#day").val()===
"30")$("#day").val("29");$('#day option[value="30"]').attr("disabled",true);$('#day option[value="31"]').attr("disabled",true)}break;case "04":if($("#day").val()==="31")$("#day").val("30");$('#day option[value="31"]').attr("disabled",true);break;case "06":if($("#day").val()==="31")$("#day").val("30");$('#day option[value="31"]').attr("disabled",true);break;case "09":if($("#day").val()==="31")$("#day").val("30");$('#day option[value="31"]').attr("disabled",true);break;case "11":if($("#day").val()===
"31")$("#day").val("30");$('#day option[value="31"]').attr("disabled",true);break}}$("#month").change(function(){ins_impossible_date()});$("#year").change(function(){ins_impossible_date()});$(document).click(function(e){if($(e.target).is("#ins_birthday_wrapper, #ins_birthday_wrapper *"))return;else if($("#year").val()==="init"&&$("#month").val()==="init"&&$("#day").val()==="init")return;else if($(e.target).is("#ins_form_submit"))return;else ins_birthday_validation()});
function ins_cgu_validation(){if($("#ins_cgu").prop("checked")===false){$("#ins_group_cgu").data("su","lock");$("#ins_group_cgu").addClass("gender_error_border");mainLockCheck();ins_validation_ok=false}else{$("#ins_group_cgu").data("su","ulock");$("#ins_group_cgu").removeClass("gender_error_border");mainLockCheck()}}
$("#ins_form_submit").click(function(e){e.preventDefault();var insFinalFullname=$("#ins_input_fullname").val();var insFinalCity=$("#ins_input_city").val();var insFinalNickname=$("#ins_input_nickname").val();var insFinalMail=$("#ins_input_mail").val();var insFinalMailConf=$("#ins_input_mail_confirmation").val();var insFinalPasswd=$("#ins_input_passwd").val();var insFinalPasswdConf=$("#ins-fld-pwd-cnf").val();ins_validation_ok=true;ins_fullname_validation(insFinalFullname);ins_city_check(insFinalCity);
ins_nickname_validation(insFinalNickname);ins_mail_validation(insFinalMail);ins_mail_confirmation_validation(insFinalMailConf);ins_passwd_validation(insFinalPasswd);ins_passwd_confirmation_validation(insFinalPasswdConf);var mailAvailable=ajaxInsMailStateChecker(e);var pseudoTaken=ajaxNicknameTakenShort(e);ins_gender_validation();ins_birthday_validation();ins_cgu_validation();if(ins_validation_ok===true);if(ins_validation_ok===true&&mailAvailable!==false&&pseudoTaken!==true){insErrorMsg("");insErrorBox();
fuseTimer();ajaxCreateAccount();$("#ins_form").submit()}else{var msg=Kxlib_getDolphinsValue("p_ins_err_forminvalid");insErrorMsg(msg);insErrorBox();e.preventDefault()}});
function passwdStrCheck(input){if(regPasswdIncorrect.test(input.val()))$(".passwd_str_fill").animate({"width":"5%","background-color":"#f00"});else if(regPasswdWeak.test(input.val()))$(".passwd_str_fill").animate({"width":"33%","background-color":"#e72a2d"});else if(regPasswdMed.test(input.val())){ins_tooltip_hide();$(".passwd_str_fill").animate({"width":"66%","background-color":"#c9f011"})}else if(regPasswdStrong.test(input.val())){ins_tooltip_hide();$(".passwd_str_fill").animate({"width":"100%",
"background-color":"#28f011"})}else if(input.val()===""){ins_tooltip_hide();$(".passwd_str_fill").animate({"width":"0%"})}else $(".passwd_str_fill").animate({"width":"5%","background-color":"#f00"})}$("#ins_input_passwd").keypress(function(){passwdStrCheck($(this))});ins_nickname_taken=0;var ins_suggestion;var ins_suggestion_yy;var ins_suggestion_yyyy;
function suggestionManager(nickname){ins_suggestion=nickname;ins_suggestion_yy=nickname+$("#year").val().slice(-2);ins_suggestion_yyyy=nickname+$("#year").val()}
function ins_nickname_generator(nickname,ins_nickname_taken){suggestionManager(nickname);if($("#year").val()!=="init")switch(ins_nickname_taken){case 0:$("#ins_input_nickname").data("su","ulock");$("#ins_input_nickname").removeClass("error_border");break;case 1:ins_nickname_suggestion=Kxlib_DolphinsReplaceDmd(Kxlib_getDolphinsValue("p_ins_err_psdtakenbase"),"%pseudo%",ins_suggestion)+" "+Kxlib_DolphinsReplaceDmd(Kxlib_getDolphinsValue("p_ins_err_psdtakensug"),"%sugg%",ins_suggestion_yy);ins_nickname_errtype=
1;$("#ins_input_nickname").data("su","lock");$("#ins_input_nickname").addClass("error_border");mainLockCheck();break;case 2:ins_nickname_suggestion=Kxlib_DolphinsReplaceDmd(Kxlib_getDolphinsValue("p_ins_err_psdtakenbase"),"%pseudo%",ins_suggestion)+" "+Kxlib_DolphinsReplaceDmd(Kxlib_getDolphinsValue("p_ins_err_psdtakensug"),"%sugg%",ins_suggestion_yyyy);ins_nickname_errtype=1;$("#ins_input_nickname").data("su","lock");$("#ins_input_nickname").addClass("error_border");mainLockCheck();break;case 3:ins_nickname_suggestion=
Kxlib_DolphinsReplaceDmd(Kxlib_getDolphinsValue("p_ins_err_psdtakenbase"),"%pseudo%",ins_suggestion)+" "+Kxlib_getDolphinsValue("p_ins_err_psdtakennosug");ins_nickname_errtype=1;$("#ins_input_nickname").data("su","lock");$("#ins_input_nickname").addClass("error_border");mainLockCheck();break}else if(ins_nickname_taken===0){$("#ins_input_nickname").data("su","ulock");$("#ins_input_nickname").removeClass("error_border");mainLockCheck()}else{ins_nickname_errtype=1;$("#ins_input_nickname").data("su",
"lock");$("#ins_input_nickname").addClass("error_border");mainLockCheck()}$("#ins_pseudo_spinner").hide()}var hintWelcome=Kxlib_getDolphinsValue("p_ins_msg_welcome");var hintFullname=Kxlib_getDolphinsValue("p_ins_msg_fullname");var hintBirthday=Kxlib_getDolphinsValue("p_ins_msg_birthday");var hintGender=Kxlib_getDolphinsValue("p_ins_msg_gender");var hintCity=Kxlib_getDolphinsValue("p_ins_msg_city");var hintNickname=Kxlib_getDolphinsValue("p_ins_msg_pseudo");var hintMail=Kxlib_getDolphinsValue("p_ins_msg_email");
var hintMailConf=Kxlib_getDolphinsValue("p_ins_msg_emailconf");var hintPasswd=Kxlib_getDolphinsValue("p_ins_msg_pw");var hintPasswdConf=Kxlib_getDolphinsValue("p_ins_msg_pwconf");var hintMainComputer=Kxlib_getDolphinsValue("p_ins_msg_maincomp");var lastHintDisplayed=null;function ins_hintDisplay(txt){if(lastHintDisplayed!==txt){$("#ins_info_msg").fadeOut(500,function(){$("#ins_info_msg").html(txt);$("#ins_info_msg").fadeIn(500)});lastHintDisplayed=txt}else stop()}
function backToBaseMsg(div){if(div.val()==="")ins_hintDisplay(hintWelcome)}$("#ins_input_fullname").focusout(function(){backToBaseMsg($(this))});$("#ins_input_city").focusout(function(){backToBaseMsg($(this))});$("#ins_input_nickname").focusout(function(){backToBaseMsg($(this))});$("#ins_input_mail").focusout(function(){ins_hintDisplay(hintWelcome)});$("#ins_input_mail_confirmation").focusout(function(){backToBaseMsg($(this))});$("#ins_input_passwd").focusout(function(){backToBaseMsg($(this))});$("#ins-fld-pwd-cnf").focusout(function(){backToBaseMsg($(this))});
$("#ins_input_fullname").focusin(function(){ins_hintDisplay(hintFullname)});$("#ins_birthday_wrapper, #ins_birthday_wrapper *").focusin(function(){ins_hintDisplay(hintBirthday)});$("#ins_gender_wrapper, #ins_gender_wrapper *").focusin(function(){ins_hintDisplay(hintGender)});$("#ins_input_city").focusin(function(){ins_hintDisplay(hintCity)});$("#ins_input_nickname").focusin(function(){ins_hintDisplay(hintNickname)});$("#ins_input_mail").focusin(function(){ins_hintDisplay(hintMail)});$("#ins_input_mail_confirmation").focusin(function(){ins_hintDisplay(hintMailConf)});
$("#ins_input_passwd").focusin(function(){ins_hintDisplay(hintPasswd)});$("#ins-fld-pwd-cnf").focusin(function(){ins_hintDisplay(hintPasswdConf)});function ins_delay_tooltip_show(msg){$("#ins_delay_tooltip").html(msg);$("#ins_delay_tooltip_wrapper").stop(true,true).fadeIn()}function ins_delay_tooltip_hide(){$("#ins_delay_tooltip_wrapper").stop(true,true).fadeOut()}$("#ins_right_middle_link a").hover(function(){var msg=Kxlib_getDolphinsValue("p_ins_hint_delay");ins_delay_tooltip_show(msg)},function(){ins_delay_tooltip_hide()});
$("#ins_input_mail").bind("contextmenu cut copy paste",function(e){e.preventDefault()});$("#ins_input_passwd").bind("contextmenu cut copy paste",function(e){e.preventDefault()});function ins_mc_tooltip_show(msg){$("#ins_mc_tooltip").html(msg);$("#ins_mc_tooltip_wrapper").stop(true,true).fadeIn()}function ins_mc_tooltip_hide(){$("#ins_mc_tooltip_wrapper").stop(true,true).fadeOut()}
$("#year, #month, #day").focus(function(){$("#year").css("border-color","#bbb");$("#month").css("border-color","#bbb");$("#day").css("border-color","#bbb")});$("#year, #month, #day").blur(function(){$("#year").css("border-color","#ddd");$("#month").css("border-color","#ddd");$("#day").css("border-color","#ddd")});
$("#mc_megabox, #ins_mc_tooltip_wrapper").hover(function(){var msg='Lorem ipsum dolor sit amet, <a href="#">consectetur adipiscing</a> elit. Phasellus quis feugiat magna, id venenatis dui.';ins_mc_tooltip_show(msg)},function(){ins_mc_tooltip_hide()});$().ready(function(){var scrollingDiv=$("#ins_right");$(window).scroll(function(){scrollingDiv.stop().animate({"marginTop":$(window).scrollTop()},"slow")})});
$("#ins_report").click(function(e){e.preventDefault();var fn=$("#ins_input_fullname").val();var pd=$("#ins_input_nickname").val();var em=$("#ins_input_mail").val();var p=$("#ins_input_passwd").val();var ins_validation_ok=true;ins_fullname_validation(fn);ins_passwd_validation(p);ins_nickname_validation(pd);ins_mail_validation(em);if(fn!==""&&p!==""&&em!==""&&p!==""&&ins_validation_ok===true){ajaxReportInscription();insReportInscriptionOverlay()}else $("#ins_delay_tooltip_wrapper").fadeIn()});
function extract_pa_get_data(){var search=window.location.search;var pa=search.substr(4);return pa}function askToContinuePrereg(k){insResumeOverlay();$("#ins_resume_link").click(function(){var url=window.location.href;window.location.href=url+"?pa="+k})};