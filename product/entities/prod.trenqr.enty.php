<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of prod
 *
 * @author arsphinx
 */
class TRENQR extends MOTHER {
    
    private $prodconf_xmlscope;
    private $is_conf_ready;
    
    private $ups_params_separator;
    private $ups_couple_separator;
    
    
    /********** RULES ************/
    private $rgx_fn;
    private $rgx_fn30;
    private $rgx_bd;
    private $rgx_gdr;
    private $rgx_psd;
    private $rgx_email;
    private $email_max;
    private $rgx_pwd;
    private $INS_DEFAULT_LNG;
    private $INS_DEFAULT_GRP;
    private $rgx_lng;
    private $TQR_AVAL_LANG;
    private $bgzy_type;
    private $rgx_bgzy_whr;
    private $rgx_bgzy_whn;
    private $rgx_bgzy_msg;
    
    private $BGZY_DFLT_SDR;
    private $BGZY_DFLT_RCV;
    
    private $PRF_OPDEC_TAB;
    private $PRF_OPDEC_TYPES_I;
    
   /*
    * [DEPUIS 26-10-15] @author BOR
    *   La liste des IPs considérées comme faisant partie des adresses IP de serveur de PRODUCTION.
    */
    private $SUGG_IP_PROD_LIST;
    private $SUGG_PFL_LMT;
    private $SUGG_TRD_LMT;
    
    /*
     * [DEPUIS 05-11-15] @author BOR
     */
    private $LASTA_DFT_LMT;
    
   /*
    * [DEPUIS 20-10-15] @author BOR
    */
    static $RECAPTCHA_SITEKEY = "6LeA8Q0TAAAAAPqg7YU02r1qzm3X_UFjHEAB2mbk";
    static $RECAPTCHA_SECRET = "6LeA8Q0TAAAAAPs9rgJIzu3ofXFs907T7vGaPilF";
    
   /*
    * [DEPUIS 05-02-16] @author BOR
    */
    static $TQR_PAGE = [
        "TMLNR"     => 1,
        "TRPG"      => 2,
        "FKSA"      => 3,
        "HVIEW"     => 4
    ];
    static $TQR_APP = [
        "NWFD"      => 1,
        "PSMN"      => 2,
        "FRDC"      => 3,
        "SRHBX"     => 4,
        "CHBX"      => 5,
        "LASTA"     => 6,
        "DSCV_XPLR" => 7,
        "DSCV_MYST" => 8,
        "DSCV_PQR"  => 9
    ];
    
    static $TQR_VIEWER = [
        "ARP"           => 1,
        "UNQ_AR"        => 2,
        "UNQ_TST"       => 3,
        "UNQ_AR_CLCMD"  => 4
    ];
    
    function __construct($prod_xmlscope = NULL) {
        parent::__construct(__FILE__,__CLASS__);
        
        if ( isset($prod_xmlscope) && is_array($prod_xmlscope) && count($prod_xmlscope) ) {
            $this->prodconf_xmlscope = $prod_xmlscope;
            
            $this->is_conf_ready = TRUE;
        } else {
            $this->is_conf_ready = FALSE;
        }
        
        /**************** RULES ****************/
        $this->rgx_fn = "/^[a-z-\. ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,25}$/i";
        $this->rgx_fn30 = "/^[a-z-\. ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,30}$/i";
        $this->rgx_bd = "/^(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/";
        $this->rgx_gdr = "/^(f|m)$/i";
        $this->rgx_psd = "/^[\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20}$/i";
        $this->rgx_email = "/^[\w.+-]+@(?:[\w\d-]+\.)+[a-z]{2,6}$/i";
        $this->email_max = 256;
        $this->rgx_pwd = "/^(?=(.*\d))(?=.*[a-z])(?=.*[²&<>!.?+*_~µ£^¨°()\[\]\-@#$%:;=''\/\\¤]).{6,32}$/i";
        $this->INS_DEFAULT_LNG = "fr";
        $this->INS_DEFAULT_GRP = 2;
        $this->rgx_lng = "/^[a-z]{2}$/i";
        $this->TQR_AVAL_LANG = ["fr"];
        $this->bgzy_type = ["BGTYP_CNX","BGTYP_SSN","BGTYP_VW","BGTYP_DT","BGTYP_SEC","BGTYP_PRF","BGTYP_PFL","BGTYP_NAV","BGTYP_SRH","BGTYP_ART","BGTYP_TRD","BGTYP_BGZY","BGTYP_OTHER"];
        $this->rgx_bgzy_whr = "/^(?=.*[a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]).{8,70}$/i";
        $this->rgx_bgzy_whn = "/^(?=.*[a-z\dÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]).{4,70}$/i";
        $this->rgx_bgzy_msg = "/^(?=.*[a-z])[\s\S]{100,1000}$/i";
        $this->BGZY_DFLT_SDR = "Trenqr <noreply@trenqr.com>";
        $this->BGZY_DFLT_RCV = "support.technical@trenqr.com";
        
        
        /****** REC ******/
        
//        $this->TDL_INTVAL = 14*86400000;
//        $this->REC_INTVAL = 1*86400000;
        
        /****** PREFERENCES *******/
        
        $this->PRF_OPDEC_TAB = [
            "_PFOP_TIABT_INR"   => ["_DEC_DSMA"],
            "_PFOP_CHTBX_ISXPM" => ["_DEC_DSMA"],
            "_PFOP_FSTCNX"      => ["_DEC_DSMA"],
            /*
             * [DEPUIS 25-05-16]
             */
            "_PFOP_BRAIN_ALWZ_OPN"          => ["_DEC_ENA","_DEC_DISA"],
            "_PFOP_PSMN_EMLWHN_NW"          => ["_DEC_ENA","_DEC_DISA"],
            "_PFOP_PSMN_EMLFOR_WKACTY"      => ["_DEC_ENA","_DEC_DISA"],
            "_PFOP_INFO_EMLWHN_NWTQRVnSECU" => ["_DEC_ENA","_DEC_DISA"],
            "_PFOP_INFO_EMLFOR_WKBESTPUB"   => ["_DEC_ENA","_DEC_DISA"],
            /*
             * [DEPUIS 28-06-16]
             */
            "_PFOP_PG_INS_INTRO_GDPLYR"     => ["_DEC_YES","_DEC_NOPE"],
            "_PFOP_PG_INS_INTRO_FSIDE"      => ["_DEC_CSTM_PG_INS_FSIDE_DARK","_DEC_CSTM_PG_INS_FSIDE_LIGHT"]
        ];
        
        $this->PRF_OPDEC_TYPES_I = [
            "_DEC_DFLT" => 1,
            "_DEC_YES"  => 2,
            "_DEC_NOPE" => 3,            
            "_DEC_ENA"  => 4,
            "_DEC_DISA" => 5,
            "_DEC_LCK"  => 6,
            "_DEC_ULCK" => 7,
            "_DEC_DSMA" => 8,
            "_DEC_CSTM_PG_INS_FSIDE_DARK"   => 100,
            "_DEC_CSTM_PG_INS_FSIDE_LIGHT"  => 101,
        ];
        
        $this->SUGG_IP_PROD_LIST = ["37.59.53.98"];
//        $this->SUGG_PFL_LMT = 4;
        $this->SUGG_PFL_LMT = 3;
        $this->SUGG_TRD_LMT = 2;
        
        $this->LASTA_DFT_LMT = 3;
    }
    
    public function Trenqr_GetVersionInfos ($verid) {
        if ( empty($verid) ) {
            return;
        }
        
        //On récupère les données relatives à la version
        $QO = new QUERY("qryl4tqrvern1");
        $params = array(":verid" => $verid);
        $prod_tab = $QO->execute($params);
        
        if (! $prod_tab ) {
            return FALSE;
        } else {
            return $prod_tab[0];
        }
    }
    
    public function Trenqr_TranslateRm ($sts, $lg = "fr") {
        if ( !isset($sts) || $sts === "" ) {
            return;
        }
        
        $sts = intval($sts);
        switch ($sts) {
            case 0:
                    $rm_dtxt = "_Stopped";
                break;
            case 1:
                    $rm_dtxt = "_InDev";
                break;
            case 2:
                    $rm_dtxt = "_Testing";
                break;
            case 3:
                    $rm_dtxt = "_Running";
                break;
            case 4:
                    $rm_dtxt = "_Maintenance";
                break;
            default:
                    return FALSE;
                break;
        }
        
        $TXTH = new TEXTHANDLER();
        $rm_dtxt = $TXTH->get_deco_text($lg, $rm_dtxt);
        
        return $rm_dtxt;
    }
            
    private function populate() {
        //TODO : Permet de récupérer les données sur le produit et la version en cours auprès de la base de données
    }
    
    public function explode_tqr_url ($url, $cmp_params_sep = NULL, $cmp_cpl_sep = NULL) {
        /*
         * Renvoie un tableau contenant: USER (Si existe), PAGE, URQ, UPS.
         */
        if (! ( isset($url ) && is_string($url) && $url !== "" ) ) {
            return;
        }
        
        /*
         * ETPAE :
         *      On decode au cas où il y aurait des accents dans l'URL
         */
        $url = urldecode($url);
        if (! $this->is_conf_ready ) {
            return "__ERR_VOL_NOT_COMPLY";
        }
        
        if ( !empty($cmp_params_sep) ) {        
            $this->prodconf_xmlscope["default_params_separator"] = $cmp_params_sep;
        }
        if ( !empty($cmp_cpl_sep) ) {       
            $this->prodconf_xmlscope["default_couple_separator"] = $cmp_cpl_sep;
        }
        
        $url_pieces = $tmp_pieces = $final_url = $user = $url_matches = $ups = $hcompl = NULL;
        
        $tmp = parse_url($url); 
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->prodconf_xmlscope["prod_hosts"]);
        if ( !empty($tmp["scheme"]) && in_array(strtolower($tmp["host"]),$this->prodconf_xmlscope["prod_hosts"]) && !empty($tmp["path"]) ) {
            /*
            //On découpe le chemin
            $tmp_path = explode("/",$tmp["path"]);
            
            //On vérifie si le premier élément est un host_complement. 
            if ( in_array(strtolower($tmp_path[1]),$this->prodconf_xmlscope["prod_host_compls"]) ) {
                $tmp_pieces = array_slice($tmp_path, 2);
                $final_url = implode("/",  array_slice($tmp_path, 2));
                $final_url = "/".$final_url;
            } else {
                $tmp_pieces = array_slice($tmp_path, 1);
                $final_url = $tmp["path"];
            }
            
            if ( count($tmp_pieces) === 1 ) {
                //On est dans le cas où l'utlisateur n'a fourni que le pseudo
                $user = $tmp_pieces[0];
                $tmp_pieces = $tmp_path[1];
                $final_url = $tmp["path"];
            } else {
                
//                var_dump($tmp_pieces,);
//                exit();

                //On vérifie si on est dans le cas où l'utilisateur n'est pas fourni
                if ( in_array(strtolower($tmp_pieces[0]), ["trend"]) ) {
                    $user = NULL;
                    //TODO : Récupérer les autres données sur la Tendance dans l'URL
                } else {
                    
                    if ( $final_url[0] === "/" ){
                        $final_url = substr($final_url, 1);
                    }
                    
//                    var_dump($final_url,preg_match_all("#^ajax/r/w=([\w\-\d]{2,30})&ups=([\w\-\d_\.]{3,})#i", $final_url, $url_matches));
//                    exit();
                    
                    if ( preg_match_all("#^ajax/r/w=([\w\-\d]{2,30})&ups=([\w\-\d_\.\=]{3,})#i", $final_url, $url_matches) ) {
                        $ups = $this->format_composed_url_params($url_matches[2][0]);
                        $user = ( key_exists("u", $ups) && isset($ups["u"]) ) ? $ups["u"] : NULL;
                    } else if ( strtolower($tmp_pieces[1]) === "timeline" ) {
                        $user = $tmp_pieces[0];
                    }
                    
                }
                    
            }
            //*/
            
            //On découpe le chemin
            $tmp_path = explode("/",$tmp["path"]);
            
            //On vérifie si le premier élément est un host_complement. 
            if ( in_array(strtolower($tmp_path[1]),$this->prodconf_xmlscope["prod_host_compls"]) ) {
                $hcompl = $tmp_path[1];
                $tmp_pieces = array_slice($tmp_path, 2);
                $final_url = implode("/",  array_slice($tmp_path, 2));
                $final_url = "/".$final_url;
            } else {
                $tmp_pieces = array_slice($tmp_path, 1);
                $final_url = $tmp["path"];
            }
            
            //On retire la barre de début si elle existe
            /*
             * [DEPUIS 19-04-16]
             *      Il peut arriver qu'il y ait plus de / qu'on en voudrait en début de chaine.
             *      On boucle pour les retirer.
             */
            do {
                if ( $final_url[0] === "/" ) {
                    $final_url = substr($final_url, 1);
                }
            } while ( $final_url[0] === "/" && $final_url && strlen($final_url) );
            if (! $final_url ) {
                return "__ERR_VOL_FAILED";
            }

            
            /*
             * [DEPUIS 24-11-15] @author BOR
             *      On retire la partie qui concerne EMAIL_CONFIRM
             */
            if ( preg_match("#(\/ec\/case=.+)#i", $final_url) ) {
                $string = $final_url;
                $pattern = '#(\/ec\/case=.+)#i';
                $replacement = "";
                $final_url = preg_replace($pattern, $replacement, $string);
            }
            
            
            if ( preg_match("#^(?:ontrenqr|)(?:&v=1m30)?$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "ontrenqr", // DEFAULT
                    "urqid"             => "ontrenqr",
                    "ups_raw" => [
//                        "tei" => $url_matches[1],
                    ]
                ];
            } 
            /*
             * [DEPUIS 30-05-16]
             */
            else if ( preg_match("#(connexion|login)#i", $final_url, $url_matches) ) {
                
                if ( isset($tmp["query"]) && preg_match("#redir_affair=([\w-]{2,25})&redir_url=(.+)#i", $tmp["query"], $query_matches) ) {
                    $url_pieces = [
                        "url"               => $url,
                        "host"              => $tmp["host"],
                        "ori_qry_string"    => $tmp["path"],
                        "new_qry_string"    => $final_url,
                        "hcompl"            => $hcompl,
                        "user"              => NULL,
                        "page"              => $url_matches[1],
                        "urqid"             => "cnx", 
                        "ups_raw" => [
                            "redir_affair"  => $query_matches[1],
                            "redir_url"     => $query_matches[2],
                        ]
                    ];
                } else {
                    $url_pieces = [
                        "url"               => $url,
                        "host"              => $tmp["host"],
                        "ori_qry_string"    => $tmp["path"],
                        "new_qry_string"    => $final_url,
                        "hcompl"            => $hcompl,
                        "user"              => NULL,
                        "page"              => $url_matches[1],
                        "urqid"             => "cnx", 
                        "ups_raw" => [
                            
                        ]
                    ];
                }
                
            } else if ( preg_match("#^(?:apropos|about)$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "FAQ_GTPG_ABOUT", // DEFAULT
                    "urqid"             => "FAQ_GTPG_ABOUT",
                    "ups_raw" => [
//                        "tei" => $url_matches[1],
                    ]
                ];
            } else if ( preg_match("#^cookies$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "FAQ_GTPG_COOKIES", // DEFAULT
                    "urqid"             => "FAQ_GTPG_COOKIES",
                    "ups_raw" => [
//                        "tei" => $url_matches[1],
                    ]
                ];
            } else if ( preg_match("#^(?:cgu|terms)$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "FAQ_GTPG_TERMS", // DEFAULT
                    "urqid"             => "FAQ_GTPG_TERMS",
                    "ups_raw" => [
//                        "tei" => $url_matches[1],
                    ]
                ];
            } else if ( preg_match("#^(?:confidentialite|privacy)$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "FAQ_GTPG_PRIVACY", // DEFAULT
                    "urqid"             => "FAQ_GTPG_PRIVACY",
                    "ups_raw" => [
//                        "tei" => $url_matches[1],
                    ]
                ];
            } else if ( preg_match("#^(?:mentionslegales|legals)$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "FAQ_GTPG_LEGALS", // DEFAULT
                    "urqid"             => "FAQ_GTPG_LEGALS",
                    "ups_raw" => [
//                        "tei" => $url_matches[1],
                    ]
                ];
            } else if ( preg_match("#^\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,50})$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => $url_matches[1],
                    "page"              => "profil", // DEFAULT
                    "urqid"             => "TMLNR_GTPG", // DEFAULT
                    "ups_raw"           => NULL
                ];
            } else if ( preg_match("#^\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,50})/explore$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => $url_matches[1],
                    "page"              => "profil", // DEFAULT
                    "urqid"             => "TMLNR_GTPG", // DEFAULT
                    "ups_raw"           => NULL
                ];
            } else if ( preg_match("#^\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})&as=(?:chezmoi|athome)$#i", $final_url, $url_matches)
                    || preg_match("#^\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})/(?:aboutme|apropos)&as=(?:chezmoi|athome)$#i", $final_url, $url_matches) 
                    || preg_match("#^\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})/(?:trends|tendances)&as=(?:chezmoi|athome)$#i", $final_url, $url_matches) 
                    || preg_match("#^\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})/(?:favorite|favoris)&as=(?:chezmoi|athome)$#i", $final_url, $url_matches) ) {
//            } else if ( preg_match("#^\@?([\w]{2,25})/(?:chezmoi|athome)$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => $url_matches[1],
                    "page"              => "profil", // DEFAULT
                    "urqid"             => "TMLNR_GTPG_RO",
                    "ups_raw"           => NULL
                ];
            } else if ( preg_match("#^\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})&as=(?:visiting)$#i", $final_url, $url_matches)
                    || preg_match("#^\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})/(?:aboutme|apropos)&as=(?:visiting)$#i", $final_url, $url_matches) 
                    || preg_match("#^\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})/(?:trends|tendances)&as=(?:visiting)$#i", $final_url, $url_matches) 
                    || preg_match("#^\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})/(?:favorite|favoris)&as=(?:visiting)$#i", $final_url, $url_matches)  ) {
//            } else if ( preg_match("#\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})/(?:visiting)$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => $url_matches[1],
                    "page"              => "profil", // DEFAULT
                    "urqid"             => "TMLNR_GTPG_RU",
                    "ups_raw"           => NULL
                ];
            } else if ( preg_match("#^\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})&as=(?:public)$#i", $final_url, $url_matches)
                    || preg_match("#^\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})/(?:aboutme|apropos)&as=(?:public)$#i", $final_url, $url_matches) 
                    || preg_match("#^\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})/(?:trends|tendances)&as=(?:public)$#i", $final_url, $url_matches) 
                    || preg_match("#^\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})/(?:favorite|favoris)&as=(?:public)$#i", $final_url, $url_matches)  ) {
//            } else if ( preg_match("#\@?([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})/(?:public)$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => $url_matches[1],
                    "page"              => "profil", // DEFAULT
                    "urqid"             => "TMLNR_GTPG_WLC",
                    "ups_raw"           => NULL
                ];
            } else if ( preg_match("#^\@?([\w\-]{2,50})/timeline/w=([\w\-]+)$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => $url_matches[1],
                    "page"              => "profil", // DEFAULT
                    "urqid"             => $url_matches[2],
                    "ups_raw"           => NULL
                ];
            } else if ( preg_match("#^\@?([\w\-]{2,50})/([\w\-]{2,50})/w=([\w\-]+)$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => $url_matches[1],
                    "page"              => $url_matches[2],
                    "urqid"             => $url_matches[3],
                    "ups_raw"           => NULL
                ];
            } elseif ( preg_match("#^\@?([\w\-]{2,50})/([\w\-]{2,50})/w=([\w\-]+)&ups=[^.]([\w\.\=\@\-ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż]{3,})$#i", $final_url, $url_matches) ) {
                $ups_tab = $this->format_composed_url_params($url_matches[4]);
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $ups_tab) ) {
                    return FALSE;
                }
                
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => $url_matches[1],
                    "page"              => $url_matches[2],
                    "urqid"             => $url_matches[3],
                    "ups_raw"           => $url_matches[4],
                    "ups_tab"           => $ups_tab
                ];
            } else if ( preg_match("#^(?:trend|tendance)/([\w\-]{2,50})/([\w\-ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż]{2,200})$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "trend", // DEFAULT
                    "urqid"             => "TRPG_GTPG",
                    "ups_raw" => [
                        "tei" => $url_matches[1],
                        "tle" => $url_matches[2]
                        /*
                        "tei" => $url_matches[0],
                        "tle" => $url_matches[1]
                         */
                    ]
                ];
            } else if ( preg_match("#^(?:trend|tendance)/([\w\-]{2,50})/([\w\-ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż]{2,200})&as=manager$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "trend", // DEFAULT
                    "urqid"             => "TRPG_GTPG_RO",
                    "ups_raw" => [
                        "tei" => $url_matches[1],
                        "tle" => $url_matches[2]
                        /*
                        "tei" => $url_matches[0],
                        "tle" => $url_matches[1]
                         */
                    ]
                ];
            } else if ( preg_match("#^^(?:trend|tendance)/([\w\-]{2,50})/([\w\-ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż]{2,200})&as=contributor$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "trend", // DEFAULT
                    "urqid"             => "TRPG_GTPG_RFOL",
                    "ups_raw" => [
                        "tei" => $url_matches[1],
                        "tle" => $url_matches[2]
                        /*
                        "tei" => $url_matches[0],
                        "tle" => $url_matches[1]
                         */
                    ]
                ];
            } else if ( preg_match("#^^(?:trend|tendance)/([\w\-]{2,50})/([\w\-ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż]{2,200})&as=me$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "trend", // DEFAULT
                    "urqid"             => "TRPG_GTPG_RU",
                    "ups_raw" => [
                        "tei" => $url_matches[1],
                        "tle" => $url_matches[2]
                        /*
                        "tei" => $url_matches[0],
                        "tle" => $url_matches[1]
                         */
                    ]
                ];
            } else if ( preg_match("#^^(?:trend|tendance)/([\w\-]{2,50})/([\w\-ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż]{2,200})&as=visitor$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"       => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "trend", // DEFAULT
                    "urqid"             => "TRPG_GTPG_WLC",
                    "ups_raw" => [
                        "tei" => $url_matches[1],
                        "tle" => $url_matches[2]
                        /*
                        "tei" => $url_matches[0],
                        "tle" => $url_matches[1]
                         */
                    ]
                ];
                
//            } else if ( preg_match("#^(?:f|article)/([\w\-_]{5,50})$#i", $final_url, $url_matches) ) { //[DEPUIS 13-05-16]
            } else if ( preg_match("#^f/([\w\-_]{5,50})$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"       => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "focus", // DEFAULT
                    "urqid"             => "FKSA_GTPG",
                    "ups_raw" => [
                        "atypi" => "photo",
                        "aplki" => $url_matches[1]
                    ]
                ];
            /*
             * [DEPUIS 18-05-16]
             */
            } else if ( preg_match("#^f\/(sts|vid)\/([\w\-_]{5,50})$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"       => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "focus", // DEFAULT
                    "urqid"             => "FKSA_GTPG",
                    "ups_raw" => [
                        "atypi" => ( $url_matches[1] === "sts" ) ? "testy" : "video",
                        "aplki" => $url_matches[2]
                    ]
                ];
//            } else if ( preg_match("#^(?:f|article)/([\w\-_]{5,50})/&vwopt=([\w\_,]{2,50})$#i", $final_url, $url_matches) ) { //[DEPUIS 13-05-16]
            } else if ( preg_match("#^f/([\w\-_]{5,50})/&vwopt=([\w\_,]{2,50})$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"       => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "focus", // DEFAULT
                    "urqid"             => "FKSA_GTPG",
                    "ups_raw" => [
                        "atypi" => "photo", //[DEPUIS 16-05-16]
                        "aplki" => $url_matches[1],
                        "vwopt" => $url_matches[2]
                    ]
                ];
            /*
             * [DEPUIS 18-05-16]
             */
            } else if ( preg_match("#^f\/(sts|vid)\/([\w\-_]{5,50})/&vwopt=([\w\_,]{2,50})$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"       => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "focus", // DEFAULT
                    "urqid"             => "FKSA_GTPG",
                    "ups_raw" => [
                        "atypi" => ( $url_matches[1] === "sts" ) ? "testy" : "video",
                        "aplki" => $url_matches[2],
                        "vwopt" => $url_matches[3]
                    ]
                ];
            } else if ( preg_match("#^hview/q=([a-z\d_ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]+)&src=hash$#i", $final_url, $url_matches) ) {
                $url_pieces = [
                    "url"       => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => NULL,
                    "page"              => "hview", // DEFAULT
                    "urqid"             => "TQR_GTPG_HVIEW",
                    "ups_raw" => [
                        "q" => $url_matches[1]
                    ]
                ];
            } else if ( preg_match("#^ajax/r/w=([\w\-]{2,30})&ups=[^.]([\w\.\=\@\-ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż]{3,})#i", $final_url, $url_matches) ) {
                
                $ups_tab = $this->format_composed_url_params($url_matches[2]);
                if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $ups_tab) ) {
                    return "__ERR_VOL_TRANS_FAILED";
                }
                    
                $user = (! ( key_exists("u", $ups_tab) && !empty ($ups_tab["u"]) ) ) ? NULL : $ups_tab["u"];
                
                $url_pieces = [
                    "url"               => $url,
                    "host"              => $tmp["host"],
                    "ori_qry_string"    => $tmp["path"],
                    "new_qry_string"    => $final_url,
                    "hcompl"            => $hcompl,
                    "user"              => $user,
                    "page"              => "profil",
                    "urqid"             => $url_matches[1],
                    "ups_raw"           => $url_matches[2],
                    "ups_tab"           => $ups_tab
                ];
            } else {
                $this->presentVarIfDebug(__FUNCTION__,__LINE__, $final_url);
                return "__ERR_VOL_UKW_URL_FRMT";
            }
            
            $url_pieces["protocol"] = $tmp["scheme"];

            return $url_pieces;
            
        } else {
            return "__ERR_VOL_BAD_URL";
        }
        
        return $url_pieces;
    }
    
    private function format_composed_url_params($entry) {
        $default_params_separator = $this->prodconf_xmlscope["default_params_separator"];
        $default_couple_separator = $this->prodconf_xmlscope["default_couple_separator"];

        $couples_of_params = explode($default_params_separator,$entry);
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__,$couples_of_params);
//            $this->presentVarIfDebug(__FUNCTION__,__LINE__,$this->prodconf_xmlscope,$default_params_separator,$default_couple_separator);

        if ( isset($couples_of_params) and count($couples_of_params) > 0 )
        {
            $params_table = array();
            foreach ($couples_of_params as $couple) {
                $key_value_couple = explode($default_couple_separator,$couple);
//                    var_dump(count($key_value_couple));
//                    $this->presentVarIfDebug(__FUNCTION__,__LINE__,$key_value_couple);
                if ( isset($key_value_couple) and count($key_value_couple) == 2 ) {
                    $params_table[$key_value_couple[0]] = $key_value_couple[1];                        
                } else return "__ERR_VOL_FAILED";
            }
            return $params_table;
        }//La fonction explode, si elle ne trouve pas le separateur elle revoie un tableau de 1 element, cet elecment est le paramètre qui lui est envoyé. Aussi, c'est impossible que count soit ==0        
    }
    
    /******************************************************************************************************************************************************/
    /********************************************************************* REPORT BUG *********************************************************************/
    
    public function ReportBug ($args) {
        /*
        * Permet de valider la mise à jout des données de profil envoyées par FE - Gestion de compte
        */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        
        $XPTD = ["accid","bgzy_type","bgzy_where","bgzy_when","bgzy_message","bgzy_lang","bgzy_url","bgzy_scrn_w","bgzy_scrn_h","ssid","srvip","srvname","user_agent","locip"];

        //On vérifie la présence des données obligatoires
        $com = array_intersect( array_keys($args), $XPTD);

        if ( count($com) != count($XPTD) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__, ["EXPECTED => ",$XPTD],'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,["WE GOT => ", $args],'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if (! ( !is_array($v) && !empty($v) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,[$k,$v],'v_d');
//                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                }
            }
        }
        
        //Permet de mettre le fuseau horaire à +0 afin d'avoir un TIMESTAMP correct
        date_default_timezone_set('UTC');
        
        //On vérifie que le compte existe et est actif
        $TQACC = new TQR_ACCOUNT(); 
        $acc_tab = $TQACC->exists_with_id($args["accid"]);
        if ( !$acc_tab || intval($acc_tab["acc_todelete"]) !== 0 ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$acc_tab,'v_d');
            return "__ERR_VOL_U_G";
        } 
        
        //On vérifie que les données sont sécurisées et fiables
        $wrg_datas = [];
        foreach ( $args as $k => $v ) {
            /*
             * Valider l'URL n'est pas assez simple pour le temps de travail qui me reste.
             * La donnée est censée venir d'une source sure. La probabilité qu'il s'agit d'un hack étant faible, on peut passer !
             */
            if ( in_array($k, ["locip","accid","bgzy_url","ssid","srvip","srvname","user_agent"]) ) {
                continue;
            } else {
                if (! $this->CheckField($k,$v) ) {
//                    $wrg_datas[] = [$k,$v];
                    $wrg_datas[] = $k;
                }
            }
        }
        if ( count($wrg_datas) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$wrg_datas,'v_d');
            return ["FAILED",$wrg_datas];
        }
//        var_dump($args);
//        return;
        
        $now = round(microtime(TRUE)*1000);
        //On insert les données dans la table
        $QO = new QUERY("qryl4bgzyn1");
        $params = array( 
            ":accid"        => $args["accid"],
            ":type"         => $args["bgzy_type"],
            ":fgvn_where"   => $args["bgzy_where"],
            ":fgvn_when"    => $args["bgzy_when"],
            ":message"      => $args["bgzy_message"],
            ":lang"         => $args["bgzy_lang"],
            ":url"          => $args["bgzy_url"],
            ":scrn_w"       => $args["bgzy_scrn_w"],
            ":scrn_h"       => $args["bgzy_scrn_h"],
            ":ssid"         => $args["ssid"],
            ":srvip"        => $args["srvip"],
            ":srvname"      => $args["srvname"],
            ":user_agent"   => $args["user_agent"],
            ":locip"        => $args["locip"],
            ":now"          => date("Y-m-d H:m:s",($now/1000)),
            ":tstamp"       => $now
        );
        $id = $QO->execute($params);
        
        //********************* TENTATIVE D'ENVOI DE MAIL *************************/
        
        $from = ( $this->is_conf_ready && isset($this->prodconf_xmlscope) ) ? $this->prodconf_xmlscope["prod_email_table"]["email_bugzy_sender"] : $this->BGZY_DFLT_SDR;
        $sendto = ( $this->is_conf_ready && isset($this->prodconf_xmlscope) ) ? $this->prodconf_xmlscope["prod_email_table"]["email_bugzy_receiver"] : $this->BGZY_DFLT_RCV;
        
//        var_dump($this->is_conf_ready,$this->prodconf_xmlscope["prod_email_table"]["email_bugzy_sender"]);
//        var_dump($this->is_conf_ready,$this->prodconf_xmlscope["prod_email_table"]["email_bugzy_receiver"]);
        
        $EMH = new EMAILAC_HANDLER();
        $args_eml = [
//           "exp" => "trenqr.bugzy.sdr@trenqr.com",
           "exp" => htmlspecialchars_decode($from),
//           "exp" => "Trenqr <noreply@trenqr.com>",
//           "rcpt" => "dieudrichard@gmail.com", //DEV, TEST, DEBUG
//           "rcpt" => "lou.carther@deuslynn-entreprise.com", //DEV, TEST, DEBUG
           "rcpt" => htmlspecialchars_decode($sendto),
//           "rcpt_uid" => $acc_tab["accid"],
           "catg" => "USER_ACTION"
       ];
         
//        var_dump($args_eml);
//        var_dump($args_eml,$rec_link_ccl);
//        exit();
        
        $r_ = $EMH->emac_send_email_via_model("emdl_bgzy_exists", "fr", $args_eml);
        if ( !$r_ || $this->return_is_error_volatile(__FUNCTION__, __LINE__, $r_) ) { 
            /*
             * [NOTE 28/1/14] @author L.C.
             * On ne fait rien car l'envoi de l'email n'est pas une étape obligatoire.
             * "Faire payer d'une erreur" l'utilisateur pour une erreur d'envoi de mail dégraderait l'expérience utilisateur.
             */
            $this->presentVarIfDebug(__FUNCTION__,__LINE__,["__ERR_VOL_FAILED_ON_EML","DATAS => ","emdl_bgzy_exists",$args_eml]);
        }
        
        return "DONE";
        
    }
    
    public function CheckField ($fld_n, $fld_v) {
        /*
         * Permet de vérifier la validité de certaines données necessaires.
         * La méthode n'effectue pas d'opérations poussées comme pourrait le faire FE.
         * Elle se contente de dire si les champs sont valides ou non.
         */
        /*
        if (! in_array($fld_n, ["yilv_ot","ilbbif"]) ) {
            $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        }
        //*/
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //IsValid
        $iv = FALSE;
        switch ($fld_n) {
            case "bgzy_type" :
                    if ( in_array($fld_v, $this->bgzy_type) ) {
                        $iv = TRUE;
                    }
                break;
            case "bgzy_where" :
                    if ( preg_match($this->rgx_bgzy_whr, $fld_v) ) {
                        $iv = TRUE;
                    }
                break;
            case "bgzy_when" :
                    if ( preg_match($this->rgx_bgzy_whn, $fld_v) ) {
                        $iv = TRUE;
                    }
                break;
            case "bgzy_message" :
                    if ( preg_match($this->rgx_bgzy_msg, $fld_v) ) {
                        $iv = TRUE;
                    }
                break;
            case "bgzy_scrn_w" :
            case "bgzy_scrn_h" :
                    if ( intval($fld_v) && intval($fld_v) >= 176 && intval($fld_v) < 5000  ) {
                        $iv = TRUE;
                    }
                break;
            case "fullname" :
                    if ( preg_match($this->rgx_fn, $fld_v) ) {
                        $iv = TRUE;
                    }
                break;
//            case "url" :
//            case "bgzy_url" :
//                    echo "URL => ".$fld_v;
//                    if ( parse_url($fld_v) ) {
//                        echo "OK URL => ".  var_dump(parse_url($fld_v));
//                        $iv = TRUE;
//                    }
//                break;
            case "date" :
                    /*
                     * La date doit être sous format : m-d-Y
                     */
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
            case "timestamp" :
                    set_error_handler('exceptions_error_handler');
                    try {
                        $d = getdate($fld_v);
                        if ( $d ) {
                            $t__d = intval(date("d",$fld_v)); 
                            $t__m = intval(date("m",$fld_v)); 
                            $t__y = intval(date("Y",$fld_v))+$this->bd_limit;
                            
                            $gt = mktime(0, 0, 0, $t__m, $t__d, $t__y);
                            $now = (new DateTime())->getTimestamp();
                            
                            $df = $now - $gt ;
//                            var_dump(541,$now,$gt,$df);
                            if ( $df > 0 ) {
                                $iv = TRUE;
                            }
                        }
                    } catch (Exception $ex) {
                    }
                break;
            case "gender" :
                    if ( preg_match($this->rgx_gdr, $fld_v) ) {
                        $iv = TRUE;
                    }
                break;
            case "city" :
                    //On vérifie si l'identifiant est connu de la base de donnnées
                    if ( $this->GetLocationInfos($fld_v) ) {
                        $iv = TRUE;
                    }
                break;
            case "pseudo" :
                    if ( preg_match($this->rgx_psd, $fld_v) ) {
                        //On vérifie si le pseudo est disponible
                        if ( !$this->Pseudo_IsUsed($fld_v) && !$this->Pseudo_IsReserved($fld_v) && !$this->Pseudo_IsDenied($fld_v) ) {
                            $iv = TRUE;
                        }
                    }
                break;
            case "email" :
                    //L'email respect-il le format d'un email
                    $tplchk = $this->Email_TripleCheck($fld_v);
                    $used = $this->Email_Used($fld_v);
                    if ( !$this->return_is_error_volatile(__FUNCTION__, __LINE__, $tplchk) && !$used ) {
                        $iv = TRUE;
                    }
                break;
            case "lang" :
            case "bgzy_lang" :
                    //La langue fournie respect-elle le format et les langues gérées
                    if ( preg_match($this->rgx_lng, $fld_v) && in_array($fld_v, $this->TQR_AVAL_LANG) ) {
                        $iv = TRUE;
                    }
                break;
            case "password" :
                    /*
                     * Le password ne doit pas avoir été hashé avant d'essayer de le valider.
                     * En effet, cela pourrait fausser la vérification.
                     */
                    if ( preg_match($this->rgx_pwd, $fld_v) ) {
                        $iv = TRUE;
                    }
                break;
            default:
                return;
        }
        
        return $iv;
    }
    
    
    /*******************************************************************************************************************************************************/
    /****************************************************************** PREFERENCES SCOPE ******************************************************************/
    
    /**
     * Permet de sauvegarder les décisions de l'utilisateur en ce qui concerne certaines opérations liées à des modules.
     * Ces décisions sont stockées dans la bdd pour les utilisateurs de Trenqr, dans les cookies pour les visiteurs de Trenqr.
     * 
     * @param type $uid L'identifiant interne du Compte qui veut faire sauvegarder sa décision.
     * @param type $opexcd L'ididentifiant externe de l'opération.
     * @param type $opdec L'identifiant externe de la décision.
     * @return L'identifiant interne de l'occurrence de décision enregistrée.
     */
    public function setPreferences ($uid, $opexcd, $opdec) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if (! is_string($opexcd) ) {
            return "__ERR_VOL_BAD_OPERCD_FRMT";
        }
        if (! is_string($opdec) ) {
            return "__ERR_VOL_BAD_OPDEC_FRMT";
        }
        
        /*
         * ETAPE : 
         * On vérifie que le Compte est actif et on récupère sa table
         */
        $PDA = new PROD_ACC();
        $utab = $PDA->exists_with_id($uid, TRUE);
        if (! $utab ) {
            return "__ERR_VOL_U_G";
        }
        
        /*
         * ETAPE :
         *  (1) On vérifie que l'opération est connue mais surtout attendue.
         *  (2) On vérifie que la décision concorde
         */
        
        $opexcd = strtoupper($opexcd);
        $opdec = strtoupper($opdec);
        switch ( $opexcd ) {
            case "_PFOP_TIABT_INR" :
            case "_PFOP_CHTBX_ISXPM" :
            case "_PFOP_FSTCNX" : 
            case "_PFOP_CFRT_SCRN" : 
            case "_PFOP_CFRT_BRWZR" : 
            case "_PFOP_CFRT_CNLNGMSG" :
           /*
            * [DEPUIS 25-05-16]
            */
            case "_PFOP_BRAIN_ALWZ_OPN" :
            case "_PFOP_PSMN_EMLWHN_NW" :
            case "_PFOP_PSMN_EMLFOR_WKACTY" :
            case "_PFOP_INFO_EMLWHN_NWTQRVnSECU" :
            case "_PFOP_INFO_EMLFOR_WKBESTPUB" :
           /*
            * [DEPUIS 28-06-16]
            */
            case "_PFOP_PG_INS_INTRO_GDPLYR" :
            case "_PFOP_PG_INS_INTRO_FSIDE" :
                   /*
                    * ETAPE :
                    *       On vérifie que la décision concorde 
                    */
                    
                    //... Avant, on s'assure que l'élémment est traité
                    if (! key_exists($opexcd, $this->PRF_OPDEC_TAB) ) {
                        return "__ERR_VOL_BAD_OPERCD";
                    }
                    
                    if (! in_array($opdec, $this->PRF_OPDEC_TAB[$opexcd]) ) {
                        return "__ERR_VOL_BAD_OPDEC";
                    }
                    
                   /*
                    * ETAPE :
                    *       On récupère les données relatives au module et l'opération.
                    */
                    $QO = new QUERY("qryl4prefmdn1");
                    $params = array(":code" => $opexcd);
                    $optab = $QO->execute($params);
                    
                    if ( !$optab | count($optab) > 1 ) {
                        return "__ERR_VOL_FAILED";
                    }
                    $optab = $optab[0];
                    
                   /*
                    * ETAPE :
                    * On procède à des  vérifications :
                    *  (1) Le module est-il disponible ?
                    *  (2) L'opération est-elle disponible ?
                    */
                    //(1) Le module est-il disponible ?
                    if ( isset($optab["prfmd_datermv_tstamp"]) ) {
                        return "__ERR_VOL_MD_UVBLE";
                    }
                    
                    //(2) L'opération est-elle disponible ?
                    if ( isset($optab["prfop_datermv_tstamp"]) ) {
                        return "__ERR_VOL_MDOPR_UVBLE";
                    }
                    
                    /*
                     * ETAPE :
                     *      On effectue une action en fonction de l'existence ou non d'une précédente décision.
                     */
                    $QO = new QUERY("qryl4prefmdn2");
                    $params = array(":prfopid" => $optab["prfop_id"], ":accid" => $utab["pdaccid"]);
                    $opdctab = $QO->execute($params);
                    
                    if ( $opdctab && count($opdctab) > 1 ) {
                        return "__ERR_VOL_CRPTED";
                    } else if ( $opdctab ) {
                       /*
                        * On annule la précédente décision dans le cas ou elle existerait
                        */
                        $opdctab = $opdctab[0];
                        
                        $now = round(microtime(TRUE)*1000);
                        $datenow = date("Y-m-d H:m:s",($now/1000));
                        
                        $QO = new QUERY("qryl4prefmdn3");
                        $params = array(
                            ":opdecid"  => $opdctab["prfopdc_id"], 
                            ":date"     => $datenow, 
                            ":tstamp"   => $now
                        );
                        $QO->execute($params);
                    }
//                    var_dump(__LINE__,$opdctab);
//                    exit();
                    
                    /*
                     * ETAPE :
                     *      On insère la nouvelle décision 
                     */
                    $now = round(microtime(TRUE)*1000);
                    $datenow = date("Y-m-d H:m:s",($now/1000));
                    $opexcdi = $this->PRF_OPDEC_TYPES_I[$opdec];
                    
//                    var_dump(__LINE__,$opexcdi);
//                    exit();
                    
                    $QO = new QUERY("qryl4prefmdn4");
                    $params = array(
                        ":prfopid"  => $optab["prfop_id"], 
                        ":accid"    => $utab["pdaccid"], 
                        ":dctypid"  => $opexcdi, 
                        ":date"     => $datenow, 
                        ":tstamp"   => $now
                    );
                    $prfopid = $QO->execute($params);
                        
//                    var_dump(__LINE__,$opdctab);
//                    exit();
                    
                break;
            default :
                return "__ERR_VOL_BAD_OPERCD";
        }
        
        return $prfopid;
        
    }
    
    /**
     * Permet de récupérer les décisions de préférence de l'utilisateur.
     * On peut récupérer toutes les décisions de préférences ou les décisions relatives à des Opérations précises.
     *
     * @param type $uid
     * @param type $opexcd
     * @return string
     */
    public function getPreferences ($uid, $opexcd = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uid]);
        
        /*
         * ETAPE : 
         * On vérifie que le Compte est actif et on récupère sa table
         */
        $PDA = new PROD_ACC();
        $utab = $PDA->exists_with_id($uid, TRUE);
        if (! $utab ) {
            return "__ERR_VOL_U_G";
        }
        
        /*
         * Si CALLER a passé un code d'opération, on ne récuperera que la ou les opérations actives de cette préférence 
         */
        if ( !empty($opexcd) ) {
            
            if (! is_string($opexcd) ) {
                return "__ERR_VOL_BAD_OPERCD_FRMT";
            }
        
           /*
            * ETAPE :
            *  (1) On vérifie que l'opération est connue mais surtout attendue.
            *  (2) On vérifie que la décision concorde
            */

           $opexcd = strtoupper($opexcd);
           switch ($opexcd) {
                case "_PFOP_TIABT_INR" :
                case "_PFOP_CHTBX_ISXPM" :
                case "_PFOP_FSTCNX" : 
                case "_PFOP_CFRT_SCRN" : 
                case "_PFOP_CFRT_BRWZR" : 
                case "_PFOP_CFRT_CNLNGMSG" :
                /*
                 * [DEPUIS 25-05-16]
                 */
                case "_PFOP_BRAIN_ALWZ_OPN" :
                case "_PFOP_PSMN_EMLWHN_NW" :
                case "_PFOP_PSMN_EMLFOR_WKACTY" :
                case "_PFOP_INFO_EMLWHN_NWTQRVnSECU" :
                case "_PFOP_INFO_EMLFOR_WKBESTPUB" :
                           
                   /*
                    * ETAPE :
                    *       On récupère les données relatives au module et l'opération.
                    */
                    $QO = new QUERY("qryl4prefmdn1");
                    $params = array(":code" => $opexcd);
                    $optab = $QO->execute($params);
                    
                    if ( !$optab | count($optab) > 1 ) {
                        return "__ERR_VOL_FAILED";
                    }
                    $pfoptab = $optab[0];
                    
                    /*
                     * ETAPE :
                     *      On vérifie que la décision concorde 
                     */
                    $QO = new QUERY("qryl4prefmdn6");
                    $params = array(
                        ":accid"    => $utab["pdaccid"],
                        ":prfopid"  => $pfoptab["prfop_id"]
                    );
                    $prfops = $QO->execute($params);

 //                    var_dump(__LINE__,$prfops);
 //                    exit();
                    
                    break;
                default :
                    return "__ERR_VOL_BAD_OPERCD";
            }
           
        } else {
            /*
             * ETAPE :
             * On récupère toutes les décisions actives relatives à l'utilisateur
             */
            
            $QO = new QUERY("qryl4prefmdn5");
            $params = array(":accid" => $utab["pdaccid"]);
            $prfops = $QO->execute($params);
            
//                    var_dump(__LINE__,$prfops);
 //                    exit();
        }
        
        return $prfops;
        
    }
    
    
    /*********************************************************************************************************************************************************************/
    /********************************************************************* TRENQR STATISTIQUES PANEL *********************************************************************/
    
    /**
     * Permet d'enregistrer l'activité de la plateforme à la date courante. 
     */
    public function tqsta_update_from_now () {
        
        $now = round(microtime(TRUE)*1000);
        
        /*************************** STATISTIQUES D'ERREURS ***************************/
        
        /*
         * ETAPE :
         *  Nombre d'erreurs signalées (manuellement)
         */
        $QO = new QUERY("qryl4prdstats_errn1");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre d'erreurs détectées (automatique)
         */
        $QO = new QUERY("qryl4prdstats_errn2");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*************************** STATISTIQUES DE COMPTES ***************************/
        
        /*
         * ETAPE :
         *  Nombre de comptes enregistrés
         */
        $QO = new QUERY("qryl4prdstatsn1");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre de comptes actifs.
         *  Il s'agit de compte n'ayant une activité confirmée. Pour mesurer cette activité, on vérifie le nombre de publications, le nombre de commentaires ainsi que le nombre d'eval
         */
        $QO = new QUERY("qryl4prdstatsn2");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre de comptes zombies.
         *  Il s'agit de compte n'ayant aucune activité. Pour mesurer cette activité, on vérifie le nombre de publications, le nombre de commentaires ainsi que le nombre d'eval
         */
        $QO = new QUERY("qryl4prdstatsn3");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre de comptes désactivés
         */
        $QO = new QUERY("qryl4prdstatsn4");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*************************** STATISTIQUES DE TENDANCES ***************************/
        
        /*
         * ETAPE :
         *  Nombre total de Tendances
         */
        $QO = new QUERY("qryl4prdstatsn5");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total de Tendances actifs
         */
        $QO = new QUERY("qryl4prdstatsn6");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total de Tendances zombies
         */
        $QO = new QUERY("qryl4prdstatsn7");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total de Tendances désactivées
         */
        $QO = new QUERY("qryl4prdstatsn8");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*************************** STATISTIQUES D'ACTIVTES ***************************/
        
        /*
         * ETAPE :
         *  Nombre total de commentaires
         */
        $QO = new QUERY("qryl4prdstatsn9");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total de publications
         */
        $QO = new QUERY("qryl4prdstatsn10");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total de tags utilisateur dans les commentaires
         */
        $QO = new QUERY("qryl4prdstatsn11");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total de tags utilisateur dans les publications
         */
        $QO = new QUERY("qryl4prdstatsn12");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total d'évaluations
         */
        $QO = new QUERY("qryl4prdstatsn13");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total d'évaluations actifs
         */
        $QO = new QUERY("qryl4prdstatsn14");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total de Messages
         */
        $QO = new QUERY("qryl4prdstatsn15");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total de Messages Mi-Orphelins
         */
        $QO = new QUERY("qryl4prdstatsn16");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total de Relations
         */
        $QO = new QUERY("qryl4prdstatsn17");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total de Relations Amis
         */
        $QO = new QUERY("qryl4prdstatsn18");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total de Relations S_FOLW
         */
        $QO = new QUERY("qryl4prdstatsn19");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total de Relations D_FOLW
         */
        $QO = new QUERY("qryl4prdstatsn20");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  Nombre total de Relations terminées
         */
        $QO = new QUERY("qryl4prdstatsn21");
        $params = array(
            ":datecrea" => date("Y-m-d H:m:s",($now/1000)), 
            ":tstamp"   => $now
        );
        $QO->execute($params);
        
        /*
         * ETAPE :
         *  10 Meilleures comptes par nombre de points (Capital)
         *
        $QO = new QUERY("...");
        $QO->execute(NULL);
        
        /*
         * ETAPE :
         *  10 Meilleures comptes par nombre d'Abonnés
         *
        $QO = new QUERY("...");
        $QO->execute(NULL);
        
        /*
         * ETAPE :
         *  10 Meilleures comptes par nombre d'Abonnements
         *
        $QO = new QUERY("...");
        $QO->execute(NULL);
        
        /*
         * ETAPE :
         *  10 Meilleures comptes par nombre de publications
         *
        $QO = new QUERY("...");
        $QO->execute(NULL);
        
        /*
         * ETAPE :
         *  10 Meilleures comptes par nombre de commentaires
         *
        $QO = new QUERY("...");
        $QO->execute(NULL);
        
        /*
         * ETAPE :
         *  10 Meilleures Tendances par nombre de Publications
         *
        $QO = new QUERY("...");
        $QO->execute(NULL);
        
        /*
         * ETAPE :
         *  10 Meilleures Tendances par nombre de points (Capital)
         *
        $QO = new QUERY("...");
        $QO->execute(NULL);
        
        /*
         * ETAPE :
         *  10 Meilleures Tendances par nombre d'Abonnés
         *
        $QO = new QUERY("...");
        $QO->execute(NULL);
        
        /*
         * ETAPE :
         *  10 Meilleures Tendances par nombre de Commentaires
         *
        $QO = new QUERY("...");
        $QO->execute(NULL);
        //*/
    } 
    
    public function tqsta_get_from ($timestamp = NULL) {
        
        $perf_start = round(microtime(TRUE)*1000);
        $fr = [];
        if (! $timestamp ) {
            $qries = [
                "qryl4prdstats_gblget_errn1",
                "qryl4prdstats_gblget_errn2",
                "qryl4prdstats_gblget_accn1",
                "qryl4prdstats_gblget_accn2",
                "qryl4prdstats_gblget_accn3",
                "qryl4prdstats_gblget_accn4",
                "qryl4prdstats_gblget_trdn1",
                "qryl4prdstats_gblget_trdn2",
                "qryl4prdstats_gblget_trdn3",
                "qryl4prdstats_gblget_trdn4",
                "qryl4prdstats_gblget_rctn1",
                "qryl4prdstats_gblget_artn1",
                "qryl4prdstats_gblget_rutgn1",
                "qryl4prdstats_gblget_autgn1",
                "qryl4prdstats_gblget_evln1",
                "qryl4prdstats_gblget_evln2",
                "qryl4prdstats_gblget_min1",
                "qryl4prdstats_gblget_miorn1",
                "qryl4prdstats_gblget_reln1",
                "qryl4prdstats_gblget_reln2",
                "qryl4prdstats_gblget_reln3",
                "qryl4prdstats_gblget_reln4",
                "qryl4prdstats_gblget_reln5"
            ];

            foreach($qries as $qry) {
                $QO = new QUERY($qry);
                $r1 = $QO->execute(NULL);
                if ($r1) {
                    $fr = ( $fr ) ? array_merge($fr,$r1[0]) : $r1[0];
                }
            }
        } else {
            $qries = [
                "qryl4prdstats_gblget_errn1_lt",
                "qryl4prdstats_gblget_errn2_lt",
                "qryl4prdstats_gblget_accn1_lt",
                "qryl4prdstats_gblget_accn2_lt",
                "qryl4prdstats_gblget_accn3_lt",
                "qryl4prdstats_gblget_accn4_lt",
                "qryl4prdstats_gblget_trdn1_lt",
                "qryl4prdstats_gblget_trdn2_lt",
                "qryl4prdstats_gblget_trdn3_lt",
                "qryl4prdstats_gblget_trdn4_lt",
                "qryl4prdstats_gblget_rctn1_lt",
                "qryl4prdstats_gblget_artn1_lt",
                "qryl4prdstats_gblget_rutgn1_lt",
                "qryl4prdstats_gblget_autgn1_lt",
                "qryl4prdstats_gblget_evln1_lt",
                "qryl4prdstats_gblget_evln2_lt",
                "qryl4prdstats_gblget_min1_lt",
                "qryl4prdstats_gblget_miorn1_lt",
                "qryl4prdstats_gblget_reln1_lt",
                "qryl4prdstats_gblget_reln2_lt",
                "qryl4prdstats_gblget_reln3_lt",
                "qryl4prdstats_gblget_reln4_lt",
                "qryl4prdstats_gblget_reln5_lt"
                ];
            
            foreach($qries as $qry) {
                $QO = new QUERY($qry);
                $params = array(":tstamp" => $timestamp);
                $r1 = $QO->execute($params);
                if ($r1) {
                    $fr = ( $fr ) ? array_merge($fr,$r1[0]) : $r1[0];
                }
            }            
        }
//        $perf_end = round(microtime(TRUE)*1000);
//        var_dump("LINE => ",__LINE__,"; DATAS => ",$fr);
//        var_dump("LINE => ",__LINE__,"; DATAS => (START)",$perf_start,"; DATAS => (START)",$perf_end);
//        exit();
//        
        return $fr;
    }
    
    
    
    /********************************************************************************************************************************************************/
    /********************************************************************* RECOTO SCOPE *********************************************************************/
    
    public function rcmd_new ($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());

        //On vérifie la présence des données obligatoires
                 
        $_needed_to_create_prop_keys = ["rcmd_aeid","rcmd_sfn","rcmd_sml","rcmd_rfn","rcmd_rml","g-recaptcha-response","rcmd_ssid","rcmd_curl","rcmd_locip","rcmd_locip_num","rcmd_uagent"];
        $com  = array_intersect( array_keys($args), $_needed_to_create_prop_keys);
        if ( count($com) != count($_needed_to_create_prop_keys) ) {
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$_needed_to_create_prop_keys,'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
            $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
        } else {
            foreach ($args as $k => $v) {
                if ( !( isset($v) && $v !== "" ) && !( in_array($k,["rcmd_aeid","rcmd_sml","rcmd_ssid","rcmd_uagent"]) ) ) {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__,$args,'v_d');
                    $this->signalError ("err_sys_l4comn9", __FUNCTION__, __LINE__);
                } 
            }
        }
        
        
       /*
        * ETAPE :
        *   On vérifie si le compte est disponible
        */
        if ( $args["rcmd_aeid"] ) {
            $acc_eid = $args["rcmd_aeid"];
            $PA = new PROD_ACC();
            $utab = $PA->exists($acc_eid,TRUE); 
            if (! $utab ) {
                return "__ERR_VOL_USER_GONE";
            } else {
                $TA = new TQR_ACCOUNT();
                $ueml = $TA->on_read_entity(["acc_eid" => $args["rcmd_aeid"]])["rcmd_sml"];
                
               /*
                * ETAPE :
                *  On vérifie que les données sur l'utilisateur connecté passé en paramètre sont identiques à ceux enregistrées.
                */
                if ( $utab["pdacc_ufn"] !== $args["rcmd_sfn"] && $ueml !== $args["rcmd_sml"] ) {
                    return "__ERR_VOL_HACK_USER";
                }
                
                $accid = $utab["pdaccid"];
                $args["accid"] = $accid;
            }
            
        }
        
        
        /*
         * ETAPE :
         *  On vérifie que les champs sont valides.
         * 
         * [TODO]
         *  Renvoyer un tableau qui spécifie les erreurs pour chaque champ
         */
        $chkds = [
            "sndr_fn"   => $args["rcmd_sfn"],
            "sndr_eml"  => $args["rcmd_sml"],
            "rcpt_fn"   => $args["rcmd_rfn"],
            "rcpt_eml"  => $args["rcmd_rml"]
        ];
        if ( $this->rcmd_check_fields($chkds) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        
        
        /*
         * ETAPE :
         *  On vérifie qu'on a une réponse captcha.
         *  Sinon, on signale l'erreur. De plus, si on est sur la vue de SAMPLE, on revient sur main
         */
        $S_RCPTCHA = new SRVC_ReCaptcha($this->rcptcha_site, $this->rcptcha_secret);
        if ( $S_RCPTCHA->checkResponse($args["g-recaptcha-response"], $args["rcmd_locip"], ["ssl_verifypeer" => FALSE]) ) {
            return "__ERR_VOL_WRG_CAPTCHA";
        }

        die("Go to send email");
        /*
         * ETAPE :
         *  On lance le processus de prepation du de l'email
         */
        

        /*
         * ETAPE :
         *  On lance le processus d'envoi de l'email
         */
        
        
    }
    
    private function rcmd_check_fields($chkds) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $errs = 0;
        foreach ($chkds as $k => $v) {
            switch ($k) {
                case "sndr_fn" :
                case "rcpt_fn" :
                        if ( empty($v) ) {
                            $errs++;
                        } else if (! preg_match($this->rgx_fn30, $v) ) {
                            $errs++;
                        }
                    break;
                case "sndr_eml" :
                        if ( !empty($v) && !preg_match($this->rgx_email, $v) ) {
                            $errs++;
                        }
                    break;
                case "rcpt_eml" :
                        if ( empty($v) ) {
                            $errs++;
                        } else if (! preg_match($this->rgx_email, $v) ) {
                            $errs++;
                        }
                    break;
                default :
                    return;
            }
        }
            
        return $errs;
        
    }
    
    
    
    /************************************************************************************************************************************************************/
    /********************************************************************* SUGGESTION SCOPE *********************************************************************/
    
    public function sugg_GetChoosenProfils ($peid = NULL, $IP = NULL, $_LMT = NULL) {
        
        //pid : PivotExtID; ptp : PivotTyPe
        /*
         * VersionName : Embryon
         * [NOTE 26-10-15] @author BOR
         *      La version actuelle ne permet pas de mettre en place un algorithme puissant qui permettrait de fournir des résultats autonomes.
         *      En effet, nous manquons de données et de main d'oeuvre pour atteindre cet objectif.
         *      Aussi, la solution repose sur le fait de proposer des Comptes et Tendances à la volée en fonction de la situation dans laquelle nous nous trouvons.
         */
        
        /*
         * ETAPE :
         *      On récupère la table de l'utilisateur
         */
        $PA = new PROD_ACC();
        if ( $peid ) {
            $ptab = $PA->exists($peid);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $ptab) ) {
                return $ptab;
            }
        }
        
        
        /*
         * ETAPE :
         *      On détermine la situation dans laquelle nous nous trouvons en fonction de l'adresse IP du serveur.
         */
        if ( $IP === NULL ) {
            $IP = $_SERVER["SERVER_ADDR"];
        }
        /*
         * ETAPE :
         *      On vérifie si on est dans le cas PROD ou DEV
         */
        $rnmd = ( in_array($IP, $this->SUGG_IP_PROD_LIST) ) ? "PROD" : "DEV";
        
        /*
         * ETAPE :
         *      On récupère les données ANGELS
         */
        $angels = $this->sugg_getAngels_Profils($rnmd);
        if (! $angels ) {
            return FALSE;
        }
        
        /*
         * ETAPE :
         *      Cas spécifique d'un seul élément dans la liste
         */
        if ( $peid && key_exists("sugg_ngl_pfl_uid",$angels) && floatval($angels["sugg_ngl_pfl_uid"]) === floatval($ptab["pdaccid"]) ) {
            return 0;
        } 
       
        /*
         * ETAPE :
         *      On exclut le pivot
         */
        $arr_a = [];
        foreach ($angels as $e) {
            if ( $peid && floatval($e["sugg_ngl_pfl_uid"]) === floatval($ptab["pdaccid"]) ) {
                continue;
            }  
            $arr_a[] = $e;
        }
        
        /*
         * ETAPE :
         *      Déterminer la limite
         */
        $lmt = ( $_LMT ) ? $_LMT : $this->SUGG_PFL_LMT;
        
        /*
         * [DEPUIS 03-11-15] @author BOR
         */
        if ( $lmt === 1 ) {
            $cn = count($arr_a);
            $ix = mt_rand (0,--$cn);
            
            $arr_a = [$arr_a[$ix]];
        }
        
        /*
         * ETAPE :
         *      Trier les données en fonction de leur poid
         */
        $arr_b = [];
        foreach($arr_a as $k => $e){ 
            $arr_b[$e["sugg_ngl_pfl_weight"]][] = $e;
        }
        
        /*
         * On ordonne en fonction du poinds représenté par la clé
         */
        krsort($arr_b); 
        
        /*
         * ETAPE :
         *      On va sélectionner les éléments finaux dans la limite connue.   
         *      Pour ce faire, nous allons traverser le tableau en fonction des poids
         */
        $arr_c = [];
        foreach($arr_b as $k => $e){ 
            $k = (string)$k;
            switch ($k) {
                case "1" :
                case "0.9" :
                case "0.8" :
                case "0.7" :
                case "0.6" :
                case "0.5" :
                case "0.4" :
                case "0.3" :
                case "0.2" :
                case "0.1" :
                        if ( !$arr_c && count($e) > $lmt ) {
                            $ln = $lmt;
                            //On secoue pour espérer avoir des données moins statiques.
                            shuffle($e);
                            //On récupère la portion souhaitée
                            $a__ = array_slice($e, 0, $ln);
                            $arr_c = array_merge($arr_c,$a__);
                            break;
                        } else if ( $arr_c && count($arr_c) === $lmt ) {
                            break;
                        } else if ( !$arr_c && count($e) === $lmt ) {
                            $arr_c = $e;
                            break;
                        } else if ( !$arr_c && count($e) && count($e) < $lmt ) {
                            $arr_c = $e;
                        } else if ( $arr_c && count($e) ) {
                            $ln = $lmt - count($arr_c);
                            //On récupère la longueur du tableau à extraire
                            $ln = ( ($lmt - count($arr_c)) < count($e) ) ? $ln : count($e);
                            //On secoue pour espérer avoir des données moins statiques
                            shuffle($e);
                            //On récupère la portion souhaitée
                            $a__ = array_slice($e, 0, $ln);
                            $arr_c = array_merge($arr_c,$a__);
                        }
                    break;
                default :
                    break;
            }
            
        }
        
//        var_dump(__LINE__,$lmt,$arr_c);
//        exit();
        
        /*
         * ETAPE :
         *      On va ajouter les éléments de la table de l'utilisateur
         */
        $fnl_datas = []; 
        foreach ($arr_c as $e) {
            $th_u_tab = $PA->exists_with_id($e["sugg_ngl_pfl_uid"]);
            $pp_datas = $PA->onread_acquiere_pp_datas($th_u_tab["pdaccid"],$th_u_tab["pdacc_gdr"]); 
            
            $fnl_datas[] = [
                "uid"   => $th_u_tab["pdacc_eid"],
                "ufn"   => $th_u_tab["pdacc_ufn"],
                "upsd"  => $th_u_tab["pdacc_upsd"],
                "uppc"  => $pp_datas["pic_rpath"],
            ];
        }
            
        return $fnl_datas;
        
    }
    
    public function sugg_GetChoosenTrends ($peid = NULL, $IP = NULL, $_LMT = NULL, $_GETOD = FALSE) {
        
        //pid : PivotExtID; ptp : PivotTyPe
        /*
         * VersionName : Embryon
         * [NOTE 26-10-15] @author BOR
         *  La version actuelle ne permet pas de mettre en place un algorithme puissant qui permettrait de fournir des résultats autonomes.
         *  En effet, nous manquons de données et de main d'oeuvre pour atteindre cet objectif.
         *  Aussi, la solution repose sur le fait de proposer des Comptes et Tendances à la volée en fonction de la situation dans laquelle nous nous trouvons.
         */
        
        
        /*
         * ETAPE :
         *      On récupère la table de l'utilisateur
         */
        $TR = new TREND();
        if ( $peid ) {
            $ptab = $TR->exists($peid);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $ptab) ) {
                return $ptab;
            }
        }
        
        
        /*
         * ETAPE :
         *      On détermine la situation dans laquelle nous nous trouvons en fonction de l'adresse IP du serveur.
         */
        if ( $IP === NULL ) {
            $IP = $_SERVER["SERVER_ADDR"];
        }
        /*
         * ETAPE :
         *      On vérifie si on est dans le cas PROD ou DEV
         */
        $rnmd = ( in_array($IP, $this->SUGG_IP_PROD_LIST) ) ? "PROD" : "DEV";
        
        /*
         * ETAPE :
         *      On récupère les données ANGELS
         */
        $angels = $this->sugg_getAngels_Trends($rnmd);
        if (! $angels ) {
            return FALSE;
        }
        
        /*
         * ETAPE :
         *      Cas spécifique d'un seul élément dans la liste
         */
        if ( $peid && key_exists("sugg_ngl_trd_trid",$angels) && floatval($angels["sugg_ngl_trd_trid"]) === floatval($ptab["trid"]) ) {
            return 0;
        } 
        
        /*
         * ETAPE :
         *      On exclut le pivot
         */
        $arr_a = [];
        foreach ($angels as $e) {
            if ( $peid && floatval($e["sugg_ngl_trd_trid"]) === floatval($ptab["trid"]) ) {
                continue;
            }  
            $arr_a[] = $e;
        }
        
        /*
         * ETAPE :
         *      Déterminer la limite
         */
        $lmt = ( $_LMT ) ? $_LMT : $this->SUGG_TRD_LMT;
        
        /*
         * [DEPUIS 03-11-15] @author BOR
         */
        if ( $lmt === 1 ) {
            $cn = count($arr_a);
            $ix = mt_rand (0,--$cn);
            
            $arr_a = [$arr_a[$ix]];
        }
        
        /*
         * ETAPE :
         *      Trier les données en fonction de leur poid
         */
        $arr_b = [];
        foreach($arr_a as $k => $e){ 
            $arr_b[$e["sugg_ngl_trd_weight"]][] = $e;
        }
        
        /*
         * On ordonne en fonction du poinds représenté par la clé
         */
        krsort($arr_b); 
        
//        var_dump(__LINE__,__FILE__,$arr_a);
//        exit();
        /*
         * ETAPE :
         *      On va sélectionner les éléments finaux dans la limite connue.   
         *      Pour ce faire, nous allons traverser le tableau en fonction des poids
         */
        $arr_c = [];
        foreach($arr_b as $k => $e){ 
            $k = (string)$k;
            switch ($k) {
                case "1" :
                case "0.9" :
                case "0.8" :
                case "0.7" :
                case "0.6" :
                case "0.5" :
                case "0.4" :
                case "0.3" :
                case "0.2" :
                case "0.1" :
                        if ( !$arr_c && count($e) > $lmt ) {
                            $ln = $lmt;
                            //On secoue pour espérer avoir des données moins statiques.
                            shuffle($e);
                            //On récupère la portion souhaitée
                            $a__ = array_slice($e, 0, $ln);
                            $arr_c = array_merge($arr_c,$a__);
                            break;
                        } else if ( $arr_c && count($arr_c) === $lmt ) {
                            break;
                        } else if ( !$arr_c && count($e) === $lmt ) {
                            $arr_c = $e;
                            break;
                        } else if ( !$arr_c && count($e) && count($e) < $lmt ) {
                            $arr_c = $e;
                        } else if ( $arr_c && count($e) ) {
                            $ln = $lmt - count($arr_c);
                            //On récupère la longueur du tableau à extraire
                            $ln = ( ($lmt - count($arr_c)) < count($e) ) ? $ln : count($e);
                            //On secoue pour espérer avoir des données moins statiques
                            shuffle($e);
                            //On récupère la portion souhaitée
                            $a__ = array_slice($e, 0, $ln);
                            $arr_c = array_merge($arr_c,$a__);
                        }
                        
                    break;
                default :
                    break;
            }
            
        }
        
//        var_dump(__LINE__,$lmt,$arr_c);
//        exit();
        
        /*
         * ETAPE :
         *      On va ajouter les éléments de la table de l'utilisateur
         */
        $fnl_datas = []; 
        foreach ($arr_c as $e) {
            $th_tr_tab = $TR->exists_with_id($e["sugg_ngl_trd_trid"]);
            if (! $th_tr_tab ) {
                continue;
            }
            
            $owner = [];
            if ( $_GETOD ) {
                $troid = $th_tr_tab["trd_owner"];
                $PA = new PROD_ACC();
                $th_tr_tab_own = $PA->exists_with_id($troid,TRUE);
                
                if (! $th_tr_tab_own ) {
                    continue;
                }
                
                $owner = [
                    "oid"    => $th_tr_tab_own["pdaccid"],
                    "ofn"    => $th_tr_tab_own["pdacc_ufn"],
                    "opsd"   => $th_tr_tab_own["pdacc_upsd"],
                ];
            }
            
            $cov = $TR->onload_trend_get_trend_cover($th_tr_tab["trid"]);
            $fnl_datas[] = [ 
                "tid"       => $th_tr_tab["trd_eid"],
                "tcvpc"     => [
                    "cov_w"   => ( $cov ) ? $cov["trcov_width"] : NULL,
                    "cov_h"   => ( $cov ) ? $cov["trcov_height"] : NULL,
                    "cov_t"   => ( $cov ) ? $cov["trcov_top"] : NULL,
                    "cov_rp"  => ( $cov ) ? $cov["pdpic_realpath"] : NULL,
                ],
                "ttle"      => html_entity_decode($th_tr_tab["trd_title"]),
                "tdsc"      => html_entity_decode($th_tr_tab["trd_desc"]),
                "tctm"      => $th_tr_tab["trd_date_tstamp"],
                "tlk"       => $TR->on_read_build_trdhref($th_tr_tab["trd_eid"], $th_tr_tab["trd_title_href"]),
                "town"      => $owner
            ];
            
        }
            
        return $fnl_datas;
    }
    
    
    public function sugg_GetChoosenAny($cuid, $_OPTIONS = NULL) {
        //cuid : L'identifiant externe de l'utilisateur connecté.
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cuid]);
        
        
        $PA = new PROD_ACC();
        $utab = $PA->exists($cuid);
        if (! $utab ) {
            return "__ERR_VOL_U_G";
        }
        
        /*
         * ETAPE :
         *      On récupère les données en se fiant à l'algorithme.
         *      Il est presque impossible qu'aucune donnée ne soit retournée.
         *      Aussi, si on a pas de données, on déclenché une erreur de type SYSTEM.
         */
        $datas = $this->sugg_algo_tchouri($utab["pdaccid"],1);
        if ( !$datas || !( $datas && is_array($datas) && count($datas) === 2 && $datas[1] ) ) {
            echo "ERROR_SYSTEM AT 1 !";
        }
        
        $case = $datas[0];
        $content = $datas[1][0];
        
        /*
         * ETAPE :
         *      On sélectionne les données en fonction des options passées en paramètres.
         *      S'il n'y a pas d'option, on renvoie les données brutes.
         */
        switch ($case) {
            case "ANGL_ART_WEVR" : //ARTICLES WHATEVER FROM ANGELS WITH RIGHTS
            case "ALGO_ART_WEVR_NW" : //ARTICLE NEW
            case "ALGO_ART_IML" : //ARTICLE IML
            case "ALGO_ART_ITR" : //ARTICLE ITR
                    if ( $_OPTIONS && in_array("W_FEO",$_OPTIONS) ) {
                        $content = "/f/".$content["art_prmlk"];
                    } 
                break;
            case "ANGL_PFL_WEVR" : //PROFILES WHATEVER FROM ANGELS
            case "ALGO_PFL_NTWK" : //COMPTE MY_NETWORK
            case "ALGO_PFL_UKNW_ODR" : //COMPTE UNKNOW + OLDER
            case "ALGO_PFL_UKNW_NW" : //COMPTE UNKNOW + NEW ONES
                    if ( $_OPTIONS && in_array("W_FEO",$_OPTIONS) ) {
                        $content = "/".$content["pdacc_upsd"];
                    }
                break;
            case "ANGL_TRD_WEVR" : //TENDANCES WHATEVER FROM ANGELS
            case "ALGO_TRD_NTWK" : //TENDANCE MY NETWORK
            case "ALGO_TRD_NW" : //TENDANCE NEW
            case "ALGO_TRD_UKNW" : //TENDANCE NOT NETWORK
                    if ( $_OPTIONS && in_array("W_FEO",$_OPTIONS) ) {
                        $content =  "/tendance/".$content["trd_eid"]."/".$content["trd_title_href"];
                    } 
                break;
            default:
                return "__ERR_VOL_FAILED"; 
        }
        
//        var_dump($case,$content);
//        exit();
        
        return $content;
        
    }
    
    private function sugg_algo_tchouri ($cuid,$lmt) {
        //uid : L'identifiant interne de l'utilisateur
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cuid,$lmt]);
        
        /*
         * ALGORITHME : PHILAE
         * DEPUIS : 12-11-15
         * PAR : Lou Carther
         * 
         * REGLES SUR LES CHANCES DE TOMBER SUR :
         *  ~75% : Aricles
         *      40% : Articles IML
         *      40% : Articles ITR
         *      20% : Nouveau Articles
         *  ~15% : Tendances
         *      50% : Tendance hors de mon réseau
         *      30% : Tendance de mon réseau
         *      30% : Tendance nouvellement créée
         *  ~10% : Comptes
         *      70% : Compte avec lequel je n'ai aucun contact
         *          60% : Nouveaux Comptes
         *          40% : Random
         *      30% : Compte de mon réseau
         * 
         * EST RECENT QUAND :
         *      -> moins de 2 semaines.
         *      -> Permet de lancer une dynamique pour les nouveaux contenus. 
         *          Sinon, un compte, une Tendance ou un Article peut ne pas avoir de visites pendant un moment.
         *          On évite l'effet Twitter.
         * 
         * NE SE CONNAISSENT PAS QUAND :
         *      Ils n'ont jamais eu de relation.
         *      Dans cette logique, les personnes avec lesquelles on a une relation de type 4 sont semble t-il écartées.
         *      Mais cette perspective n'est pas dramtique. Si les utilisateurs on décidé de mettre fin à leur RELATION, l'image qu'ils ont l'un de l'autre peut être négative.
         *      Autant éviter toute rencontre. En effet, ils seraient susceptibles d'en vouloir à la plateforme.
         *      S'ils veulent à nouveaux se voir, un mécanisme naturel le leur permettra.
         * 
         * Si suivant le cas sélectionné aucune donnée n'est disponible (surtout dans le cas de NEW) on choisit une solution de rechange.
         * => On sélectionne soit : Un Compte Angel, Une Tendance Angel, Un Article ITR d'un Angel.
         */
        //2 semaines
        $recent_is = 2*7*24*3600000;
        $recent_start = round(microtime(TRUE)*1000) - $recent_is;
        
                
        /*
         * ETAPE :
         *      Meilleure initialisation du générateur aléatoire.
         */
        mt_srand(function(){
            list($usec, $sec) = explode(' ', microtime());
            return (float) $sec + ((float) $usec * 100000);
        });
        
        $rval = mt_rand(0,100);
        $case; $datas;
        if ( $rval <= 10 ) {
           /*
            * Comptes
            *      65% : Compte avec lequel je n'ai aucun contact
            *          60% : Nouveaux Comptes
            *          40% : Compte créé après la référence "RECENT"
            *      35% : Compte de mon réseau
            */
            $rval = mt_rand(0,100);
            if ( $rval <= 70 ) {
                $rval = mt_rand(0,100);
                if ( $rval <= 60 ) {
                    $case = "ALGO_PFL_UKNW_NW";
                    $QO = new QUERY("qryl4tqrsuggn1_ttm");
                    $params = array(
                        ":cuid1"    => $cuid,
                        ":cuid2"    => $cuid,
                        ":cuid3"    => $cuid,
                        ":cuid4"    => $cuid,
                        ":cuid5"    => $cuid,
                        ":cuid6"    => $cuid,
                        ":cuid7"    => $cuid,
                        ":cuid8"    => $cuid,
                        ":cuid9"    => $cuid,
                        ":cuid10"   => $cuid,
                        ":time"     => $recent_start,
                        ":limit"    => $lmt
                    );
                    $datas = $QO->execute($params);
                } else {
                    $case = "ALGO_PFL_UKNW_ODR";
                    $QO = new QUERY("qryl4tqrsuggn2_ttm");
                    $params = array(
                        ":cuid1"    => $cuid,
                        ":cuid2"    => $cuid,
                        ":cuid3"    => $cuid,
                        ":cuid4"    => $cuid,
                        ":cuid5"    => $cuid,
                        ":cuid6"    => $cuid,
                        ":cuid7"    => $cuid,
                        ":cuid8"    => $cuid,
                        ":cuid9"    => $cuid,
                        ":cuid10"   => $cuid,
                        ":time"     => $recent_start,
                        ":limit"    => $lmt
                    );
                    $datas = $QO->execute($params);
                }
            } else {
                $case = "ALGO_PFL_NTWK";
                $QO = new QUERY("qryl4tqrsuggn3_ttm");
                $params = array(
                    ":cuid1"    => $cuid,
                    ":cuid2"    => $cuid,
                    ":cuid3"    => $cuid,
                    ":cuid4"    => $cuid,
                    ":cuid5"    => $cuid,
                    ":cuid6"    => $cuid,
                    ":cuid7"    => $cuid,
                    ":limit"    => $lmt
                );
                $datas = $QO->execute($params);
            }
        } else if ( $rval > 10 && $rval <=75 ) {
            /*  ~75% : Aricles
             *      50% : Articles ITR
             *      30% : Articles IML
             *      20% : Nouveaux Articles
             */
            $rval = mt_rand(0,100);
            if ( $rval <= 50 ) {
                $case = "ALGO_ART_ITR";
                $QO = new QUERY("qryl4tqrsuggn4_ttm");
                $params = array(
                    ":cuid"    => $cuid,
                    ":limit"   => $lmt
                );
                $datas = $QO->execute($params);
            } else if ( $rval > 50 && $rval < 80 ) {
                $case = "ALGO_ART_IML";
                $QO = new QUERY("qryl4tqrsuggn5_ttm");
                $params = array(
                    ":cuid1"   => $cuid,
                    ":cuid2"   => $cuid,
                    ":cuid3"   => $cuid,
                    ":cuid4"   => $cuid,
                    ":cuid5"   => $cuid,
                    ":cuid6"   => $cuid,
                    ":limit"   => $lmt
                );
                $datas = $QO->execute($params);
            } else {
                $case = "ALGO_ART_WEVR_NW";
                $QO = new QUERY("qryl4tqrsuggn6_ttm");
                $params = array(
                    ":cuid1"   => $cuid,
                    ":cuid2"   => $cuid,
                    ":cuid3"   => $cuid,
                    ":cuid4"   => $cuid,
                    ":cuid5"   => $cuid,
                    ":cuid6"   => $cuid,
                    ":time"    => $recent_start,
                    ":limit"   => $lmt
                );
                $datas = $QO->execute($params);
            }
        } else if ( $rval > 75 ) {
               /*  ~15% : Tendances
                *      50% : Tendance hors de mon réseau
                *      30% : Tendance nouvellement créée
                *      20% : Tendance de mon réseau
                */
                $rval = mt_rand(0,100);
                if ( $rval <= 50 ) {
                    $case = "ALGO_TRD_UKNW";
                    $QO = new QUERY("qryl4tqrsuggn7_ttm");
                    $params = array(
                        ":cuid1"   => $cuid,
                        ":cuid2"   => $cuid,
                        ":cuid3"   => $cuid,
                        ":cuid4"   => $cuid,
                        ":cuid5"   => $cuid,
                        ":cuid6"   => $cuid,
                        ":cuid7"   => $cuid,
                        ":cuid8"   => $cuid,
                        ":cuid9"   => $cuid,
                        ":cuid10"  => $cuid,
                        ":limit"   => $lmt
                    );
                    $datas = $QO->execute($params);
                } else if ( $rval > 50 && $rval <= 80 ) {
                    $case = "ALGO_TRD_NW";
                    $QO = new QUERY("qryl4tqrsuggn9_ttm");
                    $params = array(
                        ":cuid1"   => $cuid,
                        ":time"    => $recent_start,
                        ":limit"   => $lmt
                    );
                $datas = $QO->execute($params);
                } else {
                    $case = "ALGO_TRD_NTWK";
                    $QO = new QUERY("qryl4tqrsuggn8_ttm");
                    $params = array(
                        ":cuid1"   => $cuid,
                        ":cuid2"   => $cuid,
                        ":cuid3"   => $cuid,
                        ":cuid4"   => $cuid,
                        ":cuid5"   => $cuid,
                        ":cuid6"   => $cuid,
                        ":cuid7"   => $cuid,
                        ":limit"   => $lmt
                    );
                    $datas = $QO->execute($params);
                }
        }
        
        /*
         * ETAPE:
         *      On vérifie si on a des données. Sinon, on choisit la solution par défaut.
         *      Les règles appliquées sont :
         *          (1) On rand pour trouver une valeur aléatoire
         *          (2) On respectent les proportions de l'algorithme par défaut : Articles (75), Tendances (15%), Comptes (10%)
         *          (3) On sélectionne toujours dans les ANGELS
         */
        if (! $datas ) {
            $rval = mt_rand(0,100);
            if ( $rval < 75 ) {
                /*
                 * ARTICLES ANGELS :
                 *      L'algorithme choisit au hasard entre un IML et un ITR en fonction des liens qui unissent le COMPTE à CU.
                 * [NOTE 13-11-15]
                 *      Dans la version PROD, l'utilisateur aura toujours plus de chance de tomber sur un IML car peut d'entres eux auront une relation de type 3 ou 2.
                 */
                $case = "ANGL_ART_WEVR";
                $QO = new QUERY("qryl4tqrsuggn10_ttm");
                $params = array(
                    ":cuid1"    => $cuid,
                    ":cuid2"    => $cuid,
                    ":cuid3"    => $cuid,
                    ":cuid4"    => $cuid,
                    ":cuid5"    => $cuid,
                    ":cuid6"    => $cuid,
                    ":cuid7"    => $cuid,
                    ":cuid8"    => $cuid,
                    ":limit"    => $lmt
                );
                $datas = $QO->execute($params);
            } else if ( $rval >=75 && $rval < 90 ) {
                /*
                 * ARTICLES ANGELS :
                 *      L'algorithme choisit au hasard entre une TENDANCE sans plus de spécificités.
                 */
                $case = "ANGL_TRD_WEVR";
                $QO = new QUERY("qryl4tqrsuggn11_ttm");
                $params = array(
                    ":cuid1"    => $cuid,
                    ":limit"    => $lmt
                );
                $datas = $QO->execute($params);
            } else {
                /*
                 * COMPTES ANGELS :
                 *      L'algorithme choisit au hasard entre un PROFIL sans plus de spécificités.
                 */
                $case = "ANGL_PFL_WEVR";
                $QO = new QUERY("qryl4tqrsuggn12_ttm");
                $params = array(
                    ":cuid1"    => $cuid,
                    ":limit"    => $lmt
                );
                $datas = $QO->execute($params);
            }
        }
        
        return [$case,$datas];
    }

    private function sugg_getAngels_Profils ($rnmd) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$rnmd]);
        
        /*
         * ETAPE :
         *      On lance la requete auprès du serveur
         */
        $QO = new QUERY("qryl4tqrsuggn1");
        $params = array(":rnmd" => $rnmd);
        $datas = $QO->execute($params);
        
        $r = FALSE;
        if ( $datas ) {
            $r = ( count($datas) === 1 ) ? $datas[0] : $datas;
        }
        
        return $r;
    }
    
    private function sugg_getAngels_Trends ($rnmd) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$rnmd]);
        
        /*
         * ETAPE :
         *      On lance la requete auprès du serveur
         */
        $QO = new QUERY("qryl4tqrsuggn2");
        $params = array(":rnmd" => $rnmd);
        $datas = $QO->execute($params);
        
        $r = FALSE;
        if ( $datas ) {
            $r = ( count($datas) === 1 ) ? $datas[0] : $datas;
        }
        
        return $r;
    }
    
    
    
    /************************************************************************************************************************************************************/
    /************************************************************************ LASTA SCOPE ***********************************************************************/
    
    public function lasta_GetLastActivities ($pueid, $cueid = NULL, $_LMT = NULL, $FE_MD = FALSE) {
        /*
         * [RAPPEL 09-11-15] @author
         *      On ne traite que le cas des articles TRD dans cette version car c'est le plus simple.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$pueid]);
        
        /*
         * ETAPE :
         *      On récupère la table de l'utilisateur
         */
        $PA = new PROD_ACC();
        $ptab = $PA->exists($pueid,TRUE);
        if (! $ptab ) {
            return "__ERR_VOL_U_G";
        }
        if ( $cueid ) {
            $cutab = ( $pueid === $cueid ) ? $ptab : $PA->exists($cueid,TRUE);
            if (! $cutab ) {
                return "__ERR_VOL_CU_GONE";
            }
            
            /*
             * ETAPE : 
             *      On vérifie la relation entre les deux protagonistes
             */
            if ( $pueid !== $cueid ) {
                $RL = new RELATION();
                $case = $RL->onread_relation_exists_fecase($cutab["pdaccid"],$ptab["pdaccid"]);
            } else {
                $case = "_REL_HOME";
            }
        }
        
//        var_dump(__LINE__,$case);
//        var_dump(__LINE__,$cutab,$ptab);
//        exit();
        
        /*
         * ETAPE :
         *      Déterminer la limite
         */
        $lmt = ( $_LMT ) ? $_LMT : $this->LASTA_DFT_LMT;
        
        $datas = [];
        /*
         * ETAPE :
         *      On récupère les données sur les derniers articles ayant étant la source de l'activité de l'utilisateur pivot.
         *      En fonction de l'utilisateur CU on choisit le bon cas de figure
         */
        
        if ( !$cutab ) {
//            $QO = new QUERY("qryl4tqrlastan1");
            $QO = new QUERY("qryl4tqrlastan1_neovb30_0416001");
            $params = array(
                ":uid"      => $ptab["pdaccid"], 
                ":limit"    => $lmt
            );
            $rds = $QO->execute($params);
            if ( $rds ) {
                $datas = $rds;
            }

//            $QO = new QUERY("qryl4tqrlastan2");
            $QO = new QUERY("qryl4tqrlastan2_neovb30_0416001");
            $params = array(
                ":uid"      => $ptab["pdaccid"], 
                ":are_frd"  => $ptab["pdaccid"], 
                ":limit"    => $lmt
            );
            $eds = $QO->execute($params);
            if ( $eds && $rds ) {
                $datas = array_merge($datas,$eds);
            } else if ($eds) {
                $datas = $eds;
            }
            
            $QO = new QUERY("qryl4tqrlastan5");
            $params = array(
                ":uid"      => $ptab["pdaccid"], 
                ":limit"    => $lmt);
            $fds = $QO->execute($params);
            if ( $datas && $fds ) {
                $datas = array_merge($datas,$fds);
            } else if ( $fds ) {
                $datas = $fds;
            }
        }
        else if ( $case && $case === "_REL_HOME" ) {
//            $QO = new QUERY("qryl4tqrlastan1_all");
            $QO = new QUERY("qryl4tqrlastan1_all_neovb30_0416001");
            $params = array(
                ":uid"      => $ptab["pdaccid"], 
                ":limit"    => $lmt);
            $rds = $QO->execute($params);
            if ( $rds ) {
                $datas = $rds;
            }

//            $QO = new QUERY("qryl4tqrlastan2_all");
            $QO = new QUERY("qryl4tqrlastan2_all_neovb30_0416001");
            $params = array(
                ":uid"      => $ptab["pdaccid"], 
                ":limit"    => $lmt);
            $eds = $QO->execute($params);
            if ( $eds && $rds ) {
                $datas = array_merge($datas,$eds);
            } else if ($eds) {
                $datas = $eds;
            }
            
            $QO = new QUERY("qryl4tqrlastan5_all");
            $params = array(
                ":uid"      => $ptab["pdaccid"], 
                ":limit"    => $lmt);
            $fds = $QO->execute($params);
            if ( $datas && $fds ) {
                $datas = array_merge($datas,$fds);
            } else if ($fds) {
                $datas = $fds;
            }
            
//            var_dump(__FILE__,__FUNCTION__,__LINE__,[$rds,$eds,$fds]);
//            exit();
            
//        } else if ( $cutab && in_array($case,["_REL_FRD","_REL_FEO"] ) ) {
        } 
        else if ( $cutab && in_array($case,["_REL_FRD"] ) ) {
            
            /*
             * [NOTE]
             *      Dans ce cas on a affaire à AMI et D_FOLW.
             *      On récupère les données impliquant des articles (TOUS) sur TRGT et les amis et DFLOW en commun.
             */
//            $QO = new QUERY("qryl4tqrlastan1_spe1");
            $QO = new QUERY("qryl4tqrlastan1_spe1_neovb30_0416001");
            $params = array(
                ":tguid"    => $ptab["pdaccid"], 
                ":tguid1"   => $ptab["pdaccid"], 
                ":cuid"     => $cutab["pdaccid"], 
                ":cuid1"    => $cutab["pdaccid"], 
                ":cuid2"    => $cutab["pdaccid"], 
                ":cuid3"    => $cutab["pdaccid"], 
                ":cuid4"    => $cutab["pdaccid"], 
                ":limit"    => $lmt
            );
            $rds = $QO->execute($params);
            if ( $rds ) {
                $datas = $rds;
            }

//            $QO = new QUERY("qryl4tqrlastan2_spe1");
            $QO = new QUERY("qryl4tqrlastan2_spe1_neovb30_0416001");
            $params = array(
                ":tguid"    => $ptab["pdaccid"], 
                ":tguid1"   => $ptab["pdaccid"], 
                ":cuid"     => $cutab["pdaccid"], 
                ":cuid1"    => $cutab["pdaccid"], 
                ":cuid2"    => $cutab["pdaccid"], 
                ":cuid3"    => $cutab["pdaccid"], 
                ":cuid4"    => $cutab["pdaccid"],
                ":limit"    => $lmt
            );
            $eds = $QO->execute($params);
            if ( $eds && $rds ) {
                $datas = array_merge($datas,$eds);
            } else if ($eds) {
                $datas = $eds;
            }
            
            $QO = new QUERY("qryl4tqrlastan5_frd");
            $params = array(
                ":tguid"    => $ptab["pdaccid"], 
                ":tguid1"   => $ptab["pdaccid"],
                ":cuid"     => $cutab["pdaccid"], 
                ":cuid1"    => $cutab["pdaccid"], 
                ":cuid2"    => $cutab["pdaccid"], 
                ":cuid3"    => $cutab["pdaccid"], 
                ":cuid4"    => $cutab["pdaccid"],
                ":limit"    => $lmt
            );
            $fds = $QO->execute($params);
            if ( $datas && $fds ) {
                $datas = array_merge($datas,$fds);
            } else if ($fds) {
                $datas = $fds;
            }
            
        } 
        else {
            /*
             * [NOTE]
             *      On récupère les données impliquant des articles TRD sur TRGT et tous, sur les amis et DFLOW en commun.
             */
//            $QO = new QUERY("qryl4tqrlastan1_spe2");
            $QO = new QUERY("qryl4tqrlastan1_spe2_neovb30_0416001");
            $params = array(
                ":tguid"    => $ptab["pdaccid"], 
                ":cuid"     => $cutab["pdaccid"], 
                ":cuid1"    => $cutab["pdaccid"], 
                ":cuid2"    => $cutab["pdaccid"], 
                ":cuid3"    => $cutab["pdaccid"], 
                ":cuid4"    => $cutab["pdaccid"], 
                ":limit"    => $lmt
            );
            $rds = $QO->execute($params);
            if ( $rds ) {
                $datas = $rds;
            }
            
//            $QO = new QUERY("qryl4tqrlastan2_spe2");
            $QO = new QUERY("qryl4tqrlastan2_spe2_neovb30_0416001");
            $params = array(
                ":tguid"    => $ptab["pdaccid"], 
                ":cuid"     => $cutab["pdaccid"], 
                ":cuid1"    => $cutab["pdaccid"], 
                ":cuid2"    => $cutab["pdaccid"], 
                ":cuid3"    => $cutab["pdaccid"], 
                ":cuid4"    => $cutab["pdaccid"], 
                ":limit"    => $lmt
            );
            $eds = $QO->execute($params);
//            var_dump(__LINE__,__FUNCTION__,__FILE__,$eds);
//            exit();
            if ( $eds && $rds ) {
                $datas = array_merge($datas,$eds);
            } else if ($eds) {
                $datas = $eds;
            }
            
            $QO = new QUERY("qryl4tqrlastan5_notfrd");
            $params = array(
                ":tguid"    => $ptab["pdaccid"], 
                ":cuid"     => $cutab["pdaccid"], 
                ":cuid1"    => $cutab["pdaccid"], 
                ":cuid2"    => $cutab["pdaccid"], 
                ":cuid3"    => $cutab["pdaccid"], 
                ":cuid4"    => $cutab["pdaccid"],
                ":limit"    => $lmt
            );
            $fds = $QO->execute($params);
            if ( $datas && $fds ) {
                $datas = array_merge($datas,$fds);
            } else if ($fds) {
                $datas = $fds;
            }
        }
        
//        var_dump(__LINE__,$rds);
//        exit();
        
        $fds = [];
        if ( $datas ) {
           /*
            * ETAPE :
            *      On trie les données par ordre décroissant
            */
            usort($datas, function($a,$b){
                if ( floatval($a['case_date']) === floatval($b['case_date']) ) {
                    return 0;
                }
                return ( floatval($a['case_date']) < floatval($b['case_date']) ) ? 1 : -1;
            });
     
        
            /*
             * ETAPE :
             *      On sélectionne la bonne limite
             */
            $lmt = ( count($datas) > $lmt ) ? $lmt : count($datas);
            
            $fds = array_slice($datas, 0, $lmt);
            
            $F__ = [];
            if ( $FE_MD ) {
                foreach ($fds as $atb) {
                    $F__[] = [
                        "aid"       => $atb['art_eid'],
                        "aim"       => $atb['art_pdpic_realpath'],
                        "adsc"      => html_entity_decode($atb['art_desc']),
                        "aplk"      => "/f/".$atb['art_prmlk'],
                        "adt"       => $atb['art_cdate_tstamp'],
                        "acz_tp"    => $atb['case_type'],
                        "acz_id"    => $atb['case_eid'],
                        "acz_dt"    => $atb['case_date'],
                    ];
                }
                
                $fds = $F__;
            }
        }
        
        return $fds;
    }
    
    
    public function lasta_GetLastActivities_Network ($pueid, $_LMT = NULL, $FE_MD = FALSE, $_OPTIONS = NULL) {
        /*
         * [RAPPEL 09-11-15] @author
         *      On ne traite que le cas des articles TRD dans cette version car c'est le plus simple.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$pueid]);
        
        /*
         * ETAPE :
         *      On récupère la table de l'utilisateur
         */
        $PA = new PROD_ACC();
        $ptab = $PA->exists($pueid,TRUE);
        if (! $ptab ) {
            return "__ERR_VOL_U_G";
        }
        
        
        /*
         * ETAPE :
         *      Déterminer la limite
         */
        $lmt = ( $_LMT ) ? $_LMT : $this->LASTA_DFT_LMT;
        
        $datas = [];
        /*
         * ETAPE :
         *      On récupère les données sur les derniers articles ayant étant la source de l'activité de l'utilisateur pivot.
         */
        //REACTIONS
//        $QO = new QUERY("qryl4tqrlastan3");
        $QO = new QUERY("qryl4tqrlastan3_neovb30_0416001");
        $params = array(
            ":cuid"     => $ptab["pdaccid"], 
            ":cuid1"    => $ptab["pdaccid"], 
            ":cuid2"    => $ptab["pdaccid"], 
            ":cuid3"    => $ptab["pdaccid"], 
            ":cuid4"    => $ptab["pdaccid"], 
            ":cuid5"    => $ptab["pdaccid"], 
            ":cuid6"    => $ptab["pdaccid"], 
            ":cuid7"    => $ptab["pdaccid"], 
            ":cuid8"    => $ptab["pdaccid"], 
            ":cuid9"    => $ptab["pdaccid"], 
            ":limit"    => $lmt
        );
        $rds = $QO->execute($params);
        if ( $rds ) {
            $datas = $rds;
        }
//        var_dump("RDS",$rds);
        
        //EVAL
//        $QO = new QUERY("qryl4tqrlastan4");
        $QO = new QUERY("qryl4tqrlastan4_neovb30_0416001");
        $params = array(
            ":cuid"     => $ptab["pdaccid"], 
            ":cuid1"    => $ptab["pdaccid"], 
            ":cuid2"    => $ptab["pdaccid"], 
            ":cuid3"    => $ptab["pdaccid"], 
            ":cuid4"    => $ptab["pdaccid"], 
            ":cuid5"    => $ptab["pdaccid"], 
            ":cuid6"    => $ptab["pdaccid"], 
            ":cuid7"    => $ptab["pdaccid"], 
            ":cuid8"    => $ptab["pdaccid"],
            ":cuid9"    => $ptab["pdaccid"], 
            ":cuid10"   => $ptab["pdaccid"],  
            ":cuid11"   => $ptab["pdaccid"],  
            ":limit"    => $lmt
        );
        $eds = $QO->execute($params);
        if ( $eds && $rds ) {
            $datas = array_merge($datas,$eds);
        } else if ($eds) {
            $datas = $eds;
        }
//        var_dump("EDS",$eds);
        
        
        //FAVORITE
        $QO = new QUERY("qryl4tqrlastan6");
        $params = array(
            ":cuid"     => $ptab["pdaccid"], 
            ":cuid1"    => $ptab["pdaccid"], 
            ":cuid2"    => $ptab["pdaccid"], 
            ":cuid3"    => $ptab["pdaccid"], 
            ":cuid4"    => $ptab["pdaccid"], 
            ":cuid5"    => $ptab["pdaccid"], 
            ":cuid6"    => $ptab["pdaccid"], 
            ":cuid7"    => $ptab["pdaccid"], 
            ":cuid8"    => $ptab["pdaccid"],
            ":cuid9"    => $ptab["pdaccid"],
            ":limit"    => $lmt
        );
        $fvds = $QO->execute($params);
        if ( $datas && $fvds ) {
            $datas = array_merge($datas,$fvds);
        } else if ($fvds) {
            $datas = $fvds;
        }
        
        //TSM
        if (! ( $_OPTIONS && $_OPTIONS["excludes"] && in_array("_EXCL_TSM", $_OPTIONS["excludes"]) ) ) {
            $QO = new QUERY("qryl4tqrlastan7");
            $params = array(
                ":cuid"     => $ptab["pdaccid"], 
                ":cuid1"    => $ptab["pdaccid"], 
                ":cuid2"    => $ptab["pdaccid"], 
                ":cuid3"    => $ptab["pdaccid"], 
                ":cuid4"    => $ptab["pdaccid"], 
                ":cuid5"    => $ptab["pdaccid"], 
                ":cuid6"    => $ptab["pdaccid"], 
                ":cuid7"    => $ptab["pdaccid"], 
                ":cuid8"    => $ptab["pdaccid"], 
                ":limit"    => $lmt
            );
            $tstds = $QO->execute($params);
            if ( $datas && $tstds ) {
                $datas = array_merge($datas,$tstds);
            } else if ($tstds) {
                $datas = $tstds;
            }
        }
        
        
        //TSR
        if (! ( $_OPTIONS && $_OPTIONS["excludes"] && in_array("_EXCL_TSR", $_OPTIONS["excludes"]) ) ) {
            $QO = new QUERY("qryl4tqrlastan8");
            $params = array(
                ":cuid"     => $ptab["pdaccid"], 
                ":cuid1"    => $ptab["pdaccid"], 
                ":cuid2"    => $ptab["pdaccid"], 
                ":cuid3"    => $ptab["pdaccid"], 
                ":cuid4"    => $ptab["pdaccid"], 
                ":cuid5"    => $ptab["pdaccid"], 
                ":cuid6"    => $ptab["pdaccid"], 
                ":cuid7"    => $ptab["pdaccid"], 
                ":cuid8"    => $ptab["pdaccid"],
                ":limit"    => $lmt
            );
            $tsrds = $QO->execute($params);
            if ( $datas && $tsrds ) {
                $datas = array_merge($datas,$tsrds);
            } else if ($tsrds) {
                $datas = $tsrds;
            }
        }
        
        //TSL
        if (! ( $_OPTIONS && $_OPTIONS["excludes"] && in_array("_EXCL_TSL", $_OPTIONS["excludes"]) ) ) {
            $QO = new QUERY("qryl4tqrlastan9");
            $params = array(
                ":cuid"     => $ptab["pdaccid"], 
                ":cuid1"    => $ptab["pdaccid"], 
                ":cuid2"    => $ptab["pdaccid"], 
                ":cuid3"    => $ptab["pdaccid"], 
                ":cuid4"    => $ptab["pdaccid"], 
                ":cuid5"    => $ptab["pdaccid"], 
                ":cuid6"    => $ptab["pdaccid"], 
                ":cuid7"    => $ptab["pdaccid"], 
                ":cuid8"    => $ptab["pdaccid"],
                ":limit"    => $lmt
            );
            $tslds = $QO->execute($params);
            if ( $datas && $tslds ) {
                $datas = array_merge($datas,$tslds);
            } else if ($tslds) {
                $datas = $tslds;
            }
        }
        
        
        $fds = [];
        if ( $datas ) {
           /*
            * ETAPE :
            *      On trie les données par ordre décroissant
            */
            usort($datas, function($a,$b){
                if ( floatval($a['case_date']) === floatval($b['case_date']) ) {
                    return 0;
                }
                return ( floatval($a['case_date']) < floatval($b['case_date']) ) ? 1 : -1;
            });
     
        
            /*
             * ETAPE :
             *      On sélectionne la bonne limite
             */
            $lmt = ( count($datas) > $lmt ) ? $lmt : count($datas);
            
            $fds = array_slice($datas, 0, $lmt);
            
            $F__ = [];
            if ( $FE_MD ) {
                foreach ($fds as $atb) {
                   /*
                    * [DEPUIS 06-05-16]
                    *       On crée un identifiant externe à la voléé. C'est le seul moyen qu'on a pour faire fonctionner le système en ce qui concerne EVAL/
                    *       En effet, EVAL n'a pas d'identifiant externe. 
                    *       Ensuite, lorsqu'on voudra faire une recherche, on va extraire l'ID INTERNE et s'en servir comme ID de REF.
                    * [NOTE 06-05-16]
                    *       On choisit n'importe lequel ENTITY qui hérite de PROD_ENTY pour accéder aux fonction d'encodage.
                    */
                    if ( in_array($atb['case_type'],["eval","tsl"]) && $atb['case_id'] ) {
                        $MYS = new MYSTERY();
                        $case_eid = $MYS->entity_ieid_encode(intval($atb['case_date']),intval($atb['case_id']));
                    } else {
                        $case_eid = $atb['case_eid'];
                    }
                    
                    if ( in_array($atb['case_type'],["tsm","tsr","tsl"]) ) {
                        $F__[] = [
                            "aid"           => $atb['tst_eid'],
                            "aim"           => NULL,
                            "adsc"          => html_entity_decode($atb['tst_msg']),
                            "aplk"          => "/f/sts/".$atb['tst_prmlk'],
                            "adt"           => $atb['tst_adddate_tstamp'],
                            "acz_tp"        => $atb['case_type'],
                            "acz_id"        => $case_eid,
                            "acz_dt"        => $atb['case_date'],
                            /* ACTOR SCOPE */
                            "acz_aeid"      => $atb['case_aeid'],
                            "acz_afn"       => $atb['case_afn'],
                            "acz_apsd"      => $atb['case_apsd'],
                            "acz_appi"      => $PA->onread_acquiere_pp_datas($atb["case_aid"])["pic_rpath"],
                        ];
                    } else {
                        $F__[] = [
                            "aid"           => $atb['art_eid'],
                            "aim"           => $atb['art_pdpic_realpath'],
                            "adsc"          => html_entity_decode($atb['art_desc']),
                            "aplk"          => "/f/".$atb['art_prmlk'],
                            "adt"           => $atb['art_cdate_tstamp'],
                            "avid"          => ( $atb['art_is_video'] ) ? TRUE : FALSE,
                            "acz_tp"        => $atb['case_type'],
                            "acz_id"        => $case_eid,
                            "acz_dt"        => $atb['case_date'],
                            /* ACTOR SCOPE */
                            "acz_aeid"      => $atb['case_aeid'],
                            "acz_afn"       => $atb['case_afn'],
                            "acz_apsd"      => $atb['case_apsd'],
                            "acz_appi"      => $PA->onread_acquiere_pp_datas($atb["case_aid"])["pic_rpath"],
                        ];
                    }
                }
                
                $fds = $F__;
            }
            
        }
        
        
        return $fds;
    }
    
    
    /*
     * [NOTE 05-05-16]
     *      Cette fonction est un HACK principalement destiné à contouner le fait qu'il m'est AUJ impossible de retravailler la fonction de référence
     *      ... pour prendre en compte la particularité de FROM.
     *      Cette fonction est surtout utile dans le cas de NEWSFEED.
     */
    public function lasta_GetLastActivities_Network_Newer ($pueid, $refdefs, $_LMT = NULL, $FE_MD = FALSE ) {
        /*
         * [NOTE 05-05-16]
         *      La variable $refdefs contient les références pour chacune des SECTIONS traitées : REACTS, LIKES, FAVS.
         *      Ces références permettent constitue la LIMIT viturelle. Dans le cas contraire, on aurait trop de données à traiter.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$pueid, $refdefs]);
        
        /*
         * ETAPE :
         *      On récupère la table de l'utilisateur
         */
        $PA = new PROD_ACC();
        $ptab = $PA->exists($pueid,TRUE);
        if (! $ptab ) {
            return "__ERR_VOL_U_G";
        }
        
        /*
         * ETAPE :
         *      Au cas où nous n'avons pas accès à une REF, on se base sur l'instant présent.
         *      Cependant, pour des raisons de fiabilité on vérifie sur les x denrières secondes.
         *      En effet, il faut prendre en compte le temps de  LATENCE des requêtes mais aussi que ces dernières sont exécutés par interval de x secondes
         */
        $now = round(microtime(TRUE)*1000);
        $now_reli = $now - ( 20 * 1000 );
        
        //ARE = ArticleREaction
        $refid_are = ( $refdefs["ARE"]["refid"] ) ? : "any";
        $reftm_are = ( $refdefs["ARE"]["reftm"] ) ? : $now_reli;
        
        //ALI = ArticleLIke
        $refid_ali = ( $refdefs["ALI"]["refid"] ) ? : NULL;
        $reftm_ali = ( $refdefs["ALI"]["reftm"] ) ? : $now_reli;
        
        //AFV = ArticleFaVorite
        $refid_afv = ( $refdefs["AFV"]["refid"] ) ? : "any";
        $reftm_afv = ( $refdefs["AFV"]["reftm"] ) ? : $now_reli;
        
        //TSM = TeStyMessage
        $refid_tsm = ( $refdefs["TSM"]["refid"] ) ? : "any";
        $reftm_tsm = ( $refdefs["TSM"]["reftm"] ) ? : $now_reli;
        
        //TSR = TeStyReaction
        $refid_tsr = ( $refdefs["TSR"]["refid"] ) ? : "any";
        $reftm_tsr = ( $refdefs["TSR"]["reftm"] ) ? : $now_reli;
        
        //TSL = TeStyLike
        $refid_tsl = ( $refdefs["TSL"]["refid"] ) ? : "any";
        $reftm_tsl = ( $refdefs["TSL"]["reftm"] ) ? : $now_reli;
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,[$refid_are,$reftm_are,$refid_ali,$reftm_ali,$refid_afv,$reftm_afv]);
//        exit();
        
        /*
         * ETAPE :
         *      Déterminer la limite.
         * [NOTE 06-05-16]
         *      L'idéal serait de ne pas mettre de LIMIT pour respecter le schéma fonctionnelle.
         *      Sauf, qu'il y a un risque : que toutes les occurrences soient renvoyées.
         *      Il faut donc mettre une valeur LIMIT arbritraire pour s'assurer que ça n'arrivera jamais.
         */
        $lmt = ( $_LMT ) ? $_LMT : 20;
        
        $datas = [];
        /*
         * ETAPE :
         *      On récupère les données sur les derniers articles ayant étant la source de l'activité de l'utilisateur pivot.
         */
        $QO = new QUERY("qryl4tqrlastan3_neovb30_0416001_spe_top");
        $params = array(
            ":cuid"     => $ptab["pdaccid"], 
            ":cuid1"    => $ptab["pdaccid"], 
            ":cuid2"    => $ptab["pdaccid"], 
            ":cuid3"    => $ptab["pdaccid"], 
            ":cuid4"    => $ptab["pdaccid"], 
            ":cuid5"    => $ptab["pdaccid"], 
            ":cuid6"    => $ptab["pdaccid"], 
            ":cuid7"    => $ptab["pdaccid"], 
            ":cuid8"    => $ptab["pdaccid"], 
            ":cuid9"    => $ptab["pdaccid"], 
            ":refid"    => $refid_are, 
            ":reftm"    => $reftm_are, 
            ":limit"    => $lmt
        );
        $rds = $QO->execute($params);
        if ( $rds ) {
            $datas = $rds;
        }
//        var_dump("RDS",$rds);
        
        
       /*
        * [DEPUIS 06-05-16]
        *       On crée un identifiant externe à la voléé. C'est le seul moyen qu'on a pour faire fonctionner le système en ce qui concerne EVAL/
        *       En effet, EVAL n'a pas d'identifiant externe. 
        *       Ensuite, lorsqu'on voudra faire une recherche, on va extraire l'ID INTERNE et s'en servir comme ID de REF.
        * [NOTE 06-05-16]
        *       On choisit n'importe lequel ENTITY qui hérite de PROD_ENTY pour accéder aux fonction d'encodage.
        */
        if ( $refid_ali ) {
            $MYS = new MYSTERY();
            $refid_ali = $MYS->entity_ieid_decode($refid_ali)["id"];
        } else {
            $refid_ali = 0;
        }
        $QO = new QUERY("qryl4tqrlastan4_neovb30_0416001_spe_top");
        $params = array(
            ":cuid"      => $ptab["pdaccid"], 
            ":cuid1"     => $ptab["pdaccid"], 
            ":cuid2"     => $ptab["pdaccid"], 
            ":cuid3"     => $ptab["pdaccid"], 
            ":cuid4"     => $ptab["pdaccid"], 
            ":cuid5"     => $ptab["pdaccid"], 
            ":cuid6"     => $ptab["pdaccid"], 
            ":cuid7"     => $ptab["pdaccid"], 
            ":cuid8"     => $ptab["pdaccid"],
            ":cuid9"     => $ptab["pdaccid"], 
            ":cuid10"    => $ptab["pdaccid"],  
            ":cuid11"    => $ptab["pdaccid"],  
            ":refid"    => $refid_ali, 
            ":reftm"    => $reftm_ali, 
            ":limit"    => $lmt
        );
        $eds = $QO->execute($params);
        if ( $eds && $rds ) {
            $datas = array_merge($datas,$eds);
        } else if ($eds) {
            $datas = $eds;
        }
//        var_dump("EDS",$eds);
        
        
        $QO = new QUERY("qryl4tqrlastan6_spe_top");
        $params = array(
            ":cuid"         => $ptab["pdaccid"], 
            ":cuid1"        => $ptab["pdaccid"], 
            ":cuid2"        => $ptab["pdaccid"], 
            ":cuid3"        => $ptab["pdaccid"], 
            ":cuid4"        => $ptab["pdaccid"], 
            ":cuid5"        => $ptab["pdaccid"], 
            ":cuid6"        => $ptab["pdaccid"], 
            ":cuid7"        => $ptab["pdaccid"], 
            ":cuid8"        => $ptab["pdaccid"],
            ":cuid9"        => $ptab["pdaccid"],
            ":refid"        => $refid_afv, 
            ":reftm"        => $reftm_afv, 
            ":limit"        => $lmt
        );
        $fvds = $QO->execute($params);
        if ( $datas && $fvds ) {
            $datas = array_merge($datas,$fvds);
        } else if ($fvds) {
            $datas = $fvds;
        }
        
        
        /************* TESTY SCOPE *************/
        
        //TSM
        $QO = new QUERY("qryl4tqrlastan7_spe_top");
        $params = array(
            ":cuid"     => $ptab["pdaccid"], 
            ":cuid1"    => $ptab["pdaccid"], 
            ":cuid2"    => $ptab["pdaccid"], 
            ":cuid3"    => $ptab["pdaccid"], 
            ":cuid4"    => $ptab["pdaccid"], 
            ":cuid5"    => $ptab["pdaccid"], 
            ":cuid6"    => $ptab["pdaccid"], 
            ":cuid7"    => $ptab["pdaccid"], 
            ":cuid8"    => $ptab["pdaccid"],
            ":refid"    => $refid_tsm, 
            ":reftm"    => $reftm_tsm, 
            ":limit"    => $lmt
        );
        $tstds = $QO->execute($params);
        if ( $datas && $tstds ) {
            $datas = array_merge($datas,$tstds);
        } else if ($tstds) {
            $datas = $tstds;
        }
        
        //TSR
        $QO = new QUERY("qryl4tqrlastan8_spe_top");
        $params = array(
            ":cuid"     => $ptab["pdaccid"], 
            ":cuid1"    => $ptab["pdaccid"], 
            ":cuid2"    => $ptab["pdaccid"], 
            ":cuid3"    => $ptab["pdaccid"], 
            ":cuid4"    => $ptab["pdaccid"], 
            ":cuid5"    => $ptab["pdaccid"], 
            ":cuid6"    => $ptab["pdaccid"], 
            ":cuid7"    => $ptab["pdaccid"], 
            ":cuid8"    => $ptab["pdaccid"],
            ":refid"    => $refid_tsr, 
            ":reftm"    => $reftm_tsr, 
            ":limit"    => $lmt
        );
        $tsrds = $QO->execute($params);
        if ( $datas && $tsrds ) {
            $datas = array_merge($datas,$tsrds);
        } else if ($tsrds) {
            $datas = $tsrds;
        }
        
        //TSL
        /*
        * [DEPUIS 17-05-16]
        *       On crée un identifiant externe à la voléé. C'est le seul moyen qu'on a pour faire fonctionner le système en ce qui concerne EVAL/
        *       En effet, EVAL n'a pas d'identifiant externe. 
        *       Ensuite, lorsqu'on voudra faire une recherche, on va extraire l'ID INTERNE et s'en servir comme ID de REF.
        * [NOTE 17-05-16]
        *       On choisit n'importe lequel ENTITY qui hérite de PROD_ENTY pour accéder aux fonction d'encodage.
        */
        if ( $refid_tsl ) {
            $MYS = new MYSTERY();
            $refid_tsl = $MYS->entity_ieid_decode($refid_tsl)["id"];
        } else {
            $refid_tsl = 0;
        }
        $QO = new QUERY("qryl4tqrlastan9_spe_top");
        $params = array(
            ":cuid"     => $ptab["pdaccid"], 
            ":cuid1"    => $ptab["pdaccid"], 
            ":cuid2"    => $ptab["pdaccid"], 
            ":cuid3"    => $ptab["pdaccid"], 
            ":cuid4"    => $ptab["pdaccid"], 
            ":cuid5"    => $ptab["pdaccid"], 
            ":cuid6"    => $ptab["pdaccid"], 
            ":cuid7"    => $ptab["pdaccid"], 
            ":cuid8"    => $ptab["pdaccid"],
            ":refid"    => $refid_tsl, 
            ":reftm"    => $reftm_tsl,
            ":limit"    => $lmt
        );
        $tslds = $QO->execute($params);
        if ( $datas && $tslds ) {
            $datas = array_merge($datas,$tslds);
        } else if ($tslds) {
            $datas = $tslds;
        }
        
        $fds = [];
        if ( $datas ) {
           /*
            * ETAPE :
            *      On trie les données par ordre décroissant
            */
            usort($datas, function($a,$b){
                if ( floatval($a['case_date']) === floatval($b['case_date']) ) {
                    return 0;
                }
                return ( floatval($a['case_date']) < floatval($b['case_date']) ) ? 1 : -1;
            });
     
        
            /*
             * ETAPE :
             *      On sélectionne la bonne limite
             */
            $lmt = ( count($datas) > $lmt ) ? $lmt : count($datas);
            
            $fds = array_slice($datas, 0, $lmt);
            
            /*
             * [DEPUIS 06-05-16]
             *      On crée
             */
            
            $F__ = [];
            if ( $FE_MD ) {
                foreach ($fds as $atb) {
                    
                   /*
                    * [DEPUIS 06-05-16]
                    *       On crée un identifiant externe à la voléé. C'est le seul moyen qu'on a pour faire fonctionner le système en ce qui concerne EVAL/
                    *       En effet, EVAL n'a pas d'identifiant externe. 
                    *       Ensuite, lorsqu'on voudra faire une recherche, on va extraire l'ID INTERNE et s'en servir comme ID de REF.
                    * [NOTE 06-05-16]
                    *       On choisit n'importe lequel ENTITY qui hérite de PROD_ENTY pour accéder aux fonction d'encodage.
                    */
//                    if ( $atb['case_type'] === "eval" && $atb['case_id'] ) {
                    if ( in_array($atb['case_type'],["eval","tsl"]) && $atb['case_id'] ) {
                        $MYS = new MYSTERY();
                        $case_eid = $MYS->entity_ieid_encode(intval($atb['case_date']),intval($atb['case_id']));
                    } else {
                        $case_eid = $atb['case_eid'];
                    }
                    
                    /*
                    $F__[] = [
                        "aid"           => $atb['art_eid'],
                        "aim"           => $atb['art_pdpic_realpath'],
                        "adsc"          => html_entity_decode($atb['art_desc']),
                        "aplk"          => "/f/".$atb['art_prmlk'],
                        "adt"           => $atb['art_cdate_tstamp'],
                        "acz_tp"        => $atb['case_type'],
                        "acz_id"        => $case_eid,
                        "acz_dt"        => $atb['case_date'],
                        // ACTOR SCOPE 
                        "acz_aeid"      => $atb['case_aeid'],
                        "acz_afn"       => $atb['case_afn'],
                        "acz_apsd"      => $atb['case_apsd'],
                        "acz_appi"      => $PA->onread_acquiere_pp_datas($atb["case_aid"])["pic_rpath"],
                    ];
                    //*/
                    
                    if ( in_array($atb['case_type'],["tsm","tsr","tsl"]) ) {
                        $F__[] = [
                            "aid"           => $atb['tst_eid'],
                            "aim"           => NULL,
                            "adsc"          => html_entity_decode($atb['tst_msg']),
                            "aplk"          => "/f/sts/".$atb['tst_prmlk'],
                            "adt"           => $atb['tst_adddate_tstamp'],
                            "acz_tp"        => $atb['case_type'],
                            "acz_id"        => $case_eid,
                            "acz_dt"        => $atb['case_date'],
                            /* ACTOR SCOPE */
                            "acz_aeid"      => $atb['case_aeid'],
                            "acz_afn"       => $atb['case_afn'],
                            "acz_apsd"      => $atb['case_apsd'],
                            "acz_appi"      => $PA->onread_acquiere_pp_datas($atb["case_aid"])["pic_rpath"],
                        ];
                    } else {
                        $F__[] = [
                            "aid"           => $atb['art_eid'],
                            "aim"           => $atb['art_pdpic_realpath'],
                            "adsc"          => html_entity_decode($atb['art_desc']),
                            "aplk"          => "/f/".$atb['art_prmlk'],
                            "adt"           => $atb['art_cdate_tstamp'],
                            "acz_tp"        => $atb['case_type'],
                            "acz_id"        => $case_eid,
                            "acz_dt"        => $atb['case_date'],
                            /* ACTOR SCOPE */
                            "acz_aeid"      => $atb['case_aeid'],
                            "acz_afn"       => $atb['case_afn'],
                            "acz_apsd"      => $atb['case_apsd'],
                            "acz_appi"      => $PA->onread_acquiere_pp_datas($atb["case_aid"])["pic_rpath"],
                        ];
                    }
                    
                }
                
                $fds = $F__;
            }
            
        }
        
        
        return $fds;
    }
    
    
    /**********************************************************************************************************************************************************************/
    /************************************************************************ PRODDB_REACTION SCOPE ***********************************************************************/
    
    private function pdreact_treat_msg($s, &$usertags = NULL, &$kws = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$s]);
        
        /*
         * On vérifie sa longueur en prennant de ne pas compter les '#' de mot-clé et '@' pour les tags d'utilisateurs
         */
//        $_TST_MSG_RGX = "/^(?=.*[a-z])[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{1,1000}$/i"; //[DEPUIS 07-07-16]
        $_TST_MSG_RGX = "/^[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{1,1000}$/i";
        if ( is_string($s) && !preg_match($_TST_MSG_RGX,$s) ) {
            return "__ERR_VOL_MSG_MSM";
        }
        
        $TH = new TEXTHANDLER();
        
        //On extrait les hashstags si le texte en comporte
        $kws = $TH->extract_prod_keywords($s);
        //On extrait les usertags si le texte en comporte
        $usertags = $TH->extract_tqr_usertags($s);
        
        
        $ns = $TH->secure_text($s);
        
        /*
         * [DEPUIS 05-02-16]
         *      On convertit les éventuels EMOJIS en une correspondance HTML.
         */
        $ns = $TH->replace_emojis_in($ns);
        
        return $ns;
    }
    
    
    public function pdreact_add ($author,$text,$locip,$ssid,$uagnt=NULL) {
        /*
         * ATTENTION :
         * [NOTE 06-02-16]
         *      La méthode n'effectue aucune vérification pour des raisons de performances et pour accelérer le developpement de la nouvelle fonctionnalité.
         *      La méthode laisse donc le soin à CALLER d'effectuer les opérations de vérifications
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$author,$text,$locip,$ssid]);
        
        $errs = [];
        $kws = $usertags = NULL;
        $nmsg = $this->pdreact_treat_msg($text, $usertags, $kws);
        if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $nmsg) ) {
            $errs[]["msg"] = $nmsg;
        }
        
        /*
         * ETAPE :
         *  On vérifie que les champs sont valides
         */
        if ( count($errs) ) {
            return ["__ERR_VOL_MULTIPLE",$errs];
        }
        $omsg = $text;
        $text = $nmsg;
        
        $now = round(microtime(TRUE)*1000);
        $QO = new QUERY("qryl4pdreactn1");
        $params = array(
            ":text"     => $text,
            ":author"   => $author,
            ":locip"    => $locip,
            ":ssid"     => $ssid,
            ":uagnt"    => $uagnt,
            ":date"     => date("Y-m-d H:m:s",($now/1000)),
            ":tstamp"   => $now
        );
        $pdrid = $QO->execute($params);
        
        /*
         * ETAPE :
         *      On ajoute l'IDENTIFIANT EXTERNE
         */
        $pdreid = $this->entity_ieid_encode($now,$pdrid);
        $QO = new QUERY("qryl4pdreactn2");
        $params = array(":id" => $pdrid, ":eid" => $pdreid);  
        $QO->execute($params);
        
        
        /*
         * ETAPE 
         *      On lance l'opération d'entregistrement des UrlInContent.
         *      S'il n'y a pas d'URL, la méthode renvera FALSE.
         */
        $TXH = new TEXTHANDLER();
        if ( $TXH->ExtractURLs($omsg) ) {
            $URLIC = new URLIC();
            $args_urlic = [
                "t"     => $omsg,
                "uci"   => $pdrid,
                "ucei"  => $pdreid,
                "ucp"   => "UCTP_PDREACT",
                "ssid"  => $ssid,
                "locip" => $locip,
                "curl"  => NULL,
                "uagnt" => $uagnt
            ];
            $r = $URLIC->URLIC_oncreate($args_urlic["t"], $args_urlic["uci"], $args_urlic["ucei"], $args_urlic["ucp"], $args_urlic["ssid"], $args_urlic["locip"], $args_urlic["curl"], $args_urlic["uagnt"]);
//            var_dump(__LINE__,__FUNCTION__,__FILE__,$r);
        }
        
        /*
         * [DEPUIS 17-11-15]
         *      On traite le cas des mot-clés
         */
        if ( $kws ) {
            $HVIEW = new HVIEW();
            $args_urlic = [
                "t"     => $omsg,
                "hci"   => $pdrid,
                "hcei"  => $pdreid,
                "hcp"   => "HCTP_PDREACT",
                "ssid"  => $ssid,
                "locip" => $locip,
                "curl"  => NULL,
                "uagnt" => $uagnt
            ];
            $kws_r = $HVIEW->HSH_oncreate($args_urlic["t"], $args_urlic["hci"], $args_urlic["hcei"], $args_urlic["hcp"], $args_urlic["ssid"], $args_urlic["locip"], $args_urlic["curl"], $args_urlic["uagnt"]);
            /*
             * NOTE 
             *      On ne controle pas la valeur retour.
             *      L'objectif étant de ne pas arreter le process de création à cause de l'enregistrement des mots-clés.
             */
//            var_dump(__LINE__,__FUNCTION__,$kws_r);
        }
        
        /*
         * [DEPUIS 17-11-15]
         *      On traite le cas des USERTAGs
         * 
         * [NOTE]
         *      Ce code provient a été adapté de "enty.article.enty.php"
         *      Pour plus de précision en ce concerne les accents dans les usertags, se reporter aux commentaires dans le fichier cité.
         */
        if ( $usertags ) {
            $a = $usertags[1];
            
            /*
             * ETAPE :
             *      On transforme en LOWERCASE();
             */
            array_walk($a,function(&$i,$k){
                $i = strtolower($i);
            });
            
            /*
             * ETAPE :
             *      On supprime les doublons
             */
            $list_utags = array_unique($a);
            
            //Pour chaque pseudo, nous allons vérifier qu'il s'agit belle et bien d'un pseudo valide
            $PA = new PROD_ACC();
            foreach ($list_utags as $psd) {
                $utag_tab = $PA->exists_with_psd($psd,TRUE,TRUE);
                if ( $utag_tab ) {
                    /*
                     * ETAPE :
                     * On lance la procédure de création du tag au niveau de la base de données.
                     * On procède dans un premier temps à l'enregistrement puis à la mise à jour.
                     */
                    $now = round(microtime(TRUE)*1000);
                    
                    $QO = new QUERY("qryl4ustgn1");
                    $params = array(
                        /*
                         * [DEPUIS 13-08-16]
                         */
                        ":us_eid"   => $now, 
                        ":tgtuid"   => $utag_tab["pdaccid"], 
                        ":datecrea" => date("Y-m-d G:i:s",($now/1000)), 
                        ":tstamp"   => $now
                    );  
                    $ustid = $QO->execute($params);
                    
                    /*
                     * On procède à la mise à jour en insérant l'identifiant externe
                     */
                    $QO = new QUERY("qryl4ustgn2");
                    $params = array(
                        ":id"   => $ustid, 
                        ":eid"  => $this->entity_ieid_encode($now, $ustid)
                    );  
                    $QO->execute($params);
                        
                    /*
                     * On insère l'occurrence dans la classe fille dédiée à PDR
                     */
                    $QO = new QUERY("qryl4ustg_pdrn1");
                    $params = array(":id" => $ustid, ":pdrid" => $pdrid);  
                    $QO->execute($params);
                }
            }
        }
        
        /*
         * ETAPE :
         *      On récupère la table du nouveau commentaire créé
         */
        $pdrtab = $this->pdreact_exists_with_id($pdrid);
        
        return $pdrtab;
    }
    
    
    /********************************** EXISTS SCOPE **********************************/
    
    public function pdreact_exists ($pdr_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$pdr_eid]);
        
        $QO = new QUERY("qryl4pdreactn4");
        $params = array(":eid" => $pdr_eid);  
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : [];
    }
    
    public function pdreact_exists_with_id ($pdr_id) {
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$pdr_id]);
        
        $QO = new QUERY("qryl4pdreactn3");
        $params = array(":id" => $pdr_id);  
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : [];
    }
    
    
    /********************************** READ SCOPE **********************************/
    
    public function pdreact_read ($args) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$args]);
        
        if ( key_exists("id",$args) && $args["id"] ) {
            $datas = $this->pdreact_exists_with_id($args["id"]);
        } else if ( key_exists("eid",$args) && $args["eid"] ) {
            $datas = $this->pdreact_exists($args["eid"]);
        } else {
            return "__ERR_VOL_MSM_RULES";
        }
                
        if (! $datas ) {
            return "__ERR_VOL_GONE";
        }

        $pdr_tab = [
            "id"        => $datas["pdrct_id"],
            "eid"       => $datas["pdrct_eid"],
            "text"      => $datas["pdrct_text"],
            "author"    => $datas["pdrct_author"],
            "locip"     => $datas["pdrct_locip"],
            "ssid"      => $datas["pdrct_ssid"],
            "uagent"    => $datas["pdrct_uagnt"],
            "date"      => $datas["pdrct_datecrea"],
            "tstamp"    => $datas["pdrct_dcrea_tstamp"],
            "usertags"  => $this->pdreact_getUsertags($datas["pdrct_eid"],TRUE),
            "hashtags"  => $this->pdreact_getHashs($datas["pdrct_eid"],TRUE),
            "urls_set"  => NULL
        ];
        
        return $pdr_tab;
    }
    
    
    /********************************** ACQUIERE SCOPE **********************************/
    
    public function pdreact_getUsertags ($pdreid, $_WITH_FE_OPT = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $pdreid);
        
        $datas = NULL;
        $pdrtab = $this->pdreact_exists($pdreid);
        if (! $pdrtab ) {
            return "__ERR_VOL_PDRCT_GONE";
        } else {
            $datas = $this->pdreact_qry_usertags($pdrtab["pdrct_id"]); 
            if ( $datas && $_WITH_FE_OPT ) {
                array_walk($datas,function(&$i,$k){
                    $i = [
                        'eid'   => $i['ustg_eid'],
                        'ueid'  => $i['tgtueid'],
                        'ufn'   => $i['tgtufn'],
                        'upsd'  => $i['tgtupsd']
                    ];
                });
            } else if ( $datas && !$_WITH_FE_OPT ) {
                array_walk($datas,function(&$i,$k){
                    $i = [
                        'id'    => $i['ustg_id'],
                        'eid'   => $i['ustg_eid'],
                        'uid'   => $i['tgtuid'],
                        'ueid'  => $i['tgtueid'],
                        'ufn'   => $i['tgtufn'],
                        'upsd'  => $i['tgtupsd']
                    ];
                });
            }
        }
        return $datas;
    }
    
    
    public function pdreact_getHashs ($eid, $_WITH_FE_OPT = FALSE) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $eid);
        
        $datas = NULL;
        $pdrtab = $this->pdreact_exists($eid);
        if (! $pdrtab ) {
            return "__ERR_VOL_PDRCT_GONE";
        } else {
            $datas = $this->pdreact_qry_hash($pdrtab["pdrct_id"],$pdrtab["pdrct_eid"]); 
            array_walk($datas,function(&$i,$k){
                $i = $i['hic_gvnhsh'];
            });
        }
        return $datas;
    }
    
    
    public function pdreact_getUrls ($pdreid, $_WITH_FE_OPT = FALSE) {
        
    }
    
    
    private function pdreact_qry_usertags ($pdrid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $list = NULL;
        //Récupérer la liste des Usertags au niveau de la base de données
        $QO = new QUERY("qryl4ustg_pdrn2");
        $qparams_in_values = array(
            ":pdrid" => $pdrid
        );  
        $list = $QO->execute($qparams_in_values);
        
        return $list;
    }
    
    private function pdreact_qry_uic ($pdr_eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $list = NULL;
        /*
         * TOTO :
         *      Récupérer la liste des URLICS
         * [NOTE]
         *      On utilisera TOUJOURS $eid car il est unique dans la table quand id peut porter à confusion et fausser le résultat.
         */
        /*
        $QO = new QUERY("qryl4urlic_artn1");
        $qparams_in_values = array(":aeid" => $pdr_eid);  
        $list = $QO->execute($qparams_in_values);
        
        return $list;
         */
    }
    
    
    private function pdreact_qry_hash ($id,$eid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        /*
         * ETAPE :
         *      On récupère les données depuis le nouveau système de gestion HVIEW.
         *      Cela est possible grace au transfert des données HAHSTAG
         * [NOTE]
         *      On utilise la combinaison "id" "eid" pour plus une plus grande fiabilité des données.
         */
        
        $QO = new QUERY("qryl4hviewn17_PDR");
        $qparams_in_values = array(
            ":id"  => $id,
            ":eid" => $eid 
        );  
        $datas = $QO->execute($qparams_in_values);
        
        return $datas;
    }
    
    
    public function pdreact_delete ($id) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        /*
         * ETAPE :
         *      On vérifie que le COMMENTAIRE existe
         */
        $datas = $this->pdreact_exists_with_id($id);
        if (! $datas ) {
            return "__ERR_VOL_PDR_GONE";
        }
        
        /*
         * ETAPE :
         *      On supprime l'URLIC lié au COMMENTAIRE
         */
        $QO = new QUERY("qryl4urlicn6");
        $qparams_in_values = array(
            ":id"   => $id,
            ":type" => 6 
        );  
        $QO->execute($qparams_in_values);
        
        /*
         * ETAPE :
         *      On supprime les HASHTAGS liés au COMMENTAIRE
         */
        $QO = new QUERY("qryl4hviewn18");
        $qparams_in_values = array(
            ":id"   => $id,
            ":type" => 6 
        );  
        $QO->execute($qparams_in_values);
        
        /*
         * ETAPE :
         *      On supprime les USERTAGS liés au COMMENTAIRE
         */
        $QO = new QUERY("qryl4ustg_pdrn3");
        $qparams_in_values = array(
            ":pdrid"  => $id
        );  
        $QO->execute($qparams_in_values);
        
        /*
         * ETAPE :
         */
        $QO = new QUERY("qryl4pdreactn5");
        $qparams_in_values = array(
            ":id"  => $id
        );  
        $QO->execute($qparams_in_values);
        
        return TRUE;
    }
}
