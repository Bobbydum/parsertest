<?php

namespace App\Import\Managers;

use Silex\{
    Application
};
use Symfony\Component\HttpFoundation\File;


Class Import
{

    public $importFormat;
    public $message;
    public $file;
    public $userId;
    public $valideFile = false;
    public $dataObject;
    private $filepath = null;
    public $data;

    function checkFile($filepath)
    {

        $file = new \SplFileObject($filepath);
        $this->filepath = $file->getPathname();
        $ext = $file->getExtension();

        try {

            switch ($ext) {
                case CONFIG['format']['xml'];
                    $this->message[] = 'Вы залили ХМЛ';
                    $this->importFormat = CONFIG['format']['xml'];
                    $this->valideFile = true;
                    break;
                case CONFIG['format']['csv'];
                    $this->message[] = 'Вы залили CSV';
                    $this->importFormat = CONFIG['format']['csv'];
                    $this->valideFile = true;
                    break;
            }

            $this->file = $file;

        } catch (\Exception $e) {

        }

    }

    function importFile()
    {
        $importter = new ImportFile();
        $importter->file = $this->file;
        $importter->filepath = $this->filepath;

        try {
            switch ($this->importFormat) {
                case CONFIG['format']['xml'];
                    $this->importFormat = CONFIG['format']['xml'];
                    $importter->importXml();
                    $this->dataObject = $importter->dataObject;
                    break;
                case CONFIG['format']['csv'];
                    $this->importFormat = CONFIG['format']['csv'];
                    $importter->importCsv();
                    $this->dataObject = $importter->dataObject;
                    break;
            }

        } catch (\Exception $e) {

        }
    }

    function createQueue()
    {
        $amqpManager = new Amqp();
        $amqpManager->message = ['user_id' => $this->userId, 'data' => $this->dataObject];
        $amqpManager->createMessage();
        $amqpManager->addQueue();

    }

    function parseData()
    {
        $array = $this->data;
        foreach ($array as $string) {
            $str = implode("###", $string);
            $fp = fopen(__DIR__ . "/Log_OF_CONSUMER.txt", "wb");
//            $fp = fopen(LOG_DIR . "/Log_OF_CONSUMER.txt", "wb");
            fwrite($fp, $str);
            fclose($fp);
        }

    }

}