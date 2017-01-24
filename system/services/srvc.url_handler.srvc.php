<?php

/**
 * This class is made to handle all the request received from the visitor.
 * It is able to understand, authentify (more check if clean) and tidy into an Array the request.
 * Caution : This class only check if the request is well-formed. Process issues on the request are not its matter.
 * @author Lou Carther <dieudrichard@gmail.com>
 */
class URL_HANDLER extends MOTHER
{
        private $prodconf_xmlscope;
        private $table_of_url_params_from_user;
        private $want_tidy;
        private $required_url_params;
        private $optional_url_params;
        private $composed_url_params;
        /**
         * This is the constructor for the class.
         * Make sure you insered the request. Elsewhere, an exception will be thrown. 
         * @param type $entry_req
         */
        function __construct($entry_prodconf_xmlscope) 
        {
            if ( isset($entry_prodconf_xmlscope) and is_array($entry_prodconf_xmlscope) and count($entry_prodconf_xmlscope) > 0 ) {
                //Innitializing parent class
                parent::__construct(__FILE__,__CLASS__);
                $this->prodconf_xmlscope = $entry_prodconf_xmlscope;
                $this->table_of_url_params_from_user = array();
                $this->run();
            } else $this->signalError ("err_sys_l00",__FUNCTION__, __LINE__);
                                      
        }
        
        private function run()
        {            
            //We begins by checking if the URL is well-formed.
            $this->is_the_req_well_formed();
            //No we're sure the URL is correct and match with the configuration of the Webmaster
            //We apply a special treatment to url_params so that they will be treated
            $this->treat_url_params_value_about_spcarac($_GET);
            //Now we must (If they exist) format composed url_params. We won't tidy them, it's a matter of URQ_CHECHER 
            //We don't consider here UPS. This service is a general one. It must be able to understand and handle all url configurations.
            $this->treat_composed_url_params($_GET);
            $this->create_want_tidy();
        }
        
        /**********************************************************************************************************/
        /**************************************** GETTERS ABD SETTERS AREA*****************************************/
        /**********************************************************************************************************/ 
        /**
         * This getter returns the user's request into an array.
         * The array's layout is known by all.
         * It contains (layout) : hoster (target) | application | Page | urq section
         * @return The urser's request formatted into an array.
         * @return The setter returns FALSE if the array is not set, not an array or empty. 
         */
        public function getWantTidy() {
            
            $result = ( isset($this->want_tidy) and is_array($this->want_tidy) and !empty($this->want_tidy ) ) ? $this->want_tidy : FALSE;
            //$this->presentVar(__FUNCTION__,__LINE__,$result);
            return $result;
        }

       /**********************************************************************************************************/
       /************************************** PROCESSING FUNCTIONS AREA *****************************************/
       /**********************************************************************************************************/
                
       /**
        * Permet de gérer le cas où l'utilisateur n'a donné aucun paramètre.<br/>
        * Cela arrive lorsque l'on atteint uniquement index.php (example : www.domain.com)
        * La méthode va mettre en forme urq automatiquement en fonction de la config donnée par le Webmaster.
        */
       private function first_treatment () {
           $table_of_url_default_params = array();
           foreach ($this->prodconf_xmlscope["url_params_default"] as $key => $value) {
               $table_of_url_default_params[$key] = $value;
           }
//           $this->presentVarIfDebug(__FUNCTION__,__LINE__,$table_of_url_default_params);
           return $table_of_url_default_params;
       }
       
       
       private function is_the_req_well_formed() {
            //RAPPEL : La variable $_GET est toujours définie, il faut plutot verifier si elle est vide !
            if ( !count($_GET)>0 ) $_GET = $this->first_treatment ();   
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$_GET);
            
            //We check now with rules and make sure we got required params and If exist optional params needed by prod. It's the minima
            //Our goal is not here to say if optional params are necessary or not for the process. We check if they are defined by the user and Webmaster.
            //To know if they are necessary, check URQ Definition
            //We don't also care if URQ wants more url_params, it's a matter of URQ_CHECKER
            
            //REQUIRED PARAMS
            $this->required_url_params = $this->treat_requiered_element ($_GET,array_keys($this->prodconf_xmlscope["url_params_required"]));
            //OPTIONAL PARAMS
            $this->optional_url_params = $this->treat_optional_element($_GET, array_keys($this->prodconf_xmlscope["url_params_optional"]));
            $this->handle_too_much_params($_GET, $this->required_url_params, $this->optional_url_params);
        }
        
        
        private function treat_requiered_element ($entry_list_elmnt_to_check,$entry_list_elmnt_reference)
        {
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry_list_elmnt_reference);
            //I prefer to use my own process because php's default array functions don't look sure enough to me and don't allow me to master exactly what I need.
            $local_new_array = array();
            //We get the number of required elements
            $list_of_req_elmnt_count = count($entry_list_elmnt_reference);

            foreach($entry_list_elmnt_to_check as $elmnt_key => $elmnt_value){
               if( in_array($elmnt_key,$entry_list_elmnt_reference) and $elmnt_value != "") 
                  $local_new_array[$elmnt_key] = $elmnt_value;    
            }
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$local_new_array);
            //We ensure all the required params have been given by the visitor
            if( count($local_new_array) == $list_of_req_elmnt_count ) return $local_new_array;
            else $this->signalError ("err_user_l18",__FUNCTION__, __LINE__);
        }
        
    
        private function treat_optional_element ($entry_list_elmnt_to_check, $entry_list_elmnt_reference)
        {
            $local_new_array = array();

            foreach($entry_list_elmnt_to_check as $k => $v){
               if( in_array($k,$entry_list_elmnt_reference) and $v != "" ) 
                  $local_new_array[$k] = $v;    
            }
            //We ensure that at least one optional params is present
            //If we got too much ups it's the matter of the caller.
            if ( count($local_new_array)>0 ) return $local_new_array;
            else return NULL;//Means no optional emlement is recognized. 
        }
        
        
        private function handle_too_much_params($entry_list_elmnt_to_check,$entry_new_elmnt_required_tidy,$entry_new_elmnt_optional_tidy)
        {
            $local_count_want_ups = ( isset($entry_list_elmnt_to_check) ) ? count($entry_list_elmnt_to_check) : 0;
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry_list_elmnt_to_check);
            $local_count_urq_req = ( isset($entry_new_elmnt_required_tidy) ) ? count($entry_new_elmnt_required_tidy) : 0;
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry_new_elmnt_required_tidy);
            $local_count_urq_opt = ( isset($entry_new_elmnt_optional_tidy) ) ? count($entry_new_elmnt_optional_tidy) : 0;
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry_new_elmnt_optional_tidy,'v_d');

            if ( ($local_count_want_ups) > ( $local_count_urq_req + $local_count_urq_opt ) ) { 
                $this->presentVarIfDebug(__FUNCTION__,__LINE__,[
                    "WANT_UPS"      => $entry_list_elmnt_to_check,
                    "REQUIRED_UPS"  => $entry_new_elmnt_required_tidy,
                    "OPTIONAL_UPS"  => $entry_new_elmnt_optional_tidy
                ]);
                $this->signalError("err_user_l19",__FUNCTION__, __LINE__);
            } else {
                return TRUE;
            }
        }
        
        /**
         * Transformer les caractères spéciaux se trouvant dans les values de params.
         * Attention : Il ne s'agit pas de faire le travail de DCLEANER.
         * Nous le faisons avant tout des soucis de traitement notamment pour réccupérer les ups 
         * @param array $entry_array_params
         * @return array
         */
        private function treat_url_params_value_about_spcarac ($entry_array_params) {
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry_array_params);
            //We don't check if entry is set because we're sure it is, $_GET can't be void
            foreach ($entry_array_params as $key => $value) {
                $entry_array_params[$key] = $this->transformUrqAddSpChar($value);
            }
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry_array_params);
            return $entry_array_params;
        }

        
        //Peut être utile à DCLEANER 
        private function transformUrqAddSpChar($entry)
        {
            if ( isset($entry) and $entry != "" ) {
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry);
                $entry = urlencode($entry);
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry);
                $to_wipe = array("%3B","+","%20","%3D","%7C","%25","%2A",'%40');
                $to_add   = array(";","","","=","|","%","*",'@');
                return str_replace($to_wipe, $to_add, $entry);
            } else {
                $this->signalError("err_sys_l00",__FUNCTION__, __LINE__);
            }
        }
        
        
        private function treat_composed_url_params($entry_url_params)
        { 
            foreach ($entry_url_params as $key => $value) {
                if ( (key_exists($key, $this->prodconf_xmlscope["url_params_required"])) && ($this->prodconf_xmlscope["url_params_required"][$key] == "composed") ) {
                    $this->composed_url_params[$key] = $this->format_composed_url_params($value);
                }
                if ( (key_exists($key, $this->prodconf_xmlscope["url_params_optional"])) && ($this->prodconf_xmlscope["url_params_optional"][$key] == "composed") ) {
                    $this->composed_url_params[$key] = $this->format_composed_url_params($value);
                }
            }
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->composed_url_params);
        }
        
        
        private function format_composed_url_params($entry)
        {
            $default_params_separator = $this->prodconf_xmlscope["default_params_separator"];
            $default_couple_separator = $this->prodconf_xmlscope["default_couple_separator"];
            
            $couples_of_params = explode($default_params_separator,$entry);
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__,$couples_of_params);
//            exit();  
            if ( isset($couples_of_params) && count($couples_of_params) > 0 )
            {
                $params_table = array();
                foreach ($couples_of_params as $couple) {
                    $key_value_couple = explode($default_couple_separator,$couple);
//                    $this->presentVarIfDebug(__FUNCTION__,__LINE__,$key_value_couple);
                    if ( isset($key_value_couple) && count($key_value_couple) == 2 ) {
                        $params_table[$key_value_couple[0]] = $key_value_couple[1];                        
                    } else {
                        $this->presentVarIfDebug(__FUNCTION__,__LINE__,$couple);
                        $this->signalError("err_user_l110",__FUNCTION__, __LINE__);
                    }
                }
//                $this->presentVarIfDebug(__FUNCTION__,__LINE__,$params_table);
//                exit();
                return $params_table;
                
            }//La fonction explode, si elle ne trouve pas le separateur elle revoie un tableau de 1 element, cet elecment est le paramètre qui lui est envoyé. Aussi, c'est impossible que count soit ==0        
        }
        
        /**
         * Cette fonction est la seule fonction non générique de ce service.
         * Elle répond au schéma stricte : user, page, urqid, ups
         * [NOTE 10-08-14]
         * On rend cette fonction générique.
         * En effet, on récupère toutes les données fournies par l'utilisateur.
         * C'est aux autres modules de s'assurer si les valeurs entrantes sont attendues, requises ou pas.
         * Cependant, on ne change pas la totalité de la fonction. On n'y ajoute des instructions sans modifier l'architecture déjà présente.
         * On est en phase d'intégration. Je ne veux pas perdre du temps avec WOS quand il focntionne assez bien dans son état actuel.
         */
        private function create_want_tidy () {
            
            $this->want_tidy['user'] = ( key_exists("user", $_GET) ) ? $_GET["user"] : NULL;
            //Les 3 valeurs suivantes sont elles déjà controlées en amont
            $this->want_tidy['page'] = $_GET["page"];
            $this->want_tidy['urqid'] = $_GET["urqid"];
            $this->want_tidy['ups_tidy'] = $this->composed_url_params;
            
//            var_dump($this->want_tidy['ups_tidy']);
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $this->want_tidy['ups_tidy']);
            
            //[NOTE 10-08-14] Prise en compte des autres paramètres
            $others = ["user","page","urqid"];
            foreach ($_GET as $k => $v) {
                if (! in_array($k, array_keys($this->want_tidy)) )
                    $this->want_tidy[$k] = $v;
            }
            
        }
        
}
?>