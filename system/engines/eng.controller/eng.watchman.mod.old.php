<?php
//Attention : contrairement au systeme rdcs, ici le watchman est partie intégrante du controller
Class CONTROLLER_WATCHMAN extends MOTHER
{
    private $run_lang;
    private $curr_wto;
    private $prev_wto;
    private $prod_xmlscope;
    private $urq_xmlscope;
    private $snitch_infos_array;
    
    private $visitor_type;
    private $is_visitor_rest;
    private $is_visitor_admin;
    
    private $wm_infos_array;
            
    function __construct($snitch_infos_array, $running_lang, $curr_WTO, $prev_WTO, $prod_xmlscope, $urqXmlScopeIntoArray) {
        /**
         * OH TOI MORTEL ! Qui vient ici pour DEGUB ou REFACTORISER ce code
         * Prepare toi des cachets d'Aspirine car tu vas en BAVER !
         */
        
        //We ensure that until the mother this class is innit.  
        parent::__construct(__FILE__, __CLASS__);
        
        //$this->check_isset_entry_vars(__FUNCTION__,__LINE__,func_get_args());
        $this->is_visitor_rest = FALSE;
        $this->is_visitor_admin = FALSE;
        $this->run_lang = $running_lang;
        $this->curr_wto = $curr_WTO;
        //On rend à prev_WTO null s'il etait à FALSE
        $this->prev_wto = ( $prev_WTO === FALSE ) ? NULL : $prev_WTO;
        $this->prod_xmlscope = $prod_xmlscope;
        $this->urq_xmlscope = $urqXmlScopeIntoArray;
        $this->snitch_infos_array = $snitch_infos_array;
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->urq_xmlscope);
        
        $this->init_security_control();
    }
    
    
    public function init_security_control() {
        //On a choisit de terminer par la fouille
        //1)WHO : Qui est le visitor : fantome? anonyme? ou actor ? TYPE
        $this->visitor_type = $this->identify_visitor();
        
        //$this->visitor_type = AG_RACC; //FOR TEST ONLY
        //$this->is_visitor_rest = TRUE; //FOR TEST ONLY
        //$this->is_visitor_admin = TRUE; //FOR TEST ONLY
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->visitor_type,'v_d');
        /**
         * [NOTE au 07/11/2013] : Pas besion de déclencher une erreur en mode PROD.
         * Il faut le faire cependant en mode DEBUG
         */
        if ( !isset($this->visitor_type) ) {
            if ( defined("RIGHT_IS_DEBUG") and RIGHT_IS_DEBUG === TRUE ) 
                $this->signalError("err_sys_l331",__FUNCTION__, __LINE__);
            else
                $this->visitor_type = AG_WGHOST;
        }
        
        //2)WHERE : D'ou vient-il ? REFERER, COUNTRY (pas country car SNITCHER a du s'en occupé)
        //Au [24-09-2013] La fonction est vide car on a décidé d'aucune interdiction de provenance se basant sur un REFERER
        $this->handle_origin_case($this->urq_xmlscope); 
        //3)WHAT : Que veut-il ? URQ, PAGE
        $this->handle_needs_case ($this->urq_xmlscope);
        //4)HOW : Comment veut-il ses données (LANG, Verifier les incoherences au sujet de lang url)
        $this->handle_lang_contradiction($this->run_lang, $this->curr_wto);
       
        //5)DATACONTROL
        
        //6)FillUp wm_infos_array
        $this->remplir_wm_infos_array();
        //7) Renvoyer CURRENT_WTO
        return $this->curr_wto;
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $this);
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }

    
    private function identify_visitor() {
        $SS = new SESSION_TO();
        $ss_not_void = PCC_SESSION::doesSessionExistAndIsNotVoid();
        if ( $ss_not_void and key_exists("rsto_infos",$_SESSION) ) {
            //Ce qui veut dire que l'utilisateur s'est déja connecté sur le produit au moins une fois.
            //Qu'il n'a pas fermer son navigateur
            //Qu'il est soit connecté ou il ne l'est pas !
            //NOTE : Meme s'il n'est pas connecté le fait qu'il est voulu une connexion permanente est considéré comme une connexion
            $RSTO = new RSTO_INFOS();
            $RSTO = $_SESSION["rsto_infos"]; //Sorte de cast
            
            
            if ( $RSTO->getIs_connected() or (!$RSTO->getIs_connected() and $RSTO->getStay_connect()) ) {
                $this->is_visitor_rest = TRUE;
                //Si la url_param a été demandé c'est qu'il s'agit d'une question de propriété. 
                //Aussi, si USER ne correspond pas il ne faut pas lui accorder tous les droits
                $user_given_user = $this->wto->getUser();
                if ( $RSTO->getIs_an_admin_acc()) {
                    //Si l'USER est admin on ne cherche pas plus loin
                    $this->is_visitor_admin = TRUE;
                    return AG_RADMIN;
                } else if ( isset($user_given_user) and $user_given_user != "" and $RSTO->getPseudo() == strtolower($user_given_user) ) {
                    //Ce qui veut dire que l'utilisateur veut aller sur une page qui lui appartient exclusivement
                    return  AG_RACCONOWN;
                } else {
                    //Ce qui signifie que l'utilisateur est accéder à toutes les pages de l'environnement REST PROD
                    //Mais pas ceux lui appartenant (il n'a pas recquis ce droit) ou appartenant à un utilisateur
                    return AG_RACC;
                }               
            } else if ( !$RSTO->getIs_connected() and !$RSTO->getStay_connect() ) {
                return AG_WIKU;
            }
        } else if ( $ss_not_void and key_exists("sto_infos",$_SESSION) ) {
            //L'utilisateur ne s'est jamais connecté pour accéder au mode REST
            return AG_WANON;
        } else {
            //C'est la premier visite du vsiteur OU pour x raisons le fichier SESSION n'existe pas
            return AG_WGHOST;
        }
    }
    
    
    private function handle_origin_case ($entry_urq_xmlscope) {
        //TODO 
        //Si on ne veut pas qu'un user viennent d'un SITE WEB EN PARTICULIER on peut le bloquer
    }
    
    
    //Pour l'instant le code répond à un cas précis. Des évolutions permettront de le rendre générique
    private function handle_needs_case ($entry_urq_xmlscope) {
        $autorise_car_admin = $this->handle_denied_access_urq($entry_urq_xmlscope["urq_access_rules"]);
        
        if($autorise_car_admin) {
            //Si c'est Admin on SKIP il a tous les droits dans ce cas : "Celui où tout le monde ne passe pas."
            //Après on pourrait se demander pk le webmasteur n'a juste pas spécifier qu'il ne voulait que admin
        } else {
            //Le but ce n'est pas de dire il passe parce qu'il est REST mais plutot le contraire.
            //On n'aimerait pas que certaines pages soient accessibles lorsqu'on est déjà connecté
            //$this->handle_special_rest_visitor_case ($entry_urq_xmlscope["urq_in_case_of_rest"], $this->is_visitor_rest);

            //Maintenant on va décider si User a le droit de passer en fonction de s'il est connecté ou pas
            //Et d'où il va. Est ce qu'il reste dans son univers (REST, WELC) ou il veut changer

            /**
             * RAPPEL : Dans le cas où 1e cette rule
             * 1- REST -> REST : Verifier avec les paramètres propres de URQ SINON _stagne
             * 2- REST -> WELC : Verifier avec les paramètres SINON _stagne
             * 3- WELC -> REST : REDIR vers conex puis tout refaire
             * 4- WELC -> WELC : Verifier avec les paramètres propres de URQ SINON _404
             * 
             * RAPPEL : Dans le cas où plusieurs règles, ne match pas = ne match pas
             * Ces données peuvent changer en fonction du bon vouloir du Webmaster. Pour l'instant la règle se fait en dur mais ensuite elle se fera via un fichier CONF
             */
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $_SESSION);
            $autorise = $this->l_utilisateur_est_il_legitime_dans_sa_requete($this->visitor_type, $this->urq_xmlscope["urq_access_rules"]);
            
            if (!$autorise)  { //S'il n'est pas autorisé on étudie son cas un peu plus minutieusement 
                $list_ag = $this->urq_xmlscope["urq_access_rules"];
                //En gros il y'en a pas plus d'1
                if ( count($list_ag) > 1 ) {
                    if ( (in_array("ag_allaccess", $list_ag)) ) return EXC_GO_ON;
                    else if ( (in_array("rall", $list_ag)) and $this->is_visitor_rest ) return EXC_GO_ON;
                    else if ( (in_array("wall", $list_ag)) and !$this->is_visitor_rest ) return EXC_GO_ON;
                    else $this->signalError("err_sys_l328",__FUNCTION__, __LINE__);
                    //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $_SESSION);
                } else if ( count($list_ag) == 1 ) {
                    if ( $this->does_rule_is_a_rest_one($this->urq_xmlscope["urq_access_rules"]) and !$this->is_visitor_rest ) {
                        //REDIR vers CONX et on ressait
                        $this->signalErrorWithoutErrIdButGivenMsg ("<p style\"color:red;\">FOR DEBUG or TEST : We whould REDIR</p>", __FUNCTION__, __LINE__) ;
                    } else if ( !$this->does_rule_is_a_rest_one($this->urq_xmlscope["urq_access_rules"]) and $this->is_visitor_rest ) {
                        //Si l'user est connecté et qu'il veut aller vers WELC, on stagne
                        //On le fait pour éviter de trop afficher d'erreurs et qu'il pense que la plateforme bug !
                        $this->signalErrorWithoutErrIdButGivenMsg ("<p style\"color:red;\">FOR DEBUG or TEST : On STAGNE car USER est restricted !</p>", __FUNCTION__, __LINE__) ;
                        exit;
                    } //SI  les deux execptions ne sont pas remplies, bah on réessait de voir pour les all_access sinon on affiche une erreur
                    else if ( (in_array("ag_allaccess", $list_ag)) ) return EXC_GO_ON;
                    else if ( (in_array("rall", $list_ag)) and $this->is_visitor_rest ) return EXC_GO_ON;
                    else if ( (in_array("wall", $list_ag)) and !$this->is_visitor_rest ) return EXC_GO_ON;
                    else $this->signalError("err_sys_l328",__FUNCTION__, __LINE__);
                } else $this->signalError("err_sys_l328",__FUNCTION__, __LINE__);
            }
        }
    }
    
    
    private function handle_denied_access_urq($entry_array) {
        foreach ($entry_array as $value) {
            if ($value == "ag_denied" and $this->is_visitor_admin != TRUE) $this->signalError("err_sys_l332",__FUNCTION__, __LINE__);
            else if ($value == "ag_denied" and $this->is_visitor_admin == TRUE) return TRUE;
        }
    }


    private function handle_special_rest_visitor_case ($entry_reference, $is_v_rest) {
        if ( $is_v_rest == TRUE and ($entry_reference != EXC_GO_ON) ) $this->signalErrorWithoutErrIdButGivenMsg ("<p style\"color:red;\">FOR DEBUG or TEST : We whould REDIR</p>", __FUNCTION__, __LINE__) ;
    }
    
    
    private function does_rule_is_a_rest_one($entry_access_rule) {
        $list_of_rest_ag_code = array("rtopadmin", "radmin", "rtester", "racc", "racconown", "racconown_sr1", "rall");
        foreach ($entry_access_rule as $rule) {
            if ( in_array($rule, $list_of_rest_ag_code) ) {
                return TRUE;
                break; //On ne sait jamais
            }
        }
        return FALSE;
    }
    
    
    private function handle_lang_contradiction ($entry_r_lang, $entry_wto) {
        /**
         * On va verifier qu'il n'y a pas de contracdiction de langue
         * Par exemple que si running_lang est Fr et que l'url est EN
         * En cas de contradiction, au [23-09-13] :
         * Cas 1 : Utilisateur non connecté qui ne demande (logiquement) pas une page REST
         * Solution 1 : Lui fournir la page dans la langue de l'url si le prev_wto 
         * Raison : On va favoriser le referencement de la page en url_lang et eventuellement de run_lang car toute nouvelle action entrainera un changement de langue
         * (Suite) De plus, c'est comme un avant gout. Si l'user souhaite rester sur l'ancienne langue, il n'aura qu'à chercher et trouver l'endroit pour changer de langue.
         * Solution 2 : Lui fournir la page dans la langue de running_lang si le prev_wto existe (option : le prevenir qu'il peut à tout moment changer la langue s'il veut obtenir l'article dans la bonne langue)
         * Cas 2 : Utilisateur connecté qui demande une page quelque soit l'environnement
         * Solution : Lui fournir la page en fonction de running_lang 
         */
        
        //NOTE au [24-09-13] : On abondonne la piste du REFERER car il n'est pas sur. On va se fier à ce que NOUS faisons en interne
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry_running_lang,'v_d');
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry_wto,'v_d');
        if ( $entry_r_lang !=  $entry_wto->getUserSuppliedPageLang() ) {
            
            if ($this->is_visitor_rest) {
                //Laisser le running lang
                $this->curr_wto->setWatchman_decision_on_lang($entry_r_lang);
            } else {
                //On regarde si prev_WTO exists
                //Si Session, on regarde dans SESSION
                $STO = new SESSION_TO();
                $session_is_ready_to_be_used = PCC_SESSION::doesSessionExistAndIsNotvoid();
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION["sto"],'v_d');              
                if(! $session_is_ready_to_be_used) {      
                    //On laisse la langue choisie. CAD "url_lang"
                    //Pour ce faire, on va changer watchman_decision_on_lang dans curr_WTO et mettre run_lang
                    $this->curr_wto->setWatchman_decision_on_lang($entry_wto->getUserSuppliedPageLang());
                    
                    //run_lang ne sera desormais plus utilisé dans la suite du code mais bel et bien la décision de WM
                } else  {
                    if ( key_exists("sto_infos",$_SESSION) and count($_SESSION["sto_infos"]) ) {
                        //debug_print_backtrace();
                        //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->prev_wto,'v_d');          
                        if (! isset($this->prev_wto) ) {
                            //Ici le cas le plus logique est celui où l'user est arrivée sur la platforme et n'a jamais cliqué sur un lien ou btn qui  déclechée une urq avec urqid != 
                            $url_lang = $entry_wto->getUserSuppliedPageLang();
                            $this->curr_wto->setWatchman_decision_on_lang($url_lang); //En gros on garde 'url_lang'
                        } else {
                            
                            $this->curr_wto->setWatchman_decision_on_lang($entry_r_lang);
                        }
                    } else $this->signalError ("err_sys_l014", __FUNCTION__, __LINE__);
                }
            }
        } else $this->curr_wto->setWatchman_decision_on_lang($entry_r_lang);
    }
    
    
    private function l_utilisateur_est_il_legitime_dans_sa_requete ($entry_vtype, $entry_list_of_rules) {
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry_list_of_rules);
        //Le visiteur est il autorisé, son type correspond t-il ?
        if (!in_array($entry_vtype, $entry_list_of_rules)) {
            //$this->signalError("err_sys_l328",__FUNCTION__, __LINE__);
            return FALSE;
        } else return TRUE;
    }
    
    
    private function remplir_wm_infos_array() {
        $this->wm_infos_array["v_type"] = $this->visitor_type;
        $this->wm_infos_array["is_v_rest"] = $this->is_visitor_rest;
        $this->wm_infos_array["is_v_admin"] = $this->is_visitor_admin;
    }
    
    /******************************************************************************************************************/
    /********************************************* START GETTERS AND SETTERS ******************************************/
    // <editor-fold defaultstate="collapsed" desc="GETTERS and SETTERS">
    public function getVisitor_type() {
        return $this->visitor_type;
    }

    public function getIs_visitor_rest() {
        return $this->is_visitor_rest;
    }

    public function getIs_visitor_admin() {
        return $this->is_visitor_admin;
    }

    public function getWm_infos_array() {
        return $this->wm_infos_array;
    }
        
    public function getCurr_wto() {
        return $this->curr_wto;
    }

    // </editor-fold>

}
?>
