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
 * ContentMeta
 * @ODM\EmbeddedDocument
 */
class ContentMeta
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
    protected $urgency;

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
     * @ODM\String
     * @var string
     */
    protected $dateline;

    /**
     * @ODM\String
     * @ODM\AlsoLoad("by")
     * @var string
     */
    protected $byline;

    /**
     * @ODM\String
     * @var string
     */
    protected $creditline;

    /**
     * @ODM\String
     * @var string
     */
    protected $description;

    /**
     * @ODM\EmbedMany(targetDocument="Subject")
     * @var Doctrine\Common\Collections\Collection
     */
    protected $subjects;

    /**
     * @ODM\String
     * @var string
     */
    protected $language;


    /**
     */
    public function __construct()
    {
        $this->subjects = new ArrayCollection();
    }

    /**
     * Factory
     *
     * @param SimpleXMLElement $xml
     * @return Newscoop\News\ContentMeta
     */
    public static function createFromXml(\SimpleXMLElement $xml)
    {
        $meta = new self();
        $meta->urgency = (string) $xml->urgency;
        $meta->slugline = (string) $xml->slugline;
        $meta->headline = (string) $xml->headline;
        $meta->dateline = (string) $xml->dateline;
        $meta->creditline = (string) $xml->creditline;
        $meta->byline = (string) $xml->by;
        $meta->description = (string) $xml->description;
        $meta->setSubjects($xml);
        $meta->language = (string) $xml->language['tag'];
        return $meta;
    }

    /**
     * Set subjects
     *
     * @param SimpleXMLElement $xml
     * @return void
     */
    protected function setSubjects(\SimpleXMLElement $xml)
    {
        foreach ($xml->subject as $subjectXml) {
            $this->subjects->add(Subject::createFromXml($subjectXml));
        }
    }

    /**
     * @return object
     */
    public function render()
    {
        return (object) array(
            'urgency' => $this->urgency,
            'slugline' => $this->slugline,
            'headline' => $this->headline,
            'dateline' => $this->dateline,
            'byline' => $this->byline,
            'creditline' => $this->creditline,
            'description' => $this->description,
            'language' => $this->language,
            'subjects' => $this->subjects->map(function ($subject) { return $subject->render(); }),
        );
    }
}
