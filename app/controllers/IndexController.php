<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
    	$user= $this->session->get('user');
    	if (!isset($user)){
    		return $this->forward('session/index');
    	}
    }

}

