<?php

/**
 * <p>
 * This class is made to give to the developper some tools to get access to some values contained within a xml file. 
 * All the functions are static because we want to oblige the developper to redifine the xml file and ensure he won't forget. 
 * The core driver behind this is to avoid the developper searching for a value in a wrong file. 
 * This is the best way we found to solve that issue and save him from wasting time around error seeking.
 * </p>
 * @author Lou Carther <dieudrichard@gmail.com>
 */
class MyXmlTools extends MOTHER
{
    function __construct() {

        //We ensure that until the mother this class is innit.  
        parent::__construct(__FILE__, __CLASS__);
    }

    public function checkXmlFileInTripleAction($entry, $std_err_enabled = TRUE)
    {
        $file_exist = MyXmlTools::checkIfFileExists($entry);
        //echo "FILE EXISTS".$file_exist = MyXmlTools::checkIfFileExists($entry);
        if( $file_exist ){
            $dom = "";
            $dom = MyXmlTools::getXmlDomFromFileGivenInParam($entry, $dom);
            //var_dump($dom);
            if( isset($dom) and $dom !== EXC_ABORT ) {
                if(MyXmlTools::valXmlFileGivenInParam($dom) !==  EXC_ABORT) return $dom; 
                else $this->signalError("err_sys_l010",__FUNCTION__, __LINE__, true);
                
            } else  $this->signalError("err_sys_l09",__FUNCTION__, __LINE__, true) ;
          
        } else {
            if ( $std_err_enabled ) {
                $this->presentVarIfDebug(__FUNCTION__, __LINE__, $entry);
                $this->signalError("err_sys_l08", __FUNCTION__, __LINE__, true) ;
            } else return $this->get_or_signal_error (1, "err_sys_l08", __FUNCTION__, __LINE__);
        }
    }
    
    /**
     * Permet de récupérer un XMLScope à partir d'un path et id tous les deux fournis par le "caller".<br/>
     * La fonction est sécurisé car elle permet d'obtenir le résultat si et seulement si TOUTES les conditions sont réunis mais sans planter.
     * err_used_in_case est obligatoire. De plus, on ne peut pas se tromper car cette fonction remplace une suite de procédures où l'on peut get ce errno facilement
     * @param string $entry_path
     * @param string $entry_id
     * @return xmlScope
     * [NOTE au 04/11/13] : Modification de la methode pour tolérer le cas où id est NULL en parametre.
     */
    public function acquiereXmlscopeFromPathAndIdInASecureWay ($entry_path, $entry_id, $err_used_in_case, $std_err_enabled = TRUE) {
        $arr = [$entry_path,$err_used_in_case];
        //On utilise pas func_get_args() car on admet que $entry_id soit null.
        
        $this->check_isset_entry_vars(__FUNCTION__, __LINE__, $arr); 
        
        $code_err = ($std_err_enabled) ? 2 : 1;
        
        $dom = $this->checkXmlFileInTripleAction($entry_path, $std_err_enabled);
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$entry_path);
        if( @isset($dom) and is_object($dom) ) {
            $local_urq_table="";
            //We try to get the urq concerned 
            $DomUrqTab = ( !empty($entry_id) ) ? $dom->getElementById($entry_id) : $dom->documentElement;
            //$DomUrqTab = $dom->getElementById('no736c70xx1');
            //$this->presentVarIfDebug(__FUNCTION__, __LINE__,$DomUrqTab,'v_d');
            $local_urq_table = MyXmlTools::recursFinderIntoArray($DomUrqTab, $entry_path );
            //$this->presentVarIfDebug(__FUNCTION__,__LINE__,$local_urq_table);
            if ( is_array($local_urq_table) and count($local_urq_table) ){
                return $local_urq_table;
            } else {
                if ( $std_err_enabled === FALSE )
                    return $this->get_or_signal_error ($code_err, $err_used_in_case, __FUNCTION__, __LINE__);
                else {
                    $this->presentVarIfDebug(__FUNCTION__, __LINE__, $entry_id,'v_d');
                    $this->signalError ($err_used_in_case, __FUNCTION__, __LINE__, TRUE);
                }    
            }
        } else return;
    }
    
    
    static function checkIfFileExists($entry)
    {
        return ( file_exists($entry) ) ? TRUE : FALSE;
    }
    
    
    /**
     * This function is made to load a xml_dom from the xml_file it received in param.
     * <p><b>IMPORTANT NOTE : <i>Ensure your are check if the file passed in param is set and exists.
     * This fucntion will throw an error if your file is not set or exist. It doesn't treat that case.</i>
     * </b></p>
     * @param type $entry_path_file
     * @return boolean <p>Return <i>FALSE</i> if it was impossible to load a file. <i>DomDocument</i> if the process went well.</p>
     */
    static function getXmlDomFromFileGivenInParam($entry_filename, $dom)
    {
        $is_load = EXC_ABORT;
        $dom = new DOMDocument();

        if( $dom->load($entry_filename) )
        {
            return $dom;
        }
        return $is_load;
    }
    
    
    /**
     * This function is made to validate a xml_dom that has been correctly loaded. 
     * <p><b>IMPORTANT NOTE : <i>Ensure your xml_dom is set.
     * This fucntion will throw an error if your xml_dom is not set or exist. It doesn't treat that case.</i>
     * </b></p>
     * @param type $entry_dom
     * @return string <p>Return <i>EXC_NO</i> if it was impossible to validate the file. <i>EXC_YES</i> if the process went well.</p>
     */
    static function valXmlFileGivenInParam($entry_dom)
    {
        $is_validate = EXC_ABORT;
        //Remove @ if you want to see the blabla
        if( (defined("IS_DEBUG") and "IS_DEBUG" == TRUE) or (defined("RIGHT_IS_DEBUG") and "RIGHT_IS_DEBUG" == TRUE) ) { if($entry_dom->validate() ) return EXC_YES; }
        else { if( @$entry_dom->validate()) return EXC_YES; }

        return $is_validate;
    }
    
    
    static function allNodeIntoAnAssosArray(DOMDocument $entry_dom, $entry_array)
    {
        $err_code = EXC_ABORT;
        
            $gr_conf_node = $entry_dom->getElementById("greentius");
            $entry_array = Array();
            
            $gr_conf_tidy = MyXmlTools::recursFinder($gr_conf_node, $entry_array);
            //var_dump($entry_array);
            if(is_array($gr_conf_tidy) and count($gr_conf_tidy) > 1) { return $gr_conf_tidy; }
        
        return $err_code;
    }
    
    
    /**
     * Renvoie un tableau ayant une definition similaire à celle de XML.
     * 
     * @param type $entry_node
     * @param type $file Permet de faciliter le debugage de la fonction
     * @return type
     */
    static function recursFinderIntoArray($entry_node, $file)
    //static function recursFinderIntoArray(DOMElement $entry_node, $entry_array) //DRM FOR DEBUG
    {
        $local_array = array();
        if (isset($entry_node) and isset($local_array))         
        {
            if ($entry_node->hasChildNodes())             
            {
                $children = $entry_node->childNodes;
                
                foreach ( $children as $node_child ) {   //var_dump($node_child);
                    //echo "<br/><br/><br/><br/>";
                    //echo "<p>$val ==> CASE : hasChild => hasID => id is not void => key = id</p>";
                    //CASE : hasChild => hasID => id is not void => key = id
                    //[NOTE au 07/11/13] : Ajout de la conditionnelle ci-dessous pour traiter les NodeText
                    if ( $node_child->nodeType == XML_TEXT_NODE and $node_child->parentNode->hasAttribute("id") and $node_child->parentNode->getAttribute("id") != "" and !empty($node_child->nodeValue) ) {
                        $st = preg_replace("#[\s|\t\n]*#", "", $node_child->nodeValue);
                        if ( preg_match("#[\w-]+#", $st) ) 
                            $local_array[$node_child->parentNode->getAttribute("id")] = $node_child->nodeValue;
                    }
                    else if( $node_child->nodeType == XML_ELEMENT_NODE and $node_child->hasChildNodes() and $node_child->hasAttribute("id")  and $node_child->getAttribute("id") != "")
                    {
                        $new_array = "";
                        $local_array[$node_child->getAttribute("id")] = MyXmlTools::recursFinderIntoArray($node_child, $new_array);
                    }
                    //CASE : hasChild => hasID => id is void => key = 'not_defined' (it will works only once.Il n'avait qu'à mettre un id) 
                    if( $node_child->nodeType == XML_ELEMENT_NODE and $node_child->hasChildNodes() and $node_child->hasAttribute("id")  and $node_child->getAttribute("id") == "")
                    {
                        $new_array = "";
                        $local_array['not_defined'] = MyXmlTools::recursFinderIntoArray($node_child, $new_array);
                    }
                    //CASE : hasChild => no ID => key = nodeName
                    else if ( $node_child->nodeType == XML_ELEMENT_NODE and $node_child->hasChildNodes() and !$node_child->hasAttribute("id") )
                    {   
                        $new_array = "";
                        $local_array[$node_child->nodeName] = MyXmlTools::recursFinderIntoArray($node_child, $new_array);
                    }
                    //CASE : noChild => hasID => => id is not void => hasValue => key = id
                    else if ($node_child->nodeType == XML_ELEMENT_NODE and !$node_child->hasChildNodes() and $node_child->hasAttribute("id") and $node_child->getAttribute("id") != "" and $node_child->hasAttribute("value") ) 
                    {
                            $local_array[$node_child->getAttribute("id")] = $node_child->getAttribute("value");
                    }
                    //CASE : noChild => no ID => hasValue => key = nodeName
                    else if ($node_child->nodeType == XML_ELEMENT_NODE and !$node_child->hasChildNodes() and !$node_child->hasAttribute("id") and $node_child->hasAttribute("value") ) 
                    {   
                        /**
                         * [NOTE au 05/1/13] : Ajout de l'instruction de telle sorte qu'ils considère les balises sans id mais avec un name.
                         * Cela permet de palier à l'obligation de mettre un id aux nodes ayant le même nodeName.
                         * 
                         * Cette partie n'a pas encore été testé à 17:40
                         */
                        if ( $node_child->hasAttribute("name") ) {
                            $local_array[$node_child->getAttribute("name")][] = $node_child->getAttribute("value");
                        } else 
                            $local_array[$node_child->nodeName] = $node_child->getAttribute("value");
                    } 
                }
            }
            else
            {
                if($entry_node->nodeType == XML_ELEMENT_NODE)
                {
                    if ( $entry_node->hasAttribute("id") and $entry_node->getAttribute("id") != "" and $entry_node->hasAttribute("value") ){
                        $local_array[$entry_node->getAttribute("id")] = $entry_node->getAttribute("value");
                    }
                    else if ( !$entry_node->hasAttribute("id") and $entry_node->hasAttribute("value") ){
                        $local_array[$entry_node->nodeName] = $entry_node->getAttribute("value");
                    }
                    else {
                        //Nothing : according to our rules it's not possible. This function only treat according our xml rules.
                    }
                }
            }
        }

        //DEGUGGING ZONE : Please, DRM : FOR DEBUG
        //echo $debug_text;
        //var_dump($entry_array); 
        $foo = $local_array;
        //var_dump($local_array); 
        //exit;
        return $foo;  
    }
    
    
    static function getAttlistValueForThisTag( DOMDocument $entry_dom, $return_value ) { }
    
    
    static function getArrayFromAdomViaANode($dom,$node)
    {
        // TODO : ?
    }
    /**
     * @access private
     * @todo Faire que la valeur de retour soit plus cohérente.
     * @param DOMDocument $Dom
     * @param type $id
     * @param DOMElement $NewChild
     * @return type
     */
    static function replace_child_if_exists ( DOMDocument $Dom, $id, DOMElement $NewChild ) {
        if (! $Dom or ! $id) 
            return;
        
        $To_repl = $Dom->getElementById($id);
        
        if (! $To_repl) 
            return $Dom->replaceChild($NewChild, $To_repl);
    }
}
?>
