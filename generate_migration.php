<?php
//generate databse schema to be migrated.
//This file will provide a template or schema for table creation.

// Ensure script is run from CLI
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.");
}

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Str;

// Get migration name from CLI
$migrationName = $argv[1] ?? null;

if (!$migrationName) {
    die("Usage: php generate_migration.php MigrationName\n");
}

// Format migration file name
$className = Str::studly($migrationName); // Converts "create_users_table" to "CreateUsersTable"
$timestamp = date('Ymd_His');
$fileName = "{$timestamp}_{$migrationName}.php";

// Define migration file path
$migrationPath = __DIR__ . "/src/Migrations/{$fileName}";

// Migration template
$template = <<<PHP
<?php

namespace Src\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class $className extends BaseMigration
{
    public function up()
    {
        Capsule::schema()->create('your_table_name', function (Blueprint \$table) {
            \$table->id();
            // Add your schema fields here
        });

        echo "$className migration applied.\n";
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('your_table_name');
        echo "$className migration rolled back.\n";
    }
}
PHP;

// Create migration file
if (file_put_contents($migrationPath, $template)) {
    echo "Migration created: $migrationPath\n";
} else {
    echo "Error creating migration file.\n";
}
