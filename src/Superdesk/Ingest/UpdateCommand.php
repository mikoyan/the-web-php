<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\Ingest;

use Symfony\Component\Console\Command\Command;

/**
 * Update Command
 */
class UpdateCommand extends Command
{
    /**
     */
    protected function configure()
    {
        $this->setName('ingest:update');
        $this->setDescription('Ingest Update Command');
    }

    protected function execute()
    {
        global $dm;

        $itemService = new ItemService($dm);

        $feeds = $dm->getRepository('Superdesk\Document\ReutersFeed')->findAll();
        foreach ($feeds as $feed) {
            $feed->update($dm, $itemService);
        }
    }
}
