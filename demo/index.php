<?php

require_once __DIR__ . '/../vendor/autoload.php';

include 'Entity/ClassA.php';

/** @var \Silex\Application $app */
$app = require_once 'bootstrap.php';

/** @var \Doctrine\ORM\EntityManager $em */
$em = $app['doctrine.orm.entity_manager'];

$obj = new ClassA();
$obj->setMessage('asdasd');

$em->persist($obj);
$em->flush();

$entities = $em
    ->getRepository('ClassA')
    ->findAll()
;


dump($entities);
