<h1>Elasticsearch Service Example</h1>
<li><a href="https://docs.platform.sh/add-services/elasticsearch.html">https://docs.platform.sh/add-services/elasticsearch.html</a></li>

<?php

require __DIR__.'/../vendor/autoload.php';

use Elasticsearch\ClientBuilder;
use Platformsh\ConfigReader\Config;

// Create a new config object to ease reading the Platform.sh environment variables.
// You can alternatively use getenv() yourself.
$config = new Config();

// Get the credentials to connect to the Elasticsearch service.
$credentials = $config->credentials('essearch');

try {
    // The Elasticsearch library lets you connect to multiple hosts.
    // On Platform.sh Standard there is only a single host so just
    // register that.
    $hosts = [
        [
            'scheme' => $credentials['scheme'],
            'host' => $credentials['host'],
            'port' => $credentials['port'],
        ]
    ];

    // Create an Elasticsearch client object.
    $builder = ClientBuilder::create();
    $builder->setHosts($hosts);
    $client = $builder->build();
} catch (\Exception $e) {
    print "<h3>Error creating client</h3>";
    print $e->getMessage();
}

try {    
    $index = 'my_index';
    $type = 'People';

    // Index a few document.
    $params = [
        'index' => $index,
        'type' => $type,
    ];

    $names = ['Ada Lovelace', 'Alonzo Church', 'Barbara Liskov'];

    foreach ($names as $name) {
        $params['body']['name'] = $name;
        $client->index($params);
    }
} catch (\Exception $e) {
    print "<h3>Error adding index</h3>";
    print $e->getMessage();
}

try {
    // Force just-added items to be indexed.
    $client->indices()->refresh(array('index' => $index));

} catch (\Exception $e) {
    print "<h3>Error refreshing indices</h3>";
    print $e->getMessage();
}

try {
    // Search for documents.
    $result = $client->search([
        'index' => $index,
        'type' => $type,
        'body' => [
            'query' => [
                'match' => [
                    'name' => 'Barbara Liskov',
                ],
            ],
        ],
    ]);

    if (isset($result['hits']['hits'])) {
        print <<<TABLE
<table>
<thead>
<tr><th>ID</th><th>Name</th></tr>
</thead>
<tbody>
TABLE;
        foreach ($result['hits']['hits'] as $record) {
            printf("<tr><td>%s</td><td>%s</td></tr>\n", $record['_id'], $record['_source']['name']);
        }
        print "</tbody>\n</table>\n";
    }
} catch (\Exception $e) {
    print "<h3>Error searching documents</h3>";
    print $e->getMessage();
}

try {
    // Delete documents.
    $params = [
        'index' => $index,
        'type' => $type,
    ];

    $ids = array_map(function($row) {
        return $row['_id'];
    }, $result['hits']['hits']);

    foreach ($ids as $id) {
        $params['id'] = $id;
        $client->delete($params);
    }
} catch (\Exception $e) {
    print "<h3>Error deleting documents</h3>";
    print $e->getMessage();
}