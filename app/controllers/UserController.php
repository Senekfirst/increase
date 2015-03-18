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
		
		$this->jquery->get("project/equipe/".$id, "#detailProject");
		
		$bootstrap=$this->jquery->bootstrap();
		$bootstrap->htmlButton("btMasquer","Messages...","btn-primary");
		$this->jquery->getAndBindTo("#btMasquer", "click", "project/messages/".$id, "#ZoneMessages");
		$this->jquery->compile($this->view);
		
		$this->view->setVars(array('project' => $currentProject, 'messages' => $messages, 'client' => $client, 'useCases' => $useCase));
	}
	
}