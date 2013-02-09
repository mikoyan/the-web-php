<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * ItemRef
 * @ODM\EmbeddedDocument
 */
class ItemRef
{
    /**
     * @ODM\Id
     * @var string
     */
    protected $id;

    /**
     * @ODM\String
     * @var string
     */
    protected $residRef;

    /**
     * @ODM\String
     * @var string
     */
    protected $version;

    /**
     * @ODM\String
     * @var string
     */
    protected $contentType;

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
     * @ODM\String
     * @var string
     */
    protected $pubStatus;

    /**
     * @ODM\String
     * @var string
     */
    protected $slugline;

    /**
     * @ODM\String
     * @var string
     */
    protected $headline;

    /**
     * Factory
     *
     * @param SimpleXMLElement $xml
     * @return Newscoop\News\ItemRef
     */
    public static function createFromXml(\SimpleXMLElement $xml)
    {
        $ref = new self();
        $ref->residRef = (string) $xml['residref'];
        $ref->version = (string) $xml['version'];
        $ref->contentType = (string) $xml['contenttype'];
        $ref->itemClass = (string) $xml->itemClass['qcode'];
        $ref->provider = (string) $xml->provider['literal'];
        $ref->versionCreated = new \DateTime((string) $xml->versionCreated);
        $ref->pubStatus = (string) $xml->pubStatus['qcode'];
        $ref->slugline = (string) $xml->slugline;
        $ref->headline = (string) $xml->headline;
        return $ref;
    }

    /**
     * @return object
     */
    public function render()
    {
        return (object) array(
            'residRef' => $this->residRef,
            'version' => $this->version,
            'contentType' => $this->contentType,
            'itemClass' => $this->itemClass,
            'provider' => $this->provider,
            'versionCreated' => $this->versionCreated,
            'pubStatus' => $this->pubStatus,
            'slugline' => $this->slugline,
            'headline' => $this->headline,
        );
    }
}
