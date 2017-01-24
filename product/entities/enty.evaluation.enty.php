<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of enty
 *
 * @author arsphinx
 */
class EVALUATION extends PROD_ENTITY {
    
    private $tbevlid;
    
    private $tbevl_artid;
    private $tbevl_art_eid;
    
    private $tbevl_evltpid;
    private $eval_code;
    private $eval_code_fe;
    private $eval_lib;
    
    private $tbevl_start_evt;
    /**
     * Contient toutes les informations sur l'évènement qui a permis que l'évaluation soit créé.
     * C'est l'équivalent d'un onread sur un Objet PROD_EVENT.
     * @var Array 
     */
    private $start_evt_table;
    
    private $tbevl_end_evt;
    /**
     * Contient toutes les informations sur l'évènement qui a permis que l'évaluation soit terminée.
     * C'est l'équivalent d'un onread sur un Objet PROD_EVENT.
     * @var Array 
     */
    private $end_evt_table;
    
    
    /********** RULES ***********/
    /**
     * Permet de s'assurer que le code event envoyé par l'utilisateur est connu et conforme.
     * De plus, on a l'équivalent de la base de données ce qui rend encore plus l'Entity autonome.
     * 
     * @var Array 
     */
    private $_EVAL_EVT_TYPES;
    /**
     * Permet d'avoir une équivalent avec de la base de données ce qui rend encore plus l'Entity autonome.
     * 
     * @var Array 
     */
    private $_EVAL_TYPES;
            
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = ["tbevlid","tbevl_artid","tbevl_art_eid","tbevl_evltpid","eval_code", "eval_code_fe", "eval_lib", "tbevl_start_evt", "start_evt_table", "tbevl_end_evt", "end_evt_table"];
        $this->needed_to_loading_prop_keys = ["tbevlid","tbevl_artid","tbevl_art_eid","tbevl_evltpid","eval_code", "eval_code_fe", "eval_lib", "tbevl_start_evt", "start_evt_table", "tbevl_end_evt", "end_evt_table"];
        
        $this->needed_to_check_prop_keys = ["actor","artid"];
        $this->needed_to_create_prop_keys = ["actor","eval_code","art_eid"];
        
        /********* RULES **********/
        
        $this->_EVAL_EVT_TYPES = [
            "eval_spcl"     => 9, 
            "ueval_spcl"    => 10, 
            "eval_cl"       => 11, 
            "ueval_cl"      => 12, 
            "eval_dlk"      => 13, 
            "ueval_dlk"     => 14
        ];
        
        $this->_EVAL_TYPES = [
            "_EVAL_SPCL"    => 1, 
            "_EVAL_CL"      => 2, 
            "_EVAL_DLK"     => 3, 
            "_EVAL_VOID"    => 4
        ];
        
    }

    
    protected function build_volatile($args) { }

    public function exists($args, $std_err_enbaled = FALSE) {
        //QUESTION : Existe une evéluation active pour Article donné par l'utilisateur passé en paramètre (Données sur l'évaluation/FALSE)
        /*
         * FAlSE signifie qu'il n'y a jamais d'Evaluation pour l'Article donnée par le Compte passé en paramètre.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //RAPPEL : ["actor","artid"]
        $com  = array_intersect( array_keys($args), $this->needed_to_check_prop_keys);
        
        if ( count($com) != count($this->needed_to_check_prop_keys) ) {
            
            if ( $std_err_enbaled ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$this->needed_to_check_prop_keys],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$args],'v_d');
                $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
            } else  {
                return;
            }
            
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    
                    if ( $std_err_enbaled ) {
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                        $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                    } else  {
                        return;
                    }
                } 
            }
        }
        
        //On vérifie si l'utilisateur existe
        $PA = new PROD_ACC();
        $A_E = $PA->exists_with_id($args["actor"], TRUE);
        
        if (! $A_E ) {
            return "__ERR_VOL_ACC_GONE";
        }
        
        //On commence par vérifier dans la table TableEvaluation
        $QO = new QUERY("qryl4evaln1");
        $params = array(":actor" => $args["actor"] , ":artid" => $args["artid"]);
        $evt_datas = $QO->execute($params);
        
        if (! $evt_datas ) {
            return FAlSE;
        } else {
            return $evt_datas[0];
        }
        
        
    }
    
    public function exists_with_id($id) {
        //QUESTION : Existe une evéluation active dont l'identifiant de la ligne est passé en paramètre (Données sur l'évaluation/FALSE)
        /*
         * FAlSE signifie qu'il n'y a jamais d'Evaluation pour l'Article donnée par le Compte passé en paramètre.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On commence par vérifier dans la table TableEvaluation
        $QO = new QUERY("qryl4evaln4_wtdlo");
//        $QO = new QUERY("qryl4evaln4"); //[DEPUIS 11-09-15] @author
        $params = array(":tbevlid" => $id);
        $evt_datas = $QO->execute($params);
        
        if (! $evt_datas ) {
            return FAlSE;
        } else {
            return $evt_datas[0];
        }
        
        
    }

    protected function init_properties($datas) {
        /*
         * [NOTE 25-11-14] @author L.C.
         * J'ai arreté avec check_isset_and_not_empty_entry_vars() car le bit n'est pas ici de 
         */
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $datas, TRUE);
        
        foreach($datas as $k => $v) {
            $$k = $v;
            
            if (! (!empty($this->prop_keys) && is_array($this->prop_keys) && count($this->prop_keys) ) ) {
                $this->signalError ("err_sys_l4comn4", __FUNCTION__, __LINE__);
            }
            
            if ( count($this->needed_to_loading_prop_keys) != count(array_intersect(array_keys($datas), $this->needed_to_loading_prop_keys)) ) {
                
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["EXPECTED => ",$this->needed_to_loading_prop_keys],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ",array_keys($datas)],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,["CHECK HERE =>",  array_diff($this->needed_to_loading_prop_keys, array_keys($datas))],'v_d');
                $this->signalError ("err_sys_l4comn5", __FUNCTION__, __LINE__,TRUE);
            } 
            
            /*
             * On vérifie que les données entrantes sont attendues.
             * NOTE : On ne vérifie que les clés.
             */
            if (! in_array($k, $this->prop_keys) ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,"KEY => ".$k,'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__,$this->prop_keys,'v_d');
                $this->signalError ("err_sys_l4comn3", __FUNCTION__, __LINE__,TRUE);
            } 
            
            $this->all_properties[$k] = $this->$k = $datas[$k];
        }
    }

    protected function load_entity($id, $std_err_enbaled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //Intérroger la base de données pour récupérer les données sur la RELATION
        $datas = $this->exists_with_id($id);
        
        if ( !$datas && $std_err_enbaled ) 
        {
            $this->signalError ("err_user_l4comn6", __FUNCTION__, __LINE__);
        }
        else if ( !$datas && !$std_err_enbaled ) 
        {
            return 0;
        }
        
        $EVT = new PROD_EVENT();
        //On créé START_EVENT_TABLE
        $start_evt = $EVT->exists($datas["tbevl_start_evtid"]);
        
        //On créé END_EVENT_TABLE
        $end_evt = ( isset($datas["tbevl_end_evtid"]) && $datas["tbevl_end_evtid"] !== "" ) ? $EVT->exists($datas["tbevl_end_evtid"]) : NULL;
        
        $loads = [
            "tbevlid"           => $datas["tbevlid"],
            "tbevl_artid"       => $datas["tbevl_artid"],
            "tbevl_art_eid"     => $datas["art_eid"],
            "tbevl_evltpid"     => $datas["tbevl_evltpid"],
            "eval_code"         => $datas["evltp_code"], 
            "eval_code_fe"      => $datas["evtype_fe"], 
            "eval_lib"          => $datas["eval_lib"], 
            "tbevl_start_evt"   => $datas["tbevl_start_evtid"], 
            "start_evt_table"   => $start_evt, 
            "tbevl_end_evt"     => $datas["tbevl_end_evtid"], 
            "end_evt_table"     => $end_evt
        ];
        
        if ( !count($loads) ) 
        { 
            if ( $std_err_enbaled ) $this->signalError ("err_sys_l4comn1", __FUNCTION__, __LINE__);
            else return 0;
        } 
        else 
        {
            $this->init_properties($loads);
            $this->is_instance_load = TRUE;
            return $loads;
        }
        
        //PARANO : Just in CASE !!
        return $loads;
    }

    protected function on_alter_entity($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        /* EXEMPLE : MODELE
        $args = [
            "old_tbevlid" => ...,
            "end_evtid" => ...,
        ];
        //*/
        /*
         * Permet de mettre à jour une ligne Evualuation puis de créer un nouvel évènement.
         * La méthode renvoie ensuite l'identifiant de l'évènement qui servira pour créer la nouvelle ligne.
         * 
         * [NOTE 08-09-14] @author L.C.
         * Permet de mettre à jour une EVAL en mettant fin à celle dont l'identifiant est envoyé en paramètre
         *  CALLER doit maintenant envoyer lui même l'évènement de fin !!!
         */
        
        //On met à jour l'ancienne Evaluation avec l'évènement nouvellement créé
        $QO = new QUERY("qryl4evaln2");
        $params = array(":end_evtid" => $args["end_evtid"] , ":tbevlid" => $args["old_tbevlid"]);
        $QO->execute($params);
        
        return TRUE;
        
    }

    public function on_create_entity($args, $std_err_enbaled = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        //On vérifie la présence des données obligatoires : ["actor","eval_code","art_eid"]
        $com  = array_intersect( array_keys($args), $this->needed_to_create_prop_keys);
        
        if ( count($com) != count($this->needed_to_create_prop_keys) ) {
            if ( $std_err_enbaled ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$this->needed_to_create_prop_keys],'v_d');
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["WE GOT => ",$args],'v_d');
                $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
            } else  {
                return;
            }
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    if ( $std_err_enbaled ) {
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                        $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                    } else  {
                        return "__ERR_VOL_L4_DATAS_MSG";
                    }
                } 
            }
        }
        
        //On vérifie si l'ARTICLE existe
        $ART = new ARTICLE();
        $A_E = $ART->exists($args["art_eid"]);
        if (! $A_E ) {
            return "__ERR_VOL_ART_GONE";
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $A_E) ) {
            return $A_E;
        }
        
        //On vérifie si le Compte de l'Actor existe et est toujours Actif
        $PA = new PROD_ACC();
        $PA_E = $PA->exists_with_id($args["actor"], true);
        if (! $PA_E ) {
            return "__ERR_VOL_ACTOR_GONE";
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $A_E) ) {
            return $PA_E;
        }
        
        //On vérifie si l'évaluation donnée par CALLER est conforme
        if (! in_array(strtoupper($args["eval_code"]),  array_keys($this->_EVAL_TYPES)) ) {
            return "__ERR_VOL_EVALTYPE_UKNW";
        } else {
            /*
             * On vérifie si le CALLER essaie de passer un evaltype de type "VOID".
             * Cela est interdit. En effet, on ne peut faire passer que : _EVAL_SPCL, _EVAL_CL ou _EVAL_DLK
             */
            if ( strtoupper($args["eval_code"]) === "_EVAL_VOID" ) {
                return "__ERR_VOL_EVALTYPE_UKNW";
            }
        }
        
        //On vérifie si une Evaluation active existe
        $EV_E = $this->exists(["actor" => $args["actor"],"artid" => $A_E["artid"]]);
        if ( !isset($EV_E) ) {
            return "__ERR_VOL_UXPTD";
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $A_E) ) {
            return $EV_E;
        } 
        
        //Si Oui on donc vérifier laquelle et procéder aux modifications
        $start_evtid = $LBA_EVT = $evltpid = $evtype = NULL;
        if ( $EV_E ) {
            $cr_eval = $EV_E["evtype_fe"];
            
            /*
             * RULES : 
             *      (1) Si le type d'Evaluation courant est le même que celui passé en paramètre ...
             *          ... On considère qu'il s'agit d'un evenement de type ueval. On choisi le bon eventype.
             *          On appelle onalter. La méthode se chargera de créer l'évènement de fin puis l'évènement de début de la prochaine relation
             *      (2) Si le type est différent on choisi le bon event type
             */ 
            if ( strtoupper($cr_eval) === strtoupper($args["eval_code"]) ) {
                /*
                 * Alors il s'git d'un évènement de type UEVAL. Il ne nous reste plus qu'à déterminer laquelle.
                 * RAPPEL : On ne peut pas avoir "_EVAL_VOID"
                 */
                $evtype = $this->Acquiere_EvtTypeId_From_EvalFE(strtoupper($args["eval_code"]), TRUE);
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $evtype) ) {
                    return $evtype;
                }
                $evltpid = $this->Acquiere_EvalTypeId_From_EvalFE("_EVAL_VOID");
            } else {
                
                $evtype = $this->Acquiere_EvtTypeId_From_EvalFE(strtoupper($args["eval_code"]));
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $evtype) ) {
                    return $evtype;
                }
                $evltpid = $this->Acquiere_EvalTypeId_From_EvalFE(strtoupper($args["eval_code"]));
                
                //On vérifie si l'EVAL précédente est != 'VOID'
                if ( strtoupper($cr_eval) !== "_EVAL_VOID" ) {
                    //On récupère l'évaluation contraire de celle précédement donnée par l'utilisateur.
                    $lba_evtype = $this->Acquiere_EvtTypeId_From_EvalFE(strtoupper($cr_eval), TRUE);
                    if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $evtype) ) {
                        return $lba_evtype;
                    }
                    $lba_evltpid = $this->Acquiere_EvalTypeId_From_EvalFE("_EVAL_VOID");  
                    
                    //On crée l'évènement adéquat qui permettra le retrait de point(s)
                    $rr = $this->CreateEvent($args["actor"], $lba_evtype, 1);
                    if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $rr) ) {
                        return $rr;
                    } 
                    
                    $LBA_EVT = $rr["pdevtid"];
                    $alter_args = [
                        "old_tbevlid"   => $EV_E["tbevlid"],
                        "end_evtid"     => $LBA_EVT
                    ];
                    
                    //On met fin à l'ancienne EVAL
                    $this->on_alter_entity($alter_args);        
                            
                    //** On crée un nouvel EVAL VOID **//
                    //On prépare le tableau
                    $neval_args = [
                        "artid"         => $A_E["artid"],
                        "art_eid"       => $A_E["art_eid"],
                        "evltpid"       => $lba_evltpid,
                        "start_evtid"   => $LBA_EVT,
                        "art_accid"     => $A_E["art_accid"],
                        "eval_is_ab"    => 1
                    ];
                    //On write
                    $evid = $this->write_new_in_database($neval_args);
                    if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $evid) ) {
                        return $evid;
                    }

                    //On modifie l'identifiant qui était supposé être celui de l'ancienne EVAL. On va ensuite pouvoir la modifier et créer une nouvel EVAL
                    $EV_E["tbevlid"] = $evid;
                }
                
            }
            
            //On crée l'évènement adéquat résultant de l'action de l'utilisateur
            $rr = $this->CreateEvent($args["actor"], $evtype, 0);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $rr) ) {
                return $rr;
            } 
            
            $start_evtid = $rr["pdevtid"];
            
            $alter_args = [
                "old_tbevlid"   => $EV_E["tbevlid"],
                "end_evtid"     => $start_evtid,
            ];
            
            //On envoie les données à onalter pour effectuer la modification et mettre fin au faux EVAL
            $this->on_alter_entity($alter_args);
            
        } else {
            /*
             * On choisi le bon "eventype" en fonction de ce qui est passé en paramètre.
             * Ensuite, on crée l'évènement de début.
             */
                
            $evtype = $this->Acquiere_EvtTypeId_From_EvalFE(strtoupper($args["eval_code"]));
            $evltpid = $this->Acquiere_EvalTypeId_From_EvalFE(strtoupper($args["eval_code"]));    
                
            //On crée l'évènement adéquat résultant de l'action de l'utilisateur
            $rr = $this->CreateEvent($args["actor"], $evtype, 0);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $rr) ) {
                return $rr;
            } 
            
            $start_evtid = $rr["pdevtid"];
            
        }
        
        $neval_args = [
            "artid"         => $A_E["artid"],
            "art_eid"       => $A_E["art_eid"],
            "evltpid"       => $evltpid,
            "start_evtid"   => $start_evtid,
            "art_accid"     => $A_E["art_accid"],
            "eval_is_ab"    => 0
        ];
        
        $id = $this->write_new_in_database($neval_args);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $id) ) {
            return $id;
        } else if ( $id ) {
            //On load la classe
            return $this->load_entity($id);
        } else {
            return "__ERR_VOL_UXPTD";
        }
        
    }

    protected function on_delete_entity($args) { }

    protected function on_read_entity($args) { }

    protected function write_new_in_database($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        /* RAPPEL
        "actor" => $args["actor"],
        "evltpid" => $evltpid,
        "start_evtid" => $start_evtid
        "art_accid" => ...
        "eval_is_ab" => 0|1
         
        //*/
        
        //On créé la ligne dans TableEvaluation
        $QO = new QUERY("qryl4evaln3");
        $params = array(":artid" => $args["artid"], ":evltpid" => $args["evltpid"], ":start_evtid" => $args["start_evtid"], ":eval_is_ab" => $args["eval_is_ab"]);
        $id = $QO->execute($params);
        
        //On met en place l'Operation de Capital
        $CO = new CAP_OPER();
        $new_co_args = [
            "oper_evtid" => $args["start_evtid"],
            //Le propriétaire de l'Article
            "oper_recept" => $args["art_accid"]
        ];
        
        $r = $CO->on_create_entity($new_co_args);
        
        $ART = new ARTICLE();
        $ART->onalter_selfupdate_vm($args["art_eid"]);
        
        if ( $r && is_array($r) && count($r) ) {
            return $id;
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r) ) {
            return $r;
        } else {
            return "__ERR_VOL_UXPTD";
        }
        
    }
    
    /***************************************************************************************************************************************************/
    /***************************************************************************************************************************************************/
    /************************************************************ SPECEFICS SCOPE ***********************************************************************/
    
    /***********************************************************************************************************************************************/
    /************************************************************** ONREAD (DEBUT) *****************************************************************/
//    ... pour toutes les methodes onread verifie account_gone
    public function onread_count_eval_bytype_byart ($art_eid, $evltp) {
        //RAPPEL : evltp représente le CODE du type d'évaluation : _EVAL_SPCL, _EVAL_CL, _EVAL_DLK
        /*
         *  Combien d'Evaluation d'un type donné pour un Article
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        //On vérifie si evaltp est valide
        if (! in_array(strtoupper($evltp), array_keys($this->_EVAL_TYPES)) ) {
            return "__ERR_VOL_RULES_MISMATCH";
        }
        
        //On vérifie si l'Article existe
        $AR = new ARTICLE();
        $A_E = $AR->exists($art_eid);
        if (! $A_E ) {
            return "__ERR_VOL_ART_GONE";
        }
        
        $QO = new QUERY("qryl4evaln6_wtdlo");
//        $QO = new QUERY("qryl4evaln6"); //[DEPUIS 11-09-15] @author BOR
        $params = array( ":artid" => $A_E["artid"], ":evltpid" => $this->_EVAL_TYPES[strtoupper($evltp)] );
        $datas = $QO->execute($params);
        
        if (! $datas ) {
            return FALSE; //Il y a eu un problème. En effet, même si on ne trouve rien, on est censé recevoir 0
        } else {
            return $datas[0]["evltp_nb"];
        }
        
    }
    
    public function onread_count_evaltot ($art_eid) {
        //RAPPEL : evltp représente le CODE du type d'évaluation : _EVAL_SPCL, _EVAL_CL, _EVAL_DLK
        /*
         *  Combien d'Evaluations au total pour un Article
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie si l'Article existe
        $AR = new ARTICLE();
        $A_E = $AR->exists($art_eid);
        
        if (! $A_E ) {
            return "__ERR_VOL_ART_GONE";
        }
        
        $QO = new QUERY("qryl4evaln5_wtdlo");
//        $QO = new QUERY("qryl4evaln5"); //[DEPUIS 11-09-15] @author BOR
        $params = array( ":artid" => $A_E["artid"] );
        $evt_datas = $QO->execute($params);
        
        if (! $evt_datas ) {
            return FAlSE; //Il y a eu un problème. En effet, même si on ne trouve rien, on est censé recevoir 0
        } else {
            return $evt_datas[0]["eval_tot"];
        }
    }


    public function onread_eval_article_value ($art_eid) {
        /*
         * Calculer la "valeur" d'un Article. 
         * Cette "valeur" est calculée en prennant en compte les opérations d'EVAL réalisées sur l'ARTICLE
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie si l'Article existe
        $AR = new ARTICLE();
        $A_E = $AR->exists($art_eid);
        
        if (! $A_E ) {
            return "__ERR_VOL_ART_GONE";
        }
        
        $QO = new QUERY("qryl4evaln7_wtdlo");
//        $QO = new QUERY("qryl4evaln7"); //[DEPUIS 11-09-15] @author BOR
        $params = array( ":artid" => $A_E["artid"] );
        $evt_datas = $QO->execute($params);
        
        if (! $evt_datas ) {
            return FAlSE; //Il y a eu un problème. En effet, même si on ne trouve rien, on est censé recevoir 0
        } else {
            return $evt_datas[0]["art_evalval"];
        }
    }
    
    public function onread_acquiere_vips ($art_eid, $cityid = NULL, $cn_id = NULL, $cuid = NULL) {
        /*
         * Permet d'obtenir une liste de trois Compte dit VIP qui ont Evalué l'Article. 
         * Ces compte sont obtenus en fonction de liens possible avec l'utilisateu actif.
         * 
         * [VBETA1] :
         * On se base sur les des informations d'ordres géographiques et linguistiques.
         * Les critères sont :
         *  -> Sélectionner les comptes qui partagent avec CU la même ville et la même langue
         *  -> Sélectionner les comptes qui partagent avec CU le même pays et la même langue
         *  -> Sélectionner les comptes qui partagent avec CU la même ville (implcitement pas la même langue)
         *  -> Sélectionner les comptes qui partagent avec CU le même pays (implcitement pas la même langue)
         *  -> Sélectionner les comptes qui partagent avec CU la même langue (quelque soit le lieu géographique).
         * 
         * Si CALLER ne fourni pas CUID
         *  -> Sélectionne les 3 premiers comptes qui sont géographiquement situés dans la même ville que le visiteur Actif
         *  -> Sélectionne les 3 premiers comptes qui sont géographiquement situés dans le même pays que le visiteur Actif
         *  -> Sélectionne les 3 premiers comptes qui ont évalué l'Article (S'il n'y a aucune correspondance géographique)
         * 
         * [AMELIORATIONS] 
         *  (1) Prendre en compte les données sur les Relations directes
         *  (2) Prendre en compte les données sur les Relations en commun
         *  (3) Prendre en compte les données sur l'age
         *  (4) Prendre en compte les données sur le sexe
         * 
         * 
         * RETOUR
         *  -> 0 : S'il n'y a aucune EVAL
         *  -> -1 : Si le nombre d'EVAL est inferieur à 3
         *  -> Array : Un tableau contenant les données sur les utilisateurs sélectionnés pour être des VIP
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $art_eid);
        
        //On vérifie si l'Article existe
        $AR = new ARTICLE();
        $A_E = $AR->exists($art_eid);
        
        if (! $A_E ) {
            return "__ERR_VOL_ART_GONE";
        }
        
        //On vérifie si CALLER a fourni CUID
        if ( isset($cuid) && !empty($cuid) ) {
            //On vérifie si le compte existe et on récupère ses données 
            $PA = new PROD_ACC();
            $PA_E = $PA->exists_with_id($cuid);
            
            if (! $PA_E ) {
                return "__ERR_VOL_CU_GONE";
            } 
            /*
             * [NOTE 16-09-14] @author L.C.
             * J'ai exclu le propriétaire de l'Article. 
             * En effet, ne pas l'enlever dans ce contexte fera qu'il apparattra plutot qu'une autre personne.
             * Ca gache une place. Encore plus si c'est lui qui est connecté. Il préférerait de loin voir le profil de quelqu'un d'autre.
             */
            
            $QO = new QUERY("qryl4evaln8_wtdlo");
//            $QO = new QUERY("qryl4evaln8"); //[DEPUIS 11-09-15] @author BOR
            $params = array(
                ":owner"    => $cuid, ":artid" => $A_E["artid"],
                ":udl1"     => $PA_E["pdacc_udl"], ":udl2" => $PA_E["pdacc_udl"], ":udl3" => $PA_E["pdacc_udl"], ":udl4" => $PA_E["pdacc_udl"], ":udl5" => $PA_E["pdacc_udl"], ":udl6" => $PA_E["pdacc_udl"], 
                ":ucityid1" => $PA_E["pdacc_ucityid"], ":ucityid2" => $PA_E["pdacc_ucityid"], ":ucityid3" => $PA_E["pdacc_ucityid"], ":ucityid4" => $PA_E["pdacc_ucityid"],
                ":ucnid1"   => $PA_E["pdacc_ucnid"], ":ucnid2" => $PA_E["pdacc_ucnid"], ":ucnid3" => $PA_E["pdacc_ucnid"], ":ucnid4" => $PA_E["pdacc_ucnid"]);
            $datas = $QO->execute($params);
            
            if (! $datas ) {
                return 0;
            } else if ( $datas && is_array($datas) && count($datas) >= 3 ) {
                return $datas;
            } else {
                return -1;
            }
        } else if ( $cityid && $cn_id ) {
            $QO = new QUERY("qryl4evaln9_wtdlo");
//            $QO = new QUERY("qryl4evaln9"); //[DEPUIS 11-09-15] @author BOR
            $params = array(
                ":artid"    => $A_E["artid"], 
                ":ucityid1" => $cityid, 
                ":ucnid1"   => $cn_id, 
                ":ucityid2" => $cityid, 
                ":ucnid2"   => $cn_id
            );
            $datas = $QO->execute($params);
            
            if (! $datas ) {
                return 0;
            } else if ( $datas && is_array($datas) && count($datas) >= 3 ) {
                return $datas;
            } else {
                return -1;
            }
        } else {
            //On récupère les 3 Comptes qui ont en dernier évalué l'Article passé en paramètre
            $QO = new QUERY("qryl4evaln10_wtdlo");
//            $QO = new QUERY("qryl4evaln10"); //[DEPUIS 11-09-15] @author BOR
            $params = array(":artid" => $A_E["artid"]);
            $datas = $QO->execute($params);
            
            if (! $datas ) {
                return 0;
            } else if ( $datas && is_array($datas) && count($datas) >= 3 ) {
                return $datas;
            } else {
                return -1;
            }
        }
    } 
    
    public function onread_srvcode_to_fecode ($evltp) {
        /*
         * Permet de trouver une équivalence entre le code tel qu'il est connu au niveau du serveur et celui au niveau du FE.
         * L'histoire des codes différents vient du fait que les deux couches n'ont pas été faites au même moment. Leur protocole se retrouvent donc différents.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
       
        if (! in_array(strtoupper($evltp), array_keys($this->_EVAL_TYPES)) ) {
            return "__ERR_VOL_RULES_MISMATCH";
        }
        
        $me = "";
        
        switch (strtoupper($evltp)) {
            case "_EVAL_SPCL":
                    $me = "p2";
                break;
            case "_EVAL_CL":
                    $me = "p1";
                break;
            case "_EVAL_DLK":
                    $me = "m1";
                break;
            case "_EVAL_VOID":
                    $me = "vd"; //[NOTE 06-09-14] Non utiliser au niveau du FE pour l'heure !!
                break;
            default:
                return "__ERR_VOL_UXPTD";
        }
        
        return $me;
    }
    
    public function getUserMyEval ($uid,$aid) {
        //On détemine l'évaluation du Compte courant
        $datas = $this->exists(["actor" => $uid,"artid" => $aid]);
        $me = ( is_array($datas) && $datas["evtype_fe"] !== strtoupper("_EVAL_VOID") ) 
            ? $this->onread_srvcode_to_fecode($datas["evtype_fe"]) 
            :  NULL;
        
        return $me;
    }

    /************************************************************** ONREAD (FIN) *****************************************************************/
    /*********************************************************************************************************************************************/ 
    
    private function Acquiere_EvtTypeId_From_EvalFE ($EVALTY_FE, $is_downgrade = FALSE) {
        /*
         * Permet d'obtenir une donnée "eventype" à partir d'un code EVAL_TYPE de type FE
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $evtype = NULL;
        switch (strtoupper($EVALTY_FE)) {
            case "_EVAL_SPCL":
                    $evtype = (! $is_downgrade ) ? $this->_EVAL_EVT_TYPES["eval_spcl"] : $this->_EVAL_EVT_TYPES["ueval_spcl"];
                break;
            case "_EVAL_CL":
                    $evtype = (! $is_downgrade ) ? $this->_EVAL_EVT_TYPES["eval_cl"] : $this->_EVAL_EVT_TYPES["ueval_cl"];
                break;
            case "_EVAL_DLK":
                    $evtype = (! $is_downgrade ) ? $this->_EVAL_EVT_TYPES["eval_dlk"] : $this->_EVAL_EVT_TYPES["ueval_dlk"];
                break;
            default:
                    return "__ERR_VOL_EVALTYPE_UKNW";
                break;
        }
        
        return $evtype;
    }
    
    private function Acquiere_EvalTypeId_From_EvalFE ($EVALTY_FE) {
        /*
         * Permet d'obtenir une donnée "EvalType" à partir d'un code EVAL_TYPE de type FE
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $evaltpid = NULL;
        switch (strtoupper($EVALTY_FE)) {
            case "_EVAL_SPCL" : 
                    $evaltpid = $this->_EVAL_TYPES["_EVAL_SPCL"];
                break;
            case "_EVAL_CL" :
                    $evaltpid = $this->_EVAL_TYPES["_EVAL_CL"];
                break;
            case "_EVAL_DLK" :
                    $evaltpid = $this->_EVAL_TYPES["_EVAL_DLK"];
                break;
            case "_EVAL_VOID" :
                    $evaltpid = $this->_EVAL_TYPES["_EVAL_VOID"];
                break;
            default:
                    return "__ERR_VOL_EVALTYPE_UKNW";
                break;
        }
        
        return $evaltpid;
    }
    
    private function CreateEvent ($actor, $evtype, $ev_is_ab = NULL) {
        /*
         * Permet de créer un évènement. 
         * Si l'opération aboutie, en revoie la table de l'évènement.
         */
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$actor, $evtype]);
        
        $ev_is_ab = ( isset($ev_is_ab) ) ? 1 : 0;
        
        $EVT = new PROD_EVENT();
        
        $nevt_args = [
            "actor" => $actor,
            "evtype" => $evtype,
            "ev_is_ab" => $ev_is_ab
        ];
        
        $rr = $EVT->on_create_entity($nevt_args);
                    
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $rr) ) {
            return $rr;
        } else if ( $rr && is_array($rr) && count($rr) ) {
            return $rr;
        } else {
            return "__ERR_VOL_UXPTD";
        }
        
    }
    
    /***************************************************************************************************************************************************/
    /***************************************************************************************************************************************************/
    /************************************************************ GETTERS and SETTERS ******************************************************************/
    // <editor-fold defaultstate="collapsed" desc="GETTERS and SETTERS">

    public function getTbevlid() {
        return $this->tbevlid;
    }

    public function getTbevl_artid() {
        return $this->tbevl_artid;
    }

    public function getTbevl_art_eid() {
        return $this->tbevl_art_eid;
    }

    public function getTbevl_evltpid() {
        return $this->tbevl_evltpid;
    }

    public function getEval_code() {
        return $this->eval_code;
    }

    public function getEval_code_fe() {
        return $this->eval_code_fe;
    }

    public function getEval_lib() {
        return $this->eval_lib;
    }

    public function getTbevl_start_evt() {
        return $this->tbevl_start_evt;
    }

    public function getStart_evt_table() {
        return $this->start_evt_table;
    }

    public function getTbevl_end_evt() {
        return $this->tbevl_end_evt;
    }

    public function getEnd_evt_table() {
        return $this->end_evt_table;
    }

    public function get_EVAL_EVT_TYPES() {
        return $this->_EVAL_EVT_TYPES;
    }

    public function get_EVAL_TYPES() {
        return $this->_EVAL_TYPES;
    }

// </editor-fold>

}