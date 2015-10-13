<?php

require_once './vendor/autoload.php';

use Symfony\CS\Finder\DefaultFinder;
use Symfony\CS\Config\Config;

$finder = DefaultFinder::create()
    ->in('src/');

return Config::create()
    ->setUsingCache(true)
    ->finder($finder);
