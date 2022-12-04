<h1>Mounts</h1>

<a href="https://docs.platform.sh/create-apps/app-reference.html#mounts">Docs</a>

<pre>
mounts:
    'web/path/in/app':
        source: local
        source_path: 'path/in/filesystem'
    'web/things':
        source: local
        source_path: 'my_files'
    'web/path/all':
        source: local
        source_path: ''    
...
web:
    locations:
        "/":
            # The public directory of the app, relative to its root.
            root: "web"
...
</pre>

<?php
$source_filesystem_path_prefix = "/mnt/";
$web_root = "/app/web/";
$app_dirs = [
    'path/in/app' => 'path/in/filesystem',
    'things' => 'my_files',
    'path/all' => '',
];


function timestamp(){
    return date('Y-m-d H:i:s');
}

$i = 0;
foreach($app_dirs as $app_path=>$source_filesystem_path){
    $count++;
    echo "<li>";
    echo "app path: <code>" . htmlentities($app_path) . "</code><BR>";
    $base_name = "app_file" . $count . ".txt";
    $filename = $app_path . DIRECTORY_SEPARATOR . $base_name;
    $web_link = DIRECTORY_SEPARATOR . $app_path . DIRECTORY_SEPARATOR . $base_name;
    $msg = "Hello app file $filename from " . timestamp();
    echo "Writing <code>$msg</code> to <code>$filename</code>, publicly accessible at <a href='$web_link'>$web_link</a><BR>";
    file_put_contents($filename, $msg);
    $app_files = scandir($app_path);
    echo "<h3>Files in $app_path</h3>";
    echo "<PRE>";
    print_r($app_files);
    echo "</PRE>";
    echo "<HR>";

    echo "filesystem path: <code>" . htmlentities($source_filesystem_path) . "</code><BR>";
    $base_name = "source_file" . $count . ".txt";
    $filename = $source_filesystem_path_prefix . $source_filesystem_path . DIRECTORY_SEPARATOR . $base_name;
    $web_link = DIRECTORY_SEPARATOR . $app_path . DIRECTORY_SEPARATOR . $base_name;
    $msg = "Hello source file $filename from " . timestamp();
    echo "Writing <code>$msg</code> to <code>$filename</code>, publicly accessible at <a href='$web_link'>$web_link</a><BR>";
    file_put_contents($filename, $msg);
    $source_files = scandir($source_filesystem_path_prefix . $source_filesystem_path);
    echo "<h3>Files in $source_filesystem_path</h3>";
    echo "<PRE>";
    print_r($source_files);
    echo "</PRE>";
    echo "</li>";
    echo "<HR>";
    echo "<HR>";
}
?>