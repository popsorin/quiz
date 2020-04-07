<?php


namespace Quiz\Service\Validator;


use Quiz\Service\Exception\EmailAlreadyTakenException;
use Quiz\Service\Exception\UserNotFountException;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryInterface;

class UserValidator implements EntityValidator
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param EntityInterface $user
     * @return bool
     * @throws EmailAlreadyTakenException
     * @throws UserNotFountException
     */
    public function validate(EntityInterface $user): bool
    {
        if($this->repository->find($user->getId()) === null) {
            throw new UserNotFountException($user);
        }

        $userFoundByEmail = $this->repository->findOneBy(["email" => $user->getEmail()]);
        if($userFoundByEmail->getId() === null) {
            return true;
        }
        if($userFoundByEmail->getId() !== $user->getId()) {
            throw new EmailAlreadyTakenException($user);
        }

        return true;
    }
}