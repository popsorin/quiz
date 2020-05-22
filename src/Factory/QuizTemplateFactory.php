<?php


namespace Quiz\Factory;


use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use Framework\Http\Session;
use Quiz\Entity\QuizTemplate;

class QuizTemplateFactory
{
    /**
     * @param Request $request
     * @param SessionInterface $session
     * @param array $attributes
     * @param string $nameKey
     * @param string $descriptionKey
     * @param string $questionNumberKey
     * @return QuizTemplate
     */
    public function createFromRequest(
        Request $request,
        SessionInterface $session,
        array $attributes,
        string $nameKey,
        string $descriptionKey,
        string $questionNumberKey
    ): QuizTemplate {
        $session->start();
        $parameters = $request->getParameters();
        $name = (isset($parameters[$nameKey])) ? $parameters[$nameKey] : "";
        $description = (isset($parameters[$descriptionKey])) ? $parameters[$descriptionKey] : "";
        $nrQuestions = (isset($parameters[$questionNumberKey])) ? count($parameters[$questionNumberKey]) : 0;
        $id = (isset($attributes["id"])) ? $attributes["id"] : 0;
        $createdBy = ($session->get("id")) ? $session->get("id") : 0;
        $quizTemplate = new QuizTemplate($createdBy, $name, $description, $nrQuestions);
        $quizTemplate->setId($id);

        return $quizTemplate;
    }
}