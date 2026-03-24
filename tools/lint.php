<?php
$root = __DIR__ . '/../';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$errors = [];
foreach ($it as $file) {
    if (!$file->isFile()) continue;
    if (strtolower($file->getExtension()) !== 'php') continue;
    $path = $file->getPathname();
    // Skip vendor and storage views compiled (optional)
    if (strpos($path, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR) !== false) continue;
    // Run lint
    $cmd = 'php -l "' . $path . '" 2>&1';
    exec($cmd, $output, $ret);
    if ($ret !== 0) {
        $errors[$path] = $output;
    }
}
if (empty($errors)) {
    echo "No syntax errors found.\n";
    exit(0);
}
foreach ($errors as $file => $out) {
    echo "---- SYNTAX ERROR: $file ----\n";
    foreach ($out as $line) echo $line . "\n";
}
exit(1);
