<?php


namespace Quiz\Factory;


use Framework\Http\Request;
use ReallyOrm\Entity\EntityInterface;
use ReflectionClass;
use ReflectionException;

abstract class AbstractFactory
{
    /**
     * @param Request $request
     * @return EntityInterface
     *
     * This function gets all the data from the request by extracting all the
     * elements that have the same key as the name of the entity properties
     * @throws ReflectionException
     * @throws ReflectionException
     * @throws ReflectionException
     */
    public function createFromRequest(Request $request): EntityInterface
    {
        $parameters = $request->getParameters();
        $reflection = new ReflectionClass($this->getEntityName());
        $entity = $reflection->newInstanceWithoutConstructor();
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            if(array_key_exists($property->getName(), $parameters)) {
                $property->setValue($entity, $parameters[$property->getName()]);
            }
        }

        return $entity;
    }

    /**
     * @return string
     */
    public abstract function getEntityName(): string;
}