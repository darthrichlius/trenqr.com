<?php

abstract class Teamplate_Model {
    
    //protected function fonction_mimification_de_la_structure ($structure);
    
    protected function fonction_de_test_du_template_en_mode_page();

    protected function fonction_de_load_de_la_structure_selon_le_mode_de_visionnage ($human_mode);
    
    protected function build_template ();
    
    protected function display_template ();
}
?>
