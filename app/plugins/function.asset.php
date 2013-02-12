<?php
/**
 * @package Superdesk
 */

/**
 * Get asset url
 *
 * @param array $params
 * @param Smarty_Internal_Template $template
 * @return string
 */
function smarty_function_asset($params, Smarty_Internal_Template $template)
{
    return sprintf('http://%s/asset/%s', $_SERVER['HTTP_HOST'], $params['href']);
}
