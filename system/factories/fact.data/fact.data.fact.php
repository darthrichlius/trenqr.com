<?php

/**
 * Description of wrk
 *
 * @author lou.carther.69
 */
class FACT_DATA extends MOTHER{
    private $list_of_dvt_object;
    private $carrier;
    
    
    function __construct($list_of_dvt_object) {
        parent::__construct(__FILE__,__CLASS__);
        if ( isset($list_of_dvt_object) and is_array($list_of_dvt_object) ) {
            $this->list_of_dvt_object = $list_of_dvt_object;
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->list_of_dvt_object);
            $this->carrier = array();
            $this->run();
        } else $this->signalError("err_sys_l00",__FUNCTION__, __LINE__);
    }
    
    
    private function run () {
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION);
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->list_of_ordered_urq_queries);
        $this->get_and_execute_if_exist_queries_list_from_dvt_if_exists();
        //On vérifie car il est possible qu'aucun query ne soit associé au DVT
    }
    
    /*
    private function verifier_si_urq_queries_exist_si_oui_load_et_execute () {
        $this->list_of_ordered_urq_queries_in_array = $_SESSION["sto"]->getUrq_scope()["urq_queries"];
        if ( isset($this->list_of_ordered_urq_queries_in_array) and is_array($this->list_of_ordered_urq_queries_in_array) and count($this->list_of_ordered_urq_queries_in_array)>0 ) {
           foreach ($this->list_of_ordered_urq_queries_in_array as $key => $value) {
                $this->list_of_ordered_urq_queries_objects[$key] = new QUERY($value);
            }
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->list_of_ordered_urq_queries_objects);
            $this->execute_queries_and_get_qdatas_if_exist ($this->list_of_ordered_urq_queries_objects);
        }
        return FALSE;
    }
    */
    
    private function get_and_execute_if_exist_queries_list_from_dvt_if_exists () {
        foreach ($this->list_of_dvt_object as $dvt) {
            $les_queries = $dvt->getList_of_queries_object();
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$les_queries);
            if ( isset($les_queries) ) { $this->execute_queries_and_get_qdatas_if_exists ($les_queries); }
        }
    }
    
    /**************************************************************************************************/
    /****************************** TRAITEMENTS GENERAUX SUR LES QUERIES ******************************/
    private function execute_queries_and_get_qdatas_if_exists ($entry) {
        //On ne refait pas de verification il y en a eu assez en amont.
        foreach ($entry as $qobject) {
            $this->execute_query_and_get_qdatas_if_exists($qobject);
        }
    }
    
    
    private function execute_query_and_get_qdatas_if_exists (Query $Qobject) {
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$Qobject);
        $qbody = $Qobject->getQbody();
        $qtype = $Qobject->getQtype();
        $qparams_in = $Qobject->getList_qparams_in();
        //$qparams_in = $_SESSION["qparams_in"];
        $qdbname = $Qobject->getQdbname();
        
        $it_is_a_prepared_query = FALSE;
        $it_is_a_return_datas_query = FALSE;
        
        if($qtype == "get") { $it_is_a_return_datas_query = TRUE; }
        if( isset($qparams_in) and is_array($qparams_in) and count($qparams_in)>0 ) { 
            $it_is_a_prepared_query = TRUE; 
            $qparams_in = $this->mettre_en_forme_qparams_in($qparams_in);
        }
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$qparams_in);
        $bdd = new WOS_DATABASE($qdbname);
        $bdd->tryConnection();
        $return;
        if ($it_is_a_return_datas_query) {
            if ($it_is_a_prepared_query) {
                $return = $bdd->executePrepareQueryWithResult($qbody, $qparams_in);
            } else {
                $return = $bdd->executeSimpleQueryWithResult($qbody);
            }
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$Qobject);
            
            //On prend PDO::FETCH_ASSOC plutot que la valeur par défaut car avoir le numéro de colonne ne nous interesse pas.
            //De plus, on espère ainsi réduire le Temps de Reponse.
            $datas = $return->fetch(PDO::FETCH_ASSOC);
            //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $datas);
            if ($datas) {
                do {
                    //Ici on ne traite pas le nom des colonnes
                    //Elles ne doivent donc pas être données à la légère
                    //Elles doivent correspondre à celles présentes au niveau du DVT
                    $this->carrier = $datas;
                } while ( $datas = $return->fetch() );
                $_SESSION["ud_carrier"] = $this->carrier;
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION);
            } else  $this->signalError ("err_user_l316", __FUNCTION__, __LINE__);
        } else {
            if ($it_is_a_prepared_query) {
               $bdd->executePrepareQueryWithoutResult($qbody,$qparams_in);
           } else {
               $bdd->executeSimpleQueryWithRowAffected($qbody);
           }
        }
    }
    
    private function mettre_en_forme_qparams_in($qparams_in) {
        foreach ($_SESSION["qparams_in"] as $key => $value) {
            if( array_key_exists($key,$qparams_in) ) {
                $qparams_in[$key] = $value;                
            }
        }
        return $qparams_in;
    }

}

?>
