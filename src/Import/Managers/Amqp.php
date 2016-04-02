<?php

namespace App\Import\Managers;

use Silex\{
    Application
};
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpFoundation\File;

Class Amqp
{

    public $message;

    function addQueue()
    {
        $exchange = 'my_exchange';
        $queue = CONFIG['amqp']['parse_queue'];
        $connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
        $channel = $connection->channel();
        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange, 'fanout', false, false, false);
        $channel->queue_bind($queue, $exchange);
        $message = new AMQPMessage($this->message,
            array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $channel->basic_publish($message, $exchange);
        $channel->close();
        $connection->close();

    }


    public function readMessage()
    {
        $this->message = unserialize(json_decode(json_encode((array)$this->message), true));

    }

    


}