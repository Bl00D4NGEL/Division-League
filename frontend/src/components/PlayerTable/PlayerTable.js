import React from 'react';
import LeagueDisplay from "../LeagueDisplay/LeagueDisplay";
import Sorter from "../../helpers/Sorter/Sorter";
import Loader from "../BaseReactComponents/Loader/Loader";
import {usePlayers} from "../../customHooks/usePlayers";

export default function PlayerTable({...props}) {
    const {players, error} = usePlayers();

    const generateLeagueDisplays = () => {
        return Sorter(getLeagueData(), 'league').map((league) => {
            return <LeagueDisplay {...props} key={league.league} players={league.players} leagueName={league.league}/>
        });
    };

    const getLeagueData = () => {
        return players
            .map(p => p.league)
            .filter((item, i, ar) => ar.indexOf(item) === i)
            .map(league => ({
                league,
                players: addRankForPlayers(players.filter(p => p.league === league))
            }));
    };

    const addRankForPlayers = ps => {
        let currentRank = 1;
        let lastElo = undefined;
        return ps
            .sort((a, b) => a.elo > b.elo ? -1 : 1)
            .map(p => {
                if (lastElo === undefined) {
                    lastElo = p.elo;
                } else if (lastElo !== p.elo) {
                    currentRank++;
                    lastElo = p.elo;
                }
                return {
                    ...p,
                    rank: currentRank
                }
            });
    };

    return <Loader
        isLoaded={players.length !== 0}
        error={error}
        content={
            generateLeagueDisplays()
        }
    />
}
