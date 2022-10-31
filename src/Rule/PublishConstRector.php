<?php

declare(strict_types=1);

namespace RectorCustomRules\Rule;

use PhpParser\Node;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

class PublishConstRector extends AbstractRector implements ConfigurableRectorInterface
{
    public const CLASSES = 'classes';

    private const PUBLIC = 1;

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
        return [Node\Stmt\ClassConst::class];
    }

    /**
     * @param Node\Stmt\ClassConst $node
     */
    public function refactor(Node $node): ?Node
    {
        $parent = $node->getAttribute(AttributeKey::PARENT_NODE);

        if (!$parent instanceof Node\Stmt\Class_) {
            return null;
        }

        if (!in_array($parent->name->name, $this->classes)) {
            return null;
        }

        if ($node->flags === self::PUBLIC) {
            return null;
        }

        $node->flags = self::PUBLIC;

        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Publish constants instead of private or protected', []);
    }
}
