<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorCustomRules\Rule\RemoveAllPublicStaticMethodRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RemoveAllPublicStaticMethodRector::class, [
        RemoveAllPublicStaticMethodRector::CLASSES => [
            'RightClass',
        ],
    ]);
};
