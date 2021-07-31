<?php

namespace Codememory\Components\ObjectComparison\ClassComponents;

use Codememory\Components\ObjectComparison\Interfaces\PropertyComponentInterface;

/**
 * Class PropertyComponent
 *
 * @package Codememory\Components\ObjectComparison
 *
 * @author  Codememory
 */
class PropertyComponent extends AbstractComponent implements PropertyComponentInterface
{

    /**
     * @inheritDoc
     */
    public function getValue(object $objectWithProperty): mixed
    {

        $this->reflector->setAccessible(true);

        return $this->reflector->getValue($objectWithProperty);

    }

}