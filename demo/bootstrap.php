<?php

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

return $app;
