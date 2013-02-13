<?php
/**
 * @package Superdesk
 */

/**
 * Remote content block
 *
 * @param array $params
 * @param string $content
 * @param Smarty_Internal_Template $template
 * @param bool $repeat
 * @return string
 */
function smarty_block_remote_content($params, $content, $template, &$repeat)
{
    $context = $template->getTemplateVars('gimme');

    if ($content === null) {
        foreach ($context->item->content->remote as $remote_content) {
            if ($remote_content->rendition === $params['rendition']) {
                $template->assign('content', $remote_content);
                break;
            }
        }
    } else {
        return $content;
    }
}
