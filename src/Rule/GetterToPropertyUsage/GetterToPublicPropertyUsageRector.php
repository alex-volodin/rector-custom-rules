<?php

declare(strict_types=1);

namespace RectorCustomRules\Rule\GetterToPropertyUsage;

use PhpParser\Node;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class GetterToPublicPropertyUsageRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * @param Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {

        return null;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace Getter with Public Property', [new CodeSample(<<<'CODE_SAMPLE'
$some->getName();
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
$some->name;
CODE_SAMPLE
        )]);
    }
}
