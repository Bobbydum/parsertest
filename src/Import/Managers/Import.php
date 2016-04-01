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
        $array = unserialize($this->data);
        $this->userId = $array['user_id'];
        $this->dataObject = $array['data'];
        $fp = fopen(__DIR__ . "/log_string_from_comsumer.txt", "wb");
        fwrite($fp, print_r($this->dataObject, true));
        fclose($fp);
        foreach ($this->dataObject as $key => $value) {
//            $fp = fopen(__DIR__ . "/log_string_from_comsumer.txt", "wb");
//            fwrite($fp, implode('##',$value));
//            fclose($fp);

        }
    }
    public function readMessage()
    {
        $this->data = unserialize(json_decode(json_encode((array)$this->data), true));
    }

}