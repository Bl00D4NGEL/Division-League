import React, {useState, useEffect} from 'react';
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Config from "../../Config";
import Loader from "../BaseElements/Loader";
import LeagueDisplay from "../LeagueDisplay/LeagueDisplay";
import Sorter from "../../helpers/Sorter/Sorter";

export default function PlayerTable() {
    const [isLoaded, setIsLoaded] = useState(false);
    const [error, setError] = useState(undefined);
    const [players, setPlayers] = useState([]);

    const loadPlayerData = () => CustomRequest(
        Config.getAllPlayersEndpoint(),
        (result) => {
            setIsLoaded(true);
            setPlayers(result.data);
        },
        (error) => {
            setIsLoaded(true);
            setError(error);
        }
    );
    useEffect(loadPlayerData, []);

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