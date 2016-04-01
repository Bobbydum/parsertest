<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 25.03.16
 * Time: 21:59
 */

namespace App\Import\Managers;


class ImportFile
{
    public $file = null;
    public $filepath = null;
    public $dataObject = [];

    function importCsv()
    {
        $row = 1;
        if (($handle = fopen($this->filepath, "r")) !== false) {
            while (($data = fgetcsv($handle, 0, "\t")) !== false) {
                $row++;
                $this->dataObject[] = $data;
            }
            fclose($handle);
            unlink($this->filepath);
            $rows = array_shift($this->dataObject);
            foreach ($this->dataObject as &$data) {
                $data = array_combine($rows, $data);
            }
        }
    }

    function importXml()
    {
        $handle = fopen($this->filepath, "r");
        $contents = '';
        while (!feof($handle)) {
            $contents .= fread($handle, 8192);
        }
        $xml = simplexml_load_string($contents);

        foreach ($xml as $data) {
            $this->dataObject[] = (array)$data;
        }
    }


}

