<?php

namespace App\Import\Managers;

use Silex\{
    Application
};
use Symfony\Component\HttpFoundation\File;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

Class Amqp
{

    public $message;

    function addQueue()
    {
        $exchange = 'fanout_exclusive_example_exchange';
        $queue = 'msgs';
        $connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
        $channel = $connection->channel();
        /*
            The following code is the same both in the consumer and the producer.
            In this way we are sure we always have a queue to consume from and an
                exchange where to publish messages.
        */
        /*
            name: $queue
            passive: false
            durable: true // the queue will survive server restarts
            exclusive: false // the queue can be accessed in other channels
            auto_delete: false //the queue won't be deleted once the channel is closed.
        */
        $channel->queue_declare($queue, false, true, false, false);
        /*
            name: $exchange
            type: direct
            passive: false
            durable: true // the exchange will survive server restarts
            auto_delete: false //the exchange won't be deleted once the channel is closed.
        */
        $channel->exchange_declare($exchange, 'fanout', false, false, true);
        $channel->queue_bind($queue, $exchange);
        $messageBody = implode(' ', array_slice($this->message, 1));
        $message = new AMQPMessage($messageBody,
            array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $channel->basic_publish($message, $exchange);
        $channel->close();
        $connection->close();

    }


    public function readMessage()
    {
        $this->message = unserialize(json_decode(json_encode((array)$this->message), TRUE));

    }

    public function createMessage()
    {
        $this->message = serialize(json_decode(json_encode((array)$this->message), TRUE));
    }


}