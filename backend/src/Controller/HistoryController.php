<?php

namespace App\Controller;

use App\Entity\History;
use App\Resource\AddHistoryRequest;
use App\Resource\GetHistoryRequest;
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
     * @Route("/history/add", name="history_add")
     * @param Request $request
     * @return JsonResponse
     */
    public function historyAdd(Request $request)
    {
        $req = $this->serializer->deserialize($request->getContent(), AddHistoryRequest::class, 'json');
        return $this->historyModel->addHistory($req);
    }

    /**
     * @Route("/history/get/all", name="history_get_all")
     * @return JsonResponse
     */
    public function historyGetAll()
    {
        return $this->historyModel->getHistoryAll();
    }


    /**
     * @Route("/history/get/recent", name="history_get_all")
     * @return JsonResponse
     */
    public function historyGetRecent()
    {
        return $this->historyModel->getHistoryRecent();
    }

    /**
     * @Route("/history/get/filter", name="history_get")
     * @param Request $request
     * @return Response
     */
    public function historyGet(Request $request) {
        $history = $this->serializer->deserialize($request->getContent(), History::class, 'json');
        $req = $this->serializer->deserialize($request->getContent(), GetHistoryRequest::class, 'json');
        $req->history = $history;
        return $this->historyModel->getHistory($req);
    }
}