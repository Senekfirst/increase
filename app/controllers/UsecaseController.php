<?php
use Phalcon\Mvc\View;

/**
 * SessionController
 *
 * Allows to authenticate users
 */
class UseCaseController extends ControllerBase
{	
    public function tachesAction()
    {
    	$this->view->disableLevel(View::LEVEL_MAIN_LAYOUT); //Ici on coupe la vue venant du dessus, seule cette partie nous intéresse
    }
}
