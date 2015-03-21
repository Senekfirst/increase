<?php

class ErrorsController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Oops!');
    }

    public function show404Action()
    {
    	$url = $this->dispatcher->getPreviousControllerName()."/".$this->dispatcher->getActionName();
    	$this->flash->error("La page demandée {$url} n'éxiste pas");
    }

    public function show401Action()
    {
    	$this->flash->error("Vous n'avez pas accès à la page demandée");
    }

    public function show500Action()
    {
    	$this->flash->error("Un problème a été détecté");
    }
}
