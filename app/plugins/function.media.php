<?php

function smarty_function_media($params, $template)
{
    $dest =  __DIR__ . '/../../web/media/' . sha1($params['href']);
    if (!file_exists($dest)) {
        $url = sprintf('%s?token=%s', $params['href'], $params['token']);
        file_put_contents($dest, file_get_contents($url));
    }

    return 'http://localhost/reuters-php/web/media/' . basename($dest);
}
