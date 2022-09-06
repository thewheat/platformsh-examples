<h1>RabbitMQ Service Example</h1>
<li><a href="https://docs.platform.sh/add-services/rabbitmq.html">https://docs.platform.sh/add-services/rabbitmq.html</a></li>
<li><a href="https://www.rabbitmq.com/tutorials/tutorial-one-php.html">https://www.rabbitmq.com/tutorials/tutorial-one-php.html</a></li>

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
    print "<h3>Connect</h3>";
    // Connect to the RabbitMQ server.
    $connection = new AMQPStreamConnection($credentials['host'], $credentials['port'], $credentials['username'], $credentials['password']);
} catch (\Exception $e) {
    print "<h3>Error</h3>";
    print $e->getMessage();
}

try {
    print "<h3>Create Queue in Channel</h3>";
    $queueName = 'the_queue';
    $channel = $connection->channel();
    $result = $channel->queue_declare($queueName, false, false, false, false);
    print "<pre>" . print_r($result, true). "</pre>";
} catch (\Exception $e) {
    print "<h3>Error</h3>";
    print $e->getMessage();
}

try {
    print "<h3>Send Data</h3>";

    $text = 'Hello World @ ' . date('Y-m-d H:i:s');
    $msg = new AMQPMessage($text);
    $result = $channel->basic_publish($msg, '', $queueName);
    print "[x] Sent '$text'<br/>\n";
    print "<pre>" . print_r($result, true). "</pre>";
} catch (\Exception $e) {
    print "<h3>Error</h3>";
    print $e->getMessage();
}

try {
    print "<h3>Read Data</h3>";

    // In a real application you't put the following in a separate script in a loop.
    $callback = function ($msg) {
        printf("[x] Received '%s'<br />\n", $msg->body);
    };

    $result = $channel->basic_consume($queueName, '', false, true, false, false, $callback);

    if ($channel->is_open()) { // a while() loop to continually consume incoming messages
        $channel->wait();      // wait for a message to be in the queue
    }
    print "<pre>" . print_r($result, true). "</pre>";
} catch (\Exception $e) {
    print "<h3>Error</h3>";
    print $e->getMessage();
}

try {
    print "<h3>Close Channel</h3>";
    $result = $channel->close();
    print "<pre>" . print_r($result, true). "</pre>";
} catch (\Exception $e) {
    print "<h3>Error</h3>";
    print $e->getMessage();
}

try {
    print "<h3>Close Connection</h3>";
    $result = $connection->close();
    print "<pre>" . print_r($result, true). "</pre>";
} catch (Exception $e) {
    print "<h3>Error</h3>";
    print $e->getMessage();
}
