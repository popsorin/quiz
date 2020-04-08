<?php


namespace Quiz\Service\Validator;


use Quiz\Service\Exception\InvalidUserException;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryInterface;

class UserValidator implements EntityValidatorInterface
{
    const NAME_LENGTH = 3;
    const PASSWORD_LENGTH = 8;

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
     *
     * Verify if the user can be saved in the database.
     *
     * Throws an exception containing every issue found with the user data
     *
     * @param EntityInterface $user
     * @return void
     * @throws InvalidUserException
     */
    public function validate(EntityInterface $user): void
    {
        $exception = $user->getId() === null ? $this->validateBeforeCreate($user) : $this->validateBeforeUpdate($user);

        if(mb_strlen($user->getName(), 'UTF-8') < self::NAME_LENGTH) {
            $exception = InvalidUserException::forNameTooShort(self::NAME_LENGTH, 0, $exception);
        }

        if(mb_strlen($user->getPassword(), 'UTF-8') < self::PASSWORD_LENGTH) {
            $exception = InvalidUserException::forPasswordTooShort(self::PASSWORD_LENGTH, 1, $exception);
        }

        if(mb_strstr($user->getEmail(), '@', false, "UTF-8") === false) {
            $exception = InvalidUserException::forInvalidEmail(0, $exception);
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
    private function validateBeforeCreate(EntityInterface $user)
    {
        $exception = null;

        $userFoundByEmail = $this->repository->findOneBy(["email" => $user->getEmail()]);
        if($userFoundByEmail !== null) {
            $exception = InvalidUserException::forInvalidEmail(0, $exception);
        }

        return $exception;
    }

    /**
     *
     * Validates the given user for the update functionality
     * The user email is unique and must not be taken by somebody else
     *
     * @param EntityInterface $user
     * @return InvalidUserException|null
     */
    private function validateBeforeUpdate(EntityInterface $user)
    {
        $exception = null;

        $userFoundByEmail = $this->repository->findOneBy(["email" => $user->getEmail()]);
        if($userFoundByEmail !== null) {
            $exception = InvalidUserException::forInvalidEmail(0, $exception);
        }

        return $exception;
    }
}