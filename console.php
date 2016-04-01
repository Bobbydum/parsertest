<?php
require('vendor/autoload.php');
require('config.php');

use App\Import\Managers\AmqpConsumer;

$message = new AmqpConsumer();

//define('HOST', 'localhost');
//define('PORT', 5672);
//define('USER', 'guest');
//define('PASS', 'guest');
//define('VHOST', '/');
//define('AMQP_DEBUG', true);
//use PhpAmqpLib\Connection\AMQPStreamConnection;
//$exchange = 'my_exchange';
//$queue = 'parser';
//$consumerTag = 'consumer' . getmypid();
//$connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
//$channel = $connection->channel();
//list($queueName, ,) = $channel->queue_declare($queue, false, true, false, false);
//$channel->exchange_declare($exchange, 'fanout', false, false, false);
//$channel->queue_bind($queueName, $exchange);
///**
// * @param \PhpAmqpLib\Message\AMQPMessage $message
// */
//function process_message($message)
//{
//    echo "\n--------\n";
//    echo $message;
//    echo "\n--------\n";
//    $app = new \Silex\Application();
//    $app->register(new Silex\Provider\MonologServiceProvider(), array(
//        'monolog.logfile' => __DIR__.'/development.log',
//    ));
//    $app['monolog']->addInfo(sprintf("User '%s' registered.", $message->body));
//    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
//    // Send a message with the string "quit" to cancel the consumer.
//    if ($message->body === 'quit') {
//        $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
//    }
//}
//
//$channel->basic_consume($queueName, $consumerTag, false, false, true, false, 'process_message');
//
//function shutdown($channel, $connection)
//{
//    $channel->close();
//    $connection->close();
//}
//
//register_shutdown_function('shutdown', $channel, $connection);
//while (count($channel->callbacks)) {
//    $channel->wait();
//}
//https://github.com/php-amqplib/php-amqplib