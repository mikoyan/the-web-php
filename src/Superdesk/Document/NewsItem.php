<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * NewsItem
 * @ODM\Document
 */
class NewsItem extends Item
{
    /**
     * @ODM\EmbedOne(targetDocument="ContentSet")
     * @var Newscoop\News\ContentSet
     */
    protected $contentSet;

    /**
     * Factory
     *
     * @param SimpleXMLElement $xml
     * @return Newscoop\News\NewsItem
     */
    public static function createFromXml(\SimpleXMLElement $xml)
    {
        $item = parent::createFromXml($xml);
        $item->contentSet = ContentSet::createFromXml($xml->contentSet);
        return $item;
    }

    /**
     * @return object
     */
    public function render()
    {
        $view = parent::render();
        $view->content = $this->contentSet->render();
        return $view;
    }
}
