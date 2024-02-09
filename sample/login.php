<?php

require_once __DIR__ . '/vendor/autoload.php'; // Adjust the path accordingly

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

if (!isset($_POST)) {
    $msg = "NO POST MESSAGE SET, POLITELY FUCK OFF";
    echo json_encode($msg);
    exit(0);
}

$request = $_POST;
$response = "unsupported request type, politely FUCK OFF";

switch ($request["type"]) {
    case "login":
        // Publish the message to RabbitMQ
        publishToRabbitMQ(json_encode($request));
        $response = "login, yeah we can do that";
        break;
}

echo json_encode($response);
exit(0);

function publishToRabbitMQ($message)
{
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();

    $channel->queue_declare('your_queue_name', false, true, false, false);

    $msg = new AMQPMessage($message);
    $channel->basic_publish($msg, '', 'your_queue_name');

    $channel->close();
    $connection->close();
}
?>
