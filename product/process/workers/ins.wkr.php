<?php

require_once dirname(__DIR__) . '/../../vendor/autoload.php';

use Facebook\Facebook;
use Facebook\Helpers\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphNodes\GraphUser;


class WORKER_ins extends WORKER  {
    
    private $_API_FB_AppName;
    private $_API_FB_AppID;
    private $_API_FB_AppSecret;
    private $_API_FB_LoginUrl;
    
    function __construct($runlang) {
        parent::__construct(__FILE__,__CLASS__,$runlang);
        $this->isAjax = FALSE;
        //Lignes de debug
        //        $this->presentVarIfDebug(__FUNCTION__, __LINE__, $_SESSION["ud_carrier"]["itr.articles"],'v_d');
        //        $this->endExecutionIfDebug(__FUNCTION__,__LINE__);
        
        /*
         * DEV, TEST, DEBUG
         */
        //*
        $this->_API_FB_AppName = "trenqr.local.dev";
        $this->_API_FB_AppID = "1549433922025648";
        $this->_API_FB_AppSecret = "352a7cfb9abdfc1f411b8ad33e67d9b6";
        $this->_API_FB_LoginUrl = "http://trenqr.local.dev/dev.trenqr.com/signup";
        //*/
        
        /*
         * VERSION PROD
         */
        /*
        $this->_API_FB_AppName = "trenqr";
        $this->_API_FB_AppID = "1492102444425463";
        $this->_API_FB_AppSecret = "e9d84ef3f992b5cc93e57544e5123521";
        $this->_API_FB_LoginUrl = "https://trenqr.com/signup";
        //*/
        
        /*
        unset($_SESSION);
        session_destroy();
        exit();
        //*/
        
//        var_dump($_SERVER["REMOTE_ADDR"],$_SERVER["SERVER_NAME"]);
//        exit();
    }
    
    /**************** START SPECFIC METHODES ********************/
    
    private function entercz_preform () {
        
    }
    
    private function entercz_api_fb () {
        
        $fb = new Facebook([
            'app_id'        => $this->_API_FB_AppID,
            'app_secret'    => $this->_API_FB_AppSecret,
            'default_graph_version' => 'v2.2',
        ]);
        
        $helper = $fb->getRedirectLoginHelper();
        
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $url_query = ( $url && parse_url($url) && parse_url($url)["query"] ) ? parse_url($url)["query"] : [];
        if ( $url_query ) {
            parse_str($url_query, $url_query_ar);
        }
        
        if ( $_SESSION && isset($_SESSION["apps"]) && $_SESSION["apps"]["ins_wapi_fb"] && $_SESSION["apps"]["ins_wapi_fb"]["fb_token"] ) {
//            echo "Ici cela veut dire : (1) Utilisateur est connecté depuis FB (2) Il a déjà accepté de nous filer les données\n";
            
            $this->KDOut["ENTERCZ"] = "ENTERCZ_ACTIVE_FB_SSN";
            
            /*
            $fb_token = $_SESSION["apps"]["ins_wapi_fb"]["fb_token"];
                    
            // NOTE : Décommenter pour : DEV, TEST, DEBUG
            $fb_user_o_datas = $this->entercz_api_fb_request_and_format_user_datas($fb,$fb_token);
            if ( $this->return_is_error_volatile(__FUNCTION__, __LINE__, $fb_user_o_datas) ) {
                $this->KDOut["INS_WAPI_FB"]["USER_DATAS"] = [];
                unset($_SESSION["apps"]["ins_wapi_fb"]["fb_token"]);
            } else {
                $this->KDOut["INS_WAPI_FB"]["USER_DATAS"] = $fb_user_o_datas;
            }
            
            var_dump(__FIlE__,__FUNCTION__,__LINE__,["GRAPH USER RETURNED => ",$fb_user_o_datas]);
            //*/
            
            $this->KDOut["INS_WAPI_FB"]["USER_DATAS"] = [];
            unset($_SESSION["apps"]["ins_wapi_fb"]["fb_token"]);
                
            $permissions = ["email","user_birthday"]; // Optional permissions
            $loginUrl = $helper->getLoginUrl($this->_API_FB_LoginUrl, $permissions);
        
            $this->KDOut["INS_WAPI_FB"]["LoginUrl"] = $loginUrl;
            
        } else if ( $_SERVER["REQUEST_URI"] && $url_query && $url_query_ar && $url_query_ar["code"] && $url_query_ar["state"] ) {
            
            $_GET["code"] = $url_query_ar["code"];
            $_GET["state"] = $url_query_ar["state"];
            
            $continue = FALSE;
            set_error_handler('exceptions_error_handler');
            try {
                $accessToken = $helper->getAccessToken();
                restore_error_handler();
                
                $continue = TRUE;
            } catch(Exception $e) {
                /*
                 * ETAPE : 
                 *      Il peut s'agir du cas où l'utilisateur à lancer une URL avec un "truc" (je ne sais pas ce que sait) expiré.
                 *      Il faut donc "relancer" la page 
                 */
                $permissions = ["email","user_birthday"]; // Optional permissions
                $loginUrl = $helper->getLoginUrl($this->_API_FB_LoginUrl, $permissions);
                $this->KDOut["INS_WAPI_FB"]["LoginUrl"] = $loginUrl;
                
                $this->KDOut["ENTERCZ"] = "ENTERCZ_DIRECT";
                
                // When Graph returns an error
//                echo 'Graph returned an error: ' . $e->getMessage(); 
//                exit;
            }

            if ( $continue === TRUE ) {
                if (! isset($accessToken) ) {
                    if ( $helper->getError() ) {
                        //TODO : Lancer une erreur de type non autorisé
                        header('HTTP/1.0 401 Unauthorized');
                    } else {
                        //TODO : 
                        header('HTTP/1.0 400 Bad Request');
                        echo 'Bad request';
                    }
                    exit;
                }
            
                $fb_token = $accessToken->getValue();

                $_SESSION["apps"]["ins_wapi_fb"] = [
                    "fb_token" => $fb_token,
                ];

                $fb_user_o_datas = $this->entercz_api_fb_request_and_format_user_datas($fb,$fb_token);

                $this->KDOut["INS_WAPI_FB"]["USER_DATAS"] = $fb_user_o_datas;
            
//            var_dump(__FIlE__,__FUNCTION__,__LINE__,["GRAPH USER RETURNED => ",$fb_user_o_datas]);
            }
            
            
        } else {
            
            $permissions = ["email","user_birthday"]; // Optional permissions
            $loginUrl = $helper->getLoginUrl($this->_API_FB_LoginUrl, $permissions);
        
            $this->KDOut["INS_WAPI_FB"]["LoginUrl"] = $loginUrl;
            
//            var_dump(__FILE__,__FUNCTION__,__FILE__,["LOGIN_URL => ",$loginUrl]);
//            exit();
        }
    }
    
    
    private function entercz_api_fb_request_and_format_user_datas (Facebook $fb, $fb_token) {
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        $_FB_RQ_REPONSE = $this->entercz_api_fb_request_user_datas($fb,$fb_token);
        if ( empty($_FB_RQ_REPONSE) ) {
            return "__ERR_VOL_FAILED";
        }
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        $FG_GRAPH_USER = $_FB_RQ_REPONSE->getGraphUser();
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        $_FB_USER_O_DATAS = $this->entercz_api_fb_format_raw_user_datas($FG_GRAPH_USER);
        
        $this->perfAtPoint($this->tm_start,__LINE__,TRUE);
        
        return $_FB_USER_O_DATAS;
    }
    
    private function entercz_api_fb_request_user_datas (Facebook $fb, $fb_token) {
        
        set_error_handler('exceptions_error_handler');
        try {
            // Returns a `Facebook\FacebookResponse` object
            $_FB_RQ_REPONSE = $fb->get('/me?fields=id,name,is_verified,birthday,age_range,gender,hometown,email,verified,languages', $fb_token);
            restore_error_handler();
        } catch(Exception $e) {
          echo 'API Facebook returned an error: ' . $e->getMessage();
        }
        
        return $_FB_RQ_REPONSE;
    }
    
    
    private function entercz_api_fb_format_raw_user_datas (GraphUser $FG_GRAPH_USER) {
        
        $_FB_USER_O_DATAS = [
            "user_id"               => $FG_GRAPH_USER->getId(),
            "user_name"             => $FG_GRAPH_USER->getName(),
            "user_iceleb"           => $FG_GRAPH_USER->getField("is_verified"),
            "user_bdate"            => ( $FG_GRAPH_USER->getBirthday() ) ? $FG_GRAPH_USER->getBirthday()->format('Y-m-d') : "",
            "user_range"            => $FG_GRAPH_USER->getField("age_range")->all()["min"],
            "user_gender"           => ( $FG_GRAPH_USER->getGender() ) ? $FG_GRAPH_USER->getGender() : "",
            "user_hometown"         => ( $FG_GRAPH_USER->getHometown() ) ? $FG_GRAPH_USER->getHometown() : [],
            "user_email"            => $FG_GRAPH_USER->getField("email"),
            "user_email_verified"   => $FG_GRAPH_USER->getField("verified"),
            "user_languages"        => ( $FG_GRAPH_USER->getField("languages") ) ? $FG_GRAPH_USER->getField("languages") : "",
        ];
        
        return $_FB_USER_O_DATAS;
    }
    
    

    /****************** END SPECFIC METHODES ********************/
        
    public function prepare_datas_in() {
        
        @session_start();
        
        $this->perfEna = FALSE;
        $this->tm_start = round(microtime(true)*1000);
        
        /*
         * [NOTE 26-05-16]
         *  Il existe 3 manières différentes d'acceder à cette page :
         *      ENTERCZ_DIRECT      : L'Utilisateur arrive directement sur la PAGE_INS ou en cliquant sur un bouton/lien
         *      ENTERCZ_PREFORM     : L'Utilisateur arrive sur PAGE_INS après avoir remplie le Formulaire de la page d'accueil
         *      ENTERCZ_INSAPI_FB   : L'Utilisateur revient sur PAGE_INS après avoir lancer la procédure d'inscription via l'API Facebook depuis PAGE_HOME ou PAGE_INS. 
         * BONUS :
         *      ENTERCZ_DIRECT : L'utilisateur a une SESSION active sur FACEBOOK mais n'est pas connecté sur TRENQR.
         *      On a donc accès à ces données (du fait de l'appel permanent via l'API de Facebook)
         * 
         *  Chacune de ces amnières a sa particularité. En effet, certaines comme ENTERCZ_PREFORM et ENTERCZ_INSAPI_FB sont porteuses de données.
         *  Il faut donc le prendre en compte au chargement de la page. 
         */
        
//        var_dump($_SERVER["REQUEST_URI"],$_SERVER["HTTP_REFERER"]);
//        exit();
        
        /*
         * ETAPE :
         *      Si des données POST sont disponibles, on les récupère.
         *      Cette situation fait référence au cas ENTERCZ_PREFORM.
         */
        $this->KDIn["fn"] = ( key_exists("fullname", $_POST) && isset($_POST["fullname"])  ) ? $_POST["fullname"] : "";
        $this->KDIn["psd"] = ( key_exists("nickname", $_POST) && isset($_POST["nickname"]) ) ? $_POST["nickname"] : "";
        $this->KDIn["email"] = ( key_exists("email", $_POST) && isset($_POST["email"]) ) ? $_POST["email"] : "";
        $this->KDIn["hp"] = ( key_exists("passwd", $_POST) && isset($_POST["passwd"]) ) ? $_POST["passwd"] : "";
        
        /*
         * ETAPE :
         *      On effectue des opérations en amont pour vérifier si on est dans le de INS_WITH_FB_API 
         */
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $url_query = ( $url && parse_url($url) && parse_url($url)["query"] ) ? parse_url($url)["query"] : [];
        if ( $url_query ) {
            parse_str($url_query, $url_query_ar);
        }
        
//        var_dump($_SESSION);
//        exit();
        
        /*
         * ETAPE : 
         *      On détermine le cas dans lequel nous nous trouvons via les données dont nous disposons.
         */
        if ( !empty($this->KDIn["fn"]) && !empty($this->KDIn["psd"]) && !empty($this->KDIn["email"]) && !empty($this->KDIn["hp"]) ) {
             $this->KDOut["ENTERCZ"] = "ENTERCZ_PREFORM";
        } else if ( $_SERVER["REQUEST_URI"] && $url_query && $url_query_ar && $url_query_ar["code"] && $url_query_ar["state"] ) {
            $this->KDOut["ENTERCZ"] = "ENTERCZ_INSAPI_FB";
        } else  {
            $this->KDOut["ENTERCZ"] = "ENTERCZ_DIRECT";
        }
    }

    public function on_process_in() {
        
        switch ($this->KDOut["ENTERCZ"]) {
            case "ENTERCZ_DIRECT" :
            case "ENTERCZ_INSAPI_FB" :
                    $this->entercz_api_fb();
                break;
            case "ENTERCZ_PREFORM" :
                    $this->entercz_preform();
                break;
            
            default :
                    //TODO : Lancer une erreur !
                exit();
        }
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$this->KDOut["INS_WAPI_FB"]);
//        exit();
    }

    public function on_process_out() {
        /*
        $_SESSION["ud_carrier"]["fn"] = $this->KDIn["fn"];
        $_SESSION["ud_carrier"]["psd"] = $this->KDIn["psd"];
        $_SESSION["ud_carrier"]["email"] = $this->KDIn["email"];
        //hp = HarryPotter (=Password)
        $_SESSION["ud_carrier"]["hp"] = $this->KDIn["hp"];
        //*/
        //*
        $_SESSION["ud_carrier"]["fn"] = htmlentities($this->KDIn["fn"]);
        $_SESSION["ud_carrier"]["psd"] = htmlentities($this->KDIn["psd"]);
        $_SESSION["ud_carrier"]["email"] = htmlentities($this->KDIn["email"]);
        //hp = HarryPotter (=Password)
        $_SESSION["ud_carrier"]["hp"] = htmlentities($this->KDIn["hp"]);
        //*/
        
        /*
         * [DEPUIS 22-07-16]
         *      La LANG de la SESSION en cours
         */
        $_SESSION["ud_carrier"]["runlang"] = $this->runlang;
        
        if ( !empty($this->KDIn["fn"]) && !empty($this->KDIn["psd"]) && !empty($this->KDIn["email"]) && !empty($this->KDIn["hp"]) ) {
            $INS_PREFORM_DATAS = [
                "user_name"     => $this->KDIn["fn"],
                "user_pseudo"   => $this->KDIn["psd"],
                "user_email"    => $this->KDIn["email"],
                "user_pass"     => $this->KDIn["hp"],
            ];
            
            $_SESSION["ud_carrier"]["INS_PREFORM"] = base64_encode(serialize($INS_PREFORM_DATAS));
        }
        
        $_SESSION["ud_carrier"]["ENTERCZ"] = $this->KDOut["ENTERCZ"];
        
        
        if ( key_exists("INS_WAPI_FB", $this->KDOut) && $this->KDOut["INS_WAPI_FB"] ) {
            $_SESSION["ud_carrier"]["INS_WAPI_FB"] = base64_encode(serialize($this->KDOut["INS_WAPI_FB"]));
        }
        
        $_SESSION["ud_carrier"]["pagid"] = "ins";
        $_SESSION["ud_carrier"]["pgakxver"] = "wlc";
        $_SESSION["ud_carrier"]["sector"] = $this->KDOut["ENTERCZ"];
        
//        var_dump(__FILE__,__FUNCTION__,__LINE__,$_SESSION["ud_carrier"]["INS_WAPI_FB"]);
//        exit();
    }
    
    protected function prepare_params_in_if_exist() {
        
    }

    
    
    /*************************************************************************************************/
    /************************************* GETTERS and SETTERS ***************************************/
    
}



?>