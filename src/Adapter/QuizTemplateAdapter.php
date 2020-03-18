<?php


namespace Quiz\Adapter;


use Quiz\Entity\QuizInstance;
use Quiz\Entity\QuizInstanceInterface;
use Quiz\Entity\QuizTemplateInterface;
use ReallyOrm\Entity\EntityInterface;

class QuizTemplateAdapter implements EntityInterface
{
    /**
     * @param EntityInterface $quizTemplate
     * @param int $userId
     * @return QuizInstance
     */
    public function getQuizInstance(EntityInterface $quizTemplate, int $userId): QuizInstance
    {
        $quizTemplateId = $quizTemplate->getId();
        $score = 0;
        $name = $quizTemplate->getName();
        $description = $quizTemplate->getDescription();
        $nrQuestions = $quizTemplate->getNrQuestions();
        return new QuizInstance(
            $quizTemplateId,
            $userId,
            $score,
            $name,
            $description,
            $nrQuestions
        );
    }
}