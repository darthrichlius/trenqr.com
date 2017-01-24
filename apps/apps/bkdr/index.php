<?php
/*
 * README : 
 *  -> INFO 1 :
 *      Ce fichier permet d'accéder au produit en mode DEBUG. 
 *      L'objectif étant d'y accéder par une porte dérobéée, en d'autres termes, il s'agit d'une BACKDOOR admin.
 *      L'accès n'est autorisée qu'aux personnes connaissant vers ce fichier.
 *      De plus, l'accès au repertoire contenant ce fichier est sécurisé. En effet, il faut réussir un processus d'authentification.
 *      Ce fichier est la réplique exacte du fichier 'index.php' à la différence près que les variables force le produit à se mettre en mode DEBUG.
 *  -> INFO 2 :
 *      Cette porte d'entrée n'est utile que dans le cas d'une "BLANK PAGE ERROR". 
 *      Une "BLANK PAGE ERROR" est un moyen de signaler une "FATAL ERROR" et de protéger certaines données confidentielles. 
 *      Ce type d'erreur affiche en clair, des données qui permettent au développeur de comprendre l'erreur. Or, ces données ne doivent en aucun cas être rendues public.
 *      Cette erreur n'étant pas gérée, aucun email n'est envoyé à l'équipe techinque. Aussi, le seul moyen de comprendre l'erreur est de passer par cette porte.
 *      Cependant, cela necessite de connaitre l'url qui est à l'origine de l'erreur. Sauf s'il s'agit d'une erreur globale.
 * 
 *      ATTENTION-RAPPEL : 
 *          Seules les requêtes de type HTTP_GET sont acceptées. De plus, la profondeur se limite à une requete.
 *          Cela signifie, qu'une fois arriver sur la page cible, si on tente d'accéder à une autre page à partir de la page cible, on sera rediriger vers le fichier racine.
 *          On peut donc considérer cette manière d'accéder au produit comme unique et jettable.
 */


//* 
ini_set('session.cookie_domain', '.trenqr.com');
session_set_cookie_params(0, '/', '.trenqr.com');  
 
//*/
/*
session_start();
session_start(); 
var_dump(__LINE__,session_get_cookie_params(),session_id());
session_destroy();
var_dump(__LINE__,session_get_cookie_params(),session_id());
//var_dump(__LINE__,session_id(),$_SESSION);
exit();
//*/
/* 
session_destroy();
var_dump(__LINE__,$_SESSION);
exit(); 
//*/


/**
 * This file and all contained are properties of his author.
 * It's very awfully forbidden to read, use or dristribute this source.
 * If you possess this file without wishing to, would you please email us to fix the leak.
 * Otherwise, we will use all means to enforce our rights. 
 * @author Lou Carther <lou.carther@deuslynn-entreprise.com>
 */

//*
//This is a very trusted function. No error are allowed right here.
function ShowError($entry)
{
    if ($entry)
    {
        ini_set('display_startup_errors', 1);
        ini_set('display_errors', 1);
        error_reporting(E_ALL | E_NOTICE);
    }
}

function exceptions_error_handler($severity, $message, $filename, $lineno) {
    
    if ( error_reporting() & $severity ) {
        throw new ErrorException($message, 0, $severity, $filename, $lineno);
    }
}

/**
 * This function allows us to pass into debug mode very quickly when an error occur.
 * When the platform is on real prod mode, the error_reporting in php.ini is on off. Thus we can't see errors occuring.
 * Moreover, display_error is at off too. Thus when an sys error occurs we got an 500 Error. (And a blank page for USER_ERR)
 * IMPORTANT : Only if the .htaccess file hadn't set error page for 500 or others.
 * 
 * When we want to fix troubles, we get access to the local version
 * We begin by allowing error_report and display_error into php.ini.
 * But it won't show the error anyway (only if it's different from parse error in case of SYS_ERR). 
 * If it's a system error issue, we'll still have the 500 Error. In case of USER_ERR it will show the former blank page. 
 * In all cases, if we want to know about the error, we can change here to see it.
 * Then, This configuration will be changed by the index according to prod params
 */

//*

// We custom the error hanler 
//$old_error_handler = set_error_handler("myErrorHandler");

//USE THIS TO CONFIG ERROR_REPORTING 
showError(TRUE);
//Because an error can be triggered before the declaration of "right" IS_DEBUG in INDEX
define("IS_DEBUG",TRUE);  

/*
 * [DEPUIS 31-08-15] @author BOR
 *  Permet de forcer le fonctionnement en mode BACKDOOR.
 */
define(_ENV_BKDR_MD,TRUE);

//Add the main directory it runs under local server.
//Remove if root /
$complement = "/dev.trenqr.com";

//To that to be easy for sourcing.
//NEVER TRY TO REQUIRE SOMETHING USING URL (www.ex.com/my/path/to/the/file.php). IT WILL DO LEAD TO INCOMPREHENSIBLE ERRORS. NEVER !!!!!!!!

/*
 * [DEPUIS 24-08-15] @author BOR
 *      Le sous-domaine par défaut est :"www".
 *      Si vous évoluez sous un autre sous-domaine il est impératif de modifier la valeur ci-dessous.
 * [NOTE 18-09-15] @author BOR
 * 		J'ai apporté des modifications au code pour ajouter la définitiond de "WOS_THIS_SUBDOMAIN_ROOTPA"
 */ 
define("WOS_THIS_SUBDOMAIN","");
define("WOS_THIS_SUBDOMAIN_ROOTPA","");
/*
 * [DEPUIS 18-09-15] @author BOR
 *      L'utilisation d'une méthode de répartition par hôte necessite parfois de se référer au domaine principale.
 */
define("WOS_MAIN_SUBDOMAIN_ROOTPA",""); 

/*
 * [DEPUIS 18-09-15] @author BOR
 *      L'utilisation d'une méthode de répartition par hôte necessite parfois de se référer au domaine principale.
 *      Cela permet notamment à certains modules d'utiliser les ressources telles que des images, fichiers css, fichiers js, etc ...
 */
$mhost = "localhost";
define("WOS_MAIN_HOST",$mhost);
define("WOS_MAIN_HTTP_HOST","http://".$mhost);


if (! empty($complement) ) {
    define("RACINE", $_SERVER['DOCUMENT_ROOT'].$complement);
    define("HTTP_RACINE", "http://".$_SERVER['HTTP_HOST'].$complement);
} else {
    define("RACINE", $_SERVER['DOCUMENT_ROOT']);
    define("HTTP_RACINE", "http://".$_SERVER['HTTP_HOST']);
}
/*
 * [DEPUIS 15-09-15] @author BOR
 *      Permet surtout de passer la donnée au module SESSION (ENGINE) pour qu'il l'introduise dans systx
 */
define("WOS_PRODDOMAIN",HTTP_RACINE);  

$ext_files_ws_url = "/bart1/ext/public";
/* $ext_files_ws_url = "http://ext.ycgkit.com/public"; */
define("EXT_FILES_ROOT",$ext_files_ws_url);
$prod_imgfiles_ws_url = "/bart1/timg/files/img";
/* $prod_imgfiles_ws_url = "http://timg.ycgkit.com/files/img"; */
define("PROD_IMGFILES_ROOT",$prod_imgfiles_ws_url);
/*
 * Repertoire mère qui stocke les repertoires contenant les images qui sont des résultantes de l'activité du Produit.
 * Par exemple, ajout d'Articles, image de couverture, etc ...
 * ATTENTION : 
 *  Pour l'heure on ne stocke les images que sur YCGKIT1.
 *  Une amélioration permettra de répartir la charge avec TRENQR
 * 
 */
$prod_useractivity_img_url = HTTP_RACINE."/marge1/tqim/";
/* $prod_useractivity_img_url = "http://tqim.ycgkit1.com/"; */
define("WOS_SYSDIR_PROD_GLOBAL_USERIMG", $prod_useractivity_img_url);
//echo  $prod_useractivity_img_url;
//exit();

require_once RACINE."/system/conf/com.env.def.php";
require_once WOS_PATH_INC_FMK_LIB;
require_once WOS_PATH_INC_INDEX_PACK;
require_once WOS_PATH_INC_TOOLS;
//require_once WOS_PATH_DEF_CONF_FILE;
require_once WOS_PATH_DEF_LANG;

/*NON C'EST A URL_HANDLER de travailler deçu
if( !isset($_GET['want']) or  $_GET['want'] == "") $_GET['want']= "/";
if( !isset($_GET['user']) or  $_GET['user'] == "") $_GET['user']= "#";
*/    
 
$Index = new Index();
$Index->run();

//*/

header_remove("X-Powered-By");

?> 