<?php


namespace Quiz\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Session;
use Framework\Http\Stream;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Quiz\Entity\User;
use ReallyOrm\Entity\EntityInterface;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Test\Repository\RepositoryManager;
use ReflectionClass;
use ReflectionException;

class Controller extends AbstractController
{

    /**
     * @var RepositoryManager
     */
    protected $repository;

    /**
     * @var SessionInterface
     */
    protected $session;

    public function __construct(
        RendererInterface $renderer,
        RepositoryManager $repository,
        SessionInterface $session
    ) {
        parent::__construct($renderer);
        $this->repository = $repository;
        $this->session = $session;
    }

    /**
     * @param Request $request
     * @param string $className
     * @return EntityInterface|null
     */
    public function extractUser(Request $request, string $className): ?EntityInterface
    {
        return new $className($request->getParameter("name"), $request->getParameter("password"), $request->getParameter("role"));
    }

    /**
     * @param array $attributes
     * @param string $className
     * @return EntityInterface|null
     */
    public function extractUserId(array $attributes, string $className): ?EntityInterface
    {
        $entity = new $className("", "", "");
        $entity->setId($attributes['id']);

        return $entity;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function extractArray(Request $request): array
    {
        $name = $request->getParameter("name");
        $password = $request->getParameter("password");
        return ["name" =>$name, "password" => $password];
    }

    public function createResponse(Request $request, int $code, string $header, array $value): Response
    {
        $response = new Response(Stream::createFromString(" "),'1.1',"301");

        return $response->withAddedHeader($header, $value);
    }
}