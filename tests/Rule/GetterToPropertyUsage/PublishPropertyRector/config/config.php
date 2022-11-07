<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorCustomRules\Rule\GetterToPropertyUsage\PublishPropertyRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(PublishPropertyRector::class);
};
