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
 * GroupSet
 * @ODM\EmbeddedDocument
 */
class GroupSet
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
    protected $root;

    /**
     * @ODM\EmbedMany(targetDocument="Group")
     * @var Doctrine\Common\Collections\Collection
     */
    protected $groups;

    /**
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    /**
     * Factory
     *
     * @param SimpleXMLElement $xml
     * @return Newscoop\News\GroupSet
     */
    public static function createFromXml(\SimpleXMLElement $xml)
    {
        $set = new self();
        $set->root = (string) $xml['root'];
        $set->setGroups($xml);
        return $set;
    }

    /**
     * Set groups
     *
     * @param SimpleXMLElement $xml
     * @return void
     */
    private function setGroups(\SimpleXMLElement $xml)
    {
        foreach ($xml->children() as $groupXml) {
            $this->groups->add(Group::createFromXml($groupXml));
        }
    }

    /**
     * Render
     *
     * @return object
     */
    public function render()
    {
        return $this->groups->map(function ($group) { return $group->render(); });
    }
}
