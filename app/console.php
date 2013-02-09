#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;
use Superdesk\Ingest\UpdateCommand;

require_once __DIR__ . '/bootstrap.php';

$app = new Application();

// add commands
$app->add(new UpdateCommand());

$app->run();
