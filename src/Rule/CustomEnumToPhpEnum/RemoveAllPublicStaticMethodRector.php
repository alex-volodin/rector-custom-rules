<?php

declare(strict_types=1);

namespace RectorCustomRules\Rule\CustomEnumToPhpEnum;

use A;
use PhpParser\Node;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

class RemoveAllPublicStaticMethodRector extends AbstractRector implements ConfigurableRectorInterface
{
    public const CLASSES = 'classes';
    private const PUBLIC_STATIC = 9;

    /**
     * @var array<string>
     */
    private array $classes = [];

    /**
     * @param array<string, array<string>> $configuration
     */
    public function configure(array $configuration): void
    {
        $this->classes = $configuration[self::CLASSES] ?? [];

        Assert::notEmpty($this->classes);

        foreach ($this->classes as $class) {
            Assert::string($class);
            Assert::notEq($class, '', 'class should not be empty');
        }
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Class_::class];
    }
    /**
     * @param Node\Stmt\Class_ $node
     */
    public function refactor(Node $node) : ?Node
    {
        if (!in_array($this->getName($node), $this->classes)) {
            return null;
        }

        $keys = [];

        foreach ($node->stmts as $key => $stmt) {
            if (!$stmt instanceof Node\Stmt\ClassMethod) {
                continue;
            }

            if ($stmt->flags !== self::PUBLIC_STATIC) {
                continue;
            }

            $keys[] = $key;
        }

        if ($keys === []) {
            return null;
        }

        foreach ($keys as $key) {
            unset($node->stmts[$key]);
        }

        return $node;
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return new RuleDefinition('Remove all public static methods for class', [new CodeSample(<<<'CODE_SAMPLE'
class A{
    public static function test(){}
}
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
class A{
}
CODE_SAMPLE
        )]);
    }
}
