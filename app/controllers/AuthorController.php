<?php

class AuthorController extends ControllerBase {
	
	public function indexAction()
	{
	
	}
	
	public function projectsAction() {
		$user = $this->session->get('user');
		$currentUser = User::findFirstByid($user->getId());
		$useCases = $currentUser->getDeveloppeurs();
		$projets = array();
		foreach($useCases as $useCase){
			if (!isset($projets[$useCase->getProjet()->getID()]))
				$projets[$useCase->getProjet()->getID()] = $useCase->getProjet();
		}
		$bootstrap=$this->jquery->bootstrap();
		foreach($projets as $projet){
			$bouton = $bootstrap->htmlButton("btProjet".$projet->getId(),"Ouvrir...","btn-primary");
			$bouton->onClick($this->jquery->getDeferred("user/project/".$projet->getId(),"#containerProjet"));
		}
		
		$this->jquery->compile($this->view);
		$this->view->setVars(array('user' => $currentUser, 'projets' => $projets));
	}
	
	public function projectAction($id) {
		$currentProject = Projet::findFirstByid($id);
		$messages = $currentProject->getMsgs();
		$client = $currentProject->getUser();
		$useCase = $currentProject->getUsecases();
				
		$user= $this->session->get('user');
		
		$this->jquery->get("project/author/".$id."/".$user->getId(), "#detailProject");
		
		$bootstrap=$this->jquery->bootstrap();
		$bouton = $bootstrap->htmlButton("btMasquer","Messages...","btn-primary");
		$bouton->onClick($this->jquery->getDeferred("project/messages/".$id,"#ZoneMessages"));
		
		$this->view->disableLevel(View::LEVEL_MAIN_LAYOUT); //Ici on coupe la vue venant du dessus, seule cette partie nous intéresse
		
		$this->jquery->compile($this->view);
		
		$this->view->setVars(array('project' => $currentProject, 'messages' => $messages, 'client' => $client, 'useCases' => $useCase));
	}
	
}