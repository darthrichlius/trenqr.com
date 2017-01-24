<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
SELECT UNIX_TIMESTAMP(UTC_TIMESTAMP()) + tz.gmt_offset AS local_time
FROM `timezone_timezone` tz JOIN `timezone_zone` z
ON tz.zone_id=z.zone_id
WHERE tz.time_start < UNIX_TIMESTAMP(UTC_TIMESTAMP()) AND z.zone_name='America/Los_Angeles'
ORDER BY tz.time_start DESC LIMIT 1;"
//*/

class SRVC_LOCATION extends MOTHER {
    
    function __construct() {
        parent::__construct(__FILE__, __CLASS__);
    }
    
    /************************************************** PLACES SCOPE **************************************************/
    
    public function get_country_from_city () {
        
    }
    
    /************************************************** TIME SCOPE **************************************************/
    
    public function get_timezone_from_city ($cityid) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cityid]);
        
        $QO = new QUERY("qryl4srvclocn1");
        $params = array ( ":cityid"   => $cityid );
        $datas = $QO->execute($params);
        
        return ( $datas ) ? $datas[0] : NULL;
    }
        
    public function get_timezone_diff_from_city ($cityid, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cityid]);
        
        $datas1 = $this->get_timezone_from_city($cityid);
        if (! $datas1 ) {
            return;
        }
        $tmzn = $datas1["timezone"];
        
        /*
         * ETAPE 
         */
        $QO = new QUERY("qryl4srvclocn2");
        $params = array ( ":tmzn"   => $tmzn );
        $datas2 = $QO->execute($params);
        if (! $datas2 ) {
            return;
        }
                
        return $datas2[0]["timezone"];
    }
    
    public function get_localtime_from_city ($cityid, $_OPTIONS = NULL) {
        $this->check_isset_and_not_empty_entry_vars(__FUNCTION__, __LINE__, [$cityid]);
        
        $datas1 = $this->get_timezone_from_city($cityid);
        if (! $datas1 ) {
            return;
        }
        $tmzn = $datas1["timezone"];
        
        /*
         * ETAPE 
         */
        $QO = new QUERY("qryl4srvclocn3");
        $params = array ( ":tmzn"   =>  $tmzn );
        $datas2 = $QO->execute($params);
        if (! $datas2 ) {
            return;
        }
                
        return ( $_OPTIONS && !empty($_OPTIONS["in_milli"]) && $_OPTIONS["in_milli"] === TRUE ) ? $datas2[0]["local_time"]*1000 : $datas2[0]["local_time"];
    }
    
    
}