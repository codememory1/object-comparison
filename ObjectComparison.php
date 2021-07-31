<?php

namespace Codememory\Components\ObjectComparison;

use Codememory\Components\ObjectComparison\ClassComponents\AbstractComponent;
use Codememory\Components\ObjectComparison\Interfaces\AttributeInterface;
use Codememory\Components\ObjectComparison\Interfaces\MethodComponentInterface;
use Codememory\Components\ObjectComparison\Interfaces\ObjectInformationInterface;
use Codememory\Components\ObjectComparison\Interfaces\PropertyComponentInterface;
use Codememory\Components\ObjectComparison\Traits\TrackersTrait;

/**
 * Class ObjectComparison
 *
 * @package Codememory\Components\ObjectComparison
 *
 * @author  Codememory
 */
class ObjectComparison
{

    use TrackersTrait;

    /**
     * @var object
     */
    private object $oldObject;

    /**
     * @var object
     */
    private object $newObject;

    /**
     * ObjectComparison constructor.
     *
     * @param object $oldObject
     * @param object $newObject
     */
    public function __construct(object $oldObject, object $newObject)
    {

        $this->oldObject = $oldObject;
        $this->newObject = $newObject;

    }

    /**
     * @return ObjectComparison
     */
    public function compare(): ObjectComparison
    {

        $oldObjectInfo = $this->objectInformation($this->oldObject);
        $newObjectInfo = $this->objectInformation($this->newObject);
        $propertiesForAll = $this->getRemainingProperties($newObjectInfo, $oldObjectInfo);
        $methodsForAll = $this->getRemainingMethods($newObjectInfo, $oldObjectInfo);

        // Save information if the class has been renamed
        $this->classNameComparisonHandler($oldObjectInfo, $newObjectInfo);

        // Save information if new properties have been added
        $this->trackComponentsStateAndSave(
            $newObjectInfo->getProperties(),
            $this->getPropertyNames($oldObjectInfo->getProperties()),
            $this->comparisonInformation['added']['properties']
        );

        // Save information if new methods have been added
        $this->trackComponentsStateAndSave(
            $newObjectInfo->getMethods(),
            $this->getMethodNames($oldObjectInfo->getMethods()),
            $this->comparisonInformation['added']['methods']
        );

        // Save information if properties have been removing
        $this->trackComponentsStateAndSave(
            $oldObjectInfo->getProperties(),
            $this->getPropertyNames($newObjectInfo->getProperties()),
            $this->comparisonInformation['remotely']['properties']
        );

        // Save information if methods have been removing
        $this->trackComponentsStateAndSave(
            $oldObjectInfo->getMethods(),
            $this->getMethodNames($newObjectInfo->getMethods()),
            $this->comparisonInformation['remotely']['methods']
        );

        // Save information if new attributes have been added to the class
        $this->trackClassAttributesStateAndSave(
            $newObjectInfo->getClassAttributes(),
            $this->getAttributeNames($oldObjectInfo->getClassAttributes()),
            $newObjectInfo->getReflection()->getName(),
            $this->comparisonInformation['added']
        );

        // Save information if attributes have been removed from the class
        $this->trackClassAttributesStateAndSave(
            $oldObjectInfo->getClassAttributes(),
            $this->getAttributeNames($newObjectInfo->getClassAttributes()),
            $newObjectInfo->getReflection()->getName(),
            $this->comparisonInformation['remotely']
        );

        // Saves information if property attributes have been removed or new attributes have been added to this property
        $this->trackComponentAttributesStateAndSave($propertiesForAll, $oldObjectInfo, $oldObjectInfo->getProperties(), 'property');

        // Saves information if method attributes have been removed or new attributes have been added to this method
        $this->trackComponentAttributesStateAndSave($methodsForAll, $oldObjectInfo, $oldObjectInfo->getMethods(), 'method');

        // Saves information if the property's attribute arguments have been changed
        $this->trackChangesAttributeArguments($propertiesForAll, $oldObjectInfo->getProperties());

        // Saves information if the method's attribute arguments have been changed
        $this->trackChangesAttributeArguments($methodsForAll, $oldObjectInfo->getMethods());

        return $this;

    }

    /**
     * @return array
     */
    public function getComparisonResult(): array
    {

        return $this->comparisonInformation;

    }

    /**
     * @param object $trackedObject
     *
     * @return ObjectInformationInterface
     */
    public function objectInformation(object $trackedObject): ObjectInformationInterface
    {

        return new ObjectInformation($trackedObject);

    }

    /**
     * @param ObjectInformationInterface $oldObjectInfo
     * @param ObjectInformationInterface $newObjectInfo
     *
     * @return void
     */
    private function classNameComparisonHandler(ObjectInformationInterface $oldObjectInfo, ObjectInformationInterface $newObjectInfo): void
    {

        if ($oldObjectInfo->getClassName() !== $newObjectInfo->getClassName()) {
            $this->comparisonInformation['changes']['className'] = $newObjectInfo->getClassName();
        }

    }

    /**
     * @param PropertyComponentInterface[] $properties
     *
     * @return string[]
     */
    private function getPropertyNames(array $properties): array
    {

        $propertyNames = [];

        foreach ($properties as $propertyComponent) {
            $propertyNames[] = $propertyComponent->getName();
        }

        return $propertyNames;

    }

    /**
     * @param MethodComponentInterface[] $methods
     *
     * @return string[]
     */
    private function getMethodNames(array $methods): array
    {

        $methodNames = [];

        foreach ($methods as $methodComponent) {
            $methodNames[] = $methodComponent->getName();
        }

        return $methodNames;

    }

    /**
     * @param AttributeInterface[] $attributes
     *
     * @return string[]
     */
    private function getAttributeNames(array $attributes): array
    {

        $attributeNames = [];

        foreach ($attributes as $attribute) {
            $attributeNames[] = $attribute->getName();
        }

        return $attributeNames;

    }

    /**
     * @param string $name
     * @param array  $components
     *
     * @return AbstractComponent|bool
     */
    private function getComponentByName(string $name, array $components): AbstractComponent|bool
    {

        foreach ($components as $component) {
            if ($component->getName() === $name) {
                return $component;
            }
        }

        return false;

    }

    /**
     * @param ObjectInformationInterface $newObjectInformation
     * @param ObjectInformationInterface $oldObjectInformation
     *
     * @return PropertyComponentInterface[]
     */
    private function getRemainingProperties(ObjectInformationInterface $newObjectInformation, ObjectInformationInterface $oldObjectInformation): array
    {

        $remainingProperties = [];
        $propertiesOfNewObject = $this->getPropertyNames($newObjectInformation->getProperties());
        $propertiesOfOldObject = $this->getPropertyNames($oldObjectInformation->getProperties());

        foreach ($propertiesOfNewObject as $propertyName) {
            if (in_array($propertyName, $propertiesOfOldObject)) {
                $remainingProperties[] = $this->getComponentByName($propertyName, $newObjectInformation->getProperties());
            }
        }

        return $remainingProperties;

    }

    /**
     * @param ObjectInformationInterface $newObjectInformation
     * @param ObjectInformationInterface $oldObjectInformation
     *
     * @return array
     */
    private function getRemainingMethods(ObjectInformationInterface $newObjectInformation, ObjectInformationInterface $oldObjectInformation): array
    {

        $remainingMethods = [];
        $methodsFromNewObject = $this->getMethodNames($newObjectInformation->getMethods());
        $methodsFromOldObject = $this->getMethodNames($oldObjectInformation->getMethods());

        foreach ($methodsFromNewObject as $methodName) {
            if (in_array($methodName, $methodsFromOldObject)) {
                $remainingProperties[] = $this->getComponentByName($methodName, $newObjectInformation->getMethods());
            }
        }

        return $remainingMethods;

    }

    /**
     * @param string            $name
     * @param AbstractComponent $component
     *
     * @return AttributeInterface|bool
     */
    private function getAttributeByName(string $name, AbstractComponent $component): AttributeInterface|bool
    {

        $attributes = $component->getAttributes();

        foreach ($attributes as $attribute) {
            if ($attribute->getName() === $name) {
                return $attribute;
            }
        }

        return false;

    }

    /**
     * @param string                       $attributeName
     * @param PropertyComponentInterface[] $components
     * @param string                       $componentName
     *
     * @return AttributeInterface|bool
     */
    private function getAttributeByNameAndByComponentName(string $attributeName, array $components, string $componentName): AttributeInterface|bool
    {

        foreach ($components as $component) {
            if ($component->getName() === $componentName) {
                $propertyAttributes = $component->getAttributes();

                foreach ($propertyAttributes as $propertyAttribute) {
                    if ($propertyAttribute->getName() === $attributeName) {
                        return $propertyAttribute;
                    }
                }
            }
        }

        return false;

    }

    /**
     * @param string               $name
     * @param AttributeInterface[] $attributes
     *
     * @return bool
     */
    private function existAttribute(string $name, array $attributes): bool
    {

        foreach ($attributes as $attribute) {
            if ($attribute->getName() === $name) {
                return true;
            }
        }

        return false;

    }

}