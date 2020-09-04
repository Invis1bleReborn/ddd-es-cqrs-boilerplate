DDD ES CQRS Boilerplate
=======================


## Installation

```bash
mkdir config/jwt
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
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
