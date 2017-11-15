<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Channel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;

class Fixtures /*extends Fixture*/
{
    public function load(ObjectManager $manager)
    {
        $finder = Finder::create()->files()->name('*.rss')->in(__DIR__ . '/../Data');
        foreach ($finder as $file) {
            echo $file->getRelativePathname(), PHP_EOL;
            $rss = file_get_contents($file->getPathname());
            $data = new \SimpleXMLElement($rss);

            foreach ($data->channel->item as $el) {
                $item = new Channel();
                $item
                    ->setTitle((string)$el->title)
                    ->setDescription((string)$el->description)
                    ->setGuid((string)$el->guid)
                    ->setPubDate(new \DateTime((string)$el->pubDate));

                if ($el->enclosure) {
                    $item->setEnclosureUrl((string)$el->enclosure->attributes()->url);
                    $item->setEnclosureType((string)$el->enclosure->attributes()->type);
                    $item->setEnclosureLength((int)$el->enclosure->attributes()->length);
                }

                $itunes = $el->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
                if ($itunes !== null) {
                    if ($itunes->duration) {
                        $item->setDuration($this->getDuration((string)$itunes->duration));
                    }
                }

                $manager->persist($item);
            }
            $manager->flush();
        }
    }

    private function getDuration(string $spec)
    {
        // @see https://help.apple.com/itc/podcasts_connect/#/itcb54353390
        if (is_numeric($spec)) {
            return (int)$spec;
        }

        if (preg_match('/(?:(?<hours>[0-9]+):)?(?<minutes>[0-9]+):(?<seconds>[0-9]+)/', $spec, $matches)) {
            $duration = 0;
            if (isset($matches['hours'])) {
                $duration += 60 * 60 * (int)$matches['hours'];
            }
            if (isset($matches['minutes'])) {
                $duration += 60 * (int)$matches['minutes'];
            }
            if (isset($matches['seconds'])) {
                $duration += (int)$matches['seconds'];
            }

            return $duration;
        }

        return null;

    }
}
