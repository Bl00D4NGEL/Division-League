<?php

namespace App\Controller;

use App\Models\PlayerModel;
use App\Resource\AddPlayerRequest;
use App\Resource\DeletePlayerRequest;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var PlayerModel */
    private $playerModel;

    public function __construct(SerializerInterface $serializer, PlayerModel $playerModel)
    {
        $this->serializer = $serializer;
        $this->playerModel = $playerModel;
    }

    /**
     * @Route("/player/add", name="player_add")
     * @param Request $request
     * @return JsonResponse
     */
    public function playerAdd(Request $request)
    {
        $req = $this->serializer->deserialize($request->getContent(), AddPlayerRequest::class, 'json');
        return $this->playerModel->addPlayer($req);
    }

    /**
     * @Route("/player/add/confirm", name="player_add_confirm")
     * @param Request $request
     * @return JsonResponse
     */
    public function playerAddConfirm(Request $request)
    {
        $req = $this->serializer->deserialize($request->getContent(), AddPlayerRequest::class, 'json');
        return $this->playerModel->addDeletedPlayer($req);
    }

    /**
     * @Route("/player/get/all", name="player_get_all")
     * @return JsonResponse
     */
    public function playerGetAll()
    {
        return $this->playerModel->getPlayerAll();
    }

    /**
     * @Route("/player", name="player_delete", methods={"DELETE"})
     * @param Request $request
     * @return JsonResponse
     */
    public function playerDelete(Request $request)
    {
        $req = $this->serializer->deserialize($request->getContent(), DeletePlayerRequest::class, 'json');
        return $this->playerModel->playerDelete($req);
    }
}
