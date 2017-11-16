Podcast
=======

```
composer install
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
