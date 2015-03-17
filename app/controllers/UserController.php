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
	
	public function projectAction($id) {
		$currentProject = Projet::findFirstByid($id);
		$this->view->setVar("project", $currentProject);
		$messages = $currentProject->getMessages();
		$this->view->setVar("messages", $messages);
		$client = $currentProject->getUser();
		$this->view->setVar("client", $client); 
	}
	
}