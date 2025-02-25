<?php
// this file will push the database schema to the database 

use Src\Migrations\BaseMigration;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Config/database.inc.php';

// Get all migration files in the directory
$migrationFiles = glob(__DIR__ . "/src/Migrations/*.php");

$migrations = [];

foreach ($migrationFiles as $file) {
    require_once $file;

    // Extract class name from filename
    $className = basename($file, '.php');
    $classNamespace = "Src\\Migrations\\$className";

    if (class_exists($classNamespace) && is_subclass_of($classNamespace, 'Src\Migrations\BaseMigration')) {
        $migrations[] = new $classNamespace();
    }
}

// Run migrations based on CLI command
$command = $argv[1] ?? null;

if ($command === 'up') {
    foreach ($migrations as $migration) {
        if ($migration instanceof BaseMigration) {
            $migration->up();
        }
    }
} elseif ($command === 'down') {
    foreach (array_reverse($migrations) as $migration) { // Reverse to avoid foreign key issues
        if ($migration instanceof BaseMigration) {
            $migration->down();
        }
    }
} else {
    echo "Usage: php migrate.php [up|down]\n";
}