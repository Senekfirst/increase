<?php

class AuthorController extends ControllerBase {
	
	public function indexAction()
	{
	
	}
	
	public function projectsAction($idAuthor) {
		$currentUser = User::findFirstByid($idAuthor);
		$useCases = $currentUser->getDeveloppeurs();
		$projets = array();
		foreach($useCases as $useCase){
			if (!isset($projets[$useCase->getProjet()->getID()]))
				$projets[$useCase->getProjet()->getID()] = $useCase->getProjet();
		}
		$this->view->setVars(array('user' => $currentUser, 'projets' => $projets));
	}
	
	public function projectAction($id) {
		$currentProject = Projet::findFirstByid($id);
		$messages = $currentProject->getMsgs();
		$client = $currentProject->getUser();
		$useCase = $currentProject->getUsecases();
		
		$tempIDAuthor = 1; //Pour l'instant on ne peut pas le récupérer
		
		$this->jquery->get("project/author/".$id."/".$tempIDAuthor, "#detailProject");
		
		$bootstrap=$this->jquery->bootstrap();
		$bouton = $bootstrap->htmlButton("btMasquer","Messages...","btn-primary");
		$bouton->onClick($this->jquery->getDeferred("project/messages/".$id,"#ZoneMessages"));
		$this->jquery->compile($this->view);
		
		$this->view->setVars(array('project' => $currentProject, 'messages' => $messages, 'client' => $client, 'useCases' => $useCase));
	}
	
}