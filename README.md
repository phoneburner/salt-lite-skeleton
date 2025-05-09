# Salt-Lite Project Skeleton

> Feels like home, just without the salty tears of frustration

The Salt-Lite Framework is a "batteries-included" PHP framework, modeled on other
modern frameworks like Symfony and Laravel, derived from the original Salt project.
Ideally, it adapts the best core features of Salt without dragging along unnecessary 
complexity, technical debt and design decisions we regret. The goal is to provide 
users with a robust framework with minimum cognitive overhead from the original 
Salt framework, avoiding the pitfalls of bringing in a full-fledged third-party 
framework and trying to adapt that to our needs. 

```shell
docker run --rm -it -v $PWD:/app -u -w /app --env-file composer-auth.env composer/composer create-project \
  --repository='{"type": "github",  "url": "https://github.com/phoneburner/salt-lite-skeleton"}' 
  --stability=dev \
  --ignore-platform-reqs \
  --no-install \
  --ask \
  phoneburner/salt-lite-skeleton
```

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

### Backwards Capability Guarantees
Classes and interfaces with the `#[PhoneBurner\SaltLite\Framework\Util\Attribute\Contract]` attribute
are considered part of the public API of the framework and should not be changed without
a major version bump. These "contracts" can be freely used in application code.

Conversely, classes and interfaces with the `#[PhoneBurner\SaltLite\Framework\Util\Attribute\Internal]`
attribute are very tightly coupled to third-party vendor and/or framework logic,
and should not be used in application code.

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

## Component Documentation

Documentation
[Application Runtime & Bootstrapping](vendor/phoneburner/salt-lite-framework/src/App/README.md)
[Cache](vendor/phoneburner/salt-lite-framework/src/Cache/README.md)
[Application Configuration](vendor/phoneburner/salt-lite-framework/src/Configuration/README.md)
[Console Commands](vendor/phoneburner/salt-lite-framework/src/Console/README.md)
[Database](vendor/phoneburner/salt-lite-framework/src/Database/README.md)
[Service Container/Dependency Injection](vendor/phoneburner/salt-lite-framework/src/Container/README.md)
[Event Dispatcher](vendor/phoneburner/salt-lite-framework/src/EventDispatcher/README.md)
[HTTP Request/Response Handling](vendor/phoneburner/salt-lite-framework/src/Http/README.md)
[Logging](vendor/phoneburner/salt-lite-framework/src/Logging/README.md)
[Routing](vendor/phoneburner/salt-lite-framework/src/Routing/README.md)
[Queue](vendor/phoneburner/salt-lite-framework/src/Queue/README.md)
[Task Scheduling](vendor/phoneburner/salt-lite-framework/src/Scheduler/README.md)
[Utilities](vendor/phoneburner/salt-lite-framework/src/Util/README.md)

### Console Commands

Run the following to see a list of available console commands:
```shell
docker compose run --rm -it web php salt list
```

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
