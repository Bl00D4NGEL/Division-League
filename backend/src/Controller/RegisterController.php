<?php

namespace App\Controller;

use App\Models\RegisterModel;
use App\Resource\RegisterRequest;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private SerializerInterface $serializer;
    private RegisterModel $registerModel;

    public function __construct(SerializerInterface $serializer, RegisterModel $registerModel)
    {
        $this->serializer = $serializer;
        $this->registerModel = $registerModel;
    }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $req = $this->serializer->deserialize($request->getContent(), RegisterRequest::class, 'json');
        return $this->registerModel->register($req);
    }
}
