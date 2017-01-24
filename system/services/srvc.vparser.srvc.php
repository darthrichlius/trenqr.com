<?php
require(RACINE."/common/modules/phpQuery-onefile.php");

/**
 * Add a description ...
 *
 * @author lou.carther.69
 */
class VPARSER extends MOTHER {
    /**
     * Le PARSER permet de lire un fichier : dvt ou model (skeleton) et à le traiter.
     * Le VPARSER gère les marqueurs VREM (View Rich Element Marker)
     * et à les traiter en fonction de la demande. 
     * Il s'agit d'une surcouche du PARSER HTML standard.
     * 
     * NOTE : J'ai decide d'utiliser PHPQuery pour l'instant au lieu de DOMElement qui avait des lacunes en termes de selector
     * NOTE : Mutation pour utilisation de NODE.JS prevue !
     */
    private $dom;
    
    private $filename;
    private $source;
    
    private $path_to_dvt_struct_repos;
    private $path_to_dvt_def_file;
    
    private $deco_def_file;
    private $path_to_deco_def_repos;

    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
        
        $this->reinit_instance();
    }

    
    public function load ($filename, $std_err_enabled = FALSE) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $m = [];
        preg_match("/[\w-]+\.([\w-]+)/",
                strtolower(basename($filename))
                ,$m);
        $ext = $m[1];
        
        if ( file_exists($filename) AND ( $ext != "html" AND $ext != "php" )) {
            try {
                $this->dom = phpQuery::newDocument($filename);
                $this->filename = $filename;
                
                /**
                 * On recupère le contenu du fichier pour de multiples raisons :
                 * - rechercher les VREM
                 * - rechercher doctype
                 *  ...
                 */
                $this->source = file_get_contents($filename);
            } catch (Exception $e) {
                if ( $std_err_enabled ) return; 
                else $this->signalErrorWithoutErrIdButGivenMsg( print_r($e->getMessage()), __FUNCTION__, __LINE__, TRUE);
            }
        } else {
            if ( $std_err_enabled ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $filename);
                $this->signalError("err_sys_l08",__FUNCTION__, __LINE__,true) ;
            } else return;
        }
    }
        
        
    public function loadHTML ($source, $std_err_enabled = FALSE) { 
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        try {
            $this->dom = phpQuery::newDocument($source);
            $this->source = $source;
        } catch (Exception $e) {
            if ( $std_err_enabled ) return FALSE; 
            else $this->signalErrorWithoutErrIdButGivenMsg( print_r($e->getMessage()), __FUNCTION__, __LINE__, TRUE);
        }
    }
    
    /**
     * Permet de charger un dom XML à partir d'une chaine XML.
     * @param type $source
     * @param type $std_err_enabled
     */
    private function inner_xml_loadXML ($source, $std_err_enabled) { }
    
    /********************************** SOURCE *************************************/
    public function transform_source ($source, $default_lang, $c_lang, $std_err_enabled = FALSE, &$listdvt_ext = NULL) {
        //Si la focntion ne trouve pas de VREM, elle renvera NULL à $this->source.
        //Il faut donc tester sa nullité.
        //Pour rappel : NULL est renvoyé pour faire comprendre qu'il ne sert a rien de continuer le traitement de la source 
        //car les autres fonctions utilisent le même extracteur de VREM !
        //[NOTE au 27-11-13 21h49: Pour economiser des ressources on pourrait renvoyer le tableau des VREM]
        //[NOTE au 27-11-13 21h52 : Oui mais qu'en est-il si la fonction est utilisée à titre individuel]
        //[NOTE au 07-08-14 16h15 : L'ordre de transformation des VREM est important. En effet, cela nous permet par exemple de mettre des VREM de type DATX dans ceux de type DECO.]
        
        $tmp_src = $this->dvtVREM_to_dvtstruct($source, $listdvt_ext, $std_err_enabled);
        
        if ( isset($tmp_src) ){ 
            $this->source = $tmp_src;
            //[NOTE au 06-08-14] Si source est défini, il y a de grandes chances que l'acquisition de la source s'est faite non pas via un fichier mais une chaine donnée en paramètre
            $source = ( !isset($source) ) ? NULL : $tmp_src;
        }
        else {
            //Si le retour de la fonction de transformation 'dvtVREM_to_dvtstruct' est NULL c'est qu'elle n'a trouvé aucun(e) VREM.
            //Aussi nul n'est besoin de continuer le process, on renvoie donc la source.
            
            //[NOTE au 06-08-14] (Refactoriser) On vérifie si $source est défini. Si c'est on l'utilise plutot que $this->source car au moins un des deux est toujours NULL
            $st = ( !isset($source) ) ? $this->source : $source;
            $st = $this->remove_unknow_VREM($st);
            return $st; //On ne renvoie pas $source car il peut être NULL !!
        }

        
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$keys,'v_d');
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);    
        $tmp_src = $this->decoVREM_to_text($source, $default_lang, $c_lang, $std_err_enabled);
//        $tmp_src = $this->decoVREM_to_text($tmp_src, $default_lang, $c_lang, $std_err_enabled);
        
        if ( isset($tmp_src) ){ 
            $this->source = $tmp_src;
            //[NOTE au 06-08-14] Si source est défini, il y a de grandes chances que l'acquisition de la source s'est faite non pas via un fichier mais une chaine donnée en paramètre
            $source = ( !isset($source) ) ? NULL : $tmp_src;
        }
        else {
            //Si le retour de la fonction de transformation 'decoVREM_to_text' est NULL c'est qu'elle n'a trouvé aucun(e) VREM.
            //Aussi nul n'est besoin de continuer le process, on renvoie donc la source.
            
            //[NOTE au 06-08-14] (Refactoriser) On vérifie si $source est défini. Si c'est on l'utilise plutot que $this->source car au moins un des deux est toujours NULL
            $st = ( !isset($source) ) ? $this->source : $source;
            $st = $this->remove_unknow_VREM($st);
            return $st; //On ne renvoie pas $source car il peut être NULL !!
        }
        
        $tmp_src = $this->urlVREM_to_text($source, $default_lang, $c_lang, $std_err_enabled);
//        $tmp_src = $this->urlVREM_to_text($tmp_src, $default_lang, $c_lang, $std_err_enabled);
        
        if ( isset($tmp_src) ){ 
            $this->source = $tmp_src;
            //[NOTE au 06-08-14] Si source est défini, il y a de grandes chances que l'acquisition de la source s'est faite non pas via un fichier mais une chaine donnée en paramètre
            $source = ( !isset($source) ) ? NULL : $tmp_src;
        }
        else {
            //Si le retour de la fonction de transformation 'datxVREM_to_text' est NULL c'est qu'elle n'a trouvé aucun(e) VREM.
            //Aussi nul n'est besoin de continuer le process, on renvoie donc la source.
            
            //[NOTE au 06-08-14] (Refactoriser) On vérifie si $source est défini. Si c'est on l'utilise plutot que $this->source car au moins un des deux est toujours NULL
            $st = ( !isset($source) ) ? $this->source : $source;
            $st = $this->remove_unknow_VREM($st);
            return $st; //On ne renvoie pas $source car il peut être NULL !!
        }
        
        //*
        $pxs = $_SESSION["sto_infos"]->getProd_xmlscope(); 
        $tmp_src = $this->dftpgVREM_to_text ($source, $default_lang, $c_lang, $pxs, $std_err_enabled);
//        $tmp_src = $this->dftpgVREM_to_text ($tmp_src, $default_lang, $c_lang, $pxs, $std_err_enabled);
         
        if ( isset($tmp_src) ){ 
            $this->source = $tmp_src;
            //[NOTE au 06-08-14] Si source est défini, il y a de grandes chances que l'acquisition de la source s'est faite non pas via un fichier mais une chaine donnée en paramètre
            $source = ( !isset($source) ) ? NULL : $tmp_src;
        }
        else {
           
            //Si le retour de la fonction de transformation 'datxVREM_to_text' est NULL c'est qu'elle n'a trouvé aucun(e) VREM.
            //Aussi nul n'est besoin de continuer le process, on renvoie donc la source.
            
            //[NOTE au 06-08-14] (Refactoriser) On vérifie si $source est défini. Si c'est on l'utilise plutot que $this->source car au moins un des deux est toujours NULL
            $st = ( !isset($source) ) ? $this->source : $source;
            $st = $this->remove_unknow_VREM($st);
            return $st; //On ne renvoie pas $source car il peut être NULL !!
        }
        
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__,$source,'v_d');
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        $tmp_src = $this->systxVREM_to_text($source,$std_err_enabled);
//        $tmp_src = $this->systxVREM_to_text($tmp_src,$std_err_enabled);
         
        if ( isset($tmp_src) ){ 
            $this->source = $tmp_src;
            //[NOTE au 06-08-14] Si source est défini, il y a de grandes chances que l'acquisition de la source s'est faite non pas via un fichier mais une chaine donnée en paramètre
            $source = ( !isset($source) ) ? NULL : $tmp_src;
        }
        else {
            //Si le retour de la fonction de transformation 'decoVREM_to_text' est NULL c'est qu'elle n'a trouvé aucun(e) VREM.
            //Aussi nul n'est besoin de continuer le process, on renvoie donc la source.
            
            //[NOTE au 06-08-14] (Refactoriser) On vérifie si $source est défini. Si c'est on l'utilise plutot que $this->source car au moins un des deux est toujours NULL
            $st = ( !isset($source) ) ? $this->source : $source;
            $st = $this->remove_unknow_VREM($st);
            return $st; //On ne renvoie pas $source car il peut être NULL !!
        }
        
        //*/
        $tmp_src = $this->datxVREM_to_text($source, $default_lang, $c_lang, $std_err_enabled);
//        $tmp_src = $this->datxVREM_to_text($tmp_src, $default_lang, $c_lang, $std_err_enabled);
        
        if ( isset($tmp_src) ){ 
            $this->source = $tmp_src;
            //[NOTE au 06-08-14] Si source est défini, il y a de grandes chances que l'acquisition de la source s'est faite non pas via un fichier mais une chaine donnée en paramètre
            $source = ( !isset($source) ) ? NULL : $tmp_src;
        }
        else {
            //Si le retour de la fonction de transformation 'datxVREM_to_text' est NULL c'est qu'elle n'a trouvé aucun(e) VREM.
            //Aussi nul n'est besoin de continuer le process, on renvoie donc la source.
            
            //[NOTE au 06-08-14] (Refactoriser) On vérifie si $source est défini. Si c'est on l'utilise plutot que $this->source car au moins un des deux est toujours NULL
            $st = ( !isset($source) ) ? $this->source : $source;
            $st = $this->remove_unknow_VREM($st);
            return $st; //On ne renvoie pas $source car il peut être NULL !!
        }
        
        $tmp_src = $this->sysdirVREM_to_text($source,$std_err_enabled);
//        $tmp_src = $this->sysdirVREM_to_text($tmp_src,$std_err_enabled);
        
        if ( isset($tmp_src) ) {
            
            $st = $this->remove_unknow_VREM($tmp_src);
            return $st;
        } else {
            //Si le retour de la fonction de transformation 'sysdirVREM_to_text' est NULL c'est qu'elle n'a trouvé aucun(e) VREM.
            //Aussi nul n'est besoin de continuer le process, on renvoie donc la source.
            
            //[NOTE au 06-08-14] (Refactoriser) On vérifie si $source est défini. Si c'est on l'utilise plutot que $this->source car au moins un des deux est toujours NULL
            $st = ( !isset($source) ) ? $this->source : $source;
            $st = $this->remove_unknow_VREM($st);
            return $st; //On ne renvoie pas $source car il peut être NULL !!
        }
    }


    /********************************** DVT ZONE ***********************************/
    public function extract_dvt ($std_err_enabled = FALSE) { 
        
        $this->load_or_nothing();
        
        $list_of_dvt_id = [];
        
        foreach ($this->dom["[dvt-id]"] as $dvt_elmt) {
            $list_of_dvt_id [] = $attr = pq($dvt_elmt)->attr("dvt-id");
            $fn = WOS_PATH_DVT_STRUCT_REPOS.$attr.".dvt.struct.php";
            //Cette erreur ne peut pas être échapée car il s'agit de la couche la plus proche de l'utilisateur
            if (! $fn) $this->signalError("err_sys_l61", __FUNCTION__, __LINE__); 
            
            $this->create_file($fn, pq($dvt_elmt), $std_err_enabled);
        }
        
        return $list_of_dvt_id;
    }
    
    
    private function create_dvt_def_element ( $dvt_id, $css_file, $js_file ) {
            $content = "\r\t<dvt id=\"$dvt_id\">\r";
                $content .= "\t\t<dvt.id=\"$dvt_id\">\r";
                $content .= "\t\t<dvt.files.css>\r";
                    $content .= "\t\t\t<std>\r";
                        $content .= "\t\t\t\t<file name=\"std\" value=\"$css_file\" />\r";
                    $content .= "\t\t\t<std>\r";
                $content .= "\t\t</dvt.files.css>\r";
                $content .= "\t\t<dvt.files.js>\r";
                    $content .= "\t\t\t<std>\r";
                        $content .= "\t\t\t\t<file name=\"std\" value=\"$js_file\" />\r";
                    $content .= "\t\t\t<std>\r";
                $content .= "\t\t</dvt.files.js>\r";
            $content .= "\t</dvt>\r";
            
            return $content;
    } 
    
    
    /**
     * Permet d'extraire des DVT et d'écrire sur la plateforme les fichiers y référant.
     * 
     * @todo Lors des remplacements, il faut beautify le fichier
     * @param type $std_err_enabled
     * @return type
     */
    public function extract_dvt_and_write ($std_err_enabled = FALSE) {
        $dvt_ids = $this->extract_dvt($std_err_enabled);
        
        $code_err = ($std_err_enabled) ? 2 : 1;
        
        foreach ($dvt_ids as $dvt_id) {
            /**
             * On insère les path vers les fichiers JS et CSS.
             * Les fichiers sont bien evidemment vides. C'est au dev de les remplacer ou de les construire.
             * Ce n'est donc pas un problème que de retrouver des fichiers CSS vides.
             * Cela signifie jusqte qu'ils ont été crées par VPARSER et qu'ils n'ont pas subi de modifications depuis.
             */
            $css_file = WOS_GEN_PATH_DVTFILES_CSS.$dvt_id."d.css";
            $js_file = WOS_GEN_PATH_DVTFILES_JS.$dvt_id."d.js";
            
            $content = $this->create_dvt_def_element($dvt_id, $css_file, $js_file);
        
            $doc = new DOMDocument();
            $doc->formatOutput = TRUE;
            //Obligatoire pour utilisation de getElementById()
            $doc->validateOnParse = true;
            $doc->load(WOS_DVTDEF_FILE);
            
            $root = $doc->documentElement;

            // if (! $node) $this->get_or_signal_error ($code_err, "err_sys_l021", __FUNCTION__, __LINE__);
                
            try {
                $f = $doc->createDocumentFragment();
                $check = ( $doc->hasChildNodes() ) ? $doc->getElementById($dvt_id) : null;  
                
                if ( $check ) { 
                    $f->appendXML($content);
                    $root->replaceChild($f, $check);
                } else {
                    $f->appendXML($content);
                    $root->appendChild($f);
                }
                
                $foo = $doc->saveXML();
                
                if (! $foo ) 
                    echo $this->get_or_signal_error ($code_err, "err_sys_l022", __FUNCTION__, __LINE__);
                
                $this->create_file(WOS_DVTDEF_FILE, $foo, $std_err_enabled);
                
                $this->create_file($css_file, "", $std_err_enabled);
                $this->create_file($js_file, "", $std_err_enabled);
            } catch (Exception $e) {
                if ( $std_err_enabled ) return; 
                    else $this->signalErrorWithoutErrIdButGivenMsg( print_r($e->getMessage()), __FUNCTION__, __LINE__, TRUE);
            }
        }
    }
    
    
    /*************************/
    /**
     * @ticket 07-08-14
     * @param type $source
     * @param type $std_err_enabled
     * @return type
     */
    public function dvtVREM_to_dvtstruct ($source, &$listdvt_ext, $std_err_enabled = FALSE) {
        //$this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $source = (! $source) ? $this->source : $source;
        $code_err = ($std_err_enabled) ? 2 : 1;
        
        $ids_keys = $listdvt_ext = [];
        
        $r = $this->extract_VREM_couples_if_exist($source);
        
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$r,'v_d');
        if (! $r) 
            return; //Permet de dire à caller qu'il ne sert à rien de continuer le traitement de la source aucune VREM n'est presente
        else 
            $ids_keys = $this->extract_VREM_keys_and_ids_from_VREM_couples_if_dvt($r, $ids, $keys);
        
        if (! $ids) 
            return $source;
        
        
        //on va chercher dans DVT def si les ids sont definis
        $MyXT = new MyXmlTools();
        $xml_tab = $MyXT->acquiereXmlscopeFromPathAndIdInASecureWay(WOS_DVTDEF_FILE, null, "err_sys_l01");
        
        
        //On vérifie s'il y a des similitudes directes puis on remplace 
//        $com = array_intersect( keys($ids_keys), array_keys($xml_tab) ); //OBSELETE
        $flip = array_flip(array_keys($xml_tab));
        $com = array_intersect_key( $ids_keys, $flip );
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$com);
        
        if ( $com ) {
            
            $fn = "";
            foreach ($com as $k => $v) {
                
                if ( $xml_tab[$k]["dvt.type"] == "dvt" ) {
                    $fn = WOS_GEN_PATH_DVTSTRUCT_REPOS.$k.WOS_GEN_PATH_DVTSTRUCT_EXT;
                    if (! file_exists($fn) ) {
                        $infos = "DVT DEFINITION => ".$k.PHP_EOL;
                        $infos .= "MISSING FILE => ".$fn.PHP_EOL;
                        
                        if ( preg_match("#<div s-id=\"(.+?)\">#", $source, $m) ) {
                            $infos .= "SKELETON ID => ". $m[1];
                        }
                        
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$infos);
                        
                        $this->get_or_signal_error ($code_err, "err_sys_l61", __FUNCTION__, __LINE__,TRUE);
                    }
                        
                } else {
                    $fn = WOS_GEN_PATH_CSAMTRUCT_REPOS.$k.WOS_GEN_PATH_CSAMSTRUCT_EXT;
                    if (! file_exists($fn) ) {
                        $infos = "CSAM DEFINITION => ".$k.PHP_EOL;
                        $infos .= "MISSING FILE => ".$fn.PHP_EOL;
                        
                        if ( preg_match("#<div s-id=\"(.+?)\">#", $source, $m) ) {
                            $infos .= "SKELETON ID => ". $m[1].PHP_EOL;
                        }
                        $this->presentVarIfDebug(__FUNCTION__, __LINE__,$infos);
                        
                        $this->get_or_signal_error ($code_err, "err_sys_l61", __FUNCTION__, __LINE__,TRUE);
                    }
                }
                
                $content = file_get_contents($fn);
                $this->replace_VREM_with($k, $content, $source);
                
                /* 
                 * [NOTE 08-08-14 10:52] Ajout de l'instruction.
                 * On récupère les EXT_FILES s'ils existent.
                 */
                $listdvt_ext[$k] = $this->ExtractExtFilesInDVT ($k,$xml_tab);
                
                $str = $source;
                
                /* On va vérifier qu'il n'y pas de VREM déclaré le Template en cours */
                $bar = $this->DeepSearchAndTreatDvtVrem ($str,$xml_tab,$listdvt_ext,$code_err);
                if ( !empty($bar) && is_string($bar) ) $source = $bar;
                
//                $this->presentVarIfDebug(__FUNCTION__, __LINE__,$source,'v_d');
            }
        }
        
        
//        $this->presentVarIfDebug(__FUNCTION__, __LINE__,array_values($xml_tab));
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        
        //[NOTE 07-08-14] Devenu obsolete. Avec la nouvelle definition des DVT plus besoin de brocler en mettant les prefixes "csam.". On s'occupe de tout maintenant dans l'instruction ci-dessus.
        //... Contraindre les developpeurs à utiliser un seul type de dénomination permet de gagner en performance au niveau des traitements.
        /* On effectue une recherche approfondie sur les autres cas. Cela permet de récupérer les CSAM définis avec des prefixes "csam." */
        /*
        foreach ($ids_keys as $v) 
        {
            $k = key($v);
            
            if ( $k == "csam" ) 
            {
                $matches = [];
                $id = $cotent = "";
                
                if ( preg_match("/csam\.(.+)/",$v[$k],$matches) )
                {
                    //On revérifie sans préfixe "csam."
                    if ( in_array($matches[1], array_keys($xml_tab)) ) 
                    {
                        
                        //TODO : Refactoriser dans une méthode car les lignes sont utilisées dans d'autres secteurs
                        $fn1 = WOS_GEN_PATH_CSAMTRUCT_REPOS.$matches[1].WOS_GEN_PATH_CSAMSTRUCT_EXT;
                        $fn2 = WOS_GEN_PATH_CSAMTRUCT_REPOS.$v[$k].WOS_GEN_PATH_CSAMSTRUCT_EXT;
                        
                        if ( file_exists($fn1) ) //On tente de chercher le fichier selon la nouvelle dénomination (Sans prefixe)
                        {
                            $id = $matches[1];
                            $content = file_get_contents($fn1);
                        } else if ( file_exists($fn2) ) //On tente de chercher le fichier selon la nouvelle dénomination (Comme déclaré par le développeur dans le DVT)
                        {
                            $id = $v[$k];
                            $content = file_get_contents($fn2);
                        } else $this->get_or_signal_error ($code_err, "err_sys_l012", __FUNCTION__, __LINE__);
                            
                        $str = $content = file_get_contents($fn);
                        $this->replace_VREM_with($id, $content, $source);
                        
                        /* On va vérifier qu'il n'y pas de VREM déclaré le Template en cours 
                        $bar = $this->DeepSearchAndTreatDvtVrem ($str,array_keys($xml_tab),$code_err);
                        if ( !empty($bar) && is_string($bar) ) $source = $bar;
                        
                    }
                } 
                else //Si id n'a pas de préfixe "csam." on vérifie en ajout le préfixe "csam."
                {   
                    $nid = "csam.".$v[$k];
                    
                    //On revérifie avec le préfixe "csam."
                    if ( in_array($nid, array_keys($xml_tab)) ) 
                    {
                        //TODO : Refactoriser dans une méthode car les lignes sont utilisées dans d'autres secteurs
                        
                        $fn1 = WOS_GEN_PATH_CSAMTRUCT_REPOS.$nid.WOS_GEN_PATH_CSAMSTRUCT_EXT;
                        $fn2 = WOS_GEN_PATH_CSAMTRUCT_REPOS.$v[$k].WOS_GEN_PATH_CSAMSTRUCT_EXT;
                        
                        if ( file_exists($fn1) ) //On tente de chercher le fichier selon la nouvelle dénomination (Avec prefixe)
                        {
                            $id = $nid;
                            $str = $content = file_get_contents($fn1); //On tente de chercher le fichier selon la nouvelle dénomination (Comme déclaré par le développeur dans le DVT)
                        } else if ( file_exists($fn2) ) 
                        {
                            $id = $v[$k];
                            $str = $content = file_get_contents($fn2);
                        } else $this->get_or_signal_error ($code_err, "err_sys_l012", __FUNCTION__, __LINE__);
                        
                        $this->replace_VREM_with($id, $content, $source);
                        
                        
                        /* On va vérifier qu'il n'y pas de VREM déclaré le Template en cours 
                        $bar = $this->DeepSearchAndTreatDvtVrem ($str,array_keys($xml_tab),$code_err);
                        if ( !empty($bar) && is_string($bar) ) $source = $bar;
                        
                    }
                    
                }
            }
        }
        //*/
        //*
        $diff = array_diff( array_values($ids), array_keys($xml_tab) );

        if ( $diff ) {
            
            //On va 
            
            //Sinon enlever VREM de la source
            foreach ( $diff as $v ) {
                $source = $this->remove_not_defined_dvtVREM ($v, $source);
            }
        } 
        
        //*/
        return $source;
    }
    
    /**
     * Traite une chaine de caractères afin de transformer les VREM de type DVT. La particularité de cette méthode est qu'elle traite la chaine en profondeur.
     * Elle transforme les DVT présent dans les DVT nouvellement traités.
     * La méthode est utilisée par dvtVREM_to_dvtstruct() afin de traiter en profondeur des Templates.
     * La profondeur maximale est de 5 sous-couches successives. Il s'agit d'une sécurité afin d'éviter toute boucle infini et de limiter la baisse de performance. 
     * La méthode ne traite pas les cas particuliers où les ids ne correspondent pas. Il s'agit très souvent du cas des CSAM.
     * 
     * @param string La chaine qui servira de base à la recherche.
     * @param string Le tableau contenant les définitions de DVT.
     * @return mixed Une chaine est renvoyée si la chaine passée en paramètre a été traitée. Elle renvoie FALSE si la chaine d'origine n'a subit aucun traitement.
     */
    private function DeepSearchAndTreatDvtVrem ($s, $dd, &$extf, $cd = 2) {
        //$s = Source; $dd = DvtDeftions; extf = ExtFiles; $cd = CodeError
        if ( ! ( ( !empty($s) && is_string($s) ) && ( !empty($dd) && is_array($dd) ) && ( !empty($extf) && is_array($extf) ) ) ) return;
        
        $back = "";
        
        if ( preg_match("#\{wos/((?:dvt|csam)):.*?\}#",$s) ) {
            
            $r = $this->extract_VREM_couples_if_exist($s);
            
            $ids_keys = $this->extract_VREM_keys_and_ids_from_VREM_couples_if_dvt($r, $ids, $keys);
            
            $flip = array_flip(array_keys($dd));
            $found = array_intersect_key( $ids_keys, $flip );
            
            if ( $found ) {
                
                foreach ( $found as $k => $v ) {
                    if ( $dd[$k]["dvt.type"] == "dvt" ) {
                        $fn = WOS_GEN_PATH_DVTSTRUCT_REPOS.$k.WOS_GEN_PATH_DVTSTRUCT_EXT;
                        if (! file_exists($fn) ) {
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$fn,'v_d');
                            $this->get_or_signal_error ($cd, "err_sys_l012", __FUNCTION__, __LINE__);
                        }
                    } else {
                        $fn = WOS_GEN_PATH_CSAMTRUCT_REPOS.$k.WOS_GEN_PATH_CSAMSTRUCT_EXT;
                        if (! file_exists($fn) ) {
                            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$fn,'v_d');
                            $this->get_or_signal_error ($cd, "err_sys_l012", __FUNCTION__, __LINE__);
                        }
                    }

                    $content = file_get_contents($fn);
                    $this->replace_VREM_with($k, $content, $s);
                    
                    /* 
                    * [NOTE 08-08-14 10:52] Ajout de l'instruction.
                    * On récupère les EXT_FILES s'ils existent.
                    */
                   $extf[$k] = $this->ExtractExtFilesInDVT ($k,$dd);
                    
                    $back = $s;

                    //On boucle sur les chaines successives pour vérifier s'il y a des VREM dans les Templates à peine créés.
                    $cn = 0;
                    $foo = "";
                    
                    while ( is_string($foo) && preg_match("#\{wos/((?:dvt|csam)):*\}#",$s) && $cn < 5 )
                    {
                         $foo = $this->DeepSearchAndTreatDvtVrem($s,$dd,$extf,$cd);
                         ++$cn;
                        /*
                         * Conditions d'arret :
                         *  (1) $foo est NULL. Cela veut dire qu'il n'a trouvé aucun VREM dans le Template en cours de traitement.
                         *  (2) On ne trouve aucun VREM dde type DVT dans la représentation en chaine du Template en cours de traitement. 
                         *  (3) Que l'on a atteint la limite des 5 sous-couches. Il s'agit d'une mesure de précaution pour cas critique. Elle évite défitivement le cas des boucles infinies.
                         *  ... 5 est la limite tolérable en termes de profondeur de couches.
                         */
                        
                    }

                    //TODO : Vérifier si on va dépasser les 5 couches. Dans ce cas, envoyer un mail à l'équipe de developpement pour prévenir qu'un DVT n'est pas conforme.
                    
                }
            }
        } else return FALSE;
        
        return $back;
    }
    
    /**
     * Récupère les définitions d'EXT_FILES d'un DVT passé en paramètre.
     * 
     * @author Richard DIEUD <lou.carther@deuslynn-entreprise.com>
     * @since vb1.080814
     * @param type $k L'identifiant du DVT dont il faut récupérer les definitions d'EXT_FILES
     * @param type $def Tableau contenant les définitions des DVT.
     * @return array Tableau contenant les définitions EXT_FILES du DVT dont l'identifiant a été fourni en paramètre
     */
    private function ExtractExtFilesInDVT ($k,$def) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $ext_files = [];
        
        $list_css = $def[$k]["dvt.files.css"];
        if ( isset($list_css) && is_array($list_css) &&  key_exists("std", $list_css) && !empty($list_css["std"]["std"]) ) 
        {
            $ext_files["css"] = $list_css["std"];
        } else $ext_files["css"] = array();
        
    
        $list_js = $def[$k]["dvt.files.js"];
        if ( isset($list_js) && is_array($list_js) &&  key_exists("std", $list_js) && !empty($list_js["std"]["std"]) ) 
        {
            $ext_files["js"] = $list_js["std"];
        } else $ext_files["js"] = array();
        
        return $ext_files;
    }
    
    
    /******************************** DECO ZONE ***********************************/
    public function extract_deco_and_build_def ($lang, $std_err_enabled = FALSE) { }
    
    public function decoVREM_to_text ($source, $default_lang, $c_lang, $std_err_enabled = FALSE) {
        $args = [$default_lang, $c_lang];
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        $source = (! $source) ? $this->source : $source;
        $code_err = ($std_err_enabled) ? 2 : 1;
        
        
        $r = $this->extract_VREM_couples_if_exist($source);
       
        if (! $r) 
            return;
        else 
            $this->extract_VREM_keys_and_ids_from_VREM_couples_if_deco($r, $ids, $keys);
        
        if (! $ids) 
            return $source;
        
        //on va chercher dans DVT def si les id sont definis
        $MyXT = new MyXmlTools();
        
        $file = WOS_GEN_PATH_TO_DECODEF_REPOS.$c_lang."/".WOS_DECODEF_FILE;
        
        $xml_tab = $MyXT->acquiereXmlscopeFromPathAndIdInASecureWay($file, null, "err_sys_l01");
        
        $diff = array_diff( array_values($ids), array_keys($xml_tab) );
        $from_default_lang = [];
        if ( $diff ) {
            
            //Sinon enlever VREM de la source
            foreach ( $diff as $v ) {
                
                //Avant de penser à enlever, on va voir si on a le texte avec la langue de base
                $file = WOS_GEN_PATH_TO_DECODEF_REPOS.$default_lang."/".WOS_DECODEF_FILE;
                //On ne declenche pas d'erreur au cas où il n'y a pas definition.
                //On préfère ne pas avoir de texte qu'avoir une i-nième erreur
                $xml_tab2 = $MyXT->acquiereXmlscopeFromPathAndIdInASecureWay($file, $v, "err_sys_l01", FAlSE);
                
                if ( @$xml_tab2 and is_array($xml_tab2) and count($xml_tab2) ) 
                    $from_default_lang[$v] = $xml_tab2[$v];
                else    
                    $source = $this->remove_not_defined_decoVREM ($v, $source);
            }
        } 
        
        //Apres avoir eliminé les VREM qui n'étaientt pas définies
        $com = array_intersect( array_values($ids), array_keys($xml_tab) );
        
        if ( $com ) {
            foreach ($com as $v) {
                                
                $content = $xml_tab[$v][$v];
                
                //[NOTE 08-0-14] On retire les espaces autour de la chaine avavt de l'envoyer. Cela résolve les problèmes d'affichage au niveau de la couche VIEW
                $this->replace_VREM_with($v, trim($content), $source);
            }
        }

        if ( count($from_default_lang) ) {
            
            foreach ($from_default_lang as $k => $v) {
                                
                $this->replace_VREM_with($k, $v, $source);
            }
        }
        
        return $source;
    }
    
    public function systxVREM_to_text ($s, $std_err_enabled = FALSE) {
//        $args = [$default_lang, $c_lang];
        
//        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        $s = (! $s) ? $this->source : $s;
        $code_err = ($std_err_enabled) ? 2 : 1;
        
        $r = $this->extract_VREM_couples_if_exist($s);
       
        if (! $r) 
            return; 
        else 
            $this->extract_VREM_keys_and_ids_from_VREM_couples_if_systx($r, $ids, $keys);
            
        if (! $ids) 
            return $s;
        
        $systx_tab = ( array_key_exists("systx", $_SESSION) and $_SESSION["systx"] ) ? $_SESSION["systx"] : [] ;
        
        $diff = array_diff( array_values($ids), array_keys($systx_tab) );
        
        if ( $diff ) {
            //Sinon enlever VREM de la source
            foreach ( $diff as $v ) {
                $s = $this->remove_not_defined_systxVREM ($v, $s);
            }
        } 
        
        //Apres avoir eliminé les VREM qui n'étaient pas définies
        $com = array_intersect( array_values($ids), array_keys($systx_tab) );
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$com,'v_d');
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);    
        if ( $com ) {
            foreach ($com as $v) {
                                
                $content = $systx_tab[$v];
                  
                $this->replace_VREM_with($v, $content, $s);
            }
        }

       
        return $s;
    }


    public function dftpgVREM_to_text ($source, $default_lang, $c_lang, $prod_xmlscope, $std_err_enabled = FALSE) {
        $args = [$default_lang, $prod_xmlscope, $c_lang];
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        $source = (! $source) ? $this->source : $source;
        $code_err = ($std_err_enabled) ? 2 : 1;
        
        
        $r = $this->extract_VREM_couples_if_exist($source);
       
        if (! $r) 
            return;
        else 
            $this->extract_VREM_keys_and_ids_from_VREM_couples_if_dftpg($r, $ids, $keys);
        
        if (! $ids) 
            return $source;
        
        $xml_tab = $this->extract_default_pages_from_prod_conf($prod_xmlscope);
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$xml_tab,'v_d');
        $diff = array_diff( array_values($ids), array_keys($xml_tab) );
        
        if ( $diff ) {
            //On retire toutes les ids qui devraient correspondre à une 'default_page' mais qui ne sont pas définis comme tel dans le fichier de configuration du produit.
            foreach ( $diff as $v ) {
                
                    $source = $this->remove_not_defined_dftpgVREM ($v, $source);
            }
        } 
        
        //Apres avoir eliminé les VREM qui n'étaientt pas définies
        $com = array_intersect( array_values($ids), array_keys($xml_tab) );
        
        if ( $com ) {
            foreach ($com as $v) {
                                
                $content = $xml_tab[$v];
                
                $this->replace_VREM_with($v, $content, $source);
            }
        }
        
        return $source;
    }
    
    private function extract_default_pages_from_prod_conf ( $prod_xmlscope ) {
        $reg = "#pdpage_.*#";        
        $ret = array();
        
        foreach ( $prod_xmlscope as $k => $v ) {
            if ( preg_match($reg, $k) ) {
                if (! empty($v) )
                    $ret[$k] = $v;
                else 
                    $this->signalError("err_sys_l025",__FUNCTION__, __LINE__);
            }
        }
        
        return $ret;
    }
    
    public function urlVREM_to_text ($source, $default_lang, $c_lang, $std_err_enabled = FALSE) {
        $args = [$default_lang, $c_lang];
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        $source = (! $source) ? $this->source : $source;
        $code_err = ($std_err_enabled) ? 2 : 1;
        
        
        $r = $this->extract_VREM_couples_if_exist($source);
       
        if (! $r) 
            return;
        else 
            $this->extract_VREM_keys_and_ids_from_VREM_couples_if_url($r, $ids, $keys);
        
        if (! $ids) 
            return $source;
        
        //on va chercher dans DVT def si les id sont definis
        $MyXT = new MyXmlTools();
        
        $file = WOS_GEN_PATH_TO_DECODEF_FILE;
        
        $xml_tab = $MyXT->acquiereXmlscopeFromPathAndIdInASecureWay($file, null, "err_sys_l01");
        
        $diff = array_diff( array_values($ids), array_keys($xml_tab) );
        
        if ( $diff ) {
            
            //Sinon enlever VREM de la source
            foreach ( $diff as $v ) {
                    $source = $this->remove_not_defined_urlVREM ($v, $source);
            }
        } 
        
        //Apres avoir eliminé les VREM qui n'étaientt pas définies
        $com = array_intersect( array_values($ids), array_keys($xml_tab) );
        
        if ( $com ) {
            foreach ($com as $v) {
                if ( key_exists($c_lang, $xml_tab[$v]) ) {  
                    //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$xml_tab[$v],'v_d');
                    $content = $xml_tab[$v][$c_lang];
                    $this->replace_VREM_with($v, $content, $source);
                } else if ( key_exists($default_lang, $xml_tab[$v]) ) {
                    $content = $xml_tab[$v][$default_lang];
                    $this->replace_VREM_with($v, $content, $source);
                } else
                    $source = $this->remove_not_defined_urlVREM ($v, $source);
            }
        }
        
        return $source;
    }
    
    public function datxVREM_to_text ($source, $default_lang, $c_lang, $std_err_enabled = FALSE) {
        $args = [$default_lang, $c_lang];
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $args);
        $source = (! $source) ? $this->source : $source;
        $code_err = ($std_err_enabled) ? 2 : 1;
        
        
        $r = $this->extract_VREM_couples_if_exist($source);
       
        if (! $r) 
            return; 
        else 
            $this->extract_VREM_keys_and_ids_from_VREM_couples_if_datx($r, $ids, $keys);
            
        if (! $ids) 
            return $source;
        
        $datx_tab = ( array_key_exists("ud_carrier", $_SESSION) and $_SESSION["ud_carrier"] ) ? $_SESSION["ud_carrier"] : [] ;
        
        $diff = array_diff( array_values($ids), array_keys($datx_tab) );
        
        if ( $diff ) {
            
            //Sinon enlever VREM de la source
            foreach ( $diff as $v ) {
                $source = $this->remove_not_defined_datxVREM ($v, $source);
            }
        } 
        
        //Apres avoir eliminé les VREM qui n'étaient pas définies
        $com = array_intersect( array_values($ids), array_keys($datx_tab) );
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$com,'v_d');
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);    
        if ( $com ) {
            foreach ($com as $v) {
                                
                $content = $datx_tab[$v];
                
                $this->replace_VREM_with($v, $content, $source);
            }
        }

        return $source;
    }
    
    /******************************** SYSDIR ********************************/
    /**
     * Transforme les VREM de type SYSDIR. 
     * S'il n'y a aucune définition on retire la définition par la chaine "[PATH_NOT_FOUND]".
     *  
     * @author Richard Dieud <lou.carther@deuslynn-entreprise.com>
     * @since vb1
     * @param type $s
     * @param type $std_err_enabled
     * @return string
     */
    public function sysdirVREM_to_text($s,$std_err_enabled = FALSE) {
        $s = (! $s) ? $this->source : $s;
        $code_err = ($std_err_enabled) ? 2 : 1;
        $ids = $keys = [];
        $content = "";
        
        /**
         * J'ai préféré ne pas utiliser un fichier XML car s'aurait été trop lourd et je n'ai pas le temps. 
         * De plus, il s'agit de chemin système et ils ont toujours été déclaré dans com.env. 
         * On ne dénature donc pas le système.
         */
        
        $r = $this->extract_VREM_couples_if_exist($s);
        
        if ( !$r )
            return;
        
        $this->extract_VREM_keys_and_ids_from_VREM_couples_if_sysdir($r,$ids,$keys);
        
        foreach ($ids as $i) {
            
            switch ($i) {
                case "stylesheet_dir_uri" : 
                        $content = WOS_SYSDIR_STYLESHEET;
                    break;
                case "img_dir_uri" : 
                        $content = WOS_SYSDIR_PRODIMAGE;
                    break;
                case "script_dir_uri" : 
                        $content = WOS_SYSDIR_SCRIPT;
                    break;
                default:
                    //TODO : Vérifier la valeur de retour avant de la renvoyer
                    try {
                        return preg_replace("/{wos/sysdir:.*}/", "[PATH_UNREACHABLE]/", $s);
                    } catch (Exception $exc) {
                        $this->get_or_signal_error (1, "err_sys_anyerr", __FUNCTION__, __LINE__);
                    }

                    break;
            }
            
            $this->replace_VREM_with($i, $content, $s);
        } 
        /*
        else 
        {
            //On ne va pas vers la page des erreurs pour une chose aussi insignifiante.
            try {
                return preg_replace("/{wos/sysdir:.*}/", "[PATH_UNREACHABLE]/", $s);
            } catch (Exception $exc) {
                $this->get_or_signal_error (1, "err_sys_anyerr", __FUNCTION__, __LINE__);
            }
            //TODO : Envoyer vers une fontion qui traite tous les VREM de type SYSDIR ou remplacer par une valeur par défaut.
            $this->get_or_signal_error (1, "err_sys_l06", __FUNCTION__, __LINE__);
        }
        
        //*/
        
        return $s;
    }


    /******************************** GENERAL *************************************/
    /**
     * Reinitialise les PATH dans le cas où ils ont été changés.
     */
    public function reinit_instance () {
        $this->path_to_dvt_struct_repos = WOS_GEN_PATH_DVTSTRUCT_REPOS;
        $this->path_to_dvt_def_file = WOS_DVTDEF_FILE;
    
        $this->path_to_deco_def_repos = WOS_GEN_PATH_TO_DECODEF_REPOS;
        $this->deco_def_file = WOS_DECODEF_FILE;
    }
    
    /**
     * Add Description ...
     * 
     * @todo Eviter de prendre les VREM present dans les commentaires.
     * @param type $source
     * @return type
     */
    public function extract_VREM_couples_if_exist ($source = NULL) {
        //Revoie un tableau avec les VREM trouvés sinon NULL
        $source = (! $source) ? $this->source : $source;
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $source);
        $res = preg_match_all("#\{wos/((?:dvt|csam|deco|url|datx|dftpg|systx|sysdir)):([\w-]*)\}#", $source, $matches);
        
        if (! $res) 
            return;
        else {
            $ret = [];
            /**
             * On ne traite pas ind 0 car il ne s'agit que des chaines trouvées.
             * On ne traite pas ind 2 car on traite ses valeurs dans la boucle de traitement.
             */
            for( $j = 0; $j < count($matches[1]); $j++ ) {
                $ret[] = [
                   $matches[1][$j] , $matches[2][$j]
                ];
            }
            return $ret;
        }
    }
    
    
    public function extract_VREM_keys_and_ids_from_VREM_couples_if_dvt ($v_c,& $tab_ids,& $tab_keys) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $v_c);
        $tab_ids_keys = [];
        
        foreach ( $v_c as $v ) {
            if ( $v[0] == "dvt" ||  $v[0] == "csam" ) {
                $tab_keys[] = $v[0];
                $tab_ids[] = $v[1];
                $tab_ids_keys[(string)$v[1]] = (string)$v[0];
            }
        }
        
        return $tab_ids_keys;
    }
    
    private function extract_VREM_keys_and_ids_from_VREM_couples_if_deco ($v_c,& $tab_ids,& $tab_keys) {

        foreach ( $v_c as $v ) {
            if ( $v[0] == "deco") {
                $tab_keys[] = $v[0];
                $tab_ids[] = $v[1];
            }
        }
    }
    
    private function extract_VREM_keys_and_ids_from_VREM_couples_if_url ($v_c,& $tab_ids,& $tab_keys) {

        foreach ( $v_c as $v ) {
            if ( $v[0] == "url") {
                $tab_keys[] = $v[0];
                $tab_ids[] = $v[1];
            }
        }
    }
    
    private function extract_VREM_keys_and_ids_from_VREM_couples_if_datx ($v_c,& $tab_ids,& $tab_keys) {

        foreach ( $v_c as $v ) {
            if ( $v[0] == "datx") {
                $tab_keys[] = $v[0];
                $tab_ids[] = $v[1];
            }
        }
    }
    
    private function extract_VREM_keys_and_ids_from_VREM_couples_if_dftpg ($v_c,& $tab_ids,& $tab_keys) {

        foreach ( $v_c as $v ) {
            if ( $v[0] == "dftpg") {
                $tab_keys[] = $v[0];
                $tab_ids[] = $v[1];
            }
        }
    }
    
    private function extract_VREM_keys_and_ids_from_VREM_couples_if_systx ($v_c,& $tab_ids,& $tab_keys) {

        foreach ( $v_c as $v ) {
            if ( $v[0] == "systx") {
                $tab_keys[] = $v[0];
                $tab_ids[] = $v[1];
            }
        }
    }
    
    private function extract_VREM_keys_and_ids_from_VREM_couples_if_sysdir ($v_c,& $tab_ids,& $tab_keys) {

        foreach ( $v_c as $v ) {
            if ( $v[0] == "sysdir") {
                $tab_keys[] = $v[0];
                $tab_ids[] = $v[1];
            }
        }
    }
    
    /**
     * Crée et écrit un fichier en se fiant au chemin fourni et au contenu. 
     * @param type $fn
     * @param type $content
     * @param type $std_err_enabled
     */
    private function create_file ( $fn, $content = "", $std_err_enabled = FALSE ) {
       $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $code_err = ($std_err_enabled) ? 2 : 1;
        
        if (!$handle = fopen($fn, "wb") ) 
                    $this->get_or_signal_error ($code_err, "err_sys_l020", __FUNCTION__, __LINE__);
            
        if (fwrite($handle, $content) === FALSE) 
                $this->get_or_signal_error ($code_err, "err_sys_l021", __FUNCTION__, __LINE__);

        fclose($handle);
    }
    
    
    /**
     * Sert de cache de securite pour la classe. <br/>
     * En effet, la majorite des methodes de la classe ont besoin que la le dom soit chargé.
     */
    private function load_or_nothing () {
        if (! $this->dom ) $this->signalError("err_sys_l019", __FUNCTION__, __LINE__);
    }
    
    private function remove_unknow_VREM ( $source ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $reg = "#(\{wos/([|a-zA-Z]+):?([|\w-]*)\})#";
        if ( defined("RIGHT_IS_DEBUG") && RIGHT_IS_DEBUG == "true" )
            return preg_replace($reg, "'<span style='color:red;'>&lt;div name='remd-ukw-vrem'&gt;$1&lt;/div&gt;</span>'", $source);
        
        //return preg_replace($reg, "<div name='remd-ukw-vrem_key-$2-id-$3'></div>", $source);
        /**
         * [NOTE au 02-12-13]
         * En mode production, on ne peut pas remplacer le VREM mal défini par une balise.
         * En effet, si ce VREM est lié à une 'url' ou 'form' on aura un message d'erreur sys (500 je crois).
         * Il vaut mieux ne reien mettre pour l'instant. Une amélioration es t à l'etude.
         */
        return preg_replace($reg, "", $source);
    }
    
    private function remove_not_defined_dvtVREM ( $vrem_id, $source ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $reg = "#\{wos/(dvt):$vrem_id\}#";
        if ( defined("RIGHT_IS_DEBUG") && RIGHT_IS_DEBUG == "true" )
            return preg_replace($reg, "<span style=\"color:red;\">&lt;div name=\"remd-dvt-$vrem_id\"&gt;&lt;/div&gt;</span>", $source);
            
        return preg_replace($reg, "<div name=\"remd-dvt-$vrem_id\"></div>", $source);
    }
    
    private function remove_not_defined_decoVREM ( $vrem_id, $source ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $reg = "#\{wos/(deco):$vrem_id\}#";
        
        if ( defined("RIGHT_IS_DEBUG") && RIGHT_IS_DEBUG == "true" ) //debug_print_backtrace();
        return preg_replace($reg, "'<span style=\'color: red; text-decoration: underline;\'>NOT DEFINED DECOTEXT --id:$vrem_id</span>'", $source); 
        
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$source,'v_d');
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);    
        return preg_replace($reg, "", $source);
    }
    
    private function remove_not_defined_urlVREM ( $vrem_id, $source ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $reg = "#\{wos/(url):$vrem_id\}#";
        
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$source,'v_d');
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);    
        
        return preg_replace($reg, "http://dsly.projects/ttplus.dev/public/perma/broken_url.html", $source); 
    }
    
    private function remove_not_defined_dftpgVREM ( $vrem_id, $source ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $reg = "#\{wos/(dftpg):$vrem_id\}#";
        
        if ( defined("RIGHT_IS_DEBUG") && RIGHT_IS_DEBUG == "true" ) //debug_print_backtrace();
        return preg_replace($reg, "'<span style='color: purple;'>NOT DEFINED DEFAULT PAGE --id:$vrem_id</span>'", $source); 
        
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$source,'v_d');
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);    
        return preg_replace($reg, "http://dsly.projects/ttplus.dev/public/perma/broken_url.html", $source);
    }
    
    private function remove_not_defined_systxVREM ( $vrem_id, $source ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $reg = "#\{wos/(systx):$vrem_id\}#";
        
        if ( defined("RIGHT_IS_DEBUG") && RIGHT_IS_DEBUG == "true" ) //debug_print_backtrace();
        return preg_replace($reg, "'<span style=\'color: blue; text-decoration: underline;\'>NOT DEFINED SYSTX --id:$vrem_id</span>'", $source); 
        
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$source,'v_d');
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);    
        return preg_replace($reg, "", $source);
    }
    
    private function remove_not_defined_datxVREM ( $vrem_id, $source ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $reg = "#\{wos/(datx):$vrem_id\}#";
        
        if ( defined("RIGHT_IS_DEBUG") && RIGHT_IS_DEBUG == "true" ) //debug_print_backtrace();
        return preg_replace($reg, "'<span style=\'color: blue; text-decoration: underline;\'>NOT DEFINED DATX --id:$vrem_id</span>'", $source); 
        
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$source,'v_d');
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);    
        return preg_replace($reg, "", $source);
    }
    
    private function remove_not_defined_sysdirVREM ( $vrem_id, $source ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $reg = "#\{wos/(sysdir):$vrem_id\}#";
        
        if ( defined("RIGHT_IS_DEBUG") && RIGHT_IS_DEBUG == "true" ) //debug_print_backtrace();
        return preg_replace($reg, "[PATH_UNREACHABLE]", $source); 
        
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$source,'v_d');
        //$this->endExecutionIfDebug(__FUNCTION__,__LINE__);    
        return preg_replace($reg, "", $source);
    }
    
    private function replace_VREM_with ( $vrem_id, $replacement,& $source ) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $vrem_id);
        
        $reg = "#\{wos/((?:dvt|csam|deco|url|dftpg|datx|systx|sysdir)):$vrem_id\}#";
        
        $source = preg_replace($reg, $replacement, $source);
    }
    /*******************************************************************************************************************/
    /************************************************* GETTERS and SETTERS**********************************************/
    // <editor-fold defaultstate="collapsed" desc="GETRERS and SETTERS">
    public function getPath_to_dvt_struct_repos() {
        return $this->path_to_dvt_struct_repos;
    }

    public function setPath_to_dvt_struct_repos($path_to_dvt_struct_repos) {
        $this->path_to_dvt_struct_repos = $path_to_dvt_struct_repos;
    }

    public function getPath_to_dvt_def_file() {
        return $this->path_to_dvt_def_file;
    }

    public function setPath_to_dvt_def_file($path_to_dvt_def_file) {
        $this->path_to_dvt_def_file = $path_to_dvt_def_file;
    }

    public function getDeco_def_file() {
        return $this->deco_def_file;
    }

    public function setDeco_def_file($deco_def_file) {
        $this->deco_def_file = $deco_def_file;
    }

    public function getPath_to_deco_def_repos() {
        return $this->path_to_deco_def_repos;
    }

    public function setPath_to_deco_def_repos($path_to_deco_def_repos) {
        $this->path_to_deco_def_repos = $path_to_deco_def_repos;
    }

    public function getDom() {
        return $this->dom;
    }

    public function getFilename() {
        return $this->filename;
    }

    public function getSource() {
        return $this->source;
    }

// </editor-fold>

}

?>
