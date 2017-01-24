<?php

class THE_HASHER extends MOTHER
{
    /**
     * 
     * @param type $entry_purpose
     * @return type <span>The hashed purpose string.</span>
     */
    public static function hash_to_md5($entry_purpose)
    {
        if( !isset($entry_purpose) ){
            //We ensure that until the mother this class is innit.  
            parent::__construct("$2013-03-01", "2013-03-01", __FILE__, __CLASS__);
            $this->signalError ("err_sys_l00", __FUNCTION__,__LINE__);
        }
        else {
            return md5($entry_purpose);
        }
            
    }
    
    /**
     * <p>Used to hash a string using SHA-1. This is the favorite for hashing.</p>
     * <p>In fact, md5 is not as secured as people can think. SHA_512 seems to be low and guzzler.</p>
     * <p>But if this is very very sensitive datas, and even SHA_512 eats up ressources, use it!</p>
     * @param type $entry_purpose
     * @return type <span>The hashed purpose string.</span>
     */
    public static function hash_to_SHA_1($entry_purpose)
    {
        if( !isset($entry_purpose) ){
            //We ensure that until the mother this class is innit.  
            parent::__construct("$2013-03-01", "2013-03-01", __FILE__, __CLASS__);
            $this->signalError ("err_sys_l00", __FUNCTION__,__LINE__);
        }
        else {
            return sha1($entry_purpose);
        }
    }
    
    public static function hash_to_SHA_512($entry_purpose)
    {
        if( !isset($entry_purpose) ){
            //We ensure that until the mother this class is innit.  
            parent::__construct("$2013-03-01", "2013-03-01", __FILE__, __CLASS__);
            $this->signalError ("err_sys_l00", __FUNCTION__,__LINE__);
        }
        else {
            return hash("sha512" , $entry_purpose);
        }
    }
}
?>
