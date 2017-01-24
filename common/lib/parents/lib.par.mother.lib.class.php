<?php

class MOTHER {
    private $file;
    private $class;
    private $MyErr_H;
    
    function __construct($entry_file, $entry_class) {
        $this->file = $entry_file;
        $this->class = $entry_class;
    }
    
    /********************************* PROCESSING *********************************/
    
    /**
     * Permet de couper une execution et ne tester qu'une partie du code se trouvqnt avant la déclaration de la fonction.
     * Cela permet lors des refactoring d'éviter de traiter un trop gros flux d'informations suite à un changement dans le code source.
     */
     protected function endExecutionIfDebug ($entry_func, $entry_line, $debug_trace = false, $last_word = NULL) {
        if ( ( ((@defined("RIGHT_IS_DEBUG") && @RIGHT_IS_DEBUG == "true") and @defined("CONF_FILE_IS_TREATED")) 
                and (@PTF_RM == "T_RM_TRM" || @PTF_RM == "T_RM_DBRM" || IS_DEBUG == TRUE) ) 
                        OR ( @defined("SBDK") && @SBDK == "true" ) ){
            if ( (defined("RIGHT_IS_DEBUG") && RIGHT_IS_DEBUG == "true") and defined("CONF_FILE_IS_TREATED") && $debug_trace) debug_print_backtrace();
            echo "<h3 style=\"color:red;\">TAMPON !</h3>";
            echo "<p>CLASS : $this->class</p>";
            echo "<p>FUNCTION : $entry_func()</p>";
            echo "<p>LINE : $entry_line</p>";
            
            $last_word = ( !is_array($last_word) && !empty($last_word) ) ? $last_word : "";
            
            exit;
        }
    }
 

    /**
     * Allows to signal an error. The display depends on the running mode.
     * In fact, if we're into a debug mode, the application will display the system error or the user error
     * in the contrary.
     * @param type $errno
     * @param type $errfunc
     * @param type $errline
     */
    protected function signalError($errno,$errfunc, $errline, $debug_trace = false)
    {
		
        if ( isset($errline) and $errline != "" ) // We don't check here if errno exist because we want it to be checked in Err_Handmler
        {
			
            //CONF_FILE_IS_TREATED allows to be sure the conf file has been treated.
            //CONF_FILE defines how to handle errors and debug procedures. See Index_Fact for more
            if ( (defined("RIGHT_IS_DEBUG") && RIGHT_IS_DEBUG == "true") and defined("CONF_FILE_IS_TREATED") && $debug_trace == TRUE ) 
            {
				
                /*
                 * [DEPUIS 14-06-15] BOR
                 */
                ob_start(); 
                debug_print_backtrace();
                $trace = ob_get_contents(); 
                ob_end_clean(); 
                
                $trace = preg_replace ("/(#\d+\s+)/", "<br/><span style='color:#FFB005; font-size: 20px; font-weight:bold;'>$1 => </span>", $trace);  
                echo $trace;
				
            }
            
            $this->MyErr_H = new Err_Handler();
            $entry_array = $this->formatErrIntoArray($this->file, $this->class, $errfunc, $errline, $errno);
			/*
			var_dump(__LINE__,method_exists($this->MyErr_H,"myErrorHandler"));
			var_dump(__LINE__,$entry_array);
			//*/
            $this->MyErr_H->myErrorHandler($entry_array);
			
        }
    } 
      
    protected function signalErrorWithoutErrIdButGivenMsg($msg,$errfunc, $errline, $debug_trace = FALSE, $avoid_bleeding = FALSE) {
        if ( (isset($msg) and $msg != "") and (isset($errfunc) and $errfunc != "") and (isset($errline) and $errline != "") ) // We don't check here if errno exist because we want it to be checked in Err_Handmler
        {
            //CONF_FILE_IS_TREATED allows to be sure the conf file has been treated.
            //CONF_FILE defines how to handle errors and debug procedures. See Index_Fact for more
			if ( (defined("RIGHT_IS_DEBUG") && RIGHT_IS_DEBUG == "true") and defined("CONF_FILE_IS_TREATED") && $debug_trace == TRUE ) 
			{
                /*
                 * [DEPUIS 14-06-15] BOR
                 */
                ob_start(); 
                debug_print_backtrace();
                $trace = ob_get_contents(); 
                ob_end_clean(); 
                
                $trace = preg_replace ("/(#\d+\s+)/", "<span style='color:#FFB005; font-size: 20px; font-weight:bold;'>$1 => </span>", $trace); 
                echo $trace;
            }
            
            $this->MyErr_H = new Err_Handler();
            
            $entry_array = $this->formatErrIntoArray($this->file, $this->class, $errfunc, $errline, "err_sys_spnoerrid");
            $this->MyErr_H->myErrorHandlerWhenNoErrGivenButMsg($entry_array, $msg, $avoid_bleeding);
        }
    }
    
    
    protected function mother_get_error_msg ($errno, $errfunc, $errline) {
        $this->MyErr_H = new Err_Handler();

        if ( (defined("RIGHT_IS_DEBUG") && RIGHT_IS_DEBUG == "true") and defined("CONF_FILE_IS_TREATED") ) {
            //On retourne le message d'erreur system
            return $this->MyErr_H->get_error_msg($errno, $errfunc, $errline);
        } else 
            //On retourne le message d'erreur utilisateur
            return $this->MyErr_H->get_error_msg($errno, $errfunc, $errline, TRUE);
    } 
    
    
    /**
     * Presents (display) a var what ever its type. Specifying the style will
     * allow to switch between 'var_dump' and 'print_r'. 
     * It's very useful when you want to debug a var. 
     * @param type $var
     * @param type $style <p><i>v_r</i> if you want to use var_dump()</p><p><i>p_r</i> is the defaukt value. Use if you want the funtion uses print_r().</p>  
     */
    protected function presentVarIfDebug($ent_func,$ent_line,$ent_var,$ent_style='p_r')
    {
        //display only if we are in RM = DEBUG
        if ( @PTF_RM == "T_RM_TRM" || @PTF_RM == "T_RM_DBRM" || IS_DEBUG == TRUE) {
            echo "<p>CLASS : $this->class</p>";
            echo "<p>FUNCTION : $ent_func()</p>";
            echo "<p>LINE : $ent_line</p>";
            //$new_var = ( isset($ent_var) and $ent_var != "" ) ? $ent_var:"NO BODY HAS BEEN DEFINED";
            $new_var = $ent_var;
            if($ent_style=='p_r'){
                echo '<pre>';
                print_r($new_var);
                echo '</pre>';
            }
            else if($ent_style=='v_d'){
                echo '<pre>';
                var_dump($new_var);
                echo '</pre>';
            }
            echo "<br/>";
        }
    }
    /* NO : Remplie exactement les mêmes tâches que presentVarIfDebug() [19-09-13]
    protected function echoVarIfDebug($entry_func,$entry_line,$entry_var) {
        //display only if we are in RM = DEBUG
        if ( @PTF_RM == "T_RM_TRM" || @PTF_RM == "T_RM_DBRM" || IS_DEBUG == TRUE) {
            echo "<p>CLASS : $this->class</p>";
            echo "<p>FUNCTION : $entry_func()</p>";
            echo "<p>LINE : $entry_line</p>";
            echo '<pre>';
            print_r($new_var);
            echo '</pre>';
            echo "<br/>";
        }
    }
    //*/
    
    private function formatErrIntoArray($errfile, $errclass, $errfunc,$errline, $errno)
    {
        return $entry_array = [
            "errfile" => $errfile,
            "errclass" => $errclass,
            "errfunc" => $errfunc,
            "errline" => $errline,
            "errno" => $errno,
        ];
    }
    
    
     protected function check_isset_entry_vars ($errfunc, $errline, $entry, $soft_array = FALSE) {
        //[25/15/13] : Je ne sais plus pk on ne verifie pas si empty. Dans le doute je considère que c'est pour avoir plus de souplesse.
        /*
         * soft_array : Précise que si quelques child d'un tableau sont nuls ce n'est pas grave
         */
        //Aucun changement pour l'heure
        if ( isset($errfunc) and isset($errline) ) {
            if ( isset($entry) and (count ($entry) > 0) ) { 
                if ( !is_array($entry) or is_object($entry) ) { 
                    if ( !isset($entry) ) {
                        if ( RIGHT_IS_DEBUG == TRUE ) debug_print_backtrace();
                        $this->presentVarIfDebug($errfunc,$errline,$entry);
                        $this->signalError ("err_sys_l00", $errfunc, $errline);
                    } 
                } else {
                    foreach ($entry as $var) {
                        if ( !isset($var) && !$soft_array ) {
                            if ( RIGHT_IS_DEBUG == TRUE ) debug_print_backtrace();
                            $this->presentVarIfDebug($errfunc,$errline,$var);
                            $this->signalError ("err_sys_l00", $errfunc, $errline);
                        } 
                    }
                }
            } else {
                if ( RIGHT_IS_DEBUG == TRUE ) debug_print_backtrace();
                $this->presentVarIfDebug($errfunc,$errline,$entry);
                $this->signalError ("err_sys_l00", $errfunc, $errline);
            }
        } else {
            if ( @defined (RIGHT_IS_DEBUG) and RIGHT_IS_DEBUG == TRUE ) debug_print_backtrace();
            $this->presentVarIfDebug($errfunc,$errline,$entry);
            $this->signalError ("err_sys_l00", $errfunc, $errline);
        }
    }
    
    /**
     * Permet de verifier si la valeur passée en paramètre est vide.
     * Attention, du fait de l'utilisation de 
     * 
     * @method 
     * @param type $errfunc
     * @param type $errline
     * @param type $entry
     */
    protected function check_isset_and_not_empty_entry_vars ( $errfunc, $errline, $entry ) {
        /*
         * [NOTE 25-11-2014] @author L.C.
         * J'ai modifié la méthode car ce qu'on considérait comme FAUX ne pouvait l'être.
         * En effet, FALSE ne devrait pas être considéré comme NON DEFINI et NON VIDE.
         */
        $iv = TRUE;
        if ( isset($errfunc) and isset($errline) ) {
            if ( is_array($entry ) ) {
                foreach ( $entry as $k => $v ) {
                    if (! ( isset($v) && $v !== "" ) ) {
                        $this->presentVarIfDebug($errfunc,$errline,[$k,$v]);
                        $iv = FALSE;
                        break;
                    }
                }
            } else if (! ( isset($entry) && $entry !== "" ) ) {
                $iv = FALSE;
            }
            
            if (! $iv ) {
                $this->presentVarIfDebug($errfunc,$errline,$entry);
                if ( RIGHT_IS_DEBUG === TRUE || strtoupper(RIGHT_IS_DEBUG) === "TRUE" ) {
                    debug_print_backtrace();
                }
                
                $this->signalError ("err_sys_l00", $errfunc, $errline);
            }
            
        } else {
                if ( RIGHT_IS_DEBUG === TRUE || strtoupper(RIGHT_IS_DEBUG) === "TRUE" ) {
                    debug_print_backtrace();
                }
                
                $this->signalError ("err_sys_l00", $errfunc, $errline);
        }
        
        /*
        if ( isset($errfunc) and isset($errline) ) {
            /**
            * @see empty() considere FALSE comme vide. 
            * On verra donc s'il s'agit d'un bool et si c'est le cas on considere qu'il est non vide.
            *
            if ( empty($entry) or is_bool($entry) ) {
                $this->presentVarIfDebug($errfunc,$errline,$entry);
                if ( RIGHT_IS_DEBUG == TRUE ) 
                    debug_print_backtrace();
                $this->signalError ("err_sys_l00", $errfunc, $errline);
            }
        } else {
                if ( RIGHT_IS_DEBUG == TRUE ) debug_print_backtrace();
                $this->signalError ("err_sys_l00", $errfunc, $errline);
        }
        //*/
    }

    protected function get_or_signal_error ($code, $errno, $errfunc, $errline, $debug_trace = false) {
        $this->check_isset_and_not_empty_entry_vars($errfunc, $errline, func_get_args());
        
        switch ($code) {
            case 1:
                   return $this->mother_get_error_msg($errno, $errfunc, $errline);
                break;
            case 2:
                     $this->signalError($errno, $errfunc, $errline);
                break;
            default :
                break;
        }
    }


    /** [NOTE au 18/10/13] Transférer à QObject
     * <p>Cette méthode permet d'executer une QObject sont se soucier</p>
     * @param type $query_id
     * @param type $qparams_in
     * @param type $err_code
     * @return type
     */
    /**
    protected function execute_query_based_on_qobject($query_id, $qparams_in, $err_code) {
        if( ( isset($query_id) and $query_id != "") and ( is_array($qparams_in) and count($qparams_in) ) 
                and ( isset($err_code) and $$err_code != "" ) ) 
        {
           
            $Qobject = new QUERY($query_id);
            $qbody = $Qobject->getQbody();
            $qtype = $Qobject->getQtype();
            $qparams_in = $Qobject->getList_qparams_in();
            //$qparams_in = $_SESSION["qparams_in"];
            $qdbname = $Qobject->getQdbname();
            
            //init
            $it_is_a_prepared_query = FALSE;
            $it_is_a_return_datas_query = FALSE;

            $it_is_a_return_datas_query = ($qtype == "get") ? TRUE : FALSE;
            if( isset($qparams_in) and is_array($qparams_in) and count($qparams_in)>0 )  $it_is_a_prepared_query = TRUE;
        
            $bdd = new WOS_DATABASE($qdbname);
            $bdd->tryConnection();
            //**
             if ($it_is_a_return_datas_query) {
                if ($it_is_a_prepared_query) $return = $bdd->executePrepareQueryWithResult($qbody, $qparams_in);
                else $return = $bdd->executeSimpleQueryWithResult($qbody);
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$Qobject);

                //On prend PDO::FETCH_ASSOC plutot que la valeur par défaut car avoir le numéro de colonne ne nous interesse pas.
                //De plus, on espère ainsi réduire le Temps de Reponse.
                $datas = $return->fetch(PDO::FETCH_ASSOC);
                //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $datas);
                if ($datas) {
                    do {
                        foreach ($datas as $k => $v) {
                            $Results_Table[$k] = $v;
                        }
                    } while ( $datas = $return->fetch() );
                    return $Results_Table;
                    //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $Ids_Table);
                } else $this->signalError ($err_code, __FUNCTION__, __LINE__);
            } else {
                if ($it_is_a_prepared_query) $bdd->executePrepareQueryWithoutResult($qbody,$qparams_in);
                else $bdd->executeSimpleQueryWithRowAffected($qbody);
            }
            //*/
            /**    
            //[NOTE au 18/12/13] Anciennes instructions 
            $return = $bdd->executePrepareQueryWithResult($qbody, $params_in);
            $datas = $return->fetch();
            if ($datas) {
                do {
                    foreach ($datas as $k => $v) {
                        $Results_Table[$k] = $v;
                    }
                } while ( $datas = $return->fetch() );
                return $Results_Table;
                //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $Ids_Table);
            } else  $this->signalError ($err_code, __FUNCTION__, __LINE__);
            
        } else {
            if ( @defined (RIGHT_IS_DEBUG) and RIGHT_IS_DEBUG == TRUE ) debug_print_backtrace();
            $this->signalError ("err_sys_l00", __FUNCTION__, __LINE__);
        }
    }
    //*/
    
    /**
     * Vérifie si la variable donnée en paramètre est une chaine de type ERROR_VOLATILE.
     * Ce genre de chaine permettent de retourner les erreurs sans devoir Signaler une erreur.
     * Cette façon de faire est très intéressante pour les procedures de type AJAX
     *  
     * @param type $errfunc
     * @param type $errline
     * @param type $arg
     * @return boolean
     */
    protected function return_is_error_volatile ($errfunc, $errline, $arg) {
        $this->check_isset_and_not_empty_entry_vars($errfunc, $errline, [$errfunc,$errline]);
        
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
    
    /**
     * Permet d'obtenir une clé unique.
     * ATTENTION : Je n'ai pas créé cette méthode mais on l'a récupérée.
     * Aussi, il faudrait être prudent lors de son utilisation. 
     * En effet, il serait préférable de tester si la clé fournie n'existe pas déjà dans une table de clés.
     * 
     * CALLER pourrait aussi l'améliorer en y concatenant un CHAINE ce qui finira de garantir son unicité.
     * Cette CHAINE sera de préférence cryptée. On peut utiliser un scrpit md5, sha, etc ... 
     * 
     * @author Richard DIEUD <lou.carther@deuslynn-entreprise.com>
     * @return string
     */
    protected function guidv4()
    {
        $data = openssl_random_pseudo_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex ($data), 4));
    }
    
    //TESTÉ (10-11-14)
    protected function wos_send_email ( $errfunc, $errline, $args ) {
        $this->check_isset_and_not_empty_entry_vars($errfunc, $errline, func_get_args());
        
        //$sender, $recipient, $subject, $body, $reply_to = ""
        $EXPTD = ["sender","recipient","subject","body"];
        $com = array_intersect( array_keys($args), $EXPTD);
        if (! ( is_array($args) && count($com) === count($EXPTD) ) ) {
            return;
        }
        
        $headers ='From: '. $args["sender"] ."\n";
        if ( key_exists("reply_to", $args) && isset($args["reply_to"]) ) {
            $headers .='Reply-To: '. $args["reply_to"] ."\n";
        }
        $ctype = ( key_exists("ctype", $args) && isset($args["ctype"]) ) ? $args["ctype"] : "text/plain";
//        $headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
        $headers .= "MIME-Version: 1.0"."\n";
        $headers .= "Content-Type: $ctype; charset='utf8'"."\n";
        // $headers .= "Content-Transfer-Encoding: 8bit";
        $headers .= "Content-Transfer-Encoding: quoted-printable";
        // var_dump($headers);
        // $body = preg_replace("/>[\s]+</", "><", $args["body"]);
        // $body = chunk_split($args["body"],76,"=\n")
        $body = quoted_printable_encode($args["body"]);
        
        //Envoi du mail
        $r = mail($args["recipient"], $args["subject"], $body, $headers);
        return (! $r ) ? FALSE :  TRUE;
        
    }
    
    protected function wos_send_email_b64 ( $errfunc, $errline, $args ) {
        $this->check_isset_and_not_empty_entry_vars($errfunc, $errline, func_get_args());
        
        //$sender, $recipient, $subject, $body, $reply_to = ""
        $EXPTD = ["sender","recipient","subject","body"];
        $com = array_intersect( array_keys($args), $EXPTD);
        if (! ( is_array($args) && count($com) === count($EXPTD) ) ) {
            return;
        }
        
        $headers ='From: '. $args["sender"] ."\n";
        if ( key_exists("reply_to", $args) && isset($args["reply_to"]) ) {
            $headers .='Reply-To: '. $args["reply_to"] ."\n";
        }
        $ctype = ( key_exists("ctype", $args) && isset($args["ctype"]) ) ? $args["ctype"] : "text/plain";
//        $headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
        $headers .= "Content-Type: $ctype; charset='utf8'"."\n";
        $headers .= "Content-Transfer-Encoding: base64";
        
        $body = rtrim(chunk_split(base64_encode($args["body"])));
        
        //Envoi du mail
        $r = mail($args["recipient"], $args["subject"], $body, $headers); 
        
        if(! $r ){
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    /*
     * [NOTE 26-08-14] Ajouté par L.C.
     * 
     * Permet de centraliser la tache de retour d'AJAX.
     * 
     * (02-12-14)
     * On peut maintenant spécifier si l'on souhaite exit ou pas.
     */
    protected function Ajax_Return ( $key, $value, $thenExist = TRUE ) {
        if ( !isset($key) || $key === "" ) {
            return;
        }
        
        $key = (string) $key;
         
        echo json_encode([ $key => $value ]);
        
        if ( $thenExist ) {
            exit();
        } 
    }
    
    /**
     * Convertie une chaine en format ASCII.
     * ATTENTION : Cette version ne gère pas les chaines avec des caractères spéciaux !!
     * 
     * @param string $str La chaine à convertir.
     * @return strin
     */
    protected function convert_str_to_ascii ($str) {
        if ( !isset($str) || $str === "" ) {
            return;
        }
        
        $ascciver = NULL;
        for($i=0; $i < strlen($str); $i++)
        {
             $ascciver .= ord($str[$i]);
        }
        
        return $ascciver;
    }
    
    /**
     * Permet de savoir si une URL existe.
     * La méthode peut être par exemple utile pour déterminer si un fichier distant existe.
     * 
     * @param type $url
     * @return boolean
     */
    protected function wos_url_exists($url) {
        if (!$fp = curl_init($url)) return false;
        return true;
    }
    
    /**
    * HS: Fonction de création de l'IEID, extraite de la fonction de création du nom de l'image
    * @param int $upload_tstamp Timestamp d'upload de l'image (millisecondes)
    * @param int $id ID de l'élément
    */
    public function entity_ieid_encode($upload_tstamp_ms, $id){
        //On doit convertir les ms en s pour avoir des dates 'normales' au décode
        $sec_tstamp = floor(intval($upload_tstamp_ms)/1000);
        $b23tstamp = base_convert(intval($sec_tstamp), 10, 23);
        $b23picid = base_convert(intval($id), 10, 23);
        $ieid = $b23tstamp . 'o' . $b23picid;
        
        return $ieid;
    }
    

    public function isValidTimeStamp($timestamp, $milli = FALSE)
    {
        $timestamp = ( is_string($timestamp) ) ? $timestamp : (string) $timestamp;
        $MIN = ( $milli ) ? ~PHP_INT_MAX*1000 : ~PHP_INT_MAX;
        $MAX = ( $milli ) ? PHP_INT_MAX * 1000 : PHP_INT_MAX;
        return ((string) (float) $timestamp === $timestamp) 
            && ($timestamp <= $MAX)
            && ($timestamp >= $MIN);
    }
    

    /***********************************************************************************************/
    /************************************* GETTERS AND SETTERS *************************************/
    /***********************************************************************************************/
    
    /********************************** GETTERS ******************************/
    /*************************************************************************/
    protected function getFile() {
        return $this->file;
    }

    /********************************** SETTERS ******************************/
    /*************************************************************************/
    protected function setFile($file) {
        $this->file = $file;
    }

}