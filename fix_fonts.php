<?php

$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

$count = 0;
foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    
    // Replace small rem units (e.g. text-[0.65rem])
    $content = preg_replace('/text-\[0\.[0-9]+rem\]/', 'text-sm', $content);
    
    // Replace tiny px units (e.g. text-[10px], text-[12px]) 
    $content = preg_replace('/text-\[1[0-5]px\]/', 'text-sm', $content);
    $content = preg_replace('/text-\[[0-9]px\]/', 'text-sm', $content);
    
    // Replace large rem units (e.g. text-[4rem])
    $content = preg_replace('/text-\[[3-9]+(\.[0-9]+)?rem\]/', 'text-5xl', $content);
    
    // Replace large px units (e.g. text-[50px])
    $content = preg_replace('/text-\[[4-9][7-9]px\]/', 'text-5xl', $content);
    $content = preg_replace('/text-\[[5-9][0-9]px\]/', 'text-5xl', $content);

    // Replace the specific text-xs since we redefined it to 16px, it's fine.
    // Replace small tracking if requested? No, only font-size and family.
    
    file_put_contents($path, $content);
    $count++;
}

echo "Processed {$count} blade files successfully.\n";
