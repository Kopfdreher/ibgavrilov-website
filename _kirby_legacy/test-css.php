<?php
require __DIR__ . '/kirby/bootstrap.php';
$kirby = new Kirby(['roots' => ['index' => __DIR__]]);
echo css('assets/css/styles.css');
