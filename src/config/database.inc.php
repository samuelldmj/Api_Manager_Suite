<?php

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Src\Config\Environment;

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$database = new Capsule;

$database->addConnection([
    'driver'    => 'mysql',
    'host'      => $_ENV['DB_HOST'],
    'database'  => $_ENV['DB_NAME'],
    'username'  => $_ENV['DB_USER'],
    'password'  => $_ENV['DB_PASS'],
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally
$database->setAsGlobal();

// Boot Eloquent
$database->bootEloquent();

// Check if the environment is production, then disable migrations
$currentEnvironment = Environment::tryFrom($_ENV['ENVIRONMENT']);

if ($currentEnvironment?->environmentName() === Environment::PRODUCTION->value) {
    Capsule::connection()->disableQueryLog(); // Disable query logging for production
}
