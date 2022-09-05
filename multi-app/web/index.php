<?php

use App\Messages;

require __DIR__.'/../vendor/autoload.php';

$messages = new Messages();

printf("<h3>PHP %s</h3>\n", phpversion());
printf("<h1>%s</h1>\n", $messages->title());
printf("<p>%s</p>\n", $messages->message());

$URL = $_SERVER['HTTP_HOST'];
?>

<ul>
    <li>Default <code>URL</code> using the <code>.platform.app.yaml</code>
        <ul>
            <li><a href="//<?php echo $URL ?>"><?php echo $URL ?></a></li>
        </ul>
    </li>
    <li><code>app1.URL</code> / <code>php7.URL</code> using the <code>php7/.platform.app.yaml</code>
        <ul>
            <li><a href="//app1.<?php echo $URL ?>">app1.<?php echo $URL ?></a></li>
            <li><a href="//php7.<?php echo $URL ?>">php7.<?php echo $URL ?></a></li>
        </ul>
    </li>
    <li><code>app2.URL</code> / <code>php8.URL</code> using the <code>php8/.platform.app.yaml</code>
        <ul>
            <li><a href="//app2.<?php echo $URL ?>">app2.<?php echo $URL ?></a></li>
            <li><a href="//php8.<?php echo $URL ?>">php8.<?php echo $URL ?></a></li>
        </ul>
    </li>
</ul>