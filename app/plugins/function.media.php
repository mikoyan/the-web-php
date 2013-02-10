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
    return 'http://localhost/reuters-php/web/media/' . sha1($params['href']);
}
