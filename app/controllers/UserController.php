<?php

class UserController extends ControllerBase {
	
	public function indexAction()
	{
	
	}
	
	public function projectsAction($idUser) {
		$currentUser = User::findFirstByid($idUser);
		$this->view->setVar("user", $currentUser);
		$projets = $currentUser->getProjets();
		$this->view->setVar("projets", $projets);
	}
	
}