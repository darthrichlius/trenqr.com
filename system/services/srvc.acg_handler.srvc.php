<?php
/**
 * This class allows to resolve the issue around the volatile datas into db
 * concerning acces_grade. If we decide to change the id or lib, we'll be able to
 * do this very easily withot altering lines in the source.
 *
 * @author lou.carther.69
 */
class ACG_HDLR extends MOTHER {
    private $acg_sna_id;
    private $acg_ftpfl_id;
    private $acg_ora_id;
    private $acg_admin_id;
    private $acg_acc_owner_id;
    private $acg_gw_owner_id;
    private $acg_fav_id;
    private $acg_contact_id;
    private $acg_no_contact_id;
    private $acg_donus_id;
    private $acg_faver_id;
    private $acg_tester_id;
    private $acg_target_id;
    private $acg_mutual_ctt_id;
    private $acg_any_acc_id;
    private $acg_entire_ntwk_id;
    private $acgc_in_welc_id;
    private $acg_gnr_in_welc_id;
    private $acg_an_in_welc_id;
    private $acg_all_visitors_id;
    
    //In vars we only got id but not lib. In this array we got Ids and libs.
    //This vars could be use by the caller to run a particular process. But it is used most of the time inner.
    private $All_Acg_In_Array;
    
    function __construct() {

        //We ensure that until the mother this class is innit.  
        parent::__construct(__FILE__,__CLASS__);
        $this->run();
    }
    
    private function run () {
        $this->load_with_datas();
    }
    
    
    private function load_with_datas () {
        $bdd = new WOS_DATABASE($this->default_dbname);
        $bdd->tryConnection();
        $query = "SELECT * ";
        $query .= "FROM gudb_commons_v01.access_grade ";

        $return = $bdd->executeSimpleQueryWithResult($query);
        $datas = $return->fetch();
       
        if ($datas) {
            do {
                $Acg_Infos[] = [
                  //Simple datas  
                  "acg_id" => $datas['acg_id'],
                  "acg_lib" => $datas['acg_code'],
                ];

            } while ( $datas = $return->fetch() );
            //$this->presentVarIfDebug(__FUNCTION__, __LINE__, $Acg_Infos);
            
            //Fill Vars (I am fed up creating functions after functions.
            //We checking the number of row to avoid a fatal error when the nb of row provided doesn't match what we're excepting
            if( count($Acg_Infos) > 0 and $Acg_Infos == 2 ) { 
                $this->acg_sna_id = $Acg_Infos[0]['acg_id'];
                $this->acg_ftpfl_id = $Acg_Infos[1]['acg_id'];
                $this->acg_ora_id = $Acg_Infos[2]['acg_id'];
                $this->acg_admin_id = $Acg_Infos[3]['acg_id'];
                $this->acg_acc_owner_id = $Acg_Infos[4]['acg_id'];
                $this->acg_gw_owner_id = $Acg_Infos[5]['acg_id'];
                $this->acg_fav_id = $Acg_Infos[6]['acg_id'];
                $this->acg_contact_id = $Acg_Infos[7]['acg_id'];
                $this->acg_no_contact_id = $Acg_Infos[8]['acg_id'];
                $this->acg_donus_id = $Acg_Infos[9]['acg_id'];
                $this->acg_faver_id = $Acg_Infos[10]['acg_id'];
                $this->acg_tester_id = $Acg_Infos[11]['acg_id'];
                $this->acg_target_id = $Acg_Infos[12]['acg_id'];
                $this->acg_mutual_ctt_id = $Acg_Infos[13]['acg_id'];
                $this->acg_any_acc_id = $Acg_Infos[14]['acg_id'];
                $this->acg_entire_ntwk_id = $Acg_Infos[15]['acg_id'];
                $this->acgc_in_welc_id = $Acg_Infos[16]['acg_id'];
                $this->acg_gnr_in_welc_id = $Acg_Infos[17]['acg_id'];
                $this->acg_an_in_welc_id = $Acg_Infos[18]['acg_id'];
                $this->acg_all_visitors_id = $Acg_Infos[19]['acg_id'];

                $this->All_Acg_In_Array = $Acg_Infos;
            } else $this->signalError ("err_sys_l107", __FUNCTION__, __LINE__);
        } else  $this->signalError ("err_sys_l105", __FUNCTION__, __LINE__);
    }
            
            
    public function get_lib_of_given_ac_id ($entry_code) {
        if ( isset($entry_code) and $entry_code != "") {
            foreach ($this->All_Acg_In_Array as $acg) {
                if( $acg['acg_id'] == $entry_code ) return $acg['acg_code'];
            }
            $this->signalError ("err_sys_l326", __FUNCTION__, __LINE__);
        }
    }
    
    
    
    /********************************************************************************************************************************/
    /************************************************ START GETTERS AND SETTERS *****************************************************/
    
    // <editor-fold defaultstate="collapsed" desc="Getters Only because it is forbidden to alter datas by the source">
    public function getAcg_sna_id() {
        return $this->acg_sna_id;
    }

    public function getAcg_ftpfl_id() {
        return $this->acg_ftpfl_id;
    }

    public function getAcg_ora_id() {
        return $this->acg_ora_id;
    }

    public function getAcg_admin_id() {
        return $this->acg_admin_id;
    }

    public function getAcg_acc_owner_id() {
        return $this->acg_acc_owner_id;
    }

    public function getAcg_gw_owner_id() {
        return $this->acg_gw_owner_id;
    }

    public function getAcg_fav_id() {
        return $this->acg_fav_id;
    }

    public function getAcg_contact_id() {
        return $this->acg_contact_id;
    }

    public function getAcg_no_contact_id() {
        return $this->acg_no_contact_id;
    }

    public function getAcg_donus_id() {
        return $this->acg_donus_id;
    }

    public function getAcg_faver_id() {
        return $this->acg_faver_id;
    }

    public function getAcg_tester_id() {
        return $this->acg_tester_id;
    }

    public function getAcg_target_id() {
        return $this->acg_target_id;
    }

    public function getAcg_mutual_ctt_id() {
        return $this->acg_mutual_ctt_id;
    }

    public function getAcg_any_acc_id() {
        return $this->acg_any_acc_id;
    }

    public function getAcg_entire_ntwk_id() {
        return $this->acg_entire_ntwk_id;
    }

    public function getAcgc_in_welc_id() {
        return $this->acgc_in_welc_id;
    }

    public function getAcg_gnr_in_welc_id() {
        return $this->acg_gnr_in_welc_id;
    }

    public function getAcg_an_in_welc_id() {
        return $this->acg_an_in_welc_id;
    }

    public function getAcg_all_visitors_id() {
        return $this->acg_all_visitors_id;
    }

    public function getAll_Acg_In_Array() {
        return $this->All_Acg_In_Array;
    }    
    // </editor-fold>


    /************************************************** END GETTERS AND SETTERS *****************************************************/
    /********************************************************************************************************************************/

}

?>
