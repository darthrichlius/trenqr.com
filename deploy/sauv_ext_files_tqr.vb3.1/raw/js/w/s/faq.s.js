/* GESTION DE LA BARRE DE RECHECHE DU HEADER */
$('#faq_search_input_faqhome').focus(function(){
    $(this).stop(true);
    $(this).animate({
        width: '230px',
        backgroundColor: '#fff'
    }, 500);
    $(this).prop('placeholder', '');
});

$('#faq_search_input_faqhome').blur(function(){
    if($(this).val() === ''){
        $(this).stop(true);
        $(this).animate({
            width: '120px',
            backgroundColor: '#fdfdfd'
        }, 500);
        $(this).prop('placeholder', Kxlib_getDolphinsValue("p_faq_search"));
    }
});

$('#faq_search_input').focus(function(){
    $(this).stop(true);
    $(this).animate({
        width: '230px',
        backgroundColor: '#fff'
    }, 500);
    $(this).prop('placeholder', '');
});

$('#faq_search_input').blur(function(){
    if($(this).val() === ''){
        $(this).stop(true);
        $(this).animate({
            width: '138px',
            backgroundColor: '#fdfdfd'
        }, 500);
        $(this).prop('placeholder', Kxlib_getDolphinsValue("p_faq_search"));
    }
});


/*function htmlEntitiesSecure(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&apos;').replace(/=/g, '&equals;').replace(/:/g, '&colon;');
}

$('#faq_search').on('submit', function(){
    $('#faq_search_input').val(htmlEntitiesSecure($('#faq_search_input').val()));
});
$('#faq_search_faqhome').on('submit', function(){
    $('#faq_search_input_faqhome').val(htmlEntitiesSecure($('#faq_search_input_faqhome').val()));
});
$('#faq_middle_search_form').on('submit', function(){
    $('#searchbar_middle').val(htmlEntitiesSecure($('#searchbar_middle').val()));
});*/

$('#faq_search_submit').click(function(){
    $('#faq_search').submit();
});
$('#faq_search_submit_faqhome').click(function(){
    $('#faq_search_faqhome').submit();
});
$('#faq_middlesearch_submit').click(function(){
    $('#faq_middle_search_form').submit();
});


/* ------------------ */

/* Gestion de l'affichage des articles */
$('.faq_q, .faq_u_close').click(function(e){
    e.preventDefault();
    var gp = $(e.target).closest('.faq_full_group');
    if($(gp).data('displayed') === false){
        $(gp).find('.faq_a_display').stop(true).slideDown(400);
        $(gp).find('.faq_q').addClass('bold');
        $(gp).find('.faq_q_bullet').html('&dtrif;');
        $(gp).data('displayed', true);
        $(gp).find('.faq_separator').show();
    } else if($(gp).data('displayed') === true){
        $(gp).find('.faq_a_display').stop(true).slideUp(400, function(){
            $(gp).find('.faq_q').removeClass('bold');
            $(gp).find('.faq_q_bullet').html('&rtrif;');
            $(gp).data('displayed', false);
            $(gp).find('.faq_separator').hide();
        });
    }
    //Essai nucléaire de sélection du dernier .faq_separator de la liste des groupes
    $('.faq_theme_questions_group:last-of-type .faq_full_group:last-of-type .faq_separator').hide();
    //C'est que ça marche en plus o_O
});

/* Fermeture de tous les articles */
$('.faq_close_all').click(function(){
    $('.faq_a_display').stop(true).slideUp(400, function(){
        $('.faq_q').removeClass('bold');
        $('.faq_q_bullet').html('&rtrif;');
        $('.faq_separator').hide();
        $('.faq_full_group').data('displayed', false);
    });
});

/* Changement d'affichage de div dans les pages de contenu */
//function faqDivDisplay(divId){
//    switch(divId){
//        /* GENERAL */
//        case 'faq_left_back':
//            //window.location.href = '../faq_index.php';
//            depthReturn();
//            break;
//        /* TECHNICAL */
//        case 'tech_connection':
//            $('.faq_middle_content').hide();
//            $('#tech_cat_connection').css('display', 'inline-block');
//            $('#faq_left_menu *').removeClass('faq_left_item_current');
//            $('#tech_connection').addClass('faq_left_item_current');
//            $('.breadcrumb_subcat').html('Probl&egrave;mes de connexion');
//            break;
//        case 'tech_site':
//            $('.faq_middle_content').hide();
//            $('#tech_cat_site').css('display', 'inline-block');
//            $('#faq_left_menu *').removeClass('faq_left_item_current');
//            $('#tech_site').addClass('faq_left_item_current');
//            $('.breadcrumb_subcat').html('Probl&egrave;mes sur le site');
//            break;
//        case 'tech_create':
//            $('.faq_middle_content').hide();
//            $('#tech_cat_acc_crea').css('display', 'inline-block');
//            $('#faq_left_menu *').removeClass('faq_left_item_current');
//            $('#tech_create').addClass('faq_left_item_current');
//            $('.breadcrumb_subcat').html('Probl&egrave;mes de cr&eacute;ation de compte');
//            break;
//        case 'tech_security':
//            $('.faq_middle_content').hide();
//            $('#tech_cat_acc_secu').css('display', 'inline-block');
//            $('#faq_left_menu *').removeClass('faq_left_item_current');
//            $('#tech_security').addClass('faq_left_item_current');
//            $('.breadcrumb_subcat').html('S&eacute;curit&eacute; du compte');
//            break;
//        /* HOWTO */
//        case 'fct_inscription':
//            $('.faq_middle_content').hide();
//            $('#fct_cat_inscription').css('display', 'inline-block');
//            $('#faq_left_menu *').removeClass('faq_left_item_current');
//            $('#fct_inscription').addClass('faq_left_item_current');
//            $('.breadcrumb_subcat').html('Inscription');
//            break;
//        case 'fct_trialacc':
//            $('.faq_middle_content').hide();
//            $('#fct_cat_trialacc').css('display', 'inline-block');
//            $('#faq_left_menu *').removeClass('faq_left_item_current');
//            $('#fct_trialacc').addClass('faq_left_item_current');
//            $('.breadcrumb_subcat').html('Comptes d\'essai');
//            break;
//        case 'fct_connection':
//            $('.faq_middle_content').hide();
//            $('#fct_cat_connection').css('display', 'inline-block');
//            $('#faq_left_menu *').removeClass('faq_left_item_current');
//            $('#fct_connection').addClass('faq_left_item_current');
//            $('.breadcrumb_subcat').html('Connexion');
//            break;
//        case 'fct_profile':
//            $('.faq_middle_content').hide();
//            $('#fct_cat_profile').css('display', 'inline-block');
//            $('#faq_left_menu *').removeClass('faq_left_item_current');
//            $('#fct_profile').addClass('faq_left_item_current');
//            $('.breadcrumb_subcat').html('Mon profil');
//            break;
//        case 'fct_account':
//            $('.faq_middle_content').hide();
//            $('#fct_cat_account').css('display', 'inline-block');
//            $('#faq_left_menu *').removeClass('faq_left_item_current');
//            $('#fct_account').addClass('faq_left_item_current');
//            $('.breadcrumb_subcat').html('Mon compte');
//            break;
//        case 'fct_images':
//            $('.faq_middle_content').hide();
//            $('#fct_cat_images').css('display', 'inline-block');
//            $('#faq_left_menu *').removeClass('faq_left_item_current');
//            $('#fct_images').addClass('faq_left_item_current');
//            $('.breadcrumb_subcat').html('Images');
//            break;
//        case 'fct_famous':
//            $('.faq_middle_content').hide();
//            $('#fct_cat_famous').css('display', 'inline-block');
//            $('#faq_left_menu *').removeClass('faq_left_item_current');
//            $('#fct_famous').addClass('faq_left_item_current');
//            $('.breadcrumb_subcat').html('Personnalit&eacute;s publiques et c&eacute;l&eacute;brit&eacute;s');
//            break;
//        /* MISCELLANEOUS */
//        case 'misc_lang':
//            $('.faq_middle_content').hide();
//            $('#misc_cat_lang').css('display', 'inline-block');
//            $('#faq_left_menu *').removeClass('faq_left_item_current');
//            $('#misc_lang').addClass('faq_left_item_current');
//            $('.breadcrumb_subcat').html('Langues et traduction');
//            break;
//        case 'misc_lorem':
//            $('.faq_middle_content').hide();
//            $('#misc_cat_lorem').css('display', 'inline-block');
//            $('#faq_left_menu *').removeClass('faq_left_item_current');
//            $('#misc_lorem').addClass('faq_left_item_current');
//            $('.breadcrumb_subcat').html('Lorem Ipsum Dolor Sit Amet');
//            break;
//    }
//}

/* -------------------------------------------------------------------------- */
/* ESTHETIQUE */
/* Scroll top */
$(document).ready(function() {
    var offset = 220;
    var duration = 250;
    $(window).scroll(function() {
        if ($(this).scrollTop() > offset) {
            $('.faq_backToTop').fadeIn(duration);
        } else {
            $('.faq_backToTop').fadeOut(duration);
        }
         
       /*
        * [DEPUIS 17-09-15] @author BOR
        */
        if ( $(this).scrollTop() > 300) {
            var newtop = $(this).scrollTop()-75;
            $("#faq-gofuther-bmx").css('top', newtop + 'px');
        }
    });
    
    $('.faq_backToTop').click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, 500);
        return false;
    });
    
    /* Gestion des breadcrumbs grâce au data-binding */
    $('.faq_breadcrumb .breadcrumb_cat').html($('.faq_middle_content').data('bc'));
    
    if($('.faq_middle_content').data('bcs') !== 'none'){
        $('.faq_breadcrumb .breadcrumb_subcat').html($('.faq_middle_content').data('bcs'));
    } else {
        $('.faq_breadcrumb .breadcrumb_subcat').css('display', 'none');
        $('.faq_breadcrumb .breadcrumb_subcat_bullet').css('display', 'none');
    }
    
    /* - - - - - - - - */
    /* Gestion de la classe 'current' */
    var crt = $('.faq_middle_content').data('current');
    switch(crt){
        case 'cgu':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#toIndex_cgu').addClass('faq_left_item_current');
            break;
        case 'cookies':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#toIndex_cookies').addClass('faq_left_item_current');
            break;
        case 'philosophy':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#toIndex_philosophy').addClass('faq_left_item_current');
            break;
        case 'privacy':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#toIndex_privacy').addClass('faq_left_item_current');
            break;
        case 'misc_idx':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#toIndex_misc').addClass('faq_left_item_current');
            break;
        case 'fct_idx':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#toIndex_fct').addClass('faq_left_item_current');
            break;
        case 'tech_idx':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#toIndex_tech').addClass('faq_left_item_current');
            break;
        /* ...---... */
        case 'misc_lorem':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#misc_lorem').addClass('faq_left_item_current');
            break;
        case 'misc_lang':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#misc_lang').addClass('faq_left_item_current');
            break;
        /* ...---... */
        case 'tech_site':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#tech_site').addClass('faq_left_item_current');
            break;
        case 'tech_connection':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#tech_connection').addClass('faq_left_item_current');
            break;
        case 'tech_acc_crea':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#tech_acc_crea').addClass('faq_left_item_current');
            break;
        /* ...---... */
        case 'fct_account':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#fct_account').addClass('faq_left_item_current');
            break;
        case 'fct_inscription':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#fct_inscription').addClass('faq_left_item_current');
            break;
        case 'fct_trial':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#fct_trial').addClass('faq_left_item_current');
            break;
        case 'fct_connection':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#fct_connection').addClass('faq_left_item_current');
            break;
        case 'fct_profile':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#fct_profile').addClass('faq_left_item_current');
            break;
        case 'fct_images':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#fct_images').addClass('faq_left_item_current');
            break;
        case 'fct_famous':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#fct_famous').addClass('faq_left_item_current');
            break;
        case 'fct_acc_secu':
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            $('#fct_acc_secu').addClass('faq_left_item_current');
            break;
        /* ...---... */
        default:
            $('#faq_left_menu li').removeClass('faq_left_item_current');
            break;
    }
    
});

/* :hover animé sur les zones de texte spéciales (.faq_spec_text) */
$('.faq_spec_text').hover(function(){
    $(this).stop(true);
    $(this).animate({
        backgroundColor: '#f3f1ee',
        borderColor: '#ccc'
    }, 200);
}, function(){
    $(this).stop(true);
    $(this).animate({
        backgroundColor: '#fffdfa',
        borderColor: '#ddd'
    }, 200);
});

/* :hover animé sur les zones de satisfaction (.faq_u) */
$('.faq_u').hover(function(){
    var target = $(this).find('*');
    $(this).stop(true);
    $(this).animate({
        backgroundColor: '#ddd',
        borderColor: '#ccc'
    }, 200);
    $(target).stop(true);
//    $(target).animate({
//        color: '#777'
//    });
}, function(){
    var target = $(this).find('*');
    $(this).stop(true);
    $(this).animate({
        backgroundColor: '#eee',
        borderColor: '#ddd'
    }, 200);
    $(target).stop(true);
//    $(target).animate({
//        color: '#999'
//    });
});

/* -_-_-_-_-_-_-_-_- */

/* Gestion de la fonction 'Retour' du menu de la leftbar */
function targetDepthLevel(){
    //On compte le nombre de liens dans la breadcrumb
    var d = $('.faq_breadcrumb a').length;
    return d - 1;
}

function depthReturn(){
    var t = targetDepthLevel();
    if(t === 0){
        window.location.href = 'faq_index.php';
    } else if(t === 1){
        window.location.href = '../faq_index.php';
    } else {
        switch($('.faq_middle_content').data('faqcat')){
            case 1:
                window.location.href = 'index_fct.php';
                break;
            case 2:
                window.location.href = 'index_tech.php';
                break;
            case 3:
                window.location.href = 'index_misc.php';
                break;
        }
    }
}


/* ~~~~~~ */
/* Gestion de la taille de la police */
function resizeFont(c){
    $.each($('*'), function(){
        if($(this).text() !== '' && $(this).children().length === 0){
            var nfs = parseInt($(this).css('font-size')) + parseInt(c);
            $(this).css('font-size', nfs + 'px');
        } else if($(this).prop('class') === 'faq_q' || $(this).prop('class') === 'faq_a' ||  $(this).prop('class') === 'faq_txtresize'){
            var nfs = parseInt($(this).css('font-size')) + parseInt(c);
            //Override pour les questions et les réponses, puisqu'elles contiennent du texte ET des enfants (span)
            //Override également pour certaines classes spéciales
            $(this).css('font-size', nfs + 'px');
        }
    });
}


$('#faq_fontplus').click(function(){
    resizeFont(1);
});

$('#faq_fontminus').click(function(){
    resizeFont(-1);
});

/* ################## */
/* Gestion des opening des contenus ajoutés */
function AdCoShow(e){
    var target = $(e.target).closest('.faq_content_addition');
    //Je sais que ce que je fais ici donne l'impression que je tourne en rond pour pas grand-chose
    //mais je standardise pour que ce soit plus simple à manipuler et à modifier dans le futur
    var opener = target.find('.faq_content_addition_opener');
    var closer = target.find('.faq_content_addition_closer');
    var title = target.find('.faq_content_addition_title');
    var content = target.find('.faq_content_addition_content');
    
    //Animations
    $(content).stop(true);
    $(title).stop(true, true);
    $(target).stop(true);
    
    $(opener).hide();
    $(closer).show();
    $(title).fadeOut(600);
    $(target).animate({
        width: '591px'
    }, 600, function(){
        $(content).slideDown(600);
    });
    
}

function AdCoHide(e){
    var target = $(e.target).closest('.faq_content_addition');
    var inl = target.find('.faq_content_addition_title').width();
    
    //Je sais que ce que je fais ici donne l'impression que je tourne en rond pour pas grand-chose
    //mais je standardise pour que ce soit plus simple à manipuler et à modifier dans le futur
    var opener = target.find('.faq_content_addition_opener');
    var closer = target.find('.faq_content_addition_closer');
    var title = target.find('.faq_content_addition_title');
    var content = target.find('.faq_content_addition_content');
    
    //Animations
    $(content).stop(true);
    $(title).stop(true, true);
    $(target).stop(true);
    
    $(content).slideUp(600, function(){
        $(title).fadeIn(600);
        $(opener).show();
        $(closer).hide();
        $(target).animate({
            width: inl +'px'
        }, 600);
    });
}

$('.faq_content_addition_opener').click(function(e){
    AdCoShow(e);
});

$('.faq_content_addition_closer').click(function(e){
    AdCoHide(e);
});

/* Gestion de la remontée au clic sur le breadcrumb */
$('.breadcrumb_cat').click(function(e){
    e.preventDefault();
    depthReturn();
});
