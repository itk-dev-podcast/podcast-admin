Podcast
=======

```
composer install
(cd web/assets && npm install)
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate --no-interaction
```

Optionally, load fixtures:

```
bin/console doctrine:fixtures:load --no-interaction
```

```
bin/console fos:user:create admin@example.com admin@example.com
bin/console fos:user:promote admin@example.com ROLE_ADMIN
```

Cron
----

```
bin/console app:feeds:read
```


Tests
-----

```
SYMFONY_ENV=test bin/console doctrine:database:create
SYMFONY_ENV=test bin/console doctrine:schema:create
SYMFONY_ENV=test bin/console doctrine:fixtures:load --no-interaction
SYMFONY_ENV=test ./vendor/bin/behat
```
