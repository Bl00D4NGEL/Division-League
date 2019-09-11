<?php

namespace App\Controller;

use App\Resource\AddHistoryRequest;
use JMS\Serializer\SerializerInterface;
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
}