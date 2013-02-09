<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ItemMeta
 * @ODM\EmbeddedDocument
 */
class ItemMeta
{
    const STATUS_USABLE = 'stat:usable';
    const STATUS_WITHHELD = 'stat:withheld';
    const STATUS_CANCELED = 'stat:canceled';

    const CLASS_TEXT = 'icls:text';
    const CLASS_PICTURE = 'icls:picture';
    const CLASS_PACKAGE = 'icls:composite';

    /**
     * @ODM\Id
     * @var string
     */
    protected $id;

    /**
     * @ODM\String
     * @var string
     */
    protected $itemClass;

    /**
     * @ODM\String
     * @var string
     */
    protected $provider;

    /**
     * @ODM\Date
     * @var DateTime
     */
    protected $versionCreated;

    /**
     * @ODM\Date
     * @var DateTime
     */
    protected $firstCreated;

    /**
     * @ODM\String
     * @var string
     */
    protected $pubStatus;

    /**
     * @ODM\String
     * @var string
     */
    protected $role;

    /**
     * @ODM\String
     * @var string
     */
    protected $title;

    /**
     * @ODM\EmbedMany(targetDocument="Signal")
     */
    protected $signals;

    /**
     */
    public function __construct()
    {
        $this->signals = new ArrayCollection();
    }

    /**
     * Factory
     *
     * @param SimpleXMLElement $xml
     * @return Newscoop\News\ItemMeta
     */
    public static function createFromXml(\SimpleXMLElement $xml)
    {
        $meta = new static();
        $meta->itemClass = (string) $xml->itemClass['qcode'];
        $meta->provider = (string) $xml->provider['literal'];
        $meta->versionCreated = new \DateTime((string) $xml->versionCreated);
        $meta->firstCreated = new \DateTime((string) $xml->firstCreated);
        $meta->pubStatus = (string) $xml->pubStatus['qcode'] ?: self::STATUS_USABLE;
        $meta->role = (string) $xml->role['qcode'];
        $meta->title = (string) $xml->title;
        $meta->setSignals($xml);
        return $meta;
    }

    /**
     * Set signals
     *
     * @param SimpleXmlElement $xml
     */
    protected function setSignals($xml)
    {
        foreach ($xml->signal as $signalXml) {
            $this->signals->add(Signal::createFromXml($signalXml));
        }
    }

    /**
     * Get pubStatus
     *
     * @return string
     */
    public function getPubStatus()
    {
        return $this->pubStatus;
    }

    /**
     * @return object
     */
    public function render()
    {
        return (object) array(
            'role' => $this->role,
            'firstCreated' => $this->firstCreated,
            'versionCreated' => $this->versionCreated,
            'signals' => $this->signals->map(function ($signal) { return $signal->render(); }),
        );
    }
}
