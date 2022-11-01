<?php

declare(strict_types=1);

namespace RectorCustomRules\Rule\CustomEnumToPhpEnum;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

class StaticMethodToConstRector extends AbstractRector implements ConfigurableRectorInterface
{
    public const CLASSES = 'classes';

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
        return [StaticCall::class, MethodCall::class];
    }
    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(Node $node) : ?Node
    {
        if ($node->name instanceof Expr) {
            return null;
        }

        if (!$this->isRightClass($node)) {
            return null;
        }

        if ($node instanceof MethodCall) {
            return $this->refactorMethodCall($node);
        }

        $methodName = $this->getName($node->name);
        if ($methodName === null) {
            return null;
        }

        $className = $this->getName($node->class);
        if (!\is_string($className)) {
            return null;
        }

        return $this->nodeFactory->createClassConstFetch($className, $this->convertToConstName($methodName));
    }

    private function isRightClass(Node $node): bool
    {
        $parentNode = $node instanceof StaticCall ? $node->class : $node->var;
        foreach ($this->classes as $class) {
            if ($this->isObjectType($parentNode, new ObjectType($class))) {
                return true;
            }
        }

        return false;
    }

    private function convertToConstName(string $methodName): string
    {
        $result = mb_strtolower(preg_replace("/([A-Z])/u", '_$1', $methodName));

        return mb_strtoupper($result);
    }

    /**
     * @return null|\PhpParser\Node\Expr\ClassConstFetch|\PhpParser\Node\Expr\PropertyFetch
     */
    private function refactorMethodCall(MethodCall $methodCall)
    {
        if (!$methodCall->var instanceof StaticCall) {
            return null;
        }
        $staticCall = $methodCall->var;
        $className = $this->getName($staticCall->class);
        if ($className === null) {
            return null;
        }

        $methodName = $this->getName($staticCall->name);
        if ($methodName === null) {
            return null;
        }

        return $this->nodeFactory->createClassConstFetch($className, $this->convertToConstName($methodName));
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return new RuleDefinition('Refactor static method to Class const', [new CodeSample(<<<'CODE_SAMPLE'
$state = SomeClass::state()->getValue();
$state = SomeClass::state();
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
$state = SomeClass::STATE;
CODE_SAMPLE
        )]);
    }
}
