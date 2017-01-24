<?php


/**
 * FACT_PROCESSOR se situe sur le couche traitement de données pour les process.in et process.out.
 * Cependant, c'ezst au controller de piloter le processus via le current_process_worker.
 * Ce Factory ne fait que charger le bon c_P_wrk ainsi que les dvt
 *
 * @author lou.carther.69
 */
class FACT_PROCESSOR extends MOTHER {
    //[NOTE : Ajouté le 15/10/13]
    private $c_process_worker;
    private $page_id;
    private $pg_xmlscope;
    private $mdl_xmlscope;
    //private $list_of_dvt_struct_path;
    private $list_of_dvt_id;
    //private $list_of_dvt_objects;
    private $running_lang;
    
    function __construct($page_id, $running_lang) {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->check_isset_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
        //[NOTE 21-08-14] On ne prend plus pageid mais bien urqid dans SESSION. C'est plus logique.
        $this->page_id = $_SESSION["sto_infos"]->getCurr_wto()->getUrqid();
//        $this->page_id = $page_id;
        $this->running_lang = $running_lang;
         
        $this->run();
    }
    
    private function run () {
        //On va effectuer les opérations de base incombant à la requete urq.
        $this->load_worker ();
        /*
        $this->recuperer_xml_scope();
                
        $this->creer_list_of_dvt_id();
        //[NOTE au 31-10-13 : Retiré]
        //$this->creer_liste_dvt_struct_path_fonction_de_lang();
        //[NOTE au 31-10-13 : Retiré]
        //$this->creer_dvt_objects();
        
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->list_of_dvt_objects,'v_d');
         */
    }
    
    /******************************************** OPERATIONS LIEES AU IN ****/
    private function fetch_worker() {
        require_once WOS_PATH_FMK_PAR_ENTY;
        require_once WOS_GEN_PATH_WORKER;
        
        $file = WOS_GEN_PATH_TO_PROCESS_WORKERS.$this->page_id.WOS_PROCESS_WORKERS_EXT;
        
        if(! file_exists($file) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["CHECK HERE => ",$file],'v_d');
            $this->signalError("err_sys_l023", __FUNCTION__, __LINE__);
        }
            
        require_once $file;
    }

    private function load_worker () {
        $this->fetch_worker();
        $init = "WORKER_".$this->page_id;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $init);
        
        if (! class_exists($init) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["CHECK HERE => ",$init],'v_d');
            $this->signalError("err_sys_l024", __FUNCTION__, __LINE__,TRUE);
        }
//        $this->c_process_worker = new $init();
        $this->c_process_worker = new $init($this->running_lang);
        
        //[NOTE 12-08-14] On inclut les ENTITIES
        if ( file_exists(WOS_PATH_INC_ENTITIES) ) {
            require_once WOS_PATH_INC_ENTITIES;
        } else {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, WOS_PATH_INC_ENTITIES);
            $this->signalError("err_sys_l023", __FUNCTION__, __LINE__);
        }
    }
    
    
    /*
    private function recuperer_xml_scope() {
        $path_to_pg_def = WOS_GEN_PATH_PAGEDEF;
        $path_to_mdl_def = WOS_GEN_PATH_PAGES_MODELS_DEF;
        $id = $this->page_id;

        $xml_tools = new MyXmlTools();
        
        $this->pg_xmlscope = $xml_tools->acquiereXmlscopeFromPathAndIdInASecureWay($path_to_pg_def, $id, "err_sys_l013");
        
        $this->mdl_xmlscope = $xml_tools->acquiereXmlscopeFromPathAndIdInASecureWay($path_to_mdl_def, $this->pg_xmlscope["page.view.model"], "err_sys_l013");

    }
    //*/
/*
    private function parse_skeleton_and_extract_dtv_ids () {
        
    }
    //*/
    /*
    private function creer_list_of_dvt_id() {
        //$this->list_of_dvt_id = $this->xmlscope["page.dvthandler.path"];
        foreach ($this->mdl_xmlscope as $key => $value) {
            $this->list_of_dvt_id[$key] = $value;
        }
    }
//*/
/*
    private function creer_liste_dvt_struct_path_fonction_de_lang() {
        $local_array_path = array();

        foreach ($this->mdl_xmlscope as $key => $value) {
            $path = WOS_GEN_PATH_DVTSTRUCT.$this->running_lang."/".$this->running_lang.".".$value.".dvt.php";
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $path);
            if (!file_exists($path)) {
                $this->signalError("err_sys_l012", __FUNCTION__, __LINE__);
            }
            $local_array_path[$key] = $path;
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$local_array_path);
        }

        $this->list_of_dvt_struct_path = $local_array_path;
    }

*/
    /*
    private function creer_dvt_objects() {

        foreach ($this->list_of_dvt_struct_path as $key => $value) {
            $DVT = new DVT($this->list_of_dvt_id[$key], $value);
            $this->list_of_dvt_objects[$key] = $DVT;
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->list_of_dvt_objects);
        }
    }
*/
// </editor-fold>


    /*******************************************************************************************************************/
    /************************************************* GETTERS and SETTERS**********************************************/
    // <editor-fold defaultstate="collapsed" desc="GETTERS and SETTERS">
    public function getPg_xmlscope() {
        return $this->pg_xmlscope;
    }

    public function getMdl_xmlscope() {
        return $this->mdl_xmlscope;
    }

    public function getC_process_worker() {
        return $this->c_process_worker;
    }

    public function getList_of_dvt_struct_path() {
        return $this->list_of_dvt_struct_path;
    }

    public function getList_of_dvt_id() {
        return $this->list_of_dvt_id;
    }

    public function getRunning_lang() {
        return $this->running_lang;
    }

// </editor-fold>

}

?>
