<?php
require __DIR__ . '/kirby/bootstrap.php';

$url = 'https://example.github.io/my-repo';
$options = [
    'roots' => [
        'index' => __DIR__,
    ],
    'urls' => [
        'index' => $url
    ]
];

$kirby = new Kirby($options);
echo "Site URL: " . $kirby->site()->url() . "\n";
echo "CSS Tag: " . css('assets/css/styles.css') . "\n";
echo "JS Tag: " . js('assets/js/script.js') . "\n";
