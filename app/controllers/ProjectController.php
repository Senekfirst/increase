<?php

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
		
		$this->jquery->compile($this->view);
	}
	
}