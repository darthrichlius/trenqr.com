<?php
/**
 * <p>This file intends to include all the factories in the same time.
 * Note : <i>Factories.Router is not defined here. It is defined in a standalone file to avoid to include the other Factories when it's not necessary.</i>
 * If you want to change the path of any Factory make sure its correct path is define here.</p> 
 */

//Including : Factories.CONTROLLER
require_once RACINE."/system/factories/fact.controller/fact.controller.fact.php";
//Including : Factories.PROCESS
require_once RACINE."/system/factories/fact.process/fact.processor.fact.php";
//Including : Factories.DATA
require_once RACINE."/system/factories/fact.data/fact.data.fact.php";
//Including : Factories.VIEW
require_once RACINE."/system/factories/fact.view/fact.view.fact.php";
//*/
?>