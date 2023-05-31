<h1>MongoDB Service Example</h1>
<li><a href="https://docs.platform.sh/add-services/mongodb.html">https://docs.platform.sh/add-services/mongodb.html</a></li>
<?php

require __DIR__.'/../vendor/autoload.php';

use Platformsh\ConfigReader\Config;
use MongoDB\Client;

// Create a new config object to ease reading the Platform.sh environment variables.
// You can alternatively use getenv() yourself.
$config = new Config();

// The 'database' relationship is generally the name of primary database of an application.
// It could be anything, though, as in the case here here where it's called "mongodb".
$credentials = $config->credentials('database');

$MAX = 50;
try {
    $server = sprintf('%s://%s:%s@%s:%d/%s',
        $credentials['scheme'],
        $credentials['username'],
        $credentials['password'],
        $credentials['host'],
        $credentials['port'],
        $credentials['path']
    );

    $client = new Client($server);
    $collection = $client->main->starwars;

    date_default_timezone_set('UTC');

    $result = $collection->insertOne([
        'date' => date("Y-m-d H:i:s T"),
        'name' => 'Rey',
        'occupation' => 'Jedi',
    ]);

    $id = $result->getInsertedId();
    $document = $collection->findOne([
        '_id' => $id,
    ]);

    printf("Found %s records<br />\n", $collection->count());
    printf("Found %s: %s (%s)<br />\n", $document->_id, $document->date, $document->name, $document->occupation);

    $result = $collection->find();
    foreach ($result as $document) {
        printf("- Found %s: %s (%s)<br />\n", $document->_id, $document->date, $document->name, $document->occupation);
    }
    
    $count = $collection->count();
    if($count > $MAX) {
        printf("More than $MAX records, dropping collection");
        $collection->drop();
    }

} catch (\Exception $e) {
    print "<h3>Error</h3>";
    print $e->getMessage();
}
?>