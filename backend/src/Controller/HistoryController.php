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
        $winnerId = $data['winnerId'];
        $loserId = $data['loserId'];
        return new JsonResponse($hm->addHistory($winnerId, $loserId));
    }

    /**
     * @Route("/history/get", name="history_get")
     */
    public function historyGet(HistoryModel $hm) {
        return new JsonResponse($hm->getHistory());
    }
}