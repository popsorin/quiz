<?php


namespace Quiz\Factory;


use Framework\Http\Request;
use Quiz\Entity\QuestionTemplate;
use ReflectionClass;
use ReflectionException;

class QuestionTemplateFactory
{
    /**
     * @param Request $request
     * @return QuestionTemplate
     * @throws ReflectionException
     */
    public function createFromRequest(Request $request): QuestionTemplate
    {
        $reflection = new ReflectionClass($this->getCreatedEntityName());
        $questionTemplate = $reflection->newInstanceWithoutConstructor();
        $parameters = $request->getParameters();
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $property->setValue($questionTemplate, $parameters[$property->getName()]);
        }

        return $questionTemplate;
    }

    /**
     * @return string
     */
    private function getCreatedEntityName(): string
    {
        return QuestionTemplate::class;
    }
}