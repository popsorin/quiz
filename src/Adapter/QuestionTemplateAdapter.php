<?php


namespace Quiz\Adapter;


use Quiz\Entity\QuestionInstance;
use ReallyOrm\Entity\EntityInterface;

class QuestionTemplateAdapter implements EntityInterface
{
    /**
     * @param EntityInterface $questionTemplate
     * @param int $id
     * @return QuestionInstance
     */
    public function getQuestionInstance(EntityInterface $questionTemplate, int $id)
    {
        $text = $questionTemplate->getText();
        $type = $questionTemplate->getType();
        $questionTemplateId = $questionTemplate->getId();

        return new QuestionInstance($text, $id, $type, $questionTemplateId, "");
    }
}