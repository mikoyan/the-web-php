<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * InlineContent
 * @ODM\EmbeddedDocument
 */
class InlineContent
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
    protected $contentType;

    /**
     * @ODM\Int
     * @var int
     */
    protected $wordCount;

    /**
     * @ODM\String
     * @var string
     */
    protected $content;

    /**
     * Factory
     *
     * @param SimpleXMLElement $xml
     * @return Newscoop\News\InlineContent
     */
    public static function createFromXml(\SimpleXMLElement $xml)
    {
        $content = new self();
        $content->contentType = (string) $xml['contenttype'];
        $content->wordCount = (int) $xml['wordcount'];
        $content->content = $xml->children()->asXML();
        return $content;
    }

    /**
     * @return object
     */
    public function render()
    {
        return (object) array(
            'contentType' => $this->contentType,
            'wordCount' => (int) $this->wordCount,
            'content' => $this->content,
        );
    }
}
