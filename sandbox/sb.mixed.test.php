<?php
//Do not remove the following line till the class declaration.
require_once "sb.env.php";
require_once(RACINE."/common/modules/phpQuery-onefile.php");
require_once(RACINE."/common/modules/simple_html_dom.php");

use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Format\Video\X264;
use FFMpeg\Format\Video\WMV;
use FFMpeg\Format\Video\WebM;
use FFMpeg\FFProbe;

class SANDBOX extends MOTHER {
    
    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
        
    }

    //Create a new [standard or static] function here and launch the test.
    
    /**
     * <p>
     * Use this function to test your instructions.
     * Remove the old set of instructions and place yours.
     * </p>
     * <p>
     * <b><i>Caution :</i></b> You can't use the standard error handler with this static function.
     * </p>
     * @author Lou Carther <lou.carther@deuslynn-entreprise.com>SANDBOX
     * @copyright (c) 2013, DEUSLYNN ENTREPRISE
     */
    static public function test_mixed() {
        //Put your code here
        
        $st = "SELECT city_name, city_id AS city_code, city_pop, admin1_code, admin2_code, ctr_code, ctr_name, longitude, latitude 
        FROM Partner_GN_Cities_15000, Countries
        WHERE country_code = Countries.ctr_code
        AND country_code = :ctr_code
        AND city_name LIKE :city_name;";
        echo "AVANT = ".$st;
        $r = "\${1}5000";
        $reg= "/(Partner_GN_Cities_)[\d]{4,5}/";
        
        echo "<br/>APRES = ".preg_replace($reg, $r, $st);
    }

    
//    static public function test_queries() {
    public function test_queries() {
        //Put your code here
        
        /*
        $QO = new QUERY();
        $qbody = "SELECT * ";
        $qbody .= "FROM Server_Storage ";
        $qbody .= "WHERE srvid = :srvid; ";
        $qdbname = "tqr_product_vb1";
        $qtype = "get";
        $qparams_in = array(":srvid" => "2");  
        $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in); 
        //*/
        /*
        $QO = new QUERY();
        $qbody = "INSERT INTO Proddb_Pictures (is_default,pdpic_fn,pdpic_string,pdpic_height,pdpic_width,pdpic_size,pdpic_type,pdpic_realpath,srvid,pdpic_date_tstamp) VALUES ";
        $qbody .= "(:is_default,:pdpic_fn,:pdpic_string,:pdpic_height,:pdpic_width,:pdpic_size,:pdpic_type,:pdpic_realpath,:srvid,:pdpic_date_tstamp);";
        $qdbname = "gudb_736c70_main_v01";
        $qtype = "set";
//        $qparams_in = array(":pflid" => "",":accpseudo" => "",":acclang" => "",":acc_authemail" => "",":acc_authpwd" => "",":creadate" => "");  
        $qparams_in = array(":is_default" => "",":pdpic_fn" => "",":pdpic_string" => "",":pdpic_height" => "",":pdpic_width" => "",":pdpic_size" => "",":pdpic_type" => "",":pdpic_realpath" => "",":srvid" => "",":pdpic_date_tstamp" => "");  
        $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in); 
        //*/
        /*
        $QO = new QUERY();
        $qbody = "UPDATE ACCOUNTS ";
        $qbody .= "SET acc_is_banned = :val ";
        $qbody .= "WHERE accid = :accid;";
        $qdbname = "gudb_736c70_main_v01";
        $qtype = "update";
        $qparams_in = array(":val" => "", ":accid" => "");  
        $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in); 
        //*/
        /*
        $QO = new QUERY();
        $qbody = "DELETE from ACCOUNTS ";
        $qbody .= "WHERE accid = :accid;";
        $qdbname = "gudb_736c70_main_v01";
        $qtype = "delete";
        $qparams_in = array(":accid" => "");  
        $QO->build_volatile($qbody, $qdbname, $qtype, $qparams_in); 
        //*/
        
        /*
        $QO = new QUERY("qryl4artn17");
        
        $time = round(microtime(true)*1000);
//        $qparams_in_values = array(":accid" => "4", ":todelete" => FALSE, ":todel_event_date" => null, ":cancel_todel_event_date" => "2013-10-29");  
//        $qparams_in = array(":is_default" => "0",":pdpic_fn" => "toto",":pdpic_string" => "data:ddwfdbdbf",":pdpic_height" => "100",":pdpic_width" => "100",":pdpic_size" => "545654",":pdpic_type" => "image/jpg",":pdpic_realpath" => "vdfdfbdfb",":srvid" => "2",":pdpic_date_tstamp" => "$time");  
        $qparams_in = array(":artid" => 192);  
//        $datas = $QO->execute($qparams_in_values);
        $datas = $QO->execute($qparams_in);
        //*/
        /*
        $now = round(microtime(TRUE)*1000);
        $QO = new QUERY("qryl4ltc_sslogn1");
        $params = array( 
            ":temp_eid"     => $now,
            ":refu"         => 102,
            ":trid"         => 20, 
            ":wmp_ssid"     => 34341126,
            ":ssid"         => "session_id",
            ":locip"        => 2130706433,
            ":loc_cn"       => NULL,
            ":uagent"       => NULL,
            ":curl"         => NULL,
            ":start_date"   => date("Y-m-d G:i:s",($now/1000)),
            ":start_tstamp" => $now,
        );
        $id = $QO->execute($params);
        var_dump($id);
        //*/
        
        $QO = new QUERY("qryl4ltc_sslogn10");
        $params = array( 
            ":uid"     => 106, 
            ":trid"    => 20, 
        );
        $datas = $QO->execute($params); 
        var_dump($datas);
        
        
    }
    
    private $artid;
    
    public function sample2 ($args) {
        $ART = new ARTICLE();
        $r = $ART->on_create_entity($args);
        
        $this->presentVarIfDebug(__FUNCTION__,__LINE__,$r,'v_d');
        
        $this->presentVarIfDebug(__FUNCTION__,__LINE__,$ART->getAll_properties(),'v_d');
    }
    
    public static function removeEmoji($text) {

        $clean_text = "";

        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, '', $text);

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, '', $clean_text);

        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, '', $clean_text);

        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, '', $clean_text);

        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);

        return $clean_text;
    }
    
    public function repalce_emoji_in($text) {

        $clean_text = "";

        // Match Emoticons
        $regexEmoticons = '/([\x{1F600}-\x{1F64F}])/u';
        $clean_text = preg_replace_callback($regexEmoticons, function ($matches) {
            $char = current($matches);
            $utf = iconv('UTF-8', 'UCS-4', $char);
            return sprintf("&#x%s;", ltrim(strtoupper(bin2hex($utf)),"0"));
        }, $text);

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/([\x{1F300}-\x{1F5FF}])/u';
        $clean_text = preg_replace_callback($regexSymbols, function ($matches) {
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
    
    public function _uniord($c) {
        if (ord($c{0}) >=0 && ord($c{0}) <= 127)
            return ord($c{0});
        if (ord($c{0}) >= 192 && ord($c{0}) <= 223)
            return (ord($c{0})-192)*64 + (ord($c{1})-128);
        if (ord($c{0}) >= 224 && ord($c{0}) <= 239)
            return (ord($c{0})-224)*4096 + (ord($c{1})-128)*64 + (ord($c{2})-128);
        if (ord($c{0}) >= 240 && ord($c{0}) <= 247)
            return (ord($c{0})-240)*262144 + (ord($c{1})-128)*4096 + (ord($c{2})-128)*64 + (ord($c{3})-128);
        if (ord($c{0}) >= 248 && ord($c{0}) <= 251)
            return (ord($c{0})-248)*16777216 + (ord($c{1})-128)*262144 + (ord($c{2})-128)*4096 + (ord($c{3})-128)*64 + (ord($c{4})-128);
        if (ord($c{0}) >= 252 && ord($c{0}) <= 253)
            return (ord($c{0})-252)*1073741824 + (ord($c{1})-128)*16777216 + (ord($c{2})-128)*262144 + (ord($c{3})-128)*4096 + (ord($c{4})-128)*64 + (ord($c{5})-128);
        if (ord($c{0}) >= 254 && ord($c{0}) <= 255)    //  error
            return FALSE;
        return 0;
    }
    
    public function _unichr($o) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding('&#'.intval($o).';', 'UTF-8', 'HTML-ENTITIES');
        } else {
            return chr(intval($o));
        }
    }

    
function wp_encode_emoji( $content ) {
  if ( function_exists( 'mb_convert_encoding' ) ) {
    $regex = '/(
		     \x23\xE2\x83\xA3               # Digits
		     [\x30-\x39]\xE2\x83\xA3
		   | \xF0\x9F[\x85-\x88][\xA6-\xBF] # Enclosed characters
		   | \xF0\x9F[\x8C-\x97][\x80-\xBF] # Misc
		   | \xF0\x9F\x98[\x80-\xBF]        # Smilies
		   | \xF0\x9F\x99[\x80-\x8F]
		   | \xF0\x9F\x9A[\x80-\xBF]        # Transport and map symbols
		)/x';

    $matches = array();
    if ( preg_match_all( $regex, $content, $matches ) ) {
      if ( ! empty( $matches[1] ) ) {
        foreach ( $matches[1] as $emoji ) {
          /*
            * UTF-32's hex encoding is the same as HTML's hex encoding.
            * So, by converting the emoji from UTF-8 to UTF-32, we magically
            * get the correct hex encoding.
            */
          $unpacked = unpack( 'H*', mb_convert_encoding( $emoji, 'UTF-32', 'UTF-8' ) );
          if ( isset( $unpacked[1] ) ) {
            $entity = '&#x' . ltrim( $unpacked[1], '0' ) . ';';
            $content = str_replace( $emoji, $entity, $content );
          }
        }
      }
    }
  }

  return $content;
}

    
    public function sample3 () {
        
        /*
         * [DEPUIS 29-05-16]
         *      Permet d'utiliser les methodes telles que setcookies() qui doivent etre appeléés avant tout output.
         *      Aussi, on bloque toute temporisation de sortie.
         *      Je ne sais pas à cette date quelles pourraient être les conséquences pour les autres processus.
         */
       ob_start();
       
       
        /*
        echo "<div style='margin-top: 50px; box-sizing: border-box; width: 370px; height: 34px; background: rgba(206, 59, 59,.7); color: white; line-height: 34px; text-shadow: 0px 1px 1px rgba(0, 0, 0, 0.5); text-align: center; font-family: \"Liberation sans\"; font-size: 15px; font-weight: bold; padding: 0px 8px;' >Un texte décoratif qui doit matcher</div>";
//        exit();
        ob_clean();
        header('Content-Type: image/png');
        $im = @imagecreatetruecolor(740, 68)
      or die('Impossible de crée un flux d\'image GD');
        $bimg = imagecreatetruecolor(1000, 1000);
        
        
//        $red = imagecolorallocate($im, 206, 59, 59);
        $k = 70+10;
        $alpha = (((127-$k)/127)*100);
        $red = imagecolorallocatealpha($im, 206, 59, 59, $alpha);
        $black = imagecolorallocate($im, 0, 0, 0);
        $white = imagecolorallocate($im, 255, 255, 255);
        
        imagefilledrectangle($im, 0, 0, 740, 68, $red);
        
        $black2 = imagecolorallocate($bimg, 0, 0, 0);
        imagefilledrectangle($bimg, 0, 0, 1000, 1000, $black2);
        
//        imagecolortransparent($im, $red);
//        
        $text = 'Un texte décoratif qui doit matcher';
        $font = 'LiberationSans-Bold.ttf';
//        $font = 'LiberationSans-Regular.ttf';
        
        $bbox = imagettfbbox(23,0,$font,$text);
        
        $bbox_h = $bbox[7] - $bbox[1];
        $bbox_w = $bbox[2] - $bbox[0];
        
//        $tx_x = ( ( imagesx($im) - $bbox_w ) / 2 ) - 10;
//        $tx_y = ( ( imagesy($im) - $bbox_h ) / 2 ) - 3;
        $tx_x = ( ( imagesx($im) - $bbox_w ) / 2 ) - 0;
        $tx_y = ( ( imagesy($im) - $bbox_h ) / 2 ) - 4;
        
        $txt_shad_x = $tx_x+1;
        $txt_shad_y = $tx_y+1;
//        
        //TEXT-SHADOW
//        imagettftext($im, 11, 0, $txt_shad_x, $txt_shad_y, $black, $font, $text);
        imagettftext($im, 23, 0, $txt_shad_x, $txt_shad_y, $black, $font, $text);
        //TEXT
//        imagettftext($im, 11, 0, $tx_x, $tx_y, $white, $font, $text);
        imagettftext($im, 23, 0, $tx_x, $tx_y, $white, $font, $text);
        
        $thumb = imagecreatetruecolor(370, 34);
        $ratio = 370 / 740;
        $w = 740 * $ratio;
        $h = 68 * $ratio;
        imagecopyresized($thumb, $im, 0, 0, 0, 0, $w, $h, 740, 68);
        
        imagecopymerge($bimg, $im, 0, 0, 0, 0, 1000, 1000, 100);
        
        imagepng($bimg);
//        imagepng($bimg);
//        imagejpeg($thumb,NULL,100);
//        imagejpeg($thumb);
        
        imagedestroy($im);
        imagedestroy($bimg);
        imagedestroy($thumb);
        
        exit();
        //*/
        
        /*
        $TH = new TEXTHANDLER();
//        $s = "1 a * - 😃 \xF0\x9F\x98\x81 \u1F601 U+2764U+FE0F + 56 papa 😃";
//        $s = "Bonjour tout le monde, je m'appelle ET 😜, Et vous à & é @ à è  ?";
//        $s = mb_convert_encoding($s, 'UTF-8', 'UTF-16BE');
//        $s = "😃 \xF0\x9F\x98\x81 \\x1F600";
//        $s = "�������🍝������� &#x1F603;";
        $s = "🤰 😃 🤣 🤤 🇨🇬 💯 💪 💪🏾 �💪� ";
//        $s = (new TEXTHANDLER())->secure_text($s);
//        $s = iconv("UTF-8", "UCS-4", $s);
//        $s = html_entity_decode($s);
//        $s = "1 a * - 😃 + 56 papa";
//        $s = html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", "1 a * - 😃 \xF0\x9F\x98\x81 \u1F601 U+2764U+FE0F + 56 papa"), ENT_NOQUOTES, 'UTF-8');
//        $s = mb_convert_encoding($s,"UTF-8","byte4le");
//        echo "ICI => ".$s;
//        echo "OUT => ".mb_convert_encoding($s, 'UTF-16BE', 'UTF-8');
        
        $clean = $TH->replace_emojis_in($s);
        
        var_dump("ONLY CLEAN TEXT => ",$clean);
        
        var_dump("MULTIPLE VALUES => ",$s,$this->_uniord($s),$this->_unichr($s),$TH->strlen_utf8($s), $clean, html_entity_decode($clean),$TH->strlen_utf8($clean),$TH->strlen_utf8(pack($clean)));
//        var_dump($clean);
//        var_dump("RAW => ".$s."\n","CLEANED IN => ".$clean."\n","CLEANED OUT 1 => ".$this->repalce_emoji_out($clean)."\n","CLEANED OUT 2 => ".mb_convert_encoding($clean, 'UTF-16BE', 'UTF-8')."\n",mb_detect_encoding($clean),  html_entity_decode(mb_convert_encoding($clean, 'UTF-16BE', 'UTF-8')));
        exit();
        $c0s = ord($s);
        $c1s = json_decode('"'.$s.'"');
        $c2s = mb_convert_encoding($s, 'UTF-8', 'HTML-ENTITIES');
        $c3s = mb_convert_encoding("\x10\x00", 'UTF-8', 'UTF-16BE');
        var_dump($s,$c0s,$c1s,$c2s,$c3s);
        exit();
        
//        $this->_TST_MSG_RGX = "/^(?=.*(?:[a-z]|U\+([0-9A-F]{4})))[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{1,1000}$/i";
        $this->_TST_MSG_RGX = "/^(?=.*(?:[a-z]))[\s\SÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]{1,1000}$/i";
        if ( is_string($s) && !preg_match($this->_TST_MSG_RGX,$s) ) {
            echo "__ERR_VOL_MSG_MSM";
        }
        
        $TH = new TEXTHANDLER();
        
        //On extrait les hashstags si le texte en comporte
        $kws = $TH->extract_prod_keywords($s);
        //On extrait les usertags si le texte en comporte
        $usertags = $TH->extract_tqr_usertags($s);
        
        $ns = $TH->secure_text($s);
//        $ns = mb_convert_encoding($ns,"byte4le","UTF-8");
        var_dump(__LINE__, $ns, json_decode($ns), $s,mb_strlen($s,"UTF8"),$kws,$usertags,$ns);
        exit();
        //*/
        
        /*
//        $file_vid = RACINE."/sandbox/vid3.mp4";
        $file_vid = RACINE."/sandbox/heavy.mp4";
        $video_str = file_get_contents($file_vid);
        
        $pa = tempnam(sys_get_temp_dir(), 'vid');
        
        $tmpvid = file_put_contents($pa,$video_str);
//        fwrite($tmpvid, $video_str);
//        var_dump($pa);
//        var_dump($tmpvid);
//        exit();
        fseek($tmpvid, 0);
        
        $ffprobe = FFProbe::create();
        var_dump($ffprobe
            ->format($pa));
        print_r($ffprobe
            ->streams($pa)
            ->videos()                      // filters video streams
            ->first()                       // returns the first video stream
//            ->get('codec_name'));
            ->getDimensions()->getWidth());
//            ->get('duration');    
        
        
        exit();
        //*/
        /*
        $ffmpeg = FFMpeg::create();
//        $video = $ffmpeg->open(RACINE."/sandbox/vid3.mp4");
//        $video = $ffmpeg->open(RACINE."/sandbox/vid6.mp4");
//        $video = $ffmpeg->open(RACINE."/sandbox/vid8.mp4");
        $video = $ffmpeg->open(RACINE."/sandbox/vid30.mp4");
//        $video = $ffmpeg->open(RACINE."/sandbox/heavy.mp4");
//        $video = $ffmpeg->open(RACINE."/sandbox/cook.mp4");
        $video
            ->filters()
//            ->resize(new Dimension(213,120))
//               480 360 240 144
//            ->resize(new Dimension(480,270))
//            ->resize(new Dimension(480,480))
//            ->resize(new Dimension(720,405))
            ->resize(new Dimension(round(720/2)*2,round(405/2)*2))
//            ->resize(new Dimension(round(405/2)*2,round(720/2)*2))
//            ->resize(new Dimension(360,202))
//            ->framerate(new FrameRate(30),250)
            ->synchronize();
//        $video
//            ->frame(TimeCode::fromSeconds(2))
//            ->save('frame.jpg');
//        exit();
        
        $format = new X264('libmp3lame');
//        $format->on('progress', function ($video, $format, $percentage) {
//            echo "$percentage % transcoded";
//        });
        $format
            ->setKiloBitrate(720)
//            ->setAudioChannels(1)
//            ->setAudioKiloBitrate(20)
            ;
        $video
            ->save($format, 'export-x264.mp4');
//            ->save(new X264(), 'export-x264.mp4');
//            ->save(new WMV(), 'export-wmv.wmv');
//            ->save(new WebM(), 'export-webm.webm');
        
        $video
            ->frame(TimeCode::fromSeconds(2))
            ->save('frame2.jpg');
        
        exit();
        //*/
        
        /*
        $f = function($str) {
//            $str = preg_quote($str);
//            $str = urldecode($str);
//            return urlencode(stripcslashes(html_entity_decode(preg_replace("#u([0-9a-f]{3,4})#i","&#x\\1;",urldecode($str)),null,'UTF-8')));
            return htmlentities(stripcslashes(html_entity_decode(preg_replace("#u([0-9a-f]{3,4})#i","&#x\\1;",urldecode($str)),null,'UTF-8')));
//            return stripcslashes(html_entity_decode(preg_replace("#u([0-9a-f]{3,4})#i","&#x\\1;",urldecode($str)),null,'UTF-8'));
        };
        $f2 = function($str) {
            return preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", $str);
//            return html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", $str), ENT_NOQUOTES, 'UTF-8');
//            return html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", $str), ENT_NOQUOTES, 'UTF-8');
        };
        
        $v = "http:\/\/dm1412.trenqr.com\/tendance\/7cfdmom\/decouvrez-la-vie-de-marie-de-a-a-z&as=contributor";
//        $v = "http://dm1412.trenqr.com/tendance/7cfdmom/d%C3%A9couvrez-la-vie-de-marie-de-a-%C3%A0-z&as=contributor";
//        var_dump($v,filter_var($v, FILTER_VALIDATE_URL));
//        var_dump(preg_match_all("/u([0-9a-f]{3,4})/i",$v));
        var_dump($f($v), filter_var($f($v), FILTER_VALIDATE_URL), parse_url($v));
//        var_dump(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", $v));
//        var_dump(utf8_decode($v),filter_var(utf8_decode($v), FILTER_VALIDATE_URL));
        exit();
         
         //*/
        /*
//        $tle = "/-++-//--+++++aaaaaaaaaa+++++++/---";
//        $tle = "/-++-//--+++++aa+++++++/---";
//        $tle = 'ààààààààààààààààààà';
//        $tle = "abcdefghijklmnopqrst";
//        $tle = "a                  a";
//        $tle = "0123456789 abcdefghj";
//        $tle = "aaaaaaaaaa0123456789";
//        $tle = "Découvrez la vie de Lyly de A à Z";
//        $tle = "Description : Entrez dans ma vie, mes voyages, mes plaisirs, mes loisirs, mes tracas... :)";
//        $tle = "<span style='color: red;'>go red</span> <script>alert(\"Injection JS\");</script>éèçà@ÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż";
        $tle = "@55 @aa #TheBoredomKiller : #ArticleIML #test #NoUsertag #injection #sécurité <span style='color: red'>Injection HTML</span> <script>alert(\"Injection réussie\");</script> ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż";
        $rgx0 = "#(?:(?=.*[a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]*).(?![\s]{5,})){20,}#iu";
//        $rgx0 = "#^(?=.*[^\s][a-zÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]).{20,}$#i";
        $rgx1 = "#(?:(?=.*[a-z]).+[a-zA-Z\dÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]*){20,}#i";
        $rgx2 = "#(?:(?=.*[a-z]).*[a-zA-Z\dÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]*){20,}#";
        $rgx3 = "/(?:(?=.*[a-z]).*[a-zA-Z\dÀÁÂÃÄÅÆàáâãäåæắạẶặẰằẢảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøÑñⱣᵽÙÚÛÜùúûüựÿÝýŠŽžż]*){20,}/i";
        $rgx = "/(?:ontrenqr|)(?:&v=1m30)?/i";
        $TXT = new TEXTHANDLER();
        $ln = $TXT->strlen_ship_tagsmarks($tle,['#','@']);
        var_dump($tle,mb_strlen($tle),strlen(utf8_decode($tle)),$ln);
//        var_dump(preg_match($rgx0, $tle));
//        var_dump(preg_match($rgx1, $tle));
//        var_dump(preg_match($rgx2, $tle));
//        var_dump(preg_match($rgx3, $tle));
//        var_dump(preg_match($rgx, "ontrenqr"));
        exit();
        //*/
        /*
        $wbst = "trenqr.com";
        if (! preg_match("#^https?://#", $wbst) ) {
            $wbst = "http://".$wbst;
        }
        $ptrn = '_^(?:(?:https?)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS';
        var_dump(__LINE__,$wbst, filter_var($wbst,FILTER_VALIDATE_URL), parse_url($wbst),preg_match($ptrn,$wbst));
        exit();
        //*/
        
        /*
        var_dump(htmlentities('.@,<=;é'));
        var_dump(htmlspecialchars('.@,<=;é'));
        var_dump(htmlspecialchars_decode("Trenqr Bugzy &lt;treçénqr.bugzy.sdr@trenqr.com&gt;"));
        var_dump(urlencode('.@,<=;'));
        var_dump(addslashes('.@,<=;'));
        var_dump(rawurlencode('.@,<=;'));
        var_dump(quoted_printable_encode("dssssssssssssssssssss=sssssssssssssssssssssssssssssssssssssssssssssssssssssssssasssssssssssssssss@sssssss"));
        exit();
        //*/
//        var_dump(urldecode("http://dm1412.trenqr.com/tendance/2da68o3/lyon-f%C3%AAte-des-lumi%C3%A8res-2014&as=manager"));
//        $TQACC = new TQR_ACCOUNT();
//        $ueid = $TQACC->tqr_create_ueid (mktime(0, 0, 0, 10, 31, 2002), 'm', "cg", 1000000000);
//        var_dump($ueid);
//        var_dump($TQACC->tqr_read_ueid (mktime(0, 0, 0, 10, 31, 2002), $ueid));
//        $k_l = rand(0,9);
//       $k_r = rand(0,9);
//       $k = strval($k_l.$k_r);
//        var_dump(gmp_prob_prime(23));
//        var_dump(base_convert(23, 10, 23));
//        var_dump($TQACC->read_ueid("1n3gfn1nd9nf"));
//        var_dump(getdate(mktime(0, 0, 0, 10, 31, 2002)));
//        exit();
        
//        var_dump(checkdnsrr("congo.fr","MX"));
//        $ref = 12 * 31536000;
//        $td = (new DateTime())->getTimestamp();
//        $date = mktime(0, 0, 0, 10, 29, 2014);
//        echo mktime(0, 0, 0, 12, 01, 2014);
//        $df = ($td - $date);
//        $df /= 31536000;
//        var_dump($td,$date,$df);
//        exit();
        //*/
        /*
        $start = round(microtime(TRUE)*1000);
        $TXH = new TEXTHANDLER();
        echo $TXH->get_deco_text('fr', "_Cancel");
        $end = round(microtime(TRUE)*1000);
        $start = round(microtime(TRUE)*1000);
        $end = round(microtime(TRUE)*1000);
        var_dump($end-$start);
        exit();
        //*/
        /*
        $to = [
                [
                    "hashtag" => "a7e87329b5eab8578f4f1098a152d6f4",
                    "title" => "Flower",
                    "order" => 30,
                ],
                [
                    "hashtag" => "b24ce0cd392a5b0b8dedc66c25213594",
                    "title" => "Free",
                    "order" => 12,
                ],
                [
                    "hashtag" => "e7d31fc0602fb2ede144d18cdffd816b",
                    "title" => "Ready",
                    "order" => 20
                ]
        ];
        
        var_dump($to);
        usort($to, function($a, $b) {
            return $a['order'] - $b['order'];
        });
        var_dump($to);
        exit();
        //*/
        /*
//        $url = "/Fulltest2";
        $url = "http://127.0.0.1/dev.trenqr.com/f/sts/5ja31o5b";
//        $url = "http://127.0.0.1/dev.trenqr.com";
//        $url = "http://127.0.0.1/WOSTQR_Beta1/Fulltest2";
//        $url = "http://127.0.0.1/WOSTQR_Beta1/Fulltest2/timeline/w=TMLNR_GTPG_RO&ups=k1-v1.k2-v2";
//        $url = "http://127.0.0.1/WOSTQR_Beta1/Fulltest2/profil/w=TMLNR_GTPG_RO";
//        $url = "https://beta-fr.trenqr.com/WOSTQR_Beta1/@Fulltest2/timeline/w=TMLNR_GTPG_RO&ups=k1-v1.k2-v2";
//        $url = "http://beta-fr.trenqr.com/WOSTQR_Beta1/Ajax/r/w=TMLNR_GTPG_RO&ups=k1=v1.k2=v2";
//        $url = "https://www.google.com/dir/1/2/search.html?arg=0-a&arg1=1-b&arg3-c#hash";
        
//        $pcs = explode("/", $url);
//        var_dump(filter_var($url, FILTER_VALIDATE_URL));
        
//        var_dump($pcs);
//        var_dump(parse_url($url));
        session_start();
//        session_destroy();
//        var_dump($_SESSION);
//        exit();
        $TQR = new TRENQR($_SESSION["sto_infos"]->getProd_xmlscope());
        $r = $TQR->explode_tqr_url($url);
        
        var_dump($r);
        exit();
        
        $TXH = new TEXTHANDLER();
        $u = $TXH->explode_tqr_url($url);
        var_dump($u);
        exit();
        $r = preg_replace("#(/WOSTQR_Beta1)|(/forrest)#", "", $u["path"]);
        
//        str_replace($r, $TXH, $url)
        $n = $r.$u["file"];
        var_dump($n);
//        var_dump(HTTP_RACINE,$r);
//        var_dump(HTTP_RACINE,$r);
        
        exit();
        //*/
//        $p = "/ext/public/js/r/c.c/ftp_wrote.png";
//        $p = "/ext/public/js/r/c.c/ftp_wrote.gif";
//        $p = "ext/public/js/r/c.c/lock2.png";
//        $file = RACINE."/public/tempImg/ri_anim.gif";
//        $fld = RACINE."/public/tempImg";
        
//        var_dump(file_exists($fld));
        
//        $pieces = explode("/", $p);
//        $z = array_slice($pieces, 2); 
//        
//        var_dump(implode("/", $z));
//        exit();
        
//        $img_string = file_get_contents($file);
//        $fp = tmpfile();
//        fwrite($fp, $img_string);
//        rewind($fp);
//     
//        $FH = new FTP_HANDLER("bart1");
//        $r = $FH->ftp_dir_exists($p);
//        $r = $FH->ftp_file_exists($p);
//        $r = $FH->ftp_delete_file($p);
//        $r = $FH->ftp_rename_file($p, "toto.png");
//        $r = $FH->ftp_create_file($p, $fp);
        
//        $path = pathinfo($p);
//        echo $path['dirname'];
//        var_dump(basename($p));
//        var_dump($r);
//        fclose($fp);
//        exit();
        /*
        $id = $this->convert_str_to_ascii("HOME");
        $b23picid = base_convert(intval($id), 10, 23);
         var_dump($id,intval($id,23),$b23picid);
         
        
//        $t = "\r\n \t \n \\n";
        $t = " ";
        var_dump(serialize($t));
        exit();
        $string = preg_replace("/\r*\n/","\\n",$t);
        $string = preg_replace("/\//","\\\/",$string);
        $string = preg_replace("/\"/","\\\"",$string);
        $t = preg_replace("/'/"," ",$string);
        
//        $t = htmlspecialchars($t);
        
//        var_dump(preg_match("#\\\\n#", $t), html_entity_decode($t));
        var_dump($t);
        
        exit();
       //*/
        $file = RACINE."/sandbox/img.jpg";
        $file_vid = RACINE."/sandbox/vid2.mp4";
//        $file = RACINE."/public/tempImg/ri_anim.gif";
//        $file = RACINE."/public/tempImg/pfl_bb_pic.jpg";
//        echo file_exists($file);
//        var_dump($file);
//        exit();

        //IMAGE
        $img_string = "data:image/png;base64,".base64_encode(file_get_contents($file));
        //VIDEO
        $video_str = "data:video/mp4;base64,".base64_encode(file_get_contents($file_vid));
        
//        $img_string = base64_encode(file_get_contents($file));
        
//        file_put_contents(WOS_SYSDIR_PROD_GLOBAL_ARTIMAGE.'temp.gif', base64_decode($img_string));
//        var_dump(base64_decode(base64_encode(file_get_contents($file))));
        /*
        $args = [
            "pdpic_fn" => "sdfsd",
            "pdpic_string" => $img_string,
            "pdpic_path" => WOS_SYSDIR_PROD_ARTIMAGE."user-22sdvdd5425621",
            "pdpic_quality" => "1",
            "server_id" => "1"
        ];
        //*/
        
        $time = time();
        $now = round(microtime(TRUE)*1000);
        //*
        $args = [
//            "pdpic_trd_eid" => "995f99", //Seulement pour trcover case
            "pdpic_artid"   => "556", //Si l'id n'exist pas ca ne va pas marcher dans le cas de ARTIMG
            "pdpic_art_eid" => "995f99",
            "pdpic_ueid"    => "995f99",
            "pdpic_fn"      => "sdfsd",
            "pdpic_string"  => $img_string,
            "pdpic_path"    => "",
            "pdpic_quality" => "1",
            "server_id"     => "1",
            "is_default"    => "false"
        ];
        //*/
        
        $args_newart = [
            "accid"         => "102", //71, ou ... 159983865
            "acc_eid"       => "211kaahla61", // 4n3g4n1n1l4n32, ou ... 1408909257
//            "art_desc" => "J'''aime #PéNéTréž #penetre les \"titres\"\" [#mots-clés]\ \\sans\, , ï & les _ erreurs. / // #azerty #5five #TotoYY",
            "art_desc"      => "\r\n \n<span class='red' style='color: red;'>maréééààà@@@çççie en rouge ?</span><script>alert('injection')</script>) #test #test @marie @Marie @marie @marïã #PrayForParis",
//            "art_desc" => "\r\n \n O'Reilly TEST ULTERIEUR BUG FE 12:)",
            "art_locip"     => ip2long($_SERVER["REMOTE_ADDR"]),
            "pdpic_fn"      => "rihanna",
            "art_pdpic_string" => $img_string
        ];
        /*
        $toto = $args_newart["art_desc"];
//        $toto = addcslashes($toto, "\0..\37!@\177..\377 \\");
        
        $text = str_replace("\r\n", "\n", $toto);
        $text = str_replace("\r", "\n", $text);

        // JSON requires new line characters be escaped
        $text = htmlentities(str_replace("\n", "<br/>", $text));
        
        var_dump($toto,$text);
        exit();
        $tata = htmlentities(serialize($toto));
        
        var_dump(stripcslashes($tata),$toto,$tata,unserialize(html_entity_decode($tata)));//stripcslashes($tata)
        exit();
        //*/
        $cuid = '71';
        $args_new_trart = [
            "accid"             => "71",
            "acc_eid"           => "4n3g4n1n1l4n32",
//            "art_desc" => " INTR ULTERIEUR 7 TEST SUR DEUX COLS #PHOENIX ? J'''aime #PéNéTréž #penetre les \"titres\"\" [#mots-clés]\ \\sans\, , ï & les _ erreurs. / // #azerty #5five #TotoYY",
            "art_desc"          => "<span class='red' style='color: red;'>maréééààà@@@çççie en rouge ?</span><script>alert('injection')</script>",
            "art_locip"         => ip2long($_SERVER["REMOTE_ADDR"]),
            "pdpic_fn"          => "rihanna",
            "art_pdpic_string"  => $img_string,
            "trd_eid"           => 'd92ho1'
        ];
        
        //["trd_title","trd_desc","catg_decocode","trd_is_public","trd_grat","trd_loc_numip","trd_oeid"]
        
        $args_newtr = [
            "trd_title"     => "Titre 10.1 $time. _ Aller la famille & É çona <script>alert(\"ab\")</script> | ..(\"@^ + - % ù ",
            "trd_desc"      => "Une description pour ma super Tendance",
            "catg_decocode" => "animals",
            "trd_is_public" => 0,
            "trd_grat"      => 0,
            "trd_loc_numip" => ip2long($_SERVER["REMOTE_ADDR"]),
            "trd_oid"       => '70'
        ];
        
        $args_new_pdacc = [
            "accid"         => 1234,
            "acc_gid"       => "2",
            "acc_eid"       => "eidpour1234",
            "acc_upsd"      => "Pseudo1234",
            "acc_ufn"       => "Famille ProdAcc",
            "acc_ugdr"      => "m",
//            "acc_uppic" => "http://lorempixel.com/70/70",
//            "acc_uppicid" => "1",
//            "acc_uppic" => "http://lorempixel.com/70/70",
//            "acc_coverpicid" => "1",
//            "acc_coverpic" => "http://lorempixel.com/1000/1000",
            "acc_ucityid"   => "4431410",
            "acc_ucity_fn"  => "Jackson",
            "acc_nocity"    => NULL,
            "acc_ucnid"     => "us",
            "acc_ucn_fn"    => "United States",
            "acc_udl"       => "fr",
            "acc_datecrea"  => "2014-12-09 14:26:22",
            "acc_datecrea_tstamp" => $now,
            "acc_capital"   => "0"
        ];
        
        //$rbody, $rlocip, $rwriter, $artid
        $args_new_art = [
            "rbody" => "Un texte pour mon premiere essai PREDATE de commentaire @fulltest2 @fULLTEST1",
            "rlocip" => ip2long($_SERVER["REMOTE_ADDR"]),
            "rwriter" => "71",
            "artid" => "353"
//            "artid" => "95"
        ];
        
        $args_new_evt = [
            "actor" => 71,
            "evtype" => 9, /* De 1 à 30 */
        ];
        
        $args_new_co = [
            "oper_evt" => 6,
            "oper_recept" => 71
        ];
        
        $args_new_evl = [
            "actor"     => "71",
            "eval_code" => "_eval_spcl",
            "art_eid"   => "7fbbjoa7" //D'hab : 7fbbjo57
        ];
        
        $cov_file = RACINE."/public/tempImg/lyon.jpg";
        $cov_img_string = "data:image/png;base64,".base64_encode(file_get_contents($cov_file));
        $args_new_acov = [
            "cov_w"     => 550,
            "cov_h"     => 550,
            "cov_t"     => -10,
            "pdpic_fn"  => "lyon.jpg",
            "pdpic_string" => $cov_img_string,
            "oeid"      => "4n3g4n1n1l4n32"
        ];
        
        $cov_file = RACINE."/public/tempImg/lyon.jpg";
        $cov_img_string = "data:image/png;base64,".base64_encode(file_get_contents($cov_file));
        $args_new_tcov = [
            "cov_w"     => 550,
            "cov_h"     => 550,
            "cov_t"     => -10,
            "pdpic_fn"  => "trd_cover_test_sb.jpg",
            "pdpic_string" => $cov_img_string,
            "tcov_teid" => "d92ho1"
        ];
        
        $cov_file = RACINE."/public/tempImg/riri2.jpg";
        $cov_img_string = "data:image/png;base64,".base64_encode(file_get_contents($cov_file));
        $args_new_ppic = [
            "pdpic_fn"      => "riri2.jpg",
            "pdpic_string"  => $cov_img_string,
            "oeid"          => "4n3g4n1n1l4n32"
        ];
        
        $args_final_ins = [
            "fullname"  => "Fullname",
            "borndate"  => "10-28-2002",
            "gender"    => "m",
            "city"      => 2660646,
            "pseudo"    => "geneve",
            "email"     => "genva@ondeuslynn.com",
            "password"  => "geneve",
        ];
        
        $args_final_ins2 = [
            "ufn"   => "Fullname",
            "ubd"   => "10-28-2002",
            "ugdr"  => "m",
            "ucy"   => 2660646,
            "upsd"  => "geneve",
            "ueml"  => "genva@ondeuslynn.com",
            "upwd"  => "geneve.3",
            "locip" => ip2long($_SERVER["REMOTE_ADDR"])
        ];
        
        $args_final_ins3 = [
            "ins_fn"    => "A VOISARD",
            "ins_nais"  => date("m-d-Y",mktime(0, 0, 0, 02, 15, 2002)),
            "ins_nais_tstamp" => mktime(0, 0, 0, 02, 15, 2002),
            "ins_gdr"   => "f",
            "ins_cty"   => 2660646,
            "ins_psd"   => "pseudo4",
            "ins_eml"   => "pseudo4@ondeuslynn.com",
            "ins_pwd"   => "pseudo.3",
            "locip"     => ip2long($_SERVER["REMOTE_ADDR"])
        ];
        
        $args_srh_trd = [
            "srh_tr_id"     => 987456,
            "srh_tr_eid"    => "trd_eid_987456",
            "srh_tr_tle"    => "Un Titre",
            "srh_tr_desc"   => "Une Description",
            "srh_tr_tlehrf"  => "un_titre",
            //DONNEES EXTRAS
            "srh_tr_fol"    => "0",
            "srh_tr_post"   => "0",
            //DONNEES ONWER
            "srh_tr_owid"   => "70",
            "srh_tr_oweid"  => "8n3i3n2n1l4n31",
            "srh_tr_owpsd"  => "Fulltest1",
            "srh_tr_owfn"   => "Testcomplet Premier"
        ];
        
        
        $start = round(microtime(TRUE)*1000);
//        var_dump($args_new_pdacc);
//        exit();
        
        //$_CHILD_FILE, $_CHILD_CLASS, $_MAX_HEIGHT, $_MIN_HEIGHT, $_MAX_WIDTH, $_MIN_WIDTH, $_ALLOWED_EXT, $_MAX_SIZE
        $PDACC = new PROD_ACC();
//        $r = $PDACC->entity_ieid_decode("64e5fo1g1");
//        $r = $PDACC->exists("014991030168");
//        $r = $PDACC->on_create_entity($args_new_pdacc);
//        $r = $PDACC->on_read_entity(["acc_eid" => "eidpour33333"]);
//        $r = $PDACC->setPdacc_ctw_dsma("159983865", 1);
//        $r = $PDACC->onread_acquiere;
//        $r = $PDACC->onread_acquiere_my_trends_datas('71');
//        $r = $PDACC->onread_acquiere_following_trends_datas('71');
        /*
        $r = $PDACC->onread_load_my_first_articles(102, 1, [
            "VM_ART",
            "FKSA_SAMPLE", 
//            "ARTICLE_IML_FILTER" => "NOT_IML_FRD"
            "CUID" => 106
        ]);
        //*/
        
//        $r = $PDACC->on_alter_entity($args_new_pdacc);
//        $r = $PDACC->onread_acquiere_my_following("102");
//        $r = $PDACC->onread_acquiere_my_followers("70");
//        $r = $PDACC->onread_acquiere_my_friend_requests_list('71');
//        $r = $PDACC->onread_acquiere_my_friends('71');
//        $r = $PDACC->set_new_profilbio(71,"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut lacinia sapien erat, quis posuere orci tincidunt vitae. Phasellus auctor augue>.");
//        $r = $PDACC->onread_load_more_iml_articles(71, true);
//        $r = $PDACC->onread_load_more_iml_articles(71, true, '7fbbjo3l');
//        $r = $PDACC->onread_load_more_iml_articles(71, false, '7fbbjo47');
//        $r = $PDACC->onread_load_more_itr_articles(71, true);
//        $r = $PDACC->onread_load_more_itr_articles(71, true, '7fbbjo3e');
//        $r = $PDACC->onread_load_more_itr_articles(71, false, '7fbbjo3e');
//        $r = $PDACC->onread_acquiere_my_community(71);
//        $r = $PDACC->UserActyLog_FeedTestDatas(71);
//        $r = $PDACC->UserActyLog_Within(71,100,1);
//        $r = $PDACC->set_new_website(71, "");
        /***** ABOUTME *****/
//        $r = $PDACC->abme_intro_get(102,["WFEO"]);
        /*
        $r = $PDACC->abme_intro_set(102,[
//            "lib" => "Ce texte dépasse 300. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc dui elit, aliquam eget ex sit amet, tincidunt mollis elit. Ut finibus lobortis urna, vel ultrices mi consequat id. Vivamus eget dolor eu quam consectetur volutpat. Sed gravida finibus risus vel dictum. Aliquam eget commodo nunc, et metus."
//            "lib" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc dui elit, aliquam eget ex sit amet, tincidunt mollis elit. Ut finibus lobortis urna, vel ultrices mi consequat id. Vivamus eget dolor eu quam consectetur volutpat. Sed gravida finibus risus vel dictum. Aliquam eget commodo nunc, et metus."
            "lib" => "Je suis @Lou et #hashtag pour tests 😄"
        ],"99999999999999",ip2long($_SERVER["REMOTE_ADDR"]),NULL,["WGDO"]);
        //*/
        /* ------------------------ */
//        $r = $PDACC->abme_lvsp_get(102,["WFEO"]);
//        $r = $PDACC->abme_lvsp_get_pl_catgs();
        /*
        $r = $PDACC->abme_lvsp_set(102,[
            "chc_1" => "_NTR_CATG_HUMOR",
            "chc_2" => "_NTR_CATG_HUMOR",
            "chc_3" => "_NTR_CATG_ART",
            "chc_4" => "_NTR_CATG_TECHNOLOGY",
            "chc_5" => "_NTR_CATG_LEISURE",
            "chc_6" => "_NTR_CATG_MEME",
            "chc_7" => "_NTR_CATG_POLITICS",
            "chc_8" => "_NTR_CATG_THEOLOGY",
            "chc_9" => "_NTR_CATG_TUTOS_TRENQR",
            "chc_10" => "_NTR_CATG_VIDEOGAME",
        ],"99999999999999",ip2long($_SERVER["REMOTE_ADDR"]),NULL,["WGDO"]);
        //*/
        /* ------------------------ */
//        $r = $PDACC->abme_whyme_get(102,["WFEO"]);
        /*
        $r = $PDACC->abme_whyme_set(102,[
            "chc_1" => "Un texte de moins de 100 caractères",
//            "chc_2" => "Un texte de moins de 100 caractères",
//            "chc_3" => "Je suis @Lou et #hashtag pour tests 😄"
        ],"99999999999999",ip2long($_SERVER["REMOTE_ADDR"]),NULL,["WGDO"]);
        //*/
        /* ------------------------ */
//        $r = $PDACC->abme_imas_get(102,["WFEO"]);
        /*
        $r = $PDACC->abme_imas_set(102,[
            "chc_1" => "MOINS de 30 caractères 😄",
            "chc_2" => "MOINS de 30 caractères",
            "chc_3" => "MOINS de 30 caractères 😄"
        ],"99999999999999",ip2long($_SERVER["REMOTE_ADDR"]),NULL,["WGDO"]);
        //*/
        
        $REL = new RELATION();
//        $r = $REL->exists(["actor" => "70","target" => 71]);
//        $r = $REL->exists(["actor" => "55","target" => 53]);
//        $r = $REL->onread_get_urel_if_exists(33333,"46");
//        $r = $REL->on_create_entity(["acc_actor" => "71","acc_target" => "33333"]);
//        $r = $REL->on_create_entity(["acc_actor" => "70","acc_target" => "46"]);
//        $r = $REL->on_create_entity(["acc_actor" => "46","acc_target" => "70"]);
//        $r = $REL->on_create_entity(["acc_actor" => "53","acc_target" => "70"]);
//        $r = $REL->on_create_entity(["acc_actor" => "48","acc_target" => "70"]);
//        $r = $REL->friend_ask_as_a_friend('68', '71');
//        $r = $REL->friend_ask_as_a_friend('55', '71');
//        $r = $REL->friend_ask_as_a_friend('55', 71);
//        $r = $REL->friend_ask_as_a_friend(48, '71');
//        $r = $REL->friend_ask_as_a_friend(159983865, '71');
//        $r = $REL->friend_ask_as_a_friend(53, '71');
//        $r = $REL->friend_askfriend_request_exists(71, "33333");
//        $r = $REL->friend_accept_request(55, "53", "13");
//        $r = $REL->friend_reject_request(55, 53, "11");
//        $r = $REL->friend_break_friend_relation("55", 53);
//        $r = $REL->friend_break_friend_relation("53", 55);
//        $r = $REL->onalter_downgrade_relation("46", 70); //Pour tester le cas où c'est 70 qui 46 mais 46 envoie un signal pour UFOL. Cela peut être rendu possible du fait qu'un utilisateur peut avec au meme moment plusieurs SESSION
//        $r = $REL->onalter_downgrade_relation(55, "53");
//        $r = $REL->friend_commons_friends_list(53, '55');
//        $r = $REL->onread_relation_exists_fecase(70,"46");
//        $r = $REL->oncreate_SyncWmRel(48);
//        $r = $REL->onalter_SyncWmRel(48);
//        $r = $REL->ondelete_SyncWmRel(48);
        
        $EVT = new PROD_EVENT();
//        $r = $EVT->exists("6");
//        $r = $EVT->on_create_entity($args_new_evt);
        
        $CO = new CAP_OPER();
//        $r = $CO->exists(1);
//        $r = $CO->on_create_entity($args_new_co);
        
        $ART = new ARTICLE();
        /*
        $args_newart = [
            "accid"     => "102", //71, ou ... 159983865
            "acc_eid"   => "211kaahla61", // 4n3g4n1n1l4n32, ou ... 1408909257
//            "art_desc" => "J'''aime #PéNéTréž #penetre les \"titres\"\" [#mots-clés]\ \\sans\, , ï & les _ erreurs. / // #azerty #5five #TotoYY",
            "art_desc"  => "\r\n \n<span class='red' http://trenqr.me, www.trenqr.fr style='color: red;'>maréééààà@@@çççie en rouge ?</span><script>alert('injection')</script>) #test #test @marie @Marie @marie @marïã #PrayForParis",
//            "art_desc" => "\r\n \n O'Reilly TEST ULTERIEUR BUG FE 12:)",
            "art_locip" => ip2long($_SERVER["REMOTE_ADDR"]),
            "pdpic_fn"  => "rihanna",
            "art_pdpic_string" => $img_string
        ];
        //*/
        $args_newart = [
            "accid"         => "102", //71, ou ... 159983865
            "acc_eid"       => "211kaahla61", // 4n3g4n1n1l4n32, ou ... 1408909257
//            "art_desc" => "J'''aime #PéNéTréž #penetre les \"titres\"\" [#mots-clés]\ \\sans\, , ï & les _ erreurs. / // #azerty #5five #TotoYY",
//            "art_desc"      => "\r\n \n<span class='red' http://trenqr.me, www.trenqr.fr style='color: red;'>maréééààà@@@çççie en rouge ?</span><script>alert('injection')</script>) #test #test @marie @Marie @marie @marïã #PrayForParis",
//            "art_desc" => "\r\n \n O'Reilly TEST ULTERIEUR BUG FE 12:)",
            "art_desc" => "TEST 😈😜 EMOJI",
            "art_locip"     => ip2long($_SERVER["REMOTE_ADDR"]),
            /*
            "pdpic_fn"      => "rihanna",
            "art_pdpic_string" => $img_string
            //*/
            //*
            "file.name"     => "rihanna.jpg",
            "file.type"     => "image",
            "file.data"     => "data:image/png;base64,".base64_encode(file_get_contents(RACINE."/sandbox/img.jpg")),
//            "file.data"     => "data:image/png;base64,".base64_encode(file_get_contents(RACINE."/sandbox/img_rect.jpg")),
            /*
            "file.options"  => [
                "istory"    => 0,
//                "edge"      => 50,
                "top"       => 50,
                "left"      => 50
            ]
            //*/
            /*
            "file.options"  => [
                "istory"    => 1,
                "xtrabar"   => [
//                    "tx"    => "Un texte décoratif qui doit matcher",
                    "tx"    => "Ah les Jenner #LMAO!",
                    "cd"    => "std_black",
                    "top"   => 337
                ]
            ]
            //*/
            "file.options"  => [
                "orien" => [
                    "ang"   => -90
                ],
            ]
            //*/
            /*
//            "file.name"     => "Bean.mp4", //Ali
//            "file.name"     => "ali.mp4", //Ali
//            "file.name"     => "cakes.mp4", //Bubble
//            "file.name"     => "my_week.mp4", //My_Week
            "file.name"     => "heavy.mp4", //Baby Musclor
//            "file.name"     => "vid8.mp4", //Baby Musclor
            "file.type"     => "video", 
//            "file.data"     => "data:video/mp4;base64,".base64_encode(file_get_contents(RACINE."/sandbox/vid.mp4")), //Comique 
//            "file.data"     => "data:video/mp4;base64,".base64_encode(file_get_contents(RACINE."/sandbox/vid2.mp4")), //Ali
//            "file.data"     => "data:video/mp4;base64,".base64_encode(file_get_contents(RACINE."/sandbox/vid3.mp4")), //Ali
//            "file.data"     => "data:video/mp4;base64,".base64_encode(file_get_contents(RACINE."/sandbox/vid8.mp4")), //Ali
//            "file.data"     => "data:video/mp4;base64,".base64_encode(file_get_contents(RACINE."/sandbox/heavy.mp4")), //Baby Musclor
//            "file.data"     => "data:video/mp4;base64,".base64_encode(file_get_contents(RACINE."/sandbox/vid30.mp4")), //Netflix
//            "file.data"     => "data:video/mp4;base64,".base64_encode(file_get_contents(RACINE."/sandbox/vid31.mp4")), //My_Week
            "file.data"     => "data:video/mp4;base64,".base64_encode(file_get_contents(RACINE."/sandbox/vid_j.mp4")), //My_Week
//            "file.data"     => "data:video/mp4;base64,".base64_encode(file_get_contents(RACINE."/sandbox/ananas.mp4")), //Ananas
//            "file.data"     => "data:video/mp4;base64,".base64_encode(file_get_contents(RACINE."/sandbox/cook.mp4")), //Cook
            //*
            "file.options"  => [
                "istory"    => 0,
                "ihosted"   => 1,
            ]
            //*/
        ];
        
        session_start();
//        $r = $ART->on_create_entity($args_newart);
        
//        $r = $ART->exists("9gm1mj3o1ca",["NO_STATE_CHECK"]);
//        $r = $ART->exists("1h0g3o1di",["NO_STATE_CHECK"]);
//        $r = $ART->exists_with_id(1);
//        $r = $ART->on_read_entity(["art_eid" => "9gm1mj3o1ca"]);//7fbbjo3g
//        $r = $ART->article_get_reacts("7fbbjo3g");
//        $r = $ART->reaction_add_art_reaction($args_new_art["rbody"], $args_new_art["rlocip"], $args_new_art["rwriter"], $args_new_art["artid"]);
//        $r = $ART->reaction_exists('31b8ao9');
//        $r = $ART->reaction_del_art_reaction('37kfbo86', '71');
        $r = $ART->on_delete_entity("5kmf1o1kl",TRUE);
//        $r = $ART->onalter_selfupdate_vm_iml("7fbbjo3g");
//        $r = $ART->onalter_selfupdate_vm("7fbbjo24");//7fbbjoa5
//        $r = $ART->GenerateFakiesITR("71", "4n3g4n1n1l4n32", "d92ho1", 1);
//        $r = $ART->GenerateFakiesIML("71", "4n3g4n1n1l4n32", 1);
//        $r = $ART->GenerateFakiesIML("36", "129clhcc064", 5);
//        $r = $ART->ondelete_art_vm("7fbbjoa5");
//        $r = $ART->onread_archive_iml(["art_eid"=>"3kh65o1eh"]);
//        $r = $ART->onread_archive_itr(["art_eid"=>"3kb85o1ei"]);
//        $r = $ART->oncreate_EncodePrmlk("7fbbjoa8");
//        $r = $ART->exists_with_prmlk("7fbbjo3g");
//        $r = $ART->article_get_evals("7fbbjo58");
//        $r = $ART->onread_AcquierePrmlk("7fbbjo58",TRUE);
//        $r = $ART->onread_AcquiereUsertags_Article("19f74o1de");
//        $r = $ART->onread_AcquiereHashs_Article("19f74o1de");
//        $r =  $ART->article_get_reacts_from("7fbbjo3g", "34kheoc", "1409648449304", "top");
//        $r =  $ART->onread_AcquiereUsertags_Reaction("9h12ac9o1j7");
//        $r = $ART->onload_art_rnb('7fbbjo24');
//        $r = $ART->onload_art_rnb_wid(50);
//        $r = $ART->onalter_change_state("7fbbjoai",1);
//        $r = $ART->onload_neighbors_from(1, "SOURCE_ACC", 71, ["FKSA_SAMPLE","VM_ART"]);
//        $r = $ART->onread_CanRead(102,846);
//        $r = $ART->onload_art_vid_url("30j65o1em",null,TRUE);
//        $r = $ART->onread_is_trend_version(460);
//        $r = $ART->onread_is_trend_version_eid("7fbbjojm");
//        $r = $ART->onload_art_eval("7fbbjojm");
        
//        $r = $ART->oncreate_archive_iml(["test"=>null]);
        /*
         * @Lou > 211kaahla61; @Mouna > 12aoka10155; @Marie > 127mj643af14 } 
         */
        $xa_datas = [
            "ouid"      => "12aoka10155", //106 (mouna)
            "ssid"      => "1111111111111111",
            "locip"     => sprintf('%u', ip2long($_SERVER["SERVER_ADDR"])),
            "uagent"    => $_SERVER["HTTP_USER_AGENT"],
        ]; 
//        $r = $ART->Favorite_Action("211kaahla61", "7digco1e8", "ART_XA_FAV_PUB", $xa_datas["ssid"], $xa_datas["locip"], $xa_datas["uagnt"]);
//        $r = $ART->Favorite_GetFavArts(102, 102, "FST", NULL, NULL, NULL);
//        $r = $ART->Favorite_GetFavArts(102, 102, "BTM", "2i44ko1f0", 1461757805074, NULL);
//        $r = $ART->Favorite_ConvertTypeID(1);
//        $r = $ART->report_action();
                
        $TRD = new TREND();
//        $r = $TRD->on_create_entity ($args_newtr, true);
//        $r = $TRD->on_read_entity (["trd_eid" => "d92ho1"]);
//        $r = $TRD->on_read_entity (["trd_eid" => "6dl7joa", "urqid" => "explore"]);
//        $r = $TRD->exists("14g93o3");
//        $r = $TRD->exists("14g93o3",["NO_STATE_CHECK"]);
//        $r = $TRD->exists_with_id(1);
//        $r = $TRD->on_delete_entity("3124go11");
//        $r = $TRD->ondelete_statehisty(1);
//        $r = $TRD->trend_subscribe(71,"3124go11");
//        $r = $TRD->trend_subscribe(70,"d92ho1");
//        $r = $TRD->trend_abo_exists(70, "d92ho1");
//        $r = $TRD->trend_disconnect(70, "d92ho1");
//        $r = $TRD->oncreate_archive_trend($args_srh_trd);
//        $trid = 23;
//        $r = $TRD->onalter_update_archv_trend(["trid"=>$trid]);
//        $trd_eid = "6g3f2o10";
//        $r = $TRD->onalter_update_archv_trend(["trd_eid"=>$trd_eid]);
//        $r = $TRD->onread_usercontrib(71,1,TRUE);
//        $r = $TRD->onalter_change_state("14g93o3",2);
//        $r = $TRD->on_read_build_trdhref_from_treid("4m53do12");

        $TRART = new ARTICLE_TR();
//        $r = $TRART->on_create ( $args_new_trart, $cuid );
//        $r = $TRART->child_exists_with_id(1);
//            $r = $TRART->getArt_loads();
//        $r = $TRART->child_exists("7fbbjoj");
        //*
//        $r = $TRART->on_read(["art_eid"=>"7fbbjoj"]);
//            $r = $TRART->getArt_loads();
        //*/
        
        $NWFD = new NEWSFEED();
//        $r = $NWFD->GetFirstArticles(102, "list", NULL);
//        $r = $NWFD->GetFirstArticles(102, "list", "comy");
//        $r = $NWFD->GetFirstArticles(106, "list", "iml_pod");
//        $r = $NWFD->GetFirstArticles(102, "list", "itr");
        $ads__ = [
            '_xl_3im' => [
                'i' => "7fbbjo2j",
                't' => 1418376994052
            ],
            '_xl_st' => [
                'i' => "7fbbjo2f",
                't' => 1418062566923
            ],
        ];
//        $r = $NWFD->GetOlderArticles(71, "list", $ads__, NULL);
        
        
        $IMG = new IMAGE(__FILE__, __FUNCTION__, 1000, 10, 1000, 10, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], 10000000);
//        $r = $IMG->on_delete_entity($pdpicid);
//        $r = $IMG->on_delete_entity(159);
//        $ARI = new IMAGE_ART();
        $PPI = new IMAGE_PFLPIC();
//        $r = $PPI->on_create($args_new_ppic); 
//        $r = $PPI->oncreate_defaultpic(71,TRUE);
        $CVTI = new IMAGE_COVERTR();
//        $r = $CVTI->on_create($args_new_tcov);
        
        $CVAI = new IMAGE_COVERACC();
//        $r = $CVAI->on_create($args_new_acov);
         
        $video_str = "data:video/mp4;base64,".base64_encode(file_get_contents($file_vid));
        $last_srvid = ( ( defined("WOS_MAIN_HOST") && WOS_MAIN_HOST === "localhost" ) | ( defined("IS_SANDBOX") && IS_SANDBOX === TRUE ) ) ? 4 : 1;
        $_SRV_LIST = [1 => "lisa1", 2 => "marge1", 3 => "bart1", 4 => "localhost"];
        
        $vargs = [
            "vid_fname"     => "video.mp4",
            "vid_data"      => $video_str,
            "srvid"         => $last_srvid,
            "srvname"       => $_SRV_LIST[$last_srvid],
            "vid_artid"     => 871,
            "vid_art_eid"   => "602j6o1ek",
            "vid_ueid"      => "211kaahla61"
        ];
//        var_dump($vargs);
//        exit();
        
        $VDSRVC = new SRVC_VIDEO_HANDLER();
//        $r = $VDSRVC->GetInfosFromBase64VidString($video_str, "video.mp4",true);
        
        $VID = new VIDEO_ART();
//        $r = $VID->on_create($vargs);
//        $r = $VID->onexists("3kb7lo7");
//        $r = $VID->onexists_with_id(8);
//        
//        $r = $VID->on_delete(1);
        
//        $r = $VID->on_delete_entity($pdpicid);
//        $r = $VID->on_delete_entity(159);
        
        
//        $r = $ART->on_read_entity(["trd_eid" => '18df4o1', "urqid" => "TRPG_TEST_UQ"]);
//        $arts = $ART->on_read_get_filtered_articles_by_date('1408365445273', "TRART_FILTER_GET_PREDATE", "URQ_TRPG"); //FILTERED_ART_OLD, FILTERED_ART_PREDATE
//        
//        
//        
//        $r = $ART->on_read ( ["art_eid" => "7fbbjo1"], "TRPG_TEST" );
//        $r = $ART->on_read_entity(["art_eid" => "7fbbjo1"]);
//        $r = $ART->child_exists ( 1, "TRPG_TEST" );
//        $r = $ART->article_get_reacts('7fbbjo4');
          
//          $alt = $ART->setTrd_title("Un nouveau titre $time","71", FALSE);
//          $alt = $ART->setTrd_title("un nouveau titre 1408311820","71", FALSE);
//          $alt = $ART->setTrd_desc("Un nouveau texte de description pour ma Tendance a $time","71", FALSE);
//          $alt = $ART->setTrd_is_public('pub',"71", FALSE);
//          $alt = $ART->setTrd_grat('5',"71", FALSE);
//          $alt = $ART->setTrd_catgid('society',"71", FALSE);
        /*
        $rt = [
            "rbody" => "Ceci est mon tout premier #commentaire de #martien",
            "rlocip" => ip2long($_SERVER["REMOTE_ADDR"]),
            "rwriter" => '70',
            "artid" => $r["artid"]
        ];
        
        $ri = $ART->add_art_reaction($rt["rbody"], $rt["rlocip"], $rt["rwriter"], $rt["artid"]);
        */
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $alt,'v_d');
        
        $EVL = new EVALUATION();
//        $r = $EVL->exists(["actor" => 71,"artid"=>120]);
//        $r = $EVL->on_create_entity($args_new_evl);
//        $r = $EVL->onread_acquiere_vips(null, "2112923", "jp", 71);
//        $r = $EVL->onread_acquiere_vips("7fbbjo55", "2112923", "jp", 71);
//        $r = $EVL->onread_acquiere_vips("7fbbjo55", null, null, 71);
//        $r = $EVL->onread_acquiere_vips("7fbbjo55", null, "jp", null);
//        $r = $EVL->onread_acquiere_vips("7fbbjo55", "2112923", "jp", null);
//        $r = $EVL->onread_acquiere_vips("7fbbjo55", null, null, null);
//        $r = $EVL->onread_count_eval_bytype_byart("7fbbjo55", "_eval_dlk");
//        $r = $EVL->onread_count_evaltot("7fbbjo55");
//        $r = $EVL->onread_eval_article_value("7fbbjo57");
        
        $SRH = new SEARCH();
//        $r = $SRH->Profile_FirstResults("lou*", 71);
//        $r = $SRH->Profile_MrReslt("f*", "uk", null, null);
//        $r = $SRH->Trend_FirstResults("t*", 71);
//        $r = $SRH->Trend_MrReslt("u*", "pop", 1, 33333);
        
//        $email = "fake.email@ondeuslynn.com"; //TEST EMAIL EXISTS
//        $email = "fake.email@yopmail.com"; //TEST BAN EMAIL
//        $psd = "eraseme"; 
//        $psd = "LoremIpsumDolorSitAmet"; //TEST LONG PSEUDO
//        $psd = "LoremIpsumDolorSit"; //TEST LONG SUGGEST PSEUDO
//        $psd = "Erase"; //TEST UNVAL SUGGEST PSEUDO
        $psd = "trenqr";
        $INS = new INSCRIPTION();
//        $r = $INS->PullEmail($email);
//        $r = $INS->CheckEmailDomDns($email);
//        $r = $INS->IsEmailDomBan($email);
//        $r = $INS->IsEmailDomBan($email);
//        $r = $INS->PullPseudo($psd);
        $sd = [1991,991,91,'fr','me']; //Le 'me' n'a été ajouté que pour des raisons de test
//        $r = $INS->SuggestPseudo($psd,$sd,TRUE);
        /*
        foreach ($args_final_ins as $k => $v) {
            $r[] = $INS->CheckField($k, $v);
        }
        //*/
//        $r = $INS->CreateNewAccount($args_final_ins2);
        
        
        $email = "new.email@ondeuslynn.com"; //DEV, TEST
        $TQACC = new TQR_ACCOUNT();
//        $r = $TQACC->Email_Exists($email);
//        $r = $TQACC->Email_TripleCheck($email);
//        $r = $TQACC->InsertEmail($email);
//        $r = $TQACC->Pseudo_IsReserved("richardbranson");
//        $r = $TQACC->on_create_entity($args_final_ins3);
//        $r = $TQACC->on_read_entity(["acc_eid" => "21eoa74ec886"]);
//        $r = $TQACC->ondelete_goToDelete(36);
//        $r = $TQACC->onalter_CclToDelete(36);
//        $r = $TQACC->onalter_RecoverPassword("andrea@ondeuslynn.com",  session_id(),ip2long($_SERVER["REMOTE_ADDR"]));
//        $r = $TQACC->report_todelete();
//        $r = $TQACC->onread_getSenderMail("GO_TO_SHELL");
//        $r = $TQACC->Pseudo_IsDenied("trenqr77311887671246");
        $args_econf = [ 
            "ueid"      => "12aoka10155", 
            "eml"       => "lou.carther@deuslynn-entreprise.com", 
            "locip"     => ip2long($_SERVER["REMOTE_ADDR"]), 
            "purpz"     => "ACCOUNT_CREATION", 
            "ssid"      => session_id(), 
            "uagent"    => $_SERVER["HTTP_USER_AGENT"], 
            "OVRWRT"    => TRUE
        ];
//        $r = $TQACC->EC_NewOper($args_econf["ueid"], $args_econf["eml"], $args_econf["locip"], $args_econf["purpz"], $args_econf["ssid"], $args_econf["uagent"], $args_econf["OVRWRT"]);
        $args_econf_vld = [ 
            "uid"       => "12aoka10155", 
            "eml"       => "lou.carther@deuslynn-entreprise.com", 
            "key"       => "44275243-110c-4b2f-b742-115da2f43d6b", 
            "ssid"      => "36t0o1q6gs949qh0qcknajs4s2", 
            "tm"        => 1445614591875,
            "cd"        => "CHSjln"
        ];
//        $r = $TQACC->EC_ValidOper_Check($args_econf_vld["uid"], $args_econf_vld["eml"], $args_econf_vld["key"], $args_econf_vld["ssid"], $args_econf_vld["tm"], $args_econf_vld["cd"]);
//        $r = $TQACC->EC_ValidOper($args_econf_vld["uid"], $args_econf_vld["eml"], $args_econf_vld["key"], $args_econf_vld["ssid"], $args_econf_vld["tm"], $args_econf_vld["cd"]);
//        $r = $TQACC->EC_NewOperIfNotVald($args_econf["ueid"], $args_econf["locip"], $args_econf["purpz"], $args_econf["ssid"], $args_econf["eml"], $args_econf["uagent"]);
//        $r = $TQACC->EC_Exists("05e7858d-500c-4e89-b5f0-b1786cbab173");
//        $r = $TQACC->EC_AccIsCnfrmdOnce("211kaahla61");
        
        
        $args_update_pfl = [
            "accid"     => 36,
            "ins_fn"    => "Marie Leroy",
//            "ins_nais_tstamp" => 1037991600, //TEST
            "ins_nais_tstamp" => 673259931, //5 Mai 1991
            "ins_gdr"   => "f",
            "ins_cty"   => "2660646",
            "locip"     => ip2long($_SERVER["REMOTE_ADDR"])
        ];
//        $r = $TQACC->onalter_profile($args_update_pfl);
        //onalter_stgs_updproddb
        $args_update_acc = [
            "accid"     => 102,
            "ins_psd"   => "lou",
            "ins_eml"   => "lou.carther@deuslynn-entreprise.com", 
            "ins_lng"   => "en",
            "locip"     => ip2long($_SERVER["REMOTE_ADDR"])
        ];
//        $r = $TQACC->onalter_account($args_update_acc);
        $args_update_pwd = [
            "accid"     => 36,
            "ins_pwd"   => "andrea.3", //ORIGINAL : andrea
            "locip"     => ip2long($_SERVER["REMOTE_ADDR"])
        ];
//        $r = $TQACC->onalter_password($args_update_pwd);
        $args_update_seclog = [
            "accid"         => 36,
            "sec_ecwpsd"    => "uchk",
            "locip"         => ip2long($_SERVER["REMOTE_ADDR"])
        ];
//        $r = $TQACC->onalter_seculog($args_update_seclog);
        $args_delete = [
            "accid"     => 36,
            "hikw"      => "SCHOOL",
            "yilv"      => "MSENTOURAGE",
            "yilv_ot"   => "",
            "ilbbif"    => "",
            "locip"     => ip2long($_SERVER["REMOTE_ADDR"])
        ];
//        $r = $TQACC->ondelete_apply($args_delete);
        
        $TQCNX = new TQR_CONX();
        //["cnx_login","cnx_pwd","cnx_ssid","cnx_locip","cnx_too"]
        $args_cnx = [
            "cnx_login" => "andrea@ondeuslynn.com",
//            "cnx_login" => "andrea",
            "cnx_pwd"   => "andrea.3",
            "cnx_ssid"  => session_id(),
            "cnx_locip" => ip2long($_SERVER["REMOTE_ADDR"]),
            "cnx_too"   => round(microtime(TRUE)*1000)
        ];
//        $r = $TQCNX->TryConx($args_cnx);
//        $r = $TQCNX->HandleToDelCase(31,"KPIT");
//        $r = $TQCNX->checkPwdForUser("andrea.3",36);
//        $r = $TQCNX->report_ShellMode();
        
//        $r = $TQCNX->AutoCnx_OperExists("211kaahla61","8e80d6c3-c236-4445-b7c4-c92ffffe76b1");
//        $r = $TQCNX->AutoCnx_OperExists_With_Id("3j6eo4");
//        $r = $TQCNX->AutoCnx_ActvOperCount("211kaahla61",true);
//        $token = round(microtime(TRUE)*1000);
        $token = NULL;
        /*
        $r = $TQCNX->AutoCnx_StartCookie_inDB("211kaahla61", $token, [
            "compl" => [
                "ssid"      => session_id(),
                "locip"     => 11111111111111,
                "loc_cn"    => NULL,
                "uagent"    => NULL,
            ]
        ]);
        //*/
//        $r = $TQCNX->AutoCnx_Cookie_CloseThis_inDB("3j6eo4");
//        $r = $TQCNX->AutoCnx_Cookie_CloseAll_inDB("211kaahla61");
        
//        $r = $TQCNX->AutoCnx_SetCookie("285aog", "211kaahla61", "7d2788c4-52ce-445d-b77c-882827b131ab", session_id(), "TQR_CALG");
//        $r = $TQCNX->AutoCnx_DelCookie("TQR_CALG");
//        $r = $TQCNX->AutoCnx_CookieExists("TQR_CALG",TRUE);
        
        /*
        $r = $TQCNX->AutoCnx_StartAutoLogIn(session_id(),11111111111111,NULL,NULL,[
            "WITH_COOKIE_MANAGE" => TRUE
        ]);
        //*/
        
//        $txh_src = "Un text simple avec %marqueur% puis deuxième %autre_marqueur%. Enfin le même premier %marqueur%" ;
//        $txh_src = "Bonjour @màRie @marie ceci est #12#toto ## un #test !";
//        $txh_src = "Ceci est un texte contenant des #hashtags : #VoiciMaFemme, je l'ammene à la #Maire#ToujoursAvecAmour #Amour-1 #Amour2 #Amour_3 #5Papa #a5 #_azerty #5 #a";
//        $txh_src = "#TheBoredomKiller : #Temoignage #test #NoUsertag #injection #sécurité <span style='color: red'>Injection HTML</span> <script>alert(\"Injection réussie\");</script> ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż";
        $txh_src = "#TheBoredomKiller : #Temoignage #test #NoUsertag #injection #s&eacute;curit&eacute; &lt;span style='color: red'&gt;Injection HTML&lt;/span&gt; &lt;script&gt;alert(&quot;Injection r&eacute;ussie&quot;);&lt;/script&gt; &Agrave;&Aacute;&Acirc;&Atilde;&Auml;&Aring;&AElig;&agrave;&aacute;&acirc;&atilde;&auml;&aring;&aelig;ắạặằả&THORN;&szlig;&thorn;&Ccedil;&ccedil;ĐđƉɖ&ETH;&eth;&Egrave;&Eacute;&Ecirc;&Euml;&egrave;&eacute;&ecirc;&euml;ềệĘę&Igrave;&Iacute;&Icirc;&Iuml;&igrave;&iacute;&icirc;&iu";
        $TXH = new TEXTHANDLER();
//        $r = $TXH->ExtractAllDmd($txh_src);
//        $r = $TXH->ReplaceDmd("marqueur","MARQUEUR",$txh_src);
//        var_dump(__FUNCTION__,__LINE__,"ORILENGTH => ".strlen($txh_src));
//        $r = $TXH->strlen_ship_tagsmarks($txh_src,['#',"@"]);
//        $r = $TXH->extract_prod_keywords($txh_src);
//        $r = $TXH->extract_tqr_usertags($txh_src);
//        array_walk($r[1],function(&$i,$k){
//            $TXH = new TEXTHANDLER();
//            $i = strtolower($TXH->remove_accents($i));
//        });
//        $r = $TXH->ExtractURLs("trenqr.com //trenqr.com <<<.trenqr.com ;trenqr.com <span style=\"color:red;\">ceci est rouge</span> <script>alert(\"HACK : Besoin de courage !\");</script> Un message avec plusieurs liens : www.trenqr.com; http://trenqr.com //trenqr.com trenqr.me; trenqr.com .trenqr.com et un hack &lt;script&gt;alert('123456789')&lt;/script&gt; trenqr.me/mouna <a href=\"http://trenqr.me/mouna\">trenqr.me/,</a> trenqr.me?toto");
//        $r = $TXH->ExtractURLs("//trenqr.com <<<.trenqr.com ;trenqr.com <span style=\"color:red;\">ceci est rouge</span> <script>alert(\"HACK : Besoin de courage !\");</script>");
//        $r = $TXH->ExtractURLs("Un message avec plusieurs liens : www.trenqr.com; http://trenqr.com //trenqr.com trenqr.me; trenqr.com .trenqr.com et un hack &lt;script&gt;alert('123456789')&lt;/script&gt;");
//        $r = $TXH->ExtractURLs("Bonjour trenqr adresse email : lc@deuslynn.com");
//        $r = $TXH->ExtractURLs("Bonjour trenqr.me/@marie. Et de deux trenqr.me/@marie !");
//        $r = $TXH->replace_emojis_in("#Commentaire 4 : Un texte de test #hashtag #hashtag (x2) PAS DE USERTAGS http://www.trenqr.com 🤔 EOF &#x1F603; &amp;#x1F603; #trenqrvb3 #TrenqrAladin 😡😜");
        
//        $REDIR = new REDIR();
        $args_redir = [
            "user" => "lou"
        ];
//                (?:[^(?:www\.)])|(?:[^(?:http:\/\/)])
//        $r = $REDIR->redir_build_scoped_url("TMLNR_GTPG_RO",$args_redir);
        
        $EMH = new EMAILAC_HANDLER();
//        $r = $EMH->emac_acquire_emtab("emdl_recpwdn1","fr");
        $args_eml = [
            "exp"       => "noreply@trenqr.com",
            "rcpt"      => "lou.carther@deuslynn-entreprise.com",
//            "rcpt_uid" => NULL,
            "object"    => "Test envoi de email",
            "catg"      => "USER_ACTION"
        ];
        $args_eml_marks = [
            "fullname"  => "Dupont A.", 
            "pseudo"    => "DpA",
            /*
            // emdl_delacccnfn1
            "reasons" => "Une raison",
            "date" => "00/00/000",
            //*/
            "trenqr_http_root"          => HTTP_RACINE,
            "trenqr_login_link"         => HTTP_RACINE."/login",
            "trenqr_start_rcvy_link"    => HTTP_RACINE."/recovery/password",
            "trenqr_prod_img_root"      => WOS_SYSDIR_PRODIMAGE
        ];
//        $r = $EMH->emac_send_email_via_model("emdl_toshelledn1", "fr", $args_eml, $args_eml_marks);
//        $args_eml_marks = ["marqueur" => "MARQUEUR", "test" => "TEST"];
//        $r = $EMH->emac_send_email_via_model("emdl_sample", "fr", $args_eml, $args_eml_marks);
        
        $FTPH = new FTP_HANDLER("localhost");
        $ftp_path = "/marge1/tqim/article/211kaahla61";
//        $r = $FTPH->ftp_dir_exists($ftp_path);
//        $r = $FTPH->ftp_file_exists($ftp_path);
//        $r = $FTPH->ftp_delete_file($p);
//        $r = $FTPH->ftp_rename_file($p, "toto.png");
//        $r = $FTPH->ftp_create_file($p, $fp);
        
        $TQR = new TRENQR();
        $args_bgzy = [
            "accid"         => 36,
            "bgzy_type"     => "BGTYP_CNX",
            "bgzy_where"    => "Quelque part",
            "bgzy_when"     => "hier",
            "bgzy_message"  => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. In dapibus mi quam, eu efficitur felis pharetra non. Curabitur commodo mi vel leo massa nunc.",
            "bgzy_lang"     => "fr",
            "bgzy_url"      => "r",
            "bgzy_scrn_w"   => "1366",
            "bgzy_scrn_h"   => "768",
            "ssid"          => session_id(),
            "srvip"         => sprintf('%u', ip2long($_SERVER["SERVER_ADDR"])),
            "srvname"       => $_SERVER["SERVER_NAME"],
            "user_agent"    => $_SERVER["HTTP_USER_AGENT"],
            "locip"         => ip2long($_SERVER["REMOTE_ADDR"])
        ];
        $args_rcmd = [
            "rcmd_aeid"     => "",
            "rcmd_sfn"      => "",
            "rcmd_sml"      => "",
            "rcmd_rfn"      => "",
            "rcmd_rml"      => "",
            "g-recaptcha-response" => "",
            "rcmd_ssid"     => "",
            "rcmd_curl"     => "",
            "rcmd_locip"    => "",
            "rcmd_locip_num" => "",
            "rcmd_uagent"   => ""
        ];
//        $r = $TQR->ReportBug($args_bgzy);
//        $r = $TQR->setPreferences(36,"_PFOP_TIABT_INR","_DEC_DSMA");
//        $r = $TQR->setPreferences(106,"_PFOP_FSTCNX","_DEC_DSMA");
//        $r = $TQR->getPreferences(106);
//        $r = $TQR->getPreferences(106,"_PFOP_FSTCNX");
//        $r = $TQR->getPreferences(106,"_PFOP_PSMN_EMLWHN_NW");
//        $r = $TQR->rcmd_new($args_rcmd);
        /************************ SUGGESTION *************************/
//        $r = $TQR->sugg_GetChoosenProfils (NULL,NULL,1);
//        $r = $TQR->sugg_GetChoosenProfils ("211kaahla61");
//        $r = $TQR->sugg_GetChoosenProfils ();
//        $r = $TQR->sugg_GetChoosenTrends();
//        $r = $TQR->sugg_GetChoosenTrends("203mboi",NULL,1,TRUE);
//        $r = $TQR->sugg_GetChoosenTrends(NULL,NULL,1,TRUE);
//        $r = $TQR->sugg_GetChoosenAny("211kaahla61",["W_FEO"]);
        /************************ LAST ACTIVITY ***********************/
//        $r = $TQR->lasta_GetLastActivities("211kaahla61","12aoka10155",NULL,TRUE);
//        $r = $TQR->lasta_GetLastActivities_Network("211kaahla61",NULL,TRUE);
        /*
        $r = $TQR->lasta_GetLastActivities_Network_Newer("119oej47bk36",[
            "ARE" => [
                "refid" => NULL,
                "reftm" => NULL,
            ],
            "ALI" => [
                "refid" => NULL,
                "reftm" => NULL,
            ],
            "AFV" => [
                "refid" => NULL,
                "reftm" => NULL,
            ],
            "TSM" => [
                "refid" => NULL,
                "reftm" => NULL,
            ],
            "TSR" => [
                "refid" => NULL,
                "reftm" => NULL,
            ],
            "TSL" => [
                "refid" => NULL,
                "reftm" => NULL,
            ],
        ],NULL,TRUE);
        //*/
        $args_pdr = [
            "author"    => "102", //8n3i3n2n1l4n31,046792211948
            "text"      => "Un texte de test #hashtag @lou http://www.trenqr.com 😱 EOF", 
            "locip"     => sprintf('%u', ip2long($_SERVER["SERVER_ADDR"])),
            "ssid"      => 11111111111111, 
            "uagent"    => $_SERVER["HTTP_USER_AGENT"]
        ];
//        $r = $TQR->pdreact_add($args_pdr["author"],$args_pdr["text"],$args_pdr["locip"],$args_pdr["ssid"],$args_pdr["uagent"]);
//        $r = $TQR->pdreact_exists_with_id(3);
//        $r = $TQR->pdreact_exists("467b3o3");
//        $r = $TQR->pdreact_read("4606fo1");
        
        $CHBX = new CHATBOX();
//        $r = $CHBX->Search(71,"tes*");
//        $r = $CHBX->Search(71,"era*",null,TRUE);
//        $r = $CHBX->Search(71,"tes*",null,TRUE);
        
        $CBCONV = new CHBX_CONVRS();
        $args_new_convrs = [
            "conv_acteid"   => "8n3i3n2n1l4n31", 
            "conv_tgteid"   => "4n3g4n1n1l4n32", //8n3i3n2n1l4n31,046792211948
            "fetime"        => round(microtime(TRUE)*1000),
            "conv_locip"    => sprintf('%u', ip2long($_SERVER["SERVER_ADDR"])),
            "conv_useragt"  => $_SERVER["HTTP_USER_AGENT"], 
            "conv_fmsg"     => "Mon premier message avec l'utilisateur 48. Une nouvelle conversation est en cours",
            "load_anyway"   => TRUE
        ];
//        $r = $CBCONV::$_WAIT_MSGS_FOR;
//        $r = $CBCONV->exists("795e0o7gf",TRUE);
//        $r = $CBCONV->exists_with_id(1);
//        $r = $CBCONV->on_create_entity($args_new_convrs);
//        var_dump(is_array($r), count($r), strtoupper($r[0]) === "ALRDY");
//        $r = $CBCONV->onread_lastestmessage(1);
//        $r = $CBCONV->on_read_entity(["conv_eid"=>"7e8a0o1"]);
//        $r = $CBCONV->onread_FirstMessages(102,"9h13d4ao3k",TRUE,TRUE);
//        $r = $CBCONV->onread_FirstMessagesFrom(71,"7e8a0o1","chgco10","top",FALSE,TRUE);
//        $r = $CBCONV->onread_FirstMessagesFrom(71,"7e8a0o1","cea4om",null,TRUE,TRUE);
//        $r = $CBCONV->onread_ListFirstConvrs("4n3g4n1n1l4n32");
//        $r = $CBCONV->onread_UserSts("4n3g4n1n1l4n32");
//        $r = $CBCONV->ondelete_delForMe("12cceo4","046792211948");
//        $r = $CBCONV->ondelete_delForMe("7e8a0o1","8n3i3n2n1l4n31");
//        $r = $CBCONV->ondelete_delForMe("7e8a0o1","4n3g4n1n1l4n32");
//        $r = $CBCONV->onread_ListFirstConvrs("8n3i3n2n1l4n31");
//        $r = $CBCONV->onread_ListFirstConvrs("4n3g4n1n1l4n32");
//        $r = $CBCONV->onread_ListFirstConvrs("4n3g4n1n1l4n32",NULL,NULL,TRUE);
//        $r = $CBCONV->onread_ListFromConvrs("4n3g4n1n1l4n32","7e8a0o1","bot",NULL,NULL,TRUE);
        
        $CBMSG = new CHBX_MSG();
        $args_new_cbmsg = [
            "conv_eid"  => "7e8a0o1",
            "message"   => "Un message pour ..., dernier dans la liste ".round(microtime(TRUE)*1000), 
            "act_eid"   => "4n3g4n1n1l4n32", 
            "tgt_eid"   => "8n3i3n2n1l4n31", 
//            "act_eid" => "8n3i3n2n1l4n31", 
//            "tgt_eid" => "4n3g4n1n1l4n32", 
            "fetime"    => round(microtime(TRUE)*1000),
            "locip"     => sprintf('%u', ip2long($_SERVER["SERVER_ADDR"])), 
            "useragt"   => $_SERVER["HTTP_USER_AGENT"]
        ];
//        "chmsg_msg_hashs","chmsg_msg_ustgs"
//        $r = $CBMSG->on_create_entity($args_new_cbmsg);
//        $r = $CBMSG->on_read_entity(["chmsgid"=>3963]);
//        $r = $CBMSG->on_read_entity(["chmsg_eid"=>"795e0o7gf"]);
//        $r = $CBMSG->on_delete_entity("77396ob");
//        $r = $CBMSG->ondelete_delForMe("chgco10","4n3g4n1n1l4n32");
//        $r = $CBMSG->ondelete_delForMe("cea4om","4n3g4n1n1l4n32");
        
//        $r = $CBCONV->ondelete_delForMe("7e8a0o1","4n3g4n1n1l4n32");
//        $r = $CBMSG->onread_UnreadGet(102,"9h13d4ao3k",["ONLYTHIS"]);
//        $r = $CBMSG->onread_UnreadGet(102,NULL,["GROUPBY"]);
        $args_upd = [
            "ids"       => ["db91o79e","d52lo79g"],
            "rd"        => 1111111111111,
            "ssid"      => "111111111111111111111",
            "locip"     => ip2long($_SERVER["REMOTE_ADDR"]),
            "uagnt"     => $_SERVER["HTTP_USER_AGENT"]
        ];
//        $r = $CBMSG->onalter_UnreadUpd($args_upd["ids"],$args_upd["rd"],$args_upd["ssid"],$args_upd["locip"],$args_upd["uagnt"]);
//        $r = $CBMSG->onread_GetUrlics("gc84o7b7",TRUE);
        
        
        $PM = new POSTMAN();
//        $r = $PM->UserActyLog_FeedTestDatas(106,1); //TSTY_TSM
//        $r = $PM->UserActyLog_FeedTestDatas(106,1); //TSTY_TSR
//        $r = $PM->UserActyLog_FeedTestDatas(106,1); //TSTY_TSL
//        $r = $PM->UserActyLog_FeedTestDatas(102,371,1106,1); //USTG_TSM
//        $r = $PM->UserActyLog_FeedTestDatas(102,394,1107,1); //USTG_TSR
//        $r = $PM->UserActyLog_FeedTestDatas(106,215,300,1); //REL_ABO_FOLW
//        $r = $PM->UserActyLog_FeedTestDatas(102,47,901,1); //TRD_ABO_FOLW
//        $r = $PM->UserActyLog_FeedTestDatas(102,75,803,1); //ART_FAV
        /* ARTICLE FAV SCOPE */
//        $r = $PM->UAL_onexists_fav_art(106,1);
//        $r = $PM->onexistscreate_fav_art(106,1,TRUE);
        /* USER RELATION SCOPE */
//        $r = $PM->UAL_onexists_relation_followers(102,1);
//        $r = $PM->onexistscreate_relation_followers(102,1,TRUE);
        /* TREND ABO SCOPE */
//        $r = $PM->UAL_onexists_trend_followers(106,1);
//        $r = $PM->onexistscreate_trend_follower(106,1,TRUE);
        /* ARTICLE REACTION SCOPE */
//        $r = $PM->UAL_onexists_reactions(102,1);
//        $r = $PM->onexistscreate_reactions(70,1,TRUE);
        /* USERTAGY SCOPE */
//        $r = $PM->UAL_onexists_usertags(106, 1);
//        $r = $PM->onexistscreate_usertags(106,1,TRUE);
        /* TESTY SCOPE */
//        $r = $PM->UAL_onexists_testy(106,1);
//        $r = $PM->onexistscreate_testy(106,1,TRUE);
//        $r = $PM->UAL_onexists_testy_reactions(106,1);
//        $r = $PM->onexistscreate_testy_reactions(102,1,TRUE);
//        $r = $PM->UAL_onexists_testy_likes(106,1);
//        $r = $PM->onexistscreate_testy_like(102,1,TRUE);
        /* NOTIFICATIONS SCOPE */
//        $r = $PM->onread_NtfyNewest(106,2,TRUE);
//        $r = $PM->onread_NtfyNewest(106,2,TRUE);
//        $r = $PM->onread_NtfyFrom(70);
//        $r = $PM->onread_AllUnRgrGrpCount(102,["test_force_null_datas"=>TRUE]);
//        $r = $PM->onread_NtfyNewest(70, 2, TRUE);
//        $r = $PM->onread_AllUnRgrGrpCount(70);
        $plds = [
            [
                "i" => "3fkdlo1",
//                "t" => "azerty"
                "t" => (string)round(microtime(TRUE)*1000)
            ],[
                "i" => "3fkdko2",
//                "t" => "azerty"
                "t" => (string)round(microtime(TRUE)*1000)
            ],[
                "i" => "3fkdko3",
//                "t" => "azerty"
                "t" => (string)round(microtime(TRUE)*1000)
            ],[
                "i" => "3fkdko4",
//                "t" => "azerty"
                "t" => (string)round(microtime(TRUE)*1000)
            ],[
                "i" => "3fkdko5",
//                "t" => "azerty"
                "t" => (string)round(microtime(TRUE)*1000)
            ],[
                "i" => "3fkdko6",
//                "t" => "azerty"
                "t" => (string)round(microtime(TRUE)*1000)
            ]
        ];
//        $r = $PM->onupdate_ntfyPulleds(70, $plds);
//        $r = $PM->onupdate_ntfyRogers(70, $plds);
        $args_save_passive = [
            "uid"           => 102,
            "ssid"          => session_id(),
            "locip_str"     => $_SERVER["SERVER_ADDR"],
            "locip_num"     => sprintf('%u', ip2long($_SERVER["SERVER_ADDR"])),
            "useragt"       => ( !empty($_SERVER["HTTP_USER_AGENT"]) && is_string($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : "",
            "wkr"           => "UN_WORKER",
            "fe_url"        => "http://127.0.0.1/dev.trenqr.com/lou&as=chezmoi",
            "srv_url"       => "http://127.0.0.1/dev.trenqr.com/lou&as=chezmoi",
            "url"           => "http://127.0.0.1/dev.trenqr.com/lou&as=chezmoi",
            "isAx"          => 1,
            "refobj"        => 102,
            "uatid"         => 722,
            "uanid"         => 2,
            "ispasv"        => TRUE,
            "reflib"        => "REFLIB",
            "remarks"       => "MY_REMARK"
        ];
//        $r = $PM->UserActyLog_Set_MdPsv($args_save_passive);
//        $r = $PM->NotifEmail_CheckNAct(2194,1106);
        
        
        $fvlk_new = [
            "accid"         => "71",
            "acc_eid"       => "4n3g4n1n1l4n32",
            "fav_title"     => "Titre de Favlink pour la phase de test unitaire",
            "fav_url"       => "beta.trenqt.xyz",
            "fav_desc"      => "Une description simple",
            "fav_catg"      => "catg_pro",
            "fav_ssid"      => "11111111111111111111",
            "fav_curl"      => "http://127.0.0.1/dev.trenqr.com/mouna&as=chezmoi",
            "fav_locip"     => sprintf('%u', ip2long($_SERVER["SERVER_ADDR"])),
            "fav_uagent"    => $_SERVER["HTTP_USER_AGENT"]
        ];
        $fvs_new = [
            "fvs_fav_aeid"  => "4n3g4n1n1l4n32",
            "fvs_fav_eid"   => "7fbbjo2",
            "fvs_ssid"      => "11111111111111111111",
            "fvs_curl"      => "http://127.0.0.1/dev.trenqr.com/mouna&as=chezmoi",
            "fvs_locip"     => sprintf('%u', ip2long($_SERVER["SERVER_ADDR"])),
            "fvs_uagent"    => $_SERVER["HTTP_USER_AGENT"]
        ];
        $FVLK = new FAVLINLK();
        // ---> FAVLINK
//        $r = $FVLK->on_create_entity($fvlk_new);
//        $r = $FVLK->exists("7fbbjo2");
//        $r = $FVLK->exists_with_id(2);
//        $r = $FVLK->on_read_entity(["fav_eid" => "7fbbjo2"]);
//        $r = $FVLK->onread_get_eid_from_id("2");
//        $r = $FVLK->onread_get_id_from_eid("7fbbjo2");
//        $r = $FVLK->onread_pull_favs_first(71);
//        $r = $FVLK->onread_pull_favs_first(71,"catg_news");
//        $r = $FVLK->onread_pull_favs_from("7fbbjo1",1444214371539,"top","catg_pro");
//        $r = $FVLK->onread_pull_favs_from("7fbbjo2",1444214744257,"btm");
//        $r = $FVLK->on_delete_entity("7fbbjo5");
        // --->  VISITES
//        $r = $FVLK->visit_new($fvs_new,TRUE);
//        $r = $FVLK->visit_exists("7fbbjo2");
//        $r = $FVLK->visit_exists_with_id(6);
//        $r = $FVLK->visit_onread("7fbbjo2");
//        $r = $FVLK->visit_ondelete(4);
//        $r = $FVLK->visit_ondelete_all(2);
//        $r = $FVLK->onread_totLinksnb(71);
        
        $S_RCPT = new SRVC_ReCaptcha("key_site", "key_secret");
//        $r = $S_RCPT->checkResponse("123456789",NULL,["ssl_verifypeer" => FALSE,"get_json_object" => TRUE]);
//        $r = $S_RCPT->getHtml(NULL,["data-theme"=>"dark "]);
        
        $tst_new = [
            "ouid"      => "211kaahla61", //102
            "tguid"     => "12aoka10155", //106
//            "msg"       => round(microtime(TRUE)*1000)." : (@Lou) Ceci est un message utilisé pour les tests. #PrayForParis #TheBoredomKiller. Par @Lou > @Mouna; @Marie www.blackowlrobot.com ,un autre lien : http://trenqr.us",
//            "msg"       => "#TheBoredomKiller : #Temoignage #test #NoUsertag #injection #sécurité <span style='color: red'>Injection HTML</span> <script>alert(\"Injection réussie\");</script> ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż",
//            "msg"       => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam massa ipsum, auctor id ornare quis, molestie non leo. Ut quis varius diam, eu posuere.",
            "msg"       => "Bonjour tout le monde. Ô habitants de la Terre à la Lune. Je m'appelle ET 😳😆 , é vous ? Je veux dire et voûs ? # 😳 @😳",
            "ssid"      => "1111111111111111",
            "locip"     => sprintf('%u', ip2long($_SERVER["SERVER_ADDR"])),
            "uagent"    => $_SERVER["HTTP_USER_AGENT"]
        ];
        $TST = new TESTY();
//        $r = $TST->on_create_entity($tst_new);
//        $r = $TST->exists("5ia5fo6m");
//        $r = $TST->exists_with_id(2);
//        $r = $TST->exists_with_prmlk("7fbbjo2");
//        $r = $TST->on_read_entity(["tst_eid"=>"6eh02o4d"]);
//        $r = $TST->on_delete_entity("16k3fo2h");
//        $r = $TST->config_get_inis("211kaahla61");
        $sets = [
            "WCADD" => "EVRBDY",
            "WCSEE" => "EVRBDY"
        ];
//        $r = $TST->config_set_inis ($tst_new["ouid"], $sets, $tst_new["locip"], $tst_new["ssid"], $tst_new["uagnt"]);
//        $r = $TST->onread_getTesties("12aoka10155", "FST", NULL, NULL);
//        $r = $TST->onread_getTesties("12aoka10155", "TOP", "7fbbjo1", 1446152996005);
//        $r = $TST->onread_getTesties("12aoka10155", "BTM", "7fbbjo3", 1446153251948);
//        $r = $TST->oncreate_hasPermission("211kaahla61","21ap1g5ba147");
//        $r = $TST->onread_hasPermission("11f19k1hg99", "12aoka10155");
        $dnyfr_new = [
            "ouid"      => "12aoka10155", //106 (mouna)
            "type"      => "WCNTADD",
//            "type"      => "WCNTADD",
            "ssid"      => "1111111111111111",
            "locip"     => sprintf('%u', ip2long($_SERVER["SERVER_ADDR"])),
            "uagent"    => $_SERVER["HTTP_USER_AGENT"],
//            "sets"      => []
            "sets"      => ["marigo","marie"]
//            "sets"      => ["marigo","marie","zorro","lou"]
        ];         
//        $r = $TST->config_set_denyfor ($dnyfr_new["ouid"], $dnyfr_new["type"], $dnyfr_new["locip"], $dnyfr_new["ssid"], $dnyfr_new["uagnt"], $dnyfr_new["sets"]);
//        $r = $TST->config_get_denyfor("12aoka10155");
//        $r = $TST->config_check_denyfor("211kaahla61","21ap1g5ba147","WCNTADD");
//        $r = $TST->onread_AcquiereUsertags_Testy("1edhbo3f",TRUE);
//        $r = $TST->onread_AcquiereHashs_Testy("6eh02o4d");
        /*
         * @Lou > 211kaahla61; @Mouna > 12aoka10155; @Marie > 127mj643af14 } 
         */
//        $r = $TST->Like_Action("127mj643af14", "7ckmko4c", "TST_XA_GOULK", $dnyfr_new["ssid"], $dnyfr_new["locip"], $dnyfr_new["uagnt"]);
//        $r = $TST->Like_HasLiked(101,104);
//        $r = $TST->Like_Count("7ckmko4c");
//        $r = $TST->Pin_Action(102,"211kaahla61","7ckmko4c", "TST_XA_GOPN", $dnyfr_new["ssid"], $dnyfr_new["locip"], $dnyfr_new["uagent"]);
//        $r = $TST->Pin_IsPin(104);
//        $r = $TST->Pin_WhoIsPin(102,TRUE);
        $args_tsr = [
            "author"    => "102", //8n3i3n2n1l4n31,046792211948
            "text"      => "#Commentaire 4 : Un texte de @Lou test #hashtag #hashtag (x2) PAS DE USERTAGS http://www.trenqr.com 🤔 EOF &#x1F603; &amp;#x1F603; #trenqrvb3 #TrenqrAladin 😡😜", 
//            "text"      => "Commentaire 5 : Un texte sans hashtag PAS DE USERTAGS http://www.trenqr.com 🤔 EOF trenqrvb3 😡😜", 
            "locip"     => sprintf('%u', ip2long($_SERVER["SERVER_ADDR"])),
            "ssid"      => 11111111111111, 
            "uagent"    => $_SERVER["HTTP_USER_AGENT"]
        ];
//        $r = $TST->React_Add($args_tsr["author"],"2b53eo56", $args_tsr["text"], "TMLNR", $args_tsr["ssid"], $args_tsr["locip"], $args_tsr["uagnt"], NULL);
//        $r = $TST->React_Read("7493jo4j")["pdrtab"]["usertags"];
//        $r = $TST->React_Read("7493jo4j");
//        $r = $TST->React_Pull('6eh02o4d'); 
//        $r = $TST->Like_Pull("151bjo57","FST",NULL,NULL,TRUE,NULL); 
//        $r = $TST->Like_Pull("151bjo57","TOP","7fbbjo2c",1460836304470,TRUE,NULL); 
        /*
        $r = $TST->React_Pull('48khjo52',"FST",NULL,NULL,TRUE,[
            "cuid" => 106
        ])[0]["pdrtab"];
         //*/
//        $r = $TST->React_Exists("7493jo4j");
//        $r = $TST->React_Exists_With_Id(75);
//        $r = $TST->React_Del('1bhdco15'); 
//        $r = $TST->React_Del_All('6k3e8o7b', 172, false); 
//        $r = $TST->onread_AcquierePrmlk("33gc1o4a");
        
        
        $URLIC = new URLIC();
//        $r = $URLIC->URLIC_exists(1);
        $args_urlic = [
//            "t"     => "Ceci est mon premier texte lc@ondeuslynn.com",
            "t"     => "(2) Ceci est le premier message pour les tests de fonctionnement des URL : www.trenqr.com www.trenqr.com",
//            "t"     => "Ceci est mon premier texte avec plusieurs liens différents : (1) trenqr.me; (2) http://trenqr.com; (3) www.trenqr.fr (4) //trenqr.us; (5) www.trenqr.com/ontrenqr&v=1m1=30; (6) ",
            "uci"   => 459,
            "ucei"  => "7fbbjojm",
            "ucp"   => "UCTP_MI",
//            "ucp"   => "UCTP_ART_IML",
            "ssid"  => "1111111111111111",
            "locip" => "111111111111",
            "curl"  => NULL,
            "uagnt" => NULL
        ];
//        $r = $URLIC->URLIC_oncreate($args_urlic["t"], $args_urlic["uci"], $args_urlic["ucei"], $args_urlic["ucp"], $args_urlic["ssid"], $args_urlic["locip"], $args_urlic["curl"], $args_urlic["uagnt"]);
//        $r = $URLIC->URLIC_onvisit_declare(1,102);
        
        $HVIEW = new HVIEW();
//        $r = $HVIEW->HSH_exists("12hg4o1");
//        $r = $HVIEW->HSH_exists_with_id(1);
//        $r = $HVIEW->HSH_exists_with_hsh("testDelAcc",["GET_COUNT"]);
        $args_hview = [
            "t"     => "#TheBoredomKiller : #Temoignage #test #NoUsertag #injection #sécurité <span style='color: red'>Injection HTML</span> <script>alert(\"Injection réussie\");</script> ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż",
//            "t"     => round(microtime(TRUE)*1000)." : (@Lou) Ceci est un message utilisé pour les tests. #PrayForParis #TheBoredomKiller. Par @Lou > @Mouna; @Marie www.blackowlrobot.com ,un autre lien : http://trenqr.us",
//            "t"     => "Ceci est un texte contenant des #hashtags #HashTags #Hâshtags : #VoiciMaFemme, je l'ammene à la #Maire#ToujoursAvecAmour #Amour-1 #Amour2 #Amour_3 #5Papa #5",
            "hci"   => 459,
            "hcei"  => "7fbbjojm",
            "hcp"   => "HCTP_TESTY",
//            "ucp"   => "UCTP_ART_IML",
            "ssid"  => "1111111111111111",
            "locip" => "111111111111",
            "curl"  => NULL,
            "uagnt" => NULL
        ];
//        $r = $HVIEW->HSH_oncreate($args_hview["t"], $args_hview["hci"], $args_hview["hcei"], $args_hview["hcp"], $args_hview["ssid"], $args_hview["locip"], $args_hview["curl"], $args_hview["uagnt"]);
//        $r = $HVIEW->Search("test",NULL,102,NULL,NULL,10);
//        $r = $HVIEW->Search("TheBoreDomKiller","FST",106,NULL,NULL,10)['c']["TST"];
//        $r = $HVIEW->Search("trenqrthethird","FST",106,NULL,NULL,10)['c']["AITR"];
//        $r = $HVIEW->Search("theboredomkiller","BTM",NULL,"1a8hho3m","1447810130081",10);
//        $r = $HVIEW->SPE_TRANSFERT_HSH();
//        $r = $HVIEW->HSH_MODO_DESC("theboredomkiller","fr");
//        $r = $HVIEW->HSH_BLABLA(102,10);
        
        $MYSM = new MYSTERY();
        $mysm_crea_datas = [
            "ouid"      => 102,
            "text"      => "#TheBoredomKiller : #Temoignage #test #NoUsertag #injection #sécurité <span style='color: red'>Injection HTML</span> <script>alert(\"Injection réussie\");</script> ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż",
            "ssid"      => "1111111111111111",
            "locip"     => "111111111111",
            "curl"      => "URL_PAGE_FROM",
            "reflang"   => "fr",
            "refcnty"   => "fr",
            "refcity"   => 11111111111,
            "uagent"    => null,
        ];
//        $r = $MYSM->on_create_entity($mysm_crea_datas);
//        $r = $MYSM->exists("656h2o6");
//        $r = $MYSM->exists_with_id(6);
//        $r = $MYSM->on_read_entity(["mysm_eid"=>"656h2o6"]);
        /*
        $r = $MYSM->onread_select(102,"SEC_TQR","BTM","BY_LIKES",null,[
            "rfid"      => "53ig2ob",
            "rftm"      => 1458843283199,
            "refvote"   => 0,
            "fe_mode"   => true
        ]);
        //*/
        /***************** DISCLOSES *****************/ 
//        $r = $MYSM->ondisclose_disclose(106,"656h2o6",$mysm_crea_datas["locip"],$mysm_crea_datas["ssid"],$mysm_crea_datas["curl"],$mysm_crea_datas["uagent"]);
//        $r = $MYSM->ondisclose_count(106,"656h2o6");
//        $r = $MYSM->ondisclose_exists_for_user(106,"656h2o6");
//        $r = $MYSM->ondisclose_delall(6);
        /***************** VOTES SCOPE *****************/ 
//        onvote_exists_for_user(106,"656h2o6");
//        $r = $MYSM->onvote_add(104,"5kcago8","VOTE_DOWN",$mysm_crea_datas["locip"],$mysm_crea_datas["ssid"],$mysm_crea_datas["curl"],$mysm_crea_datas["uagent"]);
//        $r = $MYSM->onvote_exists("6ii4ko2");
//        $r = $MYSM->onvote_exists_with_id(2);
//        $r = $MYSM->onvote_sum("4efgbo10");
//        $r = $MYSM->onvote_delthis("6a8hko2");
//        $r = $MYSM->onvote_delall_for_msg("656efo5");
//        $r = $MYSM->onvote_delall_for_user(106);
//        $r = $MYSM->onload_mysm_cnvotes();
//        $r = $MYSM->on_delete_entity("1a8cjo13");
        
        
        $PHTOTK = new PHOTOTHEQUE();
//        $r = $PHTOTK->phototheque(102,"TREND_FOLLOWED","FST",null,4,[
        /*
        $r = $PHTOTK->phototheque(106,"TREND_FOLLOWED","FST",null,null,[
            "rfid"      => "74m25o1e6",
            "rftm"      => 1449423222815,
            "fe_mode"   => true
        ]);
        //*/
//        $r = $PHTOTK->photocount(102,"TREND_ALL");
        
        
        $LOCSRVC = new SRVC_LOCATION();
//        $r = $LOCSRVC->get_timezone_from_city (5368361);
//        $r = $LOCSRVC->get_timezone_diff_from_city(5368361);
//        $r = $LOCSRVC->get_localtime_from_city(5368361,["in_milli"=>FALSE]);
        
        
        $EXPLR = new EXPLORER();
//        $r = $EXPLR->GetDecoraPic();
        /*
        $r = $EXPLR->GetDecoraPic(NULL,NULL,[
            "strict_mode" => TRUE
        ]);
        //*/
         
        /*
        $r = $EXPLR->explorer(106,"SEC_TRD","FST",null,null,[
//            "must_art_rnb" => 0,
//            "must_art_lnb" => 0,
            "rfid"      => "7fbbjo159",
            "rftm"      => 1436633927908,
            "fe_mode"   => true
        ]);
        //*/
        
        $TQR_CNX = new TQR_CONX();
//        $r = $TQR_CNX->IsConnectedLate(106);
//        $r = $TQR_CNX->IsConnectedLate_SsnExsts(102);
//        $r = $TQR_CNX->IsConnectedLate_LateFocusHisto(102);
//        $r = $TQR_CNX->IsConnectedLate_LateActiveHisto(102,TRUE);
//        $r = $TQR_CNX->IsConnectedLate_LatePassiveHisto(102,TRUE);
        
        $FMT = new FRIEND_MEET();
        $fmt_crea_datas = [
            "acuid"     => 102,
            "tguid"     => "106",
//            "date"      => "1471600811000", //NOW
            "date"      => 1471687211000, //CORRECT
            "place"     => "#TheBoredomKiller : Adresse - ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż",
            "guests"    => ["lou","marie","@zorro"],
//            "guests"    => [],
            "message"   => "#hashtag: @lou <span style='color:red'>Injection</span> <script>alert(\"Injection\");</script> ÀÃÄãäåảÞßþÇçĐđƉɖÐðÈÉÊËèéêíîïłÒÓÔõöơởøⱣᵽÙÚÛÜùüựÿÝýÑñŠŽžż",
            "ssid"      => "1111111111111111",
            "locip"     => "111111111111",
            "uagent"    => null,
        ];
//        $r = $FMT->on_create_entity($fmt_crea_datas);
//        $r = $FMT->exists("5de30o1");
//        $r = $FMT->exists_with_id(1);
//        $r = $FMT->on_read_entity(["fmt_eid"=>"5cj0ko8"]);
//        $r = $FMT->guests_is_guest("5cj0ko8",102,TRUE);
//        $r = $FMT->guests_get_all("5cj0ko8");
//        $r = $FMT->guests_get_allresps("5cj0ko8");
        $r = $FMT->tgresp_set(106,"5cj0ko8",3,$fmt_crea_datas["ssid"],$fmt_crea_datas["locip"],$fmt_crea_datas["uagent"]);
        
        
        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $r,'v_d');
        $end = round(microtime(TRUE)*1000);
        $elp = $end - $start;
        $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["PROCESSING TIME",$elp],'v_d');
        ob_end_flush();
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $arts,'v_d');
        
//        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $ART->getAll_properties(),'v_d');
    }
    
    public function sample4 ($prod_conf) {
        
        $start = round(microtime(TRUE)*1000);
        
        //WITH PROD CONF
        $TQR = new TRENQR($prod_conf);
//        $url = "http://test.trenqr.com/marie/chezmoi";
        $url = "http://trenqr.local.dev/dev.trenqr.com/login?redir_affair=_REDIR_AFTER_LGI&redir_url=http%253A%252F%252Ftrenqr%252Elocal%252Edev%252Fdev%252Etrenqr%252Ecom%252Fhview%252Fq%253Dnewer%2526src%253Dhash";
        $r = $TQR->explode_tqr_url($url);
        
        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $r,'v_d');
        $end = round(microtime(TRUE)*1000);
        $elp = $end - $start;
        $this->presentVarIfDebug(__FUNCTION__,__LINE__, ["PROCESSING TIME",$elp],'v_d');
        
    }
    
    public function test_conx ( $is_conx = TRUE ) {
        session_start();
        var_dump($_SESSION['sto_infos']->getCurrent_ipadd());
        exit();
        
        $CH = new CONX_HANDLER();
        
        $A = new PROD_ACC();
        $A->on_read_entity(["acc_eid" => "4n3g4n1n1l4n32"]);
        
        session_start();
        $SID = session_id();
        
//        var_dump($_SESSION['rsto_infos']);
//        exit();
        
        if ( $is_conx ) {
            $r = $CH->try_login($A, $SID);
            
        } else {
            $r = $CH->try_logout();
        }
        
        if ( true ) {
            var_dump($_SESSION['rsto_infos']);
        }
        
//        var_dump($r, $_SESSION);
//        var_dump($CH->is_connected());
        
    }

    
    public function sample ( $source, $std_err_enabled = FAlSE ) {
        //Put your code here
        $all_accented = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
        
        /*
//        echo strlen($source);
        $s = trim($source);
        $s = preg_replace("/([\s\t\n\x0B\r\-]+)/","-",$s);
        $s = preg_replace("/([^\da-zA-Z\_\-ÀÁÂÃÄÅÆàáâãäåæắạặằảÞßþÇçĐđƉɖÐðÈÉÊËèéêëềệĘęÌÍÎÏìíîïłÒÓÔÕÖØΘòóôõöơởøⱣᵽÙÚÛÜùúûüựÿÝýÑñŠŽžż]+)/",'', $s);
        $s =  preg_replace("/([-]+)/","-",$s);
        echo preg_replace("/([_]+)/","_",$s);
        //*/
        $TH = new TEXTHANDLER();
        
        $r = $TH->extract_prod_keywords($source);
        
        $this->presentVarIfDebug(__FUNCTION__,__LINE__, strlen($source),'v_d');
        
        $this->presentVarIfDebug(__FUNCTION__,__LINE__, $r,'v_d');
        
    }
    
    public function test_emld () {
        
    }
    
}

//*
//SANDBOX::test_queries();
//*/
//*
//$source2 = "J'aÎme  / à@@sdsds...>>>>>... 6864  les mots----è'rkl--clés les ---_ erreurs. bal__bla ___ ";
//$source2 = "J'ắấime les titres mơts-clés\" sans  ˧˩˨ les _ erreurs.  Θ";

//$source = ["artid"=>"sdfsd","cueid"=>"0304"];

$file = RACINE."/public/tempImg/ri_anim.gif";
$img_string = "data:image/png;base64,".base64_encode(file_get_contents($file));

$source3 = [
    "cueid" => "sdfsd",
    "art_desc" => "J'''aime les \"titres\"\" [mots-clés]\ \\sans\, , ï & les _ erreurs. / //",
    "art_locip" => "154654554",
    "art_pdpic_string" => $img_string
    ];

/*
define("WOS_PATH_DVT_STRUCT_REPOS", RACINE."/product/view/repos/dvt/");
define("WOS_PATH_DVT_DEF_FILE", RACINE."/product/view/def/dvt/def.dvt.def.xml");
//Il faut completer ave /{lang}/WOS_PATH_DECO_DEF_FILE
define("WOS_PATH_TO_DEF_FILE_REPOS", RACINE."/product/data/text/");
define("WOS_DECO_DEF_FILE", RACINE."def.deco.def.xml");
//*/

function acquireProdTidy($entry)
{
    //18-09-13 : Pour l'instant on ne refactorise pas cette fonction comme pour les autres qui consistent toutes à recupérer un XmlScope. (Voir XMLTools)
    $code_err = EXC_ABORT;

    //We begin by checking if the param is a file.
    if( isset($entry) )
    {
        $xml_tools = new MyXmlTools();

        $dom = $xml_tools->checkXmlFileInTripleAction($entry);
        //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $dom);
        if ( isset($dom) and is_object($dom))
        {
            $var="";
            //TODO : Pour récupérer le nom du produit on extrait du nom de domaine le nom du produit. Ex : wwww.trenqr.com => trenqr
            $var= MyXmlTools::recursFinderIntoArray($dom->getElementById("trenqr"), $var);
            //var_dump($toto);
            if( is_array($var) and count($var)>1 )
            {
                return $var;
            }
        } else return FALSE;
    } else return FALSE;
    return $code_err;
}
//var_dump(RACINE."/system/conf/conf.prod.conf.xml");
$prod_conf = acquireProdTidy(RACINE."/system/conf/conf.prod.conf.xml");

$SD = new SANDBOX();

/*
$SD->test_queries();
exit();
 //*/

//$source = "##################################################################################################################################################################################################################################";
//$source = "#azerty #toto azerty lamantin";
/*
$source = "J'''aime les \"#titres\"\" [mots-clés]\ \\sans\, , ï & les _ erreurs. / //. J''aÎme  / à@@sdsds...>>>>>... 6864  les mots----è'rkl--clés les ---_ erreurs. bal__bla ___ ";
var_dump($source);

$entry = htmlentities($source);
var_dump(htmlentities($entry));

$out = html_entity_decode($entry);
var_dump($out);
//*/

//$r = $SD->sample($source);

//$r = $SD->sample2($source);

$r = $SD->sample3();

//$r = $SD->sample4($prod_conf);

//$r = $SD->test_emld();

//$r = $SD->test_conx();
/*
$file = RACINE."/public/tempImg/pfl_bb_pic.jpg";
var_dump(strlen(file_get_contents($file)));
$b64 = base64_encode(file_get_contents($file));
var_dump(strlen($b64));
var_dump(strlen(base64_decode($b64)));
*/


?>