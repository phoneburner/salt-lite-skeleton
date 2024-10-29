# Salt-Lite Framework Skeleton Application

> Feels like home, just without the salty tears of frustration

The Salt-Lite Framework is a "batteries-included" PHP framework, modeled on other
modern frameworks like Symfony and Laravel, derived from the original Salt project.
Ideally, it adapts the best core features of Salt without dragging along unnecessary 
complexity, technical debt and design decisions we regret. The goal is to provide 
users with a robust framework with minimum cognitive overhead from the original 
Salt framework, avoiding the pitfalls of bringing in a full-fledged third-party 
framework and trying to adapt that to our needs. 

## Installation
In order to run this application you need to have Docker installed and on your 
machine, as well as a new GitHub Personal Access Token with repo scope. The build
process will prompt you to enter this token, or it can be added manually by coping
the .env.example file to .env and adding the token to the GITHUB_TOKEN variable.

To build the application containers and install the dependencies, run the following:
```shell
make
```
You can confirm a successful installation by running the following:
```shell
make test
```

If you ever need to start fresh, you can run the following:
```shell
make clean
```

To start the application, run the following:
```shell
make up
```

To stop the application, run the following:
```shell
make down
```

By default, the application server will be available at http://localhost:8888,
navigating to this URL should display the friendly 404 response page.


### Notable Differences from Salt
- The time zone configuration for PHP and the database is set to UTC by default.
- Configuration is defined by the environment, and not by the path value of a request.
- Overriding configuration values is done via environment variables, not by adding local configuration files.
- Database migrations are handled by the [Doctrine Migrations](https://www.doctrine-project.org/projects/migrations.html) library, as opposed to Phinx.

- PHPUnit 11 is used for testing, with ParaTest for parallel execution, this is 
a significant upgrade from the previous version. Notably unit tests are defined
with attributes and data providers must be defined as static functions.


### Configuration
By convention, configuration files are located in the `config` directory. Each
file should return an array of configuration values, with the top-level key identical
to the file name. E.g., a configuration file called "logging.php" should look like:

```php
<?php
    declare(strict_types=1);
    
    return [
        'logging' => [
            'log_level' => 'debug',
            'log_file' => '/var/log/app.log',
        ],
    ]; 
```

**Important: the values of the configuration arrays must be arrays or scalar values.**

The resolved configuration array is cached for performance as a static PHP array,
with realized environmental variables from the point in time it was cached. Using
objects or expressions as values will be problematic.

### Routing
Application routes should be defined in classes that implement the 
`\PhoneBurner\SaltLiteFramework\Routing\RouteProvider` interface. That interface
defines an invokable class that returns an array of PhoneBurner\SaltLiteFramework\Routing\Definition\Definition
instances. The DQL for defining routes is as follows is the same as the full Salt 
codebase. The `RouteProvider` should then be registered with the application
in the `config/routes.php` file.

the implementation of the `getRoutes()` method, which should return an array of routes. Each route should be an associative array with the following keys:

### Console Commands

```shell
docker compose run --rm -it web php salt
```

### Filesystem/Storage

Resolve `League\Flysystem\FilesystemOperator` from the container.

### Testing

Running `make tests` will run the full test suite via ParaTest, which will execute tests in parallel for faster execution.

Running `make tests-coverage` with generate a code coverage report in HTML format,
in the "html/phpunit" directory.  The report can be viewed in a web browser at:
http://localhost:8000/html/phpunit

## Framework Project Development Notes

### How to Add Dependencies to the Framework Update the Skeleton Application
```shell
docker compose run --rm -it web composer -d lib/salt-lite-framework --no-install --no-update require nesbot/carbon \
  && docker compose run --rm -it web composer update
```

## Functionality To-Do Checklist
- [x] Dockerized PHP 8.3 Runtime with Redis and MySQL Database Services
- [x] PHPUnit/ParaTest Testing Frameworks with HTML Code Coverage
- [x] PHPStan Static Analysis Tool
- [x] PHP_CodeSniffer Coding Standard Tooling
- [x] Rector Refactor Tooling
- [x] Configuration with Caching
- [x] "Whoops" Error Handling
- [x] PSR-11 Dependency Injection Container with Service Providers
- [x] Logging with Monolog to PHP Error Handler & Daily File
- [x] Symfony Console Commands
- [x] Interactive PsySH Shell Execution in Application Runtime
- [x] PSR-7/PSR-15 Request/Response Handling
- [x] Standard and Exceptional Responses (via HTTP Tortilla)
- [x] Routing
- [x] Doctrine DBAL Database Connection
- [x] Doctrine ORM Configuration and Entity Mapping
- [x] Doctrine Migrations
- [x] Redis Connection Configuration
- [x] Remote Cache/Append-Only Cache/In-Memory Cache Support
- [x] Crypto (Paseto, Int to UUID, etc.)
- [x] Open Telemetry
- [x] Local/Remote Filesystem Storage via Flysystem
- [ ] Event Sourcing / EventSauce
- [ ] Front-End Scaffolding
- [ ] PSR-14 Event Dispatcher
- [ ] Queueing/Async Job/Listener Processing
- [ ] Task Scheduling (Cron Replacement)
- [ ] Behat BDD Testing Framework (or Codeception)
- [ ] API Handler
- [ ] Session Management
- [ ] ACL/Permissions
- [ ] API Authentication (OAuth2, JWT, etc.)
- [ ] API Rate Limiting
- [ ] Mailer / Notifications
- [ ] Document creating and running migrations

Domain Specific
- [ ] Time Domain Code
- [ ] Phone Number Domain Code

Maybe?
- [ ] "Debugbar" Tooling
- [ ] Validation
- [ ] API Documentation Tooling
- [ ] Enforce Invokable Jobs with PHPStan Rule