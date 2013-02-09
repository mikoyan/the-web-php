<?php

use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\MongoDB\Connection;

require_once __DIR__ . '/autoload.php';

$config = new Configuration();
$config->setProxyDir(__DIR__ . '/cache');
$config->setProxyNamespace('Proxies');

$config->setHydratorDir(__DIR__ . '/cache');
$config->setHydratorNamespace('Hydrators');

$reader = new AnnotationReader();
$config->setMetadataDriverImpl(new AnnotationDriver($reader, __DIR__ . '/../src/Document'));
$config->setMetadataCacheImpl(new ApcCache());

$config->setDefaultDB('newscoop');

$dm = DocumentManager::create(new Connection(), $config);

$smarty = new Smarty();
$smarty->left_delimiter = '{{';
$smarty->right_delimiter = '}}';
$smarty->auto_literal = false;
$smarty->addPluginsDir(__DIR__ . '/plugins');

//$smarty->setCaching(Smarty::CACHING_LIFETIME_SAVED);
//$smarty->setCacheLifetime(300);
