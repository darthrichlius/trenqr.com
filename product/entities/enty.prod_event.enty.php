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
class PROD_EVENT extends PROD_ENTITY {
    
    private $pdevtid;
    private $pdevt_acc_actor;
    private $pdevt_ev_type;
    private $pdevt_ev_is_ab;
    private $pdevt_datecrea;
    private $pdevt_datecrea_tstamp;
    
    private $evtyp_code;
    private $evtyp_code_fe;
    
    private $uid;
    private $ugid;
    private $ueid;
    private $upsd;
    private $ufn;
    private $uppic;
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = ["pdevtid","pdevt_acc_actor","pdevt_ev_type","pdevt_ev_is_ab","pdevt_datecrea","pdevt_datecrea_tstamp", "evtyp_code", "evtyp_code_fe", "uid", "ugid", "ueid", "upsd", "ufn", "uppic"];
        $this->needed_to_loading_prop_keys = ["pdevtid","pdevt_acc_actor","pdevt_ev_type","pdevt_ev_is_ab","pdevt_datecrea","pdevt_datecrea_tstamp", "evtyp_code", "evtyp_code_fe", "uid", "ugid", "ueid", "upsd", "ufn", "uppic"];
        
        $this->needed_to_create_prop_keys = ["actor","evtype","ev_is_ab"];
    }

    
    protected function build_volatile($args) { }

    
    public function exists($evtid) {
        //QUESTION : Existe t-il un evenenemt avec l'identifiant fourni ? (FALSE, DONNEES sur l'évènement)
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! ( is_string($evtid) || is_int($evtid) ) ) {
            return;
        } 
        
        //Contacter la base de données et vérifier si la Relation existe.
        $QO = new QUERY("qryl4pdevtn1");
        $params = array( ':evtid' => $evtid );
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
            'pdevtid' => $datas["pdevtid"],
            'pdevt_acc_actor' => $datas["pdevt_acc_actor"],
            'pdevt_ev_type' => $datas["pdevt_ev_type"],
            'pdevt_ev_is_ab' => $datas["pdevt_is_autobuild"],
            'pdevt_datecrea' => $datas["pdevt_datecrea"],
            'pdevt_datecrea_tstamp' => $datas["pdevt_datecrea_tstamp"],
            "evtyp_code" => $datas["evtyp_code"], 
            "evtyp_code_fe" => $datas["evtyp_code_fe"], 
            "uid" => $datas["uid"], 
            "ugid" => $datas["ugid"], 
            "ueid" => $datas["ueid"], 
            "upsd" => $datas["upsd"], 
            "ufn" => $datas["ufn"], 
            // "uppic" => $datas["uppic"] //[NOTE 08-10-14] OBSELETE : Du fait que PDACC n'a plus uppic 
            "uppic" => NULL
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

    protected function on_alter_entity($args) {}
    

    public function on_create_entity($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        //On vérifie la présence des données obligatoires : ["actor","evtype","ev_is_ab"]
        $com  = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$this->needed_to_create_prop_keys],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if ( "ev_is_ab" === strtolower($k) ) {
                    //On passe
                } else if (! ( !is_array($v) && !empty($v) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
        
        $id = $this->write_new_in_database($args);
        
        //On load la classe
        return $this->load_entity($id);
    }

    protected function on_delete_entity ($evtid) {
        //NOTE : Si on veut supprimer, c'est qu'on est dans le cas de la suppression d'un Compte. Dans ce cas, la suppression se fait depuis l'Entity ProdAcc
    }

    public function on_read_entity($evtid) {
        return $this->exists($evtid);
    }

    protected function write_new_in_database($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //RAPPEL : ["actor","evtype"]
        
        /* On crée l'évènement au niveau de la base de données */
        $now = round(microtime(TRUE)*1000);

        $QO = new QUERY("qryl4pdevtn2");
        $params = array(":actor" => $args["actor"] , ":evtype" => $args["evtype"], ":ev_is_ab" => $args["ev_is_ab"], ":tstamp" => $now);
        $id = $QO->execute($params);
        
        return $id;
    }
    
    /****************************************************************************************************************************************************/
    /****************************************************************************************************************************************************/
    /************************************************************** SPECEFICS SCOPE *********************************************************************/
    


    /**************************************************************************************************************************/
    /************************************************** ONREAD (start)  ****************************************************/
    
    
    /*********************************************** ONREAD (end) **********************************************************/
    /**************************************************************************************************************************/






    /***************************************************************************************************************************************************/
    /***************************************************************************************************************************************************/
    /************************************************************ GETTERS and SETTERS ******************************************************************/


// <editor-fold defaultstate="collapsed" desc="GETTERS and SETTERS">
    public function getPdevtid() {
        return $this->pdevtid;
    }

    public function getPdevt_acc_actor() {
        return $this->pdevt_acc_actor;
    }

    public function getPdevt_ev_type() {
        return $this->pdevt_ev_type;
    }
    
    public function getPdevt_ev_is_ab() {
        return $this->pdevt_ev_is_ab;
    }

    public function getPdevt_datecrea() {
        return $this->pdevt_datecrea;
    }

    public function getPdevt_datecrea_tstamp() {
        return $this->pdevt_datecrea_tstamp;
    }


    public function getEvtyp_code() {
        return $this->evtyp_code;
    }

    public function getEvtyp_code_fe() {
        return $this->evtyp_code_fe;
    }

    public function getUid() {
        return $this->uid;
    }

    public function getUgid() {
        return $this->ugid;
    }

    public function getUeid() {
        return $this->ueid;
    }

    public function getUpsd() {
        return $this->upsd;
    }

    public function getUfn() {
        return $this->ufn;
    }

    public function getUppic() {
        return $this->uppic;
    }

// </editor-fold>



}