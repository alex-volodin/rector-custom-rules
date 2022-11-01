<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorCustomRules\Rule\Others\ReplaceStringWithConstRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ReplaceStringWithConstRector::class, [
        ReplaceStringWithConstRector::REPLACEMENT_LIST => [
            ['DateTime', \DateTime::class, 'ATOM'],
        ],
    ]);
};
