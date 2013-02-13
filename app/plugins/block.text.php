<?php
/**
 * @package Superdesk
 */

/**
 * Text block
 *
 * @param array $params
 * @param string $content
 * @param Smarty_Internal_Template $template
 * @param bool $repeat
 * @return string
 */
function smarty_block_text($params, $content, $template, &$repeat)
{
    $context = $template->getTemplateVars('gimme');

    if ($content === null) {
        if (!isset($context->item->content) || !isset($context->item->content->inline)) {
            $repeat = false;
            return;
        }

        $template->assign('content', $context->item->content->inline->content);
    }

    return $content;
}
