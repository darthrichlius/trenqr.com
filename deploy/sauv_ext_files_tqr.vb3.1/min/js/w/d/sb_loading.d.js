$(document).ready(function(){if(!sbAjaxCheck()){window.location.href="index.php";return}if($("#sb_loadinggroup").css("display")!=="none")setInterval(function(){switch($("#sb_suspension_mark").html()){case "...":$("#sb_suspension_mark").html(".&nbsp;&nbsp;");break;case ".&nbsp;&nbsp;":$("#sb_suspension_mark").html("..&nbsp;");break;case "..&nbsp;":$("#sb_suspension_mark").html("...");break}},1E3);loadingFill(2E3,0);$("#sb_loadinghints").html(hintSelector());hintSlider()});
function getRandomInt(min,max){return Math.floor(Math.random()*(max-min+1))+min}function loadingFill(i,iter){var lMax=$("#sb_loadingbar").width();var cLen=$("#sb_loadingfill").width();if(cLen<lMax&&iter<=7){statusSelector(iter);iter++;loadingBar(i,iter)}else{$("#sb_loadingfill").stop(true,true).width("520px");statusSelector(7);pageLoaded()}}
function loadingBar(to,it){if(it>=7){var df=getRandomInt(2E3,4E3);$("#sb_loadingfill").animate({width:"520px",backgroundColor:"rgb("+getRandomInt(0,255)+", "+getRandomInt(0,255)+", "+getRandomInt(0,255)+")"},to,function(){loadingFill(df,it)})}else{var d=getRandomInt(2E3,4E3);var s=getRandomInt(10,100);var l=$("#sb_loadingfill").width();$("#sb_loadingfill").animate({width:l+s+"px",backgroundColor:"rgb("+getRandomInt(0,255)+", "+getRandomInt(0,255)+", "+getRandomInt(0,255)+")"},to,function(){loadingFill(d,
it)})}}function pageLoaded(){$("#sb_loadigtitle").html("Compte cr&eacute;e avec succ&egrave;s !").css("background","transparent");$("#sb_btn_wrapper, #sb_btn_wrapper *").fadeIn();$("#sb_loadingfill").animate({backgroundColor:"#4EBF4E"})}
function hintSelector(){var m=new String;var r=getRandomInt(0,9);switch(r){case 0:m=Kxlib_getDolphinsValue("p_sb_hint0");break;case 1:m=Kxlib_getDolphinsValue("p_sb_hint1");break;case 2:m=Kxlib_getDolphinsValue("p_sb_hint2");break;case 3:m=Kxlib_getDolphinsValue("p_sb_hint3");break;case 4:m=Kxlib_getDolphinsValue("p_sb_hint4");break;case 5:m=Kxlib_getDolphinsValue("p_sb_hint5");break;case 6:m=Kxlib_getDolphinsValue("p_sb_hint6");break;case 7:m=Kxlib_getDolphinsValue("p_sb_hint7");break;case 8:m=Kxlib_getDolphinsValue("p_sb_hint8");
break;case 9:m=Kxlib_getDolphinsValue("p_sb_hint9");break;default:m=Kxlib_getDolphinsValue("p_sb_hintdef");break}return m}
function statusSelector(step){var statuses=new Array;var $this=$("#sb_loadingstatus");statuses[0]="Montage des \u00e9chafaudages pour la cr\u00e9ation de votre page...";statuses[1]="D\u00e9calage des autres utilisateurs pour vous faire une place...";statuses[2]="Mise en place des param\u00e8tres de s\u00e9curit\u00e9 par d\u00e9faut...";statuses[3]="Mise en place des param\u00e8tres de profil par d\u00e9faut...";statuses[4]="Mise en place de l'apparence par d\u00e9faut...";statuses[5]="Envoi des invitations pour l'innauguration de votre page...";
statuses[6]="Pr\u00e9paration du comit\u00e9 d'accueil et des cocktails de bienvenue...";statuses[7]="Tout est pr\u00eat :)";$this.fadeOut(function(){$this.html(statuses[step]);$this.fadeIn()})}function hintSlider(){setInterval(function(){$("#sb_loadinghints").fadeOut(function(){$("#sb_loadinghints").html(hintSelector());$("#sb_loadinghints").fadeIn()})},5E3)}
function statusSlider(){setInterval(function(){$("#sb_loadingstatus").fadeOut(function(){$("#sb_loadingstatus").html(statusSelector());$("#sb_loadingstatus").fadeIn()})},3E3)}
function sbAjaxCheck(){var dataset;var jsonData=new Object;jsonData.urqid="standbyStatusChecker";$.ajax({async:false,url:"../../__servers/serverStandby.php",type:"POST",data:jsonData,success:function(data){dataset=JSON.parse(data)},error:function(jqXHR,textStatus,errorThrown){console.log(errorThrown);console.log(textStatus);console.log(jqXHR)}});return dataset.allowed};
