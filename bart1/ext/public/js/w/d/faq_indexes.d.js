/* SUR LOAD DE LA PAGE, ON CHECK LE FICHIER POUR LES REDIRECTIONS CROSS-PAGES */
//$(document).ready(function(){
//    var file = document.location.pathname.match(/[^\/]+$/)[0];
//    switch(file){
//        case 'tech.php':
//            var hash = window.location.hash;
//            switch(hash){
//                case '#tech_connection':
//                    faqDivDisplay('tech_connection');
//                    break;
//                case '#tech_site':
//                    faqDivDisplay('tech_site');
//                    break;
//                case '#tech_acc_crea':
//                    faqDivDisplay('tech_create');
//                    break;
//                case '#tech_acc_secu':
//                    faqDivDisplay('tech_security');
//                    break;
//                default:
//                    faqDivDisplay('tech_connection');
//                    break;
//            }
//            break;
//        case 'fct.php':
//            var hash = window.location.hash;
//            switch(hash){
//                case '#fct_inscription':
//                    faqDivDisplay('fct_inscription');
//                    break;
//                case '#fct_trialacc':
//                    faqDivDisplay('fct_trialacc');
//                    break;
//                case '#fct_connection':
//                    faqDivDisplay('fct_connection');
//                    break;
//                case '#fct_profile':
//                    faqDivDisplay('fct_profile');
//                    break;
//                case '#fct_account':
//                    faqDivDisplay('fct_account');
//                    break;
//                case '#fct_images':
//                    faqDivDisplay('fct_images');
//                    break;
//                case '#fct_famous':
//                    faqDivDisplay('fct_famous');
//                    break;
//                default:
//                    faqDivDisplay('fct_inscription');
//                    break;
//            }
//            break;
//        case 'misc.php':
//            var hash = window.location.hash;
//            switch(hash){
//                case '#misc_lang':
//                    faqDivDisplay('misc_lang');
//                    break;
//                case '#misc_lorem':
//                    faqDivDisplay('misc_lorem');
//                    break;
//                default:
//                    faqDivDisplay('misc_lang');
//                    break;
//            }
//            break;
//        case 'permalink_test.php':
//            $('.faq_left_item').click(function(e){
//                var link = $(this).closest('a');
//                window.location.href = $(link).prop('href');
//            });
//            break;
//            
//        case 'search.php':
//            //r.a.s.
//            break;
//        default:
//            Kxlib_DebugVars([Hmm. This shouldn\'t be happening.'])
//    }
//});


/* Gestion de l'arrivée dans une sous-categ depuis le menu de gauche */
//$('.faq_left_item').click(function(e){
//    e.preventDefault();
//    var targetId = $(e.target).prop('id');
//    faqDivDisplay(targetId);
//});

/* Gestion de l'arrivée dans une sous-categ depuis un categIndex */
//$('.faq_pLink').click(function(e){
//    e.preventDefault();
//    var targetId = $(e.target).prop('id');
//    faqCategRedirect(targetId);
//});

//function faqCategRedirect(id){
//    switch(id){
//        /* TECHNICAL */
//        case 'idxLink_tech_connection':
//            window.location.href = 'tech.php#tech_connection';
//            break;
//        case 'idxLink_tech_site':
//            window.location.href = 'tech.php#tech_site';
//            break;
//        case 'idxLink_tech_acc_crea':
//            window.location.href = 'tech.php#tech_acc_crea';
//            break;
//        case 'idxLink_tech_acc_secu':
//            window.location.href = 'tech.php#tech_acc_secu';
//            break;
//        /* HOWTO */
//        case 'idxLink_fct_inscription':
//            window.location.href = 'fct.php#fct_inscription';
//            break;
//        case 'idxLink_fct_trialacc':
//            window.location.href = 'fct.php#fct_trialacc';
//            break;
//        case 'idxLink_fct_connection':
//            window.location.href = 'fct.php#fct_connection';
//            break;
//        case 'idxLink_fct_profile':
//            window.location.href = 'fct.php#fct_profile';
//            break;
//        case 'idxLink_fct_account':
//            window.location.href = 'fct.php#fct_account';
//            break;
//        case 'idxLink_fct_images':
//            window.location.href = 'fct.php#fct_images';
//            break;
//        case 'idxLink_fct_famous':
//            window.location.href = 'fct.php#fct_famous';
//            break;
//        /* MISCELLANEOUS */
//        case 'idxLink_misc_lang':
//            window.location.href = 'misc.php#misc_lang';
//            break;
//        case 'idxLink_misc_lorem':
//            window.location.href = 'misc.php#misc_lorem';
//            break;
//    }
//}