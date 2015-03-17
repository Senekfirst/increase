<?php

class UserController extends ControllerBase {
	
	public function indexAction()
	{
	
	}
	
	public function projectsAction($idUser) {
		$currentUser = User::findFirstByid($idUser);
		$projets = $currentUser->getProjets();
		$this->view->setVars(array('user' => $currentUser, 'projets' => $projets));
	}
	
	public function projectAction($id) {
		$currentProject = Projet::findFirstByid($id);
		$messages = $currentProject->getMsgs();
		$client = $currentProject->getUser();
		$useCase = $currentProject->getUsecases();
		$this->view->setVars(array('project' => $currentProject, 'messages' => $messages, 'client' => $client, 'useCases' => $useCase));
	}
	
}