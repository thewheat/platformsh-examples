<h1>Varnish Service Example</h1>
<li><a href="https://docs.platform.sh/add-services/varnish.html">https://docs.platform.sh/add-services/varnish.html</a></li>

Date and time is <?php  echo date('Y-m-d H:i:s'); ?>

<?php
$URL = preg_replace('/^(un)?cached\./i', '', $_SERVER['HTTP_HOST']);
?>

<ul>
    <li>Varnish cached URLs
        <ul>
            <li><a href="//<?php echo $URL ?>"><?php echo $URL ?></a></li>
            <li><a href="//cached.<?php echo $URL ?>">cached.<?php echo $URL ?></a></li>
        </ul>
    </li>
    <li>Uncached URLs going straight to backend
        <ul>
            <li><a href="//uncached.<?php echo $URL ?>">uncached.<?php echo $URL ?></a></li>
        </ul>
    </li>
</ul>