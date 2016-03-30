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
    private $documentObject   = null;

    function checkFile($file)
    {

        $ext = $file->getClientOriginalExtension();
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
        try {
            switch ($this->importFormat) {
                case CONFIG['format']['xml'];
                    $this->message = 'Вы залили ХМЛ';
                    $this->importFormat = CONFIG['format']['xml'];
                    break;
                case CONFIG['format']['csv'];
                    $this->message = 'Вы залили CSV';
                    $this->importFormat = CONFIG['format']['csv'];
                    break;
            }
        } catch (\Exception $e) {

        }
    }

}