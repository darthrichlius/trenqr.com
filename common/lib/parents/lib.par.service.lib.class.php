<?php
require_once WOS_PATH_FMK_PAR_MOTH;

class SERVICE extends MOTHER
{
        protected $serv_code = "";
        
        function __construct($code, $file_name, $crea_date, $last_mod_date) {
            $this->serv_code = $code;
            $this->serv_file_name = $file_name;

            //We ensure that until the mother this class is innit. 
            parent::__construct($crea_date, $last_mod_date, __FILE__);
        }
        
        
        /***************************************************************************************************/
        /******************************************* GETTERS ***********************************************/

        protected function getServ_code() {
            return $this->serv_code;
        }

        protected function getServ_file_name() {
            return $this->serv_file_name;
        }                        


        /***************************************************************************************************/
        /******************************************* SETTERS ***********************************************/

        protected function setServ_code($serv_code) {
            $this->serv_code = $serv_code;
        }

        protected function setServ_file_name($serv_file_name) {
            $this->serv_file_name = $serv_file_name;
        }

}
?>
