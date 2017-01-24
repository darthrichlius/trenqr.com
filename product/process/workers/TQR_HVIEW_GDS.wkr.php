<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_TQR_HVIEW_GDS extends WORKER  {
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            if ( !( isset($v) && $v !== "" ) && in_array($k,["dr","pvi","pvt"]) ) {
                continue;
            } else {
               //Les données ont déjà été vérifiées
//            if (!  ) {
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
                    $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
                }
            }    
        }
    }
    
    
    private function GetDatas() {
        $this->DoesItComply_Datas();
        
        $str = $this->KDIn["datas"]["hs"];
        $dr = ( $this->KDIn["datas"]["dr"] ) ? $this->KDIn["datas"]["dr"] : NULL;
        $this->KDIn["datas"]["iald"] = ( in_array($this->KDIn["datas"]["cz"],["init","auto"]) ) ? TRUE : FALSE;
        $pvi = ( $this->KDIn["datas"]["pvi"] ) ? $this->KDIn["datas"]["pvi"] : NULL;
        $pvt = ( $this->KDIn["datas"]["pvt"] ) ? $this->KDIn["datas"]["pvt"] : NULL;
        
        /*
         * ETAPE :
         *      On vérifie que la donnée "direction" est valide.
         */
        if ( $dr && !in_array($dr,["FST","BTM","TOP"]) ) {
            $this->Ajax_Return("err", "__ERR_VOL_WRG_DATAS");
        }
        
        /*
         * ETAPE :
         *      Obligatoire pour la fiabilité de l'opération
         */
        $str = trim($str);
        
        /*
         * ETAPE :
         *      On vérifie qu'il n'y a pas de '#' devant, sinon on le retire
         */
        $str = ( $str[0] === '#' ) ? substr($str,1) : $str;
        $this->KDIn["datas"]["hs"] = $str;
        
        /*
         * ETAPE :
         *      On vérifie que le texte est un mot.
         *      Pour cela, on vérifie la présence de quelque chose qui n'est pas en rapport avec le format d'un hashtag : ESPACE, VIRGULE, ...
         */
        if ( preg_match("/[^a-z\d_ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]/i", $str) ) {
            $this->Ajax_Return("err", "__ERR_VOL_WRG_DATAS_REF");
        }
        
        $HVIEW = new HVIEW();
        /*
         * ETAPE :
         *      On vérifie qu'il existe des données pour cette recherche avant d'aller plus loin.
         *      Cela permet aussi de valider la chaine en ce qui concerne sa taille.
         */
        $hdatas = [];
        $exs = $HVIEW->HSH_exists_with_hsh($str,["GET_COUNT"]);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $exs) ) {
            $this->Ajax_Return("err", "__ERR_VOL_FAILED");
        } else if ( $exs ) {
            /*
             * ETAPE :
             *      On lance la recherche
             */
            $hdatas = $HVIEW->Search($str,$dr,$this->KDIn["oid"],$pvi,$pvt,10);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $hdatas) ) {
                $this->Ajax_Return("err", "__ERR_VOL_FAILED");
            } 
        }
        
        /*
         * ETAPE :  
         *      ON récupère le texte descritif du HASHTAG s'il existe
         */
        $hdesc = $HVIEW->HSH_MODO_DESC($str,"fr");
        
        $FE_DATAS = [
            "hd"    => $hdesc,
            "ds"    => $hdatas,
        ];
        
        $this->KDOut["FE_DATAS"] = $FE_DATAS;
    }

    /**************** END SPECFIC METHODES ********************/
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        @session_start();
        
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        $STOI = new SESSION_TO();
        $STOI = $_SESSION['sto_infos'];
        $RSTOI = new RSTO_INFOS();
        $RSTOI = $_SESSION["rsto_infos"];
        
        //* On vérifie que toutes les données sont présentes *//
        /*
         * hs   : Le HASHTAG recherché
         * dr   : La direction
         * pvi  : L'identifiant de l'élément servant de PIVOT
         * pvt  : Le TIMESTAMP de l'élément servant de PIVOT
         * cz   : Le cas correspondant à la requête : 'init', 'search', 'get_more', 'auto'
         * cu   : L'url correspondant à la page sur laquelle l'utilisateur indique être
         */
        $EXPTD = ["hs","dr","pvi","pvt","cu"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]), $EXPTD)) ) {
            $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
        }

        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);

        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v !== "" ) && !in_array($k,["dr","pvi","pvt"]) ) {
                $this->Ajax_Return("err", "__ERR_VOL_DATAS_MSG");
            }
        }

        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        //Est ce qu'une session existe ?
        if (!PCC_SESSION::doesSessionExistAndIsNotVoid()) {
            //Cela est normalement très peu probable
            $this->Ajax_Return("err", "__ERR_VOL_SS_MSG");
        }
        
        
        //Est ce que l'utilisateur est connecté ?
        $CXH = new CONX_HANDLER(); 
        if ( $CXH->is_connected() ) {
            $this->KDIn["oid"] = $_SESSION["rsto_infos"]->getAccid();
            $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        }
        
        $this->KDIn["datas"] = $in_datas;
        $this->KDIn["srv_curl"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->KDIn["locip"] = sprintf('%u', ip2long($STOI->getCurrent_ipadd()));
        $this->KDIn["loc_cn"] = $STOI->getCtr_code();
        $this->KDIn["uagent"] = $_SERVER['HTTP_USER_AGENT'];
    }
    
    public function on_process_in() {
        $this->GetDatas();
    }

    public function on_process_out() {
        $this->Ajax_Return("return", $this->KDOut["FE_DATAS"],FALSE);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
//        exit();
        
        /*
         * [DEPUIS 12-07-16]
         *      On enregistre l'ACTIVITE passive dans le cas où l'USER est CONNECTED
         */
        if ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] && $this->KDIn["datas"]["iald"] === TRUE ) {
            $PM = new POSTMAN();
            $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["cu"], $this->KDIn["srv_curl"], TRUE, $this->KDIn["oid"], 1601, TRUE, $this->KDIn["datas"]["hs"]);
        } 
        else if ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] && !$this->KDIn["datas"]["iald"] && $this->KDIn["datas"]["dr"] === "FST" ) {
            $PM = new POSTMAN();
            $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["cu"], $this->KDIn["srv_curl"], TRUE, $this->KDIn["oid"], 1602, TRUE, $this->KDIn["datas"]["hs"]);
        }
        else if ( key_exists("oid", $this->KDIn) && $this->KDIn["oid"] && !$this->KDIn["datas"]["iald"] && $this->KDIn["datas"]["dr"] === "BTM" ) {
            $PM = new POSTMAN();
            $this->Wkr_LogUsertagActy($PM, $this->KDIn["oid"], $_SESSION['sto_infos']->getCurrent_ipadd(), $this->KDIn["datas"]["cu"], $this->KDIn["srv_curl"], TRUE, $this->KDIn["oid"], 1603, TRUE, $this->KDIn["datas"]["hs"]);
        }
        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }

}

?>