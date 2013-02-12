<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\View;

/**
 * anyItem View
 */
class Item
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $headline;

    /**
     * @var string
     */
    public $description;

    /**
     * @var array
     */
    public $groups = array();
}
