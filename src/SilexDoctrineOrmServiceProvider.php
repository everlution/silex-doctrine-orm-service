<?php

namespace Everlution\SilexDoctrineOrmService;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use Silex\Application;
use Silex\ServiceProviderInterface;

class SilexDoctrineOrmServiceProvider implements ServiceProviderInterface
{
    private $metadataConfig;

    public function __construct(array $metadataConfig)
    {
        $this->metadataConfig = $metadataConfig;
    }

    public function register(Application $app)
    {
        if (isset($app['db.options'])) {
            // define the default entity manager
            $this->registerEntityManager(
                $app,
                'doctrine.orm.entity_manager',
                $app['db'],
                $this->getMetadataConfig($app, $this->metadataConfig)
            );
        } elseif (isset($app['dbs.options'])) {
            // define an entity manager for every connection
            foreach ($app['dbs.options'] as $connectionName => $values) {
                $this->registerEntityManager(
                    $app,
                    sprintf('doctrine.orm.entity_manager.%s', $connectionName),
                    $app['dbs'][$connectionName],
                    $this->getMetadataConfig($app, $this->metadataConfig[$connectionName])
                );
            }
        } else {
            throw new \Exception('Need to define "db.options" or "dbs.options"');
        }
    }

    public function boot(Application $app)
    {
    }

    private function registerEntityManager(
        Application $app,
        $serviceName,
        Connection $connection,
        Configuration $metadataConfig
    ) {
        $app[$serviceName] = $app->share(function () use ($app, $connection, $metadataConfig) {
            return EntityManager::create($connection, $metadataConfig);
        });

        // create a console for every manager
        $consoleServiceName = sprintf('%s.console', $serviceName);
        $app[$consoleServiceName] = $app->share(function () use ($app, $serviceName) {
            return ConsoleRunner::createApplication(
                ConsoleRunner::createHelperSet($app[$serviceName])
            );
        });
    }

    private function getMetadataConfig(Application $app, array $metadataConfig)
    {
        foreach (['type', 'paths'] as $requiredParam) {
            if (!in_array($requiredParam, array_keys($metadataConfig))) {
                throw new \Exception(sprintf('Missing param %s in matadata config.', $requiredParam));
            }
        }

        if (!is_array($metadataConfig['paths'])) {
            throw new \Exception('Paths must be an array.');
        }

        switch ($metadataConfig['type']) {
            case 'annotation':
                $config = Setup::createAnnotationMetadataConfiguration(
                    $metadataConfig['paths'],
                    $app['debug']
                );

                // setting driver
                $driver = new AnnotationDriver(new AnnotationReader(), $metadataConfig['paths']);
                $config->setMetadataDriverImpl($driver);

                // setting cache
                $cache = new ArrayCache(); // todo add redis cache
                $config->setMetadataCacheImpl($cache);
                $config->setQueryCacheImpl($cache);

                break;
            default:
                throw new \Exception('The only type allowed is annotation.');
        }

        return $config;
    }
}
