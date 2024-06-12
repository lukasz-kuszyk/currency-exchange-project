## Requirements
* docker

## How to start
* on Unix(-like) or WSL
```bash
# build images & start containers
bin/start

# install composer deps
bin/composer install

# execute sample exchanges
bin/execute
```

### Tests
#### Static Analysis Tests
```bash
# PHP CS fixer for Coding Standards
bin/cli vendor/bin/php-cs-fixer fix --dry-run --diff

# PHPStan for Bugs finding
bin/cli vendor/bin/phpstan
```
#### Unit Tests
```bash
# PHPUnit
bin/cli vendor/bin/phpunit tests
```
