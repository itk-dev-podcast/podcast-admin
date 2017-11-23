<?php

use AppBundle\Entity\Feed;
use AppBundle\Entity\Item;
use AppBundle\Service\CategoryManager;
use AppBundle\Service\FeedReader;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /** @var ContainerInterface  */
    private $container;

    /**
     * @var SchemaTool
     */
    private $schemaTool;

    /**
     * @var array
     */
    private $classes;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(ManagerRegistry $doctrine, ContainerInterface $container)
    {
        $this->doctrine = $doctrine;
        $this->container = $container;
        $this->manager = $doctrine->getManager();
        $this->schemaTool = new SchemaTool($this->manager);
        $this->classes = $this->manager->getMetadataFactory()->getAllMetadata();
    }

    /**
     * @BeforeScenario @createSchema
     */
    public function createDatabase()
    {
        $this->schemaTool->createSchema($this->classes);
    }

    /**
     * @AfterScenario @dropSchema
     */
    public function dropDatabase()
    {
        $this->schemaTool->dropSchema($this->classes);
    }

    /**
     * @Given feed :url is read and all items are published
     */
    public function feedIsReadAndAllItemsArePublished($url)
    {
        $baseUrl = 'file://' . $this->container->get('kernel')->getProjectDir().'/tests/Fixtures/rss';
        $url = $baseUrl.'/'.ltrim($url, '/');
        $feed = (new Feed())
            ->setTitle($url)
            ->setUrl($url)
            ->setEnabled(true);
        $this->manager->persist($feed);
        $this->container->get(FeedReader::class)->read($feed, $this->manager, $this->container->get(CategoryManager::class), $this->container->get('logger'));
        $publishedAt = new \DateTime('-1 day');
        foreach ($this->manager->getRepository(Item::class)->findAll() as $item) {
            $item->setPublishedAt($publishedAt);
            $this->manager->persist($item);
        }
        $this->manager->flush();
    }
}
