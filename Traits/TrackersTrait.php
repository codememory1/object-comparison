<?php

namespace Codememory\Components\ObjectComparison\Traits;

use Codememory\Components\ObjectComparison\ClassComponents\AbstractComponent;
use Codememory\Components\ObjectComparison\Interfaces\ObjectInformationInterface;
use Codememory\Support\Arr;

/**
 * Trait TrackersTrait
 *
 * @package Codememory\Components\ObjectComparison\Traits
 *
 * @author  Codememory
 */
trait TrackersTrait
{

    /**
     * @var array
     */
    private array $comparisonInformation = [
        'changes'  => [
            'className'          => false,
            'attributeArguments' => []
        ],
        'remotely' => [
            'methods'    => [],
            'properties' => [],
            'attributes' => [],
        ],
        'added'    => [
            'methods'    => [],
            'properties' => [],
            'attributes' => [],
        ],
    ];

    /**
     * @param array $components
     * @param array $componentNames
     * @param array $whereToAdd
     *
     * @return void
     */
    private function trackComponentsStateAndSave(array $components, array $componentNames, array &$whereToAdd): void
    {

        foreach ($components as $component) {
            if (!in_array($component->getName(), $componentNames)) {
                $whereToAdd[] = $component;
            }
        }

    }

    /**
     * @param array  $attributes
     * @param array  $attributeNames
     * @param string $to
     * @param array  $whereToAdd
     *
     * @return void
     */
    private function trackClassAttributesStateAndSave(array $attributes, array $attributeNames, string $to, array &$whereToAdd): void
    {

        foreach ($attributes as $attribute) {
            if (!in_array($attribute->getName(), $attributeNames)) {
                $whereToAdd['attributes'][] = [
                    'attribute'       => $attribute,
                    'to'              => $to,
                    'toComponentName' => 'class'
                ];
            }
        }

    }

    /**
     * @param AbstractComponent[]        $componentsForAll
     * @param ObjectInformationInterface $oldObjectInfo
     * @param array                      $componentsFromOldObject
     * @param string                     $componentName
     *
     * @return void
     */
    private function trackComponentAttributesStateAndSave(array $componentsForAll, ObjectInformationInterface $oldObjectInfo, array $componentsFromOldObject, string $componentName)
    {

        foreach ($componentsForAll as $component) {
            $componentAttributeNamesFromNewObject = $this->getAttributeNames($component->getAttributes());
            $componentOfOldObject = $this->getComponentByName($component->getName(), $componentsFromOldObject);
            $componentAttributeNamesFromOldObject = $this->getAttributeNames($componentOfOldObject->getAttributes());

            $this->iterationOverComponentAttributeNames(
                $componentAttributeNamesFromNewObject,
                $oldObjectInfo,
                $componentAttributeNamesFromOldObject,
                $component,
                $componentName,
                'added'
            );

            $this->iterationOverComponentAttributeNames(
                $componentAttributeNamesFromOldObject,
                $oldObjectInfo,
                $componentAttributeNamesFromNewObject,
                $component,
                $componentName,
                'remotely'
            );
        }

    }

    /**
     * @param array                      $componentAttributeNames
     * @param ObjectInformationInterface $oldObjectInfo
     * @param array                      $componentAttributeNamesForCheck
     * @param AbstractComponent          $component
     * @param string                     $componentName
     * @param string                     $whereToAdd
     *
     * @return void
     */
    private function iterationOverComponentAttributeNames(array $componentAttributeNames, ObjectInformationInterface $oldObjectInfo, array $componentAttributeNamesForCheck, AbstractComponent $component, string $componentName, string $whereToAdd): void
    {

        foreach ($componentAttributeNames as $componentAttributeName) {
            if ('added' === $whereToAdd) {
                $attribute = $this->getAttributeByName($componentAttributeName, $component);
            } else {
                $attribute = $this->getAttributeByNameAndByComponentName(
                    $componentAttributeName,
                    $oldObjectInfo->getProperties(),
                    $component->getName()
                );
            }

            if (false === in_array($componentAttributeName, $componentAttributeNamesForCheck)) {
                $this->comparisonInformation[$whereToAdd]['attributes'][] = [
                    'attribute'       => $attribute,
                    'to'              => $component,
                    'toComponentName' => $componentName
                ];
            }
        }

    }

    /**
     * @param AbstractComponent[] $componentsForAll
     * @param AbstractComponent[] $componentsFromOldObject
     *
     * @return void
     */
    private function trackChangesAttributeArguments(array $componentsForAll, array $componentsFromOldObject): void
    {

        foreach ($componentsForAll as $component) {
            $componentFromOldObject = $this->getComponentByName($component->getName(), $componentsFromOldObject);

            foreach ($component->getAttributes() as $attribute) {
                if ($this->existAttribute($attribute->getName(), $componentFromOldObject->getAttributes())) {
                    $componentAttributeFromOldObject = $this->getAttributeByName($attribute->getName(), $componentFromOldObject);
                    $diffArguments = Arr::recursiveDifference($attribute->getArguments(), $componentAttributeFromOldObject->getArguments());

                    if ([] !== $diffArguments) {
                        $this->comparisonInformation['changes']['attributeArguments'][] = [
                            'component' => $component,
                            'arguments' => $diffArguments,
                            'to'        => $attribute
                        ];
                    }

                }
            }
        }

    }

}