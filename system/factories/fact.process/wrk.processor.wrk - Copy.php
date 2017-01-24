<?php


/**
 * Description of WORKER_PROCESSOR 
 *
 * @author lou.carther.69
 */
class WORKER_PROCESSOR extends MOTHER {
    private $page_id;
    private $running_lang;
    private $xmlscope;
    //Utilisé pour créer les DVT mais aussi pour vérifier si les path aboutissent réellement à un file
    private $list_of_dvt_struct_path;
    private $list_of_dvt_id;
    
    private $list_of_dvt_objects;
    
    function __construct($page_id, $running_lang) {
        parent::__construct(__FILE__,__CLASS__);
        if ( ( isset($page_id) and $page_id != "" ) and ( isset($running_lang) and $running_lang != "" ) ) {
            $this->page_id = $page_id;
            $this->running_lang = $running_lang;
            
            $this->run();
        } else $this->signalError("err_sys_l00",__FUNCTION__, __LINE__); 
    }
    
    private function run () {
        $this->recuperer_xml_scope();
        $this->creer_liste_dvt_id();
        $this->creer_liste_dvt_struct_path_fonction_de_lang();
        $this->creer_dvt_objects();
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->list_of_dvt_objects,'v_d');
        $this->creer_page();
    }
    
    private function recuperer_xml_scope () {
        $entry = WOS_GEN_PATH_PAGEDEF;
        $id = $this->page_id;
        
        if ( isset($entry) and $entry != "")
        {
            $xml_tools = new MyXmlTools();
            
            $dom = $xml_tools->checkXmlFileInTripleAction($entry);
            //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$entry);
            if( @isset($dom) and is_object($dom) ){
                $local_urq_table="";
                //We try to get the urq concerned 
                $DomUrqTab = $dom->getElementById($id);
                //$DomUrqTab = $dom->getElementById('no736c70xx1');
                //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$DomUrqTab,'v_d');
                $local_urq_table = MyXmlTools::recursFinderIntoArray($DomUrqTab, $local_urq_table );
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$local_urq_table);
                if ( is_array($local_urq_table) and count($local_urq_table)>0 ){
                    //If it's a success
                   $this->xmlscope = $local_urq_table;
                } else $this->signalError ("err_sys_l013",__FUNCTION__, __LINE__);    
            }
            else
                $this->signalError ("err_sys_l01",__FUNCTION__, __LINE__);
        }
        else 
            $this->signalError ("err_sys_l00",__FUNCTION__, __LINE__);
    }

    
    private function creer_liste_dvt_id () {
       //$this->list_of_dvt_id = $this->xmlscope["page.dvthandler.path"];
       foreach ($this->xmlscope["page.dvthandler.path"] as $key => $value) {
           $this->list_of_dvt_id[$key] = explode('.', $value)[1];
       }
    }
    
    private function creer_liste_dvt_struct_path_fonction_de_lang () {
        $local_array_path = array();
        switch($this->running_lang) {
            case 'fr' :
                foreach ($this->xmlscope["page.dvthandler.path"] as $key => $value) {
                    $path = WOS_GEN_PATH_DVTSTRUCT.$this->running_lang."/".$value;
                    //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $path);
                    if(!file_exists($path)) { $this->signalError("err_sys_l012",__FUNCTION__, __LINE__); }
                    $local_array_path[$key] = $path;
                    //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$local_array_path);
                }
            break;
            case 'en' :
                break;
        }
        $this->list_of_dvt_struct_path = $local_array_path;
    }
    
    private function  creer_dvt_objects() {
        
        foreach ($this->list_of_dvt_struct_path as $key => $value) {
            $DVT = new DVT($this->list_of_dvt_id[$key], $value);
            $this->list_of_dvt_objects[$key] = $DVT;
        }
    }
    
    private function creer_page () {
        
    }
    
    /********************************************************************************************************/
    /*************************************** GETTERS and SETTERS ********************************************/
    // <editor-fold defaultstate="collapsed" desc="comment">
    public function getPage_id() {
        return $this->page_id;
    }

    public function setPage_id($page_id) {
        $this->page_id = $page_id;
    }

    public function getRunning_lang() {
        return $this->running_lang;
    }

    public function setRunning_lang($running_lang) {
        $this->running_lang = $running_lang;
    }

    public function getXmlscope() {
        return $this->xmlscope;
    }

    public function setXmlscope($xmlscope) {
        $this->xmlscope = $xmlscope;
    }

    public function getList_of_dvt_struct_path() {
        return $this->list_of_dvt_struct_path;
    }

    public function setList_of_dvt_struct_path($list_of_dvt_struct_path) {
        $this->list_of_dvt_struct_path = $list_of_dvt_struct_path;
    }

    public function getList_of_dvt_id() {
        return $this->list_of_dvt_id;
    }

    public function setList_of_dvt_id($list_of_dvt_id) {
        $this->list_of_dvt_id = $list_of_dvt_id;
    }

    public function getList_of_dvt_objects() {
        return $this->list_of_dvt_objects;
    }

    public function setList_of_dvt_objects($list_of_dvt_objects) {
        $this->list_of_dvt_objects = $list_of_dvt_objects;
    }

// </editor-fold>

}
?>