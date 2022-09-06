<h1>RabbitMQ Service Example</h1>
<li><a href="https://docs.platform.sh/add-services/rabbitmq.html">https://docs.platform.sh/add-services/rabbitmq.html</a></li>

<?php

require __DIR__.'/../vendor/autoload.php';

use Platformsh\ConfigReader\Config;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Create a new config object to ease reading the Platform.sh environment variables.
// You can alternatively use getenv() yourself.
$config = new Config();

// Get the credentials to connect to the RabbitMQ service.
$credentials = $config->credentials('rabbitmqqueue');

try {

    $queueName = 'deploy_days';

    // Connect to the RabbitMQ server.
    $connection = new AMQPStreamConnection($credentials['host'], $credentials['port'], $credentials['username'], $credentials['password']);
    $channel = $connection->channel();

    $channel->queue_declare($queueName, false, false, false, false);

    $msg = new AMQPMessage('Friday');
    $channel->basic_publish($msg, '', 'hello');

    echo "[x] Sent 'Friday'<br/>\n";

    // In a real application you't put the following in a separate script in a loop.
    $callback = function ($msg) {
        printf("[x] Deploying on %s<br />\n", $msg->body);
    };

    $channel->close();
    $connection->close();

} catch (Exception $e) {
    print $e->getMessage();
}
