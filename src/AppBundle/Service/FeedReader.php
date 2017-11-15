<?php

namespace AppBundle\Service;

use AppBundle\Entity\Feed;
use AppBundle\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use FPN\TagBundle\Entity\TagManager;
use Psr\Log\LoggerInterface;

class FeedReader
{
    /** @var Helper */
    private $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    public function read(Feed $feed, EntityManagerInterface $entityManager, TagManager $tagManager, LoggerInterface $logger = null)
    {
        $data = $this->getData($feed);
        if ($data) {
            foreach ($data->channel->item as $el) {
                $guid = (string) $el->guid;
                if ($guid === null) {
                    continue;
                }
                $item = $entityManager->getRepository(Item::class)->findOneBy(['guid' => $guid]) ?: new Item();
                $item
                    ->setFeed($feed)
                    ->setTitle((string) $el->title)
                    ->setDescription((string) $el->description)
                    ->setGuid((string) $el->guid)
                    ->setPubDate(new \DateTime((string) $el->pubDate));

                if ($el->enclosure) {
                    $item->setEnclosureUrl((string) $el->enclosure->attributes()->url);
                    $item->setEnclosureType((string) $el->enclosure->attributes()->type);
                    $item->setEnclosureLength((int) $el->enclosure->attributes()->length);
                }

                $itunes = $el->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
                if ($itunes !== null) {
                    if ($itunes->duration) {
                        $item->setDuration($this->helper->getDuration((string) $itunes->duration));
                    }
                    if ($itunes->category) {
                        $names = [];
                        foreach ($itunes->category as $category) {
                            $names[] = (string) $category->attributes()->text;
                        }
                        $names = array_filter($names);
                        if (!empty($names)) {
                            $tags = $tagManager->loadOrCreateTags($names);
                            $tagManager->replaceTags($tags, $item);
                        }
                    }
                }

                $entityManager->persist($item);
                $tagManager->saveTagging($item);
                if ($logger !== null) {
                    $logger->notice(sprintf('Item: %s', $item));
                }
            }
        }
        $entityManager->flush();
    }

    private function getData(Feed $source)
    {
        $url = $source->getUrl();
        $data = new \SimpleXMLElement($url, 0, true);

        return $data;
    }
}
