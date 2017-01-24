<?php

class PCC_SESSION extends MOTHER
{
    /*
     * NOTICE : This is the SESSION INFRASTRUCTURE (key)
     * 
     * Multiple infos about The Visitor (visitor) [not_defined is possible if the Visitor we couldn't give an identity to the Visitor eventhough it's very rare]
     * Multiple Infos about The Hoster (hoster) [not_defined is possible if the Hoster is not known]
     * Vistor's type of auth [no_auth, once_auth, double_auth] (v_auth_type)
     * Relation Beteween Actors (a_rel)
     * Visitor snitch [referer's domain, country, city?, countrylang] (snitch_array) //Can be used to improve security on platform    
     * Session's next renew date (
     */
    private $run_lang;
    private $v_type;
    private $snitcher;
    private $prod_xmlscope;
    private $curr_wto;
    private $prev_wto;
    private $urq_scope;
    
    function __construct($run_lang,$v_type,$snitcher,$prod_xmlscope,$prev_wto,$curr_wto,$urq_scope) {    
        
        //We ensure that until the mother this class is innit.  
        parent::__construct(__FILE__,__CLASS__);
  
        $this->check_isset_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        $this->run_lang = $run_lang;
        $this->v_type = $v_type;
        $this->snitcher = $snitcher;
        $this->prod_xmlscope = $prod_xmlscope;
        $this->curr_wto = $curr_wto;
        $this->prev_wto = $prev_wto;
        $this->urq_scope = $urq_scope;
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        $this->run();
    }


    public function run() {
        //FROM TOP, on va write les infos et c'est tout
        $this->create_session_tidy_objects();
    }
    
    private function create_session_tidy_objects () {
        
        $STO = new SESSION_TO();
        $STO->insert_all_datas_into_session($this->prod_xmlscope, $this->run_lang, $this->v_type, $this->snitcher, $this->prev_wto, $this->curr_wto, $this->urq_scope);
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$STO,'v_d');
        $this->write_session_in_sto_case($STO);
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION,'v_d');            
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
  
    
    private function write_session_in_sto_case ($Entry) {
        if ( isset($Entry) ) {
            $_SESSION['sto_infos'] = $Entry;
            $_SESSION['rsto_infos'] = ( isset($_SESSION) && key_exists("rsto_infos", $_SESSION) && isset($_SESSION["rsto_infos"]) ) ? $_SESSION["rsto_infos"] : array();
            //Où seront logés les UD (UserDatas).
            $_SESSION['ud_carrier'] = array();
            //[NOTE au 28-11-13 : Commentaire à venir]
            /*
             * [NOTE au 06-08-14] On insère toute les informations fournies par le système or environnement produit.
             * Pour l'instant on insère les données ici mais on pourra changer l'instruction plus tard.
             * 
             * [TODO ajouté 15-09-15]
             *  Les données "systx" devrait être disponible depuis un fichier externe configurable. 
             *  Le principe même de venir modifier l'engine ne me semble pas correcte.
             */
            $systx = [
                "now"           => time(), //Le TIMESTAMP du temps actuel. Il sert notamment à forcer la mise à jour du cache des navigateurs grace à ?[TIMESTAMP]
                "year"          => date('y'), 
                "fullyear"      => date('Y'),
                /*
                 * TODO : Récuperer cette donnée dans le fichier de configuration du produit
                 * 
                 * [DEPUIS 15-09-15] @author BOR
                 *      La donnée est configurable depuis index.php
                 * 
                 */
                "prod_domain"   => WOS_PRODDOMAIN 
            ];
            $_SESSION['systx'] = $systx;
        } else $this->signalError ("err_sys_l00",__FUNCTION__,__LINE__); 
    }
    
    
    /**
     * [NOTE 26-08-14] @author Richard DIEUD <lou.carther@deuslynn-entreprise.com>
     * La fonctionnalité qu'aurait du gérer a été transférée au service qui se charge de la connexion.
     * Ceci aussi aussi et surtout pour les raisons suivantes :
     *      (1) Une connexion est généralement demandée au niveau de la couche Entity ou Worker
     *      (2) Pour créer un objet de type SESSION, il faut passer des données quand, elles le sont génralement déjà au moment où on demarre une connexion.
     * @deprecated since version vb1.test
     */
    public function handle_log_in_process () { }
    
    
    /**
     * [NOTE 26-08-14] @author Richard DIEUD <lou.carther@deuslynn-entreprise.com>
     * La fonctionnalité qu'aurait du gérer a été transférée au service qui se charge de la connexion.
     * Ceci aussi aussi et surtout pour les raisons suivantes :
     *      (1) Une connexion est généralement demandée au niveau de la couche Entity ou Worker
     *      (2) Pour créer un objet de type SESSION, il faut passer des données quand, elles le sont génralement déjà au moment où on demarre une connexion.
     * @deprecated since version vb1.test
     */
    public function handle_log_out_process () { }
    
    
    /**
     * [NOTE 26-08-14] @author Richard DIEUD <lou.carther@deuslynn-entreprise.com>
     * La fonctionnalité qu'aurait du gérer a été transférée au service qui se charge de la connexion.
     * Ceci aussi aussi et surtout pour les raisons suivantes :
     *      (1) Une connexion est généralement demandée au niveau de la couche Entity ou Worker
     *      (2) Pour créer un objet de type SESSION, il faut passer des données quand, elles le sont génralement déjà au moment où on demarre une connexion.
     * @deprecated since version vb1.test
     */
    private function write_session_in_rsto_case () { }
    
    
    static function giveMeUspklangIfSessionRestExistsAndUserConnected() {
        $STO = new SESSION_TO();
        $session_not_void = PCC_SESSION::doesSessionExistAndIsNotvoid();
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION["sto"],'v_d');              
        if(!$session_not_void) { return FALSE; } //FALSE car SESSION n'existe pas
        else {
            $CXH = new CONX_HANDLER();
            if ( key_exists("rsto_infos",$_SESSION) && count($_SESSION["rsto_infos"]) > 0 && $CXH->is_connected() ) {
                $RSTO_INFOS = new RSTO_INFOS();
                $RSTO_INFOS = $_SESSION["rsto_infos"]; //Sort of cast
                /* ATTENTION : returnet/ou isset n'accepte pas d'eveluer des retour d'autres fonction directements. Il fau d'abord les passer à une variable
                if ( isset($RSTO_INFOS->getIs_connected()) and $RSTO_INFOS->getIs_connected() == TRUE ) return $RSTO_INFOS->getUspklang(); 
                else return NULL ;                  
                //*/
                $connect = $RSTO_INFOS->getIs_connected();
                $uspklang = $RSTO_INFOS->getUspklang();
                return ( isset($connect) and $connect == TRUE ) ? $uspklang : NULL; 
            } else return FALSE; //FALSE car rsto_infos n'existe pas
        }
    }
    

    static function doesSessionExistAndIsNotVoid() {
        return ( @isset($_SESSION) && @count($_SESSION) && key_exists("sto_infos", $_SESSION) && @count($_SESSION["sto_infos"]) ) ? TRUE : FALSE;
    }
    
    
    static function displayInfosAboutTheBadSession() {
        if ( @PTF_RM == "T_RM_TRM" || @PTF_RM == "T_RM_DBRM")
          echo "<p>This is THE SSID ( ".session_id()." ) for the no well-formed file. Found it here ( ".session_save_path()." )</p>";
    }
    /*
    //Not tested
    static function get_id_and_type_in_sto_if_exist () {
        $visitor_datas = Array();
        $visitor_datas = [
            "id" => "", //will be full in the process
            "type" => "" //Will be full in the process
        ];
        
        $temp_my_session;
        
        //We check if an object is defined into $_SESSION.
        //Only in case we've got anomaly 
        if ( count($_SESSION)>1 or count($_SESSION)==0 ) { 
            PCC_SESSION::displayInfosAboutTheBadSession(); 
            $this->signalError("err_sys_l318",__FUNCTION__,__LINE__); 
        }//In case, we've got only one object into SESSION Object
        else if( count($_SESSION)==1 ) {
            //@$this->presentVar(__FUNCTION__, __, $_SESSION);
            //We're ensuring 'sto' is defined in $_SESSION. 
            if ( @!isset($_SESSION['sto']) ) { 
                //USE IT FOR DEBUG. It's AUTOMATIC WHEN WE'RE IN DEBUG MODE
                PCC_SESSION::displayInfosAboutTheBadSession(); 
                $this->signalError("err_sys_l319",__FUNCTION__,__LINE__); 
            } 
            else if ( @isset($_SESSION['sto']) and count(@$_SESSION['sto']) >=1 )  
            {  
                $class_name = strtolower(get_class($_SESSION['sto']));
                switch($class_name) 
                {
                    case "session_to" :
                        $visitor_datas['id'] = NULL; //The visitor is an anononymous
                        $visitor_datas['type'] = AG_W3;
                    break;
                    case "session_to_rest" :
                        //$temp_my_session = new SESSION_TO_REST(); //WE DO IT JUST TO GET ACCESS TO THE OBJECT VIA AUTO IMPLEMENT
                        $temp_my_session = $_SESSION['sto'];
                        $temp_v = $temp_my_session->getVisitor_infos();

                        if( !isset($temp_v) ) {
                            PCC_SESSION::displayInfosAboutTheBadSession();
                            $this->signalError ("err_sys_l321", __FUNCTION__, __LINE__);
                        }

                        $temp_cn = strtolower(get_class($temp_v));    
                        if ( isset($temp_v) and $temp_cn != "actor_as_ftpfl" and $temp_cn != "actor_as_acc") {
                            PCC_SESSION::displayInfosAboutTheBadSession();
                            $this->signalError ("err_sys_l321", __FUNCTION__, __LINE__);
                        }
                        //Yeah I love a source I can rely on !
                        if ( isset($temp_v) and ($temp_cn == "actor_as_ftpfl" or $temp_cn == "actor_as_acc") ) {
                            if($temp_cn == "actor_as_ftpfl") {
                                //$visitor = new ACTOR_AS_FTPFL(); //JUST TO AHE AUTO IMPLEMENTATION FEATS
                                $visitor = $temp_v;
                                $temp_id = $visitor->getFtpfl_id(); //NOTE : we dont use the return from function directly because isset() bellow doesn't accept that
                                if ( isset($temp_id) and is_string($visitor->getFtpfl_id()) and $visitor->getFtpfl_id() != "" ) {
                                    $visitor_datas['type'] = AG_RFT1;
                                    $visitor_datas['id'] = $visitor->getFtpfl_id();
                                } else $this->signalError ("err_sys_l322", __FUNCTION__, __LINE__);

                            } else if ( $temp_cn == "actor_as_acc" ) {
                                //$visitor = new ACTOR_AS_ACC(FALSE); //JUST TO AHE AUTO IMPLEMENTATION FEATS
                                $visitor = $temp_v;
                                $temp_id = $visitor->getAcc_id(); //NOTE : we dont use the return from function directly because isset() bellow doesn't accept that
                                if ( isset($temp_id) and is_string($visitor->getAcc_id()) and $visitor->getAcc_id() != "" ) {
                                     $visitor_datas['type'] = AG_R11;
                                     $visitor_datas['id'] = $visitor->getAcc_id();
                                } else $this->signalError ("err_sys_l322", __FUNCTION__, __LINE__);
                            }
                        }
                    break;
                    default:
                        PCC_SESSION::displayInfosAboutTheBadSession();
                        $this->signalError ("err_sys_l320", __FUNCTION__, __LINE__);
                    break;
                }
            } else $this->signalError ("err_sys_l04", __FUNCTION__, __LINE__);
        } 
        
        return $visitor_datas;
    }
    //*/
}
?>
