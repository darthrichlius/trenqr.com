<?php


$entry_port = $_GET["port"];
$entry_key = $_GET["key"];

if ( !( isset($entry_port) && $entry_port !== "" ) || !( isset($entry_key) && $entry_key !== "" ) ) {
    exit("GO : ERROR 404");
}

switch ($entry_port) {
    //767 = SOS
    case "_ENTRY_PORT_767" :
            $_KEY = "1234";
            if ( $entry_key !== $_KEY ) {
                exit("GO : ERROR 404");
            }
//            require_once "/apps/stus.php";
            header('Location: /apps/bkdr.php'); 
        break;
    case "_ENTRY_PORT_B52" :
            $_KEY = "1234";
            if ( $entry_key !== $_KEY ) {
                exit("GO : ERROR 404");
            }
            echo "STATS";
        break;
    default:
            exit("GO : ERROR 404");
        break;
}

?>

