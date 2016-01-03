# silex-doctrine-orm-service

A Doctrine ORM service provider for Silex.

There are already a bunch of other providers out there but it looks like they are overcomplicating it, this is why I developed this.

## How to use it

This provider relies on the `\Silex\Provider\DoctrineServiceProvider`.

```php
$app = new \Silex\Application();

$app->register(new \Silex\Provider\DoctrineServiceProvider(), array(
    // single db
    'db.options' => [
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/db.sqlite',
    ],
    // multi db
    #'dbs.options' => [
    #    'default' => [
    #        'driver' => 'pdo_sqlite',
    #        'path' => __DIR__.'/db.sqlite',
    #    ],
    #],
));

$app->register(new \Everlution\SilexDoctrineOrmService\SilexDoctrineOrmServiceProvider([
    // single db
    'type' => 'annotation',
    'paths' => [
        __DIR__ . '/Entity'
    ],
    // multi db
    #'default' => [
    #    'type' => 'annotation',
    #    'paths' => [..., ...]
    #],
]));
```

This provider creates also a console for every connection.

```php
// single db
$app['doctrine.orm.entity_manager.console']->run();

// multi db
$app['doctrine.orm.entity_manager.{connection-name}.console']->run();
```

## Samples

You can find samples in the `demo` folder.

## TODO

At the moment the provider is using the `ArrayCache` which is not ideal for production. The plan is to add the `RedisCache`.
