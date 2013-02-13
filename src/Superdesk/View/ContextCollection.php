<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\View;

use SplStack;

/**
 * View Context Collection
 */
class ContextCollection
{
    /**
     * @var SplStack
     */
    private $lists;

    /**
     * @var SplStack
     */
    private $contexts;

    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context = null)
    {
        $this->lists = new SplStack();
        $this->contexts = new SplStack();
        $this->setContext($context);
    }

    /**
     * Push list to stack
     *
     * @param mixed $list
     * @return void
     */
    public function pushList($list)
    {
        $this->lists->push($list);
    }

    /**
     * Pop list
     *
     * @return mixed
     */
    public function popList()
    {
        return $this->lists->pop();
    }

    /**
     * Push context
     *
     * @param string $property
     * @return void
     */
    public function push()
    {
        $this->contexts->push(clone $this->context);
    }

    /**
     * Pop context
     *
     * @return void
     */
    public function pop()
    {
        $this->setContext($this->contexts->pop());
    }

    /**
     * Set context
     *
     * @param Context $context
     * @return void
     */
    private function setContext(Context $context = null)
    {
        if ($context === null) {
            $context = new Context();
        }

        $this->context = $context;
    }

    /**
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->context->$property;
    }

    /**
     * @param string $property
     * @param string $value
     */
    public function __set($property, $value)
    {
        $this->context->$property = $value;
    }

    /**
     * @param string $property
     * @return bool
     */
    public function __isset($property)
    {
        return isset($this->context->$property);
    }

    /**
     * @param string $property
     */
    public function __unset($property)
    {
        $this->__set($property, null);
    }
}
