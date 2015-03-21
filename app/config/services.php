<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Ajax\JsUtils;
use Ajax\Bootstrap;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Events\Manager as EventsManager;

$di = new FactoryDefault();

$di->set('dispatcher', function() use ($di) {
	$eventsManager = new EventsManager;
	$eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin);
	$eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);
	$dispatcher = new Dispatcher;
	$dispatcher->setEventsManager($eventsManager);

	return $dispatcher;
});

$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ));

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

$di->set('db', function () use ($config) {
    return new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ));
});

$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});

$di->set('flash', function(){
	return new FlashSession(array(
		'error'   => 'alert alert-danger',
		'success' => 'alert alert-success',
		'notice'  => 'alert alert-info',
	));
});

$di->set("jquery",function(){
	$jquery = new JsUtils(array("driver"=>"Jquery"));
	$jquery->bootstrap(new Bootstrap());//for Twitter Bootstrap
	$cdn = new Ajax\lib\CDNBootstrap("");
	$jquery->setCDNs($cdn->setJsUrl("js/bootstrap.min.js")->setcssUrl("css/bootstrap.min.css"));
	return $jquery;
});

$di->set('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});
