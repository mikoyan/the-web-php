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
 * ContentSet
 * @ODM\EmbeddedDocument
 */
class ContentSet
{
    /**
     * @ODM\Id
     * @var string
     */
    protected $id;

    /**
     * @ODM\EmbedOne(targetDocument="InlineContent")
     * @var Newscoop\News\InlineContent
     */
    protected $inlineContent;

    /**
     * @ODM\EmbedMany(targetDocument="RemoteContent")
     * @var Doctrine\Common\Collections\Collection
     */
    protected $remoteContent;

    /**
     */
    public function __construct()
    {
        $this->remoteContent = new ArrayCollection();
    }

    /**
     * Factory
     *
     * @param SimpleXMLElement $xml
     * @return Newscoop\News\ContentSet
     */
    public static function createFromXml(\SimpleXMLElement $xml)
    {
        $contentSet = new self();
        if ($xml->inlineXML->count()) {
            $contentSet->setInlineContent($xml->inlineXML);
        } elseif ($xml->remoteContent->count()) {
            $contentSet->setRemoteContent($xml);
        } else {
            throw new \InvalidArgumentException("Unknown content in " . $xml->asXML());
        }

        return $contentSet;
    }

    /**
     * Set remote content
     *
     * @param SimpleXMLElement $xml
     * @return void
     */
    private function setRemoteContent(\SimpleXMLElement $xml)
    {
        foreach ($xml->children() as $remoteContentXml) {
            $this->remoteContent->add(RemoteContent::createFromXml($remoteContentXml));
        }
    }

    /**
     * Set inline content
     *
     * @param SimpleXMLElement $xml
     * @return void
     */
    private function setInlineContent(\SimpleXMLElement $xml)
    {
        $this->inlineContent = InlineContent::createFromXml($xml);
    }

    public function render()
    {
        return (object) array(
            'inline' => $this->inlineContent ? $this->inlineContent->render() : null,
            'remote' => $this->remoteContent->map(function ($content) { return $content->render(); }),
        );
    }
}
