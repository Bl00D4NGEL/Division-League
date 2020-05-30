<?php

namespace App\Models;

use App\Entity\Player;
use App\Repository\ParticipantRepository;
use App\Repository\PlayerRepository;
use App\Resource\JsonResponse\SuccessResponse;
use App\ValueObjects\Grouper\DateTime\DateTimeGrouperInterface;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatisticModel
{
    private ParticipantRepository $participantRepository;
    private PlayerRepository $playerRepository;
    private DateTimeGrouperInterface $dateTimeGrouperYearWeek;

    public function __construct(PlayerRepository $playerRepository, ParticipantRepository $participantRepository, DateTimeGrouperInterface $dateTimeGrouperYearWeek)
    {
        $this->participantRepository = $participantRepository;
        $this->playerRepository = $playerRepository;
        $this->dateTimeGrouperYearWeek = $dateTimeGrouperYearWeek;
    }

    public function getGamesPerWeek(): JsonResponse
    {
        $players = $this->playerRepository->getCurrentlyActivePlayers();
        $statistics = [];
        /** @var Player $player */
        foreach($players as $player) {
            $dates = $this->participantRepository->getHistoryTimesForPlayer($player);
            $grouped = $this->dateTimeGrouperYearWeek->group(
                $dates
            );

            foreach ($grouped as $groupKey => $value) {
                if (!isset($statistics[$groupKey])) {
                    $statistics[$groupKey] = [];
                }

                $statistics[$groupKey][$player->getName()] = $value;
            }
        }

        $out = [];

        foreach ($statistics as $dateKey => $players) {
            $date = new DateTimeImmutable();
            $date = $date->setISODate(explode('-', $dateKey)[0], explode('-', $dateKey)[1]);
            $out[] = [
                'from' => $date->format('Y-m-d'),
                'to' => $date->modify('+6 days')->format('Y-m-d'),
                'players' => $players
            ];
        }

        return new SuccessResponse($out);
    }
}
