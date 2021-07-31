<?php

namespace Codememory\Components\ObjectComparison;

use Codememory\Components\ObjectComparison\ClassComponents\MethodComponent;
use Codememory\Components\ObjectComparison\ClassComponents\PropertyComponent;
use Codememory\Components\ObjectComparison\Interfaces\MethodComponentInterface;
use Codememory\Components\ObjectComparison\Interfaces\ObjectInformationInterface;
use Codememory\Components\ObjectComparison\Interfaces\PropertyComponentInterface;
use JetBrains\PhpStorm\Pure;
use ReflectionClass;

/**
 * Class ObjectInformation
 *
 * @package Codememory\Components\ObjectComparison
 *
 * @author  Codememory
 */
class ObjectInformation implements ObjectInformationInterface
{

    /**
     * @var object
     */
    private object $trackedObject;

    /**
     * @var ReflectionClass
     */
    private ReflectionClass $reflection;

    /**
     * ObjectInformation constructor.
     *
     * @param object $trackedObject
     */
    public function __construct(object $trackedObject)
    {

        $this->trackedObject = $trackedObject;
        $this->reflection = new ReflectionClass($this->trackedObject);

    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getClassName(): ?string
    {

        return $this->reflection->getShortName();

    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getClassAttributes(): array
    {

        $reflectionAttributes = $this->reflection->getAttributes();
        $attributes = [];

        foreach ($reflectionAttributes as $reflectionAttribute) {
            $attributes[] = new Attribute($reflectionAttribute);
        }

        return $attributes;

    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getProperties(): array
    {

        $reflectionProperties = $this->reflection->getProperties();
        $properties = [];

        foreach ($reflectionProperties as $reflectionProperty) {
            $properties[] = new PropertyComponent($reflectionProperty);
        }

        return $properties;

    }

    /**
     * @inheritDoc
     */
    public function getPropertyAttributes(PropertyComponentInterface $propertyComponent): array
    {

        return $propertyComponent->getAttributes();

    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getMethods(): array
    {

        $reflectionMethods = $this->reflection->getMethods();
        $methods = [];

        foreach ($reflectionMethods as $reflectionProperty) {
            $methods[] = new MethodComponent($reflectionProperty);
        }

        return $methods;

    }

    /**
     * @inheritDoc
     */
    public function getMethodAttributes(MethodComponentInterface $methodComponent): array
    {

        return $methodComponent->getAttributes();

    }

    /**
     * @inheritDoc
     */
    public function getReflection(): ReflectionClass
    {

        return $this->reflection;

    }

}