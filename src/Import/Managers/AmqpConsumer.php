<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 4:19
 */

namespace App\Import\Managers;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AmqpConsumer
{
    private $exchange;
    private $queue;
    private $consumerTag;
    private $connection;
    private $channel;

    function __construct()
    {
        $this->exchange = 'router';
        $this->queue = 'msgs';
        $this->consumerTag = 'consumer';
        $this->connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
        $this->channel = $this->connection->channel();
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
        $this->channel->queue_declare($this->queue, false, true, false, false);
        /*
            name: $exchange
            type: direct
            passive: false
            durable: true // the exchange will survive server restarts
            auto_delete: false //the exchange won't be deleted once the channel is closed.
        */
        $this->channel->exchange_declare($this->exchange, 'direct', false, true, false);
        $this->channel->queue_bind($this->queue, $this->exchange);
        $this->channel->basic_consume($this->queue, $this->consumerTag, false, false, false, false, 'process_message');
        register_shutdown_function('shutdown', $this->channel, $this->connection);
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    public function readMessage()
    {
        $this->message = unserialize(json_decode(json_encode((array)$this->message), TRUE));

    }

    function process_message($message)
    {
        return $message->body;
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        // Send a message with the string "quit" to cancel the consumer.
        if ($message->body === 'quit') {
            $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
        }
    }

    function shutdown($channel, $connection)
    {
        $channel->close();
        $connection->close();
    }


}