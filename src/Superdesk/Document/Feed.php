<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\DocumentManager;
use Superdesk\Ingest\ItemService;

/**
 * @ODM\MappedSuperclass
 */
abstract class Feed
{
    /**
     * @ODM\Id
     * @var string
     */
    protected $id;

    /**
     * @ODM\Hash
     * @var array
     */
    protected $configuration = array();

    /**
     * @ODM\Date
     * @var DateTime
     */
    protected $updated;

    /**
     * @ODM\Boolean
     * @var bool
     */
    protected $isAutoMode = false;

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set configuration
     *
     * @param array $configuration
     * @return void
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Get configuration
     *
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Update feed
     *
     * @param Doctrine\Common\Persistence\ObjectManager $om
     * @param Newscoop\News\ItemService $itemService
     * @return void
     */
    abstract public function update(DocumentManager $dm, ItemService $itemService);

    /**
     * Get updated
     *
     * @return DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Test if is auto mode active
     *
     * @return bool
     */
    public function isAutoMode()
    {
        return (bool) $this->isAutoMode;
    }

    /**
     * Switch mode
     *
     * @return void
     */
    public function switchMode()
    {
        $this->isAutoMode = ! $this->isAutoMode();
    }

    /**
     * Get name
     *
     * @return string
     */
    abstract public function getName();

    /**
     * Get remote content src
     *
     * @param Newscoop\News\RemoteContent $remoteContent
     * @return string
     */
    abstract public function getRemoteContentSrc(RemoteContent $remoteContent);

    /**
     * Get item
     *
     * @param string $id
     * @return Newscoop\News\Item
     */
    abstract public function getItem($id);
}
