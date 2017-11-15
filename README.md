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


Cron
----

```
bin/console app:feeds:read
```
