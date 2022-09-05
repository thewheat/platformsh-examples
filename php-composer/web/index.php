<?php

use App\Messages;

require __DIR__.'/../vendor/autoload.php';

$messages = new Messages();

printf("<h3>Hello from PHP %s</h3>\n", phpversion());
printf("<h1>%s</h1>\n", $messages->title());
printf("<p>%s</p>\n", $messages->message());

?>