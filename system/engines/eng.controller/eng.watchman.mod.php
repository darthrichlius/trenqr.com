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
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->urq_xmlscope);
        
        $this->init_security_control();

    }
    
    
    public function init_security_control() {
        //On a choisit de terminer par la fouille
        //1)WHO : Qui est le visitor : fantome? anonyme? ou actor ? TYPE
        $this->visitor_type = $this->identify_visitor();
        
//        $this->visitor_type = AG_WANON; //FOR TEST ONLY
        //$this->is_visitor_rest = TRUE; //FOR TEST ONLY
        //$this->is_visitor_admin = TRUE; //FOR TEST ONLY
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->visitor_type,'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        exit();
        /**
         * [NOTE au 07/11/2013] : Pas besion de déclencher une erreur en mode PROD.
         * Il faut le faire cependant en mode DEBUG
         */
        if ( !isset($this->visitor_type) ) {
            if ( defined("RIGHT_IS_DEBUG") and RIGHT_IS_DEBUG === TRUE ) {
                $this->signalError("err_sys_l331",__FUNCTION__, __LINE__);
            } else {
                $this->visitor_type = AG_WGHOST;
            }
        }
        
        //2)WHERE : D'ou vient-il ? REFERER, COUNTRY (pas country car SNITCHER a du s'en occuper)
        //Au [24-09-2013] La fonction est vide car on a décidé d'aucune interdiction de provenance se basant sur un REFERER
        $this->handle_origin_case($this->urq_xmlscope, $this->prod_xmlscope); 
        //3)WHAT : Que veut-il ? (URQ, PAGE) Est-il autorisé à l'avoir ?
        $this->handle_needs_case ($this->urq_xmlscope);
        //4)HOW : Comment veut-il ses données (LANG, Verifier les incoherences au sujet de lang url)
        $this->handle_lang_contradiction($this->run_lang, $this->curr_wto);
        //TREAT SPECIAL CASES
        $this->treat_special_urq_cases($this->urq_xmlscope);
        //5)DATACONTROL
        $this->control_incoming_datas($this->urq_xmlscope);
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
        if ( $ss_not_void && key_exists("rsto_infos",$_SESSION) and !empty($_SESSION["rsto_infos"]) ) {
            //Ce qui veut dire que l'utilisateur s'est déja connecté sur le produit au moins une fois.
            //Qu'il n'a pas fermer son navigateur
            //Qu'il est soit connecté ou il ne l'est pas !
            //NOTE : Meme s'il n'est pas connecté le fait qu'il est voulu une connexion permanente est considéré comme une connexion
            $RSTO = new RSTO_INFOS();
            $RSTO = $_SESSION["rsto_infos"]; //Sorte de cast
            
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__, [$RSTO],'v_d');
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
            
            if ( $RSTO->getIs_connected() || (!$RSTO->getIs_connected() && $RSTO->getStay_connect()) ) {
                $this->is_visitor_rest = TRUE;
                //Si la url_param a été demandé c'est qu'il s'agit d'une question de propriété. 
                //Aussi, si USER ne correspond pas il ne faut pas lui accorder tous les droits
                
                /*
                 * [NOTE 26-08-2014] L.C.
                 * 
                 * L'utilisateur peut donner une pseudo sous la forme ' @[pseudo].
                 * Il faut donc le prendre en compte lors de la comparaison.
                 * 
                 * Mais avant nous allons nous assurer que le pseudo donné suit la règle sur les pseudos.
                 */
                $user_given_user = $this->curr_wto->getUser();
                
                if ( isset($user_given_user) && $user_given_user != "" ) {
                    $TX = new TEXTHANDLER();
                    
                    //On pense à retirer '@'
                    $user_given_user = $TX->genuine_pseudo_in_url($user_given_user);
                    
                    if (! $TX->valid_user_in_url($user_given_user) ) {
                        $this->signalError ("err_user_l5e404_user", __FUNCTION__, __LINE__);
                        //TODO : Créer une erreur qui mènera vers une page  d'erreur. On pourra rediriger vers une page 404
//                        $this->signalErrorWithoutErrIdButGivenMsg("USER_NO_FOUND", __FUNCTION__, __LINE__);
//                        exit();
                    } 
                    
                    if (! $user_given_user ) {
                        $this->signalError ("err_user_l5e404_user", __FUNCTION__, __LINE__);
                        //TODO : Créer une erreur qui mènera vers une page  d'erreur. On pourra rediriger vers une page 404
//                        $this->signalErrorWithoutErrIdButGivenMsg("USER_NO_FOUND", __FUNCTION__, __LINE__);
//                        exit();
                    }
                }
                
//                $this->presentVarIfDebug(__FUNCTION__,__LINE__, [$RSTO->getUpseudo(),strtolower($user_given_user)],'v_d');
//                $this->endExecutionIfDebug(__FUNCTION__,__LINE__); 
                
                if ( $RSTO->getIs_an_admin_acc()) {
                    //Si l'USER est admin on ne cherche pas plus loin
                    $this->is_visitor_admin = TRUE;
                    return AG_RADMIN;
                    
                } else if ( isset($user_given_user) && $user_given_user != "" && strtolower($RSTO->getUpseudo()) == strtolower($user_given_user) ) {
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
    
    
    private function handle_origin_case ($entry_urq_xmlscope, $entry_prod_xmlscope) {
        //TODO 
        //Si on ne veut pas qu'un user viennent d'un SITE WEB EN PARTICULIER on peut le bloquer (ou si on veut qu'il viennent d'un site en particulier)
        //Pour faire plus court
        $prod = $entry_prod_xmlscope;
        $urq = $entry_urq_xmlscope;

        /**
         * ZONE DE TRAITEMENT POUR INNER_REFERER
         */
        if ( $urq["urq_is_inner_refer_required"] == "yes" ) {
            $ref = $_SERVER["HTTP_REFERER"]; 
            
            if ( empty($ref) )
                $this->signalError("err_user_l333",__FUNCTION__, __LINE__);
            
            /**
             * RAPPEL sur l'architecture du tableau renvoyé par parse_url
             * scheme - e.g. http
             * host - e.g. www.deuslynn.fr
             * port
             * user
             * pass
             * path - e.g. /index.php
             * query - après le marqueur de question ?
             * fragment - après la hachure #
             */
            $r = parse_url($ref);
            
            if ( $r === FALSE or !isset($r) or !key_exists("host", $r) )
                $this->signalError("err_user_l333",__FUNCTION__, __LINE__);
            
            if (! in_array($r["host"], $prod["prod_pos_hosts"]) ) $this->signalError("err_user_l334",__FUNCTION__, __LINE__);
        }
    }
    
    
    //Pour l'instant le code répond à un cas précis. Des évolutions permettront de le rendre générique
    private function handle_needs_case ($entry_urq_xmlscope) {
                
        if( $this->is_visitor_admin ) {
            //Si c'est Admin on SKIP il a tous les droits
            //Après, on pourrait se demander pk le webmasteur n'a juste pas spécifier qu'il ne voulait que admin
            return EXC_GO_ON;
        } else {
            $this->handle_denied_access_urq($entry_urq_xmlscope["urq_access_rules"]);
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->visitor_type,'v_d');
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__); 
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
            
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->urq_xmlscope["urq_access_rules"],'v_d');
//            $this->endExecutionIfDebug(__FUNCTION__,__LINE__); 
            $autorise = $this->l_utilisateur_est_il_legitime_dans_sa_requete($this->visitor_type, $this->urq_xmlscope["urq_access_rules"]);
            
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__, [$autorise,$this->visitor_type,$_SESSION]);
//            exit();
            if (! $autorise ) { //S'il n'est pas autorisé on étudie son cas un peu plus minutieusement 
                
                $list_ag = $this->urq_xmlscope["urq_access_rules"];
                
                if ( count($list_ag) == 1 ) {
                    
                    //vir = Visitor Is Restricted
                    $vir = $this->is_visitor_rest;
                    //vt = Visitor Type
                    $vt = $this->visitor_type;
//                    $this->presentVarIfDebug(__FUNCTION__,__LINE__, [$vir,$vt,$list_ag],'v_d');
//                    $this->endExecutionIfDebug(__FUNCTION__,__LINE__); 
                    

                    /**
                     * A ce stade 'vt' est surement egal à une des valeurs : AG_RADMIN, AG_RACCONOWN, AG_RACC, AG_WIKU, AG_WANON, AG_WGHOST.
                     * Elles peuvent donc être éliminées.
                     * De plus, on peut aussi éliminer les valeurs de la serie DENIED
                     * Cependant, on peut d'ores et deja eliminer AG_RADMIN car si l'user etait identifié comme ADMIN on ne serait pas ici !
                     */
//                    var_dump($vt,$list_ag);
//                    exit();
                    //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $list_ag);
                    if (! key_exists(1, $list_ag) ){
                        $this->signalError("err_sys_l026",__FUNCTION__, __LINE__);
                    }
                    
                    $url;
;                    switch ($list_ag[1]) { //[NOTE au 01-12-13 : Comment se fait-il que le premier index soit 1 ? :o]
                        case AG_AGALLACCESS :
                                return EXC_GO_ON;
                            break;
                        case AG_WALL :
                                if ( !$vir )
                                    return EXC_GO_ON;
                                else {
                                    //[NOTE 26-08-14] L.C. On redirige vers la page de l'utilisateur connecté
                                    $R = new REDIR($this->prod_xmlscope);
                                    $url = $R->redir_to_default_page(DFTPAGE_PROD_REST_OWN);
                                    
                                    $TXH = new TEXTHANDLER();
                                    $url = $TXH->ReplaceDmd("pseudo", strtolower($_SESSION["rsto_infos"]->getUpseudo()), $url);
                                } 
                            break;
                        case AG_WALL_REST_SR1 :
                        case AG_WALL_REST_SR2 :
                                //RAPPEL : Watcman ne traite pas directement les cas de AG de type 'SR'
                                if ( !$vir )
                                    return EXC_GO_ON;
                                else {
                                    $R = new REDIR($this->prod_xmlscope);
                                    /*
                                     * [NOTE 26-11-14] @author L.C. 
                                     *      Retiré car il faut "pseudo"
                                     */
//                                    $R->redir_handle_redir_case(AG_WALL_REST_SR2, $vir);
                                    $url = $R->redir_to_default_page(DFTPAGE_PROD_REST);
                                    
                                    $TXH = new TEXTHANDLER();
                                    $url = $TXH->ReplaceDmd("pseudo", strtolower($_SESSION["rsto_infos"]->getUpseudo()), $url);
                                } 
                            break;
                        case AG_RALL :
                        case AG_RACC :
                        case AG_RACCONOWN :
                                //Pour l'instant on regroupe le tout. User sera redirigé vers la page de connexion et devra relancer la requete //[NOTE 26-08-14] Résolu
                                if ( $vir ) {
                                    //[NOTE 26-08-14] Ajouté par L.C.
                                    $R = new REDIR($this->prod_xmlscope);
                                    $url = $R->redir_to_default_page(DFTPAGE_PROD_REST_OWN);
                                    
                                    $TXH = new TEXTHANDLER();
                                    $url = $TXH->ReplaceDmd("pseudo", strtolower($_SESSION["rsto_infos"]->getUpseudo()), $url);
                                } else if ( $entry_urq_xmlscope["urqid"] === "LOGOUT" && $this->urq_xmlscope["urq_is_ajax"] !== "yes" ) { //[DEPUIS 02-07-16]
                                    $R = new REDIR($this->prod_xmlscope);
                                    $url = $R->redir_to_default_page(DFTPAGE_PROD_WEL);
                                } else {
                                    
                                    /*
                                     * [NOTE 11-12-14] @author L.C.
                                     *      On crontrole maintenant s'il s'agit d'une requete de type AJAX. Dans ce cas, on renvoie un 401.
                                     *      Cela une meilleur gestion du cas de "l'utilisateur non connecté" ou de "tentative d'accès à un contrnu Restricted"
                                     */
                                    if ( $this->urq_xmlscope["urq_is_ajax"] === "yes" ) {
                                         http_response_code(401);
                                         exit; //PARANO
                                    } else {
                                        $R = new REDIR($this->prod_xmlscope);
                                        $url = $R->redir_to_default_page(DFTPAGE_PROD_CONX);
                                        
                                        /*
                                         * ETAPE :
                                         *      On ajoute des données de personnalisation pour permettre à CNX_PAGE de REDIR en fonction de son algorithme
                                         */
                                        $curl = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
//                                        $curl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                        $curl = str_replace(".", "%2E", $curl);
                                        $curl = str_replace("=", "%3D", $curl);
                                        $curl = urlencode($curl);
                                        
                                        $redir_affair = "_REDIR_AFTER_LGI";
                                        $redir_url = $url."?redir_affair=$redir_affair&redir_url=$curl";
                                        
                                        $url = $redir_url;
                                        
//                                        var_dump(__FILE__,__FUNCTION__,__LINE__,[$url,$curl]);
//                                        var_dump(__FILE__,__FUNCTION__,__LINE__,[$redir_affair,$redir_url]);
//                                        exit();
                                        
                                    }
                                }
                            break;
                        case AG_AGALL_SR_NEED :
                                //TODO 
                                exit();
                            break;
                        case AG_RACCONOWN_SR1 :
                                //TODO
                                exit();
                            break;
                        default:
                            exit();
                            break;
                    }
                    
                    $R->start_redir_to_this_url_string($url);
                }    

            }
        }
    }
    
    
    private function handle_denied_access_urq($urq_access_rules) {
        
        foreach ($urq_access_rules as $ag) {
            switch($ag){
                case AG_AGDENIED :
                        //On peut tout aussi exit. Dépend du webmaster
                        $this->signalError("err_sys_l332",__FUNCTION__, __LINE__);
                    break;
                case AG_DEN_SR_RPROD :
                case AG_DEN_SR_RPROD2 :
                        $R = new REDIR($this->prod_xmlscope);
                        $R->redir_handle_redir_case($ag,$this->is_visitor_rest);
                    break;
            }
        }
    }


    private function handle_special_rest_visitor_case ($entry_reference, $vir) {
        if ( $vir == TRUE and ($entry_reference != EXC_GO_ON) ) $this->signalErrorWithoutErrIdButGivenMsg ("<p style\"color:red;\">FOR DEBUG or TEST : We whould REDIR</p>", __FUNCTION__, __LINE__) ;
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
         * 
         * Cas 1 : Utilisateur non connecté qui ne demande (logiquement) pas une page REST
         *      Solution 1 : Lui fournir la page dans la langue de l'url si le prev_wto 
         *      Raison : On va favoriser le referencement de la page en url_lang et eventuellement de run_lang car toute nouvelle action entrainera un changement de langue
         *      (Suite) De plus, c'est comme un avant gout. Si l'user souhaite rester sur l'ancienne langue, il n'aura qu'à chercher et trouver l'endroit pour changer de langue.
         *      Solution 2 : Lui fournir la page dans la langue de running_lang si le prev_wto existe (option : le prevenir qu'il peut à tout moment changer la langue s'il veut obtenir l'article dans la bonne langue)
         *      
         * Cas 2 : Utilisateur connecté qui demande une page quelque soit l'environnement
         *      Solution : Lui fournir la page en fonction de running_lang 
         */
        
        /*
         * [DEPUIS 11-07-16]
         *      On privéligie mainteant 
         */
        $this->curr_wto->setWatchman_decision_on_lang($entry_r_lang);
        return;
        
        /*
         * NOTE au [24-09-13]
         *      On abondonne la piste du REFERER car il n'est pas sur. On va se fier à ce que NOUS faisons en interne
         */
        
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry_r_lang,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry_wto,'v_d');
        
        if ( $entry_r_lang !==  $entry_wto->getUserSuppliedPageLang() ) {
            
            if ( $this->is_visitor_rest ) {
                //Laisser le running lang
                $this->curr_wto->setWatchman_decision_on_lang($entry_r_lang);
            } else {
                //On regarde si prev_WTO exists
                //Si Session, on regarde dans SESSION
                $STO = new SESSION_TO();
                $session_is_ready_to_be_used = PCC_SESSION::doesSessionExistAndIsNotvoid();
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SESSION["sto"],'v_d');              
                if (! $session_is_ready_to_be_used) {      
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
                    } else {
                        $this->signalError ("err_sys_l014", __FUNCTION__, __LINE__);
                    }
                }
            }
        } else {
            $this->curr_wto->setWatchman_decision_on_lang($entry_r_lang);
        }
    }
    
    
    private function l_utilisateur_est_il_legitime_dans_sa_requete ($entry_vtype, $entry_list_of_rules) {
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$entry_list_of_rules);
        //Le visiteur est il autorisé, son type correspond t-il ?
        if (!in_array($entry_vtype, $entry_list_of_rules)) {
            //$this->signalError("err_sys_l328",__FUNCTION__, __LINE__);
            return FALSE;
        } else return TRUE;
    }
    
    
    private function treat_special_urq_cases ($entry_urq_xmlscope) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        //TODO
        //Pour faire plus court
        $var = $entry_urq_xmlscope;
        
        if ( key_exists("urd_is_auth", $var) and $var["urd_is_auth"] == "yes" ) {
            //TODO
            /**
             * TODO :
             * 1- Instancier la base de données et ajouter une tentative de connexion
             * 2- Créer une cle d'opération et l'associer au fichier de SESSION (ou en URL, à voir)
             * 3- Si une nouvelle connexion pour le même fichier de session est détecter dans un delai raisonnable 
             *  31 - On se resnseigne sur l'issue de la precedente tentative
             *  32 -  S'il s'agit d'un essai supplémentaire on s'assure que le nombre max d'essai n'est pas atteint
             *   321 - Si le nombre est atteint on rejete tous les essais au niveau de WATCHMAN)
             *   332 - Si un comportement suspect est détecté (ex : brute foce -> plusieurs essais dans une periode de temps courte à très courte, de façon répétée avec des identifiants differents)
             *         On bannis l'ip. On pourrait ensuite si la situation perdure, bannir au niveau du server HTTP
             *  33 - Sinon Etape 1
             */
        }
    }
            
    
    private function control_incoming_datas ($entry_urq_xmlscope) {
        //Controles les données entrant
        /**
         * La methode controle les données GET et POST.
         * Pour POST on controle en comparant les index et en controlant les valeurs insérées
         * Pour GET, URQCHECKER a déjà controlé les index et leurs légitimités. Maintenant, il faut controler les valeurs insérées.
         */
        $uxs = $entry_urq_xmlscope;
        $pd = $_POST;
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__, $uxs["urq_post_data_expected"],'v_d');
        //exit();
        //*
        if ( key_exists("post", $uxs["urq_post_data_expected"]) and count($uxs["urq_post_data_expected"]["post"]) ) {
            if ( empty($pd) )
                $this->signalError("err_sys_l335",__FUNCTION__, __LINE__);
            else {
                //upv = urq_post_values
                $upv = $uxs["urq_post_data_expected"]["post"];
                
                $diff = array_diff(array_keys($pd), array_values($upv) );
                
                if ( count($diff) )
                    $this->signalError("err_sys_l336",__FUNCTION__, __LINE__);
            }
        }
        //*/    
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
