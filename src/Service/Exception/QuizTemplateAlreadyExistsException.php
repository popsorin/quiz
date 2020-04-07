<?php


namespace Quiz\Service\Exception;


use Exception;
use ReallyOrm\Entity\EntityInterface;
use Throwable;

class QuizTemplateAlreadyExistsException extends Exception
{
    /**
     * @var EntityInterface
     */
    private $entity;

    /**
     * QuizTemplateAlreadyExistsException constructor.
     * @param EntityInterface $entity
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(EntityInterface $entity, $message = "Quiz name already exists", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->entity = $entity;
    }

    /**
     * @return EntityInterface
     */
    public function getEntity(): EntityInterface
    {
        return $this->entity;
    }
}