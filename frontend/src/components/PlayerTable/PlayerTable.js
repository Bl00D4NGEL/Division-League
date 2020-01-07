import React from 'react';
import LeagueDisplay from "../LeagueDisplay/LeagueDisplay";
import Sorter from "../../helpers/Sorter/Sorter";
import Loader from "../BaseReactComponents/Loader/Loader";
import {usePlayers} from "../../customHooks/usePlayers";

export default function PlayerTable() {
    const {players, error} = usePlayers();

    const generateLeagueDisplays = () => {
        return Sorter(getLeagueData(), 'league').map((league) => {
            return <LeagueDisplay key={league.league} players={league.players} leagueName={league.league}/>
        });
    };

    const getLeagueData = () => {
        return players
            .map(p => p.league)
            .filter((item, i, ar) => ar.indexOf(item) === i)
            .map(league => ({
                league,
                players: players.filter(p => p.league === league)
            }));
    };

    return <Loader
        isLoaded={players.length !== 0}
        error={error}
        content={
            generateLeagueDisplays()
        }
    />
}
