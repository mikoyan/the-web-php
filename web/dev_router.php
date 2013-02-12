<?php

$staticPaths = array('/media', '/asset', '/favicon');
foreach ($staticPaths as $path) {
    if (substr($_SERVER['REQUEST_URI'], 0, strlen($path)) === $path) {
        return false;
    }
}

include_once __DIR__ . '/index.php';
