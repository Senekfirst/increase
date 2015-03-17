<?php

class UserController extends ControllerBase {
	
	public function indexAction()
	{
	
	}
	
	public function projetsAction($idUser) {
		$currentUser = User::findFirstByid($idUser);
		$this->view->setVar("user", $currentUser);
		$projets = $currentUser->getProjets();
		$this->view->setVar("projets", $projets);
		//foreach ($projets as $projet) {
			
		//}
	}
	
}