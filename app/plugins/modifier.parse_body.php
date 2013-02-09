<?php

function smarty_modifier_parse_body($body)
{
    $doc = new DOMDocument();
    $doc->loadHTML($body);
    $body = $doc->getElementsByTagName('body')->item(0);

    $contents = array();
    foreach ($body->childNodes as $node) {
        $contents[] = trim($node->textContent);
    }

    return implode("\n", $contents);
}
