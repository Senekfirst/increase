<?php
use Phalcon\Mvc\View;

class UserController extends ControllerBase {
	
	public function indexAction()
	{
	
	}
	
	public function projectsAction() {
		$user = $this->session->get('user');
		$currentUser = User::findFirstByid($user->getId());
		$projets = $currentUser->getProjets();
		$bootstrap=$this->jquery->bootstrap();
		foreach($projets as $projet){
			$bouton = $bootstrap->htmlButton("btProjet".$projet->getId(),"Ouvrir...","btn-primary");
			$bouton->onClick($this->jquery->getDeferred("user/project/".$projet->getId(),"#contenu"));
		}
		
		$this->view->disableLevel(View::LEVEL_MAIN_LAYOUT); //Ici on coupe la vue venant du dessus, seule cette partie nous intéresse
		
		$this->jquery->compile($this->view);
		$this->view->setVars(array('user' => $currentUser, 'projets' => $projets));
	}
	
	public function projectAction($id) {
		$currentProject = Projet::findFirstByid($id);
		$messages = $currentProject->getMsgs();
		$client = $currentProject->getUser();
		$useCase = $currentProject->getUsecases();
		
		$this->jquery->get("project/equipe/".$id, "#detailProject");
		
		$bootstrap=$this->jquery->bootstrap();
		$bouton = $bootstrap->htmlButton("btMasquer","Messages...","btn-primary");
		$bouton->onClick($this->jquery->getDeferred("project/messages/".$id,"#ZoneMessages"));
		
		$boutonII = $bootstrap->htmlButton("btFermer","Fermer le projet","btn-default");
		$boutonII->onClick($this->jquery->getDeferred("user/projects","#contenu"));
		
		$this->view->disableLevel(View::LEVEL_MAIN_LAYOUT); //Ici on coupe la vue venant du dessus, seule cette partie nous intéresse
		
		$this->jquery->compile($this->view);
		
		$this->view->setVars(array('project' => $currentProject, 'messages' => $messages, 'client' => $client, 'useCases' => $useCase));
	}
	
}