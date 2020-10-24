DDD ES CQRS Boilerplate
=======================

[![Build Status](https://travis-ci.org/Invis1bleReborn/ddd-es-cqrs-boilerplate.svg?branch=develop)](https://travis-ci.org/Invis1bleReborn/ddd-es-cqrs-boilerplate)
[![Test Coverage](https://api.codeclimate.com/v1/badges/85236f7a90b2ebf7ef50/test_coverage)](https://codeclimate.com/github/Invis1bleReborn/ddd-es-cqrs-boilerplate/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/85236f7a90b2ebf7ef50/maintainability)](https://codeclimate.com/github/Invis1bleReborn/ddd-es-cqrs-boilerplate/maintainability)

[![API docs](https://image.thum.io/get/maxAge/12/width/700/https://ddd-es-cqrs-boilerplate.herokuapp.com/api/docs)](https://ddd-es-cqrs-boilerplate.herokuapp.com/api/docs)

Installation
------------

```bash
mkdir config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
composer install --no-dev --optimize-autoloader
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate
bin/console doctrine:schema:create
```

Migration
------------

```bash
composer install --no-dev --optimize-autoloader
bin/console doctrine:migrations:migrate
bin/console doctrine:schema:update --force
```

Setup permissions
--------------------

Follow instructions on
[Symfony Guide](https://symfony.com/doc/current/setup/file_permissions.html).
Write permissions must be set on the following directories:

1. `var/log`
