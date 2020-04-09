<?php


namespace Quiz\Service\Exception;


use Exception;
use ReallyOrm\Entity\EntityInterface;
use Throwable;

class InvalidUserException extends Exception
{
    /**
     * @param EntityInterface $user
     * @param int $errorCode
     * @param Throwable|null $previous
     * @return InvalidUserException
     */
    public static function forInvalidEmail(EntityInterface $user, int $errorCode = 0, Throwable $previous = null): InvalidUserException
    {
        return new self("{$user->getEmail()} is not valid", $errorCode, $previous);
    }

    /**
     * @param EntityInterface $user
     * @param int $errorCode
     * @param Throwable|null $previous
     * @return InvalidUserException
     */
    public static function forAlreadyTakenEmail(EntityInterface $user, int $errorCode = 0, Throwable $previous = null): InvalidUserException
    {
        return new self("{$user->getEmail()} is already taken", $errorCode, $previous);
    }

    /**
     * @param EntityInterface $user
     * @param int $nrOfCharacters
     * @param int $errorCode
     * @param Throwable|null $previous
     * @return InvalidUserException
     */
    public static function forNameTooShort(EntityInterface $user, int $nrOfCharacters,int $errorCode = 0, Throwable $previous = null): InvalidUserException
    {
        return new self("{$user->getName()} is too short it must contain at least $nrOfCharacters characters ", $errorCode, $previous);
    }

    /**
     * @param int $nrOfCharacters
     * @param int $errorCode
     * @param Throwable|null $previous
     * @return InvalidUserException
     */
    public static function forPasswordTooShort(int $nrOfCharacters, int $errorCode = 0, Throwable $previous = null): InvalidUserException
    {
        return new self("Password is too short it must contain at least $nrOfCharacters characters ", $errorCode, $previous);
    }
}