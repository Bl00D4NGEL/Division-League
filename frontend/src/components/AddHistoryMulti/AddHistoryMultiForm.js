import React, {useState, Fragment} from 'react';
import CustomForm from "../BaseReactComponents/Form/Form";
import {useOnChangeSetter} from "../../customHooks/useOnChangeSetter";
import SubmitButton from "../BaseReactComponents/SubmitButton/SubmitButton";
import Label from "../BaseReactComponents/Label/Label";
import TextInput from "../BaseReactComponents/TextInput/TextInput";
import MultiPlayerSelect from "./MultiPlayerSelect";
import AddHistoryService from "../../services/AddHistoryService";
import EloChangeDisplayMulti from "../EloChangeDisplayMulti/EloChangeDisplayMulti";
import Error from "../Error/Error";
import TwoColumnLayout from "../TwoColumnLayout";

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
        <div style={{paddingBottom: 20 + 'px'}}>
            <TwoColumnLayout
                left={
                    <Fragment>
                        <h1>Winner</h1>
                        <Label text="Team name (optional)" formField={
                            <TextInput onChange={setWinnerTeamName}/>
                        }/>
                        <MultiPlayerSelect selectedPlayers={selectedWinner}
                                           setSelectedPlayers={setSelectedWinner} players={players}/>
                    </Fragment>
                }
                right={
                    <Fragment>
                        <h1>Loser</h1>
                        <Label text="Team name (optional)" formField={
                            <TextInput onChange={setLoserTeamName}/>
                        }/>
                        <MultiPlayerSelect selectedPlayers={selectedLoser}
                                           setSelectedPlayers={setSelectedLoser} players={players}/>
                    </Fragment>
                }/>
            <div style={{display: 'flex', marginTop: 20 + 'px', marginBottom: 10 + 'px'}}>
                <div>
                    Enter proof url
                </div>
            </div>
            <div style={{display: 'flex', justifyContent: 'space-between', marginBottom: 20 + 'px'}}>
                {
                    proofUrls.map((p, i) => {
                        return <div key={i} style={{paddingBottom: 5 + 'px'}}>
                            <span style={{paddingRight: 10 + 'px'}}>Game {i + 1}</span>
                            <TextInput onChange={e => {
                                const proofs = proofUrls;
                                proofs[i] = e.target.value;
                                setProofUrls(proofs);
                            }}/>
                        </div>
                    })
                }
            </div>
        </div>
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
