<?php

/**
 * <p>Caution : The first rule of this class is to let the "Caller".</p>
 * <p>Use this class to handle a database and common processes.</p> 
 * @author DIEUD-L Richard <projet.greentius@gmail.com>
 * @copyright (c) 2013, DEUSLYNN ENTREPRISE
 */
class WOS_DATABASE extends MOTHER
{
    private $database;
    private $host;
    private $dbname;
    private $charset; //[NOTE au 22/10/13] : Ajouté + modification args constructeur
    private $user;
    private $pass;
    
    private $bdd;
    
    function __construct($entry_dbname) {
         //We ensure that until the mother this class is innit.  
        parent::__construct( __FILE__, __CLASS__);
        
        if ( isset($entry_dbname) and is_string($entry_dbname) and $entry_dbname != "" ) {
            $this->dbname = $entry_dbname;
        } else {
            $this->signalError ("err_sys_l03", __FUNCTION__, __LINE__);
        }
		
        $this->prepare_database($this->dbname);
        
    }
    
    
    public function tryConnection(){
        try
        {
            $cnx_string = "$this->database:host=$this->host;dbname=$this->dbname;charset=$this->charset";
            //[NOTE 25-08-14] Ajout� pour mieux g�rer les erreurs PDO
            $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
            $this->bdd = new PDO($cnx_string, $this->user, $this->pass, $options);
        }
        catch(PDOException $ex)
        {
            $this->presentVarIfDebug(__FUNCTION__,__LINE__,[$this->database,$this->host,$this->charset,$this->user,$this->dbname,$this->pass]);
            error_log($ex->getMessage());
            //$this->displayMsgIfDebugMode($ex->getMessage());
            $this->signalError("err_sys_l02", __FUNCTION__, __LINE__,TRUE);
            exit();
        }
    }
    
    private function prepare_database ($dbname) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $dbname);
        
        switch ($dbname) {
//            case "tqr_account_vb1_prod" : //[DEPUIS 13-09-15] @author BOR
            /*
             * [DEPUIS 07-06-16] @author BOR
             */
            case "tqr_account_vb3_dev" : 
            case "tqr_account_vb3_prod" :
                    $this->database = "mysql";
                    $this->host = "localhost";
                    $this->charset = 'utf8';
                    $this->user = "dsly_dbmgr_2";
                    $this->pass = "TqrVille.69";
                break;
//            case "tqr_product_vb1_prod" : //[DEPUIS 13-09-15] @author BOR
            /*
             * [DEPUIS 07-06-16] @author BOR
             */
            case "tqr_product_vb3_dev" :
            case "tqr_product_vb3_prod" :
                    $this->database = "mysql";
                    $this->host = "localhost";
                    $this->charset = 'utf8';
                    $this->user = "dsly_dbmgr_1";
                    $this->pass = "Geek.iT-69";
                break;
            default:
                    if (! $avoid_bleeding ) {
                        $this->presentVarIfDebug(__FUNCTION__,__LINE__,[$this->dbname]);
                        $this->signalError("err_sys_l028", __FUNCTION__, __LINE__,TRUE);
                        exit();
                    } else {
                        return "__ERR_UKNW_DB";
                    }
                   
                break;
        }
        
    }
    
    // euh ...
    public function closeCurrentConnection(PDOStatement $pdo_statment)  { $pdo_statment->closeCursor(); }

    /**
     * <p>Use this function to execute a sql query. But don't use it for a SELECT query. In fact, this function just return affected rows.</p>
     * @see executeSimpleQueryWithResult() To have a return using SELECT.
     * @see executePrepareQueryWithResult() For a Prepare querry which return PDOStatement with result for a SELECT query.
     * @param type $query
     * @return type count : Rows affected numbers.
     */
    public function executeSimpleQueryWithRowAffected($query, $avoid_bleeding = FALSE) { 
        /*
         * [DEPUIS 20-08-15] @author BOR
         *  On est obligé de changer la case car on ne peut pas utiliser "lower_case..." sans faire boguer Plesk12.
         *  Dès le moment où on pourra se passer d'une Interfance de gestion, on pourra se débarasser de cette contrainte.
         */
        $query = strtolower($query);
        if ( isset($query) and is_string($query) and $query != "" ) {
            return $count = $this->bdd->exec($query); 
        } else {
            if (! $avoid_bleeding ) {
                $this->signalError("err_sys_l00", __FUNCTION__, __LINE__);
            } else {
                return "__ERR_VOL_DATAS_MSG";
            }
        }
        
    }
    
    /**
     * [NOTE 15-08-14] Construction de la méthode
     * <p>Joue le même rôle que : executeSimpleQueryWithRowAffected().</p>
     * <p>
     * La seule différence est que la valeur de retour est l'identifiant de la dernière ligne inserée.
     * Cette méthode est donc naturellement reservée aux requtes de type 'set'
     * </p>
     * 
     * @see executeSimpleQueryWithResult() To have a return using SELECT.
     * @see executePrepareQueryWithResult() For a Prepare querry which return PDOStatement with result for a SELECT query.
     * @param type $query
     * @return type count : Rows affected numbers.
     */
    public function executeSimpleQueryWithoutResult($query, $return_last_insert_id = FALSE, $avoid_bleeding = FALSE) { 
        /*
         * [DEPUIS 20-08-15] @author BOR
         *  On est obligé de changer la case car on ne peut pas utiliser "lower_case..." sans faire boguer Plesk12.
         *  Dès le moment où on pourra se passer d'une Interfance de gestion, on pourra se débarasser de cette contrainte.
         */
        $query = strtolower($query);
        if( isset($query) and is_string($query) and $query != "" ) {
            $this->bdd->exec($query); 
        } else {
            if (! $avoid_bleeding )
                $this->signalError("err_sys_l00", __FUNCTION__, __LINE__);
            else
                return "__ERR_VOL_DATAS_MSG";
        }
        
        $r = NULL;
        if ( $return_last_insert_id ) {
            $r = $this->bdd->lastInsertId();
            return $r;
        }
    }
    
    
    /**
     * <p>Use this function to execute a sql query. Prefer it for SELECT queries.</p>
     * @param type $query
     * @return type PDOStatement containing rows if SELECT query.
     */
    public function executeSimpleQueryWithResult($query, $avoid_bleeding = FALSE) { 
        /*
         * [DEPUIS 20-08-15] @author BOR
         *  On est obligé de changer la case car on ne peut pas utiliser "lower_case..." sans faire boguer Plesk12.
         *  Dès le moment où on pourra se passer d'une Interfance de gestion, on pourra se débarasser de cette contrainte.
         */
        $query = strtolower($query);
        if( isset($query) and is_string($query) and $query != "" ) {
            return $reponse = $this->bdd->query($query);  
        } else {
            if (! $avoid_bleeding )
                $this->signalError("err_sys_l00", __FUNCTION__, __LINE__);
            else
                return "__ERR_VOL_DATAS_MSG";
        }
        
    }
    
    /**
     * 
     * @param type $query
     * @param type $params
     * @param type $return_last_insert_id
     * @return boolean s'il ne s'agit pas du cas return_last_insert_id
     * @return string last_insert_id s'il s'agit du cas return_last_insert_id
     */
    public function executePrepareQueryWithoutResult($query, $params, $return_last_insert_id = FALSE, $avoid_bleeding = FALSE) {
        /*
         * [DEPUIS 20-08-15] @author BOR
         *  On est obligé de changer la case car on ne peut pas utiliser "lower_case..." sans faire boguer Plesk12.
         *  Dès le moment où on pourra se passer d'une Interfance de gestion, on pourra se débarasser de cette contrainte.
         */
        $query = strtolower($query);
        if ( ( isset($query) and is_string($query) and $query != "" )
                and ( isset($params) and is_array($params) and count($params) > 0 ) ) {
            $req = NULL;
            try {

                    $this->bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

                    $req = $this->bdd->prepare($query);
                    if (! $req ) {
                        if (! $avoid_bleeding ) {
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $query);
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $req);
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $this->bdd->errorInfo());
                            $this->signalError("err_sys_l323", __FUNCTION__, __LINE__, TRUE);
                        } else {
                            return "__ERR_VOL_PDOStmnt";
                        }
                    }
					
					
                    //Evite d'output debug_print_backtrace() de manière inopportune
                    /*
                     * [DEPUIS 21-05-16]
                     *      Permet d'échapper à un BOGUE qui me depasse.
                     *      Cela est clairement du à l'utilisation de ob_start() ce qui cause une MEMORY LEAK
                     */
                    if (! ( defined("IS_LTC") && IS_LTC === TRUE ) ) {
                        ob_start();
                        debug_print_backtrace();
                        $trace = ob_get_clean();
                    }
                    
                    $req->execute($params)
                    or die($this->signalErrorWithoutErrIdButGivenMsg(print_r([$trace, "<br/><br/><span style='color: red; font-weight: bold;'><-- SEPARATOR --></span><br/><br/>", $query, $req->errorInfo()],TRUE), __FUNCTION__, __LINE__, TRUE, $avoid_bleeding)); //hum will see what to do for production mode. (RESOLVED [20-09-13]) 
					
                    //[NOTE 15-08-14] Pouvoir renvoyer le last_insert_id
                    $r = NULL;
                    if ( $return_last_insert_id ) {
                        $r = $this->bdd->lastInsertId();
                        return $r;
                    }
            
            } catch (Exception $ex) {
                 debug_print_backtrace(); 
                /* var_dump("CHECKPOINT => ",__FUNCTION__,__LINE__,[$this->bdd->errorInfo(),$ex->getMessage(),$ex->getCode()]); */
                error_log($ex->getMessage());
                
                if (! $avoid_bleeding ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, $query);
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, $req);
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, $this->bdd->errorInfo());
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, $ex->getMessage());
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, $ex->getCode());
                    $this->signalError("err_sys_l323", __FUNCTION__, __LINE__, TRUE);
                } else {
                    return "__ERR_VOL_PDOStmnt";
                }
            }

            return TRUE;
            
        } else {
			if (! $avoid_bleeding ) {
                $this->signalError("err_sys_l00", __FUNCTION__, __LINE__);
            } else {
                return "__ERR_VOL_DATAS_MSG";
            }
			/* $this->signalError("err_sys_l00", __FUNCTION__, __LINE__); */
		}
        
    }
        
    
    public function executePrepareQueryWithResult($query, $params, $avoid_bleeding = FALSE) {
         /*
         * [DEPUIS 20-08-15] @author BOR
         *  On est obligé de changer la case car on ne peut pas utiliser "lower_case..." sans faire boguer Plesk12.
         *  Dès le moment où on pourra se passer d'une Interfance de gestion, on pourra se débarasser de cette contrainte.
         */
        $query = strtolower($query);
        if( (isset($query) and is_string($query) and $query != "")
                and (isset($params) and is_array($params) and count($params)>0) ){
            
            $this->bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
            $req = $this->bdd->prepare($query);
            if ( isset($req) and ($req instanceof PDOStatement) ) {
                $req->execute($params);
            } else {
                if (! $avoid_bleeding ) {
                    $this->presentVarIfDebug(__FUNCTION__,__LINE__,$query);
                    $this->presentVarIfDebug(__FUNCTION__,__LINE__,$req);
                    $this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->bdd->errorInfo());

                    $this->signalError("err_sys_l323", __FUNCTION__, __LINE__,true);
                } else {
                    return "__ERR_VOL_PDOStmnt";
                }
                
            }
            return $req;
         } else {
             if (! $avoid_bleeding )
                $this->signalError("err_sys_l00", __FUNCTION__, __LINE__);
            else
                return "__ERR_VOL_DATAS_MSG";
         }
    }
    
    /************************************************ PROCEDURES ******************************************************/
    //RAPPEL : Ne gère pas le cas de PARAM OUT
    public function executePrepareProcedureWithoutResult($query, $params) {
        /*
         * [DEPUIS 20-08-15] @author BOR
         *  On est obligé de changer la case car on ne peut pas utiliser "lower_case..." sans faire boguer Plesk12.
         *  Dès le moment où on pourra se passer d'une Interface de gestion, on pourra se débarasser de cette contrainte.
         */
        $query = strtolower($query);
		
        if ( ( isset($query) && is_string($query) && $query !== "" && $query !== "''" )
                && (isset($params) && is_array($params) && count($params) > 0) ) {
					
            $this->bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
            
            $req = $this->bdd->prepare($query);
            if ( isset($req) and ($req instanceof PDOStatement) ) {
                foreach ($params as $k => &$v) { //& car bindParam a besoin de la référence (php.net)
                    $type = $this->acquiere_PDO_PARAM_TYPE($v);
                    if (! $type ) {
                        $this->signalError("err_sys_l338", __FUNCTION__, __LINE__,true);
                    }
                    $req->bindParam($k, $v, $type);
                }
                 
                //Evite d'output debug_print_backtrace() de manière inopportune
                /*
                 * [DEPUIS 21-05-16]
                 *      Permet d'échapper à un BOGUE qui me depasse.
                 *      Cela est clairement du à l'utilisation de ob_start() ce qui cause une MEMORY LEAK
                 */
                if (! ( defined("IS_LTC") && IS_LTC === TRUE ) ) {
                    ob_start();
                    debug_print_backtrace();
                    $trace = ob_get_clean();
                }
                $req->execute()//;
                or die($this->signalErrorWithoutErrIdButGivenMsg(print_r([$trace, "<br/><br/><span style='color: red; font-weight: bold;'><-- SEPARATOR --></span><br/><br/>", $query, $req->errorInfo()],TRUE), __FUNCTION__, __LINE__, TRUE, TRUE));
                
//                $req->execute();
            } else {
		
                $this->presentVarIfDebug(__FUNCTION__,__LINE__,$query);
                $this->presentVarIfDebug(__FUNCTION__,__LINE__,$req);
                $this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->bdd->errorInfo());
                
                $this->signalError("err_sys_l323", __FUNCTION__, __LINE__,true);
            }
        
         } else {
			 $this->signalError("err_sys_l00", __FUNCTION__, __LINE__);
		 }
    }
    
    
    public function executePrepareProcedureWithResult($query, $params) {
        /*
         * [DEPUIS 20-08-15] @author BOR
         *  On est obligé de changer la case car on ne peut pas utiliser "lower_case..." sans faire boguer Plesk12.
         *  Dès le moment où on pourra se passer d'une Interfance de gestion, on pourra se débarasser de cette contrainte.
         */
        $query = strtolower($query);
        
        /*
         * Gère le cas des procédures stockées qui renvoie des valeurs.
         * CALLER peut fournir des paramètres de types OutPut (op_) ou InPut/OutPut (ipop)
         */
       if ( ( isset($query) && is_string($query) && $query !== "" && $query !== "''" )
                && (isset($params) && is_array($params) && count($params) > 0) ) {
            
            $this->bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
            
            $req = $this->bdd->prepare($query);
            if ( isset($req) and ($req instanceof PDOStatement) ) {
                $r = [];
                //Les paramètres qui sont de type OutPut suivent la nomenclature "op_[NAME]"
                $op_cn = 0;
                //Les paramètres qui sont de type InPut/OutPut suivent la nomenclature "ipop_[NAME]"
                $ipop_cn = 0;
                
                foreach ($params as $k => &$v) { //& car bindParam a besoin de la référence (php.net)
                    if ( preg_match("/^:op_.+/i", $k) ) 
                    {
                        ++$op_cn;
                        $type = $this->acquiere_PDO_PARAM_TYPE($v);
                        $r[] = $v;
                    }
                    else if ( preg_match("/^:ipop_.+/i", $k) ) 
                    {
                        ++$ipop_cn;
                        $type = $this->acquiere_PDO_PARAM_TYPE($v,TRUE);
                        $r[] = $v;
                    } 
                    
                    if (! $type ) {
                        $this->signalError("err_sys_l338", __FUNCTION__, __LINE__,true);
                    }
                    if ( !$op_cn | !$ipop_cn )  {
                        $this->signalError("err_sys_l339", __FUNCTION__, __LINE__,true);
                    }
                    
                    $req->bindParam($k, $v, $type);
                }
                 
                //Evite d'output debug_print_backtrace() de manière inopportune
                /*
                ob_start();
                debug_print_backtrace();
                $trace = ob_get_clean();
                //*/
                /*
                 * [DEPUIS 21-05-16]
                 *      Permet d'échapper à un BOGUE qui me depasse.
                 *      Cela est clairement du à l'utilisation de ob_start() ce qui cause une MEMORY LEAK
                 */
                if (! ( defined("IS_LTC") && IS_LTC === TRUE ) ) {
                    ob_start();
                    debug_print_backtrace();
                    $trace = ob_get_clean();
                }
                $req->execute()//;
                        or die($this->signalErrorWithoutErrIdButGivenMsg(print_r([$trace, "<br/><br/><span style='color: red; font-weight: bold;'><-- SEPARATOR --></span><br/><br/>", $query, $req->errorInfo()],TRUE), __FUNCTION__, __LINE__, TRUE, TRUE)); 
                
                return $r;
            } else {
                $this->presentVarIfDebug(__FUNCTION__,__LINE__,$query);
                $this->presentVarIfDebug(__FUNCTION__,__LINE__,$req);
                $this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->bdd->errorInfo());
                
                $this->signalError("err_sys_l323", __FUNCTION__, __LINE__,true);
            }
        
         } else $this->signalError("err_sys_l00", __FUNCTION__, __LINE__);
    }
    
    
    private function acquiere_PDO_PARAM_TYPE ($a, $with_out = FALSE) {
        //Permet d'obtenir la constante liée au type de la variable passée en paramètre
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $a);
        
        if ( is_string($a) ) {
            return ( $with_out ) ? PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT : PDO::PARAM_STR;
        } else if ( is_bool($a) ) {
            return ( $with_out ) ? PDO::PARAM_BOOL|PDO::PARAM_INPUT_OUTPUT : PDO::PARAM_BOOL;
        } else if ( is_integer($a) ) {
            return ( $with_out ) ? PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT : PDO::PARAM_INT;
        } else return;
    }
    
    /******************************************************************************************************/
    /******************************************************************************** GETTERS AND SETTERS */
    // <editor-fold defaultstate="collapsed" desc="GETTERS and SETTERS">
    public function getDatabase() {
        return $this->database;
    }

    public function setDatabase($database) {
        $this->database = $database;
    }

    public function getHost() {
        return $this->host;
    }

    public function setHost($host) {
        $this->host = $host;
    }

    public function getDbname() {
        return $this->dbname;
    }

    public function setDbname($dbname) {
        $this->dbname = $dbname;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getPass() {
        return $this->pass;
    }

    public function setPass($pass) {
        $this->pass = $pass;
    }
    
    public function getBdd() {
        return $this->bdd;
    }


// </editor-fold>



}
?>
