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
			$bouton->onClick($this->jquery->getDeferred("session/end","#divResponse"));
			
			$boutonII = $bootstrap->htmlButton("btProjets","Mes projets","btn-primary");
			$roles = explode(",",$user->getRole()); //On récupère les rôles de l'user, comme il peut en avoir plusieurs d'après la base de données on les explode
			$tabRoles = array();
			foreach($roles as $role){
				$tabRoles[$role] = true;
			}
			if (isset($tabRoles['author'])){
				$boutonII->onClick($this->jquery->getDeferred("author/projects","#contenu"));
			} else {
				$boutonII->onClick($this->jquery->getDeferred("user/projects","#contenu"));
			}
			
    	} else {
    		$message = "Non connecté  ";
    		$bouton = $bootstrap->htmlButton("btCoDeco","Se connecter","btn-info");
    		$bouton->onClick($this->jquery->getDeferred("session/index","#divResponse"));
    		$boutonII = $bootstrap->htmlButton("btProjets","Connectez vous pour consulter des projets","btn-danger");
    	}
    	$this->jquery->compile($this->view);
    	$this->view->setVar('message', $message);
    }
}

