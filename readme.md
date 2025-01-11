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
   git clone https://github.com/yourusername/api-manager-suite.git
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
-- POST /api/auth/login - User login
-- POST /api/auth/register - User registration

## Food Items
-- GET /api/food - Fetch all food items
-- POST /api/food - Add a new food item
-- PUT /api/food/{id} - Update an existing food item
-- DELETE /api/food/{id} - Delete a food item

## Users
-- GET /api/users - Fetch all users
-- GET /api/users/{id} - Fetch a specific user
-- POST /api/users - Create a new user
-- PUT /api/users/{id} - Update a user
-- DELETE /api/users/{id} - Delete a user


## Future Improvements
-- Add API versioning for backward compatibility.
-- Implement caching for improved performance.
-- Create a frontend client using React or Vue.js.
-- Integrate CI/CD for automated testing and deployment.
-- Add support for Docker for containerized deployments.

## Contributing
Contributions are welcome! Please follow these steps to contribute:

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

