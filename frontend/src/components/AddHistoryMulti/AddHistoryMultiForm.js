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
import MultiPlayerSelect from "./MultiPlayerSelect";

export default function AddHistoryMultiForm({players}) {
    const [winner, setWinner] = useOnChangeSetter(undefined, parseInt);
    const [loser, setLoser] = useOnChangeSetter(undefined, parseInt);
    const [proofUrl, setProofUrl] = useOnChangeSetter(undefined);
    const [changes, setChanges] = useState(undefined);

    const setDefaults = () => {
        if (players.length > 1) {
            setWinner(players[0].id);
            setLoser(players[1].id);
        }
        setChanges(undefined);
    };
    useEffect(setDefaults, [players]);

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

    const renderChanges = () => {
        return changes !== undefined ?
            <EloChangeDisplay
                loser={generateChangeDisplayObjectFor(getPlayerById(players, changes.loser.id), changes.loser.elo)}
                winner={generateChangeDisplayObjectFor(getPlayerById(players, changes.winner.id), changes.winner.elo)}
            />
            : <div/>
    };

    return <CustomForm onSubmit={handleSubmit} formFields={
        <div>
            <MultiPlayerSelect RenderComponent={WinnerSelect} players={players}/>
            <MultiPlayerSelect RenderComponent={LoserSelect} players={players}/>
            <Label text="Enter proof url" formField={
                <TextInput onChange={setProofUrl}/>
            }/>
            {generateWarnings({winner, loser})}
            <SubmitButton value="Add history"/>
            {renderChanges()}
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
