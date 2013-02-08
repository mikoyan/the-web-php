<?php

require_once __DIR__ . '/../bootstrap.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->get('/', function() use ($dm, $smarty) {
    $items = $dm->getRepository('Superdesk\Document\Item')->findBy(array(), array(), 80);

    $smarty->assign(
        'items',
        array_map(function ($item) { return $item->render(); }, $items->toArray())
    );

    return $smarty->fetch(__DIR__ . '/../templates/index.tpl');
});

$app->run();
