<?php

namespace AppBundle\Service;

use AppBundle\Entity\Channel;
use AppBundle\Entity\Feed;
use AppBundle\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use FPN\TagBundle\Entity\TagManager;
use Psr\Log\LoggerInterface;

class FeedReader
{
    /** @var Helper */
    private $helper;

    /** @var Feed */
    private $feed;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var TagManager */
    private $tagManager;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    public function read(Feed $feed, EntityManagerInterface $entityManager, TagManager $tagManager, LoggerInterface $logger)
    {
        $this->feed = $feed;
        $this->entityManager = $entityManager;
        $this->tagManager = $tagManager;
        $this->logger = $logger;

        if (!$feed->isEnabled()) {
            $logger->notice(sprintf('Feed %s is not enabled', $feed));
            return;
        }
        $now = new \DateTime();
        $nextReadAt = clone ($feed->getLastReadAt() ?: new \DateTime('2000-01-01'));
        $ttl = (int)$feed->getTtl();
        if ($ttl > 0) {
            $nextReadAt->add(new \DateInterval('PT' . $ttl . 'M'));
        }
        if ($nextReadAt >= $now) {
            $logger->notice(sprintf('Next read of feed %s at %s', $feed, $nextReadAt->format(\DateTime::W3C)));
            return;
        }

        $channel = $this->readChannel();

        $feed
            ->setTtl((int)($channel->getTtl() ?: 60))
            ->setLastReadAt(new \DateTime());
        $entityManager->persist($feed);
        $entityManager->flush();
    }

    /**
     * @return Channel
     */
    private function readChannel()
    {
        $channel = $this->getChannel();
        $data = new \SimpleXMLElement($this->feed->getUrl(), 0, true);
        if ($data) {
            $this->readItems($data->channel, $channel);
        }

        return $channel;
    }

    private function readItems(\SimpleXMLElement $el, Channel $channel)
    {
        foreach ($el->item as $el) {
            $guid = (string)$el->guid;
            if ($guid === null) {
                continue;
            }
            $item = $this->getItem($guid);
            $item
                ->setTitle((string)$el->title)
                ->setLink((string)$el->link)
                ->setDescription((string)$el->description)
                ->setGuid((string)$el->guid)
                ->setPubDate(new \DateTime((string)$el->pubDate));

            if ($el->enclosure) {
                $item->setEnclosure([
                    'url' => (string)$el->enclosure->attributes()->url,
                    'type' => (string)$el->enclosure->attributes()->type,
                    'length' => (int)$el->enclosure->attributes()->length,
                ]);
            }

            $itunes = $el->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
            if ($itunes !== null) {
                if ($itunes->duration) {
                    $item->setDuration($this->helper->getDuration((string)$itunes->duration));
                }
                if ($itunes->category) {
                    $names = [];
                    foreach ($itunes->category as $category) {
                        $names[] = (string)$category->attributes()->text;
                    }
                    $names = array_filter($names);
                    if (!empty($names)) {
                        $tags = $this->tagManager->loadOrCreateTags($names);
                        $this->tagManager->replaceTags($tags, $item);
                    }
                }
            }

            $this->persist($item);
            $this->tagManager->saveTagging($item);
            $this->notice(sprintf('Item: %s', $item));
        }
    }

    private function getChannel() {
        $channel = $this->entityManager->getRepository(Channel::class)->findOneBy(['feed' => $this->feed]) ?: new Channel();
        $channel->setFeed($this->feed);

        return $channel;
    }

    private function getItem($guid) {
        $item = $this->entityManager->getRepository(Item::class)->findOneBy(['guid' => $guid]) ?: new Item();
        $item->setFeed($this->feed);

        return $item;
    }

    private function persist($entity) {
        $this->entityManager->persist($entity);
    }

    private function notice($messages) {
        $this->logger->notice($messages);
    }
}
