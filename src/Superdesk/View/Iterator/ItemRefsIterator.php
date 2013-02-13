<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\View\Iterator;

use IteratorAggregate;
use SplQueue;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Item Refs Iterator
 */
class ItemRefsIterator implements IteratorAggregate
{
    /**
     * @param Superdesk\View\Item $package
     * @param string $group
     */
    public function __construct($package, $group)
    {
        $this->package = $package;
        $this->group = $group;
    }

    /**
     * @return Iterator
     */
    public function getIterator()
    {
        return new ArrayCollection($this->getItems());
    }

    /**
     * Get items
     *
     * @return array
     */
    private function getItems()
    {
        $items = array();
        $queue = new SplQueue();

        $queue->enqueue($this->group);
        while (!$queue->isEmpty()) {
            $groupId = $queue->dequeue();
            foreach ($this->package->groups as $group) {
                if ($group->id === $groupId) {
                    foreach ($group->refs as $ref) {
                        if (isset($ref->idRef)) {
                            $queue->enqueue($ref->idRef);
                        } else {
                            $items[] = $ref;
                        }
                    }

                    break;
                }
            }
        }

        return $items;
    }
}
