<?php

/**
 * SessionController
 *
 * Allows to authenticate users
 */
class SessionController extends ControllerBase
{
    public function indexAction()
    {
    	if (!$this->request->isPost()) {
    		$this->tag->setDefault('email', 'demo@phalconphp.com');
    		$this->tag->setDefault('password', 'phalcon');
    	}
    }
    
    private function _registerSession(User $user)
    {
    	$this->session->set('user', $user);
    }

    public function startAction()
    {
        if ($this->request->isPost()) {

        	$email = $this->request->getPost('email');

            $password = $this->request->getPost('password');

            $user = User::findFirst(array(
                "(mail = :mail: OR identite = :mail:) AND password = :password:",
                'bind' => array('mail' => $email, 'password' => $password)
            ));
            //hash('Sha256', $password)
            
            if ($user != false) {
                $this->_registerSession($user);
                $this->flash->success('Welcome ' . $user->getIdentite());
                
                //On redirige selon le type d'utilisateur
	            $roles = explode(",",$user->getRole()); //On récupère les rôles de l'user, comme il peut en avoir plusieurs d'après la base de données on les explode
				$tabRoles = array();
				foreach($roles as $role){
					$tabRoles[$role] = true;
				}
				
				if (isset($tabRoles['author'])){
					return $this->forward('author/projects/'.$user->getId());
				} else {
					return $this->forward('user/projects/'.$user->getId());
				}
                
            }

            $this->flash->error('Wrong email/password');
        }

        return $this->forward('session/index');
    }

    public function endAction()
    {
        $this->session->remove('user');
        $this->flash->success('A bientôt !');
        return $this->forward('index/index');
    }
}
