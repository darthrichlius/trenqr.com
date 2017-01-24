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
class INSCRIPTION extends MOTHER {
    
    private $needed_to_create_prop_keys;
    
    private $rgx_fn;
    private $rgx_bd;
    private $bd_limit;
    private $rgx_gdr;
    private $rgx_psd;
    private $psd_min;
    private $psd_max;
    private $rgx_email;
    private $email_max;
    private $rgx_pwd;
    private $pwd_min;
    private $pwd_max;
    
    private $ufn;
    private $upsd;
    private $ueml;
    private $ubd;
    private $ugdr;
    private $pwd;
    private $ulvcty;
            
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->prop_keys = [""];
        $this->needed_to_loading_prop_keys = [""];
        
        $this->needed_to_create_prop_keys = ["ins_fn","ins_nais","ins_gdr","ins_cty","ins_psd","ins_eml","ins_pwd","locip"];
        
        /********** REGEX & RULES **********/
        $this->rgx_fn = "/^(?=.*[a-z])[a-z\-\+\. ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,25}$/i";
        $this->rgx_bd = "/^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/";
        $this->bd_limit = 12;
        $this->rgx_gdr = "/^(f|m)$/i";
        $this->rgx_psd = "/^(?=.*[a-z])[\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20}$/i";
        $this->psd_min = 2;
        $this->psd_max = 20;
        $this->rgx_email = "/^[\w.+-]+@(?:[\w\d-]+\.)+[a-z]{2,6}$/i";
        $this->email_max = 256;
        $this->rgx_pwd = "/^(?=(.*\d))(?=.*[a-z])(?=.*[²&<>!.?+*_~µ£^¨°()\[\]\-@#$%:;=''\/\\¤]).{6,32}$/i";
        $this->pwd_min = 6;
        $this->pwd_max = 32;
    }
    
    
    /********************************************************* ACQUISITION DE DONNÉES ********************************************************************/
    
    public function pullCity ($qt, $limit = NULL, $cn_code = NULL) {
        /*
         * Récupère une liste de villes à partir de la chaine passée en paramètre.
         * La requete est sélectionnée en fonction de des éléments fournies en paramètre.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $datas;
        if ( empty($limit) && empty($cn_code) ) {
            $QO = new QUERY("qryl4insn1");
            $params = array( ':sqy' => $qt );
            $datas = $QO->execute($params);
        } else if ( !empty($limit) && empty($cn_code) ) {
            $QO = new QUERY("qryl4insn2");
            $params = array( 
                ':sqy1' => $qt, 
                ':sqy2' => $qt, 
                ':limit' => $limit );
            $datas = $QO->execute($params);
        } else if ( empty($limit) && !empty($cn_code) ) {
            $QO = new QUERY("qryl4insn3");
            $params = array( 
                ':sqy1' => $qt, 
                ':sqy2' => $qt, 
                ':cn_code11' => $cn_code,
                ':cn_code12' => $cn_code );
            $datas = $QO->execute($params);
        } else {
            $QO = new QUERY("qryl4insn4");
            $params = array( 
                ':sqy11' => $qt, 
                ':sqy12' => $qt, 
                ':sqy21' => $qt,
                ':sqy22' => $qt,
                ':cn_code1' => $cn_code,
                ':cn_code2' => $cn_code,
                ':limit' => $limit );
            $datas = $QO->execute($params);
        }
        
        
        return $datas;
        
    }
    
    public function pullCity_This ($qt, $cn_code) {
        /*
         * Récupère une liste de villes à partir de la chaine passée en paramètre.
         * Les villes renvoyées ont toutes le même nom et sont toutes dans le même pays.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $datas;
        $QO = new QUERY("qryl4insn5");
        $params = array(':sqy' => $qt, ':cn_code' => $cn_code);
        $datas = $QO->execute($params);

        return $datas;
    }
    
    /******************************** PSEUDO SCOPE ***********************************/
    
    public function PullPseudo ( $psd ) {
        /*
         * Permet de vérifier si un pseudo est disponible.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! is_string($psd) ){
            return;
            
        }
        
        $datas;
        $QO = new QUERY("qryl4insn6");
        $params = array(':sqy' => $psd);
        $datas = $QO->execute($params);

        if (! $datas ) { 
            return FALSE;
        } else  {
            /*
             * [NOTE 10-09-15] @author
             *  Les pseudos ne peuvent être réutilisé. On pourrait les libérer techniquement mais nous ne le faisons pas pour des raisons d'ordre fonctionnel.
             *  Raisons :   
             *      1- Pression psychologique sur les personnes qui veulent partir et qui pensent qu'ils pourront recréer un compte avec le même pseudo.
             *      2- Lutte contre l'usurpation d'identité
             */
            
            return $datas[0];
        }
    }
    
    public function SuggestPseudo ($psd, $options, $chkExst = FALSE) {
        /*
         * Permet de faire une proposition de pseudo.
         * La proposition se fait selon les indicateurs passées par CALLER dans le tableau.
         * Les indicateurs fournis par CALLER sont en fait une liste de préfixe.
         * Ces préfixes peuvent être : une année (2 à 4 chiffres), un code pays, un code région, etc ...
         * 
         * Si CALLER fournit "chkExst" la méthode va vérifier si le pseudo est disponible.
         * Pour des soucis de performance on ne bouclera qu'un de fois défini par le module.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie s'il s'agit d'un pseudo valide
        $iv =  $this->IsValidPseudo($psd);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $iv) ) {
            $this->Ajax_Return("err",$iv);
        }
        
        $sug_psd = [];
        if ( isset($options) && is_array($options) && count($options) ) {
             $TQA = new TQR_ACCOUNT();
            foreach ($options as $k => $v) {
                $f_ = $psd.$v;
                $b_ = $psd."_".$v;
                
                //Pour la version normal
                if ( $this->IsValidPseudo($f_) === TRUE ) {
                    //On vérifie s'il faut check le pseudo ...
                    if ( $chkExst === TRUE ) {
                        /*
                         * ... On vérifie si le nouveau pseudo est disponible
                         * [DEPUIS 28-05-16]
                         *      La VERIF prend maintenant en compte les cas DENIED et RESERVED.
                         */
//                        if (! $this->PullPseudo($f_) ) {
                        if ( !$this->PullPseudo($f_) && !$TQA->Pseudo_IsReserved($f_) && !$TQA->Pseudo_IsDenied($f_) ){
                            $sug_psd[] = $f_;
                        }
                    } else {
                        $sug_psd[] = $f_;
                    }
                }
                
                //Pour la version avec séparation 
                if ( $this->IsValidPseudo($b_) === TRUE ) {
                    //On vérifie s'il faut check le pseudo ...
                    if ( $chkExst === TRUE ) {
                        /*
                         * ... On vérifie si le nouveau pseudo est disponible
                         * [DEPUIS 28-05-16]
                         *      La VERIF prend maintenant en compte les cas DENIED et RESERVED.
                         */
//                        if (! $this->PullPseudo($b_) ) {
                        if ( !$this->PullPseudo($b_) && !$TQA->Pseudo_IsReserved($b_) && !$TQA->Pseudo_IsDenied($b_) ){
                            $sug_psd[] = $b_;
                        }
                    } else {
                        $sug_psd[] = $b_;
                    }
                }
            }
            
            return ( $sug_psd && is_array($sug_psd) && count($sug_psd) ) ? $sug_psd : FALSE;
            
        } else {
            return "__ERR_VOL_BAD_OPT";
        }
        
    }
    
    /******************************** EMAIL SCOPE ***********************************/
    
    public function PullEmail ($qt) {
        /*
         * Permet de vérifier si une adresse email est disponible.
         * Pour cela on regarde dans la table des emaux en cours d'utilisation qui offre de bonnes performances.
         * 
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $datas = NULL;
        $QO = new QUERY("qryl4insn7");
        $params = array(':sqy' => $qt);
        $datas = $QO->execute($params);
        
        if (! $datas ) { 
            return FALSE;
        } else  {
            $d__ = $datas[0];
            /*
             * [DEPUIS 10-09-15] @author BOR
             *  On vérifie si l'email n'est pas lié à un compte qui est en mode 2.
             *  
             *  Finalement, je laisse tombé car modifier le fonctionnement est plus complexe que je ne le pensais.
             *  Rendre disponible un email demanderait de modifier le code qui permet de créer un compte.
             *  Une modification à ce niveau ne suffirait pas. 
             *  L'ASTUCE reste de mettre dans les CGU qu'après la période de 30 jours, les données rentrent dans une période où elles seront supprimées au plutôt.
             *  Cette phase peut durer jusqu'à 3 mois. Pendant cette période, l'email reste bloqué.
             */
            /*
            $PA = new PROD_ACC();
            $utab = $PA->exists_with_id($d__["srh_eml_uid"]);
            if (! $utab ) {
                //[NOTE 10-09-15] Ca ne serait pas logique !
                return "__ERR_VOL_FAILED";
            } else {
                if ( intval($utab["pdacc_todelete"]) === 2 ) {
                    return FALSE;
                } else {
                    return ["email" => $d__["srh_emlraw"]];
                }
            }
            //*/
            
            return $d__;
        }
    }
    
    public function CheckEmailDomDns ($em) {
        /* 
         * Vérifie aussi si le domaine lié à l'email passé en paramètre est disponible.
         * Pour cela, on tente une résolution DSN.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie s'il s'agit d'un email
        if (! $this->IsValidEmail($em) ) 
            return;
        
        //On récupère le domaine à partir de l'adresse email
        $dom = explode("@", $em)[1];
        
        $r = checkdnsrr($dom,"MX");
        
        return $r;
    }
    
    public function CheckDomDns ($dom) {
        /* 
         * Vérifie aussi si le domaine est disponible.
         * Pour cela, on tente une résolution DSN.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $r = checkdnsrr($dom,"MX");
        
        return $r;
    }
    
    public function IsEmailDomBan ($em) {
        /*
         * Permet de vérifier si le nom de domaine lié à l'émail est banni ou interdit pour ajout.
         * La plupart du temps, un nom de domaine est banni pour les raisons suivantes :
         *  (1) Risques en termes de sécurité
         *  (2) Problème de fiabilité
         *  (3) Qualité de service 
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie s'il s'agit d'un email
        if (! $this->IsValidEmail($em) ) 
            return;
        
        //On récupère le domaine à partir de l'adresse email
        $dom = explode("@", $em)[1];
        
        $datas = NULL;
        $QO = new QUERY("qryl4insn8");
        $params = array(':dom' => $dom);
        $datas = $QO->execute($params);
        
        return (! $datas ) ? FALSE : TRUE;
    }
    
    
    public function CheckField ($fld_n, $fld_v) {
        /*
         * Permet de vérifier la validité des données rentrées dans les champs au niveau du formulaire d'inscription.
         * La méthode n'effectue pas d'opérations poussées comme pourrait le faire FE.
         * Elle se contente de dire si les champs sont valides ou non.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //IsValid
        $iv = FALSE;
        switch ($fld_n) {
            case "fullname" :
                    //On utilise une des fonctions de TQRACC. Plutot que la recréer
                    $TQACC = new TQR_ACCOUNT();
                    if ( preg_match($this->rgx_fn, $fld_v) ) {
                        //On vérifie si le NomComplet ne contient pas des mots interdits ou reservé est disponible
                        if ( !$TQACC->Fullname_IsDenied($fld_v) ) {
                            $iv = TRUE;
                        }
                    }
                break;
            case "borndate" :
//                        var_dump(preg_match($this->rgx_bd, "02-02-91"));
                    
//                        var_dump($fld_v);
//                        $fld_v = "02-28-2002";
                        //On vérifie si l'age limite est atteinte 
                        $bd_d = intval(explode("-", $fld_v)[1]);
                        $bd_m = intval(explode("-", $fld_v)[0]);
                        $bd_y = intval(explode("-", $fld_v)[2]);
                        
//                        var_dump($bd_d,$bd_m,$bd_y);
//                    if ( preg_match($this->rgx_bd, $fld_v) ) {    
                    if ( checkdate($bd_m,$bd_d,$bd_y) ) {    
                        $f__ = intval($bd_y)+$this->bd_limit;
                        $gt = mktime(0, 0, 0, $bd_m, $bd_d, $f__);
                        $now = (new DateTime())->getTimestamp();
                        
                        $df = $now - $gt ;
                        
                        if ( $df > 0 ) {
                            $iv = TRUE;
                        }
                    }
                break;
            case "gender" :
                    if ( preg_match($this->rgx_gdr, $fld_v) ) {
                        $iv = TRUE;
                    }
                break;
            case "city" :
                    //On vérifie si l'identifiant est connu de la base de donnnées
                    if ( $this->CheckCityById($fld_v) ) {
                        $iv = TRUE;
                    }
                break;
            case "pseudo" :
                    //On utilise une des fonctions de TQRACC. Plutot que la recréer
                    $TQACC = new TQR_ACCOUNT();
                    if ( preg_match($this->rgx_psd, $fld_v) ) {
                        //On vérifie si le pseudo est disponible
                        if ( !$this->PullPseudo($fld_v) && !$TQACC->Pseudo_IsReserved($fld_v) && !$TQACC->Pseudo_IsDenied($fld_v) ) {
                            $iv = TRUE;
                        }
                    }
                break;
            case "email" :
                    //L'email respect-il le format d'un email
                    if ( preg_match($this->rgx_email, $fld_v) ) {
                        //On vérifie si l'email est disponible
                        $b__ = $this->PullEmail($fld_v);
                        if (! $b__ ) {
                            //On vérifie si le domaine lié est accessible et qu'il n'est pas banni
                            if ( $this->CheckEmailDomDns($fld_v) && !$this->IsEmailDomBan($fld_v) ) {
                                $iv = TRUE;
                            } 
                        }
                    }
                break;
            case "password" :
                    if ( preg_match($this->rgx_pwd, $fld_v) ) {
                        $iv = TRUE;
                    }
                break;
            default:
                break;
        }
        
        return $iv;
    }
    
    
    public function CreateNewAccount ($args) {
        /*
         * Gère l'opération de création de compte.
         * La méthode permet aussi de controler une dernière fois le formulaire avant de procéder à la création.
         * 
         * La création permet de créer un compte au niveau de la base ACCOUNT puis une copie au niveau de la base PRODUCT.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $cr_t_k = [
            "ufn"  => "ins_fn",
            "ubd"  => "ins_nais",
            "ugdr" => "ins_gdr",
            "ucy"  => "ins_cty",
            "upsd" => "ins_psd",
            "ueml" => "ins_eml",
            "upwd" => "ins_pwd",
            "locip" => "locip"
        ];
        
        $chk_t_k = [
            "ufn"  => "fullname",
            "ubd"  => "borndate",
            "ugdr" => "gender",
            "ucy"  => "city",
            "upsd" => "pseudo",
            "ueml" => "email",
            "upwd" => "password",
        ];
        
        $chk_prop = $cr_prop = $errs = [];
        
//        ["ufn","ubd","ugdr","ucy","upsd","ueml","upwd"];
//        ["ins_fn","ins_nais","ins_gdr","ins_cty","ins_psd","ins_eml","ins_pwd","locip"]
        
        /*
         * On vérifie que les champs sont valides. 
         * On en profite pour créer un tableau avec les propriétés qui seront utilisées pour la création du compte.
         */
        foreach ( $args as $k => $v ) {
            if ( $k !== "locip" ) {
                //On crée les bons identifiants
                $x1 = $chk_t_k[$k];
                $chk_prop[$x1] = $v;
                
                //On vérifie la validité des champs
                $iv = $this->CheckField($x1, $v);
                if (! $iv ) {
                    $errs[] = $x1;
                }
            }
            
            $x2 = $cr_t_k[$k];
            $cr_prop[$x2] = $v;
            
        }
        
        if ( $errs ) {
            return $errs;
        }
        
        //On vérifie qu'on a toutes les données nécessaires à l'inscription
        $com = array_intersect(array_keys($cr_prop), $this->needed_to_create_prop_keys);
        
        if ( count($com) !== count($this->needed_to_create_prop_keys) ) {
//            var_dump($com,$this->needed_to_create_prop_keys);
            return -1;
        }
//        "ueml" => "ins_eml",
                
        //*** On procède à l'inscription au niveau de la base de données ACCOUNT ***//
        
        //Création de l'occurrence d'email
        $this->InsertEmail($cr_prop["ins_eml"]);
        
        //Création du compte
        $this->CreateAccount_ACCOUNT();
        
        //
        $QO = new QUERY("qryl4insn13");
        $params = array(
            ":ufn" => $cr_prop["ins_fn"],
            ":udb" => $cr_prop["ins_nais"], 
            ":ubd_stp" => $cr_prop[""], 
            ":ulvcty" => $cr_prop["ins_cty"], 
            ":ugdr" => $cr_prop["ins_gdr"], 
            ":upsd" => $cr_prop["ins_psd"], 
            ":ulng" => $cr_prop["fr"], 
            ":upwd" => $cr_prop["ins_pwd"], 
            ":clocip" => $cr_prop["locip"], 
            ":cdate" => $cr_prop[""], 
            ":cdate_stp" => $cr_prop[""]
        );
        $uid = $QO->execute($params);
        
        //On procède à l'inscription au niveau de la base de données PRODUCT
        return TRUE;
    }
    
    /**************************************************************************************************************************************/
    /************************************************************ PRIVATE SSCOPE **********************************************************/
    
    private function CheckCityById ($id) {
        /*
         * Permet de vérifier si une ville existe en se fiant à son identifiant
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $datas;
        $QO = new QUERY("qryl4insn9");
        $params = array(":id" => $id);
        $datas = $QO->execute($params);
        
        if ( $datas )
            return $datas[0];
        else 
            return FALSE;

    }
    
    
    private function IsValidEmail ($em) {
        /*
         * Permet de vérifier si l'email passé en paramètre est valide.
         * Pour cela, on vérifie si 
         *  (1) Il s'agit d'une chaine de caractères
         *  (2) Il s'agit d'un email (format)
         * 
         * La méthode renvoie une erreur de type volatile en cas de non conformité ou TRUE.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! is_string($em) ) {
            return FALSE;
        } else if ( count($em) > $this->email_max ) {
            return "__ERR_VOL_MAX";
        } else if (! preg_match($this->rgx_email, $em) ) {
            return "__ERR_VOL_MISM";
        }
            
        return TRUE;    
    }
    
    private function IsValidPseudo ($psd) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        /*
         * Permet de vérifier si l'email passé en paramètre est valide.
         * Pour cela, on vérifie si 
         *  (1) Il s'agit d'une chaine de caractères
         *  (2) Il s'agit d'un pseudo (format)
         * 
         * La méthode renvoie une erreur de type volatile en cas de non conformité ou TRUE.
         */
        
        if (! is_string($psd) ) {
            return FALSE;
        } else if (! preg_match($this->rgx_psd, $psd) ) {
            if ( count($psd) < $this->psd_min ) {
                return "__ERR_VOL_MIN";
            } else if ( count($psd) > $this->psd_max ) {
                return "__ERR_VOL_MAX";
            } else {
                return "__ERR_VOL_MISM";
            }
        }
            
        return TRUE;    
    }
    
    private function InsertEmail ($email) {
        /*
         * Insère l'email dans la table archivant les emaux.
         * 
         * La méthode ne vérifie pas le format de l'email ni même son existence.
         * Cela doit être fait par CALLER.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $login = explode("@", $email)[0];
        $dom = explode("@", $email)[1];
        $now = round(microtime(TRUE)*1000);
        
//        var_dump($login,$dom,$now);
//        exit();
        
        $QO = new QUERY("qryl4insn10");
        $params = array(
            ":eml_raw" => $email,
            ":eml_login" => $login, 
            ":eml_dom" => $dom, 
            ":tstamp" => $now
        );
        $QO->execute($params);
        
        return TRUE;
    }
    
    private function CreateAccount_ACCOUNT ($args) {
        /*
         * Permet de créer une occurrence de COMPTE dans la base de données ACCOUNT.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $bd_d = intval(explode("-", $args["ins_nais"])[1]);
        $bd_m = intval(explode("-", $args["ins_nais"])[0]);
        $bd_y = intval(explode("-", $args["ins_nais"])[2]);
        $bd_stp = mktime(0, 0, 0, $bd_m, $bd_d, $bd_y);
        $now = round(microtime(TRUE)*1000);
        
//        var_dump($args["ins_nais"],$bd_d,$bd_m,$bd_y,$bd_stp);
//        exit();
        
        $QO = new QUERY("qryl4insn11");
        $params = array(
            ":ufn" => $args["ins_fn"],
            ":udb" => $args["ins_nais"], 
            ":ubd_stp" => $bd_stp, 
            ":ulvcty" => $args["ins_cty"], 
            ":ugdr" => $args["ins_gdr"], 
            ":upsd" => $args["ins_psd"], 
            ":ulng" => $args["fr"], 
            ":upwd" => $args["ins_pwd"], 
            ":clocip" => $args["locip"], 
            ":cdate_stp" => $now
        );
        $uid = $QO->execute($params);
        
        
        //Conversion de l'id en eid ...
//        $ueid = ;
        
        //.. et modification de la table
        $QO = new QUERY("qryl4insn12");
        $params = array(
            ":uid" => $uid,
            ":udb" => $ueid, 
        );
        $uid = $QO->execute($params);
        
        return $uid;
    }
    
    
}
