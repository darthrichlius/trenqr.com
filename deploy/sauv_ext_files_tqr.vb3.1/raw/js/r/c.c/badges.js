function h_blck() {
    $(".blocked-btn-deny").hide();
};

var _f = function() {
    $(".bloc-btn").click(function(){
        $(this).hide();
        var _i = $(this).attr("btn-id").toString();
        _i = "#bld-id-"+_i;
        $(_i).show();
    });
};

h_blck();

_f();