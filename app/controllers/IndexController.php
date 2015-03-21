<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
    	$user= $this->session->get('user');
    	$bootstrap=$this->jquery->bootstrap();
    	if ($user != false){
    		$message = "Bienvenue ".$user->getIdentite()."  ";
    		$bouton = $bootstrap->htmlButton("btCoDeco","Se déconnecter","btn-primary");
    		//$bouton->onClick($this->forward('session/end'));
    	} else {
    		$message = "Non connecté  ";
    		$bouton = $bootstrap->htmlButton("btCoDeco","Se connecter","btn-info");
    		//$bouton->onClick($this->forward('session/index'));
    	}
    	$this->jquery->compile($this->view);
    	$this->view->setVar('message', $message);
    }

}

