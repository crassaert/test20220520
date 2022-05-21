#!/bin/bash

# Startup containers
docker-compose up -d

# Install dependencies
docker-compose exec php composer install

## Create test database
docker-compose exec php bin/console --env=test doctrine:database:create

# Create app database
docker-compose exec php bin/console doctrine:database:create

# Migrate test database
docker-compose exec php bin/console --env=test doctrine:migrations:migrate --no-interaction

# Migrate app database
docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction

# Load test fixtures
docker-compose exec php bin/console --env=test doctrine:fixtures:load --append

# Load app fixtures
docker-compose exec php bin/console doctrine:fixtures:load --append

# Execute tests
docker-compose exec php php bin/phpunit tests/ShopTest.php
