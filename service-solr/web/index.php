<h1>Solr Service Example</h1>
<li><a href="https://docs.platform.sh/add-services/solr.html">https://docs.platform.sh/add-services/solr.html</a></li>
<li><a href="https://github.com/solariumphp/solarium">https://github.com/solariumphp/solarium</a></li>
<?php

require __DIR__.'/../vendor/autoload.php';

use Platformsh\ConfigReader\Config;
use Solarium\Client;

// Create a new config object to ease reading the Platform.sh environment variables.
// You can alternatively use getenv() yourself.
$config = new Config();

// Get the credentials to connect to the Solr service.
$credentials = $config->credentials('solrsearch');
$config = [
    'endpoint' => [
        'localhost' => [
            'host' => $credentials['host'],
            'port' => $credentials['port'],
            'path' => "/" . $credentials['path'],
        ]
    ]
];

// https://github.com/solariumphp/solarium#pitfall-when-upgrading-from-3x-or-4x
// So an old setting like
//    'path' => '/solr/xxxx/'
// has to be changed to something like
//    'path' => '/',
//    'collection' => 'xxxx',

$config = [
    'endpoint' => [
        'localhost' => [
            'host' => $credentials['host'],
            'port' => $credentials['port'],
            'path' => "/",
            'collection' => preg_replace('/^solr\//i', '', $credentials['path'])
        ]
    ]
];

print '<H2>Connect</H2>';
try {
    $adapter = new Solarium\Core\Client\Adapter\Curl();
    $eventDispatcher = new Symfony\Component\EventDispatcher\EventDispatcher();
    $client = new Solarium\Client($adapter, $eventDispatcher, $config);
} catch (Exception $e) {
    print '<H3>Error</H3>';
    print $e->getMessage();
}
print '<H2>Add</H2>';
try {
    // Add a document
    $update = $client->createUpdate();

    $doc1 = $update->createDocument();
    $doc1->id = 123;
    $doc1->name = 'Valentina Tereshkova';

    $update->addDocuments(array($doc1));
    $update->addCommit();

    $result = $client->update($update);
    print "Adding one document. Status (0 is success): " .$result->getStatus(). "<br />\n";
} catch (Exception $e) {
    print '<H3>Error</H3>';
    print $e->getMessage();
}
print '<H2>Select</H2>';
try {

    // Select one document
    $query = $client->createQuery($client::QUERY_SELECT);
    $resultset = $client->execute($query);
    print  "Selecting documents (1 expected): " .$resultset->getNumFound() . "<br />\n";
} catch (Exception $e) {
    print '<H3>Error</H3>';
    print $e->getMessage();
}
print '<H2>Delete</H2>';
try {
    // Delete one document
    $update = $client->createUpdate();

    $update->addDeleteById(123);
    $update->addCommit();
    $result = $client->update($update);
    print "Deleting one document. Status (0 is success): " .$result->getStatus(). "<br />\n";

} catch (Exception $e) {
    print '<H3>Error</H3>';
    print $e->getMessage();
}
