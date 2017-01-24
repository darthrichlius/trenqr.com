<?php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of srvc
 *
 * @author arsphinx
 */
class TEXTHANDLER extends MOTHER {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }
    
	/**
     * Permet de vérifier si l'argument passé en paramètre est vide.
     * Cette fonction permet de palier la faiblesse de la fonction empty(), qui considère une chaine ne contenant que le caractère '0', comme vide.
     * 
     * @param mixed $arg
     * @return boolean
     */
	public function tqr_is_blank ($arg) {
        return empty($arg) && !is_numeric($arg);
    }
	
    /**
     * Permet de transformer un texte afin de pouvoir l'insérer dans une URL.
     * Le but est de faire que la chaine en sortie soit naturellement lisible pour l'utilisateur final.
     * Exemple : Pour insérer le titre d'une Tendance dans une URL lorsqu'elle comporte des caractères telles que des espace ou des apostrophes.
     * 
     * Cette fonction utilise la fonction htmlentities() si le $flags est vide.
     * 
     * 
     * 
     * @author John Doe <lou.carther@deuslynn-entreprise.com>
     * @since vb1.10.08.14
     * @param string $s Chaine de caractère à transformer.
     * @return string La version de la chaine prete à être utiliser dans une url.
     */
    private function text_urlize_from_flags ($s,$flags = NULL) {
        //TODO : Mettre en place les instructions. Je préfère travailler sur la version avec pattern car j'en ai besoin tout de suite.
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $s);
        
        /*
         * $flags est un tableau qui contient des coucles de valeurs dont la clé est un masque et la valeur la valeur de remplacement.
         * 
         * LISTE DES FLAGS :
         * WOS_TXTFLAG_QUOTES => 
         * 
         */
        
        return $r;
    }
    
    /**
     * Permet de transformer un texte afin de pouvoir l'insérer dans une URL.
     * Le but est de faire que la chaine en sortie soit naturellement lisible pour l'utilisateur final.
     * Exemple : Pour insérer le titre d'une Tendance dans une URL lorsqu'elle comporte des caractères telles que des espace ou des apostrophes.
     * 
     * Cette fonction utilise la fonction htmlentities() si le $flags est vide.
     * 
     * @author John Doe <lou.carther@deuslynn-entreprise.com>
     * @since vb1.10.08.14
     * @param string $s Chaine de caractère à transformer.
     * @param int $pattern Le modèle de transformation à utiliser.
     * @param int $errorcode Le code de l'erreur permet de savoir s'il faut déclencher une erreur ou la renvoyer.
     * @return string La version de la chaine prete à être utiliser dans une url.
     */
    public function text_urlize_from_pattern ($s, $pattern = WOS_TXTPAT_DEFAULT, $errcode = 2) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $s);
        
        if ( !empty($pattern) ) {
            switch ($pattern) {
                case WOS_TXTPAT_DEFAULT :
                        return $this->urlize_pattern_1($s);
                    break;
                default:
                        //TODO : Déclencher une erreur technique
                        return;
                    break;
            }
        } else {
            return htmlentities($s);
        }
    }
    
    
    public function text_to_data_cache ($s, $errcode = 2) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $s);
        
        $c =  [',','[',']','"','\'','\\'];
        $r = ['&#44;','&#91;','&#93;','\\&quot;','&#39;','\\&#92;'];
        return str_replace($c,$r,$s);
    }
    
    /**
     * Retounre le nombre de caractères pour les chaines encodées en UTF-8.
     * @author BlackOwlRobot <lou.carther@deuslynn-entreprise.com>
     */
    public function strlen_utf8 ($s) {
        return mb_strlen($s,"UTF8");
    }
    
    /**
     * Permet de convertir des caractères emojis (unicode) vers un format "UTF-8" pour les insérer dans la base de données.
     * Cette méthode se focalise sur l'action d'Input quand la fonction soeur se focalise sur l'Output
     * 
     * @param type $raw_text
     * @return type
     */
    public function replace_emojis_in($raw_text) {
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $raw_text);
        
        $clean_text = "";

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/([\x{1F300}-\x{1F5FF}])/u';
        $clean_text = preg_replace_callback($regexSymbols, function ($matches) {
            $char = current($matches);
            $utf = iconv('UTF-8', 'UCS-4', $char);
            return sprintf("&#x%s;", ltrim(strtoupper(bin2hex($utf)),"0"));
        }, $raw_text); //[NOE 05-07-16] C'est NORMAL qu'il y ait $raw_text ) cet endroit !
        
        // Match Emoticons
        $regexEmoticons = '/([\x{1F600}-\x{1F64F}])/u';
        $clean_text = preg_replace_callback($regexEmoticons, function ($matches) {
            $char = current($matches);
            $utf = iconv('UTF-8', 'UCS-4', $char);
            return sprintf("&#x%s;", ltrim(strtoupper(bin2hex($utf)),"0"));
        }, $clean_text);
        
        // Match Transport And Map Symbols
        $regexTransport = '/([\x{1F680}-\x{1F6FF}])/u';
        $clean_text = preg_replace_callback($regexTransport, function ($matches) {
            $char = current($matches);
            $utf = iconv('UTF-8', 'UCS-4', $char);
            return sprintf("&#x%s;", ltrim(strtoupper(bin2hex($utf)),"0"));
        }, $clean_text);
        
        // Match Emoticons : Supplemental
        $regexEmoticons = '/([\x{1F910}-\x{1F918}])/u';
        $clean_text = preg_replace_callback($regexEmoticons, function ($matches) {
            $char = current($matches);
            $utf = iconv('UTF-8', 'UCS-4', $char);
            return sprintf("&#x%s;", ltrim(strtoupper(bin2hex($utf)),"0"));
        }, $clean_text);
        
        // Match Miscellaneous Symbols and Pictographs (Supplemental : Hand Symbols)
        $regexSymbols = '/([\x{1F918}])/u';
        $clean_text = preg_replace_callback($regexSymbols, function ($matches) {
            $char = current($matches);
            $utf = iconv('UTF-8', 'UCS-4', $char);
            return sprintf("&#x%s;", ltrim(strtoupper(bin2hex($utf)),"0"));
        }, $clean_text);
        
        /*
         * [DEPUIS 25-06-16]
         *      Ajoutés avec UNICODE 9.0
         */
        $regexEmoticons = '/([\x{1F918}-\x{1F91F}])/u';
        $clean_text = preg_replace_callback($regexEmoticons, function ($matches) {
            $char = current($matches);
            $utf = iconv('UTF-8', 'UCS-4', $char);
            return sprintf("&#x%s;", ltrim(strtoupper(bin2hex($utf)),"0"));
        }, $clean_text);

        /*
         * [DEPUIS 25-06-16]
         *      Ajoutés avec UNICODE 9.0
         */
        $regexEmoticons = '/([\x{1F920}-\x{1F927}])/u';
        $clean_text = preg_replace_callback($regexEmoticons, function ($matches) {
            $char = current($matches);
            $utf = iconv('UTF-8', 'UCS-4', $char);
            return sprintf("&#x%s;", ltrim(strtoupper(bin2hex($utf)),"0"));
        }, $clean_text);
        
        /*
         * [DEPUIS 25-06-16]
         *      Ajoutés avec UNICODE 9.0
         */
        $regexEmoticons = '/([\x{1F930}-\x{1F93F}])/u';
        $clean_text = preg_replace_callback($regexEmoticons, function ($matches) {
            $char = current($matches);
            $utf = iconv('UTF-8', 'UCS-4', $char);
            return sprintf("&#x%s;", ltrim(strtoupper(bin2hex($utf)),"0"));
        }, $clean_text);
        
        // Match Miscellaneous Symbols and Pictographs (Supplemental : Animals)
        $regexSymbols = '/([\x{1F980}-\x{1F984}])/u';
        $clean_text = preg_replace_callback($regexSymbols, function ($matches) {
            $char = current($matches);
            $utf = iconv('UTF-8', 'UCS-4', $char);
            return sprintf("&#x%s;", ltrim(strtoupper(bin2hex($utf)),"0"));
        }, $clean_text);
        
        // Match Miscellaneous Symbols and Pictographs (Supplemental : Food Symbols)
        $regexSymbols = '/([\x{1F9C0}])/u';
        $clean_text = preg_replace_callback($regexSymbols, function ($matches) {
            $char = current($matches);
            $utf = iconv('UTF-8', 'UCS-4', $char);
            return sprintf("&#x%s;", ltrim(strtoupper(bin2hex($utf)),"0"));
        }, $clean_text);

        // Match Miscellaneous Symbols
        $regexMisc = '/([\x{2600}-\x{26FF}])/u';
        $clean_text = preg_replace_callback($regexMisc, function ($matches) {
            $char = current($matches);
            $utf = iconv('UTF-8', 'UCS-4', $char);
            return sprintf("&#x%s;", ltrim(strtoupper(bin2hex($utf)),"0"));
        }, $clean_text);

        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace_callback($regexDingbats, function ($matches) {
            $char = current($matches);
            $utf = iconv('UTF-8', 'UCS-4', $char);
            return sprintf("&#x%s;", ltrim(strtoupper(bin2hex($utf)),"0"));
        }, $clean_text);

        return $clean_text;
    }
    
    
    public function remove_emojis_in($raw_text) {
        
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $raw_text);

        $clean_text = "";

        
        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/([\x{1F300}-\x{1F5FF}])/u';
        $clean_text = preg_replace_callback($regexSymbols, function ($matches) {
            return "";
        }, $clean_text);
        
        // Match Emoticons
        $regexEmoticons = '/([\x{1F600}-\x{1F64F}])/u';
        $clean_text = preg_replace_callback($regexEmoticons, function ($matches) {
            return "";
        }, $raw_text); //[NOE 05-07-16] C'est NORMAL qu'il y ait $raw_text ) cet endroit !
        
        // Match Transport And Map Symbols
        $regexTransport = '/([\x{1F680}-\x{1F6FF}])/u';
        $clean_text = preg_replace_callback($regexTransport, function ($matches) {
            return "";
        }, $clean_text);
        
        // Match Emoticons : Supplemental
        $regexEmoticons = '/([\x{1F910}-\x{1F918}])/u';
        $clean_text = preg_replace_callback($regexEmoticons, function ($matches) {
            return "";
        }, $clean_text);
        
        // Match Miscellaneous Symbols and Pictographs (Supplemental : Hand Symbols)
        $regexSymbols = '/([\x{1F918}])/u';
        $clean_text = preg_replace_callback($regexSymbols, function ($matches) {
            return "";
        }, $clean_text);
        
        /*
         * [DEPUIS 25-06-16]
         *      Ajoutés avec UNICODE 9.0
         */
        $regexEmoticons = '/([\x{1F918}-\x{1F91F}])/u';
        $clean_text = preg_replace_callback($regexEmoticons, function ($matches) {
            return "";
        }, $clean_text);
        
        /*
         * [DEPUIS 25-06-16]
         *      Ajoutés avec UNICODE 9.0
         */
        $regexEmoticons = '/([\x{1F920}-\x{1F927}])/u';
        $clean_text = preg_replace_callback($regexEmoticons, function ($matches) {
            return "";
        }, $clean_text);
        
        /*
         * [DEPUIS 25-06-16]
         *      Ajoutés avec UNICODE 9.0
         */
        $regexEmoticons = '/([\x{1F930}-\x{1F93F}])/u';
        $clean_text = preg_replace_callback($regexEmoticons, function ($matches) {
            return "";
        }, $clean_text);

        // Match Miscellaneous Symbols and Pictographs (Supplemental : Animals)
        $regexSymbols = '/([\x{1F980}-\x{1F984}])/u';
        $clean_text = preg_replace_callback($regexSymbols, function ($matches) {
            return "";
        }, $clean_text);
        
        // Match Miscellaneous Symbols and Pictographs (Supplemental : Food Symbols)
        $regexSymbols = '/([\x{1F9C0}])/u';
        $clean_text = preg_replace_callback($regexSymbols, function ($matches) {
            return "";
        }, $clean_text);

        // Match Miscellaneous Symbols
        $regexMisc = '/([\x{2600}-\x{26FF}])/u';
        $clean_text = preg_replace_callback($regexMisc, function ($matches) {
            return "";
        }, $clean_text);

        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace_callback($regexDingbats, function ($matches) {
            $char = current($matches);
            $utf = iconv('UTF-8', 'UCS-4', $char);
            return "";
        }, $clean_text);

        return $clean_text;
    }
    
    
    /**
     * Retourne la taille de la chaine après l'avoir amputée de caractères indésirables passés en paramètre.
     * Ces caractères permettent d'identifier des tags dans un texte. Il peut s'agir de hashtg ou de tag pour reconnaitre un utilisateur.
     * @author BlackOwlRobot <lou.carther@deuslynn-entreprise.com>
     * @since VB1.WOS.1503.1.1
     * @param string $s La chaine à traiter
     * @param array $s Le tableau contenant les caractères à retirer.
     * @return mixed La taille de la nouvelle chaine amputée des caractères indésirables. Retourne FALSE si les caractères passés ne ressemblent pas à des caractères.
     */
    public function strlen_ship_tagsmarks ($s,$maks) {
        if ( !( is_string($s) && !$this->tqr_is_blank($s) ) | !( is_array($maks) && !$this->tqr_is_blank($maks) ) ) {  
//        if ( !( is_string($s) && !empty($s) ) | !( is_array($maks) && !empty($maks) ) ) { //[DEPUIS 15-08-15] @BOR
            return; 
        }
        
        foreach ($maks as $mark) {
            if ( !is_string($mark) | !( is_string($mark) && strlen($mark) === 1 ) ) {
                return false;
            }
            /*
             * [DEPUIS 18-11-15] @author BOR
             *      Mise à jour pour plus de fiabilité des données et une concordance d'analyse entre FE et SRV
             */
            if ( $mark === "#" ) {
                $reg = "/(\#)(?=(?:(?=[a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż])|(?:[\d_](?=[a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż])))[a-z\d_ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]+)/i";
            } else if ( $mark === "@" ) {
                $reg = "/(\@)(?=(?=.*[a-z])[\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})/i";
            } else {
                $reg = "/(\\".$mark.")(?![\\".$mark."\s]+)(?=.+)/";
            }
            
            $s = preg_replace($reg,"", $s);
        }
        
//        return strlen($s); //[DEPUIS 26-04-15]
        return mb_strlen($s,"UTF8");
    }
    
    public function strlen_skip_hashtags ($s) {
        if ( !isset($s) || !is_string($s) ) { return; }
        
        $reg = "/(\#)(?![\#\s]+)(?=.+)/";
        $r = preg_replace($reg, "", $s);
        return strlen($r);
    }
    
    public function strlen_skip_usertags ($s) {
        if ( !isset($s) || !is_string($s) ) { return; }
        
        $reg = "/(\@)(?![\@\s]+)(?=.+)/";
        $r = preg_replace($reg, "", $s);
        return strlen($r);
    }
    
    /**
      * Extrait d'un texte fourni en paramètre les mots-clés.
      * Si le texte ne contient aucun mot-clé, la fonction renvoie FALSE. Sinon, revoie les mots-clés trouvés.
      * 
      * @param string $t
      * @return NULL | FALSE | Tableau des mots-clés extraits.
      */
     public function extract_prod_keywords ($t) {
        if ( !isset($t) || !is_string($t) ) {
            return;
        }
//        $reg = "/#([a-z\d_ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]+)/i";
        /*
         * [DEPUIS 16-11-15]
         *      Permet d'ajouter les spécificités suivantes :
         *          -> Il n'y a pas y avoir que des chiffres.
         *          En effet, un hashtag n'ayant que des chiffres est ... aux abus. De plus, sa compréhension littérale peut être proche de NULL.
         */
        $reg = "/#((?:(?=[a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż])|(?:[\d_](?=[a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż])))[a-z\d_ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]+)/i";
        $matches = NULL;
        if (! preg_match_all($reg, $t, $matches) ) { 
            return 0;
        }
        return $matches;
     }
    
    /**
      * Extrait d'un texte fourni en paramètre les mots-clés.
      * Si le texte ne contient aucun mot-clé, la fonction renvoie FALSE. Sinon, revoie les usertags trouvés.
      * 
      * @param string $t Le text dont il faut effectuer les extractions
      * @return NULL | FALSE | Tableau des mots-clés extraits.
      */
     public function extract_tqr_usertags ($t) {
        if ( !isset($t) || !is_string($t) ) { 
            return; 
        }
        $reg = "/\@(?=.*[a-z])([\wÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{2,20})/i";
        $matches = NULL;
        if (! preg_match_all($reg, $t, $matches) ) {
            return 0;
        }
        return $matches;
     }
     
     /**
     * Converts all accent characters to ASCII characters.
     *
     * If there are no accent characters, then the string given is just returned.
     *
     * @param string $string Text that might have accent characters
     * @return string Filtered string with replaced "nice" characters.
     */
    public function remove_accents ($string) {
        if ( empty($string) or !is_string($string) )
            return;
        
        if ( !preg_match('/[\x80-\xff]/', $string) )
            return $string;

        if ( $this->seems_utf8($string) ) {
            $chars = array(
            // Decompositions for Latin-1 Supplement
            chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
            chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
            chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
            chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
            chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
            chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
            chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
            chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
            chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
            chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
            chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
            chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
            chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
            chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
            chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
            chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
            chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
            chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
            chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
            chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
            chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
            chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
            chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
            chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
            chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
            chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
            chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
            chr(195).chr(191) => 'y',
            // Decompositions for Latin Extended-A
            chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
            chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
            chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
            chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
            chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
            chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
            chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
            chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
            chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
            chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
            chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
            chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
            chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
            chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
            chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
            chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
            chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
            chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
            chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
            chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
            chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
            chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
            chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
            chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
            chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
            chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
            chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
            chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
            chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
            chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
            chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
            chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
            chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
            chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
            chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
            chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
            chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
            chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
            chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
            chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
            chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
            chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
            chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
            chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
            chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
            chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
            chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
            chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
            chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
            chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
            chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
            chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
            chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
            chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
            chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
            chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
            chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
            chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
            chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
            chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
            chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
            chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
            chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
            chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
            // Euro Sign
            chr(226).chr(130).chr(172) => 'E',
            // GBP (Pound) Sign
            chr(194).chr(163) => '');

            $string = strtr($string, $chars);
        } else {
            // Assume ISO-8859-1 if not UTF-8
            $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
                .chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
                .chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
                .chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
                .chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
                .chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
                .chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
                .chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
                .chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
                .chr(252).chr(253).chr(255);

            $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

            $string = strtr($string, $chars['in'], $chars['out']);
            $double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
            $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
            $string = str_replace($double_chars['in'], $double_chars['out'], $string);
        }

        return $string;
    }
    
    public function valid_user_in_url ($psd) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        if ( preg_match("/^([\w\-ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż]{2,20})$/i", $psd) ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    //L.C. 16-08-14
    public function secure_text ($source) {
        /*
         * Cette fonction centralise l'ensemble des actions à effectuer sur un texte afin de le rendre sur.
         * Elle est amenée à évoluer dans le temps en fonction des nouvelles contraintes.
         */
        
        if ( !isset($source) || $source == "" ) {
            return;
        }
        
//        $string = preg_replace("/\r*\n/","\\n",$source);
//        $string = preg_replace("/\//","\\\/",$string);
//        $string = preg_replace("/\"/","\\\"",$string);
//        $source = preg_replace("/'/"," ",$string);
        
        $source = str_replace("\\\\n", " ", $source);
        
        //On échappe le texte 
        $source = htmlentities($source);
        
        return $source;
        
    }
    
    public function genuine_pseudo_in_url ( $user ) {
        if ( !isset( $user ) || $user === "" ) {
            return;
        }
            
        $pos = strpos($user, '@');
        
        if ( $pos === 0 || $pos > 0 ) {
            if ( $pos === 0 ) {
                //On extrait de la chaine le pseudo sans le caractère '@'.
                //RAPPEL : Les pseudos ne peuvent pas avoir des caractères autres que des lettres, chiffres,_ et certains caractères alphabéthiques de langues étrangères
                $nuser = substr($user, 1);
                return $nuser;
            } else {
                return FALSE;
            }
        }
        
        return $user;
    }
    
    public function explode_std_url ($url, $cmp_params_sep = ".", $cmp_cpl_sep = "=") {
        /*
         * Renvoie un tableau contenant: PAGESCOPE, USER, PAGE, URQ, UPS.
         * Pour ce faire, on se base sur les url de type Rewriting telles que définies sur le produit .
         * 
         * ATTENTION
         * Text_Handler étant un service plus générique que URL_HANDLER il ne tente pas de traduire l'url si elle est incomplète.
         * C'est AU CALLER DE LE FAIRE.
         */
        if (! ( isset($url ) && is_string($url) && $url !== "" ) )
            return;
        
        //MODELE : https://www.domain.com/dir/1/2/search.html?arg=0-a&arg1=1-b&arg3-c#hash
        $reg = "%^((http[s]?|ftp):\/)?\/?([^:\/\s]+)((\/\w+)*\/)([\w\-\.]+[^#?\s]+)(.*)?(#[\w\-]+)?$%";
        $matches = $url_pieces = NULL;
        
        if ( preg_match_all($reg, $url, $matches) ) {
            $url_pieces = [
                "url"       => $matches[0][0],
                "protocol"  => $matches[2][0],
                "host"      => $matches[3][0],
                "path"      => $matches[4][0],
                "file"      => $matches[6][0],
                "query"     => $matches[7][0],
                "hash"      => $matches[8][0]
            ];
        } else return FALSE;
        
        return $url_pieces;
    }
    
    public function get_deco_text ($lang,$code) {
        /*
         * Permet de récupérer le texte lié à un code deco passé en paramètre.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        $lang = strtolower($lang);
        $fp = WOS_GEN_PATH_TO_DECODEF_REPOS.$lang."/".WOS_DECODEF_FILE;
        
        if (! file_exists($fp) ) {
            return "__ERR_VOL_NO_FILE_MATCHING";
        }
        
        $MyXT = new MyXmlTools();
        $deco_tab = $MyXT->acquiereXmlscopeFromPathAndIdInASecureWay($fp, null, "err_sys_l01");
        
        $nc = ( $code[0] === '_' ) ? $code : "_".$code;
        
        if (! in_array($nc,array_keys($deco_tab)) ) {
            return "__ERR_VOL_CODE_UKNW";
        } else {
            $t = $deco_tab[$nc][$nc];
            
            if (! isset($t) ) {
                return "__ERR_VOL_UXPTD_ERR";
            } else {
                return trim($t);
            }
        }
        
    }
    
    public function HasDmdMarks ($s) {
        /*
         * Vérifie si un texte comporte des marqueurs de type DMD (DolphinsMarkupDatas)
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $s);
        
        return ( preg_match_all("/%{[\w]+}%/", $s) ) ? TRUE : FALSE;
    }

    public function ExtractAllDmd ($s, $ckeck_dmd = TRUE) {
        /*
         * Extrait tous les marqueurs d'un texte.
         * Cette méthode peut par exemple être utilisée avant celle de remplacement.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $s);
        
        //On vérifie qu'il y a effectivement des DMD
        if ( $ckeck_dmd && !$this->HasDmdMarks($s) ) {
            return FALSE;
        }       
        
        $ms = NULL;
        preg_match_all("/%{([\w]+)}%/", $s, $ms);
        
        return $ms[1];
    }

    public function ReplaceDmd ($srh, $rep, $subj) {
        /*
         * Permet de remplacer un DMD dans un texte.
         * Tous les DMD du type de celui recherché seront remplacés.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, func_get_args());
        
        //On vérifie qu'il y a effectivement des DMD
        $dmd_tab = $this->ExtractAllDmd($subj);
//        var_dump(__LINE__,__FILE__,$dmd_tab);
        if (! $dmd_tab ) {
            return FALSE;
        } 
        
        //On vérifie que la référence DMD est bien présente
        if (! in_array($srh, $dmd_tab) ) {
            return "__ERR_VOL_MISMATCH";
        }
        
        $t_srh = "%{".$srh."}%";
        $r = str_replace($t_srh, $rep, $subj);
        
        return $r;
    }
    
    /**
     * Permet d'extraire les URLs d'un texte passé en paramètre. 
     * ATTENTION : Cette méthode considère les adresses email comme des URL
     * 
     * @param string $t Le texte a traité
     * @return array Le tableau vide ou contenant les les liens.
     */
    public function ExtractURLs ($t) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $t);
        
        $matches = $m = [];
        $rgx = '_((?:(?:https?)://)?(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?)_iuS';
        
        if ( preg_match_all($rgx,$t,$m) ) {
            $matches = $m[0];
        }
        
        return $matches;
    }
    

    /*********************************************************************************************************************/
    /************************************************ PRIVATE LAYER ******************************************************/
    
    private function explode_compound_urltext ($url, $cmp_params_sep, $cmp_cpl_sep) {
        /*
         * [NOTE 11-09-14] @author L.C. 
         * Code récupéré de URL_HANDLER
         */
        
        if ( ( isset($url) && isset($url) && $url !== "" )
            && ( isset($cmp_params_sep) && isset($cmp_params_sep) && $cmp_params_sep !== "" )
            && ( isset($cmp_cpl_sep) && isset($cmp_cpl_sep) && $cmp_cpl_sep !== "" ) ) {
            return;
        }
            
        $couples_of_params = explode($cmp_params_sep, $url);
        //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$couples_of_params);
        if ( isset($couples_of_params) and count($couples_of_params) > 0 )
        {
            $params_table = array();
            foreach ($couples_of_params as $couple) {
                $key_value_couple = explode($cmp_cpl_sep,$couple);
                //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$key_value_couple);
                if ( isset($key_value_couple) and count($key_value_couple) == 2 ) {
                    $params_table[$key_value_couple[0]] = $key_value_couple[1];                        
                } else return FALSE;
            }
            return $params_table;
        }//La fonction explode, si elle ne trouve pas le separateur elle revoie un tableau de 1 element, cet elecment est le paramètre qui lui est envoyé. Aussi, c'est impossible que count soit ==0   
    } 


    /**
     * 
     * @param string $s La chaine à transformer.
     * @return string La chaine résultant de la transformation de la chaine en paramètre.
     */
    private function urlize_pattern_1 ($s) {
        
        //TODO : Lancer une erreur
        if ( empty($s) ) return;
        
        /*
         * Certains caractères dans la chaine sont transformer selon les règles ci-dessous
         * RULES : 
         * (1) -_ deviennent -_ 
         * (2) Les caractères \(\)\[\] et \s\t\n\x0B\r deviennent -
         * (2) Tous les caractères qui ne sont ni des caractères alphabétiques ni des chiffres sont transformés en (void)
         */
        
        try {
            
            $s = trim($s);
            $s = preg_replace("/([\(\)\[\]\s\t\n\x0B\r\-]+)/","-",$s);
            $s = preg_replace("/([^\w\-ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż]+)/i",'', $s);
            $s = preg_replace("/([-]+)/","-",$s);
            
            $ln = strlen($s);
            if ( $s[--$ln] === '-' )
                $s = substr($s, 0, -1);
            
            if ( $s[0] === '-' )
                $s = substr($s, 1);
            
            return $s;
        } catch (Exception $exc) {
            //TODO : Gérer l'erreur
            return;
        }

    }
    
    private function seems_utf8($str)
    {
        $length = strlen($str);
        for ($i=0; $i < $length; $i++) {
            $c = ord($str[$i]);
            if ($c < 0x80) $n = 0; # 0bbbbbbb
            elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
            elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
            elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
            elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
            elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
            else return false; # Does not match any model
            for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
                if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                    return false;
            }
        }
        return true;
    }
    
    
}
