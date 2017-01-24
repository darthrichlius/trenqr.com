<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WORKER_INS_SRH extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION,'v_d');
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["sto_infos"]->getCurr_wto()->getUps_required()["k"],'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
    }
    
    
    /**************** START SPECFIC METHODES ********************/
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
           
            if ( $k === "x" ) {
                continue;
            } elseif ( $k === "loc_cn" && $_SERVER["REMOTE_ADDR"] === "127.0.0.1" && in_array ($_SERVER["SERVER_NAME"],["127.0.0.1","localhost","trenqr.local.dev"]) ) {
                /*
                 * [DEPUIS 19-10-15] @author BOR
                 *      Permet de travailler en mode local
                 */
                $this->KDIn["datas"]["loc_cn"] = "fr";
                continue;
            } else {
               //Les données ont déjà été vérifiées
//            if (! ( isset($v) && $v !== "" ) ) {
//                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
//            }
            
                //On vérifie que le contenu ne correspond à une chaine vide dans le sens où, il n'y a que des espaces
                $m_c1 = $m_c2 =  $m_c3 = $m_c4 = $m_c5 = NULL;
                $rbody = $this->KDIn["datas"][$k];

                preg_match_all("/(\n)/", $rbody, $m_c1);
                preg_match_all("/(\r)/", $rbody, $m_c2);
                preg_match_all("/(\r\n)/", $rbody, $m_c3);
                preg_match_all("/(\t)/", $rbody, $m_c4);
                preg_match_all("/(\s)/", $rbody, $m_c5);

                //Parano : Je sais que j'aurais pu ne mettre que \s
                if ( count($m_c1[0]) === strlen($rbody) || count($m_c2[0]) === strlen($rbody) || count($m_c3[0]) === strlen($rbody) || count($m_c4[0]) === strlen($rbody) || count($m_c5[0]) === strlen($rbody) ) {
                    echo $k;
                    $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
                }

                //CAS SPECIAUX
                $qsp = ["fullname","cty_srh","cty_srh_this","pseudo","email"];
                if ( $k === "iqsp" && !in_array($v, $qsp) ) {
                    $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
                } 
            }    
            
        }
        
    }
    
    private function OnlyMyDatas ($datas, $qsp) {
        
        $nd;
        switch ($qsp) {
            case "cty_srh" :
            case "cty_srh_this" :
                    $nd = [
                        "cities"    => $datas,
                        "toq"       => $this->KDIn["datas"]["t"]
                    ];
                break;
            case "fullname" :
            case "pseudo" :
            case "email":
                    $nd = $datas;
                break;
            default:
                break;
        }
        
        return $nd;
    }
    
    private function PullDatas () {
        $this->DoesItComply_Datas(); 
        
        $qt = $this->KDIn["datas"]["qt"];
        $qsp = $this->KDIn["datas"]["iqsp"];
        $t = $this->KDIn["datas"]["t"];
        
        $INS = new INSCRIPTION();
        
        $datas = NULL;
        $skip = FALSE;
        switch ($qsp) {
            case "cty_srh":
                    $qt = $qt."*";
                    if ( !empty($this->KDIn["datas"]["loc_cn"]) ) {
                        $datas = $INS->pullCity($qt,8,$this->KDIn["datas"]["loc_cn"]);
                    } else {
                        $datas = $INS->pullCity($qt,8);
                    }
                    
                break;
            case "cty_srh_this":
                    if ( key_exists("x",$this->KDIn["datas"]) && !empty($this->KDIn["datas"]["x"]) && key_exists("cycn",$this->KDIn["datas"]["x"]) && !empty($this->KDIn["datas"]["x"]["cycn"]) ) {
                        $datas = $INS->pullCity_This($qt,$this->KDIn["datas"]["x"]["cycn"]);
                    } else {
                        $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
                    }
                break;
            case "fullname" :
                    //On vérifie si le pseudo contient des caractères interdits
                    $TQA = new TQR_ACCOUNT();
                    $datas = $TQA->Pseudo_IsDenied($qt);
                break;
            case "pseudo":
                    //On vérifie si le pseudo existe
                    $exists = $INS->PullPseudo($qt);
                    //On vérifie si le pseudo est réservé
                    $TQA = new TQR_ACCOUNT();
                    $is_resvd = $TQA->Pseudo_IsReserved($qt);
                    //On vérifie si le pseudo contient les mots interdits
                    $is_denied = $TQA->Pseudo_IsDenied($qt);
//                    var_dump(__FUNCTION__,__LINE__,$is_denied);
//                    var_dump($datas,$this->KDIn["datas"]["x"]);
//                    exit();
                    //Si le pseudo est pris ET qu'on a des données en options, on tente la suggestion si les options sont disponibles
                    if ( ( $exists | $is_resvd | $is_denied ) && isset($this->KDIn["datas"]["x"]) && is_array($this->KDIn["datas"]["x"]) && count($this->KDIn["datas"]["x"]) ) {
                        $datas = $INS->SuggestPseudo($qt, $this->KDIn["datas"]["x"], TRUE);
                    } else if ( ( $exists | $is_resvd | $is_denied ) && !$this->KDIn["datas"]["x"] ) {
                        $rd = [rand(1,2014)];
                        $datas = $INS->SuggestPseudo($qt, $rd, TRUE);
                    } 
                    /*
                     * [DEPUIS 28-05-16]
                     *      Le cas "$datas === FALSE" est possible si l'algorithme n'arrive pas à fournir de PSEUDO de remplacement.
                     *      Cela peut arriver par exemple si 
                     *          (1) Le PSEUDO fourni par USER a déjà le nombre de caractère maximum autorisé.
                     *          (2) Toutes les propositions auto-générées sont elles même déjà indisponibles
                     * 
                     *      Si le PSEUDO contient des mots interdits, il ne faut rien lui proposer.
                     *      Sinon, il ne comprendra 
                     */
                    if ( $datas === FALSE && ( $is_denied | $is_resvd ) ) {
                        $datas = -1;
                    }
                break;
            case "email":
                    $datas = $INS->PullEmail($qt);
                    if (! $datas ) {
                        //On vérifie si le domaine lié est disponible
                        $eml_dns = $INS->CheckEmailDomDns($qt);
                        
                        if (! $eml_dns ) {
                            $datas = "__ERR_VOL_DNS";
                            $skip = TRUE;
                        } 
                        if ( $eml_dns && $INS->IsEmailDomBan($qt) ) {
                            //On vérifie si le domaine lié ne fait pas parti des domaines bannis
                            $datas = "__ERR_VOL_DOM";
                            $skip = TRUE;
                        }
                    }
                break;
            default:
                    $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
                break;
        }
        
//        var_dump($datas);
        
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $datas) && !$skip ) {
            $this->Ajax_Return("err",$datas);
        }
        else if (! isset($datas) ) {
            $this->KDOut["FE_DATAS"] = "";
        } 
        else {
            $this->KDOut["FE_DATAS"] = $this->OnlyMyDatas($datas,$qsp);
//            $this->KDOut["FE_DATAS"] = $datas;
        }
        
    }
    

    /****************** END SPECFIC METHODES ********************/
    
    
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    /*****************************************************************************************************************************************************/
    
    /*********** TMP *************/
    //Mettre les instructons faites ailleurs pour les intégrer au WORKER
    
    
    /*****************************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        //On masque les erreurs NOTICE, WARNING car ça fausse les résultats cote FE
        @session_start();
        
        
        //* On vérifie que toutes les données sont présentes *//
        /*
         * "qt" : QueryText,
         * "iqsp" : InscriptionQueryScoPe
         * "t": Le temps en millisecondes au moment de la requete. Permet à FE de n'afficher que le résultat le plus récent
         * "x": eXtra datas. L'utilisateur du WORKER peut passer des données supplémentaires. Cette valeur peut être vide.
         *      Elle est obligatoire pour certaines requêtes
         */
        $EXPTD = ["qt","iqsp","t","x"];

        if (count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD))) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        /*
         * L'utilisateur du WORKER peut passer x : Extras datas
         * Cette donnée n'est pas obligée d'être non vide
         */
        foreach ($in_datas_keys as $k => $v) {
            if (! ( isset($v) && ( $v != "" || $k === "x") ) ) {
                $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
            }
        }

        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        //Est ce qu'une session existe ?
        if (!PCC_SESSION::doesSessionExistAndIsNotVoid()) {
            //Cela est normalement très peu probable

            $this->Ajax_Return("err", "__ERR_VOL_SS_MSG");
        }

        $this->KDIn["datas"] = $in_datas;
        $this->KDIn["datas"]["loc_cn"] = $_SESSION["sto_infos"]->getCtr_code();
    }

    public function on_process_in() {
        $this->PullDatas();
    }

    public function on_process_out() {
        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }
    
}

?>