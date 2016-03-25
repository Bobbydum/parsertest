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
    protected $ampqManager = null;

    public function startImport()
    {
        ini_set("memory_limit", "8G");

        $ret = false;


        // Проверяем пуста ли очередь
        if (getObject('price:priceimportqueue')->isQueueEmpty($this->userObject->userID)) {

            $this->infoObject->clearLog();

            if ($filePath = $this->saveFileForUser($this->userObject->xmlImportUrl, $this->userObject->getID())) {
                // parse document - get array of possible prices
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);

                $this->fileExtension = strtolower($extension);

                try {
                    switch (strtolower($extension)) {
                        case 'xls':
                        case 'xlsx':
                            $this->documentObject = getObject('document')->readXLSAsAssocArrayByFirstField($filePath,
                                false, false);
                            break;
                        case 'xml':
                        case 'yml':
                            $xml = $this->readXMLAsAssocArray($filePath);
                            $this->documentObject = $this->readXMLAsAssocArrayByFirstField($xml);
                            break;
                    }
                } catch (Exception $e) {
                    // помилки парсингу обробляються у CImportExportManager::validateFile()
                    throw new CI_XOException("Can't start import: you have a problem with readXLSAsAssocArrayByFirstField or readXMLAsAssocArrayByFirstField");
                }

                if (!$this->validateFile()) {
                    return false;
                }

                $ret = $this->importData();
            } else {
                $this->infoObject->getObjectInProgressByType(Cxml_import_info::TYPE_FILELOAD)->endWorkWithError(false,
                    translate('couldNotDownloadFile', 'views'));
                xmlimport_log(6, 0, array($this->userObject->userID));
            }
        } else {
            $this->infoObject->getImportObjectByType(Cxml_import_info::TYPE_START_IMPORT)->endWorkWithError(false,
                translate('previousImportsAreNotCompleted', 'views'));
            xmlimport_log(5, 0, array($this->userObject->userID));
        }


        return $ret;
    }

    public function importData()
    {
        $this->sendImportDataValidationStartNotices();

        $this->setUserDataForImport();
        $this->setPortalDataForImport();

        $priceImportQueueArr = array();

        foreach ($this->documentObject['data'] as $dataItem) {
            try {
                $priceImportQueueArr[] = $this->getImportQueueItem($dataItem);
            } catch (Exception $e) {
                $this->addDataImportWarning($e->getMessage());
            }
        }

        $importErrors = $this->getDataImportErrors(true);
        $importWarnings = $this->getDataImportWarnings(true);

        $this->sendImportDataValidationEndNotices($importErrors, $importWarnings);

        $this->addDataToQueue($priceImportQueueArr);

        return ($importErrors || $importWarnings) ? false : true;
    }

    private function sendImportDataValidationStartNotices()
    {
        $this->infoObject->getObjectInProgressByType(Cxml_import_info::TYPE_VALIDATE_FILE_DATA)->startWork();
        xmlimport_log(8, 0, array($this->userObject->userID));
    }

    private function setUserDataForImport()
    {
        $this->setXPriceIdRelations();
        $this->setXPriceCatalogRelations();
        $this->setXPriceDimensionRelations();
    }

    private function setPortalDataForImport()
    {
        $this->setPortalCatalogs();
        $this->setPortalCurrencies();
        $this->setPortalMeasureTypes();
    }

    private function sendImportDataValidationEndNotices($importErrors, $importWarnings)
    {
        if ($importErrors) {
            $this->infoObject->getObjectInProgressByType(Cxml_import_info::TYPE_VALIDATE_FILE_DATA)->endWorkWithError(false,
                "<div class='error-xml-block'><div class='error-uploading'>" . translate("errUploadingPricesXLS",
                    "views") . '</div><div class="error-text">' . implode('</div><div class="error-text">',
                    $this->getDataImportProblems(true)) . '</div></div>');
            xmlimport_log(9, 0, array($this->userObject->userID, $this->getDataImportProblems(true)));
        } elseif ($importWarnings) {
            $this->infoObject->getObjectInProgressByType(Cxml_import_info::TYPE_VALIDATE_FILE_DATA)->endWorkWithWarning(false,
                "<div class='error-xml-block'><div class='error-uploading'>" . translate("errUploadingPricesXLS",
                    "views") . '</div><div class="error-text">' . implode('</div><div class="error-text">',
                    $importWarnings) . '</div></div>');
            xmlimport_log(9, 0, array($this->userObject->userID, $importWarnings));
        } else {
            $this->infoObject->getObjectInProgressByType(Cxml_import_info::TYPE_VALIDATE_FILE_DATA)->endWork();
        }
    }

    private function addDataToQueue($priceImportQueueArr)
    {
        $this->sendImportQueueCreationStartNotices();

        $this->initOtherAmpqManagers();

        $queid = $this->getImportQueueId();
        /** @var CAmpq_manager $ampqManager */
        $ampqManager = $this->ampqManager->loadByQueueID($queid);
        $endQueueCallback =
            $this->importType == self::IMPORT_TYPE_DO_NOT_MODERATE
                ? 'price->finishPriceImport'
                : 'price->clearImportedPricesTable';
//        $endQueueCallback = 'price->finishPriceImport';
        $ampqServer = $ampqManager->queueDeclare(
            array('queue' => $queid),
            $endQueueCallback,
            array('userID' => $this->userObject->userID),
            'scr/ampq_server/subscribe_import_item'
        );
        $ampqManager->activateQueue();

        $ampqManager->subscribeAll();

        foreach ($priceImportQueueArr as $priceQueueItem) {
            // Добавляем данные в очередь
            $ampqServer->publish(serialize($priceQueueItem->queueData));
        }

        $count = count($priceImportQueueArr);

        $this->sendImportQueueCreationEndNotices($count);
    }

    private function sendImportQueueCreationStartNotices()
    {
        $this->infoObject->getObjectInProgressByType(Cxml_import_info::TYPE_CREATE_QUEUE)->startWork();
    }

    private function initOtherAmpqManagers()
    {
        $ampqManagerb = $this->ampqManager->loadByQueueID('pricebranches');
        $ampqManagerb->queueDeclare(array('queue' => 'pricebranches', 'consumerwaittime' => 10), '', '',
            'scr/ampq_server/saveimportpricebranches_consumer', 0, null, 1);
        $ampqManagerb->activateQueue();
        $ampqManageri = $this->ampqManager->loadByQueueID('importphotos');
        $ampqManageri->queueDeclare(array('queue' => 'importphotos', 'consumerwaittime' => 10), '', '',
            trim(getConfig('domain->images'), '/') . '/image/creataphotoconsumer', 0, null,
            getConfig('ampq->consumers_per_images_queue'));
        $ampqManageri->activateQueue();

        $ampqManageris = getObject('ampq_server:ampq_manager')->loadByQueueID('save_imported_photos');
        $ampqManageris->queueDeclare(array('queue' => 'save_imported_photos', 'consumerwaittime' => 10), '', '',
            'scr/ampq_server/save_imported_photos', 0, null, 2);
        $ampqManageris->activateQueue();

    }

    private function getImportQueueId()
    {
        return sprintf('import-%d', $this->userObject->userID);
    }

    function startWork($type = false, $comment = '')
    {
        $this->status = self::STATUS_IN_PROGRESS;
        $this->save($type, $comment);
    }

}