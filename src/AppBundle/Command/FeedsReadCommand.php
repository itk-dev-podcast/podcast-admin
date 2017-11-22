<?php

namespace AppBundle\Command;

use AppBundle\Entity\Feed;
use AppBundle\Service\CategoryManager;
use AppBundle\Service\FeedReader;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FeedsReadCommand extends Command
{
    private $entityManager;
    private $feedReader;
    private $tagManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, FeedReader $feedReader, CategoryManager $tagManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->feedReader = $feedReader;
        $this->tagManager = $tagManager;
        $this->logger = $logger;

        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setName('app:feeds:read')
            ->setDescription('Read RSS feeds')
            ->addOption('name', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'The name of the feed')
            ->addOption('id', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'The id of the feed');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sources = $this->entityManager->getRepository(Feed::class)->findBy(['enabled' => true]);
        foreach ($sources as $source) {
            $this->logger->notice(sprintf('Reading %s', $source));
            $this->feedReader->read($source, $this->entityManager, $this->tagManager, $this->logger);
        }
    }
}
