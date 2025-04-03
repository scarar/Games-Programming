<?php
/**
 * PHP Syntax Checker
 * 
 * This script checks all PHP files in the specified directory for syntax errors.
 */

// Configuration
$directory = __DIR__; // Current directory
$recursive = true;    // Check subdirectories
$extensions = ['php']; // File extensions to check

// Output formatting
echo "=======================================================\n";
echo "PHP Syntax Checker\n";
echo "=======================================================\n";
echo "Checking directory: " . $directory . "\n";
echo "PHP Version: " . phpversion() . "\n";
echo "=======================================================\n\n";

// Function to check a file for syntax errors
function checkSyntax($file) {
    $output = [];
    $return_var = 0;
    
    exec("php -l " . escapeshellarg($file), $output, $return_var);
    
    if ($return_var !== 0) {
        echo "❌ ERROR: " . implode("\n", $output) . "\n";
        return false;
    } else {
        echo "✅ " . basename($file) . " - No syntax errors detected\n";
        return true;
    }
}

// Function to scan directory for PHP files
function scanDirectory($dir, $extensions, $recursive = false) {
    $files = [];
    $dir_iterator = new RecursiveDirectoryIterator($dir);
    
    if ($recursive) {
        $iterator = new RecursiveIteratorIterator($dir_iterator);
    } else {
        $iterator = new IteratorIterator($dir_iterator);
    }
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $extension = strtolower(pathinfo($file->getPathname(), PATHINFO_EXTENSION));
            if (in_array($extension, $extensions)) {
                $files[] = $file->getPathname();
            }
        }
    }
    
    return $files;
}

// Get all PHP files
$files = scanDirectory($directory, $extensions, $recursive);
$total_files = count($files);
$errors = 0;

echo "Found $total_files PHP files to check.\n\n";

// Check each file
foreach ($files as $file) {
    if (!checkSyntax($file)) {
        $errors++;
    }
}

// Summary
echo "\n=======================================================\n";
echo "SUMMARY\n";
echo "=======================================================\n";
echo "Total files checked: $total_files\n";
echo "Files with syntax errors: $errors\n";
echo "Status: " . ($errors === 0 ? "✅ All files passed" : "❌ Some files have errors") . "\n";
echo "=======================================================\n";

// Return error code if there were syntax errors
exit($errors > 0 ? 1 : 0);