var langHeight=$(".langlist").height();var selectHeight=$(".lang").height();var topOffset=langHeight-selectHeight;var state=0;$(".lang").click(function(){if(state===0){$(".lang").stop();$(".lang").animate({height:langHeight});state=1;$(".langplus").css("visibility","hidden")}else if(state===1){$(".lang").stop();$(".lang").animate({height:selectHeight,top:0},function(){$(".langplus").css("visibility","visible")});state=0}});
$(document).click(function(e){if($(e.target).is(".lang, .lang *"))return;else{$(".lang").stop();$(".lang").animate({height:selectHeight,top:0},function(){$(".langplus").css("visibility","visible")});state=0}});$(".lang_current").click(function(e){e.preventDefault()});$(document).ready(function(){if($(document).height()>=$(window).height())$(".theater").css("height",$(document).height());else $(".theater").css("height",$(window).height())});
