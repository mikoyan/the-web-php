<?php
/**
 * @package Superdesk
 */

/**
 * Parse body content out of given html
 *
 * @param string $html
 * @return string
 */
function smarty_modifier_parse_body($html)
{
    $doc = new DOMDocument();
    $doc->loadHTML($html);
    $body = $doc->getElementsByTagName('body')->item(0);

    $content = '';
    foreach ($body->childNodes as $node) {
        $nodeContent = trim($node->textContent);
        if (empty($nodeContent)) {
            continue;
        }

        $content .= $node->C14N();
        $content .= "\n";
    }

    return $content;
}
