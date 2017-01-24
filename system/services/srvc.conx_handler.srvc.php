<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of srvc
 *
 * @author lou.carther.69
 */
class CONX_HANDLER extends MOTHER {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
    }
    
    public function is_connected () {
        //QUESTION : Est ce que l'utilisateur actif est connecté ?
        
        if ( isset($_SESSION) && key_exists("rsto_infos", $_SESSION) && isset($_SESSION['rsto_infos']) && ($_SESSION['rsto_infos'] instanceof RSTO_INFOS) && $_SESSION['rsto_infos']->getIs_connected() ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function try_login ($A, $SID) {
        
        $this->check_isset_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        //On vérifie qu'il s'agit bien d'un objet de type PROD_ACC
        if ( !($A instanceof PROD_ACC) ) {
            return NULL;
        }
        
        //On vérifie que l'utilisateur n'est pas déjà connecté
        if ( $this->is_connected() ) {
            return TRUE;
        }
        
        /* CLOSE PREVIOUS SESSION */
//        session_destroy();

        /* NOW GENERATING LINK FOR SESSION DATA */
//        session_start();
//        session_regenerate_id();
        
        //On crée l'objet SESSION de type restricted
        $RS = new RSTO_INFOS();
        $RS->on_create($SID, $A);
        
        //On insère l'objet au niveau de la variable globale $_SESSION
        unset($_SESSION['rsto_infos']);

        $_SESSION['rsto_infos'] = $RS;
        
        return TRUE;
    }

    public function try_logout () {
        if ( key_exists("rsto_infos", $_SESSION) && !empty($_SESSION['rsto_infos']) && $_SESSION['rsto_infos']->getIs_connected() ) {
//            $RS = new RSTO_INFOS();
//            $RS->downgrade();
            
            //On supprime l'objet
            unset($_SESSION['rsto_infos']);
            //On insère l'objet au niveau de la variable globale $_SESSION
//            $_SESSION['rsto_infos'] = $RS;
            
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function try_logout_and_destroy () {
        //On unset la partie restricted
        unset($_SESSION['rsto_infos']);
    }
    
    
}
?>
