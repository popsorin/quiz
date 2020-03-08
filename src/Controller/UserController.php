<?php

namespace Quiz\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Quiz\Entitie\User;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Test\Repository\RepositoryManager;
use ReflectionClass;
use ReflectionException;

class UserController extends Controller
{
    public function __construct(RendererInterface $renderer, RepositoryInterface $repository)
    {
        parent::__construct($renderer, $repository);
    }

    /**
     * @param Request $request
     * @return object
     * @throws ReflectionException
     */
    public function extractUser(Request $request)
    {
        $reflection = new ReflectionClass(User::class);
        $properties = $reflection->getProperties();
        $entity = $reflection->newInstanceWithoutConstructor();
        foreach ($properties as $property) {
            $property->setAccessible(true);
            if($request->getParameter($property->getName()) === null)
                continue;
            $property->setValue($entity, $request->getParameter($property->getName()));
        }

        return $entity;
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @throws ReflectionException
     */
    public function add(Request $request, array $attributes)
    {
        $user = $this->extractUser($request);
        $this->repository->insertOnDuplicateKeyUpdate($user);
      //  return $this->renderer->renderView(,$attributes);
    }
}