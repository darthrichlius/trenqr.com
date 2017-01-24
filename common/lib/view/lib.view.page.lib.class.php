<?php

class PAGE extends MOTHER {
    private $pg_xmlscope;
    private $HeadBuilder;
    
    private $page_body_source;
    
    private $run_lang;
    
    private $ready_to_build;
    private $ready_to_display;
            
    function __construct($pg_xmlscope, $r_lang, $HeadBuilder = NULL, $page_body_source = NULL) {
        parent::__construct(__FILE__,__CLASS__);
        
        $args = [$HeadBuilder,$page_body_source];
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__,$args);
                
        $this->pg_xmlscope = $pg_xmlscope;
        $this->run_lang = $r_lang;
        
        if ( ( isset($HeadBuilder) && is_object($HeadBuilder) ) && ( !empty($page_body_source) && is_string($page_body_source) ) ) {
            $this->HeadBuilder = $HeadBuilder;
            $this->page_body_source = $page_body_source;
            
            $this->ready_to_build = TRUE;
        } else $this->ready_to_build = FALSE;
        
        //Dans tous les cas
        $this->ready_to_display = FALSE;
    }
    
    public function page_prepare ($HeadBuilder, $page_body_source) {
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( ( isset($HeadBuilder) && is_object($HeadBuilder) ) && ( !empty($page_body_source) && is_string($page_body_source) ) ) {
            $this->HeadBuilder = $HeadBuilder;
            $this->page_body_source = $page_body_source;
            
            $this->ready_to_build = TRUE;
        } else $this->ready_to_build = FALSE;
    }

    //OBSELETE @since 08-08-14 00:15
    public function run() {
        /*
        foreach ($this->List_Of_Dvt_Objects as $DVT) {
            //$DVT = new DataViewTemplate_Handler($this->UserDataContent, $dvt_path, $this->human_mode);
            //Etant donné que le tableau source est déjà trié, le ou les tableaux sous-jacents le seront aussi.
            //Nous mettons donc en place un tableau contenant les structures. Celles-ci peuvent avoir fait  l'objet de mimification.
            //Nous n'affichons pas directement les structure car il faut les empacketées dans les balises <head> et <body>
            $this->Array_Dvt_Structure_Sorted [] = $DVT->getMy_structure();
        }
        //*/
        
        //OBSELETE @since 08-08-14 00:15
        /*
        $VP = new VPARSER();
        $fn = WOS_GEN_PATH_PAGES_MODELS_REPOS.$this->pg_xmlscope["page.view.model"].WOS_PAGES_MODELS_EXT; 
        if (!file_exists($fn)) $this->signalError("err_sys_l023", __FUNCTION__, __LINE__);
        
        $VP->load($fn);

        $this->body_source = $VP->transform_source(NULL, $_SESSION["sto_infos"]->getDefault_lang(), $this->run_lang);
        //*/
    }
    
    public function parse_model_via_string ($sklt, $rl, $cd = 2) {
        /*
         * Permet de créer une source à partir d'un SKLT. La méthode renvoie un tableau contenant 
         *      * index (0) : la chaine de caractères représentant la source
         *      * index (1) : liste des definitions des DVT trouvés ce quelque soit leur type (DVT ou CSAM)
         * 
         */
        
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $VP = new VPARSER();
        
        $dvt_ext_files = [];
        $model_source = $VP->transform_source($sklt, $_SESSION["sto_infos"]->getDefault_lang(), $rl, $cd, $dvt_ext_files);
        
        return [$model_source,$dvt_ext_files];
    }
    
    public function parse_model_via_mdlid ($id, $rl, $cd = 2) {
        //id = L'identifiant du modèle (Skeleton); $rl = RunningLang; $cd = CodeError
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        $fn = WOS_GEN_PATH_PAGES_MODELS_REPOS.$id.WOS_PAGES_MODELS_EXT; 
        if (!file_exists($fn)) $this->signalError("err_sys_l023", __FUNCTION__, __LINE__);
        
        $VP = new VPARSER();
        $VP->load($fn);
        
        $dvt_ext_files = [];
        
        $model_source = $VP->transform_source(NULL, $_SESSION["sto_infos"]->getDefault_lang(), $rl, $cd, $dvt_ext_files);
        
        return [$model_source,$dvt_ext_files];
    }
    
    
    public function page_build ($human_mode) {
        //TODO : Déclencher une erreur
        if ( !$this->ready_to_build ) return;
        
        $this->page_source = ("<!DOCTYPE html>");
        $this->page_source .= "\n";
        //TODO : Ajouter "data-pg" ainsi que le texte pour les versions de IE
        $this->page_source .=("<html lang=\"".$this->run_lang."\">");
        $this->page_source .="\n";
        
        $this->page_source .= $this->HeadBuilder->getMy_structure();
        
        $this->page_source .="\n";
        $this->page_source .=("<body>");
        $this->page_source .="\n";
        $this->page_source .=$this->page_body_source;
        $this->page_source .="\n";
        $this->page_source .=("</body>");
        $this->page_source .="\n";
        $this->page_source .=("</html>");
		
        if ( $human_mode == HUMAN_MODE_DISABLE) {
            $this->page_source = preg_replace("/>([\r\n\s]+)</", "><", $this->page_source);
            $this->page_source = preg_replace("/<!--([\s\S]*?)-->/", '', $this->page_source);
            /*
             * [DEPUIS 13-09-15] @author
             *  Le code ci-dessous était à l'origine de bogues qui affectait de manière fatale l'utilisation du produit sur la machine DEV.
             *  Je suspect un problème de mémoire (leak, stackoverflow, etc ...).
             *  J'ai remplacé le code deffectueux par celui ci-dessus.
             */
//            $this->page_source = preg_replace("/<!--(.|\s)*?-->/", '', $this->page_source);
        }
        
        $this->ready_to_display = TRUE;
        
    }
    
    public function page_display() {
        
        /**
         * Les fichiers contenant du code php on exécute ce code grace à eval();
         * Le fait qu'il y ait du HTML et du PHP cause que eval détecte une erreur à cause de '<'
         * Ajouter des délimiteur PHP inversés permet de résoudre le problème.
         * C'est une astuce que j'ai trouvé sur le web. Mais cett date je n'ai pas compris exactement l'astuce.
         * [NOTE au 06/11/13 à 18:05 GMT+2]
         * 
         */
        
        /*
         * [NOTE 14-09-14] @author L.C.
         * La question est : MAIS OU AVAIS-JE LA TÊTE ?  !!!!!
         * J'ai ajouté un try... catch sur l'Elément le plus important de la partie VIEW.
         */
        set_error_handler('exceptions_error_handler');
        try {
					
            $this->page_source = eval(" ?>" . $this->page_source . "<?php ");
			
			
			if ( $this->page_source === FALSE )
                throw new Exception("err_sys_l63");
			
            restore_error_handler();
			
        } catch (Exception $exc) {
            
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,error_get_last(),'v_d');
            $this->presentVarIfDebug(__FUNCTION__, __LINE__,$exc->getTraceAsString(),'v_d');
            
            $this->signalError ("err_sys_l63", __FUNCTION__, __LINE__, TRUE);
            
        }

        /*
        $handle = tmpfile();
        fwrite($handle, $this->page_source);
        fseek($handle, 0);
        $foo =  fstat($handle);
        $size = $foo["size"];
        echo fread($handle,$size);
        //*/
    }
    
    /*********************************** GETTERS and SETTERS *******************************/
    public function getPg_xmlscope() {
        return $this->pg_xmlscope;
    }

    public function getHeadBuilder() {
        return $this->HeadBuilder;
    }

    public function getPage_body_source() {
        return $this->page_body_source;
    }

    public function getRun_lang() {
        return $this->run_lang;
    }

    public function getReady_to_build() {
        return $this->ready_to_build;
    }

    public function getReady_to_diaplay() {
        return $this->ready_to_display;
    }

}
?>
