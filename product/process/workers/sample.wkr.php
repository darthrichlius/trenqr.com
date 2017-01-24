<?php

/**
 * WORKER correspondant à la page ayant le code = pgidxxxxx1;
 *
 * @author lou.carther.69
 */
class WORKER_pgidtestn1 extends WORKER  {
    
    function __construct() {
        parent::__construct(__FILE__,__CLASS__);
    }

    public function on_process_in() {
        //echo "on_process_in";
    }

    public function on_process_out() {
        //echo "on_process_out";
    }
    
    protected function prepare_params_in_if_exist() {
        
    }

    public function prepare_datas_in() {
        
    }

}

?>
