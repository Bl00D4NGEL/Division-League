<?php

namespace App\Controller;

use App\Models\LoginModel;
use App\Resource\LoginRequest;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    private LoginModel $loginModel;
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer, LoginModel $loginModel)
    {
        $this->serializer = $serializer;
        $this->loginModel = $loginModel;
    }

    /**
     * @Route("/login", name="login", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $req = $this->serializer->deserialize($request->getContent(), LoginRequest::class, 'json');
        return $this->loginModel->login($req);
    }

    /**
     * @Route("/auth", name="auth", methods={"GET"})
     * @return JsonResponse
     */
    public function auth(): JsonResponse
    {
        return $this->loginModel->auth();
    }
}
