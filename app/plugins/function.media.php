<?php
/**
 * @package Superdesk
 */

/**
 * Get media url for given resource
 *
 * @param array $params
 * @param Smarty_Internal_Template $template
 * @return string
 */
function smarty_function_media($params, Smarty_Internal_Template $template)
{
    return sprintf('http://%s/media/%s', $_SERVER['HTTP_HOST'], sha1($params['href']));
}
