<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Feed;

class LoadFeed extends LoadData
{
    public function getOrder()
    {
        return 2;
    }

    protected function loadItem($data)
    {
        $url = parse_url($data['url']);
        if (!isset($url['host'])) {
            $baseUrl = 'file://' . $this->container->get('kernel')->getProjectDir().'/tests/Fixtures/rss';
            $data['url'] = $baseUrl.'/'.ltrim($data['url'], '/');
        }
        $feed = $this->setValues(new Feed(), $data);
        $this->persist($feed);
    }
}
