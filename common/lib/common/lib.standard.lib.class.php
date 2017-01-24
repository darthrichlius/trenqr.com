<?php
/**
 * <p>Permet de verifier que la chaine string est bien sous le format date et qu'elle est valide.</p>
 * <ul>Le code valeur peut etre interprete ainsi :
 * <li> -1 : la valeur fournie n'est pas definie.</li>
 * <li> 0 : la valeur n'est pas sous le format date.</li>
 * <li> 1 : la valeur n'est pas une date valide.</li>
 * </ul>
 * 
 * @see preg_match()
 * @see checkdate()
 * @param string $arg
 * @return int
 */
function wos__is_date($arg) {
    if (isset($arg) and $arg != "") {
        $matches = array();
        $reg = "/^([0-9]{2,4})[-|\/]([0-9]{1,2})[-|\/]([0-9]{1,2})[\s]?(?:([\d]{1,2}):([\d]{1,2})(?::([\d]{1,2}))?)?$/";
        if ( preg_match($reg, $arg,$matches )) {
            if (checkdate($matches[2], $matches[3], $matches[1]) ) return 1;
        } else return 0;
    } else return -1;
}


?>
