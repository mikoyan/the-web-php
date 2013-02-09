<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * RightsInfo
 * @ODM\EmbeddedDocument
 */
class RightsInfo
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
    protected $scope;

    /**
     * @ODM\String
     * @var string
     */
    protected $copyrightHolder;

    /**
     * @ODM\String
     * @var string
     */
    protected $copyrightNotice;

    /**
     * Factory
     *
     * @param SimpleXMLElement $xml
     * @return Newscoop\News\RightsInfo
     */
    public static function createFromXml(\SimpleXMLElement $xml)
    {
        $attributes = $xml->attributes();
        $info = new self();
        $info->scope = !empty($attributes['scope']) ? (string) $attributes['scope'] : null;
        $info->copyrightHolder = (string) $xml->copyrightHolder['literal'];
        $info->copyrightNotice = (string) $xml->copyrightNotice;
        return $info;
    }

    /**
     * @return object
     */
    public function render()
    {
        return (object) array(
            'scope' => $this->scope,
            'copyrightHolder' => $this->copyrightHolder,
            'copyrightNotice' => $this->copyrightNotice,
        );
    }
}
