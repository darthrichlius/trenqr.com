$(document).keydown(function(e){
    if( e.ctrlKey && e.which === 38 ) {
        //$("#header").scrollIntoView(true);
        $(document).scrollTop( $("#header").offset().top );  
    }

});
