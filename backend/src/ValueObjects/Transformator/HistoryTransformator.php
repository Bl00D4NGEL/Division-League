<?php declare(strict_types=1);

namespace App\ValueObjects\Transformator;

use App\Entity\History;
use App\Entity\Participant;
use App\Entity\Proof;

final class HistoryTransformator
{
    public function transform(History $history): array
    {
        $winners = [];
        $losers = [];

        /** @var Participant $participant */
        foreach ($history->getParticipants()->getValues() as $participant) {
            if ($participant->getEloChange() > 0) {
                $winners[] = $participant;
            } else {
                $losers[] = $participant;
            }
        }

        return [
            'id' => $history->getId(),
            'creationTime' => $history->getCreationTime()->getTimestamp(),
            'winner' => array_map([$this, 'formatParticipant'], $winners),
            'loser' => array_map([$this, 'formatParticipant'], $losers),
            'isSweep' => $history->getIsSweep(),
            'proofs' => array_map(static function (Proof $proof) {
                return $proof->getUrl();
            }, $history->getProof()->getValues())
        ];
    }

    private function formatParticipant(Participant $participant): array
    {
        return [
            'name' => $participant->getPlayer()->getName(),
            'league' => $participant->getPlayer()->getLeague(),
            'eloChange' => $participant->getEloChange()
        ];
    }
}
