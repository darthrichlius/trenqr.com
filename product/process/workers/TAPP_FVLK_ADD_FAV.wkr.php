<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */

class WORKER_TAPP_FVLK_ADD_FAV extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = TRUE;
    }
    
    private function DoesItComply_Datas() {
        
        foreach ($this->KDIn["datas"] as $k => $v) {
            if ( $k === "d" && $v === "" ) {
                continue;
            }
            
            if (! ( isset($v) && $v !== "" ) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_DATAS");
            }
            
//            //On vérifie s'il s'agit d'un cas de body
//            if ( $k === "t" ) {
                
            //On vérifie que les données pour le "body" du commentaire sont valides selon les règles en vigueur au niveau du WORKER
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
//            }
            
            $istr = ["t","u","c","cu"];
            if ( $v && in_array($k,$istr) && !is_string($v) ) {
                $this->Ajax_Return("err","__ERR_VOL_WRG_HACK");
            }    
            
        }
        
    }
    
    private function addfavlink () {
        
        $args_new = [
            "accid"         => $this->KDIn["oid"],
            "acc_eid"       => $this->KDIn["oeid"],
            "fav_title"     => $this->KDIn["datas"]["t"],
            "fav_url"       => $this->KDIn["datas"]["u"],
            "fav_desc"      => $this->KDIn["datas"]["d"],
            "fav_catg"      => $this->KDIn["datas"]["c"],
            "fav_ssid"      => session_id(),
            "fav_curl"      => $this->KDIn["datas"]["cu"],
            "fav_locip"     => $this->KDIn["locip"],
            "fav_uagent"    => $this->KDIn["uagt"]
        ];
        
        $this->DoesItComply_Datas();
        /*
        var_dump(__LINE__,__FUNCTION__,$args_new);
        exit();
        //*/
        $uid = $this->KDIn["oid"];
        $FVLK = new FAVLINLK();
        $favdom = $FVLK->on_create_entity($args_new);
        if (! $favdom ) {
            $this->Ajax_Return("err","__ERR_VOL_FAILED");
        } else if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $favdom) ) {
            $this->Ajax_Return("err",$favdom);
        }
        
        /*
         * Données sur le nouveau lien favori.
         */
        $FAVDOM = [
            "item"      => $favdom["fav_eid"],
            "catg"      => $favdom["fav_catg"],
            "title"     => html_entity_decode($favdom["fav_title"]),
            "url"       => html_entity_decode($favdom["fav_url"]),
            "desc"      => html_entity_decode($favdom["fav_desc"]),
            "since"     => $favdom["fav_adddate_tstamp"],
            "last"      => $favdom["fav_lastvst"],
            "visits"    => $favdom["fav_nbvst"] 
        ];
        
        /*
         * Nombre total de liens favoris.
         */
        $lksnb = $FVLK->onread_totLinksnb($uid);
        
        /*
         * Données sur le propriétaire des liens.
         * On préfère cette méthode car elle nous permet de ne pas avoir à lancer un nouveau processus qui ne serait pas bénéfique pour la performance.
         */
        $USERDOM = [
            //Données sur l'OWNER
            "oid"       => $favdom["fav_oeid"],
            "ofn"       => $favdom["fav_ofn"],
            "opsd"      => $favdom["fav_opsd"],
            "ohref"     => $favdom["fav_ohref"],
            "oppic"     => $favdom["fav_oppic"],
        ];
        
        //FINALE
        $this->KDOut["FE_DATAS"] = [
            "tiadm"         => null, // TODO : Données à jour sur TIA
            "tapp_fvlkdm"   => null, // TODO : Données à jour sur l'application TIA FAVLINK
            "fvdm"          => $FAVDOM,
            "lksnb"         => $lksnb,
            "usdm"          => $USERDOM
        ]; 
    }
    
    public function prepare_datas_in() {
        //OBLIGATOIRE !!! Sinon on ne pourra pas utiliser les SESSION. Normalement on aura une erreur NOTICE qui doit se déclarer pour dire que Session_Start() a déjà été appelée, tant mieux !
        @session_start();
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        /*
         * On vérifie que toutes les données sont présentes
         * 
         *  t : Titre
         *  u : URL
         *  d : Description
         *  c : Catégorie
         *  cu : Current URL
         */
        $EXPTD = ["t","u","d","c","cu"];
        
        if ( count($EXPTD) !== count(array_intersect(array_keys($_POST["datas"]),$EXPTD)) ) {
            $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
        }
        
        $in_datas = $_POST["datas"];
        $in_datas_keys = array_keys($_POST["datas"]);
        
        foreach ($in_datas_keys as $k => $v) {
            if ( !( isset($v) && $v !== "" ) && !in_array($k,["d"]) )  {
                $this->Ajax_Return("err","__ERR_VOL_DATAS_MSG");
            }
        }
        
        //* On s'assure que l'utilisateur est connecté, existe et on le charge *//
        
        //Est ce qu'une session existe ?
        if (! PCC_SESSION::doesSessionExistAndIsNotVoid() ) {
            //Cela est normalement très peu probable
            
            $this->Ajax_Return("err","__ERR_VOL_SS_MISSING");
        }
        
        //Est ce que l'utilisateur est connecté ?
        $CXH = new CONX_HANDLER();
        if ( !$CXH->is_connected() ) {
            $this->Ajax_Return("err","__ERR_VOL_DNY_AKX");
        }
        
        //On récupère l'identifiant de l'utilisateur et on vérifie qu'il existe toujours
        $oid = $_SESSION["rsto_infos"]->getAccid();
        $A = new PROD_ACC();
        
        $exists = $A->exists_with_id($oid,TRUE);
        if ( !$exists ) {
            $this->Ajax_Return("err","__ERR_VOL_USER_GONE");
        }
        
        /* Données sur le futur OWNER (Necessaire pour l'e FE'acquisition des données) */
        $this->KDIn["oid"] = $oid;
        $this->KDIn["oeid"] = $_SESSION["rsto_infos"]->getAcc_eid();
        $this->KDIn["locip"] = sprintf('%u', ip2long($_SESSION['sto_infos']->getCurrent_ipadd()));
        $this->KDIn["uagt"] = $_SERVER["HTTP_USER_AGENT"];
        
        $this->KDIn["datas"] = $in_datas;
//        $this->KDIn["b"] = $in_datas["b"];
    }
    
    public function on_process_in() {
        $this->addfavlink();
    }
    
    public function on_process_out() {
        $this->Ajax_Return("return",$this->KDOut["FE_DATAS"]);
        //FINAL EXIT : Permet d'être sur à 100% qu'ON IRA JAMAIS VERS VIEW
        exit();
    }

    protected function prepare_params_in_if_exist() {
        
    }
}

?>
