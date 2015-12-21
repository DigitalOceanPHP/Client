<?php

use Symfony\CS\Config\Config;
use Symfony\CS\Finder\DefaultFinder;
use Symfony\CS\FixerInterface;

return Config::create()
    ->level(FixerInterface::SYMFONY_LEVEL)
    ->fixers(['ordered_use', 'phpdoc_order', 'short_array_syntax', '-visibility'])
    ->setUsingCache(true)
    ->finder(DefaultFinder::create()->in(__DIR__));
