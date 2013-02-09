<?php
/**
 * @package Superdesk
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Superdesk\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\DocumentManager;
use Guzzle\Http\Client;
use Superdesk\Ingest\ItemService;

/**
 * Reuters feed
 * @ODM\Document(collection="news_feed")
 */
class ReutersFeed extends Feed
{
    const URL = 'http://rmb.reuters.com/';

    const TOKEN_TTL = 12; // hours

    const STATUS_SUCCESS = 10;
    const STATUS_PARTIAL_SUCCESS = 20;

    /**
     * @var Zend_Rest_Client
     */
    protected $client;

    /**
     * @ODM\String
     * @var string
     */
    protected $token;

    /**
     * @ODM\Date
     * @var DateTime
     */
    protected $tokenUpdated;

    /**
     * @param array $configuration
     * @param Zend_Rest_Client $client
     */
    public function __construct(array $configuration)
    {
        $this->setConfiguration($configuration);
    }

    /**
     * Update feed
     *
     * @param DocumentManager $dm
     * @param ItemService $itemService
     * @return void
     */
    public function update(DocumentManager $dm, ItemService $itemService = null)
    {
        $updated = new \DateTime();
        foreach ($this->getChannels() as $channel) {
            if ($this->updated !== null && $this->updated->getTimestamp() > $channel->lastUpdate->getTimestamp()) {
                continue;
            }

            foreach ($this->getChannelItems($channel) as $channelItem) {
                $item = $this->getItem($channelItem->guid); // get the latest revision
                if ($item !== null) {
                    $item->setFeed($this);
                    $itemService->save($item);
                }
            }
        }

        $this->updated = $updated;
        $dm->flush();
    }

    /**
     * Get list of subscribed channels
     *
     * @return array
     */
    public function getChannels()
    {
        $response = $this->getClient()->get(array(
            '/rmd/rest/xml/channels?token={token}',
            array('token' => $this->getToken()),
        ));

        $xml = $this->parseResponse($response);

        $channels = array();
        foreach ($xml->channelInformation as $channelInformation) {
            $channels[] = (object) array(
                'description' => (string) $channelInformation->description,
                'alias' => (string) $channelInformation->alias,
                'lastUpdate' => new \DateTime((string) $channelInformation->lastUpdate),
                'category' => (string) $channelInformation['description'],
            );
        }

        return $channels;
    }

    /**
     * Get list of items in channel
     *
     * @param object $channel
     * @return array
     */
    private function getChannelItems($channel)
    {
        $response = $this->getClient()->get(array(
            '/rmd/rest/xml/items{?data*}',
            array(
                'data' => array(
                    'token' => $this->getToken(),
                    'channel' => $channel->alias,
                    'fieldsRef' => 'id',
                    'maxAge' => $this->getMaxAge($this->updated),
                ),
            ),
        ));

        $xml = $this->parseResponse($response);

        $items = array();
        foreach ($xml->result as $result) {
            $items[] = (object) array(
                'id' => (string) $result->id,
                'guid' => (string) $result->guid,
                'version' => (string) $result->version,
            );
        }

        return $items;
    }

    /**
     * Get item
     *
     * @param string $id
     * @return Newscoop\News\NewsItem
     */
    public function getItem($id)
    {
        $response = $this->getClient()->get(array(
            '/rmd/rest/xml/item{?data*}',
            array(
                'data' => array(
                    'token' => $this->getToken(),
                    'id' => $id,
                ),
            ),
        ));

        $xml = $this->parseResponse($response);
        if (!empty($xml->itemSet->newsItem)) {
            return NewsItem::createFromXml($xml->itemSet->newsItem);
        } else if (!empty($xml->packageItem)) {
            return PackageItem::createFromXml($xml->packageItem);
        } else if (!empty($xml->itemSet->packageItem)) {
            return PackageItem::createFromXml($xml->itemSet->packageItem);
        } else {
            var_dump('not implemented', $xml->asXML());
            exit;
            throw new \InvalidArgumentException("Not implemented");
        }

        return;
    }

    /**
     * Get auth token
     *
     * @return string
     */
    private function getToken()
    {
        if ($this->token === null || !$this->tokenIsValid()) {
            $client = $this->getClient('https://commerce.reuters.com/');
            $request = $client->get('/rmd/rest/xml/login?username={username}&password={password}');
            $response = $request->send();

            if (!$response->isSuccessful()) {
                throw new \RuntimeException("Can't get auth token");
            }

            $this->token = (string) simplexml_load_string($response->getBody());
            $this->tokenUpdated = new \DateTime();
        }

        return $this->token;
    }

    /**
     * Test if token is valid
     *
     * @return bool
     */
    private function tokenIsValid()
    {
        $diff = date_create()->diff($this->tokenUpdated);

        if ($diff->y || $diff->m || $diff->d) {
            return false;
        }

        return $diff->h <= self::TOKEN_TTL;
    }

    /**
     * Get remote content src
     *
     * @param Newscoop\News\RemoteContent $remoteContent
     * @return string
     */
    public function getRemoteContentSrc(RemoteContent $remoteContent)
    {
        return sprintf('%s?token=%s', $remoteContent->getHref(), $this->getToken());
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return sprintf("Reuters [%s]", $this->configuration['username']) ;
    }

    /**
     * Get client
     *
     * @param string $url
     * @return Client
     */
    private function getClient($url = self::URL)
    {
        return new Client(
            $url,
            array_merge($this->configuration, array(
                'curl.options' => array(
                    'CURLOPT_SSL_VERIFYHOST' => false,
                    'CURLOPT_SSL_VERIFYPEER' => false,
                    'CURLOPT_SSLVERSION' => 3,
                )
            ))
        );
    }

    /**
     * Throw an exception if error occured
     *
     * @param SimpleXMLElement $xml
     * @return SimpleXMLElement
     */
    private function parseResponse($request)
    {
        $response = $request->send();

        if (!$response->isSuccessful()) {
            throw new \RuntimeException("Response not successful.");
        }

        $xml = simplexml_load_string($response->getBody(true));
        if ($xml->getName() !== 'newsMessage' && !in_array((int) $xml->status['code'], array(self::STATUS_SUCCESS, self::STATUS_PARTIAL_SUCCESS))) {
            throw new \RuntimeException((string) $xml->status->error);
        }

        return $xml;
    }

    /**
     * Get max age
     *
     * @param DateTime $since
     * @return string
     */
    private function getMaxAge(\DateTime $since = null)
    {
        $map = array( // ignoring month/year as the service limits to last 30 days
            'd' => 60 * 60 * 24,
            'h' => 60 * 60,
            'i' => 60,
            's' => 1,
        );

        $limit = new \DateTime('-6 day'); // can't be older than 7 days
        if ($since === null || $since->getTimestamp() < $limit->getTimestamp()) {
            $since = date_create('-10min');
        }

        $seconds = 0;
        $diff = date_create('now')->diff($since);
        foreach ($map as $property => $factor) {
            $seconds += $diff->$property * $factor;
        }

        return "{$seconds}s";
    }
}
