# API Manager Suite
A lightweight and extensible PHP-based application for managing users, items, and food-related data. This project is built with a focus on modularity, scalability, and best practices, making it ideal for API-driven web applications.
---

## Features
- **Modular Architecture**: Organized namespaces for Config, DAL, Entities, Exceptions, Services, and Validation.
- **Robust Validation**: Built-in input validation to ensure data integrity.
- **Secure Authentication**: Token-based authentication for managing user sessions.
- **Extensibility**: Easy to extend with custom routes, services, and validations.
- **Error Handling**: Custom exceptions for graceful error reporting.
- **Test-Driven**: Integration with PHPUnit for unit and integration testing.

---

## Folder Structure
```plaintext
project-root/
├── .env
├── .env.sample
├── .gitattributes
├── .gitignore
├── .htaccess
├── LICENSE
├── README.md
├── composer.json
├── composer.lock
├── index.php
├── phpunit.xml
├── src/
│   ├── Config/
│   │   ├── database.inc.php
│   │   ├── Environment.php
│   ├── DAL/
│   │   ├── FoodDal.php
│   │   ├── TokenKeyDal.php
│   │   ├── UserDal.php
│   ├── Entity/
│   │   ├── Entitiable.php
│   │   ├── FoodItem.php
│   │   ├── Item.php
│   │   ├── User.php
│   ├── Exceptions/
│   │   ├── EmailExistException.php
│   │   ├── InvalidCredentialException.php
│   │   ├── InvalidValidationException.php
│   │   ├── NotFoundException.php
│   │   ├── TokenSessionNotSetException.php
│   ├── Helpers/
│   │   ├── header.inc.php
│   │   ├── misc.inc.php
│   ├── Routes/
│   │   ├── CustomHttp.php
│   │   ├── ItemActionRoute.php
│   │   ├── route-not-found.php
│   │   ├── routes.php
│   │   ├── UserActionRoute.php
│   ├── Service/
│   │   ├── FoodItem.php
│   │   ├── SecretKey.php
│   │   ├── User.php
│   ├── Validation/
│   │   ├── AllowCors.php
│   ├── tests/
│       ├── Entity/
│       │   ├── ItemTest.php
│       │   ├── UserTest.php
├── vendor/
```


---

## Requirements
- **PHP 7.4 or higher**
- **Composer** for dependency management
- **MySQL** or any compatible database
- **Apache/Nginx** web server

---

## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/samuelldmj/Api_Manager_Suite.git
   cd api-manager-suite

```bash
composer install
```

```bash
cp .env.sample .env
```

Usage
## API Endpoints

## Authentication
```plaintext
-- POST /api/auth/login - User login
-- POST /api/auth/register - User registration
```

## Food Items
```plaintext
-- GET /api/food - Fetch all food items
-- POST /api/food - Add a new food item
-- PUT /api/food/{id} - Update an existing food item
-- DELETE /api/food/{id} - Delete a food item
```

## Users
```plaintext
-- GET /api/users - Fetch all users
-- GET /api/users/{id} - Fetch a specific user
-- POST /api/users - Create a new user
-- PUT /api/users/{id} - Update a user
-- DELETE /api/users/{id} - Delete a user
```

## Future Improvements
```plaintext
Environment.php
-- Add support for environment validation (e.g., ensure required environment variables are set at startup).
-- Document default values for critical environment variables in .env.sample.

database.inc.php
-- Add automated migrations and seeding functionalities.
-- Add database connection health-check functionality.

DAL (Data Access Layer)
FoodDal.php, TokenKeyDal.php, UserDal.php
--- Add CRUD (Create, Read, Update, Delete) functions for all DALs.
--- Implement query logging and execution time measurement for debugging.
--- Add relationship handling for entities (e.g., users related to food items).
--- Implement soft-delete functionality for critical entities.

Entity
Entitiable.php
-- Add support for entity hydration from arrays or JSON.
-- Add property validation within entities.

FoodItem.php, Item.php, User.php

--Add support for serialization (to JSON or XML) for API responses.
-- Include attribute annotations for documentation and validation.
-- Define relationships between entities (e.g., User owns multiple FoodItems).

Exceptions
-- Improve exception handling by creating more specific exception types.
-- Add logging to capture and track exceptions in a log file or monitoring system.
-- Define user-friendly error messages for all exceptions, focusing on API responses.

Helpers
header.inc.php
-- Add support for dynamically setting HTTP headers based on the environment.
-- Implement utilities for setting Content-Type headers for different response formats.

misc.inc.php
-- Add reusable utilities for string manipulation, array helpers, and date/time formatting.

Routes
routes.php
-- Add middleware support for authentication, logging, and rate-limiting.
-- Implement API versioning (e.g., v1/ and v2/ routes).
-- Add dynamic route generation based on controllers.
-- Include CORS preflight checks for OPTIONS requests.

Service
FoodItem.php, SecretKey.php, User.php
-- Implement dependency injection for services.
-- Add caching mechanisms for frequently accessed data.
-- Ensure all services support asynchronous processing (if applicable).
-- Write detailed unit tests for all service methods.

Validation
-- Extend validation classes to support dynamic rules (e.g., read rules from a JSON schema).
-- Add custom validation error messages and translations.
-- Implement reusable validation for paginated API requests (limit, offset).

Tests
Write test cases for:
-- **Unit Tests:** For every class, including DALs, Services, and Validation.
-- **Integration Tests:** For routes and controllers to ensure request-response handling works as expected.
-- **Database Tests:** To verify migrations, seeds, and data integrity.
-- Implement coverage reporting to ensure the project meets a high percentage of test coverage.
-- Add automated testing workflows (GitHub Actions or GitLab CI/CD).
```


## General Project Enhancements
Authentication & Authorization
```plaintext
-- Implement JWT (JSON Web Token)-based authentication for API routes.
-- Add user roles (e.g., admin, user) and implement role-based access control.
```

## Logging
```plaintext
-- Use a logging library (e.g., Monolog) for application-wide logging.
-- Log critical events, warnings, errors, and user actions.
```

## Error Handling
```plaintext
-- Enhance the Whoops handler to support a custom error page for production.
-- Add support for graceful degradation (fallback mechanisms in case of failure).
```

## API Documentation
```plaintext
-- Use tools like Swagger/OpenAPI to generate API documentation.
-- Include example requests and responses for all routes.
```

## Deployment
```plaintext
-- Create Dockerfiles for containerized deployment.
-- Add docker-compose.yml for local development using containers.
-- Write deployment scripts for hosting on platforms like AWS, DigitalOcean, or Heroku.
```

## Frontend Integration
```plaintext
-- Add sample frontend views or a simple client built with React or Vue.js to showcase API usage.
-- Include authentication flow, item management, and profile editing.
```
## Monitoring & Analytics
```plaintext
-- Add basic monitoring using tools like Sentry or New Relic for error tracking.
-- Implement logging for API request and response times for analytics.
```

## CI/CD Pipeline
```plaintext
-- Integrate continuous integration and deployment pipelines for automated testing and deployment.
```

## Documentation
```plaintext
-- Write a comprehensive README file with setup instructions, features, and examples.
-- Include a CONTRIBUTING.md file for guidelines on contributing to the project.
-- Add code comments for all public methods.
```

## Security
```plaintext
-- Use .env to store sensitive credentials and ensure .gitignore includes .env.
-- Implement rate limiting for public APIs.
-- Add CSRF (Cross-Site Request Forgery) protection for state-changing endpoints.
```

## Contributing
```plaintext
Contributions are welcome! Please follow these steps to contribute:
```

## Fork the repository.
```bash
git checkout -b feature-name
```
```bash
git commit -m "Add your message here"
```

```bash
git push origin feature-name
```

## Create a pull request.

