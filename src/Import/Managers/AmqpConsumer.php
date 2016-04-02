<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 4:19
 */

namespace App\Import\Managers;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Silex\Application;
use App\Import\Managers\Import;

class AmqpConsumer
{
    private $exchange;
    private $queue;
    private $consumerTag;
    private $connection;
    private $channel;

    function __construct()
    {

        $this->exchange = 'my_exchange';
        $this->queue = CONFIG['amqp']['parse_queue'];
        $this->consumerTag = 'consumer' . getmypid();
        $this->connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($this->queue, false, true, false, false);
        $this->channel->exchange_declare($this->exchange, 'fanout', false, false, false);
        $this->channel->queue_bind($this->queue, $this->exchange);
        $this->channel->basic_consume($this->queue, $this->consumerTag, false, false, false, false,
            function ($message) {
                $importManager = new Import();
                $this->message = $message->body;

                $importManager->data = $this->message;

                $importManager->parseData();

                $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

                if ($message->body === 'quit') {
                    $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
                }
            });

        register_shutdown_function(function ($channel, $connection) {
            $channel->close();
            $connection->close();
        }, $this->channel, $this->connection);
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }


}