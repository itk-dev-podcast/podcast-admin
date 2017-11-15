<?php

namespace AppBundle\Service;

use AppBundle\Entity\Feed;
use AppBundle\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;

class FeedReader
{
    /** @var Helper */
    private $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    public function read(Feed $source, EntityManagerInterface $entityManager)
    {
        $data = $this->getData($source);
        if ($data) {
            foreach ($data->channel->item as $el) {
                $item = new Item();
                $item
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
                }

                $entityManager->persist($item);
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
