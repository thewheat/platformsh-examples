<h1>MySQL Service Example</h1>
<li><a href="https://docs.platform.sh/add-services/mysql.html">https://docs.platform.sh/add-services/mysql.html</a></li>

<?php

require __DIR__.'/../vendor/autoload.php';

use Platformsh\ConfigReader\Config;

// Create a new config object to ease reading the Platform.sh environment variables.
// You can alternatively use getenv() yourself.
$config = new Config();

// The 'database' relationship is generally the name of primary SQL database of an application.
// That's not required, but much of our default automation code assumes it.
$credentials = $config->credentials('database');

try {
    // Connect to the database using PDO.  If using some other abstraction layer you would
    // inject the values from $database into whatever your abstraction layer asks for.
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s', $credentials['host'], $credentials['port'], $credentials['path']);
    $conn = new \PDO($dsn, $credentials['username'], $credentials['password'], [
        // Always use Exception error mode with PDO, as it's more reliable.
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        // So we don't have to mess around with cursors and unbuffered queries by default.
        \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE,
        // Make sure MySQL returns all matched rows on update queries including
        // rows that actually didn't have to be updated because the values didn't
        // change. This matches common behavior among other database systems.
        \PDO::MYSQL_ATTR_FOUND_ROWS => TRUE,
    ]);
} catch (\Exception $e) {
    print "<h3>Error connecting to database</h3>";
    print $e->getMessage();
    $conn = null;
}

if($conn != null){
    try {    
        // Creating a table.
        $sql = "CREATE TABLE People (
          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(30) NOT NULL,
          city VARCHAR(30) NOT NULL,
          dt DATETIME NOT NULL
          )";
        $conn->query($sql);
    } catch (\Exception $e) {
        print "<h3>Error creating table</h3>";
        print $e->getMessage();
    }
    
    try {
        // Insert data.
        $sql = "INSERT INTO People (name, city, dt) VALUES
            ('Neil Armstrong', 'Moon', NOW()),
            ('Buzz Aldrin', 'Glen Ridge', NOW()),
            ('Sally Ride', 'La Jolla', NOW());";
        $conn->query($sql);
    } catch (\Exception $e) {
        print "<h3>Error inserting data into table</h3>";
        print $e->getMessage();
    }
    
    try {
        // Show table.
        $sql = "SELECT * FROM People";
        $result = $conn->query($sql);
        $result->setFetchMode(\PDO::FETCH_OBJ);
    
        if ($result) {
            print <<<TABLE
    <table border='1'>
    <thead>
    <tr><th>Name</th><th>City</th><th>Date Time Created</th></tr>
    </thead>
    <tbody>
    TABLE;
            foreach ($result as $record) {
                printf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>\n", $record->name, $record->city, $record->dt);
            }
            print "</tbody>\n</table>\n";
        }
    
    } catch (\Exception $e) {
        print "<h3>Error retrieving data from table</h3>";
        print $e->getMessage();
    }

    try {
        // Drop table
        $sql = "DROP TABLE People";
        $conn->query($sql);
    
    } catch (\Exception $e) {
        print "<h3>Error dropping table</h3>";
        print $e->getMessage();
    }
}
