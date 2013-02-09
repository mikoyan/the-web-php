<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Signal
 * @ODM\EmbeddedDocument
 */
class Signal
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
    protected $qcode;

    /**
     * Factory
     *
     * @param SimpleXMLElement $xml
     * @return Newscoop\News\Subject
     */
    public static function createFromXml(\SimpleXMLElement $xml)
    {
        $signal = new self();
        $signal->qcode = isset($xml['qcode']) ? (string) $xml['qcode'] : null;
        return $signal;
    }

    /**
     * @return object
     */
    public function render()
    {
        return (object) array(
            'qcode' => $this->qcode,
        );
    }
}
