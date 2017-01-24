<?php
require_once WOS_MOD_PATH_PHPBROWSCAP;
require_once WOS_GEN_PATH_VPARSER;

//Ajouter specialement pour PHPBROWSCAP
use phpbrowscap\Browscap;

class FACT_VIEW extends MOTHER {
    private $running_lang;
    private $page_id;
    private $pg_xmlscope;
    private $mdl_id;
    private $mdl_xmlscope;
    private $sklt_file;
    private $list_of_dvt_ids;
    //[NOTE 06-08-14] Ajouté car un DVT peut aussi etre de type CSAM. Sa nature est définie dans $list_of_dvt_keys
    private $list_of_dvt_keys;
    //[NOTE 06-08-14] Ajouté pour améliorer les traitements
    private $list_of_dvt_ids_keys;
    private $list_of_dvt_defs;
    
    private $model_source;
            
    private $human_mode;
    
    private $ext_files_carrier;
    private $list_of_model_css;
    private $list_of_model_js;
    private $list_of_dvt_css;
    private $list_of_dvt_js;
    
    private $PageHead;
    private $PageObj;
    
    private $bzr;
    
    function __construct($page_id, $running_lang) {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->running_lang = $running_lang;
        $this->human_mode = HUMAN_MODE;
        $this->page_id = $page_id;

        $this->run();
    }
    
    /*
    private function run () {
//        $this->bzr = $this->get_brwz_id();
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $this->bzr);
        $this->acquire_model_and_page_xmlscope();
        $this->mdl_id = $this->get_model_id ();
        $this->acquire_dvt_ids_from_skeleton_if_exist ($this->mdl_id) ;
        $this->acquire_dvt_defs_if_exist();

//        $this->list_of_model_css = $this->common_get_proper_css_external_files_vb1($this->bzr, $this->mdl_xmlscope["model.files.css"]);
        $this->list_of_model_css = $this->common_get_proper_css_external_files_vb1($this->mdl_xmlscope["model.files.css"]);
//        $this->list_of_model_js = $this->common_get_proper_js_external_files($this->bzr, $this->mdl_xmlscope["model.files.js"]);
        $this->list_of_model_js = $this->common_get_proper_js_external_files($this->mdl_xmlscope["model.files.js"]);
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $this->list_of_model_css);
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $this->list_of_dvt_defs);
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        foreach ($this->list_of_dvt_defs as $dvt_def) {
//            $foo = $this->common_get_proper_css_external_files_vb1($this->bzr, $dvt_def["dvt.files.css"]);
            $foo = $this->common_get_proper_css_external_files_vb1($dvt_def["dvt.files.css"]);
            if ( $foo )
                    $this->list_of_dvt_css[$dvt_def["dvt.id"]] = $foo;
            
//            $bar = $this->common_get_proper_js_external_files($this->bzr, $dvt_def["dvt.files.js"]);
            $bar = $this->common_get_proper_js_external_files($dvt_def["dvt.files.js"]);
            if ( $bar )
                    $this->list_of_dvt_js[$dvt_def["dvt.id"]] = $bar;
        }
        
        $this->create_ext_files_carrier();
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $this->ext_files_carrier);
        
        $this->construire_head_page();
        
        $this->construire_page();
        //*
        
        $this->afficher_page();
    }
    //*/
    
    private function run () {
        $this->acquire_model_and_page_xmlscope();
        $this->mdl_id = $this->get_model_id ();
        
        $this->list_of_model_css = $this->common_get_proper_css_external_files_vb1($this->mdl_xmlscope["model.files.css"]);
        $this->list_of_model_js = $this->common_get_proper_js_external_files($this->mdl_xmlscope["model.files.js"]);
        
        //On traite le modele et on récupère la source sous format String ainsi que les defitions des DVT
        $list_dvt_ext =[];
        $model_source = $this->get_model_source_and_templates_ext_files($this->mdl_id, $list_dvt_ext);
        
        if ( isset($list_dvt_ext) && !empty($model_source) ) {
            $this->list_of_dvt_defs = $list_dvt_ext;
            $this->model_source = $model_source;
            
            foreach ($this->list_of_dvt_defs as $k => $dvt_def) {
//            $foo = $this->common_get_proper_css_external_files_vb1($this->bzr, $dvt_def["dvt.files.css"]);
                $css = $this->common_get_proper_css_external_files_vb1($dvt_def["css"]);
                if ( $css ) {
                    $this->list_of_dvt_css[$k] = $css;
                }

    //            $bar = $this->common_get_proper_js_external_files($this->bzr, $dvt_def["dvt.files.js"]);
                $js = $this->common_get_proper_js_external_files($dvt_def["js"]);
                if ( $js ) {
                    $this->list_of_dvt_js[$k] = $js;
                }
            }
        }
        
        $this->create_ext_files_carrier();
        
        $this->construire_head_page();
        
        $this->afficher_page();
    }
    
    private function get_brwz_id () {
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SERVER["HTTP_USER_AGENT"]);
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        /**
         * Un path valide pour le path est obligatoire sinon une exception sera levée.
         */
        
        $c_brz;
        try {
            $bc = new Browscap(WOS_MOD_PATH_PHPBROWSCAP_CACHE);
            
            $c_brz = $bc->getBrowser(null, true);
        } catch (Exception $exc) {
            $this->presentVarIfDebug(__FUNCTION__,__LINE__,"IS BROWSCAP.PHP EXISTS ? ".file_exists(WOS_MOD_PATH_PHPBROWSCAP));
            $this->presentVarIfDebug(__FUNCTION__,__LINE__,"IS BROWSCAP.INI EXISTS ? ".file_exists(WOS_MOD_PATH_PHPBROWSCAP_CACHE."browscap.ini"));
            $this->signalError ("err_sys_l62", __FUNCTION__, __LINE__);
        }
        
//        ... attention a bien vérifier que le browser est détecté sinon prendre une valeur âr défaut
        $this->presentVarIfDebug(__FUNCTION__,__LINE__,$_SERVER["HTTP_USER_AGENT"]);
        $this->presentVarIfDebug(__FUNCTION__,__LINE__,$c_brz);
        
        $brz_name = strtolower($c_brz["Browser"]);
        //NOTE : la version sera toujours sous la forme x.x. Ex : 7.0
        $brz_ver = $c_brz["Version"];
        $tmp = explode(".", $brz_ver);
        $brz_sh_ver = $tmp[0];
        
       
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $brz_name);
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $brz_ver);
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $brz_sh_ver);
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SERVER['HTTP_USER_AGENT']);
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        
        //r est tableau comprenant name,ver,sh_ver (pour short version)
        
        $r = [
            'name' => $brz_name,
            'ver' => $brz_ver,
            'sh_ver' => $brz_sh_ver
        ];
        
        //Ici, nous formatons le nom du browser
        switch ($brz_name) {
            case "chrome" :
                    /**
                     * CODE BROWSER : chr
                     * Attention : Le detecteur considère Opera comme Chrome ainsi que pour la version JS.
                     * 
                     * Nous considérons Chrome comme le navigateur reférence. Aussi, on revoie 'std'.
                     * Nous ne traitons pas pour l'instant le cas des versions de Chrome.
                     */
                    $r["name"] = "std";
                break;
            case "ie" :
                    /**
                     * CODE BROWSER : ie
                     * NOTE : Dans le cas d'IE, nous traitons les versions.
                     * @see common_get_proper_css_external_files()
                     * @see common_get_proper_js_external_files()
                     */
                     $r["name"] = "ie";
                break;
            case "firefox" :
                    //CODE BROWSER : ffx
                    $r["name"] = "ffx";
                break;
            case "safari" :
                    //CODE BROWSER : saf
                    $r["name"] = "saf";
                break;
            default:
                    //Le plus souvent il s'agit de 'default browser'.  
                    //uk = UnKnow
                    $r["name"] = "uk";
                break;
        }
        //Pour les tests
        /*
        $r = [
            'name' => "ie",
            'ver' => "10.0",
            'sh_ver' => "10"
        ];
        //*/
        return $r;
    }
    
    
    private function acquire_model_and_page_xmlscope () {
        $path_to_pg_def = WOS_GEN_PATH_PAGEDEF;
        $path_to_mdl_def = WOS_GEN_PATH_PAGES_MODELS_DEF;
        
        $xml_tools = new MyXmlTools();
        
        $this->pg_xmlscope = $xml_tools->acquiereXmlscopeFromPathAndIdInASecureWay($path_to_pg_def, $this->page_id, "err_sys_l013");
        $this->mdl_xmlscope = $xml_tools->acquiereXmlscopeFromPathAndIdInASecureWay($path_to_mdl_def, $this->pg_xmlscope["page.view.model"], "err_sys_l013");
    }
    
    
    private function get_model_id () {
        //TODO : Check if mdl_id is defined.
        //TODO : get_or_signal_error in case of error
        return $this->pg_xmlscope["page.view.model"];
    }
    
    /*
    private function acquire_dvt_ids_from_skeleton_if_exist ($mdl_id) {
        $fn = WOS_GEN_PATH_PAGES_MODELS_REPOS.$mdl_id.WOS_PAGES_MODELS_EXT;
        $this->sklt_file = $fn;
        $ids = $keys  = $ids_keys = [];
        
        $VPar = new VPARSER();
        $VPar->load($fn, TRUE);
        
        $r = $VPar->extract_VREM_couples_if_exist();
        
        if (! $r) 
            return;
        else 
            $ids_keys = $VPar->extract_VREM_keys_and_ids_from_VREM_couples_if_dvt($r, $ids, $keys);
        
        $this->list_of_dvt_ids = $ids;
        $this->list_of_dvt_keys = $keys;
        $this->list_of_dvt_ids_keys = $ids_keys;
        
    }
    */
    
    //[08-08-14]OBSELETE
    private function acquire_dvt_defs_if_exist () {
        
        $path = WOS_DVTDEF_FILE;
        $xml_tools = new MyXmlTools();
        
        $dvt_def_tab = [];
        
        foreach ($this->list_of_dvt_ids as $k => $id) {
            
            /**
             * On ne declenche pas d'erreur dans le cas où DVT n'est pas defini.
             * Sinon ça ferait trop d'erreurs signalées et ce n'est pas si grave de ne pas trouver de DVT !
             * C'est aux developpeurs de faire attention pour que cela n'arrrive pas.
             * 
             * On va donc éliminer les retours qui ne sont pas des tableaux.
             * De plus, on élimine les def qui n'ont pas au moins un fichier externe defini.
             * En effet, si on a trouvé une definition, c'est forcement un tableau.
             * 
             * [NOTE :06-08-14] Cette section permet de récupérer les defintions afin d'en tirer les EXT_FILES 
             */
            $def = $xml_tools->acquiereXmlscopeFromPathAndIdInASecureWay($path, $id, "err_sys_l013", FALSE);
            
            if ( is_array( $def ) and ( count($def["dvt.files.css"] ) OR count($def["dvt.files.js"]) ) ) 
            {
                $dvt_def_tab [] = $def;
            }
            /* 
             * Si on arrive pas retrouver l'identifiant on essai de rechercher cet identifiant écrit sous une autre autre forme.
             * On ne s'interesse ici qu'au cas des DVT de type CSAM.
             */
            else if ( key($this->list_of_dvt_ids_keys[$k]) == "csam"  )
            {
                
                $matches;
                if ( preg_match("/csam\.(.+)/",$id,$matches) )
                {
                    //On revérifie sans préfixe "csam."
                    $def = $xml_tools->acquiereXmlscopeFromPathAndIdInASecureWay($path,$matches[1],"err_sys_l013", FALSE);
                    if ( is_array( $def ) and ( count($def["dvt.files.css"] ) OR count($def["dvt.files.js"]) ) ) 
                    {
                        $dvt_def_tab [] = $def;
                    }
                } 
                else //Si id n'a pas de préfixe "csam." on vérifie en ajout le préfixe "csam."
                {   
                    $nid = "csam.".$id;
                    //On revérifie avec le préfixe "csam."
                    $def = $xml_tools->acquiereXmlscopeFromPathAndIdInASecureWay($path,$nid,"err_sys_l013", FALSE);
                    if ( is_array( $def ) and ( count($def["dvt.files.css"] ) OR count($def["dvt.files.js"]) ) ) 
                    {
                        $dvt_def_tab [] = $def;
                    }
                    
                }
            }
        }
        
        $this->list_of_dvt_defs = $dvt_def_tab;
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $this->list_of_dvt_defs);
    }
    
    private function get_model_source_and_templates_ext_files ($mid, &$listdvt_ext) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $mid);
        
        $fn = WOS_GEN_PATH_PAGES_MODELS_REPOS.$mid.WOS_PAGES_MODELS_EXT;
        $this->sklt_file = $fn;
        
        $VP = new VPARSER();
        $VP->load($fn, TRUE);
        
        $listdvt_ext = [];
        $r = $VP->transform_source(NULL, $_SESSION["sto_infos"]->getDefault_lang(), $this->running_lang, 2, $listdvt_ext);
        
        return $r;
    }
    
    /*
    private function common_get_proper_css_external_files ($brz, $list_of_css) {
      //Si aucun fichier n'est trouvé pour std et/ou spec, css et/ou js egal NULL
      $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
      
      //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$brz,'v_d');
      //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$list_of_css,'v_d');
      //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);   
      
      if ( ( key_exists("std", $list_of_css) and !empty($list_of_css["std"]) )  OR key_exists("spec", $list_of_css) and !empty($list_of_css["spec"]) ) {
          
            if ( !empty($brz['name'])  && key_exists("std", $list_of_css) && !key_exists("spec", $list_of_css) ) {
                //Quelque que soit le brz, s'il n'y a que 'std' qui est defini, on prend 'std'    
                return  $list_of_css["std"]["std"];
            } else if ( $brz['name'] != "std" &&  key_exists("spec", $list_of_css) ) {
                //Dès que spec est defini, c'est lui qui est prioritaire. 
                 
                // On fait un cas à part pour IE car c'est le seul browser pour lequel nous avons défini la gestion des versions.
                if ( $brz['name'] == "ie" ) { 
                        $brz["name"] = "ie".$brz["sh_ver"];
                        /**
                         * REGLES :
                         * - current_brz est sous la forme ie, iex 
                         * => On verifie s'il est défini ou que 'ie' soit défini ce qui veut dire 'tous les ie'. 
                         * Sinon, on verifie qu'une definition de type ie((?:sup|inf|infeg|supeg))(\d) existe.
                         * => (Si oui) On recupère $1 et $2  
                         //
                        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);
                        if ( key_exists($brz["name"], $list_of_css["spec"]) ) 
                              return  $list_of_css["spec"][$brz["name"]];
                        else if ( key_exists("ie", $list_of_css["spec"]) ) {
                            //Si le developpeur n'a marqué que 'ie' ça veut dire que pour toutes les versions de 'ie', prendre les fichiers définies sous la balise brz, ie
                             return  $list_of_css["spec"]["ie"];
                        } else {
                           
                            $brz_key = "";
                            $matches = [];

                            $c_brz_ver = $brz["sh_ver"];
                            
                            foreach ( $list_of_css["spec"] as $k => $v ) {
                                preg_match("#ie((?:sup|inf|infeg|supeg))(\d*)#", $k, $matches);
                                //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $list_of_css,'v_d');
                                if ( !empty($matches) AND $this->match_browser_ver($c_brz_ver, $matches) ) {
                                    $brz_key = $k;
                                    break;
                                }
                            }
                            //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $brz_key,'v_d');
                            //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);  
                            if (! $brz_key) 
                                return  $list_of_css["std"]["std"];
                            else 
                                return $list_of_css["spec"][$brz_key];
                        }
                } else {
                        if ( key_exists($brz["name"], $list_of_css["spec"]) ) 
                                return $list_of_css["spec"][$brz];
                        else { 
                              //On ne devrait normallement jamais arriver ici.
                              //SAUF : Si le browser n'est pas connu !!
                              //Il ne s'aqit avant tout que d'un watchdog
                                
                              if ( key_exists("std", $list_of_css) )  return $list_of_css["std"]["std"];
                        }
                }
            } 
            // On arrive ici si aucun de 'std' ou 'spec' n'est défini. (IMPOSSIBLE car il y a if qui a du renvoyer un premier NULL, mais bon)
            return;
      }                  

      return;
    }
    //*/
    
    /**
     * Renvoie la liste des fichiers CSS à ajouter à la page.
     * Cette version remplace common_get_proper_css_external_files(). En effet, on a transféré la gestion des fichiers CSS spéciaux à aux FrontEnd.
     * 
     * @param type $list_of_css
     * @return type
     */
    private function common_get_proper_css_external_files_vb1 ($list_of_css) {
        $list = [];
        if ( key_exists("std", $list_of_css) && !empty($list_of_css["std"]["std"]) ) {
            //Le cas des définitions pour le modèle
            $list["std"] = $list_of_css["std"]["std"];
        } else if ( key_exists("std", $list_of_css) && !empty($list_of_css["std"]) && is_array(($list_of_css["std"])) && count(($list_of_css["std"])) ) {
             //Le cas des définitions pour les DVT
            $list["std"] = $list_of_css["std"];
        }
        if ( key_exists("std", $list_of_css) && !empty($list_of_css["std"]["media"]) ) {
            //Le cas des définitions pour le modèle
            $list["media"] = $list_of_css["std"]["media"];
        } else if ( key_exists("media", $list_of_css) && !empty($list_of_css["media"]) && is_array(($list_of_css["media"])) && count(($list_of_css["media"])) ) {
             //Le cas des définitions pour les DVT
            $list["media"] = $list_of_css["media"];
        }
        return $list;
    }
    
    //OBSELETE
    /*
    private function common_get_proper_js_external_files ($brz, $list_of_js) {
      // Si aucun fichier n'est trouvé pour std et/ou spec, css et/ou js egal NULL
      $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
      
      if ( ( key_exists("std", $list_of_js) and !empty($list_of_js["std"]) ) OR ( key_exists("spec", $list_of_js) and !empty($list_of_js["std"]) ) ) {
          
            if ( $brz['name'] == "std" && key_exists("std", $list_of_js) ) 
                return  $list_of_js["std"]["std"];
            else if ( $brz['name'] != "std" && !key_exists("spec", $list_of_js) && key_exists("std", $list_of_js) ) {
                //Si on a un browser different de 'std' quand 'spec' n'est pas défini, on renvoie std. 
                return  $list_of_js["std"]["std"];
            } else if ( $brz['name'] != "std" && key_exists("spec", $list_of_js) ) {
                //On fait un cas à part pour IE car c'est le seul browser pour lequel nous avons défini la gestion des versions.
                if ( strpos("ie", $brz) === TRUE ) { //=== car la position commence à 0 donc FALSE si ind = 0 meme si ce n'est pas le cas.
                        /**
                         * REGLES :
                         * - current_brz est sous la forme ie, iex 
                         * => On verifie s'il est défini ou que 'ie' soit défini ce qui veut dire 'tous les ie'. 
                         * Sinon, on verifie qu'une definition de type ie((?:sup|inf|infeg|supeg))(\d) existe.
                         * => (Si oui) On recupère $1 et $2  
                         //
                        if ( key_exists($brz, $list_of_js["spec"]) OR key_exists("ie", $list_of_js["spec"]) ) {
                            return  $list_of_js["spec"][$brz] || $list_of_js["spec"]["ie"];
                        } else {
                            $brz_key = "";
                            $matches = $foo = [];
                            preg_match("ie(\d*)", $brz, $foo);
                            $c_brz_ver = $foo[1];
                                                        
                            foreach ( $list_of_js["spec"] as $k => $v ) {
                                if ( preg_match("ie((?:sup|inf|infeg|supeg))(\d*)", $k, $matches) AND $this->match_browser_ver($c_brz_ver, $matches) ) 
                                    $brz_key = $k;
                            }

                            if (! $brz_key) 
                                return  $list_of_js["std"]["std"];
                            else 
                                return $list_of_js["spec"][$brz_key];
                        }
                } else {
                        if ( key_exists($brz, $list_of_js["spec"]) ) 
                                return $list_of_js["spec"][$brz];
                        else {
                              if ( key_exists("std", $list_of_js) )  return $list_of_js["std"][$brz];
                        }
                }
            } 
            // On arrive ici si aucun de 'std' ou 'spec' n'est défini. (IMPOSSIBLE car il y a if qui a du renvoyer un premier NULL, mais bon)
            return;
      }                  

      return;
    }
    //*/
    
    private function common_get_proper_js_external_files ($list_of_js) {
       
        if ( key_exists("std", $list_of_js) && !empty($list_of_js["std"]["std"]) ) {
            //Le cas des définitions pour le modèle
            return $list_of_js["std"]["std"];
        } else if ( key_exists("std", $list_of_js) && !empty($list_of_js["std"]) && is_array(($list_of_js["std"])) && count(($list_of_js["std"])) ) {
             //Le cas des définitions pour les DVT
             return $list_of_js["std"];
        }
        
    }
    
    
    private function match_browser_ver ($c_brz_ver , $matches) {
        /**
         * $matches[1] = operateur de comparaison
         * $matches[2] = version
         */
        
        switch ($matches[1]) {
            case "inf":
                    if ( intval($c_brz_ver) < intval($matches[2]) )
                        return TRUE;
                break;
            case "infeg":
                    if ( intval($c_brz_ver) <= intval($matches[2]) )
                            return TRUE;
                break;
            case "sup":
                    if ( intval($c_brz_ver) > intval($matches[2]) )
                            return TRUE;
                break;
            case "supeg":
                    if ( intval($c_brz_ver) >= intval($matches[2]) )
                            return TRUE;
                break;
            default:
                //operateur de comparaison inconnu
                    return;
                break;
        }
        return FALSE;
    }
    
    
    private function create_ext_files_carrier () {
        $this->ext_files_carrier["list_of_model_css"] = $this->list_of_model_css;
        $this->ext_files_carrier["list_of_model_js"] = $this->list_of_model_js;
        $this->ext_files_carrier["list_of_dvt_css"] = $this->list_of_dvt_css;
        $this->ext_files_carrier["list_of_dvt_js"] = $this->list_of_dvt_js;
    }

    //*
    private function construire_head_page () {
        $Head_Obj = new HEADTMPLT_BUILDER($this->pg_xmlscope, $this->ext_files_carrier, $this->running_lang, $_SESSION["sto_infos"]->getCtr_name());
        //$this->page_head = $Head_Obj->getMy_structure();
        $this->PageHead = $Head_Obj;
        //$Head_Obj->display_template();
       
    }

    
    private function construire_page () {
        $PAGE = new Page( $this->pg_xmlscope, $this->PageHead, $this->running_lang );
        $this->PageObj = $PAGE;
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $this->PageObj);
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION);
    }
    
    //*
    private function afficher_page () {
//        ($pg_xmlscope, $r_lang, $HeadBuilder = NULL, $page_body_source = NULL)
        $this->PageObj = new Page($this->pg_xmlscope, $this->running_lang, $this->PageHead, $this->model_source );
        $this->PageObj->page_build($this->human_mode);
        $this->PageObj->page_display();
    }
//*/

    /*
    public function  afficher_page_en_mode_production () {
                
    }
    
    public function afficher_body_en_mode_test_page () {
        
    }
    
    public function afficher_head_en_mode_test_page () {
        
    }
    */
    /********************************************************************************************************/
    /*************************************** GETTERS and SETTERS ********************************************/
    // <editor-fold defaultstate="collapsed" desc="comment">
    

    // </editor-fold>
}