<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * RemoteContent
 * @ODM\EmbeddedDocument
 */
class RemoteContent
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
    protected $residref;

    /**
     * @ODM\String
     * @var string
     */
    protected $href;

    /**
     * @ODM\Int
     * @var int
     */
    protected $size;

    /**
     * @ODM\String
     * @var string
     */
    protected $rendition;

    /**
     * @ODM\String
     * @var string
     */
    protected $contentType;

    /**
     * @ODM\String
     * @var string
     */
    protected $format;

    /**
     * @ODM\String
     * @var string
     */
    protected $generator;

    /**
     * @ODM\Int
     * @var int
     */
    protected $width;

    /**
     * @ODM\Int
     * @var int
     */
    protected $height;

    /**
     * Factory
     *
     * @param SimpleXMLElement $xml
     * @return Newscoop\News\RemoteContent
     */
    public static function createFromXml(\SimpleXMLElement $xml)
    {
        $content = new self();
        $content->residref = (string) $xml['residref'];
        $content->href = (string) $xml['href'];
        $content->size = (int) $xml['size'];
        $content->rendition = (string) $xml['rendition'];
        $content->contentType = (string) $xml['contenttype'];
        $content->format = (string) $xml['format'];
        $content->generator = (string) $xml['generator'];
        $content->width = (int) $xml['width'];
        $content->height = (int) $xml['height'];
        return $content;
    }

    /**
     * @return object
     */
    public function render()
    {
        return (object) array(
            'residref' => $this->residref,
            'href' => $this->href,
            'size' => $this->size,
            'rendition' => $this->rendition,
            'contentType' => $this->contentType,
            'format' => $this->format,
            'generator' => $this->generator,
            'width' => $this->width,
            'height' => $this->height,
        );
    }
}
