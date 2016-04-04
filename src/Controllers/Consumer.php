<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 4:31
 */

namespace App\Controllers;

use App\Import\Managers\AmqpConsumer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Consumer extends Command
{
    function execute(InputInterface $input, OutputInterface $output)
    {
        new AmqpConsumer();
        $output->writeln('Start consumer');
    }

    protected function configure()
    {
        $this
            ->setName('consumer');
    }

}
