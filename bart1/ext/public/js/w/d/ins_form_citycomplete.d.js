//HTMLENTITIES fait main
function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&apos;');
}

/**********************************************/
/* Gestion de l'autocompletion du champ Ville */
/**********************************************/
//Fonction de repérage des doublons
function duplicateOverseer(inputArray){
    var compteur = 1;
    var len = inputArray.length;
    if(len === 1){
        compteur = 1;
    } else {
        for(var i = 0; i < len; i++){
            if($.inArray(inputArray[i], inputArray, i+1) !== -1){
                compteur++;
            }
        }
    }
    return compteur;
}

//Fonction de "SELECT DISTINCT"
function selectDistinct(inputArray){
    var resultArray = new Array();
    var formatArray = new Array();
    var dupliArray = new Array();
    //On boucle sur le tableau donné en entrée
    for(var i = 0; i < inputArray.length; i++){
        //On met tous les éléments uniques dans un tableau
        if($.inArray(inputArray[i], resultArray) === -1){
            resultArray.push(inputArray[i]);
        //Et les doublons dans un autre
        } else {
            dupliArray.push(inputArray[i]);
        }
    }
    
    //On compte le nombre de doublons
    dupliArray.sort();
    var out = new Array();
    var currentElem = null;
    var cnt = 0;
    for (var j = 0; j < dupliArray.length; j++){
        if(dupliArray[j] !== currentElem){
            if(cnt > 0){
                out.push([currentElem, cnt+1]);
            }
            currentElem = dupliArray[j];
            cnt = 1;
        } else {
            cnt++;
        }
    }
    if(cnt > 0){
        out.push([currentElem, cnt+1]);
    }
    
    
    for(var k = 0; k < resultArray.length; k++){
        //lock pour empêcher d'afficher trouzemille fois les mêmes résultats
        var lock = false;
        for(var l = 0; l < out.length; l++){
            if(resultArray[k] === out[l][0]){
                formatArray.push('<div data-fortable="multi" class="customlist_choices"><span class="nom_ville">' + resultArray[k] + '</span> <span class="nb_villes">' + out[l][1] + ' '+Kxlib_getDolphinsValue("p_cities")+'</span></div>');
                lock = true;
            }
        }
        if(lock === false){
            formatArray.push('<div data-fortable="mono" class="customlist_choices">' + resultArray[k] + '</div>');
        }
    }
    
    return formatArray;
}

// ---------------------------

var globalArray = new Array();
var globalLight = new Array();
var multiArray = new Array();

function insCityAutocomplete(tab){
    var cityDuplicates = 0;
    var srcArray = new Array();
    var lightArray = new Array();
    var light2 = new Array();
    
    //Reset des tableaux globaux
    globalArray.length = 0;
    globalLight.length = 0;
    multiArray.length = 0;
    
    //Virtuellement inutile, je suis obligé de faire ça pour que
    //mon tableau d'objets soit effectivement traité en tant
    //que tableau, et que je puisse travailler dessus.
    $.each(tab, function(index, value){
        srcArray.push(value);
        lightArray.push(value.asciiname);
    });

    for(var i = 0; i < srcArray.length; i++){
        /* On stocke toutes les données dans un tableau à 2 dimensions si on en a besoin
         * (plusieurs fois la même ville dans un même pays par exemple) */
        light2[i] = [srcArray[i].city_id, srcArray[i].asciiname];
        globalLight[i] = [srcArray[i].city_id, srcArray[i].asciiname];
        multiArray[i] = [srcArray[i].city_id, srcArray[i].asciiname, srcArray[i].ctr_name, srcArray[i].city_pop];
        globalArray[srcArray[i].city_id] = [srcArray[i].asciiname, srcArray[i].ctr_name, srcArray[i].city_pop];
    }
    /* On regarde si notre liste a des doublons.
     * Si oui, ça veut dire qu'on a plusieurs villes
     * avec le même nom dans un même pays. */
    cityDuplicates = duplicateOverseer(lightArray);
    //La partie suivante a été commentée suite à un bricolage visant à faire fonctionner
    //la liste déroulante des villes de manière asynchrone, et sans bug de transition entre
    //villes multiples et uniques
    // ---------
    /*if(cityDuplicates === 1 && tab.length !== 0){
        $('#customlistmulti').hide();
        $('#city_table_wrapper').slideUp();
        //Reset du check de l'input
        $('#ins_input_city').data('check', 'true');
        $('#customlist').empty();
        //J'insère les données
        $.each(light2, function(i,v){
            var $o = $("<a/>");
            $o.html(v[1]);
            $o.data("city",v[0]);
            $o.addClass("jsbind-city-lis-elm");
            $o.addClass("city-lis-elm");
            //J'ajoute à mon conteneur
            if($('#customlistmulti > .jsbind-city-lis-elm').length < 10){
                $("#customlist").append($o);
            }
        });
        //J'affiche ma liste
        $("#customlist").show();
        console.log('customlist')
    } else ------------- */
    if(cityDuplicates >= 1 && tab.length !== 0){
        $('#customlist').hide();
        //On affiche la liste 'DISTINCT'
        var light3 = selectDistinct(lightArray);
        $('#customlistmulti').empty();
        $.each(light3, function(k, v){
            var $p = $('<a/>');
            $p.html(v);
            $p.addClass('jsbind-city-lis-elm');
            $p.addClass('city-lis-elm');
            if($('#customlistmulti > .jsbind-city-lis-elm').length < 10){
                $($p).on('mousedown', function(e){
                    e.stopPropagation();
                    //Cette ligne sert à ne pas soulever d'erreur entre le moment où l'utilisateur clique
                    //sur la liste et le moment où il choisit sa ville dans le tableau
                    $('#ins_input_city').data('temp', 'allowed');
                    var target = $(e.target).closest('.customlist_choices');
                    HandleSelectMulti(target);
                    //On vire le tooltip
                    $('#city_tooltip_wrapper').fadeOut();
                });
                $("#customlistmulti").append($p);
            }
        });
        $('#customlistmulti').show();
        $('#ins_city_spinner').hide();
    } else {
        $('#customlist').hide();
        $('#customlistmulti').hide();
        $('#ins_city_spinner').hide();
    }
}

/*function HandleSelectAC (t) {
    $("#ins_input_city").data("cc",t.data('city'));
    $("#ins_input_city").val(t.html());
    $("#customlist").hide();
    ins_city_check($('#ins_input_city').val());
}*/

function HandleSelectMulti(t){
    if(t.data('fortable') === 'mono'){
        $('#ins_input_city').val(t.html());
        $('#customlistmulti').hide();
        $('#ins_city_table_wrapper').slideUp();
        for(var i = 0; i < multiArray.length; i++){
            if(multiArray[i][1] === $('#ins_input_city').val()){
                $('#ins_input_city').data('cc', multiArray[i][0]);
            }
        }
        ins_city_check($('#ins_input_city').val());
    } else if(t.data('fortable') === 'multi'){
        var cName = t.find('.nom_ville').html();
        $('#ins_input_city').val(cName);
        $('#customlistmulti').hide();
        //On reset le tableau
        $('#city_table').html('<tr><th>'+Kxlib_getDolphinsValue("p_city")+'</th><th>'+Kxlib_getDolphinsValue("p_country")+'</th><th>'+Kxlib_getDolphinsValue("p_population")+'</th></tr>');
        //On le remplit
        for(var j = 0; j < multiArray.length; j++){
            if(multiArray[j][1] === cName){
                $('#city_table').append('<tr data-city=\''+ multiArray[j][0] +'\' class=\'citylist\'><td>'+ multiArray[j][1] +'</td><td>'+ multiArray[j][2] +'</td><td>'+ multiArray[j][3]+'</td></tr>');
            }
        }
        //On le montre
        $('#city_table_wrapper').slideDown(400, function(){
            $('#ins_input_city').focus();
        });
        //Et on vire le tooltip
        $('#city_tooltip_wrapper').fadeOut();
    } else {
        console.log('Error: HandleSelectMulti(t)');
    }
}

function manualInputChecker(lightTypeArray, manualInput){
    $.each(lightTypeArray, function(k, v){
        //v[1] correspond à la colonne 'name'
        if(manualInput.toLowerCase() === v[1].toLowerCase() && lightTypeArray.length === 1){
            $('#ins_input_city').data('cc', v[0].toString());
        } else if(manualInput.toLowerCase() === v[1].toLowerCase() && lightTypeArray.length > 1){
            //On set 'manuellement' à 'multi' pour traiter le cas sur la validation
            $('#ins_input_city').data('cc', 'multi');
            $('#city_tooltip_wrapper').fadeIn();
        }
    });
}


//Delegate liste
//$('#customlist').delegate('.city-lis-elm', 'mousedown', function(e){
//    e.preventDefault();
//    HandleSelectAC($(e.target));
//});
/*$('#customlist').delegate('.city-lis-elm', 'mouseover', function(e){
    e.preventDefault();
});*/

//Delegate listemulti
/*$('#customlistmulti').on('click', '.city-lis-elm', function(e){
    e.stopPropagation();
    var target = $(e.target).closest('.customlist_choices');
    HandleSelectMulti(target);
//    var target = $(e.target).closest('.customlist_choices');
//    HandleSelectMulti(target);
});*/
/*$('#customlistmulti').delegate('.city-lis-elm', 'mouseover', function(e){
    e.preventDefault();
});*/

//Trigger de clic sur le tableau
$('#city_table, #city_table *').on('mousedown', '.citylist', function(){
    //On signale aussi qu'il n'y a pas besoin de valider l'intérieur de l'input
    $('#ins_input_city').data('check', 'false');
});
$('#city_table').on('mousedown', '.citylist', function(){
    //On remplit l'input
    var thisId = $(this).data('city');
    $('#ins_input_city').val(globalArray[thisId][0] + ', ' + globalArray[thisId][1]);
    //Et on bind l'ID de la ville sélectionnée à notre input pour l'envoyer en AJAX
    $('#ins_input_city').data('cc', thisId);
    $('#city_table_wrapper').slideUp();
    //On cache le tooltip d'info
    $('#city_tooltip_wrapper').fadeOut();
    //On recheck le contenu de la ville
    ins_city_check($('#ins_input_city').val());
});

$(document).click(function(e){
    if($(e.target).is('#ins_birthday_wrapper, #ins_birthday_wrapper *')){
        //do nothing
    } else if($(e.target).is('#ins_input_city')){
        //do nothing
    } else if($(e.target).is('#city_tooltip_wrapper, #city_tooltip')){
        HandleSelectMulti($('.customlist_choices'));
        $('#city_tooltip_wrapper').fadeOut();
    } else if($(e.target).is('#ins_form, #ins_form *')){
        //ins_birthday_validation();
        $('#customlist').hide();
        $('#customlistmulti').hide();
    } else {
        //do nothing
    }
    
    //Traitement du cas de ville non précisée
    if($(e.target).not("#ins_input_city, #ins_input_city *")){
        $('#ins_input_city').data('temp', '0');
    }
});

//$('#ins_input_city').blur(function(){
//    insCityCheck($(this).val());
//});

$('#ins_input_city').focus(function(){
    if($(this).data('cc') === 'multi'){
        $('#customlistmulti').show();
    }
});


/* Désactivation de l'envoi du formulaire via 'ENTER' */
$('#ins_form').bind('keyup keypress', function(e){
    var key = e.which;
    if(key === 13){
        e.preventDefault();
        e.stopPropagation();
    } else if(key === 27){
        e.preventDefault();
        e.stopPropagation();
    }
});

/* Gestion des touches pour la navigation de l'autocomplete */
//var index = 0;
//$('#ins_input_ciy').keyup(function(e){
//    //reset de cc
//    $('#ins_input_city').data('cc', '-1');
//    //reset de check
//    $('#ins_input_city').data('check', 'true');
//    if($('#ins_input_city').val() === ''){
//        $('#customlist').hide();
//        $('#customlistmulti').hide();
//    } else {
//        //SPINNER HERE
//        $('#ins_city_spinner').show();
//        ajaxCitySuggestion();
//        manualInputChecker(globalLight, $('#ins_input_city').val());
//        //manualInputChecker(globalLight, $('#ins_input_city').val());
//    }
//    // ------ ARROW KEY NAV ------ //
//    if($('#customlist').css('display') === 'block'){
//        switch(e.which){
//            case 38: //Up
//                index--;
//                if(index < 1){index = 1;}
//                //$('.city-lis-elm:nth-child('+ index +')').trigger('hover');
//                $('.city-lis-elm:nth-child('+ index +')').addClass('city-lis-elm-hover-emulation');
//                break;
//            case 40: //Down
//                index++;
//                if(index > $('#customlist .city-lis-elm').length){index = $('#customlist .city-lis-elm').length;}
//                //$('.city-lis-elm:nth-child('+ index +')').trigger('hover');
//                $('.city-lis-elm:nth-child('+ index +')').addClass('city-lis-elm-hover-emulation');
//                break;
//            /*case 27: //Esc
//                console.log('tata');
//                $('#customlist').hide();
//                e.preventDefault();
//                break;*/
//            case 13: //Enter
//                $('.city-lis-elm:nth-child('+ index +')').trigger('mousedown');
//                $('#customlist').hide();
//                break;
//        }
//    } else if($('#customlistmulti').css('display') === 'block'){
//        switch(e.which){
//            case 38: //Up
//                index--;
//                if(index < 1){index = 1;}
//                $('.city-lis-elm:nth-child('+ index +')').addClass('city-lis-elm-hover-emulation');
//                break;
//            case 40: //Down
//                index++;
//                if(index > $('#customlistmulti .city-lis-elm').length){index = $('#customlistmulti .city-lis-elm').length;}
//                $('.city-lis-elm:nth-child('+ index +')').addClass('city-lis-elm-hover-emulation');
//                break;
//            /*case 27: //Esc
//                console.log('titi');
//                $('#customlistmulti').hide();
//                e.preventDefault();
//                break;*/
//            case 13: //Enter
//                $('.city-lis-elm:nth-child('+ index +') .customlist_choices').trigger('mousedown');
//                $('#customlist').hide();
//                break;
//        }
//    }
//});

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

var index = 0;
$('#ins_input_city').keyup(function(e) {
    delay(function(){
        //On cache le tableau de villes
        $('#city_table_wrapper').slideUp();
        //reset de cc
        $('#ins_input_city').data('cc', '-1');
        //reset de check
        $('#ins_input_city').data('check', 'true');
        if($('#ins_input_city').val() === ''){
            $('#customlist').hide();
            $('#customlistmulti').hide();
        } else {
            //SPINNER HERE
            $('#ins_city_spinner').show();
            ajaxCitySuggestion();
            //manualInputChecker(globalLight, $('#ins_input_city').val());
        }
        /*
        // ------ ARROW KEY NAV ------ //
        if($('#customlistmulti').css('display') === 'block'){
            
            switch(e.which){
                case 38: //Up
                    --index;
                    if(index < 1){index = 1;}
                    $('.city-lis-elm:nth-child('+ index +')').addClass('city-lis-elm-hover-emulation');
                    break;
                case 40: //Down
                    ++index;
                    if(index > $('#customlistmulti .city-lis-elm').length){index = $('#customlistmulti .city-lis-elm').length;}
                    $('.city-lis-elm:nth-child('+ index +')').addClass('city-lis-elm-hover-emulation');
                    break;
                /*case 27: //Esc
                    console.log('titi');
                    $('#customlistmulti').hide();
                    e.preventDefault();
                    break;
                case 13: //Enter
                    $('.city-lis-elm:nth-child('+ index +') .customlist_choices').trigger('mousedown');
                    $('#customlist').hide();
                    break;
            }
        }
        //*/
    }, 250 );
});