<?php


namespace Quiz\Service\Validator;


use Quiz\Service\Exception\InvalidUserException;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryInterface;

class UserValidator implements EntityValidatorInterface
{
    const MIN_NAME_LENGTH = 3;
    const MIN_PASSWORD_LENGTH = 8;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * UserValidator constructor.
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
    /**
     * Checks whether the given user can be saved in the database.
     *
     * Throws a nested exception containing every issue found with the user data.
     *
     * To retrieve all the issues found when validating the user,
     * catch the exception and call $exception->getPrevious() until the result is null
     *
     * @param EntityInterface $user
     * @return void
     * @throws InvalidUserException
     */
    public function validate(EntityInterface $user): void
    {
        $exception = $user->getId() === null ? $this->validateBeforeCreate($user) : $this->validateBeforeUpdate($user);

        if(mb_strlen($user->getName(), 'UTF-8') < self::MIN_NAME_LENGTH) {
            $exception = InvalidUserException::forNameTooShort($user, self::MIN_NAME_LENGTH, 0, $exception);
        }

        if(mb_strlen($user->getName(), 'UTF-8') < self::MIN_NAME_LENGTH) {
            $exception = InvalidUserException::forNameTooShort($user, self::MIN_NAME_LENGTH, 0, $exception);
        }

        if(mb_strstr($user->getEmail(), '@', false, "UTF-8") === false) {
            $exception = InvalidUserException::forInvalidEmail($user, 0, $exception);
        }

        if($exception instanceof InvalidUserException) {
            throw $exception;
        }
    }

    /**
     *
     * Validates the given user for the add functionality
     * The user email is unique and must not be taken by somebody else
     *
     * @param EntityInterface $user
     * @return InvalidUserException|null
     */
    private function validateBeforeCreate(EntityInterface $user): ?InvalidUserException
    {
        $exception = null;

        $userFoundByEmail = $this->repository->findOneBy(["email" => $user->getEmail()]);
        if($userFoundByEmail->getEmail() !== null) {
            $exception = InvalidUserException::forAlreadyTakenEmail($user, 0, $exception);
        }

        if(mb_strlen($user->getPassword(), 'UTF-8') < self::MIN_PASSWORD_LENGTH) {
            $exception = InvalidUserException::forPasswordTooShort(self::MIN_PASSWORD_LENGTH, 0, $exception);
        }

        return $exception;
    }

    /**
     *
     * Validates the given user for the update functionality
     * The user email is unique and must not be taken by somebody else
     * Validates if the given user is the same as the user the one that made the update
     *
     * @param EntityInterface $user
     * @return InvalidUserException|null
     */
    private function validateBeforeUpdate(EntityInterface $user): ?InvalidUserException
    {
        $exception = null;

        $userFoundByEmail = $this->repository->findOneBy(["email" => $user->getEmail()]);
        $userFoundById = $this->repository->find($user->getId());

        if($userFoundById->getEmail() === $userFoundByEmail->getEmail()) {
            return $exception;
        }

        if($userFoundByEmail->getEmail() !== null) {
            $exception = InvalidUserException::forAlreadyTakenEmail($user, 0, $exception);
        }

        if(mb_strlen($userFoundById->getPassword(), 'UTF-8') < self::MIN_PASSWORD_LENGTH) {
            $exception = InvalidUserException::forPasswordTooShort(self::MIN_PASSWORD_LENGTH, 0, $exception);
        }

        return $exception;
    }
}