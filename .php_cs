<?php

require_once './vendor/autoload.php';

$finder = \Symfony\CS\Finder\DefaultFinder::create()
    ->in('src/');

return \Symfony\CS\Config\Config::create()
    ->setUsingCache(true)
    ->finder($finder);
