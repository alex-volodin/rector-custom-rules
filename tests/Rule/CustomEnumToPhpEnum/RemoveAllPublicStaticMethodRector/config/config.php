<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorCustomRules\Rule\CustomEnumToPhpEnum\RemoveAllPublicStaticMethodRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RemoveAllPublicStaticMethodRector::class, [
        RemoveAllPublicStaticMethodRector::CLASSES => [
            'RightClass',
        ],
    ]);
};
