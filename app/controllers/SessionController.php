<?php
use Phalcon\Mvc\View;

/**
 * SessionController
 *
 * Allows to authenticate users
 */
class SessionController extends ControllerBase
{
	private function javaToPhpSha($str){
		$k=hash("sha256", $str,true);
		$hex_array = array();
		foreach (str_split($k) as $chr) {
			$o=ord($chr);
			if($o>127)
				$o=$o-256;
			elseif ($o<-127)
			$o=$o+256;
			$hex_array[] = sprintf("%02x", $o);
		}
		$key=implode('',$hex_array);
		return $key;
	}
	
    public function indexAction()
    {
    	if (!$this->request->isPost()) {
    		$this->tag->setDefault('email', 'demo@phalconphp.com');
    		$this->tag->setDefault('password', 'phalcon');
    	}
    	$this->view->disableLevel(View::LEVEL_MAIN_LAYOUT); //Ici on coupe la vue venant du dessus, seule cette partie nous intéresse
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
                'bind' => array('mail' => $email, 'password' => $this->javaToPhpSha($password))
            ));
            
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
        $this->view->disableLevel(View::LEVEL_MAIN_LAYOUT); //Ici on coupe la vue venant du dessus, seule cette partie nous intéresse
        return $this->forward('index/index');
    }
}
