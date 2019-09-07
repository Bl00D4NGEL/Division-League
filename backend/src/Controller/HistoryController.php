<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use App\Models\HistoryModel;

class HistoryController extends AbstractController {
    /**
     * @Route("/history/add", name="history_add")
     */
    public function historyAdd(Request $req, HistoryModel $hm) {
        $body = $req->getContent();
        $data = json_decode($body, true);
        return new JsonResponse($hm->addHistory($data['winnerId'], $data['loserId'], $data['proofUrl']));
    }

    /**
     * @Route("/history/get", name="history_get")
     */
    public function historyGet(HistoryModel $hm) {
        return new JsonResponse($hm->getHistory());
    }
}