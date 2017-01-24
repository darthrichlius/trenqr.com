<?php
/**
 * <p>This file containes all the definition of MAGIC CONSTANTS defined by the user.
 * If you want to define a new MAGIC CONSTANTS for you Product, make sure it's defined right here.</p>
 */
//*
define("NOT_DEFINED","not_defined");

//Params for ADMIN
define("ADMIN_EMAIL","lou.carther@deuslynn-entreprise.com");

//EXC = EXecutionCode
/* No, errors are handle by err_handler
define("EXC_KN_FE", "known_fatal_error");
define("EXC_UKN_F_E", "unknown_fatal_error");
 */
define("EXC_ABORT", "abort");
define("EXC_YES", "yes");
define("EXC_GO_ON", "go_on");
define("EXC_NO", "no");
define("EXC_SKIP", "skip");

//RM = Running Mode, P = Production
define("P_RM_PRM","P_RM_PRM"); //NO ERRORS ALLOWED HERE
define("P_RM_CRM","P_RM_CRM"); //NO ERRORS ALLOWED HERE
define("P_RM_MRM","P_RM_MRM"); //NO ERRORS ALLOWED HERE
define("P_RM_QRM","P_RM_QRM"); //NO ERRORS ALLOWED HERE
//Out of Production, T = Test
define("T_RM_TRM","T_RM_TRM"); //We want to see E_NOTICE and E_WARNING 
define("T_RM_DBRM","T_RM_DBRM"); //We want to see E_ALL

//DATA TYPES
define("WOS_DATA_TYPE_HASHTAG", "hashtag");
define("WOS_DATA_TYPE_EMAIL", "email");
define("WOS_DATA_TYPE_PASS", "password");
define("WOS_DATA_TYPE_LOGIN", "login");
define("WOS_DATA_TYPE_TEL", "phone");
define("WOS_DATA_TYPE_DATE", "date");
define("WOS_DATA_TYPE_STD", "std");


//IMAGE INFOS
//Constante symbolisant un carré. Ce nombre arbritaire.
define("WOS_IMG_IS_SQUARE",99);
//Constante symbolisant un carré. Ce nombre arbritaire.
define("WOS_IMG_IS_RECT",95);
//Constante signalant qu'il n'y a aucune restriction de forme
define("WOS_IMG_ALLFORMAT",1);

//Constante symbolisant le type PNG
define("WOS_IMGTYPE_PNG","png");
//Constante symbolisant le type GIF
define("WOS_IMGTYPE_GIF","gif");
//Constante symbolisant le type JPEG (JPG)
define("WOS_IMGTYPE_JPEG","jpg");
//Constante symbolisant le type JPG
define("WOS_IMGTYPE_JPG","jpg");

//ERR = ERROR
define("ERR_SYS_1","err_sys_1");
define("ERR_SYS_2","err_sys_2");
define("ERR_USER_1","err_user_1");
define("ERR_USER_2","err_user_2");

//TEXT_HANDLER
define("WOS_TXTPAT_DEFAULT",1);

/* We don't need in for now. We're using system log
//LGC = LOG CODE
define("LGC_NODE","orange");
define("LGC_CMD","blue");
define("LGC_ANSW_ERR","red");
define("LGC_ANSW_STD","green");
define("LGC_STD","black");
*/

/************************ SERVER PARAMS ************************/
//Current running mode
//define("PTF_RM",T_RM_TRM);
/*
require_once WOS_PATH_INC_FMK_LIB;
require_once WOS_PATH_INC_INDEX_PACK;
require_once WOS_PATH_INC_TOOLS;
require_once WOS_PATH_DEF_CONF_FILE;
*/

/********************************** WOS CONF **************************/
define ("PROD_CONF_FILE",RACINE."/system/conf/conf.prod.conf.xml");


/*********************************** LIB ******************************/
//PARENTS
define("WOS_PATH_STD", RACINE."/common/lib/common/lib.standard.lib.class.php");
define("WOS_PATH_FMK_PAR_MOTH", RACINE."/common/lib/parents/lib.par.mother.lib.class.php");
define("WOS_PATH_FMK_PAR_DVT", RACINE."/common/lib/parents/lib.par.dvt.lib.class.php");
define("WOS_PATH_FMK_PAR_SRVC", RACINE."/common/lib/parents/lib.par.service.lib.class.php");
define("WOS_PATH_FMK_PAR_ENTY", RACINE."/common/lib/parents/lib.par.service.lib.class.php");

//VIEW
define("WOS_PATH_FMK_VIEW_PAGE", RACINE."/common/lib/view/lib.view.page.lib.class.php");
define("WOS_PATH_FMK_VIEW_DVT", RACINE."/common/lib/view/lib.view.dvt.lib.class.php");
define("WOS_PATH_FMK_VIEW_HEADTMPLT_BUILDER", RACINE."/common/lib/view/lib.headtmplt_builder.lib.class.php");

//DATA
define("WOS_PATH_FMK_DATA_QUERY", RACINE."/common/lib/data/lib.data.query.lib.class.php");

//COMMONS
define("WOS_PATH_LIB_COMS_WTO",RACINE."/common/lib/common/lib.com.wto.lib.class.php");
define("WOS_PATH_LIB_COMS_SESSION",RACINE."/common/lib/common/lib.com.session.lib.class.php");
define("WOS_PATH_LIB_COMS_RSESSION",RACINE."/common/lib/common/lib.com.sto_rest_infos.lib.class.php");
define("WOS_PATH_LIB_COMS_SPOKLG",RACINE."/rdcs.fmk/lib/commons/lib.com.spoken_lang.rdcs.lib.php");


//RULES
define("WOS_PATH_LIB_RULES_AUC_DATA",RACINE."/rdcs.fmk/lib/rules/lib.rule.auc_data.rdcs.lib.php");
define("WOS_PATH_LIB_RULES_AUC_VIEW",RACINE."/rdcs.fmk/lib/rules/lib.rule.auc_view.rdcs.lib.php");
define("WOS_PATH_LIB_RULES_ACTOR",RACINE."/rdcs.fmk/lib/rules/lib.rule.actor_child.rdcs.lib.php");
define("WOS_PATH_LIB_RULES_PROD_RULES",RACINE."/rdcs.fmk/lib/rules/lib.rule.prod_rules.rdcs.lib.php");


//UTILITIES
define("WOS_PATH_LIB_UTILS_DB",RACINE."/common/lib/utilities/lib.utility.database.lib.class.php");
define("WOS_PATH_LIB_UTILS_HASH",RACINE."/common/lib/utilities/lib.utility.hash.lib.class.php");

//WORKERS_COMMON
//define("WOS_PATH_WORKERS_DEFAULT",RACINE."/common/lib/workers/lib.default_process.lib.class.php");

//AUC --- ACTORS
define("WOS_PATH_LIB_AUC_ACTORS",RACINE."/rdcs.fmk/lib/auc/actors/lib.auc.actor.rdcs.lib.php");
define("WOS_PATH_LIB_AUC_ACTORS_AS_FT",RACINE."/rdcs.fmk/lib/auc/actors/lib.auc.actor_as_ftpfl.rdcs.lib.php");
define("WOS_PATH_LIB_AUC_ACTORS_AS_ACC",RACINE."/rdcs.fmk/lib/auc/actors/lib.auc.actor_as_acc.rdcs.lib.php");


//AUC --- CONTACT
define("WOS_PATH_LIB_AUC_REL",RACINE."/rdcs.fmk/lib/auc/actors/lib.auc.relation.rdcs.lib.php");
define("WOS_PATH_LIB_AUC_CONTACT_LTPFL",RACINE."/rdcs.fmk/lib/auc/contact/lib.auc.lite_profil.rdcs.lib.php");
define("WOS_PATH_LIB_AUC_CONTACT_FTPFL",RACINE."/rdcs.fmk/lib/auc/contact/lib.auc.ftprofil.rdcs.lib.php");
define("WOS_PATH_LIB_AUC_CONTACT_GNR",RACINE."/rdcs.fmk/lib/auc/contact/lib.auc.greener.rdcs.lib.php");
define("WOS_PATH_LIB_AUC_CONTACT_ACC",RACINE."/rdcs.fmk/lib/auc/contact/lib.auc.account.rdcs.lib.php");
define("WOS_PATH_LIB_AUC_CONTACT_GW",RACINE."/rdcs.fmk/lib/auc/contact/lib.auc.gowas.rdcs.lib.php");


//INCLUDERS  
define("WOS_PATH_INC_INDEX_PACK",RACINE."/system/factories/index/fact.index.fact.php"); 

define("WOS_PATH_INC_FMK_LIB",RACINE."/system/includers/com.fmk_lib_par.inc.php"); 
define("WOS_PATH_INC_TOOLS", RACINE."/system/includers/com.tools.inc.php");
define("WOS_PATH_INC_SRVCS", RACINE."/system/includers/com.services.inc.php");
define("WOS_PATH_INC_FACTS", RACINE."/system/includers/com.factories.inc.php");
define("WOS_PATH_INC_CONTROLLER_ENGS",RACINE."/system/factories/fact.controller/controller.engines.inc.php");
define("WOS_PATH_INC_WORKERS",RACINE."/system/includers/com.workers.inc.php");
define("WOS_PATH_INC_ENTITIES",RACINE."/product/entities/com.entities.inc.php");

//DEFINERS
//define("WOS_PATH_DEF_CONF_FILE", RACINE."/rdcs.system/common/com.min_prod_confs.rdcs.def.php");
define("WOS_PATH_DEF_LANG", RACINE."/system/conf/com.err_tabs_files.def.php");


//SYSTEM COMMON PHYSICAL PAGES
define("WOS_SPAGE_COUNTDOWN_PAGE", RACINE."/common/pages/page.countdown.page.php");  
define("WOS_SPAGE_MAINTENANCE_PAGE", RACINE."/common/pages/page.maintenance.page.php"); 
define("WOS_SPAGE_QUARANTINE_PAGE", RACINE."/common/pages/page.quarantine.page.php"); 
define("WOS_SPAGE_FORUSER_ERR_DISPLAYER_PAGE", RACINE."/common/pages/page.err_displayer.page.php");


//PAGETABLE
define("WOS_PATH_TAB_PBTAB","/rdcs.prod/pac/common/view/page/pgtab/tab.global.pgtab.rdcs.tab.xml");


//******************* GENERAL VIEW PATH
define("WOS_GEN_PATH_VPARSER", RACINE."/system/services/srvc.vparser.srvc.php");
/****** SKELETON */
define("WOS_GEN_PATH_PAGES_MODELS_DEF", RACINE."/product/view/def/model/def.pages_models.def.xml");
define("WOS_GEN_PATH_PAGES_MODELS_REPOS", RACINE."/product/view/repos/skeleton/");
define("WOS_PAGES_MODELS_EXT", ".sklt.php");

//TODO : Préciser cette section car elle a l'air ambigue.
if ( ( defined("IS_DEBUG") and IS_DEBUG == TRUE ) && !defined("RIGHT_IS_DEBUG") ) {
    define("WOS_GEN_PATH_DVTFILES_CSS", RACINE."/public/v.files/css/d/");
    define("WOS_GEN_PATH_DVTFILES_JS", RACINE."/public/v.files/js/d/");
    define("WOS_GEN_PATH_MODELFILES_CSS", RACINE."/public/v.files/css/s/");
    define("WOS_GEN_PATH_MODELFILES_JS", RACINE."/public/v.files/js/s/");
} else if ( defined("RIGHT_IS_DEBUG") and RIGHT_IS_DEBUG == TRUE ) {
    define("WOS_GEN_PATH_DVTFILES_CSS", RACINE."/public/v.files/css/d/");
    define("WOS_GEN_PATH_DVTFILES_JS", RACINE."/public/v.files/js/d/");
    define("WOS_GEN_PATH_MODELFILES_CSS", RACINE."/public/v.files/css/s/");
    define("WOS_GEN_PATH_MODELFILES_JS", RACINE."/public/v.files/js/s/");
} else {
    define("WOS_GEN_PATH_DVTFILES_CSS", EXT_FILES_ROOT."/public/v.files/css/d/");
    define("WOS_GEN_PATH_DVTFILES_JS", EXT_FILES_ROOT."/public/v.files/js/d/");
    define("WOS_GEN_PATH_MODELFILES_CSS", EXT_FILES_ROOT."/public/v.files/css/s/");
    define("WOS_GEN_PATH_MODELFILES_JS", EXT_FILES_ROOT."/public/v.files/js/s/");
}

/*******************************************************************/
/*********************** SYSDIR PATH URL ***************************/
/*
 *  Définir un chemin définitif pour le stockage des images appartenant au produit
 */
//define("WOS_SYSDIR_PRODIMAGE", HTTP_RACINE."/bart1/timg/files/img"); 
define("WOS_SYSDIR_PRODIMAGE", WOS_MAIN_HTTPS_HOST."/bart1/timg/files/img"); 
define("WOS_SYSDIR_PRODIMAGE_X_DPPIC", WOS_MAIN_HTTP_HOST."/bart1/timg/files/img");  
define("WOS_SYSDIR_PRODIMAGE_X_DPPIC_2", "/bart1/timg/files/img");  
/*
 * [NOTE 14-08-14]
 * Sauvegarde des fichiers que l'on ne sait pas où placer. Cela arrive lorsque pour écrire l'image CALLER a omis de donner un path.
 * Cela ne devrait jamais arriver.
 * Cependant, on pourrait y mettre des images quand on ne sait pas trop où les placer. (A proscrire)
 */
define("WOS_SYSDIR_PROD_ORPHANSIMAGE", RACINE."ofiles/");
/*
 * Le chemin vers le dossier où on stocke toutes les images des Articles.
 * La suite est forcement .../user-eid/
 */
define("WOS_SYSDIR_PROD_TMP_DIR", RACINE."/temp/prod/");
//define("WOS_SYSDIR_PROD_ARTIMAGE_SRV1", "/tqim/article/");
define("WOS_SYSDIR_PROD_ARTIMAGE_SRV1", WOS_MAIN_SUBDOMAIN_ROOTPA."/marge1/tqim/article/");
//define("WOS_SYSDIR_PROD_ARTIMAGE_SRV2", "/tqim/article/");
/*
 * [DEPUIS 17-11-15] @author BOR
 */
//define("WOS_SYSDIR_PROD_ARTIMAGE_SRV2", WOS_MAIN_SUBDOMAIN_ROOTPA."/marge1/tqim/article/");
define("WOS_SYSDIR_PROD_ARTIMAGE_SRV4", WOS_MAIN_SUBDOMAIN_ROOTPA."/marge1/tqim/article/");

/*
 * Le chemin vers le dossier où on stocke toutes les images liées à des images de couverture.
 * La suite est forcement 
 *      (1) .../trend/user-eid/  => Dans le cas des images de couverture de Tendances
 *      (2) .../user/user-eid => Dans le cas des images de couverture de compte
 */
//define("WOS_SYSDIR_PROD_COVER", WOS_SYSDIR_PROD_GLOBAL_USERIMG."cover/");
define("WOS_SYSDIR_PROD_COVER", WOS_MAIN_SUBDOMAIN_ROOTPA."/marge1/tqim/cvu/");
//define("WOS_SYSDIR_PROD_COVER","/tqim/cvu/");
define("WOS_SYSDIR_PROD_COVER_TR", WOS_MAIN_SUBDOMAIN_ROOTPA."/marge1/tqim/cvt/");
//define("WOS_SYSDIR_PROD_COVER_TR","/tqim/cvt/");
/*
 * Le chemin vers le dossier où on stocke toutes les images liées à profil de l'utilisateur.
 * La suite est forcement : [pdpic_prod_name]
 */
//define("WOS_SYSDIR_PROD_PFLPIC", WOS_SYSDIR_PROD_GLOBAL_USERIMG."user/");
define("WOS_SYSDIR_PROD_PFLPIC", WOS_MAIN_SUBDOMAIN_ROOTPA."/marge1/tqim/user/");  
//define("WOS_SYSDIR_PROD_PFLPIC", "/tqim/user/");

define("TQR_DEFAULT_SERVER_NAME", "marge1");
define("TQR_WH2_SERVER_NAME", "lisa1");

define("WOS_SYSDIR_STYLESHEET", EXT_FILES_ROOT."/css");
define("WOS_SYSDIR_SCRIPT", EXT_FILES_ROOT."/js");

/*******************************************************************/


define("WOS_GEN_PATH_PAGEDEF", RACINE."/product/view/def/page/def.pages.def.xml");
//define("WOS_GEN_PATH_PAGES_NAMES_DEF",RACINE."/pages/definition/pages_names/def.pages_names.def.xml");

/****** DVT */
define("WOS_DVTDEF_FILE", RACINE."/product/view/def/dvt/def.dvt.def.xml");
define("WOS_GEN_PATH_DVTSTRUCT_REPOS", RACINE."/product/view/repos/dvt/");

define("WOS_GEN_PATH_DVTSTRUCT_EXT", ".d.php");
//*** CSAM */ 
define("WOS_GEN_PATH_CSAMTRUCT_REPOS", RACINE."/product/view/repos/csam/");
define("WOS_GEN_PATH_CSAMSTRUCT_EXT", ".csam.php");

/*** DECO */
//Il faut completer avec /{lang}/WOS_PATH_DECO_DEF_FILE
define("WOS_GEN_PATH_TO_DECODEF_REPOS", RACINE."/product/data/text/");
define("WOS_DECODEF_FILE", "def.deco.def.xml");

/*** URL */
define("WOS_GEN_PATH_TO_DECODEF_FILE", RACINE."/product/data/url/def.url.def.xml");

/*** EMAIL */
//Il faut completer avec /{lang}/WOS_PATH_DECO_DEF_FILE
define("WOS_GEN_PATH_TO_EMMDLDEF_REPOS", RACINE."/product/data/email/");
define("WOS_EMMDLDEF_FILE", "def.email_models.def.xml");
    
//Vars
define("HUMAN_MODE_ACTIVATE",TRUE);
define("HUMAN_MODE_DISABLE",FALSE);

//****************** GENERAL DATA PATH
define("WOS_GEN_PATH_DEPOT_QUERIES", RACINE."/product/data/queries.def.xml");
define("WOS_GEN_PATH_QPARAMS_HANDLERS", RACINE."/pages/layer.data/qparam_handler_files/");

//****************** GENERAL PROCESS PATH
define("WOS_GEN_PATH_WORKER", RACINE."/common/lib/process/lib.process.worker.lib.class.php");
define("WOS_GEN_PATH_TO_PROCESS_WORKERS", RACINE."/product/process/workers/");
define("WOS_PROCESS_WORKERS_EXT", ".wkr.php");


//***************** ACCESS GRADE 
//## RESTRICTED ##
define("AG_AGDENIED","ag_denied"); //StrictNoAccess (Sauf admin et top admin)
define("AG_AGALLACCESS","ag_allaccess"); //Tout le monde sans distinction

//Ajout au [29-11-13]
//-- Interdit à tous sauf admin et top admin) !!
//-- Dans le cas où l'utilisateur est connecté, il est redirigé vers la page considérée comme rest_prod_page
//-- Si l'utilisateur n'est pas connecté il est renvoyé vers default_welc_prod_page
define("AG_DEN_SR_RPROD","agden_sr_prod");
//-- Pareil que pour '...sr_prod' à la seule difference que si l'utilisateur est connecté, il est redirigé vers own_prod_page
//-- Tous les produits n'ont pas seulement
define("AG_DEN_SR_RPROD2","agden_sr_prod2");
//-- L'utilisateur est redirigé vers la page precedemment demandée present dans STO_INFOS_SPE_CONX. 
//-- Ce cas est souvent demandé lorsque l'user veut accéder à une page REST mais n'est pas co.
//-- En cas de succès, il est redirigé vers la page qu'il voulait
//-- Ce cas étant complexe il n'est utilisé que par SRVC_CONX. C'est pour cela qu'il n'est pas dans STO_INFOS
define("AG_AGALL_SR_NEED","agall_sr_need");

/**
 * NOTE [29-11-13] :
 * Tous les AG de type "VisitorType" (RADMIN, RACC, RTESTER...), en cas de problème, on ne fait aucune redirection.
 * Sauf si le webmaster veut absolument qu'une erreur soit déclenchée. A lui de modifier support.
 * Exemple : Je veux pour x raisons que l'user comprenne qu'il n'a pas le droit d'accéder à page à laquelle il veut accéder !
 */
//AG_RTOPADMIN AG_RADMIN AG_RTESTER AG_RACC AG_RACCONOWN AG_RACCONOWN_SR1 AG_RALL AG_WALL_REST_SR1 AG_WALL_REST_SR2 AG_WIKU AG_WANON AG_WGHOST AG_WALL
define("AG_RTOPADMIN","rtopadmin"); //RestToAdmin
define("AG_RADMIN","radmin"); //RestAdmin
define("AG_RTESTER","rtester"); //RestTester
define("AG_RACC","racc"); //RestAcc(auto si rest_prod_page) 
define("AG_RACCONOWN","racconown"); //RestAccOnOwn(auto si rest_own_page, necessite donc que url_param 'user' soit inséré)
//Pour la page mentionné ci-dessous, elle n'est appelé que si la page encours n'a pas son propre système de confirmation (zone mot_de_passe par exemple)
define("AG_RACCONOWN_SR1","racconown_sr1"); //RestAccOnOwn(auto si rest_own_page, necessite donc que url_param 'user' soit inséré) + User est redirigé vers page de confirmation qu'il est bien OWN
//A revoir sans doutes [21-09-13]. 
//Permet de dire que la page est disponible aux personnes connectées. 
//S'ils ne le sont pas, ils sont redirigées vers la page de connexion [29-11-13]
//Si l'authentification reussie, l'utilisateur est redirigé vers la page default_rest_prod_page.
//Peut être vu comme redondant par rapoort à 'racc'
define("AG_RALL","rall"); //AllrestNAcc 


//## WELCOME ##
//-- Autorisation pour zone ne necessitant pas une connexion (donc zone non restreinte) 
//-- Dans le cas où l'utilisateur est connecté, le rediriger vers la page default_rest_own_page
define("AG_WALL_REST_SR1","wall_rest_sr1"); //WelcToOwn 
// -- Même statut que pour AG_W1 mais où l'utilisateur est redirigé vers la page default_rest_prod_page par defaut s'il est connecté
define("AG_WALL_REST_SR2","wall_rest_sr2");
//-- Autorisation pour utilisateur non connecté, en zone non restreinte, mais ayant un fichier SESSION RSTO valide
define("AG_WIKU","wiku"); //WelcKU
//-- Même statut que pour AG_W2, mais ayant un fichier SESSION STO valide
define("AG_WANON","wanon"); //WelcAnon
//-- Même statut que pour AG_W2, mais aucun fichier SESSION n'est requis
define("AG_WGHOST","wghost"); //WelcGhost
//-- Autorisé pour tout le monde et quelque soit le statut
define("AG_WALL","wall"); //AllWelcVisitor


//***************** DEFAULT PAGE
//DFTPAGE_PROD_WEL DFTPAGE_PROD_CONX DFTPAGE_PROD_REST DFTPAGE_PROD_REST_OWN DFTPAGE_PROD_DCONX
define("DFTPAGE_PROD_WEL", "pdpage_welc_pgid");
define("DFTPAGE_PROD_CONX", "pdpage_welc_conx_pgid");
define("DFTPAGE_PROD_DCONX", "pdpage_welc_deconx_pgid");
define("DFTPAGE_PROD_REST", "pdpage_rest_prod_pgid");
define("DFTPAGE_PROD_REST_OWN", "pdpage_rest_own_pgid");
define("DFTPAGE_PROD_REST_CONFIRM_OWN", "pdpage_rest_confirm_own_pgid");


/******************************************** APPLICATION CONF */////////////////////
define("SLP_LANG_PATH", RACINE."/rdcs.prod/pac/gr/bundles/bdl.657870/lang/");
define("SLP_URQTABDEPOT_PATH", RACINE."/product/process/def.urqtab.def.xml"); //[NOTE au 15/10/13 : code refactorisé pour cause de déplacement de fichiers vers un autre repertoire]
define("SLP_REST_DEFAULT_PAGE","");

/******************************************** MODULES */////////////////////
define("WOS_MOD_PATH_PHPBROWSCAP",RACINE."/common/modules/Browscap.php");
define("WOS_MOD_PATH_PHPBROWSCAP_CACHE",RACINE."/system/cache/phpbrowscap/");

define("WOS_MOD_PATH_PHPQUERY",RACINE."/common/modules/phpQuery-onefile.php");
define("WOS_MOD_PATH_SIMPLEHTMLDOM",RACINE."/common/modules/simple_html_dom.php");
?>