<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * PackageItem
 * @ODM\Document
 */
class PackageItem extends Item
{
    /**
     * @ODM\EmbedOne(targetDocument="GroupSet")
     */
    protected $groupSet;

    /**
     * Factory
     *
     * @param SimpleXMLElement $xml
     * @return Newscoop\News\PackageItem
     */
    public static function createFromXml(\SimpleXMLElement $xml)
    {
        $item = parent::createFromXml($xml);
        $item->groupSet = GroupSet::createFromXml($xml->groupSet);
        return $item;
    }

    /**
     * @return object
     */
    public function render()
    {
        $view = parent::render();
        $view->groups = $this->groupSet->render();
        return $view;
    }
}
