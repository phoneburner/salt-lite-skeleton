

### Add Framework Dependency and Update Application
`docker compose run --rm -it web composer -d lib/salt-lite-framework --no-install --no-update require nesbot/carbon && docker compose exec web composer update`
