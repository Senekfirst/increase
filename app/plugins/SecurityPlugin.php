<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin
{

	/**
	 * Returns an existing or new access control list
	 *
	 * @returns AclList
	 */
	public function getAcl()
	{

		//throw new \Exception("something");

		if (!isset($this->persistent->acl)) {

			$acl = new AclList();

			$acl->setDefaultAction(Acl::DENY);

			//Register roles
			$roles = array(
				'users'  => new Role('Users'),
				'author'  => new Role('Author'),
				'guests' => new Role('Guests')
			);
			foreach ($roles as $role) {
				$acl->addRole($role);
			}

			//Private area resources
			$userResources = array(
				'user' => array('project', 'projects'),
				'project' => array('messages', 'equipe')
			);
			
			$authorResources = array(
				'author' => array('project', 'projects'),
				'project' => array('author')
			);
			
			array_push($authorResources, $userResources); //L'auteur a accès aux ressources des utilisateurs en plus des siennes
			
			foreach ($authorResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//Public area resources
			$publicResources = array(
				'index'      => array('index'),
				'errors'     => array('show404', 'show500', 'show401'),
				'session'    => array('index', 'register', 'start', 'end')
			);
			
			foreach ($publicResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//Grant access to public areas
			foreach ($roles as $role) {
				foreach ($publicResources as $resource => $actions) {
					foreach ($actions as $action){
						$acl->allow($role->getName(), $resource, $action);
					}
				}
			}

			//Grant acess to users area to role Users
			foreach ($userResources as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('Users', $resource, $action);
				}
			}
			
			//Grant acess to author area to role author
			foreach ($authorResources as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('Author', $resource, $action);
				}
			}

			//The acl is stored in session, APC would be useful here too
			$this->persistent->acl = $acl;
		}

		return $this->persistent->acl;
	}

	/**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher)
	{
		$user= $this->session->get('user');
		if (isset($user)){
			$roles = explode(",",$user->getRole()); //On récupère les rôles de l'user, comme il peut en avoir plusieurs d'après la base de données on les explode
			$tabRoles = array();
			foreach($roles as $role){
				$tabRoles[$role] = true;
			}
			
			if (isset($tabRoles['author'])){
				$role = 'Users';
			} else if (isset($tabRoles['user'])){
				$role = 'Author';
			} else {
				$role = 'Guests';
			}
		} else {
			$role = 'Users';
		}

		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		$acl = $this->getAcl();

		if ($acl->isResource($controller)){
			$allowed = $acl->isAllowed($role, $controller, $action);
			if ($allowed != Acl::ALLOW) {
				$dispatcher->forward(array(
					'controller' => 'errors',
					'action'     => 'show401'
				));
				return false;
			}
		}
		//Pas de sinon, on aura 404 par défaut
	}
}
