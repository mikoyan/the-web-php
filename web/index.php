<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Superdesk\View\Context;
use Superdesk\View\ContextCollection;

$loader = require_once __DIR__ . '/../app/bootstrap.php';

$app = new Application();
$app['debug'] = true;

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

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
    $context = new Context();
    $context->item = $dm->getRepository('Superdesk\Document\Item')->find($id)->render();
    $smarty->assign('gimme', new ContextCollection($context));
    return $smarty->fetch('item.tpl');
})->bind('item');

// route index
$app->get('/', function () use ($smarty) {
    $smarty->assign('gimme', new ContextCollection());
    return $smarty->fetch('index.tpl');
})->bind('home');

// services
$app['dm'] = $app->share(function () use ($dm) {
    return $dm;
});

$app['item_service'] = $app->share(function () use ($app) {
    return new Superdesk\Ingest\ItemService($app['dm']);
});

$app->run();
