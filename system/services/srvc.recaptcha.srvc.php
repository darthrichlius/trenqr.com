<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SRVC_ReCaptcha extends MOTHER {
    private $attributes_keys;
    private $check_url;
    private $code_site;
    private $code_secret;
    
    function __construct($key_site,$key_secret) {
        parent::__construct(__FILE__,__CLASS__);
        $this->attributes_keys = ["data-sitekey","data-theme","data-type","data-size","data-tabindex","data-callback","data-expired-callback"];
        
        $this->code_site = $key_site;
        $this->code_secret = $key_secret;
        
        $this->check_url = "https://www.google.com/recaptcha/api/siteverify?".http_build_query(["secret"=>$this->code_secret]);
    }
    
    
    public function checkResponse ($g_r_response, $remoteip = NULL, $_OPTIONS = NULL){
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, $g_r_response);
        
        /*
         * On ajoute la réponse
         */
        $params = [
            "response" => $g_r_response,
            "remoteip" => $remoteip
        ];
        $this->check_url .= "&".http_build_query($params);
//        var_dump(__LINE__,$this->check_url);
        
        if ( function_exists("curl_version") ) {
            $curl = curl_init($this->check_url);
            
            curl_setopt($curl, CURLOPT_HEADER, FALSE);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1500);
//            var_dump(__LINE__,$_OPTIONS && key_exists("ssl_verifypeer", $_OPTIONS) && $_OPTIONS["ssl_verifypeer"] === FALSE);
            if ( $_OPTIONS && key_exists("ssl_verifypeer", $_OPTIONS) && $_OPTIONS["ssl_verifypeer"] === FALSE ) {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            }
            
            $response = curl_exec($curl);
            curl_close($curl);
        } else {
            $response = file_get_contents($this->check_url);
        }
        
        if ( empty($response) ) {
            return false;
        }
        
        $json = json_decode($response);
        if ( empty($json) ) {
            return "__ERR_VOL_JSON";
        }
        
        if ( $_OPTIONS && key_exists("get_json_object", $_OPTIONS) && $_OPTIONS["get_json_object"] === TRUE ) {
            return $json;
        } else {
            return $json->success;
        }
    }
    
    /**
     * Génère la représentation HTML du captcha en fonction de la clé publique
     * @param string $key_site La clé publique attribuée au site
     * @param array $_OPTIONS Contient la liste des attributs de type 'data' qu'on aimerait ajouté à la zone
     * @return string La représentation HTML du captcha
     */
    public function getHtml ($key_site = NULL, $_OPTIONS = NULL ) {
        try {
            $cs = ( $key_site ) ? $key_site : $this->code_site;
        
            $html = "<div class='g-recaptcha' data-sitekey='{$cs}'></div>";
            if ( $_OPTIONS ) {
                $dom = new simple_html_dom();
                $dom->load($html);
                foreach ($_OPTIONS as $name => $value) {
                    if ( !in_array($name,$this->attributes_keys) ) {
                        continue;
                    } 
                    $dom->find(".g-recaptcha",0)->setAttribute($name,$value);
                }
                
                $html = $dom->outertext;
            }

            return $html;
        } catch (Exception $ex) {
            return "__ERR_VOL_FAILED";
        }
        
    }
    
}
