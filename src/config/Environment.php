<?php
namespace Src\Config;

use Dotenv\Dotenv;

enum Environment: string
{
    case DEVELOPMENT = 'development';
    case PRODUCTION = 'production';

    /**
     * Get the current environment from .env
     */
    public static function current(): self
    {
        return match ($_ENV['ENVIRONMENT'] ?? 'development') {
            'production' => self::PRODUCTION,
            'development' => self::DEVELOPMENT,
            default => throw new \InvalidArgumentException("Invalid ENVIRONMENT value in .env"),
        };
    }

    /**
     * Get the environment name
     */
    public function environmentName(): string
    {
        return $this->value;
    }
}

// Load environment variables
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

// Ensure required variables are set
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'ENVIRONMENT']);

// Validate environment
try {
    $currentEnv = Environment::current();
    //echo "Environment: " . $currentEnv->environmentName();
} catch (\Dotenv\Exception\ValidationException $e) {
    die("Missing required environment variable: " . $e->getMessage());
} catch (\InvalidArgumentException $e) {
    die("Environment Error: " . $e->getMessage());
}

