<?php
require_once WOS_PATH_FMK_PAR_SRVC;

/*
 * Pour éviter des phenomènes de boucle infinie, la classe n'hérite pas de SERVICE.
 * Cependant, on considère tout de même qu'il s'agit d'un module de type Service.
 */
class Err_Handler 
{
    private $code = "url_h";
    private $is_no_errid_case;
    private $spnoerrid_msg;
    private $errTable;
    private $errConcerned;
    private $repport;
    private $errtype;
    
    function __construct() {
        
    }
    
    public function myErrorHandler($entry_array)
    {
        if( isset($entry_array) and is_array($entry_array) and count($entry_array) > 1 and $entry_array['errno'] != "" )
        {
            $this->report = $entry_array;
//            var_dump($this->report);
            $this->run();
        }
        else
        {
			if ( ( defined('RIGHT_IS_DEBUG') && RIGHT_IS_DEBUG === TRUE ) | ( defined('IS_DEBUG') && IS_DEBUG === TRUE ) ) {
                debug_print_backtrace();
                $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals it has never received the error package. Then, a standard is trigger.</p>";
                echo $msg;
            } else {
                echo "FINAL_ERR_ON_QUEUE";
            }
            
            /* //[NOTE 06-09-15] @author BOR
            $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals it has never received the error package. Then, a standard is trigger.</p>";
            trigger_error ($msg,E_USER_ERROR);
            exit;
            //*/
        }
    }
    
    public function myErrorHandlerWhenNoErrGivenButMsg($entry_array, $msg, $avoid_bleeding = FALSE)
    {
		
        if ( $avoid_bleeding || ( key_exists("errno",$entry_array) && $entry_array['errno'] && strtolower($entry_array['errno']) === "err_sys_noloop" ) ) {
			if ( ( defined('RIGHT_IS_DEBUG') && RIGHT_IS_DEBUG === TRUE ) | ( defined('IS_DEBUG') && IS_DEBUG === TRUE ) ) {
                debug_print_backtrace();
                $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals it stopped in order to avoid infinite loop.</p>";
                echo $msg;
            } else {
                echo "FINAL_ERR_ON_QUEUE";
            }
            /* //[DEPUIS 06-09-15] @author BOR
            $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals it stopped in order to avoid infinite loop.</p>";
            trigger_error ($msg,E_USER_ERROR);
            //*/
        } else if ( isset($entry_array) and (isset($msg) and $msg!="") and is_array($entry_array) and count($entry_array) > 1 and $entry_array['errno'] != "" ) {
            $this->report = $entry_array;
            $this->is_no_errid_case = TRUE;
            $this->spnoerrid_msg = $msg;
            //var_dump($this->report);
            $this->run();
        } else {
            if ( ( defined('RIGHT_IS_DEBUG') && RIGHT_IS_DEBUG === TRUE ) | ( defined('IS_DEBUG') && IS_DEBUG === TRUE ) ) {
                debug_print_backtrace();
                $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals it has never received the error package. Then, a standard is trigger.</p>";
                echo $msg;
            } else {
                echo "FINAL_ERR_ON_QUEUE";
            }
            /* //[DEPUIS 06-09-15] @author BOR
            $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals it has never received the error package. Then, a standard is trigger.</p>";
            trigger_error ($msg,E_USER_ERROR);
            exit;
            //*/
        }
    }
    
    /**
     * <p>Cette methode est destisnée à renvoyer le message d'erreur correspondant à un err_code.
     * Elle est necessaire lorsque l'on ne veut pas déclencher une erreur mais seulement obtenir le message.</p>
     * <p>Elle est utile par exemple pour signifier des messages d'erreur à l'utilisateur au niveau <br/>
     * des formulaires sans changer de page. </p>
     * 
     * @param type $err
     * @return type
     */
    public function get_error_msg ($err, $errfunc, $errline, $user_msg = FALSE) {
        //Utiliser pour obtenir un msg d'err. Cette methode ne renvoie que message user et non sys.
        
        $this->acquireErrorTable();
        $go_on1 = ( $this->checkIfNumErrExist($err) ) ? TRUE : FALSE;
        $this->handleErrnoNotFound($go_on1,$err,["ERR_FUNC" => $errfunc, "ERR_LINE" => $errline]);
        $this->acquireErrConcernedIntoArray($err);
        $msg = ($user_msg === TRUE) ? $this->errConcerned['user_errmsg'] : $this->errConcerned['sys_errmsg'];
        return $msg;
    }
    
    
    private function run()
    {//Si $spRunning est spécifié alors il s'agit de gérer le cas où on a un msg au lieu d'un errId
        //AcquireErrorTable
		
        $this->acquireErrorTable();
		
//        var_dump($this->errTable);
        //we check in the tab if there is a key matching with the num_err
        $go_on1 = ( $this->checkIfNumErrExist($this->report['errno']) ) ? TRUE : FALSE;
//        var_dump($go_on1);

        $this->handleErrnoNotFound($go_on1,$this->report['errno']);
		
        $this->acquireErrConcernedIntoArray($this->report['errno']);
		
        $go_on2 = $this->acquireErrTypeOfErrnoIfExist();
//        var_dump(89,$go_on2);

        $this->handleErrTypeNotFound($go_on2);
		
        $this->decide();
		
    }
    
    
    //Tested and works
    //Refactor : We will only use this function if it's a 
    private function acquireErrorTable()
    {
        //Refactor : Add a switch to choose a lang. 
        //try to know the current lang define.
        //When you got it, switch to see if we got an errtab matching
        //If it's doesn't switch choose english
        $xml_tools = new MyXmlTools();
            
        $dom = $xml_tools->checkXmlFileInTripleAction(LANG_FR_ERRTABLE);
        //var_dump($dom);
        $entry_array = "";
        $this->errTable = MyXmlTools::recursFinderintoArray($dom->documentElement, $entry_array);
    }
    
    
    private function checkIfNumErrExist($err)
    {
       return array_key_exists($err, $this->errTable) ;
    }
    
    
    private function handleErrnoNotFound($entry, $errno, $options = NULL )
    {
        if (! $entry ) {
            if ( ( defined('RIGHT_IS_DEBUG') && RIGHT_IS_DEBUG === TRUE ) | ( defined('IS_DEBUG') && IS_DEBUG === TRUE ) ) {
                debug_print_backtrace();
                if ( isset($options) ) {
                    var_dump($options);
                }
                $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals it can't authentify errno ($errno)given by the caller.</p>";
                echo $msg;
            } else {
                echo "FINAL_ERR_ON_QUEUE";
            }
            
            /* //[NOTE 06-09-15] @author BOR
            if ( (defined("RIGHT_IS_DEBUG") && RIGHT_IS_DEBUG == "true") and defined("CONF_FILE_IS_TREATED") ) {
                 debug_print_backtrace();
            }
             
            if ( isset($options) ) {
                var_dump($options);
            }
             
            $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals it can't authentify errno ($errno)given by the caller.</p>";
            trigger_error ($msg, E_USER_ERROR);
            exit;
            //*/
        }            
    }
    
    
    private function acquireErrTypeOfErrnoIfExist()
    {
        return ($this->errConcerned['errtype'] != "");        
    } 
    
    
    private function acquireErrConcernedIntoArray($err)
    {
       $this->errConcerned = $this->errTable[$err];
    }
    
    
    private function handleErrTypeNotFound($entry)
    {
        if (! $entry ) {
			if ( ( defined('RIGHT_IS_DEBUG') && RIGHT_IS_DEBUG === TRUE ) | ( defined('IS_DEBUG') && IS_DEBUG === TRUE ) ) {
                debug_print_backtrace();
                $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals there is no errtype in errTable.<p>";
                echo $msg;
            } else {
                echo "FINAL_ERR_ON_QUEUE";
            }
            
            /* //[DEPUIS 06-09-15] @author BOR
             $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals there is no errtype in errTable.<p>";
             trigger_error ($msg, E_USER_ERROR);
             exit;
             //*/
        } else {
            $this->errtype = $this->errConcerned['errtype'];
        }
    }
    
    
    private function decide()
    {
		
        //We begin by log the error
        error_log($this->errConcerned['sys_errmsg']);
       
        //We trust the DTD, we don't really check if errtype is adequate
        switch($this->errtype)
        {
            case "E_USER_ERROR" :
					
                    $this->handleUserFatalErrorCase();
					
                break;
            case "E_USER_WARNING" :
					
                    $this->handleUserWarningErrorCase();
					
                break;
            case "E_USER_NOTICE" :
					
                    $this->handleUserNoticeErrorCase();
					
                break;
            default : 
					if ( ( defined('RIGHT_IS_DEBUG') && RIGHT_IS_DEBUG === TRUE ) | ( defined('IS_DEBUG') && IS_DEBUG === TRUE ) ) {
                        debug_print_backtrace();
                        $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals it is very confused. Despite DTD check, errno is not a PHP CONSTANT.</p>";
                        $msg .= "<p>Hint : Are you in a sandbox ?</p>";
                        echo $msg;
                    } else {
                        echo "FINAL_ERR_ON_QUEUE";
                    }
                    /* //[DEPUIS 06-09-15] @author BOR
                    $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals it is very confused. Despite DTD check, errno is not a PHP CONSTANT.</p>";
                    $msg .= "<p>Hint : Are you in a sandbox ?</p>";
                    trigger_error ($msg, E_USER_ERROR);
                    //*/
                break;
        }
		
		exit;

    }
    
    
    private function handleUserFatalErrorCase()
    {
		
        $db_mode = "";
        
        if ( defined('RIGHT_IS_DEBUG') ) {
			
            $db_mode = RIGHT_IS_DEBUG;
			
        } else {
			
            $db_mode = IS_DEBUG; 
			
        }
		
        if ( strtoupper($db_mode) === 'TRUE' || $db_mode === TRUE ) {
			
            $newErrMsg = $this->formatErrmgForDebugMode();
			
            echo $newErrMsg;
			
            exit;
        } else if ( $db_mode === FALSE ) {
			
            $ErrMessage = $this->errConcerned['user_errmsg'];
			
            $ErrCode = $this->report['errno'];
            
            /*
             * Log de l'erreur et envoi de l'email le cas échéant.
             * Cette opération est réalisée avant l'affichage de la page pour être sur qu'on ait une trace de l'action quelque soit l'action de l'user.
             * Le temps maximum d'exécution de cette instruction se situe autour des 2 secondes.
             * Il s'agit là d'un temps acceptable.
             */
			//*
			$err_msg;
            if ( $this->errConcerned['sys_errmsg'] ) {
                $err_msg = $this->errConcerned['sys_errmsg'];
            } else if ( $this->spnoerrid_msg ) {
                $err_msg = $this->spnoerrid_msg;
            } 
            $this->WarmTechSupportOnError($err_msg);
			//*/
            // $this->WarmTechSupportOnError($this->errConcerned['sys_errmsg']); //[DEPUIS 06-09-15]
            
            //Vers la page d'erreur
            require_once WOS_SPAGE_FORUSER_ERR_DISPLAYER_PAGE;
			
            
        }
    }
    
    private function WarmTechSupportOnError ($ErrMessage) {
		
		
        /*
         * Permet d'envoyer un email à l'équipe support si le cas l'autorise.
         * On ne signale que les erreurs de type "system" et certaines erreurs de type user clairement identifiées.
         * Ce choix se justifie par le fait que les erreurs systèmes sont dans la plupart des cas des erreurs handicapantes.
         * De plus, ils sont le fait d'erreur de conception. Avoir des rapports complets de façon quasi instantannée permettrait de corriger les ses erreurs ...
         * ... et de les réduire tout au long de la durée de vie du produit.
         * Il s'agit donc d'un interet qualitatif mais aussi conceptuel.
         */
        /*
        if ( !$ErrMessage ) {
            $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals it has never received arg. Then, a standard is trigger.</p>";
            trigger_error($msg,E_USER_ERROR);
            exit;
        }
        //*/
        /*
         * [DEPUIS 06-09-15] @author BOR
         */
        if (! $ErrMessage ) {
            $ErrMessage = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." says : No system error message available.</p>";
        }
		
		    
        //report => errno, errfile, errclass, errfunc, errline
        
        $errno = $this->report["errno"];
        
        //On vérifie s'il s'agit d'une erreur de tye "system"
        if (! preg_match("/err_sys/", $errno) ) {
			
//            echo "NO_LOG";
            /*
             * TODO : On vérifie si l'erreur fait partie de la liste des erreurs de type user qu'il faut quand même signaler.
             */
        } else {
			
            //On rassemble les données pour l'enregistrement dans la base de données. Elles serviront aussi pour l'envoid du mail
            $now = round(microtime(TRUE)*1000);
            
            //TODO : On vérifie que les données fournies par SERVER sont authentiques (sécurité, fiabilité)
            if (! filter_input(INPUT_SERVER, "REMOTE_ADDR", FILTER_VALIDATE_IP) ) {
                //TODO : Lancer une erreur
            }
            if (! filter_input(INPUT_SERVER, "SERVER_ADDR", FILTER_VALIDATE_IP) ) {
                //TODO : Lancer une erreur
            }
            
//            var_dump(isset($_SESSION), key_exists("rsto_infos", $_SESSION), is_object($_SESSION["rsto_infos"]), isset($_SESSION["rsto_infos"]));
//            var_dump($_SESSION["rsto_infos"]->getAccid());
            
            ob_start();
            debug_print_backtrace();
            $trace = ob_get_clean();
            
            $infos = [
                "err_code" 			=> $errno,
                "err_msg" 			=> $ErrMessage,
                //-- > WHO ?
                "err_ssid" 			=> ( isset($_SESSION) && @session_id() ) ? @session_id() : NULL,
                "err_uid" 			=> ( isset($_SESSION) && key_exists("rsto_infos", $_SESSION) && is_object($_SESSION["rsto_infos"]) && isset($_SESSION["rsto_infos"]) ) ? $_SESSION["rsto_infos"]->getAccid() : NULL,
                "err_ueid" 			=> ( isset($_SESSION) && key_exists("rsto_infos", $_SESSION) && is_object($_SESSION["rsto_infos"]) && isset($_SESSION["rsto_infos"]) ) ? $_SESSION["rsto_infos"]->getAcc_eid() : NULL,
                "err_upsd" 			=> ( isset($_SESSION) && key_exists("rsto_infos", $_SESSION) && is_object($_SESSION["rsto_infos"]) && isset($_SESSION["rsto_infos"]) ) ? $_SESSION["rsto_infos"]->getUpseudo() : NULL,
                //-- > WHERE ?
                "err_srvip" 		=> sprintf('%u', ip2long($_SERVER["SERVER_ADDR"])),
                "err_srvname" 		=> $_SERVER["SERVER_NAME"],
                "err_file" 			=> $this->report["errfile"],
                "err_class" 		=> $this->report["errclass"],
                "err_func" 			=> $this->report["errfunc"],
                "err_line" 			=> $this->report["errline"],
                //-- > HOW ?
                "err_uri" 			=> $_SERVER["REQUEST_URI"],
                "err_locip" 		=> sprintf('%u', ip2long($_SERVER["REMOTE_ADDR"])),
                "err_referer" 		=> ( key_exists("HTTP_REFERER", $_SERVER) && $_SERVER["HTTP_REFERER"] ) ? $_SERVER["HTTP_REFERER"] : NULL,
                "err_user_agent" 	=> ( $_SERVER["HTTP_USER_AGENT"] ) ? $_SERVER["HTTP_USER_AGENT"] : "",
                "err_trace" 		=> $trace,
                //-- > WHEN ?
                "tstamp" 			=> $now 
            ];
//            var_dump($infos);
//            exit();
            
            //On tente une opération d'enregistre dans la base de données. Cela permet d'avoir une copie de l'erreur même si on a un problème au niveau de l'envoi de l'email
            $QO = new QUERY("qryl4errlgn1");
            $params = array( 
                ":err_code" 		=> $infos["err_code"], 
                ":err_msg" 			=> $infos["err_msg"], 
                
                ":err_ssid" 		=> $infos["err_ssid"], 
                ":err_uid" 			=> $infos["err_uid"], 
                ":err_ueid" 		=> $infos["err_ueid"], 
                ":err_upsd" 		=> $infos["err_upsd"], 
                
                ":err_srvip" 		=> $infos["err_srvip"], 
                ":err_srvname" 		=> $infos["err_srvname"], 
                ":err_file" 		=> $infos["err_file"], 
                ":err_class" 		=> $infos["err_class"], 
                ":err_func" 		=> $infos["err_func"], 
                ":err_line" 		=> $infos["err_line"], 
                
                ":err_uri" 			=> $infos["err_uri"], 
                ":err_referer" 		=> $infos["err_referer"], 
                ":err_locip" 		=> $infos["err_locip"], 
                ":err_user_agent" 	=> $infos["err_user_agent"] ,
                ":err_trace" 		=> $infos["err_trace"], 
                
                ":tstamp" 			=> $infos["tstamp"] 
            );
			
            $id = $QO->execute_with_local_mode($params);
            
            //On vérifie si l'opération a abouti... 
            $eid = NULL;
            if (! $this->return_is_error_volatile($id) ) {
                //... On met à jour la table avec l'identifiant externe 'eid'
                $eid = $this->eid_encode($now,$id);
                
                $QO = new QUERY("qryl4errlgn2");
                $params = array( ':id' => $id, ':eid' => $eid );
                $QO->execute_with_local_mode($params);
            }
			
//            var_dump(strtolower($_SESSION["sto_infos"]->getProd_xmlscope()["err_rpt_table"]["err_rpt_enable"]));
            if ( $this->WSOE_ShouldEmail() ) {
//                var_dump(strtolower($_SESSION["sto_infos"]->getProd_xmlscope()["err_rpt_table"]["err_rpt_enable"]));
                
                //Lidentifiant du modèle de l'email
                $emldid = "emdl_errlgn1";

                //Récupération de l'adresse email du destinataire des rapports d'erreur.
                $recipient = $this->WSOE_GetRecipientEmail();
				
//                var_dump($recipient);
                if ( !$recipient | $this->return_is_error_volatile($recipient) ) {
                    /*
                     * On tente de renvoyer un message à "trenqr@support.com".
                     * (TODO) Si ce procédé echoue on tente d'enregistrer dans un fichier.
                     * Ces tous les procédés précédents échoues, bah ...
                     */
                    error_log("__ERR_VOL_WSOE_FAILED_NO_EML",1,"support@trenqr.com");
                    return;
                }

                //On tente l'envoi du mail 
                $r = $this->WSOE_SendEmail($emldid, $infos, $eid, $recipient);
//                var_dump($r);

            }
            
            return TRUE;
        }
        
    }
    
    
    private function handleUserWarningErrorCase()
    {
        $db_mode = "";
        
        if( defined('RIGHT_IS_DEBUG') )
            $db_mode = RIGHT_IS_DEBUG;
        else
            $db_mode = IS_DEBUG; 
            
        switch($db_mode)
        {
            case TRUE :
                    $newErrMsg = $this->formatErrmgForDebugMode();
                    echo $newErrMsg;
                    exit;
                break;
            case FALSE :
                    $ErrMessage = $this->errConcerned['user_errmsg'];
                    require_once WOS_SPAGE_FORUSER_ERR_DISPLAYER_PAGE;
                    exit;
                break;
        }
    }
    
    private function handleUserNoticeErrorCase()
    {
        $db_mode = "";
        
        if( defined('RIGHT_IS_DEBUG') )
            $db_mode = RIGHT_IS_DEBUG;
        else
            $db_mode = IS_DEBUG; 
            
        switch($db_mode)
        {
            case TRUE :
                    $newErrMsg = $this->formatErrmgForDebugMode();
                    echo $newErrMsg;
                    exit;
                break;
            case FALSE :
                    $ForSpageMessage = $this->errConcerned['user_errmsg'];
                   echo ("<script type=\"text/javascript\">
                        alert('ERROR : $ForSpageMessage');
                        </script>");
                   exit;
                break;
        }
    }    
    
    private function formatErrmgForDebugMode()
    {
        $errno = $this->errConcerned['errno'];
        $errfile = $this->report['errfile'];
        $errclass = $this->report['errclass'];
        $errfunc = $this->report['errfunc'];
        $errline = $this->report['errline']; 
        $errmsg = ( isset($this->is_no_errid_case) and $this->is_no_errid_case ) ? $this->spnoerrid_msg : $this->errConcerned['sys_errmsg'];
//        $this->WarmTechSupportOnError($errmsg);
        $newMsg = "
        <h2>THIS IS THE ERROR REPPORT :</h2>
        <ul>
            <li>ERRRNO : <span style=\"color:red;\">$errno</span> </li>
            <li>FILE NAME : $errfile</li>
            <li>CLASS : $errclass</li>
            <li>FUNCTION : $errfunc()</li>
            <li>LINE : $errline</li>
            <li>ERR_MSG :  <span style=\"color:red;\">$errmsg</span></li>
        </ul>
        ";
        
        return $newMsg;
    }
    
    private function return_is_error_volatile ($arg) {
        if ( !$arg ) {
            if ( ( defined('RIGHT_IS_DEBUG') && RIGHT_IS_DEBUG === TRUE ) | ( defined('IS_DEBUG') && IS_DEBUG === TRUE ) ) {
                debug_print_backtrace();
                $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals it has never received arg. Then, a standard is trigger.</p>";
                echo $msg;
            } else {
                echo "FINAL_ERR_ON_QUEUE";
            }
            
            /* //[NOTE 06-09-15] @author BOR
            $msg = "<p>FUNCTION : ".__FUNCTION__."() in CLASS : ".__CLASS__." signals it has never received arg. Then, a standard is trigger.</p>";
            trigger_error ($msg,E_USER_ERROR);
            exit;
            //*/
         }
        
        if ( !$arg | !is_string($arg) ) {
            return FALSE;
        } else {
            //Si la chaine commence par "__ERR_VOL_" avec une tolérance d'un espace devant pour les étourdis
            if ( preg_match("/^\s?__ERR_VOL_.+/", $arg) ) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    
    private function eid_encode($upload_tstamp_ms, $id){
        //On doit convertir les ms en s pour avoir des dates 'normales' au décode
        $sec_tstamp = floor(intval($upload_tstamp_ms)/1000);
        $b23tstamp = base_convert(intval($sec_tstamp), 10, 23);
        $b23picid = base_convert(intval($id), 10, 23);
        $ieid = $b23tstamp . 'o' . $b23picid;
        
        return $ieid;
    }
    
    private function WSOE_ShouldEmail() {
        /*
         * Définit si, selon la configuration du poduit, on devrait avertir l'équipe technique du problème
         */
        
        return ( isset($_SESSION) && session_id() && key_exists("sto_infos", $_SESSION) && $_SESSION["sto_infos"] && strtolower($_SESSION["sto_infos"]->getProd_xmlscope()["err_rpt_table"]["err_rpt_enable"]) === "yes" ) ? TRUE : FALSE;
    }
    
    private function WSOE_GetRecipientEmail() {
        /*
         * l'adresse email se trouve dans le tableau de SESSION.
         * En temps normal, la vériable de SESSION est "intit". Cependant, aucune certitude ne permet de deire qu'elle l'est.
         * Il faut donc s'en assurer. Dans le cas où elle n'est pas "init", on renvoie FALSE.
         */
        
        return ( isset($_SESSION) && session_id() && key_exists("sto_infos", $_SESSION) && $_SESSION["sto_infos"] ) ? htmlspecialchars_decode($_SESSION["sto_infos"]->getProd_xmlscope()["prod_email_table"]["email_serr_receiver"]) : FALSE;
    }
    
    private function WSOE_SendEmail ($emldid, $infos, $lgeid, $recipient) {
        /*
         * Permet d'envoyer le rapport sur l'erreur via MAIL.
         */
        
        //On récupère le modele de l'email et son scope
        $EMH = new EMAILAC_HANDLER();
        $emtab = $EMH->emac_acquire_emtab($emldid,"fr", "err_sys_noloop");
        
        if ( !$emtab | $this->return_is_error_volatile($emtab) ) {
            return "__ERR_VOL_NO_EMTAB";
        }
        
//        var_dump($infos);
        
        //Préparation des équivalents au marqueurs
        $args_eml_marks = [
            "error_code" 		=> $infos["err_code"],
            "error_message" 	=> $infos["err_msg"],
            "ssid" 				=> $infos["err_ssid"],
            "error_user_ueid" 	=> $infos["err_ueid"],
            "error_user_pseudo" => $infos["err_upsd"],
            "error_locip" 		=> $infos["err_locip"],
            "error_srvip" 		=> $infos["err_srvip"],
            "error_srvname" 	=> $infos["err_srvname"],
            "error_file" 		=> $infos["err_file"],
            "error_class" 		=> $infos["err_class"],
            "error_function" 	=> $infos["err_func"],
            "error_line" 		=> $infos["err_line"],
            "error_referer" 	=> $infos["err_referer"],
            "error_uri" 		=> $infos["err_uri"],
            "error_user_agent" 	=> $infos["err_user_agent"],
            "debug_print_trace" => $infos["err_trace"],
            "error_date_tstamp" => $infos["tstamp"],
            "error_datetime" 	=> date("d-m-Y G:i:s P e",($infos["tstamp"]/1000)),
            "error_log_eid" 	=> $lgeid
        ];
        /*
         * [NOTE 11-11-14] @author L.C.
         * Pour des raisons que j'ignore encore, la présence du caractère '@' empeche l'envoi de l'email.
         * En attendant de trouver une solution, je le remplace.
         */
        $args_eml_marks["debug_print_trace"] = preg_replace("/@/", "<span style='color: purple'>{AROBASE}</span>", $args_eml_marks["debug_print_trace"]);  
//        var_dump("YO",$args_eml_marks["debug_print_trace"]);
//        exit();
        
        //On s'assure qu'aucune valeur n'est nulle
        foreach ( $args_eml_marks as $k =>  $v ) {
            if ( !$v ) {
                //echo $k;//DEV, TEST, DEBUG
                $args_eml_marks[$k] = "NULL";
            }
        }
        
        $args_eml = [
            "exp" 	=> "Trenqr System <system@trenqr.com>",
            "rcpt" 	=> $recipient,
//            "rcpt" => "lou.carther@deuslynn-entreprise.com",
//            "rcpt" => "social@ondeuslynn.com",
//            "rcpt" => "dieudrichard@gmail.com",
            "object" => $emtab["emodel.object"],
            "catg" 	=> "PRODSYS_ERR"
        ];
		
        if ( $infos["err_uid"] ) {
            $args_eml["rcpt_uid"] = $infos["err_uid"];
        }
        
        $next = $EMH->emac_send_email_via_model($emtab["emodel.id"], "fr", $args_eml, $args_eml_marks);
//        var_dump($next);
        if ( !$next | $this->return_is_error_volatile($next) ) {
            return FALSE;
        }
        
        return TRUE;
        
    }
}
?>
