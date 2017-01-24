<?php

/**
 * [NOTE 25-08-14] @author L.C. <lou.carther@deuslynn-entreprise.com>
 * J'ai modifié la Classe en faisant attention à ne pas casser le code déjà présent car des modules les utilisent.
 * 
 * La classe utilise PROD_ACC pour se populate();
 */
class RSTO_INFOS extends MOTHER {
    /**
     * L'object PROD_ACC qui a servi à créer la SESSION
     * @var PROD_ACC 
     */
    private $AOb;
    
    private $rest_cnx_id;
    
//    private $pflid; //Pour pourvoir recupérrer uspklang
    private $accid;
    //[NOTE 25-08-14] Ajout par L.C.
    private $acc_eid;
    //[NOTE 25-08-14] Ajout par L.C.
    private $gid;
    private $upseudo;
//    private $uname;
    private $ufname;
    //[NOTE 25-08-14] Ajout par L.C.
    private $uppic_id;
    //[NOTE 25-08-14] Ajout par L.C.
    private $uppic_path;
    //[NOTE 25-08-14] Ajout par L.C.
    private $ucoverpic_id;
    //[NOTE 25-08-14] Ajout par L.C.
    private $ucoverpic_path;
    //[NOTE 25-08-14] Ajout par L.C.
    private $uhref;
    private $uspklang; 
    //[NOTE 25-08-14] Ajout par L.C.
    private $ulvcity_id;
    private $ulvcity;
    //[NOTE 25-08-14] Ajout par L.C.
    private $ulvcountry_id;
    private $ulvcountry;
    
    private $is_connected;
    private $stay_connect;
    
    private $is_an_admin_acc;
    //[NOTE 25-08-14] Ajout par L.C.
    private $to_delete;
    private $to_delete_date;
    
    /*
     * Date à laquelle la SESSION de type restricted actuelle a été crée.
     */
    private $ssr_start_date;
    private $ssr_start_date_tstamp;
    /*
     * Date à laquelle la SESSION de type restricted actuelle a été interrompue.
     */
    private $ssr_end_date;
    private $ssr_end_date_tstamp;
    
    //[NOTE 25-08-14] Ajout par L.C.
    private $all_properties;
    
    //[NOTE 25-08-14] Ajout par L.C.
    /**
     * Permet de dire si la Classe a été effectivement populated.
     * Cela permet d'autoriser un CALLER a initier une creation RSTO_INTO.
     * Pour cela, il doit 
     * 
     * @var boolean 
     */
    private $is_populated;
            
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    private function populate () {
        
        //$toto = new PROD_ACC(); //DEBUG : A utiliser pour avoir accès à un Objet de type PROD_ACC et à l'auto-complétion.
        
        $this->all_properties["accid"] = $this->accid = $this->AOb->getPdaccid();
        $this->all_properties["acc_eid"] = $this->acc_eid = $this->AOb->getPdacc_eid();
        $this->all_properties["gid"] = $this->gid = $this->AOb->getPdacc_gid();
        $this->all_properties["upseudo"] = $this->upseudo = $this->AOb->getPdacc_upsd();
        $this->all_properties["ufname"] = $this->ufname = $this->AOb->getPdacc_ufn();
        $this->all_properties["uppic_id"] = $this->uppic_id = $this->AOb->getPdacc_uppicid();
        $this->all_properties["uppic_path"] = $this->uppic_path = $this->AOb->getPdacc_uppic();
        $this->all_properties["ucover_datas"] = $this->ucoverpic_id = $this->AOb->getPdacc_coverdatas();
        $this->all_properties["uhref"] = $this->uhref = $this->AOb->getPdacc_href();
        $this->all_properties["uspklang"] = $this->uspklang = $this->AOb->getPdacc_udl(); 
        $this->all_properties["ulvcity_id"] = $this->ulvcity_id = $this->AOb->getPdacc_ucityid();
        $this->all_properties["ulvcity"] = $this->ulvcity = $this->AOb->getPdacc_ucity_fn();
        $this->all_properties["ulvcountry_id"] = $this->ulvcountry_id = $this->AOb->getPdacc_ucnid();
        $this->all_properties["ulvcountry"] = $this->ulvcountry = $this->AOb->getPdacc_ucn_fn();
        $this->all_properties["stay_connect"] = $this->stay_connect = NULL; //TODO : Instancier la valeur le moment venu.
        $this->all_properties["is_an_admin_acc"] = $this->is_an_admin_acc = NULL; //TODO : Gérer le cas le moment venu.
        $this->all_properties["to_delete"] = $this->to_delete = $this->AOb->getPdacc_todelete();
        $this->all_properties["to_delete_date"] = $this->to_delete_date = NULL; //TODO : Ajouter le Getter au niveau de PRD_ACC
        
        $this->is_populated = TRUE;
        
    }
    
    public function on_create ($rest_cnx_id, $PDACC) {
        //RAPPEL : rest_cnx_id fait référence à l'identifiant de Session [22-11-14] @author L.C. Pas sur
        $this->check_isset_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        if ( !($PDACC instanceof PROD_ACC) ) {
            $this->signalError("sys_err_l337", __FUNCTION__, __LINE__);
        } 
        
        $this->AOb = $PDACC;
        
        $this->populate();
        
        //TODO : Déclencher une erreur
        if (! $this->is_populated ){
            return NULL;
        }
        
        $this->rest_cnx_id = $rest_cnx_id;
        
        $this->is_connected = TRUE;
        
        $this->ssr_start_date = date("Y-m-d H:i:s");
        $this->ssr_start_date_tstamp = round(microtime(TRUE)*1000);
        
    }
    
    public function on_alter ($PDACC) {
//    public function on_alter ($PDACC, $rest_cnx_id, $start_tsamp) {
        /*
         * Permet de mettre à jour les données de SESSION.
         * Cela permet de garantir la viabilité, fiabilité et sécurité de la dite SESSION.
         * CALLER doit fournir les anciennes données en ce qui concerne : conxid et date_start.
         * Ces données sont récupérées de l'ancienne SESSION.
         * 
         * (29-11-14) @author L.C.
         * S'il ne s'agit que d'une modification, on peut récupérer en interne les données.
         * Il n'est donc nul besoin que de demander à CALLER de les fournir.
         * 
         * ATTENTION : ON CONSIDERE QUE LA SESSION EST TOUJOURS ACTIVE !!!
         * ATTENTION : Timestamp en MILLISECONDES !!!
         */
        $this->check_isset_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        if ( !($PDACC instanceof PROD_ACC) ) {
            $this->signalError("sys_err_l337", __FUNCTION__, __LINE__);
        } 
        
        $this->AOb = $PDACC;
        
        $this->populate();
        
        //TODO : Déclencher une erreur
        if (! $this->is_populated ) {
            return NULL;
        } else {
            
            //OBLIGATOIRE, sinon WATCHMAN va désigné la Sesssion comme non active (Non Connecté)
            $this->is_connected = TRUE;
            
            //*
            $this->rest_cnx_id = $_SESSION["rsto_infos"]->getRest_cnx_id();
        
            $this->ssr_start_date = $_SESSION["rsto_infos"]->getSsr_start_date();
            $this->ssr_start_date_tstamp = $_SESSION["rsto_infos"]->getSsr_start_date_tstamp();
//            $this->ssr_start_date = date("Y-m-d H:i:s",($start_tsamp/1000));
//            $this->ssr_start_date_tstamp = $start_tsamp;
            //*/
            //On insère l'objet au niveau de la variable globale $_SESSION
            unset($_SESSION["rsto_infos"]);
            $_SESSION["rsto_infos"] = $this;
            
            return TRUE;
        }
    }
    
    /**
     * Est utilisée pour essentiellement pour signifier qu'il faut downgrade la SESSION.
     * Dans le contexte de WOS, cette méthode est utilisée lors de la déconnexion de l'utilisateur
     */
    public function downgrade() {
        
        $this->is_connected = FALSE;
        
        $this->ssr_end_date = date("Y-m-d H:i:s");
        $this->ssr_end_date_tstamp = round(microtime(TRUE)*1000);
    }
    
    /******************************************************************************************************************/
    /******************************************* START GETTERS SETTERS SECTION ****************************************/
    // <editor-fold defaultstate="collapsed" desc="GETTERS and SETTERS">
    public function getAOb() {
        return $this->AOb;
    }

    public function getRest_cnx_id() {
        return $this->rest_cnx_id;
    }

    public function getAccid() {
        return $this->accid;
    }

    public function getAcc_eid() {
        return $this->acc_eid;
    }

    public function getGid() {
        return $this->gid;
    }

    public function getUpseudo() {
        return $this->upseudo;
    }

    public function getUfname() {
        return $this->ufname;
    }

    public function getUppic_id() {
        return $this->uppic_id;
    }

    public function getUppic_path() {
        return $this->uppic_path;
    }

    public function getUcoverpic_id() {
        return $this->ucoverpic_id;
    }

    public function getUcoverpic_path() {
        return $this->ucoverpic_path;
    }

    public function getUhref() {
        return $this->uhref;
    }

    public function getUspklang() {
        return $this->uspklang;
    }

    public function getUlvcity_id() {
        return $this->ulvcity_id;
    }

    public function getUlvcity() {
        return $this->ulvcity;
    }

    public function getUlvcountry_id() {
        return $this->ulvcountry_id;
    }

    public function getUlvcountry() {
        return $this->ulvcountry;
    }

    public function getIs_connected() {
        return $this->is_connected;
    }
    
    public function getStay_connect() {
        return $this->stay_connect;
    }

    public function getIs_an_admin_acc() {
        return $this->is_an_admin_acc;
    }

    public function getTo_delete() {
        return $this->to_delete;
    }

    public function getTo_delete_date() {
        return $this->to_delete_date;
    }

    public function getSsr_start_date() {
        return $this->ssr_start_date;
    }

    public function getSsr_start_date_tstamp() {
        return $this->ssr_start_date_tstamp;
    }

    public function getSsr_end_date() {
        return $this->ssr_end_date;
    }

    public function getSsr_end_date_tstamp() {
        return $this->ssr_end_date_tstamp;
    }

    public function getAll_properties() {
        return $this->all_properties;
    }

    public function getIs_populated() {
        return $this->is_populated;
    }

    // </editor-fold>

}
?>
