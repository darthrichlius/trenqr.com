<?php
/**
 * Description of lib
 *
 * @author lou.carther.69
 */
class DVT extends MOTHER {
    private $dvt_id;
    private $dvt_struct_path;
    private $dvt_has_queries;
    private $list_of_queries_object;
    private $subcarrier_name;
    private $subcarrier_array;
    private $file_css;
    private $file_js;
    private $xml_scope;
    
    function __construct($dvt_id, $dvt_struct_path) {
        parent::__construct(__FILE__,__CLASS__);
        if ( ( isset($dvt_id) and $dvt_id != "" ) and ( isset($dvt_struct_path) and $dvt_struct_path != "" ) ) {
            $this->dvt_id = $dvt_id;
            /**
             * Rappel : L'existence du fichier a déjà été vérifié par PROCESSOR pour éviter de continuer le process au cas où on aurait une erreur
             * et d'economiser les ressources processeurs.
             */
            $this->dvt_struct_path = $dvt_struct_path;
            $this->dvt_has_queries = FALSE;
            $this->build();

        } else $this->signalError("err_sys_l00",__FUNCTION__, __LINE__); 
    }
    
    private function build() {
        $this->xml_scope = $this->recuperer_xml_scope_from_id ($this->dvt_id);
        $this->construire_queries_object_from_xmlscope($this->xmlscope);
    }

    
    private function recuperer_xml_scope_from_id ($id) {
        $entry = WOS_GEN_PATH_DVTDEF;
        
        if ( (isset($id) and $id != "") and (isset($entry) and $entry != "") )
        {
            $xml_tools = new MyXmlTools();
            
            $dom = $xml_tools->checkXmlFileInTripleAction($entry);
            //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$entry);
            //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$id);
            if( @isset($dom) and is_object($dom) ){
                $local_urq_table="";
                //We try to get the urq concerned 
                $DomUrqTab = $dom->getElementById($id);
                //$DomUrqTab = $dom->getElementById('no736c70xx1');
                //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$DomUrqTab,'v_d');
                $local_urq_table = MyXmlTools::recursFinderIntoArray($DomUrqTab, $local_urq_table);
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$local_urq_table);
                //echo "NOMBRE = ".count($local_urq_table);
                if ( (is_array($local_urq_table)) and (count($local_urq_table) > 0) ){
                    //If it's a success
                   $this->xmlscope = $local_urq_table;
                } else $this->signalError ("err_sys_l013",__FUNCTION__, __LINE__);    
            }
            else
                $this->signalError ("err_sys_l01",__FUNCTION__, __LINE__);
        }
        else {
            $this->signalError ("err_sys_l00",__FUNCTION__, __LINE__);
        }
        
    }
    
    
    private function construire_queries_object_from_xmlscope ( $xmlscope ) {
        if ( isset($xmlscope) and is_array($xmlscope) and count($xmlscope)>0 ) {
            if ( isset($xmlscope["dvt.data.queries"]) and is_array($xmlscope["dvt.data.queries"]) and count($xmlscope["dvt.data.queries"]) > 0) {
                //Ce qui veut dire que le DVT requiert d'interroger la base de données
                foreach ($xmlscope["dvt.data.queries"] as $key => $value) {
                    $Query = new QUERY($value);
                    $this->list_of_queries_object[$key] = $Query;
                }
                $this->dvt_has_queries = TRUE;
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->list_of_queries_object);
            } 
            $this->subcarrier_name = $xmlscope["dvt.subcarrier.name"];
            $this->file_css = $xmlscope["dvt.files.css"];
            $this->file_js = $xmlscope["dvt.files.js"];
        } else $this->signalError("err_sys_l00",__FUNCTION__, __LINE__); 
    }

    
    public function afficher_dvt () {
        include $this->dvt_struct_path;
    }
    
    /*************************************************************************************************************************/
    /*********************************************** GETTERS AND SETTERS ****************************************************/
    // <editor-fold defaultstate="collapsed" desc="Getters and Setters">
    public function getDvt_id() {
        return $this->dvt_id;
    }

    public function setDvt_id($dvt_id) {
        $this->dvt_id = $dvt_id;
    }

    public function getDvt_struct_path() {
        return $this->dvt_struct_path;
    }

    public function setDvt_struct_path($dvt_struct_path) {
        $this->dvt_struct_path = $dvt_struct_path;
    }

    public function getDvt_has_queries() {
        return $this->dvt_has_queries;
    }

    public function setDvt_has_queries($dvt_has_queries) {
        $this->dvt_has_queries = $dvt_has_queries;
    }

    public function getList_of_queries_object() {
        return $this->list_of_queries_object;
    }

    public function setList_of_queries_object($list_of_queries_object) {
        $this->list_of_queries_object = $list_of_queries_object;
    }

    public function getSubcarrier_name() {
        return $this->subcarrier_name;
    }

    public function setSubcarrier_name($subcarrier_name) {
        $this->subcarrier_name = $subcarrier_name;
    }

    public function getSubcarrier_array() {
        return $this->subcarrier_array;
    }

    public function setSubcarrier_array($subcarrier_array) {
        $this->subcarrier_array = $subcarrier_array;
    }

    public function getFile_css() {
        return $this->file_css;
    }

    public function setFile_css($file_css) {
        $this->file_css = $file_css;
    }

    public function getFile_js() {
        return $this->file_js;
    }

    public function setFile_js($file_js) {
        $this->file_js = $file_js;
    }

    public function getXml_scope() {
        return $this->xml_scope;
    }

    public function setXml_scope($xml_scope) {
        $this->xml_scope = $xml_scope;
    }
    // </editor-fold>
    
}

?>
