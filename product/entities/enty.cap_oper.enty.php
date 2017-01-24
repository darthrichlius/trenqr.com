<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of enty
 *
 * @author arsphinx
 */
class CAP_OPER extends PROD_ENTITY {
    private $caporid;
    private $capor_amount;
    private $capor_target;
    private $capor_evtid;
    
    private $actor_uid; 
    private $actor_ugid; 
    private $actor_ueid; 
    private $actor_upsd; 
    private $actor_ufn; 
   
    private $recept_uid; 
    private $recept_ugid; 
    private $recept_ueid; 
    private $recept_upsd; 
    private $recept_ufn; 
    private $recept_uppic; 
    
    private $actor_uppic;
    
            
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = ["caporid","capor_amount","capor_target","capor_evtid", "pdevt_acc_actor", "pdevt_ev_type", "pdevt_datecrea", "pdevt_datecrea_tstamp", "recept_uid", "recept_ugid", "recept_ueid", "recept_upsd", "recept_ufn", "recept_uppic", "actor_uid", "actor_ugid", "actor_ueid", "actor_upsd", "actor_ufn", "actor_uppic"];
        $this->needed_to_loading_prop_keys = ["caporid","capor_amount","capor_target","capor_evtid", "pdevt_acc_actor", "pdevt_ev_type", "pdevt_datecrea", "pdevt_datecrea_tstamp", "recept_uid", "recept_ugid", "recept_ueid", "recept_upsd", "recept_ufn", "recept_uppic", "actor_uid", "actor_ugid", "actor_ueid", "actor_upsd", "actor_ufn", "actor_uppic"];
        
        $this->needed_to_create_prop_keys = ["oper_evtid","oper_recept"];
    }

    
    protected function build_volatile($args) { }

    public function exists($operid) { 
        //QUESTION : Est-il un evenenemt avec l'identifiant fourni ? (FALSE, DONNEES sur l'évènement)
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! ( is_string($operid) || is_int($operid) ) ) {
            return;
        } 
        
        //Contacter la base de données et vérifier si la Relation existe.
        $QO = new QUERY("qryl4capern2");
        $params = array( ':operid' => $operid );
        $datas = $QO->execute($params);
        
        if ( $datas ) {
            return $datas[0];
        } else {
            return FALSE;
        }
    }

    protected function init_properties($datas) {
        /*
         * [NOTE 25-11-14] @author L.C.
         * J'ai arreté avec check_isset_and_not_empty_entry_vars() car le bit n'est pas ici de 
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $datas, TRUE);
        
        foreach($datas as $k => $v) {
            $$k = $v;
            
            if (! (!empty($this->prop_keys) && is_array($this->prop_keys) && count($this->prop_keys) ) ) {
                $this->signalError ("err_sys_l4comn4", __FUNCTION__, __LINE__);
            }
            
            if ( count($this->needed_to_loading_prop_keys) != count(array_intersect(array_keys($datas), $this->needed_to_loading_prop_keys)) ) {
                
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["EXPECTED => ",$this->needed_to_loading_prop_keys],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ",array_keys($datas)],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE =>",  array_diff($this->needed_to_loading_prop_keys, array_keys($datas))],'v_d');
                $this->signalError ("err_sys_l4comn5", __FUNCTION__, __LINE__,TRUE);
            } 
            
            /*
             * On vérifie que les données entrantes sont attendues.
             * NOTE : On ne vérifie que les clés.
             */
            if (! in_array($k, $this->prop_keys) ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,"KEY => ".$k,'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->prop_keys,'v_d');
                $this->signalError ("err_sys_l4comn3", __FUNCTION__, __LINE__,TRUE);
            } 
            
            $this->all_properties[$k] = $this->$k = $datas[$k];
        }
    }

    protected function load_entity($id, $std_err_enbaled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //Intéroger la base de données pour récupérer les données sur la RELATION
        $datas = $this->exists($id);
        
        if ( !$datas && $std_err_enbaled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__);
        }
        else if ( !$datas && !$std_err_enbaled ) 
        {
            return 0;
        }
        
        $loads = [
            "caporid" => $datas["caporid"],
            "capor_amount" => $datas["capor_amount"],
            "capor_target" => $datas["capor_target"],
            "capor_evtid" => $datas["capor_evtid"], 
            
            "pdevt_acc_actor" => $datas["pdevt_acc_actor"],
            "pdevt_ev_type" => $datas["pdevt_ev_type"],
            "pdevt_datecrea" => $datas["pdevt_datecrea"],
            "pdevt_datecrea_tstamp" => $datas["pdevt_datecrea_tstamp"],
            
            "recept_uid" => $datas["recept_uid"], 
            "recept_ugid" => $datas["recept_ugid"], 
            "recept_ueid" => $datas["recept_ueid"], 
            "recept_upsd" => $datas["recept_upsd"], 
            "recept_ufn" => $datas["recept_ufn"], 
            //"recept_uppic" => $datas["recept_uppic"],  //[NOTE 08-10-14] OBSELETE : Du fait que PDACC n'a plus uppic 
            "recept_uppic" => NULL, 
            
            "actor_uid" => $datas["actor_uid"], 
            "actor_ugid" => $datas["actor_ugid"], 
            "actor_ueid" => $datas["actor_ueid"], 
            "actor_upsd" => $datas["actor_upsd"], 
            "actor_ufn" => $datas["actor_ufn"], 
            //"actor_uppic" => $datas["actor_uppic"] //[NOTE 08-10-14] OBSELETE : Du fait que PDACC n'a plus uppic 
            "actor_uppic" => NULL
        ];
        
        if ( !count($loads) ) 
        { 
            if ( $std_err_enbaled ) $this->signalError ("err_sys_l4comn1", __FUNCTION__, __LINE__);
            else return 0;
        } 
        else 
        {
            $this->init_properties($loads);
            $this->is_instance_load = TRUE;
            return $loads;
        }
        
        //Just in CASE !!
        return $loads;
        
    }

    protected function on_alter_entity($args) {
        
    }

    public function on_create_entity($args) {
         $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        //On vérifie la présence des données obligatoires : ["oper_evtid","oper_recept"]
        $com  = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$this->needed_to_create_prop_keys],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
        
        //On vérifie si le Compte récipiendaire existe
        $PA = new PROD_ACC();
        $C_E = $PA->exists_with_id($args["oper_recept"],TRUE);
        
        if (! $C_E ) {
            return "__ERR_VOL_TARGET_GONE";
        }
        
        //On vérifie si l'évènementà l'origine de la future opération existe
        $EVT = new PROD_EVENT();
        $E_E = $EVT->exists($args["oper_evtid"]);
        
        if (! $E_E ) {
            return "__ERR_VOL_EVENT_GONE";
        }
        
        //On récupère la somme liée à ce type d'évènement
        $args["amount"] = $E_E["amount"];
        
        //On crée l'écriture
        $id = $this->write_new_in_database($args);
        
        //On met à jour le capital de l'utilisateur récipiendaire
//        $this->oncreate_update_recept_capital($C_E, $args["amount"]); //OBSELETE 14-09-14
        $r = $this->oncreate_update_recept_capital($C_E); 
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r)  ) {
            return $r;
        }
        
        //On load la classe
        return $this->load_entity($id);
    }

    protected function on_delete_entity($args) {
        
    }

    protected function on_read_entity($args) {
        //NOTE : CALLER doit plutot utiliser exists()
    }

    protected function write_new_in_database($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //RAPPEL : ["oper_evtid","oper_recept","amount"]
        
        $QO = new QUERY("qryl4capern1");
        $params = array(":amount" => $args["amount"] , ":target" => $args["oper_recept"], ":eventid" => $args["oper_evtid"]);
        $id = $QO->execute($params);
        
        return $id;
    }
    
    /***************************************************************************************************************************************************/
    /***************************************************************************************************************************************************/
    /************************************************************ SPECEFICS SCOPE ***********************************************************************/
    
    /**************************************************************************************************************************/
    /************************************************** ONCREATE (start)  ******************************************************/
    private function oncreate_update_recept_capital ($R_T) {
        //R_T : Recipient_Table
        //RAPPEL : On ne revérifie pas si ACCOUNT est GONE car la méthode est privée. Si on arrive ici, alors tous les controles préalables ont été effectués.
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On met à jour le capital du propriétaire de l'Article
        $PA = new PROD_ACC();
        $PA->on_read_entity(["acc_eid" => $R_T["pdacc_eid"]]);
        $r = $PA->update_capital();
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r)  ) {
            return $r;
        }
        
        /*
        //Capital actuel
        $cp = intval($R_T["pdacc_capital"]);
        
        //Calcul du nouveau Capital VALIDE
        $ncp = $cp + intval($amount);
        
        $ncp = ( $ncp < 0 ) ? 0 : $ncp;
        
//        var_dump($R_T, $cp, $ncp, $amount);
        
        //TODO : Mettre une limite max au Capital
        
        //On met à jour le capital de récipiendaire
        /*
         * On fait l'opération ici pour éviter de refaire des controles au niveau de PROD_ACC  ce qui n'est pas bon pour la performance.
         * 
         * [NOTE 12-09-14] @author L.C.
         * Cette manière de faire suppose que l'on fait entierement confiance au fait que pdacc_capital sera toujours correctement à jour.
         * Cette manière de faire me parait être assez dangereuse. Les tests en conditions réelles permettant de savoir s'il faut au contraire récupérer la donnée depuis un SUM de Capital.
         * Par exemple, cette manière de faire est utilisée après une suppression d'un Article.
         
        $QO = new QUERY("qryl4capern3");
        $params = array( ':accid' => $R_T["pdaccid"], ':capital' => $ncp );
        $QO->execute($params);
        //*/
        return TRUE;
    }
    
    /************************************************* ONCREATE (end) **********************************************************/
    /**************************************************************************************************************************/

    /***************************************************************************************************************************************************/
    /***************************************************************************************************************************************************/
    /************************************************************ GETTERS and SETTERS ******************************************************************/
    
    // <editor-fold defaultstate="collapsed" desc="GETTERS and SETTERS">
    public function getCaporid() {
        return $this->caporid;
    }

    public function getCapor_amount() {
        return $this->capor_amount;
    }

    public function getCapor_target() {
        return $this->capor_target;
    }

    public function getCapor_evtid() {
        return $this->capor_evtid;
    }

    public function getActor_uid() {
        return $this->actor_uid;
    }

    public function getActor_ugid() {
        return $this->actor_ugid;
    }

    public function getActor_ueid() {
        return $this->actor_ueid;
    }

    public function getActor_upsd() {
        return $this->actor_upsd;
    }

    public function getActor_ufn() {
        return $this->actor_ufn;
    }

    public function getRecept_uid() {
        return $this->recept_uid;
    }

    public function getRecept_ugid() {
        return $this->recept_ugid;
    }

    public function getRecept_ueid() {
        return $this->recept_ueid;
    }

    public function getRecept_upsd() {
        return $this->recept_upsd;
    }

    public function getRecept_ufn() {
        return $this->recept_ufn;
    }

    public function getRecept_uppic() {
        return $this->recept_uppic;
    }

    public function getActor_uppic() {
        return $this->actor_uppic;
    }

// </editor-fold>



}
