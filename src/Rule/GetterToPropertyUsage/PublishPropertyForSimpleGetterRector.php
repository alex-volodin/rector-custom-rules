<?php

declare(strict_types=1);

namespace RectorCustomRules\Rule\GetterToPropertyUsage;

use PhpParser\Node;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class PublishPropertyForSimpleGetterRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\ClassMethod::class];
    }

    /**
     * @param Node\Stmt\ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        return null;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Publish property for simple getter', [new CodeSample(<<<'CODE_SAMPLE'
private $name;
public function getName()
{
    return $this->name;
}
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
public $name;
public function getName()
{
    return $this->name;
}
CODE_SAMPLE
        )]);
    }
}
