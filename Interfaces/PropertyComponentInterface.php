<?php

namespace Codememory\Components\ObjectComparison\Interfaces;

/**
 * Interface PropertyComponentInterface
 *
 * @package Codememory\Components\ObjectComparison\Interfaces
 *
 * @author  Codememory
 */
interface PropertyComponentInterface extends ClassComponentInterface
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the value of a specific property
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param object $objectWithProperty
     *
     * @return mixed
     */
    public function getValue(object $objectWithProperty): mixed;

}