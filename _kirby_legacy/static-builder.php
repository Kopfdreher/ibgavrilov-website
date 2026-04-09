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

// Hack to force thumb generation during render
// The previous attempts failed because Kirby components don't always behave like simple closures we can access directly via ->component()
// unless they were set as such. The default components are often methods on the App class or other classes.
// Let's use a simpler approach: hook into 'file.create:after' or just don't use a component hook if possible?
// No, we need to intercept the url generation for the crop.

// NEW STRATEGY: Don't rely on 'file::version' hook which is complex.
// Instead, iterate all files AFTER render, find the .jobs files, and manually process them.







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
    foreach ($page->files() as $file) {
        $file->publish();
    }
    
    // Also publish any files from children pages that might be referenced (like project thumbnails)
    if ($page->hasChildren()) {
        foreach ($page->children() as $child) {
            foreach ($child->files() as $childFile) {
                $childFile->publish();
            }
        }
    }

    echo "Generating: " . $page->id() . " -> " . $path . "\n";
    file_put_contents($path, $html);
}
// 2.5 Process any deferred image jobs (this fixes the missing cropped images)
echo "Processing deferred image jobs...\n";

// Use a simple iterator for the media folder
try {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__ . '/media')
    );

    foreach ($iterator as $file) {
        // Skip dots
        if ($file->getFilename() === '.' || $file->getFilename() === '..') continue;
        
        // We are looking for JSON job files inside .jobs folders
        if ($file->isFile() && $file->getExtension() === 'json' && strpos($file->getPathname(), '/.jobs/') !== false) {
            
            $jobPath = $file->getPathname();
            $jobsDir = dirname($jobPath); // .../hash/.jobs
            $hashDir = dirname($jobsDir); // .../hash

            $jobData = json_decode(file_get_contents($jobPath), true);
            if (!$jobData || !isset($jobData['filename'])) continue;

            // The original source file should be in the hash folder
            $sourcePath = $hashDir . '/' . $jobData['filename'];
            
            // The destination thumb file name is the job file name minus .json
            $thumbName = basename($jobPath, '.json');
            $destPath = $hashDir . '/' . $thumbName;
            
            // Only process if source exists and destination doesn't
            if (file_exists($sourcePath) && !file_exists($destPath)) {
                echo "  Processing thumb: " . $thumbName . "\n";
                
                try {
                    // Use Kirby's native thumb component to generate the file
                    // The signature is ($kirby, $src, $dst, $options)
                    $kirby->nativeComponent('thumb')($kirby, $sourcePath, $destPath, $jobData);
                } catch (Exception $e) {
                    echo "  Error processing thumb: " . $e->getMessage() . "\n";
                }
            }
        }
    }
} catch (Exception $e) {

    echo "  Could not iterate media folder: " . $e->getMessage() . "\n";
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
