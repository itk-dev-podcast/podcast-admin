<?php

namespace AppBundle\Command;

use AppBundle\Entity\Item;
use AppBundle\Service\IndexingService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexingCommand extends Command
{
    private $entityManager;
    private $indexingService;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, IndexingService $indexingService, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->indexingService = $indexingService;
        $this->logger = $logger;
        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setName('app:indexing:index');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $items = $this->entityManager->getRepository(Item::class)->findAll();
        foreach ($items as $item) {
            $this->logger->notice((string) $item);
            $this->indexingService->index($item);
            $this->entityManager->persist($item);
        }
        $this->entityManager->flush();
    }
}
