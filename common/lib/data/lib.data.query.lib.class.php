<?php
/**
 * 
 *
 * @author lou.carther.69
 */
class QUERY extends MOTHER {
    private $qid;
    private $qbody;
    private $qdbname;
    private $qtype;
    private $list_qparams_out;
    private $list_qparams_in;
    private $xmlscope;
    private $qdata_array;
    
    function __construct($qid = "") {
         parent::__construct(__FILE__,__CLASS__);
        if ( isset($qid) and $qid != "" ) {
            $this->qid = $qid;
            $this->inner_build();
        } //On ne déclenche pas d'erreur. Si l'id est demandé on execute sinon on lui laisse le choix de construire la requet manuellement
        
    }
    
    /**
     * <p>Permet de créer manuellement un QObject.<br/>
     * les données sont effacées à la fin de l'execution.</p>
     * <p>Cette manière de faire est utile pour tester une requete avant de l'inscrire en dur.<br/>
     * L'inscription en dur peut se faire via la méthode @see write_xml_version() </p>
     * 
     * @param type $qbody
     * @param type $dbname
     * @param type $qtype
     * @param type $qparams_in
     * @see write_xml_version()
     */
    public function build_volatile ($qbody, $qdbname, $qtype, $qparams_in) {
        $this->qbody = $qbody;
        $this->qdbname = $qdbname;
        $this->qtype = $qtype;
        $this->list_qparams_in = $qparams_in;
    }
    
    /**
     *  <p>Permet d'écrire une requete sous format XML</p>
     * <p>l'ecriture se fera à la fin du fichier XML.<br/>
     * Si aucun $qid n'est fourni la méthode tentera d'en créer un crèe un à la volée.
     * </p>
     * 
     * @param type $qid
     */
    public function write_xml_version ($qid) {
        
    }

    private function inner_build() {
        $this->xml_scope = $this->recuperer_xml_scope_from_id ($this->qid);
        $this->construire_query_from_xmlscope($this->xmlscope);
    }
    
    private function construire_query_from_xmlscope ( $xmlscope ) {
        if ( isset($xmlscope) and is_array($xmlscope) and count($xmlscope)>0 ) {
            $this->qbody = $xmlscope["query.body"];
            $this->qdbname = $xmlscope["query.dbname"];
            $this->qtype = $xmlscope["query.type"];
            /*
			 * [DEPUIS 06-09-15] @author BOR
			 * 		Resoud la contrainte liée à l'obligation de respecter la casse.	
			 */
			$this->list_qparams_in = ( $xmlscope["query.params.in"] && is_array($xmlscope["query.params.in"]) && count($xmlscope["query.params.in"]) ) ? 
				array_change_key_case($xmlscope["query.params.in"], CASE_LOWER) : $xmlscope["query.params.in"]; 
            $this->list_qparams_out = $xmlscope["query.params.out"];
        } else $this->signalError("err_sys_l00",__FUNCTION__, __LINE__); 
    }
    
    private function recuperer_xml_scope_from_id ($entry_id) {

        $path = WOS_GEN_PATH_DEPOT_QUERIES;

        $xml_tools = new MyXmlTools();
        $this->xmlscope = $xml_tools->acquiereXmlscopeFromPathAndIdInASecureWay($path, $entry_id, "err_sys_l013");
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->xmlscope);
    }
    
    
    /**
     * <p>Cette méthode permet d'effectuer une execution de la requete liée à QObject.
     *  La particularité de cette méathode est qu'elle nous évite de nous soucier du type du QObject.
     * </p>
     * <ul>Elle prend en paramètre :
     * <li>qparams_in : les données en entrée pour valider la requete.</li>
     * <li>err_code : l'erreur renvoyée en cas de problème dans l'acquisition des données.<br/>
     * <li>strict_mode : Est e qu'il faut déclencher ou non l'erreur en cas d'echec.<br/>
     * <u>Note</u> : Pour ce dernier, il n'est pas stricte car si on veut juste tester l'existence d'une occurence on aimerait pas
     * forcement déclencher une erreur_systeme.
     * </li>
     * </ul>
     * <p>Elle peut renvoyer un tableau de données. Tout dépend du type de la requete.<br/>
     * C'est donc au developpeur de se soucier du retour.
     * </p>
     * @author Lou Carther <lou.carther@deuslynn-entreprise.com>
     * @param type $qparams_in
     * @param type $err_code
     * @return type
     */
    public function execute ($qparams_in_values, $err_code="", $strict_mode = FALSE) {
        //$Qobject = new QUERY($this->qid);
		$qbody = strtolower($this->qbody);
		
		/*
         * [DEPUIS 06-09-15] @author BOR
         *  On met en minuscules les clés pour satisfaire la contrainte qui nous oblige à respecter la casse.
         */
        $qparams_in_values = ( $qparams_in_values && is_array($qparams_in_values) && count($qparams_in_values) ) ? array_change_key_case($qparams_in_values, CASE_LOWER) : $qparams_in_values; 
		
        $qtype = $this->qtype;
        //$qparams_in = $Qobject->getList_qparams_in();
        //$qparams_in = $_SESSION["qparams_in"];
        $qdbname = $this->qdbname;
        
        //On verifie s'il faut retourner un resultat
        $it_is_a_return_datas_query = ( strtolower($qtype) === "get" ) ? TRUE : FALSE;
        /*
         * [NOTE 15-08-14] Permet de retourner l'id du dernier élément ajouté.
         * On ne renvera que s'il sagit de set. Pour get, c'est au developpeur de faire de demander le dernier identifiant dans sa requete comme un grand.
         * Pour update c'est pas possible (à généraliser). De plus, il y a de grandes chances que le CALLER connaisse l'identifiant de la ligne a update.
         * Sinon, il se débrouille !!!
         */
        $return_last_insert_id = ( strtolower($qtype) === "set" ) ? TRUE : FALSE;
        //Si oui, on verifie si le mode_sctrict est activé.
        //Si oui, on verifie si une code_err a été défini pour trairer le no_resukt case.
        if( (isset($err_code) && $err_code != "") && $strict_mode === TRUE ) { $this->signalError ("err_sys_l015", __FUNCTION__, __LINE__,TRUE); }

        //On verifie s'il s'agit d'une requete preparée
        $it_is_a_prepared_query  = ( isset($this->list_qparams_in) and is_array($this->list_qparams_in) and count($this->list_qparams_in)>0 ) ?  TRUE : FALSE;
        //Si oui, on s'assure qu'on a un tableau en entrée qui est non vide
        if( $it_is_a_prepared_query === TRUE and !(is_array($qparams_in_values) and count($qparams_in_values)) ) $this->signalError ("err_sys_l016", __FUNCTION__, __LINE__,TRUE);
        //S'il s'agit effectivement d'une requete preparée et qu'on a le tableau, on verifie qu'on a toutes les clés attendues.
        //On ne verifie pas si elles sont nulles car une requête pourrait porter justement sur la nullité d'une occurence.
        else {

            if ( count($this->list_qparams_in) != count($qparams_in_values) ) {
                $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["QUERYID =>",$this->qid]);
                $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["EXPECTED =>",$this->list_qparams_in]);
                $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["WE GOT=>",$qparams_in_values]);
                $this->signalError ("err_sys_l017", __FUNCTION__, __LINE__,TRUE);
            }
            
            foreach ($this->list_qparams_in as $k => $v) {
                if ( !key_exists($k, $qparams_in_values) ) {
                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["QUERYID =>",$this->qid]);
                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["EXPECTED =>",$this->list_qparams_in]);
                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["WE GOT=>",$qparams_in_values]);
                    $this->signalError ("err_sys_l017", __FUNCTION__, __LINE__,TRUE);
                }
            }
        }
		
        $bdd = new WOS_DATABASE($qdbname);
        $bdd->tryConnection();
        
        //On vérifie s'il s'agit de l'exécution d'une procédure stockée
        if ( strtolower($qtype) === "sqlproc" || strtolower($qtype)  === "sqlproc_r" ) {
            
            if ( strtolower($qtype) === "sqlproc" ) {

                $r = $bdd->executePrepareProcedureWithoutResult($qbody, $qparams_in_values);
				
            } else {
				
                //TODO : Au besoin créer une méthode pour "sqlproc_r"
                //(29-11-14) Construction de la méthode avec retour
                $r = $bdd->executePrepareProcedureWithResult($qbody, $qparams_in_values);
            }
            
            $bdd = NULL;
            
            return $r;
        }
        
        //**
         if ( $it_is_a_return_datas_query ) {
            $return = ( $it_is_a_prepared_query ) 
                    ? $bdd->executePrepareQueryWithResult($qbody, $qparams_in_values)
                    : $bdd->executeSimpleQueryWithResult($qbody);
            
            if ( !( isset($return) && ($return instanceof PDOStatement) ) && $strict_mode ) {
                $this->signalError ($err_code, __FUNCTION__, __LINE__);
            } else if ( !( isset($return) && ($return instanceof PDOStatement) ) && !$strict_mode ) {
                return;
            }
            
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__,$qbody);
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__,$return);

            //On prend PDO::FETCH_ASSOC plutot que la valeur par défaut car avoir le numéro de colonne ne nous interesse pas.
            //De plus, on espère ainsi réduire le Temps de Reponse.
            $datas = $return->fetch(PDO::FETCH_ASSOC);
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $datas);
            if ( $datas ) {
                $Results_Table = array();
                do {
                    foreach ($datas as $k => $v) {
                        $foo[$k] = $v;
                    }
                    $Results_Table[] = $foo;
                } while ( $datas = $return->fetch(PDO::FETCH_ASSOC) );
                //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $Results_Table);
                //[NOTE 10-09-14] @author L.C. => On ferme la connexion ant de renvoyer quoique ce soit
                $bdd = NULL;
                
                return $Results_Table;
                
            } else { 
                if ( $strict_mode === TRUE ) {
                    $this->signalError ($err_code, __FUNCTION__, __LINE__);
                }
            }
        } else {
            
            if ( $it_is_a_prepared_query ) {
//                if ( $this->qid === "qryl4artn1" ) {
//                    var_dump("CHECKPOINT => ",__FUNCTION__,__LINE__,[$it_is_a_prepared_query,$return_last_insert_id,$r]);
//                }
                $r = $bdd->executePrepareQueryWithoutResult($qbody, $qparams_in_values, $return_last_insert_id);
                //[NOTE 10-09-14] @author L.C. => On ferme la connexion avant de renvoyer quoique ce soit
                $bdd = NULL;
                 
                return $r;
            } else {
                //On vérifie car avant [14-08-14] et la prise en compte de last_insert_id() on avait des instructions et je n'ai pas voulu dénaturer les anciens processus.
                if ( $return_last_insert_id ) {
                    $r = $bdd->executeSimpleQueryWithRowAffected($qbody);
                    
                    //[NOTE 10-09-14] @author L.C. => On ferme la connexion ant de renvoyer quoique ce soit
                    $bdd = NULL;
                    
                    return $r;
                } else {
                    $r = $bdd->executeSimpleQueryWithoutResult ($err_code, $return_last_insert_id);
                    
                    //[NOTE 10-09-14] @author L.C. => On ferme la connexion ant de renvoyer quoique ce soit
                    $bdd = NULL;
                    
                    return $r;
                }
            }
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
        //*/
        
    }
    
    /*
     * A la meme fonction que execute() à la seule différence que la méthode ne déclenche pas des erreurs de type page mais les renvoie.
     * Pour l'histoire, cette méthode a été créé pour éviter le phenomène de boucle infinie au niveau du module d'erreur quand il rencontrait lui meme une erreur.
     * Cela permet entre autre de ne pas toucher la méthode existante au risque de créer des désagrements.
     */
    public function execute_with_local_mode ($qparams_in_values, $err_code="", $strict_mode = FALSE) {
           
        //$Qobject = new QUERY($this->qid);
        $qbody = strtolower($this->qbody);
		/*
         * [DEPUIS 06-09-15] @author BOR
         *  On met en minuscules les clés pour satisfaire la contrainte qui nous oblige à respecter la casse.
         */
        $qparams_in_values = ( $qparams_in_values && is_array($qparams_in_values) && count($qparams_in_values) ) ? array_change_key_case($qparams_in_values, CASE_LOWER) : $qparams_in_values; 
		
        $qtype = $this->qtype;
        //$qparams_in = $Qobject->getList_qparams_in();
        //$qparams_in = $_SESSION["qparams_in"];
        $qdbname = $this->qdbname;
        
        //On verifie s'il faut retourner un resultat
        $it_is_a_return_datas_query = ( $qtype == "get" ) ? TRUE : FALSE;
        /*
         * [NOTE 15-08-14] Permet de retourner l'id du dernier élément ajouté.
         * On ne renvera que s'il sagit de set. Pour get, c'est au developpeur de faire de demander le dernier identifiant dans sa requete comme un grand.
         * Pour update c'est pas possible (à généraliser). De plus, il y a de grandes chances que le CALLER connaisse l'identifiant de la ligne a update.
         * Sinon, il se débrouille !!!
         */
        $return_last_insert_id = ( $qtype == "set" ) ? TRUE : FALSE;
        //Si oui, on verifie si le mode_sctrict est activé.
        //Si oui, on verifie si une code_err a été défini pour trairer le no_resukt case.
        if( (isset($err_code) && $err_code != "") && $strict_mode === TRUE ) { $this->signalError ("err_sys_l015", __FUNCTION__, __LINE__,TRUE); }

        //On verifie s'il s'agit d'une requete preparée
        $it_is_a_prepared_query  = ( isset($this->list_qparams_in) and is_array($this->list_qparams_in) and count($this->list_qparams_in)>0 ) ?  TRUE : FALSE;
        //Si oui, on s'assure qu'on a un tableau en entrée qui est non vide
        if( $it_is_a_prepared_query === TRUE and !(is_array($qparams_in_values) and count($qparams_in_values)) ) $this->signalError ("err_sys_l016", __FUNCTION__, __LINE__,TRUE);
        //S'il s'agit effectivement d'une requete preparée et qu'on a le tableau, on verifie qu'on a toutes les clés attendues.
        //On ne verifie pas si elles sont nulles car une requête pourrait porter justement sur la nullité d'une occurence.
        else {

            if ( count($this->list_qparams_in) != count($qparams_in_values) ) {
                return "__ERR_VOL_ROW_MSM";
            }
            
            foreach ($this->list_qparams_in as $k => $v) {
                if ( !key_exists($k, $qparams_in_values) ) {
                    return "__ERR_VOL_ROW_MSM";
                }
            }
        }

        $bdd = new WOS_DATABASE($qdbname);
        $bdd->tryConnection();
        
        
        //On vérifie s'il s'agit de l'exécution d'une procédure stockée
        if ( strtolower($qtype) === "sqlproc" || strtolower($qtype)  === "sqlproc_r" ) {
            
            if ( strtolower($qtype) === "sqlproc" ) {
                $r = $bdd->executePrepareProcedureWithoutResult($qbody, $qparams_in_values);
            } else {
                //TODO : Au besoin créer une méthode pour "sqlproc_r"
            }
            
            $bdd = NULL;
            
            return $r;
        }
        
        //**
         if ($it_is_a_return_datas_query) {
            if ( $it_is_a_prepared_query ) 
                $return = $bdd->executePrepareQueryWithResult($qbody, $qparams_in_values, TRUE);
            else 
                $return = $bdd->executeSimpleQueryWithResult($qbody, TRUE);
            
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__,$qbody);
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__,$return);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $return) )
                return $return;
                    
            //On prend PDO::FETCH_ASSOC plutot que la valeur par défaut car avoir le numéro de colonne ne nous interesse pas.
            //De plus, on espère ainsi réduire le Temps de Reponse.
            $datas = $return->fetch(PDO::FETCH_ASSOC);
//            $this->presentVarIfDebug(__FUNCTION__, __LINE__, $datas);
            if ( $datas ) {
                $Results_Table = array();
                do {
                    foreach ($datas as $k => $v) {
                        $foo[$k] = $v;
                    }
                    $Results_Table[] = $foo;
                } while ( $datas = $return->fetch(PDO::FETCH_ASSOC) );
                //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $Results_Table);
                //[NOTE 10-09-14] @author L.C. => On ferme la connexion ant de renvoyer quoique ce soit
                $bdd = NULL;
                
                return $Results_Table;
                
            } else { 
                if ($strict_mode === TRUE)$this->signalError ($err_code, __FUNCTION__, __LINE__);
            }
        } else {
            if ( $it_is_a_prepared_query ) {
                
                $r = $bdd->executePrepareQueryWithoutResult($qbody, $qparams_in_values, $return_last_insert_id, TRUE);
                
                //[NOTE 10-09-14] @author L.C. => On ferme la connexion ant de renvoyer quoique ce soit
                $bdd = NULL;
                
                return $r;
            } else {
                //On vérifie car avant [14-08-14] et la prise en compte de last_insert_id() on avait des instructions et je n'ai pas voulu dénaturer les anciens processus.
                if ( $return_last_insert_id ) {
                    $r = $bdd->executeSimpleQueryWithRowAffected($qbody, TRUE);
                    
                    //[NOTE 10-09-14] @author L.C. => On ferme la connexion ant de renvoyer quoique ce soit
                    $bdd = NULL;
                    
                    return $r;
                } else{
                    $r = $bdd->executeSimpleQueryWithoutResult ($err_code, $return_last_insert_id, TRUE);
                    
                    //[NOTE 10-09-14] @author L.C. => On ferme la connexion ant de renvoyer quoique ce soit
                    $bdd = NULL;
                    
                    return $r;
                }
            }
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
        //*/
        
    }
    
    /****************************************************************************************************/
    /************************************** GETTERS and SETTERS *****************************************/
    // <editor-fold defaultstate="collapsed" desc="Getters and Setters">
    public function getQid() {
        return $this->qid;
    }

    public function getQbody() {
        return $this->qbody;
    }
    
    public function setQbody($qbody) {
        //Il y a des cas où on souhaite modifier tout ou partie de qbody.
        //Exemple avec Location qui a le choix entre [...]15000, [...]15000, [...]15000
        $this->qbody = $qbody;
    }
    
    public function getQdbname() {
        return $this->qdbname;
    }

    public function setQdbname($qdbname) {
        $this->qdbname = $qdbname;
    }

    public function getQtype() {
        return $this->qtype;
    }

    public function getList_qparams_out() {
        return $this->list_qparams_out;
    }

    public function getList_qparams_in() {
        return $this->list_qparams_in;
    }

    public function getXmlscope() {
        return $this->xmlscope;
    }

    public function getQdata_array() {
        return $this->qdata_array;
    }
    
    public function setQdata_array($qdata_array) {
        if (isset($qdata_array) and is_array($qdata_array) and count($qdata_array)>0) {
            $intersect = array_intersect_key($qdata_array,$this->list_qparams);
            if(count($intersect)==count($$this->list_qparams)) {//On aurait aussi pu prendre 'qdata_array' pur comparer
                $this->qdata_array = $qdata_array;
            }else $this->signalError("err_sys_l50",__FUNCTION__, __LINE__);
        } else $this->signalError("err_sys_l00",__FUNCTION__, __LINE__); 
    }

    // </editor-fold>

}

?>
