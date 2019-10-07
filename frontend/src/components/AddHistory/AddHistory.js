import React, {useEffect, useState} from 'react';
import Loader from "../BaseElements/Loader";
import AddHistoryForm from "./AddHistoryForm";
import LoadPlayers from "../../services/LoadPlayers";

export default function AddHistory() {
    const [players, _setPlayers] = useState([]);
    const [winner, setWinner] = useState(undefined);
    const [loser, setLoser] = useState(undefined);
    const [isLoaded, setIsLoaded] = useState(false);
    const [error, setError] = useState(undefined);

    const setPlayers = (players) => {
        setWinner(players[0]);
        setLoser(players[1]);
        _setPlayers(players);
    };
    useEffect(() => LoadPlayers({setPlayers, setError, setIsLoaded}), []);

    return (
        <Loader
            isLoaded={isLoaded}
            error={error}
            content={
                <AddHistoryForm
                    winner={winner}
                    setWinner={setWinner}
                    loser={loser}
                    setLoser={setLoser}
                    players={players}
                />
            }
        />
    );
}