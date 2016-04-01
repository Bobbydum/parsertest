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
    private $documentObject = null;
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
                    break;
                case CONFIG['format']['csv'];
                    $this->message[] = 'Вы залили CSV';
                    $this->importFormat = CONFIG['format']['csv'];


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
                    var_dump($importter->dataObject);
                    break;
                case CONFIG['format']['csv'];
                    $this->importFormat = CONFIG['format']['csv'];
                    $importter->importCsv();
                    var_dump($importter->dataObject);
                    break;
            }
        } catch (\Exception $e) {

        }
    }

}