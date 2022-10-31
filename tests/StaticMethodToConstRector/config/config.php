<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorCustomRules\Rule\StaticMethodToConstRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(StaticMethodToConstRector::class, [
        StaticMethodToConstRector::CLASSES => [
            'RightClass',
        ],
    ]);
};
