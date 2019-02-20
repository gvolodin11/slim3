<?php

require __DIR__ . '/bootstrap/app.php';
//doctrine connection
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Helper\HelperSet;

$config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
    $container['settings']['db']['meta']['entity_path'],
    $container['settings']['db']['meta']['auto_generate_proxies'],
    $container['settings']['db']['meta']['proxy_dir'],
    $container['settings']['db']['meta']['cache'],
    false
);

$em = \Doctrine\ORM\EntityManager::create($container['settings']['db']['connection'], $config);
//return ConsoleRunner::run(HelperSet::set($em));//Symfony\Component\Console\Helper\HelperSet

$helpers = new Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));
//return ConsoleRunner::createHelperSet($em);