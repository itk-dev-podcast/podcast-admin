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

Set up test database:
```
SYMFONY_ENV=test bin/console doctrine:database:create
```

Reset test database and load fixtures:

```
SYMFONY_ENV=test bin/console doctrine:schema:drop --force
SYMFONY_ENV=test bin/console doctrine:schema:create
SYMFONY_ENV=test bin/console doctrine:fixtures:load --no-interaction
```

Run features:

```
SYMFONY_ENV=test ./vendor/bin/behat
```
