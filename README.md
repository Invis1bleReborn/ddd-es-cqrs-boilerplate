DDD ES CQRS Boilerplate
=======================

[![Build Status](https://travis-ci.org/Invis1bleReborn/ddd-es-cqrs-boilerplate.svg?branch=develop)](https://travis-ci.org/Invis1bleReborn/ddd-es-cqrs-boilerplate)


## Installation

```bash
mkdir config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
composer install --no-dev --optimize-autoloader
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate
```


## Migration

```bash
composer install --no-dev --optimize-autoloader
bin/console doctrine:migrations:migrate
```


## Setup permissions

Follow instructions on [Symfony Guide](https://symfony.com/doc/current/setup/file_permissions.html).  
Write permissions must be set on the following directories:
1. `var/log`
