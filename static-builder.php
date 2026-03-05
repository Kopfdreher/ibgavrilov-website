<?php

require __DIR__ . '/kirby/bootstrap.php';

// Check for a custom URL environment variable (useful for GitHub Pages subfolders)
$options = [
    'roots' => [
        'index' => __DIR__,
        'base' => __DIR__,
        'content' => __DIR__ . '/content',
        'site' => __DIR__ . '/site',
        'storage' => __DIR__ . '/storage',
        'kirby' => __DIR__ . '/kirby'
    ]
];

if ($url = getenv('KIRBY_URL')) {
    $options['urls'] = [
        'index' => $url,
        'base' => $url
    ];
}

$kirby = new Kirby($options);

$site = $kirby->site();
$outputDir = __DIR__ . '/static';

// Helper to recursively copy directories
function recurseCopy($src, $dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurseCopy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
}

// 1. Clean/Create static directory
if (is_dir($outputDir)) {
    // A simple recursive delete would be safer here in a real script, 
    // but for now let's just assume we overwrite or the user cleans it.
    // simpler: just ensure it exists
} else {
    mkdir($outputDir);
}

// 2. Render and save all pages
foreach ($site->index() as $page) {
    // Render the page
    $html = $page->render();
    
    // Determine output file path
    if ($page->isHomePage()) {
        $path = $outputDir . '/index.html';
    } else {
        // Create directory for the page (e.g., static/about)
        $pageDir = $outputDir . '/' . $page->id();
        if (!is_dir($pageDir)) {
            mkdir($pageDir, 0755, true);
        }
        $path = $pageDir . '/index.html';
    }
    
    // Explicitly publish files in the page's content folder to the media folder
    // This forces Kirby to generate the media URLs and copy the files
    foreach ($page->files() as $file) {
        $file->publish();
    }

    echo "Generating: " . $page->id() . " -> " . $path . "\n";
    file_put_contents($path, $html);
}

// 3. Copy Assets and Media
echo "Copying assets...\n";
if (is_dir(__DIR__ . '/assets')) {
    recurseCopy(__DIR__ . '/assets', $outputDir . '/assets');
}

echo "Copying media...\n";
if (is_dir(__DIR__ . '/media')) {
    recurseCopy(__DIR__ . '/media', $outputDir . '/media');
}

echo "Done! Your static site is in the 'static' folder.\n";
