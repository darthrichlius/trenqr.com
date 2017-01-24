$().ready(function() {
    /* Gestion du scrolling des sidebars */
    var scrollingRightBar = $('#pfl_rightbar');
    var scrollingLeftBar = $('#pfl_leftbar');
    var limiter;
    
    //Le '42' est passé en dur pour le moment mais correspondra à l'id du type connecté
    //session = 42;
    
    //On déclenche le load du premier panel (qui est forcément 'Profil' pour le moment
    //On va switch vers la bonne section
    
    var scpn = $("#page_section").data("section");
    
    if ( scpn !== "" ) {
        switch (scpn) {
            case "profile" :
                    $('#pfl_leftlink_profile').trigger('click');
                break;
            case "account":
                    $('#pfl_leftlink_account').trigger('click');
                break;
            case "security" :
                    $('#pfl_leftlink_security').trigger('click');
                break;
            case "about" :
                    $('#pfl_leftlink_about').trigger('click');
                break;
            default :
                    $('#pfl_leftlink_profile').trigger('click');
                break;
        }
    } else $('#pfl_leftlink_profile').trigger('click');
    
    
    
    $(window).scroll(function(){
        //Vérification pour que les sidebars ne scrollent pas 'hors' de la <div> affichée
        if($('#pfl_rightbar').height() < $('#pfl_leftbar').height()){
            limiter = $('#pfl_leftbar').height();
        } else {
            limiter = $('#pfl_rightbar').height();
        }
        
        //On détermine ce que sera la limite au scroll en fonction de ce qu'on a au dessus
        //Si jamais on vous demande d'où sort ce '25px', répondez que c'est empirique.
        var displayedPanel = displayedPanelOverseer();
        var scrollLimit = panelHeightOverseer(displayedPanel) - limiter + 25;
        var effectiveScroll = $(window).scrollTop();
        
        if(scrollLimit < effectiveScroll){
            effectiveScroll = scrollLimit;
        }
        
        scrollingRightBar
                .stop()
                .animate({'marginTop': effectiveScroll}, 'slow' );
        setTimeout(function(){
            scrollingLeftBar
                    .stop()
                    .animate({'marginTop': effectiveScroll}, 'slow');
        }, 100);
    });
    
    //Vérification initiale du statut de vérification de l'email de l'utilisateur
    ajaxIsEmailConfirmed();
});

/* Détermination de quel panel est affiché */
function displayedPanelOverseer(){
    //On récupère l'état des divs surveillées
    var isContentProfileDisplayed = $('#pfl_content_profile').css('display');
    var isContentAccountDisplayed = $('#pfl_content_account').css('display');
    var isContentSecurityDisplayed = $('#pfl_content_security').css('display');
    var isContentAppearanceDisplayed = $('#pfl_content_appearance').css('display');
    var isContentBlockedAccsDisplayed = $('#pfl_content_blockedaccs').css('display');
    var isContentAboutDisplayed = $('#pfl_content_about').css('display');
    var isFormProfileDisplayed = $('#pfl_form_profile_div').css('display');
    var isFormAccountDisplayed = $('#pfl_form_account_div').css('display');
    var isFormSecurityDisplayed = $('#pfl_form_security_div').css('display');
    var isFormDeleteDisplayed = $('#pfl_delete_account_div').css('display');
    
    // 1 = Profile | 2 = Account | 3 = Security | 4 Appearance
    // 5 = Blocked Accounts | 6 = About | 7 = FormProfile
    // 8 = FormAccount | 9 = FormSecurity | 10 = DeleteAccount
    if(isContentProfileDisplayed === "block"){
        return 1;
    } else if(isContentAccountDisplayed === "block"){
        return 2;
    } else if(isContentSecurityDisplayed === "block"){
        return 3;
    } else if(isContentAppearanceDisplayed === "block"){
        return 4;
    } else if(isContentBlockedAccsDisplayed === "block"){
        return 5;
    } else if(isContentAboutDisplayed === "block"){
        return 6;
    } else if(isFormProfileDisplayed === "block"){
        return 7;
    } else if(isFormAccountDisplayed === "block"){
        return 8;
    } else if(isFormSecurityDisplayed === "block"){
        return 9;
    } else if(isFormDeleteDisplayed === "block"){
        return 10;
    }
}

/* Récupération de la taille de la <div> de contenu courante */
function panelHeightOverseer(displayedPanel){
    var maxHeight;
    switch(displayedPanel){
        case 1:
            maxHeight = $('#pfl_content_profile').height();
            break;
        case 2:
            maxHeight = $('#pfl_content_account').height();
            break;
        case 3:
            maxHeight = $('#pfl_content_security').height();
            break;
        case 4:
            maxHeight = $('#pfl_content_appearance').height();
            break;
        case 5:
            maxHeight = $('#pfl_content_blockedaccs').height();
        case 6:
            maxHeight = $('#pfl_content_about').height();
            break;
        case 7:
            maxHeight = $('#pfl_form_profile_div').height();
            break;
        case 8:
            maxHeight = $('#pfl_form_account_div').height();
            break;
        case 9:
            maxHeight = $('#pfl_form_security_div').height();
            break;
        case 10:
            maxHeight = $('#pfl_delete_account_div').height();
            break;
    }
    return maxHeight;
}

/* Variables globales de gestion des chargement ajax */
var pal = 0;    //Profile
var aal = 0;    //Account
var sal = 0;    //Security

/* Gestion du menu de la LeftBar */
function clickProfile(e){
    $('#pfl_content_profile').css('display', 'none');
    $('#pfl_content_account').css('display', 'none');
    $('#pfl_content_security').css('display', 'none');
    $('#pfl_content_appearance').css('display', 'none');
    $('#pfl_content_blockedaccs').css('display', 'none');
    $('#pfl_form_profile_div').css('display', 'block');
    $('#pfl_form_account_div').css('display', 'none');
    $('#pfl_form_security_div').css('display', 'none');
    $('#pfl_delete_account_div').css('display', 'none');
    $('#pfl_content_about').css('display', 'none');
    $('.pfl_middle_content').css('border-left-color', '#FFA500');
      
    $('#pfl_leftlink_profile').addClass('pfl_leftlink_current');
    $('#pfl_leftlink_account').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_security').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_appearance').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_blockedaccs').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_about').removeClass('pfl_leftlink_current');
    
    if(pal === 0){
        profileLoader(/*session*/); //Param en dur temporaire pour les tests locaux. Sinon, ce sera la session.
        pal++;
    }    
    
    //setCookie('currentpanel', 7, 1);
    displayedPanel = 7;
    e.preventDefault();
}

function clickAccount(e){
    $('#pfl_content_profile').css('display', 'none');
    $('#pfl_content_account').css('display', 'none');
    $('#pfl_content_security').css('display', 'none');
    $('#pfl_content_appearance').css('display', 'none');
    $('#pfl_content_blockedaccs').css('display', 'none');
    $('#pfl_form_profile_div').css('display', 'none');
    $('#pfl_form_account_div').css('display', 'block');
    $('#pfl_form_security_div').css('display', 'none');
    $('#pfl_delete_account_div').css('display', 'none');
    $('#pfl_content_about').css('display', 'none');
    $('.pfl_middle_content').css('border-left-color', '#0BEE2F');
    
    $('#pfl_leftlink_profile').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_account').addClass('pfl_leftlink_current');
    $('#pfl_leftlink_security').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_appearance').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_blockedaccs').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_about').removeClass('pfl_leftlink_current');
    
    if(aal === 0){
        accountLoader(/*session*/); //Même raison que pour profile. Param en dur temporaire.
        //ajaxTryaccountChecker();
        //ajaxIsEmailConfirmed();
        aal++;
    }
    
    //setCookie('currentpanel', 8, 1);
    displayedPanel = 8;
    e.preventDefault();
}

function clickSecurity(e){
    $('#pfl_content_profile').css('display', 'none');
    $('#pfl_content_account').css('display', 'none');
    $('#pfl_content_security').css('display', 'none');
    $('#pfl_content_appearance').css('display', 'none');
    $('#pfl_content_blockedaccs').css('display', 'none');
    $('#pfl_form_profile_div').css('display', 'none');
    $('#pfl_form_account_div').css('display', 'none');
    $('#pfl_form_security_div').css('display', 'block');
    $('#pfl_delete_account_div').css('display', 'none');
    $('#pfl_content_about').css('display', 'none');
    $('.pfl_middle_content').css('border-left-color', '#9188FF');
    
    $('#pfl_leftlink_profile').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_account').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_security').addClass('pfl_leftlink_current');
    $('#pfl_leftlink_appearance').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_blockedaccs').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_about').removeClass('pfl_leftlink_current');
    
    if(sal === 0){
        securityLoader(/*session*/); //Même raison que pour profile. Param en dur temporaire.
        sal++;
    }
    
    //setCookie('currentpanel', 9, 1);
    displayedPanel = 9;
    e.preventDefault();
}

function clickAppearance(e){
    $('#pfl_content_profile').css('display', 'none');
    $('#pfl_content_account').css('display', 'none');
    $('#pfl_content_security').css('display', 'none');
    $('#pfl_content_appearance').css('display', 'block');
    $('#pfl_content_blockedaccs').css('display', 'none');
    $('#pfl_form_profile_div').css('display', 'none');
    $('#pfl_form_account_div').css('display', 'none');
    $('#pfl_form_security_div').css('display', 'none');
    $('#pfl_delete_account_div').css('display', 'none');
    $('#pfl_content_about').css('display', 'none');
    $('.pfl_middle_content').css('border-left-color', '#E2E24F');
    
    $('#pfl_leftlink_profile').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_account').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_security').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_appearance').addClass('pfl_leftlink_current');
    $('#pfl_leftlink_blockedaccs').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_about').removeClass('pfl_leftlink_current');
    
    //setCookie('currentpanel', 4, 1);
    displayedPanel = 4;
    e.preventDefault();
}

function clickBlockedAccs(e){
    $('#pfl_content_profile').css('display', 'none');
    $('#pfl_content_account').css('display', 'none');
    $('#pfl_content_security').css('display', 'none');
    $('#pfl_content_appearance').css('display', 'none');
    $('#pfl_content_blockedaccs').css('display', 'block');
    $('#pfl_form_profile_div').css('display', 'none');
    $('#pfl_form_account_div').css('display', 'none');
    $('#pfl_form_security_div').css('display', 'none');
    $('#pfl_delete_account_div').css('display', 'none');
    $('#pfl_content_about').css('display', 'none');
    $('.pfl_middle_content').css('border-left-color', '#E44F4F');
    
    $('#pfl_leftlink_profile').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_account').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_security').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_appearance').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_blockedaccs').addClass('pfl_leftlink_current');
    $('#pfl_leftlink_about').removeClass('pfl_leftlink_current');
    
    //setCookie('currentpanel', 5, 1);
    displayedPanel = 5;
    //Loading des comptes bloqués (pas nécessaire avant)
    //Bornes initiales: 0 - 20, et seulement si la liste est vide de base.
    if($('#bloacc_listzone').children().length === 0){blockedAccountsFiller(0, 20);}
    e.preventDefault();
}


function clickAbout(e){
    $('#pfl_content_profile').css('display', 'none');
    $('#pfl_content_account').css('display', 'none');
    $('#pfl_content_security').css('display', 'none');
    $('#pfl_content_appearance').css('display', 'none');
    $('#pfl_content_blockedaccs').css('display', 'none');
    $('#pfl_form_profile_div').css('display', 'none');
    $('#pfl_form_account_div').css('display', 'none');
    $('#pfl_form_security_div').css('display', 'none');
    $('#pfl_delete_account_div').css('display', 'none');
    $('#pfl_content_about').css('display', 'block');
    $('.pfl_middle_content').css('border-left-color', '#B3B3B3');
    
    $('#pfl_leftlink_profile').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_account').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_security').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_appearance').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_blockedaccs').removeClass('pfl_leftlink_current');
    $('#pfl_leftlink_about').addClass('pfl_leftlink_current');
    
    //setCookie('currentpanel', 6, 1);
    displayedPanel = 6;
    e.preventDefault();
}

//Gestion du 'retour' de la page de suppression
$('#pfl_delete_backlink a').click(function(e){
    clickSecurity(e);
    e.preventDefault();
});

/* Gestion des textes d'information de RightBar*/

//Contenu de l'encart
var infoProfile = Kxlib_getDolphinsValue("p_pfl_msg_profile");
var infoAccount = Kxlib_getDolphinsValue("p_pfl_msg_account");
var infoSecurity = Kxlib_getDolphinsValue("p_pfl_msg_security");
var infoAppearance = Kxlib_getDolphinsValue("p_pfl_msg_appearance");
var infoBlockedAccs = Kxlib_getDolphinsValue("p_pfl_msg_bloacc");
var infoAbout = Kxlib_getDolphinsValue("p_pfl_msg_about");
var infoPflCity = Kxlib_getDolphinsValue("p_pfl_msg_pflcity");
var infoAccPseudo = Kxlib_getDolphinsValue("p_pfl_msg_accpsd");
var infoAccEmail = Kxlib_getDolphinsValue("p_pfl_msg_accemail");
var infoAccLang = Kxlib_getDolphinsValue("p_pfl_msg_acclang");
var infoSecuPasswd = Kxlib_getDolphinsValue("p_pfl_msg_secupw");

//Variable de contrôle pour éviter le réaffichage de la même info
var lastInfoDisplayed = null;
//Fonction d'affichage dans l'encart
function pfl_infoDisplay(info){
    if(lastInfoDisplayed !== info){
        $('#pfl_infomsg').fadeOut(500, function(){
            $('#pfl_infomsg').html(info);
            $('#pfl_infomsg').fadeIn(500);
        });
        lastInfoDisplayed = info;
    } else {
        stop();
    }
}

/* Gestion des champs des formulaires entre les pages */
//Mise en place d'un troisième statut de data-type après 'lock' et 'ulock': 'pending'
//Signifie que les données ne doivent pas êtres vérifiées car cela ne concerne pas la page courante.
function setPending(inputForm){
    switch(inputForm){
        case 'profile':
            $('#pfl_input_fullname').data('pfl', 'pending');
            $('#pfl_birthday_date_group').data('pfl', 'pending');
            $('#pfl_input_city').data('pfl', 'pending');
            break;
        case 'account':
            $('#pfl_input_nickname').data('acc', 'pending');
            $('#pfl_input_email').data('acc', 'pending');
            $('#pfl_input_socialarea').data('acc', 'pending');
            $('#pfl_input_oldpw').data('acc', 'pending');
            $('#pfl_input_newpw').data('acc', 'pending');
            $('#pfl_input_newpwconf').data('acc', 'pending');
            break;
        case 'security':
            $('#pfl_hlock_start_group').data('secu', 'pending');
            $('#pfl_hlock_end_group').data('secu', 'pending');
            $('#pfl_dlock_start_group').data('secu', 'pending');
            $('#pfl_dlock_end_group').data('secu', 'pending');
            break;
        //SI AJOUT D'AUTRES MENUS, PENSER À REPORTER DANS LES TRIGGERS
    }
}

//Ré-activation des champs quand on revient sur le pannel
//On repasse tout en 'ulock' et on revérifie tout.
function setActive(inputForm){
    switch(inputForm){
        case 'profile':
            $('#pfl_input_fullname').data('pfl', 'ulock');
            $('#pfl_birthday_date_group').data('pfl', 'ulock');
            $('#pfl_input_city').data('pfl', 'ulock');
            pflProfileGeneralCheck();
            break;
        case 'account':
            $('#pfl_input_nickname').data('acc', 'ulock');
            $('#pfl_input_email').data('acc', 'ulock');
            $('#pfl_input_socialarea').data('acc', 'ulock');
            $('#pfl_input_oldpw').data('acc', 'ulock');
            $('#pfl_input_newpw').data('acc', 'ulock');
            $('#pfl_input_newpwconf').data('acc', 'ulock');
            pflAccountGeneralCheck();
            break;
        case 'security':
            $('#pfl_hlock_start_group').data('secu', 'ulock');
            $('#pfl_hlock_end_group').data('secu', 'ulock');
            $('#pfl_dlock_start_group').data('secu', 'ulock');
            $('#pfl_dlock_end_group').data('secu', 'ulock');
            pflSecurityGeneralCheck();
            break;
        //SI AJOUT D'AUTRES MENUS, PENSER À REPORTER DANS LES TRIGGERS
    }
}

/* Traitement des évènements */
$('#pfl_leftlink_profile').click(function(e){
    pfl_infoDisplay(infoProfile);
    clickProfile(e);
    setActive('profile');
    setPending('account');
    setPending('security');
    //Tous les autres menus seront forcément pending.
});

$('#pfl_leftlink_account').click(function(e){
    pfl_infoDisplay(infoAccount);
    clickAccount(e);    
    setActive('account');
    setPending('profile');
    setPending('security');
    //Tous les autres menus seront forcément pending.
});

$('#pfl_leftlink_security').click(function(e){
    pfl_infoDisplay(infoSecurity);
    clickSecurity(e); 
    setActive('security');
    setPending('account');
    setPending('profile');
    //Même si ce n'est pas nécessaire du point de vue des champs à vérifier,
    //on va quand même mettre un setActive pour la sécurité dans un soucis
    //d'évolutivité.
});

$('#pfl_leftlink_appearance').click(function(e){
    pfl_infoDisplay(infoAppearance);
    clickAppearance(e);    
});

$('#pfl_leftlink_blockedaccs').click(function(e){
    pfl_infoDisplay(infoBlockedAccs);
    clickBlockedAccs(e);
    setPending('account');
    setPending('profile');
    setPending('security');
    profileErrorChecker();
    AccountErrorChecker();
    securityErrorChecker();
    //On 'désactive' les autres panels, et on refait leurs vérifications respectives
    //pour cacher l'affichage des erreurs
});

$('#pfl_leftlink_about').click(function(e){
    pfl_infoDisplay(infoAbout);
    clickAbout(e);    
});

/* -- Infos sur les focus -- */
$('#pfl_input_city').focus(function(){
    pfl_infoDisplay(infoPflCity);
});

$('#pfl_input_nickname').focus(function(){
    pfl_infoDisplay(infoAccPseudo);
});

$('#pfl_input_email').focus(function(){
    pfl_infoDisplay(infoAccEmail);
});

$('#pfl_lang').focus(function(){
    pfl_infoDisplay(infoAccLang);
});

$('#pfl_input_oldpw, #pfl_input_newpw, #pfl_input_newpwconf').focus(function(){
    pfl_infoDisplay(infoSecuPasswd);
});

/* -- Reset des infos au sortir des focus -- */
$('#pfl_form_profile_div').click(function(e){
    if($(e.target).prop('id') !== 'pfl_input_city'){
        pfl_infoDisplay(infoProfile);
    }
});

$('#pfl_form_account_div').click(function(e){
    var id = $(e.target).prop('id');
    if(id !== 'pfl_input_nickname' && id !== 'pfl_input_email' && id !== 'pfl_lang' && id !== 'pfl_input_oldpw' && id !== 'pfl_input_newpw' && id !== 'pfl_input_newpwconf'){
        pfl_infoDisplay(infoAccount);
    }
});

/* Gestion des hints dans la LeftBar */
//Contenu des hints
var hintProfile = Kxlib_getDolphinsValue("p_pfl_hint_profile");
var hintAccount = Kxlib_getDolphinsValue("p_pfl_hint_account");
var hintSecurity = Kxlib_getDolphinsValue("p_pfl_hint_security");
var hintAppearance = Kxlib_getDolphinsValue("p_pfl_hint_appearance");
var hintBlockedAccs = Kxlib_getDolphinsValue("p_pfl_hint_bloacc");
var hintAbout = Kxlib_getDolphinsValue("p_pfl_hint_about");

//Fonction d'affichage
function pfl_hintDisplay(hint){
    $('#pfl_left_hint').stop(true);
    $('#pfl_left_hint').html(hint);
    $('#pfl_left_hint').fadeToggle(250);
}

//Gestion des triggers
$('#pfl_leftlink_profile').hover(function(){
    setTimeout(function(){pfl_hintDisplay(hintProfile);}, 50);
});
$('#pfl_leftlink_account').hover(function(){
    setTimeout(function(){pfl_hintDisplay(hintAccount);}, 50);
});
$('#pfl_leftlink_security').hover(function(){
    setTimeout(function(){pfl_hintDisplay(hintSecurity);}, 50);
});
$('#pfl_leftlink_appearance').hover(function(){
    setTimeout(function(){pfl_hintDisplay(hintAppearance);}, 50);
});
$('#pfl_leftlink_blockedaccs').hover(function(){
    setTimeout(function(){pfl_hintDisplay(hintBlockedAccs);}, 50);
});
$('#pfl_leftlink_about').hover(function(){
    setTimeout(function(){pfl_hintDisplay(hintAbout);}, 50);
});


/* GESTION DU CHANGEMENT DE CONTENU POUR LA SUPPRESSION DE COMPTE */
$('#pfl_delete_account a').click(function(){
    $('#pfl_form_security_div').css('display', 'none');
    $('#pfl_delete_account_div').css('display', 'block');
    $('input[name=pfl_deactivation_reason]').prop('checked', false);
    deleteDetails();
    displayedPanel = 10;
});

/* ANNEXE: GESTION DE LA BARRE DE MESURE DU PASSWORD */
//Changement de couleur du border de la <div>
$('#pfl_input_newpw').focusin(function(){
    $('#pfl_passwd_str').css('border-color', '#bbb');
});
$('#pfl_input_newpw').focusout(function(){
    $('#pfl_passwd_str').css('border-color', '#dfdfdf');
});
//Remplissage de la barre sur keyUp();
$('#pfl_input_newpw').keyup(function(){
    //Fonction définie dans form.js
    pflPasswdBar($('#pfl_input_newpw'));
});

/* COMPTE: Gestion du mini-form de conversion */
$('#pfl_trial_warning #pfl_trialswitch_btn').click(function(e){
    $('.pfl_trialswitch_form_div').slideDown();
    $(this).slideUp();
    e.preventDefault();
});

/* GENERAL: Gestion des clear des inputs */
$('.clear_input_wrapper .clear_input_link').click(function(e){
    var target = $(e.target).closest('form');
    target.find('input[type=text], input[type=password]').val('');
    target.find('select').prop('selectedIndex', 0);
    target.find('input[type=checkbox]').prop('checked', false);
    
    //On relance les checks correspondant au formulaire pour 'nettoyer' les broder
    //et les data-locks (est-ce nécessaire de partout? Ça va engendrer des tonnes
    //de messages d'erreur pour l'utilisateur...
    switch(target.attr('id')){
        case 'pfl_form_profile':
            pflProfileGeneralCheck();
            break;
        case 'pfl_form_account_classic':
            pflNicknameCheck($('#pfl_input_nickname').val(), true);
            pflEmailCheck($('#pfl_input_email').val(), true);
            pflSocialAreaCheck($('#pfl_input_socialarea').val());
            break;
        case 'pfl_form_account_passwd':
            pflOldPasswdCheck($('#pfl_input_oldpw').val());
            pflNewPasswdCheck($('#pfl_input_newpw').val());
            PflNewPasswdConfCheck($('#pfl_input_newpwconf').val());
            break;
        case 'pfl_form_security':
            break;
        case 'pfl_form_security_locks':
            break;
    }
    e.preventDefault();
});


/* Select All / Deselect All */
$('.select_deselect_wrapper .select_deselect').click(function(e){
    if($(e.target).prop('id') === 'secu_select'){
        var target = $(e.target).closest('form');
        target.find('input[type=checkbox]').prop('checked', true);
    } else if($(e.target).prop('id') === 'secu_deselect'){
        var target = $(e.target).closest('form');
        target.find('input[type=checkbox]').prop('checked', false);
    }
});

/* =========== */
/* = COOKIES = */
/* =========== */
/*function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name) === 0) return c.substring(name.length,c.length);
    }
    return "";
}

function panelSelect(){
    var panel = getCookie('currentpanel');
    switch(panel){
        case '7':
            $('#pfl_leftlink_profile').trigger('click');
            break;
        case '8':
            $('#pfl_leftlink_account').trigger('click');
            break;
        case '9':
            $('#pfl_leftlink_security').trigger('click');
            break;
        case '4':
            $('#pfl_leftlink_appearance').trigger('click');
            break;
        case '5':
            $('#pfl_leftlink_blockedaccs').trigger('click');
            break;
        case '6':
            $('#pfl_leftlink_about').trigger('click');
            break;
        //Default: Profile
        default:
            $('#pfl_leftlink_profile').trigger('click');
            break;
    }
}*/