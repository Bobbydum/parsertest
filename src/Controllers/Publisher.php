<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 19:57
 */

namespace App\Controllers;

use App\Import\Managers\Import;
use App\Import\Models\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Publicher extends Command
{

    function execute(InputInterface $input, OutputInterface $output)
    {
        $importManager = new Import();
        $user = new User();
        $allUsers = $user->getAllUserForImport();
        foreach ($allUsers as $user) {
            $userId = $user['user_id'];
            $filePath = $user['url_for_parse'];
            $importManager->userId = $userId;
            $importManager->checkFile($filePath);
            $importManager->importFile();
            $importManager->createQueue();
            $output->writeln('Start consumer');
        }
    }

    protected function configure()
    {
        $this
            ->setName('publisher');
    }

}