<?php
/**
 * @package Superdesk
 */

/**
 * Package block
 *
 * @param array $params
 * @param string $content
 * @param Smarty_Internal_Template $template
 * @param bool $repeat
 * @return string
 */
function smarty_block_package($params, $content, $template, &$repeat)
{
    if (!isset($params['item'])) {
        $params['item'] = $template->getTemplateVars('gimme')->item;
    }

    if ($content === null) {
        if (empty($params['item']->groups)) {
            $repeat = false;
            return;
        }

        $template->assign('package', $params['item']);
    }

    return $content;
}
