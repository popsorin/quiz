<?php


namespace Quiz\Factory;


use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use Framework\Http\Session;
use Quiz\Entity\QuizTemplate;
use ReallyOrm\Entity\EntityInterface;
use ReflectionException;

class QuizTemplateFactory extends AbstractFactory
{

    /**
     * It counts the number of questions that are received in the request
     * @param Request $request
     * @return EntityInterface
     * @throws ReflectionException
     */
    public function createFromRequest(Request $request): EntityInterface
    {
        $entity = parent::createFromRequest($request);
        $entity->setNrQuestions(count($request->getParameter("questions")));
        return $entity;
    }

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return QuizTemplate::class;
    }
}