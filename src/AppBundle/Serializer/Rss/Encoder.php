<?php

namespace AppBundle\Serializer\Rss;

use AppBundle\Entity\Channel;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\scalar;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class Encoder implements EncoderInterface
{
    const FORMAT = 'rss';

    const NS_ATOM = 'atom';
    const NS_ITUNES = 'itunes';
    const NS_PODCAST = 'podcast';

    private $ns = [
        self::NS_PODCAST => 'http://podcast.example.com/podcast',
        self::NS_ATOM => 'http://www.w3.org/2005/Atom',
        self::NS_ITUNES => 'http://www.itunes.com/dtds/podcast-1.0.dtd',
    ];

    /**
     * @var \SimpleXMLElement
     */
    private $element;

    /**
     * Encodes data into the given format.
     *
     * @param mixed  $data    Data to encode
     * @param string $format  Format name
     * @param array  $context options that normalizers/encoders have access to
     *
     * @throws UnexpectedValueException
     *
     * @return scalar
     */
    public function encode($data, $format, array $context = [])
    {
        $rss = $this->createDocument();
//        if (isset($data['totalItems'])) {
//            $this->addAttribute('totalItems', $data['totalItems'], self::NS_PODCAST);
//        }
//        if (isset($data['itemsPerPage'])) {
//            $this->addAttribute('itemsPerPage', $data['itemsPerPage'], self::NS_PODCAST);
//        }
//        if (isset($data['currentPage'])) {
//            $this->addAttribute('currentPage', $data['currentPage'], self::NS_PODCAST);
//        }
        $this->startElement('channel');
        if (isset($data['_links'])) {
            $baseUrl = isset($_SERVER['REQUEST_SCHEME'], $_SERVER['SERVER_NAME']) ? $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] : '';
            $this->startElement('link', null, self::NS_ATOM, array_map(function ($url) use ($baseUrl) {
                return $baseUrl . $url;
            }, $data['_links']));
        }
        $this->addItems($data);

        return $rss->asXML();
    }

    /**
     * Checks whether the serializer can encode to given format.
     *
     * @param string $format format name
     *
     * @return bool
     */
    public function supportsEncoding($format)
    {
        return self::FORMAT === $format;
    }

    private function createDocument(): \SimpleXMLElement
    {
        $namespaces = implode(' ', array_map(function ($name, $url) {
            return 'xmlns:'.$name.'="'.$url.'"';
        }, array_keys($this->ns), $this->ns));
        $xml = '<rss version="2.0" '.$namespaces.'/>';

        $this->element = new \SimpleXMLElement($xml);

        return $this->element;
    }

    private function addAttribute(string $name, $value, string $ns = null)
    {
        $namespace = null;
        if ($ns !== null) {
            $namespace = $this->ns[$ns];
            $name = $ns.':'.$name;
        }
        $this->element->addAttribute($name, $value, $namespace);

        return $this;
    }

    private function startElement(string $name, $value = null, string $ns = null, array $attributes = null)
    {
        $namespace = null;
        if ($ns !== null && isset($this->ns[$ns])) {
            $namespace = $this->ns[$ns];
            $name = $ns.':'.$name;
        }
        $child = $this->element->addChild($name, htmlspecialchars((string) $value), $namespace);
        if ($attributes !== null) {
            foreach ($attributes as $name => $value) {
                $namespace = null;
                $ns = null;
                if (is_array($value)) {
                    list($value, $ns) = $value;
                }
                if ($ns !== null) {
                    $namespace = $this->ns[$ns];
                    $name = $ns.':'.$name;
                }
                $child->addAttribute($name, $value, $namespace);
            }
        }
        if ($value === null) {
            $this->element = $child;
        }

        return $this;
    }

    private function endElement()
    {
        $this->element = $this->element->xpath('parent::*')[0];

        return $this;
    }

    // @see https://help.apple.com/itc/podcasts_connect/#/itcb54353390
    private function addItems(array $data)
    {
        if (isset($data['_items']) && is_array($data['_items'])) {
            foreach ($data['_items'] as $item) {
                /** @var $item Item */
                $this->startElement('item')
                    ->startElement('title', $item->getTitle())
                    ->startElement('guid', $item->getGuid(), null, [
                        'isPermaLink' => $item->isGuidIsPermaLink() ? 'true' : 'false',
                    ])
                    ->startElement('description', $item->getDescription());
                if ($item->getPubDate() !== null) {
                    $this->startElement('pubDate', $item->getPubDate()->format(\DateTime::ATOM));
                }
                if (!empty($item->getEnclosure())) {
                    $this->startElement('enclosure', '', null, $item->getEnclosure());
                }
                if ($item->getDuration()) {
                    $this->startElement('duration', $this->formatDuration($item->getDuration()), self::NS_ITUNES);
                }
                foreach ($item->getCategories() as $category) {
                    $this->startElement('category', $category->getName(), self::NS_ITUNES);
                }
                foreach ($item->getTags() as $tag) {
                    $this->startElement('tag', $tag->getName(), self::NS_PODCAST);
                }

                $this->endElement();
            }
        }
    }

    private function formatDuration(int $duration)
    {
        $seconds = $duration % 60;
        $duration = intdiv($duration, 60);
        $minutes = $duration % 60;
        $duration = intdiv($duration, 60);
        $hours = $duration;

        return ltrim(sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds), '0:');
    }
}
