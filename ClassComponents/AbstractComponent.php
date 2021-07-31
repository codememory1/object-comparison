<?php

namespace Codememory\Components\ObjectComparison\ClassComponents;

use Codememory\Components\ObjectComparison\Attribute;
use Codememory\Components\ObjectComparison\Interfaces\ClassComponentInterface;
use JetBrains\PhpStorm\Pure;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Class AbstractComponent
 *
 * @package Codememory\Components\ObjectComparison\ClassComponents
 *
 * @author  Codememory
 */
abstract class AbstractComponent implements ClassComponentInterface
{

    /**
     * @var ReflectionProperty|ReflectionMethod
     */
    protected ReflectionProperty|ReflectionMethod $reflector;

    /**
     * AbstractComponent constructor.
     *
     * @param ReflectionProperty|ReflectionMethod $reflector
     */
    public function __construct(ReflectionProperty|ReflectionMethod $reflector)
    {

        $this->reflector = $reflector;

    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getName(): string
    {

        return $this->reflector->getName();

    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getAttributes(): array
    {

        $reflectionAttributes = $this->reflector->getAttributes();
        $attributes = [];

        foreach ($reflectionAttributes as $reflectionAttribute) {
            $attributes[] = new Attribute($reflectionAttribute);
        }

        return $attributes;

    }

    /**
     * @inheritDoc
     */
    public function getReflector(): ReflectionProperty|ReflectionMethod
    {

        return $this->reflector;

    }


}