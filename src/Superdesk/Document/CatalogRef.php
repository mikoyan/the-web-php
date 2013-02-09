<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * CatalogRef
 * @ODM\EmbeddedDocument
 */
class CatalogRef
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
    protected $href;

    /**
     * @param string $href
     */
    public function __construct($href)
    {
        $this->href = (string) $href;
    }

    /**
     * @return object
     */
    public function render()
    {
        return (object) array(
            'href' => $this->href,
        );
    }
}
