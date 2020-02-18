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
     * @Route("/history/add", name="history_add")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function historyAdd(Request $request)
    {
        return $this->historyModel->addHistory($this->serializer->deserialize($request->getContent(), AddHistoryRequest::class, 'json'));
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
