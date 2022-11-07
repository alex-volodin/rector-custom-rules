<?php

declare(strict_types=1);

namespace RectorCustomRules\Rule\GetterToPropertyUsage;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Property;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\Visibility;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class PublishPropertyRector extends AbstractRector
{
    private VisibilityManipulator $visibilityManipulator;

    public function __construct(
        VisibilityManipulator $visibilityManipulator,
    ) {
        $this->visibilityManipulator = $visibilityManipulator;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Property::class, Param::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Publish property for easy getter/setter', [new CodeSample(<<<'CODE_SAMPLE'
private $name;
public function getName()
{
    return $this->name;
}
public function setName($name)
{
    $this->name = $name;
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

    /**
     * @param Property|Param $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->visibilityManipulator->hasVisibility($node, Visibility::PUBLIC)) {
            return null;
        }

        if ($node instanceof Param) {
            $constructNode = $node->getAttribute(AttributeKey::PARENT_NODE);

            if (!$constructNode instanceof Node\Stmt\ClassMethod || $constructNode->name->name !== '__construct') {
                return null;
            }

            $classNode = $this->betterNodeFinder->findParentType($node, Node\Stmt\ClassLike::class);
        } else {
            $classNode = $node->getAttribute(AttributeKey::PARENT_NODE);
        }

        if (!$classNode instanceof Node\Stmt\Class_) {
            return null;
        }

        if (!$this->hasSimpleGetterAndSetter($classNode, $node)) {
            return null;
        }

        if ($node instanceof Param) {
            if ($node->flags & Visibility::PROTECTED) {
                $node->flags -= Visibility::PROTECTED;
            } else {
                $node->flags -= Visibility::PRIVATE;
            }

            $node->flags |= Visibility::PUBLIC;
        } else {
            $this->visibilityManipulator->changeNodeVisibility($node, Visibility::PUBLIC);
        }

        return $node;
    }

    private function hasSimpleGetterAndSetter(Node\Stmt\Class_ $classNode, Property|Param $node): bool
    {
        $propertyName = $this->getName($node);

        $getterName = 'get'.ucwords($propertyName);
        $setterName = 'set'.ucwords($propertyName);

        foreach ($classNode->stmts as $stmt) {
            if ($stmt instanceof Node\Stmt\ClassMethod) {
                if ($stmt->name->name === $getterName && !$this->isEasyGetterMethod($stmt, $node)) {
                    return false;
                } elseif ($stmt->name->name === $setterName && !$this->isEasySetterMethod($stmt, $propertyName)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function isEasyGetterMethod(Node\Stmt\ClassMethod $methodNode, Property|Param $node): bool
    {
        if ($methodNode->returnType !== null && $methodNode->returnType->name !== $node->getType()) {
            return false;
        }

        if ($methodNode->params !== []) {
            return false;
        }

        if (count($methodNode->stmts) !== 1) {
            return false;
        }

        $returnNode = $methodNode->stmts[0] ?? null;

        if (!$returnNode instanceof Node\Stmt\Return_) {
            return false;
        }

        $propertyFetchNode = $returnNode->expr;

        if (!$propertyFetchNode instanceof Node\Expr\PropertyFetch) {
            return false;
        }

        if (!$propertyFetchNode->var instanceof Node\Expr\Variable || $propertyFetchNode->var->name !== 'this') {
            return false;
        }

        if (!$propertyFetchNode->name instanceof Node\Identifier || $propertyFetchNode->name->name !== $this->getName($node)) {
            return false;
        }

        return true;
    }

    public function isEasySetterMethod(Node\Stmt\ClassMethod $methodNode, string $nodeName): bool
    {
        if ($methodNode->returnType instanceof Node\Identifier && $methodNode->returnType->name !== 'void') {
            return false;
        } elseIf ($methodNode->returnType !== null) {
            return false;
        }

        if (count($methodNode->params) !== 1) {
            return false;
        }

        if (count($methodNode->stmts) !== 1) {
            return false;
        }

        $stmtExprNode = $methodNode->stmts[0] ?? null;

        if (!$stmtExprNode instanceof Node\Stmt\Expression) {
            return false;
        }

        $assignNode = $stmtExprNode->expr;

        if (!$assignNode instanceof Node\Expr\Assign) {
            return false;
        }

        $propertyFetchNode = $assignNode->var;

        if (!$propertyFetchNode instanceof Node\Expr\PropertyFetch) {
            return false;
        }

        if (!$propertyFetchNode->var instanceof Node\Expr\Variable || $propertyFetchNode->var->name !== 'this') {
            return false;
        }

        if (!$propertyFetchNode->name instanceof Node\Identifier || $propertyFetchNode->name->name !== $nodeName) {
            return false;
        }

        if (!$assignNode->expr instanceof Node\Expr\Variable || $assignNode->expr->name !== $methodNode->params[0]?->var->name) {
            return false;
        }

        return true;
    }
}
