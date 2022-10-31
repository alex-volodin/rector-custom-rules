<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorCustomRules\Rule\PublishConstRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(PublishConstRector::class, [
        PublishConstRector::CLASSES => [
            'RightClass',
        ],
    ]);
};
