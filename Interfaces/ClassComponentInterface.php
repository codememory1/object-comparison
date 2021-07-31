<?php

namespace Codememory\Components\ObjectComparison\Interfaces;

use ReflectionMethod;
use ReflectionProperty;

/**
 * Interface ClassComponentInterface
 *
 * @package Codememory\Components\ObjectComparison\Interfaces
 *
 * @author  Codememory
 */
interface ClassComponentInterface
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the name of the current class
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return string
     */
    public function getName(): string;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns class attributes
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return AttributeInterface[]
     */
    public function getAttributes(): array;

    /**
     * @return ReflectionProperty|ReflectionMethod
     */
    public function getReflector(): ReflectionProperty|ReflectionMethod;

}