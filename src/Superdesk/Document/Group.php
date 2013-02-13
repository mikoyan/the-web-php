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
 * Group
 * @ODM\EmbeddedDocument
 */
class Group
{
    /**
     * @ODM\Id(strategy="NONE")
     * @var string
     */
    protected $id;

    /**
     * @ODM\String
     * @var string
     */
    protected $role;

    /**
     * @ODM\String
     * @var string
     */
    protected $mode;

    /**
     * @ODM\EmbedMany(
     *   discriminatorMap={
     *     "group"="GroupRef",
     *     "item"="ItemRef"
     *   })
     * @var Doctrine\Common\Collections\Collection
     */
    protected $refs;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = (string) $id;
        $this->refs = new ArrayCollection();
    }

    /**
     * Factory
     *
     * @param SimpleXMLElement $xml
     * @return Newscoop\News\Group
     */
    public static function createFromXml(\SimpleXMLElement $xml)
    {
        $group = new self($xml['id']);
        $group->role = (string) $xml['role'];
        $group->mode = (string) $xml['mode'];
        $group->setRefs($xml);
        return $group;
    }

    /**
     * Set refs
     *
     * @param SimpleXMLElement $xml
     * @return void
     */
    public function setRefs($xml)
    {
        foreach ($xml->children() as $refXml) {
            if ($refXml->getName() === 'groupRef') {
                $this->refs->add(new GroupRef($refXml['idref']));
            } elseif ($refXml->getName() === 'itemRef') {
                $this->refs->add(ItemRef::createFromXml($refXml));
            } else {
                throw new \InvalidArgumentException("Expected group or item ref, got '{$refXml->getName()}'");
            }
        }
    }

    /**
     * @return object
     */
    public function render()
    {
        return (object) array(
            'id' => $this->id,
            'role' => $this->role,
            'mode' => $this->mode,
            'refs' => $this->refs->map(function ($ref) { return $ref->render(); }),
        );
    }
}
