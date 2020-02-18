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

const MAX_GAMES_FOR_BEST_OF_N = 3;
const defaultProofUrls = Array(MAX_GAMES_FOR_BEST_OF_N).fill('');

export default function AddHistoryMultiForm({players}) {
    const [proofUrls, setProofUrls] = useState(defaultProofUrls);
    const [selectedWinner, _setSelectedWinner] = useState([]);
    const [winnerTeamName, setWinnerTeamName] = useOnChangeSetter('');
    const [selectedLoser, _setSelectedLoser] = useState([]);
    const [loserTeamName, setLoserTeamName] = useOnChangeSetter('');
    const [changes, _setChanges] = useState(undefined);
    const [error, setError] = useState(undefined);

    const setSelectedWinner = val => {
        if (val.length > selectedWinner.length) {
            _setSelectedLoser(
                [
                    ...selectedLoser,
                    val[val.length - 1]
                ]
            );
        }
        _setSelectedWinner(val);
    };

    const setSelectedLoser = val => {
        if (val.length > selectedLoser.length) {
            _setSelectedWinner(
                [
                    ...selectedWinner,
                    val[val.length - 1]
                ]
            );
        }
        _setSelectedLoser(val);
    };

    const setChanges = val => {
        if (val !== undefined) {
            setError(undefined);
            setProofUrls(defaultProofUrls);
            _setChanges(val);
        }
    };

    const getFilteredUrls = () => {
        return proofUrls.filter(p => p !== '');
    };

    const handleSubmit = e => {
        e.preventDefault();
        AddHistoryService({
            winner: selectedWinner,
            loser: selectedLoser,
            winnerTeamName,
            loserTeamName,
            proofUrl: getFilteredUrls(),
            setChanges,
            setError
        });
    };

    return <div>
        <CustomForm onSubmit={handleSubmit} formFields={
            <div style={{paddingBottom: 20 + 'px'}}>
                <div>
                    <Label text="Winner Team name (optional)" formField={
                        <TextInput onChange={setWinnerTeamName}/>
                    }/>
                    <MultiPlayerSelect RenderComponent={WinnerSelect} selectedPlayers={selectedWinner}
                                       setSelectedPlayers={setSelectedWinner} players={players}/>
                </div>
                <div>
                    <Label text="Loser Team name (optional)" formField={
                        <TextInput onChange={setLoserTeamName}/>
                    }/>
                    <MultiPlayerSelect RenderComponent={LoserSelect} selectedPlayers={selectedLoser}
                                       setSelectedPlayers={setSelectedLoser} players={players}/>
                </div>
                <div style={{display: 'flex'}}>
                    <div>
                        Enter proof url
                    </div>
                </div>
                <div>
                    {
                        proofUrls.map((p, i) => {
                            return <div key={i} style={{paddingBottom: 5 + 'px'}}>
                                <span style={{paddingRight: 5 + 'px'}}>Game {i + 1}</span>
                                <TextInput required="required" onChange={e => {
                                    const proofs = proofUrls;
                                    proofs[i] = e.target.value;
                                    setProofUrls(proofs);
                                    console.log(proofUrls, getFilteredUrls())
                                }}/>
                            </div>
                        })
                    }
                </div>
                <SubmitButton value="Add history"/>
            </div>
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
