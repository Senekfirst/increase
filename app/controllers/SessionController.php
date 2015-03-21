<?php

/**
 * SessionController
 *
 * Allows to authenticate users
 */
class SessionController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Sign Up/Sign In');
        parent::initialize();
    }

    public function indexAction()
    {
        
    }
    
    private function _registerSession(Users $user)
    {
    	$this->session->set('user', $user);
    }

    public function startAction()
    {
        if ($this->request->isPost()) {

            $user = $this->request->getPost('user'); //On récupère l'utilisateur

            if ($user != false) {
                $this->_registerSession($user); //On met l'utilisateur en session
            }
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
