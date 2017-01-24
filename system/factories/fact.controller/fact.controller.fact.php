<?php
require_once WOS_PATH_INC_CONTROLLER_ENGS;

class CONTROLLER extends MOTHER
{
    private $prod_xmlscope;
    private $wta;
    private $current_WTO;
    private $prev_WTO;
    private $urqXmlScopeIntoArray;
    //private $human_mode; //HUMAN_MODE est maintenant une constante definie dans le fichier general des declarations de constantes
    private $snitch_infos_array;
    private $wm_infos_array;
    private $v_type;
    private $running_lang; //On l'utilise avant qu'il soit inséré dans SESSION
    
    function __construct($entry_wta, $prod_xmlscope) 
    {   
        //We ensure that until the mother this class is innit.  
        parent::__construct(__FILE__, __CLASS__);
        $this->check_isset_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, [$entry_wta,$prod_xmlscope],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        //exit();
        
        $this->prod_xmlscope = $prod_xmlscope;
        $this->wta = $entry_wta;
        $this->run();         
    }
    
    private function run()
    {
        //************* CHECKING ZONE **********************************************/
        $this->start_checking_processes();        
        
        //************* MANAGMENT ZONE *********************************************/
        $this->start_managment_processes();
    }
    
    private function start_checking_processes () {
        
        /***************************************************************************/
        /**************************************** SNITCHER ZONE ********************/
        
        $SNITCHER = new SRVC_SNITCHER($this->prod_xmlscope);
        $this->snitch_infos_array = $SNITCHER->getSnitch_infos_array();
        
        /*
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->snitch_infos_array);
        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->prod_xmlscope["available_lang_aliases"]);
        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        //*/
        
        /***************************************************************************/
        /************************************ LANG_HANDLER ZONE ********************/
        
        $LANG_HANDLER = new LANG_HANDLER();
        
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, [$this->snitch_infos_array,$this->snitch_infos_array["iplang"],$this->snitch_infos_array["loc_ctr_lg_code"], $this->prod_xmlscope["default_lang"]]);
        
//        $this->running_lang = $LANG_HANDLER->init_new_demand($this->snitch_infos_array["iplang"],$this->snitch_infos_array["loc_ctr_lg_code"], $this->prod_xmlscope["default_lang"]);
        $this->running_lang = $LANG_HANDLER->init_new_demand($this->snitch_infos_array["lang_local_cookie"],$this->snitch_infos_array["loc_ctr_lg_code"], $this->prod_xmlscope["default_lang"],$this->prod_xmlscope["available_lang_aliases"]);
        
        /*
        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->running_lang);
        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        //*/

        /***************************************************************************/
        /*************************************** URQCHECKER ZONE *******************/
        
        //(1) THIS to URQ_CHECKER : Send 'entry_wta', 'entry_app_conf'
        $Local_URQ_CKR = new PCC_URQCHECKER($this->wta, $this->running_lang);
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        //(2) URQ_CHECKER to THIS : Here is WTO infos (prev and current), URQSCOPE
        $this->urqXmlScopeIntoArray = $Local_URQ_CKR->getUrq_scope();
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->urqXmlScopeIntoArray);
        $this->current_WTO = $Local_URQ_CKR->getCurr_wto();
        //Sert à WM pour savoir s'il s'agit ou non de la premiere requete de l'user pour régler les problèmes de lang
        $this->prev_WTO = $Local_URQ_CKR->getPrev_wto();
           //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->current_WTO);
           //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->prev_WTO);
        
        
        /***************************************************************************/
        /***************************************** WATCHMAN ZONE *******************/
        
       /*
        *       Il peut arriver que prev_WTo soit vide, dans le cas où le fichier de SESSION n'existe pas ou a été supprimé
        *       Mais le args_checker de WM va déclencher une erreur alors que ce n'en est pas une.
        *       On va donc tricher et le mettre à FALSE en attendant que le checker face son travail et lui rendre sa valeur originelle après.
        * * */
		
        //*/
        $this->prev_WTO = ( isset($this->prev_WTO) ) ? $this->prev_WTO : FALSE;
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->prev_WTO, 'v_d');
        $WM = new CONTROLLER_WATCHMAN($this->snitch_infos_array, $this->running_lang, $this->current_WTO, $this->prev_WTO, $this->prod_xmlscope, $this->urqXmlScopeIntoArray);
        
        //Ici on a un get de curr_wto car c'est possible que WM ait modifier quelque chose dans WTO
        //$this->current_WTO = $WM->init_security_control(); //FAUX
        $this->current_WTO = $WM->getCurr_wto();
        
        //RAPPEL IMPORTANT : ON NE SE BASE PLUS SUR RUN_LANG (old) MAIS SUR LA DECISION DE WM
        $this->running_lang = $this->current_WTO->getWatchman_decision_on_lang();

        
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->running_lang, 'v_d');
        //$this->wm_infos_array = $WM->getWm_infos_array(); //Vaut mieux ne pas l'utiliser dans le process ca pourrait porter à confusion
        $this->v_type = $WM->getWm_infos_array()["v_type"]; 
        
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->running_lang, 'v_d');
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->wm_infos_array, 'v_d');
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->current_WTO, 'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        
        
        
        /***************************************************************************/
        /****************************************** SESSION ZONE *******************/
        $PCC_SESSION = new PCC_SESSION($this->running_lang, $this->v_type, $SNITCHER, $this->prod_xmlscope, $this->prev_WTO,$this->current_WTO,$this->urqXmlScopeIntoArray);
        
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION, 'v_d');
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        
        
        /***************************************************************************/
        /***************************************** DCLEANER ZONE *******************/
        //Permet de verifier que valeur entrées ups et POST sont "propres"
        //Pour cela il faut abosulement savoir s'il y a des ups et des post
  
    }
    
    private function  start_managment_processes () {
        $pageid = $_SESSION["sto_infos"]->getUrq_scope()["urqpageid"];
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION);
        $FACT_PROCESS = new FACT_PROCESSOR ($pageid, $this->running_lang); 
//        $FACT_PROCESS = new FACT_PROCESSOR ($pageid, "fr");
        $WRK = $FACT_PROCESS->getC_process_worker();
        
        /**
         * Permet de préparer les données entrantes si elles existent et si elles sont attendues.
         */
        $bounce_worker = $WRK->prepare_datas_in();
        /*
         * [DEPUIS 19-08-16]
         *      Permet à un WORKER de faire lancer indirectement un autre.
         *      L'avantage est d'éviter les redirections qui ont pour conséquence de ralentir le chargement de la PAGE finale.
         */
        if ( $bounce_worker instanceof WORKER_BOUNCE && $bounce_worker->getWorker_name() ) {
            $this->worker_bounce_once($bounce_worker,$pageid,$this->running_lang);
        }
        /** 
         * Process in peut faire appel directement à la base de données.
         * On ne centralise pas cette tâche d'accès à la bdd auprès de FACT.DATA car c'est marginal.
         */
//        $WRK->on_process_in();
        $bounce_worker = $WRK->on_process_in();
        /*
         * [DEPUIS 19-08-16]
         *      Permet à un WORKER de faire lancer indirectement un autre.
         *      L'avantage est d'éviter les redirections qui ont pour conséquence de ralentir le chargement de la PAGE finale.
         */
        if ( $bounce_worker instanceof WORKER_BOUNCE && $bounce_worker->getWorker_name() ) {
            $this->worker_bounce_once($bounce_worker,$pageid,$this->running_lang);
        }
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        /**
         * A ce stade nous avons effectué toutes les opérations liées au in.
         * Nous allons maintenant nous attardées aux processus out.
         */
        $bounce_worker = $WRK->on_process_out();
        /*
         * [DEPUIS 19-08-16]
         *      Permet à un WORKER de faire lancer indirectement un autre.
         *      L'avantage est d'éviter les redirections qui ont pour conséquence de ralentir le chargement de la PAGE finale.
         */
        if ( $bounce_worker instanceof WORKER_BOUNCE && $bounce_worker->getWorker_name() ) {
            $this->worker_bounce_once($bounce_worker,$pageid,$this->running_lang);
        }
        // $this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION["ud_carrier"]);
        
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->running_lang);
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION);
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        
        //$FACT_VIEW = new FACT_VIEW($pageid, "fr"); //For testing
        $FACT_VIEW = new FACT_VIEW($pageid, $this->running_lang);
        
    }
    
    
    private function worker_bounce_once (WORKER_BOUNCE $bounce_worker,$pageid,$running_lang) {
        
        $classname = $bounce_worker->getWorker_name();
        $file = $bounce_worker->getFile();
        
        require_once $file;
        
        if ( class_exists($classname)  ) {
            
            $WRK = new $classname($running_lang);

            $WRK->prepare_datas_in();
            $WRK->on_process_in();
            $WRK->on_process_out();
            
            $FACT_VIEW = new FACT_VIEW($pageid, $running_lang);
            
            exit();
        }
        
    }

}