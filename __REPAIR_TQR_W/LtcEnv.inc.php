<?php

header('Content-type: text/html; charset=utf-8');
define("IS_DEBUG", FALSE);
define("RIGHT_IS_DEBUG", FALSE);

/*
 * [DEPUIS 14-06-16]
 *      Permet de palier à un BOGUE relatif à l'utilisation de certaines fonctions de manipulation de dates.
 */
date_default_timezone_set('Europe/Paris');

/*
 * [DEPUIS 17-11-15]
 */
define("WOS_MAIN_SUBDOMAIN_ROOTPA","/www"); 
define("IS_SANDBOX",FALSE);
define("IS_LTC",TRUE);
//$mhost = "www.trenqr.com";
/*
 * [DEPUIS 11-08-16]
 */
$mhost = "trenqr.com";
define("WOS_MAIN_HOST",$mhost);
define("WOS_MAIN_HTTP_HOST","http://".$mhost); 
define("WOS_MAIN_HTTPS_HOST","https://".$mhost);


/*
 * [NOTE 13-06-16]
 * IMPORTANT : Cette section a été spécialement amenagé pour les versions PROD
 */
define("RACINE", dirname(__DIR__)."/../../");
define("HTTP_RACINE", WOS_MAIN_HTTP_HOST);


$ext_files_ws_url = "http://";
define("EXT_FILES_ROOT",$ext_files_ws_url);

$prod_useractivity_img_url = HTTP_RACINE."/marge1/tqim/";
/* $prod_useractivity_img_url = "http://tqim.ycgkit1.com/"; */
define("WOS_SYSDIR_PROD_GLOBAL_USERIMG", $prod_useractivity_img_url);

require_once RACINE."/system/conf/com.env.def.php";

/*
 * [DEPUIS 19-01-16]
 */
include_once RACINE.'/vendor/autoload.php';

require_once WOS_PATH_INC_FMK_LIB;
require_once WOS_PATH_INC_TOOLS;
//require_once WOS_PATH_DEF_CONF_FILE;
require_once WOS_PATH_INC_SRVCS;
require_once WOS_PATH_DEF_LANG;
require_once RACINE."/system/services/srvc.err_handler.srvc.php";
//[NOTE 12-08-14] On inclut les ENTITIES
require_once WOS_PATH_INC_ENTITIES;

//[NOTE 26-08-14] On inclut les ENGINES de CONTROLLER
require_once WOS_PATH_INC_CONTROLLER_ENGS;