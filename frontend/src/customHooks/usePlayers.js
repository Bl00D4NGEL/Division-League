import {useState, useEffect} from 'react';
import LoadPlayersService from "../services/LoadPlayersService";

export const usePlayers = () => {
    const [players, setPlayers] = useState([]);
    const [error, setError] = useState(undefined);

    useEffect(() => LoadPlayersService({setPlayers, setError, setIsLoaded: () => {}}), []);

    return {players, error};
};
