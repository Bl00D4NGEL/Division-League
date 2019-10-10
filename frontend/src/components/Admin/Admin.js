import React from 'react';
import Button from "../BaseElements/Button";
import LoadPlayersFromMdrService from "../../services/LoadPlayersFromMdrService";

export default function Admin() {
    const loadPlayers = () => {
        LoadPlayersFromMdrService({
            division: 'xxii',
            setError: () => {},
            setIsLoaded: () => {},
            setPlayers: (players) => {console.log(players)}
        });
    };

    return <div>
        Admin
        <Button text="Load Players" onClick={loadPlayers}/>
    </div>;
}