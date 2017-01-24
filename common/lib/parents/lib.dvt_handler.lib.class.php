<?php

class DataViewTemplate_Handler extends Teamplate_Model {
    private $dvt_path;
    private $my_structure;
    private $userdatacontent_array;
    
    private $css_file_path;
    private $js_file_path;
    
    private $human_mode;
    
    function __construct($UserDataContent, $dvt_path, $human_mode) {
        $this->human_mode = $human_mode;
        $this->userdatacontent_array = $UserDataContent;
        $this->dvt_path = $dvt_path;
        
        $this->run();
    }
    
    private function run() {
        //Extraction du code du dvt.
        $temp_code = $this->extraire_dvt_code ();
        $this->acquerir_external_files_from_dvt_code ($temp_code);
        $this->build_template();
    }

    /**
     * Permet d'extraire le libellé du fichier pour ensuite s'en servir pour récupérer les externals files
     */
    private function extraire_dvt_code () {
        $filename = basename($this->dvt_path);
        $temp = explode(".",$filename);
        $dvt_code_lib = $temp[0];
        return $dvt_code_lib;
    }
    
    /**
     * Permet d'instancier les variables relatives aux fichiers externes css et js
     * @param type $entry_code
     */
    private function acquerir_external_files_from_dvt_code ($entry_code) {
        $this->css_file_path = PATH_TO_EXTERNAL_FILES_FOR_DVT.$entry_code."css";
        $this->js_file_path = PATH_TO_EXTERNAL_FILES_FOR_DVT.$entry_code."js";
    }
    
    /**
     * Permet de construire la structure. 
     * Cette construction necessite deux etapes : charger les UDC et gérer la mimification de la structure
     */
    private function build_template () {
        $this->fonction_de_remplissage_en_userdatacontent_de_la_structure();
        $this->fonction_de_load_de_la_structure_selon_le_mode_de_visionnage($this->human_mode);
    }
    
    
    private function fonction_de_remplissage_en_userdatacontent_de_la_structure() {
        $carrier = $this->userdatacontent_array;
        include "".$this->dvt_path."";
       $this->my_structure = $structure;
    }
    
    
    private function fonction_de_load_de_la_structure_selon_le_mode_de_visionnage ($human_mode) {
        switch($human_mode) {
            case 1 :
                //demimification
                break;
            case 0 :
                //mimification
                break;
        }
    }
    
    public function display_template () {
        echo $this->my_structure;
    }
    
    /**
     * Permet d'intégrer le template dans une page et de l'afficher. 
     */
    public function test_template () {
        
    }
    
    /************************************************************************************************/
    /************************************** GETTERS AND SETTERS *************************************/
    
    // <editor-fold defaultstate="collapsed" desc="GETTERS and SETTERS">
    public function getDvt_path() {
        return $this->dvt_path;
    }

    public function setDvt_path($dvt_path) {
        $this->dvt_path = $dvt_path;
    }

    public function getMy_structure() {
        return $this->my_structure;
    }

    public function setMy_structure($my_structure) {
        $this->my_structure = $my_structure;
    }

    public function getUserdatacontent_array() {
        return $this->userdatacontent_array;
    }

    public function setUserdatacontent_array($userdatacontent_array) {
        $this->userdatacontent_array = $userdatacontent_array;
    }

    public function getCss_file_path() {
        return $this->css_file_path;
    }

    public function setCss_file_path($css_file_path) {
        $this->css_file_path = $css_file_path;
    }

    public function getJs_file_path() {
        return $this->js_file_path;
    }

    public function setJs_file_path($js_file_path) {
        $this->js_file_path = $js_file_path;
    }

    public function getHuman_mode() {
        return $this->human_mode;
    }

    public function setHuman_mode($human_mode) {
        $this->human_mode = $human_mode;
    }

// </editor-fold>


    
}
?>
