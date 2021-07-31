<?php

namespace Codememory\Components\ObjectComparison\Interfaces;

use ReflectionAttribute;

/**
 * Interface AttributeInterface
 *
 * @package Codememory\Components\ObjectComparison\Interfaces
 *
 * @author  Codememory
 */
interface AttributeInterface
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the name of the attribute
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return string
     */
    public function getName(): string;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the arguments of an attribute
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     */
    public function getArguments(): array;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Checks for the existence of an argument in an attribute
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param string $name
     *
     * @return bool
     */
    public function existArgument(string $name): bool;

    /**
     * @return ReflectionAttribute
     */
    public function getReflectionAttribute(): ReflectionAttribute;

}