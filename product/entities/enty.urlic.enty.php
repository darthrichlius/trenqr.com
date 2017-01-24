<?php


class URLIC extends MOTHER {
    
    private $UCTP;
    private $rgx_email;
    
    function __construct() {
        
        parent::__construct(__FILE__,__CLASS__);
        
        $this->UCTP = [
            "UCTP_ART_IML"  => 1,
            "UCTP_ART_ITR"  => 2,
            "UCTP_REACT"    => 3,
            "UCTP_TESTY"    => 4,
            "UCTP_MI"       => 5,
            "UCTP_PDREACT"  => 6
        ];
        
        /*******************************************************/
        
        $this->rgx_email = "/^[\w.+-]+@(?:[\w\d-]+\.)+[a-z]{2,6}$/i";
        
    }

    public function URLIC_exists ($id) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$id]);
        
       /*
        * ETAPE :
        *      On supprime les occurrences dans la table TESTIES
        */
       $QO = new QUERY("qryl4urlicn3");
       $params = array( ":id" => $id);  
       $datas = $QO->execute($params);
       
       return ( $datas ) ? $datas[0] : FALSE;
            
    }
    
    public function URLIC_oncreate ($t, $cid, $ceid, $ctp, $ssid, $locip, $curl = NULL, $uagnt = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$t,$cid,$ceid,$ctp,$ssid,$locip]);
        
        if (! in_array($ctp,array_keys($this->UCTP) ) ) {
            return "__ERR_VOL_WRG_DATAS";
        }
        
        $TXH = new TEXTHANDLER();
        $us = $TXH->ExtractURLs($t);
        if (! $us ) {
            return FALSE;
        }
        
        $uls = [];
        $time = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($time/1000));
        
        $OnlyOne;
        foreach ($us as $url) {
            
            /*
             * ETAPE :
             *      On vérifie qu'il ne s'agit pas d'un EMAIL
             */
            if ( preg_match($this->rgx_email, $url) ) {
                continue;
            } else if ( $OnlyOne && in_array($url,$OnlyOne) ) {
                continue;
            }
            
            /*
             * ETAPE :
             *      On extrait le domaine
             */
            $tmp = (! preg_match("#^https?://#ui", $url) ) ? "http://".$url : $url;
            
            $domain = str_ireplace('www.', '', parse_url($tmp, PHP_URL_HOST));
//            var_dump("CHECKPOINT => ",__LINE__,$domain,parse_url($tmp, PHP_URL_HOST));
//            continue;
            /*
             * ETAPE :
             *      On crée l'occurrence.
             */
            $QO = new QUERY("qryl4urlicn1");
            $params = array(
                ":ucid"     => $cid,
                ":uceid"    => $ceid,
                ":uctp"     => $this->UCTP[$ctp],
                ":gvnurl"   => $url,
                ":domain"   => $domain,
                ":ssid"     => $ssid,
                ":curl"     => $curl,
                ":locip"    => $locip,
                ":uagnt"    => $uagnt,
                ":date"     => $date,
                ":tstamp"   => $time,
            );  
            $id = $QO->execute($params);  
            
//            var_dump("CHECKPOINT => ",__FUNCTION__,__LINE__,$id);
//            exit();
        
            /*
             * ETAPE :
             *      On crée l'identifiant externe.
             */
            $eid = $this->entity_ieid_encode($time, $id);
            
            $QO = new QUERY("qryl4urlicn2");
            $params = array(":id" => $id, ":eid" => $eid);  
            $QO->execute($params);
            
            //On ajoute l'élément dans un tableau pour éviter les doublons
            $OnlyOne[] = $url;
            
            $uls[] = [$id,$eid,$url];
        }
        
//        var_dump("CHECKPOINT => ",__LINE__,$uls);
        
        return $uls;
    }
    
    /**
     * Permet de supprimer un lien URLIC.
     * 
     * @param type $id
     * @return boolean
     */
    public function URLIC_ondelete ($id) {
        /*
         * [NOTE 14-11-15] @author BOR
         *      Cette méthode sera utilisée avec modération.
         *      En effet, elle n'est pas liée à un contenu à propement parlé dans la base de données.
         *      Aussi, on pourra considérer ces données comme des archives d'autant qu'elles sont impersonnelles.
         */
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$id]);
        
        /*
         * ETAPE :
         *      On commence par supprimer les visites liées à cet UrlInContent !
         */
        $QO = new QUERY("qryl4urlic_vstn2");
        $params = array(":uicid" => $id );  
        $QO->execute($params);
        
        /*
         * ETAPE :
         *      On supprime l'UIC à proprement parlé.
         */
        $QO = new QUERY("qryl4urlicn4");
        $params = array(":id" => $id );  
        $QO->execute($params);
        
        return TRUE;
        
    }
    
    /*********************************************** ONVISIT SCOPE ***********************************************/ 
    
    
    /**
     * 
     * @param type $uicid L'identifiant de l'url URLIC
     * @param type $auid L'identifiant interne de l'utilisateur qui a cliqué le cas échéant
     * @param type $referer 
     * @param type $ssid
     * @param type $locip
     * @param type $curl
     * @param type $uagnt
     * @return INT L'identifiant de l'occurrence de la visite
     */
    public function URLIC_onvisit_declare ($uicid, $auid = NULL, $referer = NULL, $ssid = NULL, $locip = NULL, $curl = NULL, $uagnt = NULL) {
        //auid : L'identifiant interne de l'utilsateur qui a effectué l'action s'il existe.
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$uicid]);
        
        /*
         * [NOTE]
         *      Pour des raisons pratiques et de performance, on n'effectue que très peu de vérification. 
         *      On effet, il faut répondre le plus rapidement à CALLER.
         *      Si l'ACTOR et/ou l'URL n'existe pas, l'opération renverra NULL et c'est tout.
         */
        
        $time = round(microtime(TRUE)*1000);
        $date = date("Y-m-d G:i:s",($time/1000));
        
        $QO = new QUERY("qryl4urlic_vstn1");
        $params = array(
            ":uicid"    => $uicid,
            ":auid"     => $auid,
            ":refr"     => $referer,
            ":ssid"     => $ssid,
            ":curl"     => $curl,
            ":locip"    => $locip,
            ":uagnt"    => $uagnt,
            ":date"     => $date,
            ":tstamp"   => $time,
        );  
        $id = $QO->execute($params);  
        
        return $id;
    }
    
    
    
}

?>