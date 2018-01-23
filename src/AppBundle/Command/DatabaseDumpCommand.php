<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @see https://stackoverflow.com/a/44235455
 *
 * Class DatabaseDumpCommand
 */
class DatabaseDumpCommand extends ContainerAwareCommand
{
    /** @var OutputInterface */
    private $output;

    /** @var InputInterface */
    private $input;

    private $database;
    private $username;
    private $password;

    protected function configure()
    {
        $this->setName('app:database:dump')
            ->setDescription('Dump database.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null|int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->database = $this->getContainer()->getParameter('database_name');
        $this->username = $this->getContainer()->getParameter('database_user');
        $this->password = $this->getContainer()->getParameter('database_password');
        $this->dumpDatabase();
    }

    /**
     * Runs a system command, returns the output, what more do you NEED?
     *
     * @param $command
     * @param $streamOutput
     * @param $outputInterface mixed
     *
     * @return array
     */
    protected function runCommand($command)
    {
        $command .= ' >&1';
        exec($command, $output, $exit_status);

        return [
            'output' => $output,
            'exit_status' => $exit_status,
        ];
    }

    private function dumpDatabase()
    {
        $cmd = sprintf(
            'mysqldump --user=%s --password=%s %s',
            escapeshellarg($this->username),
            escapeshellarg($this->password),
            escapeshellarg($this->database)
        );

        $result = $this->runCommand($cmd);

        if ($result['exit_status'] > 0) {
            throw new \Exception('Could not dump database: '.var_export($result['output'], true));
        }

        echo implode(PHP_EOL, $result['output']), PHP_EOL;
    }
}
