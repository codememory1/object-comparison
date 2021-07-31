<?php

namespace Codememory\Components\ObjectComparison;

use Codememory\Components\Attributes\AttributeAssistant;
use Codememory\Components\Attributes\Interfaces\AssistantInterface;
use Codememory\Components\Attributes\Interfaces\TargetInterface;
use Codememory\Components\Attributes\Targets\ClassTarget;
use Codememory\Components\ObjectComparison\Interfaces\AttributeInterface;
use JetBrains\PhpStorm\Pure;
use ReflectionAttribute;
use ReflectionException;

/**
 * Class Attribute
 *
 * @package Codememory\Components\ObjectComparison
 *
 * @author  Codememory
 */
class Attribute implements AttributeInterface
{

    /**
     * @var ReflectionAttribute
     */
    private ReflectionAttribute $attribute;

    /**
     * @var AssistantInterface
     */
    private AssistantInterface $attributeAssistant;

    /**
     * @var TargetInterface
     */
    private TargetInterface $target;

    /**
     * Attribute constructor.
     *
     * @param ReflectionAttribute $attribute
     */
    #[Pure]
    public function __construct(ReflectionAttribute $attribute)
    {

        $this->attribute = $attribute;
        $this->attributeAssistant = new AttributeAssistant();
        $this->target = new ClassTarget($this->attributeAssistant);

    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getName(): string
    {

        return $this->attribute->getName();

    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function getArguments(): array
    {

        return $this->target->getAttributeArguments($this->attribute);

    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function existArgument(string $name): bool
    {

        return array_key_exists($name, $this->getArguments());

    }

    /**
     * @inheritDoc
     */
    public function getReflectionAttribute(): ReflectionAttribute
    {

        return $this->attribute;

    }

}