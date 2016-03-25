<?php
namespace App\Import\Provider;

use App\Import\Interfaces\Ampq;

class AmpqProvider implements Ampq
{

    const STATE_NEW = 0;
    const STATE_CREATED = 1;
    const STATE_IN_WORK = 2;
    public $queueID;
    public $params;
    public $queueconsumercontroller;
    public $queueendcallback;
    public $queueendcallbackarguments;
    public $checkmaxcount;
    public $consumers;
    protected $ampqServer = null;
    private $consumersPerQueue = 5;
    private $maxConsumers = 50;
    private $globalConsumersCount = 0;

    public function __construct()
    {
        $this->consumersPerQueue = (getConfig('ampq->consumers_per_queue')) ? getConfig('ampq->consumers_per_queue') : $this->consumersPerQueue;
        $this->maxConsumers = (getConfig('ampq->max_consumers')) ? getConfig('ampq->max_consumers') : $this->maxConsumers;
        parent::__construct(__FILE__);
    }

    public function getServer()
    {
        return $this->ampqServer;
    }

    public function queueDeclare(
        $params,
        $queueendcallback,
        $queueendcallbackarguments,
        $queueconsumercontroller,
        $checkmaxcount = 1,
        $save = true,
        $consumers = false
    ) {
        $queueInfo = $this->ampqServer->queueDeclare($params);
        $this->queueID = $queueInfo[0];
        $this->params = $params;
        $this->queueconsumercontroller = $queueconsumercontroller;
        $this->queueendcallback = $queueendcallback;
        $this->queueendcallbackarguments = $queueendcallbackarguments;
        $this->checkmaxcount = $checkmaxcount;
        $this->consumers = ($consumers) ? $consumers : $this->consumersPerQueue;
        if ($save) {
            $this->save();
        }
        return $this->ampqServer;
    }

    public function activateQueue()
    {
        $this->state = self::STATE_CREATED;
        $this->save();
    }

    public function subscribe($callback)
    {
        $this->ampqServer->queueDeclare($this->params);
        $this->ampqServer->subscribe($callback);
        $this->ampqServer->listen();
        $this->ampqServer->close();
    }

    public function loadByQueueID($queueID)
    {
        $obj = clone $this->getStaticDatasource('dsFullManager', array('queueID' => $queueID))->loadObj();
        $obj->inject(array('ampqServer' => getObject('ampq_server:rabbitmq', true)));
        return $obj;
    }

    public function subscribeAll()
    {
        $managers = $this->getStaticDatasource('dsManagers')->getData();
        $this->globalConsumersCount = $this->getConsumersCount($managers);
        foreach ($managers as $manager) {
            $manager->inject(array('ampqServer' => getObject('ampq_server:rabbitmq', true)));
            for ($i = 0; $i < $manager->consumers; $i++) {
                $info = $manager->ampqServer->getQueueInfo($manager->queueID);
                if ($info['consumers'] >= $manager->consumers) {
                    break;
                }

                if ($manager->checkmaxcount) {
                    $this->globalConsumersCount++;
                }

                if ($this->globalConsumersCount < $this->maxConsumers) {
                    $url = (stripos($manager->queueconsumercontroller,
                            'http:') === false) ? site_url("{$manager->queueconsumercontroller}/{$manager->getID()}") : $manager->queueconsumercontroller;
                    `wget -O - -bq $url`;
                }
            }
            $manager->state = self::STATE_IN_WORK;
            $manager->save();
        }
    }

    private function getConsumersCount($managers)
    {
        foreach ($managers as $manager) {
            if ($manager->checkmaxcount) {
                $info = $manager->ampqServer->getQueueInfo($manager->queueID);
                $this->globalConsumersCount += isset($info['consumers']) ? $info['consumers'] : 0;
            }
        }
        return $this->globalConsumersCount;
    }

    public function getCheckQueueStateAndCloseIfNeed()
    {
        $managers = $this->getStaticDatasource('dsActiveManagers')->getData();
        foreach ($managers as $manager) {
            /** @var self $manager */
            $manager->inject(array('ampqServer' => getObject('ampq_server:rabbitmq', true)));
            $info = $this->ampqServer->getQueueInfo($manager->queueID);
            if (($info['length'] == 0) && ($info['consumers'] == 0)) {
                try {
                    $manager->runCallback();
                } catch (Exception $e) {
                }
                $manager->deleteAndUnsubscribe();
            }
        }
    }

    public function runCallback()
    {
        if (stripos($this->queueendcallback, '->') !== false) {
            $run = explode('->', $this->queueendcallback);
            call_user_func(array(getObject($run[0]), $run[1]), $this->queueendcallbackarguments);
        }
    }

    public function deleteAndUnsubscribe()
    {
        $this->ampqServer->unsubscribe($this->queueID);
        $this->delete();
    }

    public function getQueueLength()
    {
        $info = $this->ampqServer->getQueueInfo($this->queueID);
        return isset($info['length']) ? $info['length'] + $info['consumers'] : 0;
    }

    public function close()
    {
        $this->ampqServer->close();
    }
}
