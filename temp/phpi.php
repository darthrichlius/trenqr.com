<?php

$wha = $_GET["wha"];
if (! $wha ) {
    echo "WHAT ?";
    exit();
}
$wha = strtoupper($wha);

switch ($wha) {
    case "_PHP" :
            phpinfo();
        break;
    case "_SYS_TEMP_DIR" :
            echo sys_get_temp_dir();
        break;
    default:
            echo "HUM !";
        break;
}
