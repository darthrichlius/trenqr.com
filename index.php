<?php

//echo "Fail (index.php)";
//exit();
/* 
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
 * @author Lou Carther <dieudrichard@gmail.com>
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

function on_process_end () {
	/*
	 * [TODO-NOTE 06-09-15] @author BOR
	 *  	On pourrait utiliser cette fonction pour effectuer des logs de fin de processus comme pour mesurer la performance.
	 * 		Cependant, d'autres application sont possibles :
	 *			-> Log automatique des bug_trace de toutes les requetes
	 * 			-> Log automatique des bug_trace en cas d'erreur
	 *			-> Autres vérifications 
	 */
	/*
	$error = error_get_last();
	
	if( $error !== NULL) {
		$errno   = $error["type"];
		$errfile = $error["file"];
		$errline = $error["line"];
		$errstr  = $error["message"];

		var_dump("<br/> BEFORE SHUTDOWN :",$errno, $errstr, $errfile, $errline);
	}
	//*/
}

register_shutdown_function("on_process_end");

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

//*
//USE THIS TO CONFIG ERROR_REPORTING 
showError(false);
//Because an error can be triggered before the declaration of "right" IS_DEBUG in INDEX
define("IS_DEBUG",false);  
//*/

/*
 * Utile pour les cas difficiles où on ne peut pas changer CONF car il faut qu'une action s'effectue avant.
 * De plus, si un bogue n'apparait que si le processus ne pas peut être arrêter.
 */
/*
if ( $_POST && $_POST["datas"] && $_POST["datas"]["dr"] === "BTM" ) {
    define("IS_DEBUG",TRUE);  
    define("RIGHT_IS_DEBUG",TRUE);  
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(E_ALL | E_NOTICE);
}
//*/

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
//$mhost = "localhost";
$mhost = "127.0.0.1";
define("WOS_MAIN_HOST",$mhost);
define("WOS_MAIN_HTTP_HOST","http://".$mhost); 
define("WOS_MAIN_HTTPS_HOST","http://".$mhost);


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

$ext_files_ws_url = $complement."/bart1/ext/public";
/* $ext_files_ws_url = "http://ext.ycgkit.com/public"; */
define("EXT_FILES_ROOT",$ext_files_ws_url);
$prod_imgfiles_ws_url = $complement."/bart1/timg/files/img";
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
/*
 * [DEPUIS 19-01-16]
 */
include_once RACINE.'/vendor/autoload.php';
require_once WOS_PATH_INC_FMK_LIB;
require_once WOS_PATH_INC_INDEX_PACK;
require_once WOS_PATH_INC_TOOLS;
//require_once WOS_PATH_DEF_CONF_FILE;
require_once WOS_PATH_DEF_LANG;

/*NON C'EST A URL_HANDLER de travailler deçu
if( !isset($_GET['want']) or  $_GET['want'] == "") $_GET['want']= "/";
if( !isset($_GET['user']) or  $_GET['user'] == "") $_GET['user']= "#";
*/    

header_remove("X-Powered-By");

$Index = new Index();
$Index->run();

//*/

?> 