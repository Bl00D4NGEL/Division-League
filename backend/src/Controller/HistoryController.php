<?php

namespace App\Controller;

use App\Entity\History;
use App\Models\HistoryModel;
use App\Repository\HistoryRepository;
use App\Resource\AddHistoryRequest;
use App\Resource\JsonResponse\ErrorResponse;
use App\Resource\JsonResponse\SuccessResponse;
use App\ValueObjects\Transformator\HistoryTransformator;
use Exception;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends AbstractController
{
    private SerializerInterface $serializer;
    private HistoryModel $historyModel;
    private HistoryRepository $historyRepository;
    private HistoryTransformator $historyTransformator;

    public function __construct(SerializerInterface $serializer, HistoryModel $historyModel,HistoryRepository $historyRepository, HistoryTransformator $historyTransformator)
    {
        $this->serializer = $serializer;
        $this->historyModel = $historyModel;
        $this->historyRepository = $historyRepository;
        $this->historyTransformator = $historyTransformator;
    }

    /**
     * @Route("/history/add", name="history_add")
     * @param Request $request
     * @return Response
     */
    public function historyAdd(Request $request)
    {
        try {
            return new SuccessResponse(
                [
                    $this->historyTransformator->transform($this->historyModel->addHistory(
                        $this->serializer->deserialize(
                            $request->getContent(),
                            AddHistoryRequest::class,
                            'json'
                        )
                    ))
                ]
            );
        } catch (Exception $e) {
            return new ErrorResponse($e->getMessage());
        }
    }

    /**
     * @Route("/history/get/recent", name="history_get_recent")
     * @return JsonResponse
     */
    public function historyGetRecent()
    {
        return new SuccessResponse(array_map(function (History $history) {
            return $this->historyTransformator->transform($history);
        }, $this->historyRepository->findLastEntries(35)));
    }

    /**
     * @Route("/history/get/all", name="history_get_all")
     * @return JsonResponse
     */
    public function historyGetAll()
    {
        return new SuccessResponse(array_map(function (History $history) {
            return $this->historyTransformator->transform($history);
        }, $this->historyRepository->findAll()));
    }
}
