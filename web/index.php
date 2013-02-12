<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

$loader = require_once __DIR__ . '/../app/bootstrap.php';

$app = new Application();
$app['debug'] = true;

// add access log
$app->finish(function (Request $request, Response $response) {
    file_put_contents('php://stdout', sprintf(
        "[%s] %s:%s [%d]: %s %s (generated in %.3fs)\n",
        date('D M d H:i:s Y', $request->server->get('REQUEST_TIME')),
        $request->server->get('REMOTE_ADDR'),
        $request->server->get('REMOTE_PORT'),
        $response->getStatusCode(),
        $request->getMethod(),
        $request->getRequestUri(),
        microtime(true) - $request->server->get('REQUEST_TIME_FLOAT', 0)
    ));
});

// route item
$app->get('/i/{id}', function ($id) use ($dm, $smarty) {
    $smarty->assign('item', $dm->getRepository('Superdesk\Document\Item')->find($id));
    return $smarty->fetch('item.tpl');
});

// route index
$app->get('/', function () use ($smarty) {
    return $smarty->fetch('index.tpl');
});

$app->run();
