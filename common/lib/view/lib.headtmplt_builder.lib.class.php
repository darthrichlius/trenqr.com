<?php


class HEADTMPLT_BUILDER extends MOTHER {
    
    // <editor-fold defaultstate="collapsed" desc="Definition des paramètres">
    private $running_lang;
    private $tag_title;
    private $title_tagvalue;
    /* ------------- META TAG and VALUE -------------- */
    private $tag_meta_content_lang;
    private $content_lang_tagvalue;
    
    private $tag_meta_content_type;
    private $content_type_tagvalue;
    
    private $tag_meta_keywords;
    private $keywords_tagvalue;
    
    private $tag_meta_geography;
    private $geography_tagvalue;
    
    private $tag_meta_country;
    private $country_tagvalue;
    
    private $tag_meta_copyright;
    private $copyright_tagvalue;
    
    private $tag_meta_author;
    private $author_tagvalue;
    
    private $tag_meta_robots;
    private $robots_tagvalue;
    
    private $tag_meta_cache_control;
    private $cache_control_tagvalue;
    
    private $tag_meta_pragma;
    private $pragma_tagvalue;
    
    private $tag_meta_description;
    private $description_tagvalue;
    
    private $tag_meta_language;
    private $language_tagvalue;

    /* ------------- LINK TAG and VALUE -------------- */
    private $tag_link_icon;
    private $icon_href_tagvalue;
    
    private $tag_css_page_file;
    private $css_page_file_tagvalue;
    
    private $tag_js_page_file;
    private $js_page_file_tagvalue;
    
    private $list_of_ext_files; 
    private $array_tag_model_css_files;
    private $model_css_files;
    private $array_tag_model_js_files;
    private $model_js_files;
    private $array_tag_dvt_css_files;
    private $dvt_css_files;
    private $array_tag_dvt_js_files;
    private $dvt_js_files;

    /* --------------- TAGS CARRIERS --------------- */
    private $meta_tags_carrier;
    //[07-08-14] Ajout des deux propriétés
    private $TQDefMetas;
    private $OGDefMetas;
    //[25-09-15] 
    private $CSTMs;
     // </editor-fold>
    
    private $human_mode;
    private $page_xmlscope;
    private $my_structure;
    
    function __construct($page_xmlscope, $list_of_ext_files, $running_lang, $country ) {
        parent::__construct(__FILE__, __CLASS__);
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args(),TRUE);
        
        $this->list_of_ext_files = $list_of_ext_files;
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $this->list_of_ext_files);
        $this->running_lang = $running_lang;
        $this->content_type_tagvalue = "utf-8";
        $this->page_xmlscope = $page_xmlscope;
        $this->country_tagvalue = $country;
        $this->geography_tagvalue = $country;
        
        //$this->build_template($human_mode);
        $this->human_mode = (RIGHT_IS_DEBUG) ? HUMAN_MODE_ACTIVATE : HUMAN_MODE_DISABLE;
 
        $this->build_head_template($this->human_mode);
          
    }

    //human_mode permet de définir s'il faut afficher la source de telle sorte qu'un humain puisse le comprendre ou pas.
    //C'est un gage de sécurité pour éviter qu'un developpeur vole facilement le code source
    private function build_head_template ($human_mode) {
        $this->treat_ext_files($this->list_of_ext_files);
        $this->build_template_from_page_xmlscope();
        $this->fonction_de_mise_en_boite_des_tagvalues();
        $this->fonction_de_load_de_la_structure_selon_le_mode_de_visionnage ($human_mode);
    }
    
    private function treat_ext_files () {
        /**
         * MODELE : 
         * list_of_model_css ; list_of_model_js ; list_of_dvt_css ; list_of_dvt_js;
         */
        $this->model_css_files = $this->list_of_ext_files["list_of_model_css"] ;
        $this->model_js_files = $this->list_of_ext_files["list_of_model_js"] ;
        $this->dvt_css_files = $this->list_of_ext_files["list_of_dvt_css"] ;
        $this->dvt_js_files = $this->list_of_ext_files["list_of_dvt_js"] ;
    }
    
    /***************************************** METHODES HERITEES ITEMPLATE ***********************************/
    
    private function build_template_from_page_xmlscope () {
        
            $this->title_tagvalue = $this->page_xmlscope["page.title"][$this->running_lang];
            $this->description_tagvalue = $this->page_xmlscope["page.description"][$this->running_lang];
            $this->keywords_tagvalue = $this->page_xmlscope["page.keywords"][$this->running_lang];
            $this->author_tagvalue = $this->page_xmlscope["page.author"];
            $this->language_tagvalue = $this->running_lang;
            $this->content_lang_tagvalue = $this->running_lang;
            $this->copyright_tagvalue = $this->page_xmlscope["page.copyright"];
            /*
            $this->geography_tagvalue = $this->geography_tagvalue;
            $this->country_tagvalue = $this->country_tagvalue;
             * Ces deux valeurs ont déjà été affectées dans le constructeur
             */
            $this->robots_tagvalue = $this->page_xmlscope["page.robots"];
            $this->pragma_tagvalue = $this->page_xmlscope["page.pragma"];
            $this->cache_control_tagvalue = $this->page_xmlscope["page.cache_control"];
            $this->icon_href_tagvalue = $this->page_xmlscope["page.icon"];
            
            
            /****** TQ METAS *******/
            
            if ( key_exists("page.meta.tq", $this->page_xmlscope) && isset($this->page_xmlscope["page.meta.tq"]) ) {
                /*
                 * On ne vérifie pas si les valeurs sont non vide car, dans ce cas, l'environnement tolère qu'une valeur donnée soit vide.
                 * Ce qui compte c'est que la valeur soit définie.
                 */
                foreach ($this->page_xmlscope["page.meta.tq"] as $k => $v) {
                    $this->TQDefMetas[$k] = $v;
                }
            }
            
            /****** OG METAS *******/
            
            if ( key_exists("page.meta.og", $this->page_xmlscope) && isset($this->page_xmlscope["page.meta.og"]) ) {
                /*
                 * On ne vérifie pas si les valeurs sont non vide car, dans ce cas, l'environnement tolère qu'une valeur donnée soit vide.
                 * Ce qui compte c'est que la valeur soit définie.
                 */
                foreach ($this->page_xmlscope["page.meta.og"] as $k => $v) {
                    $this->OGDefMetas[$k] = $v;
                }
            }
            
            /****** TWITTER METAS *******/
            
            if ( key_exists("page.meta.twitter", $this->page_xmlscope) && isset($this->page_xmlscope["page.meta.twitter"]) ) {
                /*
                 * On ne vérifie pas si les valeurs sont non vide car, dans ce cas, l'environnement tolère qu'une valeur donnée soit vide.
                 * Ce qui compte c'est que la valeur soit définie.
                 */
                foreach ($this->page_xmlscope["page.meta.twitter"] as $k => $v) {
                    $this->TWTDefMetas[$k] = $v;
                }
            }
            
            //[DEPUIS 26-09-15] @author BOR
            /****** CUSTOMS *******/
            $this->CSTMs = $this->page_xmlscope["page.customs"];
            
    }
    
    
    private function fonction_de_mise_en_boite_des_tagvalues(){
        
        /**************************************************************************************************/
        /****************************************** FILLING META ******************************************/
        
        $this->tag_title = "<title>".$this->title_tagvalue."</title>"; 
        
        $this->meta_tags_carrier[] = "<!--[if IE]> <meta http-equiv='X-UA-Compatible' content='IE=edge' > <![endif]-->";
//        $this->meta_tags_carrier[] = "<meta name='viewport' content='width=device-width, initial-scale=1' >";
        /*
         * [DEPUIS 01-08-16]
         */
        $this->meta_tags_carrier[] = "<meta name='viewport' content='width=device-width, initial-scale=0.6' >";
        
        $this->meta_tags_carrier[] = (! $this->content_lang_tagvalue ) ? 
                NULL : $this->tag_meta_content_lang = "<meta http-equiv=\"Content-Language\" content=\"".$this->content_lang_tagvalue."\">";
        $this->meta_tags_carrier[] = (! $this->content_type_tagvalue ) ? 
                NULL :  $this->tag_meta_content_type = "<meta http-equiv=\"Content-Type\" content=\"text/html\" charset=\"".$this->content_type_tagvalue."\">";
        $this->meta_tags_carrier[] = (! $this->keywords_tagvalue ) ? 
                NULL :  $this->tag_meta_keywords = "<meta http-equiv=\"keywords\" name=\"keywords\" content=\"".$this->keywords_tagvalue."\">";
        $this->meta_tags_carrier[] = (! $this->description_tagvalue ) ? 
                NULL :  $this->tag_meta_description = "<meta http-equiv=\"description\" name=\"description\" content=\"".$this->description_tagvalue."\">";
        $this->meta_tags_carrier[] = (! $this->geography_tagvalue ) ? 
                NULL :  $this->tag_meta_geography = "<meta name=\"Geography\" content=\"".$this->geography_tagvalue."\">";
        $this->meta_tags_carrier[] = (! $this->country_tagvalue ) ? 
                NULL :  $this->tag_meta_country = "<meta name=\"country\" content=\"".$this->country_tagvalue."\">";
        $this->meta_tags_carrier[] = (! $this->language_tagvalue ) ? 
                NULL :  $this->tag_meta_language = "<meta name=\"Language\" content=\"$this->language_tagvalue\">";
        $this->meta_tags_carrier[] = (! $this->copyright_tagvalue ) ? 
                NULL :  $this->tag_meta_copyright = "<meta name=\"Copyright\" content=\"$this->copyright_tagvalue\">";
        $this->meta_tags_carrier[] = (! $this->author_tagvalue ) ? 
                NULL :  $this->tag_meta_author = "<meta name=\"Author\" content=\"$this->author_tagvalue\">";
        //$this->meta_tags_carrier[] = $this->tag_meta_robots = "<meta name=\"robots\" content=\"INDEX|FOLLOW\">";
        /*
         * [DEPUIS 15-09-15] @author BOR
         *  Je réhabilité l'utilisation de la base "meta robot".
         *  Je n'ai mis aucun commentaire pour justifier pourquoi je l'ai retiré. Je dois légitimement m'attendre à des dysfonctionnements.
         */
        $this->meta_tags_carrier[] = (! $this->robots_tagvalue ) ? 
                NULL :  $this->tag_meta_robots = "<meta name=\"robots\" content=\"$this->robots_tagvalue\">"; 
        $this->meta_tags_carrier[] = (! $this->cache_control_tagvalue ) ? 
                NULL :  $this->tag_meta_cache_control = "<meta http-equiv=\"cache-control\" content=\"$this->cache_control_tagvalue\">";
        $this->meta_tags_carrier[] = (! $this->pragma_tagvalue ) ? 
                NULL :  $this->tag_meta_pragma = "<meta http-equiv=\"pragma\" content=\"$this->pragma_tagvalue\">";
        
        //[NOTE 07-08-14] Ajout des méthodes de traitement des META CUSTOM TQ et OG
        /****************************************************************************************************/
        /*********************************** FILILING CUSTUM META TAGS **************************************/
        
        $this->BuildTQMetaTags();
        
        $this->BuildOGMetaTags();
        
        $this->BuildTWTMetaTags();
        
        $this->BuildCuzMetaTags();
        
        /****************************************************************************************************/
        /***************************************** FILILING LINKS *******************************************/
        $this->tag_link_icon = "<link rel=\"icon\" type=\"image/png\" href=\"".$this->icon_href_tagvalue."\" />";
        
        $this->mise_en_boite_link_css($this->model_css_files);
        $this->mise_en_boite_link_css($this->dvt_css_files, TRUE);
        $this->mise_en_boite_script($this->model_js_files);
        $this->mise_en_boite_script($this->dvt_js_files, TRUE);
    }
    
    /**
     * Construit la version vue du metatag personnalisé de type TQ.
     * 
     * @author Richard Dieud <lou.carther@deuslynn-entreprise.com>
     * @since bv1
     */
    private function BuildTQMetaTags() {
        if ( isset($this->TQDefMetas) ) {
            
            foreach ($this->TQDefMetas as $k => $v) {
                $this->TQDefMetas[$k] = "<meta property=\"tq:$k\" content=\"$v\" />";
            }
        }
    }
    
    /**
     * Construit la version vue du metatag personnalisé de type OG.
     * 
     * @author Richard Dieud <lou.carther@deuslynn-entreprise.com>
     * @since bv1
     * @
     */
    private function BuildOGMetaTags() {
        if ( isset($this->OGDefMetas) ) {
            
            foreach ($this->OGDefMetas as $k => $v) {
                $this->OGDefMetas[$k] = "<meta property=\"og:$k\" content=\"$v\" />";
            }
        }
    }
    
    /**
     * Construit la version vue du metatag personnalisé de type TWT.
     * 
     * @author Richard Dieud <lou.carther@deuslynn-entreprise.com>
     * @since bv1.10.08.14 
     */
    private function BuildTWTMetaTags() {
        if ( isset($this->TWTDefMetas) ) {
            
            foreach ($this->TWTDefMetas as $k => $v) {
                $this->TWTDefMetas[$k] = "<meta property=\"twitter:$k\" content=\"$v\" />";
            }
        }
    }
    
    private function BuildCuzMetaTags() {
        if ( isset($this->CSTMs) ) {
            $tmp__ = [];
            foreach ($this->CSTMs as $v) {
                $tmp__[] = $v;
            }
            $this->CSTMs = $tmp__;
        }
    }
    
    private function mise_en_boite_link_css ($tab, $dvt_case = FALSE) {
        if ( $tab and count($tab) ) { //On verifie qu'il y a bien des fichiers definis
            $VP = new VPARSER();
            
            //[12-12-14 18h] Intégration de la gestion des fichiers avec attribut "media"
            if ( $dvt_case === TRUE ) {
                foreach ($tab as $grpfile) {
                    if ( key_exists("media", $grpfile) && !empty($grpfile["media"]) ) {
                        foreach ($grpfile["media"] as $file) {
                            $pieces = explode(";", $file);
                            if ( count($pieces) === 2 ) {
                                /*
                                * 0 : media condition
                                * 1 : file
                                */
                               $media = $pieces[0];
                               $file = $VP->sysdirVREM_to_text($pieces[1]);
                               $this->meta_tags_carrier[] = $this->tag_css_page_file = "<link rel=\"stylesheet\" type=\"text/css\" media=\"$media\" href=\"$file\">";
                            }
                       }
                    }
                    if ( key_exists("std", $grpfile) && !empty($grpfile["std"]) ) {
                       //Filling CSS from Page
                       foreach ($grpfile["std"] as $file) {
                           $file = $VP->sysdirVREM_to_text($file);
                           $this->meta_tags_carrier[] = $this->tag_css_page_file = "<link rel=\"stylesheet\" type=\"text/css\" href=\"$file\">";
                       }
                    } 
               }
            } else {
                //Filling CSS for Page
                if ( key_exists("media", $tab) && !empty($tab["media"]) ) {
                    foreach ($tab["media"] as $file) {
                           $pieces = explode(";", $file);
                           if ( count($pieces) === 2 ) {
                               /*
                                * 0 : media condition
                                * 1 : file
                                */
                               $media = $pieces[0];
                               $file = $VP->sysdirVREM_to_text($pieces[1]);
                               $this->meta_tags_carrier[] = $this->tag_css_page_file = "<link rel=\"stylesheet\" type=\"text/css\" media=\"$media\" href=\"$file\">";
                           }
                       }
                }
                if ( key_exists("std", $tab) && !empty($tab["std"]) ) {
                    foreach ($tab["std"] as $file) {
                       $file = $VP->sysdirVREM_to_text($file);
                       $this->meta_tags_carrier[] = $this->tag_css_page_file = "<link rel=\"stylesheet\" type=\"text/css\" href=\"$file\">";
                   }
                } 
            }
           
            //[12-12-14 18h] Gestion des fichiers avec attribut media
//            if ( key_exists("media", $tab) && !empty($tab["media"]) ) {
//               foreach ($tab["media"] as $grpfile) {
//                    if ( $dvt_case === TRUE ) {
//                       //Filling CSS from Page
//                       foreach ($grpfile as $file) {
//                           $pieces = explode(";", $file);
//                           if ( count($pieces) === 2 ) {
//                               /*
//                                * 0 : media condition
//                                * 1 : file
//                                */
//                               $media = $pieces[0];
//                               $file = $VP->sysdirVREM_to_text($pieces[1]);
//                               $this->meta_tags_carrier[] = $this->tag_css_page_file = "<link rel=\"stylesheet\" type=\"text/css\" media=\"$media\" href=\"$file\">";
//                           }
//                       }
//                    } else {
//                        $pieces = explode(";", $grpfile);
//                        if ( count($pieces) === 2 ) {
//                            /*
//                             * 0 : media condition
//                             * 1 : file
//                             */
//                            $media = $pieces[0];
//                            $file = $VP->sysdirVREM_to_text($pieces[1]);
//                            $this->meta_tags_carrier[] = $this->tag_css_page_file = "<link rel=\"stylesheet\" type=\"text/css\" media=\"$media\" href=\"$file\">";
//                        }
////                        $grpfile = $VP->sysdirVREM_to_text($grpfile);
////                        $this->meta_tags_carrier[] = $this->tag_css_page_file = "<link rel=\"stylesheet\" type=\"text/css\" href=\"$grpfile\">";
//
//                    }
//               }
//           }
        }
    }
    
    
    private function mise_en_boite_script ($tab, $dvt_case = FALSE) {
        if ( $tab and count($tab) ) { //On verifie qu'il y a bien des fichiers definis
            foreach ($tab as $grpfile) {
                if ( $dvt_case === TRUE ) {
                    //Filling JS from Page
                    foreach ($grpfile as $file) {
                        $this->meta_tags_carrier[] = $this->tag_js_page_file = "<script language=\"javascript\" type=\"text/javascript\" src=\"$file\"></script>";
                    }
                } else $this->meta_tags_carrier[] = $this->tag_js_page_file = "<script language=\"javascript\" type=\"text/javascript\" src=\"$grpfile\"></script>";
            }
        }
    }
    
    
    private function fonction_de_load_de_la_structure_selon_le_mode_de_visionnage ($human_mode) {
        //$this->my_structure = "<!DOCTYPE HTML>\n";
        $this->my_structure = "<head>".PHP_EOL;
        
        $this->my_structure .= $this->tag_title.PHP_EOL;
        
        //Ajout des tags de type meta tag
        foreach ($this->meta_tags_carrier as $tag) {
                $this->my_structure .= $tag.PHP_EOL;
        }
        
        /* Ajout des tag personnalisés */
        //TQ META TAGS
        if ( !empty($this->TQDefMetas) && is_array($this->TQDefMetas) ) {
            foreach ($this->TQDefMetas as $tag) {
                $this->my_structure .= $tag.PHP_EOL;
            }
        }
        //OG META TAGS
        if ( !empty($this->OGDefMetas) && is_array($this->OGDefMetas) ) {
            foreach ($this->OGDefMetas as $tag) {
                $this->my_structure .= $tag.PHP_EOL;
            }
        }
        //TWT META TAGS
        if ( !empty($this->TWTDefMetas) && is_array($this->TWTDefMetas) ) {
            foreach ($this->TWTDefMetas as $tag) {
                $this->my_structure .= $tag.PHP_EOL;
            }
        }
        //CUSTOMS TAGS
        if ( !empty($this->CSTMs) && is_array($this->CSTMs) ) {
            foreach ($this->CSTMs as $tag) {
                $this->my_structure .= $tag.PHP_EOL;
            }
        }
        
        $this->my_structure .= $this->tag_link_icon.PHP_EOL;
        
        $this->my_structure .= "</head>";
        
        //[06-08-14] Traiter pour traduire les VVREM
        $VP = new VPARSER();
        $this->my_structure = $VP->transform_source($this->my_structure, $_SESSION["sto_infos"]->getDefault_lang(), $this->running_lang, TRUE);
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $this->my_structure);
//        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
//        
        if ( $human_mode == HUMAN_MODE_DISABLE) {
            $this->my_structure = preg_replace("/[\n\t]*/", "", $this->my_structure);
        }
        
     }
     
     
    public function display_template () {
        echo $this->my_structure;
    }
    
    /****************************************** GETTERS AND SETTERS ****************************************/
    // <editor-fold defaultstate="collapsed" desc="GETTERS and SETTERS">
    public function getTitle_tagvalue() {
        return $this->title_tagvalue;
    }

    public function setTitle_tagvalue($title_tagvalue) {
        $this->title_tagvalue = $title_tagvalue;
    }

    public function getContent_lang_tagvalue() {
        return $this->content_lang_tagvalue;
    }

    public function setContent_lang_tagvalue($content_lang_tagvalue) {
        $this->content_lang_tagvalue = $content_lang_tagvalue;
    }

    public function getContent_type_tagvalue() {
        return $this->content_type_tagvalue;
    }

    public function setContent_type_tagvalue($content_type_tagvalue) {
        $this->content_type_tagvalue = $content_type_tagvalue;
    }

    public function getKeywords_tagvalue() {
        return $this->keywords_tagvalue;
    }

    public function setKeywords_tagvalue($keywords_tagvalue) {
        $this->keywords_tagvalue = $keywords_tagvalue;
    }

    public function getGeography_tagvalue() {
        return $this->geography_tagvalue;
    }

    public function setGeography_tagvalue($geography_tagvalue) {
        $this->geography_tagvalue = $geography_tagvalue;
    }

    public function getCountry_tagvalue() {
        return $this->country_tagvalue;
    }

    public function setCountry_tagvalue($country_tagvalue) {
        $this->country_tagvalue = $country_tagvalue;
    }

    public function getCopyright_tagvalue() {
        return $this->copyright_tagvalue;
    }

    public function setCopyright_tagvalue($copyright_tagvalue) {
        $this->copyright_tagvalue = $copyright_tagvalue;
    }

    public function getAuthor_tagvalue() {
        return $this->author_tagvalue;
    }

    public function setAuthor_tagvalue($author_tagvalue) {
        $this->author_tagvalue = $author_tagvalue;
    }

    public function getRobots_tagvalue() {
        return $this->robots_tagvalue;
    }

    public function setRobots_tagvalue($robots_tagvalue) {
        $this->robots_tagvalue = $robots_tagvalue;
    }

    public function getCache_control_tagvalue() {
        return $this->cache_control_tagvalue;
    }

    public function setCache_control_tagvalue($cache_control_tagvalue) {
        $this->cache_control_tagvalue = $cache_control_tagvalue;
    }

    public function getPragma_tagvalue() {
        return $this->pragma_tagvalue;
    }

    public function setPragma_tagvalue($pragma_tagvalue) {
        $this->pragma_tagvalue = $pragma_tagvalue;
    }

    public function getDescription_tagvalue() {
        return $this->description_tagvalue;
    }

    public function setDescription_tagvalue($description_tagvalue) {
        $this->description_tagvalue = $description_tagvalue;
    }

    public function getLanguage_tagvalue() {
        return $this->language_tagvalue;
    }

    public function setLanguage_tagvalue($language_tagvalue) {
        $this->language_tagvalue = $language_tagvalue;
    }
    
    public function getMy_structure() {
        return $this->my_structure;
    }

// </editor-fold>

}
?>
