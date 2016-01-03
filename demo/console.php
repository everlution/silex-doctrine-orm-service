<?php

require_once __DIR__ . '/../vendor/autoload.php';

/** @var \Silex\Application $app */
$app = require_once 'bootstrap.php';

// if single db
$app['doctrine.orm.entity_manager.console']->run();

// if multiple databases
#$app['doctrine.orm.entity_manager.{connection-name}.console']->run();

