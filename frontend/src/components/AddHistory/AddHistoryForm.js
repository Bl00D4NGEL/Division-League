import React, {useState, useEffect} from 'react';
import CustomForm from "../BaseReactComponents/Form/Form";
import {useOnChangeSetter} from "../../customHooks/useOnChangeSetter";
import SubmitButton from "../BaseReactComponents/SubmitButton/SubmitButton";
import Label from "../BaseReactComponents/Label/Label";
import TextInput from "../BaseReactComponents/TextInput/TextInput";
import LoserSelect from "../PlayerSelect/LoserSelect";
import WinnerSelect from "../PlayerSelect/WinnerSelect";
import EqualPlayerWarning from "../Warning/EqualPlayerWarning";
import AddHistoryService from "../../services/AddHistoryService";
import EloChangeDisplay from "../EloChangeDisplay/EloChangeDisplay";

export default function AddHistoryForm({players}) {
    const [winner, setWinner] = useOnChangeSetter(undefined, parseInt);
    const [loser, setLoser] = useOnChangeSetter(undefined, parseInt);
    const [proofUrl, setProofUrl] = useOnChangeSetter(undefined);
    const [changes, setChanges] = useState(undefined);

    useEffect(() => {
        if (players.length > 1) {
            setWinner(players[0].id);
            setLoser(players[1].id);
        }
        setChanges(undefined);
    }, [players]);

    const handleSubmit = e => {
        e.preventDefault();
        AddHistoryService({
            setIsLoaded: isLoaded => console.log("Is loaded", isLoaded),
            setError: error => console.log("Error", error),
            setChanges,
            winner,
            loser,
            proofUrl
        })
    };

    return <CustomForm onSubmit={handleSubmit} formFields={
        <div>
            <WinnerSelect players={players} value={winner} onChange={setWinner}/>
            <LoserSelect players={players} value={loser} onChange={setLoser}/>
            <Label text="Enter proof url" formField={
                <TextInput onChange={setProofUrl}/>
            }/>
            {generateWarnings({winner, loser})}
            <SubmitButton value="Add history"/>
            {
                changes !== undefined ?
                    <EloChangeDisplay
                        loser={generateChangeDisplayObjectFor(getPlayerById(players, changes.loser.id), changes.loser.elo)}
                        winner={generateChangeDisplayObjectFor(getPlayerById(players, changes.winner.id), changes.winner.elo)}
                    />
                    : <div/>
            }
        </div>
    }/>
}

const generateChangeDisplayObjectFor = (player = {name: 'Unknown', elo: 0}, toElo = 0) => ({
    name: player.name,
    fromElo: player.elo,
    toElo
});

const generateWarnings = ({winner, loser}) => {
    return winner === loser ? <EqualPlayerWarning/> : <div/>;
};

const getPlayerById = (players, playerId) => {
    const filtered = players.filter(p => p.id === playerId);
    return filtered.length > 0 ? filtered[0] : undefined;
};
