<?php


namespace Quiz\Service\Exception;


use Exception;
use Throwable;

class InvalidUserException extends Exception
{
    /**
     * @param int $errorCode
     * @param Throwable|null $previous
     * @return InvalidUserException
     */
    public static function forInvalidEmail(int $errorCode = 0, Throwable $previous = null): InvalidUserException
    {
        return new self("Email is not valid", $errorCode, $previous);
    }

    /**
     * @param int $nrOfCharacters
     * @param int $errorCode
     * @param Throwable|null $previous
     * @return InvalidUserException
     */
    public static function forNameTooShort(int $nrOfCharacters,int $errorCode = 0, Throwable $previous = null): InvalidUserException
    {
        return new self("Name is too short it must contain at least $nrOfCharacters characters ", $errorCode, $previous);
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