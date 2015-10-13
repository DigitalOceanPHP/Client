<?php

require_once './vendor/autoload.php';

use Symfony\CS\Config\Config;
use Symfony\CS\Finder\DefaultFinder;
use Symfony\CS\FixerInterface;

$finder = DefaultFinder::create()
    ->in('src/');

return Config::create()
    ->level(FixerInterface::SYMFONY_LEVEL)
    ->setUsingCache(true)
    ->finder($finder);
