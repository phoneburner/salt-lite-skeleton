# Salt-Lite Framework Skeleton Application

### Add Framework Dependency and Update Application
`docker compose run --rm -it web composer -d lib/salt-lite-framework --no-install --no-update require nesbot/carbon && docker compose exec web composer update`

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
- [ ] API Handler 
- [ ] PSR-14 Event Dispatcher
- [ ] Local/Remote Filesystem Storage via Flysystem
- [ ] Session Management
- [ ] ACL/Permissions
- [ ] API Authentication (OAuth2, JWT, etc.)
- [ ] API Rate Limiting
- [ ] Mailer / Notifications
- [ ] Queueing/Async Job/Listener Processing
- [ ] Cron/Scheduling

Domain Specific
- [ ] Time Domain Code
- [ ] Phone Number Domain Code


Maybe?
- [ ] "Debugbar" Tooling
- [ ] Validation
- [ ] API Documentation Tooling
