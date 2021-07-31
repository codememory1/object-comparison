<?php

namespace Codememory\Components\ObjectComparison\Interfaces;

use ReflectionClass;

/**
 * Interface ObjectInformationInterface
 *
 * @package Codememory\Components\ObjectComparison\Interfaces
 *
 * @author  Codememory
 */
interface ObjectInformationInterface
{

    /**
     * @see ClassComponentInterface::getName()
     */
    public function getClassName(): ?string;

    /**
     * @see ClassComponentInterface::getAttributes()
     */
    public function getClassAttributes(): array;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns an array of properties from the current class
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return PropertyComponentInterface[]
     */
    public function getProperties(): array;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the attributes of a specific property
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param PropertyComponentInterface $propertyComponent
     *
     * @return AttributeInterface[]
     */
    public function getPropertyAttributes(PropertyComponentInterface $propertyComponent): array;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns an array of methods from the current class
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return MethodComponentInterface[]
     */
    public function getMethods(): array;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the attributes of a specific method
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param MethodComponentInterface $methodComponent
     *
     * @return AttributeInterface[]
     */
    public function getMethodAttributes(MethodComponentInterface $methodComponent): array;

    /**
     * @return ReflectionClass
     */
    public function getReflection(): ReflectionClass;

}