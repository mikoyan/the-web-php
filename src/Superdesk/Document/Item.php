<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;
use Superdesk\View\Item as ItemView;

/**
 * anyItem
 * @ODM\Document(collection="news_item")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField(fieldName="type")
 * @ODM\DiscriminatorMap({"package"="PackageItem", "news"="NewsItem"})
 */
abstract class Item
{
    /**
     * @ODM\Id(strategy="NONE")
     * @var string
     */
    protected $id;

    /**
     * @ODM\Int
     * @var string
     */
    protected $version;

    /**
     * @ODM\String
     * @var string
     */
    protected $standard;

    /**
     * @ODM\String
     * @var string
     */
    protected $standardVersion;

    /**
     * @ODM\String
     * @var string
     */
    protected $conformance;

    /**
     * @ODM\EmbedMany(targetDocument="RightsInfo")
     * @var Doctrine\Common\Collections\Collection
     */
    protected $rightsInfo;

    /**
     * @ODM\EmbedOne(targetDocument="ItemMeta")
     * @var Newscoop\News\ItemMeta
     */
    protected $itemMeta;

    /**
     * @ODM\EmbedOne(targetDocument="ContentMeta")
     * @var Newscoop\News\ContentMeta
     */
    protected $contentMeta;

    /**
     * @ODM\Date
     * @var DateTime
     */
    protected $created;

    /**
     * @ODM\EmbedMany(targetDocument="CatalogRef")
     * @var Doctrine\Common\Collections\Collection
     */
    protected $catalogRefs;

    /**
     * @ODM\Date
     * @var DateTime
     */
    protected $published;

    /**
     * @param string $id
     * @param int $version
     */
    public function __construct($id, $version = 1)
    {
        $this->id = (string) $id;
        $this->version = max(1, (int) $version);
        $this->rightsInfo = new ArrayCollection();
        $this->catalogRefs = new ArrayCollection();
    }

    /**
     * Factory
     *
     * @param SimpleXMLElement $xml
     * @return Newscoop\News\Item
     */
    protected static function createFromXml(\SimpleXMLElement $xml)
    {
        if (empty($xml['guid'])) {
            throw new \InvalidArgumentException("Guid can't be empty");
        }

        $item = new static($xml['guid'], $xml['version']);

        $item->standard = (string) $xml['standard'];
        $item->standardVersion = (string) $xml['standardversion'];
        $item->conformance = isset($xml['conformance']) ? (string) $xml['conformance'] : 'core';
        $item->created = new \DateTime();

        $item->setRightsInfo($xml);
        $item->itemMeta = ItemMeta::createFromXml($xml->itemMeta);
        $item->contentMeta = ContentMeta::createFromXml($xml->contentMeta);
        $item->setCatalogRefs($xml);
        return $item;
    }

    /**
     * Set feed
     *
     * @param Newscoop\News\Feed $feed
     * @return void
     */
    public function setFeed(Feed $feed)
    {
        $this->feed = $feed;
    }

    /**
     * Set rights info
     *
     * @param SimpleXMLElement $xml
     * @return void
     */
    private function setRightsInfo(\SimpleXMLElement $xml)
    {
        foreach ($xml->rightsInfo as $rightsInfoXml) {
            $this->rightsInfo->add(RightsInfo::createFromXml($rightsInfoXml));
        }
    }

    /**
     * Set item meta
     *
     * @param Newscoop\News\ItemMeta $itemMeta
     * @return void
     */
    public function setItemMeta(ItemMeta $itemMeta)
    {
        $this->itemMeta = $itemMeta;
    }

    /**
     * Test if is canceled
     *
     * @return bool
     */
    public function isCanceled()
    {
        return isset($this->itemMeta) && $this->itemMeta->getPubStatus() === ItemMeta::STATUS_CANCELED;
    }

    /**
     * Set catalog refs
     *
     * @param SimpleXMLElement $xml
     * @return void
     */
    public function setCatalogRefs(\SimpleXMLElement $xml)
    {
        foreach ($xml->catalogRef as $catalogRefXml) {
            $this->catalogRefs->add(new CatalogRef($catalogRefXml['href']));
        }
    }

    /**
     * Set published
     *
     * @param DateTime $published
     * @return void
     */
    public function setPublished(\DateTime $published)
    {
        $this->published = $published;
    }

    /**
     * Test if is published
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->published !== null;
    }

    /**
     * Test if is usable
     *
     * @return bool
     */
    public function isUsable()
    {
        return $this->getItemMeta()->getPubStatus() === 'stat:usable';
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get version
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return object
     */
    public function render()
    {
        $view = new ItemView();
        $view->id = $this->id;
        $view->guid = $this->id;
        $view->contentMeta = $this->contentMeta->render();
        $view->itemMeta = $this->itemMeta->render();
        $view->rightsInfo = $this->rightsInfo->map(function ($rightsInfo) { return $rightsInfo->render(); });

        $view->slugline = $view->contentMeta->slugline;
        $view->headline = $view->contentMeta->headline;
        $view->creditline = $view->contentMeta->creditline;
        $view->byline = $view->contentMeta->byline;
        $view->description = $view->contentMeta->description;

        $view->subjects = $view->contentMeta->subjects;
        $view->signals = $view->itemMeta->signals;

        return $view;
    }
}
