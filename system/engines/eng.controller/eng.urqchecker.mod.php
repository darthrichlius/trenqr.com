<?php

Class PCC_URQCHECKER extends MOTHER
{
    private $running_lang;
    private $user_page_lang;
    private $sys_suggest_page;
    private $urq_scope;
    private $want_tidy;
    private $curr_wto;
    private $prev_wto;
    private $required_ups;
    private $optional_ups;
    
    function __construct($entry_want_tidy, $running_lang) 
    {
        //We ensure that until the mother this class is innit.  
        parent::__construct(__FILE__, __CLASS__);
        //>1 car il faut au moins 2 valeurs
        //On ne modifie pas le checker de args pour l'instant [20-09-13]. On factorise seulement pour running_lang
        if (  isset($entry_want_tidy) and is_array($entry_want_tidy) and count($entry_want_tidy) > 1 ) {
            $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $running_lang);
            
            $this->want_tidy = $entry_want_tidy;
            $this->running_lang = $running_lang;
//            debug_print_backtrace();
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry_want_tidy,'v_d');   
            
            $this->run();
        }
        else $this->signalError ("err_sys_l00",__FUNCTION__, __LINE__);
    }
    
    
    private function run()
    {
       //Ne pas oublier qu'il s'agit d'un code refactoriser donc on ne peut changer toute la structure mais se débrouiller avec les variables
       $path_to_urqtab = SLP_URQTABDEPOT_PATH;
       //$this->presentVar(__FUNCTION__,__LINE__,$path_to_urqtab);
       $this->acquiereUrqTableIfDefined($path_to_urqtab);
       $this->isTheMatchingASuccess();
       $this->createWantTidyObject();
       /**
        * On va ne va pas traiter ici l'opportunité de savoir s'il faut ou ne pas laisser une requete être traitée ou pas. 
        * Pour l'instant on considère qu'on va laisser passer.
        * 
        * Mais on va voir quand même à titre informatif créer notre historique afin que les autres modules puissent adapter leurs process.
        * C'est le cas par exemple de WM qui doit gérer le cas d'incohérence de langue entre 'run_lang' et 'url_lang'
        */
        $this->handle_user_history_using_wto();
    }
        
    
    private function handle_user_history_using_wto () {
        $STO = new SESSION_TO();
        $session_not_void = PCC_SESSION::doesSessionExistAndIsNotvoid();
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION,'v_d');              
        if(! $session_not_void) { //echo "SESSION is void";
            //Comme on a pas de fichier de SESSION, on laisse prev_wto à NULL et current reste current
            return FALSE; //FALSE car SESSION n'existe pas et il faut quand meme renvoyer quelque chose
        } 
        else { //echo "SESSION is NOT void";
            if ( key_exists("sto_infos",$_SESSION) and count($_SESSION["sto_infos"]) > 0 ) {
                $STO = $_SESSION["sto_infos"];
                /**
                 * [NOTE au 08/11/13]
                 * Il y a un bug au niveau de la decision de WM qui retourne toujours la langue donnée par l'utilisateur.
                 * Ce bug est causé par le fait que lors de la comparaison la valeur de la propriété 'watchman_decision_on_lang' de $STO->getCurr_wto()
                 * est definie quand celle de $this->curr_wto ne l'est pas. Cela est normal mais fausse tout au niveau de la comparaison des deux
                 * objets. Il faut donc mettre cette valeur à NULL pour la comparaison.
                 */
                $wto_temp = new WTO();
                $wto_temp = clone $STO->getCurr_wto(); //clone evite d'avoir la même reférence
                $wto_temp->setWatchman_decision_on_lang(NULL);
                
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $wto_temp,'v_d'); 
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $STO->getCurr_wto(),'v_d'); 
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->curr_wto,'v_d'); 
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->prev_wto,'v_d'); 
                //exit;
                if ( $wto_temp == $this->curr_wto ) {
                    //echo ("<script>alert('Same !');</script>");
                    return FALSE; //En gros on ne change rien
                } else {
                    //echo ("<script>alert('Not Same !');</script>");
                    $this->prev_wto = $STO->getCurr_wto();
                    //L'ancien current devient le prev
                }
            } else $this->signalError ("err_sys_l014", __FUNCTION__, __LINE__);
        }         
    }
    
    
    public function getUrq_scope() {
        return $this->urq_scope;
    }

    
    public function getCurr_wto() {
        return $this->curr_wto;
    }

    
    public function getPrev_wto() {
        return $this->prev_wto;
    }

    
    /**
     * <p>This is an important point.
     * This functin tries to acquiere the urqtable scope if exists.</p>
     * @param type $entry
     */    
    private function acquiereUrqTableIfDefined($entry)
    {
        $xml_tools = new MyXmlTools();
        $this->urq_scope = $xml_tools->acquiereXmlscopeFromPathAndIdInASecureWay($entry, $this->want_tidy["urqid"], "err_user_l34");
//        $this->urq_scope = $xml_tools->acquiereXmlscopeFromPathAndIdInASecureWay($entry, $this->want_tidy["urqid"], "err_user_l5e404_any");
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$entry);
    }
     
    /***********************************************************************************************/
    /********************************** START CHECK THE MATCHING ***********************************/
    /**
     * This function tries to determine if the query matchs with : page, target and params (either they are mandatory or not) 
     */
    private function isTheMatchingASuccess()
    {
        $this->doesUrqMatchWithPage();
        $this->doesUrqMathingParams();
    }
    
    /**
     *  <p>Check if the matching between urq and the page is right</p>
     * @return boolean
     */
    private function doesUrqMatchWithPage()
    {
        if ( isset($this->urq_scope['urq_is_pg_required']) and $this->urq_scope['urq_is_pg_required'] !== "" and $this->urq_scope['urq_is_pg_required'] == "yes" ) {
            if ( isset($this->urq_scope['urqpageid']) and $this->urq_scope['urqpageid'] != "" ){
                $lang = $this->get_lang_matching_pagename_given_by_user_if_correct($this->running_lang);
                //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$lang);
                if ( isset($lang) and $lang !== "" ) {
                    $this->user_page_lang = $lang;  
                } else {
                    $this->signalError ("err_user_l37",__FUNCTION__, __LINE__); //N'est plus d'actualité, on laisse WATCHMAN gérer tout ça
                }
            } else {
                $this->signalError ("err_sys_l36",__FUNCTION__, __LINE__);
            }
        } else if ( isset($this->urq_scope['urq_is_pg_required']) and $this->urq_scope['urq_is_pg_required'] != "" and $this->urq_scope['urq_is_pg_required'] == "no" ) {
            //continue
        } else {
            $this->signalError ("err_user_l35",__FUNCTION__, __LINE__);
        }
    }
    
    
    private function get_lang_matching_pagename_given_by_user_if_correct($running_lang) {
        //1) On va chercher le fichier PAGES_NAMES_DEF
        $path = WOS_GEN_PATH_PAGEDEF;
        //2) On verifie qu'en a un nom correspondant à cet id
        $xml_tools = new MyXmlTools();
        $pgdef_xmlscope = $xml_tools->acquiereXmlscopeFromPathAndIdInASecureWay($path, $this->urq_scope['urqpageid'], "err_sys_l311");
        $pgnames = $pgdef_xmlscope["page.name"];
       // $this->presentVarIfDebug(__FUNCTION__, __LINE__,$pgnames);
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->want_tidy['page']);
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$pgdef_xmlscope);
        
        if ( in_array(strtolower($this->want_tidy['page']), $pgnames) ) { 
            //Premiere validation, ce qui veut dire que le nom de page donnée par USER match avec URQID et PAGEID
            //Maintenant il faut voir ce qu'il en est pour la langue
            //C'est WATCHMAN qui prend cette décision, il faut le lui signaler
            //Pour l'heure on récupère la langue correspondante
            
            //Information pour WATCHMAN
            $this->sys_suggest_page = $pgnames[$running_lang];
            
            //La fonction est sur car on est sur qu'il n'ya pas de doublons dans le tableau en paramètre
            return $lang = array_search(strtolower($this->want_tidy['page']), $pgnames);
        } else return NULL;
    }
        
    
    /**
     * <p>Deal with 'ups' issues.</p>
     * <ul>
     * <li>Ensure all the required ups are present. Only if it is expected to have required ups for the urq.</li>
     * <li>Ensure all the optional ups have been considered. Only if it is expected to have optional ups for the urq.</li>
     * </ul>
     * @return boolean Just formal.
     */
    private function doesUrqMathingParams()
    {
        //$this->presentVar(__FUNCTION__,__LINE__,$this->urq_scope['upstab_required']);
        
        //We're getting all the ups the visitor has inserted. 
        //Note : Code refactorisé donc bidouille
        $list_want_ups = ( array_key_exists('ups_tidy', $this->want_tidy) ) ? $this->want_tidy['ups_tidy']["ups"] : NULL;

        $new_ups_required_tidy = Array();
        $new_ups_optional_tidy = Array();
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->want_tidy);
        
        if ( isset($this->urq_scope['upstab_required']) && is_array($this->urq_scope['upstab_required']) && count($this->urq_scope['upstab_required']) > 0 ) {
            if ( isset($this->want_tidy['ups_tidy']) && is_array($this->want_tidy['ups_tidy']) && count($this->want_tidy['ups_tidy']) > 0 )
            {  
                //We extract the list of ups required as defined in urq_scope.
                $list_ups_requiered = array_keys($this->urq_scope['upstab_required']);
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$list_ups_requiered);
                
                $new_ups_required_tidy = $this->treatRequieredUps($list_want_ups,$list_ups_requiered);
                $this->required_ups = $new_ups_required_tidy;
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$new_ups_required_tidy);
                if ( isset($this->urq_scope['upstab_opt']) and is_array($this->urq_scope['upstab_opt']) and count($this->urq_scope['upstab_opt']) > 0 ){
                    $list_ups_optional = array_keys($this->urq_scope['upstab_opt']);
                    $new_ups_optional_tidy = $this->treatOptionalUps($list_want_ups,$list_ups_optional);
                    $this->optional_ups = $new_ups_optional_tidy;
                }
                
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$new_ups_required_tidy);
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$new_ups_optional_tidy,'v_d');
                //We are sure that the issue over required params is resolved.
                //We are to make sure the user are not trying to insert too much params than expected.
                //So we send the original 'want_ups_tidy' and the new 'reqired_ups_array' and 'optional_ups_array' to see if we have excess  
                //$this->handleTooMuchParams($list_want_ups,$new_ups_required_tidy,$new_ups_optional_tidy);    
                
                //return true;
            } else {
                $this->signalError ("err_user_l310",__FUNCTION__, __LINE__);
            }
        } else if ( isset($this->urq_scope['upstab_opt']) && is_array($this->urq_scope['upstab_opt']) && count($this->urq_scope['upstab_opt']) >= 1 ) {
            if ( isset($this->want_tidy['ups_tidy']) && is_array($this->want_tidy['ups_tidy']) && count($this->want_tidy['ups_tidy']) >= 1 )
            {
                $list_ups_optional = array_keys($this->urq_scope['upstab_opt']);
                $new_ups_optional_tidy = $this->treatOptionalUps($list_want_ups,$list_ups_optional);                
                
//                var_dump($list_want_ups,$list_ups_optional,$new_ups_optional_tidy);
//                exit();
                
                //[NOTE 22-11-14] @author L.C. Ajouté pour prendre en compte le cas où on a des QUE des UPS_OPTIONAL
                if ( isset($new_ups_optional_tidy) ) {
                    $this->optional_ups = $new_ups_optional_tidy;
                }
                //$this->handleTooMuchParams($list_want_ups,$new_ups_required_tidy,$new_ups_optional_tidy); 
                
                //return true;
            }
        }
        
        $this->handleTooMuchParams($list_want_ups,$new_ups_required_tidy,$new_ups_optional_tidy);       
    }
    
    
    /**
     * <p>Try to see if the user has inserted some params which are not defined in the urqtab.
     * If this is the case, we throw (signal) the error and exit the process.</p>
     * @param type $entry_list_want_ups
     * @param type $entry_new_ups_required_tidy
     * @param type $entry_new_ups_optional_tidy
     * @return boolean Just formal.
     */
    private function handleTooMuchParams($entry_list_want_ups,$entry_new_ups_required_tidy,$entry_new_ups_optional_tidy)
    {
        $local_count_want_ups = ( isset($entry_list_want_ups) ) ? count($entry_list_want_ups) : 0;
        $local_count_urq_req = ( isset($entry_new_ups_required_tidy) ) ? count($entry_new_ups_required_tidy) : 0;
        $local_count_urq_opt = ( isset($entry_new_ups_optional_tidy) ) ? count($entry_new_ups_optional_tidy) : 0;

        if ( ($local_count_want_ups) > ($local_count_urq_req+$local_count_urq_opt) ) {
            $this->signalError("err_user_l312",__FUNCTION__, __LINE__);
        } else {
            return true;
        }
    }
    
    
    private function createWantTidyObject()
    {
        $Temp_WTO = new WTO();
        //Meme si USER n'est pas défini, on le considère quand même. Le script doit prévoir le cas où il le sera
        $Temp_WTO->setUser($this->want_tidy['user']);
        $Temp_WTO->setUserSuppliedPage($this->want_tidy['page']);
        $Temp_WTO->setSysSuggestPageBasedOnRunningLang($this->sys_suggest_page);
        $Temp_WTO->setUserSuppliedPageLang($this->user_page_lang);
        $Temp_WTO->setUrqid($this->want_tidy['urqid']);
        $Temp_WTO->setUps_required($this->required_ups);
        $Temp_WTO->setUps_optional($this->optional_ups);
        
             //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$Temp_WTO);
        $this->curr_wto = $Temp_WTO;
    }

    /***********************************************************************************************/
    /********************************** START TREAT UPS REQUIRED ***********************************/
    /**
     * <p>Create a new array with exclusively 'required ups' contained in want_ups.</p>
     * @param type $given_ups
     * @param type $required_ups
     * @return type
     */
    private function treatRequieredUps($given_ups, $required_ups)
    {
        //I prefer to use my own process because php default array functions don't seem sure to me enough and don't allow me to master exactly what I need to.
        $local_new_array = array();
        
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__,[$given_ups, $required_ups]);
        foreach ( $given_ups as $k => $v ) 
        {
           if ( in_array($k,$required_ups) && $v !== "") 
           {
              $local_new_array[$k] = $v;    
           }
        }
        
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__,[count($local_new_array),count($required_ups)]);
        
        //We ensure all the required params have been given by the visitor
        if ( count($local_new_array) === count($required_ups) ) 
        {
            return $local_new_array;
        } else 
        {
            $this->presentVarIfDebug(__FUNCTION__,__LINE__,["EXPECTED => ",$required_ups]);
            $this->presentVarIfDebug(__FUNCTION__,__LINE__,["WE GOT => ",$given_ups]);
            $this->signalError ("err_user_l312",__FUNCTION__, __LINE__);
        }
    }

    /**
     * <p>Create a new array with exclusively optional ups present in want.</p>
     * @param type $entry_list_ups_want
     * @param type $entry_list_ups_optional_urqtab
     * @return boolean
     */
    private function treatOptionalUps($entry_list_ups_want, $entry_list_ups_optional_urqtab)
    {
        $local_new_array = array();

        foreach($entry_list_ups_want as $single_want_ups_key => $single_want_ups_value){
           if( in_array($single_want_ups_key,$entry_list_ups_optional_urqtab) and $single_want_ups_value != "" ) 
              $local_new_array[$single_want_ups_key] = $single_want_ups_value;    
        }
        //We ensure that at least one optional params is present
        //If we got too much ups it's the matter of the caller.
        return ( count($local_new_array) ) ? $local_new_array : NULL;
    }
    /********************************** END TREAT UPS REQUIRED *************************************/
    /***********************************************************************************************/
    
    
    /********************************** END CHECK THE MATCHING *************************************/
    /***********************************************************************************************/
    
}
?>
