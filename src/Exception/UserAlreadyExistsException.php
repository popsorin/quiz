<?php

namespace Quiz\Exception;

use Exception;
use ReallyOrm\Entity\EntityInterface;
use Throwable;

class UserAlreadyExistsException extends Exception
{
    /**
     * @var EntityInterface
     */
    private $entity;

    /**
     * UserAlreadyExistsException constructor.
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
    public function getEntity(): ?EntityInterface
    {
        return $this->entity;
    }
}