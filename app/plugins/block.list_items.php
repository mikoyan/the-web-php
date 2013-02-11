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
    static $lists;

    if ($lists === null) {
        $lists = new SplStack();
    }

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
        $lists->push($items);
    } else {
        $items = $lists->pop();
    }

    if ($items->valid()) {
        $template->assign('item', $items->current()->render());
        $items->next();
        if ($items->valid()) {
            $repeat = true;
            $lists->push($items);
        }
    }

    return $content;
}
