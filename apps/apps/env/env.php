<?php
//Do not remove the following lines.

header('Content-type: text/html; charset=utf-8');
define("IS_DEBUG", TRUE);
define("RIGHT_IS_DEBUG", "true");

/*
 * [DEPUIS 17-11-15]
 */
define("WOS_MAIN_SUBDOMAIN_ROOTPA",""); 
$mhost = "localhost";
define("WOS_MAIN_HOST",$mhost);
define("WOS_MAIN_HTTP_HOST","http://".$mhost); 

$complement = "/dev.trenqr.com";
define("RACINE", $_SERVER['DOCUMENT_ROOT'].'/'.$complement);
define("HTTP_RACINE", "http://".$_SERVER['HTTP_HOST'].'/'.$complement);

$ext_files_ws_url = "http://";
define("EXT_FILES_ROOT",$ext_files_ws_url);

require_once RACINE."/system/conf/com.env.def.php";
require_once WOS_PATH_INC_FMK_LIB;
require_once WOS_PATH_INC_TOOLS;
require_once WOS_PATH_INC_SRVCS;
require_once WOS_PATH_DEF_LANG;
require_once RACINE."/system/services/srvc.err_handler.srvc.php";
require_once WOS_PATH_INC_ENTITIES;
require_once WOS_PATH_INC_CONTROLLER_ENGS;


function exceptions_error_handler($severity, $message, $filename, $lineno) {
    if ( error_reporting() == 0 )
        return;
    
    if ( error_reporting() & $severity ) {
        throw new ErrorException($message, 0, $severity, $filename, $lineno);
    }
}

?>
