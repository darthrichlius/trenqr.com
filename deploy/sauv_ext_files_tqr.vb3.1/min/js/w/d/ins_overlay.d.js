var overlay_state="hidden";$("#ins_famous").click(function(){ins_darkenBackground();$("#ins_overlay_famous").fadeIn(function(){overlay_state="displayed"})});function insKnownUserOverlay(){ins_darkenBackground();$("#ins_overlay_knownuser").fadeIn(function(){overlay_state="displayed"})}function insReportInscriptionOverlay(){ins_darkenBackground();$("#ins_overlay_report").fadeIn(function(){overlay_state="displayed"})}
function insResumeOverlay(){ins_darkenBackground();$("#ins_overlay_resume").fadeIn(function(){overlay_state="displayed"})}$(document).click(function(e){if(overlay_state==="displayed")if($(e.target).is("#ins_overlay_resume"))return;else{ins_darkenBackground();$("#ins_overlay_famous").fadeOut();$("#ins_overlay_knownuser").fadeOut();overlay_state="hidden"}});function ins_darkenBackground(){$("#ins_theater").css("height",$(document).height());$("#ins_theater").fadeToggle()}
$().ready(function(){var scrollingKnownUserOffset=$("#ins_overlay_knownuser");var scrollingFamous=$("#ins_overlay_famous");var scrollingBeta=$("#ins_overlay_beta");$(window).scroll(function(){var scroll=$(window).scrollTop()+105;scrollingKnownUserOffset.stop().animate({"marginTop":scroll},"slow");scrollingFamous.stop().animate({"marginTop":scroll},"slow");scrollingBeta.stop().animate({"marginTop":scroll},"slow")})});
function fuseTimer(){var interval=50;var fill=100;var fuse=$(".ins_fuse_fill");var tid=setInterval(function(){fuse.stop(true,true).animate({"width":fill+"%"},"fast");fill--},interval);if(fill===0){console.log("fuse stop");clearInterval(tid)}};
