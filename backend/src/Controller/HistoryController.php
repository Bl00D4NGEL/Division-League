<?php

namespace App\Controller;

use App\Resource\AddHistoryRequest;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use App\Models\HistoryModel;

class HistoryController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var HistoryModel */
    private $historyModel;

    public function __construct(SerializerInterface $serializer, HistoryModel $historyModel)
    {
        $this->serializer = $serializer;
        $this->historyModel = $historyModel;
    }

    /**
     * @Route("/history/addMulti", name="history_add_multi")
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function historyAddMulti(Request $request)
    {
        return $this->historyModel->addHistory($this->serializer->deserialize($request->getContent(), AddHistoryRequest::class, 'json'));
    }

    /**
     * @Route("/history/get/all", name="history_get_all")
     * @return JsonResponse
     */
    public function historyGetAll()
    {
        return new JsonResponse([
            "status" => "OK"
        ]); // $this->historyModel->getHistoryAll();
    }


    /**
     * @Route("/history/get/recent", name="history_get_all")
     * @return JsonResponse
     */
    public function historyGetRecent()
    {
        return $this->historyModel->getHistoryRecent();
    }
}
