<h1>Opensearch Service Example</h1>
<li><a href="https://docs.platform.sh/add-services/opensearch.html">https://docs.platform.sh/add-services/opensearch.html</a></li>

<?php

require __DIR__.'/../vendor/autoload.php';

use Opensearch\ClientBuilder;
use Platformsh\ConfigReader\Config;

// Create a new config object to ease reading the Platform.sh environment variables.
// You can alternatively use getenv() yourself.
$config = new Config();

// Get the credentials to connect to the Elasticsearch service.
$credentials = $config->credentials('ossearch');

try {
    $client = (new \OpenSearch\ClientBuilder())
        ->setHosts([$credentials['scheme'] . "://" . $credentials['host'] . ":" . $credentials['port']])
        ->build();
} catch (\Exception $e) {
    print "<h3>Error creating client</h3>";
    print $e->getMessage();
}


try {    
    $indexName = 'test-index-name';
    // Create an index with non-default settings.
    $result = $client->indices()->create([
        'index' => $indexName,
        'body' => [
            'settings' => [
                'index' => [
                    'number_of_shards' => 4
                ]
            ]
        ]
    ]);
    print "<PRE>" . print_r($result, true) . "</PRE>";
} catch (\Exception $e) {
    print "<h3>Error adding index</h3>";
    print $e->getMessage();
}

try {    
    $result = $client->create([
        'index' => $indexName,
        'id' => 1,
        'body' => [
            'title' => 'Moneyball',
            'director' => 'Bennett Miller',
            'year' => 2011
        ]
    ]);

    print "<PRE>" . print_r($result, true) . "</PRE>";

} catch (\Exception $e) {
    print "<h3>Error adding document</h3>";
    print $e->getMessage();
}


try {
    // Search for documents.
    $result = $client->search([
        'index' => $indexName,
        'body' => [
            'size' => 5,
            'query' => [
                'multi_match' => [
                    'query' => 'miller',
                    'fields' => ['title^2', 'director']
                ]
            ]
        ]
    ]);

    print "<PRE>" . print_r($result, true) . "</PRE>";

} catch (\Exception $e) {
    print "<h3>Error searching documents</h3>";
    print $e->getMessage();
}

try {
    // Delete a single document
    $client->delete([
        'index' => $indexName,
        'id' => 1,
    ]);
} catch (\Exception $e) {
    print "<h3>Error deleting documents</h3>";
    print $e->getMessage();
}

try {
    // Delete index
    $client->indices()->delete([
        'index' => $indexName
    ]);
} catch (\Exception $e) {
    print "<h3>Error deleting index</h3>";
    print $e->getMessage();
}