<?php

require_once __DIR__ . '/../app/bootstrap.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->get('/', function() use ($smarty) {
    return $smarty->fetch(__DIR__ . '/../templates/index.tpl');
});

$app->run();
