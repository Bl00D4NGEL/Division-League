import React, {useState} from 'react';
import CustomForm from "../BaseReactComponents/Form/Form";
import {useOnChangeSetter} from "../../customHooks/useOnChangeSetter";
import SubmitButton from "../BaseReactComponents/SubmitButton/SubmitButton";
import Label from "../BaseReactComponents/Label/Label";
import TextInput from "../BaseReactComponents/TextInput/TextInput";
import LoserSelect from "../PlayerSelect/LoserSelect";
import WinnerSelect from "../PlayerSelect/WinnerSelect";
import MultiPlayerSelect from "./MultiPlayerSelect";
import AddHistoryService from "../../services/AddHistoryService";
import EloChangeDisplayMulti from "../EloChangeDisplayMulti/EloChangeDisplayMulti";
import Error from "../Error/Error";

export default function AddHistoryMultiForm({players}) {
    const [proofUrl, setProofUrl] = useOnChangeSetter(undefined);
    const [selectedWinner, setSelectedWinner] = useState([]);
    const [winnerTeamName, setWinnerTeamName] = useOnChangeSetter('');
    const [selectedLoser, setSelectedLoser] = useState([]);
    const [loserTeamName, setLoserTeamName] = useOnChangeSetter('');
    const [changes, _setChanges] = useState(undefined);
    const [error, setError] = useState(undefined);

    const setChanges = val => {
        if (val !== undefined) {
            setError(undefined);
            setProofUrl('');
            _setChanges(val);
        }
    };

    const handleSubmit = e => {
        e.preventDefault();
        if (proofUrl === '' || proofUrl === undefined) {
            setError('Proof URL must be set');
            return;
        }
        AddHistoryService({
            winner: selectedWinner,
            loser: selectedLoser,
            winnerTeamName,
            loserTeamName,
            proofUrl,
            setChanges,
            setError
        });
    };

    return <div style={{paddingBottom: 20 + 'px'}}>
        <div>
            <Label text="Winner Team name (optional)" formField={
                <TextInput onChange={setWinnerTeamName}/>
            }/>
            <MultiPlayerSelect RenderComponent={WinnerSelect} setSelectedPlayers={setSelectedWinner} players={players}/>
        </div>
        <div>
            <Label text="Loser Team name (optional)" formField={
                <TextInput onChange={setLoserTeamName}/>
            }/>
            <MultiPlayerSelect RenderComponent={LoserSelect} setSelectedPlayers={setSelectedLoser} players={players}/>
        </div>
        <Label text="Enter proof url" formField={
            <TextInput required="required" value={proofUrl} onChange={setProofUrl}/>
        }/>
        <CustomForm onSubmit={handleSubmit} formFields={
            <SubmitButton value="Add history"/>
        }/>
        {
            error !== undefined ? <Error message={error}/> : <div/>
        }
        {
            changes !== undefined ?
                <EloChangeDisplayMulti
                    loser={{
                        name: loserTeamName,
                        players: changes.loser,
                        change: changes.loserEloLose
                    }}
                    winner={{
                        name: winnerTeamName,
                        players: changes.winner,
                        change: changes.winnerEloWin
                    }}
                />
                : <div/>
        }
    </div>
}
