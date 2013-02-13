<?php
/**
 * @package Superdesk
 */

/**
 * Picture block
 *
 * @param array $params
 * @param string $content
 * @param Smarty_Internal_Template $template
 * @param bool $repeat
 * @return string
 */
function smarty_block_picture($params, $content, $template, &$repeat)
{
    $context = $template->getTemplateVars('gimme');

    if ($content === null) {
        if (!isset($context->item->content) || !count($context->item->content->remote)) {
            $repeat = false;
            return;
        }

        $template->assign('remote', $context->item->content->remote);
    }

    return $content;
}
