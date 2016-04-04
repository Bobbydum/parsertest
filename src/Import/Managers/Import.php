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
    public $data;
    private $filepath = null;

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
        $parser = new Parser();
        $userId = $parser::$userField;
        $dataField = $parser::$dataField;
        $message = $this->createMessage([$userId => $this->userId, $dataField => $this->dataObject]);
        $amqpManager->message = $message;
        $amqpManager->addQueue();

    }

    public function createMessage($message)
    {
        $message = serialize(json_decode(json_encode((array)$message), true));
        return $message;
    }

    function parseData()
    {
        $array = unserialize($this->data);
        $parser = new Parser();
        $userId = $parser::$userField;
        $dataField = $parser::$dataField;
        $parser->userId = $array[$userId];
        $parser->dataObject = $array[$dataField];
        $parser->parseData();
    }

    public function readMessage()
    {
        $this->data = unserialize(json_decode(json_encode((array)$this->data), true));
    }

}