import React, {useEffect, useState} from 'react';
import Loader from "../BaseElements/Loader";
import AddHistoryForm from "./AddHistoryForm";
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Config from "../../Config";

export default function AddHistory() {
    const [players, setPlayers] = useState([]);
    const [winner, setWinner] = useState(undefined);
    const [loser, setLoser] = useState(undefined);
    const [isLoaded, setIsLoaded] = useState(false);
    const [error, setError] = useState(undefined);

    const loadPlayerData = () => CustomRequest(
        Config.getAllPlayersEndpoint(),
        (result) => {
            setPlayers(result.data);
            setWinner(result.data[0]);
            setLoser(result.data[1]);
            setIsLoaded(true);
        },
        (error) => {
            setIsLoaded(true);
            setError(error);
        }
    );

    useEffect(loadPlayerData, []);

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