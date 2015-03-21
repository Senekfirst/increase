<?php

use Phalcon\Mvc\View;

class ProjectController extends ControllerBase {
	
	public function indexAction()
	{
	
	}
	
	public function messagesAction($id) {
		$bootstrap=$this->jquery->bootstrap();
		$currentProject = Projet::findFirstByid($id);
		$messages = $currentProject->getMsgs();
		
		$count = 0;
		$zonesBtn = array();
		foreach($messages as $message){
			$bootstrap->htmlButton("btMasquer".$count, $message->getUser()->getIdentite() . ' - ' . $message->getObjet(),"btn-primary",$this->jquery->show("#zoneBoutons".$count));
			$zonesBtn[$count] = '<div id="#zoneBoutons'. $count .'">'. $message->getContent() ."</div>";
			$count++;
		}
		
		$this->view->setVars(array('zones' => $zonesBtn));
		$this->view->disableLevel(View::LEVEL_MAIN_LAYOUT); //Ici on coupe la vue venant du dessus, seule cette partie nous intéresse
		$this->jquery->compile($this->view);
	}
	
	public function testMessagesAction($id) {
		$bootstrap=$this->jquery->bootstrap();
		$currentProject = Projet::findFirstByid($id);
		$messages = $currentProject->getMsgs();
		
		$count = 0;
		$zonesPanel = array();
		foreach($messages as $message){
			$panel = $bootstrap->htmlPanel("panel".$count);
			$panel->addHeader($message->getObjet());
			$panel->addFooter($message->getContent());
			$zonesPanel[$count] = $panel;
			$count++;
		}
		
		$this->view->setVars(array('panels' => $zonesPanel));
		$this->jquery->compile($this->view);
	}
	
	public function equipeAction($id){
		$currentProject = Projet::findFirstByid($id);
		$useCases = $currentProject->getUsecases();
		$tab = array();
		$total = 0;
		foreach ($useCases as $useCase) {
			if (!isset($tab[$useCase->getUser()->getIdentite()]))
				$tab[$useCase->getUser()->getIdentite()] = 0;
			$tab[$useCase->getUser()->getIdentite()] += $useCase->getPoids();
			$total += $useCase->getPoids();
		}		
		$this->view->setVars(array('tableau' => $tab, 'totalPoids' => $total));
		$this->view->disableLevel(View::LEVEL_MAIN_LAYOUT); //Ici on coupe la vue venant du dessus, seule cette partie nous intéresse
	}
	
	public function authorAction($idProject, $idAuthor){
		
	}
	
}