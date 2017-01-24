<?php
/**
 * This class is usally used by PHH_HEAD to get infos over actors.
 * 1- Visitor_id and Visitor_type. We can't get the entire visitor infos right here because it's created when the visitor log on.
 * Note : If visitor is log on, we'll find this infos into Session. Otherwise, we consider it's an anon
 * 2- hoster_infos
 * 3- Relation between the both actors.
 */
class ACTORS extends MOTHER {
    
    private $wto;
    private $visitor_id;
    private $visitor_type;
    
    
    //function __construct(WTO $entry_wto) //NON : peut declecher une 'fatal_error' ce qui ouvre une fenetre sur l'architecture du systeme. Verifier le type par nous meme est plus judicieux
    function __construct($entry_wto) 
    {
        //We ensure that until the mother this class is innit.  
        parent::__construct(__FILE__,__CLASS__);

        if (isset($entry_wto) and is_object($entry_wto) and get_class($entry_wto) === "WTO") {
            $this->wto = $entry_wto;
            $this->run();
        }
        else $this->signalError("err_sys_l31", __CLASS__, __FUNCTION__, __LINE__);
    }
    
    private function run()
    {
        //-> Work on the visitor
            //->Give me actor infos or Array() for anonymous
        $this->acquiereVisitorTypeAndId();
    }
       
    /********************************************************************************************************************************/
    /**************************************************** START INNER PROCESSES *****************************************************/
    /**
     * This function allow us to check if an actor exits into the db corresponding to the id we got.
     * It's interesting because we got some basic ids that allow us to continue the process.
     * Ids = acc_id, gnr_id, lpfl_id
     * @param type $giusname
     * @return \ACTOR_AS_ACC|boolean
     */
    private function doesAccountExistGiusnameGivenInParam ($giusname) {
        $exist = FALSE;
        if( isset($giusname) and $giusname != "" ) {
            $A_Actor = new ACTOR_AS_ACC(TRUE);
            $A_Actor->checkActorExistenceById($giusname);
            //We will use this Actor Object again. So, we return it.
            return $A_Actor;
        } else $this->signalError ("err_sys_l00",__FUNCTION__,__LINE__);
        
        return $exist;
    }
    
    
    /********************************* VISITOR */
    //Not tested
    private function acquiereVisitorTypeAndId() {
        //Check out if visitor is already defined in SESSION
        $session_not_void = PCC_SESSION::doesSessionExistAndIsNotvoid();
        
        if($session_not_void) {
            $Visitor_Datas = PCC_SESSION::get_id_and_type_in_sto_if_exist();
            $this->visitor_type = $Visitor_Datas['type'];
            $this->visitor_id = $Visitor_Datas['id']; 
        }else { 
            //This means, either :
            //- it's the first visit for the visitor
            //- It's not the first visit for the visitor but he has deleted the Cookie Session.
            $this->visitor_type = AG_W3;
            $this->visitor_id = NULL; 
        }
    }
    
    
    
    /***************************************************** END INNER PROCESSES ******************************************************/
    /********************************************************************************************************************************/
    
    /********************************************************************************************************************************/
    /************************************************ START GETTERS AND SETTERS *****************************************************/
    
    public function getVisitor_id() {
        return $this->visitor_id;
    }

    public function setVisitor_id($visitor_id) {
        $this->visitor_id = $visitor_id;
    }

    public function getVisitor_type() {
        return $this->visitor_type;
    }

    public function setVisitor_type($visitor_type) {
        $this->visitor_type = $visitor_type;
    }

    
    /************************************************** END GETTERS AND SETTERS *****************************************************/
    /********************************************************************************************************************************/
    
    

            
}

?>
