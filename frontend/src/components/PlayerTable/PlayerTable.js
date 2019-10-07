import React, {useState, useEffect} from 'react';
import Loader from "../BaseElements/Loader";
import LeagueDisplay from "../LeagueDisplay/LeagueDisplay";
import Sorter from "../../helpers/Sorter/Sorter";
import LoadPlayers from "../../services/LoadPlayers";

export default function PlayerTable() {
    const [isLoaded, setIsLoaded] = useState(false);
    const [error, setError] = useState(undefined);
    const [players, setPlayers] = useState([]);

    useEffect(() => LoadPlayers({setIsLoaded, setError, setPlayers}), []);

    const generateLeagueDisplays = () => {
        return Sorter(getLeagueData(), 'league').map((league) => {
            return <LeagueDisplay key={league.league} players={league.players} leagueName={league.league}/>
        });
    };

    const getLeagueData = () => {
        const leagues = players
            .map((p) => {return p.league})
            .filter((item, i, ar) => ar.indexOf(item) === i);
        return leagues.map((league) => {
            return {
                league: league,
                players: players.filter(item => item.league === league)
        }
        });
    };

    return <Loader
        isLoaded={isLoaded}
        error={error}
        content={
            generateLeagueDisplays()
        }
    />
}