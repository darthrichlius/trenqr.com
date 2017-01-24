<?php
require_once WOS_PATH_FMK_PAR_MOTH;

class INDEX extends MOTHER
{
    private $prod_conf_path;
    private $prod_conf_tidy;
    private $want_tidy;
    
    
    public function __construct() 
    {
        //We ensure that until the mother this class is innit.  
        parent::__construct(__FILE__, __CLASS__);
        
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $_GET);
    }

    
    public function run() 
    {
        //The two lines below allow to initialize the process by checking some path or var.
        $this->obtainProdConfFilePath();
        //Here we include a part of the framework's components. 
        $this->importSystemFmkEnv();
        //From the conf file we acquire the running mode. This is important for the processes forthcoming.
        $err_code = $this->acquireProdRunningModeFromConf($this->prod_conf_path);
        //Check the err code : 0 > File is not set, 1 > File doesn't exist, 2 > We can't load the file, 3 > Everything's doing well.
        if ( $err_code == EXC_GO_ON or $err_code == EXC_YES ) { 
            
            //We send the RM to set ErrEnv
            $this->setPlatformRunningEnv();
            //We tidy the want_raw into want_tidy. The function returns an array where we can found the following needs : prod+target+app+page+urq
            $err_code = $this->acquireWantRawIntoWantTidy();
            //var_dump($err_code);
            if($err_code != EXC_GO_ON and $err_code != EXC_YES) $this->signalError("err_sys_l11",__FUNCTION__, __LINE__);
            else { 
                
                //HERE WE GO
                $this->startProdProcess();
            }
        } else $this->signalError("err_sys_l10",__FUNCTION__, __LINE__);
    }
   
    
    //Tested and works
    private function obtainProdConfFilePath()
    {
        //Pick and save Prod_Conf 
        $this->prod_conf_path = PROD_CONF_FILE;
    }
    
    
    //Tested and works
    private function importSystemFmkEnv()
    {
        //Import All Factories
        require_once WOS_PATH_INC_FACTS;
        //Import All Services
        require_once WOS_PATH_INC_SRVCS;
    }
    
    //Tested and works (ne sert a rien, only acquireProdTidy would be sufficient)
    private function acquireProdRunningModeFromConf($entry)
    {
        $code_err = $this->acquireProdTidy($entry);
        
        if ( isset($this->prod_conf_tidy) and is_array($this->prod_conf_tidy) and count($this->prod_conf_tidy) > 1 ) $code_err = EXC_GO_ON;
        
        return $code_err;
        
    }
    
    //Tested and works
    private function acquireProdTidy($entry)
    {
        //18-09-13 : Pour l'instant on ne refactorise pas cette fonction comme pour les autres qui consistent toutes à recupérer un XmlScope. (Voir XMLTools)
        $code_err = EXC_ABORT;
        
        //We begin by checking if the param is a file.
        if( isset($entry) )
        {
            $xml_tools = new MyXmlTools();
            
            $dom = $xml_tools->checkXmlFileInTripleAction($entry);
            //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $dom);
            if ( isset($dom) and is_object($dom))
            {
                $var="";
                //TODO : Pour récupérer le nom du produit on extrait du nom de domaine le nom du produit. Ex : wwww.trenqr.com => trenqr
                $var= MyXmlTools::recursFinderIntoArray($dom->getElementById("trenqr"), $var);
                //var_dump($toto);
                if( is_array($var) and count($var)>1 )
                {
                    $this->prod_conf_tidy = $var;
                    /*
                     * CONF_FILE_IS_TREATED allows to be sure the conf file has been treated.
                     * CONF_FILE defines how to handle errors and debug procedures. If an error occures before it treated a default page will appears.
                     * Some procedures need infos contained into prod_conf_file. You must notice them if the file has been treated. 
                    */
                    define("CONF_FILE_IS_TREATED",true); //Note : A quoi ça sert ? [NOTE au 07/11/13] : A savoir si le fichier de conf a été traié et éviter le declenchement de certains erreurs ou stopper la plateforme car certaines données contenues dans ce fichiers sont essentielles.
                    $code_err = EXC_GO_ON;
                }
            } else $this->signalError ("err_sys_l16",__FUNCTION__, __LINE__);
        } else $this->signalError ("err_sys_l00",__FUNCTION__, __LINE__);
        return $code_err;
    }
    
    //Tested and works
    private function setPlatformRunningEnv()
    {
        //var_dump($this->prod_conf_tidy);
        $local_prod_name =  $this->prod_conf_tidy['prod_name'];
        
        if( $this->prod_conf_tidy['prod_conf']['is_installed'] != "yes" )
        {
            $this->signalError("err_sys_l12",__FUNCTION__, __LINE__);
            exit;
        }
        else if($this->prod_conf_tidy['prod_conf']['w_status'] != 'on')
        {
            $this->signalError("err_sys_l13",__FUNCTION__, __LINE__);
            exit;
        }
        else if( defined("_ENV_BKDR_MD") ) { //Especially added for SBKD
            if (_ENV_BKDR_MD) {
            ini_set('display_startup_errors', 1);
            ini_set('display_errors', 1);
            error_reporting(E_ALL | E_NOTICE);
            define("PTF_RM",T_RM_DBRM);
            }
        }
        else if($this->prod_conf_tidy['prod_conf']['is_debug'] == 'yes')
        {
            //echo "EN MODE DEBUG";
            
            define("RIGHT_IS_DEBUG",true);
            define("HUMAN_MODE", HUMAN_MODE_ACTIVATE);
            
            switch($this->prod_conf_tidy['prod_conf']['r_mode'])
            {
                case T_RM_DBRM:
                        ini_set('display_startup_errors', 1);
                        ini_set('display_errors', 1);
                        error_reporting(E_ALL | E_NOTICE);
                        define("PTF_RM",$this->prod_conf_tidy['prod_conf']['r_mode']);
                    break;
                case T_RM_TRM:
                        ini_set('display_startup_errors', 1);
                        ini_set('display_errors', 1);
                        error_reporting(E_WARNING | E_NOTICE);
                        define("PTF_RM",$this->prod_conf_tidy['prod_conf']['r_mode']);
                    break;
            }
        }
        else if($this->prod_conf_tidy['prod_conf']['is_debug'] == 'no')
        {
            //echo "EN MODE NO DEBUG";
            ini_set('display_startup_errors', 0);
            ini_set('display_errors', 0);
            error_reporting(0);
            //echo $this->$this->prod_conf_tidy['prod_conf']['production_r_mode'];
            $prod_rm = (string) $this->prod_conf_tidy['prod_conf']['production_r_mode'];
            
            define("RIGHT_IS_DEBUG",false);
            define("HUMAN_MODE", HUMAN_MODE_DISABLE);
            
            switch($prod_rm)
            {
                case P_RM_PRM :
                        //Nothing to do we let the process carrying out.
                        //echo "EN MODE ".$this->$this->prod_conf_tidy['prod_conf']['production_r_mode'];
                        define("PTF_RM",$this->prod_conf_tidy['prod_conf']['production_r_mode']);
                    break;
                case P_RM_CRM :
                        //echo "EN MODE ".$this->$this->prod_conf_tidy['prod_conf']['production_r_mode'];
                        define("PTF_RM",$this->prod_conf_tidy['prod_conf']['production_r_mode']);
                        require_once WOS_SPAGE_COUNTDOWN_PAGE;
                        exit;
                    break;
                case P_RM_MRM :
                        //echo "EN MODE ".$this->$this->prod_conf_tidy['prod_conf']['production_r_mode'];
                        define("PTF_RM",$this->prod_conf_tidy['prod_conf']['production_r_mode']);
                        require_once WOS_SPAGE_MAINTENANCE_PAGE;
                        exit;
                    break;
                case P_RM_QRM :
                        //echo "EN MODE ".$this->$this->prod_conf_tidy['prod_conf']['production_r_mode'];
                        define("PTF_RM",$this->prod_conf_tidy['prod_conf']['production_r_mode']);
                        require_once WOS_SPAGE_QUARANTINE_PAGE;
                        exit;
                    break;
            }
        }
        else
        {
            $this->signalError("err_sys_l11",__FUNCTION__, __LINE__);
            exit;
        }
        
        /*
         * [DEPUIS 31-08-15] @author BOR
         */
        if ( _ENV_BKDR_MD && _ENV_BKDR_MD === TRUE ) {
            define("RIGHT_IS_DEBUG",TRUE); 
        }
        
    }
    
    //Tested and works 
    private function acquireWantRawIntoWantTidy()
    {
       $result = EXC_ABORT;
       
       $URL_Handler = new Url_Handler($this->prod_conf_tidy);
       
       //if( $URL_Handler->getIs_want_req_well_formed() ){ //NO : If it is not well formed URL_HANDLER will signal an error
           $this->want_tidy = $URL_Handler->getWantTidy();
           //var_dump($this->want_tidy);
           //We want at least : product, target and app_name so it should be min 3
           if ( isset($this->want_tidy) and is_array($this->want_tidy) and  count($this->want_tidy)>1 ) 
                $result = EXC_YES;
       //}
       
       return $result;
   }
   

    private function startProdProcess()
    {
       // :) 
       //We contact PSC here to send it app_conf and want_tidy
       //$entry_want_tidy = null; //DRM FOR DEBUG
       //$entry_appconf_tidy = null; //DRM FOR DEBUG
       
       //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $this->want_tidy);
       $CONTROLLER = new CONTROLLER($this->want_tidy, $this->prod_conf_tidy);

   }
}
?>