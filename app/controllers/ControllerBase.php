<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    protected function initialize()
    {
    }

    protected function forward($uri)
    {    	
    	$this->jquery->get($uri, "#contenu");
    }
}

