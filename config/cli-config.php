<?php

use App\Application;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

/** @var Application $application */
$application = require __DIR__ . '/../bootstrap/app.php';

$serviceContainer = $application->getServiceContainer();
$entityManager = $serviceContainer->get(EntityManagerInterface::class);

return ConsoleRunner::createHelperSet($entityManager);
