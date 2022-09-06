<h1>Redis Service Example</h1>
<li><a href="https://docs.platform.sh/add-services/redis.html">https://docs.platform.sh/add-services/redis.html</a></li>

<?php

require __DIR__.'/../vendor/autoload.php';

use Platformsh\ConfigReader\Config;

// Create a new config object to ease reading the Platform.sh environment variables.
// You can alternatively use getenv() yourself.
$config = new Config();

// Get the credentials to connect to the Redis service.
$credentials = $config->credentials('rediscache');

try {
    print "<h3>Connect</h3>";
    // Connecting to Redis server.
    $redis = new Redis();
    $redis->connect($credentials['host'], $credentials['port']);
} catch (\Exception $e) {
    print "<h3>Error connecting to Redis</h3>";
    print $e->getMessage();
}
try {
    print "<h3>Set</h3>";
    $key = "Deploy day";
    $value = "Friday";

    // Set a value.
    $result = $redis->set($key, $value);
    print "<pre>" . print_r($result, true). "</pre>";
} catch (\Exception $e) {
    print "<h3>Error setting key</h3>";
    print $e->getMessage();
}

try {
    print "<h3>Read</h3>";
    // Read it back.
    $test = $redis->get($key);

    printf('Found value <strong>%s</strong> for key <strong>%s</strong>.', $test, $key);
    print "<pre>" . print_r($test, true). "</pre>";

} catch (\Exception $e) {
    print "<h3>Error getting key</h3>";
    print $e->getMessage();
}

$credentials = $config->credentials('redisdata');

try {
    print "<h3>Connect</h3>";
    // Connecting to Redis server.
    $redis = new Redis();
    $redis->connect($credentials['host'], $credentials['port']);
} catch (\Exception $e) {
    print "<h3>Error connecting to Redis</h3>";
    print $e->getMessage();
}
try {
    print "<h3>Set</h3>";
    $key = "Deploy day";
    $value = "Friday";

    // Set a value.
    $result = $redis->set($key, $value);
    print "<pre>" . print_r($result, true). "</pre>";
} catch (\Exception $e) {
    print "<h3>Error setting key</h3>";
    print $e->getMessage();
}

try {
    print "<h3>Read</h3>";
    // Read it back.
    $test = $redis->get($key);

    printf('Found value <strong>%s</strong> for key <strong>%s</strong>.', $test, $key);
    print "<pre>" . print_r($test, true). "</pre>";

} catch (\Exception $e) {
    print "<h3>Error getting key</h3>";
    print $e->getMessage();
}