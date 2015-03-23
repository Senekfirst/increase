<?php

use Phalcon\Mvc\View;
use Ajax\bootstrap\html\HtmlPanel;
use Ajax\bootstrap\html\base\CssRef;

class ProjectController extends ControllerBase {
	
	public function indexAction()
	{
	
	}
	
	/*public function messagesAction($id) {
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
	}*/
	
	/*public function testMessagesAction($id) {
		$bootstrap=$this->jquery->bootstrap();
		$currentProject = Projet::findFirstByid($id);
		$messages = $currentProject->getMsgs();
		
		$count = 0;
		$zonesPanel = array();
		foreach($messages as $message){
			$panel = '<div class="panel panel-primary">
						<div class="panel-heading" onClick="document.getElementById(\'body' . $count . '\').style.display === \'block\'? document.getElementById(\'body' . $count . '\').style.display=\'none\' : document.getElementById(\'body' . $count . '\').style.display=\'block\'">
							<h3 class="panel-title"> ' . $message->getObjet() . '</h3>
						</div>
						<div class="panel-body" style="display:none" id="body' . $count . '">' . $message->getContent() . '</div>
					  </div>';

			$zonesPanel[$count] = $panel;
			$count++;
		}
		
		$this->view->setVars(array('panels' => $zonesPanel));
		$this->jquery->compile($this->view);
	}*/
	
	// Version avec htmlPanel, mais qui ne fonctionne pas...Attention à modifier la vue en conséquence si on remet cette version du la méthode en prod
	public function messagesAction($id) {
	    $bootstrap=$this->jquery->bootstrap();
	    $currentProject = Projet::findFirstByid($id);
	    $messages = $currentProject->getMsgs();
	    
	    $count = 0;
	    $zonesPanel = array();
	    foreach($messages as $message){
	    	$idPanel = "panel" . $count;
	        $panel = new HtmlPanel($idPanel);
	        $panel->setContent($message->getContent());
	        $panel->setStyle(CssRef::CSS_PRIMARY);
	        $panel->addHeaderH("<a id='lnk-" . $idPanel . "' href='#collapse-" . $idPanel . "'>" . $message->getObjet() . "</a>","3");
	        $panel->setCollapsable(false);
	        $panel->compile($this->jquery, $this->view);
	        $zonesPanel[$count] = $panel;
	        $count++;
	    }
	
	    $this->view->setVars(array('panels' => $zonesPanel));
	    $this->jquery->compile($this->view);
	    $this->view->disableLevel(View::LEVEL_MAIN_LAYOUT);
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