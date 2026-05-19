<?php

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/tests'])
    // Choose the sets you want to apply
    ->withSets([PHPUnitSetList::PHPUNIT_100, PHPUnitSetList::PHPUNIT_110, PHPUnitSetList::PHPUNIT_CODE_QUALITY]);
