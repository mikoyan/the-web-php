<?php
/**
 * @package Superdesk
 */

use Superdesk\View\Iterator\ItemRefsIterator;

/**
 * Package items block
 *
 * @param array $params
 * @param string $content
 * @param Smarty_Internal_Template $template
 * @param bool $repeat
 * @return string
 */
function smarty_block_package_items($params, $content, $template, &$repeat)
{
    $context = $template->getTemplateVars('gimme');

    if ($content === null) {
        $iterator = new ItemRefsIterator($context->item, $params['group']);
        $refs = $iterator->getIterator();

        if (isset($params['class'])) {
            $refs = $refs->filter(function ($ref) use ($params) {
                return empty($params['class']) || $ref->itemClass === $params['class'];
            });
        }

        if (isset($params['length']) || isset($params['start'])) {
            $refs = $refs->slice(
                isset($params['start']) ? $params['start'] : 0,
                isset($params['length']) ? $params['length']: null
            );
        }

        if (!is_array($refs)) {
            $refs = $refs->toArray();
        }

        if (empty($refs)) {
            $repeat = false;
            return;
        }

        global $app;
        $items = new ArrayIterator($app['item_service']->findByRefs($refs));
        $context->push();
    } else {
        $items = $context->popList();
    }

    if ($repeat = $items->valid()) {
        $context->item = $items->current()->render();
        $items->next();
        $context->pushList($items);
    } else {
        $context->pop();
    }

    return $content;
}
