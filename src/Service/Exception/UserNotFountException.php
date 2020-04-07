<?php


namespace Quiz\Service\Exception;


use Exception;
use ReallyOrm\Entity\EntityInterface;
use Throwable;

class UserNotFountException extends Exception
{
    /**
     * @var EntityInterface
     */
    private $entity;

    /**
     * UserNotFountException constructor.
     * @param EntityInterface $entity
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(EntityInterface $entity, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->entity = $entity;
    }

    /**
     * @return EntityInterface|null
     */
    public function getEntity(): EntityInterface
    {
        return $this->entity;
    }
}