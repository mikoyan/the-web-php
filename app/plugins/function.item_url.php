<?php
/**
 * @package Superdesk
 */

/**
 * Get item url
 *
 * @param array $params
 * @param Smarty_Internal_Template $template
 * @return string
 */
function smarty_function_item_url($params, Smarty_Internal_Template $template)
{
    if (!isset($params['item'])) {
        $params['item'] = $template->getTemplateVars('gimme')->item;
    }

    return $GLOBALS['app']['url_generator']->generate(
        'item',
        array('id' => $params['item']->id)
    );
}
