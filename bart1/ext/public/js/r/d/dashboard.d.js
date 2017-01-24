$(".dash-menu-bb").hover(function(){
    $(this).children(".dash-menu-bb-nolight").attr("class","dash-menu-bb-light");
    var id = $(this).attr("id").toString();
    
    switch (id) {
        case "dash-buzz-bb" :
                $("#dash-buzz-catcher").text("What's the buzz ?");
            break;
        case "dash-comy-bb" :
                
                $("#dash-comy-catcher").text("What's up on my circle ?");
            break;
        case "dash-man-bb" :
                $("#dash-man-catcher").html("Explore your account's stuffs");
            break;
    }
}, function() {
    $(this).children(".dash-menu-bb-light").attr("class","dash-menu-bb-nolight");
    var id = $(this).attr("id").toString();
    
    switch (id) {
        case "dash-buzz-bb" :
                $("#dash-buzz-catcher").text("");
            break;
        case "dash-comy-bb" :
                
                $("#dash-comy-catcher").text("");
            break;
        case "dash-man-bb" :
                $("#dash-man-catcher").html("");
            break;
    }
});

