<?php
/**
 * @package Superdesk
 */

/**
 * Block listing items
 *
 * @param array $params
 * @param string $content
 * @param Smarty_Internal_Template $template
 * @param bool $repeat
 * @return string
 */
function smarty_block_list_items($params, $content, $template, &$repeat)
{
    $context = $template->getTemplateVars('gimme');

    if ($content === null) {
        global $dm;

        $criteria = array();

        if (isset($params['type'])) {
            $criteria['type'] = $params['type'];
        }

        if (isset($params['class'])) {
            $criteria['itemMeta.itemClass'] = $params['class'];
        }

        $items = $dm->getRepository('Superdesk\Document\Item')->findBy(
            $criteria,
            array('itemMeta.versionCreated' => 'desc'),
            !empty($params['length']) ? $params['length'] : 25,
            !empty($params['start']) ? $params['start'] : 0
        );

        $items = new ArrayIterator($items->toArray());
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
