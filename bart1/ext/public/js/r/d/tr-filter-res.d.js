/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
$("#tr-fil-content").resizable({
    handles: "s"
});
//*/
function TrFiltersHandler () {
    
    this.maxResInBloc = 15;
    
    this.uaction;
    this.filBrSel = "#tr-filter-brain"; 
    this.filResSel = "#tr-filter-res"; 
    
    this.OpenFiltersBrain = function () {
        //1) On reduit le header
        //2) On affiche le bloc Filters_Brain
        $(this.filBrSel).removeClass("this_hide");
        //2) On affiche le bloc Filters_Resuts
        $(this.filResSel).removeClass("this_hide");
    };
    
    this.CloseFiltersBrain = function () {
        //1) On reduit le header
        //2) On affiche le bloc Filters_Brain
        $(this.filBrSel).addClass("this_hide");
        //2) On affiche le bloc Filters_Resuts
        $(this.filResSel).addClass("this_hide");
    };
    
    this.AcquireBasics = function (th) {
        $tarsel = $(th);

        if( KgbLib_CheckNullity($tarsel.data("action")) ) {
            //L'erreur devra etre encoyé au server dans la version production
            Kxlib_DebugVars("Error : Can't get access to uaction");
            return;
        } else this.uaction = $tarsel.data("action");

        return 1;
    };
    
    this.CheckOperation = function (argv) {
        this.AcquireBasics(argv);

        switch( this.uaction ) {
              case "op_filters":
                      this.OpenFiltersBrain();
                  break;
              default :
                      //TODO : Incoherence
                  break;
          }
    };
    
    this.SetNbResult = function (argv) {
        $("#t-f-r-m-nb").html(argv);
    };
    
    this.EnableNext = function(argv) {
        var ena = argv;
        
        if ( KgbLib_CheckNullity(ena) || !ena ) {
            $("#t-f-r-m-next").removeAttr("href");
            
            $("#t-f-r-m-next").removeClass("tr-fil-res-more-link-ena");
            $("#t-f-r-m-next").addClass("tr-fil-res-more-link-dis");
        } else {
            $("#t-f-r-m-next").attr("href","");
            
            $("#t-f-r-m-next").addClass("tr-fil-res-more-link-ena");
            $("#t-f-r-m-next").removeClass("tr-fil-res-more-link-dis");
        }
        
    };
    
    this.EnablePrev = function(argv) {
        var ena = argv;
        
        if ( KgbLib_CheckNullity(ena) || !ena ) {
            $("#t-f-r-m-prev").removeAttr("href");
            
            $("#t-f-r-m-prev").removeClass("tr-fil-res-more-link-ena");
            $("#t-f-r-m-prev").addClass("tr-fil-res-more-link-dis");
        } else {
            $("#t-f-r-m-prev").attr("href","");
            
            $("#t-f-r-m-prev").addClass("tr-fil-res-more-link-ena");
            $("#t-f-r-m-prev").removeClass("tr-fil-res-more-link-dis");
        }
    };
    
    //Gère le cas où il y a plus que max résultats autorisé pour une page
    this.HandleMultiplePages = function (argv) {
        //Cas 1 : On est au niveau de la première page
        //*
        //Prev doit etre désactivé
        this.EnablePrev(false);
        //Next doit etre activé
        this.EnableNext(true);
        //*/
        //Cas 2 : On est au niveau d'une page 'intermédiaire'
        /*
        //Prev doit etre activé
        this.EnablePrev(true);
        //Next doit etre activé
        this.EnableNext(true);
        //*/
        //Cas 3 : On est au niveau de la dernière page
        /*
        //Prev doit etre activé
        this.EnablePrev(true);
        //Next doit etre désactivé
        this.EnableNext(false);
        //*/
    };
    
    this.Noone = function () {
        var cn = $("#tr-fil-res-child-max").find(".tr-f-res-model").length;
//        alert(cn);

        if (! cn ) {
            $("#tr-fil-res-nochild").removeClass("this_hide");
            
            //On met à jour le nombre de résultats
            this.SetNbResult(cn);
        } else { 
            //On s'assure que le 'texte' est hide
            if (! $("#tr-fil-res-nochild").hasClass("this_hide") )
                $("#tr-fil-res-nochild").removeClass("this_hide");
            
            //On met à jour le nombre de résultats
            this.SetNbResult(cn);
            
            //S'il y a plus de résultats que Max on lance le processus de plusieurs pages
            if ( cn > this.maxResInBloc) {
                this.HandleMultiplePages(cn);
            }
        }
            
    };
    
    this.Init = function() {
        //Faut-il faire apparaitre Filter ?
        //Il y a t-il des resultats de filtres ? Sinon afficher le 'texte' 
        this.Noone();
    };
    
}

function TrFilters_Receiver (){
    this.Routeur = function (th){
        if ( KgbLib_CheckNullity(th) ) return; 
        
        var _obj = new TrFiltersHandler();
        _obj.CheckOperation(th);
    };
};

(function(){
    var obj = new TrFiltersHandler();
    obj.Init();
    
    $("#tr-fil-br-close").click(function(e){
        e.preventDefault();
        
        obj.CloseFiltersBrain();
    });
    
    $(".tr-fil-shw-desc").hover(function(e){
        var dolf = $(e.target).data("dolph");
        var msg = Kxlib_getDolphinsValue (dolf);
        
        $("#tr-fil-br-desc").html(msg);
    },function(e){
        var msg = Kxlib_getDolphinsValue ("trf_desc");
        
        $("#tr-fil-br-desc").html(msg);
    });
    
    $("#tr-fil-help-link").click(function(e){
        e.preventDefault();
    });
    
})();