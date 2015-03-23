<?php

use Phalcon\Mvc\View;
use Ajax\bootstrap\html\HtmlPanel;
use Ajax\bootstrap\html\base\CssRef;

class ProjectController extends ControllerBase {
	
	public function indexAction()
	{
	
	}

	
	public function messagesAction($id) {
		$bootstrap=$this->jquery->bootstrap();
		$currentProject = Projet::findFirstByid($id);
		$messages = $currentProject->getMsgs();
		
		$count = 0;
		$zonesPanel = array();
		$retour = "";
		foreach($messages as $message){
			if($message->getIdFil() == NULL) {
				$zonesPanel[$count] = $this->recursivePanel($message, $messages, 0);
				$count++;
			}
		}
		
		$this->view->setVars(array('panels' => $zonesPanel));
		$this->jquery->compile($this->view);
		$this->view->disableLevel(View::LEVEL_MAIN_LAYOUT);
	}
	
	private function recursivePanel ($msg, $arrayMsgs, $offset) {
		$panel = '<div class="panel panel-primary col-md-offset-' . $offset . '">
						<div class="panel-heading" onClick="document.getElementById(\'body' . $msg->getId() . '\').style.display === \'block\'? document.getElementById(\'body' .$msg->getId() . '\').style.display=\'none\' : document.getElementById(\'body' . $msg->getId() . '\').style.display=\'block\'">
							<h3 class="panel-title"> ' . $msg->getObjet() . ' - ' . $msg->getDate() . ' - ' . $msg->getUser()->getIdentite() . '</h3>
						</div>
						<div class="panel-body" style="display:none" id="body' . $msg->getId() . '">' . $msg->getContent() . '</br><button class="btn btn-primary">Repondre</button></div>
					  </div>';
		foreach($arrayMsgs as $currentMsg) {
			if($currentMsg->getIdFil() == $msg->getId()) {
				$panel .= $this->recursivePanel($currentMsg, $arrayMsgs, $offset+1);
			}
		}
		return $panel;
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