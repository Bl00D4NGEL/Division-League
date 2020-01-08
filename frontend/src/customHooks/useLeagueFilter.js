import {useState, useEffect} from 'react';
import {useOnChangeSetter} from "./useOnChangeSetter";

export const useLeagueFilter = ({players}) => {
    const [league, setLeague] = useOnChangeSetter();
    const [filteredPlayers, setFilteredPlayers] = useState([]);
    const leagues = getLeaguesFromPlayers(players);

    useEffect(() => {
        setFilteredPlayers(players.filter(p => p.league === league));
    }, [league, players]);

    return {league, setLeague, leagues, filteredPlayers};
};

const getLeaguesFromPlayers = players => {
    return players.reduce((prev, curr) => {
        if (Array.isArray(prev) && !prev.includes(curr.league)) {
            prev.push(curr.league);
        }
        return prev;
    }, []).sort();
};
