<?php

/*
 * [NOTE 01-10-2015] @author BOR
 *      J'ai choisi une solution de page plutôt qu'un CSAM pour pouvoir faire référencer cette page au niveau des moteurs de recherche.
 *      En effet, l'url permet de capter les requêtes sur les mots clés tels que : image, communauté, tendance, cool, ...
 *      De plus cela permet d'écarter Facebook et son SDK des autres pages du site.
 */
class WORKER_TQR_STUDIO_GTPG extends WORKER {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        $this->isAjax = FALSE;
    }
    
    /***************************************************************************************************************/
    /************************************************ SPECIFICS ****************************************************/
    
    private function cu_complies ($target) {
        //QUESTION : Est ce que l'utilisateur, au regard des règles de fonctionnement du produit, a le droit d'accéder à cette page?
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //* On vérifie que le pseudo est conforme au format attendu *//
        
        //On vérifie qu'on a bien l'user et que son identifiant ueid est défini 
        $A = new PROD_ACC();
        $exists = $A->exists_with_psd($target,TRUE);
        
        if (! $exists ) {
            $this->signalError ("err_user_l5e404_user", __FUNCTION__, __LINE__);
        } else {
            return $exists;
        }
    }
    
    private function AcquireCUDatas() {
        /*
         * Permet de récupérer les données fondamentales en ce qui concerne l'Utilisateur connecté.
         */
        $curr = $this->KDIn["curr"];
        
        $accid = $curr["pdaccid"];

        $A = new PROD_ACC();
        $A->on_read_entity(["accid" => $accid]);

        //TODO : A quelle moment on sécurise les données ? (Entity ou ICI)

        $CU = [];
        $CU["cueid"] = $A->getPdacc_eid();
        $CU["cuppic"] = $A->getPdacc_uppic();
        $CU["cufn"] = $A->getPdacc_ufn();
        $CU["cupsd"] = $A->getPdacc_upsd();
        $CU["cuhref"] = "/@".$A->getPdacc_upsd();
        $CU["cucityid"] = $A->getPdacc_ucityid();
        $CU["cucity"] = $A->getPdacc_ucity_fn();
        $CU["cucn_fn2"] = $A->getPdacc_ucnid();
        
        /*
         * On renvoie l'email
         */
        $TA = new TQR_ACCOUNT();
        $CU["cueml"] = $TA->on_read_entity(["acc_eid"=>$CU["cueid"]])["acc_eml"];
        
        /* On crée la donnée dans KDatas*/
        $this->KDout["CUser_TAB"] = $CU;
    }
    
    private function Page() {
        
    }
    
    private function PageVersion() {
        /*
         * Permet de déterminer la version de la page.
         * La méthode permet aussi d'obtenir la donnée qui détermine si l'utilisateur est connecté.
         */
        
        if (! ( key_exists("curr", $this->KDIn) && $this->KDIn["curr"] && count($this->KDIn["curr"]) ) ) {
            $this->KDout["pgver"] = "WLC";
            $this->KDout["iauth"] = FALSE;
        } else if ( $this->KDIn["curr"]["pdaccid"] === $this->KDOut["ART_TAB"]["art_oid"] ) {
            $this->KDout["pgver"] = "RO";
            $this->KDout["iauth"] = TRUE;
        } else {
            $this->KDout["pgver"] = "RU";
            $this->KDout["iauth"] = TRUE;
        }
        
//        var_dump($this->KDout["pgver"],$this->KDout["iauth"]);
//        exit();
    }
    
    private function CustumHeaderDatas () {
        /*
         * Placer les éléments à mettre dans le header
         */
        $this->KDout["head"]["..."] = "";
    }
    
    
    /***************************************************************************************************************/
    
    public function prepare_datas_in() {
        
        //RAPPEL : Si on est arrivé ici c'est forcement que WOS c'est assuré que CU est connecté.
        $STOI = new SESSION_TO();
        $STOI = $_SESSION["sto_infos"];
        
        $CXH = new CONX_HANDLER();
        if ( $CXH->is_connected() ) {
            $RSTOI = new RSTO_INFOS();
            $RSTOI = $_SESSION["rsto_infos"];
            
            //Récupération des données sur le compte CU
            $curr_user = $RSTOI->getUpseudo();
            //On vérifie que l'utilisateur actif est Autorisé à continuer
            $curr_user = $this->cu_complies($curr_user);

            $this->KDIn["curr"] = $curr_user;
        } 
        
    }
    
    
    public function on_process_in() {
        /* CU DATAS */
        if ( key_exists("curr", $this->KDIn) && $this->KDIn["curr"] && count($this->KDIn["curr"]) ) {
            $this->AcquireCUDatas();
        }
        
        $this->Page();
        
        /* PAGE VERSION */
        $this->PageVersion();
        
        /* PAGE HEAD*/
        $this->CustumHeaderDatas();
    }

    public function on_process_out() {
        //QUESTION : Esr ce que l'utilisateur actif est connecté ? (Sert aux modules en aval)
        $_SESSION["ud_carrier"]["iauth"] = $this->KDout["iauth"];
        
        /* CURRENTUSER PAGE DATAS */
        foreach ( $this->KDout["CUser_TAB"] as $k => $v ) {
            $_SESSION["ud_carrier"][$k] = $v;
        }

        /* PAGE HEAD DATAS */
        /*
         * Il s'agit des données destinées à être ajoutées au niveau du header de la page.
         */
        if ( $this->KDout["head"] ) {
            foreach ($this->KDout["head"] as $k => $v) {
                $_SESSION["ud_carrier"][$k] = $v;
            }
        }
        
        
        /* 
         * On inscrit certaines informations relatives à la page.
         * Ces informations sont données par WORKER car 'ver' dépend du fait qu'on soit sûr que la procédure c'est passé corectement.
         * Seuls les WORKER définissent les droits d'accès.
         * 
         * En ce qui concerne 'pgid', WORKER le fait car certains URQ sont de type AJAX qui n'admettent pas de page.
         * C'est donc au WORKER de définir ces informations.
         */
//        $_SESSION["ud_carrier"]["pagid"] = "fksa";
        $_SESSION["ud_carrier"]["pgakxver"] = $this->KDout["pgver"];
        
    }

    
    protected function prepare_params_in_if_exist() {
        
    }

}
